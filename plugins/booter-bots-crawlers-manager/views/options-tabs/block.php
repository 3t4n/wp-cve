<?php
$woocommerce_plugin_active = is_plugin_active( 'woocommerce/woocommerce.php' );
$block_useragents = isset( $settings['block']['block_useragents'] ) ? sanitize_text_field( $settings['block']['block_useragents'] ) : 'bots';
$enabled_woocommerce = isset( $settings['block']['enabled_woocommerce'] ) ? sanitize_text_field( $settings['block']['enabled_woocommerce'] ) : 'yes';
$enabled_woocommerce = $woocommerce_plugin_active ? $enabled_woocommerce : 'no';
$block_empty_useragents = isset( $settings['block']['block_empty_useragents'] ) ? sanitize_text_field( $settings['block']['block_empty_useragents'] ) : 'yes';
$regex_enabled = isset( $settings['block']['regex_enabled'] ) ? sanitize_text_field( $settings['block']['regex_enabled'] ) : 'no';
$http_response = isset( $settings['block']['http_response'] ) ? sanitize_text_field( $settings['block']['http_response'] ) : '410';

$strings = isset( $settings['block']['strings'] ) ? ( is_array( $settings['block']['strings'] ) ? $settings['block']['strings'] : json_decode( $settings['block']['strings'] ) ) : [];
$strings = htmlspecialchars( json_encode( array_map( 'sanitize_text_field', $strings ) ) );
$regex = isset( $settings['block']['regex'] ) ? ( is_array( $settings['block']['regex'] ) ? $settings['block']['regex'] : json_decode( $settings['block']['regex'] ) ) : [];
$regex = htmlspecialchars( json_encode( array_map( 'sanitize_text_field', $regex ) ) );
?>

<p class="notice notice-info">
	<?php esc_html_e( 'Block access to predefined URLs, or cleanup old spam URLs by setting a corresponding HTTP status code.', 'booter' ); ?><br>
</p>
<p class="notice notice-warning">
    <span aria-hidden="true" class="dashicons dashicons-info"></span>
	<?php esc_html_e( 'Note: If you are in the process of handling spam links, make sure to disable all types of automatic redirects to https/www, this is done to allow search engines to crawl the links and reach the HTTP 410 status. If this is not done, search engines will see a redirect - which will cause the link to remain indexed for a long time. After the treatment is complete make sure to re-enable all required redirects.', 'booter' ); ?>
</p>

