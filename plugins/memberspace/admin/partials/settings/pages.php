<section id="pages-tab" class="<?php echo esc_attr( $this->class_for_tab( 'pages' ) ); ?> memberspace-tab-content">
	<h3><?php _ex('Member Pages', 'tab header', 'memberspace'); ?></h3>
	<p>
		<?php echo sprintf(
			__('These are the pages MemberSpace is currently protecting on your live website. You can modify these pages via your %1$sMemberSpace backend%2$s.', 'memberspace'),
			'<a href="' . esc_url( $this->memberspace_backend_site_url() ) . '" target="_blank">',
			'</a>'
		); ?>
	</p>

	<?php
		$rules = get_option( 'memberspace_rules' );

		if ( $rules ) {
			include_once( plugin_dir_path( __FILE__ ) . 'rules/list.php' );
		}
	?>
</section>
