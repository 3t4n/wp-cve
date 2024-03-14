=== Better WishList API ===
Contributors: rickonline_nl
Donate link: http://bureauram.nl
Tags: WishList Member, Autorespond, API
Requires at least: 4.0
Tested up to: 6.2
Stable tag: 1.1.2
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

A better version of the WishList Member API. Created to make the connection to external services like ActiveCampaign and Autorespond a lot easier. Also gives option to send email notifications after succesfully adding an user through the API.


== Description ==

A better version of the WishList Member API. Created to make the connection to external services like ActiveCampaign and Autorespond a lot easier. Also gives option to send email notifications after succesfully adding an user through the API.

This version is currently only available in the Dutch language. A translation to English is in the works.

With this plugin you have the option to:

* Force the API to check if the user already exists. With the original Simple WLM API in combination with an external service, the user is often not recognized as existing user. As a result, the Autoresponder tries to add a new user, which will fail. Better WishList API will check if the user exists, and, if so, overrules the Autoresponder's request to create a new user. Instead, it will add the level to the existing user.
* Get notifications by email of added users and levels. This way you don't have to check if a transaction completed succesfully.
* Also register your user's first name and last name. The original Simple WLM API is not able to handle the user's name
* Log requests and see the results

This plugin also gives you detailed instructions how to use the API in conjunction with Autorespond en ActiveCampaign.

== Installation ==

1. Unzip the plugin file
2. Upload the folder `better-wlm-api` to the `/wp-content/plugins/` directory or download it from the Wordpress Plugin Directory
3. Activate the plugin through the 'Plugins' menu in WordPress
4. Review and, if necessary, change settings under Settings > Better WLM API
5. Make sure that the plugin 'simple-wlm-api' is either deactivated or removed

== Frequently Asked Questions ==

= The plugin doesn't seem to do anything =

Make sure that the Simple WLM API plugin is either deactivated or removed. Better WishList API will not perform action if Simple WLM API is also active, to prevent that statements are double executed.

Autorespond.nl: Please make sure that you use 'WishListSimpleApiAction' as the external system in the listmanager. 

If the plugin still doesn't work, download the log file in the Better WishList API screen to see what is going wrong. Feel free to contact me if you can't find what the issue is.

= I have a question =

Mail me at rick@bureauram.nl

== Screenshots ==

1. Settings page

2. Instructions and helpers to connect Better WishList API to Autorespond or through Webhooks

3. Plugin info

== Changelog ==

= 0.5 =
* Release

= 0.6 =
* Added logging

= 0.6.1 =
* Fixed typo

= 0.6.2 =
* Default value of option to send confirmation emails set to 'no'

= 0.6.3 =
* Added option to include password in confirmation mail

= 0.7.0 =
* Added page to view log

= 0.7.1 =
* Added user_name as possible value for remove_member

= 0.7.2 =
* Changed the way Better Wishlist API checks wether or not WishList Member is installed.

= 0.7.3 =
* Fully checked for WordPress 4.9

= 0.8.0 =
* Added possibility to add multiple WishList Levels in one request. Seperate your levels with a ',' or a ';'.
* Fixed some typos in log messages

= 0.8.1 =
* Fixed typo

= 0.8.2 =
* Fixed for PHP 7.2

= 0.9.0 =
* Added instructions on how to make connections

= 0.9.0.1 =
* Typo

= 0.9.0.2 =
* Typo

= 0.9.0.3 =
* Added instructions dialog how to remove a member from a level from ActiveCampaign.

= 0.9.0.4 =
* Typo

= 0.9.0.5 =
* Typo

= 0.9.1 =
* Ready for WordPress 5.4

= 1.0.1 =
* Added support for cancel and uncancel
* Added Remove, Cancel and Uncancel to tab 'Connections'
* Reworked Log section

= 1.1.0 =
* Added option to reset registration date to today if a user is added to a level to which they are already a member.

= 1.1.1 =
* Changed WishList Member method IsSequential to is_sequential
* Better WishList API now also logs to Simple History, if this logging plugin is installed on your website.

= 1.1.2 =
* Check for WordPress 6.2
* Improved code readability

== Upgrade Notice ==

= 1.1.2 =
* Check for WordPress 6.2
* Improved code readability


