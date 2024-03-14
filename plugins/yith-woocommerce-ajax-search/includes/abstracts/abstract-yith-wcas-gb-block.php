<?php
/**
 * Abstract_YITH_WCAS_Gb_InnerBlock class.
 *
 * @author  YITH
 * @package YITH/Builders/Gutenberg
 * @version 2.0
 */

/**
 * Abstract_YITH_WCAS_Gb_InnerBlock class.
 */

/**
 * AbstractBlock class.
 */
abstract class Abstract_YITH_WCAS_Gb_Block {

	/**
	 * Block namespace.
	 *
	 * @var string
	 */
	protected $namespace = 'yith';

	/**
	 * Block name within this namespace.
	 *
	 * @var string
	 */
	protected $block_name = '';

	/**
	 * Tracks if assets have been enqueued.
	 *
	 * @var boolean
	 */
	protected $enqueued_assets = false;

	/**
	 * Constructor.
	 *
	 * @param string $block_name Optionally set block name during construct.
	 */
	public function __construct( $block_name = '' ) {
		$this->block_name = $block_name ? $block_name : $this->block_name;
		$this->initialize();
	}

	/**
	 * The default render_callback for all blocks. This will ensure assets are enqueued just in time, then render
	 * the block (if applicable).
	 *
	 * @param array|WP_Block $attributes Block attributes, or an instance of a WP_Block. Defaults to an empty array.
	 * @param string         $content Block content. Default empty string.
	 * @param WP_Block|null  $block Block instance.
	 *
	 * @return string Rendered block type output.
	 */
	public function render_callback( $attributes = [], $content = '', $block = null ) {

		$render_callback_attributes = $this->parse_render_callback_attributes( $attributes );
		if ( ! is_admin() && ! WC()->is_rest_api_request() ) {
			$this->enqueue_assets( $render_callback_attributes, $content, $block );
		}

		return $this->render( $render_callback_attributes, $content, $block );
	}


	/**
	 * Initialize this block type.
	 *
	 * - Hook into WP lifecycle.
	 * - Register the block with WordPress.
	 */
	protected function initialize() {
		if ( empty( $this->block_name ) ) {
			_doing_it_wrong( __METHOD__, esc_html__( 'Block name is required.', 'woo-gutenberg-products-block' ), '4.5.0' );

			return false;
		}
		$this->register_block_type_assets();
		$this->register_block_type();
	}

	/**
	 * Register script and style assets for the block type before it is registered.
	 *
	 * This registers the scripts; it does not enqueue them.
	 */
	protected function register_block_type_assets() {
		if ( null !== $this->get_block_type_editor_script() ) {
			$data     = $this->get_script_data( $this->get_block_type_editor_script( 'path' ) );
			$has_i18n = in_array( 'wp-i18n', $data['dependencies'], true );
			$this->register_script(
				$this->get_block_type_editor_script( 'handle' ),
				$this->get_block_type_editor_script( 'path' ),
				$this->get_block_type_editor_script( 'dependencies' ),
				$has_i18n
			);
		}
		if ( null !== $this->get_block_type_script() ) {
			$data     = $this->get_script_data( $this->get_block_type_script( 'path' ) );
			$has_i18n = in_array( 'wp-i18n', $data['dependencies'], true );
			$this->register_script(
				$this->get_block_type_script( 'handle' ),
				$this->get_block_type_script( 'path' ),
				$this->get_block_type_script( 'dependencies' ),
				$has_i18n
			);
		}
	}

	/**
	 * Injects Chunk Translations into the page so translations work for lazy loaded components.
	 *
	 * The chunk names are defined when creating lazy loaded components using webpackChunkName.
	 *
	 * @param string[] $chunks Array of chunk names.
	 */
	protected function register_chunk_translations( $chunks ) {
		foreach ( $chunks as $chunk ) {
			$handle = 'ywcas-blocks-' . $chunk . '-chunk';
			$this->register_script( $handle, $this->get_block_asset_build_path( $chunk ), [], true );
			wp_add_inline_script(
				$this->get_block_type_script( 'handle' ),
				wp_scripts()->print_translations( $handle, false ),
				'before'
			);
			wp_deregister_script( $handle );
		}
	}

