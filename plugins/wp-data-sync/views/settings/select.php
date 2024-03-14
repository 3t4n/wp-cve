<?php
/**
 * Select
 *
 * Settings select.
 *
 * @since   1.0
 *
 * @package WP_Data_Sync
 */

namespace WP_DataSync\App;

if ( ! defined( 'ABSPATH' ) ) {
	return;
} ?>

<select
	name="<?php esc_attr_e( $name ); ?>"
	id="<?php esc_attr_e( $name ); ?>"
	class="<?php esc_attr_e( $class ); ?>"
>

	<option value="-1"><?php _e( 'Select One', 'wp-data-sync' ); ?></option>

	<?php foreach( $values as $value => $label ) { ?>

		<?php $choice = $selected === $value ? 'selected' : ''; ?>

		<option
			value="<?php esc_attr_e( $value ); ?>"
			<?php esc_attr_e( $choice ); ?>
		><?php esc_html_e( $label ); ?></option>

	<?php } ?>

</select>

<?php toottip( $args ); ?>
<?php message( $args ); ?>
