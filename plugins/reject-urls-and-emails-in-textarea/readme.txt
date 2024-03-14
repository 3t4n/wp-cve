=== Reject Urls And Emails In Textarea For (Gravity Forms) ===
Contributors: InternalError503
Tags: gravity forms, gravity, url detection, email detection, validation
Requires at least: 3.0
Tested up to: 4.4.1
Stable tag: 1.1
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Prevent URLS & Emails in gravity forms textarea fields.

== Description ==

Install this plugin into any WordPress system with a working Gravity Forms plugin and all known `[URL]` or `[EMAIL]` will be rejected and fail validation. 
This checks the field type `textarea` and does not require any configuration or special settings. 
Its global, if its turned on, then its working.

This WordPress plugin is brought to you by [toady](https://github.com/InternalError503) from [8pecxstudios.com](https://8pecxstudios.com)

== Installation ==

1. Upload `reject-urls-and-emails-in-textarea.php` to the `/wp-content/plugins/` directory
2. Activate the plugin through the `Plugins` menu in WordPress

== Frequently Asked Questions ==


= Question: I can not find any settings or options! =
Answer: Sorry there are none available!

= Question: How do I enable it on my site? =
Answer: If the plugins is set to active then its working.

= Question: Where did you get the regex filter? =
Answer: Thanks to [lakatos-gyula](http://stackoverflow.com/users/1420715/lakatos-gyula) for the [filter](http://stackoverflow.com/a/16481681 "stackoverflow.com").

= Question: Do you have a github? =
Answer: Yes, Here is the [repository](https://github.com/InternalError503/reject-urls-and-emails-in-textarea "Github repo") for this plugin

== Screenshots ==
Answer: Since there is no GUI for it this is N/A.

== Upgrade Notice ==
N/A

== Changelog ==

= 1.1 =
* Fixed: Die if accessed directly.
* Removed: Old filter comment.

= 1.0 =
* Init repo and project.
* Huston we have a problem urls and emails are detected.
