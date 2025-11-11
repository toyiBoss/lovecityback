<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;

class Payment extends Model
{
    use HasFactory;

    protected $primaryKey = 'paymentId';
    public $incrementing = false;
    protected $keyType = 'string';
    protected $table = 'payments';

    protected $fillable = [
        'paymentId','userId','type','amount','currency','productId','timestamp','receiptData','status'
    ];

    protected $casts = [
        'amount' => 'float',
        'timestamp' => 'datetime'
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function($m){
            if(empty($m->{$m->getKeyName()})) $m->{$m->getKeyName()} = (string) Str::uuid();
            if(empty($m->timestamp)) $m->timestamp = now();
        });
    }
}
