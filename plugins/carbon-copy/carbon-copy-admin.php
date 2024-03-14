<?php
// Admin only page
if( ! is_admin() )
	return;

require_once( dirname(__FILE__).'/carbon-copy-options.php' );

// Options wrapper for 'carbon_copy_version'
function carbon_copy_get_installed_version()
{
	return get_option( 'carbon_copy_version' );
}

// Constant wrapper for 'CARBON_COPY_CURRENT_VERSION'
function carbon_copy_get_current_version()
{
	return CARBON_COPY_CURRENT_VERSION;
}

add_action( 'admin_init', 'carbon_copy_admin_init' );
function carbon_copy_admin_init()
{
	carbon_copy_plugin_upgrade();
	if( get_option( 'carbon_copy_show_row' ) == 1 )
	{
		add_filter( 'post_row_actions', 'carbon_copy_make_duplicate_link_row', 10, 2 );
		add_filter( 'page_row_actions', 'carbon_copy_make_duplicate_link_row', 10, 2 );
	}
	if( get_option( 'carbon_copy_show_submitbox' ) == 1 )
	{
		add_action( 'post_submitbox_start', 'carbon_copy_add_carbon_copy_button' );
	}
	if( get_option( 'carbon_copy_show_original_column' ) == 1 )
	{
		carbon_copy_show_original_column();
	}
	if( get_option( 'carbon_copy_show_original_in_post_states' ) == 1 )
	{
		add_filter( 'display_post_states', 'carbon_copy_show_original_in_post_states', 10, 2 );
	}
	if( get_option( 'carbon_copy_show_original_meta_box' ) == 1 )
	{
		add_action( 'add_meta_boxes', 'carbon_copy_add_custom_box' );
		add_action( 'save_post', 'carbon_copy_save_quick_edit_data' );
	}
	
	// Connecting actions and filters to functions
	add_action( 'admin_action_carbon_copy_save_as_new_post', 'carbon_copy_save_as_new_post' );
	add_action( 'admin_action_carbon_copy_save_as_new_post_draft', 'carbon_copy_save_as_new_post_draft' );
	add_filter( 'removable_query_args', 'carbon_copy_add_removable_query_arg', 10, 1 );
	
	// Using action hooks
	add_action( 'cc_carbon_copy', 'carbon_copy_copy_post_meta_info', 10, 2 );
	add_action( 'cc_duplicate_page', 'carbon_copy_copy_post_meta_info', 10, 2 );
	
	if( get_option( 'carbon_copy_copychildren' ) == 1 )
	{
		add_action( 'cc_carbon_copy', 'carbon_copy_copy_children', 20, 3 );
		add_action( 'cc_duplicate_page', 'carbon_copy_copy_children', 20, 3 );
	}
	
	if( get_option( 'carbon_copy_copyattachments' ) == 1 )
	{
		add_action( 'cc_carbon_copy', 'carbon_copy_copy_attachments', 30, 2 );
		add_action( 'cc_duplicate_page', 'carbon_copy_copy_attachments', 30, 2 );
	}
	
	if( get_option( 'carbon_copy_copycomments' ) == 1 )
	{
		add_action( 'cc_carbon_copy', 'carbon_copy_copy_comments', 40, 2 );
		add_action( 'cc_duplicate_page', 'carbon_copy_copy_comments', 40, 2 );
	}
	
	add_action( 'cc_carbon_copy', 'carbon_copy_copy_post_taxonomies', 50, 2 );
	add_action( 'cc_duplicate_page', 'carbon_copy_copy_post_taxonomies', 50, 2 );
	add_action( 'admin_notices', 'carbon_copy_action_admin_notice' );
	
	add_filter( 'plugin_row_meta', 'carbon_copy_add_plugin_links', 10, 2 );
}

