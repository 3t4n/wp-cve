<?php
/**
 * AbsolutePlugins Services Product Updater
 * @link https://github.com/AbsolutePlugins/AbsolutePluginsServices
 * @version 1.0.0
 * @package AbsolutePluginsServices
 * @license MIT
 */

namespace AbsoluteAddons\AbsolutePluginsServices;

use stdClass;

if ( ! defined( 'ABSPATH' ) ) {
	die();
}

/**
 * Class Updater
 * @package AbsolutePluginsServices
 */
class Updater {

	/**
	 * AbsolutePluginsServices\Client
	 *
	 * @var Client
	 */
	protected $client;

	/**
	 * Flag for checking if the init method is already called.
	 * @var bool
	 */
	private $did_init = false;

	/**
	 * AbsolutePluginsServices\License
	 *
	 * @var License
	 */
	protected $license;

	/**
	 * Cache Key for current App
	 * @var string
	 */
	private $cache_key;

	/**
	 * Initialize the class
	 *
	 * @param Client $client The Client.
	 * @param License $license The license.
	 */
	public function __construct( Client $client, License $license ) {
		$this->client    = $client;
		$this->license   = $license;
		$this->cache_key = 'absp_service_api_' . md5( $this->client->getSlug() ) . '_version_info';
	}

	/**
	 * Initialize Updater
	 *
	 * @return void
	 */
	public function init() {
		if ( $this->did_init ) {
			return;
		}
		// Run hooks.
		$fn = 'run_' . $this->client->getType() . '_hooks'; // run_(plugin/theme)_hooks
		$this->$fn();

		$this->did_init = true;
	}

	/**
	 * Set up WordPress filter hooks to get plugin update.
	 *
	 * @return void
	 */
	private function run_plugin_hooks() {
		add_filter( 'pre_set_site_transient_update_plugins', [ $this, 'check_plugin_update' ] );
		add_filter( 'plugins_api', [ $this, 'plugins_api_filter' ], 10, 3 );

		register_deactivation_hook( $this->client->getFile(), [ $this, 'delete_cached_version_info' ] );
	}

	/**
	 * Set up WordPress filter hooks to get theme update.
	 *
	 * @return void
	 */
	private function run_theme_hooks() {
		add_filter( 'pre_set_site_transient_update_themes', [ $this, 'check_theme_update' ] );
		add_action( 'switch_theme', [ $this, 'delete_cached_version_info' ] );
	}

	/**
	 * Check for Update for this specific project
	 *
	 * @param object $transient_data plugin update transient data.
	 *
	 * @return object
	 */
	public function check_plugin_update( $transient_data ) {
		global $pagenow;

		if ( ! is_object( $transient_data ) ) {
			$transient_data = new stdClass;
		}

		if ( 'plugins.php' == $pagenow && is_multisite() ) {
			return $transient_data;
		}

		if ( ! empty( $transient_data->response ) && ! empty( $transient_data->response[ $this->client->getBasename() ] ) ) {
			return $transient_data;
		}

		$project_info = $this->get_information( 'plugin_update' );

		if ( false !== $project_info && is_object( $project_info ) && isset( $project_info->new_version ) ) {
			if ( version_compare( $this->client->getProjectVersion(), $project_info->new_version, '<' ) ) {
				unset( $project_info->sections );
				$transient_data->response[ $this->client->getBasename() ] = $project_info;
			}
			$transient_data->last_checked                            = time();
			$transient_data->checked[ $this->client->getBasename() ] = $this->client->getProjectVersion();
		}

		return $transient_data;
	}

	/**
	 * Get version info from database
	 *
	 * @return object|bool
	 */
	private function get_cached_version_info( $which ) {
		global $pagenow;


		// If updater page then fetch from API now
		if ( 'update-core.php' == $pagenow ) {
			return false; // Force fetching update
		}

		$info = get_transient( $this->cache_key . $which );

		if ( ! $info && ! isset( $info->name ) ) {
			return false; // Cache is expired.
		}

		return $this->__children_to_array( $info, [ 'icons', 'banners', 'sections' ] );
	}

