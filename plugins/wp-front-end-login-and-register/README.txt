=== WP Front-end login and register ===
Contributors: Mohsin khan
Donate link: https://www.paypal.me/mohsinalam
Tags: front end login,front end login and registration, wordpress login,wordpress login and registration, frontend regstration,custom registration, responsive, custom login, custom signup, ajax login, ajax signup, signup, signin, register, registration login, reset password, forgot password
Requires at least: 3.0.1
Tested up to: 5.1.1
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Allows you to add fully customizable ajax powered responsive login, registration and password reset forms anywhere on your website.

== Description ==

WP Front-end login and register is for the sites that need customized user logins or registrations and would like to avoid the classic wordpress login and registration pages, this plugin provides the capability of placing a shortcode anywhere on the website.
It also provides the feature to enable or disable the setting from backend for automating the new account activation via user email verification.
With WP Front-end login and register, you can easily make use of login and registration forms within any responsive wesbsite.


= Some of the features =

* AJAX-powered login ,registration and Profile page with profile picture, no screen refreshes!
* Fully responsive design using Bootstrap.
* Customize your redirect URL after successful login/logout to redirect your users to a custom URL or page.
* Easily customize your login/registration form heading as well as the button text from the backend.
* Custom registration email notification template that can be managed from backend.
* Now your visitors can login or register from the page you've set-up.
* Easy to use : Set up login and registration form using shortcodes.
* Registration notifications emails sent to the registered user and website admin.
* Minimize spam signups via user's email verification.
* Enable/Disable numbered Captcha on registration page.
* Enable/Disable password reset feature to enable registered user to reset the password through a user verification email and secret authentication token.
* Fully Customizable notification messages on login, registration and forgot password forms.
* Fully customizable emails for login, registration and password reset feature.
* Completely new tabbed settings page for easy settings management.
* Automatic new user account confirmation and activation via verification email.
* Easily enable/disable new user email confirmation setting from admin backend.


Just create a normal WordPress page, and use the following shortcodes:

* For login form: `[wpmp_login_form]`
* For registration form: `[wpmp_register_form]`
* For profile page/User dashboard : `[wpmp_user_profile]`

= Usage =

Steps for creating a login or register page.

1. Create a page
1. Add the following shortcode `[wpmp_login_form]` or `[wpmp_register_form]`
1. Publish/Update the page.

Steps for using in theme files:

1. Use the shortcode using `do_shortcode()` wherever you want to show your login or
registration form.

For example : `<?php echo do_shortcode("[wpmp_login_form]"); ?>`


== Installation ==

1. Download `wp-mp-register-login.zip` and upload it to the `/wp-content/plugins/` directory.
1. Activate the plugin through the 'Plugins' menu in WordPress.
1. Use the provided shortcodes anywhere on your website.


== Screenshots ==

1. Displaying the login form on front end
2. Displaying the registration form
3. Registration form with validations
4. Email confirmation message
5. Reset password
6. Backend Settings with tabs
7. Profile Page/User dashboard


== Changelog ==

= 2.3 =
* Fix login with email/Username

= 2.2 =
* Fixed some errors

= 2.1 =
* Added profile page/ user dashboard 
* Add user profile image update option
* Fixed some other minor issues
 
= 2.0 =
* fixed some errors.

= 1.0 =
* First version.


