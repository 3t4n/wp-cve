<?php

/**
 * Fontend Ajax
 * Handles all ajax requests for frontend area
 *
 * @since 1.7.6
 * @package EasyVideoReviews
 */

// Namespace.
namespace EasyVideoReviews;

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit( 1 );


if ( ! class_exists( __NAMESPACE__ . '\Ajax' ) ) {
	/**
	 * Frontend Ajax
	 *
	 * @since 1.7.6
	 */
	class Ajax extends \EasyVideoReviews\Base\Controller {

		// Use utilities trait.
		use \EasyVideoReviews\Traits\Utilities;

		/**
		 * Prefix for all ajax actions
		 *
		 * @var string
		 */
		protected $prefix = 'evr_';

		/**
		 * Registers all ajax hooks
		 *
		 * @return void
		 */
		public function register_hooks() {
			add_action( 'wp_ajax_' . $this->prefix . 'update_gallery_settings', [ $this, 'update_gallery_settings' ]);
			add_action( 'wp_ajax_nopriv' . $this->prefix . 'update_gallery_settings', [ $this, 'update_gallery_settings' ]);
		}

        /**
		 * Updates user settings
		 *
		 * @return void
		 */
        public function update_gallery_settings() {

			// Check nonce.
			check_ajax_referer( 'evr_nonce', 'nonce');

			$old_gallery_exist = false;

			$galleries = get_option( 'evr_gallaries', [] );

			$old_galleris_iputs = $this->io()->get_inputs();

			if ( 0 < count($galleries) ) {
				foreach ( $galleries as $gallery ) {
					if ( isset( $gallery['previusFolder'] ) ) {
						if ( $gallery['previusFolder'] === $old_galleris_iputs['previusFolder'] ) {
							$old_gallery_exist = true;
						}
					}
				}
			}

			if ( ! $old_gallery_exist || 0 === count($galleries) ) {
				$galleries[] = $old_galleris_iputs;
				update_option('evr_gallaries', $galleries);
			}
            $this->io()->send_json( true, __( 'Settings updated', 'easy-video-reviews' ), $galleries);
        }
    }

    // Instantiate.
	Ajax::init();
}
