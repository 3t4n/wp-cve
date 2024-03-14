<?php

namespace CTXFeed\V5\Output;

use CTXFeed\V5\API\RestConstants;
use CTXFeed\V5\Helper\CommonHelper;
use CTXFeed\V5\Output\WPOptions as WPOptionBase;
use CTXFeed\V5\Product\AttributeValueByType;
use Woo_Feed_Notices;

/**
 * Class WPOptions
 *
 * @package    CTXFeed
 * @subpackage CTXFeed\V5\Output
 * @author     Azizul Hasan <azizulhasan.cr@gmail.com>
 * @link       https://azizulhasan.com
 * @license    https://opensource.org/licenses/gpl-license.php GNU Public License
 */
class WPOptions {

	public static $option_lists = [];

	/**
	 * Save WP Option.
	 *
	 * @param array $WPOptions
	 *
	 * @return array
	 */
	public static function saveWPOption( $WPOptions ) {
		self::set_option();
		$option_name = '';
		if ( count( $WPOptions ) ) {
			// Save option name.
			foreach ( $WPOptions as $index => $name ) {
				$option_name                 = AttributeValueByType::WP_OPTION_PREFIX . $name;
				$_data                       = get_option( $name );
				self::$option_lists[ $name ] = [
					'option_id'    => $name,
					'option_name'  => $option_name,
					'option_value' => $_data,
				];
				update_option( $option_name, self::$option_lists[ $name ] );
			}
		}

		self::update_option( self::$option_lists );

        Woo_Feed_Notices :: woo_feed_newly_added_wp_options_notice_data();

		return self::$option_lists;
	}


	public static function getWPOptions() {
		self::$option_lists = get_option( AttributeValueByType::WP_OPTION_NAME, [] );
		foreach ( self::$option_lists as $option_name => $option_value ) {
			if ( ! isset( self::$option_lists[ $option_name ]['option_value'] ) ) {
				self::$option_lists[ $option_name ]['option_value'] = get_option( $option_name );
			}
		}

		return self::$option_lists;
	}

	/**
	 * Delete WP Option.
	 *
	 * @param $options
	 *
	 * @return bool
	 */
	public static function deleteWPOption( $options ) {
		$option_name        = '';
		self::$option_lists = self::getWPOptions();
		foreach ( $options as $option ) {
			if ( isset( self::$option_lists[ $option ] ) ) {
				unset( self::$option_lists[ $option ] );
			}
			$option_name = AttributeValueByType::WP_OPTION_PREFIX . $option;
			delete_option( $option_name );
		}
		WPOptionBase::update_option( self::$option_lists );

		return self::$option_lists;
	}


	/**
	 * @return void
	 */
	public static function set_option() {
		self::$option_lists = get_option( AttributeValueByType::WP_OPTION_NAME, [] );
		foreach ( self::$option_lists as $key => $option ) {
			if ( ! array_key_exists( 'option_value', self::$option_lists[ $key ] ) ) {
				self::$option_lists[ $key ]['option_value'] = get_option( $option['option_id'] );
			}
		}
	}


	/**
	 * @param $option_lists
	 *
	 * @return void
	 */
	public static function update_option( $option_lists = [] ) {
		update_option( AttributeValueByType::WP_OPTION_NAME, $option_lists );
		self::set_option();
	}


}
