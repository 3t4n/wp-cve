(function($) {
	// The width and height of the captured photo. We will set the
	// width to the value defined here, but the height will be
	// calculated based on the aspect ratio of the input stream.

	var width = 320;    // We will scale the photo width to this
	var height = 0;     // This will be computed based on the input stream

	// |streaming| indicates whether or not we're currently streaming
	// video from the camera. Obviously, we start at false.

	var streaming = false;

	var local_stream;
	// The various HTML elements we need to configure or control. These
	// will be set by the startup() function.

	var video = null;
	var canvas = null;
	var photo = null;

	function show_classic_login()
	{
		$('.datapeen-face-factor').addClass('datapeen-face-factor-use-classic');
	}

	function startup() {
		video = document.getElementById('video');
		canvas = document.getElementById('canvas');
		photo = document.getElementById('photo');

		navigator.mediaDevices.getUserMedia({video: true, audio: false})
			.then(function(stream) {
				console.log('get user media ok');
				local_stream = stream;
				video.srcObject = stream;
				video.play();
			})
			.catch(function(err) {
				console.log("An error occurred again: " + err);
				show_classic_login();
			});

		video.addEventListener('canplay', function(ev){
			if (!streaming) {
				height = video.videoHeight / (video.videoWidth/width);

				// Firefox currently has a bug where the height can't be read from
				// the video, so we will make assumptions if this happens.

				if (isNaN(height)) {
					height = width / (4/3);
				}

				video.setAttribute('width', width);
				video.setAttribute('height', height);
				canvas.setAttribute('width', width);
				canvas.setAttribute('height', height);
				streaming = true;
			}
		}, false);


		clearphoto();
	}

	// Fill the photo with an indication that none has been
	// captured.

	function clearphoto() {
		var context = canvas.getContext('2d');
		context.fillStyle = "#AAA";
		context.fillRect(0, 0, canvas.width, canvas.height);

		var data = canvas.toDataURL('image/jpeg');
		photo.setAttribute('src', data);
	}


	function start_face_login() {
		var context = canvas.getContext('2d');
		if (width && height) {
			canvas.width = width;
			canvas.height = height;
			context.drawImage(video, 0, 0, width, height);

			var data = canvas.toDataURL('image/jpeg');

			$.post(face_factor.ajaxurl, {
				'face' : data,
				'action': 'datapeen_face_factor_recognize_face'
			}, function (response) {

				//reload loading class from the button
				$('#face-factor-login-button').removeClass('ff-loading');
				console.log(response);
				//remove the loading icon
				if (typeof response.data.full_name !== 'undefined')
				{
					//display the next step
					var message = 'Hello <strong>' + response.data.full_name + '</strong>. Is that you?';
					$('#confirm-username').html(message);
					var pin_html = response.data.html;

					$('#step-3').html(pin_html);

					to_step_2();
				} else
				{
					swal("Error", response.data.reason, 'error');
					return;
					//
				}
			});

			photo.setAttribute('src', data);
		} else {
			clearphoto();
		}
	}


	/**
	 * After image verification OK, turn off camera, hide step 1 and show step 2
	 */
	function to_step_2()
	{
		streaming = false;
		try {
			local_stream.getTracks()[0].stop();
		} catch (e) {
			console.log(e);
		}

		$('#step-1').hide();
		$('#step-2').show();

	}

	/**
	 * User confirms he wants to login with that username, go to step 3
	 */
	function to_step_3()
	{
		$('#step-2').hide();
		$('#step-3').show();
	}



	function verify_pin()
	{
		//in case the select is available, get the action based on the select value
		var method = '';
		var pin = '';
		var action = '';
		if ($('.dp-ff-select').length > 0)
		{
			method = $('.dp-ff-select').val();
		} else
		{
			method = $('.pin-input').attr('data-method');
		}


		pin = $('#' + 'pin-' + method).val();

		if (method === 'email')
		{
			action = 'datapeen_face_factor_verify_email_pin';
		} else if (method==='google_authenticator')
			action = 'datapeen_face_factor_verify_authenticator_pin';



		//in case the select isn't available,

		if (pin.trim() === '')
		{
			alert('Invalid PIN');
			return;
		}


		console.log('pin', pin);
		console.log('action', action);


		$.post(face_factor.ajaxurl, {
			'pin' : pin,
			'action': action
		}, function(response){
			if (response.status === 'OK')
				window.location.href = response.URL;
		});
	}
	$(function(){
		startup();

		$('#face-factor-login-button').on('click', function (e) {
			$(this).addClass('ff-loading');
			e.preventDefault();
			start_face_login();
		});

		$('#confirm-username-button').on('click', function(e){
			$(this).addClass('ff-loading');
			to_step_3();
			e.preventDefault();
		});

		//back to step 1
		$('#reject-username-button').on('click', function(e){
			//back to step 1
			$('#step-2').hide();
			$('#step-1').show();
			$('#face-factor-login-button').removeClass('ff-loading');

			e.preventDefault();

		});


		$(document).on('click','#verify-pin-button' , function (e) {
			$(this).addClass('ff-loading');
			e.preventDefault();
			verify_pin();
		});

		$('#use-classic-login').on('click', function(e){
			//stop camera
            e.preventDefault();
			streaming = false;
			show_classic_login();
		});

		//display PIN input on select change
		$(document).on('change', 'select.dp-ff-select', function(){
			var selected_pin = '.dp-ff-pin-' + $(this).val();

			//hide other methods
			$('.dp-ff-pin-input').hide();

			$(selected_pin).show();
			$()
		});


	});
})(jQuery);
