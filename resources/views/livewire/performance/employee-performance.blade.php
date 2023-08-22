<!--Page header-->
<div class="page-header d-xl-flex d-block">
    <div class="page-leftheader">
        <h4 class="page-title">Users <span class="font-weight-normal text-muted ms-2">Performances</span></h4>
    </div>
</div>
<!--End Page header-->
<div class="card">
    <div class="tab-menu-heading hremp-tabs p-0 ">
        <div class="tabs-menu1">
            <!-- Tabs -->
            <ul class="nav panel-tabs">
                <li class="ms-4"><a href="#tab5" class="active" data-bs-toggle="tab">Research Writers</a></li>
                <li><a href="#tab6" data-bs-toggle="tab">Coordinators</a></li>
                <li><a href="#tab7" data-bs-toggle="tab">Managers</a></li>
                <li><a href="#tab8" data-bs-toggle="tab">Content Writers</a></li>
                <li><a href="#tab9" data-bs-toggle="tab">Human Resources</a></li>
            </ul>
        </div>
    </div>
    <div class="panel-body tabs-menu-body hremp-tabs1 p-0">
        <div class="tab-content">
            <div class="tab-pane active" id="tab5">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-vcenter text-nowrap table-bordered border-bottom"
                               id="responsive-datatable">
                            <thead>
                            <tr>
                                <th class="border-bottom-0 text-center">No</th>
                                <th class="border-bottom-0">Name</th>
                                <th class="border-bottom-0">Target Word</th>
                                <th class="border-bottom-0">Achieve Word</th>
                                <th class="border-bottom-0">Percentage</th>
                                <th class="border-bottom-0">Cancel Word</th>
                                <th class="border-bottom-0">Cancel Percentage</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($Research_Writer_Performance as $month => $performances)
                                {{--                                <tr>--}}
                                {{--                                    <th colspan="7" class="text-center">{{ $month }}</th>--}}
                                {{--                                </tr>--}}
                                @foreach ($performances as $Info)
                                    @if(empty($Info->bench_mark))
                                        @continue
                                    @endif
                                    @php
                                        $benchMark = isset($Info->bench_mark[0]['Bench_Mark'])? number_format($Info->bench_mark[0]['Bench_Mark']) : 0;
                                    @endphp
                                    <tr>
                                        <td class="text-center">{{ $Info->EMP_ID }}</td>
                                        <td>{{ $Info->basic_info->full_name }}</td>
                                        <td>{{ $benchMark }}</td>
                                        <td>{{ number_format($Info->stats_sum_completed) }}</td>
                                        <td>{{ PortalHelpers::getPercentage($Info->stats_sum_completed, $benchMark) }}</td>
                                        <td>{{ number_format($Info->stats_sum_canceled) }}</td>
                                        <td>{{ PortalHelpers::getPercentage($Info->stats_sum_canceled, $Info->stats_sum_completed) }}</td>
                                    </tr>
                                @endforeach
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="tab-pane" id="tab6">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-vcenter text-nowrap table-bordered border-bottom"
                               id="responsive-datatable">
                            <thead>
                            <tr>
                                <th class="border-bottom-0 text-center">No</th>
                                <th class="border-bottom-0">Name</th>
                                <th class="border-bottom-0">Target Word</th>
                                <th class="border-bottom-0">Achieve Word</th>
                                <th class="border-bottom-0">Percentage</th>
                                <th class="border-bottom-0">Cancel Word</th>
                                <th class="border-bottom-0">Cancel Percentage</th>
                                <th class="border-bottom-0">Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($Coordinator_Performance as $Info)
                                <tr>
                                    <td class="text-center">{{ $Info['EMP_ID'] }}</td>
                                    <td>
                                        {{ $Info['Name'] }}
                                    </td>
                                    <td>
                                        {{ number_format($Info['Assign_Words']) }}
                                    </td>
                                    <td>
                                        {{ number_format($Info['Achieve_Words']) }}
                                    </td>
                                    <td>
                                        {{ PortalHelpers::getPercentage($Info['Achieve_Words'], $Info['Assign_Words']) }}
                                    </td>
                                    <td>
                                        {{ number_format($Info['Cancel_Words']) }}
                                    </td>
                                    <td>
                                        {{ PortalHelpers::getPercentage($Info['Cancel_Words'], $Info['Achieve_Words']) }}
                                    </td>
                                    <td>
                                        <div class="d-flex">
                                            <a href="#" class="action-btns1" data-bs-toggle="tooltip"
                                               data-bs-placement="top" title="View"><i
                                                    class="feather feather-eye  text-primary"></i></a>
                                            <a href="#" class="action-btns1" data-bs-toggle="tooltip"
                                               data-bs-placement="top" title="Edit"><i
                                                    class="feather feather-edit-2  text-success"></i></a>
                                            <a href="#" class="action-btns1" data-bs-toggle="tooltip"
                                               data-bs-placement="top" title="Delete"><i
                                                    class="feather feather-trash-2 text-danger"></i></a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="tab-pane" id="tab7">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-vcenter text-nowrap table-bordered border-bottom"
                               id="responsive-datatable">
                            <thead>
                            <tr>
                                <th class="border-bottom-0 text-center">No</th>
                                <th class="border-bottom-0">Name</th>
                                <th class="border-bottom-0">Target Word</th>
                                <th class="border-bottom-0">Achieve Word</th>
                                <th class="border-bottom-0">Percentage</th>
                                <th class="border-bottom-0">Cancel Word</th>
                                <th class="border-bottom-0">Cancel Percentage</th>
                                <th class="border-bottom-0">Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($Manager_Performance as $Info)
                                <tr>
                                    <td class="text-center">{{ $Info['EMP_ID'] }}</td>
                                    <td>
                                        {{ $Info['Name'] }}
                                    </td>
                                    <td>
                                        {{ number_format($Info['Assign_Words']) }}
                                    </td>
                                    <td>
                                        {{ number_format($Info['Achieve_Words']) }}
                                    </td>
                                    <td>
                                        {{ PortalHelpers::getPercentage($Info['Achieve_Words'], $Info['Assign_Words']) }}
                                    </td>
                                    <td>
                                        {{ number_format($Info['Cancel_Words']) }}
                                    </td>
                                    <td>
                                        {{ PortalHelpers::getPercentage($Info['Cancel_Words'], $Info['Achieve_Words']) }}
                                    </td>
                                    <td>
                                        <div class="d-flex">
                                            <a href="#" class="action-btns1" data-bs-toggle="tooltip"
                                               data-bs-placement="top" title="View"><i
                                                    class="feather feather-eye  text-primary"></i></a>
                                            <a href="#" class="action-btns1" data-bs-toggle="tooltip"
                                               data-bs-placement="top" title="Edit"><i
                                                    class="feather feather-edit-2  text-success"></i></a>
                                            <a href="#" class="action-btns1" data-bs-toggle="tooltip"
                                               data-bs-placement="top" title="Delete"><i
                                                    class="feather feather-trash-2 text-danger"></i></a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="tab-pane" id="tab8">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-vcenter text-nowrap table-bordered border-bottom"
                               id="responsive-datatable">
                            <thead>
                            <tr>
                                <th class="border-bottom-0 text-center">No</th>
                                <th class="border-bottom-0">Name</th>
                                <th class="border-bottom-0">Target Word</th>
                                <th class="border-bottom-0">Achieve Word</th>
                                <th class="border-bottom-0">Percentage</th>
                                <th class="border-bottom-0">Cancel Word</th>
                                <th class="border-bottom-0">Cancel Percentage</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($Content_Writer_Performance as $month => $performances)
                                {{--                                <tr>--}}
                                {{--                                    <td colspan="7" class="text-center">{{ $month }}</td>--}}
                                {{--                                </tr>--}}
                                @foreach($performances as $Info)
                                    @if(empty($Info->bench_mark))
                                        @continue
                                    @endif
                                    <tr>
                                        <td class="text-center">{{ $Info->EMP_ID }}</td>
                                        <td>{{ $Info->basic_info->full_name }}</td>
                                        <td>{{ number_format($Info->bench_mark[0]['Bench_Mark']) }}</td>
                                        <td>{{ number_format($Info->stats_sum_completed) }}</td>
                                        <td>{{ PortalHelpers::getPercentage($Info->stats_sum_completed, $Info->bench_mark[0]['Bench_Mark']) }}</td>
                                        <td>{{ number_format($Info->stats_sum_canceled) }}</td>
                                        <td>{{ PortalHelpers::getPercentage($Info->stats_sum_canceled, $Info->stats_sum_completed) }}</td>
                                    </tr>
                                @endforeach
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="tab-pane" id="tab9">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-vcenter text-nowrap table-bordered border-bottom"
                               id="responsive-datatable">
                            <thead>
                            <tr>
                                <th class="border-bottom-0 text-center">No</th>
                                <th class="border-bottom-0">Name</th>
                                <th class="border-bottom-0">Target Hiring</th>
                                <th class="border-bottom-0">Achieve Hiring</th>
                                <th class="border-bottom-0">Percentage</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($Hr_Performance as $Info)
                                @if($Info->bench_mark->isEmpty())
                                    @continue
                                @endif
                                <tr>
                                    <td class="text-center">{{ $Info->EMP_ID }}</td>
                                    <td>{{ $Info->basic_info->full_name }}</td>
                                    <td>{{ number_format($Info->bench_mark[0]['Bench_Mark']) }}</td>
                                    <td>{{ number_format($Info->users_count) }}</td>
                                    <td>{{ PortalHelpers::getPercentage($Info->users_count, $Info->bench_mark[0]['Bench_Mark']) }}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
