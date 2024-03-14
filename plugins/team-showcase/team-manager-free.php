<?php
/*
	Plugin Name: Team Showcase
	Plugin URI: https://themepoints.com/teamshowcase/
	Description: Team Showcase is a WordPress plugin that allows you to easily create and manage teams. You can display single teams as multiple responsive columns, you can also showcase all teams in various styles.
	Version: 2.2
	Author: Themepoints
	Author URI: https://themepoints.com
	License: GPLv2
	Text Domain: team-manager-free
	Domain Path: /languages
*/

	if ( ! defined( 'ABSPATH' ) ) exit;
	// Exit if accessed directly

	define('TEAM_MANAGER_FREE_PLUGIN_PATH', WP_PLUGIN_URL . '/' . plugin_basename( dirname(__FILE__) ) . '/' );
	define('team_manager_free_plugin_dir', plugin_dir_path( __FILE__ ) );
	add_filter('widget_text', 'do_shortcode');

	# load plugin textdomain 
	function team_manager_free_load_textdomain(){
		load_plugin_textdomain('team-manager-free', false, dirname(plugin_basename( __FILE__ )) . '/languages/');
	}
	add_action('plugins_loaded', 'team_manager_free_load_textdomain');

	# load plugin style & scripts
	function team_manager_free_initial_script(){
		wp_enqueue_style('team_manager-normalize-css', TEAM_MANAGER_FREE_PLUGIN_PATH.'assets/css/normalize.css');
		wp_enqueue_style('team_manager-awesome-css', TEAM_MANAGER_FREE_PLUGIN_PATH.'assets/css/font-awesome.css');
		wp_enqueue_style('team_manager-featherlight-css', TEAM_MANAGER_FREE_PLUGIN_PATH.'assets/css/featherlight.css');	
		wp_enqueue_style('team_manager-style1-css', TEAM_MANAGER_FREE_PLUGIN_PATH.'assets/css/style1.css');
		wp_enqueue_script( 'jquery' );
		wp_enqueue_script('team_manager-modernizer', plugins_url( '/assets/js/modernizr.custom.js', __FILE__ ), array('jquery'), '1.0', false);
		wp_enqueue_script('team_manager-classie', plugins_url( '/assets/js/classie.js', __FILE__ ), array('jquery'), '1.0', false);  
		wp_enqueue_script('team_manager-featherlight', plugins_url( '/assets/js/featherlight.js', __FILE__ ), array('jquery'), '1.0', false);  
		wp_enqueue_script('team_manager-main', plugins_url( '/assets/js/main.js', __FILE__ ), array('jquery'), '1.0', false);
	}
	add_action('wp_enqueue_scripts', 'team_manager_free_initial_script');

	# load plugin admin style & scripts
	function team_manager_free_admins_backend(){
		wp_enqueue_style('team_manager-backend-css', TEAM_MANAGER_FREE_PLUGIN_PATH.'admin/css/team-manager-backend.css');
	}
	add_action('admin_enqueue_scripts', 'team_manager_free_admins_backend');

	# load plugin admin style & scripts
	function team_manager_free_admins_scripts(){
		global $typenow;
		if(($typenow == 'team_mf')){
			wp_enqueue_style('team-manager-free-admin2-style', TEAM_MANAGER_FREE_PLUGIN_PATH.'admin/css/team-manager-free-admin.css');
		}
	}
	add_action('admin_enqueue_scripts', 'team_manager_free_admins_scripts');

	# load plugin admin style & scripts
	function team_manager_free_admin_scripts(){
		global $typenow;
		if(($typenow == 'team_mf_team')){
			wp_enqueue_style('team-manager-free-admin-style', TEAM_MANAGER_FREE_PLUGIN_PATH.'admin/css/team-manager-free-admin.css');
			wp_enqueue_script('jquery');
			wp_enqueue_script('team-manager-free-admin-scripts', TEAM_MANAGER_FREE_PLUGIN_PATH.'admin/js/team-manager-free-admin.js', array('jquery'), '1.0.4', true );
			wp_enqueue_script('teamjscolor-scripts', TEAM_MANAGER_FREE_PLUGIN_PATH.'admin/js/jscolor.js', array('jquery'), '1.3.3', true );
			wp_enqueue_style('wp-color-picker');
			wp_enqueue_script('team-manager-color-picker', plugins_url('/admin/js/color-picker.js', __FILE__ ), array( 'wp-color-picker' ), false, true );
			wp_enqueue_script('jquery-ui-tabs');
			wp_enqueue_script('jquery-ui-sortable');
			wp_enqueue_script('media-upload');
			wp_enqueue_script('thickbox');
			wp_enqueue_style('thickbox');
		}
	}
	add_action('admin_enqueue_scripts', 'team_manager_free_admin_scripts');
	
	function team_manager_free_buy_action_links( $links ) {
		$links[] = '<a target="_blank" href="https://themepoints.com/product/team-showcase-pro/" style="color: red; font-weight: bold;" target="_blank">Buy Pro!</a>';
		return $links;
	}
	add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), 'team_manager_free_buy_action_links' );
	
	// Team Post Type File
	require_once( plugin_dir_path(__FILE__) . 'admin/team-manager-free-post-type.php');
	// Team Post Type Metabox File
	require_once( plugin_dir_path(__FILE__) . 'admin/team-manager-free-meta-boxes.php');
	// Team Shortcode File
	require_once( plugin_dir_path( __FILE__ ) . 'includes/shortcodes/team-shortcode.php' );

	# plugin activation/deactivation
	function active_team_manager_free(){
		require_once plugin_dir_path( __FILE__ ) . 'includes/team-manager-free-activator.php';
		Team_Manager_Free_Activator::activate();
	}

	function deactive_team_manager_free(){
		require_once plugin_dir_path(__FILE__) . 'includes/team-manager-free-deactivator.php';
		Team_Manager_Free_Deactivator::deactivate();
	}
	register_activation_hook(__FILE__, 'active_team_manager_free');
	register_deactivation_hook(__FILE__, 'deactive_team_manager_free');


	# Redirect Page
	function team_free_redirect_options_page( $plugin ) {
		if ( $plugin == plugin_basename( __FILE__ ) ) {
			exit( wp_redirect( admin_url( 'options-general.php' ) ) );
		}
	}
	add_action( 'activated_plugin', 'team_free_redirect_options_page' );	

	# admin menu
	function team_free_plugins_options_framwrork() {
		add_options_page( 'Team Showcase Pro Version Help & Features', 'Team Showcase Info', 'manage_options', 'team-pro-features', 'team_free_plugins_options_framwrork_inc' );
	}
	add_action( 'admin_menu', 'team_free_plugins_options_framwrork' );

	if ( is_admin() ) : // Load only if we are viewing an admin page

	function team_free_plugins_options_framwrork_settings() {
		// Register settings and call sanitation functions
		register_setting( 'teams_free_options', 'team_free_options', 'tms_free_options' );
	}
	add_action( 'admin_init', 'team_free_plugins_options_framwrork_settings' );



