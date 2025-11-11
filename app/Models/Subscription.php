<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;

class Subscription extends Model {
    use HasFactory;
    protected $primaryKey = 'subscriptionId';
    public $incrementing = false;
    protected $keyType = 'string';
    protected $fillable = ['subscriptionId','name','durationDays','price','features','storeProductId'];

    protected static function boot(){
        parent::boot();
        static::creating(function($m){
            if(empty($m->{$m->getKeyName()})) $m->{$m->getKeyName()} = (string) Str::uuid();
        });
    }

    protected $casts = ['features'=>'array'];
}
