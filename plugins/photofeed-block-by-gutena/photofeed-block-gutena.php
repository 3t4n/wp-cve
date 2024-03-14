<?php // @codingStandardsIgnoreLine
/**
 * Plugin Name:     Gutena PhotoFeed
 * Description:     PhotoFeed Gallery Block by Gutena
 * Version:         1.0.3
 * Author:          ExpressTech
 * License:         GPL-2.0-or-later
 * License URI:     https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:     photofeed-block-gutena
 *
 * @package         photofeed-block-gutena
 */

defined( 'ABSPATH' ) || exit;

/**
 * Abort if the class is already exists.
 */
if ( ! class_exists( 'Gutena_Instagram_Gallery' ) ) {

	/**
	 * Gutena Instagram Gallery class.
	 *
	 * @class Main class of the plugin.
	 */
	class Gutena_Instagram_Gallery {

		/**
		 * Plugin version.
		 *
		 * @var string
		 */
		public $version = '1.0.3';

		/**
		 * Instance of this class.
		 *
		 * @since 1.0.0
		 * @var object
		 */
		protected static $instance;

		/**
		 * Get the singleton instance of this class.
		 *
		 * @since 1.0.0
		 * @return Gutena_Instagram_Gallery
		 */
		public static function get() {
			if ( ! ( self::$instance instanceof self ) ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		/**
		 * Constructor
		 *
		 * @since 1.0.0
		 */
		public function __construct() {
			add_action( 'init', [ $this, 'register' ] );
			add_action( 'wp_ajax_gutena_get_instagram_images', [ $this, 'get_images' ] );
			add_filter( 'block_categories_all', [ $this, 'register_category' ], 10, 2 );
		}

		/**
		 * Register required functionalities.
		 */
		public function register() {
			// Register blocks.
			register_block_type( __DIR__ . '/build', [
				'render_callback' => [ $this, 'render_block' ],
			] );

			// Localize assets.
			wp_localize_script( 'gutena-instagram-gallery-editor-script', 'gutenaInstagramGalleryBlock', [
				'ajax_url' => admin_url( 'admin-ajax.php' ),
				'nonce'    => wp_create_nonce( 'gutena_instagram_gallery' ),
			] );
		}

		/**
		 * Render Gutena Instagram Gallery field block.
		 */
		public function render_block( $attributes, $content, $block ) {
			$wrapper_attributes = get_block_wrapper_attributes();

			$attributes = wp_parse_args( $attributes, [
				'instagramToken' => '',
				'columns'        => 2,
				'count'          => 6,
				'gridGap'        => '1.5rem',
				'hoverColor'     => '#008CBA',
				'hoverEffect'    => 'none',
				'opacity'        => '1',
				'linkType'       => 'none',
				'linkTarget'     => '_blank',
				'showCaption'    => true, 
			] );

			$images = $this->get_instagram_images( $attributes['instagramToken'] );
			if ( false !== $images['error'] ) {
				return;
			}

			$images = array_slice( $images['data'], 0, $attributes['count'] );

			$output = '<div class="photofeed-blocks-grid columns-' . esc_attr( $attributes['columns'] ) . '" style="--gutena--photofeed-block-gap: ' . esc_attr( $attributes['gridGap'] ) . ';--gutena--photofeed-image-hover-color: ' . esc_attr( $attributes['hoverColor'] ) . ';--gutena--photofeed-image-hover-opacity: ' . esc_attr( $attributes['opacity'] ) . ';">';
			foreach ( $images as $image ) {
				$output .= '<div class="photofeed-block-item">';
					$content = '<figure class="photofeed-block-item-inner ' . esc_attr( $attributes['hoverEffect'] ) . '">';
						$content .= '<img src="' . esc_url( $image['media_url'] ) . '" alt="' . ( ! empty( $image['caption'] ) ? esc_attr( wp_trim_words( $image['caption'], 10 ) ) : '' ) . '" class="instagram-image" />';
						$content .= '<figcaption class="overlay">';
							if ( $attributes['showCaption'] ) {
								$content .= '<div class="text">' . ( ! empty( $image['caption'] ) ? esc_attr( $image['caption'] ) : '' ) . '</div>';
							}
						$content .= '</figcaption>';
					$content .= '</figure>';
					if ( 'none' !== $attributes['linkType'] ) {
						$content = '<a href="' . ( 'media' === $attributes['linkType'] ? esc_url( $image['media_url'] ) : esc_url( $image['permalink'] ) ) .'" target="' .  esc_attr( $attributes['linkTarget'] ) . '" rel="noreferrer">' . $content . '</a>';
					}
					$output .= $content;
				$output .= '</div>';
			}
            $output .= '</div>';

			return sprintf(
				'<div %1$s>%2$s</div>',
				$wrapper_attributes,
				$output
			);
		}

		/**
		 * Get Instagram images via AJAX.
		 */
        public function get_images() {
			check_ajax_referer( 'gutena_instagram_gallery', 'nonce' );

			$access_token = isset( $_POST['access_token'] ) ? sanitize_text_field( wp_unslash( $_POST['access_token'] ) ) : '';
			if ( empty( $access_token ) || 'undefined' === $access_token ) {
				wp_send_json( [
					'status'  => 'error',
					'message' => __( 'Please add Instagram Access Token!', 'photofeed-block-gutena' ),
				] );
			}

			$images = $this->get_instagram_images( $access_token );
			
			if ( false !== $images['error'] ) {
				wp_send_json( [
					'status'  => 'error',
					'message' => $images['error'],
				] );
			}

			wp_send_json( [
				'status' => 'success',
				'images' => $images['data'],
			] );
        }

		/**
		 * Get Instagram images.
		 */
		private function get_instagram_images( $access_token ) {
			$cache = '60';
			$media_key = 'gutena_insta_'. md5( str_replace( '.', '_', $access_token ) . $cache );
			$media_data = get_transient( $media_key );

			if ( false === $media_data || ! is_array( $media_data ) || empty( $media_data ) ) {
				$response = wp_remote_get( 'https://graph.instagram.com/me/media?fields=username,id,caption,media_type,media_url,permalink,thumbnail_url,timestamp&limit=200&access_token=' . $access_token );

				$response_data = json_decode( wp_remote_retrieve_body( $response ) );

				// Check status code and abort if code is not 200.
				if ( 200 !== wp_remote_retrieve_response_code( $response ) ) {
					$data = [];
					if ( ! empty( $response_data->error ) && ! empty( $response_data->error->message ) ) {
						$data['error'] = $response_data->error->message;
					} else {
						$data['error'] = wp_remote_retrieve_response_message( $response );
					}
					return $data;
				}

				if ( ! empty( $response_data->data ) ) {
					$media_data = [];
					foreach ( $response_data->data as $media ) {
						if ( 'IMAGE' === $media->media_type ) {
							$media_data[] = array_map( 'sanitize_text_field', array_map( 'wp_unslash', (array) $media ) );
						}
					}
					set_transient( $media_key, $media_data, ( $cache * MINUTE_IN_SECONDS ) );
				}
			}

			return [
				'error' => false,
				'data'  => $media_data,
			];
        }

		/**
		 * Register block category.
		 */
		public function register_category( $block_categories, $editor_context ) {
			$fields = wp_list_pluck( $block_categories, 'slug' );
			
			if ( ! empty( $editor_context->post ) && ! in_array( 'gutena', $fields, true ) ) {
				array_push(
					$block_categories,
					[
						'slug'  => 'gutena',
						'title' => __( 'Gutena', 'photofeed-block-gutena' ),
					]
				);
			}

			return $block_categories;
		}
	}
}

/**
 * Check the existance of the function.
 */
if ( ! function_exists( 'gutena_instagram_gallery_init' ) ) {
	/**
	 * Returns the main instance of Gutena_Instagram_Gallery to prevent the need to use globals.
	 *
	 * @return Gutena_Instagram_Gallery
	 */
	function gutena_instagram_gallery_init() {
		return Gutena_Instagram_Gallery::get();
	}

	// Start it.
	gutena_instagram_gallery_init();
}

// Gutena Ecosystem init.
if ( file_exists( __DIR__ . '/includes/gutena/gutena-ecosys-onboard/gutena-ecosys-onboard.php' ) ) {
	require_once  __DIR__ . '/includes/gutena/gutena-ecosys-onboard/gutena-ecosys-onboard.php';
}