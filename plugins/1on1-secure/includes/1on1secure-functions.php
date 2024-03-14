<?php

if (!defined('ABSPATH')) exit;

if (! function_exists('OneOn1SecureActivate')) {
  function OneOn1SecureActivate() {
//    register_uninstall_hook( __FILE__, 'OneOn1SecureUninstall' ); //this does not work here, but it works when called in the main file

    //setup default settings
    add_option('APIKey1on1Secure', '');
    add_option('OnlyUSAVisitor1on1Secure', 0);
    add_option('TorUser1on1Secure', 0);
    add_option('DataAnalysis1on1Secure', 0);
    add_option('ActionForBadIPs1on1Secure', 2);
    add_option('ErrorPageForBadIPs1on1Secure', 0);
  }
}

if (! function_exists('OneOn1SecureUninstall')) {
  function OneOn1SecureUninstall() {
    delete_option('APIKey1on1Secure');
    delete_option('OnlyUSAVisitor1on1Secure');
    delete_option('toruser1on1secure');
    delete_option('DataAnalysis1on1Secure');
    delete_option('ActionForBadIPs1on1Secure');
    delete_option('ErrorPageForBadIPs1on1Secure');
  }
}

if (! function_exists('OneOn1SecureAdminSettings')) {
  function OneOn1SecureAdminSettings($links) {
    $admin_url      = admin_url('admin.php?page=1on1-secure-settings');                     //open the Settings page plugin
    $settings_link  = '<a href="'.$admin_url.'">Settings</a>';                              //add link to the plugin setting
    array_unshift($links, $settings_link);
    return $links;
  }
}

if (! function_exists('OneOn1SecureAdminSettingsPage')) {
  function OneOn1SecureAdminSettingsPage() {
    //add the plugin on the sidebar
    $oneononesecureicon = plugins_url( '../images/1on1secureicon18.png', __FILE__ );	//add the image url
    add_menu_page( '1on1 Secure Plugin', '1on1 Secure', 'manage_options', '1on1-secure-settings', 'oneon1secure_admin_settings_page_content', $oneononesecureicon, 110 );
//    add_action('admin_enqueue_scripts', 'OneOn1Secure_enqueue_styles');                 //This is now directly called by function in oneon1secure_admin_settings_page_content
  }
}

if (! function_exists('SanitizeRequestData1On1Secure')) {                     //sanitization for arrays
  function SanitizeRequestData1On1Secure($data) {
    foreach ($data as $key => $value) {
      $data[$key] = sanitize_text_field($value);
    }
    return $data;
  }
}

if (! function_exists('CaptureNinjaformsSubmissionWith1On1Secure')) {            //WPForms data capture
  function CaptureNinjaformsSubmissionWith1On1Secure($entry) {

    $data = array();
    $a = 0;

    foreach ($entry['fields'] as $key => $value) {
      $a = 1;
      $data[$key] = $value['value'];
    }

    if ($a < 1) {
      return;         //there was not data
    }

    $sanitized_request_data = SanitizeRequestData1On1Secure($data);
    CheckVisitorWith1On1Secure($sanitized_request_data);
  }
}

if (! function_exists('CaptureWpformsSubmissionWith1On1Secure')) {            //WPForms data capture
  function CaptureWpformsSubmissionWith1On1Secure($fields, $entry, $form_data) {

    if (!isset($entry['fields'])) {
      if (!is_array($entry['fields'])) {
        return;
      }
    }

    $sanitized_request_data = SanitizeRequestData1On1Secure($entry['fields']);
    CheckVisitorWith1On1Secure($sanitized_request_data);
  }
}

if (! function_exists('CaptureAllFormSubmissionsWith1On1Secure')) {           //All Forms data capture
  function CaptureAllFormSubmissionsWith1On1Secure() {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

      $ignorerequests = ['hook', 'doing_wp_cron', '_locale', 'rand^x'];

      foreach ($ignorerequests as $parameter) {
          if (isset($_REQUEST[$parameter])) {
              return; // Skip and continue
          }
      }
      
      $thispageuri = sanitize_text_field($_SERVER['REQUEST_URI']);
      if (stripos($thispageuri, 'wp-login.php') > 0) {                                //if found the wp-login.php
        return;                                                                       //dont report logins
      }

      $action = sanitize_text_field($_REQUEST['action'] ?? '');
      $ignoreactions = ['heartbeat', 'wpforms_submit', 'as_async_request_queue_runner', 'wordfence_testAjax', 'forminator_get_nonce', 'install-plugin', 'nf_optin', 'wp-remove-post-lock', 'search-install-plugins', 'delete-plugin', 'wpforms_challenge_save_option', 'get-community-events', 'twb', 'get_remaining_attempts_message'];

      if (in_array($action, $ignoreactions)) {
          return; //just skip and continue
      }

      $sanitized_request_data = SanitizeRequestData1On1Secure($_REQUEST);
      CheckVisitorWith1On1Secure($sanitized_request_data);
    }
  }
}

