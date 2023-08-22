<?php

namespace App\Http\Livewire\Auth;

use App\Models\Auth\LoginHistory;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class ViewLoginHistory extends Component
{
    public function render()
    {
        $loginHistory = LoginHistory::latest('id')
            ->where('user_id', Auth::guard('Authorized')->user()->id)
            ->get();
        return view('livewire.auth.view-login-history', compact('loginHistory'))->layout('layouts.authorized');
    }
}