<table class="form-table">
	<tr valign="top">
        <th scope="row"><label for="booter-block-block_useragents"><?php esc_html_e( 'Apply To', 'booter' ); ?></label></th>
		<td>
            <radio-toggle id="booter-block-block_useragents"
                          name="booter_settings[block][block_useragents]"
                          value="<?php echo $block_useragents; ?>"
                          options='<?php echo json_encode( [ 'bots' => __( 'Known Bots', 'booter' ), 'all' => __( 'Everyone', 'booter' ) ] ); ?>'
            ></radio-toggle>
            <p class="description">
				<?php esc_html_e( 'Choose who will be blocked by Booter, only known bots, or everyone including logged-in users.', 'booter' ); ?>
            </p>
		</td>
	</tr>

    <tr valign="top">
        <th scope="row"><label for="booter-block-block_empty_useragents"><?php esc_html_e( 'Block Empty User Agents', 'booter' ); ?></label></th>
        <td>
            <booter-switch id="booter-block-block_empty_useragents" name="booter_settings[block][block_empty_useragents]" value="<?php echo $block_empty_useragents; ?>"></booter-switch>
            <p class="description">
				<?php esc_html_e( 'Every browser has a user agent identifying it, the operating system, and the device it is running on. This option will block access to users identified with a blank user agent.', 'booter' ); ?>
            </p>
        </td>
    </tr>

	<tr valign="top">
        <th scope="row"><label for="booter-block-enabled_woocommerce"><?php esc_html_e( 'Include WooCommerce Filtering URLs', 'booter' ); ?></label></th>
		<td>
            <booter-switch id="booter-block-enabled_woocommerce" name="booter_settings[block][enabled_woocommerce]" value="<?php echo $enabled_woocommerce; ?>" <?php disabled( false, $woocommerce_plugin_active ); ?>></booter-switch>
            <p class="description">
                <?php esc_html_e( 'Some of the WooCommerce URLs such as add-to-cart buttons and filters can cause infinite loops for search engines if when the theme doesn\'t provide a noindex/nofollow property.', 'booter' ); ?>
                <br>
                <?php printf(
                    esc_html__( 'For example for a badly coded theme: %s which includes a URL to the same URL with another (same) filter %s which again includes the same filter, and so on.', 'booter' ),
                    sprintf( '<code>%s</code>', site_url( '/?filter_brand=example' ) ),
	                sprintf( '<code>%s</code>', site_url( '/?filter_brand=example&filter_brand=example' ) )
                ); ?>
            </p>
		</td>
	</tr>

	<tr valign="top">
        <th scope="row"><label for="booter-block-strings"><?php esc_html_e( 'URL Strings', 'booter' ); ?></label></th>
		<td>
            <tags-list-single value="<?php echo $strings; ?>" id="booter-block-strings" add-label-text="<?php esc_html_e( 'Add a string to block', 'booter' ); ?>" name="booter_settings[block][strings]"></tags-list-single>
            <p class="description">
				<?php esc_html_e( 'Booter will search for these strings in the URL a bot is trying to access and will block the request if it finds one of the strings.', 'booter' ); ?>
            </p>
        </td>
	</tr>

    <tr valign="top">
        <th scope="row"><label for="booter-block-regex_enabled"><?php esc_html_e( 'Regular Expression Based Blocks', 'booter' ); ?></label></th>
        <td>
            <booter-switch id="booter-block-regex_enabled" name="booter_settings[block][regex_enabled]" value="<?php echo $regex_enabled; ?>" type="warning" data-toggle-on=".js-booter-block-regex"></booter-switch>
            <p class="description">
				<?php esc_html_e( 'This is an advanced option, only enable if you know what you are doing.', 'booter' ); ?>
            </p>
        </td>
    </tr>
    <tr valign="top" class="js-booter-block-regex">
        <th scope="row"><label for="booter-block-regex"><?php esc_html_e( 'Regular Expressions', 'booter' ); ?></label></th>
        <td>
            <strings-list id="booter-block-regex"
                                 name="booter_settings[block][regex]"
                                 value="<?php echo $regex; ?>"
                                 add-label-text="<?php echo esc_attr_x( 'Regular Expression eg. ^/index.php?filter=.*$', 'regular expression string placeholder', 'booter' ); ?>"></strings-list>
        </td>
    </tr>

	<tr valign="top">
        <th scope="row"><label for="booter-block-http_response"><?php esc_html_e( 'Block HTTP Response', 'booter' ); ?></label></th>
		<td>
			<select id="booter-block-http_response" name="booter_settings[block][http_response]">
				<option value="401" <?php selected( '401', $http_response ); ?>>
					<?php esc_html_e( '401 Unauthorized - The crawler is not permitted to access this URL', 'booter' ); ?>
				</option>
				<option value="403" <?php selected( '403', $http_response ); ?>>
					<?php esc_html_e( '403 Forbidden - Access to this specific URL is not allowed', 'booter' ); ?>
				</option>
				<option value="404" <?php selected( '404', $http_response ); ?>>
					<?php esc_html_e( '404 Page Not Found - There is nothing in this URL (search engines might continue crawling this URL in case something will be there in the future)', 'booter' ); ?>
				</option>
				<option value="410" <?php selected( '410', $http_response ); ?>>
					<?php esc_html_e( '410 Gone - The URL will never be available', 'booter' ); ?>
					<?php esc_html_e( '(Recommended)', 'booter' ); ?>
				</option>
			</select>
			<br>
			<p class="description"><?php esc_html_e( 'This is the response returned when blocking. Each response means something different to search engines, so make sure to use the most correct response.', 'booter' ); ?></p>
		</td>
	</tr>
</table>

<?php submit_button(); ?>
