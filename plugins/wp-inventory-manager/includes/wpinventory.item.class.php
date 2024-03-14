<?php

// No direct access allowed.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class for accessing inventory items
 * @author    - WP Inventory Manager
 * @package   - WPInventory
 * @copyright 2013 - WP Inventory Manager
 */
class WPIMItem extends WPIMDB {

	private static $instance;

	protected static $message;

	/**
	 * Constructor magic method.
	 */
	public function __construct() {
		parent::__construct();
	}

	/**
	 * Get an instance of the class
	 * @return object
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

	public function default_args() {
		return [
			'order'             => 'inventory_name',
			'page_size'         => WPIMCore::$config->get( 'page_size' ),  // Unlimited
			'page'              => 0,        // Beginning
			'name'              => '',        // Filter by name.  Will use %LIKE%
			'include_category'  => 1,        // Whether to retrieve the category name
			'inventory_status'  => '',        // Either "All", "Active", or an ID of the specific status
			'inventory_id'      => NULL,
			'inventory_slug'    => NULL,
			'category_id'       => NULL,
			'category_name'     => NULL,    // If used, do not use category_id
			'category_slug'     => NULL,    // If used, do not use category_id
			'user_id'           => NULL,
			'search'            => '',
			'hide_low'          => WPIMCore::$config->get( 'hide_low' ),
			'hide_low_quantity' => WPIMCore::$config->get( 'hide_low_quantity' )
		];
	}

	/**
	 * Get a listing of inventory items.
	 *
	 * @param array|string $args
	 * @param boolean      $get_counts - flag if we are just getting the count of items
	 * @param boolean      $bypass_parse_order
	 *
	 * @return array|int
	 */
	public function get_all( $args = NULL, $get_counts = FALSE, $bypass_parse_order = FALSE ) {
		$fields = [ 'i.*' ];
		$from   = "$this->inventory_table AS i";
		$where  = '';
		$limit  = '';

		$defaults = $this->default_args();

		if ( ! is_array( $args ) ) {
			parse_str( $args, $args );
		}

		$args = apply_filters( 'wpim_query_item_args', $args );
		$args = wp_parse_args( $args, $defaults );

		if ( $bypass_parse_order ) {
			$order = $args['order'];
			if ( is_array( $order ) ) {
				$order = implode( ' ', $order );
			}

			if ( ! empty( $args['dir'] ) ) {
				$order .= ' ' . $args['dir'];
			}
		} else {
			$order = $this->parse_sort( $args['order'], $this->get_fields() );
		}

		if ( FALSE !== stripos( $order, 'category' ) ) {
			$args['include_category'] = 1;
		}


		if ( ! empty( $args['product_id'] ) ) {
			$args['inventory_id'] = $args['product_id'];
		}

		if ( $args['inventory_slug'] ) {
			$where = $this->append_where( $where, $this->wpdb->prepare( ' i.inventory_slug = %s', $args['inventory_slug'] ) );
		}

		if ( ! empty( $args['inventory_id'] ) ) {
			if ( is_array( $args['inventory_id'] ) ) {
				$in    = array_fill( 0, count( $args['inventory_id'] ), '%d' );
				$where = $this->append_where( $where, $this->wpdb->prepare( ' i.inventory_id IN (' . implode( ',', $in ) . ')', $args['inventory_id'] ) );
			} else {
				$where = $this->append_where( $where, $this->wpdb->prepare( ' i.inventory_id = %d', $args['inventory_id'] ) );
			}
		}

		// only honor "search" related arguments if NOT a single product id
		if ( empty( $args['inventory_id'] ) && ! apply_filters( 'wpim_additional_criteria_with_inventory_id', FALSE ) ) {
			if ( $args['category_name'] && ! $args['category_id'] ) {
				$value = $args['category_name'];
				// Set the flag to include the category name information
				$args['include_category'] = 1;
				$value                    = self::parse_arg_separator( $value );

				if ( is_array( $value ) ) {
					$in    = array_fill( 0, count( $value ), '%s' );
					$in    = implode( ',', $in );
					$where = $this->append_where( $where, $this->wpdb->prepare( " c.category_name IN ({$in})", $value ) );
				} else {
					$where = $this->append_where( $where, $this->wpdb->prepare( " c.category_name = %s", $value ) );
				}
			}

			if ( $args['category_slug'] && ! $args['category_id'] ) {
				// Set the flag to include the category name information
				$args['include_category'] = 1;
				// Set the where to limit by category name (Exact match, case insensitive)
				$where = $this->append_where( $where, $this->wpdb->prepare( 'c.category_slug = %s', $args['category_slug'] ) );
			}

			if ( $args['category_id'] ) {
				if ( is_array( $args['category_id'] ) ) {
					$in    = array_fill( 0, count( $args['category_id'] ), '%d' );
					$where = $this->append_where( $where, $this->wpdb->prepare( ' i.category_id IN (' . implode( ',', $in ) . ')', $args['category_id'] ) );
				} else {
					$where = $this->append_where( $where, $this->wpdb->prepare( ' i.category_id = %d', $args['category_id'] ) );
				}
			}

			if ( ! empty( $args['user_id'] ) && (int) $args['user_id'] ) {
				if ( is_array( $args['user_id'] ) ) {
					$in    = array_fill( 0, count( $args['user_id'] ), '%d' );
					$where = $this->append_where( $where, $this->wpdb->prepare( ' i.user_id IN (' . implode( ',', $in ) . ')', $args['user_id'] ) );
				} else {
					$where = $this->append_where( $where, $this->wpdb->prepare( ' i.user_id = %d', $args['user_id'] ) );
				}
			}

			// Support for shortcode to filter on ANY available item field
			$potential_args = self::get_labels( TRUE );
			// these fields are handled elsewhere, specially
			$ignore_args = [
				'inventory_status',
				'category_id',
				'category_slug',
				'user_id',
				'inventory_id',
				'inventory_slug'
			];

			foreach ( $potential_args AS $arg_key => $arg_label ) {
				if ( in_array( $arg_key, $ignore_args ) ) {
					continue;
				}

				$arg_label_key = ( is_string( $arg_label ) ) ? $arg_label : $arg_label['label'];
				$arg_label_key = strtolower( str_replace( ' ', '_', $arg_label_key ) );
				if ( ! empty( $args[ $arg_key ] ) || ! empty( $args[ $arg_label_key ] ) ) {
					$value  = ( ! empty( $args[ $arg_key ] ) ) ? $args[ $arg_key ] : $args[ $arg_label_key ];
					$clause = $this->parse_where_value( $value, "i.{$arg_key}" );
					$where  = $this->append_where( $where, $clause );
				}
			}

			if ( (int) $args['page_size'] ) {
				$limit = ' LIMIT ';
				$limit .= ( (int) $args['page'] ) ? (int) ( $args['page'] * $args['page_size'] ) . ',' : '';
				$limit .= (int) $args['page_size'];
			}

			if ( $args['search'] ) {
				$args['include_category'] = TRUE;
				$where                    = $this->append_where( $where, $this->parse_search( $args['search'], TRUE ) );
			}
		}

		$bypass_custom_where = apply_filters( 'wpim_item_bypass_custom_where', ! empty( $args['inventory_id'] ) );
		if ( ! empty( $args['where'] ) && ! $bypass_custom_where ) {
			$args['where'] = $this->parse_custom_where( $args['where'], $args );
			if ( FALSE !== stripos( $args['where'], 'category' ) ) {
				$args['include_category'] = TRUE;
			}

			$where = $this->append_where( $where, $args['where'] );
		}

		if ( (int) $args['include_category'] ) {
			$from     .= ' LEFT JOIN ' . $this->category_table . ' AS c ON i.category_id = c.category_id ';
			$fields[] = 'c.category_name AS inventory_category';
		}

		$from     .= ' LEFT JOIN ' . $this->status_table . ' AS s ON i.inventory_status = s.status_id';
		$fields[] = 's.status_name AS status';

		$from     .= ' LEFT JOIN ' . $this->wpdb->users . ' AS u ON i.inventory_updated_by = u.ID';
		$fields[] = 'u.user_nicename AS updated_by_name';

		if ( ! is_admin() ) {
			if ( (int) $args['inventory_status'] && strtolower( $args['inventory_status'] ) !== 'all' ) {
				$where = $this->append_where( $where, ' i.inventory_status =' . (int) $args['inventory_status'] );
			} else if ( $args['inventory_status'] != 'all' || $args['inventory_status'] == 'active' ) {
				$where = $this->append_where( $where, 's.is_active = 1' );
			}

			if ( $args['hide_low'] ) {
				$where = $this->append_where( $where, 'i.inventory_quantity > ' . (int) $args['hide_low_quantity'] );
			}
		} else {
			if ( (int) $args['inventory_status'] && strtolower( $args['inventory_status'] ) !== 'all' ) {
				$where = $this->append_where( $where, 'i.inventory_status = ' . (int) $args['inventory_status'] );
			}
		}

		$query_parts = [
			'fields' => $fields,
			'from'   => $from,
			'where'  => $where,
			'order'  => $order,
			'limit'  => $limit
		];

		$query  = apply_filters( 'wpim_item_query', $query_parts, $args );
		$fields = $query['fields'];
		$from   = $query['from'];
		$where  = $query['where'];
		$order  = $query['order'];
		$limit  = $query['limit'];

		if ( $where ) {
			$where = ' WHERE ' . $where;
		}

		if ( $get_counts ) {
			$query = "SELECT count(*) FROM {$from} {$where}";

			return $this->wpdb->get_var( $query );
		}

		$fields_string = implode( ',', $fields );
		$order         = ( trim( $order ) ) ? ' ORDER BY ' . $order : ' ';

		$query = "SELECT {$fields_string} FROM {$from} {$where} {$order} {$limit}";

		self::debug_log( $query );

		$items = $this->parseFromDb( $this->wpdb->get_results( $query ) );

		return apply_filters( 'wpim_get_items', $items );
	}


