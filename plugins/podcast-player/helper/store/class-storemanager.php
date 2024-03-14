<?php
/**
 * Base class to store podcast feed data.
 *
 * @link       https://easypodcastpro.com
 * @since      1.0.0
 *
 * @package    Podcast_Player
 * @subpackage Podcast_Player/Helper
 */

namespace Podcast_Player\Helper\Store;

use Podcast_Player\Helper\Core\Singleton;
use Podcast_Player\Helper\Store\FeedData;
use Podcast_Player\Helper\Store\StorageRegister;

/**
 * Store Manager Class
 *
 * @since 1.0.0
 */
class StoreManager extends Singleton {

    /**
     * Setup initial state of this class.
     *
     * @since 1.0.0
     */
    public function __construct() {
    }

    /**
     * Setup store manager.
     *
     * @since 1.0.0
     */
    public function register() {
        // Register post types to store all playlists and its data.
        $test = register_post_type(
            'podcast_player',
            array(
                'labels' => array(
                    'name' => __('Podcasts', 'podcast-player'),
                    'singular_name' => __('Podcast', 'podcast-player'),
                ),
                'query_var' => false,
            )
        );
    }

    /**
     * Get a stored podcast object.
     *
     * @since 1.0.0
     *
     * @param string $key Podcast unique ID or feed URL.
     */
    public function get_podcast( $key ) {
        $index = $this->get_podcast_index( $key );
        if ( ! $index instanceof StorageRegister ) {
            return false;
        }

        $object_id = $index->get( 'object_id' );
        if ( ! $object_id ) {
            // Podcast data was not saved properly. Therefore, let's delete it from Index.
            $this->delete_podcast( $key );
            return false;
        }

        $return = get_post_meta( $object_id, 'feed_data', true );
        if ( ! $return instanceof FeedData ) {
            return false;
        }
        return $return;
    }

    /**
     * Add a new Podcast or update an existing podcast.
     *
     * @since 1.0.0
     *
     * @param array  $data  Podcast feed data.
     * @param string $alias Alias URLs.
     */
    public function update_podcast( $data, $alias = false ) {
        // Data Validation.
        if ( ! $data instanceof FeedData ) {
            return new \WP_Error(
                'wrong-feed-data',
                __( 'Valid podcast data not provided.', 'podcast-player' )
            );
        }

        $url = $data->get( 'furl' );
        $index = $this->get_podcast_index( $url );

        if ( $index instanceof StorageRegister ) {
            $post_id = $index->get( 'object_id' );
            update_post_meta( $post_id, 'feed_data', $data );
            if ( $alias ) {
                $this->add_alias_to_podcast( $url, $alias );
            }
            return true;
        } else {
            return $this->add_new_podcast( $data, $alias );
        }
    }

    /**
     * Delete a stored podcast object.
     *
     * @since 1.0.0
     *
     * @param string $key Podcast unique ID or feed URL.
     */
    public function delete_podcast($key) {
        $return = false;
        $index = $this->get_podcast_index( $key );
        if ( ! $index instanceof StorageRegister ) {
            return $return;
        }
        $object_id = $index->get( 'object_id' );
        $uniqueId = $index->get( 'unique_id' );
        if ( $object_id && is_integer( $object_id ) ) {
            $return = wp_delete_post( $object_id, true );
        }
        $this->delete_podcast_from_index( $uniqueId );
        return $return;
    }

    /**
     * Adds a new podcast to the database.
     *
     * @since 1.0.0
     *
     * @param array  $data  Podcast feed data
     * @param string $alias Podcast feed url alias.
     */
    private function add_new_podcast( $data, $alias ) {
        // Create custom post object to save feed data.
        $post_id = wp_insert_post(
            array( 'post_type' => 'podcast_player', 'post_status' => 'publish' )
        );
        if ( is_wp_error( $post_id ) ) {
            return $post_id;
        }

        // Set post ID in the data. Self referencing.
        $data->set( 'post_id', $post_id );

        // Add data to the custom post object.
        $is_added = add_post_meta( $post_id, 'feed_data', $data );
        if ( false === $is_added ) {
            return new \WP_Error(
                'cant-save',
                __( 'Cannot Save Podcast Data to the Post Meta', 'podcast-player' )
            );
        }

        // Index the podcast.
        return $this->add_podcast_to_index($data, $alias);
    }

