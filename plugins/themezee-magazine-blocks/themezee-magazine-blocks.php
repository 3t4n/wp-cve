<?php
/**
 * Plugin Name:       Themezee Magazine Blocks
 * Plugin URI:        https://themezee.com/plugins/magazine-blocks/
 * Description:       Flexible Magazine Blocks for the new WordPress Block Editor.
 * Requires at least: 6.3
 * Requires PHP:      8.0
 * Version:           1.3.2
 * Author:            ThemeZee
 * Author URI:        https://themezee.com/
 * License:           GPL-2.0-or-later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       themezee-magazine-blocks
 *
 * @package           ThemeZee Magazine Blocks
 *
 * Copyright(C) 2023, themezee.com - support@themezee.com
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Main ThemeZee_Magazine_Blocks Class
 *
 * @package ThemeZee Magazine Blocks
 */
class ThemeZee_Magazine_Blocks {
	/**
	 * Call all Functions to setup the Plugin
	 *
	 * @uses ThemeZee_Magazine_Blocks::constants() Setup the constants needed
	 * @uses ThemeZee_Magazine_Blocks::includes() Include the required files
	 * @uses ThemeZee_Magazine_Blocks::setup_actions() Setup the hooks and actions
	 * @return void
	 */
	public static function setup() {
		// Setup Constants.
		self::constants();

		// Include Files.
		self::includes();

		// Setup Action Hooks.
		self::setup_actions();
	}

