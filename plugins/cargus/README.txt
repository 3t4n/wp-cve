=== Cargus ===
Contributors: cargus123
Tags: e-commerce, store, shop, woocommerce, cart, checkout, shipping, products, payments, ship&go, devlivery, cargus
Requires at least: 5.0
Tested up to: 6.4.1
Stable tag: 1.4.2
Requires PHP: 7.4
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Use Cargus delivery methods to ship and deliver your orders.W

== Description ==

Cargo delivery. Enables the use of Cargus as a shipping method, management and creation of awbs for orders delivered with Cargus.

Important!! The Cargus plugin comes as a rebranding for the existing UrgentCargus plugin. Before installing the Cargus plugin, if the UrgentCargus plugin is present on your site, it must be uninstalled first and only after that you can install, activate and configure the Cargus shipping plugin!

Before you are able to use the Cargus plugin you must do the folowing:
- Access [urgentcargus.portal](https://urgentcargus.portal.azure-api.net/).
- Click the 'Sign up' button and fill in the form (you can not use the credentials that the client has for WebExpress).
- Confirm your registration by clicking on the link you received by mail (a real email address should be used).
- On the [urgentcargus.portal](https://urgentcargus.portal.azure-api.net/developer) page, click on `PRODUCTS` in the menu, then `UrgentOnlineAPI` and click 'Subscribe', then 'Confirm'.
- After the Cargus team confirms subscription to the API, the customer receives a confirmation email.
- On the [urgentcargus.portal](https://urgentcargus.portal.azure-api.net/developer) page, click on the user name at the top right, then click `Profile'.
- The two subscription keys are masked by the characters `xxx ... xxx` and 'Show` in the right of each for display.
- It is recommended to use `Primary key` in the Cargus module.

**You can find the [documentation here](http://woocommerce.demo.cargus.ro/wp-content/plugins/cargus/documentation/).** The documentation contains all the steps necessary in order to get the required api key, download, install and configure the Cargus plugin. In the documentation you will also find information on how to use and easily customize the plugin using the provided hooks.

== Screenshots ==

1. Cargus settings.
2. Cargus settings part 2.
3. Ship and go settings.
4. Ship and go Chechout.
5. Ship and go Widget Map.

== Changelog ==

= 1.0 =
* Initial release
* Added Cargus ship and go shipping method
* Added "Ramburs la ship and go" payment method to go with the Cargus ship and go shipping method
* Added the possibility to generate/print/delete awbs for orders in bulk as well as individually from wordpress admin
* Reformed the plugin to match the Wordpress coding standards.
= 1.0.1 =
* Fixed Valoare Ramburs Input type number step from .1 to .01
* Fixed WC->Cart value null issue
* Fixed Fatal error api unavailabe issue
* Updated pudo_locations.json file with the lates pudo locations
= 1.1.0 =
* Added buttons on the admin edit order pannel for Generating AWB, Printing AWB and Deleting AWB for easier access
* Added more detailed error notices for when and awb is not generated on bulk
* Added fields in the plugin woocommerce settings for changing the order status on Generate AWB and delete AWB
* Removed order action options from admin edit order pannel for Generating AWB, Printing AWB and Deleting AWB
* Fixed the arguments order for the metod call_method from Cargus_Api class
= 1.1.1 =
* Added admin option for VAT tax inclusion on calculates shipping cost
* Fixed console error on cart and checkout when using only Cargus standard delivery ad not Cargus ship and go delivery
* Fixed forced shipping cost recalculation when not needed
* Fixed remove "Generare AWB" button from admin single order pannel after generating awb
* Fixed order status option text
= 1.1.2 =
* Fixed awb bulk generation error
= 1.1.3 =
* Fixed Ship and go map not being displayed
= 1.2.0 =
* Changed the Ship and go map visual hook location from "woocommerce_cart_totals_before_order_total" to "woocommerce_before_cart" on cart
* Changed the Ship and go map visual hook location from "woocommerce_review_order_before_order_total" to 'woocommerce_checkout_before_customer_details" on checkout
* Added the Generate postal code funtionality. The postal code will pe automaticaly generated after filling in the address.
* Added new Street and Street Number fields to the address forms in the my account and checkout sections for billing and shipping.
* Added select 2 functionality to the City field.
* The address_field_1 addres field is now hidden and is being filled automatically after the street number is fileld in or on order checkout.
* The address_field_2 now has a label and is listed under the street number field.
* Added the cargus_after_print_awb hook that runs after generating the printable the awb.
* Fixed the shipping price recalculation.
* Included the full minified bootstrapt js instead of the custom part one.
= 1.2.1 =
* Fixed The Street and Street Number custom fields are only availabe now for the Romanian Country
= 1.2.3 =
* Fixed "Produse" text appearing twice on the printed awb
* Fixed Cart Shipping calculator error
* Added, The new Parcel for multiple parcels orders now appears on top instead of the bottom
* Added a "Actualizeaza" button for the order "Detalii AWB" fields
* Added a filter for the create AWB fields named "cargus_before_create_awb_fields" that has as parameters $fields, $order_id, $cargus
* Added a filter for the order "Detalii AWB" fields named "cargus_before_add_metabox" that has as parameters $args_form, $cargus_options
* Added a filter for the order "AWB actions" buttons named "cargus_before_add_buttons_metabox" that has as parameters $args_form, $cargus_options
* Added a filter for the Cargus Shipping method Settings Extra fields named "cargus_shipping_method_extra_fields" that has as parameters $extra_fields
= 1.2.4 =
* Fixed Zip code generation for street number 1
* Added field validation for the street number field, so it's not too long
* Added cargus_add_shipping_discount filter that runs before the shipping price is calculated, has one parameter type int
* Added cargus_shipping_method_title_free that runs before the free shipping method title is displayed, has one parameter, type string
= 1.3.0 =
* Fixed Ship and go selected location name not appearing in the order confirmation email.
* Fixed (Hopefully) the page loading time slowing issue
* Added the Cargus Saturday delivery shipping rate, with it's separate price field and title field. This shipping rate it's available only on fridays, with delivery on saturday.
* Added the Cargus pre10 delivery shipping rate, with it's separate price field and title field. This shipping rate it's available only from monday to thursday, with delivery on weekdays before 10:00.
* Added the Cargus pre12 delivery shipping rate, with it's separate price field and title field. This shipping rate it's available only from monday to thursday, with delivery on weekdays before 12:00.
* Added Shipment status on the my account order details page.
* Added qrcode for the return code on the my account order details page.
* Removed the "Livrare sambata" and "Livrare dimineata" fields from the Cargus settings.
* Removed the "Livrare sambata" field from the single order page options.
= 1.3.1 =
* Fix deploy issue
= 1.3.2 =
* Fix cargus modal button showing
= 1.3.3 =
* Remove morningDelivery awb request field
* Fix normalize function callback
= 1.4.0 =
* Fix calculating the shipping cost
* Make the street and city select2 fields width 100%
* Disable "Ship to different address" checkbox when selecting ship and go
* Change the map location to wp_footer on both cart and checkout
* Add the new map widget
* Fix not showing the "Alege punct" button for ship and go if the Shipping zone name contained diacritics
* Fix the Shipping cost for Bucharest being overridden
* Fix the checkout Js files not loading without $_SESSION already started
= 1.4.1 =
* Change the name of the file path responsible to the Ship&Go functionality from cargusWidget.js to carguswidget.js
= 1.4.2 =
* Changed the sipping carrier id from 'cargus_ship_and_go' to '63
* Filter the ship and go button html with wp_kses_post()
* Overwrite the get_admin_options_html method so that the <table> html tag cold be printed in the shipping method settings as well
* Add the #[AllowDynamicProperties] class tag to the cargus shipping classes
* Add tooltip description to cargus settings
== Installation ==

= Minimum Requirements =

* PHP 7.4 or greater is recommended
* MySQL 5.6 or greater is recommended
* WordPress 5.0 or greater is recommended
* Woocommerce 4.0 or greater is recommended
* max-execution-time=90

Visit the [Cargus documentation](http://woocommerce.demo.cargus.ro/wp-content/plugins/cargus/documentation/) for detailed installation and configuring instructions.

= Automatic installation =

Automatic installation is the easiest option -- WordPress will handle the file transfer, and you won’t need to leave your web browser. To do an automatic install of Cargus, log in to your WordPress dashboard, navigate to the Plugins menu, and click “Add New.”
 
In the search field type “Cargus,” then click “Search Plugins.” Once you’ve found it,  you can view details about it such as the point release, rating, and description. Most importantly of course, you can install it by! Click “Install Now,” and WordPress will take it from there.

= Manual installation =

Manual installation method requires downloading the Cargus plugin and uploading it to your web server wiht a FTP application in the /wp-content/plugins directory. Once you do that you can go to the Plugins section in adming and activate the Cargus plugin.
**Important !!** The Cargus plugin comes with a rebranding for the UrgentCargus plugin. If you have the UrgentCargus plugin installed on your website, you must deactivate it in order to install the Cargus plugin.

