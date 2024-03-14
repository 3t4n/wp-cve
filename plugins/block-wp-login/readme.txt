=== Block wp-login ===
Contributors: domainsupport
Donate link: https://webd.uk/product/support-us/
Tags: security, secure, login security, block hackers, security plugin
Requires at least: 3.5.0
Tested up to: 6.4
Requires PHP: 5.6
Stable tag: 1.5.3
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

This plugin completely blocks access to wp-login.php and creates a new secret login URL

== Description ==
= Block Access to wp-login.php =

This plugin does the following:

- Locates wp-login.php in your WordPress installation and duplicates it
- Locates .htaccess and inserts lines to block the default wp-login.php and creates a new secret address to use for legitimate login
- Will email the site admin if an administrator signs in with an un-recognised IP address

When installed your server will return “403 Forbidden“ when attempts are made to access the default wp-login.php file. This has two benefits; it prevents hackers from using brute force methods to hack your website and it reduces the load on the server when such brute force attacks are launched on your site as WordPress isn't run at all.

Please note, this plugin uses .htaccess so is only compatible with Apache web servers, it is not compatible with Nginx web servers.

== Installation ==

Easily prevent access to the default wp-login.php file:

1) Install Block wp-login automatically or by uploading the ZIP file.
2) Activate the plugin through the ‘Plugins’ menu in WordPress.
3) Once activated, visit “Settings - Permalinks” in the admin menu.
4) At the bottom of the page enter a new login address next to “Block wp-login” or click to create a random address.
5) Make sure you make a note of the new address you will need to use to sign in.
6) Save the settings.

Although this plugin now detects when WordPress has been upgraded and re-installs itself, when upgrading WordPress core, you should still make sure you deactivate this plugin first just in case there is an issue.

== Frequently Asked Questions ==

* What is /wp-login.php ?
This is the login page for WordPress; hundreds or thousands of hits to this page is not normal and is almost certainly a brute force attempt to hack the admin password.

== Changelog ==

= 1.5.3 =
* I18N issues resolved thanks to Alex Lion @alexclassroom and added a transient check to prevent a race condition when WordPress core is updated

= 1.5.2 =
* General housekeeping

= 1.5.1 =
* Added an option to email the site admin if an administrator signs in with an un-recognised IP address
* Added translation strings

= 1.5 =
* General housekeeping

= 1.4.9 =
* Fixed bug that causes an error if the login_url hook is fired early

= 1.4.8 =
* Preparing for WordPress v6.0

= 1.4.7 =
* Fixed a cookie related bug with Google Chrome preventing login

= 1.4.6 =
* Fixed bugs when .htaccess cannot be opened
* Removed all PHP short tags

= 1.4.5 =
* Preparing for WordPress v5.8

= 1.4.4 =
* General housekeeping

= 1.4.3 =
* General housekeeping

= 1.4.2 =
* Added an option to send login URL reminders when saving Permalink settings

= 1.4.1 =
* Added random login generator.

= 1.4.0 =
* Premium functionality is now free!

= 1.3.9 =
* Bug fix

= 1.3.8 =
* Removed functionality now dealt with by Deny All Firewall

= 1.3.7 =
* Yet more fixes for compatibility with WordPress 5.3

= 1.3.6 =
* Further fixes for compatibility with WordPress 5.3

= 1.3.5 =
* Fixed a bug that blocked Admin Email Verification in WordPress 5.3

= 1.3.4 =
* Integrated plugin with new Deny All Firewall plugin

= 1.3.3 =
* Plugin now allows password protected posts and pages to work

= 1.3.2 =
* Important security update

= 1.3.1 =
* Important security update

= 1.3.0 =
* Automated upgrade activation facility
* Bug fixes

= 1.2.4 =
* Bug fix

= 1.2.3 =
* Updating new developer / activation domain
* Updating tested version

= 1.2.2 =
* Bug fixes.

= 1.2.1 =
* WordPress upgrade email re-worded

= 1.2.0 =
* Plugin now automatically detects when WordPress has been upgraded and re-installs itself.
* Bug fixed for when wp_mail() isn’t working

= 1.1.7 =
* Bug fixes.

= 1.1.6 =
* Plugin now upgrades automatically when activated if licensed.

= 1.1.5 =
* Plugin is now internationalised ready for translation.
* Help banner admin notice now appears until plugin has been configured.
* Added help links on the settings page and added this information to the FAQ.
* Minor bug fixes.

= 1.1.4 =
* Blocking admin-ajax.php now allows commands when inniated from /wp-admin/.
* Blank user or site owner emails won't break saving settings.
* Duplicate emails are not sent now when site owner and user email addresses are the same.
* Options to block admin-ajax.php, wp-cron.php, xmlrpc.php and robots.txt are disabled until wp-login.php block is activated.

= 1.1.3 =
* Plugin now emails all Administrators and the email set in General Settings with the new login URL.

= 1.1.2 =
* Added option to block admin-ajax.php, wp-cron.php, xmlrpc.php and robots.txt for the free plugin.

= 1.1.1 =
* Bug fixes.
* Option to block wp-cron.php, admin-ajax.php and robots.txt for upgraded plugin.

= 1.1.0 =
* Plugin re-written to make use of "Settings - Permalinks" so upgraded plugin can choose custom login slug.
* Plugin now reverses changes when deactivated.
* Plugin creates random login slug.

= 1.0.0 =
* First, beta version of the plugin.

== Upgrade Notice ==

= 1.5.3 =
* I18N issues resolved thanks to Alex Lion and added a transient check to prevent a race condition when WordPress core is updated
