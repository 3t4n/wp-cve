=== Toggleable Admin Bar ===
Contributors: xanthonius
Tags: adminbar, toggle, quick, links, quicklinks
Requires at least: 4
Tested up to: 5.8
Stable tag: trunk
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Allows you to toggle the admin bar on the front end. Useful for websites with fixed positioned elements where the admin bar is in the way.

== Description ==

I created this plugin to help when creating sites that have fixed headers, if you fix the header to the top of the screen you then have to go in and add additional styles for logged in users that have the admin bar, after doing this on quite a lot of sites I thought a better way to manage this might be to allow the admin bar to be toggled in and out of view on the front end, whilst still keeping everything fixed to the top of the browser!

The plugin simply adds a button to the far right of the admin bar, shifting the whole bar up past the top of the screen (out of view) except for the button, on click of the button the admin bar slides into view.

== Installation ==

1. Upload `toggleable-admin-bar.php` to the `/wp-content/plugins/` directory.
2. Activate the plugin through the 'Plugins' menu in WordPress. That's it!

== Frequently Asked Questions ==

= What does this plugin do to my admin bar? =

It simply shifts the admin bar up above the screen, thus taking it out of the viewport. It adds a toggle button with an arrow indicator to the right hand side of your admin bar (placed to the bottom so that it's always visible), when this button is clicked, the admin bar shifts back into view, and can be toggled back out of view again!

= What happens if the user doesn't have JavaScript enabled? =

As long as your theme has the "no-js" class on the "html" tag by default (most themes do), nothing! The plugin falls back to old admin bar in this case.

== Changelog ==

= 1.0 =
* Initial plugin creation.

= 1.1 =
* Added a quick link to the dashboard, and a quick link to edit the current page/post being viewed.

= 1.2 =
* Fixed responsive styles + no-js styles.

= 1.3 =
* Moved JS to footer to eliminate render blocking.