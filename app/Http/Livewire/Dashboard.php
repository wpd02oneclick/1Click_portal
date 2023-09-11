<?php

namespace App\Http\Livewire;

use App\Models\Attendance\Attendance;
use App\Models\Auth\User;
use App\Models\ContentOrders\ContentBasicInfo;
use App\Models\LeaveEntitlements\UserLeaveQuota;
use App\Models\Notice\NoticeBoard;
use App\Models\ResearchOrders\OrderAssigningInfo;
use App\Models\ResearchOrders\OrderInfo;
use App\Models\ResearchOrders\OrderSubmissionInfo;
use App\Services\OrdersService;
use App\Services\ResearchOrderService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Illuminate\Support\Facades\Cache;
use PDO;

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
        $todayDate = Carbon::now()->toDateString();
        $Today_orders_F_Deadline = OrderSubmissionInfo::select('F_DeadLine', 'client_id', 'order_id')->whereDate('F_DeadLine', $todayDate)->get();
        $Today_orders_Deadline = OrderSubmissionInfo::select('DeadLine', 'client_id', 'order_id')->whereDate('DeadLine', $todayDate)->get();
        $Today_orders_S_Deadline = OrderSubmissionInfo::select('S_DeadLine', 'client_id', 'order_id')->whereDate('S_DeadLine', $todayDate)->get();
        $Today_orders_T_Deadline = OrderSubmissionInfo::select('T_DeadLine', 'client_id', 'order_id')->whereDate('T_DeadLine', $todayDate)->get();
        $OrdersToday = $Today_orders_F_Deadline->concat($Today_orders_Deadline)->concat($Today_orders_S_Deadline)->concat($Today_orders_T_Deadline)->toArray();
        $tomorrowDate = Carbon::tomorrow()->toDateString();
        $Tomorrow_orders_F_Deadline = OrderSubmissionInfo::select('F_DeadLine', 'client_id', 'order_id')->whereDate('F_DeadLine', $tomorrowDate)->get();
        $Tomorrow_orders_Deadline = OrderSubmissionInfo::select('DeadLine', 'client_id', 'order_id')->whereDate('DeadLine', $tomorrowDate)->get();
        $Tomorrow_orders_S_Deadline = OrderSubmissionInfo::select('S_DeadLine', 'client_id', 'order_id')->whereDate('S_DeadLine', $tomorrowDate)->get();
        $Tomorrow_orders_T_Deadline = OrderSubmissionInfo::select('T_DeadLine', 'client_id', 'order_id')->whereDate('T_DeadLine', $tomorrowDate)->get();
        $OrdersTomorrow = $Tomorrow_orders_F_Deadline->concat($Tomorrow_orders_Deadline)->concat($Tomorrow_orders_S_Deadline)->concat($Tomorrow_orders_T_Deadline)->toArray();
        $todayDate = Carbon::now()->toDateString();
        $Past_orders_F_Deadline = OrderSubmissionInfo::select('F_DeadLine', 'client_id', 'order_id')->whereDate('F_DeadLine', '<', $todayDate)->get();
        $Past_orders_Deadline = OrderSubmissionInfo::select('DeadLine', 'client_id', 'order_id')->whereDate('DeadLine', '<', $todayDate)->get();
        $Past_orders_S_Deadline = OrderSubmissionInfo::select('S_DeadLine', 'client_id', 'order_id')->whereDate('S_DeadLine', '<', $todayDate)->get();
        $Past_orders_T_Deadline = OrderSubmissionInfo::select('T_DeadLine', 'client_id', 'order_id')->whereDate('T_DeadLine', '<', $todayDate)->get();
        $OrdersPast = $Past_orders_F_Deadline->concat($Past_orders_Deadline)->concat($Past_orders_S_Deadline)->concat($Past_orders_T_Deadline)->toArray();
        $auth_user = Auth::guard('Authorized')->user();
        $User_ID = (int) $auth_user->id;
        $cordinatorTodayDeadLine = OrderInfo::with(['submission_info' => function ($query) {
            $query->select('id', 'DeadLine', 'order_id');
        }])->whereHas('submission_info', function ($q) use ($todayDate) {
            $q->whereDate('DeadLine', $todayDate);
        })->whereHas('assign', function ($q) use ($User_ID) {
            $q->where('assign_id', $User_ID);
        })->get();
        $cordinatorTodayFirstDraft = OrderInfo::with(['submission_info' => function ($query) {
            $query->select('id', 'F_DeadLine', 'order_id');
        }])->whereHas('submission_info', function ($q) use ($todayDate) {
            $q->whereDate('F_DeadLine', $todayDate);
        })->whereHas('assign', function ($q) use ($User_ID) {
            $q->where('assign_id', $User_ID);
        })->get();
        $cordinatorTodaySecondDraft = OrderInfo::with(['submission_info' => function ($query) {
            $query->select('id', 'S_DeadLine', 'order_id');
        }])->whereHas('submission_info', function ($q) use ($todayDate) {
            $q->whereDate('S_DeadLine', $todayDate);
        })->whereHas('assign', function ($q) use ($User_ID) {
            $q->where('assign_id', $User_ID);
        })->get();
        $cordinatorTodayThirdDraft = OrderInfo::with(['submission_info' => function ($query) {
            $query->select('id', 'T_DeadLine', 'order_id');
        }])->whereHas('submission_info', function ($q) use ($todayDate) {
            $q->whereDate('T_DeadLine', $todayDate);
        })->whereHas('assign', function ($q) use ($User_ID) {
            $q->where('assign_id', $User_ID);
        })->get();
        $CordinatorTodayAll = $cordinatorTodayDeadLine->concat($cordinatorTodayFirstDraft)->concat($cordinatorTodaySecondDraft)->concat($cordinatorTodayThirdDraft)->toArray();
        $cordinatorTomorrowDeadLine = OrderInfo::with(['submission_info' => function ($query) {
            $query->select('id', 'DeadLine', 'order_id');
        }])->whereHas('submission_info', function ($q) use ($tomorrowDate) {
            $q->whereDate('DeadLine', $tomorrowDate);
        })->whereHas('assign', function ($q) use ($User_ID) {
            $q->where('assign_id', $User_ID);
        })->get();
        $cordinatorTomorrowFirstDraft = OrderInfo::with(['submission_info' => function ($query) {
            $query->select('id', 'F_DeadLine', 'order_id');
        }])->whereHas('submission_info', function ($q) use ($tomorrowDate) {
            $q->whereDate('F_DeadLine', $tomorrowDate);
        })->whereHas('assign', function ($q) use ($User_ID) {
            $q->where('assign_id', $User_ID);
        })->get();
        $cordinatorTomorrowSecondDraft = OrderInfo::with(['submission_info' => function ($query) {
            $query->select('id', 'S_DeadLine', 'order_id');
        }])->whereHas('submission_info', function ($q) use ($tomorrowDate) {
            $q->whereDate('S_DeadLine', $tomorrowDate);
        })->whereHas('assign', function ($q) use ($User_ID) {
            $q->where('assign_id', $User_ID);
        })->get();
        $cordinatorTomorrowThirdDraft = OrderInfo::with(['submission_info' => function ($query) {
            $query->select('id', 'T_DeadLine', 'order_id');
        }])->whereHas('submission_info', function ($q) use ($tomorrowDate) {
            $q->whereDate('T_DeadLine', $tomorrowDate);
        })->whereHas('assign', function ($q) use ($User_ID) {
            $q->where('assign_id', $User_ID);
        })->get();
        $CordinatorTomorrowAll = $cordinatorTomorrowDeadLine->concat($cordinatorTomorrowFirstDraft)->concat($cordinatorTomorrowSecondDraft)->concat($cordinatorTomorrowThirdDraft)->toArray();
        $cordinatorPreviousDeadLine = OrderInfo::with(['submission_info' => function ($query) {
            $query->select('id', 'DeadLine', 'order_id');
        }])->whereHas('submission_info', function ($q) use ($todayDate) {
            $q->whereDate('DeadLine', '<', $todayDate);
        })->whereHas('assign', function ($q) use ($User_ID) {
            $q->where('assign_id', $User_ID);
        })->get();
        $cordinatorPreviousFirstDraft = OrderInfo::with(['submission_info' => function ($query) {
            $query->select('id', 'F_DeadLine', 'order_id');
        }])->whereHas('submission_info', function ($q) use ($todayDate) {
            $q->whereDate('F_DeadLine', '<', $todayDate);
        })->whereHas('assign', function ($q) use ($User_ID) {
            $q->where('assign_id', $User_ID);
        })->get();
        $cordinatorPreviousSecondDraft = OrderInfo::with(['submission_info' => function ($query) {
            $query->select('id', 'S_DeadLine', 'order_id');
        }])->whereHas('submission_info', function ($q) use ($todayDate) {
            $q->whereDate('S_DeadLine', '<', $todayDate);
        })->whereHas('assign', function ($q) use ($User_ID) {
            $q->where('assign_id', $User_ID);
        })->get();
        $cordinatorPreviousThirdDraft = OrderInfo::with(['submission_info' => function ($query) {
            $query->select('id', 'T_DeadLine', 'order_id');
        }])->whereHas('submission_info', function ($q) use ($todayDate) {
            $q->whereDate('T_DeadLine', '<', $todayDate);
        })->whereHas('assign', function ($q) use ($User_ID) {
            $q->where('assign_id', $User_ID);
        })->get();
        $CordinatorPreviousAll = $cordinatorPreviousDeadLine->concat($cordinatorPreviousFirstDraft)->concat($cordinatorPreviousSecondDraft)->concat($cordinatorPreviousThirdDraft)->toArray();
        $WriterTodayOrder = OrderInfo::with(['tasks' => function ($query) use ($User_ID, $todayDate) {
            $query->where('assign_id', $User_ID)->whereDate('DeadLine', $todayDate);
        }])->whereHas('tasks', function ($q) use ($User_ID, $todayDate) {
            $q->where('assign_id', $User_ID)->whereDate('DeadLine', $todayDate);
        })->get();
        $WriterTomorrowOrder = OrderInfo::with(['tasks' => function ($query) use ($User_ID, $tomorrowDate) {
            $query->where('assign_id', $User_ID)->whereDate('DeadLine', $tomorrowDate);
        }])->whereHas('tasks', function ($q) use ($User_ID, $tomorrowDate) {
            $q->where('assign_id', $User_ID)->whereDate('DeadLine', $tomorrowDate);
        })->get();
        $WriterPreviousOrder = OrderInfo::with(['tasks' => function ($query) use ($User_ID, $todayDate) {
            $query->where('assign_id', $User_ID)->whereDate('DeadLine', '<', $todayDate);
        }])->whereHas('tasks', function ($q) use ($User_ID, $todayDate) {
            $q->where('assign_id', $User_ID)->whereDate('DeadLine', '<', $todayDate);
        })->get();
        $ContentTodayDeadLine = OrderInfo::whereHas('assign', function ($query) use ($User_ID) {
            $query->where('assign_id', $User_ID);
        })->whereHas('submission_info', function ($query) use ($todayDate) {
            $query->where('DeadLine', $todayDate);
        })->with(['submission_info' => function ($query) {
            $query->select('id', 'DeadLine', 'order_id');
        }])->get();
        $ContentTodayFirstDeadLine = OrderInfo::whereHas('assign', function ($query) use ($User_ID) {
            $query->where('assign_id', $User_ID);
        })->whereHas('submission_info', function ($query) use ($todayDate) {
            $query->where('F_DeadLine', $todayDate);
        })->with(['submission_info' => function ($query) {
            $query->select('id', 'F_DeadLine', 'order_id');
        }])->get();
        $ContentTodaySecondDeadLine = OrderInfo::whereHas('assign', function ($query) use ($User_ID) {
            $query->where('assign_id', $User_ID);
        })->whereHas('submission_info', function ($query) use ($todayDate) {
            $query->where('S_DeadLine', $todayDate);
        })->with(['submission_info' => function ($query) {
            $query->select('id', 'S_DeadLine', 'order_id');
        }])->get();
        $ContentTodayThirdDeadLine = OrderInfo::whereHas('assign', function ($query) use ($User_ID) {
            $query->where('assign_id', $User_ID);
        })->whereHas('submission_info', function ($query) use ($todayDate) {
            $query->where('T_DeadLine', $todayDate);
        })->with(['submission_info' => function ($query) {
            $query->select('id', 'T_DeadLine', 'order_id');
        }])->get();
        $ContentAllToday = $ContentTodayDeadLine->concat($ContentTodayFirstDeadLine)->concat($ContentTodaySecondDeadLine)->concat($ContentTodayThirdDeadLine);
        $ContentTowmorrowDeadLine = OrderInfo::whereHas('assign', function ($query) use ($User_ID) {
            $query->where('assign_id', $User_ID);
        })->whereHas('submission_info', function ($query) use ($tomorrowDate) {
            $query->where('DeadLine', $tomorrowDate);
        })->with(['submission_info' => function ($query) {
            $query->select('id', 'DeadLine', 'order_id');
        }])->get();
        $ContentTowmorrowFirstDeadLine = OrderInfo::whereHas('assign', function ($query) use ($User_ID) {
            $query->where('assign_id', $User_ID);
        })->whereHas('submission_info', function ($query) use ($tomorrowDate) {
            $query->where('F_DeadLine', $tomorrowDate);
        })->with(['submission_info' => function ($query) {
            $query->select('id', 'F_DeadLine', 'order_id');
        }])->get();
        $ContentTowmorrowSecondDeadLine = OrderInfo::whereHas('assign', function ($query) use ($User_ID) {
            $query->where('assign_id', $User_ID);
        })->whereHas('submission_info', function ($query) use ($tomorrowDate) {
            $query->where('S_DeadLine', $tomorrowDate);
        })->with(['submission_info' => function ($query) {
            $query->select('id', 'S_DeadLine', 'order_id');
        }])->get();
        $ContentTowmorrowThirdDeadLine = OrderInfo::whereHas('assign', function ($query) use ($User_ID) {
            $query->where('assign_id', $User_ID);
        })->whereHas('submission_info', function ($query) use ($tomorrowDate) {
            $query->where('T_DeadLine', $tomorrowDate);
        })->with(['submission_info' => function ($query) {
            $query->select('id', 'T_DeadLine', 'order_id');
        }])->get();
        $ContentAllTowmorrow = $ContentTowmorrowDeadLine->concat($ContentTowmorrowFirstDeadLine)->concat($ContentTowmorrowSecondDeadLine)->concat($ContentTowmorrowThirdDeadLine);
        $ContentPreviousDeadLine = OrderInfo::whereHas('assign', function ($query) use ($User_ID) {
            $query->where('assign_id', $User_ID);
        })->whereHas('submission_info', function ($query) use ($todayDate) {
            $query->where('DeadLine', '<', $todayDate);
        })->with(['submission_info' => function ($query) {
            $query->select('id', 'DeadLine', 'order_id');
        }])->get();
        $ContentPreviousFirstDeadLine = OrderInfo::whereHas('assign', function ($query) use ($User_ID) {
            $query->where('assign_id', $User_ID);
        })->whereHas('submission_info', function ($query) use ($todayDate) {
            $query->where('F_DeadLine', '<', $todayDate);
        })->with(['submission_info' => function ($query) {
            $query->select('id', 'F_DeadLine', 'order_id');
        }])->get();
        $ContentPreviousSecondDeadLine = OrderInfo::whereHas('assign', function ($query) use ($User_ID) {
            $query->where('assign_id', $User_ID);
        })->whereHas('submission_info', function ($query) use ($todayDate) {
            $query->where('S_DeadLine', '<', $todayDate);
        })->with(['submission_info' => function ($query) {
            $query->select('id', 'S_DeadLine', 'order_id');
        }])->get();
        $ContentPreviousThirdDeadLine = OrderInfo::whereHas('assign', function ($query) use ($User_ID) {
            $query->where('assign_id', $User_ID);
        })->whereHas('submission_info', function ($query) use ($todayDate) {
            $query->where('T_DeadLine', '<', $todayDate);
        })->with(['submission_info' => function ($query) {
            $query->select('id', 'T_DeadLine', 'order_id');
        }])->get();
        $ContentAllPrevious = $ContentPreviousDeadLine->concat($ContentPreviousFirstDeadLine)->concat($ContentPreviousSecondDeadLine)->concat($ContentPreviousThirdDeadLine);
        $today = Carbon::now();
        $tomorrow = Carbon::tomorrow();
        $threeHoursLeft = Carbon::now()->subHours(3);
        $Role_ID = (int)$auth_user->Role_ID;
        $Previous_Order = $this->researchOrderService->getDeadLineOrders($today, $Role_ID, $User_ID, true);
        $Today_Order = $this->researchOrderService->getDeadLineOrders($today, $Role_ID, $User_ID, false);
        $Tomorrow_Order = $this->researchOrderService->getDeadLineOrders($today, $Role_ID, $User_ID, null);
        $Final_DeadLines = $this->researchOrderService->getThreeHoursDeadLineOrders($today, $threeHoursLeft, $Role_ID, $User_ID);
        $deadline_times = collect($Final_DeadLines)->map(function ($item) {
            $DeadLine = (!empty($item->submission_info->DeadLine)) ? strtotime($item->submission_info->DeadLine) : strtotime($item->DeadLine);
            $DeadLine_Time = (!empty($item->submission_info->DeadLine_Time)) ? strtotime($item->submission_info->DeadLine_Time) : strtotime($item->DeadLine_Time);
            $OrderID = (!empty($item->Order_ID)) ? $item->Order_ID : $item->order_info->Order_ID;
            $date = date('Y-m-d', $DeadLine) . ' ' . date('H:i:s', $DeadLine_Time);
            return [$OrderID, $date];
        });
        $statusCountsFlat = $this->orderService->getOrderCounts();
        $empCount = User::count();
        $lastAttendanceID = Attendance::whereDate('created_at', $today->format('Y-m-d'))->where('user_id', $auth_user->id)->first();
        $Leave_Quota = UserLeaveQuota::where('user_id', $auth_user->id)->first();
        $Get_Notice = NoticeBoard::latest('id')->get();
        return view('livewire.dashboard', compact('ContentAllPrevious', 'ContentAllTowmorrow', 'ContentAllToday', 'WriterPreviousOrder', 'WriterTomorrowOrder', 'WriterTodayOrder', 'CordinatorPreviousAll', 'CordinatorTomorrowAll', 'CordinatorTodayAll', 'OrdersToday', 'OrdersPast', 'OrdersTomorrow', 'Previous_Order', 'Today_Order', 'Tomorrow_Order', 'auth_user', 'Final_DeadLines', 'deadline_times', 'statusCountsFlat', 'auth_user', 'empCount', 'lastAttendanceID', 'Leave_Quota', 'Get_Notice'))->layout('layouts.authorized');
    }
}
