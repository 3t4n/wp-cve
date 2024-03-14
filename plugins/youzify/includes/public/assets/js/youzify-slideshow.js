( function( $ ) {

    'use strict';

    $( document ).ready( function () {

        $( document ).on( 'click', '#youzify-slideshow-button' , function( e ) {

            var current_wg_nbr = $( '.youzify-wg-item[data-wg=slideshow]' ).length + 1;

            if ( current_wg_nbr > youzify_max_slideshow_img  )  {
				// Show Error Message
                $.youzify_DialogMsg( 'error', Youzify_Slideshow.items_nbr + youzify_max_slideshow_img );
                return false;
            }

            e.preventDefault();

            var slideshow_button = $.ukai_form_input( {
                    label_title : Youzify_Slideshow.upload_photo,
                    options_name : 'youzify_slideshow',
                    input_id    : 'youzify_slideshow_' + youzify_ss_nextCell,
                    cell         : youzify_ss_nextCell,
                    class        : 'youzify-photo-url',
                    input_type  : 'image',
                    option_item  : 'original',
                    option_only : true
                });

            // Add Slideshow Item.
            $(  '<li class="youzify-wg-item" data-wg="slideshow">' +
                    '<div class="youzify-wg-container">' +
                        '<div class="youzify-cphoto-content">' + slideshow_button +
                    '</div></div><a class="youzify-delete-item"></a>' +
                '</li>'
            ).hide().prependTo( '.youzify-wg-slideshow-options' ).fadeIn( 400 );

            // Increase ID Number.
            youzify_ss_nextCell++;

            // Check Account Items List
            $.youzify_CheckList();

        });

        /**
         * Remove Items.
         */
        $( document ).on( 'click', '.youzify-delete-item', function( e ) {

            $( this ).parent().fadeOut( function() {

                // Remove Item
                $( this ).remove();

                // Check Widget Items
                $.youzify_CheckList();

            });

        });

        /**
         * Check Account Items
         */
        $.youzify_CheckList = function() {

            // Check Slideshow List.
            if ( $( '.youzify-wg-slideshow-options li' )[0] ) {
                $( '.youzify-no-slideshow' ).remove();
            } else if ( ! $( '.youzify-no-slideshow' )[0] ) {
                $( '.youzify-wg-slideshow-options' ).append(
                    '<p class="youzify-no-content youzify-no-slideshow">' + Youzify_Slideshow.no_items + '</p>'
                );
            }

        }

        // Check Account Items List.
        $.youzify_CheckList();

    });

})( jQuery );