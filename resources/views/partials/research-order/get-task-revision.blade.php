@foreach ($data as $row)
    <div class="card">
        <div class="card-header border-0 d-flex justify-content-between">
            <div class="card-title">Created At => {{ $row->created_at }}</div>
            <div class="card-title">Created By => {{ $row->revision_by->basic_info->full_name }}</div>
        </div>
        <div class="card-body">
            {!! $row->Task_Revision !!}
        </div>

        @if ($row->attachments->count() > 0)
            <h4>Revision Attachments</h4>
            <div class="card-footer">
                <div class="table-responsive">
                    <table class="table table-bordered text-nowrap border-bottom">
                        <tbody>
                        @foreach ($row->attachments as $attach)
                            <tr>
                                <td>{{ $attach->File_Name }}</td>
                                <td><a href="{{ asset($attach->file_path) }}">Download File</a></td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif
    </div>
@endforeach
