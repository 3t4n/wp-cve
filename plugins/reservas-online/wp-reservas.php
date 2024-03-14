<?php
/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://www.ticketself.com
 * @since             1.0.0
 * @package           Wp_Reservas
 *
 * @wordpress-plugin
 * Plugin Name:       Reservas Online
 * Plugin URI:        https://www.ticketself.com/es?cmp=wp
 * Description:       Reservas online es el plugin la gestión de tu reservas desde tu sitio WordPress. Seleccionado como mejor plugin de reservas &#9733;&#9733;&#9733;&#9733;&#9733; 
 * Version:           1.0
 * Author:            ticketself
 * Author URI:        https://www.ticketself.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       wp-reservas
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'RESERVAS_ONLINE_BASE_URL', 'https://www.ticketself.com/' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-wp-reservas-activator.php
 */
function activate_wp_reservas() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-wp-reservas-activator.php';
	Wp_Reservas_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-wp-reservas-deactivator.php
 */
function deactivate_wp_reservas() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-wp-reservas-deactivator.php';
	Wp_Reservas_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_wp_reservas' );
register_deactivation_hook( __FILE__, 'deactivate_wp_reservas' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-wp-reservas.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
 function reservas_online_admin_menu() {
 		$options = wp_load_alloptions();
        $username = !empty($options['wpReservas-email']) ? $options['wpReservas-email'] : '';
        $password = !empty($options['wpReservas-password']) ? $options['wpReservas-password'] : '';
 		$url = RESERVAS_ONLINE_BASE_URL.'action/integrations/wordpress/checknews';
		$fields = array(
		    'username' => $username,
		    'password' => $password,
		);
		$response = wp_remote_post( $url, array(
		    'method' => 'POST',
		    'timeout' => 5,
		    'httpversion' => '1.0',
		    'headers' => array(),
		    'body' => $fields,
		    )
		);
		if ( is_wp_error( $response ) ) {
		    $error_message = $response->get_error_message();
		    echo "Something went wrong: $error_message";
		} 
		else {
		    if ($response['body'] == "0") {
		    	$counter = 0;
		    }
		    else {
		    	$rps = json_decode($response['body'],true);
		    	$counter = $rps['counter'];
		    }
		}
		add_menu_page( 'Reservas online', 'Reservas online<span class="update-plugins count-'.$counter.'"><span class="plugin-count">' . number_format_i18n($counter) . '</span></span>', 'manage_options', 'reservas-online/reservas-online-admin-page.php', 'reservas_online_plugin_page', 'dashicons-tickets', 6);
	}
	function reservas_online_plugin_page(){
		$options = wp_load_alloptions();
        $username = !empty($options['wpReservas-email']) ? $options['wpReservas-email'] : '';
        $password = !empty($options['wpReservas-password']) ? $options['wpReservas-password'] : '';
		$url = RESERVAS_ONLINE_BASE_URL.'action/integrations/wordpress/auth';
		$fields = array(
			'username' => $username,
			'password' => $password,
		);
		$response = wp_remote_post( $url, array(
		    'method' => 'POST',
		    'timeout' => 5,
		    'httpversion' => '1.0',
		    'headers' => array(),
		    'body' => $fields
		    )
		);
		if ( is_wp_error( $response ) ) {
		    $error_message = $response->get_error_message();
		    echo "Something went wrong: $error_message";
		}
		else {
		    if ($response['body'] == "0") {
		    	echo '<div style="margin: 50px;"><img src="'.plugin_dir_url( __FILE__ ) . 'assets/default/img/logo.png" width="120"><br><br>Los datos de acceso no son correctos. Debes introducir tus datos de acceso correctos en <strong><a href="options-general.php?page=wp-reservas">la configuración del plugin</a>.</strong><br><br> Si tienes algún problema, envíanos un email a <a href="mailto:support@ticketself.com">support@ticketself.com</a></div>';
		    }
		    if ($response['body'] != "0") {
				//added link generator with token in order to avoid the iframe
				$token = $response['body'];
				$url = RESERVAS_ONLINE_BASE_URL.'action/integrations/wordpress/open';
				$fields = array(
					'token' => $token
				);
				$response = wp_remote_post( $url, array(
				    'method' => 'POST',
				    'timeout' => 5,
				    'httpversion' => '1.0',
				    'headers' => array(),
				    'body' => $fields
				    )
				);
				if ( is_wp_error( $response ) ) {
				    $error_message = $response->get_error_message();
				    echo "Something went wrong: $error_message";
				}
				else {
					?>
					<div style="margin: 10px;"><a id="link_opener" href="<?php echo RESERVAS_ONLINE_BASE_URL.'action/integrations/wordpress/authtoken/'.$token;?>">Abrir panel de ticketself</a></div>
					<div id="notifications_list">
					<?php echo $response['body'];?>
					</div>
					<?php
				}
			}
		}
	}
function run_wp_reservas() {
	$plugin = new Wp_Reservas();
	$plugin->run();
	add_action( 'admin_menu', 'reservas_online_admin_menu' );
}
run_wp_reservas();
