=== Fattura24 ===

Contributors:      Fattura24
Plugin Name:       WooCommerce Fattura24
Plugin URI:        http://www.fattura24.com
Tags:              fattura elettronica, fatturazione, fatture, codice fiscale, partita iva
Author URI:        http://www.fattura24.com
Author:            Fattura24.com
Requires PHP:      5.6
Requires at least: 4.6
Tested up to:      6.4
Stable tag:        7.1.1
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html


The official **Fattura24 plugin** allows **the creation of electronic invoices, orders, traditional invoices and receipts** via [Fattura24](https://www.fattura24.com)

== Description ==

The official **Fattura24 plugin** allows **the creation of electronic invoices, orders, traditional invoices and receipts** via [Fattura24](https://www.fattura24.com)
You can also analyze the progress of your business by graphic reports and share everything with your accountant.
You can hook one or more eCommerce to your Fattura24 account and on each one create documents with different issue counters.

By the plugin you can:

* Add the PEC and SDI Recipient Code fields on the check-out page;
* Add the fields "Tax code" and "VAT number" of the customer on the check-out page and decide if the fields have to be required;
* Create a copy of the order from WooCommerce to Fattura24;
* Send an automatic email to the customer with a PDF copy of the order or invoice attached;
* Add the customer information to your Fattura24 address book or update the data if they have already been created;
* Create the receipt/invoice - in your Fattura24 account - relating to the order and download it to your e-commerce so that it can be displayed both by the administrator and the customer. The system will create an invoice if the customer has filled out the VAT number field, otherwise a receipt, or you can choose to create the invoice always;
* Load stocks in Fattura24, pledge the goods with orders and unload them with invoices;
* Choose the template to create PDF copies of orders and receipts / electronic invoices / invoices;
* Associate the current-account balance for each invoice and analyze the details through the Fattura24 graphic reports;
* Set a custom issue counter for the invoices.

== Installation ==

**You may see our video guides (spoken in italian):**
   
   In this lesson we will explain how to install and activate Fattura24 plugin for WooCommerce and we will setup a basic configuration.
   

 [youtube https://youtu.be/svsJbyVNQmk]
   
   In this lesson we will explain: how to enable tax calculation in WooCommerce, how to add VAT rates in the 'Standard' tax class, how to add a zero rate, how to assign a Natura IVA code in Fattura24->Tax configuration and issue proper Electronic Invoices.


 [youtube https://youtu.be/iTDYvCVyG1Q]

   In this video we will emulate a customer purchase in our shop, focusing on some WooCommerce fields and on fields added by Fattura24. At last we will check the order status and the documents created in Fattura24.
   

 [youtube https://youtu.be/1UEjxzoWw2s]

**Otherwise read this guide:**
 
#### Preparing
To use Fattura24 plugin as a billing service in WooCommerce you need:<br/>

   * "Business" subscription on Fattura24
   * API KEY (you can find it on your Fattura24 account)

to get the API Key go to ‘Configurazione’ -> ’App Servizi Esterni’, click on "SI" to enable and copy the key.

#### Configuration
Now let's see how to configure the Fattura24 plugin in Wordpress admin area:
   
   * go to ‘Plugins’ -> ’Add new’
   * use the search toolbar to quickly find "Fattura24"
   * then click on "Install Now"
   * fill out the API Key field
   * then click on ‘Save settings’
   * click on ‘Verifica API KEY’ to make sure you've entered the correct API KEY

#### Verification
Your configuration is done, let's proceed with some tests.
Whenever you receive an order from your check-out page, the plugin will do the following:

   * if you checked 'Save customer', it will add the customer to Fattura24 address book or update its data;
   * if you checked 'Create order', it will send the order to Fattura24;
   * if you checked 'Send email', an email will be sent to the customer with the order.

When you set the status of the order on 'Processing' or ‘Completed’, the plugin will do the following:

   * if you selected to Create an invoice, it will create a receipt in Fattura24 if the customer doesn't have a VAT number, otherwise it will create an invoice;
   * it will add the customer to your address book or update the data;
   * it will create the PDF receipt / invoice 
   * if you checked 'Download PDF' it download a copy of the PDF on your e-commerce;
   * if you checked 'Send email', an email will be sent to the customer with the receipt / invoice PDF;
   * the Invoice will be created (or not) in the 'paid' status according to the option you chose;
   * if you checked 'Disable receipts', an invoice will be created instead of a receipt even in the absence of the VAT number.


The plugin also adds the fields "Tax Code" and "VAT number" to the customer data. You can choose whether or not the customer is required to fill these fields.

Using Fattura24 with WooCommerce is very simple but if you would like technical assistance contact us at +39 06-40402261.

== Changelog ==

= 7.1.1 =
* Correzione stile campi checkout

= 7.1.0 =
* Aggiunta opzione per il Pdc linea commissioni
* Riordino delle opzioni nella schermata delle impostazioni

= 7.0.0 =
* Riscrittura codice per compatibilità HPOS
* Aggiornamento hooks, metodi e filtri lista ordini
* Miglioramento gestione coupon percentuali
* Modifica controllo sconti a importo fisso

= 6.2.9 =
* Compatibilità WP 6.4 e WC 8.2.1

== Frequently Asked Questions ==

= Is the plugin free? =

To create PDFs and electronic invoices you need a Business or Enterprise subscription on Fattura24.
More information on Fattura24 prices are available [here](https://www.fattura24.com/prezzi-versioni-fattura24/)

= Do you have a customer service? =

Yes, the customer support service is available for all the subscription plans, it is provided by Italy with Italian personnel.
Contact us +39 06-40402261

= How can get a trial period of Fattura24? =

Sign to Fattura24 for free, then mail to assistenza@fattura24.com and ask for a trial period of Business plan. Otherwise use 'Support' section of the plugin and write us by filling the form there.
Do you need more time? Call us and we will extend the duration of your trial period.

= Is it possible to issue invoices containing withholding tax or social security contribution ? =

No, unfortunately WooCommerce doesn't handle this data in the order and Fattura24 may show in the invoice only data contained in the order.

== Screenshots ==

1. Click on: Configurazione -> App e Servizi esterni
2. Then click on WooCommerce icon
3. Copy your API KEY
4. Paste API KEY and save the settings, then verify the key
5. Set the plugin to create Electronic Invoices (example)
6. Remember to specify Natura for 0% rates by following these instructions
7. To create the document, put the order to the status selected in settings page (e.g.: "Completed")
8. Columns to check document status in Fattura24
