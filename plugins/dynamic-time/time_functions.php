<?php
/*
Plugin Name: Dynamic Time
Description: A simple, dynamic calendar-based time solution.
Author: RLDD
Version: 5.0.14
Text Domain: dynamic-time
Author URI: http://richardlerma.com/plugins/
Copyright: (c) 2017-2024 - rldd.net - All Rights Reserved
License: GPLv3 or later
License URI: http://www.gnu.org/licenses/gpl-3.0.html
*/
global $dyt_version; $dyt_version='5.0.14';
if(!defined('ABSPATH')) exit;

//Create a constant for the dyt root path
define('DYT_DIR_PATH',plugin_dir_path( __FILE__ ));

function dyt_error() {file_put_contents(dirname(__file__).'/install_log.txt', ob_get_contents());}
if(defined('WP_DEBUG') && true===WP_DEBUG) add_action('activated_plugin','dyt_error');

function dyt_adminMenu() {
  if(current_user_can('list_users')) add_menu_page('Dynamic Time','Dynamic Time','list_users','dynamic-time','dyt_admin','dashicons-clock','3'); // Admin
  else { // Supervisor
    global $wpdb;
    $dyt_user=dyt_userid();
    $get_sup=dyt_sql("SELECT 1 FROM {$wpdb->prefix}time_user WHERE Supervisor=%d;",array($dyt_user));
    if($get_sup && (current_user_can('edit_posts')||current_user_can('moderate_comments'))) {
      if(current_user_can('edit_posts')) add_menu_page('Dynamic Time','Dynamic Time','edit_posts','dynamic-time','dyt_admin','dashicons-clock','3');
      elseif(current_user_can('moderate_comments')) add_menu_page('Dynamic Time','Dynamic Time','moderate_comments','dynamic-time','dyt_admin','dashicons-clock','3');
    }
    else add_menu_page('Dynamic Time','Dynamic Time','read','dynamic-time','dynamicTime','dashicons-clock','3');
  }
  function dyt_adminMenuCSS() { echo "<style>#adminmenu .toplevel_page_dynamic-time:not(.current):hover div.wp-menu-image:before{color:#EEE;transform:rotateY(180deg);-webkit-transition:all .5s;-moz-transition:all .5s;transition:all .5s}</style>";}
  add_action('admin_head','dyt_adminMenuCSS');
}
add_action('admin_menu','dyt_adminMenu');

function dyt_admin() {
  global $wpdb,$dyt_version;
  include_once(DYT_DIR_PATH.'time_admin.php');
  wp_enqueue_style('dyt_style',plugins_url('assets/time_min.css?v=0'.$dyt_version,__FILE__));
}
add_shortcode('dyt_admin','dyt_admin');

function dynamicTime($atts=array()) {
  if(dyt_is_path(basename(get_admin_url()).',/wp-json') && !dyt_is_path('page=dynamic-time')) return;
  global $dyt_version,$time_cal,$max_width;
  $css_url='assets/time_min.css';
  $css_fmt=filemtime(plugin_dir_path(__FILE__).$css_url);
  $css_url=plugins_url($css_url."?v={$dyt_version}_{$css_fmt}",__FILE__);
  wp_enqueue_style('dyt_style',$css_url);

  $js_url='assets/time_min.js';
  $js_fmt=filemtime(plugin_dir_path(__FILE__).$js_url);
  $js_url=plugins_url($js_url."?v={$dyt_version}_{$js_fmt}",__FILE__);
  wp_enqueue_script('dyt_script',$js_url);

  $max_width=isset($atts['max_width']) && $atts['max_width']==='true' ? 1 : 0;
  require_once(DYT_DIR_PATH.'time_cal.php');
  if(dyt_is_path('admin.php') && !dyt_is_path('dyt_view_user') && !current_user_can('list_users')) echo $time_cal;
  else return $time_cal;
}
add_shortcode('dynamicTime','dynamicTime');

