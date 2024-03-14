=== Payment Gateway for MTN MoMo on WooCommerce ===
Contributors: dennokip
Plugin Name: Payment Gateway for MTN MoMo on WooCommerce
Tags: mobile payments, momo, mtn, collection, payment gateway,e-commerce,woocommerce, mobile, MTN, shop, online
Author: Demkitech Solutions
Requires at least: 2.2
Tested up to: 6.2.2
Stable tag: 1.0
License: GNU General Public License v2.0
License URI: http://www.gnu.org/licenses/gpl-2.0.html
Donate link: #

== Description ==

MTN MoMo(Mobile Money) is a payment platform that enables customers to pay for goods and services using their mobile phones.
MTN MoMo is now available in these countries: *Uganda, Ghana, Cameroon, Zambia, Swaziland, Rwanda, Ivory Coast, Benin, Guinea, South Africa and Liberia.*
This plugin will work in all the countries where MTN MoMo is available. 
The plugin enables the customer to have an option of paying for goods or services using MTN MoMo Collection API*(Not Collection Widget)* from a Wordpress site with WooCommerce plugin installed. 
The plugin is a Woocommerce Payment Gateway which adds an option on the checkout section for paying through MTN MoMo(A mobile payment platform). 

= PLUGIN SETUP FOR MTN MOMO SANDBOX =
* Testing of the plugin is possible by creating an account in the [MoMo API portal](https://momodeveloper.mtn.com/).
* For Rwanda sign up to [momodeveloper.mtn.co.rw](https://momodeveloper.mtn.co.rw) and get the keys.
* After account creation, log in to the account and subscribe to the Collection product in order to get the Primary Key, Secondary Key also known as Ocp-Apim-Subscription-Key.
* When logged in, you can be able to see the Primary Key and Secondary Key after clicking on your profile.
* Use the Primary Key/Secondary Key referred to as Ocp-Apim-Subscription-Key header in a sandbox API to generate the API User and API Key.
* Generate the API User(Use online UUID generator to come up with a UUID value to use in the Sandbox API for generating a user)
* Generate the API Key the reference ID(UUID) in the previous request for generating API user
* Get more information on the API Key and API User generation [HERE](https://momodeveloper.mtn.com/api-documentation/api-description/)
* Access the plugin settings here(WooCommerce :arrow: Settings :arrow: Payments :arrow: MoMo - MTN MoMo)
* Fill in the API Key, API User and Ocp-Apim-Subscription-Key(Primary Key/Secondary Key) in the plugin settings, save and start testing.
* The Endpoints for Sandbox and currency are already filled by default when you install the plugin.

**Note these items when testing in Sandbox:**
1. No USSD Push request on your phone, therefore no deductions will be made to your account when testing in Sandbox.
2. These test phone numbers are used to test error conditions: 46733123450, 46733123451, 46733123452, 46733123453.
3. **All other numbers will act like the payment was successful immediately without any USSD Push.**

= PLUGIN SETUP FOR MTN MOMO PRODUCTION =
* To receive payments, one must [GO LIVE](https://momodeveloper.mtn.com/go-live) by submitting your KYC documents when logged in to MTN Developer Portal.
* For Rwanda use [https://momodeveloper.mtn.co.rw/go-live](https://momodeveloper.mtn.co.rw/go-live) to Go Live and then get the Primary Key here [https://momoapi.mtn.co.rw](https://momoapi.mtn.co.rw)
* Wait for MTN to process your application and send you access to the Partner Portal and API management dashboard.
* Login to [momoapi.mtn.com](https://momoapi.mtn.com/) and get your Live Collection Primary key(Ocp-Apim-Subscription-Key)
* Login to the MTN Partner Portal and generate API user and API key.
* Purchase the Pro Version of the plugin [HERE](https://woomtnmomo.demkitech.com/) and download it from the link that will be sent to your email account.
* Install the plugin by uploading the zipped file containing the plugin and activate it.
* Enter Collection Primary key(Ocp-Apim-Subscription-Key), API User and API Key in the Plugin settings located here(WooCommerce :arrow: Settings :arrow: Payments :arrow: MoMo - MTN MoMo)
* Confirm the Payment Endpoint and Credential Endpoints are not for Sandbox.
* Fill in the currency depending on the country you have gone live in, there are the currencies available:
	* EUR (Sandbox)
	* GHS Ghana
	* UGX Uganda
	* XAF Cameroon
	* RWF Rwanda
	* XOF Benin
	* XOF Ivory Coast
	* XAF Congo Brazza
	* SZL Eswatini/Swaziland
	* GNF Guinea
	* ZMW Zambia
	* ZAR South Africa
	* LRD Liberia

* Save the details and start reveiving payments.
We already have some customers who have gone live with the plugin and one of them is [Jelele.com](https://jelele.com/) who are using the plugin to sell digital products.

= PLUGIN CUSTOMER JOURNEY =
* When the customer clicks on the Pay button on the payment page, the plugin will initiate a payment authorization request to the customer. 
* The customer will then accept or decline the payment from the personal mobile phone and the payment can be confirmed by clicking the Complete Order button. 
* The order status in the online shop is then changed depending on the customer's action(In the Pro Version).
* This  **free version** of the plugin does not change order status and does not have the functionality of checking the transaction status of the payments but the Pro Version does.
* The main purpose of the **free version** is to test the functionality of how your website will work and it's compatibility with your website before deciding to use it in production.
* Please check the  **DEMO** of the Pro Version of the plugin [HERE](https://demowoomomo.demkitech.com/)
* To be able to get the **Pro Version** please purchase the plugin on our website [HERE](https://woomtnmomo.demkitech.com/) and a download link will be sent to the email address filled in the checkout form.

#### How to use: ####
1.	Make sure you have installed and activated WooCommerce plugin before installing and activating this plugin.
2.	Upload Payment Gateway for MTN MoMo on WooCommerce plugin files to the wordpress plugins directory (/wp-content/plugins/), or install the plugin from the Wordpress admin plugin screen.
3.	Activate the plugin.
4.	On the Wordpress admin, navigate to WooCommerce :arrow: Settings :arrow: Payments :arrow: MoMo - MTN MoMo Manage and fill in the fields provided in order for the plugin to work.


#### Demo Video####
This is the demo video for the Pro Version of the plugin:

https://youtu.be/kpw9-tMHrcg

= HOW TO GET SUPPORT =

* Send us an Email to info@demkitech.com
* Contact us via WhatsApp(+254790120923)
* Submit your question to the plugin support section.  

== Installation ==
1. Log in to your wordpress administrator and go to Plugins then Add New.
2. Upload the plugin zipped file and activate it or install it from the Wordpress Plugins Directory.
3. Activate the plugin.
4. Update the settings.

== Upgrade Notice ==
This is the first version.

== Disclaimer ==
This plugin does not have any relation with WooCommerce or MTN. The plugin’s purpose is just to help in linking the WooCommerce plugin with the MTN MoMo payment method.
 In the plugin description there is links to other websites which are not under the control of Payment Gateway for MTN MoMo on WooCommerce plugin. We have no control over
 the nature,content and availability of those sites. 
 The inclusion of any links does not necessarily imply a recommendation or endorse the views expressed within them.

== Changelog ==
= 1.0 =
== Frequently Asked Questions ==
=How does the customer authenticate the payment?
The customer receives a push notification to authorize the payment.
MTN Cameroon journey is different, an SMS is sent with the steps to follow to make the payment.

= How can I get the Pro Version
The Pro Version is purchased here [https://woomtnmomo.demkitech.com/](https://woomtnmomo.demkitech.com/) and a download link will be sent to your email.
The price is USD $50 one time payment for one domain/website.


