var upload_image_button = false;

jQuery(document).ready(function($){
	if (jQuery('input[name="clicface_trombi_settings[trombi_profile_width_type]"]').length) {
		if ( jQuery('input[name="clicface_trombi_settings[trombi_profile_width_type]"]:checked').val() == 'fixed' ) {
			jQuery('#trombi_profile_width_size').removeClass('hidden');
		}
		jQuery('input[name="clicface_trombi_settings[trombi_profile_width_type]"]').change(function() {
			if ( $(this).val() == 'fixed' ) {
				jQuery('#trombi_profile_width_size').removeClass('hidden');
			} else {
				jQuery('#trombi_profile_width_size').addClass('hidden');
			}
		});
	}
	
	if (jQuery('input[name="clicface_trombi_settings[trombi_profile_height_type]"]').length) {
		if ( jQuery('input[name="clicface_trombi_settings[trombi_profile_height_type]"]:checked').val() == 'fixed' ) {
			jQuery('#trombi_profile_height_size').removeClass('hidden');
		}
		jQuery('input[name="clicface_trombi_settings[trombi_profile_height_type]"]').change(function() {
			if ( $(this).val() == 'fixed' ) {
				jQuery('#trombi_profile_height_size').removeClass('hidden');
			} else {
				jQuery('#trombi_profile_height_size').addClass('hidden');
			}
		});
	}
	
	$('.upload_image_button').click(function(e) {
		e.preventDefault();
		var custom_uploader = wp.media({
			title: 'Image',
			button: {
				text: 'Upload'
			},
			multiple: false
		})
		.on('select', function() {
			var attachment = custom_uploader.state().get('selection').first().toJSON();
			$('input#trombi_default_picture').val(attachment.url);
		})
		.open();
	});
	
});