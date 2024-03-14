<?php
/**
 * service class for Wpform Google Sheet Connector
 * @since 1.0
 */
if (!defined('ABSPATH')) {
   exit; // Exit if accessed directly
}

/**
 * WPforms_Googlesheet_Services Class
 *
 * @since 1.0
 */
class WPforms_Googlesheet_Services {

   public function __construct() {
      // get with all data and display form
      add_action('wp_ajax_get_wpforms', array($this, 'display_wpforms_data'));
      // get all form data
      add_action('admin_init', array($this, 'execute_post_data'));
      // activation n deactivation ajax call
      add_action('wp_ajax_deactivate_wpformgsc_integation', array($this, 'deactivate_wpformgsc_integation'));
      // save entry with posted data
      add_action('wpforms_process_entry_save', array($this, 'entry_save'), 20, 4);
      add_action( 'wp_ajax_set_upgrade_notification_interval', array( $this, 'set_upgrade_notification_interval' ) );
      add_action( 'wp_ajax_close_upgrade_notification_interval', array( $this, 'close_upgrade_notification_interval' ) );

   }

   /**
    * AJAX function - get wpforms details with sheet data
    * @since 1.1
    */
   function display_wpforms_data() {

      // nonce check
      check_ajax_referer('wp-ajax-nonce', 'security');

      $form = get_post($_POST['wpformsId']);
      $form_id = $_POST['wpformsId'];
     $form_title = wpforms()->form->get( absint( $form_id ) );
    $form_name = '';
    if ( ! empty( $form_title  ) ) {
     $form_name =  $form->post_title;
    }
      ob_start();
      if(!empty($form_id)){
         $host_name = str_replace('/wp-admin', '', get_admin_url());
          $new_link = site_url("wp-admin/admin.php?page=wpforms-builder&view=settings&form_id=$form_id");
      }
      else{
     $new_link = site_url("wp-admin/admin.php?page=wpform-google-sheet-config&tab=settings");   
      }
    
      ?>
<p class="deprecated-notice">
    <?php _e( 'This settings page is deprecated and will be removed in upcoming version. Move your settings to the <a target="_blank" href="'.$new_link.'">new settings page</a> under GSheetConnector Tab to avoid loss of data.', 'gsheetconnector-wpforms' ); ?>
</p>
<?php 
      // $this->wpforms_googlesheet_settings_content($form_id,$form_name);
      $this->save_old_settings_to_new_settings($form_id,$form_name);
      $result = ob_get_contents();
      ob_get_clean();
      // wp_send_json_success(htmlentities($result));
      wp_send_json_success($new_link);
   }


   // moved old settings to new settings
    public function save_old_settings_to_new_settings($form_id,$form_name){
        
     $get_existing_data = get_post_meta($form_id, 'wpform_gs_settings');
    
     $gheet_new = [];
     if(!empty($get_existing_data)){
      foreach($get_existing_data as $ge){

    $gsheet_new['name'] = $form_name.' '.'GoogleSheet';
    $gsheet_new['gs_sheet_integration_mode'] = 'manual'; 
    $gsheet_new['gs_sheet_manuals_sheet_name'] = $ge['sheet-name'];
    $gsheet_new['gs_sheet_manuals_sheet_id'] = $ge['sheet-id']; 
    $gsheet_new['gs_sheet_manuals_sheet_tab_name'] = $ge['sheet-tab-name'];
    $gsheet_new['gs_sheet_manuals_sheet_tab_id'] = $ge['tab-id']; 
}

   // update_post_meta($form_id, 'wpform_gs_settings_new', $gsheet_new);
       update_post_meta($form_id, 'wpform_gs_settings', $gsheet_new);
   
   }

}


