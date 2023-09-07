<?php

namespace App\Http\Livewire;

use App\Models\Attendance\Attendance;
use App\Models\Auth\User;
use App\Models\LeaveEntitlements\UserLeaveQuota;
use App\Models\Notice\NoticeBoard;
use App\Models\ResearchOrders\OrderInfo;
use App\Models\ResearchOrders\OrderSubmissionInfo;
use App\Services\OrdersService;
use App\Services\ResearchOrderService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

use Livewire\Component;
use Illuminate\Support\Facades\Cache;


class Dashboard extends Component
{
    protected ResearchOrderService $researchOrderService;
    protected OrdersService $orderService;

    public function mount(ResearchOrderService $researchOrderService, OrdersService $orderService): void
    {
        $this->researchOrderService = $researchOrderService;
        $this->orderService = $orderService;
    }

    public function render()
    {
        $todayDate = Carbon::now()->toDateString(); // Example date, replace with Carbon::now()->toDateString() for the current date.

        $Today_orders_F_Deadline =  OrderSubmissionInfo::select('F_DeadLine', 'client_id', 'order_id')->whereDate('F_DeadLine',  $todayDate)
        ->get();

        $Today_orders_Deadline =  OrderSubmissionInfo::select('DeadLine', 'client_id', 'order_id')->whereDate('DeadLine',  $todayDate)
        ->get();

        $Today_orders_S_Deadline =  OrderSubmissionInfo::select('S_DeadLine', 'client_id', 'order_id')->whereDate('S_DeadLine',  $todayDate)
        ->get();

        $Today_orders_T_Deadline =  OrderSubmissionInfo::select('T_DeadLine', 'client_id', 'order_id')->whereDate('T_DeadLine',  $todayDate)
        ->get();

        // Merge the results into a single array
        $OrdersToday = $Today_orders_F_Deadline
        ->concat($Today_orders_Deadline)
        ->concat($Today_orders_S_Deadline)
        ->concat($Today_orders_T_Deadline)
        ->toArray();
        // dd($OrdersToday);

        $tomorrowDate = Carbon::tomorrow()->toDateString();

        $Tomorrow_orders_F_Deadline =  OrderSubmissionInfo::select('F_DeadLine', 'client_id', 'order_id')->whereDate('F_DeadLine',  $tomorrowDate)
        ->get();

        $Tomorrow_orders_Deadline =  OrderSubmissionInfo::select('DeadLine', 'client_id', 'order_id')->whereDate('DeadLine',  $tomorrowDate)
        ->get();

        $Tomorrow_orders_S_Deadline =  OrderSubmissionInfo::select('S_DeadLine', 'client_id', 'order_id')->whereDate('S_DeadLine',  $tomorrowDate)
        ->get();

        $Tomorrow_orders_T_Deadline =  OrderSubmissionInfo::select('T_DeadLine', 'client_id', 'order_id')->whereDate('T_DeadLine',  $tomorrowDate)
        ->get();

        // Merge the results into a single array
        $OrdersTomorrow = $Tomorrow_orders_F_Deadline
        ->concat($Tomorrow_orders_Deadline)
        ->concat($Tomorrow_orders_S_Deadline)
        ->concat($Tomorrow_orders_T_Deadline)
        ->toArray();


        $todayDate = Carbon::now()->toDateString();

        // Get orders with deadlines before today
        $Past_orders_F_Deadline = OrderSubmissionInfo::select('F_DeadLine', 'client_id', 'order_id')
        ->whereDate('F_DeadLine', '<', $todayDate)
        ->get();

        $Past_orders_Deadline = OrderSubmissionInfo::select('DeadLine', 'client_id', 'order_id')
        ->whereDate('DeadLine', '<', $todayDate)
        ->get();

        $Past_orders_S_Deadline = OrderSubmissionInfo::select('S_DeadLine', 'client_id', 'order_id')
        ->whereDate('S_DeadLine', '<', $todayDate)
        ->get();

        $Past_orders_T_Deadline = OrderSubmissionInfo::select('T_DeadLine', 'client_id', 'order_id')
        ->whereDate('T_DeadLine', '<', $todayDate)
        ->get();

        // Merge the results into a single array
        $OrdersPast = $Past_orders_F_Deadline
        ->concat($Past_orders_Deadline)
        ->concat($Past_orders_S_Deadline)
        ->concat($Past_orders_T_Deadline)
        ->toArray();



       

        $today = Carbon::now();
        $tomorrow = Carbon::tomorrow();
        $threeHoursLeft = Carbon::now()->subHours(3);
        $auth_user = Auth::guard('Authorized')->user();

        $Role_ID = (int)$auth_user->Role_ID;
        $User_ID = (int)$auth_user->id;

        $Previous_Order = $this->researchOrderService->getDeadLineOrders($today, $Role_ID, $User_ID,true);
        $Today_Order = $this->researchOrderService->getDeadLineOrders($today, $Role_ID, $User_ID,false);
        $Tomorrow_Order = $this->researchOrderService->getDeadLineOrders($today, $Role_ID, $User_ID,null);
        $Final_DeadLines = $this->researchOrderService->getThreeHoursDeadLineOrders($today, $threeHoursLeft, $Role_ID, $User_ID);

        $deadline_times = collect($Final_DeadLines)->map(function ($item) {
            $DeadLine = (!empty($item->submission_info->DeadLine))? strtotime($item->submission_info->DeadLine) : strtotime($item->DeadLine);
            $DeadLine_Time = (!empty($item->submission_info->DeadLine_Time))? strtotime($item->submission_info->DeadLine_Time) : strtotime($item->DeadLine_Time);
            $OrderID = (!empty($item->Order_ID))? $item->Order_ID : $item->order_info->Order_ID;
            $date = date('Y-m-d', $DeadLine) . ' ' . date('H:i:s', $DeadLine_Time);
            return [$OrderID, $date];
        });

        $statusCountsFlat = $this->orderService->getOrderCounts();
        $empCount = User::count();

        $lastAttendanceID = Attendance::whereDate('created_at', $today->format('Y-m-d'))
            ->where('user_id', $auth_user->id)
            ->first();

        $Leave_Quota = UserLeaveQuota::where('user_id', $auth_user->id)->first();
        $Get_Notice = NoticeBoard::latest('id')->get();





        return view('livewire.dashboard', compact('OrdersToday', 'OrdersPast','OrdersTomorrow','Previous_Order', 'Today_Order', 'Tomorrow_Order', 'auth_user', 'Final_DeadLines', 'deadline_times', 'statusCountsFlat', 'auth_user', 'empCount', 'lastAttendanceID', 'Leave_Quota', 'Get_Notice'))->layout('layouts.authorized');
    }
}
