<?php

namespace Vimeotheque\Shortcode;

use Vimeotheque\Helper;
use Vimeotheque\Playlist\Theme\Theme;
use Vimeotheque\Plugin;
use Vimeotheque\Video_Post;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Class Playlist
 * @package Vimeotheque\Shortcode
 * @ignore
 */
class Playlist extends Shortcode_Abstract implements Shortcode_Interface {
	/**
	 * @var null
	 */
	private $options = null;
	/**
	 * @var \WP_Post
	 */
	private $posts = [];

	/**
	 * @param \WP_Post $posts
	 */
	public function set_posts( $posts ){
		foreach ( $posts as $post ) {
			if( $post instanceof \WP_Post ){
				$_post = Helper::get_video_post( $post );
				if( $_post->is_video() ){
					$this->posts[] = $_post;
				}
			}
		}
	}

	/**
	 * @param $atts
	 * @param $content
	 *
	 * @return string|void
	 */
	public function get_output( $atts, $content ){
		parent::set_atts( $atts );
		parent::set_content( $content );

		$videos = $this->posts ?: $this->get_video_posts();
		if( !$videos ){
			return;
		}

		ob_start();

		global $CVM_PLAYER_SETTINGS;
		$embed_options = $this->get_embed_options();
		$embed_options['show_excerpts'] = is_wp_error( parent::get_attr( 'show_excerpts') ) ? false : parent::get_attr( 'show_excerpts');

		// Do not allow lazy loading on playlists
		$embed_options['lazy_load'] = false;
		// Do not autoplay on first load
		$embed_options['autoplay'] = 0;

		$CVM_PLAYER_SETTINGS = array_merge( $atts, $embed_options );

		/**
		 * @var Video_Post $cvm_video
		 */
		global $cvm_video;

		$handles = Helper::enqueue_player();

		$theme = $this->get_theme();
		if( !$theme ){
			$theme = Plugin::instance()->get_playlist_themes()->get_theme('default');
		}

		// include theme file
		include $theme->get_file();

		wp_enqueue_script(
			'vimeotheque-player-' . strtolower( $theme->get_folder_name() ) ,
			$theme->get_js_url(),
			/**
			 * Vimeotheque playlist theme script dependencies filter.
			 *
			 * @param array $handles        The registered JavaScript handles.
			 * @param string $script_handle The script handle.
			 */
			apply_filters(
				'vimeotheque-theme-' . strtolower( $theme->get_folder_name() ) . '-script-dependencies',
				$handles['js']
			),
			'1.0'
		);

		wp_enqueue_style(
			'vimeotheque-player-' . strtolower( $theme->get_folder_name() ) ,
			$theme->get_style_url(),
			false,
			'1.0'
		);

		$content = ob_get_contents();
		ob_end_clean();

		// remove custom player settings
		$CVM_PLAYER_SETTINGS = false;

		return $content;
	}

	/**
	 * Shortcode defaults
	 *
	 * @return array
	 */
	private function get_embed_options(){
		if( $this->options ){
			return $this->options;
		}

		$this->options = Plugin::instance()->get_embed_options_obj()->get_options();
		foreach( $this->options as $key => $value ){
			$attr = parent::get_attr( $key );
			if( !is_wp_error( $attr ) ){
				// some options have value 0 or 1 and need to be processed this way
				if( in_array( $value, [0, 1] ) ){
					$attr = absint( $attr );
				}

				$this->options[ $key ] = $attr;
			}
		}

		return $this->options;
	}

	/**
	 * @return mixed|Theme
	 */
	private function get_theme(){
		$theme = parent::get_attr('theme');
		if( !$theme instanceof Theme ){
			$theme = Plugin::instance()->get_playlist_themes()->get_theme( $theme );
		}
		return $theme;
	}

	/**
	 * Get videos IDs from attributes
	 *
	 * @return array|mixed
	 */
	private function get_video_ids(){
		$video_ids = parent::get_attr('post_ids');
		if( is_wp_error( $video_ids ) ){
			$videos = parent::get_attr('videos');
			if( !is_wp_error( $videos ) ) {
				$video_ids = explode( ',', $videos );
			}
		}
		return $video_ids;
	}

	/**
	 * Get categories IDs from attributes
	 *
	 * @return array|mixed
	 */
	private function get_categories_ids(){
		$cat_ids = parent::get_attr( 'cat_ids' );
		if( is_wp_error( $cat_ids ) ){
			$categories = parent::get_attr( 'categories' );
			if( !is_wp_error( $categories ) ){
				$cat_ids = explode( ',', $categories );
			}
		}
		return $cat_ids;
	}

