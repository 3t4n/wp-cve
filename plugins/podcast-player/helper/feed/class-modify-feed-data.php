<?php
/**
 * Sort & Filter Feed Data for output.
 *
 * @link       https://www.vedathemes.com
 * @since      1.0.0
 *
 * @package    Podcast_Player
 * @subpackage Podcast_Player/Helper
 */

namespace Podcast_Player\Helper\Feed;

use Podcast_Player\Helper\Core\Singleton;

/**
 * Sort & Filter Feed Data for output.
 *
 * @package    Podcast_Player
 * @subpackage Podcast_Player/Helper
 * @author     vedathemes <contact@vedathemes.com>
 */
class Modify_Feed_Data extends Singleton {

	/**
	 * Init method.
	 *
	 * @since  3.3.0
	 *
	 * @param array $data Fetched feed data to modified.
	 * @param array $mods Modification args.
	 */
	public function init( $data, $mods ) {

		// Prepare feed modification data.
		$defaults = $this->get_mod_defaults();
		$mods     = wp_parse_args( $mods, $defaults );

		// Get feed items.
		$feed_items = $data['items'];
		if ( ! $feed_items ) {
			return false;
		}

		/**
		 * Perform additional filter or custom operations on fetched data.
		 *
		 * @since 2.8.0
		 *
		 * @param array $feed_items All fetched feed items.
		 * @param array $mods       Additional args supplied.
		 */
		$items = apply_filters( 'podcast_player_episode_filters', $feed_items, $mods );

		// Return if no items left after applying custom filters.
		if ( ! $items || empty( $items ) ) {
			return array( null, array() );
		}

		// Filter feed items where item title contains a specific text ($filterby).
		$items = $this->filter_data( $items, $mods['filterby'] );

		// Return if no items left after applying filterby filter.
		if ( ! $items || empty( $items ) ) {
			return array( null, array() );
		}

		// Get total available items after applying all filters.
		$total_items = count( $items );

		// Sort filtered items by data or title.
		$items = $this->sort_data( $items, $mods['sortby'] );

		// Move fixed item to top of the list (if available in the list).
		$items = $this->move_fixed_item_to_top( $items, $mods['fixed'] );

		// Get required number of items.
		$items = $this->get_required_items( $items, $mods['start'], $mods['end'] );

		return array( $total_items, $items );
	}

	/**
	 * Mod defaults.
	 *
	 * @since  3.3.0
	 */
	private function get_mod_defaults() {
		return array(
			'start'    => 0,
			'end'      => 0,
			'filterby' => '',
			'sortby'   => 'none',
			'fixed'    => false,
		);
	}

	/**
	 * Filter feed items if title contains a specific text.
	 *
	 * @since  3.3.0
	 *
	 * @param array  $items    Primary filtered feed items.
	 * @param string $filterby Episodes to be filtered by.
	 */
	private function filter_data( $items, $filterby ) {

		// Return if there is no filterby criteria.
		if ( ! $filterby ) {
			return $items;
		}

		// Check if item title contains a specific ($filterby) text.
		return array_filter(
			$items,
			function( $item ) use ( $filterby ) {
				$item_title = strtolower( $item['title'] );
				$filterby   = strtolower( $filterby );
				return false !== strpos( $item_title, $filterby );
			}
		);
	}

	/**
	 * Sort all filtered items.
	 *
	 * @since  3.3.0
	 *
	 * @param array  $items  Filtered feed items.
	 * @param string $sortby Episodes to be sorted by.
	 */
	private function sort_data( $items, $sortby ) {
		$allowed_sort_order = array(
			'sort_title_desc',
			'sort_title_asc',
			'sort_date_asc',
			'sort_date_desc',
		);

		if ( in_array( $sortby, $allowed_sort_order, true ) ) {
			uasort( $items, array( $this, $sortby ) );
		} elseif ( 'reverse_sort' === $sortby ) {
			$items = array_reverse( $items );
		}

		return $items;
	}

	/**
	 * Check and move fixed item on top of the list.
	 *
	 * @since  3.3.0
	 *
	 * @param array  $items     Filtered and sorted feed items.
	 * @param string $fixed_key Fixed item key.
	 */
	private function move_fixed_item_to_top( $items, $fixed_key ) {

		// Check and move the fixed item to top of the list.
		if ( $fixed_key && isset( $items[ $fixed_key ] ) ) {
			$items = array( $fixed_key => $items[ $fixed_key ] ) + $items;
		}
		return $items;
	}

	/**
	 * Get required items from the array.
	 *
	 * @since  3.3.0
	 *
	 * @param array $items Filtered and sorted feed items.
	 * @param int   $start Start collecting items.
	 * @param int   $end   Stop collecting items.
	 */
	private function get_required_items( $items, $start, $end ) {

		// Slice the data as desired.
		if ( 0 === $end ) {
			return array_slice( $items, $start );
		} else {
			return array_slice( $items, $start, $end );
		}
	}

	/**
	 * Sorting callback for items title descending.
	 *
	 * @since 3.3.0
	 *
	 * @param array $a Feed item.
	 * @param array $b Feed item.
	 * @return boolean
	 */
	private function sort_title_desc( $a, $b ) {
		return $a['title'] <= $b['title'] ? 1 : -1;
	}

	/**
	 * Sorting callback for items title ascending.
	 *
	 * @since 3.3.0
	 *
	 * @param array $a Feed item.
	 * @param array $b Feed item.
	 * @return boolean
	 */
	private function sort_title_asc( $a, $b ) {
		return $a['title'] > $b['title'] ? 1 : -1;
	}

	/**
	 * Sorting callback for items date ascending.
	 *
	 * @since 3.3.0
	 *
	 * @param array $a Feed item.
	 * @param array $b Feed item.
	 * @return boolean
	 */
	private function sort_date_asc( $a, $b ) {
		return $a['date'] > $b['date'] ? 1 : -1;
	}

	/**
	 * Sorting callback for items date descending.
	 *
	 * @since 3.3.0
	 *
	 * @param array $a Feed item.
	 * @param array $b Feed item.
	 * @return boolean
	 */
	private function sort_date_desc( $a, $b ) {
		return $a['date'] <= $b['date'] ? 1 : -1;
	}
}
