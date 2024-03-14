=== Brozzme Multiple admin emails ===
Contributors: Benoti
Tags: admin, email, publish notification, notification, multiple, rereading, purposes, registration, sanitize, misprint, coworking
Donate link: https://brozzme.com/
Requires at least: 4.5
Tested up to: 6.0
Stable tag: 1.4
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Add more than one email address to a website admin notifications settings. Be informed of new articles or awaiting review of others to improve quality.

== Description ==
Add more than one admin email for notification such as new user registration. **Brozzme Multiple admin emails**, override sanitization of admin_email and new_admin_email to allow multiple admin email, separate with commas.
This function applies a limit, check for valid email using is_email( ) function (WordPress).
With this function, site notifications are sent to these admin emails.

Since version 1.2.0, new features and options appears.

* New notification emails are send when other admin, editor publish content.
* Email with link, edit link,
* On demand notification meta box on post & page to use new notifications.

Options

* Limit website admin_email up to 5 addresses.
* Send email when publishing,
* Post & page configuration
* Event configuration (work automatically with pending or publish)
* Notification On Demand mode,

Behaviours

* override sanitize_option_admin_email and sanitize_option_new_admin_email,
* custom sanitization with is_email() and trim, to prevent thoughtlessness typo,
* **remove additional address on desactivate and plugin suppress.**

Since 1.2.0

If publish event are enable

* send notifications, according to the settings, when a post or/and page are save as pending or publish.
* If set, On Demand, adds meta box to send notification on demand or reset notification status.
* The current author will not receive notifications, others admin_email will.
* When a post (or page) notifications has been already send, you will be able to send another notification on demand or reset status.
* Silent mode to hide the meta box for non admin_email when save_post hook is used (can fail with new WordPress editor)
* On demand mode, perfect to verify the publishing content, correct it to improve seo, misprint, graphical convention.
* new Brozzme admin menu to centralize Brozzme plugins.

Read the FAQ to get more tricks.

Link to [Brozzme](https://brozzme.com/)
Check all [Benoti / Brozzme plugins](https://profiles.wordpress.org/benoti#content-plugins) on WordPress.org


== Installation ==
1. Upload "multiple-admin-emails" folder to the "/wp-content/plugins/" directory.
2. Activate the plugin through the "Plugins" menu in WordPress.
3. Add your admin emails and configure notification in Brozzme/Notifications

== Frequently Asked Questions ==
= How should I write the emails ? =
Separate with commas. I.e : mail1@tld.com, mail2@tld.com, etc...

= Where should I write the emails ? =
As usual, in Settings/General

= I change the limit but all the emails remains in the settings panel =
You need to save once again to active this limit, emails are taken in the same order and cut from the end of the list. Be sure to place your most important admin first.

= I can find the Notification in the Settings panel =
Notifications settings move to Brozzme panel.

== Screenshots ==
1. Admin views screenshot-1.png.
2. Brozzme Multiple admin emails settings panel screenshot-2.png.
3. Brozzme Multiple admin emails settings panel in french screenshot-3.png
4. Post or page meta box when no notifications sent yet screenshot-4.png
5. Post or page meta box when a notification has been already sent screenshot-5.png


== Changelog ==
= 1.4 =
* bugfixes
= 1.0.0 =
* Initial release.
= 1.2.0 =
* Introduce Brozzme admin menu & Quality content notifications *
= 1.2.1 =
* solve bug with php 7.1 *