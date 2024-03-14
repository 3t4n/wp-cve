=== Simple Ticker ===
Contributors: Katsushi Kawamori
Donate link: https://shop.riverforest-wp.info/donate/
Tags: block, shortcode, ticker, widget, woocommerce
Requires at least: 5.2
Requires PHP: 8.0
Tested up to: 6.5
Stable tag: 3.08
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Displays the ticker.

== Description ==

= Displays the ticker. =

It can display three own ticker.
It can view the Sticky Posts as ticker.
It can view the WooCommerce sale as ticker.
It supports the display of the widget and the short code and block.

= Filter hooks =
~~~
/** ==================================================
 * Filter for Inner text.
 * simple_ticker_1_inner_text
 * simple_ticker_2_inner_text
 * simple_ticker_3_inner_text
 *
 * @param $text1  Inner text.
 * @param $post_id  Post ID.
 *
 */
add_filter(
	'simple_ticker_1_inner_text', 
	function( $text1, $post_id ) {

		if ( 3309 == $post_id ) {
			$change  = 'Test';
			$changed = '<span style="color: #329BCB">' . esc_attr( $change ) . '</span>';
			$text1 = str_replace( $change, $changed, $text1 );
		}

		return $text1;
	},
	10,
	2
);
~~~

== Installation ==

1. Upload `simple-ticker` directory to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress

== Frequently Asked Questions ==

none

== Screenshots ==

1. Block
2. Settings 1
3. Settings 2
4. Settings 3

== Changelog ==

= 3.08 =
Rebuilt blocks.

= 3.07 =
Supported WordPress 6.4.
PHP 8.0 is now required.

= 3.06 =
Fixed problem of XSS via shortcode.

= 3.05 =
Added filter to edit text in ticker.

= 3.04 =
Supported WordPress 6.1.

= 3.03 =
Rebuilt blocks.

= 3.02 =
Rebuilt blocks.
Fixed admin screen.
Fixed uninstall.

= 3.01 =
Changed management screen.
Added URL options.

= 3.00 =
Added block.
Added beginning display.

= 2.11 =
Fixed an issue with blank saving of ticker text.

= 2.10 =
Supported WordPress 5.3.

= 2.09 =
Fixed problem of widget.

= 2.08 =
Conformed to the WordPress coding standard.

= 2.07 =
Fixed problem of interval days for sale.

= 2.06 =
Fixed problem of widget.

= 2.05 =
Fixed problem of specifying color with shortcode. 

= 2.04 =
Added a filter to insert before and after content.

= 2.03 =
Abolition of font tag.
Fixed problem of initial settings.

= 2.02 =
Fixed color problem.
Resurrected font tag.

= 2.01 =
Added currency symbol
Added discount text.
Abolition of font tag.

= 2.00 =
Ticker speed adjustment added.
The display of Woocommerce sale was added.

= 1.07 =
Removed unnecessary code.

= 1.06 =
Fixed fine problem.

= 1.05 =
Changed donate link.

= 1.04 =
Security measures.

= 1.03 =
Changed to 1 message on 1 line.

= 1.02 =
Fixed problem of Javascript.

= 1.01 =
Supported GlotPress. /languages directory is deleted.

= 1.0 =

== Upgrade Notice ==

= 3.06 =
Security measures.

= 1.04 =
Security measures.
