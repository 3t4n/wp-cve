<?php 

if(!defined('ABSPATH')) exit;
if(!current_user_can('edit_posts') && !current_user_can('moderate_comments')) exit;
if(!$wpdb) $wpdb=new wpdb(DB_USER,DB_PASSWORD,DB_NAME,DB_HOST); else global $wpdb;
$input_saved=0;

// Get Versions
  global $wp_version,$dyt_version,$dyt_pro_version,$dyt_version_type;
  $dyt_db_version=get_option('dyt_db_version');
  $dyt_pro_version=0;
  $dyt_version_type='GPL';
  $pfx='dyt_config_';
  dyt_pro_path('dynamic-time','PRO'); dyt_pro_path('dynamic-time','pro');
  $get_version=dyt_sql("SELECT @@version as version;");
  if($get_version) foreach($get_version as $row):$mysql_version=$row->version;endforeach;
  $db_config_mode=dyt_sql("SELECT @@sql_safe_updates as mode;");
  if($db_config_mode) foreach($db_config_mode as $row):$config_mode=$row->mode;endforeach;

  if(isset($_POST['user_filter'])) {
    $user_filter=intval($_POST['user_filter']);
    $user_ct=intval($_POST['user_ct']);
    if($user_filter>=0 || ($user_filter<0 && $user_ct<20)) update_option($pfx.'user_filter',$user_filter);
  }
  else $user_filter=get_option($pfx.'user_filter');

  function dyt_pro_path($slug,$type) {
    global $dyt_pro_version;
    global $dyt_version_type;
    $pro_path=str_replace($slug,"$slug-$type",plugin_dir_path( __FILE__ )).'pro_content.php';
    if(is_plugin_active("$slug-$type/pro_functions.php")) 
      if(file_exists($pro_path)) {include_once $pro_path; $dyt_pro_version=get_option('dyt_pro_version'); if(function_exists('dyt_pro_ping')) {$dyt_version_type='PRO'; if(empty($dyt_pro_version))$dyt_pro_version=1;}}
  }

// Update Configuration
  if(!empty($_POST['dyt_config_time']) && check_admin_referer('config_time','dyt_config_time')) {
    $currency=sanitize_text_field($_POST['currency']);
    $prompt=intval($_POST['prompt']);
    $notes=intval($_POST['notes']);
    $period=intval($_POST['period']);
    $weekbegin=intval($_POST['weekbegin']);
    $dropdata=intval($_POST['dropdata']);
    
    if(isset($_POST['sigreq'])) $sig_req=intval($_POST['sigreq']); else $sig_req=0;
    if(isset($_POST['categoryon'])) $cat_on=intval($_POST['categoryon']); else $cat_on=0;
    $cat_list=preg_replace('/\\\\/','',sanitize_text_field(preg_replace("/\n/",",",$_POST['categorylist'])));
    $cat_list_pto=preg_replace('/\\\\/','',sanitize_text_field(preg_replace("/\n/",",",$_POST['categorylist_pto'])));
    
    if(isset($_POST['custom_ot'])) $custom_ot=intval($_POST['custom_ot']); else $custom_ot=0;
    $ot_min_dy=filter_var($_POST['ot_min_dy'],FILTER_SANITIZE_NUMBER_FLOAT,FILTER_FLAG_ALLOW_FRACTION);
    $ot_min_wk=filter_var($_POST['ot_min_wk'],FILTER_SANITIZE_NUMBER_FLOAT,FILTER_FLAG_ALLOW_FRACTION);
    $ot_multip=filter_var($_POST['ot_multip'],FILTER_SANITIZE_NUMBER_FLOAT,FILTER_FLAG_ALLOW_FRACTION);

    $df_in=sanitize_text_field($_POST['df_in']);
    $df_out=sanitize_text_field($_POST['df_out']);
    $df_hr=sanitize_text_field($_POST['df_hr']);
    $predict=intval($_POST['predict']);
    
    $pto_default=sanitize_text_field($_POST['pto_default']);
    $pto_accrue=intval($_POST['pto_accrue']);

    if(isset($_POST['pto_update']) && $dyt_pro_version>0 && $pto_default>0) $update_user_pto=dyt_sql("UPDATE {$wpdb->prefix}time_user SET PTO=%s WHERE PTO=0;",array($pto_default));
    if(isset($_POST['timeout'])) $timeout=intval($_POST['timeout']); else $timeout=-1;
    if(!empty($_POST['hide_survey'])) $hide_survey=intval($_POST['hide_survey']); else $hide_survey=0;
    $payroll_id=intval($_POST['payroll_id']);
    update_option($pfx.'prompt',$prompt);
    update_option($pfx.'notes',$notes);
    update_option($pfx.'period',$period);
    update_option($pfx.'weekbegin',$weekbegin);
    update_option($pfx.'payroll',$payroll_id);
    update_option($pfx.'currency',$currency);
    update_option($pfx.'dropdata',$dropdata);
    update_option($pfx.'timeout',$timeout);
    
    update_option($pfx.'sigreq',$sig_req);
    update_option($pfx.'categoryon',$cat_on);
    update_option($pfx.'categorylist',$cat_list);
    update_option($pfx.'categorylist_pto',$cat_list_pto);
    
    update_option($pfx.'custom_ot',$custom_ot);
    update_option($pfx.'ot_min_dy',$ot_min_dy);
    update_option($pfx.'ot_min_wk',$ot_min_wk);
    update_option($pfx.'ot_multip',$ot_multip);

    update_option($pfx.'df_in',$df_in);
    update_option($pfx.'df_out',$df_out);
    update_option($pfx.'df_hr',$df_hr);
    update_option($pfx.'predict',$predict);
    
    update_option($pfx.'pto_default',$pto_default);
    update_option($pfx.'pto_accrue',$pto_accrue);
    
    if(isset($hide_survey)) update_option('dyt_hide_survey',$hide_survey,'no');
    $input_saved++;
  }

// Update User
  if(!empty($_POST['dyt_config_user']) && check_admin_referer('config_user','dyt_config_user')) {
    $archive_user=0;
    $wp_userid=intval($_POST['wp_userid']);
    if(isset($_POST['archive_user'])) $archive_user=intval($_POST['archive_user']); 
    if($archive_user>0) $update_user=dyt_sql("UPDATE {$wpdb->prefix}time_user SET WP_UserID=-WP_UserID WHERE WP_UserID=%d;",array($wp_userid));
    else {
      if(isset($_POST['user_period'])) $user_period=intval($_POST['user_period']); else $user_period=15;
      if(isset($_POST['user_prompt'])) $user_prompt=intval($_POST['user_prompt']); else $user_prompt=2;
      $rate=filter_var($_POST['rate'],FILTER_SANITIZE_NUMBER_FLOAT,FILTER_FLAG_ALLOW_FRACTION);
      if(isset($_POST['pto'])) $pto=filter_var($_POST['pto'],FILTER_SANITIZE_NUMBER_FLOAT,FILTER_FLAG_ALLOW_FRACTION); else $pto=0;
      $exempt=intval($_POST['exempt']);
      $supervisor_id=intval($_POST['supervisor_id']);
      $wpdb->query("SET SQL_SAFE_UPDATES=0;");
      $get_user=dyt_sql("SELECT UserID FROM {$wpdb->prefix}time_user WHERE WP_UserID=%d;",array($wp_userid));
      if($get_user) $user_row=$get_user[0]->UserID; else $user_row=0;
      if($user_row>0) $update_user=dyt_sql("UPDATE {$wpdb->prefix}time_user SET Period=%s,Rate=%s,Exempt=%s,Supervisor=%s,Prompt=%s WHERE WP_UserID=%d AND UserID=%d;",array($user_period,$rate,$exempt,$supervisor_id,$user_prompt,$wp_userid,$user_row));
      else $insert_user=dyt_sql("INSERT INTO {$wpdb->prefix}time_user (WP_UserID,Period,Rate,Exempt,Supervisor,Prompt)VALUES(%d,%s,%s,%s,%s,%s);",array($wp_userid,$user_period,$rate,$exempt,$supervisor_id,$user_prompt));
      if($pto>0) $update_user=dyt_sql("UPDATE {$wpdb->prefix}time_user SET PTO=%s WHERE WP_UserID=%d;",array($pto,$wp_userid));
    }
    $input_saved++;
  }

