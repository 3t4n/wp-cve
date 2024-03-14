=== WP Deferred JavaScripts ===
Contributors: willybahuaud, Confridin, greglone
Tags: javascript, optimization, performance, deferring, labjs, asynchronous, speed
Requires at least: 3.0
Tested up to: 4.6
Text Domain: wp-deferred-javascripts
Domain Path: /languages/
Stable tag: 2.0.5
Version: 2.0.5
License: GPLv2 or later

Defer the loading of all JavaScripts added with `wp_enqueue_script()`, using LABJS (an asynchronous javascript library).

== Description ==

This plugin defer the loading of all JavaScripts added by the way of `wp_enqueue_script()`, using LABJS. The result is a significant optimization of loading time.

It is compatible with all WordPress JavaScript functions (`wp_localize_script()`, js in header, in footer...) and works with all well coded plugins.

If a plugin or a theme is not properly enqueuing scripts, your site may not work. Check this page: [Function Reference/wp_enqueue_script on WordPress Codex](http://codex.wordpress.org/Function_Reference/wp_enqueue_script).

LABjs (Loading And Blocking JavaScript) is an open-source (MIT license) project supported by [Getify Solutions](http://getify.com/).

We performed a range of tests to determine the potential benefit of loading time. On [wabeo](http://wabeo.fr) we executed [webwait](http://webwait.com/) (150 calls by test). Result is this plugin could **improve your loading time by 25%**!!
More information in the [Screenshots section](http://wordpress.org/extend/plugins/wp-deferred-javascripts/screenshots/).

You can find [more information about WP Deferred JavaScripts](http://www.seomix.fr/wp-deferred-javascript/) and [technical information about asynchronous scripts](http://wabeo.fr/blog/wordpress-javascripts-asynchrones/) on authors blogs.

== Installation ==

1. Upload the WP Deferred JavaScripts plugin to your blog and activate it.

2. Enjoy ^^

== Frequently Asked Questions ==

WP Deferred JavaScript includes some hooks. If you never used one of them, [check this page](http://codex.wordpress.org/Plugin_API). It's better to use those filters in a plugin or a mu-plugin.

= Exclude Scripts =

*do_not_defer* is a filter that took as a parameter an array containing scripts that should be handle normally.

Here is an example:

	// Normal script enqueue.
	add_action( 'wp_enqueue_scripts', 'register_canvas_script' );
	function register_canvas_script() {
		wp_register_script( 'my-canvas-script', 'http://exemple.com/myscript.js' );
		wp_enqueue_script( 'my-canvas-script' );
	}

	// Don't defer this script.
	add_filter( 'do_not_defer', 'exclude_canvas_script' );
	function exclude_canvas_script( $do_not_defer ) {
		$do_not_defer[] = 'my-canvas-script';
		return $do_not_defer;
	}

**Since 2.0.3 you can also use the WP Deferred JS settings pannel!**

= Change LABJS URL =

*wdjs_labjs_src* is a filter that allow you to change LabJS URL.
 ($lab_src, $lab_ver)

	// for example, I need a specific version of LabJS
	add_filter( 'wdjs_labjs_src', 'my_labjs_src', 10, 2 );
	function my_labjs_src( $src, $ver ) {
		if ( '2.0' !== $ver ) {
			// Hotlinking raw github is a bad practice, I did it just for the lulz.
			return 'https://raw.githubusercontent.com/getify/LABjs/edb9fed40dc224bc03c338be938cb586ef397fa6/LAB.min.js';
		}
		return $src;
	}

= HTML5 compatibility =

If you use HTM5, `wdjs_use_html5` is a filter that remove the `type="text/javascript"` attribute. You can use it this way:

	add_filter( 'wdjs_use_html5', '__return_true' );

= Change a script URL =

*wdjs_deferred_script_src* can be used to change the way one of the script is loaded. For example:

	// Here, I need to add information about the charset.
	add_filter( 'wdjs_deferred_script_src', '', 10, 3 );
	function change_my_script_src( $src_string, $handle, $src ) {
		// $src_string -> .script("http://exemple.com/myscript.js?v=2.0")
		// $handle -> my-script
		// $src -> http://exemple.com/myscript.js?v=2.0
		$out = array( 'src' => $src, 'charset' => 'iso-8859-1' );
		return '.wait(' . json_encode( $out ) . ')';
	}

= How to execute a code right after script loading =

You may need to execute a script right after its loading. You can use *wdjs_deferred_script_wait* filter to do it.

	add_action( 'wdjs_deferred_script_wait', 'after_my_script', 10, 2 );
	function after_my_script( $wait, $handle ) {
		if ( 'my-script' === $handle ) {
			$wait = 'function(){new MyScriptObject();}';
		}
		return $wait;
	}

= Execute a function when all scripts are loaded =

You may have to use inline JavaScript in your footer. If that's the case, you will have to use that last hook to make it compatible with WP Deferred JavaScripts.

You will have to wrap this inline JS into a new function. Then, you will have to use *wdjs_before_end_lab* to execute it.

	// This is a fake function that we are wrapping in a new function
	add_filter( 'before_shitty_plugin_print_js', 'wrap_this_code' );
	function wrap_this_code( $code ) {
		return 'function PluginShittyCode(){' . $code . '}';
	}

	add_filter( 'wdjs_before_end_lab', 'call_shitty_code' );
	function call_shitty_code( $end ) {
		$end .= '.PluginShittyCode()';
		return $end;
	}


== Screenshots ==

1. Average load time of **1.91** seconds **without WP Deferred JavaScripts activated** and scripts loaded in the header
2. Average load time of **1.99** seconds **without WP Deferred JavaScripts activated** and scripts queued in the footer
3. Average load time of **1.56** seconds **with WP Deferred JavaScripts activated** and scripts queued in the header
4. Average load time of **1.54** seconds **with WP Deferred JavaScripts activated** and scripts queued in the footer

== Changelog ==

= 2.0.5 =
* Solve problem encountered on [this support topic](https://wordpress.org/support/topic/problem-after-update-array_merge-argument-2-is-not-an-array?replies=2)

= 2.0.4 =
* do_not_defer can now accept scripts URI.
* New settings sub-pannel to exclude scripts from deferring, without using the plugin filter.
* Tested up to WordPress 4.4.2

= 2.0.2 =
* Minor bugfix: now the plugin catches some data added lately and include it in the plugin script tag (instead of letting the data create its own tag).

= 2.0.1 =
* Small code improvement.
* Prefix functions with `wdjs` instead of `sfdjs`.

= 2.0.0 =
* Overall code rewrite, by [Grégory Viguier](http://screenfeed.fr).
* New hooks.
* LabJS is loaded now loaded asynchronously.
* Conditional script are now supported.
* Bug fix: 404 error on scripts without source.
* Script dependency that should not be deferred are now excluded automatically.
* WP Deferred JavaScripts is compatible with [SF Cache Busting](http://www.screenfeed.fr/plugin-wp/sf-cache-busting/).

= 1.5.5 =
* Solve a problem when uri script contain "&amp;".
* Solve a bug while waiting dependencies.

= 1.5.4 =
* Prevent bug when scripts dependencies are not enqueued.

= 1.5.3 =
* Prevent a minor bug for footer enqueue script.

= 1.5.2 =
* Fixed a minor bug: bad priority while emptying `$wp_scripts`.

= 1.5.1 =
* Fixed a minor bug: plugin active was on login and register pages.

= 1.5 =
* Fixed a major bug: plugin active only in front end.

= 1.4 =
* Fixed a minor bug: some JavaScripts enqueued with very high priority were ignored - filter scripts are now hooked on *wp_print_scripts*.

= 1.3 =
* Fixed a major bug: files with dependencies are now waiting the loading of parent files before loading themselves.

= 1.2 =
* Data called after *wp_head*, but linked to a script queued into header are now considered by the plugin.

= 1.1 =
* Correction of some minor bugs
* Improve code readability

= 1.0 =
* Initial release