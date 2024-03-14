=== Products per Page for WooCommerce ===
Contributors: wpcodefactory, algoritmika, anbinder, karzin, omardabbas, kousikmukherjeeli
Tags: woocommerce, products per page, woo commerce
Requires at least: 4.4
Tested up to: 6.4
Stable tag: 2.2.0
License: GNU General Public License v3.0
License URI: http://www.gnu.org/licenses/gpl-3.0.html

Products per page selector for WooCommerce.

== Description ==

**Products per Page for WooCommerce** plugin lets you add **products per page selector** to the frontend of your WooCommerce store.

### &#9989; Main Features ###

* Multiple and customizable **frontend positions** (before products, after products, custom positions, etc.).
* Display selector anywhere on your site with the `[alg_wc_products_per_page]` **shortcode**. This is especially useful when using **visual builders**, e.g., Elementor.
* **Template options**: template, selector class and style, before and after HTML.
* Output selector as a **dropdown box** or as **radio buttons**.
* Option to enable/disable **cookie**.
* Plugin is **WPML** and **Polylang** compatible.
* And more...

### &#127942; Premium Version ###

[Products per Page for WooCommerce Pro](https://wpfactory.com/item/products-per-page-woocommerce/) plugin version includes:

* Customizable **select options**.
* "Products per Page" **widget**.

### &#128472; Feedback ###

* We are open to your suggestions and feedback. Thank you for using or trying out one of our plugins!
* [Visit plugin site](https://wpfactory.com/item/products-per-page-woocommerce/).

### &#8505; More ###

* The plugin is **"High-Performance Order Storage (HPOS)"** compatible.

== Installation ==

1. Upload the entire plugin folder to the `/wp-content/plugins/` directory.
2. Activate the plugin through the "Plugins" menu in WordPress.
3. Start by visiting plugin settings at "WooCommerce > Settings > Products per Page".

== Screenshots ==

1. Products per Page for WooCommerce - Frontend example

== Changelog ==

= 2.2.0 - 19/02/2024 =
* Dev - PHP 8.2 compatibility - "Creation of dynamic property is deprecated" notice fixed.
* Dev – "High-Performance Order Storage (HPOS)" compatibility.
* WC tested up to: 8.6.
* Tested up to: 6.4.

= 2.1.4 - 03/10/2023 =
* Plugin author updated.

= 2.1.3 - 26/09/2023 =
* WC tested up to: 8.1.
* Tested up to: 6.3.
* Plugin icon, banner updated.

= 2.1.2 - 18/06/2023 =
* WC tested up to: 7.8.
* Tested up to: 6.2.

= 2.1.1 - 18/05/2022 =
* Dev - Developers - `alg_wc_products_per_page_replace_pagination_template` filter added.
* WC tested up to: 6.5.

= 2.1.0 - 08/02/2022 =
* Dev - Advanced - "Scopes" options added.
* Dev - Advanced - "Apply in WooCommerce shortcodes" option added (defaults to `yes`).
* Dev - GitHub deploy script added.
* Tested up to: 5.9.
* WC tested up to: 6.1.

= 2.0.1 - 18/01/2022 =
* Dev - Save in session - Additional safe-checks added.
* Dev - Code refactoring.
* WC tested up to: 6.0.

= 2.0.0 - 23/09/2021 =
* Dev - Template Options - Template - `%radio%` placeholder (and "Radio glue" option) added.
* Dev - Template Options - Template - `%select_form%` placeholder renamed to `%dropdown%` (`%select_form%` is still supported though).
* Dev - Position Options - "Before pagination" and "After pagination" positions added (compatible with the "Product Filters for WooCommerce" plugin (https://woocommerce.com/products/product-filters/)).
* Dev - Advanced Options - "Custom CSS" option added.
* Dev - Advanced Options - Form method - GET - Passing all URL params via hidden fields now.
* Dev - Advanced Options - "Save in session" options added (defaults to `yes`). I.e., storing selected "products per page" value in session as well now.
* Dev - WooCommerce `[products]` shortcode compatibility added.
* Dev - Cookie is (maybe) set on the `init` action now.
* Dev - Not escaping the current URL in "products per page" form now.
* Dev - All admin settings input is properly sanitized now.
* Dev - Admin settings descriptions updated.
* Dev - Admin settings rearranged: "Advanced" section added.
* Dev - Code refactoring.
* WC tested up to: 5.7.

= 1.6.0 - 03/09/2021 =
* Dev - "JetWooBuilder For Elementor" plugin (by Crocoblock) compatibility added.
* Dev - Plugin is initialized on the `plugins_loaded` action now.
* Dev - Code refactoring.
* Tested up to: 5.8.
* WC tested up to: 5.6.

= 1.5.0 - 25/03/2021 =
* Dev - Ensuring that HTML `id` attribute for the `select` tag is unique (in case if multiple "products per page" forms are displayed on the same page).
* Dev - Position Options - "Position priority" is now separate for each position.
* Dev - Position Options - Custom position(s) - Now allows setting priority (with vertical bar `|`).
* Dev - Position Options - "Widget" option added.
* Dev - Advanced Options - "Form method" option added (defaults to `POST`).
* Dev - Shortcodes - `[alg_wc_products_per_page]` (and `[alg_wc_ppp_form]` alias) shortcode added.
* Dev - Code refactoring.

= 1.4.0 - 23/03/2021 =
* Dev - Position Options - Position(s) - New positions added: "Before main content", "In archive description" and "After main content".
* Dev - Position Options - "Custom position(s)" option added.
* Dev - Localisation - `load_plugin_textdomain()` to move to the `init` hook.
* Dev - Code refactoring.
* Dev - Admin settings restyled; descriptions updated.
* Tested up to: 5.7.
* WC tested up to: 5.1.

= 1.3.2 - 22/12/2020 =
* Dev - `[alg_wc_ppp_translate]` shortcode added. Shortcodes are now processed when outputting the "products per page" form.
* Tested up to: 5.6.
* WC tested up to: 4.8.

= 1.3.1 - 13/10/2020 =
* Fix - Checking if any products will be displayed before outputting the "products per page" form (e.g., fixes the issue when only subcategories are displayed).
* Tested up to: 5.5.
* WC tested up to: 4.5.

= 1.3.0 - 07/04/2020 =
* Fix - Admin "reset settings" notice fixed.
* Dev - Code refactoring.
* Dev - Admin settings descriptions updated.
* Tested up to: 5.4.
* WC tested up to: 4.0.

= 1.2.0 - 29/10/2019 =
* Fix - Correctly removing page num for pretty permalinks now.
* Fix - Template Options - Template - `%select_form%` fixed.
* Dev - Template Options - "Select class", "Select style", "Before HTML" and "After HTML" options added.
* Dev - Advanced Options - "Enable cookie" and "Cookie expiration time" options added.
* Dev - Code refactoring.
* Dev - Admin settings restyled; descriptions updated.
* Dev - Plugin URI updated.
* WC tested up to: 3.7.
* Tested up to: 5.2.

= 1.1.1 - 23/07/2017 =
* Dev - POT file added.
* Dev - Link updated.
* Dev - Plugin header ("Text Domain" etc.) updated.

= 1.1.0 - 16/01/2017 =
* Fix - "Is plugin enabled" check fixed.
* Dev - "Reset Section Settings" added.
* Tweak - Donate link added.

= 1.0.0 - 29/12/2016 =
* Initial Release.

== Upgrade Notice ==

= 1.0.0 =
This is the first release of the plugin.
