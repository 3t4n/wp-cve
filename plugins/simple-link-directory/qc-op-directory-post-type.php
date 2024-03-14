<?php

defined('ABSPATH') or die("No direct script access!");

//Registering custom post for Team
if ( ! function_exists( 'qcopd_register_cpt_sld' ) ) {
	function qcopd_register_cpt_sld() {
		
		$qc_list_labels = array(
			'name'               => _x( 'Manage List Items', 'qc-opd' ),
			'singular_name'      => _x( 'Manage List Item', 'qc-opd' ),
			'add_new'            => _x( 'New List', 'qc-opd' ),
			'add_new_item'       => __( 'Add New List Item' ),
			'edit_item'          => __( 'Edit List Item' ),
			'new_item'           => __( 'New List Item' ),
			'all_items'          => __( 'Manage List Items' ),
			'view_item'          => __( 'View List Item' ),
			'search_items'       => __( 'Search List Item' ),
			'not_found'          => __( 'No List Item found' ),
			'not_found_in_trash' => __( 'No List Item found in the Trash' ), 
			'parent_item_colon'  => '',
			'menu_name'          => 'Simple Link Directory'
		);
		
		$qc_list_args = array(
			'labels'        		=> $qc_list_labels,
			'description'   		=> esc_html('This post type holds all posts for your directory items.'),
			'public'        		=> true,
			'publicly_queryable' 	=> false,
			'menu_position' 		=> 25,
			'exclude_from_search' 	=> true,
			'show_in_nav_menus' 	=> false,
			'supports'      		=> array( 'title' ),
			'has_archive'   		=> true,
			'menu_icon' 			=> QCOPD_IMG_URL . '/menu_icon.png',
		);
		
		register_post_type( 'sld', $qc_list_args );	
		
		//Register New Taxonomy for Our New Post Type
		// Add new taxonomy, make it hierarchical (like categories)
		$labels = array(
			'name'              => _x( 'List Categories', 'List Categories', 'qc-opd' ),
			'singular_name'     => _x( 'Category', 'taxonomy singular name', 'qc-opd' ),
			'search_items'      => __( 'Search List Categories', 'qc-opd' ),
			'all_items'         => __( 'All List Categories', 'qc-opd' ),
			'parent_item'       => __( 'Parent List Categories', 'qc-opd' ),
			'parent_item_colon' => __( 'Parent List Category:', 'qc-opd' ),
			'edit_item'         => __( 'Edit List Category', 'qc-opd' ),
			'update_item'       => __( 'Update List Category', 'qc-opd' ),
			'add_new_item'      => __( 'Add New List Category', 'qc-opd' ),
			'new_item_name'     => __( 'New List Category Name', 'qc-opd' ),
			'menu_name'         => __( 'List Categories', 'qc-opd' ),
		);

		$args = array(
			'hierarchical'      => true,
			'labels'            => $labels,
			'show_ui'           => true,
			'show_admin_column' => true,
			'query_var'         => true,
			'rewrite'           => array( 'slug' => 'sld_cat' ),
		);

		register_taxonomy( 'sld_cat', array( 'sld' ), $args );
		
	}
}

add_action( 'init', 'qcopd_register_cpt_sld' );
add_action( 'init', 'qcopd_load_cmb' );
if ( ! function_exists( 'qcopd_load_cmb' ) ) {
	function qcopd_load_cmb(){
		$post_type = '';
		if(isset($_GET['post']) && $_GET['post']!=''){
			$post = get_post(sanitize_text_field($_GET['post']));
			$post_type = $post->post_type;
		}elseif(isset($_GET['post_type']) && $_GET['post_type']=='sld'){
			$post_type = 'sld';
		}elseif(isset($_POST['post_type']) && $_POST['post_type']=='sld'){
			$post_type = 'sld';
		}
			
		if ( ! class_exists( 'CMB_Meta_Box' ) ){

			if ( is_admin() && $post_type == 'sld') {
				require_once QCOPD_INC_DIR . '/cmb/custom-meta-boxes.php';
			}
		}
	}
}


