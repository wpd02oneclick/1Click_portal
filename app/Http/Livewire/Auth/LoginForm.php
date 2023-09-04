<?php

namespace App\Http\Livewire\Auth;

use App\Helpers\PortalHelpers;
use App\Models\Auth\LoginHistory;
use App\Models\Auth\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;

class LoginForm extends Component
{
    public function render()
    {
        return view('livewire.auth.login-form')->layout('layouts.auth');
    }

    //  ======== User Login Function ==============
   

public function UserLogin(Request $request): RedirectResponse
{
    $validator = Validator::make($request->all(), [
        'Log_ID' => 'required',
        'Log_Password' => 'required'
    ]);

    if ($validator->fails()) {
        return back()->with('Error!', 'Validation failed. Please check your inputs.');
    }

    if (!Auth::guard('Authorized')->attempt([
        'EMP_ID' => $request->Log_ID,
        'password' => $request->Log_Password
    ])) {
        return back()->with('Error!', 'Unauthorized. Please check your credentials.');
    }

    $user = User::with('designation.module_permission', 'designation.other_permission')
        ->where('EMP_ID', $request->Log_ID)
        ->first();


    if ($user) {
        if (Hash::check($request->Log_Password, $user->password)) {
          
              
            Cache::forever('permission' , $user->designation->module_permission );
            Cache::forever('other_permission' , $user->designation->other_permission );

            LoginHistory::create([
                'user_id' => $user->id,
                'ip_address' => PortalHelpers::getIpAddress()
            ]);
            Artisan::call('attendance:mark-absent');
            return redirect()->route('Main.Dashboard')
                ->with('Success!', 'User Login Successfully!')
                ->with('Alert!', 'Mark Attendance!');
        }
        return back()->with('Error!', '400 Bad Request \n Wrong Password!');
    }
    return back()->with('Error!', '400 Bad Request \n User doesn\'t Exist! \n Invalid Email!');
}


    public function UserLogout(): RedirectResponse
    {
        Auth::guard('Authorized')->logout();
        Cache::forget('permission');
        Cache::forget('other_permission');        
        return redirect()->route('Auth.Forms')->with('Success!', 'User LogOut Successfully!');
    }
}
