jQuery(function(e){
	       'use strict';

		    var popover = new bootstrap.Popover(document.querySelector('[data-bs-popover-color="head-primary"]'), {
			template: '<div class="popover popover-head-primary" role="popover"><div class="popover-arrow"><\/div><h3 class="popover-header"><\/h3><div class="popover-body"><\/div><\/div>'
			})


		   var popover = new bootstrap.Popover(document.querySelector('[data-bs-popover-color="head-secondary"]'), {
			template: '<div class="popover popover-head-secondary" role="popover"><div class="popover-arrow"><\/div><h3 class="popover-header"><\/h3><div class="popover-body"><\/div><\/div>'
			})

		   var popover = new bootstrap.Popover(document.querySelector('[data-bs-popover-color="primary"]'), {
			template: '<div class="popover popover-primary" role="popover"><div class="popover-arrow"><\/div><h3 class="popover-header"><\/h3><div class="popover-body"><\/div><\/div>'
			})

		   var popover = new bootstrap.Popover(document.querySelector('[data-bs-popover-color="secondary"]'), {
			template: '<div class="popover popover-secondary" role="popover"><div class="popover-arrow"><\/div><h3 class="popover-header"><\/h3><div class="popover-body"><\/div><\/div>'
			})

	       // By default, Bootstrap doesn't auto close popover after appearing in the page
	       // resulting other popover overlap each other. Doing this will auto dismiss a popover
	       // when clicking anywhere outside of it
		   $(document).on('click', function(e) {
			$('[data-bs-toggle="popover"]').each(function() {
				if (!$(this).is(e.target) && $(this).has(e.target).length === 0 && $('.popover').has(e.target).length === 0) {
					(($(this).popover('hide').data('bs.popover') || {}).inState || {}).click = false
				}
			});
		});

	     });
