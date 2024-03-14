# Conotoxia Payment Gateway

The Conotoxia Pay Payment Gateway for WooCommerce adds new payment option for Clients.

## Changelog

- 1.31.19 Plugin updated to be compatible with WordPress 6.4.
- 1.31.13 Plugin updated to be compatible with WooCommerce 8.2.
- 1.31.5 Plugin updated to be compatible with WooCommerce 8.1.
- 1.30.9 Plugin updated to be compatible with WordPress 6.3.0 and WooCommerce 7.9.0.
- 1.29.6 Plugin updated to be compatible with WordPress 6.2.2 and WooCommerce 7.8.2.
- 1.27.0 Added the ability to make BLIK payments without leaving the Store (BLIK Level 0).
- 1.26.25 Plugin updated to be compatible with WordPress 6.2 and WooCommerce 7.5.1.
- 1.26.5 Plugin updated in order to make WooCommerce compatible with newer versions (WooCommerce 7.2.3).
- 1.26.0 Plugin updated to be compatible with WordPress 6.1.
- 1.25.16 Support for PHP version less than 7.2 has ended.
- 1.25.0 Plugin updated in order to make WooCommerce compatible with newer versions (WooCommerce 7.0.0).
- 1.21.0 Plugin updated to be compatible with WordPress 6.0 and PHP 8.1.
- 1.20.1 Plugin updated in order to make WooCommerce compatible with newer versions (WooCommerce 5.9.1).
- 1.20.0 Added Vipps icon.
- 1.19.0 Added OnlineTransfer icon.
- 1.18.0 Added option to set Conotoxia Pay as default selected payment method.
- 1.17.0 Added option to select visible payment method icons on the payment method selection screen.
- 1.15.0 Plugin updated in order to make WooCommerce compatible with newer versions (WooCommerce 5.8.0).
- 1.14.0 Added information about the requirement for public key activation.
- 1.13.0 Added support for refund CANCELLED status.
- 1.11.0 Plugin updated in order to make WooCommerce compatible with newer versions (WooCommerce 5.4.2).
- 1.10.1 Removing invalid characters and communication protocol from order description and refund reason.
- 1.8.0 Changes to allow support for custom order identifiers.
- 1.6.0 Plugin updated in order to make WooCommerce compatible with newer versions (WooCommerce 5.1.0).
- 1.4.0 Updated WordPress and WooCommerce (up to 4.9.2).
- 1.1.0 Added a possibility to hide the icon on the payment selection list.
- 0.9.0 Added possibility to generate public and private keys during configuration.
- 0.8.0 Added a sandbox mode that allows you to make payments on a test environment.
- 0.4.5 Added payment identifier on payment summary page.

## Table of contents

* [Requirements](#requirements)
* [Installation and activation](#installation-and-activation)
    * [Manual upload via WordPress admin panel](#manual-upload-via-wordpress-admin-panel)
    * [Manual plugin installation](#manual-plugin-installation)
* [Configuration](#configuration)
    * [Point of sale configuration in the Merchant's panel](#point-of-sale-configuration-in-the-merchants-panel)
    * [Activation of a public key in the Merchant's panel](#activation-of-a-public-key-in-the-merchants-panel)
* [Refund in the Merchant's Panel](#refund)

## Requirements

* WordPress 5.4 - 6.4
* WooCommerce 4.2 - 8.2
* PHP 7.2 - 8.1
* PHP extensions:
    * curl
    * json
    * openssl
    * readline

## Installation and activation

The plugin is available for download from [Wordpress.org](https://wordpress.org/plugins/conotoxia-payment-gateway) website.  
The plugin can be installed in two ways - manual upload via WordPress admin panel or manual plugin installation on web server.  
If you are new to WordPress, we advise using manual upload via WordPress admin panel.

#### Manual upload via WordPress admin panel

1. In the administration panel, select `Plugins → Add New`, then select `Upload Plugin` button and select the zip file
   you downloaded.
2. Activate the plugin by selecting `Activate` in `Plugins → Installed Plugins`.

#### Manual plugin installation

1. The downloaded plugin should be unpacked into `wp-content/plugins` directory on web server with your store files.
2. Activate the plugin by selecting `Activate` in `Plugins → Installed Plugins`.

## Configuration

1. Go to WooCommerce plugin settings panel `WooCommerce → Settings`.
2. Enter `Payments` section.
3. Look for `Conotoxia Pay` on payments methods list.
4. Click `Set up` button next to `Conotoxia Pay` payment method.
5. Fill all fields in configuration page:
    - `API Client identifier` and `API Client secret` - API access data that can be obtained
      in [Merchant's panel](https://fx.conotoxia.com/merchant/configuration) in `Access data` section.
    - `Point of sale identifier*` - the identifier of the created point of sale.
    - `Private key` - it is possible to generate a private key on the plugin configuration page. A public key is
      generated from the private key entered on the plugin configuration page. This key is automatically transferred to
      Conotoxia Pay when generating key. It is not necessary to enter the key in the Merchant's panel. Additional
      instructions on how to generate keys can be found in
      the [documentation](https://docs.conotoxia.com/payments/online-shops#generating-a-public-key).
    - `Sandbox mode` - it is possible to test the module on a test environment. In order to gain access to the test
      environment, go to the [conotoxia.com](https://conotoxia.com/contact-us/business).
    - `Conotoxia Pay payment icon` - it is possible to add the Conotoxia Pay icon to the payment selection list.
    - `Payment method icons` - it is possible to select visible payment method icons on the payment method selection 
      screen.
6. Enable payment using `Enable/Disable` checkbox.

`*` Store and point of sale can be created through the wizard in
the [Merchant's panel](https://fx.conotoxia.com/merchant/).

### Point of sale configuration in the [Merchant's panel](https://fx.conotoxia.com/merchant)

The point of sale should be set up according to the configuration below:

- `URL address for payment creation notification` - https://store.com/?wc-api=WC_Gateway_Conotoxia_Pay
- `URL address for refund creation notification` - https://store.com/?wc-api=WC_Gateway_Conotoxia_Pay
- `URL address for successfully executed payment` - https://store.com/
- `URL address for unsuccessful payment` - https://store.com/
- `List of allowed URL addresses` - http://store.com/

Where there is a `store.com`, it should be replaced with your woocommerce shop domain.

### Activation of a public key in the [Merchant's panel](https://fx.conotoxia.com/merchant)
The public key created and sent using the plugin must be activated.  
In order to activate public key redirect to [Merchant's panel](https://fx.conotoxia.com/merchant/configuration).

# Refund
Refunds can be ordered from within the plugin and from the [Merchant's panel](https://fx.conotoxia.com/merchant).
In case of ordering from Merchant's Panel, you need to fill in `External payment refund number` according to the order number from the store.
If the field is omitted then no notification will be delivered to the store.
