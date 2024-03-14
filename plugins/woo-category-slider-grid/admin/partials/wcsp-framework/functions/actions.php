<?php
/**
 * Framework actions file.
 *
 * @link       https://shapedplugin.com/
 * @since      1.0.0
 * @package    Woo_Category_Slider
 * @subpackage Woo_Category_Slider/framework
 * @author     ShapedPlugin <support@shapedplugin.com>
 */

if ( ! defined( 'ABSPATH' ) ) {
	die;
} // Cannot access directly.


if ( ! function_exists( 'wpsp_get_option' ) ) {
	/**
	 * The wpsp_get_option function.
	 *
	 * @param string $option The option unique ID.
	 * @param mixed  $default The default value for the option.
	 * @return statement
	 */
	function wpsp_get_option( $option = '', $default = null ) {
		$options = get_option( 'sp_wcsp_settings' );
		return ( isset( $options[ $option ] ) ) ? $options[ $option ] : $default;
	}
}

if ( ! function_exists( 'spf_get_icons' ) ) {
	/**
	 *
	 * Get icons from admin ajax
	 *
	 * @since 1.0.0
	 * @version 1.0.0
	 */
	function spf_get_icons() {

		if ( ! empty( $_POST['nonce'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['nonce'] ) ), 'spf_icon_nonce' ) ) {

			ob_start();

			SP_WCS::include_plugin_file( 'fields/icon/default-icons.php' );

			$icon_lists = apply_filters( 'spf_field_icon_add_icons', spf_get_default_icons() );

			if ( ! empty( $icon_lists ) ) {

				foreach ( $icon_lists as $list ) {

					echo ( count( $icon_lists ) >= 2 ) ? '<div class="spf-icon-title">' . wp_kses_post( $list['title'] ) . '</div>' : '';

					foreach ( $list['icons'] as $icon ) {
						echo '<a class="spf-icon-tooltip" data-spf-icon="' . esc_attr( $icon ) . '" title="' . esc_attr( $icon ) . '"><span class="spf-icon spf-selector"><i class="' . esc_attr( $icon ) . '"></i></span></a>';
					}
				}
			} else {
				echo '<div class="spf-text-error">' . esc_html__( 'No data provided by developer', 'woo-category-slider-grid' ) . '</div>';
			}
			wp_send_json_success(
				array(
					'success' => true,
					'content' => ob_get_clean(),
				)
			);
		} else {
			wp_send_json_error(
				array(
					'success' => false,
					'error'   => esc_html__( 'Error while saving.', 'woo-category-slider-grid' ),
					'debug'   => $_REQUEST,
				)
			);
		}
	}
	add_action( 'wp_ajax_spf-get-icons', 'spf_get_icons' );
}

if ( ! function_exists( 'spf_reset_ajax' ) ) {
	/**
	 *
	 * Reset Ajax
	 *
	 * @since 1.0.0
	 * @version 1.0.0
	 */
	function spf_reset_ajax() {
		if ( ! empty( $_POST['unique'] ) && ! empty( $_POST['nonce'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['nonce'] ) ), 'spf_backup_nonce' ) ) {
			delete_option( wp_unslash( $_POST['unique'] ) );
			wp_send_json_success( array( 'success' => true ) );
		}
		wp_send_json_error(
			array(
				'success' => false,
				'error'   => esc_html__( 'Error while saving.', 'woo-category-slider-grid' ),
				'debug'   => $_REQUEST,
			)
		);
	}
	add_action( 'wp_ajax_spf-reset', 'spf_reset_ajax' );
}


if ( ! function_exists( 'spf_set_icons' ) ) {
	/**
	 *
	 * Set icons for wp dialog
	 *
	 * @since 1.0.0
	 * @version 1.0.0
	 */
	function spf_set_icons() {
		$screen = get_current_screen();
		if ( 'product_cat' === $screen->taxonomy ) { ?>
			<div id="spf-modal-icon" class="spf-modal spf-modal-icon">
				<div class="spf-modal-table">
				<div class="spf-modal-table-cell">
					<div class="spf-modal-overlay"></div>
					<div class="spf-modal-inner">
					<div class="spf-modal-title">
						<?php esc_html_e( 'Add Icon', 'woo-category-slider-grid' ); ?>
						<div class="spf-modal-close spf-icon-close"></div>
					</div>
					<div class="spf-modal-header spf-text-center">
						<input type="text" placeholder="<?php esc_html_e( 'Search a Icon...', 'woo-category-slider-grid' ); ?>" class="spf-icon-search" />
					</div>
					<div class="spf-modal-content">
						<div class="spf-modal-loading"><div class="spf-loading"></div></div>
						<div class="spf-modal-load"></div>
					</div>
					</div>
				</div>
				</div>
			</div>
			<?php
		}
	}
	add_action( 'admin_footer', 'spf_set_icons' );
	add_action( 'customize_controls_print_footer_scripts', 'spf_set_icons' );
}
