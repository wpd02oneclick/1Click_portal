$(function(e){

	// ______________Ckeditor
	ClassicEditor
	.create( document.querySelector( '.editckeditor' ), {
		
	toolbar: {
		items: [
			'heading',
			'|',
			'bold',
			'italic',
			'link',
			'bulletedList',
			'numberedList',
			'|',
			'outdent',
			'indent',
			'|',
			'imageUpload',
			'blockQuote',
			'insertTable',
			'mediaEmbed',
			'undo',
			'redo'
		],
		rtl:"true",
	},
	language: 'en',
	image: {
		toolbar: [
			'imageTextAlternative',
			'imageStyle:inline',
			'imageStyle:block',
			'imageStyle:side'
		]
	},
	table: {
		contentToolbar: [
			'tableColumn',
			'tableRow',
			'mergeTableCells'
		]
	},
		licenseKey: '',
		
		
		
	} )
	.then( editor => {
		window.editor = editor;
   
		
		
		
	} )
	.catch( error => {
		console.error( 'Oops, something went wrong!' );
		console.error( 'Please, report the following error on https://github.com/ckeditor/ckeditor5/issues with the build id and the error stack trace:' );
		console.warn( 'Build id: fgydboej4r6a-nohdljl880ze' );
		console.error( error );
	} );
	
	
	// ______________ Attach Remove
	$(document).on('click', '[data-bs-toggle="remove"]', function(e) {
		let $a = $(this).closest(".attach-supportfiles");
		$a.remove();
		e.preventDefault();
		return false;
	});

	// ______________Edit Summernote
	$(document).on("click", '.supportnote-icon', function () {    
		if(document) {
			$('body').toggleClass('add-supportnote');
			localStorage.setItem("add-supportnote", "True");
		}
		else {
			$('body').removeClass('add-supportnote');
			localStorage.setItem("add-supportnote", "false");
		}
	});
	$(document).on("click", '.dismiss-btn', function () {    
		$('body').removeClass('add-supportnote');
	});

	// ______________Edit Summernote
	$(document).on("click", '.reopen-button', function () {    
		if(document) {
			$('body').toggleClass('add-reopencard');
			localStorage.setItem("add-reopencard", "True");
		}
		else {
			$('body').removeClass('add-reopencard');
			localStorage.setItem("add-reopencard", "false");
		}
	});

 });

 