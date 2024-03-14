<?php
/**
 * [Short description]
 *
 * @package    DEVRY\FIP
 * @copyright  Copyright (c) 2024, Developry Ltd.
 * @license    https://www.gnu.org/licenses/gpl-2.0.html GNU Public License
 * @since      1.4
 */

namespace DEVRY\FIP;

! defined( ABSPATH ) || exit; // Exit if accessed directly.

/**
 * Display a notice encouraging users to rate the plugin
 * on WordPress.org and provide options to dismiss the notice.
 */
function fip_display_rating_notice() {
	if ( ! get_option( 'fip_rating_notice', '' ) ) {
		?>
			<div class="notice notice-info is-dismissible fip-admin">
				<h3><?php echo FIP_PLUGIN_NAME; ?></h3>
				<p>
					<?php
					printf(
						wp_kses(
							/* translators: %1$s is replaced with by giving it 5 stars rating */
							__( '✨💪🔌 Could you please kindly help the plugin in your turn %1$s? (Thank you in advance) ' ),
							json_decode( FIP_PLUGIN_ALLOWED_HTML_ARR )
						),
						'<strong>' . __( 'by giving it 5 stars rating', 'featured-image-plus' ) . '</strong>'
					);
					?>
				</p>
				<div class="button-group">
					<a href="<?php echo FIP_PLUGIN_WPORG_RATE; ?>" target="_blank" class="button button-primary">
						<?php echo esc_html__( 'Rate us @ WordPress.org', 'featured-image-plus' ); ?>
						<i class="dashicons dashicons-external"></i>
					</a>
					<a href="<?php echo esc_url( admin_url( 'options-general.php?page=fip_settings&fip_rating_notice_dismiss' ) ); ?>" class="button">
						<?php echo esc_html__( 'I already did', 'featured-image-plus' ); ?>
					</a>
					<a href="<?php echo esc_url( admin_url( 'options-general.php?page=fip_settings&fip_rating_notice_dismiss' ) ); ?>" class="button">
						<?php echo esc_html__( "Don't show this notice again!", 'featured-image-plus' ); ?>
					</a>
				</div>
			</div>
		<?php
	}
}

add_action( 'admin_notices', __NAMESPACE__ . '\fip_display_rating_notice' );
