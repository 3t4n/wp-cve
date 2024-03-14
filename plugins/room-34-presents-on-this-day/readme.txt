=== On This Day (by Room 34) ===
Contributors: room34
Donate link: https://room34.com/payments
Tags: this day, on this day, this date, today's date, posts on this day, posts on today's date, archive, history, calendar
Requires at least: 4.0
Tested up to: 6.4.3
Stable tag: 3.2.1

Display your blog posts from this date in previous years as a sidebar widget.

== Description ==

On This Day (formerly Room 34 presents On This Day) is a simple widget that displays a list of blog posts that were published on the same date in previous years. Customization options include:

* Title
* Message to display if no posts are found
* Maximum posts to display
* Show featured images (if available)
* Category filtering
* Optional On This Day archive page (new in version 2.0)

**Important:** While not technically required, the On This Day archive page assumes your theme includes an **archive.php** file that uses the **the_archive_title()** function to display the page title.

== Installation ==

1. Upload the plugin files to the /wp-content/plugins/ directory.
2. Activate the plugin through the 'Plugins' menu in WordPress.
3. Place the widget in one of your sidebars and customize as desired.

**Note:** This plugin requires PHP 5.3 or greater due to the use of anonymous functions (closures).

== Frequently Asked Questions ==

= Why isn't the archive page displaying the correct (or any) title? =
If there's an incorrect title, or no title, at the top of your page (before the title of the first post), check your theme. It should have an **archive.php** file, and that file must use the **the_archive_title()** function to display the page title.

== Screenshots ==

== Changelog ==

= 3.2.1 - 2024.02.11 =

* Admin: Updated content and layout of shortcode guide on admin page.
* Bug fix: Incorrect alias key in line 29 of `widget.php`.
* Shortcode: Added support for custom excerpt lengths by setting an integer value for `show_post_excerpt`. Support for this feature will be added to the widget in a future update.
* i18n: Updated translation strings in `.pot` file.
* Bumped "tested up to" to 6.4.3.

= 3.2.0.1 - 2024.01.04 =

* Rolled back CSS styling (font sizes/margins) based on negative user feedback. Styling will return as an _option_ in a future update.

= 3.2.0 - 2024.01.01 =

* Added **Show post dates** and **Show post excerpts** options. (To conserve space in the widget, excerpts use the `excerpt_length` and `excerpt_more` hooks to override site defaults; length is shortened to 25 words if longer; "more" is always `...`.)
* Changed: Front end post dates now show year only unless **Show post dates** is checked.
* Added CSS to adjust relative sizes of titles, dates and excerpts, reduced white space in CSS files, and added logic to append the version number to the enqueued CSS files.
* Reorganized admin widget configuration interface.
* i18n: Added `.pot` file for translation strings.

= 3.1.2 - 2023.12.20 =

* Updated widget output to use `get_the_title()` instead of retrieving the post's title property directly, allowing for use of the `the_title` filter.

= 3.1.1 - 2021.11.20 =

* Updated plugin name on admin pages.

= 3.1.0 - 2021.11.20 =

* Changed displayed plugin name from "Room 34 presents On This Day" to "On This Day (by Room 34)".
* Changed `require_once()` instances in main plugin file to use `plugin_dir_path()` instead of `dirname()`.
* Updated tags in readme file.
* Bumped "tested up to" to 5.8.2.

= 3.0.2 =

* Removed support for the Category option if the widget block editor is enabled (included by default with WordPress 5.8). The functionality for loading the category list and saving that option does not work with the AJAX-based tools in the block editor, and the presence of this option was causing the plugin not to load at all in the widget block editor, although it still functioned properly on the site itself. **If you wish to restore the old widget editing functionality, we suggest trying the [Classic Widgets](https://wordpress.org/plugins/classic-widgets/) plugin.**

= 3.0.1 =

* Changed shortcode behavior to return output instead of echoing it directly.

= 3.0.0 =

* Added shortcode support. Use `[on_this_day]` anywhere shortcodes are supported. All of the regular widget options are available as shortcode parameters. See the plugin's new settings page for details.
* Oh yeah, there's also now a settings page! Go to **Settings &gt; On This Day**.
* Added code to handle an arbitrary date (currently only works with shortcode, not widget).
* Fixed issue that was causing admin CSS file to load on front-end pages.
* Created separate front-end CSS file.
* Coming soon: translations!

= 2.5.1 =

* Modified conditions to show current year's posts in widget when "Use post date" is set.
* Improved help text in widget configuration.
* Added text domain to i18n functions in preparation for translation support.
* Minor CSS tweaks in admin.

= 2.5.0.2 =

* Fixed displayed publish date of posts to resolve issues when post's publish *time* falls on a different date between local time and GMT/UTC+0.
* Bumped tested up to version to 5.5.3.

= 2.5.0.1 =

* Fixed incorrect callback function name in `pre_get_document_title` filter.

= 2.5.0 =

* Replaced all closures in actions and filters with named functions to give developers more flexibility in working with the plugin.
* Removed "On This Day" from archive page titles in keeping with the goal of avoiding any hardcoded front-end text until full i18n support is implemented.

= 2.4.1 =

* Made 'See all...' text string editable in widget configuration. This was the only non-editable text displayed on the front end. Making this text editable will allow for easier use of the plugin on non-English language websites while full i18n support is in development.

= 2.4.0 =

* Replaced uses of PHP `date()` and WordPress `date_i18n()` function with `wp_date()` for improved i18n support. (Full i18n support for all displayed text strings coming in a subsequent update.)

= 2.3.0 =
* Removed "See all..." links when a date has no posts.
* Removed "On This Post's Date" when on archive pages.
* Fixed logic for "See all..." pages to work with a specific (not today's) date.

= 2.2.2 =
* Added option to hide widget entirely when list is empty by leaving "Message to display if no posts are found" blank.
* Modified logic for hiding redundant lists so that if two lists on the same page are both *empty* and "Message to display if no posts are found" is set, then they'll still appear.

= 2.2.1 =
* Added logic to prevent duplicate list of posts from appearing multiple times on one page. This allows creation of two widgets, one for showing today's posts, and one for "Use post date" posts, for example.

= 2.2.0 =
* Added "Use post date" option to widget. If set, when viewing an individual post, the widget will show posts from the same date as the current post, not today's date. On main blog or archive pages, widget will still show posts from today's date.

= 2.1.0 =
* Added month/day filtering of Posts in admin to show all posts published on in given month/date.

= 2.0.2 =
* Fixed handling of a global array variable to resolve fatal errors with WP-CLI.

= 2.0.1 =
* Simplified date_query.

= 2.0.0 =
* Added On This Day Archive page.
* Refactored to use date_query instead of retrieving and then omitting posts from the current year.

= 1.5.3 =
* Made featured images clickable links to posts.
* Tested in WP 4.7.1.

= 1.5.2.1 =
* Updated "Tested up to" to 4.7.

= 1.5.2 =
* Fix for implode() warning when no categories were selected.

= 1.5.1 =
* Updated plugin repository description text.

= 1.5 =
* Added ability to filter list by category.
* Added ability to set maximum number of posts to display.

= 1.4 =
* Added option to display featured image with each post.
* General refactoring and code clean-up.

= 1.3 =
* Updated main function to use parent::__construct() for compatibility with WordPress 4.3.

= 1.2 =
* Updated link structure to use standard permalinks instead of custom-built URL format.

= 1.1.1 =
* Updated "Tested up to" tag.

= 1.1 =
* Added CSS class to ul tag and changed tag and class attribute for each li item.

= 1.0 =
* Original version.

== Upgrade Notice ==
