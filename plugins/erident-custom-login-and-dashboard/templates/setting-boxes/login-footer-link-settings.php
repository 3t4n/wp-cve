<?php
/**
 * Login footer link settings template.
 *
 * @package Custom_Login_Dashboard
 */

defined( 'ABSPATH' ) || die( "Can't access directly" );

/**
 * Display login footer link settings template.
 *
 * @param array $settings The plugin settings.
 */
return function ( $settings ) {

	$remove_register_link = isset( $settings['dashboard_check_lost_pass'] ) ? $settings['dashboard_check_lost_pass'] : 0;
	$remove_register_link = 'yes' === strtolower( $remove_register_link ) ? 1 : $remove_register_link;
	$remove_register_link = 'no' === strtolower( $remove_register_link ) ? 0 : $remove_register_link;

	$remove_back_to_blog_link = isset( $settings['dashboard_check_backtoblog'] ) ? $settings['dashboard_check_backtoblog'] : 0;
	$remove_back_to_blog_link = 'yes' === strtolower( $remove_back_to_blog_link ) ? 1 : $remove_back_to_blog_link;
	$remove_back_to_blog_link = 'no' === strtolower( $remove_back_to_blog_link ) ? 0 : $remove_back_to_blog_link;
	?>

	<div class="heatbox dashboard-settings-box">
		<h2>
			<?php _e( 'Footer Link Settings', 'erident-custom-login-and-dashboard' ); ?>
		</h2>
		<div class="setting-fields">

			<div class="field">
				<label for="dashboard_check_lost_pass" class="label checkbox-label">
					<?php _e( 'Remove "Register | Lost your password?" link', 'erident-custom-login-and-dashboard' ); ?>
					<input type="checkbox" name="dashboard_check_lost_pass" id="dashboard_check_lost_pass" value="1" class="general-setting-field" <?php checked( $remove_register_link, 1 ); ?>>
					<div class="indicator"></div>
				</label>
			</div>

			<div class="field">
				<label for="dashboard_check_backtoblog" class="label checkbox-label">
					<?php _e( 'Remove "Back to website" link', 'erident-custom-login-and-dashboard' ); ?>
					<input type="checkbox" name="dashboard_check_backtoblog" id="dashboard_check_backtoblog" value="1" class="general-setting-field" <?php checked( $remove_back_to_blog_link, 1 ); ?>>
					<div class="indicator"></div>
				</label>
			</div>

		</div>
	</div>

	<?php
};
