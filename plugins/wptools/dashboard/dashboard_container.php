<?php
/**
 * @ Author: Bill Minozzi
 * @ Copyright: 2020 www.BillMinozzi.com
 * @ Modified time: 2021-03-02 12:38:04
 */
if (!defined('ABSPATH')) {
   die('We\'re sorry, but you can not directly access this file.');
 }
global $wptools_checkversion;
global $wptools_tab;
$active_tab = $wptools_tab;
?>
<h2 class="nav-tab-wrapper">
    <a href="admin.php?page=wptools_options31&tab=dashboard" class="nav-tab"><?php esc_attr_e("Dashboard","wptools");?></a>
    <a href="admin.php?page=wptools_options31&tab=requirements" class="nav-tab"><?php esc_attr_e("Server Check & Requirements","wptools");?></a>
    <a href="admin.php?page=wptools_options31&tab=debug" class="nav-tab"><?php esc_attr_e("Debug Info","wptools");?></a>
    <a href="admin.php?page=wptools_options31&tab=tools" class="nav-tab"><?php esc_attr_e("More Tools","wptools");?></a> 
    <?php 
    if(empty($wptools_checkversion)) { ?> 
    <a href="https://wptoolsplugin.com/premium/" class="nav-tab"><?php esc_attr_e("Go Pro","wptools");?></a>
    <?php
   } ?>
</h2>
<?php
  echo '<div id="wptools-dashboard-left">'; 
if($wptools_tab == 'dashboard')
   require_once(WPTOOLSPATH . 'dashboard/dashboard.php');
elseif($wptools_tab == 'tools')
   require_once(WPTOOLSPATH . 'dashboard/freebies.php');
elseif($wptools_tab == 'debug')
   require_once(WPTOOLSPATH . 'dashboard/tools.php');
else
   require_once(WPTOOLSPATH . 'dashboard/requirements.php');
?>
</div> <!-- "wptools-dashboard-left"> -->
<div id="wptools-dashboard-right">
    <div id="wptools-containerright-dashboard">
        <?php 
           require_once(WPTOOLSPATH . "dashboard/mybanners.php");
         ?>
    </div>
</div> <!-- "wptools-dashboard-right"> -->
<?php
return;
function edd_tools_sysinfo_download22() {
	if( ! current_user_can( 'manage_shop_settings' ) ) {
	}
	nocache_headers();
	header( 'Content-Type: text/plain' );
	header( 'Content-Disposition: attachment; filename="edd-system-info.txt"' );
	// echo wp_strip_all_tags( $_POST['edd-sysinfo'] );
   echo esc_attr( sanitize_text_field($_POST['edd-sysinfo'] ));
}
?>