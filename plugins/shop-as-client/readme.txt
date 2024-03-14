=== Shop as Client for WooCommerce ===
Contributors: webdados, ptwooplugins 
Tags: woocommerce, ecommerce, e-commerce, client, customer, checkout, admin, phone order, webdados
Requires at least: 5.4
Tested up to: 6.5
Requires PHP: 7.0
Stable tag: 3.5
License: GPLv3
License URI: https://www.gnu.org/licenses/gpl-3.0.html

Allows a WooCommerce store Administrator or Shop Manager to use the frontend and assign a new order to a registered or new customer. Useful for phone or email orders.

== Description ==

Allows a WooCommerce store Administrator or Shop Manager to use the frontend and assign a new order to a registered or new customer, taking advantage of all the frontend functionalities that might not exist on the backend, which can be very useful for phone or email orders.

The order will automatically be assigned to a registered customer if the billing email matches. If no registered user is found, the shop manager can decide to either create a new user or leave the order as if it was inserted by a guest.

Two new fields are added to the billing checkout section, for logged in administrators and shop managers.

== Features ==

* Enter email and phone orders directly on the frontend;
* Create orders for existing users if the email address exists on the customer database;
* Choose either to create a new user or leave the order as if it was inserted by a guest if the email address does not exist on the customer database;
* BETA compatibility for the WooCommerce block-based Checkout (only on the Free version for now)

== PRO add-on features ==

