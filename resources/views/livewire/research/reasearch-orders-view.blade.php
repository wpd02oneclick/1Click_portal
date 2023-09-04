<style>
    .msg_card_body {
        background: url({{ asset('assets/images/pattern2.png') }}) !important;
        overflow-y: auto;
    }

    .chatbox .card,
    #chatmodel {
        min-height: 100vh !important;
    }

    .card-body.pt-2.ps-3.pr-3 tr {
        text-align: right !important;
    }
</style>

@php
   //dd($Research_Order->toArray());
    $assign = 0;
    $total_words = 0;
@endphp
{{-- Admin View --}}
@if ((int) $auth_user->Role_ID === 1)
    <!--Page header-->
    <div class="page-header d-xl-flex d-block">
        <div class="page-rightheader ms-md-auto">
            <div class="d-flex align-items-end flex-wrap my-auto end-content breadcrumb-end">
                <div class="btn-list">
                    <div class="dropdown">
                        <button class="btn btn-info dropdown-toggle px-5" data-bs-toggle="dropdown">
                            <i class="fe fe-activity me-2"></i>Actions
                        </button>
                        <div class="dropdown-menu">
                            {{--                            <a class="dropdown-item" href="{{ route('Submit.Research.Order', ['Order_ID' => $Research_Order->id]) }}">Submit Order</a> --}}
                            @if ($Research_Order->basic_info->Order_Status !== 'Completed')
                                <a class="dropdown-item" href="javascript:void(0)" data-bs-toggle="modal"
                                    data-bs-target="#AssignModal">Assign Order</a>
                                <a class="dropdown-item" href="javascript:void(0)" data-bs-toggle="modal"
                                    data-bs-target="#NewTaskModal">New Task</a>
                            @endif
                            <a class="dropdown-item Order-Revision" href="JavaScript:void(0);" data-bs-toggle="modal"
                                data-bs-target="#TaskRevisionModal">Add Revision
                                <input type="hidden" id="Order_ID" value="{{ $Research_Order->Order_ID }}"></a>
                            <a class="dropdown-item"
                                href="{{ route('Research.Edit.Order', ['Order_ID' => $Research_Order->Order_ID]) }}">Edit
                                Order</a>
                            <a class="dropdown-item"
                                href="{{ route('Cancel.Research.Order', ['Order_ID' => $Research_Order->id]) }}">Cancel
                                Order</a>
                            <a class="dropdown-item"
                                href="{{ route('Delete.Research.Order', ['Order_ID' => $Research_Order->id]) }}">Delete
                                Order</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--End Page header-->
    <!-- Row -->
    <div class="row">
        <div class="col-xl-9 col-md-12 col-lg-12">
            <div class="tab-menu-heading hremp-tabs p-0 ">
                <div class="tabs-menu1">
                    <!-- Tabs -->
                    <ul class="nav panel-tabs">
                        <li class="ms-4"><a href="#tab5" class="active" data-bs-toggle="tab">Order Description</a>
                        </li>
                        <li><a href="#tab7" data-bs-toggle="tab">Order Attachments</a></li>
                        <li><a href="#tab8" data-bs-toggle="tab">Assign Task</a></li>
                        <li><a href="#tab9" data-bs-toggle="tab">Writer Submission</a></li>
                        <li><a href="#tab10" data-bs-toggle="tab">Final Submission</a></li>
                        @if (!empty($Research_Order->revision))
                            <li><a href="#tab11" data-bs-toggle="tab">Order Revisions</a></li>
                        @endif
                        @if (!empty($Research_Order->submission_info->F_DeadLine) || !empty($Research_Order->submission_info->S_DeadLine) || !empty($Research_Order->submission_info->T_DeadLine))
                            <li><a href="#tab12" data-bs-toggle="tab">Draft Submission</a></li>
                        @endif
                    </ul>
                </div>
            </div>
            <div class="panel-body tabs-menu-body hremp-tabs1 p-0">
                <div class="tab-content">
                    <div class="tab-pane active" id="tab5">
                        <div class="card-body">
                            {!! $Research_Order->order_desc->Description !!}
                        </div>
                    </div>
                    <div class="tab-pane" id="tab7">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table text-center table-vcenter text-nowrap table-bordered border-bottom"
                                    id="files-tables">
                                    <thead>
                                        <tr>
                                            <th class="border-bottom-0 text-center w-5">No</th>
                                            <th class="border-bottom-0">File Name</th>
                                            <th class="border-bottom-0">Download File</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($Research_Order->attachments as $attachment)
                                            <tr>
                                                <td class="text-center">{{ $loop->iteration }}</td>
                                                <td>
                                                    <a href="{{ asset($attachment->order_attachment_path) }}"
                                                        target="_blank" download
                                                        class="font-weight-semibold fs-14 mt-5">{{ $attachment->File_Name }}
                                                        <span class="text-muted ms-2">(23 KB)</span></a>
                                                    <div class="clearfix"></div>
                                                    <small class="text-muted">{{ $attachment->created_at }}</small>
                                                </td>

                                                <td>
                                                    <div class="d-flex justify-content-center">
                                                        <a href="{{ asset($attachment->order_attachment_path) }}"
                                                            class="action-btns1" data-bs-toggle="tooltip" download
                                                            data-bs-placement="top" title="Download" target="_blank"><i
                                                                class="feather feather-download   text-success"></i></a>
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="3">Files are Not Attached</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane" id="tab8">
                        <div class="card-body">
                            <div class="table-responsive" style="min-height: 30vh;">

                                <table class="table text-center table-vcenter text-nowrap table-bordered border-bottom"
                                    id="responsive-datatable">
                                    <thead>
                                        <tr>
                                            <th class="border-bottom-0 text-center">Task</th>
                                            <th class="border-bottom-0">Writer</th>
                                            <th class="border-bottom-0">Assign Date</th>
                                            <th class="border-bottom-0">Assign Words</th>
                                            <th class="border-bottom-0">Remaining Words</th>
                                            <th class="border-bottom-0">Deadline</th>
                                            <th class="border-bottom-0">Status</th>
                                            <th class="border-bottom-0">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($Research_Order->tasks as $Task)
                                            <tr>
                                                <td class="font-weight-bold">Task-{{ $loop->iteration }}</td>
                                                <td>
                                                    {{ $Task->assign->basic_info->full_name }}
                                                </td>
                                                <td>
                                                    {{ $Task->created_at }}
                                                </td>
                                                <td>
                                                    {{ $Task->Assign_Words }}
                                                </td>
                                                <td>
                                                    {{ $Task->Due_Words }}
                                                </td>
                                                <td>
                                                    {{ $Task->DeadLine }} &nbsp; {{ $Task->DeadLine_Time }}
                                                </td>
                                                <td>
                                                    {{ $Task->Task_Status }}
                                                </td>
                                                <td>
                                                    <div class="btn-list">
                                                        <div class="dropdown">
                                                            <button class="btn btn-info dropdown-toggle px-5"
                                                                data-bs-toggle="dropdown">
                                                                <i class="fe fe-activity me-2"></i>Actions
                                                            </button>
                                                            <div class="dropdown-menu">
                                                                <a class="dropdown-item view-assign-task"
                                                                    href="javascript:void(0)" data-bs-toggle="modal"
                                                                    data-bs-target="#ViewTaskModal"
                                                                    data-id="{{ $Task->id }}">View Detail</a>
                                                                <a class="dropdown-item Task_ID"
                                                                    href="javascript:void(0)" data-bs-toggle="modal"
                                                                    data-bs-target="#ChangedWriter">
                                                                    <input type="hidden" id="Task_ID"
                                                                        value="{{ $Task->id }}">Change
                                                                    Writer</a>
                                                                <a class="dropdown-item Edit-Task Task_ID"
                                                                    href="javascript:void(0)" data-bs-toggle="modal"
                                                                    data-bs-target="#EditTaskModal">
                                                                    <input type="hidden" id="Task_ID"
                                                                        value="{{ $Task->id }}">
                                                                    Edit Task</a>
                                                                <a class="dropdown-item Delete_Task"
                                                                    href="{{ route('Delete.Task', ['Task_ID' => $Task->id]) }}">
                                                                    Delete Task
                                                                </a>
                                                                <a class="dropdown-item Task_ID"
                                                                    href="javascript:void(0)" data-bs-toggle="modal"
                                                                    data-bs-target="#TaskRevisionModal">
                                                                    <input type="hidden" id="Task_ID"
                                                                        value="{{ $Task->id }}">
                                                                    Add Revision</a>
                                                                @if (count($Task->revision) > 0)
                                                                    <a class="dropdown-item View-Revision"
                                                                        href="javascript:void(0)"
                                                                        data-bs-toggle="modal"
                                                                        data-bs-target="#ViewRevisionModal">View
                                                                        Revision
                                                                        <input type="hidden" id="Task_ID"
                                                                            value="{{ $Task->id }}"></a>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                            @php
                                                if ($Task->Task_Status !== 'Canceled') {
                                                    $assign += (float) Str::replace(['$ ', ','], '', $Task->Assign_Words);
                                                    $wordcount = (float) Str::replace(['$', ','], '', $Research_Order->basic_info->Word_Count);
                                                    $total_words = $wordcount - $assign;
                                                }
                                            @endphp
                                        @empty
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane" id="tab9">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table text-center table-vcenter text-nowrap table-bordered border-bottom"
                                    id="files-tables">
                                    <thead>
                                        <tr>
                                            <th class="border-bottom-0 text-center w-5">No</th>
                                            <th class="border-bottom-0">File Name</th>
                                            <th class="border-bottom-0">Download File</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($Research_Order->tasks as $task)
                                            @forelse($task->submit_info as $submission)
                                                <tr>
                                                    <td class="text-center">{{ $loop->iteration }}</td>
                                                    <td>
                                                        <a href="{{ asset($submission->task_file_path) }}"
                                                            target="_blank" download
                                                            class="font-weight-semibold fs-14 mt-5">{{ $submission->File_Name }}
                                                            <span class="text-muted ms-2">(23 KB)</span></a>
                                                        <div class="clearfix"></div>
                                                        <small class="text-muted">{{ $submission->created_at }} -
                                                            Submitted
                                                            By
                                                            {{ $submission->submitted->basic_info->full_name }}</small>
                                                    </td>

                                                    <td>
                                                        <div class="d-flex justify-content-center">
                                                            <a href="{{ asset($submission->task_file_path) }}"
                                                                class="action-btns1" data-bs-toggle="tooltip" download
                                                                data-bs-placement="top" title="Download"
                                                                target="_blank"><i
                                                                    class="feather feather-download   text-success"></i></a>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @empty
                                            @endforelse
                                        @empty
                                            <tr>
                                                <td colspan="3">Files are Not Attached</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane" id="tab10">
                        <div class="card-body">
                            <div class="d-flex justify-content-end">
                                <button type="button" class="btn btn-danger mb-4" data-bs-toggle="modal"
                                    data-bs-target="#FinalSubmissionModal"> Upload Files
                                </button>
                            </div>
                            <div class="table-responsive">
                                <table class="table text-center table-vcenter text-nowrap table-bordered border-bottom"
                                    id="files-tables">
                                    <thead>
                                        <tr>
                                            <th class="border-bottom-0 text-center w-5">No</th>
                                            <th class="border-bottom-0">File Name</th>
                                            <th class="border-bottom-0">Download File</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($Research_Order->final_submission as $submission)
                                            <tr>
                                                <td class="text-center">{{ $loop->iteration }}</td>
                                                <td>
                                                    <a href="{{ asset($submission->final_submission_path) }}"
                                                        target="_blank" download
                                                        class="font-weight-semibold fs-14 mt-5">{{ $submission->File_Name }}
                                                        <span class="text-muted ms-2">(23 KB)</span></a>
                                                    <div class="clearfix"></div>
                                                    <small class="text-muted">{{ $submission->created_at }}</small>
                                                </td>

                                                <td>
                                                    <div class="d-flex justify-content-center">
                                                        <a href="{{ asset($submission->final_submission_path) }}"
                                                            class="action-btns1" data-bs-toggle="tooltip" download
                                                            data-bs-placement="top" title="Download"
                                                            target="_blank"><i
                                                                class="feather feather-download   text-success"></i></a>
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="3">Files are Not Attached</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                  @if (!empty($Research_Order->revision))
                        <div class="tab-pane" id="tab11">
                           
                                 <div class="card-body">
                                <div class="d-flex justify-content-end">
                                    
                                </div>
                                <div class="table-responsive">
                                    <table
                                        class="table text-center table-vcenter text-nowrap table-bordered border-bottom"
                                        id="files-tables">
                                        <thead>
                                            <tr>
                                                <th class="border-bottom-0 text-center w-5">No</th>
                                                <th class="border-bottom-0">Revised By</th>
                                                <th class="border-bottom-0">Date</th>
                                                <th class="border-bottom-0">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>                                       
                                             @foreach ($Research_Order->revision as $revision)
                                                    <tr>
                                                        <td>{{ $loop->iteration}}</td>
                                                        <td>{{ $revision->revision_by->basic_info->F_Name . ' ' . $revision->revision_by->basic_info->L_Name }}</td>
                                                        <td>{{$revision->created_at}}</td>
                                                        <td>
                                                        <div class="btn-list">
                                                            <div class="dropdown">
                                                                <button class="btn btn-info dropdown-toggle px-5" data-bs-toggle="dropdown">
                                                                    <i class="fe fe-activity me-2"></i>Actions
                                                                </button>
                                                                <div class="dropdown-menu">
                                                                    <a class="dropdown-item Order-Revision" id="Revision_ID" data-id="{{ $revision->id }}" href="JavaScript:void(0);" data-bs-toggle="modal"
                                                                        data-bs-target="#ViewRevision">View Revision</a>

                                                                </div>
                                                            </div>
                                                        </div>
                                                        </td>
                                                    </tr>                                               
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                           
                        </div>
                    @endif
                    @if (!empty($Research_Order->submission_info->F_DeadLine) || !empty($Research_Order->submission_info->S_DeadLine) || !empty($Research_Order->submission_info->T_DeadLine))
                        <div class="tab-pane" id="tab12">
                            <div class="card-body">
                                <div class="d-flex justify-content-end">
                                    <button type="button" class="btn btn-danger mb-4" data-bs-toggle="modal"
                                        data-bs-target="#uploaddraft"> Upload Draft
                                    </button>
                                </div>
                                <div class="table-responsive">
                                    <table
                                        class="table text-center table-vcenter text-nowrap table-bordered border-bottom"
                                        id="files-tables">
                                        <thead>
                                            <tr>
                                                <th class="border-bottom-0 text-center w-5">No</th>
                                                <th class="border-bottom-0">File Name</th>
                                                <th class="border-bottom-0">Upload By</th>
                                                <th class="border-bottom-0">Download File</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        @php
                                            $a = 1;
                                        @endphp
                                            @foreach ($draft_submission as $submission)
                                                @forelse ($submission->attachments as $attachment)
                                                    <tr>
                                                        <td class="text-center">{{ $a }}</td>
                                                        <td>
                                                            <a href="{{ asset($attachment->File_Path) }}"
                                                                target="_blank" download
                                                                class="font-weight-semibold fs-14 mt-5">
                                                                {{ $attachment->File_Name }}
                                                            </a>
                                                            @if ($submission->draft_number === 1)
                                                                <div class="">First Draft</div>
                                                            @elseif($submission->draft_number === 2)
                                                                <div class="">Second Draft</div>
                                                            @elseif($submission->draft_number === 3)
                                                                <div class="">Third Draft</div>
                                                            @else
                                                                <div class="">Unknown Draft</div>
                                                            @endif
                                                            <div>{{ $submission->created_at }}</div>
                                                        </td>
                                                        <td>{{ $submission->submittedByUser->basic_info->F_Name }}
                                                        </td>
                                                        <td>
                                                            <div class="d-flex justify-content-center">
                                                                <a href="{{ asset($attachment->File_Path) }}"
                                                                    class="action-btns1" data-bs-toggle="tooltip"
                                                                    download="" data-bs-placement="top"
                                                                    title="" target="_blank"
                                                                    data-bs-original-title="Download"
                                                                    aria-label="Download">
                                                                    <i
                                                                        class="feather feather-download text-success"></i>
                                                                </a>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    @php
                                                        $a++;
                                                    @endphp
                                                @empty
                                                    <tr>
                                                        <td colspan="3">No Files Attached</td>
                                                    </tr>
                                                @endforelse
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
            <div class="chatbox mt-lg-5" id="chatmodel">
                <div class="chat border-0">
                    <div class="card overflow-hidden mb-0 border-0">
                        <!-- action-header -->
                        <div class="card-header">
                            <div class="float-start hidden-xs d-flex ms-2">
                                <div class="img_cont me-3">
                                    <img src="{{ !empty($auth_user->basic_info->profile_photo_path) ? asset($auth_user->basic_info->profile_photo_path) : asset('assets/images/users/16.jpg') }}"
                                        class="rounded-circle user_img avatar avatar-md" alt="img">
                                </div>
                                <div class="align-items-center mt-2 text-black">
                                    <h5 class="mb-0">{{ $auth_user->basic_info->full_name }}</h5>
                                    <span class="w-2 h-2 brround bg-success d-inline-block"></span><span
                                        class="ms-2 fs-12">Online</span>
                                </div>
                            </div>
                        </div>
                        <!-- action-header end -->
                        <!-- msg_card_body -->
                        <div class="card-body msg_card_body" id="Order-Messages">

                        </div>
                        <!-- msg_card_body end -->
                        <!-- card-footer -->
                        <div class="card-footer">
                            <form data-action="{{ route('Post.Message') }}" method="POST"
                                enctype="multipart/form-data" id="Chat-Form">
                                @csrf
                                <input type="hidden" name="order_id" value="{{ $Research_Order->id }}">
                                <input type="hidden" name="user_id"
                                    value="{{ Auth::guard('Authorized')->user()->id }}">
                                <div class="msb-reply-button d-flex mb-3">
                                    <div class="input-group">
                                        <input type="text" class="form-control bg-white" placeholder="Typing...."
                                            name="Chat_Message" required>
                                        <div class="input-group-append ">
                                            <button type="submit" class="btn btn-primary ">
                                                <span class="feather feather-send"></span> Send
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <div class="msb-reply-button d-flex">
                                    <div class="input-group">
                                        <input type="file" class="form-control bg-white"
                                            placeholder="Any Attachments" name="files[]" multiple>
                                    </div>
                                </div>
                            </form>
                        </div><!-- card-footer end -->
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-12 col-lg-12">
            <div class="card">
                <div class="card-header border-0">
                    <div class="card-title">Order Details</div>
                </div>
                <div class="card-body pt-2 ps-3 pr-3">
                    <div class="table-responsive">
                        <table class="table">
                            <tbody>
                                <tr>
                                    <td>
                                        <span class="w-50">Order ID </span>
                                    </td>
                                    <td>:</td>
                                    <td>
                                        <span class="font-weight-semibold">{{ $Research_Order->Order_ID }}</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <span class="w-50">Education</span>
                                    </td>
                                    <td>:</td>
                                    <td>
                                        <span
                                            class="font-weight-semibold">{{ $Research_Order->basic_info->Education_Level }}</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <span class="w-50">Pages</span>
                                    </td>
                                    <td>:</td>
                                    <td>
                                        <span
                                            class="font-weight-semibold">{{ $Research_Order->basic_info->Pages_Count }}</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <span class="w-50">Words</span>
                                    </td>
                                    <td>:</td>
                                    <td>
                                        <span
                                            class="font-weight-semibold">{{ $Research_Order->basic_info->Word_Count }}</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <span class="w-50">Spacing</span>
                                    </td>
                                    <td>:</td>
                                    <td>
                                        <span
                                            class="font-weight-semibold">{{ $Research_Order->basic_info->Spacing }}</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <span class="w-50">Citation Style</span>
                                    </td>
                                    <td>:</td>
                                    <td>
                                        <span
                                            class="font-weight-semibold">{{ $Research_Order->basic_info->Citation_Style }}</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <span class="w-50">Sources Needed</span>
                                    </td>
                                    <td>:</td>
                                    <td>
                                        <span
                                            class="font-weight-semibold">{{ $Research_Order->basic_info->Sources }}</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <span class="w-50">Website</span>
                                    </td>
                                    <td>:</td>
                                    <td>
                                        <span
                                            class="font-weight-semibold">{{ $Research_Order->basic_info->Order_Website }}</span>
                                    </td>
                                </tr>
                                @if (!empty($Research_Order->reference_info->Reference_Code))
                                    <tr>
                                        <td>
                                            <span class="w-50">Reference</span>
                                        </td>
                                        <td>:</td>
                                        <td>
                                            <span
                                                class="font-weight-semibold">{{ $Research_Order->reference_info->Reference_Code }}</span>
                                        </td>
                                    </tr>
                                @endif

                                @if (!empty($Research_Order->submission_info->F_DeadLine))
                                    <tr>
                                        <td>
                                            <span class="w-50">First Draft</span>
                                        </td>
                                        <td>:</td>
                                        <td>
                                            <span
                                                class="font-weight-semibold text-danger">{{ $Research_Order->submission_info->F_DeadLine }}</span>
                                        </td>
                                    </tr>
                                @endif
                                @if (!empty($Research_Order->submission_info->S_DeadLine))
                                    <tr>
                                        <td>
                                            <span class="w-50">Second Draft</span>
                                        </td>
                                        <td>:</td>
                                        <td>
                                            <span
                                                class="font-weight-semibold text-danger">{{ $Research_Order->submission_info->S_DeadLine }}</span>
                                        </td>
                                    </tr>
                                @endif
                                @if (!empty($Research_Order->submission_info->T_DeadLine))
                                    <tr>
                                        <td>
                                            <span class="w-50">Third Draft</span>
                                        </td>
                                        <td>:</td>
                                        <td>
                                            <span
                                                class="font-weight-semibold text-danger">{{ $Research_Order->submission_info->T_DeadLine }}</span>
                                        </td>
                                    </tr>
                                @endif
                                <tr>
                                    <td>
                                        <span class="w-50">Final Deadline</span>
                                    </td>
                                    <td>:</td>
                                    <td>
                                        <span
                                            class="font-weight-semibold text-danger">{{ $Research_Order->submission_info->DeadLine }}</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <span class="w-50">Status</span>
                                    </td>
                                    <td>:</td>
                                    <td>
                                        <span
                                            class="font-weight-semibold text-info">{{ $Research_Order->basic_info->Order_Status }}</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <span class="w-50">Created Date</span>
                                    </td>
                                    <td>:</td>
                                    <td>
                                        <span
                                            class="font-weight-semibold">{{ date('F d, Y', strtotime($Research_Order->created_at)) }}</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <span class="w-50">Created Time</span>
                                    </td>
                                    <td>:</td>
                                    <td>
                                        <span
                                            class="font-weight-semibold">{{ date('H:i:s A', strtotime($Research_Order->created_at)) }}</span>
                                    </td>
                                </tr>

                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
            <div class="card">
                <div class="card-header border-0">
                    <div class="card-title">Client Information</div>
                </div>
                <div class="card-body pt-2 ps-3 pr-3">
                    <div class="table-responsive">
                        <table class="table">
                            <tbody>
                                <tr>
                                    <td>
                                        <span class="w-50">Name</span>
                                    </td>
                                    <td>:</td>
                                    <td>
                                        <span
                                            class="font-weight-semibold">{{ $Research_Order->client_info->Client_Name }}</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <span class="w-50">Country </span>
                                    </td>
                                    <td>:</td>
                                    <td>
                                        <span
                                            class="font-weight-semibold">{{ $Research_Order->client_info->Client_Country }}</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <span class="w-50">Phone</span>
                                    </td>
                                    <td>:</td>
                                    <td>
                                        <span
                                            class="font-weight-semibold">{{ $Research_Order->client_info->Client_Phone }}</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <span class="w-50">Email</span>
                                    </td>
                                    <td>:</td>
                                    <td>
                                        <span
                                            class="font-weight-semibold">{{ $Research_Order->client_info->Client_Email }}</span>
                                    </td>
                                </tr>

                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
            <div class="card">
                <div class="card-header  border-0">
                    <div class="card-title">Research Department</div>
                </div>
                <div class="card-body pt-2 ps-3 pr-3">
                    <div class="table-responsive">
                        <table class="table">
                            <tbody>
                                @if (!empty($Research_Order->assign))
                                    @foreach ($Research_Order->assign as $User)
                                        <tr>
                                            <td>
                                                <span class="w-50">Coordinator {{ $loop->iteration }}</span>
                                            </td>
                                            <td>:</td>
                                            <td>
                                                <span
                                                    class="font-weight-semibold">{{ $User->basic_info->full_name }}</span>
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
                                @if (count($Research_Order->tasks) > 0)
                                    @foreach ($Research_Order->tasks as $task)
                                        <tr>
                                            <td>
                                                <span class="w-50">Task {{ $loop->iteration }} Assign To</span>
                                            </td>
                                            <td>:</td>
                                            <td>
                                                <span
                                                    class="font-weight-semibold">{{ $task->assign->basic_info->full_name }}</span>
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
            <div class="card">
                <div class="card-header  border-0">
                    <div class="card-title">Sales Department</div>
                </div>
                <div class="card-body pt-2 ps-3 pr-3">
                    <div class="table-responsive">
                        <table class="table">
                            <tbody>
                                <tr>
                                    <td>
                                        <span class="w-50">Create By</span>
                                    </td>
                                    <td>:</td>
                                    <td>
                                        <span
                                            class="font-weight-semibold">{{ $Research_Order->authorized_user->basic_info->full_name }}</span>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
            <div class="card">
                <div class="card-header border-0">
                    <div class="card-title">Payment Detail</div>
                </div>
                <div class="card-body pt-2 ps-3 pr-3">
                    <div class="table-responsive">
                        <table class="table">
                            <tbody>
                                <tr>
                                    <td>
                                        <span class="w-50">Order Price</span>
                                    </td>
                                    <td>:</td>
                                    <td>
                                        <span
                                            class="font-weight-semibold">{{ $Research_Order->payment_info->Order_Price }}
                                            &nbsp; {{ $Research_Order->payment_info->Order_Currency }}</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <span class="w-50">Payment Status</span>
                                    </td>
                                    <td>:</td>
                                    <td>
                                        <span
                                            class="font-weight-semibold">{{ $Research_Order->payment_info->Payment_Status }}
                                        </span>
                                    </td>
                                </tr>
                                @if ($Research_Order->payment_info->Payment_Status === 'Partial')
                                    <tr>
                                        <td>
                                            <span class="w-50">Receive Amount</span>
                                        </td>
                                        <td>:</td>
                                        <td>
                                            <span
                                                class="font-weight-semibold">{{ $Research_Order->payment_info->Rec_Amount }}
                                                &nbsp; {{ $Research_Order->payment_info->Order_Currency }}</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <span class="w-50">Receive Amount</span>
                                        </td>
                                        <td>:</td>
                                        <td>
                                            <span
                                                class="font-weight-semibold">{{ $Research_Order->payment_info->Due_Amount }}
                                                &nbsp; {{ $Research_Order->payment_info->Order_Currency }}</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="3">
                                            <span class="font-weight-semibold">Description</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="3">
                                            {{ $Research_Order->payment_info->Partial_Info }}
                                        </td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>
    <!-- Assign Modal -->
    <div class="modal fade" id="AssignModal">
        <div class="modal-dialog modal-md" role="document">
            <div class="modal-content">
                <form action="{{ route('Assign.Order') }}" method="POST" class="needs-validation was-validated">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Assign Order</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <input type="hidden" name="Order_ID" value="{{ $Order_ID }}">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="form-label" for="Assign_ID">Coordinators</label>
                                    <select name="Assign_ID[]" id="Assign_ID" class="form-control select2"
                                        data-placeholder="Select Coordinator" multiple required>
                                        <option value="">Select Coordinator</option>
                                        @forelse($Coordinators as $Coordinator)
                                            <option value="{{ $Coordinator->id }}">
                                                {{ $Coordinator->basic_info->full_name }}</option>
                                        @empty
                                            <option value="">Select Coordinator</option>
                                        @endforelse
                                    </select>
                                </div>

                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary btn-block">Assign Order</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- New Task Modal -->
    <div class="modal fade" id="NewTaskModal">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <form action="{{ route('New.Task') }}" method="POST" class="needs-validation was-validated">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Make Order New Task</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <input type="hidden" name="order_id" value="{{ $Research_Order->id }}">
                            <input type="hidden" name="Order_ID" value="{{ $Research_Order->Order_ID }}">
                            <input type="hidden" name="assign_by"
                                value="{{ Auth::guard('Authorized')->user()->id }}">
                            <input type="hidden" name="total_words" id="Total_Words"
                                value="{{ $total_words !== 0 ? $total_words : (float) Str::replace(['$ ', ','], '', $Research_Order->basic_info->Word_Count) }}">
                            @if ($Research_Order->assign->isEmpty())
                                <input type="hidden" name="Order_ID" value="{{ $Order_ID }}">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="form-label" for="C_Assign_ID">Coordinators</label>
                                        <select name="C_Assign_ID[]" id="C_Assign_ID" class="form-control select2"
                                            data-placeholder="Select Coordinator" multiple>
                                            <option value="">Select Coordinator</option>
                                            @forelse($Coordinators as $Coordinator)
                                                <option value="{{ $Coordinator->id }}">
                                                    {{ $Coordinator->basic_info->full_name }}</option>
                                            @empty
                                                <option value="">Select Coordinator</option>
                                            @endforelse
                                        </select>
                                    </div>
                                </div>
                            @endif
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="form-label">Select Writers</label>
                                    <select name="W_Assign_ID" class="form-control custom-select select2"
                                        data-placeholder="Select Writer" aria-required="true" required>
                                        <option value="">Select Writers</option>
                                        @forelse($Writers as $Writer)
                                            <option value="{{ $Writer->id }}">{{ $Writer->basic_info->full_name }}
                                            </option>
                                        @empty
                                        @endforelse
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">DeadLine</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                            <span class="feather feather-calendar"></span>
                                        </div>
                                    </div>
                                    <input class="form-control" placeholder="MM/DD/YYYY" name="DeadLine"
                                        type="date" min="0">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">DeadLine Time</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                            <span class="feather feather-clock"></span>
                                        </div>
                                    </div><!-- input-group-prepend -->
                                    <input class="form-control" id="tp3" placeholder="Set time"
                                        name="DeadLine_Time" type="time" required>
                                </div><!-- input-group -->
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Total Words</label>
                                    <input class="form-control mb-4 is-valid" name="Order_Words"
                                        placeholder="Enter Order Words" id="Assign_Words" min="0"
                                        type="number" value="" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Due Words</label>
                                    <input class="form-control mb-4 is-valid" name="Due_Words" readonly
                                        placeholder="Enter Due Amount" type="number" id="Rem_Word">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary btn-block">Create Task</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- Task Revision Modal -->
    <div class="modal fade" id="TaskRevisionModal">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <form action="{{ route('Task.Revision') }}" method="POST" class="needs-validation was-validated"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Add Revision For Current Task</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <input type="hidden" name="order_id" value="{{ $Research_Order->id }}">
                            <input type="hidden" name="Order_ID" value="{{ $Research_Order->Order_ID }}">
                            <input type="hidden" name="task_id" class="task-id">
                            <input type="hidden" name="revised_by"
                                value="{{ Auth::guard('Authorized')->user()->id }}">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="form-label" class="form-label"></label>
                                    <textarea id="summernote" class="form-control mb-4 is-invalid state-invalid" name="Task_Revision"
                                        placeholder="Textarea (invalid state)"></textarea>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">DeadLine</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                            <i class="feather feather-calendar"></i>
                                        </div>
                                    </div>
                                    <input class="form-control Rev-DeadLine" placeholder="MM/DD/YYYY" name="DeadLine"
                                        type="date">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">DeadLine Time</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                            <span class="feather feather-clock"></span>
                                        </div>
                                    </div><!-- input-group-prepend -->
                                    <input class="form-control Rev-Time" placeholder="Set time" name="DeadLine_Time"
                                        type="time" required>
                                </div><!-- input-group -->
                            </div>
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label for="form-label" class="form-label"></label>
                                    <input class="form-control" type="file" name="files[]" multiple>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary btn-block">Send Revision</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- View Revision Modal -->
    <div class="modal fade" id="ViewRevisionModal">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">View Revisions of Current Task</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body Get-Revisions">

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary btn-block" data-bs-dismiss="modal"
                        aria-label="Close">
                        Close
                    </button>
                </div>
            </div>
        </div>
    </div>
    <!-- Change Writer Modal -->
    <div class="modal fade" id="ChangedWriter">
        <div class="modal-dialog modal-md" role="document">
            <div class="modal-content">
                <form action="{{ route('Change.Writer') }}" method="POST" class="needs-validation was-validated">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Change Writer on Current Task</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <input type="hidden" name="task_id" class="task-id">
                            <input type="hidden" name="Order_ID" value="{{ $Research_Order->Order_ID }}">
                            <input type="hidden" name="assign_by"
                                value="{{ Auth::guard('Authorized')->user()->id }}">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="form-label">Select Writers</label>
                                    <select name="W_Assign_ID" class="form-control custom-select select2"
                                        data-placeholder="Select Writer" aria-required="true" required>
                                        <option value="">Select Writers</option>
                                        @forelse($Writers as $Writer)
                                            <option value="{{ $Writer->id }}">{{ $Writer->basic_info->full_name }}
                                            </option>
                                        @empty
                                        @endforelse
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary btn-block">Changed Writer</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- Edit Task Modal -->
    <div class="modal fade" id="EditTaskModal">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <form action="{{ route('Edit.Task') }}" method="POST" class="needs-validation was-validated">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Update Current Task</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <input type="hidden" name="task_id" class="task-id">
                            <input type="hidden" name="order_id" value="{{ $Research_Order->id }}">
                            <input type="hidden" name="assign_by"
                                value="{{ Auth::guard('Authorized')->user()->id }}">
                            <input type="hidden" class="Selected-Total-Words" name="total_words"
                                id="Edit_Total_Words">
                            <input type="hidden" name="Order_ID" value="{{ $Research_Order->Order_ID }}">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="form-label">Select Writers</label>
                                    <select name="W_Assign_ID"
                                        class="form-control custom-select select2 Selected-Writer"
                                        data-placeholder="Select Writer" aria-required="true" required>
                                        <option value="">Select Writers</option>
                                        @forelse($Writers as $Writer)
                                            <option value="{{ $Writer->id }}">
                                                {{ $Writer->basic_info->full_name }}</option>
                                        @empty
                                        @endforelse
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">DeadLine</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                            <span class="feather feather-calendar"></span>
                                        </div>
                                    </div>
                                    <input class="form-control Selected-Date" placeholder="MM/DD/YYYY"
                                        name="DeadLine" type="date" min="0">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">DeadLine Time</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                            <span class="feather feather-clock"></span>
                                        </div>
                                    </div><!-- input-group-prepend -->
                                    <input class="form-control Selected-Time" placeholder="Set time"
                                        name="DeadLine_Time" type="time" required>
                                </div><!-- input-group -->
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Total Words</label>
                                    <input class="form-control mb-4 is-valid Selected-Words" name="Order_Words"
                                        placeholder="Enter Order Words" id="Edit_Assign_Words" min="0"
                                        type="number" value="" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Due Words</label>
                                    <input class="form-control mb-4 is-valid Selected-Due-Words" name="Due_Words"
                                        readonly placeholder="Enter Due Amount" type="number" id="Edit_Rem_Word">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary btn-block">Update Current Task</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- Final Submission Modal -->
    <div class="modal fade" id="FinalSubmissionModal">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <form action="{{ route('Research.Order.Final.Submit') }}" method="POST"
                    class="needs-validation was-validated" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Final Submission For Current Order</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <input type="hidden" name="order_id" value="{{ $Research_Order->id }}">
                            <input type="hidden" name="Order_ID" value="{{ $Research_Order->Order_ID }}">
                            <input type="hidden" name="task_id" class="task-id">
                            <input type="hidden" name="submit_by"
                                value="{{ Auth::guard('Authorized')->user()->id }}">
                            <input type="hidden" name="user_id"
                                value="{{ Auth::guard('Authorized')->user()->id }}">
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label for="form-label" class="form-label"></label>
                                    <input class="form-control" type="file" name="files[]" multiple>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary btn-block">Upload Final Submission</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endif

