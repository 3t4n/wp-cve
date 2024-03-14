<?php
/**
 * Aa single function that includes a filter(s) for filtering POST/GET/REQUEST/FILE (securing-input) (securing-output) (securing-input-output)
 *
 *
 * @since      4.4.64
 * @package    CTXFeed
 * @subpackage CTXFeed/filters
 * @author     Anwar <anwar.webappick@gmail.com>
 * @link       https://webappick.com
 */
// If this file is called directly, abort.
if (!defined('ABSPATH')) {
	exit;
}
if(!function_exists('CTXFEED_filter_securing_input_cb')){
	function CTXFEED_filter_securing_input_cb($meth = "POST", $inpu = '', $type = "text" ) {
		$outp = '';
		switch ( $type ) {
			case "email":
				$outp = sanitize_email($inpu);
				break;

			case "text":
				$outp = sanitize_text_field($inpu);
				break;

			case "textarea":
				$outp = sanitize_textarea_field($inpu);
				break;

			case "file_name":
				$outp = sanitize_file_name($inpu);
				break;
		}
		return $outp;

	}

	add_filter( 'CTXFEED_filter_securing_input', 'CTXFEED_filter_securing_input_cb', 10, 4 );
}

