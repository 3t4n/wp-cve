=== Dropp for WooCommerce ===
Contributors: Forsvunnet
License: GPLv3
License URI: http://www.gnu.org/licenses/gpl.html
Tags: Shipping, WooCommerce, Iceland
Requires at least: 5.2
Tested up to: 6.4.2
Requires PHP: 8.1
WC requires at least: 3.8.1
WC tested up to: 8.5.2
Stable tag: 2.1.1

Seamlessly integrate your shipping with Dropp for delivery in Iceland using this WordPress plugin.
Requires a Dropp account. Sign up [here](https://www.dropp.is/stofna-adgang).

== Installation ==

1. Download the plugin
2. Extract the files from the downloaded archive to your `wp-content/plugins` directory.
3. Activate the plugin under WP-Admin → Plugins

== Configuration ==

### Adding the shipping method to a shipping zone

1. Navigate to WooCommerce → Settings → Shipping
2. Choose a zone where you want to enable Dropp. If you need help with WooCommerce zones, check out this guide [here](https://docs.woocommerce.com/document/setting-up-shipping-zones/).
3. Click on **Add shipping method**
4. In the dropdown menu, select **Dropp**, then click **Add shipping method** in the modal.
5. Click on the added shipping method to set the name and price.

### Connect to the Dropp.is API for booking

1. Navigate to  WooCommerce → Settings → Shipping → Dropp
2. Fill in your API key and Store ID
3. Click the **Save changes** button

== Booking ==

### Bulk booking

On the orders view in the admin panel you can select all the orders you want to book by checking the checkbox to the left of the order. Next select "Dropp - Book orders" in the Bulk Actions dropdown menu and click the **Apply** button. Only orders that have 0 in the Dropp column will be booked using bulk booking. After the orders have been booked you will be given a link to download the labels for the selected orders. If an order for some reason could not be booked then you will have to book those individually. The number in the Dropp column in the orders table indicate how many times an order has been successfully booked.

### Individual booking

On the order screen there is a new meta box for Dropp booking. An order can be shipped to multiple locations or have multiple consignments sent to the same location. Click on the **Add shipment** button to add additional shipments.

For each shipment you can select which products should be sent and the quantity of each product. When you are ready to book, click the **Book now** button.

When an order has the status **Initial** it can be updated or cancelled.

### Enabling Dropp for orders that has a different shipping method

If the order does not have a dropp shipping method attached to one of the order lines then dropp booking will not be available. To enable it simply add a new shipping line to the order and edit it to use dropp shipping. If an order cannot be edited then try to change the order status to **pending** first.

== Frequently Asked Questions ==

= Does the plugin work with Elementor PRO checkout widget? =

Yes, provided that the checkout widget is on the WooCommerce checkout page. If you have multiple pages using the checkout widget then it will not work.

= Do you support plugin X? =

In most cases yes, but we cannot guarantee compatability with every plugin.
If you experience any problems using this plugin alongside another plugin then please create a support ticket.

== Changelog ==

= 2.1.1 =

* Fixed error when HPOS is enabled
* Added bulk action support when HPOS is enabled

= 2.1.0 =

* Added support for WooCommerce block checkout
* Fixed some warning messages

= 2.0.4 =

* Fixed a bug where settings would in some cases not load correctly
* Fixed warning of missing key enable_ssn
* Made cost field optional
* Some changes to integrate better with woocommerce block

= 2.0.2 =

* Fixed a bug where a message falsly indicated the API key was not set in the settings

= 2.0.1 =

* Fixed bug where the cost settings did not work under "Locations not covered by your other zones"
* Added a warning in the shipping settings for the cost fields if the API key has not been entered yet
* Refactored getting global options for the dropp shipping method to increase performance for the settings screen

= 2.0.0 =

* Added new settings for cost of shipping rates based on weight of the items in cart
* Made PDF's downloadable from source if the websites filesystem is restricted

= 1.5.3 =

* Added support for pickup at location (price type 0)
* Added setting to insert dropp shipping options at the top in the cart and checkout

= 1.5.2 =

* Fixed more type errors

= 1.5.1 =

* Fixed a type error on orders without a dropp shipping item

= 1.5.0 =

* Added setting to allow booking with included return labels
* Fixed a bug where the location would revert to the one selected at checkout when updating a booking with a different location
* Improved code by using typed variables and parameters

= 1.4.11 =

* Added option to book orders with return labels

= 1.4.10 =

* Fixed tax calculation for free shipping
* Fixed booking of home delivery with day time shipping (day delivery)

= 1.4.8 =

* Fixed a bug with the name in label option where it would not work with locations outside capital area
* Fixed a javascript bug causing the booking panel to not initialize
* Added option to include the dropp location as part of the shipping rate name
* Made Dropp and Dropp home delivery methods available before any address information has been provided.
* Added a fix to not lose selection of the Dropp shipping method when changing location between inside and outside capital area.

= 1.4.4 =

* Added support for mynto_id on shipping items
* Added location name to shipping description for order emails

= 1.4.2 =

* Fixed bug with SSN always being enabled
* Fixed bug with weight limit validation

= 1.4.0 =

* Added new shipping methods, Dropp Outside Capital Area, Dropp Home Delivery Outside Capital Area
* Renamed Flytjandi to Dropp - Other pickup locations
* Increased weight limitation for home delivery and removed it for Dropp - Other pickup locations.
* Added weight shortcode for cost calculation
* Fixed order status update when mass booking orders
* Added a fix to make the plugin compatible with WooCommerce advanced shipping methods plugin
* Added conversion of dropp order ids in the order meta data to allow third party interaction

= 1.3.10 =

* Implemented new booking field for delivery instructions
* Added setting to copy customer note to delivery instructions
* Implemented new checkout API call
* Added weight treshold to cart for dropp shipping methods
* Fixed bug with "Tracking code" always showing on WooCommerce order emails

= 1.3.9 =

* Fixed a rare fatal error that occured when orders contained deleted products
* Normalised shipping weight into kilograms

= 1.3.8 =

* Added free shipping settings
* Updated javascript development dependencies

= 1.3.5 =

* Fixed the SSN validation code

= 1.3.4 =

* Fixed bulk print bug when printing only one pdf
* Fixed a bug where shipping rates were cached between price types

= 1.3.2 =

* Added flytjandi and pickup shipping methods
* Restricted home delivery based on post codes
* Added tracking code to booked orders
* Added post code validation
* Added extra PDF support
* Added extra price setting for locations outside the capital area

= 1.2.0 =

* Renamed the plugin
* Added home delivery as a new shipping method
* Fixed a bug that duplicated product lines when editing an existing consignment

= 1.1.2 =

* Fixed bug that caused shipping to always be free

= 1.1.1 =

* Added icelandic translations
* Changed plugin name from WooCommerce Dropp Shipping to Dropp for WooCommerce
* Corrected the login URL for getting the live API key
* Fixed a bug that caused dropp consignments to revert back to test-mode

= 1.1.0 =

* Implemented status updates from dropp
* Implemented methods to cancel and update dropp orders
* Added bulk booking and printing

= 1.0.0 =

* First version
