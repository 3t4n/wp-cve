<?php

if ( ! function_exists( 'ulb_add_custom_styling' ) ) {
function ulb_add_custom_styling() {
	global $ulb_controller;

	$styling = '<style>';
		if ( ! $ulb_controller->settings->get_setting( 'show-overlay-text' ) != '' ) { $styling .= '.ewd-ulb-slide-overlay { display: none !important; }'; }
		if ( $ulb_controller->settings->get_setting( 'styling-title-font' ) != '' ) { $styling .= '.ewd-ulb-slide-title { font-family: \'' . $ulb_controller->settings->get_setting( 'styling-title-font' ) . '\' !important; }'; }
		if ( $ulb_controller->settings->get_setting( 'styling-title-font-size' ) != '' ) { $styling .=  '.ewd-ulb-slide-title { font-size: ' . $ulb_controller->settings->get_setting( 'styling-title-font-size' ) . ' !important; }'; }
		if ( $ulb_controller->settings->get_setting( 'styling-title-font-color' ) != '' ) { $styling .=  '.ewd-ulb-slide-title { color: ' . $ulb_controller->settings->get_setting( 'styling-title-font-color' ) . ' !important; }'; }
		if ( $ulb_controller->settings->get_setting( 'styling-description-font' ) != '' ) { $styling .=  '.ewd-ulb-slide-description { font-family: ' . $ulb_controller->settings->get_setting( 'styling-description-font' ) . ' !important; }'; }
		if ( $ulb_controller->settings->get_setting( 'styling-description-font-size' ) != '' ) { $styling .=  '.ewd-ulb-slide-description { font-size: ' . $ulb_controller->settings->get_setting( 'styling-description-font-size') . ' !important; }'; }
		if ( $ulb_controller->settings->get_setting( 'styling-description-font-color' ) != '' ) { $styling .=  '.ewd-ulb-slide-description { color: ' . $ulb_controller->settings->get_setting( 'styling-description-font' ) . ' !important; }'; }
		if ( $ulb_controller->settings->get_setting( 'styling-arrow-color' ) != '' ) { $styling .=  '.ewd-ulb-arrow { color: ' . $ulb_controller->settings->get_setting( 'styling-arrow-color' ) . ' !important; }'; }
		if ( $ulb_controller->settings->get_setting( 'styling-arrow-size' ) != '' ) { $styling .=  '.ewd-ulb-arrow { font-size: ' . $ulb_controller->settings->get_setting( 'styling-arrow-size' ) . ' !important; }'; }
		if ( $ulb_controller->settings->get_setting( 'styling-arrow-background-color' ) != '' ) { 
			$styling .=  '.ewd-ulb-slide-control { background: rgb(' . ewd_hex_to_rgb( $ulb_controller->settings->get_setting( 'styling-arrow-background-color' ) ) . ') !important; }'; 
			if ( $ulb_controller->settings->get_setting( 'styling-arrow-background-opacity' ) != '' ) { $styling .=  '.ewd-ulb-slide-control { background: rgba( ' . ewd_hex_to_rgb( $ulb_controller->settings->get_setting( 'styling-arrow-background-color' ) ) . ', ' . $ulb_controller->settings->get_setting( 'styling-arrow-background-opacity' ) . ' !important;'; }
			if ( $ulb_controller->settings->get_setting( 'styling-arrow-background-hover-opacity' ) != '' ) { $styling .=  '.ewd-ulb-slide-control:hover background: rgba( ' . ewd_hex_to_rgb( $ulb_controller->settings->get_setting( 'styling-arrow-background-color' ) ) . ', ' . $ulb_controller->settings->get_setting( 'styling-arrow-background-hover-opacity' ) . ' !important;'; }
		}
		if ( $ulb_controller->settings->get_setting( 'styling-icon-color' ) != '' ) { $styling .=  '.ewd-ulb-control { color: ' . $ulb_controller->settings->get_setting( 'styling-icon-color' ) . ' !important; }'; }
		if ( $ulb_controller->settings->get_setting( 'styling-icon-size' ) != '' ) { $styling .=  '.ewd-ulb-control { font-size: ' . $ulb_controller->settings->get_setting( 'styling-icon-size' ) . ' !important; }'; }
		if ( $ulb_controller->settings->get_setting( 'styling-background-overlay-color' ) != '' ) { $styling .=  '.ewd-ulb-background { background: ' . $ulb_controller->settings->get_setting( 'styling-background-overlay-color' ) . ' !important; }'; }
		if ( $ulb_controller->settings->get_setting( 'styling-background-overlay-opacity' ) != '' ) { $styling .=  '.ewd-ulb-background { opacity: ' . $ulb_controller->settings->get_setting( 'styling-background-overlay-opacity' ) . ' !important; }'; }
		if ( $ulb_controller->settings->get_setting( 'styling-toolbar-color' ) != '' ) { 
			$styling .=  '.ewd-ulb-top-toolbar, .ewd-ulb-bottom-toolbar { background: rgb(' . ewd_hex_to_rgb( $ulb_controller->settings->get_setting( 'styling-toolbar-color' ) ) . ') !important; }';
			if ( $ulb_controller->settings->get_setting( 'styling-toolbar-opacity' ) != '' ) { $styling .=  '.ewd-ulb-top-toolbar, .ewd-ulb-bottom-toolbar { background: rgba( ' . ewd_hex_to_rgb( $ulb_controller->settings->get_setting( 'styling-toolbar-color' ) ) . ', ' . $ulb_controller->settings->get_setting( 'styling-toolbar-opacity' ) . ' !important;'; }
		}
		if ( $ulb_controller->settings->get_setting( 'styling-image-overlay-color' ) != '' ) { 
			$styling .=  '.ewd-ulb-slide .ewd-ulb-slide-overlay { background: rgb(' . ewd_hex_to_rgb( $ulb_controller->settings->get_setting( 'styling-image-overlay-color' ) ) . ') !important; }';
			if ( $ulb_controller->settings->get_setting( 'styling-image-overlay-opacity' ) != '' ) { $styling .=  '.ewd-ulb-slide .ewd-ulb-slide-overlay { background: rgba( ' . ewd_hex_to_rgb( $ulb_controller->settings->get_setting( 'styling-image-overlay-color' ) ) . ', ' . $ulb_controller->settings->get_setting( 'styling-image-overlay-opacity' ) . ' !important;'; }
		}
		if ( $ulb_controller->settings->get_setting( 'styling-thumbnail-bar-color' ) != '' ) { 
			$styling .=  '.ewd-ulb-bottom-thumbnail-bar, .ewd-ulb-top-thumbnail-bar { background: rgb(' . ewd_hex_to_rgb( $ulb_controller->settings->get_setting( 'styling-thumbnail-bar-color' ) ) . ') !important; }';
			if ( $ulb_controller->settings->get_setting( 'styling-thumbnail-bar-opacity' ) != '' ) { $styling .=  '.ewd-ulb-bottom-thumbnail-bar, .ewd-ulb-top-thumbnail-bar { background: rgba( ' . ewd_hex_to_rgb( $ulb_controller->settings->get_setting( 'styling-thumbnail-bar-color' ) ) . ', ' . $ulb_controller->settings->get_setting( 'styling-thumbnail-bar-opacity' ) . ' !important;'; }
		}
		if ( $ulb_controller->settings->get_setting( 'styling-thumbnail-scroll-arrow-color' ) != '' ) { $styling .=  '.ewd-thumbnail-scroll-button { color: ' . $ulb_controller->settings->get_setting( 'styling-thumbnail-scroll-arrow-color' ) . ' !important; }'; }
		if ( $ulb_controller->settings->get_setting( 'styling-thumbnail-active-border-color' ) != '' ) { $styling .=  '.ewd-ulb-active-thumbnail img { border-color: ' . $ulb_controller->settings->get_setting( 'styling-thumbnail-active-border-color' ) . ' !important; }'; }
	$styling .=   '</style>';

	return $styling;
}
}

