<?php defined('ABSPATH') or die("No script kiddies please!");

function urpro_shortcode_uptime( $attr ){
	ob_start();
	
	$defaultdays = array("1","7","30","365");
	$attr = shortcode_atts(array('days' => '1-7-30-365', 'hide' => '0', 'show' => '0', 'monitors' => '0'), $attr);
		if($attr['monitors'] == "0") {
			$monitors =  urpro_monitororder();
		}else{
			$monitors = explode("-",$attr['monitors']);
			$monitors =  urpro_sortoffline($monitors);
		}
		$days = explode("-",$attr['days']);
		if($attr['days'] != "1-7-30-365") {
			foreach($monitors as $monitor){	
			foreach($days as $day){ if(!in_array($day,$defaultdays)) {
				urpro_custmonitorcache($monitor,$day);
			}}
			}
		}
		$show = explode("-",$attr['show']);
		$hide = explode("-",$attr['hide']);

	$output = '<div class="'.urpro_data("style_class","no").'">';
	$output .= '<table width="100%" class="inside">';
		if(!in_array("head",$hide)){
	$output .= '<thead><tr>';
		if(in_array("id",$show)){
	$output .= '<th>'.__('ID','urpro').'</th>';
		}
		if(!in_array("name",$hide)){
	$output .= '<th>'.__('Monitor','urpro').'</th>';
		}
		if(!in_array("status",$hide)){
	$output .= '<th>'.__('Status','urpro').'</th>';
		}
		if(in_array("duration",$show)){
	$output .= '<th>'.__('Duration','urpro').'</th>';
		}
		if(!in_array("type",$hide)){
	$output .= '<th>'.__('Type','urpro').'</th>';
		}
		if(in_array("url",$show)){
	$output .= '<th>'.__('URL','urpro').'</th>';
		}
		if(!in_array("uptime",$hide)){
		foreach($days as $day){
	$output .= '<th>'.urpro_outputs($day,"uptimetitle").'</th>';
		}
		}
	$output .= '</tr></thead>';
		}
	$output .= '<tbody><tr>';

	foreach($monitors as $monitor){
		$uptime = explode("-",urpro_monitordata("custom_uptime_ranges",$monitor));
		if(in_array("id",$show)){
	$output .= '<td>'.urpro_monitordata("id",$monitor).'</td>';
		}
		if(!in_array("name",$hide)){
		$output .= '<td>'.urpro_monitordata("friendly_name",$monitor).'</td>';
		}
		if(!in_array("status",$hide)){
		$output .= '<td '.urpro_getstyle(urpro_monitordata("status",$monitor),"status").'>'.urpro_outputs(urpro_monitordata("status",$monitor),"status").'</td>';
		}
		if(in_array("duration",$show)){
		$duration = urpro_monitordata("logs",$monitor);
		$duration = end($duration);
		$output .= '<td>'.urpro_sectotime($duration['duration'],"medium").'</td>';
		}
		if(!in_array("type",$hide)){
			if(urpro_monitordata("type",$monitor) == "4"){
		$output .= '<td>'.urpro_outputs(urpro_monitordata("sub_type",$monitor),"sub_type").' ('.urpro_monitordata("port",$monitor).')</td>';
			}else{
		$output .= '<td>'.urpro_outputs(urpro_monitordata("type",$monitor),"type").'</td>';
			}
		}
		if(in_array("url",$show)){
		$output .= '<td>'.urpro_monitordata("url",$monitor).'</td>';
		}
		if(!in_array("uptime",$hide)){
		foreach($days as $day){
			if(!in_array($day,$defaultdays)) {
		$output .= urpro_getstyle(urpro_monitordata("custom_uptime_ranges",$day.'-'.$monitor),"uptime");
			}else{
			$uptimes = explode("-",urpro_monitordata("custom_uptime_ranges",$monitor));
			$uptime = array_search($day, $defaultdays);
		$output .= urpro_getstyle($uptimes[$uptime],"uptime");
			}
		}
		}
		$output .= '</tr>';
	}
	$output .= '</tbody></table>';
	$output .= '</div>';

	echo $output;

	return ob_get_clean();
}

