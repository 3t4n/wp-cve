<?php


//CUSTOM ROW ACTIONS FOR LEGACY POSTS
function fca_ept_post_row_actions( $actions, $post ) {
    
    if ( $post->post_type == "easy-pricing-table" ) {
		
		$clone_query =  add_query_arg( array(
			'fca_ept_clone_table' => $post->ID,
			'ept_nonce' => wp_create_nonce( 'ept_clone' )
		));
		
		return array(
			'edit' => $actions['edit'],
			'duplicate' => "<a href='" . esc_url( $clone_query ) . "'>Make a Copy</a>",
			'trash' => $actions['trash'],
			'view' => $actions['view']
		);
		
	}
	return $actions;
}
add_filter( 'post_row_actions', 'fca_ept_post_row_actions', 10, 2 );

function dh_ptp_add_new_pricing_table_columns($gallery_columns) {
    $new_columns['cb'] = '<input type="checkbox" />';
    $new_columns['title'] = _x('Pricing Table Name', 'column name', 'easy-pricing-tables');    
    $new_columns['shortcode'] = __('Shortcode', 'easy-pricing-tables');
    $new_columns['date'] = _x('Date', 'column name', 'easy-pricing-tables');
 
    return $new_columns;
}
add_filter( 'manage_edit-easy-pricing-table_columns', 'dh_ptp_add_new_pricing_table_columns' );

function dh_ptp_manage_pricing_table_columns($column_name, $id) {
	
    switch ($column_name) {
	    case 'shortcode':
	        echo '<input type="text" style="width: 300px;" readonly="readonly" onclick="this.select()" value="[easy-pricing-table id=&quot;'. $id . '&quot;]"/>';
	            break;
	 
	    default:
	        break;
    } // end switch
}  
add_action( 'manage_easy-pricing-table_posts_custom_column', 'dh_ptp_manage_pricing_table_columns', 10, 2 );



/**
 * Preview functionality.
 * (Append the pricing table shortcode to the empty post.)
 * @param  [type] $content [description]
 * @return [type]          [description]
 */
function dh_ptp_live_preview($content){
    global $post;
    if ( is_user_logged_in() &&
    	 'easy-pricing-table' == get_post_type() && 
    	 is_main_query() )  {
		return $content . do_shortcode("[easy-pricing-table id={$post->ID}]");
	} else {
		return $content;
    }
}
add_filter( 'the_content', 'dh_ptp_live_preview');

/**
 * Redirect to 404 Page
 * Current user is not an admin
 * @param  [type] $content [description]
 * @return [type]          [description]
 */
function dh_ptp_404()
{
    // check is admin
    if( is_singular( 'easy-pricing-table' ) &&
    	!current_user_can( 'manage_options' ) ) {
    	
		global $wp_query;
	    $wp_query->set_404();
	    status_header(404);
	    nocache_headers();
	    include( get_query_template( '404' ) );
        die();
    }
}
add_action( 'wp', 'dh_ptp_404');

/**
 * Remove the publish metabox for pricing tables
 * @return [type] [description]
 */
function dh_ptp_remove_publish_metabox()
{
    remove_meta_box( 'submitdiv', 'easy-pricing-table', 'side' );
}
add_action( 'admin_menu', 'dh_ptp_remove_publish_metabox' );

/* Redirect when Save & Preview button is clicked */
add_filter('redirect_post_location', 'dh_ptp_save_preview_redirect');
function dh_ptp_save_preview_redirect ( $location )
{
    global $post;
 
    if (
        (isset($_POST['publish']) || isset($_POST['save'])) && preg_match("/post=([0-9]*)/", $location, $match) && $post &&
		$post->ID == $match[1] && (isset($_POST['publish']) || $post->post_status == 'publish') && $pl = get_permalink($post->ID)
		&& isset($_POST['dh_ptp_preview_url'])
    ) {
		// Flush rewrite rules
		global $wp_rewrite;
		$wp_rewrite->flush_rules(true);
		
        // Always redirect to the post
        $location = $_POST['dh_ptp_preview_url'];
    }
 
    return $location;
}

/* Number of Columns */
function dh_ptp_screen_layout_columns()
{
	global $current_screen;
	$current_user =  wp_get_current_user();
	
	if ( $current_screen->post_type == 'easy-pricing-table' ) {
		$user_id    = $current_user->ID;
		$prev_value = NULL;
		
		// Full width
		$screen_layout_option = get_user_meta($user_id, 'screen_layout_easy-pricing-table');
		if ( ! $screen_layout_option ) {
			update_user_meta($user_id, 'screen_layout_easy-pricing-table', 1, $prev_value);
		}
	}
}
add_action( 'admin_head-post.php'    , 'dh_ptp_screen_layout_columns' );
add_action( 'admin_head-post-new.php', 'dh_ptp_screen_layout_columns' );

/* Deal with parasite Post Type Switcher plugin */
function ptp_dh_pts_disable( $args ) {
    $postType  = get_post_type();
    if( 'easy-pricing-table' === $postType){
        $args = array(
          'name' => 'easy-pricing-table'
        );
    }
    return $args;
}
add_filter( 'pts_post_type_filter', 'ptp_dh_pts_disable' );


/**
 *  set screen layout to 2 colums
 */
if ( DH_PTP_LICENSE_PACKAGE === 'Free') {
	function tt_ptp_set_custom_branding_screen_layout($columns, $screen) {
		
		   if ($screen === 'easy-pricing-table'){
			$columns[$screen] = 2;
		}
		return $columns;
	}
	add_filter('screen_layout_columns', 'tt_ptp_set_custom_branding_screen_layout', 10, 2);

	function tt_ptp_user_option_screen_layout_easy_pricing_table() {
		
		$screen = get_current_screen();
		if ( 'easy-pricing-table' == $screen->id ) {
			 return 2;
		}
	   
	}
	add_filter( 'get_user_option_screen_layout_easy-pricing-table', 'tt_ptp_user_option_screen_layout_easy_pricing_table' );
}
