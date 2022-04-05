<?php

namespace App\Models;

use App\Models\Store;
use App\Models\productLine;
use App\Models\UserProfile;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Traits\HasRoles;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Models\Permission;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements JWTSubject
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    
    protected $fillable = [
        'name',
        'email',
        'password',
        
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     * 
     * @return mixed
     */

     public function getJWTIdentifier()
     {
       return $this->getkey();
     }

     /**
      * Return a key value array, containing any custom claims to be added to JWT.
      *
      *@return array
      */
      public function getJWTCustomClaims()
      {
          return [];
      }
      public function setPasswordAttribute($password){
          if(!empty($password)){
              $this->attributes['password'] = bcrypt($password);

          }
      }

      public function  userProfile()
      {
        return $this->hasOne(UserProfile::class);
      }

      public function stores(){
          return $this->hasMany(Store::class, 'owner_id');
      }

      public function productLine(){
        return $this->hasMany(productLine::class,'owners_id');
    }
  

      

      

   

  

}
