<?php

  // Start:: Language constants, translate below:
  // -----------------------------------------------

  $l_calendar        = __("Calendar",'booking-calendar-contact-form');
  $l_min_nights      = __("The minimum number of nights to book is",'booking-calendar-contact-form');
  $l_max_nights      = __("The maximum number of nights to book is",'booking-calendar-contact-form');
  $l_select_dates    = __("Select start and end dates",'booking-calendar-contact-form');
  $l_p_select        = __("Please select start and end dates",'booking-calendar-contact-form');
  $l_select_start    = __("Select Start Date",'booking-calendar-contact-form');
  $l_select_end      = __("Select End Date",'booking-calendar-contact-form');
  $l_cancel_c        = __("Cancel Selection",'booking-calendar-contact-form');
  $l_sucess          = __("Successfully",'booking-calendar-contact-form');
  $l_cost            = __("Cost",'booking-calendar-contact-form');
  $l_coupon          = __("Coupon code (optional)",'booking-calendar-contact-form');
  $l_service         = __("Service",'booking-calendar-contact-form');
  $l_sec_code        = __("Please enter the security code",'booking-calendar-contact-form');
  $l_sec_code_low    = __("Security Code (lowercase letters)",'booking-calendar-contact-form');  
  $l_payment_options = __("Payment options",'booking-calendar-contact-form');
  
  $l_continue        = __($button_label,'booking-calendar-contact-form');

  // End:: Language constants.
  // -----------------------------------------------
  
  $nonce_un = wp_create_nonce( 'bccf_bookingf' );

?>
<?php if ( !defined('DEX_AUTH_INCLUDE') ) { echo 'Direct access not allowed.'; exit; } ?>
<style type="text/css">
<?php 
  $calendar_mwidth = dex_bccf_get_option('calendar_mwidth', '2');
  $calendar_minmwidth = dex_bccf_get_option('calendar_minmwidth', '200px');
  $calendar_maxmwidth = dex_bccf_get_option('calendar_maxmwidth', '400px');
  $calendar_height = dex_bccf_get_option('calendar_height', '1');
  
  if ($calendar_mwidth == '') $calendar_mwidth = 2;
  echo '#dex_bccf_pform .rcalendar .ui-datepicker-multi .ui-datepicker-group{width:'.floor(100/$calendar_mwidth - 0.1).'% !important}';
  
  if ($calendar_minmwidth == '') $calendar_minmwidth = '200px';
  if (!strpos($calendar_minmwidth,'px') && !strpos($calendar_minmwidth,'%')) $calendar_minmwidth .= 'px';
  echo '#dex_bccf_pform .rcalendar .ui-datepicker-multi .ui-datepicker-group{min-width:'.$calendar_minmwidth.' !important}';
  echo '#dex_bccf_pform .ui-datepicker:not(.ui-datepicker-multi){min-width:'.$calendar_minmwidth.'}';   // single cal
 
  if ($calendar_maxmwidth == '') $calendar_maxmwidth = '400px';
  if (!strpos($calendar_maxmwidth,'px') && !strpos($calendar_maxmwidth,'%')) $calendar_maxmwidth .= 'px';
  echo '#dex_bccf_pform .rcalendar .ui-datepicker-multi .ui-datepicker-group{max-width:'.$calendar_maxmwidth.' !important}';
  echo '#dex_bccf_pform .ui-datepicker:not(.ui-datepicker-multi){max-width:'.$calendar_maxmwidth.'}';  // single cal
  
  if ($calendar_height == '') $calendar_height = 2;
  $calendar_height = (intval($calendar_height)-1)*2;
  //echo '#dex_bccf_pform tr th,#dex_bccf_pform tr td{padding:'.$calendar_height.'px}';
  echo '#dex_bccf_pform tr th span,#dex_bccf_pform tr th a,#dex_bccf_pform tr td span, #dex_bccf_pform tr td a{padding:'.$calendar_height.'px}';  

  $custom_styles = base64_decode(get_option('CP_BCCF_CSS', '')); 
  if ($custom_styles != '')
      echo $custom_styles;
