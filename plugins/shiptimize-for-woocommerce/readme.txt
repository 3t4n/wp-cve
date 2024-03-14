=== Shiptimize for WooCommerce ===
Contributors: Shiptimize
Tags: shipping, multi carrier, save, automate, woocommerce
Requires at least: 4.9
Tested up to: 6.3
Requires PHP: 5.6
Stable tag: trunk 
Author URI: https://www.shiptimize.me/
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Shiptimize for WooCommerce is a Digital Delivery Management Solution for online stores that helps you save time and money with your shipping. 

== Description ==
*The multi-carrier shipping software that helps you save time and money!*

Are you searching for a user-friendly shipping tool that offers extensive delivery options for letters, parcels and pallets* plus close and personal advice? Then, Shiptimize/PakketMail is the way to go!
 
*Get advice, connect, save and grow!*
Based on your shipping related necessities and demands, we advise which shipping carriers are the best fit for your online store.
 
After connecting your WooCommerce store, and other potential sales channels, to Shiptimize/PakketMail you start saving time by automating your shipping process, increasing your conversion by offering multi-carrier and shipping options in your checkout, and saving on shipping costs thanks to our pre-negotiated high-volume shipping contracts.
 
And if something goes wrong with a shipment, count on us! We’re ready to handle all status inquiries, potential claims and necessary communication with the carriers in your behalf.
 
This way, you can stay focused on growing your business!
 
*Pallets deliveries only available in The Netherlands at the moment.

*Increase conversion and customer satisfaction*
 
* Easily connect multiple shipping carriers to your online store in order to offer your customers the freedom to choose, during checkout, among some of the most reliable shipping companies: DHL, PostNL, UPS, GLS, DPD, B2C Europe, CTT Correios, VASP, Chronopost, Correos;
* Let your customers choose between home delivery or delivery at a service point;
* Keep your customers up to date with automated track&trace emails, in your customer’s language and with the visual identity of your online store.
 
* Save time when processing your orders*
 
* Export all required orders with a single click to your Shiptimize/PakketMail account and click one more time to generate all your shipping labels at once;
* Automatically update tracking IDs to WooCommerce;
* Update order statuses in WooCommerce. Yes, automatically!
 
*Save on shipping costs*
 
* Use our pre-negotiated high-volume shipping contracts to save money on your shipments
* Or use your own shipping contract and connect to Shiptimize/PakketMail platform. 

= Screenshots =
- Enjoy the freedom to choose from multiple carriers the ones that best fit your needs
- Click once to export all shipments. Click again to generate shipping labels. 
- Save money by using our high-volume pre-negociated shipping rates. 
- Offer your customer the cance to choose the most suitable pickup location on a map. 
- Keep your customers on the loop by automating tracking e-mails, customized to your brand. 

== Installation ==
1. Download and install the plugin from WordPress dashboard. You can also upload the entire “Shiptimize for Woocommerce” folder to the `/wp-content/plugins/` directory
1. Activate the plugin through the ‘Plugins’ menu in WordPress
1. Go to Settings > Shiptimize Settings and insert your keys to get started. 

== Changelog ==
= 3.1.82 - 2024-02-29
* fix - Close map on point selection
= 3.1.80 - 2024-02-08
* Enhancement - Upgrade the service point map functionality to have a search option, supports ability to scroll on the map and display the closest pickup points to the scrolled spot.
= 3.1.78 - 2023-12-07 =
* fix - version update

= 3.1.77 - 2023-12-04 =
* fix - Add forgotten HPOS compatability action

= 3.1.76 - 2023-12-04 =
* Enhancement - Check if HPOS is enabled in for actions and filters, use HPOS friendly actions if it’s enabled.
* Enhancement - Remove any direct usage of the posts table and change it with the orders table

= 3.1.75 - 2023-10-27 =
* Enhancement - frontend recompile because of old functionality removal

= 3.1.74 - 2023-10-17 =
* Enhancement - Language files update

= 3.1.73 - 2023-10-12 =
* BugFix - Change getInstance method calling for carriers

= 3.1.72 - 2023-09-27 =
* BugFix - Check if plugins array key is set before using it
* BugFix - Fix the call of is_virtual on empty product
* BugFix - shippingMethods typo fix

= 3.1.71 - 2023-06-23 =
*  BugFix - Collection option not recognized when exporting. 

= 3.1.70 - 2023-05-15 = 
*  BugFix - Issue in exporting orders incorrectly assigning a point selection. 

