<?php
/**
 * Fetch Feed Data from Feed XML file.
 *
 * @link       https://www.vedathemes.com
 * @since      1.0.0
 *
 * @package    Podcast_Player
 * @subpackage Podcast_Player/Helper
 */

namespace Podcast_Player\Helper\Feed;

use Podcast_Player\Helper\Functions\Getters as Get_Fn;
use Podcast_Player\Helper\Functions\Validation as Validation_Fn;
use Podcast_Player\Helper\Store\StoreManager;
use Podcast_Player\Helper\Store\FeedData;
use Podcast_Player\Helper\Store\ItemData;

/**
 * Fetch Feed Data from Feed XML file.
 *
 * @package    Podcast_Player
 * @subpackage Podcast_Player/Helper
 * @author     vedathemes <contact@vedathemes.com>
 */
class Prepare_Storage_New {

	/**
	 * Holds feed raw data.
	 *
	 * @since  6.4.3
	 * @access private
	 * @var    string
	 */
	private $feed;

	/**
	 * Holds unique feed key for current instance.
	 *
	 * @since  6.4.3
	 * @access private
	 * @var    string
	 */
	private $feed_key = '';

	/**
	 * Holds feed url for current instance.
	 *
	 * @since  3.5.0
	 * @access private
	 * @var    string
	 */
	private $feed_url = '';

	/**
	 * Holds feed key prefix.
	 *
	 * @since  6.4.3
	 * @access private
	 * @var    string
	 */
	private $prefix = 'pp_feed';

	/**
	 * Holds old feed data.
	 *
	 * @since  6.4.3
	 * @access private
	 * @var    array
	 */
	private $old_data = array();

	/**
	 * Holds new episodes ID.
	 *
	 * @since  5.8.0
	 * @access private
	 * @var    array
	 */
	private $elist = array();

	/**
	 * Constructor method.
	 *
	 * @since  6.4.3
	 *
	 * @param object $raw_data Feed Data.
	 * @param string $key      Feed Key.
	 * @param string $url      Feed Url.
	 */
	public function __construct( $raw_data, $key, $url ) {
		$this->feed = $raw_data;
		$this->feed_key = $key;
        $this->feed_url = $url;

        $store_manager = StoreManager::get_instance();
        $old_data = $store_manager->get_podcast( $key );

        if ( $old_data instanceof FeedData ) {
            $this->old_data = $old_data;
        } else {
		    $data_key = $this->prefix . '_data_' . $this->feed_key;
		    $old_data = get_option( $data_key );
		    if ( ! $old_data || ! is_array( $old_data ) || ! isset( $old_data['items'] ) || ! $old_data['items'] ) {
			    $this->old_data = false;
		    } else {
			    $this->old_data = $this->arr_to_obj( $old_data );
		    }
        }
	}

	/**
	 * Convert older array data to the object data.
	 *
	 * @since 6.5.0
	 *
	 * @param array $data Older podcast data in array form
	 */
	private function arr_to_obj( $data ) {
		$data['items'] = array_map(
			function( $item ) {
				$item_data = new ItemData();
				$item_data->set( $item, false, 'none' );
				return $item_data;
			},
			$data['items']
		);
		$feed_data = new FeedData();
		$feed_data->set( $data, false, 'none' );
		return $feed_data;
	}

	/**
	 * Prepare feed data for storage.
	 *
	 * @since  6.4.3
	 */
	public function init() {
		if ( ! $this->feed instanceof FeedData ) {
			return false;
		}

		// Compare with old stored data to get changes.
		list( $data, $changed ) = $this->get_changes();

		// Check if some images are not saved locally.
		$is_img_save = $this->check_if_image_save( $data );
		return array( $data, $changed, $is_img_save, $this->elist );
	}

	/**
	 * Compare new data with old stored data for changes.
	 *
	 * @since  6.4.3
	 */
	private function get_changes() {
		$is_changed = false;

		if ( ! $this->old_data instanceof FeedData ) {
			return array( $this->feed, true );
		}

		// Compare and update channel level data.
		$is_changed = $this->apply_channel_changes();
		return array( $this->old_data, $is_changed );
	}

