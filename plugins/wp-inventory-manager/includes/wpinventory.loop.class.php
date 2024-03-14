<?php

// No direct access allowed.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class to expose WordPress loop-like functions for the inventory items
 *
 * @author    - WP Inventory Manager
 * @package   - WPInventory
 * @copyright 2013 - WP Inventory Manager
 */
class WPIMLoop extends WPIMCore {

	private static $instance;

	private $item;

	/**
	 * An array of objects of loaded inventory items
	 * @var array - inventory items
	 */
	public $items;

	/**
	 * A single inventory item
	 * @var object - inventory item
	 */
	public $current_item;

	/**
	 * The currently loaded inventory item images
	 * @var arrays
	 */
	public $images;

	/**
	 * The currently loaded inventory item media
	 * @var arrays
	 */
	public $media;

	/**
	 * An array of custom labels
	 * @var array
	 */
	private $labels;

	private $is_single = FALSE;

	private $single_id = NULL;

	private $query_args;

	/**
	 * A pointer to the item in our list of items
	 * @var integer
	 */
	private $pointer;

	/**
	 * The number of items loaded in load_items
	 * @var integer
	 */
	private $count;

	/**
	 * Local store of user information for displaying user names
	 *
	 * @var array
	 */
	private $users = [];

	private $use_currency_formats;

	private $use_date_formats;

	private $loaded = FALSE;

	/**
	 * For debugging / testing - used as a counter of number calls
	 * @var integer
	 */
	private $run = 0;


	/**
	 * Constructor magic method.
	 * Private because this class should not be called on its own.
	 *
	 * @param array $args
	 */
	public function __construct( $args = [] ) {
		parent::__construct();
		$this->labels = $this->get_labels();
		$this->item   = new WPIMItem();
		$args         = apply_filters( 'wpim_loop_query_args', $args );
		$this->set_query_args( $args );
		$this->set_single();

		$this->use_currency_formats = [ 'price', 'amount', 'inventory_price', 'inventory_amount' ];
		$this->use_currency_formats = apply_filters( 'wpim_use_currency_formats', $this->use_currency_formats );

		$this->use_date_formats = [
			'date_updated',
			'date_added',
			'inventory_date_updated',
			'inventory_date_added'
		];

		$this->use_date_formats = apply_filters( 'wpim_use_date_formats', $this->use_date_formats );
	}

	/**
	 * Get the instance of the current loop item.
	 */
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

	/**
	 * Sets the query arguments to the class variable.
	 *
	 * @param array $args
	 */
	protected function set_query_args( $args ) {
		$args = wp_parse_args( $args, $this->item->default_args() );

		$order = $this->item->parse_sort( $args['order'], $this->item->get_fields(), TRUE );

		$args = wp_parse_args( $order, $args );

		$this->query_args = $args;
	}

	/**
	 * Retrieve the arguments for the current query.
	 * @return array
	 */
	public function get_query_args() {
		return $this->query_args;
	}

	public function get_query_arg( $key, $default = NULL ) {
		return ( array_key_exists( $key, $this->query_args ) ) ? $this->query_args[ $key ] : $default;
	}

	/**
	 * Load the items into the class variables
	 *
	 * @param array $args
	 */
	public function load_items( $args = NULL ) {
		if ( ! $args ) {
			$args = $this->get_query_args();
		}

		$this->pointer = - 1;
		if ( $this->is_single() && $this->single_id() ) {
			$args['inventory_id'] = $this->single_id();
		}

		$this->total_count = $this->item->get_all( $args, TRUE, TRUE );
		$this->items       = $this->item->get_all( (array) $args, FALSE, TRUE );
		// $this->pages = $this->get_pages((array)$args);
		$this->count  = count( $this->items );
		$this->loaded = TRUE;
	}

	public function set_items( $items ) {
		$this->items  = $items;
		$this->loaded = TRUE;
		$this->rewind_items();
	}

	public function rewind_items() {
		$this->pointer = - 1;
	}

	/**
	 * Returns true / false depending on if there are more items or not
	 * @return boolean
	 */
	public function have_items() {
		if ( ! $this->loaded ) {
			$this->load_items();
		}

		return ( $this->count && ( $this->count > ( $this->pointer + 1 ) ) ) ? TRUE : FALSE;
	}

	/**
	 * Advances the pointer to the next item
	 */
	public function the_item() {
		$this->pointer ++;
		if ( $this->pointer >= ( $this->count ) ) {
			$this->pointer = ( $this->count - 1 );
		}

		$this->current_item = ( ! empty( $this->items[ $this->pointer ] ) ) ? $this->items[ $this->pointer ] : NULL;
		global $wpinventory_item;
		$wpinventory_item = $this->current_item;
		$wpinventory_item = apply_filters( 'wpim_loop_the_item', $wpinventory_item );
		$this->load_images();
		$this->load_media();
	}