	/**
	 * Generate an array of chunks paths for loading translation.
	 *
	 * @param string $chunks_folder The folder to iterate over.
	 *
	 * @return string[] $chunks list of chunks to load.
	 */
	protected function get_chunks_paths( $chunks_folder ) {
		$build_path = YITH_WCAS_BUILD_BLOCK_PATH;
		$blocks     = [];
		if ( ! is_dir( $build_path . $chunks_folder ) ) {
			return [];
		}
		foreach ( new \RecursiveIteratorIterator( new \RecursiveDirectoryIterator( $build_path . $chunks_folder ) ) as $block_name ) {
			$blocks[] = str_replace( $build_path, '', $block_name );
		}

		$chunks = preg_filter( '/.js/', '', $blocks );

		return $chunks;
	}

	/**
	 * Get the path to a block's metadata
	 *
	 * @param string $block_name The block to get metadata for.
	 * @param string $path Optional. The path to the metadata file inside the 'build' folder.
	 *
	 * @return string|boolean False if metadata file is not found for the block.
	 */
	public function get_block_metadata_path( $block_name, $path = '' ) {
		$path_to_metadata_from_plugin_root = YITH_WCAS_BUILD_BLOCK_PATH . $path . $block_name . '/block.json';
		if ( ! file_exists( $path_to_metadata_from_plugin_root ) ) {
			return false;
		}

		return $path_to_metadata_from_plugin_root;
	}


	/**
	 * Registers the block type with WordPress.
	 *
	 * @return string[] Chunks paths.
	 */
	protected function register_block_type() {
		$block_settings = [
			'render_callback' => $this->get_block_type_render_callback(),
			'editor_script'   => $this->get_block_type_editor_script( 'handle' ),
			'editor_style'    => $this->get_block_type_editor_style(),
			'style'           => $this->get_block_type_style(),
		];

		if ( isset( $this->api_version ) && '2' === $this->api_version ) {
			$block_settings['api_version'] = 2;
		}

		$metadata_path = $this->get_block_metadata_path( $this->block_name );

		/**
		 * We always want to load block styles separately, for every theme.
		 * When the core assets are loaded separately, other blocks' styles get
		 * enqueued separately too. Thus we only need to handle the remaining
		 * case.
		 */
		if (
			! is_admin() &&
			! wc_current_theme_is_fse_theme() &&
			$block_settings['style'] &&
			(
				! function_exists( 'wp_should_load_separate_core_block_assets' ) ||
				! wp_should_load_separate_core_block_assets()
			)
		) {
			$style_handles           = $block_settings['style'];
			$block_settings['style'] = null;
			add_filter(
				'render_block',
				function ( $html, $block ) use ( $style_handles ) {
					if ( $block['blockName'] === $this->get_block_type() ) {
						array_map( 'wp_enqueue_style', $style_handles );
					}

					return $html;
				},
				10,
				2
			);
		}

		// Prefer to register with metadata if the path is set in the block's class.
		if ( ! empty( $metadata_path ) ) {
			register_block_type_from_metadata(
				$metadata_path,
				$block_settings
			);

			return;
		}

		/*
		 * Insert attributes and supports if we're not registering the block using metadata.
		 * These are left unset until now and only added here because if they were set when registering with metadata,
		 * the attributes and supports from $block_settings would override the values from metadata.
		 */
		$block_settings['attributes']   = $this->get_block_type_attributes();
		$block_settings['supports']     = $this->get_block_type_supports();
		$block_settings['uses_context'] = $this->get_block_type_uses_context();

		register_block_type(
			$this->get_block_type(),
			$block_settings
		);
	}

	/**
	 * Get the block type.
	 *
	 * @return string
	 */
	protected function get_block_type() {
		return $this->namespace . '/' . $this->block_name;
	}

	/**
	 * Get the render callback for this block type.
	 *
	 * Dynamic blocks should return a callback, for example, `return [ $this, 'render' ];`
	 *
	 * @return callable|null;
	 * @see $this->register_block_type()
	 */
	protected function get_block_type_render_callback() {
		return [ $this, 'render_callback' ];
	}

	/**
	 * Get the editor script data for this block type.
	 *
	 * @param string $key Data to get, or default to everything.
	 *
	 * @return array|string
	 * @see $this->register_block_type()
	 */
	protected function get_block_type_editor_script( $key = null ) {
		$script = [
			'handle'       => 'ywcas-' . $this->block_name . '-block',
			'path'         => $this->get_block_asset_build_path( $this->block_name ),
			'dependencies' => [ 'ywcas-blocks', 'accounting' ],
		];

		return $key ? $script[ $key ] : $script;
	}

