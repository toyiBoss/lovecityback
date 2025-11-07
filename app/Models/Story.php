<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Story extends Model
{
    use HasFactory;

    protected $primaryKey = 'storyId';
    public $incrementing = false;
    protected $keyType = 'string';

    // correspond aux colonnes dans ta migration
    protected $fillable = [
        'storyId',
        'userId',
        'storagePath',
        'type',          // image | video | text
        'caption',
        'timestamp',
        'expiresAt',
        'views'
    ];

    protected $casts = [
        'timestamp' => 'datetime',
        'expiresAt' => 'datetime',
        'views' => 'array', // JSON -> tableau
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->storyId)) {
                $model->storyId = (string) Str::uuid();
            }
            if (empty($model->timestamp)) {
                $model->timestamp = now();
            }
            // par défaut expire dans 24h si pas précisé
            if (empty($model->expiresAt)) {
                $model->expiresAt = now()->addHours(24);
            }
            if (! isset($model->views)) {
                $model->views = [];
            }
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'userId', 'user_id'); 
        // adapte le 'user_id' du User si ta colonne PK est 'user_id' ; 
        // sinon utilise 'id' si c'est id.
    }
}
