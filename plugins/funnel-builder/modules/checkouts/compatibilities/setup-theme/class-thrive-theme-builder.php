<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


#[AllowDynamicProperties] 

  class WFACP_Compatibility_With_Thrive_Theme_builder {
	private $shortcode_content = '';

	public function __construct() {
		add_action( 'wp_enqueue_scripts', array( $this, 'maybe_remove_is_checkout' ), - 1 );

		add_filter( 'wfacp_shortcode_exist', [ $this, 'is_shortcode_exists' ], 10, 2 );
		add_filter( 'wfacp_detect_shortcode', [ $this, 'send_thrive_content' ] );


		add_filter( 'thrive_theme_shortcode_prefixes', [ $this, 'notify_aero_shortcode_to_thrive' ] );
		add_action( 'tve_editor_print_footer_scripts', [ $this, 'footer_scripts' ] );
		add_action( 'wfacp_after_checkout_page_found', [ $this, 'remove_theme_style' ], 9999 );

		add_action( 'wfacp_duplicate_pages', [ $this, 'duplicate_data' ], 10, 2 );
	}

	public function notify_aero_shortcode_to_thrive( $prefixes ) {
		array_push( $prefixes, 'wfacp_' );

		return $prefixes;
	}

	public function maybe_remove_is_checkout() {
		global $post;
		if ( is_object( $post ) && $post->post_type === WFACP_Common::get_post_type_slug() && is_editor_page() ) {

			add_filter( 'woocommerce_is_checkout', '__return_false' );
			remove_filter( 'tcb_editor_javascript_params', [ 'Thrive\Theme\Integrations\WooCommerce\Filters', 'tcb_editor_javascript_params' ] );
		}
	}

	public function remove_theme_style() {
		if ( ! class_exists( 'Thrive\Theme\Integrations\WooCommerce\Actions' ) ) {
			return;
		}
		add_action( 'wp_enqueue_scripts', [ $this, 'dequeue_css' ], 99 );

	}

	public function dequeue_css() {
		wp_dequeue_style( 'thrive-theme-woocommerce' );
	}


	public function is_shortcode_exists( $status, $post ) {
		if ( true == $status ) {
			return $status;
		}

		$content = $this->get_shortcode_content( $post );
		if ( false !== $content ) {
			$this->shortcode_content = $content;
			$status                  = true;
		}

		return $status;


	}

	public function send_thrive_content( $post_content ) {
		return ! empty( $this->shortcode_content ) ? $this->shortcode_content : $post_content;
	}


	public function get_shortcode_content() {

		if ( ! function_exists( 'tve_get_post_meta' ) ) {
			return false;
		}
		global $post;


		$panels_data = get_post_meta( $post->ID );

		if ( empty( $panels_data ) ) {
			return false;
		}
		$shortcodes = json_encode( $panels_data );

		$start_position = strpos( $shortcodes, '[wfacp_forms' );
		if ( false === $start_position ) {
			return false;
		}
		$shortcode_string = substr( $shortcodes, $start_position );
		$closing_position = strpos( $shortcode_string, ']', 1 );
		if ( false === $closing_position ) {
			return false;
		}
		$shortcode_string = substr( $shortcodes, $start_position, $closing_position + 1 );
		if ( strlen( $shortcode_string ) <= 0 ) {
			return false;
		}

		return $shortcode_string;

	}

	public function footer_scripts() {
		?>
        <script type="text/javascript">
            document.addEventListener('DOMContentLoaded', (event) => {
                if (typeof TVE !== "undefined") {
                    TVE.add_filter('tve.allowed.empty.posts.type', function (list) {
                        list.push('wfacp_checkout');
                        return list;
                    });
                }
            });

        </script>
		<?php
	}

	public function duplicate_data( $new_post_id, $post_id ) {
		$meta = get_post_meta( $post_id );
		foreach ( $meta as $key => $value ) {
			if ( false !== strpos( $key, 'tcb_' ) || false !== strpos( $key, 'tve' ) || false !== strpos( $key, 'thrive' ) || false !== strpos( $key, 'tcb2' ) ) {
				$meta_data = maybe_unserialize( $value[0] );
				update_post_meta( $new_post_id, $key, $meta_data );
			}

		}

	}
}


WFACP_Plugin_Compatibilities::register( new WFACP_Compatibility_With_Thrive_Theme_builder(), 'thrive_theme' );