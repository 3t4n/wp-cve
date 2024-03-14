<?php

namespace Vimeotheque\Vimeo_Api;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Class Resources_Objects
 * @package Vimeotheque\Admin
 * @ignore
 */
class Resource_Objects{
	/**
	 * @var Resource_Abstract[]
	 */
	private $resources = [];

	/**
	 * Sorting options
	 *
	 * @var array
	 */
	private $sort_options = [];

	/**
	 * Holds the plugin instance.
	 *
	 * @var Resource_Objects
	 */
	private static $instance = null;

	/**
	 * Clone.
	 *
	 * Disable class cloning and throw an error on object clone.
	 *
	 * The whole idea of the singleton design pattern is that there is a single
	 * object. Therefore, we don't want the object to be cloned.
	 *
	 * @access public
	 */
	public function __clone() {
		// Cloning instances of the class is forbidden.
		_doing_it_wrong( __FUNCTION__, esc_html__( 'Something went wrong.', 'codeflavors-vimeo-video-post-lite' ), '2.0' );
	}

	/**
	 * Wakeup.
	 *
	 * Disable unserializing of the class.
	 *
	 * @access public
	 */
	public function __wakeup() {
		// Unserializing instances of the class is forbidden.
		_doing_it_wrong( __FUNCTION__, esc_html__( 'Something went wrong.', 'codeflavors-vimeo-video-post-lite' ), '2.0' );
	}

	/**
	 * Instance.
	 *
	 * Ensures only one instance of the plugin class is loaded or can be loaded.
	 *
	 * @access public
	 * @static
	 *
	 * @return Resource_Objects
	 */
	public static function instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Resources_Objects constructor.
	 */
	private function __construct() {
		// set sorting options
		$this->set_sort_options();

		// register resources
		$this->register_resource( new Album_Resource( false ) );
		// Duplicate for Album_Resources to add the "showcase" type (otherwise, implements the exact functionality of Album_Resource).
		$this->register_resource( new Showcase_Resource( false ) );
		$this->register_resource( new Category_Resource( false ) );
		$this->register_resource( new Channel_Resource( false ) );
		$this->register_resource( new Group_Resource( false ) );
		$this->register_resource( new Portfolio_Resource( false ) );
		$this->register_resource( new Search_Resource( false ) );
		$this->register_resource( new Thumbnails_Resource( false ) );
		$this->register_resource( new User_Resource( false ) );
		$this->register_resource( new Video_Resource( false ) );
	}

	/**
	 * Set sorting options
	 */
	private function set_sort_options(){
		$this->sort_options = [
			'new' => [
				'label' => __( 'Newest', 'codeflavors-vimeo-video-post-lite' ),
				'sort' => 'date',
				'direction' => 'desc',
				'resources' => []
			],
			'alphabetical_asc' => [
				'label' => __( 'Alphabetical &#x1F81D;', 'codeflavors-vimeo-video-post-lite' ),
				'sort' => 'alphabetical',
				'direction' => 'asc',
				'resources' => []
			],
			'alphabetical_desc' => [
				'label' => __( 'Alphabetical &#x1F81F;', 'codeflavors-vimeo-video-post-lite' ),
				'sort' => 'alphabetical',
				'direction' => 'desc',
				'resources' => []
			],

			'duration' => [
				'label' => __( 'Duration', 'codeflavors-vimeo-video-post-lite' ),
				'sort' => 'duration',
				'direction' => 'desc',
				'resources' => []
			],
			'old' => [
				'label' => __( 'Oldest', 'codeflavors-vimeo-video-post-lite' ),
				'sort' => 'date',
				'direction' => 'asc',
				'resources' => []
			],
			'played' => [
				'label' => __( 'Plays', 'codeflavors-vimeo-video-post-lite' ),
				'sort' => 'plays',
				'direction' => 'desc',
				'resources' => []
			],
			'likes'	=> [
				'label' => __( 'Likes', 'codeflavors-vimeo-video-post-lite' ),
				'sort' => 'likes',
				'direction' => 'desc',
				'resources' => []
			],
			'comments' => [
				'label' => __( 'Comments', 'codeflavors-vimeo-video-post-lite' ),
				'sort' => 'comments',
				'direction' => 'desc',
				'resources' => []
			],
			'relevant' => [
				'label' => __( 'Relevancy', 'codeflavors-vimeo-video-post-lite' ),
				'sort' => 'relevant',
				'direction' => 'desc',
				'resources' => []
			],
			'default' => [
				'label' => __( 'Default order', 'codeflavors-vimeo-video-post-lite' ),
				'sort' => 'default',
				'direction' => false,
				'resources' => []
			]
 		];
	}

	/**
	 * Registers a given resource sorting options
	 *
	 * @param Resource_Interface $resource
	 */
	private function register_sort_options( Resource_Interface $resource ){
		$_sort_options = $resource->get_sort_options();

		foreach( $this->sort_options as $k => $option ){
			if( in_array( $option['sort'], $_sort_options ) ){
				$this->sort_options[ $k ]['resources'][ $resource->get_name() ] = $resource;
			}
		}
	}

	/**
	 * @return array
	 */
	public function get_sort_options() {
		return $this->sort_options;
	}

	/**
	 * Return a sort option from $this->sort_options
	 *
	 * @param string $option
	 *
	 * @return array
	 */
	public function get_sort_option( $option ){
		if( isset( $this->sort_options[ $option ] ) ){
			return $this->sort_options[ $option ];
		}

		return $this->sort_options['new'];
	}

	/**
	 * @param Resource_Interface $resource
	 */
	public function register_resource( Resource_Interface $resource ){
		$this->resources[ $resource->get_name() ] = $resource;
		$this->register_sort_options( $resource );
	}

	/**
	 * @return Resource_Abstract[]
	 */
	public function get_resources(){
		return $this->resources;
	}

	/**
	 * @param $name
	 *
	 * @return Resource_Interface|Resource_Abstract|\WP_Error
	 */
	public function get_resource( $name ){
		if( !isset( $this->resources[ $name ] ) ){
			return new \WP_Error(
				'vimeotheque-api-query-resource-unknown',
				sprintf( __( 'Resource %s is not registered.', 'codeflavors-vimeo-video-post-lite' ), $name )
			);
		}

		return $this->resources[ $name ];
	}
}