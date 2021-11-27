<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\SoftDeletes;

class Account extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'employee_id', 'amount', 'date_added', 'employer_id'
    ];

    protected $dates = [
        'deleted_at', 
    ];
}
