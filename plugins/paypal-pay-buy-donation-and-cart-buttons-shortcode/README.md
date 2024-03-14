=== PayPal Pay Now, Buy Now, Donation and Cart Buttons Shortcode ===
Contributors: mohsinrasool
Donate link: http://mohsinrasool.wordpress.com/2013/01/11/wordpress-shortcode-for-paypal-pay-buy-donation-and-cart-buttons/
Tags: paypal, pay now, buy now, add to cart, shopping cart, donation, donate, pay button, cart button, paypal payment, paypal checkout, donate now
Requires at least: 3.0.1
Tested up to: 6.3.1
Stable tag: 1.7
Author: Mohsin Rasool
License: GPLv2

Adds a shortcode to display PayPal's pay now, buy now, donation and add to cart buttons with facility to customize your PayPal checkout page.

== Description ==

This plugin adds "paypal_button" shortcode to display pay now, buy now, donation and add to cart PayPal buttons with facility to customize their PayPal checkout page. Handy features of the plugins are

- **Four types of button** Pay Now, Buy Now, Add to Cart, Donation
- **Customizable quantity field** which is available as text box and drop down box with possible selected values or range of values for all types of buttons
- **Customizable PayPal Payment Button**
- **Customizable PayPal Checkout page**, change header logo and page color etc
- **Output can be a PayPal form** with Default or Customizable PayPal button **or a URL to PayPal payment/cart page** that can be used on linked texts
- **All type of PayPal button options** i.e. large, small, with and without Credit Card Logos 
- **Set Thank you page** after successful payment to return the user back to your website
- **Set Handling and Shipping charges**
- **Allow buyer to add a note to order**
- **Select language of your button.** Supports 17 widely used languages
- **Both Admin generated amount (based on quantity) and user filled amount payments**

Plugin also adds a [paypal_view_cart_link] shortcode to be used to create view cart buttons.

Possible usage of the plugin are as follows

    Pay Now Button: [paypal_button type="paynow" amount="100"]
    Buy Now Button: [paypal_button type="buynow" name="WordPres Portfolio Theme" amount="100"]
    Donation Button: [paypal_button type="donate" amount="100"] 
    Add to cart Button: [paypal_button type="cart" name="Computer Table" amount="100"]
    Payment Link: [paypal_button type="buynow" name="WordPres Portfolio Theme" amount="100" echo_link=1]

    View Cart Link: <a href="[paypal_view_cart_link]">View Cart</a>

Set your PayPal E-Mail address and Checkout page customization in Settings->PayPal Buttons.

= Usage =

    [paypal_button type="paynow|buynow|donate|cart" amount="100"]
    <a href="[paypal_view_cart_link]">View Cart</a>

