=== WooCommerce Cloak Affiliate Links ===

Contributors: datafeedr.com
Tags: woocommerce, affiliate, links, cloak, mask, redirect, external, url, urls, rewrite, datafeedr
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=9945A9PLQ7P46
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
Requires at least: 4.7.0
Tested up to: 6.4.2-alpha
Stable tag: 1.0.33

Cloak your WooCommerce external & affiliate links.

== Description ==

The *WooCommerce Cloak Affiliate Links* plugin allows you to mask all external links in your WooCommerce store.

For example, change this...
`merchant.com/index.php?aff_id=123&product_id=456`

... into this:
`yoursite.com/go/123`

Configure the status code for the redirect to either 301, 302 or 307.

The plugin also adds a "Disallow" to your robots.txt file to prevent bots from following those external links.

== Installation ==

This section describes how to install the plugin and get it working.

1. Upload the `woocommerce-cloak-affiliate-links` folder to the `/wp-content/plugins/` directory.
1. Activate the *WooCommerce Cloak Affiliate Links* plugin through the 'Plugins' menu in WordPress.
1. Configure the plugin by going to Settings > WC Cloak Links.

== Frequently Asked Questions ==

= What redirect status should I use, 301, 302 or 307? =

There's a lot of debate about this.  I would suggest Googling this and seeing which works best for you.  The default status is 302.

== Screenshots ==

1. Settings
2. Permalinks

== Changelog ==

= 1.0.33 - 2023/11/10 =
* Updated "tested up to" value
* Declared WooCommerce HPOS compatibility

= 1.0.32 - 2023/05/02 =
* Added 2 public properties to `Wccal` class.

= 1.0.31 - 2023/04/20 =
* Updated "tested up to" values.

= 1.0.30 - 2022/10/28 =
* Updated "tested up to" values.

= 1.0.29 - 2021/08/09 =
* Added `wccal_product_url_permalink` filter.
* Added `wccal_product_url_qs` filter.

= 1.0.28 - 2021/07/13 =
* Forgot one small change in last update.

= 1.0.27 - 2021/07/13 =
* Updated to support WordPress 5.8.
* Updated to support WooCommerce 5.5.

= 1.0.26 - 2020/03/08 =
* Updated to support WordPress 5.7.

= 1.0.25 - 2020/12/01 =
* Updated to support WordPress 5.6.

= 1.0.24 - 2020/09/30 =
* Updated to support WooCommerce 4.5.

= 1.0.23 - 2020/07/27 =
* Updated to support WooCommerce 4.3.

= 1.0.22 - 2020/03/11 =
* Updated to support WooCommerce 4.0.

= 1.0.21 - 2019/11/12 =
* Updated readme.

= 1.0.20 - 2019/08/20 =
* Updated for WooCommerce 3.7

= 1.0.19 - 2019/05/06 =
* Updated readme.

= 1.0.18 - 2019/04/23 =
* Updated readme.

= 1.0.17 - 2019/02/19 =
* Updated readme.

= 1.0.16 - 2018/12/07 =
* Updated readme.

= 1.0.15 - 2018/07/30 =
* Disables cloaking of URLs during WooCommerce Product Exporting.

= 1.0.14 - 2018/03/14 =
* Declared required and supported WooCommerce version.

= 1.0.13 - 2018/03/13 =
* Updated readme file.

= 1.0.12 - 2018/01/17 =
* Added README file for GitHub.

= 1.0.11 - 2017/11/30 =
* Fixed compatibility issue with WooZone.

= 1.0.10 - 2017/09/29 =
* Fixed woocommerce_product_class() so that Wccal_Product_External is returned when the admin-ajax.php is requested from the frontend.

= 1.0.9 - 2017/05/01 =
* Changed `woocommerce_clean()` to `wc_clean()`.

= 1.0.8 - 2017/04/18 =
* Fixed bug introduced with WooCommerce 3.0+ where affiliate link was being rewritten on product edit page in the WordPress Admin Area.

= 1.0.7 - 2017/03/20 =
* Changed `get_site_url()` to `home_url()` to fix [this error](https://wordpress.org/support/topic/im-getting-404-on-all-the-cloaked-links/#post-8936140).

= 1.0.6 - 2017/02/16 =
* Added `$context` argument to `get_product_url()` method to support changes in WooCommerce 2.7.

= 1.0.5 =
* Just updating readme.

= 1.0.4 =
* Added user_trailingslashit() function to returned redirect URL. (#9588)

= 1.0.3 =
* Updated 'tested up to' tag.

= 1.0.2 =
* Added "static" to static methods to meet Strict Standards.
* Updated plugin information.

= 1.0.1 =
* Changed author name to "datafeedr.com"
* Flushed rewrite rules on plugin activation/deactivation.

= 1.0.0 =
* Initial release.

== Upgrade Notice ==

*None*

