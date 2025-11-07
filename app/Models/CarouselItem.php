<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;

class CarouselItem extends Model
{
    use HasFactory;
    protected $primaryKey = 'itemId';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = ['itemId','title','imageUrl','linkUrl','order','isActive','targetCountryId'];

    protected static function boot()
{
    parent::boot();
    static::creating(function($m){
        if(empty($m->{$m->getKeyName()})) $m->{$m->getKeyName()} = (string) Str::uuid();
    });
}

}

