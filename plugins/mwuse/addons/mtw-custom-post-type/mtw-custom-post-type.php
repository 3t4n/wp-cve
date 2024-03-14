<?php


function mtw_default_declare_post_type_loop()
{
	
	
	if( class_exists('MusePage') )
	{
		$mtw_archives_auto = get_option( 'mtw_archives_auto' );

		global $mtw_ctp_permalink;
		$mtw_ctp_permalink = get_option( 'mtw_ctp_permalink', array() );

		if( $mtw_archives_auto )
		{
			foreach ($mtw_archives_auto as $key => $archive) 
			{
				mtw_default_declare_post_type( $archive );
			}
		}

		add_action('admin_init', 'mtw_custom_post_type_permalink_section');
	}
}

add_action( 'init', 'mtw_default_declare_post_type_loop' );


function mtw_default_declare_post_type( $archive )
{

	$name = $archive['title'];
	$slug = $archive['post_type'];

	if( !post_type_exists( $slug ) )
	{

		$labels = array(
			'name' => $name,
			'singular_name' => $name,
			'menu_name' => $name,
			'name_admin_bar' => $name,
			'all_items' => __('All Posts'),
			'add_new' => __('Add'),
			'add_new_item' => __('Add New Post'),
			'edit_item' => __('Edit Post'),
			'new_item' => __('New Post'),
			'view_item' => __('View Post'),
			'search_items' => __('Search Posts'),
			'not_found' =>  __('No posts found.'),
			'not_found_in_trash' => __('No posts found in Trash.'),
			'parent_item_colon' => __('Parent Page'),
		);

		$args = array(
			'labels' => $labels,
			'public' => true,
			'exclude_from_search' => false,
			'publicly_queryable' => true,
			'show_ui' => true,
			'show_in_nav_menus' => true,
			'show_in_menu' => true,
			'show_in_admin_bar' => true,
			'has_archive' => true,
			'menu_position' => null,
			'menu_icon' => null,
			'hierarchical' => true,
			'rewrite' => array( 'slug' => sanitize_title( $name ), 'with_front' => true, 'feeds' => true, 'pages' => true ),
			'query_var' => true,
			'can_export' => true,
			'supports' => array( 'title', 'editor', 'author', 'page-attributes', 'thumbnail', 'excerpt','trackbacks','custom-fields','comments','revisions' ),
		);

		register_post_type( $slug , $args );

		global $mtw_ctp_permalink;

		$category_slug = sanitize_title( $name ).'-category';
		$tag_slug = sanitize_title( $name ).'-tag';

		if( isset( $mtw_ctp_permalink[$category_slug] ) ) {$category_slug = $mtw_ctp_permalink[$category_slug];}
		if( isset( $mtw_ctp_permalink[$tag_slug] ) ) {$tag_slug = $mtw_ctp_permalink[$tag_slug];}

		$labels = array(
			'name' => __('Category'),
			'singular_name' => __('Category'),
			'menu_name' => __('Categories'),
			'all_items' => __('All Categories'),
			'edit_item' => __('Edit Category'),
			'view_item' => __('View Category'),
			'update_item' => __('Update Category'),
			'add_new_item' => __('Add New Category'),
			'new_item_name' => __('New Category'),
			'parent_item' => __('Parent Category'),
			'parent_item_colon' =>  __('Parent Category'),
			'search_items' => __('Search Tags'),
			'popular_items' => __('Popular Tags'),
			'separate_items_with_commas' => __('Separate tags with commas'),
			'add_or_remove_items' => __('Add or remove tags'),
			'choose_from_most_used' => __('Choose from the most used tags'),
			'not_found' => __('No tags found.'),
		);

		$args = array(
			'labels' => $labels,
			'public' => true,
			'show_ui' => true,
			'show_in_nav_menus' => true,
			'show_tagcloud' => true,
			'meta_box_cb' => null,
			'show_admin_column' => false,
			'hierarchical' => true,
			'query_var' => true,
			'rewrite' => array( 'slug' => $category_slug,'with_front' => true,'hierarchical' => false, ),
			'sort' => false,
		);

		register_taxonomy( sanitize_title( $name ).'_category', array( $slug ) , $args );


		$labels = array(
			'name' => __('Tags'),
			'singular_name' => __('Tag'),
			'menu_name' => __('Tags'),
			'all_items' => __('All Tags'),
			'edit_item' => __('Edit Tag'),
			'view_item' => __('View Tag'),
			'update_item' => __('Update Tag'),
			'add_new_item' => __('Add New Tag'),
			'new_item_name' => __('New Tag Name'),
			'parent_item' => __('Parent Category'),
			'parent_item_colon' =>  __('Parent Category'),
			'search_items' => __('Search Tags'),
			'popular_items' => __('Popular Tags'),
			'separate_items_with_commas' => __('Separate tags with commas'),
			'add_or_remove_items' => __('Add or remove tags'),
			'choose_from_most_used' => __('Choose from the most used tags'),
			'not_found' => __('No tags found.'),
		);

		$args = array(
			'labels' => $labels,
			'public' => true,
			'show_ui' => true,
			'show_in_nav_menus' => true,
			'show_tagcloud' => true,
			'meta_box_cb' => null,
			'show_admin_column' => false,
			'hierarchical' => false,
			'query_var' => true,
			'rewrite' => array( 'slug' => $tag_slug,'with_front' => true,'hierarchical' => false, ),
			'sort' => false,
		);

		register_taxonomy( sanitize_title( $name ).'_tag', array( $slug ) , $args );
		flush_rewrite_rules();
	}
}


