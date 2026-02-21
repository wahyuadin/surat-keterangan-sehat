<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use OwenIt\Auditing\Auditable as AuditingAuditable;
use OwenIt\Auditing\Contracts\Auditable;

class Agent extends Model implements Auditable
{
    use HasFactory, SoftDeletes, AuditingAuditable;
    protected $guarded = [];
    protected $auditEvents = [
        'created',
        'updated',
        'deleted',
        'restored',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    public function clinic()
    {
        return $this->belongsTo(Clinic::class, 'clinic_id');
    }

    public static function showData($id = null)
    {
        return $id ? self::find($id)->with('customer', 'clinic')->first() : self::with('customer', 'clinic')->latest()->get();
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

    public static function getAgent($customer_id, $clinic_id)
    {
        if (Auth::user()->role == 0) {
            return self::where('customer_id', null)
                ->where('clinic_id', $clinic_id)
                ->with('customer', 'clinic')
                ->latest()
                ->get();
        } else if (Auth::user()->role == 1) {
            return self::where('clinic_id', $clinic_id)
                ->with('customer', 'clinic')
                ->latest()
                ->get();
        }
        return self::where('customer_id', $customer_id)
            ->where('clinic_id', $clinic_id)
            ->with('customer', 'clinic')
            ->latest()
            ->get();
    }
}
