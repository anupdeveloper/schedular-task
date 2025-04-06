<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserDetail extends Model
{
    use SoftDeletes, HasFactory;

    protected $table = 'user_details';
    protected $fillable = [
        'user_id', 
        'gender'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
