function wpzf_submit(token) {
    // Check if required inputs are filled
    var allInputsFilled = true;
  
    jQuery('form.wpzoom-forms_form [required]').each(function() {
        var input = jQuery(this);
        if (input.is(':checkbox')) {
            // For checkboxes
            if (!input.is(':checked')) {
                allInputsFilled = false;
                input.focus();
                return false; // exit the loop if any required checkbox is not checked
            }
        } else {
            // For non-checkbox inputs (text, password, etc.)
            if ( ! input.val() ) {
                allInputsFilled = false;
                input.focus();
                return false; // exit the loop if any required input is not filled
            }
        }
    } );
  
    if ( allInputsFilled ) {
        // All required inputs are filled, trigger form submission
        jQuery('form.wpzoom-forms_form').trigger('submit');
    }
}