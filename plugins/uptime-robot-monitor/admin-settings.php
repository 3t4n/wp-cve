<?php defined('ABSPATH') or die("No script kiddies please!");

if ( !current_user_can( 'manage_options' ) )  {
		wp_die( __( 'You do not have sufficient permissions to access this page.', 'urpro') );
}

function urpro_admin_general() {
		global $wpdb;
		$table_name = $wpdb->base_prefix . 'urpro';
		$siteid = urpro_siteid();
	
	if(isset($_POST['urpro_refresh_cache'])){
		$sql = "DELETE FROM ".$table_name." WHERE ur_key LIKE 'cache-%'";
		$wpdb->query($sql);
		$wpdb->update($table_name, array('time' => '1'), array('ur_key' => 'monitorlist'));
		$wpdb->update($table_name, array('time' => '1'), array('ur_key' => 'monitororder'));
		urpro_monitororder();
		wp_redirect($_SERVER['REQUEST_URI']); exit();
	}
	if(isset($_POST['urpro_multisite']) AND urpro_data("multisite","yes") != $_POST['urpro_multisite']){
		$wpdb->update($table_name, array('ur_value' => $_POST['urpro_multisite']), array('ur_key' => 'multisite'));
		wp_redirect($_SERVER['REQUEST_URI']); exit();
	}else{
		if(isset($_POST['urpro_apikey']) AND urpro_data("apikey","no") == ""){
			$wpdb->insert($table_name, array('siteid' => $siteid, 'ur_key' => 'apikey', 'ur_value' => $_POST['urpro_apikey']));
			wp_redirect($_SERVER['REQUEST_URI']); exit();
		}elseif(isset($_POST['urpro_apikey']) AND urpro_data("apikey","no") != $_POST['urpro_apikey']){
			$wpdb->update($table_name, array('ur_value' => $_POST['urpro_apikey']), array('ur_key' => 'apikey', 'siteid' => $siteid));
			$wpdb->update($table_name, array('time' => '0'), array('siteid' => $siteid));
			wp_redirect($_SERVER['REQUEST_URI']); exit();
		}elseif(isset($_POST['urpro_apikey']) AND urpro_data("apikey","no") == $_POST['urpro_apikey']){
			if(isset($_POST['urpro_refresh']) AND urpro_data("refresh","no") == ""){
				wp_schedule_event( current_time( 'timestamp' ), 'hourly', 'urpro_clear_cache' );
				$wpdb->insert($table_name, array('siteid' => $siteid, 'ur_key' => 'refresh', 'ur_value' => $_POST['urpro_refresh']));
			}elseif(isset($_POST['urpro_refresh']) AND urpro_data("refresh","no") != $_POST['urpro_refresh']){
				wp_schedule_event( current_time( 'timestamp' ), 'hourly', 'urpro_clear_cache' );
				$wpdb->update($table_name, array('ur_value' => $_POST['urpro_refresh']), array('ur_key' => 'refresh', 'siteid' => $siteid));
			}
			if(isset($_POST['urpro_offlinetop']) AND urpro_data("offlinetop","no") == ""){
				$wpdb->insert($table_name, array('siteid' => $siteid, 'ur_key' => 'offlinetop', 'ur_value' => $_POST['urpro_offlinetop']));
			}elseif(isset($_POST['urpro_offlinetop']) AND urpro_data("offlinetop","no") != $_POST['urpro_offlinetop']){
				$wpdb->update($table_name, array('ur_value' => $_POST['urpro_offlinetop']), array('ur_key' => 'offlinetop', 'siteid' => $siteid));
			}
			if(isset($_POST['urpro_showlogs']) AND urpro_data("showlogs","no") == ""){
				$wpdb->insert($table_name, array('siteid' => $siteid, 'ur_key' => 'showlogs', 'ur_value' => $_POST['urpro_showlogs']));
			}elseif(isset($_POST['urpro_showlogs']) AND urpro_data("showlogs","no") != $_POST['urpro_showlogs']){
				$wpdb->update($table_name, array('ur_value' => $_POST['urpro_showlogs']), array('ur_key' => 'showlogs', 'siteid' => $siteid));
			}
			wp_redirect($_SERVER['REQUEST_URI']); exit();
		}
	}

	echo '<div class="wrap"><h2>'.__('General Settings', 'urpro').'</h2>';

        echo '<h3>'.__('Plugin settings', 'urpro').'</h3><form method="post"><table class="form-table">  
            <tr>
                <th scope="row"><label for="urpro_multisite">'.__('Individual site settings?', 'urpro').'</label></th>';
			if(urpro_data("multisite","yes") == 0 && is_multisite()){
        	echo '<td><input type="radio" name="urpro_multisite" value="0" CHECKED> '.__('Yes', 'urpro').' &nbsp<input type="radio" name="urpro_multisite" value="1"> '.__('No', 'urpro').'</td>';
			}elseif(is_multisite()){
        	echo '<td><input type="radio" name="urpro_multisite" value="0"> '.__('Yes', 'urpro').' &nbsp<input type="radio" name="urpro_multisite" value="1" CHECKED> '.__('No', 'urpro').'</td>';
			}elseif(is_multisite()){
        	echo '<td><input type="radio" name="urpro_multisite" value="1" CHECKED> '.__('Yes', 'urpro').'</td>';
			}
		echo '<td><span class="description">'.__('If you are running a multisite installation you can choose to use individual settings for each site.', 'urpro').'</span></td>
            </tr>';

	if(is_multisite()){
	echo '<tr>
                <th></th>
                <td><input type="submit" value="'.__('save changes', 'urpro').'" class="button button-primary"></td>
		<td></td>
            </tr>';
	}
	echo '</table>
	<h3>'.__('API settings', 'urpro').'</h3><table class="form-table">
            <tr>
                <th scope="row"><label for="urpro_apikey">Uptime Robot API</label></th>
                <td><input type="text" size="50" name="urpro_apikey" value="'.str_replace(" ","",urpro_data("apikey","no")).'"></td>
		<td><span class="description">'.__('Copy you &#40Main&#41 API Key from the Uptime Robot settings page.', 'uptime-robot-monitor').'</span></td>
            </tr>
	    <tr>
                <th scope="row">'.__('Refresh rate', 'uptime-robot-monitor').'</th>
		<td><select name="urpro_refresh">';
			$refreshrates = array("1" => __('Realtime', 'urpro'),"60" => __('Every minute', 'urpro'),"300" => __('Every 5 minutes', 'urpro'),"900" => __('Every 15 minutes', 'urpro'),"1800" => __('Every 30 minutes', 'urpro'),"3600" => __('Every hour', 'urpro'),"14400" => __('Every 4 hours', 'urpro'),"28800" => __('Every 8 hours', 'urpro'),"43200" => __('Every 12 hours', 'urpro'),"86400" => __('Once a day', 'urpro'));
			foreach($refreshrates as $rate => $text){
				if(urpro_data("refresh","no") == $rate){
					echo '<option value="'.$rate.'" SELECTED>'.$text.'</option>';
				}else{
					echo '<option value="'.$rate.'">'.$text.'</option>';
				}
			}			
		echo '</select> <form method="post"><input type="submit" name="urpro_refresh_cache" value="'.__('clear cache', 'urpro').'" class="button"></form></td>
		<td><span class="description">'.__('How often do you want to connect to the API? Note: Connecting to the API to often might slow down your site a bit.', 'urpro').'</span></td>
            </tr>
            <tr>
                <th></th>
                <td><input type="submit" value="'.__('save changes', 'urpro').'" class="button button-primary"></td>
		<td></td>
            </tr>
           </table>

	<h3>'.__('Other settings', 'urpro').'</h3><table class="form-table">';
         echo '<tr>
                <th scope="row"><label for="urpro_offlinetop">'.__('Offline monitors on top?', 'urpro').'</label></th>';
			if(urpro_data("offlinetop","no") == 1 OR urpro_data("offlinetop","no") == ""){
        	echo '<td><input type="radio" name="urpro_offlinetop" value="1" CHECKED> '.__('Yes', 'urpro').' &nbsp <input type="radio" name="urpro_offlinetop" value="0"> '.__('No', 'urpro').'</td>';
			}else{
        	echo '<td><input type="radio" name="urpro_offlinetop" value="1"> '.__('Yes', 'urpro').' &nbsp <input type="radio" name="urpro_offlinetop" value="0" CHECKED> '.__('No', 'urpro').'</td>';
			}
         echo '</tr>';
         echo '<tr>
                <th scope="row"><label for="urpro_offlinetop">'.__('Show logs table even without logs found?', 'urpro').'</label></th>';
			if(urpro_data("showlogs","no") == 1){
        	echo '<td><input type="radio" name="urpro_showlogs" value="1" CHECKED> '.__('Yes', 'urpro').' &nbsp <input type="radio" name="urpro_showlogs" value="0"> '.__('No', 'urpro').'</td>';
			}else{
        	echo '<td><input type="radio" name="urpro_showlogs" value="1"> '.__('Yes', 'urpro').' &nbsp <input type="radio" name="urpro_showlogs" value="0" CHECKED> '.__('No', 'urpro').'</td>';
			}
         echo '</tr>';
	 echo '<tr><th></th><td><input type="submit" value="'.__('save changes', 'urpro').'" class="button button-primary"></td></tr></table></div></form>';

}