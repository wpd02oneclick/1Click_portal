<?php

namespace App\Http\Livewire\LeaveEntitlements;

use App\Services\LeaveEntitlementService;
use Livewire\Component;

class UserLeaveQuotaView extends Component
{
    protected LeaveEntitlementService $leaveEntitlementService;
    public function mount(LeaveEntitlementService $leaveEntitlementService): void
    {
        $this->leaveEntitlementService = $leaveEntitlementService;
    }
    public function render()
    {
        $Leave_Quota = $this->leaveEntitlementService->getUserLeaveQuota();
        return view('livewire.leave-entitlements.user-leave-quota-view', compact('Leave_Quota'))->layout('layouts.authorized');
    }
}