// Get Configuration
  $hide_survey=get_option('dyt_hide_survey');
  $dyt_user=0;
  $not_set='&#9888; Not Set';
  if(strpos($_SERVER['REQUEST_URI'],'dyt_user=')!==false) $dyt_user=intval($_GET['dyt_user']);
  if($dyt_user>0) $time_cal=dynamicTime();

  $prompt=get_option($pfx.'prompt');
  if(strlen($prompt)>0): 
    $notes=get_option($pfx.'notes');
    $period=get_option($pfx.'period');
    $weekbegin=get_option($pfx.'weekbegin');
    $payroll_id=get_option($pfx.'payroll');
    $currency=get_option($pfx.'currency');
    $dropdata=get_option($pfx.'dropdata');
    $timeout=get_option($pfx.'timeout');
    
    $sig_req=get_option($pfx.'sigreq');
    $cat_on=get_option($pfx.'categoryon');
    $cat_list=get_option($pfx.'categorylist');
    $cat_list_pto=get_option($pfx.'categorylist_pto');
    
    $custom_ot=get_option($pfx.'custom_ot');
    $ot_min_dy=get_option($pfx.'ot_min_dy');
    $ot_min_wk=get_option($pfx.'ot_min_wk');
    $ot_multip=get_option($pfx.'ot_multip');
    
    $df_in=get_option($pfx.'df_in');
    $df_out=get_option($pfx.'df_out');
    $df_hr=get_option($pfx.'df_hr');
    $predict=get_option($pfx.'predict');
    
    $pto_default=get_option($pfx.'pto_default');
    $pto_accrue=get_option($pfx.'pto_accrue');
    $dyt_setup_mode=0;
  else:
    $prompt='';
    $weekbegin=$payroll_id=$timeout=-1;
    $period=$pto_default=0;
    $currency='$';
    $cat_list=$cat_list_pto='';
    $dyt_setup_mode=1;
  endif;
  
  if(empty($period)) {
    $prompt=$dropdata=$sig_req=$cat_on=$custom_ot=0;
    $notes=1;
  }
  
  if(empty($ot_min_dy)) $ot_min_dy=8;
  if(empty($ot_min_wk)) $ot_min_wk=40;
  if(empty($ot_multip)) $ot_multip=1.5;

  if(empty($df_in)) $df_in='09:00';
  if(empty($df_out)) $df_out='17:00';
  if(empty($df_hr)) $df_hr='8';
  if(empty($predict)) $predict=1;
  if(empty($pto_accrue)) $pto_accrue=1;

