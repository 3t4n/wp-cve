<?php
/**
 * Nouvello Wemanage Settings screen
 *
 * @package    Nouvello WeManage Worker
 * @subpackage Core
 * @author     Nouvello Studio
 * @copyright  (c) Copyright by Nouvello Studio
 * @since      1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

?>
<style>
	@media only screen and (max-width: 767px) {
		.wemanage-worker-settings-wrap .mobile-hide{
			display: none;
		}
	}
</style>
<div class="wemanage-worker-settings-wrap" style="background: #fff; padding: 20px 0; display: flex; position: absolute; width: 100%; top: 0; z-index:100">
	<div class="wemanage-welcome">
		<div class="welcome-col pb">
			<div class="wemanage-wrap about-wrap">
				<span class="title-count">Version <?php echo esc_html( NSWMW_VER ); ?></span>
				<h1><?php esc_html_e( 'Welcome to WEmanage', 'ns-wmw' ); ?></h1>
				<div class="about-text">
					<p><?php esc_html_e( 'Thank you for installing our plugin. We hope you\'ll enyoy it.', 'ns-wmw' ); ?><br><?php esc_html_e( 'Please let us know if you need any assistance.', 'ns-wmw' ); ?><br></p>
				</div>
				<div class="about-text">
					<h3><?php esc_html_e( 'Quick start', 'ns-wmw' ); ?></h3>
					<div style="display: flex;">
						<div>
							<img style="max-width: 80px" src="<?php echo esc_url( NSWMW_ROOT_DIR . 'includes/assets/img/one.png' ); ?>"/>
						</div>
						<div>
							<h3 style="margin-top: 20px; color: rgb(50,97,169)"><?php esc_html_e( 'Download app', 'ns-wmw' ); ?></h3>
							<p><?php esc_html_e( 'Download free from the Apple app store or Google play store.', 'ns-wmw' ); ?></p>
							<p><a target="_blank" href="https://play.google.com/store/apps/details?id=com.nouvellostudio.wemanageapp"><img src="<?php echo esc_url( NSWMW_ROOT_DIR . 'includes/assets/img/get-it-google-play.png' ); ?>" style="max-width: 200px">
								</a><a target="_blank" href="https://apps.apple.com/app/wemanage-ecommerce-management/id1637047947"><img src="<?php echo esc_url( NSWMW_ROOT_DIR . 'includes/assets/img/appstore.png' ); ?>" style="max-width: 200px"></a>
							</p>
						</div>
					</div>
					<div style="display: flex;">
						<div>
							<img style="max-width: 80px" src="<?php echo esc_url( NSWMW_ROOT_DIR . 'includes/assets/img/two.png' ); ?>"/>
						</div>
						<div>
							<h3 style="margin-top: 20px; color: rgb(50,97,169)"><?php esc_html_e( 'Create account', 'ns-wmw' ); ?></h3>
							<p><?php esc_html_e( 'Get access to all our great features.', 'ns-wmw' ); ?>
								<a style="color: rgb(50,97,169)" target="_blank" href="https://run.wemanage.app">Create account</a>
							</p>
						</div>
					</div>
					<div style="display: flex;">
						<div>
							<img style="max-width: 80px" src="<?php echo esc_url( NSWMW_ROOT_DIR . 'includes/assets/img/three.png' ); ?>"/>
						</div>
						<div>
							<h3 style="margin-top: 20px; color: rgb(50,97,169)"><?php esc_html_e( 'Already have an account?', 'ns-wmw' ); ?></h3>

							<p><?php esc_html_e( '1. Press activate to start the activation process. ', 'ns-wmw' ); ?><button type="button" id="activate-btn" class="button" style="color: red; border-color: red;">Activate</button></p>
			
							<p><?php esc_html_e( '2. After activating connection, please copy the code below and paste it in the app.', 'ns-wmw' ); ?></p>
							<p><?php echo esc_html( nouvello_wemanage_worker()->init->return_activation_key() ); ?><button type="button" id="copykey-btn" class="button" style="color: rgb(50,97,169); border-color: rgb(50,97,169);">Copy</button></p>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="mobile-hide">
		<img src="<?php echo esc_url( NSWMW_ROOT_DIR . 'includes/assets/img/wemanage-app.png' ); ?>"/>
	</div>
	<div id="nouvello-worker-connection-key">
		<input id="connection-key" type="hidden" value="<?php echo esc_html( nouvello_wemanage_worker()->init->return_activation_key() ); ?>">
		<input id="website-url" type="hidden" value="<?php echo esc_html( get_home_url() ); ?>">
		<input id="website-name" type="hidden" value="<?php echo esc_html( get_bloginfo( 'name' ) ); ?>">
		<input id="website-tagline" type="hidden" value="<?php echo esc_html( get_bloginfo( 'description', 'display' ) ); ?>">
		<input id="ns-wmw-key" type="hidden" value="<?php echo esc_html( get_transient( 'ns-wmw-key' ) ); ?>">
		<input id="ns-wmw-secret" type="hidden" value="<?php echo esc_html( get_transient( 'ns-wmw-secret' ) ); ?>">
		<input id="ns-wmw-wc-key" type="hidden" value="<?php echo esc_html( get_transient( 'ns-wmw-wc-key' ) ); ?>">
		<input id="ns-wmw-wc-secret" type="hidden" value="<?php echo esc_html( get_transient( 'ns-wmw-wc-secret' ) ); ?>">
		<input id="ns-wmw-plugin-version" type="hidden" value="<?php echo esc_html( NSWMW_VER ); ?>">
	</div>
</div>
