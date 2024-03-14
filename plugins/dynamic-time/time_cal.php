<?php

if(!defined('ABSPATH')) exit;
if(!is_user_logged_in()) {?><script type='text/javascript'>window.location="<?php echo wp_login_url().'?redirect_to='.urlencode($_SERVER['REQUEST_URI']);?>"</script><?php return; }
if(!current_user_can('read')) return;
if(!empty($_GET['dyt_user'])) $dyt_user=intval($_GET['dyt_user']);

// Sync Configuration
global $wpdb,$wp_version,$dyt_version,$max_width;
$dyt_pro_version=get_option('dyt_pro_version');
$dyt_db_version=get_option('dyt_db_version');
if($dyt_version!==$dyt_db_version || version_compare($dyt_db_version,'3.6.8','<')) dyt_activate(1); // Run dbDelta upgrade

$admin_view=$db_sup=0;
$wp_userid=dyt_userid(); // Default to Self
$action_user=dyt_userid();

if(!empty($dyt_user)) {
  if(current_user_can('list_users')) {$wp_userid=$dyt_user; $admin_view=1;} // Admin
  else { // Supervisor
    $get_sup=dyt_sql("SELECT Supervisor FROM {$wpdb->prefix}time_user WHERE WP_UserID=%d;",array($dyt_user));
    if($get_sup) foreach($get_sup as $row):$db_sup=$row->Supervisor;endforeach;
    if($wp_userid==$db_sup && $db_sup!=$dyt_user) { 
      $wp_userid=$dyt_user;
      $admin_view=1;
    }
    if($admin_view<1 && $dyt_user>0 && $dyt_user!=$wp_userid) {echo "<div class='dyt_control' style='background:#fff;margin:2em;padding:1em 2em'><h2>You need a higher level of permission.</h2><p>Sorry, you are not allowed to access this user.</p></div>"; return;}
  }
}

