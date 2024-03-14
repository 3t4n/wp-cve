<?php
/*
Plugin Name: Wonder PDF Embed
Plugin URI: https://www.wonderplugin.com/wordpress-pdf-embed/
Description: Embed PDF to your WordPress website
Version: 2.7
Author: Magic Hills Pty Ltd
Author URI: https://www.wonderplugin.com
*/

class WonderPlugin_PDF_Plugin {

	function __construct() {

		$this->init();
	}
	
	function init() {

		add_action( 'admin_menu', array($this, 'register_menu') );
		
		add_shortcode( 'wonderplugin_pdf', array($this, 'shortcode_handler') );
		
		add_filter( 'widget_text', 'do_shortcode' );
		
		add_filter( 'plugin_action_links_' . plugin_basename(__FILE__), array($this, 'modify_plugin_action_links') );
	}
	
	function modify_plugin_action_links( $links ) {
		
		$links[] = '<a href="https://www.wonderplugin.com/wordpress-pdf-embed/#tutorial" target="_blank">Online Tutorial</a>';
		
		return $links;
	}
	
	function register_menu() {
	
		$menu = add_menu_page(
				__('Wonder PDF Embed', 'wonderplugin_pdf'),
				__('Wonder PDF Embed', 'wonderplugin_pdf'),
				'manage_options',
				'wonderplugin_pdf_overview',
				array($this, 'edit_settings'),
				'dashicons-media-text');
		
		$menu = add_submenu_page(
				'wonderplugin_pdf_overview',
				__('Settings', 'wonderplugin_pdf'),
				__('Settings', 'wonderplugin_pdf'),
				'manage_options',
				'wonderplugin_pdf_overview',
				array($this, 'edit_settings' ) );
	}
	
	function get_pdf_engine() {
		
		$engine = plugin_dir_url(__FILE__) . 'pdfjslight/web/viewer.html';

		$settings = $this->get_settings();
		
		if ($settings['usedarktheme'] == 1) {
			$engine = plugin_dir_url(__FILE__) . 'pdfjs/web/viewer.html';
		}

		$params = array();
		
		$params[] = 'v=2';

		if ($settings['disabledownload'] == 1)
			$params[] = 'disabledownload=1';
		
		if ($settings['disableprint'] == 1)
			$params[] = 'disableprint=1';
		
		if ($settings['disabletext'] == 1)
			$params[] = 'disabletext=1';
		
		if ($settings['disabledoc'] == 1)
			$params[] = 'disabledoc=1';
		
		if ($settings['disableopenfile'] == 1)
			$params[] = 'disableopenfile=1';

		if ($settings['disabletoolbar'] == 1)
			$params[] = 'disabletoolbar=1';

		if ($settings['disablerightclick'] == 1)
			$params[] = 'disablerightclick=1';

		if (isset($settings['externallinktarget']))
		{
			$externallinktarget = intval($settings['externallinktarget']);
			if ($externallinktarget > 0 && $externallinktarget <= 4)
			{
				$params[] = 'externallinktarget=' . $externallinktarget;
			}
		}

		if (!empty($settings['gaaccount']))
		{
			$params[] = 'gaaccount=' . esc_attr($settings['gaaccount']);
		}

		$urlparams = implode("&", $params);
		
		if (!empty($urlparams))
			$engine .= '?' . $urlparams;
		
		return $engine;
	}
	
	function shortcode_handler($atts, $content = null) {
	
		if ( empty($atts['src']) )
		{
			return __('No URL defined for Wonder PDF Embed', 'wonderplugin_pdf');
		}
		
		foreach($atts as $key => $value)
		{
			if ($key == 'src') 
			{
				$atts[$key] = esc_url(trim($value));
			}
			else
			{
				$atts[$key] = esc_attr(trim($value));
			}
		}

		$engine = $this->get_pdf_engine();

		$src = $engine . ((strpos($engine, '?') == false) ? '?' : '&') . 'file=' . $atts['src'];

		$iframe = '<iframe class="wonderplugin-pdf-iframe" src="' . $src . '"';

		unset($atts['src']);
		
		foreach($atts as $key => $value)
		{
			$allowedtags = apply_filters( 'wonderplugin_pdf_allowed_tags', array('width', 'height', 'style') );
			
			if (in_array(strtolower($key), $allowedtags)) 
			{
				$iframe .= ' ' . $key . '="' . $value . '"';
			}
		}
		
		$iframe .= '></iframe>';
		
		return $iframe;		
	}
	
