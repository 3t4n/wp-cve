=== WP Shopify ===
Contributors: fahadmahmood
Tags: Shopify
Requires at least: 4.3 
Tested up to: 6.4
Stable tag: 1.4.4
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Display products from your Shopify store
on your WordPress blog using shortcodes.

== Description ==

Display Shopify products on your WordPress blog.

How it works?

A) Set up all the apis as directed by the App
B) Set up two new pages on your WordPress website

Page #1 Set the permalink to shopify (or products, shop, catalogue etc.) and add code [wp-shopify type="products" limit="100" url-type="default"]

Page #2 Set the permalink to product (note there is no "s" at the end of the product in the url, the slug/permalink should be with exactly "product") and insert code [wp-shopify-product] (this is where product redirect link to shopify store will work on your WordPress website)

C) Modify the layout of your WordPress website pages with the CSS

== Installation ==

Note: This is a two-part install; you have to do some configuration on your Shopify store admin, then you must install code on your WordPress site. 

In your Shopify admin, do the following: 

1. Login to the Shopify 2 Admin Panel.
1. Go to Apps, then click the "Manage private apps" link at the bottom of the page. 
1. Click the "Generate API credentials" button.  
1. Set the Private app name to "Product Display" and be sure you have Read access to Products, variants and collections.  
1. Click the save button, and note your Private app API key and Password.

Install the WordPress part of this mod as usual (using the Install button 
on the mod page on WordPress.org).  The follow these steps: 

1. In your WordPress admin, do the following: 
- In Plugins->Installed Plugins, click the "Activate" link under WP Shopify.
- In Settings->WP Shopify, set your Shopify URL, API Key and password.  

To show a specific product on your blog, use the shortcode 
[wp-shopify-product] with parameter "id" as a self closing tag.  
So showing product 617935406 would be done as follows: 

[wp-shopify-product id="617935406"]

The id is shown in the URL when you edit a product in your admin.

== Frequently Asked Questions ==


== Screenshots ==

1. Single Product
2. Shop/Collection
3. Settings

== Changelog ==
= 1.4.4 =
* Updated description with the helpful guidelines. [Thanks to @kira01][28/01/2024]
= 1.4.3 =
* Translatable strings. [Thanks to @bichareh][11/08/2023]
= 1.4.2 =
* Translatable strings. 03/08/2023 [Thanks to @bichareh]
= 1.4.1 =
* Accessibility feature revised. 10/03/2023 [Thanks to JAMES MENENDEZ / FKQ]
= 1.4.0 =
* Accessibility feature added. 01/03/2023 [Thanks to JAMES MENENDEZ / FKQ]
= 1.3.9 =
* Video tutorial removed from the support tab. 06/02/2023 [Thanks to Joshua Stokes & Andrew Robbins]
= 1.3.8 =
* Back to shop link added through the shortcode on single product page. 20/12/2022 [Thanks to KC Clark]
= 1.3.7 =
* JS based add to cart button included in the shortcodes as a new attribute "button_type". 14/10/2022 [Thanks to Roman von Contzen]
= 1.3.6 =
* Search filter attribute added to the collection based shortcode. 22/06/2022 [Thanks to KC Clark]
* url-type and thumb-size attribues added to the collections shortcode. 14/09/2022 [Thanks to James Menedez]
= 1.3.5 =
* Settings page css tweaks. 19/06/2022 [Thanks to KC Clark]
= 1.3.4 =
* Capri theme added in premium version. 14/05/2022 [Thanks to Dylan Ence]
= 1.3.3 =
* GraphQL implemented with access token. 17/04/2022 [Thanks to Maria Abad-Guillen]
= 1.3.2 =
* Stylesheet path fixed for shotcodes function. 14/04/2022 [Thanks to kamasi]
= 1.3.1 =
* Updated version with help tab.
= 1.3 =
* Updated version for WordPress.
= 1.2 =
* Fatal error fixed. [Thanks to @adabob and @andrewmrobbins]
= 1.1 =
* Shortcode improved with two more attributes. [Thanks to lakewebworks]
= 1.0.1 =
* Languages added. [Thanks to Abu Usman]
= 1.0.0 =
First version

== Upgrade Notice ==
= 1.4.4 =
Updated description with the helpful guidelines.
= 1.4.3 =
Translatable strings.
= 1.4.2 =
Translatable strings.
= 1.4.1 =
Accessibility feature revised.
= 1.4.0 =
Accessibility feature added.
= 1.3.9 =
Video tutorial removed from the support tab.
= 1.3.8 =
Back to shop link added through the shortcode on single product page.
= 1.3.7 =
JS based add to cart button included in the shortcodes as a new attribute "button_type".
= 1.3.6 =
Search filter attribute added to the collection based shortcode.
= 1.3.5 =
Settings page css tweaks.
= 1.3.4 =
Capri theme added in premium version.
= 1.3.3 =
GraphQL implemented with access token.
= 1.3.2 =
Stylesheet path fixed for shotcodes function.
= 1.3.1 =
Updated version with help tab.
= 1.3 =
Updated version for WordPress.
= 1.2 =
Fatal error fixed.
= 1.1 =
Shortcode improved with two more attributes.
= 1.0.1 =
Languages added.
= 1.0.0 =
First version

