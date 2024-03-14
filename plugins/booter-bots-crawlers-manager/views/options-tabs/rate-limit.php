<?php
$enabled_logged_in = isset( $settings['rate_limit']['enabled_logged_in'] ) ? sanitize_text_field( $settings['rate_limit']['enabled_logged_in'] ) : 'no';
$requests_limit = isset( $settings['rate_limit']['requests_limit'] ) ? sanitize_text_field( $settings['rate_limit']['requests_limit'] ) : '30';
$block_for = isset( $settings['rate_limit']['block_for'] ) ? sanitize_text_field( $settings['rate_limit']['block_for'] ) : '300';
$exclude = isset( $settings['rate_limit']['exclude'] ) ? ( is_array( $settings['rate_limit']['exclude'] ) ? $settings['rate_limit']['exclude'] : json_decode( $settings['rate_limit']['exclude'] ) ) : [];
$exclude = htmlspecialchars( json_encode( array_map( 'sanitize_text_field', $exclude ) ) );
?>

<p class="notice notice-info">
    <?php esc_html_e( 'Throttle excessive access from bots, crawlers, and malicious users.', 'booter' ); ?><br>
    <?php esc_html_e( 'When a user exceeds the defined amount of requests per minute he will be blocked for a period of time defined.', 'booter' ); ?><br>
    <?php esc_html_e( 'The block will return a 429 HTTP status code (too many requests), which tells legitimate users/bots to reduce the crawl rate, making them realize that they make request too frequently but are still desirable, unlike 403 status code which means that they are not allowed here.', 'booter' ); ?>
</p>

<table class="form-table">
	<tr valign="top">
        <th scope="row"><label for="booter-rate_limit-enabled_logged_in"><?php esc_html_e( 'Rate Limit Logged In Users', 'booter' ); ?></label></th>
		<td>
            <booter-switch id="booter-rate_limit-enabled_logged_in" name="booter_settings[rate_limit][enabled_logged_in]" value="<?php echo $enabled_logged_in; ?>"></booter-switch>
            <p class="description">
                <?php esc_html_e( 'Choose if you want to rate limit logged in users, otherwise only guests will be rate limited.', 'booter' ); ?>
            </p>
		</td>
	</tr>

	<tr valign="top">
        <th scope="row"><label for="booter-rate_limit-requests_limit"><?php esc_html_e( 'Requests Limit Per Minute', 'booter' ); ?></label></th>
		<td>
			<input id="booter-rate_limit-requests_limit" type="number" name="booter_settings[rate_limit][requests_limit]" class="text"
			       value="<?php echo $requests_limit; ?>"
			       min="10" max="60"/>
			<span class="description"><?php esc_html_e( 'per minute', 'booter' ); ?></span>
            <p class="description">
				<?php esc_html_e( 'This is the maximum number of request a user can make to the website before getting blocked.', 'booter' ); ?>
            </p>
		</td>
	</tr>
	<tr valign="top">
        <th scope="row"><label for="booter-rate_limit-block_for"><?php esc_html_e( 'Block For', 'booter' ); ?></label></th>
		<td>
            <radio-toggle id="booter-rate_limit-block_for"
                          name="booter_settings[rate_limit][block_for]"
                          value="<?php echo $block_for; ?>"
                          options='<?php echo json_encode( [
                                  '300' => sprintf( _n( '%s Minute', '%s Minutes', 5, 'booter' ), 5 ),
                                  '600' => sprintf( _n( '%s Minute', '%s Minutes', 10, 'booter' ), 10 ),
                                  '900' => sprintf( _n( '%s Minute', '%s Minutes', 15, 'booter' ), 15 ),
                                  '1800' => sprintf( _n( '%s Minute', '%s Minutes', 30, 'booter' ), 30 ),
                                  '3600' => sprintf( _n( '%s Hour', '%s Hours', 1, 'booter' ), 1 ),
                              ] ); ?>'
            ></radio-toggle>
            <p class="description">
				<?php esc_html_e( 'This is the time the user will be blocked for with a 429 status code after reaching the requests limit.', 'booter' ); ?>
            </p>
		</td>
	</tr>

    <tr valign="top">
        <th scope="row"><label for="booter-rate_limit-exclude"><?php esc_html_e( 'Excluded User Agents', 'booter' ); ?></label></th>
        <td>
            <tags-list-single value="<?php echo $exclude; ?>" id="booter-rate_limit-exclude" add-label-text="<?php esc_html_e( 'Add a string to block', 'booter' ); ?>" name="booter_settings[rate_limit][exclude]"></tags-list-single>
            <p class="description">
				<?php esc_html_e( 'Any useragent in this list will not be rate limited.', 'booter' ); ?>
				<?php esc_html_e( 'Make sure you add only bots you trust.', 'booter' ); ?>
            </p>
        </td>
    </tr>
</table>

<?php submit_button(); ?>
