=== Ajax Load More for SearchWP ===
Contributors: dcooney, palmiak
Plugin URI: https://connekthq.com/plugins/ajax-load-more/extensions/searchwp/
Donate link: https://connekthq.com/donate/
Tags: ajax load more, search, searchwp, search results, filter, ajax, infinite scroll
Requires at least: 5.0
Tested up to: 6.1
Stable tag: 1.0.2
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

An Ajax Load More extension that adds compatibility with SearchWP plugin.

== Description ==
**Ajax Load More for SearchWP** is a tiny extension that provides the functionality for returning [SearchWP](https://searchwp.com) query results to [Ajax Load More](https://wordpress.org/plugins/ajax-load-more/) for infinite scrolling.

The extension works by providing a connection point between Ajax Load More and SearchWP and is available for users running at least Ajax Load More 2.13.0 and SearchWP 2.6.1.

= Implementation Steps =
1. Activate plugin.
2. Create Ajax Load More shortcode with a unique ID parameter.
3. Add custom `alm_query_args` filter to your theme functions.php - [Learn More](https://connekthq.com/plugins/ajax-load-more/extensions/searchwp/#how-it-works).

**[View Documentation](https://connekthq.com/plugins/ajax-load-more/extensions/searchwp/)**

== Frequently Asked Questions ==

= What version of SearchWP is this plugin compatible with? =
Requires SearchWP > 2.6.1

= How does this work? =
This extensions works by using the [alm_query_args](https://connekthq.com/plugins/ajax-load-more/docs/filter-hooks/#alm_query_args) filter to pass values to the SWP_Query and then back to Ajax Load More.
[View Example](https://gist.github.com/dcooney/54bed833e51d862e204337cc7a0e18a1)


= How do I pass a search term to SearchWP =
You can pass search term to your Ajax Load More shortcode. [ajax_load_more search="My Search Query" id="searchwp"]

= Can I choose my SearchWP search engine? =
Yes, when you add your `alm_query_args` filter, you can specify a search engine.
`$engine = 'my_custom_engine';`

= How do I highlight the search term in the search results? =
In your Ajax Load More Repeater Template you can do the following, which uses the [SearchWP Highlighter](https://searchwp.com/documentation/classes/searchwp-highlighter/).
	global $post;
	$excerpt = get_the_excerpt( $post );
	echo alm_searchwp_highlight( $excerpt, $args );


== Screenshots ==


== Installation ==

= Uploading in WordPress Dashboard =
1. Navigate to the 'Add New' in the plugins dashboard
2. Navigate to the 'Upload' area
3. Select `ajax-load-more-for-searchwp.zip` from your computer
4. Click 'Install Now'
5. Activate the plugin in the Plugin dashboard

= Using FTP =
1. Download `ajax-load-more-searchwp.zip`.
2. Extract the `ajax-load-more-for-searchwp` directory to your computer.
3. Upload the `ajax-load-more-for-searchwp` directory to the `/wp-content/plugins/` directory.
4. Ensure Ajax Load More is installed prior to activating the plugin.
5. Activate the plugin in the WP plugin dashboard.


== Changelog ==

= 1.0.2 - April 17, 2021 =
* UPDATE - Added support for highlighting the search term in SearchWP results with Ajax Load More. See plugin FAQs.


= 1.0.1 - November 28, 2016 =
* UPDATE - Updating SWP_Query to only return post IDs.


= 1.0 - November 25, 2016 =
* Initial Release.


== Upgrade Notice ==
* None
