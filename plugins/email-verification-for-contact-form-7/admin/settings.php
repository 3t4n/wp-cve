<?php
if(!class_exists('evcf7_settings'))
{
    $evcf7_options = get_option('evcf7_options');
    class evcf7_settings
    {
        public function __construct(){
            add_action( 'admin_init', array('evcf7_settings','register_settings_init'));    // register general settings
            add_action( 'admin_menu', array('evcf7_settings','register_admin_page'));       // add submenu page to cf7
        }

        static function register_admin_page() {
            add_submenu_page( 'wpcf7', 'Email Verification', 'Email Verification', 'manage_options', 'evcf7-email-verify', array('evcf7_settings', 'evcf7_admin_callback') );
        }

        /* setting html */
        static function evcf7_admin_callback() {
            if(! current_user_can( 'administrator' ) && !current_user_can( 'manage_options' ) ){
                wp_die( __('You do not have sufficient permissions to access this page.', 'email-verification-for-contact-form-7'));
            } ?>
            <div class="wrap">
                <h2 class="evcf7-h2-title"><?php _e('Email verification for Contact Form 7','email-verification-for-contact-form-7') ?></h2>
                <?php settings_errors(); ?>

                <form method="post" action="options.php">
                    <?php settings_fields( 'evcf7-setting-options' ); ?>
                    <div class="evcf7-inner-row">
                        <div class="evcf7-col-7">
                            <div class="evcf7-box">
                                <div class="evcf7-fonts-form evcf7-sec">
                                    <?php 
                                        do_settings_sections( 'evcf7_general_section' ); 
                                        submit_button( 'Save Settings' );
                                    ?>
                                </div>
                            </div>
                        </div>
                        <div class=" evcf7-col-5">
                            <div class="evcf7-box">
                                <div class="evcf7-pro-features-box">
                                    <h3 class="evcf7-h3-title"><?php echo esc_html('Email verification for Contact Form 7 Pro','email-verification-for-contact-form-7'); ?></h3>
                                    <ul class="evcf7-pro-features-list">
                                        <li><?php echo esc_html('Set the custom text for Resend OTP button.','email-verification-for-contact-form-7'); ?></li>
                                        <li><?php echo esc_html('Option to set OTP expiry times.','email-verification-for-contact-form-7'); ?></li>
                                        <li><?php echo esc_html('Display the OTP expiry countdown timer with your custom message.','email-verification-for-contact-form-7'); ?></li>
                                        <li><?php echo esc_html('Admin can change Expired OTP Message.','email-verification-for-contact-form-7'); ?></li>
                                        <li><?php echo esc_html('HTML Supported in OTP mail body.','email-verification-for-contact-form-7'); ?></li>
                                        <li><?php echo esc_html('Admin can change position of verify button.','email-verification-for-contact-form-7'); ?></li>
                                        <li><?php echo esc_html('Admin can change length of OTP which users received via email.','email-verification-for-contact-form-7'); ?></li>
                                        <li><?php echo esc_html('Timely','email-verification-for-contact-form-7'); ?> <a href="https://geekcodelab.com/contact/" target="_blank"><?php echo esc_html('support','email-verification-for-contact-form-7'); ?></a> <?php echo esc_html('24/7.','email-verification-for-contact-form-7'); ?></li>
                                        <li><?php echo esc_html('Regular updates.','email-verification-for-contact-form-7'); ?></li>
                                        <li><?php echo esc_html('Well documented.','email-verification-for-contact-form-7'); ?></li>
                                    </ul>
                                    <a href="<?php echo esc_url(EVCF7_PRO_PLUGIN_URL); ?>"
                                        class="evcf7-buy-now-btn" title="Upgrade to Premium" target="_blank"><?php echo esc_html('Upgrade to Premium','email-verification-for-contact-form-7'); ?></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <?php
        }

        static function register_settings_init() {
            register_setting( 'evcf7-setting-options', 'evcf7_options', array('evcf7_settings','sanitize_settings'));

            add_settings_section(
                'evcf7_general_setting',
                __( 'General Setting', 'email-verification-for-contact-form-7' ),
                array(),
                'evcf7_general_section'
            );
            add_settings_field(
                'verify_button_text',
                __('Button Text', 'email-verification-for-contact-form-7'),
                array( 'evcf7_settings', 'verify_button_text_call'),
                'evcf7_general_section',
                'evcf7_general_setting', 
                [
                    'label_for' => 'verify_button_text'
                ]
            );
            add_settings_field(
                'resend_button_label',
                __('Resend OTP Button Text', 'email-verification-for-contact-form-7'),
                array( 'evcf7_settings', 'resend_button_label_call'),
                'evcf7_general_section',
                'evcf7_general_setting', 
                [
                    'label_for' => 'resend_button_label',
                    'class'     => 'evcf7_disabled'
                ]
            );
            add_settings_field(
                'verification_otp_length',
                __('OTP Length', 'email-verification-for-contact-form-7'),
                array( 'evcf7_settings', 'verification_otp_length_call'),
                'evcf7_general_section',
                'evcf7_general_setting', 
                [
                    'label_for' => 'verification_otp_length',
                    'class'     => 'evcf7_disabled'
                ]
            );
            add_settings_field(
                'otp_expiration_time',
                __('Set OTP expiry time', 'email-verification-for-contact-form-7'),
                array( 'evcf7_settings', 'otp_expiration_time_call'),
                'evcf7_general_section',
                'evcf7_general_setting', 
                [
                    'label_for' => 'otp_expiration_time',
                    'class'     => 'evcf7_disabled'
                ]
            );
            add_settings_field(
                'button_form_tag',
                __('Verify Button Form Tag', 'email-verification-for-contact-form-7'),
                array( 'evcf7_settings', 'button_form_tag_call'),
                'evcf7_general_section',
                'evcf7_general_setting', 
                [
                    'label_for' => 'button_form_tag',
                    'class'     => 'evcf7_disabled'
                ]
            );
            add_settings_field(
                'invalid_format_message',
                __('Invalid Format Message', 'email-verification-for-contact-form-7'), 
                array( 'evcf7_settings', 'invalid_format_message_call'),
                'evcf7_general_section',
                'evcf7_general_setting', 
                [
                    'label_for' => 'invalid_format_message'
                ]
            );
            add_settings_field(
                'success_otp_message',
                __('Success OTP Email Message', 'email-verification-for-contact-form-7'), 
                array( 'evcf7_settings', 'success_otp_message_call'),
                'evcf7_general_section',
                'evcf7_general_setting', 
                [
                    'label_for' => 'success_otp_message'
                ]
            );
            add_settings_field(
                'error_otp_message',
                __('Error OTP Email Message', 'email-verification-for-contact-form-7'), 
                array( 'evcf7_settings', 'error_otp_message_call'),
                'evcf7_general_section',
                'evcf7_general_setting', 
                [
                    'label_for' => 'error_otp_message'
                ]
            );
            add_settings_field(
                'invalid_otp_message',
                __('Invalid OTP Message', 'email-verification-for-contact-form-7'), 
                array( 'evcf7_settings', 'invalid_otp_message_call'),
                'evcf7_general_section',
                'evcf7_general_setting', 
                [
                    'label_for' => 'invalid_otp_message'
                ]
            );
            add_settings_field(
                'otp_expiration_message',
                __('OTP Countdown message', 'email-verification-for-contact-form-7'), 
                array( 'evcf7_settings', 'otp_expiration_message_call'),
                'evcf7_general_section',
                'evcf7_general_setting', 
                [
                    'label_for' => 'otp_expiration_message',
                    'class'     => 'evcf7_disabled'
                ]
            );
            add_settings_field(
                'otp_expired_message',
                __('OTP Expired Message', 'email-verification-for-contact-form-7'), 
                array( 'evcf7_settings', 'otp_expired_message_call'),
                'evcf7_general_section',
                'evcf7_general_setting', 
                [
                    'label_for' => 'otp_expired_message',
                    'class'     => 'evcf7_disabled'
                ]
            );
            add_settings_field(
                'email_subject',
                __('Email Subject', 'email-verification-for-contact-form-7'), 
                array( 'evcf7_settings', 'email_subject_call'),
                'evcf7_general_section',
                'evcf7_general_setting', 
                [
                    'label_for' => 'email_subject'
                ]
            );
            add_settings_field(
                'email_content',
                __('Email Message Body', 'email-verification-for-contact-form-7'), 
                array( 'evcf7_settings', 'email_content_call'),
                'evcf7_general_section',
                'evcf7_general_setting', 
                [
                    'label_for' => 'email_content'
                ]
            );
            add_settings_field(
                'verify_button_color',
                __('Verify Button Color', 'email-verification-for-contact-form-7'), 
                array( 'evcf7_settings', 'verify_button_color_call'),
                'evcf7_general_section',
                'evcf7_general_setting', 
                [
                    'label_for' => 'verify_button_color'
                ]
            );
            add_settings_field(
                'verify_button_text_color',
                __('Verify Button Text Color', 'email-verification-for-contact-form-7'), 
                array( 'evcf7_settings', 'verify_button_text_color_call'),
                'evcf7_general_section',
                'evcf7_general_setting', 
                [
                    'label_for' => 'verify_button_text_color'
                ]
            );
            add_settings_field(
                'success_message_color',
                __('Success OTP Message Color', 'email-verification-for-contact-form-7'),
                array( 'evcf7_settings', 'success_message_color_call'),
                'evcf7_general_section',
                'evcf7_general_setting',
                [
                    'label_for' => 'success_message_color'
                ]
            );
            add_settings_field(
                'error_message_color',
                __('Error OTP Message Color', 'email-verification-for-contact-form-7'), 
                array( 'evcf7_settings', 'error_message_color_call'),
                'evcf7_general_section',
                'evcf7_general_setting',
                [
                    'label_for' => 'error_message_color'
                ]
            );
        }

        static function sanitize_settings($input) {
                
            $new_input = array();
            if( isset( $input['verify_button_text'] )  && !empty($input['verify_button_text'])) {
                $new_input['verify_button_text'] = sanitize_text_field($input['verify_button_text']);
            }else{ 
                $new_input['verify_button_text'] = sanitize_text_field('Click here to verify your mail');
            }

            if( isset( $input['invalid_format_message'] ) && !empty($input['invalid_format_message']) ) {
                $new_input['invalid_format_message'] = sanitize_textarea_field($input['invalid_format_message']);
            }else{ 
                $new_input['invalid_format_message'] = sanitize_textarea_field('Please enter a valid Email Address. E.g:abc@abc.abc');
            }

            if( isset( $input['success_otp_message'] ) && !empty($input['success_otp_message']) ) {
                $new_input['success_otp_message'] = sanitize_textarea_field($input['success_otp_message']);
            }else{
                $new_input['success_otp_message'] = sanitize_textarea_field('A One Time Passcode has been sent to {email} Please enter the OTP below to verify your Email Address. If you cannot see the email in your inbox, make sure to check your SPAM folder.');
            }

            if( isset( $input['error_otp_message'] ) && !empty($input['error_otp_message']) ) {
                $new_input['error_otp_message'] = sanitize_textarea_field($input['error_otp_message']);
            }else{
                $new_input['error_otp_message'] = sanitize_textarea_field('There was an error in sending the OTP. Please verify your email address or contact site Admin.');
            }
                
            if( isset( $input['invalid_otp_message'] ) && !empty($input['invalid_otp_message']) ) {
                $new_input['invalid_otp_message'] = sanitize_text_field($input['invalid_otp_message']);
            }else{
                $new_input['invalid_otp_message'] = sanitize_text_field('Invalid OTP. Please enter a valid OTP.');
            }
                
            if( isset( $input['email_subject'] ) )
                $new_input['email_subject'] = sanitize_text_field($input['email_subject']);
            if( isset( $input['email_content'] ) ) {
                $allowed_elemets = array( 'br' => array(), 'strong' => array(), 'b' => array(), 'i' => array(), 'u' => array() );
                $new_input['email_content'] = sanitize_textarea_field(htmlentities(wp_kses($input['email_content'],$allowed_elemets)));
            }
            if( isset( $input['verify_button_color'] ) )
                $new_input['verify_button_color'] = sanitize_text_field($input['verify_button_color']);
            if( isset( $input['verify_button_text_color'] ) )
                $new_input['verify_button_text_color'] = sanitize_text_field($input['verify_button_text_color']);
            if( isset( $input['success_message_color'] ) )
                $new_input['success_message_color'] = sanitize_text_field($input['success_message_color']);
            if( isset( $input['error_message_color'] ) )
                $new_input['error_message_color'] = sanitize_text_field($input['error_message_color']);

            return $new_input;
        }

        /* general setting html */
        static function verify_button_text_call($args) {
            global $evcf7_options;
            $value = isset($evcf7_options[$args['label_for']]) ? sanitize_text_field($evcf7_options[$args['label_for']]) : ''; ?>
            <input class="evcf7-text-field" type="text" name="evcf7_options[<?php esc_attr_e( $args['label_for'] ); ?>]" 
                id="<?php esc_attr_e( $args['label_for'] ); ?>" value="<?php _e($value,'email-verification-for-contact-form-7'); ?>">
            <?php 
        }

        static function resend_button_label_call($args) { ?>
                <div class="evcf7-pro-feature-field">
                    <input type="text" class="evcf7-text-field" value="Click here to Resend email" disabled>
                    <span class="evcf7-pro">pro</span>
                </div>
            <?php 
        }

        static function verification_otp_length_call($args) { ?>
                <div class="evcf7-pro-feature-field">
                    <input type="number" class="evcf7-text-field" value="6" disabled>
                    <span class="evcf7-pro">pro</span>
                </div>
                <p class="evcf7-note"><i><?php echo esc_html('Use length between 4 to 10. Default is 6.','email-verification-for-contact-form-7'); ?></i></p>
            <?php 
        }

        static function otp_expiration_time_call($args) { ?>
                <div class="evcf7-pro-feature-field">
                    <input type="number" class="evcf7-text-field" value="1" disabled>
                    <span class="evcf7-pro">pro</span>
                </div>
                <p class="evcf7-note"><i><?php echo esc_html('Set the OTP expiry time in minute. Default is 0 minute.','email-verification-for-contact-form-7'); ?></i></p>
            <?php 
        }

        static function button_form_tag_call($args) { ?>
                <div class="evcf7-pro-feature-field">
                    <input class="evcf7-text-field" type="text" value="[evcf7_verify_button]" style="text-align:center;" disabled>
                    <span class="evcf7-pro">pro</span>
                </div>    
                <p class="evcf7-note"><i><?php echo esc_html('Add this tag to display button in contact form. Default will display under email field.','email-verification-for-contact-form-7'); ?></i></p>
            <?php
        }

        static function invalid_format_message_call($args) {
            global $evcf7_options;
            $value = isset($evcf7_options[$args['label_for']]) ? sanitize_textarea_field($evcf7_options[$args['label_for']]) : ''; ?>
            <textarea name="evcf7_options[<?php esc_attr_e( $args['label_for'] ); ?>]" id="<?php esc_attr_e( $args['label_for'] ); ?>" 
                cols="30" rows="3"><?php _e($value,'email-verification-for-contact-form-7'); ?></textarea>
            <?php
        }

        static function success_otp_message_call($args) {
            global $evcf7_options;
            $value = isset($evcf7_options[$args['label_for']]) ? sanitize_textarea_field($evcf7_options[$args['label_for']]) : ''; ?>
            <textarea name="evcf7_options[<?php esc_attr_e( $args['label_for'] ); ?>]" id="<?php esc_attr_e( $args['label_for'] ); ?>" 
                cols="30" rows="3"><?php _e($value,'email-verification-for-contact-form-7'); ?></textarea>
            <?php 
        }

        static function error_otp_message_call($args) {
            global $evcf7_options;
            $value = isset($evcf7_options[$args['label_for']]) ? sanitize_textarea_field($evcf7_options[$args['label_for']]) : ''; ?>
            <textarea name="evcf7_options[<?php esc_attr_e( $args['label_for'] ); ?>]" id="<?php esc_attr_e( $args['label_for'] ); ?>" 
                cols="30" rows="3"><?php _e($value,'email-verification-for-contact-form-7'); ?></textarea>
            <?php 
        }

        static function invalid_otp_message_call($args) {
            global $evcf7_options;
            $value = isset($evcf7_options[$args['label_for']]) ? sanitize_textarea_field($evcf7_options[$args['label_for']]) : ''; ?>
            <textarea name="evcf7_options[<?php esc_attr_e( $args['label_for'] ); ?>]" id="<?php esc_attr_e( $args['label_for'] ); ?>" 
                cols="30" rows="3"><?php _e($value,'email-verification-for-contact-form-7'); ?></textarea>
            <?php 
        }

        static function otp_expiration_message_call($args) { ?>
                <div class="evcf7-pro-feature-field">
                    <textarea cols="30" rows="3" disabled>Your OTP will expiring in {minutes} minutes and {seconds} seconds.</textarea>
                </div>
                <p><span><i>Use these tags to display the expiry countdown timer. like <code>{minutes}</code> <code>{seconds}</code>. Available in <a href="<?php echo esc_url(EVCF7_PRO_PLUGIN_URL); ?>" target="_blank" title="Buy Email Verification For Contact Form 7 Pro"><?php _e('Pro Version') ?></a>.</i></span></p>
            <?php 
        }

        static function otp_expired_message_call($args) { ?>
            <div class="evcf7-pro-feature-field">
                <textarea cols="30" rows="3" disabled>OTP expired, please request a new one.</textarea>
            </div>
            <p><span><i><?php echo esc_html('This message is displayed after OTP expired and the countdown is finished. Available only in the'. ''); ?> <a href="<?php echo esc_url(EVCF7_PRO_PLUGIN_URL); ?>" target="_blank" title="Buy Email Verification For Contact Form 7 Pro"><?php _e('Pro Version') ?></a>.</i></span></p>
            <?php 
        }

        static function email_subject_call($args) {
            global $evcf7_options;
            $value = isset($evcf7_options[$args['label_for']]) ? sanitize_text_field($evcf7_options[$args['label_for']]) : ''; ?>
            <input class="evcf7-text-field" type="text" name="evcf7_options[<?php esc_attr_e( $args['label_for'] ); ?>]"
                id="<?php esc_attr_e( $args['label_for'] ); ?>" value="<?php _e($value,'email-verification-for-contact-form-7'); ?>">
            <?php 
        }

        static function email_content_call($args) {
            global $evcf7_options;
            $value = isset($evcf7_options[$args['label_for']]) ? sanitize_textarea_field($evcf7_options[$args['label_for']]) : ''; ?>
            <textarea name="evcf7_options[<?php esc_attr_e( $args['label_for'] ); ?>]"
                id="<?php esc_attr_e( $args['label_for'] ); ?>" cols="30" rows="3"><?php _e($value,'email-verification-for-contact-form-7'); ?></textarea>
                <p><span><i><?php _e("HTML content for OTP's mail body is supported in") ?> <a href="<?php echo esc_url(EVCF7_PRO_PLUGIN_URL); ?>" target="_blank" title="Buy Email Verification For Contact Form 7 Pro"><?php _e('Email Verification For Contact Form 7 Pro.') ?></a></i></span></p>
            <?php 
        }

        static function verify_button_color_call($args) {
            global $evcf7_options;
            $value = isset($evcf7_options[$args['label_for']]) ? sanitize_text_field($evcf7_options[$args['label_for']]) : ''; ?>
            <input type="text" class="evcf7-color-field" name="evcf7_options[<?php esc_attr_e( $args['label_for'] ); ?>]"
                id="<?php esc_attr_e( $args['label_for'] ); ?>" value="<?php _e($value,'email-verification-for-contact-form-7'); ?>">
            <?php 
        }

        static function verify_button_text_color_call($args) {
            global $evcf7_options;
            $value = isset($evcf7_options[$args['label_for']]) ? sanitize_text_field($evcf7_options[$args['label_for']]) : ''; ?>
            <input type="text" class="evcf7-color-field" name="evcf7_options[<?php esc_attr_e( $args['label_for'] ); ?>]"
                id="<?php esc_attr_e( $args['label_for'] ); ?>" value="<?php _e($value,'email-verification-for-contact-form-7'); ?>">
            <?php 
        }
        
        static function success_message_color_call($args) {
            global $evcf7_options;
            $value = isset($evcf7_options[$args['label_for']]) ? sanitize_text_field($evcf7_options[$args['label_for']]) : ''; ?>
            <input type="text" class="evcf7-color-field" name="evcf7_options[<?php esc_attr_e( $args['label_for'] ); ?>]"
                id="<?php esc_attr_e( $args['label_for'] ); ?>" value="<?php _e($value,'email-verification-for-contact-form-7'); ?>">
            <?php 
        }

        static function error_message_color_call($args) {
            global $evcf7_options;
            $value = isset($evcf7_options[$args['label_for']]) ? sanitize_text_field($evcf7_options[$args['label_for']]) : ''; ?>
            <input type="text" class="evcf7-color-field" name="evcf7_options[<?php esc_attr_e( $args['label_for'] ); ?>]"
                id="<?php esc_attr_e( $args['label_for'] ); ?>" value="<?php _e($value,'email-verification-for-contact-form-7'); ?>">
            <?php 
        }

    }
    new evcf7_settings();
}