	/**
	 * Get the editor style handle for this block type.
	 *
	 * @return string|null
	 * @see $this->register_block_type()
	 */
	protected function get_block_type_editor_style() {
		return 'ywcas-blocks-editor-style';
	}

	/**
	 * Get the frontend script handle for this block type.
	 *
	 * @param string $key Data to get, or default to everything.
	 *
	 * @return array|string|null
	 * @see $this->register_block_type()
	 */
	protected function get_block_type_script( $key = null ) {
		$script = [
			'handle'       => 'ywcas-' . $this->block_name . '-block-frontend',
			'path'         => $this->get_block_asset_build_path( $this->block_name . '-frontend' ),
			'dependencies' => ['accounting'],
		];

		return $key ? $script[ $key ] : $script;
	}

	/**
	 * Get the frontend style handle for this block type.
	 *
	 * @return string[]|null
	 */
	protected function get_block_type_style() {
		
		$this->register_style( 'ywcas-blocks-style-frontend', $this->get_block_asset_build_path( 'frontend', 'css' ), [], 'all', true );

		return [ 'ywcas-blocks-style', 'wc-blocks-style','wc-blocks-style-all-products', 'ywcas-blocks-style-frontend' ];
	}

	/**
	 * Get the supports array for this block type.
	 *
	 * @return string;
	 * @see $this->register_block_type()
	 */
	protected function get_block_type_supports() {
		return [];
	}

	/**
	 * Get block attributes.
	 *
	 * @return array;
	 */
	protected function get_block_type_attributes() {
		return [];
	}

	/**
	 * Get block usesContext.
	 *
	 * @return array;
	 */
	protected function get_block_type_uses_context() {
		return [];
	}

	/**
	 * Parses block attributes from the render_callback.
	 *
	 * @param array|WP_Block $attributes Block attributes, or an instance of a WP_Block. Defaults to an empty array.
	 *
	 * @return array
	 */
	protected function parse_render_callback_attributes( $attributes ) {
		return is_a( $attributes, 'WP_Block' ) ? $attributes->attributes : $attributes;
	}

	/**
	 * Render the block. Extended by children.
	 *
	 * @param array    $attributes Block attributes.
	 * @param string   $content Block content.
	 * @param WP_Block $block Block instance.
	 *
	 * @return string Rendered block type output.
	 */
	protected function render( $attributes, $content, $block ) {
		return $content;
	}

	/**
	 * Enqueue frontend assets for this block, just in time for rendering.
	 *
	 * @param array    $attributes Any attributes that currently are available from the block.
	 * @param string   $content The block content.
	 * @param WP_Block $block The block object.
	 *
	 * @internal This prevents the block script being enqueued on all pages. It is only enqueued as needed. Note that
	 * we intentionally do not pass 'script' to register_block_type.
	 *
	 */
	protected function enqueue_assets( array $attributes, $content, $block ) {
		if ( $this->enqueued_assets ) {
			return;
		}
		$this->enqueue_scripts( $attributes );
		$this->enqueued_assets = true;
	}


	/**
	 * Register/enqueue scripts used for this block on the frontend, during render.
	 *
	 * @param array $attributes Any attributes that currently are available from the block.
	 */
	protected function enqueue_scripts( array $attributes = [] ) {
		if ( null !== $this->get_block_type_script() ) {
			wp_enqueue_script( $this->get_block_type_script( 'handle' ) );
		}
	}


