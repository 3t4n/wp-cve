<?php
/**
 * @package CTXFeed\V5\Helper
 */

namespace CTXFeed\V5\Helper;

use CTXFeed\V5\Common\Helper;
use CTXFeed\V5\FTP\FtpClient;
use CTXFeed\V5\FTP\FtpException;
use CTXFeed\V5\Product\AttributeValueByType;
use CTXFeed\V5\Query\QueryFactory;
use CTXFeed\V5\Template\TemplateFactory;
use CTXFeed\V5\Utility\Cache;
use CTXFeed\V5\Utility\Config;
use CTXFeed\V5\Utility\FileSystem;
use WP_Error;

/**
 * This class contains feed generated method
 */
class FeedHelper {

	/**
	 * Sanitizes form fields recursively using WordPress standards.
	 *
	 * @param array $data Data associated with form fields to be sanitized.
	 *
	 * @return array Sanitized form data.
	 */
	public static function sanitize_form_fields( $data ) {
		foreach ( $data as $k => $v ) {
			if ( true === apply_filters( 'woo_feed_sanitize_form_fields', true, $k, $v, $data ) ) {
				if ( is_array( $v ) ) {
					$v = self::sanitize_form_fields( $v );
				} else {
					// $v = sanitize_text_field( $v ); #TODO should not trim Prefix and Suffix field
				}
			}
			$data[ $k ] = apply_filters( 'woo_feed_sanitize_form_field', $v, $k );
		}

		return $data;
	}

	/**
	 * Generates a unique filename for a feed, ensuring no conflicts in the feed directory.
	 *
	 * @param string $file_name The initial filename.
	 * @param string $type The type of the feed (e.g., 'xml', 'csv').
	 * @param string $provider The provider for which the feed is being generated.
	 *
	 * @return string|false The unique filename, or false if an error occurs.
	 */
	public static function generate_unique_feed_file_name( $file_name, $type, $provider ) {
		if ( ! \is_string( $file_name ) || ! \is_string( $type ) || ! \is_string( $provider ) ) {
			// Handle invalid input types.
			return false;
		}

		$feed_dir = Helper::get_file_dir( $provider, $type );

		$raw_filename  = \sanitize_title( $file_name, '', 'save' );
		$raw_filename  = self::unique_feed_slug( $raw_filename, 'wf_feed_' );
		$raw_filename  = \sanitize_file_name( $raw_filename . '.' . $type );
		$raw_filename  = \wp_unique_filename( $feed_dir, $raw_filename );
		$base_filename = \str_replace( '.' . $type, '', $raw_filename );

		return \is_numeric( $base_filename ) ? false : $base_filename;
	}

	/**
	 * Generates a unique slug for a feed by checking against existing database entries.
	 * This function delegates to CommonHelper::unique_option_name for actual uniqueness check.
	 * Use generate_unique_feed_file_name() for a complete unique file name generation.
	 *
	 * @param string $slug The initial slug for the feed.
	 * @param string $prefix An optional prefix to prepend to the slug.
	 * @param int $option_id An optional ID to exclude a specific option from the uniqueness check.
	 *
	 * @return string Unique slug for the feed.
	 * @see CommonHelper::unique_option_name()
	 */
	public static function unique_feed_slug( $slug, $prefix = '', $option_id = null ) {
		return CommonHelper::unique_option_name( $slug, $prefix, $option_id );

	}

	/**
	 * Sanitizes and saves feed configuration data to the WordPress options table.
	 *
	 * @param array $feed_rules Data to be saved. Should be an associative array of feed rules.
	 * @param string|null $feed_option_name Optional. The name of the feed option. If null, a name is auto-generated.
	 * @param bool $configOnly Optional. Whether to save only 'wf_config' or both 'wf_config' and 'wf_feed_'. Defaults to true.
	 *
	 * @return bool|string False on failure, or the feed option name on success.
	 */
	public static function save_feed_config_data( $feed_rules, $feed_option_name = null, $configOnly = true ) {
		if ( ! \is_array( $feed_rules ) ) {
			// Handle invalid input
			return false;
		}

		$prepared_feed_rules = self::prepare_feed_rules_to_save( $feed_rules, $feed_option_name );
		if ( ! $prepared_feed_rules ) {
			// Handle failure in preparing feed rules
			return false;
		}

		$feed_option_name = $prepared_feed_rules['feed_option_name'];
		$is_update        = $prepared_feed_rules['is_update'];

		self::call_action_before_update_feed_config( $is_update, $feed_rules, $feed_option_name );

		$updated = update_option( $feed_option_name, $prepared_feed_rules['feedrules_to_save'], false );

		self::call_action_after_update_feed_config( $is_update, $feed_rules, $feed_option_name );

		// Return feed option name on success or false if update failed
		return $updated ? $feed_option_name : false;
	}


	/**
	 * Prepares feed rules for saving to the database, ensuring data integrity and sanitization.
	 *
	 * @param array $feed_rules Data to be saved, expected to contain 'filename', 'feedType', 'provider'.
	 * @param mixed $feed_option_name Optional. Feed name, auto-generated if null or empty.
	 *
	 * @return array|false Returns prepared data for saving or false if input is invalid.
	 */
	public static function prepare_feed_rules_to_save( $feed_rules, $feed_option_name ) {
		if ( ! \is_array( $feed_rules ) || ! self::validate_feed_rules( $feed_rules ) ) {
			return false;
		}

		$feed_rules = self::remove_unnecessary_fields( $feed_rules );

		$feed_rules = self::sanitize_form_fields( $feed_rules );

		// Handle feed option name generation or retrieval.
		list( $feed_option_name, $old_feed, $update, $status ) = self::handle_feed_option_name( $feed_rules, $feed_option_name );


		$feed_url = self::get_file_url( $feed_option_name, $feed_rules['provider'], $feed_rules['feedType'] );

		$feed_rules = apply_filters( 'woo_feed_insert_feed_data', $feed_rules, $old_feed, $feed_option_name );

		$feed_rulesToSave = [
			'feedrules'    => $feed_rules,
			'url'          => $feed_url,
			'last_updated' => \current_time( 'mysql' ),
			'status'       => $status,
		];

		return [
			'feedrules_to_save' => $feed_rulesToSave,
			'is_update'         => $update,
			'old_data'          => $old_feed,
			'feed_option_name'  => $feed_option_name,
		];
	}

