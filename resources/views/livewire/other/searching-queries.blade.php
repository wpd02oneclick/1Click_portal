<!--Page header-->
<div class="page-header d-lg-flex d-block">
    <div class="page-leftheader">
        <h4 class="page-title">Search Anything!</h4>
    </div>
</div>
<!--End Page header-->

<!-- Row -->
<div class="row">
    <div class="col-sm-12 col-md-12">
        <div class="card custom-card">
            <div class="card-body pb-0">
                <div class="input-group mb-2">
                    <input type="text" class="form-control Search_Records" placeholder="Searching.....">
                    <span class="input-group-append">
												<button class="btn ripple btn-primary" >Search</button>
											</span>
                </div>
            </div>
            <div class="card-body p-3">
                <p class="text-muted mb-0 ps-3">About 12,546,90000 results (0.56 Seconds)</p>
            </div>
        </div>
        <div id="Searching-Queries"></div>
    </div>
</div>
<!-- End Row -->

<script>
    (function ($) {
        $('.Search_Records').keyup(function () {
            const Query = $(this).val();

            if (Query !== '') {
                $.ajax({
                    url: '{{ route('Get.Search.Records') }}',
                    type: 'GET',
                    data: {
                        'Query': Query
                    },
                    success: function (data) {
                        $('#Searching-Queries').html(data);
                    },
                    error: function (data) {
                        alert(data.responseJSON);
                    }
                });
            } else {
                $('#Searching-Queries').html('');
            }
        });
    })(jQuery);
</script>
