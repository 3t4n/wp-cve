<?php

/**
 * Metabox settings
 *
 *
 * @link       https://timersys.com/geotargeting/
 * @since      1.0.0
 *
 * @package    GeoTarget
 * @subpackage GeoTarget/admin/partials
 */
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;?>
<table class="form-table geot_table">

	<?php do_action( 'geot/metaboxes/before_display_options', $opts );?>

	<tr valign="top">
		<th><label for="geot_position"><?php _e( 'Show to the following countries:', 'geot' ); ?></label></th>
		<td>
			<select name="geot[country_code][]" multiple class="geot-chosen-select" data-placeholder="<?php _e( 'Type or choose country name...', 'geot' );?>" >
				<?php
				if( is_array( $countries ) ) {
					foreach ($countries as $c) {
						?>
						<option value="<?php echo $c->iso_code;?>" <?php selected(true, @in_array($c->iso_code, @(array)$opts['country_code']) ); ?>> <?php echo $c->country; ?></option>
						<?php
					}
				}
				?>
			</select>
		</td>
		<td colspan="2"></td>
	</tr>
</table>
<?php wp_nonce_field( 'geot_options', 'geot_options_nonce' ); ?>
