=== Product Options and Price Calculation Formulas for WooCommerce – Uni CPO ===
Contributors: moomooagency, mrpsiho, andriimatenka, freemius
Tags: custom options, extra options, product visual builder, woocommerce plugins, price calculation, maths formula, conditional logic, wholesale
Requires at least: 5.6
Tested up to: 6.4
Requires PHP: 7.4
WC requires at least: 7.1.0
WC tested up to: 8.3.0
Stable tag: trunk
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Provides an opportunity to add extra product options with the possibility to calculate the price based on the chosen options and using custom maths formula!

== Description ==

= Overview =

**Uni CPO - WooCommerce Options and Price Calculation Formulas** is a fully featured plugin that creates an opportunity to add custom options for a WooCommerce products as well as enables custom price calculation based on any maths formula.

A fully featured visual form builder is used to add custom options. Would you like to place the options in two/three/more columns? Easy! Would you like to set custom color, margins, add custom text and so on? Yes, it's possible too!!)

It takes only 3 minutes to personalize a WC product and implement price calculation based on the extra product options and any maths formula you like:
[youtube https://www.youtube.com/watch?v=qZHWG9IAD5Q]

Add extra options to your products, display them conditionally, give a possibility for your customers to customize products, to personalize them by adding highly dynamic info like dimensions, custom labels, comments. Moreover, create a unique scheme for price calculation based on custom options added!

= Main features =

* Visual form builder - design the look of your form in easy and smooth way!
* Custom product option types - 10+ different types!
* A possibility to use non option variables (NOV) - synthetic variables which can hold both a specific value or a
maths formula as its value
* A possibility to use wholesale-like functionality for your NOVs - different values for different user roles!
* A possibility to use virtually any maths formula for the price calculation of your product
* A possibility to add formulas conditional logic - apply different formulas under different circumstances!
* A possibility to create fields conditional logic - display/hide certain custom options based on the values of
other custom options and/or NOVs
* A possibility to use custom price tables (via Non Option Variables functionality), set product price based on one or two custom options!
* Integrate with ShipperHQ or Boxtal and let them use the calculated weight of the ordered item and request the real shipping rates!
...and many many more! ;)

= Full List of Features and Docs =

