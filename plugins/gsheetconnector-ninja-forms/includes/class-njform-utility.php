<?php
/*
 * Utilities class for njform google sheet connector
 * @since       1.0
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
   exit;
}
/**
 * Utilities class - singleton class
 * @since 1.0
 */
class NJForm_gs_Connector_Utility {

   // private function __construct() {
   //    // Do Nothing
   // }

  /**
    * Add Sub Menu in Ninja Form
    *
    * @return singleton instance of NJForm_gs_Connector_Utility
    */
  public function admin_page() {
    try{
      $current_role = $this->get_current_user_role();
      add_submenu_page('ninja-forms', __('Google Sheet', 'gsheetconnector-ninjaforms'), __('Google Sheet', 'gsheetconnector-ninjaforms'), $current_role, 'njform-google-sheet-config', array($this, 'njforms_google_sheet_config'));
    } catch (Exception $e) {
         $this->gs_debug_log("Something Wrong : - " . $e->getMessage());
      } 
   }

   /**
    * Setting Page 
    *
    * @return singleton instance of NJForm_gs_Connector_Utility
    */
   public function njforms_google_sheet_config() {
       include( NINJAFORMS_GOOGLESHEET_PATH . "includes/pages/njforms-gs-settings.php" );
   }
   
   /**
    * Get the singleton instance of the NJForm_gs_Connector_Utility class
    *
    * @return singleton instance of NJForm_gs_Connector_Utility
    */
   public static function instance() {

      static $instance = NULL;
      if (is_null($instance)) {
         $instance = new NJForm_gs_Connector_Utility();
      }
      return $instance;
   }

   /**
    * Prints message (string or array) in the debug.log file
    *
    * @param mixed $message
    */
   public function logger($message) {
      if (WP_DEBUG === true) {
         if (is_array($message) || is_object($message)) {
            error_log(print_r($message, true));
         } else {
            error_log($message);
         }
      }
   }

   /**
    * Display error or success message in the admin section
    *
    * @param array $data containing type and message
    * @return string with html containing the error message
    * 
    * @since 1.0 initial version
    */
   public function admin_notice($data = array()) {
      // extract message and type from the $data array
      $message = isset($data['message']) ? $data['message'] : "";
      $message_type = isset($data['type']) ? $data['type'] : "";
      switch ($message_type) {
         case 'error':
            $admin_notice = '<div id="message" class="error notice is-dismissible">';
            break;
         case 'update':
            $admin_notice = '<div id="message" class="updated notice is-dismissible">';
            break;
         case 'update-nag':
            $admin_notice = '<div id="message" class="update-nag">';
            break;
         case 'upgrade':
            $admin_notice = '<div id="message" class="error notice njforms-gs-upgrade is-dismissible">';
            break;
         default:
            $message = __('There\'s something wrong with your code...', 'gsheetconnector-ninjaforms');
            $admin_notice = "<div id=\"message\" class=\"error\">\n";
            break;
      }

      $admin_notice .= "    <p>" . __($message, 'gsheetconnector-ninjaforms') . "</p>\n";
      $admin_notice .= "</div>\n";
      return $admin_notice;
   }

   /**
    * Utility function to get the current user's role
    *
    * @since 1.0
    */
   public function get_current_user_role() {
      global $wp_roles;
      foreach ($wp_roles->role_names as $role => $name) :
         if (current_user_can($role))
            return $role;
      endforeach;
   }

   /**
    * Utility function to get the current user's role
    *
    * @since 1.0
    */
   public static function gs_debug_log($error){
      try{  
         if( ! is_dir( NINJAFORMS_GOOGLESHEET_PATH.'logs' ) ){
            mkdir( NINJAFORMS_GOOGLESHEET_PATH . 'logs', 0755, true );
         }
      } catch (Exception $e) {

      }
      try{
         // check if debug log file exists or not
        $nflogFilePathToDelete = NINJAFORMS_GOOGLESHEET_PATH . "logs/log.txt";
        // Check if the log file exists before attempting to delete
        if (file_exists($nflogFilePathToDelete)) {
            unlink($nflogFilePathToDelete);
        }
         // check if debug unique log file exists or not
         $nfexistDebugFile = get_option('nfgs_debug_log_file');
         if (!empty($nfexistDebugFile) && file_exists($nfexistDebugFile)) {
         $nflog = fopen( $nfexistDebugFile , 'a');
         if ( is_array( $error ) ) {
            fwrite($nflog, print_r(date_i18n( 'j F Y H:i:s', current_time( 'timestamp' ) )." \t PHP ".phpversion(), TRUE));
            fwrite( $nflog, print_r($error, TRUE));   
         } else {
         $result = fwrite($nflog, print_r(date_i18n( 'j F Y H:i:s', current_time( 'timestamp' ) )." \t PHP ".phpversion()." \t $error \r\n", TRUE));
         }
         fclose( $nflog );
            }
        else{
        // if unique log file not exists then create new file code
        // Your log content (you can customize this)
        $nf_unique_log_content = "Log created at " . date('Y-m-d H:i:s');
        // Create the log file
          $nflogfileName = 'log-' . uniqid() . '.txt';
        // Define the file path
          $nflogUniqueFile = NINJAFORMS_GOOGLESHEET_PATH . "logs/".$nflogfileName;
       if (file_put_contents($nflogUniqueFile, $nf_unique_log_content)) {
         // save debug unique file in table
         update_option('nfgs_debug_log_file', $nflogUniqueFile);
         $nflog = fopen( $nflogUniqueFile , 'a');
         if ( is_array( $error ) ) {
            fwrite($nflog, print_r(date_i18n( 'j F Y H:i:s', current_time( 'timestamp' ) )." \t PHP ".phpversion(), TRUE));
            fwrite( $nflog, print_r($error, TRUE));   
         } else {
         $result = fwrite($nflog, print_r(date_i18n( 'j F Y H:i:s', current_time( 'timestamp' ) )." \t PHP ".phpversion()." \t $error \r\n", TRUE));
         }
         fclose( $nflog );

       } else {
        // Error message
        echo "Error - Not able to create Log File.";
          }
        }
        
      } catch (Exception $e) {
         
      }
   }

}
