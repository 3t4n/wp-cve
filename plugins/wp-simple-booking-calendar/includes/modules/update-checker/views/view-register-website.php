<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

$serial_key = get_option( 'wpsbc_serial_key', '' );
$website_id = get_option( 'wpsbc_registered_website_id', '' );

?>

<!-- Serial Key -->
<div id="wpsbc-wpsbc-settings-field-register-website" class="wpsbc-settings-field-wrapper wpsbc-settings-field-inline">

	<label class="wpsbc-settings-field-label"><?php echo __( 'Serial Key', 'wp-simple-booking-calendar' ); ?><?php echo wpsbc_get_output_tooltip( sprintf( __( 'You can find your serial key in your WP Simple Booking Calendar account. %sClick here to go to your account.%s', 'wp-simple-booking-calendar' ), '<a href="https://www.wpsimplebookingcalendar.com/account/" target="_blank">', '</a>' ) ); ?></label>

	<div class="wpsbc-settings-field-inner">

		<input type="text" name="serial_key" <?php echo ( ! empty( $serial_key ) ? 'disabled' : '' ); ?> value="<?php echo esc_attr( $serial_key ); ?>" />

		<?php if( empty( $website_id ) ): ?>
			<a id="wpsbc-register-website-button" class="button-primary" href="<?php echo add_query_arg( array( 'tab' => 'register_website', 'wpsbc_action' => 'register_website', 'wpsbc_token' => wp_create_nonce( 'wpsbc_register_website' ) ) ); ?>"><?php echo __( 'Register Website', 'wp-simple-booking-calendar' ); ?></a>
		<?php else: ?>
			<a id="wpsbc-deregister-website-button" class="button-secondary" href="<?php echo add_query_arg( array( 'tab' => 'register_website', 'wpsbc_action' => 'deregister_website', 'wpsbc_token' => wp_create_nonce( 'wpsbc_deregister_website' ) ) ); ?>"><?php echo __( 'Deregister Website', 'wp-simple-booking-calendar' ); ?></a>
		<?php endif; ?>

	</div>
	
</div>

<!-- Check for Updates -->
<div id="wpsbc-wpsbc-settings-field-register-website" class="wpsbc-settings-field-wrapper wpsbc-settings-field-inline">

	<label class="wpsbc-settings-field-label"><?php echo __( 'Manual Update Check', 'wp-simple-booking-calendar' ); ?><?php echo wpsbc_get_output_tooltip( sprintf( __( 'The plugin by default checks once a day if there is an update available for it and displays if there is one in the %1$sPlugins page%2$s. If you want to do a manual update check and not wait for the automatic one, press the Check for Updates button and then check the %1$sPlugins page%2$s.', 'wp-simple-booking-calendar' ), '<a href="' . admin_url( 'plugins.php' ) . '">', '</a>' ) ); ?></label>

	<div class="wpsbc-settings-field-inner">

		<a id="wpsbc-check-for-updates-button" class="button-secondary" <?php echo ( empty( $serial_key ) || empty( $website_id ) ? 'disabled' : '' ); ?> href="<?php echo add_query_arg( array( 'tab' => 'register_website', 'wpsbc_action' => 'check_for_updates', 'wpsbc_token' => wp_create_nonce( 'wpsbc_check_for_updates' ) ) ); ?>"><?php echo __( 'Check for Updates', 'wp-simple-booking-calendar' ); ?></a>

	</div>
	
</div>


<?php if( !empty( $website_id ) ): ?>
<div class="wpsbc-page-notice notice-info wpsbc-form-changed-notice"> 
    <p><?php echo __( '<strong>IMPORTANT</strong>: After updating, you will need to refresh the Plugins page and manually activate the premium plugin.', 'wp-simple-booking-calendar' ); ?></p>
</div>
<?php endif; ?>