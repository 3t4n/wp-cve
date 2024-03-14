<?php
/**
 * Admin Checkbox
 *
 * Admin settings checkbox.
 *
 * @since   1.0.0
 *
 * @package WP_Data_Sync
 */

namespace WP_DataSync\App;

if ( ! defined( 'ABSPATH' ) ) {
	return;
} ?>

<input
	type="checkbox"
	value="checked"
	name="<?php esc_attr_e( $key ); ?>"
	id="<?php esc_attr_e( $key ); ?>"
	class="<?php esc_attr_e( $class ); ?>"
	<?php esc_attr_e( $value ); ?>
>

<?php toottip( $args ); ?>
<?php message( $args ); ?>
