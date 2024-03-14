<?php

namespace Vimeotheque\Post;

use Vimeotheque\Helper;
use Vimeotheque\Plugin;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Custom post type class. Manages post registering, taxonomies, data saving
 * @ignore
 */
class Post_Type{

	/**
	 * Video post type
	 *
	 * @var string
	 */
	private $post_type = 'vimeo-video';

	/**
	 * Video cateogry taxonomy
	 *
	 * @var string
	 */
	private $taxonomy = 'vimeo-videos';

	/**
	 * Video custom post type tag taxonomy
	 *
	 * @var string
	 */
	private $tag = 'vimeo-tag';

	/**
	 * @var Post_Settings
	 */
	protected $post_settings;

	/**
	 * @var Plugin
	 */
	private $plugin;
	/**
	 * @var \WP_Error|\WP_Post_Type
	 */
	private $wp_post_type;
	/**
	 * @var \WP_Error|\WP_Taxonomy
	 */
	private $category_taxonomy;

	/**
	 * Constructor, initiates post type registering
	 *
	 * @param Plugin $plugin
	 */
	public function __construct( Plugin $plugin ){
		// custom post type registration and messages
		add_action( 'init', [ $this, 'register_post' ], 1 );
		// custom post type messages
		add_filter('post_updated_messages', [ $this, 'updated_messages' ] );

		$this->plugin = $plugin;
		$this->post_settings = new Post_Settings( $this );
	}

