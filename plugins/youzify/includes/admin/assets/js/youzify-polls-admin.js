(function ($) {

    'use strict';

    $( document ).ready( function () {


        $( document ).on( 'click', 'input[name="youzify_options[yzap_poll_options_image_enable]"]', function () {

    	   if ( $( this ).prop( 'checked' ) == true ) {

              // $( 'input[name="youzify_options[yzap_poll_options_image]"]' ).prop( 'checked', true );
               $( 'input[name="youzify_options[yzap_poll_options_image]"]' ).prop( 'disabled', false );

               $( 'input[name="youzify_options[yzap_poll_options_image]"]' ).closest( '.youzify-checkbox-field' ).show();

           } else {

                $( 'input[name="youzify_options[yzap_poll_options_image]"]' ).prop( 'checked', false );
                $( 'input[name="youzify_options[yzap_poll_options_image]"]' ).prop( 'disabled', true );

                $( 'input[name="youzify_options[yzap_poll_options_image]"]' ).closest( '.youzify-checkbox-field' ).hide()

           }

        });

 
    });

})( jQuery );