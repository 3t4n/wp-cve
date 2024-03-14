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

! defined( ABSPATH ) || exit; // Exit if accessed directly

/**
 * Don't allow to have both Free and Pro active at the same time.
 */
function fip_check_pro_plugin() {
	// Deactitve the Pro version if active.
	if ( is_plugin_active( 'featured-image-plus-pro/featured-image-plus.php' ) ) {
		deactivate_plugins( 'featured-image-plus-pro/featured-image-plus.php', true );
	}
}

register_activation_hook( FIP_PLUGIN_BASENAME, __NAMESPACE__ . '\fip_check_pro_plugin' );

/**
 * Display a promotion for the pro plugin.
 */
function fip_display_upgrade_notice() {
	if ( ! get_transient( 'fip_upgrade_plugin' ) || ! get_option( 'fip_upgrade_notice' ) ) {
		?>
			<div class="notice notice-success is-dismissible fip-admin">
				<h3><?php echo esc_html__( 'Featured Image Plus PRO 🚀' ); ?></h3>
				<p>
					<?php
					printf(
						wp_kses(
							/* translators: %1$s is replaced with Found the free version helpful */
							/* translators: %2$s is replaced with Featured Image Plus Pro */
							__( '✨🎉📢 %1$s? Would you be interested in learning more about the benefits of upgrading to the %2$s? ' ),
							json_decode( FIP_PLUGIN_ALLOWED_HTML_ARR )
						),
						'<strong>' . __( 'Found the free version helpful', 'featured-image-plus' ) . '</strong>',
						'<strong>' . __( 'Featured Image Plus Pro', 'featured-image-plus' ) . '</strong>'
					);
					?>
					<br />
					<?php
					printf(
						wp_kses(
							/* translators: %1$s is replaced with promo code */
							/* translators: %2$s is replaced with 10% off */
							__( 'Use the %1$s code and get %2$s your purchase!' ),
							json_decode( FIP_PLUGIN_ALLOWED_HTML_ARR )
						),
						'<code>' . __( 'FIP10', 'featured-image-plus' ) . '</code>',
						'<strong>' . __( '10% off', 'featured-image-plus' ) . '</strong>'
					);
					?>
				</p>
				<div class="button-group">
					<a href="https://bit.ly/43jkDMW" target="_blank" class="button button-primary button-success">
						<?php echo esc_html__( 'Go Pro', 'featured-image-plus' ); ?>
						<i class="dashicons dashicons-external"></i>
					</a>
					<a href="<?php echo esc_url( admin_url( 'options-general.php?page=fip_settings&fip_upgrade_dismiss' ) ); ?>" class="button">
						<?php echo esc_html__( 'I already did', 'featured-image-plus' ); ?>
					</a>
					<a href="<?php echo esc_url( admin_url( 'options-general.php?page=fip_settings&fip_upgrade_dismiss' ) ); ?>" class="button">
						<?php echo esc_html__( "Don't show this notice again!", 'featured-image-plus' ); ?>
					</a>
				</div>
			</div>
		<?php

		// Set the transient to last for 30 days.
		set_transient( 'fip_upgrade_plugin', true, 30 * DAY_IN_SECONDS );
	}
}

add_action( 'admin_notices', __NAMESPACE__ . '\fip_display_upgrade_notice' );

