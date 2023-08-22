<?php

namespace App\Http\Livewire\Performance;

use App\Services\UsersPerformanceService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Livewire\Component;

class UserPerformance extends Component
{
    protected UsersPerformanceService $performanceService;

    public function mount(UsersPerformanceService $performanceService): void
    {
        $this->performanceService = $performanceService;
    }

    public function render(Request $request)
    {
        $Role_ID = (int)Crypt::decryptString($request->Role_ID);
        $EMP_ID = Crypt::decryptString($request->EMP_ID);
        $User_Performance = $this->performanceService->getUserPerformances($Role_ID, $EMP_ID);

        return view('livewire.performance.user-performance', compact('User_Performance', 'Role_ID', 'EMP_ID'))->layout('layouts.authorized');
    }
}
