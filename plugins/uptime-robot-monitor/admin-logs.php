<?php defined('ABSPATH') or die("No script kiddies please!");

if ( !current_user_can( 'manage_options' ) )  {
		wp_die( __( 'You do not have sufficient permissions to access this page.', 'urpro') );
}

function urpro_admin_logs() {
		$oldlist = urpro_monitororder();
		$alllogs = array();
	foreach($oldlist as $monitor){
		$logs = urpro_monitordata("logs",$monitor);
		foreach($logs as $log){ if($log['type'] != 2 AND $log['type'] != 98){
			$alllogs[] = array('time'=>$log['datetime'],'all'=> array('monitor'=>$monitor,'log'=>$log));
		}}
	}
		usort($alllogs,urpro_sorter('time'));

	echo '<div class="wrap"><h2>'.__('Log history', 'urpro').'</h2>';
	echo '<table class="widefat" style="border-collapse: collapse;"><thead><tr>
		<th><b>'.__('Status', 'urpro').'</b></th>
		<th><b>'.__('Friendly name', 'urpro').'</b></th>
		<th><b>'.__('Date/time', 'urpro').'</b></th>
		<th><b>'.__('Duration', 'urpro').'</b></th>
		<th><b>'.__('Type', 'urpro').'</b></th>
		<th><b>'.__('URL', 'urpro').'</b></th>
		<th><b>'.__('ID', 'urpro').'</b></th>';
	      echo '</tr></thead><tbody>';

	foreach($alllogs as $log){
		$monitor = $log['all']['monitor'];
		$logid = $log['all']['log'];
		echo '<tr style="margin-bottom: 25px; border-bottom: 1px dotted grey; background: '.urpro_getstyle($logid['type'],"log").';">';
		echo '<td>'.urpro_outputs($logid['type'],"log").'</td>';
		echo '<td>'.urpro_monitordata("friendly_name",$monitor).'</td>';
		echo '<td>'.date('j M y @ H:i:s', urpro_timezone($logid['datetime'])).'</td>';
		$duration = urpro_monitordata("logs",$monitor);
		$duration = end($duration);
		echo '<td>'.urpro_sectotime($logid['duration'],"medium").'</td>';
			if(urpro_monitordata("type",$monitor) == "4"){
		echo '<td>'.urpro_outputs(urpro_monitordata("sub_type",$monitor),"sub_type").' ('.urpro_monitordata("port",$monitor).')</td>';
			}else{
		echo '<td>'.urpro_outputs(urpro_monitordata("type",$monitor),"type").'</td>';
			}
		echo '<td>'.urpro_monitordata("url",$monitor).'</td>';
		echo '<td><a href="https://uptimerobot.com/dashboard.php#'.$monitor.'" target="_blank" title="'.__('Open Uptime Robot dashboard', 'urpro').'">'.$monitor.'</a></td>';
	}

	echo '</tbody></table></div>';

}