<div class="ea-columns ea-noprint">
	<div>
		<div class="ea-document__status <?php echo sanitize_html_class( $bill->get_status() ); ?>)>"><span><?php echo esc_html( $bill->get_status_nicename() ); ?></span></div>
	</div>
	<div>
		<button class="button-secondary" onclick="window.print();"><?php esc_html_e( 'Print', 'wp-ever-accounting' ); ?></button>	</div>
</div>
