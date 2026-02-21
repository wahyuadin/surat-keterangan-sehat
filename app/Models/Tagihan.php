<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use OwenIt\Auditing\Auditable as AuditingAuditable;
use OwenIt\Auditing\Contracts\Auditable;

class Tagihan extends Model implements Auditable
{
    use HasFactory, SoftDeletes, AuditingAuditable;
    protected $guarded = [];
    protected $casts = [
        'pasien' => 'array',
        'status_tagihan' => 'boolean',
    ];
    protected $auditEvents = [
        'created',
        'updated',
        'deleted',
        'restored',
    ];

    public function clinic()
    {
        return $this->belongsTo(Clinic::class, 'clinic_id');
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    public function agent()
    {
        return $this->belongsTo(Agent::class, 'agent_id');
    }

    public static function showData($id = null)
    {
        return $id ? self::where('id', $id)->with('customer', 'clinic')->first() : self::with('customer', 'clinic')->latest()->get();
    }

    public static function tambahData($data)
    {
        return self::create($data);
    }

    public static function editData($id)
    {
        $agent = self::findOrFail($id);
        $agent->fill(['status_tagihan' => 1]);
        $agent->save();
        return $agent;
    }

    public static function hapusData($id)
    {
        $agent = self::findOrFail($id);
        $agent->delete();
        return $agent;
    }

    public static function generateInvoiceNumber()
    {
        $count = self::withTrashed()->count() + 1;
        $bulan = now()->format('m');
        $tahun = now()->format('Y');
        return sprintf("%04d/INV/%s/%s", $count, $bulan, $tahun);
    }

    public static function tagihanByauditRole($where, $role)
    {
        $model = self::where($where, $role);
        $model->auditEvent = 'Generate Tagihan';
        $model->isCustomEvent = true;
        $model->auditCustomNew = [
            'tanggal'   => \Carbon\Carbon::now(),
            'keterangan' => 'User atas nama ' . Auth::user()->nama . ' telah membuat tagihan.',
            'data'      => $model->latest()->get(),
        ];
        return $model->get();
    }
}
