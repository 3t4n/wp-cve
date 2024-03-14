=== Email notification on admin login ===
Contributors: Azumi93
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_donations&business=stefany%2enewman93%40gmail%2ecom&lc=US&item_name=Stefany%20Web%20Design&no_note=0&currency_code=USD&bn=PP%2dDonationsBF%3abtn_donateCC_LG%2egif%3aNonHostedGuest
Tags: email notify on admin login, admin login notification, email notification
Requires at least: 3.0.1
Tested up to: 4.9.8
Stable tag: 1.1
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Sends an email to a pointed email address when an admin user logs in

== Description ==

This plugin will send you an email every time a user logs in your website as an admin.
It displays the date the user logged in and their IP address.


== Premium WordPress Login Notifications ==
The wait is over and the premium version of the plugin is here.
You can buy it [from here](https://premiumwordpressloginnotifications.com/)

Features of the premium plugin are:
- Choose which roles to track (admin, author, contributor, etc)
- Stores user activity in a log and allows you to download it in CSV file
- Choose which IPs to ignore (good for development)
- Send emails at multiple e-mail accounts
- Lifetime free updates
- Add the username of the logged in user in the emails/logs

== Installation ==

How to install this plugin?

1. Upload `email-notification-on-admin-login` directory to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress

== Frequently Asked Questions ==

= I am not getting an email when a user logs in as an admin =

That's because the default email address is the one you entered when you installed your WordPress
and you no longer use the email in question, or at least not too often.

= Can I change the email address the notifications are being sent to? =
Go to `wp-content/plugins/email-admin-notify/config.php`
Search for `define("ADMIN_EMAIL", "$admin_email");` 
and change `"$admin_email"` to whatever email you want.
For example `define("ADMIN_EMAIL", "MY_AMAZING_EMAIL@AMAZING.COM");`
That way all notifications will be sent to `"MY_AMAZING_EMAIL@AMAZING.COM"`
instead of the email you installed your WordPress with. 

= This is an awesome plugin, how can I thank Stefany? =

I will be very grateful if you [donate](https://www.paypal.com/cgi-bin/webscr?cmd=_donations&business=stefany%2enewman93%40gmail%2ecom&lc=US&item_name=Stefany%20Web%20Design&no_note=0&currency_code=USD&bn=PP%2dDonationsBF%3abtn_donateCC_LG%2egif%3aNonHostedGuest)

== Screenshots == 
1. Example of a plugin generated email

== Changelog ==

= 1.1 =
* Tested it with v4.9.8
* Changed my name (got married)
* Changed my website
* Changed PayPal donate link.

= 1.0 =
