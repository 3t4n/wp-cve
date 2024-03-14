<?php
/**
 *
 * Plugin Name:       Floating Ads Bottom
 * Plugin URI: 		  http://wp.labnul.com/plugin/floating-ads-bottom/
 * Description:       Increase your adsense click using Floating Ads at the Bottom wordpress plugin. Show floating ad at the bottom of your visitor screen. Start <a href="options-general.php?page=floating-ads-bottom">Floating Ads Bottom's setting</a>.
 * Version:           1.0.3
 * Author:            Aby Rafa
 * Author URI:        http://wp.labnul.com/
 * Text Domain:       floating-ads-bottom
 * Domain Path		  /languages
 * License:           GPL-2.0+
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.txt
 *
 
Floating Ads Bottom is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 2 of the License, or
any later version.

Floating Ads Bottom is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with Floating Ads Bottom. If not, see https://www.gnu.org/licenses/gpl-2.0.html.
*/

function floating_ads_bottom_css() { ?>
<style type='text/css'>
#floating_ads_bottom_textcss_container {position: fixed;bottom: 2px;width: 100%;}
#floating_ads_bottom_textcss_wrap {text-align: center;}
#floating_ads_bottom_textcss_ad {display:inline-block;}
#floating_ads_bottom_textcss_close {position: absolute;top: -20px;display:inline-block;}
</style><?php
}
function floating_ads_bottom_div() { ?>
<div id="floating_ads_bottom_textcss_container">
	<div id="floating_ads_bottom_textcss_wrap">
		<div id="floating_ads_bottom_textcss_ad">
			<?php echo get_option('floating_ads_bottom_script'); ?>
		</div>
		<div id="floating_ads_bottom_textcss_close">	
			<a href="#" onclick="document.getElementById('floating_ads_bottom_textcss_container').style.display='none';return false;" id="floating_ads_bottom_textcss_x"><img border="none" src="<?php echo plugins_url("images/close.png", __FILE__); ?>" alt="x"></a>	
		</div>
	</div>
</div>
<?php
}

add_filter( 'plugin_action_links_' . plugin_basename(__FILE__), 'floating_ads_bottom_status_link' );
function floating_ads_bottom_status_link ( $links ) {
	$mylinks = array('<a href="' . admin_url( 'options-general.php?page=floating-ads-bottom' ) . '">Settings</a>');
	return array_merge( $links, $mylinks );
}

if (is_admin()) add_action('admin_menu', 'floating_ads_bottom_menu');
else {
	if (get_option('floating_ads_bottom_status') == "1") {
		if (get_option('floating_ads_bottom_desktop') == "1") {
			if (!wp_is_mobile()) {
				add_action('wp_head','floating_ads_bottom_css');
				add_action('wp_footer','floating_ads_bottom_div');
			}
		}
		if (get_option('floating_ads_bottom_mobile') == "1") {
			if (wp_is_mobile()) {
				add_action('wp_head','floating_ads_bottom_css');
				add_action('wp_footer','floating_ads_bottom_div');
			}
		}
	}
}

function floating_ads_bottom_menu() {
	add_options_page("Floating Ads Bottom Page","Floating Ads Bottom","manage_options","floating-ads-bottom","floating_ads_bottom_wrap");
	add_action( 'admin_init', 'floating_ads_bottom_reg' );
}

function floating_ads_bottom_reg() {
	register_setting( 'floating-ads-bottom-abyrafa', 'floating_ads_bottom_script' );
	register_setting( 'floating-ads-bottom-abyrafa', 'floating_ads_bottom_status' );
	register_setting( 'floating-ads-bottom-abyrafa', 'floating_ads_bottom_mobile' );
	register_setting( 'floating-ads-bottom-abyrafa', 'floating_ads_bottom_desktop' );
}

function floating_ads_bottom_wrap() { ?>
<div class="wrap">
	<h1>Floating Ads Bottom</h1>
	<form method="post" action="options.php">
	<?php
		settings_fields( 'floating-ads-bottom-abyrafa' );
		do_settings_sections( 'floating-ads-bottom-abyrafa' );
	?>
		<div id="dashboard-widgets-wrap">
			<div id="dashboard-widgets" class="metabox-holder">
				<div id="postbox-container-2" class="postbox-container">
					<div id="side-sortables" class="meta-box-sortables">
						<div id="dashboard_primary" class="postbox ">
							<h2 class="hndle"><span>About Plugin:</span></h2>
							<div class="inside">
								<div class="rss-widget">
									<div style="float:left; margin-right:25px;">
										<p><img src="<?php echo plugins_url("images/home.jpg", __FILE__); ?>" /> <a href="http://wp.labnul.com/plugin/floating-ads-bottom/" target="_blank">Plugin Homepage</a></p>
									</div>
									<div style="clear:left;"></div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div id="postbox-container-1" class="postbox-container">
					<div id="side-sortables" class="meta-box-sortables">
						<div id="dashboard_primary" class="postbox ">
							<h2 class="hndle"><span>Adsense Visibility</span></h2>
							<div class="inside">
								<div class="rss-widget"><br />
									<input name="floating_ads_bottom_desktop" type="checkbox" value="1" <?php checked(1, get_option('floating_ads_bottom_desktop'),true); ?>/> Desktop Browser
									<br /><br />
									<input name="floating_ads_bottom_mobile" type="checkbox" value="1" <?php checked(1, get_option('floating_ads_bottom_mobile'),true); if(false===get_option('floating_ads_bottom_mobile')) echo "checked"; ?> /> Mobile Browser<br /><br />
								</div>
							</div>
							<h2 class="hndle"><span>Adsense script</span></h2>
							<div class="inside">
								<div class="rss-widget"><br />											
									<textarea id="floating_ads_bottom_script" name="floating_ads_bottom_script" class="large-text code" rows="9"><?php echo esc_textarea(get_option('floating_ads_bottom_script')); ?></textarea>
									<input type="radio" name="floating_ads_bottom_status" value="1" <?php checked(1, get_option('floating_ads_bottom_status'),true); ?>>Enable</input>&nbsp;&nbsp;&nbsp;
									<input type="radio" name="floating_ads_bottom_status" value="0" <?php checked(0, get_option('floating_ads_bottom_status'),true); if(false===get_option('floating_ads_bottom_status')) echo "checked"; ?>>Disable</input><br /><br />
									<?php submit_button(); ?>
								</div>
							</div>								
						</div>
					</div>							
				</div>						
			</div>
		</div>            
	</form>
</div>
<?php } ?>