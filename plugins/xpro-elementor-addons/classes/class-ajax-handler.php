<?php

namespace XproElementorAddons;

use WC_Ajax;
use WP_Query;
use XproElementorAddons\Libs\Dashboard\Classes\Xpro_Elementor_Dashboard_Utils;
use XproElementorAddonsPro\Module\Xpro_Elementor_Mega_Menu;

defined( 'ABSPATH' ) || exit;

/**
 * Class Xpro_Ajax_Handler
 *
 * ajax handler
 */
class Xpro_Ajax_Handler {


	private static $instance = null;

	public function __construct() {
	}

	public static function instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
			self::$instance->init();
		}

		return self::$instance;
	}

	public function init() {

		add_action( 'wp_ajax_xpro_elementor_select_search_post', array( $this, 'select_ajax_posts_filter_autocomplete' ) );
		add_action( 'wp_ajax_xpro_elementor_select_get_title', array( $this, 'select_ajax_get_posts_value_titles' ) );
		add_action( 'wp_ajax_xpro_elementor_contact_form', array( $this, 'simple_contact_form_submit' ) );
		add_action( 'wp_ajax_nopriv_xpro_elementor_contact_form', array( $this, 'simple_contact_form_submit' ) );
		add_action( 'wp_ajax_xpro_elementor_mailchimp_form', array( $this, 'mailchimp_subscribe_form_submit' ) );
		add_action( 'wp_ajax_nopriv_xpro_elementor_mailchimp_form', array( $this, 'mailchimp_subscribe_form_submit' ) );

		add_action( 'wp_ajax_xpro_save_menuitem_settings', array( $this, 'save_menu_item_settings' ) );
		add_action( 'wp_ajax_xpro_get_menuitem_settings', array( $this, 'get_menu_item_settings' ) );
		add_action( 'wp_ajax_xpro_get_content_editor', array( $this, 'get_menu_content_editor' ) );
		add_action( 'wp_ajax_save_megamenu_settings', array( $this, 'save_megamenu_settings' ) );

		add_action( 'wp_ajax_xpro_elementor_live_search_data_fetch', array( $this, 'xpro_elementor_live_search_data_fetch' ) );
		add_action( 'wp_ajax_nopriv_xpro_elementor_live_search_data_fetch', array( $this, 'xpro_elementor_live_search_data_fetch' ) );

		if ( class_exists( 'WooCommerce' ) ) {
			add_action( 'wp_ajax_load_quick_view_product_data', array( $this, 'load_quick_view_product_data' ) );
			add_action( 'wp_ajax_nopriv_load_quick_view_product_data', array( $this, 'load_quick_view_product_data' ) );

			add_action( 'wp_ajax_add_cart_single_product_ajax', array( $this, 'add_cart_single_product_ajax' ) );
			add_action( 'wp_ajax_nopriv_add_cart_single_product_ajax', array( $this, 'add_cart_single_product_ajax' ) );
		}

	}

	/**
	 * Mega Menu Ajax calls
	 */
	public static function save_menu_item_settings() {

		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		$menu_item_id       = $_REQUEST['settings']['menu_id'];
		$menu_item_settings = wp_json_encode( $_REQUEST['settings'], JSON_UNESCAPED_UNICODE );
		update_post_meta( $menu_item_id, Xpro_Elementor_Mega_Menu::$menuitem_settings_key, $menu_item_settings );

		echo wp_json_encode(
			array(
				'saved'   => 1,
				'message' => esc_html__( 'Saved', 'xpro-elementor-addons' ),
			)
		);

		wp_die();
	}

	public static function save_megamenu_settings() {
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}
		$menu_id = $_REQUEST['menu_id'];
		$is_enabled = $_REQUEST['mega_menu'];
		
		$data = xpro_megamenu_option( Xpro_Elementor_Mega_Menu::$megamenu_settings_key, array() );
		$data[ 'menu_location_' . $menu_id ] = array(
			'is_enabled' => $is_enabled,
		);

		xpro_megamenu_save_option( Xpro_Elementor_Mega_Menu::$megamenu_settings_key, $data );
		var_dump($data);
		wp_die();
	}
	
	/**
	 * Mega Menu get item settings
	 */
	public static function get_menu_item_settings() {
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}
		$menu_item_id = $_REQUEST['menu_id'];
		$data         = get_post_meta( $menu_item_id, Xpro_Elementor_Mega_Menu::$menuitem_settings_key, true );

		if ( empty( $data ) ) {
			$data = array(
				'menu_id'                         => $menu_item_id,
				'menu_has_child'                  => '',
				'menu_enable'                     => '',
				'menu_icon'                       => '',
				'menu_icon_color'                 => '',
				'menu_badge_text'                 => '',
				'menu_badge_color'                => '',
				'menu_badge_background'           => '',
				'menu_badge_radius'               => '',
				'vertical_menu_width'             => '',
				'mobile_submenu_content_type'     => '',
				'vertical_megamenu_position_type' => '',
				'megamenu_width_type'             => '',
			);
			$data = wp_json_encode( $data );
		}

		echo $data;
		wp_die();
	}

	/**
	 * Get Menu Item Iframe URL
	 */
	public static function get_menu_content_editor() {

		$content_key = $_REQUEST['key'];

		$builder_post_title = 'xpro-megamenu-content-' . $content_key;
		$builder_post_id    = xpro_get_page_by_title( $builder_post_title, OBJECT, 'xpro_content' );

		if ( ! isset( $builder_post_id ) ) {
			$builder_post = get_posts(
				array(
					'post_type'  => 'xpro_content',
					'meta_key'   => 'xpro_dynamic_template_id',
					'meta_value' => $builder_post_title,
				)
			);
			if ( isset( $builder_post ) && isset( $builder_post[0] ) ) {
				$builder_post_id = $builder_post[0];
			}
		}

		if ( is_null( $builder_post_id ) ) {
			$defaults        = array(
				'post_content' => '',
				'post_title'   => $builder_post_title,
				'post_status'  => 'publish',
				'post_type'    => 'xpro_content',
			);
			$builder_post_id = wp_insert_post( $defaults );

			update_post_meta( $builder_post_id, '_wp_page_template', 'elementor_canvas' );
			update_post_meta( $builder_post_id, 'xpro_dynamic_template_id', $builder_post_title );

		} else {
			$builder_post_id = $builder_post_id->ID;
		}

		$url = get_admin_url() . 'post.php?post=' . $builder_post_id . '&action=elementor';
		echo $url;
		wp_die();
	}

	public function select_ajax_posts_filter_autocomplete() {

		check_ajax_referer( 'xpro-select-nonce', 'nonce' );

		$post_type   = 'post';
		$source_name = 'post_type';

		if ( ! empty( $_GET['post_type'] ) ) {
			$post_type = sanitize_text_field( $_GET['post_type'] );
		}

		if ( ! empty( $_GET['source_name'] ) ) {
			$source_name = sanitize_text_field( $_GET['source_name'] );
		}

		if ( 'taxonomy' === $source_name && 'any' === $post_type ) {
			$post_type = get_taxonomies( '', 'names' );
		}

		$search = ! empty( $_GET['term'] ) ? sanitize_text_field( $_GET['term'] ) : '';

		$results   = array();
		$post_list = array();

		switch ( $source_name ) {
			case 'taxonomy':
				$post_list = wp_list_pluck(
					get_terms(
						$post_type,
						array(
							'hide_empty' => false,
							'orderby'    => 'name',
							'order'      => 'ASC',
							'search'     => $search,
							'number'     => '5',
						)
					),
					'name',
					'term_id'
				);
				break;
			default:
				$post_list = xpro_elementor_get_query_post_list( $post_type, 10, $search );
		}

		if ( ! empty( $post_list ) ) {
			foreach ( $post_list as $key => $item ) {
				$results[] = array(
					'text' => $item,
					'id'   => $key,
				);
			}
		}
		wp_send_json( array( 'results' => $results ) );
	}

	public function select_ajax_get_posts_value_titles() {

		check_ajax_referer( 'xpro-select-nonce', 'nonce' );

		if ( empty( intval( $_POST['id'] ) ) ) {
			wp_send_json_error( array() );
		}

		$ids         = array_map( 'intval', $_POST['id'] );
		$source_name = ! empty( $_POST['source_name'] ) ? sanitize_text_field( $_POST['source_name'] ) : '';
		$post_type   = sanitize_text_field( $_POST['post_type'] );

		if ( 'dynamic' === $post_type ) {
			$post_type = array( 'elementor_library', 'xpro-themer', 'xpro_content' );
		}

		switch ( $source_name ) {
			case 'taxonomy':
				$response = wp_list_pluck(
					get_terms(
						$post_type,
						array(
							'hide_empty' => false,
							'orderby'    => 'name',
							'order'      => 'ASC',
							'include'    => implode( ',', $ids ),
						)
					),
					'name',
					'term_id'
				);
				break;
			default:
				$post_info = get_posts(
					array(
						'post_type' => $post_type,
						'include'   => implode( ',', $ids ),
					)
				);
				$response  = wp_list_pluck( $post_info, 'post_title', 'ID' );
		}

		if ( ! empty( $response ) ) {
			wp_send_json_success( array( 'results' => $response ) );
		} else {
			wp_send_json_error( array() );
		}
	}

	public function simple_contact_form_submit() {

		check_ajax_referer( 'xpro-contact-nonce', 'nonce' );

		if ( 'POST' !== $_SERVER['REQUEST_METHOD'] ) {
			wp_send_json_error( __( 'Request Not Valid', 'xpro-elementor-addons' ) );

			return;
		}

		$user_settings = Xpro_Elementor_Dashboard_Utils::instance()->get_option( 'xpro_elementor_user_data', array() );

		$email_to  = get_option( 'admin_email' );
		$site_name = get_option( 'blogname' );

		$captcha = sanitize_text_field( $_POST['captcha'] );
		$data    = json_decode( stripslashes_deep( $_POST['postData'] ) );
		$name    = sanitize_text_field( $_POST['formName'] );
		$subject = sanitize_text_field( $_POST['formSubject'] );

		if ( empty( $data ) ) {
			wp_send_json_error();

			return;
		}

		//      if ( 'false' !== $captcha && '' !== $captcha ) {
		//          $secret_key            = $user_settings['recaptcha']['secret_key'];
		//          $captcha_url           = esc_url( 'https://www.google.com/recaptcha/api/siteverify?secret=' . rawurlencode( $secret_key ) . '&response=' . rawurlencode( $captcha ) );
		//          $captcha_response      = wp_remote_get( $captcha_url );
		//          $captcha_response_keys = json_decode( $captcha_response, true );
		//          if ( ! $captcha_response_keys['success'] ) {
		//              wp_send_json_error();
		//              return;
		//          }
		//      }

		if ( ! empty( $user_settings['contact_form']['mail'] ) ) {
			$email_to = $user_settings['contact_form']['mail'];
		}

		if ( ! filter_var( $email_to, FILTER_VALIDATE_EMAIL ) ) {
			wp_send_json_error();

			return;
		}

		$new_array = array();

		foreach ( $data as $i => $value ) {
			foreach ( explode( '&&', $value ) as $val ) {
				$new_array[] = explode( '=', $val );
			}
		}

		$body  = '<div style="line-height: 1.2; font-family:Lato,sans-serif; text-align: left;">';
		$body .= '<h2 style="color: #2b2b2b; font-size: 20px; margin: 0 0 5px;">' . esc_html( $name ) . '</h2>';
		$body .= '<p style="color: #2b2b2b; font-size: 15px; margin: 0 0 30px 0;">' . esc_html( $subject ) . '</p>';
		$body .= '<div style="display:block;position: relative;overflow: hidden; margin-bottom: 30px;">';

		$count = 1;

		foreach ( $new_array as $i => $item ) {
			$body .= ( $count % 2 ) ? '<strong>' . ( ( $item[1] ) ? esc_html( $item[1] ) : __( 'Field', 'xpro-elementor-addons' ) ) . ': </strong>' : ( ( $item[1] ) ? esc_html( $item[1] ) : __( 'Field', 'xpro-elementor-addons' ) ) . '<br>';
			$count ++;
		}

		$body .= '</div>';

		$body .= '<p style="color: #2b2b2b; font-size: 13px; margin-top: 30px;">';
		$body .= __( 'Sent From ', 'xpro-elementor-addons' );
		$body .= '<a href="' . site_url() . '" target="_blank">' . get_bloginfo( 'name' ) . '</a></p>';

		$body .= '</div>';

		$mail_header  = 'From: ' . $site_name . ' <' . $email_to . ">\n";
		$mail_header .= "Content-Type: text/html; charset=UTF-8\n";

		$send = wp_mail( $email_to, $subject, $body, $mail_header );

		if ( $send ) {
			wp_send_json_success();
		} else {
			wp_send_json_error();
		}
	}

	public function mailchimp_subscribe_form_submit() {

		check_ajax_referer( 'xpro-mailchimp-nonce', 'nonce' );

		if ( 'POST' !== $_SERVER['REQUEST_METHOD'] ) {
			wp_send_json_error( __( 'Request Not Valid', 'xpro-elementor-addons' ) );

			return;
		}

		$api_key       = isset( $_POST['apiKey'] ) ? sanitize_key( $_POST['apiKey'] ) : '';
		$api_key_sufix = explode( '-', $api_key )[1];

		// List ID
		$list_id = isset( $_POST['listId'] ) ? sanitize_text_field( wp_unslash( $_POST['listId'] ) ) : '';

		// Get Available Fileds (PHPCS - fields are sanitized later on input)
		$available_fields = isset( $_POST['fields'] ) ? $_POST['fields'] : []; // phpcs:ignore
		wp_parse_str( $available_fields, $fields );

		// Merge Additional Fields
		$merge_fields = array(
			'FNAME' => ! empty( $fields['xpro_mailchimp_firstname'] ) ? sanitize_text_field( $fields['xpro_mailchimp_firstname'] ) : '',
			'LNAME' => ! empty( $fields['xpro_mailchimp_lastname'] ) ? sanitize_text_field( $fields['xpro_mailchimp_lastname'] ) : '',
		);

		// API URL
		$api_url = 'https://' . $api_key_sufix . '.api.mailchimp.com/3.0/lists/' . $list_id . '/members/' . md5( strtolower( sanitize_text_field( $fields['xpro_mailchimp_email'] ) ) );

		// API Args
		$api_args = array(
			'method'  => 'PUT',
			'headers' => array(
				'Content-Type'  => 'application/json',
				'Authorization' => 'apikey ' . $api_key,
			),
			'body'    => wp_json_encode(
				array(
					'email_address' => sanitize_text_field( $fields['xpro_mailchimp_email'] ),
					'status'        => 'subscribed',
					'merge_fields'  => $merge_fields,
				)
			)
		);

		// Send Request
		$request = wp_remote_post( $api_url, $api_args );

		if ( ! is_wp_error( $request ) ) {
			$request = json_decode( wp_remote_retrieve_body( $request ) );

			// Set Status
			if ( ! empty( $request ) ) {
				if ( 'subscribed' === $request->status ) {
					wp_send_json_success( array( 'status' => 'subscribed' ) );
				} else {
					wp_send_json_success( array( 'status' => $request->title ) );
				}
			}
		} else {
			wp_send_json_error();
		}

	}

	/**
	 * QV Single Product add to cart ajax request
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function add_cart_single_product_ajax() {

		check_ajax_referer( 'xpro-elementor-addons-nonce', 'nonce' );

		$product_id   = isset( $_POST['product_id'] ) ? sanitize_text_field( $_POST['product_id'] ) : 0;
		$variation_id = isset( $_POST['variation_id'] ) ? sanitize_text_field( $_POST['variation_id'] ) : 0;
		$quantity     = isset( $_POST['quantity'] ) ? sanitize_text_field( $_POST['quantity'] ) : 0;

		if ( ! empty( $variation_id ) ) {
			add_action( 'wp_loaded', array( 'WC_Form_Handler', 'add_to_cart_action' ), 20 );

			if ( is_callable( array( 'WC_AJAX', 'get_refreshed_fragments' ) ) ) {
				home_url() . WC_Ajax::get_refreshed_fragments();
			}
		} else {
			WC()->cart->add_to_cart( $product_id, $quantity );
		}
		die();
	}

	/**
	 * load_quick_view_product_data
	 *
	 * @since 1.0.0
	 * @access public
	 */

	public function load_quick_view_product_data() {

		check_ajax_referer( 'xpro-elementor-addons-nonce', 'nonce' );

		global $post, $product;
		$product_id   = intval( $_POST['id'] );
		$product      = get_post( $product_id );
		$product_type = $product->product_type;

		if ( isset( $product_id ) && '' !== $product_id ) {
			$product = wc_get_product( $product_id );
			if ( $product ) :
				$post = get_post( $product_id, OBJECT ); //phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
				setup_postdata( $post );
				do_action( 'xpro_elementor_woo_before_product' );
				?>
				<div class="xpro-woo-qv-inner-wrapper">
					<!-- left sec -->
					<div class="xpro-woo-qv-left-sec">
						<div class="xpro-woo-qv-img-sec">
							<?php
							$img_url         = wp_get_attachment_url( get_post_thumbnail_id( $post->ID ) );
							$placeholder_url = WC()->plugin_url() . '/assets/images/placeholder.png';
							?>
							<img class="xpro-woo-product-grid-img xpro-product-img-url" src="<?php echo esc_url( $img_url ? $img_url : $placeholder_url ); ?>" alt="Image Not Found">
						</div>
					</div>
					<!-- right sec -->
					<div class="xpro-woo-qv-right-sec">
						<div class="xpro-woo-qv-content-sec <?php echo esc_attr( $product_type ); ?>">
							<!-- cross button -->
							<div class="xpro-woo-qv-cross">
								<i class="fas fa-times"></i>
							</div>
							<?php

							//remove add to cart redirect.
							add_filter( 'woocommerce_add_to_cart_form_action', '__return_empty_string' );
							// single product summary
							do_action( 'woocommerce_before_single_product', $product_id );
							do_action( 'woocommerce_single_product_summary' );
							do_action( 'woocommerce_after_single_product', $product_id );
							?>

						</div>
					</div>
				</div><!-- modal inner wrapper end -->
				<?php
				do_action( 'xpro_elementor_woo_after_product' );
				wp_reset_postdata();
			endif;
		}
		die();
	}

	/**
	 * xpro_elementor_live_search_data_fetch
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function xpro_elementor_live_search_data_fetch() {
		check_ajax_referer( 'xpro-live-search-nonce', 'nonce' );

		$keyword         = sanitize_text_field( $_POST['keyword'] );
		$post_type       = sanitize_text_field( $_POST['post_type'] );
		$posts_per_page  = sanitize_text_field( $_POST['posts_per_page'] );
		$order           = sanitize_text_field( $_POST['order'] );
		$show_img        = sanitize_text_field( $_POST['display_img'] );
		$display_title   = sanitize_text_field( $_POST['display_title'] );
		$display_content = sanitize_text_field( $_POST['display_content'] );

		$the_query = new WP_Query(
			array(
				'posts_per_page' => $posts_per_page,
				's'              => $keyword,
				'post_type'      => $post_type,
				'order'          => $order,
			)
		);

		if ( $the_query->have_posts() ) :
			while ( $the_query->have_posts() ) :
				$the_query->the_post();
				$post_query     = $keyword;
				$search_keyword = $post_query;
				$search_title   = get_the_title();
				if ( stripos( "/{$search_title}/", $search_keyword ) !== false ) {
					?>
					<div class="xpro-live-search-post-item">
						<?php if ( 'yes' === $show_img ) { ?>
							<div class="xpro-live-search-post-img-wrap">
								<?php
								$img_url = wp_get_attachment_image_src(
									get_post_thumbnail_id( get_the_id() ),
									'thumbnail',
									true
								);
								$img_src = $img_url[0];
								?>
								<a class="xpro-live-search-post-img-link" href="<?php echo esc_url( get_permalink() ); ?>">
									<img class="xpro-live-search-post-item-img" src="<?php echo esc_url( $img_src ); ?>" alt="search-image"/>
								</a>
							</div>
						<?php } ?>
						<div class="xpro-live-search-post-content-wrap">
							<?php if ( 'yes' === $display_title ) { ?>
								<a class="xpro-live-search-post-title-link" href="<?php echo esc_url( get_permalink() ); ?>">
									<h2 class="xpro-live-search-post-title">
										<?php echo esc_html( the_title() ); ?>
									</h2>
								</a>
							<?php } ?>
							<?php if ( 'yes' === $display_content ) { ?>
								<p class="xpro-live-search-post-content">
									<?php
									$content = wp_trim_words( get_the_content(), 10, '...' );
									echo wp_kses_post( $content );
									?>
								</p>
							<?php } ?>
						</div>
					</div>
					<?php
				}
			endwhile;
		else :
			?>
			<div class="xpro-live-search-post-item xpro-no-result-item">
				<p class="xpro-live-search-no-result">
					<?php esc_html_e( 'No result found', 'xpro-elementor-addons' ); ?>
				</p>
			</div>
			<?php
		endif;
		die();
	}
}

Xpro_Ajax_Handler::instance();
