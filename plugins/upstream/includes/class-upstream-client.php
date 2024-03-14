<?php
/**
 * UpStream_Client Class
 *
 * @package UpStream
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * UpStream_Client Class
 *
 * @since 1.0
 */
class UpStream_Client {

	/**
	 * The client ID
	 *
	 * @var int $ID Client ID.
	 * @since 1.0
	 */
	public $ID = 0;

	/**
	 * The meta prefix
	 *
	 * @var string $meta_prefix Meta prefix.
	 * @since 1.0
	 */
	public $meta_prefix = '_upstream_client_';

	/**
	 * The meta keys
	 *
	 * @var array $meta Meta keys.
	 * @since 1.0
	 */
	public $meta = array(
		'logo',
		'logo_id',
		'address',
		'phone',
		'website',
		'users',
	);

	/**
	 * Declare the default properties in WP_Post as we can't extend it
	 * Anything we've declared above has been removed.
	 */

	/**
	 * The post author
	 *
	 * @var int Post author.
	 */
	public $post_author = 0;

	/**
	 * The post date
	 *
	 * @var string Post date.
	 */
	public $post_date = '0000-00-00 00:00:00';

	/**
	 * The post date in GMT
	 *
	 * @var string Post date in GMT.
	 */
	public $post_date_gmt = '0000-00-00 00:00:00';

	/**
	 * The post content
	 *
	 * @var string Post content.
	 */
	public $post_content = '';

	/**
	 * The post title
	 *
	 * @var string Post title.
	 */
	public $post_title = '';

	/**
	 * The post excerpt
	 *
	 * @var string Post excerpt.
	 */
	public $post_excerpt = '';

	/**
	 * The post status
	 *
	 * @var string Post status.
	 */
	public $post_status = 'publish';

	/**
	 * The comment status
	 *
	 * @var string Comment status.
	 */
	public $comment_status = 'open';

	/**
	 * The ping status
	 *
	 * @var string Ping status.
	 */
	public $ping_status = 'open';

	/**
	 * The post password
	 *
	 * @var string Post password.
	 */
	public $post_password = '';

	/**
	 * The post name
	 *
	 * @var string Post name.
	 */
	public $post_name = '';

	/**
	 * To ping
	 *
	 * @var string To ping.
	 */
	public $to_ping = '';

	/**
	 * Pinged
	 *
	 * @var string Pinged.
	 */
	public $pinged = '';

	/**
	 * Post modified date
	 *
	 * @var string Post modified date.
	 */
	public $post_modified = '0000-00-00 00:00:00';

	/**
	 * Post modified date GMT
	 *
	 * @var string Post modified date GMT.
	 */
	public $post_modified_gmt = '0000-00-00 00:00:00';

	/**
	 * Post content filtered
	 *
	 * @var string Post content filtered.
	 */
	public $post_content_filtered = '';

	/**
	 * Post parent
	 *
	 * @var string Post parent.
	 */
	public $post_parent = 0;

	/**
	 * Post GUID
	 *
	 * @var string Post GUID.
	 */
	public $guid = '';

	/**
	 * Menu order
	 *
	 * @var string Menu order.
	 */
	public $menu_order = 0;

	/**
	 * Post mime type
	 *
	 * @var string Post mime type.
	 */
	public $post_mime_type = '';

	/**
	 * Comment count
	 *
	 * @var int Comment count.
	 */
	public $comment_count = 0;

	/**
	 * Filter
	 *
	 * @var int Filter.
	 */
	public $filter;

	/**
	 * Get things going
	 *
	 * @param mixed $_id Client ID or false.
	 * @param array $_args Query argument.
	 * @since 1.0
	 */
	public function __construct( $_id = false, $_args = array() ) {
		// if no id is sent, then go through the varous ways of getting the id
		// may need to check the order more closely to ensure we get it right.
		if ( ! $_id ) {
			$user_id = upstream_current_user_id();
			$_id     = upstream_get_users_client_id( $user_id );
		}

		if ( ! $_id ) {
			$_id = upstream_project_client_id();
		}

		$client = WP_Post::get_instance( $_id );

		return $this->setup_client( $client );
	}

	/**
	 * Given the client data, let's set the variables
	 *
	 * @since  2.3.6
	 *
	 * @param  object $client The Client Object.
	 *
	 * @return bool   If the setup was successful or not
	 */
	private function setup_client( $client ) {
		if ( ! is_object( $client ) ) {
			return false;
		}

		if ( ! is_a( $client, 'WP_Post' ) ) {
			return false;
		}

		if ( 'client' !== $client->post_type ) {
			return false;
		}

		// sets the value of each key.
		foreach ( $client as $key => $value ) {
			switch ( $key ) {
				default:
					$this->$key = $value;
					break;
			}
		}

		return true;
	}

	/**
	 * Get a meta value
	 *
	 * @since 1.0.0
	 *
	 * @param string $meta the meta field (without prefix).
	 *
	 * @return mixed
	 */
	public function get_meta( $meta ) {
		$result = get_post_meta( $this->ID, $this->meta_prefix . $meta, true );
		if ( ! $result ) {
			$result = null;
		}

		return $result;
	}
}
