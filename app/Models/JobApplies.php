<?php

namespace App\Models;

use App\Models\Vacancies;
use App\Models\AvailablePosition;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

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
        return $this->hasMany(JobPositions::class, 'job_apply_societies_id', 'id');
    }

    public function vacancies() 
    {
        return $this->hasOne(Vacancies::class, 'id', 'job_vacancy_id');
    }

    public function availablePosition()
    {
        return $this->hasMany(AvailablePosition::class, 'id', 'position_id');
    }

    public function jobCategories()
    {
        return $this->hasOne(JobCategories::class, 'id', 'job_category_id');
    }

    public function jobApplyPosition()
    {
        return $this->hasOne(JobPositions::class, 'job_apply_societies_id');
    }

    

    use HasFactory;
}