	/**
	 * Set version info to database
	 *
	 * @param string $value data (version info) to cache.
	 *
	 * @return void
	 */
	private function set_cached_version_info( $value, $which ) {
		if ( ! $value ) {
			return;
		}
		set_transient( $this->cache_key . $which, $value, 3 * HOUR_IN_SECONDS );
	}

	/**
	 * Delete cached version info
	 * @return bool
	 */
	public function delete_cached_version_info() {
		return delete_transient( $this->cache_key );
	}

	/**
	 * Get plugin info from WC API Manager
	 *
	 * @param string $action
	 *
	 * @return bool|array
	 */
	private function get_information( $action ) {

		// cache first.
		$project_info = $this->get_cached_version_info( $action );

		if ( false === $project_info ) {
			$project_info = $this->get_updates( $action );
			$this->set_cached_version_info( $project_info, $action );
		}

		return $project_info;
	}

	private function get_updates( $action ) {

		$response = $this->license->check_update();

		if ( isset( $response['success'] ) && $response['success'] ) {

			$data = $response['data']['package'];

			if ( 'plugin_update' !== $action ) {
				$response = $this->license->get_information();
				if ( isset( $response['success'] ) && $response['success'] ) {
					$data = array_merge( $data, $response['data']['info'] );
				}
			}

			if ( isset( $data['product_id'] ) ) {
				unset( $data['product_id'] );
			}

			/**
			 * Filter API Response Data
			 *
			 * @param array $data
			 */
			$data = apply_filters( 'absp_service_api_' . $this->client->getSlug() . '_plugin_api_info', $data, $action );

			return $this->__children_to_array( (object) $data, [
				'icons',
				'banners',
				'sections',
				'compatibility',
				'ratings',
				'contributors',
				'screenshots',
				'tags'
			] );

		}

		return false;
	}

	/**
	 * Updates information on the "View version x.x details" page with custom data.
	 *
	 * @param mixed $data Plugin info Data.
	 * @param string $action Request Action.
	 * @param object $args API Args.
	 *
	 * @return object $data
	 */
	public function plugins_api_filter( $data, $action = '', $args = null ) {
		if ( 'plugin_information' != $action ) {
			return $data;
		}

		if ( ! isset( $args->slug ) || ( $args->slug != $this->client->getSlug() ) ) {
			return $data;
		}

		return $this->get_information( 'plugin_information' );
	}

	/**
	 * Check theme update
	 *
	 * @param object $transient_data Theme update transient data.
	 *
	 * @return object
	 */
	public function check_theme_update( $transient_data ) {
		global $pagenow;

		if ( ! is_object( $transient_data ) ) {
			$transient_data = new stdClass();
		}

		if ( 'themes.php' == $pagenow && is_multisite() ) {
			return $transient_data;
		}

		if ( ! empty( $transient_data->response ) && ! empty( $transient_data->response[ $this->client->getSlug() ] ) ) {
			return $transient_data;
		}

		$project_info = $this->get_information( 'theme_update' );

		if ( false !== $project_info && is_object( $project_info ) && isset( $project_info->new_version ) ) {

			if ( version_compare( $this->client->getProjectVersion(), $project_info->new_version, '<' ) ) {
				$transient_data->response[ $this->client->getSlug() ] = (array) $project_info;
			}

			$transient_data->last_checked                        = time();
			$transient_data->checked[ $this->client->getSlug() ] = $this->client->getProjectVersion();
		}

		return $transient_data;
	}

	/**
	 * Typecast child element to array or object
	 * Utility method
	 *
	 * @param array|object $input the array or object to convert/typecast.
	 * @param array $children children array.
	 *
	 * @return array|object
	 */
	private function __children_to_array( $input, $children = [] ) {
		if ( ! empty( $children ) && is_array( $children ) && ( is_object( $input ) || is_array( $input ) ) ) {
			$isObject = is_object( $input );
			foreach ( $children as $child ) {
				if ( call_user_func_array( $isObject ? 'property_exists' : 'array_key_exists', [ $input, $child ] ) ) {
					if ( $isObject ) {
						$input->{$child} = (array) $input->{$child};
					} else {
						$input[ $child ] = (array) $input->{$child};
					}
				}
			}
		}

		return $input;
	}
}
// End of file class-updater.php.
