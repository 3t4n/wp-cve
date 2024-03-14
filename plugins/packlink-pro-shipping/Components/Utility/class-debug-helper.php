<?php
/**
 * Packlink PRO Shipping WooCommerce Integration.
 *
 * @package Packlink
 */

namespace Packlink\WooCommerce\Components\Utility;

use DateTime;
use Logeecom\Infrastructure\Exceptions\BaseException;
use Logeecom\Infrastructure\ORM\Exceptions\QueryFilterInvalidParamException;
use Logeecom\Infrastructure\ORM\QueryFilter\Operators;
use Logeecom\Infrastructure\ORM\QueryFilter\QueryFilter;
use Logeecom\Infrastructure\ORM\RepositoryRegistry;
use Logeecom\Infrastructure\ServiceRegister;
use Logeecom\Infrastructure\TaskExecution\QueueItem;
use Packlink\BusinessLogic\ShippingMethod\Models\ShippingMethod;
use Packlink\WooCommerce\Components\Services\Config_Service;
use Packlink\WooCommerce\Components\Services\Logger_Service;
use WooCommerce;
use ZipArchive;

/**
 * Class Debug_Helper
 *
 * @package Packlink\WooCommerce\Components\Utility
 */
class Debug_Helper {

	const PHP_INFO_FILE_NAME         = 'phpinfo.html';
	const SYSTEM_INFO_FILE_NAME      = 'system-info.json';
	const LOG_FILE_NAME              = 'logs.txt';
	const WC_LOG_FILE_NAME           = 'wc-logs.txt';
	const USER_INFO_FILE_NAME        = 'packlink-user-info.json';
	const QUEUE_INFO_FILE_NAME       = 'queue.json';
	const PARCEL_WAREHOUSE_FILE_NAME = 'parcel-warehouse.json';
	const SERVICE_INFO_FILE_NAME     = 'services.json';
	const DATABASE                   = 'MySQL';

	/**
	 * Configuration service.
	 *
	 * @var Config_Service
	 */
	private static $config_service;

	/**
	 * Returns path to zip archive that contains current system information.
	 *
	 * @return string Temporary file path.
	 *
	 * @throws QueryFilterInvalidParamException If filter is not available.
	 */
	public static function get_system_info() {
		$file = tempnam( sys_get_temp_dir(), 'packlink_system_info' );

		$zip = new ZipArchive();
		$zip->open( $file, ZipArchive::CREATE );

		$php_info = static::get_php_info();

		if ( false !== $php_info ) {
			$zip->addFromString( static::PHP_INFO_FILE_NAME, $php_info );
		}

		$dir = dirname( Logger_Service::get_log_file() );
		$zip->addFromString( static::SYSTEM_INFO_FILE_NAME, static::get_woocommerce_shop_info() );
		$zip->addFromString( static::LOG_FILE_NAME, static::get_logs( $dir ) );
		/** Ignore. @noinspection PhpUndefinedConstantInspection */
		$zip->addFromString( static::WC_LOG_FILE_NAME, static::get_logs( WC_LOG_DIR ) );
		$zip->addFromString( static::USER_INFO_FILE_NAME, static::get_user_info() );
		$zip->addFromString( static::QUEUE_INFO_FILE_NAME, static::get_queue_status() );
		$zip->addFromString( static::PARCEL_WAREHOUSE_FILE_NAME, static::get_parcel_and_warehouse_info() );
		$zip->addFromString( static::SERVICE_INFO_FILE_NAME, self::get_entities( ShippingMethod::CLASS_NAME ) );

		$zip->close();

		return $file;
	}

	/**
	 * Retrieves php info.
	 *
	 * @return false | string
	 */
	protected static function get_php_info() {
		ob_start();
		phpinfo(); //phpcs:ignore

		return ob_get_clean();
	}

