=== Decimal Product Quantity for WooCommerce ===
Contributors: WPGear
Donate link: wpgear.xyz/decimal-product-quantity-woo
Tags: woocommerce,decimal,quantity,step,min,max,piece,product,minimum,maximum,variation
Requires at least: 5.0
Tested up to: 6.4.3
Requires PHP: 5.4
License: GPLv2
License URI: http://www.gnu.org/licenses/gpl-2.0.html
Stable tag: 10.35

Products like: Pizza, Liquid on the filling, Custom weight, etc..
(Piece of Product). Min, Max, Step & Default preset Quantity. Variable Products Supported.

== Description ==
The plugin makes it possible to sell Products as whole or in parts: 0.5 1.5 etc.
(Piece of Product). Min, Max, Step & Default preset Quantity. Variable Products Supported.

For example:
	Pizza. You can sell 1.5 Pizzas, or a quarter. With the price set for 1 piece.
	Liquids on tap. For example, Kerosene or Olive Oil. You can sell 1.5 liters, with the price set for 1 liter.
	Bulk materials. For example, Tobacco or Golden Sand. You can sell 0.1 g at a price quoted for 1 g.

= Futured =
* You can set the Minimum product Quantity for all Products by default (preset = 1). But at the same time, each Product / Categories can have its own Minimum Quantity value.
* You can set the Step of Changing the Quantity of goods for all Products by default (preset = 1). But at the same time, each Product / Categories can have its own value for the Change in Quantity Step.
* You can set the Default - Choice product quantity for all Products by default (preset = 1). But at the same time, each Product / Categories can have its own Default - Choice Quantity value. 
* You can set the Maximum product Quantity for all Products by default. But at the same time, each Product / Categories can have its own Maximum Quantity value.
* Works correctly with Variable Products.
* Auto correction "No valid value" customer enters to nearest valid value.
* Auto correction considering with "Maximum allowed for Product".
* Column "Quantity" on Products List.
* Update Cart Automatically on Quantity Change (AJAX Cart Update)
* You can set a "Value Label" for each individual Item. Individually or as a whole for the Category. For example: "Price per Meter", "Price per Liter".
* It is possible to use Product - JS Object for Ext.Integration: QNT_Data. Function: DPQW_Get_QuantityData (Product_ID).
* Works with WooCommerce specifics from v3.4.8 & more

	<a href="http://wpgear.xyz/decimal-product-quantity-woo-pro/">PRO Version</a> Features:
* You can set separate Minimum Product Quantity, Step of Changing the Quantity & Default preset Quantity - for each Variable Product Variation.

== Installation ==

1. Upload 'decimal-product-quantity-for-woocommerce' folder to the `/wp-content/plugins/` directory.
2. Activate the plugin through the 'Plugins' menu in WordPress.
3. Go to Settings: Products -> Decimal Quantity. Set: Min cart Quantity, Max cart Quantity, Step change Quantity, Default preset Quantity (default preset = 1).
4. If you have any problems - please ask for support. 

== Frequently Asked Questions ==
N/A

== Screenshots ==
 
1. screenshot-1.png Admin page | WooCommerce Products -> Decimal Quantity. Here are the all Options: "Defaults for all Products" and Setup.
2. screenshot-2.png Admin page | WooCommerce Products -> Decimal Quantity. Validation Settings.
3. screenshot-3.png Admin page | WooCommerce Products -> All Products. Column "Quantity".
4. screenshot-4.png Admin page | Product -> General. Here are the Fields: "Minimum cart Quantity", "Step change Quantity", "Default preset Quantity", "Maximum cart Quantity" which override the "default" values. And "Price Unit-Label".
5. screenshot-5.png Front page | Product page. The Quantity field has a Decimal value and "Price Unit-Label".
6. screenshot-6.png Front page | Cart page. The Quantity field has a Decimal value. Subtotals / Totals are recalculated.
7. screenshot-7.png Admin page | WooCommerce Products -> Categories. "Price Unit-Label" and "Quantity options" for all Products in this Category.

== Changelog ==	
= 10.35 =
	2024.02.26
	* Fix Fatal Error woocommerce_quantity_input_args. (Thanks to shawfactor)
	
= 10.34 =
	2024.02.23
	* Fix Cart Processing for Variation Products. (Thanks to Kylie)
	* Tested to WooCommerce: 8.6.1
	
= 10.33 =
	2024.02.16
	* Fix Fatal Error in the admin order screen if the product does not exist anymore. (Thanks to nkals722)
	
= 10.32 =
	2024.01.31
	* Fix red-view "Pice Unit-Label" about in Product Setup page.
	* Tested to WP: 6.4.3
	* Tested to WooCommerce: 8.5.2
	
= 10.31 =
	2023.08.29
	* Fix Step Quantity for Parent Product on Woo Order Admin-Page.
	
= 10.30 =
	2023.08.27 (for NetMorais special)
	* Added the ability to use Product - JS Object for Ext.Integration: DPQW_Get_QuantityData (Product_ID)
	
= 9.29 =
	2023.07.31
	* Fix Step Quantity for Product List & Product Setup.
	
