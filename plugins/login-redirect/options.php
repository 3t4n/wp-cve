<?php
function login_redirect_register_settings() {
	add_option('login_redirect_type', 'current');
	add_option('login_redirect_customise_url', home_url());
	register_setting('login_redirect_options', 'login_redirect_type');
	register_setting('login_redirect_options', 'login_redirect_customise_url');
}
add_action('admin_init', 'login_redirect_register_settings');

function login_redirect_register_options_page() {
	add_options_page(__('Login Redirect Options Page', LOGIN_REDIRECT_TEXT_DOMAIN), __('Login Redirect', LOGIN_REDIRECT_TEXT_DOMAIN), 'manage_options', LOGIN_REDIRECT_TEXT_DOMAIN.'-options', 'login_redirect_options_page');
}
add_action('admin_menu', 'login_redirect_register_options_page');

function login_redirect_get_select_option($select_option_name, $select_option_value, $select_option_id){
	?>
	<select name="<?php echo $select_option_name; ?>" id="<?php echo $select_option_name; ?>"<?php if($select_option_name == "login_redirect_type"){ ?> onchange="customise_url(this);"<?php } ?>>
		<?php
		for($num = 0; $num < count($select_option_id); $num++){
			$select_option_value_each = $select_option_value[$num];
			$select_option_id_each = $select_option_id[$num];
			?>
			<option value="<?php echo $select_option_id_each; ?>"<?php if (get_option($select_option_name) == $select_option_id_each){?> selected="selected"<?php } ?>>
				<?php echo $select_option_value_each; ?>
			</option>
		<?php } ?>
	</select>
	<?php
}

function login_redirect_options_page() {
?>
<script>
function customise_url(select){
	var selected_option = select.options[select.selectedIndex].value;
	if(selected_option == "customise"){
		jQuery("#login_redirect_customise_div").slideDown();
	}else{
		jQuery("#login_redirect_customise_div").slideUp();
	}
}
</script>
<div class="wrap">
	<h2><?php _e("Login Redirect Options Page", LOGIN_REDIRECT_TEXT_DOMAIN); ?></h2>
	<form method="post" action="options.php">
		<?php settings_fields('login_redirect_options'); ?>
		<h3><?php _e("General Options", LOGIN_REDIRECT_TEXT_DOMAIN); ?></h3>
			<table class="form-table">
				<tr valign="top">
					<th scope="row"><label for="login_redirect_type"><?php _e("Where you want to go after login?", LOGIN_REDIRECT_TEXT_DOMAIN); ?></label></th>
					<td>
						<?php login_redirect_get_select_option("login_redirect_type", array(__('Admin Panel', LOGIN_REDIRECT_TEXT_DOMAIN), __('Customise URL', LOGIN_REDIRECT_TEXT_DOMAIN)), array('admin', 'customise')); ?>
						<div id="login_redirect_customise_div"<?php if(get_option("login_redirect_type") != "customise"){ ?> style="display: none;"<?php } ?>>
							<input type="url" name="login_redirect_customise_url" id="login_redirect_customise_url" value="<?php echo get_option('login_redirect_customise_url'); ?>" size="40" />
						</div>
					</td>
				</tr>
			</table>
		<?php submit_button(); ?>
	</form>
</div>
<?php
}
?>