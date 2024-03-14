=== Plugin Name ===
Contributors: doytch
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_xclick&business=mark%2edeutsch%40utoronto%2eca&lc=US&item_name=Coffee%20Donation%20%3d%29&amount=10%2e00&currency_code=USD&button_subtype=services&bn=PP%2dBuyNowBF%3abtn_buynowCC_LG%2egif%3aNonHosted
Tags: audio, embed, html5, media, plugin, shortcode, video, youtube
Requires at least: 3.0.1
Tested up to: 4.9.4
Stable tag: 1.4.4
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Adds clickable links via shortcode or search-and-replace that skip to a time in a media player.

== Description ==

Skip to Timestamp allows two ways of generating links in your posts that automatically skip to a given time
in an embedded media element. You've seen it in the Youtube descriptions, now get it in your embeds!

To create a link, either:

* Use our [skipto] shortcode which takes a "time" (or "timestamp" or "ts") argument and uses that to create a link.
Eg, "Just wait until...or...hold on, you can [skipto time=4:30]skip to[/skipto] this part of the video!"
* Check the "Replace Timestamps Automatically" checkbox in the Settings > Skip to Timestamp, and watch as all your
timestamps in the form "4:30" or "1:23:45" are automatically converted into links.

The links search for and skip to a time in:

* Youtube videos embedded using Wordpress embeds. Make sure you're just pasting in the Youtube video link into your post editor and let WordPress embed things for you to ensure that the correct player is inserted into your post.
* HTML5 &lt;audio&gt; elements. Jetpack, Blubrry Powerpress and other plugins do this automatically using the [audio] shortcode.
* HTML5 &lt;video&gt; elements. Jetpack and other plugins do this automatically using the [video] shortcode.

== Installation ==

You don't really need to do anything special to install this plugin. Just:

1. Download using the button above, unzip and place the folder in your wp-content/plugins/ folder. Alternately,
install using Wordpress' built-in plugin installer.
1. Activate the plugin through the Plugins menu in WordPress
1. Configure settings via Settings > Skip to Timestamp. Automatic timestamp replacement is turned off by default.

== Frequently Asked Questions ==
= Will this work with ____ player? =
If it adds an &lt;audio&gt; or &lt;video&gt; tag, then maybe. The plugin uses the currentTime parameter of those elements
to set the location to skip to. If you open your browsers developer console and see a message saying
'Skip to Timestamp: No media player found!', then that player isn't currently supported. I'd like to help you
though, so please submit a request for that player on the Wordpress plugin page. It's the only way I know
which players to prioritise!

= Can I use both shortcodes and automatically-replaced timestamps? =
Yes, as long as you don't use both in the same post. Each post can contain either shortcodes OR timestamps to
auto-replace. If both are present, then timestamps will not be automatically replaced.

= It's not working! =
Please leave me a message with information about the media you embedded, how you tried to create the link, and what
browser and operating system you're running. If it's a bug, I'll get right to work on it!

== Screenshots ==
1. Timestamps in your posts can be automatically converted into links that jump to that time.
2. Control the text of the link by using our [skipto] shortcode.

== Changelog ==

= 1.4.4 = 
* Fixed bug with autolinks on timestamps.

= 1.4.1 =
* Switched links back to <a> from <span>

= 1.4 =
* Fixed issue where playbackRate wouldn't be preserved.
* Switched to dataset tags and global event handlers.
* Thanks Lewis Cowles for the pull request!

= 1.3 =
* Housekeeping

= 1.2 =
* Added ability to turn off automatic linking on a per-page basis.

= 1.1 =
* Support for older versions of WordPress

= 1.0 =
* Initial release
