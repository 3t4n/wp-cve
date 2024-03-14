WooCommerce Ajax Mini Cart
Contributors: inconver
Donate link: https://inconver.com/
Tags: woocommerce, ajax, mini cart, 
Requires at least: 4.6
Tested up to: 5.1
Stable tag: 5.1
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Custom mini cart for WooCommerce. You can add to cart, update quanity in this cart via ajax. Also you can edit the style for this cart in the admin panel.

== Description ==

Custom mini cart for WooCommerce. You can add to cart, update quanity in this cart via ajax. Also you can edit the style for this cart in the admin panel.

<strong>Video Overview</strong>

<iframe width="560" height="315" src="https://www.youtube.com/embed/adt1zv8ZdFI" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>

<strong>Demo</strong>

<a target="blank" href="http://minicart.inconver.com">http://minicart.inconver.com</a>

<strong>About plugin</strong>

<a href="http://inconver.com/ajax-mini-cart/" target="blank">http://inconver.com/ajax-mini-cart/</a>

<strong>Features</strong>

<ul>
<li>Ajax add to cart</li>
<li>Auto open when click Add to cart or manual the button</li>
<li>Ajax change quantity or remove product</li>
<li>Full style change in admin panel</li>
<li>Reassigning plugin templates</li>
</ul>

<strong>For developers</strong>

You can use these js functions to show and update Ajax Mini Cart
public_woo_amc_show() - show Ajax Mini Cart
public_woo_amc_get_cart() - update Ajax Mini Cart

f.e. this code shows and updates Ajax Mini Cart in 3 seconds
setTimeout(function(){
	public_woo_amc_show();
	public_woo_amc_get_cart();
}, 3000);

== Installation ==

<ol>
<li>Please make sure that you installed WooCommerce (https://wordpress.org/plugins/woocommerce/)</li>
<li>Go to Plugins in your dashboard and select “Add New”</li>
<li>Search for “WooCommerce Ajax Mini Cart”, Install &amp; Activate it</li>
<li>Go to WooCommerce / Ajax Mini Cart and customize as you want</li>
<li>Now, whenever you add product into the cart, the mini cart will be show</li>
</ol>

== Frequently Asked Questions ==

== Screenshots ==

== Changelog ==

= 1.0 =
* First version.
= 1.0.1 =
* Fixed the problem with wp_verify_nonce().
= 1.0.2 =
* Added public js functions.