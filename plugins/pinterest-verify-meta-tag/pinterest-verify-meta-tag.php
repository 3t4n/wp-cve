<?php
/*
Plugin Name: Pinterest Verify Meta Tag
Version: 1.3
Description: Add Pinterest meta tag verification code to the HEAD section of your site.
Author: Marvie Pons
Author URI: http://tutskid.com/
Donate URI: http://tutskid.com/
Plugin URI: http://tutskid.com/pinterest-verify-meta-tag/
  
Copyright 2012  Marvie Pons (email: support@tutskid.com)

Released under GPL License.
*/

define('PVMT_VERSION', '1.3');

// REQUIRE MINIMUM VERSION OF WORDPRESS:
function pvmt_requires_wordpress_version() {
	global $wp_version;
	$plugin = plugin_basename( __FILE__ );
	$plugin_data = get_plugin_data( __FILE__, false );

	if ( version_compare($wp_version, "3.0", "<" ) ) {
		if( is_plugin_active($plugin) ) {
			deactivate_plugins( $plugin );
			wp_die( "'".$plugin_data['Name']."' requires WordPress 3.0 or higher, and has been deactivated! Please upgrade WordPress and try again.<br /><br />Back to <a href='".admin_url()."'>WordPress admin</a>." );
		}
	}
}
add_action( 'admin_init', 'pvmt_requires_wordpress_version' );

// Set-up Action and Filter Hooks
register_activation_hook(__FILE__, 'pvmt_add_defaults');
register_uninstall_hook(__FILE__, 'pvmt_delete_plugin_options');
add_action('admin_init', 'pvmt_init' );
add_action('admin_menu', 'pvmt_add_options_page');
add_filter( 'plugin_action_links', 'pvmt_plugin_action_links', 10, 2 );

// Delete options table entries ONLY when plugin deactivated AND deleted
function pvmt_delete_plugin_options() {
	delete_option('pvmt_options');
}

// Define default option settings
function pvmt_add_defaults() {
	$tmp = get_option('pvmt_options');
    if(($tmp['chk_default_options_db']=='1')||(!is_array($tmp))) {
		delete_option('pvmt_options'); // so we don't have to reset all the 'off' checkboxes too! (don't think this is needed but leave for now)
		$arr = array(	"chk_default_options_db" => "",
						"txt_one" => ""
		);
		update_option('pvmt_options', $arr);
	}
}

// Init plugin options to white list our options
function pvmt_init(){
	register_setting( 'pvmt_plugin_options', 'pvmt_options','pvmt_validate_options' );
}

// Add menu page
function pvmt_add_options_page() {
	add_options_page('Pinterest Verify Meta Tag', 'Pinterest Verify Meta', 'manage_options', 'pvmt', 'pvmt_render_form');
}

