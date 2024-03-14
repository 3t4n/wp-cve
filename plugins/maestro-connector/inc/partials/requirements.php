<p class="thin">
	<?php esc_html_e( 'We\'re sorry, but your site is not compatible with the Maestro plugin.', 'maestro-connector' ); ?>
</p>
<p class="thin error">
	<?php echo esc_html( $compatible->get_error_message() ); ?>
</p>
