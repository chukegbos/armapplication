<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\SoftDeletes;

class Role extends Model
{
	use SoftDeletes;
    protected $fillable = [
        'title'
    ];

    protected $dates = [
        'deleted_at', 
    ];
}
