<?php
/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://wpswings.com/
 * @since      3.0.0
 *
 * @package    Pdf_Generator_For_Wp
 * @subpackage Pdf_Generator_For_Wp/admin/partials
 */

if ( ! defined( 'ABSPATH' ) ) {

	exit(); // Exit if accessed directly.
}
global $pgfw_wps_pgfw_obj, $pgfw_wps_wpg_obj;
$pgfw_active_tab        = isset( $_GET['pgfw_tab'] ) ? sanitize_key( $_GET['pgfw_tab'] ) : 'pdf-generator-for-wp-layout-settings'; // phpcs:ignore WordPress.Security.NonceVerification
$pgfw_default_tabs      = $pgfw_wps_pgfw_obj->wps_pgfw_plug_layout_setting_sub_tabs_dummy();
$pgfw_cover_setting_html = apply_filters( 'pgfw_plugin_standard_admin_settings_sub_tabs_dummy', array() );
?>
<main class="wps-main wps-bg-white wps-r-8 wps_pgfw_pro_tag">
	<nav class="wps-navbar">
		<ul class="wps-navbar__items">
			<?php
			if ( is_array( $pgfw_default_tabs ) && ! empty( $pgfw_default_tabs ) ) {

				foreach ( $pgfw_default_tabs as $pgfw_tab_key => $pgfw_default_tabs ) {

					$pgfw_tab_classes = 'wps-link ';
					if ( ! empty( $pgfw_active_tab ) && $pgfw_active_tab === $pgfw_tab_key ) {
						$pgfw_tab_classes .= 'active';
					} elseif ( 'pdf-generator-for-wp-layout-settings' === $pgfw_active_tab ) {
						if ( 'pdf-generator-for-wp-cover-page-setting' === $pgfw_tab_key ) {
							$pgfw_tab_classes .= 'active';
						}
					}
					?>
					<li>
						<a id="<?php echo esc_attr( $pgfw_tab_key ); ?>" href="<?php echo esc_url( admin_url( 'admin.php?page=pdf_generator_for_wp_menu' ) . '&pgfw_tab=' . esc_attr( $pgfw_tab_key ) ); ?>" class="<?php echo esc_attr( $pgfw_tab_classes ); ?>"><?php echo esc_html( $pgfw_default_tabs['title'] ); ?></a>
					</li>
					<?php
				}
			}
			?>
		</ul>
	</nav>
	<section class="wps-section">
		<div>
			<form action="" method="POST" class="wps-pgfw-gen-section-form">
				<div class="pgfw-secion-wrap">
					<?php
					require_once PDF_GENERATOR_FOR_WP_DIR_PATH . 'admin/partials/pdf-generator-for-wp-layout-table.php';
					wp_nonce_field( 'nonce_settings_save', 'wpg_nonce_field' );
					$pgfw_wps_pgfw_obj->wps_pgfw_plug_generate_html( $pgfw_cover_setting_html );
					?>
				</div>
			</form>
		</div>
	</section>
