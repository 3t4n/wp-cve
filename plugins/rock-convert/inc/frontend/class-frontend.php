<?php
/**
 * FrontEnd Class.
 *
 * @link  https://rockcontent.com
 * @since 1.0.0
 *
 * @package Rock_Convert
 */

namespace Rock_Convert\Inc\Frontend;

use Rock_Convert\Inc\Admin\Admin;
use Rock_Convert\inc\admin\announcements\Announcement;
use Rock_Convert\Inc\Frontend\Widget\Banner;
use Rock_Convert\Inc\Frontend\Widget\Subscribe;

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @link  https://rockcontent.com
 * @since 1.0.0
 *
 * @author Rock Content
 */
class Frontend {

	/**
	 * The ID of this plugin.
	 *
	 * @since  1.0.0
	 * @access private
	 * @var    string $plugin_name The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since  1.0.0
	 * @access private
	 * @var    string $version The current version of this plugin.
	 */
	private $version;

	/**
	 * The text domain of this plugin.
	 *
	 * @since  1.0.0
	 * @access private
	 * @var    string $plugin_text_domain The text domain of this plugin.
	 */
	private $plugin_text_domain;

	/**
	 * The front css from assets
	 *
	 * @since  2.11.0
	 * @access protected
	 * @var    string $plugin_front_css_bundle_url The string used to enqueue front css.
	 */
	private $plugin_front_css_bundle_url;

	/**
	 * The front js from assets
	 *
	 * @since  2.11.0
	 * @access protected
	 * @var    string $plugin_front_js_bundle_url The string used to enqueue front js.
	 */
	private $plugin_front_js_bundle_url;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since 1.0.0/wp-admin/edit.php
	 *
	 * @param string $plugin_name                 The name of this plugin.
	 * @param string $version                     The version of this plugin.
	 * @param string $plugin_text_domain          The text domain of this plugin.
	 * @param string $plugin_front_css_bundle_url The style path of this plugin.
	 * @param string $plugin_front_js_bundle_url  The js path of this plugin.
	 */
	public function __construct( $plugin_name,
		$version,
		$plugin_text_domain,
		$plugin_front_css_bundle_url,
		$plugin_front_js_bundle_url
	) {
		$this->plugin_name                 = $plugin_name;
		$this->version                     = $version;
		$this->plugin_text_domain          = $plugin_text_domain;
		$this->plugin_front_css_bundle_url = $plugin_front_css_bundle_url;
		$this->plugin_front_js_bundle_url  = $plugin_front_js_bundle_url;
		$this->register_ctas();
		$this->register_widgets();
	}

	/**
	 * Register CTAS
	 */
	public function register_ctas() {
		new CTA();
		new Download();
	}

	/**
	 * Undocumented function
	 *
	 * @return void
	 */
	public function rock_exit_modal() {
		global $post;
		$popup_activate       = get_option( '_rock_convert_popup_activate' );
		$popup_image_activate = get_option( '_rock_convert_popup_image_activate' );
		$popup_box_class      = $popup_image_activate ? 'convert-popup-box' : 'convert-popup-box-ni';

		if ( 'yes' === $popup_activate && ! is_admin() && 'wp-login.php' !== $GLOBALS['pagenow'] ) {
			$popup_title              = get_option( '_rock_convert_popup_title' );
			$popup_description        = get_option( '_rock_convert_popup_descricao' );
			$popup_color              = get_option( '_rock_convert_popup_color' );
			$popup_button_color       = get_option( '_rock_convert_popup_button_color' );
			$popup_button_text_color  = get_option( '_rock_convert_popup_button_text_color' );
			$popup_button_close_color = get_option( '_rock_convert_popup_button_close_color' );
			$popup_title_color        = get_option( '_rock_convert_popup_title_color' );
			$popup_description_color  = get_option( '_rock_convert_popup_description_color' );
			$popup_image              = get_option( '_rock_convert_popup_image' );
			$image_url                = wp_get_attachment_image_url(
				$popup_image,
				'medium',
				false,
				array( 'id' => 'rock_convert_popup_image' )
			);
			$redirect_page_id         = $post->ID ? $post->ID : null;
			$current_url              = home_url();
			$get_post_id              = 0;

			?>
				<div class="convert-popup" style="z-index:20000;">
					<div class="<?php echo esc_html( $popup_box_class ); ?>"
						style="background-color: <?php echo esc_html( $popup_color ); ?>">
						<?php if ( $popup_image_activate ) { ?>
							<img src="<?php echo esc_url( $image_url ); ?>" id="rock_convert_popup_image" alt="Pop Up Image">
						<?php } ?>
						<div class="convert-popup-content">
							<h2 style="color: <?php echo esc_attr( $popup_title_color ); ?>">
								<?php echo esc_html( $popup_title ); ?>
							</h2>
							<p style="word-spacing:-1px;text-align:justify;width:350px;color:<?php echo esc_attr( $popup_description_color ); ?>">
							<?php echo esc_html( $popup_description ); ?><a style="color:<?php echo esc_html( $popup_button_close_color ); ?>" id="btnClose" href="#" class="convert-popup-close">X</a></p>

							<form style="margin:30px 0 20px 0;width:95%;" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" method="post">
								<div id="html_element"></div>
								<input type="hidden" name="action" value="rock_convert_subscribe_form">
								<input type="hidden" name="popup_send" value="1">
								<?php wp_nonce_field( 'rock_convert_subscriber_nonce', 'rock_convert_subscribe_nonce' ); ?>
								<input type="hidden" name="rock_convert_subscribe_page" value="<?php echo esc_url( $current_url ); ?>">
								<input type="hidden" name="rock_convert_subscribe_redirect_page" value="">
								<input type="hidden" name="rock_get_current_post_id" value="<?php echo esc_attr( $get_post_id ); ?>">
								<input type="email" name="rock_convert_subscribe_email" required
									class="convert-popup-email rock-convert-subscribe-form-email"
									placeholder="&nbsp;&nbsp;E-mail">
								<input type="submit" class="convert-popup-btn rock-convert-subscribe-form-btn"
									value="Enviar" style="background-color:<?php echo esc_attr( $popup_button_color ); ?>; color:<?php echo esc_attr( $popup_button_text_color ); ?>;">
							</form>
						</div>
					</div>
				</div>
			<?php
		}
	}

