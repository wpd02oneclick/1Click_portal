<?php

namespace App\Http\Livewire\LeaveEntitlements;

use App\Models\LeaveEntitlements\LeaveRequest;
use App\Models\LeaveEntitlements\UserLeaveQuota;
use App\Services\LeaveEntitlementService;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class ReceivedLeaveRequest extends Component
{
    protected LeaveEntitlementService $leaveEntitlementService;
    public function mount(LeaveEntitlementService $leaveEntitlementService): void
    {
        $this->leaveEntitlementService = $leaveEntitlementService;
    }
    public function render()
    {
        $auth_user = Auth::guard('Authorized')->user();
        $Leave_Request = $this->leaveEntitlementService->getLeavesRequests((int)$auth_user->Role_ID, (int)$auth_user->id);
        $Total_Leaves = (int)$this->leaveEntitlementService->getTotalSumLeaves();
        return view('livewire.leave-entitlements.recieved-leave-request', compact('Leave_Request', 'Total_Leaves'))->layout('layouts.authorized');
    }

    public function acceptLeaveRequest(Request $request): RedirectResponse
    {
        try {
            DB::beginTransaction();
            $Leave_Info = LeaveRequest::where('id', $request->Leave_ID)->firstOrFail();
            $User_Info = UserLeaveQuota::where('user_id', $Leave_Info->user_id)->firstOrFail();

            $F_Date = date('F d, Y H:i:s A', strtotime($Leave_Info->F_Date));
            $L_Date = $Leave_Info->L_Date ? date('F d, Y H:i:s A', strtotime($Leave_Info->L_Date)) : null;
            $totalLeaves = self::getDays($F_Date, $L_Date);

            if ($Leave_Info->leave_id === 1) {
                $User_Info->Annual_Leaves -= $totalLeaves;
            }
            if ($Leave_Info->leave_id === 2) {
                $User_Info->Casual_Leaves -= $totalLeaves;
            }
            if ($Leave_Info->leave_id === 3) {
                $User_Info->Sick_Leaves -= $totalLeaves;
            }
            if ($Leave_Info->leave_id === 4) {
                $User_Info->Un_Paid .= $totalLeaves;
            }
            $Leave_Info->Leave_Status = 1;
            $Leave_Info->approved_by = $request->Approved_ID;

            if ($Leave_Info->update() && $User_Info->update()){
                DB::commit();
                return back()->with('Success!', 'Leave Request has been Accepted');
            }
            DB::rollBack();
            return back()->with('Error!', 'Something Went Wrong!');
        } catch (\Exception $exception){
            DB::rollBack();
            return redirect()->route('Error.Response', ['Message' => $exception->getMessage()]);
        }
    }

    public function rejectLeaveRequest(Request $request): RedirectResponse
    {
        try {
            DB::beginTransaction();
            $Leave_Info = LeaveRequest::where('id', $request->Leave_ID)->firstOrFail();
            $Leave_Info->Leave_Status = 2;
            if ($Leave_Info->update()){
                DB::commit();
                return back()->with('Success!', 'Leave Request has been Rejected!');
            }
            DB::rollBack();
            return back()->with('Error!', 'Something Went Wrong!');
        } catch (\Exception $exception){
            DB::rollBack();
            return redirect()->route('Error.Response', ['Message' => $exception->getMessage()]);
        }
    }

    private static function getDays($date, $currentDate = null): string
    {
        $createdAtDate = Carbon::createFromFormat('F d, Y H:i:s A', $date, env('APP_TIMEZONE'))->startOfDay();
        if (!is_null($currentDate)){
            $currentDate = Carbon::createFromFormat('F d, Y H:i:s A', $currentDate, env('APP_TIMEZONE'))->startOfDay();
        }
        if (is_null($currentDate)){
            $currentDate = Carbon::parse($currentDate ?? 'now', env('APP_TIMEZONE'))->startOfDay();
        }

        return $currentDate->diffInDays($createdAtDate);
    }
}
