=== WordPress Stats View Counter ===
Contributors: AdamCapriola
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=LHHK2RKRY4JFA
Tags: views, counter, stats
Requires at least: 3.0
Tested up to: 4.3.1
Stable tag: 1.3
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Saves view counts from WordPress.com Stats Jetpack module as post meta data.

== Description ==

Saves view counts from [Jetpack](http://wordpress.org/extend/plugins/jetpack/) Site Stats module as post meta data. This can be useful for displaying view counts in your theme and building [custom queries](http://codex.wordpress.org/Class_Reference/WP_Query#Order_.26_Orderby_Parameters).

What makes this a better solution over other view-tracking plugins is that it simply pulls the information from Jetpack, which is popular and provides fairly accurate data, so this has very little overhead. It's a streamlined plugin that piggybacks off another which you might already be using.

Comes with a shortcode to display views and a filter to change the post meta key where views are saved.

Thank you to [Milan Dinić](http://profiles.wordpress.org/dimadin/) for improvements in the code after the initial release (and for the Serbian translation)!

== Installation ==

1. Upload `wp-stats-view-counter` to the `/wp-content/plugins/` directory.
1. Activate the plugin through the *Plugins* menu in WordPress.
1. Go to Settings > View Counter to select post types to track.
1. Use shortcode `[view-count]` or `get_post_meta( $post->ID, 'views', true );` to display views.

== Frequently Asked Questions ==

= Which post meta key is used? =

By default `views` is the key where the view counts are saved, but you can filter this with the filter `view_counter_meta_key`. So for example, if you wanted to change the meta key from `views` to `my-key`, you would use this in your functions.php file:

`add_filter( 'view_counter_meta_key', 'my_view_counter_meta_key', 10, 1 );

function my_view_counter_meta_key( $key ) {

	return 'my-key';

}`

The plugin will check to make sure the new view count is greater than the old one before replacing, so there is some fallback to prevent it from overwriting previously saved views from other plugins also using the `views` post meta key.

= How do I display the views? =

Either using `get_post_meta( $post->ID, 'views', true );` or the shortcode `[view-count]`.

The shortcode can also accept before and after parameters, like so:

`[view-count before="Views: "]`
`[view-count after=" views"]`

And keep in mind that shortcodes can be activated using [do_shortcode()](http://codex.wordpress.org/Function_Reference/do_shortcode) if you have having troubled getting it to work:

`echo do_shortcode( '[view-count]' );`

= How often does it update the views? =

Every 3 hours an entry is accessed, the plugin will check the WordPress.com Stats view count and update locally. The delay is to ensure we don't overload the WordPress.com Stats API.

This will be unnoticeable to the average visitor because it's unlikely they are sitting on your site waiting for the views to update, like you might be.

Unless you have an extremely active site, there isn't really much reason for the check to be more frequent.

= Can I change the update time? =

Yes, if you really want to, you can! Use the following filter:

`add_filter( 'view_counter_transient_expiration', 'my_view_counter_transient_expiration', 10, 1 );

function my_view_counter_transient_expiration( $hours ) {

	return 2; // time in hours

}`

== Screenshots ==

1. Settings

== Changelog ==

= Version 1.3 =

* Better handling on 404 pages
* Minor improvements to code
* Added more instructions about shortcode to readme

= Version 1.2 =

* Serbian translation thanks to [Milan Dinić](http://profiles.wordpress.org/dimadin/)!

= Version 1.1 =

* Thank you to [Milan Dinić](http://profiles.wordpress.org/dimadin/) for all the improvements in this update!
* Makes expiration time filterable, also makes sure that filter returns positive integer.
* Changes one wrong text domain.
* Wraps two strings with i18n function.
* Uses number_format_i18n instead of number_format so that numbers are formatted for languages other than English too. Also makes sure that value passed to it is float since otherwise number_format will return error.
* Pinking shears.

= Version 1.0 =

* This is version 1.0. Everything's new!

== Upgrade Notice ==

= Version 1.3 =

* Better handling on 404 pages
* Minor improvements to code
* Added more instructions about shortcode to readme

= Version 1.2 =

* Serbian translation thanks to Milan Dinić!

= Version 1.1 =

* Thank you to Milan Dinić for all the improvements in this update! Now makes expiration time filterable, also makes sure that filter returns positive integer, changes one wrong text domain, wraps two strings with i18n function, uses number_format_i18n instead of number_format, and more.

= Version 1.0 =

* This is version 1.0. Everything's new!