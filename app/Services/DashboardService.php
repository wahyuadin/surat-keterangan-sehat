<?php

namespace App\Services;

use App\Models\PatientRegistration;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class DashboardService
{
    public function sudahDilayani($id, $tanggal = null, $clinic_id = null)
    {
        return PatientRegistration::getPatient($id, $tanggal, $clinic_id);
    }

    public function belumDilayani($id, $tanggal = null, $clinic_id = null)
    {
        return PatientRegistration::getPatient($id, $tanggal, $clinic_id);
    }

    public function icd($tanggal = null, $clinic_id = null)
    {
        return PatientRegistration::getTopICD($tanggal, $clinic_id);
    }

    public function occupation($tanggal = null, $clinic_id = null)
    {
        return PatientRegistration::getTopOccupation($tanggal, $clinic_id);
    }

    public function tabel($request)
    {
        $startDate = '2023-03-01';
        $endDate = '2023-03-31';
        // $tanggal = [date('Y-m-d', strtotime('-1 month')), date('Y-m-d')];
        $tanggal = [$startDate, $endDate];
        $clinic_id = $request->clinic_id ?: (Auth::user()->role == 0 ? Auth::user()->clinic_id : null);
        $data = PatientRegistration::getTabel($tanggal, $clinic_id);

        return DataTables::of($data)
            // kolom dari tbl_client
            ->filterColumn('client_name', function ($data, $keyword) {
                $data->where('tbl_client.client_name', 'like', "%{$keyword}%");
            })
            // kolom dari tbl_patient
            ->filterColumn('bpjs_no', function ($data, $keyword) {
                $data->where('tbl_patient.bpjs_no', 'like', "%{$keyword}%");
            })
            ->filterColumn('patient_name', function ($data, $keyword) {
                $data->where('tbl_patient.patient_name', 'like', "%{$keyword}%");
            })
            ->filterColumn('birth_date', function ($data, $keyword) {
                $data->where('tbl_patient.birth_date', 'like', "%{$keyword}%");
            })
            ->filterColumn('sex', function ($data, $keyword) {
                $data->where('tbl_patient.sex', 'like', "%{$keyword}%");
            })
            ->filterColumn('occupation', function ($data, $keyword) {
                $data->where('tbl_patient.occupation', 'like', "%{$keyword}%");
            })
            // kolom dari tbl_poly
            ->filterColumn('poly_name', function ($data, $keyword) {
                $data->where('tbl_poly.poly_name', 'like', "%{$keyword}%");
            })
            // kolom dari tbl_doctor
            ->filterColumn('doctor_name', function ($data, $keyword) {
                $data->where('tbl_doctor.doctor_name', 'like', "%{$keyword}%");
            })
            ->filterColumn('kdicd', function ($query, $keyword) {
                $query->whereRaw("dbo.F_LIST_ICD10(m.mr_id) LIKE ?", ["%{$keyword}%"]);
            })
            ->filterColumn('hasil_pemeriksaan', function ($data, $keyword) {
                $data->where('tbl_medical_record.hasil_pemeriksaan', 'like', "%{$keyword}%");
            })
            ->make(true);
    }

    // ===== Bulanan ====
    public function top10diagnosis($tanggal = null, $clinic_id = null)
    {
        return PatientRegistration::getTop10Diagnosis($tanggal, $clinic_id);
    }

    public function top5kunjunganperbagian($tanggal = null, $clinic_id = null)
    {
        return PatientRegistration::getTop5KunjunganPerBagian($tanggal, $clinic_id);
    }

    public function top10kunjunganperorangan($tanggal = null, $clinic_id = null)
    {
        return PatientRegistration::top10kunjunganperorangan($tanggal, $clinic_id);
    }

    public function jumlahKunjungan($tanggal = null, $clinic_id = null)
    {
        return PatientRegistration::jumlahKunjungan($tanggal, $clinic_id);
    }

    public function jumlahsuratsakit($tanggal = null, $clinic_id = null)
    {
        return PatientRegistration::jumlahSuratSakit($tanggal, $clinic_id);
    }
}