= 9.28 =
	2023.07.20
	* Fix PHP Warning: Undefined array key "min_qnt", "max_qnt", "stp_qnt".
	
= 9.27 =
	2023.07.19
	* Fix Placeholder value in Product Categories setup Page.
	
= 9.26 =
	2023.07.18
	* Add Options "Min, Max, Step" Quantity for Product Categories.
	* Fix Check Quantity Options.
	* Fix Uninstall.
	* Tested to WooCommerce: 7.9.0	
	
= 8.25 =
	2023.05.24
	* Fix Fatal Error (Filter 'woocommerce_loop_add_to_cart_link') for "Pice Unit-Label". In some cases, some users.
	* Tested to WooCommerce: 7.7.0
	
= 8.24 =
	2023.05.23
	* Add Option "Cost Marker" (Pice Unit-Label).
	* Tested to WP: 6.2.2
	* Update Screenshots.
	
= 7.23 =
	2023.05.08
	* Fix Options Page. $Errors_Msg.
	* Tested to WooCommerce: 7.6.1
	
= 7.22 =
	2023.04.12
	* Add Option "Update Cart Automatically on Quantity Change (AJAX Cart Update)": On/Off (Defaul = Off).
	* Update Screenshots.
	
= 6.21 =
	2023.04.06
	* Global Settings "Quantity" moved from: DashBoard -> WooCommerce -> Setup Page -> Products -> Inventory to: DashBoard -> Products -> Decimal Quantity.
	* The "Quantity" settings for each Product have been moved from the Tab: Product -> Inventory to Tab: Product -> General.
	* Add Column "Quantity" on Products List.
	* Validation Options Settings.
	* Add Option "Browser Console-Log Debuging": On/Off (Defaul = Off).
	* Add Option "Delete Quantity-MetaData with Uninstall Plugin": On/Off (Defaul = Off).
	* Update Screenshots.
	
= 5.20 =
	2023.04.01
	* Add Checking: Default Quantity < Min Quantity
	* Fix Check Max Quantity
	
= 5.19 =
	2023.03.31
	* Tested to WP: 6.2
	* Fix show test-debug msg.
	
= 5.18 =
	2023.03.30 (Thanks Ady DeeJay for Testing)
	* Tested to WooCommerce: 7.5.0
	* Fix Auto correction.
	* Fix Incr/Decr for some Browsers.
	* Fix Variation Product processing.
	* Warning! Possible incompatibility with the active functions of "Ajax Update Cart".
	
= 5.17 =
	2023.03.22
	* Add Option: "Auto correction Quantity": On/Off  (Defaul = On).
	* Auto correction considering with "Maximum allowed for Product".
	
= 4.16 =
	2023.03.21
	* Fix Auto correction
	
= 4.15 =
	2023.03.21
	* Auto correction "No valid value" customer enters to nearest valid value. (Request: Ady DeeJay)
	
= 3.14 =
	2023.02.22
	* Tested to WP: 6.1.1
	* Tested to WooCommerce: 7.4.0
	* Fix "Undefined array key "input_name" / "input_value" - as a possible error with some configuration of other Plugins and Themes.
	
= 3.13 =
	2022.11.09
	* Add Option: "Max Quantity" for Product. (Set Max Quantity - Default for All Products / Set Max Quantity for Any-One Product)
	* Fix "add to cart message" when Quantity is < 1
	* Tested to WP: 6.1
	* Tested to WooCommerce: 7.0.1
	
= 2.12 =
	2022.10.27
	* Change the internal structure.
	* Fix Default Quantity in Cart.
	* Tested to WP: 6.0.3
	* Tested to WooCommerce: 7.0.0
	
= 2.11 =
	2022.10.22
	* Fix Save "Min/Step Quantity" for Edit Order (Edit/Refund).
	
= 2.10 =
	2022.09.23
	* Automatic conversion of previous set values for Products from the previous version.
	
= 2.9 =
	2022.09.20
	* Fix Save "Min/Step Quantity" for Product.
	* Tested to WP: 6.0.2
	* Tested to WooCommerce: 6.8.2
	
= 2.8 =
	2022.05.25
	* Fix Quantity in Cart.
	
= 2.7 =
	2022.05.20
	* Fix Save "Step_Quantity_Default" for Product.
	* Add Option: "Default preset Quantity" for Product. (Set Default for All Products / Set Default for Any-One Product)
	* Tested to WP: 5.9.3
	* Tested to WooCommerce: 6.2.0

= 1.4 =
	2021.08.13
	* Fix Uninstall.
	* Fix view Placeholders Step/Min on Product Page.
	* Fix Decimal Quantity in Message about adding product to Cart.
	* Tested to WP: 5.8
	* Tested to WooCommerce: 5.5.2
	
= 1.3 =
	2021.08.09
	* Fix Quantity in Cart.
	
= 1.2 =
	2021.07.22
	* Fix Min. Quantity for Variation.
	
= 1.1 =
	2021.04.10
	* Initial release
	
	
== Upgrade Notice ==