{{-- Sales Manager & Coordinator View --}}
@if ((int) $auth_user->Role_ID === 9 || (int) $auth_user->Role_ID === 10)
    <!--Page header-->
    <div class="page-header d-xl-flex d-block">
        <div class="page-rightheader ms-md-auto">
            <div class="d-flex align-items-end flex-wrap my-auto end-content breadcrumb-end">
                <div class="btn-list">
                    <div class="dropdown">
                        <button class="btn btn-info dropdown-toggle px-5" data-bs-toggle="dropdown">
                            <i class="fe fe-activity me-2"></i>Actions
                        </button>
                        <div class="dropdown-menu">
                            <a class="dropdown-item Order-Revision" href="JavaScript:void(0);" data-bs-toggle="modal"
                                data-bs-target="#TaskRevisionModal">Add Revision
                                <input type="hidden" id="Order_ID" value="{{ $Research_Order->Order_ID }}"></a>
                            <a class="dropdown-item"
                                href="{{ route('Research.Edit.Order', ['Order_ID' => $Research_Order->Order_ID]) }}">Edit
                                Order</a>
                            <a class="dropdown-item"
                                href="{{ route('Cancel.Research.Order', ['Order_ID' => $Research_Order->id]) }}">Cancel
                                Order</a>
                            <a class="dropdown-item"
                                href="{{ route('Delete.Research.Order', ['Order_ID' => $Research_Order->id]) }}">Delete
                                Order</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--End Page header-->
    <!-- Row -->
    <div class="row">
        <div class="col-xl-9 col-md-12 col-lg-12">
            <div class="tab-menu-heading hremp-tabs p-0 ">
                <div class="tabs-menu1">
                    <!-- Tabs -->
                    <ul class="nav panel-tabs">
                        <li class="ms-4"><a href="#tab5" class="active" data-bs-toggle="tab">Order
                                Description</a></li>
                        <li><a href="#tab7" data-bs-toggle="tab">Order Attachments</a></li>
                        <li><a href="#tab9" data-bs-toggle="tab">Order Submission</a></li>
                        @if (!empty($Research_Order->revision))
                            <li><a href="#tab10" data-bs-toggle="tab">Order Revisions</a></li>
                        @endif
                        @if (!empty($Research_Order->submission_info->F_DeadLine) || !empty($Research_Order->submission_info->S_DeadLine) || !empty($Research_Order->submission_info->T_DeadLine))
                            <li><a href="#tab12" data-bs-toggle="tab">Draft Submission</a></li>
                        @endif
                    </ul>
                </div>
            </div>
            <div class="panel-body tabs-menu-body hremp-tabs1 p-0">
                <div class="tab-content">
                    <div class="tab-pane active" id="tab5">
                        <div class="card-body">
                            {!! $Research_Order->order_desc->Description !!}
                        </div>
                    </div>
                    <div class="tab-pane" id="tab7">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table text-center table-vcenter text-nowrap table-bordered border-bottom"
                                    id="files-tables">
                                    <thead>
                                        <tr>
                                            <th class="border-bottom-0 text-center w-5">No</th>
                                            <th class="border-bottom-0">File Name</th>
                                            <th class="border-bottom-0">Download File</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($Research_Order->attachments as $attachment)
                                            <tr>
                                                <td class="text-center">{{ $loop->iteration }}</td>
                                                <td>
                                                    <a href="{{ asset($attachment->order_attachment_path) }}"
                                                        target="_blank" download
                                                        class="font-weight-semibold fs-14 mt-5">{{ $attachment->File_Name }}
                                                        <span class="text-muted ms-2">(23 KB)</span></a>
                                                    <div class="clearfix"></div>
                                                    <small class="text-muted">{{ $attachment->created_at }}</small>
                                                </td>

                                                <td>
                                                    <div class="d-flex justify-content-center">
                                                        <a href="{{ asset($attachment->order_attachment_path) }}"
                                                            class="action-btns1" data-bs-toggle="tooltip" download
                                                            data-bs-placement="top" title="Download"
                                                            target="_blank"><i
                                                                class="feather feather-download   text-success"></i></a>
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="3">Files are Not Attached</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane" id="tab9">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table text-center table-vcenter text-nowrap table-bordered border-bottom"
                                    id="files-tables">
                                    <thead>
                                        <tr>
                                            <th class="border-bottom-0 text-center w-5">No</th>
                                            <th class="border-bottom-0">File Name</th>
                                            <th class="border-bottom-0">Download File</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($Research_Order->final_submission as $submission)
                                            <tr>
                                                <td class="text-center">{{ $loop->iteration }}</td>
                                                <td>
                                                    <a href="{{ asset($submission->final_submission_path) }}"
                                                        target="_blank" download
                                                        class="font-weight-semibold fs-14 mt-5">{{ $submission->File_Name }}
                                                        <span class="text-muted ms-2">(23 KB)</span></a>
                                                    <div class="clearfix"></div>
                                                    <small class="text-muted">{{ $submission->created_at }}</small>
                                                </td>

                                                <td>
                                                    <div class="d-flex justify-content-center">
                                                        <a href="{{ asset($submission->final_submission_path) }}"
                                                            class="action-btns1" data-bs-toggle="tooltip" download
                                                            data-bs-placement="top" title="Download"
                                                            target="_blank"><i
                                                                class="feather feather-download   text-success"></i></a>
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="3">Files are Not Attached</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    @if (!empty($Research_Order->revision))
                        <div class="tab-pane" id="tab10">
                           
                                 <div class="card-body">
                                <div class="d-flex justify-content-end">
                                    
                                </div>
                                <div class="table-responsive">
                                    <table
                                        class="table text-center table-vcenter text-nowrap table-bordered border-bottom"
                                        id="files-tables">
                                        <thead>
                                            <tr>
                                                <th class="border-bottom-0 text-center w-5">No</th>
                                                <th class="border-bottom-0">Revised By</th>
                                                <th class="border-bottom-0">Date</th>
                                                <th class="border-bottom-0">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>                                       
                                             @foreach ($Research_Order->revision as $revision)
                                                    <tr>
                                                        <td>{{ $loop->iteration}}</td>
                                                        <td>{{ $revision->revision_by->basic_info->F_Name . ' ' . $revision->revision_by->basic_info->L_Name }}</td>
                                                        <td>{{$revision->created_at}}</td>
                                                        <td>
                                                        <div class="btn-list">
                                                            <div class="dropdown">
                                                                <button class="btn btn-info dropdown-toggle px-5" data-bs-toggle="dropdown">
                                                                    <i class="fe fe-activity me-2"></i>Actions
                                                                </button>
                                                                <div class="dropdown-menu">
                                                                    <a class="dropdown-item Order-Revision" id="Revision_ID" data-id="{{ $revision->id }}" href="JavaScript:void(0);" data-bs-toggle="modal"
                                                                        data-bs-target="#ViewRevision">View Revision</a>

                                                                </div>
                                                            </div>
                                                        </div>
                                                        </td>
                                                    </tr>                                               
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                           
                        </div>
                    @endif
                    @if (!empty($Research_Order->submission_info->F_DeadLine) || !empty($Research_Order->submission_info->S_DeadLine) || !empty($Research_Order->submission_info->T_DeadLine))
                        <div class="tab-pane" id="tab12">
                            <div class="card-body">
                                <div class="d-flex justify-content-end">
                                    
                                </div>
                                <div class="table-responsive">
                                    <table
                                        class="table text-center table-vcenter text-nowrap table-bordered border-bottom"
                                        id="files-tables">
                                        <thead>
                                            <tr>
                                                <th class="border-bottom-0 text-center w-5">No</th>
                                                <th class="border-bottom-0">File Name</th>
                                                <th class="border-bottom-0">Upload By</th>
                                                <th class="border-bottom-0">Download File</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        @php
                                            $b=1;
                                        @endphp
                                            @foreach ($draft_submission as $submission)
                                                @forelse ($submission->attachments as $attachment)
                                                    <tr>
                                                        <td class="text-center">{{ $b }}</td>
                                                        <td>
                                                            <a href="{{ asset($attachment->File_Path) }}"
                                                                target="_blank" download
                                                                class="font-weight-semibold fs-14 mt-5">
                                                                {{ $attachment->File_Name }}
                                                            </a>
                                                            @if ($submission->draft_number === 1)
                                                                <div class="">First Draft</div>
                                                            @elseif($submission->draft_number === 2)
                                                                <div class="">Second Draft</div>
                                                            @elseif($submission->draft_number === 3)
                                                                <div class="">Third Draft</div>
                                                            @else
                                                                <div class="">Unknown Draft</div>
                                                            @endif
                                                            <div>{{ $submission->created_at }}</div>
                                                        </td>
                                                        <td>{{ $submission->submittedByUser->basic_info->F_Name }}
                                                        </td>
                                                        <td>
                                                            <div class="d-flex justify-content-center">
                                                                <a href="{{ asset($attachment->File_Path) }}"
                                                                    class="action-btns1" data-bs-toggle="tooltip"
                                                                    download="" data-bs-placement="top"
                                                                    title="" target="_blank"
                                                                    data-bs-original-title="Download"
                                                                    aria-label="Download">
                                                                    <i
                                                                        class="feather feather-download text-success"></i>
                                                                </a>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                     @php
                                                         $b++;
                                                     @endphp
                                                @empty
                                                    <tr>
                                                        <td colspan="3">No Files Attached</td>
                                                    </tr>
                                                @endforelse
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
            <div class="chatbox mt-lg-5" id="chatmodel">
                <div class="chat border-0">
                    <div class="card overflow-hidden mb-0 border-0">
                        <!-- action-header -->
                        <div class="card-header">
                            <div class="float-start hidden-xs d-flex ms-2">
                                <div class="img_cont me-3">
                                    <img src="{{ !empty($auth_user->basic_info->profile_photo_path) ? asset($auth_user->basic_info->profile_photo_path) : asset('assets/images/users/16.jpg') }}"
                                        class="rounded-circle user_img avatar avatar-md" alt="img">
                                </div>
                                <div class="align-items-center mt-2 text-black">
                                    <h5 class="mb-0">{{ $auth_user->basic_info->full_name }}</h5>
                                    <span class="w-2 h-2 brround bg-success d-inline-block"></span><span
                                        class="ms-2 fs-12">Online</span>
                                </div>
                            </div>
                        </div>
                       
                        <!-- msg_card_body -->
                        <div class="card-body msg_card_body" id="Order-Messages">

                        </div>
                        <!-- card-footer -->
                        <div class="card-footer">
                            <form data-action="{{ route('Post.Message') }}" method="POST"
                                enctype="multipart/form-data" id="Chat-Form">
                                @csrf
                                <input type="hidden" name="order_id" value="{{ $Research_Order->id }}">
                                <input type="hidden" name="user_id"
                                    value="{{ Auth::guard('Authorized')->user()->id }}">
                                <div class="msb-reply-button d-flex mb-3">
                                    <div class="input-group">
                                        <input type="text" class="form-control bg-white" placeholder="Typing...."
                                            name="Chat_Message" required>
                                        <div class="input-group-append ">
                                            <button type="submit" class="btn btn-primary ">
                                                <span class="feather feather-send"></span> Send
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <div class="msb-reply-button d-flex">
                                    <div class="input-group">
                                        <input type="file" class="form-control bg-white"
                                            placeholder="Any Attachments" name="files[]" multiple>
                                    </div>
                                </div>
                            </form>
                        </div><!-- card-footer end -->
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-12 col-lg-12">
            <div class="card">
                <div class="card-header  border-0">
                    <div class="card-title">Order Details</div>
                </div>
                <div class="card-body pt-2 ps-3 pr-3">
                    <div class="table-responsive">
                        <table class="table">
                            <tbody>
                                <tr>
                                    <td>
                                        <span class="w-50">Order ID </span>
                                    </td>
                                    <td>:</td>
                                    <td>
                                        <span class="font-weight-semibold">{{ $Research_Order->Order_ID }}</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <span class="w-50">Education</span>
                                    </td>
                                    <td>:</td>
                                    <td>
                                        <span
                                            class="font-weight-semibold">{{ $Research_Order->basic_info->Education_Level }}</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <span class="w-50">Pages</span>
                                    </td>
                                    <td>:</td>
                                    <td>
                                        <span
                                            class="font-weight-semibold">{{ $Research_Order->basic_info->Pages_Count }}</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <span class="w-50">Words</span>
                                    </td>
                                    <td>:</td>
                                    <td>
                                        <span
                                            class="font-weight-semibold">{{ $Research_Order->basic_info->Word_Count }}</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <span class="w-50">Spacing</span>
                                    </td>
                                    <td>:</td>
                                    <td>
                                        <span
                                            class="font-weight-semibold">{{ $Research_Order->basic_info->Spacing }}</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <span class="w-50">Citation Style</span>
                                    </td>
                                    <td>:</td>
                                    <td>
                                        <span
                                            class="font-weight-semibold">{{ $Research_Order->basic_info->Citation_Style }}</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <span class="w-50">Sources Needed</span>
                                    </td>
                                    <td>:</td>
                                    <td>
                                        <span
                                            class="font-weight-semibold">{{ $Research_Order->basic_info->Sources }}</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <span class="w-50">Website</span>
                                    </td>
                                    <td>:</td>
                                    <td>
                                        <span
                                            class="font-weight-semibold">{{ $Research_Order->basic_info->Order_Website }}</span>
                                    </td>
                                </tr>
                                @if (!empty($Research_Order->reference_info->Reference_Code))
                                    <tr>
                                        <td>
                                            <span class="w-50">Reference</span>
                                        </td>
                                        <td>:</td>
                                        <td>
                                            <span
                                                class="font-weight-semibold">{{ $Research_Order->reference_info->Reference_Code }}</span>
                                        </td>
                                    </tr>
                                @endif
                                @if (!empty($Research_Order->submission_info->F_DeadLine))
                                    <tr>
                                        <td>
                                            <span class="w-50">First Draft</span>
                                        </td>
                                        <td>:</td>
                                        <td>
                                            <span
                                                class="font-weight-semibold text-danger">{{ $Research_Order->submission_info->F_DeadLine }}</span>
                                        </td>
                                    </tr>
                                @endif
                                @if (!empty($Research_Order->submission_info->S_DeadLine))
                                    <tr>
                                        <td>
                                            <span class="w-50">Second Draft</span>
                                        </td>
                                        <td>:</td>
                                        <td>
                                            <span
                                                class="font-weight-semibold text-danger">{{ $Research_Order->submission_info->S_DeadLine }}</span>
                                        </td>
                                    </tr>
                                @endif
                                @if (!empty($Research_Order->submission_info->T_DeadLine))
                                    <tr>
                                        <td>
                                            <span class="w-50">Third Draft</span>
                                        </td>
                                        <td>:</td>
                                        <td>
                                            <span
                                                class="font-weight-semibold text-danger">{{ $Research_Order->submission_info->T_DeadLine }}</span>
                                        </td>
                                    </tr>
                                @endif
                            

                                <tr>
                                    <td>
                                        <span class="w-50">Final Deadline</span>
                                    </td>
                                    <td>:</td>
                                    <td>
                                        <span
                                            class="font-weight-semibold text-danger">{{ $Research_Order->submission_info->DeadLine }}</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <span class="w-50">Status</span>
                                    </td>
                                    <td>:</td>
                                    <td>
                                        <span
                                            class="font-weight-semibold text-info">{{ $Research_Order->basic_info->Order_Status }}</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <span class="w-50">Created Date</span>
                                    </td>
                                    <td>:</td>
                                    <td>
                                        <span
                                            class="font-weight-semibold">{{ date('F d, Y', strtotime($Research_Order->created_at)) }}</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <span class="w-50">Created Time</span>
                                    </td>
                                    <td>:</td>
                                    <td>
                                        <span
                                            class="font-weight-semibold">{{ date('H:i:s A', strtotime($Research_Order->created_at)) }}</span>
                                    </td>
                                </tr>

                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
            <div class="card">
                <div class="card-header border-0">
                    <div class="card-title">Client Information</div>
                </div>
                <div class="card-body pt-2 ps-3 pr-3">
                    <div class="table-responsive">
                        <table class="table">
                            <tbody>
                                <tr>
                                    <td>
                                        <span class="w-50">Name</span>
                                    </td>
                                    <td>:</td>
                                    <td>
                                        <span
                                            class="font-weight-semibold">{{ $Research_Order->client_info->Client_Name }}</span>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
            <div class="card">
                <div class="card-header  border-0">
                    <div class="card-title">Sales Department</div>
                </div>
                <div class="card-body pt-2 ps-3 pr-3">
                    <div class="table-responsive">
                        <table class="table">
                            <tbody>
                                <tr>
                                    <td>
                                        <span class="w-50">Create By</span>
                                    </td>
                                    <td>:</td>
                                    <td>
                                        <span
                                            class="font-weight-semibold">{{ $Research_Order->authorized_user->basic_info->full_name }}</span>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
            <div class="card">
                <div class="card-header border-0">
                    <div class="card-title">Payment Detail</div>
                </div>
                <div class="card-body pt-2 ps-3 pr-3">
                    <div class="table-responsive">
                        <table class="table">
                            <tbody>
                                <tr>
                                    <td>
                                        <span class="w-50">Order Price</span>
                                    </td>
                                    <td>:</td>
                                    <td>
                                        <span
                                            class="font-weight-semibold">{{ $Research_Order->payment_info->Order_Price }}
                                            &nbsp; {{ $Research_Order->payment_info->Order_Currency }}</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <span class="w-50">Payment Status</span>
                                    </td>
                                    <td>:</td>
                                    <td>
                                        <span
                                            class="font-weight-semibold">{{ $Research_Order->payment_info->Payment_Status }}
                                        </span>
                                    </td>
                                </tr>
                                @if ($Research_Order->payment_info->Payment_Status === 'Partial')
                                    <tr>
                                        <td>
                                            <span class="w-50">Receive Amount</span>
                                        </td>
                                        <td>:</td>
                                        <td>
                                            <span
                                                class="font-weight-semibold">{{ $Research_Order->payment_info->Rec_Amount }}
                                                &nbsp; {{ $Research_Order->payment_info->Order_Currency }}</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <span class="w-50">Receive Amount</span>
                                        </td>
                                        <td>:</td>
                                        <td>
                                            <span
                                                class="font-weight-semibold">{{ $Research_Order->payment_info->Due_Amount }}
                                                &nbsp; {{ $Research_Order->payment_info->Order_Currency }}</span>
                                        </td>
                                    </tr>
                                    <tr class="">
                                        <td colspan="3">
                                            <span class="font-weight-semibold">Description</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="3">
                                            {{ $Research_Order->payment_info->Partial_Info }}
                                        </td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>
    <!-- Order Revision Modal -->
    <div class="modal fade" id="TaskRevisionModal">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <form action="{{ route('Research.Order.Revision') }}" method="POST"
                    class="needs-validation was-validated" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Add Revision For Current Order</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <input type="hidden" name="order_id" value="{{ $Research_Order->id }}">
                            <input type="hidden" name="Order_ID" value="{{ $Research_Order->Order_ID }}">
                            <input type="hidden" name="revised_by"
                                value="{{ Auth::guard('Authorized')->user()->id }}">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="form-label" class="form-label"></label>
                                    <textarea id="summernote" class="form-control mb-4 is-invalid state-invalid" name="Order_Revision"
                                        placeholder="Textarea (invalid state)"></textarea>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">DeadLine</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                            <i class="feather feather-calendar"></i>
                                        </div>
                                    </div>
                                    <input class="form-control Order-DeadLine" placeholder="MM/DD/YYYY"
                                        name="DeadLine" type="date">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">DeadLine Time</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                            <span class="feather feather-clock"></span>
                                        </div>
                                    </div><!-- input-group-prepend -->
                                    <input class="form-control Order-Time" id="tp3" placeholder="Set time"
                                        name="DeadLine_Time" type="time" required>
                                </div><!-- input-group -->
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Total Words</label>
                                    <input class="form-control mb-4 is-valid" name="Order_Words"
                                        placeholder="Enter Order Words" id="Order_Words" min="0"
                                        type="number" value="0" required>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label for="form-label" class="form-label"></label>
                                    <input class="form-control" type="file" name="files[]" multiple>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary btn-block">Upload Revision</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


   
