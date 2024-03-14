<?php
/**
 * BuddyPress Birthdays
 * Shortcodes
 *
 * @package BP_Birthdays/assets/inc
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;} // end if.

/**
 * Build The Custom Plugin Form
 * Display Anywhere Using Shortcode: [bb_custom_plugin_form]
 *
 * @param string $atts Shortcode atts.
 * @param string $content Content.
 */
function bb_custom_plugin_form_display( $atts, $content = null ) {
		extract(
			shortcode_atts(
				array(
					'el_class' => '',
					'el_id'    => '',
				),
				$atts
			)
		);

		$out  = '';
		$out .= '<div id="bb_custom_plugin_form_wrap" class="bb-form-wrap">';
		$out .= 'Hey! Im a cool new plugin named <strong>BuddyPress Birthdays!</strong>';
		$out .= '<form id="bb_custom_plugin_form">';
		$out .= '<p><input type="text" name="myInputField" id="myInputField" placeholder="Test Field: Test Ajax Responses"></p>';

		// Final Submit Button.
		$out .= '<p><input type="submit" id="submit_btn" value="Submit My Form"></p>';
		$out .= '</form>';
		// Form Ends.
		$out .= '</div><!-- bb_custom_plugin_form_wrap -->';
		return $out;
}
/**
 * Register All Shorcodes At Once
 */
function bb_register_shortcodes() {
	// Registered Shortcodes.
	add_shortcode( 'bb_custom_plugin_form', 'bb_custom_plugin_form_display' );
}
add_action( 'init', 'bb_register_shortcodes' );
