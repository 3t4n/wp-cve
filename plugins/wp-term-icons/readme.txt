=== WP Term Icons ===
Contributors: johnjamesjacoby
Tags: taxonomy, term, meta, metadata, icon, icons
Requires at least: 4.3
Tested up to: 4.9
Stable tag: 0.1.2
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

== Description ==

Pretty icons for categories, tags, and other taxonomy terms

WP Term Icons allows users to assign icons to any visible category, tag, or taxonomy term using a fancy icon picker, providing a customized look for their taxonomy terms.

= Also checkout =

* [Term Order](https://wordpress.org/plugins/wp-term-order/ "Sort taxonomy terms, your way.")
* [Term Colors](https://wordpress.org/plugins/wp-term-colors/ "Pretty colors for categories, tags, and other taxonomy terms.")
* [Term Visibility](https://wordpress.org/plugins/wp-term-visibility/ "Visibilities for categories, tags, and other taxonomy terms.")
* [User Groups](https://wordpress.org/plugins/wp-user-groups/ "Group users together with taxonomies & terms.")
* [User Profiles](https://wordpress.org/plugins/wp-user-profiles/ "The sophisticated way to edit users in WordPress.")

== Screenshots ==

1. Category Icons

== Installation ==

* Download and install using the built in WordPress plugin installer.
* Activate in the "Plugins" area of your admin by clicking the "Activate" link.
* No further setup or configuration is necessary.

== Frequently Asked Questions ==

= Does this create new database tables? =

No. There are no new database tables with this plugin.

= Does this modify existing database tables? =

No. All of WordPress's core database tables remain untouched.

= How do I query for terms via their icons? =

With WordPress's `get_terms()` function, the same as usual, but with an additional `meta_query` argument according the `WP_Meta_Query` specification:
http://codex.wordpress.org/Class_Reference/WP_Meta_Query

`
$terms = get_terms( 'category', array(
	'depth'      => 1,
	'number'     => 100,
	'parent'     => 0,
	'hide_empty' => false,

	// Query by icon using the "wp-term-meta" plugin!
	'meta_query' => array( array(
		'key'   => 'icon',
		'value' => 'dashicons-networking'
	) )
) );
`

= Where can I get support? =

The WordPress support forums: https://wordpress.org/support/plugin/wp-term-icons

= Where can I find documentation? =

The JJJ Software, Inc. page: https://jjj.software/wp-term-icons/

== Changelog ==

= 0.1.2 =
* Add "term-icon-wrap" class to fields

= 0.1.1 =
* Remove erroneous "form-required" class from field

= 0.1.0 =
* Initial release
