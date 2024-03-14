<?php

if ( !is_admin() )
{
    echo 'Direct access not allowed.';
    exit;
}

if (!defined('CP_BCCF_CALENDAR_ID'))
    define ('CP_BCCF_CALENDAR_ID',intval($_GET["cal"]));

global $wpdb, $DEX_DEFAULT_COLORS;
$mycalendarrows = $wpdb->get_results( $wpdb->prepare( 'SELECT * FROM '.DEX_BCCF_CONFIG_TABLE_NAME .' WHERE `'.TDE_BCCFCONFIG_ID.'`=%d', CP_BCCF_CALENDAR_ID ) );

if ( 'POST' == $_SERVER['REQUEST_METHOD'] && isset( $_POST['dex_bccf_post_options'] ) )
    echo "<div id='setting-error-settings_updated' class='updated settings-error'> <p><strong>Settings saved.</strong></p></div>";

$current_user = wp_get_current_user();

if (cp_bccf_is_administrator() || $mycalendarrows[0]->conwer == $current_user->ID) {


$request_costs = explode(";",dex_bccf_get_option('request_cost',DEX_BCCF_DEFAULT_COST));
if (!count($request_costs)) $request_costs[0] = DEX_BCCF_DEFAULT_COST;

$request_costs_exploded = "'".esc_js($request_costs[0])."'";
for ($k=1;$k<100;$k++)
   if (isset($request_costs[$k]))
       $request_costs_exploded .= ",'".str_replace("'","\'",$request_costs[$k])."'";
   else
       $request_costs_exploded .= ",'".str_replace("'","\'",$request_costs[0]*($k))."'";

$nonce_un = wp_create_nonce( 'uname_bccf' );


$nonce_ss = wp_create_nonce( 'seasons_bccf' );

?>

<div class="wrap">
<h1>Booking Calendar Contact Form - Manage Calendar Availability</h1>

<input type="button" name="backbtn" value="Back to items list..." onclick="document.location='admin.php?page=dex_bccf.php';">

<form method="post" name="dexconfigofrm" action="">
<input name="dex_bccf_post_options" type="hidden" value="1" />
<input name="dex_item" type="hidden" value="<?php echo intval(CP_BCCF_CALENDAR_ID); ?>" />
<input name="_wpnonce" type="hidden" value="<?php echo esc_attr($nonce_un); ?>" />


<div id="normal-sortables" class="meta-box-sortables">

 <hr />
 <h3>These calendar settings apply only to: <?php echo esc_html($mycalendarrows[0]->uname); ?></h3>

 <div id="metabox_basic_settings" class="postbox" >
  <h3 class='hndle' style="padding:5px;"><span>Calendar Configuration / Administration</span></h3>
  <div class="inside">
     <table class="form-table">
        <tr valign="top">
        <td colspan="5">
          &nbsp; <strong>Use/display calendar in the booking form?</strong><br />
          <?php $option = dex_bccf_get_option('calendar_enabled', DEX_BCCF_DEFAULT_CALENDAR_ENABLED); ?>
          &nbsp; <select name="calendar_enabled">
           <option value="true"<?php if ($option == 'true') echo ' selected'; ?>>Yes, use it in the booking form</option>
           <option value="false"<?php if ($option == 'false') echo ' selected'; ?>>No, ignore it, isn't needed</option>
          </select>
        </td>
        </tr>
     </table>

<?php
    $option_use_calendar = $option;
    $option_overlapped = dex_bccf_get_option('calendar_overlapped', DEX_BCCF_DEFAULT_CALENDAR_OVERLAPPED);
    $calendar_language = dex_bccf_get_option('calendar_language',DEX_BCCF_DEFAULT_CALENDAR_LANGUAGE);
    
    if ($calendar_language == '') $calendar_language = dex_bccf_autodetect_language();  
    
    $calendar_dateformat = dex_bccf_get_option('calendar_dateformat',DEX_BCCF_DEFAULT_CALENDAR_DATEFORMAT);
    $dformat = ((dex_bccf_get_option('calendar_dateformat', DEX_BCCF_DEFAULT_CALENDAR_DATEFORMAT)==0)?"mm/dd/yy":"dd/mm/yy");
    $dformat_php = ((dex_bccf_get_option('calendar_dateformat', DEX_BCCF_DEFAULT_CALENDAR_DATEFORMAT)==0)?"m/d/Y":"d/m/Y");
    $calendar_mindate = "";
    $value = dex_bccf_get_option('calendar_mindate',DEX_BCCF_DEFAULT_CALENDAR_MINDATE);
    if ($value != '') $calendar_mindate = date($dformat_php, strtotime($value));
    $calendar_maxdate = "";
    $value = dex_bccf_get_option('calendar_maxdate',DEX_BCCF_DEFAULT_CALENDAR_MAXDATE);
    if ($value != '') $calendar_maxdate = date($dformat_php, strtotime($value));
    if ($option_use_calendar == 'false')
    {
?>
   <div style="background-color:#bbffff;width:450px;border: 1px solid black;padding:10px;margin:10px;">
    <strong>Note:</strong> Calendar has been disabled in the field above, so there isn't need to display and edit it.
     <strong>To re-enable</strong> the calendar select that option in the field above and <strong>save the settings</strong> to render the calendar again.
   </div>
<?php
  //  } else if ($option_overlapped == 'true') {
?>
   <!-- <div style="background-color:#ffff55;width:450px;border: 1px solid black;padding:10px;margin:10px;">
    <strong>Note:</strong> Overlapped reservations are enabled below, so you cannot use the calendar to block dates and the booking should be checked in the <a href="admin.php?page=dex_bccf.php&cal=<?php echo CP_BCCF_CALENDAR_ID; ?>&list=1">bookings list area</a>.
   </div>
    -->
<?php } else { ?>


   <script type="text/javascript">
   var pathCalendar = "<?php echo esc_js(cp_bccf_get_site_url(true)); ?>/";
   </script>

   <div id="bccfcaladmin"><div id="cal<?php echo intval(CP_BCCF_CALENDAR_ID); ?>" class="rcalendar"><span style="color:#009900">Loading calendar data...</span></em></div></div>
<?php if ($calendar_language != '') { ?><script type="text/javascript" src="<?php echo plugins_url('js/languages/jquery.ui.datepicker-'.$calendar_language.'.js', __FILE__); ?>"></script><?php } ?>

   <script type="text/javascript">
    jQuery(function(){
    (function($) {   
        $calendarjQuery = jQuery.noConflict();   
        $calendarjQuery(function() {
        $calendarjQuery("#cal<?php echo intval(CP_BCCF_CALENDAR_ID); ?>").rcalendar({"calendarId":<?php echo intval(CP_BCCF_CALENDAR_ID); ?>,
                                            "partialDate":<?php echo esc_js(dex_bccf_get_option('calendar_mode',DEX_BCCF_DEFAULT_CALENDAR_MODE)); ?>,
                                            "defaultColor":'<?php echo esc_js(dex_bccf_get_option('calendar_deselcolor',TDE_BCCFCALENDAR_DEFAULT_SELCOLOR)); ?>',
                                            "partial_defaultColor":'<?php echo esc_js(dex_bccf_get_option('calendar_deselcolor',TDE_BCCFCALENDAR_DEFAULT_SELCOLOR)); ?>',
                                            "defaultSaveColor":'<?php echo esc_js(dex_bccf_get_option('calendar_defrcolor',TDE_BCCFCALENDAR_DEFAULT_SELCOLOR)); ?>',
                                            "edition":true,
                                            //"minDate":"<?php echo $calendar_mindate;?>",
                                            //"maxDate":"<?php echo $calendar_maxdate;?>",
                                            "dformat":"<?php echo esc_js($dformat); ?>",
                                            "language":"<?php echo esc_js($calendar_language); ?>",
                                            "firstDay":<?php echo intval(dex_bccf_get_option('calendar_weekday', DEX_BCCF_DEFAULT_CALENDAR_WEEKDAY)); ?>,
                                            "numberOfMonths":<?php echo 3 /**dex_bccf_get_option('calendar_pages',DEX_BCCF_DEFAULT_CALENDAR_PAGES)*/; ?>
                                            });
       
        });
    })(jQuery);
    });
   </script>

   <div style="clear:both;height:20px" ></div>

<?php if ($option_overlapped == 'true') { ?>
<div style="background-color:#ffffdd;width:450px;border: 1px solid black;padding:10px;margin:10px;">
    <strong>Note:</strong> Overlapped reservations are enabled below and you can use the calendar for blocking dates, however only the blocked dates are shown in the calendar. The bookings should be checked in the <a href="admin.php?page=dex_bccf.php&cal=<?php echo CP_BCCF_CALENDAR_ID; ?>&list=1">bookings list area</a>.
   </div>
<?php } ?>

<?php } ?>



<?php if ($option_use_calendar != 'false') { ?>
   <div id="demo" class="yui-navset"></div>
   <div style="clear:both;height:0px" ></div>

   <table class="form-table" style="width:100%;">
        <tr valign="top">
         <td colspan="4" style="padding:0px;background-color:#E2EFF8;color:#666666;font-weight:bold;text-align:center">
           SETTINGS FOR BOTH ADMIN AND PUBLIC CALENDARS
         </td>
        </tr> 
        <tr valign="top">
        <td colspan="4">

          <div style="float:left;width:200px;">          
            <strong>Calendar language</strong><br />
<?php $v = dex_bccf_get_option('calendar_language',DEX_BCCF_DEFAULT_CALENDAR_LANGUAGE); ?>            
             <select name="calendar_language" id="calendar_language">
<option <?php if ($v == '') echo 'selected'; ?> value=""> - auto-detect - </option>
<option <?php if ($v == 'af') echo 'selected'; ?> value="af">Afrikaans</option>
<option <?php if ($v == 'sq') echo 'selected'; ?> value="sq">Albanian</option>
<option <?php if ($v == 'ar') echo 'selected'; ?> value="ar">Arabic</option>
<option <?php if ($v == 'ar_DZ') echo 'selected'; ?> value="ar_DZ">Arabic (Algeria)</option>
<option <?php if ($v == 'hy_AM') echo 'selected'; ?> value="hy_AM">Armenian</option>
<option <?php if ($v == 'az') echo 'selected'; ?> value="az">Azerbaijani</option>
<option <?php if ($v == 'eu') echo 'selected'; ?> value="eu">Basque</option>
<option <?php if ($v == 'bs_BA') echo 'selected'; ?> value="bs_BA">Bosnian</option>
<option <?php if ($v == 'bg_BG') echo 'selected'; ?> value="bg_BG">Bulgarian</option>
<option <?php if ($v == 'be_BY') echo 'selected'; ?> value="be_BY">Byelorussian (Belarusian)</option>
<option <?php if ($v == 'km') echo 'selected'; ?> value="km">Cambodian</option>
<option <?php if ($v == 'ca') echo 'selected'; ?> value="ca">Catalan</option>
<option <?php if ($v == 'zh_HK') echo 'selected'; ?> value="zh_HK">Chinese (Hong Kong SAR)</option>
<option <?php if ($v == 'zh_CN') echo 'selected'; ?> value="zh_CN">Chinese (PRC)</option>
<option <?php if ($v == 'zh_TW') echo 'selected'; ?> value="zh_TW">Chinese (Taiwan)</option>
<option <?php if ($v == 'hr') echo 'selected'; ?> value="hr">Croatian</option>
<option <?php if ($v == 'cs_CZ') echo 'selected'; ?> value="cs_CZ">Czech</option>
<option <?php if ($v == 'da_DK') echo 'selected'; ?> value="da_DK">Danish</option>
<option <?php if ($v == 'nl_NL') echo 'selected'; ?> value="nl_NL">Dutch</option>
<option <?php if ($v == 'nl_BE') echo 'selected'; ?> value="nl_BE">Dutch - Belgium</option>
<option <?php if ($v == 'en_AU') echo 'selected'; ?> value="en_AU">English (Australia)</option>
<option <?php if ($v == 'en_NZ') echo 'selected'; ?> value="en_NZ">English (New Zealand)</option>
<option <?php if ($v == 'en_GB') echo 'selected'; ?> value="en_GB">English (United Kingdom)</option>
<option <?php if ($v == 'eo_EO') echo 'selected'; ?> value="eo">Esperanto</option>
<option <?php if ($v == 'et') echo 'selected'; ?> value="et">Estonian</option>
<option <?php if ($v == 'fo') echo 'selected'; ?> value="fo">Faeroese</option>
<option <?php if ($v == 'fa_IR') echo 'selected'; ?> value="fa_IR">Farsi</option>
<option <?php if ($v == 'fi') echo 'selected'; ?> value="fi">Finnish</option>
<option <?php if ($v == 'fr_FR') echo 'selected'; ?> value="fr_FR">French</option>
<option <?php if ($v == 'fr_CA') echo 'selected'; ?> value="fr_CA">French (Canada)</option>
<option <?php if ($v == 'fr_CH') echo 'selected'; ?> value="fr_CH">French (Switzerland)</option>
<option <?php if ($v == 'gl_ES') echo 'selected'; ?> value="gl_ES">Galician</option>
<option <?php if ($v == 'ka_GE') echo 'selected'; ?> value="ka_GE">Georgian</option>
<option <?php if ($v == 'de_DE') echo 'selected'; ?> value="de_DE">German</option>
<option <?php if ($v == 'el') echo 'selected'; ?> value="el">Greek</option>
<option <?php if ($v == 'he_IL') echo 'selected'; ?> value="he_IL">Hebrew</option>
<option <?php if ($v == 'hi_IN') echo 'selected'; ?> value="hi_IN">Hindi</option>
<option <?php if ($v == 'hu_HU') echo 'selected'; ?> value="hu_HU">Hungarian</option>
<option <?php if ($v == 'is') echo 'selected'; ?> value="is">Icelandic</option>
<option <?php if ($v == 'id_ID') echo 'selected'; ?> value="id_ID">Indonesian</option>
<option <?php if ($v == 'it_IT') echo 'selected'; ?> value="it_IT">Italian</option>
<option <?php if ($v == 'it_CH') echo 'selected'; ?> value="it_CH">Italian (Switzerland)</option>
<option <?php if ($v == 'ja') echo 'selected'; ?> value="ja">Japanese</option>
<option <?php if ($v == 'kk') echo 'selected'; ?> value="kk">Kazakh</option>
<option <?php if ($v == 'ky') echo 'selected'; ?> value="ky">Kirghiz</option>
<option <?php if ($v == 'ko_KR') echo 'selected'; ?> value="ko_KR">Korean</option>
<option <?php if ($v == 'lv') echo 'selected'; ?> value="lv">Latvian (Lettish)</option>
<option <?php if ($v == 'lt_LT') echo 'selected'; ?> value="lt_LT">Lithuanian</option>
<option <?php if ($v == 'lb') echo 'selected'; ?> value="lb">Luxembourgish</option>
<option <?php if ($v == 'mk_MK') echo 'selected'; ?> value="mk_MK">Macedonian</option>
<option <?php if ($v == 'ms_MY') echo 'selected'; ?> value="ms_MY">Malay</option>
<option <?php if ($v == 'ml_IN') echo 'selected'; ?> value="ml_IN">Malayalam</option>
<option <?php if ($v == 'no') echo 'selected'; ?> value="no">Norwegian</option>
<option <?php if ($v == 'nb_NO') echo 'selected'; ?> value="nb_NO">Norwegian (Bokm&aring;l)</option>
<option <?php if ($v == 'nn') echo 'selected'; ?> value="nn">Norwegian Nynorsk</option>
<option <?php if ($v == 'pl_PL') echo 'selected'; ?> value="pl_PL">Polish</option>
<option <?php if ($v == 'pt_PT') echo 'selected'; ?> value="pt_PT">Portuguese</option>
<option <?php if ($v == 'pt_BR') echo 'selected'; ?> value="pt_BR">Portuguese (Brazil)</option>
<option <?php if ($v == 'rm') echo 'selected'; ?> value="rm">Rhaeto-Romance</option>
<option <?php if ($v == 'ro_RO') echo 'selected'; ?> value="ro_RO">Romanian</option>
<option <?php if ($v == 'ru_RU') echo 'selected'; ?> value="ru_RU">Russian</option>
<option <?php if ($v == 'sr_SR') echo 'selected'; ?> value="sr_SR">Serbian</option>
<option <?php if ($v == 'sk_SK') echo 'selected'; ?> value="sk_SK">Slovak</option>
<option <?php if ($v == 'sl_SI') echo 'selected'; ?> value="sl_SI">Slovenian</option>
<option <?php if ($v == 'es_ES') echo 'selected'; ?> value="es_ES">Spanish</option>
<option <?php if ($v == 'sv_SE') echo 'selected'; ?> value="sv_SE">Swedish</option>
<option <?php if ($v == 'tj') echo 'selected'; ?> value="tj">Tajikistan</option>
<option <?php if ($v == 'ta') echo 'selected'; ?> value="ta">Tamil</option>
<option <?php if ($v == 'th') echo 'selected'; ?> value="th">Thai</option>
<option <?php if ($v == 'tr_TR') echo 'selected'; ?> value="tr_TR">Turkish</option>
<option <?php if ($v == 'uk') echo 'selected'; ?> value="uk">Ukrainian</option>
<option <?php if ($v == 'vi') echo 'selected'; ?> value="vi">Vietnamese</option>
<option <?php if ($v == 'cy_GB') echo 'selected'; ?> value="cy_GB">Welsh/UK</option>
            </select>
          </div>

          <div style="float:left;width:120px;">
             <strong>Start weekday</strong><br />
             <?php $value = dex_bccf_get_option('calendar_weekday',DEX_BCCF_DEFAULT_CALENDAR_WEEKDAY); ?>
             <select name="calendar_weekday">
               <option value="0" <?php if ($value == '0') echo ' selected="selected"'; ?>>Sunday</option>
               <option value="1" <?php if ($value == '1') echo ' selected="selected"'; ?>>Monday</option>
               <option value="2" <?php if ($value == '2') echo ' selected="selected"'; ?>>Tuesday</option>
               <option value="3" <?php if ($value == '3') echo ' selected="selected"'; ?>>Wednesday</option>
               <option value="4" <?php if ($value == '4') echo ' selected="selected"'; ?>>Thursday</option>
               <option value="5" <?php if ($value == '5') echo ' selected="selected"'; ?>>Friday</option>
               <option value="6" <?php if ($value == '6') echo ' selected="selected"'; ?>>Saturday</option>
             </select>
          </div>

          <div style="float:left;width:110px;">
             <strong>Date format</strong><br />
             <select name="calendar_dateformat">
               <option value="0" <?php if ($calendar_dateformat == '0') echo ' selected="selected"'; ?>>mm/dd/yyyy</option>
               <option value="1" <?php if ($calendar_dateformat == '1') echo ' selected="selected"'; ?>>dd/mm/yyyy</option>
             </select>
          </div>

          <div style="float:left;width:205px;">
             <strong><nobr>Accept overlapped reservations?</nobr></strong><br />
             <?php $option = dex_bccf_get_option('calendar_overlapped', DEX_BCCF_DEFAULT_CALENDAR_OVERLAPPED); ?>
             <select name="calendar_overlapped">
              <option value="true"<?php if ($option == 'true') echo ' selected'; ?>>Yes</option>
              <option value="false"<?php if ($option == 'false') echo ' selected'; ?>>No</option>
             </select>
          </div>

        </td>
       </tr>
       <tr>
        <td valign="top" colspan="4">
           <div style="width:190px;float:left"> 
          <strong>Show cost below calendar?</strong><br />
             <?php $value = dex_bccf_get_option('calendar_showcost','1'); ?>
             <select name="calendar_showcost">
               <option value="1" <?php if ($value == '1') echo ' selected="selected"'; ?>>Yes</option>
               <option value="0" <?php if ($value != '1') echo ' selected="selected"'; ?>>No</option>
             </select>            
           </div>            
           <div style="width:140px;float:left"> 
             <strong>Reservation Mode</strong><br />
             <?php $value = dex_bccf_get_option('calendar_mode',DEX_BCCF_DEFAULT_CALENDAR_MODE); ?>
             <select name="calendar_mode">
               <option value="true" <?php if ($value == 'true') echo ' selected="selected"'; ?>>Partial Days</option>
               <option value="false" <?php if ($value == 'false') echo ' selected="selected"'; ?>>Complete Days</option>
             </select>
           </div>  
           <div style="width:500px;float:left;padding-top:10px;"> 
             <em style="font-size:11px;">Complete day means that the first and the last days booked are charged as full days;<br />Partial Day means that they are charged as half-days only.</em>
           </div>  
        </td>
       </tr>
       
      <tr>
        <td valign="top" colspan="4">
           <div style="width:190px;float:left"> 
          <strong>Default reservation color</strong><br />
             <?php $value = dex_bccf_get_option('calendar_defrcolor', TDE_BCCFCALENDAR_DEFAULT_COLOR); ?>           
             <select name="calendar_defrcolor" id="calendar_defrcolor" onchange="dexbccf_updatecolor(this)">
             <?php foreach ($DEX_DEFAULT_COLORS as $color) {?>
               <option value="<?php echo $color; ?>" style="background-color:#<?php echo $color; ?>"<?php if ($value == $color) echo ' selected="selected"'; ?>>#<?php echo $color; ?></option>
             <?php } ?>
             </select> <br />
             <em style="font-size:11px;">* Will apply for new bookings</em>
           </div>             
           <div style="width:140px;float:left"> 
             <strong>Selection color</strong><br />
             <?php $value = dex_bccf_get_option('calendar_deselcolor',TDE_BCCFCALENDAR_DEFAULT_SELCOLOR); ?>           
             <select name="calendar_deselcolor" id="calendar_deselcolor" onchange="dexbccf_updatecolor(this)">
             <?php foreach ($DEX_DEFAULT_COLORS as $color) {?>
               <option value="<?php echo $color; ?>" style="background-color:#<?php echo $color; ?>"<?php if ($value == $color) echo ' selected="selected"'; ?>>#<?php echo $color; ?></option>
             <?php } ?>
             </select>  
           </div>       
             <script type="text/javascript">
              function dexbccf_updatecolor(item) {
                  item.style.backgroundColor = "#"+item.options[item.options.selectedIndex].value;
              }
              dexbccf_updatecolor(document.getElementById("calendar_defrcolor"));
              dexbccf_updatecolor(document.getElementById("calendar_deselcolor"));
             </script>           
        </td>
       </tr>         
 
        <tr valign="top">
         <td colspan="4" style="padding:0px;background-color:#E2EFF8;color:#666666;font-weight:bold;text-align:center">
           PUBLIC CALENDAR MONTHS, WIDTH, HEIGHT
         </td>
        </tr> 
        
       <tr valign="top">
        <td colspan="4">

          <div style="float:left;width:180px;">
            <strong># months displayed:</strong><br />
             <?php $value = dex_bccf_get_option('calendar_pages',DEX_BCCF_DEFAULT_CALENDAR_PAGES); ?>
             <select name="calendar_pages">
               <option value="1" <?php if ($value == '1' || $value == '') echo ' selected="selected"'; ?>>1</option>
               <option value="2" <?php if ($value == '2') echo ' selected="selected"'; ?>>2</option>
               <option value="3" <?php if ($value == '3') echo ' selected="selected"'; ?>>3</option>
               <option value="4" <?php if ($value == '4') echo ' selected="selected"'; ?>>4</option>
               <option value="5" <?php if ($value == '5') echo ' selected="selected"'; ?>>5</option>
               <option value="6" <?php if ($value == '6') echo ' selected="selected"'; ?>>6</option>
               <option value="7" <?php if ($value == '7') echo ' selected="selected"'; ?>>7</option>
               <option value="8" <?php if ($value == '8') echo ' selected="selected"'; ?>>8</option>
               <option value="9" <?php if ($value == '9') echo ' selected="selected"'; ?>>9</option>
               <option value="10" <?php if ($value == '10') echo ' selected="selected"'; ?>>10</option>
               <option value="11" <?php if ($value == '11') echo ' selected="selected"'; ?>>11</option>
               <option value="12" <?php if ($value == '12') echo ' selected="selected"'; ?>>12</option>
             </select>
          </div>         
          
          <div style="float:left;width:350px;">
            <strong>Months in the same row:</strong><br />
             <?php $value = dex_bccf_get_option('calendar_mwidth', '2'); ?>
             <select name="calendar_mwidth">
               <option value="1" <?php if ($value == '1') echo ' selected="selected"'; ?>>1</option>
               <option value="2" <?php if ($value == '2' || $value == '') echo ' selected="selected"'; ?>>2</option>
               <option value="3" <?php if ($value == '3') echo ' selected="selected"'; ?>>3</option>
               <option value="4" <?php if ($value == '4') echo ' selected="selected"'; ?>>4</option>
               <option value="5" <?php if ($value == '5') echo ' selected="selected"'; ?>>5</option>
               <option value="6" <?php if ($value == '6') echo ' selected="selected"'; ?>>6</option>
               <option value="7" <?php if ($value == '7') echo ' selected="selected"'; ?>>7</option>
               <option value="8" <?php if ($value == '8') echo ' selected="selected"'; ?>>8</option>
               <option value="9" <?php if ($value == '9') echo ' selected="selected"'; ?>>9</option>
               <option value="10" <?php if ($value == '10') echo ' selected="selected"'; ?>>10</option>
               <option value="11" <?php if ($value == '11') echo ' selected="selected"'; ?>>11</option>
               <option value="12" <?php if ($value == '12') echo ' selected="selected"'; ?>>12</option>
             </select>  
             <br /><em>* Note: May be overwritten by the min and max width</em>
          </div>
          
          <div class="clearer"></div>
          <br />
          <div style="float:left;width:210px;">
            <strong>Minimum month width:</strong><br />
             <?php $value = dex_bccf_get_option('calendar_minmwidth', '200px'); ?>
             <input name="calendar_minmwidth" type="text" value="<?php echo esc_attr($value); ?>"><br />
             <em>* Example: 200px</em>  
          </div>
          
          <div style="float:left;width:210px;">
            <strong>Maximum month width:</strong><br />
             <?php $value = dex_bccf_get_option('calendar_maxmwidth', '400px'); ?>
             <input name="calendar_maxmwidth" type="text" value="<?php echo esc_attr($value); ?>"><br />
             <em>* Example: 400px</em>  
          </div>
          

          <div style="float:left;width:210px;">
            <strong>Calendar height level:</strong><br />
             <?php $value = dex_bccf_get_option('calendar_height', '1'); ?>
             <select name="calendar_height">
               <option value="1" <?php if ($value == '1' || $value == '') echo ' selected="selected"'; ?>>1 - Minimun</option>
               <option value="2" <?php if ($value == '2') echo ' selected="selected"'; ?>>2</option>
               <option value="3" <?php if ($value == '3') echo ' selected="selected"'; ?>>3</option>
               <option value="4" <?php if ($value == '4') echo ' selected="selected"'; ?>>4</option>
               <option value="5" <?php if ($value == '5') echo ' selected="selected"'; ?>>5 - Medium</option>
               <option value="6" <?php if ($value == '6') echo ' selected="selected"'; ?>>6</option>
               <option value="7" <?php if ($value == '7') echo ' selected="selected"'; ?>>7</option>
               <option value="8" <?php if ($value == '8') echo ' selected="selected"'; ?>>8</option>
               <option value="9" <?php if ($value == '9') echo ' selected="selected"'; ?>>9</option>
               <option value="10" <?php if ($value == '10') echo ' selected="selected"'; ?>>10  - Maximun</option>
             </select>
          </div>          


          </td>
          </tr>
        
        <tr valign="top">
         <td colspan="4" style="padding:0px;background-color:#E2EFF8;color:#666666;font-weight:bold;text-align:center">
           SETTINGS FOR PUBLIC CALENDAR
         </td>
        </tr> 
               
       <tr>
        <td width="1%" nowrap valign="top" colspan="2">
         <strong>Minimum  available date:</strong><br />
         <input type="text" onchange="bccf_checkdatemin()" id="calendar_mindate" name="calendar_mindate" size="40" value="<?php echo esc_attr(dex_bccf_get_option('calendar_mindate',DEX_BCCF_DEFAULT_CALENDAR_MINDATE)); ?>" /><br />
         <em style="font-size:11px;">Examples: 2016-02-25, today, today + 3 days</em>
         <div id="abcmindateval" style="font-weight:bold"></div>
        </td>
        <td valign="top" colspan="2">
         <strong>Maximum  available date:</strong><br />
         <input type="text" onchange="bccf_checkdatemax()" id="calendar_maxdate" name="calendar_maxdate" size="40" value="<?php echo esc_attr(dex_bccf_get_option('calendar_maxdate',DEX_BCCF_DEFAULT_CALENDAR_MAXDATE)); ?>" /><br />
         <em style="font-size:11px;">Examples: 2016-02-27, today, today + 3 days</em>
         <div id="abcmaxdateval" style="font-weight:bold"></div>
        </td>
        </tr>

       <tr>
        <td width="1%" nowrap valign="top" colspan="2">
         <strong>Minimum number of nights to be booked:</strong><br />
         <input type="text" name="calendar_minnights" size="40" value="<?php $v = dex_bccf_get_option('calendar_minnights', '0'); echo esc_attr(($v==''?'0':$v)); ?>" /><br />
         <em style="font-size:11px;">The booking form won't accept less than the above nights</em>
        </td>
        <td valign="top" colspan="2">
         <strong>Maximum number of nights to be booked:</strong><br />
         <input type="text" name="calendar_maxnights" size="40" value="<?php $v = dex_bccf_get_option('calendar_maxnights','365'); echo esc_attr(($v==''?'365':$v)); ?>" /><br />
         <em style="font-size:11px;">The booking form won't accept more than the above nights</em>
        </td>
       </tr>

       <tr>
        <td width="1%" nowrap valign="top" colspan="2">
         <strong>Working dates</strong>
         <div id="workingdates">
         <?php $cfmode = dex_bccf_get_option('calendar_holidaysdays', '1111111'); if ($cfmode == '') $cfmode = '1111111'; ?>
         <input type="checkbox" class="wdCheck" value="1" name="wd1" <?php echo ($cfmode[0]=='1'?'checked="checked"':''); ?> /> Su &nbsp; &nbsp; 
         <input type="checkbox" class="wdCheck" value="1" name="wd2" <?php echo ($cfmode[1]=='1'?'checked="checked"':''); ?> /> Mo &nbsp; &nbsp; 
         <input type="checkbox" class="wdCheck" value="1" name="wd3" <?php echo ($cfmode[2]=='1'?'checked="checked"':''); ?> /> Tu &nbsp; &nbsp; 
         <input type="checkbox" class="wdCheck" value="1" name="wd4" <?php echo ($cfmode[3]=='1'?'checked="checked"':''); ?> /> We &nbsp; &nbsp;
         <input type="checkbox" class="wdCheck" value="1" name="wd5" <?php echo ($cfmode[4]=='1'?'checked="checked"':''); ?> /> Th &nbsp; &nbsp; 
         <input type="checkbox" class="wdCheck" value="1" name="wd6" <?php echo ($cfmode[5]=='1'?'checked="checked"':''); ?> /> Fr &nbsp; &nbsp; 
         <input type="checkbox" class="wdCheck" value="1" name="wd7" <?php echo ($cfmode[6]=='1'?'checked="checked"':''); ?> /> Sa &nbsp; &nbsp; 
         <br />
         <em style="font-size:11px;">Working dates are the dates that accept bookings.</em>
         </div>
         <br />
         <div><strong>Start Reservation Date</strong></div>
         <div>
         <?php $cfmode = dex_bccf_get_option('calendar_startresdays', '1111111'); if ($cfmode == '') $cfmode = '1111111'; ?>
         <input type="checkbox" class="srCheck" value="1" name="sd1" id="c0" <?php echo ($cfmode[0]=='1'?'checked="checked"':''); ?> /> Su &nbsp; &nbsp; 
         <input type="checkbox" class="srCheck" value="1" name="sd2" id="c1" <?php echo ($cfmode[1]=='1'?'checked="checked"':''); ?> /> Mo &nbsp; &nbsp; 
         <input type="checkbox" class="srCheck" value="1" name="sd3" id="c2" <?php echo ($cfmode[2]=='1'?'checked="checked"':''); ?> /> Tu &nbsp; &nbsp; 
         <input type="checkbox" class="srCheck" value="1" name="sd4" id="c3" <?php echo ($cfmode[3]=='1'?'checked="checked"':''); ?> /> We &nbsp; &nbsp; 
         <input type="checkbox" class="srCheck" value="1" name="sd5" id="c4" <?php echo ($cfmode[4]=='1'?'checked="checked"':''); ?> /> Th &nbsp; &nbsp; 
         <input type="checkbox" class="srCheck" value="1" name="sd6" id="c5" <?php echo ($cfmode[5]=='1'?'checked="checked"':''); ?> /> Fr &nbsp; &nbsp; 
         <input type="checkbox" class="srCheck" value="1" name="sd7" id="c6" <?php echo ($cfmode[6]=='1'?'checked="checked"':''); ?> /> Sa &nbsp; &nbsp; 
         <br /><em style="font-size:11px;">Use this for allowing specific weekdays as start of the reservation.</em>
         </div> 
         <br />
         <div style="background:#E2EFF8;border: 1px dotted #888888;padding:10px;">
             <div><strong><input type="checkbox" value="1" name="calendar_fixedmode" <?php echo esc_attr((dex_bccf_get_option('calendar_fixedmode', '')=='1'?'checked="checked"':'')); ?> id="fixedreservation"> Enable Fixed Reservation Length?</strong>
                 <br />&nbsp;&nbsp;&nbsp;&nbsp; <em style="font-size:11px;">Use this for allowing only bookings of a specific number of days.</em>
             </div>
             <div id="container_fixedreservation" <?php echo (dex_bccf_get_option('calendar_fixedmode', '')=='1'?'':'style="display:none"'); ?>>
                 <br />
                 <?php $v = dex_bccf_get_option('calendar_fixedreslength','1'); ?>
                 Fixed reservation length (days):
                 <select name="calendar_fixedreslength" id="calendar_fixedreslength">
                  <?php for ($k=1;$k<30;$k++) echo '<option value="'.$k.'"'.($k.""==$v?' selected ':'').'>'.$k.'</option>'; ?>
                 </select>
                 <br /><br />
                 

             </div>
         </div>
         <input type="hidden" name="calendar_holidays" id="holidays" value="<?php echo esc_attr(dex_bccf_get_option('calendar_holidays','')); ?>" />
         <input type="hidden" name="calendar_startres" id="startreservation" value="<?php echo esc_attr(dex_bccf_get_option('calendar_startres','')); ?>" />
        </td>
        <td  valign="top" colspan="2">
          <strong>Disabled and special dates (see legend below):</strong>
          <div id="bccfcaladmin" style="width:300px;"><div id="calConfig" class="rcalendar"><em><span style="color:#009900">Loading calendar data...</span></em></div></div>
          
          <div style="margin-top:5px;margin-left:10px;"><div style="float:left;width:20px;height:20px;margin-right:10px;background-color:#FEA69A;"></div> <strong>Non-available dates:</strong> Click once to mark the date as non-available.</div>
          <div style="clear:both"></div>
          <div id="startreslegend" style="margin-top:5px;margin-left:10px;"><div style="float:left;width:20px;height:20px;margin-right:10px;background-color:#80BF92;"></div> <strong>Start reservation dates:</strong> Click twice to mark the date as start date.</div>          
          <div style="clear:both"></div>
          <div style="margin-left:35px;"><em style="font-size:11px;">Every time a date is cliked it status changes. Click it to mark/unmark it.</em></div>
        </td>
       </tr>

   </table>
<?php } else { ?>
    <input type="hidden" name="calendar_language" value="<?php echo esc_attr(dex_bccf_get_option('calendar_language',DEX_BCCF_DEFAULT_CALENDAR_LANGUAGE)); ?>" />
    <input type="hidden" name="calendar_weekday" value="<?php echo esc_attr(dex_bccf_get_option('calendar_weekday',DEX_BCCF_DEFAULT_CALENDAR_WEEKDAY)); ?>" />
    <input type="hidden" name="calendar_dateformat" value="<?php echo esc_attr(dex_bccf_get_option('calendar_dateformat',DEX_BCCF_DEFAULT_CALENDAR_DATEFORMAT)); ?>" />
    <input type="hidden" name="calendar_overlapped" value="<?php echo esc_attr(dex_bccf_get_option('calendar_overlapped', DEX_BCCF_DEFAULT_CALENDAR_OVERLAPPED)); ?>" />
    <input type="hidden" name="calendar_mode" value="<?php echo esc_attr(dex_bccf_get_option('calendar_mode',DEX_BCCF_DEFAULT_CALENDAR_MODE)); ?>" />
    <input type="hidden" name="calendar_mindate" value="<?php echo esc_attr(dex_bccf_get_option('calendar_mindate',DEX_BCCF_DEFAULT_CALENDAR_MINDATE)); ?>" />
    <input type="hidden" name="calendar_maxdate" value="<?php echo esc_attr(dex_bccf_get_option('calendar_maxdate',DEX_BCCF_DEFAULT_CALENDAR_MAXDATE)); ?>" />
<?php } ?>

  </div>
 </div>


 <div id="metabox_basic_settings" class="postbox" >
  <h3 class='hndle' style="padding:5px;"><span>Price Configuration</span></h3>
  <div class="inside">

    <table class="form-table">

        <tr valign="top">
        <th scope="row"><strong>Currency</strong></th>
        <td>
        <?php $currency = strtoupper(esc_attr(dex_bccf_get_option('currency',DEX_BCCF_DEFAULT_CURRENCY))); ?>
<select name="currency" onchange="javascript:cpExplainCurrency(this);">
<option value="USD"<?php if ($currency == 'USD' || $currency == '') echo ' selected'; ?>>USD - U.S. Dollar</option>
<option value="EUR"<?php if ($currency == 'EUR') echo ' selected'; ?>>EUR - Euro</option>
<option value="GBP"<?php if ($currency == 'GBP') echo ' selected'; ?>>GBP - Pound Sterling</option>
<option value="USD"> - </option>
<option value="ARS"<?php if ($currency == 'ARS') echo ' selected'; ?>>ARS - Argentine peso</option>
<option value="AUD"<?php if ($currency == 'AUD') echo ' selected'; ?>>AUD - Australian Dollar</option>
<option value="BRL"<?php if ($currency == 'BRL') echo ' selected'; ?>>BRL - Brazilian Real</option>
<option value="CAD"<?php if ($currency == 'CAD') echo ' selected'; ?>>CAD - Canadian Dollar</option>
<option value="CZK"<?php if ($currency == 'CZK') echo ' selected'; ?>>CZK - Czech Koruna</option>
<option value="DKK"<?php if ($currency == 'DKK') echo ' selected'; ?>>DKK - Danish Krone</option>
<option value="HKD"<?php if ($currency == 'HKD') echo ' selected'; ?>>HKD - Hong Kong Dollar</option>
<option value="HUF"<?php if ($currency == 'HUF') echo ' selected'; ?>>HUF - Hungarian Forint</option>
<option value="ILS"<?php if ($currency == 'ILS') echo ' selected'; ?>>ILS - Israeli New Sheqel</option>
<option value="INR"<?php if ($currency == 'INR') echo ' selected'; ?>>INR - Indian Rupee</option>
<option value="JPY"<?php if ($currency == 'JPY') echo ' selected'; ?>>JPY - Japanese Yen</option>
<option value="MYR"<?php if ($currency == 'MYR') echo ' selected'; ?>>MYR - Malaysian Ringgit</option>
<option value="MXN"<?php if ($currency == 'MXN') echo ' selected'; ?>>MXN - Mexican Peso</option>	
<option value="NOK"<?php if ($currency == 'NOK') echo ' selected'; ?>>NOK - Norwegian Krone</option>	
<option value="NZD"<?php if ($currency == 'NZD') echo ' selected'; ?>>NZD - New Zealand Dollar</option>	
<option value="PHP"<?php if ($currency == 'PHP') echo ' selected'; ?>>PHP - Philippine Peso</option>	
<option value="PLN"<?php if ($currency == 'PLN') echo ' selected'; ?>>PLN - Polish Zloty</option>		
<option value="RUB"<?php if ($currency == 'RUB') echo ' selected'; ?>>RUB - Russian Ruble</option>
<option value="SGD"<?php if ($currency == 'SGD') echo ' selected'; ?>>SGD - Singapore Dollar</option>	
<option value="SEK"<?php if ($currency == 'SEK') echo ' selected'; ?>>SEK - Swedish Krona</option>
<option value="CHF"<?php if ($currency == 'CHF') echo ' selected'; ?>>CHF - Swiss Franc</option>
<option value="TWD"<?php if ($currency == 'TWD') echo ' selected'; ?>>TWD - Taiwan New Dollar</option>
<option value="THB"<?php if ($currency == 'THB') echo ' selected'; ?>>THB - Thai Baht</option>
<option value="USD"<?php if ($currency == 'nocurrency') echo ' selected'; ?>> - Other Currency? -</option>
</select>
<script type="text/javascript">
function cpExplainCurrency(fld)
{
    var sel = fld.options[fld.options.selectedIndex].text;
    if (sel == '- Other Currency? -')
        document.getElementById("cpexplaincurr").style.display = '';
}
</script>
<div id="cpexplaincurr" style="display:none;padding:15px;background-color:#EDF5FF;border:1px solid #808080;">
<p>The currencies listed in this dropdown are the <a href="https://developer.paypal.com/docs/reports/sftp-reports/reference/paypal-supported-currencies/" target="_blank">currencies supported by PayPal</a> to accept payments.</p><br />

<p>The commercial versions of the plugin support all currencies since supports integration with other payment gateways.</p><br />

<p>If you need further information or solution about this currency setting you can <a href="https://bccf.dwbooster.com/contact-us">contact our support service</a>.</p>
</div>
        </td>
        </tr>


        <tr valign="top">
        <th scope="row"><strong>Default request cost (per day)</strong></th>
        <td><input required type="text" step="any" size="5" name="request_cost" value="<?php echo esc_attr($request_costs[0]); ?>" /></td>
        </tr>


        <tr valign="top">
        <th scope="row"><nobr><strong>Total request cost for specific # of days</strong></nobr><br />
          <nobr># of days to setup:
          <?php $option = @intval (dex_bccf_get_option('max_slots', '0')); if ($option=='') $option = 0;  ?>
          <select name="max_slots" onchange="dex_updatemaxslots();">
           <?php for ($k=0; $k<=30; $k++) { ?>
           <option value="<?php echo $k; ?>"<?php if ($option == $k) echo ' selected'; ?>><?php echo $k; ?></option>
           <?php } ?>
          </select></nobr>
        </th>
        <td>
           <div id="cpabcslots">Help: Select the number of days to setup if you want to use this configuration option.<br /><br /></div>
           <div style="clear:both"></div>
           <em style="font-size:11px;">Note: Each box should contain the  TOTAL price for a booking of that length. This will overwrite the default price if the booking length matches some of the specified booking lengths.</em>
        </td>
        </tr>

       <tr>
        <td valign="top" colspan="2">
         <strong>Supplement for bookings between</strong>
         <input type="text" step="any" size="5" name="calendar_suplementminnight" size="40" value="<?php $v = dex_bccf_get_option('calendar_suplementminnight', '0'); echo esc_attr(($v==''?'0':$v)); ?>" />
         <strong>and</strong>
         <input type="text" step="any" size="5" name="calendar_suplementmaxnight" size="40" value="<?php $v = dex_bccf_get_option('calendar_suplementmaxnight', '0'); echo esc_attr(($v==''?'0':$v)); ?>" />
         <strong>nights:</strong>
         <input type="text" step="any" size="5" name="calendar_suplement" size="40" value="<?php $v = dex_bccf_get_option('calendar_suplement', '0'); echo esc_attr(($v==''?'0':$v)); ?>" /><br />
         <em style="font-size:11px;">Suplement will be added once for bookings between those nights.</em>
        </td>
       </tr>
       

        <tr valign="top">
         <td colspan="4" style="padding:3px;background-color:#E2EFF8;color:#666666;font-weight:bold;text-align:left">
           DEPOSIT PAYMENT (OPTIONAL)
         </td>
        </tr>        

       <tr>
        <td valign="top" colspan="2">
        
         <?php $v = dex_bccf_get_option('calendar_depositenable', '0'); if ($v=='') $v = '0'; ?>
         <strong>Enable deposit payment?:</strong>
         <select name="calendar_depositenable">
          <option value="0" <?php if ($v=='0') echo ' selected'; ?>>No</option>
          <option value="1" <?php if ($v=='1') echo ' selected'; ?>>Yes</option>
         </select>
         &nbsp;&nbsp;
         <strong>Deposit Amount:</strong>
         <input type="text" step="any" size="5" name="calendar_depositamount" size="40" value="<?php $v = dex_bccf_get_option('calendar_depositamount', '0'); echo esc_attr(($v==''?'0':$v)); ?>" />
         &nbsp;&nbsp;
         <?php $v = dex_bccf_get_option('calendar_deposittype', '0'); if ($v=='') $v = '0'; ?>
         <strong>Deposit type:</strong>
         <select name="calendar_deposittype">
          <option value="0" <?php if ($v=='0') echo ' selected'; ?>>Percent</option>
          <option value="1" <?php if ($v=='1') echo ' selected'; ?>>Fixed</option>
         </select>
         <br />
         <em style="font-size:11px;">If enabled, the customer will have to pay at PayPal only the deposit amount.</em>
         <br /> 
         
        </td>
       </tr>
       

        <tr valign="top">
         <td colspan="4" style="padding:3px;background-color:#E2EFF8;color:#666666;font-weight:bold;text-align:left">
           SEASONS CONFIGURATION (OPTIONAL)
         </td>
        </tr> 
        
        <tr valign="top">
        <td scope="row" colspan="2">
           <!--<strong>Season cost (per day):</strong>-->
           <div id="dex_noseasons_availmsg">Loading...</div>

           <br />
           <div style="background:#EEF5FB;border: 1px dotted #888888;padding:10px;">
             <strong>Add new season:</strong>
             <br />
             Default Cost for this season: <br /> <input type="text" name="dex_dc_price" id="dex_dc_price" value="" /> <br />
             <div id="cpabcslots_season"></div>
             From: <br /> <input type="text"  size="10" name="dex_dc_season_dfrom" id="dex_dc_season_dfrom" value="" />&nbsp; &nbsp; &nbsp; <br />
             To: <br /> <input type="text"  size="10" name="dex_dc_season_dto" id="dex_dc_season_dto" value="" />&nbsp; &nbsp; &nbsp;<br />
             <input type="button" name="dex_dc_subcseasons" id="dex_dc_subcseasons" value="Add Season" />
             <br />
             <em>Note: Season prices override the "Default request cost" specified above.</em>
           </div>  
        </td>
        </tr>
    </table>

  </div>
 </div>

 <div id="metabox_basic_settings" class="postbox" >
  <h3 class='hndle' style="padding:5px;"><span>Paypal Payment Configuration</span></h3>
  <div class="inside">

    <table class="form-table">
        <tr valign="top">
        <th scope="row">Enable Paypal Payments?</th>
                <td><select name="enable_paypal" onchange="bccp_update_pp_payment_selection();">
             <option value="0" <?php if (!dex_bccf_get_option('enable_paypal',DEX_BCCF_DEFAULT_ENABLE_PAYPAL)) echo 'selected'; ?> >No</option>
             <option value="1" <?php if (dex_bccf_get_option('enable_paypal',DEX_BCCF_DEFAULT_ENABLE_PAYPAL) == '1') echo 'selected'; ?> >Yes</option>
            </select> 
         <em>* For more options (ex: with optional PayPal payments or "pay later" option) please check the <a href="https://bccf.dwbooster.com/download">plugin's page</a>.</em>
        </td>
        </tr>

        <tr valign="top">
        <th scope="row">Paypal email</th>
        <td><input required type="email" name="paypal_email" size="40" value="<?php echo esc_attr(dex_bccf_get_option('paypal_email',dex_bccf_get_default_paypal_email())); ?>" /></td>
        </tr>

        <tr valign="top">
        <th scope="row">Paypal product name</th>
        <td><input required type="text" name="paypal_product_name" size="50" value="<?php echo esc_attr(dex_bccf_get_option('paypal_product_name',DEX_BCCF_DEFAULT_PRODUCT_NAME)); ?>" /></td>
        </tr>

        <tr valign="top">
        <th scope="row">URL to return after successful  payment</th>
        <td><input required type="text" name="url_ok" size="70" value="<?php echo esc_attr(dex_bccf_get_option('url_ok',DEX_BCCF_DEFAULT_OK_URL)); ?>" />
          <br />
          <em>Note: This field is used as the "acknowledgment / thank you message" even if the Paypal feature isn't used.</em>
        </td>
        </tr>

        <tr valign="top">
        <th scope="row">URL to return after an incomplete or cancelled payment</th>
        <td><input type="text" name="url_cancel" size="70" value="<?php echo esc_attr(dex_bccf_get_option('url_cancel',DEX_BCCF_DEFAULT_CANCEL_URL)); ?>" /></td>
        </tr>


        <tr valign="top">
        <th scope="row">Paypal language</th>
        <td><input type="text" name="paypal_language" value="<?php echo esc_attr(dex_bccf_get_option('paypal_language',DEX_BCCF_DEFAULT_PAYPAL_LANGUAGE)); ?>" /></td>
        </tr>

        
        <tr valign="top">
        <th scope="row">Taxes (applied at Paypal)</th>
        <td><input type="text" name="request_taxes" value="<?php echo esc_attr(dex_bccf_get_option('request_taxes','0')); ?>" /></td>
        </tr>

        <tr valign="top">
        <th scope="row">Discount Codes</th>
        <td>
           <em>This feature is available in the <a href="https://bccf.dwbooster.com/download">commercial versions</a>.</em>          
        </td>
        </tr>

     </table>

  </div>
 </div>

 <div id="metabox_basic_settings" class="postbox" >
  <h3 class='hndle' style="padding:5px;"><span>Form Builder</span></h3>
  <div class="inside">
  
     <input type="hidden" name="form_structure_control" id="form_structure_control" value="&quot;&quot;&quot;&quot;&quot;&quot;" />
     <input type="hidden" name="form_structure" id="form_structure" size="180" value="<?php echo str_replace('"','&quot;',str_replace("\r","",str_replace("\n","",esc_attr(dex_bccf_cleanJSON(dex_bccf_get_option('form_structure', DEX_BCCF_DEFAULT_form_structure)))))); ?>" />     

     <script>
         $contactFormPPQuery = jQuery.noConflict();
         jQuery(window).on('load', function(){
             $contactFormPPQuery(document).ready(function() {
                var f = $contactFormPPQuery("#fbuilder").BCCFfbuilder();
                f.fBuild.loadData("form_structure");
             
                $contactFormPPQuery("#saveForm").click(function() {
                    f.fBuild.saveData("form_structure");
                });
             
                $contactFormPPQuery(".itemForm").click(function() {
     	           f.fBuild.addItem($contactFormPPQuery(this).attr("id"));
     	       });
             
               $contactFormPPQuery( ".itemForm" ).draggable({revert1: "invalid",helper: "clone",cursor: "move"});
     	       $contactFormPPQuery( "#fbuilder" ).droppable({
     	           accept: ".button",
     	           drop: function( event, ui ) {
     	               f.fBuild.addItem(ui.draggable.attr("id"));
     	           }
     	       });
             
             });
         });

     </script>

     <div style="background:#fafafa;min-width:780px;" class="form-builder">

         <div class="column width50">
             <div id="tabs">
     			<ul>
     				<li><a href="#tabs-1">Add a Field</a></li>
     				<li><a href="#tabs-2">Field Settings</a></li>
     				<li><a href="#tabs-3">Form Settings</a></li>
     			</ul>
     			<div id="tabs-1">

     			</div>
     			<div id="tabs-2"></div>
     			<div id="tabs-3"></div>
     		</div>
         </div>
         <div class="columnr width50 padding10" id="fbuilder">
             <div id="formheader"></div>
             <div id="fieldlist"></div>
             <div class="button" id="saveForm">Save Form</div>
         </div>
         <div class="clearer"></div>

     </div>
     
  <div style="border:1px dotted black;background-color:#ffffaa;padding-left:15px;padding-right:15px;padding-top:5px;min-width:740px;font-size:12px;color:#000000;"> 
   <p>This version supports the most frequently used field types: "Single Line Text", "Email", "Text-area" and "Acceptance Checkbox".</p>
   <p><button type="button" onclick="window.open('https://bccf.dwbooster.com/download?src=activatebtn');" style="cursor:pointer;height:35px;color:#20A020;font-weight:bold;">Activate the FULL form builder</button>
   <p>The full set of fields also supports:
   <ul>
    <li> - Conditional Logic: Hide/show fields based in previous selections.</li>
    <li> - File uploads, Multi-page forms</li>
    <li> - Publish it as a widget in the sidebar</li>
    <li> - PayPal not required, more calendar options, more booking price settings &amp; payment methods.</li>
    <li> - ...etc</li>
   </ul>
   <p>For time-slot appointment booking options instead range of dates check the <a href="https://wordpress.org/plugins/appointment-hour-booking/">Appointment Hour Booking Calendar</a>.</p>
   </p>
   
  </div>     

  </div>
 </div>
 
 
 <div id="metabox_basic_settings" class="postbox" >
  <h3 class='hndle' style="padding:5px;"><span>Submit Button</span></h3>
  <div class="inside">   
     <table class="form-table">    
        <tr valign="top">
        <th scope="row">Submit button label (text):</th>
        <td><input type="text" name="vs_text_submitbtn" size="40" value="<?php $label = esc_attr(dex_bccf_get_option('vs_text_submitbtn', 'Continue')); echo ($label==''?'Continue':$label); ?>" /></td>
        </tr>
        <tr valign="top">
        <th scope="row">Previous button label (text):</th>
        <td><input type="text" name="vs_text_previousbtn" size="40" value="<?php $label = esc_attr(dex_bccf_get_option('vs_text_previousbtn', 'Previous')); echo ($label==''?'Previous':$label); ?>" /></td>
        </tr>    
        <tr valign="top">
        <th scope="row">Next button label (text):</th>
        <td><input type="text" name="vs_text_nextbtn" size="40" value="<?php $label = esc_attr(dex_bccf_get_option('vs_text_nextbtn', 'Next')); echo ($label==''?'Next':$label); ?>" /></td>
        </tr>  
        <tr valign="top">
        <td colspan="2"> - The  <em>class="pbSubmit"</em> can be used to modify the button styles. <br />
        - The styles can be applied into any of the CSS files of your theme or into the CSS file <em>"booking-calendar-contact-form\css\stylepublic.css"</em>. <br />
        - For further modifications the submit button is located at the end of the file <em>"dex_scheduler.inc.php"</em>.<br />
        - For general CSS styles modifications to the form and samples <a href="https://bccf.dwbooster.com/faq#q82" target="_blank">check this FAQ</a>.
        </tr>
     </table>
  </div>    
 </div> 
  
 

  <div id="metabox_basic_settings" class="postbox" >
  <h3 class='hndle' style="padding:5px;"><span>Validation Texts</span></h3>
  <div class="inside">
     <?php $option = dex_bccf_get_option('vs_use_validation', true); ?>
     <input type="hidden" name="vs_use_validation" value="<?php echo esc_attr($option); ?>" />
     <table class="form-table">
        <tr valign="top">
         <td width="50%" nowrap><strong>"is required" text:</strong><br /><input type="text" required name="vs_text_is_required" style="width:100%" value="<?php echo esc_attr(dex_bccf_get_option('vs_text_is_required', DEX_BCCF_DEFAULT_vs_text_is_required)); ?>" /></td>
         <td><strong>"is email" text:</strong><br /><input type="text" required name="vs_text_is_email" style="width:100%" value="<?php echo esc_attr(dex_bccf_get_option('vs_text_is_email', DEX_BCCF_DEFAULT_vs_text_is_email)); ?>" /></td>
        </tr>
        <tr valign="top">
         <td><strong>"is valid captcha" text:</strong><br /><input type="text" name="cv_text_enter_valid_captcha" style="width:100%" value="<?php echo esc_attr(dex_bccf_get_option('cv_text_enter_valid_captcha', DEX_BCCF_DEFAULT_dexcv_text_enter_valid_captcha)); ?>" /></td>
         <td><strong>"is valid date (mm/dd/yyyy)" text:</strong><br /><input type="text" name="vs_text_datemmddyyyy" style="width:100%" value="<?php echo esc_attr(dex_bccf_get_option('vs_text_datemmddyyyy', DEX_BCCF_DEFAULT_vs_text_datemmddyyyy)); ?>" /></td>
        </tr>
        <tr valign="top">
         <td><strong>"is valid date (dd/mm/yyyy)" text:</strong><br /><input type="text" name="vs_text_dateddmmyyyy" style="width:100%" value="<?php echo esc_attr(dex_bccf_get_option('vs_text_dateddmmyyyy', DEX_BCCF_DEFAULT_vs_text_dateddmmyyyy)); ?>" /></td>
         <td><strong>"is number" text:</strong><br /><input type="text" name="vs_text_number" style="width:100%" value="<?php echo esc_attr(dex_bccf_get_option('vs_text_number', DEX_BCCF_DEFAULT_vs_text_number)); ?>" /></td>
        </tr>
        <tr valign="top">
         <td><strong>"only digits" text:</strong><br /><input type="text" name="vs_text_digits" style="width:100%" value="<?php echo esc_attr(dex_bccf_get_option('vs_text_digits', DEX_BCCF_DEFAULT_vs_text_digits)); ?>" /></td>
         <td><strong>"under maximum" text:</strong><br /><input type="text" name="vs_text_max" style="width:100%" value="<?php echo esc_attr(dex_bccf_get_option('vs_text_max', DEX_BCCF_DEFAULT_vs_text_max)); ?>" /></td>
        </tr>
        <tr valign="top">
         <td><strong>"over minimum" text:</strong><br /><input type="text" name="vs_text_min" style="width:100%" value="<?php echo esc_attr(dex_bccf_get_option('vs_text_min', DEX_BCCF_DEFAULT_vs_text_min)); ?>" /></td>
        </tr>

     </table>
  </div>
 </div>


 <div id="metabox_basic_settings" class="postbox" >
  <h3 class='hndle' style="padding:5px;"><span>Optional Services/Items Field</span></h3>
  <div class="inside">
  <?php for ($k=1;$k<=1; $k++) { ?>
    <fieldset style="border: 1px solid #888888;">
     <legend><strong>Optional Services Field #<?php echo $k; ?></strong></legend>
     <table class="form-table">
        <tr valign="top" colspan="2">
        <th scope="row">
         <?php
           $flabel = dex_bccf_get_option('cp_cal_checkboxes_label'.$k, 'Service');
           if ($flabel == '') $flabel = 'Service';
         ?>
        Field Label: <input type="text" readonly disabled name="cp_cal_checkboxes_label<?php echo $k; ?>" value="<?php echo esc_attr($flabel); ?>" />
        </th>
        </tr>
        <tr valign="top">
        <td colspan="2">
          <strong>If enabled, use the services/items field as:</strong><br />
          <?php $option = dex_bccf_get_option('cp_cal_checkboxes_type'.$k, DEX_BCCF_DEFAULT_CP_CAL_CHECKBOXES_TYPE); ?>
          <select name="cp_cal_checkboxes_type<?php echo $k; ?>">
           <option value="0"<?php if ($option == '0') echo ' selected'; ?>>Additional items field. The item price will be added ONCE to the above prices.</option>
           <option value="4"<?php if ($option == '4') echo ' selected'; ?>>Additional items field per day. The item price will be added for each day to the above prices.</option>
           <option value="1"<?php if ($option == '1') echo ' selected'; ?>>Price per day field. This price will overwrite the above prices.</option>
           <option value="2"<?php if ($option == '2') echo ' selected'; ?>>Fixed price. This price will overwrite the above prices.</option>
          </select>
        </td>
        </tr>
     </table>
     <table class="form-table">
        <tr valign="top">
        <th scope="row" style="width:390px;" >Options (drop-down select, one item per line with format: <span style="color:#ff0000">price | title</span>)<br />
            <textarea style="width:385px;color:#666666;background-color:#efefef;" wrap="on" rows="4" name="cp_cal_checkboxesnok<?php echo $k; ?>" readonly disabled style="color:#999999;">This feature isn't available in this version. Please check the plugin's page for other versions.</textarea>
            <input type="hidden" name="cp_cal_checkboxes<?php echo $k; ?>" value="<?php echo esc_attr(dex_bccf_get_option('cp_cal_checkboxes'.$k, DEX_BCCF_DEFAULT_CP_CAL_CHECKBOXES)); ?>">
        </th>
        <td>
        <em>Note: This is an optional field that appears only if some option is specified.</em>
        <br /><u><strong>Sample Format:</strong></u><br />
        <?php echo str_replace("\n", "<br />", DEX_BCCF_DEFAULT_EXPLAIN_CP_CAL_CHECKBOXES); ?></td>
        </tr>
     </table>
    </fieldset>
  <?php } ?>
  </div>
 </div>

 <div id="metabox_basic_settings" class="postbox" >
  <h3 class='hndle' style="padding:5px;"><span>Notification Settings for Administrator(s)</span></h3>
  <div class="inside">
     <table class="form-table">
        <tr valign="top">
        <th scope="row">Notification "from" email</th>
        <td><input required type="email" name="notification_from_email" size="40" value="<?php echo esc_attr(dex_bccf_get_option('notification_from_email', dex_bccf_get_default_from_email() )); ?>" /></td>
        </tr>
        <tr valign="top">
        <th scope="row">Send notification to email</th>
        <td><input required type="text" name="notification_destination_email" size="40" value="<?php echo esc_attr(dex_bccf_get_option('notification_destination_email', dex_bccf_get_default_paypal_email())); ?>" />
            <br />
                <em>Note: Comma separated list for adding more than one email address<em>
            
            </td>
        </tr>
        <tr valign="top">
        <th scope="row">Email subject notification to admin</th>
        <td><input type="text" name="email_subject_notification_to_admin" size="70" value="<?php echo esc_attr(dex_bccf_get_option('email_subject_notification_to_admin', DEX_BCCF_DEFAULT_SUBJECT_NOTIFICATION_EMAIL)); ?>" /></td>
        </tr>
        <tr valign="top">
        <th scope="row">Email notification to admin</th>
        <td><textarea cols="70" rows="5" name="email_notification_to_admin"><?php echo esc_textarea(dex_bccf_get_option('email_notification_to_admin', DEX_BCCF_DEFAULT_NOTIFICATION_EMAIL)); ?></textarea></td>
        </tr>
     </table>
  </div>
 </div>


 <div id="metabox_basic_settings" class="postbox" >
  <h3 class='hndle' style="padding:5px;"><span>Email Copy to User</span></h3>
  <div class="inside">
     <table class="form-table">
        <tr valign="top">
        <th scope="row">Email field on the form</th>
        <td><select id="cu_user_email_field" name="cu_user_email_field" def="<?php echo esc_attr(dex_bccf_get_option('cu_user_email_field', DEX_BCCF_DEFAULT_cu_user_email_field)); ?>"></select></td>
        </tr>
        <tr valign="top">
        <th scope="row">Email subject confirmation to user</th>
        <td><input type="text" name="email_subject_confirmation_to_user" size="70" value="<?php echo esc_attr(dex_bccf_get_option('email_subject_confirmation_to_user', DEX_BCCF_DEFAULT_SUBJECT_CONFIRMATION_EMAIL)); ?>" /></td>
        </tr>
        <tr valign="top">
        <th scope="row">Email confirmation to user</th>
        <td><textarea cols="70" rows="5" name="email_confirmation_to_user"><?php echo esc_textarea(dex_bccf_get_option('email_confirmation_to_user', DEX_BCCF_DEFAULT_CONFIRMATION_EMAIL)); ?></textarea></td>
        </tr>
     </table>
  </div>
 </div>


 <div id="metabox_basic_settings" class="postbox" >
  <h3 class='hndle' style="padding:5px;"><span>Captcha Verification</span></h3>
  <div class="inside">
     <table class="form-table">
        <tr valign="top">
        <th scope="row">Use Captcha Verification?</th>
        <td colspan="5">
          <?php $option = dex_bccf_get_option('dexcv_enable_captcha', TDE_BCCFDEFAULT_dexcv_enable_captcha); ?>
          <select name="dexcv_enable_captcha">
           <option value="true"<?php if ($option == 'true') echo ' selected'; ?>>Yes</option>
           <option value="false"<?php if ($option == 'false') echo ' selected'; ?>>No</option>
          </select>
        </td>
        </tr>

        <tr valign="top">
         <th scope="row">Width:</th>
         <td><input type="number" name="dexcv_width" size="10" value="<?php echo esc_attr(dex_bccf_get_option('dexcv_width', TDE_BCCFDEFAULT_dexcv_width)); ?>"  onblur="generateCaptcha();"  /></td>
         <th scope="row">Height:</th>
         <td><input type="number" name="dexcv_height" size="10" value="<?php echo esc_attr(dex_bccf_get_option('dexcv_height', TDE_BCCFDEFAULT_dexcv_height)); ?>" onblur="generateCaptcha();"  /></td>
         <th scope="row">Chars:</th>
         <td><input type="number" name="dexcv_chars" size="10" value="<?php echo esc_attr(dex_bccf_get_option('dexcv_chars', TDE_BCCFDEFAULT_dexcv_chars)); ?>" onblur="generateCaptcha();"  /></td>
        </tr>

        <tr valign="top">
         <th scope="row">Min font size:</th>
         <td><input type="number" name="dexcv_min_font_size" size="10" value="<?php echo esc_attr(dex_bccf_get_option('dexcv_min_font_size', TDE_BCCFDEFAULT_dexcv_min_font_size)); ?>" onblur="generateCaptcha();"  /></td>
         <th scope="row">Max font size:</th>
         <td><input type="number" name="dexcv_max_font_size" size="10" value="<?php echo esc_attr(dex_bccf_get_option('dexcv_max_font_size', TDE_BCCFDEFAULT_dexcv_max_font_size)); ?>" onblur="generateCaptcha();"  /></td>
         <td colspan="2" rowspan="">
           Preview:<br />
             <br />
            <img src="<?php echo plugins_url('/captcha/captcha.php', __FILE__); ?>"  id="captchaimg" alt="security code" border="0"  />
         </td>
        </tr>


        <tr valign="top">
         <th scope="row">Noise:</th>
         <td><input type="number" name="dexcv_noise" size="10" value="<?php echo esc_attr(dex_bccf_get_option('dexcv_noise', TDE_BCCFDEFAULT_dexcv_noise)); ?>" onblur="generateCaptcha();" /></td>
         <th scope="row">Noise Length:</th>
         <td><input type="number" name="dexcv_noise_length" size="10" value="<?php echo esc_attr(dex_bccf_get_option('dexcv_noise_length', TDE_BCCFDEFAULT_dexcv_noise_length)); ?>" onblur="generateCaptcha();" /></td>
        </tr>


        <tr valign="top">
         <th scope="row">Background:</th>
         <td><input type="color" name="dexcv_background" size="10" value="#<?php echo esc_attr(dex_bccf_get_option('dexcv_background', TDE_BCCFDEFAULT_dexcv_background)); ?>" onblur="generateCaptcha();" /></td>
         <th scope="row">Border:</th>
         <td><input type="color" name="dexcv_border" size="10" value="#<?php echo esc_attr(dex_bccf_get_option('dexcv_border', TDE_BCCFDEFAULT_dexcv_border)); ?>" onblur="generateCaptcha();" /></td>
        </tr>

        <tr valign="top">
         <th scope="row">Font:</th>
         <td>
            <select name="dexcv_font" onchange="generateCaptcha();" >
              <option value="font-1.ttf"<?php if ("font-1.ttf" == dex_bccf_get_option('dexcv_font', TDE_BCCFDEFAULT_dexcv_font)) echo " selected"; ?>>Font 1</option>
              <option value="font-2.ttf"<?php if ("font-2.ttf" == dex_bccf_get_option('dexcv_font', TDE_BCCFDEFAULT_dexcv_font)) echo " selected"; ?>>Font 2</option>
              <option value="font-3.ttf"<?php if ("font-3.ttf" == dex_bccf_get_option('dexcv_font', TDE_BCCFDEFAULT_dexcv_font)) echo " selected"; ?>>Font 3</option>
              <option value="font-4.ttf"<?php if ("font-4.ttf" == dex_bccf_get_option('dexcv_font', TDE_BCCFDEFAULT_dexcv_font)) echo " selected"; ?>>Font 4</option>
            </select>
         </td>
        </tr>


     </table>
  </div>
 </div>

  <div id="metabox_basic_settings" class="postbox" >
    <h3 class='hndle' style="padding:5px;"><span>Note</span></h3>
    <div class="inside">
     To insert this form in a post/page, use the dedicated icon
     <?php print '<img hspace="5" src="'.plugins_url('/images/dex_apps.gif', __FILE__).'" alt="'.__('Insert Booking Calendar','booking-calendar-contact-form').'" />';     ?>
     which has been added to your Upload/Insert Menu, just below the title of your Post/Page.
     <br /><br />
    </div>
  </div>

</div>


<p class="submit"><input type="submit" name="submit" id="submit" class="button-primary" value="Save Changes"  /></p>

[<a href="https://wordpress.org/support/plugin/booking-calendar-contact-form#new-post" target="_blank">Support</a>] | [<a href="https://bccf.dwbooster.com" target="_blank">Documentation</a>]
</form>
</div>
<script type="text/javascript">
 function generateCaptcha()
 {
    var d=new Date();
    var f = document.dexconfigofrm;
    var cv_background = f.dexcv_background.value;
	cv_background = cv_background.replace('#','');
	var cv_border = f.dexcv_border.value;
	cv_border = cv_border.replace('#','');    
    var qs = "?width="+f.dexcv_width.value;
    qs += "&height="+f.dexcv_height.value;
    qs += "&letter_count="+f.dexcv_chars.value;
    qs += "&min_size="+f.dexcv_min_font_size.value;
    qs += "&max_size="+f.dexcv_max_font_size.value;
    qs += "&noise="+f.dexcv_noise.value;
    qs += "&noiselength="+f.dexcv_noise_length.value;
    qs += "&bcolor="+cv_background;
    qs += "&border="+cv_border;
    qs += "&font="+f.dexcv_font.options[f.dexcv_font.selectedIndex].value;
    qs += "&rand="+d;
    document.getElementById("captchaimg").src= "<?php echo plugins_url('/captcha/captcha.php', __FILE__); ?>"+qs+"&inAdmin=1";
 }
 generateCaptcha();
 var $j = jQuery.noConflict();
 $j(function() {
 	$j("#dex_dc_expires").datepicker({
                    dateFormat: 'yy-mm-dd'
                 });

 });
  $j(function() {
 	$j("#dex_dc_season_dfrom").datepicker({
                    dateFormat: 'yy-mm-dd'
                 });
    $j("#dex_dc_season_dto").datepicker({
                    dateFormat: 'yy-mm-dd'
                 });
  });
  $j('#dex_noseasons_availmsg').load('<?php echo esc_js(cp_bccf_get_site_url(true)); ?>/?dex_bccf=loadseasonprices&_wpnonce=<?php echo esc_js($nonce_ss); ?>&inAdmin=1&dex_item=<?php echo intval(CP_BCCF_CALENDAR_ID); ?>');
  $j('#dex_dc_subcseasons').click (function() {
                               var code = $j('#dex_dc_price').val();
                               var dfrom = $j('#dex_dc_season_dfrom').val();
                               var dto = $j('#dex_dc_season_dto').val();
                               if (parseFloat(code)+"" != code && parseFloat(code)+"0" != code && parseFloat(code)+"00" != code) { alert('Please enter a price (valid number).'); return; }
                               var f = document.dexconfigofrm;
                               var slots = f.max_slots.options[f.max_slots.selectedIndex].value;
                               for(var i=1; i<=slots; i++)
                                   code += ";"+ $j('#request_cost_season'+i).val();
                               if (dfrom == '') { alert('Please enter an expiration date for the code'); return; }
                               if (dto == '') { alert('Please enter an expiration date for the code'); return; }
                               var params = '&add=1&dto='+encodeURIComponent(dto)+'&dfrom='+encodeURIComponent(dfrom)+'&price='+encodeURIComponent(code);                               
                               $j('#dex_noseasons_availmsg').load('<?php echo cp_bccf_get_site_url(true); ?>/?dex_bccf=loadseasonprices&_wpnonce=<?php echo $nonce_ss; ?>&inAdmin=1&dex_item=<?php echo CP_BCCF_CALENDAR_ID; ?>'+params);
                               $j('#dex_dc_price').val();
                             });
  function dex_delete_season_price(id)
  {
     $j('#dex_noseasons_availmsg').load('<?php echo cp_bccf_get_site_url(true); ?>/?dex_bccf=loadseasonprices&_wpnonce=<?php echo $nonce_ss; ?>&inAdmin=1&dex_item=<?php echo CP_BCCF_CALENDAR_ID; ?>&delete=1&code='+id);
  }

  function showcurrencies()
  {
      document.getElementById("currencyhelp").style.display = "none";
      document.getElementById("currencylist").style.display = "";
  }
  function dex_updatemaxslots()
  {
      try
      {
          var default_request_cost = new Array(<?php echo ($request_costs_exploded); ?>);
          var f = document.dexconfigofrm;
          var slots = f.max_slots.options[f.max_slots.selectedIndex].value;
          var buffer = "";
          var buffer2 = "";
          for(var i=1; i<=slots; i++)
          {
              buffer += '<div id="cpabccost'+i+'" style="float:left;width:70px;font-size:10px;">'+i+' day'+(i>1?'s':'')+':<br />'+
                         '<input type="text" name="request_cost_'+i+'" style="width:40px;" value="'+default_request_cost[i]+'" /></div>';
              buffer2 += '<div id="cpabccost_season'+i+'" style="float:left;width:70px;font-size:10px;">'+i+' day'+(i>1?'s':'')+':<br />'+
                         '<input type="text" name="request_cost_season'+i+'" id="request_cost_season'+i+'" style="width:40px;" value="" /></div>';           
          }               
          if (slots == '0')
              buffer = "<br />&lt;-<em> Select the number of days to setup if you want to use this configuration option.<br /></em>";
          else
              buffer2 = 'Total request cost for specific # of days:<br />'+buffer2+'<div style="clear:both"></div>';  
          document.getElementById("cpabcslots").innerHTML = buffer;
          document.getElementById("cpabcslots_season").innerHTML = buffer2;          
      }
      catch(e)
      {
      }
  }
  dex_updatemaxslots();
  
  function bccf_checkdatemin()                             
  {
     $j('#abcmindateval').load('<?php echo esc_js(cp_bccf_get_site_url(true)); ?>/?dex_bccf=bccf_loadmindate&code='+encodeURIComponent(document.getElementById("calendar_mindate").value));
  }
  
  function bccf_checkdatemax()                             
  {
     $j('#abcmaxdateval').load('<?php echo esc_js(cp_bccf_get_site_url(true)); ?>/?dex_bccf=bccf_loadmaxdate&code='+encodeURIComponent(document.getElementById("calendar_maxdate").value)+'&code2='+encodeURIComponent(document.getElementById("calendar_mindate").value));
  }  
  bccf_checkdatemax();
  bccf_checkdatemin();
  

</script>


<?php } else { ?>
  <br />
  The current user logged in doesn't have enough permissions to edit this calendar. This user can edit only his/her own calendars. Please log in as administrator to get access to all calendars.
<?php } ?>
