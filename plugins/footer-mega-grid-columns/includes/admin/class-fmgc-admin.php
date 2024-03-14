<?php
/**
 * Admin Class
 *
 * Handles the Admin side functionality of plugin
 *
 * @package Footer Mega Grid Columns
 * @since 1.1.2
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Fmgc_Admin {

	function __construct() {

		// Action to register admin menu
		add_action( 'admin_menu', array($this, 'fmgc_register_menu'), 12 );

		// Widget init
		add_action( 'widgets_init', array($this, 'fmgc_widgets_init') );

		// Action to register plugin settings
		add_action ( 'admin_init', array($this, 'fmgc_admin_processes') );

		// Add an extra fields in widget
		add_filter('in_widget_form', array($this, 'fmgc_add_grid_option' ), 10, 3 );
	}

	/**
	 * Function to register admin menus
	 * 
	 * @package Footer Mega Grid Columns
	 * @since 1.1.2
	 */
	function fmgc_register_menu() {

		// Register how it work page
		add_menu_page( __('Footer Mega Grid Columns', 'footer-mega-grid-columns'), __('Footer Mega Grid Columns', 'footer-mega-grid-columns'), 'manage_options', 'fmgc-about',  array($this, 'fmgc_settings_page'), 'dashicons-align-left', 6 );

		// Register plugin premium page
		add_submenu_page( 'fmgc-about', __('Upgrade to PRO - Footer Mega Grid Columns', 'footer-mega-grid-columns'), '<span style="color:#ff2700">'.__('Upgrade to PRO', 'footer-mega-grid-columns').'</span>', 'manage_options', 'fmgc-premium', array($this, 'fmgc_premium_page') );
	}

	/**
	 * Function to display plugin design HTML
	 * 
	 * @package Footer Mega Grid Columns
	 * @since 1.1.2
	 */
	function fmgc_settings_page() {
		include_once( FMGC_DIR . '/includes/admin/fmgc-how-it-work.php' );
	}

	/**
	 * Getting Started Page Html
	 * 
	 * @package Footer Mega Grid Columns
	 * @since 1.1.2
	 */
	function fmgc_premium_page() {
		include_once( FMGC_DIR . '/includes/admin/settings/premium.php' );
	}

	/**
	 * Function register setings
	 * 
	 * @since 1.2.5
	 */
	function fmgc_admin_processes() {

		// If plugin notice is dismissed
		if( isset( $_GET['message'] ) && $_GET['message'] == 'fmgc-plugin-notice' ) {
			set_transient( 'fmgc_install_notice', true, 604800 );
		}
	}

	/**
	* Register our main widget areas.
	* 
	* @package Footer Mega Grid Columns
	* @since 1.0.0
	*/
	function fmgc_widgets_init() {

			register_sidebar( array(
				'name'			=> __( 'Footer Mega Grid Columns', 'footer-mega-grid-columns' ),
				'id'			=> 'fmgc-footer-widget',
				'description'	=> __( 'Footer Mega Grid Columns- Register a widget area for your theme and allow you to add and display widgets in grid view.', 'footer-mega-grid-columns' ),
				'before_widget'	=> '<aside id="%1$s" class="widget fmgc-columns '. fmgc_count_widgets( 'fmgc-footer-widget' ) .' %2$s">',
				'after_widget'	=> '</aside>',
				'before_title'	=> '<h6 class="widget-title">',
				'after_title'	=> '</h6>',
			) );
	}

	/**
	* Adding extra field in every widget
	* 
	* @package Footer Mega Grid Columns
	* @since 1.0.0
	*/
	function fmgc_add_grid_option( $widget, $return, $instance ) {
	?>
		<div class="fmgc-widget-opts-wrp">
			<hr>
			<strong><?php _e('Footer Mega Grid Columns Settings', 'footer-mega-grid-columns'); ?>:</strong>
			<hr>
			<div class="pro-notice"><?php echo sprintf( __( 'Upgrade to <a href="%s" target="_blank">Premium Version</a> to unlock more features.', 'footer-mega-grid-columns'), FMGC_PLUGIN_LINK); ?></div>
			<p class="fmgc-pro-feature">
				<label><?php _e('Grid', 'footer-mega-grid-columns'); ?><span class="fmgc-pro-tag"><?php _e('PRO','accordion-and-accordion-slider');?></span><br/>
				<select class='widefat' name='<?php echo $widget->get_field_name('widget_grid'); ?>' disabled="">
					<option><?php _e('Grid 1', 'footer-mega-grid-columns'); ?></option>
				</select>
				</label>
			</p>
			<p class="fmgc-pro-feature">
				<label for='<?php echo $widget->get_field_id('widget-css-class'); ?>'><?php _e('CSS Class', 'footer-mega-grid-columns') ?><span class="fmgc-pro-tag"><?php _e('PRO','accordion-and-accordion-slider');?></span><br/>
				<input type="text" class='widefat' name='<?php echo $widget->get_field_name('widget_css_class'); ?>' disabled="" />
				</label>
			</p>
		</div><!-- end .fmgc-widget-opts-wrp -->
	<?php
	}
}

$fmgc_admin = new Fmgc_Admin();