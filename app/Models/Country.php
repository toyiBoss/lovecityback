<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Country extends Model
{
    use HasFactory;

    protected $primaryKey = 'countryCode';
    public $incrementing = false;
    protected $keyType = 'string';
    protected $table = 'countries';
    public $timestamps = false;

    protected $fillable = [
        'countryCode','name','currency','isActive'
    ];

    protected $casts = [
        'isActive' => 'boolean'
    ];
}
