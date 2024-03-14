=== Page Meta ===
Contributors: stvwhtly
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=QPKN7EUFKAZLE
Tags: page, meta, seo, description, keywords, title, custom
Requires at least: 2.8.2
Tested up to: 3.5.2
Stable tag: 1.5.1
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Adds the ability to override the page meta title and add in meta descriptions and keywords for pages. 

== Description ==

This plugin uses custom fields to allow the page title tag to be customised and differ from the actual page title.

Both meta descriptions and keywords can also be added if required.

Page meta details can be modified on any publicly accessible post type, such as posts and pages, as well as custom post types.

Theme and plugin developers should note that it is possible override the page title within your templates by setting `$wppm_title = 'Newly defined title';` before the call to `get_header();`.

== Installation ==

Here we go:

1. Upload the `pagemeta` folder to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Choose which post types the plugin should be enabled on via the `Settings > Reading` options.

== Frequently Asked Questions ==

= What custom field names does it use? =

The field names used are `_pagemeta_title`, `_pagemeta_description` and `_pagemeta_keywords`.

The underscore prefix prevents it from being displayed in the list of custom fields.

= How do I output the custom title? =

If a custom title is set for a post type, the plugin will automatically hook into the `wp_title` function to modify the output.

This uses the parameters passed via [wp_title](http://codex.wordpress.org/Function_Reference/wp_title "Function Reference/wp title") and will completely override the title.

You can customise the output further using the `pagemeta_title` filter, which uses the same parameters plus the original post title value.

In this example we prefix the original title to the custom title.

`add_filter( 'pagemeta_title', function( $title, $sep, $seplocation, $original_title ) {
	return $original_title . $title;
}, 10, 4 );`

Please refer to the Codex for further information on the [add_filter](http://codex.wordpress.org/Function_Reference/add_filter "Function Reference/add filter").

= Why are the meta tags not added to my pages? =

This plugin makes use of the `wp_head` hook action, therefore in order for it to work there must be a call to wp_head in the current theme header file.

More information on this can be found in the [WordPress Codex](http://codex.wordpress.org/Function_Reference/wp_head "Function Reference/wp head").

= Can I modify which fields are shown? =

Yes, as of version 1.5 you can modify which fields are used using the `pagemeta_fields` filter:

`add_filter( 'pagemeta_fields', function( $fields ) {
	$fields['author'] = 'Author'; // Add a new field.
	unset( $fields['keywords'] ); // Remove a default field.
	return $fields;
} );`

The default fields are 'title', 'description' and 'keywords'.

= Can I output the meta values? =

The page meta values can be output within posts using the `[postmeta]` shortcode.

Passing through a `name` attribute will determine which value is returned, for example to output the description value use the following.

`[postmeta name="description"]`

Name values are determined by the fields set, by default these are 'title', 'description' and 'keywords'.

To output meta values in template files, you can make use of `the_pagemeta` function.

`<?php if ( function_exists( 'the_pagemeta' ) ) { the_pagemeta( 'description' ); } ?>`

This will output the value, in order to return the value or lookup a specific post ID you can use `get_the_pagemeta`.

`<?php if ( function_exists( 'get_the_pagemeta' ) ) { 
	$description = get_the_pagemeta( 'description' );
} ?>`

`<?php if ( function_exists( 'get_the_pagemeta' ) ) {
	$description = get_the_pagemeta(
		'description', // Page meta value name
		123 // Post ID to lookup
	);
} ?>`

Not that these functions will return the raw values prior to any output manipulation.

== Upgrade Notice ==

= 1.4 =
Introduced the ability to enable page meta data on any public post type via the settings page, which has been merged into the `Settings > Reading` admin page instead of the plugin specific settings page. Ensure page meta is enabled for required post types by checking settings in the new location.

== Screenshots ==

1. The Page Meta panel displayed below the content editor.
2. Settings are managed via the Settings > Reading page.

== Changelog ==

= 1.5 =
* Fix for code previously committed in error, preventing edit page functionality.

= 1.5 =
* Added ability to filter / modify meta boxes using the `pagemeta_fields` filter.
* Custom titles can now be filtered using `pagemeta_title`.
* Meta values are now output for static pages (Front and Posts) assigned via the Reading Settings page.
* For new plugin installs, the plugin will be enabled on pages as well as posts by default.
* Introduced shortcode to allow values to be output in post content.
* New `the_pagemeta` and `get_the_pagemeta` functions added.
* Addition of basic inline documentation.

= 1.4.1 =
* Replaced PHP short tag used to display nonce field on admin edit screen panel.

= 1.4 =
* Changed plugin author and contributor names.
* Replaced deactivation hook and tidy option with uninstall.php.
* Modified activation hook to remove warnings during plugin activation.
* Combined settings with Settings > Reading page.
* Allowed meta data to be added to any public post type, instead of just posts and pages.
* Removed PHP shortcodes from edit page meta box.
* Updated donate link.
* Updated screenshots.

= 1.3 =
* Page title is now automatically inserted, instead of using the custom function.

= 1.2 =
* Fixed issue with WordPress 3.0+ not correctly outputting headers.

= 1.1 =
* Fixed errors when error reporting is set to all.
* Tested the plugin in WordPress 2.9.

= 1.0 =
* Converted plugin to a class based structure.
* Converted constants into settings page.
* Added donate link ;)

= 0.3.1 =
* Bug fix nonce checking and addition of title override.

= 0.3 =
* Added ability to allow page meta data on posts, as well as properly escaping values.

= 0.2.1 =
* Removed debugging output.

= 0.2 =
* Tested up to 2.8.5 and began optimisation of the included files.

= 0.1 =
* This is the very first version.