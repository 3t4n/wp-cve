<?php
// Exit if accessed directly
if (!defined('ABSPATH')) exit;

require_once('get_value.php');
?>

<div id="wrapper">
	<!-- Navigation -->
	<nav class="navbar navbar-default navbar-static-top" role="navigation">
		<div class="navbar-header">
			<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
				<span class="sr-only"><?php esc_html_e('Toggle navigation', WEBLIZAR_ACL) ?></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</button>
			<a class="sidebar-toggle hidden-xs" href="javascript:void(0);"><i class="fa fa-bars fa-2x"></i></a>
			<a class="navbar-brand coming-soon-admin-title" href="index.html" style="color:#dc3545;"><?php esc_html_e('Admin Custom Login', WEBLIZAR_ACL); ?></a>
		</div>
		<!-- /.navbar-header -->
		<ul class="nav navbar-top-links navbar-right coming-soon-top">
			<!-- Code for prev Login page-->
			<?php add_thickbox(); ?>

			<!-- /.dropdown -->
			<li class="dropdown" style="display:none">
				<a class="dropdown-toggle" data-toggle="dropdown" href="#">
					<i class="fa fa-bell fa-fw"></i>
				</a>
				<!-- /.dropdown-alerts -->
			</li>
			<!-- /.dropdown -->
			<li class="dropdown" style="display:none">
				<a class="dropdown-toggle" data-toggle="dropdown" href="#">
					<i class="fa fa-info fa-fw"></i>
				</a>
			</li>
			<!-- /.dropdown -->
		</ul>
		<!-- /.navbar-top-links -->
		<div class="navbar-default sidebar" role="navigation">
			<div class="sidebar-nav navbar-collapse">
				<ul class="nav " id="side-menu">
					<li>
						<a class="active" href="#" id="ui-id-1">
							<span class="sidebar-item-icon fa-stack">
								<i class="fa fa-square fa-stack-2x text-primary"></i>
								<i class="fas fa-tachometer-alt fa-stack-1x fa-inverse"></i>
							</span>
							<span class="sidebar-item-title"><?php esc_html_e('Dashboard', WEBLIZAR_ACL); ?></span>
							<span class="sidebar-item-subtitle"><?php esc_html_e('application overview', WEBLIZAR_ACL); ?></span>
						</a>
					</li>
					<li>
						<a href="#" id="ui-id-6">
							<span class="sidebar-item-icon fa-stack ">
								<i class="fa fa-square fa-stack-2x text-primary"></i>
								<i class="fa fa-paint-brush fa-stack-1x fa-inverse"></i>
							</span>
							<span class="sidebar-item-title"><?php esc_html_e('Background Design', WEBLIZAR_ACL); ?></span>
							<span class="sidebar-item-subtitle"><?php esc_html_e('modify background design here', WEBLIZAR_ACL); ?></span>
						</a>
						<!-- /.nav-second-level -->
					</li>
					<li>
						<a href="#" id="ui-id-8">
							<span class="sidebar-item-icon fa-stack ">
								<i class="fa fa-square fa-stack-2x text-primary"></i>
								<i class="fa fa-paint-brush fa-stack-1x fa-inverse"></i>
							</span>
							<span class="sidebar-item-title"><?php esc_html_e('Login form Settings', WEBLIZAR_ACL); ?></span>
							<span class="sidebar-item-subtitle"><?php esc_html_e('modify login design here', WEBLIZAR_ACL); ?></span>
						</a>
						<!-- /.nav-second-level -->
					</li>
					<li>
						<a href="#Text-And-Colour" id="ui-id-7">
							<span class="sidebar-item-icon fa-stack">
								<i class="fa fa-square fa-stack-2x text-primary"></i>
								<i class="fas fa-font fa-stack-1x fa-inverse"></i>
							</span>
							<span class="sidebar-item-title"><?php esc_html_e('Font Settings', WEBLIZAR_ACL); ?></span>
							<span class="sidebar-item-subtitle"><?php esc_html_e('modify login form style here', WEBLIZAR_ACL); ?></span>
						</a>
					</li>

					<li>
						<a href="#" id="ui-id-3">
							<span class="sidebar-item-icon fa-stack ">
								<i class="fa fa-square fa-stack-2x text-primary"></i>
								<i class="fa fa-wrench fa-stack-1x fa-inverse"></i>
							</span>
							<span class="sidebar-item-title"><?php esc_html_e('Logo Settings', WEBLIZAR_ACL); ?></span>
							<span class="sidebar-item-subtitle"><?php esc_html_e('customize logo settings here', WEBLIZAR_ACL); ?></span>
						</a>

						<!-- /.nav-second-level -->
					</li>
					<li>
						<a href="#" id="ui-id-9">
							<span class="sidebar-item-icon fa-stack">
								<i class="fa fa-square fa-stack-2x text-primary"></i>
								<i class="fa fa-table fa-stack-1x fa-inverse"></i>
							</span>
							<span class="sidebar-item-title"><?php esc_html_e('Social Settings', WEBLIZAR_ACL); ?></span>
							<span class="sidebar-item-subtitle"><?php esc_html_e('connect with your social profile', WEBLIZAR_ACL); ?></span>
						</a>
					</li>

					<li>
						<a href="#" id="ui-id-13">
							<span class="sidebar-item-icon fa-stack">
								<i class="fa fa-square fa-stack-2x text-primary"></i>
								<i class="fab fa-google fa-stack-1x fa-inverse"></i>
							</span>
							<span class="sidebar-item-title"><?php esc_html_e('Google Captcha', WEBLIZAR_ACL); ?></span>
							<span class="sidebar-item-subtitle"><?php esc_html_e('configure captcha settings', WEBLIZAR_ACL); ?></span>
						</a>
					</li>

					<li>
						<a href="#" id="ui-id-4">
							<span class="sidebar-item-icon fa-stack">
								<i class="fa fa-square fa-stack-2x text-primary"></i>
								<i class="fa fa-upload fa-stack-1x fa-inverse"></i>
							</span>
							<span class="sidebar-item-title"><?php esc_html_e('Export / Import', WEBLIZAR_ACL); ?></span>
							<span class="sidebar-item-subtitle"><?php esc_html_e('import / export plugin settings', WEBLIZAR_ACL); ?></span>
						</a>
					</li>
					<li>
						<a href="#" id="ui-id-2">
							<span class="sidebar-item-icon fa-stack">
								<i class="fa fa-square fa-stack-2x text-primary"></i>
								<i class="fa fa-question fa-stack-1x fa-inverse"></i>
							</span>
							<span class="sidebar-item-title"><?php esc_html_e('Help And Support', WEBLIZAR_ACL); ?></span>
							<span class="sidebar-item-subtitle"><?php esc_html_e('ask your queries', WEBLIZAR_ACL); ?></span>
						</a>
					</li>
					
					<li>
						<a href="#" id="ui-id-10">
							<span class="sidebar-item-icon fa-stack">
								<i class="fa fa-square fa-stack-2x text-primary"></i>
								<i class="fa fa-star fa-stack-1x fa-inverse"></i>
							</span>
							<span class="sidebar-item-title"><?php esc_html_e('Rate & Donate to Us', WEBLIZAR_ACL); ?></span>
							<span class="sidebar-item-subtitle"><?php esc_html_e('if you like us', WEBLIZAR_ACL); ?></span>
						</a>
					</li>

					<li>
						<a href="<?php echo esc_url('https://weblizar.com/plugins/admin-custom-login-pro/'); ?>" rel="noopener noreferrer" target="_blank">
							<span class="sidebar-item-icon fa-stack">
								<i class="fa fa-square fa-stack-2x text-primary"></i>
								<i class="fa fa-star fa-stack-1x fa-inverse"></i>
							</span>
							<span class="sidebar-item-title"><?php esc_html_e('Get Pro Version', WEBLIZAR_ACL); ?></span>
							<span class="sidebar-item-subtitle"><?php esc_html_e('if love the plugin', WEBLIZAR_ACL); ?></span>
						</a>
					</li>
				</ul>
			</div>
			<!-- /.sidebar-collapse -->
		</div>
		<!-- /.navbar-static-side -->
	</nav>

	<div class="page-wrapper ui-tabs-panel active" id="option-ui-id-1">
		<?php require_once('dashboard/dashboard.php'); ?>
	</div>
	<div class="page-wrapper ui-tabs-panel deactive" id="option-ui-id-3">
		<?php require_once('settings/page-settings.php'); ?>
	</div>
	<div class="page-wrapper ui-tabs-panel deactive" id="option-ui-id-6">
		<?php require_once('design/background.php'); ?>
	</div>
	<div class="page-wrapper ui-tabs-panel deactive" id="option-ui-id-7">
		<?php require_once('design/text_and_color.php'); ?>
	</div>
	<div class="page-wrapper ui-tabs-panel deactive" id="option-ui-id-8">
		<?php require_once('login-form-setting/login-form-background.php'); ?>
	</div>
	<div class="page-wrapper ui-tabs-panel deactive" id="option-ui-id-9">
		<?php require_once('social/social.php'); ?>
	</div>
	<div class="page-wrapper ui-tabs-panel deactive" id="option-ui-id-4">
		<?php require_once('import-export-setting/import_export.php'); ?>
	</div>
	<div class="page-wrapper ui-tabs-panel deactive" id="option-ui-id-2">
		<?php require_once('help/help.php'); ?>
	</div>
	<div class="page-wrapper ui-tabs-panel deactive" id="option-ui-id-10">
		<?php require_once('help/rate.php'); ?>
	</div>
	<div class="page-wrapper ui-tabs-panel deactive" id="option-ui-id-13">
		<?php require_once('googlecaptcha-settings/gcaptcha-settings.php'); ?>
	</div>
	<div class="page-wrapper ui-tabs-panel deactive" id="option-ui-id-18">
	</div>
</div>
<!-- /#wrapper -->
