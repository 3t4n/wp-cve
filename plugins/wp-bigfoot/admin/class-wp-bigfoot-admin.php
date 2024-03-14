<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       zemartino.com
 * @since      1.0.0
 *
 * @package    Wp_Bigfoot
 * @subpackage Wp_Bigfoot/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Wp_Bigfoot
 * @subpackage Wp_Bigfoot/admin
 * @author     Adam Martinez <am@zemartino.com>
 */
class Wp_Bigfoot_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

		add_action( 'admin_menu', array( $this, 'add_admin_pages' ), 9);
		add_action( 'admin_init', array( $this, 'wpbf_settings_init' ));
		

	}

	public function enqueue_styles() {

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/wp-bigfoot-admin.css', array(), $this->version, 'all' );

	}


	public function enqueue_scripts() {
		wp_enqueue_style( 'wp-color-picker' );
		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/wp-bigfoot-admin.js', array( 'jquery', 'wp-color-picker' ), $this->version, false );

	}



	// Register our settings. Add the settings section, and settings fields
	public function wpbf_settings_init(){
		register_setting('wpbf-options', 'wpbf-options', 'validate_options' );
		add_settings_section('main_section', 'Main Settings', 'section_text_fn', __FILE__);
		add_settings_field('radio_buttons', 'Select Style', 'setting_radio_fn', __FILE__, 'main_section');

		add_settings_section('color_section', 'Color Settings', 'color_selection_text', __FILE__);
		$options = get_option('wpbf-options');
		if ( $options['wpbf-style'] == "Number") {
		add_settings_field('cpa_footnote_fg', 'Select Foreground Color','fg_settings_field', __FILE__, 'color_section' );
		}
		add_settings_field('cpa_footnote_bg', 'Select Background Color','bg_settings_field', __FILE__, 'color_section' );
	// ************************************************************************************************************

	// Callback functions

	// Section HTML, displayed before the first option
	function section_text_fn() {
		echo '<p>Update the Bigfoot.js style, and color options</p>';
	}

	// RADIO-BUTTON - Name: plugin_options[wpbf-style]
	function setting_radio_fn() {
		$options = get_option('wpbf-options');
		$items = array("Default", "Number", "Bottom");
		foreach($items as $item) {
			$checked = ($options['wpbf-style']==$item) ? ' checked="checked" ' : '';
			echo "<label><input ".$checked." value='$item' name='wpbf-options[wpbf-style]' type='radio' /> $item</label><br />";
		}
	}
 
		// Section HTML, displayed before the first option
		function color_selection_text() {
			echo '<p>Choose a color for the footnote background and foreground (if Number option is selected above)</p>';
		}
	function bg_settings_field() { 
		$options = get_option('wpbf-options');
		$val = ( isset( $options['wpbf-bgcolor'] ) ) ? $options['wpbf-bgcolor'] : '';
		echo '<input type="text" name="wpbf-options[wpbf-bgcolor]" value="' . $val . '" class="cpa-color-picker" >';
		
	}

	function fg_settings_field() {
		$options = get_option('wpbf-options');
		$val = ( isset( $options['wpbf-fgcolor'] ) ) ? $options['wpbf-fgcolor'] : '';
		echo '<input type="text" name="wpbf-options[wpbf-fgcolor]" value="' . $val . '" class="cpa-color-picker" >';
	}

	function validate_options( $fields ) { 
     
		$valid_fields = array();
		 
		// Validate Foreground Field
		$title = trim( $fields['wpbf-fgcolor'] );
		$valid_fields['wpbf-fgcolor'] = strip_tags( stripslashes( $title ) );
		 
		// Validate Background Color
		$background = trim( $fields['wpbf-bgcolor'] );
		$background = strip_tags( stripslashes( $background ) );
		 
		// Check if is a valid hex color
		if( FALSE === $this->check_color( $background ) ) {
		 
			// Set the error message
			add_settings_error( 'wpbf-options', 'cpa_bg_error', 'Insert a valid color for Background', 'error' ); // $setting, $code, $message, $type
			 
			// Get the previous valid value
			$valid_fields['wpbf-bgcolor'] = $this->options['wpbf-bgcolor'];
		 
		} else {
		 
			$valid_fields['wpbf-bgcolor'] = $background;  
		 
		}
		 
		return apply_filters( 'validate_options', $valid_fields, $fields);
	}
	 
	/**
	 * Function that will check if value is a valid HEX color.
	 */
	function check_color( $value ) { 
		 
		if ( preg_match( '/^#[a-f0-9]{6}$/i', $value ) ) { // if user insert a HEX color with #     
			return true;
		}
		 
		return false;

	}


	}

	public function add_admin_pages(){
		add_submenu_page("edit.php", __('Footnotes', 'wpbigfoot'), __('Footnotes', 'wpbigfoot'), 'edit_posts', __FILE__, array($this, 'options_page_fn'));
	}



	// Display the admin options page
	public function options_page_fn() {
	?>
		<div class="wrap">
			<div class="icon32" id="icon-options-general"><br></div>
			<h2>WP-Bigfoot Footnotes Settings</h2>
			<form action="options.php" method="post">
			<?php settings_fields('wpbf-options'); ?>
			<?php do_settings_sections(__FILE__); ?>
			<p class="submit">
				<input name="Submit" type="submit" class="button-primary" value="<?php esc_attr_e('Save Changes'); ?>" />
			</p>
			</form>
		</div>
	<?php
	}
}