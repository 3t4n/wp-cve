<?php
/**
 * This File is used for displaying Sidebar Menus.
 *
 * @author Tech Banker
 * @package wp-cleanup-optimizer/includes
 * @version 3.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // exit if accessed directly.
}
if ( ! is_user_logged_in() ) {
	return;
} else {
	$access_granted = false;
	if ( isset( $user_role_permission ) && count( $user_role_permission ) > 0 ) {
		foreach ( $user_role_permission as $permission ) {
			if ( current_user_can( $permission ) ) {
				$access_granted = true;
				break;
			}
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
								<img src="<?php echo esc_attr( plugins_url( 'assets/global/img/logo.png', dirname( __FILE__ ) ) ); ?>" alt ="Clean Up Optimizer"/>
							</a>
						</div>
						<li id="ux_li_dashboard">
							<a href="javascript:;">
								<i class="icon-custom-grid"></i>
								<span class="title">
									<?php echo esc_attr( $cpo_dashboard ); ?>
								</span>
							</a>
							<ul class="sub-menu">
								<li id="ux_li_wp_optimizer">
									<a href="admin.php?page=cpo_dashboard">
										<i class="icon-custom-note"></i>
										<span class="title">
											<?php echo esc_attr( $cpo_wp_optimizer ); ?>
										</span>
									</a>
								</li>
								<li id="ux_li_wp_scheduled_optimizer">
									<a href="admin.php?page=cpo_schedule_optimizer">
										<i class="icon-custom-hourglass"></i>
										<span class="title">
										</span><span class="badge">Pro</span>
											<?php echo esc_attr( $cpo_schedule_wp_optimizer ); ?>
										</span>
									</a>
								</li>
								<li id="ux_cpo_li_db_optimizer">
									<a href="admin.php?page=cpo_db_optimizer">
										<i class="icon-custom-book-open"></i>
										<span class="title">
											<?php echo esc_attr( $cpo_database_optimizer ); ?>
										</span>
									</a>
								</li>
								<li id="ux_cpo_li_schedule_db_optimizer">
									<a href="admin.php?page=cpo_schedule_db_optimizer">
										<i class="icon-custom-screen-tablet"></i>
										<span class="title">
											<?php echo esc_attr( $cpo_schedule_database_optimizer ); ?>
										</span><span class="badge">Pro</span>
										</span>
									</a>
								</li>
							</ul>
						</li>
						<li id="ux_li_logs">
							<a href="javascript:;">
								<i class="icon-custom-docs"></i>
								<span class="title">
									<?php echo esc_attr( $cpo_logs_label ); ?>
								</span>
							</a>
							<ul class="sub-menu">
								<li id="ux_li_logins_logs">
									<a href="admin.php?page=cpo_login_logs">
										<i class="icon-custom-clock"></i>
										<span class="title">
											<?php echo esc_attr( $cpo_logs_recent_login_logs ); ?>
										</span>
									</a>
								</li>
								<li id="ux_li_visitor_logs">
									<a href="admin.php?page=cpo_visitor_logs">
										<i class="icon-custom-user"></i>
										<span class="title">
											<?php echo esc_attr( $cpo_logs_visitor_logs ); ?>
										</span>
									</a>
								</li>
								<li id="ux_li_live_traffic">
									<a href="admin.php?page=cpo_live_traffic">
										<i class="icon-custom-directions"></i>
										<span class="title">
											<?php echo esc_attr( $cpo_logs_live_traffic ); ?>
										</span>
									</a>
								</li>
							</ul>
						</li>
						<li id="ux_li_cron_jobs">
							<a href="javascript:;">
								<i class="icon-custom-speedometer"></i>
								<span class="title">
									<?php echo esc_attr( $cpo_cron_jobs_label ); ?>
								</span>
							</a>
							<ul class="sub-menu">
								<li id="ux_li_core_cron_jobs">
									<a href="admin.php?page=cpo_core_jobs">
										<i class="icon-custom-folder"></i>
										<span class="title">
											<?php echo esc_attr( $cpo_cron_core_jobs_label ); ?>
										</span>
									</a>
								</li>
								<li id="ux_li_custom_cron_jobs">
									<a href="admin.php?page=cpo_custom_jobs">
										<i class="icon-custom-layers"></i>
										<span class="title">
											<?php echo esc_attr( $cpo_cron_custom_jobs_label ); ?>
										</span>
									</a>
								</li>
							</ul>
						</li>
						<li id="ux_li_general_settings">
							<a href="javascript:;">
								<i class="icon-custom-settings"></i>
								<span class="title">
									<?php echo esc_attr( $cpo_general_settings_label ); ?>
								</span>
							</a>
							<ul class="sub-menu">
								<li id="ux_li_notifications_setup">
									<a href="admin.php?page=cpo_notifications_setup">
										<i class="icon-custom-bell"></i>
										<span class="title">
											<?php echo esc_attr( $cpo_notifications_setup ); ?>
										</span><span class="badge">Pro</span>
										</span>
									</a>
								</li>
								<li id="ux_li_message_settings">
									<a href="admin.php?page=cpo_message_settings">
										<i class="icon-custom-envelope"></i>
										<span class="title">
											<?php echo esc_attr( $cpo_message_settings ); ?>
										</span><span class="badge">Pro</span>
										</span>
									</a>
								</li>
								<li id="ux_li_email_templates">
									<a href="admin.php?page=cpo_email_templates">
										<i class="icon-custom-link"></i>
										<span class="title">
											<?php echo esc_attr( $cpo_email_templates_label ); ?>
										</span><span class="badge">Pro</span>
										</span>
									</a>
								</li>
								<li id="ux_li_roles_capabilities">
									<a href="admin.php?page=cpo_roles_and_capabilities">
										<i class="icon-custom-users"></i>
										<span class="title">
											<?php echo esc_attr( $cpo_roles_capabilities_label ); ?>
										</span><span class="badge">Pro</span>
										</span>
									</a>
								</li>
							</ul>
						</li>
						<li id="ux_li_security_settings">
							<a href="javascript:;">
								<i class="icon-custom-lock"></i>
								<span class="title">
									<?php echo esc_attr( $cpo_security_settings ); ?>
								</span>
							</a>
							<ul class="sub-menu">
								<li id="ux_li_blockage_settings">
									<a href="admin.php?page=cpo_blockage_settings">
										<i class="icon-custom-shield"></i>
											<span class="title">
												<?php echo esc_attr( $cpo_blockage_settings ); ?>
											</span>
										</a>
									</li>
									<li id="ux_li_manage_ip_addresses">
										<a href="admin.php?page=cpo_ip_addresses">
											<i class="icon-custom-globe"></i>
											<span class="title">
												<?php echo esc_attr( $cpo_block_unblock_ip_addresses ); ?>
											</span>
										</a>
									</li>
									<li id="ux_li_manage_ip_ranges">
										<a href="admin.php?page=cpo_ip_ranges">
											<i class="icon-custom-paper-clip"></i>
											<span class="title">
												<?php echo esc_attr( $cpo_block_unblock_ip_ranges ); ?>
											</span>
										</a>
									</li>
									<li id="ux_li_block_unblock_countries">
										<a href="admin.php?page=cpo_block_unblock_countries">
											<i class="icon-custom-target"></i>
											<span class="title">
												<?php echo esc_attr( $cpo_block_unblock_countries ); ?>
											</span><span class="badge">Pro</span>
											</span>
										</a>
									</li>
								</ul>
							</li>
							<li id="ux_li_other_settings">
								<a href="admin.php?page=cpo_other_settings">
									<i class="icon-custom-wrench"></i>
									<span class="title">
										<?php echo esc_attr( $cpo_general_other_settings ); ?>
									</span>
								</a>
							</li>
							<li id="ux_li_feature_requests">
								<a href="https://wordpress.org/support/plugin/wp-clean-up-optimizer" target="_blank">
									<i class="icon-custom-star"></i>
									<span class="title">
										<?php echo esc_attr( $cpo_feature_request_label ); ?>
									</span>
								</a>
							</li>
							<li id="ux_li_system_information">
								<a href="admin.php?page=cpo_system_information">
									<i class="icon-custom-screen-desktop"></i>
									<span class="title">
										<?php echo esc_attr( $cpo_system_information_label ); ?>
									</span>
								</a>
							</li>
							<li id="ux_li_feature_requests">
								<a href="https://tech-banker.com/clean-up-optimizer/pricing/" target="_blank">
									<i class="icon-custom-briefcase"></i>
									<span class="title" style="color: yellow !important;">
										<strong><?php echo esc_attr( $cpo_upgrade ); ?></strong>
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