= 3.1.69 - 2023-05-15 = 
* Enhancement  - Enable multi order label print when allowed for client

= 3.1.68 - 2023-04-20 = 
* BugFix - Issue in displaying the selected sub option of extra options in carrier settings. 
* Enhancement - Collect extra info such as packstation id 
* Enhancement - Warn that multiple orders in the same label pdf are only possible via the app. 

= 3.1.67 - 2023-03-09 = 
* Enhancement - Allow users to map Flexible Checkout Fields to Address Fields. Mapped fields will be sent to the API when order is exported. 

= 3.1.66 - 2023-01-13 = 
* Enhancement - Do not export orders set to be picked up at the shop (Local Pickup)

= 3.1.65 - 2023-01-12 = 
* Enhancement - By default, do not export orders that contain only virtual products, do not include virtual products when exporting. 

= 3.1.64 - 2022-12-07 = 
* Fix - Error when creating labels from websites that use custom order numbers 

= 3.1.63 - 2022-11-16 = 
* Fix - Error with Weight Based Shipping methods 

= 3.1.62 - 2022-11-16 = 
* Fix - Issue with re-exporting a shipment that was exported and then deleted in the app.

= 3.1.61 - 2022-11-01 = 
* Fix - compatibility with "advanced trable rates" restaured.  

= 3.1.60 - 2022-11-01 = 
* Fix - Some shops seem to load shipping methods before the plugin is loaded, triggering an error in trying to use a method in the plugin. We've written a fallback

= 3.1.59 - 2022-11-01 = 
* Fix - when sending item weight to the api, send the individual item weight not qty * item_weight

= 3.1.58 - 2022-10-26 = 
* Enhancement - When hiding all shipping methods not free, make sure only activate feature if the at least one free shipping is not "local pickup".

= 3.1.57 - 2022-10-18 = 
* fix - When selecting the shipping method the value of shipping was not added to total. 

= 3.1.56 - 2022-10-18 = 
* fix - If hide shipping methods was enabled and only rates that can't display because of class constraints match, then no rates would not display. 

= 3.1.55 - 2022-10-17 = 
* enhancement - If plugin Advanced Shipping is installed and active allow sellers to specify certain classes where our shipping methods will not display at checkout. 

= 3.1.53 - 2022-09-07 = 
* fix - Item weight should be the weight of the item, not the total of the quantity indicated 

= 3.1.52 - 2022-08-26 = 
* fix - Total weight did not account for quantity. 

= 3.1.51 - 2022-08-19 = 
* enhancement - Always round weights down, some shops will define weights with decimals even in grams. The smaller unit of weight which the API accepts is grams. 

= 3.1.50 - 2022-08-19 = 
* enhancement -  Store tracking id that comes with the label response, that way if the shop update from app fails because of a broken route, it's already there. 

= 3.1.49 - 2022-07-16 = 
* enhancement -  Support for Sequential Order Numbers Pro 

= 3.1.48 - 2022-07-16 = 
* fix - service level label not shown for weight based shipping 
* fix - if no option is selected don't send the option at all 

= 3.1.47 - 20222-06-22 = 
* fix - openmap make sure we parse lat,lng coords as floats regardless of the format we receive them in.
* enhancement - print the button before payment instead of after shipment because some themes will break with it

= 3.1.45 - 20212-05-17 = 
* enhancement - Make receiving updates via the API default behaviour 

= 3.1.44 - 20212-05-12 = 
* enhancement - Receive updates using the WP API 

= 3.1.43 - 20212-05-12 = 
* enhancement - Bulk label printing from woocommerce 
* fix - Also update the status to label printed when we receive a tracking id from the API 

= 3.1.42 - 20212-04-20 = 
* enhancement - Option to hide other shipping methods when free shipping is available 

= 3.1.41 - 20212-04-13 = 
* enhancement - allow users to declare Free shipping With one of our carriers 

= 3.1.40 - 20212-03-28 =  
* enhancement - don't declare a label with the hidden field to store the point id, as woo will still display it 

= 3.1.39 - 20212-03-28 =  
* enhancement - On some websites plugin_dir_url will return http when serving the website via https, always check if ssl and append the https there  
* enhancement - Also send the shipping method name so it can be used in the rulebook 

= 3.1.38 - 20212-03-28 =  
* enhancement - If the translation does not exist in the current language serve the english translation

