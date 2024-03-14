=== Expanding Archives ===
Contributors: NoseGraze
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=L2TL7ZBVUMG9C
Tags: widget, sidebar, posts, archives, navigation, menu, collapse, expand, collapsing, collapsible, expanding, expandable
Requires at least: 3.0
Tested up to: 6.0
Requires PHP: 7.4
Stable tag: trunk
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

This plugin adds a new widget where you can view your old posts by expanding certain years and months.

== Description ==

Expanding Archives adds a widget that shows your old posts in an expandable/collapsible format. Each post is categorized under its year and month, so you can expand all the posts in a given month and year.

This plugin comes with very minimal CSS styling so you can easily customize it to match your design.

JavaScript is required. No IE support.

== Installation ==

1. Upload `expanding-archives` to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress
1. Go to Appearance -> Widgets and drag the Expanding Archives widget into your sidebar.

== Frequently Asked Questions ==

= How can I change the appearance of the widget? =

The plugin does not come with a settings panel so you have to do this with your own custom CSS. Here are a few examples:

Change the year background colour:

`.expanding-archives-title {
    background: #000000;
}`

Change the year font colour:

`.expanding-archives-title a {
    color: #ffffff;
}`

= How can I limit the results to a specific category? =

By default, the widget includes posts in all categories. You can add the following code to a custom plugin or a child theme's functions.php file to limit the results to posts in a specific category:

`add_filter('expanding_archives_get_posts', function(array $args) {
     $args['cat'] = 2; // Replace with ID of your category.

     return $args;
 });

 add_filter('expanding_archives_query', function(string $query) {
     $category = get_category(2); // Replace with ID of your category.
     if (! $category instanceof \WP_Term) {
         return $query;
     }

     global $wpdb;

     return "
 SELECT DISTINCT MONTH(post_date) AS month, YEAR(post_date) AS year, COUNT(id) as post_count
 FROM {$wpdb->posts}
          INNER JOIN {$wpdb->term_relationships} ON ({$wpdb->posts}.ID = {$wpdb->term_relationships}.object_id AND {$wpdb->term_relationships}.term_taxonomy_id = 2)
 WHERE post_status = 'publish'
   AND post_date <= now()
   AND post_type = 'post'
 GROUP BY month, year
 ORDER BY post_date DESC
     ";
 });`

Be sure to set the ID of your category in both of the designated places (the examples use ID `2`).

Note that the results may not update instantly, as the query to retrieve the date periods is cached for one day. To force the query to re-run, delete this transient: `expanding_archives_months`

== Screenshots ==

1. The widget on my blog. This version has custom CSS applied.
2. The widget on the Twenty Fifteen theme, with only the default styles applied.
3. No widget settings - just add and save!

== Changelog ==

= 2.0.2 - 3 February, 2022 =
* Refactor: Posts are now retrieved via a custom REST API endpoint, instead of the default. This allows developers to more easily filter the query arguments for retrieving posts.

= 2.0.1 - 31 January, 2022 =
* Fix: Only showing a max of 10 posts in a month. Now it will show up to 100.

= 2.0 - 23 January, 2022 =
* Dev: Plugin has been rewritten (should be backwards compatible).
* Dev: Removed jQuery dependency (and dropped IE support).
* Dev: Remove Font Awesome spinner in favour of vanilla CSS.
* Fix: Invalid HTML when the site has no posts.

= 1.1.1 =
* Added filters that allow developers to easily modify the archive list.

= 1.1.0 =
* Added a new option in the widget where you can choose to auto expand the current month or not.

= 1.0.5 =
* Use transient for database query that fetches all the months.

= 1.0.4 =
* Added `xhrFields: { withCredentials: true }` to ajax call.

= 1.0.3 =
* Changed the month URLs to use get_month_link() instead of building them manually.
* Tested the plugin with WordPress 4.4 beta.

= 1.0.2 =
* Tested with WordPress version 4.3.

= 1.0.1 =
* Month names are now displayed using date_i18n() instead of date() so they will translate.

= 1.0.0 =
* Initial release.

== Upgrade Notice ==

= 2.0.2 =
* Refactor: Use custom API endpoint for retrieving posts.