function mtw_custom_post_type_permalink_section() {
	
	register_setting(
	    'mtw_ctp_permalink_group', // Option group
	    'mtw_ctp_permalink' // Option name
	);

	if( isset( $_POST['mtw_ctp_permalink'] ) )
	{
		$array_sanitized = $_POST['mtw_ctp_permalink'];
		foreach ($array_sanitized as $key => $value) 
		{
			$array_sanitized[$key] = sanitize_title( $value );
		}
		update_option( 'mtw_ctp_permalink', $array_sanitized );
	}

	$mtw_archives_auto = get_option( 'mtw_archives_auto' );

	if( $mtw_archives_auto )
	{
	    foreach ($mtw_archives_auto as $key => $archive) 
	    {
	    	add_settings_section(
	    	    sanitize_title( $archive['title'] ), // ID
	    	    $archive['title'] . ' (custom category & tag)', // Section title
	    	    'mtw_get_custom_post_permalink_form', // Callback for your function
	    	    'permalink' // Location (Settings > Permalinks)
	    	);

		    add_settings_field(
		        sanitize_title( $archive['title'] ).'-category', // ID
		        __('Category base'), // Title 
		        'mtw_custom_post_permalink_get_field', // Callback
		        'permalink', // Page
		        sanitize_title( $archive['title'] ), // Section 
		        array( 'id' => sanitize_title( $archive['title'] ).'-category' ) // Callback args
		    );

		    add_settings_field(
		        sanitize_title( $archive['title'] ).'-tag', // ID
		        __('Tag base'), // Title 
		        'mtw_custom_post_permalink_get_field', // Callback
		        'permalink', // Page
		        sanitize_title( $archive['title'] ), // Section 
		        array( 'id' => sanitize_title( $archive['title'] ).'-tag' ) // Callback args
		    );
		}
	}
}


function mtw_get_custom_post_permalink_form( $args )
{

}

function mtw_custom_post_permalink_get_field( $args )
{
	global $mtw_ctp_permalink;
	$id = $args['id'];
	$value = $mtw_ctp_permalink[ $id ];
	?>
	<input class="regular-text code" name="mtw_ctp_permalink[<?php echo $id; ?>]" type="text" value="<?php echo $value ?>" >
	<?php
}

function mtw_thumbnail_categories_image( $id )
{

	global $wpdb;
	global $wp_query;
	if( $wp_query->is_mtw_term_item )
	{
		$term_id =  $wp_query->queried_object->term_id;
		$image_url = get_option( 'z_taxonomy_image' . $term_id, '' );
		
		$attachment = $wpdb->get_col($wpdb->prepare("SELECT ID FROM $wpdb->posts WHERE guid='%s';", $image_url )); 
		if( $attachment )
		{
			return $attachment[0];
		}
	}
}
if ( function_exists('z_taxonomy_image_url') )
{
	add_filter( 'mtw_thumbnail', 'mtw_thumbnail_categories_image' );
}
?>