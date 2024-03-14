<?php
/*
 * njforms configuration and Intigration page
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
       'integration'    => __('Integration', 'gsheetconnector-ninjaforms'),
       'faq'            => __('FAQ', 'gsheetconnector-ninjaforms'),
       'demos'          => __('Demos', 'gsheetconnector-ninjaforms'),
       'system_status'  => __('System Status', 'gsheetconnector-ninjaforms'),
      
   );
   echo '<div id="icon-themes" class="icon32"><br></div>';
   echo '<h2 class="nav-tab-wrapper">';
   foreach ($tabs as $tab => $name) {
      $class = ( $tab == $active_tab ) ? ' nav-tab-active' : '';
      echo "<a class='nav-tab$class' href='?page=njform-google-sheet-config&tab=$tab'>".esc_html($name)."</a>";
   }
   echo '</h2>';
   switch ($active_tab) {
      case 'settings' :
         $njform_settings = new NJforms_Googlesheet_Services();
         $njform_settings->add_settings_page();
         break;
      case 'integration' :
         $njform_integration = new NJforms_Googlesheet_Services();
         $njform_integration->add_integration();
         break;
       case 'faq' :
         include( NINJAFORMS_GOOGLESHEET_PATH . "includes/pages/njforms-integration-faq.php" );
         break;
      case 'demos' :
         include( NINJAFORMS_GOOGLESHEET_PATH . "includes/pages/njforms-integration-demo-info.php" );
         break;
      case 'support' :
         include( NINJAFORMS_GOOGLESHEET_PATH . "includes/pages/njforms-gs-integration-support.php" );
         break;
       case 'system_status' :
         include( NINJAFORMS_GOOGLESHEET_PATH . "includes/pages/njforms-integrate-system-info.php" );
         break;
   }
   ?>
</div>