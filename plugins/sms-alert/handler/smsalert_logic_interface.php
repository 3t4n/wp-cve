<?php
/**
 * Smsalert logic interface 
 *
 * PHP version 5
 *
 * @category Handler
 * @package  SMSAlert
 * @author   SMS Alert <support@cozyvision.com>
 * @license  URI: http://www.gnu.org/licenses/gpl-2.0.html
 * @link     https://www.smsalert.co.in/
 */
if (! defined('ABSPATH') ) {
    exit;
}

/**
 * PHP version 5
 *
 * @category Handler
 * @package  SMSAlert
 * @author   SMS Alert <support@cozyvision.com>
 * @license  URI: http://www.gnu.org/licenses/gpl-2.0.html
 * @link     https://www.smsalert.co.in/
 * Phone logic interface class.
 */
abstract class LogicInterface
{
    /**
     * Main Logic handler.
     *
     * @param string $user_login   User name.
     * @param string $user_email   User email id.
     * @param string $phone_number Phone number.
     * @param string $otp_type     OTP type.
     * @param string $from_both    Form name.
     *
     * @return void
     */
    abstract public function _handle_logic( $user_login, $user_email, $phone_number, $otp_type, $from_both);

    /**
     * Handles OTP sent success action.
     *
     * @param string $user_login   user name.
     * @param string $user_email   User email id.
     * @param string $phone_number Phone number.
     * @param string $otp_type     OTP type.
     * @param string $from_both    Form name.
     * @param string $content      Content.
     *
     * @return void
     */
    abstract public function _handle_otp_sent( $user_login, $user_email, $phone_number, $otp_type, $from_both, $content);

    /**
     * Handles OTP sent failed.
     *
     * @param string $user_login   user name.
     * @param string $user_email   User email id.
     * @param string $phone_number Phone number.
     * @param string $otp_type     OTP type.
     * @param string $from_both    Form name.
     * @param string $content      Content.
     *
     * @return void
     */
    abstract public function _handle_otp_sent_failed( $user_login, $user_email, $phone_number, $otp_type, $from_both, $content);

    /**
     * Gets OTP sent success message.
     *
     * @return void
     */
    abstract public function _get_otp_sent_message();

    /**
     * Gets OTP sent failed message.
     *
     * @return void
     */
    abstract public function _get_otp_sent_failed_message();

    /**
     * Gets OTP sent failed due to invalid number format message.
     *
     * @return void
     */
    abstract public function _get_otp_invalid_format_message();

    /**
     * Handles OTP matched action.
     *
     * @param string $user_login   User name.
     * @param string $user_email   User email id.
     * @param string $phone_number Phone number.
     * @param string $otp_type     OTP type.
     * @param string $from_both    Form name.
     *
     * @return void
     */
    abstract public function _handle_matched( $user_login, $user_email, $phone_number, $otp_type, $from_both);

    /**
     * Handles OTP not matched action.
     *
     * @param string $phone_number Phone number.
     * @param string $otp_type     OTP type.
     * @param string $from_both    Form name.
     *
     * @return void
     */
    abstract public function _handle_not_matched( $phone_number, $otp_type, $from_both);

    /**
     * Checks whether ajax form is enabled or not.
     *
     * @return void
     */
    public static function _is_ajax_form()
    {
        return (bool) apply_filters('is_ajax_form', false);
    }
}
