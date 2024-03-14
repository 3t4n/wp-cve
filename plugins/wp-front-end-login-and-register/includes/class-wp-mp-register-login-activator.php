<?php
/**
 * Fired during plugin activation
 *
 * @link       http://www.daffodilsw.com/
 * @since      1.0.0
 *
 * @package    Wp_Mp_Register_Login
 * @subpackage Wp_Mp_Register_Login/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Wp_Mp_Register_Login
 * @subpackage Wp_Mp_Register_Login/includes
 * @author     Jenis Patel <jenis.patel@daffodilsw.com>
 */
class Wp_Mp_Register_Login_Activator
{

    /**
     *
     * @since    1.0.0
     */
    public static function activate()
    {
        //delete old settings "wpmp_settings"
        if (get_option('wpmp_settings')) {
            delete_option('wpmp_settings');
        }
        $wpmp_redirect_settings = get_option("wpmp_redirect_settings");
        $wpmp_display_settings = get_option("wpmp_display_settings");
        $wpmp_form_settings = get_option("wpmp_form_settings");
        $wpmp_email_settings = get_option("wpmp_email_settings");


        //initialize redirect settings
        if (empty($wpmp_redirect_settings)) {

            $wpmp_redirect_settings = array(
                'wpmp_login_redirect' => '-1',
                'wpmp_logout_redirect' => '-1',
            );
            add_option('wpmp_redirect_settings', $wpmp_redirect_settings);
        }

        //initialize display settings
        if (empty($wpmp_display_settings)) {

            $wpmp_display_settings = array(
                'wpmp_email_error_message' => 'Could not able to send the email notification.',
                'wpmp_account_activated_message' => 'Your account has been activated. You can login now.',
                'wpmp_account_notactivated_message' => 'Your account has not been activated yet, please verify your email first.',
                'wpmp_login_error_message' => 'Username or password is incorrect.',
                'wpmp_login_success_message' => 'You are successfully logged in.',
                'wpmp_password_reset_invalid_email_message' => 'We cannot identify any user with this email.',
                'wpmp_password_reset_link_sent_message' => 'A link to reset your password has been sent to you.',
                'wpmp_password_reset_link_notsent_message' => 'Password reset link not sent.',
                'wpmp_password_reset_success_message' => 'Your password has been changed successfully.',
                'wpmp_invalid_password_reset_token_message' => 'This token appears to be invalid.'
            );
            add_option('wpmp_display_settings', $wpmp_display_settings);
        }

        //initialize form settings
        if (empty($wpmp_form_settings)) {

            $wpmp_form_settings = array(
                'wpmp_signup_heading' => 'Register',
                'wpmp_signin_heading' => 'Login',
                'wpmp_resetpassword_heading' => 'Reset Password',
                'wpmp_signin_button_text' => 'Login',
                'wpmp_signup_button_text' => 'Register',
                'wpmp_returntologin_button_text' => 'Return to Login',
                'wpmp_forgot_password_button_text' => 'Forgot Password',
                'wpmp_resetpassword_button_text' => 'Reset Password',
                'wpmp_enable_captcha' => '1',
                'wpmp_enable_forgot_password' => '1'
            );
            add_option('wpmp_form_settings', $wpmp_form_settings);
        }

        //initialize email settings
        if (empty($wpmp_email_settings)) {

            $wpmp_email_settings = array(
                'wpmp_notification_subject' => 'Welcome to %BLOGNAME%',
                'wpmp_notification_message' => 'Thank you for registering on %BLOGNAME%.
<br><br>
<strong>First Name :</strong> %FIRSTNAME%<br>
<strong>Last Name : </strong>%LASTNAME%<br>
<strong>Username :</strong> %USERNAME%<br>
<strong>Email :</strong> %USEREMAIL%<br>
<strong>Password :</strong> As choosen at the time of registration.
<br><br>
Please visit %BLOGURL% to login.
<br><br>
Thanks and regards,
<br>
The team at %BLOGNAME%',
                'wpmp_admin_email_notification' => '1',
                'wpmp_user_email_confirmation' => '1',
                'wpmp_new_account_verification_email_subject' => '%BLOGNAME% | Please confirm your email',
                'wpmp_new_account_verification_email_message' => 'Thank you for registering on %BLOGNAME%.
<br><br>
Please confirm your email by clicking on below link :
<br><br>
%ACTIVATIONLINK%
<br><br>
Thanks and regards,
<br>
The team at %BLOGNAME%',
                'wpmp_password_reset_email_subject' => '%BLOGNAME% | Password Reset',
                'wpmp_password_reset_email_message' => 'Hello %USERNAME%,
<br><br>
We have received a request to change your password.
Click on the link to change your password : 
<br><br>
%RECOVERYLINK%
<br><br>
Thanks and regards,
<br>
The team at %BLOGNAME%',
            );
            add_option('wpmp_email_settings', $wpmp_email_settings);
        }
    }
}
