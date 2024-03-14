=== CP Contact Form with PayPal ===
Contributors: codepeople, paypaldev
Donate link: https://cfpaypal.dwbooster.com
Tags: paypal,paypal payment,paypal donation,paypal form,paypal contact form,paypal button,contact form,contact,form,payment,payment form,order form
Requires at least: 3.0.5
Tested up to: 6.4
Stable tag: trunk
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

PayPal integration for contact forms, payment forms, order forms. Adds a contact form and connect it to a PayPal payment. Created by an Official PayPal Partner.

== Description ==

CP Contact Form with PayPal inserts a **contact form** into a WordPress website and connect it to a PayPal payment, either a **PayPal Standard**  payment or a **PayPal Express / PayPal Credit** payment where available.

New feature: Now includes also a visual form builder for customizing forms.

Once the user has filled the PayPal contact form fields and click the submit button the posted data is saved into the WordPress database and the user is automatically redirected to PayPal to complete a payment. After completed the PayPal payment, the website administrator (the email indicated from the settings) will receive an email with the form data and the user will receive a confirmation/thank you email.

Both the paid forms and unpaid forms sent from the contact form will appear in the WordPress settings area with the mark of "Paid" or "Not Paid" so you can check all the details and contact the user if needed.

This WordPress plugin is useful for different types of contact forms, booking forms, order forms, consultation services, payments for joining events (paid event registration), ect...

= Features: =

CP Contact Form with PayPal has the following main features:

* Supports PayPal Standard and PayPal Express / PayPal Credit (where available)
* Supports many **contact forms** into the same WP website, each one with its own prices and settings.
* Visual form builder for adding/editing/deleting fields
* Allows checking the **messages** for both paid and un-paid submissions sent from the contact form.
* You can **customize the notification email** details, including from address, subject and content with the contact form fields.
* The website administrator receives an **email notification** of the paid contact message.
* The customer receives a **"thank you - confirmation" message**.
* **Easy setup** of the PayPal payment, basically just indicate the price and email linked to the PayPal account. There are optional fields for language and currency settings.
* Export the contact form messages to CSV/Excel
* Support PayPal taxes configuration
* Support recurrent payments
* Refunds can be processed directly from the plugin
* Supports donation layouts for PayPal donation forms
* Optionally request address at PayPal (useful for the delivery of tangible items)
* Includes optional **captcha** verification as part of the contact form.
* Elementor integration, Gutemberg integration
* GDPR acceptance field (General Data Protection Regulation)

= How it can be used =

These are some possible scenarios where this plugin is useful:

* Contact form linked to a PayPal payment
* As a PayPal button
* For accepting donations through PayPal (leave a zero amount in the payment amount)
* Support request forms or paid assistance contact forms
* For receiving product orders (order form), purchases, bookings and reservations.
* For automatic delivering of information after payment (put the information into the auto-reply message)
* Registration forms for events with payment involved
* ... any other use involving forms and PayPal payments

= Commercial Features: =

There are also commercial versions available with more features:

* Visual form builder: The free version includes an useful basic form builder, however the commercial version features an easy, strong and full features visual form builder with all you need to build any form.
* Works also without PayPal, discount codes and dynamic/open prices, additional configuration settings.
* Other payment methods: PayPal Pro, Stripe Payments, Skrill, Authorize.net, Mollie / iDEAL, TargetPay / iDEAL, Sage Pay, SagePayments, Redsys TPV
* Tons of additional features and add ons: Mailchimp, reCapctha, SMS message delivery, Signature fields, etc...

You can get these features at: https://cfpaypal.dwbooster.com

Note: Payments processed through the plugin are SCA ready (Strong Customer Authentication), compatible with the new Payment services (PSD 2) - Directive (EU) that comes into full effect on 14 September, 2019.

= Language Support =

The Contact Form with PayPal plugin is compatible with all charsets. The troubleshoot area contains options to change the encoding of the plugin database tables if needed.

Translations are supported through PO/MO files located in the Contact Form with PayPal plugin folder "languages".

Multiple language translations are already included in the plugin.


== Installation ==

CP Contact Form with PayPal can be installed following these steps:

1.	Download and unzip the CP Contact Form with PayPal plugin
2.	Upload the entire cp-contact-form-with-paypal/ directory to the /wp-content/plugins/ directory
3.	Activate the CP Contact Form with PayPal plugin through the Plugins menu in WordPress
4.	Configure the PayPal contact form settings at the administration menu >> Settings >> CP Contact Form with PayPal
5.	To insert the PayPal contact form into some content or post use the icon that will appear when editing contents

