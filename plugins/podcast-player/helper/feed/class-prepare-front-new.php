<?php
/**
 * Escape data and prepare to send to frontend.
 *
 * @link       https://www.vedathemes.com
 * @since      1.0.0
 *
 * @package    Podcast_Player
 * @subpackage Podcast_Player/Helper
 */

namespace Podcast_Player\Helper\Feed;

use Podcast_Player\Helper\Functions\Getters as Get_Fn;
use Podcast_Player\Helper\Core\Singleton;

/**
 * Escape data and prepare to send to frontend.
 *
 * @package    Podcast_Player
 * @subpackage Podcast_Player/Helper
 * @author     vedathemes <contact@vedathemes.com>
 */
class Prepare_Front_New extends Singleton {

	/**
	 * Constructor method.
	 *
	 * @since  3.3.0
	 *
	 * @param array $data Fetched feed data to be sent to frontend.
	 */
	public function init( $data ) {
		$items = $data['items'];
		array_walk(
			$items,
			function( &$item, $key ) {
				if ( isset( $item['date'] ) ) {
					$item['timestamp'] = $this->timestamp( $item['date'] );
					$item['date'] = $this->date( $item['date'] );
				}
			}
		);
		$data['items'] = $items;
		return $data;
	}

	/**
	 * Escape feed item date.
	 *
	 * @since  3.3.0
	 *
	 * @param string|Array $val Feed item date.
	 */
	public function date( $val ) {
		$timezone = Get_Fn::get_plugin_option( 'timezone' );
        $date     = is_array( $val ) ? $val['date'] : $val;
        $date     = $date ? $date : 0;
        $offset   = is_array( $val ) ? $val['offset'] : 0;

        if ( 'local' === $timezone ) {
            return date_i18n( get_option( 'date_format' ), $date + 60 * 60 * get_option( 'gmt_offset' ) );
        } elseif ( 'feed' === $timezone ) {
            return date_i18n( get_option( 'date_format' ), $date + $offset );
        } else {
            return date_i18n( get_option( 'date_format' ), $date );
        }
	}

	/**
	 * Escape feed item date.
	 *
	 * @since  6.7.0
	 *
	 * @param string|Array $val Feed item date.
	 */
	public function timestamp( $val ) {
		$timezone = Get_Fn::get_plugin_option( 'timezone' );
        $date     = is_array( $val ) ? $val['date'] : $val;
        $date     = $date ? $date : 0;
        $offset   = is_array( $val ) ? $val['offset'] : 0;

        if ( 'local' === $timezone ) {
            return $date + 60 * 60 * get_option( 'gmt_offset' );
        } elseif ( 'feed' === $timezone ) {
            return $date + $offset;
        } else {
            return $date;
        }
	}
}
