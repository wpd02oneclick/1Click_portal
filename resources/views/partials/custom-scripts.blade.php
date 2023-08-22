<script>
    (function ($) {
        $('form').attr('autocomplete', 'off');
        $('input').attr('autocomplete', 'off');

        $('.Phone-Number').mask("(+99) 999 9999999");
        $('.CNIC-Number').mask("99999 - 9999999 - 9");
        // $('input[type="search"]').attr('autocomplete','off');

        //________ Datepicker
        // $(".fc-datepicker").each(function() {
        //     // Change the "type" attribute to "date"
        //     $(this).attr("type", "date");
        // });
        // Date Validation
        var today = new Date();
        var dd = String(today.getDate()).padStart(2, '0');
        var mm = String(today.getMonth() + 1).padStart(2, '0');
        var yyyy = today.getFullYear();

        today = yyyy + '-' + mm + '-' + dd;
        $('input[type="date"]').attr('min', today);

        // $('.fc-datepicker-2').datepicker({
        //     dateFormat: "mm/dd/yy",
        //     zIndex: 9999998,
        // });



        function showLoader() {
            var html = '<div class="dimmer active loader"><div class="spinner4"><div class="bounce1"></div><div class="bounce2"></div><div class="bounce3"></div></div></div>';
            var loader = $(html);
            $('body').append(loader);
        }
        // $('form').submit(function(e) {
        //     e.preventDefault(); // Prevent normal form submission
        //
        //     // var formToken = $('[name="form_token"]').val();
        //     var submitButton = $('button[type="submit"]');
        //
        //     // Disable the submit button
        //     submitButton.prop('disabled', true);
        //
        //     // Perform form validation
        //     var isFormValid = true;
        // // , form :selected[required]
        //     $('form :input[required]').each(function(e) {
        //             console.log($(this).val() + ' Null' + e);
        //         if ($(this).val() === null) {
        //             isFormValid = false;
        //             return false; // Exit the loop if any required field is empty
        //         }
        //     });
        //
        //     if (!isFormValid) {
        //         // Enable the submit button and return
        //         submitButton.prop('disabled', false);
        //         return;
        //     }
        //     submitButton.addClass("btn-loading");
        //     showLoader();
        //     this.submit();
        // });

    })(jQuery);
</script>
<!-- Include Laravel Echo and Pusher libraries -->
{{--    <script>--}}
{{--        import Echo from 'laravel-echo';--}}
{{--        import Pusher from 'pusher-js';--}}
{{--        // Import the sound file--}}
{{--        const notificationSound = '{{ asset('notification.mp3') }}';--}}
{{--        // Initialize Laravel Echo--}}
{{--        const authUser = {!! json_encode(Auth::user(), JSON_THROW_ON_ERROR) !!};--}}
{{--        console.log(authUser);--}}
{{--        window.Echo = new Echo({--}}
{{--            broadcaster: 'pusher',--}}
{{--            key: '{{ env('PUSHER_APP_KEY') }}',--}}
{{--            cluster: '{{ env('PUSHER_APP_CLUSTER') }}',--}}
{{--            encrypted: true,--}}
{{--            forceTLS: true,--}}
{{--            auth: {--}}
{{--                headers: {--}}
{{--                    Authorization: 'Bearer ' + authUser.api_token,--}}
{{--                },--}}
{{--            },--}}
{{--        });--}}
{{--        window.Echo.private('App.User.' + authUser.id)--}}
{{--            .notification((notification) => {--}}
{{--                console.log(notification);--}}
{{--                if (notification.data.Play_Sound) {--}}
{{--                    // Create a new audio element and play the sound--}}
{{--                    const audio = new Audio(notificationSound);--}}
{{--                    audio.play();--}}
{{--                }--}}
{{--                // Handle the rest of your notification logic here--}}
{{--            });--}}
{{--    </script>--}}
{{--<script src="{{ asset('js/notification.js') }}"></script>--}}

@if (Session::has('Success!'))
    <script>
        $('.loader').remove();
        not1('{!! Session::get('Success!') !!}');
    </script>
@endif
@if (Session::has('Error!'))
    <script>
        $('.loader').remove();
        not2('{!! Session::get('Error!') !!}');
    </script>
@endif
@if (Session::has('Warning!'))
    <script>
        $('.loader').remove();
        not3('{!! Session::get('Warning!') !!}');
    </script>
@endif
@if (Session::has('Info!'))
    <script>
        $('.loader').remove();
        not4('{!! Session::get('Info!') !!}');
    </script>
@endif
