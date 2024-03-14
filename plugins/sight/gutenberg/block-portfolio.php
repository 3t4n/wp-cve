<?php
/**
 * The Gutenberg Block.
 *
 * @link       https://codesupply.co
 * @since      1.0.0
 *
 * @package    Sight
 */

/**
 * The initialize block.
 */
class Sight_Block_Portfolio {

	/**
	 * Initialize
	 */
	public function __construct() {
		add_action( 'init', array( $this, 'block_register' ) );
		add_action( 'wp_ajax_sight_render_thumbnail', array( $this, 'sight_render_thumbnail' ) );
		add_action( 'wp_ajax_nopriv_sight_render_thumbnail', array( $this, 'sight_render_thumbnail' ) );
	}

	/**
	 * Registers layouts of block.
	 */
	public function block_layouts() {
		$layouts = array(
			'standard' => array(
				'name'       => esc_html__( 'Standard', 'sight' ),
				'icon'       => '<svg height="44" width="52" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 44.69 29.75"><path d="M1.55.75h9.59a.8.8 0 01.8.8v9.64a.8.8 0 01-.8.81H1.55a.8.8 0 01-.8-.81V1.55a.8.8 0 01.8-.8zM17.55.75h9.59a.8.8 0 01.8.8v9.64a.8.8 0 01-.8.81h-9.59a.8.8 0 01-.8-.81V1.55a.8.8 0 01.8-.8zM33.55.75h9.59a.8.8 0 01.8.8v9.64a.8.8 0 01-.8.81h-9.59a.8.8 0 01-.8-.81V1.55a.8.8 0 01.8-.8zM1.55 17.75h9.59a.8.8 0 01.8.8v9.64a.8.8 0 01-.8.81H1.55a.8.8 0 01-.8-.81v-9.64a.8.8 0 01.8-.8zM17.55 17.75h9.59a.8.8 0 01.8.8v9.64a.8.8 0 01-.8.81h-9.59a.8.8 0 01-.8-.81v-9.64a.8.8 0 01.8-.8zM33.55 17.75h9.59a.8.8 0 01.8.8v9.64a.8.8 0 01-.8.81h-9.59a.8.8 0 01-.8-.81v-9.64a.8.8 0 01.8-.8z" fill="none" stroke="#2d2d2d" stroke-width="1.5"/></svg>',
				'template'   => SIGHT_PATH . 'render/handler/post-area-init.php',
				'attributes' => array(
					'filter_items'    => array(
						'type'            => 'boolean',
						'default'         => true,
						'active_callback' => array(
							array(
								'field'    => 'layout',
								'operator' => '==',
								'value'    => 'standard',
							),
							array(
								'field'    => 'source',
								'operator' => '==',
								'value'    => 'projects',
							),
							array(
								'field'    => 'projects_filter_post_type',
								'operator' => '==',
								'value'    => 'sight-projects',
							),
						),
					),
					'pagination_type' => array(
						'type'            => 'string',
						'default'         => 'ajax',
						'active_callback' => array(
							array(
								'field'    => 'layout',
								'operator' => '==',
								'value'    => 'standard',
							),
							array(
								'field'    => 'source',
								'operator' => '==',
								'value'    => 'projects',
							),
						),
					),
				),
			),
		);

		// Return.
		return apply_filters( 'sight_block_portfolio_layouts', $layouts );
	}

