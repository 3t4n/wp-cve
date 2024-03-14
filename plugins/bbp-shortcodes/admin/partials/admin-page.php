<?php
/**
 * Represents the view for the administration dashboard.
 *
 * This includes the header, options, and other information that should provide
 * The User Interface to the end user.
 *
 * @package BBPS
 */
?>
<div class="wrap">
	<form id="bbpress_shortcodes_options" action="options.php" method="post">
		<?php
			settings_fields( 'bbpress_shortcodes' );
			do_settings_sections( 'bbpress_shortcodes' );
			submit_button( 'Save Options', 'primary', 'bbpress_shortcodes_options_submit' );
		?>
		<div id="after-submit">
			<p>
				<?php esc_html_e( 'Like bbPress Shortcodes?', 'bbpress-shortcodes' ); ?> <a href="https://wordpress.org/support/plugin/bbp-shortcodes/reviews/?filter=5#new-post" target="_blank"><?php esc_html_e( 'Give us a rating', 'bbpress-shortcodes' ); ?></a>
			</p>
			<p>
				<?php esc_html_e( 'Need Help or Have Suggestions?', 'bbpress-shortcodes' ); ?> <?php esc_html_e( 'contact us on', 'bbpress-shortcodes' ); ?> <a href="https://wordpress.org/support/plugin/bbpress-shortcodes/" target="_blank"><?php esc_html_e( 'Plugin support forum', 'bbpress-shortcodes' ); ?></a>
			</p>
		</div>
	 </form>
</div>

