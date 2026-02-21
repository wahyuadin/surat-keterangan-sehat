<?php

namespace App\Http\Controllers;

use App\Models\BugReport;
use App\Services\DashboardService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    protected $dashboard;

    public function __construct(DashboardService $dashboard)
    {
        $this->dashboard = $dashboard;
    }

    public function showData()
    {
        if (Auth::user()->role == '2') {
            return view('dashboard.harian');
        } else {
            return redirect()->route('surat.index');
        }
    }

    public function auditable()
    {
        return view('audit.index', [
            'data' => DB::table('audits')->latest()->get()
        ]);
    }

    public function serverSiteHarian(Request $request)
    {
        $tanggal = '2023-03-17';
        // $tanggal = date('Y-m-d');
        $clinic_id = $request->clinic_id ?: (Auth::user()->role == 0 ? Auth::user()->clinic_id : null);
        $data = $this->dashboard->tabel($tanggal, $clinic_id);

        return DataTables::of($data)->make(true);
    }


    public function ajaxHarianSummary(Request $request)
    {
        $tanggal = '2023-03-17';
        // $tanggal = null;
        $clinic_id = $request->clinic_id ?: (Auth::user()->role == 0 ? Auth::user()->clinic_id : null);

        $belumDiLayani = $this->dashboard->belumDilayani(0, $tanggal, $clinic_id);
        $sudahDiLayani = $this->dashboard->sudahDilayani(2, $tanggal, $clinic_id);
        $occupation = $this->dashboard->occupation($tanggal, $clinic_id);
        $icd = $this->dashboard->icd($tanggal, $clinic_id);

        return response()->json([
            'belumDiLayani' => $belumDiLayani,
            'sudahDiLayani' => $sudahDiLayani,
            'totalDiLayani' => $belumDiLayani + $sudahDiLayani,
            'icd' => $icd,
            'occupation' => $occupation
        ]);
    }

    // ============
    // BULANAN
    // ============

    public function serverSiteBulanan(Request $request)
    {
        $startDate = $request->start_date;
        $endDate = $request->end_date;
        $clinic_id = $request->clinic_id ?: (Auth::user()->role == 0 ? Auth::user()->clinic_id : null);
        // $clinic_id = 29;

        if (!$startDate || !$endDate) {
            $startDate = date('Y-m-01');
            $endDate = date('Y-m-d');
        }
        $tanggal = [$startDate, $endDate];

        $data = $this->dashboard->tabel($tanggal, $clinic_id);

        return DataTables::of($data)->make(true);
    }

    public function top10diagnosis(Request $request)
    {
        $startDate = $request->start_date;
        $endDate = $request->end_date;
        $clinic_id = $request->clinic_id ?: (Auth::user()->role == 0 ? Auth::user()->clinic_id : null);
        // $clinic_id = 29;

        if (!$startDate || !$endDate) {
            $startDate = date('Y-m-01');
            $endDate = date('Y-m-d');
            // $startDate = '2023-03-17';
            // $endDate = '2023-03-25';
        }
        $tanggal = [$startDate, $endDate];

        $top10diagnosis = $this->dashboard->top10diagnosis($tanggal, $clinic_id);

        return response()->json([
            'top10diagnosis' => $top10diagnosis,
        ]);
    }

    public function top5kunjunganperbagian(Request $request)
    {
        $startDate = $request->start_date;
        $endDate = $request->end_date;
        $clinic_id = $request->clinic_id ?: (Auth::user()->role == 0 ? Auth::user()->clinic_id : null);
        // $clinic_id = 29;

        if (!$startDate || !$endDate) {
            $startDate = date('Y-m-01');
            $endDate = date('Y-m-d');
            // $startDate = '2023-03-01';
            // $endDate = '2023-03-31';
        }
        $tanggal = [$startDate, $endDate];

        $top5kunjunganperbagian = $this->dashboard->top5kunjunganperbagian($tanggal, $clinic_id);

        return response()->json([
            'top5kunjunganperbagian' => $top5kunjunganperbagian,
        ]);
    }

    public function top10kunjunganperorangan(Request $request)
    {
        $startDate = $request->start_date;
        $endDate = $request->end_date;
        $clinic_id = $request->clinic_id ?: (Auth::user()->role == 0 ? Auth::user()->clinic_id : null);
        // $clinic_id = 29;

        if (!$startDate || !$endDate) {
            $startDate = date('Y-m-01');
            $endDate = date('Y-m-d');
            // $startDate = '2023-03-17';
            // $endDate = '2023-03-25';
        }
        $tanggal = [$startDate, $endDate];

        $top10kunjunganperorangan = $this->dashboard->top10kunjunganperorangan($tanggal, $clinic_id);

        return response()->json([
            'top10kunjunganperorangan' => $top10kunjunganperorangan,
        ]);
    }

    public function jumlahKunjungan(Request $request)
    {
        $startDate = $request->start_date;
        $endDate = $request->end_date;
        $clinic_id = $request->clinic_id ?: (Auth::user()->role == 0 ? Auth::user()->clinic_id : null);
        // $clinic_id = 29;

        if (!$startDate || !$endDate) {
            $startDate = date('Y-m-01');
            $endDate = date('Y-m-d');
            // $startDate = '2023-03-17';
            // $endDate = '2023-03-25';
        }
        $tanggal = [$startDate, $endDate];

        $jumlahKunjungan = $this->dashboard->jumlahKunjungan($tanggal, $clinic_id);

        return $jumlahKunjungan;
    }

    public function jumlahsuratsakit(Request $request)
    {
        $startDate = $request->start_date;
        $endDate = $request->end_date;
        $clinic_id = $request->clinic_id ?: (Auth::user()->role == 0 ? Auth::user()->clinic_id : null);
        // $clinic_id = 29;

        if (!$startDate || !$endDate) {
            $startDate = date('Y-m-01');
            $endDate = date('Y-m-d');
        }
        $tanggal = [$startDate, $endDate];

        $jumlahsuratsakit = $this->dashboard->jumlahsuratsakit($tanggal, $clinic_id);

        return response()->json([
            'jumlahsuratsakit' => $jumlahsuratsakit,
        ]);
    }
}
