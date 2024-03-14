jQuery(document).ready(function () {

    let debug_text = '';
	if(typeof easypack_settings.debug_notice != 'undefined' && easypack_settings.debug_notice !== null) {
		debug_text = '<p class="easypack_debug_notice">' + easypack_settings.debug_notice + '</p>';
	};

    if ( jQuery('#easypack_js_map_button').is(':checked') ) {
        jQuery('#easypack_button_output').prop('disabled', true);
        jQuery('#easypack_button_output').closest('.forminp-select').append(debug_text);
    } else {
        jQuery('#easypack_button_output').prop('disabled', false);
        jQuery('.easypack_debug_notice').each(function(ind, elem) {
            jQuery(elem).remove();
        });
    }

    jQuery('#easypack_js_map_button').on('change', function () {
        if (jQuery(this).is(':checked')) {
            jQuery('#easypack_button_output').prop('disabled', true);
            jQuery('#easypack_button_output').closest('.forminp-select').append(debug_text);
            
        } else {
            jQuery('#easypack_button_output').prop('disabled', false);
            jQuery('.easypack_debug_notice').each(function(ind, elem) {
                jQuery(elem).remove();
            });            
        }
    });

    if ( ! jQuery('#easypack_set_default_courier_dimensions').is(':checked') ) {
        jQuery('.easypack_hidden_setting').each(function (i, elem) {
            let parent = jQuery(elem).closest('tr[valign="top"]');
            jQuery(parent).css('display', 'none');
        });
    }

    jQuery('#easypack_set_default_courier_dimensions').on('change', function () {
        if (jQuery(this).is(':checked')) {
            jQuery('.easypack_hidden_setting').each(function (i, elem) {
                let parent = jQuery(elem).closest('tr[valign="top"]');
                jQuery(parent).fadeIn(300);
            });
        } else {
            jQuery('.easypack_hidden_setting').each(function (i, elem) {
                let parent = jQuery(elem).closest('tr[valign="top"]');
                jQuery(parent).fadeOut(100);
            });
        }
    });

    jQuery('#easypack_api_url').closest('tr').css('display', 'none');
    jQuery('#easypack_geowidget_url').closest('tr').css('display', 'none');

    jQuery('#easypack_api_url_button').click(function () {
        if (jQuery('#easypack_api_url').closest('tr').css('display') === 'none') {
            jQuery('#easypack_api_url').closest('tr').css('display', 'table-row');
            if (jQuery('#easypack_api_country').val() === 'gb' || jQuery('#easypack_api_country').val() === 'test-gb') {
                jQuery('#easypack_geowidget_url').closest('tr').css('display', 'table-row');
            }

        } else {
            jQuery('#easypack_api_url').closest('tr').css('display', 'none');
            jQuery('#easypack_geowidget_url').closest('tr').css('display', 'none');
        }
        return false;
    });


    function easypack_address_fields() {

        if (jQuery('#easypack_api_country').val() === 'gb' || jQuery('#easypack_api_country').val() === 'test-gb') {

            jQuery('#easypack_sender_flat_no').attr('required', false);

            jQuery('#easypack_sender_flat_no').closest('tr').css('display', 'none');

            jQuery('#easypack_sender_flat_no').closest('tr').css('display', 'table-row');
            jQuery('#easypack_sender_address2').closest('tr').css('display', 'table-row');

        } else {
            jQuery('#easypack_sender_street').attr('required', true);
            jQuery('#easypack_sender_building_no').attr('required', true);
            jQuery('#easypack_sender_flat_no').attr('required', false);
            jQuery('#easypack_sender_post_code').attr('required', true);

            jQuery('#easypack_sender_street').closest('tr').css('display', 'table-row');
            jQuery('#easypack_sender_building_no').closest('tr').css('display', 'table-row');
            jQuery('#easypack_sender_flat_no').closest('tr').css('display', 'table-row');
            jQuery('#easypack_sender_post_code').closest('tr').css('display', 'table-row');

            jQuery('#easypack_sender_flat_no').attr('required', false);

            jQuery('#easypack_sender_flat_no').closest('tr').css('display', 'none');
            jQuery('#easypack_sender_address2').closest('tr').css('display', 'none');

        }
    }

    function easypack_returns() {
        if (jQuery('#easypack_api_country').val() === 'gb' || jQuery('#easypack_api_country').val() === 'test-gb') {
            jQuery('#easypack_returns_page').closest('table').prev().css('display', 'none');
            jQuery('#easypack_returns_page').closest('table').css('display', 'none');
        } else {
            jQuery('#easypack_returns_page').closest('table').prev().css('display', 'block');
            jQuery('#easypack_returns_page').closest('table').css('display', 'table');
        }
    }

    function easypack_send_options() {
        if (jQuery('#easypack_api_country').val() === 'pl' || jQuery('#easypack_api_country').val() === 'test-pl'
        ) {
            jQuery('#easypack_default_send_method').closest('table').prev().css('display', 'block');
            jQuery('#easypack_default_send_method').closest('table').css('display', 'table');

        } else {
            jQuery('#easypack_default_send_method').closest('table').prev().css('display', 'none');
            jQuery('#easypack_default_send_method').closest('table').css('display', 'none');
            jQuery('#easypack_default_machine_id').attr('required', false);
        }
    }

    function easypack_country_change() {
        if (jQuery('#easypack_api_country').val() === '--') {

            jQuery('#easypack_tax_status').closest('table').prev().css('display', 'none');
            jQuery('#easypack_tax_status').closest('table').css('display', 'none');
            jQuery('#easypack_returns_page').closest('table').prev().css('display', 'none');
            jQuery('#easypack_returns_page').closest('table').css('display', 'none');
            jQuery('#easypack_default_send_method').closest('table').prev().css('display', 'none');
            jQuery('#easypack_default_send_method').closest('table').css('display', 'none');
            jQuery('#easypack_sender_first_name').closest('table').prev().css('display', 'none');
            jQuery('#easypack_sender_first_name').closest('table').css('display', 'none');
            jQuery('.button-primary').attr('disabled', true);
        } else {

            jQuery('#easypack_tax_status').closest('table').prev().css('display', 'block');
            jQuery('#easypack_tax_status').closest('table').css('display', 'table');
            jQuery('#easypack_returns_page').closest('table').prev().css('display', 'block');
            jQuery('#easypack_returns_page').closest('table').css('display', 'table');
            jQuery('#easypack_default_send_method').closest('table').prev().css('display', 'block');
            jQuery('#easypack_default_send_method').closest('table').css('display', 'table');
            jQuery('#easypack_sender_first_name').closest('table').prev().css('display', 'block');
            jQuery('#easypack_sender_first_name').closest('table').css('display', 'table');
            jQuery('.button-primary').attr('disabled', false);
            easypack_address_fields();
            easypack_returns();
            easypack_send_options();
        }
        if (jQuery('#easypack_api_country').val() === 'pl' || jQuery('#easypack_api_country').val() === 'test-pl') {
            jQuery('#easypack_dispatch_point_name').attr('required', true);
            jQuery('#easypack_dispatch_point_email').attr('required', true);
            jQuery('#easypack_dispatch_point_phone').attr('required', true);
            jQuery('#easypack_dispatch_point_office_hours').attr('required', false);
            jQuery('#easypack_dispatch_point_street').attr('required', true);
            jQuery('#easypack_dispatch_point_building_no').attr('required', true);
            jQuery('#easypack_dispatch_point_flat_no').attr('required', false);
            jQuery('#easypack_dispatch_point_post_code').attr('required', true);
            jQuery('#easypack_dispatch_point_city').attr('required', true);
        } else {
            jQuery('#easypack_dispatch_point_name').attr('required', false);
            jQuery('#easypack_dispatch_point_email').attr('required', false);
            jQuery('#easypack_dispatch_point_phone').attr('required', false);
            jQuery('#easypack_dispatch_point_office_hours').attr('required', false);
            jQuery('#easypack_dispatch_point_street').attr('required', false);
            jQuery('#easypack_dispatch_point_building_no').attr('required', false);
            jQuery('#easypack_dispatch_point_flat_no').attr('required', false);
            jQuery('#easypack_dispatch_point_post_code').attr('required', false);
            jQuery('#easypack_dispatch_point_city').attr('required', false);
            jQuery('#easypack_default_machine_id').attr('required', false);
        }
    }

    var api_country = jQuery('#easypack_api_country').val();
    jQuery('#easypack_api_country').change(function () {
        if (api_country !== jQuery('#easypack_api_country').val()) {
            if (api_country !== '--' && !confirm( easypack_settings.change_country_alert )) {
                jQuery('#easypack_api_country').val(api_country);
            }
            jQuery('#easypack_api_change').val('1');
            api_country = jQuery('#easypack_api_country').val();
            easypack_country_change();
        }
    });

    jQuery('#easypack_organization_id').change(function () {
        jQuery('#easypack_api_change').val('1');
    });
    jQuery('#easypack_api_environment').change(function () {
        jQuery('#easypack_api_change').val('1');
    });
    jQuery('#easypack_token').change(function () {
        jQuery('#easypack_api_change').val('1');
    });

    jQuery('#easypack_token').keyup(function () {
        if (easypack_token !== jQuery('#easypack_token').val()) {
            jQuery('#easypack_api_change').val('1');
        }
    });
    var easypack_token = jQuery('#easypack_token').val();

    easypack_country_change();
    jQuery(document).ready(function () {
        easypack_country_change();
    });

});