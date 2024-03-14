<div class="memberspace notice notice-success is-dismissible">
	<img class="logo" title="MemberSpace Logo" src="<?php echo esc_url( plugins_url( '/images/ms-icon-round-white.png', __DIR__ ) ); ?>" alt="MemberSpace Logo">
	<p>
		<strong><?php _ex('Thank you for installing MemberSpace\'s plugin!', 'activation banner header', 'memberspace'); ?></strong>
		<br>
		<?php echo sprintf(
			__('Let\'s get started: %1$sConfigure Settings%2$s', 'memberspace'),
			'<a href="' . esc_url( admin_url( 'admin.php?page=memberspace' ) ) . '">',
			'</a>'
		); ?>
	</p>
</div>
