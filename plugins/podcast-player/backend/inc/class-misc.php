<?php
/**
 * Podcast player miscellaneous actions.
 *
 * @link       https://www.vedathemes.com
 * @since      1.0.0
 *
 * @package    Podcast_Player
 */

namespace Podcast_Player\Backend\Inc;

use Podcast_Player\Helper\Functions\Getters as Get_Fn;
use Podcast_Player\Helper\Functions\Utility as Utility_Fn;
use Podcast_Player\Helper\Store\StoreManager;
use Podcast_Player\Helper\Store\FeedData;
use Podcast_Player\Helper\Core\Singleton;

/**
 * Display podcast player instance.
 *
 * @package    Podcast_Player
 * @author     vedathemes <contact@vedathemes.com>
 */
class Misc extends Singleton {

    /**
     * Initiate podcast player data storage.
     *
     * @since 6.5.0
     */
    public function init_storage() {
        $store_manager = StoreManager::get_instance();
        $store_manager->register();
    }

	/**
	 * Save feed episode images locally.
	 *
	 * @since 2.9.0
	 *
	 * @param string $fprint Feed footprint.
	 */
    public function save_images_locally( $fprint ) {

        // Check if new data format exists.
        $store_manager = StoreManager::get_instance();
        $feed_obj = $store_manager->get_podcast( $fprint );
        if ( $feed_obj instanceof FeedData ) {
            $this->save_images_locally_new( $feed_obj );
            return;
        }

        // Continue with the older method.
        $data_key = 'pp_feed_data_' . $fprint;
		$uploaded = false;

		// Get saved feed data.
		$feed_arr = get_option( $data_key );
		if ( ! $feed_arr ) {
			return;
		}

		set_time_limit( 540 ); // Give it 9 minutes.

		// Check and get podcast cover art image.
		if ( ! isset( $feed_arr['cover_id'] ) ) {
			if ( isset( $feed_arr['image'] ) && $feed_arr['image'] ) {
				$ctitle = isset( $feed_arr['title'] ) ? $feed_arr['title'] : '';
				$cid    = Utility_Fn::upload_image( $feed_arr['image'], $ctitle );
				if ( $cid ) {
					$feed_arr['cover_id'] = $cid;
					$uploaded             = true;
				}
			}
		}

		// Check and get podcast episodes featured images.
		$items      = $feed_arr['items'];
		$counter    = 0;
		$batch_size = 10;

		// Download images for 10 latest episodes.
		uasort(
			$items,
			function( $a, $b ) {
				return $a['date'] <= $b['date'];
			}
		);

		foreach ( $items as $item => $args ) {
			if ( $counter >= $batch_size ) {
				break;
			}
			if ( ! isset( $args['featured_id'] ) ) {
				if ( isset( $args['featured'] ) && $args['featured'] ) {
					$title = isset( $args['title'] ) ? $args['title'] : '';
					$id    = Utility_Fn::upload_image( $args['featured'], $title );
					if ( $id ) {
						$args['featured_id'] = $id;
						$items[ $item ]      = $args;
						$uploaded            = true;
						$counter++;
					}
				}
			}
		}

		if ( $uploaded ) {
			$feed_arr['items'] = $items;
			update_option( $data_key, $feed_arr, 'no' );
		}
    }

    /**
     * Upload images locally for new feed data type.
     *
     * @since 6.5.0
     *
     * @param object $feed FeedData object.
     */
    public function save_images_locally_new( $feed ) {
        $uploaded = false;
		set_time_limit( 540 ); // Give it 9 minutes.

        if ( ! $feed->get('cover_id') ) {
            $image = $feed->get('image');
            if ( $image ) {
                $cid = Utility_Fn::upload_image( $image, $feed->get('title') );
                if ( $cid ) {
                    $feed->set( 'cover_id', $cid );
                    $uloaded = true;
                }
            }
        }

		// Check and get podcast episodes featured images.
		$items      = $feed->get('items');
		$counter    = 0;
		$batch_size = 10;

		// Download images for 10 latest episodes.
		uasort(
			$items,
			function( $a, $b ) {
				return $a->get('date') <= $b->get('date');
			}
		);

		foreach ( $items as $key => $item ) {
			if ( $counter >= $batch_size ) {
				break;
            }

            if ( ! $item->get('featured_id') ) {
                $featured = $item->get('featured');
                if ( $featured ) {
                    $fid = Utility_Fn::upload_image( $featured, $item->get('title') );
                    if ( $fid ) {
                        $item->set( 'featured_id', $fid );
                        $uploaded = true;
                        $counter++;
                    }
                }
            }
		}

        if ( $uploaded ) {
            $store_manager = StoreManager::get_instance();
            $store_manager->update_podcast( $feed );
        }
    }