// Plugin upgrade
function carbon_copy_plugin_upgrade()
{
	$installed_version = carbon_copy_get_installed_version();
	if( $installed_version == carbon_copy_get_current_version() )
		return;
	if( empty( $installed_version ) )
	{
		// Get default roles
		$default_roles = array(
			3 => 'editor',
			8 => 'administrator',
		);
		// Cycle roles and assign capability if level >= 'carbon_copy_copy_user_level'
		foreach( $default_roles as $level => $name )
		{
			$role = get_role( $name );
			if( ! empty( $role ) )
				$role->add_cap( 'copy_posts' );
		}
	}
	else
	{
		$min_user_level = get_option( 'carbon_copy_copy_user_level' );
		if( ! empty( $min_user_level ) )
		{
			// Get default roles
			$default_roles = array(
				1 => 'contributor',
				2 => 'author',
				3 => 'editor',
				8 => 'administrator',
			);
			// Cycle roles and assign capability if level >= 'carbon_copy_copy_user_level'
			foreach( $default_roles as $level => $name )
			{
				$role = get_role( $name );
				if( $role && $min_user_level <= $level )
					$role->add_cap( 'copy_posts' );
			}
			delete_option( 'carbon_copy_copy_user_level' );
		}
	}

	add_option( 'carbon_copy_copytitle', '1' );
	add_option( 'carbon_copy_copydate', '0' );
	add_option( 'carbon_copy_copystatus', '0' );
	add_option( 'carbon_copy_copyslug', '0' );
	add_option( 'carbon_copy_copyexcerpt', '1' );
	add_option( 'carbon_copy_copycontent', '1' );
	add_option( 'carbon_copy_copythumbnail', '1' );
	add_option( 'carbon_copy_copytemplate', '1' );
	add_option( 'carbon_copy_copyformat', '1' );
	add_option( 'carbon_copy_copyauthor', '1' );
	add_option( 'carbon_copy_copypassword', '0' );
	add_option( 'carbon_copy_copyattachments', '0' );
	add_option( 'carbon_copy_copychildren', '0' );
	add_option( 'carbon_copy_copycomments', '0' );
	add_option( 'carbon_copy_copymenuorder', '0' );

	add_option( 'carbon_copy_widgets_classic', '0' );
  add_option( 'carbon_copy_widgets', '0' );
	add_option( 'carbon_copy_menus', '0' );
	
	// ! carbon copy roles
	
	add_option( 'carbon_copy_types_enabled', array( 'post', 'page' ) );

	add_option( 'carbon_copy_taxonomies_blacklist', array() );

	// ! prefix
	// ! suffix
	// ! increase menu order by
	add_option( 'carbon_copy_blacklist', '' );

	add_option( 'carbon_copy_show_row', '1' );
	add_option( 'carbon_copy_show_adminbar', '1' );
	add_option( 'carbon_copy_show_submitbox', '1' );
	add_option( 'carbon_copy_show_bulkactions', '0' );

	add_option( 'carbon_copy_show_original_column', '0' );
	add_option( 'carbon_copy_show_original_in_post_states', '0' );
	add_option( 'carbon_copy_show_original_meta_box', '0' );

	add_option( 'carbon_copy_cleaner', '1' );
	
	$taxonomies_blacklist = get_option( 'carbon_copy_taxonomies_blacklist' );
	
	if( $taxonomies_blacklist == "" )
		$taxonomies_blacklist = array();
	
	if( in_array( 'post_format', $taxonomies_blacklist ) )
	{
		update_option( 'carbon_copy_copyformat', 0 );
		$taxonomies_blacklist = array_diff( $taxonomies_blacklist, array( 'post_format' ) );
		update_option( 'carbon_copy_taxonomies_blacklist', $taxonomies_blacklist );
	}
	
	$meta_blacklist = explode( ",", get_option( 'carbon_copy_blacklist' ) );
	
	if( $meta_blacklist == "" )
		$meta_blacklist = array();
		$meta_blacklist = array_map( 'trim', $meta_blacklist );
		
	if( in_array( '_wp_page_template', $meta_blacklist ) )
	{
		update_option( 'carbon_copy_copytemplate', 0 );
		$meta_blacklist = array_diff( $meta_blacklist, array( '_wp_page_template' ) );	
	}
	
	if( in_array( '_thumbnail_id', $meta_blacklist ) )
	{
		update_option( 'carbon_copy_copythumbnail', 0 );
		$meta_blacklist = array_diff( $meta_blacklist, array( '_thumbnail_id' ) );
	}
	
	update_option( 'carbon_copy_blacklist', implode( ',', $meta_blacklist ) );

	delete_option( 'carbon_copy_admin_user_level' );
	delete_option( 'carbon_copy_create_user_level' );
	delete_option( 'carbon_copy_view_user_level' );
	
	delete_site_option( 'carbon_copy_version' );
	
	update_option( 'carbon_copy_version', carbon_copy_get_current_version() );
	
}

function carbon_copy_show_original_column()
{
	$carbon_copy_types_enabled = get_option( 'carbon_copy_types_enabled', array( 'post', 'page' ) );
	if( ! is_array( $carbon_copy_types_enabled ) )
	{
		$carbon_copy_types_enabled = array( $carbon_copy_types_enabled );
	}

	if( count( $carbon_copy_types_enabled ) )
	{
		foreach( $carbon_copy_types_enabled as $enabled_post_type )
		{
			add_filter( "manage_{$enabled_post_type}_posts_columns", 'carbon_copy_add_original_column' );
			add_action( "manage_{$enabled_post_type}_posts_custom_column", 'carbon_copy_show_original_item', 10, 2 );
		}
		add_action( 'quick_edit_custom_box', 'carbon_copy_quick_edit_remove_original', 10, 2 );
		add_action( 'save_post', 'carbon_copy_save_quick_edit_data' );
		add_action( 'admin_enqueue_scripts', 'carbon_copy_admin_enqueue_scripts' );
	}
}

function carbon_copy_add_original_column( $post_columns )
{
	$post_columns['carbon_copy_original_item'] = __( 'Original item', 'carbon-copy' );
	return $post_columns;
}

function carbon_copy_show_original_item( $column_name, $post_id )
{
	if( 'carbon_copy_original_item' === $column_name )
	{
		$column_value = '<span data-no_original>-</span>';
		$original_item = carbon_copy_get_original( $post_id );
		if( $original_item )
		{
			$column_value = carbon_copy_get_edit_or_view_link( $original_item );
		}
		echo $column_value;
	}
}

