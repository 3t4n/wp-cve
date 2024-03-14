=== Simple QR Code ===
Contributors: bainternet 
Donate link:http://en.bainternet.info/donations
Tags: qr code, QR Code, QR widget
Requires at least: 3.9
Tested up to: 4.7.0
Stable tag: 1.4

With This Plugin you Easily insert QR codes in your blog, with Widget or Shortcode.

== Description ==
With This Plugin you Easily insert QR codes in your blog, with Widget or Shortcode.
the plugin add a tinymce button so even the shortcode can be inserted for you.


Main features:

*	Very easy to use.
*	TinyMCE button for easy shortcode insertion.
*	built-in Widget.
*	Use as shortcode.
*	Use as template tag for easy integration with your theme or plugin.
	
any Feedback is Welcome.

check out my [other plugins][1]

[1]: http://en.bainternet.info/category/plugins

== Installation ==

1.  Upload the plugin directory to the /wp-content/plugins/ directory
1.  Activate the plugin through the \'Plugins\' menu in WordPress

== Frequently Asked Questions ==

= It's not working, whats wrong? =
Could be a miolion thing but the main reason is you simply need to add the shortcode to a page or a post,
so simply create a page/post and enter `[QR]http://www.google.com[/QR]`

= I have Found a Bug, Now what? =

Simply use the <a href=\"http://wordpress.org/tags/simple-qrcode?forum_id=10\">Support Forum</a> and thanks a head for doing that.


= How to use in template files? =

`<?php echo do_shortcode('[QR]http://www.google.com[/QR]'); ?>`


== Screenshots ==
1. TinyMCE button.

2. TinyMCE popup options

3. Widget options

4. example of generated QR, shortcode and widget

== Changelog ==
1.4 plugin returns link without protocol to avoid mixed content on https enabled sites.

1.3 updated tinymce button.
re-coded most of the plugin whihc no is a simpler version of the older ones.
using `wp_get_shortlink` to allow long urls to be encoded to qr code.

1.1 `out` widget bug fix.

1.0 initial release.
