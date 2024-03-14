=== Custom progress bar ===

Tags: progressbar, custom progressbar, circled progress bar, round progress bar, percent bar, progress bar, wordpress progressbar, wordpress progress bar awesome progressbar, widget progress bar, widget progressbar,  multi color progress bar, animated progress bar, bootstrap progress bar
Requires at least: 3.0.1
Tested up to: 4.3
Stable tag: 4.3
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Progressbar plugin will enable awesome progress bar with various styles.


== Description ==

Progressbar plugin will enable awesome progress bar with various styles.
you can change your progressbar with various styles with custom width, color, animation styles.

demo and documentation: http://design.hellothirteen.com/custom-progress-bar/

== Installation ==

This section describes how to install the plugin and get it working.

e.g.

1. Upload custom-progress-bar.php to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Place '<?php do_action('plugin_name_hook'); ?>' in your templates
4. to get the hook name, see the documentation.
5. Use shortcode in posts, page or in widget


Plugin usage -
progress bar can be easily embedded into any page or post. To use it, simply paste this code into your post, page or widget.

<pre>[progressbar_simple]</pre>

<strong>Usages of different progressbar, you can use this shortocode in your posts, pages or widgets.</strong>

<strong>To use striped progress bar, use this shortocode</strong> <p>Chnage the text field as you want. </p>
<pre>[progressbar_striped width="78" color="#ddd" bg_color="#1582cf" text="78% completed"]</pre>

<strong>To use striped animated bar, use this shortocode</strong>
<pre>[progressbar_striped class="active" width="75" text="75% In PHP" bg_color="#e43573"]</pre>

<strong>To use 3 Colors progress bar, use this shortocode</strong> <p>for default style</p>
<pre>[pregressbar_multicolor]</pre>

<strong>To use 3 Colors progress bar, use this shortocode</strong> <p>for custom style style</p>
<pre>[pregressbar_multicolor width="90" bg_color_1="#ec13b7" bg_color_2="#cd60b2" bg_color_3="#ed99d8" text_1="90%"]</pre>

To customize your progress bar, you have to use some codes with this shortcode:

You can use <strong>width </strong> for changing your progressbar width.

You can use <strong>bg_color</strong> for changing your progressbar background-color.

You can use <strong>color</strong> for changing your progressbar font-color. e.g.

<pre>[progressbar width="60" color="#000" bg_color="#aaa"]</pre>

<pre>[progressbar_striped width="25" color="#ddd" bg_color="#000"]</pre>

Also you can use <strong>text</strong> for changing your progressbar text over the progressbar.

<pre>[progressbar_striped width="25" color="#ddd" text="25% completed"]</pre>

This plugin also supported widget shortcode, you can use shortcode in the widget also.


== Screenshots ==

1. screenshot-1
2. screenshot-2
3. screenshot-3
4. screenshot-4


== Changelog ==

= 2.0 = 
* Added Circular progress bar and changed the style of progressbar

= 1.0 =
* Initial Release