	/**
	 * Validates the required keys in feed rules.
	 *
	 * @param array $feed_rules
	 *
	 * @return bool
	 */
	private static function validate_feed_rules( $feed_rules ) {
		return isset( $feed_rules['filename'], $feed_rules['feedType'], $feed_rules['provider'] );
	}

	/**
	 * Removes unnecessary fields from feed rules.
	 *
	 * @param array $feed_rules
	 *
	 * @return array
	 */
	private static function remove_unnecessary_fields( $feed_rules ) {
		// Define fields to remove
		$removables = array( 'closedpostboxesnonce', '_wpnonce', '_wp_http_referer', 'save_feed_config', 'edit-feed' );
		foreach ( $removables as $removable ) {
			unset( $feed_rules[ $removable ] );
		}

		return $feed_rules;
	}

	/**
	 * Handles the generation or retrieval of feed option name.
	 *
	 * @param array $feed_rules
	 * @param mixed $feed_option_name
	 *
	 * @return array
	 */
	private static function handle_feed_option_name( $feed_rules, $feed_option_name ) {
		if ( empty( $feed_option_name ) ) {
			$feed_option_name = AttributeValueByType::FEED_RULES_OPTION_PREFIX . self::generate_unique_feed_file_name(
					$feed_rules['filename'],
					$feed_rules['feedType'],
					$feed_rules['provider']
				);

			return [ $feed_option_name, array(), false, 1 ];
		} else {
			$old_feed = maybe_unserialize( get_option( $feed_option_name, [] ) );
			$status   = isset( $old_feed['status'] ) && 1 === (int) $old_feed['status'] ? 1 : 0;

			return [ $feed_option_name, $old_feed, true, $status ];
		}
	}


	/**
	 * @param $update
	 * @param $feed_rules
	 * @param $feed_option_name
	 *
	 * @return void
	 */
	public static function call_action_before_update_feed_config( $update, $feed_rules, $feed_option_name ) {
		if ( $update ) {
			/**
			 * Before Updating Config to db
			 *
			 * @param array $feed_rules An array of sanitized config
			 * @param string $feed_option_name Option name
			 */
			do_action( 'woo_feed_before_update_config', $feed_rules, $feed_option_name );
		} else {
			/**
			 * Before inserting Config to db
			 *
			 * @param array $feed_rules An array of sanitized config
			 * @param string $feed_option_name Option name
			 */
			do_action( 'woo_feed_before_insert_config', $feed_rules, $feed_option_name );
		}
	}

	/**
	 * @param $update
	 * @param $feed_rules
	 * @param $feed_option_name
	 *
	 * @return void
	 */
	public static function call_action_after_update_feed_config( $update, $feed_rules, $feed_option_name ) {
		if ( $update ) {
			/**
			 * After Updating Config to db
			 *
			 * @param array $feed_rules An array of sanitized config
			 * @param string $feed_option_name Option name
			 */
			do_action( 'woo_feed_after_update_config', $feed_rules, $feed_option_name );
		} else {
			/**
			 * After inserting Config to db
			 *
			 * @param array $feed_rules An array of sanitized config
			 * @param string $feed_option_name Option name
			 */
			do_action( 'woo_feed_after_insert_config', $feed_rules, $feed_option_name );
		}
	}

	/**
	 * @param $rules
	 * @param $context
	 *
	 * @return mixed|null
	 */
	private static function parse_feed_rules( $rules = array(), $context = 'view' ) {

		if ( empty( $rules ) ) {
			$rules = array();
		}

		if ( Helper::is_pro() ) {
			$defaults = Config::pro_default_feed_rules();
		} else {
			$defaults = Config::free_default_feed_rules();
		}

		$rules                = wp_parse_args( $rules, $defaults );
		$rules['filter_mode'] = wp_parse_args(
			$rules['filter_mode'],
			array(
				'product_ids' => 'include',
				'categories'  => 'include',
				'post_status' => 'include',
			)
		);

		$rules['campaign_parameters'] = wp_parse_args(
			$rules['campaign_parameters'],
			array(
				'utm_source'   => '',
				'utm_medium'   => '',
				'utm_campaign' => '',
				'utm_term'     => '',
				'utm_content'  => '',
			)
		);

		if ( ! empty( $rules['provider'] ) && is_string( $rules['provider'] ) ) {
			/**
			 * filter parsed rules for provider
			 *
			 * @param array $rules
			 * @param string $context
			 *
			 * @since 3.3.7
			 *
			 */
			$rules = apply_filters( "woo_feed_{$rules['provider']}_parsed_rules", $rules, $context );
		}

		/**
		 * filter parsed rules
		 *
		 * @param array $rules
		 * @param string $context
		 *
		 * @since 3.3.7 $provider parameter removed
		 *
		 */
		return apply_filters( 'woo_feed_parsed_rules', $rules, $context );
	}

