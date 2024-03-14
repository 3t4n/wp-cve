=== WP Advanced Math Captcha ===
Contributors: AntiCaptcha
Tags: antispam, capcha, captcha, spam, security, cf7, contact form 7
Requires at least: 4.0
Tested up to: 6.0
Stable tag: 1.2.20
License: MIT License
License URI: http://opensource.org/licenses/MIT

Math Captcha is a 100% effective CAPTCHA for WordPress that integrates into login, registration, comments, Contact Form 7 and bbPress.

== Description ==

[Math Captcha]() is a 100% effective and easy to use CAPTCHA for WordPress that seamlessly integrates into login, registration, lost password, comments, bbPress and Contact Form 7.

For more information, check out the [plugin page]() or see the [Support Forum]().

= Features include: =

* Select where to use math captcha: login, registration and lost password forms, comments, Contact Form 7 and bbPress
* Hiding captcha for logged in users
* Select which mathematical operation to use
* Displaying captcha as numbers and/or words
* Multiple captcha on one page support
* Block spambots direct access to wp-comments-post.php
* Option to set captcha field title
* Option to set captcha input time
* .pot file for translations included
* IP filtering (hides captcha for IP or subnet)
* GEO filtering (hides captcha for selected trusted countries)

== Installation ==

1. Install Math Captcha either via the WordPress.org plugin directory, or by uploading the files to your server
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Go to the Math Captcha menu and set your captcha display settings.

== Frequently Asked Questions ==

= Q. I have a question =

A. Chances are, someone else has asked it. Check out the support forum at:

== Screenshots ==

1. screenshot-1.png

== Changelog ==



= 1.2.10 =
* Fix: Call to undefined function wpcf7_add_form_tag

= 1.2.9 =
* Fix: Potential vulnerability bugs
* Tweak: Improve getting server host name for comment blocking

= 1.2.8 =
* Fix: Potential vulnerability bug

* Tweak: Contact Form 7 compatibility

= 1.2.7 =
* Fix: Contact form 7 compatibility fix
* Tweak: Removed local translation files in favor of WP repository translations.

= 1.2.6 =
* Fix: Contact form 7 compatibility fix

= 1.2.5.1 =
* Fix: Final fix for CF7

= 1.2.5 =
* Fix: Contact Form 7 validation issue, due to recent CF7 changes

= 1.2.4 =
* Tweak: Switch from wp_generate_password() to custom function due to Jetpack statistics DB calls issue
* Fix: Undefined contant notice in plugin settings

= 1.2.3 =
* New: Romanian translation, thanks to [Robert M.]()

= 1.2.2 =
* New: Hebrew translation, thanks to [Ahrale Shrem]()

= 1.2.1 =
* New: Slovak translation, thanks to [Ján Fajčák]()

= 1.2.0 =
* Tweak: Added option to reset settings to defaults
* Tweak: Code rewritten to singleton design pattern
* Fix: Contact Form 7 compatibility issues
* New: Option to donate this plugin :)

= 1.1.1 =
* Tweak: UI improvements for WordPress 3.8

= 1.1.0 =
* New: Option to block spambots direct access to wp-comments-post.php
* New: Basic CSS styling of Math Captcha fields

= 1.0.9 =
* New: Multiple Math Captcha on one page support
* New: Chinese translation, thanks to xiaoyaole
* Fix: COntact Form 7 errors and general CF7 support
* Tweak: crypt_key generation changed to AUTH_KEY usage

= 1.0.8 =
* Fix: Strict Standards warnings on some server setups.

= 1.0.7 =
* New: Italian translation, thanks to Alessandro Fiorotto

= 1.0.6 =
* New: Dutch translation, thanks to [Monique]()
* New: French translation, thanks to sil3r

= 1.0.5 =
* New: Japanese translation, thanks to stranger-jp

= 1.0.4 =
* New: Russian translation, thanks to Valery Ryaboshapko

= 1.0.3 =
* New: German translation, thanks to Stephan Hilchenbach

= 1.0.2 =
* New: Option to delete plugin settings on deactivation
* New: Persian translation by Ali Mirzaei
* Fix: Captcha time expired error - thanks to Aaron and Simo 

= 1.0.1 =
* Fix: Math Captcha not available in Contact Form 7 if hide for logged in users selected
* Fix: Captcha time expired error on bbPress 
* Tweak: Empty captcha title

= 1.0.0 =
* Initial release

== Upgrade Notice ==

= 1.2.10 =
* Fix: Call to undefined function wpcf7_add_form_tag