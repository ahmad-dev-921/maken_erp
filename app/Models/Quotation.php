<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Quotation extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'customer_id',
        'reference_name',
        'items',
        'total',
        'date',
        'expiry_date',
    ];

    protected $casts = [
        'items' => 'array',
        'total' => 'float',
        'date' => 'date:Y-m-d',
        'expiry_date' => 'date:Y-m-d',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}
