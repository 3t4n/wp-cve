=== Particle Background WP ===
Contributors: ryannovotny
Donate link: https://particlebackground.com/
Tags: particle, background, backgrounds, particle background, particles.js 
Plugin URI: https://particlebackground.com/
Requires at least: 4.0
Tested up to: 6.4
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
Stable tag: trunk

Particle Backgrounds WP lets you easily add cool particle-style backgrounds to any page.

== Description ==

Add the particles.js JavaScript library to add cool particle effects to any WordPress page ( demo: [https://particlebackground.com/](https://particlebackground.com/) ).

Uses shortcode or easy options for adding to the front page or blog page of WordPress.

Fully customize the particles using your own JSON or the built in color & density settings

Leave a comment in the support forum for any suggestions or bugs.

= Features =
Add particle background to home or blog pages with one click
Add particle background to any page using shortcodes
Customize the background color and dot color
Add any HTML just like a WordPress post to display on top of the particle background (optional)

== Installation ==

1. Upload the plugin file to your `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. In your sidebar, select 'Particle Backgrounds' to get started
 
== Frequently Asked Questions ==

= How can I add my own particles.js config file? =
Method 1: Add a directory in your theme called `particle-background-wp` and copy your `particlesjs-config.json` file to that folder.  The json will automatically be loaded.
Method 2. Add a filter `rn-pbwp-custom-json` that returns a JSON string of the config.

= How can I customize which pages the particle background appears on? =
Method 1: Use the one-click buttons to add particle background to your Blog or Home pages
Method 2: Insert the shortcode `[particle-background-wp]` on the page you want to add a particle background to.
Method 3: Use the filter `rn-pbwp-enqueue` to return true to add particle background to a page.

== Screenshots ==
1. Default Setting
2. Admin screen

== Changelog ==

= Particle Background WP 2.0.0 =
* Added ability to target CSS selectors to fill with particles(!)
* Rewrote shortcode behavior to fill the parent with particles, instead of inserting a top section
* A few UI updates

= Particle Background WP 1.1.0 =
* Added particle density slider
* Added a filter for custom JSON 
* Added a theme-specific particle json support.  Add /particle-background-wp/particlejs-config.json to your theme to automatically load a particles.js JSON config file
* Added a filter for enqueuing the particle script ( 'rn-pbwp-enqueue' ) - true to enqueue the particles on that page
* Fix shortcodes not working in the content input

= Particle Background WP 1.0.0 =
* Initial Release