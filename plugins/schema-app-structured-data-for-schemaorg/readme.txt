=== Schema App Structured Data ===

Contributors: vberkel
Plugin Name: Schema App Structured Data
Tags: schema, structured data, schema.org, rich snippets, json-ld
Author URI: https://www.hunchmanifest.com
Author: Mark van Berkel (vberkel)
Requires at least: 4.1
Requires PHP: 5.4
Tested up to: 6.4
Stable tag: 1.23.2
License: GPL2
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Get Schema.org structured data for all pages, posts, categories and profile pages on activation. Use Schema App to customize any Schema Markup. 

== Description ==

**What Markup does Schema App WordPress Plugin Create?**

The Schema App WordPress plugin automatically creates [schema.org](https://schema.org/) markup for all your pages, posts, author and category content leveraging information that already exists in your WordPress website. Just activate the plugin, add your logo and the name of your business, and your content is optimized to be fully understood by search engines resulting in higher traffic, higher click-through rates and more. The plugin also provides all three Google Site Structure features including Breadcrumbs, Sitelinks Searchbox and Your Site Name in Results.

**What type of markup is automatically created with this plugin?**

* Page : http://schema.org/Article
* Post : http://schema.org/BlogPosting
* Search : http://search.org/SearchResultsPage
* Author : http://schema.org/ProfilePage
* Category : http://schema.org/CollectionPage
* Tag : http://schema.org/CollectionPage
* Blog : http://schema.org/Blog
* BreadcrumbList : http://schema.org/BreadcrumbList
* VideoObject: https://schema.org/VideoObject
* WebSite : http://schema.org/WebSite

Customization of Page and Post schema markup can be done through default settings (e.g. posts can default to NewsArticle) as well as by directly editing the generated JSON-LD for each page.

[Google's Powerful Video Features](https://developers.google.com/search/docs/appearance/structured-data/video) are added automatically for all videos hosted by YouTube and Vimeo. 

**Advanced WordPress plugin**

Do you want even better schema markup results than what the free Schema App plugin offers? To achieve this you need to optimize your whole website with schema markup.

Schema App enables marketers to create custom schema.org markup for a website’s Local Business, Organization, Services, Reviews, Contact Page and more. Schema App has the complete schema.org vocabulary, requires no JSON-LD coding, and helps you do ongoing Schema Markup maintenance when Google changes their recommendations. 

Schema App Pro subscriptions include support from our experts in schema markup and access to the Schema App Advanced WordPress Plugin. The Advanced Plugin compliments this base Schema Plugin by adding capabilities including:

* WooCommerce Products
* Link Category & Tag Definitions to Wikipedia, Wikidata
* Page & Post Review Widget
* Custom Post & Field Mapping

Want to learn more about how to get the advanced WordPress Plugin? Learn more [here](https://www.schemaapp.com/solutions/wordpress-plugin/).

== Installation ==

**Free Plugin Instructions:**

To use the free plugin here are the steps you need to take to install:

1. First, click the download button above.
2. Once you have the downloaded zip file navigate to Plugins > Add New in WordPress.
3. Next click Upload Plugin in the top left corner and select the zip file you downloaded previously. 
4. After you click Install Now you can activate the plugin.
5. Now that you have activated the plugin navigate to Plugins > Installed plugins.
6. Under your installed plugins look for the Schema App Structured Data plugin and click Settings.
7. Configure the Settings to whatever best fits your site.

== Frequently Asked Questions ==

You'll find the [FAQ on SchemaApp.com](https://www.schemaapp.com/solutions/wordpress-plugin/).

== Screenshots ==

1. Schema App Tools Admin
2. Settings Menu Navigation
3. Schema.org Page Meta Box
4. Schema.org Editor UI
5. Link to Validation

== Changelog ==

= 1.23.2 =
Release Date - 08 February 2024

- Fix, Added nonce check to limit unauthorised access.

= 1.23.1 =
Release Date - 20 December 2023

- Fix, Added missing capability check to limit unauthorised access.

= 1.23.0 =
Release Date - 7 December 2023

- Improve, Added mainEntity~Person to ProfilePage to qualify for rich results
- Improve, Added dateCreated property to ProfilePage
- Fix, Use ISO-8601 format for all dates in ProfilePage
- Fix, Suppress warnings from DOMDocument::loadHTML

= 1.22.6 =
Release Date - 20 November 2023

- Fix, Added filtering of invalid image URLs

= 1.22.5 =
Release Date - 31 October 2023

- Fix, Added missing capability check in functions to limit unauthorised access.  

= 1.22.4 =
Release Date - 5 October 2023

- Fix, Added missing capability check on page_init function to prevent unauthorized loss of data
- Fix, Improve error handling for video markup

= 1.22.3 =
Release Date - 25 August 2023

- Improve, Tested up to WordPress 6.3

= 1.22.2 =
Release Date - 28 July 2023

- Fix, Check for source only after confirming response is not a WP_Error in getResourceFromAPI routine

= 1.22.1 =
Release Date - 20 July 2023

- Fix, Added missing check for permalink structure when formatting in get permalink routine

= 1.22.0 =
Release Date - 27 April 2023

- Feature, added setting for automatically clearing WPEngine's page cache for faster deployment times

= 1.21.2 =
Release Date - 06 April 2023

- Fix, proper error handling with try-catch blocks.
- Fix, removed use of null coalescing operator for compatibility with versions of PHP < 7.0.
- Fix, use of array instead of stdClass for rendering markups on the page.

= 1.21.1 =
Release Date - 22 March 2023

- Fix, Revert changes added in 1.21.0

= 1.21.0 =
Release Date - 22 March 2023

- Feature, Added a data-source attribute to the JSON-LD output that indicates the source of markup from Schema App.

= 1.20.2 =
Release Date - 08 February 2023

- Fix, Added missing parameters and string formatting in get permalink routine

= 1.20.1 =
Release Date - 17 January 2023

- Fix, Disable default markup when rendering Highlighter JS markup on the client-side

= 1.20.0 =
Release Date - 19 December 2022

- Feature, Added compatibility for Highlighter JS client-side rendering of markup

= 1.19.1 =
Release Date - 13 December 2022

- Fix, removed use of null coalescing operator for compatibility with versions of PHP < 7.0

= 1.19.0 =
Release Date - 18 November 2022

- Feature, for paid accounts send generated markup to SchemaApp for deployment monitoring

= 1.18.0 =
Release Date - 27 October 2022

- Improve, Updated get permalink routine, cache, and webhooks to allow configurable URL

= 1.17.15 =
Release Date - 23 May 2022

- Fix, Cache REST API Account Id validation for Highlighter JS

= 1.17.14 =
Release Date - 27 January 2022

- Fix, WooCommerce notices not showing on my account and checkout pages

= 1.17.13 =
Release Date - 12 January 2022

- Fix, Double escaping of admin notices
- Fix, Removed extra comma from add_settings_field function call

= 1.17.12 =
Release Date - 16 December 2021

- Improve, Added ability to delete Transient cache of API responses

= 1.17.11 =
Release Date - 9 December 2021

- Fix, Markup issue due to merging of Editor and Highlighter markup

= 1.17.10 =
Release Date - 16 November 2021

- Improve, Updated YouTube URL matching for URLs without protocol
- Improve, Merging of Editor and Highlighter markup
- Fix, Late escaping of data for display

= 1.17.9 =
Release Date - 8 October 2021

- Fix, Updated sanitization when saving data and escaping of HTML data for displaying

= 1.17.8 =
Release Date - 29 August 2021

- Fix, Added sanitization when saving data and displaying links
- Improve, Updated Admin bar Test Schema to use Google Rich Results and added Schema.org validator

= 1.17.7 =
Release Date - 1 August 2021

- Fix, Added proper Content-Type header to Linked Open Data request
- Improve, Added Rest API Account Id validation

= 1.17.6 =
Release Date - 9 July 2021

- Improve, Updated get permalink routine
- Improve, Added static method for getting post content to avoid multiple the_content filter
- Improve, Updated plugin admin setting page

= 1.17.5 =
Release Date - 9 February 2021

- Fix, Added required parameter permission_callback to Rest API

= 1.17.4 =
Release Date - 12 December 2020

- Improve, Updated get permalink routine for single Page/Post
- Improve, Debug info enhancement
- Fix, Yoast SEO meta description

= 1.17.3 =
Release Date - 3 December 2020

- Fix, URL vulnerability in Linked Open Data
- Fix, Encoding of API resource URL

= 1.17.2 =
Release Date - 17 November 2020

- Improve, Added option to enable/disable background sync of Schema Editor markup

= 1.17.1 =
Release Date - 11 November 2020

- Improve, Caching of API response when no schema data is available for a resource

= 1.17.0 =
Release Date - 3 November 2020

- Feature, Schema data fetching from API is now handled by WP Cron to improve page load time
- Improve, License check message for WooCommerce plugin
- Improve, Updated get permalink routine to use $wp for detecting correct urls
- Improve, Optimized video parsing routine

= 1.16.2 =
Release Date - 23 August 2020

- Fix, Archive permalinks for Schema API resource

= 1.16.1 = 
Release Date - 20 August 2020

- Improve, Updated Yoast SEO filter to remove schema markup
- Improve, Added filter for adding data sources to API
- Improve, Debug info for logged in users

= 1.16.0 = 
Release Date - 21 July 2020

- Feature, Added global option to enable or disable video schema markup
- Fix, Webhok fix for delete markup
- Fix, Updated all Schema.org context to https

= 1.15.4 = 
Release Date - 22 May 2020

- Fix, Updated BreadcrumbList markup based on Google's schema

= 1.15.3 = 
Release Date - 30 April 2020

- Fix, hunch_schema_meta_box_post_types filter error

= 1.15.2 = 
Release Date - 29 April 2020

- Improve, Added back global options for Post/Page schema markup
- Improve, Added hunch_schema_meta_box_post_types filter to show schema metabox on CPT
- Fix, Optimize debug log file size
- Fix, Vimeo private video markup issue

= 1.15.1 = 
Release Date - 24 March 2020

- Fix, Rollback global options for Post/Page schema markup
- Fix, Webhook check for different home and site url

= 1.15.0 = 
Release Date - 18 March 2020

- Feature, Added global options for Post/Page to enable or disable schema markup
- Fix, Account Id issue for fetching markup from API

= 1.14.4 = 
Release Date - 24 September 2019

- Improve, Webhook by caching all schema markup and better matching of permalinks

= 1.14.3 = 
Release Date - 17 September 2019

- Fix, Updating of markup cache from Webhook event

= 1.14.2 = 
Release Date - 12 September 2019

- Fix, Transient cache issue
- Improve, added debug feature to quickly resolve common issues on client website

= 1.14.1 = 
Release Date - 9 September 2019

- Fix, Remove comment schema setting for archive pages

= 1.14.0 = 
Release Date - 29 July 2019

- Feature, Added webhook support for schema data events which will update markup cache in realtime
- Improve, WC migrate license web service

= 1.13.0 = 
Release Date - 30 May 2019

- Feature, Added HunchSchema_Thing markup filters `hunch_schema_thing_markup_` permalink, excerpt, image, videos, tags, comments, author, publisher
- Improve, Switched API calls to api.schemaapp.com

= 1.12.1 = 
Release Date - 16 May 2019

- Fix, PHP variable notice

= 1.12.0 = 
Release Date - 8 May 2019

- Feature, Add support for Featured Video Plus

= 1.11.4 = 
Release Date - 25 April 2019

- Fix, Site Name link in plugin settings (feature deprecated)
- Fix, blogPosting property for Blog
- Improve, added @id to data items

= 1.11.3 = 
Release Date - 17 April 2019

- Improve, Yoast SEO filter to remove schema markup

= 1.11.2 = 
Release Date - 17 April 2019

- Fix, Temporary fix for error caused by Yoast SEO v11 schema changes

= 1.11.1 = 
Release Date - 1 April 2019

- Fix, Linked Open Data URL parameter
- Improve, Added Linked Open Data link tag in header

= 1.11.0 = 
Release Date - 19 November 2018

- Feature, use Yoast SEO's description if available
- Feature, Schema markup for Vimeo videos
- Info, tested on WP 5 beta for Gutenberg compatibility

= 1.10.2 = 
Release Date - 15 October 2018

- Fix, VideoObject markup generated from Youtube images
- Improve, Use #Class based @id's to prevent collisions

= 1.10.1 =
Release Date - 20 August 2018

- Fix, Yoast Wordpress SEO breadcrumblist @id collisions

= 1.10.0 =
Release Date - 18 July 2018

- Feature, add support for WPML urls
- Feature, add BlogPost to include article content, make comments optional
- Fix, error with AMP schema, collision with Glue for Yoast SEO AMP
- Fix, CollectionPage use default schema type

= 1.9.10 = 
- Fix, loading double encoded Cyrillic characters in URL lookup

= 1.9.9 =
- Feature, add setting for schema in header or footer
- Fix, Advanced Plugin Rating Widget Javascript enqueue priority

= 1.9.8 =
- Fix, Ignore cached null returned

= 1.9.7 = 
- Fix, Improve cached data checking 

= 1.9.6 = 
- Fix, WooCommerce Activation error
- Fix, Breadcrumb List Item, unique @id
- Fix, CollectionPage items check for and use configured default
- Improve, add low priority to hunch_schema_add function, behind other JS

= 1.9.5 = 
- Fix, API error in PHP versions < 5.4

= 1.9.4 = 
- Fix, caching when no Schema App data found

= 1.9.3 = 
- Improve, switch remote schema lookup to faster Schema Delivery Network (CDN)
- Improve, move JSON-LD output to wp_footer for faster loading pages
- Feature, add filter for Wordpress SEO to remove WebSite, Company and Person data items

= 1.9.2 = 
- Fix, YouTube Video warning

= 1.9.1 =
- Fix, Warning for pages without post data
- Fix, JSON-LD Editor ignores input with script element
- Fix, Settings set as Default BlogPosting

= 1.9.0 =
- Feature, VideoObject markup for YouTube videos in Pages and Posts
- Feature, Page and Posts Default schema class options
- Improve, Settings Page
- Fix, datePublished change to improve localization

= 1.8.1 =
- Fix, Genesis deactivation settings
- Fix, Article Image Missing for Blog Page

= 1.8.0 =
- Feature, AMP structured data support to improve on the WP /amp/ page’s structured data

= 1.7.7 = 
- Fix, improve compatibility with Advanced
- Info, tested on WP 4.8

= 1.7.6 = 
- Fix, activation improvements, new hooks

= 1.7.5 = 
- Fix, adjust filter 'hunch_schema_markup', action 'hunch_schema_markup_render'

= 1.7.4 = 
- Fix, WooCommerce activation

= 1.7.3 = 
- Feature, integration point for Schema App Advanced
- Fix, cannot access property PHP notice

= 1.7.2 =
- Feature, For Page markup add name, url, comment, commentCount
- Fix, Improve Linked Data output

= 1.7.1 =
- Fix, Server configuration
- Fix, Missing description, use own method

= 1.7.0 = 
- Feature, Microdata filter option
- Fix, communication with custom Schema App markup. Try Curl first otherwise use file_get_contents
- Fix, Simplify Activation Sequence
- Documentation, improve marketing copy, instructions

= 1.6.1 =
- Fix, Server Errors on some web server

= 1.6.0 = 
- Feature, Add Linked Data Support
- Feature, Tag Pages, add CollectionPage schema markup
- Fix, Errors with Javascript URL, PHP Notices
- Fix, Publisher details missing from list
- Fix, Admin notice links

= 1.5.1 =
- Fix, activation sequence

= 1.5.0 =
- Feature, Improve Schema App speed by storing custom data in WP as transient data
- Feature, Genesis Framework Filtering options 
- Fix, Show Page & Post Description default in admin
- Fix, Setting page notice for default image

= 1.4.2 =
- Feature, Default Article Image Object option
- Fix, Improve custom markup form feedback

= 1.4.1 =
- Fix, Publisher details for Article, BlogPosting

= 1.4.0 = 
- Feature, provide in post editing for custom schema markup

= 1.3.4 = 
- Fix, error with custom post types for WebSite markup

= 1.3.3 = 
- Feature, add wordCount, split keyword many properties

= 1.3.2 =
- Fix, Add to BlogPosting default needed switch to HTTPS

= 1.3.1 = 
- Fix, BreadcrumbList @id conflicts

= 1.3.0 = 
- Feature, Website for Google Site Search Box
- Feature, Website for Google Site Name
- Feature, Filter to disable markup from PHP usable in Themes, Templates
- Fix, BreadcrumbList error on other post types

= 1.2.1 = 
- Fix, error with access settings

= 1.2.0 =
- Feature, BreadcrumbList for Page & Posts
- Feature, Meta Box Layout Improvement
- Fix, Javascript conflict for admin sections elements

= 1.1.4 = 
- Fix, Show Custom Markup for Latest Blog Homepages

= 1.1.3 = 
- Fix, Author details on Post edit screen
- Fix, Loading of assets on plugin settings page for SSL site
- Fix, jQuery conflict on Post edit screen
- Feature, Added link in Toolbar to test markup

= 1.1.2 = 
- Fix, Javascript version to force reload

= 1.1.1 = 
- Fix, Add to Default Markup button

= 1.1.0 = 
- Feature, extend posts and page markup
- Documentation, improve setup page instructions and descriptions

= 1.0.0 = 
- Feature, Pages option for more specific types
- Feature, Disable markup option
- Feature, Author's ProfilePage improvement
- Feature, Add 10 comments, total commentCount to blogPosting, Blog and Category pages
- Feature, BlogPosting add keywords using tags
- Feature, Add the Blog page as type schema.org/Blog

= 0.5.9 = 
- Feature, Default markup if no featured image set add first image in content
- Fix, Publisher logo fallback markup
- Fix, Canonical URL check with get_permalink

= 0.5.8 = 
- Fix, Improve Category (CollectionPage) data
- Documentation, Improve Quick Guide and Settings instructions

= 0.5.7 = 
- Fix, Publisher image dimensions
- Fix, Author name for Pages
- Fix, API results filter null

= 0.5.6 = 
- Feature, Rename menu item 'Schema Settings' as 'Schema App'
- Feature, Admin Settings redesign as tabs
- Feature, Tab for Quick Guide
- Feature, License Tab for enabling WooCommerce plugin extension

= 0.5.5 = 
- Fix, Setting Publisher Image Upload
- Feature, Add Admin Notices
- Security, Prevent scripts being accessed directly

= 0.5.4 = 
- Fix for Publisher Logo Upload

= 0.5.3 = 
- Fix Editor JSON-LD Preview

= 0.5.2 = 
- Timeout after 5 seconds
- Tested up to WP 4.4.1

= 0.5.1 = 
- Suppress Warning when no content found

= 0.5.0 = 
- Extend Page and Post Markup for Accelerated Mobile Pages

= 0.4.4 = 
- Plugin Description Update
- Fix Meta Box Update (Create) Link

= 0.4.3 = 
- Fix Meta Box Update Link

= 0.4.2 = 
- Fix Category page error

= 0.4.1 = 
- Fix PHP Warning from empty Graph ID

= 0.4.0 = 
- Add Author, Category and Search page types
- Show formatted and default markup in Meta Box
- Change date formats to ISO8601
- Code refactoring
- Add Banner and Icon

= 0.3.3 = 
- Fixes to getResource routine

= 0.3.2 = 
- Fix PHP warning

= 0.3.1 = 
- Fix server file_get_contents warning

= 0.3.0 =
- When no data found in Schema App, add some default page and post structured data

= 0.2.0 =
- Add Post and Page Edit meta box
- Server does caching, switch from Javascript to PHP API to retrieve data for header

= 0.1.0 =
- First version 

== Upgrade Notice ==

= 1.17.15 =
- Fix Cache REST API Account Id validation for Highlighter JS