   /**
    * Function - save the setting data of google sheet with sheet name and tab name
    * @since 1.0
    */
   public function wpforms_googlesheet_settings_content($form_id, $form_name) {

      $get_data = get_post_meta($form_id, 'wpform_gs_settings');
   
     $get_disable_setting = get_post_meta($form_id, 'wpform_gs_old_settings');
      $check = $disable_text = '';
      if(isset($get_disable_setting[0]) && $get_disable_setting[0] == 1){
      $check = 'checked';
      $disable_text = 'disabled';
     }
       $saved_sheet_name = isset($get_data[0]['sheet-name']) ? $get_data[0]['sheet-name'] : "";
      
       $saved_tab_name = isset($get_data[0]['sheet-tab-name']) ? $get_data[0]['sheet-tab-name'] : "";
    
       $saved_sheet_id = isset($get_data[0]['sheet-id']) ? $get_data[0]['sheet-id'] : "";
    
      $saved_tab_id = isset($get_data[0]['tab-id']) ? $get_data[0]['tab-id'] : "";

    
      
      echo '<div class="wpforms-panel-content-section-googlesheet-tab">';
      echo '<div class="wpforms-panel-content-section-title">';
      ?>


   
<div class="wpforms-old-settings">
  <label class="switch">
  <input type="checkbox" class="checkbox disable_old_settings" name="disable_old_settings" form-id="<?php echo $form_id; ?>" form-title="<?php echo $form_name; ?>" value="" <?php echo $check; ?>>
  <span class="slider round"></span>
</label>

    Disable Old Settings
      <span class="gs-disble-setting-message"></span>
</div>
<div class="wpforms-gs-fields">
    <!-- <input type="checkbox" class="checkbox disable_old_settings" name="disable_old_settings" form-id="<?php echo $form_id; ?>" value="" <?php echo $check; ?>>Disable Old Settings
        -->
         <br>
    <h3><?php esc_html_e('Google Sheet Settings', 'gsheetconnector-wpforms'); ?>
        <span class="gs-info-wpform">( Fetch your sheets automatically using PRO <a
                href="https://www.gsheetconnector.com/wpforms-google-sheet-connector-pro?gsheetconnector-ref=17"
                target="_blank">Upgrade to PRO</a> )</span>

                
    </h3>
 
    <p>
        <label><?php echo esc_html(__('Google Sheet Name', 'gsheetconnector-wpforms')); ?></label>
        <input type="text" name="wpform-gs[sheet-name]" id="wpforms-gs-sheet-name"
            value="<?php echo  esc_attr($saved_sheet_name); ?>" <?php echo $disable_text;?>  />
        <a href=""
            class=" gs-name help-link"><?php //echo esc_html(__('Where do i get Google Sheet Name?', 'gsheetconnector-wpforms')); ?><img
                src="<?php echo WPFORMS_GOOGLESHEET_URL; ?>assets/img/help.png" class="help-icon"><span
                class='hover-data'><?php echo esc_html(__('Go to your google account and click on"Google apps" icon and than click "Sheets". Select the name of the appropriate sheet you want to link your contact form or create new sheet.', 'gsheetconnector-wpforms')); ?>
            </span></a>
    </p>
    <p>
        <label><?php echo esc_html(__('Google Sheet Id', 'gsheetconnector-wpforms')); ?></label>
        <input type="text" name="wpform-gs[sheet-id]" id="wpforms-gs-sheet-id"
            value="<?php echo  esc_attr($saved_sheet_id) ; ?>" <?php echo $disable_text;?> />
        <a href="" class=" gs-name help-link"><img src="<?php echo WPFORMS_GOOGLESHEET_URL; ?>assets/img/help.png"
                class="help-icon"><?php //echo esc_html(__('Google Sheet Id?', 'gsheetconnector-wpforms')); ?><span
                class='hover-data'><?php echo esc_html(__('you can get sheet id from your sheet URL', 'gsheetconnector-wpforms')); ?></span></a>
    </p>
    <p>
        <label><?php echo esc_html(__('Google Sheet Tab Name', 'gsheetconnector-wpforms')); ?></label>
        <input type="text" name="wpform-gs[sheet-tab-name]" id="wpforms-sheet-tab-name"
            value="<?php echo  esc_attr($saved_tab_name) ; ?>" <?php echo $disable_text;?> />
        <a href="" class=" gs-name help-link"><img src="<?php echo WPFORMS_GOOGLESHEET_URL; ?>assets/img/help.png"
                class="help-icon"><?php //echo esc_html(__('Where do i get Sheet Tab Name?', 'gsheetconnector-wpforms')); ?><span
                class='hover-data'><?php echo esc_html(__('Open your Google Sheet with which you want to link your contact form . You will notice a tab names at bottom of the screen. Copy the tab name where you want to have an entry of contact form.', 'gsheetconnector-wpforms')); ?></span></a>
    </p>
    <p>
        <label><?php echo esc_html(__('Google Tab Id', 'gsheetconnector-wpforms')); ?></label>
        <input type="text" name="wpform-gs[tab-id]" id="wpforms-gs-tab-id"
            value="<?php echo esc_attr($saved_tab_id) ; ?>" <?php echo $disable_text;?> />
        <a href="" class=" gs-name help-link"><img src="<?php echo WPFORMS_GOOGLESHEET_URL; ?>assets/img/help.png"
                class="help-icon"><?php //echo esc_html(__('Google Tab Id?', 'gsheetconnector-wpforms')); ?><span
                class='hover-data'><?php echo esc_html(__('you can get tab id from your sheet URL', 'gsheetconnector-wpforms')); ?></span></a>
    </p>
    <?php if(((isset($saved_sheet_name)) || $saved_sheet_name!="") && ((isset($saved_tab_name)) || $saved_tab_name!="") &&  ((isset($saved_sheet_id)) || $saved_sheet_id!="") && ((isset($saved_tab_id)) || $saved_tab_id)) {
          $sheet_url = "https://docs.google.com/spreadsheets/d/".$saved_sheet_id."/edit#gid=".$saved_tab_id;
          ?>
    <p>
        <a href="<?php echo $sheet_url; ?>" target="_blank" class="cf7_gs_link_wpfrom">Google Sheet Link</a>
    </p>

    <?php } ?>

</div>

<input type="hidden" name="form-id" id="form-id" value="<?php echo $form_id; ?>">
</div>
<!-- Upgrade to PRO -->
<br />
<hr class="divide">
<div class="upgrade_pro_wpform">
    <div class="wpform_pro_demo">
        <div class="cd-faq-content" style="display: block;">
            <div class="gs-demo-fields gs-second-block">

                <h2 class="upgradetoprotitlewpform">Upgrade to WPForms Google sheet Connector PRO</h2>
                <hr class="divide">
                <p>
                    <a class="wpform_pro_link" target="_blank"
                        href="https://wpformsdemo.gsheetconnector.com"><label><?php echo esc_html( __( 'Click Here Demo', 'gsheetconnector-wpforms' ) ); ?></label></a>
                </p>
                <p>
                    <a class="wpform_pro_link"
                        href="https://docs.google.com/spreadsheets/d/1ooBdX0cgtk155ww9MmdMTw8kDavIy5J1m76VwSrcTSs/edit#gid=1289172471"
                        target="_blank"
                        rel="noopener"><label><?php echo esc_html( __( 'Sheet URL (Click Here to view Sheet with submitted data.)', 'gsheetconnector-wpforms' ) ); ?></label></a>
                </p>

                <a href="https://www.gsheetconnector.com/wpforms-google-sheet-connector-pro?gsheetconnector-ref=17"
                    target="_blank">
                    <h3>WPForms Google Sheet Connector PRO Features </h3>
                </a>
                <div class="gsh_wpform_pro_fatur_int1">
                    <ul style="list-style: square;margin-left:30px">
                        <li>Google Sheets API (Up-to date)</li>
                        <li>One Click Authentication</li>
                        <li>Click & Fetch Sheet Automated</li>
                        <li>Automated Sheet Name & Tab Name</li>
                        <li>Manually Adding Sheet Name & Tab Name</li>
                        <li>Supported WPForms Lite/Pro</li>
                        <li>Latest WordPress & PHP Support</li>
                        <li>Support WordPress Multisite</li>
                    </ul>
                </div>
                <div class="gsh_wpform_pro_img_int">
                    <img width="250" height="200" alt="wpform-GSheetConnector"
                        src="<?php echo WPFORMS_GOOGLESHEET_URL; ?>assets/img/WPForms-GSheetConnector-desktop-img.png"
                        class="">
                </div>
                <div class="gsh_wpform_pro_fatur_int2">
                    <ul style="list-style: square;margin-left:68px">
                        <li>Multiple Forms to Sheet</li>
                        <li>Roles Management</li>
                        <li>Creating New Sheet Option</li>
                        <li>Authenticated Email Display</li>
                        <li>Automatic Updates</li>
                        <li>Using Smart Tags</li>
                        <li>Custom Ordering</li>
                        <li>Image / PDF Attachment Link</li>
                        <li>Sheet Headers Settings</li>
                        <li>Click to Sync</li>
                        <li>Sheet Sorting</li>
                        <li>Excellent Priority Support</li>
                    </ul>
                </div>
                <p>
                    <a class="wpform_pro_link_buy"
                        href="https://www.gsheetconnector.com/wpforms-google-sheet-connector-pro?gsheetconnector-ref=17"
                        target="_blank"
                        rel="noopener"><label><?php echo esc_html( __( 'Buy Now', 'gsheetconnector-wpforms' ) ); ?></label></a>
                </p>
            </div>
        </div>
    </div>
</div>
<!-- Upgrade to PRO -->
<?php
   }

