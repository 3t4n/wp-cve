(function( $ ) {

    // Listen for our change to our trigger type selectors
    $('.requirements-list').on( 'change', '.select-trigger-type', function() {

        // Grab our selected trigger type
        var trigger_type = $(this).val();
        var id_field = $(this).siblings('.input-button-id');
        var class_field = $(this).siblings('.input-button-class');

        id_field.hide();
        class_field.hide();

        if( trigger_type === 'gamipress_specific_id_button_click'
            || trigger_type === 'gamipress_user_specific_id_button_click' ) {
            id_field.show();
        }

        if( trigger_type === 'gamipress_specific_class_button_click'
            || trigger_type === 'gamipress_user_specific_class_button_click' ) {
            class_field.show();
        }

        gamipress_button_update_shortcode_preview( $(this).closest('.requirement-row') );

    });

    // Loop requirement list items to show/hide inputs on initial load
    $('.requirements-list li').each(function() {

        // Grab our selected trigger type
        var trigger_type = $(this).find('.select-trigger-type').val();
        var id_field = $(this).find('.input-button-id');
        var class_field = $(this).find('.input-button-class');

        id_field.hide();
        class_field.hide();

        if( trigger_type === 'gamipress_specific_id_button_click'
            || trigger_type === 'gamipress_user_specific_id_button_click' ) {
            id_field.show();
        }

        if( trigger_type === 'gamipress_specific_class_button_click'
            || trigger_type === 'gamipress_user_specific_class_button_click' ) {
            class_field.show();
        }

        gamipress_button_update_shortcode_preview( $(this) );

    });

    $('.requirements-list').on( 'update_requirement_data', '.requirement-row', function(e, requirement_details, requirement) {

        if( requirement_details.trigger_type === 'gamipress_specific_id_button_click'
            || requirement_details.trigger_type === 'gamipress_user_specific_id_button_click' ) {
            requirement_details.button_id = requirement.find( '.input-button-id' ).val();
        }

        if( requirement_details.trigger_type === 'gamipress_specific_class_button_click'
            || requirement_details.trigger_type === 'gamipress_user_specific_class_button_click' ) {
            requirement_details.button_class = requirement.find( '.input-button-class' ).val();
        }

    });

    // Update shortcode preview on change involved inputs
    $('.requirements-list').on( 'keyup change', '.input-button-id, .input-button-class', function(e, requirement_details, requirement) {
        gamipress_button_update_shortcode_preview( $(this).closest('.requirement-row') );
    });

    // Update the shortcode preview
    function gamipress_button_update_shortcode_preview( row ) {

        var triggers = [
            'gamipress_button_click',
            'gamipress_specific_id_button_click',
            'gamipress_specific_class_button_click',
            'gamipress_user_button_click',
            'gamipress_user_specific_id_button_click',
            'gamipress_user_specific_class_button_click',
        ];

        var trigger_type = row.find('.select-trigger-type').val();
        var id_field = row.find('.input-button-id');
        var class_field = row.find('.input-button-class');
        var preview = row.find('.gamipress-button-shortcode-preview');

        // Hide the shortcode preview
        preview.hide();

        if( triggers.indexOf( trigger_type ) !== -1 ) {

            var shortcode = '[gamipress_button label="Click here!"';

            // Setup specific triggers attributes
            if( trigger_type === 'gamipress_specific_id_button_click'
                || trigger_type === 'gamipress_user_specific_id_button_click' ) {
                shortcode += ' id="' + id_field.val() + '"';
            }

            if( trigger_type === 'gamipress_specific_class_button_click'
                || trigger_type === 'gamipress_user_specific_class_button_click' ) {
                shortcode += ' class="' + class_field.val() + '"';
            }

            shortcode += ']';

            preview.find('.gamipress-button-shortcode').val(shortcode);

            // Show the shortcode preview
            preview.show();
        }

    }

})( jQuery );