@endif

{{-- Sales Executive View --}}
@if ((int) $auth_user->Role_ID === 11)
    <!--Page header-->
    <div class="page-header d-xl-flex d-block">
        <div class="page-rightheader ms-md-auto">
        </div>
    </div>
    <!--End Page header-->
    <!-- Row -->
    <div class="row">
        <div class="col-xl-9 col-md-12 col-lg-12">
            <div class="tab-menu-heading hremp-tabs p-0 ">
                <div class="tabs-menu1">
                    <!-- Tabs -->
                    <ul class="nav panel-tabs">
                        <li class="ms-4"><a href="#tab5" class="active" data-bs-toggle="tab">Order
                                Description</a></li>
                        <li><a href="#tab7" data-bs-toggle="tab">Order Attachments</a></li>
                        <li><a href="#tab9" data-bs-toggle="tab">Order Submission</a></li>
                        @if (!empty($Research_Order->revision))
                            <li><a href="#tab10" data-bs-toggle="tab">Order Revisions</a></li>
                        @endif
                        @if (!empty($Research_Order->submission_info->F_DeadLine) || !empty($Research_Order->submission_info->S_DeadLine) || !empty($Research_Order->submission_info->T_DeadLine))

                            <li><a href="#tab12" data-bs-toggle="tab">Draft Submission</a></li>
                        @endif
                    </ul>
                </div>
            </div>
            <div class="panel-body tabs-menu-body hremp-tabs1 p-0">
                <div class="tab-content">
                    <div class="tab-pane active" id="tab5">
                        <div class="card-body">
                            {!! $Research_Order->order_desc->Description !!}
                        </div>
                    </div>
                    <div class="tab-pane" id="tab7">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table
                                    class="table text-center table-vcenter text-nowrap table-bordered border-bottom"
                                    id="files-tables">
                                    <thead>
                                        <tr>
                                            <th class="border-bottom-0 text-center w-5">No</th>
                                            <th class="border-bottom-0">File Name</th>
                                            <th class="border-bottom-0">Download File</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($Research_Order->attachments as $attachment)
                                            <tr>
                                                <td class="text-center">{{ $loop->iteration }}</td>
                                                <td>
                                                    <a href="{{ asset($attachment->order_attachment_path) }}"
                                                        target="_blank" download
                                                        class="font-weight-semibold fs-14 mt-5">{{ $attachment->File_Name }}
                                                        <span class="text-muted ms-2">(23 KB)</span></a>
                                                    <div class="clearfix"></div>
                                                    <small class="text-muted">{{ $attachment->created_at }}</small>
                                                </td>

                                                <td>
                                                    <div class="d-flex justify-content-center">
                                                        <a href="{{ asset($attachment->order_attachment_path) }}"
                                                            class="action-btns1" data-bs-toggle="tooltip" download
                                                            data-bs-placement="top" title="Download"
                                                            target="_blank"><i
                                                                class="feather feather-download   text-success"></i></a>
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="3">Files are Not Attached</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane" id="tab9">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table
                                    class="table text-center table-vcenter text-nowrap table-bordered border-bottom"
                                    id="files-tables">
                                    <thead>
                                        <tr>
                                            <th class="border-bottom-0 text-center w-5">No</th>
                                            <th class="border-bottom-0">File Name</th>
                                            <th class="border-bottom-0">Download File</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($Research_Order->final_submission as $submission)
                                            <tr>
                                                <td class="text-center">{{ $loop->iteration }}</td>
                                                <td>
                                                    <a href="{{ asset($submission->final_submission_path) }}"
                                                        target="_blank" download
                                                        class="font-weight-semibold fs-14 mt-5">{{ $submission->File_Name }}
                                                        <span class="text-muted ms-2">(23 KB)</span></a>
                                                    <div class="clearfix"></div>
                                                    <small class="text-muted">{{ $submission->created_at }}</small>
                                                </td>

                                                <td>
                                                    <div class="d-flex justify-content-center">
                                                        <a href="{{ asset($submission->final_submission_path) }}"
                                                            class="action-btns1" data-bs-toggle="tooltip" download
                                                            data-bs-placement="top" title="Download"
                                                            target="_blank"><i
                                                                class="feather feather-download   text-success"></i></a>
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="3">Files are Not Attached</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    @if (!empty($Research_Order->revision))
                        <div class="tab-pane" id="tab10">
                           
                                 <div class="card-body">
                                <div class="d-flex justify-content-end">
                                    
                                </div>
                                <div class="table-responsive">
                                    <table
                                        class="table text-center table-vcenter text-nowrap table-bordered border-bottom"
                                        id="files-tables">
                                        <thead>
                                            <tr>
                                                <th class="border-bottom-0 text-center w-5">No</th>
                                                <th class="border-bottom-0">Revised By</th>
                                                <th class="border-bottom-0">Date</th>
                                                <th class="border-bottom-0">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>                                       
                                             @foreach ($Research_Order->revision as $revision)
                                                    <tr>
                                                        <td>{{ $loop->iteration}}</td>
                                                        <td>{{ $revision->revision_by->basic_info->F_Name . ' ' . $revision->revision_by->basic_info->L_Name }}</td>
                                                        <td>{{$revision->created_at}}</td>
                                                        <td>
                                                        <div class="btn-list">
                                                            <div class="dropdown">
                                                                <button class="btn btn-info dropdown-toggle px-5" data-bs-toggle="dropdown">
                                                                    <i class="fe fe-activity me-2"></i>Actions
                                                                </button>
                                                                <div class="dropdown-menu">
                                                                    <a class="dropdown-item Order-Revision" id="Revision_ID" data-id="{{ $revision->id }}" href="JavaScript:void(0);" data-bs-toggle="modal"
                                                                        data-bs-target="#ViewRevision">View Revision</a>

                                                                </div>
                                                            </div>
                                                        </div>
                                                        </td>
                                                    </tr>                                               
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                           
                        </div>
                    @endif
                    @if (!empty($Research_Order->submission_info->F_DeadLine) || !empty($Research_Order->submission_info->S_DeadLine) || !empty($Research_Order->submission_info->T_DeadLine))
                        <div class="tab-pane" id="tab12">
                            <div class="card-body">
                                <div class="d-flex justify-content-end">
                                   
                                </div>
                                <div class="table-responsive">
                                    <table
                                        class="table text-center table-vcenter text-nowrap table-bordered border-bottom"
                                        id="files-tables">
                                        <thead>
                                            <tr>
                                                <th class="border-bottom-0 text-center w-5">No</th>
                                                <th class="border-bottom-0">File Name</th>
                                                <th class="border-bottom-0">Upload By</th>
                                                <th class="border-bottom-0">Download File</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        @php                        
                                         $c =1;
                                        @endphp
                                            @foreach ($draft_submission as $submission)
                                                @forelse ($submission->attachments as $attachment)
                                                    <tr>
                                                        <td class="text-center">{{ $c }}</td>
                                                        <td>
                                                            <a href="{{ asset($attachment->File_Path) }}"
                                                                target="_blank" download
                                                                class="font-weight-semibold fs-14 mt-5">
                                                                {{ $attachment->File_Name }}
                                                            </a>
                                                            @if ($submission->draft_number === 1)
                                                                <div class="">First Draft</div>
                                                            @elseif($submission->draft_number === 2)
                                                                <div class="">Second Draft</div>
                                                            @elseif($submission->draft_number === 3)
                                                                <div class="">Third Draft</div>
                                                            @else
                                                                <div class="">Unknown Draft</div>
                                                            @endif
                                                            <div>{{ $submission->created_at }}</div>
                                                        </td>
                                                        <td>{{ $submission->submittedByUser->basic_info->F_Name }}
                                                        </td>
                                                        <td>
                                                            <div class="d-flex justify-content-center">
                                                                <a href="{{ asset($attachment->File_Path) }}"
                                                                    class="action-btns1" data-bs-toggle="tooltip"
                                                                    download="" data-bs-placement="top"
                                                                    title="" target="_blank"
                                                                    data-bs-original-title="Download"
                                                                    aria-label="Download">
                                                                    <i
                                                                        class="feather feather-download text-success"></i>
                                                                </a>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                     @php                        
                                                        $c =1;
                                                    @endphp
                                                @empty
                                                    <tr>
                                                        <td colspan="3">No Files Attached</td>
                                                    </tr>
                                                @endforelse
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
            <div class="chatbox mt-lg-5" id="chatmodel">
                <div class="chat border-0">
                    <div class="card overflow-hidden mb-0 border-0">
                        <!-- action-header -->
                        <div class="card-header">
                            <div class="float-start hidden-xs d-flex ms-2">
                                <div class="img_cont me-3">
                                    <img src="{{ !empty($auth_user->basic_info->profile_photo_path) ? asset($auth_user->basic_info->profile_photo_path) : asset('assets/images/users/16.jpg') }}"
                                        class="rounded-circle user_img avatar avatar-md" alt="img">
                                </div>
                                <div class="align-items-center mt-2 text-black">
                                    <h5 class="mb-0">{{ $auth_user->basic_info->full_name }}</h5>
                                    <span class="w-2 h-2 brround bg-success d-inline-block"></span><span
                                        class="ms-2 fs-12">Online</span>
                                </div>
                            </div>
                        </div>
                        <!-- action-header end -->
                        <!-- msg_card_body -->
                        <div class="card-body msg_card_body" id="Order-Messages">

                        </div>
                        <!-- msg_card_body end -->
                        <!-- card-footer -->
                        <div class="card-footer">
                            <form data-action="{{ route('Post.Message') }}" method="POST"
                                enctype="multipart/form-data" id="Chat-Form">
                                @csrf
                                <input type="hidden" name="order_id" value="{{ $Research_Order->id }}">
                                <input type="hidden" name="user_id"
                                    value="{{ Auth::guard('Authorized')->user()->id }}">
                                <div class="msb-reply-button d-flex mb-3">
                                    <div class="input-group">
                                        <input type="text" class="form-control bg-white"
                                            placeholder="Typing...." name="Chat_Message" required>
                                        <div class="input-group-append ">
                                            <button type="submit" class="btn btn-primary ">
                                                <span class="feather feather-send"></span> Send
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <div class="msb-reply-button d-flex">
                                    <div class="input-group">
                                        <input type="file" class="form-control bg-white"
                                            placeholder="Any Attachments" name="files[]" multiple>
                                    </div>
                                </div>
                            </form>
                        </div><!-- card-footer end -->
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-12 col-lg-12">
            <div class="card">
                <div class="card-header  border-0">
                    <div class="card-title">Order Details</div>
                </div>
                <div class="card-body pt-2 ps-3 pr-3">
                    <div class="table-responsive">
                        <table class="table">
                            <tbody>
                                <tr>
                                    <td>
                                        <span class="w-50">Order ID </span>
                                    </td>
                                    <td>:</td>
                                    <td>
                                        <span class="font-weight-semibold">{{ $Research_Order->Order_ID }}</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <span class="w-50">Education</span>
                                    </td>
                                    <td>:</td>
                                    <td>
                                        <span
                                            class="font-weight-semibold">{{ $Research_Order->basic_info->Education_Level }}</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <span class="w-50">Pages</span>
                                    </td>
                                    <td>:</td>
                                    <td>
                                        <span
                                            class="font-weight-semibold">{{ $Research_Order->basic_info->Pages_Count }}</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <span class="w-50">Words</span>
                                    </td>
                                    <td>:</td>
                                    <td>
                                        <span
                                            class="font-weight-semibold">{{ $Research_Order->basic_info->Word_Count }}</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <span class="w-50">Spacing</span>
                                    </td>
                                    <td>:</td>
                                    <td>
                                        <span
                                            class="font-weight-semibold">{{ $Research_Order->basic_info->Spacing }}</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <span class="w-50">Citation Style</span>
                                    </td>
                                    <td>:</td>
                                    <td>
                                        <span
                                            class="font-weight-semibold">{{ $Research_Order->basic_info->Citation_Style }}</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <span class="w-50">Sources Needed</span>
                                    </td>
                                    <td>:</td>
                                    <td>
                                        <span
                                            class="font-weight-semibold">{{ $Research_Order->basic_info->Sources }}</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <span class="w-50">Website</span>
                                    </td>
                                    <td>:</td>
                                    <td>
                                        <span
                                            class="font-weight-semibold">{{ $Research_Order->basic_info->Order_Website }}</span>
                                    </td>
                                </tr>
                                @if (!empty($Research_Order->reference_info->Reference_Code))
                                    <tr>
                                        <td>
                                            <span class="w-50">Reference</span>
                                        </td>
                                        <td>:</td>
                                        <td>
                                            <span
                                                class="font-weight-semibold">{{ $Research_Order->reference_info->Reference_Code }}</span>
                                        </td>
                                    </tr>
                                @endif
                                 @if (!empty($Research_Order->submission_info->F_DeadLine))
                                    <tr>
                                        <td>
                                            <span class="w-50">First Draft</span>
                                        </td>
                                        <td>:</td>
                                        <td>
                                            <span
                                                class="font-weight-semibold text-danger">{{ $Research_Order->submission_info->F_DeadLine }}</span>
                                        </td>
                                    </tr>
                                @endif
                                @if (!empty($Research_Order->submission_info->S_DeadLine))
                                    <tr>
                                        <td>
                                            <span class="w-50">Second Draft</span>
                                        </td>
                                        <td>:</td>
                                        <td>
                                            <span
                                                class="font-weight-semibold text-danger">{{ $Research_Order->submission_info->S_DeadLine }}</span>
                                        </td>
                                    </tr>
                                @endif
                                @if (!empty($Research_Order->submission_info->T_DeadLine))
                                    <tr>
                                        <td>
                                            <span class="w-50">Third Draft</span>
                                        </td>
                                        <td>:</td>
                                        <td>
                                            <span
                                                class="font-weight-semibold text-danger">{{ $Research_Order->submission_info->T_DeadLine }}</span>
                                        </td>
                                    </tr>
                                @endif

                                <tr>
                                    <td>
                                        <span class="w-50">Final Deadline</span>
                                    </td>
                                    <td>:</td>
                                    <td>
                                        <span
                                            class="font-weight-semibold text-danger">{{ $Research_Order->submission_info->DeadLine }}</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <span class="w-50">Status</span>
                                    </td>
                                    <td>:</td>
                                    <td>
                                        <span
                                            class="font-weight-semibold text-info">{{ $Research_Order->basic_info->Order_Status }}</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <span class="w-50">Created Date</span>
                                    </td>
                                    <td>:</td>
                                    <td>
                                        <span
                                            class="font-weight-semibold">{{ date('F d, Y', strtotime($Research_Order->created_at)) }}</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <span class="w-50">Created Time</span>
                                    </td>
                                    <td>:</td>
                                    <td>
                                        <span
                                            class="font-weight-semibold">{{ date('H:i:s A', strtotime($Research_Order->created_at)) }}</span>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
            <div class="card">
                <div class="card-header border-0">
                    <div class="card-title">Client Information</div>
                </div>
                <div class="card-body pt-2 ps-3 pr-3">
                    <div class="table-responsive">
                        <table class="table">
                            <tbody>
                                <tr>
                                    <td>
                                        <span class="w-50">Name</span>
                                    </td>
                                    <td>:</td>
                                    <td>
                                        <span
                                            class="font-weight-semibold">{{ $Research_Order->client_info->Client_Name }}</span>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
            <div class="card">
                <div class="card-header  border-0">
                    <div class="card-title">Sales Department</div>
                </div>
                <div class="card-body pt-2 ps-3 pr-3">
                    <div class="table-responsive">
                        <table class="table">
                            <tbody>
                                <tr>
                                    <td>
                                        <span class="w-50">Create By</span>
                                    </td>
                                    <td>:</td>
                                    <td>
                                        <span
                                            class="font-weight-semibold">{{ $Research_Order->authorized_user->basic_info->full_name }}</span>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endif