   /**
    * function to get all the custom posted header fields
    *
    * @since 1.0
    */
   public function execute_post_data() {
      if (isset($_POST ['wp-save-btn'])) {

         $form_id = $_POST['form-id'];

         $get_existing_data = get_post_meta($form_id, 'wpform_gs_settings');


         $gs_sheet_name = isset($_POST['wpform-gs']['sheet-name']) ? $_POST['wpform-gs']['sheet-name'] : "";
         $gs_sheet_id = isset($_POST['wpform-gs']['sheet-id']) ? $_POST['wpform-gs']['sheet-id'] : "";
         $gs_tab_name = isset($_POST['wpform-gs']['sheet-tab-name']) ? $_POST['wpform-gs']['sheet-tab-name'] : "";
         $gs_tab_id = isset($_POST['wpform-gs']['tab-id']) ? $_POST['wpform-gs']['tab-id'] : "";
         // If data exist and user want to disconnect
         if (!empty($get_existing_data) && $gs_sheet_name == "") {
            update_post_meta($form_id, 'wpform_gs_settings', "");
         }

         if (!empty($gs_sheet_name) && (!empty($gs_tab_name) )) {
            update_post_meta($form_id, 'wpform_gs_settings', $_POST['wpform-gs']);
         }
      }
   }

