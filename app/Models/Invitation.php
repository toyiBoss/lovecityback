<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;

class Invitation extends Model
{
    use HasFactory;

    protected $primaryKey = 'invitationId';
    public $incrementing = false;
    protected $keyType = 'string';
    protected $table = 'invitations';

    protected $fillable = [
        'invitationId','senderId','recipientContact','code','status','timestamp'
    ];

    protected $casts = [
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
