<?php
if ( ! defined( 'ABSPATH' ) ) { exit; }
# css
function foxtool_show_css() {
    global $foxtool_code_options;
    if (!empty($foxtool_code_options['code1'])){
        echo '<style>' . $foxtool_code_options['code1'] . '</style>';
    }
	if (!empty($foxtool_code_options['code11'])){
        echo '<style>@media (max-width: 849px){' . $foxtool_code_options['code11'] . '}</style>';
    }
	if (!empty($foxtool_code_options['code12'])){
        echo '<style>@media (max-width: 549px){' . $foxtool_code_options['code12'] . '}</style>';
    }
}
add_action('wp_head', 'foxtool_show_css');
# head
function foxtool_header_script() {
    global $foxtool_code_options;
    if (!empty($foxtool_code_options['code2'])){
        echo $foxtool_code_options['code2'];
    }
}
add_action('wp_head', 'foxtool_header_script');
# body
function foxtool_body_script() {
    global $foxtool_code_options;
    if (!empty($foxtool_code_options['code3'])) {
        echo $foxtool_code_options['code3'];
    }
}
add_action('wp_body_open', 'foxtool_body_script');
# footer
function foxtool_footer_script() {
    global $foxtool_code_options;
    if (!empty($foxtool_code_options['code4'])){
        echo $foxtool_code_options['code4'];
    }
}
add_action('wp_footer', 'foxtool_footer_script');
# login
function foxtool_login_script() {
	global $foxtool_code_options;
    if (!empty($foxtool_code_options['code5'])){
        echo $foxtool_code_options['code5'];
    }
add_action('login_head', 'foxtool_login_script', 1);
}