{{-- Manager View --}}
@if ((int) $auth_user->Role_ID === 4)
    <!--Page header-->
    <div class="page-header d-xl-flex d-block">
        <div class="page-rightheader ms-md-auto">
            @if ($Research_Order->basic_info->Order_Status !== 'Completed')
                <div class="d-flex align-items-end flex-wrap my-auto end-content breadcrumb-end">
                    <div class="btn-list">
                        <div class="dropdown">
                            <button class="btn btn-info dropdown-toggle px-5" data-bs-toggle="dropdown">
                                <i class="fe fe-activity me-2"></i>Actions
                            </button>
                            <div class="dropdown-menu">
                                <a class="dropdown-item" href="javascript:void(0)" data-bs-toggle="modal"
                                    data-bs-target="#AssignModal">Assign Order</a>
                                <a class="dropdown-item" href="javascript:void(0)" data-bs-toggle="modal"
                                    data-bs-target="#NewTaskModal">New Task</a>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
    <!--End Page header-->
    <!-- Row -->
    <div class="row">
        <div class="col-xl-9 col-md-12 col-lg-12">
            <div class="tab-menu-heading hremp-tabs p-0 ">
                <div class="tabs-menu1">
                    <!-- Tabs -->
                    <ul class="nav panel-tabs">
                        <li class="ms-4"><a href="#tab5" class="active" data-bs-toggle="tab">Order
                                Description</a></li>
                        <li><a href="#tab7" data-bs-toggle="tab">Order Attachments</a></li>
                        <li><a href="#tab8" data-bs-toggle="tab"> Assign Task</a></li>
                        <li><a href="#tab9" data-bs-toggle="tab">Writer Submission</a></li>
                        <li><a href="#tab10" data-bs-toggle="tab">Final Submission</a></li>
                        @if (!empty($Research_Order->revision))
                            <li><a href="#tab11" data-bs-toggle="tab">Order Revisions</a></li>
                        @endif
                        @if (!empty($Research_Order->submission_info->F_DeadLine) || !empty($Research_Order->submission_info->S_DeadLine) || !empty($Research_Order->submission_info->T_DeadLine))
                            <li><a href="#tab12" data-bs-toggle="tab">Draft Submission</a></li>
                        @endif

                    </ul>
                </div>
            </div>
            <div class="panel-body tabs-menu-body hremp-tabs1 p-0">
                <div class="tab-content">
                    <div class="tab-pane active" id="tab5">
                        <div class="card-body">
                            {!! $Research_Order->order_desc->Description !!}
                        </div>
                    </div>
                    <div class="tab-pane" id="tab7">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table
                                    class="table text-center table-vcenter text-nowrap table-bordered border-bottom"
                                    id="files-tables">
                                    <thead>
                                        <tr>
                                            <th class="border-bottom-0 text-center w-5">No</th>
                                            <th class="border-bottom-0">File Name</th>
                                            <th class="border-bottom-0">Download File</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($Research_Order->attachments as $attachment)
                                            <tr>
                                                <td class="text-center">{{ $loop->iteration }}</td>
                                                <td>
                                                    <a href="{{ asset($attachment->order_attachment_path) }}"
                                                        target="_blank" download
                                                        class="font-weight-semibold fs-14 mt-5">{{ $attachment->File_Name }}
                                                        <span class="text-muted ms-2">(23 KB)</span></a>
                                                    <div class="clearfix"></div>
                                                    <small class="text-muted">{{ $attachment->created_at }}</small>
                                                </td>

                                                <td>
                                                    <div class="d-flex justify-content-center">
                                                        <a href="{{ asset($attachment->order_attachment_path) }}"
                                                            class="action-btns1" data-bs-toggle="tooltip" download
                                                            data-bs-placement="top" title="Download"
                                                            target="_blank"><i
                                                                class="feather feather-download   text-success"></i></a>
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="3">Files are Not Attached</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane" id="tab8">
                        <div class="card-body">
                            <div class="table-responsive" style="min-height: 30vh;">

                                <table
                                    class="table text-center table-vcenter text-nowrap table-bordered border-bottom"
                                    id="responsive-datatable">
                                    <thead>
                                        <tr>
                                            <th class="border-bottom-0 text-center">Task</th>
                                            <th class="border-bottom-0">Writer</th>
                                            <th class="border-bottom-0">Assign Date</th>
                                            <th class="border-bottom-0">Assign Words</th>
                                            <th class="border-bottom-0">Remaining Words</th>
                                            <th class="border-bottom-0">Deadline</th>
                                            <th class="border-bottom-0">Status</th>
                                            <th class="border-bottom-0">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($Research_Order->tasks as $Task)
                                            <tr>
                                                <td class="font-weight-bold">Task-{{ $loop->iteration }}</td>
                                                <td>
                                                    {{ $Task->assign->basic_info->full_name }}
                                                </td>
                                                <td>
                                                    {{ $Task->created_at }}
                                                </td>
                                                <td>
                                                    {{ $Task->Assign_Words }}
                                                </td>
                                                <td>
                                                    {{ $Task->Due_Words }}
                                                </td>
                                                <td>
                                                    {{ $Task->DeadLine }} &nbsp; {{ $Task->DeadLine_Time }}
                                                </td>
                                                <td>
                                                    {{ $Task->Task_Status }}
                                                </td>
                                                <td>
                                                    <div class="btn-list">
                                                        <div class="dropdown">
                                                            <button class="btn btn-info dropdown-toggle px-5"
                                                                data-bs-toggle="dropdown">
                                                                <i class="fe fe-activity me-2"></i>Actions
                                                            </button>
                                                            <div class="dropdown-menu">
                                                                <a class="dropdown-item view-assign-task"
                                                                    href="javascript:void(0)" data-bs-toggle="modal"
                                                                    data-bs-target="#ViewTaskModal"
                                                                    data-id="{{ $Task->id }}">View Detail</a>
                                                                <a class="dropdown-item Task_ID"
                                                                    href="javascript:void(0)" data-bs-toggle="modal"
                                                                    data-bs-target="#ChangedWriter">
                                                                    <input type="hidden" id="Task_ID"
                                                                        value="{{ $Task->id }}">Change
                                                                    Writer</a>
                                                                <a class="dropdown-item Edit-Task Task_ID"
                                                                    href="javascript:void(0)" data-bs-toggle="modal"
                                                                    data-bs-target="#EditTaskModal">
                                                                    <input type="hidden" id="Task_ID"
                                                                        value="{{ $Task->id }}">
                                                                    Edit Task</a>
                                                                <a class="dropdown-item Delete_Task"
                                                                    href="{{ route('Delete.Task', ['Task_ID' => $Task->id]) }}">
                                                                    Delete Task
                                                                </a>
                                                                <a class="dropdown-item Add-Revision Task_ID"
                                                                    href="javascript:void(0)" data-bs-toggle="modal"
                                                                    data-bs-target="#TaskRevisionModal">
                                                                    <input type="hidden" id="Task_ID"
                                                                        value="{{ $Task->id }}">
                                                                    Add Revision</a>
                                                                @if (count($Task->revision) > 0)
                                                                    <a class="dropdown-item View-Revision"
                                                                        href="javascript:void(0)"
                                                                        data-bs-toggle="modal"
                                                                        data-bs-target="#ViewRevisionModal">View
                                                                        Revision
                                                                        <input type="hidden" id="Task_ID"
                                                                            value="{{ $Task->id }}"></a>
                                                                @endif
                                                                <a class="dropdown-item Task_ID"
                                                                    href="javascript:void(0)" data-bs-toggle="modal"
                                                                    data-bs-target="#TaskCancelWordModal">
                                                                    <input type="hidden" id="Task_ID"
                                                                        value="{{ $Task->id }}">
                                                                    Cancel Words</a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                            @php
                                                if ($Task->Task_Status !== 'Canceled') {
                                                    $assign += (float) Str::replace(['$ ', ','], '', $Task->Assign_Words);
                                                    $wordcount = (float) Str::replace(['$', ','], '', $Research_Order->basic_info->Word_Count);
                                                    $total_words = $wordcount - $assign;
                                                }
                                            @endphp
                                        @empty
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane" id="tab9">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table
                                    class="table text-center table-vcenter text-nowrap table-bordered border-bottom"
                                    id="files-tables">
                                    <thead>
                                        <tr>
                                            <th class="border-bottom-0 text-center w-5">No</th>
                                            <th class="border-bottom-0">File Name</th>
                                            <th class="border-bottom-0">Download File</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($Research_Order->tasks as $task)
                                            @forelse($task->submit_info as $submission)
                                                <tr>
                                                    <td class="text-center">{{ $loop->iteration }}</td>
                                                    <td>
                                                        <a href="{{ asset($submission->task_file_path) }}"
                                                            target="_blank" download
                                                            class="font-weight-semibold fs-14 mt-5">{{ $submission->File_Name }}
                                                            <span class="text-muted ms-2">(23 KB)</span></a>
                                                        <div class="clearfix"></div>
                                                        <small class="text-muted">{{ $submission->created_at }} -
                                                            Submitted
                                                            By
                                                            {{ $submission->submitted->basic_info->full_name }}</small>
                                                    </td>

                                                    <td>
                                                        <div class="d-flex justify-content-center">
                                                            <a href="{{ asset($submission->task_file_path) }}"
                                                                class="action-btns1" data-bs-toggle="tooltip"
                                                                data-bs-placement="top" title="Download" download
                                                                target="_blank"><i
                                                                    class="feather feather-download   text-success"></i></a>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @empty
                                            @endforelse
                                        @empty
                                            <tr>
                                                <td colspan="3">Files are Not Attached</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane" id="tab10">
                        <div class="card-body">
                            <div class="d-flex justify-content-end">
                                <button type="button" class="btn btn-danger mb-4" data-bs-toggle="modal"
                                    data-bs-target="#FinalSubmissionModal"> Upload Files
                                </button>
                            </div>
                            <div class="table-responsive">
                                <table
                                    class="table text-center table-vcenter text-nowrap table-bordered border-bottom"
                                    id="files-tables">
                                    <thead>
                                        <tr>
                                            <th class="border-bottom-0 text-center w-5">No</th>
                                            <th class="border-bottom-0">File Name</th>
                                            <th class="border-bottom-0">Download File</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($Research_Order->final_submission as $submission)
                                            <tr>
                                                <td class="text-center">{{ $loop->iteration }}</td>
                                                <td>
                                                    <a href="{{ asset($submission->final_submission_path) }}"
                                                        target="_blank" download
                                                        class="font-weight-semibold fs-14 mt-5">{{ $submission->File_Name }}
                                                        <span class="text-muted ms-2">(23 KB)</span></a>
                                                    <div class="clearfix"></div>
                                                    <small class="text-muted">{{ $submission->created_at }}</small>
                                                </td>

                                                <td>
                                                    <div class="d-flex justify-content-center">
                                                        <a href="{{ asset($submission->final_submission_path) }}"
                                                            class="action-btns1" data-bs-toggle="tooltip" download
                                                            data-bs-placement="top" title="Download"
                                                            target="_blank"><i
                                                                class="feather feather-download   text-success"></i></a>
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="3">Files are Not Attached</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    @if (!empty($Research_Order->revision))
                        <div class="tab-pane" id="tab11">                           
                                 <div class="card-body">
                                 <div class="d-flex justify-content-end">
                                   
                                </div>
                                <div class="table-responsive">
                                    <table
                                        class="table text-center table-vcenter text-nowrap table-bordered border-bottom"
                                        id="files-tables">
                                        <thead>
                                            <tr>
                                                <th class="border-bottom-0 text-center w-5">No</th>
                                                <th class="border-bottom-0">Revised By</th>
                                                <th class="border-bottom-0">Date</th>
                                                <th class="border-bottom-0">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>                                       
                                             @foreach ($Research_Order->revision as $revision)
                                                    <tr>
                                                        <td>{{ $loop->iteration}}</td>
                                                        <td>{{ $revision->revision_by->basic_info->F_Name . ' ' . $revision->revision_by->basic_info->L_Name }}</td>
                                                        <td>{{$revision->created_at}}</td>
                                                        <td>
                                                        <div class="btn-list">
                                                            <div class="dropdown">
                                                                <button class="btn btn-info dropdown-toggle px-5" data-bs-toggle="dropdown">
                                                                    <i class="fe fe-activity me-2"></i>Actions
                                                                </button>
                                                                <div class="dropdown-menu">
                                                                    <a class="dropdown-item Order-Revision" id="Revision_ID" data-id="{{ $revision->id }}" href="JavaScript:void(0);" data-bs-toggle="modal"
                                                                        data-bs-target="#ViewRevision">View Revision</a>
                                                                     <a class="dropdown-item Order-Revision" id="Upload_Revision_ID" data-id="{{ $revision->id }}" href="JavaScript:void(0);" data-bs-toggle="modal"
                                                                        data-bs-target="#SubmitRevision">Upload Revision</a>

                                                                </div>
                                                            </div>
                                                        </div>
                                                        </td>
                                                    </tr>                                               
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                           
                        </div>
                    @endif
                    @if (!empty($Research_Order->submission_info->F_DeadLine) || !empty($Research_Order->submission_info->S_DeadLine) || !empty($Research_Order->submission_info->T_DeadLine))
                        <div class="tab-pane" id="tab12">
                            <div class="card-body">
                                <div class="d-flex justify-content-end">
                                    <button type="button" class="btn btn-danger mb-4" data-bs-toggle="modal"
                                        data-bs-target="#uploaddraft"> Upload Draft
                                    </button>
                                </div>
                                <div class="table-responsive">
                                    <table
                                        class="table text-center table-vcenter text-nowrap table-bordered border-bottom"
                                        id="files-tables">
                                        <thead>
                                            <tr>
                                                <th class="border-bottom-0 text-center w-5">No</th>
                                                <th class="border-bottom-0">File Name</th>
                                                <th class="border-bottom-0">Upload By</th>
                                                <th class="border-bottom-0">Download File</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        @php
                                            
                                            $d =1;
                                        @endphp
                                            @foreach ($draft_submission as $submission)
                                                @forelse ($submission->attachments as $attachment)
                                                    <tr>
                                                        <td class="text-center">{{ $d }}</td>
                                                        <td>
                                                            <a href="{{ asset($attachment->File_Path) }}"
                                                                target="_blank" download
                                                                class="font-weight-semibold fs-14 mt-5">
                                                                {{ $attachment->File_Name }}
                                                            </a>
                                                            @if ($submission->draft_number === 1)
                                                                <div class="">First Draft</div>
                                                            @elseif($submission->draft_number === 2)
                                                                <div class="">Second Draft</div>
                                                            @elseif($submission->draft_number === 3)
                                                                <div class="">Third Draft</div>
                                                            @else
                                                                <div class="">Unknown Draft</div>
                                                            @endif
                                                            <div>{{ $submission->created_at }}</div>
                                                        </td>
                                                        <td>{{ $submission->submittedByUser->basic_info->F_Name }}
                                                        </td>
                                                        <td>
                                                            <div class="d-flex justify-content-center">
                                                                <a href="{{ asset($attachment->File_Path) }}"
                                                                    class="action-btns1" data-bs-toggle="tooltip"
                                                                    download="" data-bs-placement="top"
                                                                    title="" target="_blank"
                                                                    data-bs-original-title="Download"
                                                                    aria-label="Download">
                                                                    <i class="feather feather-download text-success"></i>
                                                                </a>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    @php
                                                        $d++;
                                                    @endphp
                                                @empty
                                                    <tr>
                                                        <td colspan="3">No Files Attached</td>
                                                    </tr>
                                                @endforelse
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
            <div class="chatbox mt-lg-5" id="chatmodel">
                <div class="chat border-0">
                    <div class="card overflow-hidden mb-0 border-0">
                        <!-- action-header -->
                        <div class="card-header">
                            <div class="float-start hidden-xs d-flex ms-2">
                                <div class="img_cont me-3">
                                    <img src="{{ !empty($auth_user->basic_info->profile_photo_path) ? asset($auth_user->basic_info->profile_photo_path) : asset('assets/images/users/16.jpg') }}"
                                        class="rounded-circle user_img avatar avatar-md" alt="img">
                                </div>
                                <div class="align-items-center mt-2 text-black">
                                    <h5 class="mb-0">{{ $auth_user->basic_info->full_name }}</h5>
                                    <span class="w-2 h-2 brround bg-success d-inline-block"></span><span
                                        class="ms-2 fs-12">Online</span>
                                </div>
                            </div>
                        </div>
                        <!-- action-header end -->
                        <!-- msg_card_body -->
                        <div class="card-body msg_card_body" id="Order-Messages">

                        </div>
                        <!-- msg_card_body end -->
                        <!-- card-footer -->
                        <div class="card-footer">
                            <form data-action="{{ route('Post.Message') }}" method="POST"
                                enctype="multipart/form-data" id="Chat-Form">
                                @csrf
                                <input type="hidden" name="order_id" value="{{ $Research_Order->id }}">
                                <input type="hidden" name="user_id"
                                    value="{{ Auth::guard('Authorized')->user()->id }}">
                                <div class="msb-reply-button d-flex mb-3">
                                    <div class="input-group">
                                        <input type="text" class="form-control bg-white"
                                            placeholder="Typing...." name="Chat_Message" required>
                                        <div class="input-group-append ">
                                            <button type="submit" class="btn btn-primary ">
                                                <span class="feather feather-send"></span> Send
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <div class="msb-reply-button d-flex">
                                    <div class="input-group">
                                        <input type="file" class="form-control bg-white"
                                            placeholder="Any Attachments" name="files[]" multiple>
                                    </div>
                                </div>
                            </form>
                        </div><!-- card-footer end -->
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-12 col-lg-12">
            <div class="card">
                <div class="card-header  border-0">
                    <div class="card-title">Order Details</div>
                </div>
                <div class="card-body pt-2 ps-3 pr-3">
                    <div class="table-responsive">
                        <table class="table">
                            <tbody>
                                <tr>
                                    <td>
                                        <span class="w-50">Order ID </span>
                                    </td>
                                    <td>:</td>
                                    <td>
                                        <span class="font-weight-semibold">{{ $Research_Order->Order_ID }}</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <span class="w-50">Education</span>
                                    </td>
                                    <td>:</td>
                                    <td>
                                        <span
                                            class="font-weight-semibold">{{ $Research_Order->basic_info->Education_Level }}</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <span class="w-50">Pages</span>
                                    </td>
                                    <td>:</td>
                                    <td>
                                        <span
                                            class="font-weight-semibold">{{ $Research_Order->basic_info->Pages_Count }}</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <span class="w-50">Words</span>
                                    </td>
                                    <td>:</td>
                                    <td>
                                        <span
                                            class="font-weight-semibold">{{ $Research_Order->basic_info->Word_Count }}</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <span class="w-50">Spacing</span>
                                    </td>
                                    <td>:</td>
                                    <td>
                                        <span
                                            class="font-weight-semibold">{{ $Research_Order->basic_info->Spacing }}</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <span class="w-50">Citation Style</span>
                                    </td>
                                    <td>:</td>
                                    <td>
                                        <span
                                            class="font-weight-semibold">{{ $Research_Order->basic_info->Citation_Style }}</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <span class="w-50">Sources Needed</span>
                                    </td>
                                    <td>:</td>
                                    <td>
                                        <span
                                            class="font-weight-semibold">{{ $Research_Order->basic_info->Sources }}</span>
                                    </td>
                                </tr>
                                 @if (!empty($Research_Order->submission_info->F_DeadLine))
                                    <tr>
                                        <td>
                                            <span class="w-50">First Draft</span>
                                        </td>
                                        <td>:</td>
                                        <td>
                                            <span
                                                class="font-weight-semibold text-danger">{{ $Research_Order->submission_info->F_DeadLine }}</span>
                                        </td>
                                    </tr>
                                @endif
                                @if (!empty($Research_Order->submission_info->S_DeadLine))
                                    <tr>
                                        <td>
                                            <span class="w-50">Second Draft</span>
                                        </td>
                                        <td>:</td>
                                        <td>
                                            <span
                                                class="font-weight-semibold text-danger">{{ $Research_Order->submission_info->S_DeadLine }}</span>
                                        </td>
                                    </tr>
                                @endif
                                @if (!empty($Research_Order->submission_info->T_DeadLine))
                                    <tr>
                                        <td>
                                            <span class="w-50">Third Draft</span>
                                        </td>
                                        <td>:</td>
                                        <td>
                                            <span
                                                class="font-weight-semibold text-danger">{{ $Research_Order->submission_info->T_DeadLine }}</span>
                                        </td>
                                    </tr>
                                @endif
                                <tr>
                                    <td>
                                        <span class="w-50">Final Deadline</span>
                                    </td>
                                    <td>:</td>
                                    <td>
                                        <span
                                            class="font-weight-semibold text-danger">{{ $Research_Order->submission_info->DeadLine }}</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <span class="w-50">Status</span>
                                    </td>
                                    <td>:</td>
                                    <td>
                                        <span
                                            class="font-weight-semibold text-info">{{ $Research_Order->basic_info->Order_Status }}</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <span class="w-50">Created Date</span>
                                    </td>
                                    <td>:</td>
                                    <td>
                                        <span
                                            class="font-weight-semibold">{{ date('F d, Y', strtotime($Research_Order->created_at)) }}</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <span class="w-50">Created Time</span>
                                    </td>
                                    <td>:</td>
                                    <td>
                                        <span
                                            class="font-weight-semibold">{{ date('H:i:s A', strtotime($Research_Order->created_at)) }}</span>
                                    </td>
                                </tr>

                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
            <div class="card">
                <div class="card-header  border-0">
                    <div class="card-title">Research Department</div>
                </div>
                <div class="card-body pt-2 ps-3 pr-3">
                    <div class="table-responsive">
                        <table class="table">
                            <tbody>
                                @if (!empty($Research_Order->assign))
                                    @foreach ($Research_Order->assign as $User)
                                        <tr>
                                            <td>
                                                <span class="w-50">Coordinator {{ $loop->iteration }}</span>
                                            </td>
                                            <td>:</td>
                                            <td>
                                                <span
                                                    class="font-weight-semibold">{{ $User->basic_info->full_name }}</span>
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
                                @if (count($Research_Order->tasks) > 0)
                                    @foreach ($Research_Order->tasks as $task)
                                        <tr>
                                            <td>
                                                <span class="w-50">Executive {{ $loop->iteration }}</span>
                                            </td>
                                            <td>:</td>
                                            <td>
                                                <span
                                                    class="font-weight-semibold">{{ $task->assign->basic_info->full_name }}</span>
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>
   
    <!-- New Task Modal -->
    <div class="modal fade" id="NewTaskModal">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <form action="{{ route('New.Task') }}" method="POST" class="needs-validation was-validated">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Make Order New Task</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <input type="hidden" name="order_id" value="{{ $Research_Order->id }}">
                            <input type="hidden" name="assign_by"
                                value="{{ Auth::guard('Authorized')->user()->id }}">
                            <input type="hidden" name="total_words" id="Total_Words"
                                value="{{ $total_words !== 0 ? $total_words : (float) Str::replace(['$ ', ','], '', $Research_Order->basic_info->Word_Count) }}">
                            <input type="hidden" name="Order_ID" value="{{ $Research_Order->Order_ID }}">
                            @if ($Research_Order->assign->isEmpty())
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="form-label" for="C_Assign_ID">Coordinators</label>
                                        <select name="C_Assign_ID[]" id="C_Assign_ID" class="form-control select2"
                                            data-placeholder="Select Coordinator" multiple>
                                            <option value="">Select Coordinator</option>
                                            @forelse($Coordinators as $Coordinator)
                                                <option value="{{ $Coordinator->id }}">
                                                    {{ $Coordinator->basic_info->full_name }}</option>
                                            @empty
                                                <option value="">Select Coordinator</option>
                                            @endforelse
                                        </select>
                                    </div>
                                </div>
                            @endif
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="form-label">Select Writers</label>
                                    <select name="W_Assign_ID" class="form-control custom-select select2"
                                        data-placeholder="Select Writer" aria-required="true" required>
                                        <option value="">Select Writers</option>
                                        @forelse($Writers as $Writer)
                                            <option value="{{ $Writer->id }}">
                                                {{ $Writer->basic_info->full_name }}</option>
                                        @empty
                                        @endforelse
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">DeadLine</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                            <i class="feather feather-calendar"></i>
                                        </div>
                                    </div>
                                    <input class="form-control" placeholder="MM/DD/YYYY" name="DeadLine"
                                        type="date" min="0">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">DeadLine Time</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                            <span class="feather feather-clock"></span>
                                        </div>
                                    </div><!-- input-group-prepend -->
                                    <input class="form-control" id="tp3" placeholder="Set time"
                                        name="DeadLine_Time" type="time" required>
                                </div><!-- input-group -->
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Total Words</label>
                                    <input class="form-control mb-4 is-valid" name="Order_Words"
                                        placeholder="Enter Order Words" id="Assign_Words" min="0"
                                        type="number" value="" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Due Words</label>
                                    <input class="form-control mb-4 is-valid" name="Due_Words" readonly
                                        placeholder="Enter Due Amount" type="number" id="Rem_Word">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary btn-block">Create Task</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- Task Revision Modal -->
    <div class="modal fade" id="TaskRevisionModal">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <form action="{{ route('Task.Revision') }}" method="POST"
                    class="needs-validation was-validated" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Add Revision For Current Task</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <input type="hidden" name="order_id" value="{{ $Research_Order->id }}">
                            <input type="hidden" name="Order_ID" value="{{ $Research_Order->Order_ID }}">
                            <input type="hidden" name="task_id" class="task-id">
                            <input type="hidden" name="revised_by"
                                value="{{ Auth::guard('Authorized')->user()->id }}">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="form-label" class="form-label"></label>
                                    <textarea id="summernote" class="form-control mb-4 is-invalid state-invalid" name="Task_Revision"
                                        placeholder="Textarea (invalid state)"></textarea>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">DeadLine</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                            <i class="feather feather-calendar"></i>
                                        </div>
                                    </div>
                                    <input class="form-control  Rev-DeadLine" placeholder="MM/DD/YYYY"
                                        name="DeadLine" type="date">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">DeadLine Time</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                            <span class="feather feather-clock"></span>
                                        </div>
                                    </div><!-- input-group-prepend -->
                                    <input class="form-control Rev-Time" id="tp3" placeholder="Set time"
                                        name="DeadLine_Time" type="time" required>
                                </div><!-- input-group -->
                            </div>
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label for="form-label" class="form-label"></label>
                                    <input class="form-control" type="file" name="files[]" multiple>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary btn-block">Send Revision</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- View Revision Modal -->
    <div class="modal fade" id="ViewRevisionModal">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">View Revisions of Current Task</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body Get-Revisions">

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary btn-block" data-bs-dismiss="modal"
                        aria-label="Close">
                        Close
                    </button>
                </div>
            </div>
        </div>
    </div>
    <!-- Change Writer Modal -->
    <div class="modal fade" id="ChangedWriter">
        <div class="modal-dialog modal-md" role="document">
            <div class="modal-content">
                <form action="{{ route('Change.Writer') }}" method="POST"
                    class="needs-validation was-validated">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Change Writer on Current Task</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <input type="hidden" name="task_id" class="task-id">
                            <input type="hidden" name="Order_ID" value="{{ $Research_Order->Order_ID }}">
                            <input type="hidden" name="assign_by"
                                value="{{ Auth::guard('Authorized')->user()->id }}">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="form-label">Select Writers</label>
                                    <select name="W_Assign_ID" class="form-control custom-select select2"
                                        data-placeholder="Select Writer" aria-required="true" required>
                                        <option value="">Select Writers</option>
                                        @forelse($Writers as $Writer)
                                            <option value="{{ $Writer->id }}">
                                                {{ $Writer->basic_info->full_name }}</option>
                                        @empty
                                        @endforelse
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary btn-block">Changed Writer</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- Edit Task Modal -->
    <div class="modal fade" id="EditTaskModal">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <form action="{{ route('Edit.Task') }}" method="POST" class="needs-validation was-validated">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Update Current Task</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <input type="hidden" name="task_id" class="task-id">
                            <input type="hidden" name="order_id" value="{{ $Research_Order->id }}">
                            <input type="hidden" name="Order_ID" value="{{ $Research_Order->Order_ID }}">
                            <input type="hidden" name="assign_by"
                                value="{{ Auth::guard('Authorized')->user()->id }}">
                            <input type="hidden" class="Selected-Total-Words" name="total_words"
                                id="Edit_Total_Words">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="form-label">Select Writers</label>
                                    <select name="W_Assign_ID"
                                        class="form-control custom-select select2 Selected-Writer"
                                        data-placeholder="Select Writer" aria-required="true" required>
                                        <option value="">Select Writers</option>
                                        @forelse($Writers as $Writer)
                                            <option value="{{ $Writer->id }}">
                                                {{ $Writer->basic_info->full_name }}</option>
                                        @empty
                                        @endforelse
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">DeadLine</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                            <span class="feather feather-calendar"></span>
                                        </div>
                                    </div>
                                    <input class="form-control Selected-Date" placeholder="MM/DD/YYYY"
                                        name="DeadLine" min="0" type="date">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">DeadLine Time</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                            <span class="feather feather-clock"></span>
                                        </div>
                                    </div><!-- input-group-prepend -->
                                    <input class="form-control Selected-Time" placeholder="Set time"
                                        name="DeadLine_Time" type="time" required>
                                </div><!-- input-group -->
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Total Words</label>
                                    <input class="form-control mb-4 is-valid Selected-Words" name="Order_Words"
                                        placeholder="Enter Order Words" id="Edit_Assign_Words" min="0"
                                        type="number" value="" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Due Words</label>
                                    <input class="form-control mb-4 is-valid Selected-Due-Words" name="Due_Words"
                                        readonly placeholder="Enter Due Amount" type="number" id="Edit_Rem_Word">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary btn-block">Update Current Task</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- Final Submission Modal -->
    <div class="modal fade" id="FinalSubmissionModal">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <form action="{{ route('Research.Order.Final.Submit') }}" method="POST"
                    class="needs-validation was-validated" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Final Submission For Current Order</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <input type="hidden" name="order_id" value="{{ $Research_Order->id }}">
                            <input type="hidden" name="Order_ID" value="{{ $Research_Order->Order_ID }}">
                            <input type="hidden" name="task_id" class="task-id">
                            <input type="hidden" name="submit_by"
                                value="{{ Auth::guard('Authorized')->user()->id }}">
                            <input type="hidden" name="user_id"
                                value="{{ Auth::guard('Authorized')->user()->id }}">
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label for="form-label" class="form-label"></label>
                                    <input class="form-control" type="file" name="files[]" multiple>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary btn-block">Upload Final Submission</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- Task Cancel Words Modal -->
    <div class="modal fade" id="TaskCancelWordModal">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <form action="{{ route('Cancel.Words.Task') }}" method="POST"
                    class="needs-validation was-validated" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Are You Sure To Cancel Words of Current Task</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <input type="hidden" name="order_id" value="{{ $Research_Order->id }}">
                            <input type="hidden" name="Order_ID" value="{{ $Research_Order->Order_ID }}">
                            <input type="hidden" name="task_id" class="task-id">
                            <input type="hidden" name="cancel_by"
                                value="{{ Auth::guard('Authorized')->user()->id }}">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="form-label" class="form-label">Any Comments</label>
                                    <textarea class="form-control mb-4 is-invalid state-invalid" name="Cancellation_Comments"
                                        placeholder="Textarea (invalid state)" required></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary btn-block">Cancel Words</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endif

