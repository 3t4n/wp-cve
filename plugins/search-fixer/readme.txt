=== Search Fixer ===
Contributors: bennettmcelwee
Donate link: http://www.thunderguy.com/semicolon/donate/
Tags: search, search-meter, permalink, space, pretty
Requires at least: 3.0
Tested up to: 3.1.3
Stable tag: 2.0

Search Fixer makes "pretty" search links (e.g. http://example.com/search/waldo) work properly even when they contain spaces.

== Description ==

Search Fixer makes "pretty" search links work properly. A pretty search link usually looks like this:
http://example.com/search/waldo
Because of a bug in WordPress, pretty search links with spaces in them do not work. Search Fixer fixes that bug.

If you use [Search Meter](http://wordpress.org/extend/plugins/search-meter/)'s widgets, you should install Search Fixer too.

= Technical details =

The bug is [WordPress bug 13961](http://core.trac.wordpress.org/ticket/13961). This prevents "pretty" search URLs from working properly. For example, http://example.com/search/hello%20world should search the example.com blog for the words "hello" and "world", but because of the bug it actually searches for "hello%20world" and fails to find anything.

When the WordPress bug is fixed (probably sometime in 2011) Search Fixer will no longer be necessary. I will keep Search Fixer up to date so it won't interfere when the WordPress bug gets fixed.

== Installation ==

Simply install and activate through the 'Plugins' menu in WordPress.

If you want to install manually, upload `search-fixer.php` to the `/wp-content/plugins/` directory and then activate the plugin through the 'Plugins' menu in WordPress.

== Changelog ==

= 2.0 =
* Search links to multi-word searches now work. For example, a search link for "hello world" will find posts that contain those two words anywhere in the post.

= 1.0 =
* Internal version, not publicly released.
* Search links function correctly, but multi-word searches do not work. For example, a search link for "hello world" will only find posts that contain that exact phrase.
