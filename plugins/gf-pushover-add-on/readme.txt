=== Gravity Forms Pushover Add-On ===
Contributors: wp2pgpmail
Donate link: https://wp2pgpmail.com
Tags: Gravity Forms, Pushover, notification, push notification, push
Requires at least: 2.9.2
Tested up to: 6.2
Requires PHP: 5.6
Stable tag: 1.06
License: GPLv2
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Get Gravity Forms submissions as instant push notifications with Pushover on your Android, iPhone, iPad, and Desktop.

== Description ==

Gravity Pushover plugin allows you to push instant notifications to your smartphone when a new submission is created with Gravity Forms. To achieve it, you need to have:

* [Gravity Forms](http://bit.ly/GravityFormsWordPress) plugin
* A [Pushover](https://pushover.net/) account
* Gravity Pushover plugin

To sum up, Gravity Pushover will create the link between [Gravity Forms](http://bit.ly/GravityFormsWordPress) and [Pushover](https://pushover.net/).

Pushover is a service to get real-time notifications on your Android, iPhone, iPad and Desktop. It runs as an application and it's very cheap. After creating an account at Pushover, you will get a Pushover User Key. Notifications from Gravity Forms will be sent to this Pushover User Key.

With Gravity Pushover plugin, it's possible to:

* Send e-mails and/or Pushover notifications to recipients
* Send only Pushover notifications for specific forms
* Send notifications to several recipients
* Send notifications to the same recipient on several devices

You can customize to whole process to exactly what you need. Everything is done through the Gravity Forms interface. All you need is a Pushover User Key for each recipient.

== Installation ==

First, you must have:

* An existing form on your website, made with [Gravity Forms](http://bit.ly/GravityFormsWordPress)
* An existing [Pushover](https://pushover.net/) account

That's all you need to start.

Then:

1. Upload and extract the content of 'gf-pushover-add-on.zip' to the '/wp-content/plugins/' directory
1. Activate the plugin through the 'Plugins' menu in WordPress
1. Go to the Notifications tab of one of your existing forms
1. Create a new notification
1. Choose **Pushover** as Email Service
1. Then, fill the information with you Pushover User Key, the subject (it will be the title of the notification) and the message (it will be the content of the notification)
1. Save the Notification

That's it!

Now, every notification sent by Gravity Forms will be pushed to your Pushover account, on your mobile phone.

Explanations with screenshots are available at [https://wp2pgpmail.com/gravity-pushover/](https://wp2pgpmail.com/gravity-pushover/).

== Frequently Asked Questions ==

= What are the requirements? =
To run the plugin, you need:

* a website with WordPress and [Gravity Forms](http://bit.ly/GravityFormsWordPress)
* a [Pushover](https://pushover.net/) account and the Pushover app installed on one of your devices.

= Is it free? =
The Gravity Pushover plugin is free, but you need to have [Gravity Forms](http://bit.ly/GravityFormsWordPress), and a [Pushover](https://pushover.net/) account as a requirement. Pushover costs only $4,99 once.

= Can I use my own Pushover application? =
Yes, you can use your own Pushover application if you have one. You will need to enter the Pushover application token in the setting page.

== Screenshots ==

Screenshots with explanation are available at [https://wp2pgpmail.com/gravity-pushover/](https://wp2pgpmail.com/gravity-pushover/).

== Changelog ==

= 1.06 =
* Fixing is_plugin_active() function call

= 1.05 =
* Fixing new notification creation

= 1.04 =
* Adding the new filter gform_notification_settings_fields introduced with Gravity Forms v2.5

= 1.03 =
* Testing WordPress 5.3

= 1.02 =
* Testing WordPress 5.0

= 1.01 =
* Initial import