	/**
	 * Register video post type and taxonomies
	 */
	public function register_post(){
		$labels = [
			'name' 					=> _x('Vimeo Videos', 'Vimeo Videos', 'codeflavors-vimeo-video-post-lite'),
	    	'singular_name' 		=> _x('Vimeo Video', 'Vimeo Video', 'codeflavors-vimeo-video-post-lite'),
	    	'add_new' 				=> _x('Add new', 'video', 'codeflavors-vimeo-video-post-lite'),
	    	'add_new_item' 			=> __('Add new video', 'codeflavors-vimeo-video-post-lite'),
	    	'edit_item' 			=> __('Edit video', 'codeflavors-vimeo-video-post-lite'),
	    	'new_item'				=> __('New video', 'codeflavors-vimeo-video-post-lite'),
	    	'all_items' 			=> __('All videos', 'codeflavors-vimeo-video-post-lite'),
	    	'view_item' 			=> __('View', 'codeflavors-vimeo-video-post-lite'),
	    	'search_items' 			=> __('Search', 'codeflavors-vimeo-video-post-lite'),
	    	'not_found' 			=> __('No videos found', 'codeflavors-vimeo-video-post-lite'),
	    	'not_found_in_trash' 	=> __('No videos in trash', 'codeflavors-vimeo-video-post-lite'),
	    	'parent_item_colon' 	=> '',
	    	'menu_name' 			=> __('Videos', 'codeflavors-vimeo-video-post-lite')
		];
		
		$options 	= \Vimeotheque\Plugin::instance()->get_options();
		$is_public 	= $options['public'];
		
		$args = [
    		'labels' 				=> $labels,
    		'public' 				=> $is_public,
			'exclude_from_search'	=> !$is_public,
    		'publicly_queryable' 	=> $is_public,
			'show_in_nav_menus'		=> $is_public,
		
    		'show_ui' 				=> true,
			'show_in_menu' 			=> true,
			'menu_position' 		=> 5,
			'menu_icon'				=> VIMEOTHEQUE_URL . 'assets/back-end/images/video.png',
		
    		'query_var' 			=> true,
    		'capability_type' 		=> 'post',
    		'has_archive' 			=> $is_public, 
    		'hierarchical' 			=> false,
    		
			// REST support
			'show_in_rest' 			=> true,
							
			'rewrite'				=> [
				'slug' => $options['post_slug']
			],
		
    		'supports' 				=> [
				'title', 
    			'editor', 
    			'author', 
    			'thumbnail', 
    			'excerpt', 
    			'trackbacks',
				'custom-fields',
    			'comments',  
    			'revisions',
    			'post-formats'
		    ],
		];
 		
 		$this->wp_post_type = register_post_type( $this->post_type, $args );

  		// Add new taxonomy, make it hierarchical (like categories)
  		$cat_labels = [
	    	'name' 					=> _x( 'Vimeo Video categories', 'Vimeo Video categories', 'codeflavors-vimeo-video-post-lite' ),
	    	'singular_name' 		=> _x( 'Vimeo Video category', 'Vimeo Video category', 'codeflavors-vimeo-video-post-lite' ),
	    	'search_items' 			=>  __( 'Search video category', 'codeflavors-vimeo-video-post-lite' ),
	    	'all_items' 			=> __( 'All video categories', 'codeflavors-vimeo-video-post-lite' ),
	    	'parent_item' 			=> __( 'Video category parent', 'codeflavors-vimeo-video-post-lite' ),
	    	'parent_item_colon'		=> __( 'Video category parent:', 'codeflavors-vimeo-video-post-lite' ),
	    	'edit_item' 			=> __( 'Edit video category', 'codeflavors-vimeo-video-post-lite' ),
	    	'update_item' 			=> __( 'Update video category', 'codeflavors-vimeo-video-post-lite' ),
	    	'add_new_item' 			=> __( 'Add new video category', 'codeflavors-vimeo-video-post-lite' ),
	    	'new_item_name' 		=> __( 'Video category name', 'codeflavors-vimeo-video-post-lite' ),
	    	'menu_name' 			=> __( 'Categories', 'codeflavors-vimeo-video-post-lite' ),
	    ];

		$this->category_taxonomy = register_taxonomy( $this->taxonomy, [ $this->post_type ], [
			'public'			=> $is_public,
    		'show_ui' 			=> true,
			'show_in_nav_menus' => $is_public,
			'show_admin_column' => true,		
			'hierarchical' 		=> true,
			// REST support
			'show_in_rest' 		=> true,
			'rewrite' 			=> [
				'slug' => $options['taxonomy_slug']
			],
			'capabilities'		=> [ 'edit_posts' ],
    		'labels' 			=> $cat_labels,    		
    		'query_var' 		=> true
		] );

		/**
		 * Prior to WP version 5.4, function register_taxonomy always returned null.
		 */
		if( version_compare( get_bloginfo( 'version' ), '5.4', '<' ) ){
			$this->category_taxonomy = get_taxonomy( $this->taxonomy );
		}

  		// tags
  		$tag_labels = [
	    	'name' 					=> _x( 'Vimeo Video tags', 'Vimeo Video tags', 'codeflavors-vimeo-video-post-lite' ),
	    	'singular_name' 		=> _x( 'Vimeo Video tag', 'Vimeo Video tag', 'codeflavors-vimeo-video-post-lite' ),
	    	'search_items' 			=>  __( 'Search video tag', 'codeflavors-vimeo-video-post-lite' ),
	    	'all_items' 			=> __( 'All video tags', 'codeflavors-vimeo-video-post-lite' ),
	    	'parent_item' 			=> __( 'Video tag parent', 'codeflavors-vimeo-video-post-lite' ),
	    	'parent_item_colon'		=> __( 'Video tag parent:', 'codeflavors-vimeo-video-post-lite' ),
	    	'edit_item' 			=> __( 'Edit video tag', 'codeflavors-vimeo-video-post-lite' ),
	    	'update_item' 			=> __( 'Update video tag', 'codeflavors-vimeo-video-post-lite' ),
	    	'add_new_item' 			=> __( 'Add new video tag', 'codeflavors-vimeo-video-post-lite' ),
	    	'new_item_name' 		=> __( 'Video tag name', 'codeflavors-vimeo-video-post-lite' ),
	    	'menu_name' 			=> __( 'Tags', 'codeflavors-vimeo-video-post-lite' ),
	    ];

  		register_taxonomy( $this->tag, [ $this->post_type ], [
			'public'			=> $is_public,
    		'show_ui' 			=> true,
			'show_in_nav_menus' => $is_public,
			'show_admin_column' => true,		
			'hierarchical' 		=> false,
  			// REST support
  			'show_in_rest' 		=> true,
			'rewrite' 			=> [
				'slug' => $options['tag_slug']
			],
			'capabilities'		=> [ 'edit_posts' ],
    		'labels' 			=> $tag_labels,    		
    		'query_var' 		=> true
	    ] );
	}

