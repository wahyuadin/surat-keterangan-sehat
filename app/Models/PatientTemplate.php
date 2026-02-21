<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditingAuditable;

class PatientTemplate extends Model implements Auditable
{
    use HasFactory, SoftDeletes, AuditingAuditable;
    protected $guarded = [];
    protected $auditEvents = [
        'created',
        'updated',
        'deleted',
        'restored',
    ];

    public function clinic()
    {
        return $this->belongsTo(Clinic::class, 'clinic_id', 'id');
    }

    public static function showData($id = null)
    {
        return $id ? self::where('id', $id)->with('clinic')->first() : self::latest()->with('clinic')->get();
    }

    public static function tambahData($data)
    {
        return self::create($data);
    }

    public static function editData($id, $data)
    {
        $dataEdit = self::where('id', $id)->first();
        $dataEdit->fill($data);
        $dataEdit->update();
        return $dataEdit;
    }

    public static function hapusData($id)
    {
        $data = self::where('id', $id)->first();
        $data->delete();
        return $data;
    }
}
