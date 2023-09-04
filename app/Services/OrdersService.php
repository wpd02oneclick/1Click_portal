<?php

namespace App\Services;

use App\Helpers\PortalHelpers;
use App\Models\Auth\User;
use App\Models\BasicModels\WriterSkills;
use App\Models\ContentOrders\ContentBasicInfo;
use App\Models\Draft\DraftAttachment;
use App\Models\Draft\DraftSubmission;
use App\Models\Performance\UserWordsStats;
use App\Models\ResearchOrders\ClientOrdersList;
use App\Models\ResearchOrders\FinalOrderSubmission;
use App\Models\ResearchOrders\OrderAssigningInfo;
use App\Models\ResearchOrders\OrderAttachment;
use App\Models\ResearchOrders\OrderBasicInfo;
use App\Models\ResearchOrders\OrderClientInfo;
use App\Models\ResearchOrders\OrderDescriptionInfo;
use App\Models\ResearchOrders\OrderInfo;
use App\Models\ResearchOrders\OrderPaymentInfo;
use App\Models\ResearchOrders\OrderReferenceInfo;
use App\Models\ResearchOrders\OrderRevision;
use App\Models\ResearchOrders\OrderRevisionAttachments;
use App\Models\ResearchOrders\OrderSubmissionInfo;
use App\Models\ResearchOrders\OrderTask;
use App\Models\ResearchOrders\OrderTaskSubmit;
use App\Models\ResearchOrders\ResearchDraftAttachment;
use App\Models\ResearchOrders\ResearchDraftSubmission;
use App\Models\ResearchOrders\ResearchOrderSubmissionDeadline;
use App\Models\ResearchOrders\RevisionAttachments;
use App\Models\ResearchOrders\SubmitRevisionAttachment;
use App\Models\ResearchOrders\TaskCancelWords;
use App\Models\ResearchOrders\TaskRevision;
use Carbon\Carbon;
use Exception;
use http\Exception\RuntimeException;
use Illuminate\Database\QueryException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class OrdersService
{
    // ======================= Start Order Creation (Research & Content) ==========================
    public function createNewOrder(Request $request): RedirectResponse
    {
        try {
            $auth_user = Auth::guard('Authorized')->user();
            $flag = false;
            DB::beginTransaction();
            $F_DeadLine = (!empty($request->F_DeadLine)) ? date('Y-m-d', strtotime($request->F_DeadLine)) : null;
            $S_DeadLine = (!empty($request->S_DeadLine)) ? date('Y-m-d', strtotime($request->S_DeadLine)) : null;
            $T_DeadLine = (!empty($request->T_DeadLine)) ? date('Y-m-d', strtotime($request->T_DeadLine)) : null;

            $check_client = $this->getClientInfoFromRoute($request->Client_Code);
            $L_OID = $this->getNewOrderID();

            if (empty($check_client)) {
                $L_CID = $this->getNewClientID();

                $OrderClientInfo = OrderClientInfo::create([
                    'Client_Code' => $L_CID,
                    'Client_Name' => $request->Client_Name,
                    'Client_Country' => $request->Client_Country,
                    'Client_Email' => $request->Client_Email,
                    'Client_Phone' => $request->Client_Phone,
                    'user_id' => $auth_user->id
                ]);
                $client_id = (int)$OrderClientInfo->id;
                if ($OrderClientInfo) {
                    return $this->CreateOrder($request, $auth_user->id, $client_id, $F_DeadLine, $S_DeadLine, $T_DeadLine, $flag, $auth_user->designation->Designation_Name, $L_OID);
                }
                DB::rollBack();
                return back()->with('Error!', "Order Creation Failed!");
            }

            $client_id = (int)$check_client->id;
            return $this->CreateOrder($request, $auth_user->id, $client_id, $F_DeadLine, $S_DeadLine, $T_DeadLine, $flag, $auth_user->designation->Designation_Name, $L_OID);
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->route('Error.Response', ['Message' => $e->getMessage()]);
        }
    }

    public function CreateOrder(Request $request, $user_id, $client_id, ?string $F_DeadLine, ?string $S_DeadLine, ?string $T_DeadLine, bool $flag, string $Role_Name, string $L_OID): RedirectResponse
    {

        try {
            DB::beginTransaction();

            $assign_id = ((int)$request->Order_Type === 1) ? null : $this->getAssignID($request->Order_Services);
            $Order_Info = $this->orderInfo($request, $user_id, $client_id, $L_OID);
            $order_id = $Order_Info->id;
            if ($Order_Info) {
                $OrderClientInfo = ClientOrdersList::create([
                    'order_id' => $order_id,
                    'client_id' => $client_id
                ]);
                $OrderAssigningInfo = OrderAssigningInfo::create([
                    'order_id' => $order_id,
                    'assign_id' => $assign_id,
                ]);
                $BasicInfo = $this->orderBasicInfo($request, $user_id, $client_id, $order_id);
                if ($BasicInfo && $OrderClientInfo && $OrderAssigningInfo) {
                    $OrderSubmissionInfo = OrderSubmissionInfo::create([
                        'DeadLine' => date('Y-m-d', strtotime($request->DeadLine)),
                        'DeadLine_Time' => $request->input('DeadLine_Time'),
                        'F_DeadLine' => $request->input('F_DeadLine'),
                        'S_DeadLine' => $request->input('S_DeadLine'),
                        'T_DeadLine' => $request->input('T_DeadLine'),
                        'order_id' => $order_id,
                        'user_id' => $user_id,
                        'client_id' => $client_id
                    ]);

                    if ($OrderSubmissionInfo) {
                        $OrderReferenceInfo = OrderReferenceInfo::create([
                            'Reference_Code' => $request->Reference_Code,
                            'order_id' => $order_id,
                            'user_id' => $user_id,
                            'client_id' => $client_id
                        ]);
                        if ($OrderReferenceInfo) {
                            $OrderDescriptionInfo = OrderDescriptionInfo::create([
                                'Description' => $request->input('Order_Description'),
                                'order_id' => $order_id,
                                'user_id' => $user_id,
                                'client_id' => $client_id
                            ]);
                            if ($OrderDescriptionInfo) {
                                $OrderPaymentInfo = OrderPaymentInfo::create([
                                    'Order_Price' => $request->Order_Price,
                                    'Order_Currency' => $request->Order_Currency,
                                    'Payment_Status' => $request->Payment_Status,
                                    'Rec_Amount' => $request->Rec_Amount,
                                    'Due_Amount' => $request->Due_Amount,
                                    'Partial_Info' => $request->Partial_Info,
                                    'order_id' => $order_id,
                                    'user_id' => $user_id,
                                    'client_id' => $client_id
                                ]);
                                if ($OrderPaymentInfo) {
                                    if (!empty($request->file('files'))) {
                                        foreach ($request->file('files') as $key => $ImageFile) {
                                            $imageGalleryName = $ImageFile->getClientOriginalName();
                                            $ImageFile->move(public_path('Uploads/Attachments/' . $Order_Info->Order_ID . '/'), $imageGalleryName);
                                            $FileName = 'Uploads/Attachments/' . $Order_Info->Order_ID . '/' . $imageGalleryName;
                                            OrderAttachment::create([
                                                'File_Name' => $imageGalleryName,
                                                'order_attachment_path' => $FileName,
                                                'order_id' => $order_id,
                                                'user_id' => $user_id,
                                                'client_id' => $client_id
                                            ]);
                                            $flag = true;
                                        }
                                        if ($flag) {
                                            DB::commit();
                                            return $this->SendNotifications($Order_Info, $request->Order_Type);
                                        }
                                        DB::rollBack();
                                        return back()->with('Error!', "Attachments Error!");
                                    }
                                    DB::commit();
                                    return $this->SendNotifications($Order_Info, $request->Order_Type);
                                }
                                DB::rollBack();
                                return back()->with('Error!', "Payment Error!");
                            }
                            DB::rollBack();
                            return back()->with('Error!', "Description Error!");
                        }
                        DB::rollBack();
                        return back()->with('Error!', "Reference Error!");
                    }
                    DB::rollBack();
                    return back()->with('Error!', "Date Submission Error!");
                }
                DB::rollBack();
                return back()->with('Error!', "Order Info Error!");
            }
            DB::rollBack();
            return back()->with('Error!', "Client Info Error!");
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->route('Error.Response', ['Message' => $e->getMessage()]);
        }
    }

    private function orderBasicInfo(Request $request, $user_id, $client_id, $order_id)
    {
        if ((int)$request->Order_Type === 1) {
            return OrderBasicInfo::create([
                'Order_Services' => $request->Order_Services,
                'Education_Level' => $request->Education_Level,
                'Pages_Count' => PortalHelpers::getPageCount($request->Word_Count, $request->Spacing),
                'Word_Count' => $request->Word_Count,
                'Spacing' => $request->Spacing,
                'Citation_Style' => $request->Citation_Style,
                'Sources' => $request->Sources,
                'Order_Website' => $request->Order_Website,
                'Order_Status' => $request->Order_Status,
                'order_id' => $order_id,
                'user_id' => $user_id,
                'client_id' => $client_id
            ]);
        }

        return ContentBasicInfo::create([
            'Content_Title' => $request->Content_Title,
            'Industry_Name' => $request->Industry_Name,
            'Writing_Style' => $request->Writing_Style,
            'Preferred_Voice' => $request->Preferred_Voice,
            'Target_Audience' => $request->Target_Audience,
            'Target_Gender' => $request->Target_Gender,
            'Free_Image' => $request->Free_Image,
            'Generic_Type' => $request->Generic_Type,
            'Keywords' => $request->Keywords,
            'Meta_Description' => $request->Meta_Description,
            'Reference_Link' => $request->Reference_Link,
            'Order_Services' => $request->Order_Services,
            'Word_Count' => $request->Word_Count,
            'Order_Website' => $request->Order_Website,
            'Order_Status' => $request->Order_Status,
            'order_id' => $order_id,
            'user_id' => $user_id,
            'client_id' => $client_id
        ]);
    }

    private function orderInfo(Request $request, $user_id, $client_id, $L_OID)
    {
        if ((int)$request->Order_Type === 1) {
            return OrderInfo::create([
                'Order_ID' => $L_OID,
                'Order_Type' => $request->Order_Type,
                'user_id' => $user_id,
                'client_id' => $client_id,
                'assign_id' => null, // Set assign_id to null for Order_Type 1
            ]);
        }

        return OrderInfo::create([
            'Order_ID' => $L_OID,
            'Order_Type' => $request->Order_Type,
            'assign_id' => null,
            'user_id' => $user_id,
            'client_id' => $client_id,
        ]);
    }

    private function getAssignID($Order_Service): int|null
    {
        $skill = WriterSkills::where('Skill_Name', $Order_Service)->firstOrFail();
        $users_ids = User::where('Skill_ID', $skill->id)->pluck('id');

        if (isset($users_ids)) {
            $assign_ids = OrderInfo::whereRelation('content_info', static function ($q) use ($Order_Service) {
                $q->where('Order_Status', '<>', 2)->where('Order_Services', $Order_Service);
            })->where('Order_Type', 2)->pluck('assign_id');

            $users_ids_not_assigned = $users_ids->diff($assign_ids);

            if ($users_ids_not_assigned->count() === 0) {
                $assign_ids_with_min_order_count = OrderInfo::whereIn('assign_id', $assign_ids)
                    ->select('assign_id', DB::raw('COUNT(*) as order_count'), DB::raw('SUM(content_basic_infos.Word_Count) as word_sum'))
                    ->join('content_basic_infos', 'order_infos.id', '=', 'content_basic_infos.order_id')
                    ->whereMonth('content_basic_infos.created_at', now()->month)
                    ->groupBy('assign_id')
                    ->orderBy('word_sum')
                    ->orderBy('order_count')
                    ->pluck('assign_id')
                    ->take(1);

                if ($assign_ids_with_min_order_count->count() > 0) {
                    return $assign_ids_with_min_order_count->first();
                }
                return null;
            }
            return $users_ids_not_assigned->random();
        }
        return null;
    }

    private function SendNotifications($Order_Info, $Order_Type): RedirectResponse
    {
        try {
            $authUser = Auth::guard('Authorized')->user();
            $message = $Order_Info->Order_ID . ' Order has been Created!';
            PortalHelpers::sendNotification(null, $Order_Info->Order_ID, $message, $authUser->designation->Designation_Name, [$authUser->id], [1, 4, 9, 10, 11]); // Adding Role ID 9 because notifications are not showing for Role 9.
            DB::commit();
            if ((int)$Order_Type === 1) {
                return redirect()->route('Research.Orders')->with('Success!', "Order Created Successfully!");
            }
            return redirect()->route('Content.Orders')->with('Success!', "Order Created Successfully!");
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->route('Error.Response', ['Message' => $e->getMessage()]);
        }
    }

    //    public function getNewOrderID(): string
    //    {
    //        $lastOrder = OrderInfo::withTrashed()->orderBy('id', 'DESC')->first();
    //        $curDate = Carbon::now()->isoFormat('MM-YYYY');
    //
    //        $lastOrderId = $lastOrder->id ?? 0;
    //        return 'OC-' . $curDate . '-' . str_pad($lastOrderId + 1, 3, '0', STR_PAD_LEFT);
    //    }

    public function getNewOrderID(): string
    {
        $month = Carbon::now()->month;

        $lastOrder = OrderInfo::withTrashed()->latest('id')->whereMonth('created_at', $month)->count();
        $curDate = Carbon::now()->isoFormat('MM-YYYY');

        $lastOrderId = $lastOrder ?? 0;
        return 'OC-' . $curDate . '-' . str_pad($lastOrderId + 1, 3, '0', STR_PAD_LEFT);
    }

    public function getNewClientID(): string
    {
        $lastClient = OrderClientInfo::orderBy('id', 'DESC')->first();
        if ($lastClient === null) {
            return 'Client' . '-1';
        }

        return 'Client' . '-' . ($lastClient->id + 1);
    }

    public function getClientInfoFromRoute($Client_ID)
    {
        return (!empty($Client_ID) ? OrderClientInfo::where('id', $Client_ID)->firstOrFail() : null);
    }
    // ======================= End Order Creation (Research & Content) ==========================

    // ======================= Start Order Update (Research & Content) ==========================
    public function updateOrder(Request $request): RedirectResponse
    {
        try {
            $user_id = Auth::guard('Authorized')->user()->id;
            $flag = false;
            DB::beginTransaction();

            $F_DeadLine = (!empty($request->F_DeadLine)) ? date('Y-m-d', strtotime($request->F_DeadLine)) : null;
            $S_DeadLine = (!empty($request->S_DeadLine)) ? date('Y-m-d', strtotime($request->S_DeadLine)) : null;
            $T_DeadLine = (!empty($request->T_DeadLine)) ? date('Y-m-d', strtotime($request->T_DeadLine)) : null;
            $Order_ID = $request->order_id;

            $Order_Info = OrderInfo::where('id', $Order_ID)->firstOrFail();

            $OrderBasicInfo = ((int)$request->Order_Type === 1) ?
                OrderBasicInfo::where('order_id', $Order_ID)
                ->update([
                    'Order_Services' => $request->Order_Services,
                    'Education_Level' => $request->Education_Level,
                    'Pages_Count' => PortalHelpers::getPageCount($request->Word_Count, $request->Spacing),
                    'Word_Count' => $request->Word_Count,
                    'Spacing' => $request->Spacing,
                    'Citation_Style' => $request->Citation_Style,
                    'Sources' => $request->Sources,
                    'Order_Website' => $request->Order_Website,
                    'Order_Status' => $request->Order_Status,
                    'user_id' => $user_id
                ])
                :
                ContentBasicInfo::where('order_id', $Order_ID)
                ->update([
                    'Content_Title' => $request->Content_Title,
                    'Industry_Name' => $request->Industry_Name,
                    'Writing_Style' => $request->Writing_Style,
                    'Preferred_Voice' => $request->Preferred_Voice,
                    'Target_Audience' => $request->Target_Audience,
                    'Target_Gender' => $request->Target_Gender,
                    'Free_Image' => $request->Free_Image,
                    'Generic_Type' => $request->Generic_Type,
                    'Keywords' => $request->Keywords,
                    'Meta_Description' => $request->Meta_Description,
                    'Reference_Link' => $request->Reference_Link,
                    'Order_Services' => $request->Order_Services,
                    'Word_Count' => $request->Word_Count,
                    'Order_Website' => $request->Order_Website,
                    'Order_Status' => $request->Order_Status,
                    'user_id' => $user_id
                ]);

            if (!$OrderBasicInfo) {
                throw new RuntimeException("Order Info Error!");
            }

            $OrderSubmissionInfo = OrderSubmissionInfo::where('order_id', $Order_ID)
                ->update([
                    'DeadLine' => date('Y-m-d', strtotime($request->DeadLine)),
                    'DeadLine_Time' => $request->input('DeadLine_Time'),
                    'F_DeadLine' => $F_DeadLine,
                    'S_DeadLine' => $S_DeadLine,
                    'T_DeadLine' => $T_DeadLine,
                    'user_id' => $user_id
                ]);

            if (!$OrderSubmissionInfo) {
                throw new RuntimeException("Date Submission Error!");
            }

            $OrderReferenceInfo = OrderReferenceInfo::where('order_id', $Order_ID)
                ->update([
                    'Reference_Code' => $request->Reference_Code,
                    'user_id' => $user_id
                ]);

            if (!$OrderReferenceInfo) {
                throw new RuntimeException("Reference Error!");
            }

            $OrderDescriptionInfo = OrderDescriptionInfo::where('order_id', $Order_ID)
                ->update([
                    'Description' => $request->input('Order_Description'),
                    'user_id' => $user_id
                ]);

            if (!$OrderDescriptionInfo) {
                throw new RuntimeException("Description Error!");
            }

            $OrderPaymentInfo = OrderPaymentInfo::where('order_id', $Order_ID)
                ->update([
                    'Order_Price' => $request->Order_Price,
                    'Order_Currency' => $request->Order_Currency,
                    'Payment_Status' => $request->Payment_Status,
                    'Rec_Amount' => $request->Rec_Amount,
                    'Due_Amount' => $request->Due_Amount,
                    'Partial_Info' => $request->Partial_Info,
                    'user_id' => $user_id
                ]);

            if (!$OrderPaymentInfo) {
                throw new RuntimeException("Payment Error!");
            }

            if (!empty($request->file('files'))) {
                foreach ($request->file('files') as $key => $ImageFile) {
                    $imageGalleryName = $ImageFile->getClientOriginalName();
                    $ImageFile->move(public_path('Uploads/Attachments/' . $request->Order_ID . '/'), $imageGalleryName);
                    $FileName = 'Uploads/Attachments/' . $request->Order_ID . '/' . $imageGalleryName;
                    OrderAttachment::create([
                        'File_Name' => $imageGalleryName,
                        'order_attachment_path' => $FileName,
                        'order_id' => $Order_ID,
                        'user_id' => $user_id,
                        'client_id' => $Order_Info->client_id
                    ]);
                    $flag = true;
                }

                if (!$flag) {
                    throw new RuntimeException("Attachments Error!");
                }
            }

            DB::commit();
            if ((int)$request->Order_Type === 1) {
                return redirect()->route('Order.Details', ['Order_ID' => $Order_Info->Order_ID])->with('Success!', "Order Updated Successfully!");
            }
            return redirect()->route('Content.Order.Details', ['Order_ID' => $Order_Info->Order_ID])->with('Success!', "Order Updated Successfully!");
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->route('Error.Response', ['Message' => $e->getMessage()]);
        }
    }
    // ======================= End Order Update (Research & Content) ============================

    // ======================= Start Order Actions (Research & Content) ==========================
    public function getOrderCounts(): array
    {
        $basicInfoCounts = OrderBasicInfo::select('Order_Status', DB::raw('COUNT(*) as count'))
            ->groupBy('Order_Status')
            ->get();

        $contentInfoCounts = ContentBasicInfo::select('Order_Status', DB::raw('COUNT(*) as count'))
            ->groupBy('Order_Status')
            ->get();

        $statusCounts = collect();

        foreach ($basicInfoCounts as $status) {
            $statusCounts->put($status->Order_Status . '_Count', $status->count + ($statusCounts->get($status->Order_Status . '_Count', 0)));
        }

        foreach ($contentInfoCounts as $status) {
            $statusCounts->put($status->Order_Status . '_Count', $status->count + ($statusCounts->get($status->Order_Status . '_Count', 0)));
        }
        $statusCounts->toArray();
        return [
            'Working_Count' => $statusCounts['Working_Count'] ?? 0,
            'Canceled_Count' => $statusCounts['Canceled_Count'] ?? 0,
            'Completed_Count' => $statusCounts['Completed_Count'] ?? 0,
            'Revision_Count' => $statusCounts['Revision_Count'] ?? 0,
        ];
    }

    public function getCoordinators()
    {
        return User::where('Role_ID', 5)
            ->with([
                'basic_info' => function ($q) {
                    $q->select('id', 'F_Name', 'L_Name', 'user_id');
                }
            ])->get();
    }

    public function getWriters($Role_ID, $User_ID)
    {
        if ((int)$Role_ID === 5) {
            return User::whereIn('Role_ID', [6, 7])
                ->with([
                    'basic_info' => function ($q) {
                        $q->select('id', 'F_Name', 'L_Name', 'user_id');
                    }
                ])
                ->whereHas('basic_info', function ($q) use ($User_ID) {
                    $q->where('CID', $User_ID);
                })
                ->get();
        }
        return User::whereIn('Role_ID', [6, 7])
            ->with([
                'basic_info' => function ($q) {
                    $q->select('id', 'F_Name', 'L_Name', 'user_id');
                }
            ])
            ->get();
    }

    // Detail Order View Functionalities
    public function newOrderTask(Request $request): RedirectResponse
    {
        try {
            DB::beginTransaction();

            if (isset($request->C_Assign_ID)) {
                $Order = OrderInfo::where('Order_ID', $request->Order_ID)->firstOrFail();
                $users = User::findMany($request->C_Assign_ID);

                foreach ($users as $user) {
                    if (!$Order->assign->contains('id', $user->id)) {
                        $Assign_Order = OrderAssigningInfo::create([
                            'order_id' => $Order->id,
                            'assign_id' => $user->id,
                        ]);
                        if (!$Assign_Order) {
                            throw new \RuntimeException("Order Assignment Failed.");
                        }
                    }
                }
            }

            $Order_Task = OrderTask::create([
                'Assign_Words' => (float)$request->Order_Words,
                'Total_Words' => (float)$request->total_words,
                'Due_Words' => (float)$request->Due_Words,
                'DeadLine' => date('Y-m-d', strtotime($request->DeadLine)),
                'DeadLine_Time' => $request->DeadLine_Time,
                'Task_Status' => 0,
                'order_id' => $request->order_id,
                'assign_id' => $request->W_Assign_ID,
                'assign_by' => $request->assign_by,
            ]);

            if (!$Order_Task) {
                throw new \RuntimeException("Task Creation Failed.");
            }

            $authUser = Auth::guard('Authorized')->user();
            $notify_id = [
                ($authUser->Role_ID === 4 && $authUser->id === $request->assign_by) ? $request->assign_by : $authUser->id
            ];

            PortalHelpers::sendNotification(
                null,
                $request->Order_ID,
                'Task has been Created' . (isset($request->C_Assign_ID) ? ' & Assigned' : '') . '!',
                $authUser->designation->Designation_Name,
                [$request->W_Assign_ID, ...$notify_id],
                [1, 4, 5]
            );

            DB::commit();
            return back()->with('Success!', isset($request->C_Assign_ID) ? 'Order & Task Assigned Successfully!' : 'Task Assigned Successfully!');
        } catch (QueryException $e) {
            DB::rollback();
            return back()->with('Error!', 'Database Error: ' . $e->getMessage());
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->route('Error.Response', ['Message' => $e->getMessage()]);
        }
    }

    public function EditOrderTask(Request $request): RedirectResponse
    {
        DB::beginTransaction();
        $Order_Task = OrderTask::where('id', $request->task_id)
            ->update([
                'Assign_Words' => (float)$request->Order_Words,
                'Total_Words' => (float)$request->total_words,
                'Due_Words' => (float)$request->Due_Words,
                'DeadLine' => date('Y-m-d', strtotime($request->DeadLine)),
                'DeadLine_Time' => $request->DeadLine_Time,
                'order_id' => $request->order_id,
                'assign_id' => $request->W_Assign_ID,
                'assign_by' => $request->assign_by,
            ]);
        if ($Order_Task) {
            DB::commit();
            return back()->with('Success!', "Task Updated Successfully!");
        }
        DB::rollBack();
        return back()->with('Error!!', "Task Failed To Update!");
    }

    public function DeleteOrderTask(Request $request): RedirectResponse
    {
        DB::beginTransaction();
        $Task_ID = Crypt::decryptString($request->Task_ID);
        $Order_Task = OrderTask::where('id', $Task_ID)->delete();
        if ($Order_Task) {
            DB::commit();
            return back()->with('Success!', "Task Deleted Successfully!");
        }
        DB::rollBack();
        return back()->with('Error!!', "Task Failed To Delete!");
    }

    public function submitUserTask(Request $request): RedirectResponse
    {
        try {
            $flag = false;
            $order_id = $request->order_id;
            $authUser = Auth::guard('Authorized')->user();
            DB::beginTransaction();
            $Order_Task = OrderTask::where('id', $request->task_id)
                ->update([
                    'Task_Status' => 2
                ]);

            if ($Order_Task) {
                if ((int)$authUser->Role_ID === 7) {
                    foreach ($request->file('files') as $key => $file) {
                        $fileName = $file->getClientOriginalName();
                        $filePath = 'Uploads/Final-Attachments/' . $request->Order_ID . '/' . $fileName;

                        $file->move(public_path('Uploads/Final-Attachments/' . $request->Order_ID), $fileName);

                        FinalOrderSubmission::create([
                            'File_Name' => $fileName,
                            'final_submission_path' => $filePath,
                            'order_id' => $request->order_id,
                            'user_id' => $request->submit_by,
                        ]);
                        $flag = true;
                    }
                }
                if ((int)$authUser->Role_ID !== 7) {
                    foreach ($request->file('files') as $key => $ImageFile) {
                        $imageGalleryName = $ImageFile->getClientOriginalName();
                        $ImageFile->move(public_path('Uploads/Task-Attachments/' . $order_id . '/'), $imageGalleryName);
                        $FileName = 'Uploads/Task-Attachments/' . $order_id . '/' . $imageGalleryName;
                        OrderTaskSubmit::create([
                            'File_Name' => $imageGalleryName,
                            'task_file_path' => $FileName,
                            'task_id' => $request->task_id,
                            'submit_by' => $request->submit_by,
                        ]);
                        $flag = true;
                    }
                }
                if ($flag) {
                    $Word_Count = OrderTask::where('id', $request->task_id)->first();
                    $this->handleUserStatistics($request, 1);
                    $message = $request->Order_ID . 'Task has been Submitted!';
                    PortalHelpers::sendNotification(null, $request->Order_ID, $message, $authUser->designation->Designation_Name, [$authUser->id, $Word_Count->assign_by, $authUser->CID], [1, 4, 5]);
                    DB::commit();
                    return back()->with('Success!', "Task Submitted Successfully!");
                }
                DB::rollBack();
                return back()->with('Error!', "Task Submission Failed!");
            }
            DB::rollBack();
            return back()->with('Error!', "Task Updated Failed!");
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->route('Error.Response', ['Message' => $e->getMessage()]);
        }
    }

    public function TaskRevision(Request $request): RedirectResponse
    {
        try {
            DB::beginTransaction();
            $flag = false;
            $Order_Task = OrderTask::where('id', $request->task_id)
                ->update([
                    'Task_Status' => 3,
                    'DeadLine' => date('Y-m-d', strtotime($request->DeadLine)),
                    'DeadLine_Time' => $request->DeadLine_Time,
                ]);
            if ($Order_Task) {
                $TaskRevision = TaskRevision::create([
                    'Task_Revision' => $request->Task_Revision,
                    'task_id' => $request->task_id,
                    'revised_by' => $request->revised_by,
                ]);
                $this->handleUserStatistics($request, 1);
                if ($TaskRevision) {
                    if (!empty($request->file('files'))) {
                        foreach ($request->file('files') as $key => $ImageFile) {
                            $imageGalleryName = $ImageFile->getClientOriginalName();
                            $ImageFile->move(public_path('Uploads/Revision-Attachments/' . $request->task_id . '/'), $imageGalleryName);
                            $FileName = 'Uploads/Revision-Attachments/' . $request->task_id . '/' . $imageGalleryName;
                            RevisionAttachments::create([
                                'File_Name' => $imageGalleryName,
                                'file_path' => $FileName,
                                'revision_id' => $TaskRevision->id,
                            ]);
                            $flag = true;
                        }
                        if ($flag) {
                            return $this->RevisionNotification($request);
                        }
                        DB::rollBack();
                        return back()->with('Error!', "Revision Attachments Failed!");
                    }
                    return $this->RevisionNotification($request);
                }
                DB::rollBack();
                return back()->with('Error!', "Revision Failed!");
            }
            DB::rollBack();
            return back()->with('Error!', "Task Updated Failed!");
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->route('Error.Response', ['Message' => $e->getMessage()]);
        }
    }

    public function ChangeResearchWriter(Request $request): RedirectResponse
    {
        try {
            DB::beginTransaction();
            $Order_Task = OrderTask::where('id', $request->task_id)
                ->update([
                    'assign_id' => $request->W_Assign_ID,
                    'assign_by' => $request->assign_by,
                ]);
            if ($Order_Task) {
                $authUser = Auth::guard('Authorized')->user();
                $message = $request->Order_ID . 'Writer has been Changed!';
                PortalHelpers::sendNotification(null, $request->Order_ID, $message, $authUser->designation->Designation_Name, [$request->W_Assign_ID, $authUser->id, $request->assign_by], [1, 4, 5]);
                DB::commit();
                return back()->with('Success!', "Writer Change Successfully!");
            }
            DB::rollBack();
            return back()->with('Error!', "Writer Assigning Failed!");
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->route('Error.Response', ['Message' => $e->getMessage()]);
        }
    }

    public function ChangeContentWriter(Request $request): RedirectResponse
    {
        try {
            DB::beginTransaction();
            $Order = OrderAssigningInfo::where('order_id', $request->order_id)
                ->where('assign_id', $request->user_id)
                ->update([
                    'assign_id' => $request->W_Assign_ID,
                ]);
            if ($Order) {
                $authUser = Auth::guard('Authorized')->user();
                $message = $request->Order_ID . ' Content Writer has been Changed!';
                PortalHelpers::sendNotification(null, $request->Order_ID, $message, $authUser->designation->Designation_Name, [$request->W_Assign_ID, $authUser->id], [1, 4]);
                DB::commit();
                return back()->with('Success!', "Writer Change Successfully!");
            }
            DB::rollBack();
            return back()->with('Error!', "Writer Assigning Failed!");
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->route('Error.Response', ['Message' => $e->getMessage()]);
        }
    }

    public function removeContentWriter(Request $request): RedirectResponse
    {
        try {
            DB::beginTransaction();
            $Order = OrderAssigningInfo::where('order_id', $request->order_id)
                ->where('assign_id', $request->user_id)
                ->delete();
            if ($Order) {
                $authUser = Auth::guard('Authorized')->user();
                $message = $request->Order_ID . ' Content Writer has been Removed!';
                PortalHelpers::sendNotification(null, $request->Order_ID, $message, $authUser->designation->Designation_Name, [$request->W_Assign_ID, $authUser->id], [1, 4]);
                DB::commit();
                return back()->with('Success!', "Writer Removed Successfully!");
            }
            DB::rollBack();
            return back()->with('Error!', "Writer Assigning Failed!");
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->route('Error.Response', ['Message' => $e->getMessage()]);
        }
    }

    public function ResearchOrderSubmission(Request $request): RedirectResponse
    {
        DB::beginTransaction();
        try {
            OrderBasicInfo::where('order_id', $request->order_id)
                ->update(['Order_Status' => 2]);

            $this->orderFinalSubmission($request);

            DB::commit();
            return back()->with('Success!', 'Final Files Submitted Successfully!');
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->route('Error.Response', ['Message' => $e->getMessage()]);
        }
    }

    public function ContentOrderSubmission(Request $request): RedirectResponse
    {
        DB::beginTransaction();
        try {
            ContentBasicInfo::where('order_id', $request->order_id)
                ->update(['Order_Status' => 2]);

            $this->orderFinalSubmission($request);
            $this->handleContentWriterStatistics($request, 2);

            DB::commit();
            return back()->with('Success!', 'Final Files Submitted Successfully!');
        } catch (Exception $e) {
            DB::rollBack();
            $message = $e->getMessage() . $e->getFile() . $e->getLine();
            return redirect()->route('Error.Response', ['Message' => $message]);
        }
    }

    public function ResearchOrderRevision(Request $request): RedirectResponse
    {
        DB::beginTransaction();
        try {
            $orderBasicInfo = OrderBasicInfo::where('order_id', $request->order_id)->first();
            return $this->OrderRevision($request, $orderBasicInfo);
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->route('Error.Response', ['Message' => $e->getMessage()]);
        }
    }

    public function ContentOrderRevision(Request $request): RedirectResponse
    {
        DB::beginTransaction();
        try {
            $orderBasicInfo = ContentBasicInfo::where('order_id', $request->order_id)->first();
            $this->handleContentWriterStatistics($request, 3);
            return $this->OrderRevision($request, $orderBasicInfo);
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->route('Error.Response', ['Message' => $e->getMessage()]);
        }
    }

    /**
     * @param Request $request
     * @param $orderBasicInfo
     * @return RedirectResponse
     */
    private function OrderRevision(Request $request, $orderBasicInfo): RedirectResponse
    {
        $additionalWords = (float)Str::replace(['$ ', ','], "", $orderBasicInfo->Word_Count) + (float)$request->Order_Words;
        $orderBasicInfo->update([
            'Word_Count' => $additionalWords,
            'Order_Status' => 3,
        ]);

        $orderSubmissionInfo = OrderSubmissionInfo::where('order_id', $request->order_id)->first();

        $orderSubmissionInfo->update([
            'DeadLine' => $request->DeadLine,
            'DeadLine_Time' => $request->DeadLine_Time,
        ]);

        $orderRevision = OrderRevision::create([
            'Order_Revision' => $request->input('Order_Revision'),
            'order_id' => $request->order_id,
            'revised_by' => $request->revised_by,
        ]);

        if ($request->hasFile('files')) {
            foreach ($request->file('files') as $file) {
                $fileName = $file->getClientOriginalName();
                $filePath = 'Uploads/Revision-Attachments/' . $request->Order_ID . '/' . $fileName;

                $file->move(public_path('Uploads/Revision-Attachments/' . $request->Order_ID), $fileName);

                OrderRevisionAttachments::create([
                    'File_Name' => $fileName,
                    'file_path' => $filePath,
                    'revision_id' => $orderRevision->id,
                ]);
            }
        }

        $Order_Info = OrderInfo::where('Order_ID', $request->Order_ID)->firstOrFail();
        $authUser = Auth::guard('Authorized')->user();
        $message = $request->Order_ID . ' The Revision of Order has been Placed!';
        PortalHelpers::sendNotification(null, $request->Order_ID, $message, $authUser->designation->Designation_Name, [(int)$Order_Info->assign_id], [1, 4, (int)$authUser->Role_ID, 5, 9, 10, 11]);

        DB::commit();
        return back()->with('Success!', 'Order Revision Submitted!');
    }

    public function RevisionNotification(Request $request): RedirectResponse
    {
        $Order_Task = OrderTask::where('id', $request->task_id)->firstOrFail();
        $authUser = Auth::guard('Authorized')->user();
        $message = $request->Order_ID . ' The Revision of Task has been Placed!';
        PortalHelpers::sendNotification(null, $request->Order_ID, $message, $authUser->designation->Designation_Name, [(int)$request->revised_by, (int)$authUser->id, (int)$Order_Task->assign_id], [1, 4, 5]);

        DB::commit();
        return back()->with('Success!', "Revision Submitted Successfully!");
    }

    public function CancelWordsTask(Request $request): RedirectResponse
    {
        DB::beginTransaction();
        try {
            OrderTask::where('id', $request->task_id)
                ->update([
                    'Task_Status' => 1
                ]);

            TaskCancelWords::create([
                'Comments' => $request->Cancellation_Comments,
                'task_id' => $request->task_id,
                'order_id' => $request->order_id,
                'cancel_by' => $request->cancel_by
            ]);

            $authUser = Auth::guard('Authorized')->user();
            $this->handleUserStatistics($request, 2);
            PortalHelpers::sendNotification(null, $request->Order_ID, 'The Words of Task has been Cancelled!', $authUser->designation->Designation_Name, [(int)$authUser->id], [1, 4, 5]);

            DB::commit();
            return back()->with('Success!', 'Words has been Canceled!');
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->route('Error.Response', ['Message' => $e->getMessage()]);
        }
    }

    private function handleUserStatistics(Request $request, int $status): void
    {
        $wordCount = OrderTask::where('id', $request->task_id)->firstOrFail();
        $assignWords = (float)str_replace(['$ ', ','], '', $wordCount->Assign_Words);

        $userWordsStats = UserWordsStats::where('task_id', $request->task_id)->first();

        if (empty($userWordsStats)) {
            UserWordsStats::create([
                'Completed' => $assignWords,
                'task_id' => $request->task_id,
                'order_id' => $wordCount->order_id,
                'user_id' => $wordCount->assign_id,
            ]);
        } else {
            $this->UserStatistics($status, $assignWords, $userWordsStats, $wordCount);
        }
    }

    private function handleContentWriterStatistics(Request $request, int $status): void
    {
        $wordCount = OrderInfo::where('id', $request->order_id)
            ->with('content_info')->firstOrFail();
        $assignWords = (float)str_replace(['$ ', ','], '', $wordCount->content_info->Word_Count);

        $userWordsStats = UserWordsStats::where('order_id', $request->order_id)->first();

        if (empty($userWordsStats)) {
            UserWordsStats::create([
                'Completed' => $assignWords,
                'order_id' => $wordCount->id,
                'user_id' => $request->user_id,
            ]);
        } else {
            $this->UserStatistics($status, $assignWords, $userWordsStats, $wordCount);
        }
    }

    private function UserStatistics(int $status, float $assignWords, $userWordsStats, $wordCount): void
    {
        if ($status === 0 || $status === 2) {
            $updateData = [
                'Completed' => $assignWords,
                'Canceled' => 0,
            ];
            UserWordsStats::where('id', $userWordsStats->id)
                ->update($updateData);
        } elseif ($status === 1 || $status === 3) {
            $updateData = [
                'Completed' => 0,
                'Canceled' => $assignWords,
            ];
            UserWordsStats::where('id', $userWordsStats->id)
                ->update($updateData);
        }
    }

    private function orderFinalSubmission(Request $request): void
    {
        $uploadedFiles = $request->file('files');

        foreach ($uploadedFiles as $key => $file) {
            $fileName = $file->getClientOriginalName();
            $filePath = 'Uploads/Final-Attachments/' . $request->Order_ID . '/' . $fileName;

            $file->move(public_path('Uploads/Final-Attachments/' . $request->Order_ID), $fileName);

            FinalOrderSubmission::create([
                'File_Name' => $fileName,
                'final_submission_path' => $filePath,
                'order_id' => $request->order_id,
                'user_id' => $request->submit_by,
            ]);
        }
        $authUser = Auth::guard('Authorized')->user();
        PortalHelpers::sendNotification(null, $request->Order_ID, 'The Order ' . $request->Order_ID . ' has been Submitted!', $authUser->designation->Designation_Name, [(int)$authUser->id], [1, 4, 5, 9, 10, 11, 12]);
    }
    // ======================= End Order Actions (Research & Content) ==========================

    // ====================== Order Actions Submit | Cancelled | Deleted ===============
    public function submitContentOrder(Request $request): RedirectResponse
    {
        DB::beginTransaction();
        $Order_ID = Crypt::decryptString($request->Order_ID);
        $OrderBasicInfo = ContentBasicInfo::where('order_id', $Order_ID)
            ->update([
                'Order_Status' => 2
            ]);
        return $this->sendResearchContentActions($OrderBasicInfo, $request, 'Order has been Submitted!');
    }

    public function cancelContentOrder(Request $request): RedirectResponse
    {
        DB::beginTransaction();
        $Order_ID = Crypt::decryptString($request->Order_ID);
        $OrderBasicInfo = ContentBasicInfo::where('order_id', $Order_ID)
            ->update([
                'Order_Status' => 1
            ]);
        return $this->sendResearchContentActions($OrderBasicInfo, $request, 'Order has been Cancelled!');
    }

    public function deleteContentOrder(Request $request): RedirectResponse
    {
        DB::beginTransaction();
        $Order_ID = Crypt::decryptString($request->Order_ID);
        $OrderBasicInfo = OrderInfo::where('order_id', $Order_ID)->delete();
        return $this->sendResearchContentActions($OrderBasicInfo, $request, 'Order has been Deleted!');
    }

    public function submitResearchOrder(Request $request): RedirectResponse
    {
        DB::beginTransaction();
        $Order_ID = Crypt::decryptString($request->Order_ID);
        $OrderBasicInfo = OrderBasicInfo::where('order_id', $Order_ID)
            ->update([
                'Order_Status' => 2
            ]);
        return $this->sendResearchContentActions($OrderBasicInfo, $request, 'Order has been Submitted!');
    }

    public function cancelResearchOrder(Request $request): RedirectResponse
    {
        DB::beginTransaction();
        $Order_ID = Crypt::decryptString($request->Order_ID);
        $OrderBasicInfo = OrderBasicInfo::where('order_id', $Order_ID)
            ->update([
                'Order_Status' => 1
            ]);

        OrderTask::where('order_id', $request->Order_ID)
            ->update([
                'Task_Status' => 1
            ]);
        return $this->sendResearchContentActions($OrderBasicInfo, $request, 'Order has been Cancelled!');
    }

    public function deleteResearchOrder(Request $request): RedirectResponse
    {
        DB::beginTransaction();
        $Order_ID = Crypt::decryptString($request->Order_ID);
        $OrderBasicInfo = OrderInfo::where('id', $Order_ID)->delete();
        return $this->sendResearchContentActions($OrderBasicInfo, $request, 'Order has been Deleted!');
    }

    /**
     * @param $OrderBasicInfo
     * @param Request $request
     * @param $message
     * @return RedirectResponse
     */
    private function sendResearchContentActions($OrderBasicInfo, Request $request, $message): RedirectResponse
    {
        if ($OrderBasicInfo) {
            $Order_ID = Crypt::decryptString($request->Order_ID);
            $authUser = Auth::guard('Authorized')->user();
            PortalHelpers::sendNotification($Order_ID, null, $message, $authUser->designation->Designation_Name, [$authUser->id], [1, 4, 5, 9, 10, 11]);
            DB::commit();
            return back()->with('Success!', "Order Submitted Successfully!");
        }
        DB::rollBack();
        return back()->with('Error!', "Order Submission Error!");
    }


    public function ContentOrderDraftSubmission(Request $request)
    {
        // dd($request->toArray());
        DB::beginTransaction(); // Start the transaction

        $draft_submission = DraftSubmission::create([
            'order_id' => $request->order_id,
            'order_number' => $request->Order_Number,
            'submitted_by' => $request->user_id,
            'draft_number' => $request->draft_number
        ]);

        if ($draft_submission) {
            $uploadedFiles = $request->file('files');
            if ($uploadedFiles) {
                foreach ($uploadedFiles as $key => $file) {
                    $fileName = $file->getClientOriginalName();
                    $filePath = 'Uploads/Draft-Attachments/' . $request->Order_Number . '/' . $request->draft_number . '/' . $fileName;

                    $file->move(public_path('Uploads/Draft-Attachments/' . $request->Order_Number . '/' . $request->draft_number), $fileName);

                    DraftAttachment::create([
                        'File_Name' => $fileName,
                        'File_Path' => $filePath,
                        'Draft_submission_id' => $draft_submission->id,
                    ]);
                }

                DB::commit();
                $authUser = Auth::guard('Authorized')->user();
                PortalHelpers::sendNotification(null, $request->Order_Number, 'The Order ' . $request->Order_Number . ' Draft has been  Submitted!', $authUser->designation->Designation_Name, [(int)$authUser->id], [1, 4, 9, 10, 11]);
                // Commit the transaction after all files are processed
                return back()->with('Success!', "Draft Submitted successfully");
            } else {
                DB::rollBack();
                return back()->with('Error!', "Draft Files not uploaded!");
            }
        } else {
            DB::rollBack();
            return back()->with('Error!', "Draft Upload Failed!");
        }
    }


    public function GetRevisionDetails(Request $request){
        $Revision_ID = $request->Revision_ID;
        $Revision_Info = OrderRevision::with('attachments' , 'order_info' , 'revision_by')->where('id', $Revision_ID)->first();

        if ($Revision_Info) {
            $Revised_By_Name = $Revision_Info->revision_by->basic_info->F_Name . ' ' . $Revision_Info->revision_by->basic_info->L_Name;

            $responseArray = [
                'revision_id' => $Revision_Info->id,
                'revision_details' => $Revision_Info->Order_Revision,
                'revised_by' => $Revised_By_Name,
                'Order_deadLine' => $Revision_Info->order_info->submission_info->DeadLine,
                'Order_deadLine_Time' => $Revision_Info->order_info->submission_info->DeadLine_Time,
                'Order_Id'=>$Revision_Info->order_id,
                'revision_date' => $Revision_Info->created_at,
                'attachments' => [],
            ];

            if ($Revision_Info->attachments->count() > 0) {
                foreach ($Revision_Info->attachments as $attachment) {
                    $responseArray['attachments'][] = [
                        'file_name' => $attachment->File_Name,
                        'file_path' => $attachment->file_path,
                        'attachment_time' => $attachment->created_at, 
                        
                    ];
                }
            }

            return $responseArray;

        } else {
            return ['error' => 'Order Revision not found'];
        }


        
    }


    public function SubmitOrderRevision(Request $request){      

        $uploadedFiles = $request->file('files');
      
        if ($uploadedFiles) {
            foreach ($uploadedFiles as $key => $file) {
                $fileName = $file->getClientOriginalName();
                $filePath = 'Uploads/Revision-Attachments/' . $request->Revision_ID . '/'. $fileName;

                $file->move(public_path('Uploads/Revision-Attachments/' . $request->Revision_ID . '/' ), $fileName);

                 SubmitRevisionAttachment::create([
                    'file_name' => $fileName,
                    'file_path' => $filePath,
                    'uploaded_by' => $request->upload_by,
                    'revision_id' => $request->Revision_ID,
                ]);
            }
            $OrderBasicInfo = OrderBasicInfo::where('order_id', $request->Order_ID)
            ->update([
                'Order_Status' => 2
            ]);

            if($OrderBasicInfo){
                $authUser = Auth::guard('Authorized')->user();
                $message = "The Order " . $request->Order_Number . "have Submit a Revision";
                PortalHelpers::sendNotification($request->Order_ID, null, $message, $authUser->designation->Designation_Name, [$authUser->id], [1, 4, 5, 9, 10, 11]);
                return back()->with('Success!' , 'Revision Submited Successfully');
            }else{
                return back()->with('Error!', 'Revision Not Submited Sucessfully');
            }




        }else{
            return back()->with('Error!', 'File Not Submited Sucessfuyy');

        }

    }


    public function GetRevisionAttachment(Request $request)
    {
        $Revision_Info = SubmitRevisionAttachment::where('revision_id', $request->Revision_ID)->get();

        $tableHtml = ''; // Initialize an empty string

        foreach ($Revision_Info as $index => $attachment) {
            $assetUrl = asset($attachment->file_path);
            $Full_Name = $attachment->UplodedBy->basic_info->F_Name . ' ' . $attachment->UplodedBy->basic_info->L_Name;

            $tableHtml .= '<tr>'; // Append to the existing string
            $tableHtml .= '<td>' . ($index + 1) . '</td>';
            $tableHtml .= '<td>' . $attachment->file_name . '<br>' . $attachment->created_at . '</td>';
            $tableHtml .= '<td>' . $Full_Name . '</td>';
            $tableHtml .= '<td>';
            $tableHtml .= '<div class="d-flex justify-content-center">';
            $tableHtml .= '<a href="' . $assetUrl . '" class="action-btns1" ' .
                'data-bs-toggle="tooltip" download="' . $attachment->file_name . '" ' .
                'data-bs-placement="top" title="Download" target="_blank" aria-label="Download">';
            $tableHtml .= '<i class="feather feather-download text-success"></i>';
            $tableHtml .= '</a>';
            $tableHtml .= '</div>';
            $tableHtml .= '</td>';
            $tableHtml .= '</tr>';
        }

        return $tableHtml;
    }




  
}
