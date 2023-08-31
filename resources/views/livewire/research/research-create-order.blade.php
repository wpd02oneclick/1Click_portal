<!--Page header-->
<div class="page-header d-xl-flex d-block">
    <div class="page-leftheader">
        <h4 class="page-title">Research<span class="font-weight-normal text-muted ms-2"> Writing Orders</span></h4>
    </div>
</div>
<!--End Page header-->
<div class="card">
    <div class="card-header border-bottom-0"><h3 class="card-title">New Order Details</h3></div>
    <div class="card-body pb-2">
        <form action="{{ route('Post.Research.Create.Order') }}" method="POST" class="needs-validation was-validated"
              enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="Order_Type" value="1">
            <div class="row row-sm">
                <div class="col-lg-4">
                    <div class="form-group">
                        <label class="form-label">Order ID</label>
                        <input class="form-control mb-4" name="Order_ID"
                               placeholder="Order ID" readonly type="text"
                               value="{{ $L_OID }}">
                    </div>
                </div>
                @if(!empty($Client_Info->Client_Code))
                    <div class="col-lg-4">
                        <div class="form-group">
                            <label class="form-label">Client ID</label>
                            <input class="form-control mb-4"
                                   placeholder="Order ID" readonly type="text"
                                   value="{{ $Client_Info->Client_Code }}">
                        </div>
                    </div>
                @endif
            </div>
            <h4 class="my-4">Client Information</h4>
            <div class="row row-sm mb-4">
                <input class="Client-Code" name="Client_Code" type="hidden"
                       value="{{ !empty($Client_Info->id)? $Client_Info->id : null }}">
                <div class="col-lg-3">
                    <div class="form-group">
                        <label class="form-label">Client Name</label>
                        <input class="form-control mb-4 is-valid Get-Client typeahead Client_Name" id="myTypeahead"
                               name="Client_Name"
                               placeholder="Client Name" type="text" required autocomplete="off"
                               value="{{ !empty($Client_Info->Client_Name)? $Client_Info->Client_Name : null }}">
                    </div>
                </div>
                <div class="col-lg-3">
                    <div class="form-group">
                        <label class="form-label">Client Country</label>
                        <select name="Client_Country"
                                class="form-control select2-show-search custom-select select2 Client_Country select2-hidden-accessible"
                                data-placeholder="Select Country" tabindex="-1" aria-hidden="true" required>
                            <option label="Select Country"></option>
                            @foreach($Countries as $Country)
                                @if(!empty($Client_Info->Client_Country))
                                    <option @selected($Client_Info->Client_Country === $Country->name)
                                            value="{{ $Country->name }}">{{ $Country->name }}</option>
                                @else
                                    <option value="{{ $Country->name }}">{{ $Country->name }}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-lg-3">
                    <div class="form-group">
                        <label class="form-label">Client Email</label>
                        <input class="form-control mb-4 is-valid Client_Email" name="Client_Email"
                               placeholder="Client Email" type="email"
                               value="{{ !empty($Client_Info->Client_Email)? $Client_Info->Client_Email : null }}">
                    </div>
                </div>
                <div class="col-lg-3">
                    <div class="form-group">
                        <label class="form-label">Client Phone</label>
                        <input class="form-control mb-4 is-valid Client_Phone" name="Client_Phone"
                               placeholder="Client Phone" type="text"
                               value="{{ !empty($Client_Info->Client_Phone)? $Client_Info->Client_Phone : null }}">
                    </div>
                </div>
            </div>
            <h4 class="my-4">Order Information</h4>
            <div class="row row-sm mb-4">
                <div class="col-lg-4">
                    <div class="form-group">
                        <label class="form-label">Services</label>
                        <select name="Order_Services" class="form-control select2-show-search custom-select select2"
                                data-placeholder="Select Services" required>
                            <option label="Select Services"></option>
                            @foreach($Order_Services as $Service)
                                <option
                                    value="{{ $Service->Service_Name }}">{{ $Service->Service_Name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="form-group">
                        <label class="form-label">Education Level</label>
                        <select name="Education_Level" class="form-control custom-select select2"
                                data-placeholder="Select Education Level" required>
                            <option label="Select Education"></option>
                            <option value="High School">High School</option>
                            <option value="UnderGraduate">UnderGraduate</option>
                            <option value="Graduate">Graduate</option>
                            <option value="Mastered">Mastered</option>
                            <option value="Doctoral">Doctoral</option>
                        </select>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="form-group">
                        <label class="form-label">Word Count</label>
                        <input class="form-control mb-4 is-valid" name="Word_Count"
                               placeholder="Enter No. of Words" type="number" min="0" required>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="form-group">
                        <label class="form-label">Spacing</label>
                        <select name="Spacing" class="form-control custom-select select2"
                                data-placeholder="Select Spacing" required>
                            <option label="Select Spacing"></option>
                            <option value="1.0">1.0</option>
                            <option value="1.5">1.5</option>
                            <option value="2.0">2.0</option>
                        </select>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="form-group">
                        <label class="form-label">Citation Style</label>
                        <select name="Citation_Style" class="form-control custom-select select2"
                                data-placeholder="Select Document Style" required>
                            <option label="Select Spacing"></option>
                            <option value="APA">APA</option>
                            <option value="MLA">MLA</option>
                            <option value="Chicago/Turban">Chicago/Turban</option>
                            <option value="IEEE Style">IEEE Style</option>
                            <option value="Harvard">Harvard</option>
                            <option value="Oxford">Oxford</option>
                            <option value="Oscala">Oscala</option>
                            <option value="As Per Requirement">As Per Requirement</option>
                        </select>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="form-group">
                        <label class="form-label">Sources Needed</label>
                        <input class="form-control mb-4 is-valid"
                               placeholder="Enter Source" name="Sources" type="text">
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="form-group">
                        <label class="form-label">Websites</label>
                        <select name="Order_Website" class="form-control select2-show-search custom-select select2"
                                data-placeholder="Select Website" required>
                            <option label="Select Website"></option>
                            @foreach($Order_Websites as $Website)
                                <option
                                    value="{{ $Website->Website_Name }}">{{ $Website->Website_Name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="form-group">
                        <label class="form-label">Order Status</label>
                        <select name="Order_Status" class="form-control custom-select select2"
                                data-placeholder="Select Status" required>
                            <option label="Select Status"></option>
                            <option selected value="0">Working</option>
                            <option value="1">Canceled</option>
                            <option value="2">Completed</option>
                            <option value="3">Revision</option>
                        </select>
                    </div>
                </div>
            </div>
            <h4 class="my-4">Submission Date Information</h4>
            <div class="row row-sm mb-4">
                <div class="col-lg-3 mb-2">
                    <label class="form-label">DeadLine Date</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <div class="input-group-text">
                                <span class="feather feather-calendar"></span>
                            </div>
                        </div>
                        <input class="form-control" placeholder="MM/DD/YYYY" name="DeadLine" required
                               type="date">
                    </div>
                </div>
                <div class="col-lg-3 mb-2">
                    <label class="form-label">DeadLine Time</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <div class="input-group-text">
                                <span class="feather feather-clock"></span>
                            </div>
                        </div><!-- input-group-prepend -->
                        <input class="form-control" placeholder="Set time" name="DeadLine_Time" type="time"
                               required>
                    </div><!-- input-group -->
                </div>
                <div class="col-lg-3 mb-2">
                    <label class="form-label">First Date</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <div class="input-group-text">
                                <span class="feather feather-calendar"></span>
                            </div>
                        </div>
                        <input class="form-control" placeholder="MM/DD/YYYY" name="F_DeadLine" 
                               type="date">
                    </div>
                </div>
                <div class="col-lg-3 mb-2">
                    <label class="form-label">Second Date</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <div class="input-group-text">
                                <span class="feather feather-calendar"></span>
                            </div>
                        </div>
                        <input class="form-control" placeholder="MM/DD/YYYY" name="S_DeadLine" 
                               type="date">
                    </div>
                </div>
                <div class="col-lg-3 mb-2">
                    <label class="form-label">Third Date</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <div class="input-group-text">
                                <span class="feather feather-calendar"></span>
                            </div>
                        </div>
                        <input class="form-control" placeholder="MM/DD/YYYY" name="T_DeadLine" 
                               type="date">
                    </div>
                </div>
            </div>

            <h4 class="my-4">Any References</h4>
            <div class="row row-sm mb-4">
                <div class="col-lg-4">
                    <div class="form-group">
                        <label class="form-label">Reference Order Code</label>
                        <input class="form-control mb-4 is-valid" name="Reference_Code"
                               placeholder="Reference" type="text">
                    </div>
                </div>
            </div>
            <h4 class="my-4">Order Description</h4>
            <div class="row row-sm mb-4">
                <div class="col-lg-12">
                    <div class="form-group">
                        <textarea id="summernote" class="form-control mb-4 is-invalid state-invalid"
                                  name="Order_Description" placeholder="Textarea (invalid state)"></textarea>
                    </div>
                </div>
            </div>
            <h4 class="my-4">Payment Information</h4>
            <div class="row row-sm mb-4">
                <div class="col-lg-3">
                    <div class="form-group">
                        <label class="form-label">Order Price</label>
                        <input class="form-control mb-4 is-valid" name="Order_Price"
                               placeholder="Enter Order Amount" id="Order_Price" min="0" type="number" required>
                    </div>
                </div>
                <div class="col-lg-3">
                    <div class="form-group">
                        <label class="form-label">Order Currency</label>
                        <select name="Order_Currency" class="form-control select2-show-search custom-select select2"
                                data-placeholder="Select Currencies" required>
                            <option label="Select Currency"></option>
                            @foreach($Currencies as $Currency)
                                <option @selected($Currency->code === 'USD')
                                    value="{{ $Currency->code }}">{{ $Currency->code }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-lg-3">
                    <div class="form-group">
                        <label class="form-label">Payment Status</label>
                        <select name="Payment_Status" class="form-control custom-select select2 Partial_Payment"
                                data-placeholder="Select Status" aria-required="true" required>
                            <option value="">Select Status</option>
                            <option value="0">Paid</option>
                            <option value="1">Un-Paid</option>
                            <option value="2">Partial</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="row row-sm mb-4 Partial-Info">
                <div class="col-lg-3">
                    <div class="form-group">
                        <label class="form-label">Receive Amount</label>
                        <input class="form-control mb-4 is-valid" name="Rec_Amount"
                               placeholder="Enter Receive Amount" id="Rec_Amount" type="number">
                    </div>
                </div>
                <div class="col-lg-3">
                    <div class="form-group">
                        <label class="form-label">Due Amount</label>
                        <input class="form-control mb-4 is-valid" name="Due_Amount" readonly
                               placeholder="Enter Due Amount" type="number" id="Due_Amount">
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="form-group">
                        <label class="form-label">Partial Payment Information</label>
                        <input class="form-control mb-4 is-valid" name="Partial_Info"
                               placeholder="Enter Partial Payment Information" type="text">
                    </div>
                </div>
            </div>
            <h4 class="my-4">Upload Any Attachments</h4>
            <div class="row row-sm mb-4">
                <div class="col-lg-12">
                    <div class="form-group">
                        <label for="form-label" class="form-label"></label>
                        <input class="form-control" type="file" name="files[]" multiple>
                    </div>
                </div>
            </div>
            <div class="mb-4">
                <button type="submit" class="btn btn-primary btn-block">Finish & Save</button>
            </div>
        </form>
    </div>
</div>


<script>
    $(document).ready(function () {

        const searchOrderID = '{{ route('Get.Clients') }}';
        $('#myTypeahead').autocomplete({
            source: function (request, response) {
                $.get(searchOrderID, {query: request.term}, function (data) {
                    response(data);
                });
            },
            select: function (event, ui) {
                // The selected value is available in the 'ui.item.value' parameter
                getClientInfo(ui.item.value)
            }
        });

        function getClientInfo(value) {
            $.ajax({
                url: '{{ route('Get.Client.Info') }}',
                type: 'GET',
                data: {
                    'query': value
                },
                success: function (data) {
                    $('.Client-Code').val(data.Client_Code);
                    $('.Client_Name').val(data.Client_Name);
                    $('.Client_Phone').val(data.Client_Phone);
                    $('.Client_Country').val(data.Client_Country).trigger('change');
                    $('.Client_Email').val(data.Client_Email);
                }
            });
        }

        $('.Client_Name').keyup(function () {
            var Client_Name = $(this).val();
            if (Client_Name === '') {
                $('.Client-Code').val('');
                $('.Client_Name').val('');
                $('.Client_Phone').val('');
                $('.Client_Country').val('').trigger('change');
                $('.Client_Email').val('');
            }
        });

        $('.Partial-Info').hide();
        $('.Partial_Payment').change(function () {
            const mode = $(this).val();
            if (mode === '2') {
                $('.Partial-Info').show();
            } else {
                $('.Partial-Info').hide();
            }
        });

        $('#Rec_Amount').keyup(function () {
            var Payment = $('#Order_Price').val();
            var Rec_Amount = $(this).val();
            var Due_Amount = Payment - Rec_Amount;
            $('#Due_Amount').val(Due_Amount);
        });
    });
</script>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        const repeaterContainer = document.getElementById("repeater-container");
        const addButton = document.querySelector(".add-button");

        addButton.addEventListener("click", function () {
            const repeaterItem = repeaterContainer.querySelector(".repeater-item").cloneNode(true);
            repeaterItem.querySelector(".remove-button").addEventListener("click", function () {
                repeaterItem.remove();
            });

            repeaterContainer.appendChild(repeaterItem);
        });
    });
</script>