{{-- Coordinator View --}}
@if ((int) $auth_user->Role_ID === 5)
    <!--Page header-->
    <div class="page-header d-xl-flex d-block">
        <div class="page-rightheader ms-md-auto">
            @if ($Research_Order->basic_info->Order_Status !== 'Completed')
                <div class="d-flex align-items-end flex-wrap my-auto end-content breadcrumb-end">
                    <div class="btn-list">
                        <div class="dropdown">
                            <button class="btn btn-info dropdown-toggle px-5" data-bs-toggle="dropdown">
                                <i class="fe fe-activity me-2"></i>Actions
                            </button>
                            <div class="dropdown-menu">
                                <a class="dropdown-item" href="javascript:void(0)" data-bs-toggle="modal"
                                    data-bs-target="#NewTaskModal">Assign Task</a>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
    <!--End Page header-->
    <!-- Row -->
    <div class="row">
        <div class="col-xl-9 col-md-12 col-lg-12">
            <div class="tab-menu-heading hremp-tabs p-0 ">
                <div class="tabs-menu1">
                    <!-- Tabs -->
                    <ul class="nav panel-tabs">
                        <li class="ms-4"><a href="#tab5" class="active" data-bs-toggle="tab">Order
                                Description</a></li>
                        <li><a href="#tab7" data-bs-toggle="tab">Order Attachments</a></li>
                        <li><a href="#tab8" data-bs-toggle="tab">Assign Task</a></li>
                        <li><a href="#tab9" data-bs-toggle="tab">Writer Submission</a></li>
                        <li><a href="#tab10" data-bs-toggle="tab">Final Submission</a></li>
                        @if (!empty($Research_Order->revision))
                            <li><a href="#tab11" data-bs-toggle="tab">Order Revisions</a></li>
                        @endif
                        @if (!empty($Research_Order->submission_info->F_DeadLine) || !empty($Research_Order->submission_info->S_DeadLine) || !empty($Research_Order->submission_info->T_DeadLine))
                        <li><a href="#tab12" data-bs-toggle="tab">Draft Submission</a></li>
                        @endif
                </div>
            </div>
            <div class="panel-body tabs-menu-body hremp-tabs1 p-0">
                <div class="tab-content">
                    <div class="tab-pane active" id="tab5">
                        <div class="card-body">
                            {!! $Research_Order->order_desc->Description !!}
                        </div>
                    </div>
                    <div class="tab-pane" id="tab7">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table
                                    class="table text-center table-vcenter text-nowrap table-bordered border-bottom"
                                    id="files-tables">
                                    <thead>
                                        <tr>
                                            <th class="border-bottom-0 text-center w-5">No</th>
                                            <th class="border-bottom-0">File Name</th>
                                            <th class="border-bottom-0">Download File</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($Research_Order->attachments as $attachment)
                                            <tr>
                                                <td class="text-center">{{ $loop->iteration }}</td>
                                                <td>
                                                    <a href="{{ asset($attachment->order_attachment_path) }}"
                                                        target="_blank" download
                                                        class="font-weight-semibold fs-14 mt-5">{{ $attachment->File_Name }}
                                                        <span class="text-muted ms-2">(23 KB)</span></a>
                                                    <div class="clearfix"></div>
                                                    <small class="text-muted">{{ $attachment->created_at }}</small>
                                                </td>

                                                <td>
                                                    <div class="d-flex justify-content-center">
                                                        <a href="{{ asset($attachment->order_attachment_path) }}"
                                                            class="action-btns1" data-bs-toggle="tooltip" download
                                                            data-bs-placement="top" title="Download"
                                                            target="_blank"><i
                                                                class="feather feather-download   text-success"></i></a>
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="3">Files are Not Attached</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane" id="tab8">
                        <div class="card-body">
                            <div class="table-responsive" style="min-height: 30vh;">

                                <table
                                    class="table text-center table-vcenter text-nowrap table-bordered border-bottom"
                                    id="responsive-datatable">
                                    <thead>
                                        <tr>
                                            <th class="border-bottom-0 text-center">Task</th>
                                            <th class="border-bottom-0">Writer</th>
                                            <th class="border-bottom-0">Assign Date</th>
                                            <th class="border-bottom-0">Assign Words</th>
                                            <th class="border-bottom-0">Remaining Words</th>
                                            <th class="border-bottom-0">Deadline</th>
                                            <th class="border-bottom-0">Status</th>
                                            <th class="border-bottom-0">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($Research_Order->tasks as $Task)
                                            <tr>
                                                <td class="font-weight-bold">Task-{{ $loop->iteration }}</td>
                                                <td>
                                                    {{ $Task->assign->basic_info->full_name }}
                                                </td>
                                                <td>
                                                    {{ $Task->created_at }}
                                                </td>
                                                <td>
                                                    {{ $Task->Assign_Words }}
                                                </td>
                                                <td>
                                                    {{ $Task->Due_Words }}
                                                </td>
                                                <td>
                                                    {{ $Task->DeadLine }} &nbsp; {{ $Task->DeadLine_Time }}
                                                </td>
                                                <td>
                                                    {{ $Task->Task_Status }}
                                                </td>
                                                <td>
                                                    <div class="btn-list">
                                                        <div class="dropdown">
                                                            <button class="btn btn-info dropdown-toggle px-5"
                                                                data-bs-toggle="dropdown">
                                                                <i class="fe fe-activity me-2"></i>Actions
                                                            </button>
                                                            <div class="dropdown-menu">
                                                                <a class="dropdown-item view-assign-task"
                                                                    href="javascript:void(0)" data-bs-toggle="modal"
                                                                    data-bs-target="#ViewTaskModal"
                                                                    data-id="{{ $Task->id }}">View Detail</a>
                                                                <a class="dropdown-item Task_ID"
                                                                    href="javascript:void(0)" data-bs-toggle="modal"
                                                                    data-bs-target="#ChangedWriter">
                                                                    <input type="hidden" id="Task_ID"
                                                                        value="{{ $Task->id }}">Change
                                                                    Writer</a>
                                                                <a class="dropdown-item Edit-Task Task_ID"
                                                                    href="javascript:void(0)" data-bs-toggle="modal"
                                                                    data-bs-target="#EditTaskModal">
                                                                    <input type="hidden" id="Task_ID"
                                                                        value="{{ $Task->id }}">
                                                                    Edit Task</a>
                                                                <a class="dropdown-item Delete_Task"
                                                                    href="{{ route('Delete.Task', ['Task_ID' => $Task->id]) }}">
                                                                    Delete Task
                                                                </a>
                                                                <a class="dropdown-item Task_ID"
                                                                    href="javascript:void(0)" data-bs-toggle="modal"
                                                                    data-bs-target="#TaskRevisionModal">
                                                                    <input type="hidden" id="Task_ID"
                                                                        value="{{ $Task->id }}">
                                                                    Add Revision</a>
                                                                @if (count($Task->revision) > 0)
                                                                    <a class="dropdown-item View-Revision"
                                                                        href="javascript:void(0)"
                                                                        data-bs-toggle="modal"
                                                                        data-bs-target="#ViewRevisionModal">View
                                                                        Revision
                                                                        <input type="hidden" id="Task_ID"
                                                                            value="{{ $Task->id }}"></a>
                                                                @endif
                                                                <a class="dropdown-item Task_ID"
                                                                    href="javascript:void(0)" data-bs-toggle="modal"
                                                                    data-bs-target="#TaskCancelWordModal">
                                                                    <input type="hidden" id="Task_ID"
                                                                        value="{{ $Task->id }}">
                                                                    Cancel Words</a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                            @php
                                                if ($Task->Task_Status !== 'Canceled') {
                                                    $assign += (float) Str::replace(['$ ', ','], '', $Task->Assign_Words);
                                                    $wordcount = (float) Str::replace(['$', ','], '', $Research_Order->basic_info->Word_Count);
                                                    $total_words = $wordcount - $assign;
                                                }
                                            @endphp
                                        @empty
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane" id="tab9">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table
                                    class="table text-center table-vcenter text-nowrap table-bordered border-bottom"
                                    id="files-tables">
                                    <thead>
                                        <tr>
                                            <th class="border-bottom-0 text-center w-5">No</th>
                                            <th class="border-bottom-0">File Name</th>
                                            <th class="border-bottom-0">Download File</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($Research_Order->tasks as $task)
                                            @forelse($task->submit_info as $submission)
                                                <tr>
                                                    <td class="text-center">{{ $loop->iteration }}</td>
                                                    <td>
                                                        <a href="{{ asset($submission->task_file_path) }}"
                                                            target="_blank" download
                                                            class="font-weight-semibold fs-14 mt-5">{{ $submission->File_Name }}
                                                            <span class="text-muted ms-2">(23 KB)</span></a>
                                                        <div class="clearfix"></div>
                                                        <small class="text-muted">{{ $submission->created_at }} -
                                                            Submitted
                                                            By
                                                            {{ $submission->submitted->basic_info->full_name }}</small>
                                                    </td>

                                                    <td>
                                                        <div class="d-flex justify-content-center">
                                                            <a href="{{ asset($submission->task_file_path) }}"
                                                                class="action-btns1" data-bs-toggle="tooltip"
                                                                data-bs-placement="top" title="Download" download
                                                                target="_blank"><i
                                                                    class="feather feather-download   text-success"></i></a>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @empty
                                            @endforelse
                                        @empty
                                            <tr>
                                                <td colspan="3">Files are Not Attached</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane" id="tab10">
                        <div class="card-body">
                            <div class="d-flex justify-content-end">
                                <button type="button" class="btn btn-danger mb-4" data-bs-toggle="modal"
                                    data-bs-target="#FinalSubmissionModal"> Upload Files
                                </button>
                            </div>
                            <div class="table-responsive">
                                <table
                                    class="table text-center table-vcenter text-nowrap table-bordered border-bottom"
                                    id="files-tables">
                                    <thead>
                                        <tr>
                                            <th class="border-bottom-0 text-center w-5">No</th>
                                            <th class="border-bottom-0">File Name</th>
                                            <th class="border-bottom-0">Download File</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($Research_Order->final_submission as $submission)
                                            <tr>
                                                <td class="text-center">{{ $loop->iteration }}</td>
                                                <td>
                                                    <a href="{{ asset($submission->final_submission_path) }}"
                                                        target="_blank" download
                                                        class="font-weight-semibold fs-14 mt-5">{{ $submission->File_Name }}
                                                        <span class="text-muted ms-2">(23 KB)</span></a>
                                                    <div class="clearfix"></div>
                                                    <small class="text-muted">{{ $submission->created_at }}</small>
                                                </td>

                                                <td>
                                                    <div class="d-flex justify-content-center">
                                                        <a href="{{ asset($submission->final_submission_path) }}"
                                                            class="action-btns1" data-bs-toggle="tooltip" download
                                                            data-bs-placement="top" title="Download"
                                                            target="_blank"><i
                                                                class="feather feather-download   text-success"></i></a>
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="3">Files are Not Attached</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                   @if (!empty($Research_Order->revision))
                        <div class="tab-pane" id="tab11">
                           
                                 <div class="card-body">
                                <div class="d-flex justify-content-end">
                                    
                                </div>
                                <div class="table-responsive">
                                    <table
                                        class="table text-center table-vcenter text-nowrap table-bordered border-bottom"
                                        id="files-tables">
                                        <thead>
                                            <tr>
                                                <th class="border-bottom-0 text-center w-5">No</th>
                                                <th class="border-bottom-0">Revised By</th>
                                                <th class="border-bottom-0">Date</th>
                                                <th class="border-bottom-0">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>                                       
                                             @foreach ($Research_Order->revision as $revision)
                                                    <tr>
                                                        <td>{{ $loop->iteration}}</td>
                                                        <td>{{ $revision->revision_by->basic_info->F_Name . ' ' . $revision->revision_by->basic_info->L_Name }}</td>
                                                        <td>{{$revision->created_at}}</td>
                                                        <td>
                                                        <div class="btn-list">
                                                            <div class="dropdown">
                                                                <button class="btn btn-info dropdown-toggle px-5" data-bs-toggle="dropdown">
                                                                    <i class="fe fe-activity me-2"></i>Actions
                                                                </button>
                                                                <div class="dropdown-menu">
                                                                    <a class="dropdown-item Order-Revision" id="Revision_ID" data-id="{{ $revision->id }}" href="JavaScript:void(0);" data-bs-toggle="modal"
                                                                        data-bs-target="#ViewRevision">View Revision</a>
                                                                    <a class="dropdown-item Order-Revision" id="Upload_Revision_ID" data-id="{{ $revision->id }}" href="JavaScript:void(0);" data-bs-toggle="modal"
                                                                        data-bs-target="#SubmitRevision">Upload Revision</a>

                                                                </div>
                                                            </div>
                                                        </div>
                                                        </td>
                                                    </tr>                                               
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                           
                        </div>
                    @endif
                    @if (!empty($Research_Order->submission_info->F_DeadLine) || !empty($Research_Order->submission_info->S_DeadLine) || !empty($Research_Order->submission_info->T_DeadLine))
                        <div class="tab-pane" id="tab12">
                            <div class="card-body">
                                <div class="d-flex justify-content-end">
                                    <button type="button" class="btn btn-danger mb-4" data-bs-toggle="modal"
                                        data-bs-target="#uploaddraft"> Upload Draft
                                    </button>
                                </div>
                                <div class="table-responsive">
                                    <table
                                        class="table text-center table-vcenter text-nowrap table-bordered border-bottom"
                                        id="files-tables">
                                        <thead>
                                            <tr>
                                                <th class="border-bottom-0 text-center w-5">No</th>
                                                <th class="border-bottom-0">File Name</th>
                                                <th class="border-bottom-0">Upload By</th>
                                                <th class="border-bottom-0">Download File</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        @php
                                            $e =1;
                                        @endphp
                                            @foreach ($draft_submission as $submission)
                                                @forelse ($submission->attachments as $attachment)
                                                    <tr>
                                                        <td class="text-center">{{ $e }}</td>
                                                        <td>
                                                            <a href="{{ asset($attachment->File_Path) }}"
                                                                target="_blank" download
                                                                class="font-weight-semibold fs-14 mt-5">
                                                                {{ $attachment->File_Name }}
                                                            </a>
                                                            @if ($submission->draft_number === 1)
                                                                <div class="">First Draft</div>
                                                            @elseif($submission->draft_number === 2)
                                                                <div class="">Second Draft</div>
                                                            @elseif($submission->draft_number === 3)
                                                                <div class="">Third Draft</div>
                                                            @else
                                                                <div class="">Unknown Draft</div>
                                                            @endif
                                                            <div>{{ $submission->created_at }}</div>
                                                        </td>
                                                        <td>{{ $submission->submittedByUser->basic_info->F_Name }}
                                                        </td>
                                                        <td>
                                                            <div class="d-flex justify-content-center">
                                                                <a href="{{ asset($attachment->File_Path) }}"
                                                                    class="action-btns1" data-bs-toggle="tooltip"
                                                                    download="" data-bs-placement="top"
                                                                    title="" target="_blank"
                                                                    data-bs-original-title="Download"
                                                                    aria-label="Download">
                                                                    <i
                                                                        class="feather feather-download text-success"></i>
                                                                </a>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    @php
                                                        
                                                    $e++
                                                    @endphp
                                                @empty
                                                    <tr>
                                                        <td colspan="3">No Files Attached</td>
                                                    </tr>
                                                @endforelse
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
            <div class="chatbox mt-lg-5" id="chatmodel">
                <div class="chat border-0">
                    <div class="card overflow-hidden mb-0 border-0">
                        <!-- action-header -->
                        <div class="card-header">
                            <div class="float-start hidden-xs d-flex ms-2">
                                <div class="img_cont me-3">
                                    <img src="{{ !empty($auth_user->basic_info->profile_photo_path) ? asset($auth_user->basic_info->profile_photo_path) : asset('assets/images/users/16.jpg') }}"
                                        class="rounded-circle user_img avatar avatar-md" alt="img">
                                </div>
                                <div class="align-items-center mt-2 text-black">
                                    <h5 class="mb-0">{{ $auth_user->basic_info->full_name }}</h5>
                                    <span class="w-2 h-2 brround bg-success d-inline-block"></span><span
                                        class="ms-2 fs-12">Online</span>
                                </div>
                            </div>
                        </div>
                        <!-- action-header end -->
                        <!-- msg_card_body -->
                        <div class="card-body msg_card_body" id="Order-Messages">

                        </div>
                        <!-- msg_card_body end -->
                        <!-- card-footer -->
                        <div class="card-footer">
                            <form data-action="{{ route('Post.Message') }}" method="POST"
                                enctype="multipart/form-data" id="Chat-Form">
                                @csrf
                                <input type="hidden" name="order_id" value="{{ $Research_Order->id }}">
                                <input type="hidden" name="user_id"
                                    value="{{ Auth::guard('Authorized')->user()->id }}">
                                <div class="msb-reply-button d-flex mb-3">
                                    <div class="input-group">
                                        <input type="text" class="form-control bg-white"
                                            placeholder="Typing...." name="Chat_Message" required>
                                        <div class="input-group-append ">
                                            <button type="submit" class="btn btn-primary ">
                                                <span class="feather feather-send"></span> Send
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <div class="msb-reply-button d-flex">
                                    <div class="input-group">
                                        <input type="file" class="form-control bg-white"
                                            placeholder="Any Attachments" name="files[]" multiple>
                                    </div>
                                </div>
                            </form>
                        </div><!-- card-footer end -->
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-12 col-lg-12">
            <div class="card">
                <div class="card-header  border-0">
                    <div class="card-title">Order Details</div>
                </div>
                <div class="card-body pt-2 ps-3 pr-3">
                    <div class="table-responsive">
                        <table class="table">
                            <tbody>
                                <tr>
                                    <td>
                                        <span class="w-50">Order ID </span>
                                    </td>
                                    <td>:</td>
                                    <td>
                                        <span class="font-weight-semibold">{{ $Research_Order->Order_ID }}</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <span class="w-50">Education</span>
                                    </td>
                                    <td>:</td>
                                    <td>
                                        <span
                                            class="font-weight-semibold">{{ $Research_Order->basic_info->Education_Level }}</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <span class="w-50">Pages</span>
                                    </td>
                                    <td>:</td>
                                    <td>
                                        <span
                                            class="font-weight-semibold">{{ $Research_Order->basic_info->Pages_Count }}</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <span class="w-50">Words</span>
                                    </td>
                                    <td>:</td>
                                    <td>
                                        <span
                                            class="font-weight-semibold">{{ $Research_Order->basic_info->Word_Count }}</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <span class="w-50">Spacing</span>
                                    </td>
                                    <td>:</td>
                                    <td>
                                        <span
                                            class="font-weight-semibold">{{ $Research_Order->basic_info->Spacing }}</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <span class="w-50">Citation Style</span>
                                    </td>
                                    <td>:</td>
                                    <td>
                                        <span
                                            class="font-weight-semibold">{{ $Research_Order->basic_info->Citation_Style }}</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <span class="w-50">Sources Needed</span>
                                    </td>
                                    <td>:</td>
                                    <td>
                                        <span
                                            class="font-weight-semibold">{{ $Research_Order->basic_info->Sources }}</span>
                                    </td>
                                </tr>
                                 @if (!empty($Research_Order->submission_info->F_DeadLine))
                                    <tr>
                                        <td>
                                            <span class="w-50">First Draft</span>
                                        </td>
                                        <td>:</td>
                                        <td>
                                            <span
                                                class="font-weight-semibold text-danger">{{ $Research_Order->submission_info->F_DeadLine }}</span>
                                        </td>
                                    </tr>
                                @endif
                                @if (!empty($Research_Order->submission_info->S_DeadLine))
                                    <tr>
                                        <td>
                                            <span class="w-50">Second Draft</span>
                                        </td>
                                        <td>:</td>
                                        <td>
                                            <span
                                                class="font-weight-semibold text-danger">{{ $Research_Order->submission_info->S_DeadLine }}</span>
                                        </td>
                                    </tr>
                                @endif
                                @if (!empty($Research_Order->submission_info->T_DeadLine))
                                    <tr>
                                        <td>
                                            <span class="w-50">Third Draft</span>
                                        </td>
                                        <td>:</td>
                                        <td>
                                            <span
                                                class="font-weight-semibold text-danger">{{ $Research_Order->submission_info->T_DeadLine }}</span>
                                        </td>
                                    </tr>
                                @endif
                                <tr>
                                    <td>
                                        <span class="w-50">Final Deadline</span>
                                    </td>
                                    <td>:</td>
                                    <td>
                                        <span
                                            class="font-weight-semibold text-danger">{{ $Research_Order->submission_info->DeadLine }}</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <span class="w-50">Status</span>
                                    </td>
                                    <td>:</td>
                                    <td>
                                        <span
                                            class="font-weight-semibold text-info">{{ $Research_Order->basic_info->Order_Status }}</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <span class="w-50">Created Date</span>
                                    </td>
                                    <td>:</td>
                                    <td>
                                        <span
                                            class="font-weight-semibold">{{ date('F d, Y', strtotime($Research_Order->created_at)) }}</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <span class="w-50">Created Time</span>
                                    </td>
                                    <td>:</td>
                                    <td>
                                        <span
                                            class="font-weight-semibold">{{ date('H:i:s A', strtotime($Research_Order->created_at)) }}</span>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
            <div class="card">
                <div class="card-header  border-0">
                    <div class="card-title">Research Department</div>
                </div>
                <div class="card-body pt-2 ps-3 pr-3">
                    <div class="table-responsive">
                        <table class="table">
                            <tbody>
                                @if (!empty($Research_Order->assign))
                                    @foreach ($Research_Order->assign as $User)
                                        <tr>
                                            <td>
                                                <span class="w-50">Coordinator {{ $loop->iteration }}</span>
                                            </td>
                                            <td>:</td>
                                            <td>
                                                <span
                                                    class="font-weight-semibold">{{ $User->basic_info->full_name }}</span>
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
                                @if (count($Research_Order->tasks) > 0)
                                    @foreach ($Research_Order->tasks as $task)
                                        <tr>
                                            <td>
                                                <span class="w-50">Executive {{ $loop->iteration }} </span>
                                            </td>
                                            <td>:</td>
                                            <td>
                                                <span
                                                    class="font-weight-semibold">{{ $task->assign->basic_info->full_name }}</span>
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>
    <!-- New Task Modal -->
    <div class="modal fade" id="NewTaskModal">upload
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <form action="{{ route('New.Task') }}" method="POST" class="needs-validation was-validated">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Make Order New Task</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <input type="hidden" name="order_id" value="{{ $Research_Order->id }}">
                            <input type="hidden" name="Order_ID" value="{{ $Research_Order->Order_ID }}">
                            <input type="hidden" name="assign_by"
                                value="{{ Auth::guard('Authorized')->user()->id }}">
                            <input type="hidden" name="total_words" id="Total_Words"
                                value="{{ $total_words !== 0 ? $total_words : (float) Str::replace(['$ ', ','], '', $Research_Order->basic_info->Word_Count) }}">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="form-label">Select Writers</label>
                                    <select name="W_Assign_ID" class="form-control custom-select select2"
                                        data-placeholder="Select Writer" aria-required="true" required>
                                        <option value="">Select Writers</option>
                                        @forelse($Writers as $Writer)
                                            <option value="{{ $Writer->id }}">
                                                {{ $Writer->basic_info->full_name }}</option>
                                        @empty
                                        @endforelse
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">DeadLine</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                            <span class="feather feather-calendar"></span>
                                        </div>
                                    </div>
                                    <input class="form-control" placeholder="MM/DD/YYYY" name="DeadLine"
                                        min="0" type="date">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">DeadLine Time</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                            <span class="feather feather-clock"></span>
                                        </div>
                                    </div><!-- input-group-prepend -->
                                    <input class="form-control" id="tp3" placeholder="Set time"
                                        name="DeadLine_Time" type="time" required>
                                </div><!-- input-group -->
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Total Words</label>
                                    <input class="form-control mb-4 is-valid" name="Order_Words"
                                        placeholder="Enter Order Words" id="Assign_Words" min="0"
                                        type="number" value="" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Due Words</label>
                                    <input class="form-control mb-4 is-valid" name="Due_Words" readonly
                                        placeholder="Enter Due Amount" type="number" id="Rem_Word">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary btn-block">Create Task</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- Task Revision Modal -->
    <div class="modal fade" id="TaskRevisionModal">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <form action="{{ route('Task.Revision') }}" method="POST"
                    class="needs-validation was-validated" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Add Revision For Current Task</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <input type="hidden" name="order_id" value="{{ $Research_Order->id }}">
                            <input type="hidden" name="Order_ID" value="{{ $Research_Order->Order_ID }}">
                            <input type="hidden" name="task_id" class="task-id">
                            <input type="hidden" name="revised_by"
                                value="{{ Auth::guard('Authorized')->user()->id }}">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="form-label" class="form-label"></label>
                                    <textarea id="summernote" class="form-control mb-4 is-invalid state-invalid" name="Task_Revision"
                                        placeholder="Textarea (invalid state)"></textarea>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">DeadLine</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                            <i class="feather feather-calendar"></i>
                                        </div>
                                    </div>
                                    <input class="form-control Rev-DeadLine" placeholder="MM/DD/YYYY"
                                        name="DeadLine" type="date">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">DeadLine Time</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                            <span class="feather feather-clock"></span>
                                        </div>
                                    </div><!-- input-group-prepend -->
                                    <input class="form-control Rev-Time" id="tp3" placeholder="Set time"
                                        name="DeadLine_Time" type="time" required>
                                </div><!-- input-group -->
                            </div>

                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label for="form-label" class="form-label"></label>
                                    <input class="form-control" type="file" name="files[]" multiple>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary btn-block">Send Revision</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- View Revision Modal -->
    <div class="modal fade" id="ViewRevisionModal">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">View Revisions of Current Task</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body Task_Deatils">
                    <div class="table-responsive">
                        <table class="table text-center table-vcenter text-nowrap table-bordered border-bottom" id="files-tables">
                            <thead>
                                <tr>
                                    <th class="border-bottom-0 text-center w-5">No</th>
                                    <th class="border-bottom-0">File Name</th>
                                    <th class="border-bottom-0">Download File</th>
                                </tr>
                            </thead>
                            <tbody id="task-table-body">
                                <!-- Table rows will be appended here -->
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary btn-block" data-bs-dismiss="modal"
                        aria-label="Close">
                        Close
                    </button>
                </div>
            </div>
        </div>
    </div>
    <!-- Change Writer Modal -->
    <div class="modal fade" id="ChangedWriter">
        <div class="modal-dialog modal-md" role="document">
            <div class="modal-content">
                <form action="{{ route('Change.Writer') }}" method="POST"
                    class="needs-validation was-validated">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Change Writer on Current Task</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <input type="hidden" name="task_id" class="task-id">
                            <input type="hidden" name="assign_by"
                                value="{{ Auth::guard('Authorized')->user()->id }}">
                            <input type="hidden" name="Order_ID" value="{{ $Research_Order->Order_ID }}">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="form-label">Select Writers</label>
                                    <select name="W_Assign_ID" class="form-control custom-select select2"
                                        data-placeholder="Select Writer" aria-required="true" required>
                                        <option value="">Select Writers</option>
                                        @forelse($Writers as $Writer)
                                            <option value="{{ $Writer->id }}">
                                                {{ $Writer->basic_info->full_name }}</option>
                                        @empty
                                        @endforelse
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary btn-block">Changed Writer</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- Edit Task Modal -->
    <div class="modal fade" id="EditTaskModal">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <form action="{{ route('Edit.Task') }}" method="POST" class="needs-validation was-validated">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Update Current Task</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <input type="hidden" name="task_id" class="task-id">
                            <input type="hidden" name="order_id" value="{{ $Research_Order->id }}">
                            <input type="hidden" name="Order_ID" value="{{ $Research_Order->Order_ID }}">
                            <input type="hidden" name="assign_by"
                                value="{{ Auth::guard('Authorized')->user()->id }}">
                            <input type="hidden" class="Selected-Total-Words" name="total_words"
                                id="Edit_Total_Words">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="form-label">Select Writers</label>
                                    <select name="W_Assign_ID"
                                        class="form-control custom-select select2 Selected-Writer"
                                        data-placeholder="Select Writer" aria-required="true" required>
                                        <option value="">Select Writers</option>
                                        @forelse($Writers as $Writer)
                                            <option value="{{ $Writer->id }}">
                                                {{ $Writer->basic_info->full_name }}</option>
                                        @empty
                                        @endforelse
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">DeadLine</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                            <span class="feather feather-calendar"></span>
                                        </div>
                                    </div>
                                    <input class="form-control Selected-Date" placeholder="MM/DD/YYYY"
                                        name="DeadLine" min="0" type="date">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">DeadLine Time</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                            <span class="feather feather-clock"></span>
                                        </div>
                                    </div><!-- input-group-prepend -->
                                    <input class="form-control Selected-Time" placeholder="Set time"
                                        name="DeadLine_Time" type="time" required>
                                </div><!-- input-group -->
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Total Words</label>
                                    <input class="form-control mb-4 is-valid Selected-Words" name="Order_Words"
                                        placeholder="Enter Order Words" id="Edit_Assign_Words" min="0"
                                        type="number" value="" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Due Words</label>
                                    <input class="form-control mb-4 is-valid Selected-Due-Words" name="Due_Words"
                                        readonly placeholder="Enter Due Amount" type="number" id="Edit_Rem_Word">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary btn-block">Update Current Task</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- Final Submission Modal -->
    <div class="modal fade" id="FinalSubmissionModal">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <form action="{{ route('Research.Order.Final.Submit') }}" method="POST"
                    class="needs-validation was-validated" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Final Submission For Current Order</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <input type="hidden" name="order_id" value="{{ $Research_Order->id }}">
                            <input type="hidden" name="Order_ID" value="{{ $Research_Order->Order_ID }}">
                            <input type="hidden" name="task_id" class="task-id">
                            <input type="hidden" name="submit_by"
                                value="{{ Auth::guard('Authorized')->user()->id }}">
                            <input type="hidden" name="user_id"
                                value="{{ Auth::guard('Authorized')->user()->id }}">
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label for="form-label" class="form-label"></label>
                                    <input class="form-control" type="file" name="files[]" multiple>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary btn-block">Upload Final Submission</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal fade" id="TaskCancelWordModal">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <form action="{{ route('Cancel.Words.Task') }}" method="POST"
                    class="needs-validation was-validated" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Are You Sure To Cancel Words of Current Task</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <input type="hidden" name="order_id" value="{{ $Research_Order->id }}">
                            <input type="hidden" name="Order_ID" value="{{ $Research_Order->Order_ID }}">
                            <input type="hidden" name="task_id" class="task-id">
                            <input type="hidden" name="cancel_by"
                                value="{{ Auth::guard('Authorized')->user()->id }}">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="form-label" class="form-label">Any Comments</label>
                                    <textarea class="form-control mb-4 is-invalid state-invalid" name="Cancellation_Comments"
                                        placeholder="Textarea (invalid state)"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary btn-block">Cancel Words</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

  
