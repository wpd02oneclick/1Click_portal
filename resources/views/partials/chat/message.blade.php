<div class="d-flex justify-content-start">
{{--    <div class="img_cont_msg">--}}
{{--        <img src="{{ $photo }}" class="rounded-circle user_img_msg" alt="img">--}}
{{--    </div>--}}
    <div class="msg_cotainer">
        <h6 class="mb-1 fs-16">
            @if ($message->authorized_user->designation)
                @if((int)Auth::guard('Authorized')->user()->Role_ID !== 1)
                    {{ (!empty(PortalHelpers::chatSenderName((int) $message->authorized_user->Role_ID)))? PortalHelpers::chatSenderName((int) $message->authorized_user->Role_ID) : $message->authorized_user->designation->Designation_Name }}
                @else
                    {{ $message->authorized_user->basic_info->full_name }}
                @endif
            @endif
        </h6>
        {{ $message->User_Message }}
        <span class="msg_time fs-8">{{ $messageDate->format('H:i:s A') }}</span>
    </div>
</div>
@if (in_array((int)$message->authorized_user->Role_ID, [9, 10, 11], true))
    @if(in_array((int)Auth::guard('Authorized')->user()->Role_ID, [4, 5], true))
        <h6 class="m-3 fs-14">
            <a href="JavaScript:void(0);" class="ForwardTag" data-bs-container="body"
               data-bs-content="{!! $executiveList !!}"
               data-bs-placement="right" data-bs-html="true"
               data-bs-popover-color="default" data-bs-toggle="popover" title="Executive List">
                Forward To Executive
            </a>
        </h6>
    @endif
@endif
