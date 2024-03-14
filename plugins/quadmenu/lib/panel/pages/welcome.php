<?php
	use QuadLayers\QuadMenu\Plugin;
?>
	<div class="wrap about-wrap full-width-layout qlwrap">
		<div class="has-2-columns is-wider-left" style="max-width: 100%">
			<div class="column">
				<div class="welcome-header">
					<h1><?php echo QUADMENU_PLUGIN_NAME; ?> <span style="font-size: 24px;color: #555;">v<?php echo QUADMENU_PLUGIN_VERSION; ?></span></h1>
					<div class="about-description">
						<?php esc_html_e( 'Thank you for using our plugin, we\'re very grateful for your welcome. We have worked very much and very hard to release this great product and we will do our absolute best to support it and fix all the issues.', 'quadmenu' ); ?>
					</div>
				</div>
				<hr/>
				<div class="feature-section" style="margin: 15px 0;">
					<h3><?php esc_html_e( 'Easy and fast start', 'quadmenu' ); ?></h3>
					<p>
						<?php esc_html_e( 'The QuadMenu Plugin has a simple and intuitive interface, integrated into the WP dashboard, allowing you to create and customize an unlimited amount of mega menus, without any programming skills.', 'quadmenu' ); ?>
					</p>
					<p>
						<?php printf( __( 'Go to the <a href="%s">Options</a> panel and activate the plugin in your theme locations.', 'quadmenu' ), Plugin::taburl( 0 ) ); ?>
					</p>
				</div>
			</div>
			<div class="column">
				<img src="<?php echo QUADMENU_PLUGIN_URL; ?>assets/backend/img/screenshot.png">
			</div>
		</div>
		<hr/>
		<div class="quadmenu-admin-box-text quadmenu-admin-box-three">
			<h3><?php esc_html_e( 'Documentation', 'quadmenu' ); ?></h3>
			<p>
				<?php esc_html_e( 'Our online documentation will give you important information about the plugin. This is an exceptional resource to start discovering the plugin’s true potential.', 'quadmenu' ); ?>
			</p>
			<a class="button button-primary" href="<?php echo QUADMENU_DOCUMENTATION_URL; ?>" target="_blank"><?php esc_html_e( 'Open documentation', 'quadmenu' ); ?></a>
		</div>
		<div class="quadmenu-admin-box-text quadmenu-admin-box-three">
			<h3><?php esc_html_e( 'Demo', 'quadmenu' ); ?></h3>
			<p>
				<?php esc_html_e( 'Thank you for choosing our mega menu plugin! Here you can see our demo content and some layout examples to explore the QuadMenu features.', 'quadmenu' ); ?>
			</p>
			<a class="button button-primary" href="<?php echo QUADMENU_DEMO_URL; ?>" target="_blank"><?php esc_html_e( 'View demo', 'quadmenu' ); ?></a>
		</div>
		<div class="quadmenu-admin-box-text quadmenu-admin-box-three quadmenu-admin-box-last">
			<h3><?php esc_html_e( 'Support', 'quadmenu' ); ?></h3>
			<p>
				<?php printf( __( 'We offer personalized support to all <a href="%s" target="_blank">QuadMenu PRO</a> users. To get support first you need to create an account and open a ticket in your account.', 'quadmenu' ), QUADMENU_DEMO ); ?>
			</p>
			<a class="button button-primary" href="<?php echo QUADMENU_SUPPORT_URL; ?>" target="_blank"><?php esc_html_e( 'Submit ticket', 'quadmenu' ); ?></a>
			<a class="button button-secondary" href="<?php echo QUADMENU_GROUP_URL; ?>" target="_blank"><?php esc_html_e( 'Join Community', 'quadmenu' ); ?></a>
		</div>
	</div>
