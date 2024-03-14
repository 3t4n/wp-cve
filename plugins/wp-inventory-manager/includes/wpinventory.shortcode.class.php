<?php

// No direct access allowed.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class for shortcode functionality
 *
 * @author WP Inventory Manager
 *
 */
class WPIMShortcode extends WPIMCore {

	private static $instance;

	private static $args;

	private static $template_part;

	private static $loop;

	private static $template;

	private static $inventory_id = NULL;

	/**
	 * Constructor magic method.
	 * Private because this class should not be called on its own.
	 */
	public function __construct() {
		parent::__construct();
		self::$template = new WPIMTemplate();
	}

	public static function getInstance() {
		if ( ! self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;
	}

	/**
	 * This is here purely to prevent someone from cloning the class
	 */
	private function __clone() {
	}

	public function get( $args = [] ) {
		/**
		 * Detect which view, and load the appropriate one
		 */
		$default = [
			'inventory_id'     => NULL,
			'category_id'      => NULL,
			'product_id'       => NULL,
			'category'         => NULL,
			'order'            => 'inventory_number',
			'inventory_search' => NULL,
			'inventory_status' => '',
			'page'             => 0,
			'page_size'        => self::$config->get( 'page_size', 20 ),
			'backlink'         => 1
		];

		if ( ! is_array( $args ) ) {
			parse_str( $args, $args );
		}

		self::$args = wp_parse_args( $args, $default );

		if ( in_array( strtolower( self::$args['backlink'] ), [ 'off', '0', 'false' ] ) ) {
			add_filter( 'wpim_detail_back_link', function () {
				return '';
			} );
		}

		foreach ( self::$args AS $key => $value ) {
			if ( self::request( $key ) ) {
				self::$args[ $key ] = self::request( $key );
			}
		}

		if ( ! self::$args['category_id'] && self::$args['category'] ) {
			self::$args['category_id'] = self::$args['category'];
			unset( self::$args['category'] );
		}

		if ( self::$args['category_id'] && ! (int) self::$args['category_id'] ) {
			$WPIMCategory = new WPIMCategory();
			$category     = $WPIMCategory->get_id_from_name( self::$args['category_id'] );
			if ( $category ) {
				self::$args['category_id'] = $category;
			}
		}

		$filters = apply_filters( 'wpim_filter_fields', self::$filters );

		foreach ( $filters AS $filter => $field ) {
			if ( self::request( $filter ) ) {
				self::$args[ $field ] = self::request( $filter );
			}
		}

		self::$args = self::parse_args_separators( self::$args, [ 'category_id', 'product_id', 'order' ] );

		self::debug_log( self::$args );

		self::$args = apply_filters( 'wpim_shortcode_args', self::$args );

		self::debug_log( self::$args );

		self::$loop = new WPIMLoop( self::$args );
		wpinventory_set_loop( self::$loop );

		if ( self::$args['inventory_id'] ) {
			return self::display_detail();
		} else {
			return self::display_listing();
		}
	}

	public function get_template( $template, $echo = FALSE, $type = 'list' ) {
		$file = self::$template->get( $template );

		if ( ! $file ) {
			return '';
		}

		if ( ! file_exists( $file ) ) {
			echo '<!-- WPInventory Error: template part ' . $file . ' missing! -->';
		}

		if ( ! $echo ) {
			ob_start();
		}

		include $file;

		do_action( 'wpim_get_template', $template, $echo, $type, self::$inventory_id );

		$content = ob_get_clean();
		$content = apply_filters( 'wpim_shortcode_content', $content, $type );

		if ( ! $echo ) {
			return $content;
		}
	}

	protected function display_listing() {
		$type         = 'list';
		$inventory_id = NULL;
		// Default template is the loop-all
		self::$template_part = "loop-all";
		self::$template_part .= ( self::$config->get( 'display_listing_table' ) ) ? '-table' : '';
		// If we're viewing a category, set the template to loop-category
		if ( ! empty( self::$args['category_id'] ) ) {
			self::$template_part = 'loop-category';
			self::$template_part .= ( self::$config->get( 'display_listing_table' ) ) ? '-table' : '';
			// If we're viewing a single item, set the template to view a single item
		} else if ( ! empty( self::$args['inventory_id'] ) || self::$loop->is_single() ) {
			self::$template_part = 'single-item';
			$type                = 'single';
			self::$inventory_id  = self::$loop->single_id();
		}

		self::$template_part = apply_filters( 'wpim_display_listing_template_part', self::$template_part, $type );

		return $this->get_template( self::$template_part . '.php', FALSE, $type );
	}

	protected function display_detail() {
		// Default template is the loop-all
		self::$template_part = "single-item";
		$inventory_id        = self::$loop->single_id();

		return $this->get_template( self::$template_part . '.php', FALSE, 'single' );
	}
}

global $WPIMLoop;
