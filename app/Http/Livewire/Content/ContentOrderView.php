<?php

namespace App\Http\Livewire\Content;

use App\Models\Auth\User;
use App\Services\ContentOrderService;
use App\Services\OrdersService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Livewire\Component;

class ContentOrderView extends Component
{
    protected ContentOrderService $contentOrderService;
    protected OrdersService $ordersService;

    public function mount(ContentOrderService $contentOrderService, OrdersService $ordersService): void
    {
        $this->contentOrderService = $contentOrderService;
        $this->ordersService = $ordersService;
    }

    public function render(Request $request)
    {
        $Order_ID = Crypt::decryptString($request->Order_ID);
        $auth_user = Auth::guard('Authorized')->user();

        $Content_Order = $this->contentOrderService->getOrderDetail($Order_ID);
        $Content_Writer_List = $this->contentOrderService->getContentWriters();
        $Writers = $this->ordersService->getWriters((int)$auth_user->Role_ID, (int)$auth_user->id);

        return view('livewire.content.content-order-view', compact('Order_ID', 'Content_Order', 'Content_Writer_List', 'Writers', 'auth_user'))->layout('layouts.authorized');
    }
}
