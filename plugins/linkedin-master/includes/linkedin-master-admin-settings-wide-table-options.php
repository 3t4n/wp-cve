<?php
if(!class_exists('WP_List_Table')){
	require_once( get_home_path() . 'wp-admin/includes/class-wp-list-table.php' );
}
class linkedin_master_admin_settings_wide_table_options extends WP_List_Table {
	/**
	 * Display the rows of records in the table
	 * @return string, echo the markup of the rows
	 */
function display() {
global $wpdb, $blog_id;
//Set Default Language
$linkedin_master_system_wide_language = "en_US";
	if(is_multisite()){
	add_blog_option($blog_id, 'linkedin_master_system_wide', "true");
	add_blog_option($blog_id, 'linkedin_master_system_wide_language', $linkedin_master_system_wide_language);
	}
	else{
	//Set Activate TechGasp Pinterest System and ON
	add_option('linkedin_master_system_wide', "true");
	add_option('linkedin_master_system_wide_language', $linkedin_master_system_wide_language);
	}

//Save Post Options
if (isset($_POST['update_system_wide'])){
	check_admin_referer( 'save-settings_linkedin_master_admin_settings_wide_table_options' );
	if(is_multisite()){
		if (isset($_POST['linkedin_master_system_wide'])){
			update_blog_option($blog_id, 'linkedin_master_system_wide', sanitize_text_field($_POST['linkedin_master_system_wide']));
		}
		else{
			update_blog_option($blog_id, 'linkedin_master_system_wide', 'false' );
		}
		if (isset($_POST['linkedin_master_system_wide_language'])){
			update_blog_option($blog_id, 'linkedin_master_system_wide_language', sanitize_text_field($_POST['linkedin_master_system_wide_language']));
		}
		else{
			update_blog_option($blog_id, 'linkedin_master_system_wide_language', 'false' );
		}
	}
	else{
		if (isset($_POST['linkedin_master_system_wide'])){
			update_option('linkedin_master_system_wide', sanitize_text_field($_POST['linkedin_master_system_wide']));
		}
		else{
			update_option('linkedin_master_system_wide', 'false' );
		}
		if (isset($_POST['linkedin_master_system_wide_language'])){
			update_option('linkedin_master_system_wide_language', sanitize_text_field($_POST['linkedin_master_system_wide_language']));
		}
		else{
			update_option('linkedin_master_system_wide_language', 'false' );
		}
	}

?>
<div id="message" class="updated fade">
<p><strong><?php _e('Settings Saved!', 'linkedin_master'); ?></strong></p>
</div>
<?php
}
//nothing to post
else{}

//Lets get data from single and multi to populate the form

if(is_multisite()){
	$linkedin_master_system_wide = esc_html(get_blog_option($blog_id, 'linkedin_master_system_wide'));
	$pinterest_master_system_wide_size = esc_html(get_blog_option($blog_id, 'pinterest_master_system_wide_size'));
}
else{
	$linkedin_master_system_wide = esc_html(get_option('linkedin_master_system_wide'));
	$linkedin_master_system_wide_language = esc_html(get_option('linkedin_master_system_wide_language'));
}
?>
<form method="post" width='1'>
<fieldset class="options">
<?php $sec_nonce = wp_nonce_field( 'save-settings_linkedin_master_admin_settings_wide_table_options' ); ?>
<table class="widefat" cellspacing="0">
	<thead>
		<tr>
			<th colspan="3"><h2><img src="<?php echo plugins_url('images/techgasp-minilogo-16.png', dirname(__FILE__)); ?>" style="float:left; height:18px; vertical-align:middle;" /><?php _e('&nbsp;System Wide Settings', 'linkedin_master'); ?></h2></th>
		</tr>
	</thead>

	<tfoot>
		<tr>
			<th colspan="3"></th>
		</tr>
	</tfoot>

	<tbody>
		<tr class="alternate">
			<th class="check-column" scope="row"><input name="linkedin_master_system_wide" id="linkedin_master_system_wide" value="true" type="checkbox" <?php echo $linkedin_master_system_wide == 'true' ? 'checked="checked"':''; ?> /></th>
			<td><label for="linkedin_master_system_wide"><b><?php _e('Activate TechGasp Linkedin System', 'linkedin_master'); ?></b></label></td>
			<td style="vertical-align:middle">Default is <b>On</b>, if off no shortcodes or widgets will be loaded.</td>
		</tr>
		<tr class="alternate">
			<th class="check-column" scope="row"></th>
			<td><input id="linkedin_master_system_wide_language" name="linkedin_master_system_wide_language" type="text" size="22" value="<?php echo $linkedin_master_system_wide_language; ?>"></td>
			<td>
				<label for="linkedin_master_system_wide_language"><?php _e('LinkedIn Language', 'linkedin_master'); ?></label>
				<div class="description">Optional, leave empty for default English language <b>en_US</b>. Override language by inserting your language code, example <b>fr_FR</b> for French, <b>es_ES</b> for Spanish. <a href="https://developer.linkedin.com/docs/reference/language-codes" target="_blank" title="Linkedin Available Language Codes">Linkedin Available Language Codes</a>.</div>
			</td>
		</tr>
	</tbody>
</table>
<p class="submit"><input class='button-primary' type='submit' name='update_system_wide' value='<?php _e("Save Settings", 'pinterest_master'); ?>' id='submitbutton' /></p>
</fieldset>
</form>
<?php
	}
//CLASS ENDS
}
