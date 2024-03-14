=== Plugin Name ===
Contributors: coleds
Donate link: https://checkout.square.site/merchant/CGD6KJ0N7YECM/checkout/BN3726JNC6C6P6HL3JKNX3LC
Tags: SEO, meta, meta keywords, mera description, meta title, woocommerce seo, post, local seo, search engine, open graph, optimization, Google, google webmaster tools, analytic, analytics, analytics 4, readability, facebook, twitter, Bing, Yandex, custom post types, custom post type, custom posts, custom post, sitemap, import rank math, import all in one seo, import yoast, 
Requires at least: 4.6.2
Tested up to: 6.4
Stable tag: 2.0.26
License: GPLv3
License URI: http://www.gnu.org/licenses/gpl.html
Requires PHP: 5.5.6

Allows the modification of META titles, descriptions and keywords for all pages and posts. Also allows for default setting for of META title, description, and keywords for the homepage (under Settings -> Simple SEO), optimizes your Wordpress blog for Search Engines (Search Engine Optimization). Includes Sitemap generation, Google webmaster tools (site verification), Google analytic, Bing verification, Yandex verification, Baidu verification, Twitter and Facebook! 

== Description ==

* Nonce Security!
* Generates META tags automatically.
* Works out-of-the-box. Just install!
* You can override any title and set any META description and any META keywords you want!
* Google Analytic 4!
* Google Webmaster Tools!
* Bing verification & Yandex verification!
* Twitter and Facebook customization!
* Quickedit SEO titles and descriptions!
* Import Yoast SEO data!
* Import Rank Math SEO data!
* Import All In One SEO data!
* Sitemaps!
* Supports custom post types!

== Installation ==

1. Upload the plugin files to the `/wp-content/plugins/cds-simple-seo` directory, or install the plugin through the WordPress plugins screen directly.
2. Activate the plugin through the 'Plugins' screen in WordPress.
3. Use the Settings -> Simple SEO screen to configure the sitemap, the META info for the homepage, Google webmaster tools, Google analytic, etc.

If upgrading, please back up your database first!

== Frequently Asked Questions ==

Please email dave@coleds.com with any questions.

Q: How does front page title and description work.

A: The default title and description will be used under settings. If the front page, or blog page are used, then those pages meta information will be used.

== Screenshots ==

== Changelog ==

= 2.0.0 =

Release Date: November 22nd, 2022

* Complete code rewrite to namespaces and OOP
* Added SEO to tags

= 2.0.11 =

Release Date: November 23rd, 2022

* Added options to use the posts featured image for FB and TW by default.
* If FB or TW image is added, it will use that instead of the featured image.
* Fixed a bug that was preventing the media loader from working in Elementor.
* More translation options with the use of __() instead of just echoing. More coming.

= 2.0.12 =

Release Date: Jan 11th, 2023

* Support for WooCommerce category and tag meta title and description.

= 2.0.13 =

Release Date: Jan 25th, 2023

* text domains added to __() for translations.

= 2.0.14 =

Release Date: Feb 1st, 2023

* more text domains added to __() for translations.
* Sitemaps added back, with the option to select post types
* og:image:url added to fix Facebook bug.

= 2.0.15 =

Release Date: Feb 2nd, 2023

* in_array() bool value fix for sitemap post type selections.
* I am aware of the sitemap select not working, you can still generate a sitemap, or delete one using the buttons.

= 2.0.16 =

Release Date: Feb 2nd, 2023

* Bug fix. Attempt to read property "ID" on null.

= 2.0.17 =

Release Date: Feb 3rd, 2023

* Bug fix. Sitemap causing redirect when editing, publishing or deleting.

= 2.0.18 =

Release Date: Feb 6th, 2023

* Bug fix. Sitemap causing was throwing an error when deleting a page if post_types are undefined.

= 2.0.19 =

Release Date: Feb 27th, 2023

* Bug fixes. Sitemap, and title error.

= 2.0.21 =

Release Date: Feb 27th, 2023

* 6.2.2 update
* Change og:type to website
* prepping for txonomy robots noindex, nofollow

= 2.0.22 =

Release Date: Aug 10th, 2023

* 6.3 update
* Google site verification meta tag added.
* Tags, Categories no index, follow added.
* Daniel Roth, Thank you! Added title filter, spacing issues fixed, along with some other changes.

= 2.0.23 =

Release Date: Sept 21st, 2023

* Modifications to title and how its generated courtesy of Daniel Roth.

= 2.0.24 =

Release Date: October 31st, 2023

* Google Analytic tag fix.
* CSRF Issue isa  falkse positive: Recommend:
* - https://content-security-policy.com/examples/htaccess/
* - https://web.dev/articles/csp

= 2.0.25 =

Release Date: Nov 1st, 2023

* Updated URL's on options page to use wp_nonce_url(); without this it was triggering a CSRF vulnerability.

= 2.0.26 =

Release Date: Nov 2st, 2023

* Added nonce verification for options; sitemap creation, deletion, importing