	function edit_settings() {
	
		?>
		<div class='wrap'>			
		<h2><?php _e( 'Wonder PDF Embed Settings', 'wonderplugin_pdf' ); ?> </h2>
		
		<?php
		if ( isset($_POST['save-wonderplugin-pdf-options']) && check_admin_referer('wonderplugin-pdf', 'wonderplugin-pdf-settings') )
		{
			$this->save_settings($_POST);
			echo '<div class="updated"><p>Settings saved.</p></div>';
		}
		$settings = $this->get_settings();
		?>
	
		<form method="post">
		<?php wp_nonce_field('wonderplugin-pdf', 'wonderplugin-pdf-settings'); ?>
		
		<table class="form-table">
		<tr>
			<th>PDF.js Viewer Toolbar Options</th>
			<td><p><label><input name='disabledownload' type='checkbox' id='disabledownload' <?php echo ($settings['disabledownload'] == 1) ? 'checked' : ''; ?> /> Hide the Download button in the toolbar</label></p>
			<p><label><input name='disableprint' type='checkbox' id='disableprint' <?php echo ($settings['disableprint'] == 1) ? 'checked' : ''; ?> /> Hide the Print button in the toolbar</label></p>
			<p><label><input name='disabletext' type='checkbox' id='disabletext' <?php echo ($settings['disabletext'] == 1) ? 'checked' : ''; ?> /> Hide the Text Selection Tool menu item in the toolbar</label></p>
			<p><label><input name='disabledoc' type='checkbox' id='disabledoc' <?php echo ($settings['disabledoc'] == 1) ? 'checked' : ''; ?> /> Hide the Document Properties menu item in the toolbar</label></p>
			<p><label><input name='disableopenfile' type='checkbox' id='disableopenfile' <?php echo ($settings['disableopenfile'] == 1) ? 'checked' : ''; ?> /> Hide the Open File button in the toolbar</label></p>
			<p><label><input name='disabletoolbar' type='checkbox' id='disabletoolbar' <?php echo ($settings['disabletoolbar'] == 1) ? 'checked' : ''; ?> /> Hide the whole toolbar</label></p>
			<p><label><input name='disablerightclick' type='checkbox' id='disablerightclick' <?php echo ($settings['disablerightclick'] == 1) ? 'checked' : ''; ?> /> Disable right click on the PDF viewer</label></p>
			<p style="font-weight:bold;font-style:italic;margin-top:18px;">Please note: the above options only use CSS and JavaScript code to hide the relative menu items/buttons in the PDF.js viewer toolbar. It's NOT a DRM (Digital Rights Management) scheme to protect the PDF file. It does NOT stop experienced visitors from downloading, printing or copying text from the PDF file.</p>
			</td>
		</tr>
		<tr>
			<th>PDF Link target</th>
			<td>
				<select name="externallinktarget">
				  <option value="0" <?php echo ($settings['externallinktarget'] == 0) ? 'selected="selected"' : ''; ?>>None</option>
				  <option value="1" <?php echo ($settings['externallinktarget'] == 1) ? 'selected="selected"' : ''; ?>>_self</option>
				  <option value="2" <?php echo ($settings['externallinktarget'] == 2) ? 'selected="selected"' : ''; ?>>_blank</option>
				  <option value="3" <?php echo ($settings['externallinktarget'] == 3) ? 'selected="selected"' : ''; ?>>_parent</option>
				  <option value="4" <?php echo ($settings['externallinktarget'] == 4) ? 'selected="selected"' : ''; ?>>_top</option>
				</select>
				<p style="font-weight:bold;font-style:italic;margin-top:18px;">Please note: this option only works with the new light color theme.</p>
			</td>
		</tr>
		<tr>
			<th>Google Analytics ID</th>
			<td><input type="text" name="gaaccount" size=36 value="<?php echo isset($settings['gaaccount']) ? $settings['gaaccount'] : ''; ?>"></td>
		</tr>
		<tr>
			<th>PDF.js Version</th>
			<td><p><label><input name='usedarktheme' type='checkbox' id='usedarktheme' <?php echo ($settings['usedarktheme'] == 1) ? 'checked' : ''; ?> /> Use Mozilla PDF.js old version 2.0.493 (dark theme toolbar)</label></p></td>
		</tr>
		</table>
		
		<p class="submit"><input type="submit" name="save-wonderplugin-pdf-options" id="save-wonderplugin-pdf-options" class="button button-primary" value="Save Changes"  /></p>
		
		</form>

		<table class="form-table">
		<tr>
			<th>Online Tutorial</th>
			<td><a href="https://www.wonderplugin.com/wordpress-pdf-embed/#tutorial" target="_blank">How to embed PDF in WordPress</a></td>
		</tr>
		</table>

		<?php
	}
	