	/**
	 * Get pro version feed default rules.
	 *
	 * @param $rules
	 *
	 * @return mixed|null
	 */
	private static function free_default_feed_rules( $rules = [] ) {
		$defaults = array(
			'provider'            => '',
			'filename'            => '',
			'feedType'            => '',
			'feed_country'        => '',
			'ftpenabled'          => 0,
			'ftporsftp'           => 'ftp',
			'ftphost'             => '',
			'ftpport'             => '21',
			'ftpuser'             => '',
			'ftppassword'         => '',
			'ftppath'             => '',
			'ftpmode'             => 'active',
			'is_variations'       => 'y',
			'variable_price'      => 'first',
			'variable_quantity'   => 'first',
			'feedLanguage'        => apply_filters( 'wpml_current_language', null ),
			'feedCurrency'        => get_woocommerce_currency(),
			'itemsWrapper'        => 'products',
			'itemWrapper'         => 'product',
			'delimiter'           => ',',
			'enclosure'           => 'double',
			'extraHeader'         => '',
			'vendors'             => array(),
			// Feed Config
			'mattributes'         => array(), // merchant attributes
			'prefix'              => array(), // prefixes
			'type'                => array(), // value (attribute) types
			'attributes'          => array(), // product attribute mappings
			'default'             => array(), // default values (patterns) if value type set to pattern
			'suffix'              => array(), // suffixes
			'output_type'         => array(), // output type (output filter)
			'limit'               => array(), // limit or command
			// filters tab
			'composite_price'     => '',
			'shipping_country'    => '',
			'tax_country'         => '',
			'product_ids'         => '',
			'categories'          => array(),
			'post_status'         => array( 'publish' ),
			'filter_mode'         => array(),
			'campaign_parameters' => array(),

			'ptitle_show'        => '',
			'decimal_separator'  => wc_get_price_decimal_separator(),
			'thousand_separator' => wc_get_price_thousand_separator(),
			'decimals'           => wc_get_price_decimals(),
		);
		$rules    = wp_parse_args( $rules, $defaults );

		return apply_filters( 'woo_feed_free_default_feed_rules', $rules );
	}

	/**
	 * Get pro version feed default rules.
	 *
	 * @param $rules
	 *
	 * @return mixed|null
	 */
	private static function pro_default_feed_rules( $rules = [] ) {
		$defaults = array(
			'provider'              => '',
			'feed_country'          => '',
			'filename'              => '',
			'feedType'              => '',
			'ftpenabled'            => 0,
			'ftporsftp'             => 'ftp',
			'ftphost'               => '',
			'ftpport'               => '21',
			'ftpuser'               => '',
			'ftppassword'           => '',
			'ftppath'               => '',
			'ftpmode'               => 'active',
			'is_variations'         => 'y', // Only Variations (All Variations)
			'variable_price'        => 'first',
			'variable_quantity'     => 'first',
			'feedLanguage'          => apply_filters( 'wpml_current_language', null ),
			'feedCurrency'          => get_woocommerce_currency(),
			'itemsWrapper'          => 'products',
			'itemWrapper'           => 'product',
			'delimiter'             => ',',
			'enclosure'             => 'double',
			'extraHeader'           => '',
			'vendors'               => array(),
			// Feed Config
			'mattributes'           => array(), // merchant attributes
			'prefix'                => array(), // prefixes
			'type'                  => array(), // value (attribute) types
			'attributes'            => array(), // product attribute mappings
			'default'               => array(), // default values (patterns) if value type set to pattern
			'suffix'                => array(), // suffixes
			'output_type'           => array(), // output type (output filter)
			'limit'                 => array(), // limit or command
			// filters tab
			'composite_price'       => 'all_product_price',
			'product_ids'           => '',
			'categories'            => array(),
			'post_status'           => array( 'publish' ),
			'filter_mode'           => array(),
			'campaign_parameters'   => array(),
			'is_outOfStock'         => 'n',
			'is_backorder'          => 'n',
			'is_emptyDescription'   => 'n',
			'is_emptyImage'         => 'n',
			'is_emptyPrice'         => 'n',
			'product_visibility'    => 0,
			// include hidden ? 1 yes 0 no
			'outofstock_visibility' => 0,
			// override wc global option for out-of-stock product hidden from catalog? 1 yes 0 no
			'ptitle_show'           => '',
			'decimal_separator'     => wc_get_price_decimal_separator(),
			'thousand_separator'    => wc_get_price_thousand_separator(),
			'decimals'              => wc_get_price_decimals(),
		);
		$rules    = wp_parse_args( $rules, $defaults );

		return apply_filters( 'woo_feed_pro_default_feed_rules', $rules );
	}


	/**
	 * @param $item
	 * @param $request
	 *
	 * @return void|\WP_Error|\WP_REST_Response
	 */
	public static function prepare_item_for_response( $item ) {

		if ( isset( $item['option_value'] ) ) {
			$item['option_value'] = maybe_unserialize( maybe_unserialize( $item['option_value'] ) );

			return $item;
		} else {
			$item['option_value'] = maybe_unserialize( get_option( $item['option_name'] ) );
		}

		if ( ! isset( $item['option_value']['url'] ) ) {
			$item['option_value']['url'] = Helper::get_file_url( $item['option_name'], $item['option_value']['feedrules']['provider'], $item['option_value']['feedrules']['feedType'] );
		}

		if ( ! isset( $item['option_value']['status'] ) ) {
			$item['option_value']['status'] = false;
		}

		return $item;
	}

	/**
	 * @param $feed_lists
	 * @param $status
	 *
	 * @return array
	 */
	public static function prepare_all_feeds( $feed_lists, $status ) {
		$lists = [];

		foreach ( $feed_lists as $feed ) {
			$item = self::prepare_item_for_response( $feed );
			if ( $status ) {
				if ( \is_object( $item['option_value'] ) ) {
					$lists[] = $item;
					continue;
				}
				if ( 'active' === $status && 1 === $item['option_value']['status'] ) {
					$lists[] = $item;
				}
				if ( 'inactive' === $status && 0 === $item['option_value']['status'] ) {
					$lists[] = $item;
				}
			} else {
				$lists[] = $item;
			}
		}

		return $lists;
	}

	/**
	 * Removes predefined prefixes from a feed option name and returns the resulting slug.
	 *
	 * @param string $feed The feed option name from which to remove prefixes.
	 *
	 * @return string The slug derived from the feed option name after removing specific prefixes.
	 */
	public static function get_feed_option_name( $feed ) {
		if ( ! \is_string( $feed ) ) {
			// Handle invalid input.
			return '';
		}

		// Define the prefixes to be removed. Consider making these configurable if necessary.
		$prefixes_to_remove = [ 'wf_feed_', 'wf_config' ];

		return \str_replace( $prefixes_to_remove, '', $feed );
	}


