<?php
if (!defined('ABSPATH')) {
    die('Do not open this file directly.');
}
?><form method="post" action="<?php echo admin_url( "admin-post.php" ); ?>">
	<table class="form-table">
		<tbody>


			<tr id="general-settings">
				<td colspan="2" style="padding:0;">
					<h3><div class="dashicons dashicons-admin-settings"></div>&nbsp;<?php esc_attr_e( 'General Settings', 'jem-woocommerce-exporter' ); ?></h3>
				</td>
			</tr>

			<tr valign="top">
				<th scope="row"><label for="jemex_cron_log"><?php esc_attr_e( 'CRON LOG', 'jem-woocommerce-exporter' ); ?></label></th>
				<td>
					<p>Current Server Time: <?php esc_attr_e(date('Y-m-d H:i:s')); ?></p>
					<textarea readonly cols="100" rows="20" name="jemex_cron_log" id="jemex_cron_log"></textarea>
				</td>
			</tr>
		</tbody>
	</table>
    
    <input type="hidden" name="action" value="save_settings">
	<input type="hidden" name="_wp_http_referer" value="<?php esc_attr_e(urlencode( $_SERVER['REQUEST_URI'] )); ?>">
		 
	
</form>


