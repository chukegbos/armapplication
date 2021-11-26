<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Employee;
use App\EmployeeNOK;
use App\Employer;
use Illuminate\Support\Facades\Hash;
use App\User;
use Carbon\Carbon;
use Mail;
use Illuminate\Support\Facades\Input;

class AccountController extends Controller
{

    public function __construct()
    {
        $this->middleware('api');
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'employee_id' => 'required',
            'amount' => 'required',
        ]);

        $employee = Employee::where('deleted_at', NULL)->findOrFail($request->employee_id);
        if ((!$employee) || (auth('api')->user()->role!=3)) {
            return 'Not authorised';
        }

        $employer = Employer::where('deleted_at', NULL)->findOrFail($employee->employer_id);
        if (!$employer) {
            return 'Not authorised';
        }

        $user = User::where('deleted_at', NULL)->findOrFail($employer->user_id);

        if ($user && ($user->id==auth('api')->user()->id)) {
            $account = Account::create([
                'employee_id' => $request->employee,
                'employer_id' => auth('api')->user()->idee,
                'amount' => $request->amount,
                'date_added' => Carbon::now(),
            ]);
            return 'done';
        }
        
        return 'not authorise';
    }

    public function update(Request $request, $id)
    {

        $this->validate($request, [
            'surname' => 'required|string|max:255',
            'firstname' => 'required|string|max:255',
            'email' => 'required|string',
            'employer' => 'required',
            'mobile' => 'required',
        ]);

        $employee = Employee::where('deleted_at', NULL)->findOrFail($id);
        $employee->update($request->all());

        $nok = EmployeeNOK::updateOrCreate([
            'employee_id' => $id,
        ],[
            'surname' => $request->surname,
            'firstname' => $request->firstname,
            'email' => $request->email,
            'mobile' => $request->mobile,
            'employee_id' => $id,
        ]);
        return $employee;
    }

}
