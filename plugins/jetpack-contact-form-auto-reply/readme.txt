=== Jetpack Contact Form Auto Reply ===
Contributors: hlashbrooke
Donate link: http://www.hughlashbrooke.com/donate
Tags: jetpack, contact, form, auto reply, email
Requires at least: 4.0
Tested up to: 5.0
Stable tag: 1.1
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Send an automatic reply to anyone who fills in your Jetpack contact form

== Description ==

[Jetpack](http://jetpack.me/) supplies you with a very easy to use contact form for your WordPress site. This plugin enhances that contact form by allowing you to send a custom automatic reply to anyone who fills in the form.

**Plugin features**

- Craft your custom reply message using the WordPress WYSIWYG editor
- Set a unique from name and address for auto replies
- Include user info in auto reply (name, email address, message content, etc.)

Want to contribute? [Fork the GitHub repository](https://github.com/hlashbrooke/Jetpack-Contact-Form-Auto-Reply).

== Installation ==

Installing "Jetpack Contact Form Auto Reply" can be done either by searching for "Jetpack Contact Form Auto Reply" via the "Plugins > Add New" screen in your WordPress dashboard, or by using the following steps:

1. Download the plugin via WordPress.org
1. Upload the ZIP file through the 'Plugins > Add New > Upload' screen in your WordPress dashboard
1. Activate the plugin through the 'Plugins' menu in WordPress

== Frequently Asked Questions ==

= The automatic reply isn't sending! Why doesn't this plugin work? =

First make sure that you have set up your auto reply to work with all the settings active (Jetpack > Auto Reply). If all of that is setup correctly then the problem will either be that your server is not allowing mails to be sent, or the emails are being sent, but being marked as spam.

= What if I don't want to show my email address to everyone who contacts me? =

You can set a custom from name and address for your automatic reply, so you can use any email address (real or fake) that you like.

= How do I include the sender's name in the auto reply email subject? =

You can include any of the contact form fields in the subject by adding the field label like this: `{Field label}` - e.g. `{Name}`. This is case-sensitive.

= How do I include the sender's message in the auto reply? =

Just like with the subject, you can include any of the contact form fields in the auto reply email by adding the field label like this: `{Field label}` - e.g. `{Comment}`. This is case-sensitive.

== Changelog ==

= 1.1 =
* 2014-12-18
* [NEW] Adding option to not send auto reply when email is marked as spam
* [NEW] Adding filters to all email parameters
* [NEW] Adding ability to include original message data in auto reply content and subject
* [TWEAK] Improving description for email field option

= 1.0 =
* 2014-12-07
* Initial release #boom

== Upgrade Notice ==

= 1.1=
Adding option to not send auto reply when email is marked as spam, filters to all email parameters and ability to include original message data in auto reply content and subject.
