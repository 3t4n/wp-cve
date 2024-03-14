<?php if ( !defined('DEX_AUTH_INCLUDE') ) { echo 'Direct access not allowed.'; exit; } ?>
<link href="<?php echo plugins_url('../css/stylepublic.css', __FILE__); ?>" type="text/css" rel="stylesheet" />
<link href="<?php echo plugins_url('../css/cupertino/jquery-ui-1.8.20.custom.css', __FILE__); ?>" type="text/css" rel="stylesheet" />
<link href="<?php echo plugins_url('../css/calendar.css', __FILE__); ?>" type="text/css" rel="stylesheet" />
<form class="cpp_form" name="dex_bccf_pform_allcals" id="dex_bccf_pform_allcals" action="" method="post">
<script>
var pathCalendar = "<?php echo cp_bccf_get_site_url(); ?>/";
</script>
<?php
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
<?php if ($calendar_language != '') { ?><script type="text/javascript" src="<?php echo plugins_url('js/languages/jquery.ui.datepicker-'.$calendar_language.'.js', __FILE__); ?>"></script><?php } ?>
<script type="text/javascript" src="<?php echo plugins_url('../js/jquery.rcalendar.js', __FILE__); ?>"></script>
<?php
  foreach ($myrows as $item)
      echo '<div id="kcalarea'.$item->id.'" class="rcalendar"></div>'; 
?>
<script type="text/javascript">
 var dex_current_calendar_item;
 function dex_do_init(id)
 {
    myjQuery = (typeof myjQuery != 'undefined' ) ? myjQuery : jQuery;
    myjQuery(function(){
    (function($) {
       dex_current_calendar_item = id;
       document.getElementById("kcalarea"+dex_current_calendar_item).style.display = "";        
       $calendarjQuery = myjQuery;    
       $calendarjQuery(function() {
       $calendarjQuery("#kcalarea"+id).rcalendar({"calendarId":-1,
                                                   "partialDate":<?php echo dex_bccf_get_option('calendar_mode',DEX_BCCF_DEFAULT_CALENDAR_MODE); ?>,
                                                   "edition":false,
                                                   "minDate":"<?php echo $calendar_mindate;?>",
                                                   "maxDate":"<?php echo $calendar_maxdate;?>",
                                                   "dformat":"<?php echo $dformat;?>",
                                                   "readonly":true,
                                                   "workingDates":<?php echo $workingdates;?>,
	   			                                "holidayDates":<?php echo $holidayDates;?>,
	   			                                "startReservationWeekly":<?php echo $startReservationWeekly;?>,
	   			                                "startReservationDates":<?php echo $startReservationDates;?>,
	   			                                "fixedReservationDates":<?php echo ((dex_bccf_get_option('calendar_fixedmode', '')=='1'?'true':'false'));?>,
	   			                                "fixedReservationDates_length":<?php echo dex_bccf_get_option('calendar_fixedreslength','1');?>,				                                
                                                   "language":"<?php echo $calendar_language?>",
                                                   "firstDay":<?php echo dex_bccf_get_option('calendar_weekday', DEX_BCCF_DEFAULT_CALENDAR_WEEKDAY); ?>,
                                                   "numberOfMonths":<?php echo dex_bccf_get_option('calendar_pages',2); ?>
                                                   });
       });       
    })(myjQuery);
    });
 }
 dex_do_init(<?php echo $myrows[0]->id; ?>);
</script>

</form>