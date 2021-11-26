<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\SoftDeletes;

class EmployeeNOK extends Model
{
    use SoftDeletes;
    protected $table = 'employee_next_of_kin';
    protected $fillable = [
        'employee_id', 'surname', 'firstname', 'email', 'mobile',
    ];

    protected $dates = [
        'deleted_at', 
    ];
}
