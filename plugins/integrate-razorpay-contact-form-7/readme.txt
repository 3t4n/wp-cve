=== Integrate Razorpay for Contact Form 7 ===
Contributors: vinaysaini30, abhilashg90
Tags: razorpay, contact form 7, contact form, contact form razorpay, contact form 7 razorpay 
Requires at least: 5.6
Tested up to: 6.4.3
Stable tag: 1.0.9
Requires PHP: 7.2
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html
 
Seamlessly integrates Razorpay with Contact Form 7

== Description ==

This Razorpay Add-on is a WordPress plugin for Contact Form 7.  It seamlessly integrates the Razorpay Payment Gateway with the forms created through Contact Form 7 plugin. Developers can now add this for easy payments in their Contact Form 7 on WordPress platform for their website’s customers. 

Note: The sole dependency of this plugin is “Contact Form 7”. It is mandatory to install the same first and then continue with the Razorpay Addon.

If you want any customization or found any bugs you can contact us [here](https://www.codolin.com/contact?utm_source=wordpress&utm_medium=plugin&utm_campaign=integrate-razorpay-cf7&utm_id=integrate-razorpay-cf7).

= Features =

You will have the following features in the Razorpay panel (payments and settings) after successful installation- 

* Individual activation/deactivation of Razorpay in different forms
* Individual detail addition (item id, item price, name, etc.) in different forms
* Both “Test” or “Live” mode 
* API integration credentials inputs of ID and secret key present
* Company name can be added
* “Return URL” option for a landing page after payment
* All the payments listed under Razorpay Payment page on WordPress dashboard
* Payments can be filtered according to forms, status, dates, etc.
* View more information related to individual payments in a popup.
* A payment can be searched using internally generated Order ID. 
* “Error” upon entering wrong credentials 

= Key Features in Premium Edition =

* **Variable Pricing:** Allows Razorpay Payment Gateway to pick variable pricing options field set in form by overriding the base item price. [Useful in allowing enduser to choose the pre-defined(By Admin) price options in the form.]
* **EndUser Pricing:** Allows Razorpay Payment Gateway to pick enduser pricing field set in form by overriding the base item price. [Useful in allowing enduser to enter their own price in the form.] 
* **FormData Collection:** Allows collecting user submitted form data wrt payment. [Useful in knowing the form details submitted by user during Razorpay Payment.]
* **Order Shortcode:** Enables the display of order-related details such as OrderId, ItemId, ItemName, and ItemPrice on the ThankYou Page using a shortcode.
* **Export CSV:** Allows "export/download" of "Order/Payment" data and its associated "User submitted form" data as CSV file based on contact form selected.
* **Custom OrderId Prefix:** Set your preferred product/item/contactform specific prefix for OrderId by overriding the default(cf7rzp_).
* **Order Success Redirect:** Set product/item/contactform specific order success redirect url by overriding the default. It can be either Internal Thank You page url or External url.

**[Get Premium](https://cf7rzppa.codolin.com/?utm_source=plugin_user&utm_medium=plugin&utm_campaign=upsell)** with various features & support.

== Installation ==

* Upload the plugin zip file through the WordPress admin panel “Upload Plugin” button.
* Activate the plugin from the plugin list page.
* You’ll notice a “Razorpay” column on the form editing panel. Also, a “Razorpay Settings” and “Razorpay Payments” panels will show on your left WordPress Dashboard under the Contact Form 7 Plugin.
* Choose the mode (sandbox or live).
* If you already have a Razorpay account, provide necessary Razorpay API credentials (key ID and key secret) received from your Razorpay Dashboard to make use of Razorpay API services. You can also add the company name and return URL. 
* Upon integration, you are ready to use the gateway for different forms.
* Enable the Razorpay payment checkbox and provide necessary product/item details per form that has been created through Contact Form 7.  

== Frequently Asked Questions ==

== Screenshots ==

1. Razorpay Settings per Contact Form 7
2. Razorpay General Settings
3. Razorpay Payments list page
4. Individual Razorpay Payment detail view
5. Find Payment based on internal order number
6. Filter Payments based on Contact Form 7
7. Filter Payments based on status(success | failure | pending)

== Changelog ==

= 1.0.9 - 2024-03-08 =
* Added: New sections. Compatible with V-p105.

= 1.0.8 - 2024-03-06 =
* Added: New sections. Compatible with V-p104.

= 1.0.7 - 2024-03-04 =
* Added: New sections. Compatible with V-p103.

= 1.0.6 - 2024-02-21 =
* Added: New sections. Compatible with V-p102.

= 1.0.5 - 2024-02-13 =
* Added: New sections. Compatible with V-p101.
* Tested: Compatible with wordpress version(6.4.3).

= 1.0.4 - 2024-02-02 =
* Added: New sections.

= 1.0.3 - 2024-01-29 =
* Added: New sections. Compatible with V-p100.
* Tested: Compatible with wordpress version(6.4.2). 

= 1.0.2 - 2023-10-23 =
* Added: Compatible with wordpress version(6.3.2).

= 1.0.1 - 2022-02-04 =
* Fixed: Razorpay payment poup not triggering.
* Changed: Razorpay default mode set to Test.

= 1.0 - 2022-02-02 =
* Added: First commit.