<?php
namespace QuadLayers\QLWAPP;

use QuadLayers\QLWAPP\Models\Box as Models_Box;
use QuadLayers\QLWAPP\Models\Button as Models_Button;
use QuadLayers\QLWAPP\Models\Display as Models_Display;
use QuadLayers\QLWAPP\Models\Scheme as Models_Scheme;
use QuadLayers\QLWAPP\Models\Contacts as Models_Contacts;
use QuadLayers\QLWAPP\Services\Entity_Visibility;

class Frontend {

	protected static $instance;

	private function __construct() {
		add_action( 'wp', array( $this, 'display' ) );
		add_shortcode( 'whatsapp', array( $this, 'do_shortcode' ) );
		add_action( 'qlwapp_load', array( $this, 'load' ) );
	}

	public function add_js() {
		$frontend = include_once QLWAPP_PLUGIN_DIR . 'build/frontend/js/index.asset.php';
		wp_enqueue_style( QLWAPP_DOMAIN, plugins_url( '/build/frontend/css/style.css', QLWAPP_PLUGIN_FILE ), null, QLWAPP_PLUGIN_VERSION, 'all' );
		wp_enqueue_script( QLWAPP_DOMAIN, plugins_url( '/build/frontend/js/index.js', QLWAPP_PLUGIN_FILE ), $frontend['dependencies'], $frontend['version'], true );
	}

	public function add_box() {
		global $qlwapp;

		if ( is_file( $file = apply_filters( 'qlwapp_box_template', QLWAPP_PLUGIN_DIR . 'templates/box.php' ) ) ) {

			$models_box        = Models_Box::instance();
			$models_contacts   = Models_Contacts::instance();
			$models_button     = Models_Button::instance();
			$models_display    = Models_Display::instance();
			$entity_visibility = $display_service = Entity_Visibility::instance();

			$contacts = $models_contacts->get_contacts_reorder();
			$display  = $models_display->get();
			$button   = $models_button->get();
			$box      = $models_box->get();

			include_once $file;
		}
	}

	public function add_frontend_css() {
		$models_scheme = Models_Scheme::instance();
		$models_button = Models_Button::instance();
		$scheme        = $models_scheme->get();
		$button        = $models_button->get();
		?>
			<style>
				:root {
				<?php

				unset( $scheme['_wp_http_referer'] );
				unset( $scheme['_scheme_form_nonce'] );
				foreach ( $scheme as $key => $value ) {
					if ( $value != '' ) {
						if ( is_numeric( $value ) ) {
							$value = "{$value}px";
						}
						printf( '--%s-scheme-%s:%s;', QLWAPP_DOMAIN, esc_attr( str_replace( '_', '-', $key ) ), esc_attr( $value ) );
					}
				}

				unset( $button['_wp_http_referer'] );
				unset( $button['_button_form_nonce'] );

				foreach ( $button as $key => $value ) {
					if ( $value != '' ) {
						if ( ! str_contains( $key, 'animation' ) ) {
							continue;
						}
						if ( str_contains( $key, 'animation_delay' ) ) {
							$value = "{$value}s";
						}
						printf( '--%s-button-%s:%s;', QLWAPP_DOMAIN, esc_attr( str_replace( '_', '-', $key ) ), esc_attr( $value ) );
					}
				}

				?>
				}
			</style>
			<?php
	}

	public function box_display1( $show ) {
		global $wp_query;
		$models_display = Models_Display::instance();
		$display        = $models_display->get();
		if ( is_customize_preview() ) {
			return true;
		}
		$entity_visibility = Entity_Visibility::instance();
		return $entity_visibility->is_show_view( $display );
	}

	public function do_shortcode( $atts, $content = null ) {
		$models_button = Models_Button::instance();
		$button        = $models_button->get();

		$atts = wp_parse_args( $atts, $button );

		ob_start();
		?>
			<div style="width: auto;" id="qlwapp" class="qlwapp qlwapp-js-ready <?php printf( 'qlwapp-%s qlwapp-%s', esc_attr( $atts['layout'] ), esc_attr( $atts['rounded'] === 'yes' ? 'rounded' : 'square' ) ); ?>">
				<a class="qlwapp-toggle" data-action="open" data-phone="<?php echo esc_attr( $atts['phone'] ); ?>" data-message="<?php echo esc_html( $atts['message'] ); ?>" href="#" target="_blank">
				<?php if ( $atts['icon'] ) : ?>
						<i class="qlwapp-icon <?php echo esc_attr( $atts['icon'] ); ?>"></i>
					<?php endif; ?>
					<i class="qlwapp-close" data-action="close">&times;</i>
				<?php if ( $atts['text'] ) : ?>
						<span class="qlwapp-text"><?php echo esc_html( $content ); ?></span>
					<?php endif; ?>
				</a>
			</div>
			<?php
			return ob_get_clean();
	}

	public function display() {

		$is_elementor_library = isset( $_GET['post_type'] ) && $_GET['post_type'] === 'elementor_library' && isset( $_GET['render_mode'] ) && $_GET['render_mode'] === 'template-preview';

		if ( $is_elementor_library ) {
			return;
		}

		$models_display    = Models_Display::instance();
		$display           = $models_display->get();
		$entity_visibility = Entity_Visibility::instance();

		if ( ! is_admin() && $entity_visibility->is_show_view( $display ) ) {
			do_action( 'qlwapp_load' );
		}
	}

	public function load() {
		add_action( 'wp_enqueue_scripts', array( $this, 'add_js' ) );
		add_action( 'wp_head', array( $this, 'add_frontend_css' ), 200 );
		add_action( 'wp_footer', array( $this, 'add_box' ) );
	}

	public static function instance() {
		if ( ! isset( self::$instance ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}
}
