$(function(e){
	
	//________ DataTable
	var table = $('#hr-attendance1').DataTable( {
		rowReorder: true,
		columnDefs: [
			{ orderable: true, className: 'reorder', targets: [0,1] },
			{ orderable: false, targets: '_all' }
		]
	} );

	/* End Data Table */
	
	//________ Datepicker
	$( ".fc-datepicker" ).datepicker({
		dateFormat: "dd MM yy",
		monthNamesShort: [ "Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec" ]
	});
	
	//________ Select2
	$('.select2').select2({
		minimumResultsForSearch: Infinity,
		width:'100%'
	});

 });