=== Remove Redundant Links ===
Author URI:        http://toscho.de
Author:            toscho
Tags:              links, navigation, usability, formatting, filter
Requires at least: 3.0
Tested up to:      3.1
Stable tag:        trunk
Version:           1.7

Replaces links to the currently seen page.

== Description ==

Changes `<a>` elements pointing to the currently seen page by removing the `href` attribute and adding a descriptive `title`.

Compatible with most themes. Tested with TwentyTen

Example:
If you are on the page `/about/`

	<a href='http://example.com/about/'>About</a> 

will be converted to 
	
	<a title='You are here.' class='rrl current_page_item'>About</a>

and

	<link rel='author' href='/about/'>
	 
will be removed.

All changes apply to `GET` requests only.

Send me your bug reports and suggestions via my [contact page](http://toscho.de/kontakt/). 

== Installation ==

Upload the directory to your plugin directory. 
Activate the plugin through the 'Plugins' menu in WordPress.

== Frequently Asked Questions ==


= How can I change the replaced element, the title or the class name? =

The current settings are chosen for compatibility. 
To use other settings, you probably have to alter your theme.

You may alter the settings with a filter on `rrl_settings` in your `functions.php`.

Example:

	function change_rrl_settings( $settings )
	{
		$settings['class']     = 'my_own_class';
		$settings['title']     = 'Here be dragons';
		$settings['replace_a'] = 'span';
		
		return $settings;
	}
	
	add_filter( 'rrl_settings', 'change_rrl_settings', 10, 1 );



= How can I prevent the stripping of the server prefix for links to other pages on the same site? =

Same as above, set `strip_server_prefix_on_all_links` to `FALSE`:

	function rrl_prevent_server_prefix( $settings )
	{
	    $settings['strip_server_prefix_on_all_links']     = FALSE;
	    return $settings;
	}
	
	add_filter( 'rrl_settings', 'rrl_prevent_server_prefix', 10, 1);

== Changelog ==

* v1.0 Initial release
* v1.1 Fixed update blocker
* v1.2 Fixed regex to match links. `acronym` and `abbr` will not be matched anymore.
* v1.3 Fixed broken URIs on the frontpage.
* v1.4 Added an option `strip_server_prefix_on_all_links`. Defaults to `TRUE`. Set this to `FALSE` to keep absolute URIs. See the FAQ.
* v1.5 Fixed issue with `<link rel=canonical>`. Thanks to [eviluody](http://twitter.com/eviluody) for testing!
* v1.6 Fixed missing space in `<linkrel=canonical>`.
* v1.7 Don’t touch `<a rel=bookmark>`, the »permalink« in TwentyTen.

== Upgrade Notice ==
Doesn't touch rel=bookmark anymore