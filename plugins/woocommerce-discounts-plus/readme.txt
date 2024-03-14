=== Discounts Manager for Products ===
Contributors: fahadmahmood
Tags: discounts, percentage, cart discount, s2Member, order discount, woocommerce discount, criteria
Requires at least: 3.5
Tested up to: 6.4
Stable tag: 3.5.1
Requires PHP: 7.0
License: GPLv3
License URI: http://www.gnu.org/licenses/gpl-3.0.html

An amazing WooCommerce extension to implement multiple discount criterias and its compatible with s2Member plugin as well.

== Description ==
* Author: [Fahad Mahmood](https://www.androidbubbles.com/contact)
* Project URI: <http://androidbubble.com/blog/wordpress/plugins/woocommerce-discounts-plus>

An amazing WooCommerce extension to implement multiple discount criterias with ultimate convenience.

Discounts Manager for Products is compatible with WooCommerce and s2Member Plugin.

= Tags =
woocommerce, discounts, percentage, s2Member, sales, memership, weight, membership levels, cart discounts, quantity

== Video Tutorials ==

= 1) Overview: =
[youtube http://www.youtube.com/watch?v=8j7gRzoHZdc]

= 2) Setup membership levels and discount criteria with WooCommerce =
[youtube http://www.youtube.com/watch?v=plIK2MTgB5E]

== Some examples of usage ==

= If the customer orders more than 5 items of a given product =
*   You may want to feature the following discount policy in your store: if the customer
orders more than 5 items of a given product, he/she will pay the price of this order
line lowered by 10%.

= Offering a 5% discount if the customer orders more than 10 items =
*   Or you may want a different policy, for example offering a 5% discount if the customer
orders more than 10 items of a product and a 10% discount if he/she orders more than
20 items.

= When the customer orders more than 10 items (say, 15, 20, etc.) =
*   Discounts Plus supports flat discounts in currency units as well,
enabling you to handle scenarios like deducting fixed value of, say $10 from the item subtotal.
For example, when the customer orders more than 10 items (say, 15, 20, etc.), a discount of $10
will be applied only on the subtotal price.

The settings for discounts are simple yet extensive, allowing wide range of discount
policies to be adopted in your store.

###Here is the list of the main features:

*   Possibility of setting percentage Discounts Plus or flat (fixed) Discounts Plus in currency units.
*   Discounts Plus for product variations is supported to treat them separately or by shared quantity when discounting. 
*   Discount is better visible and is available on several locations (see below).
*   Discount is visible on the Checkout page
*   Discount is visible on the Order Details page
*   Discount is visible in WooCommerce order e-mails and invoices as well.
*   Showing the applied discount when hovering over the item price in the cart.   
*   Possibility of easily changing the CSS of the price before and after discount.
*   Discounts Plus can or cannot be applied if a coupon code is used, depending on configuration.
*   HTML markup is allowed in information about the Discounts Plus offer in Product Description.
*   Discounts Plus can be disabled more easily in the Product Options page.
*   Compatibility with WooCommerce 2.0.x, 2.1.x, 2.2.x.

** If you like Discounts Manager for Products, please also check out more premium plugins: **



== Installation ==

1. Download the latest version and extract it in the /wp-content/plugins/ directory
2. Activate the plugin through the 'Plugins' menu in WordPress

Once the plugin is activated, you can use it as follows:

1. First navigate to WooCommerce settings. Under the Discounts Plus tab, find the global
configuration for Discounts Plus. Make sure "Discounts Plus Enabled" is checked and optionally
fill information about discounts which will be visible on the cart page. You can include HTML
markup in the text - you can, for instance, include a link to your page with your discount
policy. In case you need the plugin to work well with product variations, make sure that the
"Treat product variations separately" option is unchecked. Since version 2.0 you
may choose to use a flat discount applied to the cart item subtotal. Optionally you may also
modify the CSS styles for the old value and the new value which is displayed in the cart.
Save the settings.

2. Navigate to Products and choose a product for which you want to create a discount policy.
In the Product Data panel, click Discounts Plus and optionally fill information about the discount
which will be visible in the product description.

3. Click "Define discount criteria" button to create a policy. Quantity (min.) means minimal
number of ordered items so that the (second textbox) Discount applies. It is possible to
add up to five discount lines to fine-tune the discount setting.

== Frequently Asked Questions ==

= How it works with WooCommerce single products? =

[youtube https://youtu.be/U8KlUIrlpgs]

= How can you apply multiple discounts to a product through categories? =

[youtube https://youtu.be/qNbjU1XkTuw]

= Are multiple discounts supported? How many levels of discounting may be applied? =

Yes, multiple discounts (related to a single product) are supported. Currently it is possible to
set up to 5 discount lines. That should be enough for reasonable fine-tuning of the discount.

= Is only a percentage discount implemented? =
Since version 2.0 another type of discount is added, allowing you to set a fixed discount in currency units
for the cart item subtotal.

= Will the discount be visible on WooCommerce e-mails and Order status as well? =
Yes. Since version 2.0, this feature has been implemented.

= Is it possible to handle discount for product variations as a whole? =
Yes, in case you have several product variations in your store and you need to apply the discount
to all the purchased variations, please upgrade to the latest version of Discounts Plus.
This functionality can be disabled in Discounts Plus settings.

= Is the plugin i18n ready? =
Yes, the plugin supports localization files. You can add support for your language as well by the standard process.

= Can you provide an example of setting a percentage Discounts Plus? =
Sure. Below is an example of setting a Discounts Plus for a product with three discount lines. 

1. Quantity (min.) = 3, Discount (%) = 5
2. Quantity (min.) = 8, Discount (%) = 10
3. Quantity (min.) = 15, Discount (%) = 15

If the customer orders, say, 12 items of the product which costs $15 per item, the second
discount line will apply. The customer then pays 12 * 15 = 225 dollars in total minus
10%, which yields $202.5. Note that this discount policy only applies to the concrete product -- other
products may have their own (possibly different) discount policies.

= Can you provide an example of setting a flat Discounts Plus? =
Example for flat discount follows:

1. Quantity (min.) = 10, Discount ($) = 10
2. Quantity (min.) = 30, Discount ($) = 20

If the customer orders, say, 15 items of the product which costs $10 per item, the first discount
line will apply and the customer will pay (15 * 10) - 10 dollars. If the customers orders
50 items, the second discount line will apply and the final price will be (50 * 10) - 20 dollars.
Setting Discounts Plus couldn't have been easier.

== Screenshots ==
1. Features at a Glance
2. WooCommerce Products Listing
3. WooCommerce Product Discount Criterias
4. WooCommerce Cart View - Need Discounts? (Pro Feature)
5. WooCommerce Cart View - Need Discounts? Clicked (Pro Feature)
6. WooCommerce Cart View - Discount Option Selected (Pro Feature)
7. WooCommerce Cart View - Discounts On Multiple Products (Pro Feature)
8. WooCommerce Cart View - Boost Your Sales With This Plugin (Pro Feature)
9. WooCommerce Cart - Order Review (Pro Feature)
10. Setup Discounts with s2member Plugin
11. Gabriel & Jose's Logic
12. Discount Available Contionally - No Shipping or Only Shipping
13. Global Criteria
14. Category Based Criteria
15. Cart Amount Based Criteria
16. Error Messages (Customization)
17. WooCommerce Settings Area
18. Premium Features
19. How multiple categories based discount work?

== Changelog ==
= 3.5.1 =
* Updating for the WordPress version. [17/01/2024][Thanks to gtcdesign]
= 3.5.0 =
* Fix: Uncaught Error: Call to a member function get_type() on bool. [19/05/2023][Thanks to Rafał Chełpa]
= 3.4.9 =
* Improved version for s2Member (Pro) plugin. [12/05/2023][Thanks to Rafał Chełpa]
= 3.4.8 =
* Improved version for s2Member (Pro) plugin. [19/04/2023][Thanks to Rafał Chełpa]
= 3.4.7 =
* Improved version for WordPress 6.0. [02/06/2022][Thanks to Toby Cryns]
= 3.4.6 =
= 3.4.5 =
= 3.4.4 =
= 3.4.3 =
= 3.4.2 =
* Improved version after in depth review by the plugin author and WordPress Plugin Review Team.
= 3.4.1 =
* Free version revised with qty. based discounts.
= 3.4.0 =
* Undefined property: stdClass::$post_type - fixed. [Thanks to eurostratos]
= 3.3.9 =
* Light version revised.
= 3.3.8 =
* Language translation files updated.
= 3.3.7 =
* Discount methods revised and tested to ensure accuracy. [Thanks to Stephen Russell]
= 3.3.6 =
* Uncaught Error: Call to a member function WC->session->get(), fixed. [Thanks to justinmyoung]
= 3.3.5 =
* Discount Label/Caption added on settings page. [Thanks to Don Paul]
= 3.3.4 =
* Settings page revised and discount value ensured in email. [Thanks to Don Paul]
= 3.3.3 =
* WC Membership compatibility revised. [Thanks to Team Ibulb Work and Yonasr]
= 3.3.2 =
* session_write_close() inserted after using session. [Thanks to egocefalo]
= 3.3.1 =
* Made easy to understand premium features. [Thanks to Abu Usman]
= 3.3.0 =
* Made easy to understand premium features. [Thanks to Abu Usman]
= 3.2.9 =
* Made easy to understand premium features.
= 3.2.8 =
* An improvement made in script. [Thanks to seighart]
= 3.2.7 =
* PHP warning on cart page. Fixed. [Thanks to seighart]
= 3.2.6 =
* Tabs introduced for better usability and added visual aids as well. [Thanks to Team Ibulb Work and AndroidBubbles]
= 3.2.5 =
* Updated and improved UI and UX.
= 3.2.4 =
* "Number of decimals" will control the decimal places in this plugin from this version onwards. [Thanks to Behnam Khan]
= 3.2.3 =
* Updated round of discounts on percentage. [Thanks to justinmyoung]
= 3.2.2 =
* Updated for WP 5.4.
= 3.2.1 =
* Another PHP notice fixed. [Thanks to goedebuursilentdisco]
= 3.2.0 =
* Another PHP notice fixed.
= 3.1.9 =
* PHP notice fixed – Product properties should not be accessed directly. [Thanks to amiayu]
= 3.1.8 =
* Improved discount text in emails for percentage discount. [Thanks to collartags.com]
= 3.1.7 =
* Improved Gabriela & Jose's Logic. [Thanks to collartags.com]
= 3.1.6 =
* Sprintf function issue fixed. [Thanks to donmcleman]
= 3.1.5 =
* Improved qty. discount range display. [Thanks to collartags.com]
= 3.1.4 =
* Fixed a minor javascript file symbol issue. [Thanks to René Baade Pedersen]
= 3.1.3 =
* Fixed a minor echo thing on admin screen. [Thanks to collartags.com]
= 3.1.2 =
* Pricing scale text is editable from settings page now. [Thanks to Don Paul]
= 3.1.1 =
* Warning: sprintf(): Too few arguments issue resolved. [Thanks to amiayu]
= 3.1.0 =
* %% issue resolved. [Thanks to patowins]
= 3.0.9 =
* Languages added. [Thanks to Abu Usman]
= 3.0.8 =
* WooCommerce get_cart() uncaught fatal error fixed on product page. [David Currie]
= 3.0.7 =
* WooCommerce Memberships compatibility added using class_exists check WC_Memberships_Loader. [David Currie]
= 3.0.6 =
* Flat discount > cart page > subtotal section correction. [David Currie]
= 3.0.5 =
* Flat discount updated price round float number format correction. [Thanks to Tom C]
= 3.0.4 =
* Flat discount updated price formula refined. [Thanks to Sadaf]
= 3.0.3 =
* Pricing scale turned ON by default. [Thanks to Sadaf Parvez]
= 3.0.2 =
* Fixed a few PHP notices regarding undefined variable and wrong usage of parent_id with product object. [Thanks to Sadaf Naz]
= 3.0.1 =
* Fixed a PHP notice regarding undefined variable unit price. [Thanks to mareklukas]
* Refined a few Premium features regarding weight based discounts. [Thanks to Ryan Chan]
= 3.0.0 =
* Fixed a PHP notice regarding undefined index 0. [Thanks to mareklukas]
* Fixed a Premium feature regarding settings page. [Thanks to Ryan Chan]
= 2.5.9 =
* Refined pricing scale display area and also fixed the decimal positions. [Thanks to cccnate]
= 2.5.8 =
* Refined and tested varitions discount criteria. [Thanks to Andrea Tarricone]
= 2.5.7 =
* Undefined constant WDP_PER_PRODUCT related warning fixed. [Thanks to Angelo]
* Protected function get_product_id call corrected. [Thanks to Konstantinos Zachos]
= 2.5.6 =
* Refined the varitions related discount aspect. [Thanks to Andrea Tarricone]
= 2.5.5 =
* Call to undefined method WC_Product_Simple::get_id() error fixed. Changes made in index.php on line 1054. [Thanks to Andrew]
= 2.5.4 =
* Discounts display on product page now added as an option in product page settings. [Thanks to Michiel]
= 2.5.3 =
* Category based discount feature refined and explained in the video tutorial again. [Thanks to Mouring Kolhoff]
= 2.5.2 =
* Added another compatibility with latest version of the WooCommerce. [Thanks to cathydol]
= 2.5.0 =
* s2member compatibility nonce related bug fixed. [Thanks to blastostitch]
= 2.4.9 =
* Display price issue in admin panel price column resolved. [Thanks to Dharmishtha Patel]
= 2.4.8 =
* WooCommerce > Cart Page > Old price was having 4 decimals > Fixed. [Thanks to Nate Melanson]
* Settings page > turn discounted price as display price on loop and single product pages. [Thanks to Breda McGuigan]
= 2.4.7 =
* User roles are added with multiple selection to ignore. If you don't want to allow a user role to get discounts. [Thanks to Jim Yow]
= 2.4.6 =
* Weight based discounts improved with multiple quantities. [Thanks to Dimitar Tsankov]
= 2.4.5 =
* Sanitized input and fixed direct file access issues.
= 2.4.4 =
* Discounts available with shipment conditions and can be restricted on user decision. [Thanks to Jon Siddall]
* Currency symbol position implemented as from WooCommerce settings page. [Thanks to grupa]
= 2.4.3 =
* Discounts available with shipment conditions. [Thanks to Jon Siddall]
* After discounts applied, prices were missing decimal values. Fixed. [Thanks to Greg Nowak]
= 2.4.2 =
* Weight based discounts introduced. [Thanks to Jon Siddall]
= 2.4.1 =
* Flat discounts refined for variable products. [Thanks to Paul Day]
= 2.4.0 =
* Flat discounts refined.
= 2.3.8 =
* Discounts refined and new features added. [Thanks to Scott McClain]
= 2.3.7 =
* Refining conditions and settings.
= 2.3.6 =
* Checkout process refined. [Thanks to shameemali]
= 2.3.5 =
* Variable products refined. [Thanks to Scott McClain]
= 2.3.4 =
* An important Fatal Error fixed on report. [Thanks to actionarchery]
= 2.3.3 =
* A few warnings were reported and those are fixed. [Thanks to scottmcx]
= 2.3.2 =
* Variable products refined. [Thanks to Jocelyne]
= 2.3.1 =
* Flat discount refined. [Thanks to Andy]
= 2.3.0 =
* Global settings improved. [Thanks to Jose & Gabriela]
* Global discounts crieteria and per product discount criteria, both are in action at the same time from now.
* Discount on cart total quanity introduced this time as a new optional logic.
= 2.2.3 =
* Flat discount global settings refined. [Thanks to GP Themes Team]
= 2.2.2 =
* Flat discount per product refined. [Thanks to Paul & NemoPro]
= 2.2.1 =
* A minor issue fixed in flat discount per product. [Thanks to Paul Braoudakis]
= 2.2.0 =
* s2member compatibility added.
= 2.1.0 =
* A few important fixes. [Thanks to nextime]
= 2.0.3 =
* Discounted prices should not be considered for discount again.
= 2.0.2 =
* Discounted prices should not be considered for discount again.
= 2.0.1 =
* Discounted prices on cart page. [Thanks to Alois]
= 2.0 =
* Global settings are introduced and a useful widget for discounts detail under product short description. [Thanks to nameez]
= 1.0 =
* Releasing 1.0 version.

== Upgrade Notice ==
= 3.5.1 =
Updating for the WordPress version.
= 3.5.0 =
Fix: Uncaught Error: Call to a member function get_type() on bool.
= 3.4.9 =
Improved version for s2Member (Pro) plugin.
= 3.4.8 =
Improved version for s2Member (Pro) plugin.
= 3.4.7 =
Improved version for WordPress 6.0.
= 3.4.6 =
= 3.4.5 =
= 3.4.4 =
= 3.4.3 =
= 3.4.2 =
Improved version after in depth review by the plugin author and WordPress Plugin Review Team.
= 3.4.1 =
Free version revised with qty. based discounts.
= 3.4.0 =
Undefined property: stdClass::$post_type - fixed.
= 3.3.9 =
Light version revised.
= 3.3.8 =
Language translation files updated.
= 3.3.7 =
Discount methods revised and tested to ensure accuracy.
= 3.3.6 =
Uncaught Error: Call to a member function WC->session->get(), fixed.
= 3.3.5 =
Discount Label/Caption added on settings page.
= 3.3.4 =
Settings page revised and discount value ensured in email.
= 3.3.3 =
WC Membership compatibility revised.
= 3.3.2 =
session_write_close() inserted after using session.
= 3.3.1 =
Made easy to understand premium features.
= 3.3.0 =
Made easy to understand premium features.
= 3.2.9 =
Made easy to understand premium features.
= 3.2.8 =
An improvement made in script.
= 3.2.7 =
PHP warning on cart page. Fixed.
= 3.2.6 =
Tabs introduced for better usability and added visual aids as well.
= 3.2.5 =
Updated and improved UI and UX.
= 3.2.4 =
"Number of decimals" will control the decimal places in this plugin from this version onwards.
= 3.2.3 =
Updated round of discounts on percentage.
= 3.2.2 =
Updated for WP 5.4.
= 3.2.1 =
Another PHP notice fixed.
= 3.2.0 =
Another PHP notice fixed.
= 3.1.9 =
PHP notice fixed – Product properties should not be accessed directly. 
= 3.1.8 =
Improved discount text in emails for percentage discount.
= 3.1.7 =
Improved Gabriela & Jose's Logic.
= 3.1.6 =
Sprintf function issue fixed.
= 3.1.5 =
Improved qty. discount range display.
= 3.1.4 =
Fixed a minor javascript file symbol issue.
= 3.1.3 =
Fixed a minor echo thing on admin screen.
= 3.1.2 =
Pricing scale text is editable from settings page now.
= 3.1.1 =
Warning: sprintf(): Too few arguments issue resolved.
= 3.1.0 =
%% issue resolved.
= 3.0.9 =
Languages added.
= 3.0.8 =
WooCommerce get_cart() uncaught fatal error fixed on product page.
= 3.0.7 =
WooCommerce Memberships compatibility added using class_exists check WC_Memberships_Loader.
= 3.0.6 =
Flat discount > cart page > subtotal section correction.
= 3.0.5 =
Flat discount updated price round float number format correction.
= 3.0.4 =
Flat discount updated price formula refined.
= 3.0.3 =
Pricing scale turned ON by default.
= 3.0.2 =
Fixed a few PHP notices but it's an important update.
= 3.0.1 =
Fixed a PHP notice regarding undefined variable unit price.
= 3.0.0 =
Fixed a PHP notice regarding undefined index 0.
= 2.5.9 =
Refined pricing scale display area.
= 2.5.8 =
Refined and tested varitions discount criteria.
= 2.5.7 =
Undefined constant WDP_PER_PRODUCT related warning fixed.
= 2.5.6 =
Refined the varitions related discount aspect.
= 2.5.5 =
Call to undefined method WC_Product_Simple::get_id() error fixed.
= 2.5.4 =
Discounts display on product page now added as an option in product page settings.
= 2.5.3 =
Category based discount feature refined and explained in the video tutorial again.
= 2.5.2 =
Added another compatibility with latest version of the WooCommerce.
= 2.5.0 =
s2member compatibility nonce related bug fixed.
= 2.4.9 =
Display price issue in admin panel price column resolved.
= 2.4.8 =
WooCommerce > Cart Page > Old price was having 4 decimals > Fixed.
= 2.4.7 =
User roles are added with multiple selection to ignore. If you don't want to allow a user role to get discounts.
= 2.4.6 =
Weight based discounts improved with multiple quantities.
= 2.4.5 =
Sanitized input and fixed direct file access issues.
= 2.4.4 =
Discounts available with shipment conditions and can be restricted on user decision. And currency symbol position implemented as from WooCommerce settings page.
= 2.4.3 =
Discounts available with shipment conditions.
After discounts applied, prices were missing decimal values. Fixed.
= 2.4.2 =
Weight based discounts introduced.
= 2.4.1 =
Flat discounts refined for variable products.
= 2.4.0 =
Flat discounts refined.
= 2.3.8 =
Discounts refined and new features added.
= 2.3.7 =
Refining conditions and settings.
= 2.3.6 =
Checkout process refined.
= 2.3.5 =
Variable products refined. 
= 2.3.4 =
An important Fatal Error fixed on report.
= 2.3.3 =
A few warnings were reported and those are fixed.
= 2.3.2 =
Variable products refined.
= 2.3.1 =
Flat discount refined.
= 2.3.0 =
Global settings improved.
= 2.2.3 =
Flat discount global settings refined.
= 2.2.2 =
Flat discount per product refined.
= 2.2.1 =
A minor issue fixed in flat discount per product. 
= 2.2.0 =
s2member compatibility added.
= 2.1.0 =
A few important fixes.
= 2.0.3 =
Discounted prices should not be considered for discount again.
= 2.0.2 =
Discounted prices should not be considered for discount again.
= 2.0.1 =
Discounted prices on cart page.
= 2.0 =
Global settings are introduced and a useful widget for discounts detail under product short description.
= 1.0 =
Releasing 1.0 version.

== License ==
This WordPress Plugin is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 2 of the License, or any later version. This free software is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details. You should have received a copy of the GNU General Public License along with this software. If not, see http://www.gnu.org/licenses/gpl-2.0.html.