	/**
	 * Returns information about WooCommerce and plugin.
	 *
	 * @return string
	 */
	protected static function get_woocommerce_shop_info() {
		global $wpdb, $wp_version;

		$result['WooCommerce version'] = WooCommerce::instance()->version;
		$result['WordPress version']   = $wp_version;
		$result['Theme']               = wp_get_theme()->get( 'Name' );
		$result['Base admin URL']      = get_admin_url();
		$result['Database']            = static::DATABASE;
		$result['Database version']    = $wpdb->db_version();
		$result['Plugin version']      = Shop_Helper::get_plugin_version();
		$result['Async process URL']   = self::get_config_service()->getAsyncProcessUrl( 'test' );
		$result['Auto-test URL']       = admin_url( 'admin.php?page=packlink-pro-auto-test' );

		return wp_json_encode( $result, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES );
	}

	/**
	 * Retrieves logs from WooCommerce.
	 *
	 * @noinspection PhpDocMissingThrowsInspection
	 *
	 * @param string $dir Logs directory path.
	 *
	 * @return string Log file contents.
	 */
	protected static function get_logs( $dir ) {
		$ignore      = array( '.', '..', 'index.html', '.htaccess' );
		$dir_content = scandir( $dir, SCANDIR_SORT_NONE );

		$dir   = rtrim( $dir, '/' ) . '/';
		$start = new DateTime( '-7 days' );
		$start->setTime( 0, 0 );
		$files = array();
		foreach ( $dir_content as $file ) {
			if ( in_array( $file, $ignore, true ) ) {
				continue;
			}

			// only logs from past 7 days.
			$file_time = filemtime( $dir . '/' . $file );
			if ( $file_time >= $start->getTimestamp() ) {
				$files[ $file ] = $file_time;
			}
		}

		asort( $files );
		$result = '';
		foreach ( array_keys( $files ) as $file ) {
			$result .= file_get_contents( $dir . $file ) . "\n"; // phpcs:ignore
		}

		return $result;
	}

	/**
	 * Retrieves user info.
	 *
	 * @return string User info.
	 */
	protected static function get_user_info() {
		return wp_json_encode(
			array(
				'User info' => self::get_config_service()->getUserInfo(),
				'API Key'   => self::get_config_service()->getAuthorizationToken(),
			),
			JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES
		);
	}

	/**
	 * Retrieves current queue status.
	 *
	 * @return string Queue status.
	 *
	 * @throws QueryFilterInvalidParamException If filter params are invalid.
	 */
	protected static function get_queue_status() {
		$filter = new QueryFilter();
		$filter->where( 'status', Operators::NOT_EQUALS, QueueItem::COMPLETED );

		return self::get_entities( QueueItem::CLASS_NAME, $filter );
	}

	/**
	 * Retrieves parcel and warehouse information.
	 *
	 * @return string Parcel and warehouse info.
	 */
	protected static function get_parcel_and_warehouse_info() {
		return wp_json_encode(
			array(
				'Default parcel'    => self::get_config_service()->getDefaultParcel() ?: array(),
				'Default warehouse' => self::get_config_service()->getDefaultWarehouse() ?: array(),
			),
			JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES
		);
	}

	/**
	 * Retrieves entities from database info.
	 *
	 * @param string      $entity_class The class identifier of the entity.
	 *
	 * @param QueryFilter $filter Query filter.
	 *
	 * @return string Service info.
	 */
	protected static function get_entities( $entity_class, $filter = null ) {
		$result = array();

		try {
			$repository = RepositoryRegistry::getRepository( $entity_class );

			foreach ( $repository->select( $filter ) as $item ) {
				$result[] = $item->toArray();
			}
		} catch ( BaseException $e ) { // phpcs:ignore
			/* Just continue with empty result. */
		}

		return wp_json_encode( $result, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES );
	}

	/**
	 * Gets the configuration service.
	 *
	 * @return Config_Service Configuration service instance.
	 */
	protected static function get_config_service() {
		if ( self::$config_service === null ) { // phpcs:ignore
			self::$config_service = ServiceRegister::getService( Config_Service::CLASS_NAME );
		}

		return self::$config_service;
	}
}
