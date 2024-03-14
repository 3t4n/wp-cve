=== Paystack MemberPress ===
Contributors: paystack, kaneahabagale
Tags: paystack, billing, subscription, payment, memberpress,
Requires at least: 5.1
Tested up to: 6.3
Requires PHP: 7.2 and higher
Stable tag: 1.3.3
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

== Description ==
 
A Memberpress Payment Gateway integration with Paystack for membership subscriptions.

This addon helps admins and webmasters using Memberpress to accept subscription payment via Paystack gateway. This addon has all the settings for development as well as for production usage. Its easy to install, set up and effective. 
A few notes about the sections above:
 
== Installation ==
 
Login to your wordpress site as an “admin user” and follow the steps below.

1) First on your wordpress admin dashboard and install this plugin and activate it.

2) Go to MemberPress Options > Payments Tab

3) Click the plus button and select the gateway "Paystack".

4) Go to your Paystack dashboard and navigate to the 'Developer' settings section to obtain the necessary keys: test secret key, test public key, live secret key, and/or live public key.

5) Copy and paste the keys into your MemberPress settings.

5) Copy your "Paystack Webhook URL" from your MemberPress setup page.

6) Navigate back to your "Developers" settings in Paystack dashboard and update the webhook field.

7) Save all settings.

== Detailed List of Gateway Settings ==
 
 Below you will find explanations for every setting that you can find for your Paystack Gateway as a reference for both during and after you have setup your gateway:

* Name - The name users will see when choosing this gateway option during registration.
Gateway - This should be set to "Paystack" and will not show up on the frontend.

* ID - ID of your gateway used in database and to be entered if importing subscriptions for this gateway as outlined here.

* Show Payment Label - When checked or unchecked, this will show or hide the Name you entered for this gateway option.

* Show Payment Icon - When checked or unchecked, this will show or hide the Paystack Credit Card symbols for this gateway option.

* Show Payment Description - When checked or unchecked, this will show or hide the description for this gateway option.

* Test/Live Secret Key - This is where you will enter your Paystack Secret Key for either Test or Live Mode. Before entering your Test Secret Key, be sure to check the Test Mode box in your Paystack gateway in MemberPress. 

* Test/Live Public Key - This is where you will enter your Paystack Publishable Key for either Test or Live Mode. Before entering your Test Publishable Key, be sure to check the Test Mode box in your Paystack gateway in MemberPress. 

* Test Mode - Check this box if you would like to use Paystack in Test Mode for Testing. 

* Force SSL - Use this option if your site has the option of being viewed in http and https. This will automatically switch to https when a user is going to pay so that the transaction is secure.

* Send Debug Emails - Only enable this option if you are having issues with your Paystack gateway and know how to interpret debug emails or have been told to enable them by our support team. Enabling this option will send out about 5 separate debug emails to your email found in your General WordPress Settings.

* Paystack Webhook URL - The Webhook URL is used to communicate data between your Paystack account and MemberPress. It must be entered correctly for either Test or Live Modes to work correctly. Use the clipboard icon to copy over your Webhook URL. Then go to your Paystack Dashboard > Settings > API Keys & Webhooks.
 
== Capabilities of the Paystack Integration ==
 
* Process One-Time Payments
* Process Recurring Payments
* Process Refunds
* Cancel Recurring Payments
* Change Credit Card for Recurring Subscription
* Pause Recurring Payments
* Resume Recurring Payments
* Process Free/Paid Trial Periods
* Credit Card Expiring Reminders Supported

== Frequently Asked Questions ==


== Contribution ==

Here you can browse the source, look at open issues and keep track of development. 
 
== Changelog ==

= 1.3.3 =
*  Fixed incorrect date set on expiredOn parameter 

= 1.3.2 - Septmber 26, 2023 =
*  Compatibility with WordPress 6.3 and PHP 8.2.0

= 1.3.1 - May 9, 2022 =
* 	Fix: Add support for weekly, quarterly, and yearly plans

== Upgrade Notice ==

= 1.3.2 =
*  Compatibility with WordPress 6.3 and PHP 8.2.0

= 1.3.3 =
*  Fixed incorrect date set on expiredOn parameter 
