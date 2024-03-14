<?php

// Standard shortcode
function dh_ptp_message_shortcode($atts)
{
	// extract id shortcode
    extract(shortcode_atts( array('id' => ''), $atts));  

    // check if id exists
    if ($id != '' ) {
    	global $features_metabox;
		$meta = get_post_meta($id, $features_metabox->get_the_id(), TRUE);

        // check if our pricing table contains any content
		if ($meta != "") {
            // if the table contains content, call the function that generates the table
			return do_shortcode(dh_ptp_generate_pricing_table($id));
		}
    }
	
	return __('Pricing table does not exist. Please check your shortcode.', 'easy-pricing-tables');
}
add_shortcode('easy-pricing-table', 'dh_ptp_message_shortcode');
