<?php
/*
Plugin Name:    Log cleaner for Solid Security
Plugin URI:     https://wordpress.org/plugins/log-cleaner-for-solid-security/
Description:    Delete Solid Security logs from the database.
Version:        1.4.1
Author:         Rocket Apps
Author URI:     https://rocketapps.com.au/
License:        GPL2
Domain Path:    /languages/
*/

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Look for translation file.
function load_log_cleaner_textdomain() {
    load_plugin_textdomain( 'log-cleaner-for-solid-security', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
}
add_action( 'plugins_loaded', 'load_log_cleaner_textdomain' );

// Create admin page under the Tools menu.
if ( is_multisite() ) {
    add_action('network_admin_menu', 'create_network_cleaner_submenu');
    function create_network_cleaner_submenu() {
        $icon =  plugins_url('images/', __FILE__ ) . 'shield.svg';
        add_menu_page( "Solid Security Log Cleaner", "Solid Security Log Cleaner", 'manage_options', 'log-cleaner-for-solid-security', 'generate_page_content', $icon);	
    }
} else {
    add_action('admin_menu', 'create_tools_cleaner_submenu');
    function create_tools_cleaner_submenu() {
        add_management_page( 'Solid Security Log Cleaner', 'Solid Security Log Cleaner', 'manage_options', 'log-cleaner-for-solid-security', 'generate_page_content' );
    }
}

// Add custom CSS to admin
function log_cleaner_admin_style() {
    $plugin_data = get_plugin_data( __FILE__ );
    $plugin_version = $plugin_data['Version'];
	$plugin_directory = plugins_url('css/', __FILE__ );
    wp_enqueue_style('log-cleaner-style-admin', $plugin_directory . 'log-cleaner.css', '', $plugin_version);
}
add_action('admin_enqueue_scripts', 'log_cleaner_admin_style');

// Admin page.
function generate_page_content() { ?>
    
    <div class="wrap solid-security-log-cleaner">
        <form action="" method="post">
            <?php
                $page = $_GET["page"];

                if (isset($_POST['submit']) && wp_verify_nonce($_POST['things'], 'delete-things')) {

                    if (isset($_POST['logs']) || isset($_POST['dashboard']) || isset($_POST['lockouts']) || isset($_POST['temp']) || isset($_POST['ds'])) {

                        global $wpdb;
                        $charset_collate        = $wpdb->get_charset_collate();
                        $lockouts_table         = $wpdb->prefix . 'itsec_lockouts';
                        $logs_table             = $wpdb->prefix . 'itsec_logs';
                        $dashboard_table        = $wpdb->prefix . 'itsec_dashboard_events';
                        $temp_table             = $wpdb->prefix . 'itsec_temp';
                        $storage_ds_table       = $wpdb->prefix . 'itsec_distributed_storage';

                        if (isset($_POST['lockouts']) || isset($_POST['all'])) {
                            $wpdb->query("TRUNCATE TABLE " . $lockouts_table);
                        }

                        if (isset($_POST['logs']) || isset($_POST['all'])) {
                            $wpdb->query("TRUNCATE TABLE " . $logs_table);
                        }

    
                        if (isset($_POST['dashboard']) || isset($_POST['all'])) {
                            $wpdb->query("TRUNCATE TABLE " . $dashboard_table);
                        }
                        
                        
                        if (isset($_POST['temp']) || isset($_POST['all'])) {
                            $wpdb->query("TRUNCATE TABLE " . $temp_table);
                        }

                        if (isset($_POST['ds']) || isset($_POST['all'])) {
                            $wpdb->query("TRUNCATE TABLE " . $storage_ds_table);
                        }
                        ?>

                        <div id="message" class="updated notice notice-success is-dismissible" style="margin: 20px 0;">
                            <p><?php _e("The selected logs have been deleted.", 'log-cleaner-for-solid-security'); ?></p>
                        </div>

                    <?php } else { ?>
                        <div id="message" class="error notice notice-success is-dismissible" style="margin: 20px 0;">
                            <p><?php _e("You need to select at least one item to delete.", 'log-cleaner-for-solid-security'); ?></p>
                        </div>
                    <?php  }

                }
            ?>

            <h1><?php _e('Log cleaner for Solid Security', 'log-cleaner-for-solid-security'); ?></h1>

            <?php 
                global $wpdb;

                /* itsec_lockouts table */
                $table_itsec_lockouts = $wpdb->prefix . 'itsec_lockouts';
                $check_table_itsec_lockouts = $wpdb->get_var("SHOW TABLES LIKE '$table_itsec_lockouts'");
                if($check_table_itsec_lockouts == $table_itsec_lockouts) {
                    $itsec_lockouts = $wpdb->prefix . 'itsec_lockouts';
                    $lockouts_query = "SELECT count(*) from $itsec_lockouts";
                    $num_lockouts   = $wpdb->get_var($lockouts_query);
                } else {
                    $num_lockouts = 0;
                }
                
                /* itsec_logs table */
                $table_itsec_logs = $wpdb->prefix . 'itsec_logs';
                $check_table_itsec_logs = $wpdb->get_var("SHOW TABLES LIKE '$table_itsec_logs'");
                if($check_table_itsec_logs == $table_itsec_logs) {
                    $itsec_logs = $wpdb->prefix . 'itsec_logs';
                    $logs_query = "SELECT count(*) FROM $itsec_logs";
                    $num_logs   = $wpdb->get_var($logs_query);
                } else {
                    $num_logs = 0;
                }
                
                /* itsec_dashboard_events table */
                $table_itsec_dashboard_events = $wpdb->prefix . 'itsec_dashboard_events';
                $check_table_itsec_dashboard_events = $wpdb->get_var("SHOW TABLES LIKE '$table_itsec_dashboard_events'");
                if($check_table_itsec_dashboard_events == $table_itsec_dashboard_events) {
                    $dashboard_events       = $wpdb->prefix . 'itsec_dashboard_events';
                    $dashboard_events_query = "SELECT count(*) FROM $dashboard_events";
                    $num_dashboard_events   = $wpdb->get_var($dashboard_events_query);
                } else {
                    $num_dashboard_events = 0;
                }

                /* itsec_temp table */
                $table_itsec_temp = $wpdb->prefix . 'itsec_temp';
                $check_table_itsec_temp = $wpdb->get_var("SHOW TABLES LIKE '$table_itsec_temp'");
                if($check_table_itsec_temp == $table_itsec_temp) {
                    $itsec_temp = $wpdb->prefix . 'itsec_temp';
                    $temp_query = "SELECT count(*) from $itsec_temp";
                    $num_temps  = $wpdb->get_var($temp_query);
                } else {
                    $num_temps = 0;
                }

                /* itsec_distributed_storage table */
                $table_itsec_distributed_storage = $wpdb->prefix . 'itsec_distributed_storage';
                $check_table_itsec_distributed_storage = $wpdb->get_var("SHOW TABLES LIKE '$table_itsec_distributed_storage'");
                if($check_table_itsec_distributed_storage == $table_itsec_distributed_storage) {
                    $itsec_distributed_storage = $wpdb->prefix . 'itsec_distributed_storage';
                    $ds_query = "SELECT count(*) from $itsec_distributed_storage";
                    $num_ds  = $wpdb->get_var($ds_query);
                } else {
                    $num_ds = 0;
                }
 
                $combined_logs  = $num_logs;                
                $total          = $num_lockouts + $num_temps + $num_dashboard_events + $num_ds + $combined_logs;

                global $current_user;

                if ( is_multisite() ) {
                    $network = 'network';
                } else {
                    $network = '';
                }
                $log_link = $network . 'admin.php?page=itsec-logs';
            ?>

            <?php if($total > 0) { // If there are logs ?>
            <p class="delete-logs-notice">
                <?php printf( __( "Note: Continuing here will delete the selected Solid Security logs from the database. You absolutely can not undo this action. If in doubt, <a href='%s'>view the logs first</a> or backup your database.", "log-cleaner-for-solid-security" ), $log_link ); ?>
            </p>
            <?php } ?>

            <?php if($total > 0) { // If there are logs ?>

            <div class="log-cleaner boxy">

                <p><strong><?php _e("Clear the following log tables: ", 'log-cleaner-for-solid-security'); ?></strong></p>

                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tbody>
                        <tr class="all-items">
                            <td><input type="checkbox" name="all" id="all" /></td>
                            <td><?php _e("All", 'log-cleaner-for-solid-security'); ?></td>
                            <td></td>
                        </tr>
                        <?php if($combined_logs > 0) { ?>
                        <tr>
                            <td><input type="checkbox" name="logs" class="other-items" /></td>
                            <td><?php _e("Security logs", 'log-cleaner-for-solid-security'); ?></td>
                            <td><?php echo $combined_logs; ?> <?php _e("rows", 'log-cleaner-for-solid-security'); ?></td>
                        </tr>
                        <?php } ?>
                        <?php if($num_dashboard_events > 0) { ?>
                        <tr>
                            <td><input type="checkbox" name="dashboard" class="other-items" /></td>
                            <td><?php _e("Dashboard logs", 'log-cleaner-for-solid-security'); ?></td>
                            <td><?php echo $num_dashboard_events; ?> <?php _e("rows", 'log-cleaner-for-solid-security'); ?></td>
                        </tr>
                        <?php } ?>
                        <?php if($num_lockouts > 0) { ?>
                        <tr>
                            <td><input type="checkbox" name="lockouts" class="other-items" /></td>
                            <td><?php _e("Lockouts", 'log-cleaner-for-solid-security'); ?></td>
                            <td><?php echo $num_lockouts; ?> <?php _e("rows", 'log-cleaner-for-solid-security'); ?></td>
                        </tr>
                        <?php } ?>
                        <?php if($num_temps > 0) { ?>
                        <tr>
                            <td class="no-border"><input type="checkbox" name="temp" class="other-items" /></td>
                            <td class="no-border"><?php _e("Temps", 'log-cleaner-for-solid-security'); ?></td>
                            <td class="no-border"><?php echo $num_temps; ?> <?php _e("rows", 'log-cleaner-for-solid-security'); ?></td>
                        </tr>
                        <?php } ?>
                        <?php if($num_ds > 0) { ?>
                        <tr>
                            <td class="no-border"><input type="checkbox" name="ds" class="other-items" /></td>
                            <td class="no-border"><?php _e("Distributed Storage", 'log-cleaner-for-solid-security'); ?></td>
                            <td class="no-border"><?php echo $num_ds; ?> <?php _e("rows", 'log-cleaner-for-solid-security'); ?></td>
                        </tr>
                        <?php } ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                        </tr>
                        <tr class="total">
                            <td class="no-border"></td>
                            <td class="no-border">Total:</td>
                            <td class="no-border"><?php echo $total; ?> <?php _e("rows", 'log-cleaner-for-solid-security'); ?></td>
                        </tr>
                    </tfoot>
                </table>
                
                <script>
                    jQuery('#all').click(function(event) {   
                        if(this.checked) {
                            // Iterate each checkbox
                            jQuery(':checkbox').each(function() {
                                this.checked = true;
                                jQuery( event.target ).closest( 'tbody tr' ).addClass( 'active' );
                                jQuery('tbody tr').addClass('active');                   
                            });
                        } else {
                            jQuery(':checkbox').each(function() {
                                this.checked = false;    
                                jQuery( event.target ).closest( 'tbody tr' ).removeClass( 'active' );
                                jQuery('tbody tr').removeClass('active');                       
                            });
                        }
                    });
                    jQuery('.other-items').click(function(event) {
                        jQuery( event.target ).closest( 'tbody tr' ).toggleClass( 'active' );
                        jQuery('#all').prop("checked", false);
                        jQuery('.all-items').removeClass('active');  
                    });
                </script>
            </div>

            <?php  // If the total number of log entries is not 0, and if you're an administrator
                if(current_user_can( 'manage_options' )) { ?>
                <input type="submit" name="submit" class="button button-primary" value="<?php _e('Clear logs', 'log-cleaner-for-solid-security'); ?>" onclick="return confirm('<?php _e('This is your last chance. Are you sure?', 'log-cleaner-for-solid-security'); ?>')" />
                <?php wp_nonce_field( 'delete-things','things' ) ?>
            <?php } ?>

            <?php } else { // Otherwise, all logs are clear ?>
                <p class="all-clear">&#10004; <?php _e("All logs are clear.", 'log-cleaner-for-solid-security'); ?></p>
            <?php } ?>
    
        </form>

    </div>


<?php }