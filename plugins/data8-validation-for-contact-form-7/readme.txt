=== Data8 Validation ===
Contributors: data8
Tags: woocommerce, gravity, gravity forms, gravityforms, elementor, elementor pro, contact form 7, contactform7, cf7, data validation, address lookup, predictive address, geolocation lookup, email, phone verification, address, addresses, address autocomplete, address autofill, cell, checkout, data, form, international, mobile, paf, phone, usps, royal mail, validation, verification
Requires at least: 4.5
Tested up to: 6.1
Stable tag: trunk
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Applies Data8 Email, Unusable Name, Phone Validation and PredictiveAddress services to WooCommerce checkout, Gravity Forms and Contact Form 7, WPForms & Elementor Pro forms.

== Description ==

Verify international postal addresses, email addresses, names, bank details and telephone numbers as they are entered at the point of capture. This plugin works perfectly with Wordpress, WooCommerce, Gravity Forms, WPForms, Elementor Pro and Contact Form 7 forms.

International coverage for all services, the most advanced fuzzy matching and backed by official data from sources such as USPS and Royal Mail ensures you can rely on the data being entered.

Improve your user experience with fast, natural address entry.

Ensure you capture accurate contact details and eliminate spam contacts by verifying email addresses, names and telephone numbers in real-time.

Enter a what3words 3 word address (word.word.word) and the PredictiveAddress™ service will provide a picklist of suggested addresses within close proximity of that what3words location to enable the user to select their postal address.

Quick to set up with a <a href="https://www.data-8.co.uk/register/" target="_blank">free trial</a>. This extension is free of charge, just pay for the credits you use for our validation services.

For more information on the services we offer, or to manage the credits on your Data8 account, <a href="https://www.data-8.co.uk/" target="_blank">visit our website</a>.

= WooCommerce =

The Data8 PredictiveAddress service is automatically applied to all address entry forms in WooCommerce, including billing and shipping addresses on checkout and in the "My Account" section.
Data8 Validation will be applied to the name, email and telephone fields during the checkout process. (Bank Validation not currently supported).

= Gravity Forms =

The Data8 PredictiveAddress service is automatically applied to all Address fields
The Data8 Email Validation service is automatically applied to all Email fields
The Data8 Phone Validation service is automatically applied to all Phone fields
The Data8 Unusable Name service is automatically applied to all Name fields
The Data8 Bank Validation service is automatically applied to fields tagged with correct CSS classes

= Contact Form 7 =

The Data8 PredictiveAddress service is automatically applied to all correctly tagged Address fields
The Data8 Email Validation service is automatically applied to all correctly tagged Email fields
The Data8 Phone Validation service is automatically applied to all correctly tagged Phone fields
The Data8 Unusable Name service is automatically applied to all correctly tagged Name fields
The Data8 Bank Validation service is automatically applied to all correctly tagged bank fields

== WPForms ==

The Data8 PredictiveAddress service is automatically applied to all Address fields
The Data8 Email Validation service is automatically applied to all Email fields
The Data8 Phone Validation service is automatically applied to all Phone fields
The Data8 Unusable Name service is automatically applied to all Name fields
The Data8 Bank Validation service is automatically applied to all fields tagged with correct CSS classes

== Elementor Pro ==

The Data8 PredictiveAddress service is automatically applied to all Address fields with correct IDs.
The Data8 Email Validation service is automatically applied to all Email fields with correct IDs.
The Data8 Phone Validation service is automatically applied to all form fields of type 'tel'.
The Data8 Unusable Name service is automatically applied to all Name fields with correct IDs.
The Data8 Bank Validation service is automatically applied to all bank account and sort code fields with correct ID.

== Installation ==

1. Upload the plugin files to the `/wp-content/plugins/data8-validation-for-contact-form-7` directory
1. Activate the plugin through the 'Plugins' screen in Wordpress
1. Use the Settings link on the 'Plugins' screen to configure the plugin
1. Enter your Data8 client and server Api keys and select the validation options you wish to enable.
1. If enabled, Email, Phone, Bank and Unusable Name validation will be applied to all relevant fields
1. If enabled, PredictiveAddress will attach to any forms with address fields set up as described on the settings screen.
 
== Frequently Asked Questions ==
 
= How do I change the email validation level? =

On the plugin configuration page, enable the Email validation option by selecting a level at which to validate email fields. Fields of type 'email' in Gravity Forms and Contact Form 7 will be automatically validated. To gain further control over individual elements, the level of validation can be overridden as follows:

In Contact Form 7, the email validation level can be changed using the level option, e.g.
[email* your-email level:Address]

In Gravity Forms, the email validation level can be changed by adding a "d8level_Address" CSS class to the Custom CSS Class setting.
 
The following are valid email validation levels:

* Syntax - validates the syntax of the email address (lowest)
* MX - validates the domain name (right hand part) the email address (default)
* Server - validates the mail servers for the domain are alive
* Address - validates the full email address (highest)

= Will Data8's phone validation service validate mobile and landline telephone numbers? =

Yes! Simply enable the Phone validation option on the plugin configuration page and the appropriate validation will be applied to any 'tel' fields. The service includes both UK landline and mobile validation. You will need credits for the Data8 Phone Validation service to use this option.

= How do I enter international telephone numbers? =

The Phone Validation service will validate the entered number using the rules appropriate for the country indicated in the telephone number using standard international dialling rules. For example, if a number is entered with a "+1" or "001" prefix, it will be validated as a US number, or if "+44" or "0044" is used it will be validated as a UK number.

