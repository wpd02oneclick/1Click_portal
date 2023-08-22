<?php

namespace App\Http\Livewire\Research;

use App\Events\NotificationCreated;
use App\Helpers\PortalHelpers;
use App\Models\Auth\User;
use App\Models\ResearchOrders\OrderAssigningInfo;
use App\Models\ResearchOrders\OrderInfo;
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

        $Research_Order = $this->researchOrderService->getOrderDetail($Order_ID, (int)$auth_user->Role_ID, (int)$auth_user->id);
        $Coordinators = $this->ordersService->getCoordinators();
        $Writers = $this->ordersService->getWriters((int)$auth_user->Role_ID, (int)$auth_user->id);

        return view('livewire.research.reasearch-orders-view', compact('Order_ID', 'Research_Order', 'Coordinators', 'Writers', 'auth_user'))->layout('layouts.authorized');
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


}
