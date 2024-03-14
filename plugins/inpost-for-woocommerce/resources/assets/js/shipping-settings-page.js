jQuery(document).ready(function () {	

    function display_rates() {
        if (jQuery('.easypack_flat_rate').prop('checked')) {
            jQuery('#woocommerce_easypack_parcel_machines_weekend_3').css('display', 'none');
            jQuery('.easypack_cost_per_order').closest('tr').css('display', 'table-row');
            jQuery('.easypack_based_on').closest('tr').css('display', 'none');
            jQuery('.easypack_rates').closest('tr').css('display', 'none');
            jQuery('#woocommerce_easypack_parcel_machines_1').css('display', 'none');
            jQuery('#woocommerce_easypack_parcel_machines_cod_1').css('display', 'none');
            // gabaryt rows
            jQuery('.easypack_gabaryt_a').closest('tr').css('display', 'none');
            jQuery('.easypack_gabaryt_b').closest('tr').css('display', 'none');
            jQuery('.easypack_gabaryt_c').closest('tr').css('display', 'none');

        } else {
            jQuery('#woocommerce_easypack_parcel_machines_weekend_3').css('display', 'block');
            jQuery('.easypack_cost_per_order').closest('tr').css('display', 'none');
            jQuery('.easypack_based_on').closest('tr').css('display', 'table-row');
            jQuery('.easypack_rates').closest('tr').css('display', 'table-row');
            jQuery('#woocommerce_easypack_parcel_machines_1').css('display', 'block');
            jQuery('#woocommerce_easypack_parcel_machines_cod_1').css('display', 'block');

            let select_position = jQuery("[id$='_based_on']").val();
            if(select_position === 'size') {

                jQuery('#woocommerce_easypack_parcel_machines_rates').closest('tr').hide();
                jQuery('#woocommerce_easypack_parcel_machines_rates').hide(); // on parcel lockers settings page
                jQuery('#woocommerce_easypack_shipping_courier_c2c_rates').closest('tr').hide();
                jQuery('#woocommerce_easypack_shipping_courier_c2c_rates').hide(); // on c2c courier settings page
                jQuery('#woocommerce_easypack_shipping_courier_c2c_cod_rates').closest('tr').hide();
                jQuery('#woocommerce_easypack_shipping_courier_c2c_cod_rates').hide();
                jQuery('.easypack_gabaryt_a').closest('tr').show();
                jQuery('.easypack_gabaryt_b').closest('tr').show();
                jQuery('.easypack_gabaryt_c').closest('tr').show();
            }
        }
    }

    let easypack_flat_rate = jQuery('.easypack_flat_rate');
    if( typeof easypack_flat_rate != 'undefined' && easypack_flat_rate !== null ) {
        jQuery(easypack_flat_rate).change(function () {
            display_rates();
        });
        display_rates();
    }
});