// Render the Plugin options form
function pvmt_render_form() {
$options = get_option('pvmt_options');
$meta = $options['txt_one'];
	?>
	<div class="wrap">
		
		<!-- Display Plugin Icon, Header, and Description -->
		<div class="icon32" id="icon-options-general"><br></div>
		<h2>Pinterest Verify Meta Tag</h2>
	

	<div id="poststuff">
		<div id="post-body" class="metabox-holder columns-2">
			<div id="post-body-content">
			<div class="postbox">
				<div class="inside">

		<!-- Beginning of the Plugin Options Form -->
		<form method="post" action="options.php">
			<?php settings_fields('pvmt_plugin_options'); ?>
			<?php $options = get_option('pvmt_options'); ?>

			<!-- Table Structure Containing Form Controls -->
			<!-- Each Plugin Option Defined on a New Table Row -->
			<table class="form-table">

				<!-- Textbox Control -->
				<tr>
					<th scope="row">Meta Tag</th>
					<td>
						<input type="text" size="57" name="pvmt_options[txt_one]" value="<?php echo $options['txt_one']; ?>" /><span style="color:#666666;margin-left:2px;"><br />Copy the <b>content</b> part only of the meta tag from Pinterest Verification page and enter it here.</span><br />
						<?php if( $options['txt_one']!= '' ) { echo
						'<b>This meta tag has been added to the &lt;head&gt; section of your site.</b>: </ br><div style="background: silver;border: 1px solid gray;">&lt;meta name="p:domain_verify" content="'.$meta.'" /&gt;</div>';
						} ?>					
					</td>
				</tr>

				<tr><td colspan="2"><div style="margin-top:10px;"></div></td></tr>
				<tr valign="top" style="border-top:#dddddd 1px solid;">
					<th scope="row">Database Options</th>
					<td>
						<label><input name="pvmt_options[chk_default_options_db]" type="checkbox" value="1" <?php if (isset($options['chk_default_options_db'])) { checked('1', $options['chk_default_options_db']); } ?> /> Restore defaults upon plugin deactivation/reactivation</label>
						<br /><span style="color:#666666;margin-left:2px;">Only check this if you want to reset plugin settings upon Plugin reactivation</span>
					</td>
				</tr>
			</table>
			<input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
				
		</form>
				
				</div><!-- .inside -->
			</div><!-- .postbox -->
			</div> <!-- #post-body-content -->
		
<div id="postbox-container-1" class="postbox-container">
				<div id="side-sortables" class="meta-box-sortables">
				
					<div id="about" class="postbox">
					<div class="handlediv" title="Click to toggle"><br /></div>
						<h3 class="hndle"><span>About the Plugin:</span></h3>
						<div class="inside">
								<p>You are using <a href="http://tutskid.com/pinterest-verify-meta-tag/" target="_blank" style="color:#72a1c6;"><strong>Pinterest Verify Meta Tag</strong></a> v<?php echo PVMT_VERSION; ?></p>
						</div><!-- .inside -->
					</div><!-- #about.postbox -->
					
					<div id="about" class="postbox">
					<div class="handlediv" title="Click to toggle"><br /></div>
						<h3 class="hndle"><span>Enjoy the plugin?</span></h3>
						<div class="inside">		
							Why not consider <a href="http://wordpress.org/extend/plugins/pinterest-verify-meta-tag/" target="_blank">giving it a good rating on WordPress.org</a> or <a href="http://twitter.com/?status=Pinterest Verify Meta Plugin for WordPress - check it out! http://wp.me/p2uqdU-mK" target="_blank">Tweet about it</a>. Thanks.</p>
							<div id="fb-root"></div><script src="http://connect.facebook.net/en_US/all.js#xfbml=1"></script><fb:like href="http://www.facebook.com/Tutskidcom" send="false" layout="standard" width="280" show_faces="false" font="trebuchet ms" action="like"></fb:like>
							<span><a href="http://www.facebook.com/Tutskidcom" title="Our Facebook page" target="_blank"><img style="border:1px #ccc solid;" src="<?php echo plugins_url(); ?>/pinterest-verify-meta-tag/images/facebook-icon.png" /></a></span>
							&nbsp;&nbsp;<span><a href="http://twitter.com/tutskid" title="Follow on Twitter" target="_blank"><img style="border:1px #ccc solid;" src="<?php echo plugins_url(); ?>/pinterest-verify-meta-tag/images/twitter-icon.png" /></a></span>
							&nbsp;&nbsp;<span><a href="http://tutskid.com/" title="TutsKid.com" target="_blank"><img style="border:1px #ccc solid;" src="<?php echo plugins_url(); ?>/pinterest-verify-meta-tag/images/pc-icon.png" /></a></span>
						</div><!-- .inside -->
					</div><!-- #about.postbox -->

					<div id="about" class="postbox">
					<div class="handlediv" title="Click to toggle"><br /></div>
						<h3 class="hndle"><span>Donate:</span></h3>
						<div class="inside">
							<p>If you have found this plugin at all useful, please consider buying me a cup of coffee. Thank you!<br />
							<form action="https://www.paypal.com/cgi-bin/webscr" method="post">
							<input type="hidden" name="cmd" value="_s-xclick">
							<input type="hidden" name="hosted_button_id" value="FTFEPE3YBRKQY">
							<input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_donate_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
							<img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1">
							</form>
							</p>
						</div><!-- .inside -->
					</div><!-- #about.postbox -->

				</div><!-- #side-sortables.meta-box-sortables -->
		</div><!-- .postbox-container -->
		</div> <!-- #post-body -->
	</div> <!-- #poststuff -->

			
</div>
	<?php	
}

// Sanitize and validate input
function pvmt_validate_options($input) {
	$input['txt_one'] =  wp_filter_nohtml_kses($input['txt_one']);
	return $input;
}

// Display a Settings link on the main Plugins page
function pvmt_plugin_action_links( $links, $file ) {

	if ( $file == plugin_basename( __FILE__ ) ) {
		$pvmt_links = '<a href="'.get_admin_url().'options-general.php?page=pvmt">'.__('Settings').'</a>';
		// make the 'Settings' link appear first
		array_unshift( $links, $pvmt_links );
	}

	return $links;
}

// ------------------------------------------------------------------------------
// PVMT PLUGIN FUNCTION:
// ------------------------------------------------------------------------------

$options = get_option('pvmt_options');
if( $options['txt_one']!= '' )
add_action('wp_head', 'pvmt_pinterest_meta');

function pvmt_pinterest_meta() {
$options = get_option('pvmt_options');
$meta = $options['txt_one'];
?>
<!-- Pinterest Meta Tag added by Pinterest Verify Meta Tag Plugin v<?php echo PVMT_VERSION; ?>: http://tutskid.com/pinterest-verify-meta-tag/ -->
<meta name="p:domain_verify" content="<?php echo $meta; ?>" />
<?php
}
