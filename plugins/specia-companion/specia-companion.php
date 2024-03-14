<?php
/*
Plugin Name: Specia Companion
Description: Specia Companion is created for Specia Theme. The plugin set frontpage sections, It allow edit customizer settings for the theme. Extend your theme functionalities with one click import & enjoy free stock images. Try to install Specia Companion, 26+ Theme Supported with this Plugin.
Version: 3.4
Author: specia
Author URI: https://speciatheme.com
Text Domain: specia-companion
Requires PHP: 5.6
Requires: 4.6 or higher
*/

if ( 'specia' !== get_template() ) {
	return;
}

define('SPECIA_COMPANION_VER', '3.3');
define( 'SPECIA_COMPANION_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'SPECIA_COMPANION_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
	

	if ( ! class_exists( 'Specia_Companion_Setup' ) ) {

	/**
	 * Customizer Loader
	 *
	 * @since 1.0.0
	 */
	class Specia_Companion_Setup {

		/**
		 * Instance
		 *
		 * @access private
		 * @var object
		 */
		private static $instance;

		/**
		 * Initiator
		 */
		public static function get_instance() {
			if ( ! isset( self::$instance ) ) {
				self::$instance = new self;
			}
			return self::$instance;
		}

		/**
		 * Constructor
		 */
		 public function __construct() {
			add_action( 'admin_menu', array($this, 'specia_companion_setup_menu') );
			add_action( 'wp_ajax_specia-companion-activate-theme', array( $this, 'activate_theme' ),2 );
			//add_action( 'wp_ajax_specia-companion-activate-theme', array( $this, 'activate_theme' ),1 );
			add_action( 'admin_enqueue_scripts', array( $this, 'specia_companion_enqueue_scripts' ) );
		}
		
		
		public function specia_companion_enqueue_scripts() {
			wp_enqueue_style('specia-companion-admin',SPECIA_COMPANION_PLUGIN_URL .'assets/css/admin.css');
			wp_enqueue_script( 'jquery-ui-core' );
					wp_enqueue_script( 'jquery-ui-dialog' );
					wp_enqueue_style( 'wp-jquery-ui-dialog' );	
					
					wp_enqueue_script( 'specia-companion-install-theme', SPECIA_COMPANION_PLUGIN_URL . 'assets/js/install-theme.js', array( 'jquery', 'updates' ), SPECIA_COMPANION_VER, true );
					
					$data = apply_filters(
						'specia_companion_install_theme_localize_vars',
						array(
							'installed'  => __( 'Installed! Activating..', 'specia-companion' ),
							'activating' => __( 'Activating..', 'specia-companion' ),
							'activated'  => __( 'Activated! Reloading..', 'specia-companion' ),
							'installing' => __( 'Installing..', 'specia-companion' ),
							'ajaxurl'    => esc_url( admin_url( 'admin-ajax.php' ) ),
							'security' => wp_create_nonce( 'my-special-string' )
						)
					);
					wp_localize_script( 'specia-companion-install-theme', 'SpeciaCompanionInstallThemeVars', $data );
		}

		public function specia_companion_setup_menu() {
			add_menu_page( 'Specia Companion Page', 'Specia Companion', 'manage_options', 'specia-companion', array($this, 'specia_companion_page_init')  );
		}


		 function specia_companion_page_init(){
		echo "<h2>Specia Companion Compatible Themes</h2>";
		$api_url = 'https://api.wordpress.org/themes/info/1.1/?action=query_themes&request[author]=specia&request[per_page]=22';

		// Read JSON file
		$json_data = file_get_contents($api_url);

		// Decode JSON data into PHP array
		$response_data = json_decode($json_data);

	
		// All user data exists in 'data' object
		$theme_data = $response_data->themes;

		// Traverse array and display user data
		
		?>
		
		<div class="specia-sites-panel wp-clearfix">
			<div class="specia-sites-wrapper" id="wrap-disk">
				<?php foreach ($theme_data as $themes) { 
				
				$theme = wp_get_theme();
				
				$get_theme_staus='';
				// Theme installed and activate.
				if ( $themes->name == $theme->name ) {
					$get_theme_staus= 'installed-and-active';
					$specia_btn_value= 'Activated';
				}else{

					// Theme installed but not activate.
					foreach ( (array) wp_get_themes() as $theme_dir => $themesss ) {
						if ( $themes->name == $themesss->name ) {
							$get_theme_staus= 'installed-but-inactive';
							$specia_btn_value= 'Activate Now';
						}
						 //$get_theme_staus= 'not-installed';
					}
				}
				
				?>
					<div id="specia-theme-activation-xl" class="specia-companion-sites-items">
						<div class="specia-companion-items-inner">
							<div class="specia-demo-screenshot">
								<div class="specia-demo-image" style="background-image: url(<?php echo esc_url($themes->screenshot_url); ?>);"></div>
									<div class="specia-demo-actions">
										<a class="specia-companion-btn specia-companion-btn-outline" href="https://demo.speciatheme.com/pro/<?php echo esc_html($themes->slug); ?>" target="_blank"><?php esc_html_e('Preview','specia-companion'); ?></a>
										<?php 
										if($get_theme_staus !== 'installed-and-active' && $get_theme_staus !== 'installed-but-inactive'):
											$get_theme_staus= 'not-installed';
											$specia_btn_value= 'Install & Activate Now';
										endif;
										$theme_status = 'specia-companion-theme-' . $get_theme_staus;
										echo sprintf( __( '<a href="#" class="%3$s xl-btn-active specia-companion-btn-outline xl-install-action specia-companion-btn" data-theme-slug="%1$s">%4$s</a>', 'specia-companion' ), esc_html($themes->name),esc_url( admin_url( 'themes.php?theme=%1$s' ) ), $theme_status, $specia_btn_value );
										//switch_theme( $themes->name );
										?>
									</div>
								</div>
								<div class="sp-demo-meta  sp-demo-meta--with-preview">
									<div class="sp-demo-name"><h4 title="specia"><a href="<?php echo esc_url(admin_url('theme-install.php?search='.$themes->name)); ?>"><?php echo esc_html($themes->name); ?></a></h4></div>	
									<a class="specia-companion-btn specia-companion-btn-outline" href="https://speciatheme.com/<?php echo esc_html($themes->slug); ?>-premium/" target="_blank"><?php esc_html_e('Buy Now','specia-companion'); ?></a>									
								</div>
								<?php //echo $get_theme_staus; ?>
						</div>
					</div>
				<?php } ?>									
										
				</div>
			</div>
		
		<?php
}


		/**
		 * Activate theme
		 *
		 * @since 1.0
		 * @return void
		 */
		function activate_theme() { 
			 $specia_current_theme =  strtolower($_POST['specia_current_theme']);
			switch_theme(  $specia_current_theme );
			wp_send_json_success(
				array(
					'success' => true,
					'message' => __( 'Theme Successfully Activated', 'specia-companion' ),
				)
			);
			wp_die(); 
		}
		

	}
}// End if().

/**
 *  Kicking this off by calling 'get_instance()' method
 */
Specia_Companion_Setup::get_instance();

/**
 * The code during plugin activation.
 */
function activate_speciacompanion() {
	require_once plugin_dir_path( __FILE__ ) . 'inc/specia-companion-activator.php';
	Specia_Companion_Activator::activate();
}
register_activation_hook( __FILE__, 'activate_speciacompanion' );