function team_free_plugins_options_framwrork_inc() {

	if ( ! isset( $_REQUEST['updated'] ) ) {
		$_REQUEST['updated'] = false;
	} ?>
		<div class="wrap about-wrap">
			<div class="team-versions">
				<h1><?php echo esc_html__('Welcome to Team Showcase V2.2', 'team-showcase');?></h1>
			</div>
			<div class="teamcontainerfree">
				<p class="teamcontainerfreetext"><?php echo wp_kses_post( 'You have been using <b> Team Showcase </b> for a while. Would you please show us a little love by rating us in the <a href="https://wordpress.org/support/plugin/team-showcase/reviews/#new-post" target="_blank"><strong>WordPress.org</strong></a>?', 'logoshowcase' ); ?></p>
				<p>
					<div class="reviewteam">
						<a target="_blank" href="https://wordpress.org/plugins/team-showcase/">
							<span class="dashicons dashicons-star-filled"></span>
							<span class="dashicons dashicons-star-filled"></span>
							<span class="dashicons dashicons-star-filled"></span>
							<span class="dashicons dashicons-star-filled"></span>
							<span class="dashicons dashicons-star-filled"></span>
						</a>
					</div>
				</p>

				<div class="team-free-setup-area">
					<div class="single-team-setup"><h3><a href="https://themepoints.com/teamshowcase/free-version-documentation/" target="_blank"><?php echo esc_html__('Team Installation Guide', 'team-showcase');?></a></h3> </div>
					<div class="single-team-setup"><h3><a href="https://themepoints.com/teamshowcase/team-sortable/" target="_blank"><?php echo esc_html__('Team Member Order', 'team-showcase');?></a> </h3></div>
				</div>

				<div class="why-choose-proversion">
					<h3><?php echo esc_html__('Why Choose Pro?', 'team-showcase');?></h3>
				</div>
				<p class="choose_details">We create a <a target="_blank" href="https://themepoints.com/product/team-showcase-pro/">Premium Version</a> of this plugin with some amazing cool features.</p>

				<div class="features-team-container">
					<div class="features-team-services">
						<div class="single-features">
							<h4><?php echo esc_html__('Fully Responsive', 'team-showcase');?></h4>
							<p><?php echo esc_html__('Team Showcase is fully responsive in mobile & Desktop devices. It can adapt any screen sizes for achieving best viewing case.', 'team-showcase');?></p>
						</div>
						<div class="single-features">
							<h4><?php echo esc_html__('Unlimited Team Support', 'team-showcase');?></h4>
							<p><?php echo esc_html__('Team Showcase allows you to create unlimited number of team member as you need. We have no limits for create team members.', 'team-showcase');?></p>
						</div>
						<div class="single-features">
							<h4><?php echo esc_html__('All Browsers Compatible', 'team-showcase');?></h4>
							<p><?php echo esc_html__('Team Showcase work\'s properly in all most popular stable versions of the browsers: IE, Firefox, Safari, Opera, Chrome etc.', 'team-showcase');?></p>
						</div>
						<div class="single-features">
							<h4><?php echo esc_html__('Powerful Setting panel', 'team-showcase');?></h4>
							<p><?php echo esc_html__('Team Showcase Plugin comes with a powerfull admin panel which allows you to deeply customize to fit your website. no need to required any coding skill.', 'team-showcase');?></p>
						</div>
						<div class="single-features">
							<h4><?php echo esc_html__('25 Unique Team Styles', 'team-showcase');?></h4>
							<p><?php echo esc_html__('Team Showcase Plugin comes with 25 different unique layouts with multiple columns support (1/6).', 'team-showcase');?></p>
						</div>
						<div class="single-features">
							<h4><?php echo esc_html__('Lifetime Free Updates', 'team-showcase');?></h4>
							<p><?php echo esc_html__('All future updates and developments to the Team showcase will be completely free once you have bought the project one time.', 'team-showcase');?></p>
						</div>
						<div class="single-features">
							<h4><?php echo esc_html__('Custom Color', 'team-showcase');?></h4>
							<p><?php echo esc_html__('Team Showcase Plugin comes with a custom color which allows you to customize background color hover color, title color or overlay color etc.', 'team-showcase');?></p>
						</div>
						<div class="single-features">
							<h4><?php echo esc_html__('24/7 Dedicated Support', 'team-showcase');?></h4>
							<p><?php echo esc_html__('We are available for support in case you have any questions, problems or need any help implementing the Team Showcase. We will do our best to respond as soon as possible.', 'team-showcase');?></p>
						</div>
						<div class="single-features">
							<h4><?php echo esc_html__('Quick & Easy Setup', 'team-showcase');?></h4>
							<p><?php echo esc_html__('Team Showcase is Fully documented and video tutorials step-by-step guide provided to help you.', 'team-showcase');?></p>
						</div>
					</div>
				</div>

				<div class="feature-section two-col">
					<div class="col">
						<ul>
							<li><span class="dashicons dashicons-yes"></span> <?php echo esc_html__('All Features of the free version.', 'team-showcase');?></li>
							<li><span class="dashicons dashicons-yes"></span> <?php echo esc_html__('Fully responsive.', 'team-showcase');?></li>
							<li><span class="dashicons dashicons-yes"></span> <?php echo esc_html__('25 different Themes.', 'team-showcase');?></li>
							<li><span class="dashicons dashicons-yes"></span> <?php echo esc_html__('Highly customized for User Experience.', 'team-showcase');?></li>
							<li><span class="dashicons dashicons-yes"></span> <?php echo esc_html__('Widget Ready.', 'team-showcase');?></li>
							<li><span class="dashicons dashicons-yes"></span> <?php echo esc_html__('Unlimited Domain.', 'team-showcase');?></li>
							<li><span class="dashicons dashicons-yes"></span> <?php echo esc_html__('Unlimited Team Support.', 'team-showcase');?></li>
							<li><span class="dashicons dashicons-yes"></span> <?php echo esc_html__('Unlimited ShortCode Generator.', 'team-showcase');?></li>
							<li><span class="dashicons dashicons-yes"></span> <?php echo esc_html__('Create Team by group.', 'team-showcase');?></li>
							<li><span class="dashicons dashicons-yes"></span> <?php echo esc_html__('Cross-browser compatibility.', 'team-showcase');?></li>
							<li><span class="dashicons dashicons-yes"></span> <?php echo esc_html__('Drag & Drop team items sorting.', 'team-showcase');?></li>
							<li><span class="dashicons dashicons-yes"></span> <?php echo esc_html__('Unlimited color options.', 'team-showcase');?></li>
							<li><span class="dashicons dashicons-yes"></span> <?php echo esc_html__('Use via short-codes.', 'team-showcase');?></li>
							<li><span class="dashicons dashicons-yes"></span> <?php echo esc_html__('All fields control show/hide.', 'team-showcase');?></li>
							<li><span class="dashicons dashicons-yes"></span> <?php echo esc_html__('2 Different Popup Style & Different Positions.', 'team-showcase');?></li>
							<li><span class="dashicons dashicons-yes"></span> <?php echo esc_html__('Popup Detail Page with control.', 'team-showcase');?></li>				
							<li><span class="dashicons dashicons-yes"></span> <?php echo esc_html__('All text size, color, text align control.', 'team-showcase');?></li>
						</ul>
					</div>
					<div class="col">
						<ul>
							<li><span class="dashicons dashicons-yes"></span> <?php echo esc_html__('Grid with Margin or No Margin.', 'team-showcase');?></li>
							<li><span class="dashicons dashicons-yes"></span> <?php echo esc_html__('Multi Color team option.', 'team-showcase');?></li>					
							<li><span class="dashicons dashicons-yes"></span> <?php echo esc_html__('Unlimited Team Columns.', 'team-showcase');?></li>	
							<li><span class="dashicons dashicons-yes"></span> <?php echo esc_html__('Team member Display from categories.', 'team-showcase');?></li>
							<li><span class="dashicons dashicons-yes"></span> <?php echo esc_html__('Team member images size option.', 'team-showcase');?></li>
							<li><span class="dashicons dashicons-yes"></span> <?php echo esc_html__('Show/hide social icon options.', 'team-showcase');?></li>
							<li><span class="dashicons dashicons-yes"></span> <?php echo esc_html__('Social icon font size options.', 'team-showcase');?></li>
							<li><span class="dashicons dashicons-yes"></span> <?php echo esc_html__('Social icon background color options.', 'team-showcase');?></li>
							<li><span class="dashicons dashicons-yes"></span> <?php echo esc_html__('Social icon color options.', 'team-showcase');?></li>
							<li><span class="dashicons dashicons-yes"></span> <?php echo esc_html__('Social icon hover color options.', 'team-showcase');?></li>
							<li><span class="dashicons dashicons-yes"></span> <?php echo esc_html__('Open Social Link (self/new) tab.', 'team-showcase');?></li>
							<li><span class="dashicons dashicons-yes"></span> <?php echo esc_html__('Team Info Sortable Options.', 'team-showcase');?></li>
							<li><span class="dashicons dashicons-yes"></span> <?php echo esc_html__('Unlimited accordion anywhere in the themes or template.', 'team-showcase');?></li>
							<li><span class="dashicons dashicons-yes"></span> <?php echo esc_html__('Life Time Self hosted auto updated enable.', 'team-showcase');?></li>
							<li><span class="dashicons dashicons-yes"></span> <?php echo esc_html__('Online Documentation.', 'team-showcase');?></li>
							<li><span class="dashicons dashicons-yes"></span> <?php echo esc_html__('24/7 Dedicated support forum.', 'team-showcase');?></li>
							<li><span class="dashicons dashicons-yes"></span> <?php echo esc_html__('And Many More', 'team-showcase');?></li>
						</ul>
					</div>
				</div>
				<div class="purchasepro-vers">
					<a target="_blank" href="https://themepoints.com/product/team-showcase-pro"><?php echo esc_html__('Purchase Now', 'team-showcase');?></a>
				</div>
				<br>
			</div>
		</div>
	<?php
}
endif;  // EndIf is_admin()

register_activation_hook( __FILE__, 'team_pro_free_plugin_active_hook' );
add_action( 'admin_init', 'team_pro_deac_plugin_active_hook' );

function team_pro_free_plugin_active_hook() {
	add_option( 'team_pro_plugin_active_hook', true );
}

function team_pro_deac_plugin_active_hook() {
	if ( get_option( 'team_pro_plugin_active_hook', false ) ) {
		delete_option( 'team_pro_plugin_active_hook' );
		if ( ! isset( $_GET['activate-multi'] ) ) {
			wp_redirect( "options-general.php?page=team-pro-features" );
		}
	}
}

?>