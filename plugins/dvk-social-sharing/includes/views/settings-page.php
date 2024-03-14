<?php

if( ! defined("DVKSS_VERSION") ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	exit;
}

$networks = array(
	'twitter' => 'Twitter',
	'facebook' => 'Facebook',
	'googleplus' => 'Google Plus',
    'linkedin' => 'LinkedIn',
);

?><div id="dvkss" class="wrap">
	<div class="dvkss-container">
		<div class="dvkss-column dvkss-primary">

			<h1>Social Sharing &nbsp; <small style="font-weight: normal; font-style: italic; font-size: 70%;"><?php _e( 'by' ); ?> Danny van Kooten</small></h1>


		<form id="dvkss_settings" method="post" action="options.php">
			<?php settings_fields( 'dvk_social_sharing' ); ?>

			<h2><?php _e('Settings'); ?></h2>

			<table class="form-table">

				<tr valign="top">
					<th scope="row">
						<?php _e('Text before links', 'dvk-social-sharing'); ?>
					</th>
					<td>
						<input type="text" name="dvk_social_sharing[before_text]" id="dvkss_text" class="widefat" placeholder="Share this post:" value="<?php echo esc_attr($opts['before_text']); ?>">
						<small><?php _e('The text to show before the sharing links.', 'dvk-social-sharing'); ?></small>
					</td>
				</tr>

				<tr valign="top">
					<th scope="row">
						<?php _e('Add to', 'dvk-social-sharing'); ?>
					</th>
					<td>
						<ul>
						<?php foreach( $post_types as $post_type_id => $post_type ) { ?>
							<li>
								<label>
									<input type="checkbox" name="dvk_social_sharing[auto_add_post_types][]" value="<?php echo esc_attr( $post_type_id ); ?>" <?php checked( in_array( $post_type_id, $opts['auto_add_post_types'] ), true ); ?>> <?php echo $post_type->labels->name; ?>
								</label>
							</li>
						<?php } ?>
						</ul>

						<small><?php _e('Automatically adds the sharing links to the end of the selected post types.', 'dvk-social-sharing'); ?></small>
					</td>
				</tr>

				<tr valign="top">
					<th scope="row">
						<?php _e('Social networks', 'dvk-social-sharing'); ?>
					</th>
					<td>
						<ul>
							<?php foreach( $networks as $network_slug => $network_name ) { ?>
								<li>
									<label>
										<input type="checkbox" name="dvk_social_sharing[social_options][]" value="<?php echo esc_attr( $network_slug ); ?>" <?php checked( in_array( $network_slug, $opts['social_options'] ), true ); ?>> <?php echo $network_name; ?>
									</label>
								</li>
							<?php } ?>
						</ul>

						<small><?php _e('Show these social network options.', 'dvk-social-sharing'); ?></small>
					</td>
				</tr>

				<tr valign="top" class="row-load-icon-css">
					<th scope="row">
						<?php _e('Load icon CSS?', 'dvk-social-sharing'); ?>
					</th>
					<td>
						<label><input type="radio" name="dvk_social_sharing[load_icon_css]" value="1" <?php checked($opts['load_icon_css'], 1); ?> > <?php _e('Yes'); ?></label> &nbsp;
						<label><input type="radio" name="dvk_social_sharing[load_icon_css]" value="0" <?php checked($opts['load_icon_css'], 0); ?> > <?php _e('No'); ?></label>
						<br>
						<small><?php _e('Adds simple but pretty icons to the sharing links.', 'dvk-social-sharing'); ?></small>
					</td>
				</tr>

				<tr valign="top" class="row-icon-size">
					<th scope="row">
						<label for="dvkss_icon_size"><?php _e('Icon size', 'dvk-social-sharing'); ?></label>
					</th>
					<td>
						<select name="dvk_social_sharing[icon_size]" id="dvkss_icon_size" class="widefat">
							<option value="16" <?php selected($opts['icon_size'], 16); ?> ><?php _e('Small'); ?> - 16x16 <?php _e( 'pixels' ); ?></option>
							<option value="32" <?php selected($opts['icon_size'], 32); ?> ><?php _e('Normal'); ?> - 32x32 <?php _e( 'pixels' ); ?></option>
							<option value="48" <?php selected($opts['icon_size'], 48); ?> ><?php _e('Large'); ?> - 48x48 <?php _e( 'pixels' ); ?></option>
						</select>
					</td>
				</tr>

				<tr valign="top">
					<th scope="row">
						<?php _e('Load pop-up JS?', 'dvk-social-sharing'); ?>
					</th>
					<td>
						<label><input type="radio" name="dvk_social_sharing[load_popup_js]" value="1" <?php checked($opts['load_popup_js'], 1); ?> > <?php _e('Yes'); ?></label> &nbsp;
						<label><input type="radio" name="dvk_social_sharing[load_popup_js]" value="0" <?php checked($opts['load_popup_js'], 0); ?> > <?php _e('No'); ?></label>
						<br>
						<small><?php _e("A small JavaScript file of just 600 bytes so people won't have to leave your website to share.", 'dvk-social-sharing'); ?></small>
					</td>
				</tr>

				<tr valign="top">
					<th scope="row">
						<label for="dvkss_twitter_username"><?php _e('Twitter username', 'dvk-social-sharing'); ?></label>
					</th>
					<td>
						<input type="text" name="dvk_social_sharing[twitter_username]" id="dvkss_twitter_username" class="widefat" placeholder="@yourtwitter" value="<?php echo esc_attr($opts['twitter_username']); ?>">
						<small><?php _e('Set this if you want to append "via @yourtwitter" to tweets.', 'dvk-social-sharing'); ?></small>
					</td>
				</tr>

			</table>

			<?php
				submit_button();
			?>

		</form>

	</div>

	<!-- Start dvkss Sidebar -->
	<div class="dvkss-column dvkss-secondary">

		<div class="dvkss-box">
			<h3 class="dvkss-title"><?php _e( 'Enjoying this plugin?', 'dvk-social-sharing' ); ?></h3>
			<p><?php _e( 'If you like this plugin, consider supporting it in one the following ways.', 'dvk-social-sharing' ); ?></p>

            <ul class="ul-square">
				<li><a href="https://wordpress.org/support/view/plugin-reviews/dvk-social-sharing?rate=5#postform"><?php printf( __( 'Leave a %s review on WordPress.org', 'dvk-social-sharing' ), '&#9733;&#9733;&#9733;&#9733;&#9733;' ); ?></a></li>
				<li><a href="https://twitter.com/intent/tweet/?text=<?php echo urlencode('Need social sharing options for your WordPress site? This plugin is great: '); ?>&url=<?php echo urlencode('https://wordpress.org/plugins/dvk-social-sharing/'); ?>">Tweet about the plugin</a></li>
			</ul>

            <p>Or check out the following plugins by the same author.</p>
            <ul class="ul-square">
                <li>
                    <a href="https://wordpress.org/plugins/mailchimp-for-wp/">Mailchimp for WordPress</a><br />
                    The easiest way to connect your WordPress site to Mailchimp.
                </li>
                <li>
                    <a href="https://wordpress.org/plugins/koko-analytics/">Koko Analytics</a><br />
                    Privacy-friendly analytics for your WordPress site.
                </li>
                <li>
                    <a href="https://wordpress.org/plugins/html-forms/">HTML Forms</a><br />
                    A fast & flexible way to add a contact form to your site.
                </li>
                <li>
                    <a href="https://wordpress.org/plugins/boxzilla/">Boxzilla Pop-Ups</a><br />
                    Allows you to show pop-ups at just the right time.
                </li>
            </ul>

		</div>

		<div class="dvkss-box">
			<h3 class="dvkss-title"><?php _e( 'Looking for support?', 'dvk-social-sharing' ); ?></h3>
			<p><?php printf( __( 'Please use the %splugin support forums%s on WordPress.org.', 'dvk-social-sharing' ), '<a href="https://wordpress.org/support/plugin/dvk-social-sharing">', '</a>' ); ?></p>
		</div>

		<!-- End dvkss Sidebar -->

		<br style="clear:both; " />
	</div>
</div>

