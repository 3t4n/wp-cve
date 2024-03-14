<?php
namespace QuadLayers\IGG\Api\Fetch\Personal\User_Media;

use QuadLayers\IGG\Api\Fetch\Personal\Base;
use QuadLayers\IGG\Helpers as Helpers;

/**
 * Api_Fetch_Personal_User_Media
 */
class Get extends Base {

	/**
	 * Function to get media data from feed.
	 *
	 * @param string  $access_token Account access_token.
	 * @param int     $limit Feed limit.
	 * @param string  $after After param to query pagination.
	 * @param boolean $hide_items_with_copyright Feed copyright['hide'] to set visibility of media with copyright.
	 * @param boolean $hide_reels Feed reel['hide'] to set visibility of reels.
	 * @return array $data
	 */
	public function get_data( $access_token = null, $limit = null, $after = null, $hide_items_with_copyright = null, $hide_reels = null ) {
		$response = $this->get_response( $access_token, $limit, $after );
		$data     = $this->response_to_data( $response, $hide_items_with_copyright, $hide_reels );
		return $data;
	}

	/**
	 * Function to get item media file data
	 *
	 * @param array   $item Item to get media url.
	 * @param boolean $hide_items_with_copyright Property to hide item if has copyright.
	 * @return array|null
	 */
	protected function get_item_media_file_data( array $item = array(), $hide_items_with_copyright = false ) {
		if ( isset( $item['media_type'] ) ) {
			switch ( $item['media_type'] ) {
				case 'IMAGE':
					if ( isset( $item['media_url'] ) ) {
							return [ $item['media_url'], 'IMAGE' ];
					}
					break;
				case 'VIDEO':
					if ( isset( $item['media_url'] ) ) {
							return [ $item['media_url'], 'VIDEO' ];
					}
					break;
				case 'CAROUSEL_ALBUM':
					// If $hide_items_with_copyright is true, verify that item has children with valid url, else return first children url or null.
					if ( $hide_items_with_copyright ) {
						$children = array_values(
							array_filter(
								$item['children']['data'],
								function( $child ) {
									if ( isset( $child['media_url'] ) ) {
										return [ $child['media_url'], $child['media_type'] ];
									}
								}
							)
						);
						if ( isset( $children[0]['media_url'] ) ) {
							return [ $children[0]['media_url'], $children[0]['media_type'] ];
						}
					}
					if ( isset( $item['children']['data'][0]['media_url'] ) ) {
						return [ $item['children']['data'][0]['media_url'], $item['children']['data'][0]['media_type'] ];
					}
					return null;
					break;
			}
		}

		return false;
	}

	/**
	 * Function to get item media url and type
	 *
	 * @param array   $item Feed element.
	 * @param boolean $hide_items_with_copyright Property to hide item if has copyright.
	 * @return array
	 */
	public function get_item_media( $item = null, $hide_items_with_copyright = null ) {

		$media_file_url = $this->get_item_media_file_data( $item, $hide_items_with_copyright );

		if ( ! $media_file_url && false === $hide_items_with_copyright ) {
			return array(
				null,
				null,
			);
		}
		return $media_file_url;
	}