	/**
	 * Get an array containing all of the inventory item fields in the database
	 * @return array
	 */
	public static function get_fields() {
		return [
			'inventory_id',
			'inventory_number',
			'inventory_name',
			'inventory_description',
			'inventory_size',
			'inventory_manufacturer',
			'inventory_make',
			'inventory_model',
			'inventory_year',
			'inventory_serial',
			'inventory_fob',
			'inventory_quantity',
			'inventory_quantity_reserved',
			'inventory_price',
			'inventory_status',
			'inventory_slug',
			'inventory_sort_order',
			'category_id',
			'user_id',
			'inventory_date_added',
			'inventory_date_updated',
			'inventory_updated_by'
		];
	}

	public static function get_searchable_fields( $include_additional = TRUE ) {
		$no_search = [
			'inventory_id',
			'inventory_slug',
			'inventory_sort_order',
			'category_id',
			'user_id',
			'inventory_date_added',
			'inventory_date_updated',
		];

		$fields = self::get_fields();

		if ( $include_additional ) {
			$additional_fields = [ 'c.category_name' ];
			$fields            = array_merge( $fields, $additional_fields );
		}


		foreach ( $fields AS $key => $field ) {
			if ( in_array( $field, $no_search ) ) {
				unset( $fields[ $key ] );
			}
		}

		return $fields;
	}

