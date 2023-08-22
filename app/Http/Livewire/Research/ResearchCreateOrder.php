<?php

namespace App\Http\Livewire\Research;

use App\Models\BasicModels\OrderCountry;
use App\Models\BasicModels\OrderCurrencies;
use App\Services\OrdersService;
use App\Services\Pre_Villages;
use App\Services\ResearchOrderService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Livewire\Component;

class ResearchCreateOrder extends Component
{
    protected ResearchOrderService $researchOrderService;
    protected Pre_Villages $pre_Villages;
    protected OrdersService $orderService;

    public function mount(Pre_Villages $pre_Villages, ResearchOrderService $researchOrderService, OrdersService $orderService): void
    {
        $this->pre_Villages = $pre_Villages;
        $this->researchOrderService = $researchOrderService;
        $this->orderService = $orderService;
    }

    public function render(Request $request)
    {
        $Order_Services = $this->pre_Villages->getOrderServices();
        $Order_Websites = $this->pre_Villages->getOrderWebsites();
        $Currencies = OrderCurrencies::get();
        $Countries = OrderCountry::get();
        $L_OID = $this->orderService->getNewOrderID();

        $Client_Info = $this->orderService->getClientInfoFromRoute($request->Client_ID);

        return view('livewire.research.research-create-order', compact('Order_Services', 'Order_Websites', 'L_OID', 'Currencies', 'Countries', 'Client_Info'))->layout('layouts.authorized');
    }

    public function createResearchOrder(Request $request, OrdersService $orderService): RedirectResponse
    {
        return $orderService->createNewOrder($request);
    }
}
