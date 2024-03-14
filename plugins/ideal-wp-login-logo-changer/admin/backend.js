
(function($){

	$(document).ready(function(){

		// Depend on saved type to hide/show color/image field
		if( iwllc_admin.bg_type === 'color' ){
			$('.type_image').hide();
			$('.type_color').show();
		}
		else{
			$('.type_color').hide();
			$('.type_image').show();
		}

		$('.iwllc_wp_bg_select').on('change', function(){

			// Get the selected value
			var selected_type = $(this).find(":selected").val();

			if( selected_type === 'color' ){
				$('.type_image').hide();
				$('.type_color').show();
			}
			else{
				$('.type_color').hide();
				$('.type_image').show();
			}

		});

		// Initialize color picker
		$('.iwllc_wp_bg_color').wpColorPicker();

		// Image Popup Function
		$('[id=iwllc-upload-btn]').click(function(e) {
			e.preventDefault();
			var btn = $(this);
			var image = wp.media({ 
				title: 'Upload Logo',
				multiple: false
			}).open()
			.on('select', function(e){
				var uploaded_image = image.state().get('selection').first();
				console.log(uploaded_image);
				var image_url = uploaded_image.toJSON().url;
				btn.closest('td').find('input[type=text]').val(image_url);
			});
		});



	});

})(jQuery);

