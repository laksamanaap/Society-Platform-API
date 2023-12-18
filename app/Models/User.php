<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{

    protected $primaryKey = 'id';
    protected $table = 'societies';
    protected $fillable = [
        'login_tokens',
    ];
    public $timestamps = false;



    public function regionals()
    {
        return $this->hasOne(Regionals::class, 'id', 'id');
    }


    public function applications()  
    {
        return $this->hasMany(JobApplies::class, 'id', 'id');
    }

    // Temporary 
    public function positions()
    {
        return $this->hasMany(JobPositions::class, 'society_id', 'id'); // Foreign, Local
    }

    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    

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
    
     public function validations()
    {
        return $this->hasMany(Validation::class, 'society_id', 'id');
    }

}
