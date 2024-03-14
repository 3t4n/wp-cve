<?php
// if called directly, abort.
if (!defined('WPINC')) { die; }

function rsgd_shortcode($atts, $content=null){

	return '['.RSGD_PFX.' alias="'.esc_attr($atts['alias']).'"]';

}

add_shortcode('rsgd', 'rsgd_shortcode');
