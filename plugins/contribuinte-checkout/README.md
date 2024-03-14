# Contribuinte Checkout

[![License: GPL v2](https://img.shields.io/badge/License-GPL_v2-green.svg)](https://www.gnu.org/licenses/old-licenses/gpl-2.0.en.html)
![WordPress Plugin: Required PHP Version](https://img.shields.io/badge/php-%3E%3D5.6-blue)
![WordPress Plugin: Tested PHP Version](https://img.shields.io/badge/php-8.1%20tested-blue)
![WordPress Plugin: Required WP Version](https://img.shields.io/badge/WordPress-%3E%3D%205.0-orange)
![WordPress Plugin: Tested WP Version](https://img.shields.io/badge/WordPress-6.3%20tested-orange)
![WooCommerce: Required Version](https://img.shields.io/badge/WooCommerce-%3E%3D%203.0.0-orange)
![WooCommerce: Tested Version](https://img.shields.io/badge/WooCommerce-8.0.2%20tested-orange)

**Contributors:**       [moloni-pt](https://github.com/moloni-pt)  
**Tested up to:**       6.3  
**WC tested up to**     8.0.2  
**Requires at least:**  5.0  
**Requires PHP:**       5.6  
**PHP tested up to:**   8.1  
**Tags:**               Invoicing, Orders, Customers, VAT, WooCommerce, IVA, Contribuinte, NIF  
**Stable tag:**         1.0.50  
**License:**            GPLv2 or later    
**License URI:**        [https://www.gnu.org/licenses/gpl-2.0.html](https://www.gnu.org/licenses/gpl-2.0.html)  

## Description

With this plugin you can add VAT and VIES support to your WooCommerce store. The VAT field will be saved as `_billing_vat`.

**Important:** this plugin requires WooCommerce 3.0.0 or higher.  
**Warning:** to enable and use VIES information you need to have SOAP extension enabled (SoapClient PHP class).

## Features

* Adds VAT field to billing form.
* Adds VAT field to outgoing email.
* Adds VAT field to checkout billing information.
* Adds VAT field to admin orders page.
* Change VAT field label and description.
* Validate Portuguese VAT numbers.
* Choose how to handle vat field validation errors.
* You can make VAT field required.
* You can add VIES information to admin order page, checkout and user billing page.
* Adds settings page under WooCommerce menu so you manage all the features.

## Translations

* English.
* Portuguese.

## Installation
This plugin can be installed via FTP or using the Wordpress plugin installer.

Via FTP
1. Upload the plugin files to the `/wp-content/plugins/woocommerce-contribuinte` directory
2. Activate the plugin through the `Plugins` option visible in WordPress

## Screenshots
1. Settings where you can change VAT field behaviour.
2. Customers can add their VAT to their billing information.
3. VAT field in admin page.
4. Vies Information in admin page.

## Upgrade Notice
### 1.0.0
Released version 1.0.0.

## Changelog
### 1.0.50
* Added live VAT validation in checkout and billing address edit.
* Tested up to version 6.3.0 of Wordpress.
* Tested up to version 8.0.2 of WooCommerce.

### 1.0.46
* Updated image and banner.
* Tested up to version 6.2.0 of Wordpress.
* Tested up to version 7.5.1 of WooCommerce.

### 1.0.45
* Fix undefined settings warning.

### 1.0.44
* Added HPOS compatibility.
* Remove vat prefix from Vies verification.
* Tested up to version 6.1.1 of Wordpress.
* Tested up to version 7.2.2 of WooCommerce.
* Tested up to version 8.1 of PHP.

### 1.0.43
* New setting to make VAT field required on orders over 1000€
* Tested up to version 5.9.1 of Wordpress.
* Tested up to version 6.2.1 of WooCommerce.

### 1.0.42
* VAT number is now included in billing address container in emails and "thank you" page.
* Tested up to version 5.8.1 of Wordpress.
* Tested up to version 5.8.0 of WooCommerce.

### 1.0.41
* Tested up to version 5.7 of Wordpress.
* Tested up to version 5.1.0 of WooCommerce.
* Tested up to version 8.0 of PHP.

### 1.0.4
* Increased max char number for foreign customers

### 1.0.3
* Added settings button to the plugins page, under plugin name.

### 1.0.0
* Initial release