	/**
	 * @return array
	 */
	private function get_video_posts(){
		$posts = [];
		$videos = $this->get_video_ids();
		if( $videos && !is_wp_error( $videos ) ){

			$atts = array_merge(
				[
					/**
					 * Filter that allows setup of post types to be queried for videos.
					 *
					 * @param array $post_types The post types that will be queried.
					 */
					'post_type' => apply_filters(
						'vimeotheque\shortcode\post_types',
						[ Plugin::instance()->get_cpt()->get_post_type() ]
					),
					'include' => $videos,
					'posts_per_page' => count( $videos ),
					'numberposts' => count( $videos ),
					'post_status' => ['publish'],
					'suppress_filters' => true
				],
				$this->get_order_params()
			);

			$_posts = get_posts( $atts );

			if( $_posts && !is_wp_error( $_posts ) ){
				foreach( $_posts as $post ){
					$_post = Helper::get_video_post( $post );
					if( $_post->is_video() ){
						$key = array_search( $post->ID, $videos);
						$posts[ $key ] = $_post;
					}
				}

				if( is_wp_error( parent::get_attr( 'order' ) ) || 'manual' == parent::get_attr( 'order' ) ) {
					ksort( $posts );
				}
			}
		}

		$categories = $this->get_categories_ids();
		if( !is_wp_error( $categories ) ){
			$post_type = parent::get_attr( 'post_type' );
			if( $post_type && !is_wp_error( $post_type ) ){
				$_post_type = explode( ',', $post_type );
			}else{
				$_post_type = false;
			}

			$_posts = $this->get_category_post_ids( $categories, $_post_type );
			if( $_posts ){
				$_posts = array_diff_key( $_posts, $posts );
				$posts = array_merge( $posts, $_posts );
			}

		}

		return array_values( $posts );
	}

	/**
	 * Get the ordering parameters for the query arguments
	 *
	 * @return array    The ordering parameters for the query
	 */
	private function get_order_params(){
		$order = parent::get_attr( 'order' );
		$_order = [];

		if( is_wp_error( $order ) ){
			return $_order;
		}

		switch( $order ){
			case 'newest':
				$_order['orderby'] = 'date';
				$_order['order'] = 'DESC';
			break;
			case 'oldest':
				$_order['orderby'] = 'date';
				$_order['order'] = 'ASC';
			break;
			case 'alphabetical':
				$_order['orderby'] = 'post_title';
				$_order['order'] = 'ASC';
			break;
            case 'manual':
                $_order['orderby'] = 'menu_order';
                $_order['order'] = 'ASC';
            break;
		}

		return $_order;
	}

	/**
	 * Returns all post ids for the given categories
	 *
	 * @param array $categories - array of terms IDs
	 * @param $post_type
	 *
	 * @return array|void
	 */
	protected function get_category_post_ids( /*array*/ $categories, /*array*/ $post_type ){
		if( !is_array( $categories ) || !$categories ){
			return;
		}

		$posts = [];

		// if newest videos should be returned, return them
		if( in_array( '0', $categories ) ){

			/**
			 * Changes number of posts.
			 *
			 * Filter that allows changing of number of posts displayed into a playlist block or shortcode.
			 *
			 * @param int $max_posts    Maximum number of posts to retrieve.
			 */
			$post_num = apply_filters( 'vimeotheque\shortcode\playlist\newest_max_posts', 10 );

			$args = array_merge(
				[
					'post_type' => $post_type,
					'numberposts' => $post_num,
					'order' => 'DESC',
					'orderby' => 'post_date'
				],
				$this->get_order_params()
			);

			$p = get_posts( $args );
			if( $p && !is_wp_error( $p ) ){
				foreach( $p as $post ){
					$_post = Helper::get_video_post( $post );
					if( $_post->is_video() ) {
						$posts[ $post->ID ] = $_post;
					}
				}
			}
			return $posts;
		}

		$terms = [];
		foreach( $categories as $term_id ){
			$term = get_term( $term_id );
			if( $term && !is_wp_error( $term ) ){
				$terms[ $term->taxonomy ][] = $term->term_id;
			}
		}

		if( $terms ){
			$args = array_merge(
				[
					'post_type' => $this->get_post_types_by_taxonomy( array_keys( $terms ) ),
					'numberposts' => -1,
					'tax_query' => [
						'relation' => 'OR',
					],
					'order' => 'DESC',
					'orderby' => 'post_date'
				],
				$this->get_order_params()
			);

			foreach( $terms as $taxonomy => $term_ids ){
				$args['tax_query'][] = [
					'taxonomy' => $taxonomy,
					'field' => 'term_id',
					'terms' => $term_ids
				];
			}

			$p = get_posts( $args );
			if( $p && !is_wp_error( $p ) ){
				foreach( $p as $post ){
					$_post = Helper::get_video_post( $post );
					if( $_post->is_video() ) {
						$posts[ $post->ID ] = $_post;
					}
				}
			}
		}

		return $posts;
	}

	/**
	 * @param array $taxonomies
	 *
	 * @return array
	 */
	protected function get_post_types_by_taxonomy( $taxonomies ){
		$out = [];

		foreach( $taxonomies as $tax ){
			$taxonomy = get_taxonomy( $tax );
			if( $taxonomy ){
				$out = array_merge( $out, $taxonomy->object_type );
			}
		}

		return $out;
	}

}