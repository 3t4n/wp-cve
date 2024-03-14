<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/*
 * Mondial Relay - WordPress
 * Author Name:  Rodolphe Cazemajou-Tournié
 * https://mondialrelay-wp.com/
 */


#[AllowDynamicProperties] 

  class WFACP_Compatibility_With_Mondialrelay_WP {

	private $obj = null;
	private $plugin_name = 'Mondial Relay - WordPress';
	private $version = '1.16';
	private $instance = null;

	public function __construct() {
		add_action( 'wfacp_after_template_found', [ $this, 'action' ] );
		add_action( 'wfacp_after_checkout_page_found', [ $this, 'remove_hooks' ] );
		add_action( 'wfacp_internal_css', [ $this, 'internal_css' ], 10 );
	}

	public function action() {
		$this->instance = WFACP_Common::remove_actions( 'woocommerce_review_order_after_shipping', 'class_MRWP_public', 'modaal_link' );

		if ( WFACP_Common::is_theme_builder() ) {
			WFACP_Common::remove_actions( 'wp_enqueue_scripts', 'class_MRWP_public', 'enqueue_scripts' );
		}

		if ( ! $this->instance instanceof class_MRWP_public ) {
			return;
		}

		remove_action( 'woocommerce_after_order_notes', [ $this->instance, 'my_custom_checkout_field' ] );
		add_action( 'wfacp_after_shipping_calculator_field', [ $this, 'move_link' ] );

		if ( method_exists( $this->instance, 'my_custom_checkout_field' ) ) {
			add_action( 'woocommerce_checkout_before_customer_details', function () {
				$this->instance->my_custom_checkout_field( WC()->checkout() );
			} );
		}
	}

	public function remove_hooks() {
		$plugin_public = new class_MRWP_public( $this->plugin_name, $this->version, false, false );
		if ( is_null( $plugin_public ) || ! $plugin_public instanceof class_MRWP_public ) {
			return;
		}

		$this->obj = $plugin_public;
		add_action( 'wfacp_after_shipping_calculator_field', [ $plugin_public, 'modaal_content' ] );
		add_filter( 'wfacp_show_shipping_options', [ $this, 'show_shipping_on_load' ] );
	}

	public function show_shipping_on_load() {
		return true;
	}

	public function add_hidden_custom_fields() {
		if ( is_null( $this->obj ) || ! $this->obj instanceof class_MRWP_public ) {
			return;
		}

		$this->obj->my_custom_checkout_field( wc()->checkout() );
	}

	public function internal_css() {
		?>
        <style>
            .wfacp_mondial_relay_link_div table {
                width: 97%;
            }

            button#delivery_point_chosen {
                z-index: 999999;
                display: block;
                padding: 10px;
                cursor: pointer;
            }
        </style>
        <script>
            window.addEventListener('load', function () {
                (function ($) {
                    function remove_modaalink() {
                        $('.wfacp_order_summary_container').find('.mrwp').remove();
                        $('.wfacp_mini_cart_reviews').find('.mrwp').remove();
                    }

                    $(document.body).on('updated_checkout', function () {
                        remove_modaalink();
                    });
                })(jQuery);
            });
        </script>
		<?php
	}

	public function move_link() {
		if ( ! $this->instance instanceof class_MRWP_public ) {
			return;
		}

		echo "<div class='wfacp_mondial_relay_link_div'><table>";
		$this->instance->modaal_link();
		echo "</table></div>";
	}

}

WFACP_Plugin_Compatibilities::register( new WFACP_Compatibility_With_Mondialrelay_WP(), 'wfacp-mondialrelay-wordpress' );
