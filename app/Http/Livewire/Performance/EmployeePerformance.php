<?php

namespace App\Http\Livewire\Performance;

use App\Services\UsersPerformanceService;
use Livewire\Component;

class EmployeePerformance extends Component
{
    protected UsersPerformanceService $performanceService;

    public function mount(UsersPerformanceService $performanceService): void
    {
        $this->performanceService = $performanceService;
    }

    public function render()
    {
        $Research_Writer_Performance = $this->performanceService->getResearchWriterPerformances();
        $Coordinator_Performance = $this->performanceService->getCoordinatorPerformances();
        $Manager_Performance = $this->performanceService->getManagerPerformances();
        $Content_Writer_Performance = $this->performanceService->getContentWriterPerformances();
        $Hr_Performance = $this->performanceService->getHumanResourcePerformances();

//        dd($Research_Writer_Performance->toArray());

        return view('livewire.performance.employee-performance', compact('Research_Writer_Performance', 'Coordinator_Performance', 'Manager_Performance', 'Content_Writer_Performance', 'Hr_Performance'))->layout('layouts.authorized');
    }
}
