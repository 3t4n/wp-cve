<?php



/*

 * Plugin Name: Fast ClickFunnels

 * Plugin URI: https://www.fastflow.io

 * Description: ClickFunnels integration with FastMember

 * Version: 1.1
 * Author: FastFlow.io

 * Author URI: https://www.fastflow.io

 */



// get wordpress version number and fill it up to 9 digits

$int_wp_version = preg_replace('#[^0-9]#', '', get_bloginfo('version'));

while(strlen($int_wp_version) < 9) {

    $int_wp_version .= '0';

}



// get php version number and fill it up to 9 digits

$int_php_version = preg_replace('#[^0-9]#', '', phpversion());

while(strlen($int_php_version) < 9) {

    $int_php_version .= '0';

}



if ($int_wp_version >= 390000000 && 		// Wordpress version > 3.9

    $int_php_version >= 520000000 && 		// PHP version > 5.2

    defined('ABSPATH') && 			// Plugin is not loaded directly

    defined('WPINC')) {				// Plugin is not loaded directly



        define('FASTCF_DIR', dirname(__FILE__));

        define('FASTCF_URL', plugins_url('/', __FILE__));

        define('FASTCF_MAIN_PLUGINS_DIRR', dirname(dirname(__FILE__)));

        define('FASTCF_PLUGIN_NAME' , 'Fast ClickFunnels');

        define('FASTCF_PLUGIN_SLUG' , 'fast-clickfunnels');

        define('FASTCF_PLUGIN_VERSION' , '1.0.2');

        if (in_array( 'fastmember/fastmember.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) || in_array( 'fastmember-pro/fastmember-pro.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {

            require_once( FASTCF_DIR . '/lib/fast-cf-main-class.php' );

            $fcf = new fast_CF_Main_Class();

        }





} else add_action('admin_notices', 'fastcf_incomp');





function fastcf_incomp(){

    echo '<div id="message" class="error">

    <p><b>The &quot;Fast ClickFunnels&quot; Plugin does not work on this WordPress installation!</b></p>

    <p>Please check your WordPress installation for following minimum requirements:</p>

    <p>

    - WordPress version 3.9 or higher<br />

    - PHP version 5.2 or higher<br />

    </p>

    <p>Do you need help? Contact <a href="mailto:support@fastflow.io">Support</a></p>

    </div>';

}
