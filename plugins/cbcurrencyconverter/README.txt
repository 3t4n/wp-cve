=== CBX Currency Converter ===
Contributors: codeboxr, manchumahara
Tags: currency converter,currency conversion,currency exchange,currency calculator,bitcoin
Requires at least: 5.3
Tested up to: 6.4.1
Stable tag: 3.1.0
PHP:7.4.*
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Currency Converter shortcode and widget for WordPress

== Description ==
Universal Currency Converter and Rate Display is capable to calculate and display current exchange rates via Widget and Shortcode. It has four display layouts. Supports almost all global currency (94 supported so far) and display up to 10 currency rates simultaneously.

### CBX Currency Converter by [Codeboxr](https://codeboxr.com/product/cbx-currency-converter-for-wordpress/)

>📺 [Live Demo](https://codeboxr.net/wordpress/demo-cbx-currency-converter/) | 🌟 [Upgrade to PRO](https://codeboxr.com/product/cbx-currency-converter-for-wordpress/) | 📋 [Documentation](https://codeboxr.com/doc/cbcurrencyconverter-doc/) | 👨‍💻 [Free Support](https://wordpress.org/support/plugin/cbcurrencyconverter/) | 🤴 [Pro Support](https://codeboxr.com/contact-us) | 📱 [Free Support](https://wordpress.org/support/plugin/cbcurrencyconverter/)

**Any kind of feedback is welcome. Any feature missing that you think it should have contact us, we will try to add in the core or addon.**

### 🛄 Core Plugin ###

**🎁 Features**

*   Displays Currency Converter and Live Exchange Rate
*   Support most [currencies](https://codeboxr.com/doc/cbcurrencyconverter-doc/available-currencies/)
*   Display multiple Currencies at the same time
*   Set Default Currency and Amount for Calculator
*   Any currency missing that you need to add ? Contact us.

**✅ Layouts & Functions**
*   4 layout
*   Classic Widgets & Shortcodes support
*   Elementor Widget support (New From V2.7.2)
*   Gutenberg Block support (New From V2.7.2)
*   WPBakery Widget support (New From V2.7.6)
*   AJAX based Calculator
*   Decimal point or how many digits to show

**$ Currency Apis**

*   Core - Alphavantage.co - Need pro plan
*   Core - openexchangerates.org - Free Plan(only base USD)
*   Core - Currencylayer.com - Free Plan(only base USD)
*   Pro Addon -  Openexchangerates.org Pro Plan(The Unlimited Plan)
*   Pro Addon -  Exchangeratesapi.io Pro Plan(at least Basic Plan)
*   Pro Addon -  Currencylayer.com Pro Plan(at least Basic Plan)
*   Pro Addon - xe.com Pro Plan - New in pro addon version 1.6.10
*   Pro Addon - abstractapi.com Pro Plan - New in pro addon version 1.6.10
*   Pro Addon - getgeoapi.com Pro Plan - New in pro addon version 1.6.10

** Rest Api(New) **

* Pro Adddon - New REST API endpoint for currency conversion - for single currency(calculator)
* Pro Adddon - New REST API endpoint for currency conversion - for multi currencies(list)

**▶️ How to set Finance Api**

[youtube http://www.youtube.com/watch?v=Y5yQbEJ0KzA]

**🧮 Shortcodes**
One shortcode for both calculator , list or both feature. Please check the shortcode params.
[cbcurrencyconverter]


### 💎 Pro Version ###
👉 Get the [Pro addon](https://codeboxr.com/product/cbx-currency-converter-for-wordpress/)

* Optional integration in woocommerce product page if product is in stock,takes product price as default amount and product currency as default from currency
* Can give every widgets and shortcodes different look
* Widgets can take its own setting or global settings
* Custom currency rate
* Bitcoin Currency Support
* Add extra % to currency rate(From V1.6.2)

### 👍 Liked Codeboxr? ###

- Join our [Facebook Page](https://www.facebook.com/codeboxr/)
- Learn from our tutorials on [Youtube Channel](https://www.youtube.com/user/codeboxr)
- Or [Rate us](https://wordpress.org/support/plugin/cbcurrencyconverter/reviews/#new-post) on WordPress

**🔩 Installation**

How to install the plugin and get it working.


1. Upload `cbcurrencyconverter` folder  to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. From 'Setting' menu see sub menu 'Currrency Converter', save setting
4. Select view, default amount, currency and css colors  from the plugin setting

== Screenshots ==

== Changelog ==
= 3.1.0 =
* [NEW] New shortcode introduced 'cbcurrencyconverter_rate'  for direct rate display

= 3.0.9 =
* [IMPROVEMENT] Calculator form layout style improved
* [IMPROVEMENT] Added option to display country flag
* [IMPROVEMENT] Added option to display currency symbol
* [IMPROVEMENT] Helper classed got some new methods related with symbol
* [NEW] Mobile app support in pro addon(To be release soon)
* [New] exchangerate.host api now needs api key
* [New] exchangerate.host api free(core plugin) and pro(pro addon) introduced

= 3.0.8 =
* [New] Setting field 'multiselect' is deprecated and now uses 'select' with 'multi' = 1
* [New] exchangerate.host now needs api key, they have both free and pro

= 3.0.6 =
* [IMPROVEMENT] Minor improvement for plugin setting panel

= 3.0.5 =
* [Fixed] Plugin activation error fixed
* [IMPROVEMENT] Js and CSS enqueue names are now standard

= 3.0.4 =
* [Fixed] Nonce checking security fixed
* [IMPROVEMENT] Style improvement for currency converter dropdown field(make it work for dark back background)
* [IMPROVEMENT] Dashboard setting improvements

= 3.0.3 =
* [IMPROVEMENT] Style improvement for currency converter dropdown field(make it work for dark back background)

= 3.0.2 =
* [IMPROVEMENT] Style improvement for currency converter dropdown field

= 3.0.1 =
* [IMPROVEMENT] Style improvement for currency converter input field

= 3.0.0 =
* [Important Note] There are breaking changes in Core plugin v3.0.0 and pro addon 1.7.0. Core plugin V3.0.0 needs pro addon 1.7.0
* [Removed] Removed support for WP eCommerce https://wordpress.org/plugins/wp-e-commerce/

= 2.9.0 =
* [Important Note] Alphavantage no longer free for conversion
* [New] Added some new currency api support in pro addon
* [Bug Fix] Gutenberg multi select display
* [NEW] Pro Addon - New REST API endpoint for currency conversion - for single currency
* [NEW] Pro Addon - New REST API endpoint for currency conversion - for multi currencies

= 2.8.4 =
* [Important Note] There is breaking changes in core version 2.8.4, if you have pro addon less than 1.6.9 then it will be automatically deactivated, please update pro addon to version 1.6.9 with core 2.8.4
* [Bug Fix] Bug fixed in rates api, pro addon needed update to make that work with core. On upgrade if pro addon not 1.6.9 or latest then pro addon will be disabled
* [Bug Fix] JS error fixed

= 2.8.3 =
* [IMPROVEMENT] Dashboard improvement & Pro addon compatibility fix
* [NEW] Gutenberg blocks works in new WordPress 5.8 widget area that is block based

= 2.8.2 =
* [NEW] Added new currency XOF - West African CFA franc (Currency rates and support depends on rates api)
* [NEW] Added  new currency XAF - Central African CFA franc (Currency rates and support depends on rates api)

= 2.8.1 =
* [NEW] New currency api for currencylayer.com(https://currencylayer.com) if base only USD - free subscription
* [NEW] Pro Addon - New currency api for currencylayer.com(https://currencylayer.com) - pro account holder of https://currencylayer.com with at least "Basic Plan"


= 2.8.0 =
* [NEW] Easy way to add new api using new hooks(filter) 'cbcurrencyconverter_rates_apis', see documentation
* [NEW] New currency api for openexchangerates(https://openexchangerates.org/) if base only USD
* [NEW] Pro Addon - New currency api for Openexchangerates(https://openexchangerates.org/) - pro account holder of https://openexchangerates.org/ with at least "The Unlimited Plan"
* [NEW] Pro Addon - New currency api for Exchangeratesapi(Exchangeratesapi.io) - pro account holder of https://Exchangeratesapi.io/ with at least "Basic Plan"

= 2.7.12 =
* [Improvement] Dashboard improved
* [NEW] CSS is now generated as scss files

= 2.7.11 =
* [New] Added Bahrain Dinar(BHD) as new currency
* [Improvement] Some minor adjustments and improvements

= 2.7.10 =
* [New] All Currency names are now translatable

= 2.7.9 =
* [New] New Currency (Kenya Shilling) Added

= 2.7.8 =
* [Bug fix] Uninstall plugin(delete) now deletes properly the options created by this plugin
* [New] Uninstall plugin(delete) now deletes currency rate caches

= 2.7.7 =
* [New] Backend Setting on click shortcode copy and Demo
* [New] Admin notice for full reset
* [New] Admin notice for rate api cache reset

= 2.7.6 =
* [New] WPBakery Widget

= 2.7.5 =
* [Bug Fix] Style fix for widget page, last release was messing with widgets page style and header style which is now restricted

= 2.7.3 =

* [Bug Fix] Elementor widget loading conflict issue fixed

= 2.7.2 =

* [Improvement] Replaced chosen select js library with select2 js library
* [New] Template system for layout for list, calculator and inline
* [New] Elementor widget
* Overall improvement

= 2.7.1 =

* [New] Changed version format to x.y.z
* [New] Added Rate api caching enable/disable
* [New] Added Rate api caching time, default 2
* [Improvement] Minor overall improvement, pro addon updated as well