@endif

{{-- Executive Writer View --}}
@if ((int) $auth_user->Role_ID === 6)
    <!--Page header-->
    <div class="page-header d-xl-flex d-block">
        <div class="page-rightheader ms-md-auto">
        </div>
    </div>
    <!--End Page header-->
    <!-- Row -->
    <div class="row">
        <div class="col-xl-9 col-md-12 col-lg-12">
            <div class="tab-menu-heading hremp-tabs p-0 ">
                <div class="tabs-menu1">
                    <!-- Tabs -->
                    <ul class="nav panel-tabs">
                        <li class="ms-4"><a href="#tab5" class="active" data-bs-toggle="tab">Order
                                Description</a></li>
                        <li><a href="#tab7" data-bs-toggle="tab">Order Attachments</a></li>
                        <li><a href="#tab8" data-bs-toggle="tab">Order Assign Task</a></li>
                        <li><a href="#tab9" data-bs-toggle="tab">Writer Submission</a></li>
                    </ul>
                </div>
            </div>
            <div class="panel-body tabs-menu-body hremp-tabs1 p-0">
                <div class="tab-content">
                    <div class="tab-pane active" id="tab5">
                        <div class="card-body">
                            {!! $Research_Order->order_desc->Description !!}
                        </div>
                    </div>
                    <div class="tab-pane" id="tab7">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table
                                    class="table text-center table-vcenter text-nowrap table-bordered border-bottom"
                                    id="files-tables">
                                    <thead>
                                        <tr>
                                            <th class="border-bottom-0 text-center w-5">No</th>
                                            <th class="border-bottom-0">File Name</th>
                                            <th class="border-bottom-0">Download File</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($Research_Order->attachments as $attachment)
                                            <tr>
                                                <td class="text-center">{{ $loop->iteration }}</td>
                                                <td>
                                                    <a href="{{ asset($attachment->order_attachment_path) }}"
                                                        target="_blank" download
                                                        class="font-weight-semibold fs-14 mt-5">{{ $attachment->File_Name }}
                                                        <span class="text-muted ms-2">(23 KB)</span></a>
                                                    <div class="clearfix"></div>
                                                    <small class="text-muted">{{ $attachment->created_at }}</small>
                                                </td>

                                                <td>
                                                    <div class="d-flex justify-content-center">
                                                        <a href="{{ asset($attachment->order_attachment_path) }}"
                                                            class="action-btns1" data-bs-toggle="tooltip" download
                                                            data-bs-placement="top" title="Download"
                                                            target="_blank"><i
                                                                class="feather feather-download   text-success"></i></a>
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="3">Files are Not Attached</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane" id="tab8">
                        <div class="card-body">
                            <div class="table-responsive">

                                <table
                                    class="table text-center table-vcenter text-nowrap table-bordered border-bottom"
                                    id="responsive-datatable">
                                    <thead>
                                        <tr>
                                            <th class="border-bottom-0 text-center">Task</th>
                                            <th class="border-bottom-0">Writer</th>
                                            <th class="border-bottom-0">Assign Date</th>
                                            <th class="border-bottom-0">Assign Words</th>
                                            <th class="border-bottom-0">Deadline</th>
                                            <th class="border-bottom-0">Status</th>
                                            <th class="border-bottom-0">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($Research_Order->tasks as $Task)
                                            <tr>
                                                <td class="font-weight-bold">Task-{{ $loop->iteration }}</td>
                                                <td>
                                                    {{ $Task->assign->basic_info->full_name }}
                                                </td>
                                                <td>
                                                    {{ $Task->created_at }}
                                                </td>
                                                <td>
                                                    {{ $Task->Assign_Words }}
                                                </td>
                                                <td>
                                                    {{ $Task->DeadLine }} &nbsp; {{ $Task->DeadLine_Time }}
                                                </td>
                                                <td>
                                                    {{ $Task->Task_Status }}
                                                </td>
                                                <td>
                                                    <div class="d-flex">
                                                        @if ($Task->Task_Status !== 'Completed')
                                                            <a href="#" class="action-btns1 Task_ID w-100"
                                                                data-bs-toggle="modal" data-bs-placement="top"
                                                                title="Submit Task"
                                                                data-bs-target="#TaskSubmissionModal"><i
                                                                    class="feather feather-paperclip text-primary"></i>
                                                                Submit
                                                                <input type="hidden" id="Task_ID"
                                                                    value="{{ $Task->id }}">
                                                            </a>
                                                        @endif
                                                        @if (count($Task->revision) > 0)
                                                            <a href="#"
                                                                class="action-btns1 Task_ID w-100 View-Revision"
                                                                data-bs-placement="top" title="Submit Task"
                                                                data-bs-toggle="modal"
                                                                data-bs-target="#ViewRevisionModal"><i
                                                                    class="feather feather-repeat text-danger"></i>
                                                                Revision
                                                                <input type="hidden" id="Task_ID"
                                                                    value="{{ $Task->id }}">
                                                            </a>
                                                        @endif
                                                    </div>

                                                </td>
                                            </tr>
                                        @empty
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane" id="tab9">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table
                                    class="table text-center table-vcenter text-nowrap table-bordered border-bottom"
                                    id="files-tables">
                                    <thead>
                                        <tr>
                                            <th class="border-bottom-0 text-center w-5">No</th>
                                            <th class="border-bottom-0">File Name</th>
                                            <th class="border-bottom-0">Download File</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($Research_Order->tasks as $task)
                                            @forelse($task->submit_info as $submission)
                                                <tr>
                                                    <td class="text-center">{{ $loop->iteration }}</td>
                                                    <td>
                                                        <a href="{{ asset($submission->task_file_path) }}"
                                                            target="_blank" download
                                                            class="font-weight-semibold fs-14 mt-5">{{ $submission->File_Name }}
                                                            <span class="text-muted ms-2">(23 KB)</span></a>
                                                        <div class="clearfix"></div>
                                                        <small class="text-muted">{{ $submission->created_at }} -
                                                            Submitted
                                                            By
                                                            {{ $submission->submitted->basic_info->full_name }}</small>
                                                    </td>

                                                    <td>
                                                        <div class="d-flex justify-content-center">
                                                            <a href="{{ asset($submission->task_file_path) }}"
                                                                class="action-btns1" data-bs-toggle="tooltip"
                                                                data-bs-placement="top" title="Download" download
                                                                target="_blank"><i
                                                                    class="feather feather-download   text-success"></i></a>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @empty
                                            @endforelse
                                        @empty
                                            <tr>
                                                <td colspan="3">Files are Not Attached</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="chatbox mt-lg-5" id="chatmodel">
                <div class="chat border-0">
                    <div class="card overflow-hidden mb-0 border-0">
                        <!-- action-header -->
                        <div class="card-header">
                            <div class="float-start hidden-xs d-flex ms-2">
                                <div class="img_cont me-3">
                                    <img src="{{ !empty($auth_user->basic_info->profile_photo_path) ? asset($auth_user->basic_info->profile_photo_path) : asset('assets/images/users/16.jpg') }}"
                                        class="rounded-circle user_img avatar avatar-md" alt="img">
                                </div>
                                <div class="align-items-center mt-2 text-black">
                                    <h5 class="mb-0">{{ $auth_user->basic_info->full_name }}</h5>
                                    <span class="w-2 h-2 brround bg-success d-inline-block"></span><span
                                        class="ms-2 fs-12">Online</span>
                                </div>
                            </div>
                        </div>
                        <!-- action-header end -->
                        <!-- msg_card_body -->
                        <div class="card-body msg_card_body" id="Order-Messages">

                        </div>
                        <!-- msg_card_body end -->
                        <!-- card-footer -->
                        <div class="card-footer">
                            <form data-action="{{ route('Post.Message') }}" method="POST"
                                enctype="multipart/form-data" id="Chat-Form">
                                @csrf
                                <input type="hidden" name="order_id" value="{{ $Research_Order->id }}">
                                <input type="hidden" name="user_id"
                                    value="{{ Auth::guard('Authorized')->user()->id }}">
                                <input type="hidden" name="is_executive" value="1">
                                <div class="msb-reply-button d-flex mb-3">
                                    <div class="input-group">
                                        <input type="text" class="form-control bg-white"
                                            placeholder="Typing...." name="Chat_Message" required>
                                        <div class="input-group-append ">
                                            <button type="submit" class="btn btn-primary ">
                                                <span class="feather feather-send"></span> Send
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <div class="msb-reply-button d-flex">
                                    <div class="input-group">
                                        <input type="file" class="form-control bg-white"
                                            placeholder="Any Attachments" name="files[]" multiple>
                                    </div>
                                </div>
                            </form>
                        </div><!-- card-footer end -->
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-12 col-lg-12">
            <div class="card">
                <div class="card-header  border-0">
                    <div class="card-title">Order Details</div>
                </div>
                <div class="card-body pt-2 ps-3 pr-3">
                    <div class="table-responsive">
                        <table class="table">
                            <tbody>
                                <tr>
                                    <td>
                                        <span class="w-50">Order ID </span>
                                    </td>
                                    <td>:</td>
                                    <td>
                                        <span class="font-weight-semibold">{{ $Research_Order->Order_ID }}</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <span class="w-50">Education</span>
                                    </td>
                                    <td>:</td>
                                    <td>
                                        <span
                                            class="font-weight-semibold">{{ $Research_Order->basic_info->Education_Level }}</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <span class="w-50">Pages</span>
                                    </td>
                                    <td>:</td>
                                    <td>
                                        <span
                                            class="font-weight-semibold">{{ $Research_Order->basic_info->Pages_Count }}</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <span class="w-50">Words</span>
                                    </td>
                                    <td>:</td>
                                    <td>
                                        <span
                                            class="font-weight-semibold">{{ $Research_Order->basic_info->Word_Count }}</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <span class="w-50">Spacing</span>
                                    </td>
                                    <td>:</td>
                                    <td>
                                        <span
                                            class="font-weight-semibold">{{ $Research_Order->basic_info->Spacing }}</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <span class="w-50">Citation Style</span>
                                    </td>
                                    <td>:</td>
                                    <td>
                                        <span
                                            class="font-weight-semibold">{{ $Research_Order->basic_info->Citation_Style }}</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <span class="w-50">Sources Needed</span>
                                    </td>
                                    <td>:</td>
                                    <td>
                                        <span
                                            class="font-weight-semibold">{{ $Research_Order->basic_info->Sources }}</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <span class="w-50">Final Deadline</span>
                                    </td>
                                    <td>:</td>
                                    <td>
                                        <span
                                            class="font-weight-semibold text-danger">{{ $Research_Order->submission_info->DeadLine }}</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <span class="w-50">Status</span>
                                    </td>
                                    <td>:</td>
                                    <td>
                                        <span
                                            class="font-weight-semibold text-info">{{ $Research_Order->basic_info->Order_Status }}</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <span class="w-50">Created Date</span>
                                    </td>
                                    <td>:</td>
                                    <td>
                                        <span
                                            class="font-weight-semibold">{{ date('F d, Y', strtotime($Research_Order->created_at)) }}</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <span class="w-50">Created Time</span>
                                    </td>
                                    <td>:</td>
                                    <td>
                                        <span
                                            class="font-weight-semibold">{{ date('H:i:s A', strtotime($Research_Order->created_at)) }}</span>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>
    <!-- New Task Modal -->
    <div class="modal fade" id="TaskSubmissionModal">
        <div class="modal-dialog modal-md" role="document">
            <div class="modal-content">
                <form action="{{ route('Task.Submit') }}" method="POST" class="needs-validation was-validated"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Submit Your Task</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <input type="hidden" name="order_id" value="{{ $Research_Order->id }}">
                            <input type="hidden" name="Order_ID" value="{{ $Research_Order->Order_ID }}">
                            <input type="hidden" name="task_id" class="task-id">
                            <input type="hidden" name="submit_by"
                                value="{{ Auth::guard('Authorized')->user()->id }}">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="form-label" class="form-label"></label>
                                    <input class="form-control" type="file" name="files[]" multiple required>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary btn-block">Submit Task</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- View Revision Modal -->
    <div class="modal fade" id="ViewRevisionModal">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">View Revisions of Current Task</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body Get-Revisions">

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary btn-block" data-bs-dismiss="modal"
                        aria-label="Close">
                        Close
                    </button>
                </div>
            </div>
        </div>
    </div>
