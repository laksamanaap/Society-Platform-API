<?php

namespace App\Models;

use App\Models\Validators;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Validation extends Model
{

    protected $table = 'validations';
    protected $primaryKey = 'id';
    protected $fillable = [
        'work_experience',
        'job_category_id',
        'job_position',
        'reason_accepted',
        'society_id',
        'validator_id',
        'status',
        'validator_notes'
    ];
    protected $guarded=[];
    public $timestamps = false;
    
     public function user()
    {
        return $this->belongsTo(User::class, 'id', 'id');
    }

    public function jobCategories()
    {
        return $this->hasOne(JobCategories::class, 'id', 'job_category_id');
    }

    public function validators()
    {
        return $this->hasOne(Validators::class, 'id', 'validator_id');
    }



    use HasFactory;
}
