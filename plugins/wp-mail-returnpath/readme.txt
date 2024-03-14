=== Plugin Name ===
Contributors: barnz99
Tags: wp-mail, return-path, sender, phpmailer, outlook, email, headers, via, the actual sender of
Requires at least: 3.0.1
Tested up to: 6.3
Stable tag: 6.3
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Simple plugin that correctly sets the return-path header when using wp_mail.

Mitigates the "via" and "The actual sender of" Notification in Outlook and stops email going to spam/junk because of the mismatch between From and Return-Path headers.

== Description ==

This plugin sets the PHPMailer Sender (return-path) the same as the From address if it's not correctly set.

Mitigates the "via" and "The actual sender of" Notification in Outlook and stops email going to spam/junk because of the mismatch between From and Return-Path headers.

This usually occurs on shared hosting servers when local user / domain is used as default for the return-path header and Wordpress only sets the From header.

== Installation ==

1. Unzip all files to the `/wp-content/plugins/` directory
2. Log into Wordpress admin and activate the 'Latest Tweets' plugin through the 'Plugins' menu

== Changelog ==

= 1.1.1 =
* Tested with 6.3

= 1.1.0 =
* Tested with 5.6.1
* Fixed typo on filter_var
* Changed to a singleton class

= 1.0.3 =
* Tested with WordPress 4.9.4

= 1.0.2 =
* Now only sets the sender if it's not already set with a valid email address.

= 1.0.1 =
* Tested on 4.4 

= 1.0.0 =
* Inital Release

= 0.0.1 =
* Beta Release