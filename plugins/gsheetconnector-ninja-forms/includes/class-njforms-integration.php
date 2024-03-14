<?php
/*
 * service class for njform Google Sheet Connector
 * @since 1.0
 */
if (!defined('ABSPATH')) {
   exit; // Exit if accessed directly
}
/**
 * NJforms_Googlesheet_Services Class
 *
 * @since 1.0
 */
class NJforms_Googlesheet_Services {

   public function __construct() {
      // activation n deactivation ajax call
      add_action('wp_ajax_deactivate_nj_integation', array($this, 'deactivate_nj_integation'));
      
      // display for upgrade notice
      add_action( 'admin_notices', array( $this, 'display_upgrade_notice' ) );
      
      add_action( 'wp_ajax_set_upgrade_notification_interval', array( $this, 'set_upgrade_notification_interval' ) );
      add_action( 'wp_ajax_close_upgrade_notification_interval', array( $this, 'close_upgrade_notification_interval' ) );

   }

   /**
    * Function - fetch njform list that is connected with google sheet
    * @since 1.0
    */
   public function get_forms_connected_to_sheet() {
      global $wpdb;
      //$query = $wpdb->get_results("SELECT ID,post_title,meta_value from " . $wpdb->prefix . "posts as p JOIN " . $wpdb->prefix . "postmeta as pm on p.ID = pm.post_id where pm.meta_key='njforms_gs_settings' AND p.post_type='njforms' ORDER BY p.ID");
      $query = $wpdb->get_results("SELECT DISTINCT(naction.parent_id) AS ID, nform.title AS title  FROM ".$wpdb->prefix."nf3_actions AS naction JOIN ". $wpdb->prefix ."nf3_forms AS nform ON nform.id = naction.parent_id
WHERE type='google_sheet'");
      return $query;
   }

   /**
    * function to save the setting data of google sheet
    *
    * @since 1.0
    */
   public function add_integration() {
    
    $Code = "";
    $header = "";
    if (isset($_GET['code'])) {
        update_option('is_new_client_secret_njfgsc', 1);
        if (is_string($_GET['code'])) {
            $Code = sanitize_text_field($_GET["code"]);
        }
        $header = esc_url_raw(admin_url('admin.php?page=njform-google-sheet-config'));
        //  $service = new NJforms_Gsheet_Connector_Init();
        //  $service->verify_njforms_gs_integation($Code);
        //  header("Location: " . admin_url('admin.php?page=njform-google-sheet-config'));
    }
      ?>
<div class="card-wp">
    <span class="njforms-setting-field log-setting">
        <input type="hidden" name="redirect_auth_ninjaforms" id="redirect_auth_ninjaforms"
            value="<?php echo (isset($header)) ? esc_attr($header) : ''; ?>">
        <!-- Changed by ahmed 17-6-23  -->
        <span class="title1"><?php echo __('Ninja Forms - '); ?></span>
        <span class="title"><?php echo __('Google Sheet Integration'); ?></span>
        <hr>

        <?php if (empty($Code)) { ?>
            <div class="njform-gs-alert-kk" id="google-drive-msg">
                <p class="njform-gs-alert-heading">
                    <?php echo esc_html__('Authenticate with your Google account, follow these steps:', 'gsheetconnector-ninjaforms'); ?>
                </p>
                <ol class="njform-gs-alert-steps">
                    <li><?php echo esc_html__('Click on the "Sign In With Google" button.', 'gsheetconnector-ninjaforms'); ?></li>
                    <li><?php echo esc_html__('Grant permissions for the following:', 'gsheetconnector-ninjaforms'); ?>
                        <ul class="njform-gs-alert-permissions">
                            <li><?php echo esc_html__('Google Drive', 'gsheetconnector-ninjaforms'); ?></li>
                            <li><?php echo esc_html__('Google Sheets', 'gsheetconnector-ninjaforms'); ?></li>
                        </ul>
                        <p class="njform-gs-alert-note">
                            <?php echo esc_html__('Ensure that you enable the checkbox for each of these services.', 'gsheetconnector-ninjaforms'); ?>
                        </p>
                    </li>
                    <li><?php echo esc_html__('This will allow the integration to access your Google Drive and Google Sheets.', 'gsheetconnector-ninjaforms'); ?>
                    </li>
                </ol>
            </div>
        <?php } ?>

        <!-- changed end 17-6-23  -->
        <p>
            <label><?php echo __('Google Access Code', 'gsheetconnector-ninjaforms'); ?></label>

            <?php if (!empty(get_option('njforms_gs_token')) && get_option('njforms_gs_token') !== "") { ?>
                <input type="text" name="google-access-code" id="njforms-setting-google-access-code" value=""
                    disabled placeholder="<?php echo __('Currently Active', 'gsheetconnector-ninjaforms'); ?>" />
                <input type="button" name="nj-deactivate-log" id="nj-deactivate-log"
                    value="<?php echo __('Deactivate', 'gsheetconnector-ninjaforms'); ?>"
                    class="button button-primary" style ="padding:6px; background-color: #135e96;" />
                <span class="tooltip">
                    <img src="<?php echo NINJAFORMS_GOOGLESHEET_URL; ?>assets/img/help.png" class="help-icon">
                    <span class="tooltiptext tooltip-right"><?php _e('On deactivation, all your data saved with authentication will be removed and you need to reauthenticate with your google account and configure sheet name and tab.', 'gsheetconnector-ninjaforms'); ?></span>
                </span>
        <span class="loading-sign-deactive">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
            <?php } else { 
                    $redirct_uri = admin_url('admin.php?page=njform-google-sheet-config');
                    ?>
                    <input type="text" name="google-access-code" id="njforms-setting-google-access-code" value="<?php echo esc_attr($Code); ?>" readonly placeholder="<?php echo esc_html__('Click Sign In With Google Button', 'gsheetconnector-ninjaforms'); ?>" oncopy="return false;" onpaste="return false;" oncut="return false;" />

                    <?php if (empty($Code)) { ?>
                        <a href="https://oauth.gsheetconnector.com/index.php?clien_admin_url=<?php echo $redirct_uri; ?>&plugin=ninjaformsgsheetconnector" class="button_njfgsc">
                            <img class="custom-image" src="<?php echo  NINJAFORMS_GOOGLESHEET_URL ?>/assets/img/btn_google_signin_dark_pressed_web.png" alt="Connect Now">
                        </a>

                    <?php } ?>
 
            <?php } ?>
            <!-- set nonce -->
            <input type="hidden" name="gs-ajax-nonce" id="gs-ajax-nonce"
                value="<?php echo wp_create_nonce('gs-ajax-nonce'); ?>" />

            <?php if (is_user_logged_in() && !empty($Code)) { ?>
                <input type="submit" name="save-gs" class="njforms-btn njforms-btn-md njforms-btn-orange"
                    id="save-njform-gs-code" value="Save & Authenticate">

            <?php } ?>
            <span class="loading-sign">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
           </p>
            <?php 
            //resolved - google sheet permission issues - START
            if (!empty(get_option('njforms_gs_verify')) && (get_option('njforms_gs_verify') == "invalid-auth")) {
                ?>
                <p style="color:red"> 
                    <?php echo esc_html(__('Something went wrong! It looks you have not given the permission of Google Drive and Google Sheets from your google account.Please Deactivate Auth and Re-Authenticate again with the permissions.', 'gsheetconnector-ninjaforms'));
                  ?>
                      
                  </p>
                <p style="color:#c80d0d;border: 1px solid;padding: 8px;"><img width="350px"
                    src="<?php echo NINJAFORMS_GOOGLESHEET_URL; ?>assets/img/permission_screen.png"></p>
                <p style="color:#c80d0d; font-size: 14px; border: 1px solid;padding: 8px;">
                    <?php echo esc_html(__('Also,', 'gsheetconnector-ninjaforms')); ?><a href="https://myaccount.google.com/permissions"
                        target="_blank"> <?php echo esc_html(__('Click Here ', 'gsheetconnector-ninjaforms')); ?></a>
                    <?php echo esc_html(__(' and if it displays "GSheetConnector for WP Contact Forms" under Third-party apps with account access then remove it.', 'gsheetconnector-ninjaforms')); ?></p>
            <?php
            }
            //resolved - google sheet permission issues - END
            else{
                // connected-email-account
                $token = get_option('njforms_gs_token');
                if (!empty($token) && $token !== "") {
                $google_sheet = new njfgsc_googlesheet();
                $email_account = $google_sheet->gsheet_print_google_account_email();

                if ($email_account) {
                                ?>
                <p class="connected-account">
                    <?php printf(__('Connected email account: %s', 'gsheetconnector-ninjaforms'), $email_account); ?>
                </p>
               <?php } else { ?>
                <p style="color:red">
                    <?php echo esc_html(__('Something wrong! Your Auth code may be wrong or expired. Please Deactivate and Do Re-Auth Code ', 'gsheetconnector-ninjaforms')); ?>
                </p>
                <?php
                            }
                        }
                    }
                ?>
        </br>
       <div id="nj-gsc-cta" class="nj-gsc-privacy-box">
            <div class="nj-gsc-table">
                <div class="nj-gsc-less-free">
                    <p><i class="dashicons dashicons-lock"></i> We do not store any of the data from your Google account on our servers, everything is processed & stored on your server. We take your privacy extremely seriously and ensure it is never misused.</p> <a href="https://gsheetconnector.com/usage-tracking/" target="_blank" rel="noopener noreferrer">Learn more.</a>
                </div>
            </div>
        </div>
        <span class="njforms-setting-field">
            <label><?php echo __('Debug Log ->', 'gsheetconnector-ninjaforms'); ?></label>
            <button class="njgsc-logs">View</button>
            <!-- <label><a href="<?php echo plugins_url('logs/log.txt', __FILE__); ?>" target="_blank"
                    class="njform-debug-view"><?php echo __('View', 'gsheetconnector-ninjaforms'); ?></a></label> -->
            <label><a class="debug-clear-kk"><?php echo __('Clear', 'gsheetconnector-ninjaforms'); ?></a></label>
            <span class="clear-loading-sign">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
            <p id="njgs-validation-message"></p>

            <div class="nj-system-Error-logs" >
               <div class="njdisplayLogs">
                    <?php
                    $nfexistDebugFile = get_option('nfgs_debug_log_file');
                    // check if debug unique log file exist or not
                    if (!empty($nfexistDebugFile) && file_exists($nfexistDebugFile)) {
                      $displaynjfreeLogs =  nl2br(file_get_contents($nfexistDebugFile));
                    if(!empty($displaynjfreeLogs)){
                     echo $displaynjfreeLogs;
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


           
            <span id="deactivate-message"></span>
        </span>
      

        
    </p>
</div>
<script>
// JavaScript/jQuery code
document.addEventListener('DOMContentLoaded', function() {
    var googleDriveMsg = document.getElementById('google-drive-msg');
    if (googleDriveMsg) {
        // Check if the 'gfgs_token' option is not empty
        if ('<?php echo get_option('njforms_gs_token'); ?>' !== '') {
            googleDriveMsg.style.display = 'none';
        }
    }
});
</script>

<div class="two-col njgsc-box-help12">
    <div class="col njgsc-box12">
        <header>
            <h3>Next steps…</h3>
        </header>
        <div class="njgsc-box-content12">
            <ul class="njgsc-list-icon12">
                <li>
                    <a href="https://www.gsheetconnector.com/ninja-forms-google-sheet-connector-pro" target="_blank">
                        <div>
                            <button class="icon-button">
                                <span class="dashicons dashicons-star-filled"></span>
                            </button>
                            <strong style="color: black; font-weight: bold;">Upgrade to PRO</strong>
                            <p> Multiple Forms to Sheets, Merge Tags and much more...</p>
                        </div>
                    </a>
                </li>
                <li>
                    <a href="https://www.gsheetconnector.com/ninja-forms-google-sheet-connector-pro" target="_blank">
                        <div>
                            <button class="icon-button">
                                <span class="dashicons dashicons-download"></span>
                            </button>
                            <strong style="color: black; font-weight: bold;">Compatibility</strong>
                            <p>Compatibility with Ninja-Forms Third-Party Plugins</p>
                        </div>
                    </a>
                </li>
                <li>
                    <a href="https://www.gsheetconnector.com/ninja-forms-google-sheet-connector-pro" target="_blank">
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
                    <a href="https://www.gsheetconnector.com/ninja-forms-google-sheet-connector-pro" target="_blank">
                        <div>
                            <button class="icon-button">
                                <span class="dashicons dashicons-download"></span>
                            </button>
                            <strong style="color: black; font-weight: bold;">Support Wordpress multisites</strong>
                            <p>With the use of a Multisite, you’ll also have a new level of user-available: the Super Admin.</p>
                        </div>
                    </a>
                </li>
            </ul>
        </div>
    </div>

    <!-- 2nd div -->
    <div class="col njgsc-box13">
        <header>
            <h3>Product Support</h3>
        </header>
        <div class="njgsc-box-content13">
            <ul class="njgsc-list-icon13">
                <li>
                    <a href="https://www.gsheetconnector.com/docs" target="_blank">
                        <span class="dashicons dashicons-book"></span>
                        <div>
                            <strong>Online Documentation</strong>
                            <p>Understand all the capabilities of Ninja-Forms GsheetConnector</p>
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


<?php
   }

   /**
    * AJAX function - deactivate activation
    * @since 1.0
    */
   public function deactivate_nj_integation() {
      // nonce check
      check_ajax_referer('gs-ajax-nonce', 'security');

      if (get_option('njforms_gs_token') != '') {

         $accesstoken = get_option( 'njforms_gs_token' );
         $client = new njfgsc_googlesheet();
         //$client->revokeToken_auto($accesstoken);

         delete_option('njforms_gs_token');
         delete_option('njforms_gs_access_code');
         delete_option('njforms_gs_verify');
         wp_send_json_success();
      } else {
         wp_send_json_error();
      }
   }

   /**
    * Function - Display Upgrade Notice
    * @since 1.0
    */
   
   public function display_upgrade_notice() {
      $get_notification_display_interval = get_option( 'njforms_gs_upgrade_notice_interval' );
      $close_notification_interval = get_option( 'njforms_gs_close_upgrade_notice' );
      
      if( $close_notification_interval === "off" ) {
         return;
      }
      
      if ( ! empty( $get_notification_display_interval ) ) {
         $adds_interval_date_object = DateTime::createFromFormat( "Y-m-d", $get_notification_display_interval );
         $notice_interval_timestamp = $adds_interval_date_object->getTimestamp();
      }
   }
   
   public function set_upgrade_notification_interval() {
      // check nonce
      check_ajax_referer( 'njforms_gs_upgrade_ajax_nonce', 'security' );
      $time_interval = date( 'Y-m-d', strtotime( '+10 day' ) );
      update_option( 'njforms_gs_upgrade_notice_interval', $time_interval );
      wp_send_json_success();
   }
   
   public function close_upgrade_notification_interval() {
      // check nonce
      check_ajax_referer( 'njforms_gs_upgrade_ajax_nonce', 'security' );
      update_option( 'njforms_gs_close_upgrade_notice', 'off' );
      wp_send_json_success();
   }

}


$njforms_service = new NJforms_Googlesheet_Services();