	/**
	 * Setup plugin constants
	 *
	 * @return void
	 */
	public static function constants() {
		// Define Version Number.
		define( 'THEMEZEE_MAGAZINE_BLOCKS_VERSION', '1.3.2' );

		// Plugin Folder Path.
		define( 'THEMEZEE_MAGAZINE_BLOCKS_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );

		// Plugin Folder URL.
		define( 'THEMEZEE_MAGAZINE_BLOCKS_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

		// Plugin Root File.
		define( 'THEMEZEE_MAGAZINE_BLOCKS_PLUGIN_FILE', __FILE__ );
	}

	/**
	 * Include required files
	 *
	 * @return void
	 */
	public static function includes() {
		// Include Magazine Cache Class.
		require_once THEMEZEE_MAGAZINE_BLOCKS_PLUGIN_DIR . '/includes/class-themezee-magazine-blocks-cache.php';

		// Include Magazine Template Class.
		require_once THEMEZEE_MAGAZINE_BLOCKS_PLUGIN_DIR . '/includes/class-themezee-magazine-blocks-template.php';
	}

	/**
	 * Setup Action Hooks
	 *
	 * @see https://codex.wordpress.org/Function_Reference/add_action WordPress Codex
	 * @return void
	 */
	public static function setup_actions() {
		// Enqueue Block Assets.
		add_action( 'init', array( __CLASS__, 'register_blocks' ) );

		// Setup Translation.
		add_action( 'init', array( __CLASS__, 'translation' ) );

		// Transfer Data from PHP to ThemeZee Magazine Blocks scripts.
		add_action( 'init', array( __CLASS__, 'data' ) );

		// Add block category.
		add_filter( 'block_categories_all', array( __CLASS__, 'block_categories' ), 10, 2 );
	}

	/**
	 * Register blocks
	 *
	 * @return void
	 */
	public static function register_blocks() {
		register_block_type(
			__DIR__ . '/build/blocks/column',
			array(
				'title'       => _x( 'Magazine Column', 'block title', 'themezee-magazine-blocks' ),
				'description' => _x( 'Displays your posts in a list with the first post highlighted.', 'block description', 'themezee-magazine-blocks' ),
			),
		);

		register_block_type(
			__DIR__ . '/build/blocks/columns',
			array(
				'title'       => _x( 'Magazine Columns', 'block title', 'themezee-magazine-blocks' ),
				'description' => _x( 'Displays your latest posts in two separate columns.', 'block description', 'themezee-magazine-blocks' ),
			),
		);

		register_block_type(
			__DIR__ . '/build/blocks/grid',
			array(
				'title'       => _x( 'Magazine Grid', 'block title', 'themezee-magazine-blocks' ),
				'description' => _x( 'Displays your latest posts in a grid layout.', 'block description', 'themezee-magazine-blocks' ),
			),
		);

		register_block_type(
			__DIR__ . '/build/blocks/horizontal',
			array(
				'title'       => _x( 'Magazine Horizontal', 'block title', 'themezee-magazine-blocks' ),
				'description' => _x( 'Displays your latest posts in a horizontal box.', 'block description', 'themezee-magazine-blocks' ),
			),
		);

		register_block_type(
			__DIR__ . '/build/blocks/list',
			array(
				'title'       => _x( 'Magazine List', 'block title', 'themezee-magazine-blocks' ),
				'description' => _x( 'Displays your latest posts in a list layout.', 'block description', 'themezee-magazine-blocks' ),
			),
		);

		register_block_type(
			__DIR__ . '/build/blocks/vertical',
			array(
				'title'       => _x( 'Magazine Vertical', 'block title', 'themezee-magazine-blocks' ),
				'description' => _x( 'Displays your latest posts in a vertical box.', 'block description', 'themezee-magazine-blocks' ),
			),
		);
	}

	/**
	 * Load Translation File
	 *
	 * @return void
	 */
	public static function translation() {
		load_plugin_textdomain( 'themezee-magazine-blocks', false, dirname( plugin_basename( THEMEZEE_MAGAZINE_BLOCKS_PLUGIN_FILE ) ) . '/languages/' );

		// Load translation for JS files.
		wp_set_script_translations( 'themezee-magazine-blocks-column-editor-script', 'themezee-magazine-blocks', THEMEZEE_MAGAZINE_BLOCKS_PLUGIN_DIR . 'languages' );
		wp_set_script_translations( 'themezee-magazine-blocks-columns-editor-script', 'themezee-magazine-blocks', THEMEZEE_MAGAZINE_BLOCKS_PLUGIN_DIR . 'languages' );
		wp_set_script_translations( 'themezee-magazine-blocks-grid-editor-script', 'themezee-magazine-blocks', THEMEZEE_MAGAZINE_BLOCKS_PLUGIN_DIR . 'languages' );
		wp_set_script_translations( 'themezee-magazine-blocks-horizontal-editor-script', 'themezee-magazine-blocks', THEMEZEE_MAGAZINE_BLOCKS_PLUGIN_DIR . 'languages' );
		wp_set_script_translations( 'themezee-magazine-blocks-list-editor-script', 'themezee-magazine-blocks', THEMEZEE_MAGAZINE_BLOCKS_PLUGIN_DIR . 'languages' );
		wp_set_script_translations( 'themezee-magazine-blocks-vertical-editor-script', 'themezee-magazine-blocks', THEMEZEE_MAGAZINE_BLOCKS_PLUGIN_DIR . 'languages' );
	}

	/**
	 * Pass data to blocks
	 *
	 * @return void
	 */
	public static function data() {
		$encoded_variables = wp_json_encode(
			array(
				'pluginUrl' => THEMEZEE_MAGAZINE_BLOCKS_PLUGIN_URL,
			)
		);

		$global_variable = 'var themezeeMagazineBlocks = ' . $encoded_variables;

		// Pass data to all JS files.
		wp_add_inline_script( 'themezee-magazine-blocks-column-editor-script', $global_variable, 'after' );
		wp_add_inline_script( 'themezee-magazine-blocks-columns-editor-script', $global_variable, 'after' );
		wp_add_inline_script( 'themezee-magazine-blocks-grid-editor-script', $global_variable, 'after' );
		wp_add_inline_script( 'themezee-magazine-blocks-horizontal-editor-script', $global_variable, 'after' );
		wp_add_inline_script( 'themezee-magazine-blocks-list-editor-script', $global_variable, 'after' );
		wp_add_inline_script( 'themezee-magazine-blocks-vertical-editor-script', $global_variable, 'after' );
	}

	/**
	 * Register block category
	 *
	 * @return array
	 */
	public static function block_categories( $categories, $post ) {
		return array_merge(
			$categories,
			array(
				array(
					'slug'  => 'themezee-magazine-blocks',
					'title' => __( 'ThemeZee Magazine Blocks', 'themezee-magazine-blocks' ),
				),
			)
		);
	}
}

// Run Plugin.
ThemeZee_Magazine_Blocks::setup();
