=== PriceList for WooCommerce ===
Contributors: cherrymountains, Simbaclaws, jordibieger
Tags: woocommerce, pricelist, price, list, overview, woo, commerce, innerjoin
Requires at least: 5.0
Tested up to: 6.1
Stable tag: 1.1.0
Requires PHP: 7.0
License: GPLv3
License URI: https://www.gnu.org/licenses/gpl-3.0.html

With this plugin you'll be able to generate price lists for your WooCommerce products and display them in a overview or create a PDF.

== Description ==
PriceList for WooCommerce allows you to easily create a price list of all the products in your store.
Creating an easy overview of the products image, name, description and price.

By default it creates a HTML table, but if desired it can instead create a PDF file that your customers can download.

Simply add the shortcode [pricelist] to the post or page where you want to display the price list.

== Frequently Asked Questions ==

= Does this work with all products? =

This plugin works with all published and not password protected products in WooCommerce. Only the main product from a grouped product or variable product will be displayed.

= Does this plugin have a Pro version? =

Yes it does! Get it here: https://inner-join.nl/product/price-list-pro/

These are some of the Pro features:
* Customers can easily click on the product's name in order to go to it's store page. (links for products inside HTML and PDF output)
* See the old price and the current sale price of a product.
* Full support for grouped and variable products.
* A option to include a toggle button to switch between HTML output or download PDF button output.
* Set a word limit for when the product description gets cut off, including the ability to create a read more text link with the text you decide to display.
* A new shortcode parameter called generate_url that allows you to return a URL from the shortcode (plaintext) for usecases such as creating custom links and buttons towards the downloadable PDF. (Can obviously only be used with output PDF)
* Dynamic URL generation on the Settings page. Where a URL is generated from the current global settings you've selected. So that it can easily be copy and pasted for those specific settings. Instead of having to rely on the shortcode with generate_url.
* The ability to change the language of specific components.
* The ability to display the product SKU, Stock Status and Stock Quantity.
* Setup your own CSS for the generated tables.
* The ability to add social media sharing buttons.

== Usage ==
Add the shortcode `[pricelist]` to the post or page where you want to display the price list.

The following shortcode parameters are available. These override the settings in the Settings page so you can place multiple different shortcodes:

= output =
* html, pdf or dl `[pricelist output="pdf"]`
* Embed the price list as HTML on the current web page (`html`), or display a button to generate and display (`pdf) or download (`dl`) a price list PDF. 

= company =
* any text `[pricelist company="My Company"]`

= name =
* any text `[pricelist name="Price List"]`

= table_header_color =
* any hex code `[pricelist table_header_color="82AAD7"]`

= table_color =
* any hex code `[pricelist table_color="FFFFFF"]`

= description =
* true or false `[pricelist description="false"]`

= short_description =
* true or false `[pricelist short_description="false"]`

= product_image =
* true or false `[pricelist product_image="true"]`

= category_description =
* true or false `[pricelist category_description="true"]`

= category_image =
* true or false `[pricelist category_image="true"]`

= page =
* any text `[pricelist page="Page"]`

= date1 =
* 0 = Hide, 1 = Day, 2 = Month, 3 = Year `[pricelist date1="1"]`

= date2 =
* 0 = Hide, 1 = Day, 2 = Month, 3 = Year `[pricelist date2="2"]`

= date3 =
* 0 = Hide, 1 = Day, 2 = Month, 3 = Year `[pricelist date3="3"]`

== Installation ==
From your WordPress dashboard:
* Go to Plugins->Add New
* Search for Woocommerce Pricelist
* Click on Install Now
* Activate by either pressing on Activate Now or by going to the Plugins page and activating it there.

From WordPress.org:
* Go to Plugins
* Search for Woocommerce Pricelist
* Download the zip file
* Open the zip file and upload the [woocommerce-pricelist] folder to the [/wp-content/plugins/] directory.
* Activate the plugin from the Plugins page (Plugins->Installed Plugins).

== Screenshots ==

1. This is the HTML overview of one category of products being displayed with their images and descriptions.
2. This is the PDF file that is created when someone clicks on the download PDF button, product image and product description options set to Show.
3. The Settings page with all the default settings.

== Changelog ==

= 1.1.0 =
* Category support improved. Products can now appear in multiple categories, and category order is (by default) determined by the order set in WooCommerce (or the category ID if order is not set).
* No longer showing products that are supposed to be hidden (e.g. draft, trash, private, and non-catalog products). Only published catalog products are now shown.
* Codebase overhaul.

= 1.0.9 =
* Small bugfixes related to display of categories and images in rare occasions.

= 1.0.8 =
* Added support for displaying non-numeric prices (setting non-numeric prices requires a separate third-party plugin).
* Added support for displaying HTML and shortcodes in (short) descriptions.
* Cutting off (short) descriptions is now optional in the Pro version. 
* Added option for having visitors download the price list PDF, rather than displaying it in the browser.
* Improved price list PDF generation speed.
* Document properties of generated PDFs now include more information (e.g. authored by your company).
* Fixed bugs with Settings and display on narrow devices (e.g. mobile).

= 1.0.7 =
* Security and robustness improvements.

= 1.0.6 =
* Added a multi-language font to the PDF.

= 1.0.5 =
* Added shortcode parameter overrides (allows for creating individual shortcodes on different pages).
* Added the ability to have Cyrillic, Hebrew, Asian and Arabic text inside of the generated PDFs.
* Removed Read More option, this is a Pro feature that didn't yet work in the free version.

= 1.0.2 =
* Fixed bug with images and price not being displayed correctly

= 1.0.1 =
* Release of the plugin, supporting WordPress 5.5

= 1.0.0 =
* First release of the plugin

== Upgrade Notice ==

= 1.1.0 =
* Supports Wordpress 6.1 and WooCommerce 7.1

= 1.0.9 =
* Pro version supports automatic updates
* Supports Wordpress 6.0 and WooCommerce 6.7

= 1.0.8 =
* Supports Wordpress 5.9

= 1.0.7 =
* Supports Wordpress 5.8

= 1.0.5 =
* Supports Wordpress 5.7 and WooCommerce 5.4

= 1.0.3 =
* If you are activating the Pro plugin, please make sure to deactivate the Free plugin beforehand.

= 1.0.1 =
* Supports WordPress 5.5

= 1.0.0 = 
* First release of the plugin