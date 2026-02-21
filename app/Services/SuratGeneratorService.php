<?php

namespace App\Services;

use App\Models\Clinic;
use App\Models\Transaksi;
use App\Models\Patient;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;
use Novay\Word\Facades\Word;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;


class SuratGeneratorService
{

    public function tambah($request)
    {
        if ($request->filled('foto')) {

            $manager = new ImageManager(new Driver());

            $image = $request->input('foto');

            // hapus prefix base64 (auto detect png/jpg/webp)
            $image = preg_replace('#^data:image/\w+;base64,#i', '', $image);
            $image = str_replace(' ', '+', $image);

            $decodedImage = base64_decode($image);

            $img = $manager->read($decodedImage)
                ->scale(width: 800)     // resize max width 800px
                ->toWebp(70);           // compress 70%

            $imageName = 'registration/' . uniqid() . '.webp';

            Storage::disk('public')->put($imageName, $img);

            $request->merge([
                'foto' => $imageName
            ]);
        }

        if ($request->is_bayar == true) {
            $request->merge([
                'is_tagih' => true
            ]);
        }
        DB::beginTransaction();
        try {
            $data = Transaksi::tambahData($request->except('_token', '_method'));
            $result = Transaksi::with('paramedis.clinic', 'patient')->find($data)->toArray();
            $pdf = Pdf::loadView('surat-generator.pdf', ['data' => $result])->setPaper('A4', 'portait');
            DB::commit();
            // return $pdf->stream($result['patient']['nama_pasien'] . '_' . $result['patient']['no_ktp'] . '_' . $result['tgl_transaksi'] . '.pdf');
            // return $pdf->stream('surat-generator_' . now()->format('dmyHis') . '.pdf');
            toastify()->success('Data Berhasil Ditambahlan.');
            return redirect()->route('surat.index');
        } catch (\Throwable $th) {
            toastify()->error('Error, ' . $th);
            return redirect()->back();
            DB::rollback();
        }
    }

    public function edit($id, $request)
    {
        DB::beginTransaction();
        try {
            // kalau ada foto base64 (kamera)
            if ($request->filled('foto') && str_starts_with($request->input('foto'), 'data:image')) {
                // base64 dari kamera
                $image = $request->input('foto');
                $image = preg_replace('/^data:image\/\w+;base64,/', '', $image);
                $imageName = 'registration/' . uniqid() . '.png';
                Storage::disk('public')->put($imageName, base64_decode($image));
                $request->merge(['foto' => $imageName]);
            } elseif ($request->hasFile('foto') && $request->file('foto')->isValid()) {
                // file upload manual
                $file = $request->file('foto');
                $imageName = 'registration/' . uniqid() . '.' . $file->getClientOriginalExtension();
                $file->storeAs('public', $imageName);
                $request->merge(['foto' => $imageName]);
            } else {
                // tidak ganti foto
                $request->request->remove('foto');
            }


            Transaksi::editData($id, $request->except('_method', '_token'));
            toastify()->success('Data Berhasil diedit.');
            DB::commit();

            return redirect()->route('surat.index');
        } catch (\Throwable $th) {
            DB::rollBack();
            toastify()->error('Error: ' . $th->getMessage());
            return redirect()->back();
        }
    }


    public function hapus($id)
    {
        DB::beginTransaction();
        try {
            Transaksi::hapusData($id);
            toastify()->success('Data Berhasil Dihapus.');
            DB::commit();
            return redirect()->route('surat.index');
        } catch (\Throwable $th) {
            toastify()->error('Error, ' . $th);
            DB::rollback();
            return redirect()->back();
        }
    }

    public function pdf($id)
    {
        DB::beginTransaction();
        try {
            $result = Transaksi::showDatabyAudit($id);
            $pdf = Pdf::loadView('surat-generator.pdf', ['data' => $result])->setPaper([0, 0, 419.53, 595.28], 'portrait');
            DB::commit();
            return $pdf->stream($result['patient']['nama_pasien'] . '_' . $result['patient']['no_ktp'] . '_' . $result['tgl_transaksi'] . '.pdf');
            // toastify()->success('Data Berhasil Ditambahlan.');
            // return redirect()->route('surat.index');
        } catch (\Throwable $th) {
            toastify()->error('Error, ' . $th);
            return redirect()->back();
            DB::rollback();
        }
    }

    public function generateNoTransaksi($patient_id)
    {
        $patient = Patient::with('clinic')->findOrFail($patient_id);

        $clinic = $patient->clinic;
        if (!$clinic) {
            return response()->json(['error' => 'Clinic tidak ditemukan'], 404);
        }

        $kodeKlinik = $clinic->kode ?? 'XXX';
        $count = Transaksi::withTrashed()
            ->whereHas('patient', function ($q) use ($clinic) {
                $q->where('clinic_id', $clinic->id);
            })->count();

        $nomorUrut = str_pad($count + 1, 3, '0', STR_PAD_LEFT);
        $bulan = date('m');
        $tahun = date('Y');

        $noTransaksi = "SKS.{$nomorUrut}/{$kodeKlinik}/{$bulan}{$tahun}";

        return response()->json(['no_transaksi' => $noTransaksi]);
    }

    public function resultData($request)
    {
        $user = Auth::user();
        if ($user->role == '1') {
            $clinicId = $user->clinic_id;
            $query = Transaksi::showData($clinicId);
        } else if ($user->role == '0') {
            $customerid = $user->customer_id;
            $query = Transaksi::showDataCustomer($customerid);
        } else {
            $query = Transaksi::showData();
        }

        if ($request->filled('dari') && $request->filled('sampai') && $request->has('agent_id')) {
            $query->whereBetween('tgl_transaksi', [$request->dari, $request->sampai]);
            $query->when(
                $request->agent_id && $request->agent_id !== 'without' && $request->agent_id !== 'all',
                fn($q) => $q->where('agent_id', $request->agent_id)
            )->when(
                $request->agent_id === 'without',
                fn($q) => $q->whereNull('agent_id')
            );
        }

        return DataTables::eloquent($query)
            ->addIndexColumn()
            ->editColumn('tgl_transaksi', function ($row) {
                return Carbon::parse($row->tgl_transaksi)->locale('id')->isoFormat('D MMMM Y');
            })
            ->addColumn('clinic', fn($row) => $row->patient?->clinic?->nama_klinik ?? '-')
            ->addColumn('customer', fn($row) => $row->patient?->customer?->nama_perusahaan ?? '-')
            ->addColumn('paramedis', fn($row) => $row->paramedis?->nama ?? '-')
            ->toJson();
    }

    public function suratBlangkoPdf()
    {
        return pdf::loadView('surat-generator.blangko', ['data' => Clinic::where('id', Auth::user()->clinic_id)->first()])->stream('SKD_blangko.pdf');
    }

    public function suratBlangkoDocx()
    {
        // $data = Clinic::where('id', Auth::user()->clinic_id)->first();
        return Word::template(asset('storage/word/skd.docx'))
            ->download(now()->format('d-m-Y_His') . 'template.docx');
    }
}
