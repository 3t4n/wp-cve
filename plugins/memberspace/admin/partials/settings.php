<div class="memberspace-settings" class="wrap">
	<h2><?php echo esc_html( get_admin_page_title() ); ?></h2>

	<section class="memberspace-settings-container">
		<column class="memberspace-settings-main-column">

			<nav class="memberspace-tabs-container">
				<a id="memberspace-account" href="#account-tab" class="memberspace-tab <?php echo esc_attr( $this->class_for_tab( 'account' ) ); ?>">
					<span class="memberspace-tab-icon dashicons dashicons-admin-users"></span>
					<span class="memberspace-tab-label"><?php _ex('Account', 'tab button', 'memberspace'); ?></span>
				</a>
				<?php if (get_option( 'memberspace_subdomain' )): ?>
					<a id="memberspace-config" href="#configuration-tab" class="memberspace-tab <?php echo esc_attr( $this->class_for_tab( 'configuration' ) ); ?>">
						<span class="memberspace-tab-icon dashicons dashicons-admin-tools"></span>
						<span class="memberspace-tab-label"><?php _ex('Configuration', 'tab button', 'memberspace'); ?></span>
					</a>
					<a id="memberspace-pages" href="#pages-tab" class="memberspace-tab <?php echo esc_attr( $this->class_for_tab( 'pages' ) ); ?>">
						<span class="memberspace-tab-icon dashicons dashicons-privacy"></span>
						<span class="memberspace-tab-label"><?php _ex('Pages', 'tab button', 'memberspace'); ?></span>
					</a>
				<?php endif; ?>
				<a id="memberspace-support"href="#support-tab" class="memberspace-tab <?php echo esc_attr( $this->class_for_tab( 'support' ) ); ?>">
					<span class="memberspace-tab-icon dashicons dashicons-sos"></span>
					<span class="memberspace-tab-label"><?php _ex('Support', 'tab button', 'memberspace'); ?></span>
				</a>
			</nav>

			<?php
				if (get_option( 'memberspace_subdomain' )) {
					include_once( plugin_dir_path( __FILE__ ) . 'settings/account_visit.php' );
				} else {
        	include_once( plugin_dir_path( __FILE__ ) . 'settings/account_create.php' );
				};
			?>
			<?php include_once( plugin_dir_path( __FILE__ ) . 'settings/configuration.php' ); ?>
			<?php include_once( plugin_dir_path( __FILE__ ) . 'settings/pages.php' ); ?>
			<?php include_once( plugin_dir_path( __FILE__ ) . 'settings/support.php' ); ?>

		</column>

		<column class="memberspace-settings-sidebar">
		  <div class="memberspace-settings-sidebar-inner">
				<p>
					<a href="<?php echo esc_url( $this->memberspace_backend_site_url() ); ?>" class="button button-primary" target='_blank'>
						<?php _ex('MemberSpace Backend', 'sidebar open MS admin button', 'memberspace'); ?> &rarr;
					</a>
				</p>

				<p>
					<?php echo sprintf(
						_x('MemberSpace Support:%1$s%2$s%3$s%4$s', 'sidebar contact support row', 'memberspace'),
						'<br>',
						'<a href="mailto:' . esc_html( MemberSpace::SUPPORT_EMAIL ) . '">',
						esc_html( MemberSpace::SUPPORT_EMAIL ),
						'</a>'
					); ?>
				</p>

				<p>
					<?php echo sprintf(
						_x('Plugin Version: %1$s', 'sidebar plugin version row', 'memberspace'),
						esc_html( MEMBERSPACE_PLUGIN_VERSION )
					); ?>
				</p>

				<form action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" method="post">
					<input type="hidden" name="action" value="manual_refresh_site_config">
					Last Synced: <a href="#" onclick="handleMsFormSubmit(event)"><?php echo esc_html( wp_date( 'Y/m/d \a\t g:ia', get_option( 'memberspace_last_updated' ) ) ?: 'never' ); ?></a>
				</form>
			</div>
		</column>
	</section>

</div>
