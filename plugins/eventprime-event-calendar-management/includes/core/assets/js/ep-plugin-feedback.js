jQuery( function( $ ) {
    var ep_plugin_deactivate_location = '';
    // show feedback modal on click on the deactivate link
    $( '#the-list' ).find('[data-slug="eventprime-event-calendar-management"] span.deactivate a').click( function(event) {
        $( '#ep_plugin_feedback_form_modal' ).openPopup({
            anim: (!$(this).attr('data-animation') || $(this).data('animation') == null) ? 'ep-modal-' : $(this).data('animation')
        });
        ep_plugin_deactivate_location = $(this).attr('href');
        event.preventDefault();
    });

    $( document ).on( 'change', 'input[name="ep_feedback_key"]', function() {
        var ep_selectedVal = $(this).val();
        var ep_reasonElement = $( '#ep_reason_' + ep_selectedVal );
        $( '.ep-deactivate-feedback-dialog-input-wrapper .epinput' ).hide();
        jQuery(".ep-feedback-form-feature-box").hide();
        //console.log(ep_selectedVal);
        
        
        
        $('.ep-feedback-form-feature-box').each(function () {
            var condition = $(this).data('condition');
            
            //console.log(`${ep_selectedVal} and ${condition}`);
          

            // Check if the condition matches ep_selectedVal
            if (condition == ep_selectedVal) {
           
                // Show the box if the condition is true
                $(this).show();
            } else {
                // Hide the box if the condition is not true
                $(this).hide();
            }
        });
        
  
    });
    
    
    

   
    
    
    // Get all checkboxes with id "ep-inform-email"
    
    var $informEmailCheckboxes = $(".ep-plugin-feedback-email-check input");

    // Get all corresponding divs with class "ep-feedback-user-email"
    
    var $feedbackUserEmailDivs = $(".ep-feedback-user-email");
    
        // Add a change event handler to all checkboxes
        $informEmailCheckboxes.change(function () {
        // Find the index of the checkbox that was changed
        var index = $informEmailCheckboxes.index(this);
        //console.log(this.checked);

        if (this.checked) {
            // Checkbox is checked, so show the corresponding feedbackUserEmailDiv
            $feedbackUserEmailDivs.eq(index).show();
        } else {
            // Checkbox is unchecked, so hide the corresponding feedbackUserEmailDiv
            $feedbackUserEmailDivs.eq(index).hide();
        }
    });
    

    // submit
    $( document ).on( 'click', '#ep_save_plugin_feedback_on_deactivation', function() {
        $( '#ep_plugin_feedback_form_modal .ep-plugin-deactivation-message' ).html('');
        let selectedVal = $( 'input[name="ep_feedback_key"]:checked' ).val();
        var ep_inform_email = ( $('#'+selectedVal+' #ep-inform-email').prop("checked") == true ) ? 1 : 0;
        let ep_user_support_email = $('#'+selectedVal+' #ep_user_support_email' ).val();
        if( !selectedVal ) {
            $( '#ep_plugin_feedback_form_modal .ep-plugin-deactivation-message' ).text( ep_feedback.option_error );
            $( '#ep_plugin_feedback_form_modal .ep-plugin-deactivation-message' ).show();
            return false;
        }

        let ep_feedbackInput = $( "input[name='ep_reason_"+ selectedVal + "']" );
        $( '#ep_plugin_feedback_form_modal .ep-plugin-deactivation-message' ).hide();
        $( '#ep_plugin_feedback_form_modal .ep-plugin-deactivation-loader' ).show();

        let data = { 
            action: 'ep_send_plugin_deactivation_feedback', 
            security: ep_feedback.feedback_nonce, 
            feedback: selectedVal,
            message: ep_feedbackInput.val(),
            ep_inform_email:ep_inform_email,
            ep_user_support_email:ep_user_support_email,
        };
        $.ajax({
            type: 'POST', 
            url : ep_feedback.ajaxurl,
            data: data,
            success: function( data, textStatus, XMLHttpRequest ) {
                location.href = ep_plugin_deactivate_location;
            }
        });
    });

    // skip and deactivation
    $( document ).on( 'click', '#ep_save_plugin_feedback_direct_deactivation', function() {
        $( '#ep_plugin_feedback_form_modal .ep-plugin-deactivation-message' ).html('');
        $( '#ep_plugin_feedback_form_modal .ep-plugin-deactivation-message' ).hide();
        $( '#ep_plugin_feedback_form_modal .ep-plugin-deactivation-loader' ).show();
        setTimeout( function() {
            location.href = ep_plugin_deactivate_location;
        }, 1000 );
    });
});