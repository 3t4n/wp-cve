=== WP Payeezy Pay ===
Contributors: RickRottman
Donate link: https://www.paypal.me/RichardRottman
Tags: First Data, Fiserv, Payeezy, Recurring Payments, Hosted Checkout, Payment Form, Donation Form
Requires at least: 3.0.2
Tested up to: 6.2.2
Stable tag: 3.18
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Connects a WordPress site to the Payeezy Gateway using the Payment Page/Hosted Checkout (HCO) method of integration. Note: When updating to a newer version, go into Settings and press "Save WP Payeezy Settings" at the bottom of the page.

== Description ==

Plugin creates a shortcode that when placed in the body of a page or a post, generates a payment or donation form for a Payeezy. The published form includes:

* First Name
* Last Name
* Company Name (optional)
* Street Address
* City
* State (dropdown)
* Zip Code
* Country (dropdown)
* Email Address (optional)
* Phone Number (optional)
* x_invoice_num (optional)
* x_po_num (optional)
* x_reference_3 (optional)
* User Defined 1 (optional)
* User Defined 2 (optional)
* User Defined 3 (optional)
* Amount (optionally recurring every month)
* "Pay Now", "Donate Now", "Continue", "Continue to Secure Payment Form", or "Continue to Secure Donation Form" button

Once a cardholder enters their information on your website for making a payment or a donation, they press a submit button. They are then redirected to the secure, PCI compliant form hosted by Payeezy. They then finish by entering the credit card number, expiration date, and security code. Once the transaction is complete, the user is provided a receipt. They can then click a link and be redirected back to the original website. 

== Installation ==

**From your WordPress dashboard**

1. Visit 'Plugins > Add New'.

2. Search for 'WP Payeezy Pay'.

3. Press the 'Install Now' button.

4. Press the 'Active Now' button.

**From WordPress.org**

1. Download WP Payeezy Pay.

2. Upload the 'WP Payeezy Pay' directory to your '/wp-content/plugins/' directory, using your favorite method (ftp, sftp, scp, etc...).

3. Press the 'Active Now' button.

**Once Activated**

1. Visit 'Menu > WP Payeezy Pay > and enter the Payment Page ID and the Transaction Key. These values are obtained in Payeezy. Also enter a currency code. If your account is setup for US Dollars, enter USD.

2. Choose the Mode you wish to use, 'Live' to processes credit cards or 'Demo' for a non-production testing account. 'Troubleshooting' is to test what is getting sent to Payeezy. As the name sugests, it's for troubleshooting.

3. Enter the Type of Transactions you want the Payment Page to make:

    * Payments  - All payments are done on a singular basis. 

    * Payments with optional Recurring - Customer has the option of clicking a box that will repeat their payment automatically in 30 days. If they don’t click the box, the payment is handled as a single payment. Recurring payment will continue until the Payeezy Merchant Administrator goes into Recurring and suspends or deletes the Recurring payment.
 
    * Payments with automatic Recurring - Customer doesn’t get a checkbox to make the payment recurring. The transaction will automatically be made again in 30 days and will continue until the Payeezy Merchant Administrator goes into Recurring and suspends or deletes the Recurring payment. Good for gym memberships, karate schools, etc.

    * Donations - Cardholder will have the option of making a donation by selecting a predefined amount. If none of the predefined amounts are optimal, they can select 'Other' and enter their own. Instead of a button labeled 'Pay Now' to go to the secure payment form hosted by First Data, the button will be labeled 'Donate Now.' 

    * Donations with optional Recurring - This is just like the above, but it gives the cardholder the option of making their donation on a monthly recurring basis by clicking a box. Recurring donations will continue until the Payeezy Merchant Administrator goes into Recurring and suspends or deletes the Recurring donation. 

4. Enter names for the optional payment form fields that you would like to use with your payment form. If you leave any of these fields blank, they will not appear on the published payment for. For example, if you want your customers to enter their invoice number on the bill they are paying, you would enter 'Invoice Number' in the x_invoice_num field. A field will then appear on the published payment form for Invoice Number. The value entered by the cardholder will be passed on to Payeezy and it will be part of the transaction record. 

5. Press 'Save Settings'.


**Once Configured**

