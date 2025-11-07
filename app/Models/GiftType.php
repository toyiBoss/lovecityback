<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;

class GiftType extends Model
{
    use HasFactory;
    protected $primaryKey = 'giftId';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = ['giftId','name','costInCoins','imageUrl','isActive'];
    protected static function boot()
{
    parent::boot();
    static::creating(function($m){
        if(empty($m->{$m->getKeyName()})) $m->{$m->getKeyName()} = (string) Str::uuid();
    });
}

}

