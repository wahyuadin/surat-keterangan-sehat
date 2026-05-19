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
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Shared\Date;

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
            toastify()->error('Error, ' . $th);

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
            toastify()->error('Error, ' . $th);

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
            toastify()->error('Error, ' . $th);

            return redirect()->back();
            DB::rollback();
        }
    }

    public function datatable()
    {
        $query = Patient::with(['clinic', 'customer'])->select('patients.*');

        return datatables()->eloquent($query)
            ->addIndexColumn()
            ->addColumn('nama_patient', fn($row) => strtoupper($row->nama_pasien ?? '-'))
            ->addColumn('nama_clinic', fn($row) => strtoupper($row->clinic->nama_klinik ?? '-'))
            ->addColumn('nama_customer', fn($row) => strtoupper($row->customer->nama_perusahaan ?? '-'))
            ->addColumn('pekerjaan', fn($row) => strtoupper($row->pekerjaan ?? '-'))
            ->addColumn('no_ktp', fn($row) => strtoupper($row->no_ktp ?? '-'))
            ->addColumn('lahir', fn($row) => strtoupper($row->tgl_lahir . ', ' . $row->tempat_lahir ?? '-'))
            ->addColumn('jenis_kelamin', fn($row) => strtoupper($row->jenis_kelamin ?? '-'))
            ->addColumn('telp', fn($row) => strtoupper($row->telp ?? '-'))
            ->addColumn('alamat', fn($row) => strtoupper($row->alamat ?? '-'))
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
            ->addColumn('updated_at', fn($row) => $row->updated_at->format('Y-m-d H:i'))
            ->addColumn('action', function ($row) {
                return '
                 <div class="d-flex gap-1">
                    <button class="btn btn-sm btn-primary editBtn" data-id="' . $row->id . '">Edit</button>
                    <button class="btn btn-sm btn-danger deleteBtn" data-id="' . $row->id . '">Delete</button>
                 </div>
                ';
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function generateTemplate()
    {
        $spreadsheet = new Spreadsheet;
        $sheet1 = $spreadsheet->getActiveSheet();
        $sheet1->setTitle('DATA');

        // Header disesuaikan persis dengan skema database
        $headersData = [
            'clinic_id',
            'customer_id',
            'nama_pasien',
            'no_ktp',
            'tgl_lahir',
            'tempat_lahir',
            'pekerjaan',
            'jenis_kelamin',
            'alamat',
            'telp',
        ];

        $col = 'A';
        foreach ($headersData as $header) {
            $sheet1->setCellValue($col . '1', $header);
            $sheet1->getColumnDimension($col)->setAutoSize(true);
            $col++;
        }
        $lastCol1 = chr(ord('A') + count($headersData) - 1);

        // --- TAMBAHAN: Data Dummy ---
        $dummyData = [
            [1, 2, 'M Wahyu Adi Nugroho', '19416255201091', '2000-03-12', 'Bandung', 'Karyawan Swasta', 1, 'Jl. Diponegoro No. 10, Bandung', '081234567890'],
        ];

        $rowDummy = 2;
        foreach ($dummyData as $data) {
            $colDummy = 'A';
            foreach ($data as $value) {
                $sheet1->setCellValue($colDummy . $rowDummy, $value);
                $colDummy++;
            }
            $rowDummy++;
        }

        // --- TAMBAHAN: FORMATTING TIPE DATA KOLOM EXCEL (Baris 2 s.d 1000) ---
        // Kolom A: clinic_id (Number)
        $sheet1->getStyle('A2:A1000')->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER);

        // Kolom B: customer_id (Number)
        $sheet1->getStyle('B2:B1000')->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER);

        // Kolom D: no_ktp (Number tanpa desimal / dipaksa tidak scientific)
        $sheet1->getStyle('D2:D1000')->getNumberFormat()->setFormatCode('0');

        // Kolom E: tgl_lahir (Date YYYY-MM-DD)
        $sheet1->getStyle('E2:E1000')->getNumberFormat()->setFormatCode('yyyy-mm-dd');

        // Kolom H: jenis_kelamin (Number)
        $sheet1->getStyle('H2:H1000')->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER);

        // Kolom J: telp (Number tanpa desimal)
        $sheet1->getStyle('J2:J1000')->getNumberFormat()->setFormatCode('0');

        // Filter pada Header
        $sheet1->setAutoFilter('A1:' . $lastCol1 . '1');

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

        $sheet1->getStyle('A1:' . $lastCol1 . '1')->applyFromArray($styleDataHeader);

        // Beri border untuk data dummy
        $sheet1->getStyle('A2:' . $lastCol1 . ($rowDummy - 1))->applyFromArray([
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
        $clinics = Clinic::select('id', 'kode', 'nama_klinik', 'kota', 'alamat')->get();
        $headersKlinik = ['ID (Masukkan ke clinic_id)', 'KODE', 'NAMA KLINIK', 'KOTA', 'ALAMAT'];

        $col = 'A';
        foreach ($headersKlinik as $header) {
            $sheet2->setCellValue($col . '1', $header);
            $sheet2->getColumnDimension($col)->setAutoSize(true);
            $col++;
        }
        $lastCol2 = chr(ord('A') + count($headersKlinik) - 1);

        $row = 2;
        foreach ($clinics as $clinic) {
            $sheet2->setCellValue('A' . $row, $clinic->id);
            $sheet2->setCellValue('B' . $row, $clinic->kode);
            $sheet2->setCellValue('C' . $row, $clinic->nama_klinik);
            $sheet2->setCellValue('D' . $row, $clinic->kota);
            $sheet2->setCellValue('E' . $row, $clinic->alamat);
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
        $customers = Customer::select('id', 'nama_perusahaan', 'alamat')->get();
        $headersPerusahaan = ['ID (Masukkan ke customer_id)', 'NAMA PERUSAHAAN', 'ALAMAT'];

        $col = 'A';
        foreach ($headersPerusahaan as $header) {
            $sheet3->setCellValue($col . '1', $header);
            $sheet3->getColumnDimension($col)->setAutoSize(true);
            $col++;
        }
        $lastCol3 = chr(ord('A') + count($headersPerusahaan) - 1);

        $row = 2;
        foreach ($customers as $customer) {
            $sheet3->setCellValue('A' . $row, $customer->id);
            $sheet3->setCellValue('B' . $row, $customer->nama_perusahaan);
            $sheet3->setCellValue('C' . $row, $customer->alamat);
            $row++;
        }

        // Styling & Password Sheet 3
        $this->applyMasterStyle($sheet3, $lastCol3, $row - 1);
        $sheet3->freezePane('A2');
        $sheet3->getProtection()->setPassword('NayakaPusat');
        $sheet3->getProtection()->setSheet(true);

        // =========================================================
        // SHEET 4: MASTER JENIS KELAMIN
        // =========================================================
        $sheet4 = $spreadsheet->createSheet();
        $sheet4->setTitle('MASTER JENIS KELAMIN');

        // Mengambil data dari database
        $genders = ['1' => 'Laki-laki', '0' => 'Perempuan'];
        $headersJenisKelamin = ['Type (Masukkan ke "jenis_kelamin")', 'JENIS KELAMIN'];

        $col = 'A';
        foreach ($headersJenisKelamin as $header) {
            $sheet4->setCellValue($col . '1', $header);
            $sheet4->getColumnDimension($col)->setAutoSize(true);
            $col++;
        }
        $lastCol4 = chr(ord('A') + count($headersJenisKelamin) - 1);

        $row = 2;
        foreach ($genders as $id => $name) {
            $sheet4->setCellValue('A' . $row, $id);
            $sheet4->setCellValue('B' . $row, $name);
            $row++;
        }

        // Styling & Password Sheet 4
        $this->applyMasterStyle($sheet4, $lastCol4, $row - 1);
        $sheet4->freezePane('A2');
        $sheet4->getProtection()->setPassword('NayakaPusat');
        $sheet4->getProtection()->setSheet(true);

        // =========================================================
        // FINALISASI
        // =========================================================
        $spreadsheet->setActiveSheetIndex(0);

        $writer = new Xlsx($spreadsheet);
        $fileName = 'Template_Import_Pasien_' . date('Y-m-d_H-i-s') . '.xlsx';
        $tempPath = storage_path('app/public/' . $fileName);
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

        $sheet->getStyle('A1:' . $lastCol . '1')->applyFromArray($styleHeader);
        $sheet->getRowDimension(1)->setRowHeight(25);
        $sheet->setAutoFilter('A1:' . $lastCol . '1'); // Opsional: Tambahkan filter juga di sheet master

        if ($lastRow >= 2) {
            $styleData = [
                'borders' => [
                    'allBorders' => ['borderStyle' => Border::BORDER_THIN],
                ],
            ];
            $sheet->getStyle('A2:' . $lastCol . $lastRow)->applyFromArray($styleData);
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
        $fullPath = storage_path('app/public/' . $filePath);

        if (!file_exists($fullPath)) {
            return ['status' => 'error', 'message' => 'File sementara tidak ditemukan. Silakan upload ulang.'];
        }

        $spreadsheet = IOFactory::load($fullPath);
        $sheet = $spreadsheet->getActiveSheet();
        $rows = $sheet->toArray();

        array_shift($rows);

        $successCount = 0;
        $errorsList = [];
        $excelRow = 2;

        foreach ($rows as $row) {
            if (empty(array_filter($row))) {
                $excelRow++;
                continue;
            }

            $patientData = [];
            foreach ($mapping as $excelColumnIndex => $dbColumnName) {
                if (!empty($dbColumnName)) {
                    $patientData[$dbColumnName] = $row[$excelColumnIndex] ?? null;
                }
            }

            // ==========================================
            // 1. VALIDASI PRA-SISTEM
            // ==========================================
            $isRowValid = true;

            // Validasi Wajib Isi
            if (empty($patientData['nama_pasien']) || empty($patientData['no_ktp'])) {
                $errorsList[] = [
                    'deskripsi' => "Baris {$excelRow}: Gagal disimpan (Nama Pasien / No KTP kosong)",
                    'detail' => "Data wajib (Mandatory) tidak lengkap. Nama Pasien dan No KTP harus diisi."
                ];
                $isRowValid = false;
            }

            // Validasi Telepon (Wajib Angka)
            if (!empty($patientData['telp'])) {
                $telp = trim($patientData['telp']);
                if (!preg_match('/^[0-9]+$/', $telp)) {
                    $errorsList[] = [
                        'deskripsi' => "Baris {$excelRow}: Gagal disimpan (Format Telepon Salah)",
                        'detail' => "Kolom Telepon harus berupa angka. Nilai yang dimasukkan: '{$telp}' tidak valid."
                    ];
                    $isRowValid = false;
                } else {
                    $patientData['telp'] = $telp;
                }
            }

            // Validasi Panjang Karakter Pekerjaan
            if (!empty($patientData['pekerjaan']) && strlen($patientData['pekerjaan']) > 255) {
                $errorsList[] = [
                    'deskripsi' => "Baris {$excelRow}: Gagal disimpan (Pekerjaan Terlalu Panjang)",
                    'detail' => "Kolom Pekerjaan tidak boleh melebihi 255 karakter."
                ];
                $isRowValid = false;
            }

            // Validasi Panjang Karakter Tempat Lahir
            if (!empty($patientData['tempat_lahir']) && strlen($patientData['tempat_lahir']) > 255) {
                $errorsList[] = [
                    'deskripsi' => "Baris {$excelRow}: Gagal disimpan (Tempat Lahir Terlalu Panjang)",
                    'detail' => "Kolom Tempat Lahir tidak boleh melebihi 255 karakter."
                ];
                $isRowValid = false;
            }

            // Lewati baris jika validasi pra-sistem gagal
            if (!$isRowValid) {
                $excelRow++;
                continue;
            }

            if (isset($patientData['jenis_kelamin']) && $patientData['jenis_kelamin'] !== '') {
                $jk = strtolower(trim($patientData['jenis_kelamin']));
                $validLaki = ['l', 'laki-laki', 'laki', '1', 'pria'];
                $validPerempuan = ['p', 'perempuan', 'wanita', '0'];

                if (in_array($jk, $validLaki)) {
                    $patientData['jenis_kelamin'] = 1;
                } elseif (in_array($jk, $validPerempuan)) {
                    $patientData['jenis_kelamin'] = 0;
                } else {
                    $errorsList[] = [
                        'deskripsi' => "Baris {$excelRow}: Gagal disimpan (Format Jenis Kelamin Salah)",
                        'detail' => "Kolom Jenis Kelamin harus berisi Laki-laki/Perempuan atau 1/0. Nilai yang dimasukkan: '{$patientData['jenis_kelamin']}' tidak valid."
                    ];
                    $isRowValid = false;
                }
            } else {
                $patientData['jenis_kelamin'] = 1;
            }

            // ==========================================
            // 2. EKSEKUSI KE DATABASE
            // ==========================================
            try {
                Patient::updateOrCreate(
                    ['no_ktp' => $patientData['no_ktp']],
                    $patientData
                );
                $successCount++;
            } catch (\Exception $e) {
                $errorMsg = $e->getMessage();

                $friendlyDeskripsi = "Baris {$excelRow}: Gagal disimpan";
                $friendlyDetail = "Terjadi kesalahan format data atau sistem.";

                // --- KAMUS KOLOM UNTUK DETEKSI OTOMATIS ---
                $kamusKolom = [
                    'tgl_lahir' => 'Tanggal Lahir',
                    'clinic_id' => 'ID Klinik',
                    'customer_id' => 'ID Perusahaan',
                    'jenis_kelamin' => 'Jenis Kelamin',
                    'no_ktp' => 'No KTP',
                    'nama_pasien' => 'Nama Pasien',
                    'tempat_lahir' => 'Tempat Lahir',
                    'pekerjaan' => 'Pekerjaan',
                    'alamat' => 'Alamat',
                    'telp' => 'Telepon'
                ];

                $kolomError = "salah satu kolom";
                foreach ($kamusKolom as $dbCol => $namaKolom) {
                    if (str_contains($errorMsg, "'$dbCol'") || str_contains($errorMsg, "`$dbCol`")) {
                        $kolomError = $namaKolom;
                        break;
                    }
                }

                // 1. Error ID Relasi / Foreign Key
                if (str_contains($errorMsg, 'foreign key constraint fails')) {
                    if (str_contains($errorMsg, 'clinic_id')) {
                        $invalidId = $patientData['clinic_id'] ?? 'Kosong';
                        $friendlyDeskripsi .= " (ID Klinik '$invalidId' Tidak Valid)";
                        $friendlyDetail = "ID Klinik '$invalidId' tidak terdapat pada database. Silakan cek referensi di sheet MASTER KLINIK.";
                    } elseif (str_contains($errorMsg, 'customer_id')) {
                        $invalidId = $patientData['customer_id'] ?? 'Kosong';
                        $friendlyDeskripsi .= " (ID Perusahaan '$invalidId' Tidak Valid)";
                        $friendlyDetail = "ID Perusahaan '$invalidId' tidak terdapat pada database. Silakan cek referensi di sheet MASTER PERUSAHAAN.";
                    } else {
                        $friendlyDeskripsi .= " (ID Relasi Tidak Valid)";
                        $friendlyDetail = "Salah satu ID referensi tidak ditemukan di database.";
                    }
                }
                // 2. Error Data Terlalu Panjang
                elseif (str_contains($errorMsg, 'Data too long')) {
                    $friendlyDeskripsi .= " (Data Terlalu Panjang pada kolom $kolomError)";
                    $friendlyDetail = "Input teks pada kolom '$kolomError' melebihi batas maksimal karakter yang diizinkan sistem.";
                }
                // 3. Error Format Tipe Data (Tanggal / Angka)
                elseif (str_contains($errorMsg, 'Incorrect') || str_contains($errorMsg, 'Invalid datetime format')) {
                    $friendlyDeskripsi .= " (Format Data Salah pada kolom $kolomError)";

                    if ($kolomError === 'Tanggal Lahir') {
                        $friendlyDetail = "Format Tanggal Lahir salah. Pastikan menggunakan format YYYY-MM-DD (Contoh: 1990-12-31).";
                    } else {
                        $friendlyDetail = "Format data pada kolom '$kolomError' tidak sesuai. Pastikan tidak memasukkan huruf ke dalam kolom khusus angka.";
                    }
                }
                // 4. Error Data Duplikat
                elseif (str_contains($errorMsg, 'Duplicate entry')) {
                    $friendlyDeskripsi .= " (Data Duplikat pada kolom $kolomError)";
                    $friendlyDetail = "Data pada kolom '$kolomError' sudah terdaftar di sistem (duplikat).";
                }

                // Masukkan pesan yang sudah di-filter ke dalam Log Error
                $errorsList[] = [
                    'deskripsi' => $friendlyDeskripsi,
                    'detail' => $friendlyDetail
                ];
            }

            $excelRow++;
        }

        unlink($fullPath);

        $response = [
            'status' => count($errorsList) > 0 ? 'warning' : 'success',
            'message' => "Proses selesai! $successCount data tersimpan, " . count($errorsList) . " data gagal.",
        ];

        // ==========================================
        // 3. GENERATE FILE EXCEL ERROR (JIKA ADA)
        // ==========================================
        if (count($errorsList) > 0) {
            $errSpreadsheet = new Spreadsheet();
            $errSheet = $errSpreadsheet->getActiveSheet();
            $errSheet->setTitle('Log Error Import');

            $errSheet->setCellValue('A1', 'No');
            $errSheet->setCellValue('B1', 'Deskripsi Error');
            $errSheet->setCellValue('C1', 'Detail Error');

            // Style Header Merah
            $styleErrHeader = [
                'font' => ['bold' => true, 'color' => ['argb' => 'FFFFFFFF']],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['argb' => 'FFDC2626'], // Merah
                ],
            ];
            $errSheet->getStyle('A1:C1')->applyFromArray($styleErrHeader);
            $errSheet->getColumnDimension('A')->setAutoSize(true);
            $errSheet->getColumnDimension('B')->setAutoSize(true);
            $errSheet->getColumnDimension('C')->setAutoSize(true);

            $r = 2;
            foreach ($errorsList as $index => $err) {
                $errSheet->setCellValue('A' . $r, $index + 1);
                $errSheet->setCellValue('B' . $r, $err['deskripsi']);
                $errSheet->setCellValue('C' . $r, $err['detail']);
                $r++;
            }

            $writer = new Xlsx($errSpreadsheet);
            $errorFileName = 'patient_import_error_' . date('d-m-Y_His') . '.xlsx';
            if (!file_exists(storage_path('app/public/temp_errors'))) {
                mkdir(storage_path('app/public/temp_errors'), 0777, true);
            }

            $errorFilePath = storage_path('app/public/temp_errors/' . $errorFileName);
            $writer->save($errorFilePath);

            $response['error_file'] = $errorFileName;
            $response['error_url'] = route('patient.import.error_log', ['filename' => $errorFileName]);
        }

        return $response;
    }
}
