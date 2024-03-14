<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              http://topinfosoft.com
 * @since             1.0.1
 * @package           Wp_Visitors_Details
 *
 * @wordpress-plugin
 * Plugin Name:       Visitors Details
 * Plugin URI:        http://topinfosoft.com
 * Description:       This plugin will help you to store your website's visitor information to database. Information track visitors IP, OS, Browser information. Get details from settings->Visitors details
 * Version:           1.0.1
 * Author:            Top Infosoft
 * Author URI:        http://topinfosoft.com/about/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       visitors-details
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.1 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define('WP_VISITORS_DETAILS_VERSION', '1.0.1');

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-wp-visitors-details-activator.php
 */
function activate_wp_visitors_details()
{
    require_once plugin_dir_path(__FILE__) . 'includes/class-wp-visitors-details-activator.php';
    Wp_Visitors_Details_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-wp-visitors-details-deactivator.php
 */
function deactivate_wp_visitors_details()
{
    require_once plugin_dir_path(__FILE__) . 'includes/class-wp-visitors-details-deactivator.php';
    Wp_Visitors_Details_Deactivator::deactivate();
}

register_activation_hook(__FILE__, 'activate_wp_visitors_details');
register_deactivation_hook(__FILE__, 'deactivate_wp_visitors_details');

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path(__FILE__) . 'includes/class-wp-visitors-details.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.1
 */
function run_wp_visitors_details()
{

    $plugin = new Wp_Visitors_Details();
    $plugin->run();

}
run_wp_visitors_details();

// custom code

// create custom database
global $wvd_db_version;
$wvd_db_version = '1.0';

function visitorsDetailTbl()
{
    global $wpdb;
    global $wvd_db_version;

    $wvd_table_name = $wpdb->prefix . 'visitordetails';

    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE $wvd_table_name (
		id mediumint(9) NOT NULL AUTO_INCREMENT,
		time datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
		ip varchar(55) DEFAULT '' NOT NULL,
		device varchar(55) DEFAULT '' NOT NULL,
		os varchar(55) DEFAULT '' NOT NULL,
		browser varchar(55) DEFAULT '' NOT NULL,
		PRIMARY KEY  (id)
	) $charset_collate;";

    require_once ABSPATH . 'wp-admin/includes/upgrade.php';
    dbDelta($sql);

    add_option('wvd_db_version', $wvd_db_version);
}
// register hook
register_activation_hook(__FILE__, 'visitorsDetailTbl');

// insert to table
function wvd_visitorsDetailInsert($ip = null, $purpose = "location", $deep_detect = true)
{
    global $wpdb;

    include plugin_dir_path(__FILE__) . 'includes/UserInfo.php';

    $wvd_table_name = $wpdb->prefix . 'visitordetails';
    if (!is_admin()) {
        $wpdb->insert(
            $wvd_table_name,
            array(
                'time' => current_time('mysql'),
                'ip' => UserInfo::get_ip(),
                'device' => UserInfo::get_device(),
                'os' => UserInfo::get_os(),
                'browser' => UserInfo::get_browser(),
            )
        );
    }
}
add_action('wp', 'wvd_visitorsDetailInsert');
// insert to table

// show menu at admin
/** Step 2 (from text above). */
add_action('admin_menu', 'wvd_visitorDetail_menu');

/** Step 1. */
function wvd_visitorDetail_menu()
{
    add_options_page('Visitor Details Options', 'Visitors Details', 'manage_options', 'visitors-details', 'wvd_plugin_options');
}

/** Step 3. */
function wvd_plugin_options()
{
    global $wpdb;
    $wvd_table_name = $wpdb->prefix . 'visitordetails';
    if (!current_user_can('manage_options')) {
        wp_die(__('You do not have sufficient permissions to access this page.'));
    }
    ?>
<div class="wrap">
<div style="width:68%; float: left; background-color: #fff; padding: 10px 20px 20px 20px;">
<h2>Visitors List</h2>
<p>Every time user comes to your website it will show you the result here. If you want to remove all data. </p>
<table id="example" class="table table-striped table-bordered" style="width:100%">
        <thead>
            <tr>
                <th>Time</th>
                <th>IP</th>
                <th>Device</th>
                <th>OS</th>
                <th>Browser</th>
            </tr>
        </thead>
        <tbody>
        	<?php
$results = $wpdb->get_results("SELECT * FROM $wvd_table_name");
    if (!empty($results)) {
        foreach ($results as $row) {
            ?>
            <tr style="text-align: center;">
                <td><?php echo $row->time; ?></td>
                <td><?php echo $row->ip; ?></td>
                <td><?php echo $row->device; ?></td>
                <td><?php echo $row->os; ?></td>
                <td><?php echo $row->browser; ?></td>
            </tr>
           <?php
}
    }?>
        </tbody>
        <tfoot>
            <tr>
            	<th>Time</th>
                <th>IP</th>
                <th>Device</th>
                <th>OS</th>
                <th>Browser</th>
            </tr>
        </tfoot>
    </table>
</div>
    <div style="width:24%; float: right; background-color: #fff; padding: 10px 20px 20px 20px; ">
    	<h2>Plugin Details</h2>
    	<p>This plugin will grab the number of user visits. It will show the time when user visited and their IP Address, Device, OS and Browser Information</p>
    	<form method="post">
    		<h2>Delete Records</h2>
    		<p>If you want to delete all the records you can click on the below delete button</p>
<input name="visitorDelete" type="hidden" value="<?php echo wp_create_nonce('visitor-Delete'); ?>" />

    		<input type="submit" name="delete" value="Delete" style="background-color: red; border:none; color:#fff; border-radius:20px; font-size:12px; padding:3px 10px;" onClick="window.location.reload()">
    		
    	</form>

	<?php

if(isset($_POST['delete']))
{
if (!isset($_POST['visitorDelete'])) die("<br><br>Hmm .. looks like you didn't send any credentials.. No CSRF for you! ");
if (!wp_verify_nonce($_POST['visitorDelete'],'visitor-Delete')) die("<br><br>Hmm .. looks like you didn't send any credentials.. No CSRF for you! ");
        global $wpdb;
        $wpdb->query($wpdb->prepare("DELETE FROM $wvd_table_name"));
echo "<script type='text/javascript'>
        window.location=document.location.href;
        </script>";
}
?>
    </div>
</div>
<?php
}