   /**
    * Function - fetch WPform list that is connected with google sheet
    * @since 1.0
    */
        public function get_forms_connected_to_sheet() {
      global $wpdb;
      $query = $wpdb->get_results("SELECT ID,post_title,meta_value from " . $wpdb->prefix . "posts as p JOIN " . $wpdb->prefix . "postmeta as pm on p.ID = pm.post_id where pm.meta_key='wpform_gs_settings' AND p.post_type='wpforms' ORDER BY p.ID");
      return $query;
    }   

   /**
    * function to save the setting data of google sheet
    *
    * @since 1.0
    */
   public function add_integration() {

    $wpforms_manual_setting = get_option('wpforms_manual_setting');
    $Code = "";
    $header = "";
    if (isset($_GET['code']) && ($wpforms_manual_setting == 0)) {
        if (is_string($_GET['code'])) {
            $Code = sanitize_text_field($_GET["code"]);
        }
        update_option('is_new_client_secret_wpformsgsc', 1);
        $header = esc_url_raw(admin_url('admin.php?page=wpform-google-sheet-config'));
    }

      ?>
<div class="gs-parts-wpform">
    <div class="card-wp">
        <input type="hidden" name="redirect_auth_wpforms" id="redirect_auth_wpforms"
            value="<?php echo (isset($header)) ?$header:''; ?>">
        <span class="wpforms-setting-field log-setting">
            <h2 class="title"><?php echo __('WPForms - Google Sheet Integration'); ?></h2>
            <hr>

            <?php if (empty(get_option('wpform_gs_token'))) { ?>
                <div class="wpform-gs-alert-kk" id="google-drive-msg">
                    <p class="wpform-gs-alert-heading">
                        <?php echo esc_html__('Authenticate with your Google account, follow these steps:', 'gsheetconnector-wpforms'); ?>
                    </p>
                    <ol class="wpform-gs-alert-steps">
                        <li><?php echo esc_html__('Click on the "Sign In With Google" button.', 'gsheetconnector-wpforms'); ?></li>
                        <li><?php echo esc_html__('Grant permissions for the following:', 'gsheetconnector-wpforms'); ?>
                            <ul class="wpform-gs-alert-permissions">
                                <li><?php echo esc_html__('Google Drive', 'gsheetconnector-wpforms'); ?></li>
                                <li><?php echo esc_html__('Google Sheets', 'gsheetconnector-wpforms'); ?></li>
                            </ul>
                            <p class="wpform-gs-alert-note">
                                <?php echo esc_html__('Ensure that you enable the checkbox for each of these services.', 'gsheetconnector-wpforms'); ?>
                            </p>
                        </li>
                        <li><?php echo esc_html__('This will allow the integration to access your Google Drive and Google Sheets.', 'gsheetconnector-wpforms'); ?>
                        </li>
                    </ol>
                </div>
            <?php } ?>

         
            <p>
                <label><?php echo __('Google Access Code', 'gsheetconnector-wpforms'); ?></label>

                <?php if (!empty(get_option('wpform_gs_token')) && get_option('wpform_gs_token') !== "") { ?>
                <input type="text" name="google-access-code" id="wpforms-setting-google-access-code" value="" disabled
                    placeholder="<?php echo __('Currently Active', 'gsheetconnector-wpforms'); ?>" />
                <input type="button" name="wp-deactivate-log" id="wp-deactivate-log"
                    value="<?php echo __('Deactivate', 'gsheetconnector-wpforms'); ?>" class="button button-primary" />
                <span class="tooltip"> <img src="<?php echo WPFORMS_GOOGLESHEET_URL; ?>assets/img/help.png"
                        class="help-icon"> <span
                        class="tooltiptext tooltip-right"><?php _e('On deactivation, all your data saved with authentication will be removed and you need to reauthenticate with your google account and configure sheet name and tab.', 'gsheetconnector-wpforms'); ?></span></span>
                <span class="loading-sign-deactive">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
                <?php } else { 
            $redirct_uri = admin_url( 'admin.php?page=wpform-google-sheet-config' );
         ?>

         <input type="text" name="google-access-code" id="wpforms-setting-google-access-code" value="<?php echo esc_attr($Code); ?>" disabled placeholder="<?php echo esc_html__('Click Sign in with Google', 'gsheetconnector-wpforms'); ?>" oncopy="return false;" onpaste="return false;" oncut="return false;" />

                <!-- <a href="https://accounts.google.com/o/oauth2/auth?access_type=offline&approval_prompt=force&client_id=1075324102277-drjc21uouvq2d0l7hlgv3bmm67er90mc.apps.googleusercontent.com&redirect_uri=urn:ietf:wg:oauth:2.0:oob&response_type=code&scope=https%3A%2F%2Fspreadsheets.google.com%2Ffeeds%2F+https://www.googleapis.com/auth/userinfo.email+https://www.googleapis.com/auth/drive.metadata.readonly" target="_blank" class="wpforms-btn wpforms-btn-md wpforms-btn-light-grey"><?php //echo __('Get Code', 'gsheetconnector-wpforms'); ?></a> -->
                

            <?php if (empty($Code)) { ?>  
                <a href="https://oauth.gsheetconnector.com/index.php?client_admin_url=<?php echo $redirct_uri;  ?>&plugin=wpformgsheetconnector"
                    class="button_wpformgsc">
                    <img class="wp-custom-image" img src="<?php echo WPFORMS_GOOGLESHEET_URL ?>/assets/img/btn_google_signin_dark_pressed_web.png">
                </a>
                <?php } ?>
            <?php } ?>
                <!-- set nonce -->
                <input type="hidden" name="gs-ajax-nonce" id="gs-ajax-nonce"
                    value="<?php echo wp_create_nonce('gs-ajax-nonce'); ?>" />
                <?php if (!empty($_GET['code'])) { ?>
                    <input type="submit" name="save-gs" class="wpforms-btn wpforms-btn-md wpforms-btn-orange"
                       id="save-wpform-gs-code" value="Save & Authenticate">
                <?php } ?>
                 <span class="loading-sign">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
            </p>
            <?php 
          //resolved - google sheet permission issues - START
          if (!empty(get_option('wpform_gs_verify')) && (get_option('wpform_gs_verify') == "invalid-auth")) {
         ?>
        <p style="color:#c80d0d; font-size: 14px; border: 1px solid;padding: 8px;">
            <?php echo 'Something went wrong! It looks you have not given the permission of Google Drive and Google Sheets from your google account.Please Deactivate Auth and Re-Authenticate again with the permissions.'; ?></p>
        <p style="color:#c80d0d;border: 1px solid;padding: 8px;"><img width="350px"
                    src="<?php echo WPFORMS_GOOGLESHEET_URL; ?>assets/img/permission_screen.png"></p>
            <p style="color:#c80d0d; font-size: 14px; border: 1px solid;padding: 8px;">
                <?php echo esc_html(__('Also,', 'gsheetconnector-wpforms')); ?><a href="https://myaccount.google.com/permissions"
                    target="_blank"> <?php echo esc_html(__('Click Here ', 'gsheetconnector-wpforms')); ?></a>
                <?php echo esc_html(__('and if it displays "GSheetConnector for WP Contact Forms" under Third-party apps with account access then remove it.', 'gsheetconnector-wpforms')); ?>
            </p>

     <?php 
        }
      //resolved - google sheet permission issues - END
     else{
        $wp_token = get_option('wpform_gs_token');
        if (!empty($wp_token) && $wp_token !== "") {
         $google_sheet = new wpfgsc_googlesheet();
         $email_account = $google_sheet->gsheet_print_google_account_email(); 
         if (!empty($email_account)) { ?>
            <p class="connected-account-wpform">
                <?php printf( __( 'Connected email account: %s', 'gsheetconnector-wpforms' ), $email_account ); ?>
            </p>
                <?php } else{?>
            <p style="color:red">
                <?php echo esc_html(__('Something went wrong ! Your Auth Code may be wrong or expired. Please Deactivate AUTH and Re-Authenticate again. ', 'gsheetconnector-wpforms')); ?>
            </p>
            <?php 
         } 
         }        
          }
          ?>
          <br>
          <p>
            <div id="wp-gsc-cta" class="wp-gsc-privacy-box">
                <div class="wp-gsc-table">
                    <div class="wp-gsc-less-free">
                        <p><i class="dashicons dashicons-lock"></i> We do not store any of the data from your Google account on our servers, everything is processed & stored on your server. We take your privacy extremely seriously and ensure it is never misused.</p> <a href="https://gsheetconnector.com/usage-tracking/" target="_blank" rel="noopener noreferrer">Learn more.</a>
                    </div>
                </div>
            </div>
        </p>
            <span class="wpforms-setting-field">
                <label><?php echo __('Debug Log', 'gsheetconnector-wpforms'); ?></label> 
                <button class="wpgsc-logs">View</button>
                <!-- <label><a href="<?php echo plugins_url('logs/log.txt', __FILE__); ?>" target="_blank"
                        class="wpform-debug-view"><?php echo __('View', 'gsheetconnector-wpforms'); ?></a></label> -->
                <label><a class="debug-clear-kk"><?php echo __('Clear', 'gsheetconnector-wpforms'); ?></a></label>
                <span class="clear-loading-sign">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
                <p id="gs-validation-message"></p>
               
                <span id="deactivate-message"></span>
            </span>
           
            
           
    </div>
    
</div>
<div class="wp-system-Error-logs">
   <div class="wpdisplayLogs">
         <?php
            $wpexistDebugFile = get_option('wpf_gs_debug_log_file');
            // check if debug unique log file exist or not
            if (!empty($wpexistDebugFile) && file_exists($wpexistDebugFile)) {
              $displaywpfreeLogs =  nl2br(file_get_contents($wpexistDebugFile));
            if(!empty($displaywpfreeLogs)){
             echo $displaywpfreeLogs;
            }
            else{
                echo "No errors found.";
             }
        }
       else{
            // check if debug unique log file not exist
            echo "No log file exists as no errors are generated";
        }
            
             ?>
    </div>
</div>
<div class="two-col wpform-box-help12">
    <div class="col wpform-box12">
        <header>
            <h3>Next steps…</h3>
        </header>
        <div class="wpform-box-content12">
            <ul class="wpform-list-icon12">
                <li>
                    <a href="https://www.gsheetconnector.com/wpforms-google-sheet-connector-pro" target="_blank">
                        <div>
                            <button class="icon-button">
                                <span class="dashicons dashicons-star-filled"></span>
                            </button>
                            <strong style="color: black; font-weight: bold;"><a href="https://www.gsheetconnector.com/wpforms-google-sheet-connector-pro" target="_blank">Upgrade to PRO</a></strong>
                            <p> Multiple Forms to Sheets, Custom mail tags and much more...</p>
                        </div>
                    </a>
                </li>
                <li>
                    <a href="https://www.gsheetconnector.com/wpforms-google-sheet-connector-pro" target="_blank">
                        <div>
                            <button class="icon-button">
                                <span class="dashicons dashicons-download"></span>
                            </button>
                            <strong style="color: black; font-weight: bold;">Compatibility</strong>
                            <p>Compatibility with WPForms Third-Party Plugins</p>
                        </div>
                    </a>
                </li>
                <li>
                    <a href="https://www.gsheetconnector.com/wpforms-google-sheet-connector-pro" target="_blank">
                        <div>
                            <button class="icon-button">
                                <span class="dashicons dashicons-chart-bar"></span>
                            </button>
                            <strong style="color: black; font-weight: bold;">Multi Languages </strong>
                            <p>This plugin supports multi-languages as well!</p>
                        </div>
                    </a>
                </li>
                <li>
                    <a href="https://www.gsheetconnector.com/wpforms-google-sheet-connector-pro" target="_blank">
                        <div>
                            <button class="icon-button">
                                <span class="dashicons dashicons-download"></span>
                            </button>
                            <strong style="color: black; font-weight: bold;">Support Wordpress multisites</strong>
                            <p>With the use of a Multisite, you’ll also have a new level of user-available: the Super
                                Admin.</p>
                        </div>
                    </a>
                </li>
            </ul>
        </div>
    </div>

    <!-- 2nd div -->
    <div class="col wpform-box13">
        <header>
            <h3>Product Support</h3>
        </header>
        <div class="wpform-box-content13">
            <ul class="wpform-list-icon13">
                <li>
                    <a href="https://www.gsheetconnector.com/docs/wpforms-gsheetconnector-pro" target="_blank">
                        <span class="dashicons dashicons-book"></span>
                        <div>
                            <strong>Online Documentation</strong>
                            <p>Understand all the capabilities of WPForms GsheetConnector</p>
                        </div>
                    </a>
                </li>
                <li>
                    <a href="https://www.gsheetconnector.com/support" target="_blank">
                        <span class="dashicons dashicons-sos"></span>
                        <div>
                            <strong>Ticket Support</strong>
                            <p>Direct help from our qualified support team</p>
                        </div>
                    </a>
                </li>
                <li>
                    <a href="https://www.gsheetconnector.com/affiliate-area" target="_blank">
                        <span class="dashicons dashicons-admin-links"></span>
                        <div>
                            <strong>Affiliate Program</strong>
                            <p>Earn flat 30% on every sale!</p>
                        </div>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</div>
<script>
// JavaScript/jQuery code
jQuery(document).ready(function($) {
    // Check if the account is connected
    var isAccountConnected =
        <?php echo (!empty(get_option('wpform_gs_token')) && get_option('wpform_gs_token') !== "") ? 'true' : 'false'; ?>;

    // Toggle the visibility of the alert card
    if (isAccountConnected) {
        $('.wpform-gs-alert-card').addClass('hidden');
    } else {
        $('.wpform-gs-alert-card').removeClass('hidden');
    }
});
</script>
<?php
   }

