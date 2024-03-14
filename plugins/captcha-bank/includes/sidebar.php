<?php
/**
 * This file is used for displaying sidebar menus.
 *
 * @author  Tech Banker
 * @package captcha-bank/includes
 * @version 3.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly
if ( ! is_user_logged_in() ) {
	return;
} else {
	$access_granted = false;
	foreach ( $user_role_permission as $permission ) {
		if ( current_user_can( $permission ) ) {
			$access_granted = true;
			break;
		}
	}
	if ( ! $access_granted ) {
		return;
	} else {
		?>
		<div class="page-sidebar-wrapper-tech-banker">
		<div class="page-sidebar-tech-banker navbar-collapse collapse">
			<div class="sidebar-menu-tech-banker">
				<ul class="page-sidebar-menu-tech-banker" data-slide-speed="200">
					<div class="sidebar-search-wrapper" style="padding:20px;text-align:center">
					<a class="plugin-logo" href="<?php echo esc_attr( TECH_BANKER_BETA_URL ); ?>" target="_blank">
						<img src="<?php echo esc_attr( plugins_url( 'assets/global/img/logo-captcha-bank.png', dirname( __FILE__ ) ) ); ?>" alt="Captcha Bank">
					</a>
					</div>
					<li class="" id="ux_li_captcha_settings">
					<a href="admin.php?page=captcha_bank">
								<i class="icon-custom-layers"></i>
								<span class="title">
									<?php echo esc_attr( $cpb_captcha_wizard_label ); ?>
								</span>
							</a>
						</li>
						<li class="" id="ux_li_general_settings">
					<a href="javascript:;">
						<i class="icon-custom-settings"></i>
						<span class="title">
							<?php echo esc_attr( $cpb_general_settings_menu ); ?>
						</span>
						<span class="badge">Pro</span>
					</a>
					<ul class="sub-menu">
						<li id="ux_li_notification_setup">
							<a href="admin.php?page=captcha_bank_notifications_setup">
								<i class="icon-custom-bell"></i>
								<span class="title">
									<?php echo esc_attr( $cpb_notification_setup_label ); ?>
								</span>
							</a>
						</li>
						<li id="ux_li_message_settings">
							<a href="admin.php?page=captcha_bank_message_settings">
								<i class="icon-custom-envelope"></i>
								<span class="title">
									<?php echo esc_attr( $cpb_message_settings_label ); ?>
								</span>
							</a>
						</li>
						<li id="ux_li_email_templates">
							<a href="admin.php?page=captcha_bank_email_templates">
								<i class="icon-custom-link"></i>
								<span class="title">
									<?php echo esc_attr( $cpb_email_templates_menu ); ?>
								</span>
							</a>
						</li>
						<li id="ux_li_roles_capabilities">
							<a href="admin.php?page=captcha_bank_roles_capabilities">
								<i class="icon-custom-users"></i>
								<span class="title">
									<?php echo esc_attr( $cpb_roles_and_capabilities_menu ); ?>
								</span>
							</a>
						</li>
					</ul>
					</li>
					<li id="ux_li_blockage_settings">
						<a href="admin.php?page=captcha_bank_blockage_settings">
							<i class="icon-custom-shield"></i>
							<span class="title">
								<?php echo esc_attr( $cpb_blockage_settings_label ); ?>
							</span>
						</a>
				</li>
				<li id="ux_li_block_unblock_ip_address">
					<a href="admin.php?page=captcha_bank_block_unblock_ip_addresses">
						<i class=icon-custom-globe></i>
						<span class="title">
							<?php echo esc_attr( $cpb_ip_address_range ); ?>
						</span>
					</a>
				</li>
					<li id="ux_li_whitelist_ip_addresses">
						<a href="admin.php?page=captcha_bank_whitelist_ip_addresses">
							<i class=icon-custom-target></i>
							<span class="title">
								<?php echo esc_attr( $cpb_whitelist_ip_address ); ?>
							</span>
						</a>
					</li>
					<li id="ux_li_block_unblock_countries">
						<a href="admin.php?page=captcha_bank_block_unblock_countries">
							<i class="icon-custom-lock"></i>
							<span class="title">
								<?php echo esc_attr( $cpb_block_unblock_countries_label ); ?>
							</span>
							<span class="badge">Pro</span>
						</a>
					</li>
					</li>
					<li id="ux_li_other_settings">
					<a href="admin.php?page=captcha_bank_other_settings">
						<i class="icon-custom-wrench"></i>
						<span class="title">
							<?php echo esc_attr( $cpb_other_settings_menu ); ?>
						</span>
					</a>
					</li>
					<li id="ux_li_feature_requests">
						<a href="https://wordpress.org/support/plugin/captcha-bank" target="_blank">
							<i class="icon-custom-call-out"></i>
							<span class="title">
								<?php echo esc_attr( $cpb_feature_requests ); ?>
							</span>
						</a>
					</li>
					<li id="ux_li_system_information">
					<a href="admin.php?page=captcha_bank_system_information">
						<i class="icon-custom-screen-desktop"></i>
						<span class="title">
							<?php echo esc_attr( $cpb_system_information_menu ); ?>
						</span>
					</a>
					</li>
					<li id="ux_li_system_information">
					<a href="https://tech-banker.com/captcha-bank/pricing/" target="_blank" >
						<i class="icon-custom-briefcase"></i>
						<span class="title" style="color: yellow !important;">
							<strong><?php echo esc_attr( $cpb_upgrade ); ?></strong>
						</span>
					</a>
					</li>
				</ul>
			</div>
		</div>
		</div>
		<div class="page-content-wrapper">
		<div class="page-content">
		<?php
	}
}
