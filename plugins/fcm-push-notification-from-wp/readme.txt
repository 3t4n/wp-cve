=== FCM Push Notification from WP ===
Contributors: dprogrammer
Donate link: https://www.buymeacoffee.com/dprogrammer
Tags: fcm, firebase, push, notification, android, ios, flutter
Requires at least: 4.6
Tested up to: 5.8.2
Stable tag: 1.0
Requires PHP: 5.6.20
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Text Domain: fcm-push-notification-from-wp
Domain Path: /languages

Notify your users using Firebase Cloud Messaging (FCM) when content is published or updated.

== Description ==

Notifications for posts, pages and custom post types.

Works with scheduled posts.

Send notifications to users of your app from your website using Google's service, Firebase Push Notification.

The notification sent includes the block with the data message to be handled by the application, even when it is in the background.

Configure the plugin to start sending notifications.

Send custom field values ​​in the notification, in the data option.

Send a notification when you post news or update your content. When editing, the option is deselected to send you to accidentally send a new notification. Check if you want to send a new notification when editing.

Compatible with apps developed with the SDK Flutter.

You need to register users on the same topic (fcm) that was informed in the plugin configuration. This plugin is not intended for sending notifications to websites.

Support my work
<https://www.buymeacoffee.com/dprogrammer>

== Installation ==

1. Upload the plugin files to the `/wp-content/plugins/fcm-push-notification-from-wp` directory, or install the plugin through the WordPress plugins screen directly.
2. Activate the plugin through the 'Plugins' screen in WordPress.
3. Use the Settings->FCM Push Notification from WordPress screen to configure the plugin.
4. In the FCM Options put the FCM API Key and put the topic name registered in your app.
5. Optionally put the image url to display in the notification.

== Frequently Asked Questions ==

= Does it work with scheduled posts? =

Yes. When a post changes the status from scheduled to published, a notification is sent. If the option was checked when saving the post.

= Can I send to one user only? =

No. You can only send to the topic informed in the plugin configuration.

All users receive notification.

= Can I disable sending on the publication screen? =

Yes. Uncheck the checkbox to not send a notification.

= Can I send to my site user? =

No. sends only to users who are using your android/ios app.

== Screenshots ==

1. Plugin settings screen.
2. Sending from a post.
3. Sending from a custom post type.
4. Sending from a page.
5. Sending from a scheduled post.
6. Notification and data message fields.
7. Test performed using WordPress 5.8.1. Opening the notification within the app.
8. Test performed using WordPress 5.8.1. Notification being displayed when the app is closed.

== Changelog ==

= 1.0.0 =
* First version.

= 1.1.0 =
* Bug fixes.

= 1.2.0 =
* Bring the option unchecked when editing a post.

= 1.3.0 =
* Sending notification for scheduled posts.
* Bug fixes.

= 1.4.0 =
* Bug fixes. Two notifications were sent when sending.

= 1.5.0 =
* Bug fixes. this plugin was not sending the permalink when editing a post. Solution sent by @alchmi. Thanks.

= 1.6.0 =
* Included routine to remove visual composer tags from page and post text.

= 1.7.0 =
* Included the Sound field in the configuration. If empty, the name of the sound will be "default".

= 1.8.0 =
* Bug fixes.

= 1.9.0 =
* Option to take values ​​from custom fields and include in data area.
* Fixed text notifications display with '.
* Fixed notification display when using Divi theme.