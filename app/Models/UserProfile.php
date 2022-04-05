<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UserProfile extends Model
{
    use HasFactory;

    
    
    

    protected $fillable = [
        'firstname',
        'lastname',
        'gender',
        'active',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);

    }
}
