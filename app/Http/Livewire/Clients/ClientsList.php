<?php

namespace App\Http\Livewire\Clients;

use App\Models\BasicModels\OrderCountry;
use App\Models\ResearchOrders\OrderClientInfo;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Yajra\DataTables\DataTables;

class ClientsList extends Component
{
    public function render()
    {
        $Countries = OrderCountry::get();
        $clients = OrderClientInfo::latest('id')->get();
        return view('livewire.clients.clients-list', compact('Countries', 'clients'))->layout('layouts.authorized');
    }

    /**
     * @return JsonResponse
     * @throws Exception
     */
//    public function getAllClientsList(): JsonResponse
//    {
//        $clients = OrderClientInfo::latest('id')->get();
//
//        return DataTables::of($clients)
//            ->addColumn('actions', function ($client) {
//                // Generate the HTML for the action column
//                return '<div class="btn-list btn-sm">
//                        <div class="dropdown">
//                            <button class="btn btn-info dropdown-toggle px-5"
//                                    data-bs-toggle="dropdown">
//                                <i class="fe fe-activity me-2"></i>Actions
//                            </button>
//                            <div class="dropdown-menu">
//                                <a class="dropdown-item" href="' . route('Research.Create.Order', ['Client_ID' => $client->id]) . '">Place Research Order</a>
//                                <a class="dropdown-item" href="' . route('Content.Create.Order', ['Client_ID' => $client->id]) . '">Place Content Order</a>
//                                <a class="dropdown-item Client-Edit" data-bs-toggle="modal"
//                                                                   data-bs-target="#ClientEditModal" href="javascript:void(0);">
//                                Edit Client
//                                <input type="hidden" id="Client_ID" value="' . $client->Client_Name . '"/>
//                                </a>
//                            </div>
//                        </div>
//                    </div>';
//            })
//            ->addIndexColumn()
//            ->rawColumns(['actions']) // Specify the 'actions' column as raw HTML
//            ->make(true);
//    }

    public function updateClientInfo(Request $request): RedirectResponse
    {
        DB::beginTransaction();
        $OrderClientInfo = OrderClientInfo::where('id', $request->Client_Code)
            ->update([
            'Client_Name' => $request->Client_Name,
            'Client_Country' => $request->Client_Country,
            'Client_Email' => $request->Client_Email,
            'Client_Phone' => $request->Client_Phone,
        ]);
        if ($OrderClientInfo){
            DB::commit();
            return back()->with('Success!', "Client Information Updated!");
        }
        DB::rollBack();
        return back()->with('Error!', "Client Editing Failed!");
    }

}
