<?php

namespace QuadLayers\IGG\Models;

use QuadLayers\IGG\Models\Base as Models_Base;

/**
 * Models_Feed Class
 */
class Feed extends Models_Base {

	/**
	 * Table name
	 *
	 * @var string
	 */
	protected $table = 'insta_gallery_feeds';
	/**
	 * Instagram URL
	 *
	 * @var string
	 */
	public $instagram_url = 'https://www.instagram.com';

	/**
	 * Function to get next feed id
	 *
	 * @return int
	 */
	protected function get_next_id() {
		$feeds = $this->get();
		if ( count( $feeds ) ) {
			return max( array_keys( $feeds ) ) + 1;
		}
		return 0;
	}

	/**
	 * Function to sanitize instagram feed
	 *
	 * @param string $feed Feed string to be sanitized.
	 * @return string
	 */
	protected function sanitize_instagram_feed( $feed ) {

		// Removing @, # and trimming input
		// ---------------------------------------------------------------------

		$feed = sanitize_text_field( $feed );

		$feed = trim( $feed );
		$feed = str_replace( '@', '', $feed );
		$feed = str_replace( '#', '', $feed );
		$feed = str_replace( $this->instagram_url, '', $feed );
		$feed = str_replace( '/explore/tags /', '', $feed );
		$feed = str_replace( '/', '', $feed );

		return $feed;
	}

	/* CRUD */

	/**
	 * Function to get default args
	 *
	 * @return array
	 */
	public function get_args() {
		return array(
			'id'          => 0,
			'account_id'  => '',
			'source'      => 'username',
			'tag'         => 'wordpress',
			'order_by'    => 'top_media',
			'layout'      => 'gallery',
			'limit'       => 12,
			'columns'     => 3,
			'lazy'        => false,
			'spacing'     => 10,
			'highlight'   => array(
				'tag'      => '',
				'id'       => '',
				'position' => '1,3,5',
			),
			'reel'        => array(
				'hide' => false,
			),
			'copyright'   => array(
				'hide'        => false, // Hide content with copyrights, it will be empty and only description will appear, if is true alert that maybe quanty of elements could be minor.
				'placeholder' => '',    // Show user placeholder instead content with copyrights
			),
			'profile'     => array(
				'display'      => false,
				'auto'         => false, // only for business token
				'username'     => '',
				'nickname'     => '',
				'website'      => '',
				'biography'    => '',
				'link_text'    => 'Follow',
				'website_text' => 'Website',
				'avatar'       => '',
					// 'followers_count'     => 0,
					// 'media_count'         => 0,
			),
			'box'         => array(
				'display'    => false,
				'padding'    => 1,
				'radius'     => 0,
				'background' => '#fefefe',
				'profile'    => false,
				'desc'       => '',
				'text_color' => '#000000',
			),
			'mask'        => array(
				'display'        => true,
				'background'     => '#000000',
				'likes_count'    => true,
				'comments_count' => true,
			),
			'card'        => array(
				'display'          => false,
				'radius'           => 1,
				'font_size'        => 12,
				'background'       => '#ffffff',
				'background_hover' => '#ffffff',
				'text_color'       => '#000000',
				'padding'          => 5,
				'likes_count'      => true,
				'text_length'      => 10,
				'comments_count'   => true,
				'text_align'       => 'left',
			),
			'carousel'    => array(
				'slidespv'          => 5,
				'autoplay'          => false,
				'autoplay_interval' => 3000,
				'navarrows'         => true,
				'navarrows_color'   => '',
				'pagination'        => true,
				'pagination_color'  => '',
			),
			'modal'       => array(
				'display'           => true,
				'profile'           => true,
				'media_description' => true,
				'likes_count'       => true,
				'comments_count'    => true,
				'text_align'        => 'left',
				'modal_align'       => 'right',
				'text_length'       => 10000,
				'font_size'         => 12,
			),
			'button'      => array(
				'display'          => true,
				'text'             => 'View on Instagram',
				'text_color'       => '#ffff',
				'background'       => '',
				'background_hover' => '',
			),
			'button_load' => array(
				'display'          => false,
				'text'             => 'Load more...',
				'text_color'       => '#ffff',
				'background'       => '',
				'background_hover' => '',
			),
		);
	}

