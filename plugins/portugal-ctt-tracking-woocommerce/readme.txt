=== Portugal CTT Tracking for WooCommerce ===
Contributors: webdados, ptwooplugins
Tags: woocommerce, shipping, ctt, tracking, ecommerce, e-commerce, delivery, webdados
Requires at least: 5.6
Tested up to: 6.5
Requires PHP: 7.0
Stable tag: 2.2

Lets you associate a tracking code with a WooCommerce order so that both the store owner and the client can track the order sent with CTT

== Description ==

Shop owners using the Portuguese carrier CTT can use this plugin to associate the tracking code with the WooCommerce order and track it on the order edit screen.

Clients will also be able to track the order shipping status on the “My Account” page.

**[Due to changes in CTT's systems, the tracking information is currently unavailable and can only be accessed through their website. We are working on a workaround but we cannot commit to a deadline.](https://wordpress.org/support/topic/informacao-de-seguimento-indisponivel-ler-antes-de-criar-topico/)**

= Features: =

* Add a CTT tracking code to the order
* Shop owner can check the shipping status on the order edit screen, by clicking the CTT link
* Client can check the delivery status on the order page on My account, by clicking the CTT link
* Tracking code is added to order emails sent to the client
* High-Performance Order Storage compatible
* Block based checkout compatible

= Do your customers still write the full address details manually on the checkout? =

Activate the automatic filling of the address details at the checkout, including street name and neighbourhood, based on the postal, avoiding incorrect data at the time of shipping, with our plugin [Portuguese Postcodes for WooCommerce](https://ptwooplugins.com/product/portuguese-postcodes-for-woocommerce-technical-support/)

= Are you already issuing automatic invoices on your WooCommerce store? =

If not, get to know our new plugin: [Invoicing with InvoiceXpress for WooCommerce](https://wordpress.org/plugins/woo-billing-with-invoicexpress/)

= Other (premium) plugins =

Already know our other WooCommerce (premium) plugins?

* [Portuguese Postcodes for WooCommerce](https://ptwooplugins.com/product/portuguese-postcodes-for-woocommerce-technical-support/) - Automatic filling of the address details at the checkout, including street name and neighbourhood, based on the postal code
* [Invoicing with InvoiceXpress for WooCommerce](https://wordpress.org/plugins/woo-billing-with-invoicexpress/) - Automatically issue invoices directly from the WooCommerce order
* [DPD Portugal for WooCommerce](https://ptwooplugins.com/product/dpd-portugal-for-woocommerce/) - Create shipping and return guide in the DPD webservice directly from the WooCommerce order
* [Feed KuantoKusta for WooCommerce](https://ptwooplugins.com/product/feed-kuantokusta-for-woocommerce-pro/) - Publish your products on Kuanto Kusta with this easy to use feed generator
* [Simple WooCommerce Order Approval](https://ptwooplugins.com/product/simple-woocommerce-order-approval/) - The hassle-free solution for WooCommerce orders approval before payment
* [Shop as Client for WooCommerce](https://ptwooplugins.com/product/shop-as-client-for-woocommerce-pro-add-on/) - Quickly create orders on behalf of your customers
* [Taxonomy/Term and Role based Discounts for WooCommerce](https://ptwooplugins.com/product/taxonomy-term-and-role-based-discounts-for-woocommerce-pro-add-on/) - Easily create bulk discount rules for products based on any taxonomy terms (built-in or custom)
* [DPD / SEUR / Geopost Pickup and Lockers network for WooCommerce](https://ptwooplugins.com/product/dpd-seur-geopost-pickup-and-lockers-network-for-woocommerce/) - Deliver your WooCommerce orders on the DPD and SEUR Pickup network of Parcelshops and Lockers in 9 European countries

== Installation ==

* Use the included automatic install feature on your WordPress admin panel and search for “Portugal CTT Tracking for WooCommerce”
* Go to WooCommerce > Shipping > Shipping options and set your preferences

== Frequently Asked Questions ==

= Is this a shipping method? =

No.

= Can this plugin calculate the CTT shipping costs? =

No.

= What else can this plugin do besides tracking the CTT order? =

Nothing.

= Can I trigger an event when the order changes status at CTT? =

No.

= Is it possible to set the CTT tracking code on an order via hooks? =

Yes.

You can use the `portugal_ctt_tracking_set_tracking_code` action with two arguments, `$order_id` and `$tracking_code`, to set the tracking code on your order.

= I need technical support. Who should I contact, CTT or Webdados? =

The development and support is [Webdados](https://www.webdados.pt) responsibility.
For free/standard support you should use the support forums at WordPress.org but no answer is guaranteed.
For premium/urgent support or custom developments you should contact [Webdados](https://www.webdados.pt/contactos/) directly. Charges may (and most certainly will) apply.

= Where do I report security vulnerabilities found in this plugin? =  
 
You can report any security bugs found in the source code of this plugin through the [Patchstack Vulnerability Disclosure Program](https://patchstack.com/database/vdp/portugal-ctt-tracking-woocommerce). The Patchstack team will assist you with verification, CVE assignment and take care of notifying the developers of this plugin.

== Changelog ==

= 2.2 - 2024-01-19 =
* Remove code related to fetch the information from the CTT website, including simplehtmldom, as is not likely we'll be able to activate that functionality again
* Security update
* Requires WooCommerce 6.0 and WordPress 5.6
* Tested with WordPress 6.5-alpha-57299 and WooCommerce 8.5.1

= 2.1 - 2023-12-12 =
* Declare WooCommerce block-based Cart and Checkout compatibility
* Requires WooCommerce 5.0 and WordPress 5.4
* Tested with WordPress 6.5-alpha-57159 and WooCommerce 8.4.0-rc.1

= 2.0.2 - 2023-07-08 =
* Requires WooCommerce 5.0
* Tested with WordPress 6.3-beta3-56143 and WooCommerce 7.9.0-rc.2

= 2.0.1 - 2022-11-30 =
* Removed a debug echo on the emails

= 2.0.0 - 2022-11-18 =
* WooCommerce 7.1 and above High-Performance Order Storage compatible (in beta)
* Fix a bug on emails when the shop language is not the same as the user managing the orders
* Fix jQuery deprecations
* Tested with WordPress 6.2-alpha-54855 and WooCommerce 7.1

= 1.7.0 - 2022-09-01 =
* **Due to changes in CTT's systems, the tracking information is currently unavailable and can only be accessed through their website**
* Requires WooCommerce 4.0
* Tested with WordPress 6.1-alpha-54043 and WooCommerce 6.9.0-beta.2

= 1.6.0 - 2022-07-27 =
* Allow users to update CTT tracking information at the order details on their account (sponsored by [Esconderijo dos Livros](https://esconderijodoslivros.pt/))

= 1.5.0 - 2022-07-21 =
* New actions to allow 3rd party developers to set the tracking code and force update of information from CTT: `portugal_ctt_tracking_set_tracking_code` and `portugal_ctt_tracking_update_info_for_order`
* Tested with WordPress 6.1-alpha-53743 and WooCommerce 6.8.0-beta.1

= 1.4.0 - 2022-06-29 =
* New brand: PT Woo Plugins 🥳
* Requires WordPress 5.0, WooCommerce 3.0 and PHP 7.0
* Tested with WordPress 6.1-alpha-53556 and WooCommerce 6.7.0-beta.2

= 1.3.0 - 2022-01-12 =
* Improvments on the way we get the order object on our metabox, when other plugins might have messed up the `$post` object
* Better error handling when parsing the information from the CTT website
* Fixed PHP notice by replacing `is_ajax` with `wp_doing_ajax`
* Tested with WordPress 5.9-RC1-52550 and WooCommerce 6.1.0

= 1.2.1 - 2021-03-10 =
* Avoid error when the object is found but no tracking details
* Tested with WordPress 5.8-alpha-50516 and WooCommerce 5.1.0

= 1.2.0 =
* New option to set the email link position on emails
* Fix a PHP notice
* Tested with 5.6-beta3-49562 and WooCommerce 4.8.0-beta.1

= 1.1.2 =
* New [Portuguese Postcodes for WooCommerce](https://www.webdados.pt/wordpress/plugins/codigos-postais-portugueses-para-woocommerce/) plugin information
* Tested with 5.5-alpha-47761 and WooCommerce 4.1.0-rc.2

= 1.1.1 =
* Tested with WordPress 5.2.5-alpha and WooCommerce 3.8.0

= 1.1 =
* New option to change the type of link shown on the client emails
* New [`portugal_ctt_tracking_email_info` filter](https://gist.github.com/webdados/4c3753fac653dcb0d8353483d6a7730e) so that the email information can be modified by developers
* Fix php notice

= 1.0.1 =
* Remove useless settings link
* Minor text corrections

= 1.0 =
* Initial release
