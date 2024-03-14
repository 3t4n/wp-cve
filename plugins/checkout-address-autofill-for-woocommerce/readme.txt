=== Checkout Address AutoFill For WooCommerce ===
Contributors: zetamatic, pitabas106
Tags: checkout address autocomplete, google address autofill, google address autocomplete, google address validation, woocommerce address autocomplete,
Requires at least: 4.0
Requires PHP: 5.4
Tested up to: 5.8.3
Stable tag: 1.1.8
WC tested up to: 6.1.1
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

== Description ==

Checkout Address AutoFill For WooCommerce is a WooCommerce add-on which allows your user to autofill both Billing and Shipping address fields in the checkout page by using google address autocomplete API.

This plugin also has the functionality where the user can select his current location and the plugin will set the current location in the respective checkout fields.

Its simplicity in design and function, number of unique features, and versatility are the reasons why an increasing number of people are turning to it.

Disclaimer: The Checkout Address Autofill for WooCommerce / Pro plugins only works with Google Maps API as of now. Please check that Google Maps is available and works well for the region of end-users to provide the best experience.

= Check out our Video demo =
[youtube https://www.youtube.com/watch?v=rh5NeV9JZ4E]

= Check out Plugin Settings Video =
[youtube https://www.youtube.com/watch?v=3XgFUAwVFxw]


== Features ==

* Simple to use with a clear User Interface
* Works for Billing and/or Shipping addresses
* Enable use current location for both Ship/Bill address
* Show Results from Specific Country
* Manage Autocomplete Field placement
* Enables autofill Phone Number
* Has attributes which allow Company Name autofill
* Supports the latest version of WooCommerce
* Translation Ready


= Pro Features =

* All features of the free version
* Option to validate Google API Key
* Ensures Multilingual Translation and support
* Save Geolocation (Latitude and Longitude) of the Billing and Shipping addresses. You can see the exact location of the billing and shipping addresses on the Edit Order page. Thereby, this allows specific shipping/billing address
* Fill in the addresses for the Billing and Shipping fields using the Location Picker feature.
* Adjust the Location Picker Zoom – The Admin or owner can adjust and have control over the location picker zoom size from the plugin’s Settings page.
* The Google Autofill Field Mapping feature allows you to combine multiple fields from your billing and shipping addresses into a single field of the respective billing and shipping addresses.
* Now get Compound Code and Global Code in the Checkout Page Location Picker for any location. It has powerful shipping methods. Useful when you are using WooCommerce Distance Rate Shipping. Easily calculate shipping costs based on the actual distance of your customers accurately.
* Option to adjust the Autocomplete Billing Field, Autocomplete Shipping Field, Current Location, Location Picker positions on checkout page
Supports WooCommerce Checkout Block
* Set the existing Billing or Shipping field as Google autocomplete field
Premium Customer Support


= Pro Version =

The Pro version of this plugin has some really cool features.
[Upgrade to Pro](https://zetamatic.com/downloads/checkout-address-autofill-for-woocommerce-pro/) version for better support and unrestricted access to Checkout Address AutoFill For WooCommerce Pro.

== Installation ==
1. Login to your WordPress dashboard and navigate to Plugins > Add New
2. Search for "Checkout Address AutoFill For WooCommerce".
3. Click install.
4. Click activate.
5. Once the plugin is installed then you need to add your google api key from its setting panel.
6. Change the settings as per your requirement and you are done.


== Screenshots ==
1. Plugin Upload.
2. Plugin Settings Link.
3. Plugin Settings.
4. Plugin Frontend - AutoFill for Billing Address.
5. Plugin Frontend - AutoFill for Shipping Address.

== Frequently Asked Questions ==

= Do I need Google API Key to use this plugin? =
Yes, you will require a Google API Key; additionally, for the API key to function, you must have a billing enabled Google Developers Console account. The plugin is free to download

= How to get my Google API Key? =
Please check the steps in our [documentation](https://zetamatic.com/docs/checkout-address-autofill-for-woocommerce/setup/google-api-key/) and you will learn how to get your Google API key.

=  How can I verify my Google API Key? =
To verify your API Key, enter the API Key on it’s field and then click on Save Changes. Then under the Verify Google API Key section, you will find the Verify Autocomplete Field, here enter any address and if your API key is correct, then you will see the address suggestions under the Autocomplete Field. Also, the address will be displayed on popup.

You can also use the Verify Current Location icon to check if your API key is correct or not. If you see a popup with your current location then your Google API key is working fine.

= Can I use the Location Picker feature on my checkout page? =
No, It’s a Pro feature, You will need to purchase the [Checkout Address Autofill for WooCommerce Pro](https://zetamatic.com/downloads/checkout-address-autofill-for-woocommerce-pro/) plugin to use that feature.

= Shipping Autocomplete Fields not working? =
Please go to the Shipping Autocomplete Fields section and check the Enable for Shipping option if it is not already checked.

= Autocomplete not working? =
One of the following could fix this:
* You need to add your domain to Google map API Referrer list
* Google Maps APIs should be enabled
* Google APIs Billing should be enable

= How do I restrict the address results to some countries only? =
Choose the countries in Show Results from Country field in Common fields for both Billing and Shipping Address section. And the it will show the addresses from the selected countries only.

= Can I only display the Search Address Autocomplete field on the Billing Field? =
Yes, you can display the Search Address Autocomplete field on the Billing Field, the Shipping Field, or both fields simultaneously. This plugin's settings make it simple to do so.

= Does it support RTL for handling multiple languages? =
Yes, it does. You can leverage another plugin like https://wordpress.org/plugins/loco-translate/ to achieve RTL support.

= I really love your plugin, and I want to support it! =
You can help us by providing your valuable feedback! Please rate it and review the plugin.


== ChangeLog ==

= Version 0.1 =
* Initial public release.

= Version 0.2 =
* Street address autocomplete added.
* Fixed issue for state autofill.

= Version 0.3 =
* Admin option to change position of Autocomplete field.
* Admin option to change the label of the Autocomplete field.

= Version 0.4 =
* css, js optimized
* Added option to select country from which the search result will be shown

= Version 0.5 =
* Fixed issue for select2 on admin settings

= Version 0.6 =
* Fixed issue for select2 on frontend

= Version 0.7 =
* Add Autofill option for Ship to different address
* Added autofill option for Phone and Company name field

= Version 0.8 =
* Fixed compatible issue with YITH WooCommerce plugin. Ref: https://wordpress.org/support/topic/site-not-working-when-activating-plugin/#post-11978932

= Version 0.9 =
* Fixed Multisite installation issue

= Version 1.0.0 =
* Updated Address field values

= Version 1.0.1 =
* Minor Bug Fixes

= Version 1.0.2 =
* Minor Bug Fixes

= Version 1.0.3 =
* Option to disable auto clearing default address values

= Version 1.0.4 =
* Fixed the street address field issue

= Version 1.0.5 =
* Minor bug Fixes
* Added Google api key testing in admin panel

= Version 1.0.6 =
* Minor bug Fixes

= Version 1.0.7 =
* Minor bug Fixes
* Added language support for autofill

= Version 1.0.8 =
* Fixed the issue related to autofill language

= Version 1.0.9 =
* Tested with Wordpress 5.7

= Version 1.1.0 =
* Minor bug Fixes

= Version 1.1.1 =
* Minor bug Fixes

= Version 1.1.2 =
* Bug Fixes

= Version 1.1.3 =
* Added help section

= Version 1.1.4 =
* Minor bug Fixes
* Tested with Wordpress 5.7.2
* Tested with wooCommerce 5.3.0

= Version 1.1.5 =
* Minor bug Fixes

= Version 1.1.6 =
* Tested with Wordpress 5.8

= Version 1.1.7 =
* Tested with Wordpress 5.8.1
* Tested with WooCommerce 5.7.1

= Version 1.1.8 =
* Tested with Wordpress 5.8.3
* Tested with WooCommerce 6.1.1
* Added Installation Error Admin Notice