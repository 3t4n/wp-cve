=== Simple Buy Now Button for PayPal ===
Plugin Name: Simple Buy Now Button for PayPal
Plugin URI: http://www.wpcodelibrary.com/
Author: wpcodelibrary
Author URI: #
Contributors: wpcodelibrary
Stable tag: 1.1.1
Tags: ecommerce, button, paypal, buynow, paypal buynow, shortcode, sidebar, widget
Requires at least: 3.8
Requires PHP: 5.2.4
Tested up to: 6.0
Donate link: #
Copyright:  
License: GPLv3 or later
License URI: http://www.gnu.org/licenses/gpl-3.0.html

Create your own PayPal Buy Now as many as you want as per your need in simple way.

== Description ==
Simple PayPal Buy Now button plugin allows you to add PayPal Buy Now button to your site using shortcode.

= Plugin Functionality: =
* Create PayPal  button
* Place PayPal Buy Now button shortcode on any post and page.
* Place PayPal Buy Now button shortcode on widget.
* Place PayPal Buy Now button shortcode in template file.

= Shortcode =
<code>[wpcsimple_button email='yourpaypalemail@example.com' size='large' amount ='15.00' name='T-shirt' currency_code= 'USD']</code>
<code><?php echo do_shortcode('[wpcsimple_button]'); ?></code>
= Currency Codes = 
* 		'AUD' => 'Australian Dollars (A $)'
*        	'BRL' => 'Brazilian Real'
*        	'CAD' => 'Canadian Dollars (C $)'
*        	'CZK' => 'Czech Koruna'
* 			'DKK' => 'Danish Krone'
*       	'EUR' => 'Euros (€)',
*       	'HKD' => 'Hong Kong Dollar ($)'
*      		'HUF' => 'Hungarian Forint'
*       	'ILS' => 'Israeli New Shekel'
*			'JPY' => 'Yen (¥)'
*       	'MYR' => 'Malaysian Ringgit'
*			'MXN' => 'Mexican Peso'
*    		'NOK' => 'Norwegian Krone'
*      		'NZD' => 'New Zealand Dollar ($)'
*     		'PHP' => 'Philippine Peso'
*     		'PLN' => 'Polish Zloty'
*     		'GBP' => 'Pounds Sterling (£)'
*     		'RUB' => 'Russian Ruble'
*     		'SGD' => 'Singapore Dollar ($)'
*     		'SEK' => 'Swedish Krona'
*    		'CHF' => 'Swiss Franc'
*     		'TWD' => 'Taiwan New Dollar'
*     		'THB' => 'Thai Baht'
*     		'TRY' => 'Turkish Lira'
*    		'USD' => 'US Dollars'

= Shortcode parameter = 
* email => Your email address associated with your PayPal account. 
* name => Description of item being sold.
* amount => The amount associated with item 
* currency_code => The currency of the payment. Default is USD.
* lc = > The locale of the checkout login or sign-up page. for example en_GB
* paymentaction => sale or authorization.
* return => The URL to which PayPal redirects buyers' browser after they complete their payments.
* cancel_return =>  URL to which PayPal redirects the buyers' browsers if they cancel checkout before completing their payments.

= Try our other premium plugins : =
1. <a href ="https://codecanyon.net/item/woocommerce-custom-related-products-pro/17893664?s_rank=2&ref=wpcodelibrary">Woocommerce Custom Related Products Pro</a>
2. <a href ="https://codecanyon.net/item/woocommerce-estimated-delivery-date-per-product/18309929?s_rank=1&ref=wpcodelibrary">Woocommerce Estimated Delivery Date Per Product
</a>
3. <a href ="https://codecanyon.net/item/woocommerce-advanced-discounts-and-fees/19009855">WooCommerce Advanced Discounts and Fees</a>
4. <a href ="https://codecanyon.net/item/cashback-coupon-for-woocommerce/28516866">Cashback Coupon for WooCommerce</a>
5. <a href ="https://codecanyon.net/item/dynamic-pricing-per-product-for-woocommerce/29117132">Dynamic Pricing Per Product for WooCommerce
</a>
== Installation ==

* Download the plugin
* Upload the folder "simple-paypal-buynow-button" to wp-content/plugins (or upload a zip through the WordPress admin)


== Frequently Asked Questions ==

= Installation Instruction =

* Place shortcode [wpcsimple_button] to display Buy Now button.


== Screenshots ==
1. PayPal Buy Now button shortcode in backend
2. PayPal Buy Now button shortcode in front page


== Upgrade Notice ==

= 1.0 =
Automatic updates should work great for you.  As always, though, we recommend backing up your site prior to making any updates just to be sure nothing goes wrong.
                     

== Changelog ==
= 1.0.0 =
* First Version.