	/**
	 * Get Schedule Intervals
	 * @return mixed
	 */
	public static function get_schedule_interval_options() {
		if ( Helper::is_pro() ) {
			$interval_options = array(
				WEEK_IN_SECONDS        => esc_html__( '1 Week', 'woo-feed' ),
				DAY_IN_SECONDS         => esc_html__( '24 Hours', 'woo-feed' ),
				12 * HOUR_IN_SECONDS   => esc_html__( '12 Hours', 'woo-feed' ),
				6 * HOUR_IN_SECONDS    => esc_html__( '6 Hours', 'woo-feed' ),
				HOUR_IN_SECONDS        => esc_html__( '1 Hour', 'woo-feed' ),
				30 * MINUTE_IN_SECONDS => esc_html__( '30 Minutes', 'woo-feed' ),
				15 * MINUTE_IN_SECONDS => esc_html__( '15 Minutes', 'woo-feed' ),
				5 * MINUTE_IN_SECONDS  => esc_html__( '5 Minutes', 'woo-feed' )
			);
		} else {
			$interval_options = array(
				WEEK_IN_SECONDS      => esc_html__( '1 Week', 'woo-feed' ),
				DAY_IN_SECONDS       => esc_html__( '24 Hours', 'woo-feed' ),
				12 * HOUR_IN_SECONDS => esc_html__( '12 Hours', 'woo-feed' ),
				6 * HOUR_IN_SECONDS  => esc_html__( '6 Hours', 'woo-feed' ),
				HOUR_IN_SECONDS      => esc_html__( '1 Hour', 'woo-feed' ),
			);
		}

		return apply_filters(
			'woo_feed_schedule_interval_options', $interval_options
		);
	}

	/**
	 * @return false|float|int|string
	 */
	public static function get_minimum_interval_option() {
		$intervals = \array_keys( self::get_schedule_interval_options() );
		if ( ! empty( $intervals ) ) {
			return \end( $intervals );
		}

		return 15 * MINUTE_IN_SECONDS;
	}

	/**
	 * Get Merchant list that are allowed on Custom2 Template
	 * @return array
	 */
	public static function get_custom2_merchant() {
		return array( 'custom2', 'admarkt', 'yandex_xml', 'glami' );
	}

	/**
	 * Get Feed File URL
	 *
	 * @param string $file_name
	 * @param string $provider
	 * @param string $type
	 *
	 * @return string
	 */
	public static function get_file_url( $file_name, $provider, $type ) {
		$file_name  = Helper::extract_feed_option_name( $file_name );
		$upload_dir = wp_get_upload_dir();

		return esc_url(
			\sprintf(
				'%s/woo-feed/%s/%s/%s.%s',
				$upload_dir['baseurl'],
				$provider,
				$type,
				$file_name,
				$type
			)
		);
	}

	/**
	 * Removes temporary feed files based on the given configuration and file name.
	 *
	 * @param array $config Feed configuration data.
	 * @param string $file_name The name of the feed file.
	 * @param bool $auto Flag indicating whether the process is automatic.
	 *
	 * @return void
	 */
	public static function unlink_temporary_files( $config, $file_name, $auto = false ) {
		if ( ! \is_array( $config ) || ! \is_string( $file_name ) ) {
			// Handle invalid input.
			return;
		}

		$type = $config['feedType'];
		$ext  = self::get_file_type( $type );
		$path = Helper::get_file_dir( $config['provider'], $type );

		$temp_feed_body_prefix = self::get_feed_body_temp_prefix( $auto );

		$files = [
			'headerFile' => $path . '/' . AttributeValueByType::FEED_TEMP_HEADER_PREFIX . $file_name . '.' . $ext,
			'bodyFile'   => $path . '/' . $temp_feed_body_prefix . $file_name . '.' . $ext,
			'footerFile' => $path . '/' . AttributeValueByType::FEED_TEMP_FOOTER_PREFIX . $file_name . '.' . $ext,
		];

		foreach ( $files as $file ) {
			if ( \file_exists( $file ) ) {
				\unlink( $file ); // Consider adding error handling here.
			}
		}
	}

	/**
	 * Saves a batch chunk of feed information to a file.
	 *
	 * @param string $feed_service Merchant service.
	 * @param string $type File type (extension).
	 * @param string|array $string Data to be saved.
	 * @param string $file_name File name.
	 * @param array $info Feed configuration.
	 *
	 * @return bool True on successful save, false otherwise.
	 */
	public static function save_batch_feed_info( $feed_service, $type, $string, $file_name, $info ) {
		if ( ! \is_string( $feed_service ) || ! \is_string( $type ) || ! ( \is_string( $string ) || \is_array( $string ) ) || ! \is_string( $file_name ) ) {
			// Handle invalid input.
			return false;
		}

		$ext = self::get_file_type( $type );
		if ( 'json' === $ext ) {
			$string = \wp_json_encode( $string );
		}

		$path   = Helper::get_file_dir( $feed_service, $type );
		$file   = $path . '/' . $file_name . '.' . $ext;
		$status = FileSystem::saveFile( $path, $file, $string );

		if ( Helper::is_debugging_enabled() ) {
			$message = $status ? \sprintf( 'Batch chunk file (%s) saved.', $file_name ) :
				\sprintf( 'Unable to save batch chunk file %s.', $file_name );
			woo_feed_log_feed_process( $info['filename'], $message );
		}

		return $status;
	}

	/**
	 * Retrieves batch feed information from a file.
	 *
	 * @param string $feed_service The feed service.
	 * @param string $type The file type.
	 * @param string $file_name The file name.
	 *
	 * @return bool|array|string  False if file does not exist or data is not readable, array or JSON string otherwise.
	 */
	public static function get_batch_feed_info( $feed_service, $type, $file_name ) {
		if ( ! \is_string( $feed_service ) || ! \is_string( $type ) || ! \is_string( $file_name ) ) {
			// Handle invalid input.
			return false;
		}

		$ext  = self::get_file_type( $type );
		$path = Helper::get_file_dir( $feed_service, $type );
		$file = $path . '/' . $file_name . '.' . $ext;

		if ( ! \file_exists( $file ) ) {
			return false;
		}

		$data = \file_get_contents( $file ); // Consider adding error handling here.
		if ( false === $data ) {
			return false;
		}

		return 'json' === $ext ? \json_decode( $data, true ) : $data;
	}

