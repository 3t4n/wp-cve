=== Plugin Name ===
Contributors: findshorty
Tags: woocommerce, email, testing, control, embed images, product categories, sku, category, woo, images
Requires at least: 4.0
Tested up to: 4.5
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Get better control of your Woocommerce emails. Add product images & embed them in emails. Test emails in your browser and via email.

== Description ==

This enhancement to the Woocommerce email system allows you to get more control of your Woocommerce emails.

* Add product images of any size - *(requires Woocommerce 2.6+)*
* Embed and attach email images
* Easier selection of email header image (replaces the Woocommerce default functionality)
* Display product categories for each line item
* Display product SKU for each line item
* Test your emails either in your browser or emailed to your email address using data from real orders

It is compatible with either the standard email templates, or your custom templates, as long as you retain the correct hooks and filters used by the standard templates.

The plugin also gives you the option of attaching and embedding any images within the email, including the header image if you have chosen one.
This prevents the user having to "load images" when they receive the email, reduces the chances of your emails being considered as spam with most providers, and prevents your images becoming "not found" if the user or your website are offline.

*Be careful not to overuse large embedded images as it does increase the size of your emails.*

Woo Email Control also provides a very useful testing facility, whereby you can either view any email directly within the browser, or send it to your email address. The preview emails contain live data for any order, not simply the empty template - great for checking your custom email templates without having to create or edit a new test order each time.

**Please note** This is an Add-on for Woocommerce and requires Woocommerce 2.5+ (2.6+ for product images)

== Installation ==

1. Upload the plugin files to the `/wp-content/plugins/woo-email-control` directory, or install the plugin through the WordPress plugins screen directly, using
the full unzipped plugin file.
2. Activate the plugin through the 'Plugins' screen in WordPress
3. To configure your Woocommerce emails, use the Woocommerce -> Settings -> Email tab. The extended settings are towards the bottom of the main settings form.
4. For testing individual emails, click on the email from the list on the main settings page, and the section marked "Test this email template" is at the bottom.


== Frequently Asked Questions ==

To change the category separator when displaying product categories (default " > ", add the following filter to your functions.php file.

	add_filter('wooctrl_category_separator','my_wooctrl_category_separator');
	function my_wooctrl_category_separator($sep) {
		return " : "; // you can replace this string with anything you like. Best with spaces!
	}

To change the html wrapper for the category list, add the following filters to functions.php.

	add_filter('wooctrl_category_wrapper_start','my_wooctrl_category_wrapper_start');
	add_filter('wooctrl_category_wrapper_end','my_wooctrl_category_wrapper_end');
	
	function my_wooctrl_category_wrapper_start($str) {
		return "<p style="color:#ccc">";
	}
	function my_wooctrl_category_wrapper_end($str) {
		return "</p>";
	}

== Changelog ==

= 1.061 =

* Fixed preview checkbox val

= 1.06 =

* Added ability to preview settings without saving
* Couple of php notices and warnings removed

= 1.05 =

* Changed the constructor

= 1.041 =

* Included missed css and img

= 1.04 =

* Fixed categories displaying regardless of setting
* Fixed SKU as above
* Removed php warning for display_product_cats 

= 1.03 =
* Added ability to display Product Categories - see FAQs
* Added ability to display SKU after product name
* Added FAQ

= 1.02 =
* Added nonce check to email test form

= 1.01 =
* Added improved header image selection for Woocommerce emails (replacing default)
* Added global email type selection - allows you to set the email type for all registered Woocommerce emails

= 1.0 =
* Initial version