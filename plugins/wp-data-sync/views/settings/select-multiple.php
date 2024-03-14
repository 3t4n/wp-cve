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
	name="<?php esc_attr_e( $name ); ?>[]"
	id="<?php esc_attr_e( $name ); ?>"
	class="<?php esc_attr_e( $class ); ?>"
	multiple="multiple"
>

	<?php foreach( $options as $value => $label ) { ?>

		<?php $choice = in_array( $value, $selected ) ? 'selected' : ''; ?>

		<option
			value="<?php esc_attr_e( $value ); ?>"
			<?php esc_attr_e( $choice ); ?>
		><?php esc_html_e( $label ); ?></option>

	<?php } ?>

</select>

<?php toottip( $args ); ?>
<?php message( $args ); ?>
