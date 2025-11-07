<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;

class DMessage extends Model
{
    use HasFactory;
    protected $primaryKey = 'messageId';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = ['messageId','discussionId','senderId','text','mediaUrl','type','timestamp','isSeen'];
    protected static function boot()
{
    parent::boot();
    static::creating(function($m){
        if(empty($m->{$m->getKeyName()})) $m->{$m->getKeyName()} = (string) Str::uuid();
    });
}

}
