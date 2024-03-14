=== Phrase TMS Integration for WordPress ===
Contributors: memsource
Tags: phrase, wpml, translation, localization, localisation, multilingual
Requires at least: 4.9
Requires PHP: 7.3
Tested up to: 6.2
Stable tag: trunk
License: GPLv2 or later

== Description ==

We’re transforming language technology, opening the door to global business so you can reach more people, make deeper connections, and drive growth. Phrase is the leading translation management system, offering a comprehensive suite of translation tools that’s intuitive to use and simple to integrate so you can focus on forming deeper connections with people across cultures. We help organizations like Uber, Shopify, Volkswagen, and thousands of others accelerate their global growth by giving people the content they need, in the language they speak.

At Phrase, we:

* Use the latest technology to translate more efficiently and accurately, supporting 500+ languages, 50+ file types, and 30+ machine translation engines.
* Manage massive volumes of translation with advanced automation, machine learning, and AI features to deliver resonant content that reflects your message, regardless of its language.
* Make localization an essential driver of business growth with the only vendor-neutral provider on the market that offers a complete translation management solution and software localization platform for developers.

= Features =

* Seamlessly translate your WordPress posts, pages, tags, categories, and custom post types
* Automatically send content for translation and track translation progress without leaving WordPress
* Compatible with a wide range of WordPress plugins including Avada, Divi, Elementor, Gutenberg, Yoast SEO, SEOPress, WPBakery Page Builder and Avia
* Boost productivity and reduce costs with AI-powered machine translation. Phrase TMS supports 30+ machine translation engines and automatically selects the optimal engine for your content.
* Live In-context Preview gives translators proper context while they’re working. Translators can see exactly how their translations will look on the page, reducing feedback loops and improving translation quality
* After building a custom shortcode in a third party plugin, add it to your WordPress site with our shortcode editor and translate shortcode content.

The Phrase TMS Integration supports both WPML and MultilingualPress.

