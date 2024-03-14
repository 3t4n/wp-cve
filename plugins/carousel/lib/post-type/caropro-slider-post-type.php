<?php

	if( !defined( 'ABSPATH' ) ){
	    exit;
	}

	function tp_carousel_main_custom_post_register() {
		# Set UI labels for Custom Post Type
		$labels = array(
			'name'                => _x( 'Carousel Free', 'Post Type General Name', 'carosuelfree' ),
			'singular_name'       => _x( 'Carousel', 'Post Type Singular Name', 'carosuelfree' ),
			'menu_name'           => __( 'Carousel Free', 'carosuelfree' ),
			'parent_item_colon'   => __( 'Parent Carousel', 'carosuelfree' ),
			'all_items'           => __( 'All Carousels', 'carosuelfree' ),
			'view_item'           => __( 'View Carousel', 'carosuelfree' ),
			'add_new_item'        => __( 'Add New Image', 'carosuelfree' ),
			'add_new'             => __( 'Add New Image', 'carosuelfree' ),
			'edit_item'           => __( 'Edit Carousel', 'carosuelfree' ),
			'update_item'         => __( 'Update Carousel', 'carosuelfree' ),
			'search_items'        => __( 'Search Carousel', 'carosuelfree' ),
			'not_found'           => __( 'Not Found', 'carosuelfree' ),
			'not_found_in_trash'  => __( 'Not found in Trash', 'carosuelfree' ),
		);

		# Set other options for Custom Post Type
		$args = array(
			'label'               => __( 'Carousels', 'carosuelfree' ),
			'description'         => __( 'Carousel reviews', 'carosuelfree' ),
			'labels'              => $labels,
			'supports'            => array( 'title', 'thumbnail'),
			'taxonomies'          => array( 'genres' ),
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
			'capability_type'     => 'page',
			'menu_icon'		      => 'dashicons-images-alt2'
		);
		// Registering your Custom Post Type
		register_post_type( 'tpmfcarousel', $args );
	}
	add_action( 'init', 'tp_carousel_main_custom_post_register', 0 );


	function tp_carousel_main_custom_taxonomies_register() {
		register_taxonomy( 'tpmfcarouselcat', 'tpmfcarousel', array(
			'hierarchical' => true,
			'label' => 'Carousel Categories',
			'query_var' => true,
			'rewrite' => true
		));
	}
	add_action( 'init', 'tp_carousel_main_custom_taxonomies_register', 0 );

	# Add Option Page Generate Shortcode
	function tp_carousel_main_custom_slider_shortcode_submenu_page(){
		add_submenu_page('edit.php?post_type=tpmfcarousel', __('Generate Shortcode', 'carosuelfree'), __('Generate Shortcode', 'carosuelfree'), 'manage_options', 'post-new.php?post_type=carousel_shortcode');
	}
	add_action('admin_menu', 'tp_carousel_main_custom_slider_shortcode_submenu_page');

	#Columns Declaration Function
	function tp_carousel_main_custompostcolumns($tp_carousel_main_custompostcolumns){

		$order='asc';

		if($_GET['order']=='asc') {
			$order='desc';
		}

		$tp_carousel_main_custompostcolumns = array(
			"cb" => "<input type=\"checkbox\" />",
			"thumbnail" => __('Image', 'carosuelfree'),
			"title" => __('Name', 'carosuelfree'),
			"carpro_slider_catlist" => __('Categories', 'carosuelfree'),
			"date" => __('Date', 'carosuelfree'),
		);
		return $tp_carousel_main_custompostcolumns;
	}

	function tp_carousel_main_custompostcolumns_display($tp_carousel_main_custompostcolumns, $post_id){
		global $post;
		$width = (int) 80;
		$height = (int) 80;

		if ( 'thumbnail' == $tp_carousel_main_custompostcolumns ) {
			if ( has_post_thumbnail($post_id)) {
				$thumbnail_id = get_post_meta( $post_id, '_thumbnail_id', true );
				$thumb = wp_get_attachment_image( $thumbnail_id, array($width, $height), true );
				echo $thumb;
			}
			else {
				echo __('None');
			}
		}

		if ( 'carpro_slider_catlist' == $tp_carousel_main_custompostcolumns ) {
			$terms = get_the_terms( $post_id , 'tpmfcarouselcat');
			$count = count($terms);
			if ( $terms ){
				$i = 0;
				foreach ( $terms as $term ) {
					echo '<a href="'.admin_url( 'edit.php?post_type=tpmfcarousel&tpmfcarouselcat='.$term->slug ).'">'.$term->name.'</a>';	
					if($i+1 != $count) {
						echo " , ";
					}
					$i++;
				}
			}
		}
	}

	# Add manage posts columns Filter 
	add_filter("manage_tpmfcarousel_posts_columns", "tp_carousel_main_custompostcolumns");

	# Add manage posts custom column Action
	add_action("manage_tpmfcarousel_posts_custom_column",  "tp_carousel_main_custompostcolumns_display", 10, 2 );	

	# Registering Post Type For Generate Shortcode
	function tp_carousel_main_custom_shortcode_post_type_reg() {

		# Set UI labels for Custom Post Type
		$labels = array(
			'name'                => _x( 'All Shortcodes', 'Post Type General Name' ),
			'singular_name'       => _x( 'Generate Shortcode', 'Post Type Singular Name' ),
			'menu_name'           => __( 'Generate Shortcode' ),
			'parent_item_colon'   => __( 'Parent Shortcode' ),
			'all_items'           => __( 'All Shortcodes' ),
			'view_item'           => __( 'View Shortcode' ),
			'add_new_item'        => __( 'Generate New Shortcode' ),
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
			'label'               => __( 'carousel-slider' ),
			'description'         => __( 'Carousel Slider news and reviews' ),
			'hierarchical'        => false,
			'public'              => true,
			'show_ui'             => true,
			'show_in_menu' 		  => 'edit.php?post_type=tpmfcarousel',
			'show_in_nav_menus'   => true,
			'show_in_admin_bar'   => true,
			'menu_position'       => 5,
			'can_export'          => true,
			'has_archive'         => true,
			'exclude_from_search' => false,
			'publicly_queryable'  => true,
			'supports'            => array( 'title' ),
		);
		register_post_type( 'carousel_shortcode', $args );
	}
	add_action( 'init', 'tp_carousel_main_custom_shortcode_post_type_reg', 0 );

	# Carousel Manage Shortcode Column 
	function tp_carousel_main_custom_colmns_reg( $columnsreg ) {
	 return array_merge( $columnsreg, 
	  array(
	  		'shortcode' => __( 'Shortcode', 'carosuelfree' ),
	  		'doshortcode' => __( 'Template Shortcode', 'carosuelfree' ) )
	   );
	}
	add_filter( 'manage_carousel_shortcode_posts_columns' , 'tp_carousel_main_custom_colmns_reg' );

	function tp_carousel_main_custom_colmns_reg_display( $carpro_columnreg, $post_id ) {
	 if ( $carpro_columnreg == 'shortcode' ){
	  ?>
	  <input style="background:#ddd" type="text" onClick="this.select();" value="[carousel_composer <?php echo 'id=&quot;'.$post_id.'&quot;';?>]" />
	  <?php
	}
 	if ( $carpro_columnreg == 'doshortcode' ){
  	?>

  	<textarea cols="40" rows="2" style="background:#ddd;" onClick="this.select();" ><?php echo '<?php echo do_shortcode("[carousel_composer id='; echo "'".$post_id."']"; echo '"); ?>'; ?></textarea>
  	<?php
 	}
}
add_action( 'manage_carousel_shortcode_posts_custom_column' , 'tp_carousel_main_custom_colmns_reg_display', 10, 2 );