=== Restrict - membership, site, content and user access restrictions for WordPress ===
Contributors: restrict, tickera, freemius
Donate link: https://restrict.io
Tags: restricted content, restrict content, restrict access, protect content, restrict site
Requires at least: 4.3
Tested up to: 6.4.3

Restrict content easily to logged in users, members with a specific role or user capability, to it's author, Tickera, Easy Digital Downloads or WooCommerce users and more!

== Description ==

The [Restrict plugin](https://restrict.io/?utm_source=wordpress.org&utm_medium=plugin-page&utm_campaign=main "Restricted Content for WordPress") makes it easy for you to control and protect access to the content of posts, pages and custom post types.

You can restrict, show and hide content to:

* Everyone (all website visitors)
* Logged in users
* Users with a specific role (administrator, editor, author, subscriber, etc)
* Users with a specific [capability](https://codex.wordpress.org/Roles_and_Capabilities "WordPress Roles and Capabilities")
* Author of a post / page
* Tickera users (who purchased any ticket, any ticket for a specific event or a specific ticket type)
* WooCommerce users (who made any purchase or who purchased a specific product)
* WooCommerce users with limited time access to the content after they made a purchase of specific product
* Easy Digital Downloads users (who made any purchase or who purchased a specific product)
* Easy Digital Downloads users with limited time access to the content after they made a purchase of specific product

= Integrations =

Restrict has seamless integrations with [Tickera Event Ticketing System for WordPress](https://tickera.com/ "Event Ticketing System for WordPress"), [Easy Digital Downloads](https://wordpress.org/plugins/easy-digital-downloads/ "Simple Ecommerce for Selling Digital Files on WordPress"), [WooCommerce](https://wordpress.org/plugins/woocommerce/ "WooCommerce is a powerful, extendable eCommerce plugin that helps you sell anything. Beautifully.") allowing you to restrict the sensitive content of pages or posts based on criteria specific for these plugins (i.e. show content to users who purchased a specific ticket or WooCommerce product). Also, Restrict has integration with [Simple URLs](https://wordpress.org/plugins/simple-urls/ "Simple URLs"). With this integration, you can create a redirection in SimpleURLs plugin and then restrict access to that redirect so that if the customer attempts to access redirect URL without fulfilling the required criteria, you can set in Restrict whether to display them a specific page or redirect them to some other, specific URL.

== Premium version features ==

* [Restrict whole post type at once](https://restrict.io/restricted-content-documentation/post-types/) - the post types area of Restrict plugin allows you to select the default content visibility to the existing post types on your website.
* [Restrict part or all the content](https://restrict.io/restricted-content-documentation/shortcodes/) (Shortcodes) - Easily restrict just part of the content on any of your pages or posts making it possible to use the same page or post but with different content based on the set criteria.
* [Restricting a whole post category](https://restrict.io/restricted-content-documentation/rectricting-access-to-post-categories/) - If you want to restrict the whole post category, the premium version of Restrict plugin takes care of that too, allowing you to easily set the criteria based on which a certain post category will be displayed or hidden.
* [Hide and show widgets conditionally](https://restrict.io/restricted-content-documentation/restricting-visibility-of-wordpress-widgets/) - Make your widgets show or disappear for different users! Similarly to the content restriction, you can also restrict which widgets will be shown to what user.
* [Display menu items conditionally](https://restrict.io/restricted-content-documentation/restricting-menu-items/) - You can set different criteria for each menu item and make them displayed or hidden to logged in users, certain user roles, users with specific capability and even logged out users.
* [Login form anywhere](https://restrict.io/restricted-content-documentation/login-form/) - Regardless of whether you’re using Gutenberg or classic editor, we made it easy for you to place the login form on any of your pages or posts.
* Site Lock - Lock the entire website in one simple click and make it accessible only to logged in users! Similarly, you can also restrict access to REST API to only logged in users.
* [White label option](https://restrict.io/restricted-content-documentation/white-label-option/)

= Documentation =

Stuck? Check out the [plugin documentation](https://restrict.io/documentation/?utm_source=wordpress.org&utm_medium=plugin-page&utm_campaign=documentation "Restrict Plugin Documentation")

== Installation ==

1. Install plugin
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Go to Admin > Restrict to set up different restriction messages
4. Add restrictions to your posts, pages or various custom post types

== Screenshots ==
1. Main welcome screen with the features descriptions
2. General options
3. Tickera options
4. WooCommerce options
5. Content availability metabox which is visible in a post / page / custom post type screen in the admin panel
6. Content availability metabox for Tickera users
7. Content availability metabox for WooCommerce users

== Changelog ==

= 2.2.6 =
* Woocommerce HPOS compatibility.
* Unable to save styles in visual editor. [Fixed]

= 2.2.5 =
* Security measures (Data sanitization and output escaping).

=2.2.4=
* Updated Freemius SDK

= 2.2.3 =
* Gutenberg block elements: Update deprecated InspectorControls and serverSideRender.

= 2.2.2 =
* PHP Warning: "Trying to access array offset" appearing in admin page. [Fixed]
* "Content Available To" setting: Append ProductID at "Tickera Users" and "Woocommerce Users" Multi-select dropdown menu.

= 2.2.1 =
* Clearing PHP Deprecated Notice: Required parameter follows optional parameter.

= 2.2.0 =
* Wordpress 6.0 compatibility

= 2.1.9 =
* Freemius SDK update

= 2.1.8 =
* Fixed white label feature
* Tested up to 5.7.2 version of WordPress

= 2.1.7 =
* Tested up to 5.6.1 version of WordPress
* Added new premium feature: bots & web crawlers exclusions which (optionally) allow access to your site content by search engines and benefit from SEO juice even from protected / hidden content.

= 2.1.6 =
* Fixed issue with WooCommerce SPECIFIC PRODUCT (LIMITED TIME) message

= 2.1.5 =
* Added integration with Easy Digital Downloads plugin (https://wordpress.org/plugins/easy-digital-downloads/)

= 2.1.4 =
* Added support for Page Builder by SiteOrigin plugin (https://wordpress.org/plugins/siteorigin-panels/)

= 2.1.3 =
* Fixed conflict with "Yoast SEO" plugin (restricted select box was invisible on edit category page when Yoast plugin is active)
* Fixed deprecated hook notice (edit_category_form_fields)

= 2.1.2 =
* Added time limit option for WooCommerce products

= 2.1.1 =
* Fix for automatic updates of the free version (clients with previous versions need to update it manually first)

= 2.1.0 =
* Fixed bug with WooCommerce categories

= 2.0.9 =
* Fixed issue with rendering Elementor shortcodes as a restricted messages (now supports Shortcode Elementor https://wordpress.org/plugins/shortcode-elementor/, AnyWhere Elementor https://wordpress.org/plugins/anywhere-elementor/ and similar plugins)

= 2.0.8 =
* Added integration for Simple URLs plugin (https://wordpress.org/plugins/simple-urls/)
* Removed "Content available to" select box from the shop_order post type (and similar post types where the box isn't needed)
* Added global restriction for post types at once (premium version)
* Added restriction for WooCommerce shop page (which is actually product archive page shown via template)
* Various code improvements

= 2.0.7 =
* Added restriction to post / page author (and the administrators)

= 2.0.6 =
* Added admin javascript browser cache control based on the plugin's version

= 2.0.5 =
* Added white label option in the premium version. Usage: just put a define('RSC_PLUGIN_TITLE', 'Custom Name');

= 2.0.4 =
* Added new placeholders for WooCommerce (rsc_woo_product_links) and Tickera (rsc_tc_event_links) so you can now show products / events titles with links
* Small wording changes on the general page (admin area)

= 2.0.3 =
* Tested up to WordPress 5.4.1
* Fixed bug with WooCommerce post / page restrictions (when a specific product is selected)
* Fixed bug with Tickera post / page restrictions (when a specific ticket type / event is selected)

= 2.0.2 =
* IMPORTANT UPDATE: fixed the issue with saving and merging saved options

= 2.0.1 =
* Fixed fatal error in the FREE version upon activation

= 2.0 =
* Code refactoring
* Upgraded one-line text messages with the WP Editor
* Added option to hide post comments for restricted posts
* Styling changes
* Freemius integration
* Rebrended from "Restricted Content" to "Restrict"

= 1.0.4 =
* Tested for WordPress 5.3.2

= 1.0.3 =
* Removed restricted content from tc_order custom post type
* Added new hook for developers (rsc_skip_post_types)
* Fixed wp_localize_script var

= 1.0.2 =
* Updated language file

= 1.0.1 =
* Fixed issue with empty restriction messages
* Removed content availability box from Tickera ticket type screen in the admin

= 1.0 =
* First release
