jQuery(document).ready(function(){

	var mediaUploader;

	jQuery('.woo-inpost-logo-upload-btn').on('click',function(e) {
		e.preventDefault();


		if( mediaUploader ){
			mediaUploader.open();
			return;
		}

		mediaUploader = wp.media.frames.file_frame =wp.media({
			title: 'Choose a shipping method logo',
			button: {
				text: 'Choose Image'
			},
			multiple:false
		});

		mediaUploader.on('select', function(){
			var attachment = mediaUploader.state().get('selection').first().toJSON();
			jQuery('#woocommerce_easypack_logo_upload').val(attachment.url);
			jQuery('#woo-inpost-logo-preview').attr('src',attachment.url);
            jQuery('#woo-inpost-logo-preview').css('display', 'block');
            jQuery('#woo-inpost-logo-action').css('display', 'block');
		});
		mediaUploader.open();
	});

    jQuery('#woo-inpost-logo-delete').on('click',function(e) {
        e.preventDefault();
        jQuery('#woo-inpost-logo-preview').css('display', 'none');
        jQuery('#woocommerce_easypack_logo_upload').val('');
        jQuery('#woo-inpost-logo-action').css('display', 'none');
    });

	function easypack_dispatch_point() {
		if ( jQuery('#easypack_api_country').val() === 'pl' || jQuery('#easypack_api_country').val() === 'test-pl' ) {
			jQuery('#easypack_dispatch_point_name').closest('table').prev().css('display','block');
			jQuery('#easypack_dispatch_point_name').closest('table').css('display','table');
			jQuery('#easypack_crossborder_password').closest('tr').css('display','table-row');
		} 		
		else {
			jQuery('#easypack_dispatch_point_name').closest('table').prev().css('display','none');
			jQuery('#easypack_dispatch_point_name').closest('table').css('display','none');
			jQuery('#easypack_crossborder_api_url').closest('tr').css('display','none');
			jQuery('#easypack_crossborder_password').closest('tr').css('display','none');
			jQuery('#easypack_default_package_size').val('A');
			jQuery('#easypack_default_package_size').closest('tr').css('display','none');
		}
	}
	easypack_dispatch_point();
	
	jQuery('#easypack_api_country').change(function(){
		var url = 'https://api-'+jQuery('#easypack_api_country').val()+'.easypack24.net';
		url = url.replace('api-test', 'test-api');
		jQuery('#easypack_api_url').val(url);
		easypack_dispatch_point();

		if (jQuery('#easypack_api_country').val() === 'gb') {
            jQuery('#easypack_api_url').val('https://sandbox-api-shipx-pl.easypack24.net');
		}
	});

	jQuery('.easypack_parcel').click(function () {

		var allowReturnStickers = jQuery(this).data('allow_return_stickers') === 1;
		if (jQuery(this).is(':checked')) {
			if (false === allowReturnStickers) {
				jQuery('#get_return_stickers').prop('disabled', true);
			}
		} else {
			if (false === allowReturnStickers) {
				jQuery('#get_return_stickers').removeAttr('disabled');
			}
		}
	});

	jQuery('#woo_inpost_dpoint_add').click(function(e) {
		e.preventDefault();
		var cloned = jQuery('#woo_inpost_dpoint-cell .woo_inpost_dpoint-cell-wraper:last').clone();
		jQuery('#woo_inpost_dpoint-cell').append(cloned);

		jQuery('.woo_inpost_dpoint_selected').each(function(index, obj){
			jQuery(this).val(index);
		});

	});

    document.addEventListener( 'click', function (e) {
        e = e || window.event;
        var target = e.target || e.srcElement;

        if ( target.classList.contains( 'woo_inpost_dpoint-remove' ) ) {
            target.parentNode.parentNode.parentNode.removeChild( target.parentNode.parentNode );
        }

    }, false );
	
	// integration with Flexible Shipping
	let fs_integration_select = jQuery('#woocommerce_flexible_shipping_fs_inpost_pl_method');

	if(jQuery(fs_integration_select).val() !== 'easypack_parcel_machines_weekend') {
        jQuery('.fs-inpost-pl-weekend').each(function(i,elem) {
            jQuery(elem).closest('tr').hide();
        });
    }

	jQuery(fs_integration_select).on('change', function() {
        if(jQuery(this).val() === 'easypack_parcel_machines_weekend') {

            jQuery('.fs-inpost-pl-weekend').each(function(i,elem) {
                jQuery(elem).closest('tr').show();
            });

        } else {

            jQuery('.fs-inpost-pl-weekend').each(function(i,elem) {
                jQuery(elem).closest('tr').hide();
            });
        }

        if(jQuery(this).val() !== '0') {
            jQuery('#woocommerce_flexible_shipping_method_integration').val('').trigger('change');
        }

        jQuery('#woocommerce_flexible_shipping_method_integration').on('change', function() {
            if(jQuery(this).val()) {
                jQuery(fs_integration_select).val('0').trigger('change');
            }
        })
	});

});