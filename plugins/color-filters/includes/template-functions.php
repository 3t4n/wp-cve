<?php

/**
 * Create a shortcode to display the filtering controls
 * @since 3.0.0
 */
function ewd_uwcf_filters_shortcode( $atts ) {
	global $ewd_uwcf_controller;

	ewd_uwcf_load_view_files();

	$filtering = new ewduwcfViewFiltering( array() );

	$output = $filtering->render();

	return $output;
}
add_shortcode( 'ultimate-woocommerce-filters', 'ewd_uwcf_filters_shortcode' );

function ewd_uwcf_load_view_files() {

	$files = array(
		EWD_UWCF_PLUGIN_DIR . '/views/Base.class.php' // This will load all default classes
	);

	$files = apply_filters( 'ewd_uwcf_load_view_files', $files );

	foreach( $files as $file ) {
		require_once( $file );
	}
}

if ( ! function_exists( 'ewd_uwcf_get_shop_url' ) ) {
function ewd_uwcf_get_shop_url() {

	return get_permalink( wc_get_page_id( 'shop' ) );
}
}

if ( ! function_exists( 'ewd_uwcf_get_woocommerce_taxonomies' ) ) {
function ewd_uwcf_get_woocommerce_taxonomies() {
	global $wpdb;

	$wc_attribute_table_name = $wpdb->prefix . "woocommerce_attribute_taxonomies";
	
	return $wpdb->get_results( "SELECT * FROM $wc_attribute_table_name order by attribute_name ASC;" );
}
}

if ( ! function_exists( 'ewd_uwcf_get_attribute' ) ) {
function ewd_uwcf_get_attribute( $attribute_name ) {
	global $wpdb;

	$wc_attribute_table_name = $wpdb->prefix . "woocommerce_attribute_taxonomies";
	
	return $wpdb->get_row( $wpdb->prepare( "SELECT * FROM $wc_attribute_table_name WHERE attribute_name=%s;", $attribute_name ) );
}
}

if ( ! function_exists( 'ewd_hex_to_rgb' ) ) {
function ewd_check_font_size( $font_size ) {
	
	if ( is_numeric( $font_size ) ) { $font_size .= 'px'; }

	return $font_size;
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

if ( ! function_exists( 'ewd_format_classes' ) ) {
function ewd_format_classes( $classes ) {

	if ( count( $classes ) ) {
		return ' class="' . join( ' ', $classes ) . '"';
	}
}
}

if ( ! function_exists( 'ewd_add_frontend_ajax_url' ) ) {
function ewd_add_frontend_ajax_url() { ?>
    
    <script type="text/javascript">
        var ajaxurl = '<?php echo admin_url('admin-ajax.php'); ?>';
    </script>
<?php }
}

if ( ! function_exists( 'ewd_random_string' ) ) {
function ewd_random_string( $length = 10 ) {

	$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $randstring = '';

    for ( $i = 0; $i < $length; $i++ ) {

        $randstring .= $characters[ rand( 0, strlen( $characters ) - 1 ) ];
    }

    return $randstring;
}
}