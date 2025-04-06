<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserLocation extends Model
{
    use SoftDeletes, HasFactory;

    protected $table = 'user_locations';
    protected $fillable = [
        'user_id',
        'city', 
        'country'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