	public function parse_search( $search, $include_additional ) {
		$fields = $this->get_searchable_fields( $include_additional );
		$where  = '';

		foreach ( $fields AS $field ) {
			if ( $where ) {
				$where .= ' OR';
			}

			$field = explode( '.', $field );
			$field = implode( '`.`', $field );
			$where .= ' `' . $field . '` LIKE ' . $this->wpdb->prepare( "%s", '%' . $search . '%' ) . '';
		}

		$where = apply_filters( 'wpim_parse_search', $where, $search );

		return '(' . $where . ')';
	}

	/**
	 * Get specific inventory item
	 *
	 * @param integer $inventory_id
	 *
	 * @return object
	 */
	public function get( $inventory_id ) {
		if ( ! (int) $inventory_id ) {
			return NULL;
		}

		do_action( 'wpim_pre_get_item', $inventory_id );
		$item = $this->parseRowFromDb( $this->wpdb->get_row( $this->wpdb->prepare( 'SELECT * FROM ' . $this->inventory_table . ' WHERE inventory_id = %d', $inventory_id ) ) );

		$items = apply_filters( 'wpim_get_items', [ (object) $item ] );
		return reset( $items );
	}

	/**
	 * Get all of the images associated with an inventory item
	 *
	 * @param integer $inventory_id
	 *
	 * @return array
	 */
	public function get_images( $inventory_id ) {
		if ( ! $inventory_id ) {
			return [];
		}

		return $this->wpdb->get_results( $this->wpdb->prepare( 'SELECT * FROM ' . $this->image_table . ' WHERE inventory_id = %d ORDER BY image_sort_order', $inventory_id ) );
	}

