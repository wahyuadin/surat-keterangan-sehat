<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Event;
use OwenIt\Auditing\Auditable as AuditingAuditable;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Events\AuditCustom;

class Transaksi extends Model implements Auditable
{
    use HasFactory, SoftDeletes, AuditingAuditable;
    protected $guarded = [];
    protected $auditEvents = [
        'created',
        'updated',
        'deleted',
        'restored',
    ];


    public function patient()
    {
        return $this->belongsTo(Patient::class, 'patient_id');
    }

    public function paramedis()
    {
        return $this->belongsTo(Paramedis::class, 'paramedis_id');
    }

    public function agent()
    {
        return $this->belongsTo(Agent::class, 'agent_id')->withTrashed();
    }

    public static function showData($id = null)
    {
        return $id ? self::whereHas('patient.clinic', function ($q) use ($id) {
            $q->where('id', $id);
        })->with('patient.clinic', 'patient.customer', 'paramedis', 'agent')->latest() : self::with('patient.clinic', 'patient.customer', 'paramedis', 'agent')->latest();
    }

    public static function showDataCustomer($id = null)
    {
        return $id ? self::whereHas('patient.customer', function ($q) use ($id) {
            $q->where('id', $id);
        })->with('patient.clinic', 'patient.customer', 'paramedis', 'agent')->latest() : self::with('patient.clinic', 'patient.customer', 'paramedis', 'agent')->latest();
    }

    public static function tambahData($data)
    {
        $model = self::create($data);
        $model->auditEvent = 'created';
        $model->isCustomEvent = true;
        $model->auditCustomNew = [
            'print'     => 'User dengan atas nama ' . Auth::user()->nama . ' Telah membuat surat SKD',
            'tanggal'   => \Carbon\Carbon::now(),
            'data'      => $data,
        ];
        return $model->id;
    }

    public static function editData($id, $data)
    {
        $agent = self::findOrFail($id);
        $agent->fill($data);
        $agent->save();
        return $agent;
    }

    public static function hapusData($id)
    {
        $agent = self::findOrFail($id);
        $agent->delete();
        return $agent;
    }

    public static function showDatabyAudit($id)
    {
        $model = Transaksi::with('paramedis.clinic', 'patient')->find($id);
        $model->auditEvent = 'Monitoring Print';
        $model->isCustomEvent = true;
        $model->auditCustomNew = [
            'print'     => 'User dengan atas nama ' . Auth::user()->nama . ' Telah membuat surat SKD',
            'tanggal'   => \Carbon\Carbon::now(),
        ];
        Event::dispatch(AuditCustom::class, [$model]);
        return $model->toArray();
    }
}
