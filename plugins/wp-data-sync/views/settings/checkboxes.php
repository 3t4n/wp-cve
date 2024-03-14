<?php
/**
 * Admin Checkboxes
 *
 * Admin settings checkboxes.
 *
 * @since   1.3.4
 *
 * @package WP_Data_Sync
 */

namespace WP_DataSync\App;

if ( ! defined( 'ABSPATH' ) ) {
	return;
} ?>

<?php foreach ( $options as $option ) { ?>

	<?php extract( $option ); ?>

	<?php $checked = in_array( $value, $values ) ? 'checked' : ''; ?>

	<tr class="widefat">

		<th scope="row"><label for="<?php esc_attr_e( $id ); ?>"><?php esc_html_e( $label ); ?></label></th>

		<td>
			<input
				type="checkbox"
				value="<?php esc_attr_e( $value ); ?>"
				name="<?php esc_attr_e( $key ); ?>[]"
				id="<?php esc_attr_e( $id ); ?>"
				class="<?php esc_attr_e( $class ); ?>"
				<?php esc_attr_e( $checked ); ?>
			>

			<?php toottip( $args ); ?>
			<?php message( $args ); ?>

		</td>

	</tr>

<?php } ?>