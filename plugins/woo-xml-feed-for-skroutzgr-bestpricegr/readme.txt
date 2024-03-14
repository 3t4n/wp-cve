=== WooCommerce XML feed for Skroutz & Bestprice ===
Plugin URI: https://www.papaki.com
Description: XML feed creator for Skroutz & Best Price
Requires at least: 4.7
Tested up to: 5.9.1
Stable tag: 1.6.9.1
Contributors: enartia,g.georgopoulos,georgekapsalakis,akatopodis
Author URI: https://www.papaki.com
WC tested up to: 6.2.1
Tags: ecommerce, e-commerce,  wordpress ecommerce, xml, feed
License: GPLv3 or later
License URI: http://www.gnu.org/licenses/gpl-3.0.html

Create Skroutz and Bestprice XML feeds for Woocommerce

== Description ==

With this plugin you can create XML feeds for Skroutz and Bestprice. Based on original plugin "Skroutz.gr & Bestprice.gr XML Feed for Woocommerce By emspace.gr" [https://wordpress.org/plugins/woo-xml-feed-skroutz-bestprice/]

Since the 1.6.0 version is a major release, if you face any issues, choose the "Rollback to previous version" option (in order the XML feeds to be produced the old way.) and contact us at wordpress@enartia.com to resolve your issues


== Frequently Asked Questions ==

= When in Stock Availability =
Dropdown  option "When in Stock Availability"   with options will show for all in Stock products 
"Available in store / Delivery 1 to 3 days", "Delivery 1 to 3 days", "Delivery 4 to 10 days" as availability

= If Product Attribute: Availability is used =
Dropdown  option "When in Stock Availability" value "Product Attribute: Availability" must be used
(the attribute must have slug "availability")

= If Custom Availability plugin is used =
Dropdown  option "When in Stock Availability" value "Custom Availability" must be used

= If a Product is out of Stock =
Dropdown  option "If a Product is out of Stock"  with options will 
"Include as out of Stock or Upon Request" or "Exclude from feed", "Delivery 1 to 3 days", "Delivery 4 to 10 days"

= If Product Attribute: Out of Stock Availability is used =
Dropdown  option "If a Product is out of Stock or on backorder" value "Product Attribute: Out of Stock Availability" must be used
(the attribute must have slug "outofstockavailability")

= Add mpn/isbn to product =

To add mpn/isbn to the product just fill in the SKU field of WooCommerce (default choice) or choose from the dropdown MPN field the desired option

= Exclude mpn data from an item =

If you want to send empty mpn field for an item, you can add a special field in the product edit area "excludempn" with value "yes".

= Add color =

To add the color to a product , in order to be printed on the XML feed add an attribute with Slug "color" , Type "Select" and Name of your choice

= Add manufacturer =

To add the manufacturer to a product , in order to be printed on the XML feed add an attribute with Slug "manufacturer" , Type "Select" and Name of your choice

OR

Brands plugins are supported to be shown as manufacturer.


= Add sizes =

To add the size to a product, in order to be printed on the XML feed, add an attribute with Slug "size", Type "Select" and Name of your choice. 
Then is created a variable product with this attribute.

If you have stock management enabled on variations, sizes with stock lower or equal to 0 will not be shown on the feed

= Remove item from feed =

If you want to remove items from the feed, you can add a special field in the product edit area "onfeed" with value "no".



= Backorder =
If you have enabled backorder and set to notify, the product will be shown as upon order and not in stock. 

If you have selected Yes, the product will be shown as available and in stock. 

If you have selected no to backorder, the product will be not available. 

= GTIN plugins support =

If you want to add extra gtin tag (ean, barcode, isbn) in your xml, you can enable the "Enable GTIN Feed" option in the admin panel and then, to select the preferred option of the tag and the  GTIN Source Plugin (either the name of the plugin or the name of the field)

= Split Variable products based on color attributes = 
If you want to split your products based on color attribute you should check the "Split variable products by color" option

= Custom Product Id = 
If you want to have a custom product id (and not the default id from WooCommerce) you can create a special field in the product edit area i.e. "custom_product_id" or to choose from other meta fields that are available. 
If that field has a value, the product will have this for id or else if it has no value that field, product will have the default id as value in the XML. 
In order to disable it, just choose the -default- option. 

= Exclude categories from XML = 
You can add from which categories you want to exclude products from the XML Feed	

= Calculate taxes on product's price = 
Prices should have included VAT. If you have set up your prices without taxes, choose the "Auto Calculate Price with Tax" in order to auto calculate the price with the tax.

= Product with multiple categories = 
When a product has multiple categories, it will search for final categories and build the path of one of them. 
If it hasn't any final category and product has only parent categories, it will build the path of one of them. 
(In all paths, has been added the "Home", in order skroutz validator to not throw warning for partial path in case of parent category path)

= Description tag = 
In order to have the description Tag in your xml, you have to add the description in the short description field in your product.

== Changelog ==
= Version 1.6.9.1 =
compatibility with Woocommerce 6.2.1 and wordpress 5.9.1

= Version 1.6.9 =
compatibility with Woocommerce 5.0.0

= Version 1.6.8 =
Added description field on skroutz xml

= Version 1.6.7 =
Render the weight field in xml only if it is greater than 0

= Version 1.6.6 =
added the option to choose the source of the mpn field
added the ability to exclude mpn data from an item with the use of special field "excludempn"
added split variation product functionality and in case product has only one color
render all (parent) product's sizes if in the variation has only set the color attribute and has "any size" as the other option

= Version 1.6.5 =
Added more availability status and custom availability option per product, for out of stock or on backorder products
fix compatibility with brand plugins and variable products

= Version 1.6.4 =
fix select2 conflict with some templates
fix issue with weight and different units
apply exclude from feed in variable products
update availability statuses  

= Version 1.6.3 =
Changes in category path when product has multiple categories
Display category id in BestPrice xml 


= Version 1.6.2 =
Fix an issue with custom product id and xml production

= Version 1.6.1 =
Don't display color tag in xml if there is no value 
Fixes a conflict with select2 library

= Version 1.6.0 = 
Perfomance improvement
Split variable product based on color attribute
Option to calculate taxes on price
Option to set Custom Product Id
Exclude Categories from XML Feed


= Version 1.5.0 = 
Enabled support for GTIN plugins (Now you can have extra field for your ean/barcode/isbn attribute)
Fixed issues with Availability as product attribute

= Version: 1.4.3 =
Fixed issue with size in variable products.

= Version: 1.4.2 =
Updated Additional Images format for Bestprice(xml specs v.2.0.3)

= Version: 1.4.1 = 
Updated translations. 

= Version: 1.4.0 = 
Additional Images are now supported
Can now set attributes(Size, color, Manufacturer) to empty if you don't want to import them in the xml
Fix issue for Brand Plugins to be shown as manufacturer
Fix Weight issue if the weight was under 1kg

= Version: 1.2.1 =
Correct handling for variable products stock

= Version: 1.0.2 =
WooCommerce 3.0 compatibility.

= Version: 1.0.0 =
Initial Release