// Get Users
  $condition=$recent='';
  if(empty($user_filter)) {$condition="AND HmnDate>NOW()-INTERVAL 2 MONTH"; $recent="ORDER BY PeriodID DESC LIMIT 20";}
  elseif($user_filter>0) $condition="AND WP_UserID=$user_filter";
  $get_users=$wpdb->get_results("
    SELECT u.*
    ,COALESCE((SELECT Period FROM {$wpdb->prefix}time_user WHERE Period IS NOT NULL ORDER BY UserID DESC LIMIT 1),30)df_period
    ,(SELECT user_email FROM {$wpdb->base_prefix}users WHERE ID=u.WP_UserID)email
    ,COALESCE(
      (SELECT option_value FROM {$wpdb->prefix}options WHERE option_name=CONCAT('dyt_nm_',u.WP_UserID))
      ,(CONCAT((SELECT meta_value FROM {$wpdb->base_prefix}usermeta WHERE user_id=u.WP_UserID AND meta_key='first_name' AND LENGTH(meta_value)>0),IFNULL((SELECT CONCAT(' ',meta_value) FROM {$wpdb->base_prefix}usermeta WHERE user_id=u.WP_UserID AND meta_key='last_name' AND LENGTH(meta_value)>0),''),''))
      ,(SELECT display_name FROM {$wpdb->base_prefix}users WHERE ID=u.WP_UserID AND LENGTH(display_name)>0)
      ,(SELECT meta_value FROM {$wpdb->base_prefix}usermeta WHERE user_id=u.WP_UserID AND meta_key='nickname' AND LENGTH(meta_value)>0)
      ,'[wp user deleted]'
    )as name
    ,COALESCE(
      (SELECT option_value FROM {$wpdb->prefix}options WHERE option_name=CONCAT('dyt_nm_',u.Supervisor))
      ,CONCAT((SELECT meta_value FROM {$wpdb->base_prefix}usermeta WHERE user_id=u.Supervisor AND meta_key='first_name' AND LENGTH(meta_value)>0)
      ,(SELECT CONCAT(' ',meta_value) FROM {$wpdb->base_prefix}usermeta WHERE user_id=u.Supervisor AND meta_key='last_name' AND LENGTH(meta_value)>0))
      ,(SELECT meta_value FROM {$wpdb->base_prefix}usermeta WHERE user_id=u.Supervisor AND meta_key='nickname' AND LENGTH(meta_value)>0)
      ,(SELECT display_name FROM {$wpdb->base_prefix}users WHERE ID=u.Supervisor)
      ,'[wp user deleted]'
    )supervisor_name
    ,Supervisor supervisor_id
    ,(SELECT DATE_FORMAT(Submitted,'%Y-%m-%d') FROM {$wpdb->prefix}time_period WHERE WP_UserID=t.WP_UserID AND Submitted IS NOT NULL ORDER BY PeriodID DESC LIMIT 1)Submitted
    ,(SELECT DATE_FORMAT(Approved, '%Y-%m-%d') FROM {$wpdb->prefix}time_period WHERE WP_UserID=t.WP_UserID AND Submitted IS NOT NULL ORDER BY PeriodID DESC LIMIT 1)Approved
    ,(SELECT DATE_FORMAT(Processed,'%Y-%m-%d') FROM {$wpdb->prefix}time_period WHERE WP_UserID=t.WP_UserID AND Submitted IS NOT NULL ORDER BY PeriodID DESC LIMIT 1)Processed
    FROM {$wpdb->prefix}time_user u
    JOIN (
      SELECT WP_UserID
      FROM {$wpdb->prefix}time_period
      WHERE 1=1 
      $condition 
      GROUP BY WP_UserID
      $recent
    )t ON t.WP_UserID=u.WP_UserID
    WHERE u.WP_UserID>0
    GROUP BY u.WP_UserID
    ORDER BY Submitted DESC, Approved DESC, Processed DESC;
  ",OBJECT);

  $sup_ct=$user_ct=0;
  if($user_filter<=0) {
    foreach($get_users as $row) {$user_ct++; if($row->supervisor_id>0) $sup_ct++;}
    update_option('dyt_user_ct',$user_ct);
    update_option('dyt_sup_ct',$sup_ct);
  } else {
    $user_ct=get_option('dyt_user_ct',0);
    $sup_ct=get_option('dyt_sup_ct',0);
  } ?>

<div id='input_saved'>Saved</div>
<table id='dyt_head' class='dyt_control' style='width:99%;border:none;'>
  <tbody style='display:block'>
    <tr style='display:block'>
      <td align='left' style='display:block'>
        <img style='height:20px;' src='<?php echo plugins_url('/assets/DynamicTime.png',__FILE__);?>'>
        <div class='dyt_links'>
          <a href="#!" onclick="dyt_expand('dyt_setup');">Setup <span class="dashicons dashicons-admin-plugins"></span></a><br>
          <a href="#!" onclick="dyt_expand('dyt_diag');">Support <span class="dashicons dashicons-admin-tools"></span></a><br>
          <a href="#!" onclick="dyt_expand('dyt_pro');" <?php if(!current_user_can('manage_options')) echo "style='display:none'"; ?>><span class='caps'>Dynamic Time <span class='pro'>Pro</span><span class="dashicons dashicons-chart-line" style='color:#b71b8a'></span><span style='color:#1177aa;font-weight:bold'></a>
        </div>
        <br><hr style='width:50%;float:left'><br>
        <?php if(isset($dyt_user)) if($dyt_user>0) { ?>
          <a id='dyt_return' href='#!' onclick="dyt_switchScreen('dyt_admin');"><div id='dyt_return_icon'></div> Return to Admin</a>
        <?php } ?>
      </td>
    </tr>
  </tbody>
</table>

<?php 
  if(isset($_GET['updated'])) { ?>
  <div id='dyt_new' onclick="dyt_expand('dyt_pro');">
    <span style='font-size:1.2em'>Now available in <span class='caps' style='color:#FFF'>PRO</span></span><br><br>
    <div style='padding:1em;background:#931e71;border-radius:3px'>
      <span class="dashicons dashicons-controls-play"></span> PTO Bank
      <br><span class="dashicons dashicons-controls-play"></span> Custom Categories
      <br><span class="dashicons dashicons-controls-play"></span> Signature Pad
      <br><span class="dashicons dashicons-controls-play"></span> CSV Export
      <br><span class="dashicons dashicons-controls-play"></span> Filter and Total by Category
    </div>
    <?php if($dyt_pro_version<1) echo '<button>Get PRO</button>'; ?>
  </div>
<?php 
  if(!empty(get_option('dyt_idx'))) dyt_sql("OPTIMIZE TABLE {$wpdb->prefix}time_entry;");
  else {
    dyt_sql("CREATE INDEX {$wpdb->prefix}time_entry_user_date_hmn ON {$wpdb->prefix}time_entry (WP_UserID,Date,HmnDate);");
    update_option('dyt_idx',1);
  }
  if(!empty(get_option('dyt_idx5'))) dyt_sql("OPTIMIZE TABLE {$wpdb->prefix}time_period;");
  else {
    dyt_sql("CREATE INDEX {$wpdb->prefix}time_period_user_hmn ON {$wpdb->prefix}time_period (WP_UserID,PeriodID,HmnDate);");
    update_option('dyt_idx5',1);
  }
}

if($dyt_user>0) {
  if(empty($_GET['sup']) && !current_user_can('list_users')) check_admin_referer('view_user','dyt_view_user');?>
  <style>#dyt_setup td{padding-left:3em}#dyt_admin{display:none}#dyt_admin,#dyt_cal_admin{-webkit-transition:all .2s;-moz-transition:all .2s;transition:all .2s}#dyt_survey,#dyt_survey_button{display:none}#dyt_cal_admin #dyt_form{margin:0;width:99%}</style>
  <div id='dyt_cal_admin'>
    <?php echo $time_cal;?>
  </div>
<?php } ?>

<style>
#dyt_pro ul>li:before{padding:0;margin:0;content:'\276D';font-weight:700;color:#b71b8a;padding-right:6px}
a:focus{box-shadow:none}
#dyt_new{display:inline-block;float:right;margin:-1.1em 0 0;padding:2em;background:#b71b8a;color:#fff;border-radius:3px;z-index:99;min-width:33%;cursor:pointer}
#dyt_new:before{border-width:0px 7px 14px;border-color:#b71b8a transparent;content:'';position:absolute;border-style:solid;display:block;width:0;margin:-3em 3.5em 0 0;right:0}
#dyt_new button{-webkit-appearance:none;border:none;background:#fff;border-radius:3px;margin-top:1em;padding:1em;width:100%;color:gray;font-weight:bold}
#dyt_new button:hover{background:#065780;color:#fff;cursor:pointer}
#dyt_admin .button{width:100%;font-size:.9em;font-weight:normal}
#dyt_admin .dashicons{vertical-align:sub}
#dyt_admin .dashicons-dismiss{cursor:pointer}
#dyt_head .dyt_links{float:right;text-align:right}
#dyt_head .dyt_links a{line-height:1.8em}
#dyt_admin .dyt_control .dyt_link{display:block;background:#0073aa;color:#FFF;border-radius:3px;padding:1em 3em;text-align:center;font-weight:normal}
#dyt_admin .dyt_control .dyt_link:hover{background:#b71b8a;color:#FFF;text-decoration:none}
#dyt_admin .spin:hover,#dyt_admin .budge,#dyt_admin .dyt_expand{-webkit-transition:all .5s;-moz-transition:all .5s;transition:all .5s;text-decoration:none}
#dyt_admin .dyt_expand{display:none;opacity:0;max-height:0;padding:2em}
#dyt_admin #dyt_setup{font-size:1.2em}
#dyt_admin #dyt_setup li{margin:1em 0}
#dyt_admin .sel_status{color:#ce299e;}
#dyt_admin .setup_order{font-size: 2em;vertical-align:text-bottom;font-weight:bold;color:#ce299e}
#dyt_admin table th{color:#0073AA;padding:1em}
#dyt_admin table td{overflow:hidden;white-space:nowrap;padding:.7em 1em}
#dyt_admin #timesettings td{padding:.5em}
#dyt_admin table .even{background:#fff;color:#666}
#dyt_admin table .odd{background:#eef1f5;color:#000}
#dyt_admin table tr.even:hover,#dyt_admin table tr.odd:hover{background:#f7fbff;color:#0073AA;outline:.5px solid #ddd}
#dyt_admin table .col_name{color:#777}
#dyt_admin table .dyt_disable{color:gray;opacity:.7;pointer-events:none}
.caps{color:#17a;font-weight:700;font-variant-caps:petite-caps}
#dyt_admin .attn{color:#FFF;border-radius:3px;background:#b71b8a;color:#fff;padding:1em;margin:1em 0}
#dyt_admin .spin:hover{transform:rotate(180deg)}
#dyt_admin .view{cursor:pointer;text-decoration:none;user-select:none}
#dyt_admin tr:hover>td>.budge{margin-right:-.1em}
#dyt_admin .goog-te-gadget-simple{border:1px solid #888}
#dyt_admin .dyt_archive{text-decoration:none;opacity:.3}
#dyt_admin .dyt_archive:hover{color:red;opacity:1}
#usersettings .rate,#usersettings select{opacity:.7;max-width:6em;-webkit-transition:all .5s;-moz-transition:all .5s;transition:all .5s}
#usersettings .rate{opacity:.1;padding:.5em}
#usersettings select:hover,#usersettings select:focus,#usersettings .rate:hover,#usersettings .rate:focus{opacity:1}
#set_div{float:left;max-width:49em;-webkit-transition:all .3s;-moz-transition:all .3s;transition:all .3s;transition-delay:.3s}
#set_div.condense{max-width:3.2em;overflow:hidden;;margin-right:1em;border-right:1px solid #d7e5eb;border-radius:5px;cursor:pointer}
#set_div.condense:hover{max-width:49em;border-right:unset}
#set_div.condense th{float:left;transform:rotate(-90deg);letter-spacing:.1em;margin:3em 0 0 -4.45em;-webkit-transition:all .5s;-moz-transition:all .5s;transition:all .5s;transition-delay: .5s;}
#set_div.condense td{display:inline-block;opacity:.1;-webkit-transition:all .5s;-moz-transition:all .5s;transition:all .5s}
#set_div.condense:hover th{transform:unset;margin-top:unset;margin-left:unset}
#set_div.condense:hover td{opacity:1}
#set_div .dyt_control{border-left:5px solid #ccc}
</style>

<div id='dyt_admin'>
  <form class='dyt_form' name='timeconfig' id='timeconfig' method='post' accept-charset='UTF-8 ISO-8859-1' action='<?php echo get_admin_url(null,'admin.php?page=dynamic-time');?>&wp=0'>
    <?php echo wp_nonce_field('config_time','dyt_config_time');
    if(current_user_can('manage_options')) { ?>
    <div id='set_div' <?php if(!isset($_POST['dyt_config_time']) && $dyt_setup_mode<1 && ($user_ct>1 || $user_filter>0)) echo "class='condense'";?>>
    <table id='timesettings' class='dyt_control'>
      <tr style='background:transparent;color:#1177aa'><th align='left' style='padding:.5em'><span class="dashicons dashicons-admin-generic" style='vertical-align:text-top;font-size:1.2em'></span> Settings</tr>
      <tr>
        <td nowrap>
          <hr style='margin:0 0 1em'>
          Translate these settings
          <div id="google_translate_element"></div>
          <script type="text/javascript">function googleTranslateElementInit(){new google.translate.TranslateElement({pageLanguage:'en',layout:google.translate.TranslateElement.InlineLayout.SIMPLE},'google_translate_element');}</script>
          <script type="text/javascript" src="https://translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>
          <br>
        </td>
      </tr>
      <tr><td style='pointer-events:none'><span class="dashicons dashicons-info-outline"></span> Hover for more information.</td></tr>
      <tr>
        <td nowrap>
          <input type='text' name='currency' id='currency' placeholder='$' title='Currency Symbol' pattern=".{1,5}" required title="1 to 5 characters" style='max-width:5em' value='<?php if(empty($currency) || $dyt_setup_mode>0) echo '$'; else echo esc_html($currency);?>' onchange="if(this.value.length>0) dyt_config('timeconfig',this.id,this.selectedIndex);"> Currency Symbol
        </td>
      </tr>
      <tr>
        <td nowrap>
          <select name='period' id='period' onchange="<?php if($dyt_setup_mode<1 && $user_ct>1) echo "if(!confirm('Are you sure you want to change pay period length? Previous pay periods for this user may not appear correctly.')) {this.value='$period'; return false;} else";?> dyt_config('timeconfig',this.id,this.selectedIndex);" title='Select the length of pay period.'>
            <option value='' disabled <?php if($period==0) echo 'selected';?>>Select Pay Period
            <option value='7'  <?php if($period==7 ) echo 'selected';?> title='Pay periods are every week (52 per year).'>Weekly Pay Period &#8505;</span>
            <option value='14' <?php if($period==14) echo 'selected';?> title='Pay periods are in two week groups (26 per year).'>BiWeekly Pay Period &#8505;</span>
            <option value='15' <?php if($period==15) echo 'selected';?> title='Pay periods are twice per calendar month (24 per year).'>Semi-Monthly Pay Period &#8505;</span>
            <option value='30' <?php if($period==30) echo 'selected';?> title='Pay periods are every calendar month (12 per year).'>Monthly Pay Period &#8505;</span>
            <option value='-1' <?php if($period==-1) echo 'selected';?> title='Pay period differs between users.'>Manage Period on User Level &#8505;</span>
          </select>
          <span id='period_sel' class='sel_status'><?php if($period==0) echo $not_set;?></span>
        </td>
      </tr>
      <tr>
        <td nowrap>
          <select name='weekbegin' id='weekbegin' onchange="dyt_config('timeconfig',this.id,this.selectedIndex);" title='Select the day a week begins.'>
            <option value='' disabled <?php if($weekbegin<0) echo 'selected';?>>Select Week Begin &#8505;</span>
            <option value='0' <?php if($weekbegin==0 && $dyt_setup_mode==0) echo 'selected';?>>Week Begins Sunday
            <option value='1' <?php if($weekbegin==1) echo 'selected';?>>Week Begins Monday
            <option value='2' <?php if($weekbegin==2) echo 'selected';?>>Week Begins Tuesday
            <option value='3' <?php if($weekbegin==3) echo 'selected';?>>Week Begins Wednesday
            <option value='4' <?php if($weekbegin==4) echo 'selected';?>>Week Begins Thursday
            <option value='5' <?php if($weekbegin==5) echo 'selected';?>>Week Begins Friday
            <option value='6' <?php if($weekbegin==6) echo 'selected';?>>Week Begins Saturday
          </select>
          <span id='weekbegin_sel' class='sel_status'><?php if($weekbegin<0) echo $not_set;?></span>
        </td>
      </tr>
      <tr>
        <td nowrap>
          <select name='payroll_id' id='payroll_id' onchange="dyt_config('timeconfig',this.id,this.selectedIndex);" title='A supervisor receives notification when a pay period is submitted. Payroll Admin receives notification when a pay period is approved. If a supervisor is not assigned, Payroll Admin will receive both types of notifications.'>
            <option value='0' disabled <?php if($payroll_id<0) echo 'selected';?> title='A supervisor receives notification when a pay period is submitted. Payroll Admin receives notification when a pay period is approved. If a supervisor is not assigned, Payroll Admin will receive both types of notifications.'>Payroll Admin &#8505;</span>
            <option value='0' <?php if($payroll_id==0) echo 'selected';?>>No Notification
            <?php echo dyt_user_dropdown('payroll',$payroll_id);?>
          </select>
          <span id='payroll_id_sel' class='sel_status'><?php if($payroll_id<0) echo $not_set;?></span>
        </td>
      </tr>
      <tr>
        <td nowrap>
          <select name='prompt' id='prompt' onchange="dyt_config('timeconfig',this.id,this.selectedIndex);" title='Select a method of time entry.'>
            <option value='' disabled  <?php if(strlen($prompt)==0) echo 'selected';?>>Select Entry Type
            <option value='0' <?php if(strlen($prompt)>0 && $prompt==0) echo 'selected';?> title="Multiple hour fields per day.">Simple Total (recommended) &#8505;</span>
            <option value='1' <?php if($prompt==1) echo 'selected';?> title="Multiple In/Out fields per day with predictive entry.">Itemized Time &#8505;</span>
            <option value='3' <?php if($prompt==3) echo 'selected';?> title="Punch In/Out Only (Only admins can manually adjust time).">Punch Only &#8505;</span>
            <option value='2' <?php if($prompt==2) echo 'selected'; if($dyt_pro_version<=0) echo 'disabled'; ?> title="AUTO Entry - records time automatically (This is practical when a user is active in WordPress throughout the workday).">Auto Entry (PRO) &#8505;</span>
            <option value='-1' <?php if($prompt==-1) echo 'selected';?> title='Entry type differs between users.'>Manage Entry Type on User Level &#8505;</span>
          </select>
          <span id='prompt_sel' class='sel_status'><?php if(strlen($prompt)==0) echo $not_set;?></span>

          <select name='timeout' id='timeout' onchange="dyt_config('timeconfig',0,0);" style='<?php if($dyt_pro_version>0 && $prompt>1) echo "display:block;margin:.5em 0 0"; else echo "display:none"; ?>' title="If user inactivity in exceeds this time, Auto Entry will create an additional in/out period when activity resumes.">
            <option value='' disabled <?php if($timeout<0) echo 'selected';?>>Session Timeout &#8505;</span>
            <option value='10' <?php if($timeout==10) echo 'selected';?> title="If user inactivity in exceeds this time, Auto Entry will create an additional in/out period when activity resumes.">10 minutes
            <option value='20' <?php if($timeout==20) echo 'selected';?> title="If user inactivity in exceeds this time, Auto Entry will create an additional in/out period when activity resumes.">20 minutes
            <option value='30' <?php if($timeout==30) echo 'selected';?> title="If user inactivity in exceeds this time, Auto Entry will create an additional in/out period when activity resumes.">30 minutes (recommended)
            <option value='40' <?php if($timeout==40) echo 'selected';?> title="If user inactivity in exceeds this time, Auto Entry will create an additional in/out period when activity resumes.">40 minutes
            <option value='60' <?php if($timeout==60) echo 'selected';?> title="If user inactivity in exceeds this time, Auto Entry will create an additional in/out period when activity resumes.">1 hour
            <option value='90' <?php if($timeout==90) echo 'selected';?> title="If user inactivity in exceeds this time, Auto Entry will create an additional in/out period when activity resumes.">1.5 hours
            <option value='120' <?php if($timeout==120) echo 'selected';?> title="If user inactivity in exceeds this time, Auto Entry will create an additional in/out period when activity resumes.">2 hours
            <option value='240' <?php if($timeout==240) echo 'selected';?> title="If user inactivity in exceeds this time, Auto Entry will create an additional in/out period when activity resumes.">4 hours
            <option value='360' <?php if($timeout==360) echo 'selected';?> title="If user inactivity in exceeds this time, Auto Entry will create an additional in/out period when activity resumes.">6 hours
            <option value='480' <?php if($timeout==480) echo 'selected';?> title="If user inactivity in exceeds this time, Auto Entry will create an additional in/out period when activity resumes.">8 hours
            <option value='600' <?php if($timeout==600) echo 'selected';?> title="If user inactivity in exceeds this time, Auto Entry will create an additional in/out period when activity resumes.">10 hours
            <option value='720' <?php if($timeout==720) echo 'selected';?> title="If user inactivity in exceeds this time, Auto Entry will create an additional in/out period when activity resumes.">12 hours
          </select>
        </td>
      </tr>
      <tr>
        <td nowrap>
          <select name='notes' id='notes' onchange="dyt_config('timeconfig',this.id,this.selectedIndex);" title='Enable the option to enter notes on each time entry.'>
            <option value='' disabled  <?php if($notes<0) echo 'selected';?> title='Enable the option to enter notes on each time entry.'>Select Note Display &#8505;</span>
            <option value='1' <?php if($notes==1) echo 'selected';?> title="Display optional note field.">Display Notes &#8505;</span>
            <option value='0' <?php if($notes==0) echo 'selected';?> title="Hide note field.">No Notes &#8505;</span>
          </select>
          <span id='notes_sel' class='sel_status'><?php if($notes<0) echo $not_set;?></span>
        </td>
      </tr>
      <tr>
        <td nowrap style='border-color:transparent;border-left:5px solid gray;border-style:dotted;'>
          <select name='dropdata' id='dropdata' onchange="dyt_config('timeconfig',this.id,this.selectedIndex);" title='Keep Data Safe will retain time entries and configuration data even if the plugin is uninstalled.'>
            <option value='' disabled <?php if($dropdata<0) echo 'selected';?> title='Keep Data Safe will retain time entries and configuration data even if the plugin is uninstalled.'>Uninstall Option &#8505;</span>
            <option value='0' <?php if($dropdata===0) echo 'selected';?> title='No plugin data will be deleted.'>Keep Data Safe &#8505;</span>
            <option value='1' <?php if($dropdata==1) echo 'selected';?> title='All data will be deleted on uninstall.'>Delete All Plugin Data &#8505;</span>
          </select>
          <span id='dropdata_sel' class='sel_status'><?php if($dropdata<0) echo '&#9888; Not Set';?></span>
        </td>
      </tr>
      <?php if($dyt_pro_version>0) $pro_td_style="style='border-color:transparent;border-left:5px solid #ce299e;'"; else $pro_td_style="style='border-color:transparent;border-left:5px solid #ce299e4d;'"; ?>
      <tr>
        <td nowrap <?php echo $pro_td_style;?>>
          <select name='sigreq' id='sigreq' onchange="dyt_config('timeconfig',this.id,this.selectedIndex);" title='Signature Pad for Employee and Supervisors. PRO Feature.'>
            <option value='0' disabled  <?php if($sig_req<=0) echo 'selected';?>>Signature Pad (PRO) &#8505;</span>
            <option value='1' <?php if($sig_req==1 && $dyt_pro_version>0) echo 'selected'; if($dyt_pro_version<=0) echo ' disabled';?>>Signature Optional (PRO)</span>
            <option value='2' <?php if($sig_req==2 && $dyt_pro_version>0) echo 'selected'; if($dyt_pro_version<=0) echo ' disabled';?>>Require Employee Signature (PRO)</span>
            <option value='3' <?php if($sig_req==3 && $dyt_pro_version>0) echo 'selected'; if($dyt_pro_version<=0) echo ' disabled';?>>Require Supervisor Signature (PRO)</span>
            <option value='5' <?php if($sig_req==5 && $dyt_pro_version>0) echo 'selected'; if($dyt_pro_version<=0) echo ' disabled';?>>Require all Signatures (PRO)</span>
            <option value='0' <?php if($dyt_pro_version<=0) echo ' disabled';?>>Disable Signature Pad</span>
          </select>
          <span id='sigreq_sel' class='sel_status'><?php if($sig_req<0) echo $not_set;?></span>
        </td>
      </tr>
      <tr>
        <td nowrap <?php echo $pro_td_style;?>>
          <select name='categoryon' id='categoryon' onchange="dyt_config('timeconfig',this.id,this.selectedIndex);" title='Additional dropdown for categorizing time. PRO Feature.'>
            <option value='0' disabled  <?php if($cat_on<=0) echo 'selected';?>>Categories (PRO) &#8505;</span>
            <option value='1' <?php if($cat_on==1 && $dyt_pro_version>0) echo 'selected'; if($dyt_pro_version<=0) echo ' disabled';?>>Enable Categories (PRO)</span>
            <option value='0' <?php if($dyt_pro_version<=0) echo ' disabled';?>>Disable Categories</span>
          </select>
          <span id='categoryon_sel' class='sel_status'><?php if($cat_on<0) echo $not_set;?></span>
        </td>
      </tr>
      <tr <?php if(!$cat_on>0 || $dyt_setup_mode>0 || $dyt_pro_version<=0) echo "style='display:none'"; ?>>
        <td nowrap <?php echo $pro_td_style;?>>
          <span title="List categories available for Reg time separated by comma or line."><span class="dashicons dashicons-info-outline"></span> Reg Categories</span><textarea name='categorylist' id='categorylist' onchange="dyt_config('timeconfig',this.id,this.selectedIndex);" style='display:block;overflow:hidden scroll;width:100%;height:7em;padding:.5em' placeholder='List Reg Categories&#10;Separate by comma or line.'><?php echo esc_html(str_replace(',',"\n",$cat_list)); ?></textarea><br>
          <span title="List categories available for PTO (Paid Time Off) separated by comma or line. PTO is not eligible for overtime."><span class="dashicons dashicons-info-outline"></span> PTO Categories</span><textarea name='categorylist_pto' id='categorylist_pto' onchange="dyt_config('timeconfig',this.id,this.selectedIndex);" style='display:block;overflow:hidden scroll;width:100%;height:7em;background:#eee;padding:.5em' placeholder='List PTO Categories&#10;Separate by comma or line.&#10;PTO is not eligible for overtime.'><?php echo esc_html(str_replace(',',"\n",$cat_list_pto)); ?></textarea><br>
        </td>
      </tr>
      <tr>
        <td nowrap <?php echo $pro_td_style;?>>
          <select name='custom_ot' id='custom_ot' onchange="dyt_config('timeconfig',this.id,this.selectedIndex);" title='Trigger overtime by setting a custom day or week limit. PRO Feature.'>
            <option value='0' disabled  <?php if($custom_ot<=0) echo 'selected';?>>Custom Overtime (PRO) &#8505;</span>
            <option value='1' <?php if($custom_ot==1 && $dyt_pro_version>0) echo 'selected'; if($dyt_pro_version<=0) echo ' disabled';?>>Enable Custom Overtime (PRO)</span>
            <option value='0' <?php if($dyt_pro_version<=0) echo ' disabled';?>>Disable Custom Overtime</span>
          </select>
          <span id='custom_ot_sel' class='sel_status'><?php if($custom_ot<0) echo $not_set;?></span>
        </td>
      </tr>
      <tr <?php if($custom_ot<=0 || $dyt_setup_mode>0 || $dyt_pro_version<=0) echo "style='display:none'"; ?>>
        <td nowrap <?php echo $pro_td_style;?>>
          <div><b>Custom Overtime</b></div>
          <div style='background:#f5f8fd;border:1px solid #aaa;border-radius:3px;padding:1em'>
            <input type='number' name='ot_min_dy' id='ot_min_dy' title='Min Day OT' step='.25' min='1' max='24' required style='max-width:5em;margin:.25em 0' value='<?php echo esc_html($ot_min_dy);?>' onchange="if(this.value>0) dyt_config('timeconfig',this.id,this.selectedIndex);"> 
            <span title='Hours worked over this threshold qualify as overtime.'> <span class="dashicons dashicons-info-outline"></span> Min Hours per Day</span><br>
            <input type='number' name='ot_min_wk' id='ot_min_wk' title='Min Week OT' step='.25' min='1' max='99' required style='max-width:5em;margin:.25em 0' value='<?php echo esc_html($ot_min_wk);?>' onchange="if(this.value>0) dyt_config('timeconfig',this.id,this.selectedIndex);"> 
            <span title='Hours worked over this threshold qualify as overtime.'> <span class="dashicons dashicons-info-outline"></span> Min Hours per Week</span><br>
            <input type='number' name='ot_multip' id='ot_multip' title='OT Multiplier' step='.25' min='1' max='3' required style='max-width:5em;margin:.25em 0' value='<?php echo esc_html($ot_multip);?>' onchange="if(this.value>0) dyt_config('timeconfig',this.id,this.selectedIndex);"> 
            <span title='Hourly rate multiplier for hours worked overtime.'> <span class="dashicons dashicons-info-outline"></span>Overtime Wage Multiplier</span>
          </div>
        </td>
        <span id='ot_min_dy_sel' class='sel_status'><?php if($ot_min_dy<0) echo $not_set;?></span>
      </tr>
      <tr <?php if($dyt_pro_version<=0) echo "style='display:none'"; ?>>
        <td nowrap <?php echo $pro_td_style;?>>
          <div><b>PTO Behavior</b></div>
          <div style='background:#f5f8fd;border:1px solid #aaa;border-radius:3px;padding:1em'>
            <input type='number' name='pto_default' id='pto_default' title='PTO Default Hours per Year for new employees' step='.25' min='1' max='999' required style='max-width:5em;margin:.25em 0' value='<?php echo esc_html($pto_default);?>' onchange="dyt_config('timeconfig',this.id,this.selectedIndex);"> 
            <span title='PTO Default Hours per Calendar Year for new employees.'> <span class="dashicons dashicons-info-outline"></span> PTO Default per Year</span><br>
            <div <?php if($pto_default==0) echo "style='display:none'";?>>
              <input type='checkbox' name='pto_update' id='pto_update' title='Update PTO' onchange="if(this.checked && confirm('Are you sure you want to update PTO to <?php echo esc_html($pto_default);?> for all employees that have no current PTO allowance?')) dyt_config('timeconfig','',''); else this.checked=false;"> 
              <span title='Update PTO for all employees that have no current PTO allowance.'> <span class="dashicons dashicons-info-outline"></span> Update PTO Now</span><br>
            </div>
            <div style='margin:1em 0 .5em'>
              <input type='checkbox' name='pto_accrue_ck' id='pto_accrue_ck' title='Automatic PTO Accrual' <?php if($pto_accrue>=0) echo 'checked';?> onchange="if(this.checked) pto_accrue.value=1; else pto_accrue.value=-1; dyt_config('timeconfig','','');"> 
              <span title='This setting accrues PTO based on first and last time entry per calendar year. Unchecking this makes all PTO available up front.'> <span class="dashicons dashicons-info-outline"></span> PTO Automatic Accruals</span>
              <input type='hidden' name='pto_accrue' id='pto_accrue' value='<?php echo esc_html($pto_accrue);?>';>
            </div>
          </div>
        </td>
        <span id='ot_min_dy_sel' class='sel_status'><?php if($ot_min_dy<0) echo $not_set;?></span>
      </tr>
      <tr>
        <td nowrap>
          <div><b>Entry Defaults</b></div>
          <div style='background:#fff;border:1px solid #aaa;border-radius:3px;padding:1em'>
            <div style='display:<?php if($prompt<=0 || $prompt==3) echo 'block'; else echo 'none';?>'>
              <input type='number' name='df_hr' id='df_hr' title='Default Hours per Day' step='1' min='1' max='23' required style='max-width:5em;margin:.25em 0' value='<?php echo esc_html($df_hr);?>' onchange="if(this.value>0) dyt_config('timeconfig',this.id,this.selectedIndex);"> 
              <span title='Default hours per day for simple total entry setting.'> <span class="dashicons dashicons-info-outline"></span> Default Hours</span><br>
            </div>
            <div style='display:<?php if($prompt!=0 && $prompt!=3) echo 'block'; else echo 'none';?>'>
              <input type='time' name='df_in' id='df_in' title='Default Time In.' step='60' required style='margin:.25em 0' value='<?php echo esc_html($df_in);?>' onchange="dyt_config('timeconfig',this.id,this.selectedIndex);"> 
              <span title='Default time-in for itemized entry setting.'> <span class="dashicons dashicons-info-outline"></span> Default Time In</span><br>
              <input type='time' name='df_out' id='df_out' title='Default Time Out.' step='60' required style='margin:.25em 0' value='<?php echo esc_html($df_out);?>' onchange="dyt_config('timeconfig',this.id,this.selectedIndex);"> 
              <span title='Default time-out for itemized entry setting.'> <span class="dashicons dashicons-info-outline"></span> Default Time Out</span>
            </div>
            <div style='margin:1em 0 .5em'>
              <input type='checkbox' name='predict_ck' id='predict_ck' title='Predictive Entry' <?php if($predict>0) echo 'checked';?> onchange="if(this.checked) predict.value=1; else predict.value=-1; dyt_config('timeconfig','','');"> 
              <span title='Predictive entry takes precedence over entry defaults, and is based on historical entries per user.'> <span class="dashicons dashicons-info-outline"></span> Predictive Entry</span>
              <input type='hidden' name='predict' id='predict' value='<?php echo esc_html($predict);?>';>
            </div>
          </div>
        </td>
        <span id='df_in_sel' class='sel_status'><?php if($df_in<0) echo $not_set;?></span>
      </tr>
    </table>
    </div>
    <?php }

    if($dyt_setup_mode==0) { ?>
    <input type='hidden' name='hide_survey' id='hide_survey' value=<?php if($hide_survey==1) echo 1; else echo 0; ?>>
    <?php } ?>
  </form>

  <div id='dyt_diag' class='dyt_control dyt_expand' style='float:right'>
    <span style='color:#1177aa;font-weight:bold'><span class="dashicons dashicons-admin-tools"></span> Support & Diagnostics</span>
    <a onclick="dyt_expand('dyt_diag');" style='text-decoration:none;float:right'><span class="dashicons dashicons-dismiss"></span></a><hr>
    <br>
    <div id='dyt_diag_data'>
      <b>Configuration</b><br>
      Host <?php echo esc_html($_SERVER['HTTP_HOST'].'@'.$_SERVER['SERVER_ADDR']); ?><br>
      Path <?php echo esc_html(substr(plugin_dir_path( __FILE__ ),-33));?><br>
      WP <?php echo esc_html($wp_version); if(is_multisite()) echo 'multi'; ?><br>
      PHP <?php echo phpversion();?><br>
      MYSQL <?php echo esc_html($mysql_version); if(!empty($config_mode)) echo esc_html($config_mode); ?><br>
      Theme <?php $pt=wp_get_theme(get_template()); echo esc_html($pt->Name.' '.$pt->Version); $ct=wp_get_theme(); if($pt->Name!==$ct->Name) echo esc_html(', '.$ct->Name.' '.$ct->Version);?><br>
      Dynamic Time <?php echo esc_html($dyt_version.' '.$dyt_version_type.' '.$dyt_pro_version); ?><br>
      Dynamic Time db <?php echo esc_html($dyt_db_version);?><br>
      <br>
      <b>Settings</b><br>
      Setup Mode <?php echo esc_html($dyt_setup_mode);?><br>
      Currency <?php echo esc_html($currency);?><br>
      Period <?php echo esc_html($period);?><br>
      Week Begin <?php echo esc_html($weekbegin);?><br>
      Payroll <?php echo esc_html($payroll_id);?><br>
      Prompt <?php echo esc_html($prompt);?><br>
      Notes <?php echo esc_html($notes);?><br>
      Drop Data <?php echo esc_html($dropdata);?><br>
      Sig Pad <?php echo esc_html($sig_req);?><br>
      Reg Cats <?php echo esc_html($cat_on); echo esc_html('('.count(explode(',',$cat_list)).')'); ?><br>
      PTO Cats <?php echo esc_html($cat_on); echo esc_html('('.count(explode(',',$cat_list_pto)).')'); ?><br>
      Custom OT <?php echo esc_html($custom_ot); echo esc_html("($ot_min_dy / $ot_min_wk / $ot_multip)"); ?><br>
      Defaults <?php echo esc_html($custom_ot); echo esc_html("($df_hr / $df_in / $df_out / $predict)"); ?><br>
      Users <?php echo esc_html($user_ct);?><br>
      Sups <?php echo esc_html($sup_ct);?><br>
    </div>
    <br>
    <a class='dyt_link caps' href="https://richardlerma.com/contact/?imsg=" target='_blank' onclick="this.href+=append_diag('dyt_diag_data');">Contact Support</a>
  </div>

  <div id='dyt_pro' class='dyt_control dyt_expand' style='float:right'>
    Get <span class='caps'>Dynamic Time <span class='pro'>Pro</span></span><span class="dashicons dashicons-chart-line" style='color:#b71b8a;vertical-align:text-bottom'></span>
    <a onclick="dyt_expand('dyt_pro');" style='text-decoration:none;float:right'><span class="dashicons dashicons-dismiss"></span></a><hr>
    <br>
      <strong>Subscription Features</strong>
      <ul style='padding:unset;color:#555;font-size:1.2em'>
        <li><b>PTO bank</b> with accruals
        <li><b>Custom categories</b> with dropdown
        <li><b>Signature pad</b> for approvals
        <li><b>CSV export</b> compatible with Excel
        <li><b>Filter and total</b> time entries
        <li><b>Dedicated support</b> by email
      </ul><br>

    <?php if($dyt_pro_version!==0) { ?>
      <div class='attn caps' style='font-weight:normal;cursor:pointer;text-align:center;padding-right:2.5em' onclick="dyt_expand('dyt_pro');"><span class='dashicons dashicons-yes'></span> Installed</div>
      <input type='button' class='button' value='Check for Updates' onclick="window.location.href='<?php echo get_admin_url(null,'admin.php?page=dynamic-time&pro_update=1');?>';"><?php 
    }
    if($dyt_pro_version<=0) { ?><a class='dyt_link caps' style='margin-top:1em' href="https://dynamictime.net" target='_blank'>Learn More</a><br><?php } ?>
  </div>

  <div id='dyt_setup' class='dyt_control dyt_expand' style='float:left'>
    <span style='color:#0073AA;font-weight:bold'><span class="dashicons dashicons-admin-plugins"></span> Get Started</span>
    <a onclick="alert('Setup instructions will persistently display until more than one user has a recent time entry.'); dyt_expand('dyt_setup');" style='text-decoration:none;float:right'><span class="dashicons dashicons-dismiss"></span></a><hr>
    <ul style='margin-left:1em'>
      <li><span class='setup_order'>&#10112;</span> Customize your organization's setup in the <b>Settings</b> module.
      <li><span class='setup_order'>&#10113;</span> <select style='border:1px solid #c7daec;color:gray' onchange="window.location.href='<?php echo str_replace('&amp;','&',wp_nonce_url(get_admin_url(null,'admin.php?page=dynamic-time'),'view_user','dyt_view_user'));?>&dyt_user='+this.options[this.selectedIndex].value;"><option selected disabled>Open a timesheet<?php echo dyt_user_dropdown('setup',0);?></select> Press save. Users must save a time period to appear in <b>Entries</b>.
      <li><span class='setup_order'>&#10114;</span> Refresh this page <a href='#!' onclick="if(window.location.href.indexOf('dyt_view_user')>0) window.location='<?php echo get_admin_url(null,'admin.php?page=dynamic-time');?>'; else location.reload(true);" title='Refresh'><span style='opacity:.5' class="spin dashicons dashicons-update"></span></a> to see your new entry.
      <li title='Employees will only see their timesheet on this page.  Only Administrators and assigned Supervisors can see other employee timesheets.'><span class='setup_order'>&#10115;</span> Privileges are automatic <span class="dashicons dashicons-info-outline"></span> (only admins see all employees).
      <li><span class='setup_order'>&#10116;</span> Employee-access shortcode:
        <div style='text-align:center;padding:5px 1em;background:#fff;width:fit-content;margin:1em auto;color:dimgray;border:1px solid #c7daec;font-size:.8em' title='Click to copy.&#013;When published, the shortcode will automatically redirect to the login form if a user is not logged in. Leave max_width=true for the best page fit. Change max_width=false for native page width.'><a id='dyt_shortcode' style='display:inline-block;padding:1em' href='#!' onclick="dyt_copy(this.id,this.id);">[dynamicTime max_width=true]</a> <span class="dashicons dashicons-info-outline" style='font-size:1.3em'></span></span>
    </ul>
    <div style='font-size:.8em;padding:1em'><span class="dashicons dashicons-format-status"></span> Need help? View <a href='#!' onclick="dyt_expand('dyt_diag');">Support & Diagnostics</a></div>
  </div>

  <?php if($dyt_pro_version>0 && (current_user_can('edit_posts')||current_user_can('moderate_comments')) && function_exists('dyt_pro')) @dyt_pro($prompt,$notes,$cat_on); 
    if($user_ct>0 || $dyt_setup_mode<1) { ?>
    <table id='usersettings' cellspacing='0' cellpadding='0' class='dyt_control'>
      <tr style='background:transparent'>
        <th colspan='<?php $cols=7; if($period<0) $cols++; if($prompt<0) $cols++; if($dyt_pro_version>0) $cols++; echo $cols; ?>' align='left'>
          <span class="dashicons dashicons-clock" style='vertical-align:middle;font-size:1.3em'></span> Entries &nbsp;
          <form style='display:inline' method='post' accept-charset='UTF-8 ISO-8859-1' action='<?php echo get_admin_url(null,'admin.php?page=dynamic-time');?>'>
            <select style='height:2em;min-width:20em;font-size:.9em;border:none;font-weight:normal' name='user_filter' onchange="<?php if($user_ct>=20) echo "if(this.value<0) if(!confirm('\'Display All\' may take a while to load. Continue?')) {this.value=0;return false;}";?>this.form.submit();">
              <option value='0' selected>Recently Active
              <option value='-1' <?php if($user_ct<20 && $user_filter<0) echo 'selected'; ?>>Display All
              <?php echo dyt_user_dropdown('user',$user_filter);?>
            </select>
            <input type='hidden' name='user_ct' value='<?php echo $user_ct;?>'>
          </form><hr>
        </th>
        <th><a href='#!' onclick="if(window.location.href.indexOf('dyt_view_user')>0) window.location='<?php echo get_admin_url(null,'admin.php?page=dynamic-time');?>'; else location.reload(true);" title='Refresh'><span style='float:right;opacity:.5;margin:-1.5em -1em 0 0' class="spin dashicons dashicons-update"></span></a></th>
      </tr>
      
      <tr style='background:transparent'>
        <th class='col_name'>Name</th>
        <th class='col_name dyt_rate'>Rate</th>
        <?php if((!empty($pto_default) || !empty($pto_accrue)) && $dyt_pro_version>0) {?><th class='col_name dyt_pto' title='Enter Annual PTO in hours. Accruals are prorated from date of first time entry this year, thru date of last time entry this year.'>PTO<span class='dashicons dashicons-info-outline' style='font-size:1.1em'></span></th><?php } ?>
        <th class='col_name'>Status</th><?php 
        if($prompt<0) { ?><th class='col_name'>Entry Type</th><?php } 
        if($period<0) { ?><th class='col_name'>Period</th><?php } ?>
        <th class='col_name'>Supervisor</th>
        <th class='col_name'>Submitted</th>
        <th class='col_name'>Approved</th>
        <th class='col_name'>Processed</th>
        <th class='col_name' align='right' style='min-width:70px;'>View</th>
      </tr><?php 

    $row_id=1; 
    foreach($get_users as $row) {
      if(strpos($row->name,'[wp ')!==false) $disabled="class='dyt_disable'"; else $disabled='';
      $uid=$row->WP_UserID;
      $sid=$row->supervisor_id;
      $cid=dyt_userid();
      if($cid!=$sid && $cid!=$uid && !current_user_can('list_users')) continue;
      $uname=ucwords(preg_replace("/[^\p{L} ]/u",'',$row->name)); if($uid>0 && !empty($row->email)) update_option("dyt_nm_$uid",$uname);
      $sname=ucwords(preg_replace("/[^\p{L} ]/u",'',$row->supervisor_name)); if($sid>0 && !empty($row->email)) update_option("dyt_nm_$sid",$sname);
      $u_exempt=$row->Exempt;
      $u_prompt=$row->Prompt;
      $df_period=$row->df_period;
      $u_period=$row->Period; ?>

      <form class='form' name='userconfig' id='userconfig<?php echo esc_attr($uid);?>' method='post' accept-charset='UTF-8 ISO-8859-1' action='<?php echo get_admin_url(null,'admin.php?page=dynamic-time');?>&wp=0'>
        <?php echo wp_nonce_field('config_user','dyt_config_user');?>
        <input type='hidden' name='wp_userid' value='<?php echo esc_attr($uid);?>'>
        <tr class='<?php if($row_id % 2===0) echo 'odd'; else echo 'even';?>'>

          <td nowrap>
            <?php if(empty($row->email)) { ?>
              <input type='hidden' name='archive_user' value=0>
              <a href='#!' class='dyt_archive' title='Archive' onclick="if(confirm('Are you sure you want to archive this user?\n\nArchived users\' data is only accessible by direct database access, or CSV Export in PRO.')) {this.previousElementSibling.value=1; this.parentElement.parentElement.style.background='indianred'; dyt_config('userconfig<?php echo esc_attr($uid);?>');} else return false;">
                <span class="dashicons dashicons-remove"></span> &nbsp; 
              </a>
            <?php } else { ?>
              <a title="Edit Account" style='opacity:.3' target='_blank' href='../wp-admin/user-edit.php?user_id=<?php echo esc_attr($uid);?>' <?php echo esc_html($disabled);?>> &#128100;</a> &nbsp;
              <a title="Email <?php echo esc_attr($uname);?>" style='font-size:1.3em;text-decoration:none' target='_blank' href='mailto:<?php echo esc_attr($row->email);?>' <?php echo esc_attr($row->email);?>' <?php echo esc_html($disabled);?>> &#9993;</a> &nbsp;
            <?php } ?>
            <a title="View Timecard" style='font-size:1.2em' class='view' <?php echo esc_html($disabled);?> onclick="dyt_switchScreen('<?php echo esc_attr($uid);?>');"><?php echo esc_attr($uname);?></a>
          </td>

          <td nowrap class='dyt_rate'>
            <?php if(!$row->Rate>0) echo "<span title='Enter Hourly Rate'>&#9888;</span>"; else  echo esc_html($currency); if($currency=='$') $mxr='999'; else $mxr='9999'; ?>
            <input type='number' <?php if($cid==$uid && !current_user_can('list_users')) echo "style='pointer-events:none'"; ?> required step='0.01' min='0' max='<?php echo esc_attr($mxr);?>' value='<?php echo esc_attr($row->Rate);?>' name='rate' class='rate' placeholder='Hourly Rate' onchange="dyt_config('userconfig<?php echo esc_attr($uid);?>');">
          </td>

          <?php if(isset($row->PTO) && $dyt_pro_version>0) {?>
            <td nowrap class='dyt_pto'>
              <input type='number' title='Annual PTO Hours' <?php if($cid==$uid && !current_user_can('list_users')) echo "style='pointer-events:none'"; ?> required step='0.5' min='0' max='999' value='<?php echo esc_attr($row->PTO);?>' name='pto' class='rate' placeholder='PTO' onchange="dyt_config('userconfig<?php echo esc_attr($uid);?>');">
            </td>
          <?php } ?>

          <td nowrap>
            <?php if(!isset($u_exempt)) echo "<span title='Select Status'>&#9888;</span>";?>
            <select required name='exempt' onchange="dyt_config('userconfig<?php echo esc_attr($uid);?>');">
              <option disabled  <?php if(!isset($u_exempt)) echo 'selected';?>>Select Status
              <option value='0' <?php if($u_exempt===0) echo 'selected';?> title='Time and a half for hours worked in excess of 40/week.'>Overtime Eligible (Standard FLSA <span class="dashicons dashicons-info-outline"></span>)
              <option value='-1' <?php if($u_exempt==-1) echo 'selected';?> title='Time and a half for hours worked in excess of 8/day OR 40/week.'>Overtime Eligible (California <span class="dashicons dashicons-info-outline"></span>)
              <option value='-2' <?php if($u_exempt==-2) echo "selected title='Time * $ot_multip for hours worked in excess of $ot_min_dy/day OR $ot_min_wk/week.'"; elseif($custom_ot<1 || $dyt_pro_version<=0) echo 'disabled'; ?> >Custom Overtime (PRO)
              <option value='1' <?php if($u_exempt==1) echo 'selected';?> title='Not overtime eligible'>Exempt <span class="dashicons dashicons-info-outline"></span>
            </select>
          </td><?php 

          if($prompt<0) { ?>
            <td nowrap>
              <?php if(!isset($u_prompt)) echo "<span title='Select Entry Type'>&#9888;</span>";?>
              <select name='user_prompt' id='user_prompt' onchange="<?php if($dyt_setup_mode<1 && $user_ct>1) echo "if(!confirm('Are you sure you want to change entry type for this user?')) {this.value='".esc_attr($u_prompt)."'; return false;} else";?> dyt_config('userconfig<?php echo esc_attr($uid);?>');">
                <option value='' disabled  <?php if($u_prompt<0) echo 'selected';?>>Select Entry Type
                <option value='0' <?php if($u_prompt==0) echo 'selected';?> title="One total field per day.">Simple <span class="dashicons dashicons-info-outline"></span>
                <option value='1' <?php if($u_prompt==1) echo 'selected';?> title="Multiple In/Out fields per day with predictive entry.">Itemized <span class="dashicons dashicons-info-outline"></span>
                <option value='3' <?php if($u_prompt==3) echo 'selected';?> title="Punch In/Out Only (Only admins can manually adjust time).">Punch Only <span class="dashicons dashicons-info-outline"></span>
                <option value='2' <?php if($u_prompt==2) echo 'selected'; if($dyt_pro_version<=0) echo 'disabled'; ?> title="AUTO Entry - records time automatically (This is practical when a user is active in WordPress throughout the workday).">Auto Entry (PRO) <span class="dashicons dashicons-info-outline"></span>
              </select>
            </td><?php 
          }

          if($period<0) { ?>
            <td nowrap>
              <?php if(!isset($u_period)) {echo "<span title='Select Pay Period'>&#9888;</span>"; $u_period=$df_period;}?>
              <select name='user_period' id='user_period' onchange="<?php if($dyt_setup_mode<1 && $user_ct>1) echo "if(!confirm('Are you sure you want to change pay period length? Previously submitted pay periods may require reprocessing to appear correctly. Find the reprocess button under each timecard.')) {this.value='".$u_period."'; return false;} else";?> dyt_config('userconfig<?php echo esc_attr($uid);?>');">
                <option value='' disabled>Select Pay Period
                <option value='7'  <?php if($u_period==7 ) echo 'selected';?> title='Pay periods are every week (52 per year).'>Weekly Pay Period <span class="dashicons dashicons-info-outline"></span>
                <option value='14' <?php if($u_period==14) echo 'selected';?> title='Pay periods are in two week groups (26 per year).'>BiWeekly Pay Period <span class="dashicons dashicons-info-outline"></span>
                <option value='15' <?php if($u_period==15) echo 'selected';?> title='Pay periods are twice per calendar month (24 per year).'>Semi-Monthly Pay Period <span class="dashicons dashicons-info-outline"></span>
                <option value='30' <?php if($u_period==30) echo 'selected';?> title='Pay periods are every calendar month (12 per year)'>Monthly Pay Period <span class="dashicons dashicons-info-outline"></span>
              </select>
            </td><?php 
          } ?>

          <td nowrap>
            <select value='<?php echo esc_attr($sid);?>' <?php if(!current_user_can('list_users')) echo "style='pointer-events:none'"; else echo "onchange=\"dyt_config('userconfig$uid');\"";?> name='supervisor_id' title="<?php echo esc_attr($sname);?>">
              <option value='0' disabled title='A supervisor will receive notification when a pay period is submitted.'>Supervisor <span class="dashicons dashicons-info-outline"></span>
              <option value='0' <?php if(!$sid>0) echo 'selected';?>>None
              <?php echo dyt_user_dropdown('supervisor',$sid);?>
            </select>
          </td>

          <td nowrap align='right'><a class='view' onclick="dyt_switchScreen('<?php echo esc_attr($uid);?>');"><?php echo $row->Submitted;?></a></td>
          <td nowrap align='right'><a class='view' onclick="dyt_switchScreen('<?php echo esc_attr($uid);?>');"><?php echo $row->Approved;?></a></td>
          <td nowrap align='right'><a class='view' onclick="dyt_switchScreen('<?php echo esc_attr($uid);?>');"><?php echo $row->Processed;?></a></td>
          <td align='right'><a title='View Timecard' class='view budge' style='font-size:2em;' onclick="dyt_switchScreen('<?php echo esc_attr($uid);?>');">&#10162;</a></td>
        </tr>
      </form><?php 
      $row_id++;
    }} else echo "<style>#usersettings{display:none}</style>"; ?>
  </table>

  <?php if($dyt_setup_mode==0 && $user_ct>2 && $hide_survey!=1) { ?>
    <div class='clear'></div>
    <div id='dyt_survey' class='dyt_control'>
      <a onclick="
        if(!confirm('Permanently hide the Review prompt?')) return;
        if(dyt_getE('hide_survey')) {
          if(dyt_getE('hide_survey').value==1) dyt_getE('hide_survey').value=0;
          else dyt_getE('hide_survey').value=1;
        }
        if(dyt_getE('timeconfig')) dyt_getE('timeconfig').submit();">
      <span style='text-decoration:none;float:right' class="dashicons dashicons-dismiss"></span>
      </a>
      <div class='caps'>Liking Dynamic Time?</div>
      <a href='https://wordpress.org/support/plugin/dynamic-time/reviews/#new-post' style='color:#ffb900;text-decoration:none' target='_blank'>
        <div style='margin:.5em 0 1.5em 0;color:#ffb900'>
          <span style='color:#ccc;font-size:.8em'>Leave a Quick Review</span><br>
          <span class="dashicons dashicons-star-filled"></span>
          <span class="dashicons dashicons-star-filled"></span>
          <span class="dashicons dashicons-star-filled"></span>
          <span class="dashicons dashicons-star-filled"></span>
          <span class="dashicons dashicons-star-filled"></span>
        </div>
      </a>
      Have a question or concern?<br><br>
      <a href='#!' class='dyt_link caps' onclick="dyt_expand('dyt_diag');dyt_survey.style.display='none';">open support menu</a>
    <?php } ?>
  </div>

<script type='text/javascript'>
  var terms_clicked=cal_expired=0;
  var dyt_user='<?php echo esc_attr($dyt_user);?>';<?php 
  if($dyt_pro_version>0) {$pro=get_option('dyt_pro'); if($pro=='new') update_option('dyt_pro','yes','no');} else $pro='no';
  if($pro=='new' || ($dyt_pro_version<=0 && isset($_GET['updated']) && function_exists('gzcompress'))) { ?>
    var dyt_version_Interval=setInterval(function() {
      if(document.readyState==='complete') {clearInterval(dyt_version_Interval);dyt_expand('dyt_pro');window.location.hash='#dyt_pro';}
    },200);<?php 
  }

  if(!$dyt_user>0 && ($dyt_setup_mode>0 || ($user_ct<2 && $user_filter<=0))) { ?>
    var dyt_setup_Interval=setInterval(function() {
      if(document.readyState==='complete') {clearInterval(dyt_setup_Interval);dyt_expand('dyt_setup');}
    },200);
  <?php } ?>

  if('<?php echo $input_saved;?>'>0) {
    save_msg=dyt_getE('input_saved');
    save_msg.style.opacity=1;
    save_msg.style.display='block';
    save_msg.innerHTML='Saved';
    setTimeout(function(){save_msg.style.opacity=0;},2000);
    setTimeout(function(){save_msg.style.display='none';},3000);
  }
  function dyt_getE(e) {return document.getElementById(e);}

  function dyt_switchScreen(screen) {
    var setup_mode='<?php if($user_ct<2 || $dyt_setup_mode>0) echo 1; else echo 0;?>';
    var user;
    if(screen!='dyt_admin') {user=screen; screen='dyt_cal_admin';}
    if((cal_expired>0 || dyt_user!=user) && screen=='dyt_cal_admin') {
      dyt_config('userconfig');
      var url="<?php echo str_replace('&amp;','&',wp_nonce_url(get_admin_url(null,'admin.php?page=dynamic-time'),'view_user','dyt_view_user')); ?>";
      window.location=url+"&dyt_user="+user;
      return;
    }
    if(sum_updated>0 && screen=='dyt_admin') if(!confirm('Exit without saving changes?')) return false;
    dyt_getE('dyt_cal_admin').style.opacity='0';
    dyt_getE('dyt_admin').style.opacity='0';
    setTimeout(function(){
      dyt_getE('dyt_return').style.display='none';
      dyt_getE('dyt_cal_admin').style.display='none';
      dyt_getE('dyt_admin').style.display='none';
    },100);
    
    setTimeout(function(){
      dyt_getE(screen).style.display='block';
      if(screen=='dyt_cal_admin')dyt_getE('dyt_return').style.display='block';
      else if(setup_mode>0) dyt_expand('dyt_setup');
      setTimeout(function(){dyt_getE(screen).style.opacity='1';},100);
    },101);
  }

  function dyt_config(fid,sid,sel_index) {
    var setup_mode='<?php echo esc_attr($dyt_setup_mode);?>';
    if(fid=='timeconfig') {
      if(sel_index>0 && sid.length>0) dyt_getE(sid+'_sel').innerHTML='&#10004';
      if(dyt_getE('timeconfig').innerHTML.indexOf('Not Set')!=-1) return false;
    }
    dyt_getE('dyt_admin').style.opacity='.3';
    
    if(dyt_getE('dyt_head')) var dyt_head=dyt_getE('dyt_head');
    if(dyt_head) dyt_head.innerHTML=dyt_head.innerHTML+"<progress id='loading' style='float:left;width:100%;' max='100'></progress>";
    if(setup_mode>0) { setTimeout(function(){ if(dyt_getE(fid)) dyt_getE(fid).submit(); },2000);}
    else if(dyt_getE(fid)) dyt_getE(fid).submit();
  }

  function dyt_copy(content_id,button_id) {
    dyt_getE(button_id).disabled=true;
    var contents=dyt_getE(content_id).innerHTML;
    
    if(contents.indexOf('&#9986;')>0) return false;
    var tmpEl;
    var copy_temp='copy_temp'+Math.floor((Math.random()*1000)+1);
    
    tmpEl=document.createElement(copy_temp);
    tmpEl.style.opacity=0;
    tmpEl.style.position="absolute";
    tmpEl.style.pointerEvents="none";
    tmpEl.style.zIndex=-1;
    tmpEl.innerHTML=contents;
    document.body.appendChild(tmpEl);

    var range=document.createRange();
    range.selectNode(tmpEl);
    
    var w=window.getSelection();
    if(w.rangeCount>0) w.removeAllRanges();
    w.addRange(range);

    if(!document.execCommand("copy")) {
      alert('Unable to auto-copy this content.\nPlease copy this content manually.');
      return false;
    }
    document.body.removeChild(tmpEl);
    
    var confirm="<div class='caps' style='position:absolute;background:#17A;color:#DDD;padding:2em;border-radius:3px;box-shadow:0px 3px 5px #888;z-index:99'>&#9986; Copied to clipboard!</div>";
    var opaque="<div style='opacity:.3;height:100%;width:100%;'>"+contents+"</div>";
    
    dyt_getE(content_id).innerHTML=confirm+opaque;
    
    setTimeout(function(){
      dyt_getE(content_id).innerHTML=contents;
      dyt_getE(button_id).disabled=false;
    },2000);
  }

  function dyt_expand(id) {
    if(!dyt_getE(id)) return false;
    var is_admin=1; if(dyt_getE('dyt_return')) {if(dyt_getE('dyt_return').style.display!='none') is_admin=0;}
    var target=dyt_getE(id);
    if(is_admin==0) dyt_switchScreen('dyt_admin');
    if(target.style.opacity==1 && is_admin==0) {target.style.opacity='0'; dyt_expand(id); return;}
    if(target.style.opacity!=1) {
      target.style.display='block';
      setTimeout(function(){
        target.style.opacity='1';
        target.style.maxHeight='99em';
        target.style.padding=target.style.overflow='';
      },10);
    } else {
      target.style.maxHeight=target.style.opacity=target.style.padding='0';
      target.style.overflow='none';
      setTimeout(function(){target.style.display='none';},500);
    }
  }

  function append_diag(diag) {
    var d=dyt_getE(diag).innerHTML;
    d=d.replace(/  /g,'');
    d=d.replace(/(\r\n|\r|\n)/g,'%0A');
    d=d.replace(/<\/?[^>]+(>|$)/g,'');
    return 'Type your inquiry here%0A%0A%0ADiagnostics follow:%0A-------------%0A'+d;
  }
</script>