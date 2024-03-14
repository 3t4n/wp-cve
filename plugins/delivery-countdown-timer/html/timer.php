<?php
defined('ABSPATH') or die();
//date_default_timezone_set('Europe/London');
//date_default_timezone_set('Asia/Calcutta');

$id = !empty($data['cdn_timer_id'])?$data['cdn_timer_id']:'cdn_timer';

$opts = get_option('ct_settings'); 		
$days = explode(",", $opts['ct-days']);

$day = date('N');
$hour = date('H');
$daytime = str_pad($opts['ct-cut-off-time'], 2, 0, STR_PAD_LEFT);
$fulltext = CountdownTimer::nextdayCalculation($id, $days, $opts);
if( $hour < $opts['ct-cut-off-time'] ){ 
	$dateandtime = date('Y/m/d '.$daytime.':00:00');		
}else{  
	$dateandtime = date('Y/m/d '.$daytime.':00:00', strtotime('+1 day'));	
}


if( in_array( $day, $days) ){
?>
<div class="container">
    <div class="countdown-timer-wrap <?php echo @$data['cdn_class']; ?>">
        <div class="wc_countdowntimer"><?php echo $fulltext; ?></div>
    </div>
</div>   
<script>

    if(jQuery("#<?php echo $id; ?>").length>0){
        jQuery("#<?php echo $id; ?>").countdowntimer({
            startDate : "<?php echo date('Y/m/d H:i:s'); ?>",
            dateAndTime : "<?php echo $dateandtime; ?>",
            size : "xs",
            regexpMatchFormat: "([0-9]{1,2}):([0-9]{1,2}):([0-9]{1,2}):([0-9]{1,2})",
            regexpReplaceWith: "<span class='cdn_hrs cdn_hldr'>$2</span>:<span class='cdn_mins cdn_hldr'>$3</span>:<span class='cdn_secs cdn_hldr'>$4</span>"
        });
    };

</script>
<?php } ?>