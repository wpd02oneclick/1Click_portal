<!--Page header-->
<div class="page-header d-xl-flex d-block">
    <div class="page-leftheader">
        <h4 class="page-title">Notice Board</h4>
    </div>
    <div class="page-rightheader ms-md-auto">
        <div class="d-flex align-items-end flex-wrap my-auto end-content breadcrumb-end">
            <div class="btn-list">
                <a href="#" class="btn btn-primary me-3" data-bs-toggle="modal" data-bs-target="#addnoticemodal">Add New Notice</a>
            </div>
        </div>
    </div>
</div>
<!--End Page header-->
<!-- Row -->
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header  border-0">
                <h4 class="card-title">Notice Summary</h4>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table  table-vcenter text-nowrap table-bordered border-bottom" id="hr-notice">
                        <thead>
                        <tr>
                            <th class="border-bottom-0 w-5">No</th>
                            <th class="border-bottom-0">Title</th>
                            <th class="border-bottom-0">Description</th>
                            <th class="border-bottom-0">Create On</th>
                            <th class="border-bottom-0">Action</th>
                        </tr>
                        </thead>
                        <tbody>

                        @foreach ($Get_Notice as $Notice)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $Notice->Notice_Title }}</td>
                                <td>{{ substr($Notice->Notice_Desc, 0, 50) }}</td>
                                <td>{{ $Notice->created_at  }}</td>
                                <td>
                                    <div class="d-flex">
                                        <a href="{{ route('Delete.Notice' , ['Notice_ID' => $Notice->id]) }}" class="action-btns1" data-bs-toggle="tooltip" data-bs-placement="top" title="Delete"><i class="feather feather-trash-2 text-danger"></i></a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- End Row -->
<div class="modal fade" id="addnoticemodal">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add New Notice</h5>
                <button class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <form action="{{ route('Post.Notice') }}" method="POSt">
                @csrf
                <div class="modal-body">
                    <div class="form-group" id="title-text">
                        <label class="form-label">Title</label>
                        <input class="form-control" type="text" name="Notice_Title">
                    </div>
                    <div class="form-group" id="title-select-box">
                        <label class="form-label">Title</label>
                        <select name="Notice_Titles" id="" class="form-control">
                            <Option value="Office Timings Changed">Office Timings Changed</Option>
                            <Option value="Updated the Company Policy">Updated the Company Policy</Option>
                            <Option value="Meeting">Meeting</Option>
                            <Option value="Other">Other</Option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Description:</label>
                        <textarea name="Notice_Desc" class="form-control" id="description" cols="30" rows="10"></textarea>
                    </div>
                    <div class="custom-controls-stacked d-md-flex">
                        <label class="form-label mt-1 me-5">To</label>
                        <label class="custom-control custom-radio success me-4">
                            <input type="radio" class="custom-control-input" name="status" id="single_user" value="0">
                            <span class="custom-control-label">Single Employee</span>
                        </label>
                        <label class="custom-control custom-radio success">
                            <input type="radio" class="custom-control-input" name="status" id="All_user" value="1">
                            <span class="custom-control-label">All Employee</span>
                        </label>
                    </div>
                    <div class="form-group mt-4" id="Select_single_User">
                        <label class="form-label"> Select User</label>
                        <select class="form-control select2-show-search custom-select select2"
                                data-placeholder="Select Users" name="User_ID">
                            <option label="Select Users"></option>
                            @foreach($Get_Users as $user)
                                <option value="{{$user->id}}">{{ $user->basic_info->full_name}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="  w-100 btn btn-success">Add Notice</button>
                </div>
            </form>

        </div>
    </div>
</div>



<script>
    $(document).ready(function() {
        $('#title-text').hide();
        $('#Select_single_User').hide();

        $('select[name="Notice_Titles"]').change(function() {
            const selectedValue = $(this).val();
            if (selectedValue === 'Other') {
                $('#title-text').show();
                $('#title-select-box').val('Other');
            }else{
                $('#title-text').hide();
            }
        });
        $('#single_user').on('click', function() {
            $('#Select_single_User').show();
        })
        $('#All_user').on('click', function() {
            $('#Select_single_User').hide();
        })
    });

</script>
