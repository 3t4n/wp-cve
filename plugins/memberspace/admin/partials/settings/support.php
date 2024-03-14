<section id="support-tab" class="<?php echo esc_attr( $this->class_for_tab( 'support' ) ); ?> memberspace-tab-content">
	<h3><?php _ex('MemberSpace Support', 'tab header', 'memberspace'); ?></h3>
	<p>
		<?php echo sprintf(
			__('You can always view our %1$show-to guides%2$s on using MemberSpace with WordPress.', 'memberspace'),
			'<a href="' . esc_url( MemberSpace::SUPPORT_URI ) . '" target="_blank">',
			'</a>'
		); ?>
	</p>
	<p>
		<?php echo sprintf(
			__('You can also email us at %1$s%2$s%3$s and we\'ll get back to you in under an hour (Monday - Friday, 10am to 6pm ET).', 'memberspace'),
			'<a href="mailto:' . esc_html( MemberSpace::SUPPORT_EMAIL ) . '">',
			esc_html( MemberSpace::SUPPORT_EMAIL ),
			'</a>'
		); ?>
	</p>
</section>
