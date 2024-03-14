=== Magic Login Mail ===
Contributors: Katsushi Kawamori
Donate link: https://shop.riverforest-wp.info/donate/
Tags: email, login, passwordless, users
Requires at least: 4.7
Requires PHP: 8.0
Tested up to: 6.5
Stable tag: 1.06
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Enter your email address, and send you an email with a magic link to login without a password.

== Description ==

Enter your email address, and send you an email with a magic link to login without a password.

= Login =
* Login with email address only.
* Only registered users can login.
* Password-less login from the magic link notified in the email.
* shortcode : `[magic_login]`
* action hook : `do_action( 'magic_email_send', $emails | array, true | bool )` : To send the magic link simultaneously from the management account.

= Thanks =
* This plugin is a modified version of [Passwordless Login](https://wordpress.org/plugins/passwordless-login/).
* The main changes are the addition of various filter hooks and the addition of the following action hooks.

= Action hook =
 * This is for sending bulk e-mails with a magic link for login to multiple accounts from the management screen.
 * When using this action hook, the URL of the page where the shortcode [magic_login] is placed should be specified in the filter hook 'magic_login_mail_url'.
~~~
/** ==================================================
 * To send the magic link simultaneously from the management account.
 *
 * @param array  $emails  Multiple email addresses.
 * @param bool   true  Output notifications to the management screen immediately after sending.
 */
do_action( 'magic_email_send', $emails, true );
~~~

= Filter hooks =
~~~
/** ==================================================
 * Filter for magic link url with mail.
 *
 */
add_filter( 'magic_login_mail_url', function(){ return 'url'; }, 10, 1 );
~~~
~~~
/** ==================================================
 * Currently logged in user link for Magic Login Mail
 *
 * @param string $url  URL.
 * @param int    $user_id  User ID.
 * @return $url
 */
add_filter( 'magic_login_mail_user_redirect', 'redirect_url_login_users', 10, 2 );

/** ==================================================
 * Login user after redirect for Magic Login Mail
 *
 * @param string $url  URL.
 * @param int    $user_id  User ID.
 * @return $url
 */
add_filter( 'magic_login_mail_after_login_redirect', 'redirect_url_login_users', 10, 2 );

function redirect_url_login_users( $url, $user_id ){
	/* your code */
	return $url;
}
~~~
~~~
/** ==================================================
 * Filter for message with shortcode form.
 *
 */
add_filter( 'magic_login_mail_success_link_msg', function(){ return 'Message for success.'; }, 10, 1 );
add_filter( 'magic_login_mail_success_login_msg', function(){ return 'Message for success with login.'; }, 10, 1 );
add_filter( 'magic_login_mail_valid_errors', function(){ return 'Message for mail validation error.'; }, 10, 1 );
add_filter( 'magic_login_mail_email_errors', function(){ return 'Message for sent mail error.'; }, 10, 1 );
add_filter( 'magic_login_mail_invalid_token_error', function(){ return 'Message for token error.'; }, 10, 1 );
add_filter( 'magic_login_mail_form_label', function(){ return 'Message for form label.'; }, 10, 1 );
~~~
~~~
/** ==================================================
 * Filter for color with shortcode form.
 *
 */
add_filter( 'magic_login_mail_success_link_msg_back_color', function(){ return '#e7f7d3'; }, 10, 1 );
add_filter( 'magic_login_mail_success_login_msg_back_color', function(){ return '#e7f7d3'; }, 10, 1 );
add_filter( 'magic_login_mail_valid_errors_back_color', function(){ return '#ffebe8'; }, 10, 1 );
add_filter( 'magic_login_mail_email_errors_back_color', function(){ return '#ffebe8'; }, 10, 1 );
add_filter( 'magic_login_mail_invalid_token_error_back_color', function(){ return '#ffebe8'; }, 10, 1 );
~~~
~~~
/** ==================================================
 * Filter for input text size.
 *
 */
add_filter( 'magic_login_mail_input_size', function(){ return 17; }, 10, 1 );
~~~
~~~
/** ==================================================
 * Filter for class name.
 *
 */
add_filter( 'magic_login_mail_form_class_name', function(){ return 'myform'; }, 10, 1 );
add_filter( 'magic_login_mail_label_class_name', function(){ return 'mylabel'; }, 10, 1 );
add_filter( 'magic_login_mail_input_class_name', function(){ return 'myinput'; }, 10, 1 );
add_filter( 'magic_login_mail_submit_class_name', function(){ return 'mysubmit'; }, 10, 1 );
~~~
~~~
/** ==================================================
 * Filter for message with mail.
 *
 */
add_filter( 'magic_login_mail_subject', function(){ return 'subject'; }, 10, 1 );
add_filter( 'magic_login_mail_message', function(){ return 'Message with magic link.'; }, 10, 1 );
~~~
~~~
/** ==================================================
 * Filter for login expiration.
 *
 */
add_filter( 'magic_login_mail_expiration', function(){ return 10; }, 10, 1 );
~~~

== Installation ==

1. Upload `magic-login-mail` directory to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress

== Frequently Asked Questions ==

none

== Screenshots ==

1. Login form by shortcode
2. Login form
3. Login success
4. Email with magic link
5. Login error with no user
6. Login error with expired token

== Changelog ==

= [1.06] 2024/03/04 =
* Fix - Elimination of short code attribute values.

= 1.05 =
Supported WordPress 6.4.
PHP 8.0 is now required.

= 1.04 =
Added class name filter('magic_login_mail_form_class_name') for login form.
Added "placeholder" and "required" attributes to the email input form.

= 1.03 =
Added an option to save sent emails. 

= 1.02 =
Changed input size.

= 1.01 =
Supported GlotPress.

= 1.00 =
Initial release.

== Upgrade Notice ==

= 1.00 =
Initial release.

