=== Anything Order by Terms ===
Contributors: Briar
Donate link: https://briar.business/donate/
Tags: admin, custom, drag and drop, menu_order, order, page, post, rearrange, reorder, sort, taxonomy, term_order
Requires at least: 5.0
Requires PHP: 5.6
Tested up to: 6.0
Stable tag: 1.4.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

This plugin allows you to arrange any post types and terms with drag and drop. Save post order for each term.


== Description ==

This plugin allows you to arrange any post types and terms with simple drag and drop within the builtin list table on administration screen. Save post order for each term.

= Features =
* Support for any post types and taxonomies.
* Multiple selection is available.
* Capabilities aware. 'edit_others_posts' for post. 'manage_terms' for taxonomy.
* No additional column in builtin tables.
* No additional table in database.
* Save post order for each term.
* Woocommerce and Polylang compatibility.

== Installation ==

1. Upload 'anything-order-by-terms' to the '/wp-content/plugins/' directory
2. Activate the plugin through the 'Plugins' menu in WordPress


== Frequently Asked Questions ==

= I don't want some post types to be sortable. =
Uncheck the "Order" option in "Show on screen" section on [Screen Options](http://codex.wordpress.org/Administration_Screens#Screen_Options) tab to disable sorting.

= I don't want terms or post to be sortable. =
Use [filter](https://developer.wordpress.org/reference/functions/add_filter/). Place in you theme's function.php file `add_filter('Anything_Order/do_order/Post', '__return_false');` or `add_filter('Anything_Order/do_order/Taxonomy', '__return_false');`.

= Why is the post order wrong when using the `get_posts` function?  =
As you can see from [source code](https://core.trac.wordpress.org/browser/trunk/src/wp-includes/post.php#L1936) function `get_posts` default set `suppress_filters` param to WP_Query args. Therefore, filters specified by plugins don't work. So if you need menu order or order within term add `'suppress_filters' => false` to your args.

= How do I reset the order? =
Select the "Reset Order" option in [bulk actions](https://codex.wordpress.org/Posts_Screen#Actions) select and click "Apply".

= How do I select multiple items? =
Ctrl(or Command on OS X)+Click toggle selection state of current item. Shift+Click select items between first selected item on the list and current item.


== Screenshots ==

1. Enable/Disable arrangement with drag and drop on "Screen Options" tab. Reset bulk action.
2. Dragging items. Also support custom post type like Woocommerce product.
3. You can select multiple items by Ctrl(or Command on OS X)+Click.


== Changelog ==

= 1.4.0 - 2022-07-08 =
* Fixed - Wordpress 6.0  compatibility.

= 1.3.10 - 2022-07-06 =
* Fixed bug with "Woocommerce + Polylang + WP All Import" package.

= 1.3.9 - 2022-04-22 =
* Fixed - Wordpress 5.7  compatibility.

= 1.3.8 - 2021-11-11 =
* Fixed - Conflict with Wicked Folders plugin.

= 1.3.7 - 2020-11-04 =
* Changed - Wordpress 5.6 and Woocommerce 4.9 compatibility.

= 1.3.6 - 2020-11-04 =
* Fixed - Wrong detect current term if Polylang is activated.

= 1.3.5 - 2020-07-21 =
* Changed - Wordpress 5.4.2 and Woocommerce 4.3.0 compatibility.

== Upgrade Notice ==

The current version of Anything Order requires WordPress 5.0 or higher. If you use older version of WordPress, you need to upgrade WordPress first.