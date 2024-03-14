<?php

namespace QuadLayers\IGG\Frontend;

use QuadLayers\IGG\Models\Feed as Models_Feed;
use QuadLayers\IGG\Models\Setting as Models_Settings;

use QuadLayers\IGG\Api\Rest\Endpoints\Frontend\User_Profile as Api_Rest_User_Profile;
use QuadLayers\IGG\Api\Rest\Endpoints\Frontend\User_Media as Api_Rest_User_Media;
use QuadLayers\IGG\Api\Rest\Endpoints\Frontend\Hashtag_Media as Api_Rest_Hashtag_Media;

/**
 * Frontend Class
 */
class Load {

	protected static $instance;

	private function __construct() {
		add_action( 'wp_enqueue_scripts', array( $this, 'register_scripts' ) );
		add_shortcode( 'insta-gallery', array( $this, 'do_shortcode' ) );
	}

	public function register_scripts() {

		$models_settings = new Models_Settings();

		$settings = $models_settings->get();

		$frontend = include QLIGG_PLUGIN_DIR . 'build/frontend/js/index.asset.php';

		/**
		 * Swiper
		 */
		wp_register_style( 'qligg-swiper', plugins_url( '/assets/frontend/swiper/swiper.min.css', QLIGG_PLUGIN_FILE ), null, QLIGG_PLUGIN_VERSION );
		wp_register_script( 'qligg-swiper', plugins_url( '/assets/frontend/swiper/swiper.min.js', QLIGG_PLUGIN_FILE ), array( 'jquery' ), QLIGG_PLUGIN_VERSION, true );

		/**
		 * Instagram
		 */
		wp_register_style( 'qligg-frontend', plugins_url( '/build/frontend/css/style.css', QLIGG_PLUGIN_FILE ), array(), QLIGG_PLUGIN_VERSION );
		wp_register_script( 'qligg-frontend', plugins_url( '/build/frontend/js/index.js', QLIGG_PLUGIN_FILE ), $frontend['dependencies'], $frontend['version'], true );

		wp_localize_script(
			'qligg-frontend',
			'qligg_frontend',
			array(
				'settings'       => $settings,
				'restRoutePaths' => array(
					'username'    => Api_Rest_User_Media::get_rest_url(),
					'tag'         => Api_Rest_Hashtag_Media::get_rest_url(),
					'userprofile' => Api_Rest_User_Profile::get_rest_url(),
				),
			)
		);
	}

	public function create_shortcode( $feed, $id = null ) {

		if ( ! isset( $feed['layout'] ) ) {
			return;
		}

		$id = isset( $id ) ? $id : $feed['id'];

		$feed_layout = $feed['layout'];

		if ( in_array( $feed_layout, array( 'masonry', 'highlight', 'highlight-square' ) ) ) {
			wp_enqueue_script( 'masonry' );
		}

		if ( strpos( $feed_layout, 'carousel' ) !== false ) {
			wp_enqueue_style( 'qligg-swiper' );
			wp_enqueue_script( 'qligg-swiper' );
		}

		wp_enqueue_style( 'qligg-frontend' );
		wp_enqueue_script( 'qligg-frontend' );
		ob_start();
		?>
		<div id="instagram-gallery-feed-<?php echo esc_attr( $id ); ?>" class="instagram-gallery-feed" data-feed="<?php echo htmlentities( json_encode( $feed ), ENT_QUOTES, 'UTF-8' ); ?>">
		<!-- <FeedContainer/> -->
		</div>
		<?php
		return ob_get_clean();
	}

	public function do_shortcode( $atts, $content = null ) {

		$atts = shortcode_atts(
			array(
				'id' => 0,
			),
			$atts
		);

		$id = absint( $atts['id'] );

		$models_feed = new Models_Feed();
		$feed         = $models_feed->get_by_id( $id );

		return $this->create_shortcode( $feed, $id );

	}

	public static function instance() {
		if ( ! isset( self::$instance ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}
}
