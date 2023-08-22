<div class="card mt-4">
    <div class="card-header border-bottom-0">
        <div class="card-title">Portal Notifications</div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered text-nowrap border-bottom" id="responsive-datatable">
                <thead>
                <tr>
                    <th class="wd-15p border-bottom-0">S.No</th>
                    <th class="wd-15p border-bottom-0">Order ID</th>
                    <th class="wd-15p border-bottom-0">Read At</th>
                    <th class="wd-15p border-bottom-0">Message</th>
                    <th class="wd-25p border-bottom-0">Action</th>
                </tr>
                </thead>
                <tbody>
                @forelse($notifications as $notification)
                    <tr>
                        <td><strong>{{ $loop->iteration }}</strong></td>
                        <td>
                            <h6 class="mb-1 fs-16"><a
                                    href="{!! $notification['route'] !!}">{{ $notification['Order_ID'] }}</a>
                            </h6>
                        </td>
                        <td>
                            {{ $notification['read_at'] }}
                        </td>
                        <td>{!! $notification['Message'] !!}</td>
                        <td>
                            <div class="btn-list">
                                <div class="dropdown">
                                    <button class="btn btn-danger dropdown-toggle px-5"
                                            data-bs-toggle="dropdown">
                                        <i class="fe fe-trash-2 me-2"></i>Actions
                                    </button>
                                    <div class="dropdown-menu">
                                        <a class="dropdown-item" href="JavaScript:void(0);">View</a>
                                    </div>
                                </div>
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
