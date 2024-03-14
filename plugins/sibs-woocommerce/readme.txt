=== SIBS woocommerce payment gateway ===
Contributors: comprassibs
Tags: payments , mbway , credit card, woocommerce, multibanco, multibanco reference, sibs, visa , mastercard, amex, payment gateway
Requires at least: 4.9
Tested up to: 5.5
Stable tag: 2.2.0
Requires PHP: 7.2 (with sodium)

Accept VISA, MasterCard, American Express, MULTIBANCO and MB WAY with a single payment gateway solution.

== Description ==

A ready-to-service e-commerce solution, with integrated checkout positioning and BackOffice for merchant management.  An interface for various national and international payment methods.

*Why use SIBS Digital Payments Gateway for WooCommerce?*

*	Unique platform for payment methods and brands relevant to your customers, with PCI DSS security certification.
*	Fraud prevention and detection in every channel and cards in the system.
*	Web responsive hosted SIBS payment page allowing you not to process sensitive data.
*	Allows the Acquirer choice for each payment method.
*	From the authenticated registration, SIBS keep the card sensitive data, allowing One Click Shopping experience.
*	Supports 3D Secure.
*	And more!



*Integrated Checkout*

SIBS Digital Payments Gateway Plugin for WooCommerce allows you to have an integrated checkout on your Web page, ensuring a good customer experience and better conversion rates.
Through COPYandPAY payment form direct integration in the shopping cart, you offer different payment methods to the customer in a safe way. 

*MULTIBANCO reference with real time configuration*

SIBS Digital Payments Gateway is the unique solution in the market that allows immediate MULTIBANCO reference generation with fixed amount and expiry date validation, allowing a minute-by-minute payment management, real time notifications with the payment result, no additional SW costs and the refund of a paid MULTIBANCO reference to the customer.

*Monitoring and reporting tools*

The BackOffice allows you to view an activity summary in the entry page with given modules and configurable indicators, consult the transaction list with real time state updates, verify the transaction detail, act on a transaction (amount capture, transaction canceling and amount refund), activity monitoring through configurable reports (by period, payment method, operation type, etc...) and export visualized data or aggregated reports.

*Better Fraud protection*

SIBS Digital Payments Gateway offers the various prevention service components and systemic fraud detection implemented in the MULTIBANCO network, by PAYWATCH. 

*Integrated with the domestic banking system*

Integrated with national issuers, processing and in the interbank clearing system.

* Warranty *

The plugin is provided “as is” without warranty of any kind, expressed or implied. We shall not be liable for any damages, including but not limited to, direct, indirect, special, incidental or consequential damages or losses that occur out of the use or inability to use our products or support.
Before general release, the plugins are tested and guaranteed to function on basic installation of Wordpress and regular Woocommerce. We do not, and cannot, assure that that it will function with all third-party plugins or in all web browsers, therefore, we cannot be held responsible for any conflicts that may occur in your installation. 
It is our policy to support our plugins as best we can and we will provide support for third-party plugin conflicts at our discretion or as time allows. 
We are not responsible for any data loss or other damages that may occur as a result of installing our plugins.

* Acceptance *

By using the plugin you indicate that you have read and agreed and understood the terms above.
We reserve the right to change or modify the current terms.



== Installation ==

In order to have the solution ready to use:
1.	Separately from the plugin installation you will need an agreement with the available Financial Institutions:
*	Caixa Geral de Depósitos
*	Millennium BCP
*	Santander
*	Banco BPI
*	Caixa Crédito Agrícola
*	SIBS Pagamentos
*	Others 
2.	Once the agreement is signed with the chosen Financial Institution, you can request your credentials to start accepting payments.
3.	Please contact SIBS to proceed:
*	Email - pagamentos@sibs.pt, paymentservices@sibs.pt
*	Phone - 217918703


== Frequently Asked Questions ==

= Where can I find documentation? =

For help setting up and configuring, please refer to our user guide.

= How long does it take to start accepting payments? =

After the agreement is signed and the plugin is run to integrate the solution, enter the credentials to start accepting payments.

= What currencies can I use? =

Currently, only Euros.

= How long does it take the money to get to my account? =

The money will be deposited directly in your bank account (there are no wallets in the middle of the process) accordingly to the Bank clearing and settlement cycles.

= How can I configure the Access Token? =

* Go to BIP portal
* Navigate to Account data -> Channel Info -> ACCESS TOKEN
* Get token
* Go to wp-admin page
* Navigate to WooCommerce -> Settings -> Payments
* Update all available payment methods (SIBS Cards, SIBS MULTIBANCO or SIBS MB WAY) with the retrieved token

= How can I configure cronjob to expire 'On Hold' orders?

* Install a cron job plugin (like wp-control)
* Set Hook name -> sibs_cancel_onhold_orders
* Set recurrence has you like


== Screenshots ==

1. Configuration Menu
2. Form for card payment
3. Result for Card payment
4. Form for MBWay payment
5. Result for MBWay payment
6. Result for Service payments


/assets/config.jpg
/assets/cardConf.jpg
/assets/card.jpg
/assets/mbwayConf.jpg
/assets/mbway.jpg
/assets/refConf.jpg

== Changelog ==

= 1.0.23 =
* The first stable version.


= 1.0.24 =
* Changed readme to annouce plugin parametrization change
* Webhook status corrections.
* Allow multibanco reference from customers outside Portugal


= 2.0.1 =
* Changed to DPG api authentication method 
	1. Go to BIP portal
	2. Navigate to Account data -> Channel Info -> ACCESS TOKEN
	3. Get token
	4. Go to wp-admin page
	5. Navigate to WooCommerce -> Settings -> Payments
	6. Update all available payment methods (SIBS Cards, SIBS MULTIBANCO or SIBS MB WAY) with the retrieved token  
* Change application status flow to support WooCommerce correct behaviour
* New backoffice parameter where it's possible to decide if the state after the payment is completed is 'completed' or 'processing' (default value is 'processing')
* Fix stock update
* New cron job was created to cancel orders 'On Hold' for to much time


= 2.1.0 =
* Add description texts to all payment methods. They can be changed on BackOffice.
* Improvements on MULTIBANCO payments.
* Remove unnecessary parameter on BackOffice.

= 2.2.0 =
* Update MULTIBANCO logo
* Correction on MULTIBANCO payment states.
* Improve emails design.
* Corretion to avoid compatibility problems with Wordpress libs.

== Upgrade Notice ==