	/**
	 * Determines the appropriate file extension type for the given file type.
	 *
	 * @param string $type The file type (e.g., 'csv', 'json').
	 *
	 * @return string The determined file extension type, defaults to 'json' for certain types.
	 */
	public static function get_file_type( $type ) {
		if ( ! \is_string( $type ) ) {
			// Handle non-string type.
			return '';
		}

		$json_types = array( 'csv', 'tsv', 'xls', 'xlsx', 'json' );

		return \in_array( $type, $json_types ) ? 'json' : $type;
	}

	/**
	 * Determines if the content of a given file type should be JSON decoded.
	 *
	 * @param string $type The file type (e.g., 'csv', 'json').
	 *
	 * @return bool True if the content should be JSON decoded, false otherwise.
	 */
	public static function should_json_decode( $type ) {
		if ( ! \is_string( $type ) ) {
			// Handle non-string type.
			return false;
		}

		$json_decodable_types = array( 'csv', 'tsv', 'xls', 'xlsx', 'json' );

		return \in_array( $type, $json_decodable_types );
	}


	/**
	 * @param $file_ext_type
	 *
	 * @return bool
	 */
	public static function should_create_footer( $file_ext_type ) {
		return 'xml' == $file_ext_type;
	}

	/**
	 * @param $value
	 *
	 * @return bool
	 */
	public static function is_attribute_price_type( $value ) {
		return \in_array( $value, [
			'price',
			'current_price',
			'sale_price',
			'price_with_tax',
			'current_price_with_tax',
			'sale_price_with_tax'
		] );
	}

	/**
	 * @return string[]
	 */
	public static function get_special_templates() {
		return array(
			'custom2',
			'admarkt',
			'glami',
			'yandex_xml',
		);
	}

	/**
	 * @param $feed_info
	 *
	 * @return array
	 */
	public static function get_product_ids( $feed_info ) {

		$config = new Config( $feed_info );

		do_action( 'before_woo_feed_get_product_information', $config );

		$ids = QueryFactory::get_ids( $config );

		do_action( 'after_woo_feed_get_product_information', $config );

		return $ids;
	}

	/**
	 * @param $feed_info
	 * @param $product_ids
	 * @param $offset
	 * @param $status
	 *
	 * @return bool|mixed
	 */
	public static function generate_temp_feed_body( $feed_info, $product_ids, $offset, $status = false, $auto = false ) {

		$feed_rules = $feed_info['option_value']['feedrules'];
		$feed_name  = Helper::extract_feed_option_name( $feed_info['option_name'] );

		$config = new Config( $feed_info );

		do_action( 'before_woo_feed_generate_batch_data', $config );

		if ( ! empty( $feed_rules['provider'] ) ) {

			$provider      = $config->get_feed_template();
			$file_ext_type = $config->get_feed_file_type();
			if ( $offset === 0 ) {
				self::unlink_temporary_files( $feed_rules, $feed_rules['filename'], $auto );
			}
			$feed_template = TemplateFactory::make_feed( $product_ids, $config );
			//Generate Header footer
			// TODO: call this function only when offset is 0. But when creating the new feed 0 is calling 2 times. and not generating the header footer.
			self::generate_header_footer( $feed_template, $file_ext_type, $feed_name, $feed_rules, $provider );


			$current_feed = $feed_template->get_feed();
			woo_feed_log_feed_process( $feed_rules['filename'], sprintf( 'Initializing merchant Class %s for %s', $provider, $provider ) );
			if ( ! empty( $current_feed ) ) {
				// Get previous feed body data from temporary file to concat with current data.
				//$temp_feed_body_name = AttributeValueByType::AUTO_FEED_TEMP_BODY_PREFIX . $feed_name;
				$temp_feed_body_prefix = self::get_feed_body_temp_prefix( $auto );
				$temp_feed_body_name   = $temp_feed_body_prefix . $feed_name;
				$previous_feed         = self::get_batch_feed_info( $provider, $file_ext_type, $temp_feed_body_name );
				// Has previous feed body.
				if ( $previous_feed ) {
					/**
					 * If file extension type is csv, tsv, xls, json, xlsx then
					 * merge previous array with current array
					 *
					 * Else concat previous feed body with current feed body
					 */
					if ( 'csv' === $file_ext_type || 'tsv' === $file_ext_type || 'xls' === $file_ext_type || 'json' === $file_ext_type || 'xlsx' === $file_ext_type ) {
						if ( \is_array( $previous_feed ) ) {
							$newFeed = \array_merge( $previous_feed, $current_feed );
							self::save_batch_feed_info( $provider, $file_ext_type, $newFeed, $temp_feed_body_name, $feed_rules );
						} else {
							$newFeed = $previous_feed . $current_feed;
							self::save_batch_feed_info( $provider, $file_ext_type, $newFeed, $temp_feed_body_name, $feed_rules );
						}
					} else {
						$newFeed = $previous_feed . $current_feed;
						self::save_batch_feed_info( $provider, $file_ext_type, $newFeed, $temp_feed_body_name, $feed_rules );
					}
				} else {
					self::save_batch_feed_info( $provider, $file_ext_type, $current_feed, $temp_feed_body_name, $feed_rules );
				}
				$status = true;
			} else {
				$status = false;
			}
		}
		do_action( 'after_woo_feed_generate_batch_data', $config );

		return $status;
	}

