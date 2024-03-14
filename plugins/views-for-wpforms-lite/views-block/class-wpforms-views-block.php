<?php
class WPF_Views_Block {

	public function __construct() {
		add_action( 'init', array( $this, 'register_block' ) );
		add_action( 'init', array( $this, 'register_meta' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ), 10, 0 );

	}

	function register_block() {
		register_block_type(
			WPFORMS_VIEWS_DIR_URL_LITE . '/views-block/build',
			array(
				'render_callback' => array( $this, 'render_block' ),
			)
		);
	}

	/**
	 * Register the custom meta fields
	 */
	function register_meta() {

		$metafields = array( '_view_id' );

		foreach ( $metafields as $metafield ) {
			// Pass an empty string to register the meta key across all existing post types.
			register_post_meta(
				'',
				$metafield,
				array(
					'show_in_rest'      => true,
					'type'              => 'string',
					'single'            => true,
					'sanitize_callback' => 'sanitize_text_field',
					'auth_callback'     => function() {
						return current_user_can( 'edit_posts' );
					},
				)
			);
		}
	}

	function render_block( $attributes, $content, $block ) {
		$view_id = get_post_meta( get_the_ID(), '_view_id', true );

		if ( ! empty( $view_id ) ) {
			return '<div ' . get_block_wrapper_attributes() . '>' . do_shortcode( '[wpforms-views id=' . $view_id . ']' ) . '</div>';
		} else {
			return '<div ' . get_block_wrapper_attributes() . '>' . '<strong>' . __( 'Please select a View!' ) . '</strong>' . '</div>';
		}

	}


	public function admin_enqueue_scripts() {
		 wp_enqueue_script( 'wp_views_block', WPFORMS_VIEWS_URL_LITE . '/assets/js/post-editor.js', array( 'jquery' ) );

			$args      = array(
				'post_type'      => 'wpforms-views',
				'post_status'    => 'publish',
				'posts_per_page' => -1,
			);
			$views     = get_posts( $args );
			$view_list = array(
				array(
					'value' => '',
					'label' => 'Select',
				),
			);
			if ( ! empty( $views ) ) {
				foreach ( $views as $view ) {
					$view_list[] = array(
						'value' => $view->ID,
						'label' => $view->post_title,
					);
				}
			}
			$js_settings              = array();
			$js_settings['admin_url'] = get_admin_url( '' );
			$js_settings['views']     = $view_list;
			wp_localize_script( 'wp_views_block', 'wp_views_block', $js_settings );

	}

}

new WPF_Views_Block();