   /**
    * get form data on ajax fire inside div
    * @since 1.1
    */
   public function add_settings_page() {
      $forms = get_posts(array(
         'post_type' => 'wpforms',
         'numberposts' => -1
      ));
      ?>
<div class="wp-formSelect">
    <h3><?php echo __('Select Form', 'gsheetconnector-wpforms'); ?></h3>
</div>
<div class="wp-select">
    <select id="wpforms_select" name="wpforms">
        <option value=""><?php echo __('Select Form', 'gsheetconnector-wpforms'); ?></option>
        <?php foreach ($forms as $form) { ?>
        <option value="<?php echo $form->ID; ?>"><?php echo $form->post_title; ?></option>
        <?php } ?>
    </select>
    <!-- <span class="loading-sign-select">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span> -->
    <input type="hidden" name="wp-ajax-nonce" id="wp-ajax-nonce" value="<?php echo wp_create_nonce('wp-ajax-nonce'); ?>" />
</div>
<div class="wrap gs-form">
    <div class="wp-parts">

        <div class="card" id="wpform-gs">
            <form method="post">
                <h2 class="title"><?php echo __('WPForms - Google Sheet Settings', 'gsheetconnector-wpforms'); ?></h2>
                <hr class="divide">
                <br class="clear">
              
                <p class="deprecated-notice">
    <?php _e( 'This settings page is deprecated and moved old settings to new settings.Follow these below steps to import the old settings into the new settings.', 'gsheetconnector-wpforms' ); ?>
     </p>
      <p class="old_settings_steps">1) Select the form which you want to connect with your spreadsheet.
     </p>
        <p> 
            <img src="<?php echo WPFORMS_GOOGLESHEET_URL; ?>assets/img/faq-screenshot1.png" class="alignnone">