	/**
	 * Returns the current item object
	 * @return object
	 */
	public function return_item() {
		return $this->current_item;
	}

	/**
	 * Load the current item's images into the class variables
	 *
	 * @return NULL
	 */
	public function load_images() {
		if ( empty( $this->current_item->inventory_id ) ) {
			return NULL;
		}
		$this->images = $this->item->get_images( $this->current_item->inventory_id );
	}

	/**
	 * Returns true / false depending on if there are images
	 *
	 * @return boolean
	 */
	public function have_images() {
		return count( $this->images );
	}

	/**
	 * Returns array of images for the current item
	 *
	 * @param string  $size
	 * @param integer $limit
	 *
	 * @return array
	 */
	public function get_images( $size = "large", $limit = 0 ) {
		$size = strtolower( $size );
		if ( $size == 'full' ) {
			$size = 'image';
		} else if ( $size == 'thumb' ) {
			$size = 'thumbnail';
		}

		$images = [];
		$count  = 0;
		foreach ( (array) $this->images AS $image ) {
			if ( $limit && ++ $count > $limit ) {
				break;
			}
			$images[] = ( isset( $image->{$size} ) ) ? $image->{$size} : $image->image;
		}

		return $images;
	}

	/**
	 * Load the current item's media into the class variables
	 *
	 * @param array $args
	 *
	 * @return NULL
	 */
	public function load_media() {
		if ( empty( $this->current_item->inventory_id ) ) {
			return NULL;
		}

		$this->media = $this->item->get_media( $this->current_item->inventory_id );
	}

	/**
	 * Returns true / false depending on if there are media
	 * @return boolean
	 */
	public function have_media() {
		return count( $this->media );
	}

	/**
	 * Returns array of media for the current item
	 *
	 * @param integer $limit
	 */
	public function get_media( $limit = 0 ) {
		$medias = [];
		$count  = 0;
		foreach ( (array) $this->media AS $media ) {
			if ( $limit && ++ $count > $limit ) {
				break;
			}
			$medias[] = $media;
		}

		return $medias;
	}

	/**
	 * Load the field from the passed-in field name.
	 * Automatically repairs based on some conventions
	 * Accepts: name or label.
	 * Example: if the fieldname is inventory_name, and the label is set to "Custom Name", then any of the following
	 * (passed in as the fieldname) will work: name inventory_name custom name
	 *
	 * @param string $fieldname
	 *
	 * @return string
	 */
	public function get_field( $fieldname ) {
		$field = $fieldname;
		$value = NULL;
		// If they passed the fully qualified field name, grab it
		if ( isset( $this->current_item->{$fieldname} ) ) {
			$value = $this->parse_field_for_display( $fieldname, $this->current_item->{$fieldname} );
		} else if ( isset( $this->current_item->{'inventory_' . $fieldname} ) ) {
			// If they passed the shortname, grab it
			$value = $this->parse_field_for_display( 'inventory_' . $fieldname, $this->current_item->{'inventory_' . $fieldname} );
		} else {
			// Did they pass a custom label? Try that...
			$label     = $fieldname;
			$fieldname = $this->get_field_from_label( $label );
			if ( $fieldname && isset( $this->current_item->{$fieldname} ) ) {
				$value = $this->parse_field_for_display( $fieldname, $this->current_item->{$fieldname} );
			}
		}

		$value = apply_filters( 'wpim_get_field', $value, $field );
		$value = apply_filters( 'wpim_get_field_' . $field, $value );

		return $value;
	}

	/**
	 * Parses a field for display.  Concerned with formatting prices, dates
	 */
	public function parse_field_for_display( $fieldname, $value ) {
		if ( in_array( $fieldname, $this->use_currency_formats ) ) {
			return $this->format_currency( $value );
		}

		if ( in_array( $fieldname, $this->use_date_formats ) ) {
			return $this->format_date( $value );
		}

		if ( $fieldname == 'user_id' || $fieldname == 'inventory_user_id' ) {
			return $this->get_username( $value );
		}
		if ( $fieldname == 'status' || $fieldname == 'inventory_status' ) {
			return self::get_status( $value );
		}

		$value = apply_filters( 'wpim_parse_field', $value, $fieldname, $this->current_item );
		$value = apply_filters( 'wpim_parse_field_' . $fieldname, $value, $this->current_item );

		return $value;
	}

	public function get_username( $user_id ) {
		if ( ! isset( $this->users[ $user_id ] ) ) {
			$user                    = get_userdata( $user_id );
			$this->users[ $user_id ] = $user;
		}

		return ( ! empty( $this->users[ $user_id ] ) ) ? $this->users[ $user_id ]->user_nicename : '';
	}

