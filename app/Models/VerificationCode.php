<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class VerificationCode extends Model
{
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';
    protected $fillable = ['id','userId','contact','channel','code','expires_at','used'];
    protected $casts = ['expires_at' => 'datetime','used' => 'boolean'];
}