@endif

{{-- Independent Writer View --}}
@if ((int) $auth_user->Role_ID === 7)
    <!--Page header-->
    <div class="page-header d-xl-flex d-block">
        <div class="page-rightheader ms-md-auto">
        </div>
    </div>
    <!--End Page header-->
    <!-- Row -->
    <div class="row">
        <div class="col-xl-9 col-md-12 col-lg-12">
            <div class="tab-menu-heading hremp-tabs p-0 ">
                <div class="tabs-menu1">
                    <!-- Tabs -->
                    <ul class="nav panel-tabs">
                        <li class="ms-4"><a href="#tab5" class="active" data-bs-toggle="tab">Order
                                Description</a></li>
                        <li><a href="#tab7" data-bs-toggle="tab">Order Attachments</a></li>
                        <li><a href="#tab8" data-bs-toggle="tab">Order Assign Task</a></li>
                        @if (!empty($Research_Order->submission_info->F_DeadLine) || !empty($Research_Order->submission_info->S_DeadLine) || !empty($Research_Order->submission_info->T_DeadLine))
                            <li><a href="#tab12" data-bs-toggle="tab">Draft Submission</a></li>
                        @endif
                    </ul>
                </div>
            </div>
            <div class="panel-body tabs-menu-body hremp-tabs1 p-0">
                <div class="tab-content">
                    <div class="tab-pane active" id="tab5">
                        <div class="card-body">
                            {!! $Research_Order->order_desc->Description !!}
                        </div>
                    </div>
                    <div class="tab-pane" id="tab7">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table
                                    class="table text-center table-vcenter text-nowrap table-bordered border-bottom"
                                    id="files-tables">
                                    <thead>
                                        <tr>
                                            <th class="border-bottom-0 text-center w-5">No</th>
                                            <th class="border-bottom-0">File Name</th>
                                            <th class="border-bottom-0">Download File</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($Research_Order->attachments as $attachment)
                                            <tr>
                                                <td class="text-center">{{ $loop->iteration }}</td>
                                                <td>
                                                    <a href="{{ asset($attachment->order_attachment_path) }}"
                                                        target="_blank" download
                                                        class="font-weight-semibold fs-14 mt-5">{{ $attachment->File_Name }}
                                                        <span class="text-muted ms-2">(23 KB)</span></a>
                                                    <div class="clearfix"></div>
                                                    <small class="text-muted">{{ $attachment->created_at }}</small>
                                                </td>

                                                <td>
                                                    <div class="d-flex justify-content-center">
                                                        <a href="{{ asset($attachment->order_attachment_path) }}"
                                                            class="action-btns1" data-bs-toggle="tooltip" download
                                                            data-bs-placement="top" title="Download"
                                                            target="_blank"><i
                                                                class="feather feather-download   text-success"></i></a>
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="3">Files are Not Attached</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane" id="tab8">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table
                                    class="table text-center table-vcenter text-nowrap table-bordered border-bottom"
                                    id="responsive-datatable">
                                    <thead>
                                        <tr>
                                            <th class="border-bottom-0 text-center">Task</th>
                                            <th class="border-bottom-0">Writer</th>
                                            <th class="border-bottom-0">Assign Date</th>
                                            <th class="border-bottom-0">Assign Words</th>
                                            <th class="border-bottom-0">Deadline</th>
                                            <th class="border-bottom-0">Status</th>
                                            <th class="border-bottom-0">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $total_words = 0;
                                        @endphp
                                        @forelse($Research_Order->tasks as $Task)
                                            <tr>
                                                <td class="font-weight-bold">Task-{{ $loop->iteration }}</td>
                                                <td>
                                                    {{ $Task->assign->basic_info->full_name }}
                                                </td>
                                                <td>
                                                    {{ $Task->created_at }}
                                                </td>
                                                <td>
                                                    {{ $Task->Assign_Words }}
                                                </td>
                                                <td>
                                                    {{ $Task->DeadLine }} &nbsp; {{ $Task->DeadLine_Time }}
                                                </td>
                                                <td>
                                                    {{ $Task->Task_Status }}
                                                </td>
                                                <td>
                                                    <div class="d-flex">
                                                        @if ($Task->Task_Status !== 'Completed')
                                                            <a href="#" class="action-btns1 Task_ID w-100"
                                                                data-bs-toggle="modal" data-bs-placement="top"
                                                                title="Submit Task"
                                                                data-bs-target="#TaskSubmissionModal"><i
                                                                    class="feather feather-paperclip text-primary"></i>
                                                                Submit
                                                                <input type="hidden" id="Task_ID"
                                                                    value="{{ $Task->id }}">
                                                            </a>
                                                        @endif
                                                        @if (count($Task->revision) > 0)
                                                            <a href="#"
                                                                class="action-btns1 Task_ID  View-Revision w-100"
                                                                data-bs-placement="top" title="Submit Task"
                                                                data-bs-toggle="modal"
                                                                data-bs-target="#ViewRevisionModal"><i
                                                                    class="feather feather-repeat text-danger"></i>
                                                                Revision
                                                                <input type="hidden" id="Task_ID"
                                                                    value="{{ $Task->id }}">
                                                            </a>
                                                        @endif
                                                    </div>

                                                </td>
                                            </tr>
                                        @empty
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane" id="tab9">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table
                                    class="table text-center table-vcenter text-nowrap table-bordered border-bottom"
                                    id="files-tables">
                                    <thead>
                                        <tr>
                                            <th class="border-bottom-0 text-center w-5">No</th>
                                            <th class="border-bottom-0">File Name</th>
                                            <th class="border-bottom-0">Download File</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($Research_Order->tasks as $task)
                                            @forelse($task->submit_info as $submission)
                                                <tr>
                                                    <td class="text-center">{{ $loop->iteration }}</td>
                                                    <td>
                                                        <a href="{{ asset($submission->task_file_path) }}"
                                                            target="_blank" download
                                                            class="font-weight-semibold fs-14 mt-5">{{ $submission->File_Name }}
                                                            <span class="text-muted ms-2">(23 KB)</span></a>
                                                        <div class="clearfix"></div>
                                                        <small class="text-muted">{{ $submission->created_at }} -
                                                            Submitted
                                                            By
                                                            {{ $submission->submitted->basic_info->full_name }}</small>
                                                    </td>

                                                    <td>
                                                        <div class="d-flex justify-content-center">
                                                            <a href="{{ asset($submission->task_file_path) }}"
                                                                class="action-btns1" data-bs-toggle="tooltip"
                                                                data-bs-placement="top" title="Download" download
                                                                target="_blank"><i
                                                                    class="feather feather-download   text-success"></i></a>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @empty
                                            @endforelse
                                        @empty
                                            <tr>
                                                <td colspan="3">Files are Not Attached</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    @if (!empty($Research_Order->submission_info->F_DeadLine) || !empty($Research_Order->submission_info->S_DeadLine) || !empty($Research_Order->submission_info->T_DeadLine))
                        <div class="tab-pane" id="tab12">
                            <div class="card-body">
                                <div class="d-flex justify-content-end">
                                    <button type="button" class="btn btn-danger mb-4" data-bs-toggle="modal"
                                        data-bs-target="#uploaddraft"> Upload Draft
                                    </button>
                                </div>
                                <div class="table-responsive">
                                    <table
                                        class="table text-center table-vcenter text-nowrap table-bordered border-bottom"
                                        id="files-tables">
                                        <thead>
                                            <tr>
                                                <th class="border-bottom-0 text-center w-5">No</th>
                                                <th class="border-bottom-0">File Name</th>
                                                <th class="border-bottom-0">Upload By</th>
                                                <th class="border-bottom-0">Download File</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        @php
                                            $f = 1;
                                        @endphp
                                            @foreach ($draft_submission as $submission)
                                                @forelse ($submission->attachments as $attachment)
                                                    <tr>
                                                        <td class="text-center">{{ $f }}</td>
                                                        <td>
                                                            <a href="{{ asset($attachment->File_Path) }}"
                                                                target="_blank" download
                                                                class="font-weight-semibold fs-14 mt-5">
                                                                {{ $attachment->File_Name }}
                                                            </a>
                                                            @if ($submission->draft_number === 1)
                                                                <div class="">First Draft</div>
                                                            @elseif($submission->draft_number === 2)
                                                                <div class="">Second Draft</div>
                                                            @elseif($submission->draft_number === 3)
                                                                <div class="">Third Draft</div>
                                                            @else
                                                                <div class="">Unknown Draft</div>
                                                            @endif
                                                            <div>{{ $submission->created_at }}</div>
                                                        </td>
                                                        <td>{{ $submission->submittedByUser->basic_info->F_Name }}
                                                        </td>
                                                        <td>
                                                            <div class="d-flex justify-content-center">
                                                                <a href="{{ asset($attachment->File_Path) }}"
                                                                    class="action-btns1" data-bs-toggle="tooltip"
                                                                    download="" data-bs-placement="top"
                                                                    title="" target="_blank"
                                                                    data-bs-original-title="Download"
                                                                    aria-label="Download">
                                                                    <i
                                                                        class="feather feather-download text-success"></i>
                                                                </a>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                     @php
                                                        $f++;
                                                    @endphp

                                                @empty
                                                    <tr>
                                                        <td colspan="3">No Files Attached</td>
                                                    </tr>
                                                @endforelse
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
            <div class="chatbox mt-lg-5" id="chatmodel">
                <div class="chat border-0">
                    <div class="card overflow-hidden mb-0 border-0">
                        <!-- action-header -->
                        <div class="card-header">
                            <div class="float-start hidden-xs d-flex ms-2">
                                <div class="img_cont me-3">
                                    <img src="{{ !empty($auth_user->basic_info->profile_photo_path) ? asset($auth_user->basic_info->profile_photo_path) : asset('assets/images/users/16.jpg') }}"
                                        class="rounded-circle user_img avatar avatar-md" alt="img">
                                </div>
                                <div class="align-items-center mt-2 text-black">
                                    <h5 class="mb-0">{{ $auth_user->basic_info->full_name }}</h5>
                                    <span class="w-2 h-2 brround bg-success d-inline-block"></span><span
                                        class="ms-2 fs-12">Online</span>
                                </div>
                            </div>
                        </div>
                        <!-- action-header end -->
                        <!-- msg_card_body -->
                        <div class="card-body msg_card_body" id="Order-Messages">

                        </div>
                        <!-- msg_card_body end -->
                        <!-- card-footer -->
                        <div class="card-footer">
                            <form data-action="{{ route('Post.Message') }}" method="POST"
                                enctype="multipart/form-data" id="Chat-Form">
                                @csrf
                                <input type="hidden" name="order_id" value="{{ $Research_Order->id }}">
                                <input type="hidden" name="user_id"
                                    value="{{ Auth::guard('Authorized')->user()->id }}">
                                <div class="msb-reply-button d-flex mb-3">
                                    <div class="input-group">
                                        <input type="text" class="form-control bg-white"
                                            placeholder="Typing...." name="Chat_Message" required>
                                        <div class="input-group-append ">
                                            <button type="submit" class="btn btn-primary ">
                                                <span class="feather feather-send"></span> Send
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <div class="msb-reply-button d-flex">
                                    <div class="input-group">
                                        <input type="file" class="form-control bg-white"
                                            placeholder="Any Attachments" name="files[]" multiple>
                                    </div>
                                </div>
                            </form>
                        </div><!-- card-footer end -->
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-12 col-lg-12">
            <div class="card">
                <div class="card-header  border-0">
                    <div class="card-title">Order Details</div>
                </div>
                <div class="card-body pt-2 ps-3 pr-3">
                    <div class="table-responsive">
                        <table class="table">
                            <tbody>
                                <tr>
                                    <td>
                                        <span class="w-50">Order ID </span>
                                    </td>
                                    <td>:</td>
                                    <td>
                                        <span class="font-weight-semibold">{{ $Research_Order->Order_ID }}</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <span class="w-50">Education</span>
                                    </td>
                                    <td>:</td>
                                    <td>
                                        <span
                                            class="font-weight-semibold">{{ $Research_Order->basic_info->Education_Level }}</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <span class="w-50">Pages</span>
                                    </td>
                                    <td>:</td>
                                    <td>
                                        <span
                                            class="font-weight-semibold">{{ $Research_Order->basic_info->Pages_Count }}</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <span class="w-50">Words</span>
                                    </td>
                                    <td>:</td>
                                    <td>
                                        <span
                                            class="font-weight-semibold">{{ $Research_Order->basic_info->Word_Count }}</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <span class="w-50">Spacing</span>
                                    </td>
                                    <td>:</td>
                                    <td>
                                        <span
                                            class="font-weight-semibold">{{ $Research_Order->basic_info->Spacing }}</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <span class="w-50">Citation Style</span>
                                    </td>
                                    <td>:</td>
                                    <td>
                                        <span
                                            class="font-weight-semibold">{{ $Research_Order->basic_info->Citation_Style }}</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <span class="w-50">Sources Needed</span>
                                    </td>
                                    <td>:</td>
                                    <td>
                                        <span
                                            class="font-weight-semibold">{{ $Research_Order->basic_info->Sources }}</span>
                                    </td>
                                </tr>
                                 @if (!empty($Research_Order->submission_info->F_DeadLine))
                                    <tr>
                                        <td>
                                            <span class="w-50">First Draft</span>
                                        </td>
                                        <td>:</td>
                                        <td>
                                            <span
                                                class="font-weight-semibold text-danger">{{ $Research_Order->submission_info->F_DeadLine }}</span>
                                        </td>
                                    </tr>
                                @endif
                                @if (!empty($Research_Order->submission_info->S_DeadLine))
                                    <tr>
                                        <td>
                                            <span class="w-50">Second Draft</span>
                                        </td>
                                        <td>:</td>
                                        <td>
                                            <span
                                                class="font-weight-semibold text-danger">{{ $Research_Order->submission_info->S_DeadLine }}</span>
                                        </td>
                                    </tr>
                                @endif
                                @if (!empty($Research_Order->submission_info->T_DeadLine))
                                    <tr>
                                        <td>
                                            <span class="w-50">Third Draft</span>
                                        </td>
                                        <td>:</td>
                                        <td>
                                            <span
                                                class="font-weight-semibold text-danger">{{ $Research_Order->submission_info->T_DeadLine }}</span>
                                        </td>
                                    </tr>
                                @endif
                                <tr>
                                    <td>
                                        <span class="w-50">Final Deadline</span>
                                    </td>
                                    <td>:</td>
                                    <td>
                                        <span
                                            class="font-weight-semibold text-danger">{{ $Research_Order->submission_info->DeadLine }}</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <span class="w-50">Status</span>
                                    </td>
                                    <td>:</td>
                                    <td>
                                        <span
                                            class="font-weight-semibold text-info">{{ $Research_Order->basic_info->Order_Status }}</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <span class="w-50">Created Date</span>
                                    </td>
                                    <td>:</td>
                                    <td>
                                        <span
                                            class="font-weight-semibold">{{ date('F d, Y', strtotime($Research_Order->created_at)) }}</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <span class="w-50">Created Time</span>
                                    </td>
                                    <td>:</td>
                                    <td>
                                        <span
                                            class="font-weight-semibold">{{ date('H:i:s A', strtotime($Research_Order->created_at)) }}</span>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>
    <!-- New Task Modal -->
    <div class="modal fade" id="TaskSubmissionModal">
        <div class="modal-dialog modal-md" role="document">
            <div class="modal-content">
                <form action="{{ route('Task.Submit') }}" method="POST" class="needs-validation was-validated"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Submit Your Task</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <input type="hidden" name="order_id" value="{{ $Research_Order->id }}">
                            <input type="hidden" name="Order_ID" value="{{ $Research_Order->Order_ID }}">
                            <input type="hidden" name="task_id" class="task-id">
                            <input type="hidden" name="submit_by"
                                value="{{ Auth::guard('Authorized')->user()->id }}">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="form-label" class="form-label"></label>
                                    <input class="form-control" type="file" name="files[]" multiple required>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary btn-block">Submit Task</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- View Revision Modal -->
    <div class="modal fade" id="ViewRevisionModal">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">View Revisions of Current Task</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body Get-Revisions">

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary btn-block" data-bs-dismiss="modal"
                        aria-label="Close">
                        Close
                    </button>
                </div>
            </div>
        </div>
    </div>
