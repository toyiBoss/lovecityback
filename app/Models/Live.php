<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;

class Live extends Model
{
    use HasFactory;
    protected $primaryKey = 'liveId';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = ['liveId','hostId','title','status','streamUrl','viewersCount','startTime'];
    protected static function boot()
{
    parent::boot();
    static::creating(function($m){
        if(empty($m->{$m->getKeyName()})) $m->{$m->getKeyName()} = (string) Str::uuid();
    });
}

}
