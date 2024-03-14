=== Fancy Product Designer REST API ===
Contributors: radykal
Requires at least: 4.6 
Tested up to: 5.2
Tags: product designer, tool, online, shirt, rest, fancy product designer, customize products
Stable tag: trunk
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

== Description ==

This plugin registers all required routes and methods for the Fancy Product Designer Admin. So you can manage your [Fancy Product Designer](https://fancyproductdesigner.com) with the Admin hosted on [https://admin.fancyproductdesigner.com](https://admin.fancyproductdesigner.com).

= Required Plugins =

You also need the [Fancy Product Designer Plugin for WordPress/WooCommerce](https://codecanyon.net/item/fancy-product-designer-woocommercewordpress/6318393?ref=radykal) in order to use this plugin.

See [Documentation](https://support.fancyproductdesigner.com/support/solutions) for further help.

== Changelog ==

= 1.6.4 =
* Required for latest FPD version, otherwise authentication is not working.

= 1.6.3 =
* Pricing-Rules endpoint added.

= 1.6.2 =
* Update Order endpoint added.

= 1.6.1 =
* class-route-ui-layouts.php was missing.

= 1.6.0 =
* Updated endpoints. This update requires Fancy Product Designer 4.1.0.

= 1.5.3 =
* Setting CORS to allow HTTP methods.

= 1.5.2 =
* Improved ednpoints.

= 1.5.1 =
* Fixed some issues with endpoints arguments.

= 1.5.0 =
* Endpoints added for Products and Views.

= 1.4.8 =
* Now working with the new database schema of Gravity Form V2.3. This means also you can only load orders that are made with V2.3 or newer.

= 1.4.7 =
* Semi-bold font variant added in response for custom fonts.

= 1.4.6 =
* Security issue with password for REST API fixed.

= 1.4.5 =
* Endpoints added for Designs.

= 1.4.4 =
* Endpoints added for UI Composer.

= 1.4.3 =
* Added Automated Export and Advanced endpoints for settings.

= 1.4.2 =
* Required for Fancy Product Designer V3.9.2 in order to use the new Automated Export.

= 1.4.1 =
* Bold and italic fonts set are added to the single order data for the print-ready export.

= 1.4.0 =
* New endpoints added to manage the global settings for Fancy Product Designer via ADMIN.

= 1.3.2 =
* Bug: Orders, that were made with an older version of FPD, are not loading.

= 1.3.1 =
* Bug: Authentication was conflicting with some other plugins.

= 1.3.0 =
* Endpoints updated to use with the new ADMIN.

= 1.2.0 =
* Function were used with empty() which caused an error when activating the plugin.

= 1.1.9 =
* Checks if a custom structure for permalinks is set in order to use the REST API.

= 1.1.8 =
* Updated for Fancy Product Designer - WooCommerce/WordPress plugin V3.4.9.

= 1.1.7 =
* Fixed bug for orders in WooCommerce 3.1 because of extra backslashes in the FPD data.

= 1.1.6 =
* Fixed bug when trying to log in via /wp-admin.

= 1.1.5 =
* Shows an error when the necessary header entries are missing in the $_SERVER variable.
* Fixed a bug that new orders from WooCommerce 3.1.0 could not be loaded into ADMIN.

= 1.1.4 =
* WooCommerce orders in the trash were also queried when calling all orders

= 1.1.3 =
* Undefined error issue fixed for authentication process

= 1.1.2 =
* Get username and password from HTTP_AUTHORIZATION or REDIRECT_HTTP_AUTHORIZATION string, if PHP_AUTH_USER or PHP_AUTH_PW is not set in the header. This will fix the forbidden issue.

= 1.1.1 =
* Fixed a Fatal error bug

= 1.1.0 =
* You do not need the Application Passwords anymore, set a custom password for authentication in the Fancy Product Designer REST API options. These options can be found in the General settings of Fancy Product Designer settings.

= 1.0.1 =
* Checks if Shortcode order database table exists


== Installation ==

1. Go to your admin area and select Plugins -> Add new from the menu.
2. Search for „Fancy Product Designer REST API“.
3. Click install.
4. Click activate.