	/**
	 * Gets the permalink for the current inventory item.
	 *
	 * @param int  $post_id
	 * @param bool $recursive_call
	 *
	 * @return string
	 */
	public function get_permalink( $post_id = NULL, $recursive_call = FALSE ) {
		$post_id = ( $post_id ) ? $post_id : get_queried_object_id();

		if ( empty( $post_id ) ) {
			return '';
		}

		if ( empty( $this->current_item ) ) {
			if ( $recursive_call ) {
				return '';
			}

			return $this->get_permalink( $post_id, TRUE );
		}

		$slug = $this->current_item->inventory_slug;

		$post_id = apply_filters( 'wpim_inventory_permalink_post_id', $post_id, $this->current_item );

		$permalink = get_permalink( $post_id );
		if ( stripos( $permalink, '?' ) === FALSE && self::$seo_urls && $slug ) {
			$permalink = trim( $permalink, '/' );
			$permalink .= '/' . trim( self::$seo_endpoint, '/' ) . '/';
			$permalink .= $slug;

		} else {
			$sep       = ( stripos( $permalink, '?' ) !== FALSE ) ? '&amp;' : '?';
			$permalink .= $sep . 'inventory_id=' . $this->current_item->inventory_id;
		}

		return apply_filters( 'wpim_inventory_permalink', $permalink );
	}

	public function get_current_pagination() {
		/**
		 * ?inventory_page=1
		 * &search=1
		 * &sort=1
		 * &sort_label=Sort+By&categories=1&button=Search&search_label=Search+For&caller=&inventory_search=tes&inventory_sort_by=inventory_date_added&inventory_category_id=
		 * @var unknown_type
		 */
		$params = [
			'search',
			'sort',
			'inventory_page',
			'inventory_sort_by'
		];
	}

	/**
	 * Gets the page information - item count, page count, current page, and page size
	 * @return array
	 */
	public function get_pages() {
		$count     = $this->total_count;
		$page_size = (int) $this->query_args['page_size'];
		$page_size = ( ! $page_size ) ? (int) self::$config->get( 'page_size' ) : $page_size;
		$page_size = ( ! $page_size ) ? 20 : $page_size;
		$page      = 0;
		if ( ! empty( $this->query_args['page'] ) ) {
			$page = (int) $this->query_args['page'];
		} else if ( (int) $this->request( 'inventory_page' ) ) {
			$page = (int) $this->request( 'inventory_page' );
		}

		return [
			'item_count' => $count,
			'pages'      => ceil( $count / $page_size ),
			'page'       => $page,
			'page_size'  => $page_size
		];
	}

	/**
	 * Setter method.
	 * Determine if the loop is for a single entity or not. Sets up the appropriate variables.
	 *
	 * @param boolean $remove
	 */
	public function set_single( $remove = FALSE ) {
		if ( $remove ) {
			$this->is_single = FALSE;
			$this->single_id = NULL;

			return;
		}

		if ( ! self::$seo_endpoint ) {
			self::$seo_endpoint = self::$config->get( 'seo_endpoint' );
		}

		if ( $this->get_query_arg( 'inventory_id' ) ) {
			$this->single_id = $this->get_query_arg( 'inventory_id' );
		} else if ( $this->request( "inventory_id" ) ) {
			$this->single_id = $this->request( "inventory_id" );
		} else {
			global $wp_query;
			if ( isset( $wp_query->query_vars[ self::$seo_endpoint ] ) ) {
				$args = [
					'inventory_slug' => $wp_query->query_vars[ self::$seo_endpoint ]
				];
				$item = $this->item->get_all( $args );

				if ( $item ) {
					$this->single_id = $item[0]->inventory_id;
				}
			}
		}

		$this->is_single = ( $this->single_id ) ? TRUE : FALSE;

		if ( $this->is_single() ) {
			add_filter( 'body_class', function ( $classes ) {
				return array_merge( $classes, [ 'wpinventory-single' ] );
			} );
		}
	}

	/**
	 * Getter method.  Returns whether or not current loop is a single item.
	 * @return integer
	 */
	public function is_single() {
		return $this->is_single;
	}

	/**
	 * Getter method.  Returns the current single item id
	 * @return integer
	 */
	public function single_id() {
		return $this->single_id;
	}

	/**
	 * Get the current item index
	 * @return integer
	 */
	public function get_index() {
		return $this->pointer;
	}

	/**
	 * Get the count of items
	 * @return integer
	 */
	public function get_count() {
		return $this->count;
	}

	/**
	 * Get whether the current item is even / odd index
	 * @return string - 'odd' or 'even'
	 */
	public function get_even_or_odd() {
		return ( ( $this->pointer + 1 ) % 2 ) ? 'odd' : 'even';
	}
}
