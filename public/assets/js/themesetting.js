(function($) {
    "use strict";

	// ______________ Page Loading
	$(window).on("load", function(e) {
		$("#global-loader").fadeOut("slow");
	})

	// ______________Full screen
	$(document).on("click", ".fullscreen-button", function toggleFullScreen() {
		$('html').addClass('fullscreen-content');
		if ((document.fullScreenElement !== undefined && document.fullScreenElement === null) || (document.msFullscreenElement !== undefined && document.msFullscreenElement === null) || (document.mozFullScreen !== undefined && !document.mozFullScreen) || (document.webkitIsFullScreen !== undefined && !document.webkitIsFullScreen)) {
			if (document.documentElement.requestFullScreen) {
				document.documentElement.requestFullScreen();
			} else if (document.documentElement.mozRequestFullScreen) {
				document.documentElement.mozRequestFullScreen();
			} else if (document.documentElement.webkitRequestFullScreen) {
				document.documentElement.webkitRequestFullScreen(Element.ALLOW_KEYBOARD_INPUT);
			} else if (document.documentElement.msRequestFullscreen) {
				document.documentElement.msRequestFullscreen();
			}
		} else {
			$('html').removeClass('fullscreen-content');
			if (document.cancelFullScreen) {
				document.cancelFullScreen();
			} else if (document.mozCancelFullScreen) {
				document.mozCancelFullScreen();
			} else if (document.webkitCancelFullScreen) {
				document.webkitCancelFullScreen();
			} else if (document.msExitFullscreen) {
				document.msExitFullscreen();
			}
		}
	})
	

	// ______________Cover Image
	$(".cover-image").each(function() {
		var attr = $(this).attr('data-image-src');
		if (typeof attr !== typeof undefined && attr !== false) {
			$(this).css('background', 'url(' + attr + ') center center');
		}
	});


	// ______________ Horizonatl
	$(document).ready(function() {
      $("a[data-theme]").on('click', function() {
        $("head link#theme").attr("href", $(this).data("theme"));
        $(this).toggleClass('active').siblings().removeClass('active');
      });
      $("a[data-effect]").on('click', function() {
        $("head link#effect").attr("href", $(this).data("effect"));
        $(this).toggleClass('active').siblings().removeClass('active');
      });
    });

	// ______________ Tooltip
	var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
	var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
	return new bootstrap.Tooltip(tooltipTriggerEl)
	})
	

	
	// __________POPOVER
	var popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'))
	var popoverList = popoverTriggerList.map(function (popoverTriggerEl) {
	  return new bootstrap.Popover(popoverTriggerEl)
	})
	

	// __________MODAL
	// showing modal with effect
	$('.modal-effect').on('click', function(e) {
		e.preventDefault();
		var effect = $(this).attr('data-effect');
		$('#modaldemo8').addClass(effect);
	});
	// hide modal with effect
	$('#modaldemo8').on('hidden.bs.modal', function(e) {
		$(this).removeClass(function(index, className) {
			return (className.match(/(^|\s)effect-\S+/g) || []).join(' ');
		});
	});


	// ______________Back to top Button
	$(window).on("scroll", function(e) {
    	if ($(this).scrollTop() > 0) {
            $('#back-to-top').fadeIn('slow');
        } else {
            $('#back-to-top').fadeOut('slow');
        }
    });
    $("#back-to-top").on("click", function(e){
        $("html, body").animate({
            scrollTop: 0
        }, 0);
        return false;
    });

	// ______________ Chart-circle
	if ($('.chart-circle').length) {
		$('.chart-circle').each(function() {
			let $this = $(this);

			$this.circleProgress({
			  fill: {
				color: $this.attr('data-color')
			  },
			  size: $this.height(),
			  startAngle: -Math.PI / 4 * 2,
			  emptyFill: '#e5e9f2',
			  lineCap: 'round'
			});
		});
	}
	// ______________ Chart-circle
	if ($('.chart-circle-primary').length) {
		$('.chart-circle-primary').each(function() {
			let $this = $(this);

			$this.circleProgress({
			  fill: {
				color: $this.attr('data-color')
			  },
			  size: $this.height(),
			  startAngle: -Math.PI / 4 * 2,
			  emptyFill: 'rgba(51, 102, 255, 0.4)',
			  lineCap: 'round'
			});
		});
	}

	// ______________ Chart-circle
	if ($('.chart-circle-secondary').length) {
		$('.chart-circle-secondary').each(function() {
			let $this = $(this);

			$this.circleProgress({
			  fill: {
				color: $this.attr('data-color')
			  },
			  size: $this.height(),
			  startAngle: -Math.PI / 4 * 2,
			  emptyFill: 'rgba(254, 127, 0, 0.4)',
			  lineCap: 'round'
			});
		});
	}

	// ______________ Chart-circle
	if ($('.chart-circle-success').length) {
		$('.chart-circle-success').each(function() {
			let $this = $(this);

			$this.circleProgress({
			  fill: {
				color: $this.attr('data-color')
			  },
			  size: $this.height(),
			  startAngle: -Math.PI / 4 * 2,
			  emptyFill: 'rgba(13, 205, 148, 0.5)',
			  lineCap: 'round'
			});
		});
	}

	// ______________ Chart-circle
	if ($('.chart-circle-warning').length) {
		$('.chart-circle-warning').each(function() {
			let $this = $(this);

			$this.circleProgress({
			  fill: {
				color: $this.attr('data-color')
			  },
			  size: $this.height(),
			  startAngle: -Math.PI / 4 * 2,
			  emptyFill: 'rgba(247, 40, 74, 0.4)',
			  lineCap: 'round'
			});
		});
	}

	// ______________ Chart-circle
	if ($('.chart-circle-danger').length) {
		$('.chart-circle-danger').each(function() {
			let $this = $(this);

			$this.circleProgress({
			  fill: {
				color: $this.attr('data-color')
			  },
			  size: $this.height(),
			  startAngle: -Math.PI / 4 * 2,
			  emptyFill: 'rgba(247, 40, 74, 0.4)',
			  lineCap: 'round'
			});
		});
	}

	// ______________ Global Search
	$(document).on("click", "[data-bs-toggle='search']", function(e) {
		var body = $("body");

		if(body.hasClass('search-gone')) {
			body.addClass('search-gone');
			body.removeClass('search-show');
		}else{
			body.removeClass('search-gone');
			body.addClass('search-show');
		}
	});
	var toggleSidebar = function() {
		var w = $(window);
		if(w.outerWidth() <= 1024) {
			$("body").addClass("sidebar-gone");
			$(document).off("click", "body").on("click", "body", function(e) {
				if($(e.target).hasClass('sidebar-show') || $(e.target).hasClass('search-show')) {
					$("body").removeClass("sidebar-show");
					$("body").addClass("sidebar-gone");
					$("body").removeClass("search-show");
				}
			});
		}else{
			$("body").removeClass("sidebar-gone");
		}
	}
	toggleSidebar();
	$(window).resize(toggleSidebar);

	const DIV_CARD = 'div.card';
	
	// ______________ Function for remove card
	$(document).on('click', '[data-bs-toggle="card-remove"]', function(e) {
		let $card = $(this).closest(DIV_CARD);
		$card.remove();
		e.preventDefault();
		return false;
	});
	// ______________ Functions for collapsed card
	$(document).on('click', '[data-bs-toggle="card-collapse"]', function(e) {
		let $card = $(this).closest(DIV_CARD);
		$card.toggleClass('card-collapsed');
		e.preventDefault();
		return false;
	});
	
	// ______________ Card full screen
	$(document).on('click', '[data-bs-toggle="card-fullscreen"]', function(e) {
		let $card = $(this).closest(DIV_CARD);
		$card.toggleClass('card-fullscreen').removeClass('card-collapsed');
		e.preventDefault();
		return false;
	});

	//Input file-browser
	$(document).on('change', '.file-browserinput', function() {
		var input = $(this),
		numFiles = input.get(0).files ? input.get(0).files.length : 1,
		label = input.val().replace(/\\/g, '/').replace(/.*\//, '');
	input.trigger('fileselect', [numFiles, label]);
	});// We can watch for our custom `fileselect` event like this

	//______File Upload
	$('.file-browserinput').on('fileselect', function(event, numFiles, label) {
			var input = $(this).parents('.input-group').find(':text'),
			log = numFiles > 1 ? numFiles + ' files selected' : label;
			if( input.length ) {
				input.val(log);
			} else {
				if( log );
			}
	});

	//______Select2
	$('.select2').select2({
		minimumResultsForSearch: Infinity,
		width: '100%'
	});

	//______Product carosuel
	$('.thumb').on('click', function () {
		if(!$(this).hasClass('active'))
		{
			$(".thumb.active").removeClass("active");
			$(this).addClass("active");
		}
	});
    $('#myonoffswitch54').click(function() {
        if (this.checked) {
			$("#global-loader").fadeOut("slow");
            $('body').addClass('ltr');
            $('body').removeClass('rtl');
			$("html[lang=en]").attr("dir", "ltr");
            localStorage.setItem("ltr", "True");
            $("head link#style").attr("href", $(this));
            (document.getElementById("style")?.setAttribute("href", "../../assets/plugins/bootstrap/css/bootstrap.css"));
			var carousel = $('.owl-carousel');
			$.each(carousel ,function( index, element)  {
			// element == this
			var carouselData = $(element).data('owl.carousel');
			carouselData.settings.rtl = false; //don't know if both are necessary
			carouselData.options.rtl = false;
			$(element).trigger('refresh.owl.carousel');
		});
        } else {
            $('body').removeClass('ltr');
            $('body').addClass('rtl');
			$("html[lang=en]").attr("dir", "rtl");
            localStorage.setItem("ltr", "false"); 
            $("head link#style").attr("href", $(this));
            (document.getElementById("style")?.setAttribute("href", "../../assets/plugins/bootstrap/css/bootstrap.rtl.css"));
         }
    });



	  /*Header Style*/
	  $('#background1').click(function() {
        if (this.checked) {
            $('body').addClass('light-header');
			$('body').removeClass('color-header');
			$('body').removeClass('dark-header');
			$('body').removeClass('gradient-header');
        } else {
            $('body').removeClass('light-header');
         }
    });

	$('#background2').click(function() {
        if (this.checked) {
            $('body').addClass('color-header');
			$('body').removeClass('light-header');
			$('body').removeClass('dark-header');
			$('body').removeClass('gradient-header');
        } else {
            $('body').removeClass('color-header');
         }
    });
	$('#background3').click(function() {
        if (this.checked) {
            $('body').addClass('dark-header');
			$('body').removeClass('light-header');
			$('body').removeClass('color-header');
			$('body').removeClass('gradient-header');
        } else {
            $('body').removeClass('dark-header');
         }
    });
	$('#background11').click(function() {
        if (this.checked) {
            $('body').addClass('gradient-header');
			$('body').removeClass('light-header');
			$('body').removeClass('color-header');
			$('body').removeClass('dark-header');
        } else {
            $('body').removeClass('gradient-header');
         }
    });


	  /*Leftmenu Style*/
	$('#background4').click(function() {
        if (this.checked) {
            $('body').addClass('light-menu');
			$('body').removeClass('color-menu');
			$('body').removeClass('dark-menu');
			$('body').removeClass('gradient-menu');
			$('body').removeClass('light-hormenu');
			 $('body').removeClass('dark-hormenu');
			 $('body').removeClass('color-hormenu');
        } else {
            $('body').removeClass('light-menu');
         }
    });

	$('#background5').click(function() {
        if (this.checked) {
            $('body').addClass('color-menu');
			$('body').removeClass('light-menu');
			$('body').removeClass('dark-menu');
			$('body').removeClass('gradient-menu');
			$('body').removeClass('light-hormenu');
			 $('body').removeClass('dark-hormenu');
			 $('body').removeClass('color-hormenu');
        } else {
            $('body').removeClass('color-menu');
         }
    });


	$('#background6').click(function() {
        if (this.checked) {
            $('body').addClass('dark-menu');
			$('body').removeClass('light-menu');
			$('body').removeClass('color-menu');
			$('body').removeClass('gradient-menu');
			$('body').removeClass('light-hormenu');
			 $('body').removeClass('dark-hormenu');
			 $('body').removeClass('color-hormenu');
        } else {
            $('body').removeClass('dark-menu');
         }
    });

	$('#background10').click(function() {
        if (this.checked) {
            $('body').addClass('gradient-menu');
			$('body').removeClass('light-menu');
			$('body').removeClass('color-menu');
			$('body').removeClass('dark-menu');
			$('body').removeClass('light-hormenu');
			 $('body').removeClass('gradient-hormenu');
			 $('body').removeClass('color-hormenu');
        } else {
            $('body').removeClass('gradient-menu');
         }
    });


	  /*Horizontal Style*/
	$('#background7').click(function() {
        if (this.checked) {
			$('body').addClass('light-hormenu');
			$('body').removeClass('color-hormenu');
			$('body').removeClass('dark-hormenu');
			$('body').removeClass('gradient-hormenu');
			$('body').removeClass('dark-menu');
			$('body').removeClass('color-menu');
			$('body').removeClass('light-menu');
			$('body').removeClass('gradient-menu');
        } else {
            $('body').removeClass('light-hormenu');
         }
    });
	
	$('#background8').click(function() {
        if (this.checked) {
			$('body').addClass('color-hormenu');
			$('body').removeClass('light-hormenu');
			$('body').removeClass('dark-hormenu');
			$('body').removeClass('gradient-hormenu');
			$('body').removeClass('dark-menu');
			$('body').removeClass('color-menu');
			$('body').removeClass('light-menu');
			$('body').removeClass('gradient-menu');
        } else {
            $('body').removeClass('color-hormenu');
         }
    });
	
	$('#background9').click(function() {
        if (this.checked) {
			$('body').addClass('dark-hormenu');
			$('body').removeClass('light-hormenu');
			$('body').removeClass('color-hormenu');
			$('body').removeClass('gradient-hormenu');
			$('body').removeClass('dark-menu');
			$('body').removeClass('color-menu');
			$('body').removeClass('light-menu');
			$('body').removeClass('gradient-menu');
        } else {
            $('body').removeClass('dark-hormenu');
         }
    });
	$('#background13').click(function() {
        if (this.checked) {
			$('body').addClass('gradient-hormenu');
			$('body').removeClass('light-hormenu');
			$('body').removeClass('color-hormenu');
			$('body').removeClass('dark-hormenu');
			$('body').removeClass('dark-menu');
			$('body').removeClass('color-menu');
			$('body').removeClass('light-menu');
			$('body').removeClass('gradient-menu');
        } else {
            $('body').removeClass('gradient-hormenu');
         }
    });

	  
	//______LTR AND RTL
    $('#myonoffswitch55').click(function() {
        if (this.checked) {
			$("#global-loader").fadeOut("slow");
            $('body').addClass('rtl');
            $('body').removeClass('ltr');
			$("html[lang=en]").attr("dir", "rtl");
            localStorage.setItem("rtl", "True");
            $("head link#style").attr("href", $(this));
            (document.getElementById("style")?.setAttribute("href", "../../assets/plugins/bootstrap/css/bootstrap.rtl.css"));
			var carousel = $('.owl-carousel');
				$.each(carousel ,function( index, element)  {
				// element == this
				var carouselData = $(element).data('owl.carousel');
				carouselData.settings.rtl = true; //don't know if both are necessary
				carouselData.options.rtl = true;
				$(element).trigger('refresh.owl.carousel');
			});
        } else {
            $('body').removeClass('rtl');
            $('body').addClass('ltr');
			$("html[lang=en]").attr("dir", "ltr");
            localStorage.setItem("rtl", "false"); 
            $("head link#style").attr("href", $(this));
            (document.getElementById("style")?.setAttribute("href", "../../assets/plugins/bootstrap/css/bootstrap.css"));
         }
    });
	
    /*-- width styles ---*/
    $('#myonoffswitch18').click(function() {
        if (this.checked) {
            $('body').addClass('default');
            $('body').removeClass('boxed');
            localStorage.setItem("default", "True");
        } else {
            $('body').removeClass('default');
            localStorage.setItem("default", "false");
        }
    });
    $('#myonoffswitch19').click(function() {
        if (this.checked) {
            $('body').addClass('boxed');
            $('body').removeClass('default');
            localStorage.setItem("boxed", "True");
        } else {
            $('body').removeClass('boxed');
            localStorage.setItem("boxed", "false");
        }
    });
	
	/*Theme-layout*/ 
	$('#background-left1').click(function() {
        if (this.checked) {
            $('body').addClass('light-mode');
			$('body').removeClass('gradient-hormenu');
			$('body').removeClass('dark-mode');
			$('body').removeClass('color-header');
			$('body').removeClass('dark-header');
			$('body').removeClass('dark-menu');
			$('body').removeClass('gradient-menu');
			$('body').removeClass('gradient-header');
			$('body').removeClass('color-menu');
            localStorage.setItem("light-mode", "True");
        } else {
            $('body').removeClass('light-mode');
            localStorage.setItem("light-mode", "false");
        }
    });

	$('#background-left2').click(function() {
        if (this.checked) {
            $('body').addClass('dark-mode');
			$('body').removeClass('light-mode');
			$('body').removeClass('light-menu');
			$('body').removeClass('color-menu');
			$('body').removeClass('dark-header');
			$('body').removeClass('color-header');
			$('body').removeClass('light-header');
			$('body').removeClass('dark-menu');
			$('body').removeClass('light-hormenu');
			$('body').removeClass('gradient-hormenu');
			$('body').removeClass('gradient-menu');
            localStorage.setItem("dark-mode", "True");
        } else {
            $('body').removeClass('dark-mode');
            localStorage.setItem("dark-mode", "false");
        }
    });

	/*********************Horizontal Versions********************/
	  $('#myonoffswitch20').click(function() {
        if (this.checked) {
			$("#global-loader").fadeOut("slow");
            $('body').addClass('default-horizontal');
            $('body').removeClass('centerlogo-horizontal');
            localStorage.setItem("default-horizontal", "True");
        } else {
            $('body').removeClass('default-horizontal');
            localStorage.setItem("default-horizontal", "false");
        }
    });
    $('#myonoffswitch21').click(function() {
        if (this.checked) {
            $('body').addClass('centerlogo-horizontal');
            $('body').removeClass('default-horizontal');
            localStorage.setItem("centerlogo-horizontal", "True");
        } else {
            $('body').removeClass('centerlogo-horizontal');
            localStorage.setItem("centerlogo-horizontal", "false");
        }
    });
	/*********************Horizontal Versions********************/

	/*********************Default-menu open********************/
    $('#myonoffswitch22').click(function() {
        if (this.checked) {
			$("#global-loader").fadeOut("slow");
            $('body').addClass('default-sidemenu');
			hovermenu();		
            $('body').removeClass('sidenav-toggled');
            $('body').removeClass('closed');
            $('body').removeClass('hover-submenu1');
            $('body').removeClass('default-sidebar');
            $('body').removeClass('hover-submenu');
            $('body').removeClass('icon-overlay');
            $('body').removeClass('icon-text');
        } else {
            $('body').removeClass('default-sidemenu');
         }
    });
	
	/*********************Default-menu closed********************/

	/*********************closed-menu open********************/

    $('#myonoffswitch23').click(function() {
        if (this.checked) {
			$("#global-loader").fadeOut("slow");
            $('body').addClass('closed');
            $('body').addClass('sidenav-toggled');
			hovermenu();		
            $('body').removeClass('default-sidemenu');
            $('body').removeClass('hover-submenu1');
            $('body').removeClass('default-sidebar');
            $('body').removeClass('hover-submenu');
            $('body').removeClass('icon-overlay');
            $('body').removeClass('icon-text');
        } else {
            $('body').removeClass('closed');
            $('body').removeClass('sidenav-toggled');
            $('body').addClass('default-sidemenu');
         }
    });

	/*********************closed-menu closed********************/

   /*********************hover-menu open********************/

    $('#myonoffswitch24').click(function() {
        if (this.checked) {
			$("#global-loader").fadeOut("slow");
            $('body').addClass('hover-submenu');
			hovermenu();		
            $('body').addClass('sidenav-toggled');
            $('body').removeClass('default-sidemenu');
            $('body').removeClass('hover-submenu1');
            $('body').removeClass('default-sidebar');
            $('body').removeClass('closed');
            $('body').removeClass('icon-overlay');
            $('body').removeClass('icon-text');
        } else {
            $('body').removeClass('hover-submenu');
            $('body').removeClass('sidenav-toggled');
            $('body').addClass('default-sidemenu');
         }
    });
	/*********************hover-menu closed********************/
	
   /*********************hover-menu-1 open********************/

   $('#myonoffswitch30').click(function() {
	if (this.checked) {
		$("#global-loader").fadeOut("slow");
		$('body').addClass('hover-submenu1');
		hovermenu();		
		$('body').addClass('sidenav-toggled');
		$('body').removeClass('default-sidemenu');
		$('body').removeClass('hover-submenu');
		$('body').removeClass('default-sidebar');
		$('body').removeClass('closed');
		$('body').removeClass('icon-overlay');
		$('body').removeClass('icon-text');
	} else {
		$('body').removeClass('hover-submenu1');
		$('body').removeClass('sidenav-toggled');
		$('body').addClass('default-sidemenu');
	 }
});
/*********************hover-menu-1 closed********************/

   /*********************icon-overlay open********************/

   $('#myonoffswitch25').click(function() {
	if (this.checked) {
		$("#global-loader").fadeOut("slow");
		$('body').addClass('icon-overlay');
		hovermenu();		
		$('body').addClass('sidenav-toggled');
		$('body').removeClass('default-sidemenu');
		$('body').removeClass('hover-submenu');
		$('body').removeClass('default-sidebar');
		$('body').removeClass('closed');
		$('body').removeClass('hover-submenu1');
		$('body').removeClass('icon-text');
	} else {
		$('body').removeClass('icon-overlay');
		$('body').removeClass('sidenav-toggled');
		$('body').addClass('default-sidemenu');
	 }
});

/*********************icon-overlay closed********************/

   /*********************icon-overlay open********************/

   $('#myonoffswitch29').click(function() {
	if (this.checked) {
		$("#global-loader").fadeOut("slow");
		$('body').addClass('icon-text');
		icontext();		
		$('body').addClass('sidenav-toggled');
		$('body').removeClass('default-sidemenu');
		$('body').removeClass('hover-submenu');
		$('body').removeClass('default-sidebar');
		$('body').removeClass('closed');
		$('body').removeClass('hover-submenu1');
		$('body').removeClass('icon-overlay');
	} else {
		$('body').removeClass('icon-text');
		$('body').removeClass('sidenav-toggled');
		$('body').addClass('default-sidemenu');
	 }
});

/*********************icon-overlay closed********************/
	

})(jQuery);