	/**
	 * Get all of the media associated with an inventory item
	 *
	 * @param integer $inventory_id
	 *
	 * @return array
	 */
	public function get_media( $inventory_id ) {
		if ( ! $inventory_id ) {
			return [];
		}

		return $this->wpdb->get_results( $this->wpdb->prepare( 'SELECT * FROM ' . $this->media_table . ' WHERE inventory_id = %d ORDER BY media_sort_order', $inventory_id ) );
	}

	/**
	 * Save the inventory item
	 *
	 * @param array $data
	 *
	 * @return integer | boolean - inventory id on success, false on failure
	 */
	public function save( $data ) {
		$inventory_id     = '';
		$inventory_name   = '';
		$inventory_slug   = '';
		$inventory_price  = 0;
		$inventory_number = '';

		extract( $data );

		// Parsing price is challenging, depends on how they have decided to format for entry....
		// permit overriding this by filtering wpim_parse_price and returning falsey value
		if ( apply_filters( 'wpim_parse_price', TRUE, $inventory_price ) ) {
			$thousands_separator = self::$config->get( 'currency_thousands_separator' );
			$decimal_separator   = self::$config->get( 'currency_decimal_separator' );

			if ( '.' == $thousands_separator ) {
				$count = substr_count( $inventory_price, $thousands_separator );
				if ( '.' == $decimal_separator ) {
					$count --;
				}

				$inventory_price = str_replace( $thousands_separator, '', $inventory_price, $count );
			}

			if ( '.' !== $decimal_separator ) {
				$inventory_price = str_replace( $decimal_separator, '.', $inventory_price );
			}

			$inventory_price = preg_replace( '/[^0-9.]/', '', $inventory_price );
			// permit changing the parsed price if appropriate
			$inventory_price = apply_filters( 'wpim_parsed_price', $inventory_price, $inventory_id, $data );
		}

		$now             = $this->date_to_mysql( current_time( 'timestamp' ), TRUE );
		$current_user_id = get_current_user_id();

		$inventory_slug = $this->validate_slug( 'inventory', $inventory_slug, $inventory_name, $inventory_id );
		$query          = $this->wpdb->prepare( " " . $this->inventory_table . " SET
			inventory_number = %s,
			inventory_name = %s,
			inventory_description = %s,
			inventory_size = %s,
			inventory_manufacturer = %s,
			inventory_make = %s,
			inventory_model = %s,
			inventory_year = %s,
			inventory_serial = %s,
			inventory_fob = %s,
			inventory_quantity = %d,
			inventory_quantity_reserved = %d,
			inventory_price = %f,
			inventory_status = %d,
			inventory_slug = %s,
			inventory_sort_order = %d,	
			category_id = %d,
			inventory_date_updated = %s,
			inventory_updated_by = %d",
            $inventory_number, $inventory_name, $inventory_description, $inventory_size, $inventory_manufacturer, $inventory_make, $inventory_model, $inventory_year, $inventory_serial, $inventory_fob, $inventory_quantity, $inventory_quantity_reserved, $inventory_price, $inventory_status, $inventory_slug, $inventory_sort_order, $category_id, $now, $current_user_id );

		if ( $inventory_id ) {
			$query = 'UPDATE ' . $query . $this->wpdb->prepare( ' WHERE inventory_id=%d', $inventory_id );
		} else {
			$user_id = get_current_user_id();

			$query = 'INSERT INTO' . $query . $this->wpdb->prepare( ", user_id = %d,
				inventory_date_added = %s", $user_id, $now );
		}

		$this->wpdb->query( $query );

		if ( ! $inventory_id ) {
			$inventory_id = $this->wpdb->insert_id;
		}

		return ( ! $this->wpdb->last_error ) ? $inventory_id : FALSE;
	}

	/**
	 * Updates an item, permits partial data (does not require all item fields)
	 *
	 * @param int   $inventory_id
	 * @param array $data
	 *
	 * @return false|int
	 */
	public function update( $inventory_id, $data ) {
		return $this->wpdb->update( $this->inventory_table, $data, [ 'inventory_id' => $inventory_id ] );
	}

	public function reassign_user( $old_user_id, $new_user_id ) {
		if ( NULL === $new_user_id ) {
			$items = $this->get_all( [ 'user_id' => $old_user_id ] );

			foreach ( $items AS $item ) {
				$this->delete( $item->inventory_id );
			}
		}

		$this->wpdb->update( $this->inventory_table, [ 'user_id' => $new_user_id ], [ 'user_id' => $old_user_id ] );
		$this->wpdb->update( $this->inventory_table, [ 'inventory_updated_by' => $new_user_id ], [ 'inventory_updated_by' => $old_user_id ] );
	}

	/**
	 * Method to save a reserved item
	 *
	 * @param integer $inventory_id
	 * @param integer $quantity (always as a positive)
	 *
	 * @return FALSE|int
	 */
	public function save_reserve( int $inventory_id, int $quantity ) {
		$item              = new WPIMItem();
		$item              = $item->get( $inventory_id );
		$original_quantity = $item->inventory_quantity;

		$quantity = apply_filters( 'wpim_reserve_quantity', $quantity, $inventory_id );

		$query = $this->wpdb->prepare( 'UPDATE ' . $this->inventory_table . '
						SET inventory_quantity = (inventory_quantity - %d),
							 inventory_quantity_reserved = (inventory_quantity_reserved + %d) WHERE inventory_id = %d', $quantity, $quantity, $inventory_id );

		do_action( 'wpim_save_reserve', $inventory_id, $quantity, $original_quantity );

		return $this->wpdb->query( $query );
	}

	/**
	 * Save the images associated with a given inventory item
	 *
	 * @param integer $inventory_id - id of the relevant inventory item
	 * @param array   $images       - array of image urls
	 * @param array   $sort         - array of indexes for sort order
	 */
	public function save_images( int $inventory_id, $images, $sort = [] ) {
		$thumbnail = '';
		$medium    = '';
		$large     = '';

		// Ensure sort array has enough entries
		if ( ! $sort ) {
			$count = 0;
			foreach ( $images AS $i ) {
				$sort[] = $count ++;
			}
		}

		// Remove all existing images
		$this->delete_images( $inventory_id );

		// Initialize the sort order
		$sort_order = 0;

		// Iterate through the images in the relevant sort order
		foreach ( $sort AS $i ) {

			// Due to the way the sort array comes over, not all items may be filled
			if ( empty( $images[ $i ] ) ) {
				continue;
			}

			$image = $images[ $i ];

			// Images can be id's as well...
			if ( ! is_numeric( $image ) ) {
				// Get the attachment id
				$post_id = $this->get_attachment_id_from_url( $image );
			} else {
				$post_id = (int) $image;
				$image   = wp_get_attachment_url( $post_id );
			}


			if ( $post_id ) {
				// Now - get large size, medium, plus thumbnail
				extract( $this->get_image_urls( $post_id ) );
			} else {
				$thumbnail = $image;
				$medium    = $image;
				$large     = $image;
			}

			$query = $this->wpdb->prepare( 'INSERT INTO ' . $this->image_table . '
					SET inventory_id = %d,
					post_id = %d,
					image = %s,
					thumbnail = %s,
					`medium` = %s,
					large = %s,
					image_sort_order = %d', $inventory_id, $post_id, $image, $thumbnail, $medium, $large, $sort_order ++ );

			$this->wpdb->query( $query );

			if ( $this->get_error() ) {
				// TODO: Do something with the error.  Currently, this is not used
//				$this->error = $this->get_error();
			}
		}
	}

	/**
	 *
	 * Save the media associated with a given inventory item
	 *
	 * @param int   $inventory_id - id of the relevant inventory item
	 * @param array $media        - array of media urls
	 * @param array $media_title  - title of the media items
	 * @param array $sort         - array of indexes for sort order
	 */
	public function save_media( int $inventory_id, $media, $media_title, $sort = [] ) {

		// Ensure sort array has enogh entries
		if ( ! $sort ) {
			$count = 0;
			foreach ( $media AS $i ) {
				$sort[] = $count ++;
			}
		}

		// Remove all existing media
		$this->delete_media( $inventory_id );

		// Initialize the sort order
		$sort_order = 0;

		// Iterate through the images in the relevant sort order
		foreach ( $sort AS $i ) {

			// Due to the way the sort array comes over, not all items may be filled
			if ( empty( $media[ $i ] ) ) {
				continue;
			}

			$url   = $media[ $i ];
			$title = $media_title[ $i ];

			if ( ! $title ) {
				$title = $url;
			}

			$query = $this->wpdb->prepare( 'INSERT INTO ' . $this->media_table . '
					SET inventory_id = %d,
					media_title = %s,
					media = %s,
					media_sort_order = %d', $inventory_id, $title, $url, $sort_order ++ );

			$this->wpdb->query( $query );
		}
	}

	/**
	 * Delete an inventory item
	 *
	 * @param integer $inventory_id
	 *
	 * @return int
	 */
	function delete( int $inventory_id ) {
		// Remove item

		do_action( 'wpim_pre_delete_item', $inventory_id );

		$success = $this->wpdb->query( $this->wpdb->prepare( "DELETE FROM " . $this->inventory_table . ' WHERE inventory_id = %d', $inventory_id . ' LIMIT 1' ) );

		if ( $success ) {
			$images_success = $this->delete_images( $inventory_id );
			$media_success  = $this->delete_media( $inventory_id );

			if ( ! $images_success ) {
				self::$message = $this->__( 'Inventory item deleted, but the images could not be deleted.' ) . '<br />';
			}

			if ( ! $media_success ) {
				self::$message = $this->__( 'Inventory item deleted, but the media could not be deleted.' ) . '<br />';
			}
		} else {
			if ( $success === 0 ) {
				self::$message = $this->__( 'The inventory item is already deleted.' );
			} else {
				self::$message = $this->__( 'Inventory item could not be deleted.' );
			}
		}

		do_action( 'wpim_post_delete_item', $inventory_id );

		return $success;
	}

	/**
	 * Delete all image for an inventory item
	 *
	 * @param integer $inventory_id
	 *
	 * @return int
	 */
	function delete_images( int $inventory_id ) {
		// Remove all existing media
		return $this->wpdb->query( $this->wpdb->prepare( "DELETE FROM " . $this->image_table . '  WHERE inventory_id = %d', $inventory_id ) );
	}

	/**
	 * Delete all media for an inventory item
	 *
	 * @param int $inventory_id
	 *
	 * @return FALSE|int
	 */
	function delete_media( int $inventory_id ) {
		// Remove all existing media
		return $this->wpdb->query( $this->wpdb->prepare( "DELETE FROM " . $this->media_table . '  WHERE inventory_id = %d', $inventory_id ) );
	}

	/**
	 * Retrieve the various sizes (large, medium, thumbnail) in one convenient location
	 *
	 * @param integer - $post_id
	 *
	 * @return array of image urls
	 */
	public function get_image_urls( $post_id = NULL, $resize = FALSE ) {

		$thumbnail = wp_get_attachment_image_src( $post_id, 'thumbnail' );
		$thumbnail = ( ! empty( $thumbnail[0] ) ) ? $thumbnail[0] : NULL;

		$medium = wp_get_attachment_image_src( $post_id, 'medium' );
		$medium = ( ! empty( $medium[0] ) ) ? $medium[0] : NULL;

		$large = wp_get_attachment_image_src( $post_id, 'large' );
		$large = ( ! empty( $large[0] ) ) ? $large[0] : NULL;

		if ( $resize ) {
			// Get upload dir info for file paths
			$upload_dir = wp_upload_dir();
			$upload_url = $upload_dir['baseurl'];
			$upload_dir = $upload_dir['basedir'];

			// Use the full file as the base for thumb generation
			$full      = wp_get_attachment_image_src( $post_id, 'full' );
			$full      = ( ! empty( $full[0] ) ) ? $full[0] : NULL;
			$full_file = str_replace( $upload_url, $upload_dir, $full );

			// Generate thumbs
			$results = wp_generate_attachment_metadata( $post_id, $full_file );

			// If success, then overload the variables
			if ( ! empty( $results['sizes'] ) ) {
				$base_url  = substr( $full, 0, strripos( $full, '/' ) + 1 );
				$results   = $results['sizes'];
				$large     = ( ! empty( $results['large'] ) ) ? $base_url . $results['large']['file'] : $full;
				$medium    = ( ! empty( $results['medium'] ) ) ? $base_url . $results['medium']['file'] : $full;
				$thumbnail = ( ! empty( $results['thumbnail'] ) ) ? $base_url . $results['thumbnail']['file'] : $full;
			}
		}

		return [
			'large'     => $large,
			'medium'    => $medium,
			'thumbnail' => $thumbnail
		];
	}

	/**
	 * Function to retrieve the post id based on the image url
	 *
	 * @param string $attachment_url
	 *
	 * @return int $post_id - post id that the image belongs to
	 */
	public function get_attachment_id_from_url( $attachment_url = NULL ) {

		$attachment_id = NULL;

		// If there is no url, return.
		if ( ! $attachment_url ) {
			return 0;
		}

		// Get the upload directory paths
		$upload_dir_paths = wp_upload_dir();

		// Make sure the upload path base directory exists in the attachment URL, to verify that we're working with a media library image
		if ( strpos( $attachment_url, $upload_dir_paths['baseurl'] ) !== FALSE ) {

			// If this is the URL of an auto-generated thumbnail, get the URL of the original image
			$attachment_url = preg_replace( '/-\d+x\d+(?=\.(jpg|jpeg|png|gif)$)/i', '', $attachment_url );

			// Remove the upload path base directory from the attachment URL
			$attachment_url = str_replace( $upload_dir_paths['baseurl'] . '/', '', $attachment_url );

			// Finally, run a custom database query to get the attachment ID from the modified attachment URL
			$attachment_id = $this->wpdb->get_var( $this->wpdb->prepare( "SELECT p.ID
					FROM " . $this->wpdb->posts . " AS p
					INNER JOIN " . $this->wpdb->postmeta . " AS pm ON p.ID = pm.post_id 
					WHERE pm.meta_key = '_wp_attached_file' 
						AND pm.meta_value = '%s' 
						AND p.post_type = 'attachment'", $attachment_url ) );

		}

		return $attachment_id;
	}

	/**
	 * Utility function to regenerate thumbnails in the event the user has changed media sizes
	 * @returns integer $count - count of images rebuilt
	 */
	public function rebuild_image_thumbs() {

		$images = $this->wpdb->get_results( "SELECT * FROM " . $this->image_table );
		$count  = 0;

		foreach ( $images as $image ) {
			extract( $this->get_image_urls( $image->post_id, TRUE ) );
			$query = $this->wpdb->prepare( "UPDATE " . $this->image_table . "
					SET thumbnail=%s, medium = %s, large=%s WHERE image_id=%d", $thumbnail, $medium, $large, $image->image_id );
			$count += $this->wpdb->query( $query );
		}

		return $count;
	}

	public function get_message() {
		return $this->message;
	}
}