	/**
	 * Function to set items into required structure
	 *
	 * @param array   $items Array of raw items.
	 * @param boolean $hide_items_with_copyright Property to hide item if has copyright.
	 * @param boolean $hide_reels Property to hide item if is a reel.
	 * @return array
	 */
	protected function set_items_data( $items, $hide_items_with_copyright, $hide_reels ) {

		$filter_items = Helpers::array_reduce(
			$items,
			function( $carry, $key, $item ) use ( $hide_items_with_copyright, $hide_reels ) {

				list( $media_file_url, $media_file_type ) = $this->get_item_media( $item, $hide_items_with_copyright );

				// If $hide_reels is true, do not return type media reel
				if ( $hide_reels && isset( $item['permalink'] ) && strpos( $item['permalink'], 'https://www.instagram.com/reel/' ) !== false ) {
					return $carry;
				}

				// If $hide_items_with_copyright is true and $media_file_url, skip to next item
				if ( ! $media_file_url && $hide_items_with_copyright ) {
					return $carry;
				}

				if ( isset( $item['caption'] ) ) {
					$item['caption'] .= ' ';
					preg_match_all(
						'/(?=#)(.*?)(?=\s)+/',
						htmlspecialchars( $item['caption'] ),
						$tags
					);
				}

				$item = array_filter(
					array(
						'media'             => array(
							'url'       => $media_file_url,
							'thumbnail' => isset( $item['media_type'] ) && 'VIDEO' === $item['media_type'] ? $item['thumbnail_url'] : '',
							'type'      => $media_file_type,
						),
						'user_type'         => 'PERSONAL',
						'media_type'        => isset( $item['media_type'] ) ? $item['media_type'] : '',
						'id'                => isset( $item['id'] ) ? $item['id'] : '',
						'share_url'         => isset( $item['permalink'] ) ? $item['permalink'] : '',
						'media_description' => isset( $item['caption'] ) ? $item['caption'] : '',
						'tags'              => isset( $tags ) ? $tags[0] : '',
						'comments_count'    => isset( $item['comments_count'] ) ? $item['comments_count'] : '',
						'likes_count'       => isset( $item['like_count'] ) ? $item['like_count'] : '',
						'children'          => isset( $item['children']['data'] ) ? $this->set_items_data(
							$item['children']['data'],
							$hide_items_with_copyright,
							$hide_reels
						) : array(),
						'date'              => isset( $item['timestamp'] ) ? date_i18n( 'j F, Y', strtotime( trim( str_replace( array( 'T', '+', ' 0000' ), ' ', $item['timestamp'] ) ) ) ) : '',
					)
				);

				array_push( $carry, $item );
				return $carry;
			},
			array()
		);

		return $filter_items;
	}

	/**
	 * Function to parse response to usable data.
	 *
	 * @param array   $response Raw response from instagram.
	 * @param boolean $hide_items_with_copyright Property to hide item if has copyright.
	 * @param boolean $hide_reels Property to hide item if is a reel.
	 * @return array
	 */
	public function response_to_data( $response = null, $hide_items_with_copyright = null, $hide_reels = null ) {

		if ( isset( $response['data'], $response['paging'] ) ) {
			$feed_items_data = $this->set_items_data( $response['data'], $hide_items_with_copyright, $hide_reels );

			$response = array(
				'data'   => $feed_items_data,
				'paging' => array(
					'prev' => isset( $response['paging']['previous'] ) ? $response['paging']['cursors']['before'] : '',
					'next' => isset( $response['paging']['next'] ) ? $response['paging']['cursors']['after'] : '',
				),
			);
		}

		return $response;
	}

	/**
	 * Function to query instagram data.
	 *
	 * @param string $access_token Account access_token.
	 * @param int    $limit Feed limit.
	 * @param string $after After param to query pagination.
	 * @return array
	 */
	public function get_response( $access_token = null, $limit = null, $after = null ) {
		$url = $this->get_url( $access_token, $limit, $after );

		$response = wp_remote_get(
			$url,
			array(
				'timeout' => 30,
			)
		);
		$response = $this->handle_response( $response );

		return $response;
	}

	/**
	 * Function to build query url.
	 *
	 * @param string $access_token Account access_token.
	 * @param int    $limit Feed limit.
	 * @param string $after After param to query pagination.
	 * @return string
	 */
	public function get_url( $access_token = null, $limit = null, $after = null ) {
		$url = add_query_arg(
			array(
				'after'        => $after,
				'limit'        => $limit,
				'fields'       => 'media_url,thumbnail_url,caption,id,media_type,timestamp,username,permalink,children{media_url,id,media_type,timestamp,permalink,thumbnail_url}',
				'access_token' => $access_token,
			),
			"{$this->api_url}/media"
		);

		return $url;
	}

}
