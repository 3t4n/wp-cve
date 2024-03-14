=== WPFront Notification Bar ===
Contributors: syammohanm
Donate link: http://wpfront.com/donate/
Tags: notification bar, wordpress notification bar, top bar, bottom bar, notification, bar, quick bar, fixed bar, sticky bar, message bar, message, floating bar, notice, sticky header, special offer, discount offer, offer, important, attention bar, highlight bar, popup bar, hellobar, heads up, heads up bar, headsup, headsupbar, popup, Toolbar
Requires at least: 5.0
Tested up to: 6.4
Requires PHP: 7.0
Stable tag: 3.4
License: GPLv3
License URI: http://www.gnu.org/licenses/gpl-3.0.html

Easily lets you create a bar on top or bottom to display a notification.

== Description ==
Want to display a notification about a promotion or a news? WPFront Notification Bar plugin lets you do that easily. 

[Upgrade to PRO](http://wpfront.com/notification-bar-pro/) to create multiple bars and to use advanced editor.

### Features
* Display a **message** with a **button** (optional).
* Processes **shortcodes**.
* Button will **open a URL** or **execute JavaScript**.
* **Position** the bar on **top** or **bottom**.
* Can be **fixed at position** (Sticky Bar).
* **Display on Scroll** option.
* Set **any height** you want.
* Set the number of **seconds before** the **bar appears**.
* Display a **close button** for the visitor.
* Set the number of **seconds before auto close**.
* **Colors** are fully **customizable**.
* Display a **Reopen Button**.
* **Select the pages/posts** you want to display the notification.
* **Select the user roles** you want to display the notification.
* Set **Start** and **End dates**.
* Hide in **Small Devices**.

Visit [WPFront Notification Bar Troubleshooting](https://wpfront.com/wordpress-plugins/notification-bar-plugin/wpfront-notification-bar-troubleshooting/) page for troubleshooting steps.

Visit [WPFront Notification Bar Settings](http://wpfront.com/notification-bar-plugin-settings/) page for detailed option descriptions.

== Installation ==

1. Click Plugins/Add New from the WordPress admin panel
1. Search for "WPFront Notification Bar" and install

-or-

1. Download the .zip package
1. Unzip into the subdirectory 'wpfront-notification-bar' within your local WordPress plugins directory
1. Refresh plugin page and activate plugin
1. Configure plugin using *settings* link under plugin name or by going to WPFront/Notification Bar

== Frequently Asked Questions ==

= WPFront Notification Bar and GDPR compliance? =

This plugin doesn’t collect any personal information. For more information please visit [GDPR compliance](https://wpfront.com/wpfront-and-gdpr-compliance/).

= I don’t want the plugin to be displayed on “wp-admin”, what should I do? =

Notification bar doesn’t display on the wp-admin pages, except on the notification bar settings page. On the settings page it acts as a preview so that you can see the changes you make.

= How do I stop the bar from displaying for logged in users? =

The new version(1.3) allows you to filter the bar based on user roles. In this case you need to select the “Guest users” option.

== Screenshots ==
 
1. Settings page.

== Changelog ==

= 3.4 =
* Bug fixes.
* Compatibility fixes.
* XSS fixes.

= 3.3.2 =
* Bug fixes.
* PHP & WP compatibility fixes.

= 3.3.1 =
* Bug fixes.

= 3.3.0 =
* New schedules UI.
* WPML compatibility fixes.
* Plugin conflict fixes.
* Bug fixes.

= 3.2.1 =
* Copy bars.

= 3.2.0 =
* Recurring schedule(PRO).
* New UI.
* Bug fixes.

= 3.1.0 =
* Max views configuration.
* Enqueue CSS in footer.
* Reopen button offset.
* Bug fixes.

= 3.0.0 =
* [Create multiple bars](http://wpfront.com/notification-bar-pro/)(PRO).
* TinyMCE editor.
* Custom capabilities(PRO).

= 2.3.0 =
* Custom capability bug fix.
* Keep closed bug fix.

= 2.2.0 =
* You can now change the capability checked by Notification Bar.
* Use **WPFRONT_NOTIFICATION_BAR_EDIT_CAPABILITY** constant to set your custom capability or use **wpfront_notification_bar_edit_capability** filter.
* You can now enable/disable notification bar based on any condition using **wpfront_notification_bar_enabled** filter.

= 2.1.0 =
* More XSS fixes. Please read [this link](https://wordpress.org/support/topic/v2-contain-breaking-changes/) before upgrading.
* Use **WPFRONT_NOTIFICATION_BAR_UNFILTERED_HTML** constant to get v1.x behavior on message & button text.
* Add **define('WPFRONT_NOTIFICATION_BAR_UNFILTERED_HTML', true);** to your wp-config.php to disable message sanitization.
* Another way is to use **wpfront_notification_bar_message_allow_unfiltered_html** and **wpfront_notification_bar_button_text_allow_unfiltered_html** filters.
* WPML compatibility fix. Use **WPFRONT_NOTIFICATION_BAR_LANG_DOMAIN** constant to change language domain.

= 2.0.0 =
* Breaking change added. Please read [this link](https://wordpress.org/support/topic/v2-contain-breaking-changes/) before upgrading.
* Breaking change: Message text no longer allow script tags. 
* If you have script tags in your message text, use 'wpfront_notification_bar_message' filter to set your message.
* This change is needed as per 'WordPress Plugin Review Team'.
* More XSS fixes.

= 1.9.2 =
* XSS fix on the settings page.

= 1.9.1 =
* Compatibility fix.

= 1.9.0 =
* Reopen button image is now configurable.
* Add dynamic CSS through URL.
* Compatibility fixes.
* Bug fixes.
* PHP 8.0 fixes.
* SiteGround conflict fix.

= 1.8.1 =
* Description correction.

= 1.8 =
* Preview mode.
* Debug mode.
* Hide in small devices and windows.
* Change cookie names.
* Edit include/exclude post IDs manually.
* Edit colors manually.
* More rel attributes. Thanks to jetxpert.
* Accessibility and compatibility fixes.
* Filters 'wpfront_notification_bar_message' and 'wpfront_notification_bar_button_text' added.
* Bug fixes.

= 1.7.1 =
* Processes shortcode in button text.
* Notification bar menu is now under 'Settings' menu.
* PHP 7.2 compatibility fixes.
* Bug fixes.

= 1.7 =
* Start and End times.

= 1.6 =
* Processes shortcodes.
* Nofollow link option.

= 1.5.2 =
* WP eMember integration.

= 1.4.2 =
* Bug fixes.
* Serbo-Croatian translation. Thanks to Borisa Djuraskovic.

= 1.4.1 =
* Bug fixes.
* Hungarian translation. Thanks to Botfai Tibor.

= 1.4 =
* Display on Scroll option added.
* Date filters added.

= 1.3 =
* User roles filter added.
* New menu structure.

= 1.2.1 =
* Fixed an issue with mod_security.
* German language added. Thanks to Anders Lind.

= 1.2 =
* Keep closed for days.
* Change color of close button.
* Custom CSS option added.

= 1.1 =
* Filter pages option added.
* Reopen button added.
* Keep closed option added.
* Position offset added.
* WordPress 3.8 ready.

= 1.0.1 =
* A couple of bug fixes.

= 1.0 =
* Initial release

== Upgrade Notice ==

= 3.4 =
* Compatibility & security fixes.

= 3.3.2 =
* Bug fixes.

= 3.3.1 =
* Bug fixes.

= 3.3.0 =
* New features, compatibility & bug fixes.

= 3.2.0 =
* Bug fixes.

= 3.1.0 =
* Bug fixes.

= 3.0.0 =
* TinyMCE editor.

= 2.3.0 =
* Bug fixes.

= 2.2.0 =
* New filters.

= 2.1.0 =
* XSS and compatibility fixes.

= 2.0.0 =
* Please read change log before upgrading.

= 1.9.2 =
* XSS fix on the settings page.

= 1.9.1 =
* Compatibility fix.

= 1.9.0 =
* Compatibility fixes.

= 1.8 =
* New features added.

= 1.7.1 =
* Bug fixes.

= 1.7 =
* Start and End times.

= 1.6 =
* Processes shortcodes.

= 1.5.2 =
* WP eMember integration.

= 1.4.2 =
* Bug fixes.

= 1.4.1 =
* A couple of bug fixes.

= 1.4 =
* Now you can set it to display on scroll.
* Date filters available now.

= 1.3 =
* Now you can filter by user roles.

= 1.2.1 =
* Fixed an issue with mod_security on "cookie" rule.

= 1.2 =
* Now you can keep it closed for days.
* Change the color of close button.

= 1.1 =
* This version is WordPress 3.8 ready.
* Now you can filter the pages.
* Option to keep the bar closed.

= 1.0.1 =
* Fixed an issue with CSS conflicting with some themes

= 1.0 =
* Initial release
