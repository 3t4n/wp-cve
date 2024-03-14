<?php
if(!class_exists('WP_List_Table')){
	require_once( get_home_path() . 'wp-admin/includes/class-wp-list-table.php' );
}
class linkedin_master_admin_addons_table extends WP_List_Table {
	/**
	 * Display the rows of records in the table
	 * @return string, echo the markup of the rows
	 */
	function display() {
	$plugin_master_name = constant('LINKEDIN_MASTER_NAME');
	$path = WP_CONTENT_DIR . '/plugins/linkedin-master-addons/';
	if ( is_plugin_active( 'linkedin-master-addons/linkedin-master-addons.php' ) && file_exists($path) ) {
		$linkedin_master_addon = "yes";
		$linkedin_master_addon_get = '<b>All add-ons Installed</b>';
	}
	else{
		$linkedin_master_addon = "no";
		$linkedin_master_addon_get = '<a class="button-primary" href="https://wordpress.techgasp.com/linkedin-master/" target="_blank" title="Get all Add-ons" style="float:left;">Get all Add-ons pack for peanuts</a>';
	}
?>
<table class="widefat" cellspacing="0">
	<thead>
		<tr>
			<th colspan="3"><?php echo $linkedin_master_addon_get; ?></th>
		</tr>
		<tr>
			<th><h2><img src="<?php echo plugins_url('images/techgasp-minilogo-16.png', dirname(__FILE__)); ?>" style="float:left; height:18px; vertical-align:middle;" /><?php _e('&nbsp;Screenshot', 'linkedin_master'); ?></h2></th>
			<th><h2><img src="<?php echo plugins_url('images/techgasp-minilogo-16.png', dirname(__FILE__)); ?>" style="float:left; height:18px; vertical-align:middle;" /><?php _e('&nbsp;Description', 'linkedin_master'); ?></h2></th>
			<th><h2><img src="<?php echo plugins_url('images/techgasp-minilogo-16.png', dirname(__FILE__)); ?>" style="float:left; height:18px; vertical-align:middle;" /><?php _e('&nbsp;Installed', 'linkedin_master'); ?></h2></th>
		</tr>
	</thead>

	<tfoot>
		<tr>
			<th><?php echo $linkedin_master_addon_get; ?></th>
			<th></th>
			<th></th>
		</tr>
	</tfoot>

	<tbody>
		<tr class="alternate">
			<td><img src="<?php echo plugins_url('images/techgasp-admin-addons-widgets.png', dirname(__FILE__)); ?>" alt="<?php echo $plugin_master_name; ?>" align="left" width="300px" height="139px" style="padding:5px;"/></td>
			<td style="vertical-align:middle"><h3>Buttons Widget</h3><p>The perfect widget if you only want to display the LinkedIn Connect Button or the Lyrics Button. A great way to connect people to your LinkedIn profile or to display your cool lyrics page.</p><p>This widget works great when published under any of the below players. You can activate both buttons or a single one, navigate to your wordpress widgets page and start using it. <a href="https://wordpress.techgasp.com/linkedin-master/" target="_blank"><strong>Demo Link</strong></a>.</p></td>
			<td style="vertical-align:middle"><img src="<?php echo plugins_url('images/techgasp-check-yes.png', dirname(__FILE__)); ?>" alt="<?php echo $plugin_master_name; ?>" align="left" width="200px" height="121px" style="padding:5px;"/></td>
		</tr>
		<tr>
			<td style="vertical-align:middle"><img src="<?php echo plugins_url('images/techgasp-admin-addons-widgets.png', dirname(__FILE__)); ?>" alt="<?php echo $plugin_master_name; ?>" align="left" width="300px" height="139px" style="padding:5px;"/></td>
			<td style="vertical-align:middle"><h3>Basic Member Profile Widget</h3><p>LinkedIn Master Member Profile, allows you to display your linkedin personal profile. Designed for fast loading times and easy deployment by any wordpress administrator.</p><p>Navigate to your wordpress widgets page and start using it. <a href="https://wordpress.techgasp.com/linkedin-master/" target="_blank"><strong>Demo Link</strong></a>.</p></td>
			<td style="vertical-align:middle"><img src="<?php echo plugins_url('images/techgasp-check-yes.png', dirname(__FILE__)); ?>" alt="<?php echo $plugin_master_name; ?>" align="left" width="200px" height="121px" style="padding:5px;"/></td>
		</tr>
		<tr class="alternate">
			<td style="vertical-align:middle"><img src="<?php echo plugins_url('images/techgasp-admin-addons-widgets.png', dirname(__FILE__)); ?>" alt="<?php echo $plugin_master_name; ?>" align="left" width="300px" height="139px" style="padding:5px;"/></td>
			<td style="vertical-align:middle"><h3>Advanced Member Profile Widget</h3><p>The "top of the line" Advanced LinkedIn Member Profile Widget, allows you to display your linkedin personal profile with all LinkedIn Options. <a href="https://wordpress.techgasp.com/linkedin-master/" target="_blank"><strong>Demo Link</strong></a>.</p></td>
			<td style="vertical-align:middle"><img src="<?php echo plugins_url('images/techgasp-check-'.$linkedin_master_addon.'.png', dirname(__FILE__)); ?>" alt="<?php echo $plugin_master_name; ?>" align="left" width="200px" height="121px" style="padding:5px;"/></td>
		</tr>
		<tr>
			<td style="vertical-align:middle"><img src="<?php echo plugins_url('images/techgasp-admin-addons-widgets.png', dirname(__FILE__)); ?>" alt="<?php echo $plugin_master_name; ?>" align="left" width="300px" height="139px" style="padding:5px;"/></td>
			<td style="vertical-align:middle"><h3>Company Insider Widget</h3><p>LinkedIn Master Company Insider Widget, allows to enhance your content and show rich personalized insights about companies featured on your site. <a href="https://wordpress.techgasp.com/linkedin-master/" target="_blank"><strong>Demo Link</strong></a>.</p></td>
			<td style="vertical-align:middle"><img src="<?php echo plugins_url('images/techgasp-check-'.$linkedin_master_addon.'.png', dirname(__FILE__)); ?>" alt="<?php echo $plugin_master_name; ?>" align="left" width="200px" height="121px" style="padding:5px;"/></td>
		</tr>
		<tr class="alternate">
			<td style="vertical-align:middle"><img src="<?php echo plugins_url('images/techgasp-admin-addons-widgets.png', dirname(__FILE__)); ?>" alt="<?php echo $plugin_master_name; ?>" align="left" width="300px" height="139px" style="padding:5px;"/></td>
			<td style="vertical-align:middle"><h3>Available Jobs Widget</h3><p>LinkedIn Master Available Jobs Widget allows for candidates to apply for your jobs using their LinkedIn profiles. <a href="https://wordpress.techgasp.com/linkedin-master/" target="_blank"><strong>Demo Link</strong></a>.</p></td>
			<td style="vertical-align:middle"><img src="<?php echo plugins_url('images/techgasp-check-'.$linkedin_master_addon.'.png', dirname(__FILE__)); ?>" alt="<?php echo $plugin_master_name; ?>" align="left" width="200px" height="121px" style="padding:5px;"/></td>
		</tr>
<!--		<tr>
			<td style="vertical-align:middle"><img src="<?php echo plugins_url('images/techgasp-admin-addons-widgets.png', dirname(__FILE__)); ?>" alt="<?php echo $plugin_master_name; ?>" align="left" width="300px" height="139px" style="padding:5px;"/></td>
			<td style="vertical-align:middle"><h3>Alumni Tool Widget</h3><p>LinkedIn Master Alumni, page provides high-level insights about alumni of your school, as well as access to the more detailed professional profiles they have shared.</p></td>
			<td style="vertical-align:middle"><img src="<?php echo plugins_url('images/techgasp-check-'.$linkedin_master_addon.'.png', dirname(__FILE__)); ?>" alt="<?php echo $plugin_master_name; ?>" align="left" width="200px" height="121px" style="padding:5px;"/></td>
		</tr> -->
		<tr>
			<td style="vertical-align:middle"><img src="<?php echo plugins_url('images/techgasp-admin-addons-shortcodes.png', dirname(__FILE__)); ?>" alt="<?php echo $plugin_master_name; ?>" align="left" width="300px" height="139px" style="padding:5px;"/></td>
			<td style="vertical-align:middle"><h3>Individual Shortcode</h3><p>LinkedIn Master uses TechGasp Wordpress Framework. The <b>Individual Shortcode</b> allows you to have a different customized LinkedInshortcode in each page or post. Easy to use it can be found in the plugin <b>Individual Shortcodes menu</b> or when you edit a page or a post under the wordpress text editor. Once you have created your shortcode, Just insert the shortcode <b>[linkedin-master]</b> anywhere inside that text. <a href="https://wordpress.techgasp.com/linkedin-master/" target="_blank"><strong>Demo Link</strong></a>.</p></td>
			<td style="vertical-align:middle"><img src="<?php echo plugins_url('images/techgasp-check-'.$linkedin_master_addon.'.png', dirname(__FILE__)); ?>" alt="<?php echo $plugin_master_name; ?>" align="left" width="200px" height="121px" style="padding:5px;"/></td>
		</tr>
		<tr class="alternate">
			<td style="vertical-align:middle"><img src="<?php echo plugins_url('images/techgasp-admin-addons-shortcodes.png', dirname(__FILE__)); ?>" alt="<?php echo $plugin_master_name; ?>" align="left" width="300px" height="139px" style="padding:5px;"/></td>
			<td style="vertical-align:middle"><h3>Universal Shortcode</h3><p>LinkedIn Master uses TechGasp Wordpress Framework. The <b>Universal Shortcode</b> allows you to have the same LinkedIn shortcode across different pages or posts. Easy to use it can be found right here under the <b>Universal Shortcodes menu</b>. Once you have created your shortcode, Just insert the shortcode <b>[linkedin-master-un]</b> anywhere inside the text of your pages or posts. <a href="https://wordpress.techgasp.com/linkedin-master/" target="_blank"><strong>Demo Link</strong></a>.</p></td>
			<td style="vertical-align:middle"><img src="<?php echo plugins_url('images/techgasp-check-'.$linkedin_master_addon.'.png', dirname(__FILE__)); ?>" alt="<?php echo $plugin_master_name; ?>" align="left" width="200px" height="121px" style="padding:5px;"/></td>
		</tr>
		<tr>
			<td style="vertical-align:middle"><img src="<?php echo plugins_url('images/techgasp-admin-addons-updater.png', dirname(__FILE__)); ?>" alt="<?php echo $plugin_master_name; ?>" align="left" width="300px" height="139px" style="padding:5px;"/></td>
			<td style="vertical-align:middle"><h3>Advanced Updater</h3><p>The Advanced Updater allows you to easily Update / Upgrade your Advanced Plugin. You can instantly update your plugin after we release a new version with more goodies without having to wait for the nightly native wordpress updater that runs every 24/48 hours. Get it fresh, get it fast.</p></td>
			<td style="vertical-align:middle"><img src="<?php echo plugins_url('images/techgasp-check-'.$linkedin_master_addon.'.png', dirname(__FILE__)); ?>" alt="<?php echo $plugin_master_name; ?>" align="left" width="200px" height="121px" style="padding:5px;"/></td>
		</tr>
		<tr class="alternate">
			<td style="vertical-align:middle"><img src="<?php echo plugins_url('images/techgasp-admin-addons-support.png', dirname(__FILE__)); ?>" alt="<?php echo $plugin_master_name; ?>" align="left" width="300px" height="139px" style="padding:5px;"/></td>
			<td style="vertical-align:middle"><h3>Award Winning Professional Support</h3><p>Need professional help deploying this plugin? TechGasp provides award winning professional wordpress support for all advanced version costumers and wordpress professionals. Support Us and we will Support You.</p></td>
			<td style="vertical-align:middle"><img src="<?php echo plugins_url('images/techgasp-check-'.$linkedin_master_addon.'.png', dirname(__FILE__)); ?>" alt="<?php echo $plugin_master_name; ?>" align="left" width="200px" height="121px" style="padding:5px;"/></td>
		</tr>
	</tbody>
</table>
<?php
		}
}
