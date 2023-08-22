<!--Page header-->
<div class="page-header d-xl-flex d-block">
    <div class="page-leftheader">
        <h4 class="page-title">All Registered<span class="font-weight-normal text-muted ms-2">Employee</span></h4>
    </div>
</div>
<!--End Page header-->
<div class="card">
    <div class="card-body pb-2">
        <div class="row">
            <div class="col mb-4">
                <a href="{{ route('New.Employee') }}" class="btn btn-primary"><i class="fe fe-plus"></i> Add New
                    User</a>
                <a href="{{ route('Delete.All.Employee') }}" class="btn btn-danger">
                    <i class="fe fe-trash-2"></i> Delete All Users</a>
            </div>
            <div class="col col-auto mb-4">
                <div class="form-group w-100">
                    <div class="input-icon">
                        <span class="input-icon-addon">
							<i class="fe fe-search"></i>
						</span>
                        <input type="text" class="form-control" placeholder="Search User">
                    </div>
                </div>
            </div>
        </div>
        @include('partials.fetch-users', ['Users' => $All_Users])
    </div>

</div>
