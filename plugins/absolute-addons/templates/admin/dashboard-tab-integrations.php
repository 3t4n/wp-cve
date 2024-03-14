<?php
/**
 * Dashboard Main Layout
 *
 * @package ABSP
 * @since 1.0.0
 * @version 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	die();
}

?>
	<div class="absp-settings-panel integrations">
		<div class="absp-settings-panel__body">
			<form action="" class="absp-integration-settings" id="absp-integration-settings" method="post">
			<div class="absp-admin-options">
				<?php self::render_option_fields( 'integrations' ); ?>
				<div class="clear"></div>
				<div class="submit-div">
					<button type="submit" class="btn-gr absp-admin--save"><?php esc_html_e('SAVE SETTINGS', 'absolute-addons'); ?></button>
				</div>
			</div>
			</form>
		</div>
	</div>
<?php
// End of file dashboard-tab-integrations.php.