if (! function_exists('CheckVisitorWith1On1Secure')) {
  function CheckVisitorWith1On1Secure($sanitized_formdata='') {

    $sanitized_formdatajson = json_encode($sanitized_formdata);

    $apikey         = sanitize_key(get_option("APIKey1on1Secure"));                   //sanitize the input
    $torboolean     = absint(get_option("toruser1on1secure"));                        //sanitize the input
    $redirpageid    = absint(get_option("errorpageforbadips1on1secure"));             //sanitize the input
    $redirectoption = absint(get_option("actionforbadips1on1secure"));                //sanitize the input
    $homeurl        = sanitize_url(get_site_url());                                   //sanitize the input

    $visitingip = GetIPAddress1on1Secure();
    $thispageuri = sanitize_text_field($_SERVER['REQUEST_URI']);
    $intendedurl = sanitize_text_field($_SERVER['REQUEST_SCHEME']).'://'.sanitize_text_field($_SERVER['HTTP_HOST']).$thispageuri;

    if (stripos($thispageuri, 'admin-ajax.php') < 1) {                                //we are not in the admin-ajax.php
      if (is_admin()) { return; }                                                     //they are in the admin, don't do a security lookup
    }


    if ( (get_queried_object_id() == $redirpageid) && ($redirpageid > 0) ) { return; }
    //TODO: use this later to only restrict access to specific pages as user defined in settings


    if (strlen($sanitized_formdatajson) < 10) {   //cause the error when submit the form
      $sanitized_formdatajson = '';
    }

    if ($sanitized_formdatajson == '') {                                                  //no formdata submitted, so check for caching
      if (get_transient('1on1secure_'.$visitingip) > 0) {
        //    print "cached approval";
            return;                                                                   //previously approved and cached approval
      }
    }

    $apiurl = 'https://api.1on1secure.com/?action=checkip&apitoken='.$apikey.'&tor='.$torboolean.'&urlhit='.$intendedurl.'&domain='.$homeurl.'&ip='.$visitingip.'&content='.$sanitized_formdatajson;

    $response = wp_remote_get($apiurl, array('timeout' => 2));
    $body     = wp_remote_retrieve_body($response);
    $apianswers = json_decode($body, true);

    if ($apianswers) {
      if ($apianswers['response'] == 'fail') {
        switch ($redirectoption) {
          case 1:
            //white screen
            exit;
            break;

          case 3:
            //custom error page
            wp_redirect( get_permalink($redirpageid), 302 );
            exit;
            break;

          case 4:
            //captcha
            header ("Location: https://baiter.1on1secure.com/verification.php?target=".$visitingip."&url=".$intendedurl);
            exit;
            break;

          default:
            //default to honeypot
            header ("Location: https://baiter.1on1secure.com/contactus.php");
            exit;
            break;
        }
      } else {
          set_transient('1on1secure_'.$visitingip, 1, 3600);  //expire in 1 hour
  //        print "allow them to pass";
      }
    } else {
  //    print "no data and allowed to pass";
    }
  }
}

if (! function_exists('ListWebpages1on1secure')) {
  function ListWebpages1on1secure() {

    $pageslist = get_pages();

    foreach($pageslist as $thispage) {
      $pagearray[] = array("name" => $thispage->post_title, "id" => $thispage->ID);
    }

    return $pagearray;
  }
}

if (! function_exists('GetIPAddress1on1Secure')) {
  function GetIPAddress1on1Secure() {
    $ipHeaders = array('HTTP_CF_CONNECTING_IP','HTTP_X_FORWARDED_FOR','HTTP_X_FORWARDED','HTTP_FORWARDED_FOR','HTTP_FORWARDED','HTTP_CLIENT_IP',);

    foreach ($ipHeaders as $header) {
      $thisheader = sanitize_text_field($_SERVER[$header] ?? '');

      if (filter_var($thisheader, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4 | FILTER_FLAG_IPV6)) {
          return $thisheader;
      }
    }

    return sanitize_text_field($_SERVER['REMOTE_ADDR']);
  }
}

if (! function_exists('OneOn1Secure_custom_admin_notice')) {
  function OneOn1Secure_custom_admin_notice() {                                       //the notification or advertising
    $oneononesecurelogo = plugins_url( '../images/1on1securelogo40.png', __FILE__ );		//add the image url

?>
    <div class="notice notice-error is-dismissible" style="padding-bottom: 0; padding-top: 5px; background: linear-gradient(to top, #E67E23, #E5A56C); background-size: cover; background-repeat: no-repeat; color: #ffffff; border-left-color: orange;">
      <div id="main_content" class="notice_content" style="padding: 0;">
          <div class="content-wrapper">
          <h1 style="color: white; font-family: 'Roboto', sans-serif; font-size: 28px; font-weight: 500; display: flex; align-items: center;">
            <img src="<?php print esc_url($oneononesecurelogo); ?>" alt="1on1 Secure Logo" style="margin-right: 10px;">1on1 Secure</h1>

            <h2 style="color: white; margin: 15px 0; font-family: 'Roboto', sans-serif; font-size: 16px; font-weight: 400; line-height: 1.5;">
              Elevate your web security with <strong>1on1 Secure</strong>, the leading WordPress plugin championing cyber-defense. Benefit from our advanced security measures that provide continuous protection, safeguarding your data's integrity.
            </h2>

            <hr style="border-top: 1px solid white; margin: 10px 0;">

            <h2 style="color: white; margin: 15px 0; font-family: 'Roboto', sans-serif; font-size: 16px; font-weight: 400; line-height: 1.5;">
              Harness unparalleled encryption and fortify your WordPress site. Choose <a href="https://1on1secure.com" style="color: white; text-decoration-line: underline; text-decoration-style: solid; font-weight: bold;">1on1 Secure</a> and set the gold standard for cybersecurity.
            </h2>

          </div>
      </div>
      <button type="button" class="notice-dismiss">
          <span class="screen-reader-text">Dismiss this notice.</span>
      </button>
    </div>

<?php
  }
}
