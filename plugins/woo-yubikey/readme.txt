=== yubikey-plugin ===
Contributors: Henrik.Schack, Adam lyons
Donate Link: 
Tags: authentication,yubikey,password,security,login
Requires at least: 3.8
Tested up to: 5.3
Stable tag: 2.3

Enhanced Login Security for Your Wordpress blog.

== Description ==

This is a plugin for Wordpress that provides multifactor authentication with one-time passwords using the [Yubikey USB token](http://www.yubico.com/).  
The plugin uses the Yubico Web service API in the authentication process.  
The one-time password requirement can be enabled on a per user basis.  

== Installation ==

1. [Buy a Yubikey](http://www.yubico.com/o.php?refid=57&rno=444655187)
2. [Create a Yubico ID & API Key](https://upgrade.yubico.com/getapikey/)
3. Unzip plugin into your /wp-content/plugins/ directory.
4. Enter Key ID on the Users -> Profile and Personal options page.
5. Enter Yubico ID & API key on the Settings -> Yubikey options page.  
Id/key confused ? Well the Key ID is the first 12 chars from the output Your Yubikey generates,   
they don't change, the Yubico ID and API Key is used when communicating with the Yubico authentication server.

== Frequently Asked Questions ==

= How much does the Yubikey cost ? =

A single Yubikey is $40

= Are there any special requirements for my Wordpress/PHP installation ? =

PHP5 with Hash & Curl libs enabled.

= I have a lot of users on my Wordpress installation, do they all need Yubikeys ? =

No the plugin can be enabled on a per user basis.

== Screenshots ==

1. Entering Key ID on the profile page
2. Entering Yubico ID & API key on Yubikey options page.
3. The enhanced loginbox.
4. The Yubikey itself.

== Changelog ==
= 2.3 =
Yubi API Version 2 Implemented

= 2.2 =
Darn SVN messing me up

= 2.1 =
Working with more recent API from YubiKey

= 0.96 =
Some depricated stuff removed.
Tab index on login page remove.

= 0.95 =
API key URL updated

= 0.94 =
* Version mess fixed

= 0.93 =
* Styling on descriptions added, once again thanks to Uwe Moosheimer

= 0.92 =
* German translation by Uwe Moosheimer added

= 0.91 =
* Tab index fix on registration page

= 0.90 =
* Support for multiple Yubikeys per account.
* Tested with Wordpress 3.1.1

= 0.82 =
* Russian translation contributed by M. Comfi http://www.comfi.com/

= 0.81 =
* Wordpress global var $is_profile_page has been changed into a constant
* IS_PROFILE_PAGE. Thanks to Koen Vervloesem for reporting this.

= 0.80 =
* More multiuser friendly version. Now, a Yubikey can be registered during
* registration. An Administrator can disable the OTP requirement for other users

= 0.71 =
* Initial release