	/**
	 * Function to get feed by id
	 *
	 * @param int $id Feed's id to look for.
	 * @return array
	 */
	public function get_by_id( $id ) {
		$feeds = $this->get();

		foreach ( $feeds as $feed ) {
			if ( $feed['id'] == $id ) {
				return $feed;
			}
		}

		if ( isset( $feeds[ $id ] ) ) {
			return $feeds[ $id ];
		}

		return null;
	}

	/**
	 * Function to get all feeds
	 *
	 * @return array
	 */
	public function get() {
		$feeds = $this->get_all();
		/**
		 * Make sure each account has all values
		 */
		if ( count( $feeds ) ) {
			foreach ( $feeds as $id => $feed ) {
				$feeds[ $id ] = array_replace_recursive( $this->get_args(), $feeds[ $id ] );

				/**
				 * Make sure account_id is string to prevent JS max int 16 chars error
				 */
				if ( isset( $feeds[ $id ]['account_id'] ) ) {
					$feeds[ $id ]['account_id'] = strval( $feeds[ $id ]['account_id'] );
				}
			}
		}
		return $feeds;
	}

	/**
	 * Function to create new feed
	 *
	 * @param array $feed_data New feed data.
	 * @return array|false
	 */
	public function create( $feed_data ) {

		$feed_id            = $this->get_next_id();
		$feed_data['id']    = $feed_id;
		$feed_data['order'] = $feed_id + 1;
		$feed_data['tag']   = $this->sanitize_instagram_feed( $feed_data['tag'] );

		$success = $this->save( $feed_data );

		if ( $success ) {
			return $feed_data;
		}

		return false;
	}

	/**
	 * Function to edit feed
	 *
	 * @param array $feed New feed data to replace old one.
	 * @return boolean
	 */
	public function edit( $feed ) {
		$feeds = $this->get_all();
		if ( $feeds ) {
			if ( count( $feeds ) > 0 ) {
				$new_feeds = array_map(
					function ( $f ) use ( $feed ) {
						return ( absint( $f['id'] ) === absint( $feed['id'] ) ? $feed : $f );
					},
					$feeds
				);
				$success   = $this->save_all( $new_feeds );
				if ( $success ) {
					return $success;
				}
			}
		}
	}

	/**
	 * Function to delete a feed
	 *
	 * @param int $id Feed id to be deleted.
	 * @return boolean
	 */
	public function delete( $id = null ) {
		$feeds = $this->get_all();
		if ( $feeds ) {
			if ( count( $feeds ) > 0 ) {

				if ( isset( $feeds[ $id ] ) ) {

					unset( $feeds[ $id ] );

					$success = $this->save_all( $feeds );
					if ( $success ) {
						return $success;
					}
					return false;
				}
			}
		}
		return false;
	}

	/**
	 * Function to save a feed
	 *
	 * @param array $feed_data Feed data to be saved.
	 * @return array|false
	 */
	protected function save( $feed_data = null ) {
		$feeds                     = $this->get();
		$feeds[ $feed_data['id'] ] = self::array_intersect_key_recursive( array_replace_recursive( $this->get_args(), $feed_data ), $this->get_args() );
		$success                   = $this->save_all( $feeds );
		if ( $success ) {
			return $feeds;
		}
		return false;
	}

	protected static function array_intersect_key_recursive( $array1, $array2 ) {
		$array1 = array_intersect_key( $array1, $array2 );
		foreach ( $array1 as $key => $value ) {
			if ( is_array( $value ) && is_array( $array2[ $key ] ) ) {
				$array1[ $key ] = self::array_intersect_key_recursive( $value, $array2[ $key ] );
			}
		}
		return $array1;
	}

	/**
	 * Function to delete table
	 *
	 * @return void
	 */
	public function delete_table() {
		$this->delete_all();
	}
}
