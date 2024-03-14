<?php
/**
 * Save Feed Data fetched from Feed XML file.
 *
 * @link       https://www.vedathemes.com
 * @since      1.0.0
 *
 * @package    Podcast_Player
 * @subpackage Podcast_Player/Helper
 */

namespace Podcast_Player\Helper\Feed;

use Podcast_Player\Helper\Store\StoreManager;
use Podcast_Player\Helper\Functions\Getters as Get_Fn;

/**
 * Save Feed Data fetched from Feed XML file.
 *
 * @package    Podcast_Player
 * @subpackage Podcast_Player/Helper
 * @author     vedathemes <contact@vedathemes.com>
 */
class Save_Feed_New {

	/**
	 * Holds option index key.
	 *
	 * @since  3.3.0
	 * @access private
	 * @var    string
	 */
	private $optkey = 'pp_feed_index';


    /**
	 * Holds feed unique identification key.
	 *
	 * @since  3.3.0
	 * @access private
	 * @var    string
	 */
	private $fkey = '';

	/**
	 * Holds fetched feed data to be saved.
	 *
	 * @since  3.3.0
	 * @access private
	 * @var    array
	 */
	private $fdata = array();

	/**
	 * Holds feed url value.
	 *
	 * @since  3.3.0
	 * @access private
	 * @var    string
	 */
	private $furl = '';

	/**
	 * Constructor method.
	 *
	 * @since  3.3.0
	 *
	 * @param array  $data Fetched feed data to be saved.
	 * @param string $key  Unique feed identification key.
	 * @param string $url  Feed URL.
	 * @param bool   $is_changed Check if feed data has been changed.
	 */
	public function __construct( $data, $key, $url, $is_changed ) {
		$this->fdata = $data;
		$this->fkey  = $key;
		$this->furl  = $url;

		if ( $is_changed ) {
			$this->create_time_transient();
			$this->save_feed_data();
			$this->index_feed();
		} else {
			$this->create_time_transient();
		}
	}

	/**
	 * Constructor method.
	 *
	 * @since  3.3.0
	 */
	public function create_time_transient() {

		$cache_time = absint( Get_Fn::get_plugin_option( 'refresh_interval' ) );

		/** This filter is documented in wp-includes/class-wp-feed-cache-transient.php */
		$life = apply_filters( 'wp_feed_cache_transient_lifetime', $cache_time * 60, $this->furl );

		$time_key = 'pp_feed_time_' . $this->fkey;
		set_transient( $time_key, time(), $life );
	}

	/**
	 * Constructor method.
	 *
	 * @since  3.3.0
	 */
    public function save_feed_data() {
        $store = StoreManager::get_instance();
        $store->update_podcast( $this->fdata );
    }

	/**
	 * Constructor method.
	 *
	 * @since  3.3.0
	 */
	public function index_feed() {
		$option = get_option( $this->optkey );
		$finfo  = $this->get_feed_info();

		if ( ! $option || ! is_array( $option ) ) {
			$option = array( $this->fkey => $finfo );
		} elseif ( ! isset( $option[ $this->fkey ] ) ) {
			$option[ $this->fkey ] = $finfo;
		} else {
			$option[ $this->fkey ]['title'] = $finfo['title'];
			$option[ $this->fkey ]['url']   = $finfo['url'];
		}

		if ( $option ) {
			update_option( $this->optkey, $option, 'no' );
		}
	}

	/**
	 * Get current feed index entry array.
	 *
	 * @since  3.3.0
	 */
	public function get_feed_info() {
		return array(
			'title' => $this->fdata->get('title'),
			'url' => $this->fdata->get('furl'),
		);
	}
}
