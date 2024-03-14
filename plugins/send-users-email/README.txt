=== Send Users Email ===
Contributors: metalfreek
Donate link: https://sendusersemail.com
Tags: email users, email subscribers, email system users, send email, email all users
Requires at least: 5.7
Tested up to: 6.4.1
Requires PHP: 7.4
Stable tag: 1.5.1
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Send Users Email provides a way to send email to all system users either by selecting individual users or user roles.

== Description ==

Send user email allows you to easily send bulk emails by selecting individual user(s) or send bulk messages using role(s).

This plugin has a very simple interface with necessary features so that you can just send simple emails without having to fiddle around with tons of settings.

This plugin uses `wp_mail` function to send emails. Any other E-Mail plugin that tap on `wp_mail` functions works with this plugin.

[PRO Version](https://sendusersemail.com/)

= Features =
- Send email to users in your site
- Send email by selecting individual users
- Send email by selecting roles
- Placeholder in email to personalize message
- Ability to add style to your email using CSS
- Ability to add social icon links to emails
- Logs error if any when attempting to send email
- Logs content of sent emails for 15 days

= Pro only Features =
- All feature of free version
- Email queue system (slowly send emails so that you stay within your email provider limit and improve delivery)
- Queue email scheduling (Add emails to queue to be sent at a later dates)
- User group (Create group and add users to them so that you can send email to these users)
- Email templates (Sending same text email over and over again? Save it once and reuse it with ease. Save your time)
- Email styles (Don't know how to style email? Pro version has well crafted email with various color schemes)
- Placeholder on email subject
- Ability to set default email style
- Ability to set if queue should be used by default
- Logs content of sent emails for 90 days (can be adjusted in settings)
- Clutter free UI

== Installation ==

This section describes how to install the plugin and get it working.

1. Upload the plugin files to the `/wp-content/plugins/send-users-email` directory, or upload plugin zip by going to Upload Plugin section at /wp-admin/plugin-install.php
2. Activate the plugin through the 'Plugins' screen in WordPress admin

== Frequently Asked Questions ==

= Can I select individual users? =

Absolutely. Go to `Email Users` page of plugin and select user you want to send email to.

= Can I choose multiple roles? =

Yes. You are able to choose one or many roles at a time and send email.

= When is email send? =

Emails are processed immediately and there is no delay. However, depending on your hosting or mail service provides, there might be slight delay in delivery. Pro version however has option to send emails via queue.

= I am using Gmail as email service provider (or any other provider) and user are not getting emails. =

This plugin only acts as a bridge between your site and email service provider. Its upto email provider to deliver or block sent emails. If your delivery is not consistent, please contact your email provider support or hosting to see if you are hitting their limit. Plugin does attempt to let you know when your email are not sent. If this happens, please check your logs or email service provider usage for any issues.

= I have many users in my system and many are not getting the emails? =

Since, processing is happening immediately, low `max execution time` in PHP setting might terminate the process. Try increasing the max execution time. For Pro version of the plugin, you can avoid this issue by adding your emails to queue so that they are sent gradually with help of cron.

= I have an issue/question/suggestion/request? =

Please post your issue/question/suggestion/request to support forum. I will try and address it as soon as possible.


= Is there a way to try out the plugin before I install it on my website? =

Absolutely. Try it out at [https://tastewp.org/plugins/send-users-email/](https://tastewp.org/plugins/send-users-email/). Please note that this service doesn't allow outgoing email so you will just be trying out the interface and general idea of the features.

== Screenshots ==

1. Admin dashboard providing basic overview of users in the system.
2. Send email to individual users
3. Send email by selecting roles

== Changelog ==

= 1.5.1 (2024-01-15) =
* Freemius SDK update


= 1.5.0 (2023-12-06) =
* Freemius SDK update
* Bug fixes


= 1.4.4 (2023-11-14) =
* Bug fixes
* WordPress version stability test
* Freemius SDK update

= 1.4.3 (2023-10-05) =
* Officially support PHP 8.0 (should work on higher version as well but not fully tested yet)


= 1.4.2 (2023-09-05) =
* Freemius SDK update
* WordPress compatibility check with version 6.3


= 1.4.1 (2023-07-05) =
* Freemius SDK update

= 1.4.0 (2023-06-16) =
* Added feature to log error if wp_mail fails to send email
* Added feature to log sent email of last 15 days
* Bug fix: Email content image alignment not working fixed
* Freemius SDK update


= 1.3.9 (2023-05-10) =
* Validation added to check if Email from/reply to email and name are set
* Max execution time warning relocated
* Bug fixes: Caption shortcode removed from mail content
* Freemius SDK update


= 1.3.8 (2023-04-23) =
* Cleanup user interface
* Max execution time warning added
* Freemius SDK update
* Minor bug fixes


= 1.3.7 (2023-04-21) =
* Freemius SDK update
* Minor bug fixes

= 1.3.6 (2023-04-15) =
* Freemius SDK update
* User Email page, add render slow warning if there are many users
* Minor bug fixes


= 1.3.5 (2023-03-01) =
* Added ability to hide table columns on user email page
* Minor bug fixes


= 1.3.4 (2023-02-06) =
* Minor bug fix


= 1.3.3 (2023-01-11) =
* Bug fix: Single and double quote escaping fix on email subject

= 1.3.2 (2023-01-06) =
* Bug fix: Paragraph break and line break issue fix removing excess spacing

= 1.3.1 (2022-12-25) =
* UX improvement to better report failed email send attempt
* Feature to add Social media link on email template
* Bug fix: New line to break tag addition


= 1.3.0 (2022-12-21) =
* UX improvement
* Minor bug fixes


= 1.2.1 (2022-12-10) =
* UX improvement for error/success message
* user_id placeholder added


= 1.2.0 (2022-12-06) =
* Pro Version release
* Freemius integration
* Minor bug fixes

= 1.1.2 (2022-10-26) =
* Settings page access bug fix and UX improvements

= 1.1.1 (2022-10-24) =
* Minor bug fix on roles capability feature

= 1.1.0 (2022-10-24) =
* Added support to select roles to use send users email

= 1.0.6 (2022-09-10) =
* Added HTML tag support in email footer

= 1.0.5 (2022-07-31) =
* Added username column to users display table

= 1.0.4 (2022-06-19) =
* Added filter to user selection with ID range

= 1.0.3 (2022-05-28) =
* Added ability for users to style email template
* minor bug fixes

= 1.0.2 (2022-02-12) =
* Username placeholder added to email template
* Email From/Reply-To settings added

= 1.0.1 (2021-11-17) =
* Settings bug fix and style changes

= 1.0.0 (2021-10-01) =
* Initial release

== Credits ==
* [unDraw](https://undraw.co/) - Illustrations
* [Bootstrap](https://getbootstrap.com/) - UI
* [DataTables](https://datatables.net/) - Tables