	/**
	 * Registers attributes of block.
	 */
	public function block_attributes() {
		$attributes = array(
			'layout'                     => array(
				'type'    => 'string',
				'default' => 'standard',
			),
			'source'                     => array(
				'type'    => 'string',
				'default' => 'projects',
			),
			'video'                      => array(
				'type'            => 'string',
				'default'         => 'always',
				'active_callback' => array(
					array(
						'field'    => 'source',
						'operator' => '==',
						'value'    => 'projects',
					),
				),
			),
			'video_controls'             => array(
				'type'            => 'boolean',
				'default'         => true,
				'active_callback' => array(
					array(
						'field'    => 'source',
						'operator' => '==',
						'value'    => 'projects',
					),
					array(
						'field'    => 'video',
						'operator' => '!=',
						'value'    => 'none',
					),
				),
			),
			'custom_post'                => array(
				'type'            => 'string',
				'default'         => '',
				'active_callback' => array(
					array(
						'field'    => 'source',
						'operator' => '==',
						'value'    => 'post',
					),
				),
			),
			'custom_images'              => array(
				'type'            => 'array',
				'active_callback' => array(
					array(
						'field'    => 'source',
						'operator' => '==',
						'value'    => 'custom',
					),
				),
			),
			'number_items'               => array(
				'type'    => 'number',
				'default' => 10,
			),
			'attachment_lightbox'        => array(
				'type'    => 'boolean',
				'default' => false,
			),
			'attachment_lightbox_icon'   => array(
				'type'            => 'boolean',
				'default'         => true,
				'active_callback' => array(
					array(
						'field'    => 'attachment_lightbox',
						'operator' => '==',
						'value'    => true,
					),
				),
			),
			'attachment_link_to'         => array(
				'type'            => 'string',
				'default'         => 'page',
				'active_callback' => array(
					array(
						'field'    => 'attachment_lightbox',
						'operator' => '!=',
						'value'    => true,
					),
				),
			),
			'attachment_view_more'       => array(
				'type'            => 'boolean',
				'default'         => false,
				'active_callback' => array(
					array(
						'field'    => 'attachment_lightbox',
						'operator' => '!=',
						'value'    => true,
					),
					array(
						'field'    => 'attachment_link_to',
						'operator' => '!=',
						'value'    => 'none',
					),
				),
			),
			'attachment_size'            => array(
				'type'    => 'string',
				'default' => 'large',
			),
			'attachment_orientation'     => array(
				'type'    => 'string',
				'default' => 'landscape-16-9',
			),
			'typography_heading'         => array(
				'type'            => 'string',
				'default'         => '',
				'active_callback' => array(
					array(
						'field'    => 'source',
						'operator' => '!=',
						'value'    => 'post',
					),
					array(
						'field'    => 'source',
						'operator' => '!=',
						'value'    => 'custom',
					),
					array(
						'field'    => 'meta_title',
						'operator' => '==',
						'value'    => true,
					),
				),
			),
			'typography_heading_tag'     => array(
				'type'            => 'string',
				'default'         => 'h3',
				'active_callback' => array(
					array(
						'field'    => 'source',
						'operator' => '!=',
						'value'    => 'post',
					),
					array(
						'field'    => 'source',
						'operator' => '!=',
						'value'    => 'custom',
					),
					array(
						'field'    => 'meta_title',
						'operator' => '==',
						'value'    => true,
					),
				),
			),
			'typography_caption'         => array(
				'type'            => 'string',
				'default'         => '',
				'active_callback' => array(
					array(
						'field'    => 'meta_caption',
						'operator' => '==',
						'value'    => true,
					),
				),
			),
			'meta_title'                 => array(
				'type'            => 'boolean',
				'default'         => true,
				'active_callback' => array(
					array(
						'field'    => 'source',
						'operator' => '!=',
						'value'    => 'post',
					),
					array(
						'field'    => 'source',
						'operator' => '!=',
						'value'    => 'custom',
					),
				),
			),
			'meta_caption'               => array(
				'type'    => 'boolean',
				'default' => true,
			),
			'meta_caption_length'        => array(
				'type'            => 'number',
				'default'         => 100,
				'active_callback' => array(
					array(
						'field'    => 'meta_caption',
						'operator' => '==',
						'value'    => true,
					),
				),
			),
			'meta_category'              => array(
				'type'            => 'boolean',
				'default'         => false,
				'active_callback' => array(
					array(
						'field'    => 'source',
						'operator' => '==',
						'value'    => 'projects',
					),
					array(
						'field'    => 'projects_filter_post_type',
						'operator' => '==',
						'value'    => 'sight-projects',
					),
				),
			),
			'meta_date'                  => array(
				'type'            => 'boolean',
				'default'         => false,
				'active_callback' => array(
					array(
						'field'    => 'source',
						'operator' => '!=',
						'value'    => 'categories',
					),
				),
			),
			'projects_filter_post_type'  => array(
				'type'            => 'string',
				'default'         => 'sight-projects',
				'active_callback' => array(
					array(
						'field'    => 'source',
						'operator' => '==',
						'value'    => 'projects',
					),
				),
			),
			'projects_filter_categories' => array(
				'type'            => 'array',
				'active_callback' => array(
					array(
						'field'    => 'source',
						'operator' => '==',
						'value'    => 'projects',
					),
					array(
						'field'    => 'projects_filter_post_type',
						'operator' => '==',
						'value'    => 'sight-projects',
					),
				),
			),
			'projects_filter_offset'     => array(
				'type'            => 'string',
				'active_callback' => array(
					array(
						'field'    => 'source',
						'operator' => '==',
						'value'    => 'projects',
					),
				),
			),
			'projects_orderby'           => array(
				'type'            => 'string',
				'default'         => 'date',
				'active_callback' => array(
					array(
						'field'    => 'source',
						'operator' => '==',
						'value'    => 'projects',
					),
				),
			),
			'projects_order'             => array(
				'type'            => 'string',
				'default'         => 'DESC',
				'active_callback' => array(
					array(
						'field'    => 'source',
						'operator' => '==',
						'value'    => 'projects',
					),
				),
			),
			'categories_filter_ids'      => array(
				'type'            => 'array',
				'active_callback' => array(
					array(
						'field'    => 'source',
						'operator' => '==',
						'value'    => 'categories',
					),
				),
			),
			'categories_orderby'         => array(
				'type'            => 'string',
				'default'         => 'name',
				'active_callback' => array(
					array(
						'field'    => 'source',
						'operator' => '==',
						'value'    => 'categories',
					),
				),
			),
			'categories_order'           => array(
				'type'            => 'string',
				'default'         => 'ASC',
				'active_callback' => array(
					array(
						'field'    => 'source',
						'operator' => '==',
						'value'    => 'categories',
					),
				),
			),
			'color_heading'              => array(
				'type'            => 'string',
				'default'         => '',
				'active_callback' => array(
					array(
						'field'    => 'source',
						'operator' => '!=',
						'value'    => 'post',
					),
					array(
						'field'    => 'source',
						'operator' => '!=',
						'value'    => 'custom',
					),
					array(
						'field'    => 'meta_title',
						'operator' => '==',
						'value'    => true,
					),
				),
			),
			'color_heading_hover'        => array(
				'type'            => 'string',
				'default'         => '',
				'active_callback' => array(
					array(
						'field'    => 'source',
						'operator' => '!=',
						'value'    => 'post',
					),
					array(
						'field'    => 'source',
						'operator' => '!=',
						'value'    => 'custom',
					),
					array(
						'field'    => 'meta_title',
						'operator' => '==',
						'value'    => true,
					),
				),
			),
			'color_caption'              => array(
				'type'            => 'string',
				'default'         => '',
				'active_callback' => array(
					array(
						'field'    => 'meta_caption',
						'operator' => '==',
						'value'    => true,
					),
				),
			),
		);

		// Set attributes from layouts.
		$layouts = $this->block_layouts();

		foreach ( $layouts as $slug => $layout ) {
			if ( isset( $layout['attributes'] ) && $layout['attributes'] ) {
				foreach ( $layout['attributes'] as $attribute => $settings ) {
					$attributes[ $slug . '_' . $attribute ] = $settings;
				}
			}
		}

		// Set system settings.
		foreach ( $attributes as $key => $attribute ) {
			$attributes[ $key ]['visible'] = true;
		}

		// Return.
		return apply_filters( 'sight_block_portfolio_attributes', $attributes );
	}