	/**
	 * Custom post type messages on edit, update, create, etc.
	 *
	 * @param array $messages
	 *
	 * @return array|void
	 */
	public function updated_messages( $messages ){
		global $post, $post_ID;

		$_post = Helper::get_video_post( $post );
		if( !$_post->get_post() || !$_post->is_video() ){
			return;
		}
		
		$vid_id = isset( $_GET['video_id'] ) ? $_GET['video_id'] : '';
		
		$messages[ $this->post_type ] = [
			0 => '', // Unused. Messages start at index 1.
	    	1 => sprintf(
	    		'%s %s',
	    		__('Video updated', 'codeflavors-vimeo-video-post-lite'),
			    sprintf(
			    	'<a href="%s">%s</a>',
				    esc_url( get_permalink($post_ID) ),
				    __( 'See video', 'codeflavors-vimeo-video-post-lite' )
			    )
		    ),
	    	2 => __('Custom field updated.', 'codeflavors-vimeo-video-post-lite'),
	    	3 => __('Custom field deleted.', 'codeflavors-vimeo-video-post-lite'),
	    	4 => __('Video updated.', 'codeflavors-vimeo-video-post-lite'),
	   		/* translators: %s: date and time of the revision */
	    	5 => isset($_GET['revision']) ? sprintf( __('Video restored to version %s', 'codeflavors-vimeo-video-post-lite'), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
	    	6 => sprintf(
			    '%s %s',
			    __('Video published', 'codeflavors-vimeo-video-post-lite'),
			    sprintf(
				    '<a href="%s">%s</a>',
				    esc_url( get_permalink($post_ID) ),
				    __( 'See video', 'codeflavors-vimeo-video-post-lite' )
			    )
		    ),
	    	7 => __('Video saved.', 'codeflavors-vimeo-video-post-lite'),
	    	8 => sprintf(
			    '%s %s',
			    __('Video saved', 'codeflavors-vimeo-video-post-lite'),
			    sprintf(
				    '<a href="%s">%s</a>',
				    esc_url( add_query_arg( 'preview', 'true', get_permalink($post_ID) ) ),
				    __( 'See video', 'codeflavors-vimeo-video-post-lite' )
			    )
		    ),
	    	9 => sprintf(
				'%s %s',
				sprintf(
					__('Video will be published at: %s.', 'codeflavors-vimeo-video-post-lite'),
					sprintf(
						'<strong>%s</strong>',
						date_i18n(
							// translators: Publish box date format, see http://php.net/date
							__( 'M j, Y @ G:i', 'codeflavors-vimeo-video-post-lite' ),
							strtotime( $post->post_date )
						)
					)
				),
				sprintf(
					'<a href="%s">%s</a>',
					esc_url( add_query_arg( 'preview', 'true', get_permalink($post_ID) ) ),
					__( 'See video', 'codeflavors-vimeo-video-post-lite' )
				)
			),
	    	10 => sprintf(
		        '%s %s',
		        __('Video draft saved.', 'codeflavors-vimeo-video-post-lite'),
		        sprintf(
			        '<a href="%s">%s</a>',
			        esc_url( add_query_arg( 'preview', 'true', get_permalink($post_ID) ) ),
			        __( 'See video', 'codeflavors-vimeo-video-post-lite' )
		        )
	        ),
	    	101 => __('Please select a source', 'codeflavors-vimeo-video-post-lite'),
	    	102 => sprintf(
	    		__( 'Vimeo video with ID %s is already imported. You are now editing the existing video.', 'codeflavors-vimeo-video-post-lite' ),
			    sprintf(
			    	'<strong><em>%s</em></strong>',
				    $vid_id
			    )
		    )
		];
	    
		return $messages;
	}

	/**
	 * Return post type
	 */
	public function get_post_type(){
		return $this->post_type;
	}

	/**
	 * Return taxonomy
	 */
	public function get_post_tax(){
		return $this->taxonomy;
	}
	
	/**
	 * Returns tags taxonomy
	 */
	public function get_tag_tax(){
		return $this->tag;
	}

	/**
	 * @return Post_Settings
	 */
	public function get_post_settings(){
		return $this->post_settings;
	}

	/**
	 * @return Plugin
	 */
	public function get_plugin(){
		return $this->plugin;
	}

	/**
	 * @return \WP_Error|\WP_Post_Type
	 */
	public function get_wp_post_type_object(){
		return $this->wp_post_type;
	}

	/**
	 * @return \WP_Error|\WP_Taxonomy
	 */
	public function get_category_taxonomy_object() {
		return $this->category_taxonomy;
	}
}