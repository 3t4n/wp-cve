<?php

//DEQUEUE CONFLICTING PLUGINS
add_action( 'admin_enqueue_scripts', 'dh_ptp_metabox_dequeue', 99999999 );
function dh_ptp_metabox_dequeue() {
	global $post_type;
	if( 'easy-pricing-table' == $post_type ) {
		if ( class_exists ( 'RichSnippets' ) ) {
			wp_dequeue_script( 'bsf_jquery_star' );
			wp_dequeue_script( 'bsf_toggle' );
			wp_dequeue_style( 'star_style' );
			wp_dequeue_script( 'bsf-scripts' );
			wp_dequeue_script( 'bsf-scripts-media' );
			wp_dequeue_style('jquery-style');
			wp_dequeue_style( 'meta_style');
			wp_dequeue_style( 'bsf-styles');
		}
		
		if ( class_exists ( 'Easy_Digital_Downloads' ) ) {
			wp_dequeue_style( 'jquery-chosen' );
			wp_dequeue_script( 'jquery-chosen' );
			wp_dequeue_script( 'jquery-form' );
			wp_dequeue_script( 'edd-admin-scripts' );
			wp_dequeue_script( 'wp-color-picker' );
			wp_dequeue_style( 'colorbox' );
			wp_dequeue_script( 'colorbox' );
			wp_dequeue_script( 'jquery-flot' );
			wp_dequeue_script( 'jquery-ui-datepicker' );
			wp_dequeue_script( 'jquery-ui-dialog' );
			wp_dequeue_script( 'jquery-ui-tooltip' );
			wp_dequeue_style( 'jquery-ui-css' );
			wp_dequeue_script( 'media-upload' );
			wp_dequeue_script( 'thickbox' );
			wp_dequeue_style( 'thickbox' );
			wp_dequeue_style( 'edd-admin' );
		}
		
		if ( class_exists ( 'PostSnippets' ) ) {
			wp_dequeue_script('jquery-ui-dialog');
			wp_dequeue_script('jquery-ui-tabs');
			wp_dequeue_style('wp-jquery-ui-dialog');
			wp_dequeue_style('post-snippets');
			wp_dequeue_script('post-snippets');
		}
		
		if ( class_exists ( 'Sensei_Main' ) ) {
			wp_dequeue_script( 'sensei-core-select2' );
			wp_dequeue_style( 'sensei-core-select2' );
		}

	}
}

//FIX CONFLICT WITH CALL TO ACTIONS PLUGIN
function dh_ptp_exclude_ept_cta( $exclude ) {
	
	$exclude[] = 'easy-pricing-table';
	
	return $exclude;
}

add_filter( 'cta_excluded_post_types', 'dh_ptp_exclude_ept_cta', 10, 1 );

function dh_ptp_metabox_styles_and_scripts() {
	
	$screen = get_current_screen();

    if( 'easy-pricing-table' === $screen->id ) {
		wp_enqueue_script( 'easy-palette-ept', PTP_PLUGIN_PATH_FOR_SUBDIRS . '/assets/ui/js/easy-palette.min.js' );
		wp_enqueue_script( 'color-palettes-ept', PTP_PLUGIN_PATH_FOR_SUBDIRS . '/assets/ui/js/color-palettes.min.js' );
		wp_enqueue_style( 'jquery-ui-fresh-ept', PTP_PLUGIN_PATH_FOR_SUBDIRS . '/assets/ui/jquery-ui-fresh.min.css' );
		//add bootstrap js for popovers - before the UI to prevent error
		wp_enqueue_script( 'bootstrap-popover-ept', PTP_PLUGIN_PATH_FOR_SUBDIRS . '/assets/ui/ui-components/bootstrap/js/bootstrap.min.js' );

		//add jquery accordion JS & CSS
		wp_enqueue_script('jquery-ui-accordion');
		wp_enqueue_style('dh-ptp-jquery-ui', PTP_PLUGIN_PATH_FOR_SUBDIRS . '/assets/ui/ui-accordion.min.css');
	
		//ui scripts - this file contains all Javascript necessary for the GUI
		wp_enqueue_script( 'ui-script-ept', PTP_PLUGIN_PATH_FOR_SUBDIRS . '/assets/ui/ui-scripts.min.js', array('jquery', 'jquery-ui-tabs', 'wp-color-picker', 'dh-ptp-colorbox') );	
		//UI styles - includes all styles necessary for the UI
		wp_enqueue_style('wpalchemy-metabox-ept', PTP_PLUGIN_PATH_FOR_SUBDIRS . '/assets/ui/ui-styles.min.css' );
		// Color Picker JS
		wp_enqueue_style( 'wp-color-picker' );		
		// Jquery UI Tabs
		wp_enqueue_script('jquery-ui-tabs');
		// Jquery lighbox - colorbox
		wp_enqueue_script( 'dh-ptp-colorbox', PTP_PLUGIN_PATH_FOR_SUBDIRS . '/assets/ui/ui-components/colorbox/jquery.colorbox-min.js', array('jquery') );
		/** UI Components **/
		//add bootstrap css for popover help boxes
		wp_enqueue_style('bootstrap-popover-ept', PTP_PLUGIN_PATH_FOR_SUBDIRS . '/assets/ui/ui-components/bootstrap/css/bootstrap.min.css' );
		// fontello icons
		wp_enqueue_style('fontello-icon-ept', PTP_PLUGIN_PATH_FOR_SUBDIRS . '/assets/ui/ui-components/fontello/fontello.min.css' );
		
		$codemirror = wp_enqueue_code_editor( [ 'type' => 'text/css', 'codemirror' => [ 'matchBrackets' => true, 'lineNumbers' => true, 'foldGutter' => true, 'gutters' => ['CodeMirror-linenumbers', 'CodeMirror-foldgutter'], 'autoRefresh' => true, 'lineWrapping' => true ] ] );
		wp_localize_script('ui-script-ept', 'code_mirror', $codemirror);

	}

}
add_action( 'admin_enqueue_scripts', 'dh_ptp_metabox_styles_and_scripts' );

?>