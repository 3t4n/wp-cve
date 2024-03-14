=== Plugin Name ===
Contributors: aercolino
Donate link: http://andowebsit.es/blog/noteslog.com/contact/
Tags: utf8, utf-8, utf 8, unicode, encoding, db_charset, charset, 4-bytes-long, character
Requires at least: 1.0
Tested up to: 4.0
Stable tag: 2.0.0
License: GPLv2 or later

Trustfully write anything in your language. Stop worrying about truncated content.


== Description ==

Full UTF-8 adds <strong>complete support</strong> for any UTF-8 character.
Without this plugin, WordPress truncates anything after the first 4-bytes-long UTF-8 character.

Suppose you copy some text from a web page and paste it into the editor. You see
everything is fine, so you hit save. As soon as the page is reloaded, you find out that your post
has been truncated at a point that seems to be quite random. Well, that's not random.
The breaking point is the position of the first 4-bytes-long UTF-8 character.
Anything from that one up to the end of the post is irremediably lost forever.

You definitely need to install this plugin if you write 4-bytes-long UTF-8 characters.
How do you know that? Many Chinese characters, musical and mathematical symbols and Emoji icons
are 4-bytes-long. But you can always [try to draw something](http://shapecatcher.com/) and check for yourself.
If you get a Unicode hexadecimal value with more than four digits after 0x then it is a 4-bytes-long UTF-8 character.
For example, a [G-clef](http://shapecatcher.com/unicode/info/119070) is one because 0x1D11E has 5 digits after 0x.


== Installation ==

1. Upload `full-utf-8` folder to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress


== Frequently Asked Questions ==

None.


== Screenshots ==

1. Copy and paste some text into a new post (it works also if you type yourself in the editor.)
   [Example](http://japanese.stackexchange.com/a/6873)<br>
   Everything looks fine, so you save.
1. Default WordPress: Oops! What happened to your text??<br>
   WordPress misbehaves. It cuts anything after the first character it can't process.
1. WordPress + Full UTF-8: Nice.<br>
   WordPress behaves.


== Changelog ==

= 2.0.1 =
* Refreshed main code from WP 4.0.
* Added some images.
* Improved the description.
* Added banner and icon.

= 2.0.0 =
* Added complete support for data written to and read from the database.

= 1.0.2 =
* Fixed the unescape method so that it works on large strings as well.

= 1.0.1 =
* Fixed a small bug in a path that made 1.0.0 unusable.

= 1.0.0 =
* First version.


== Upgrade Notice ==

Deactivate and activate again.


== Other Notes ==

1. This plugin is a WordPress "drop in". Drop-ins are advanced plugins in the wp-content
directory that replace WordPress functionality when present.
1. This plugin is completely automatic. You are not supposed to do anything special
to make it work after activating it.


= Interesting Links =

* [Beauty of Writing Systems](http://www.smashingmagazine.com/2010/05/18/the-beauty-of-typography-writing-systems-and-calligraphy-of-the-world/)
* [Complexity of Writing Systems](http://rishida.net/docs/unicode-tutorial/part1)
* [What is UTF-8?](https://the-pastry-box-project.net/oli-studholme/2013-october-8)
