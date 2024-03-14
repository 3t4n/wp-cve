<?php
/**
 * This file is used for displaying sidebar menus.
 *
 * @author  Tech Banker
 * @package wp-captcha-booster/includes
 * @version 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} //exit if accessed directly
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
							<a class="plugin-logo" href="<?php echo esc_url( TECH_BANKER_BETA_URL ); ?>" target="_blank">
								<img src="<?php echo esc_attr( plugins_url( 'assets/global/img/logo.png', dirname( __FILE__ ) ) ); ?>" alt="Captcha Booster"/>
							</a>
						</div>
						<li id="ux_li_captcha_setup">
							<a href="javascript:;">
								<i class="icon-custom-grid"></i>
								<span class="title">
									<?php echo esc_attr( $cpb_captcha_setup_menu ); ?>
								</span>
							</a>
							<ul class="sub-menu">
								<li id="ux_li_captcha_type">
									<a href="admin.php?page=cpb_captcha_booster">
										<i class="icon-custom-layers"></i>
										<span class="title">
											<?php echo esc_attr( $cpb_captcha_booster_type_breadcrumb ); ?>
										</span>
									</a>
								</li>
								<li id="ux_li_error_message">
									<a href="admin.php?page=cpb_error_message">
										<i class="icon-custom-shield"></i>
										<span class="title">
											<?php echo esc_attr( $cpb_error_message_common ); ?>
										</span>
										<span class="badge">Pro</span>
									</a>
								</li>
								<li id="ux_li_display_settings">
									<a href="admin.php?page=cpb_display_settings">
										<i class=icon-custom-paper-clip></i>
										<span class="title">
											<?php echo esc_attr( $cpb_display_settings_title ); ?>
										</span>
									</a>
								</li>
							</ul>
						</li>
						<li id="ux_li_logs">
							<a href="javascript:;">
								<i class="icon-custom-docs"></i>
								<span class="title">
									<?php echo esc_attr( $cpb_logs_menu ); ?>
								</span>
							</a>
							<ul class="sub-menu">
								<li id="ux_li_live_traffic">
									<a href="admin.php?page=cpb_live_traffic">
										<i class=icon-custom-directions></i>
										<span class="title">
											<?php echo esc_attr( $cpb_live_traffic_title ); ?>
										</span>
									</a>
								</li>
								<li id="ux_li_recent_login_logs">
									<a href="admin.php?page=cpb_login_logs">
										<i class="icon-custom-clock"></i>
										<span class="title">
											<?php echo esc_attr( $cpb_recent_login_log_title ); ?>
										</span>
									</a>
								</li>
								<li id="ux_li_visitor_logs">
									<a href="admin.php?page=cpb_visitor_logs">
										<i class="icon-custom-users"></i>
										<span class="title">
											<?php echo esc_attr( $cpb_visitor_logs_title ); ?>
										</span>
									</a>
								</li>
							</ul>
						</li>
						<li id="ux_li_advance_security">
							<a href="javascript:;">
								<i class="icon-custom-lock"></i>
								<span class="title">
									<?php echo esc_attr( $cpb_advance_security_menu ); ?>
								</span>
							</a>
							<ul class="sub-menu">
								<li id="ux_li_blocking_options">
									<a href="admin.php?page=cpb_blocking_options">
										<i class="icon-custom-ban"></i>
										<span class="title">
									<?php echo esc_attr( $cpb_blocking_options ); ?>
								</span>
							</a>
						</li>
						<li id="ux_li_manage_ip_addresses">
							<a href="admin.php?page=cpb_manage_ip_addresses">
								<i class="icon-custom-globe"></i>
								<span class="title">
									<?php echo esc_attr( $cpb_manage_ip_addresses ); ?>
								</span>
							</a>
						</li>
						<li id="ux_li_manage_ip_ranges">
							<a href="admin.php?page=cpb_manage_ip_ranges">
								<i class="icon-custom-wrench"></i>
								<span class="title">
									<?php echo esc_attr( $cpb_manage_ip_ranges ); ?>
								</span>
							</a>
						</li>
						<li id="ux_li_country_blocks">
							<a href="admin.php?page=cpb_country_blocks">
								<i class="icon-custom-target"></i>
								<span class="title">
									<?php echo esc_attr( $cpb_country_blocks_menu ); ?>
								</span>
								<span class="badge">Pro</span>
							</a>
						</li>
					</ul>
				</li>
				<li id="ux_li_general_settings">
					<a href="javascript:;">
						<i class="icon-custom-paper-clip"></i>
						<span class="title">
							<?php echo esc_attr( $cpb_general_settings_menu ); ?>
						</span>
					</a>
					<ul class="sub-menu">
						<li id="ux_li_alert_setup">
							<a href="admin.php?page=cpb_alert_setup">
								<i class="icon-custom-bell"></i>
								<span class="title">
									<?php echo esc_attr( $cpb_alert_setup_menu ); ?>
								</span>
								<span class="badge">Pro</span>
							</a>
						</li>
						<li id="ux_li_other_settings">
							<a href="admin.php?page=cpb_other_settings">
								<i class="icon-custom-settings"></i>
								<span class="title">
									<?php echo esc_attr( $cpb_other_settings_menu ); ?>
								</span>
							</a>
						</li>
					</ul>
				</li>
				<li id="ux_li_email_templates">
					<a href="admin.php?page=cpb_email_templates">
						<i class="icon-custom-link"></i>
						<span class="title">
							<?php echo esc_attr( $cpb_email_templates_menu ); ?>
						</span>
						<span class="badge">Pro</span>
					</a>
				</li>

				<li id="ux_li_roles_capabilities">
					<a href="admin.php?page=cpb_roles_and_capabilities">
						<i class="icon-custom-users"></i>
						<span class="title">
							<?php echo esc_attr( $cpb_roles_and_capabilities_menu ); ?>
						</span>
						<span class="badge">Pro</span>
					</a>
				</li>
					<li id="ux_li_support_forum">
						<a href="https://wordpress.org/support/plugin/wp-captcha-booster" target="_blank">
						<i class="icon-custom-users"></i>
						<span class="title">
							<?php echo esc_attr( $cpb_support_forum ); ?>
						</span>
					</a>
					</li>
					<li id="ux_li_system_information">
						<a href="admin.php?page=cpb_system_information">
							<i class="icon-custom-screen-desktop"></i>
							<span class="title">
								<?php echo esc_attr( $cpb_system_information_menu ); ?>
							</span>
						</a>
					</li>
					<li id="ux_li_premium_editions">
						<a href="https://tech-banker.com/captcha-booster/pricing/" target="_blank">
						<i class="icon-custom-briefcase"></i>
						<strong><span class="title" style="color:yellow;">
							<?php echo esc_attr( $cpb_premium ); ?>
							</span></strong>
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
