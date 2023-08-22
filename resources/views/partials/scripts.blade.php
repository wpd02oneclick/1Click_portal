<!-- Jquery js-->
{{--<script src="{{ asset('assets/plugins/jquery/jquery.min.js') }}"></script>--}}
<!-- Moment js-->
<script src="{{ asset('assets/plugins/moment/moment.js') }}"></script>
<!-- Bootstrap js-->
<script src="{{ asset('assets/plugins/bootstrap/js/popper.min.js') }}"></script>
<script src="{{ asset('assets/plugins/bootstrap/js/bootstrap.min.js') }}"></script>
<!-- Sidemen js-->
<script src="{{ asset('assets/plugins/sidemenu/sidemenu.js') }}"></script>
<!-- P-scroll js-->
<script src="{{ asset('assets/plugins/p-scrollbar/p-scrollbar.js') }}"></script>
<script src="{{ asset('assets/plugins/p-scrollbar/p-scroll1.js') }}"></script>
<!-- Sidebar js-->
<script src="{{ asset('assets/plugins/sidebar/sidebar.js') }}"></script>
<!-- Select2 js -->
<script src="{{ asset('assets/plugins/select2/select2.full.min.js') }}"></script>
<!-- Apex-chart js-->
<script src="{{ asset('assets/plugins/apexchart/apexcharts.js') }}"></script>
<!-- Vertical-scroll js-->
<script src="{{ asset('assets/plugins/vertical-scroll/jquery.bootstrap.newsbox.js') }}"></script>
<script src="{{ asset('assets/plugins/vertical-scroll/vertical-scroll.js') }}"></script>
<!-- Datepicker js -->
{{--<script src="{{ asset('assets/plugins/date-picker/jquery-ui.js') }}"></script>--}}
{{--<script src="{{ asset('assets/plugins/bootstrap-datepicker/bootstrap-datepicker.js') }}"></script>--}}
<script src="{{ asset('assets/plugins/modal-datepicker/datepicker.js') }}"></script>
<!-- Timepicker js -->
<script src="{{ asset('assets/plugins/time-picker/jquery.timepicker.js') }}"></script>
<script src="{{ asset('assets/plugins/time-picker/toggles.min.js') }}"></script>
<!-- Chart scripts -->
<script src="{{ asset('assets/plugins/chart.min/chart.min.js') }}"></script>
<script src="{{ asset('assets/plugins/chart.min/rounded-barchart.js') }}"></script>
<!-- Data tables -->
<script src="{{ asset('assets/plugins/datatable/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('assets/plugins/datatable/js/dataTables.bootstrap5.js') }}"></script>
<script src="{{ asset('assets/plugins/datatable/js/dataTables.buttons.min.js') }}"></script>
<script src="{{ asset('assets/plugins/datatable/js/buttons.bootstrap5.min.js') }}"></script>
<script src="{{ asset('assets/plugins/datatable/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('assets/plugins/datatable/responsive.bootstrap5.min.js') }}"></script>
<script src="{{ asset('assets/js/datatables.js') }}"></script>
<!-- Summernote js -->
<script src="{{ asset('assets/plugins/summer-note/summernote1.js') }}"></script>
<script src="{{ asset('assets/js/summernote.js') }}"></script>
<!-- Notifications js -->
<script src="{{ asset('assets/plugins/notify/js/rainbow.js') }}"></script>
<script src="{{ asset('assets/plugins/notify/js/sample.js') }}"></script>
<script src="{{ asset('assets/plugins/notify/js/jquery.growl.js') }}"></script>
<script src="{{ asset('assets/plugins/notify/js/notifIt.js') }}"></script>
<!-- Popover js -->
<script src="{{ asset('assets/js/popover.js') }}"></script>
<!-- Custom js-->
<script src="{{ asset('assets/js/formelementadvnced.js') }}"></script>
<script src="{{ asset('assets/js/form-elements.js') }}"></script>
<script src="{{ asset('assets/js/index6.js') }}"></script>
<script src="{{ asset('assets/js/hr/hr-attlist.js') }}"></script>
<script src="{{ asset('assets/js/custom.js') }}"></script>
<script src="{{ asset('assets/js/select2.js') }}"></script>
<script>
    // Get All Notifications
    getNotifications();
    setInterval(function() {
        getNotifications();
    }, 5000);

    function getNotifications() {
        $.ajax({
            url: '{{ route('Get.Portal.Notifications') }}',
            type: 'GET',
            success: function(data) {
                $('.Portal-Notification').html(data.html);
            },
            error: function (data) {
                console.log(data.error);
            }
        });
    }

    $(document).on('click', '.MarkRead', function () {
        const Notify_ID = $(this).data('notification-id'); // Use data() to retrieve the attribute value
        $.ajax({
            url: '{{ route('Mark.Read') }}',
            type: 'GET',
            data: {
                'Notify_ID': Notify_ID
            },
            success: function (data) {
                getNotifications();
            },
            error: function (data) {
                alert(data.error);
            }
        });
    });

    $(document).on('click', '.MarkReadAll', function () {
        const Notify_ID = $(this).find('input').val();
        $.ajax({
            url: '{{ route('Mark.Read.All') }}',
            type: 'GET',
            data: {
                'Notify_ID': Notify_ID
            },
            success: function (data) {
                getNotifications();
            },
            error: function (data) {
                alert(data.error);
            }
        });
    });

</script>
