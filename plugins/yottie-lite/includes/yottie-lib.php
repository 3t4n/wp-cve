<?php

if (!defined('ABSPATH')) exit;


// register styles and scripts
function yottie_lite_lib() {
	wp_register_script('yottie-lite', plugins_url('assets/yottie-lite/dist/jquery.yottie-lite.bundled.js', YOTTIE_LITE_FILE), array(), YOTTIE_LITE_VERSION);
	wp_print_scripts('yottie-lite');
}
add_action('wp_footer', 'yottie_lite_lib');

?>