	/**
	 * Registers a block type.
	 */
	public function block_register() {
		wp_register_script( 'sight-block-portfolio', SIGHT_URL . 'gutenberg/jsx/block-portfolio.js', array( 'wp-blocks', 'wp-components', 'wp-element', 'wp-i18n', 'wp-editor' ) );

		// Set config of block.
		wp_localize_script(
			'sight-block-portfolio',
			'sightBlockConfig',
			array(
				'name'             => esc_html__( 'Portfolio', 'sight' ),
				'icon'             => '<svg width="24" height="24" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" role="img" aria-hidden="true" focusable="false"><path d="M20 4v12H8V4h12m0-2H8c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zm-8.5 9.67l1.69 2.26 2.48-3.1L19 15H9zM2 6v14c0 1.1.9 2 2 2h14v-2H4V6H2z"></path></svg>',
				'category'         => 'common',
				'archive'          => sight_is_archive(),
				'layouts'          => $this->block_layouts(),
				'attributes'       => $this->block_attributes(),
				'post_types_stack' => $this->convert_array_to_options( sight_get_post_types_stack() ),
				'categories_stack' => $this->convert_array_to_options( sight_get_categories_stack() ),
				'image_sizes'      => $this->convert_array_to_options( sight_get_list_available_image_sizes() ),
				'ajax_url'         => admin_url( 'admin-ajax.php' ),
			)
		);

		// Register portfolio block.
		register_block_type(
			'sight/portfolio',
			array(
				'attributes'      => $this->block_attributes(),
				'editor_script'   => 'sight-block-portfolio',
				'render_callback' => array( $this, 'block_render_callback' ),
			)
		);
	}

