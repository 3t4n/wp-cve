=== Emergency Password Reset ===
Contributors: andymoyle
Donate link: https://www.paypal.me/andymoyle
Tags: emergency password reset
Requires at least: 2.7.0
Tested up to: 6.3
Stable tag: 8.0
Text Domain: emergency-password-reset
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

This plugin allows the admin to reset all the passwords and automatically email out the link to reset

== Description ==

This plugin does 3 things
1) It will check you don't have a username called "admin" which is asking to be hacked
2) It will allow you to reset all passwords, with an password reset link sent to all users to warn them.
Following a couple of reviews from v7.0 the plugin will allow you to set the email from address, name, subject and message
3) You can also change the SALTS which forces a logout of all users.

== Installation ==

1. Upload the `emergency-password-reset` directory to the `/wp-content/plugins/` directory.
2. Activate the plugin through the 'Plugins' menu in WordPress.
3. Click on Emergency Password Reset in the Users menu
4. Adjust the settings as required
5. Click on the 'Reset Passwords' button

== Frequently Asked Questions ==
= How does it work? =
When you click rest passwords, the plugin recreates random passwords for every user and emails them the reset password link.

= Will I be secure now from a hack? =
Not necessarily. We advise you change your SALTS in the wp-config.php file which will force logouts for all users. Wordpress provide a <a href="https://api.wordpress.org/secret-key/1.1/salt/">tool</a> to generate new ones.
You can now reset them automatically from the plugin Dashboard>Settings>Reset SALTs
Check out our <a href="http://www.themoyles.co.uk/2013/02/so-your-wordpress-site-has-been-hacked/">blog post</a> on hacked Wordpress sites


== Screenshots ==
1. The main and only screen!

== Changelog ==
= 8.0 =
* Emails sent in batches of 10 as BCC, to avoid crashes and email errors
= 7.0 =
* Setttings to change email name, from and message
= 6.2 =
* Translation ready
= 6.1 =
* New username when changing from "admin" properly sanitized.
= 6.0 =
* Don't allow a user to reset admin username to empty field!
= 5.0 =
* Added WordPress reset "salt keys" to secure your site - Dashboard>Settings>Reset SALTs
= 4.0 =
* Updated deprecated functions
= 3.0 =
* Updated reset link
= 2.0 =
* Password reset link sent
= 1.0 =
* Sends link to reset password page rather than new password
= 0.5 =
* Form to change username from "admin"
= 0.4 =
* Shows WP 4.0 compatability
= 0.3 =
* Add Screenshot
= 0.2 =
* Correct the title in readme.txt!
= 0.1 =
* Initial release



== Upgrade notice ==
* 7.0