@endif

<div class="modal fade" id="uploaddraft">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form action="{{ route('Order.Draft.Submit') }}" method="POST"
                class="needs-validation was-validated" enctype="multipart/form-data">

                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Draft Submission</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <input type="hidden" name="order_id" value="{{ $Research_Order->id }}">
                        <input type="hidden" name="Order_Number" value="{{ $Order_ID }}">
                        <input type="hidden" name="submit_by"
                            value="{{ Auth::guard('Authorized')->user()->id }}">
                        <input type="hidden" name="user_id"
                            value="{{ Auth::guard('Authorized')->user()->id }}">
                        <div class="col-lg-12">
                            <div class="form-group">
                                <label for="form-label" class="form-label"> Select Draft</label>
                              <select name="draft_number" id="draft_number" class="form-select" required>
                                <option value="" disabled selected>Select Deadline</option>
                                @if (!empty($Research_Order->submission_info->F_DeadLine))
                                    <option value="1">1st Draft</option>
                                @endif
                                @if (!empty($Research_Order->submission_info->S_DeadLine))
                                    <option value="2">2nd Draft</option>
                                @endif
                                @if (!empty($Research_Order->submission_info->T_DeadLine))
                                    <option value="3">3rd Draft</option>
                                @endif
                            </select>
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="form-group">
                                <label for="form-label" class="form-label"></label>
                                <input class="form-control" type="file" name="files[]" multiple>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary btn-block">Upload Draft Submission</button>
                </div>
            </form>
        </div>
    </div>
</div>

 <!-- Assign Modal -->
<div class="modal fade" id="AssignModal">
        <div class="modal-dialog modal-md" role="document">
            <div class="modal-content">
                <form action="{{ route('Assign.Order') }}" method="POST"
                    class="needs-validation was-validated">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Assign Order</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <input type="hidden" name="Order_ID" value="{{ $Order_ID }}">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="form-label" for="Assign_ID">Coordinators</label>
                                    <select name="Assign_ID[]" id="Assign_ID" class="form-control select2"
                                        data-placeholder="Select Coordinator" multiple required>
                                        <option value="">Select Coordinator</option>
                                        @forelse($Coordinators as $Coordinator)
                                            <option value="{{ $Coordinator->id }}">
                                                {{ $Coordinator->basic_info->full_name }}</option>
                                        @empty
                                            <option value="">No Coordinators available</option>
                                        @endforelse
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary btn-block">Assign Order</button>
                    </div>
                </form>
            </div>
        </div>
</div>


<div class="modal fade" id="ViewTaskModal">
      <div class="modal-dialog modal-lg" role="document">
          <div class="modal-content">
              <div class="modal-header">
                  <h5 class="modal-title">View Submited Task</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">×</span>
                  </button>
              </div>
              <div class="modal-body">
                  <table id="task-table-body" class="table">
                      <thead>
                          <tr>
                              <th>ID</th>
                              <th>Document</th>
                              <th>Download</th>
                          </tr>
                      </thead>
                      <tbody>
                      
                      </tbody>
                  </table>
              </div>
              <div class="modal-footer">
                  <button type="button" class="btn btn-primary btn-block" data-bs-dismiss="modal"
                      aria-label="Close">
                      Close
                  </button>
              </div>
          </div>
      </div>
</div>


    <div class="modal fade" id="ViewRevision">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <form action="" method="POST"
                    class="needs-validation was-validated" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">View Revision Details</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <input type="hidden" name="order_id" value="">                           
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="form-label" class="form-label"></label>
                                    <div class="p-2">
                                    <h4>Order Description</h4>
                                    <p id="revision_details"></p>                                    
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mt-5">
                                <label class="form-label">DeadLine</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                            <i class="feather feather-calendar"></i>
                                        </div>
                                    </div>
                                    <input class="form-control" id="Order_Deadline_Date" 
                                        name="DeadLine" type="text" readonly>
                                </div>
                            </div>
                            <div class="col-md-6 mt-5">
                                <label class="form-label">DeadLine Time</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                            <span class="feather feather-clock"></span>
                                        </div>
                                    </div><!-- input-group-prepend -->
                                    <input class="form-control Order-Time" placeholder="Set time"
                                        name="DeadLine_Time" type="time" id="Order_Deadline_Time" required readonly>
                                </div><!-- input-group -->
                            </div>
                              <div class="table-responsive mt-5">
                              <h4 class="my-4">Upload by Sales</h4>
                                    <table
                                        class="table text-center table-vcenter text-nowrap table-bordered border-bottom"
                                        id="Revision_view_table">
                                        <thead>
                                            <tr>
                                                <th class="border-bottom-0 text-center w-5">No</th>
                                                <th class="border-bottom-0">File Name</th>
                                                <th class="border-bottom-0">Download File</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                                                                
                                                  
                                              
                                        </tbody>
                                    </table>
                              </div>
                               <div class="table-responsive mt-5">
                              <h4 class="my-4">Upload by Writer</h4>
                                    <table
                                        class="table text-center table-vcenter text-nowrap table-bordered border-bottom"
                                        id="Writer_Submission_view_table">
                                        <thead>
                                            <tr>
                                                <th class="border-bottom-0 text-center w-5">No</th>
                                                <th class="border-bottom-0">File Name</th>
                                                <th class="border-bottom-0">Upload By</th>
                                                <th class="border-bottom-0">Download File</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                                                                
                                                  
                                              
                                        </tbody>
                                    </table>
                              </div>
                           
                           
                            
                        </div>
                    </div>
                   
                </form>
            </div>
        </div>
    </div>

<div class="modal fade" id="SubmitRevision">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <form action="{{route('Submit.Upload.Order.Revision')}}" method="POST"
                    class="needs-validation was-validated" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Upload Revision</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <input type="hidden" id="hidden_Revision_ID" name="Revision_ID" value="">    
                            <input type="hidden" name="upload_by" value="{{ Auth::guard('Authorized')->user()->id }}">    
                            <input type="hidden" name="Order_ID" value="{{$Research_Order->id }}">    
                            <input type="hidden" name="Order_Number" value="{{$Research_Order->Order_ID}}">    

                              <div class="col-lg-12">
                                <div class="form-group">
                                    <label for="form-label" class="form-label"></label>
                                    <input class="form-control" type="file" name="files[]" multiple>
                                </div>
                            </div>

                            <div class="modal-footer">
                                <button type="submit" class="btn btn-primary btn-block">Submit Revision </button>
                            </div>                          
                        </div>
                    </div>
                   
                </form>
            </div>
        </div>
    </div>


<script>

  $('#Upload_Revision_ID').click(function(){
    var Revision_ID = $(this).data('id');
    $('#hidden_Revision_ID').val(Revision_ID);
      
  })


  $('#Revision_ID').click(function() {
    var Revision_ID = $(this).data('id');
    console.log("ID before AJAX request:", Revision_ID);

    $.ajax({
        url: "{{ route('Get.Revision.Details') }}",
        type: 'GET',
        data: {
            'Revision_ID': Revision_ID
        },
        success: function(data) {
            console.log("Response from server:", data);
            $('#revision_details').append(data.revision_details);            
            $('#Order_Deadline_Time').val(data.Order_deadLine_Time);             
            $('#Order_Deadline_Date').val(data.Order_deadLine); 
           
           // Clear any previous attachment data in the modal
            $('#Revision_view_table tbody').empty();

            // Loop through attachments and append them to the table
            if (data.attachments.length > 0) {
                var tbody = $('#Revision_view_table tbody');
                $.each(data.attachments, function(index, attachment) {
                    var assetUrl = "{{ asset('" + attachment.file_path + "') }}"; // Generate asset URL
                    var assetUrl = `{{ asset('${attachment.file_path}') }}`; // Generate asset URL

                    var row = '<tr>' +
                        '<td>' + (index + 1) + '</td>' +
                       '<td>' + attachment.file_name + '<br>' + attachment.attachment_time + '</td>' +

                        '<td>' +
                        '<div class="d-flex justify-content-center">' +
                        '<a href="' + assetUrl + '" class="action-btns1" ' +
                        'data-bs-toggle="tooltip" download="' + attachment.file_name + '" ' +
                        'data-bs-placement="top" title="Download" target="_blank" aria-label="Download">' +
                        '<i class="feather feather-download text-success"></i>' +
                        '</a>' +
                        '</div>' +
                        '</td>' +
                        '</tr>';
                    tbody.append(row);
                });
            } else {
                console.log("No attachments found.");
            }
            

        },
        error: function(xhr) {
            var errorMessage = xhr.responseText;
            console.log("Error message:", errorMessage);
        }
    });

   

   

   $.ajax({
        url: "{{ route('Get.Writer.Order.Attachment') }}",
        type: 'GET',
        data: {
            'Revision_ID': Revision_ID
        },
      success: function(data) {
        console.log("AJAX Response:", data); 
        $('#Writer_Submission_view_table tbody').append(data);
            },

        error: function(xhr) {
            var errorMessage = xhr.responseText;
            console.log("Error message:", errorMessage);
        }
    });



    $('.view-assign-task').click(function () {
        var currentTaskID = $(this).data('id');
        $.ajax({
            url: "{{ route('View.Assign.Task') }}",
            type: 'GET',
            data: {
                'task_id': currentTaskID
            },
            success: function (data) {

                $('#task-table-body tbody').empty().append(data); // Clear existing content and append new rows
            },
            error: function (xhr) {
                var errorMessage = xhr.responseText;
                console.log(errorMessage);
            }
        });
    });


    $('#Assign_Words').keyup(function () {
        var Rec_Amount = parseInt($(this).val());
        var Payment = parseInt($('#Total_Words').val());

        if (Rec_Amount > Payment) {
            alert('Entered Words Exceeds!');
            $('#Rem_Word').val(0);
            $(this).val(Payment);
        } else {
            var Due_Amount = Payment - Rec_Amount;
            $('#Rem_Word').val(Due_Amount);
        }
    });

    $('.Task_ID').on('click', function () {
        var getTask_ID = $(this).find('#Task_ID').val();
        $(".task-id").val(getTask_ID);
    });

    $('.View-Revision').on('click', function () {
        const getTask_ID = $(this).find('#Task_ID').val();
        $.ajax({
            url: '{{ route('Get.Task.Revisions') }}',
            type: 'GET',
            data: {
                'task_id': getTask_ID
            },
            success: function (data) {
                $('.Get-Revisions').html(data);
            },
            error: function (data) {
                alert(data.responseJSON);
            }
        });
    });

    $('#Edit_Assign_Words').keyup(function () {
        var Rec_Amount = parseInt($(this).val());
        var Payment = parseInt($('#Edit_Total_Words').val());
        console.log(Payment);
        console.log(Rec_Amount);
        if (Rec_Amount > Payment) {
            alert('Entered Words Exceeds!');
            $('#Edit_Rem_Word').val(0);
            $(this).val(Payment);
        } else {
            var Due_Amount = Payment - Rec_Amount;
            $('#Edit_Rem_Word').val(Due_Amount);
        }
    });

    $('.Edit-Task').on('click', function () {
        const getTask_ID = $(this).find('#Task_ID').val();
        $.ajax({
            url: '{{ route('Get.Edit.Task.Info') }}',
            type: 'GET',
            data: {
                'task_id': getTask_ID
            },
            success: function (data) {
                $('.Selected-Date').val(data.Selected_Date);
                $('.Selected-Time').val(data.Selected_Time);
                $('.Selected-Writer').val(data.Selected_Writer).trigger('change');
                $('.Selected-Words').val(data.Selected_Words);
                $('.Selected-Total-Words').val(data.Selected_Total_Words);
                $('.Selected-Due-Words').val(data.Selected_Due_Words);
            },
            error: function (data) {
                alert(data.responseJSON);
            }
        });
    });

    $('.Add-Revision').on('click', function () {
        const getTask_ID = $(this).find('#Task_ID').val();
        $.ajax({
            url: '{{ route('Get.Edit.Task.Info') }}',
            type: 'GET',
            data: {
                'task_id': getTask_ID
            },
            success: function (data) {
                $('.Rev-DeadLine').val(data.Selected_Date);
                $('.Rev-Time').val(data.Selected_Time);
            },
            error: function (data) {
                alert(data.responseJSON);
            }
        });
    });

    $('.Order-Revision').on('click', function () {
        const getOrder_ID = $(this).find('#Order_ID').val();

        $.ajax({
            url: '{{ route('Get.Order.Rev.Info') }}',
            type: 'GET',
            data: {
                'Order_ID': getOrder_ID
            },
            success: function (data) {
                console.log(data);
                $('.Order-DeadLine').val(data.Selected_Date);
                $('.Order-Time').val(data.Selected_Time);
                $('.Order-Words').val(data.Selected_Words);
            },
            error: function (data) {
                alert(data.responseJSON);
            }
        });
    });

    var form = '#Chat-Form';

    $(form).on('submit', function (event) {
        event.preventDefault();

        var url = $(this).attr('data-action');
        var div = document.getElementById('Order-Messages');

        $.ajax({
            url: url,
            method: 'POST',
            data: new FormData(this),
            dataType: 'JSON',
            contentType: false,
            cache: false,
            processData: false,
            success: function (response) {
                $(form).trigger("reset");
                div.scrollTop = div.scrollHeight;
            },
            error: function (response) {
                alert(response);
            }
        });
    });

    $(document).on('click', '.popover-body ul li a.Message_Forward', function () {
        const Msg_ID = $(this).find('.Msg_ID').html();
        const Assign_ID = $(this).find('.Assign_ID').html();
        const Order_ID = $(this).find('.Order_ID').html();

        $.ajax({
            url: '{{ route('Forward.To.Executive') }}',
            type: 'GET',
            data: {
                'Msg_ID': Msg_ID,
                'Assign_ID': Assign_ID,
                'Order_ID': Order_ID,
            },
            success: function (data) {
                sendRequest(Order_ID);
                alert(data.message);
            },
            error: function (data) {
                console.log(data);
                alert(data);
            }
        });
    });

    // Get All Order Messages
    const Order_ID = {{ (int) $Research_Order->id }};

setInterval(function () {
    sendRequest(Order_ID);
}, 1000);

// Function to initialize the popover
function initializePopover() {
    $('[data-toggle="popover"]').popover({
        html: true,
        sanitize: false,
    })
    var popoverTriggerEl = document.querySelector('[data-bs-toggle="popover"]');
    var popoverInstance = new bootstrap.Popover(popoverTriggerEl);

    // Remove popover when clicking on the otherside
    document.addEventListener('click', function (event) {
        var targetElement = event.target;

        // Check if the clicked element is outside the popover and its trigger element
        if (!targetElement.closest('.popover') && targetElement !== popoverTriggerEl) {
            popoverInstance.hide();
        }
    });
}

function sendRequest(Order_ID) {

    $.ajax({
        url: '{{ route('Get.Messages') }}',
        type: 'GET',
        data: {
            'Order_ID': Order_ID
        },
        success: function (data) {
            $('#Order-Messages').html(data);
            initializePopover();
        }
    });
}

});
</script>