	/**
	 * @param $feed_info
	 * @param $should_update_last_update_time
	 *
	 * @return array
	 */
	public static function save_feed_file( $feed_info, $should_update_last_update_time = false, $auto = false ) {
		$option_name_orginal = $feed_info['option_name'];
		$provider            = $feed_info['option_value']['feedrules']['provider'];
		$feed_type_ext       = $feed_info['option_value']['feedrules']['feedType'];

		$path        = Helper::get_file_dir( $provider, $feed_type_ext );
		$option_name = Helper::extract_feed_option_name( $option_name_orginal );
		$feed_url    = Helper::get_file_url( $option_name, $provider, $feed_type_ext );

		$contents = '';
		$sections = [ 'header', 'body', 'footer' ];
		// Remove the footer if feed type is csv
		if ( ! self::should_create_footer( $feed_type_ext ) ) {
			$sections = \array_filter( $sections, function ( $section ) {
				return 'footer' != $section;
			} );
		}

		$temp_file_name = '';
		foreach ( $sections as $section ) {
			$temp_file_ext = self::get_file_type( $feed_type_ext );
			if ( 'header' === $section ) {
				$temp_file_name = AttributeValueByType::FEED_TEMP_HEADER_PREFIX . $option_name . '.' . $temp_file_ext;
			} elseif ( 'footer' === $section ) {
				$temp_file_name = AttributeValueByType::FEED_TEMP_FOOTER_PREFIX . $option_name . '.' . $temp_file_ext;
			} else {
				$temp_feed_body_prefix = self::get_feed_body_temp_prefix( $auto );
				$temp_file_name        = $temp_feed_body_prefix . $option_name . '.' . $temp_file_ext;
			}

			$temp_content = FileSystem::ReadFile( $path, $temp_file_name );

			// If there is a problem regarding file system or other.
			if ( is_wp_error( $temp_content ) ) {
				$status = new WP_Error(
					$temp_content->get_error_code(),
					$temp_content->get_error_message(),
					[ 'status' => 404 ]
				);

				return [
					'status'   => $status,
					'feed_url' => $feed_url,
				];
			}

			if ( self::should_json_decode( $feed_type_ext ) ) {
				$temp_content = \json_decode( $temp_content, true );
				if ( \is_array( $temp_content ) || 'json' === $feed_type_ext ) { // json, csv fil
					$temp_contents = $contents ? $contents : [];
					$temp_content  = $temp_content ? $temp_content : [];
					$contents      = \array_merge( $temp_contents, $temp_content );
				} else {
					$contents .= $temp_content;
				}
			} else {
				$contents .= $temp_content;
			}
		}

		$file_name = $option_name . '.' . $feed_type_ext;


		if ( is_array( $contents ) ) { // file type json, csv
			$contents = wp_json_encode( $contents );
			$status   = FileSystem::WriteFile( $contents, $path, $file_name );
		} else {
			$status = FileSystem::WriteFile( $contents, $path, $file_name );
		}

		// Upload ftp/sftp if enabled.
		self::upload_feed_file_to_ftp_server( $feed_info, $path, $file_name );

		// Remove temporary files.
		self::unlink_temporary_files( $feed_info['option_value']['feedrules'], $option_name, $auto );

		// Delete temporary cache data.
		Cache::delete( 'wad_discounts' );

		if ( ! isset( $feed_info['option_value']['url'] ) ) {
			$feed_info['option_value']['url'] = Helper::get_file_url( $feed_info['option_name'], $feed_info['option_value']['feedrules']['provider'], $feed_info['option_value']['feedrules']['feedType'] );
		}

		if ( ! isset( $feed_info['option_value']['status'] ) ) {
			$feed_info['option_value']['status'] = false;
		}

		if ( $should_update_last_update_time ) {
			$feed_info['option_value']['last_updated'] = \date( 'Y-m-d H:i:s', \strtotime( \current_time( 'mysql' ) ) );
			update_option( $option_name_orginal, $feed_info['option_value'] );
		}

		delete_transient( 'ctx_feed_structure_transient' );

		return [
			'status'   => $status,
			'feed_url' => $feed_url,
		];

	}

	/**
	 * @param $feed_info
	 * @param $path
	 * @param $file_name
	 *
	 * @return void
	 * @throws \CTXFeed\V5\FTP\FtpException
	 */
	private static function upload_feed_file_to_ftp_server( $feed_info, $path, $file_name ) {

		/**
		 * class FtpClient only can upload ftp/ftps not sftp upload.
		 * ftp_ssl_connect method is used for FTP SSL file upload
		 * ssh2_sftp is intended to use for sFTP file upload
		 *
		 * That's why here we use FtpClient class for FTP file upload and
		 *  self::handle_file_transfer for sFTP uload.
		 *
		 * @see https://secure.helpscout.net/conversation/2390941164/29741?folderId=713813
		 * @see https://www.php.net/manual/en/function.ftp-ssl-connect.php
		 * @see https://www.php.net/manual/en/function.ssh2-sftp.php
		 * @see https://www.spiceworks.com/tech/networking/articles/sftp-vs-ftps/
		 */
		if ( isset( $feed_info['option_value']['feedrules']['ftpenabled'] ) && $feed_info['option_value']['feedrules']['ftpenabled'] ) {
			$path = $path . '/' . $file_name; // locale file path to upload.

			if ( isset( $feed_info['option_value']['feedrules']['ftporsftp'] ) & 'ftp' === $feed_info['option_value']['feedrules']['ftporsftp'] ) {
				/*$ftp         = new FtpClient();
				$ftp_connect = $ftp->connect( $feed_info['option_value']['feedrules']['ftphost'], false, $feed_info['option_value']['feedrules']['ftpport'] ); // connect to ftp/sftp server
				$ftp_connect = $ftp_connect->login( $feed_info['option_value']['feedrules']['ftpuser'], $feed_info['option_value']['feedrules']['ftppassword'] ); // login to server

				$ftp_connect->putFromPath( $path );*/

				$remote_file = basename( $path );
				self::uploadFileInFtp( $feed_info['option_value']['feedrules']['ftpuser'], $feed_info['option_value']['feedrules']['ftppassword'], $feed_info['option_value']['feedrules']['ftphost'], $path, $remote_file );


			} else {
				$feed_rules       = $feed_info['option_value']['feedrules'];
				$is_file_uploaded = self::handle_file_transfer( $path, $file_name, $feed_rules );
				if ( $is_file_uploaded ) {
					woo_feed_log_feed_process( $file_name, 'file transfer request success.' );
				} else {
					woo_feed_log_feed_process( $file_name, 'Unable to process file transfer request.' );
				}
			}
		}

	}

