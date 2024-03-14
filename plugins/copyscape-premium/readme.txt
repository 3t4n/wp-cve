=== Copyscape Premium ===
Contributors: Copyscape
Tags: copyscape, plagiarism, duplicate content, original, unique
Requires at least: 3.0.1
Tested up to: 6.2
Stable tag: 1.3.4
License: MIT
License URI: http://opensource.org/licenses/MIT

The Copyscape Premium plugin lets you check if a WordPress post is unique before it's published, by searching for duplicate content on the web.

== Description ==

The Copyscape Premium plugin lets you check if a WordPress post is original before it's published, by using the Copyscape Premium API to check for duplicate content on the web.

The plugin will add a 'Copyscape Check' button to your WordPress interface, allowing you to check your posts whenever you wish. You may also set the plugin to automatically check your posts when you click 'Publish' and/or 'Update'.

When duplicate content is found, a report of matching pages is shown. You may also see a detailed comparison that highlights your content on the found page.

If you do not already have a Copyscape Premium account, please [sign up](http://www.copyscape.com/redirect/?to=prosignup "Copyscape Premium sign up"),  [purchase some credits](http://www.copyscape.com/redirect/?to=propurchase "Purchase Copyscape Premium Credits"), and enable your [API access](http://www.copyscape.com/redirect/?to=apiconfigure#key "Copyscape Premium API page"). You may then begin using the plugin. 


== Installation ==

You may download and install the Copyscape Premium plugin using the built-in WordPress plugin installer. If you wish to download the Copyscape Premium plugin manually, make sure to save it in the directory "/wp-content/plugins/copyscape-premium/".

Begin by activating the Copyscape Premium plugin in the "Plugins" admin panel using the "Activate" link. A simple installation wizard will guide you through the process of configuring the plugin.

To use the plugin, you must have a Copyscape Premium account with credits and an API key. If you don't yet have an account, please [sign up](http://www.copyscape.com/redirect/?to=prosignup "Copyscape Premium sign up") and [purchase some credits](http://www.copyscape.com/redirect/?to=propurchase "Purchase Copyscape Premium Credits"). To get your API key, please visit the [API configuration page](http://www.copyscape.com/redirect/?to=apiconfigure#key "Copyscape Premium API page"), click 'Enable API Access', and your API key will be displayed. 


== Frequently Asked Questions ==

Please read the list of Frequently Asked Questions below. If you have additional questions, please feel free to [contact us](http://www.copyscape.com/redirect/?to=contact "Contact Us").

= How do I sign up for a Copyscape Premium account? =

To use the plugin, you must have a Copyscape Premium account. If you do not already have an account, please [sign up](http://www.copyscape.com/redirect/?to=prosignup "Copyscape Premium sign up"). 


= How do I purchase credits? = 

To use the plugin, you will need to purchase Copyscape Premium credits. If you have not already done so, please [purchase some credits now](http://www.copyscape.com/redirect/?to=propurchase "Purchase Copyscape Premium Credits").


= How do I get an API key? =

To get your API key, please visit the [API configuration page](http://www.copyscape.com/redirect/?to=apiconfigure#key "Copyscape Premium API page"), click 'Enable API Access' (if it is not already enabled), and your API key will be displayed. 


= What happens if I run out of credits? =
 
If you run out of credits, checks will not be performed. If automatic checking is on, posts will be moved to drafts, and you will receive a notification that you have run out of credits. You will also be given a link to purchase additional credits.


= Will I be charged for checking the same text twice? =

If a check is performed immediately again on the exact same text, the previous report will be presented without a new check being performed.


= How do I configure the plugin to automatically check my posts? =

In the Plugin Settings, check the box marked ‘Check for copies when a post is published’ (or ‘Check for copies when a post is updated’). Upon clicking publish (or update), your post will be checked. If your site runs WordPress version 5.0 or higher, when matching content is found, the option to view the detailed report will be presented along with the choice to unpublish the post and move it back to Drafts. If your site runs earlier versions of WordPress, when matching content is found the post will be unpublished automatically, and an option to view the detailed report will be presented, along with the choice to publish the post anyway.


== Screenshots ==

1. A 'Copyscape Check' button allows checking for duplicate content.

2. A notification appears when duplicate content has been detected.

3. A report shows the matching results found. 

4. A detailed comparison shows the user's content highlighted on the found page.


== Changelog ==
= 1.0 =

First released version.

= 1.1 =

Added support for WordPress 5.0+

= 1.2 =

Added support for Gutenberg and classic editor
Solved warnings and notices that were shown in the dashboard

= 1.3 =

Solved issues of button hidden after the wp 5.6 update

= 1.3.2 =

Solved conflict with SEO plugins

= 1.3.3 =

Prevent sending API requests on shorter paragraphs

= 1.3.4 = 

Fix View matches button not showing in gutenberg on slow themes