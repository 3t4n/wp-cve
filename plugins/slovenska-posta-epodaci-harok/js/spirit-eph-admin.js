(function( $ ) {
	'use strict';

	tsseph_register_actions();
	tsseph_register_lic_actions();
	tsseph_check_form();
	
	$('select[name="tsseph_options[SposobUhrady]"]').change( function() {
		tsseph_check_form();
	})

	function tsseph_check_form() {

		var eph_payment_method = $('select[name="tsseph_options[SposobUhrady]"]');

	/*
		Ak v elemente <SposobUhrady> uvediete kód pre bezhotovostný spôsob 
		úhrady (1 - poštovné úverované, 2 - výplatný stroj, 3 - platené prevodom, 8
		– faktúra, 9 – online (platobná karta) musí byť element
		<Hmotnost>vyplnený vždy.
	*/
		if (eph_payment_method.val() == 1 || eph_payment_method.val() == 2 
			|| eph_payment_method.val() == 3 || eph_payment_method.val() == 8 
			|| eph_payment_method.val() == 9  ) {
			eph_payment_method.parent().find('.tsseph_warning').show();
		} else {
			eph_payment_method.parent().find('.tsseph_warning').hide();
		}

		//Ak sa vyberie faktúra, je potrebné upozorniť používateľa o zadaní podacích čísel.
		if (eph_payment_method.val() == 8) {
			eph_payment_method.parent().find('.tsseph_warning_faktura').show();
		}
		else {
			eph_payment_method.parent().find('.tsseph_warning_faktura').hide();
		}
	}

    
})( jQuery );

/*
* Ajax to update shipping method change
*/
function tsseph_shipping_change(event, url) {
	jQuery.ajax(
		{
			type: "post",
			action: "tsseph_set_shipping_method",
			url: url,
			data: {
				shipping_method_id: jQuery(event).val()
			},
			success: function(response){
				jQuery(event).parent().find('.eph_save_status').css('display', 'inline-block');
				 setTimeout(function() {
				 	jQuery(event).parent().find('.eph_save_status').fadeOut();
				 }, 3000);
			}
		});
}

/*
* Show activate form
*/
function tsseph_show_activate_form(el) {
	jQuery(el).closest('.tsseph-bonus').find('.tsseph-activate-wrap').toggle("slow");
}

/*
* Register jQuery actions
*/
function tsseph_register_actions() {

	//Show/hide Rovnaka navratová hodnota
	jQuery('#tsseph_RovnakaNavratova').change( function() {
		if (jQuery(this).is(':checked')) {
			jQuery('.tsseph_return_address').hide('500');
		}
		else {
			jQuery('.tsseph_return_address').show('500');
		}
	});

	//Show/hide podacie cisla box
	jQuery('#tsseph_PodacieCislaEnabled').change( function() {
		if (jQuery(this).is(':checked')) {
			jQuery('.tsseph_podacie_cisla').show('500');
		}
		else {
			jQuery('.tsseph_podacie_cisla').hide('500');
		}
	});	

	//Show/hide log
	jQuery('#spirit-eph-show-log').click(function() {
		jQuery('#spirit-eph-log ').show();
		jQuery('#spirit-eph-show-log').hide();
	});

	//Init Select2 for PaymentType
	jQuery(document).ready(function($) {
        jQuery('select[name="tsseph_options[PaymentType][]"]').select2();
    });
}

/*
* License activation
*/
function tsseph_register_lic_actions() {
	jQuery('.eph-activate').click(function() {

		var bonus_block = jQuery(this).closest('.tsseph-bonus');
		var extension_id = jQuery(bonus_block).find('.ext-id').val();

		jQuery(bonus_block).find('.ajax-loader').css('visibility', 'visible');
		jQuery(this).attr('disabled','disabled');

		var data = {
			action: 'tsseph_manage_license',
			task: 'activate',
			extension_id: extension_id,
			license_key: jQuery(bonus_block).find('input[name=ext_lic_key_' + extension_id + ']').val()
		};

		jQuery.ajax(
			{
				type: "post",
				url: tsseph_ajax_object.ajax_url,
				data: data,
				success: function(response){

					var response_data = JSON.parse(response);

					if (response_data.lc_result == "success") {
						jQuery(bonus_block).find('.tsseph-activate-wrap').toggle("slow");
						jQuery(bonus_block).replaceWith(response_data.extension_block);
						jQuery('input[name=ext_check_' + extension_id + ']').prop( "checked", true );
						tsseph_register_lic_actions();
						reload_settings_page();
					}
					
					jQuery(bonus_block).find('.ajax-loader').css('visibility', 'hidden');
					jQuery(this).removeAttr('disabled');

				}
			} 
		)

	});

	/*
	* License deactivation
	*/
	jQuery('.eph-deactivate').click(function() {

		var bonus_block = jQuery(this).closest('.tsseph-bonus');
		var extension_id = jQuery(bonus_block).find('.ext-id').val();

		jQuery(bonus_block).find('.ajax-loader').css('visibility', 'visible');
		jQuery(this).attr('disabled','disabled');

		var data = {
			action: 'tsseph_manage_license',
			task: 'deactivate',
			extension_id: extension_id,
			license_key: jQuery(bonus_block).find('input[name=ext_lic_key_' + extension_id + ']').val()
		};

		jQuery.ajax(
			{
				type: "post",
				url: tsseph_ajax_object.ajax_url,
				data: data,
				success: function(response){

					var response_data = JSON.parse(response);

					if (response_data.lc_result == "success") {
						jQuery(bonus_block).find('.tsseph-activate-wrap').toggle("slow");
						jQuery(bonus_block).replaceWith(response_data.extension_block);
						tsseph_register_lic_actions();
						reload_settings_page();
					}
					
					jQuery(bonus_block).find('.ajax-loader').css('visibility', 'hidden');
					jQuery(this).removeAttr('disabled');

				}
			} 
		)

	});
}

/*
* Bonus callback
*/
jQuery('.switch input[type="checkbox"]').change(function() {
	var ext_id = jQuery(this).parent().find('.ext-id').val();
	var ext_status = jQuery(this).parent().find('input[type=checkbox]').is(":checked");

	jQuery.ajax(
		{
			type: "post",
			data: {
				action: "tsseph_bonus_ext_status",
				ext_id: ext_id,
				ext_status: (ext_status ? 1 : 0)
			},
			url: tsseph_ajax_object.ajax_url,
			success: function(response){
				reload_settings_page();
			}
		});
});	

/*
* Reload settings page
*/
function reload_settings_page() {
	jQuery.ajax(
		{
			type: "post",
			data: {
				action: "tsseph_reload_settings_page"
			},
			url: tsseph_ajax_object.ajax_url,
			success: function(response){
				jQuery('.form-content').replaceWith(response);
				tsseph_register_actions();
			}
		});
}