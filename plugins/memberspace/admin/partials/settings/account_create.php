<section id="account-tab" class="<?php echo $this->class_for_tab( 'account' ); ?> memberspace-tab-content">
	<h3><?php _ex('Set up your MemberSpace account', 'account create tab header', 'memberspace'); ?></h3>
	<p><?php _ex('To finish installing your plugin, please create your MemberSpace account:', 'account create tab description', 'memberspace'); ?></p>
	<p>
		<a href="<?php
			echo add_query_arg( array(
				'cms' => 'wordpress',
				'source' => 'admin',
				'site-url' => urlencode( get_home_url() ),
				'admin-url' => urlencode( get_admin_url() ),
				'site-name' => urlencode( get_bloginfo( 'name' ) ),
				'admin-email' => urlencode( get_option( 'admin_email' ) ),
				'utm_source' => 'memberspace',
				'utm_medium' => 'wp_plugin'
			), esc_url( MemberSpace::SIGNUP_URI ) );
		?>" class="button button-primary" referrerpolicy="unsafe-url">
			<?php _ex('Create your account', 'account create tab CTA button label', 'memberspace'); ?>
	  </a>
	</p>
	<form action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" method="post">
		<input type="hidden" name="action" value="manual_refresh_site_config">
		<p>
			&#x1F44B;&nbsp;
			<?php
				echo sprintf(
					__('If you already have a MemberSpace account, all you need to do is %1$sclick here to connect it to your WordPress site.%2$s', 'memberspace'),
					'<a href="#" onclick="handleMsFormSubmit(event)">',
					'</a>'
				);
			?>
		</p>
	</form>
</section>
