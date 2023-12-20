<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Validators extends Model
{

    protected $table = 'validators';
    protected $primaryKey = 'id';
    protected $guarded = [];

    use HasFactory;
}
