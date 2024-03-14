=== Hide Categories On Shop Page ===
Contributors: WMEric
Donate link: https://www.paypal.me/matrixwd
Tags: Wordpress, WooCommerce, e-Commerce
Requires at least: 3.0.1
Tested up to: 6.1.1
Requires PHP: 7.0
Stable tag: 1.1.3
License: GPLv3 or later
License URI: http://www.gnu.org/licenses/gpl-3.0.html

Simple solution to hide specific categories in you woocommerce shop main page i.e. domain.com/shop This plugin was based on WC Hide Categories On Shop Page located at https://wordpress.org/plugins/wc-hide-categories-on-shop-page/ However it did not work and wasn't maintained.

== Description ==

This free WooCommerce extension permits you to hide categories on your shops main page.
For this you have to save the categories with ',' seperated in woocommerce > settings > products tab section.

In order for this to work you have to have Categories displayed. This can be set via Customizer. Appearance > Customize > WooCommerce > Product Catalog.

"Choose what to display on the main shop page." Select Show categories as seen in the Screenshot below.

== Installation ==

* Upload 'wc-hide-categories.zip' like any other plugin or upload the zip file contents to the '/wp-content/plugins/' directory
* Activate the plugin through the 'Plugins' menu in WordPress
* Configure the plugin at Dashboard > WooCommerce > Settings > Products > Hide Categories On Shop Page
* Save your settings with the category slugs with ',' seperated.
* I.E. cat1, cat2, cat3

== Frequently Asked Questions ==

= Does this work on Multisite? =

As of version 1.1.0 Yes

= I'd like to donate, how can I? =

Click the donate button to the right or go here https://www.paypal.me/matrixwd
And thank you in advance :) 
 
== Screenshots ==
 
1. New "Hide Categories on Shop Page" link under Product tab
2. Before Plugin
3. After Plugin
 

== Changelog ==

= 1.1.3 =
* Added missing line if foreach loop and fixed versioning numbers

= 1.1.2 =
* Updated Foreach() that was causing a PHP warning: Attempt to read property "slug" on int ... line 147

= 1.1.1 =
* Updated Readme

= 1.1.0 =
* Updated so that it now works on Wordpress Multisites
* Added new comments to the code so its easier to follow on what block does what
* Added the ability to hide sub categories
* Added the ability to hide all products under root category and sub category

= 1.0.1 =
* Added the conditionals to test if the shop is_home() or is_front_page() and added the a fourth test a user could uncomment to add their custom shop page slug.

= 1.0 =
* Stable initial release

== Upgrade Notice ==

= 1.0.1 =
 Users should use this version and disregard version 1.0

== Additional Information ==

For users who has a different/custom page for their shop you can edit the wc-hide-categories.php file
Find line # 127 and remove //

`//$mwd_opt4 = in_array( 'product_cat', $taxonomies ) && ! is_admin() && is_page('YOUR_PAGE_SLUG'),`

Then find line #129 `/*|| $mwd_opt4*/`

Change to
`|| $mwd_opt4`

To remove products from those categories find line 160

`// Uncomment the function below if you also want those products hidden
/*
	add_action( 'woocommerce_product_query', 'mwd_hwcosp_remove_product_in_cat' );
	
	function mwd_hwcosp_remove_product_in_cat( $q ) {
		//hwcosp_global is the databse row entry
		$opt_terms = get_option('hwcosp_global');
		
		// Processes our users data to the way we want it from above
		$data = mwd_hwcosp_comma_separated_to_array($opt_terms);
		
		$tax_query = (array) $q->get('tax_query');
		$tax_query[] = array(
							 'taxonomy' => 'product_cat',
							 'field' => 'slug',
							 'terms' => $data, // Set Category Slug which products not show on the shop and Archieve page.
							 'operator' => 'NOT IN'
							);
		$q->set( 'tax_query', $tax_query );
	}
*/`

The code is well documented so its easy to find what part you are looking for

If you have any question please ask in the support forum, Thanks 