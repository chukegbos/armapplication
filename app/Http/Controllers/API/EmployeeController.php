<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Employee;
use Illuminate\Support\Facades\Hash;
use App\User;
use Carbon\Carbon;
use Mail;
use Illuminate\Support\Facades\Input;

class EmployeeController extends Controller
{
    public function index()
    {
        
        return Employee::where('deleted_at', NULL)->get();
    }



    public function show($id)
    {
        return Employee::where('deleted_at', NULL)->findOrFail($id);
    }

    public function store(Request $request)
    {

        $this->validate($request, [
            'surname' => 'required|string|max:255',
            'firstname' => 'required|string|max:255',
            'email' => 'required|string',
            'mobile' => 'required',
        ]);
        $otp = rand(1000, 9999);
        
        $user = User::create([
            'name' => ucwords($request->surname).' '.ucwords($request->firstname),
            'email' => $request->email,
            'role' => 2,
            'password' => Hash::make($request->password),
            'otp' => $otp,
        ]);

        $employee = Employee::create([
            'surname' => ucwords($request->surname),
            'firstname' => ucwords($request->firstname),
            'employer_id' => $request->employer,
            'email' => $request->email,
            'mobile' => $request->mobile,
            'user_id' => $user->id,
        ]);

        $input = Input::only(
            'name',
            'email',
        );

        /*if ($employee) {
            $otp = rand(1000, 9999);

            $data = array('otp'=> $otp, 'name'=> Input::get('name'));
            Mail::send('emails.sendotp', $data, function($message) {
                $message
                    ->to(Input::get('email'), Input::get('name'))
                    ->subject('Confirm OTP');
            });
        }*/

        return $otp.' is the OTP sent to the employee to use as verification code.';
    }

    public function verifyotp(Request $request)
    {
        $this->validate($request, [
            'email' => 'required',
            'otp' => 'required|integer',
        ]);


        $user = User::where('deleted_at', NULL)->where('email', $request->email)->first();
        $employee = Employee::where('deleted_at', NULL)->where('email', $request->email)->first();
        
        if (($user) && ($employee) && ($user->otp == $request->otp)) {
            $unique_code = 'EEM'.sprintf('%012d', $employee->id);
        
            //update user info
            $user->status = 1;
            $user->username = $unique_code;
            $user->email_verified_at = Carbon::now();
            $user->update();


            //Update employer info
            $employee->unique_account = $unique_code;
            $employee->update();
            return $unique_code;
        }
       
        else {
            return  'OTP did not match';
        }
        
    }
    public function update(Request $request, $id)
    {
        $employee = Employee::where('deleted_at', NULL)->findOrFail($id);
        $employee->update($request->all());

        return $employee;
    }

    public function delete(Request $request, $id)
    {
        $employee = Employee::where('deleted_at', NULL)->findOrFail($id);
        $employee->delete();

        return 204;
    }
}
