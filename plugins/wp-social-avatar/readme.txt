=== WP Social Avatar ===
Contributors: marutim
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_xclick&business=marutimohanty89@gmail.com&item_name=WP%20Social%20Avatar
Tags: social avatar, avatar, gravatar, social
Requires at least: 3.3.1
Tested up to: 4.5
Stable tag: 1.5
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

This plugin gives the users the option to use their social profile picture as the WordPress Avatar

== Description ==

As the name suggests, this plugin allows you to use the social profile picture as your WordPress Avatar.
You can see more, on how to install and use the plugin here in this blog [WP Social Avatar](http://www.sourcexpress.com/wp-social-avatar/)

**Why this plugin?**

If you are me, who gets bore of using the same gravatar for few days or months then this plugin is totally for you.
This plugin provides option to the user to use Social media like Facebook and Google plus profile pictures as the gravatar instead of the one from en.gravatar.com.

**WP Social Avatar Settings:**

After activating the plugin admin will see **WP Avatar**, under Users menu. Here the admin can set the minimum role/capabilities required to avail this feature.

The allowed users will see the available options in their **Your Profile** sub menu under Users menu.

For the first version I only have two social options available, Facebook and Google plus. You need to add either the Facebook user ID(numeric) or Google plus id in the corresponding fields. [You can find your facebook user id here](http://findmyfacebookid.com/)

Once done with the above, check the social profile picture you want to use as the gravatar and click Update Profile and you are done.

After saving the profile you can see all the gravatars in the site has been replaced by the respective social media profile picture.

With this you will get rid of the boredom of looking at the same gravatar all the time. With the change in the respective social media profile picture, the gravatar of your site will also change.

With Version 1.4 Cache functionality is implemented for Google Plus avatar, this optimizes the functionality. I have added a clear cache button for Google plus, anytime you upload a new profile picture in your Google Plus account click the "clear cache" button and you will get the latest image else the code will make a request and pull the latest image after every 48 hours. This has been done to make the Google Plus functionality faster.


== Installation ==
1. Upload "**wp-social-avatar**" folder to the "wp-content/**plugins**" directory.
2. Activate the plugin through the "**Plugins**" menu in WordPress.

== Frequently Asked Questions ==
No FAQS yet

== Screenshots ==
1. Admin Settings screen
2. Profile Options screen

== Changelog ==
= 1.5 =
* Made changes due to change in Facebook APIs. Now you need to use facebook user id instead of facebook handle/username.

= 1.4.1 =
* Introduced wp_social_avatar_heading filter

= 1.4 =
* Opitimized the Google Plus API call
* Implemented cache functionality to improve the Google Plus Avatar functionality.

= 1.3 =
* Opitimized the plugin

= 1.2 =
* Now Users can either use the Google plus user id or the new Google Plus handle for WP Social Avatar Google Plus option. 

= 1.1 =
* Fixed the Google Plus issue.

= 1.0 =
* First release.

== Upgrade Notice ==
1.5
