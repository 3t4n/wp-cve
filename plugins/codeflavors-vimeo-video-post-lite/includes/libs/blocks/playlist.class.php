<?php
namespace Vimeotheque\Blocks;
use Vimeotheque\Helper;
use Vimeotheque\Plugin;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Class Video
 * @package Vimeotheque\Blocks
 * @ignore
 */
class Playlist extends Block_Abstract implements Block_Interface {
	/**
	 * Video constructor.
	 *
	 * @param Plugin $plugin
	 */
	public function __construct( Plugin $plugin ) {
		parent::__construct( $plugin );

		parent::register_script(
			'vimeotheque-playlist-block',
			'playlist'
		);
		parent::register_style( 'vimeotheque-playlist-block', 'playlist', true );

		parent::register_block_type(
			'vimeotheque/video-playlist',
			[
				'attributes' => [
					'theme' => [
						'type' => 'string',
						'default' => 'default'
					],
					'layout' => [
						'type' => 'string',
						'default' => ''
					],
					'show_excerpts' => [
						'type' => 'boolean',
						'default' => false
					],
					'use_original_thumbnails' => [
						'type' => 'boolean',
						'default' => false,
					],
					'aspect_ratio' => [
						'type' => 'string',
						'default' => '16x9'
					],
					'width' => [
						'type' => 'string',
						'default' => 900
					],
					'align' => [
						'type' => 'string',
						'default' => 'align-left'
					],
					'volume' => [
						'type' => 'string',
						'default' => 70
					],
					'title' => [
						'type' => 'boolean',
						'default' => true
					],
					'byline' => [
						'type' => 'boolean',
						'default' => true
					],
					'portrait' => [
						'type' => 'boolean',
						'default' => true
					],
					'playlist_loop' => [
						'type' => 'boolean',
						'default' => false
					],
					'videos' => [
						'type' => 'array',
						'default' => [],
						'items' => [
							'type' => 'number'
						]
					],
					'post_ids' => [
						'type' => 'array',
						'default' => [],
						'items' => [
							'type' => 'number'
						]
					],
					'categories' => [
						'type' => 'array',
						'default' => [],
						'items' => [
							'type' => 'number'
						]
					],
					'cat_ids' => [
						'type' => 'array',
						'default' => [],
						'items' => [
							'type' => 'number'
						]
					],
					/**
					 * Posts order:
					 *
					 * - manual: posts displayed into the order that they were picked;
					 * - newest: posts displayed by date, descending
					 * - oldest: posts displayed by date, ascending
					 * - alphabetical: posts displayed alphabetically
					 */
					'order' => [
						'type' => 'string',
						'default' => 'manual'
					]
				],
				'editor_script' => parent::get_script_handle(),
				'editor_style' => parent::get_editor_style_handle(),
				'render_callback' => function( $attr ){
					$attr['videos'] = implode( ',', $attr['post_ids'] );
					$attr['categories'] = implode( ',', $attr['cat_ids'] );
					$playlist = new \Vimeotheque\Shortcode\Playlist();
					return $playlist->get_output( $attr, false );
				}
			]
		);

		add_action(
			'enqueue_block_editor_assets',
			[
				$this,
				'editor_assets'
			],
			-999999999
		);

		$this->set_rest_meta_queries();
	}

	/**
	 * Enqueue editor assets if needed
	 */
	public function editor_assets(){

		if( !parent::is_active() ){
			return;
		}

		$themes = Plugin::instance()->get_playlist_themes()->get_themes();
		$_themes = [];
		foreach( $themes as $key => $theme ){
			$_themes[] = [
				'label' => $theme->get_theme_name(),
				'value' => $key
			];

			wp_enqueue_script(
				'vimeotheque-player-' . strtolower( $key ),
				$theme->get_js_url(),
				[ parent::get_script_handle() ]
			);

			wp_enqueue_style(
				'vimeotheque-player-' . strtolower( $key ),
				$theme->get_style_url()
			);

		}

		$post_types = Plugin::instance()->get_registered_post_types()->get_post_types();
		$_post_types = [];
		foreach( $post_types as $post_type ){
			$_post_types[ $post_type->get_post_type()->name ] = [
				'post_type' => $post_type->get_post_type(),
				'taxonomy' => $post_type->get_taxonomy(),
				'post_type_endpoint' => $post_type->get_post_type_rest_endpoint(),
				'taxonomy_endpoint' => $post_type->get_taxonomy_rest_endpoint()
			];
		}

		$posts_order = [
			'manual' => __( 'Manual order', 'codeflavors-vimeo-video-post-lite' ),
		    'newest' => __( 'Newest first', 'codeflavors-vimeo-video-post-lite' ),
		    'oldest' => __( 'Oldest first', 'codeflavors-vimeo-video-post-lite' ),
		    'alphabetical' => __( 'Alphabetically', 'codeflavors-vimeo-video-post-lite' )
		];
		$_posts_order = [];
		foreach( $posts_order as $value => $label ){
			$_posts_order[] = [
				'label' => $label,
				'value' => $value
			];
		}

		wp_localize_script(
			parent::get_script_handle(),
			'vmtq',
			[
				'noImageUrl' => VIMEOTHEQUE_URL . 'assets/back-end/images/no-image.jpg',
				'themes' => $_themes,
				'postTypes' => $_post_types,
				'order' => $_posts_order
			]
		);

		Helper::enqueue_player( true, parent::get_script_handle(), parent::get_editor_style_handle() );

		wp_enqueue_style(
			'bootstrap-grid2',
			VIMEOTHEQUE_URL . 'assets/back-end/css/vendor/bootstrap.css',
			[ parent::get_editor_style_handle() ]
		);
	}

	/**
	 * By default, REST api doesn't allow queries from React to be made for meta keys.
	 * Register meta query queries for post types.
	 * @see \WP_REST_Posts_Controller::get_items() line 269
	 */
	private function set_rest_meta_queries(){
		$post_types = [ 'post', Plugin::instance()->get_cpt()->get_post_type() ];
		$taxonomies = ['category', Plugin::instance()->get_cpt()->get_post_tax() ];

		foreach( $post_types as $post_type ){
			add_filter( 'rest_' . $post_type . '_query', [ $this, 'meta_queries' ], 10, 2 );
		}

		foreach( $taxonomies as $taxonomy ){
			add_filter( 'rest_' . $taxonomy . '_query', [ $this, 'tax_queries' ], 10, 2 );
		}
	}

	/**
	 * @see Playlist::set_rest_meta_queries()
	 *
	 * @param array $args
	 * @param \WP_REST_Request $request
	 *
	 * @return mixed
	 */
	public function meta_queries( $args, $request ){
		if( $request->get_param( 'vimeothequeMetaKey' ) ){
			$args['meta_query'] = [
				[
					'key'     => Plugin::instance()->get_cpt()->get_post_settings()->get_meta_video_data(),
					'compare' => 'EXISTS'
				]
			];
		}

		if( $request->get_param( 'vimeothequeAllPostType' ) ){
			$args['post_type'] = [
				'post', Plugin::instance()->get_cpt()->get_post_type()
			];
		}

		return $args;
	}

	public function tax_queries( $args, $request ){
		return $args;
	}
}