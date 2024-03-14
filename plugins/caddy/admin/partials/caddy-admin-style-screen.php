<?php
/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin style screen of the plugin.
 *
 * @link       https://www.madebytribe.com
 * @since      1.0.0
 *
 * @package    Caddy
 * @subpackage Caddy/admin/partials
 */
?>

<?php
// GET STYLE OPTIONS
$cc_custom_css = get_option( 'cc_custom_css' );
$cc_custom_css = ! empty( $cc_custom_css ) ? esc_html( stripslashes( $cc_custom_css ) ) : '';
?>

<?php do_action( 'caddy_before_color_selectors_section' ); ?>

	<h2><i class="dashicons dashicons-color-picker section-icons"></i>&nbsp;<?php echo esc_html( __( 'Custom Styles', 'caddy' ) ); ?></h2>
	<p><?php echo esc_html( __( 'General style customization options.', 'caddy' ) ); ?></p>
<?php do_action( 'caddy_before_custom_css_row' ); ?>
	<table class="form-table cc-style-table">
		<tbody>
		<tr>
			<th scope="row">
				<label for="cc_custom_css"><?php echo esc_html( __( 'Custom CSS', 'caddy' ) ); ?></label>
			</th>
			<td class="color-picker">
				<label><textarea name="cc_custom_css" id="cc_custom_css" rows="10" cols="50"><?php echo $cc_custom_css; ?></textarea></label>
			</td>
		</tr>
		</tbody>
	</table>
<?php do_action( 'caddy_after_custom_css_row' ); ?>
<?php
$caddy_license_status = get_option( 'caddy_premium_edd_license_status' );
if ( ! isset( $caddy_license_status ) || 'valid' !== $caddy_license_status ) {
	?>
	<div class="cc-unlock-msg">
		<hr>
		<div><span class="dashicons dashicons-unlock"></span><?php echo esc_html( __( 'Unlock 7 different cart icons & 15+ custom color options with ', 'caddy' ) ); ?><a
					href="<?php echo esc_url( 'https://www.usecaddy.com?utm_source=upgrade-notice&amp;utm_medium=plugin&amp;utm_campaign=style-settings-links' ); ?>" target="_blank"><?php echo esc_html( __( 'Caddy Premium Edition', 'caddy' ) ); ?></a></div>
	</div>
	<?php
}

do_action( 'caddy_after_color_selectors_section' );
