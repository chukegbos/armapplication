<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\SoftDeletes;

class Employer extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'name', 'email', 'mobile', 'office_address', 'unique_account', 'user_id'
    ];

    protected $dates = [
        'deleted_at', 
    ];
}
