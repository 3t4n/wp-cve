<?php

class WPForms_Views_Posttype {
	private $forms = array();
	function __construct() {
		add_action( 'init', array( $this, 'create_posttype' ) );
		add_filter( 'manage_wpforms-views_posts_columns' , array( $this, 'add_extra_columns' ) );
		add_action( 'manage_wpforms-views_posts_custom_column' , array( $this, 'extra_column_detail' ), 10, 2 );
	}

	function create_posttype() {

		$labels = array(
			'name'               => _x( 'WPForms Views', 'post type general name', 'views-for-wpforms-lite' ),
			'singular_name'      => _x( 'WPForms View', 'post type singular name', 'views-for-wpforms-lite' ),
			'menu_name'          => _x( 'WPForms Views Lite', 'admin menu', 'views-for-wpforms-lite' ),
			'name_admin_bar'     => _x( 'WPForms Views', 'add new on admin bar', 'views-for-wpforms-lite' ),
			'add_new'            => _x( 'Add New', 'book', 'views-for-wpforms-lite' ),
			'add_new_item'       => __( 'Add New', 'views-for-wpforms-lite' ),
			'new_item'           => __( 'New WPForms View', 'views-for-wpforms-lite' ),
			'edit_item'          => __( 'Edit WPForms View', 'views-for-wpforms-lite' ),
			'view_item'          => __( 'View WPForms View', 'views-for-wpforms-lite' ),
			'all_items'          => __( 'All Views', 'views-for-wpforms-lite' ),
			'search_items'       => __( 'Search Views', 'views-for-wpforms-lite' ),
			'parent_item_colon'  => __( 'Parent Views:', 'views-for-wpforms-lite' ),
			'not_found'          => __( 'No view found.', 'views-for-wpforms-lite' ),
			'not_found_in_trash' => __( 'No view found in Trash.', 'views-for-wpforms-lite' )
		);

		$args = array(
			'labels'             => $labels,
			'description'        => __( 'Description.', 'views-for-wpforms-lite' ),
			'public'             => false,
			'exclude_from_search'=> true,
			'publicly_queryable' => false,
			'show_ui'            => true,
			'show_in_menu'       => true,
			'query_var'          => true,
			'rewrite'            => array( 'slug' => 'views-for-wpforms-lite' ),
			'capability_type'    => 'post',
			'has_archive'        => false,
			'menu_icon'		 => 'dashicons-format-gallery',
			'hierarchical'       => false,
			'menu_position'      => null,
			'supports'           => array( 'title', 'false' )
		);

		register_post_type( 'wpforms-views', $args );
	}

	function add_extra_columns( $columns ) {
		$columns = array_slice( $columns, 0, 2, true ) + array( "view_format" =>__( 'View Format', 'views-for-wpforms-lite' ) ) + array_slice( $columns, 2, count( $columns )-2, true );
		$columns = array_slice( $columns, 0, 2, true ) + array( "view_source" =>__( 'View Source', 'views-for-wpforms-lite' ) ) + array_slice( $columns, 2, count( $columns )-2, true );
		$columns = array_slice( $columns, 0, 2, true ) + array( "shortcode" =>__( 'Shortcode', 'views-for-wpforms-lite' ) ) + array_slice( $columns, 2, count( $columns )-2, true );
		return $columns;
	}

	function extra_column_detail( $column, $post_id ) {
		switch ( $column ) {
		case 'shortcode' :
			echo '<code>[wpforms-views id=' . $post_id . ']</code>';
			break;
		case 'view_format' :
			$view_settings_json = get_post_meta( $post_id, 'view_settings', true );
			if ( ! empty( $view_settings_json ) ) {
				$view_settings =  json_decode( $view_settings_json );
				$view_type = $view_settings->viewType;
				echo '<span>' . ucfirst( $view_type ) . '</span>';
			}
			break;
		case 'view_source' :
			if ( empty( $this->forms ) && function_exists( 'wpforms' ) ) {
				$this->forms = wpforms()->form->get();
			}
			$view_settings_json = get_post_meta( $post_id, 'view_settings', true );
			if ( ! empty( $view_settings_json ) ) {
				$view_settings =  json_decode( $view_settings_json );
				$form_id = $view_settings->formId;
				if ( ! empty( $this->forms ) ) {
					foreach ( $this->forms as $form ) {
						if( $form->ID == $form_id){
							printf('<a href="%s">' . $form->post_title . '</a>',
							admin_url('admin.php?page=wpforms-builder&view=fields&form_id='.$form_id)
							);
						}
					}
				}

			}
			break;

		}
	}
}

new WPForms_Views_Posttype();
