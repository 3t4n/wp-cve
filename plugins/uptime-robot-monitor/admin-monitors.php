<?php defined('ABSPATH') or die("No script kiddies please!");

if ( !current_user_can( 'manage_options' ) )  {
		wp_die( __( 'You do not have sufficient permissions to access this page.', 'urpro') );
}

function urpro_admin_monitors() {
		global $wpdb;
		$table_name = $wpdb->base_prefix . 'urpro';
		$siteid = urpro_siteid();
	if(isset($_POST['urpro_order']) AND urpro_data("monitororder","no") == ""){
		$wpdb->insert($table_name, array('siteid' => $siteid, 'ur_key' => 'monitororder', 'ur_value' => json_encode($_POST['urpro_order'])));
		wp_redirect($_SERVER['REQUEST_URI']); exit();
	}elseif(isset($_POST['urpro_order'])){
		$wpdb->update($table_name, array('ur_value' => json_encode($_POST['urpro_order'])), array('ur_key' => 'monitororder', 'siteid' => $siteid));
		wp_redirect($_SERVER['REQUEST_URI']); exit();
	}

?><script type="text/javascript">
 function checkAll(ele) {
     var checkboxes = document.getElementsByTagName('input');
     if (ele.checked) {
         for (var i = 0; i < checkboxes.length; i++) {
             if (checkboxes[i].type == 'checkbox') {
                 checkboxes[i].checked = true;
             }
         }
     } else {
         for (var i = 0; i < checkboxes.length; i++) {
             console.log(i)
             if (checkboxes[i].type == 'checkbox') {
                 checkboxes[i].checked = false;
             }
         }
     }
 }

	 jQuery(function($) {
 
		 var smpSortable;
		 var smpSortableInit = function() {

		  smpSortable = $('#sortcontainer').sortable( {
		  accept: 'sortable',
		  onStop: smpSortableInit
	  	  } );
	 	 }
								 
	 // initialize sortable
		 smpSortableInit();
	});
</script><?php

	echo '<form method="post"><div class="wrap"><h2>'.__('Monitor details', 'urpro').'</h2>';
	echo '<table class="widefat" style="border-collapse: collapse;"><thead><tr>
		<th><input type="checkbox" style="margin-left: 0px !important;" name="urpro_order" onchange="checkAll(this)"></th>
		<th><b>'.__('Friendly name', 'urpro').'</b></th>
		<th colspan="2"><b>'.__('Status', 'urpro').'</b></th>
		<th><b>'.__('Type', 'urpro').'</b></th>
		<th><b>'.__('URL', 'urpro').'</b></th>
		<th><b>x&#772; '.__('Response time', 'urpro').'</b></th>
		<th><b>'.__('ID', 'urpro').'</b></th>';
		echo '<th></th>';
	      echo '</tr></thead><tbody id="sortcontainer">';

		$oldlist = urpro_monitororder();
		$fulllist = urpro_api_monitorlist();
		$fulllist = urpro_sortname($fulllist);
	foreach($oldlist as $monitor){
		echo '<tr style="margin-bottom: 25px; border-bottom: 1px dotted grey;">';
		echo '<td><input type="checkbox" name="urpro_order[]" value="'.$monitor.'" CHECKED></td>';
		echo '<td>'.urpro_monitordata("friendly_name",$monitor).'</td>';
		echo '<td '.urpro_getstyle(urpro_monitordata("status",$monitor),"status").'>'.urpro_outputs(urpro_monitordata("status",$monitor),"status").'</td>';
		$duration = urpro_monitordata("logs",$monitor);
		$duration = end($duration);
		echo '<td>'.urpro_sectotime($duration['duration'],"medium").'</td>';
			if(urpro_monitordata("type",$monitor) == "4"){
		echo '<td>'.urpro_outputs(urpro_monitordata("sub_type",$monitor),"sub_type").' ('.urpro_monitordata("port",$monitor).')</td>';
			}else{
		echo '<td>'.urpro_outputs(urpro_monitordata("type",$monitor),"type").'</td>';
			}
		echo '<td>'.urpro_monitordata("url",$monitor).'</td>';
			if(is_numeric(urpro_monitordata("average_response_time",$monitor))){
		echo '<td>'.round(urpro_monitordata("average_response_time",$monitor),0).'ms</td>';
			}else{
		echo '<td>0ms</td>';
			}
		echo '<td><a href="https://uptimerobot.com/dashboard.php#'.$monitor.'" target="_blank" title="'.__('Open Uptime Robot dashboard', 'urpro').'">'.$monitor.'</a></td>';
			$colspan = "9";
		echo '<td style="background-image: url('.plugins_url('img/dragicon.png', __FILE__ ).'); background-repeat: no-repeat; background-size: 100% 100%;"></td></tr>';
			
	}
	foreach($fulllist as $monitor){ if(!in_array($monitor, $oldlist)){
		echo '<tr style="margin-bottom: 25px; border-bottom: 1px dotted grey;">';
		echo '<td><input type="checkbox" name="urpro_order[]" value="'.$monitor.'"></td>';
		echo '<td>'.urpro_monitordata("friendly_name",$monitor).'</td>';
		echo '<td '.urpro_getstyle(urpro_monitordata("status",$monitor),"status").'>'.urpro_outputs(urpro_monitordata("status",$monitor),"status").'</td>';
		$duration = urpro_monitordata("logs",$monitor);
		$duration = end($duration);
		echo '<td>'.urpro_sectotime($duration['duration'],"medium").'</td>';
			if(urpro_monitordata("type",$monitor) == "4"){
		echo '<td>'.urpro_outputs(urpro_monitordata("sub_type",$monitor),"sub_type").' ('.urpro_monitordata("port",$monitor).')</td>';
			}else{
		echo '<td>'.urpro_outputs(urpro_monitordata("type",$monitor),"type").'</td>';
			}
		echo '<td>'.urpro_monitordata("url",$monitor).'</td>';
			if(is_numeric(urpro_monitordata("average_response_time",$monitor))){
		echo '<td>'.round(urpro_monitordata("average_response_time",$monitor),0).'ms</td>';
			}else{
		echo '<td>0ms</td>';
			}
		echo '<td><a href="https://uptimerobot.com/dashboard.php#'.$monitor.'" target="_blank" title="'.__('Open Uptime Robot dashboard', 'urpro').'">'.$monitor.'</a></td>';
			$colspan = "9";
		echo '<td style="background-image: url('.plugins_url('img/dragicon.png', __FILE__ ).'); background-repeat: no-repeat; background-size: 100% 100%;"></td></tr>';
			
	}}

	echo '</tbody>
	      <tfoot><tr>
		<th colspan="'.$colspan.'"><input type="submit" name="save" class="button button-primary" value="'.__('save changes', 'urpro').'"></th>
	      </tr></tfoot></table></div></form>';

}