if ( ! function_exists( 'ewd_hex_to_rgb' ) ) {
function ewd_hex_to_rgb( $hex ) {

	$hex = str_replace("#", "", $hex);

	// return if the string isn't a color code
	if ( strlen( $hex ) !== 3 and strlen( $hex ) !== 6 ) { return '0,0,0'; }

	if(strlen($hex) == 3) {
		$r = hexdec( substr( $hex, 0, 1 ) . substr( $hex, 0, 1 ) );
		$g = hexdec( substr( $hex, 1, 1 ) . substr( $hex, 1, 1 ) );
		$b = hexdec( substr( $hex, 2, 1 ) . substr( $hex, 2, 1 ) );
	} else {
		$r = hexdec( substr( $hex, 0, 2 ) );
		$g = hexdec( substr( $hex, 2, 2 ) );
		$b = hexdec( substr( $hex, 4, 2 ) );
	}

	$rgb = $r . ", " . $g . ", " . $b;
  
	return $rgb;
}
}

if ( ! function_exists( 'ewd_add_frontend_ajax_url' ) ) {
function ewd_add_frontend_ajax_url() { ?>
    
    <script type="text/javascript">
        var ajaxurl = '<?php echo admin_url('admin-ajax.php'); ?>';
    </script>
<?php }
}