	function get_settings() {
		$settings = array(
			'disabledownload' => get_option( 'wonderplugin_pdf_disabledownload', 0 ),
			'disableprint' => get_option( 'wonderplugin_pdf_disableprint', 0 ),
			'disabletext' => get_option( 'wonderplugin_pdf_disabletext', 0 ),
			'disabledoc' => get_option( 'wonderplugin_pdf_disabledoc', 0 ),
			'disableopenfile' => get_option( 'wonderplugin_pdf_disableopenfile', 0 ),
			'disabletoolbar' => get_option( 'wonderplugin_pdf_disabletoolbar', 0 ),
			'disablerightclick' => get_option( 'wonderplugin_pdf_disablerightclick', 0 ),
			'usedarktheme' => get_option( 'wonderplugin_pdf_usedarktheme', 0 ),
			'externallinktarget' => get_option( 'wonderplugin_pdf_externallinktarget', 0 ),
			'gaaccount' => get_option( 'wonderplugin_pdf_gaaccount', '' )
		);
		
		return $settings;
	}
	
	function save_settings($options) {
	
		update_option( 'wonderplugin_pdf_disabledownload', (!isset($options) || !isset($options['disabledownload'])) ? 0 : 1 );
		update_option( 'wonderplugin_pdf_disableprint', (!isset($options) || !isset($options['disableprint'])) ? 0 : 1 );
		update_option( 'wonderplugin_pdf_disabletext', (!isset($options) || !isset($options['disabletext'])) ? 0 : 1 );
		update_option( 'wonderplugin_pdf_disabledoc', (!isset($options) || !isset($options['disabledoc'])) ? 0 : 1 );
		update_option( 'wonderplugin_pdf_disableopenfile', (!isset($options) || !isset($options['disableopenfile'])) ? 0 : 1 );
		update_option( 'wonderplugin_pdf_disabletoolbar', (!isset($options) || !isset($options['disabletoolbar'])) ? 0 : 1 );
		update_option( 'wonderplugin_pdf_disablerightclick', (!isset($options) || !isset($options['disablerightclick'])) ? 0 : 1 );
		update_option( 'wonderplugin_pdf_usedarktheme', (!isset($options) || !isset($options['usedarktheme'])) ? 0 : 1 );

		$externallinktarget = 0;
		if (isset($options) && isset($options['externallinktarget']) && is_numeric($options['externallinktarget']))
		{
			$externallinktarget = intval($options['externallinktarget']);
			if ($externallinktarget < 0 || $externallinktarget > 4)
			{
				$externallinktarget = 0;
			}
		}
		update_option( 'wonderplugin_pdf_externallinktarget', $externallinktarget );
		update_option( 'wonderplugin_pdf_gaaccount', (!isset($options) || !isset($options['gaaccount'])) ? '' : esc_attr(trim($options['gaaccount'])));
	}
}

$wonderplugin_pdf_plugin = new WonderPlugin_PDF_Plugin();

// PHP API
function wonderplugin_get_pdf_engine()
{	
	global $wonderplugin_pdf_plugin;
	
	return $wonderplugin_pdf_plugin->get_pdf_engine();
}