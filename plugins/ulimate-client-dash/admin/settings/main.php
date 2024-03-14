<?php


// Get requried indiviual settings pages

require( plugin_dir_path( __FILE__ ) . 'create-tabs.php');
require( plugin_dir_path( __FILE__ ) . 'dashboard-settings.php');
require( plugin_dir_path( __FILE__ ) . 'login-settings.php');
require( plugin_dir_path( __FILE__ ) . 'client-access.php');
require( plugin_dir_path( __FILE__ ) . 'menu-items.php');
require( plugin_dir_path( __FILE__ ) . 'widget-settings.php');
require( plugin_dir_path( __FILE__ ) . 'welcome-message.php');
require( plugin_dir_path( __FILE__ ) . 'tracking-and-custom-code.php');
require( plugin_dir_path( __FILE__ ) . 'landing-page.php');
require( plugin_dir_path( __FILE__ ) . 'misc.php');
require( plugin_dir_path( __FILE__ ) . 'shortcodes.php');
require( plugin_dir_path( __FILE__ ) . 'upgrade.php');


// Client Dashboard settings page content

function ucd_client_dash_page() {
global $ucd_active_tab;
global $ucd_plugin_version;
global $ucd_pro_plugin_version;
$ucd_active_tab = isset( $_GET['tab'] ) ? $_GET['tab'] : 'dashboard-settings';
	
// Create UCD settings notice

if ( isset( $_GET['settings-updated'] )) {
	add_settings_error( 'ucd-notices', 'ucd-settings-updated', __('Settings saved.', 'ultimate-client-dash'), 'updated' );
}

?>

		<div class="wrap ucd-client-settings-wrapper">
				<div class="ultimate-header">
						<img clas="client-logo" src="<?php echo plugins_url( 'assets/Ultimate-Client-Dash-Logo.png', __FILE__ ); ?>" alt="Ultimate Client Dash" height="50" width="62.5" />
						<h1><?php _e( 'Ultimate Client Dash', 'ultimate-client-dash' ); ?><span class="ucd-brand-dash-developer">Version <?php echo $ucd_plugin_version; ?><?php if (is_plugin_active('ultimate-client-dash-pro/ultimate-client-dash-pro.php') ) { ?> | Pro Version <?php echo $ucd_pro_plugin_version ?><?php } ?></span></h1>
        </div>

        <div class="ucd-page-navigation vertical left clearfix">

						<div class="ucd-tabs-navigation-wrapper">
								<?php
								do_action( 'ucd_settings_tab' );
								?>
		        </div>

        </div>

        <div class="ucd-tab-content">
        <?php do_action( 'ucd_settings_content' ); ?>
        </div>

			      <script type="text/javascript">
			      jQuery(document).ready(function($){
			          $('#upload-btn').click(function(e) {
			              e.preventDefault();
			              var image = wp.media({
			                  title: 'Upload Image',
			                  // mutiple: true if you want to upload multiple files at once
			                  multiple: false
			              }).open()
			              .on('select', function(e){
			                  // This will return the selected image from the Media Uploader, the result is an object
			                  var uploaded_image = image.state().get('selection').first();
			                  // We convert uploaded_image to a JSON object to make accessing it easier
			                  // Output to the console uploaded_image
			                  console.log(uploaded_image);
			                  var image_url = uploaded_image.toJSON().url;
			                  // Let's assign the url value to the input field
			                  $('#image_url').val(image_url);
			              });
			          });
			      });
			      jQuery(document).ready(function($){
			          $('#upload-btn-two').click(function(e) {
			              e.preventDefault();
			              var image = wp.media({
			                  title: 'Upload Image',
			                  // mutiple: true if you want to upload multiple files at once
			                  multiple: false
			              }).open()
			              .on('select', function(e){
			                  // This will return the selected image from the Media Uploader, the result is an object
			                  var uploaded_image = image.state().get('selection').first();
			                  // We convert uploaded_image to a JSON object to make accessing it easier
			                  // Output to the console uploaded_image
			                  console.log(uploaded_image);
			                  var image_url_two = uploaded_image.toJSON().url;
			                  // Let's assign the url value to the input field
			                  $('#image_url_two').val(image_url_two);
			              });
			          });
			      });
			      jQuery(document).ready(function($){
			          $('#upload-btn-three').click(function(e) {
			              e.preventDefault();
			              var image = wp.media({
			                  title: 'Upload Image',
			                  // mutiple: true if you want to upload multiple files at once
			                  multiple: false
			              }).open()
			              .on('select', function(e){
			                  // This will return the selected image from the Media Uploader, the result is an object
			                  var uploaded_image = image.state().get('selection').first();
			                  // We convert uploaded_image to a JSON object to make accessing it easier
			                  // Output to the console uploaded_image
			                  console.log(uploaded_image);
			                  var image_url_three = uploaded_image.toJSON().url;
			                  // Let's assign the url value to the input field
			                  $('#image_url_three').val(image_url_three);
			              });
			          });
			      });
			      jQuery(document).ready(function($){
			          $('#upload-btn-four').click(function(e) {
			              e.preventDefault();
			              var image = wp.media({
			                  title: 'Upload Image',
			                  // mutiple: true if you want to upload multiple files at once
			                  multiple: false
			              }).open()
			              .on('select', function(e){
			                  // This will return the selected image from the Media Uploader, the result is an object
			                  var uploaded_image = image.state().get('selection').first();
			                  // We convert uploaded_image to a JSON object to make accessing it easier
			                  // Output to the console uploaded_image
			                  console.log(uploaded_image);
			                  var image_url_four = uploaded_image.toJSON().url;
			                  // Let's assign the url value to the input field
			                  $('#image_url_four').val(image_url_four);
			              });
			          });
			      });
						jQuery(document).ready(function($){
			          $('#upload-btn-admin-logo').click(function(e) {
			              e.preventDefault();
			              var image = wp.media({
			                  title: 'Upload Image',
			                  // mutiple: true if you want to upload multiple files at once
			                  multiple: false
			              }).open()
			              .on('select', function(e){
			                  // This will return the selected image from the Media Uploader, the result is an object
			                  var uploaded_image = image.state().get('selection').first();
			                  // We convert uploaded_image to a JSON object to make accessing it easier
			                  // Output to the console uploaded_image
			                  console.log(uploaded_image);
			                  var admin_logo_image_url = uploaded_image.toJSON().url;
			                  // Let's assign the url value to the input field
			                  $('#admin_logo_image_url').val(admin_logo_image_url);
			              });
			          });
			      });

			      // Add color picker to input

			      (function( $ ) {
			          // Add Color Picker to all inputs that have 'color-field' class
			          $(function() {
			              $('.color-field').wpColorPicker();
			          });

			      })( jQuery );

			      </script>
    </div>

		<!-- Footer -->
    <div class="ultimiate-client-footer">Proudly developed by <a href="https://wpcodeus.com/" target="_blank">WP Codeus</a>.</div>
<?php }