function dyt_activate($update) {
  global $wpdb,$dyt_version;
  $pfx='dyt_config_';
  wp_cache_flush();
  $dyt_db_version=get_option('dyt_db_version');
  require_once(ABSPATH.basename(get_admin_url()).'/includes/upgrade.php');

  if($dyt_db_version>0) {
    if(version_compare($dyt_db_version,'3.6.0','<')) {
      $get_config=dyt_sql("SELECT * FROM {$wpdb->prefix}time_config LIMIT 1;");
      if($get_config) {
        update_option($pfx.'prompt',$get_config[0]->Prompt);
        update_option($pfx.'notes',$get_config[0]->Notes);
        update_option($pfx.'period',$get_config[0]->Period);
        update_option($pfx.'weekbegin',$get_config[0]->WeekBegin);
        update_option($pfx.'payroll',$get_config[0]->Payroll);
        update_option($pfx.'currency',$get_config[0]->Currency);
        update_option($pfx.'dropdata',$get_config[0]->DropData);
        update_option($pfx.'timeout',$get_config[0]->Timeout);
        update_option($pfx.'categoryon',$get_config[0]->CategoryOn);
        update_option($pfx.'categorylist',$get_config[0]->CategoryList);
        update_option($pfx.'custom_ot','-1');
        $wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}time_config;");
      }
    }
    if(version_compare($dyt_db_version,'3.6.5','<')) {
      $cat_list=get_option($pfx.'categorylist');
      $cat_list_pto=get_option($pfx.'categorylist');
      if(!empty($cat_list) && empty($cat_list_pto)) update_option($pfx.'categorylist_pto',$cat_list);
      $custom_ot=get_option($pfx.'custom_ot');
      if($custom_ot<0) update_option($pfx.'custom_ot',0);
    }
  }
  update_option('dyt_db_version',$dyt_version,'yes');

  $sql="
    CREATE TABLE {$wpdb->prefix}time_user
    (UserID INT NOT NULL AUTO_INCREMENT,
    WP_UserID INT,
    Period TINYINT DEFAULT 30,
    Rate DECIMAL(6,2),
    PTO DECIMAL(6,2) NOT NULL DEFAULT 0,
    Exempt TINYINT DEFAULT 0,
    Supervisor INT,
    Prompt TINYINT DEFAULT 1,
    PRIMARY KEY  (UserID));";
  dbDelta($sql);

  $sql="
    CREATE TABLE {$wpdb->prefix}time_entry
    (EntryID INT NOT NULL AUTO_INCREMENT,
    WP_UserID INT,
    Date INT,
    HmnDate DATE DEFAULT NULL,
    Hours DECIMAL(4,2),
    HourType VARCHAR(3),
    TimeIn VARCHAR(8),
    TimeOut VARCHAR(8),
    Note VARCHAR(250),
    Category VARCHAR(50),
    PRIMARY KEY  (EntryID));";
  dbDelta($sql);
  
  $sql="
    CREATE TABLE {$wpdb->prefix}time_period
    (PeriodID INT NOT NULL AUTO_INCREMENT,
    WP_UserID INT,
    Date INT,
    HmnDate DATE DEFAULT NULL,
    Rate DECIMAL(4,2),
    Reg DECIMAL(5,2),
    PTO DECIMAL(5,2),
    OT DECIMAL(5,2),
    Bonus DECIMAL(5,2),
    Note VARCHAR(250),
    Submitted DATETIME,
    Submitter INT,
    Approved DATETIME,
    Approver INT,
    Processed DATETIME,
    PRIMARY KEY  (PeriodID));";
  dbDelta($sql);

  if(function_exists('dyt_pro_ping'))dyt_pro_ping(2);
  ?><script type'text/javascript'>window.location.href='<?php echo get_admin_url(null,'admin.php?page=dynamic-time');?>&updated=1';</script><?php 
}
register_activation_hook(__FILE__,'dyt_activate');
function dyt_shh() { ?><style type='text/css'>div.error{display:none!important}</style><?php }
if(dyt_is_path(basename(get_admin_url()).'/plugins.php') && dyt_is_path('plugin=dynamic-time')) add_action('admin_head','dyt_shh'); 