?></style><?php  
  $custom_scripts = base64_decode(get_option('CP_BCCF_JS', '')); 
  if ($custom_scripts != '')
      echo '<script type="text/javascript">'.$custom_scripts.'</script>';  
?>
<form class="cpp_form" name="dex_bccf_pform" id="dex_bccf_pform" action="<?php get_site_url(); ?>" method="post" enctype="multipart/form-data" onsubmit="return doValidate(this);"><input name="dex_bccf_post" type="hidden" value="1" /><input type="hidden" name="_wpnonce" value="<?php echo esc_attr($nonce_un); ?>" />
<?php if ($option_calendar_enabled != 'false') { ?>
<script>
var pathCalendar = "<?php echo esc_js(cp_bccf_get_site_url()); ?>/";
</script>
<?php
    $option_overlapped = dex_bccf_get_option('calendar_overlapped', DEX_BCCF_DEFAULT_CALENDAR_OVERLAPPED);

    $calendar_dateformat = dex_bccf_get_option('calendar_dateformat',DEX_BCCF_DEFAULT_CALENDAR_DATEFORMAT);
    $dformat = ((dex_bccf_get_option('calendar_dateformat', DEX_BCCF_DEFAULT_CALENDAR_DATEFORMAT)==0)?"mm/dd/yy":"dd/mm/yy");
    $dformat_php = ((dex_bccf_get_option('calendar_dateformat', DEX_BCCF_DEFAULT_CALENDAR_DATEFORMAT)==0)?"m/d/Y":"d/m/Y");
    $calendar_mindate = "";
    $value = dex_bccf_get_option('calendar_mindate',DEX_BCCF_DEFAULT_CALENDAR_MINDATE);
    if ($value != '') $calendar_mindate = date($dformat_php, strtotime($value));
    $calendar_maxdate = "";
    $value = dex_bccf_get_option('calendar_maxdate',DEX_BCCF_DEFAULT_CALENDAR_MAXDATE);
    if ($value != '') $calendar_maxdate = date($dformat_php, strtotime($value));
    $cfmode = dex_bccf_get_option('calendar_holidaysdays', '1111111'); if (strlen($cfmode)!=7) $cfmode = '1111111';
    $workingdates = "[".$cfmode[0].",".$cfmode[1].",".$cfmode[2].",".$cfmode[3].",".$cfmode[4].",".$cfmode[5].",".$cfmode[6]."]";
    $cfmode = dex_bccf_get_option('calendar_startresdays', '1111111'); if (strlen($cfmode)!=7) $cfmode = '1111111';
    $startReservationWeekly = "[".$cfmode[0].",".$cfmode[1].",".$cfmode[2].",".$cfmode[3].",".$cfmode[4].",".$cfmode[5].",".$cfmode[6]."]";

    $h = dex_bccf_get_option('calendar_holidays','');
    $h = explode(";",$h);
    $holidayDates = array();
    for ($i=0;$i<count($h);$i++)
        if ($h[$i]!="")
            $holidayDates[]= '"'.esc_js($h[$i]).'"';
    $holidayDates = "[".implode(",", $holidayDates)."]";

    $h = dex_bccf_get_option('calendar_startres','');
    $h = explode(";",$h);
    $startReservationDates = array();
    for ($i=0;$i<count($h);$i++)
        if ($h[$i]!="")
            $startReservationDates[]= '"'.esc_html($h[$i]).'"';
    $startReservationDates = "[".implode(",",$startReservationDates)."]";




?>
<div <?php echo (count($myrows) < 2?'style="display:none"':''); ?> id="bccfcalselectionarea">
<?php
  echo esc_html($l_calendar).':<br /><select name="dex_item" id="dex_item" onchange="dex_updateItem()">';
  foreach ($myrows as $item)
      echo '<option value='.intval($item->id).'>'.esc_html(__($item->uname,'booking-calendar-contact-form')).'</option>';
  echo '</select>';
?>
<br /><br />
</div>
<div id="bccfcalarea">
<label id="bccfselectdates"><?php echo esc_html($l_select_dates).":"; ?></label>
<?php
  foreach ($myrows as $item)
      echo '<div id="calarea'.intval($item->id).'" style="display:none" class="rcalendar"></div>';
?>
<div id="bccf_display_price" <?php if (dex_bccf_get_option('calendar_showcost','1') != '1') echo 'style="display:none"'; ?>>
Price:
</div>
</div>
<?php } else { ?><input name="dex_item" id="dex_item" type="hidden" value="<?php echo intval($myrows[0]->id); ?>" /><?php } ?>
<div id="selddiv" style="font-weight: bold;margin-top:0px;padding-top:0px;padding-right:3px;padding-left:3px;"></div>
<script type="text/javascript"><?php if ($option_calendar_enabled != 'false') { ?>
 var dex_current_calendar_item;
 function dex_updateItem()
 {
    document.getElementById("calarea"+dex_current_calendar_item).style.display = "none";
    var i = document.dex_bccf_pform.dex_item.options.selectedIndex;
    var selecteditem = document.dex_bccf_pform.dex_item.options[i].value;
    dex_do_init(selecteditem);
 }
 function dex_do_init(id)
 {
myjQuery = (typeof myjQuery != 'undefined' ) ? myjQuery : jQuery;
  try{$testjq = myjQuery} catch (e) {}
  if (typeof $testjq == 'undefined')
  {
    setTimeout("dex_do_init("+id+");");
    return;
  }    
  myjQuery(function(){
    (function($) {         
        dex_current_calendar_item = id;
        document.getElementById("calarea"+dex_current_calendar_item).style.display = "";
        $calendarjQuery = myjQuery;
        $calendarjQuery(function() {
        $calendarjQuery("#calarea"+id).rcalendar({"calendarId":id,
                                                    "partialDate":<?php echo esc_js(dex_bccf_get_option('calendar_mode',DEX_BCCF_DEFAULT_CALENDAR_MODE)); ?>,
                                                    "defaultColor":'<?php echo esc_js(dex_bccf_get_option('calendar_deselcolor',TDE_BCCFCALENDAR_DEFAULT_SELCOLOR)); ?>',
                                                    "partial_defaultColor":'<?php echo esc_js(dex_bccf_get_option('calendar_deselcolor',TDE_BCCFCALENDAR_DEFAULT_SELCOLOR)); ?>',
                                                    "edition":false,
                                                    "minDate":"<?php echo esc_js($calendar_mindate);?>",
                                                    "maxDate":"<?php echo esc_js($calendar_maxdate);?>",
                                                    "dformat":"<?php echo esc_js($dformat);?>",
                                                    "workingDates":<?php echo esc_js($workingdates);?>,
	    			                                "holidayDates":<?php echo ($holidayDates);?>,
	    			                                "startReservationWeekly":<?php echo esc_html($startReservationWeekly);?>,
	    			                                "startReservationDates":<?php echo ($startReservationDates);?>,
	    			                                "fixedReservationDates":<?php echo ((dex_bccf_get_option('calendar_fixedmode', '')=='1'?'true':'false'));?>,
	    			                                "fixedReservationDates_length":<?php $v=dex_bccf_get_option('calendar_fixedreslength','1'); if ($v=='') echo '1'; else echo esc_js($v); ?>,
                                                    "language":"<?php echo esc_js($calendar_language); ?>",
                                                    "firstDay":<?php echo intval(dex_bccf_get_option('calendar_weekday', DEX_BCCF_DEFAULT_CALENDAR_WEEKDAY)); ?>,
                                                    "numberOfMonths":<?php echo esc_js(dex_bccf_get_option('calendar_pages',DEX_BCCF_DEFAULT_CALENDAR_PAGES)); ?>
                                                    });
        });
        $calendarjQuery("#calarea"+id).addClass('notranslate');
        document.getElementById("selddiv").innerHTML = "";
    })(myjQuery);
    });
 }
 function bccf_init_cal(){
    if (window.jQuery){
        dex_do_init(<?php echo intval($myrows[0]->id); ?>);
    }
    else{
       window.setTimeout("bccf_init_cal();",100);
    }
 }
 bccf_init_cal();
 var bccf_d1 = "";
 var bccf_d2 = "";
 var bccf_ser = "";
 function updatedate()
 {
    try
    {
        var a = (document.getElementById("selDay_startcalarea"+dex_current_calendar_item ).value != '');
        var b = (document.getElementById("selDay_endcalarea"+dex_current_calendar_item ).value != '');
        var c = false;
        if (a)
        {
          if (!b && <?php echo (dex_bccf_get_option('calendar_mode',DEX_BCCF_DEFAULT_CALENDAR_MODE) == 'false'?'true':'false'); ?>)
          {
            b = a;              
            document.getElementById("selDay_endcalarea"+dex_current_calendar_item ).value = document.getElementById("selDay_startcalarea"+dex_current_calendar_item ).value;
            document.getElementById("selMonth_endcalarea"+dex_current_calendar_item ).value = document.getElementById("selMonth_startcalarea"+dex_current_calendar_item ).value;
            document.getElementById("selYear_endcalarea"+dex_current_calendar_item ).value = document.getElementById("selYear_startcalarea"+dex_current_calendar_item ).value;
          }
          if (b)
            c = true;    
        }
        if (c)
        {
            var d1 = document.getElementById("selYear_startcalarea"+dex_current_calendar_item ).value+"-"+document.getElementById("selMonth_startcalarea"+dex_current_calendar_item ).value+"-"+document.getElementById("selDay_startcalarea"+dex_current_calendar_item ).value;
            var d2 = document.getElementById("selYear_endcalarea"+dex_current_calendar_item ).value+"-"+document.getElementById("selMonth_endcalarea"+dex_current_calendar_item ).value+"-"+document.getElementById("selDay_endcalarea"+dex_current_calendar_item ).value;
            $dexQuery = (typeof myjQuery != 'undefined' ) ? myjQuery : jQuery;
            var ser = "";<?php for ($k=1;$k<=DEX_BCCF_DEFAULT_SERVICES_FIELDS; $k++) if ($dex_buffer[$k] != '') { ?>ser += String.fromCharCode(38)+"ser<?php echo $k; ?>="+$dexQuery("#dex_services<?php echo $k; ?>").val();<?php } ?>
            if (bccf_d1 != d1 || bccf_d2 != d2 || bccf_ser != ser)
            {
                bccf_d1 = d1;
                bccf_d2 = d2;
                bccf_ser = ser;
                $dexQuery.ajax({
                  type: "GET",
                  url: "<?php echo cp_bccf_get_site_url(); ?>/?dex_bccf=getcost"+String.fromCharCode(38)+"inAdmin=1"+String.fromCharCode(38)+"dex_item="+dex_current_calendar_item+""+String.fromCharCode(38)+"from="+d1+""+String.fromCharCode(38)+"to="+d2+""+ser
                }).done(function( html ) {
                    document.getElementById("bccf_display_price").innerHTML = '';
                    $dexQuery("#bccf_display_price").append('<b><?php echo esc_js($l_cost); ?>:</b> <?php echo dex_bccf_get_option('currency', DEX_BCCF_DEFAULT_CURRENCY); ?> '+html);
                });
            }
        }
        else
        {
            bccf_d1 = "";
            bccf_d2 = "";
            document.getElementById("bccf_display_price").innerHTML = '';
        }
    } catch (e) {}
 }
 setInterval('updatedate()',200);<?php } ?>
 var cp_bccf_ready_to_go = false;
 function doValidate(form)
 {
    if (cp_bccf_ready_to_go) return;
    $dexQuery = (typeof myjQuery != 'undefined' ) ? myjQuery : jQuery;<?php if ($option_calendar_enabled != 'false') { ?>
    var d1 = new Date(document.getElementById("selYear_startcalarea"+dex_current_calendar_item ).value,document.getElementById("selMonth_startcalarea"+dex_current_calendar_item ).value-1,document.getElementById("selDay_startcalarea"+dex_current_calendar_item ).value);
    var d2 = new Date(document.getElementById("selYear_endcalarea"+dex_current_calendar_item ).value,document.getElementById("selMonth_endcalarea"+dex_current_calendar_item ).value-1,document.getElementById("selDay_endcalarea"+dex_current_calendar_item ).value);
    var ONE_DAY = 1000 * 60 * 60 * 24;
    var nights = Math.round(Math.abs(d2.getTime() - d1.getTime()) / ONE_DAY)<?php if (dex_bccf_get_option('calendar_mode',DEX_BCCF_DEFAULT_CALENDAR_MODE) == 'false') echo '+1'; ?>;<?php
    $minn = dex_bccf_get_option('calendar_minnights', '0'); if ($minn == '') $minn = '0';
    $maxn = dex_bccf_get_option('calendar_maxnights', '0'); if ($maxn == '0' || $maxn == '') $maxn = '365';
    ?>
    <?php } ?>
    document.dex_bccf_pform.dex_bccf_ref_page.value = document.location;<?php if ($option_calendar_enabled != 'false') { ?>
    if (document.getElementById("selDay_startcalarea"+dex_current_calendar_item).value == '' || document.getElementById("selDay_endcalarea"+dex_current_calendar_item).value == '')
    {
        alert('<?php echo str_replace("'","\'",$l_p_select); ?>.');
        return false;
    }
    if (nights < <?php echo $minn; ?>){
        alert('<?php echo str_replace("'","\'",$l_min_nights.' '.$minn); ?>');
        return false;
    }
    if (nights > <?php echo $maxn; ?>){
        alert('<?php echo str_replace("'","\'",$l_max_nights.' '.$maxn); ?>');
        return false;
    }<?php } ?>
    <?php if (!is_admin() && dex_bccf_get_option('dexcv_enable_captcha', TDE_BCCFDEFAULT_dexcv_enable_captcha) != 'false') { ?> if ($dexQuery("#hdcaptcha_dex_bccf_post").val() == '')
    {
        setTimeout( "cpbccf_cerror()", 100);
        return false;
    }
    var result = $dexQuery.ajax({
        type: "GET",
        url: "<?php echo cp_bccf_get_site_url(); ?>?inAdmin=1"+String.fromCharCode(38)+"hdcaptcha_dex_bccf_post="+$dexQuery("#hdcaptcha_dex_bccf_post").val(),
        async: false
    }).responseText;
    if (result.indexOf("captchafailed") != -1)
    {
        $dexQuery("#dex_bccf_captchaimg").attr('src', $dexQuery("#dex_bccf_captchaimg").attr('src')+'&'+Math.floor((Math.random() * 99999) + 1));
        setTimeout( "cpbccf_cerror()", 100);
        return false;
    }
    else <?php } ?>
    {
        var cpefb_error = 0;
        $dexQuery("#dex_bccf_pform").find(".cpefb_error").each(function(index){
            if ($dexQuery(this).css("display")!="none")
                cpefb_error++;    
            });
        if (cpefb_error) return false;    
        cp_bccf_ready_to_go = true;
        cpbccf_blink(".pbSubmit");        
        document.getElementById("form_structure").value = '';        
        return true;
    }    
 }
 function cpbccf_cerror(){$dexQuery = jQuery.noConflict();$dexQuery("#hdcaptcha_error").css('top',$dexQuery("#hdcaptcha_dex_bccf_post").outerHeight());$dexQuery("#hdcaptcha_error").css("display","inline");} 
 function cpbccf_blink(selector){
        try {   
            $dexQuery = jQuery.noConflict();
            $dexQuery(selector).fadeOut(1000, function(){
                $dexQuery(this).fadeIn(1000, function(){
                    try {
                        if (cp_bccf_ready_to_go)
                            cpbccf_blink(this); 
                    } catch (e) {}  
                });
            });        
        } catch (e) {}             
 }
