<?php defined('ABSPATH') or die("No script kiddies please!");

if ( !current_user_can( 'manage_options' ) )  {
		wp_die( __( 'You do not have sufficient permissions to access this page.', 'urpro') );
}

function urpro_admin_responsetimes() {

	echo '<div class="wrap"><h2>'.__('Response times', 'urpro').'</h2>';

?><script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script type="text/javascript">google.charts.load('current', {'packages':['corechart']});</script><?php

$monitors = urpro_monitororder();
$resValue = "";
foreach($monitors as $monitor) {
	$friendlyname = urpro_monitordata("friendly_name",$monitor);
	$responses = array_reverse(urpro_monitordata("response_times",$monitor));
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
    <div id="<?php echo $friendlyname; ?>" style="width: 100%; max-width: 500px; height: 300px; display: inline-block; padding-right: 10px; padding-bottom: 10px;"></div>
  <?php 
	$resValue = "";
}

	echo '</div>';

}