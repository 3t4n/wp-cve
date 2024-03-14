( function( $ ) {

    'use strict';

    $( document ).ready( function () {

        $( document ).on( 'click', '#youzify-portfolio-button' , function( e ) {

            var current_wg_nbr = $( '.youzify-wg-item[data-wg=portfolio]' ).length + 1;

            if ( current_wg_nbr > youzify_max_portfolio_img  )  {
                // Show Error Message
                $.youzify_DialogMsg( 'error', Youzify_Portfolio.items_nbr + youzify_max_portfolio_img );
                return false;
            }

            e.preventDefault();

            var portfolio_button = $.ukai_form_input( {
                    class       : 'youzify-photo-url',
                    cell        : youzify_pf_nextCell,
                    label_title : Youzify_Portfolio.upload_photo,
                    options_name: 'youzify_portfolio',
                    input_id    : 'youzify_portfolio_' + youzify_pf_nextCell,
                    input_type  : 'image',
                    option_item : 'url',
                    option_only : true
                }),

                portfolio_link = $.ukai_form_input( {
                    option_item     : 'link',
                    options_name    : 'youzify_portfolio',
                    cell            : youzify_pf_nextCell,
                    label_title     : Youzify_Portfolio.photo_link,
                    input_type      : 'text',
                    show_label      : false,
                    show_ph         : true
                }),

                portfolio_title = $.ukai_form_input( {
                    options_name    : 'youzify_portfolio',
                    option_item     : 'title',
                    label_title     : Youzify_Portfolio.photo_title,
                    cell            : youzify_pf_nextCell,
                    input_type      : 'text',
                    show_label      : false,
                    show_ph         : true
                });

            // Add Portflio Item.
            $( '<li class="youzify-wg-item" data-wg="portfolio">' +
                    '<div class="youzify-wg-container">' +
                        '<div class="youzify-cphoto-content">' +
                        portfolio_button + portfolio_title + portfolio_link +
                    '</div></div><a class="youzify-delete-item"></a>' +
                '</li>'
            ).hide().prependTo( '.youzify-wg-portfolio-options' ).fadeIn( 400 );

            // Increase ID Number.
            youzify_pf_nextCell++;

            // Check Account Items List
            $.youzify_CheckList();

        });

        /**
         * Check Account Items
         */
        $.youzify_CheckList = function() {

            // Check Portfolio List.
            if ( $( '.youzify-wg-portfolio-options li' )[0] ) {
                $( '.youzify-no-portfolio' ).remove();
            } else if ( ! $( '.youzify-no-portfolio' )[0] ) {
                $( '.youzify-wg-portfolio-options' ).append(
                    '<p class="youzify-no-content youzify-no-portfolio">' + Youzify_Portfolio.no_items + '</p>'
                );
            }

        }

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

        // Check Account Items List
        $.youzify_CheckList();

    });

})( jQuery );