= 3.1.37 - 20212-03-23 =  
* enhancement - obfuscate keys 

= 3.1.36 - 20212-03-08 =  
* fix - If there is a trailing / in the homeurl, then remove it 

= 3.1.35 - 20212-02-21 =  
* enhancement - Copy changes  

= 3.1.34 - 20212-02-09 =  
* enhancement - print label directly from the woocommerce, allow shop admins to choose a service point behaviour (optional, mandatory, not available)

= 3.1.33 - 2021-11-10 = 
* fix - issue with custom themes that don't use checkboxes to display the selected shipping method
* enhancement - add a loading stage to the pickup point retrieval 

= 3.1.32 - 2021-11-02 = 
* fix - issue with exported icons not showing 

= 3.1.31 - 2021-09-27 = 
* enhancement - Add "return label" option when available for the carrier & client 

= 3.1.30 - 2021-09-20 = 
* enhancement - Better error handling when there's an error in our API. Still display no point to the client, log the error to the console.  

= 3.1.29 - 2021-08-23 = 
* enhancement - only send state to the API if we can get an iso code

= 3.1.28 - 2021-08-06 = 
* enhancement - make "activate pickup" option available in shipping methods 

= 3.1.27 - 2021-07-14 = 
* enhancement - tested with wordpress 5.8
* enhancement COD - if the payment method is COD, check if the assigned carrier supports COD, then add COD option with order value

= 3.1.26 - 2021-06-13 = 
* fix - Error when carrier name contains breaking characters in the filename 

= 3.1.25 - 2021-06-13 = 
* enhancement -  don't die when we can't write the shipping method class, just log the error. 

= 3.1.24 - 2021-05-13 = 
* enhancement -  add necessary changes to pull shipping method list to be used in the rulebook

= 3.1.23 - 2021-05-04 = 
* enhancement -  It's now possible to set the following options "delivery window", "fragile", "delivery attempts"

= 3.1.22 - 2021-04-01 = 
* fix -  dokan shiptimize settings not showing up for vendor 

= 3.1.21 - 2021-04-01 = 
* fix -  conflict in declaring states for Portugal 

= 3.1.20 - 2021-04-01 = 
* enhancement -  add dhl 2c options 

= 3.1.19 - 2021-02-21 = 
* fix -  Deprecate customs description in favor of item list. 

= 3.1.18 - 2021-02-21 = 
* fix -  compatibility with custom wp structures such as those used by bedrock

= 3.1.17 - 2021-02-21 = 
* fix - compatibility regions 
* enhancement - some pickup points now have a specific type and a different icon

= 3.1.16 - 2021-02-12 = 
* Enhancement - Make declaring provinces for Portugal optional and available only for marketplace integrations 

= 3.1.15 - 2021-01-26 = 
* Enhancement - Changed Format by which we append the tracking id to the order notes to TRACKING<trackingid>

= 3.1.14 - 2021-01-20 = 
* Enhancement - Added compatibility with Wordpress network "Network Activate". Installs the plugins on all sites in the network.

= 3.1.13 - 2020-12-23 = 
* Fix - make sure all includes are done with absolute paths, seems to be an issue with some shared hostings. 

= 3.1.12 - 2020-12-23 = 
* Enhancement - resurrect basic integration with dokan 
* Enhancement - added saturday delivery to allowed options 
* Enhancement - tracking updates now are added to order notes so they can be picked up by other third party integrations like Channable

= 3.1.11 - 2020-12-21 = 
* Enhancement - WCFM Master account - only admin user can edit vendor connections, add link to export from order details , admin can submit form to request new vendor account , list all status as possible autoexport status , for master account skip the has account form and just connect the account

= 3.1.10 - 2020-12-12 = 
* fix - language install fails when done via wp client command line 

= 3.1.9 - 2020-10-23 = 
* Fix - Always check if the shiptimize routes are present with each request, add them if not 
* Enhancement - change the way we espace characters to trash anything not in our list of diacriticals that is larger than 2 bytes 

= 3.1.8 - 2020-10-23 = 
* Fix - Conflict with WPML derived from the way we declare the route used to receive api updates

= 3.1.7 - 2020-10-23 = 
* Enhancement - Added 3 zones PT Continente, PT Ilhas and ES Canarias 

= 3.1.6 - 2020-10-23 = 
* fix - Use of deprecated function to split string 

