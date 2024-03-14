<?php

  if (!defined('CP_BCCF_CALENDAR_ID'))
     define ('CP_BCCF_CALENDAR_ID',1);
  $myrows = $wpdb->get_results( "SELECT * FROM ".DEX_BCCF_CONFIG_TABLE_NAME );

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

?>

<style type="text/css">
<?php 
  $calendar_mwidth = dex_bccf_get_option('calendar_mwidth', '2');
  $calendar_minmwidth = dex_bccf_get_option('calendar_minmwidth', '250px');
  $calendar_maxmwidth = dex_bccf_get_option('calendar_maxmwidth', '400px');
  $calendar_height = dex_bccf_get_option('calendar_height', '2');
  
  if ($calendar_mwidth == '') $calendar_mwidth = 2;
  echo '#dex_bccf_pform .rcalendar .ui-datepicker-multi .ui-datepicker-group{width:'.floor(100/$calendar_mwidth - 0.1).'% !important}';
  
  if ($calendar_minmwidth == '') $calendar_minmwidth = '200px';
  if (!strpos($calendar_minmwidth,'px') && !strpos($calendar_minmwidth,'%')) $calendar_minmwidth .= 'px';
  echo '#dex_bccf_pform .rcalendar .ui-datepicker-multi .ui-datepicker-group{min-width:'.$calendar_minmwidth.' !important}';
 
  if ($calendar_maxmwidth == '') $calendar_minmwidth = '400px';
  if (!strpos($calendar_maxmwidth,'px') && !strpos($calendar_maxmwidth,'%')) $calendar_maxmwidth .= 'px';
  echo '#dex_bccf_pform .rcalendar .ui-datepicker-multi .ui-datepicker-group{max-width:'.$calendar_maxmwidth.' !important}';
  
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
<div class="cpp_form" name="dex_bccf_pform" id="dex_bccf_pform"><input name="dex_bccf_post" type="hidden" value="1" />
<?php if ($option_calendar_enabled != 'false') { ?>
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
            $holidayDates[]= '"'.$h[$i].'"';
    $holidayDates = "[".implode(",",$holidayDates)."]";

    $h = dex_bccf_get_option('calendar_startres','');
    $h = explode(";",$h);
    $startReservationDates = array();
    for ($i=0;$i<count($h);$i++)
        if ($h[$i]!="")
            $startReservationDates[]= '"'.$h[$i].'"';
    $startReservationDates = "[".implode(",",$startReservationDates)."]";




?>
<div <?php echo (count($myrows) < 2?'style="display:none"':''); ?> id="bccfcalselectionarea">
<?php
  echo $l_calendar.':<br /><select name="dex_item" id="dex_item" onchange="dex_updateItem()">';
  foreach ($myrows as $item)
      echo '<option value='.$atts["instanceId"].'>'.esc_html(__($item->uname,'booking-calendar-contact-form')).'</option>';
  echo '</select>';
?>
<br /><br />
</div>
<div id="bccfcalarea">
<label id="bccfselectdates"><?php echo esc_html($l_select_dates).":"; ?></label>
<?php
  foreach ($myrows as $item)
      echo '<div id="calarea'.$atts["instanceId"].'" class="rcalendar"></div>';
?>
</div>
<?php } else { ?><input name="dex_item" id="dex_item" type="hidden" value="<?php echo esc_attr($myrows[0]->id); ?>" /><?php } ?>

<div id="fbuilder"><div id="fbuilder_<?php echo esc_attr($atts["instanceId"]); ?>"><div id="formheader_<?php echo esc_attr($atts["instanceId"]); ?>"></div><div id="fieldlist_<?php echo esc_attr($atts["instanceId"]); ?>"></div></div></div>

<div id="selddiv" style="font-weight: bold;margin-top:0px;padding-top:0px;padding-right:3px;padding-left:3px;"></div>
<input type="hidden" name="dex_bccf_id" value="<?php echo esc_attr(CP_BCCF_CALENDAR_ID); ?>" />
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
  <?php echo esc_html($l_sec_code); ?>:<br /><img src="<?php echo plugins_url('/captcha/captcha.php?width='.dex_bccf_get_option('dexcv_width', TDE_BCCFDEFAULT_dexcv_width).'&inAdmin=1&height='.dex_bccf_get_option('dexcv_height', TDE_BCCFDEFAULT_dexcv_height).'&letter_count='.dex_bccf_get_option('dexcv_chars', TDE_BCCFDEFAULT_dexcv_chars).'&min_size='.dex_bccf_get_option('dexcv_min_font_size', TDE_BCCFDEFAULT_dexcv_min_font_size).'&max_size='.dex_bccf_get_option('dexcv_max_font_size', TDE_BCCFDEFAULT_dexcv_max_font_size).'&noise='.dex_bccf_get_option('dexcv_noise', TDE_BCCFDEFAULT_dexcv_noise).'&noiselength='.dex_bccf_get_option('dexcv_noise_length', TDE_BCCFDEFAULT_dexcv_noise_length).'&bcolor='.dex_bccf_get_option('dexcv_background', TDE_BCCFDEFAULT_dexcv_background).'&border='.dex_bccf_get_option('dexcv_border', TDE_BCCFDEFAULT_dexcv_border).'&font='.dex_bccf_get_option('dexcv_font', TDE_BCCFDEFAULT_dexcv_font), __FILE__); ?>"  id="dex_bccf_captchaimg" alt="security code" border="0"  /><br />
  <div class="fields" id="field-c2"><label><?php echo esc_html($l_sec_code_low); ?>:</label>
   <div class="dfield">
    <input type="text" size="20" name="hdcaptcha_dex_bccf_post" id="hdcaptcha_dex_bccf_post" value="" />
    <div class="cpefb_error message" id="hdcaptcha_error" generated="true" style="display:none;position: absolute; left: 0px; top: 25px;"><?php echo esc_html(dex_bccf_get_option('cv_text_enter_valid_captcha', DEX_BCCF_DEFAULT_dexcv_text_enter_valid_captcha)); ?></div>
    <div class="clearer"></div>
   </div>
  </div>
<?php } ?>
</div>
<div id="cp_subbtn" class="cp_subbtn"><?php echo esc_html($l_continue); ?></div>