If no international prefix is specified, it will be validated according to the rules for a default country. The default is United Kingdom (GB), however this can be changed globally using the Default Country Code option under the Phone Validation section, or set individually on each field as follows:

In Gravity Forms, add the "d8country_XX" CSS class to the Custom CSS Class setting, e.g. d8country_US

In Contact Form 7, use the "country" tag, e.g.:
[tel* your-tel country:US]

== Changelog ==

= 1.0 =
* Initial release!

= 1.1 =
* Fixed "Fatal error: Cannot use object of type stdClass as array" errors

= 1.2 =
* Fixed use of telephone validation without email validation

= 1.3 =
* Fixed syntax error on PHP 5.3

= 1.4 =
* Added support for WooCommerce and Gravity Forms

= 1.5 =
* Added support for SalaciousName to Gravity Forms & Contact Forms 7
* Moved the setting page to it's own page

= 1.6 =
* Added AllowedPrefixes & BarredPrefixes parameters to Tel Val

= 2.0 =
* Updated configuration page to a more user-friendly interface
* Changed configuration to give the user more control over which validation services to use
* Added validation on name fields using Data8 Unusable Name validation

= 2.1 =
* Fixed application of advanced telephone validation options & defaults

= 2.2 =
* Fixed application of advanced telephone validation options in Gravity Forms and WooCommerce

= 2.3 =
* Improved handling of non-numerical values for telephone validation in Gravity Forms and Contact Form 7

= 2.4 =
* Split authentication into server-side and client-side keys to allow greater control over security. Both types of key can be generated from the Data8 Dashboard.

= 2.5 =
* Added more configuration options for the International Telephone Validation service (required country, allowed and barred prefixes).

= 2.6 =
* Added configuration options for the Predictive Address service.

= 2.7 =
* Added support for a 3rd party plugin introducing International Telephone fields (with country drop-down) in Contact Form 7.

= 3.0 =
* Replaced deprecated International Telephone Validation, Mobile Validation and Landline Validation services with the new comprehensive Phone Validation service. Please get in touch with the Client Services team (clientservices@data-8.co.uk) to arrange porting credits between the services and ensure the transition goes as smoothly as possible.

= 3.1 =
* Fixed bug with Gravity Forms multi-page forms performing validation twice.

= 3.2 =
* Fixed trimming of default country code for telephone number validation.

= 3.3 =
* Added functionality to get end user IP.

= 3.4 =
* Added Email, Telephone and Salacious Name validation for WPForms. Bank account validation has been added for WPForms ONLY.

= 3.5 =
* Added Predictive Address for WPForms and Bank Validation for Contact Form 7 and Gravity Forms.

= 3.6 =
* Added all validation services for Elementor Pro

== Upgrade Notice ==

= 1.0 =
* First version, nothing to upgrade!

= 1.1 =
* Bug fixes for when your Data8 account only has access to a single web service

= 1.2 =
* Bug fixes for when your Data8 account has access to telephone validation but not email validation

= 1.3 =
* Bug fix for installing on PHP 5.3

= 1.4 =
* Added support for WooCommerce and Gravity Forms

= 1.5 =
* Added support for SalaciousName to Gravity Forms & Contact Forms 7
* Moved the setting page to it's own page

= 1.6 =
* Added AllowedPrefixes & BarredPrefixes parameters to Tel Val

= 2.0 =
* Updated configuration page to a more user-friendly interface
* Changed configuration to give the user more control over which validation services to use
* Added validation on name fields using Data8 Unusable Name validation

= 2.1 =
* Fixed application of advanced telephone validation options & defaults

= 2.2 =
* Fixed application of advanced telephone validation options in Gravity Forms and WooCommerce

= 2.3 =
* Improved handling of non-numerical values for telephone validation in Gravity Forms and Contact Form 7

= 2.4 =
* Split authentication into server-side and client-side keys to allow greater control over security. Both types of key can be generated from the Data8 Dashboard.

= 2.5 =
* Added more configuration options for the International Telephone Validation service (required country, allowed and barred prefixes).

= 2.6 =
* Added configuration options for the Predictive Address service.

= 2.7 =
* Added support for a 3rd party plugin introducing International Telephone fields (with country drop-down) in Contact Form 7.

= 3.0 =
* Replaced deprecated International Telephone Validation, Mobile Validation and Landline Validation services with the new comprehensive Phone Validation service. Please get in touch with the Client Services team (clientservices@data-8.co.uk) to arrange porting credits between the services and ensure the transition goes as smoothly as possible.

= 3.1 =
* Fixed bug with Gravity Forms multi-page forms performing validation twice.

= 3.2 =
* Fixed trimming of default country code for telephone number validation.

= 3.3 =
* Added functionality to get end user IP.

= 3.4 =
* Added Email, Telephone and Salacious Name validation for WPForms. Bank account validation has been added for WPForms ONLY.

= 3.5 =
* Added Predictive Address for WPForms and Bank Validation for Contact Form 7 and Gravity Forms.

= 3.6 =
* Added all validation services for Elementor Pro

= 3.7 =
* Added advanced options for Phone Validation

== Screenshots ==
1. Configuration screen
2. Contact Form 7 validation
3. Gravity Forms validation
4. PredictiveAddress in action
5. PredictiveAddress in action
6. PredictiveAddress in action
7. PredictiveAddress in action