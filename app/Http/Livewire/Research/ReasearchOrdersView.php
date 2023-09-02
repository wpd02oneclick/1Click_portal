<?php

namespace App\Http\Livewire\Research;

use App\Events\NotificationCreated;
use App\Helpers\PortalHelpers;
use App\Models\Auth\User;
use App\Models\Draft\DraftSubmission;
use App\Models\ResearchOrders\OrderAssigningInfo;
use App\Models\ResearchOrders\OrderInfo;
use App\Models\ResearchOrders\OrderTaskSubmit;
use App\Models\ResearchOrders\ResearchDraftSubmission;
use App\Notifications\PortalNotifications;
use App\Services\OrdersService;
use App\Services\ResearchOrderService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Notification;
use Livewire\Component;

class ReasearchOrdersView extends Component
{
    protected ResearchOrderService $researchOrderService;
    protected OrdersService $ordersService;

    public function mount(ResearchOrderService $researchOrderService, OrdersService $ordersService): void
    {
        $this->researchOrderService = $researchOrderService;
        $this->ordersService = $ordersService;
    }

    public function render(Request $request)
    {
        $Order_ID = Crypt::decryptString($request->Order_ID);
        $auth_user = Auth::guard('Authorized')->user();   


        $currentDateTime = date('Y-m-d H:i:s');

        DB::table('notifications')
        ->where('notifiable_id', $auth_user->id)
        ->where('data->Order_ID', $Order_ID) // Use 'Order_ID' from your data field
        ->update(['read_at' => $currentDateTime]);


        $draft_submission = DraftSubmission::where('order_number', $Order_ID)->get();        
        $Research_Order = $this->researchOrderService->getOrderDetail($Order_ID, (int)$auth_user->Role_ID, (int)$auth_user->id);
        $Coordinators = $this->ordersService->getCoordinators();
        $Writers = $this->ordersService->getWriters((int)$auth_user->Role_ID, (int)$auth_user->id);

        return view('livewire.research.reasearch-orders-view', compact('Order_ID', 'Research_Order', 'Coordinators', 'Writers', 'auth_user' , 'draft_submission'))->layout('layouts.authorized');
    }

    public function assignOrder(Request $request): RedirectResponse
    {
        DB::beginTransaction();
        try {
            $Order = OrderInfo::where('Order_ID', $request->Order_ID)->firstOrFail();
            $users = User::findMany($request->Assign_ID);
            $userIds = [];

            foreach ($users as $user) {
                if (!$Order->assign->contains('id', $user->id)) {
                    OrderAssigningInfo::create([
                        'order_id' => $Order->id,
                        'assign_id' => $user->id,
                    ]);
                }
            }

            foreach ($users as $user) {
                $userIds[] = (int)$user->id;
            }

            $authUser = Auth::guard('Authorized')->user();
            $usersToNotify = array_merge([(int)$authUser->id], $userIds);

            $message = $request->Order_ID . ' has been Assigned Successfully!.';
            PortalHelpers::sendNotification(null, $request->Order_ID, $message, $authUser->designation->Designation_Name, (array)$usersToNotify, [1, 4]);

            DB::commit();
            return back()->with('Success!', "Order Assigned Successfully!");
        } catch (ModelNotFoundException|\Exception $e) {
            DB::rollBack();
            return redirect()->route('Error.Response', ['Message' => $e->getMessage()]);
        }
    }

    public function ViewAssignTask(Request $request)
    {
        $Task_Details = OrderTaskSubmit::where('task_id', $request->task_id)->get();

        $html = '';
        foreach ($Task_Details as $index => $task) {
            $html .= '<tr>';
            $html .= '<td>' . ($index + 1) . '</td>';
            $html .= '<td>' . $task->File_Name . '</td>';
            $html .= '<td><a href="' . asset($task->task_file_path) . '" class="action-btns1" download><i class="feather feather-download text-success"></i></a></td>';
            $html .= '</tr>';
        }

        return $html;
    }


}
