( function( $ ) {

    "use strict";

    /**
     * Adds <style> to <head>, replace if such exist
     *
     * @param $style_id
     *  style id
     * @param $new_style
     *  <style id="...">...</style>
     */
    function mphb_divi_add_new_style($style_id, $new_style){

        var $style_old = $('#'+$style_id);

        if ( $style_old.length ) {
            $style_old.replaceWith( $new_style );
        } else {
            $( 'head' ).append( $new_style );
        }
    }

    /**
     * Forms font style css
     *
     * @param value
     *  font styles
     * @param important_tag
     *  important tag
     * @returns {string}
     *  font style css
     */
    function mphb_divi_font_styles( value, important_tag ) {
		var font_styles = value.split( '|' ),
			style = '';

		if ( $.inArray( 'bold', font_styles ) >= 0 ) {
			style += "font-weight: bold " + important_tag + ";";
		} else {
			style += "font-weight: inherit " + important_tag + ";";
		}

		if ( $.inArray( 'italic', font_styles ) >= 0 ) {
			style += "font-style: italic " + important_tag + ";";
		} else {
			style += "font-style: inherit " + important_tag + ";";
		}

		if ( $.inArray( 'underline', font_styles ) >= 0 ) {
			style += "text-decoration: underline " + important_tag + ";";
		} else {
			style += "text-decoration: inherit " + important_tag + ";";
		}

		if ( $.inArray( 'uppercase', font_styles ) >= 0 ) {
			style += "text-transform: uppercase " + important_tag + ";";
		} else {
			style += "text-transform: inherit " + important_tag + ";";
		}

		return style;
	}

    /**
     * button font size css
     */
    wp.customize( 'et_divi[all_buttons_font_size]', function( value ) {
        value.bind( function( to ) {

            var	$button_style = '<style id="mphb-divi-button-font-size">' +
                '.mphb_sc_rooms-wrapper .button,'+
                '.mphb_sc_search-wrapper .button,'+
                '.mphb_sc_search_results-wrapper .button,'+
                '.mphb_sc_checkout-wrapper .button,'+
                '.mphb_sc_room-wrapper .button,'+
                '.mphb_sc_booking_form-wrapper .button,'+
                '.widget_mphb_rooms_widget .button,'+
                '.widget_mphb_search_availability_widget form .button,'+
                '.mphb-booking-form .button{'+
                'font-size:' + to + 'px !important;'+
                '}'+
                '</style>';

            mphb_divi_add_new_style('mphb-divi-button-font-size', $button_style);

        } );
    } );

    /**
     * button text color css
     */
    wp.customize( 'et_divi[all_buttons_text_color]', function( value ) {
        value.bind( function( to ) {

            var	$button_style = '<style id="mphb-divi-button-text-color">' +
                '.mphb_sc_rooms-wrapper .button,'+
                '.mphb_sc_search-wrapper .button,'+
                '.mphb_sc_search_results-wrapper .button,'+
                '.mphb_sc_checkout-wrapper .button,'+
                '.mphb_sc_room-wrapper .button,'+
                '.mphb_sc_booking_form-wrapper .button,'+
                '.widget_mphb_rooms_widget .button,'+
                '.widget_mphb_search_availability_widget form .button,'+
                '.mphb-booking-form .button{'+
                'color:' + to + ' !important;'+
                '}'+
                '</style>';

            mphb_divi_add_new_style('mphb-divi-button-text-color', $button_style);

        } );
    } );

    /**
     * button bg color css
     */
    wp.customize( 'et_divi[all_buttons_bg_color]', function( value ) {
        value.bind( function( to ) {

            var	$button_style = '<style id="mphb-divi-button-bg-color">' +
                '.mphb_sc_rooms-wrapper .button,'+
                '.mphb_sc_search-wrapper .button,'+
                '.mphb_sc_search_results-wrapper .button,'+
                '.mphb_sc_checkout-wrapper .button,'+
                '.mphb_sc_room-wrapper .button,'+
                '.mphb_sc_booking_form-wrapper .button,'+
                '.widget_mphb_rooms_widget .button,'+
                '.widget_mphb_search_availability_widget form .button,'+
                '.mphb-booking-form .button{'+
                'background:' + to + ' !important;'+
                '}'+
                '</style>';

            mphb_divi_add_new_style('mphb-divi-button-bg-color', $button_style);
            
        } );

    } );

    /**
     * button border width css
     */
    wp.customize( 'et_divi[all_buttons_border_width]', function( value ) {
        value.bind( function( to ) {

            var	$button_style = '<style id="mphb-divi-button-border-width">' +
                '.mphb_sc_rooms-wrapper .button,'+
                '.mphb_sc_search-wrapper .button,'+
                '.mphb_sc_search_results-wrapper .button,'+
                '.mphb_sc_checkout-wrapper .button,'+
                '.mphb_sc_room-wrapper .button,'+
                '.mphb_sc_booking_form-wrapper .button,'+
                '.widget_mphb_rooms_widget .button,'+
                '.widget_mphb_search_availability_widget form .button,'+
                '.mphb-booking-form .button{'+
                'border-width:' + to + 'px !important;'+
                '}'+
                '</style>';

            mphb_divi_add_new_style('mphb-divi-button-border-width', $button_style);

        } );
    } );

    /**
     * button border color css
     */
    wp.customize( 'et_divi[all_buttons_border_color]', function( value ) {
        value.bind( function( to ) {

            var	$button_style = '<style id="mphb-divi-button-border-color">' +
                '.mphb_sc_rooms-wrapper .button,'+
                '.mphb_sc_search-wrapper .button,'+
                '.mphb_sc_search_results-wrapper .button,'+
                '.mphb_sc_checkout-wrapper .button,'+
                '.mphb_sc_room-wrapper .button,'+
                '.mphb_sc_booking_form-wrapper .button,'+
                '.widget_mphb_rooms_widget .button,'+
                '.widget_mphb_search_availability_widget form .button,'+
                '.mphb-booking-form .button{'+
                'border-color:' + to + ' !important;'+
                '}'+
                '</style>';

            mphb_divi_add_new_style('mphb-divi-button-border-color', $button_style);

        } );
    } );

    /**
     * button border radius css
     */
    wp.customize( 'et_divi[all_buttons_border_radius]', function( value ) {
        value.bind( function( to ) {

            var	$button_style = '<style id="mphb-divi-button-border-radius">' +
                '.mphb_sc_rooms-wrapper .button,'+
                '.mphb_sc_search-wrapper .button,'+
                '.mphb_sc_search_results-wrapper .button,'+
                '.mphb_sc_checkout-wrapper .button,'+
                '.mphb_sc_room-wrapper .button,'+
                '.mphb_sc_booking_form-wrapper .button,'+
                '.widget_mphb_rooms_widget .button,'+
                '.widget_mphb_search_availability_widget form .button,'+
                '.mphb-booking-form .button{'+
                'border-radius:' + to + 'px !important;'+
                '}'+
                '</style>';

            mphb_divi_add_new_style('mphb-divi-button-border-radius', $button_style);

        } );
    } );

    /**
     * button font style css
     */
    wp.customize( 'et_divi[all_buttons_font_style]', function( value ) {
        value.bind( function( to ) {
            var $font_style = mphb_divi_font_styles(to, ''),
            $button_style = '<style id="mphb-divi-button-font-style">' +
                '.mphb_sc_rooms-wrapper .button,'+
                '.mphb_sc_search-wrapper .button,'+
                '.mphb_sc_search_results-wrapper .button,'+
                '.mphb_sc_checkout-wrapper .button,'+
                '.mphb_sc_room-wrapper .button,'+
                '.mphb_sc_booking_form-wrapper .button,'+
                '.widget_mphb_rooms_widget .button,'+
                '.widget_mphb_search_availability_widget form .button,'+
                '.mphb-booking-form .button{'+$font_style+'}'+
                '</style>';

            mphb_divi_add_new_style('mphb-divi-button-font-style', $button_style);

        } );
    } );

    /**
     * button letter spacing css
     */
    wp.customize( 'et_divi[all_buttons_spacing]', function( value ) {
        value.bind( function( to ) {
            
            var $button_style = '<style id="mphb-divi-button-spacing">' +
                '.mphb_sc_rooms-wrapper .button,'+
                '.mphb_sc_search-wrapper .button,'+
                '.mphb_sc_search_results-wrapper .button,'+
                '.mphb_sc_checkout-wrapper .button,'+
                '.mphb_sc_room-wrapper .button,'+
                '.mphb_sc_booking_form-wrapper .button,'+
                '.widget_mphb_rooms_widget .button,'+
                '.widget_mphb_search_availability_widget form .button,'+
                '.mphb-booking-form .button{'+
                'letter-spacing:' + to +'px;  !important}'+
                '</style>';

            mphb_divi_add_new_style('mphb-divi-button-spacing', $button_style);

        } );
    } );

    /**
     * button font css
     */
    wp.customize( 'et_divi[all_buttons_font]', function( value ) {
        value.bind( function( to ) {
            
            var $button_style = '<style id="mphb-divi-button-font">' +
                '.mphb_sc_rooms-wrapper .button,'+
                '.mphb_sc_search-wrapper .button,'+
                '.mphb_sc_search_results-wrapper .button,'+
                '.mphb_sc_checkout-wrapper .button,'+
                '.mphb_sc_room-wrapper .button,'+
                '.mphb_sc_booking_form-wrapper .button,'+
                '.widget_mphb_rooms_widget .button,'+
                '.widget_mphb_search_availability_widget form .button,'+
                '.mphb-booking-form .button{'+
                'font-family:' + to +', sans-serif  !important; }'+
                '</style>';

            mphb_divi_add_new_style('mphb-divi-button-font', $button_style);

        } );
    } );

    /**
     * button hover color css
     */
    wp.customize( 'et_divi[all_buttons_text_color_hover]', function( value ) {
        value.bind( function( to ) {
            
            var $button_style = '<style id="mphb-divi-button-text-color-hover">' +
                '.mphb_sc_rooms-wrapper .button:hover,'+
                '.mphb_sc_search-wrapper .button:hover,'+
                '.mphb_sc_search_results-wrapper .button:hover,'+
                '.mphb_sc_checkout-wrapper .button:hover,'+
                '.mphb_sc_room-wrapper .button:hover,'+
                '.mphb_sc_booking_form-wrapper .button:hover,'+
                '.widget_mphb_rooms_widget .button:hover,'+
                '.widget_mphb_search_availability_widget form .button:hover,'+
                '.mphb-booking-form .button:hover{'+
                'color:' + to +' !important; }'+
                '</style>';

            mphb_divi_add_new_style('mphb-divi-button-text-color-hover', $button_style);

        } );
    } );

    /**
     * button hover bg color css
     */
    wp.customize( 'et_divi[all_buttons_bg_color_hover]', function( value ) {
        value.bind( function( to ) {
            
            var $button_style = '<style id="mphb-divi-button-bg-color-hover">' +
                '.mphb_sc_rooms-wrapper .button:hover,'+
                '.mphb_sc_search-wrapper .button:hover,'+
                '.mphb_sc_search_results-wrapper .button:hover,'+
                '.mphb_sc_checkout-wrapper .button:hover,'+
                '.mphb_sc_room-wrapper .button:hover,'+
                '.mphb_sc_booking_form-wrapper .button:hover,'+
                '.widget_mphb_rooms_widget .button:hover,'+
                '.widget_mphb_search_availability_widget form .button:hover,'+
                '.mphb-booking-form .button:hover{'+
                'background:' + to +' !important; }'+
                '</style>';

            mphb_divi_add_new_style('mphb-divi-button-bg-color-hover', $button_style);

        } );
    } );

    /**
     * button hover border color css
     */
    wp.customize( 'et_divi[all_buttons_border_color_hover]', function( value ) {
        value.bind( function( to ) {
            
            var $button_style = '<style id="mphb-divi-button-border-color-hover">' +
                '.mphb_sc_rooms-wrapper .button:hover,'+
                '.mphb_sc_search-wrapper .button:hover,'+
                '.mphb_sc_search_results-wrapper .button:hover,'+
                '.mphb_sc_checkout-wrapper .button:hover,'+
                '.mphb_sc_room-wrapper .button:hover,'+
                '.mphb_sc_booking_form-wrapper .button:hover,'+
                '.widget_mphb_rooms_widget .button:hover,'+
                '.widget_mphb_search_availability_widget form .button:hover,'+
                '.mphb-booking-form .button:hover{'+
                'border-color:' + to +' !important; }'+
                '</style>';

            mphb_divi_add_new_style('mphb-divi-button-border-color-hover', $button_style);

        } );
    } );

    /**
     * button hover border radius css
     */
    wp.customize( 'et_divi[all_buttons_border_radius_hover]', function( value ) {
        value.bind( function( to ) {
            
            var $button_style = '<style id="mphb-divi-button-border-radius-hover">' +
                '.mphb_sc_rooms-wrapper .button:hover,'+
                '.mphb_sc_search-wrapper .button:hover,'+
                '.mphb_sc_search_results-wrapper .button:hover,'+
                '.mphb_sc_checkout-wrapper .button:hover,'+
                '.mphb_sc_room-wrapper .button:hover,'+
                '.mphb_sc_booking_form-wrapper .button:hover,'+
                '.widget_mphb_rooms_widget .button:hover,'+
                '.widget_mphb_search_availability_widget form .button:hover,'+
                '.mphb-booking-form .button:hover{'+
                'border-radius:' + to +'px !important; }'+
                '</style>';

            mphb_divi_add_new_style('mphb-divi-button-border-radius-hover', $button_style);

        } );
    } );

    /**
     * button hover letter spacing css
     */
    wp.customize( 'et_divi[all_buttons_spacing_hover]', function( value ) {
        value.bind( function( to ) {
            
            var $button_style = '<style id="mphb-divi-button-spacing-hover">' +
                '.mphb_sc_rooms-wrapper .button:hover,'+
                '.mphb_sc_search-wrapper .button:hover,'+
                '.mphb_sc_search_results-wrapper .button:hover,'+
                '.mphb_sc_checkout-wrapper .button:hover,'+
                '.mphb_sc_room-wrapper .button:hover,'+
                '.mphb_sc_booking_form-wrapper .button:hover,'+
                '.widget_mphb_rooms_widget .button:hover,'+
                '.widget_mphb_search_availability_widget form .button:hover,'+
                '.mphb-booking-form .button:hover{'+
                'letter-spacing: '+ to +'px !important; }'+
                '</style>';

            mphb_divi_add_new_style('mphb-divi-button-spacing-hover', $button_style);

        } );
    } );
        
    
})(jQuery);