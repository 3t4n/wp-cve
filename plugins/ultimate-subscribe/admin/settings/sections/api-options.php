<?php 
$options                = get_option('ultimate_subscribe_options');
$mailchimp_api_key      = isset($options['mailchimp_api'])?$options['mailchimp_api']:'';
$getresponse_api_key    = isset($options['getresponse_api'])?$options['getresponse_api']:'';

?>
 <div id="api-options" class="tab-pane">
    <h3> <?php esc_html_e('API Options', 'ultimate-subscribe'); ?> </h3>
    <div class="form-fieldset">
        <div class="field-group">
            <div class="field-row">
                <div class="field-label"> <?php _e('MailChimp Api Key', 'ultimate-subscribe'); ?> </div>
                <div class="field-data"> <input type="text" class="input-field" value="<?php echo esc_attr($mailchimp_api_key); ?>" name="ultimate_subscribe_options[mailchimp_api]"> </div>
            </div>
        </div>
    </div>
</div>