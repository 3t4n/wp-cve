<?php
namespace Login_With_AJAX\AJAXify;

class Admin {
	
	public static function init(){
		// Admin
		add_action('lwa_settings_page_general', array( static::class, 'admin_settings' ));
	}
	
	public static function admin_settings(){
		$lwa = get_option('lwa_data', array());
		?>
		<table class="form-table">
			<tr valign="top">
				<th scope="row">
					<label for="lwa_ajaxify_wp_login"><?php esc_html_e("AJAXify Login Forms", 'login-with-ajax-pro'); ?></label>
				</th>
				<td>
					<input type="checkbox" name="lwa_ajaxify" id="lwa_ajaxify_wp_login" value='1' <?php echo ( !empty($lwa['ajaxify']) ) ? 'checked':''; ?> >
					<p><em><?php esc_html_e('Add AJAX effects to the regular WP Login forms, preventing a full page reload for every login, password recovery or registration attempt.', 'login-with-ajax-pro'); ?></em></p>
					<p><em><?php echo sprintf(esc_html__('Additionally, other supported plugins with login forms can be "AJAXified" if enabled on the %s setting tab above.', 'login-with-ajax-pro'), '<code>'. esc_html__('Integrations', 'login-with-ajax') . '</code>'); ?></em></p>
				</td>
			</tr>
		</table>
		<?php
	}
	
}
Admin::init();