	/**
	 * Compare and update podcast channel level data.
	 *
	 * @since 6.5.0
	 */
	private function apply_channel_changes() {
		$is_changed = false;
		$old = $this->old_data->getVars();
		$new = $this->feed->getVars();

		foreach ( $new as $key => $data ) {
			// Only compare channel level items.
			if ( 'items' === $key ) {
				list( $items, $is_changed ) = $this->apply_items_changes( $data, $old[ $key ] );
				$this->old_data->set( 'items', $items );
				continue;
			}

			// Do nothing if older data has not changed.
			if ( isset( $old[ $key ] ) && $data === $old[ $key ] ) {
				continue;
			}

			// Data changed/ Added, so update accordingly.
			$this->old_data->set( $key, $data, 'none' );
			$is_changed = true;

			// Remove saved cover ID if cover image changes.
			if ( 'image' === $key ) {
				$this->old_data->set( 'cover_id', 0, 'none' );
			}
		}

		return $is_changed;
	}

	private function apply_items_changes( $new, $old ) {
		$deleted_items = array_diff_key( $old, $new );
		$del_ids       = array();
		$is_changed    = false;

		// Get episode ids against episode unique keys to handle borderline cases.
		foreach ( $deleted_items as $key => $val ) {
			$episode_id = $val->get('episode_id');
			$del_ids[ $episode_id ] = $key;
		}

		// Conditionally remove old items which are no longer available in the feed.
		$keep_old = Get_Fn::get_plugin_option( 'keep_old' );
		if ( 'yes' !== $keep_old ) {
			$old = array_intersect_key( $old, $new );
		}

		foreach ( $new as $id => $item ) {

			// If the item is already available and has not changed.
			if ( isset( $old[ $id ] ) && $item === $old[ $id ] ) {
				continue;
			}

			// Just add if new item does not exist.
			if ( ! isset( $old[ $id ] ) ) {

				// Handle borderline cases where episode audio URL is modified.
				$episode_id = $item->get('episode_id');
				if ( isset( $del_ids[ $episode_id ] ) ) {
					$episode_key = $del_ids[ $episode_id ];
					$old[ $episode_key ] = $item;
					unset( $deleted_items[ $key ] );
				} else {
					$old[ $id ]    = $item;
					$this->elist[] = $id;
				}

				$is_changed = true;
				continue;
			}

			// Check and update modified item data.
			list( $data, $is_changed ) = $this->item_changes( $item, $old[ $id ] );
			if ( $is_changed ) {
				$old[ $id ] = $data;
			}	
		}

		return array( $old, ( $is_changed || ! empty( $deleted_items ) ) );
	}

	/**
	 * Compare and update item properties.
	 *
	 * @since  6.4.3
	 *
	 * @param array $new Newly Created data for the item.
	 * @param array $old Old Stored data for the item.
	 */
	public function item_changes( $new, $old ) {
		$is_changed = false;
		$new_item = $new->getVars();
		$old_item = $old->getVars();
		foreach ( $new_item as $key => $data ) {
			if ( isset( $old_item[ $key ] ) && $data === $old_item[ $key ] ) {
				continue;
			}

			$old->set( $key, $data, 'none' );
			$is_changed = true;

			// Remove saved featured image ID if featured image changes.
			if ( 'featured' === $key && isset( $old_item['featured_id'] ) && $old_item['featured_id'] ) {
				$old->set( 'featured_id', 0, 'none' );
			}
		}
		return array( $old, $is_changed );
	}

	/**
	 * Check if some images are not saved locally.
	 *
	 * @since  6.4.3
	 *
	 * @param array $data Fetched final data for the feed.
	 */
	public function check_if_image_save( $data ) {
		$items = $data->get('items');
		$cover = $data->get('cover_id');
		if ( ! $cover || empty( $cover ) ) {
			return true;
		}

		foreach ( $items as $item ) {
			$featured = $item->get('featured');
			$featured_id = $item->get('featured_id');
			if ( $featured && 0 !== $featured_id ) {
				return true;
			}
		}
		return false;
	}
}