== Frequently Asked Questions ==

= Q: What means each field in the PayPal contact form settings area? =

A: The product's page contains detailed information about each contact form field and customization:

https://cfpaypal.dwbooster.com

= Q: Where can I publish the CP Contact Form with PayPal with the PayPal button? =

A: You can publish the PayPal contact forms / PayPal button into pages and posts. Other versions of the plugin also allow publishing the CP Contact Form with PayPal as a widget.

= Q: The PayPal payment has been received but the status of the Message isn't being set to Paid. What happens? =

A:  First check if you are testing the CP Contact Form with PayPal on a local website or in an online website. Note you should test this feature into an online website (local websites cannot receive PayPal IPN connections).

After that initial verification, please check if the IPN notifications are enabled at your PayPal account. Check also the IPN logs at your PayPal account to confirm if are being received.

= Q: I'm not receiving the emails after PayPal payment. =

A: Please check if the messages are marked as "paid" or "not paid" in the contact form messages page.

If the contact form messages are marked as paid then the problem is that your WordPress isn't delivering the emails. You should setup the WordPress to deliver the emails according to your mail server settings. You may have to ask to your web hosting support about the requirements to send emails from WordPress/PHP with their hosting service.

On the other hand if the contact form messages aren't marked as "paid" then the PayPal IPN connection isn't being received. Read the previous FAQ entry for information and solution.

= Q: How can I customize the style of the PayPal button? =

A: The PayPal button is located at the end of the file "cp_contactformpp_public_int.inc.php". It's a classic submit button, you can change it to any other button that submits the CP Contact Form with PayPal.

= Q: How can I have an actual PayPal button as the submit button for the form instead of the default grey button? =

A: At the end of the file "cp_contactformpp_public_int.inc.php" replace this:

        <?php _e($button_label); ?>

... by this:

        <img src="https://www.paypalobjects.com/en_US/i/btn/btn_buynow_LG.gif" />

You may also want to change the background color of the button with the CSS style class="pbSubmit".

= Q: How can I add specific fields into the contact form message? =

A: There is a tag named %INFO% that is replaced with all the information posted from the form, however you can use also optional tags for specific fields into the contact form.

For doing that, click the desired contact form field into the contact form builder and in the settings box for that field there is a read-only setting named "Field tag for the message (optional):". Copy & paste that tag into the message text and after the form submission (after clicking the PayPal button and receiving the PayPal payment) that tag will be replaced with the text entered in the form field.

= Q: Can I use this with PayPal Personal accounts =

A: Yes, you can use it with PayPal Personal accounts and also with PayPal Premium and PayPal Business accounts.

= Q: Can I accept credit card payments through PayPal directly without forcing the users to create a PayPal account? =

A: That depends of the type of your PayPal account and its status. In most cases PayPal Business accounts and PayPal premium accounts allow accepting payments from users that don't have PayPal accounts. In this case after clicking the PayPal button the PayPal page will appear with two options "Login at your PayPal account" and "Pay directly without having to register". The title of the options at PayPal may vary.

= Q: I'm having problems with non-latin characters in the PayPal contact form. =

A: New: Use the "throubleshoot area" to change the character encoding. If you prefer to do that manually , in most cases the problem is located in the database table collation/encoding. The solution is to change the table encoding to UTF-8. You can do that from the PHPMyAdmin provided by your hosting service.

For example, if your WordPress database table prefix is "wp_" (the default one) then run these queries (will update only the PayPal contact form tables):

    alter table wp_cp_contact_form_paypal_discount_codes convert to character set utf8 collate utf8_unicode_ci;
    alter table wp_cp_contact_form_paypal_settings convert to character set utf8 collate utf8_unicode_ci;
    alter table wp_cp_contact_form_paypal_posts convert to character set utf8 collate utf8_unicode_ci;

If you don't know how to do that, contact our support service and we will help you.

= Q: The contact form doesn't appear in the public website. Solution? =

A: In the "throubleshoot area" (located below the list of forms in the settings area) change the "Script load method" from "Classic" to "Direct".

= Q: How can I duplicate a form and its settings? =

A: Use the "Clone" button located in the contact form's list. That button will duplicate the contact form structure and all its settings.

= Q: How to setup the CP Contact Form with PayPal to accept a PayPal donation? =

