<?php
/**
 * Admin settings file for the WP Auto Featured Image plugin.
 *
 * @package WP_Auto_Featured_Image
 */

?>
<div class="wrap">
	<h1>WP Auto Featured Image Settings</h1>
	<div id="poststuff">
		<div id="post-body" class="metabox-holder columns-2">
			<!-- Main Content Area -->
			<div id="post-body-content">
				<div class="meta-box-sortables">
					<form method="post" action="options.php">
						<?php settings_fields( 'wp_auto_featured_image_options' ); ?>
						<?php do_settings_sections( 'wp_auto_featured_image_options' ); ?>
						<input name="Submit" type="submit" class="button button-primary" value="<?php esc_attr_e( 'Save Changes', 'wp-default-featured-image' ); ?>" />
					</form>
				</div>
			</div>
			<!-- Sidebar -->
			<div id="postbox-container-1" class="postbox-container">
				<div class="meta-box-sortables">
					<div class="postbox">
						<h3 class="hndle"><span><?php esc_html_e( 'Support', 'wpafi' ); ?></span></h3>
						<div class="inside">
							<p class="multi-option"><?php esc_html_e( 'Have you encountered any problems with our plugin and need our help? Do you need to ask us any questions?', 'wpafi' ); ?></p>
							<p><?php esc_html_e( 'You can post your questions or issues on the WordPress', 'wpafi' ); ?> <a target="_blank" href="http://wordpress.org/support/plugin/wp-auto-featured-image"><?php esc_html_e( 'Support Forum', 'wpafi' ); ?></a><?php esc_html_e( ' or can directly', 'wpafi' ); ?> <a href="mailto:sannysrivastava@gmail.com?subject=<?php esc_html_e( 'Support Request For WordPress Auto Featured Image From', 'wpafi' ); ?> <?php bloginfo( 'url' ); ?>"><?php esc_html_e( 'email me', 'wpafi' ); ?></a>.</p>
						</div>
					</div>
				</div>
				<div class="metabox-container">
					<div class="postbox">
						<h3 class="hndle"><span><?php esc_html_e( 'Help', 'wpafi' ); ?></span></h3>
						<div class="inside">
							<p class="multi-option"><?php esc_html_e( 'We need your support to make this and other plugins smarter and more helpful for you. If this plugin saved any minutes of your time and effort, kindly take some time for a favor to us and:', 'wpafi' ); ?></p>
							<p>
								1) <a target="_blank" href="http://wordpress.org/support/view/plugin-reviews/wp-auto-featured-image"><?php esc_html_e( 'Rate this plugin.', 'wpafi' ); ?></a><br/>
								2) <?php esc_html_e( 'Make a small', 'wpafi' ); ?> <a href="https://sanny.dev/donate" target="_blank"><?php esc_html_e( 'donation', 'wpafi' ); ?></a> <?php esc_html_e( 'for us.', 'wpafi' ); ?>
							</p>
							<form target="_blank" class="paypal" action="https://www.paypal.com/cgi-bin/webscr" method="post" name="_xclick">
								<input type="hidden" name="cmd" value="_xclick" />
								<input type="hidden" name="business" value="sannysrivastava@gmail.com" />
								<input type="hidden" name="item_name" value="<?php esc_html_e( 'Donate for plugin support', 'wpafi' ); ?>" />
								<input type="hidden" name="currency_code" value="USD" />
								<select name="amount">
									<option value="10.00">10</option>
									<option value="15.00">15</option>
									<option value="20.00">20</option>
									<option value="25.00">25</option>
									<option value="50.00">50</option>
								</select>
								<input type="image" alt="<?php esc_html_e( 'Make payments with PayPal - it\'s fast, free and secure!', 'wpafi' ); ?>" name="submit" src="http://www.paypal.com/en_US/i/btn/btn_donate_LG.gif" />
							</form>
							<p><?php esc_html_e( 'Any help would be very appreciated. Thanks for using this plugin.', 'wpafi' ); ?><br/>
							<?php esc_html_e( 'Have a good day!!', 'wpafi' ); ?></p>
						</div>
					</div>
				</div>
				<div class="metabox-container">
					<div class="postbox">
						<h3 class="hndle"><span><?php esc_html_e( 'Hire Me', 'wpafi' ); ?></span></h3>
						<div class="inside">
							<p class="multi-option"><?php esc_html_e( 'Do you want more customization on this plugin? Or want to develop a new plugin?', 'wpafi' ); ?></p>
							<p><a href="mailto:sannysrivastava@gmail.com?subject=<?php esc_html_e( 'Hire Request From', 'wpafi' ); ?> <?php bloginfo( 'url' ); ?>"><?php esc_html_e( 'Hire me', 'wpafi' ); ?></a></p>
						</div>
					</div>
				</div>
			</div>
		</div> <!-- #post-body -->
		<br class="clear">
	</div> <!-- #poststuff -->
</div>
