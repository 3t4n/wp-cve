<?php 

function ihss_scripts() {
	wp_enqueue_style('ihss-custom-style', plugin_dir_url( __FILE__ ).'style.css');
}
add_action('admin_enqueue_scripts', 'ihss_scripts');
?>