1. To add a payment form to a Post or a Page, simply add the '[wp_payeezy_payment_form]' shortcode to content. 

2. Publish the Post or Page. 

3. That's it! 


== Frequently Asked Questions ==

= How can I get a Payeezy Demo account?

Go [here and sign up](https://provisioning.demo.globalgatewaye4.firstdata.com/signup). It's completely free. 

= Can I use WP Payeezy Pay even though my site does not have an SSL certificate?

Yes. Because the the credit card number, the expiration date, and the security code are entered on Payeezy's site, WP Payeezy Pay does not require an SSL certificate. 

= Does WP Payeezy Pay work with WooCommerce?

No. WP Payeezy Pay uses the Hosted Checkout (HCO) method of integration. Plugins that connect to WooCommerce use the API method of integration.

= Is this plugin an official First Data Payeezy product? =

No. I used to work for First Data supporting their various e-commerce products. This plugin is independent of First Data Payeezy but was built using their [sample code](https://support.payeezy.com/hc/en-us/articles/204011429-Sample-Code-for-Creating-a-Pay-Button-to-use-with-a-Hosted-Payment-Page) on my own time. 


== Screenshots ==

1. Required Settings.
2. Optional Settings. 
3. Optional Payment Fields.
4. Payment Page ID and Transaction Key in Payeezy. 
5. Recurring Billing ID in Payeezy.

== Changelog ==

= 3.18

* Updated links.

= 3.17

* Removed reference to a stylesheet that no longer exists. If warnings is turned on in wp-config.php, it would display an error.

= 3.16.1

* Removed leftover code that was throwing an error if warnings are turned on. 

= 3.16

* Returned CSS and drop down content back to the mail file. I discovered at least one website was not loading CSS or the drop down content from the external files. 

= 3.15.2

* Added a </div> to line 361. 
* Fixed padding around the currency symbol on the custom amount field.

= 3.15.1

* Added ' step=".01" ' back to the x_amount input field per request.

= 3.15 =

* Corrected invalid HTML that I allowed to creep into the code (Thanks Brian Gunzenhauser).
* Moved CSS to separate files located into a new folder.
* Moved states and countries to separate files located into a new folder.
* Created /stylesheets folder.
* Created /select folder.
* Added a link to the plugin's support forum on WordPress.

= 3.14 =

* Closed a div that was left open.
* Added missing CSS for Telephone and Email field.

= 3.13.1 =

* Added missing drop down arrow image.

= 3.13 =

* Modified the look of the form. State and country drop-downs now have an arrow down symbol. Added currency symbol before the amount instead of the currency code ("USD") after the amount. Added fancy donation buttons.
* Moved information from the published form to the backend where it cannot be viewed by prying eyes. 
* If you don't assign an invoice number to a transaction, it will populate an invoice number containing based on the cardholder's name and the date. 


= 3.12.1 = 

* Fixed a couple of typos.

= 3.12 = 

* x_description was not getting sent to Payeezy.

= 3.11 = 

* Minor housekeeping.

= 3.10 = 

* HMAC encryption type can be selected in the plugin's settings, HMAC-MD5 or HMAC-SHA1. The default is still HMAC-MD5 because that was the default on Payeezy until it was changed sometime this year. When you create a new payment page on Payeezy, it automatically sets it to HMAC-SHA1.

= 3.09 = 

* Changed site_url() to home_url() to combat 404 errors. Tip of the hat to Christian Sanchez.

= 3.08 = 

* Fixed a bug that was causing donations with a custom amount not to work. 

= 3.06 =

* Fixed a bug that was causing WP Payeezy Pay not to appear in the left-hand menu.

= 3.05 =

* Fixed a bug that was causing 'The plugin generated 625 characters of unexpected output during activation. If you notice “headers already sent” messages, problems with syndication feeds or other issues, try deactivating or removing this plugin.' warning.

= 3.04 =

* Fixed a bug that was throwing a non-fatal error message about recurring.

= 3.03 =

* Removed pay.php that resided in the /plugins/wp-payeezy-pay folder. This file acted as an endpoint file for the payment/donation form. Data that was collected from the form was sent to pay.php file where a security hash was created. It was then immediately sent to First Data. Instead of this file, upon activation of the plugin, a new page is created with the title, 'payeezyendpoint.' This new page contains the contents of what pay.php possessed. The reason for this is change is because some security plugins were blocking access to pay.php. The result was a 404 error when attempting to process a transaction. 

= 3.02 =

* Removed name of the x_invoice field from the x_invoice. First Data was not showing the full field in reports and on the generated receipt. 
* Now shows "payment" or "donation" on the temporary page between the form and Payeezy. Made the text larger and colored the amount green.  

= 3.01 =

* Corrected an error that was causing Payments to trigger a white screen of death. 

= 3.00 =

* Removed POST sanitize in pay.php and instead added stripslashes() to each POST. 
* Added generic key.php file that the Transaction Key can be manually inserted if needed. 
* Made it so pay.php cannot be accessed directly.
* Added index.php to the plugin's folder. If someone tries to access pay.php directly, they will see this file instead. 

= 2.99 =

* Consolidated pay.php, donate.php, donate-rec, and pay-rec into a single file, pay.php.
* Sanitized all POST calls. 

= 2.98 =

* Updated pay.php, donate.php, donate-rec, and pay-rec to eliminate a potential local file inclusion (LFI) vulnerability.

= 2.97 =

* Updated screenshots and links.

= 2.96 =

* When updating the plugin, the administrator needed to go into the plugin's settings and press "Save WP Payeezy Pay Settings." Not doing this resulted in a nasty and unwelcome x_fp_hash error. This is no longer necessary. The plugin can be updated and the administrator does not need to do anything. The settings will now be saved automatically anytime the plugin is activated. 

= 2.95 =

* Fixed a bug where transactions on their way to Payeezy were labeled as a donation even if they were a payment. 

= 2.94 =

* Added Troubleshooting to Mode. When Troubleshooting is selected and saved, instead of submitting the information to First Data, the information is sent to a file that displays the information being sent, including transaction_key. Very useful for troubleshooting.  

= 2.93 =

* Added a button in the settings that takes you to the page or post the form is on. 

= 2.92 =

* Notification Email and Notification Email Subject were not saving. Hat tip to 
Buck Sommerkamp.

= 2.91 =

* x_company was not working if processing donations. 
* Added a field for receipt URL to be used with WP Payeezy Results. 

= 2.90 =

* Removed all parts of Better Payeezy Email. Was causing unforeseen problems and was not working as intended. Planning on rolling its features into WP Payeezy Results. 

= 2.89 =

* Added the ability to work with Better Payeezy Email, a premium add-on plugin for WP Payeezy Pay. 
* Added icon back to plugin's menu on th sidebar. Where did it go before?
* Moved form's stylesheet to it's on separate file located in wp-payeezy-pay/css/stylesheet.css.
* Added a separate changelog that can be viewed from within the plugin's administration screen.
* Removed prices for premium plugins. 


= 2.88 =

* If a street address (x_address) is longer than 30 characters, the gateway will not submit the transaction and the attempt will fail. If the x_address is longer than 30 character, the plugin will truncate the x_address to the first 30 characters. 

= 2.87 =

* Added clarification that plugin is fully compliant with TLS v1.2 security protocol requirement. 

= 2.86 =

* Corrected "Continue to Secure Donation Form".
* Removed "Make it So".
* Added prices for premium plugins.
* Added Notification Email field (future use).

= 2.85 =

* Cleaned code and added to the link for Payeezy Pay.

= 2.84 =

* Fixed a typo. Added a link to Payeezy Pay.

= 2.83 =

* Fixed a bug that wouldn't use the x_invoice_num as x_description if x_invoice_num is being used.

= 2.82 =

* Fixed a bug that wouldn't allow a user to tab to the Country input.

= 2.81 =

* Require a payment amount to be entered for non-donation payments. This was already required for donations.

= 2.80 =

* Cleaned up the transposition page between the payment/donation form and First Data.

= 2.79 =

* Corrected a few typos.
* Linked field names with definitions explaining what they are and how they work.

= 2.78 =

* Response Key field was mistakenly made a required field. I corrected that.

= 2.77 =

* Converted Currency Code to a drop-down. Added Response Key field. 

= 2.76 =

* Removed the option of selecting x_type. It was causing intermittent errors on too many installations. Transactions will be processed as Authorization and Capture. 

= 2.75 =

* Fixed a bug that stopped the ability to change/update the currency type.

= 2.74 =

* Changed the way the form uses CSS. It's now included in the form instead of pulling it from a separate CSS file.  
* Changed the way the pay.php, donate.php, donate-rec, and pay-rec process x_type. The way it was included in the file was throwing intermittent errors.  

= 2.73 =

* Corrected an image link. 
* Added donation button. 

= 2.72 =

* Added the ability to process transactions as authorization-only transactions. 
* Corrected the spelling of "Provence."  

= 2.71 =

* Added states and countries directly to the main plugin file. 

= 2.70 =

* Did away with the need to save the Transaction Key after upgrading the plugin to the latest version. It will now happen automatically during the activation process. 

= 2.67 =

* The transaction key file is now named after the Payment Page ID. This should enable this plugin to be used multisite.
* Added the x_company field as one of the optional fields.
* Add x_description back to the optional fields. I don’t remember ever removing it, so it must have been by mistake. I made it a textarea field since Payeezy doesn’t seem to have any character restrictions. Most fields are caped at 30 characters. This field has no such restrictions. I’ve tested it with hundreds of words without any errors. If you want a field for the cardholder to write a book, this is the one you want to use.
* Removed unnecessary css from the admin screen.

= 2.66 =

* State and Country selectors now show "Select a State" or "Select a Country" instead of the first option.


= 2.65 =

* Changed the way the admin screen is styled.
* Added the state selector and country selector drop-down values in a separate text file.  

= 2.62 =

* Fixed a problem that allowed donors to enter a negative custom amount.

= 2.61 =

* Donors now not continue to First Data without first selecting an amount to donate.
* If for whatever reason the Transaction Key file is blank and needs to be generated, an error message will appear at the top of any Admin screen advising that the "Save Now" button in Payeezy settings needs to be pressed.


= 2.60 =

* Added the ability to change the text on the submit button.
* Direct link to the stylesheet in the plugin editor.
* Added a link to the WordPress plugin repository to make it easier to leave a 5-star (I hope!) review. 
* Added a warning message if the Transaction Key has not been saved. 

= 2.53 =

* Removed break at the end of every label.
* Added an external stylesheet (wp-payeezy-pay/stylesheet.css) for the form so it can be modified, customized, copied, or manipulated. 


= 2.52 =

* Added a break at the end of every label.


= 2.51 =

* Updated screenshots.

= 2.50 =

* Moved Currency Code to the top of Required settings.
* Now generates a message if Payment Page ID, Transaction Key, or Currency Code is not entered.
* Added a link for a new add-on plugin, Payeezy Transactions. 

= 2.45 = 

* Noticed I had placed Alabama twice in the drop-down. 
* Removed the link to WP Payeezy Transactions. I am not ready to make it live yet. 


= 2.40 = 

* Added the ability to hard-code the amount the card holder will pay. 
* Added Response Key so that WP Payeezy Pay will be compatible with WP Payeezy Transactions, a premium add-on.
* Updated the banner image and the icon image. The purple color was irritating me. 

= 2.36 = 

* Removed two breaks and a horizontal line that was causing an annoying space before the form. Thanks Colette!
* Updated the banner image and the icon image so it (hopefully) looks nicer in the plugin repository. 

= 2.35 =

* Fixed a problem with the currency code. 

= 2.31 =

* Fixed an image. 

= 2.30 = 

* Tested to make sure it is compatible with WordPress 4.4.
* Added Currency Code to the required settings. 
* Cleaned up the CSS to make sure everything looks pretty.

= 2.25 = 

* Minor changes involving support links. 

= 2.2 = 

* Now the cardholder making a donation cannot proceed to Payeezy without picking an amount.  

= 2.1 = 

* Made a change that strengthens security of the plugin. The Transaction Key is no longer visible in the HTML form. It's now stored in a tiny file called key.php located in the plugin's directory.   

= 2.0 = 

* Combined this plugin with my other plugin, WP Payeezy Donate. All features found in that plugin are now rolled into this plugin. Going forward, this will be the only plugin updated, assuming updates are needed. If you select a Transaction Type option that supports Recurring and if you save the settings without entering a Recurring Billing ID, an error is displayed. If the mode is set to Demo, it now displays a notice. I also corrected a few typos and commented most of the code.  

= 1.4 = 

* Added the ability to do Recurring.

= 1.3 = 

* Fixed a typo that wasn't allowing x_reference_3 to work. 

= 1.2 = 

* Removed Recurring. Was causing an error if no Recurring Plan ID was entered in the settings. The ability to add Recurring will be added back in a future update. Stay tuned!

= 1.1 = 

* Changed the field values to be required values if they are added to the form. If a cardholder leaves a field blank, they will be told the field is required before proceeding. 

= 1.0 =

* Initial release.


== Upgrade Notice ==

= 3.17

* Removed reference to a stylesheet that no longer exists. If warnings is turned on in wp-config.php, it would display an error.

= 3.16.1

* Removed leftover code that was throwing an error if warnings are turned on. 

= 3.16

Stylesheets and drop down data was not loading correctly in at least one site. 

= 3.15.2

* Added a </div> to line 361. 
* Fixed padding around the currency symbol on the custom amount field.

= 3.15.1

* Added ' step=".01" ' back to the x_amount input field per request.

= 3.15 =

* Corrects invalid HTML.

= 3.14 =

* Fixed a problem with the HTML form that was causing ugliness and unnecessary chaos.

= 3.13.1 =

* Important! Fixes a bug that showed up in yesterday's update that resulted in the amount field to not show up.

= 3.13 =

* Modified the look of the form. State and country drop-downs now have an arrow down symbol. Added currency symbol before the amount instead of the currency code ("USD") after the amount. Added fancy donation buttons.
* Moved information from the published form to the backend where it cannot be viewed by prying eyes. 
* If you don't assign an invoice number to a transaction, it will populate an invoice number containing based on the cardholder's name and the date. 

= 3.12.1 = 

* Fixed a couple of typos.

= 3.12 = 

* x_description was not getting sent to Payeezy.

= 3.11 = 

* Minor housekeeping.

= 3.10 = 

* HMAC encryption type can be selected in the plugin's settings, HMAC-MD5 or HMAC-SHA1. The default is still HMAC-MD5 because that was the default on Payeezy until it was changed sometime this year. When you create a new payment page on Payeezy, it automatically sets it to HMAC-SHA1.

= 3.09 = 

* Changed site_url() to home_url() to combat 404 errors. Tip of the hat to Christian Sanchez.

= 3.08 =

* Fixed a bug that was causing donations with a custom amount not to work. 

= 3.06 =

* Fixed a bug that was causing WP Payeezy Pay not to appear in the left-hand menu.

= 3.05 =

* Fixed a bug that was causing 'The plugin generated 625 characters of unexpected output during activation. If you notice “headers already sent” messages, problems with syndication feeds or other issues, try deactivating or removing this plugin.' warning.

= 3.04 =

* Fixed a bug that was throwing a non-fatal error message about recurring.


= 3.03 =

* Removed pay.php that resided in the /plugins/wp-payeezy-pay folder. This file acted as an endpoint file for the payment/donation form. Data that was collected from the form was sent to pay.php file where a security hash was created. It was then immediately sent to First Data. Instead of this file, upon activation of the plugin, a new page is created with the title, 'payeezyendpoint.' This new page contains the contents of what pay.php possessed. The reason for this is change is because some security plugins were blocking access to pay.php. The result was a 404 error when attempting to process a transaction. 

= 3.02 =

* Removed name of the x_invoice field from the x_invoice. First Data was not showing the full field in reports and on the generated receipt. 
* Now shows "payment" or "donation" on the temportary page between the form and Payeezy. Made the text larger and colored the amount green.  

= 3.01 =

* Corrected an error that was causing Payments to trigger a white screen of death. 

= 3.00 =

* Removed POST sanitize in pay.php and instead added stripslashes() to each POST call. 
* Added generic key.php file that the Transaction Key can be manually inserted if needed. 
* Made it so pay.php cannot be accessed directly.
* Added index.php to the plugin's folder. If someone tries to access pay.php directly, they will see this file instead. 

 = 2.99 =

* Consolidated pay.php, donate.php, donate-rec, and pay-rec into a single file, pay.php.
* Sanitized all POST calls. 

= 2.98 =

* Updated pay.php, donate.php, donate-rec, and pay-rec to eliminate a potential local file inclusion (LFI) vulnerability.

= 2.97 =

* Updated screenshots and links.

= 2.96 =

* When updating the plugin, the administrator needed to go into the plugin's settings and press "Save WP Payeezy Pay Settings." Not doing this resulted in a nasty and unwelcome x_fp_hash error. This is no longer necessary. The plugin can be updated and the administrator does not need to do anything. The settings will now be saved automatically anytime the plugin is activated.

= 2.95 =

* Transactions on their way to Payeezy were labeled as a donation even if they were a payment.   

= 2.94 =

* Added Troubleshooting to Mode. When Troubleshooting is selected and saved, instead of submitting the information to First Data, the information is sent to a file that displays the information being sent, including transaction_key. Very useful for troubleshooting.  

= 2.93 =

* Added a button in the settings that takes you to the page or post the form is on. 

= 2.92 =

* Notification Email and Notification Email Subject were not saving. Hat tip to 
Buck Sommerkamp.

= 2.91 =

* x_company was not working if processing donations. 
* Added a field for receipt URL to be used with WP Payeezy Results. 

= 2.90 =

* Removed all parts of Better Payeezy Email. Was causing unforeseen problems and was not working as intended. Planning on rolling its features into WP Payeezy Results. 

= 2.89 =

* Added the ability to work with Better Payeezy Email, a premium add-on plugin for WP Payeezy Pay. 
* Added icon back to plugin's menu on th sidebar. Where did it go before?
* Moved form's stylesheet to it's on separate file located in wp-payeezy-pay/css/stylesheet.css.
* Added a separate changelog that can be viewed from within the plugin's administration screen.
* Removed prices for premium plugins. 


= 2.88 =

* If a street address (x_address) is longer than 30 characters, the gateway will not submit the transaction and the attempt will fail. If the x_address is longer than 30 character, the plugin will truncate the x_address to the first 30 characters. 

= 2.87 =

* Added clarification that plugin is fully compliant with TLS v1.2 security protocol requirement. 

= 2.86 =

* Corrected "Continue to Secure Donation Form".
* Removed "Make it So".
* Added prices for premium plugins.
* Added Notification Email field (future use).

= 2.85 =

* Cleaned code and added to the link for Payeezy Pay.

= 2.84 =

* Fixed a typo. Added a link to Payeezy Pay.

= 2.83 =

* Fixed a bug that wouldn't use the x_invoice_num as x_description if x_invoice_num is being used.

= 2.82 =

* Fixed a bug that wouldn't allow a user to tab to the Country input.

= 2.81 =

* Require a payment amount to be entered for non-donation payments. This was already required for donations.

= 2.80 =

* Cleaned up the transposition page between the payment/donation form and First Data.


= 2.79 =

* Corrected a few typos.
* Linked field names with definitions explaining what they are and how they work.

= 2.78 =

* Response Key field was mistakenly made a required field. I corrected that.

= 2.77 =

* Converted Currency Code to a drop-down. Added Response Key field. 

= 2.76 =

* Removed the option of selecting x_type. It was causing intermittent errors on too many installations. Transactions will be processed as Authorization and Capture. 

= 2.75 =

* Fixed a bug that stopped the ability to change/update the currency type.


= 2.74 =

* Changed the way the form uses CSS. It's now included in the form instead of pulling it from a separate CSS file.  
* Changed the way the pay.php, donate.php, donate-rec, and pay-rec process x_type. The way it was included in the file was throwing intermittent errors.  


= 2.73 =

* Corrected an image link. 
* Added donation button. 

= 2.72 =

* Added the ability to process transactions as authorization-only transactions. 
* Corrected the spelling of "Provence."  



= 2.71 =

* Added states and countries directly to the main plugin file. 

= 2.70 =

* Did away with the need to save the Transaction Key after upgrading the plugin to the latest version. It will now happen automatically during the activation process. 


= 2.67 =

* The transaction key file is now named after the Payment Page ID. This should enable this plugin to be used multi-site.
* Added the x_company field as one of the optional fields.
* Add x_description back to the optional fields. I don’t remember ever removing it, so it must have been by mistake. I made it a textarea field since Payeezy doesn’t seem to have any character restrictions. Most fields are caped at 30 characters. This field has no such restrictions. I’ve tested it with hundreds of words without any errors. If you want a field for the cardholder to write a book, this is the one you want to use.
* Removed unnecessary css from the admin screen.


= 2.66 =

* State and Country selectors now show "Select a State" or "Select a Country" instead of the first option.


= 2.65 =

* Changed the way the admin screen is styled.
* Added the state selector and country selector drop-down values in a separate text file.  

= 2.62 =

* Fixed a problem that allowed donors to enter a negative custom amount.

= 2.61 =

* Donors now not continue to First Data without first selecting an amount to donate.
* If for whatever reason the Transaction Key file is blank and needs to be generated, an error message will appear at the top of any Admin screen advising that the "Save Now" button in Payeezy settings needs to be pressed.


= 2.60 =

* Added the ability to change the text on the submit button.
* Direct link to the stylesheet in the plugin editor.
* Added a link to the WordPress plugin repository to make it easier to leave a 5-star (I hope!) review. 
* Added a warning message if the Transaction Key has not been saved. 

= 2.53 =

* Removed break at the end of every label.
* Added an external stylesheet (wp-payeezy-pay/stylesheet.css) for the form so it can be modified, customized, copied, or manipulated. 


= 2.52 =

* Added a break at the end of every label.


= 2.51 =

* Updated screenshots.


= 2.50 =

* Moved Currency Code to the top of Required settings.
* Now generates a message if Payment Page ID, Transaction Key, or Currency Code is not entered.
* Added a link for a new add-on plugin, Payeezy Transactions. 


= 2.45 = 

* Noticed I had placed Alabama twice in the dropdown. 
* Removed the link to WP Payeezy Transactions. I am not ready to make it live yet. 


= 2.40 = 

* Added the ability to hard-code the amount the card holder will pay. 
* Added Response Key so that WP Payeezy Pay will be compatible with WP Payeezy Transactions, a premium add-on.
* Updated the banner image and the icon image. The purple color was irritating me. 

= 2.36 = 

* Removed to breaks and a horizontal line that was causing an annoying space before the form. Thanks Colette!
* Updated the banner image and the icon image so it (hopefully) looks nicer in the plugin repository. 

= 2.35 =

* Fixed a problem with the currency code. 

= 2.31 =

* Fixed an image. 

= 2.30 = 

* Tested to make sure it is compatible with WordPress 4.4.
* Added Currency Code to the required settings. 
* Cleaned up the CSS to make sure everything looks pretty.

= 2.25 = 

* Minor changes involving support links. I also added WP dashicons. 

= 2.2 = 

* Now the cardholder making a donation cannot proceed to Payeezy without picking an amount.  

= 2.1 = 

* IMPORTANT! After upgrading, make sure you go into the plugin's settings and press the blue "Save Changes" button at the bottom of the page. I made a somewhat major change that strengthens the security of the plugin. The Transaction Key is no longer visible in the HTML form. It's now stored in a tiny file called key.php located in the plugin's directory.  If you don't press "Save Changes" it will not save the Transaction Key to this new file. I also included an internal style sheet for the form. Each field now has an ID that will make customizing it much easier. 

= 2.0 = 

* Combined this plugin with my other plugin, WP Payeezy Donate. All features found in that plugin are now rolled into this plugin. Going forward, this will be the only plugin updated, assuming updates are needed. If you select a Transaction Type option that supports Recurring and if you save the settings without entering a Recurring Billing ID, an error is displayed. If the mode is set to Demo, it now displays a notice. I also corrected a few typos and commented most of the code.  

= 1.2 = 

I had to remove the ability to do Recurring. Was causing an error if no Recurring Plan ID was entered in the settings. The ability to add Recurring will be added back in a future update. Stay tuned!

= 1.1 =

Fixed form fields so that they are required to be filled in by the cardholder. If you include a field in the form, the cardholder will not be allowed to proceed to Payeezy until they enter something.