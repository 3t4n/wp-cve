<?php

if ( ! class_exists( 'QiAddonsForElementor_Dashboard_Widgets' ) ) {
	class QiAddonsForElementor_Dashboard_Widgets {

		private static $instance;

		private $magazine_url;
		private $magazine_transient;
		private $special_post_transient;

		private function __construct() {

			$this->magazine_url           = 'https://qodeinteractive.com/magazine/';
			$this->magazine_transient     = 'qi_addons_for_elementor_magazine_posts';
			$this->special_post_transient = 'qi_addons_for_elementor_special_post';

			// Register Dashboard Widgets.
			add_action( 'wp_dashboard_setup', array( $this, 'add_dashboard_widgets' ) );

			// Enqueue Dashboard Widgets Scripts
			add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_dashboard_widgets_styles' ), 5 );
		}

		public static function get_instance() {
			if ( is_null( self::$instance ) ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		public function add_dashboard_widgets() {
			wp_add_dashboard_widget(
				'qi_addons_for_elementor_dashboard_widget',
				esc_html__( 'Qode Interactive News', 'qi-addons-for-elementor' ),
				array( $this, 'dashboard_widget_render' )
			);
		}

		public function dashboard_widget_render() {
			$params                   = array();
			$special_posts            = $this->get_special_post();
			$params['magazine_posts'] = $this->get_magazine_posts();
			$params['special_post']   = reset( $special_posts );
			qi_addons_for_elementor_framework_template_part( QI_ADDONS_FOR_ELEMENTOR_ADMIN_PATH, 'inc/dashboard-widgets', 'templates/widget', '', $params );

		}

		public function get_magazine_posts() {

			$posts = get_transient( $this->magazine_transient );

			if ( false === $posts ) {
				return $this->get_current_magazine_posts();
			}

			return $posts;
		}

		public function get_current_magazine_posts() {

			$url            = trailingslashit( $this->magazine_url . '/wp-json/wp/v2/posts' );
			$formated_posts = array();
			$posts          = $this->get_url_content( $url, array( 'per_page' => 3 ) );

			if ( is_array( $posts ) && count( $posts ) > 0 ) {
				foreach ( $posts as $post ) {

					$formated_posts[ $post->id ] = array(
						'title'   => $post->title->rendered,
						'link'    => $post->link,
						'excerpt' => strip_tags( $post->excerpt->rendered ),
					);

					$media_url  = trailingslashit( $this->magazine_url . '/wp-json/wp/v2/media/' . $post->featured_media );
					$post_image = $this->get_url_content( $media_url );

					if ( false !== $post_image ) {
						$formated_posts[ $post->id ]['img_url'] = $post_image->media_details->sizes->thumbnail->source_url;
					}
				}
			}

			set_transient( $this->magazine_transient, $formated_posts, 2 * DAY_IN_SECONDS );

			return $formated_posts;
		}

		public function get_special_post() {

			$posts = get_transient( $this->special_post_transient );

			if ( false === $posts ) {
				return $this->get_current_special_post();
			}

			return $posts;
		}

		public function get_current_special_post() {

			$url            = trailingslashit( $this->magazine_url . '/wp-json/wp/v2/special-posts' );
			$formated_posts = array();
			$posts          = $this->get_url_content( $url, array( 'per_page' => 1 ) );

			if ( is_array( $posts ) && count( $posts ) > 0 ) {
				$formated_posts[ $posts[0]->id ] = array(
					'title'   => $posts[0]->title->rendered,
					'link'    => $posts[0]->qodef_special_post_link,
					'excerpt' => $posts[0]->excerpt->rendered,
				);

				$media_url  = trailingslashit( $this->magazine_url . '/wp-json/wp/v2/media/' . $posts[0]->featured_media );
				$post_image = $this->get_url_content( $media_url );

				if ( false !== $post_image ) {
					$formated_posts[ $posts[0]->id ]['img_url'] = $post_image->media_details->sizes->full->source_url;
				}
			}

			set_transient( $this->special_post_transient, $formated_posts, 2 * DAY_IN_SECONDS );

			return $formated_posts;
		}

		public function get_url_content( $url, $args = array() ) {

			$response = wp_remote_get(
				add_query_arg(
					$args,
					$url
				)
			);

			if ( ! is_wp_error( $response ) && 200 === $response['response']['code'] ) {
				$remote_content = json_decode( $response['body'] );

				return $remote_content;
			}

			return false;
		}

		function enqueue_dashboard_widgets_styles( $hook ) {

			if ( 'index.php' === $hook ) {
				wp_enqueue_style( 'qode-framework-dashboard-widgets', QI_ADDONS_FOR_ELEMENTOR_ADMIN_URL_PATH . '/inc/dashboard-widgets/assets/css/dashboard-widgets.css' );
			}
		}

	}

	QiAddonsForElementor_Dashboard_Widgets::get_instance();
}
