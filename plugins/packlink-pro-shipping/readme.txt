=== Packlink PRO shipping module ===
Contributors: packlink
Tags: woocommerce, shipment, shipping, packlink
Requires at least: 4.7
Requires PHP: 5.5
Tested up to: 6.4.2
Stable tag: 3.4.3
License: LICENSE-2.0
License URI: http://www.apache.org/licenses/LICENSE-2.0

Free professional shipping platform that will simplify and automate your logistics.

== Description ==

**Packlink PRO is the professional shipping platform that allows you to automate your shipment process.** It is free to use and does not require a minimum volume of shipments. You only need to register and you'll get instant access to a variety of shipping services and rates that can help to make your business more profitable.


Connect your WooCommerce account with Packlink PRO.

- You have complete control over your sales and you can manage all your shipments from a single platform.

- You can start shipping straight away: there's no contract to sign and no minimum shipping volume.

- Choose the transport services that your customers want: express, international, etc...

- Save time in your daily shipping routine - import the parcel dimensions and destination, print the labels in bulk and check at a glance the status of all your shipments.

- Individual telephone support: a team of shipping specialists will assist you with the integration process and provide ongoing account management.


No download costs, installation or monthly fees - you pay purely for the shipments you book!

**<a href="https://pro.packlink.es/cmslp/woocommerce" target="_blank" title="Subscription">Register free</a> in Packlink PRO and get started!**

== Installation ==

This is how the WooCommerce integration with Packlink PRO works.

**1. Install and configure the plugin**

- You can install the Packlink PRO plugin in one of two ways: either a. directly from your back office, or b. from the WooCommerce plugs page.
  - Option a. From your WordPress back office go to "Plugins" > "Add new" > then, search for "Packlink" > "Install now".
  - Option b. Go to <a href="https://wordpress.org/plugins/packlink-pro-shipping">https://wordpress.org/plugins/packlink-pro-shipping</a> and click on the "Download" button. Then, from your WordPress back office "Plugins" section click on "Add new" > "Upload plugin" and upload the downloaded zip file.

- Once you have installed the plugin, login to the Packlink PRO website and click on the "Configuration" icon in the top right-hand corner. Then, from the left-hand menu, select "Integrations for your online store" and click on the WooCommerce logo, where you can generate the API key required to synchronize both platforms. Copy this API key. You will need to enter this key in Packlink PRO module in WooCommerce.

- In Packlink PRO, you can define the dimensions of your most common parcel and pickup address. This information is automatically synchronized with your WooCommerce and becomes your predefined parcel and address.

**2. Sync with your Packlink PRO account**

- Go back to your WooCommerce back office and select the WooCommerce > Packlink PRO from the left-hand menu. When the module login page opens, paste the API key you copied from your Packlink PRO account and click on the Log in button. The module will automatically synchronize your default parcel dimensions and pickup address from Packlink PRO. Also, after a few moments, it will synchronize all available shipping services.

- Select the shipping services you want to use. When you click on a "configure" button next to each shipping service, you can configure how you name each service and whether you show the carrier logo to your customers.

- Besides name and logo, for each shipping service you can define your pricing policy by choosing from the following options: direct Packlink prices, percentage of Packlink price, fixed price by weight, or fixed price by shopping cart.


**3. Use the module**

- If an order has been paid or payment was accepted by you, the shipment will be automatically imported into your Packlink PRO account. Also, you have an option to manually send an order to the Packlink PRO by opening order details page and clicking on the "Create draft" button in the "Packlink PRO Shipping" section on the right side.

- Packlink PRO is always updated with all shipments that are ready for shipment in WooCommerce.

- You only need to access Packlink PRO for the payment. Sender and recipient details will already have been synchronized with WooCommerce data.


Click <a href="https://support-pro.packlink.com/hc/es-es/articles/210158585" target="_blank" title="support">here</a> to get more information about the installation of the module.

== Changelog ==

#### 3.4.3 - March 11th, 2024

**Updates**
- Add unsupported countries: Estonia, Latvia and Romania

#### 3.4.2 - February 26th, 2024

**Updates**
- Add unsupported countries (Bulgaria, Finland, Greece, Australia)
- Add French overseas territories
- Fix displaying error message on the checkout page

#### 3.4.1 - January 17th, 2024

**Updates**
- Fix rendering order page
- Fix updating 'Send with Packlink' button

#### 3.4.0 - January 11th, 2024

**Updates**
- Add compatability with WooCommerce block checkout enhancement
- Update compatible version of WooCommerce (8.5.0) and WordPress (6.4.2)

#### 3.3.4 - November 21st, 2023

**Updates**
- Updated to the latest Core changes regarding shipping cost calculations.

#### 3.3.3 - October 19th, 2023

**Updates**
- Fix different logo image size on order page
- Update compatible version of WooCommerce (8.2.1) and WordPress (6.3.2)

#### 3.3.2 - October 17th, 2023

**Updates**
- Fix the issue with drop off selection
- Update compatible version of WooCommerce (8.2)

#### 3.3.1 - October 11th, 2023

**Updates**
- Add compatibility with WooCommerce 8.1.1 and WordPress 6.3.1

#### 3.3.0 - July 24th, 2023

**Updates**
- Add compatibility with WooCommerce HPOS (High-Performance Order Storage) feature

#### 3.2.18 - July 19th, 2023

**Updates**
- Fix issues with relay points
- Update phone number validation

