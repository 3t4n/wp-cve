=== WC Shipping Rates Importer ===
Contributors: joesat
Donate link: https://www.paypal.me/wpjoesat
Tags: woocommerce, shipping table rates, import, export
Requires at least: 3.9.3
Tested up to: 4.7.3
Stable tag: 1.1.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Import / Export WooCoomerce Shipping Zone data & Shipping Table Rates (if installed)

== Description ==
Dealing with multiple WooCommerce sites sharing the same shipping table rates, adding all those data over and over is a tedious task. You should be doing something more productive. WC Shipping Rates Importer takes care of exporting and importing shipping zone data and table rates (WC premium plugin required) for you!

The plugin has been tested extensively with WP_DEBUG enabled and works seamlessly with multi-site network setup.

For questions, suggestions or issues with this plugin, visit the plugin support page or contact me at wp [dot] joesat [at] gmail [dot ]com.

== Installation ==

1. Upload 'wc-shipping-rates-importer' folder to '/wp-content/plugins/' directory. Alternatively just search 'WC Shipping Rates Importer' from the Add New Plugin in your Wordpress Admin

2. The plugin attempts to create data folders and sets the proper read-write permissions, please check that the plugin/ directory is writable.

3. Activate the plugin through 'Plugins' menu in WordPress Admin

4. 'WC Import Shipping' menu should appear after activating the plugin. Click that link and you're good to go!

== Frequently Asked Questions ==

* Does this plugin work with multi-site setup?
No.

* Was did plugin tested with debug option enabled?
Yes.

* Where is the admin page located?
In the admin panel, there should be a menu item 'WP Import Shipping'.

* Plugin is reporting permission denied...?<br>
The data directories where the plugin writes content must be writable. The plugin tries to do this via (chmod 774 and 775) but some setup might need 777. You may need to update permissions manually.<br>
Folder paths<br>
[WPDIR]/wp-content/plugins/wc-shipping-rates-importer/data/import<br>
[WPDIR]/wp-content/plugins/wc-shipping-rates-importer/data/export

* Anything else?
Imported and Exported files are stored in the plugin's data/ folder. Later releases will have a purge feature to cleanup these folders.

== Upgrade Notice ==

= 1.1.0 =
Thanks to @kingfisher64 & @jphase for some fixes for this version.<br>
https://wordpress.org/support/topic/error-cant-activate-plugin-2/

= 1.0.0 =
Nothing yet.
== Screenshots ==

1. WC Shipping Rates Importer Main Page. A file has been selected for import.
2. WC Shipping Rates Importer Post Upload Page. Summary of current WooCommerce zone and table rates data, plus a column of values from the import file.
3. WC Shipping Rates Importer Post Import Page. A notification that the import has completed successfully.
4. WC Shipping Rates Importer Export Screen.

== Changelog ==

= 1.1.0 =
* Applied fix provided by @kingfisher64 & @jphase (see https://wordpress.org/support/topic/error-cant-activate-plugin-2/)
* updated version format	
* added notification on plugin admin page, if export/import folders are non-writable


= 1.0.0 =
* First release