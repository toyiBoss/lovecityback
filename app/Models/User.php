<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use Illuminate\Support\Str;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

    protected $primaryKey = 'user_id';
    public $incrementing = false; // UUID
    protected $keyType = 'string';

    protected $fillable = [
        'user_id', 'name', 'first_name', 'bio', 'gender', 'birth_date','phone_number','photo_url',
        'country_id','auth_provider', 'city', 'galleryRef', 'settingsRef', 'email', 'password'
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (empty($model->user_id)) {
                $model->user_id = (string) Str::uuid();
            }
        });
    }
}
