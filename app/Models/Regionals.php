<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Regionals extends Model
{

    protected $primaryKey = 'id';
    protected $table = 'regionals';
    protected $fillable = [];

    public function user()
    {
        return $this->belongsTo(User::class, 'id', 'id');
    }
    use HasFactory;
}