* [The full list of plugin's features](https://moomoo-agency.gitbook.io/uni-cpo-4-documentation/why-uni-cpo)
* [The plugin's Documentation](https://moomoo-agency.gitbook.io/uni-cpo-4-documentation/)
* [How to Use Quick Guide](https://moomoo-agency.gitbook.io/uni-cpo-4-documentation/usage)

= Demo - Try By Yourself! =

[DEMO site with PRO version installed (unlocked all the features)](https://cpo.builderius.io)
Use the following credentials to log in and try by yourself:
* username: `demo`
* password: `demo`
[login URL](https://cpo.builderius.io/wp-login.php)

**Pro version of the plugin is [available here](https://builderius.io/cpo)**
**The official FB group [Builderians](https://www.facebook.com/groups/builderians/)**

**Uni CPO supports ONLY these product types: 'simple' and 'subscription'!** But why you ever need any variable products when this plugin exists, right? :)

== Installation ==

= Minimum Requirements =

* WooCommerce 7.1+
* WordPress 5.9 or greater
* PHP version 7.4 or greater

= Automatic installation =

To do an automatic install of Uni CPO, log in to your WordPress dashboard, navigate to the Plugins menu and click Add New.

In the search field type “Uni CPO” and click Search Plugins. Once you’ve found our WC extension plugin you can view details about it such as the point release, rating and description. Most importantly of course, you can install it by simply clicking “Install Now”.

= Manual installation =

1. Upload the plugin files to the `/wp-content/plugins/uni-woo-custom-product-options` directory, or install the plugin through the WordPress plugins screen directly.
1. Activate the plugin through the `Plugins` screen in WordPress
1. Use the WooCommerce->Uni CPO Settings screen to configure the plugin


== Frequently Asked Questions ==

** Q: Do I need to back up before update? **
A: Yes, always and ever! Back up your files as well as database. Always test new versions of the plugin on test/stage server first!

== Screenshots ==

1. screenshot-1.png
2. screenshot-2.png
3. screenshot-3.png
4. screenshot-4.png
5. screenshot-5.png
6. screenshot-6.png
7. screenshot-7.png

== Changelog ==

= 4.9.32 =
* Updated: Freemius SDK to 2.6.0

= 4.9.31 =
* Added: shipping class conditional applying

= 4.9.30 =
* Fixed: displaying dynamic data suboptions' labels
* Fixed: some PHP warnings

= 4.9.28 =
* Improved: The plugin prevents ordering the product again (from history) if one or more suboptions are missing
* Added: support for HPOS

= 4.9.27 =
* Fixed: a bug related to displaying qty based discounts
* Fixed: a bug related to 'woocommerce_add_to_cart_redirect' filter

= 4.9.26 =
* Fixed: a bug with suboptions conditional rules for Checkbox option

= 4.9.25 =
* Fixed: a bug with suboptions conditional rules for Select option
* Updated: Freemius SDK to the latest version

= 4.9.24 =
* Fixed: a bug related to editing qty value for cart item that is set as an option
* Added: bulk edit functionality

= 4.9.23 =
* Improved: Added new option "Own role" for "User role when edit orders"
* Added: setting to control image changing in the cart when you are using "Imagify", "Colorify", "Image Conditional Logic" functionalities

= 4.9.22 =
* Hotfix: fixed PHP error visible on new product creation

= 4.9.21 =
* Improved: proper taxes handling for cpo enabled products when adding order manually
* Fixed: uploading files with "'" in the name

= 4.9.20 =
* Improved: error message when importing CSV with suboptions data
* Fixed: some small bugs

= 4.9.19 =
* Fixed: the issue with importing configurations
* Improved: added disabling of the "add to cart" button while a file is being uploaded in File Upload option

= 4.9.17 =
* Fixed: issue with ramda.js dependency

= 4.9.16 =
* Fixed: another issue with PHP 8 compatibility

= 4.9.15 =
* Fixed: issue with PHP 8 compatibility
* Improved: moved JS lib dependency inside the plugin, therefore removed the link to CDN
* Fixed: issues with proper image displaying for suboptions in some cases

= 4.9.14 =
* Added: support for "woocommerce price based on countries" plugin
* Security fix

= 4.9.13 =
* Fixed: bug with 'cpo_product_layered_image' field
* Fixed: bug with wrong cart item price for switched (simple -> variable) product

= 4.9.12 =
* Fixed: bug with 'clear option' functionality

= 4.9.11 =
* Fixed: bug

= 4.9.10 =
* Fixed: a bug preventing from saving options while using the plugin on some PHP configurations

= 4.9.9 =
* Fixed: notice about cart discount is shown in the cart even if no discounts applied
* Fixed: price archive template regular/sale prices are now correctly formatted
* Added: new filter 'uni_cpo_woocommerce_cart_item_thumbnail' for possibility to change image size of the cart item

= 4.9.8 =
* Fixed: a bug with "price tmpl for archives" and "price tmpl for archives (sale)" fields in data manager

= 4.9.7 =
* Improved: adjusted CSS price tag selector, added reference to 'bdi' tag
* Added: a new type of option Font Preview (pro version)

= 4.9.6 =
* Fixed: availability of some texts for translations
* Fixed: saving extra validation rules for options

= 4.9.5 =
* Added: new option "Distance by postcode"
* Fixed: several small errors in JS

= 4.9.4 =
* Improved: Hidden some system order meta items on edit order page screen
* Fixed: a bug when it is possible to add uni cpo config product with 0 price to the cart

= 4.9.3 =
* Fixed: displaying default values for options when editing in edit order screen
* Fixed: teh bug related to deleting NOVs when saving data in data manager
* Updated: Freemius SDK to the latest version

= 4.9.2 =
* Added: auto correction of 'pid' attributes on config import
* Added: auto correction of URLs in radio/checkboxes on config import
* Added: new filter 'uni_cpo_nov_variable_value'

= 4.9.1 =
* Fixed: annoying JS error related to certain version of jQuery
* Fixed: 'unknown' NOV is being saved; now it will not be saving, still you have to remove it from the list of NOVs and save them, for each product

= 4.9.0 =
* Added: first version of CPO data manager (pro version)
* Improved: integration with NBdesigner plugin
* Fixed: a bug with layouts for "Colorify" functionality

= 4.8.2 =
* Fixed: a bug with "Colorify" functionality on mobile devices
* Fixed: a bug with layouts for "Imagify" functionality

= 4.8.1 =
* Improved: several warnings popping up under certain circumstances

= 4.8.0 =
* Fixed: displaying proper order data in add/edit CPO options modal
* Added: setting to control for which user role prices should be calculated when administrator edits order
* Improvement: added 'setlocale(LC_NUMERIC, 'C');' to force using '.' (dots) as decimal delimiter (by default, in PHP 7.3+ env it sets this param differently); commas cannot be used as they break price calculation;

= 4.7.7 =
* Fixed: cart item with removed/overwritten options generated error

= 4.7.6 =
* Updated: 'rangesum' functionality; added new filter related to this functionality
* Improved: possibilities for string translation in several places
* Improved: conditional logic for "Imagify" functionality
* Added: fix for 'sold individually' behaved wrongly when WC Multilingual plugin is activated
* Fixed: a bug with tooltips on mobile devices

= 4.7.5 =
* Fixed: a bug in 'rangesum' functionality;

= 4.7.4 =
* Fixed: displaying 'select options' for 'out of stock' products

= 4.7.3 =
* Fixed: disappearing of tooltip icons next to option's labels

= 4.7.2 =
* Fixed: duplicating cart item functionality

= 4.7.1 =
* Fixed: issue related to checkboxes labels disappearing

= 4.7.0 =
* Added: a possibility to configure dynamic options' and suboptions' labels
* Fixed: a bug in "Imagify" functionality for Classic/Colour/Text types of Radio Input option
* Checked for compatibility with WC 3.9

= 4.6.14 =
* Added: new util method getCartLabel; it can be used in Dynamic Notice, gets cart/order label of the
option instead of regular label
* Fixed: an issue when Image Conditional Logic data was not saving on copying/duplicating the product

= 4.6.13 =
* Fixed: a bug when it was not possible to edit custom options in wp admin dashboard when editing an order

= 4.6.12 =
* Fixed: a bug related to 'order again' pro functionality

= 4.6.11 =
* Fixed: a bug in "Imagify" functionality for mobile devices
* Fixed: a warning appearing in the cart related to pitchprint integration code

= 4.6.10 =
* Fixed: a bug when pitchprint cart item's preview was not displaying
* Fixed: a bug in "Image Conditional Logic" functionality
* Improved: now files of added to cart items are cleared from the form on the product page

= 4.6.8 =
* Fixed: a bug in NOV matrix functionality
* Fixed: a bug in 'getLabel' func

= 4.6.7 =
* Fixed: a bug in Select option render function
* Added: 'uni_cpo_before_render_builder_modules' filter
* Added: cs_CZ translation file (thanks to Radim Hahn)

= 4.6.6 =
* Fixed: a styling issue with tooltip for select field
* Improved: NOV's matrix 'range sum' functionality

= 4.6.5 =
* Fixed: an issue with wrong values when editting cart item
* Fixed: an issue with lost validation of qty field when using Uni CPO plugin
* Fixed: an issue when it sometimes was possible to upload more than file for File Upload

= 4.6.4 =
* Fixed: a bug in NOV's matrix 'range sum' functionality

= 4.6.3 =
* Added: rangesum() math function (the details are in the docs)
* Added: an advanced functionality related to using NOV's matrix in 'range sum' functionality


= 4.6.2 =
* Fixed: a bug in Datepicker related to counting duration when in 'days' mode
* Fixed: a bug in fields conditional logic related to operators is_empty|is_not_empty and radio/checkboxes fields

= 4.6.1 =
* Improved: CSV import functionality

= 4.6.0 =
* Added: import tool for radio/checkboxes/select options' suboptions
* Fixed: a possibility to choose more than one default value for checkboxes

= 4.5.4 =
* Added: 'currency' special variable to formula conditional logic; may be used in conjunction with Aelia switcher
* Added: new type of JS validators: 'greaterorequalthan', 'greaterthan', 'lessorequalthan', 'lessthan'
* Fixed: an issue with validation when creating order in admin area
* Fixed: an issue with with matrix table

= 4.5.3 =
* Added: 'minpositiveorzero' function to be used in formulas
* Added: 'uni_wrapper_attributes_for_option' filter; now custom attributes can be added to option's wrapper

= 4.5.2 =
* Fixed: small fix: a PHP warning when using conditional logic

= 4.5.1 =
* Fixed: a bug with suboptions conditional rules for Radio Input and Checkboxes options"
* Fixed: a bug with changing main image for "Range slider"

= 4.5.0 =
* Added: suboptions conditional rules for Radio Input and Checkboxes options
* Fixed: a bug with step setting for "Range slider" option and the input next to range slider

= 4.4.9 =
* Fixed: Security fix

= 4.4.8 =
* Fixed: conflict when using along with YITH bundled products plugin
* Fixed: a bug with data.getSuboptionLabel() method for Checkbox inputs option
* Fixed: a bug with changing main image and adding item to the cart page

= 4.4.7 =
* Fixed: a bug when excluded from participating in Imagify options were still used in Imagify

= 4.4.6 =
* Improved: Colorify/Imagify dynamically generated image for the cart/order item
* Fixed: Fixed a broken link/html to the uploaded file; reverted to just filename, because WC does not allow ANY html
* Fixed: changing slides in Flatsome theme when using Colorify/Imagify functionality

= 4.4.5 =
* Improved: displaying product prices on archives

= 4.4.4 =
* Fixed: add/edit CPO options data on edit order page in admin area

= 4.4.3 =
* Fixed: displaying product price in admin area; reverted to original prices, so they can be sorted
* Fixed: displaying range slider in the builder mode

= 4.4.2 =
* Hotfix: another one bug in the code

= 4.4.1 =
* Hotfix: a bug in the code

= 4.4.0 =
* Added: Imagify functionality
* Added: new option: Extra Cart Button
* Added: "Free samples" functionality
* Added: new option: Google Map
* Added: two new vars for Dynamic Notice related to cart discounts
* Added: helper methods for variables to be used in Dynamic Notice
* Improved: fields conditional logic script, fixed some minor issues
* Fixed: Colorify func related code - the issue when the main image has not been updated accordingly on init
* Fixed: Cart/order meta strings could not be translated via string translation functionality of multi language plugins
* Fixed: a bug related to using big numbers in Matrix option

= 4.3.2 =
* Fixed: a PHP Warning related to Radio Input option

= 4.3.1 =
* Fixed: some issues with radio and checkboxes options

= 4.3.0 =
* Added: qty based cart discounts
* Added: possibility to choose custom field as qty field (instead of standard WC qty field) and display its value in Qty column in cart/order
* Added: "sold individually" setting; it does what the original WC setting does, but for products with enabled Uni CPO options
* Improved: displaying custom price tag templates on archives
* Improved: added separate settings for styling option's label

= 4.2.7 =
* Fixed: 'step' attribute for Text Input now works like intended
* Fixed: the compatibility issue with Aelia Currency Switcher when using {uni_cpo_price} variable

= 4.2.6 =
* Fixed: bug in IE with radio/checkbox options
* Fixed: added missing parsley.min.js.map file
* Fixed: bug with border for radio/checkbox options

= 4.2.5 =
* Added: a possibility to use any NOV as starting price setting; useful for displaying role based wholesale prices
* Fixed: issues with fields conditional logic and operators 'between' and 'not_between'

= 4.2.4 =
* Added: product basic setup tutorial (WP pointers)
* Added: 'between' and 'not_between' query builder filters for Text Input; fixed the same filters for NOVs
* Fixed: some minor styling issues

= 4.2.3 =
* Added: support of Aelia Currency Switcher
* Fixed: not saving 'class' and/or 'id' attributes for a, img, table html tags
* Fixed: displaying dimensions for cart items even if it is disabled
* Improved: suboptions conditional logic functionality based on users' feedbacks

= 4.2.2 =
* Added: support of Storefront Sticky header
* Improved: suboption conditional logic
* Fixed: some minor styling issues

= 4.2.1 =
* Fixed: a bug on order edit page

= 4.2.0 =
* Added: suboption conditional logic (currently Select option only)
* Added: a possibility to display NOVs in cart/order meta
* Fixed: a bug with saving checkboxes values in order meta

= 4.1.6 =
* Added: a possibility to use regular variables and NOVs as cart discounts values
* Fixed: a bug in formula conditional logic

= 4.1.5 =
* Added: a possibility to use Dropbox as the file storage for the files uploaded via File Upload Options
* Updated: Freemius SDK

= 4.1.4 =
* Added: timepicker mode for Datepicker Option
* Added: 'multiple dates' mode for Datepicker Option
* Added: 'subscription' product type unlocked for using in the plugin (experimental)
* Updated: jQuery QueryBuilder script to 2.5.2
* Fixed: several small style issues

= 4.1.3 =
* Added: support for "Popup Maker – Popup Forms, Optins & More" plugin; use popups instead of tooltips
* Improved: some small enhancements, including adding notifications in case of common errors and issues
* Fixed: a bug when 'remove' icon is disappeared in NOV matrices

= 4.1.2 =
* Improvement: small fixes and improvements
* Added: support for the plugin add-ons

= 4.1.1 =
* Hot-fix for price calculation in the cart; there was a bug related to options with suboptions
* Fixed: using NOVs in validation conditional logic
* Added: support for Avada theme for changing image upon selection in option functionality
* Added: a possibility to set NOV value as value for validation attribute

= 4.1.0 =
* Added: Matrix Option
* Added: Colorify functionality
* Added: a possibility to add input for range slider in single mode
* Added: starting price, price prefix, price postfix and price template for archives settings
* Added: support for RTL languages
* Fixed: a possibility to add files for order meta if this meta is for File Upload Option
* Fixed: displaying added item in WC cart widget
* Fixed: some other minor bugs

= 4.0.11 =
* Hot-fix price displaying issue on other products

= 4.0.10 =
* Fixed adding custom image (if selected) upon cart item duplication
* Fixed updating cart item after 'full edit' instead of creating a new cart item
* Fixed disabling 'add to cart' btn by using a special word 'disable' instead of formula

= 4.0.9 =
* Fixed a JS bug related to price calculation data that is returned from backend

= 4.0.8 =
* Fixed a bug when adding product to cart
* Added support for WC 3.3+

= 4.0.7 =
* Added 'min date' and 'max date' settings for Date picker option
* Added 'custom values' setting for Range Slider option
* Added 'font' setting for Select option
* Added cart item edit 'full' mode (PRO)
* Added 'other variables' are now can be used in Dynamic Notice
* Improved File upload: a file will be uploaded automatically after adding
* Improved: WC price is now hidden on init
* Improved: option's values are now preserved after page reload during adding to cart
* Changed: JS event names are prefixed with 'uni_'
* Fixed saving special tags in Dynamic Notice option
* Fixed saving 'img' tag in tooltips
* Fixed displaying/hiding 'order disabled' custom message
* Fixed fields conditional logic when using NOVs

= 4.0.6 =
* Fixed validation for Text Input in 'decimal' mode
* Fixed a bug when it was not possible to set 'one letter' slug for suboptions

= 4.0.5 =
* Added Dynamic Notice option
* Added Range Slider option
* Added Dimensions Conditional Logic
* Added 'convert to unit' functionality for NOVs
* Added order item edit functionality
* Improved support for various WP themes, fixed some CSS related issues
* Improved cart item inline edit functionality - added support for datepicker
* Fixed an issue when alt image for suboptions was not actually optional
* Fixed an issue when two clicks were needed to select radio input in 'image' mode

= 4.0.4 =
* Fixed an issue related to the order of cart/order meta
* Fixed displaying 'Select options' instead of 'Add to cart' on product archives
* Fixed an issue with using a wrong protocol during enqueueing some dynamically generated content on sites with SSL enabled
* Improved styles for several option types
* Added a scroll bar for the options list in the builder panel

= 4.0.3 =
* Enhaced and improved

= 4.0.2 =
* Fixed a bug "Inconsistency of view in builder and in the frontend"

= 4.0.1 =
* Fixed a bug with displaying prices which are higher than one thousand

= 4.0 =
* The release of the plugin
