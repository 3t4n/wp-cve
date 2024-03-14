<?php
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/*
   Panel
*/
//Register Settings
function elpug_register_settings(){
	$option_group = 'elpug_powerups';
    register_setting( $option_group, 'elpug_portfolio_switch', $args = array( 'default'      => 0, ) );
	register_setting( $option_group, 'elpug_slider_switch', $args = array( 'default'      => 1, ) );
	register_setting( $option_group, 'elpug_blogroll_switch', $args = array( 'default'      => 1, ) );
	register_setting( $option_group, 'elpug_team_switch', $args = array( 'default'      => 1, ) );
	register_setting( $option_group, 'elpug_testimonials_switch', $args = array( 'default'      => 1, ) );
	register_setting( $option_group, 'elpug_countdown_switch', $args = array( 'default'      => 1, ) );
	register_setting( $option_group, 'elpug_magic_buttons_switch', $args = array( 'default'      => 1, ) );
	
}
elpug_register_settings();

add_action('admin_menu', 'elpug_setup_menu');
 
function elpug_setup_menu(){

	//Enqueue color picker
	wp_enqueue_style( 'wp-color-picker' );
	//wp_enqueue_script( 'elemenfolio-js', get_template_directory_uri().'/myscript.js', array( 'wp-color-picker','jquery' ), false, true );
	wp_enqueue_script( 'elemenfolio-js', plugin_dir_url( __FILE__ ) .  'js/elemenfolio-admin.js', array( 'wp-color-picker' ), '20151218', true );
	wp_enqueue_style( 'elpug-admin-css', plugin_dir_url( __FILE__ ) . 'css/elpug_admin.css' );

	//Create Admin Page
 	$page_title = 'Power-Ups For Elementor';
    $menu_title = 'PWR Plugins for Elementor';
    $capability = 'edit_posts';
    $menu_slug = 'powerups_for_elementor';
    $function = 'elpug_powerups_options_page';
    $icon_url = 'dashicons-layout';
    $position = 99;

    add_menu_page( $page_title, $menu_title, $capability, $menu_slug, $function, $icon_url, $position );

    //Create Settings
    $option_group = 'elpug_powerups';

	// Color Section
	$settings_section = 'elpug_main';
	$page = 'elpug_powerups';
	add_settings_field( 'elpug_portfolio_addon', __('Portfolio Module', 'elpug_powerups'), 'elpug_portfolio_module_callback', $page, 'elpug_main' );
	add_settings_field( 'elpug_gallery_addon', __('Image Gallery Grid Module', 'elpug_powerups'), 'elpug_gallery_module_callback', $page, 'elpug_main' );
	add_settings_field( 'elpug_post_grid_addon', __('Posts and Products Grid Module', 'elpug_powerups'), 'elpug_post_grid_module_callback', $page, 'elpug_main' );
	add_settings_section( $settings_section, __( 'Enable/Disable Modules', 'elpug_powerups' ), 'elpug_settings_modules_callback', $page );
	add_settings_field( 'elpug_slider_switch', __('Slider Module', 'elpug_powerups'), 'elpug_slider_switch_callback', $page, 'elpug_main' );
	add_settings_field( 'elpug_blogroll_switch', __('Post Carousel Module', 'elpug_powerups'), 'elpug_blogroll_switch_callback', $page, 'elpug_main' );
	add_settings_field( 'elpug_team_switch', __('Team Module', 'elpug_powerups'), 'elpug_team_switch_callback', $page, 'elpug_main' );
	add_settings_field( 'elpug_testimonials_switch', __('Testimonials Module', 'elpug_powerups'), 'elpug_testimonials_switch_callback', $page, 'elpug_main' );
	add_settings_field( 'elpug_countdown_switch', __('Countdown Module', 'elpug_powerups'), 'elpug_countdown_switch_callback', $page, 'elpug_main' );
	add_settings_field( 'elpug_magic_buttons_switch', __('Magic Buttons Module', 'elpug_powerups'), 'elpug_magic_buttons_switch_callback', $page, 'elpug_main' );
	add_settings_field( 'elpug_portfolio_switch', __('(Legacy) Old Portfolio Module', 'elpug_powerups'), 'elpug_portfolio_legacy_switch_callback', $page, 'elpug_main' );
	//Shortcode Section
	//add_settings_section( 'elpug_howto', __( 'How to display the portfolio grid', 'elpug_powerups' ), 'elpug_shortcode_callback', $page );
}

