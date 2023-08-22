<div class="row">
    @forelse($All_Users as $User)
        <div class="col-xl-4 col-lg-6">
            <div class="card border p-0 shadow-none">
                <div class="d-flex align-items-center p-4">
                    <div class="avatar avatar-lg brround d-block cover-image"
                         data-image-src="{{ asset('assets/images/users/7.jpg') }}">
                    </div>
                    <div class="wrapper ms-3">
                        <p class="mb-0 mt-1 text-dark font-weight-semibold">{{ $User->basic_info->full_name }}</p>
                        <small class="text-muted">{{ $User->User_Role }}</small>
                    </div>
                    <div class="float-end ms-auto">
                        <div class="btn-group ms-3 mb-0">
                            <a href="#" class="option-dots" data-bs-toggle="dropdown"
                               aria-haspopup="true"
                               aria-expanded="false">
                                <i class="fa fa-ellipsis-v"></i>
                            </a>
                            <div class="dropdown-menu dropdown-menu-start">
                                <a class="dropdown-item"
                                   href="{{ route('View.Employee', ['EMP_ID' => $User->EMP_ID]) }}">
                                    <i class="fe fe-edit me-2"></i>
                                    Edit</a>
                                @if(empty($User->deleted_at) && $User->Role_ID !== 1)
                                    <a class="dropdown-item" href="{{ route('Delete.Employee', ['EMP_ID' => $User->EMP_ID]) }}">
                                        <i class="fe fe-trash me-2"></i>
                                        Delete</a>
                                @endif
                                @if(!empty($User->deleted_at))
                                    <a class="dropdown-item" href="{{ route('Restore.Employee', ['EMP_ID' => $User->EMP_ID]) }}">
                                        <i class="fe fe-trash me-2"></i>
                                        Restore</a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body border-top">
                    <div class="d-flex mb-3">
                        <span class="icon-style-circle1 ri-user-2-fill fs-18"></span>
                        <div class="h6 mb-0 ms-3 mt-1">Created By
                            => {{ empty($User->createdBy->basic_info->full_name)? 'Admin' : $User->createdBy->basic_info->full_name }}</div>
                    </div>
                    <div class="d-flex">
                        <span class="icon-style-circle1 ri-message-2-line fs-18"></span>
                        <div class="h6 mb-0 ms-3 mt-1">{{ $User->email }}</div>
                    </div>
                    <div class="d-flex">
                        <span class="icon-style-circle1 ri-phone-line fs-18"></span>
                        <div class="h6 mb-0 ms-3 mt-1">{{ $User->basic_info->Phone_Number }}</div>
                    </div>
                    @if(!empty($User->deleted_at))
                        <div class="d-flex text-danger">
                            <span class="icon-style-circle1 ti-trash fs-18"></span>
                            <div class="h6 mb-0 ms-3 mt-1">{{ $User->deleted_at }}</div>
                        </div>
                    @endif
                </div>
                <div class="card-footer">
                    <a href="{{ route('View.Employee', ['EMP_ID' => $User->EMP_ID]) }}"
                       class="btn btn-primary btn-sm btn-block">View
                        Profile</a>
                </div>
            </div>
        </div>
    @empty
        <h4>Users are Not Found!!</h4>
    @endforelse
</div>
