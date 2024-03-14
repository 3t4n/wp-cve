=== AMB Variable Affiliate Products for WooCommerce ===
Contributors: wpguild
Tags: variable affiliate products, variable external products, affiliate variable products, external variable products, woocommerce, products, variable products, affiliate products, external products
Requires at least: 4.9.8
Tested up to: 6.3
Requires PHP: 7.0
Stable tag: 1.0.2
License: GPL-2.0+
License URI: http://www.gnu.org/licenses/gpl-2.0.txt

Make variable products behave like External/Affiliate products. Make the buy button redirect to your affiliate, and customize the buy button text!

== Description ==
Make your WooCommerce variable products behave just like affiliate/external products! Super simple to set up:

1. Enable the 'Affiliate Product' option in the 'Inventory' tab of your Variable Product.
2. Put in the product URL and optionally customize the cart text.
3. Optionally add affiliate/external URLs for each variation in the 'Variations' tab.

== Installation ==
There are 2 ways to install AMB Variable Affiliate Products for WooCommerce:

### From the repo

Navigate to Plugins -> Add New, search for "AMB Variable Affiliate Products for WooCommerce", install and activate.

### Manually install

Download the ZIP file from the plugin repo, then navigate to Plugins -> Add New -> Upload Plugin in your WordPress admin dashboard. Upload the plugin and activate.

This guide is helpful: https://www.wpbeginner.com/beginners-guide/step-by-step-guide-to-install-a-wordpress-plugin-for-beginners/.
1. Enable the 'Affiliate Product' option in the 'Inventory' tab of your Variable Product.
2. Put in the product URL and optionally customize the cart text.
3. Optionally add affiliate/external URLs for each variation in the 'Variations' tab.

### Set up after install

After the plugin is installed, you'll find a new option "Affiliate Product" in the "Inventory" tab for your variable products. Just enable that option, put in your affiliate product URL, and optionally customize the cart text. You can also optionally add individual affiliate URLs for each variation in the "Variations" tab.

== Frequently Asked Questions ==

= How can I import Variable Affiliate Products? =

Go to WooCommerce > Settings and enable the "Enable importing mode" option. Then, when importing the products, you'll need to import these custom fields:

- "_amb_vap_prod"            - import "yes" or "no.
- "_amb_vap_prod_url"        - import the parent product affiliate URL.
- "_amb_vap_prod_cart_text"  - import the text for the buy button.
- "_amb_wpvap_variation_url" - import the variation affiliate URL.
 
= Can I leave the parent product URL empty? =
 
No, the parent product URL is required if "Affiliate Product" is enabled. This URL is used as a fallback URL in the case that a variation that's added to the cart does not have an individual affiliate URL.

= Do my products still go into the shopping cart? =

No, if "Affiliate Product" is enabled, the item will automatically be removed from the cart, the cart notice message will be dismissed, and the user will be redirected to the affiliate URL.

== Screenshots ==
1. The "Affiliate Product" option.
2. After enabled.
3. Variation URLs.

== Changelog ==
= 1.0.0 =
* Initial release.

= 1.0.1 =
* Fix readme
* Remove assets folder from plugin

= 1.0.2 =
* Fixed importing option for product types that aren't "variable" or "variation".