function carbon_copy_quick_edit_remove_original( $column_name, $post_type )
{
	if( 'carbon_copy_original_item' != $column_name )
	{
		return;
	}

	printf('<fieldset class="inline-edit-col-right" id="carbon_copy_quick_edit_fieldset">
	<div class="inline-edit-col">
		<label class="alignleft">
			<input type="checkbox" name="carbon_copy_remove_original" value="carbon_copy_remove_original">
			<span class="checkbox-title">%s</span>
		</label>
	</div>
	</fieldset>', __( 'Delete reference to original item: <span class="carbon_copy_original_item_title_span"></span>', 'carbon-copy' ) );
}

function carbon_copy_save_quick_edit_data( $post_id )
{
	if( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
	{
		return $post_id;
	}

	if( ! current_user_can( 'edit_post', $post_id ) )
	{
		return $post_id;
	}

	if( ! empty( $_POST['carbon_copy_remove_original'] ) )
	{
		delete_post_meta( $post_id, '_cc_original' );
	}
}

function carbon_copy_show_original_in_post_states( $post_states, $post )
{
	$original_item = carbon_copy_get_original( $post->ID );
	if( $original_item )
	{
		// translators: Original item link (to view or edit) title.
		$post_states['carbon_copy_original_item'] = sprintf( __( 'Original: %s', 'carbon-copy' ), carbon_copy_get_edit_or_view_link( $original_item ) );
	}
	return $post_states;
}

function carbon_copy_admin_enqueue_scripts( $hook )
{
	if( 'edit.php' === $hook )
	{
		###wp_enqueue_script( 'carbon_copy_admin_script', plugins_url( 'carbon-copy.js', __FILE__ ), false, CARBON_COPY_CURRENT_VERSION, true );
?>
<script>
(function( jQuery )
{
	// Copy of wp inline edit post function
	var $wp_inline_edit = inlineEditPost.edit;
	// Overwrite with custom function
	inlineEditPost.edit = function( id ) 
	{
		// Call original wp edit function, otherwise will hang
		$wp_inline_edit.apply( this, arguments );
		// Get post ID
		var $post_id = 0;
		if( typeof( id ) == 'object' )
		{
			$post_id = parseInt( this.getId( id ) );
		}
		if( $post_id > 0 )
		{
			// Define edit row
			var $edit_row = jQuery( '#edit-' + $post_id );
			var $post_row = jQuery( '#post-' + $post_id );
			// Get data
			var has_original = ( jQuery( '.carbon_copy_original_item span[data-no_original]', $post_row ).length === 0 );
			var original = jQuery( '.carbon_copy_original_item', $post_row ).html();
			// Populate data
			if( has_original )
			{
				jQuery( '.carbon_copy_original_item_title_span', $edit_row ).html( original );
				jQuery( '#carbon_copy_quick_edit_fieldset', $edit_row ).show();
			}
			else
			{
				jQuery( '#carbon_copy_quick_edit_fieldset', $edit_row ).hide();
				jQuery( '.carbon_copy_original_item_title_span', $edit_row ).html( '' );
			}
		}
	};
} )( jQuery );
</script>
<?php
	}
}

function carbon_copy_add_custom_box()
{
	$screens = get_option( 'carbon_copy_types_enabled' );
	if( ! is_array( $screens ) )
		$screens = array($screens);
	foreach( $screens as $screen )
	{
		add_meta_box(
			'carbon_copy_show_original', // Unique ID
			'Carbon Copy', // Box title
			'carbon_copy_custom_box_html', // Content callback, must be of type callable
			$screen, // Post type
			'side'
		);
	}
}

function carbon_copy_custom_box_html( $post )
{
	$original_item = carbon_copy_get_original( $post->ID );
	if( $original_item )
	{
?>
<label>
	<input type="checkbox" name="carbon_copy_remove_original" value="carbon_copy_remove_original">
	<?php printf( __( 'Delete reference to original item: <span class="carbon_copy_original_item_title_span">%s</span>', 'carbon-copy' ), carbon_copy_get_edit_or_view_link( $original_item ) ); ?>
</label>
<?php
	}
	else
	{
?>
<script>
(function(jQuery)
{
	jQuery( '#carbon_copy_show_original' ).hide();
})(jQuery);
</script>
<?php
	}
}

// Add link to action list for 'post_row_actions'
function carbon_copy_make_duplicate_link_row( $actions, $post )
{
	if( carbon_copy_is_current_user_allowed_to_copy() && carbon_copy_is_post_type_enabled( $post->post_type ) )
	{
		$title = _draft_or_post_title( $post );

		$copy_quick = esc_attr( sprintf( __( 'Quick Copy &#8220;%s&#8221;', 'carbon-copy' ), $title ) );
		$copy_draft = esc_attr( sprintf( __( 'Copy &#8220;%s&#8221;, Edit Draft', 'carbon-copy' ), $title ) );
		
		$actions['clone'] = '<a title="' . $copy_quick . '" href="'.carbon_copy_get_clone_post_link( $post->ID , 'display', false ).'" aria-label="' . $copy_quick . '">' .  esc_html__( 'Copy', 'carbon-copy' ) . '</a>';
		$actions['edit_as_new_draft'] = '<a title="' . $copy_draft . '" href="'. carbon_copy_get_clone_post_link( $post->ID ) .'" aria-label="' . $copy_draft . '">' .  esc_html__( 'Edit Copy', 'carbon-copy' ) . '</a>';
	}
	return $actions;
}

// Add Copy button to post/page edit screens
function carbon_copy_add_carbon_copy_button()
{
	if( isset( $_GET['post'] ) )
	{
		$id = intval( $_GET['post'] );
		$post = get_post( $id );
		if( carbon_copy_is_current_user_allowed_to_copy() && carbon_copy_is_post_type_enabled( $post->post_type ) )
		{
?>
<div id="duplicate-action">
	<a class="submitduplicate duplication" href="<?php echo esc_url( carbon_copy_get_clone_post_link( $id ) ); ?>"><?php esc_html_e( 'Copy to Draft', 'default' ); ?></a>
</div>
<?php
		}
	}
}

// Create new copy of selected post (as draft), redirects to edit post screen
function carbon_copy_save_as_new_post_draft()
{
	carbon_copy_save_as_new_post( 'draft' );
}

function carbon_copy_add_removable_query_arg( $removable_query_args )
{
	$removable_query_args[] = 'cloned';
	return $removable_query_args;
}

// Creates new copy of selected post (by default preserving the original publish stats), redirects to Posts list screen
function carbon_copy_save_as_new_post( $status = '' )
{
	if( ! carbon_copy_is_current_user_allowed_to_copy() )
	{
		wp_die( esc_html__( 'Current user is not allowed to copy posts.', 'carbon-copy' ) );
	}
	
	if( ! ( isset( $_GET['post'] ) || isset( $_POST['post'] ) || ( isset( $_REQUEST['action'] ) && 'carbon_copy_save_as_new_post' == $_REQUEST['action'] ) ) )
	{
		wp_die( esc_html__('No post to copy was supplied.', 'carbon-copy') );
	}

	// Get original post
	if( isset( $_GET['post'] ) && ! empty( $_GET['post'] ) )
	{
		$id = intval( $_GET['post'] );
	}
	else
	{
		$id = intval( $_POST['post'] );
	}
	
	check_admin_referer( 'carbon-copy_' . $id );
	
	$post = get_post( $id );	

	// Copy and insert post
	if( isset( $post ) && $post != null )
	{
		$post_type = $post->post_type;
		$new_id = carbon_copy_create_duplicate( $post, $status );
		
		if( $status == '' )
		{
			$sendback = wp_get_referer();
			if( ! $sendback ||
				strpos( $sendback, 'post.php' ) !== false ||
				strpos( $sendback, 'post-new.php' ) !== false )
			{
				if( 'attachment' == $post_type )
				{
					$sendback = admin_url( 'upload.php' );
				}
				else
				{
					$sendback = admin_url( 'edit.php' );
					if( ! empty( $post_type ) )
					{
						$sendback = add_query_arg( 'post_type', $post_type, $sendback );
					}
				}
			}
			else
			{
				$sendback = remove_query_arg( array( 'trashed', 'untrashed', 'deleted', 'cloned', 'ids' ), $sendback );
			}
			// Redirect to post list screen
			wp_redirect( add_query_arg( array( 'cloned' => 1, 'ids' => $post->ID), $sendback ) );
		}
		else
		{
			// Redirect to edit screen for new draft
			wp_redirect( add_query_arg( array( 'cloned' => 1, 'ids' => $post->ID), admin_url( 'post.php?action=edit&post=' . $new_id ) ) );
		}
		exit;

	}
	else
	{
		wp_die( esc_html__( 'Copy creation failed, could not find original:', 'carbon-copy' ) . ' ' . htmlspecialchars( $id ) );
	}
}

// Copy post taxonomies to another post
function carbon_copy_copy_post_taxonomies( $new_id, $post )
{
	global $wpdb;

	if( isset( $wpdb->terms ) )
	{
		// Clear default category (added by 'wp_insert_post')
		wp_set_object_terms( $new_id, NULL, 'category' );

		$post_taxonomies = get_object_taxonomies( $post->post_type );
		// several plugins just add support to post-formats but don't register post_format taxonomy
		if( post_type_supports( $post->post_type, 'post-formats' ) && ! in_array( 'post_format', $post_taxonomies ) )
		{
			$post_taxonomies[] = 'post_format';
		}
		
		$taxonomies_blacklist = get_option( 'carbon_copy_taxonomies_blacklist' );
		if( $taxonomies_blacklist == "" ) $taxonomies_blacklist = array();
		if( get_option( 'carbon_copy_copyformat' ) == 0 )
		{
			$taxonomies_blacklist[] = 'post_format';
		}
		$taxonomies = array_diff( $post_taxonomies, $taxonomies_blacklist );
		foreach( $taxonomies as $taxonomy )
		{
			$post_terms = wp_get_object_terms( $post->ID, $taxonomy, array( 'orderby' => 'term_order' ) );
			$terms = array();
			for( $i=0; $i<count( $post_terms ); $i++ )
			{
				$terms[] = $post_terms[$i]->slug;
			}
			wp_set_object_terms( $new_id, $terms, $taxonomy );
		}
	}
}

// Copy post meta to another post
function carbon_copy_copy_post_meta_info( $new_id, $post )
{
	$post_meta_keys = get_post_custom_keys( $post->ID );
	if( empty( $post_meta_keys ) )
		return;
	$meta_blacklist = get_option( 'carbon_copy_blacklist' );
	if( $meta_blacklist == "" )
	{
		$meta_blacklist = array();
	}
	else
	{
		$meta_blacklist = explode( ',', $meta_blacklist );
		$meta_blacklist = array_filter( $meta_blacklist );
		$meta_blacklist = array_map( 'trim', $meta_blacklist );
	}	
	$meta_blacklist[] = '_edit_lock'; // edit lock
	$meta_blacklist[] = '_edit_last'; // edit lock
	if( get_option( 'carbon_copy_copytemplate' ) == 0 )
	{
		$meta_blacklist[] = '_wp_page_template';
	}
	if( get_option( 'carbon_copy_copythumbnail' ) == 0 )
	{
		$meta_blacklist[] = '_thumbnail_id';
	}
	
	$meta_blacklist = apply_filters( 'carbon_copy_blacklist_filter', $meta_blacklist );
	
	$meta_blacklist_string = '('.implode(')|(',$meta_blacklist).')';
	if( strpos( $meta_blacklist_string, '*' ) !== false )
	{
		$meta_blacklist_string = str_replace( array('*'), array('[a-zA-Z0-9_]*'), $meta_blacklist_string );
	
		$meta_keys = array();
		foreach( $post_meta_keys as $meta_key )
		{
			if( ! preg_match( '#^'.$meta_blacklist_string.'$#', $meta_key ) )
				$meta_keys[] = $meta_key;
		}
	}
	else
	{
		$meta_keys = array_diff( $post_meta_keys, $meta_blacklist );
	}

	$meta_keys = apply_filters( 'carbon_copy_meta_keys_filter', $meta_keys );

	foreach( $meta_keys as $meta_key )
	{
		$meta_values = get_post_custom_values( $meta_key, $post->ID );
		foreach( $meta_values as $meta_value )
		{
			$meta_value = maybe_unserialize( $meta_value );
			add_post_meta( $new_id, $meta_key, carbon_copy_wp_slash( $meta_value ) );
		}
	}
}

// Patch for inconsistent 'wp_slash', works only with WP 4.4+ (map_deep)
function carbon_copy_addslashes_deep( $value )
{
	if( function_exists( 'map_deep' ) )
	{
		return map_deep( $value, 'carbon_copy_addslashes_to_strings_only' );
	}
	else
	{
		return wp_slash( $value );
	}
}

function carbon_copy_addslashes_to_strings_only( $value )
{
	return is_string( $value ) ? addslashes( $value ) : $value;
}

function carbon_copy_wp_slash( $value )
{ 
	return carbon_copy_addslashes_deep( $value ); 
} 

// Copy post attachments to another post
function carbon_copy_copy_attachments( $new_id, $post )
{
	// Get thumbnail ID
	$old_thumbnail_id = get_post_thumbnail_id( $post->ID );
	// Get children
	$children = get_posts( array( 'post_type' => 'any', 'numberposts' => -1, 'post_status' => 'any', 'post_parent' => $post->ID ) );
	// Clone old attachments
	foreach( $children as $child )
	{
		if( $child->post_type != 'attachment' )
			continue;
		$url = wp_get_attachment_url( $child->ID );
		// Copy file
		$tmp = download_url( $url );
		if( is_wp_error( $tmp ) )
		{
			@unlink( $tmp );
			continue;
		}
		$desc = wp_slash( $child->post_content );
		$file_array = array();
		$file_array['name'] = basename( $url );
		$file_array['tmp_name'] = $tmp;
		// Upload media collection
		$new_attachment_id = media_handle_sideload( $file_array, $new_id, $desc );

		if( is_wp_error( $new_attachment_id ) )
		{
			@unlink($file_array['tmp_name']);
			continue;
		}
		$new_post_author = wp_get_current_user();
		$cloned_child = array(
				'ID' => $new_attachment_id,
				'post_title' => $child->post_title,
				'post_excerpt' => $child->post_excerpt,
				'post_content' => $child->post_content,
				'post_author'  => $new_post_author->ID
		);
		wp_update_post( wp_slash( $cloned_child ) );
		$alt_title = get_post_meta( $child->ID, '_wp_attachment_image_alt', true );
		if( $alt_title ) update_post_meta( $new_attachment_id, '_wp_attachment_image_alt', wp_slash( $alt_title ) );
		// If post thumbnail was copied, set new thumbnail copy for new post
		if( get_option( 'carbon_copy_copythumbnail' ) == 1 && $old_thumbnail_id == $child->ID )
		{
			set_post_thumbnail( $new_id, $new_attachment_id );
		}
	}
}

// Copy children posts
function carbon_copy_copy_children( $new_id, $post, $status = '' )
{
	// Get children
	$children = get_posts( array( 'post_type' => 'any', 'numberposts' => -1, 'post_status' => 'any', 'post_parent' => $post->ID ) );
	// Clone old attachments
	foreach( $children as $child )
	{
		if( $child->post_type == 'attachment' )
			continue;
		carbon_copy_create_duplicate( $child, $status, $new_id );
	}
}

// Copy post comments to another post
function carbon_copy_copy_comments( $new_id, $post )
{
	$comments = get_comments( array(
		'post_id' => $post->ID,
		'order' => 'ASC',
		'orderby' => 'comment_date_gmt'
	));

	$old_id_to_new = array();
	foreach( $comments as $comment )
	{
		// Do Not copy pingbacks or trackbacks
		if( $comment->comment_type === "pingback" || $comment->comment_type === "trackback" )
			continue;
		$parent = ( $comment->comment_parent && $old_id_to_new[$comment->comment_parent] ) ? $old_id_to_new[$comment->comment_parent] : 0;
		$commentdata = array(
			'comment_post_ID' => $new_id,
			'comment_author' => $comment->comment_author,
			'comment_author_email' => $comment->comment_author_email,
			'comment_author_url' => $comment->comment_author_url,
			'comment_content' => $comment->comment_content,
			'comment_type' => $comment->comment_type,
			'comment_parent' => $parent,
			'user_id' => $comment->user_id,
			'comment_author_IP' => $comment->comment_author_IP,
			'comment_agent' => $comment->comment_agent,
			'comment_karma' => $comment->comment_karma,
			'comment_approved' => $comment->comment_approved,
		);
		if( get_option( 'carbon_copy_copydate' ) == 1 )
		{
			$commentdata['comment_date'] = $comment->comment_date ;
			$commentdata['comment_date_gmt'] = get_gmt_from_date( $comment->comment_date );
		}
		$new_comment_id = wp_insert_comment( $commentdata );
		$old_id_to_new[$comment->comment_ID] = $new_comment_id;
	}
}

// Create post copy
function carbon_copy_create_duplicate( $post, $status = '', $parent_id = '' )
{
	do_action( 'carbon_copy_pre_copy' );

	if( ! carbon_copy_is_post_type_enabled( $post->post_type ) && $post->post_type != 'attachment' )
		wp_die( esc_html__( 'Copy features for this post type are not enabled in options page', 'carbon-copy' ) );

	$new_post_status = ( empty( $status ) )? $post->post_status : $status;
	
	if( $post->post_type != 'attachment' )
	{
		$prefix = sanitize_text_field( get_option( 'carbon_copy_title_prefix' ) );
		$suffix = sanitize_text_field( get_option( 'carbon_copy_title_suffix' ) );
		$title = ' ';
		if( get_option( 'carbon_copy_copytitle' ) == 1 )
		{
			$title = $post->post_title;
			if( ! empty( $prefix ) ) $prefix.= " ";
			if( ! empty( $suffix ) ) $suffix = " ".$suffix;
		}
		else
		{
			$title = ' ';
		}
		$title = trim( $prefix.$title.$suffix );

		if( $title == '' )
		{
			// empty title
			$title = __( 'Untitled', 'default' );
		}
		if( get_option( 'carbon_copy_copystatus' ) == 0 )
		{
			$new_post_status = 'draft';
		}
		else
		{
			if( 'publish' == $new_post_status || 'future' == $new_post_status )
			{
				// check user permissions / capabilities
				if( is_post_type_hierarchical( $post->post_type ) )
				{
					if( ! current_user_can( 'publish_pages' ) )
					{
						$new_post_status = 'pending';
					}
				}
				else
				{
					if( ! current_user_can( 'publish_posts' ) )
					{
						$new_post_status = 'pending';
					}
				}
			}
		}
	}	
	
	$new_post_author = wp_get_current_user();
	$new_post_author_id = $new_post_author->ID;
	
	if( get_option( 'carbon_copy_copyauthor' ) == '1' )
	{
		// check user permissions / capabilities
		if( is_post_type_hierarchical( $post->post_type ) )
		{
			if( current_user_can( 'edit_others_pages' ) )
			{
				$new_post_author_id = $post->post_author;
			}
		}
		else
		{
			if( current_user_can( 'edit_others_posts' ) )
			{
				$new_post_author_id = $post->post_author;
			}
		}
	}
	
	$menu_order = ( get_option( 'carbon_copy_copymenuorder' ) == '1' ) ? $post->menu_order : 0;
	$increase_menu_order_by = get_option('carbon_copy_increase_menu_order_by' );
	if( ! empty( $increase_menu_order_by ) && is_numeric( $increase_menu_order_by ) )
	{
		$menu_order += intval( $increase_menu_order_by );
	}
	
	$post_name = $post->post_name;
	
	if( get_option( 'carbon_copy_copyslug' ) != 1 )
	{
		$post_name = '';
	}

	$new_post = array(
		'menu_order' => $menu_order,
		'comment_status' => $post->comment_status,
		'ping_status' => $post->ping_status,
		'post_author' => $new_post_author_id,
		'post_content' => (get_option('carbon_copy_copycontent') == '1') ? $post->post_content : "" ,
		'post_content_filtered' => (get_option('carbon_copy_copycontent') == '1') ? $post->post_content_filtered : "" ,			
		'post_excerpt' => (get_option('carbon_copy_copyexcerpt') == '1') ? $post->post_excerpt : "",
		'post_mime_type' => $post->post_mime_type,
		'post_parent' => $new_post_parent = empty($parent_id)? $post->post_parent : $parent_id,
		'post_password' => (get_option('carbon_copy_copypassword') == '1') ? $post->post_password: "",
		'post_status' => $new_post_status,
		'post_title' => $title,
		'post_type' => $post->post_type,
		'post_name' => $post_name
	);

	if( get_option( 'carbon_copy_copydate' ) == 1 )
	{
		$new_post['post_date'] = $new_post_date =  $post->post_date;
		$new_post['post_date_gmt'] = get_gmt_from_date( $new_post_date );
	}

	$new_post_id = wp_insert_post( wp_slash( $new_post ) );

	// If you have written a plugin which uses non-WP database tables to save
	// information about a post you can hook this action to duplicate that data.
	
	if( $new_post_id !== 0 && ! is_wp_error( $new_post_id ) )
	{
		if( $post->post_type == 'page' || is_post_type_hierarchical( $post->post_type ) )
			do_action( 'cc_duplicate_page', $new_post_id, $post, $status );
		else
			do_action( 'cc_carbon_copy', $new_post_id, $post, $status );
	
		delete_post_meta( $new_post_id, '_cc_original' );
		add_post_meta( $new_post_id, '_cc_original', $post->ID );
		do_action( 'carbon_copy_post_copy' );
	}
	return $new_post_id;
}

// Admin notices
function carbon_copy_action_admin_notice()
{
	if( ! empty( $_REQUEST['cloned'] ) )
	{
		$copied_posts = intval( $_REQUEST['cloned'] );
		printf( '<div id="message" class="updated fade"><p>' . 
		_n( '%s item copied.',
        '%s items copied.',
        $copied_posts,
		'carbon-copy'
		) . '</p></div>', $copied_posts );
    	remove_query_arg( 'cloned' );
	}
}

// Plugin page additional links
function carbon_copy_add_plugin_links( $links, $file )
{
	if( $file == plugin_basename( dirname(__FILE__).'/carbon-copy.php' ) )
	{
		#$links[] = '<a href="https://endurtech.com/carbon-copy-wordpress-plugin/" aria-label="' . esc_attr__('Documentation for Carbon Copy', 'carbon-copy') . '">' . esc_html__('Documentation', 'carbon-copy') . '</a>';
		$links[] = '<a href="https://endurtech.com/give-thanks/" aria-label="' . esc_attr__( 'Support future improvments Donate to Carbon Copy', 'carbon-copy' ) . '">' . '<strong>' . esc_html__( 'Donate', 'carbon-copy' ) . '</strong></a>';
	}
	return $links;
}

// Bulk actions
add_action( 'admin_init', 'carbon_copy_add_bulk_filters' );
function carbon_copy_add_bulk_filters()
{
	if( get_option( 'carbon_copy_show_bulkactions' ) != 1 )
	{
		return;
	}
	if( ! carbon_copy_is_current_user_allowed_to_copy() )
	{
		return;
	}
	$carbon_copy_types_enabled = get_option( 'carbon_copy_types_enabled', array( 'post', 'page' ) );
	if( ! is_array( $carbon_copy_types_enabled ) )
		$carbon_copy_types_enabled = array( $carbon_copy_types_enabled );
	foreach( $carbon_copy_types_enabled as $carbon_copy_type_enabled )
	{
		add_filter( "bulk_actions-edit-{$carbon_copy_type_enabled}", 'carbon_copy_register_bulk_action' );
		add_filter( "handle_bulk_actions-edit-{$carbon_copy_type_enabled}", 'carbon_copy_action_handler', 10, 3 );
	}
}

function carbon_copy_register_bulk_action( $bulk_actions )
{
	$bulk_actions['carbon_copy_clone'] = esc_html__( 'Copy', 'carbon-copy' );
	return $bulk_actions;
}

function carbon_copy_action_handler( $redirect_to, $doaction, $post_ids )
{
	if( $doaction !== 'carbon_copy_clone' )
	{
		return $redirect_to;
	}
	$counter = 0;
	foreach( $post_ids as $post_id )
	{
		$post = get_post( $post_id );
		if( ! empty( $post ) )
		{
			if( get_option( 'carbon_copy_copychildren' ) != 1
				|| ! is_post_type_hierarchical( $post->post_type )
				|| ( is_post_type_hierarchical( $post->post_type ) && ! carbon_copy_has_ancestors_marked( $post, $post_ids ) ) )
			{
				if( carbon_copy_create_duplicate( $post ) )
				{
					$counter++;
				}
			}
		}
	}
	$redirect_to = add_query_arg( 'cloned', $counter, $redirect_to );
	return $redirect_to;
}

function carbon_copy_has_ancestors_marked( $post, $post_ids )
{
	$ancestors_in_array = 0;
	$parent = $post->ID;
	while( $parent = wp_get_post_parent_id( $parent ) )
	{
		if( in_array( $parent, $post_ids ) )
		{
			$ancestors_in_array++;
		}
	}
	return( $ancestors_in_array !== 0 );
}

// Carbon Copy Restore Classic Widgets
$carbon_copy_widgets_init = get_option( 'carbon_copy_widgets_classic' );
if( $carbon_copy_widgets_init == '1' )
{
  // Disable block editor widget management in Gutenberg.
  add_filter( 'gutenberg_use_widgets_block_editor', '__return_false' );
  // Disable block editor widget management. Apperance > Widgets.
  add_filter( 'use_widgets_block_editor', '__return_false' );
}

// Carbon Copy Widgets
$carbon_copy_widgets_init = get_option( 'carbon_copy_widgets' );
if( $carbon_copy_widgets_init == '1' )
{
	class Carbon_Copy_Widgets
	{
		function __construct()
		{
			add_filter( 'admin_head', array( $this, 'clone_script'  )  );
		}

		function clone_script()
		{
			global $pagenow;

			if( $pagenow != 'widgets.php' )
			{
				return;
			}
			?>

<script>
(function($)
{
	if( !window.CarbonCopy ) window.CarbonCopy = {};

	CarbonCopy.CloneWidgets = {
		
		init: function()
		{
			$('body').on('click', '.widget-control-actions .clone-me', CarbonCopy.CloneWidgets.Clone);
			CarbonCopy.CloneWidgets.Bind();
		},

		Bind: function()
		{
			$('#widgets-right').off('DOMSubtreeModified', CarbonCopy.CloneWidgets.Bind);
			$('.widget-control-actions:not(.carboncopy-cloneable)').each(function() {
				var $widget = $(this);
				var $clone = $( '<a>' );
				var clone = $clone.get()[0];
				$clone.addClass( 'clone-me carboncopy-clone-action' )
							.attr( 'title', 'Copy this Widget' )
							.attr( 'href', '#' )
							.html( 'Copy' );
				$widget.addClass('carboncopy-cloneable');
				$clone.insertAfter( $widget.find( '.alignleft .widget-control-remove') );
				clone.insertAdjacentHTML( 'beforebegin', ' | ' );
			});
			$('#widgets-right').on('DOMSubtreeModified', CarbonCopy.CloneWidgets.Bind);
		},

		Clone: function(ev)
		{
			var $original = $(this).parents('.widget');
			var $widget = $original.clone();
			// Find Widget ID. Find Widget number. Duplicate.
			var idbase = $widget.find('input[name="id_base"]').val();
			var number = $widget.find('input[name="widget_number"]').val();
			var mnumber = $widget.find('input[name="multi_number"]').val();
			var highest = 0;
			$('input.widget-id[value|="' + idbase + '"]').each(function()
			{
				var match = this.value.match(/-(\d+)$/);
				if( match && parseInt( match[1]) > highest )
					highest = parseInt(match[1]);
			});
			var newnum = highest + 1;
			$widget.find('.widget-content').find('input,select,textarea').each(function()
			{
				if($(this).attr('name'))
					$(this).attr('name', $(this).attr('name').replace(number, newnum));
			});
			// Assign Unique ID to Widget
			var highest = 0;
			$('.widget').each(function()
			{
				var match = this.id.match(/^widget-(\d+)/);
				if(match && parseInt(match[1]) > highest)
					highest = parseInt(match[1]);
			});
			var newid = highest + 1;
			// Find value of add_new from original widget
			var add = $('#widget-list .id_base[value="' + idbase + '"]').siblings('.add_new').val();
			$widget[0].id = 'widget-' + newid + '_' + idbase + '-' + newnum;
			$widget.find('input.widget-id').val(idbase+'-'+newnum);
			$widget.find('input.widget_number').val(newnum);
			$widget.hide();
			$original.after($widget);
			$widget.fadeIn();
			$widget.find('.multi_number').val(newnum);
			wpWidgets.save($widget, 0, 0, 1);
			ev.stopPropagation();
			ev.preventDefault();
		}
	}
	$(CarbonCopy.CloneWidgets.init);
})(jQuery);
</script>

			<?php
		}
	}
	new Carbon_Copy_Widgets();
}

// Carbon Copy Menus
$carbon_copy_menus_init = get_option( 'carbon_copy_menus' );
if( $carbon_copy_menus_init == '1' )
{
	
class CarbonCopyMenu
{
    function __construct()
	{
        add_action( 'admin_menu', array( $this, 'options_page' ) );
  }
  // Admin menu Appearance > Menus Carbon Copy
	function options_page()
	{
        add_theme_page(
            'Menus Carbon Copy',
            'Menus Carbon Copy',
            'edit_theme_options',
            'carbon-copy-menu',
            array( $this, 'options_screen' )
        );
    }
    // Menu coping function
    function duplicate( $id = null, $name = null )
	{
        // Check and set variables
        if ( empty( $id ) || empty( $name ) )
		{
	        return false;
        }

        $id           = intval( $id );
        $name         = sanitize_text_field( $name );
        $source       = wp_get_nav_menu_object( $id );
        $source_items = wp_get_nav_menu_items( $id );
        $new_id       = wp_create_nav_menu( $name );

		// Ensure new menu created
        if ( ! $new_id )
		{
            return false;
        }
		
		// init
        $i = 1;
        $rel = array();
		
        foreach ( $source_items as $menu_item )
		{
            $args = array(
                'menu-item-db-id'       => $menu_item->db_id,
                'menu-item-object-id'   => $menu_item->object_id,
                'menu-item-object'      => $menu_item->object,
                'menu-item-position'    => $i,
                'menu-item-type'        => $menu_item->type,
                'menu-item-title'       => $menu_item->title,
                'menu-item-url'         => $menu_item->url,
                'menu-item-description' => $menu_item->description,
                'menu-item-attr-title'  => $menu_item->attr_title,
                'menu-item-target'      => $menu_item->target,
                'menu-item-classes'     => implode( ' ', $menu_item->classes ),
                'menu-item-xfn'         => $menu_item->xfn,
                'menu-item-status'      => $menu_item->post_status
            );

            $parent_id = wp_update_nav_menu_item( $new_id, 0, $args );
            $rel[$menu_item->db_id] = $parent_id;

            // If parent menu, update with new ID
            if( $menu_item->menu_item_parent )
			{
                $args['menu-item-parent-id'] = $rel[$menu_item->menu_item_parent];
                $parent_id = wp_update_nav_menu_item( $new_id, $parent_id, $args );
            }

	        // Allow developers to run custom functions
	        do_action( 'carbon_copy_menu_item', $menu_item, $args );

            $i++;
        }

        return $new_id;
    }
    // Options screen
    function options_screen()
	{
        $nav_menus = wp_get_nav_menus();
		?>

<div class="wrap">

	<h2><?php esc_html_e( 'Menus Carbon Copy', 'default' ); ?></h2>
	<?php if( ! empty( $_POST ) && wp_verify_nonce( $_POST['carbon_copy_menu_nonce'], 'carbon_copy_menu' ) ) : ?>
		<?php
			$source_menu   = intval( $_POST['source_menu'] );
			$new_menu_name = sanitize_text_field( $_POST['new_menu_name'] );
			// Future update will check to ensure new, unique menu name is used before new menu creation attempt.
			// Carbon Copy the Menu
			$duplicator    = new CarbonCopyMenu();
			$new_menu_id   = $duplicator->duplicate( $source_menu, $new_menu_name );
		?>
		<div id="message" class="updated">
			<p>
			<?php if ( $new_menu_id ) : ?>
				<?php esc_html_e( 'The selected menu has been successfully copied!', 'default' ) ?>. <a href="nav-menus.php?action=edit&amp;menu=<?php echo absint( $new_menu_id ); ?>"><?php esc_html_e( 'View', 'default' ) ?></a>
			<?php else: ?>
				<?php esc_html_e( 'There was a problem coping your menu. No action was taken.', 'default' ) ?>.
			<?php endif; ?>
			</p>
		</div>
	<?php endif; ?>

	<?php if( empty( $nav_menus ) ) : ?>
		<p><?php esc_html_e( "You haven't created any Menus yet.", 'default' ); ?></p>
	<?php else: ?>
		<form method="post" action="">
			<?php wp_nonce_field( 'carbon_copy_menu', 'carbon_copy_menu_nonce' ); ?>
			<table class="form-table">
				<tr valign="top">
					<th scope="row">
						<label for="source"><?php esc_html_e( 'Existing Menu to be Copied', 'default' ); ?>:</label>
					</th>
					<td>
						<select name="source_menu" id="source_menu" required>
							<option value="">- SELECT A MENU -</option>
							<?php foreach( (array) $nav_menus as $_nav_menu ) : ?>
								<option value="<?php echo esc_attr($_nav_menu->term_id) ?>"><?php echo esc_html( $_nav_menu->name ); ?></option>
							<?php endforeach; ?>
						</select>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row">
						<label for="new_menu_name"><?php esc_html_e( 'Enter the New Menu Name', 'default' ); ?>:</label>
					</th>
					<td>
						<input type="text" name="new_menu_name" id="new_menu_name" value="" class="regular-text" required /><br />
						<span class="description">( <em><strong><?php esc_html_e( "Must create a new and unique menu name.", 'default' );  ?></strong></em> )</span>
					</td>
				</tr>
			</table>
			<p class="submit"><input type="submit" name="submit" id="submit" class="button-primary" value="<?php esc_html_e( 'Carbon Copy Menu', 'default' ) ?>" /></p>
		</form>
	<?php endif; ?>

</div>

	<?php
	}
}

new CarbonCopyMenu();

}