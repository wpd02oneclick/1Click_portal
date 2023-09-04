
$('#Upload_Revision_ID').click(function () {
    var Revision_ID = $(this).data('id');
    $('#hidden_Revision_ID').val(Revision_ID);

})


$('.Revision_ID').click(function () {
    var Revision_ID = $(this).data('id');
    console.log("ID before AJAX request:", Revision_ID);

    $.ajax({
        url: "{{ route('Get.Revision.Details') }}",
        type: 'GET',
        data: {
            'Revision_ID': Revision_ID
        },
        success: function (data) {
            console.log("Response from server:", data);
            $('#revision_details').append(data.revision_details);
            $('#Order_Deadline_Time').val(data.Order_deadLine_Time);
            $('#Order_Deadline_Date').val(data.Order_deadLine);

            // Clear any previous attachment data in the modal
            $('#Revision_view_table tbody').empty();

            // Loop through attachments and append them to the table
            if (data.attachments.length > 0) {
                var tbody = $('#Revision_view_table tbody');
                $.each(data.attachments, function (index, attachment) {
                    var assetUrl = "{{ asset('" + attachment.file_path + "') }}"; // Generate asset URL
                    var assetUrl = `{{ asset('${attachment.file_path}') }}`; // Generate asset URL

                    var row = '<tr>' +
                        '<td>' + (index + 1) + '</td>' +
                        '<td>' + attachment.file_name + '<br>' + attachment.attachment_time + '</td>' +
                        '<td>' +
                        '<div class="d-flex justify-content-center">' +
                        '<a href="' + assetUrl + '" class="action-btns1" ' +
                        'data-bs-toggle="tooltip" download="' + attachment.file_name + '" ' +
                        'data-bs-placement="top" title="Download" target="_blank" aria-label="Download">' +
                        '<i class="feather feather-download text-success"></i>' +
                        '</a>' +
                        '</div>' +
                        '</td>' +
                        '</tr>';
                    tbody.append(row);
                });
            } else {
                console.log("No attachments found.");
            }


        },
        error: function (xhr) {
            var errorMessage = xhr.responseText;
            console.log("Error message:", errorMessage);
        }
    });


    $.ajax({
        url: "{{ route('Get.Writer.Order.Attachment') }}",
        type: 'GET',
        data: {
            'Revision_ID': Revision_ID
        },
        success: function (data) {
            console.log("AJAX Response:", data);
            $('#Writer_Submission_view_table tbody').append(data);
        },

        error: function (xhr) {
            var errorMessage = xhr.responseText;
            console.log("Error message:", errorMessage);
        }
    });
});


$('.view-assign-task').click(function () {
    var currentTaskID = $(this).data('id');
    $.ajax({
        url: "{{ route('View.Assign.Task') }}",
        type: 'GET',
        data: {
            'task_id': currentTaskID
        },
        success: function (data) {

            $('#task-table-body tbody').empty().append(data); // Clear existing content and append new rows
        },
        error: function (xhr) {
            var errorMessage = xhr.responseText;
            console.log(errorMessage);
        }
    });
});


$('#Assign_Words').keyup(function () {
    var Rec_Amount = parseInt($(this).val());
    var Payment = parseInt($('#Total_Words').val());

    if (Rec_Amount > Payment) {
        alert('Entered Words Exceeds!');
        $('#Rem_Word').val(0);
        $(this).val(Payment);
    } else {
        var Due_Amount = Payment - Rec_Amount;
        $('#Rem_Word').val(Due_Amount);
    }
});

$('.Task_ID').on('click', function () {
    var getTask_ID = $(this).find('#Task_ID').val();
    $(".task-id").val(getTask_ID);
});

$('.View-Revision').on('click', function () {
    const getTask_ID = $(this).find('#Task_ID').val();
    $.ajax({
        url: '{{ route('Get.Task.Revisions') }}',
        type: 'GET',
        data: {
            'task_id': getTask_ID
        },
        success: function (data) {
            $('.Get-Revisions').html(data);
        },
        error: function (data) {
            alert(data.responseJSON);
        }
    });
});

$('#Edit_Assign_Words').keyup(function () {
    var Rec_Amount = parseInt($(this).val());
    var Payment = parseInt($('#Edit_Total_Words').val());
    console.log(Payment);
    console.log(Rec_Amount);
    if (Rec_Amount > Payment) {
        alert('Entered Words Exceeds!');
        $('#Edit_Rem_Word').val(0);
        $(this).val(Payment);
    } else {
        var Due_Amount = Payment - Rec_Amount;
        $('#Edit_Rem_Word').val(Due_Amount);
    }
});

