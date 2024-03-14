<?php

/**
 *
 * @link               	 epaka.pl
 * @since             	 1.0.0
 * @package           	 epaka
 *
 * @wordpress-plugin
 * Plugin Name:       	 epaka.pl
 * Plugin URI:        	 https://www.epaka.pl
 * Description:       	 Integracja Woocommerce z epaka.pl. Składanie zamówień, generowanie etykiet, tracking przesyłek, mapa puntów odbioru dla klientów sklepu oraz o wiele więcej przydatnych funkcji dostępnych pod ręką.
 * Version:           	 1.0.10
 * Author:            	 epaka.pl
 * Author URI:        	 https://www.epaka.pl
 * License:           	 GPL-2.0+
 * License URI:       	 http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       	 epaka
 * Domain Path:       	 /languages
 * Requires at least: 	 4.4
 * Requires PHP:      	 5.6
 * WC requires at least: 2.6.0
 * WC tested up to:      5.6.0
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

$epaka_wooActive = false;
foreach(apply_filters( 'active_plugins', get_option( 'active_plugins' )) as $plugin){
	if(preg_match( '/woocommerce.php/',$plugin) == 1){
		$epaka_wooActive = true;
	}
}

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_epaka() {
	/**
	 * Currently plugin version.
	 * Start at version 1.0.0 and use SemVer - https://semver.org
	 * Rename this for your plugin and update it as you release new versions.
	 */
	define( 'EPAKA_VERSION', '1.0.11' );
	define( 'EPAKA_DOMAIN', 'https://www.epaka.pl/' );
	define( 'EPAKA_PATH', dirname(__FILE__) );
	

	/**
	 * The code that runs during plugin activation.
	 * This action is documented in includes/class-epaka-activator.php
	 */
	function activate_epaka() {
		require_once plugin_dir_path( __FILE__ ) . 'includes/class-epaka-activator.php';
		Epaka_Activator::activate();
	}

	/**
	 * The code that runs during plugin deactivation.
	 * This action is documented in includes/class-epaka-deactivator.php
	 */
	function deactivate_epaka() {
		require_once plugin_dir_path( __FILE__ ) . 'includes/class-epaka-deactivator.php';
		Epaka_Deactivator::deactivate();
	}

	register_activation_hook( __FILE__, 'activate_epaka' );
	register_deactivation_hook( __FILE__, 'deactivate_epaka' );

	/**
	 * The core plugin class that is used to define internationalization,
	 * admin-specific hooks, and public-facing site hooks.
	 */
	require plugin_dir_path( __FILE__ ) . 'includes/class-epaka.php';

	$epaka_plugin = new Epaka();
	$epaka_plugin->run();

}

if(!empty(get_option('permalink_structure'))){
	/**
	 * Check if WooCommerce is active
	 **/
	if ($epaka_wooActive) {
		if(!empty(get_option("epaka_credits_agree"))){
			run_epaka();
		}else{
			function epaka_woo_error() {
				$class = 'notice notice-error';
				$message = "Do działania pluginu Epaka wymagana jest zgoda na wyświetlenie uznania dla OpenStreetMap oraz Leaflet.";
			 
				printf( '<div class="%1$s"><p>%2$s</p></div>', esc_attr( $class ), esc_html( $message ) );
			}
			add_action( 'admin_notices','epaka_woo_error');

			function epaka_credits(){
				wp_enqueue_style( "epaka", plugin_dir_url( __FILE__ ) . '/admin/css/epaka-admin.min.css', array(), "", 'all' );
				add_menu_page(__('Epaka', 'epaka'),__('Epaka', 'epaka'),'manage_woocommerce','epaka_admin',function(){
					if(!empty($_POST['epaka_credits_agree']) && $_POST['epaka_credits_agree'] == "on"){
						add_option("epaka_credits_agree", 1);
						run_epaka();
						require_once plugin_dir_path( __FILE__ ) . 'includes/class-epaka-activator.php';
						Epaka_Activator::activate();
						wp_redirect("/wp-admin/admin.php?page=epaka_admin_panel_login_page");
					}
					echo "
					<div class='epaka-card'>
						<div class='margin-10px'>
							<span>
								Aby plugin mógł wyświetlić mapę na stronach dla klientów wymagana jest zgoda na wyświetlenie odnośników w mapie do <a href='https://www.openstreetmap.org/'>OpenStreetMap</a>(Map data © <a href='https://www.openstreetmap.org/'>OpenStreetMap</a> contributors, <a href='https://creativecommons.org/licenses/by-sa/2.0/'>CC-BY-SA</a>) oraz <a href='https://leafletjs.com/'>Leaflet</a>. 
							</span>
						</div>
						<form action='/wp-admin/admin.php?page=epaka_admin' method='POST' class='margin-10px'>
							<input id='credits_agree' name='epaka_credits_agree' type='checkbox'/>
							<label for='credits_agree'><b>Zgadzam się</b></label>
							<br/>
							<br/>
							<input value='Zapisz' type='submit'/>
						</form>
					</div>";
				});
			}

			add_action( 'admin_menu', 'epaka_credits' );
		}
	}else{
		// echo "<div>".(new WP_Error( 'woocommerce', "Do działania pluginu Epaka wymagany jest aktywny plugin WooCommerce."))->get_error_messages()."</div>";
		// show_message('Do działania pluginu Epaka wymagany jest aktywny plugin WooCommerce.');
		// apply_filters( 'wp_php_error_message', "Do działania pluginu Epaka wymagany jest aktywny plugin WooCommerce.", "woocommerce" );
		// error_log("Do działania pluginu Epaka wymagany jest aktywny plugin WooCommerce.", 0);
		// br_trigger_error('Do działania pluginu Epaka wymagany jest aktywny plugin WooCommerce.',E_USER_ERROR);
		function epaka_woo_error() {
			$class = 'notice notice-error';
			$message = "Do działania pluginu Epaka wymagany jest aktywny plugin WooCommerce.";
		 
			printf( '<div class="%1$s"><p>%2$s</p></div>', esc_attr( $class ), esc_html( $message ) );
		}
		add_action( 'admin_notices','epaka_woo_error');
	}
}else{
	// show_message('Do poprawnego działania pluginu Epaka wymagane są bezpośrednie odnośniki.');
	// echo "<div>".(new WP_Error( 'permalinks', "Do poprawnego działania pluginu Epaka wymagane są bezpośrednie odnośniki."))->get_error_messages()."</div>";
	// apply_filters( 'wp_php_error_message', "Do poprawnego działania pluginu Epaka wymagane są bezpośrednie odnośniki.", "permalinks" );
	// error_log("Do poprawnego działania pluginu Epaka wymagane są bezpośrednie odnośniki.", 0);
	// br_trigger_error('Do poprawnego działania pluginu Epaka wymagane są bezpośrednie odnośniki. <br/> Ustawienia > Bezpośrednie Odnośniki',E_USER_ERROR);
	function epaka_links_error() {
		$class = 'notice notice-error';
		$message = "Do poprawnego działania pluginu Epaka wymagane są bezpośrednie odnośniki.  (Ustawienia --> Bezpośrednie Odnośniki)";
	 
		printf( '<div class="%1$s"><p>%2$s</p></div>', esc_attr( $class ), esc_html( $message ) );
	}
	add_action( 'admin_notices','epaka_links_error');
}


