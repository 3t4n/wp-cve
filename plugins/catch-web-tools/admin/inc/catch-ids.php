<?php
/**
 * @package Admin
 * Fumction called when Catch IDs is enabled
 */

if ( ! function_exists( 'catchwebtools_catchids_column' ) ):
/**
 * Prepend the new column to the columns array
 */
function catchwebtools_catchids_column($cols) {
	$column_id = array( 'cwt_catchids' => __( 'ID', 'catch-web-tools' ) );
	$cols      = array_slice( $cols, 0, 1, true ) + $column_id + array_slice( $cols, 1, NULL, true );
	return $cols;
}
endif; // catchwebtools_catchids_column


if ( ! function_exists( 'catchwebtools_catchids_value' ) ) :
/**
 * Echo the ID for the new column
 */
function catchwebtools_catchids_value($column_name, $id) {
	if ( 'cwt_catchids' == $column_name ) {
		echo $id;
	}
}
endif; // catchwebtools_catchids_value


if ( ! function_exists( 'catchwebtools_catchids_return_value' ) ) :
function catchwebtools_catchids_return_value($value, $column_name, $id) {
	if ( 'cwt_catchids' == $column_name ) {
		$value .= $id;
	}
	return $value;
}
endif; // catchwebtools_catchids_return_value


if ( ! function_exists( 'catchwebtools_catchids_css' ) ) :
/**
 * Output CSS for width of new column
 */
function catchwebtools_catchids_css() {
?>
<style type="text/css">
    #cwt_catchids { width: 80px; }
    @media screen and (max-width: 782px) {
        .wp-list-table #cwt_catchids, .wp-list-table #the-list .cwt_catchids { display: none; }
        .wp-list-table #the-list .is-expanded .cwt_catchids {
            padding-left: 30px;
        }
    }
</style>
<?php
}
endif; // catchwebtools_catchids_css
add_action( 'admin_head', 'catchwebtools_catchids_css');


if ( ! function_exists( 'catchwebtools_catchids_add' ) ) :
/**
 * Actions/Filters for various tables and the css output
 */
function catchwebtools_catchids_add() {
	$settings = catchwebtools_get_options( 'catchwebtools_catchids' );

	if( $settings['status'] && ! is_plugin_active( 'catch-ids/catch-ids.php' ) ) {
		// For Media Management
		if( is_array( $settings ) && array_key_exists( 'media', $settings ) && ( 1 == $settings['media'] )  ) {
			add_action( 'manage_media_columns', 'catchwebtools_catchids_column' );
			add_filter( 'manage_media_custom_column', 'catchwebtools_catchids_value' , 10 , 3 );
		}

		// For Link Management
		add_action( 'manage_link_custom_column', 'catchwebtools_catchids_value', 10, 2 );
		add_filter( 'manage_link-manager_columns', 'catchwebtools_catchids_column' );

		// For Category Management
		add_action( 'manage_edit-link-categories_columns', 'catchwebtools_catchids_column' );
		add_filter( 'manage_link_categories_custom_column', 'catchwebtools_catchids_return_value', 10, 3 );
		
		// For Category, Tags and other custom taxonomies Management
		foreach( get_taxonomies() as $taxonomy ) {
			if( is_array( $settings ) && array_key_exists ( 'category', $settings ) && ( 1 == $settings['category'] )  ) {
				add_action( "manage_edit-${taxonomy}_columns" ,  'catchwebtools_catchids_column' );
				add_filter( "manage_${taxonomy}_custom_column" , 'catchwebtools_catchids_return_value' , 10 , 3 );
				if( version_compare($GLOBALS['wp_version'], '3.0.999', '>') ) {
					add_filter( "manage_edit-${taxonomy}_sortable_columns" , 'catchwebtools_catchids_column' );
				}
			}
		}

		foreach( get_post_types() as $ptype ) {
			if( is_array( $settings ) && array_key_exists ( $ptype, $settings ) && ( 1 == $settings[$ptype] ) ){
				add_action( "manage_edit-${ptype}_columns" ,        'catchwebtools_catchids_column' );
				add_filter( "manage_${ptype}_posts_custom_column" , 'catchwebtools_catchids_value' , 10 , 3 );
				if( version_compare($GLOBALS['wp_version'], '3.0.999', '>') ) {
					add_filter( "manage_edit-${ptype}_sortable_columns" , 'catchwebtools_catchids_column' );
				}
			}
		}

		// For User Management
		if( is_array( $settings ) && array_key_exists ( 'user', $settings ) && ( 1 == $settings['user'] )  ){
			add_action( 'manage_users_columns', 'catchwebtools_catchids_column' );
			add_filter( 'manage_users_custom_column', 'catchwebtools_catchids_return_value', 10, 3 );
			if( version_compare($GLOBALS['wp_version'], '3.0.999', '>') ) {
				add_filter( "manage_users_sortable_columns" , 'catchwebtools_catchids_column' );
			}
		}

		// For Comment Management
		if( is_array( $settings ) && array_key_exists ( 'comment', $settings ) && ( 1 == $settings['comment'] )  ){
			add_action( 'manage_edit-comments_columns', 'catchwebtools_catchids_column' );
			add_action( 'manage_comments_custom_column', 'catchwebtools_catchids_value', 10, 2 );
			if( version_compare($GLOBALS['wp_version'], '3.0.999', '>') ) {
				add_filter( "manage_edit-comments_sortable_columns" , 'catchwebtools_catchids_column' );
			}
		}
	}
}
endif; // catchwebtools_catchids_add
add_action( 'admin_init', 'catchwebtools_catchids_add');


if ( ! function_exists( 'catchwebtools_catchids_get_all_post_types' ) ) :
function catchwebtools_catchids_get_all_post_types() {
	$post_types = get_post_types( array( 'public' => true ) ); 
	$post_type_list = array();
    foreach ( $post_types as $key => $value  ) {
    	if( 'attachment' != $key ) {
	    	$data = str_replace('-', ' ', $value);
	    	$data = str_replace('_', ' ', $data);
	    	$post_type_list[$key] = ucwords( $data );
    	}
    }
    return $post_type_list;
}
endif; // catchwebtools_catchids_get_all_post_types

add_action( 'wp_ajax_catchwebtools_catchids_switch', 'catchwebtools_catchids_switch' );
if ( ! function_exists( 'catchwebtools_catchids_switch' ) ) :
function catchwebtools_catchids_switch() {

	// Check nonce beforehand
	if( ! check_ajax_referer( 'catchwebtools_catchids_nonce', 'catch_ids_nonce', false  ) ) {
		wp_die( esc_html__( 'Invalid Nonce', 'catch-web-tools' ) );
	}

	// Check user's capability
	if ( ! current_user_can( 'manage_options' ) ) {
		wp_die( esc_html__( 'Permission denied!', 'catch-web-tools' ) );
	}
	$value = ( 'true' == $_POST['value'] ) ? 1 : 0;

	$option_name = $_POST['option_name'];

	$option_value = catchwebtools_get_options( 'catchwebtools_catchids' );

	$option_value[$option_name] = $value;

	if( update_option( 'catchwebtools_catchids', $option_value ) ) {
    	echo $value;
    } else {
    	esc_html_e( 'Connection Error. Please try again.', 'catch-ids' );
    }

	wp_die(); // this is required to terminate immediately and return a proper response
}
endif; // catchwebtools_catchids_switch
