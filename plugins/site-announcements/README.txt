=== Site Announcements ===
Contributors: wpnook
Donate link: https://codewrangler.io
Tags: announcements, news, users, messages
Requires at least: 4.0
Tested up to: 5.1
Stable tag: trunk
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Site Announcements allows you to broadcast site-wide messages to your visitors, as well as set custom parameters for the messages,
such as the background and text color, and how long a user-hidden announcement should be hidden from users.

== Description ==

Site Announcements allows you to broadcast site-wide messages to your visitors, as well as set custom parameters for the messages,
such as the background and text color, and how long a user-hidden announcement should be hidden from users.

By default, announcements will open in a slide-down modal (showing the announcement content). Alternatively, each announcement can be configured to point to an internal or external URL. Announcements with no URL and no content will simply display user-selected text, which can be handy for broadcasting things like coupon codes or important messages to users.

Plays well with the WordPress Admin Toolbar and is mobile-friendly. Has been tested with 100+ WordPress themes and should work well on all themes. If you have theme compatibility issues, please post a support thread or contact me.

The plugin's font sizes and families are inherited from the theme. If you wish to make adjustments to the announcement bar's font sizes or types you will need to add custom CSS to override your theme's styles.

Site Announcements uses animate.css for the modal transitions and JSCookie for setting a cookie if a user hides a modal.

== Installation ==

1. Upload `site-announcements` to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress

== Frequently Asked Questions ==

= If a User Hides a Specific Announcement, Will Other Announcements Be Hidden as Well? =

No, each announcement is hidden based on its ID specifically.

== Screenshots ==

1. Shows a sample closable announcement with a red-background and white text.
2. Shows the announcement creation panel, which lets you set a custom background, text color and other options.
3. Shows the slide-down modal displaying the announcement content if no URL is specified.

== Changelog ==

= 1.0.4 =
* Fixes color-picker issue

= 1.0.3 =
* Fixes custom text color issue when content is empty

= 1.0.2 =
* Prevent script enqueue if there are no announcements
* Removes erroneous admin output

= 1.0.1 =
* Update for plugin assets

= 1.0.0 =
* Initial release