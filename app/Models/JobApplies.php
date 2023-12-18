<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobApplies extends Model
{

    public $timestamps = false;  
    protected $table = 'job_apply_societies';
    protected $primaryKey = 'id';
    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class, 'id', 'id');
    }

    public function positions()
    {
        return $this->belongsTo(JobPositions::class, 'id', 'id');
    }

    use HasFactory;
}