	/**
	 * Registers a script according to `wp_register_script`, adding the correct prefix, and additionally loading translations.
	 *
	 * When creating script assets, the following rules should be followed:
	 *   1. All asset handles should have a `wc-` prefix.
	 *   2. If the asset handle is for a Block (in editor context) use the `-block` suffix.
	 *   3. If the asset handle is for a Block (in frontend context) use the `-block-frontend` suffix.
	 *   4. If the asset is for any other script being consumed or enqueued by the blocks plugin, use the `wc-blocks-` prefix.
	 *
	 * @param string $handle Unique name of the script.
	 * @param string $relative_src Relative url for the script to the path from plugin root.
	 * @param array  $dependencies Optional. An array of registered script handles this script depends on. Default empty array.
	 * @param bool   $has_i18n Optional. Whether to add a script translation call to this file. Default: true.
	 *
	 * @throws Exception If the registered script has a dependency on itself.
	 *
	 * @since 2.1.0
	 */
	public function register_script( $handle, $relative_src, $dependencies = [], $has_i18n = true ) {
		$script_data = $this->get_script_data( $relative_src, $dependencies );

		/**
		 * Filters the list of script dependencies.
		 *
		 * @param array  $dependencies The list of script dependencies.
		 * @param string $handle The script's handle.
		 *
		 * @return array
		 * @since 2.1.0
		 *
		 */
		$script_dependencies = apply_filters( 'ywcas_blocks_register_script_dependencies', $script_data['dependencies'], $handle );

		wp_register_script( $handle, $script_data['src'], $script_dependencies, $script_data['version'], true );

		if ( $has_i18n && function_exists( 'wp_set_script_translations' ) ) {
			wp_set_script_translations( $handle, 'yith-woocommerce-ajax-search', plugin_basename( YITH_WCAS_DIR ) . '/languages' );
		}
	}

	/**
	 * Registers a style according to `wp_register_style`.
	 *
	 * @param string  $handle Name of the stylesheet. Should be unique.
	 * @param string  $relative_src Relative source of the stylesheet to the plugin path.
	 * @param array   $deps Optional. An array of registered stylesheet handles this stylesheet depends on. Default empty array.
	 * @param string  $media Optional. The media for which this stylesheet has been defined. Default 'all'. Accepts media types like
	 *                              'all', 'print' and 'screen', or media queries like '(orientation: portrait)' and '(max-width: 640px)'.
	 * @param boolean $rtl Optional. Whether or not to register RTL styles.
	 *
	 * @since 2.6.0 Change src to be relative source.
	 *
	 * @since 2.5.0
	 */
	public function register_style( $handle, $relative_src, $deps = [], $media = 'all', $rtl = false ) {
		$src = YITH_WCAS_URL . $relative_src;
		$ver = YITH_WCAS_VERSION;
		wp_register_style( $handle, $src, $deps, $ver, $media );

		if ( $rtl ) {
			wp_style_add_data( $handle, 'rtl', 'replace' );
		}
	}

	/**
	 * Get src, version and dependencies given a script relative src.
	 *
	 * @param string $relative_src Relative src to the script.
	 * @param array  $dependencies Optional. An array of registered script handles this script depends on. Default empty array.
	 *
	 * @return array src, version and dependencies of the script.
	 */
	public function get_script_data( $relative_src, $dependencies = [] ) {
		if ( ! $relative_src ) {
			return array(
				'src'          => '',
				'version'      => '1',
				'dependencies' => $dependencies,
			);
		}
		$asset_path = YITH_WCAS_DIR . str_replace( '.js', '.asset.php', $relative_src );
		$asset      = file_exists( $asset_path ) ? require $asset_path : [];

		$src          = YITH_WCAS_URL . $relative_src;
		$version      = ! empty( $asset['version'] ) ? $asset['version'] : YITH_WCAS_VERSION;
		$dependencies = ! empty( $asset['dependencies'] ) ? array_merge( $asset['dependencies'], $dependencies ) : $dependencies;

		return array(
			'src'          => $src,
			'version'      => $version,
			'dependencies' => $dependencies
		);
	}

	/**
	 * Returns the appropriate asset path for current builds.
	 *
	 * @param string $filename Filename for asset path (without extension).
	 * @param string $type File type (.css or .js).
	 *
	 * @return  string             The generated path.
	 */
	public function get_block_asset_build_path( $filename, $type = 'js' ) {
		return "assets/js/blocks/build/$filename.$type";
	}

	/**
	 * Check if empty blocks has a particular block
	 *
	 * @param string $block_name The inner block to check.
	 * @param array  $inner_blocks The inner blocks.
	 *
	 * @return bool
	 */
	public function hasInnerBlock( $block_name, $inner_blocks ) {
		$inner_blocks_name = wp_list_pluck( $inner_blocks, 'blockName' );

		return in_array( $block_name, $inner_blocks_name, true );
	}

}