function dyt_sql($sql,$vars=array()) {
  global $wpdb;
  if(count($vars)>0) $sql=$wpdb->prepare($sql,$vars);
  $result=$wpdb->get_results($sql,OBJECT);
  return $result;
}

function dyt_is_path($pages) {
  $page_array=explode(',',$pages);
  $current_page=strtolower($_SERVER['REQUEST_URI']);
  foreach($page_array as $page) {
    if(strpos($current_page,strtolower($page))!==false) return true;
  }
  return false;
}

function dyt_admin_notice() {
  if(!dyt_is_path('page=dynamic-time')){
    require_once(ABSPATH."wp-includes/pluggable.php");
    if(current_user_can('manage_options')) {
      $settings_url=get_admin_url(null,'admin.php?page=dynamic-time'); ?>
      <div class="notice notice-success is-dismissible" style='margin:0;'>
        <p><?php _e("The <em>Dynamic Time</em> plugin is active, but is not yet configured. Visit the <a href='$settings_url'>configuration page</a> to complete setup.",'Dynamic Time');?>
      </div><?php
    }
  }
}

function dyt_checkConfig() {
  global $dyt_version;
  $dyt_db_version=get_option('dyt_db_version');
  $prompt=get_option('dyt_config_prompt');
  if($dyt_version!==$dyt_db_version || version_compare($dyt_db_version,'3.7','<')) dyt_activate(1);
  else if(strlen($prompt)<1) add_action('admin_notices','dyt_admin_notice');
}
add_action('admin_init','dyt_checkConfig');

function dyt_add_action_links($links) {
  $settings_url=get_admin_url(null,'admin.php?page=dynamic-time');
  $support_url='http://richardlerma.com/plugins/';
  $links[]='<a href="'.$support_url.'">Support</a>';
  array_push($links,'<a href="'.$settings_url.'">Settings</a>');
  return $links;
}
add_filter('plugin_action_links_'.plugin_basename(__FILE__),'dyt_add_action_links');

function dyt_uninstall() {
  global $wpdb;
  $pfx='dyt_config_';
  wp_cache_flush();
  $dropdata=get_option('dyt_config_dropdata');
  if($dropdata>0) {
    $wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}time_config;");
    $wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}time_user;");
    $wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}time_entry;");
    $wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}time_period;");
    $option_name=explode(',',"dyt_hide_survey,dyt_pro,dyt_pro_version,dyt_db_version,dyt_time_format,prompt,notes,period,weekbegin,payroll,currency,dropdata,timeout,categoryon,categorylist,custom_ot,ot_min_dy,ot_min_wk,ot_multip,df_date");
    foreach($option_name as $o) {$pfx='';if(!stripos($o,'dyt_'))$pfx='dyt_config_';delete_option($pfx.$o);}
    $del_transients=dyt_sql("DELETE FROM {$wpdb->prefix}options WHERE option_name LIKE '_transient_%' AND option_name LIKE '%dyt_%'; ");
    $del_options=dyt_sql("DELETE FROM {$wpdb->prefix}options WHERE option_name LIKE 'dyt_%'; ");
  }
}
register_uninstall_hook(__FILE__,'dyt_uninstall');

function dyt_userid() {
  $userid=0;
  if(is_user_logged_in()) {
    $current_user=wp_get_current_user();
    $userid=$current_user->ID;
  }
  return $userid;
}

