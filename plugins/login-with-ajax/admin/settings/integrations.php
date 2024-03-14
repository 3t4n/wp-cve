<?php
	global $lwa_submit_button, $lwa_data, $wp_version;
?>
<?php do_action('lwa_settings_integrations'); ?>

<table class="form-table">
	<tr valign="top">
		<th>
			<label><?php esc_html_e("Integrate with BuddyPress / BuddyBoss?", 'login-with-ajax'); ?></label>
		</th>
		<td>
			<input type="checkbox" name="lwa_integrate[buddypress]" value='1' class='wide' <?php echo ( !empty($lwa_data['integrate']['buddypress']) && $lwa_data['integrate']['buddypress'] == '1' ) ? 'checked="checked"':''; ?>>
			<p><em><?php echo sprintf( esc_html__('Enable %s integration to integrate our Ajaxify and 2FA features (if enabled) to account pages and login forms.', 'login-with-ajax'), 'BuddyPress & <a href="https://buddyboss.com/" target="_blank">BuddyBoss</a>'); ?></em></p>
		</td>
	</tr>
	<tr valign="top">
		<th>
			<label><?php esc_html_e("Integrate with WooCommerce?", 'login-with-ajax'); ?></label>
		</th>
		<td>
			<input type="checkbox" name="lwa_integrate[woocommerce]" value='1' class='wide' <?php echo ( !empty($lwa_data['integrate']['woocommerce']) && $lwa_data['integrate']['woocommerce'] == '1' ) ? 'checked="checked"':''; ?>>
			<p><em><?php echo sprintf( esc_html__('Enable %s integration to integrate our Ajaxify and 2FA features (if enabled) to account pages and login forms.', 'login-with-ajax'), 'WooCommerce'); ?></em></p>
		</td>
	</tr>
	<tr valign="top">
		<th>
			<label><?php esc_html_e("Integrate with Events Manager?", 'login-with-ajax'); ?></label>
		</th>
		<td>
			<input type="checkbox" name="lwa_integrate[events-manager]" value='1' class='wide' <?php echo ( !empty($lwa_data['integrate']['events-manager']) && $lwa_data['integrate']['events-manager'] == '1' ) ? 'checked="checked"':''; ?>>
			<p><em><?php echo sprintf( esc_html__('Enable %s integration to integrate our Ajaxify and 2FA features (if enabled) to account pages and login forms.', 'login-with-ajax'), '<a href="https://wp-events-plugin.com/" target="_blank">Events Manager</a>'); ?></em></p>
		</td>
	</tr>
</table>
	
<?php if( !defined('LWA_PRO_VERSION') && (!defined('LWA_REMOVE_PRO_NAGS') || !LWA_REMOVE_PRO_NAGS) ): ?>
<div style="border:2px dashed #ccc; margin:10px 0px; padding:20px; ">
	<h3><?php esc_html_e('More Integrations', 'login-with-ajax'); ?></h3>
	<p>
		<?php esc_html_e('Add WhatsApp, Twillio (SMS & WhatsApp), Telegram and more!.', 'login-with-ajax'); ?>
	</p>
	<a href="https://loginwithajax.com/gopro/" class="button-primary" target="_blank"><?php esc_html_e('Go Pro!', 'login-with-ajax'); ?></a>
</div>
<?php endif; ?>
<?php do_action('lwa_settings_integrations_footer'); ?>
<?php echo $lwa_submit_button; ?>