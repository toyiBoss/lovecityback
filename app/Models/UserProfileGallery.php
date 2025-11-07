<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserProfileGallery extends Model
{
    use HasFactory;

    protected $primaryKey = 'mediaId';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'mediaId',
        'user_id',
        'storagePath',
        'order',
        'isVerified',
        'timestamp',
    ];

    protected $casts = [
        'isVerified' => 'boolean',
        'timestamp' => 'datetime',
    ];

    public function user()
    {
        // Adapte la clé étrangère / clé primaire du modèle User si nécessaire.
        // Si ton User a une colonne 'user_id' (UUID), c'est correct.
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }
}
