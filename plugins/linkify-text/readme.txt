=== Linkify Text ===
Contributors: coffee2code
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=6ARCFJ9TX3522
Tags: text, link, hyperlink, autolink, replace, shortcut, shortcuts, post, post content, coffee2code
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
Requires at least: 4.7
Tested up to: 4.9
Stable tag: 1.9.1

Automatically add links to words or phrases in your posts.

== Description ==

This plugin allows you to define words or phrases that, whenever they appear in your posts or pages, get automatically linked to the URLs of your choosing. For instance, wherever you may mention the word "WordPress", that can get automatically linked as "[WordPress](https://wordpress.org)".

Additional features of the plugin controlled via settings and filters:

* Text linkification can be enabled for comments (it isn't by default)
* Text linkification can be made case sensitive (it isn't by default)
* Text linkification can be limited to doing only one linkification per term, per post (by default, all occurrences of a term are linkified)
* Text linkification links can be set to open in a new window (it isn't by default)

You can also link multiple terms to the same link and only define that link once in the settings via use of a special link syntax.

A number of filters exist to programmatically customize the behavior of the plugin, all of which are documented.

Links: [Plugin Homepage](http://coffee2code.com/wp-plugins/linkify-text/) | [Plugin Directory Page](https://wordpress.org/plugins/linkify-text/) | [GitHub](https://github.com/coffe2code/linkify-text/) | [Author Homepage](http://coffee2code.com)


== Installation ==

1. Install via the built-in WordPress plugin installer. Or download and unzip `linkify-text.zip` inside the plugins directory for your site (typically `wp-content/plugins/`)
1. Activate the plugin through the 'Plugins' admin menu in WordPress
1. (optional) Go to the `Settings` -> `Linkify Text` admin options page and define text and the URLs they should point to


== Frequently Asked Questions ==

= Does this plugin modify the post content in the database? =

No. The plugin filters post content on-the-fly.

= Will this work for posts I wrote prior to installing this plugin? =

Yes, if they include terms that you have defined to be linkified.

= What post fields get handled by this plugin? =

By default, the plugin filters the post content, post excerpt fields, widget text, and optionally comments and comment excerpts. You can use the 'c2c_linkify_text_filters' filter to modify that behavior (see Filters section). There is a setting you can change to make text linkifications apply to comments as well.

= Is the plugin case sensitive? =

By default, yes. There is a setting you can change to make it case insensitive. Or if you are a coder, you can use the 'c2c_linkify_text_case_sensitive' filter (see Filters section).

= What if the word or phrase is already linked in a post? =

Already linked text will not get linked again by this plugin (regardless of what the link may be).

= Will all instances of a given term be linked in a single post? =

By default, yes. There is a setting you can change so that only the first occurrence of the term in the post gets linked. Or if you are a coder, you can use the 'c2c_linkify_text_replace_once' filter (see Filters section).

= Is there an efficient way to link multiple terms to the same link without repeating the link in the settings field (which can be tedious and prone to errors)? =

Yes. You can reference another term by specifying its link as another term in the list prepended with a colon (':'). For instance:

`
WP => https://wordpress.org,
WordPress => :WP
dotorg => :WP
`

Given the above terms to link, all terms would link to 'https://wordpress.org'. The latter two all reference the link used for the term "WP".

NOTE: The referenced term must have an actual link defined and not be a reference to another term. (Basically, nested references are not currently supported.)

= How can I get text linkification to apply for custom fields (or something not linkified by default)? =

You can add to the list of filters that get text linkified using something like this (added to your theme's functions.php file, for instance):

`
/**
 * Enable text linkification for custom fields.
 *
 * @param array $filters Array of filters that the plugin should hook.
 * @return array
 */
function more_text_replacements( $filters ) {
	$filters[] = 'the_meta'; // Here you could put in the name of any filter you want
	return $filters;
}
add_filter( 'c2c_linkify_text_filters', 'more_text_replacements' );
`

= Can I only have text linkification take place for only a part of a post (such as text inside certain tags, or except for text in certain tags)? =

No. The plugin applies fully to the post content. With some non-trivial coding the plugin could be utilized to affect only targeted parts of a post's content, but it's not something that will be built into the plugin.

= Can I change how the link gets created because I want to add a 'title' attribute to the link? =

Yes, with a bit of code. You can define the title attribute text in your replacement string, like so:

`
WP => https://wordpress.org || This is the link title
`

Now the code:

`
/**
 * Force links created by Linkify Text plugin to open in a new tab.
 *
 * @param array  $attrs         The associative array of attributes to be used for the link.
 * @param string $old_text      The text being replaced/linkified.
 * @param string $link_for_text The URL that $old_text is to be linked to.
 * @return array
 */
function add_title_attribute_to_linkified_text( $attrs, $old_text, $link_for_text ) {
	// The string that you chose to separate the link URL and the title attribute text.
	$separator = ' || ';

	// Only change the linked text if a title has been defined
	if ( false !== strpos( $link_for_text, $separator ) ) {
		// Get the link and title that was defined for the text to be linked.
		list( $url, $title ) = explode( $separator, $link_for_text, 2 );

		// Set the attributes ('href' must be overridden to be a proper URL).
		$attrs['href']  = $url;
		$attrs['title'] = $title;
	}

	return $attrs;
}
add_filter( 'c2c_linkify_text_link_attrs', 'add_title_attribute_to_linkified_text', 10, 3 );
`

= Can I selectively disable text linkification? =

Yes, with some custom code making use of the 'c2c_linkify_text_linked_text' filter. The code should determine if the given text linkification should be disabled, and if so, return the second argument sent via the filter. See the docs for the 'c2c_linkify_text_linked_text' filter for an example of how a custom field could be used to disable all text linkifications on a per-post basis. No doubt your particular situation will require custom logic to determine when to disable linkification.

= Does this plugin include unit tests? =

Yes.


== Screenshots ==

1. A screenshot of the admin options page for the plugin, where you define the text and their related links, as well as customize various settings.


== Hooks ==

The plugin exposes a number of filters for hooking. Typically, code making use of filters should ideally be put into a mu-plugin or site-specific plugin (which is beyond the scope of this readme to explain). Bear in mind that most of the features controlled by these filters are configurable via the plugin's settings page. These filters are likely only of interest to advanced users able to code.

**c2c_linkify_text_filters (filter)**

The 'c2c_linkify_text_filters' hook allows you to customize what hooks get text linkification applied to them.

Arguments:

* $hooks (array): Array of hooks that will be text linkified.

Example:

`
/**
 * Enables text linkification for custom fields.
 *
 * @param array $filters The filters handled by the Linkify Text plugin.
 */
function more_text_replacements( $filters ) {
	$filters[] = 'the_meta'; // Here you could put in the name of any filter you want
	return $filters;
}
add_filter( 'c2c_linkify_text_filters', 'more_text_replacements' );
`

**c2c_linkify_text_comments (filter)**

The 'c2c_linkify_text_comments' hook allows you to customize or override the setting indicating if text linkification should be enabled in comments.

Arguments:

* $state (bool): Either true or false indicating if text linkification is enabled for comments. The default value will be the value set via the plugin's settings page.

Example:

`// Prevent text linkification from ever being enabled in comments.
add_filter( 'c2c_linkify_text_comments', '__return_false' );`

**c2c_linkify_text (filter)**

The 'c2c_linkify_text' hook allows you to customize or override the setting defining all of the text phrases and their associated links.

Arguments:

* $linkify_text_array (array): Array of text and their associated links. The default value will be the value set via the plugin's settings page.

Example:

`
/**
 * Programmatically adds more text to be linked.
 *
 * @param array $text_to_links Array of text and their associated URLs.
 */
function my_text_linkifications( $text_to_links ) {
	// Add text link
	$text_to_links['Matt Mullenweg'] => 'https://ma.tt';

	// Unset a text link that we never want defined
	if ( isset( $text_to_links['WordPress'] ) ) {
		unset( $text_to_links['WordPress'] );
	}

	// Important! Return the changes.
	return $text_to_links;
}
add_filter( 'c2c_linkify_text', 'my_text_linkifications' );
`

**c2c_linkify_text_case_sensitive (filter)**

The 'c2c_linkify_text_case_sensitive' hook allows you to customize or override the setting indicating if text matching for potential text linkification should be case sensitive or not.

Arguments:

* $state (bool): Either true or false indicating if text matching is case sensitive. The default value will be the value set via the plugin's settings page.

Example:

`// Prevent text matching from ever being case sensitive.
add_filter( 'c2c_linkify_text_case_sensitive', '__return_false' );`

**c2c_linkify_text_replace_once (filter)**

The 'c2c_linkify_text_replace_once' hook allows you to customize or override the setting indicating if text linkification should be limited to once per term per piece of text being processed regardless of how many times the term appears.

Arguments:

* $state (bool): Either true or false indicating if text linkification is to only occur once per term. The default value will be the value set via the plugin's settings page.

Example:

`// Only linkify a term once per post.
add_filter( 'c2c_linkify_text_replace_once', '__return_true' );`

**c2c_linkify_text_open_new_window (filter)**

The 'c2c_linkify_text_open_new_window' hook allows you to customize or override the setting indicating if links should open in a new window.

Arguments:

* $state (bool): Either true or false indicating if links should open in a new window. The default value will be the value set via the plugin's settings page, which itself is defaulted to false.

Example:

`// Make links open in a new window.
add_filter( 'c2c_linkify_text_open_new_window', '__return_true' );`

**c2c_linkify_text_linked_text (filter)**

The 'c2c_linkify_text_linked_text' hook allows you to customize or override the replacement link markup for a given string. Return the value of $old_text to effectively prevent the given text linkification.

Arguments:

* $new_text (string): The link markup that will replace $old_text.
* $old_text (string): The text being replaced/linkified.
* $link (string): The URL that $old_text is to be linked to.
* $text_to_link (array): The full array of text and the URLs they should link to.

Example:

`
/**
 * Disable linkification of links for posts that have the 'disable_linkify_text'
 * custom field defined.
 *
 * @param array  $display_link  The associative array of attributes to be used for the link.
 * @param string $old_text      The text being replaced/linkified.
 * @param string $link_for_text The URL that $old_text is to be linked to.
 * @param string $text_to_link  The full array of text and the URLs they should link to.
 * @return string
 */
function selectively_disable_text_linkification( $display_link, $old_text, $link_for_text, $text_to_link ) {
	if ( get_metadata( 'post', get_the_ID(), 'disable_linkify_text', true ) ) {
		$display_link = $old_text;
	}
	return $display_link;
}
add_filter( 'c2c_linkify_text_linked_text', 'selectively_disable_text_linkification', 10, 4 );
`

**c2c_linkify_text_link_attrs (filter)**

The 'c2c_linkify_text_link_attrs' hook allows you to add or customize attributes for the link.

Arguments:

* $attrs (array): The associative array of attributes to be used for the link. By default includes 'href'.
* $old_text (string): The text being replaced/linkified.
* $link (string): The URL that $old_text is to be linked to.

Example:

`
/**
 * Force links created by Linkify Text plugin to open in a new tab.
 *
 * @param array $attrs     The associative array of attributes to be used for the link.
 * @param string $old_text The text being replaced/linkified.
 * @param string $link     The URL that $old_text is to be linked to.
 * @return array
 */
function my_linkify_text_attrs( $attrs, $old_text, $link ) {
	$attrs['target'] = '_blank';
	return $attrs;
}
add_filter( 'c2c_linkify_text_link_attrs', 'my_linkify_text_attrs', 10, 3 );
`


== Changelog ==

= 1.9.1 (2018-07-19) =
* Fix: Ensure `mb_*` functions aren't used when not available
* Fix: Prevent conflicts with oembeds by firing at a lower hook priority
* New: Add a unit test related to multibyte text

= 1.9 (2018-07-05) =
Highlights:

* This release adds a setting for links to open in a new window, adds support for linkable text spanning multiple lines in your post, adds a filter for customizing link attributes, improves performance, and makes numerous behind-the-scenes improvements and changes.

Details:
* New: Add setting to set if links should open in a new window/tab
* New: Add filter 'c2c_linkify_text_link_attrs' for adding attributes to links
* New: Add support for finding linkable text that may span more than one line or consist of internal spaces
* Fix: Improve handling of removing links within links
* Change: Improve performance by checking for substring match for phrase to linkify before doing much work
* Change: Update plugin framework to 048
    * 048:
    * When resetting options, delete the option rather than setting it with default values
    * Prevent double "Settings reset" admin notice upon settings reset
    * 047:
    * Don't save default setting values to database on install
    * Change "Cheatin', huh?" error messages to "Something went wrong.", consistent with WP core
    * Note compatibility through WP 4.9+
    * Drop compatibility with version of WP older than 4.7
    * 046:
    * Fix `reset_options()` to reference instance variable `$options`
    * Note compatibility through WP 4.7+
    * Update copyright date (2017)
    * 045:
    * Ensure `reset_options()` resets values saved in the database
    * 044:
    * Add `reset_caches()` to clear caches and memoized data. Use it in `reset_options()` and `verify_config()`
    * Add `verify_options()` with logic extracted from `verify_config()` for initializing default option attributes
    * Add  `add_option()` to add a new option to the plugin's configuration
    * Add filter 'sanitized_option_names' to allow modifying the list of whitelisted option names
    * Change: Refactor `get_option_names()`
    * 043:
    * Disregard invalid lines supplied as part of hash option value
* Change: Bail early if filtering disables linking of the given text
* Change: Prevent PHP warnings by ensuring array elements exist before use
* Change: Cast return values of hooks to expected data types
* Change: Improve setting page help text
* New: Add README.md
* New: Add GitHub link to readme
* Change: Store setting name in constant
* Unit tests:
    * Change: Improve test initialization
    * Change: Improve tests for settings handling
    * Change: Default `WP_TESTS_DIR` to `/tmp/wordpress-tests-lib` rather than erroring out if not defined via environment variable
    * Change: Enable more error output for unit tests
    * New: Add more tests
    * New: Add header comments to bootstrap
* Change: Note compatibility through WP 4.9+
* Change: Drop compatibility with version of WP older than 4.7.
* Change: Tweak plugin description
* Change: Rename readme.txt section from 'Filters' to 'Hooks'
* Change: Modify formatting of hook name in readme to prevent being uppercased when shown in the Plugin Directory
* Change: Update installation instruction to prefer built-in installer over .zip file
* Change: Update copyright date (2018)

= 1.8 (2016-05-16) =
* New: Ensure longer, more precise link strings match before shorter strings that might also match, regardless of order defined.
* New: Linkify text within shortcode content, but not within the shortcode tags themselves.
* New: Add $text_to_link as additional optional argument to 'c2c_linkify_text_linked_text' filter.
* Bugfix: Fix being able to limit text replacements to just once a post.
* Bugfix: Honor setting to limit text replacements to just once a post for multibyte strings.
* Bugfix: Preserve capitalization of source string being linkified. Fixes case-sensitive matches where the source string is differently cased than defined in setting.
* Change: Update plugin framework to 043:
    * Change class name to c2c_LinkifyText_Plugin_043 to be plugin-specific.
    * Disregard invalid lines supplied as part of a hash option value.
    * Set textdomain using a string instead of a variable.
    * Don't load textdomain from file.
    * Change admin page header from 'h2' to 'h1' tag.
    * Add `c2c_plugin_version()`.
    * Formatting improvements to inline docs.
* Change: Add support for language packs:
    * Set textdomain using a string instead of a variable.
    * Remove .pot file and /lang subdirectory.
    * Remove 'Domain Path' plugin header.
* Change: Declare class as final.
* Change: Add more unit tests.
* Change: Explicitly declare methods in unit tests as public or protected.
* Change: Discontinue unnecessary use of `empty()`.
* Change: Minor code reformatting (spacing).
* Change: Minor documentation tweaks.
* Change: Note compatibility through WP 4.5+.
* Change: Remove support for versions of WordPress older than 4.1.
* Change: Update copyright date (2016).
* Change: Prevent direct invocation of test file.
* Change: Prevent web invocation of unit test bootstrap.php.
* New: Document 'c2c_linkify_text_linked_text' filter in readme.
* New: Create empty index.php to prevent files from being listed if web server has enabled directory listings.
* New: Add LICENSE file.

= 1.7 (2015-02-20) =
* Improve support of '&' in text to be linked by recognizing its encoded alternatives ('&amp;', '&#038;') as equivalents
* Prevent linkification of text if the provided link doesn't look anything like a link
* Change regex delimiter from '|' to '~'
* Minor refactoring of multibyte handling
* Add to and improve unit tests
* Add help text under primary textarea mentioning the term referencing feature
* Minor documentation changes throughout

= 1.6 (2015-02-12) =
* Prevent text replacements from taking place within shortcode attributes or content. props @rbonk
* Support linkifying multibyte strings. NOTE: Multibyte strings don't honor limiting their replacement within a piece of text to once
* Use preg_quote() to escape user input used in regex
* Update plugin framework to 039
* Add check to prevent execution of code if file is directly accessed
* Minor plugin header reformatting
* Minor code reformatting (spacing, bracing)
* Change documentation links to wp.org to be https
* Add an FAQ question
* Note compatibility through WP 4.1+
* Update copyright date (2015)
* Add an FAQ question
* Add more unit tests
* Add plugin icon
* Regenerate .pot

= 1.5 (2014-01-04) =
* Add setting to allow limiting linkification to once per term per text
* Add filter 'c2c_linkify_text_replace_once'
* Add ability for a term to use another term's link
* Change to just-in-time (rather than on init) determination if comments should be filtered
* Add linkify_comment_text()
* Add get_instance() static method for returning/creating singleton instance
* Made static variable 'instance' private
* Validate post is either int or string before handling
* Add unit tests
* Omit output of empty 'title' attribute for links
* Update plugin framework to 037
* Use explicit path for require_once()
* For options_page_description(), match method signature of parent class
* Discontinue use of explicit pass-by-reference for objects
* Code tweaks (spacing, bracing, rearranging)
* Documentation enhancements, additions, and tweaks
* Note compatibility through WP 3.8+
* Drop compatibility with version of WP older than 3.6
* Update copyright date (2014)
* Regenerate .pot
* Change donate link
* Add assets directory to plugin repository checkout
* Add banner
* Add screenshot

= 1.0.1 (unreleased) =
* Re-license as GPLv2 or later (from X11)
* Add 'License' and 'License URI' header tags to readme.txt and plugin file
* Add 'Upgrade Notice' section to readme.txt
* Remove ending PHP close tag
* Note compatibility through WP 3.4+
* Update copyright date (2012)

= 1.0 =
* Initial release


== Upgrade Notice ==

= 1.9.1 =
Minor bugfix release: prevented multibyte functions from being used if not available; prevented conflicts with oembeds.

= 1.9 =
Recommended update: added setting to open links in new window; added filter for customizing link attributes; improved performance; allow for linkable text to contain any number of whitespace; updated plugin framework to v048; compatibility is now WP 4.7-4.9; added README.md; more.

= 1.8 =
Recommended update: fixed to honor 'replace once' setting, including for multibyte strings; preserved capitalization of linkified text; matched longer strings before shorter strings; added support for language packs; compatibility is now WP 4.1-4.5+

= 1.7 =
Enhancement update: improved support for '&' in text to be linkified; no longer create a link when the link look anything like a URL or filename; minor refactoring; added more unit tests

= 1.6 =
Recommended update: prevented linkification of text within shortcodes; added support for linkifying multibyte text; updated plugin framework to version 039; noted compatibility through WP 4.1+; added plugin icon.

= 1.5 =
Recommended update: added ability to reference another term's link; added setting to allow limiting linkification to once per term per post; improved validation of data received; added unit tests; noted compatibility through WP 3.8+

= 1.0.1 =
Trivial update: noted compatibility through WP 3.4+; explicitly stated license
