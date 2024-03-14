=== Channel Widget for telegram ===
Contributors: sjhoo
Tags: telegram channel in wordpress , telegram widget , telegram channel , channel , telegram , embed channel , channel widget , telegram channel widget
Requires at least: 3.1
Tested up to: 5.2
Stable tag: 0.2
Donate link: http://www.ttmga.com/
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Display your telegram channel in wordpress.

== Description ==

### What the plugin can do ?

As you know, Telegram has added the ability to display channel posts on the web using EMBED Code.

Based on this feature, I decided to build this plugin That allows you to display all your channel content in a beautiful widget
similar to the Telegram App easily, and by activating the **post updater bot** on your channel, you will automatically have
the most recent channel content in your site widget.

Note : You can display any channel's post on your Wordpress site when you enter last post link of a channel in the plugin's Setting !
but if you need to Connect widget to your channel and automatically show all new posts of your channel on TGchannel widget ,
 you should add @TGCH_BOT to your telegram channel as admin.
this bot will refresh widget posts when a new post published in telegram channel.


== Screenshots ==
1. Show your Channel in sidebar using TGchannel widget. (screen-1.png)
2. Show your Channel in pages/posts using shortcode. (screen-2.png)
3. TGchannel Setting. (screen-3.png)
4. TGchannel Widget. (screen-4.png)


== Installation ==

1. Install the TGchannel plugin either via the WordPress plugin directory, or by uploading the files to your web server (in the `/wp-content/plugins/` directory).

2. Activate the TGchannel plugin through the 'Plugins' menu in WordPress.

3. Navigate to the 'TGchannel' settings page to obtain your channel link and configure your settings.

4. Use the shortcode `[tgchannel]` in your page, post or widget to display your Telegram Channel.


== Frequently Asked Questions ==

= How can i use it? =

Go to target channel in telegram , then right click on last post , and choose **Copy Post Link**, paste the coppied link to **last post link** field in TGchannel's Setting and click on Validation.

If you desire to have the newest posts from your Telegram channel on **TGchannel Widget** ,  add "@TGCH_BOT" to your Telegram channel as admin and then click on **Active TGCH_BOT** . 

click on **Save changes** button .

Copy and paste the following shortcode directly into the page, post or widget where you'd like your channel to show up :

[tgchannel]

also you can use **TGchannel Widget** that already exists on  Appearance->widgets !



== Changelog ==
= 0.1 =
Launched the TGchannel plugin!
= 0.2 =
An option page to customize styles and widget's size has ben added.