In addition to all you can do with the free plugin, the [paid add-on](https://ptwooplugins.com/product/shop-as-client-for-woocommerce-pro-add-on/?utm_source=wordpress.org&utm_medium=link&utm_campaign=shopasclient_woocommerce_plugin) offers you a number of additional features:

* Search customer by email address (on the users’ table and optionally also previous orders) and automatically fill in the checkout details;
* Fake payment gateway that allows administrators and shop managers to finalize the order and ask for the order payment by sending the customer a payment link via email;
* Autocomplete feature while typing the customer email, first name, last name, company or phone number
* Show handler and allow filtering by handler on the admin orders list
* [Developer filter](https://gist.github.com/webdados/de05d48a99063ac25f6462b1dedba2ee) to add custom fields to the automatically filled checkout details;
* [User Switching](https://wordpress.org/plugins/user-switching/) integration to benefit the fact the customer is logged in and still use our plugin functionalities, like the payment request gateway and seller tracking. WooCommerce block-based Checkout compatible.
* Possibility to start the order with a blank checkout form;
* Set default values for the “Shop as client” and “Create user” fields;
* Option to update the customer details on his profile;
* Get custom fields from:
	* [WooCommerce EU VAT Assistant](https://wordpress.org/plugins/woocommerce-eu-vat-assistant/)
	* [WooCommerce EU VAT Number](https://woocommerce.com/products/eu-vat-number/)
	* [Invoicing with InvoiceXpress for WooCommerce](https://invoicewoo.com/)
	* [NIF (Num. de Contribuinte Português) for WooCommerce](https://wordpress.org/plugins/nif-num-de-contribuinte-portugues-for-woocommerce/)
* Technical support;
* Continued development;

**Now available in lifetime licensing**

Try the PRO add-on for free [here](https://ptwooplugins.com/product/shop-as-client-for-woocommerce-pro-add-on/?utm_source=wordpress.org&utm_medium=link&utm_campaign=shopasclient_woocommerce_plugin#testimonials)!

= Other (premium) plugins =

Already know our other WooCommerce (premium) plugins?

* [Taxonomy/Term and Role based Discounts for WooCommerce](https://ptwooplugins.com/product/taxonomy-term-and-role-based-discounts-for-woocommerce-pro-add-on/) - Easily create bulk discount rules for products based on any taxonomy terms (built-in or custom)
* [Simple WooCommerce Order Approval](https://ptwooplugins.com/product/simple-woocommerce-order-approval/) - The hassle-free solution for WooCommerce order approval before payment

== Installation ==

* Use the included automatic install feature on your WordPress admin panel and search for “Shop as client”.

== Frequently Asked Questions ==

= How to set “Shop as client” to “No” by default? =

Add [this](https://gist.github.com/webdados/fec5983b1be08dc09f290ce707a1bb44) to your (child) theme functions.php file, or use the [PRO add-on](https://ptwooplugins.com/product/shop-as-client-for-woocommerce-pro-add-on/?utm_source=wordpress.org&utm_medium=link&utm_campaign=shopasclient_woocommerce_plugin).

= How to set “Create user” to “Yes” by default? =

Add [this](https://gist.github.com/webdados/6e0f3cedb315bfdb9ac258bc6e630101) to your (child) theme functions.php file, or use the [PRO add-on](https://ptwooplugins.com/product/shop-as-client-for-woocommerce-pro-add-on/?utm_source=wordpress.org&utm_medium=link&utm_campaign=shopasclient_woocommerce_plugin).

= Which user roles have access to the “Shop as client” functionality? =

Administrators and Shop managers can use “Shop as client” on the checkout.
Add [this](https://gist.github.com/webdados/0f1a8e5ca2cd98276a9ae73918e9b842) to your (child) theme functions.php file if you want to allow other user roles to use this functionality.

= I’m giving discounts user or user role-based. Will this work? =

No. Yes. It’s complicated...

The logged in user is the Administrator or Shop Manager. It’s not possible to integrate with the vast amount of user or user role-based discount plugins when the logged in user is not the customer himself, so any customer roled-based discounts will not be applied.

However, our PRO add-on integrates with the [User Switching](https://wordpress.org/plugins/user-switching/) plugin and sets the Administrator or Shop Manager that switched to the customer account as the order handler upon checkout, thus allowing to benefit from the fact the customer is logged in and still use our plugin functionalities, like the payment request gateway and seller tracking.

= Is this plugin compatible with the new WooCommerce High-Performance Order Storage? =

Yes, from version 2.1 onwards.

= Is this plugin compatible with the new WooCommerce block-based Cart and Checkout? =

Yes, in beta only on the Free version from version 3.5 onwards.

Known limitations:

* Only core WooCommerce fields are saved to the customer user, and not custom or 3rd party fields, if the `shop_as_client_update_customer_data` filter is set to true;
* No warning about the lack of information on the “Order Received” / “Thank You” page on WooCommerce 7.8.1 and above;
* Report to us if you find more limitations, using the [support forum](https://wordpress.org/support/plugin/shop-as-client/);

You can also use the Blocks Checkout on the [PRO add-on](https://ptwooplugins.com/product/shop-as-client-for-woocommerce-pro-add-on/?utm_source=wordpress.org&utm_medium=link&utm_campaign=shopasclient_woocommerce_plugin) using the [User Switching](https://wordpress.org/plugins/user-switching/) integration.

We’re working to make it 100% compatible shortly.

= Can I contribute with a translation? =

Sure. Go to [GlotPress](https://translate.wordpress.org/projects/wp-plugins/shop-as-client) and help us out.

= Where do I report security vulnerabilities found in this plugin? =  
 
You can report any security bugs found in the source code of this plugin through the [Patchstack Vulnerability Disclosure Program](https://patchstack.com/database/vdp/shop-as-client). The Patchstack team will assist you with verification, CVE assignment and take care of notifying the developers of this plugin.

= More FAQs =

Check out the [PRO add-on FAQs](https://ptwooplugins.com/faqs/shop-as-client-for-woocommerce-pro-add-on-faq/)

== Changelog ==

= 3.5 - 2024-03-04 =
* [NEW] BETA compatibility for the WooCommerce block-based Checkout (only on the Free version for now)
* [DEV] Tested with WordPress 6.5-beta3-57738 and WooCommerce 8.7.0-beta.2

= 3.4 - 2024-02-20 =
* [DEV] Change plugin loading priority
* [PRO add-on](https://ptwooplugins.com/product/shop-as-client-for-woocommerce-pro-add-on/?utm_source=wordpress.org&utm_medium=link&utm_campaign=shopasclient_woocommerce_plugin): Requires Shop As Client (free) 3.4
* [DEV] Requires WooCommerce 5.4 or above
* [DEV] Tested with WordPress 6.5-beta1-57656 and WooCommerce 8.6.0

= 3.3 - 2024-01-29 =
* [DEV] [PRO add-on](https://ptwooplugins.com/product/shop-as-client-for-woocommerce-pro-add-on/?utm_source=wordpress.org&utm_medium=link&utm_campaign=shopasclient_woocommerce_plugin): Deprecate non-autocomplete search method on the checkout
* [DEV] [PRO add-on](https://ptwooplugins.com/product/shop-as-client-for-woocommerce-pro-add-on/?utm_source=wordpress.org&utm_medium=link&utm_campaign=shopasclient_woocommerce_plugin): Plugin updater improvements

= 3.2 - 2024-01-23 =
* [PRO add-on](https://ptwooplugins.com/product/shop-as-client-for-woocommerce-pro-add-on/?utm_source=wordpress.org&utm_medium=link&utm_campaign=shopasclient_woocommerce_plugin): Better feedback when the license is expired
* Tested with WordPress 6.5-alpha-57299 and WooCommerce 8.5.1

= 3.1 - 2023-12-19 =
* [PRO add-on](https://ptwooplugins.com/product/shop-as-client-for-woocommerce-pro-add-on/?utm_source=wordpress.org&utm_medium=link&utm_campaign=shopasclient_woocommerce_plugin): Better User Switching integration: Blocks Checkout compatibility and “switch back to admin” links on the order received (thank you) page

= 3.0 - 2023-12-15 =
* [PRO add-on](https://ptwooplugins.com/product/shop-as-client-for-woocommerce-pro-add-on/?utm_source=wordpress.org&utm_medium=link&utm_campaign=shopasclient_woocommerce_plugin): User Switching integration
* Small adjustments on the order edit screen information code

= 2.8.1 - 2023-12-15 =
* Fix fatal error when performing the ajax search call in the checkout, with HPOS enabled
* Fix PHP notices

= 2.8 - 2023-12-15 =
* [PRO add-on](https://ptwooplugins.com/product/shop-as-client-for-woocommerce-pro-add-on/?utm_source=wordpress.org&utm_medium=link&utm_campaign=shopasclient_woocommerce_plugin): Show handler and allow filtering by handler on the admin orders list, now compatible with HPOS
* [PRO add-on](https://ptwooplugins.com/product/shop-as-client-for-woocommerce-pro-add-on/?utm_source=wordpress.org&utm_medium=link&utm_campaign=shopasclient_woocommerce_plugin): Hide license key from shop managers (only available for users with “manage_options” capabilities, normally Administrators)
* Small internal changes to better integrate with the PRO add-on and a future Funnelkit integration
* Tested with WordPress 6.5-alpha-57189 and WooCommerce 8.4

= 2.7 - 2023-11-27 =
* [PRO add-on](https://ptwooplugins.com/product/shop-as-client-for-woocommerce-pro-add-on/?utm_source=wordpress.org&utm_medium=link&utm_campaign=shopasclient_woocommerce_plugin): Update plugin translations online instead of shipping them with the main plugin
* [PRO add-on](https://ptwooplugins.com/product/shop-as-client-for-woocommerce-pro-add-on/?utm_source=wordpress.org&utm_medium=link&utm_campaign=shopasclient_woocommerce_plugin): HTML tag closing fix on the settings page
* Requires WordPress 5.4
* Tested with WordPress 6.5-alpha-57137 and WooCommerce 8.3.1

= 2.6 - 2023-10-12 =
* Do not show other plugins promotion banner if the [PRO add-on](https://ptwooplugins.com/product/shop-as-client-for-woocommerce-pro-add-on/?utm_source=wordpress.org&utm_medium=link&utm_campaign=shopasclient_woocommerce_plugin) is active
* Tested with WordPress 6.4-beta2-56809 and WooCommerce 8.2.0

= 2.5 - 2023-08-01 =
* [PRO add-on](https://ptwooplugins.com/product/shop-as-client-for-woocommerce-pro-add-on/?utm_source=wordpress.org&utm_medium=link&utm_campaign=shopasclient_woocommerce_plugin): Fix two fatal errors (`print_f` and `sprint_f` instead of `printf` and `sprintf`)

= 2.4 - 2023-07-25 =
* [PRO add-on](https://ptwooplugins.com/product/shop-as-client-for-woocommerce-pro-add-on/?utm_source=wordpress.org&utm_medium=link&utm_campaign=shopasclient_woocommerce_plugin): New autocomplete option to try to find users by registration first name, last name, and email if not found by WooCommerce meta.
* [PRO add-on](https://ptwooplugins.com/product/shop-as-client-for-woocommerce-pro-add-on/?utm_source=wordpress.org&utm_medium=link&utm_campaign=shopasclient_woocommerce_plugin): Fix a bug where the autocomplete would not be activated if the “Shop as client field default” option is set no “No”
* Tested with WordPress 6.3-RC1-56289 and WooCommerce 8.0.0-beta.1

= 2.3.1 - 2023-07-14 =
* Fix updater for PRO add-on users

= 2.3 - 2023-07-14 =
* Warning about the lack of information on the “Order Received” / “Thank You” page on WooCommerce 7.8.1 and above
* [PRO add-on](https://ptwooplugins.com/product/shop-as-client-for-woocommerce-pro-add-on/?utm_source=wordpress.org&utm_medium=link&utm_campaign=shopasclient_woocommerce_plugin): Show the “Order Received” / “Thank You” page on WooCommerce 7.8.1 and above
* Remove the InvoiceXpress banner and add the [Simple Order Approval for WooCommerce](https://ptwooplugins.com/product/simple-woocommerce-order-approval/) one
* Fix jQuery deprecation notice

= 2.2 - 2023-07-07 =
* [PRO add-on](https://ptwooplugins.com/product/shop-as-client-for-woocommerce-pro-add-on/?utm_source=wordpress.org&utm_medium=link&utm_campaign=shopasclient_woocommerce_plugin): Exclude some 3rd party plugin fields from being cleared from the checkout when the “Clear checkout fields” option is set to “Yes”, and a new `shop_as_client_empty_checkout_field_exclusions` filter to allow developers to add more fields to the exclusions
* Tested with WooCommerce 7.9.0-rc.3

= 2.1 - 2023-05-13 =
* High-Performance Order Storage compatible (in beta and only on WooCommerce 7.1 and above)
* [PRO add-on](https://ptwooplugins.com/product/shop-as-client-for-woocommerce-pro-add-on/?utm_source=wordpress.org&utm_medium=link&utm_campaign=shopasclient_woocommerce_plugin): Add payment gateway support for [Subscriptions for WooCommerce](https://wordpress.org/plugins/subscriptions-for-woocommerce/) and confirmed support for [WooCommerce Subscriptions](https://woocommerce.com/products/woocommerce-subscriptions/)
* Requires WooCommerce 5.0 or above
* Tested with WordPress 6.3-alpha-55693 and WooCommerce 7.7

= 2.0 - 2022-10-13 =
* [PRO add-on](https://ptwooplugins.com/product/shop-as-client-for-woocommerce-pro-add-on/?utm_source=wordpress.org&utm_medium=link&utm_campaign=shopasclient_woocommerce_plugin): Show handler and allow filtering by handler on the admin orders list
* Tested with WordPress 6.1-RC1-54506 and WooCommerce 7.0

= 1.9.2 - 2022-07-28 =
* [PRO add-on](https://ptwooplugins.com/product/shop-as-client-for-woocommerce-pro-add-on/?utm_source=wordpress.org&utm_medium=link&utm_campaign=shopasclient_woocommerce_plugin): Fix a bug on the updater class

= 1.9.1 - 2022-07-28 =
* Fixed a bug that would assign an order to a random user if no email address was provided
* Better feedback when no email address is provided
* [PRO add-on](https://ptwooplugins.com/product/shop-as-client-for-woocommerce-pro-add-on/?utm_source=wordpress.org&utm_medium=link&utm_campaign=shopasclient_woocommerce_plugin): Create user without checking out
* New filter `shop_as_client_user_email_if_empty` to allow developers to provide an email address programmatically when none is provided by the user
* Only show the InvoiceXpress nag for portuguese stores
* Requires WordPress 5.0 and WooCommerce 4.0 or above
* Tested with WordPress 6.1-alpha-53789 and WooCommerce 6.8.0-beta.2

= 1.9.0 - 2022-07-28 =
* Unreleased version with a bug

= 1.8.0 - 2022-05-05 =
* New brand: PT Woo Plugins 🥳
* Tested with WooCommerce 6.5.0-rc.1 and WordPress 6.0-beta2-53236

= 1.7.2 - 2021-05-21 =
* [PRO add-on](https://ptwooplugins.com/product/shop-as-client-for-woocommerce-pro-add-on/?utm_source=wordpress.org&utm_medium=link&utm_campaign=shopasclient_woocommerce_plugin): Fix on the payment gateway: stock behaviour when setting the order “On hold”

= 1.7.1 - 2021-05-21 =
* [PRO add-on](https://ptwooplugins.com/product/shop-as-client-for-woocommerce-pro-add-on/?utm_source=wordpress.org&utm_medium=link&utm_campaign=shopasclient_woocommerce_plugin): Fix on the payment gateway: “On hold” orders should be payable

= 1.7.0 - 2021-05-21 =
* [PRO add-on](https://ptwooplugins.com/product/shop-as-client-for-woocommerce-pro-add-on/?utm_source=wordpress.org&utm_medium=link&utm_campaign=shopasclient_woocommerce_plugin): New fake payment gateway option to set the order “On hold” instead of “Pending” after the checkout
* [PRO add-on](https://ptwooplugins.com/product/shop-as-client-for-woocommerce-pro-add-on/?utm_source=wordpress.org&utm_medium=link&utm_campaign=shopasclient_woocommerce_plugin): Greek translation
* Small code fixes
* Drop support for WooCommerce below 3.0
* Requires PHP 7
* [PRO add-on](https://ptwooplugins.com/product/shop-as-client-for-woocommerce-pro-add-on/?utm_source=wordpress.org&utm_medium=link&utm_campaign=shopasclient_woocommerce_plugin): Add the “Update URI” header
* Tested with WordPress 5.8-alpha-50943 and WooCommerce 5.4-beta.1

= 1.6.6 - 2021-03-10 =
* Tested with WordPress 5.8-alpha-50516 and WooCommerce 5.1.0

= 1.6.5 =
* [PRO add-on](https://ptwooplugins.com/product/shop-as-client-for-woocommerce-pro-add-on/?utm_source=wordpress.org&utm_medium=link&utm_campaign=shopasclient_woocommerce_plugin): Hebrew translation (Thanks [fixerwolfe](https://profiles.wordpress.org/fixerwolfe/))
* Tested with WordPress 5.7-alpha-49862 and WooCommerce 5.0.0-beta.1

= 1.6.4 =
* [PRO add-on](https://ptwooplugins.com/product/shop-as-client-for-woocommerce-pro-add-on/?utm_source=wordpress.org&utm_medium=link&utm_campaign=shopasclient_woocommerce_plugin): Add option to not send the payment request email on the fake payment gateway
* readme.txt update
* Tested with WordPress 5.5-RC1-48708 and WooCommerce 4.4.0-beta.1

= 1.6.3 =
* [PRO add-on](https://ptwooplugins.com/product/shop-as-client-for-woocommerce-pro-add-on/?utm_source=wordpress.org&utm_medium=link&utm_campaign=shopasclient_woocommerce_plugin): Make Autocomplete enabled by default and no longer beta
* [PRO add-on](https://ptwooplugins.com/product/shop-as-client-for-woocommerce-pro-add-on/?utm_source=wordpress.org&utm_medium=link&utm_campaign=shopasclient_woocommerce_plugin): Fix WooCommerce EU VAT Number integration
* Tested with WordPress 5.5-alpha-47609 and WooCommerce 4.1.0-rc.1

= 1.6.2 =
* [PRO add-on](https://ptwooplugins.com/product/shop-as-client-for-woocommerce-pro-add-on/?utm_source=wordpress.org&utm_medium=link&utm_campaign=shopasclient_woocommerce_plugin): Use WooCommerce Ajax endpoint instead of WordPress admin-ajax.php
* Tested with WordPress 5.5-alpha-47547 and WooCommerce 4.0.1

= 1.6.0 =
* [PRO add-on](https://ptwooplugins.com/product/shop-as-client-for-woocommerce-pro-add-on/?utm_source=wordpress.org&utm_medium=link&utm_campaign=shopasclient_woocommerce_plugin): New Autocomplete (BETA) feature while typing the customer email, first name, last name, company or phone number
* Tested with WooCommerce 4.0.0-rc.1

= 1.5.3 =
* New `shop_as_client_update_customer_data` filter so that developers can allow the customer details to be updated on their profile;
* [PRO add-on](https://ptwooplugins.com/product/shop-as-client-for-woocommerce-pro-add-on/?utm_source=wordpress.org&utm_medium=link&utm_campaign=shopasclient_woocommerce_plugin): New setting to update the customer details on his profile;

= 1.5.2 =
* Changes on the InvoiceXpress banner

= 1.5.1 =
* Bugfix: PHP notice

= 1.5.0 =
* [PRO add-on](https://ptwooplugins.com/product/shop-as-client-for-woocommerce-pro-add-on/?utm_source=wordpress.org&utm_medium=link&utm_campaign=shopasclient_woocommerce_plugin): New (fake) payment gateway that allows administrators and shop managers to finalize the order and ask for the order payment by sending the customer a payment link via email

= 1.4.0 =
* [PRO add-on](https://ptwooplugins.com/product/shop-as-client-for-woocommerce-pro-add-on/?utm_source=wordpress.org&utm_medium=link&utm_campaign=shopasclient_woocommerce_plugin): Get customer details from orders if not found as a user, useful if you want to insert an order for a client that previously shopped as a guest
* [PRO add-on](https://ptwooplugins.com/product/shop-as-client-for-woocommerce-pro-add-on/?utm_source=wordpress.org&utm_medium=link&utm_campaign=shopasclient_woocommerce_plugin): New `shop_as_client_pro_search_order_statuses` [filter](https://gist.github.com/webdados/412cf06fdbf86ba2cef9e900ab95838c) to limit the order statuses where the customer is searched (if not found as a user)
* [PRO add-on](https://ptwooplugins.com/product/shop-as-client-for-woocommerce-pro-add-on/?utm_source=wordpress.org&utm_medium=link&utm_campaign=shopasclient_woocommerce_plugin): Get VAT number from WooCommerce EU VAT Number

= 1.3.0 =
* Hide “Create user” when “Shop as client” is set to “No”
* Add version number when loading the javascript functions file
* Sync version number with the [PRO add-on](https://ptwooplugins.com/product/shop-as-client-for-woocommerce-pro-add-on/?utm_source=wordpress.org&utm_medium=link&utm_campaign=shopasclient_woocommerce_plugin)
* Fix `Tested up to` tag

= 1.0.4 =
* Tested with WordPress 5.3.3-alpha-46995 and WooCommerce 3.9.0-rc.1

= 1.0.3 =
* Fix version number

= 1.0.2 =
* PRO add-on announcement

= 1.0.1 =
* Fix fatal error

= 1.0 =
* Preparation for the, soon to be released, PRO add-on
* Search the customer also by billing email in addition to the profile email
* Invoicing with InvoiceXpress for WooCommerce nag
* Tested with WordPress 5.3.1-alpha-46798 and WooCommerce 3.8.1

= 0.6 =
* Tested with WordPress 5.2.5-alpha and WooCommerce 3.8.0

= 0.5 =
* Fix the order handler information - we now store it on a specific custom field and it will only be available for orders created after this plugin version
* Prevent the logged in user details to be updated with the client details

= 0.4 =
* Show the order handler on the order edit screen (Thanks Albert Amar / Israprods)
* New `shop_as_client_allow_checkout` filter so that developers can allow other user roles to use the “Shop as client” functionality (Thanks CJ Ratliff / A+ Media for suggesting this)
* Tested with WordPress 5.2.3-alpha-45552 and WooCommerce 3.7.0-beta.1
* WordPress 4.9 minimum requirement
* PHP 5.6 minimum requirement
* Translations update

= 0.3 =
* Force field defaults
* Update readme.txt
* Fix plugin version number
* Tested with WordPress 5.1.1 and WooCommerce 3.6.2

= 0.2 =
* The generated password for a new user account is now sent via email, unless `false` is returned to the `shop_as_client_email_password` filter
* The username will be generated from the email (text before @) if the “When creating an account, automatically generate a username from the customer’s email address” WooCommerce option is checked, otherwise the whole email is used as username
* Bumped `WC tested up to` tag
* Release sponsored by Albert Amar / Israprods

= 0.1 =
* Initial release (sponsored by [telasprojeção.pt](https://telasprojecao.pt)