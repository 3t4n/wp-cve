<?php

/**
 * Created by PhpStorm.
 * User: sandeep
 * Date: 26/7/18
 * Time: 5:10 PM
 */
#[AllowDynamicProperties]

 final class WFACP_Embed_Form_loader {
	private static $ins = null;
	private $current_template = false;
	private $is_divi_builder_page = false;
	private $wfacp_id = 0;
	private $rest_api_run = false;
	private $page_is_editable = false;
	private $is_received_page = false;
	public static $pop_up_trigger = false;
	public $current_page_id = 0;

	protected function __construct() {
		add_action( 'rest_jsonp_enabled', [ $this, 'enable_rest_jsonp' ] );
		add_action( 'wfacp_none_checkout_pages', [ $this, 'detect_shortcode' ], 1 );
		add_action( 'wfacp_none_checkout_pages', [ $this, 'active_woo_compatibility' ] );
		add_shortcode( 'wfacp_forms', [ $this, 'shortcode' ] );
		add_filter( 'wfacp_do_not_execute_shortcode', [ $this, 'do_not_execute_shortcode' ] );

		add_filter( 'wfacp_do_not_allow_shortcode_printing', [ $this, 'do_not_allow_shortcode_printing' ] );
		add_filter( 'wfacp_embed_form_allow_header', [ $this, 'do_not_allow_header_in_ajx' ] );
	}

	public function is_divi_builder_page() {
		return $this->is_divi_builder_page;
	}

	/**
	 * @return WFACP_Embed_Form_loader;
	 */
	public static function get_instance() {
		if ( is_null( self::$ins ) ) {
			self::$ins = new self();
		}

		return self::$ins;
	}

	public function check_shortcode_exist( $post_data, $post_excerpt = '' ) {
		global $post;
		$status = $this->shortcode_exist( $post_data ) || ( ( $this->shortcode_exist( $post_excerpt ) ? true : false ) );

		return apply_filters( 'wfacp_shortcode_exist', $status, $post );
	}

	public function shortcode_exist( $content ) {
		return ( false !== strpos( $content, '[wfacp_forms' ) || false !== strpos( $content, '[WFACP_FORMS' ) );
	}

	public function detect_shortcode() {
		if ( is_admin() || true == $this->rest_api_run ) {
			return '';
		}
		if ( true == apply_filters( 'wfacp_do_not_execute_shortcode', false, $this ) ) {
			return '';
		}

		global $post;
		$is_true = WFACP_Common::is_customizer();
		if ( ! $post instanceof WP_Post || true == $is_true ) {
			return false;
		}
		$this->current_page_id = $post->ID;
		$shortcode_exist       = $this->check_shortcode_exist( $post->post_content, $post->post_excerpt );
		$shortcode_content     = apply_filters( 'wfacp_detect_shortcode', $post->post_content, $post );

		do_action( 'wfacp_run_shortcode_before', $shortcode_exist );

		/** Return if no shortcode exist */
		if ( false === $shortcode_exist ) {
			return false;
		}
		$this->is_received_page = ( is_order_received_page() || is_checkout_pay_page() );
		if ( is_cart() || is_shop() || $this->is_received_page ) {
			return false;
		}
		/** Shortcode exist on a page */

		if ( ! is_null( $post ) ) {
			remove_action( 'wp', [ WFACP_Core()->template_loader, 'maybe_setup_page' ], 7 );
			do_shortcode( $shortcode_content );
		}
	}

	public function do_not_allow_shortcode_printing( $status ) {
		if ( isset( $_REQUEST['elementor-preview'] ) ) {
			$status = true;
		}
		if ( is_admin() && ( true == $this->rest_api_run || ( isset( $_GET['post'] ) && $_GET['post'] > 0 && isset( $_REQUEST['action'] ) ) ) ) {
			//return;
			$status = true;
		}

		// Allow Shortcode Execute in AJAx Call

		if ( is_admin() && wp_doing_ajax() ) {
			$status = false;
		}


		return $status;
	}

	public function do_not_allow_header_in_ajx( $status ) {
		$wfacp_id = 0;

		if ( isset( $_REQUEST['elementor-preview'] ) ) {
			$aero_id  = filter_input( INPUT_GET, 'elementor-preview', FILTER_UNSAFE_RAW );
			$wfacp_id = absint( $aero_id );
		} else if ( isset( $_REQUEST['action'] ) && 'elementor_ajax' == $_REQUEST['action'] ) {
			$aero_id  = filter_input( INPUT_POST, 'editor_post_id', FILTER_UNSAFE_RAW );
			$wfacp_id = absint( $aero_id );
		}
		if ( $wfacp_id > 0 ) {
			$post = get_post( $wfacp_id );
			if ( ! is_null( $post ) && $post->post_type == WFACP_Common::get_post_type_slug() ) {
				$status = false;
				remove_all_actions( 'wfacp_after_form' );
			}
		}

		return $status;
	}

	public function shortcode( $attributes ) {

		if ( is_null( WC()->cart ) || true == apply_filters( 'wfacp_do_not_allow_shortcode_printing', false ) ) {
			return '';
		}
		// If execute woocommerce_checkout shortcode in case of thankyou & pay page open . This issue comes when client use Embed shortcode native woocommerce checkout page  directly
		if ( true === $this->is_received_page ) {
			return do_shortcode( '[woocommerce_checkout]' );
		}
		$template = wfacp_template();
		if ( $template instanceof WFACP_Pre_Built ) {
			global $post;
			if ( ! is_null( $post ) && $post->post_type == WFACP_Common::get_post_type_slug() ) {
				// checking template support Shortcode execution
				add_filter( 'wfacp_skip_form_printing', '__return_false' );
				ob_start();
				$this->get_form_shortcode_html( $template, WFACP_Core()->public->is_checkout_override() );

				return ob_get_clean();
			}
		}


		$attributes = shortcode_atts( [
			'id'           => 0,
			'lightbox'     => 'no',
			'width'        => 500,
			'mode'         => 'all',
			'product_ids'  => '',
			'product_qtys' => '',
		], $attributes );
		$wfacp_id   = $attributes['id'];
		$lightbox   = $attributes['lightbox'];
		if ( '' !== $attributes['product_ids'] ) {
			$aero_add_to_checkout_parameter          = WFACP_Core()->public->aero_add_to_checkout_parameter();
			$_GET[ $aero_add_to_checkout_parameter ] = trim( $attributes['product_ids'] );
		}
		if ( '' !== $attributes['product_qtys'] ) {
			$aero_add_to_checkout_product_quantity_parameter          = WFACP_Core()->public->aero_add_to_checkout_product_quantity_parameter();
			$_GET[ $aero_add_to_checkout_product_quantity_parameter ] = trim( $attributes['product_qtys'] );
		}

		$data = WFACP_Common::get_page_design( $wfacp_id );

		if ( empty( $data ) || 'embed_forms' !== $data['selected_type'] ) {
			return '';
		}

		if ( 0 === $this->wfacp_id ) {

			$this->wfacp_id = absint( $wfacp_id );
			if ( 0 == $this->wfacp_id ) {
				return '';
			}
			$temp_post = get_post( $this->wfacp_id );
			if ( is_null( $temp_post ) ) {
				return '';
			}
			if ( ! is_super_admin() ) {
				if ( ( 'publish' !== $temp_post->post_status || $temp_post->post_type !== WFACP_Common::get_post_type_slug() ) ) {
					return '';
				}
			}

			// Normal checkout page (Woocommerce setting checkout page)
			if ( ( is_checkout() || ( $this->current_page_id > 0 && $this->current_page_id == WFACP_Common::get_checkout_page_id() ) ) && false == $this->page_is_editable ) {
				remove_action( 'wfacp_after_checkout_page_found', [ WFACP_Core()->public, 'add_to_cart' ], 2 );
				do_action( 'wfacp_changed_default_woocommerce_page' );
			}

			add_filter( 'wfacp_skip_add_to_cart', [ $this, 'skip_add_to_cart' ] );

			$this->remove_hooks();

			add_filter( 'wfacp_enqueue_global_script', '__return_true' );
			add_filter( 'wfacp_cancel_url_arguments', [ $this, 'add_embed_page_id' ] );
			add_filter( 'woocommerce_is_checkout', '__return_true' );
			add_filter( 'wfacp_remove_woocommerce_style_dependency', '__return_true' );
			add_filter( 'wfacp_skip_form_printing', '__return_true', 10 );


			if ( 'yes' == $lightbox ) {
				add_action( 'wp_enqueue_scripts', [ $this, 'remove_select2_wc' ], 100 );
				self::$pop_up_trigger = true;
			}
			WFACP_Common::set_id( $this->wfacp_id );
			$get_template_loader = WFACP_Core()->template_loader;
			$get_template_loader->load_template( $this->wfacp_id );
			$this->current_template = $get_template_loader->get_template_ins();

			if ( ! is_null( $this->current_template ) && $this->current_template instanceof WFACP_Pre_Built ) {
				remove_filter( 'template_redirect', array( $get_template_loader, 'setup_preview' ), 99 );
				// Remove assign template function because of shortcode embed on other page
				global $post;
				if ( ! is_null( $post ) && $post->ID !== $this->wfacp_id ) {
					remove_filter( 'template_include', array( $get_template_loader, 'assign_template' ), 95 );
				}

				add_action( 'wfacp_after_payment_section', [ $this, 'create_hidden_input_for_saving_current_page_id' ] );
				$this->current_template->get_customizer_data();
				WFACP_Core()->public->add_to_cart_action( $this->wfacp_id );
				do_action( 'wfacp_after_checkout_page_found', $this->wfacp_id );
			}

			return '';
		} else {

			/** Don't execute shortcode if mode is mobile and a user not came from mobile */
			if ( 'mobile' == $attributes['mode'] && ! wp_is_mobile() ) {
				return '';
			}

			/** Don't execute shortcode if mode is desktop and a user came from mobile */
			if ( 'desktop' == $attributes['mode'] && wp_is_mobile() ) {
				return '';
			}

			if ( ! $this->current_template instanceof WFACP_Pre_Built ) {
				return '';
			}

			add_filter( 'wfacp_skip_form_printing', '__return_false' );
			ob_start();

			if ( 'yes' == $lightbox ) {
				$this->wrap_in_light_box();
			} else {
				include $this->current_template->get_template_url();
			}

			return ob_get_clean();

		}
	}

	/**
	 * @param $instance WFACP_Template_Common
	 */
	protected function get_form_shortcode_html( $instance, $exclude_header_footer = false ) {
		include WFACP_Core()->dir( 'builder/customizer/templates/embed_forms_1/views/view.php' );
	}


	public function create_hidden_input_for_saving_current_page_id() {
		echo '<input type="hidden" name="wfacp_embed_form_page_id" id="wfacp_embed_form_page_id" value="' . $this->current_page_id . '">';
	}

	public function add_embed_page_id( $params ) {
		$params['wfacp_embed_page_id'] = $this->current_page_id;

		return $params;
	}

	private function wrap_in_light_box() {

		?>
        <div class="wfacp_pop_up_wrap" id="wfacp_pop_up_wrap">
            <div class="wfacp_modal_overlay wfacp_display_none"></div>
            <div class="wfacp_modal_outerwrap wfacp_display_none">
                <div class="wfacp_modal_innerwrap">
                    <div class="wfacp_modal_content" id="wfacp_modal_content">
                        <div class="wfacp_pop_sec">
                            <div class="wfacp_modal_container">
								<?php include $this->current_template->get_template_url(); ?>
                            </div>
                        </div><!-- product-container -->
                        <button title="Close (Esc)" type="button" class="wfacp_modal_close">x</button>
                    </div>
                </div>
            </div>
        </div>
		<?php
	}

	public function enable_rest_jsonp( $status ) {
		$this->rest_api_run = true;

		return $status;
	}

	public function active_woo_compatibility() {

		if ( class_exists( 'WC_Active_Woo' ) ) {

			global $activewoo;
			remove_action( 'woocommerce_before_checkout_form', array( $activewoo->recover_cart, 'print_subscribe_form' ) );
			add_action( 'woocommerce_before_checkout_form', function () {
				wp_enqueue_script( 'aw_rc_cart_js' );
				wp_enqueue_script( 'wfacp_active_woo', WFACP_PLUGIN_URL . '/compatibilities/js/activewoo.min.js', [ 'wfacp_checkout_js' ], WFACP_VERSION, true );
			} );
		}
	}


	public function remove_select2_wc() {
		wp_dequeue_style( 'select2' );
		wp_dequeue_script( 'select2' );

	}

	public function skip_add_to_cart( $status ) {
		global $post;
		if ( ! is_null( $post ) && $post->post_type == WFACP_Common::get_post_type_slug() ) {
			return $status;
		}

		if ( ! WC()->cart->is_empty() && 0 == count( WFACP_Common::get_page_product( $this->wfacp_id ) ) ) {
			return true;
		}

		return $status;
	}

	private function remove_hooks() {

		if ( class_exists( 'Astra_Woocommerce' ) ) {
			$astra = Astra_Woocommerce::get_instance();
			remove_filter( 'astra_get_sidebar', [ $astra, 'replace_store_sidebar' ] );
			remove_filter( 'astra_page_layout', [ $astra, 'store_sidebar_layout' ] );
			remove_filter( 'astra_get_content_layout', [ $astra, 'store_content_layout' ] );

		}
		if ( function_exists( 'flatsome_woocommerce_add_notice' ) ) {
			remove_action( 'flatsome_after_header', 'flatsome_woocommerce_add_notice', 100 );
		}
	}


	public function do_not_execute_shortcode( $status ) {

		if ( is_admin() && isset( $_REQUEST['action'] ) && 'elementor' == $_REQUEST['action'] ) {
			return true;
		}

		if ( isset( $_REQUEST['elementor-preview'] ) && $_REQUEST['elementor-preview'] > 0 ) {

			return true;
		}

		return $status;

	}

	public function page_is_editable() {
		return $this->page_is_editable;
	}

	/**
	 * To avoid cloning of current class
	 */
	protected function __clone() {
	}

	/**
	 * to avoid unserialize of the current class
	 */
	public function __wakeup() {
		throw new ErrorException( 'WFACPEF_Core can`t converted to string' );
	}


	/**
	 * to avoid serialize of the current class
	 */
	public function __sleep() {
		throw new ErrorException( 'WFACPEF_Core can`t converted to string' );
	}


}

if ( class_exists( 'WFACP_Core' ) && ! WFACP_Common::is_disabled() ) {
	WFACP_Core::register( 'embed_forms', 'WFACP_Embed_Form_loader' );
}
