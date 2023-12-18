<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vacancies extends Model
{
    protected $table = 'job_vacancies';
    protected $primaryKey = 'id';
    protected $guarded = [];

    public function availablePositions()
    {
        return $this->hasMany(AvailablePosition::class, 'job_vacancy_id', 'id');
    }

    public function jobCategories()
    {
        return $this->hasOne(JobCategories::class, 'id', 'job_category_id');
    }

    use HasFactory;
}
