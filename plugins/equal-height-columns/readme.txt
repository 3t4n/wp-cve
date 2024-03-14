=== Equal Height Columns ===
Contributors: MIGHTYminnow, Braad, McGuive7
Donate link: http://mightyminnow.com
Tags: equal, height, column, div, element, jQuery, JavaScript
Requires at least: 3.5
Tested up to: 6.4.3
Stable tag: 1.2.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Easily equalize the height of columns or any collection of elements.

== Description ==

**Compatible with PHP 8.2**

**Like this plugin? Please consider [leaving a 5-star review](https://wordpress.org/support/view/plugin-reviews/equal-height-columns).**

Equal Height Columns lets you easily equalize the height of various columns and elements.

= Features =

* Target unlimited elements and element groups
* Specify simple CSS/jQuery selectors to target elements
* Specify breakpoint to kick in only at certain screen sizes
* Easy to use admin interface
* Heights are equalized immediately after the page has loaded
* Fully responsive (automatically updates on resize and orientationchange events)
* Works on mobile devices
* Works across all modern browsers (including IE8)
* Comes with custom event listener to manually trigger
* Super small - 8kB jQuery file size
* Trigger custom 'equalheight' event to force resize

= Instructions =

1. Navigate to **Settings > Equal Height Columns** in the WordPress admin.
2. Enter a *selector* and *breakpoint* for the first **column group**.
3. Add/remove column groups by clicking the "+ Add More" and "Remove" buttons.

= Advanced =

Want to trigger the equalizing of the heights manually? No problem. You can skip entering a selector on the settings page and call the jQuery script yourself using one of two functions:

`
jQuery( '.selector' ).initEqualHeights();

// Or

jQuery( '.selector' ).equalizeTheHeights();
`

The difference between these two functions is simply that `initEqualHeights()` will set up all the events for recalculating the heights when the window is resized or the global `equalheights` event is triggered, but `equalizeTheHeights()` will simply equalize the heights without involving any events.

Both functions take three optional arguments, the minimum height (number of pixels), maximum height, and the breakpoint (below which the heights will revert to their original size):

`
jQuery( '.selector' ).initEqualHeights( minHeight, maxHeight, breakPoint );
`

So an example might look like this:

`
jQuery( '.selector' ).initEqualHeights( 200, 500, 768 );
`

When entering a selector on the settings page or using the `initEqualHeights()` method this plugin also adds an event 'equalheights' to the window, allowing you to easily trigger the equalizing manually. This is useful if you have added new items to the page after it loads via AJAX. You can trigger the event like this:

`
jQuery( window ).trigger( 'equalheights' );
`

Another option for controlling which elements get equalized is the `equal_height_columns_elements` filter. This filter operates on the array of data that gets passed to the JS right before it is passed. This allows for developers to specify selectors that can't be deleted from the settings page, and for programmaticly building selectors based on dynamic data. Here's an example of how the filter can be used:

`
add_filter( 'equal_height_columns_elements', 'custom_ehc_elements' );
function custom_ehc_elements( $elements ) {

	$elements['element-groups']['custom'] = array(
		'selector'   => '.ehc-target', // Selector goes here.
		'breakpoint' => '768',
	);

	return $elements;
}
`

The keys on the `element-groups` array used by selectors entered on the settings page will come in as numbered indexes, so to avoid collision it's best to use named keys for any custom selectors (we're using 'custom' in the example above, but any unique string will work).

This plugin is [on Github](https://github.com/MIGHTYminnow/equal-height-columns) and pull requests are always welcome.

== Frequently Asked Questions ==

= Is this plugin fully responsive? =

Yes! When the function runs it creates event listeners for the window resize and orientationchange events and recalculates the heights after those events trigger. You can also specify a breakpoint under which the function will not affect the heights, allowing you to equalize the heights for larger screens but leave smaller screens unaffected.

= Does the plugin support multiple collections of items that get equalized independently? =

Yes! From the settings page you can enter as many selectors as you'd like, giving you the ability to equalize the heights of an unlimited number of items.

= What if I am dynamically adding elements to the page after it loads? =

The jQuery script uses the selector to always grab the items fresh from the DOM in its current state, so as long as the selector matches the newly added elements they will get included in the calculation. You can trigger the equalizing manually at any time (such as after new content has been added via AJAX) by triggering the 'equalheights' event on the window like this:

`
jQuery( window ).trigger( 'equalheights' );
`

Or if you'd prefer to just trigger the equalizing of the heights without involving any events, you can call the `equalizeTheHeights()` method directly like this:

`
jQuery( '.selector' ).equalizeTheHeights();
`

== Screenshots ==

1. The easy-to-use admin interface.

== Installation ==

= Manual Installation =

1. Upload the entire `/equal-height-columns` directory to the `/wp-content/plugins/` directory.
1. Activate Equal Height Columns through the 'Plugins' menu in WordPress.

== Changelog ==

### 1.2.0 ###
* NEW: Added multi-rows feature (javascript only).

### 1.1.4 ###
* Fire again Equal Height Columns if an image is lazy loaded inside the selector.

= 1.1.3 =
* Fix PHP warning.

= 1.1.2 =
* Update version number of the main javascript file to force clearing cache.

= 1.1.1 =
* BUGFIX: The plugin was not working with WordPress 5.6 due a jQuery update incompatibility.

= 1.1.0 =
* Add new method `equalizeTheHeights()` to allow direct equalizing of the heights without involving events
* Better code formatting and usage examples in the block comments
* Add new filter `equal_height_columns_elements`

= 1.0.3 =
* Fix JS error on activation (Uncaught TypeError: Cannot use 'in' operator to search for 'length' in...)

= 1.0.2 =
* Only load admin JS on EHC settings page
* Make admin settings wrapper class and jQuery more specific to avoid potential conflicts

= 1.0.1 =
* Improve admin (Mm)

= 1.0.0 =
* First release

== Upgrade Notice ==

= 1.1.0 =
* Add new method `equalizeTheHeights()` to allow direct equalizing of the heights without involving events
* Better code formatting and usage examples in the block comments
* Add new filter `equal_height_columns_elements`

= 1.0.3 =
* Fix JS error on activation (Uncaught TypeError: Cannot use 'in' operator to search for 'length' in...

= 1.0.2 =
* Only load admin JS on EHC settings page
* Make admin settings wrapper class and jQuery more specific to avoid potential conflicts

= 1.0.1 =
* Improve admin (Mm)

= 1.0.0 =
First Release
