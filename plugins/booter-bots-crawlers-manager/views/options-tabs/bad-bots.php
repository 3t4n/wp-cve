<?php
$blocked_robots = isset( $settings['block']['badrobots'] ) ? ( is_array( $settings['block']['badrobots'] ) ? $settings['block']['badrobots'] : json_decode( $settings['block']['badrobots'] ) ) : [];
$blocked_robots = htmlspecialchars( json_encode( array_map( 'sanitize_text_field', $blocked_robots ) ) );
$bad_robots_list = get_transient( 'booter_bad_robots' );
?>

<p class="notice notice-info">
	<?php esc_html_e( 'Block bots we identified as malicious, which are causing high server loads from very frequent page crawls, or are used as part of a vulnerability/security breach scans.', 'booter' ); ?><br>
</p>

<table class="form-table">
	<tr valign="top">
		<th scope="row"><label for="booter-badrobots-useragents"><?php esc_html_e( 'Blocked Bot User Agents', 'booter' ); ?></label></th>
		<td>
			<bots-selector id="booter-badrobots-useragents" name="booter_settings[block][badrobots]" action="booter_get_bad_robots_list" value="<?php echo $blocked_robots; ?>"></bots-selector>
		</td>
	</tr>
</table>

<?php submit_button(); ?>
