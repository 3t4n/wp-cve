<?php
if(!class_exists('evcf7_functions'))
{
    $evcf7_options = get_option('evcf7_options');
    class evcf7_functions
    {
        public function __construct() {
            add_action( 'wp_head', array('evcf7_functions','evcf7_head_style') );                               // add dynamic style
            add_filter( 'wpcf7_validate_text', array('evcf7_functions','evcf7_otp_verification'), 50, 3 );      // validate text field
            add_filter( 'wpcf7_validate_text*', array('evcf7_functions','evcf7_otp_verification'), 50, 3 );     // validate text field
        }

        static function evcf7_head_style() {
            global $evcf7_options;
            $verify_btn_color = $verify_text_color = $success_msg_color = $error_btn_color = "";
            if(isset($evcf7_options['verify_button_color']))        $verify_btn_color  = sanitize_text_field($evcf7_options['verify_button_color']);
            if(isset($evcf7_options['verify_button_text_color']))   $verify_text_color = sanitize_text_field($evcf7_options['verify_button_text_color']);
            if(isset($evcf7_options['success_message_color']))      $success_msg_color = sanitize_text_field($evcf7_options['success_message_color']);
            if(isset($evcf7_options['error_message_color']))        $error_btn_color   = sanitize_text_field($evcf7_options['error_message_color']);

            if(!empty($verify_btn_color) || !empty($verify_text_color) || !empty($success_msg_color) || !empty($error_btn_color)) { ?>
                <style type="text/css">
                    <?php global $evcf7_options;
                    if(!empty($verify_btn_color)){
                        printf('.evcf7-verify-btn input[type="button"][name="evcf7-verify-email"] { background-color: %s; }',__($verify_btn_color));
                    } 
                    if(!empty($verify_text_color)){
                        printf('.evcf7-verify-btn input[type="button"][name="evcf7-verify-email"] { color: %s; }',__($verify_text_color));
                    } 
                    if(!empty($success_msg_color)){
                        printf('.evcf7_email_sent{ color: %s; }',__($success_msg_color));
                    } 
                    if(!empty($error_btn_color)){
                        printf('.evcf7_error_sending_mail{ color: %s; }',__($error_btn_color));
                    } ?>
                </style>
            <?php
            }
        }
        
        static function evcf7_otp_verification($result, $tag) {
            global $evcf7_options;
            if(isset($_POST['verification'] ) && isset( $_POST['verification-otp'] ) && !empty($_POST['verification-otp']) && 'verification-otp' == $tag->name){
                if(isset($evcf7_options['invalid_otp_message'])) $invalid_otp_message = sanitize_text_field($evcf7_options['invalid_otp_message']);
                global $wpdb;
                $cur_time   = time();
                $datetime   = date("Y-m-d H:i:s",$cur_time);
                $form_id    =   intval($_POST['_wpcf7']);
                $data_email =   isset( $_POST['verification'] ) ? sanitize_text_field($_POST['verification']) : '';
                $data_otp   =   isset( $_POST['verification-otp'] ) ? sanitize_text_field($_POST['verification-otp']) : '';
                $db_table_name = $wpdb->prefix . 'evcf7_options'; 
                $match_results = $wpdb->get_results("SELECT * FROM  $db_table_name WHERE (form_id='$form_id' AND email='$data_email' AND time <= '$datetime' AND otp='$data_otp')");
                
                if(isset($match_results) && empty($match_results)){
                    $result->invalidate( $tag, $invalid_otp_message );
                }
            }
            
            return $result;
        }
    }
    new evcf7_functions();
}