A: To accept a PayPal donation (an open donation amount) just put a zero (0) on the "Request Cost" settings field. That way after filling the contact form clicking the PayPal button (contact form submit button) the PayPal payment page will appear letting the user to enter the amount to pay.

= Q: How to show a company name instead the email address at the PayPal payment page? =

A: To show a company name instead the email address at the PayPal payment page (after the contact form submission) you have to use a PayPal Standard Business account. Note that in Personal and Premier Standard accounts the email is shown instead the company name since there is no company name in that case.

Note also that if you are testing the contact form in the SandBox mode then the email may be shown instead the name of the production account.

= Q: Are the forms GDPR compliant? =

A: In all plugin versions you can turn off IP tracking to avoid saving that user info. Full GDPR compliant forms can be built using the commercial versions of the plugin.


= Q: How to enable the refund processing feature? =

A: Into the plugin settings select the option "Process refunds through this plugin?" as "Yes". In addition to that you have to indicate the PayPal (NVP) - API UserName, Password and Signature.

When done to refund a payment just use the refund button that appears for each transaction in the settings area.


== Other Notes ==

**Requesting address at PayPal:** If you are selling tangible items and you need to request the customer address at PayPal you can enable that option into the settings field "Request address at PayPal" available separately for each contact form.

**Taxes at PayPal:** You can indicate the taxes to charge at PayPal over the "request cost" as a percent into the settings field "Taxes (percent)". Each contact form can have a different taxes setting.

**Edit submit button label:** You can easily edit the submit button label into each contact form settings. The **class="cp_subbtn"** can be used to modify the button styles. The styles can be applied into any of the CSS files of your theme or into the CSS file "cp-contact-form-with-paypal\css\stylepublic.css". For further modifications the submit button is located at the end of the file "cp_contactformpp_public_int.inc.php".

**Use a specific field from the form for the payment amount:** If a field is selected in this settings field, any price in the selected field will be added to the above request cost. Use this field for example for having an open donation amount. This field is more useful in the pro version since it supports adding more fields to the contact form.

**Button to change status to paid:** The messages list contains a button to change the status of the "Not paid" contact form messages to "Paid". This is mainly for administrative purposes.

**Export data to CSV/Excel:** The messages list contains an option to export the contact messages received from the contact form to a CSV/Excel file. This way you can export the email address and other data from the contact messages to other applications or manage the data in Excel. The filters in the message list apply also to the exported CSV/Excel file.

**Enabling donation layout:** The plugin supports enabling the PayPal donation layout, this way a payment page improved for donations is displayed to the donors.

== Screenshots ==

1. PayPal Contact Forms List
2. PayPal Contact Form Settings
3. Inserting a PayPal contact form into a page
4. Sample PayPal contact form

== Changelog ==

= 1.0 =
* First stable version released.
* More configuration options added.

= 1.0.1 =
* Compatible with latest WordPress versions
* Speed improvements, the contact form loads faster
* Improved validation options for the contact form fields
* New email content editing feature and interface changes
* New tooltip scripts
* Fixed bug related to discount codes
* Fixed conflict with captcha image generation
* New feature to get the logged in user information into the notification email
* Update to CSS styles for minimizing the CSS conflicts.
* Improvements to CSS styles
* Fixes problem with backslash when saving the contact form settings
* PayPal Sandbox option added
* Added language support through MO/PO files

= 1.1.2 =
* Compatible with the latest WP versions
* Better interface and access to the plugin options
* Captcha image works better in different server environments
* New translations added
* Fixed bug in multisite installations
* Minor bug fixes
* Fixed warning that appeared with PHP safe mode restrictions
* Fixed issue with the site home URL in WP with folders in non-default locations

= 1.1.3 =
* Update to generate the IPN addres with HTTPS if available
* Security update for SQL queries (vulnerability was found by Joaquin Ramirez Martinez with the help Christian Mondragon Uriel Zarate)
* Fixed bug that caused double emails
* Fixed issues with WP sites not configured in the default folders

= 1.1.4 =
* Fixed bug in https detection
* Improved documentation
* Improved debug messages to get feedback
* Compatible with the latest WordPress 4.2.x version

= 1.1.5 =
* Compatible with the WordPress 4.2.2 version

