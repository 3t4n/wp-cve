jQuery(document).ready(function() {
	jQuery('.ewd-uwcf-welcome-screen-box h2').on('click', function() {
		var page = jQuery(this).parent().data('screen');
		EWD_UWCF_Toggle_Welcome_Page(page);
	});

	jQuery('.ewd-uwcf-welcome-screen-next-button').on('click', function() {
		var page = jQuery(this).data('nextaction');
		EWD_UWCF_Toggle_Welcome_Page(page);
	});

	jQuery('.ewd-uwcf-welcome-screen-previous-button').on('click', function() {
		var page = jQuery(this).data('previousaction');
		EWD_UWCF_Toggle_Welcome_Page(page);
	});

	jQuery('.ewd-uwcf-welcome-screen-save-table-mode-button').on('click', function() {

		var table_mode = jQuery('input[name="table_mode"]:checked').val(); 

		var params = {
			table_mode: table_mode,
			nonce: ewd_uwcf_getting_started.nonce,
			action: 'ewd_uwcf_welcome_set_options'
		};

		var data = jQuery.param( params );
		
		jQuery.post(ajaxurl, data, function(response) {

			jQuery('.ewd-uwcf-welcome-screen-save-table-mode-button').after('<div class="ewd-uwcf-save-message"><div class="ewd-uwcf-save-message-inside">Option has been saved.</div></div>');
			jQuery('.ewd-uwcf-save-message').delay(2000).fadeOut(400, function() {jQuery('.ewd-uwcf-save-message').remove();});
		});
	});

	jQuery('.ewd-uwcf-welcome-screen-save-options-button').on('click', function() {

		var color_filtering = jQuery('input[name="color_filtering"]:checked').val(); 
		var size_filtering = jQuery('input[name="size_filtering"]:checked').val(); 
		var category_filtering = jQuery('input[name="category_filtering"]:checked').val();
		var tag_filtering = jQuery('input[name="tag_filtering"]:checked').val();
		
		var params = {
			color_filtering: color_filtering,
			size_filtering: size_filtering,
			category_filtering: category_filtering,
			tag_filtering: tag_filtering,
			nonce: ewd_uwcf_getting_started.nonce,
			action: 'ewd_uwcf_welcome_set_options'
		};

		var data = jQuery.param( params );

		jQuery.post(ajaxurl, data, function(response) {

			jQuery('.ewd-uwcf-welcome-screen-save-options-button').after('<div class="ewd-uwcf-save-message"><div class="ewd-uwcf-save-message-inside">Options have been saved.</div></div>');
			jQuery('.ewd-uwcf-save-message').delay(2000).fadeOut(400, function() {jQuery('.ewd-uwcf-save-message').remove();});
		});
	});

	jQuery('.ewd-uwcf-welcome-screen-add-color-button').on('click', function() {

		jQuery('.ewd-uwcf-welcome-screen-show-created-colors').show();

		var color_name = jQuery('.ewd-uwcf-welcome-screen-add-color-name input').val();
		var color_description = jQuery('.ewd-uwcf-welcome-screen-add-color-description textarea').val();
		var normal_fill = jQuery('#normal_fill_color').val();
		var color_image = jQuery('#color_image').val();

		jQuery('.ewd-uwcf-welcome-screen-add-color-name input').val('');
		jQuery('.ewd-uwcf-welcome-screen-add-color-description textarea').val('');
		jQuery('#normal_fill_color').val('').css('background', '#ffffff');
		jQuery('#color_image').val('');

		var params = {
			color_name: color_name,
			color_description: color_description,
			normal_fill: normal_fill,
			color_image: color_image,
			nonce: ewd_uwcf_getting_started.nonce,
			action: 'ewd_uwcf_welcome_add_color'
		};

		var data = jQuery.param( params );

		jQuery.post(ajaxurl, data, function(response) {

			var HTML = '<tr class="ewd-uwcf-welcome-screen-color">';
			HTML += '<td class="ewd-uwcf-welcome-screen-color-name">' + color_name + '</td>';
			HTML += '<td class="ewd-uwcf-welcome-screen-color-description">' + color_description + '</td>';
			HTML += '</tr>';

			jQuery('.ewd-uwcf-welcome-screen-show-created-colors').append( HTML );
		});
	});

	jQuery('.ewd-uwcf-welcome-screen-add-size-button').on('click', function() {

		jQuery('.ewd-uwcf-welcome-screen-show-created-sizes').show();

		var size_name = jQuery('.ewd-uwcf-welcome-screen-add-size-name input').val();
		var size_description = jQuery('.ewd-uwcf-welcome-screen-add-size-description textarea').val();

		jQuery('.ewd-uwcf-welcome-screen-add-size-name input').val('');
		jQuery('.ewd-uwcf-welcome-screen-add-size-description textarea').val('');

		var params = {
			size_name: size_name,
			size_description: size_description,
			nonce: ewd_uwcf_getting_started.nonce,
			action: 'ewd_uwcf_welcome_add_size'
		};

		var data = jQuery.param( params );

		jQuery.post(ajaxurl, data, function(response) {

			var HTML = '<tr class="ewd-uwcf-welcome-screen-size">';
			HTML += '<td class="ewd-uwcf-welcome-screen-size-name">' + size_name + '</td>';
			HTML += '<td class="ewd-uwcf-welcome-screen-size-description">' + size_description + '</td>';
			HTML += '</tr>';

			jQuery('.ewd-uwcf-welcome-screen-show-created-sizes').append(HTML);
		});
	});

	jQuery( 'input[data-inputname="color_filtering"]' ).on( 'click', function() {

		jQuery( '#ewd-uwcf-welcome-screen-color-taxonomy' ).toggleClass( 'ewd-uwcf-hidden' );
	});

	jQuery( 'input[data-inputname="size_filtering"]' ).on( 'click', function() {

		jQuery( '#ewd-uwcf-welcome-screen-size-taxonomy' ).toggleClass( 'ewd-uwcf-hidden' );
	});
});

jQuery(document).ready(function($){
 
    var custom_uploader;
 
    jQuery( '#color_image_button' ).click(function(e) {
 
        e.preventDefault();
 
        //If the uploader object has already been created, reopen the dialog
        if (custom_uploader) {
            custom_uploader.open();
            return;
        }
 
        //Extend the wp.media object
        custom_uploader = wp.media.frames.file_frame = wp.media({
            title: 'Choose Image',
            button: {
                text: 'Choose Image'
            },
            multiple: false
        });
 
        //When a file is selected, grab the URL and set it as the text field's value
        custom_uploader.on('select', function() {
            attachment = custom_uploader.state().get('selection').first().toJSON();
            jQuery('input[name="color_image"]').val(attachment.url);
        });
 
        //Open the uploader dialog
        custom_uploader.open();
 
    });
});

function EWD_UWCF_Toggle_Welcome_Page(page) {
	jQuery('.ewd-uwcf-welcome-screen-box').removeClass('ewd-uwcf-welcome-screen-open');
	jQuery('.ewd-uwcf-welcome-screen-' + page).addClass('ewd-uwcf-welcome-screen-open');
}