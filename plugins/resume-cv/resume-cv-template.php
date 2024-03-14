<?php
/*
 * Template Name: Resume CV Template
 * Description: A Page Template with a darker design.
 */

//require_once 'themes/shark/page.php';
$resumecv_options = get_option( 'resumecv_options');
$theme_dir = RESUMECV_PLUGIN_DIR . 'themes/shark';
$theme_dir_temp = resumecv_data($resumecv_options,'theme_dir');
if ( $theme_dir_temp != '' ) {
	$theme_dir = $theme_dir_temp;
}

// $_GET
$template = filter_input(INPUT_GET, 'template', FILTER_SANITIZE_STRING);
if ($template) {
	$theme_array = resumecv_theme_get();
	if ($theme_array) {
		foreach ($theme_array as $key => $value) {
			if ($template == $value) {
				$theme_dir = $key;
				break;
			}
		}
	}
}

$theme_page_file = $theme_dir . "/page.php";
if ( file_exists( $theme_page_file ) ) {
	require_once $theme_page_file;
} else {
	echo "<strong>". $theme_page_file . " is not found </strong>";
}