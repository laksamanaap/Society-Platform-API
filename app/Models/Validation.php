<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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



    use HasFactory;
}
