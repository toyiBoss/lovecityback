<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class UserMatch extends Model
{
    use HasFactory;

    protected $primaryKey = 'matchId';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'matchId',
        'user1Id',
        'user2Id',
        'timestamp',
        'discussionRef',
        'lastMessageTimestamp',
        'status'
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $model->matchId = Str::uuid(); // Génère automatiquement l'UUID
        });
    }
}