// ================================ Fields Callback ===============================
//Section Callback 
function elpug_settings_modules_callback(){

	echo esc_html('In this section you can disable modules that you are not using or that you do not need to improve the performance of your website.', 'elpug');

}	

//Portfolio Addon
function elpug_portfolio_module_callback(){
	?>	
	<?php if ( ! class_exists('ELPT_portfolio_Post_Types') ) { ?>
	<div class="addon-warning">		
		<?php _e('<a href="https://wordpress.org/plugins/portfolio-elementor/" target="_blank" class="addon-btn">Download Portfolio Module</a>', 'elpug'); ?>
		<p><?php _e('Please install the "Powerfolio" addon to enable the portfolio module.', 'elpug'); ?></p>	
	</div>
	<?php } else {
		_e('<strong><span class="dashicons dashicons-yes"></span> The portfolio module is currently active (Powerfolio Addon).</strong>', 'elpug');
	} ?>
	
	<?php
}	

//Image Gallery Addon
function elpug_gallery_module_callback(){
	?>	
	<?php if ( ! class_exists('ELPT_portfolio_Post_Types') ) { ?>
	<div class="addon-warning">		
		<?php _e('<a href="https://wordpress.org/plugins/portfolio-elementor/" target="_blank" class="addon-btn">Download Image Gallery Module</a>', 'elpug'); ?>
		<p><?php _e('Please install the "Powerfolio" addon to enable the filterable image gallery module.', 'elpug'); ?></p>	
	</div>
	<?php } else {
		_e('<strong><span class="dashicons dashicons-yes"></span> The image gallery module is currently active (Powerfolio Addon).</strong>', 'elpug');
	} ?>
	<?php
}	

//Post Grid Addon
function elpug_post_grid_module_callback(){
	?>	
	
	<?php if ( ! function_exists('pwgd_get_plugin_directory_url') ) { ?>
	<div class="addon-warning">		
		<?php _e('<a href="https://wordpress.org/plugins/post-grid-for-elementor/" target="_blank" class="addon-btn">Download Post Grid & Product Grid Module</a>', 'elpug'); ?>
		<p><?php _e('Please install the "PowerGrids" addon to enable the Posts and Products Grid module.', 'elpug'); ?></p>	
	</div>
	<?php } else {
		_e('<strong><span class="dashicons dashicons-yes"></span> The Post Grid module is currently active (PowerGrids Addon).</strong>', 'elpug');
	} ?>
	
	<?php
}	

//Portfolio (legacy)
function elpug_portfolio_legacy_switch_callback(){
	?>	
	<?php if ( ! class_exists('ELPT_portfolio_Post_Types') ) { ?>
		<hr/> 

		<div class="infos">
			<p>
			<?php _e('<strong>Warning:</strong> This is the legacy porfolio module, which is no longer maintained / supported. This functionality has been moved to the new addon. Please install the "Powerfolio" free add-on instead. ', 'elpug'); ?>
			<?php _e('<a href="https://wordpress.org/plugins/portfolio-elementor/" target="_blank">Click here to download the portfolio addon.</a> ', 'elpug'); ?>
			</p>
			<p>  
				<?php _e('Only enable it if you already used the portfolio module, and want to keep the functionality while making the transition.', 'elpug'); ?>
			</p>
		</div>

		<label class="elpug-admin-switch">
			<input type="checkbox" name="elpug_portfolio_switch" value="1"  <?php checked(1, get_option('elpug_portfolio_switch'), true); ?> >
			<span class="elpug-admin-slider round"></span>
		</label>

	<?php } else { 
		_e('<span class="dashicons dashicons-yes"></span> You are currently using the "Powerfolio" addon.<br/>Disable it if you need to enable the portfolio legacy mode.', 'elpug'); 
	} ?>
	
	<?php
}	