	/**
	 * Render attributes from block.
	 *
	 * @param array  $attributes The attributes.
	 * @param string $layout     The layout.
	 */
	public function render_attributes( $attributes, $layout ) {
		// Get layouts.
		$block_attributes = $this->block_attributes();

		foreach ( $attributes as $key => $settings ) {
			if ( isset( $block_attributes[ $key ]['active_callback'] ) && $block_attributes[ $key ]['active_callback'] ) {
				$is_visible = Sight_Utils_Is_Field_Visible::check_condition( $block_attributes[ $key ]['active_callback'], $attributes, 'AND' );

				if ( ! $is_visible ) {
					unset( $attributes[ $key ] );
				}
			}
		}

		return $attributes;
	}

	/**
	 * Render options from block.
	 *
	 * @param array  $attributes The attributes.
	 * @param string $layout     The layout.
	 */
	public function render_options( $attributes, $layout ) {
		$options = array();

		// Get layouts.
		$layouts = $this->block_layouts();

		// Render stack.
		if ( isset( $layouts[ $layout ]['attributes'] ) && $layouts[ $layout ]['attributes'] ) {

			foreach ( $layouts[ $layout ]['attributes'] as $key => $settings ) {

				$search = $layout . '_' . $key;

				if ( array_key_exists( $search, $attributes ) ) {
					$options[ $key ] = $attributes[ $search ];
				}
			}
		}

		return $options;
	}

	/**
	 * Callback used to render blocks of this block type.
	 *
	 * @param array  $attributes The attributes.
	 * @param string $content    The content.
	 */
	public function block_render_callback( $attributes, $content ) {
		$layouts = $this->block_layouts();

		ob_start();

		// Generate id.
		$id = uniqid();

		// Set layout.
		$layout = $attributes['layout'];

		// Render attributes.
		$attributes = $this->render_attributes( $attributes, $layout );

		// Render options.
		$options = $this->render_options( $attributes, $layout );

		// Set classes.
		$classes  = " sight-block-portfolio-id-{$id}";
		$classes .= " sight-block-portfolio-layout-{$layout}";

		// Output.
		?>
		<div class="sight-block-portfolio <?php echo esc_attr( $classes ); ?>">
			<?php
			if ( isset( $layouts[ $layout ]['template'] ) && file_exists( $layouts[ $layout ]['template'] ) ) {

				// Require custom template.
				require $layouts[ $layout ]['template'];
			} else {

				// Default template.
				require SIGHT_PATH . 'render/handler/post-area-init.php';
			}
			?>
		</div>

		<?php sight_portfolio_render_style( $attributes, $options, $id ); ?>

		<?php

		return ob_get_clean();
	}

	/**
	 * Convert array to options
	 *
	 * @param array $array The array.
	 */
	public function convert_array_to_options( $array = array() ) {

		$options = array();

		foreach ( $array as $key => $value ) {
			$options[] = array(
				'value' => $key,
				'label' => $value,
			);
		}

		return $options;
	}

	/**
	 * Render thumbnail.
	 */
	public function sight_render_thumbnail() {
		wp_verify_nonce( null );

		if ( isset( $_GET['image_id'] ) ) { // Input var ok.
			$image_id = sanitize_text_field( wp_unslash( $_GET['image_id'] ) ); // Input var ok.

			$image_url = wp_get_attachment_image_url( $image_id, 'thumbnail' );

			if ( $image_url ) {
				header( 'Location: ' . $image_url );
			}
		}
		die();
	}
}

new Sight_Block_Portfolio();
