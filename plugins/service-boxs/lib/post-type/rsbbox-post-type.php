<?php

	if( !defined( 'ABSPATH' ) ){
	    exit;
	}

	function rsbbox_admin_load_post_type() {
		# Set UI labels for Custom Post Type
		$labels = array(
			'name'                => _x( 'Service Box', 'Post Type General Name' ),
			'singular_name'       => _x( 'Service Box', 'Post Type Singular Name' ),
			'menu_name'           => __( 'Service Box' ),
			'parent_item_colon'   => __( 'Parent Post Service' ),
			'all_items'           => __( 'All Services' ),
			'view_item'           => __( 'View Service' ),
			'add_new_item'        => __( 'Add New Service' ),
			'add_new'             => __( 'Add Service' ),
			'edit_item'           => __( 'Edit Service' ),
			'update_item'         => __( 'Update Service' ),
			'search_items'        => __( 'Search Service' ),
			'not_found'           => __( 'Not Found' ),
			'not_found_in_trash'  => __( 'Not found in Trash' ),
			'featured_image'        => __( 'Upload Service Background Image (Pro Only)', 'service-boxs' ),
		);
		# Set other options for Custom Post Type...
		$args = array(
			'labels'              => $labels,
			'label'               => __( 'service-box' ),
			'description'         => __( 'Service news and reviews' ),
			'hierarchical'        => false,
			'public'              => true,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'show_in_nav_menus'   => true,
			'show_in_admin_bar'   => true,
			'can_export'          => true,
			'has_archive'         => true,
			'exclude_from_search' => false,
			'publicly_queryable'  => true,
			'supports'            => array( 'title', 'thumbnail' ),
			'menu_icon'		      => 'dashicons-hammer'
		);
		register_post_type( 'tpwp_serviceboxs', $args );
	}
	add_action( 'init', 'rsbbox_admin_load_post_type', 0 );
	

	#Columns Declaration Function
	function rsbbox_admincolumns($rsbbox_admincolumns){
		$order='asc';
		if($_GET['order']=='asc') {
			$order='desc';
		}

		$rsbbox_admincolumns = array(
			"cb" 			=> "<input type=\"checkbox\" />",
			"title" 		=> __('Name', 'service-boxs'),
			"lsuw_catcols" 	=> __('Categories', 'service-boxs'),
			"date" 			=> __('Date', 'service-boxs'),
		);
		return $rsbbox_admincolumns;
	}

	function rsbbox_admincolumns_display($rsbbox_admincolumns, $post_id){
		global $post;
		if ( 'lsuw_catcols' == $rsbbox_admincolumns ) {
			$terms = get_the_terms( $post_id , 'rsbboxcat');
			if ( $terms ) { 
				$count = count( $terms );
				if ( $terms ) {
					$i = 0;
					foreach ( $terms as $term ) {
						echo '<a href="'.admin_url( 'edit.php?post_type=tpwp_serviceboxs&rsbboxcat='.$term->slug ).'">'.$term->name.'</a>';
						if ( $i+1 != $count ) {
							echo " , ";
						}
						$i++;
					}
				}
			} else {
				echo "—";
			}
		}
	}

	# Add manage posts columns Filter 
	add_filter("manage_tpwp_serviceboxs_posts_columns", "rsbbox_admincolumns");

	# Add manage posts custom column Action
	add_action("manage_tpwp_serviceboxs_posts_custom_column",  "rsbbox_admincolumns_display", 10, 2 );

	# Registering Carousel Taxonomies
	function rsbbox_admin_load_taxonomies_register() {
		register_taxonomy( 'rsbboxcat', 'tpwp_serviceboxs', array(
			'hierarchical' 	=> true,
			'label' 		=> 'Service Group',
			'query_var' 	=> true,
			'rewrite' 		=> true
		));
	}
	add_action( 'init', 'rsbbox_admin_load_taxonomies_register', 0 );

	# Add Option Page Generate Shortcode
	function rsbbox_admin_shortcode_submenu_page(){
		add_submenu_page('edit.php?post_type=tpwp_serviceboxs', __('Generate Shortcode', 'service-boxs'), __('Generate Shortcode', 'service-boxs'), 'manage_options', 'post-new.php?post_type=generateservices');
	}
	add_action('admin_menu', 'rsbbox_admin_shortcode_submenu_page');
	
	# Registering Post Type For Generate Shortcode
	function rsbbox_shortcode_register_type() {

		# Set UI labels for Custom Post Type
		$labels = array(
			'name'                => _x( 'All Shortcodes', 'Post Type General Name' ),
			'singular_name'       => _x( 'Service Shortcode', 'Post Type Singular Name' ),
			'menu_name'           => __( 'Service Shortcode' ),
			'parent_item_colon'   => __( 'Parent Shortcode' ),
			'all_items'           => __( 'All Shortcodes' ),
			'view_item'           => __( 'View Shortcode' ),
			'add_new_item'        => __( 'Add New Shortcode' ),
			'add_new'             => __( 'Generate Shortcode' ),
			'edit_item'           => __( 'Edit Shortcode' ),
			'update_item'         => __( 'Update Shortcode' ),
			'search_items'        => __( 'Search Shortcode' ),
			'not_found'           => __( 'Not Found' ),
			'not_found_in_trash'  => __( 'Not found in Trash' )
		);

		# Set other options for Custom Post Type...
		$args = array(
			'labels'              => $labels,
			'label'               => __( 'service-shortcodes' ),
			'description'         => __( 'Service news and reviews' ),
			'hierarchical'        => false,
			'public'              => true,
			'show_ui'             => true,
			'show_in_menu' 		  => 'edit.php?post_type=tpwp_serviceboxs',
			'show_in_nav_menus'   => true,
			'show_in_admin_bar'   => true,
			'can_export'          => true,
			'has_archive'         => true,
			'exclude_from_search' => false,
			'publicly_queryable'  => true,
			'supports'            => array( 'title' ),
			'menu_icon'		      => 'dashicons-images-alt2'
		);
		register_post_type( 'generateservices', $args );
	}
	add_action( 'init', 'rsbbox_shortcode_register_type', 0 );

	# Carousel Manage Shortcode Column 
	function rsbbox_add_shortcode_column( $rsbboxcolumns ) {
	 return array_merge( $rsbboxcolumns,
		  	array(
		  		'shortcode' 	=> __( 'Shortcode', 'service-boxs' ),
		  		'doshortcode' 	=> __( 'Template Shortcode', 'service-boxs' ) 
		  	)
		);
	}
	add_filter( 'manage_generateservices_posts_columns' , 'rsbbox_add_shortcode_column' );

	function lsuw_logo_slider_add_posts_shortcode_display( $rsbbox_column, $post_id ) {
		if ( $rsbbox_column == 'shortcode' ){ ?>
			<input style="background:#ddd" type="text" onClick="this.select();" value="[tpservicebox <?php echo 'id=&quot;'.$post_id.'&quot;';?>]" />
			<?php 
		}
	 	if ( $rsbbox_column == 'doshortcode' ){ ?>
			<textarea cols="40" rows="2" style="background:#ddd;" onClick="this.select();" ><?php echo '<?php echo do_shortcode("[tpservicebox id='; echo "'".$post_id."']"; echo '"); ?>'; ?></textarea>
			<?php
	 	}
	}
	add_action( 'manage_generateservices_posts_custom_column' , 'lsuw_logo_slider_add_posts_shortcode_display', 10, 2 );