The integration is available with the Ultimate and Enterprise Phrase TMS plans. [Contact us](https://phrase.com/demo/) for licensing information, or to schedule a demo.

== Changelog ==

= 4.6.1 =
*Release Date - 1 Mar 2024*

* Added support for the ACF plugin installed via MU-Plugins

= 4.6.0 =
*Release Date - 6 Dec 2023*

* Added option to use the same permalink for source and target posts

= 4.5.1 =
*Release Date - 8 Aug 2023*

* Fixed UI bug in the Gutenberg block table

= 4.5.0 =
*Release Date - 4 Aug 2023*

* Added option to keep URLs unchanged during export from Phrase TMS

= 4.4.0 =
*Release Date - 30 Jun 2023*

* Redesigned the block management page
* URLs in some blocks will point to the translated post when exported from Phrase

= 4.3.1 =
*Release Date - 22 Jun 2023*

* Allowed shortcodes containing hyphens
* Added ACF PRO compatibility

= 4.3.0 =
*Release Date - 2 May 2023*

* An asterisk can be used to specify blocks created by ACF Repeater
* Fixed WP-CLI compatibility
* Fixed case where translations were not linked to the source content

= 4.2.0 =
*Release Date - 23 Feb 2023*

* Added support for custom fields linked to terms (categories, tags and custom taxonomies)
* Added support for custom fields created by the ACF plugin
* Fixed issue with escaped quotes in a post content

= 4.1.0 =
*Release Date - 16 Dec 2022*

* WordPress 6.1 compatibility

= 4.0.1 =
*Release Date - 27 Sep 2022*

* Fixed a bug where exporting a page led to an error

= 4.0.0 =
*Release Date - 27 Sep 2022*

* Memsource becomes Phrase

= 3.5.2 =
*Release Date - 2 Sep 2022*

* Fixed the way of starting database migrations

= 3.5.1 =
*Release Date - 26 Aug 2022*

* Fixed Elementor plugin dependency

= 3.5.0 =
*Release Date - 23 Aug 2022*

* Added SEOPress support
* All taxonomies are now transferred to the translation
* The lowest supported PHP version is now 7.3
* Various bug fixes

= 3.4.3 =
*Release Date - 23 Jun 2022*

* WordPress 6.0 compatibility
* Export text in square brackets for translation

= 3.4.2 =
*Release Date - 25 May 2022*

* Fixed export of Elementor field "Toggle".

= 3.4.1 =
*Release Date - 12 May 2022*

* Fixed issue with exporting custom fields.

= 3.4.0 =
*Release Date - 12 Apr 2022*

* The excerpt field is now exported for translation.
* Fixed multiple notifications appearing in Memsource when using APC.
* Improved Gutenberg blocks parsing.

= 3.3.2 =
*Release Date - 20 Jan 2022*

* Improve Gutenberg blocks parsing.
* Do not export HTML comments to Memsource.

= 3.3.1 =
*Release Date - 11 Jan 2022*

* Fix a case when Gutenberg block is not exported for translation.

= 3.3.0 =
*Release Date - 6 Jan 2022*

* Content authors can pass the list of target languages to Memsource via a dedicated field.
* Support translation workflows.

= 3.2.5 =
*Release Date - 20 Dec 2021*

* Optimize post revisions loading

= 3.2.4 =
*Release Date - 14 Dec 2021*

* Fix another case when Gutenberg block is not exported for translation
* Improve logging, add the ability to download log file in wp-admin

= 3.2.3 =
*Release Date - 29 Nov 2021*

* Fix TOO_LARGE_INPUT error caused by long shortcodes

= 3.2.2 =
*Release Date - 26 Nov 2021*

* Fix a case when Gutenberg block is not exported for translation
* Load wp-config.php placed in a parent directory

= 3.2.1 =
*Release Date - 26 Oct 2021*

* Fixed bug when shortcode was not converted to Memsource tag
* Fixed bug when Gutenberg Block was not imported to Memsource

= 3.2.0 =
*Release Date - 8 Oct 2021*

* Support Elementor
* Copy author, categories, and featured image from source to target
* Links in translated posts use proper language

= 3.1.0 =
*Release Date - 1 Sep 2021*

* Support Gutenberg Blocks
* Support Reusable Blocks
* Support Yoast SEO

= 3.0.2 =
*Release Date - 13 Jul 2021*

* Fix issue with slug uniqueness

= 3.0.1 =
*Release Date - 13 Jul 2021*

* Improve WpBakery compatibility

= 3.0.0 =
*Release Date - 28 May 2021*

* Support MultilingualPress - another multilingual plugin for WordPress.
* Improve parsing of nested shortcodes.
* Increase required PHP version to 7.0.

= 2.15 =
*Release Date - 17 Mar 2021*

* Match shortcodes in live preview properly.

= 2.14 =
*Release Date - 4 Jan 2021*

* Allow shortcodes editing.

= 2.13 =
*Release Date - 16 Nov 2020*

* Allow <style> tags in transations.

= 2.12.1 =
*Release Date - 16 Nov 2020*

* Support in-context preview for drafts.

= 2.12 =
*Release Date - 10 Nov 2020*

* Support in-context preview for drafts.

= 2.11 =
*Release Date - 29 Oct 2020*

* Fix compatibility with WordPress 5.5 and PHP 7.4.
* Fix compatibility with WPML Translation Management plugin.

= 2.10 =
*Release Date - 19 Aug 2020*

* Add more detailed info to logs.

= 2.9 =
*Release Date - 12 Jun 2020*

* Do not translate custom fields keys.

= 2.8 =
*Release Date - 10 Jun 2020*

* Support Multisite installation.

= 2.7.1 =
*Release Date - 16 Apr 2020*

* Fix pages list created by Muffin page builder.

= 2.7 =
*Release Date - 31 Jul 2019*

* Fix decoding of translated category.

= 2.6 =
*Release Date - 28 May 2019*

* Add pagination to the translatable content page.
* Create valid link in the JSON response.

= 2.5 =
*Release Date - 1 April 2019*

* Change contact e-mail address.

= 2.4.6 =
*Release Date - 28 January 2019*

* Fixed filtering of empty posts.

= 2.4.5 =
*Release Date - 20 December 2018*

* Fixed processing of shortcodes.
* Allowed translation of hidden languages.

= 2.4.4 =
*Release Date - 22 November 2018*

* Fixed an issue with listing posts without content.
* Added more information to the debug log.

= 2.4.3 =
*Release Date - 11 September 2018*

* Fixed an issue with Fusion Builder and translation upload.

= 2.4.2 =
*Release Date - 2 July 2018*

* Fixed an issue with Fusion Builder and Base64 encoded content.

= 2.4.1 =
*Release Date - 5 June 2018*

* Fixed a rare bug that affected loading shortcode definitions.

= 2.4 =
*Release Date - 31 May 2018*

* Added Avia Layout Builder support.

= 2.3.1 =
*Release Date - 29 May 2018*

* Added more data to debug visual editor issues.

= 2.3 =
*Release Date - 18 May 2018*

* Memsource Connector supports WordPress custom types now.

= 2.2.2 =
*Release Date - 4 May 2018*

* Fixed another database issue.

= 2.2.1 =
*Release Date - 17 April 2018*

* Fixed an occasional database issue.

= 2.2 =
*Release Date - 4 April 2018*

* Added a new admin page to select translatable custom fields.
* Fixed an issue with certain shortcode definitions.

= 2.1.1 =
*Release Date - 23 February 2018*

* Fixed max length of language mapping codes.

= 2.1 =
*Release Date - 21 February 2018*

* Added "Language mapping" page to map WPML locale codes to their Memsource counterparts.
* Fixed several bugs in visual editor shortcode parsers.

= 2.0.2 =
*Release Date - 27 November 2017*

* Fixed "Index column size too large" issue on some MySQL configurations using utf8mb4 character set.

= 2.0.1 =
*Release Date - 8 November 2017*

* Fixed an occasional bug with loading shortcode definitions.

= 2.0 =
*Release Date - 7 November 2017*

* The plugin now works with categories, tags and visual editor shortcodes (Visual Composer, Avada, Divi). Also the user interface was improved.

= 1.2.3 =
*Release Date - 24 August 2017*

* Fixed a small bug with including a third party library.

= 1.2.2 =
*Release Date - 18 August 2017*

* Added a simple logging system to debug the plugin and send reports.

= 1.2.1 =
*Release Date - 25 April 2017*

* Fixed the WPML plugin detection.

= 1.2 =
*Release Date - 15 November 2016*

* Added options to list posts with selected statuses (publish, draft).
* Added options to insert translations with a selected status (publish, draft).

= 1.1.3 =
*Release Date - 20 October 2016*

* Fixed list of all posts.

= 1.1.2 =
*Release Date - 15 October 2016*

* Fixed authentication token generator.

= 1.1.1 =
*Release Date - 12 October 2016*

* Fixed storing ID of the last processed post.

= 1.1 =
*Release Date - 12 October 2016*

* List and Get methods return posts with the last revision content.
* Translation can be inserted as a draft.
* Minor UI improvements.
* A better description added to readme.txt

= 1.0.4 =
*Release Date - 4 October 2016*

* Added readme.txt.

= 1.0.3 =
*Release Date - 3 October 2016*

* Fixed a bug with the last processed post ID.

= 1.0.2 =
*Release Date - 1 October 2016*

* Fixed a bug at JSON response of the translation endpoint.

= 1.0.1 =
*Release Date - 29 September 2016*

* Added i18n strings.

= 1.0 =
*Release Date - 28 September 2016*

* Initial version.
