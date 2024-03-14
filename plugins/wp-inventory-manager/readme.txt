=== WP Inventory Manager ===
Contributors: wpinventory.com
Tags: inventory, inventory manager
Requires at least: 3.5.0
Tested up to: 6.3
Stable Tag: 2.2.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

== Description ==

Manage products, equipment, and more in your WordPress website.  Perfect for car dealers, art collectors, parts dealers, etc.

= Free Version =
This is the free version of plugin, which comes with an expansive array of inventory management feature.

= Pro Version =
* You may purchase the Pro version of WP Inventory by going to the [WP Inventory Pro Page](https://www.wpinventory.com/wp-inventory-license/)

= Add-Ons Available with WP Inventory Pro  =
* [Advanced Inventory Manager](https://www.wpinventory.com/downloads/add-advanced-inventory-manager/)
* [Import and Export](https://www.wpinventory.com/downloads/wp-inventory-import-and-export/)
* [Ledger](https://www.wpinventory.com/downloads/wp-inventory-ledger/)
* [Reserve Cart](https://www.wpinventory.com/downloads/add-reserve-cart/)
* [Bulk Item Manager](https://www.wpinventory.com/downloads/add-on-bulk-item-manager/)
* [Advanced User Control](https://www.wpinventory.com/downloads/advanced-user-control/)
* [Location Manager](https://www.wpinventory.com/downloads/add-on-locations-manager/)
* [Advanced Search](https://www.wpinventory.com/downloads/add-on-advanced-search/)
* [Per Item Low Quantity Notifications](https://www.wpinventory.com/downloads/add-on-notifications/)

= Support =
**All support requests are handled through our website.**
If you have a support request, we are **happy to help**, but you need to submit your request here:
[https://www.wpinventory.com/support/](https://www.wpinventory.com/support/) (This is the only way we are notified of your support request). And as noted above, support is for licensed users.

* Supports multiple categories
* Fully customizable labels
* Templating system makes customization easy
* Choose what fields you want to use and label them as needed
* Uses separate database tables for faster database access
* Developer friendly with hooks, filters, and utility functions

[youtube https://www.youtube.com/watch?v=3a72VtNFmWw]


= Tested on =
* Mac Firefox 	:)
* Mac Safari 	:)
* Mac Chrome	:)
* PC Safari 	:)
* PC Chrome	    :)
* PC Firefox	:)
* iPhone Safari :)
* iPad Safari 	:)
* PC ie7		:S

= Website =
[https://www.wpinventory.com](https://www.wpinventory.com/)

= Documentation =
* [Getting Started](https://www.wpinventory.com/wp-inventory-documentation/)
* [Support](https://www.wpinventory.com/support/)


= Bug Submission and Support =
[https://www.wpinventory.com/support/](https://www.wpinventory.com/support/)

== Installation ==

1. Upload ‘wpinventory’ to the '/wp-content/plugins/' directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Click on the new menu item “WP Inventory” and follow the fast-start directions.
4. Watch this tutorial video to get your inventory up and running in under 10 minutes:  https://www.youtube.com/watch?v=3a72VtNFmWw


== Frequently Asked Questions ==

= Q. I have a question =
A. You’re going to want to visit our website for documentation and support: https://www.wpinventory.com/documentation/


== Screenshots ==

1. Dashboard list of items.

2. Inventory categories.  Add as many as you like.

3. Status page.  Useful to help you understand the health of your system.  Check this page if you are having problems.

4. Display settings.  Set the fields that are visible on every view.

5. Settings page tabs.  Navigate this section to properly configure your WP Inventory Manager.

6. Send messages via the built in support tab.

7. Beautiful two column layout design if using the default styles.


== Changelog ==
= 2.2.1 =
* Temporarily disable media field to debug security issue

= 2.1.0.15 =
* 6/26/2023
    * Failsafe for settings initialization

= 2.1.0.10 =
* 11/16/2022
    * Textarea formatting bug fix

= 2.1.0.8 =
* Various bug fixes after transition to free
    * Guarantee reserve form displays properly

= 2.1.0 =
* 12/22/2021
    * Transitioned to free version. Add ons and licenses will no longer work with this version of the plugin.
    * Security updates and bug fixes
    * Tested up to 5.8.2

= 2.0.9 =
*10/31/2021
    * Tested up to 5.8
    * Enhanced security concerning the search filter search input

= 2.0.8 =
*07/12/2021
    * Tested up to 5.7.1.

= 2.0.7 =
*11/20/2020
    * Add utility function for add-ons to use.
    * Hook to delete user add inside of the delete user function
    * Protect against a missing user id in the view item screen in the dashboard
    * Resolve warning if no items are in the database
    * Extra utility function for the locations add on

= 2.0.6 =
*09/21/2020
    * Fix directory separator.
    * jQuery dependency needed for wpinventory-stepper script and was added.
    * Fixed email alert not going out on reserve for low quantity when RC installed.
    * Removed the get_add_ons API call.
    * Translation file additions and enhancements.

= 2.0.5 =
*08/20/2020
    * Updated pricing for promotions to match increase on the website.

= 2.0.4 =
*07/10/2020
    * Improve shortcodes class to be extended / used by add-ons.
    * Refactor the 'note' field into default filter.  Fixed note not showing in Reserve Cart.

= 2.0.3 =
* 06/24/2020
    * Updated shortcode separator from comma (,) to pipe (|).  This causes less conflicts overall as comma is more common in field names.  Particularly this revision was brought on so that Advanced Search Filter can properly search fields.

= 2.0.2 =
* 06/24/2020
    * Message for non existing item in the admin.
    * For the Reserve Cart add on, we added functionality to hook into so a view cart button could be added in the listing page.
    * Shortcode option added to hide the "back" button on details.
    * UK/US/International date formats are now options to choose from.
    * Back button on detail page use to be javascript.  Reworked to use wp_get_referer().
    * When doing a search in the filter by category, the results displayed two add to cart options when Reserve Cart is active.
    * Enhanced the drag and drop functionality of the display settings in the back end.
    * Category names are now being honored in search text.
    * Extra / redundant "Email Input Label" removed.

= 2.0.1 =
* 05/06/2020
    * Includes an upgrade notification system to provide additional information with upcoming updates.
    * Dropped minimum PHP version to 5.6

= 2.0.0 =
* 4/12/2020
    * Add database versioning utility classes.

= 1.9.9 =
* 4/03/2020
    * Add two new tables to track reservations.
    * Tested up to WordPress 5.4.
    * Updates to dashboard styles.

= 1.9.8 =
* 2/19/2020
    * Resolve foreign characters not translating in email confirmations.
    * License system improvements
    * Added reservation confirmation message and ability to add it before or after email information.

= 1.9.7 =
* 1/13/2020
    * Update to /includes/wpinventory.class.php that causes implode() to fail on PHP 7.4.

= 1.9.6 =
* 1/1/2020
    * Update tested up to version of WordPress.
    * Update the minimum PHP version necessary.

= 1.9.5 =
* 12/21/2019
    * Modernize PHP arrays.
    * Silence non-useful notices for license validation.

= 1.9.4 =
* 11/04/2019
    * Light CSS house keeping / clean up.

= 1.9.3 =
* 10/13/2019
    * Russian translation now available.
    * Chinese translation now available.
    * Hindi translation now available.
    * Arabic translation now available.
    * Dutch translation now available.
    * German translation now available.
    * Style adjustments for settings page layout.

= 1.9.2 =
* 09/16/2019
    * Hooks for improvements to the logging add on for maintenance requests involving license key issues
    * Fix display conflict with listing and latest items widget

= 1.9.1 =
* 08/26/2019
    * Improvements to support new add on that turns off front-end detail page view and associated settings

= 1.9.0 =
* 08/23/2019
    * Updated filter for notifications item edit low quantity message

= 1.8.9 =
* 08/22/2019
    * Complete integration of notifications add on hooks and filter
    * Added the notifications add on to the add ons page for promotion

= 1.8.8 =
* 08/09/2019
    * Fixed broken styles on bx slider images

= 1.8.7 =
* 08/07/2019
    * Add support for multiple add-ons under the same settings tab group
    * Fix issue with item detail permalink on activation

= 1.8.6 =
* 05/29/2019
    * Fixed a missing closing div tag in the HTML
    * Took out restricting CSS on images field
    * Fixes to double slashes in admin file paths

= 1.8.5 =
* 04/27/2019
    * Formatting and light house keeping (code review for quality)

= 1.8.4 =
* 04/25/2019
    * Refactor email to use HTML tables
    * Refactor reserve quantity check to use core item class

= 1.8.3 =
* 04/22/2019
    * Move all reserve emails to core for more centralized control
    * Refactor data santization to be recursive for arrays
    * Refactored front end back link on details page to vanilla javascript

= 1.8.2 =
* 04/16/2019
    * Address security data sanitization in admin dashboard

= 1.8.1 =
* 03/24/2019
    * Address security data sanitization in various $_POST, $_GET, $_REQUEST.
    * Update promo class with new prices reflecting our price increase.

= 1.7.8 =
* 02/28/2019
    * Fix bug with plugin updater still showing update available even though update succeeded.

= 1.7.7 =
* 02/23/2019
    * Add notifications functionality.
    * Added last user to update field.
    * Added the ability to hide the WP Inventory Manager header in the dashboard.
    * Added new setting to show the ID in the database in the admin tables.
    * Added more filters and hooks.
    * Light style changes / modifications.
    * Obscure bug fix in reserve form when using hide low quantity.

= 1.7.6 =
* 01/14/2019
    * Add filter to improve AIM integration.
    * Add some promotional information for unlicensed users.

= 1.7.5 =
* 12/20/2018
    * Fix issue where mb_encode_string function is sometimes not available on some hosting providers.

= 1.7.4 =
* 12/07/2018
    * Fix issue with latest items widget not linking to correct page.

= 1.7.3 =
* 11/24/2018
    * Hot fix of labels not rendering properly in some instances.

= 1.7.2 =
* 11/20/2018
    * Fix bug with custom WHERE parsing.
    * Improve item query filtering.
    * Fix bug with timing of loading custom labels.

= 1.7.1 =
* 09/05/2018
    * Integrate select2 into core.

= 1.7.0 =
* 08/24/2018
    * Fix inventory calculation in status page when duplicate SEO's were found.
    * Additional improvements to search / sort in admin.
    * Add new setting to define the title of the Reserve Form
    * Permanently remove all WP Inventory themes except the default theme
    * Resolve jQuery notice for deprecated .load function
    * Enhancements to support upcoming locations manager add-on
    * Integration of SweetAlert to be available to add-ons as needed

= 1.6.9 =
* 07/07/2018
    * Fix bug with search / sort in admin not properly applying.

= 1.6.8 =
* 06/09/2018
    * Fix bug introduced in 1.6.7 that caused the status to not save / update properly when search / sort was used in the inventory listing.
    * Fix bug introduced in 1.6.7 that caused the license key tab (in settings) to be empty.

= 1.6.7 =
* 06/08/2018
    * Improvements to "Remove default items" feature.
    * Improve slug handling to ensure slugs, and eliminate duplicates.
    * Integrate a "rebuild slugs" tool.
    * Add notice / explanation for DataTables option.
    * Preserve selected filter / sorting when editing items in the dashboard.
    * Add setting for confirmation displayed to visitor after reserving an item.

= 1.6.6 =
* 05/07/2018
    * Include slideshow in core plugin.  Can be disabled in settings.
    * Include robust default items.

= 1.6.5 =
* 04/05/2018
    * Fix issue when searching caused by new super "where" argument.

= 1.6.4 =
* 03/31/2018
    * Bump version number.

= 1.6.3 =
* 03/15/2018
    * Fix issue with AIM introduced by "where" argument improvement.

= 1.6.2 =
* 02/28/2018
    * Add support for powerful / flexible "where" argument in shortcodes.  Also utilized by Bulk Item Manager for robust searching of items.

= 1.6.1 =
* 02/13/2018
    * Fix bug with slugs not working sometimes
    * Improve integration with Advanced Search Filter
    * Fix PHP 7.x compatibility issue

= 1.5.9 =
* 02/12/2018
    * Add support for Advanced Search Filter

= 1.5.8 =
* 02/01/2018
    * Add support for "search" for any field in shortcode attributes.
    * Add "wpinventory_shortcode_on_page" function to detect if shortcode is on the displayed page / post

= 1.5.7 =
* 10/18/2017
    * Fix issue with placement of action on loop template
    * Add YouTube video channel link

= 1.5.6 =
* 08/20/2017
    * Integrate deactivation survey request
    * Fix bug where WPIM interferes with the "Appearance" editors
    * Improve hooks for Reserve Cart plugin layout in table listing

= 1.5.5 =
* 08/05/2017
    * Fix bug with translation of submenu slugs
    * Add DataTables option to front-end listing

= 1.5.4. =
* 07/22/2017
    * Fix bug with wpinventory_the_price
    * Fix notice / bug with status analysis

= 1.5.3 =
* 06/10/2017
    * Improve workflow when entering licenses
    * Cause shortcode to support sorting by category sub-fields (such as category sort order)
    * Improve numeric sorting to use natural (human-friendly) sorting

= 1.5.2 =
* 05/24/2017
    * Improve license entry / onboarding
    * Enhance plugin to recognize anti-spam add-on

= 1.5.1 =
* 05/03/2017
    * Bug fix in some situations when saving items
    * Improvements to sorting arguments in shortcode

= 1.5.0 =
* 04/20/2017
    * Add "Duplicate Item" functionality
    * Improvements to support AIM features
    * Add Status tab with user tips / feedback

= 1.4.9 =
* 03/30/2017
    * Fix issue with old version of PHP
    * Fix for AIM editing items

= 1.4.8 =
* 03/24/2017
    * Improvements to Plugin update notices

= 1.4.7 =
* 03/23/2017
    * Actions / Filters to support reserve cart

= 1.4.6 =
* 03/20/2017
    * Revisions to the reserve form
    * Improved reserve form spam prevention
    * Improved styling of messages / errors
    * Remove "Donate" button

= 1.4.5 =
* 03/17/2017
    * Made interface for Display Settings more extensible
    * Added filters / hooks for various actions

= 1.4.4 =
* 03/02/2017
    * Add enhancements for improved UX

= 1.4.3 =
* 02/19/2017
    * Fix bug with reserve email not sending
    * Use different links in dashboard to prevent collissions with other plugins

= 1.4.2 =
* 01/22/2017
    * Improvements to various minor items.

= 1.4.1 =
* 12/27/2016
    * Add filter for AIM sorting

= 1.4.0 =
* 12/10/2016
    * Improvements to support Ledger Invoicing feature
    * Improvements to prevent loading if under minimum PHP version

= 1.3.9 =
* 11/08/2016
    * Fix issue with add-ons not receiving automatic updates
    * Fix bug with items not listing when certain sort situations

= 1.3.8 =
* 10/27/2016
    * Add ability to use shortcode on home page
    * Fix bug with sort-by not holding on pagination
    * Improved Spanish translation

= 1.3.7 =
* 09/10/2016
    * Add support for enhancements made to Import / Export support for Advanced Inventory Types

= 1.3.6 =
* 09/07/2016
    * Fix issue with add-ons not listing under certain hostile network conditions

= 1.3.5 =
* 08/28/2016
    * Fix bug with reserve email skipping inventory field(s)

= 1.3.4 =
* 08/01/2016
    * Add hook for deleting items

= 1.3.3 =
* 07/07/2016
    * Fix bug in rewind_items

= 1.3.2 =
* 07/02/2016
    * Improve internationalization
    * Add filters for image sizes to work with lightbox plugin

= 1.3.1 =
* 06/20/2016
    * Turn off debug mode for reserve form

= 1.3.0 =
* 05/01/2016
    * Add several new filters
    * Add several new actions
    * Add labels information for immutable labels (status, etc)
    * Fix bug in Media Upload
    * Modify views to include filters (loop-all-table.php, single-loop-all-table.php, single-loop-search.php, single-loop-all.php, single-loop-category.php)

= 1.2.9 =
* 04/05/2016
    * Fix bug with featured image not opening in new window.
    * Fix bug with open media / open image in new window conflicts.
    * Add wpim_image_link_attributes filter to image link (to support lightboxes).

= 1.2.8 =
* 03/18/2016
    * Fix issue that caused fatal error in PHP versions older than 5.4

= 1.2.7 =
* 03/12/2016
    * Fix notices on search results with empty search term
    * Additional developer filters

= 1.2.6 =
* 02/19/2016
    * Add ability to define custom labels for reserve form inputs.

= 1.2.5 =
* 02/17/2016
    * Fix issue where status ID is displayed instead of status name / text
    * Fix issue where status filter in admin not working

= 1.2.4 =
* 02/13/2016 - Added actions in admin "settings" interface for each section (wpim_edit_settings_general, wpim_edit_settings_date, wpim_edit_settings_currency, etc)

= 1.2.3 =
* 01/23/2016 - Fix bug where category name not displaying

= 1.2.2 =
* 01/12/2016
    * Add robust configurable inventory item status functionality
    * Beta - add inventory results into WP core search results
    * Added setting to display media in new window (or same window)
    * Added setting to make images clickable (or not), and to open in new window (or same window)
    * Added clean theme to use site theme's colors, fonts - (non-table listing only)
    * Added loop-search.php template
    * Added single-loop-search.php template
    * Added display settings for Search Results
    * Added setting for search results link-to page
    * Added new action: do_action('wpim_core_loaded'); // no parameters.  Triggered after Core class constructed
    * Added 'set_items' and 'rewind_items' functionality to WPIM Loop
    * Added 'additional class' parameter to wpinventory_get_class() function
    * Added support for 'post_id' in wpinventory_get_permalink functions

= 1.2.1 =
* 12/11/2015
    * Fix notices when reset labels
    * Fix notices if cannot connect to get add-ons
    * Fix issue where license number doesn't appear in settings

= 1.2.0 =
* 09/07/2015 - Update to WP 4.3 preferred Widget Constructor method
* 09/14/2015 - Add Reserve send Confirmation functionality

= 1.1.9 =
* 08/11/2015 - Change from category to label in category dropdown on front-end
* 08/21/2015 - Adjust language loading path

= 1.1.8 =
* 07/16/2015
    * Fix bug with currency display
    * Cause reserve submit to jump down to reserve form / notice
    * Cause sorting by date to list most recent at top

= 1.1.7 =
* 06/29/2015 - Fix bug with widget

= 1.1.6 =
* 06/20/2015
    * Improve licensing system.
    * Add numeric sort setting for fields
    * Fix bug with rebuilding images
    * Improve reserve e-mails

= 1.1.5 =
* 06/10/2015 - Fix bug in loop templates attempting to load single-shortcode instead of single-item

= 1.1.4 =
* 05/12/2015
    * Fix date formatting for updated / added date
    * Added new filter:  apply_filters('wpim_get_config', $setting, $field);
    * usage: add_filter('wpim_get_config', 10, 2); // Two parameters, setting & field
    * added new filter: return apply_filters( 'wpim_check_permission', TRUE, $type, $inventory_item );
    * usage: add_filter('wpim_check_permission', 10, 3); // Three parameters, value, $type (edit_item or save_item), $inventory_item
    * added new filter: $args = apply_filters('wpim_query_item_args', $args);
    * usage: add_filter('wpim_query_item_args'); // The args are the only parameter

= 1.1.3 =
* 05/04/2015
    * Updated code to prevent strict notices
    * Improvements to placeholder loading in various conditions

= 1.1.2 =
* 04/18/2015
    * Make improvement for license validation
    * Fix issue with placeholder image not loading in admin

= 1.1.0 =
* 03/30/2015
    * Fix issue where users could view (not edit) items without permissions in admin
    * Fix issue where permalink / slug field would not show in edit item screen
    * Add support for placeholder image
    * Improve reserve form extensibility and data capture
    * Add hooks / actions in various places
    * Build out "user_can_edit" public function
    * Improve comments in code

= 1.0.9 =
* 02/13/2015 - Fix minor bug with sort-by dropdown including hidden fields

= 1.0.8 =
* 02/04/2015 - Convert tables to utf8

= 1.0.7 =
* 01/26/2015 - License activation debugging output

= 1.0.6 =
* 01/26/2015 - License activation debugging output

= 1.0.5 =
* 01/19/2015 - License activation improvement

= 1.0.4 =
* 12/05/2014 - Fix bug with special chars on form inputs

= 1.0.3	=
* 10/27/2014 - Improvements to license system

= 1.0.2	=
* 11/26/2014 - Minor bug fixes - media not appearing on front-end, improvements to css classes

= 1.0.1	=
* 11/18/2014 - Implement automatic updates

= 1.0.0	=
* 11/18/2014 - Implement license system

= 0.7.9	=
* 11/11/2014 - Enhancements to sort by category

= 0.7.8	=
* 10/08/2014 - Improvements to add-on system

= 0.7.7	=
* 09/29/2014 - Added shortcode atts: category_name, category_slug, user_id

= 0.7.6	=
* 09/25/2014 - Fix bug with permalinks setting not being honored

= 0.7.5	=
* 09/23/2014 - Fix bug with category name display, add wpim_use_currency_formats filter

= 0.7.4	=
* 09/17/2014 - Improved css classes, added class function wpinventory_label_class()

= 0.7.3	=
* 09/10/2014 - Improve css classes throughout front-end views

= 0.7.2	=
* 08/29/2014 - Bug fixes (edit category, error on certain views)

= 0.7.1 =
* Extend hooks for add-ons, improve internationalization.

= 0.6.3 =
* Bug Fixes

= 0.5.0 =
* WP Inventory

== Upgrade Notice ==

= 2.0.3 =
2.0.3 Contains an update that could break your site if you use shortcodes with arguments that contain commas (eg, [wpinventory inventory_id="1,2,3"])

Before upgrading, please review the [Changelog for version 2.0.3](https://www.wpinventory.com/release-version-2-0-3)
