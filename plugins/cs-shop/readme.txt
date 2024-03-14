=== CS Shop ===
Contributors: cottonspace
Tags: ad,ads,advertising,affiliate,shortcode,yahoo,amazon
Requires at least: 3.0
Tested up to: 4.9.4
Stable tag: 1.2.2

Easy to create a affiliate products page of affiliate services in Japan.

== Description ==

Easy to create a affiliate products page of affiliate services in Japan.

Simply by writing a short code, a shopping mall will be created on your site.

This plugin is only support affiliate services of Japan.

[Description in Japanese is here.](http://www.csync.net/wp-plugin/cs-shop/cs-shop-readme/)

== Installation ==

1. Upload `cs-shop` directory to the `/wp-content/plugins/` directory.
1. Activate the plugin through the 'Plugins' menu in WordPress.
1. Select the setting of 'CS Shop' from the Admin menu, Please save to set the account information for the affiliate service.
1. Create a new page, Please write a short code `[csshop service="rakuten"]` to that page. (This is the case you select the Rakuten.)
1. Please visit the page that you created. Categories of the affiliate service that you set should be displayed.

This is the most simple configuration steps. Short code to a variety of attributes can be set. Please read 'Shortcode Example' section.

== Changelog ==

= 1.2.2 =
* Tested up to 4.9.4.

= 1.2 =
* Support Rakuten API 2014-02-22 version.

= 1.1.1 =
* Display "Search" button, when not support JavaScript.

= 1.1 =
* Change query param "page" to "pagenum".

= 1.0 =
* Change from "+sales" to "-sales" the value of the sort attribute.

= 0.9.9 =
* Add "embed" mode.

= 0.9.8 =
* Add support ValueCommerce.

= 0.9.7 =
* Add support LinkShare.
* Remove "action" parameter from shortcode attributes.

= 0.9.6 =
* Add support Yahoo! Shopping API.

= 0.9.5 =
* Add support Amazon Product Advertising API.

= 0.9.4 =
* Fixed bug that failed to download by the execution environment.
* Fixed a bug does not work on sites that do not have to configure the settings of Permalink.

= 0.9.3 =
* When the content is empty, Added a logic that does not cache.

= 0.9.2 =
* Add Cache feature using Cache_Lite.

= 0.9.1 =
* Add Timeout and Retry feature for service access.

= 0.9 =
* The first release version.

== Upgrade Notice ==

= 1.1 =
Change the name of page param.

= 1.0 =
Change the value of the sort attribute.

= 0.9.9 =
Add "embed" mode.

= 0.9.8 =
Support ValueCommerce.

= 0.9.7 =
Support LinkShare.

= 0.9.6 =
Support Yahoo! Shopping.

= 0.9.5 =
Support Amazon service.

= 0.9.4 =
Fixed lots of bugs.

= 0.9.3 =
This is a simple fix of source code.

= 0.9.2 =
Reduce the number of queries, and improve response time.

= 0.9.1 =
If you have problem which the search results is not displayed, which may be resolved.

= 0.9 =
The first release version.

== Shortcode Example ==

Show the products which have the keyword 'foo' automatically.

`[csshop service="rakuten" keyword="foo"]`

`[csshop service="amazon" keyword="foo"]`

`[csshop service="yahoo" keyword="foo"]`

`[csshop service="linkshare" keyword="foo"]`

`[csshop service="valuecommerce" keyword="foo"]`

Show the products of specified category automatically.

`[csshop service="rakuten" category="100026"]`

`[csshop service="amazon" category="Electronics"]`

`[csshop service="yahoo" category="2505"]`

`[csshop service="linkshare" category="Electronics"]`

`[csshop service="valuecommerce" category="electronics"]`
