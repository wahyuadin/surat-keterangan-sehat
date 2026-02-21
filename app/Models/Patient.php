<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Auditable as AuditingAuditable;
use OwenIt\Auditing\Contracts\Auditable;

class Patient extends Model implements Auditable
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
        return $this->belongsTo(Clinic::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public static function showData($id = null)
    {
        return $id ? self::where('id', $id)->with('clinic', 'customer')->first() : self::with('clinic', 'customer')->latest()->get();
    }

    public static function tambahData($data)
    {
        return self::create($data);
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
}
