<?php
/*
Plugin Name: CF7 Icons and Labels
Plugin URI:  https://wordpress.org/plugins/cf7-icons-and-labels/
Version:     1.3
Author:      Team Code Aid
Author URI:  https://codeaid.wordpress.com/
Description: This plugin modifies the output of the popular Contact Form 7 plugin to add font awesome icons and labels
License:     GNU General Public License v3
License URI: http://www.gnu.org/licenses/gpl-3.0.html
Tags:        contact form 7, cf7, addon, contact form 7 addon, contact form, cf7 font awesome icons, cf7 labels, cf7 icons and labels, dynamic labels, contact form 7 dynamic labels, labels in input field, dynamic icons, contact form 7 dynamic icons, icon in input field, animate icons contact form 7, contact form 7 animate icons, animate labels contact form 7, contact form 7 animate labels
*/
if ( ! class_exists( 'CF7_CLASS' ) )
{
  class CF7_CLASS {


	function __construct( $file )
	{
		$this->file = $file;

		add_action( 'admin_menu', array( &$this, 'cf7_icons_menu' ) );
		add_action( 'admin_init', array( &$this, 'cf7_icons_and_labels_settings' ) );
		add_action( 'wp_enqueue_scripts', array( &$this, 'cf7_icons_and_labels_scripts' ) );
	} // end constructor

	
	/*--------------------------------------------*
	 * Enqueue scripts
	 *--------------------------------------------*/
	 
	function cf7_icons_and_labels_scripts(){  
	
		$options = get_option( 'cf7_icons_and_labels_settings' );
		if( !isset($options['disable_css']) ) $options['disable_css'] = '0';
		if( !isset($options['recommend_style']) ) $options['recommend_style'] = '0';

		if ($options['disable_css'] == '0') {

			if ($options['recommend_style'] == '0') {
				wp_enqueue_style( 'cf7_style', plugins_url( 'css/cf7_style.css', __FILE__ ) );
			}
		}
		
		if( !isset($options['disable_fontawesome']) ) $options['disable_fontawesome'] = '0';
		if( !isset($options['recommend_style']) ) $options['recommend_style'] = '0';

		if ($options['disable_fontawesome'] == '0') {

			if ($options['recommend_style'] == '0') {
				wp_enqueue_script( 'fontawesome', 'https://use.fontawesome.com/2deb5ba708.js', '', '', true );
			}
		}
	
	  wp_enqueue_script('cf7_script', plugins_url('cf7-icons-and-labels/js/cf7_script.js'), '', '', true);
		
	}


		/*--------------------------------------------*
		 * Admin Menu
		 *--------------------------------------------*/
		 
		function cf7_icons_menu()
		{
			$page_title = __('Cf7 Icons and labels');
			$menu_title = __('Cf7 Icons and labels');
			$capability = 'manage_options';
			$menu_slug = 'CF7-icons-and-labels';
			$function = array( &$this, 'cf7_icn_lbl_options_page');
			add_options_page($page_title, $menu_title, $capability, $menu_slug, $function);
		}

		
		/*--------------------------------------------*
		 * Settings & Settings Page
		 *--------------------------------------------*/

		public function cf7_icons_and_labels_settings() // whitelist options
		{
			register_setting( 'cf7-icons-and-labels', 'cf7_icons_and_labels_settings', array(&$this, 'settings_validate') );

			add_settings_section( 'cf7-icons-and-labels', '', array(&$this, 'section_intro'), 'cf7-icons-and-labels' );
			
			add_settings_field( 'disable_css', __( 'Disable CF7 CSS','cf7'), array(&$this,'setting_disable_css'), 'cf7-icons-and-labels', 'cf7-icons-and-labels');
			
			add_settings_field( 'disable_fontawesome', __( 'Disable Font Awesome CDN','cf7'), array(&$this,'setting_disable_fontawesome'), 'cf7-icons-and-labels', 'cf7-icons-and-labels');
			
			
		}	//cf7_icons_and_labels_settings


// create option page content
		function cf7_icn_lbl_options_page()
		{
			?>
			<div class="wrap">
				<?php screen_icon(); ?>
				<h2>CF7 Icons and Labels Settings </h2>

				<!-- MAIN CONTENT -->
				<div id="post-body">
					<div id="post-body-content">
						<form action="options.php" method="post">
							<?php settings_fields( 'cf7-icons-and-labels' ); ?>
							<?php do_settings_sections( 'cf7-icons-and-labels' ); ?>
							<p class="submit"><input type="submit" class="button-primary" value="<?php _e( 'Save Changes'); ?>" /></p>
						</form>
					</div>
				</div> <!-- //main content -->
				
			</div>
			<?php

		} //cf7_icn_lbl_options_page
		
		
	// option Settings
	function section_intro() {}
		
		function setting_disable_css()
		{
			$options = get_option( 'cf7_icons_and_labels_settings' );
			if( !isset($options['disable_css']) ) $options['disable_css'] = '0';

			echo '<input type="hidden" name="cf7_icons_and_labels_settings[disable_css]" value="0" />
			<label><input type="checkbox" name="cf7_icons_and_labels_settings[disable_css]" value="1"'. (($options['disable_css']) ? ' checked="checked"' : '') .' />' .
			__('I want to use my own CSS styles', 'cf7') . '</label>';
		}
		
		function setting_disable_fontawesome()
		{
			$options = get_option( 'cf7_icons_and_labels_settings' );
			if( !isset($options['disable_fontawesome']) ) $options['disable_fontawesome'] = '0';

			echo '<input type="hidden" name="cf7_icons_and_labels_settings[disable_fontawesome]" value="0" />
			<label><input type="checkbox" name="cf7_icons_and_labels_settings[disable_fontawesome]" value="1"'. (($options['disable_fontawesome']) ? ' checked="checked"' : '') .' />' .
			__('I have already enabeld fontawesome icons <br/>(<span style="color:#0073aa;font-size:12px;">Recommended to disable it to speed up load timing, "ONLY" if you have already enabled Font-Awesome CDN or CSS</span>)', 'cf7') . '</label>';
		}
		
		function settings_validate($input)
		{
			return $input;
		}

  } // End Class

	global $cf7_global_class;

	// Initiation call of plugin
	$cf7_global_class = new CF7_CLASS(__FILE__);
}
		
?>
