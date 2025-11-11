<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;

class NotificationItem extends Model
{
    use HasFactory;

    protected $primaryKey = 'notificationId';
    public $incrementing = false;
    protected $keyType = 'string';
    protected $table = 'notifications';

    protected $fillable = [
        'notificationId','userId','type','message','relatedEntityId','timestamp','isRead'
    ];

    protected $casts = [
        'timestamp' => 'datetime',
        'isRead' => 'boolean'
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function($m){
            if(empty($m->{$m->getKeyName()})) $m->{$m->getKeyName()} = (string) Str::uuid();
            if(empty($m->timestamp)) $m->timestamp = now();
            if(!isset($m->isRead)) $m->isRead = false;
        });
    }
}
