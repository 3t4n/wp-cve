<?php
namespace Enteraddons\HeaderFooterBuilder;

/**
 * Enteraddons Header Footer Builder class
 *
 * @package     Enteraddons
 * @author      ThemeLooks
 * @copyright   2022 ThemeLooks
 * @license     GPL-2.0-or-later
 *
 *
 */

class Header_Footer_Builder {

	private static $instance = null;

	private function __construct() {

		add_filter( 'single_template', [ __CLASS__, 'loadCanvasTemplate' ] );
		//
		add_action( 'init', [ __CLASS__, 'HeaderFooterPostType' ] );
		// init Meta object
		Post_Type_Meta::getInstance();
		add_filter( 'parse_query', [ __CLASS__, 'hf_query_filter' ] );
		//
    	add_action( 'get_header', [ __CLASS__, 'builder_header_support' ] );
    	//
		add_action( 'get_footer', [ __CLASS__, 'builder_footer_support' ] );
		
	}
	
	public static function getInstance() {
		if( is_null( self::$instance ) ) {
			self::$instance = new self;
		}

		return self::$instance;
	}

    public static function loadCanvasTemplate( $single_template ) {

		global $post;

		if ( 'ea_builder_template' == $post->post_type ) {

			$elementor_2_0_canvas = ELEMENTOR_PATH . '/modules/page-templates/templates/canvas.php';

			if ( file_exists( $elementor_2_0_canvas ) ) {
				return $elementor_2_0_canvas;
			} else {
				return ELEMENTOR_PATH . '/includes/page-templates/canvas.php';
			}
		}

		return $single_template;
	}

	public static function HeaderFooterPostType() {

		$labels = array(
			'name'               => esc_html__( 'Header Footer', 'enteraddons' ),
			'singular_name'      => esc_html__( 'Header Footer', 'enteraddons' ),
			'menu_name'          => esc_html__( 'Header Footer', 'enteraddons' ),
			'name_admin_bar'     => esc_html__( 'Header', 'enteraddons' ),
			'add_new'            => esc_html__( 'Add New', 'enteraddons' ),
			'add_new_item'       => esc_html__( 'Add New ', 'enteraddons' ),
			'new_item'           => esc_html__( 'New ', 'enteraddons' ),
			'edit_item'          => esc_html__( 'Edit ', 'enteraddons' ),
			'view_item'          => esc_html__( 'View ', 'enteraddons' ),
			'all_items'          => esc_html__( 'All ', 'enteraddons' ),
			'search_items'       => esc_html__( 'Search ', 'enteraddons' ),
			'parent_item_colon'  => esc_html__( 'Parent :', 'enteraddons' ),
			'not_found'          => esc_html__( 'No Header found.', 'enteraddons' ),
			'not_found_in_trash' => esc_html__( 'No Header found in Trash.', 'enteraddons' ),
		);

		$args = array(
			'labels'              => $labels,
			'public'              => true,
			'rewrite'             => false,
			'show_ui'             => true,
			'show_in_menu'        => false,
			'show_in_nav_menus'   => false,
			'exclude_from_search' => true,
			'capability_type'     => 'post',
			'hierarchical'        => false,
			'supports'            => array( 'title', 'elementor' ),
		);

		register_post_type( 'ea_builder_template', $args );

		remove_post_type_support( 'ea_builder_template', 'page-attributes' );
	}

	public static function hf_query_filter( $query ) {

		global $pagenow;

		$postType = isset( $_GET['post_type'] ) && $_GET['post_type'] == 'ea_builder_template' ? $_GET['post_type'] : '';
		$filterType = isset( $_GET['ea_filter_type'] ) && ( $_GET['ea_filter_type'] == 'header' || $_GET['ea_filter_type'] == 'footer' ) ? $_GET['ea_filter_type'] : '';

		if( is_admin() && 'edit.php' == $pagenow && $postType && $filterType ) {
			$query->query_vars['meta_key']     = '_ea_hf_type';
			$query->query_vars['meta_value']   = sanitize_text_field( wp_unslash( $filterType ) );
			$query->query_vars['meta_compare'] = '=';
		}

	}
	
	public static function builder_header_support( $name ) {
		// Exclude Page
		global $post;
   		$getPageSlug = $post->post_name ?? '';

	    // Get Global Header Template ID
		$globalTempID = self::headerFooterQuery('header');

		$excludePage = get_post_meta( $globalTempID, '_ea_exclude_page', true );
		$showonfof = get_post_meta( $globalTempID, '_ea_hf_show_onfof', true );
		$excludePage = !empty( $excludePage ) ? json_decode( $excludePage, true ) : [];

		//
		$tempId = apply_filters( 'ea_hf_header_template_id', $globalTempID );

		//
		if( ( is_404() && $showonfof == 'yes' ) || !is_404() ) {
		    if( !empty( $tempId ) && !in_array( $getPageSlug, $excludePage ) ) {

				// Load enteraddons header template			
				$args = $tempId;

				load_template( ENTERADDONS_DIR_PATH.'header-footer-builder/templates/header.php', true, $args );

				$templates = [];
				$name = $name;
				if ( '' !== $name ) {
					$templates[] = "header-{$name}.php";
				}

				$templates[] = 'header.php';

				remove_all_actions( 'wp_head' );
				ob_start();
				locate_template( $templates, true );
				ob_get_clean();

			}
		}

	}

	public static function builder_footer_support( $name ) {
		// Exclude Page
		global $post;
   		$getPageSlug = $post->post_name ?? '';

		// Get Global Footer Template ID
		$globalTempID = self::headerFooterQuery('footer');
		
		$excludePage = get_post_meta( $globalTempID, '_ea_exclude_page', true );
		$showonfof = get_post_meta( $globalTempID, '_ea_hf_show_onfof', true );
		$excludePage =  !empty( $excludePage ) ? json_decode( $excludePage, true ) : [];

		//
		$tempId = apply_filters( 'ea_hf_footer_template_id', $globalTempID );
		
		if( ( is_404() && $showonfof == 'yes' ) || !is_404() ) {
		   	if( !empty( $tempId ) && !in_array( $getPageSlug, $excludePage ) ) {
				// Load enteraddons footer template
							
				$args = $tempId;
				load_template( ENTERADDONS_DIR_PATH.'header-footer-builder/templates/footer.php', true, $args );

				$templates = [];
				$name = $name;
				if ( '' !== $name ) {
					$templates[] = "footer-{$name}.php";
				}

				$templates[] = 'footer.php';

				ob_start();
				locate_template( $templates, true );
				ob_get_clean();
			}
		}


	}

	private static function headerFooterQuery( $type = '' ) {

		$posts = get_posts( array(
		    'numberposts'	=> 1,
		    'post_type'		=> 'ea_builder_template',
		    'post_status'   => 'publish',
		    'meta_query'	=> array(
		        'relation'	=> 'AND',
		        array(
		            'key'	 => '_ea_hf_type',
		            'value' => $type,
		            'compare' 	 => '=',
		        ),
		        array(
		            'key'	 => '_ea_use_on_header',
		            'value' => 'global',
		            'compare' 	 => '=',
		        ),
		        array(
		            'key'	 => '_ea_hf_status',
		            'value' => 'yes',
		            'compare' 	 => '=',
		        )
		    ),
		));

		return !empty( $posts[0]->ID ) ? $posts[0]->ID : '';

	}


} // END CLASS
