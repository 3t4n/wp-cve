<?php
/*
 * Wpforms configuration and Intigration page
 * @since 1.0
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
   exit();
}

$active_tab = ( isset($_GET['tab']) && sanitize_text_field($_GET["tab"])) ? sanitize_text_field($_GET['tab']) : 'integration';
?>

<div class="wrap">
   <?php
   $tabs = array(
       'integration'    => __('Integration', 'gsheetconnector-wpforms'),
       'settings'       => __('GoogleSheet Form Settings', 'gsheetconnector-wpforms'),
       'faq'            => __('FAQ', 'gsheetconnector-wpforms'),
       'demos'          => __('Demos', 'gsheetconnector-wpforms'),
       'support'        => __('Support','gsheetconnector-wpforms'),
       'system_status'  => __('System Status', 'gsheetconnector-wpforms'),
   );
   echo '<div id="icon-themes" class="icon32"><br></div>';
   echo '<h2 class="nav-tab-wrapper">';
   foreach ($tabs as $tab => $name) {
      $class = ( $tab == $active_tab ) ? ' nav-tab-active' : '';
      echo "<a class='nav-tab$class' href='?page=wpform-google-sheet-config&tab=$tab'>$name</a>";
   }
   echo '</h2>';
   switch ($active_tab) {
      case 'settings' :
         $wpform_settings = new WPforms_Googlesheet_Services();
         $wpform_settings->add_settings_page();
         break;
      case 'integration' :
         $wpform_integration = new WPforms_Googlesheet_Services();
         $wpform_integration->add_integration();
         break;
       case 'faq' :
         include( WPFORMS_GOOGLESHEET_PATH . "includes/pages/wpforms-integration-faq.php" );
         break;
      case 'demos' :
         include( WPFORMS_GOOGLESHEET_PATH . "includes/pages/wpforms-integration-demo-info.php" );
         break;
      case 'support' :
         include( WPFORMS_GOOGLESHEET_PATH . "includes/pages/wpforms-gs-integration-support.php" );
         break;
       case 'system_status' :
         include( WPFORMS_GOOGLESHEET_PATH . "includes/pages/wpforms-integrate-system-info.php" );
         break;
   }
   ?>
</div>