= 3.1.5 - 2020-10-23 = 
* fix - Change the way we detect the marketplace in the order 

= 3.1.4 - 2020-10-22 = 
* Enhancement - Compatibility with wcfm from WCLovers
* Enhancement - Style changes to improve compatibility with custom themes 

= 3.1.3 - 2020-06-19 = 
* fix  - Change the way we detect if it's a dev machine. 

= 3.1.2 - 2020-05-07 = 
* fix  - If clients repeat the city name in the postal code, ignore it. 

= 3.1.1 - 2020-04-29 = 
* fix  - incorrect dutch translation in options page

= 3.1.0 - 2020-04-27 = 
* Enhancement  - Add options for servicelevel, cash service, send insured when possible. 
* Enhancement - Do not html encode anything in the ascii space

= 3.0.27 - 2020-04-07 = 
* Enhancement  - Clean invalid; <3 characters; companyNames 

= 3.0.26 - 2020-03-31 = 
* Enhancement  - Automatic export available 
* Enhancement  - Support for Dokan plugin

= 3.0.25 - 2019-02-14 =
* Fix - scripts on admin not loading with checkout disabled 

= 3.0.24 - 2019-02-12 =
* Enhancement - If the don't include on checkout is enabled don't include the scripts in the website.

= 3.0.23 - 2019-02-11 =
* Enhancement - Be more verbose about shipping address errors, display the associated metadata in the error message

= 3.0.22 - 2019-02-11 =
* Enhancement - If shipping country is not set, get it from the billing address. Some shops ship only to one country and don't set this field.  

= 3.0.21 - 2019-01-20 =
* Enhancement - Allow users to choose not to display pickup options at checkout 
* Enhancement - If there is not Tekst in the error, then print the object to the message 

= 3.0.20 - 2019-12-09 =
* Fix - on latter versions of Woo clicking any button on checkout will submit the order form, we should prevent default on click on  "choose pickup locations".

= 3.0.19 - 2019-11-19 =
* Enhancement - If the user did not configure the plugin correctly add a warning on export

= 3.0.18 - 2019-10-24 =
* Enhancement - Allow users in Brazil to configure CPF/CNPJ and neighborhood fields so we can send them to the API

= 3.0.17- 2019-10-24 =
* Fix - replace all non latin1 character by their ascii equivalents 
* Fix - Trim emails before sending them to API 

= 3.0.15- 2019-10-10 =
* Fix - For orders with many items, the customs description was not being sent. 
* Fix - Do not presume units of weight  

= 3.0.15- 2019-10-10 =
* Fix - On some systems woo does not return a list of items for an order. If we can't get it, then don't send Customs information and issue a warning. 

= 3.0.14- 2019-10-10 =
* Fix - Not all items in an order are products

= 3.0.13 - 2019-10-01 =
* Enhancement - send special chars html encoded to avoid charset conflicts 
* Enhancement - avoid conflicts with other plugins that print to fragments by pushing our filter down the line

= 3.0.12 - 2019-09-29 =
* Enhancement - Send list of items with the shipment for CN22 forms. 

= 3.0.11 - 2019-09-29 =
* Enhancement - on upgrade request a new token, thus requesting the carriers associated with the contract and regenerating all shipping method classes to grant the service levels are present if available. 

= 3.0.10 - 2019-09-29 = 
* Enhancement - If the carrier has service levels (such as express and standard), then allow the seller user to assign a particular service level to each instance of the associated "Shipping Method" 

= 3.0.9 - 2019-09-23 = 
* Fix - incorrect weight for orders with items with quantities larger than 1 

= 3.0.8 - 2019-09-5 = 
* Fix - When the state code was a single char, orders where not exported, we are now sending the state name instead. 

= 3.0.7 - 2019-08-2 = 
* Fix - If there where global rules in wbs it would show one instance for each shiptimize method.

= 3.0.6 - 2019-06-11 = 
* Enhancement - If weightbasedshipping is active declare a weightbased shipping method
* Tweak - Changed the way we do translation so wordpress won't confuse our function with their translate function 

= 3.0.5 - 2019-04-18 =
* Tweak - If we can't get a list of products for an order then exclude the customs object from the shipment 
* Tweak - If a Client inserts a company name smaller than 2 chars ex: "-" , then we ignore it when sending the info to the API

= 3.0.3 - 2019-04-18 =
* Enhancement - It's now possible to request an account from the settings page 
* Tweak - Declare supported languages 

