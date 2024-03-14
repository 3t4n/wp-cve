=== Custom Login URL ===
Contributors: gwin
Tags: custom login url, custom registration url
Requires at least: 3.8.0
Tested up to: 4.2.2
Stable tag: 1.0.2
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Whitelabel your site by hiding wp-login.php in the login and registration URLs

== Description ==

Custom Login URL (CLU) is a lightweight plugin that allows to customize default WP login, registration and password
reminder URLs without modifying any files, simple and swift.

Why would anyone would want to use this plugin? Well, after developing [WPHelpDesk](http://wphelpdesk.com/) it turned 
out that site owners do not want to reveal to customers that they are using WordPress, hence the plugin that will mask
original URLs.

What the plugin can do:

*   change /wp-login.php to for example /user/login/
*   change /wp-login.php?action=register to for example /user/register/
*   change /wp-login.php?action=lostpassword to for example /user/remind/
*   change /wp-login.php?action=logout to for example /user/logout/
*   you can define your own custom paths for each URL above
*   set successfull login and logout redirect URLs

In order to make the plugin work you need to have Permalinks enabled in WP Settings.


== Installation ==

1. Upload `custom-login-url` folder to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Go to wp-admin / Settings / Permalinks and configure your custom URLs

== Screenshots ==

1. Custom URLs configuration page in wp-admin / Settings / Permalinks panel.

== Changelog ==

= 1.0.2 =
* FIXED: Some 'notices' and 'warnings'
* FIXED: Correct stable tag set.
= 1.0.1 =
* FIXED: Automatic Login Rewrite Activation
= 1.0 =
* Initial release
