=== Categories Multiple Images ===
Contributors: binternet
Tags: category, taxonomy, images
Requires at least: 4.1
Tested up to: 4.9.8
Stable tag: trunk
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

The Categories Multiple Images Plugin allow you to add image unlimited images to category or any other taxonomy.

== Description ==

This plugin is an extended version of [Categories Images](https://wordpress.org/plugins/categories-images/).

The Categories Multiple Images Plugin allow you to add unlimited amount of image to a category or any other taxonomy.

You can use: 
<?php Categories_Multiple_Images::get_image( term_id, image_number, image_size, use_placeholder ); ?> 

to get the direct image url and put it in any <img> tag in your template.

Also from settings menu you can exclude any taxonomies from the plugin to avoid conflicting with another plugins like WooCommerce!

More documentation
https://github.com/Binternet/WordPress-Plugin-Categories-Multiple-Images

== Installation ==

e.g.

1. Put the plugin directory in your plugins directory (Usually `/wp-content/plugins/`)
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Place `<?php Categories_Multiple_Images::get_image( term_id, image_number, image_size, use_placeholder ); ?> ` in your templates
   Values are:
   - term_id - Category ID (or any other term_id)
   - image_number - Desired image number (starting from 1), you can also use 'random' and a random image from that category will be returned
   - image_size - desired image size, defaults to 'full'
   - use_placeholder - should a placeholder should be returned if the image does not exists

== Changelog ==

= 1.1 =
* Added a new option to pass 'random' as an image number and get a random image for a given category ID
* Some documentation and refactoring

= 1.0.3 =
* Bugfix - Thanks to Paul Tero

= 1.0.2 =
* Bugfix, on need for php_short_tags enabled anymore.

= 1.0.1 =
* Bugfix

= 1.0.0 =
* First release, my first plugin ;-)