//Slider
function elpug_slider_switch_callback(){
	
	?>

	<label class="elpug-admin-switch">
		<input type="checkbox" name="elpug_slider_switch" value="1"  <?php checked(1, get_option('elpug_slider_switch'), true); ?> >
		<span class="elpug-admin-slider round"></span>
	</label>
	
	<?php
}	

//Blogroll
function elpug_blogroll_switch_callback(){
	
	?>

	<label class="elpug-admin-switch">
		<input type="checkbox" name="elpug_blogroll_switch" value="1"  <?php checked(1, get_option('elpug_blogroll_switch'), true); ?> >
		<span class="elpug-admin-slider round"></span>
	</label>
	
	<?php
}

//Team
function elpug_team_switch_callback(){
	
	?>

	<label class="elpug-admin-switch">
		<input type="checkbox" name="elpug_team_switch" value="1"  <?php checked(1, get_option('elpug_team_switch'), true); ?> >
		<span class="elpug-admin-slider round"></span>
	</label>
	
	<?php
}

//Testimonials
function elpug_testimonials_switch_callback(){
	
	?>

	<label class="elpug-admin-switch">
		<input type="checkbox" name="elpug_testimonials_switch" value="1"  <?php checked(1, get_option('elpug_testimonials_switch'), true); ?> >
		<span class="elpug-admin-slider round"></span>
	</label>
	
	<?php
}

//Countdown
function elpug_countdown_switch_callback(){
	
	?>

	<label class="elpug-admin-switch">
		<input type="checkbox" name="elpug_countdown_switch" value="1"  <?php checked(1, get_option('elpug_countdown_switch'), true); ?> >
		<span class="elpug-admin-slider round"></span>
	</label>
	
	<?php
}

//Magic Buttons
function elpug_magic_buttons_switch_callback(){
	
	?>

	<label class="elpug-admin-switch">
		<input type="checkbox" name="elpug_magic_buttons_switch" value="1"  <?php checked(1, get_option('elpug_magic_buttons_switch'), true); ?> >
		<span class="elpug-admin-slider round"></span>
	</label>
	
	<?php
}


//==================================== Page ====================================
function elpug_powerups_options_page() {
?>
	<div class="wrap">
		<h1><?php esc_html_e( 'Power-Ups for Elementor - Settings', 'elpug' ) ?></h1>
		<form action="options.php" method="post">
			<hr/><br/>		
			<?php do_settings_sections( 'elpug_powerups' ); ?>
			<?php settings_fields( 'elpug_powerups' ); ?>
			<input name="Submit" type="submit" value="<?php esc_attr_e( 'Save Changes', 'elpug' ); ?>" class="button button-primary" />
			<br/><br/><br/><hr/><br/>
			<h2><?php esc_html_e( 'How to use the Power-Ups for Elementor Widgets', 'elpug' ); ?></h2>
			<div ><p><strong><?php esc_html_e( 'The widgets will be available in Elementor editor page, on the "Power-Ups for Elementor" category. Just drag it to your page and select the customization options :)', 'elpug' ); ?></strong></p></div>			
		</form>
	</div>
	<div>
		
	</div>
<?php
}

//Admin Noticer
function general_admin_notice(){
    //global $pagenow;
    //if ( $pagenow == 'options-general.php' ) {
         echo '<div class="notice notice-warning is-dismissible"><p>'
		  .__('<strong>Warning - Power-ups for Elementor</strong><br/> The portfolio functionality has been moved to the new addon. Please install the "Powerfolio" free add-on to enable it again.<br/> ', 'elpug')
		  .__('<a href="https://wordpress.org/plugins/portfolio-elementor/" target="_blank">Click here to download the portfolio addon.</a></br> ', 'elpug')
		  .__('Note that you will need to add the portfolio widget to your page again.<br/> If you want to postpone it, you can also enable the portfolio legacy module on the "Power-Ups for Elementor" settings page.<br/>', 'elpug')
         .'</p></div>';
    //}
}

add_action( 'plugins_loaded', 'my_plugin_override' );
 
function my_plugin_override() {
    if ( ! class_exists('ELPT_portfolio_Post_Types') ) { 
		add_action('admin_notices', 'general_admin_notice');
	}
}
