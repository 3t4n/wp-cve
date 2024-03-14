<script type='text/javascript'>
    jQuery( document ).ready(function() {
        jQuery( '.sg_email_marketing_field_preview_label').html(jQuery('.sg_email_marketing_checkbox_text').val())
        var selected = jQuery('select.sg_email_marketing_groups').attr('data-selected').length > 0 ? jQuery('select.sg_email_marketing_groups').attr('data-selected') : '[]';
        jQuery('select.sg_email_marketing_groups').selectize({
            plugins: ['restore_on_backspace', 'clear_button'],
            delimiter: ' - ',
            persist: false,
            maxItems: null,
            items: JSON.parse( selected ),
        });
        if( jQuery('input.sg_email_marketing_checkbox_toggle').is(':checked') ) {
            jQuery('.sg_email_marketing_checkbox_label').show();
            jQuery('.sg_email_marketing_checkbox_text').show();
            jQuery('.sg-email-marketing-checkbox-enabled').show();
            jQuery('.sg-email-marketing-checkbox-disabled').hide();
        } else {
            jQuery('.sg_email_marketing_checkbox_label').hide();
            jQuery('.sg_email_marketing_checkbox_text').hide();
            jQuery('.sg-email-marketing-checkbox-enabled').hide();
            jQuery('.sg-email-marketing-checkbox-disabled').show();
        }
        jQuery('input.sg_email_marketing_checkbox_toggle').change( function() {
            if( jQuery(this).is(':checked') ) {
                jQuery('.sg_email_marketing_checkbox_label').show();
                jQuery('.sg_email_marketing_checkbox_text').show();
                jQuery('.sg-email-marketing-checkbox-enabled').show();
                jQuery('.sg-email-marketing-checkbox-disabled').hide();
            } else {
                jQuery('.sg_email_marketing_checkbox_label').hide();
                jQuery('.sg_email_marketing_checkbox_text').hide();
                jQuery('.sg-email-marketing-checkbox-enabled').hide();
                jQuery('.sg-email-marketing-checkbox-disabled').show();
            }
        })
        jQuery('input.sg_email_marketing_checkbox_text').on( 'keyup', function() { 
            jQuery( '.sg_email_marketing_field_preview_label').html(jQuery(this).val())
        });
    })
</script>