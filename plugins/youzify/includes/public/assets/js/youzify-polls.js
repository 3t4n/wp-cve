(function ($) {

    'use strict';

    $( document ).ready( function () {

        var yzp_index = 2;

        /**
         * Get More Option.
         */
        function yzfes_moreOption( index ) {

            // Get New Option.
            var fieldHTML = '<div class="youzify-wall-cf-item youzify-wall-cf-dragable-item youzify-allow-image-upload"><i class="fas fa-expand-arrows-alt youzify-wall-form-drag-item"></i><div class="youzify-attachments youzify-poll-attachment youzify-cf-attachment" data-name="poll_options[' + index + '][attachment]"><i class="fas fa-upload youzify-wall-item-upload youzify-current-bg-color"></i><input hidden="true" class="youzify-upload-attachments" type="file" name="poll_options[' + index + '][attachment]"><div class="youzify-form-attachments"></div></div><input type="text" class="youzify-wall-cf-input" name="poll_options[' + index + '][option]" placeholder="' + Youzify.poll_option.replace( '%d', index ) + '" /><i class="fas fa-trash-alt youzify-wall-form-remove-item"></i></div>';
           
            return fieldHTML;

        }

        /**
         *  Add Option.
         */
        $( document ).on( 'click', '.youzify-add-new-standard-poll-option', function() {

            // Get Vars
            var parent = $( this ).closest( '.youzify-wall-custom-form' ),
                max_options = $( this ).attr( 'data-options-limit' );

            //Check maximum number of input fields
            if ( parent.find( '.youzify-allow-image-upload' ).length < max_options ) {

                yzp_index++;

                var holder = parent.find( '.youzify-option-holder' );

                //Increment field counter
                holder.append( yzfes_moreOption( yzp_index ) );

                // Reset Placeholder
                youzify_reset_placeholders( holder );

            } else {

                // Show Max Error
                $.youzify_DialogMsg( 'error', Youzify_Wall.poll_max_options.replace( '%d', max_options ) );
            }

        });

        /**
         *  Delete Option.
         */
        $( '.youzify-option-holder' ).on( 'click' , '.youzify-wall-form-remove-item', function ( e ) {

            e.preventDefault();

            // Get Parent
            var parent = $( this ).closest( '.youzify-option-holder' );

            // Remove Option.
            $( this ).closest( '.youzify-wall-cf-item' ).remove();

            // Triger KeyUp.
            parent.find( 'input.youzify-wall-cf-input' ).trigger( 'keyup' );

            // Reset Place Holders
            youzify_reset_placeholders( parent );

        });

        /**
         *  Make CheckBox Like Radio.
         */
        $( document ).on( 'click' , '.radio', function () {
            $( 'input[type="checkbox"]' ).not( this ).prop( 'checked' , false );
        });

        /**
         * Rename Polls Placeholders
         **/
         function youzify_reset_placeholders( form ) {
            form.find( '.youzify-wall-cf-item .youzify-wall-cf-input').each( function(i, v ) {
                $( this ).attr( 'placeholder', Youzify.poll_option.replace( '%d', i + 1 ) );
            });
        }


        /**
         * Sort Options.
         */
        $( '.youzify-option-holder' ).sortable({
            handle: '.youzify-wall-form-drag-item',
            update: function( ) {
                youzify_reset_placeholders( $( this ) );
            }
        });

        /**
         * Duplicated Input Value.
         */
        $( document ).on( 'keyup' , '.youzify-wall-cf-input', function() {

            // Init Var.
            var parent = $( this ).closest( '.youzify-option-holder' ),
                value = $( this ).val(), count = 0, options = [],
                counts = [], input,input_value;

            //
            parent.find( '.youzify-wall-cf-input' ).each( function( i, v ) {

                // Set Value
                input_value = $( this ).val();

                // Init Attr.
                $( this ).attr( 'value', input_value );

                // Diffrent Of Empty
                if ( $( this ).val() ) {

                    // Get Element With The Same Value.
                    input = parent.find( '.youzify-wall-cf-input[value="' + input_value + '"]' );

                    if ( counts[ input_value ] ) {

                        // Count Icrement.
                        counts[ input_value ] += 1;

                        // Add Class.
                        input.addClass( 'yzfes-error-duplicate' );

                    } else {

                        // Set Count 1.
                        counts[ input_value ] = 1;

                        // Remove Class.
                        input.removeClass( 'yzfes-error yzfes-error-duplicate' );

                    }

                }

            });

            // Check More Then one input With The Same Value.
            if ( counts[ value ] > 1 ) {

                // Add Class.
                $( this ).addClass( 'yzfes-error' );

            } else {

                // Remove Class 
                $( this ).removeClass( 'yzfes-error' );

                // Add Error Class To The Last Duplicated Input Value.
                parent.find( '.error-duplicate:last' ).addClass( 'error' ).removeClass( 'error-duplicate' );        

            }

        });

    });

})( jQuery );