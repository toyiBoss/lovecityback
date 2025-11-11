<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;

class Favorite extends Model
{
    use HasFactory;

    protected $primaryKey = null;
    public $incrementing = false;
    protected $table = 'favorites';
    public $timestamps = false;

    protected $fillable = [
        'userId','targetId','timestamp'
    ];

    protected $casts = [
        'timestamp' => 'datetime'
    ];

    protected static function boot()
    {
        parent::boot();
        // composite key handled at application level; not generating uuid
    }
}
