=== WP Post Notifier For All ===

Contributors: Fayçal Tirich
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=GEUJA8MV256VE
Tags: notify, post, notifier
Requires at least: 3.0
Tested up to: 4.5.2
Stable tag: 2.7

Notify all Wordpress users (and not only the admin) on every post publishing.

== Description ==

Notify all Wordpress users (and not only the admin) on every post publishing. The notification is only sent on the first publishing action and not after every update.

== Installation ==

1. Upload `wp-post-notifier-for-all` to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Set your notification template

== Frequently Asked Questions ==


== Changelog ==
= 2.7 =
* Admin option to strip eamil content tags
* Email content return to line fix

= 2.6 =
* Admin option to choose excluding or NOT post owner from notification

= 2.5 =
* Adding [CONTENT] in email body template 

= 2.4 =
* Excluding post owner from notification

= 2.3 =
* Adding [CATEGORIES] in email body template

= 2.2.1 =
* Fixing a text/html content type problem with some email clients 

= 2.2 =
* Adding [BLOG_NAME] as template to the notification body
* Removing useless [LOGO] template, one can include its logo directly using img HTML tag

= 2.1 =
* Adding the possibility to include the excerpt in the email notification body
* using wp_mail instead of PHP mail

= 2.0 =
* Fix the compatibility with Wordpress 3.x

= 1.0 =
* First release 


== Screenshots ==

1. Admin settings
2. Enable/Disable for users