function dyt_user_dropdown($type,$userid) {
  global $wpdb;
  $cid=dyt_userid();
  $bid=get_current_blog_id();
  $users='';//get_transient("{$type}_{$cid}_user_dropdown");
  if(empty($users)) {
    $role_criteria=$role_join='';
    if($type=='payroll') $role_criteria="AND l.meta_value>=6";
    if($type=='user') {
      $role_join="JOIN {$wpdb->prefix}time_user tu ON tu.WP_UserID=u.ID";
      if(!current_user_can('list_users')) if($cid>0) $role_criteria.=" AND (u.ID=$cid OR tu.Supervisor=$cid)";
    }
    $user_query="
      SELECT DISTINCT u.ID userid
      ,COALESCE(
        (SELECT option_value FROM {$wpdb->prefix}options WHERE option_name=CONCAT('dyt_nm_',u.ID))
        ,(CONCAT((SELECT meta_value FROM {$wpdb->base_prefix}usermeta WHERE user_id=u.ID AND meta_key='first_name' AND LENGTH(meta_value)>0),IFNULL((SELECT CONCAT(' ',meta_value) FROM {$wpdb->base_prefix}usermeta WHERE user_id=u.ID AND meta_key='last_name' AND LENGTH(meta_value)>0),''),''))
        ,(SELECT display_name FROM {$wpdb->base_prefix}users WHERE ID=u.ID AND LENGTH(display_name)>0)
        ,(SELECT meta_value FROM {$wpdb->base_prefix}usermeta WHERE user_id=u.ID AND meta_key='nickname' AND LENGTH(meta_value)>0)
        ,'[wp user deleted]'
      ) as name
      FROM (
        SELECT u.*
        FROM {$wpdb->base_prefix}users u
        JOIN {$wpdb->base_prefix}usermeta l ON l.user_id=u.ID AND l.meta_key LIKE '%user_level'
        LEFT JOIN {$wpdb->base_prefix}usermeta b ON b.user_id=u.ID AND b.meta_key='primary_blog'
        LEFT JOIN {$wpdb->base_prefix}usermeta t ON t.user_id=u.ID AND t.meta_key='session_tokens'
        $role_join
        WHERE (IFNULL(b.meta_value,'$bid')='$bid' OR l.meta_value>9)
        $role_criteria
        ORDER BY CASE WHEN u.ID='$userid' THEN 9 else SUBSTRING(t.meta_value,NULLIF(LOCATE('expiration',t.meta_value),0)+14,10) END DESC
        LIMIT 1000
      )u
      ORDER BY name;
    ";
    //echo $user_query;
    $users=dyt_sql($user_query);
    set_transient("{$type}_{$cid}_user_dropdown",$users,180);
  }
  $options='';
  if($users) foreach($users as $u) {
    if($u->userid==$userid) $selected='selected'; else $selected='';
    $options.="<option value='{$u->userid}' $selected>".ucwords(preg_replace("/[^\p{L} ]/u",'',$u->name));
  } else $options="<option value='0' disabled>No Eligible Users</option>";
  return $options;
}

/* User Defined Email Subject & Content
function dyt_custom_mail_subject($user_name) {return $user_name." - Pay Period Submission";}
function dyt_custom_mail_content($recipient_name,$user_name,$url) {return "Dear $recipient_name<br><br>&nbsp;Please find a new pay period submission for $user_name at <a href='$url' target='_blank'>$url</a><br><br>- Dynamic Time.";}
*/

// Email Functions
function dyt_html_mail() {return 'text/html';}
function dyt_mail_from($email='') {return get_bloginfo('admin_email');}
function dyt_mail_name($name='') {return get_bloginfo('name');}
function dyt_email($target_id,$target_name,$target_type,$user_id,$username) {
  $url=get_site_url();
  $username=ucwords($username);
  $target_name=ucwords($target_name);
  if(dyt_is_path('page=dynamic-time')) $url=get_admin_url(null,'admin.php?page=dynamic-time'); else $url.=$_SERVER['REQUEST_URI']."?x=0";
  $url.="&sup=$target_id&dyt_user=$user_id";
  if($target_type=='supervisor') {
    $sessions=get_user_meta($user_id,'session_tokens',true);
    if(!empty($sessions)) {
      $session=array_column($sessions,'expiration');
      $url.='&dyt_key='.$session[0];
    }
  }
  $target=get_userdata($target_id);
  $email=$target->user_email;
  if(strpos($email,'@')!==false) { // check for valid email
    require_once(ABSPATH.WPINC.'/pluggable.php');
    if(function_exists('dyt_custom_mail_subject')) $subject=dyt_custom_mail_subject($username); else $subject=$username." - Pay Period Submission";
    if(function_exists('dyt_custom_mail_content')) $message=dyt_custom_mail_content($target_name,$username,$url); else $message="Dear $target_name<br><br>&nbsp;Please find a new pay period submission for $username at <a href='$url' target='_blank'>$url</a><br><br>- ".dyt_mail_name();
    add_filter('wp_mail_content_type','dyt_html_mail');
    add_filter('wp_mail_from','dyt_mail_from');
    add_filter('wp_mail_from_name','dyt_mail_name');
    $sent=wp_mail($email,$subject,$message);
    remove_filter('wp_mail_content_type','dyt_html_mail');
  }
}

