=== Ajax Load More for Relevanssi ===
Contributors: dcooney
Plugin URI: https://connekthq.com/plugins/ajax-load-more/extensions/relevanssi/
Donate link: https://connekthq.com/donate/
Tags: ajax load more, search, relevanssi, ajax relevanssi, filter, ajax search
Requires at least: 5.0
Tested up to: 6.1
Stable tag: 1.0.2
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

An Ajax Load More extension that adds compatibility with Relevanssi.

== Description ==
**Ajax Load More for Relevanssi** is a tiny extension that provides the functionality for returning [Relevanssi](https://en-ca.wordpress.org/plugins/relevanssi/) query results to [Ajax Load More](https://wordpress.org/plugins/ajax-load-more/) for infinite scrolling.

The extension works by providing a connection point between Ajax Load More and Relevanssi and is available for users running at least Ajax Load More 2.13.0.

= Implementation Steps =
1. Activate plugin.
2. Create Ajax Load More shortcode with a unique ID parameter.
3. Add custom `alm_query_args` filter to your theme functions.php - [Learn More](https://connekthq.com/plugins/ajax-load-more/extensions/relevanssi/#how-it-works).

**[View Documentation](https://connekthq.com/plugins/ajax-load-more/extensions/relevanssi/)**

== Frequently Asked Questions ==

= How does this work? =
This extensions works by using the [alm_query_args](https://connekthq.com/plugins/ajax-load-more/docs/filter-hooks/#alm_query_args) filter to pass values to the Relevanssi query and then back to Ajax Load More.

= How do I pass a search term to Relevanssi =
You can pass search term to your Ajax Load More shortcode. [ajax_load_more search="My Search Query" id="relevanssi"]

= How do I highlight the search term in the search excerpts? =
In your Ajax Load More Repeater Template you can do the following using the [relevanssi_do_excerpt](https://github.com/msaari/relevanssi/blob/master/lib/excerpts-highlights.php#L27) function.
	global $post;
	echo relevanssi_do_excerpt( $post, $args['search'] );

== Screenshots ==


== Installation ==

= Uploading in WordPress Dashboard =
1. Navigate to the 'Add New' in the plugins dashboard
2. Navigate to the 'Upload' area
3. Select `ajax-load-more-for-relevanssi.zip` from your computer
4. Click 'Install Now'
5. Activate the plugin in the Plugin dashboard

= Using FTP =
1. Download `ajax-load-more-relevanssi.zip`.
2. Extract the `ajax-load-more-for-relevanssi` directory to your computer.
3. Upload the `ajax-load-more-for-relevanssi` directory to the `/wp-content/plugins/` directory.
4. Ensure Ajax Load More is installed prior to activating the plugin.
5. Activate the plugin in the WP plugin dashboard.

== Changelog ==

= 1.0.2 - April 17, 2021 =
* UPDATE - Added support for highlighting the search term in Relevannsi post excerpts with Ajax Load More. See plugin FAQs for details.

= 1.0.1 - December 18, 2019=
* NEW - Added support for core Relevanssi filter [relevanssi_modify_wp_query](https://www.relevanssi.com/knowledge-base/ordering-search-results-date/)

= 1.0 =
* Initial Release.

== Upgrade Notice ==
* None
