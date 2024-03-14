<?php
/*
Per Post Language v1.3
Copyright (C) 2016 Fahad Alduraibi

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/


// Add settings page entry under WordPress default Settings menu
function ppl_settings_menu() {
	add_options_page(
		__('Settings') . ' - Per Post Language ',
		'Per Post Language',
		'manage_options',
		'ppl_settings_page',
		'ppl_settings_page'
	);
}
add_action( 'admin_menu', 'ppl_settings_menu' );

// The settings page for the plugin
function ppl_settings_page(){
	?>
	<div class="wrap">
		<h1><?php _e('Settings'); ?> - Per Post Language</h1>
		<?php
		if( isset( $_POST['submit'] ) ) {
			if( ! isset( $_POST['_ppl_nonce'] ) || ! wp_verify_nonce( $_POST['_ppl_nonce'], 'updating_language_settings' )) {
				echo '<div class="error settings-error notice is-dismissible"><p><strong>';
				esc_html_e('Error: Could not validate the data integrity!', 'per-post-language');
				echo '</strong></p><button type="button" class="notice-dismiss"><span class="screen-reader-text">' . __('Dismiss this notice.') . '</span></button></div>';
			} else {
				$pplSaveLanguages = array();
				foreach ($_POST as $key => $value) {
					if ( substr( $key, 0, 8) == 'pplLang:' ) {
						$pplSaveLanguages[substr( $key, 8)]['name'] = $value;
					} else if ( substr( $key, 0, 7) == 'pplDir:' ) {
						$pplSaveLanguages[substr( $key, 7)]['dir'] = $value;
					}
				}
				update_option( "ppl_options", $pplSaveLanguages);
				echo '<div id="setting-error-settings_updated" class="updated settings-error notice is-dismissible"><p><strong>';
				_e('Settings saved.');
				echo '</strong></p><button type="button" class="notice-dismiss"><span class="screen-reader-text">' . __('Dismiss this notice.') . '</span></button></div>';
			}
		}
		?>
		<p><?php esc_html_e('Select the language that you like to be available for your posts from the menu:', 'per-post-language'); ?></p>
		<table class="form-table"><tbody>
			<tr>
			<th style="padding: 0 10px 0 0;"><label for="langList"><h2><?php esc_html_e('Available languages', 'per-post-language'); ?></h2></label></th>
			<td>
				<select id="langList">
					<option value="en_US">English (United States)</option>
					<?php
					require_once( ABSPATH . 'wp-admin/includes/translation-install.php' );
					$langs = wp_get_available_translations();
					foreach ($langs as $key => $value) {
						?><option value="<?php echo $value['language']; ?>"><?php echo $value['native_name']; ?></option><?php
					}
				?></select>
				<span class="button" onClick="pplAddNewLang()"><?php esc_html_e('Add Language', 'per-post-language'); ?></span>
			</td>
			</tr>
			<tr>
				<th style="padding: 0 10px 0 0;"><h2><?php esc_html_e('Selected languages:', 'per-post-language'); ?></h2></th>
			</tr>
		</tbody></table>
		<?php
		$pplOptions=get_option("ppl_options");
		?><form method="POST" action="">
			<table id="langTable"><tbody>
				<tr>
					<td></td>
					<td style="padding: 5px 10px; text-align: center;"><?php esc_html_e('Language Name', 'per-post-language'); ?></td>
					<td style="padding: 5px 10px; text-align: center;"><?php esc_html_e('is RTL?', 'per-post-language'); ?></td>
					<td style="padding: 5px 10px; text-align: center;"><?php esc_html_e('Downloaded?', 'per-post-language'); ?></td>
				</tr>
				<?php
				if( $pplOptions != false ) {
					foreach ($pplOptions as $key => $value) {
						// This 'if' is for people upgrading from old version with only one dimensional array
						if (empty($value['name'])) {
							$value_array['name'] = $value;
						} else {
							$value_array = $value;
						}
						
						$download_status = '<span style="color:green" class="dashicons dashicons-yes"></span>';
						if ( $key != 'en_US' ) {
							// Check to see if we already have the language files downloaded
							if ( !in_array( $key, get_available_languages() ) ) {
								// If not downloaded check if WordPress has access to the filesystem without asking for credentials
								if ( wp_can_install_language_pack() == true ) {
									$downloadResult = wp_download_language_pack( $key );
									// If result is "false" then it faild to 'download' or 'save' the files!
									if ( $downloadResult == false ) {
										$download_status = '<span style="color:red" class="dashicons dashicons-no"></span>';
										echo '<div class="error settings-error notice is-dismissible"><p><strong>';
										esc_html_e('Error: Could not download language files for', 'per-post-language');
										echo ' ' . $value_array['name'] . '</strong></p><button type="button" class="notice-dismiss"><span class="screen-reader-text">' . __('Dismiss this notice.') . '</span></button></div>';
									}
								} else {
									$download_status = '<span style="color:red" class="dashicons dashicons-no"></span>';
									echo '<div class="error settings-error notice is-dismissible"><p><strong>';
									esc_html_e('Error: Could not save language files for', 'per-post-language');
									echo ' ' . $value_array['name'] . ', ';
									esc_html_e('check the language folder write permission.', 'per-post-language');
									echo '</strong></p><button type="button" class="notice-dismiss"><span class="screen-reader-text">' . __('Dismiss this notice.') . '</span></button></div>';
								}
								
							}
						}
						?><tr>
							<td style="text-align: right; padding: 5px 10px;"><span class="button" onclick="pplRemoveLang(this)"><?php esc_html_e('Remove Language', 'per-post-language'); ?></span></td>
							<td style="padding: 5px 10px;"><input type="text" name="pplLang:<?php echo $key; ?>" value="<?php echo $value_array['name']; ?>" readonly style="background-color: white;"></td>
							<td style="padding: 5px 10px; text-align: center;"><input type="checkbox" name="pplDir:<?php echo $key; ?>" <?php if (isset($value_array['dir']) and $value_array['dir'] == 'on'){ echo "checked=\"checked\"";} ?>></td>
							<td style="padding: 5px 10px; text-align: center;"><?php echo $download_status; ?></td>
						</tr><?php
					}
				}
				?>
			</tbody></table>
			
			<p>
				<?php esc_html_e('* The option (is RTL) is used to set the text direction for the title and body when editing posts only and has no effect on how it is displayed to the visitors. Changing the direction of the displayed posts is done by editing the theme.', 'per-post-language'); ?>
			</p>

			<p class="submit">
				<?php wp_nonce_field( 'updating_language_settings', '_ppl_nonce' ); ?>
				<input name="submit" id="submit" class="button button-primary" value="<?php _e('Save Changes'); ?>" type="submit">
				<br />
				<strong><?php esc_html_e('* When saving new languages their translation files will be downloaded from wordpress.org if they do not exist.', 'per-post-language'); ?></strong>
			</p>
		</form>
	</div>
	<script>
		function pplAddNewLang() {
			var langList = document.getElementById("langList");
			var key = langList.options[langList.selectedIndex].value;
			var value = langList.options[langList.selectedIndex].text;

			var table = document.getElementById("langTable");
			var row = table.insertRow(table.rows.length);
			var cell1 = row.insertCell(0);
			var cell2 = row.insertCell(1);
			var cell3 = row.insertCell(2);
			
			cell1.innerHTML = '<span class="button" onclick="pplRemoveLang(this)"><?php esc_html_e('Remove Language', 'per-post-language'); ?></span>';
			cell1.style = 'text-align: right; padding: 5px 10px;';
			cell2.innerHTML = '<input type="text" name="pplLang:' + key + '" value="' + value + '" readonly style="background-color: white;">';
			cell2.style = 'padding: 5px 10px;';
			cell3.innerHTML = '<input type="checkbox" name="pplDir:' + key + '">';
			cell3.style = 'padding: 5px 10px; text-align: center;';
		}

		function pplRemoveLang(rowRef) {
			var pNode=rowRef.parentNode.parentNode;
			pNode.parentNode.removeChild(pNode);
		}
	</script>
<?php
}
?>
