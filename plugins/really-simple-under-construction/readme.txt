=== Really Simple Under Construction Page ===
Tags: temporary site, under construction, construction, hide, hide page, hide site, secret key, really simple
Requires at least: 6.0.0
Tested up to: 6.2.2
Stable tag: 1.4.6
License: GPLv2 or later
Contributors: jonashjalmarsson
Requires PHP: 7.4
Donate link: https://paypal.me/byjalma

Adds a really simple version of a Under Construction page to your website. Use secret word in URL or IP addresses to a whitelist to by-pass for test purpose.

== Description ==
Add a really simple version of a Under Construction page to your website by enable this plugin. Use IP to restrict all users except the whitelisted addresses.

Go to the settings page in Settings > Really Simple Under Construction. Enable by checking the checkbox. The Under Construction site is only visible if not logged in. Optionally you can set three things: 
1. Customize the Under Construction site by adding plain HTML that will be displayed.
2. Set the secret key to be able to by-pass the Under Construction page. 
3. Set for how long the by-pass should work. 
4. Add IP addresses for whitelisting a users or services.

== Changelog ==

= 1.4.6 =
* Bugfix, not working for startpage since 1.4.5.

= 1.4.5 =
* Minor code cleanup

= 1.4.4 =
* Improved handling of login page

= 1.4.3 =
* Added setting to make Wordpress static Homepage to be visible, the plugin still restricts all other pages.

= 1.4.2 =
* Ignore if call to webhook wp-json

= 1.4.1 =
* Bugfix Wordpress login blocked

= 1.4 =
* Ignore if call to webhook wc-api

= 1.3.2 =
* Minor bugfixes

= 1.3.1 =
* Add your IP to textfield link added.

= 1.3 =
* Whitelisting with IP address added. Settings layout updated. Refactored code.

= 1.2.1 =
* Settings link added in plugins list. Author information updated.

= 1.2 =
* Bugfix, not working for startpage in some set ups.

= 1.0 =
* Language support added. sv_SE and en_US in first version.

= 0.2 =
* Fix to ignore "Under Construction" page if current page is wp-admin or wp-login.php. Handles custom URLs.

= 0.1 =
* First commit.

== Upgrade Notice ==

= 0.2 =
Upgrade, fixing beeing locked out from wp-admin if no secret word is set.

= 0.1 =
First commit.

== Screenshots ==

1. This is the admin page of Really Simple Construction Page