= Attributes =

    **type**: 
    (string) (optional) Type of transaction. Allowed Values are 
    Possible Values: 'paynow', 'buynow', 'cart' or 'donate'

    **id**: 
    (string) (optional) Product Number
    Possible Values: Any numeric product id

    **email**: 
    (string) (optional) Your PayPal E-Mail address. 
    Possible Values: A valid PayPal E-Mail address

    **name**: 
    (string) (optional) Name of the Product
    Possible Values: Any String

    **amount**: 
    (numeric) (optional) Product price to be charged. Yes, you can left empty for user to input amount. This can be used for donations.
    Possible Values: Any numeric value

    **quantity**: 
    (numeric or string) (optional) Specfiy quantity as number or range or possible comma separated values. Leave empty to let user specify any quantity.
    Possible Values: "1" or "1,5,10" or "1-10"

    **quantity_txt_postfix**: 
    (string) (optional) Post fix text to be shown in quantity dropdown.
    Possible Values: " items" or " products"

    **field_sep**: 
    (string) (optional) HTML code to separate the generated visible HTML fields. Use "<br />" for new line.
    Possible Values: "&nbsp;" or "<br />"

    **amount_prompt**: 
    (boolean) (optional) Setting to "1" will enable user to enter amount they would like to pay.
    Possible Values: 1 or 0

    **echo_link**: 
    (boolean) (optional) Set to "1" for linked output
    Possible Values: 1 or 0

    **open_new_window**: 
    (boolean) (optional) Set to "1" to open payment page in a new window
    Possible Values: 1 or 0

    **currency**: 
    (string) (optional) Currency of the Transaction. 
    Possible Values: 'USD' or 'CAD' or any currency code

    **tax_rate**: 
    (numeric) (optional) Tax rate in percentage applied to the total price.
    Possible Values: 0.0001 to 100

    **shipping_charges**: 
    (numeric) (optional) Shipping charges for the product. 
    Possible Values: Any numeric value

    **btn_url**: 
    (string) (optional) Set URL of your own customizable button. Global setting is used when left empty or default PayPal Button
    Possible Values: A URL to an image

    **btn_size**: 
    (string) (optional) Set size of the button either 'large' or 'small'.
    Possible Values: 'large' or 'small'

    **btn_display_cc**: 
    (string) (optional) Display Credit Cards Logo under the button.
    Possible Values: 'yes' or 'no'

    **add_note**: 
    (string) (optional) Let buyer add a note to order.
    Possible Values: 'yes' or 'no'

    **thankyou_page_url**: 
    (string) (optional) Buyer will be redirect to this page after successful payment.
    Possible Values: An absolute URL e.g. http://abc.com/thankyou

    **checkout_logo_url**: 
    (string) (optional) URL to your Logo image.
    Possible Values: An absolute URL e.g. http://abc.com/logo.png

    **checkout_header_border_color**: 
    (string) (optional) Set border color of the checkout page header.
    Possible Values: A HTML Hexa-decimal code. e.g. FFFF00, 999999 etc

    **checkout_header_bg_color**: 
    (string) (optional) Change background color of the checkout page header.
    Possible Values: A HTML Hexa-decimal code. e.g. FFFF00, 999999 etc

    **checkout_bg_color**: 
    (string) (optional) Change background color of the entire checkout page.
    Possible Values: A HTML Hexa-decimal code. e.g. FFFF00, 999999 etc

== Installation ==

This section describes how to install the plugin and get it working.

1. Upload `paypal-pay-buy-cart-buttons` folder to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. You can now use "[paypal_button]" shortcode.
4. Visit Settings page to set the default values and merchant information.

== Frequently Asked Questions ==

= Do I need a PayPal Account? =

Yes, You do need a PayPal account to receive payments. Please visit http://paypal.com to sign up.

= Does it support all PayPal buttons =

It supports Pay Now, Buy Now, Donate and Add to Cart Buttons.

= How can I put my logo on checkout page using this shortcode? =

You can use Logo URL field in the admin settings to place your logo on checkout page. Please make sure it is not bigger than 750x90;

== Screenshots ==

1. Different flavors of the shortcode
2. Settings page in Admin -> Settings -> PayPal Buttons
3. Sample PayPal checkout page with logo replaced

== Changelog ==

= 1.7 =
* Backword compatiblity 
* Latest WordPres Tested

= 1.6 =
* Backword compatiblity 
* Latest WordPres Tested

= 1.5 =
* Added filters
* Added feature to open payment page in a new window
* Added feature to allow for user entered payments.

= 1.4 =
* Added support for 17 languages for PayPal buttons.
* Added CSS classes for customization.

= 1.3 =
* Customize the Payment Button. Set your button's URL either in settings page or in shortcode
* Fix Javascript error of quantity when used with buy now and cart buttons.

= 1.2 =
* Added quantity support for drop down box.
* Added support for payment using URL. You can now use it in achor tag's "href" attribute also.

= 1.1 =
* Customizable Quantity attribute
* Quantity Text Postfix
* Added Field Seperator

= 1.0 =
* First Revision

== Upgrade Notice ==

= 1.7 =
* Backword compatiblity 
* Latest WordPres Tested

= 1.6 =
* Backword compatiblity 
* Latest WordPres Tested

= 1.5 =
* Added filters
* Added feature to open payment page in a new window
* Added feature to allow for user entered payments.

= 1.4 =
* Added support for multilingual buttons.

= 1.3 =
* Added support for customizing the PayPal button.

= 1.2 =
* Fixed quantity feature for donation 
* Added support for payment using URL. You can now use it in achor tag's "href" attribute also.


= 1.1 =
* Added Quantity drop down or text box feature to be selected by the user.
* Fix a Javascript error related to quantity
