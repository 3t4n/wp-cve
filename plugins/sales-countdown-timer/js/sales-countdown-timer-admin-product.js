jQuery(document).ready(function () {
    'use strict';
	jQuery('#_woo_ctr_progress_bar_order_status').select2({placeholder: "All"});
    jQuery( '.sale_price_dates_field' ).each( function() {
        var $these_sale_dates = jQuery( this );
        var sale_schedule_set = false;
        var $wrap = $these_sale_dates.parent().parent();

        $these_sale_dates.find( 'input[type="text"]' ).each( function() {
            if ( '' !== jQuery( this ).val() ) {
                sale_schedule_set = true;
            }
        });
        if ( sale_schedule_set ) {
            $wrap.find( '.sale_schedule' ).hide();
            $wrap.find( '.sale_price_dates_field' ).show();
            $wrap.find( '.woo-sctr-countdown-timer-admin-product' ).show();
        } else {
            $wrap.find( '.sale_schedule' ).show();
            $wrap.find( '.sale_price_dates_field' ).hide();
            $wrap.find( '.woo-sctr-countdown-timer-admin-product' ).hide();
        }
    });
    jQuery('#woocommerce-product-data').on('woocommerce_variations_loaded', function(event) {
        jQuery( '.sale_price_dates_field' ).each( function() {
            jQuery( this ).find( 'input[type="text"]' ).datepicker({
                defaultDate: '',
                dateFormat: 'yy-mm-dd',
                numberOfMonths: 1,
                showButtonPanel: true,
                onSelect: function() {
                    date_picker_select( jQuery( this ) );
                }
            });
            jQuery( this ).find( 'input[type="text"]' ).each( function() { date_picker_select( jQuery( this ) ); } );
        });
        jQuery( '.sale_price_dates_field' ).each( function() {
            var $these_sale_dates = jQuery( this );
            var sale_schedule_set = false;
            var $wrap = $these_sale_dates.parent().parent();

            $these_sale_dates.find( 'input[type="text"]' ).each( function() {
                if ( '' !== jQuery( this ).val() ) {
                    sale_schedule_set = true;
                }
            });
            if ( sale_schedule_set ) {
                $wrap.find( '.sale_schedule' ).hide();
                $wrap.find( '.sale_price_dates_field' ).show();
                $wrap.find( '.woo-sctr-countdown-timer-admin-product' ).show();
            } else {
                $wrap.find( '.sale_schedule' ).show();
                $wrap.find( '.sale_price_dates_field' ).hide();
                $wrap.find( '.woo-sctr-countdown-timer-admin-product' ).hide();
            }
        });
    });

    jQuery( '.sale_price_dates_field' ).each( function() {
        jQuery( this ).find( 'input[type="text"]' ).datepicker({
            defaultDate: '',
            dateFormat: 'yy-mm-dd',
            numberOfMonths: 1,
            showButtonPanel: true,
            onSelect: function() {
                date_picker_select( jQuery( this ) );
            }
        });
        jQuery( this ).find( 'input[type="text"]' ).each( function() { date_picker_select( jQuery( this ) ); } );
    });
    jQuery( '#woocommerce-product-data' ).on( 'click', '.sale_schedule', function() {
        var $wrap = jQuery( this ).parent().parent().parent();

        jQuery( this ).hide();
        $wrap.find( '.cancel_sale_schedule' ).show();
        $wrap.find( '.sale_price_dates_field' ).show();
        $wrap.find( '.woo-sctr-countdown-timer-admin-product' ).show();

        return false;
    });
    jQuery( '#woocommerce-product-data' ).on( 'click', '.cancel_sale_schedule', function() {
        var $wrap = jQuery( this ).parent().parent().parent();

        jQuery( this ).hide();
        $wrap.find( '.sale_schedule' ).show();
        $wrap.find( '.sale_price_dates_field' ).hide();
        $wrap.find( '.woo-sctr-countdown-timer-admin-product' ).hide();
        $wrap.find( '.sale_price_dates_field' ).find( 'input' ).val('');

        return false;
    });
    // Date picker fields.
    function date_picker_select( datepicker ) {
        var option         = jQuery( datepicker ).next().next().is( '.hasDatepicker' ) ? 'minDate' : 'maxDate',
            otherDateField = 'minDate' === option ? jQuery( datepicker ).next().next() : jQuery( datepicker ).prev().prev(),
            date           = jQuery( datepicker ).datepicker( 'getDate' );
        jQuery( otherDateField ).datepicker( 'option', option, date );
        jQuery( datepicker ).trigger('change');
    }
});