	/**
	 * Add plugin action links.
	 *
	 * Add actions links for better user engagement.
	 *
	 * @since 1.0.0
	 *
	 * @param  array $links List of existing plugin action links.
	 * @return array         List of modified plugin action links.
	 */
	public function action_links( $links ) {
		$links = array_merge(
			array(
				'<a href="' . esc_url( admin_url( 'admin.php?page=pp-options' ) ) . '">' . __( 'Settings', 'podcast-player' ) . '</a>',
			),
			$links
		);

		if ( defined( 'PP_PRO_VERSION' ) ) {
			return $links;
		}

		$links = array_merge(
			array(
				'<a href="' . esc_url( 'https://easypodcastpro.com/podcast-player/' ) . '" style="color: #35b747; font-weight: 700;">' . __( 'Get Pro', 'podcast-player' ) . '</a>',
			),
			$links
		);
		return $links;
	}

	/**
	 * Auto Update Podcast.
	 *
	 * @since 5.8.0
	 *
	 * @param string $feed_key Podcast feed key.
	 */
	public function auto_update_podcast( $feed_key ) {

		// Return if podcast has been deleted from the index.
		$feed_key = Get_Fn::get_feed_url_from_index( $feed_key );
		if ( false === $feed_key ) {
			return;
		}

		// Init feed fetch and update method.
		Get_Fn::get_feed_data( $feed_key );
	}

	/**
	 * Create REST API endpoints to get all pages list.
	 *
	 * @since 1.8.0
	 */
	public function register_routes() {
		register_rest_route(
			'podcastplayer/v1',
			'/fIndex',
			array(
				'methods'             => 'GET',
				'callback'            => function() {
					$feed_index = Get_Fn::get_feed_index();
					if ( $feed_index && is_array( $feed_index ) && ! empty( $feed_index ) ) {
						array_walk(
							$feed_index,
							function( &$val, $key ) {
								$val = isset( $val['title'] ) ? $val['title'] : '';
							}
						);
						$feed_index = array_filter( $feed_index );
						return array_merge(
							array( '' => esc_html__( 'Select a Podcast', 'podcast-player' ) ),
							$feed_index
						);
					}
					return array();
				},
				'permission_callback' => function () {
					return current_user_can( 'edit_posts' );
				},
			)
		);
	}

	/**
	 * TEMPORARY FUNCTION to Transfer podcast custom data from the options table to the post table.
	 *
	 * This function can be removed at a later stage once we are sure that all the podcast custom data is transferred.
	 *
	 * @since 6.6.0
	 */
	public function transfer_custom_data() {
		// Do not transfer data for PP pro version lower than 4.8.2.
		// TODO: Only for compatibility. Remove in next update.
		if ( defined( 'PP_PRO_VERSION' ) && version_compare( PP_PRO_VERSION, '4.8.2', '<' ) ) {
			return;
		}

		if ( false !== get_option( 'pp-custom-data-transferred' ) ) {
			return;
		}

		$store_manager = StoreManager::get_instance();
		$feed_index = Get_Fn::get_feed_index();
		if ( ! $feed_index || ! is_array( $feed_index ) ) {
			update_option( 'pp-custom-data-transferred', true );
			return;
		}

		foreach ( $feed_index as $key => $args ) {
			$data_key = 'pp_feed_data_custom_' . $key;
			$custom_data = get_option( $data_key );
			if ( ! $custom_data || ! is_array( $custom_data ) ) {
				continue;
			}
			$is_updated = $store_manager->update_custom_data( $key, $custom_data );
			if ($is_updated) {
				delete_option( $data_key );
				delete_option('pp_feed_data_' . $key);
			}
		}
		update_option( 'pp-custom-data-transferred', true );
	}
}
