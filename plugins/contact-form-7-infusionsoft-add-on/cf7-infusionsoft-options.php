<?php // Options Page for Contact Form 7 - InfusionSoft Add-on Plugin 

function register_cf7_infusionsoft_addon_settings() {
	register_setting( 'cf7_infusionsoft_api_credentials', 'infusionsoft_app_name' ); 
	register_setting( 'cf7_infusionsoft_api_credentials', 'infusionsoft_api_key' ); 
} 
add_action( 'admin_init', 'register_cf7_infusionsoft_addon_settings' );

function cf7_infusionsoft_addon_register_options_page() {
  add_options_page('CF7 - InfusionSoft Integration Settings', 'CF7 InfusionSoft Add-on', 'manage_options', 'cf7-infusionsoft-integration', 'show_cf7_infusionsoft_addon_options');
}
add_action('admin_menu', 'cf7_infusionsoft_addon_register_options_page');



function show_cf7_infusionsoft_addon_options(){ ?>
	<div class="wrap">
	<h2>Contact Form 7 - InfusionSoft Add-on Settings</h2>
	<form method="post" action="options.php"> 
		<?php settings_fields( 'cf7_infusionsoft_api_credentials' ); ?>
		<table class="form-table">
			<tbody>
				<tr>
					<th scope="row"><label for="infusionsoft_app_name">InfusionSoft App Name:</label></th>
					<td><input type="text" id="infusionsoft_app_name" class="regular-text" name="infusionsoft_app_name" value="<?php echo get_option('infusionsoft_app_name'); ?>" placeholder="Example: wp123">
					<p class="description">The App Name is the portion of your InfusionSoft URL that comes before ".infusionsoft.com".</p></td>
				</tr>
				<tr>
					<th scope="row"><label for="infusionsoft_api_key">InfusionSoft API Key:</label></th>
					<td><input type="text" id="infusionsoft_api_key" class="regular-text" name="infusionsoft_api_key" value="<?php echo get_option('infusionsoft_api_key'); ?>">
					<p class="description">Instructions for finding your API Key can be seen in <a target="_blank" href="http://ug.infusionsoft.com/article/AA-00442/0/How-do-I-enable-the-Infusionsoft-API-and-generate-an-API-Key.html">the InfusionSoft User Guide</a>.</p></td>
				</tr>
			</tbody>
		</table>
		<?php submit_button(); ?>
	</form>
<?php }
