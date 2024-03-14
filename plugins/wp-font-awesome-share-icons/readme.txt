=== WP Font Awesome Share Icons ===
Contributors: spyrosvl, artifex404
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=9QBE26DTURXJQ
Tags: social media, social share, share post, social sharing, share buttons, facebook, twitter, google+, share, share links, stumble upon, linkedin, pinterest
Requires at least: 3.6
Tested up to: 4.7
Stable tag: 1.1.1
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

A simple plugin to display share button links before or after post or where ever you choose, utilizing Font Awesome Icons.

== Description ==

WP Font Awesome Share Icons is a simple plugin that will help you put social sharing buttons on your site without writing any code, in a SEO friendly way.

= Supported Networks =

* Facebook
* Twitter
* Google Plus
* Linkedin
* Pinterest
* Tumblr
* VKontakte
* StumbleUpon
* Delicious
* Digg
* Xing
* Weibo
* Renren
* SpecificFeeds
* Email
* W3C Validation Link

[W3C validation link will only be displayed when the user is logged in and is admin. If you do not know what this icon is for just ignore it.]

= Filters for Devs (via add_filter) =

* wpfai_array_filter (You can alter the social media array with this)
* wpfai_output_filter (You can alter the final output with this)

= Languages =

* English
* Greek
* Spanish thanks to Andrew of [WebHostingHub](http://www.webhostinghub.com/)

Please email to us any translations you may make and we will include it on the next release!

= Features =

* Drag-n-drop ordering
* Easy enabling or disabling
* Square, Circle (round) or Simple icons.
* Multiple sizes out-of-the-box
* It will not re-load Font Awesome if it is already loaded and detected
* Many options about where to display the icons
* Option to open in new window, so users will not go away from your site
* Shortcode is included to enter on posts/pages etc
* PHP Function is included to enter it on your theme


[Detailed description, screenshots and guide for WP Font Awesome Share Icons](https://www.hostivate.com/en/blog/wp-font-awesome-share-icons)

== Installation ==

1. Upload the entire `wp-font-awesome-share` folder to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress


== Frequently Asked Questions ==

= How can I include it via my theme files? =

Use this: &lt;?php echo wpfai_social(); ?&gt;

You can also use an argument for customization. Please refer to http://www.hostivate.com/en/blog/wp-font-awesome-share-icons/

= What is the shortcode to inlude it on my posts? =

Use this: [wpfai_social]

Attributes can be inserted for customization. Please refer to http://www.hostivate.com/en/blog/wp-font-awesome-share-icons/

= Is there a widget? =

Not yet. You can use a plugin to enable shortcode on text widgets and then paste [wpfai_social] on one of them.

= Please use the support forum of the plugin on wordpress.org =

== Screenshots ==

1. Admin UI.
2. Circle (round) inverse icons.
3. Circle (round) icons.
4. Square icons.
5. Square inverse icons.
6. Simple icons.

== Changelog ==

= 1.1.1 =

* [  Bug  ] Fixed Twitter share url
* [  Bug  ] Fixed PHP warnings

= 1.1.0 =

* [Feature] Added a counter setting to show share amount

= 1.0.12 =

* [Feature] Added Spanish translation

= 1.0.11 =

* [Feature] Added circle-thin to stack

= 1.0.10 =

* [  Bug  ] Fixed trim bug

= 1.0.9 =

* [Feature] Added shortlink option
* [  Bug  ] Fixed some urlencode bug


= 1.0.8 =

* [Feature] Added translation .po .mo files
* [Feature] Added Greek translation
* [Feature] Added plugin icon for the plugin installer

= 1.0.7 =

* [Feature] SpecificFeeds service was added
* [Feature] W3C Validation Link that is displayed only for the admins was added
* [Feature] Filters were added (look at the description
* [Feature] Upgraded to Font Awesome 4.2.0
* [  Bug  ] Fixed some validation errors

= 1.0.6 =

* Various bug and w3c fixes

= 1.0.5 =

* Fix for some ... stubborn themes

= 1.0.4 =

* [  Bug  ] Fixed notices warnings
* [Feature] Added shortcut attributes
* [Feature] Added function argument
* [Feature] Open in new window option
* [Feature] Added Weibo icon for sharing
* [Feature] Added Renren icon for sharing

= 1.0.3 =

* [  Bug  ] Fixed Tumblr sharing bug

= 1.0.2 =

* [  Bug  ] Check if there are no styles enqueued

= 1.0.1 =

* [  Bug  ] Fixed path

= 1.0.0 =

* Initial version.