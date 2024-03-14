=== Plugin Name ===
Contributors: mattkeys
Tags: comments, spam, captcha, invisible, bot, bots, antispam, anti-spam, comment spam, cache, cacheable, cache friendly
Requires at least: 3.5
Tested up to: 4.5
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Block bots without annoying captchas. Cache friendly solution with rotating keys! Blocks comment, registration, and bbpress spam. Activate and done!

== Description ==

Anti-Spam solutions that require your users to fill out captcha's are frustrating for actual humans, and not that great at stopping bots.

This solution works automatically without any user configuration, and puts no additional burden on your users. After activation this plugin will go to work stopping: comment spam, spam bot registration, and bbPress spam.

Key features:

*	Fully compatible with wordpress caching plugins or even more advanced server level caching solutions (like WP Engine uses).
*	Utilizes randomly generated and rotating token field name and token value, which stops more clever bots that might try caching the 'solved' field.
*	Anti-spam field is randomly placed within the form on page load to make it difficult for spammers to target the field.
*	Developer friendly with filters and actions
*	Lightweight, clean, and efficient solution to comment, registration, and bbPress spam
*	Uses native JavaScript to avoid jQuery dependancies

Requires JavaScript be enabled in client browser (Users will see a warning if JavaScript is disabled)

== Installation ==

1. Login to your Wordpress Admin page (usually http://yourdomain.com/wp-admin)
2. Navigate to the Plugins screen and then click the "Add New" button
3. Click on the "Upload" link near the top of the page and browse for the Advanced Invisible Anti-Spam zip file
4. Upload the file, and click "Activate Plugin" after the installation completes
5. Congratulations, installation is complete!

== Screenshots ==

1. ( Comments ) The error message shown to bots, or users who do not have javascript enabled. This message is translatable, and filterable.
2. ( Registration ) The error message shown to bots, or users who do not have javascript enabled. This message is translatable, and filterable.

== Changelog ==

= 1.4.3 =
* Fixed bug where plugin was dependant on transients being stored in the DB (which isn't the case when object caching is enabled)

= 1.4.2 =
* Fixed bug when get_key_name() fails to create a new key if none is found

= 1.4.1 =
* Bug Fix: Fixed fatal error on sites not using bbPress (sorry)

= 1.4 =
* New Feature: Added Anti-Spam capabilities to bbPress new topic and reply forms

= 1.3 =
* New Feature: Added Anti-Spam capabilities to the user registration screen to block spam bot registration

= 1.2.1 =
* New Feature: Added support for ajax type comment forms.
* Fixed obscure bug that caused JS errors when this plugin is used along with BWP Minify plugin (and perhaps other JS concat plugins/methods as well)

= 1.2 =
* New Feature: The anti-spam token field name now also rotates similiar to the token value. Token names expire every 2 hours. The most recently expired field name can also be submitted to without failure. This prevents a commentor from getting caught during the transition between an old and new field name.
* New Feature: Placement of the anti-spam token input is now randomized to appear in different places within the comment form. This coupled with the rotating and random field names makes it difficult for spammers to defeat the system.

= 1.1 =
* Fixed bug that was preventing comment reply ability from the WordPress comment admin area.

== Upgrade Notice ==

= 1.4.3 =
Fixed bug where plugin was dependant on transients being stored in the DB (which isn't the case when object caching is enabled)

= 1.4.2 =
Fixed bug when get_key_name() fails to create a new key if none is found

= 1.4.1 =
Fixed fatal error on sites not using bbPress (sorry)

= 1.4 =
New Feature: Added Anti-Spam capabilities to bbPress new topic and reply forms

= 1.3 =
New Feature: Added Anti-Spam capabilities to the user registration screen to block spam bot registration

= 1.2.1 =
Added support for ajax type comment forms
Fixed obscure bug that caused JS errors when this plugin is used along with BWP Minify plugin (and perhaps other JS concat plugins/methods as well)