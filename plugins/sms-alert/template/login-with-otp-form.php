<?php
/**
 * Login with otp form template.
 * PHP version 5
 *
 * @category Template
 * @package  SMSAlert
 * @author   SMS Alert <support@cozyvision.com>
 * @license  URI: http://www.gnu.org/licenses/gpl-2.0.html
 * @link     https://www.smsalert.co.in/
 */
 
$redirect = isset($_GET['redirect_to'])?$_GET['redirect_to']:$redirect_url;
?>
<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
    <label for="username"><?php esc_html_e($label_field, 'sms-alert'); ?><span class="required">*</span></label>
    <input type="tel" placeholder = "<?php esc_html_e($placeholder_field, 'sms-alert'); ?>" class="woocommerce-Input woocommerce-Input--text input-text sa_mobileno phone-valid" name="username"  value="">
    <input type="hidden" class="woocommerce-Input woocommerce-Input--text input-text" name="redirect" value="<?php esc_html_e($redirect, 'sms-alert'); ?>">
</p>
<?php 
echo apply_filters( 'gglcptch_display_recaptcha','', 'sa_lwo_form' );
?>
<p class="form-row">
    <button type="submit" class="button smsalert_login_with_otp_btn" name="smsalert_login_with_otp_btn" value="<?php echo esc_html_e($button_field, 'sms-alert'); ?>"><span class="button__text"><?php echo esc_html_e($button_field, 'sms-alert'); ?></span></button>    
    <a href="#" onclick="return false;" class="sa_default_login_form" data-parentForm="login"><?php esc_html_e('Back', 'sms-alert'); ?></a>
</p>