//Metabox Fields
if ( ! function_exists( 'cmb_qcopd_dir_fields' ) ) {
	function cmb_qcopd_dir_fields( array $meta_boxes ) {
		
		//Repeatable Fields
		$qcopd_item_fields = array(
			array( 'id' => 'qcopd_item_title',  'name' => 'Item Title', 'type' => 'text', 'cols' => 4, 'desc' => 'Title of the list item' ),
			array( 'id' => 'qcopd_item_link',  'name' => 'Item Link', 'type' => 'text', 'cols' => 4, 'desc' => 'With http://, Example: http://www.google.com' ),
			array( 'id' => 'qcopd_upvote_count',  'name' => 'Upvote Count', 'type' => 'text', 'cols' => 4, 'default' => '0', 'desc' => 'Total upvote for this element' ),
			array( 'id' => 'qcopd_item_img', 'name' => 'List Image', 'type' => 'image', 'repeatable' => false, 'show_size' => false, 'cols' => 3, 'desc' => 'Preferred Size: 100X100px'  ),
			array( 'id' => 'qcopd_item_nofollow',  'name' => 'No Follow', 'type' => 'checkbox', 'cols' => 3, 'default' => 0 ),
			
			
			
			array( 'id' => 'qcopd_entry_time',  'name' => 'Entry Time', 'type' => 'text', 'cols' => 4, 'default' => ''.date("Y-m-d H:i:s").'' ),	
			array( 'id' => 'qcopd_timelaps',  'name' => 'Time Laps', 'type' => 'text', 'cols' => 4, 'default' => '' ),	
			
			array( 'id' => 'qcopd_item_newtab',  'name' => 'Open Link in a New Tab', 'type' => 'checkbox', 'cols' => 3, 'default' => 0 ),
			array( 'id' => 'qcopd_featured',  'name' => 'Mark Item as Featured', 'type' => 'checkbox', 'cols' => 3, 'default' => 0, 'desc' => '' ),
			array( 'id' => 'qcopd_item_subtitle',  'name' => 'Item Subtitle', 'type' => 'text', 'cols' => 7 ),
			array( 'id' => 'list_item_bg_color',  'name' => 'Item Background Color', 'type' => 'colorpicker', 'cols' => 2, 'default' => '0' ),
			
		);

		$meta_boxes[] = array(
			'title' => 'List Elements',
			'pages' => 'sld',
			'fields' => array(
				array(
					'id' => 'qcopd_list_item01',
					'name' => 'Create List Elements',
					'type' => 'group',
					'repeatable' => true,
					'sortable' => true,
					'fields' => $qcopd_item_fields,
					'desc' => 'Please add your list items here. <br><br><i style="color:indianred;font-size:15px !important">If you are unable to save a long List, please increase the value of max_input_vars to 15000 on your server.</i>'
				)
			)
		);

		return $meta_boxes;

	}
}

add_filter( 'cmb_meta_boxes', 'cmb_qcopd_dir_fields', null );

//Custom Columns for Directory Listing
if ( ! function_exists( 'qcopd_list_columns_head' ) ) {
	function qcopd_list_columns_head($defaults) {

	    $new_columns['cb'] = '<input type="checkbox" />';
	    $new_columns['title'] = __('Title');

	    $new_columns['qcopd_item_count'] = 'Number of Elements';
	    $new_columns['shortcode_col'] = 'Shortcode';

	    $new_columns['date'] = __('Date');

	    return $new_columns;
	}
}
 
//Custom Columns Data for Backend Listing
if ( ! function_exists( 'qcopd_list_columns_content' ) ) {
	function qcopd_list_columns_content($column_name, $post_ID) {
	    

	    if ($column_name == 'qcopd_item_count') {
	        echo count(get_post_meta( $post_ID, 'qcopd_list_item01' ));
	    }

	    if ($column_name == 'shortcode_col') {
	        echo '[qcopd-directory mode="one" style="simple" list_id="'.$post_ID.'"]';
	    }
	}
}

add_filter('manage_sld_posts_columns', 'qcopd_list_columns_head');
add_action('manage_sld_posts_custom_column', 'qcopd_list_columns_content', 10, 2);


