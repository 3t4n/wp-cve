( function( $ ) {

    'use strict';

    $( document ).ready( function() {

        $( document ).on( 'click', '#youzify-service-button' , function( e ) {

            var current_wg_nbr = $( '.youzify-wg-item[data-wg=services]' ).length + 1;

            if ( current_wg_nbr > youzify_max_services_nbr )  {
                // Show Error Message
                $.youzify_DialogMsg( 'error', Youzify_Services.items_nbr + youzify_max_services_nbr );
                return false;
            }

            e.preventDefault();

            var service_icon = $.ukai_form_input( {
                    option_item     : 'icon',
                    cell            : youzify_service_nextCell,
                    options_name    : 'youzify_services',
                    input_desc      : Youzify_Services.serv_desc_icon,
                    label_title     : Youzify_Services.service_icon,
                    input_type      : 'icon',
                    inner_option    : true
                }),

                service_title = $.ukai_form_input( {
                    option_item     : 'title',
                    cell            : youzify_service_nextCell,
                    input_desc      : Youzify_Services.serv_desc_title,
                    options_name    : 'youzify_services',
                    label_title     : Youzify_Services.service_title,
                    input_type      : 'text',
                    inner_option    : true
                }),

                service_desc = $.ukai_form_input( {
                    option_item     : 'description',
                    cell            : youzify_service_nextCell,
                    options_name    : 'youzify_services',
                    input_desc      : Youzify_Services.serv_desc_desc,
                    label_title     : Youzify_Services.service_desc,
                    input_type      : 'textarea',
                    inner_option    : true
                });

            // Add Service
            $( '<li class="youzify-wg-item" data-wg="services"><div class="youzify-wg-container">' +
                service_icon + service_title + service_desc +
                '</div><a class="youzify-delete-item"></a></li>'
            ).hide().prependTo( '.youzify-wg-services-options' ).fadeIn( 400 );

            // increase ID number.
            youzify_service_nextCell++;

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

            // Check Services List.
            if ( $( '.youzify-wg-services-options li' )[0] ) {
                $( '.youzify-no-services' ).remove();
            } else if ( ! $( '.youzify-no-services' )[0] ) {
                $( '.youzify-wg-services-options' ).append(
                    '<p class="youzify-no-content youzify-no-services">' + Youzify_Services.no_items + '</p>'
                );
            }

        }

        // Check Account Items List.
        $.youzify_CheckList();

    });

})( jQuery );