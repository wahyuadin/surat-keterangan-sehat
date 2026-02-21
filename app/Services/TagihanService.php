<?php

namespace App\Services;

use App\Models\Clinic;
use App\Models\Customer;
use App\Models\Tagihan;
use App\Models\Transaksi;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class TagihanService
{
    public function tambah($request)
    {
        $transaksi = Transaksi::with('patient.customer', 'agent')
            ->whereBetween('tgl_transaksi', [$request->tgl_mulai, $request->tgl_sampai])
            ->where('is_tagih', false)
            ->where('is_bayar', false)
            ->whereHas('patient', function ($q) use ($request) {
                $q->where('customer_id', $request->customer_id)
                    ->where('clinic_id', $request->clinic_id);
            });

        if ($request->agent_id == 'all') {
            $transaksi->whereNotNull('agent_id');
        } elseif ($request->agent_id == 'without') {
            $transaksi->whereNull('agent_id');
        } else {
            $transaksi->where('agent_id', $request->agent_id);
        }

        $transaksi = $transaksi->get();
        if ($transaksi->isEmpty()) {
            toastify()->error('Error, ' . 'Tidak ada transaksi yang bisa ditagihkan.');
            return redirect()->back();
        }

        // Generate nomor tagihan
        $clinic     = Clinic::findOrFail($request->clinic_id);
        $kodeKlinik = $clinic->kode ?? 'NYK';
        $count      = Tagihan::withTrashed()->count() + 1;
        $nomorUrut  = str_pad($count, 4, '0', STR_PAD_LEFT);
        $bulan      = date('m');
        $tahun      = date('Y');
        $nomorTagihan = "{$nomorUrut}/INV/{$kodeKlinik}/{$bulan}/{$tahun}";

        // Ambil tarif satuan dari customer
        $customer   = Customer::findOrFail($request->customer_id);
        $tarifSatuan = str_replace(',', '', $customer->tarif);

        // Data pasien
        $pasienData = $transaksi->map(function ($item) use ($tarifSatuan) {
            return [
                'no_transaksi'  => $item->no_transaksi,
                'tgl_transaksi' => $item->tgl_transaksi,
                'nama'          => $item->patient->nama_pasien,
                'no_ktp'        => $item->patient->no_ktp,
                'perusahaan'    => $item->patient->customer->nama_perusahaan ?? '-',
                'agent'         => Str::upper($item->agent->nama_agent ?? '-') ?? '-',
                'tagihan'       => $tarifSatuan,
            ];
        });

        try {
            DB::beginTransaction();
            $qty = $pasienData->count();
            Tagihan::create([
                'clinic_id'      => $request->clinic_id,
                'customer_id'    => $request->customer_id,
                'nomor_tagihan'  => $nomorTagihan,
                'dari'           => $request->tgl_mulai,
                'sampai'         => $request->tgl_sampai,
                'qty'            => $qty,
                'satuan'         => $tarifSatuan,
                'pasien'         => $pasienData,
                'status_tagihan' => false,
            ]);
            $transaksi->each->update(['is_tagih' => true]);
            DB::commit();

            toastify()->success("Tagihan {$nomorTagihan} berhasil dibuat.");
            return redirect()->back();
        } catch (\Throwable $th) {
            DB::rollBack();
            toastify()->error('Error, ' . $th->getMessage());
            return redirect()->back();
        }
    }

    public function pdf($id)
    {
        $pdf = Pdf::loadView('tagihan.pdf', ['data' => Tagihan::showData($id)])->setPaper('A4', 'landscape');
        return $pdf->stream('invoice_' . now()->format('dmyHis') . '.pdf');
    }

    public function bayar($id)
    {
        DB::beginTransaction();
        try {
            $tagihan = Tagihan::findOrFail($id);
            $pasienData = $tagihan->pasien ?? [];
            $noTransaksi = collect($pasienData)->pluck('no_transaksi');
            Transaksi::whereIn('no_transaksi', $noTransaksi)
                ->update([
                    'is_tagih' => true,
                    'is_bayar' => true,
                ]);
            Tagihan::editData($id);

            DB::commit();

            toastify()->success('Tagihan berhasil dibayar.');
            return redirect()->back();
        } catch (\Throwable $th) {
            DB::rollBack();
            toastify()->error('Error, ' . $th->getMessage());
            return redirect()->back();
        }
    }


    public function hapus($id)
    {
        DB::beginTransaction();
        try {
            $tagihan = Tagihan::findOrFail($id);
            $pasienData = $tagihan->pasien;
            $noTransaksi = collect($pasienData)->pluck('no_transaksi');
            Transaksi::whereIn('no_transaksi', $noTransaksi)->update([
                'is_tagih'      => false,
                'is_bayar'      => false,
            ]);
            Tagihan::hapusData($id);

            DB::commit();
            toastify()->success('Data Berhasil Dihapus.');
            return redirect()->back();
        } catch (\Throwable $th) {
            DB::rollback();
            toastify()->error('Error, ' . $th->getMessage());
            return redirect()->back();
        }
    }


    public function getPasien($request)
    {
        $query = Transaksi::with('patient.customer', 'agent')
            ->whereBetween('tgl_transaksi', [$request->tgl_mulai, $request->tgl_sampai])
            ->where('is_tagih', false)
            ->where('is_bayar', false);


        if ($request->customer_id) {
            $query->whereHas('patient', function ($q) use ($request) {
                $q->where('customer_id', $request->customer_id);
            });
        }

        if ($request->agent_id == 'all') {
            $query->whereNotNull('agent_id');
        } elseif ($request->agent_id == 'without') {
            $query->whereNull('agent_id');
        } else {
            $query->where('agent_id', $request->agent_id);
        }

        // if ($request->agent_id === 1) {
        //         $query->whereNotNull('agent_id');
        //     } elseif ($request->agent_id === 0) {
        //         $query->whereNull('agent_id');
        //     } else {
        //         $query->where('agent_id', $request->agent_id);
        //     }

        if ($request->clinic_id) {
            $query->whereHas('patient', function ($q) use ($request) {
                $q->where('clinic_id', $request->clinic_id);
            });
        }

        $data = $query->get()->map(function ($item) {
            return [
                'nama'              => $item->patient->nama_pasien ?? '-',
                'no_transaksi'      => $item->no_transaksi ?? '-',
                'tgl_transaksi'     => $item->tgl_transaksi ?? '-',
                'no_ktp'            => $item->patient->no_ktp ?? '-',
                'alamat'            => $item->patient->alamat ?? '-',
                'perusahaan'        => $item->patient->customer->nama_perusahaan ?? '-',
                'agent'             => Str::upper($item->agent->nama_agent ?? '-') ?? '-',
                'tagihan'           => str_replace(',', '', $item->patient->customer->tarif) ?? 0
            ];
        });

        return response()->json($data);
    }

    public function generateNoTagihan($clinic_id)
    {

        $clinic     = Clinic::findOrFail($clinic_id);
        $kodeKlinik = $clinic->kode ?? 'NYK';
        $count      = Tagihan::withTrashed()->count() + 1;
        $nomorUrut  = str_pad($count, 4, '0', STR_PAD_LEFT);
        $bulan      = date('m');
        $tahun      = date('Y');

        $noTransaksi = "{$nomorUrut}/INV/{$kodeKlinik}/{$bulan}/{$tahun}";

        return response()->json(['no_tagihan' => $noTransaksi]);
    }

    // public function getData($request)
    // {
    //     $query = Transaksi::with(['patient.clinic', 'patient.customer', 'paramedis']);
    //     if ($request->filled('dari') && $request->filled('sampai')) {
    //         $query->whereBetween('tgl_transaksi', [$request->dari, $request->sampai]);
    //     }

    //     return DataTables::of($query)
    //         ->addIndexColumn()
    //         ->editColumn('tgl_transaksi', fn($row) => Carbon::parse($row->tgl_transaksi)->isoFormat('D MMMM Y'))
    //         ->editColumn('tgl_lahir', fn($row) => Carbon::parse($row->patient->tgl_lahir)->isoFormat('D MMMM Y'))
    //         ->editColumn('umur', fn($row) => Carbon::parse($row->patient->tgl_lahir)->age . " Tahun")
    //         ->addColumn('status_transaksi', function ($row) {
    //             return $row->is_bayar
    //                 ? '<span class="badge bg-success">LUNAS</span>'
    //                 : '<span class="badge bg-danger">BELUM BAYAR</span>';
    //         })
    //         ->addColumn('foto', function ($row) {
    //             return '<a href="' . asset('assets/registration/' . $row->foto) . '" target="_blank">
    //                     <img src="' . asset('assets/registration/' . $row->foto) . '" width="50" class="rounded"/>
    //                 </a>';
    //         })
    //         ->addColumn('action', function ($row) {
    //             $btn = '<a href="' . route('surat.show', $row->id) . '" target="_blank" class="btn btn-sm btn-warning bx bxs-file-pdf"></a>';
    //             if (auth()->user()->role != 0) {
    //                 $btn .= ' <button class="btn btn-sm btn-primary bx bx-edit" data-bs-toggle="modal" data-bs-target="#editsuratGenerator' . $row->id . '"></button>';
    //                 $btn .= ' <button class="btn btn-sm btn-danger bx bx-trash" data-bs-toggle="modal" data-bs-target="#deletesuratGenerator' . $row->id . '"></button>';
    //             }
    //             return $btn;
    //         })
    //         ->rawColumns(['status_transaksi', 'foto', 'action'])
    //         ->make(true);
    // }
}
