<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Setting extends Model
{
    use HasFactory;

    protected $primaryKey = 'userId';
    public $incrementing = false;
    protected $keyType = 'string';
    protected $table = 'settings';
    public $timestamps = false;

    protected $fillable = [
        'userId','searchGender','minAge','maxAge','maxDistanceKm','isVisible','notificationPrefs'
    ];

    protected $casts = [
        'searchGender' => 'array',
        'notificationPrefs' => 'array',
    ];
}