function urpro_shortcode_response( $attr ){
	ob_start();

	$attr = shortcode_atts(array('monitors' => '0', 'width' => '45%', 'height' => '300px'), $attr);
		if($attr['monitors'] == "0") {
			$monitors =  urpro_monitororder();
		}else{
			$monitors =  explode("-",$attr['monitors']);
		}
?><script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script type="text/javascript">google.charts.load('current', {'packages':['corechart']});</script><?php

 foreach($monitors as $monitor) {
	$friendlyname = urpro_monitordata("friendly_name",$monitor);
	$responses = array_reverse(urpro_monitordata("response_times",$monitor));
	$resValue = "";
  if(count($responses) == "0"){ $responses = array(array("datetime"=>current_time( 'timestamp' ), "value"=> "0")); }
  foreach($responses as $response) { 
	$resValue = $resValue."['".date_i18n('j M @ G:i', urpro_timezone($response['datetime']))."',".$response['value']."],";
  }
    ?>
	<script type="text/javascript">
      google.charts.setOnLoadCallback(drawChart);
      function drawChart() {
        var data = google.visualization.arrayToDataTable([
          ['Time', 'Milliseconds'],
          <?php echo rtrim($resValue, ","); ?>
        ]);

        var options = {
          title: '<?php echo $friendlyname; ?>',
	  titleTextStyle: { color: '<?php echo urpro_stylecolor("style_font"); ?>' },
	  chartArea: {height: '55%', top: 50, width: '85%', right: 20},
	  colors: ['<?php echo urpro_stylecolor("style_chart"); ?>'],
          vAxis: {minValue: 0},
          hAxis: {showTextEvery: 6, slantedText: 'true', slantedTextAngle: 50, format: 'DD-MM-YYYY hh:mm:ss'},
	  legend: {position: 'none'}
        };

        var chart = new google.visualization.AreaChart(document.getElementById('<?php echo $friendlyname; ?>'));
        chart.draw(data, options);
      }
    </script>
    <div id="<?php echo $friendlyname; ?>" style="width: <?php echo $attr['width']; ?>; height: <?php echo $attr['height']; ?>; display: inline-block; padding-right: 10px; padding-bottom: 10px;"></div>
  <?php 
	$resValue = "";
 }

	return ob_get_clean();
}

function urpro_shortcode_logs($attr){
	ob_start();

	$attr = shortcode_atts(array('days' => '0', 'monitors' => '0'), $attr);
		if($attr['monitors'] == "0") {
			$monitors =  urpro_monitororder();
		}else{
			$monitors =  explode("-",$attr['monitors']);
		}
		if($attr['days'] == "0" OR !is_numeric($attr['days'])) {
			$days = 0;
		}else{
			$days = time()-bcmul($attr['days'],86400);
		}

		$alllogs = array();
	foreach($monitors as $monitor){
		$logs = urpro_monitordata("logs",$monitor);
		foreach($logs as $log){ if($log['type'] != 2 AND $log['type'] != 98 AND $log['datetime'] > $days){
			$alllogs[] = array('time'=>$log['datetime'],'all'=> array('monitor'=>$monitor,'log'=>$log));
		}}
	}
		usort($alllogs,urpro_sorter('time'));

	if(count($alllogs) != 0 OR urpro_data("showlogs","no") == 1){
	echo '<div class="'.urpro_data("style_class","no").'"><table style="border-collapse: collapse; width: 100%;" class="inside"><thead><tr>
		<th><b>'.__('Status', 'urpro').'</b></th>
		<th><b>'.__('Monitor', 'urpro').'</b></th>
		<th><b>'.__('Date/time', 'urpro').'</b></th>
		<th><b>'.__('Duration', 'urpro').'</b></th>
	        </tr></thead><tbody>';

	 if(count($alllogs) == 0){ echo '<tr><td colspan="4"><i>'.__('No recent log history found.','urpro').'</i></td></tr>'; }else{
	 foreach($alllogs as $log){
		$monitor = $log['all']['monitor'];
		$logid = $log['all']['log'];
		echo '<tr style="background: '.urpro_getstyle($logid['type'],"log").';">';
		echo '<td>'.urpro_outputs($logid['type'],"log").'</td>';
		echo '<td>'.urpro_monitordata("friendly_name",$monitor).'</td>';
		echo '<td>'.date_i18n('j F Y @ H:i:s', urpro_timezone($logid['datetime'])).'</td>';
		$duration = urpro_monitordata("logs",$monitor);
		$duration = end($duration);
		echo '<td>'.urpro_sectotime($logid['duration'],"medium").'</td>';
	 }
	 }

	echo '</tbody></table></div>';

	}
	return ob_get_clean();
}

add_shortcode('uptime-robot', 'urpro_shortcode_uptime');
add_shortcode('uptime-robot-logs', 'urpro_shortcode_logs');
add_shortcode('uptime-robot-response', 'urpro_shortcode_response');