function dyt_pto($pto_tot,$pto_tkn,$wrk_tot,$wrk_end) {
  if($pto_tot>0 && $wrk_end>0 && function_exists('dyt_pro_ping')) {
    $pto_accrue=get_option('dyt_config_pto_accrue');
    if($pto_accrue>=0 || empty($pto_accrue)) {
      if($wrk_tot<$wrk_end) $pto_acr=number_format($pto_tot*($wrk_tot/$wrk_end),2); // emp start after 1/1
      else $pto_acr=number_format($pto_tot*($wrk_end/365),2); // emp start before 1/1
    } else $pto_acr=$pto_tot;
    $pto_rem=$pto_acr-$pto_tkn;
    $pto_acr_dy=number_format($pto_acr/8,1);
    $pto_tkn_dy=number_format($pto_tkn/8,1);
    $pto_rem_dy=number_format($pto_rem/8,1);
    
    return "
    <table id='pto_rate' style='display:inline-block;text-transform:none;margin:.5em .5em 0 0;padding:1em;color:#888;background:#fff;border:1px solid #fff;border-radius:2px'>
      <tr><td style='color:var(--dyt_clr);text-align:center' colspan='3'><b>".date('Y')." PTO</b></td></tr>
      <tr><td></td><th style='vertical-align:sub'>Hours</th><th title='PTO days are considered 8 hours. Accruals are prorated from date of first time entry this year, thru date of last time entry this year.'>Days<span class='dashicons dashicons-info-outline' style='font-size:1.2em;vertical-align:sub'></span></th></tr>
      <tr class='pto_rate'><td>Accrued</td><td>$pto_acr</td><td>$pto_acr_dy</td></tr>
      <tr class='pto_rate'><td>Taken</td><td>$pto_tkn</td><td>$pto_tkn_dy</td></tr>
      <tr><td>Remaining</td><td>$pto_rem</td><td>$pto_rem_dy</td></tr>
      <tr class='pto_desc'><td colspan='3' style='color:#aaa;padding:1em'>*PTO days are considered 8 hours. Accruals are prorated from date of first time entry this year thru date of last time entry this year.</td></tr>
    </table>
    <style>
      #pto_rate tr th{color:#ccc;padding:0;text-align:center}
      #pto_rate tr td,#dyt_sum #pto_rate tr td{text-align:right;padding:.5em 1em}
      #dyt_sum #pto_rate tr td{font-size:1.2em}
      #pto_rate tr.pto_rate td{border-bottom:1px solid #eee}
      @media(min-width:568px){.pto_desc{display:none}}
    </style>";
  }
}

function dyt_save_sig($wp_userid,$dateval,$sigimg,$type) {
  $path=$_SERVER['DOCUMENT_ROOT'].'/wp-content/uploads/time_sig/';
  if(!is_dir($path)) mkdir($path,0755,true);
  if($sigimg=='delete') {unlink("$path{$wp_userid}_{$dateval}_{$type}.png"); return;}
  $sigimg=str_replace('data:image/png;base64,','',$sigimg);
  file_put_contents("$path{$wp_userid}_{$dateval}_{$type}.png",base64_decode($sigimg));
}