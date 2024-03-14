=== Restaurant Menu Manager ===
Contributors: noumaan
Tags: restaurant, restaurant menu, cafe menu, food
Requires at least: 3.0.1
Tested up to: 4.5.2
Stable tag: 1.0.5
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Create restaurant menu in WordPress, group different menu items, display them in a list or jQuery accordion or tabs.

== Description ==

Restaurant Menu Manager allows you to easily add entries into your menu. You can group those entries into menu types, for example: *Lunch, Dinner, Breakfast, Desserts, etc.* You can also group entries with entry tags, for example: *Vegetarian, Sandwiches, Salad, Soups, etc*. 

You can choose to display your menu in a simple list view, or you can display it in jQuery accordion or jQuery tabs. Menu entries are grouped by Menu types. For example, entries marked under menu type *'Lunch'* will be displayed together. 

Restaurant Menu uses default WordPress functionality and makes use of custom post types, taxonomies, and custom meta fields to do the job. This allows you, to easily import and export your data using the built in WordPress tools. Restaurant Menu also supports other WordPress features:

*	Add images or even a full gallery for each of your menu entries.
*	Set a featured image or entry thumbnail.
*	Allow visitors to leave comments below each entry.
*	Use other WordPress plugins with your Restaurant Menu Entries.
*	Translation Ready.

To display the menu on your site in simple list, use this simple shortcode:

[rm-menu]

If you want to display restaurant menu in a jQuery accordion with collapsible tabs for different menu types, then add this shortcode:

[rm-menu display='accordion']

If you want to display your menu in jQuery tabs, then add this shortcode:

[rm-menu display='tabs']


If you have feedback, suggestion, or questions about the plugin, please visit [Restaurant Menu Manager Plugin website](http://sabza.org/restaurant-menu-manager-plugin-wordpress/ "Restaurant Menu Manager Plugin for WordPress"). Lastly, if you like this plugin, then please don't forget to give it a good rating and review.


== Installation ==

1. Upload the plugin folder to your /wp-content/plugins/ folder.
1. Go to the **Plugins** page and activate the plugin.

== Frequently Asked Questions ==

= How do I create restaurant menu? =

Simply click on **Restaurant Menu** in your WordPress admin sidebar. Then click on **Add New Restaurant Menu Entry** to create the first entry in your menu.

Creating an entry is like creating any page or post in WordPress. Provide your entry a title. In the editor area provide details about the entry, a nice description, ingredients, nutrional information, images, etc. Set the price for the entry in the Entry Price field. Group your entries in **Menu Types**, e.g. *Lunch, Dinner, Breakfast, Specials, Desserts, etc*. You can also add Entry Tags for each entry, e.g. *Spicy, Vegetarian, Sandwiches, Soups, etc*. You can also set a featured image for each entry. .

= How do I display the menu on my website? =

After entering a couple of entries in your menu, create a new page in WordPress. To create a new page simply click on **Pages -> Add New** in your WordPress admin sidebar. Give your new page a title, for example, *Our Menu*. 

To display your menu in a simple list, use this shortcode:

`[rm-menu]`

To display your menu in a jQuery accordion, use this shortcode:

`[rm-menu display='accordion']`

To display your menu in jQuery tabs, use this shortcode: 

`[rm-menu display='tabs']`

= How do I rearrange the Tabs or Accordion order? = 

By default tabs and accordion show menu type alphabetically. For example, Breakfast, Dinner, Lunch. This may not be a very ideal order in some situations. For users who would like to change that order, you can use [Taxonomy Terms Order](http://wordpress.org/plugins/taxonomy-terms-order/ "Taxonomy Terms Order") plugin to change the order of Menu Types.  

= I added menu entries and inserted shortcode but no menu entries are displayed on the page?  =

Please note, that currently you must choose a 'Menu Type' for your entries. Restaurant Menu Manager displays entries grouped in 'Menu Types', so each of your entry must have a menu type assigned to it. However, if you do not want to group your entries into menu types, then I would recommend that you at least create one Menu Type, then assign all your menu entries into that menu type. 

= How do I modify CSS to use my own colors? =

You can modify the plugin stylesheet file restaurant-menu-screen.css, which is located in the plugins folder. 

= I have some feature requests, feedback, or questions about the plugin... =

You can use the support tab here, or visit the [plugin website](http://sabza.org/restaurant-menu-manager-plugin-wordpress/ "Restaurant Menu WordPress Plugin"). 

== Screenshots ==

1. Restaurant menu displayed in jQuery accordion tabs.
1. Restaurant menu displayed in jQuery tabs. 
1. Restaurant menu displayed in simple list. 
1. Add New Restaurant Menu Entry screen.
1. All Restaurant Menu Entries Screen. 

== Changelog ==
= 1.0.4 =
* Fixing pagination issues by turning off pagination. 
= 1.0.3 =
* Bug fix to display entry price when a single entry is viewed. 
= 1.0.2 = 
* Fixed shortcode output bug
* Fixed menu icon for Restaurant Menu Entry custom post type on WordPress 3.8 and later versions. 
* Fixed translation issues

= 1.0.1 =
* fixed bug that broke tabs when displayed without thumbnails.
= 1.0 =
* Plugin released. 
