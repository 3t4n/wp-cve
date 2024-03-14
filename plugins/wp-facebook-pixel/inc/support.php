<?php
global $WPFacebookPixel;
?>
<div style="max-width: 800px;">
    Need some help?  We love helping our customers!  We pride ourselves on our quick response rate to customer questions and would love an
    opportunity to show you how helpful we can be!  Contact us on our support forum directly here 
    <a href="http://nightshiftapps.com/forums/forum/wp-facebook-pixel-support/" target="_blank">http://nightshiftapps.com/forums/forum/wp-facebook-pixel-support/ <img src="<?php echo $WPFacebookPixel->plugin_root_dir_url; ?>inc/images/newtab.png" /></a>
</div>
<div style="max-width: 800px;">
    <h3>System Report</h3>
    After contacting us on our support forum, we may ask for more information about your particular environment.  This report contains 
    information about your host, WordPress core, active plug-ins and Remarketable settings.<br />
    Highlight and copy (<pre style="display: inline;">[Control]/[Command] + [C]</pre>) the information below and send to 
    <a href="mailto:info@nightshiftapps.com">info@nightshiftapps.com</a> along with information about your problem and we will be happy to look into it!
    <br />
    <textarea id="support_info" style="width: 100%" rows="30" readonly="readonly">
---- HOST & SERVER ---- 
PHP Version: <?php echo ( function_exists( 'phpversion' ) ? phpversion() : 'Cannot get PHP version.'); ?> 
Server Type: <?php echo esc_html( $_SERVER['SERVER_SOFTWARE'] ); ?> 
MySQL Version: <?php global $wpdb; echo $wpdb->db_version(); ?> 
 
 
---- WORDPRESS ---- 
Version: <?php bloginfo('version'); ?> 
Home URL: <?php form_option( 'home' ); ?> 
Site URL: <?php form_option( 'siteurl' ); ?> 
Multisite: <?php if ( is_multisite() ) echo 'Yes'; else echo 'No'; ?> 
Debug Mode: <?php if ( defined('WP_DEBUG') && WP_DEBUG ) echo 'Yes'; else echo 'No'; ?> 
Plugin Host: <?php
    $host = parse_url( $WPFacebookPixel->NOTIFICATION_URL, PHP_URL_HOST );
    echo ((!defined( 'WP_ACCESSIBLE_HOSTS' ) || (WP_HTTP_BLOCK_EXTERNAL === false) || (WP_HTTP_BLOCK_EXTERNAL === true && stristr( WP_ACCESSIBLE_HOSTS, $host ) === true )) ? 'Open' : 'Blocked' );
                     ?> 
 
 
---- ACTIVE THEME ---- 
<?php
    include_once( ABSPATH . 'wp-admin/includes/theme-install.php' );

    $active_theme         = wp_get_theme();
    $theme_version        = $active_theme->Version;

    echo esc_html( $active_theme->Name )." (".esc_html( $theme_version ).") \n";

    echo $active_theme->{'Author URI'}." \n";
?>
 
 
---- ACTIVE PLUGINS ---- 
<?php
$active_plugins = (array) get_option( 'active_plugins', array() );

if ( is_multisite() ) {
    $network_activated_plugins = array_keys( get_site_option( 'active_sitewide_plugins', array() ) );
    $active_plugins            = array_merge( $active_plugins, $network_activated_plugins );
}

foreach ( $active_plugins as $plugin ) {

    $plugin_data    = @get_plugin_data( WP_PLUGIN_DIR . '/' . $plugin );
    $dirname        = dirname( $plugin );
    $version_string = '';
    $network_string = '';

    if ( ! empty( $plugin_data['Name'] ) ) {
        echo sprintf("%s (%s) - %s \n", esc_html( $plugin_data['Name'] ), esc_html( $plugin_data['Version'] ), $plugin_data['PluginURI']);
    }
}
?>
 
 
---- Remarketable ---- 
Version: <?php echo $WPFacebookPixel->PLUGIN_VERSION_NAME ?> <?php echo ($WPFacebookPixel->ProEnabled ? 'Pro' : '') ?> (<?php echo $WPFacebookPixel->PLUGIN_VERSION ?>) 
Settings: 
<?php print_r(get_option('nsa_wpfbp_settings')); ?> 

    </textarea>
</div>

<script type="text/javascript">
    jQuery(document).ready(function ($) {
        $('#support_info').select();
    });
</script>