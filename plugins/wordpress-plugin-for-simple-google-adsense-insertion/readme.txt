=== WP Simple Adsense Insertion ===
Contributors: Tips and Tricks HQ, Ruhul Amin
Donate link: https://www.tipsandtricks-hq.com/
Tags: Google Adsense, google, plugin, adsense, insert adsense, adsense ad, ad code, insert ad, ads, adsense plugin, advertising
Requires at least: 5.5
Tested up to: 6.4
Stable tag: 2.1
License: GPLv2 or later

Easy to use Wordpress plugin to insert Google Adsense to your posts, pages and sidebar.

== Description ==

Use this plugin to quickly and easily insert Google Adsense to your posts, pages and sidebar by using a shortcode or calling the php function from your theme's template file.

There are many plugins and services which can add Google Adsense to your WordPress site. However I found that even though something like Adsense Manager or Adsense Deluxe provides a lot of customizable options, it can be overwhelming and isn't really simple enough for people who are new to WordPress.

I found that most of the time I only use two or three types of Google Adsense units in various places and posts throughout my sites. 

That's why I wrote my own Simple Adsense Insertion Plugin for WordPress, to focus on having 1-3 Google Adsense codes saved and use them where ever I want to on my site by inserting a simple shortcode text to my posts, pages and sidebar.

This plugin can also be used to automatically insert in-article ad code. If specified, the ad code is inserted after the 2nd paragraph of every posts.

It also has the ability to automatically insert adsense code at the end of every article.

You can use this plugin to store any ad code too (it doesn't have to be just adsense code).

For information and updates, please visit the [simple Google Adsense plugin page](https://www.tipsandtricks-hq.com/wordpress-plugin-for-simple-google-adsense-insertion-170)

= Usage: =

There are two ways you can use this plugin:

Use the shortcodes: 

* [wp_ad_camp_1]
* [wp_ad_camp_2]
* [wp_ad_camp_3]
* [wp_ad_camp_4]
* [wp_ad_camp_5]

Call the function from template files:

* &lt;?php echo show_ad_camp_1(); ?&gt;
* &lt;?php echo show_ad_camp_2(); ?&gt;
* &lt;?php echo show_ad_camp_3(); ?&gt;
* &lt;?php echo show_ad_camp_4(); ?&gt;
* &lt;?php echo show_ad_camp_5(); ?&gt;

== Installation ==

1. Unzip and Upload the folder 'WP-Simple-Adsense-Insertion' to the '/wp-content/plugins/' directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Go to Settings and configure the options eg. Copy and paste the Google Adsense code that you want to use.
4. Add the trigger text [wp_ad_camp_1] to a post or page where you want it to appear.

== Frequently Asked Questions ==

= Does this plugin allow complex adsense management? =
No.

= Can I automatically insert adsense code at the end of every article? 
Yes.

= Can I automatically insert adsense inside every article?
Yes. You can automatically insert after after the 2nd paragraph of every article.

= I am updating an older version of the plugin.  Will it still work? =

The method for displaying ads changed in version 1.2.  The old style will continue to work in this version, but it highly suggested that you update all the adcode in your content to the new method.

An easy way to do this would be to install the find/replace plugin (https://wordpress.org/extend/plugins/search-and-replace/) and update all your old tags. Example: Find <!-- wp_ad_camp_1 --> and replace it with [wp_ad_camp_1].

== Screenshots ==  

1. Check out this Plugin in action at https://www.tipsandtricks-hq.com

== Upgrade Notice ==
None

== Changelog ==

= 2.1 =
* Added nonce check in the admin settings menu update action.

= 2.0 =
* Added a new option to automatically insert adsense code at the end of every article. Thanks to Drew Glows for providing the update.

= 1.9 =
* Ability to use in-article ad code. If specified, the ad code is inserted after the 2nd paragraph of every posts.

= 1.8 =
* Updated the code to remove a couple of the debug mode warnings.

= Older =
1.7 - WordPress 4.2 compatibility
1.6 - WordPress 3.8 compatibility
1.5 - Added a 5th ad code slot
1.4 - Added a 4th ad code slot
1.3 - Added a new shortcode implementation to display the adsense ads
1.2 - Changed from displaying adcodes through content filtering to the shortcode method