    /**
     * Index saved podcast.
     *
     * Add a new podcast to the index.
     *
     * @since 1.0.0
     *
     * @param array  $data  Podcast data array.
     * @param string $alias Podcast Feed Alias.
     */
    private function add_podcast_to_index( $data, $alias ) {
        $register = $this->get_register();
        $url = $data->get( 'furl' );
        $urls = array( $url );
        if ( $alias ) {
            $urls[] = $alias;
        }
        $id = md5( $url );
        $indexObj = new StorageRegister();
        $indexObj->set( 'unique_id', $id );
        $indexObj->set( 'title', $data->get( 'title' ) );
        $indexObj->set( 'feed_url', $urls );
        $indexObj->set( 'object_id', $data->get( 'post_id' ) );
        $register[$id] = $indexObj;
        update_option( "pp-register", $register, false );
        return true;
    }

    /**
     * Add Alias to the podcast register.
     *
     * @since 1.0.0
     *
     * @param string $url   Podcast feed URL.
     * @param string $alias Podcast Alias URL.
     */
    private function add_alias_to_podcast( $url, $alias ) {
        $register = $this->getRegister();
        $id = md5( $url );
        $index = $register[$id] ? $register[$id] : false;
        if (! $index instanceof StorageRegister) {
            return;
        }
        $urls = $index->get( 'feed_url' );
        $urls[] = (string) $alias;
        $index->set( 'feed_url', $urls );
        $register[$id] = $index;
        update_option( 'pp-register', $register, false );
        return true;
    }

    /**
     * Get stored podcast index object.
     *
     * @since 1.0.0
     *
     * @param string $key Podcast unique ID or feed URL.
     */
    public function get_podcast_index( $key ) {
        $key = apply_filters( 'podcast_player_index', $key );
        if ( ! $key ) {
            return false;
        }
        $register = $this->get_register();

        // If podcast key is provided instead of the URL.
        if ( isset( $register[$key] ) ) {
            return $register[$key];
        }

        // Get podcast key from the URL.
        $uniqueId = md5( $key );
        
        // Check and get podcast by unique ID.
        if ( isset( $register[$uniqueId] ) ) {
            return $register[$uniqueId];
        }
        
        // Deep search for the required podcast in the index.
        $feed = false;
        foreach ( $register as $podcast ) {
            $feed = $podcast->lookup( $key );
            if ( false !== $feed ) {
                break;
            }
        }
        return $feed;
    }

    /**
     * Get database register.
     *
     * @since 1.0.0
     */
    private function get_register() {
        $register = get_option( 'pp-register' );
        return false !== $register ? $register : array();
    }

    /**
     * Remove a podcast from the Index.
     *
     * @since 1.0.0
     *
     * @param string $key Podcast Identification Key.
     */
    private function delete_podcast_from_index( $key ) {
        $register = $this->get_register();
        if ( ! $key || ! isset( $register[$key] ) ) {
            return false;
        }
        unset( $register[$key] );
        update_option( 'pp-register', $register, false );
    }

    /**
     * Get podcast custom data.
     *
     * @since 6.6.0
     *
     * @param string $key Podcast unique ID or feed URL.
     */
    public function get_custom_data( $key ) {
        // TODO: Only for compatibility. Remove in next update.
        if ( defined( 'PP_PRO_VERSION' ) && version_compare( PP_PRO_VERSION, '4.8.2', '<' ) ) {
			$ckey        = 'pp_feed_data_custom_' . $key;
		    $custom_data = get_option( $ckey );
            if ( ! $custom_data || ! is_array( $custom_data ) ) {
                return false;
            }
            return $custom_data;
		}

        $index = $this->get_podcast_index( $key );
        if ( ! $index instanceof StorageRegister ) {
            return false;
        }

        $object_id = $index->get( 'object_id' );
        if ( ! $object_id ) {
            // Podcast data was not saved properly. Therefore, let's delete it from Index.
            $this->delete_podcast( $key );
            return false;
        }

        $return = get_post_meta( $object_id, 'custom_feed_data', true );
        return $return;
    }

    /**
     * Update podcast custom data.
     *
     * @since 6.6.0
     *
     * @param string $key Podcast unique ID or feed URL.
     * @param array  $custom_data Podcast custom data.
     */
    public function update_custom_data( $key, $custom_data ) {
        if (! $key || ! $custom_data || ! is_array( $custom_data ) ) {
            return false;
        }

        $index = $this->get_podcast_index( $key );

        if ( $index instanceof StorageRegister ) {
            $post_id = $index->get( 'object_id' );
            update_post_meta( $post_id, 'custom_feed_data', $custom_data );
            return true;
        }

        return false;
    }

    /**
     * Delete podcast custom data.
     *
     * @since 6.6.0
     *
     * @param string $key Podcast unique ID or feed URL.
     */
    public function delete_custom_data( $key ) {
        $index = $this->get_podcast_index( $key );
        if ( $index instanceof StorageRegister ) {
            $post_id = $index->get( 'object_id' );
            delete_post_meta( $post_id, 'custom_feed_data' );
            return true;
        }
    }
}
