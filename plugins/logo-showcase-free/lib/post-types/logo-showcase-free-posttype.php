<?php

	if( !defined( 'ABSPATH' ) ){
	    exit;
	}

	# Register Logo Showcase Free Post Styles
	function pick_logosowcasefree_post_register(){
		$labels = array(
			'name'                  => _x( 'Logo Showcase', 'Post Type General Name', 'logo-showcase-free' ),
			'singular_name'         => _x( 'Logo Showcase', 'Post Type Singular Name', 'logo-showcase-free' ),
			'menu_name'             => __( 'Logo Showcase', 'logo-showcase-free' ),
			'name_admin_bar'        => __( 'Logo Showcase', 'logo-showcase-free' ),
			'parent_item_colon'     => __( 'Parent Item:', 'logo-showcase-free' ),
			'all_items'             => __( 'All Logos', 'logo-showcase-free' ),
			'add_new_item'          => __( 'Add New Logo', 'logo-showcase-free' ),
			'add_new'               => __( 'Add New Logo', 'logo-showcase-free' ),
			'new_item'              => __( 'New Logo', 'logo-showcase-free' ),
			'edit_item'             => __( 'Edit Logo', 'logo-showcase-free' ),
			'update_item'           => __( 'Update Logo', 'logo-showcase-free' ),
			'view_item'             => __( 'View Logo', 'logo-showcase-free' ),
			'search_items'          => __( 'Search Logo', 'logo-showcase-free' ),
			'not_found'             => __( 'Logo Not found', 'logo-showcase-free' ),
			'not_found_in_trash'    => __( 'Not found in Trash', 'logo-showcase-free' ),
			'featured_image'        => __( 'Logo Showcase Image', 'logo-showcase-free' ),
			'set_featured_image'    => __( 'Set logo Showcase image', 'logo-showcase-free' ),
			'remove_featured_image' => __( 'Remove logo featured image', 'logo-showcase-free' ),
			'use_featured_image'    => __( 'Use as logo featured image', 'logo-showcase-free' ),
			'items_list'            => __( 'Items list', 'logo-showcase-free' ),
			'items_list_navigation' => __( 'Items list navigation', 'logo-showcase-free' ),
			'filter_items_list'     => __( 'Filter items list', 'logo-showcase-free' ),
		);
		$args = array(
			'label'                 => __( 'Post Type', 'logo-showcase-free' ),
			'description'           => __( 'Post Type Description', 'logo-showcase-free' ),
			'labels'                => $labels,
			'supports'              =>  array( 'title'),
			'hierarchical'          => false,
			'public'                => true,
			'menu_icon' 			=> 'dashicons-images-alt',
			'show_ui'               => true,
			'show_in_menu'          => true,
			'show_in_admin_bar'     => true,
			'show_in_nav_menus'     => true,
			'can_export'            => true,
			'has_archive'           => true,		
			'exclude_from_search'   => false,
			'publicly_queryable'    => true,
			'capability_type'       => 'page',
		);
		register_post_type( 'piklogoshowcase', $args );
	}
	add_action('init', 'pick_logosowcasefree_post_register');

	/**
	* Adds a submenu page under logo showcase post type parent.
	*/
	add_action( 'admin_menu', 'pick_logosowcasefree_info_page' );
	function pick_logosowcasefree_info_page() {
	    add_submenu_page(
	        'edit.php?post_type=piklogoshowcase',
	        __( 'Need Support', 'logo-showcase-free' ),
	        __( 'Need Support', 'logo-showcase-free' ),
	        'manage_options',
	        'logo-showcase-info',
	        'pick_logosowcasefree_page_callback'
	    );
	}

	/**
	* Display callback for the submenu page.
	*/
	function pick_logosowcasefree_page_callback() { 
	    ?>
	    <div class="wrap">
	        <h1><?php _e( 'Logo Showcase - 2.0.8', 'logo-showcase-free' ); ?></h1>
	        <div class="card">
				<h4>
					<span class="card-name"><?php _e( 'Public Support Forum', 'logo-showcase-free' ); ?></span>
					<div class="card-header-right"></div>
				</h4>
				<div class="card-body">
					<p>We are very active on the <a href="https://wordpress.org/support/plugin/logo-showcase-free" target="_blank">WordPress Support Forum</a>. If you found a bug, have a feature idea please drop a comments. We love to hear back from our users.</p>
				</div>
	        </div>
	        <div class="card">
				<h4>
					<span class="card-name"><?php _e( 'Premium Email Support - PRO', 'logo-showcase-free' ); ?></span>
					<div class="card-header-right"></div>
				</h4>
				<div class="card-body">
					<p class="mb0">Need urgent support? Have one of our developers personally assist you with your issue. All PRO license holders have access to premium email support. Get <a href="https://pickelements.com/logoshowcasefree/">PRO</a> now.</p>
				</div>
	        </div>
	        <div class="card">
				<h4>
					<span class="card-name"><?php _e( 'Activate License - PRO', 'logo-showcase-free' ); ?></span>
					<div class="card-header-right"></div>
				</h4>
				<div class="card-body">
					<p>You can find the License Key on the confirmation email sent to the email address provided on purchase.</p>
				    <p>If you don't have a license - <a target="_blank" href="https://pickelements.com/logoshowcasefree/">Purchase Now</a>. In case of problems with the license please <a href="https://pickelements.com/contact/" target="_blank"><?php _e( 'Contact Support', 'logo-showcase-free' ); ?></a>.</p>
				    <hr>
				    <p><label for="wpr-license-key"><?php _e( 'License Key:', 'logo-showcase-free' ); ?> </label><input class="regular-text" type="text" value="" placeholder="xxxxx-xxxxx-xxxxx-xxxxx"><br><label><?php _e( 'Status:', 'logo-showcase-free' ); ?> </label><strong style="color: #ea1919;"><?php _e( 'Inactive', 'logo-showcase-free' ); ?></strong></p>
				    <p><a href="#" class="button button-primary"><?php _e( 'Activate License', 'logo-showcase-free' ); ?></a>&nbsp; &nbsp;<a href="#" class="button button-secondary"><?php _e( 'Deactivate License', 'logo-showcase-free' ); ?></a>
				    </p>
				</div>
	        </div>
	    </div>
	    <?php
	}

	# Logo Showcase Free Register Column
	function pic_logoshowcase_free_add_shortcode_column( $columns ) {
		$order='asc';
		if($_GET['order']=='asc') {
			$order='desc';
		}
		$columns = array(
			"cb"        => "<input type=\"checkbox\" />",
			"title"     => __('Title', 'logo-showcase-free'),
			"shortcode" => __('Shortcode', 'logo-showcase-free'),
			"date"      => __('Date', 'logo-showcase-free'),
		);
		return $columns;
	}
	add_filter( 'manage_piklogoshowcase_posts_columns' , 'pic_logoshowcase_free_add_shortcode_column' );

	# Logo Showcase Free Display Shortcode or Do Shortcode
	function pic_logoshowcase_free_add_posts_shortcode_display( $column, $post_id ) {
		 if ( $column == 'shortcode' ){ ?>
			<input style="background:#ddd" type="text" onClick="this.select();" value="[piclogofree <?php echo 'id=&quot;'.$post_id.'&quot;';?>]" />
			<?php 
		}
	}
	add_action( 'manage_piklogoshowcase_posts_custom_column' , 'pic_logoshowcase_free_add_posts_shortcode_display', 10, 2 );