//______Success Notification
function not1(){
	notif({
		msg: "<b><i class='fa fa-check fs-20 me-2'></i></b> Well done Details Submitted Successfully",
		type: "success"
	});
}
$(document).ready(function() {
	$("#Password-toggle a").on('click', function(event) {
        event.preventDefault();
        if($('#Password-toggle input').attr("type") == "text"){
            $('#Password-toggle input').attr('type', 'password');
            $('#Password-toggle i').addClass( "fe-eye-off" );
            $('#Password-toggle i').removeClass( "fe-eye" );
        }else if($('#Password-toggle input').attr("type") == "password"){
            $('#Password-toggle input').attr('type', 'text');
            $('#Password-toggle i').removeClass( "fe-eye-off" );
            $('#Password-toggle i').addClass( "fe-eye" );
        }
    });
	
	$("#Password-toggle1 a").on('click', function(event) {
        event.preventDefault();
        if($('#Password-toggle1 input').attr("type") == "text"){
            $('#Password-toggle1 input').attr('type', 'password');
            $('#Password-toggle1 i').addClass( "fe-eye-off" );
            $('#Password-toggle1 i').removeClass( "fe-eye" );
        }else if($('#Password-toggle1 input').attr("type") == "password"){
            $('#Password-toggle1 input').attr('type', 'text');
            $('#Password-toggle1 i').removeClass( "fe-eye-off" );
            $('#Password-toggle1 i').addClass( "fe-eye" );
        }
    });
	
	$("#Password-toggle2 a").on('click', function(event) {
        event.preventDefault();
        if($('#Password-toggle2 input').attr("type") == "text"){
            $('#Password-toggle2 input').attr('type', 'password');
            $('#Password-toggle2 i').addClass( "fe-eye-off" );
            $('#Password-toggle2 i').removeClass( "fe-eye" );
        }else if($('#Password-toggle2 input').attr("type") == "password"){
            $('#Password-toggle2 input').attr('type', 'text');
            $('#Password-toggle2 i').removeClass( "fe-eye-off" );
            $('#Password-toggle2 i').addClass( "fe-eye" );
        }
    });
});