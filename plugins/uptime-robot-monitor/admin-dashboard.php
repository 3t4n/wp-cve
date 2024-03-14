<?php defined('ABSPATH') or die("No script kiddies please!");

if ( !current_user_can( 'manage_options' ) )  {
		wp_die( __( 'You do not have sufficient permissions to access this page.', 'urpro') );
}

function urpro_dashboard(){
	wp_add_dashboard_widget('urpro-dashboard','Uptime Robot','urpro_admin_dashboard');
}

function urpro_admin_dashboard() {

		global $wpdb;
		$table_name = $wpdb->base_prefix . 'urpro';
		$siteid = urpro_siteid();

	echo '<div>';
	echo '<table style="border-collapse: collapse; padding: 5px;" width="100%"><thead><tr style="text-align: left;">
		<th><b>'.__('Friendly name', 'urpro').'</b></th>
		<th><b>'.__('Duration','urpro').'</b></th>
		<th><b>'.__('Last 24H','urpro').'</b></th>
		<th><b>'.__('7 days','urpro').'</b></th>
		<th><b>'.__('31 days','urpro').'</b></th>
		<th><b>x&#772; '.__('Response time', 'urpro').'</b></th>';
	      echo '</tr></thead><tbody>';

		$oldlist = urpro_monitororder();
	foreach($oldlist as $monitor){
		$uptime = explode("-",urpro_monitordata("custom_uptime_ranges",$monitor));
		echo '<tr style="border-top: 1px solid #e5e5e5;">';
		echo '<td '.urpro_getstyle(urpro_monitordata("status",$monitor),"dashstatus").'>'.urpro_monitordata("friendly_name",$monitor).'</td>';
		$duration = urpro_monitordata("logs",$monitor);
		$duration = end($duration);
		echo '<td>'.urpro_sectotime($duration['duration'],"short").'</td>';
		echo urpro_getstyle($uptime[0],"uptime");
		echo urpro_getstyle($uptime[1],"uptime");
		echo urpro_getstyle($uptime[2],"uptime");
			if(is_numeric(urpro_monitordata("average_response_time",$monitor))){
		echo '<td>'.round(urpro_monitordata("average_response_time",$monitor),0).'ms</td>';
			}else{
		echo '<td>0ms</td>';
			}
	}

	echo '</tbody></table></div>';

		$oldlist = urpro_monitororder();
		$alllogs = array();
	foreach($oldlist as $monitor){
		$logs = urpro_monitordata("logs",$monitor);
		foreach($logs as $log){ if($log['type'] != 2 AND $log['type'] != 98 AND $log['datetime'] > strtotime("-1 month",time())){
			$alllogs[] = array('time'=>urpro_timezone($log['datetime']),'all'=> array('monitor'=>$monitor,'log'=>$log));
		}}
	}
		usort($alllogs,urpro_sorter('time'));

	if(count($alllogs) != 0){
	echo '<div style="margin-top: 20px;"><table style="border-collapse: collapse; width: 100%;"><thead><tr style="text-align: left;">
		<th><b>'.__('Monitor', 'urpro').'</b></th>
		<th><b>'.__('Date/time', 'urpro').'</b></th>
		<th><b>'.__('Duration', 'urpro').'</b></th>
		<th><b>'.__('Status', 'urpro').'</b></th>
	        </tr></thead><tbody>';

	 foreach($alllogs as $log){
		$monitor = $log['all']['monitor'];
		$logid = $log['all']['log'];
		echo '<tr style="background: '.urpro_getstyle($logid['type'],"log").';">';
		echo '<td>'.urpro_monitordata("friendly_name",$monitor).'</td>';
		echo '<td>'.date_i18n('j F Y @ H:i:s', $logid['datetime']).'</td>';
		$duration = urpro_monitordata("logs",$monitor);
		$duration = end($duration);
		echo '<td>'.urpro_sectotime($logid['duration'],"medium").'</td>';
		echo '<td>'.urpro_outputs($logid['type'],"log").'</td>';
	 }

	echo '</tbody></table></div>';

	}

}