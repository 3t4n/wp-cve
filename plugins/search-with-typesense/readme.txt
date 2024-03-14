=== Search with Typesense ===
Contributors: codemanas, digamberpradhan, sachyya-sachet, j__3rk, ugene
Tags: search, typesense, lightning fast, autocomplete, instant search
Requires at least: 6.2.0
Tested up to: 6.3.1
Requires PHP: 7.4
Stable tag: 1.9.7
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Lightning fast search for your WordPress site, powered by Typesense.

== Description ==

Turbocharge your sites search functionality with [Typesense](https://typesense.org/).

Create a fast search experience for your site. Give your users a search listing page or autocomplete search.

[Typesense](https://typesense.org/) is a modern, privacy-friendly, open source search engine built from the ground up using cutting-edge search algorithms, that take advantage of the latest advances in hardware capabilities.

**FEATURES**

- Lightning-fast search results in milliseconds
- Allow overriding native WordPress default search for whole site.
- Shortcodes for adding search in only specific locations.
- Hooks and filters for customizations
- Template Override for design customizations.
- Developer friendly
- Elementor widgets: Instant Search and Autocomplete

*This plugin requires API keys from [Typesense](https://cloud.typesense.org/).*

**Getting Started with Typesense**
[youtube https://www.youtube.com/watch?v=nEHCDgdsWmk]

**LINKS**

[Documentation](https://docs.wptypesense.com/)
[Typesense](https://typesense.org/)
[Typesense WordPress Site](https://typesense.codemanas.com/)
[Typesense Search for WooCommerce](https://typesense.codemanas.com/woocommerce/)
[Typesense Comparison with other platforms](https://typesense.org/typesense-vs-algolia-vs-elasticsearch-vs-meilisearch/)

**DEMO LINKS**

[Frontend Demo](https://typesense.codemanas.com/)
[Autocomplete](https://typesense.codemanas.com/autocomplete-with-typesense/)
[Instant Search](https://typesense.codemanas.com/instant-search/)

**PRO ADDONS**
Addon: [WooCommerce Addon](https://www.codemanas.com/downloads/typesense-search-for-woocommerce/)

== Installation ==
= Minimum Requirements =
* PHP 7.4 required or greater
* MySQL 5.6 or greater is recommended

= Automatic Installation =
Go to WordPress Plugins > Add New Search for "Search with Typesense"
Click Install and then activate Plugin

= Manual Installation =
If for some reason automatic installation is not possible, go to [https://wordpress.org/plugins/search-with-typesense](https://wordpress.org/plugins/search-with-typesense) and you will see the download button. Clicking download button will provide you with a zip file of the plugin then.

Go to WordPress Plugins > Add New and click upload plugin.
Click upload plugin and then add the zip file
The plugin will then be installed, then activate the plugin.

== Screenshots ==
1. Instant Search Frontend
2. Autocomplete
3. Backend Settings
4. Search Configuration

== Frequently Asked Questions ==
= What is typesense =
Typesense is an open source, typo tolerant search engine that is optimized for instant sub-50ms searches, while providing an intuitive developer experience.
You can learn more [here](https://typesense.org)

= How do I generate API Keys =
This is covered in the documentation - please see [https://docs.wptypesense.com/](https://docs.wptypesense.com/)

== Changelog ==
= 1.9.7 Jan 5, 2023 =
* Fix: Push WPCLI folder

= 1.9.6 Jan 5, 2023 =
* Fix: Fix the WP CLI indexing issues with `posts_per_page`

= 1.9.5 September 29, 2023 =
* New: WP CLI Introduced (Documentation)[https://docs.wptypesense.com/wp-cli/]
* Dev: Refactor code for showing thumbnail

= 1.9.4 September 26, 2023 =
* Dev: Ability to localize stats widget

= 1.9.3 September 13, 2023 =
* Enhancement: Added `routing` enabling argument in shortcode

= 1.9.2 September 13, 2023 =
* Enhancement: Added Selected Refinements option can be show in shortcode by adding `[cm_typesense_search post_types="book,post" columns="3" filter="show" per_page="3" sortby="off" placeholder="Search for..." selected_filters="show"]`
* Enhancement: Added Stats widget option to show number of results and time it took to show the results `[cm_typesense_search post_types="book,post" columns="3" filter="show" per_page="3" sortby="off" placeholder="Search for..." stats="show"]`
* Dev: Optimized js for better code organization.

= 1.9.1 August 22, 2023 =
* Dev: Backward compatibility for the post_date for posts that was introduced in version 1.9.0

= 1.9.0 August 21, 2023 =
Enhancement: Backward Incompatible, for posted_date will use the locale (from site language) as well as use the format described in General > Settings > Time Format.
Will require a re-index of posts.

= 1.8.5 July 28, 2023 =
Fix: Correct hit-meta not closed properly

= 1.8.4 June 12, 2023 =
Update: Update documentation link to https://docs.wptypesense.com/

= 1.8.3 June 09, 2023 =
Feature: Addons Tab Added to showcase available addons
Fix: Should be hitting debug input to verify credentials
UI/UX: Design improvements for loading and schema
Feature: Added ability to bulk delete logs
UI/UX: Improvements for notifications

= 1.8.2 May 31, 2023 =
Feature: Verify settings when credentials are entered.
Enhancement: Very minor CSS changes for Admin UI/UX

= 1.8.1 May 18, 2023 =
*Fix: Fix popup customizer post types listing not showing on select issue.
Update: Pre select the default enabled post types on popup post types option

= 1.8.0 May 08, 2023 =
* Dev: Code refactoring for JS for future development.

= 1.7.6 Apr 02, 2023 =
* Fix: Removed console logs and debugging

= 1.7.5 Apr 02, 2023 =
* Dev: Added filter for popup

= 1.7.4 Mar 22, 2023 =
* Dev: Added ability to select menuSelect as a facet

= 1.7.3 Feb 16, 2023 =
* Feature: Add hook: `cm_typesense_search_box_settings` to configure searchbox settings

= 1.7.2 Jan 24, 2023 =
* Fix: Do not show warning message for non-admin out users.

= 1.7.1 Jan 20, 2023 =
* Fix: Tab settings typo fix
* Update: Show appropriate message on frontend on error

= 1.7.0 Jan 13, 2023 =
* Enhancement: Added proper error message if node curling fails

= 1.6.9 Nov 29, 2022 =
* Enhancement: Added elementor widgets namely Instant Search and Autocomplete

= 1.6.8 Nov 11, 2022 =
* Enhancement: Added number of documents for Advanced tab

= 1.6.7 Nov 8, 2022 =
* Enhancement: Change post_author data to user display name instead of user nicename

= 1.6.6 Oct 1, 2022 =
* Fix: Fix error 1.6.5 caused that prevented settings form being saved

= 1.6.5 Sep 29, 2022 =
* New Feature: Added advanced tab - so users can see what collections have been defined on Typesense
* UI/UX: Changed responsive view for backend admin settings to be more usable.

= 1.6.4 Aug 30, 2022 =
* Feature: Added option to choose what happens when autocomplete form is submitted

= 1.6.3 Aug 29, 2022 =
* Fix: Compatibility with Typesense Search for WooCommerce added

= 1.6.2 Aug 25, 2022 =
* Feature: Added Site Info button under > Typesense > Logs to get info for debugging.

= 1.6.1 Aug 24, 2022 =
* Hit list css updated - use Grid instead of flex for consistent design
* Select2 added for customizer
* Use image_html with img src set instead of full size image

= 1.6.0 Aug 8.2022 =
* [Release Notes] (https://www.codemanas.com/search-with-typesense-v1-6-0-major-release-notes/)
* Default Instant Search UI/UX changed for tabbed multi-collection search
* Naming convention changed for HTML classes to better match with Algolia intant search
* Responsive design changes for Instant Search
* Templating Structure Redesigned

= 1.5.7 Aug 3, 2022 =
* Enhancement: Add major update notification message

= 1.5.6 July 20, 2022 =
* Enhancement: Add filter `cm_typesense_additional_search_params` to add additional parameters
* Enhancement: Add filter `cm_typesense_additional_autocomplete_params` to add additional parameters for autocomplete
* Enhancement: Chunk single index.js file into different chunks: autocomplete, instant-search and popup; and only load them when required

= 1.5.5 July 07, 2022 =
* Edge Case: Fix for edge case where sometimes the multiselect for customizer is retrieving wrong value from the database

= 1.5.4 May 20, 2022 =
* Hotfix: Categories do not have placeholder image

= 1.5.3 May 20, 2022 =
* Dev Enhancement: Overhaul code to allow multiple sites to use same cluster
* Design Enhancement: Autocomplete design enhanced to better show content/teaser text

= 1.5.2 May 19, 2022 =
* Enhancement: Show/Hide filter by option enhanced for popup
* Admin Screen: Re-structured for more clarity

= 1.5.1 May 12, 2022 =
* Minor Enhancement: Uniform desing for single column layout
* Minor Fix: Customizer update columns for both paginated and infinite pagination view

= 1.5.0 May 8, 2022 =
* Major Update: Added option to replace all search with instant search popup, infinite pagination option added, uniform styling added

= 1.4.0 April 21, 2022 =
* Dev Enhancement: Switched to action hook system to allow modification of the main instant search results template

= 1.3.3 April 6, 2022 =
* Enhancement: Hide panel if there are no relevant facets

= 1.3.2 April 4, 2022 =
* Dev Fix: Load compiled/optimized version of JS and CSS

= 1.3.1 April 4, 2022 =
* Minor Changes: Added Post Type Category as default index able field, dev fixes for taxonomy indexing

= 1.3.0 March 30, 2022 =
* Dev Feature: Hooks for before and after bulk import - added for third party compatibility
* Enhancement: Allow - no delay option for autocomplete
* Enhancement: Allow - ability to index taxonomies
* Depreciated: cm_typesense_available_post_types hook depreciated use cm_typesense_available_index_types instead

= 1.2.6 March 28, 2022 =
* Fix: Block Editor fix - sticky post no longer requires custom code
* Enhancement: Added - analytics middleware option see https://gist.github.com/digamber89/7b8bb403399bfaaf37ddab8d5b6dd570

= 1.2.5 March 14, 2022 =
* Enhancement: Added ability to add / change input delay for autocomplete

= 1.2.4 March 7, 2022 =
* Dev Enhancement: Added filter to change filter widget type for menu, rangeSlider and rangeInput

= 1.2.3 February 11, 2022 =
* Change Hijack to Replace as Hijack sounds a bit too aggressive
* Fix: Style changes to make it more extendible with premium addons
* Dev: Code refactoring for styles
* Dev Enhancement: Register script and styles before enqueuing

= 1.2.2 February 8,2022 =
* Enhancement: Log viewer updated
* Link to WooCommerce Demo added

= 1.2.1 February 7, 2022 =
* Fix: Pages not being indexed

= 1.2.0 February 6, 2022 =
* Enhancement: Ability to delete collection and re-index

= 1.1.11 February 6, 2022 =
* Dev Fix: Correctly handle special chars for Categories

= 1.1.10 February 2, 2022 =
* Fix: Singleton class not correctly defined
* Improvements: Code Refactoring

= 1.1.9 January 20, 2022 =
* Dev Enhancement: Added cm_typesense_locate_template filter to allow 3rd party developers or customization
* Enhancement: Added query_by filter to Autocomplete shortcode e.g. use as ```[cm_typesense_autocomplete query_by="post_title"]```

= 1.1.8 January 20, 2022 =
* Fix: Autocomplete shortcode not working - when Hijack WordPress search is not selected.

= 1.1.7 January 17, 2022 =
* Enhancement: Remove documents if post status changes to draft
* Dev: Ability to either skip or change bulk index query

= 1.1.6 January 14, 2022 =
* Enhancement: Add category parent terms

= 1.1.5 January 13, 2022 =
* Hotfix: Custom post types may not have been updated after version 1.1.4 enable post type validation check

= 1.1.4 January 12, 2022 =
* Dev Enhancement: Added filters to modify facets and sortby options
* Dev Fix: Remove item from enable post types if it has been removed from available post types

= 1.1.3 January 11, 2022 =
* Enhancement: Collapsible Panel for Mobile devices

= 1.1.2 January 10, 2022 =
* Enhancement: Added ability for query_by and show sticky_posts first

= 1.1.1 January 6, 2022 =
* Enhancement: Add text highlighting for autocomplete, no results provided

= 1.1.0 January 6, 2022 =
* Update: Update enabled posts if category is modified

= 1.0.2 January 5, 2022 =
* Changed text domain from codemanas-typesense to search-with-typesense

= 1.0.1 January 5, 2022 =
* Code refactoring
* Further security checks
* Log files re-ordered to show the latest first

= 1.0.0 December 24, 2021 =
* Initial Release
