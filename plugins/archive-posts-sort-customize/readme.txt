=== Archive Posts Sort Customize ===
Contributors: gqevu6bsiz
Donate link: http://gqevu6bsiz.chicappa.jp/please-donation/?utm_source=wporg&utm_medium=donate&utm_content=apsc&utm_campaign=1_6_1
Tags: archive, posts, sort, customize, frontend, archive posts, home, search, yearly, monthly, daily, category, tag, custom taxonomy
Requires at least: 4.3
Tested up to: 4.3
Stable tag: 1.6.1
License: GPL2

Customize the display order of the list of Archive Posts.

== Description ==

Easily settings interface and available sort to home, date archives, category/tag/taxonomy archives, search.

= Sort Items =
* Post Date
* Post Title
* Post Author
* Post Comments Count
* Post ID
* Post Last Modified
* Post Order (page attributes/menu_order)
* Custom Field of Post

= For sort order to title =
Available to ignore words of beginning of the title for post title of sort.
e.g.)
* The 
* A 

= For sort order to custom fields =
Available to automatically whether sort to number *(meta_key_num)* or sort to string*(meta_key)*.

= For Action/Filter =
You will possible to action before and after the this plugin.
`
add_action( 'apsc_before_sort' , 'custom_apsc_before_sort' , 10 , 2 );

function custom_apsc_before_sort( $wp_query , $setting_data ) {
	
	//print_r($wp_query);
	//print_r($setting_data);
	
}
`
`
add_action( 'apsc_after_sort' , 'custom_apsc_after_sort' , 10 , 2 );

function custom_apsc_after_sort( $wp_query , $setting_data ) {
	
	//print_r($wp_query);
	//print_r($setting_data);
	
}
`

= For Debug =
You will possible to see the debug information after activate the [Debug Bar](https://wordpress.org/plugins/debug-bar/) plugin.

== Installation ==

1. Upload the entire archive-posts-sort-customize folder to the /wp-content/plugins/ directory.
2. Activate the plugin through the 'Plugins' menu in WordPress.
3. You will find 'Archive Posts Sort Customize' menu in your WordPress admin panel.

== Frequently Asked Questions ==

= Q. For found the bug =
A. Please contact the Support Forum.
https://wordpress.org/support/plugin/archive-posts-sort-customize

= Q. Can I change the setting authority of the plugin? =
A. Yes, You will be able to plugin authority. Please try the filter hook.
`
add_filter( 'apsc_capability' , 'custom_apsc_capability' );

function custom_apsc_capability( $capability ) {
	
	//var_dump($capability);
	$capability = 'edit_posts';
	
	return $capability;
	
}
`

== Screenshots ==

1. Settings Interface
2. Support the ignore words for title order sort.
3. Support the custom fields order sort.
4. Support the individual term sort settings.

== Changelog ==

= 1.6.1 =
* Fxied: Show error when not installed debug bar plugin.

= 1.6 =
* Added: Support archives for Yearly/Daily.
* Added: Some actions before and after sort.
* Updated: Taxonomies archives settings.

= 1.5.1 =
* Security enhancement: Escape to add_query_arg/remove_query_arg.

= 1.5 =
* Added: Order field of Page Attributes of the Sort Target.
* Added: Ignore words of Post Title order of Sort.

= 1.4 =
* Added: Custon Taxonomies.
* Fixed: Get data mistake when category settings.

= 1.3.1 =
* Fixed: Javascript toggle miss.

= 1.3 =
* Updated: Settings for per Categories.
* Changed: Data version.

= 1.2.4.2 =
* Fixed: Data update way.

= 1.2.4.1 =
* Updated: Screen shots.
* BUg Fixed: Monthly archive link on settings screen.

= 1.2.4 =
* Changed: Data save process.
* Supported: Compatible to 3.8-RC1.
* Added: Customize sort for Monthly archive.
* Bug Fixed: Empty setting when order by is custom field.

= 1.2.3 =
* Added: Last modified of Sort target(orderby).
* Updated: Translations.

= 1.2.2 =
* Support for SSL.
* Check to 3.6.

= 1.2.1 =
* Added a confirmation of Nonce field.

= 1.2 =
* Added Search support.

= 1.1.1 =
* Some translation fixed.

= 1.1 =
Made it possible to sort of home.

= 1.0 =
This is the initial release.

== Upgrade Notice ==

= 1.6 =
Some do not use the previous data.
Sorry for trouble you please re-settings again.
