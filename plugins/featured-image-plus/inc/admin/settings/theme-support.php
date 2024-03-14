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
 * Display the theme support options.
 */
function fip_theme_support() {
	$fip = new Featured_Image_Plus();

	$theme_support = get_option( 'fip_pro_theme_support', $fip->theme_support );

	// No if empty or non-existent or current theme supports featured images, otherwise select Yes.
	if ( 'yes' === $theme_support || current_theme_supports( 'post-thumbnails' ) ) {
		$theme_support = 'selected';
	}

	printf(
		'<select id="fip-theme-support" name="fip_theme_support">
			<option value="">No</option>
			<option value="yes" %1$s>Yes</option>
		</select>',
		esc_attr( $theme_support )
	);
	?>
		<p class="description">
			<small>
				<?php echo esc_html__( 'Enable theme support for the fetured images.', 'featured-image-plus' ); ?>
			</small>
		</p>
	<?php
}

/**
 * Sanitize and update theme support option.
 */
function fip_sanitize_theme_support( $theme_support ) {
	if ( empty( $_REQUEST['fip_nonce'] )
		|| ! wp_verify_nonce( $_REQUEST['fip_nonce'], 'fip_security' ) ) {
		return;
	}

	if ( empty( $theme_support ) ) {
		return;
	}

	// Option changed and updated.
	if ( get_option( 'fip_theme_support' ) !== $theme_support ) {
		add_settings_error(
			'fip_settings_errors',
			'fip_theme_support',
			esc_html__( 'Theme support option was updated successfully.', 'featured-image-plus' ),
			'updated'
		);
	}

	return sanitize_text_field( $theme_support );
}
