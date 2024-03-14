<?php

	// add custom styling to header
	function wpc_custom_styling(){
		$wpc_primary_bg_color = get_option('wpc_primary_bg_color', '#ffffff');
	    $wpc_primary_color = get_option('wpc_primary_color', '#3adfa9');
	    $wpc_secondary_color = get_option('wpc_secondary_color', '#019ee5');
		
		$wpc_toolbar_buttons_color = get_option('wpc_toolbar_buttons_color', '#4f646d');
		$wpc_selected_bg_color = get_option('wpc_selected_bg_color', '#afafaf');
		$wpc_link_color = get_option('wpc_link_color', '#3adfa9');
		$wpc_standard_button_color = get_option('wpc_standard_button_color', '#4f646d');

	    $width = get_option('wpc_row_width', '100%');
	    $max_width = get_option('wpc_row_max_width', '1300px');

	    $h1 = get_option('wpc_h1_font_size');
	    $h2 = get_option('wpc_h2_font_size');
	    $h3 = get_option('wpc_h3_font_size');

	    $container_padding_top = get_option('wpc_container_padding_top'); 
	    $container_padding_top = wpc_esc_unit($container_padding_top, 'px');
	    $container_padding_bottom = get_option('wpc_container_padding_bottom'); 
	    $container_padding_bottom = wpc_esc_unit($container_padding_bottom, 'px');
	    $container_padding_left = get_option('wpc_container_padding_left'); 
	    $container_padding_left = wpc_esc_unit($container_padding_left, 'px');
	    $container_padding_right = get_option('wpc_container_padding_right'); 
	    $container_padding_right = wpc_esc_unit($container_padding_right, 'px');

	   	$container_margin_top = get_option('wpc_container_margin_top'); 
	    $container_margin_top = wpc_esc_unit($container_margin_top, 'px');
	    $container_margin_bottom = get_option('wpc_container_margin_bottom'); 
	    $container_margin_bottom = wpc_esc_unit($container_margin_bottom, 'px');

	    echo '<style>';

	    // styling shim for old quiz versions
	    if(!defined('WPCP_VERSION')) {
			echo '.wpc-fe-quiz-question-container {
				width: 100%;
				border-right: 0;
				float: none;
				padding: 0;
				min-height: 0;
			}';
		}

	    echo '.wpc-h1 {
	    	font-size: ' . $h1 . ' !important;
	    }';

	    echo '.wpc-h2 {
	    	font-size: ' . $h2 . ' !important;
	    }';

	    echo '.wpc-h3 {
	    	font-size: ' . $h3 . ' !important;
	    }';

	    echo '.wpc-main {
	    	width: ' . $width . ' !important;
	    	max-width: ' . $max_width . ' !important;
	    	padding-top: ' . $container_padding_top . ' !important;
	    	padding-bottom: ' . $container_padding_bottom . ' !important;
	    	padding-left: ' . $container_padding_left . ' !important;
	    	padding-right: ' . $container_padding_right . ' !important;
	    	margin-top: ' . $container_margin_top . ' !important;
	    	margin-bottom: ' . $container_margin_bottom . ' !important;
	    }';

	    echo ':root {
			--wpcbg: ' . esc_html( $wpc_primary_bg_color ) . ';
			--green: ' . esc_html( $wpc_primary_color ) . ';
			--blue: ' . esc_html( $wpc_secondary_color ) . ';
			--tool: ' . esc_html( $wpc_toolbar_buttons_color ) . ';
			--sele: ' . esc_html( $wpc_selected_bg_color ) . ';
			--link: ' . esc_html( $wpc_link_color ) . ';
			--stand: ' . esc_html( $wpc_standard_button_color ) . ';
	    }';

	    echo '</style>';

	}
	
	add_action('wp_head', 'wpc_custom_styling');
	add_action('admin_head', 'wpc_custom_styling');

?>