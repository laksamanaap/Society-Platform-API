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

    public function jobApply()
    {
        return $this->belongsTo(JobApplies::class, 'job_apply_societies_id', 'id');
    }

    public function availablePosition()
    {
        return $this->hasMany(AvailablePositions::class, 'position_id', 'id');
    }

    // Job Applies
    public function applications()  
    {
        return $this->hasMany(JobApplies::class, 'id', 'id');
    }

    public function vacancies() 
    {
        return $this->hasOne(JobVacancies::class, 'job_vacancy_id', 'id');
    }

    use HasFactory;
}