	/**
	 * Register PopUp Widget
	 *
	 * @return void
	 */
	public function register_widgets() {
		add_action( 'widgets_init', array( $this, 'load_widgets' ) );
		add_action( 'wp_footer', array( $this, 'rock_exit_modal' ) );
	}

	/**
	 * Load PopUp widget data
	 *
	 * @return void
	 */
	public function load_widgets() {
		register_widget( new Subscribe() );
		register_widget( new Banner() );
	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since 1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style(
			$this->plugin_name . '-frontend',
			$this->plugin_front_css_bundle_url,
			array(),
			$this->version,
			'all'
		);
	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since 1.0.0
	 */
	public function enqueue_scripts() {

		$analytics_enabled = Admin::analytics_enabled();
		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		$params = array(
			'ajaxurl'                    => admin_url( 'admin-ajax.php' ),
			'track_cta_click_path'       => get_rest_url(
				null,
				'rock-convert/v2/analytics/cta/click/'
			),
			'track_cta_view_path'        => \get_rest_url( null, 'rock-convert/v2/analytics/cta/view/' ),
			'announcements_bar_settings' => Announcement::options(),
			'analytics_enabled'          => $analytics_enabled,
		);
		wp_enqueue_script(
			$this->plugin_name . '-frontend',
			$this->plugin_front_js_bundle_url,
			array( 'jquery' ),
			$this->version,
			false
		);

		wp_localize_script( $this->plugin_name . '-frontend', 'rconvert_params', $params );
	}

	/**
	 * Query all post categories.
	 *
	 * @param int $post_id ID from post.
	 */
	public function get_post_categories( $post_id ) {
		$categories = get_the_category( $post_id );

		if ( ! $categories ) {
			return '';
		}

		$cat_names = array_map(
			function ( $cat ) {
				return $cat->name;
			},
			$categories
		);

		return wp_json_encode( $cat_names );
	}

	/**
	 * Query all tags.
	 *
	 * @param int $post_id ID from post.
	 */
	public function get_post_tags( $post_id ) {
		$tags = get_the_tags( $post_id );

		if ( ! $tags ) {
			return '';
		}

		$tag_names = array_map(
			function ( $tag ) {
				return $tag->name;
			},
			$tags
		);

		return wp_json_encode( $tag_names );
	}

	/**
	 * Get word count from post.
	 *
	 * @param int $post_id ID from post.
	 */
	public function get_post_word_count( $post_id ) {
		$content = get_post( $post_id )->post_content;

		if ( ! $content ) {
			return 0;
		}

		return str_word_count( wp_strip_all_tags( $content ) );
	}

	/**
	 * API Call function.
	 */
	public function rest_api_endpoint() {
		\register_rest_route(
			'rock-convert/v2',
			'/analytics/cta/click/(?P<id>\d+)',
			array(
				'methods'             => 'POST',
				'callback'            => array( $this, 'track_cta_click' ),
				'permission_callback' => '__return_true',
				'args'                => array(
					'id' => array(
						'validate_callback' => function( $param ) {
							return is_numeric( $param );
						},
					),
				),
			)
		);

		\register_rest_route(
			'rock-convert/v2',
			'/analytics/cta/view/(?P<id>\d+)',
			array(
				'methods'             => 'POST',
				'callback'            => array( $this, 'track_cta_view' ),
				'permission_callback' => '__return_true',
				'args'                => array(
					'id' => array(
						'validate_callback' => function( $param ) {
							return is_numeric( $param );
						},
					),
				),
			)
		);
	}

	/**
	 * Track clicks from call to action.
	 *
	 * @param class \WP_REST_Request $request WordPress REST class.
	 */
	public function track_cta_click( \WP_REST_Request $request ) {
		$id = sanitize_key( $request->get_param( 'id' ) );

		$post = get_post( $id );
		if ( 'cta' === $post->post_type ) {
			$this->increase_click_count( $post->ID );
		}
	}

	/**
	 * Increment clicks function
	 *
	 * @param int $post_id ID from post.
	 */
	public function increase_click_count( $post_id ) {
		$count_key = '_rock_convert_cta_clicks';
		$count     = intval( get_post_meta( $post_id, $count_key, true ) );

		if ( empty( $count ) ) {
			$count = 1;
		} else {
			$count++;
		}

		update_post_meta( $post_id, $count_key, $count );
	}

	/**
	 * Track views from call to action.
	 *
	 * @param class \WP_REST_Request $request WordPress REST class.
	 */
	public function track_cta_view( \WP_REST_Request $request ) {
		$id = sanitize_key( $request->get_param( 'id' ) );

		$post = get_post( $id );
		if ( 'cta' === $post->post_type ) {
			$this->increase_view_count( $post->ID );
		}
	}

	/**
	 * Views Increment function.
	 *
	 * @param int $post_id ID from post.
	 */
	public function increase_view_count( $post_id ) {
		$count_key = '_rock_convert_cta_views';
		$count     = intval( get_post_meta( $post_id, $count_key, true ) );

		if ( empty( $count ) ) {
			$count = 1;
		} else {
			$count++;
		}

		update_post_meta( $post_id, $count_key, $count );
	}
}
