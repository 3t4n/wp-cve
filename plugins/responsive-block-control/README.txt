=== Responsive Block Control - Hide blocks based on display width ===
Contributors: landwire
Donate link: https://saschapaukner.de
Tags: block, visibility, responsive, hide content, width
Requires at least: 5.2
Tested up to: 6.3.1
Stable tag: 1.2.9
Requires PHP: 5.6
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Responsive Block Control adds responsive toggles to a "Visibility" panel of the block editor (i.e Gutenberg), to show or hide blocks according to screen width.

== Description ==
Responsive Block Control adds responsive toggles to a "Visibility" panel of the block editor (i.e Gutenberg), to show or hide blocks according to screen width.

= Limitations =
Does not work with the Classic Block, Widget Block or Widget Area Block ['core/freeform', 'core/legacy-widget', 'core/widget-area'], as the those blocks do not support block attributes. Does also not work with the HTML Block ['core/html'] inside the Widget Screen, as this one also does not support block attributes there.

== Installation ==
1. Upload `responsive-block-control.php` to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress

== Configuration ==

= Override existing breakpoints =

`function override_responsive_block_control_breakpoints($break_points) {
     $break_points['base'] = 0;
     $break_points['mobile'] = 400;
     $break_points['tablet'] = 800;
     $break_points['desktop'] = 1000;
     $break_points['wide'] = 1600;

     return $break_points;
 }

 add_filter('responsive_block_control_breakpoints', 'override_responsive_block_control_breakpoints');`

= Stop css output completely =


 `function override_responsive_block_control_add_css() {
     return false;
 }
 add_filter('responsive_block_control_breakpoints', 'override_responsive_block_control_add_css');`

== Frequently Asked Questions ==

= Is it possible to use different breakpoints? =

Yes, use the following code in your functions.php or similar.

`function override_responsive_block_control_breakpoints($break_points) {
     $break_points['base'] = 0;
     $break_points['mobile'] = 400;
     $break_points['tablet'] = 800;
     $break_points['desktop'] = 1000;
     $break_points['wide'] = 1600;

     return $break_points;
 }

 add_filter('responsive_block_control_breakpoints', 'override_responsive_block_control_breakpoints');`

= Can I write my own CSS and just use the classes? =

Yes, that is absolutely possible. Just use the filter below to stop the plugin from injecting its' styles:

`function override_responsive_block_control_add_css() {
     return false;
 }
 add_filter('responsive_block_control_breakpoints', 'override_responsive_block_control_add_css');`

== Screenshots ==

1. The 'Responsive Block Control' toggles at work in the block editor.

== Changelog ==

= 1.2.9 =
* Added check to not load editor assets in the front end

= 1.2.8 =
* Updated asset loading for changes introduced in WordPress 6.3
* Added "Limitations" section to readme

= 1.2.7 =
Recompiled assets for distribution

= 1.2.6 =
* fixed translation for visiblity information
* added check for Classic Block and disabled display of settings there

= 1.2.0 =
* fixed some JS logic
* added visibility information to blocks in Gutenberg editor

= 1.1.1 =
* fixed regex for adding classes in the frontend

= 1.1.0 =
* Removed the "blocks.getSaveContent.extraProps" JS filter as it causes block validation errors
* Instead we are using the recommended PHP filter "render_block" to add the necessary classes vie preg_replace()

= 1.0.0 =
* Initial Release of the plugin


== Upgrade Notice ==

Nothing to consider.
