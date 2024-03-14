jQuery( document ).ready(function() {

	let buttonContent = '<i class="fas fa-file-import"></i>'+af2_demoimport_object.strings.import;
	let buttonContentLoad = '<span class="af2_hide loading">&nbsp;<i class="fas fa-circle-notch fa-spin"></i></span>';

    jQuery(document).on('click', '.af2_import_file', function(e) {
		e.preventDefault();
        const selector = '#'+jQuery(this).attr('id')+'.af2_import_file';
		if(!jQuery('#af2_import_button').hasClass('af2_btn_disabled')) {
			jQuery(selector).prop('disabled', true);
			jQuery(selector).addClass('af2_btn_disabled');


			jQuery(selector).html(buttonContent+' - '+af2_demoimport_object.strings.wait+buttonContentLoad);
			jQuery(selector +' .loading').removeClass('af2_hide');

			jQuery.ajax({
                url: af2_menu_components_object.ajax_url,
                type: "POST",
                data: { 
					action: 'af2_demoimport',
					filename: jQuery(this).data('filename') 
				},
                success: (msg) => {
					jQuery(selector).removeAttr('disabled'); 
					jQuery(selector).removeClass('af2_btn_disabled'); 
					jQuery(selector +' .loading').addClass('af2_hide');
					jQuery(selector).html(af2_demoimport_object.strings.success); 
					jQuery(selector).addClass('af2_succeed_import'); 

					setTimeout(_ => {
						window.location.reload();
					}, 1000);
				},
                error: () => { }
        	});
		}
	});

});