#### 3.2.17 - June 6, 2023

**Updates**
- Update link to order draft on Packlink

#### 3.2.16 - May 30, 2023

**Updates**
- Fix view on Packlink link

#### 3.2.15 - May 18, 2023

**Updates**
- Fix layout on orders page

#### 3.2.14 - March 8, 2023

**Updates**
- Add handling of shipment delivered webhook.

#### 3.2.13 - December 13, 2022

**Updates**
- Fix duplicating shipping methods.

#### 3.2.12 - October 10, 2022

**Updates**
- Stabilize version.

#### 3.2.10 - July 19, 2022

**Updates**
- Added compatibility with the new checkout page.

#### 3.2.9 - May 30, 2022

**Updates**
- Updated async process wakeup delay for manual sync.

#### 3.2.8 - May 10, 2022

**Updates**
- Added carrier logos for Colis Prive and Shop2Shop shipping services.

#### 3.2.7 - April 12, 2022

**Updates**
- Optimized order sync for users experiencing CPU surcharge by adding an option to switch to manual synchronization.

#### 3.2.6 - February 17, 2022

**Updates**
- Updated to the latest Core changes regarding changing the value of the marketing calls flag.
- Updated compatible versions of WordPress (5.9.0) and WooCommerce (6.1.0).

#### 3.2.5 - December 7, 2021

**Updates**
- Added configuration for page footer height.
- Fixed shipping cost calculation.

#### 3.2.4 - August 31, 2021

**Updates**
- Add order reference sync.
- Add support for additional statuses.

#### 3.2.0 - July 07, 2021

**Updates**

- Add support for multi-currency.

#### 3.1.3 - March 01, 2021

**Updates**

- Preserve shipping class costs configuration when updating Packlink carriers.
- Remove notifications on the configuration page.
- Fix order status cancelled update.

#### 3.1.2 - December 21, 2020

**Updates**

- Add migration script to fix the saved parcel.

#### 3.1.0 - December 17, 2020

**Updates**

- Added postal code transformer that transforms postal code into supported postal code format for GB, NL, US and PT countries.
- Added support for new warehouse countries.

#### 3.0.7 - November 11, 2020

**Updates**

- Fix issue with execution of queue items.

#### 3.0.6 - November 10, 2020

**Updates**

- Add missing HERMES and DPD carrier logos.
- Fix warnings on the cart page.
- Fix setting warehouse postal code and city from the module.

#### 3.0.5 - October 21, 2020

**Updates**

- Add sending "disable_carriers" analytics.

#### 3.0.4 - September 28, 2020

**Updates**

- Check whether Packlink object is defined before initializing checkout script.
- Fix error when plugin translations for a language don't exist.

#### 3.0.3 - September 04, 2020

**Updates**

- Fixed location picker issue.

#### 3.0.2 - September 02, 2020

**Updates**

- Fixed translation issue in Italian.

#### 3.0.1

**Updates**

- Fixed changing shop order status upon shipment status update.

#### 3.0.0 - August 26, 2020

**Updates**

- New module design with new pricing policy.

#### 2.2.4

**Updates**

- Added support for the network activated WooCommerce plugin.
- Added Hungary to the list of supported countries.
- Fixed not saved drop-off point details on the order.

#### 2.2.3

**Updates**

- Added "Send with Packlink" button on order overview page.

#### 2.2.2

**Updates**

- Added top margin to drop-off button on checkout page.

#### 2.2.1

**Updates**

- Prevented export of order with no shippable products.
- Fixed order export if orders are not made with Packlink shipping method.
- Fixed the mechanism for updating information about created shipments.

#### 2.1.2

**Updates**

- Fixed the mechanism for updating information about created shipments.

#### 2.1.1

**Updates**

- Allow setting the lowest boundary for fixed price policies per shipping method.
- Changed the update interval for getting shipment data from Packlink API.
- Updated compatibility with the latest WP and WC versions

#### 2.1.0

**Updates**

- Added automatic re-configuration of the module based on WooCommerce and WordPress settings in cases when the module cannot run with the default shop and server settings.

#### 2.0.9

**Updates**

- Fixed compatibility bug with the WooCommerce prior to 3.0.4 for order shipping and billing addresses.

#### 2.0.8

**Updates**

- Fixed compatibility bug with the PHP versions prior to 5.5.

#### 2.0.7

**Updates**

- Fixed compatibility bug with the WooCommerce prior to 3.2 for shipment methods that require drop-off location.

#### 2.0.6

**Updates**

- Fixed backward compatibility with the WooCommerce prior to 3.2
- Fixed problem in updating shipping information from Packlink

#### 2.0.5

**Updates**

- Added new registration links
- Fixed some CSS issues

#### 2.0.4

**Updates**

- Added update message mechanism
- Minor bug fixes

#### 2.0.3

**Updates**

- The Add-on configuration page is completely redesigned with advanced options
- Added possibility for admin to enable only specific shipping services for end customers
- Each shipping service can be additionally configured by admin - title, logo display, advanced pricing policy configuration
- Enhanced integration with Packlink API
- End customers can now select a specific drop-off point for such shipping services during the checkout process
- Order list now has information about Packlink shipments and option to print shipping labels directly from the order list
- Order details page now contains more information about each shipment

#### 1.0.2

**Updates**

- Tested up to: 4.9.1

#### 1.0.0

**Updates**

- Initial version.