         </p>
      <p class="old_settings_steps">
        2) Then you can see your old settings here.
      </p>
      <p>
     <img src="<?php echo WPFORMS_GOOGLESHEET_URL; ?>assets/img/new_settings_screenshot.png" class="alignnone">

     </p>
      
                <div id="inside">

                </div>
            </form>
        </div>

    </div>
</div>
<?php
   }

   /**
    * AJAX function - deactivate activation
    * @since 1.0
    */
   public function deactivate_wpformgsc_integation() {
      // nonce check
      check_ajax_referer('gs-ajax-nonce', 'security');

      if (get_option('wpform_gs_token') != '') {

         $accesstoken = get_option( 'wpform_gs_token' );
         $client = new wpfgsc_googlesheet();
         $client->revokeToken_auto($accesstoken);
         
         delete_option('wpform_gs_token');
         delete_option('wpform_gs_access_code');
         delete_option('wpform_gs_verify');
         wp_send_json_success();
      } else {
         wp_send_json_error();
      }
   }

   /**
    * Function - To send wpform data to google spreadsheet
    * @since 1.0
    */
   public function entry_save($fields, $entry, $form_id, $form_data = '') {       
      $data = array();
      
      // Get Entry Id
      $entry_id = wpforms()->process->entry_id;
             
      // get form data
      $form_data_get = get_post_meta($form_id, 'wpform_gs_settings'); 

      $sheet_name = isset( $form_data_get[0]['sheet-name'] ) ? $form_data_get[0]['sheet-name'] : "";

      $sheet_id = isset( $form_data_get[0]['sheet-id'] ) ? $form_data_get[0]['sheet-id'] : "";

      $sheet_tab_name = isset( $form_data_get[0]['sheet-tab-name'] ) ? $form_data_get[0]['sheet-tab-name'] : "";

      $tab_id = isset( $form_data_get[0]['tab-id'] ) ? $form_data_get[0]['tab-id'] : "";
      
      $payment_type = array( "payment-single", "payment-multiple", "payment-select", "payment-total" );

      if ((!empty($sheet_name) ) && (!empty($sheet_tab_name))) {
         try {
            include_once( WPFORMS_GOOGLESHEET_ROOT . "/lib/google-sheets.php" );
            $doc = new wpfgsc_googlesheet();
            $doc->auth();
            $doc->setSpreadsheetId($sheet_id);
            $doc->setWorkTabId($tab_id);

            //$timestamp = strtotime(date("Y-m-d H:i:s"));
            // Fetched local date and time instaed of unix date and time
            $data['date'] = date_i18n(get_option('date_format'));
            $data['time'] = date_i18n(get_option('time_format'));
            
            foreach ($fields as $k => $v) {
               $get_field = $fields[$k];
               $key = $get_field['name'];
               $value = $get_field['value'];
               if( in_array( $get_field['type'], $payment_type ) ) {
                  $value =  html_entity_decode( $get_field['value'] );
               }
               $data[$key] = $value;
            }             
            $doc->add_row($data);
         } catch (Exception $e) {
            $data['ERROR_MSG'] = $e->getMessage();
            $data['TRACE_STK'] = $e->getTraceAsString();
            Wpform_gs_Connector_Utility::gs_debug_log($data);
         }
      }
   }
   
   public function display_upgrade_notice() {
      $get_notification_display_interval = get_option( 'wpforms_gs_upgrade_notice_interval' );
      $close_notification_interval = get_option( 'wpforms_gs_close_upgrade_notice' );
      
      if( $close_notification_interval === "off" ) {
         return;
      }
      
      if ( ! empty( $get_notification_display_interval ) ) {
         $adds_interval_date_object = DateTime::createFromFormat( "Y-m-d", $get_notification_display_interval );
         $notice_interval_timestamp = $adds_interval_date_object->getTimestamp();
      }
      
      if ( empty( $get_notification_display_interval ) || current_time( 'timestamp' ) > $notice_interval_timestamp ) {
         $ajax_nonce   = wp_create_nonce( "wpforms_gs_upgrade_ajax_nonce" );
         $upgrade_text = '<div class="gs-adds-notice">';
         $upgrade_text .= '<span><b>GSheetConnector WPForms </b> ';
         $upgrade_text .= 'version 2.0 would required you to <a href="'.  admin_url("admin.php?page=wpcf7-google-sheet-config") . '">reauthenticate</a> with your Google Account again due to update of Google API V3 to V4.<br/><br/>';
         $upgrade_text .= 'To avoid any loss of data redo the <a href="'.  admin_url("admin.php?page=wpform-google-sheet-config&tab=settings") . '">Google Sheet Form Settings</a> of each WPForms again with required sheet and tab details.<br/><br/>';
         $upgrade_text .= 'Also set header names again with the same name as specified for each WPForms field label.<br/><br/>';
         $upgrade_text .= 'Example: "Comment or Message" label must be added similarly for Google Sheet header.</span>';
         $upgrade_text .= '<ul class="review-rating-list">';
         $upgrade_text .= '<li><a href="javascript:void(0);" class="wpforms_gs_upgrade" title="Done">Yes, I have done.</a></li>';
         $upgrade_text .= '<li><a href="javascript:void(0);" class="wpforms_gs_upgrade_later" title="Remind me later">Remind me later.</a></li>';      
         $upgrade_text .= '</ul>';
         $upgrade_text .= '<input type="hidden" name="wpforms_gs_upgrade_ajax_nonce" id="wpforms_gs_upgrade_ajax_nonce" value="' . $ajax_nonce . '" />';
         $upgrade_text .= '</div>';

         $upgrade_block = Wpform_gs_Connector_Utility::instance()->admin_notice( array(
            'type'    => 'upgrade',
            'message' => $upgrade_text
         ) );
         echo $upgrade_block;
      }
   }
   
   public function set_upgrade_notification_interval() {
      // check nonce
      check_ajax_referer( 'wpforms_gs_upgrade_ajax_nonce', 'security' );
      $time_interval = date( 'Y-m-d', strtotime( '+10 day' ) );
      update_option( 'wpforms_gs_upgrade_notice_interval', $time_interval );
      wp_send_json_success();
   }
   
   public function close_upgrade_notification_interval() {
      // check nonce
      check_ajax_referer( 'wpforms_gs_upgrade_ajax_nonce', 'security' );
      update_option( 'wpforms_gs_close_upgrade_notice', 'off' );
      wp_send_json_success();
   }
}

$wpforms_service = new WPforms_Googlesheet_Services();