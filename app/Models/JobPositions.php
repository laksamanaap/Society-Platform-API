<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobPositions extends Model
{

    protected $table = 'job_apply_positions';
    protected $primaryKey = 'id';
    protected $guarded = [];

    public $timestamps = false;

    use HasFactory;
}
