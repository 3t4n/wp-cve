<?php 
// Separating out this section allows devs to update the way data is loaded into the calendar

  $date=$hours=$hourtype=$time_in=$time_out=$note=$cat=$hmn_date='';

  if($entries):
    foreach($entries as $row): 
    $date=$date."'".$row->Date."',";
    $hours=$hours."'".$row->Hours."',";
    $hourtype=$hourtype."'".$row->HourType."',";
    $time_in=$time_in."'".$row->TimeIn."',";
    $time_out=$time_out."'".$row->TimeOut."',";
    $cat=$cat."'".str_replace('\\','',str_replace('\'','',$row->Category))."',";
    $note=$note."'".str_replace('\\','',str_replace('\'','',$row->Note))."',";
    $hmn_date=$hmn_date."'".$row->HmnDate."',";
    endforeach;
  endif; 
  
  function esc_js_array($array) {return '['.str_replace("\'","'",esc_js(@substr($array,0,-1))).']';} ?>

<meta name="viewport" content="width=device-width, user-scalable=no">
<meta http-equiv="cache-control" content="no-cache">
<script type="text/javascript">
  function dyt_date_convert(date,method) {
    if(method=='hmn') return date.getFullYear()+'-'+(('0'+(date.getMonth()+1)).slice(-2))+'-'+('0'+date.getDate()).slice(-2);
    var new_date=new Date(date).getTime();
    if(method=='etime') return new_date;
    return ((new_date-28800000)/8.64e7);
  }

  var admv="<?php echo esc_js($admin_view);?>";
  var input_saved="<?php echo esc_js($input_saved);?>";
  var setup_path="<?php echo esc_js(get_admin_url(null,'admin.php?page=dynamic-time'));?>";
  var rate="<?php if(isset($rate)) echo esc_js($rate); else echo 0;?>";
  var prompt="<?php if(isset($prompt)) echo esc_js($prompt);?>";
  var notes="<?php if(isset($notes)) echo esc_js($notes);?>";
  var categoryon="<?php if(isset($cat_on)) echo esc_js($cat_on);?>";
  var exempt="<?php if(isset($exempt)) echo esc_js($exempt);?>";
  var period="<?php if(isset($period)) echo esc_js($period);?>";
  var weekbegin="<?php if(isset($weekbegin)) echo esc_js($weekbegin);?>";
  var currency="<?php if(isset($currency)) echo esc_js($currency);?>";
  var df_hr="<?php if(!empty($df_hr)) echo esc_js($df_hr); else echo '8'; ?>";
  var df_in="<?php if(!empty($df_in)) echo esc_js($df_in); else echo '09:00'; ?>";
  var df_out="<?php if(!empty($df_out)) echo esc_js($df_out); else echo '17:00'; ?>";
  var predict="<?php if(!empty($predict)) echo esc_js($predict); else echo 0; ?>";
  var ot_min_dy=<?php if(!empty($ot_min_dy)) echo esc_js($ot_min_dy); else echo 8; ?>;
  var ot_min_wk=<?php if(!empty($ot_min_wk)) echo esc_js($ot_min_wk); else echo 40; ?>;
  var ot_multip=<?php if(!empty($ot_multip)) echo esc_js($ot_multip); else echo .5; ?>;
  var tdy_epoch=<?php echo floor(strtotime(wp_date('Y-m-d'))/86400); 
  function dyt_df_date() { $date=wp_date("Y-m-d"); return strtotime($date).'000'; }?>;

  var df_date=db_df_date=new Date(<?php echo dyt_df_date();?>);
  var ls_df_date=df_hmn_date=ls_df_hmn_date=0;
  if(localStorage.getItem('df_date')!==null) ls_df_date=parseInt(localStorage.getItem('df_date'));
  if(localStorage.getItem('df_hmn_date')!==null) ls_df_hmn_date=localStorage.getItem('df_hmn_date');

  <?php 
  if(!empty($df_date)) {
    $sess=1;//if(isset($_SERVER['HTTP_COOKIE'])) $sess=sanitize_text_field($_SERVER['HTTP_COOKIE']); else $sess=sanitize_text_field($_SERVER['REMOTE_ADDR']);
    $period_sync=get_transient("dyt_period_sync_{$wp_userid}_{$df_date}_$sess");
    $period_set=get_transient("dyt_period_set");
    if($period_set>0 && (empty($period_sync) || $period_sync<$period_set)) {
      set_transient("dyt_period_sync_{$wp_userid}_{$df_date}_$sess",$df_date,86400);
      echo "var db_df_date=new Date(parseInt('$df_date')); if(db_df_date.getTime()>(df_date.getTime()-2419200000)) {df_date=localStorage.df_date=db_df_date;df_hmn_date=localStorage.df_hmn_date='$df_hmn_date';} else ";
    }
  }?>

  if(ls_df_date>0) df_date=new Date(parseInt(ls_df_date));
  if(ls_df_hmn_date>0) {df_hmn_date=ls_df_hmn_date;df_date=dyt_date_convert(df_hmn_date,'etime');}

  var period_end=<?php echo esc_js_array($period_end); ?>;
  var period_end_hmn=<?php echo esc_js_array($period_end_hmn); ?>;
  var period_rate=<?php echo esc_js_array($period_rate);?>;
  var period_bonus=<?php echo esc_js_array($period_bonus);?>;
  var period_note=<?php echo '['.substr($period_note,0,-1).']';?>;
  var submitted=<?php echo esc_js_array($submitted);?>;
  var submitter=<?php echo esc_js_array($submitter);?>;
  var submitter_sig=<?php echo esc_js_array($submitter_sig);?>;
  var approved=<?php echo esc_js_array($approved);?>;
  var approver=<?php echo esc_js_array($approver);?>;
  var approver_sig=<?php echo esc_js_array($approver_sig);?>;
  var processed=<?php echo esc_js_array($processed);?>;

  var db_hmn_date=<?php echo esc_js_array($hmn_date);?>;
  var db_date=<?php echo esc_js_array($date);?>;
  var db_hours=<?php echo esc_js_array($hours);?>;
  var db_hourtype=<?php echo esc_js_array($hourtype);?>;
  var db_time_in=<?php echo esc_js_array($time_in);?>;
  var db_time_out=<?php echo esc_js_array($time_out);?>;
  var db_note=<?php echo '['.substr($note,0,-1).']';?>;
  var db_category=<?php echo esc_js_array($cat);?>;
  var cal_lk=<?php if(isset($cal_lk) && $cal_lk>0 && dyt_userid()!=$wp_userid) echo 1; else echo 0; ?>;

  var dyt_interval=setInterval(function() {if(document.readyState==='complete') {clearInterval(dyt_interval); if(typeof dyt_load==='function')dyt_load(); else setTimeout(function(){dyt_load();},2000); console.log('dyt_loaded');}},100);
</script>
