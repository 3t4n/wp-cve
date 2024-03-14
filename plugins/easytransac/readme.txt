=== EasyTransac pour WooCommerce ===
Contributors: EasyTransac
Tags: payment,checkout,payment pro,encaissement,moyen de paiement,paiement,bezahlsystem,purchase,online payment,easytransac
Requires at least: 4.1
Tested up to: 6.2
Requires PHP: 7.0
Stable tag: 2.73
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

EasyTransac payment gateway for WooCommerce.

== Description ==
= Introduction =

Notre module EasyTransac pour WooCommerce vous permet d'encaisser simplement les paiements CB sur votre site Wordpress.

 * EasyTransac accepte les paiement en EURO uniquement (Visa et Mastercard)
 * EasyTransac propose des taux bas pour des paiement sécurisés sur votre site E-commerce

 ------------------------------------------------------

Easy payment solution for your Wordpress WooCommerce website:

 * Accept VISA and Mastercard payments in EURO only
 * EasyTransac provides turnkey secure payment at very low charge rates


== Installation ==
== Installation ==

1. Installez le module WooCommerce si vous ne l'avez pas déjà et activez-le sur votre site Wordpress.
2. Installez et activez ensuite notre module EasyTransac pour WooCommerce.
3. Créez un compte sur https://www.easytransac.com et rendez vous dans la partie E-Commerce dans laquelle vous pourrez créer une Application.
Depuis la page de création de l'Application, vous pouvez choisir le nom de l'application qui vous permettra de filtrer vos futures transactions.
4. Copiez-collez la clé d'API depuis la page de l'application sur EasyTransac dans la configuration du module EasyTransac pour WooCommerce.
5. Le type d'application Test (ou Démo) signifie que les paiements ne sont pas réels, le type Live (ou Réel) indique que les paiements sont réellement effectués en banque.
6. Veuillez saisir l'adresse IP de votre serveur dans le cadre des adresses IP autorisées.
Pour le lien de notification, saisissez l'adresse de votre site internet, suivie de: /wc-api/easytransac. Exemple: http://www.mon-site.com/wc-api/easytransac


------------------------------------------------------

1. Ensure that you have the WooCommerce plugin enabled on your Wordpress site.
2. Install our plugin EasyTransac for WooCommerce afterwards.
3. Create an account on https://www.easytransac.com, then log in and go to the E-commerce Applications menu in order to create an application to be linked with your Wordpress site.
On the application creation page, enter a name for your app that will allow you to filter your transactions by usage.
4. Copy the API key from the application page to our plugin's configuration page.
5. You can chose the application type: 'Test' meaning that payments aren't real, whereas Live is for production use.
6. Please enter the IP address of your server hosting your Wordpress website in the allowed IP addresses section.
For the notification link, please enter your website's link followed by '/wc-api/easytransac'. Example: http://yoursite.com/wc-api/easytransac


== Changelog ==

= 2.9 =
* Wordpress 6.4 compatibility check
* WooCommerce 8.4 compatibility check

= 2.73 =
* Update easytransac SDK from 1.3.0 tro 1.3.7
* Wordpress 6 compatibility check

= 2.72 =
* Link to Easytransac on settings page.

= 2.71 =
* Optional phone number.

= 2.70 =
* Partial refund.

= 2.69 =
* Configuration help enhancement.

= 2.68 =
* Subscriptions: removed 20% minimum amount limit for first payment.

= 2.67 =
* Restock products after unpaid order is cancelled. Multiple payments up to 12 times with WooCommerce Subscription.

= 2.66 =
* One-click ergonomics bis.

= 2.65 =
* One-click ergonomics.

= 2.64 =
* Support for recurring coupons after WooCommerce update.

= 2.63 =
* Logo image quality.

= 2.62 =
* Logo changed.

= 2.61 =
* Country code added. New Logo.

= 2.60 =
* WooCommerce subscription payment over a period of 90 days as EasyTransac subscription option.

= 2.58 =
* WooCommerce subscription payment status update.

= 2.57 =
* WooCommerce subscription pending payment and failed payment handled.

= 2.56 =
* WooCommerce subscription renewal orders.

= 2.55 =
* EasyTransac return url update. Ready for WooCommerce 5.6.

= 2.54 =
* EasyTransac SDK v1.2.0 update.

= 2.53 =
* WooCommerce subscription: Minimum initial payment of 20%.

= 2.52 =
* EU VAT assistant vat number support.

= 2.51 =
* Test up to hint for Wordpress 5.5.

= 2.50 =
* WooCommerce subscription recurring percent discount.

= 2.40 =
* WooCommerce subscription taxes included.

= 2.30 =
* WooCommerce subscription without signup fees.
* Bank transfer notification added.

= 2.20 =
* Status update on payment retry with in progress status locked

= 2.12 =
* Disable stock level reduce options
* Settings quick link
* E-mail notification option for order id and paid amount mismatch

= 2.11 =
* WooCommerce Subscription update

= 2.10 =
* EasyTransac SDK v1.1.2

= 2.9 =
* Oneclick display

= 2.8 =
* English-French translations
* Updated EasyTransac SDK to 1.0.14
* Reworded OneClick texts 

= 2.7 =
* English-French translations

= 2.6 =
* Telephone number validation

= 2.5 =
* New subscriptions possibilities
* New refunds possibilities
* EasyTransac SDK update to v1.0.11
* Other than unix environment support
* Notification wordpress magic quotes bugfix

= 2.4 =
* New debug mode for problem troubleshooting.

= 2.3 =
* EasyTransac SDK update to v1.0.10

= 2.2 =
* WooCommerce v3 compatibility.
* HTTP notification issue fix for HTTP only websites.

= 2.1 =
* OneClick can be disabled.

= 2.0 =
* Easytransac SDK integration. New requirement : PHP >= 5.5.

= 1.9 =
* Language of payment page is set.

= 1.8 =
* OneClick payments.

= 1.7 =
* cURL fallback.
* Notification URL helper on settings page.

= 1.6 =
* New logo.

= 1.5 =
* TLSv1.1 Fallback instead of TLSv1 which is not working correctly on certain systems.

= 1.4 =
* Non-HTTPS websites hotfix.

= 1.3 =
* Cancel button redirects back to the cart.
* The cart is only emptied when the payment is completed.
* Refund support removed: EasyTransac API doesn\'t support partial refund nor WooCommerce supports full refund only. Refund can still be done via the EasyTransac back office.

= 1.2 =
* Support for non-HTTPS websites.
* Adds system requirements checks.

= 1.1 =
* French translations.

= 1.0 =
* WooCommerce Payment Gateway.
