<?php

namespace App\Services;

use App\Models\Clinic;
use App\Models\Customer;
use App\Models\Patient;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class PatientService
{
    public function tambah($request)
    {
        DB::beginTransaction();
        try {
            $data = $request->except('_token', '_method');
            $data['created_by'] = Auth::user()->id;
            Patient::tambahData($data);
            DB::commit();
            toastify()->success('Data Berhasil Ditambahkan.');

            return redirect()->back();
        } catch (\Throwable $th) {
            toastify()->error('Error, '.$th);

            return redirect()->back();
            DB::rollback();
        }
    }

    public function edit($id, $request)
    {
        DB::beginTransaction();
        try {
            $data = $request->except('_token', '_method');
            $data['created_by'] = Auth::user()->id;
            Patient::editData($id, $data);
            DB::commit();
            toastify()->success('Data Berhasil diedit.');

            return redirect()->back();
        } catch (\Throwable $th) {
            toastify()->error('Error, '.$th);

            return redirect()->back();
            DB::rollback();
        }
    }

    public function hapus($id)
    {
        DB::beginTransaction();
        try {
            Patient::hapusData($id);
            DB::commit();
            toastify()->success('Data Berhasil Dihapus.');

            return redirect()->back();
        } catch (\Throwable $th) {
            toastify()->error('Error, '.$th);

            return redirect()->back();
            DB::rollback();
        }
    }

    public function datatable()
    {
        $query = Patient::with(['clinic', 'customer'])->select('patients.*');

        return datatables()->eloquent($query)
            ->addIndexColumn()
            ->addColumn('nama_patient', fn ($row) => strtoupper($row->nama_pasien ?? '-'))
            ->addColumn('nama_clinic', fn ($row) => strtoupper($row->clinic->nama_klinik ?? '-'))
            ->addColumn('nama_customer', fn ($row) => strtoupper($row->customer->nama_perusahaan ?? '-'))
            ->addColumn('pekerjaan', fn ($row) => strtoupper($row->pekerjaan ?? '-'))
            ->addColumn('no_ktp', fn ($row) => strtoupper($row->no_ktp ?? '-'))
            ->addColumn('lahir', fn ($row) => strtoupper($row->tgl_lahir.', '.$row->tempat_lahir ?? '-'))
            ->addColumn('jenis_kelamin', fn ($row) => strtoupper($row->jenis_kelamin ?? '-'))
            ->addColumn('telp', fn ($row) => strtoupper($row->telp ?? '-'))
            ->addColumn('alamat', fn ($row) => strtoupper($row->alamat ?? '-'))
            ->addColumn('updated_by', function ($row) {
                $userName = '-';
                if ($row->created_by) {
                    $user = User::where('id', $row->created_by)->first();
                    if ($user) {
                        $userName = strtoupper($user->nama);
                    }
                }

                return $userName;
            })
            ->addColumn('updated_at', fn ($row) => $row->updated_at->format('Y-m-d H:i'))
            ->addColumn('action', function ($row) {
                return '
                 <div class="d-flex gap-1">
                    <button class="btn btn-sm btn-primary editBtn" data-id="'.$row->id.'">Edit</button>
                    <button class="btn btn-sm btn-danger deleteBtn" data-id="'.$row->id.'">Delete</button>
                 </div>
                ';
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function generateTemplate()
    {
        $spreadsheet = new Spreadsheet;

        // =========================================================
        // SHEET 1: DATA PASIEN
        // =========================================================
        $sheet1 = $spreadsheet->getActiveSheet();
        $sheet1->setTitle('DATA');

        // Header disesuaikan persis dengan skema database
        $headersData = [
            'clinic_id', 'customer_id', 'nama_pasien', 'no_ktp',
            'tgl_lahir', 'tempat_lahir', 'pekerjaan', 'jenis_kelamin',
            'alamat', 'telp',
        ];

        $col = 'A';
        foreach ($headersData as $header) {
            $sheet1->setCellValue($col.'1', $header);
            $sheet1->getColumnDimension($col)->setAutoSize(true);
            $col++;
        }

        // Ambil huruf kolom terakhir (misal: J)
        $lastCol1 = chr(ord('A') + count($headersData) - 1);

        // --- TAMBAHAN: Data Dummy ---
        $dummyData = [
            [1, 2, 'Budi Santoso', '3201011203900001', '1990-03-12', 'Jakarta', 'Karyawan Swasta', 1, 'Jl. Sudirman No. 10, Jakarta', '081234567890'],
            [2, 1, 'Siti Aminah', '3201011508920002', '1992-08-15', 'Bandung', 'Ibu Rumah Tangga', 0, 'Jl. Merdeka No. 5, Bandung', '081987654321'],
        ];

        $rowDummy = 2;
        foreach ($dummyData as $data) {
            $colDummy = 'A';
            foreach ($data as $value) {
                $sheet1->setCellValue($colDummy.$rowDummy, $value);
                $colDummy++;
            }
            $rowDummy++;
        }

        // --- TAMBAHAN: Filter pada Header ---
        $sheet1->setAutoFilter('A1:'.$lastCol1.'1');

        // Desain Modern Sheet 1 (Warna Biru Tua, Center, Bold, Border)
        $styleDataHeader = [
            'font' => ['bold' => true, 'color' => ['argb' => 'FFFFFFFF']],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
            'borders' => [
                'allBorders' => ['borderStyle' => Border::BORDER_THIN],
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['argb' => 'FF1E3A8A'], // Modern Dark Blue
            ],
        ];

        $sheet1->getStyle('A1:'.$lastCol1.'1')->applyFromArray($styleDataHeader);

        // Beri border untuk data dummy
        $sheet1->getStyle('A2:'.$lastCol1.($rowDummy - 1))->applyFromArray([
            'borders' => [
                'allBorders' => ['borderStyle' => Border::BORDER_THIN],
            ],
        ]);

        $sheet1->getRowDimension(1)->setRowHeight(30);
        $sheet1->freezePane('A2');

        // =========================================================
        // SHEET 2: MASTER KLINIK
        // =========================================================
        $sheet2 = $spreadsheet->createSheet();
        $sheet2->setTitle('MASTER KLINIK');

        // Mengambil data dari database
        $clinics = Clinic::select('id', 'kode', 'nama_klinik', 'kota')->get();
        $headersKlinik = ['ID (Masukkan ke clinic_id)', 'KODE', 'NAMA KLINIK', 'KOTA'];

        $col = 'A';
        foreach ($headersKlinik as $header) {
            $sheet2->setCellValue($col.'1', $header);
            $sheet2->getColumnDimension($col)->setAutoSize(true);
            $col++;
        }
        $lastCol2 = chr(ord('A') + count($headersKlinik) - 1);

        $row = 2;
        foreach ($clinics as $clinic) {
            $sheet2->setCellValue('A'.$row, $clinic->id);
            $sheet2->setCellValue('B'.$row, $clinic->kode);
            $sheet2->setCellValue('C'.$row, $clinic->nama_klinik);
            $sheet2->setCellValue('D'.$row, $clinic->kota);
            $row++;
        }

        // Styling & Password Sheet 2
        $this->applyMasterStyle($sheet2, $lastCol2, $row - 1);
        $sheet2->freezePane('A2');
        $sheet2->getProtection()->setPassword('NayakaPusat');
        $sheet2->getProtection()->setSheet(true);

        // =========================================================
        // SHEET 3: MASTER PERUSAHAAN (CUSTOMER)
        // =========================================================
        $sheet3 = $spreadsheet->createSheet();
        $sheet3->setTitle('MASTER PERUSAHAAN');

        // Mengambil data dari database
        $customers = Customer::select('id', 'kode', 'nama_perusahaan')->get();
        $headersPerusahaan = ['ID (Masukkan ke customer_id)', 'KODE', 'NAMA PERUSAHAAN'];

        $col = 'A';
        foreach ($headersPerusahaan as $header) {
            $sheet3->setCellValue($col.'1', $header);
            $sheet3->getColumnDimension($col)->setAutoSize(true);
            $col++;
        }
        $lastCol3 = chr(ord('A') + count($headersPerusahaan) - 1);

        $row = 2;
        foreach ($customers as $customer) {
            $sheet3->setCellValue('A'.$row, $customer->id);
            $sheet3->setCellValue('B'.$row, $customer->kode);
            $sheet3->setCellValue('C'.$row, $customer->nama_perusahaan);
            $row++;
        }

        // Styling & Password Sheet 3
        $this->applyMasterStyle($sheet3, $lastCol3, $row - 1);
        $sheet3->freezePane('A2');
        $sheet3->getProtection()->setPassword('NayakaPusat');
        $sheet3->getProtection()->setSheet(true);

        // =========================================================
        // FINALISASI
        // =========================================================
        $spreadsheet->setActiveSheetIndex(0);

        $writer = new Xlsx($spreadsheet);
        $fileName = 'Template_Import_Pasien.xlsx';
        $tempPath = storage_path('app/public/'.$fileName);
        $writer->save($tempPath);

        return response()->download($tempPath)->deleteFileAfterSend(true);
    }

    /**
     * Helper logic terpisah untuk styling sheet referensi (Klinik & Perusahaan)
     */
    private function applyMasterStyle($sheet, $lastCol, $lastRow)
    {
        // Style Header Hijau
        $styleHeader = [
            'font' => ['bold' => true, 'color' => ['argb' => 'FFFFFFFF']],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
            'borders' => [
                'allBorders' => ['borderStyle' => Border::BORDER_THIN],
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['argb' => 'FF16A34A'], // Modern Green
            ],
        ];

        $sheet->getStyle('A1:'.$lastCol.'1')->applyFromArray($styleHeader);
        $sheet->getRowDimension(1)->setRowHeight(25);
        $sheet->setAutoFilter('A1:'.$lastCol.'1'); // Opsional: Tambahkan filter juga di sheet master

        if ($lastRow >= 2) {
            $styleData = [
                'borders' => [
                    'allBorders' => ['borderStyle' => Border::BORDER_THIN],
                ],
            ];
            $sheet->getStyle('A2:'.$lastCol.$lastRow)->applyFromArray($styleData);
        }
    }

    /**
     * Logika untuk membaca file Excel dan mengembalikan data preview
     */
    public function parseForPreview($file)
    {
        $spreadsheet = IOFactory::load($file->getPathname());
        $sheet = $spreadsheet->getActiveSheet();
        $rows = $sheet->toArray();

        if (empty($rows) || count($rows) <= 1) {
            return [
                'status' => 'error',
                'message' => 'File kosong atau tidak memiliki data selain header.',
            ];
        }

        $excelHeaders = array_shift($rows);
        $excelHeaders = array_filter($excelHeaders, function ($value) {
            return ! is_null($value) && $value !== '';
        });

        $previewData = array_slice($rows, 0, 5);
        $dbColumns = [
            '' => '-- Abaikan Kolom Ini --',
            'clinic_id' => 'ID Perusahaan',
            'customer_id' => 'ID Klinik',
            'nama_pasien' => 'Nama Pasien',
            'no_ktp' => 'No KTP',
            'tgl_lahir' => 'Tanggal Lahir',
            'tempat_lahir' => 'Tempat Lahir',
            'pekerjaan' => 'Pekerjaan',
            'jenis_kelamin' => 'Jenis Kelamin',
            'telp' => 'Telp',
            'alamat' => 'Alamat',
        ];

        return [
            'status' => 'success',
            'headers' => $excelHeaders,
            'preview_data' => $previewData,
            'db_columns' => $dbColumns,
        ];
    }

    public function processMappedImport($filePath, $mapping)
    {
        $fullPath = storage_path('app/public/'.$filePath);

        if (! file_exists($fullPath)) {
            return ['status' => 'error', 'message' => 'File sementara tidak ditemukan. Silakan upload ulang.'];
        }

        $spreadsheet = IOFactory::load($fullPath);
        $sheet = $spreadsheet->getActiveSheet();
        $rows = $sheet->toArray();

        // Hapus baris pertama karena itu adalah header
        array_shift($rows);

        $successCount = 0;
        $errorsList = []; // <-- Berubah dari $errorCount menjadi array

        // Kita mulai dari baris ke-2 (karena baris 1 di Excel adalah header)
        $excelRow = 2;

        foreach ($rows as $row) {
            // Lewati jika baris benar-benar kosong
            if (empty(array_filter($row))) {
                $excelRow++;

                continue;
            }

            $patientData = [];
            foreach ($mapping as $excelColumnIndex => $dbColumnName) {
                if (! empty($dbColumnName)) {
                    $patientData[$dbColumnName] = $row[$excelColumnIndex] ?? null;
                }
            }

            // 1. Validasi Keamanan Pertama (Wajib Isi)
            if (empty($patientData['nama_pasien']) || empty($patientData['no_ktp'])) {
                $errorsList[] = "Baris {$excelRow}: Nama Pasien atau No KTP kosong.";
                $excelRow++;

                continue; // Lanjut ke baris berikutnya
            }

            // Format Jenis Kelamin
            if (isset($patientData['jenis_kelamin'])) {
                $jk = strtolower(trim($patientData['jenis_kelamin']));
                $patientData['jenis_kelamin'] = in_array($jk, ['l', 'laki-laki', 'laki', '1', 'pria']) ? 1 : 0;
            } else {
                $patientData['jenis_kelamin'] = 1;
            }

            // 2. Eksekusi ke Database
            try {
                Patient::updateOrCreate(
                    ['no_ktp' => $patientData['no_ktp']],
                    $patientData
                );
                $successCount++;
            } catch (\Exception $e) {
                $errorsList[] = "Baris {$excelRow}: Gagal disimpan (Cek kesesuaian ID relasi atau format tipe data) detail: ".$e->getMessage();
            }

            $excelRow++;
        }

        // Hapus file sementara
        unlink($fullPath);

        // Menentukan status akhir
        $status = count($errorsList) > 0 ? 'warning' : 'success';

        return [
            'status' => $status,
            'message' => "Proses selesai! $successCount data tersimpan.",
            'errors_list' => $errorsList, // <-- Kirim array error ke Controller/Frontend
        ];
    }
}
