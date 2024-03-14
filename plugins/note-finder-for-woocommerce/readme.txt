=== Note Finder for WooCommerce ===
Contributors: disablebloat
Tags: woocommerce, search for notes, order notes search, find woocommerce notes
Stable tag: trunk
Requires at least: 5.0
Tested up to: 5.8
Requires PHP: 7.0
WC requires at least: 3.0
WC tested up to: 4.0
License: GNU General Public License v3.0
License URI: http://www.gnu.org/licenses/gpl-3.0.html

Search for WooCommerce order notes

== Description ==

Extends WooCommerce functionality by letting you **search for notes**. Plugin adds a new subpage in your WooCommerce admin panel in which you can easily search for WooCommerce notes. Filter WooCommerce notes by words, phrases or statuses.

= Features =

* Allows you to **search notes**
* Lists all notes with links to orders
* Shows and indicator if a note has been sent to the customer
* Shows a note's author (if there's one)
* Shows note's date & time

= Usage =

1. Go to **WooCommerce** -> **Notes** menu in your WordPress admin panel
2. Enter your query in the search form and click *Search notes* button
3. When your note is found, you can go to the order related with it by clicking an order number in the first column

 
= TO DO =

 * Pagination
 * Enhance *Items per page* field
 * Editing and managing notes

= Big thanks to =
*Based on [WPC Order Notes](https://wordpress.org/plugins/woo-order-notes/) which unfortunately does not allow to search notes. This feature has been implemented and plugin's code got cleaned-up.*

== Installation ==

1. Upload the entire `note-finder-for-woocommerce` folder to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Search panel can be found in WooCommerce -> Notes menu

== Frequently Asked Questions ==

= Can I browse notes by note author? =
Yes - simply enter author's nickname in the search form.

= Can I use pagination while browsing notes? =
Unfortunately this feature is not available yet.

= There is an error on the Notes page =
Please make sure that WooCommerce plugin is installed and active.

== Screenshots ==
1. Search for WooCommerce notes easily through your admin panel

== Changelog ==

= 1.3 =
* Fixed fatal error
* Added WPML support

= 1.2 =
* Added compatibility with WooCommerce 5.1 and WordPress 5.7

= 1.1 =
* Fix: order edit link now comptible with custom order IDs 

= 1.0 =
* First release