= 1.1.6 =
* Fixes security bug: CSRF, XSS, SQLi - thank you to [Nitin Venkatesh](https://in.linkedin.com/in/nitinvenkatesh/) for the report

= 1.1.7 =
* Fixed issue in SSL detection for the PayPal IPN address
* Improved captha generation code
* Update to fix bug in captcha image generation
* Update to the captcha processing code
* Added nonce verification in the form settings
* Security update for CSRF issue

= 1.1.8 =
* Compatible with WordPress 4.3
* Replacement of heading tags for the WP 4.3
* Update to the Dutch language
* Fixed a bug in the submission process

= 1.1.9 =
* Fixed bug in submission process
* New parameter for optimizing support

= 1.1.10 =
* Admin interface updates

= 1.1.11 =
* Supports PayPal recurrent payments

= 1.1.12 =
* Supports for new language in troubleshoot area

= 1.1.13 =
* Fixed menu slug

= 1.1.14 =
* Option to bill the recurrent payments every two monts

= 1.1.15 =
* Tested and compatible with WordPress 4.4
* Fixed CSS bugs

= 1.1.16 =
* Fixed query bug in WP 4.4

= 1.1.17 =
* Fixed captcha issues

= 1.1.18 =
* PayPal button update

= 1.1.19 =
* Optimized CSS styles

= 1.1.20 =
* Update to database structure

= 1.1.21 =
* Fixed captcha bug in Windows servers

= 1.1.22 =
* Improved captcha security

= 1.1.23 =
* Updated website URL. New documentation and distributions.

= 1.1.24 =
* French translation updated

= 1.1.25 =
* Fixed PHP session issues
 
= 1.1.26 =
* Shortcode paramters sanitization

= 1.1.27 =
* Used SSL for API URLs

= 1.1.28 =
* Added fr_CA translation

= 1.1.29 =
* Added nonces to message list

= 1.1.30 =
*  Updated FAQ and doc URLs

= 1.1.31 =
* Plugin support and demo links updated

= 1.1.32 =
* New filter for file uploads

= 1.1.33 =
* Tested for WP 4.5

= 1.1.34 =
* Stripcslashes fix

= 1.1.35 =
* Scripts moved to head section

= 1.1.36 =
* Fixed bug in admin list

= 1.1.37 =
* Added explicit error page

= 1.1.38 =
* Improved initialization hooks

= 1.1.39 =
* Wrong </p> tag removed from CP Contact Form with PayPal

= 1.1.40 =
* Removed old versions of CP Contact Form with PayPal

= 1.1.41 =
* New documentation

= 1.1.42 =
* Fix in FROM email

= 1.1.43 =
* IP Address excluded from email as default value

= 1.1.44 =
* Security improvement

= 1.1.45 =
* POST param sanitization

= 1.1.46 =
* Better from email setup

= 1.1.47 =
* Doc update

= 1.1.48 =
* Fix to admin link

= 1.1.49 =
* Change name to reflect exactly what the plugin does

= 1.1.50 =
* Compatible with WP 4.6

= 1.1.51 =
* New custom request form 

= 1.1.52 =
* New option to set lenght of initial period for PayPal recurrent payments

= 1.1.53 =
* Fixed bug in saving settings data

= 1.1.54 =
* IP address field update

= 1.1.55 =
* Magic quotes processing correction

= 1.1.56 =
* More updates about magic quotes

= 1.1.57 =
* Roll back to incorrect update

= 1.1.58 =
* Interface update

= 1.1.59 =
* Donation layout documented

= 1.1.60 =
* Update to French translation

= 1.1.61 =
* New placeholder email

= 1.1.62 =
* Better debug service

= 1.1.63 =
* Fixed DB error on IP v6 networks

= 1.1.64 =
* Int. typos corrected

= 1.1.65 =                           
* PayPal IPN verification improvement 

= 1.1.66 =
* New documentation page

= 1.1.67 =
* WP 4.7 compatible

= 1.1.68 =
* Code optimizations

= 1.1.69 =
* Instructions update

= 1.1.70 =
* Database structure update

= 1.1.71 =
* Fixed typo

= 1.1.72 =
* Fixed typo

= 1.1.73 =
* DB improvements

= 1.1.74 =
* Security improvement

= 1.1.75 =
* Admin interface modification

= 1.1.76 =
* CSS update

= 1.1.77 =
* Validation update

= 1.1.78 =
* Fixed bug in add CP Contact Form with PayPal

= 1.1.79 =
* Admin updates

= 1.1.80 =
* Code updates

= 1.1.81 =
* Security improvement

= 1.1.82 =
* Help address updated

= 1.1.83 =
* Compatible with WP 4.7.3

= 1.1.84 =
* Update related to the parameters in the PayPal IPN notification

= 1.1.85 =
* Using now only 1 parameter for the PayPal IPN notification

= 1.1.86 =
* Tags updated to max 12

= 1.1.87 =
* Added email validation in admin

= 1.1.88 =
* Form builder updated suporting email field types

= 1.1.89 =
* Added several field validations in the admin settings form

= 1.1.90 =
* Captha code optimizations and security improvements
* Fix to PayPal recurrent payments setup

= 1.1.91 =
* New review panel 

= 1.1.92 =
* Fix in review panel

= 1.1.93 =
* Added PayPal Express + PayPal Credit feature

= 1.1.94 =
* Fixed bug on older PHP versions

= 1.1.95 =
* Sanitization for PayPal email address

= 1.1.96 =
* Validation code in PayPal Standard Integration

= 1.1.97 =
* Tested and compatible with WordPress 4.8

= 1.1.98 =
* Removed deprecated PayPal parameters

= 1.1.99 =
* Cleanup for PayPal parameters

= 1.2.10 =
* Accessibility updates

= 1.2.11 =
* Fixed bug in color selectors

= 1.2.12 =
* Moved plugin website and links to SSL

= 1.2.14 =
* Removed use of deprecated MySQL functions

= 1.2.15 =
* Accesibility and validation updates

= 1.2.16 =
* PayPal return address santized

= 1.2.17 =
* Fixed number validation issue in Google Chrome

= 1.2.18 =
* Improved print option

= 1.2.19 =
* Admin area improvements

= 1.2.20 =
* Better PayPal currency selection

= 1.2.21 =
* Cost number formatting

= 1.2.22 =
* Price setting auto-cleaning

= 1.2.23 =
* Added default values for options

= 1.2.24 =
* Code and validation updates

= 1.2.25 =
* Fixed notice when email field not defined

= 1.2.26 =
* Fixed escaping of translated texts

= 1.2.27 =
* Improved file extension verification

= 1.2.28 =
* Better equeue of CSS files

= 1.2.29 =
* Compatible with WordPress 4.9

= 1.2.30 =
* Easier file for form customization

= 1.2.31 =
* Added support for HTML formatted emails

= 1.2.32 =
* Fixed typos and interface improvements

= 1.2.33 =
* Fixed bug in review panel

= 1.2.34 =
* Added CSS and JavaScript customization panel

= 1.2.35 =
* Improved submission process to avoid duplicated submissions

= 1.2.36 =
* Better currency auto-detection

= 1.2.37 =
* Additional improvements to currency auto-detection

= 1.2.38 =
* Code updates & bug fixes

= 1.2.39 =
* Admin interface improvements

= 1.2.40 =
* Added security verification

= 1.2.41 =
* Adjustments to make the form GDPR compliant

= 1.2.42 =
* Added two-months initial billing period for recurrent payments

= 1.2.43 =
* Removed use of external CSS from Google CDN

= 1.2.44 =
* Label updates to fix layout issues

= 1.2.45 =
* Feature to display form name on settings page

= 1.2.46 =
* Faster script load method as default option

= 1.2.47 =
* Fixed conflict with autoptimize plugin

= 1.2.48 =
* Fixed captcha reloading issue

= 1.2.49 =
* Improvements to captcha validation notifications

= 1.2.50 =
* Improvements to bookings list to match GPDR requirements

= 1.2.51 =
* Interface updates

= 1.2.52 =
* Currency detection improvement

= 1.2.53 =
* Fixed open forms conflicts

= 1.2.54 =
* Easier activation process

= 1.2.55 =
* Fixed initialization issue and added optional feedback

= 1.2.56 =
* New active visual form builder
* GDPR acceptance fields

= 1.2.57 =
* Added payment refund feature

= 1.2.58 =
* Added support for INR - Indian Rupee and ARS - Argentine peso currencies

= 1.2.59 =
* Database creating encoding fix 

= 1.2.60 =
* Fixed activation bug

= 1.2.61 =
* Fixed jQuery conflict

= 1.2.62 =
* Interface improvements

= 1.2.63 =
* Fixed admin interface conflict with 3rd party plugin

= 1.2.64 =
* Compatible with Gutenberg

= 1.2.65 =
* Fix to Gutenberg integration

= 1.2.66 =
* Fixed conflict with Gutenberg editor

= 1.2.67 =
* Fixed magic quotes issue

= 1.2.68 =
* Gutenberg integration update

= 1.2.69 =
* Fixed issue in taxes calculations

= 1.2.70 =
* Improved CSS edition area

= 1.2.71 =
* Interface improvements

= 1.2.72 =
* Currency setting clarifications

= 1.2.73 =
* Fixed conflict with third party plugins

= 1.2.74 =
* Gutenberg compatibility updates

= 1.2.75 =
* Fixed conflict with loading page plugins

= 1.2.76 =
* New publishing option. Compatible with WordPress 5.0

= 1.2.77 =
* Form builder header fixes

= 1.2.78 =
* CSS, interface updates and improved conflicts detection

= 1.2.79 =
* Interface fixes

= 1.2.80 =
* CSV Encoding fix and interface improvements

= 1.2.81 =
* New Gutemberg Block

= 1.2.82 =
* Improved translations

= 1.2.83 =
* Fixed bug in form edition

= 1.2.84 =
* Removed use of CURL

= 1.2.85 =
* Added Elementor Page Builder integration

= 1.2.86 =
* Administration area ready for translations

= 1.2.87 =
* Fixes to translations and SSL detection

= 1.2.88 =
* Better email address auto-config and compatible with latest WordPress version

= 1.2.89 =
* Fixed bug in email settings

= 1.2.90 =
* Fixed settings saving issue

= 1.2.91 =
* Fixed conflict with lazy loading feature of Jetpack

= 1.2.92 =
* Minor interface update

= 1.2.93 =
* Fixed conflict with Yoast SEO

= 1.2.94 =
* Review link update

= 1.2.95 =
* Compatible with WordPress 5.2

= 1.2.96 =
* Fixed bug in iconv functions

= 1.2.97 =
* Update for compatibility with WordPress 5.2

= 1.2.98 =
* Fixed XSS vulnerability in CSS edition

= 1.2.99 =
* Fixed XSS vulnerability in publishing wizard

= 1.3.01 =
* Multiple code improvements

= 1.3.02 =
* Multiple code improvements & sanitization

= 1.3.03 =
* Misc code improvements

= 1.3.04 =
* Additional code improvements

= 1.3.05 =
* Fix to database encoding

= 1.3.06 =
* Update to publish section

= 1.3.07 =
* PSD 2 complaint - SCA Ready

= 1.3.08 =
* Fixed bug marking items as paid

= 1.3.09 =
* Sanitization improvements

= 1.3.10 =
* Compatible with WordPress 5.3

= 1.3.11 =
* Added INR currency

= 1.3.12 =
* Fixed email delivery bug

= 1.3.14 =
* Database improvements

= 1.3.15 =
* Interface improvements

= 1.3.16 =
* Fixed bug in PayPal IPN

= 1.3.17 =
* New hooks for conversion tracking

= 1.3.18 =
* Compatible with WordPress 5.4

= 1.3.19 =
* Fixed compatibility with 3rd party scripts

= 1.3.20 =
* Insert / publish block updated

= 1.3.21 =
* New params supported in redirect address

= 1.3.22 =
* Faster form load speed

= 1.3.23 =
* Faster captcha rendering

= 1.3.24 =
* Fixed captcha conflict

= 1.3.25 =
* Compatible with WordPress 5.5

= 1.3.26 =
* Updated jQuery deprecated code

= 1.3.27 =
* Compatible with WordPress 5.6

= 1.3.28 =
* Compatible with WordPress 5.7
* Interface styles update

= 1.3.29 =
* PHP 8.0 compatibility updated
* CSS improvements

= 1.3.30 =
* Compatible with WordPress 5.8

= 1.3.31 =
* Compatible with WordPress 5.9

= 1.3.32 =
* Elementor integration update

= 1.3.33 =
* Compatible with WordPress 6.0

= 1.3.34 =
* Validation fix

= 1.3.35 =
* Feedback panel update

= 1.3.36 =
* Better captcha

= 1.3.37 =
* Compatible with WP 6.1

= 1.3.38 =
* Translations and settings update

= 1.3.39 =
* PHP 8 update

= 1.3.40 =
* Compatible with WP 6.2
* PayPal express update

= 1.3.41 =
* PHP 8 fix

= 1.3.42 =
* WP 6.2 update

= 1.3.43 =
* Compatible with WP 6.3

= 1.3.44 =
* Improved submission process

= 1.3.45 =
* Compatible with WP 6.4

= 1.3.46 =
* Layout fix

= 1.3.47 =
* New hook on completed payments

== Upgrade Notice ==

= 1.3.47 =
* New hook on completed payments