if($wp_userid>0) {
  $action=$pto=$ot='';
  // Pay Period Meta
  if(!empty($_POST['action']))$action=sanitize_text_field($_POST['action']);
  if(!empty($_POST['Reg']))   $reg=filter_var($_POST['Reg'],FILTER_SANITIZE_NUMBER_FLOAT,FILTER_FLAG_ALLOW_FRACTION); else $reg='';
  if(!empty($_POST['PTO']))   $pto=filter_var($_POST['PTO'],FILTER_SANITIZE_NUMBER_FLOAT,FILTER_FLAG_ALLOW_FRACTION); else $pto='';
  if(!empty($_POST['OT']))    $ot=filter_var($_POST['OT'],FILTER_SANITIZE_NUMBER_FLOAT,FILTER_FLAG_ALLOW_FRACTION); else $ot='';
  if(!empty($_POST['Bonus'])) $bonus=filter_var($_POST['Bonus'],FILTER_SANITIZE_NUMBER_FLOAT,FILTER_FLAG_ALLOW_FRACTION); else $bonus='';
  if(!empty($_POST['sigImg']))$sigimg=sanitize_text_field($_POST['sigImg']);
  if(!empty($_POST['period_note'])) $period_note=sanitize_text_field($_POST['period_note']); else $period_note='';

  // Time Entry Meta
  if(!empty($_POST['date']))    $date=array_map('ABSINT',$_POST['date']);
  if(!empty($_POST['hmn_date']))$hmn_date=array_map('sanitize_text_field',$_POST['hmn_date']);
  if(!empty($_POST['hours']))   $hours=array_map('sanitize_text_field',$_POST['hours']);
  if(!empty($_POST['hourtype']))$hourtype=array_map('sanitize_text_field',$_POST['hourtype']);
  if(!empty($_POST['time_in'])) $time_in=array_map('sanitize_text_field',$_POST['time_in']);
  if(!empty($_POST['time_out']))$time_out=array_map('sanitize_text_field',$_POST['time_out']);
  if(!empty($_POST['note']))    $note=array_map('sanitize_text_field',$_POST['note']);
  if(!empty($_POST['category']))$cat=array_map('sanitize_text_field',$_POST['category']);
  $input_saved=0;

  // Configuration
  $config=dyt_sql("
    SELECT WP_UserID
    ,COALESCE(
      (SELECT option_value FROM {$wpdb->prefix}options WHERE option_name=CONCAT('dyt_nm_',$wp_userid))
      ,(CONCAT((SELECT meta_value FROM {$wpdb->base_prefix}usermeta WHERE user_id=$wp_userid AND meta_key='first_name' AND LENGTH(meta_value)>0),IFNULL((SELECT CONCAT(' ',meta_value) FROM {$wpdb->base_prefix}usermeta WHERE user_id=$wp_userid AND meta_key='last_name' AND LENGTH(meta_value)>0),''),''))
      ,(SELECT display_name FROM {$wpdb->base_prefix}users WHERE ID=$wp_userid AND LENGTH(display_name)>0)
      ,(SELECT meta_value FROM {$wpdb->base_prefix}usermeta WHERE user_id=$wp_userid AND meta_key='nickname' AND LENGTH(meta_value)>0)
      ,'[wp user deleted]'
    ) Uname
    ,COALESCE(
      (SELECT option_value FROM {$wpdb->prefix}options WHERE option_name=CONCAT('dyt_nm_',Supervisor))
      ,(CONCAT((SELECT meta_value FROM {$wpdb->base_prefix}usermeta WHERE user_id=Supervisor AND meta_key='first_name' AND LENGTH(meta_value)>0),IFNULL((SELECT CONCAT(' ',meta_value) FROM {$wpdb->base_prefix}usermeta WHERE user_id=Supervisor AND meta_key='last_name' AND LENGTH(meta_value)>0),''),''))
      ,(SELECT display_name FROM {$wpdb->base_prefix}users WHERE ID=Supervisor AND LENGTH(display_name)>0)
      ,(SELECT meta_value FROM {$wpdb->base_prefix}usermeta WHERE user_id=Supervisor AND meta_key='nickname' AND LENGTH(meta_value)>0)
      ,'[wp user deleted]'
    ) Sname
    ,COALESCE(
      (SELECT option_value FROM {$wpdb->prefix}options WHERE option_name=CONCAT('dyt_nm_',c.option_value))
      ,(CONCAT((SELECT meta_value FROM {$wpdb->base_prefix}usermeta WHERE user_id=c.option_value AND meta_key='first_name' AND LENGTH(meta_value)>0),IFNULL((SELECT CONCAT(' ',meta_value) FROM {$wpdb->base_prefix}usermeta WHERE user_id=c.option_value AND meta_key='last_name' AND LENGTH(meta_value)>0),''),''))
      ,(SELECT display_name FROM {$wpdb->base_prefix}users WHERE ID=c.option_value AND LENGTH(display_name)>0)
      ,(SELECT meta_value FROM {$wpdb->base_prefix}usermeta WHERE user_id=c.option_value AND meta_key='nickname' AND LENGTH(meta_value)>0)
      ,'[wp user deleted]'
    ) Pname
    ,COALESCE(
      (SELECT option_value FROM {$wpdb->prefix}options WHERE option_name=CONCAT('dyt_nm_',$action_user))
      ,(CONCAT((SELECT meta_value FROM {$wpdb->base_prefix}usermeta WHERE user_id=$action_user AND meta_key='first_name' AND LENGTH(meta_value)>0),IFNULL((SELECT CONCAT(' ',meta_value) FROM {$wpdb->base_prefix}usermeta WHERE user_id=$action_user AND meta_key='last_name' AND LENGTH(meta_value)>0),''),''))
      ,(SELECT display_name FROM {$wpdb->base_prefix}users WHERE ID=$action_user AND LENGTH(display_name)>0)
      ,(SELECT meta_value FROM {$wpdb->base_prefix}usermeta WHERE user_id=$action_user AND meta_key='nickname' AND LENGTH(meta_value)>0)
      ,'[wp user deleted]'
    ) Cname
    ,CASE WHEN pd.option_value<0 THEN COALESCE(u.Period,(SELECT Period FROM {$wpdb->prefix}time_user WHERE Period IS NOT NULL ORDER BY UserID DESC LIMIT 1),30) ELSE pd.option_value END cfg_Period
    ,CASE WHEN pr.option_value<0 THEN u.Prompt ELSE pr.option_value END cfg_Prompt
    ,c.option_value Payroll
    ,u.*
    ,IFNULL((SELECT SUM(Hours) FROM {$wpdb->prefix}time_entry WHERE WP_UserID=u.WP_UserID AND HourType='PTO' AND HmnDate>=DATE_FORMAT(NOW(),'%Y-01-01')),0)pto_tkn
    ,IFNULL((SELECT DATEDIFF(NOW(),HmnDate) FROM {$wpdb->prefix}time_entry WHERE WP_UserID=u.WP_UserID ORDER BY HmnDate ASC LIMIT 1),0)wrk_tot
    ,IFNULL((SELECT DATEDIFF(HmnDate,DATE_FORMAT(NOW(),'%Y-01-01')) FROM {$wpdb->prefix}time_entry WHERE WP_UserID=u.WP_UserID ORDER BY HmnDate DESC LIMIT 1),0)wrk_end
    FROM {$wpdb->prefix}options c
    JOIN {$wpdb->prefix}options pd ON pd.option_name='dyt_config_period'
    JOIN {$wpdb->prefix}options pr ON pr.option_name='dyt_config_prompt'
    LEFT JOIN {$wpdb->prefix}time_user u ON u.WP_UserID='$wp_userid'
    WHERE c.option_name='dyt_config_payroll'
    LIMIT 1;
  ");
  
  $pfx='dyt_config_';
  if($config): 
    $notes=get_option($pfx.'notes');
    $weekbegin=get_option($pfx.'weekbegin');
    $currency=get_option($pfx.'currency');
    $sig_req=get_option($pfx.'sigreq',-1);
    $cat_list=get_option($pfx.'categorylist');
    $cat_list_pto=get_option($pfx.'categorylist_pto');
    $df_hr=get_option($pfx.'df_hr');
    $df_in=get_option($pfx.'df_in');
    $df_out=get_option($pfx.'df_out');
    $predict=get_option($pfx.'predict',1);
    $df_date=get_option($pfx.'df_date');
    $df_hmn_date=get_option($pfx.'df_hmn_date');
    $cal_lk=get_option($pfx.'cal_lk');
    
    if(strlen($cat_list.$cat_list_pto)>0 && get_option('dyt_pro_version')>0) $cat_on=get_option($pfx.'categoryon');
    else {
      $cat_on=0;
      $cat_list=$cat_list_pto='';
    }
    
    foreach($config as $row):
      $user=preg_replace("/[^\p{L} ]/u",'',$row->Uname);
      $rate=$row->Rate;
      $exempt=$row->Exempt;
      $prompt=$row->cfg_Prompt; if(strlen($prompt)<1) $prompt=1;
      $period=$row->cfg_Period; if(empty($period)) $period=30;
      $pid=$row->Payroll;
      $sid=$row->Supervisor;
      $sname=preg_replace("/[^\p{L} ]/u",'',$row->Sname);
      $pname=preg_replace("/[^\p{L} ]/u",'',$row->Pname);
      $cname=preg_replace("/[^\p{L} ]/u",'',$row->Cname);
      $pto_tot=$row->PTO;
      $pto_tkn=$row->pto_tkn;
      $wrk_tot=$row->wrk_tot;
      $wrk_end=$row->wrk_end;

      if($exempt==-1) { // CA
        $ot_min_dy=8;
        $ot_min_wk=40;
        $ot_multip=.5;
      }
      if($exempt<-1) {
        $ot_min_dy=get_option($pfx.'ot_min_dy');
        $ot_min_wk=get_option($pfx.'ot_min_wk');
        $ot_multip=get_option($pfx.'ot_multip');
        if($ot_multip>1) $ot_multip=$ot_multip-1;
      }
    endforeach;

    else: $input_saved='-3';
  endif;


  if(!empty($_POST['dyt_save_time']) && (dyt_is_path('/time/?demo=3') || check_admin_referer('save_time','dyt_save_time'))) {
    $timestamp=current_time('mysql');
    $hmn_dateval='';

    if(!empty($_POST['df_date']) && current_user_can('list_users')) { // Cache period
      $post_df_date=intval($_POST['df_date']);
      $post_df_hmn_date=sanitize_text_field($_POST['df_hmn_date']);
      $db_df_date=get_option($pfx.'df_date');
      if($post_df_date>0 && $post_df_date!==$db_df_date) {
        update_option($pfx.'df_date',$post_df_date);
        update_option($pfx.'df_hmn_date',$post_df_hmn_date);
        $df_date=$post_df_date;
        $df_hmn_date=$post_df_hmn_date;
        set_transient("dyt_period_set",$df_date,0);
      }
    }
    
    // Insert User If Not Exists
    $get_login=dyt_sql("SELECT WP_UserID FROM {$wpdb->prefix}time_user WHERE WP_UserID=%d LIMIT 1; ",array($wp_userid));
    if(!$get_login) {
      $get_last_exempt=dyt_sql("SELECT Exempt FROM {$wpdb->prefix}time_user ORDER BY UserID DESC LIMIT 1;");
      if(empty($get_last_exempt)) $last_exempt=0; else $last_exempt=$get_last_exempt[0]->Exempt;
      $insert_config=dyt_sql("INSERT INTO {$wpdb->prefix}time_user (WP_UserID,Period,Rate,Exempt,Supervisor) VALUES(%s,%s,NULL,%s,NULL);",array($wp_userid,$period,$last_exempt));
      if($dyt_pro_version>0) { $pto_default=get_option($pfx.'pto_default'); if($pto_default>0) $update_config=dyt_sql("UPDATE {$wpdb->prefix}time_user SET PTO=%s WHERE WP_UserID=%d;",array($pto_default,$wp_userid));}
    }

    if($action!='reset' && $action!='unsubmit') {
      if(count(array_filter($hours))>0) { // If Hours Array is not empty, Delete Matching Entries before Insert
        $wpdb->query("SET SQL_SAFE_UPDATES=0;");
        foreach($date as $index=>$dateval) $delete_entry=dyt_sql("DELETE FROM {$wpdb->prefix}time_entry WHERE WP_UserID=%d AND HmnDate=%s; ",array($wp_userid,$hmn_date[$index]));
      }

      foreach($date as $index=>$dateval) { // Insert Entries
        reset($date);
        if($hours[$index]>0) {
          $insert_entry=dyt_sql("INSERT INTO {$wpdb->prefix}time_entry (WP_UserID,Date,HmnDate,Hours,HourType,TimeIn,TimeOut,Category,Note)VALUES(%d,%s,%s,%s,%s,%s,%s,%s,%s); ",array($wp_userid,$dateval,$hmn_date[$index],$hours[$index],$hourtype[$index],$time_in[$index],$time_out[$index],$cat[$index],$note[$index]));
        }
        $hmn_dateval=$hmn_date[$index];
      }
    }

    if($action=='reset' || $action=='unsubmit') {
      end($date); foreach($date as $index=>$dateval) $hmn_dateval=$hmn_date[$index]; //last row
      
      if($action=='reset') foreach($date as $index=>$dateval) $reverse_period=dyt_sql("DELETE FROM {$wpdb->prefix}time_period WHERE WP_UserID=%d AND (Date=%d OR HmnDate=%s); ",array($wp_userid,$dateval,$hmn_date[$index]));
      elseif($action=='unsubmit') {
        $unsubmit_period=dyt_sql("
          UPDATE {$wpdb->prefix}time_period
          SET Submitted=NULL,Submitter=NULL,Approved=NULL,Approver=NULL,Processed=NULL
          WHERE WP_UserID=%d AND (Date=%d OR HmnDate=%s)",array($wp_userid,$dateval,$hmn_dateval));
      }
      delete_option("dyt_subm_{$wp_userid}_$hmn_dateval");
      delete_option("dyt_appr_{$wp_userid}_$hmn_dateval");
      dyt_save_sig($wp_userid,$hmn_dateval,'delete','sb');
      dyt_save_sig($wp_userid,$hmn_dateval,'delete','ap');
    } else {

      // Update Period Totals
      $update_period=dyt_sql("UPDATE {$wpdb->prefix}time_period p 
        JOIN {$wpdb->prefix}time_user u ON u.WP_UserID=p.WP_UserID
        SET p.HmnDate=%s,p.Rate=u.Rate,Reg=%s,p.PTO=%s,OT=%s,Bonus=%s,Note=%s 
        WHERE p.WP_UserID=%d AND (Date=%d OR HmnDate=%s);",array($hmn_dateval,$reg,$pto,$ot,$bonus,$period_note,$wp_userid,$dateval,$hmn_dateval));

      $insert_period=dyt_sql("INSERT INTO {$wpdb->prefix}time_period (WP_UserID,Date,HmnDate,Rate,Reg,PTO,OT,Bonus,Note)
        SELECT %d,%d,%s,Rate,%s,%s,%s,%s,%s
        FROM {$wpdb->prefix}time_user
        WHERE WP_UserID=%d AND NOT EXISTS (SELECT PeriodID FROM {$wpdb->prefix}time_period WHERE WP_UserID=%d AND (Date=%d OR HmnDate=%s))",array($wp_userid,$dateval,$hmn_dateval,$reg,$pto,$ot,$bonus,$period_note,$wp_userid,$wp_userid,$dateval,$hmn_dateval));
    }

    if($action=='send') { // Submit Pay Period
      $submit_period=dyt_sql("UPDATE {$wpdb->prefix}time_period p 
        JOIN {$wpdb->prefix}time_user u ON u.WP_UserID=p.WP_UserID SET p.Rate=u.Rate, Submitter=%d,Submitted=%s,Reg=%s,p.PTO=%s,OT=%s,Bonus=%s,Note=%s 
        WHERE p.WP_UserID=%d AND (Date=%d OR HmnDate=%s)",array($action_user,$timestamp,$reg,$pto,$ot,$bonus,$period_note,$wp_userid,$dateval,$hmn_dateval));

      if(isset($_SERVER['REMOTE_ADDR'])) update_option("dyt_subm_{$wp_userid}_$hmn_dateval",sanitize_text_field($_SERVER['REMOTE_ADDR']));
      if(!empty($sigimg)) dyt_save_sig($wp_userid,$hmn_dateval,$sigimg,'sb');
      if($sid>0) dyt_email($sid,$sname,'supervisor',$wp_userid,$user);
      elseif($pid>0) dyt_email($pid,$pname,'payroll',$wp_userid,$user);
    }

    if($action=='approve') { // Approve Submission
      $approve_period=dyt_sql("UPDATE {$wpdb->prefix}time_period p 
        JOIN {$wpdb->prefix}time_user u ON u.WP_UserID=p.WP_UserID SET p.Rate=u.Rate, Approver=%d,Approved=%s,Reg=%s,p.PTO=%s,OT=%s,Bonus=%s,Note=%s 
        WHERE p.WP_UserID=%d AND (Date=%d OR HmnDate=%s)",array($action_user,$timestamp,$reg,$pto,$ot,$bonus,$period_note,$wp_userid,$dateval,$hmn_dateval));

      
      if(isset($_SERVER['REMOTE_ADDR'])) update_option("dyt_appr_{$wp_userid}_$dateval",sanitize_text_field($_SERVER['REMOTE_ADDR']));
      if(!empty($sigimg)) dyt_save_sig($wp_userid,$hmn_dateval,$sigimg,'ap');
      if($pid>0 && $sid>0) dyt_email($pid,$pname,'payroll',$wp_userid,$user);
    }

    if($action=='process') // Process Submission
      $process_period=dyt_sql("UPDATE {$wpdb->prefix}time_period SET Processed=%s WHERE WP_UserID=%d AND (Date=%d OR HmnDate=%s);",array($timestamp,$wp_userid,$dateval,$hmn_dateval));

    $input_saved++;
  }
  
  // Pay Period Meta
  $periods=$wpdb->get_results("
    SELECT WP_UserID,Date,IFNULL(HmnDate,'')HmnDate,Rate,Bonus,Note
    ,DATE_FORMAT(Submitted,'%b %D %h:%i%p') Submitted
    ,IFNULL((SELECT option_value FROM {$wpdb->prefix}options WHERE option_name=CONCAT('dyt_subm_',WP_UserID,'_',HmnDate) LIMIT 1),(SELECT option_value FROM {$wpdb->prefix}options WHERE option_name=CONCAT('dyt_subm_',WP_UserID,'_',Date) LIMIT 1)) Submitted_IP
    ,DATE_FORMAT(Approved,'%b %D %h:%i%p') Approved
    ,IFNULL((SELECT option_value FROM {$wpdb->prefix}options WHERE option_name=CONCAT('dyt_appr_',WP_UserID,'_',HmnDate) LIMIT 1),(SELECT option_value FROM {$wpdb->prefix}options WHERE option_name=CONCAT('dyt_appr_',WP_UserID,'_',Date) LIMIT 1)) Approved_IP
    ,DATE_FORMAT(Processed,'%b %D %h:%i%p') Processed
    ,CASE WHEN Submitter>0 THEN COALESCE(
      (SELECT option_value FROM {$wpdb->prefix}options WHERE option_name=CONCAT('dyt_nm_',Submitter))
      ,CONCAT((SELECT meta_value FROM {$wpdb->base_prefix}usermeta WHERE user_id=Submitter AND meta_key='first_name' AND LENGTH(meta_value)>0)
      ,(SELECT CONCAT(' ',meta_value) FROM {$wpdb->base_prefix}usermeta WHERE user_id=Submitter AND meta_key='last_name' AND LENGTH(meta_value)>0))
      ,(SELECT meta_value FROM {$wpdb->base_prefix}usermeta WHERE user_id=Submitter AND meta_key='nickname' AND LENGTH(meta_value)>0)
      ,(SELECT display_name FROM {$wpdb->base_prefix}users WHERE ID=Submitter)
    ) ELSE '' END Submitter
    ,CASE WHEN Approver>0 THEN COALESCE(
      (SELECT option_value FROM {$wpdb->prefix}options WHERE option_name=CONCAT('dyt_nm_',Approver))
      ,CONCAT((SELECT meta_value FROM {$wpdb->base_prefix}usermeta WHERE user_id=Approver AND meta_key='first_name' AND LENGTH(meta_value)>0)
      ,(SELECT CONCAT(' ',meta_value) FROM {$wpdb->base_prefix}usermeta WHERE user_id=Approver AND meta_key='last_name' AND LENGTH(meta_value)>0))
      ,(SELECT meta_value FROM {$wpdb->base_prefix}usermeta WHERE user_id=Approver AND meta_key='nickname' AND LENGTH(meta_value)>0)
      ,(SELECT display_name FROM {$wpdb->base_prefix}users WHERE ID=Approver)
      ,'[wp user deleted]'
    ) ELSE '' END Approver
    FROM {$wpdb->prefix}time_period
    WHERE WP_UserID='$wp_userid';
  ");
  
  $period_end='';
  $period_end_hmn='';
  $period_rate='';
  $period_bonus='';
  $period_note='';
  $submitted='';
  $submitted_ip='';
  $submitter='';
  $submitter_sig='';
  $approved='';
  $approved_ip='';
  $approver='';
  $approver_sig='';
  $processed='';
  
  if($periods):
    foreach($periods as $row): 
    $period_end=$period_end."'".$row->Date."',";
    $period_end_hmn=$period_end_hmn."'".$row->HmnDate."',";
    $period_rate=$period_rate."'".$row->Rate."',";
    $period_bonus=$period_bonus."'".$row->Bonus."',";
    $period_note=$period_note."'".str_replace('\\','',str_replace('\'','',$row->Note))."',";
    
    $submitted=$submitted."'".$row->Submitted."',";
    if(!empty($row->Submitted_IP)) $submitted_ip=' via '.$row->Submitted_IP;
    $submitter=$submitter."'".$row->Submitter.$submitted_ip."',";
    $submitter_sig=$submitter_sig."'".$wp_userid."_".$row->HmnDate."_sb',";
    
    $approved=$approved."'".$row->Approved."',";
    if(!empty($row->Approved_IP)) $approved_ip=' via '.$row->Approved_IP;
    $approver=$approver."'".$row->Approver.$approved_ip."',";
    $approver_sig=$approver_sig."'".$wp_userid."_".$row->HmnDate."_ap',";
    $processed=$processed."'".$row->Processed."',";
    endforeach;
  endif;


  //Allow filtering the $query_string in order to extend plugin
  $entry_query_string="
    SELECT WP_UserID,Date,Hours,HourType,TimeIn,TimeOut,Note,Category,HmnDate
    FROM (SELECT WP_UserID,Date,Hours,HourType,TimeIn,TimeOut,Note,Category,HmnDate FROM {$wpdb->prefix}time_entry WHERE WP_UserID='$wp_userid')a
    ORDER BY Date ASC, HourType DESC, TimeIn;
    ";
  $entry_query_string=apply_filters('dyt/entries/query',$entry_query_string);

  // Entry Meta
  $entries=dyt_sql($entry_query_string);

  //Add a filter to allow the user to edit how entry data is loaded
  $load_entry_path=DYT_DIR_PATH.'time_load_entries.php';
  include(apply_filters('dyt/entries/load_entry_path',$load_entry_path));
}

ob_start();
if($input_saved>=0) { ?>
<form id='dyt_form' method='post' accept-charset='UTF-8 ISO-8859-1' style='max-width:90%;margin-left:auto;margin-right:auto'>
  <?php echo wp_nonce_field('save_time','dyt_save_time'); ?>

  <div class='dyt_print' style='display:none'><?php echo get_custom_logo(); ?></div>
  <div id='dyt_nav' class='dyt_nav' onclick="show_time(0,0);">
    <a onclick='add_week(-1);' class='dyt_bkw noprint'>Prev Period</a>
    <a onclick='add_week(1);' class='dyt_fwd noprint'>Next Period</a>

    <?php if($admin_view>0 && $period==14) {?>
      <div style='display:inline-block;cursor:pointer' onclick="week_set.style.display='none'; week_set_desc.style.display='block'; next_week.style.display=prev_week.style.display='inline-block';">
        <span title='Previous Week' id='prev_week' onclick='add_week(-.1);' class='dashicons dashicons-image-flip-horizontal biweek noprint'></span>
        <div id='period_disp' class='dyt_title' style='margin-top:1em;padding:.5em 0;display:inline-block;font-size:1.5em'></div>
        <span title='Next Week' id='next_week' onclick='add_week(.1);' class='dashicons dashicons-image-flip-horizontal biweek noprint'></span>
        <span id='week_set' class='dashicons dashicons-admin-generic week_set noprint'></span>
        <div id='week_set_desc' class='noprint' style='display:none;font-size:1.2em;background:#fff;padding:1em;margin-bottom:1em;color:#555;width:100%'>This setting adjusts the biweekly period for all users by 1 week.</div>
      </div>

    <?php } else {?><div id='period_disp' class='dyt_title' style='margin-top:1em;padding:.5em 0;display:inline-block;font-size:1.5em'></div><?php } ?>
  </div>

  <div id='dyt_cal'></div>

  <div id='dyt_sum' style='padding:1em' onclick="show_time(0,0);">
    <div>
      <div class='dyt_title'><?php echo esc_html($user);?></div>

      <table style='float:left;margin:1em;width:auto'>
        <tr><td colspan='2' style='border-right:none'>Regular (Reg)</td><td style='border-left:none;text-align:right'><input type='text' id='Reg' name='Reg' readonly></td></tr>
        <tr id='pto_row'><td colspan='2' style='border-right:none'>Paid Time Off (PTO)</td><td style='border-left:none;text-align:right'><input type='text' id='PTO' name='PTO' readonly></td></tr>
        <tr id='ot_row'><td colspan='2' style='border-right:none'>OverTime</td><td style='border-left:none;text-align:right'><input type='text' id='OT' name='OT' readonly></td></tr>
        <tr><td colspan='2' style='border-right:none'>Total Hours</td><td style='border-left:none;text-align:right'><input type='text' id='TOT' readonly></td></tr>
        <tr id='bn_row'><td colspan='2' style='border-right:none'>Bonus</td><td style='border-left:none;text-align:right'><input type='text' id='Bonus' name='Bonus' <?php if($admin_view>0) echo "style='pointer-events:inherit' onchange='pay_bonus=this.value;sumrows();dyt_form.submit();'";?>></td></tr>
        <tr class='dyt_rate'><td colspan='2' style='border-right:none'>Total</td><td style='border-left:none;text-align:right'><input type='text' id='TOTamt' readonly></td></tr>
      </table>

      <div id='dyt_actions' style='margin:1em 0'>
        <?php $sig=0;
          if(function_exists('dyt_sig') && get_option('dyt_pro_version')>0) {
          if($sig_req<0) {update_option($pfx.'sigreq',1);$sig_req=1;}
          if($sig_req>0) {
            $sig='1';
            dyt_sig(0,0);
          }
        } ?>
        <input id='dyt_save' type='submit' value='Save' name='save' class='noprint' onclick="action.value='save'; show_save(-1);">
        <input id='dyt_print'type='button' value='Print / PDF' class='noprint' onclick="if(!localStorage.atb_print){alert('Landscape is the recommended layout for this page. To export to PDF, choose destination as \'Save as PDF\'.');localStorage.atb_print=Date.now();} window.print();">
        <div id='dyt_send_sig'></div>
        <input id='dyt_send' type='submit' disabled name='send'  value='Submit for Approval' class='noprint' style='height:auto;white-space:normal' onclick="dyt_confirm('send',2,<?php echo $sig;?>);return false;">
        <input id='dyt_cname' type='hidden' value='<?php echo $cname;?>'>
        <?php if($admin_view>0) { ?>
        <input id='dyt_df_date' type='hidden' name='df_date' value=''>
        <input id='dyt_df_hmn_date' type='hidden' name='df_hmn_date' value=''>
        <div id='dyt_approve_sig'></div>
        <input id='dyt_approve' type='submit' disabled name='approve' class='noprint pre_lock_btn' value='Approve' style='height:auto;white-space:normal' onclick="dyt_confirm('approve',3,<?php echo $sig;?>);return false;">
        <input id='dyt_process' type='submit' disabled name='process' class='noprint pre_lock_btn' value='Mark as Processed' onclick="if(confirm('&#9888; Are you sure you want to mark this period as processed?')) {action.value='process'; show_save(-2);} else return false;">
        <input id='dyt_unsubmit' type='submit' name='unsubmit' class='noprint' value='Unsubmit Period' onclick="if(!confirm('&#9888; Unsubmit this period?\n\nThis will remove any existing submission/approval/process timestamps and reopen the period to editing.')) return false; action.value='unsubmit'; show_save(-1);">
        <textarea name="period_note" id="period_note" placeholder="Period Note" style='text-align:left;pointer-events:inherit' onchange='dyt_form.submit();'></textarea>
        <input id='dyt_reset' type='submit' name='reset' class='noprint' value='Delete Period' style='width:fit-content' onclick="if(!confirm('&#9888; Delete all current period data, including all period-level notes, bonuses and submission/approval/process timestamps? Only individual time entries are retained.\n\nThis will correct period overlaps created by admin changes to pay period timeframes. Deletion may be required on adjacent timeframes for a full correction.')) return false; localStorage.removeItem('df_hmn_date'); action.value='reset'; show_save(-1);">
        <?php } ?>

        <style>
          <?php if(function_exists('dyt_sig') && get_option('dyt_pro_version')>0 && $sig_req>0) { ?>
            #sig{position:fixed;visibility:hidden;opacity:0;text-align:center;text-transform:none;border:.2em solid #EEE;background-color:#FFF;border-radius:3px;max-width:100%;max-height:100%;background:#fff;margin-left:auto;margin-right:auto;top:10%;left:0;right:0;width:fit-content;z-index:999;-webkit-transition:all .5s;-moz-transition:all .5s;transition:all .5s}
            #sigbg{position:fixed;left:0;top:0;background:#000000bd;height:100%;width:100%;z-index:0}
            #signatureForm{position:relative;background:#fff;padding:1em;z-index:1}
            #canvas{width:100%}
            #dyt_send_sig img,#dyt_approve_sig img{width:200px}
            @media screen and (min-width:769px){#sig{min-width:500px}}
          <?php } if(!$admin_view>0 && $prompt==3) { ?>
            .dyt_pop input[type=time]{pointer-events:none!important}
            .dyt_pop .stepup,.dyt_pop .stepdown{display:none!important}
          <?php } ?>
        </style>

        <script>
          var pending_action='';
          var pending_action_num=canvas_init=0;
          var max_width='<?php echo $max_width;?>';
          var sig_req='<?php echo $sig_req;?>';
          var sig_path='<?php if(function_exists('dyt_sig') && get_option('dyt_pro_version')>0) echo get_site_url().'/wp-content/uploads/time_sig/';?>';
          function saveSig() {
            if(!signaturePad.isEmpty()) dyt_gE('sigImg').value=signaturePad.toDataURL();
            else if(sig_req>1) {if(sig_req>=5 || sig_req==pending_action_num) alert('Your signature is required to proceed.');return false;}
            dyt_confirm_action(pending_action);
          }
          function clearSig(){signaturePad.clear();dyt_gE('sigImg').value='';}
          function closeSig(){if(dyt_gE('sig')){var s=dyt_gE('sig');s.style.opacity='0';s.style.visibility='hidden';}}
          function fitToContainer(c) {if(canvas_init<1){var s=dyt_gE('sig');canvas_init=1;c.width=s.offsetWidth;c.height=s.offsetHeight;}}
          function dyt_confirm(action,action_num,sig) {
            pending_action=action;
            pending_action_num=action_num;
            if(sig>0) {
              fitToContainer(canvas);
              dyt_gE('dyt_sigName').innerHTML=dyt_gE('dyt_cname').value;
              var s=dyt_gE('sig');
              s.style.visibility='visible';
              s.style.opacity='1';
              return false;
            }
            else dyt_confirm_action();
            return false;
          }
          function dyt_confirm_action() {
            if(pending_action=='send') {if(confirm('\u{26A0} Are you sure you want to submit this pay period?\n\nYour supervisor (if assigned) will be notified of this submission.')) {action.value='send'; show_save(-2);} else return false;}
            if(pending_action=='approve') {if(confirm('\u{26A0} Are you sure you want to approve this pay period?\n\nThis period will be locked, and a payroll processor (if assigned) will be notified of this approval.')) {action.value='approve'; show_save(-2);} else return false;}
          }

          window.addEventListener("DOMContentLoaded",function(){
            if(max_width>0 && !document.getElementById("adminmenuback")){
              var e=document.getElementById("dyt_form");function t(e){e.style.marginLeft="0",e.style.marginRight="0",e.style.paddingLeft="0",e.style.paddingRight="0",e.style.width="100%",e.style.maxWidth="100%",e.parentElement&&e.parentElement.offsetWidth<document.documentElement.clientWidth&&t(e.parentElement)}t(e.parentElement)
            }
          });

        </script>
        
        <input id='action' type='hidden' name='action' value=''>
        <?php if(!function_exists('dyt_sig') || get_option('dyt_pro_version')<1) {?>
          <br>
          <div class='dyt_print' style='display:none;text-align:left;margin-top:6em;padding:1em 1em 0 0;border-top:1px dotted lightgray'>
          <?php echo esc_html($user);?><br>
          By signing I, <?php echo esc_html($user);?>, certify all information is true and correct to the best of my knowledge.
          </div>
        <?php } ?>
      </div>
    </div>
    
    <?php if(isset($pto_tot)) echo dyt_pto($pto_tot,$pto_tkn,$wrk_tot,$wrk_end);?>

  </div>
  <?php
  if(isset($cat_list)) if(!empty($cat_list)) {
    $cat_list=explode(',',$cat_list);
    if(function_exists('dyt_user_cat')) {$user_cat_list=dyt_user_cat($wp_userid); if(!empty($user_cat_list)) $cat_list=$user_cat_list;}
    echo "<datalist id='cat_list_Reg'>";
    foreach($cat_list as $c) echo "<option>".esc_html($c);
    echo "</datalist>";
  }
  if(isset($cat_list_pto)) if(!empty($cat_list_pto)) {
    $cat_list_pto=explode(',',$cat_list_pto);
    echo "<datalist id='cat_list_PTO'>";
    foreach($cat_list_pto as $c) echo "<option>".esc_html($c);
    echo "</datalist>";
  } ?>
</form>

<?php if(!dyt_is_path('page=dynamic-time')) { ?>
  <div id='glang' style='margin:1em;float:right;background:#f8f8f8;padding:1em;box-shadow:0px 3px 8px #CCC'>
    <div id="google_translate_element"></div>
    <script type="text/javascript">function googleTranslateElementInit(){new google.translate.TranslateElement({pageLanguage:'en'},'google_translate_element');}</script>
    <script type="text/javascript" src="https://translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>
  </div>
<?php }} ?>

<div id='input_saved'>Saved</div><?php 
$time_cal=ob_get_contents();
ob_end_clean();

/*
function dyt_user_cat($userid) {
  $cats=get_user_meta($userid,'description',true);
  if(!empty($cats)) return preg_split('/\R/',$cats);
}
*/