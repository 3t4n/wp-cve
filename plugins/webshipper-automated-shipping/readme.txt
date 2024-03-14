=== Webshipper - Automated Shipping ===
Plugin Name: Webshipper - Automated Shipping
Plugin URI: https://www.webshipper.com
Contributors: Webshipper
Tags: shipping, valgfrit afhentningssted, automated shipping, post danmark, postdk, bluewater,blue water,woocommerce, automated shipping, dropshipping, webshipr, webshipper, GLS, Bring, DHL, DHL Express, DAO, DHL Parcel
Stable tag: 1.5.7
Requires at least: 3.7
Tested up to: 6.4
WC requires at least: 3.4.0
WC tested up to: 7.1.0
License: MIT
License URI: https://opensource.org/licenses/MIT


Automated shipping for WooCommerce.

== Description ==

NOTICE: This plugin is compatible only with Webshipper version 2. For the old platform use the plugin "Webshipr - automated shipping"

Webshipper automates the shipping flow in your WooCommerce webshop. In one click your shipments are sent directly to the shipper, a label is generated, and
your tracking informations are available directly from the woo-commerce backend.

= Key Features =
* Automate shipping
* Integrate with PostNord DK/SE/NO
* Integrate with Blue Water shipping
* Integrate with Warehouse partners
* Integrate with GLS Shipping
* Integrate with GLS Pakkeshop
* Integrate with Swipbox
* Integrate with DHL Express
* Integrate with DHL Parcel
* Integrate with DHL Freight
* Integrate with DAO
* Integrate with CoolRunner

And many more! https://webshipper.dk/fragtleverandoer/

-------------------------

== Installation ==
* Go to Plugins > “Add New”.
* Download the Webshipper plugin from Wordpress repository and Click "Install Now" to install the Plugin. A popup
   window will ask you to confirm your wish to install the Plugin.
= Note: = If this is the first time you've installed a WordPress Plugin, you may need to enter the FTP login credential information. If
          you've installed a Plugin before, it will still have the login information. This information is available through your web server host.

* Click “Proceed” to continue the installation. The resulting installation screen will list the installation as successful or note any problems during the install.
* If successful, click "Activate Plugin" to activate it, or “Return to Plugin Installer” for further actions.
* Go to WooCommerce => Settings => Shipping => Shipping Options
* Insert your configuration key from your Webshipper account.
* You are ready to go!


== Brief Version History ==
* 1.0.0: New plugin released for platform version 2
* 1.0.1 Added Transient Caching for improved performance
* 1.0.2 Added support for multi-currency shops that use WPML
* 1.0.3 Minor fixes during plugin development
* 1.0.4 Minor fixes during plugin development
* 1.0.5 Fixed pickup selector always appearing
* 1.1.0 First public release
* 1.1.1 Readme corrections
* 1.1.2 Minor responsiveness corrections for google maps modal
* 1.1.3 Fixes errors when using gift card plugin
* 1.1.4 Fixed issue in some checkouts, where droppoints could not be located properly
* 1.2.0 Added option to save droppoint in shipping address
* 1.2.1 Added city to the shipping rate quote, to support dynmic price quoting with DHL for some countries
* 1.3.0 Fixed language issues when installing plugin from store. Minor responsiveness fixes for droppoint modal in checkout
* 1.3.1 Fixed issue where removed files werent removed correctly
* 1.3.2 Added message when no droppoints could be found and allowed overwriting of droppoint modal
* 1.3.3 Fixed issue with shipping rates returned as strings
* 1.3.4 Changed action name to be more webshipper specific
* 1.3.5 Added option to run imports async and option to disable recalculating cart totals
* 1.3.6 Added weight unit to rate quote request to properly get shipping rates based on weight
* 1.3.7 Fixed error when accessing internal quote counter
* 1.3.8 Only load assets when on checkout or cart pages
* 1.3.9 Fix bug with scrabled drop point locator values when using alternate delivery address
* 1.3.10 Fix "select" button on droppoint on map not always working 
* 1.4.0 Removed Guzzle and replaced with CURL for all Webshipper API requests
* 1.4.1 Changed support url to new support site'
* 1.4.2 Added better support for non-webshipper shipping rates
* 1.4.4 Updated internal HTTP library
* 1.4.5 Fixed drop point selection not working properly for some shops
* 1.4.6 Added check during calculate_shipping to verify that post data is set
* 1.4.7 Fixed rounding error
* 1.4.8 Bumped 'tested up to' versions
* 1.5.0 Adhere to WooCommerce requirements by adding sanitisation, escaping, validation and relative filepaths. Also, no more HEREDOC nor NOWDOC
* 1.5.1 Adhere more the WC requirements. More sanitisation, escaping and easier to read multiline strings.
* 1.5.2 Match version numbering across files.
* 1.5.3 Fix issue with filepaths corrupting Webshipper app.
* 1.5.4 Removed escaping of javascript inside echo that would destroy using localisations which would also prevent our users from using our droppoint modal.
* 1.5.5 Now tested up to WordPress version 6.3
* 1.5.6 Remove WPML requirement for multi currency
* 1.5.7 Added city to drop points modal and tested up to WordPress version 6.4