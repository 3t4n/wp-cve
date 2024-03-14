=== Sparxpres for WooCommerce ===
Contributors: Sparxpres
Tags: finance, banking, loan, sparxpres
Requires at least: 5.9
Tested up to: 6.4
Requires PHP: 7.2
WC requires at least: 5.0
WC tested up to: 7.9.0
Stable tag: trunk
License: GPL v3 or later
License URI: https://www.gnu.org/licenses/gpl-3.0.html

This plugin is for web shops that have a finance agreement with Sparxpres.

== Description ==

= Easily insert Sparxpres finance calculations on your WooCommerce web shop =

This plugin is for Sparxpres retailers, and is used for displaying finance calculations on product pages and in the shopping cart. The plugin also adds a Sparxpres Payment method in checkout.

= Features of Sparxpres for WooCommerce plugin =

* Adds finance calculation to the single product pages
* Adds finance calculation to the shopping cart
* Adds Sparxpres as a payment method on checkout

= Credits =

This plugin is created by the <a href="https://sparxpres.dk/" rel="friend" title="Sparxpres">Sparxpres</a> team.


== Installation ==

1. Visit Plugins > Add New.
2. Search for "Sparxpres for WooCommerce" and install it.
3. Activate "Sparxpres for WooCommerce" plugin.
4. Goto "Settings" in the Sparxpres for WooCommerce plugin.
5. Insert the link id you got from Sparxpres.
6. Save the settings, and you are good to go ;-)

== Frequently Asked Questions ==

= Can I use Sparxpres for WooCommerce plugin, without being a Sparxpres retailer? =

No, you need to have an agreement with Sparxpres to use this plugin.

= What is a link id? =

The linkId, is a key you get from Sparxpres, that identify you and your loan product.

= Why is the loan calculation not shown? =

If no calculation is shown:
* The plugin is not activated.
* The plugin is not setup under the "Settings -> Sparxpres for WooCommerce" menu in WordPress.
* The link id used in the settings is not correct or empty.
* The actual single product's price is lower (typical minimum 2.000 DKK) or higher than allowed by the loan product.
* The currency is not DKK.

= How do I contact Sparxpres? =

At <a href="https://sparxpres.dk/" rel="friend" title="Sparxpres">sparxpres.dk</a>, you will find our contact information.

== Changelog ==

= 1.0.16 =
* Initial release version

= 1.0.17 =
* Tested up to 5.7
* Information modal corrected so that it always opens on top

= 1.0.18 =
* New callback types added

= 1.1.0 =
* Tested up to 5.8
* OBS: Callback order flow/status updated, to use woocommerce payment_complete method when an application is RESERVED or CAPTURED.
  - The old order statuses spx-processing (Sparxpres, behandles) and spx-captured (Sparxpres, godkendt) is removed.
* Send callback key to Sparxpres button/function added

= 1.1.1 =
* Add note to order if callback didn't update the order

= 1.1.2 =
* Removed warning when registering a rest route
* Convert order status on old Sparxpres orders (spx-processing is converted to pending and spx-captured is converted to completed).

= 1.1.3 =
* Fixed error, where order conversion in special cases failed
* Updated clean-up on uninstall
* Send callback key, moved to save button

= 1.1.4 =
* Updated callback engine

= 1.1.5 =
* Slider updated
* Tested up to 5.9

= 1.1.6 =
* Slider updated
* Slider imported as module

= 1.1.7 =
* Tested up to 6.0
* XpresPay added
* Slider updated
* Terms page updated

= 1.2.0 =
* Slider change to use HTML5 range
* Module refactored

= 1.2.1 =
* Only run if WooCommerce is installed and active
* Minor CSS updates

= 1.2.2 =
* XpresPay added as separate payment option
* Minor bugfixes
* Tested up to 6.0.3

= 1.2.3 =
* Tested up to 6.1

= 1.2.4 =
* Minor bugfixes
* XpresPay appearances updated

= 1.2.5 =
* Tested up to 6.2
* Changed to use sparxpres.dk/app
* Minor updates

= 1.2.6 =
* Bugfix

= 1.2.7 =
* Moved product variation detection to js file
* Bugfix

= 1.2.8 =
* Bugfix

= 1.2.9 =
* Regex updated

= 1.2.10 =
* Changed to use app.sparxpres.dk
* Loaninfo is cached for non-dynamic products

= 1.2.11 =
* Payment gateway available check updated
* CSS update for small screens

= 1.2.12 =
* Payment gateway available check updated

= 1.2.13 =
* Bugfix, api endpoints updated

= 1.2.14 =
* Bugfix

= 1.2.15 =
* Tested up to 6.3
* Danish translation added
* Settings page rebuild

= 1.2.16 =
* Tested up to 6.4
* Minor CSS updates