	/**
	 * Transfer file as per ftp config
	 *
	 * @param string $file_from
	 * @param string $file_to
	 * @param array $info
	 *
	 * @return bool
	 */
	private static function handle_file_transfer( $file_from, $file_to, $info ) {
		if ( $info['ftpenabled'] ) {
			if ( ! file_exists( $file_from ) ) {
				\woo_feed_log_feed_process( $info['filename'], 'Unable to process file transfer request. File does not exists.' );

				return false;
			}
			$ftp_host         = \sanitize_text_field( $info['ftphost'] );
			$ftp_user         = \sanitize_text_field( $info['ftpuser'] );
			$ftp_password     = \sanitize_text_field( $info['ftppassword'] );
			$ftp_path         = \trailingslashit( \untrailingslashit( \sanitize_text_field( $info['ftppath'] ) ) );
			$ftp_passive_mode = ( isset( $info['ftpmode'] ) && \sanitize_text_field( $info['ftpmode'] ) === 'passive' ) ? true : false;
			if ( isset( $info['ftporsftp'] ) & 'ftp' === $info['ftporsftp'] ) {
				$ftporsftp = 'ftp';
			} else {
				$ftporsftp = 'sftp';
			}
			if ( isset( $info['ftpport'] ) && ! empty( $info['ftpport'] ) ) {
				$ftp_port = \absint( $info['ftpport'] );
			} else {
				$ftp_port = false;
			}

			if ( ! $ftp_port || ! ( ( 1 <= $ftp_port ) && ( $ftp_port <= 65535 ) ) ) {
				$ftp_port = 'sftp' === $ftporsftp ? 22 : 21;
			}

			\woo_feed_log_feed_process( $info['filename'], sprintf( 'Uploading Feed file via %s.', $ftporsftp ) );


			try {
				if ( 'ftp' === $ftporsftp ) {

					$ftp = new \WebAppick\FTP\FTPConnection();
					if ( $ftp->connect( $ftp_host, $ftp_user, $ftp_password, $ftp_passive_mode, $ftp_port ) ) {
						return $ftp->upload_file( $file_from, $ftp_path . $file_to );
					}
				} elseif ( 'sftp' === $ftporsftp ) {
					$sftp = new \WebAppick\FTP\SFTPConnection( $ftp_host, $ftp_port );
					$sftp->login( $ftp_user, $ftp_password );

					return $sftp->upload_file( $file_from, $file_to, $ftp_path );

				}
			} catch ( \Exception $e ) {
				$message = 'Error Uploading Feed Via ' . $ftporsftp . PHP_EOL . 'Caught Exception :: ' . $e->getMessage();
				\woo_feed_log( $info['filename'], $message, 'critical', $e, true );
				\woo_feed_log_fatal_error( $message, $e );

				return false;
			}
		}

		return false;
	}

	/**
	 * @param array $feed_rules
	 * @param int $offset
	 * @param array $product_ids
	 *
	 * @return void
	 */
	public static function log_data( $feed_rules, $offset, $product_ids ) {
		\woo_feed_log_feed_process( $feed_rules['filename'], \sprintf( 'Processing Loop %d.', ( $offset + 1 ) ) );
		$m = 'Processing Product Following Product (IDs) : ' . PHP_EOL;
		foreach ( \array_chunk( $product_ids, 10 ) as $ids ) { // pretty print log [B-)=
			$m .= \implode( ', ', $ids ) . PHP_EOL;
		}

		\woo_feed_log_feed_process( $feed_rules['filename'], $m );
	}


	/**
	 * Generates and saves the header and footer for a feed.
	 *
	 * @param object $feed_template The feed template object.
	 * @param string $file_ext_type The file extension type.
	 * @param string $feed_name The name of the feed.
	 * @param array $feed_rules Feed rules.
	 * @param string $provider The provider.
	 *
	 * @return void
	 */
	public static function generate_header_footer( $feed_template, $file_ext_type, $feed_name, $feed_rules, $provider ) {
		$feed_header = $feed_template->get_header();
		$feed_footer = $feed_template->get_footer();

		$temp_feed_header_name = AttributeValueByType::FEED_TEMP_HEADER_PREFIX . $feed_name;
		$temp_feed_footer_name = AttributeValueByType::FEED_TEMP_FOOTER_PREFIX . $feed_name;
		// TODO: should generate footer when template type is csv ?
		self::save_batch_feed_info( $provider, $file_ext_type, $feed_header, $temp_feed_header_name, $feed_rules );

		// create footer for xml file .
		if ( self::should_create_footer( $file_ext_type ) ) {
			self::save_batch_feed_info( $provider, $file_ext_type, $feed_footer, $temp_feed_footer_name, $feed_rules );
		}

	}

	/**
	 * Generates a feed during a cron job.
	 *
	 * @param array $feed_info Feed configuration information.
	 * @param int $offset Offset for batch processing.
	 * @param bool $should_update_last_update_time Flag indicating whether to update the last update time.
	 *
	 * @return bool True if the feed generation is successful, false otherwise.
	 */
	public static function generate_feed( $feed_info, $offset = 0, $should_update_last_update_time = true ) {
		if ( ! \is_array( $feed_info ) || ! isset( $feed_info['option_value'] ) ) {
			// Handle invalid input.
			return false;
		}

		if ( $offset < 0 ) {
			// Handle invalid offset.
			return false;
		}

		$ids = self::get_product_ids( $feed_info );

		if ( empty( $ids ) ) {
			// Handle the case where no product IDs are found.
			return false;
		}

		$status = self::generate_temp_feed_body( $feed_info, $ids, $offset, false, true );

		if ( $status ) {
			self::save_feed_file( $feed_info, $should_update_last_update_time, true );
		}

		return $status;
	}

	/**
	 * Extracts a substring from a given string, delimited by start and end markers.
	 *
	 * @param string $string The full string to extract from.
	 * @param string $start The start marker of the desired substring.
	 * @param string $end The end marker of the desired substring.
	 *
	 * @return string The extracted substring, or an empty string if markers are not found.
	 */
	public static function get_string_between( $string, $start, $end ) {
		if ( ! \is_string( $string ) || ! \is_string( $start ) || ! \is_string( $end ) ) {
			// Handle invalid inputs.
			return '';
		}

		$start_pos = \strpos( $string, $start );

		if ( $start_pos === false ) {
			return '';
		}

		$start_pos += \strlen( $start );
		$end_pos   = \strpos( $string, $end, $start_pos );

		if ( $end_pos === false ) {
			return '';
		}

		return \substr( $string, $start_pos, $end_pos - $start_pos );
	}

