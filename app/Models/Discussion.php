<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;


class Discussion extends Model
{
    use HasFactory;
    protected $primaryKey = 'discussionId';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = ['discussionId','user1Id','user2Id','status','lastMessage','lastMessageTimestamp','unreadCount_U1','unreadCount_U2'];

    public function messages() {
        return $this->hasMany(DMessage::class, 'discussionId', 'discussionId');
    }
    protected static function boot()
{
    parent::boot();
    static::creating(function($m){
        if(empty($m->{$m->getKeyName()})) $m->{$m->getKeyName()} = (string) Str::uuid();
    });
}

}

