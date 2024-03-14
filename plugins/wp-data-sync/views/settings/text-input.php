<?php
/**
 * Admin Input
 *
 * Admin settings input.
 *
 * @since  1.0
 *
 * @package WP_Data_Sync
 */

namespace WP_DataSync\App;

if ( ! defined( 'ABSPATH' ) ) {
	return;
} ?>

<input
	type="<?php esc_attr_e( $type ); ?>"
	name="<?php esc_attr_e( $key ); ?>"
	id="<?php esc_attr_e( $key ); ?>"
	value="<?php esc_attr_e( $value ); ?>"
	class="<?php esc_attr_e( $class ); ?>"
	placeholder="<?php esc_attr_e( $placeholder ); ?>"
>

<?php toottip( $args ); ?>
<?php message( $args ); ?>