	/**
	 * Validate old feeds during cron job.
	 *
	 * @param array $feed_info feed information.
	 *
	 * @return array  An array return form old feed.
	 */
	public static function validate_feed( $feed_info ) {
		$temp_make_feed = $feed_info;
		// Modify old feed data
		$temp_make_rules      = $temp_make_feed['option_value']['feedrules'];
		$should_modify_filter = array(
			'is_outOfStock'         => false,
			'is_backorder'          => false,
			'is_emptyDescription'   => false,
			'is_emptyImage'         => false,
			'is_emptyPrice'         => false,
			'product_visibility'    => false,
			'outofstock_visibility' => false,
		);
		$should_modify_filter = \array_keys( $should_modify_filter );
		$feed_default_value   = array( 'product_ids', 'post_status', 'categories' );

		foreach ( $temp_make_rules as $key => $value ) {
			if ( \in_array( $key, $should_modify_filter ) ) {
				$temp_make_rules[ $key ] = self::get_toggle_value( $temp_make_rules[ $key ] );
			}

			/**
			 * Some previous feed is saving an error as value of product_ids.
			 * this value should be an array, it's default value is an array
			 */

			if ( \in_array( $key, $feed_default_value ) && ! \is_array( $temp_make_rules[ $key ] ) ) {
				if ( $temp_make_rules[ $key ] ) {
					if ( 'product_ids' === $key ) { // if key is product_ids then remove the extra space from ids.
						$temp_data               = \explode( ',', $temp_make_rules[ $key ] );
						$temp_data               = \array_map( 'absint', $temp_data );
						$temp_make_rules[ $key ] = $temp_data;
					} else {
						$temp_make_rules[ $key ] = \explode( ',', $temp_make_rules[ $key ] );
					}
				} else {
					$temp_make_rules[ $key ] = array();
				}
			}
		}

		$temp_make_feed['option_value']['feedrules'] = $temp_make_rules;

		return $temp_make_feed;
	}

	/**
	 * Validate feed config.
	 *
	 * @param array $feed_rules feed rules.
	 *
	 * @return array  feed config.
	 */
	public static function validate_config( $feed_rules ) {
		// Modify old feed data
		$temp_feed_rules      = $feed_rules;
		$should_modify_filter = array(
			'is_outOfStock'         => false,
			'is_backorder'          => false,
			'is_emptyDescription'   => false,
			'is_emptyImage'         => false,
			'is_emptyPrice'         => false,
			'product_visibility'    => false,
			'outofstock_visibility' => false,
			'is_emptyTitle'         => false,
		);
		$should_modify_filter = \array_keys( $should_modify_filter );

		foreach ( $temp_feed_rules as $key => $value ) {
			if ( \in_array( $key, $should_modify_filter ) ) {
				$temp_feed_rules[ $key ] = self::get_toggle_value( $temp_feed_rules[ $key ] );
			}
		}

		return $temp_feed_rules;
	}

	/**
	 * Determines the boolean value based on various representations of 'false'.
	 *
	 * @param mixed $toggle_value The value to evaluate.
	 *
	 * @return bool Returns false for common representations of 'false', otherwise true.
	 */
	public static function get_toggle_value( $toggle_value ) {
		$false_values = array( 'disable', 'off', 'no', false, '', 0, 'n', '0', 'false' );

		// Check if the value is in the list of 'false' representations.
		return ! \in_array( $toggle_value, $false_values, true );
	}

	/**
	 * Returns the appropriate temporary feed body prefix based on the automatic flag.
	 *
	 * @param bool $auto Indicates whether the automatic mode is enabled.
	 *
	 * @return string The temporary feed body prefix.
	 */
	public static function get_feed_body_temp_prefix( $auto = false ) {
		if ( ! \is_bool( $auto ) ) {
			// Handle non-boolean input.
			return AttributeValueByType::FEED_TEMP_BODY_PREFIX;
		}

		return $auto ? AttributeValueByType::AUTO_FEED_TEMP_BODY_PREFIX : AttributeValueByType::FEED_TEMP_BODY_PREFIX;
	}

	/**
	 * @return bool
	 */
	public static function should_generate_feed_by_ajax() {
		$should_generate_feed_by_ajax = true;
		if ( is_plugin_active( 'polylang/polylang.php' ) ) {
			$should_generate_feed_by_ajax = false;
		}

		if ( is_plugin_active( 'woocommerce-multilingual/wpml-woocommerce.php' ) ) {
			$should_generate_feed_by_ajax = false;
		}

		return apply_filters( 'woo_feed_generate_feed_by_ajax', $should_generate_feed_by_ajax );
	}

	public static function uploadFileInFtp( $ftpUsername, $ftpPassword, $ftpServer, $localFilePath, $serverFilePath ) {
		$conn_id = ftp_connect( $ftpServer );
		if ( ! $conn_id ) {
			throw new FtpException( "Failed to connect to the FTP server" );
		}
		$login = ftp_login( $conn_id, $ftpUsername, $ftpPassword );
		if ( ! $login ) {
			throw new FtpException( "FTP login failed" );
		}
		ftp_pasv( $conn_id, true );

		if ( ! file_exists( $localFilePath ) ) {
			throw new FtpException( "Local file not found: $localFilePath" );
		}
		$content = file_get_contents( $localFilePath );
		$tmp     = fopen( tempnam( sys_get_temp_dir(), $localFilePath ), "w+" );
		fwrite( $tmp, $content );
		rewind( $tmp );
		$upload = ftp_fput( $conn_id, $serverFilePath, $tmp, FTP_BINARY );
		ftp_close( $conn_id );

		if ( $upload ) {
			return true;
		} else {
			throw new FtpException(
				'Unable to put the remote file from the local file "' . $localFilePath . '"'
			);
		}

	}

}
