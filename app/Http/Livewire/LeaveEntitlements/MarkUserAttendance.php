<?php

namespace App\Http\Livewire\LeaveEntitlements;

use App\Helpers\PortalHelpers;
use App\Models\Attendance\Attendance;
use App\Models\Auth\User;
use App\Services\AttendanceService;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class MarkUserAttendance extends Component
{
    protected AttendanceService $attendanceService;

    public function mount(AttendanceService $attendanceService): void
    {
        $this->attendanceService = $attendanceService;
    }
    public function render()
    {
        $User_Attendance = $this->attendanceService->getUserAttendance();
        $currentDate = now()->toDateString();


        $UserInfo = User::orderBy('id', 'DESC')
            ->with(['attendance' => function ($query) use ($currentDate) {
                $query->whereDate('created_at', $currentDate);
            }])
            ->get();
        return view('livewire.leave-entitlements.mark-user-attendance', compact('User_Attendance', 'UserInfo'))->layout('layouts.authorized');
    }

    public function markAttendance(Request $request): RedirectResponse
    {
        try {
            DB::beginTransaction();
            $currentDate = $request->date ?? Carbon::now();
            $ip_address = PortalHelpers::getIpAddress();

            Attendance::create([
                'check_in' => $request->Shift_Start,
                'check_out' => $request->Shift_End,
                'user_id' => $request->User_ID,
                'status' => 0, // Assuming 0 represents an initial status
                'created_at' => $currentDate,
                'ip_address' => $ip_address
            ]);

            DB::commit();
            return redirect()->back()->with('success', 'Attendance has been marked successfully');
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->route('Error.Response', ['Message' => $e->getMessage()]);
        }
    }

    public function getMarkAttendance(Request $request): string
    {
        $Date = date('Y-m-d', strtotime($request->date));
        $UserInfo = User::orderBy('id', 'DESC')
            ->with(['attendance' => function ($query) use ($Date) {
                $query->whereDate('created_at', $Date);
            }])->get();

        $output = '<table class="table table-vcenter text-nowrap table-bordered border-bottom" id="hr-table">
        <thead>
            <tr>
                <th class="border-bottom-0 w-5">#Emp ID</th>
                <th class="border-bottom-0">Emp Name</th>
                <th class="border-bottom-0">Status</th>
                <th class="border-bottom-0">Clock In</th>
                <th class="border-bottom-0">Clock Out</th>
                <th class="border-bottom-0">IP Address</th>
                <th class="border-bottom-0">Working From</th>
                <th class="border-bottom-0">Attendance</th>
                <th class="border-bottom-0">Actions</th>
            </tr>
        </thead>
        <tbody>';

        foreach ($UserInfo as $Info) {
            $output .= '<tr>
            <td>' . $Info->EMP_ID. '</td>
            <td>
                <div class="d-flex">
                    <span class="avatar avatar-md brround me-3" style="background-image: url(../../assets/images/users/1.jpg)"></span>
                    <div class="me-3 mt-0 mt-sm-1 d-block">
                        <h6 class="mb-1 fs-14">' . $Info->basic_info->full_name . '</h6>
                    </div>
                </div>
            </td>';

            if ($Info->attendance->isEmpty()) {
                $output .= '<td><span>-</span></td>
                        <td>-</td>
                        <td>-</td>
                        <td>-</td>';
            } else {
                foreach ($Info->attendance as $attendance) {
                    $output .= '<td><span>' . ($attendance->status ?? '-') . '</span></td>
                            <td>' . ($attendance->check_in ?? '-') . '</td>
                            <td>' . ($attendance->check_out ?? '-') . '</td>
                            <td>' . ($attendance->ip_address ?? '-') . '</td>';
                }
            }

            $output .= '<td>Office</td>
            <td><span class="badge badge-success">Marked</span></td>
            <td>
                <div class="d-flex">
                    <label class="custom-control custom-checkbox-md">
                        <input type="checkbox" data-id="' . $Info->id . '" class="custom-control-input-success" name="Mark-Attandence" value="option1" id="Mark-Attandence">
                        <span class="custom-control-label-md success"></span>
                    </label>
                    <a href="#" class="action-btns1 bg-light" data-bs-toggle="modal" data-bs-target="#presentmodal">
                        <i class="feather feather-eye primary text-primary" data-bs-toggle="tooltip" data-original-title="View"></i>
                    </a>
                </div>
            </td>
        </tr>';
        }

        $output .= '</tbody>
    </table>';

        return $output;
    }
}
