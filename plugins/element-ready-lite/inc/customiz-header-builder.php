<?php

if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly.
}


// wp Head
add_action('wp_head', function () {
	if (isset($_GET['elementor-preview'])) {
		return;
	}
?>
	<style>
		#element-ready-header-builder .element-ready-header-nav {
			display: none;
		}
	</style>
<?php
});

// elementor frontend after enqueue scripts 
add_action('elementor/frontend/after_enqueue_scripts', function () {
	if (isset($_GET['elementor-preview'])) {
		return;
	}
?>
	<style>
		#element-ready-header-builder .element-ready-header-nav {
			display: inherit;
		}
	</style>
<?php
});
