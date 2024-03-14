=== Genesis Simple Page Sections ===

Contributors: Doug Yuen
Author URI: https://efficientwp.com
Plugin URI: https://efficientwp.com/plugins/genesis-simple-page-sections
Tags: genesis, genesiswp, studiopress, page sections, shortcode
Requires at least: 4.0
Tested up to: 5.1
Stable tag: trunk
License: GPLv3 or later
License URI: https://www.gnu.org/licenses/gpl.html

Easily make full width page sections in Genesis. Must be using the Genesis theme framework.

== Description ==

Easily make full width page sections in Genesis via shortcode. Must be using the [Genesis theme framework](https://efficientwp.com/genesis "Genesis theme framework").

Notes:

* If you use this shortcode on a page, you should enclose all of your content on the page within one or more instances of the shortcode. In order for this plugin to create a full width section, you must specify the page/post layout to be "full width content." 
* Breadcrumbs and other sections dynamically inserted in the inner content section may also stretch to full width, but you can add custom CSS to your theme's style.css (preferably your child theme's style.css, or a custom CSS plugin) to accommodate it. You need to get the class or id of the section you do not want to stretch, and assign the properties "max-width: 1200px;" (or whatever width you want the section to be) and "margin: 0 auto;" to it. 
* The shortcode syntax \[gsps\] was added in version 1.2, and it is identical to the \[genesis-simple-page-section\] syntax. You can use either one, or both. 

Basic shortcode syntax:

\[gsps color="orange" width="960" outer_class="" outer_css="" inner_class="" inner_css=""\]

\[/gsps\]

For more about the shortcode syntax, available parameters, and color presets, please visit the [plugin page on EfficientWP](https://efficientwp.com/plugins/genesis-simple-page-sections "Genesis Simple Page Sections").

Created by [EfficientWP](https://efficientwp.com "EfficientWP").

== Installation ==

1. Upload `genesis-simple-page-sections.zip` into your plugin directory (typically `/wp-content/plugins/`).
2. Unzip the `genesis-simple-page-sections.zip` file.
3. Activate the plugin through the 'Plugins' menu in WordPress.

== Frequently Asked Questions ==

[Plugin page on EfficientWP](https://efficientwp.com/plugins/genesis-simple-page-sections "Genesis Simple Page Sections")

== Screenshots ==

1. An example of the plugin in use, using the [Dynamik child theme for Genesis](https://efficientwp.com/dynamik "Dynamik child theme for Genesis").

== Changelog ==

= 1.4 =
* Confirmed compatibility up to WordPress 5.1
* Rewritten in object-oriented format

= 1.3 =
* Confirmed compatibility up to WordPress 5.0.2
* Added specificity to CSS to apply full width only to single pages, posts, and custom post types (not archives and searches)

= 1.2 =
* Confirmed compatibility up to WordPress 4.2.1
* Added alternate shortcode syntax \[gsps\]

= 1.1 =
* Improved instructions
* Improved code standards to fix PHP notice
* Removed call to deprecated force layout function
* Moved CSS to enqueued file

= 1.0 =
* Fixed CSS to make width 100% and be compatible with Genesis 2.0
* Improved shortcode documentation

= 0.1 =
* Initial release

== Upgrade Notice ==

Coming soon.