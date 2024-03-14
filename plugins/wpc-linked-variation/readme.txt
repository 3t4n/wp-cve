=== WPC Linked Variation for WooCommerce ===
Contributors: wpclever
Donate link: https://wpclever.net
Tags: woocommerce, wpc, linked variations, variation
Requires at least: 4.0
Tested up to: 6.4
Version: 4.2.2
Stable tag: 4.2.2
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

WPC Linked Variation built to link separate products together by attributes.

== Description ==

**WPC Linked Variation** is a sharp tool for WooCommerce store owners to make life simple and easy. It allows users to connect a group of any product types together by attribute(s) while they can still be managed as separated products. All products will be organized neatly by attributes with an elegant swatches style just as the variation swatches look for normal variable products.

What is worth mentioning is that our plugin allows users to link items of many product types together. In your dream, have you ever wondered how you can group a [Product Bundles](https://wordpress.org/plugins/woo-product-bundle/), a [Smart Grouped Product](https://wordpress.org/plugins/wpc-grouped-product/), and a [Composite Products](https://wordpress.org/plugins/wpc-composite-products/) together? This plugin just realizes your dream. No complex codes. Just need to create a common attribute(s) then configure the terms for them then you can effortlessly configure the swatches display for those products.

If you have ever felt exhausted from finding useful tools to configure complex settings at the variation level, this plugin will save the day. Many of the great plugins, including most of our WPC plugins, are only available at the product level. With this plugin, you can still utilize plugins on your products while linking them together at ease and showcase in an informative style.

Noticeably, **WPC Linked Variation** is integrated with the Quick View feature from the [WPC Smart Quick View](https://wordpress.org/plugins/woo-smart-quick-view/) plugin, which allows buyers to preview products right on the current page. This helps improve the user experience and make customers stay on track with the currently viewed products.

= Live demo =

Visit our [live demo](https://demo.wpclever.net/wpclv/ "live demo") here to see how this plugin works.

= How to link any product types by attributes =

https://www.youtube.com/watch?v=7_EJqkEXdAQ

= Key Features =

- Configure a position to display the linked variations on each single product page.
- Swatches style for linked variations.
- Use the shortcode to display the linked variations in any place on your site.
- Preview individual products in a new tab, the same tab, or on a Quick View popup.
- Translatable for multilingual sites
- Integrated with the feature from the [WPC Smart Quick View](https://wordpress.org/plugins/woo-smart-quick-view/) plugin
- Compatible with most WooCommerce themes & plugins

= Steps to set up linked variations =

**STEP 1:** Create the attributes by navigating to WooCommerce >> Attributes, configure a name, slug then click on Configure the terms button to add terms.

If the attributes you need have already been created, you still need to check carefully if the terms are fully configured or not.

**STEP 2:** Go to the single product page, in the Product Data section, open the Attributes tab and check the drop-down list to make sure the attributes chosen are the ones configured in the Attributes section above.

Since WPC Linked Variations will display swatches buttons for linked variations, it’s important that all terms under each attribute be fully configured. If you click to create new custom attributes, then configure the terms in the Attributes tab of the product page, this won’t work and those attributes won’t be displayed in the swatches.

**STEP 3:** Make sure you have properly added the chosen attributes to all products that are about to be linked.

- The selected attributes for linked variation must be common for all chosen products.
- The terms used for variations must be distinctive in order to distinguish between. linked variations in the preview step.

For example:

The chosen Attributes - Color must be chosen in the Attributes tab of both product A & product B. The terms chosen for each item must be different from each other: Color - Blue for product A & Color - Red for product B.

Or both can have the same color but they need a second common attribute where they can be distinguished.

**STEP 4:** Navigate to WPClever >> Linked Variation:

- Configure a position to display the list of linked variations.
- Choose a preview style: in the same tab, new tab, or Quick View.

Then you’re good to go. You can also read for more instructions here.
Good luck and enjoy our plugin.

= Other alternatives you might also like =

- [WPC Variation Swatches](https://wordpress.org/plugins/wpc-variation-swatches/)
- [WPC Variations Radio Buttons](https://wordpress.org/plugins/wpc-variations-radio-buttons/)
- [WPC Variations Table](https://wordpress.org/plugins/wpc-variations-table/)
- [WPC Show Single Variations](https://wordpress.org/plugins/wpc-show-single-variations/)
- [WPC Smart Quick View](https://wordpress.org/plugins/woo-smart-quick-view/)

== Installation ==

1. Please make sure that you installed WooCommerce
2. Go to plugins in your dashboard and select "Add New"
3. Search for "WPC Linked Variation", Install & Activate it
4. Go to WP-admin > WPClever > Linked Variation to add linked variations

== Changelog ==

= 4.2.2 =
* Added: Filter hook 'wpclv_get_terms_args'

= 4.2.1 =
* Added: Filter hook 'wpclv_product_thumbnail_size'

= 4.2.0 =
* Added: Filter hook 'wpclv_attribute_label'

= 4.1.9 =
* Fixed: Allow HTML in tooltip content

= 4.1.8 =
* Updated: Compatible with WP 6.4 & Woo 8.4

= 4.1.7 =
* Updated: Optimized the code

= 4.1.6 =
* Fixed: Minor CSS/JS issues in the backend

= 4.1.5 =
* Fixed: Don't get product outside linked

= 4.1.4 =
* Updated: Optimized the code

= 4.1.3 =
* Added: Use dropdown for an attribute

= 4.1.2 =
* Fixed: Attributes limitation when using shortcode

= 4.1.1 =
* Added: Parameter 'hide' and 'limit' for shortcode [wpclv], for example: [wpclv hide="pa_size,pa_color" limit="3"]

= 4.1.0 =
* Added: New tooltip library
* Added: Option to display product information on tooltip

= 4.0.7 =
* Updated: Optimized the code

= 4.0.6 =
* Fixed: Minor CSS/JS issues in the backend

= 4.0.5 =
* Updated: Optimized the code

= 4.0.4 =
* Updated: Optimized the code

= 4.0.3 =
* Fixed: Minor CSS/JS issues in the backend

= 4.0.2 =
* Added: Option to limit variations on the archive page

= 4.0.1 =
* Fixed: Remove filter 'wc_attributes_array_filter_visible'

= 4.0.0 =
* Added: Position on archive page
* Added: Option to choose any attribute terms

= 3.4.3 =
* Updated: Optimized the code
* Added: HPOS compatibility

= 3.4.2 =
* Fixed: For sites with over 500 linked products

= 3.4.1 =
* Added: Compatible with WPC Smart Messages for WooCommerce

= 3.4.0 =
* Added: Function 'get_settings' & 'get_setting'

= 3.3.4 =
* Fixed: Some products weren't shown (again)

= 3.3.3 =
* Fixed: Some products weren't shown

= 3.3.2 =
* Updated: Optimized the code

= 3.3.1 =
* Fixed: Notice on settings page

= 3.3.0 =
* Updated: New interface for product attributes selector

= 3.2.3 =
* Updated: Optimized the code

= 3.2.2 =
* Fixed: Minor CSS/JS issues

= 3.2.1 =
* Updated: Optimized the code (Thanks to Christian)

= 3.2.0 =
* Fixed: Minor CSS/JS issues

= 3.1.2 =
* Fixed: Error when using Elementor

= 3.1.1 =
* Fixed: Error when using the shortcode

= 3.1.0 =
* Added: Use swatches from WPC Variation Swatches

= 3.0.0 =
* Updated: New data structure for Linked Variations, allow a huge number of linked variations

= 2.0.2 =
* Added: Filter hook 'wpclv_term_title'

= 2.0.1 =
* Added: Option to exclude hidden product or unpurchasable product

= 2.0.0 =
* Added: Products source categories or tags
* Added: Drag & drop to re-arrange linked

= 1.0.8 =
* Added: Filter hook "wpclv_term_label"

= 1.0.7 =
* Added: Option for tooltip position

= 1.0.6 =
* Added: Option "Use nofollow links"
* Fixed: Remove the link when using quick view popup

= 1.0.5 =
* Fixed: Don't show product image

= 1.0.4 =
* Added: Filter hook 'wpclv_term_image'

= 1.0.3 =
* Added: Option to show/hide empty attribute terms

= 1.0.2 =
* Fixed: Wrong product links

= 1.0.1 =
* Added: Compatible with WPC Smart Quick View
* Fixed: Use placeholder image if product hasn't featured image

= 1.0.0 =
* Released