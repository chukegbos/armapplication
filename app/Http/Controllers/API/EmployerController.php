<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Employer;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use App\User;
use Mail;
use Illuminate\Support\Facades\Input;

class EmployerController extends Controller
{
    public function index()
    {
        
        return Employer::where('deleted_at', NULL)->get();
    }

    public function show($id)
    {
        return Employer::where('deleted_at', NULL)->findOrFail($id);
    }


    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string|max:255',
            'email' => 'required|string',
            'mobile' => 'required',
            'office_address' => 'required',
        ]);

        $otp = rand(1000, 9999);

        $user = User::create([
            'name' => ucwords($request->name),
            'email' => $request->email,
            'role' => 3,
            'password' => Hash::make($request->password),
            'otp' => $otp,
        ]);

        $employer = Employer::create([
            'name' => ucwords($request->name),
            'email' => $request->email,
            'mobile' => $request->mobile,
            'office_address' =>$request->office_address,
            'user_id' => $user->id,
        ]);

        $input = Input::only(
            'name',
            'email',
        );

        /*if ($employer) {
            

            $data = array('otp'=> $otp, 'name'=> Input::get('name'));
            Mail::send('emails.sendotp', $data, function($message) {
                $message
                    ->to(Input::get('email'), Input::get('name'))
                    ->subject('Confirm OTP');
            });
        }*/
        return $otp.' is the OTP sent to the employer to use as verification code.';
    }

    public function verifyotp(Request $request)
    {
        $this->validate($request, [
            'email' => 'required',
            'otp' => 'required|integer',
        ]);

        $user = User::where('deleted_at', NULL)->where('email', $request->email)->first();
        $employer = Employer::where('deleted_at', NULL)->where('email', $request->email)->first();
        
        if (($user) && ($employer) && ($user->otp == $request->otp)) {
            $unique_code = 'EMP'.sprintf('%012d', $employer->id);
            
            //update user info
            $user->status = 1;
            $user->username = $unique_code;
            $user->email_verified_at = Carbon::now();
            $user->update();


            //Update employer info
            $employer->unique_account = $unique_code;
            $employer->update();
            return $unique_code;
        }
       
        else {
            return 'OTP did not match';
        }   
    }

    public function update(Request $request, $id)
    {
        $employer = Employer::where('deleted_at', NULL)->findOrFail($id);
        $employer->update($request->all());
        return $employer;
    }

    public function delete(Request $request, $id)
    {
        $employer = Employer::where('deleted_at', NULL)->findOrFail($id);
        $employer->delete();

        return 204;
    }
}
