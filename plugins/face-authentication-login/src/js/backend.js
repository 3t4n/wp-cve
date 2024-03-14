(function ($) {

	$(document).ready( function($) {


		$('a[data-rel^=lightcase]').lightcase();

		$('#datapeen-get-faces').click(function(e){
			e.preventDefault();

		});


		/**
		 * ------------------ CAMERA FUNCTIONS ------------------
		 */

		var video = null;
		var canvas = null;
		var height = 250;
		var width = 333;
		var streaming = false;
		function request_camera() {
			video = document.getElementById('video');
			canvas = document.getElementById('canvas');

			navigator.mediaDevices.getUserMedia({video: true, audio: false})
				.then(function(stream) {
					video.srcObject = stream;
					video.play();

					//hide the request camera button
					$('#dp-turn-on-camera').hide();
					$('#dp-add-face').show();
				})
				.catch(function(err) {
					console.log("An error occurred again: " + err);
				});

			video.addEventListener('canplay', function(ev){
				console.log('video can plan')
				if (!streaming) {
					height = video.videoHeight / (video.videoWidth/width);

					// Firefox currently has a bug where the height can't be read from
					// the video, so we will make assumptions if this happens.

					if (isNaN(height)) {
						height = width / (16/9);
					}

					video.setAttribute('width', width);
					video.setAttribute('height', height);
					canvas.setAttribute('width', width);
					canvas.setAttribute('height', height);

					console.log('streaming turning on');
					streaming = true;
				}
			}, false);
		}


		$('#dp-turn-on-camera').on('click', function(e){
			e.preventDefault();
			request_camera();
		});


		$(document).on('click', '.single-face .fa-trash', function(e){
			e.preventDefault();
			var image_id = $(this).attr('data-id');

			$.post(ajaxurl, {
				'action' : 'datapeen_ff_remove_face',
				'image_id': image_id
			}, function(response){
				var data = JSON.parse(response);

				if (data.status.toLowerCase() == 'success')
				{
					get_face_from_server();
					swal('Done', 'Image deleted', 'success');
				}
				console.log(response);
			});

		});


		function get_face_from_server()
		{
			$.post(ajaxurl, {
				'action' : 'datapeen_ff_get_faces',
			}, function(response){

				if (response == null)
					return;
				console.log(response);
				// try {
				// 	response = JSON.parse(response);
				// } catch (e) {
				//
				// 	console.log('response is not a valid JSON');
				// 	return;
				// }

				console.log(response);

				if (typeof response.data === 'undefined')
				{
					console.log('response.data is null');
					return;
				}
				var images = response.data.images;

				console.log('images are', images);

				if (images == null)
				{
					console.log('images are null');
					return;
				}

				images = images.replace(/"/g, '');
				images = images.replace(/'/g, '"');

				console.log('new img', images);
				try {
					images = JSON.parse(images);
				} catch (e) {
					console.log(e);
					console.log('JSON parse images string failed');
					return;
				}


				var html = '';
				for (var i =0; i< images.length; i++)
				{
					html+= '<div class="single-face"> <img style="width: 120px;" src="https://wpfacelogin.com' + images[i].url +'"><i class="fas fa-trash" data-id="'+images[i].uuid+'"></i></div>';
				}

				$('#all-images').html(html);


			});
		}


		get_face_from_server();


		$('#dp-add-face').click(function(e) {

			e.preventDefault();

			var context = canvas.getContext('2d');


			context.drawImage(video, 0, 0, width, height);

			var face_data = canvas.toDataURL('image/jpeg');

			$.post(ajaxurl, {
				'action' : 'datapeen_ff_add_new_face',
				'face_image': face_data
			}, function(response){

                response = JSON.parse(response);
				if (response.status.toLowerCase() === 'success')
				{
					get_face_from_server();

					swal("Success", response.data.reason, 'success');
				} else
				{
					swal("Error", response.data.reason, 'error');
				}
			});
		});




		$('#authenticator_verification_button').on('click', function (e) {
			e.preventDefault();
			var auth_code = $('#authenticator_verification_code').val();
			var auth_key = $('#authenticator_generated_key').val();
			var user_id = $('#authenticator_user_id').val();

			console.log('key', auth_key);
			console.log('code', auth_code);
			console.log('id', user_id);

			$.post(ajaxurl, {
				action: 'verify_google_authenticator_method',
				auth_code: auth_code,
				auth_key: auth_key,
				user_id: user_id
			}, function(response) {
				if (response.status === 'OK')
				{
					swal("", response.message, "success", {
						button: "OK",
					});
				} else
				{
					swal("", response.message, "error", {
						button: "OK",
					});
				}

			})

		})


		$('#verify-token-button').on('click', function(e){
			e.preventDefault();
			var verify_token = $('#verify_token').val();

			$.post(ajaxurl, {
				action: 'datapeen_face_factor_verify_token',
				verify_token: verify_token
			}, function(response){
                if (response.status == 'success')
				{
					swal('', response.message, 'success');
					setTimeout(function(){

						window.location.reload();

					}, 1000)
				}
                else
                    swal('', response.message, 'error');
				console.log(response);
			});


		});
	});


})(jQuery);


