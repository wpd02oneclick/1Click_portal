@if((int) $Role_ID === 6 || (int) $Role_ID === 7 || (int) $Role_ID === 12)
    <!--Page header-->
    <div class="page-header d-xl-flex d-block">
        <div class="page-leftheader">
            <h4 class="page-title">My <span class="font-weight-normal text-muted ms-2">Performance</span></h4>
        </div>
    </div>
    <!--End Page header-->
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-vcenter text-nowrap table-bordered border-bottom"
                       id="responsive-datatable">
                    <thead>
                    <tr>
                        <th class="border-bottom-0 text-center">Order ID</th>
                        <th class="border-bottom-0">Target Word</th>
                        <th class="border-bottom-0">Achieve Word</th>
                        <th class="border-bottom-0">Percentage</th>
                        <th class="border-bottom-0">Cancel Word</th>
                        <th class="border-bottom-0">Cancel Percentage</th>
                    </tr>
                    </thead>
                    <tbody>
                    @if(!empty($User_Performance))
                        @forelse ($User_Performance['stats'] as $Info)
                            @if(empty($User_Performance['bench_mark']))
                                @continue
                            @endif
                            @php
                                $benchMark = isset($User_Performance['bench_mark'][0]['Bench_Mark'])? number_format((double) $User_Performance['bench_mark'][0]['Bench_Mark']) : 0;
                            @endphp
                            <tr>
                                <td class="text-center">
                                    {{ $Info['order_info']['Order_ID'] }}
                                </td>
                                <td>
                                    {{ $benchMark }}
                                </td>
                                <td>
                                    {{ number_format($Info['Completed']) }}
                                </td>
                                <td>
                                    {{ PortalHelpers::getPercentage((double)$Info['Completed'], $benchMark) }}
                                </td>
                                <td>
                                    {{ (double)$Info['Canceled'] }}
                                </td>
                                <td>
                                    {{ PortalHelpers::getPercentage((double)$Info['Canceled'], $Info['Completed']) }}
                                </td>
                            </tr>
                        @empty
                        @endforelse
                    @endif
                    </tbody>

                </table>
            </div>
        </div>
    </div>
@endif

@if((int) $Role_ID === 5)
    <!--Page header-->
    <div class="page-header d-xl-flex d-block">
        <div class="page-leftheader">
            <h4 class="page-title">My <span class="font-weight-normal text-muted ms-2">Writer Performances</span></h4>
        </div>
    </div>
    <!--End Page header-->
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-vcenter text-nowrap table-bordered border-bottom" id="responsive-datatable">
                    <thead>
                    <tr>
                        <th class="border-bottom-0 text-center">EMP ID</th>
                        <th class="border-bottom-0 text-center">Name</th>
                        <th class="border-bottom-0 text-center">Order ID</th>
                        <th class="border-bottom-0">Target Word</th>
                        <th class="border-bottom-0">Achieve Word</th>
                        <th class="border-bottom-0">Percentage</th>
                        <th class="border-bottom-0">Cancel Word</th>
                        <th class="border-bottom-0">Cancel Percentage</th>
                        <th class="border-bottom-0">Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($User_Performance as $user)
                        @if (isset($user->writers) && count($user->writers) > 0)
                            @foreach ($user->writers as $key => $writer)
                                @if (empty($writer['assign_task'][$key]['stats']['Completed']) || empty($writer['bench_mark']))
                                    @continue
                                @endif
                                @php
                                    $benchMark = isset($writer['bench_mark'][0]['Bench_Mark'])? number_format((double) $writer['bench_mark'][0]['Bench_Mark']) : 0;
                                @endphp
                                <tr>
                                    <td class="text-center">
                                        {{ $writer['EMP_ID'] }}
                                    </td>
                                    <td class="text-center">
                                        {{ $writer->basic_info->full_name }}
                                    </td>
                                    <td class="text-center">
                                        {{ $writer['assign_task'][$key]['order_info']['Order_ID'] }}
                                    </td>
                                    <td>
                                        {{ $benchMark }}
                                    </td>
                                    <td>
                                        {{ number_format($writer['assign_task'][$key]['stats']['Completed']) }}
                                    </td>
                                    <td>
                                        {{ PortalHelpers::getPercentage($writer['assign_task'][$key]['stats']['Completed'], $benchMark) }}
                                    </td>
                                    <td>
                                        {{ number_format($writer['assign_task'][$key]['stats']['Canceled']) }}
                                    </td>
                                    <td>
                                        {{ PortalHelpers::getPercentage($writer['assign_task'][$key]['stats']['Canceled'], $writer['assign_task'][$key]['stats']['Completed']) }}
                                    </td>
                                    <td>
                                        <div class="d-flex">
                                            <a href="{{ route('User.Performance', ['EMP_ID' => $writer['EMP_ID'], 'Role_ID' => $writer['Role_ID']]) }}"
                                               class="action-btns1" data-bs-toggle="tooltip"
                                               data-bs-placement="top" title="View">
                                                <i class="feather feather-eye text-primary"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                    @endforeach
                    </tbody>

                </table>
            </div>
        </div>
    </div>
@endif

@if((int) $Role_ID === 4)
    <!--Page header-->
    <div class="page-header d-xl-flex d-block">
        <div class="page-leftheader">
            <h4 class="page-title">My <span class="font-weight-normal text-muted ms-2">Coordinators Performances</span>
            </h4>
        </div>
    </div>
    <!--End Page header-->
    <div class="card">
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
                    @foreach($User_Performance as $Info)
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
                                    <a href="{{ route('User.Performance', ['EMP_ID' => $Info['EMP_ID'], 'Role_ID' => 5]) }}"
                                       class="action-btns1" data-bs-toggle="tooltip"
                                       data-bs-placement="top" title="View"><i
                                            class="feather feather-eye  text-primary"></i>
                                    </a>
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
