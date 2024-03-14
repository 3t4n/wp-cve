<?php  
$options                    = get_option('ultimate_subscribe_options');
$opt_in_process             = (isset($options['opt_in_process']))?$options['opt_in_process']:'single';
$form_messages              = isset($options['form_messages'])?$options['form_messages']:array();
$subscribe_success_message  = (isset($form_messages['success']))?$form_messages['success']:__('Thank you! We will be back with the quote.', 'ultimate-subscribe');
$subscribe_double_message   = (isset($form_messages['success_double']))?$form_messages['success_double']:__('Thank you, confirmation link has sent to your Email Address', 'ultimate-subscribe');
$alerady_sucribed_message   = (isset($form_messages['already_subscribed']))?$form_messages['already_subscribed']:__('You have already subscribed.', 'ultimate-subscribe');
$confirm_success_message    = (isset($form_messages['confirm']))?$form_messages['confirm']:__('Thank You, You have been successfully subscribed to our newsletter.', 'ultimate-subscribe');
$alerady_active_message     = (isset($form_messages['already_confirm']))?$form_messages['already_confirm']:__('Your subscription is already active.', 'ultimate-subscribe');
$invalid_details_message    = (isset($form_messages['invalid_details']))?$form_messages['invalid_details']:__('Error: Invalid subscription details.', 'ultimate-subscribe'); 
$unexpected_error_message   = (isset($form_messages['error']))?$form_messages['error']:__('Error: some unexpected error occurred please try again.', 'ultimate-subscribe'); 

?>
<div id="form-options" class="tab-pane active">
    <h3> <?php esc_html_e('Subscriber Form Settings', 'ultimate-subscribe'); ?> </h3>
    <div class="form-fieldset">
        <div class="field-row">
            <div class="field-label"> <?php _e('Subscribe Process', 'ultimate-subscribe'); ?> </div>
            <div class="field-data"> 
                    <input type="radio" value="single" id="process_single" name="ultimate_subscribe_options[opt_in_process]" <?php checked($opt_in_process, 'single', true); ?>/>
                    <label for="process_single"><?php _e('Single Opt In', 'ultimate-subscribe'); ?></label>
                    <input type="radio" value="double" id="process_double" name="ultimate_subscribe_options[opt_in_process]" <?php checked($opt_in_process, 'double', true); ?>/>
                    <label for="process_double"><?php _e('Double Opt In (Send Confirmation Link)', 'ultimate-subscribe'); ?></label>
                    <br>
                    <span class="description"><?php _e('<strong>Double Opt In</strong> means subscribers need to confirm their email address by an activation link sent them on a activation email message.'); ?></span>
                    <br>
                    <span class="description"><?php _e('<strong>Single Opt In</strong> means subscribers do not need to confirm their email address.','ultimate-subscribe'); ?></span>
             </div>
        </div>
        <h4 class="sub-title"> <?php esc_html_e('Subscribe Form Messages', 'ultimate-subscribe'); ?> </h4>
        <div class="field-row">
            <div class="field-label"> <?php _e('Subscribe Success Message', 'ultimate-subscribe'); ?> </div>
            <div class="field-data"> 
                <textarea class="input-field" name="ultimate_subscribe_options[form_messages][success]"><?php echo esc_html($subscribe_success_message); ?></textarea> 
                <br>
                <span class="description"><?php _e('<strong>Single Opt In</strong> This message will be shown after successful subscription','ultimate-subscribe'); ?></span>
            </div>
        </div>
        <div class="field-row">
            <div class="field-label"> <?php _e('Subscribe Success Message', 'ultimate-subscribe'); ?> </div>
            <div class="field-data"> 
                <textarea class="input-field" name="ultimate_subscribe_options[form_messages][success_double]"><?php echo esc_html($subscribe_double_message); ?></textarea> 
                <br>
                <span class="description"><?php _e('<strong>Double Opt In</strong> This message will be shown after confirmation link sent','ultimate-subscribe'); ?></span>
            </div>
        </div>
        <div class="field-row">
            <div class="field-label"> <?php _e('Already Subscribe Message', 'ultimate-subscribe'); ?> </div>
            <div class="field-data"> 
                <textarea class="input-field" name="ultimate_subscribe_options[form_messages][already_subscribed]"><?php echo esc_html($alerady_sucribed_message); ?></textarea> 
                <br>
                <span class="description"><?php _e('This message will be shown user if already subscribed','ultimate-subscribe'); ?></span>
            </div>
        </div>
        <div class="field-row">
            <div class="field-label"> <?php _e('After Confirmation Success Message', 'ultimate-subscribe'); ?> </div>
            <div class="field-data"> 
                <textarea class="input-field" name="ultimate_subscribe_options[form_messages][confirm]"><?php echo esc_html($confirm_success_message); ?></textarea>
                <br>
                <span class="description"><?php _e('This message will be shown after user confirmation success','ultimate-subscribe'); ?></span>
            </div>
        </div>
        <div class="field-row">
            <div class="field-label"> <?php _e('Already Confirmed Message', 'ultimate-subscribe'); ?> </div>
            <div class="field-data"> 
                <textarea class="input-field" name="ultimate_subscribe_options[form_messages][already_confirm]"><?php echo esc_html($alerady_active_message); ?></textarea> 
                <br>
                <span class="description"><?php _e('This message will be shown if subscription is already active.','ultimate-subscribe'); ?></span>
            </div>
        </div>
        <div class="field-row">
            <div class="field-label"> <?php _e('Invalid Details Error Message  ', 'ultimate-subscribe'); ?> </div>
            <div class="field-data"> 
                <textarea class="input-field" name="ultimate_subscribe_options[form_messages][invalid_details]"><?php echo esc_html($invalid_details_message);  ?></textarea> 
                <br>
                <span class="description"><?php _e('This message will be shown if invalid detail submit or error occur','ultimate-subscribe'); ?></span>
            </div>
        </div>
        <div class="field-row">
            <div class="field-label"> <?php _e('Unexpected Error Message  ', 'ultimate-subscribe'); ?> </div>
            <div class="field-data"> 
                <textarea class="input-field" name="ultimate_subscribe_options[form_messages][error]"><?php echo esc_html($unexpected_error_message);  ?></textarea> 
                <br>
                <span class="description"><?php _e('This message will be shown if unexpected error occur','ultimate-subscribe'); ?></span>
            </div>
        </div>
    </div>
</div>