</script><input type="hidden" name="dex_bccf_pform_process" value="1" /><input type="hidden" name="dex_bccf_id" value="<?php echo CP_BCCF_CALENDAR_ID; ?>" /><input type="hidden" name="dex_bccf_ref_page" value="<?php esc_attr(cp_bccf_get_FULL_site_url()); ?>" /><input type="hidden" name="form_structure" id="form_structure" size="180" value="<?php echo str_replace('"','&quot;',str_replace("\r","",str_replace("\n","",esc_attr(dex_bccf_cleanJSON(dex_bccf_translate_json(dex_bccf_get_option('form_structure', DEX_BCCF_DEFAULT_form_structure))))))); ?>" />
<?php if (is_admin()) {?>
  <fieldset style="border: 1px solid black; -webkit-border-radius: 8px; -moz-border-radius: 8px; border-radius: 8px; padding:15px;">
   <legend>Administrator options</legend>
    <input type="checkbox" name="sendemails_admin" value="1" checked /> Send notification emails for this booking<br /><br />
    <input type="checkbox" name="repeatbooking" value="1"  /> Repeat booking <input type="text" name="repeattimes" size="2" value="2"> times <input type="text" name="repeatevery" size="2" value="7"> days 
  </fieldset> 
<?php } ?>
  <div id="fbuilder">
      <div id="formheader"></div>
      <div id="fieldlist"></div>
  </div>
