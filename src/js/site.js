jQuery(document).ready(function($) {
	'use strict';

	$('#page_header').bannerImage();



	// Floating Menu
	if ($('#child_links').length) {
		(function() {
			var offset = $('#child_links').offset(),
				marginTop = $('#child_links').css('margin-top');

			var topPadding = 200;
			$(window).scroll(function() {
				if ($(window).scrollTop() > (offset.top - topPadding)) {
					$('#child_links').stop().css({
						marginTop: $(window).scrollTop() - offset.top + topPadding
					});
				} else {
					$('#child_links').stop().css({
						marginTop: marginTop
					});
				}
			});
		})();
	}

	// add caret to drop menu
	$('ul.sub-menu').each(function() {
		$(this).parent('li').addClass('has_submenu');
	});

	$('#kt_contact_widget').submit(function(e) {

		var formData = new FormData($('#kt_contact_widget')[0]),
			actionUrl = $(this).attr('action');

		e.preventDefault();
		$.ajax({
			type: 'POST',
			xhr: function() { // custom xhr
				var myXhr = $.ajaxSettings.xhr();
				if (myXhr.upload) { // check if upload property exists
					myXhr.upload.addEventListener('progress', progressHandlingFunction, false); // for handling the progress of the upload
				}
				return myXhr;
			},
			url: actionUrl,
			data: formData,
			cache: false,
			contentType: false,
			processData: false,
			dataType: 'json',
			success: function(data) {
				console.log(data);
				if (!data.status) {
					$('#kt_contact_success').hide();
					$('#kt_contact_error').html(data.errors[0].msg).show(500);
					$('#upload_progress').hide(250);
				} else {
					$('#kt_contact_error').hide();
					$('#kt_contact_success').slideDown(500).delay(3500).slideUp(500);
					$('#kt_contact_widget').get(0).reset();
				}
			},
			error: function(data) {
				console.log(data);
			}
		});
	});

	function progressHandlingFunction(e) {
		if (e.lengthComputable) {
			$('progress').attr({
				value: e.loaded,
				max: e.total
			});
		}
	}


	$('#contactform').submit(function(e) {
		//console.log(e);
		$('#upload_progress').slideDown(250);
		var formData = new FormData($('#contactform')[0]);
		var actionUrl = $(this).attr('action');

		e.preventDefault();
		$.ajax({
			type: 'POST',
			xhr: function() { // custom xhr
				var myXhr = $.ajaxSettings.xhr();
				if (myXhr.upload) { // check if upload property exists
					myXhr.upload.addEventListener('progress', progressHandlingFunction, false); // for handling the progress of the upload
				}
				return myXhr;
			},
			url: actionUrl,
			data: formData,
			cache: false,
			contentType: false,
			processData: false,
			dataType: 'json',
			success: function(data) {
				console.log(data);
				if (!data.status) {
					$('#server_success_msg').hide();
					$('#server_fail_msg').css('line-height', '5').html(data.errors[0].msg).show('slow');
					$('#upload_progress').hide(250);
				} else {
					$("#server_fail_msg").hide();
					$("#server_success_msg").html("Your email was sent with success").show("slow");
					$('#upload_progress').hide(250);
					$('#contactform').get(0).reset();
				}
			},
			error: function(data) {
				console.log('there was an error: ' + data);
			}
		});
	});

	$('#kt_newsletter_widget #email').focus(function() {
		$('#kt_newsletter_widget #first_name, #kt_newsletter_widget #last_name, #kt_newsletter_widget .submit_wrap').slideDown(500);
	});

}); //jQuery document ready
