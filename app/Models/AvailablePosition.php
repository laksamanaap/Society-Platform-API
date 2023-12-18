<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AvailablePosition extends Model
{

    protected $table = 'available_positions';
    protected $primaryKey = 'id';
    protected $guarded = [];

    public function vacancy()
    {
        return $this->belongsTo(Vacancies::class, 'vacancy_id', 'id');
    }

    use HasFactory;
}
