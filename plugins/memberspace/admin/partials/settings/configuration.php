<section id="configuration-tab" class="<?php echo esc_attr( $this->class_for_tab( 'configuration' ) ); ?> memberspace-tab-content">
	<h3><?php _ex('Configuration', 'tab header', 'memberspace'); ?></h3>
		<p>
			<?php echo sprintf(
				__('These settings link your WordPress website to your MemberSpace account. Once configured, they will protect your WordPress pages using the settings specified in your %1$sMemberSpace backend%2$s.', 'memberspace'),
				'<a href="' . esc_url( $this->memberspace_backend_site_url() ) . '" target="_blank">',
				'</a>'
			); ?>
		</p>

		<form action="options.php" method="post">
			<?php settings_fields( 'memberspace_settings' ); ?>
			<label>
				<b>
					<?php _ex('Enable Extra Security', 'input label', 'memberspace'); ?>
				</b>
				&nbsp;&nbsp;
				<input id='memberspace_extra_security'
					name='memberspace_extra_security'
					type='checkbox'
					value='1'
					<?php checked( 1, get_option( 'memberspace_extra_security' ), true ); ?> />
			</label>
			<p>
				<small>
					<?php echo sprintf(
						__('Enabling Extra Security adds extra protection to your Member Pages and sends a strong signal to search engines like Google to not index the page\'s content in search results. %1$sMore details%2$s.', 'memberspace'),
						'<a href="https://help.memberspace.com/article/72-add-extra-security-to-your-member-pages-in-wordpress" target="_blank">',
						'</a>'
					); ?>
				</small>
			</p>

			<?php submit_button( _x('Save Settings', 'button label', 'memberspace') ); ?>
		</form>
</section>
