<?php
/*
 * Plugin Name: Indent Lists Button
 * Version: 0.1.37
 * Plugin URI: http://www.elisewebredactie.nl/indent-lists-button
 * Author: Klaas van der Linden
 * Author URI: http://www.klaasmaakt.nl/
 * Text Domain: indent-lists-button
 * Domain Path: /languages
 * Description: Indent all lists! Once activated, an "Indent List" button appears in the regular WordPress editor.
 */
// Direct access to this file is not permitted
if (! defined ( 'ABSPATH' ))
	exit ();
class ILI_Button{
	private $url;
	private $css;
	private $editor_css;
	public function __construct(){
		$this->url = plugin_dir_url ( __FILE__ );
		$this->css = '.ili-indent{padding-left:40px !important;overflow:hidden}';
		$this->editor_css = 'body#tinymce.wp-editor ' . $this->css;
		add_action ( 'plugins_loaded', array($this,'load_plugin_textdomain') );
		add_action ( 'init', array($this,'init') );
		add_action ( 'wp_head', array($this,'frontend_css'));
		add_action ( 'admin_enqueue_scripts', array($this,'button_css') );
		// Add a javascript object in the header; to be used by mce-plugin.js
		foreach ( array('post.php','post-new.php') as $hook ) {
			add_action( "admin_head-$hook", array($this,'admin_head') );
		}
	}
	// Add the plugin and the new button to the wordpress editor.
	function init() {
		add_filter ( "mce_external_plugins", array($this,"register_mce_plugin") );
		add_filter ( 'mce_buttons_2', array($this,'add_button') );
	}
	// Make sure the right translations are loaded
	function load_plugin_textdomain() {
		load_plugin_textdomain ( 'indent-lists-button', FALSE, basename ( dirname ( __FILE__ ) ) . '/languages/' );
	}
	// Register a new tinymce external plugin at wordpress.
	function register_mce_plugin($plugin_array) {
		$plugin_array ['ili_mce_plugin'] = $this->url . 'js/mce-plugin.js?v=9';
		return $plugin_array;
	}
	// Tell wordpress to add a button to its tinymce editor, the details of which are contained in the tinymce external plugin 'ili_mce_plugin.js' registered earlier
	function add_button($buttons) {
		if (in_array ( 'indent', $buttons )) {
			$new_buttons = array ();
			foreach ( $buttons as $button ) {
				$new_buttons [] = $button;
				if ($button == 'indent') {
					$new_buttons [] = 'the_ili_button';
				}
			}
			$buttons = $new_buttons;
		} else {
			array_push ( $buttons, 'the_ili_button' );
		}
		return $buttons;
	}
	// Add the new list css (see $this->css above in constructor) to frontend lists having class 'ili-indent'.
	function frontend_css() {
		?>
	        <style>
	            <?php echo $this->css; ?>
	        </style>
	    <?php
	}
	// Add the styling for the icon in the wordpress editor.
	function button_css() {
		wp_enqueue_style ( 'ili-button-css', $this->url . 'css/admin/ili-admin.css' );
	}
	// Add a javascript object in the header; to be used by mce-plugin.js
	function admin_head() {
		/* translators: this is the tooltip when hovering the Indent Lists button */
		$title = esc_html__('Indent List', 'indent-lists-button');
		$data = get_plugin_data(__FILE__,false,true);
		?>
		<script type='text/javascript'>
		var iliGlob = {
		    title: '<?php echo $title ?>',
		    version: '<?php echo $data['Version'] ?>',
		    pluginURI: '<?php echo $data['PluginURI'] ?>',
		    name: '<?php echo $data['Name'] ?>',
		    css: '<?php echo $this->editor_css ?>'
		};
		</script>
	    <?php
	}
}
$ili_button = new ILI_Button();