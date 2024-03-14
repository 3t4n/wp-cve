=== Donation Block For PayPal ===
Plugin Name: Donation Block For PayPal
Plugin URI: https://profiles.wordpress.org/bharatkambariya/#content-plugins
Author: bharatkambariya
Author URI: https://profiles.wordpress.org/bharatkambariya/
Contributors: bharatkambariya, kailanitish90, jankimoradiya, bansarikambariya, sagarprajapati
Stable tag: 2.1.0
Tags: donation-button, donation-block, button, donate, donation, paypal, paypal donation, paypal donation button, shortcode, sidebar, widget
Requires at least: 5.0
Tested up to: 6.1.1
Donate link: https://www.paypal.me/bharatkambariya
Requires PHP: 5.2.4
Copyright:
License: GPLv3 or later
License URI: http://www.gnu.org/licenses/gpl-3.0.html

Create PayPal Donation Buttons as per your need in very simple way.

== Description ==
Paypal donation block allows you to create dynamic PayPal Donation Buttons quickly and in a very easy way on your website.

Watch this 1 minute video of how the plugin works:

[youtube https://www.youtube.com/watch?v=-FF8dFRVuto]

= Plugin Functionality: =
* Sandbox/Live Mode
* Dynamic Amount of Donation
* All Currencies available
* Dynamic Button Size
* Dynamic Description
* Donation Record Page
* Donation Setting Page
* Donation Success/Failed Page
* Shortcode can be used in code too.

= Shortcode =
<code>[paypal_donation_block email ='yourpaypalemail@example.com' amount ='10' currency='USD' purpose='Charity for Child Health Care' mode='sandbox' suggestion='1, 5, 10, 20, 50, 100' ]</code>
<code><?php echo do_shortcode('[paypal_donation_block]'); ?></code>
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

== Installation ==

* Download the plugin
* Upload the folder "donations-block" to wp-content/plugins (or upload a zip through the WordPress admin)


== Frequently Asked Questions ==

= How to create PayPal donation button ? =

* Select donation button block from blocks list.
* Also you can place shortcode [paypal_donation_block]


== Screenshots ==
1. screenshot-1.jpg
2. screenshot-2.jpg
3. screenshot-3.jpg
4. screenshot-4.jpg


== Changelog ==

= 1.0.0
* Very first release which allows many dynamic options like Sandbox/Live mode, Amount, Button Size, etc.
= 1.1.0
* Security changes and upgrade stable version
= 2.0.0
* Add new option in donation block, donation setting page and donation record page at admin side and upgraded with wordpress current version
= 2.1.0
* Security changes and updated to boilerplate code

== Upgrade Notice ==

* There is a new version for boilerplate code
