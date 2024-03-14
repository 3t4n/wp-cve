<?php
/**
 * This file defines the core plugin class
 *
 * @link       davidtowoju@gmail.com
 * @since      1.0
 *
 * @package pluginette-todo-list
 */

/**
 * The core plugin class.
 *
 * @since   1.0
 * @package pluginette-todo-list
 * @author  David Towoju <hello@pluginette.com>
 */
class ToDo_Block {

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since  1.0
	 * @access protected
	 * @var    string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since  1.0
	 * @access protected
	 * @var    string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since 1.0
	 */
	public function __construct() {

		$this->version     = '1.0';
		$this->plugin_name = 'pluginette-todo-list';
	}


	/**
	 * Register all of the hooks related to both admin & public area
	 *
	 * @since  1.0
	 * @access private
	 */
	public function run_hooks() {
		add_action( 'plugins_laoded', array( $this, 'set_locale' ) );
		add_action( 'init', array( $this, 'todo_list_block_init' ) );
		add_filter( 'render_block', array( $this, 'add_input_to_list_items' ), 10, 2 );
	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * @since  1.0
	 * @access private
	 */
	private function set_locale() {
		load_plugin_textdomain(
			'pluginette-todo-list',
			false,
			plugin_dir_path( __FILE__ ) . '/languages/'
		);
	}

	/**
	 * Registers the block using the metadata loaded from the `block.json` file.
	 *
	 * @return void
	 */
	public function todo_list_block_init() {
		register_block_type( plugin_dir_path( __FILE__ ) . 'blocks/todo-item/' );
		register_block_type( plugin_dir_path( __FILE__ ) . 'blocks/todo-list/' );
	}

	/**
	 * Add input checkbox to the list on the frontend
	 *
	 * @param  array $block_content the block conent.
	 * @param  array $block the block array.
	 * @return array
	 */
	public function add_input_to_list_items( $block_content, $block ) {
		if ( ! is_admin() && 'pluginette/todo-block-item' === $block['blockName'] ) {
			$checked   = isset( $block['attrs']['checked'] ) && true === $block['attrs']['checked'] ? true : false;
			$read_only = isset( $block['attrs']['toggleReadOnly'] ) && true === $block['attrs']['toggleReadOnly'] ? 'true' : 'false';
			$disabled  = isset( $block['attrs']['toggleDisable'] ) && true === $block['attrs']['toggleDisable'] ? 'true' : 'false';

			$input = sprintf(
				'<input class="wp-block-pluginette-todo-input" type="checkbox" value="1" %s %s %s />',
				checked( 1, $checked, false ),
				'true' === $read_only ? 'data-readonly="true" onclick="this.checked=!this.checked;"' : '',
				'true' === $disabled ? 'disabled=disabled' : ''
			);

			$content  = '<div class="wp-block-pluginette-todo-block-item-wrapper">';
			$content .= $input;
			$content .= $block_content;
			$content .= '</div>';

			$block_content = apply_filters( 'todolists_add_checkbox', $content, $block_content, $checked );
		}

		return $block_content;
	}

	/**
	 * The name of the plugin
	 *
	 * @since  1.0
	 * @return string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}


	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since  1.0
	 * @return string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}
}
