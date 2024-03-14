=== OnSale Page for WooCommerce ===
Contributors: wpgenie2
Donate link: https://wpgenie.org/store/
Tags: wordpress onsale page, OnSale Page for WooCommerce, simple wordpress onsale page, onsale page, onsale page plugin, wordpress onsale page plugin, OnSale Page for WooCommerce plugin, simple onsale page, wpgenie onsale page, wpgenie
Requires at least: 4.0
Tested up to: 7.0
Requires PHP: 5.6
Stable tag: 1.1.3
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

OnSale Page is an extension for Woocommerce which enables you to have real on sale page with paging, sorting and filtering.

== Description ==

OnSale Page for WooCommerce is an extension for WooCommerce. Since WooCommerce is popular we decided that it would be neat to
extend it with real WordPress page which displays products on sale. We developed this plugin because WooCommerce
has onsale widget and shortcode but it lacks paging, sorting and filtering which you can usually find on regular WooCommerce catalog page.

With our onsale page plugin you can setup OnSale Page for WooCommerce where you can display all products that are on sale. If you add text / content on your on sale page it will be displayed along with on sale products. Gutenberg and classic editor supported.

= Support =

You can contact us at our website [wpgenie.org](http://wpgenie.org/) if you have problems or questions.


== Installation ==

= Minimum Requirements =

* WordPress 4.0 or greater
* WooCommerce 3.0 or greater


= Setup =

This section describes how to install OnSale Page for WooCommerce plugin and get it working.

1. Upload the plugin files to the /wp-content/plugins/ directory, or install the plugin through the WordPress plugins screen directly.
2. Activate the plugin through the 'Plugins' screen in WordPress.
3. Create new page.
4. Go to Woocommerce Settings -> Products -> tab Display.
5. Under Shop & Product Pages you will notice "Onsale Page".
6. Select page you created for on sale page.
7. Save settings.
8. Go to new on sale page and you will see all on sale products there.

If you have any problems contact us at http://wpgenie.org/




== Frequently Asked Questions ==

= Can I add content to on sale page? =

Yes, from version v1.1.0 you can add content. If you add text / content on your on sale page it will be displayed along with on sale products. Gutenberg and classic editor supported.

= I'm using latest WooCommerce and I don't see any product on sale? =

If you have problem with displaying on sale products empty wp_wc_product_meta_lookup table and regenerate it using Status -> Tools -> Regenerate Product lookup tables tool.

= I cannot reach on sale page? =

Go to WordPress settings -> Permalinks and click Save Cahnges button on the bottom without doing any changes. You will see notification "Permalink structure updated." Should be all good now.

= How can I show only specific category of products that are on sale? =

Load page with ?product_cat query parameter for example https://your-website.com/on-sale-page/?product_cat=uncategorized

= Have a question? =

If you want answer here please send us your questions to info@wpgenie.org



== Screenshots ==

1. OnSale Page plugin options


== Changelog ==
= 1.1.3 =
* Fix: WPMU compatibility

= 1.1.2 =
* Add: HPOS compatibility
* Add: versions

= 1.1.1 =
* Fix: WPMU compatibility

= 1.1.0 =
* Add: versions
* Add: display on sale page content if exists
* Fix: Rank Math seo fixes

= 1.0.12 =
* Add: versions
* Fix: seo canonical url

= 1.0.11 =
* Add: versions

= 1.0.10 =
* Add: wc_onsale_page_product_ids_on_sale filter

= 1.0.9 =
* Add: Yoast SEO compatibility

= 1.0.8 =
* Add: is_woocommerce_sale_page()

= 1.0.7 =
* Add: WooCommerce OnSale Page Layered Nav Widget

= 1.0.6 =
* Fix: Issue with current item in menu
* Fix: Issue with language switcher in WPML

= 1.0.5 =
* Fix: Issue with page title on sale page

= 1.0.4 =
* Fix: Issue with 404 when there is no products on sale

= 1.0.3 =
* Add: WPML support

= 1.0.2 =
* Fix: product not showing when Shop Page Display is set to show categories
* Fix: not showing option Shop Page Display in WooCommerce settings

= 1.0.1 =
* Fix: notice when on sale page is not set

= 1.0 =
* Initial release


== Upgrade Notice ==

= 1.0.1 =
Fix: notice when on sale page is not set

= 1.0 =
Initial realease
