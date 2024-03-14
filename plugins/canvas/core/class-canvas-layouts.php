<?php
/**
 * Integration of the Layouts into the plugin.
 *
 * @package Canvas
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Integration Layouts class.
 */
class CNVS_Layouts {
	/**
	 * Constructor
	 */
	public function __construct() {
		// Register custom post type.
		add_action( 'init', array( $this, 'add_custom_post_type' ) );

		add_action( 'enqueue_block_editor_assets', array( $this, 'enqueue_block_editor_assets' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );
	}

	/**
	 * Register custom post type.
	 */
	public function add_custom_post_type() {
		register_post_type(
			'canvas_layout',
			array(
				'label'               => __( 'Canvas Layouts', 'canvas' ),
				'public'              => false,
				'publicly_queryable'  => false,
				'has_archive'         => false,
				'show_ui'             => true,
				'exclude_from_search' => true,
				'show_in_nav_menus'   => false,
				'rewrite'             => false,
				'menu_icon'           => 'dashicons-editor-table',
				'hierarchical'        => false,
				'show_in_menu'        => false,
				'show_in_admin_bar'   => false,
				'show_in_rest'        => true,
				'capability_type'     => 'post',
				'supports'            => array(
					'title',
					'editor',
					'thumbnail',
				),
			)
		);
	}

	/**
	 * Get layouts data.
	 *
	 * @return array
	 */
	public function get_layouts_data() {
		// Predefined layouts.
		$layouts = apply_filters( 'canvas_register_layouts', array() );

		$categories = apply_filters( 'canvas_register_layouts_categories', array() );

		if ( $layouts ) {
			// Parse layouts JSON.
			foreach ( $layouts as $k => $data ) {
				$json = file_get_contents( $data['json'] );
				$json = json_decode( $json, true );

				if ( isset( $json['content'] ) ) {
					$layouts[ $k ]['content'] = $json['content'];
				}
			}

			// Get layouts from DB.
			global $post;
			$backup_global_post = $post;

			$local_layouts_query = new WP_Query(
				array(
					'post_type'      => 'canvas_layout',
					'posts_per_page' => -1,
					'showposts'      => -1,
					'paged'          => -1,
				)
			);

			while ( $local_layouts_query->have_posts() ) {
				$local_layouts_query->the_post();
				$db_template = get_post();

				$image_id   = get_post_thumbnail_id( $db_template->ID );
				$image_data = wp_get_attachment_image_src( $image_id, 'large' );

				$layouts[ 'db-layout-' . $db_template->ID ] = array(
					'title'     => $db_template->post_title,
					'content'   => $db_template->post_content,
					'thumbnail' => isset( $image_data[0] ) ? $image_data[0] : false,
					'category'  => array( 'db-layouts' ),
				);

				// add category.
				if ( ! isset( $categories['db-layouts'] ) ) {
					$categories['db-layouts'] = esc_html__( 'User Layouts', 'canvas' );
				}
			}

			wp_reset_postdata();

			$post = $backup_global_post;
		}

		return array(
			'layouts'    => $layouts,
			'categories' => $categories,
		);
	}

	/**
	 * Enqueue block editor specific scripts.
	 */
	public function enqueue_block_editor_assets() {
		wp_enqueue_script(
			'canvas-layouts',
			CNVS_URL . 'layouts/extension/index.js',
			array( 'wp-editor', 'wp-blocks', 'wp-i18n', 'wp-element', 'wp-edit-post', 'wp-compose', 'wp-components' ),
			filemtime( CNVS_PATH . 'layouts/extension/index.js' )
		);
		wp_localize_script( 'jquery-ui-core', 'canvasLayouts', $this->get_layouts_data() );
	}

	/**
	 * Enqueue admin layouts list scripts.
	 */
	public function admin_enqueue_scripts() {
		$screen = get_current_screen();

		if ( isset( $screen->id ) && 'edit-canvas_layout' === $screen->id ) {
			wp_enqueue_style( 'wp-components' );
			wp_enqueue_script(
				'canvas-layouts',
				CNVS_URL . 'layouts/admin-list/index.js',
				array( 'wp-editor', 'wp-blocks', 'wp-i18n', 'wp-element', 'wp-edit-post', 'wp-compose', 'wp-components', 'wp-api-fetch', 'lodash' ),
				filemtime( CNVS_PATH . 'layouts/admin-list/index.js' ),
				true
			);
		}
	}
}

new CNVS_Layouts();
