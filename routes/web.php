<?php

use App\Http\Controllers\AgentController;
use App\Http\Controllers\BranchOfficeController;
use App\Http\Controllers\BugReportController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\DaftarKunjunganController;
use App\Http\Controllers\FormController;
use App\Http\Controllers\KlinikController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\ParamedisController;
use App\Http\Controllers\PasienController;
use App\Http\Controllers\PatientTemplateController;
use App\Http\Controllers\PerusahaanController;
use App\Http\Controllers\SuratGeneratorController;
use App\Http\Controllers\TagihanController;
use App\Http\Controllers\UserDataController;
use Illuminate\Support\Facades\Route;


Route::resource('form', FormController::class);
Route::get('form-check/{data}', [FormController::class, 'formCheck'])->name('form-check');
Route::middleware(['auth'])->group(function () {
    // master
    Route::get('/', [Controller::class, 'showData'])->name('dashboard-harian');
    Route::resource('perusahaan', PerusahaanController::class);
    Route::resource('klinik', KlinikController::class);
    Route::resource('paramedis', ParamedisController::class);
    Route::resource('branch-office', BranchOfficeController::class);
    Route::resource('patient', PasienController::class);
    Route::resource('patient-template', PatientTemplateController::class);
    Route::resource('user-data', UserDataController::class);
    Route::resource('agent', AgentController::class);
    // end master
    Route::resource('surat', SuratGeneratorController::class);
    Route::resource('tagihan', TagihanController::class);
    Route::resource('bug-report', BugReportController::class);
    Route::put('bug-report-accept/{id}', [BugReportController::class, 'accept'])->name('bug-report-accept');
    Route::put('bug-report-reject/{id}', [BugReportController::class, 'reject'])->name('bug-report-reject');
    Route::get('audit', [Controller::class, 'auditable'])->name('audit');
    Route::prefix('surat-blangko')->group(function () {
        Route::get('pdf', [SuratGeneratorController::class, 'suratBlangkoPdf'])->name('surat-blangko-pdf');
        Route::get('docx', [SuratGeneratorController::class, 'suratBlangkoDocx'])->name('surat-blangko-word');
    });
    Route::prefix('server-site')->group(function () {
        Route::get('surat', [SuratGeneratorController::class, 'resultData'])->name('surat.data');
        Route::get('pasien', [PasienController::class, 'datatable'])->name('patient.datatable');
        Route::get('audit', [Controller::class, 'auditData'])->name('audit.datatable');
    });



    // ========
    // routes/web.php
    Route::get('get-patients', [TagihanController::class, 'getPatients'])->name('tagihan.getPatients');
    Route::get('/generate-no-tagihan/{clinic_id}', [TagihanController::class, 'generateNoTagihan'])
        ->name('generate.no.tagihan');
    Route::get('/generate-no-transaksi/patient/{patient_id}', [SuratGeneratorController::class, 'generateNoTransaksi'])
        ->name('surat.generateNo');
    Route::get('get-agent/{customer_id}/{clinic_id}', [AgentController::class, 'getAgent'])->name('get-agent');
    Route::prefix('excel')->group(function () {
        Route::prefix('user')->group(function () {
            Route::post('/', [UserDataController::class, 'importExcel'])->name('user-data.import-excel');
            Route::get('export', [UserDataController::class, 'templateExcel'])->name('user-data.template-excel');
        });
    });
    Route::delete('user-data.bulk-delete', [UserDataController::class, 'bulkDelete'])->name('user-data.bulk-delete');


    // ==================
    Route::prefix('server-site')->group(function () {
        // harian
        Route::prefix('harian')->group(function () {
            Route::get('/', [Controller::class, 'serverSiteHarian'])->name('server-site-harian');
            Route::get('chart-harian', [Controller::class, 'ajaxHarianSummary'])->name('ajax-harian-summary');
        });
        // bulanan
        Route::prefix('bulanan')->group(function () {
            Route::get('/', [Controller::class, 'serverSiteBulanan'])->name('server-site-bulanan');
            Route::get('top10diagnosis', [Controller::class, 'top10diagnosis'])->name('top10diagnosis');
            Route::get('top5kunjunganperbagian', [Controller::class, 'top5kunjunganperbagian'])->name('top5kunjunganperbagian');
            Route::get('top10kunjunganperorangan', [Controller::class, 'top10kunjunganperorangan'])->name('top10kunjunganperorangan');
            Route::get('jumlahkunjungan', [Controller::class, 'jumlahKunjungan'])->name('jumlah-kunjungan');
            Route::get('jumlah-surat-sakit', [Controller::class, 'jumlahsuratsakit'])->name('jumlah-surat-sakit');
        });
        // Route::get('chart-bulanan', [Controller::class, 'ajaxBulananSummary'])->name('ajax-bulanan-summary');
        // kunjungan
        Route::get('daftar-kunjungan', [DaftarKunjunganController::class, 'serverSite'])->name('server-site-daftar-kunjungan');
    });

    Route::resource('daftar-kunjungan', DaftarKunjunganController::class);
});
Route::get('/logout', [LoginController::class, 'logout'])->name('logout');
Route::resource('login', LoginController::class);
