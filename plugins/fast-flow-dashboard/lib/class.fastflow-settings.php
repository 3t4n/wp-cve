<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/*
 *  Class for FastFlow Settings
 */

class Fast_Flow_Settings {


    function __construct() {

    }

    public static function fast_flow_settings_page_content() {
        $settings_page_content = self::fastflow_get_all_settings_content();
        if ( empty( $settings_page_content ) ) {
            return '<p>No settings data available.</p>';
        } else {
            return $settings_page_content;
        }
    }



    public static function fast_flow_process_settings_data() {

        $check_save = empty( $_POST['fastflowsettings'] ) ? '0' : sanitize_text_field( $_POST['fastflowsettings'] );

        if ( $check_save == '1'  ) {

            //self::fast_flow_process_smtp_data();
            self::fast_flow_dashboard_setitngs();
            apply_filters('ff_settings_data', $_POST);

            die("<META HTTP-EQUIV='Refresh' Content='0; URL=admin.php?page=fast-flow-settings&msg=update' />");

        }
    }

    private static function fast_flow_dashboard_setitngs(){
      $upload_id = 0;
      $dashboard_data = self::fastflow_get_settings_db('FF_Dashboard');
      $dashboard_options = empty( $dashboard_data->settings_data ) ? array() : unserialize( $dashboard_data->settings_data );
      $dashboard_logo_id = empty( $dashboard_options['dashboard_logo'] ) ? '' : $dashboard_options['dashboard_logo'];
      global $wpdb;
      $data_arr = array();
      $data_arr['dashboard_hide_admin_bar'] = (isset($_POST['dashboard_hide_admin_bar']) && !empty($_POST['dashboard_hide_admin_bar']))?1:0;
      $data_arr['dashboard_logo'] = ($_POST['dashboard_logo'])?$_POST['dashboard_logo']:$dashboard_logo_id;

      $data_ser = serialize($data_arr);
      $count = $wpdb->get_var( "SELECT COUNT(ID) FROM {$wpdb->prefix}fastflow_settings
                                                                          WHERE settings_for='FF_Dashboard'" );

      if( $count == 1 ) {
          $wpdb->update(
              $wpdb->prefix . 'fastflow_settings',
              array( 'settings_data' => $data_ser, 'extra_data' => '' ),
              array( 'settings_for' => 'FF_Dashboard' ),
              array( '%s', '%s' ),
              array( '%s' )
            );
      } else {

          $wpdb->insert(
              $wpdb->prefix . 'fastflow_settings',
              array( 'settings_for' => 'FF_Dashboard', 'settings_data' => $data_ser, 'extra_data' => '' ),
              array( '%s', '%s', '%s' ) );
      }
    }


    private static function fast_flow_process_smtp_data() {
        global $wpdb;
        $data_arr = array();
        $data_arr['smtp_host'] = empty( $_POST['smtp_host'] ) ? '' : sanitize_text_field( $_POST['smtp_host'] );
        $data_arr['smtp_port'] = empty( $_POST['smtp_port'] ) ? '' : sanitize_text_field( $_POST['smtp_port'] );
        $data_arr['smtp_auth'] = empty( $_POST['smtp_auth']) ? 0 : 1;
        $data_arr['smtp_user'] = empty( $_POST['smtp_user'] ) ? '' : sanitize_text_field( $_POST['smtp_user'] );
        $data_arr['smtp_pass'] = empty( $_POST['smtp_pass'] ) ? '' : sanitize_text_field( $_POST['smtp_pass'] );
        $data_arr['smtp_secure'] = empty( $_POST['smtp_secure']) ? '' : sanitize_text_field( $_POST['smtp_secure'] );
        $data_ser = serialize($data_arr);
        $query_count = $wpdb->get_var( "SELECT COUNT(ID) FROM {$wpdb->prefix}fastflow_settings
                                                                            WHERE settings_for='SMTP'" );
        self::fast_flow_process_db($query_count, 'SMTP', $data_ser, '');

    }

    private static function fast_flow_process_db($count=0, $for='', $data='', $extra='') {

        global $wpdb;

        if( $count == 1 ) {
            $wpdb->update(
								$wpdb->prefix . 'fastflow_settings',
								array( 'settings_data' => $data, 'extra_data' => $extra ),
								array( 'settings_for' => $for ),
								array( '%s', '%s' ),
								array( '%s' )
							);
        } else {
            $wpdb->insert(
								$wpdb->prefix . 'fastflow_settings',
								array( 'settings_for' => $for, 'settings_data' => $data, 'extra_data' => $extra ),
								array( '%s', '%s', '%s' ) );
        }
    }


    private static function fastflow_get_settings_db($for='') {

        if( !empty( $for ) && $for !== '' ) {

            global $wpdb;

            $data = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM {$wpdb->prefix}fastflow_settings
                                            WHERE settings_for=%s", $for ) );

            if( count($data) >= 1 ) {
                return $data[0];
            } else return false;
        }
        return false;
    }

    private static function fastflow_get_all_settings_content() {
        $dashboard_data = self::fastflow_get_settings_db('FF_Dashboard');
        $dashboard_options = empty( $dashboard_data->settings_data ) ? array() : unserialize( $dashboard_data->settings_data );
        $dashboard_logo = empty( $dashboard_options['dashboard_logo'] ) ? '' : $dashboard_options['dashboard_logo'];
        $dashboard_hide_admin_bar = empty( $dashboard_options['dashboard_hide_admin_bar'] ) ? '' : $dashboard_options['dashboard_hide_admin_bar'];
        $is_checked = ($dashboard_hide_admin_bar)?'checked="checked"':'';


        $all_settings_content = '<form id="hop-form" name="hop-form" method="post" action="" enctype="multipart/form-data">
                                <input type="hidden" name="fastflowsettings" value="1" />
								<div id="accordion" style="width:50%;">';
        //$settings_items = self::get_all_settings_items();
        //$all_settings_content .= self::fastflow_get_SMTP_settings_content();
        $all_settings_content .= '<h1><strong>FastFlow</strong></h1>';
        $all_settings_content .= '<div class="item-tab-box">';
        $all_settings_content .= '<table cellspacing="10" width="100%">';
        $all_settings_content .= '<tr><td width="30%">'.__("Logo").':</td><td width="70%"><input type="hidden" class="dashboard_logo" name="dashboard_logo" value="'.$dashboard_logo.'"/>';
        $all_settings_content .= '<button class="dashboard-logo-btn" type="button"><span class="dashicons dashicons-format-image"></span></button></td></tr>';
        if($dashboard_logo){
          $image = wp_get_attachment_url($dashboard_logo);
    			$image_src = ($image)?"src='$image'":'';
        }
        if($image_src){
          $all_settings_content .= '<tr><td width="30%"></td><td width="70%"><img class="dashboard-logo-preview" '.$image_src.' width="70" height="70"/></td></tr>';
        }
        $all_settings_content .= '<tr><td width="30%">'.__("Hide admin bar").':</td><td width="70%"><input type="checkbox" id="dashboard_hide_admin_bar" name="dashboard_hide_admin_bar" value="1"  '.$is_checked.' /></td></tr>';
        $all_settings_content .= '</table>';
        $all_settings_content .= '</div>';
        $all_settings_content .= apply_filters( 'ff_settings', '');
         $all_settings_content .= '</div>
									<p class="submit"><input type="submit" class="button-primary" value="Save Settings" />
                                                        </p></form>';
         return $all_settings_content;
    }

    private static function fastflow_get_SMTP_settings_content() {
        $settings_heading = 'SMTP';
        $SMTP_db = self::fastflow_get_settings_db('SMTP');
        $smtpconf = empty( $SMTP_db->settings_data ) ? array() : unserialize( $SMTP_db->settings_data );
        $chauth = empty( $smtpconf['smtp_auth'] ) ? '' : 'checked'; $dispauth = empty( $smtpconf['smtp_auth'] ) ? 'none' : 'block';
        $chsecnone = (isset($smtpconf['smtp_secure']) && $smtpconf['smtp_secure'] == 'none') ? 'checked' : '';
        $chsecssl = (isset($smtpconf['smtp_secure']) && $smtpconf['smtp_secure'] == 'ssl') ? 'checked' : '';
        $chsectls = (isset($smtpconf['smtp_secure']) && $smtpconf['smtp_secure'] == 'tls') ? 'checked' : '';
        $smtpconf['smtp_host'] = empty( $smtpconf['smtp_host'] ) ? '' : $smtpconf['smtp_host'];
        $smtpconf['smtp_port'] = empty( $smtpconf['smtp_port'] ) ? '' : $smtpconf['smtp_port'];
        $smtpconf['smtp_user'] = empty( $smtpconf['smtp_user'] ) ? '' : $smtpconf['smtp_user'];
        $smtpconf['smtp_pass'] = empty( $smtpconf['smtp_pass'] ) ? '' : $smtpconf['smtp_pass'];
        $settings_form_html = '<table cellspacing="10"><tr><td style="width: 140px;">'.__("SMTP Host & Post").':</td>
                                                                <td><input type="text" id="smtp_host" style="width: 160px;" name="smtp_host" value="' . $smtpconf['smtp_host'] . '" maxlength="100" /> <input type="text" id="smtp_port" style="width: 30px;" name="smtp_port" value="' . $smtpconf['smtp_port'] . '" maxlength="6" /></td></tr>
                                                            <tr><td valign="top">'.__("SMTP Credentials").':</td><td><input type="checkbox" name="smtp_auth" id="smtp_auth" value="1" ' . $chauth . ' onclick=\'if (this.checked) document.getElementById("authbox").style.display="block"; else document.getElementById("authbox").style.display="none";\' /> <label for="smtp_auth">'.__("Authentication Required").'</label>
                                                                <div id="authbox" style="padding-top: 6px; display: ' . $dispauth . '"><table cellspacing="4">
                                                                        <tr><td>'.__("Username").':</td><td><input type="text" id="smtp_user" style="width: 160px;" name="smtp_user" value="' . $smtpconf['smtp_user'] . '" maxlength="80" /></td>
                                                                        <tr><td>'.__("Password").':</td><td><input type="text" id="smtp_pass" style="width: 160px;" name="smtp_pass" value="' . $smtpconf['smtp_pass'] . '" maxlength="80" /></td>
                                                            </table></div></td></tr>
                                                            <tr><td valign="top">'.__("SMTP Encryption").':</td><td><div id="securebox" style="padding-top: 6px;">
                                                                    <input type="radio" name="smtp_secure" id="smtp_secure1" value="none" ' . $chsecnone . ' /><label for="smtp_secure1">'.__("None").'</label>
                                                                    <input type="radio" name="smtp_secure" id="smtp_secure2" value="ssl" ' . $chsecssl . ' /><label for="smtp_secure2">SSL</label>
                                                                    <input type="radio" name="smtp_secure" id="smtp_secure3" value="tls" ' . $chsectls . ' /><label for="smtp_secure3">TLS</label>
                                                            </div></td></tr></table>';
        return self::get_accordion_wrap($settings_heading, $settings_form_html, 1);
    }

    private static function get_accordion_wrap($heading, $html, $pn1idx) {
        $accrdn_html = '<h1><strong>' . $heading . '</strong></h1>';
        $accrdn_html .= '<div class="item-tab-box">';
        $accrdn_html .= $html;
        $accrdn_html .= '</div>';
        return $accrdn_html;
    }

}
