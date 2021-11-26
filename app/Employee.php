<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Employer;
use App\Employee;
use App\EmployeeNOK;

class Employee extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'surname', 'firstname', 'email', 'mobile', 'unique_account', 'employer_id', 'user_id', 'employer_detail', 'nok',
    ];

    protected $dates = [
        'deleted_at', 
    ];

    public function getEmployerDetailAttribute()
    {
        $id = $this->attributes['employer_id'];
        $employer = Employer::find($id);
        if ($employer) {
            return $employer;   
        }
        else {
            return NULL;
        }
    }

    public function getNokAttribute()
    {
        $unique_account = $this->attributes['unique_account'];

        $employee = Employee::where('unique_account', $unique_account)->first();
        $employee_id = $employee->id;
        $employeeNok = EmployeeNOK::where('employee_id', $employee_id)->first();
        
        if ($employeeNok) {
            return $employeeNok;   
        }
        else {
            return NULL;
        }
    }
}
