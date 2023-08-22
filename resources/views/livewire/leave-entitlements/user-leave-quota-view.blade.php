<!--Page header-->
<div class="page-header d-xl-flex d-block">
    <div class="page-leftheader">
        <h4 class="page-title">Attendance View</h4>
    </div>
</div>
<!--End Page header-->
<!-- Row-->
<div class="row">
    <div class="col-xl-12 col-md-12 col-lg-12">
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table  table-vcenter text-nowrap table-bordered border-bottom table-striped" id="hr-attendance">
                        <thead>
                        <tr class="text-center">
                            <th class="border-bottom-0 ">S.No.</th>
                            <th class="border-bottom-0 ">Emp ID</th>
                            <th class="border-bottom-0 ">Emp Name</th>
                            <th class="text-center border-bottom-0 ">Last Absent</th>
                            <th class="text-center border-bottom-0 ">Half Day</th>
                            <th class="text-center border-bottom-0 ">Sick</th>
                            <th class="text-center border-bottom-0 ">Casual</th>
                            <th class="text-center border-bottom-0 ">Annual</th>
                            <th class="text-center border-bottom-0 ">Unpaid</th>
                            <th class="text-center border-bottom-0 ">Remaining</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($Leave_Quota as $Leave)
                            <tr>
                                <td><strong>{{ $loop->iteration }}</strong></td>
                                <td>{{ $Leave['EMP_ID'] }}</td>
                                <td>
                                    <div class="d-flex">
                                        <span class="avatar avatar brround me-3" style="background-image: url({{ $Leave['Profile_Photo'] }})"></span>
                                        <div class="me-3 mt-0 mt-sm-2 d-block">
                                            <h6 class="mb-1 fs-14">{{ $Leave['EMP_Name'] }}</h6>
                                        </div>
                                    </div>
                                </td>
                                <td class="text-center"><span class="badge badge-danger-light">4 days</span></td>
                                <td class="text-center">{{ $Leave['Half_Day'] }}</td>
                                <td class="text-center">{{ $Leave['Sick_Leaves'] }}</td>
                                <td class="text-center">{{ $Leave['Casual_Leaves'] }}</td>
                                <td class="text-center">{{ $Leave['Annual_Leaves'] }}</td>
                                <td class="text-center">{{ $Leave['Un_Paid'] }}</td>
                                <td class="text-center"><span class="badge badge-success">{{ $Leave['Remaining'] }}</span></td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