<div style="display:none">  
<div id="cpcaptchalayer">
<?php
     $codes = $wpdb->get_results ( $wpdb->prepare( 'SELECT * FROM '.DEX_BCCF_DISCOUNT_CODES_TABLE_NAME.' WHERE `cal_id`=%d', CP_BCCF_CALENDAR_ID ) );
     if (count($codes))
     {
?>
      <div class="fields" id="field-c0">
         <label><?php echo $l_coupon; ?>:</label>
         <div class="dfield"><input type="text" name="couponcode" value=""></div>
         <div class="clearer"></div>
      </div>
<?php } ?>
<?php
   if (!is_admin() && dex_bccf_get_option('dexcv_enable_captcha', TDE_BCCFDEFAULT_dexcv_enable_captcha) != 'false')
   {
?>
  <div class="fields" id="field-ck2"><label></label><div class="dfield"><?php echo $l_sec_code; ?>:<br /><img src="<?php echo plugins_url('/captcha/captcha.php?width='.dex_bccf_get_option('dexcv_width', TDE_BCCFDEFAULT_dexcv_width).'&inAdmin=1&height='.dex_bccf_get_option('dexcv_height', TDE_BCCFDEFAULT_dexcv_height).'&letter_count='.dex_bccf_get_option('dexcv_chars', TDE_BCCFDEFAULT_dexcv_chars).'&min_size='.dex_bccf_get_option('dexcv_min_font_size', TDE_BCCFDEFAULT_dexcv_min_font_size).'&max_size='.dex_bccf_get_option('dexcv_max_font_size', TDE_BCCFDEFAULT_dexcv_max_font_size).'&noise='.dex_bccf_get_option('dexcv_noise', TDE_BCCFDEFAULT_dexcv_noise).'&noiselength='.dex_bccf_get_option('dexcv_noise_length', TDE_BCCFDEFAULT_dexcv_noise_length).'&bcolor='.dex_bccf_get_option('dexcv_background', TDE_BCCFDEFAULT_dexcv_background).'&border='.dex_bccf_get_option('dexcv_border', TDE_BCCFDEFAULT_dexcv_border).'&font='.dex_bccf_get_option('dexcv_font', TDE_BCCFDEFAULT_dexcv_font), __FILE__); ?>"  id="dex_bccf_captchaimg" alt="security code" border="0" class="skip-lazy"  />
  <div class="clearer"></div></div></div>
  <div class="fields" id="field-c2"><label><?php echo esc_html($l_sec_code_low); ?>:</label>
   <div class="dfield">
    <input type="text" size="20" name="hdcaptcha_dex_bccf_post" id="hdcaptcha_dex_bccf_post" value="" />
    <div class="cpefb_error message" id="hdcaptcha_error" generated="true" style="display:none;position: absolute; left: 0px; top: 25px;"><?php echo esc_html(dex_bccf_get_option('cv_text_enter_valid_captcha', DEX_BCCF_DEFAULT_dexcv_text_enter_valid_captcha)); ?></div>
    <div class="clearer"></div>
   </div>
  </div>
<?php } ?>
</div>
</div>
<div id="cp_subbtn" class="cp_subbtn"><?php echo esc_html($l_continue); ?></div>
</form>