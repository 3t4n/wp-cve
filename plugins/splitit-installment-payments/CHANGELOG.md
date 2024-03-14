## Changelog

All notable changes to this project will be documented in this file
-

### 4.1.4
* Added async logic for refunds
* Tested compatibility with WordPress version 6.4.3 and Woocommerce version 8.4.0

### 4.1.3
* Some minor code improvements and bug fixes
* Tested compatibility with WordPress version 6.3.2 and Woocommerce version 8.2.1

### 4.1.2
* Some minor code improvements and bug fixes

### 4.1.1
* Some minor code improvements and bug fixes

### 4.1.0
* Added minimum supported PHP version 7.2

### 4.0.0
* Added minimum supported PHP version 8.1
* Implemented a new design and new logic for the plugin settings page with authorization through the Splitit Merchant Portal.
* Implemented new On-Site Messaging.
* Implemented new version of the SDK based on a new version of the API v.3
* Tested compatibility with WordPress version 6.2.2 and Woocommerce version 7.7.2
* Some minor code improvements and bug fixes

### 3.3.2
* Added update interruption in case of PHP version mismatch

### 3.3.1
* Rollback version with php-7 compatibility

### 3.3.0
* Implemented a new design and new logic for the plugin settings page with authorization through the Splitit Merchant Portal.
* Implemented new On-Site Messaging.
* Implemented new version of the SDK based on a new version of the API v.3
* Tested compatibility with WordPress version 6.2.2 and Woocommerce version 7.7.2
* Some minor code improvements and bug fixes

### 3.2.3

* Tested compatibility with WordPress version 6.0 and Woocommerce version 6.5.1
* Fix a bug with incorrect displaying UM in footer on some pages
* Fix a bug with generating empty cell in shop table on cart page
* Some minor code improvements and bug fixes

### 3.2.2

* Fix the problem of canceling the plan due to incorrect VAT calculation
* Fix a bug with switching payment methods on the checkout page
* Added notification to the internal Splitit Slack channel and internal Splitit API about activate / deactivate the plugin
* Added logic for UM with the "Enable Splitit per product" setting
* Improved FlexField logic when "Enable Splitit per product" setting
* Some minor code improvements and bug fixes

### 3.2.1

* Upstream message on checkout page
* Check cart total for display upstream message on product page
* Some minor code improvements and bug fixes
* Improved code style quality

### 3.2.0

* Fix issue with order success page
* Fix issue on order pay page
* Fix fatal error: Cannot redeclare GuzzleHttp\describe_type()
* Fix display payment if settings empty
* Fix async order creation process
* Compatibility with "WooCommerce TM Extra Product Options" plugin
* Compatibility with "WooCommerce Multilingual" plugin
* Compatibility with "Speed Booster Pack" plugin
* Compatibility with "WooCommerce Avatax" plugin
* Add a link to documentation (on plugin settings page)
* Some minor code improvements and bug fixes
* Improved code style quality

### 3.1.1

* Some minor code improvements and bug fixes

### 3.1.0

* Allow merchant to choose the num of installments to divide the upstream messages
* Compatibility with "WooCommerce Smart COD" plugin
* Added a new Feature to enable Splitit per product
* Add logo to checkout
* Fix upstream messages init on the product page
* Fix fatal error "Call to a member function is_type() on null"
* Fix display price in UM (£NaN/month)
* Fix issue with optional values in billing address
* Improved code style quality

### 3.0.1

* Updated cart and product page upstream messages
* Added custom checkout loader
* Changed function for get total price for multi currency plugins
* Added settings with position of the upstream messages
* Some minor code improvements and bug fixes

### 1.0.1

* Initial plugin release