$('.Edit-Task').on('click', function () {
    const getTask_ID = $(this).find('#Task_ID').val();
    $.ajax({
        url: '{{ route('Get.Edit.Task.Info') }}',
        type: 'GET',
        data: {
            'task_id': getTask_ID
        },
        success: function (data) {
            $('.Selected-Date').val(data.Selected_Date);
            $('.Selected-Time').val(data.Selected_Time);
            $('.Selected-Writer').val(data.Selected_Writer).trigger('change');
            $('.Selected-Words').val(data.Selected_Words);
            $('.Selected-Total-Words').val(data.Selected_Total_Words);
            $('.Selected-Due-Words').val(data.Selected_Due_Words);
        },
        error: function (data) {
            alert(data.responseJSON);
        }
    });
});

$('.Add-Revision').on('click', function () {
    const getTask_ID = $(this).find('#Task_ID').val();
    $.ajax({
        url: '{{ route('Get.Edit.Task.Info') }}',
        type: 'GET',
        data: {
            'task_id': getTask_ID
        },
        success: function (data) {
            $('.Rev-DeadLine').val(data.Selected_Date);
            $('.Rev-Time').val(data.Selected_Time);
        },
        error: function (data) {
            alert(data.responseJSON);
        }
    });
});

$('.Order-Revision').on('click', function () {
    const getOrder_ID = $(this).find('#Order_ID').val();

    $.ajax({
        url: '{{ route('Get.Order.Rev.Info') }}',
        type: 'GET',
        data: {
            'Order_ID': getOrder_ID
        },
        success: function (data) {
            console.log(data);
            $('.Order-DeadLine').val(data.Selected_Date);
            $('.Order-Time').val(data.Selected_Time);
            $('.Order-Words').val(data.Selected_Words);
        },
        error: function (data) {
            alert(data.responseJSON);
        }
    });
});

var form = '#Chat-Form';

$(form).on('submit', function (event) {
    event.preventDefault();

    var url = $(this).attr('data-action');
    var div = document.getElementById('Order-Messages');

    $.ajax({
        url: url,
        method: 'POST',
        data: new FormData(this),
        dataType: 'JSON',
        contentType: false,
        cache: false,
        processData: false,
        success: function (response) {
            $(form).trigger("reset");
            div.scrollTop = div.scrollHeight;
        },
        error: function (response) {
            alert(response);
        }
    });
});

$(document).on('click', '.popover-body ul li a.Message_Forward', function () {
    const Msg_ID = $(this).find('.Msg_ID').html();
    const Assign_ID = $(this).find('.Assign_ID').html();
    const Order_ID = $(this).find('.Order_ID').html();

    $.ajax({
        url: '{{ route('Forward.To.Executive') }}',
        type: 'GET',
        data: {
            'Msg_ID': Msg_ID,
            'Assign_ID': Assign_ID,
            'Order_ID': Order_ID,
        },
        success: function (data) {
            sendRequest(Order_ID);
            alert(data.message);
        },
        error: function (data) {
            console.log(data);
            alert(data);
        }
    });
});

// Get All Order Messages
const Order_ID = {{ (int) $Research_Order -> id }};

setInterval(function () {
    sendRequest(Order_ID);
}, 1000);

// Function to initialize the popover
function initializePopover() {
    $('[data-toggle="popover"]').popover({
        html: true,
        sanitize: false,
    })
    var popoverTriggerEl = document.querySelector('[data-bs-toggle="popover"]');
    var popoverInstance = new bootstrap.Popover(popoverTriggerEl);

    // Remove popover when clicking on the otherside
    document.addEventListener('click', function (event) {
        var targetElement = event.target;

        // Check if the clicked element is outside the popover and its trigger element
        if (!targetElement.closest('.popover') && targetElement !== popoverTriggerEl) {
            popoverInstance.hide();
        }
    });
}

function sendRequest(Order_ID) {

    $.ajax({
        url: '{{ route('Get.Messages') }}',
        type: 'GET',
        data: {
            'Order_ID': Order_ID
        },
        success: function (data) {
            $('#Order-Messages').html(data);
            initializePopover();
        }
    });
}

});
</script >
