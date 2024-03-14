<?php defined('ABSPATH') or die("No script kiddies please!");

if ( !current_user_can( 'manage_options' ) )  {
		wp_die( __( 'You do not have sufficient permissions to access this page.', 'urpro') );
}

function urpro_admin_styling() {
		global $wpdb;
		$table_name = $wpdb->base_prefix . 'urpro';
		$siteid = urpro_siteid();

		if(isset($_POST['save_style'])){
		  if(urpro_data("style_font","no") != ""){
			$wpdb->update($table_name, array('ur_value' => $_POST['urpro_style_font']), array('ur_key' => 'style_font', 'siteid' => $siteid));
		  }else{
			$wpdb->insert($table_name, array('siteid' => $siteid, 'ur_key' => 'style_font', 'ur_value' => $_POST['urpro_style_font']));
		  }
		  if(urpro_data("style_class","no") != ""){
			$wpdb->update($table_name, array('ur_value' => $_POST['urpro_style_class']), array('ur_key' => 'style_class', 'siteid' => $siteid));
		  }else{
			$wpdb->insert($table_name, array('siteid' => $siteid, 'ur_key' => 'style_class', 'ur_value' => $_POST['urpro_style_class']));
		  }
		  if(urpro_data("style_online","no") != ""){
			$wpdb->update($table_name, array('ur_value' => $_POST['urpro_style_online']), array('ur_key' => 'style_online', 'siteid' => $siteid));
		  }else{
			$wpdb->insert($table_name, array('siteid' => $siteid, 'ur_key' => 'style_online', 'ur_value' => $_POST['urpro_style_online']));
		  }
		  if(urpro_data("style_offline","no") != ""){
			$wpdb->update($table_name, array('ur_value' => $_POST['urpro_style_offline']), array('ur_key' => 'style_offline', 'siteid' => $siteid));
		  }else{
			$wpdb->insert($table_name, array('siteid' => $siteid, 'ur_key' => 'style_offline', 'ur_value' => $_POST['urpro_style_offline']));
		  }
		  if(urpro_data("style_paused","no") != ""){
			$wpdb->update($table_name, array('ur_value' => $_POST['urpro_style_paused']), array('ur_key' => 'style_paused', 'siteid' => $siteid));
		  }else{
			$wpdb->insert($table_name, array('siteid' => $siteid, 'ur_key' => 'style_paused', 'ur_value' => $_POST['urpro_style_paused']));
		  }
		  if(urpro_data("style_chart","no") != ""){
			$wpdb->update($table_name, array('ur_value' => $_POST['urpro_style_chart']), array('ur_key' => 'style_chart', 'siteid' => $siteid));
		  }else{
			$wpdb->insert($table_name, array('siteid' => $siteid, 'ur_key' => 'style_chart', 'ur_value' => $_POST['urpro_style_chart']));
		  }
		  if(urpro_data("style_100","no") != ""){
			$wpdb->update($table_name, array('ur_value' => $_POST['urpro_style_100']), array('ur_key' => 'style_100', 'siteid' => $siteid));
		  }else{
			$wpdb->insert($table_name, array('siteid' => $siteid, 'ur_key' => 'style_100', 'ur_value' => $_POST['urpro_style_100']));
		  }
		  if(urpro_data("style_99999","no") != ""){
			$wpdb->update($table_name, array('ur_value' => $_POST['urpro_style_99999']), array('ur_key' => 'style_99999', 'siteid' => $siteid));
		  }else{
			$wpdb->insert($table_name, array('siteid' => $siteid, 'ur_key' => 'style_99999', 'ur_value' => $_POST['urpro_style_99999']));
		  }
		  if(urpro_data("style_99899","no") != ""){
			$wpdb->update($table_name, array('ur_value' => $_POST['urpro_style_99899']), array('ur_key' => 'style_99899', 'siteid' => $siteid));
		  }else{
			$wpdb->insert($table_name, array('siteid' => $siteid, 'ur_key' => 'style_99899', 'ur_value' => $_POST['urpro_style_99899']));
		  }
		  if(urpro_data("style_99499","no") != ""){
			$wpdb->update($table_name, array('ur_value' => $_POST['urpro_style_99499']), array('ur_key' => 'style_99499', 'siteid' => $siteid));
		  }else{
			$wpdb->insert($table_name, array('siteid' => $siteid, 'ur_key' => 'style_99499', 'ur_value' => $_POST['urpro_style_99499']));
		  }
		  if(urpro_data("style_99500","no") != ""){
			$wpdb->update($table_name, array('ur_value' => $_POST['urpro_style_99500']), array('ur_key' => 'style_99500', 'siteid' => $siteid));
		  }else{
			$wpdb->insert($table_name, array('siteid' => $siteid, 'ur_key' => 'style_99500', 'ur_value' => $_POST['urpro_style_99500']));
		  }
		  if(urpro_data("style_0","no") != ""){
			$wpdb->update($table_name, array('ur_value' => $_POST['urpro_style_0']), array('ur_key' => 'style_0', 'siteid' => $siteid));
		  }else{
			$wpdb->insert($table_name, array('siteid' => $siteid, 'ur_key' => 'style_0', 'ur_value' => $_POST['urpro_style_0']));
		  }
		wp_redirect($_SERVER['REQUEST_URI']); exit();
		}

	echo '<div class="wp-ur-set"><h2>'.__('Styling', 'urpro').'</h2><form method="post">';

	echo '<div class="wp-ur-set"><h2>'.__('General font styling', 'urpro').'</h2>';
	echo '<table class="form-table">
	   	 <tr><th scope="row">'.__('Font color', 'urpro').'</th>
			<td><input type="color" style="width: 160px; height: 27px; border: 0px;" name="urpro_style_font" value="'.urpro_stylecolor("style_font").'"></td> 
			<th scope="row">'.__('Theme table class', 'urpro').'</th>
			<td><input type="text" style="width: 160px; height: 27px; border: 0px;" name="urpro_style_class" value="'.urpro_data("style_class","no").'"></td> 
            	 </tr>
	   	 <tr><th scope="row">'.__('Online', 'urpro').'</th>
			<td><input type="color" style="width: 160px; height: 27px; border: 0px;" name="urpro_style_online" value="'.urpro_stylecolor("style_online").'"></td> 
			<th scope="row">'.__('Offline', 'urpro').'</th>
			<td><input type="color" style="width: 160px; height: 27px; border: 0px;" name="urpro_style_offline" value="'.urpro_stylecolor("style_offline").'"></td> 
		 </tr>
	   	 <tr><th scope="row">'.__('Paused', 'urpro').'</th>
			<td><input type="color" style="width: 160px; height: 27px; border: 0px;" name="urpro_style_paused" value="'.urpro_stylecolor("style_paused").'"></td> 
			<th scope="row">'.__('Response chart color', 'urpro').'</th>
			<td><input type="color" style="width: 160px; height: 27px; border: 0px;" name="urpro_style_chart" value="'.urpro_stylecolor("style_chart").'"></td> 
            	 </tr>
	      </table>';

	echo '<div class="wp-ur-set"><h2>'.__('Uptime styling', 'urpro').'</h2>';
	echo '<table class="form-table">
	   	 <tr><th scope="row">'.__('If', 'urpro').' 100%</th>
			<td><input type="color" style="width: 160px; height: 27px; border: 0px;" name="urpro_style_100" value="'.urpro_stylecolor("style_100").'"></td> 
            	 	<th scope="row">'.__('If', 'urpro').' 99.999%</th>
			<td><input type="color" style="width: 160px; height: 27px; border: 0px;" name="urpro_style_99999" value="'.urpro_stylecolor("style_99999").'"></td> 
            	 </tr>
	   	 <tr><th scope="row">'.__('More then', 'urpro').' 99.899%</th>
			<td><input type="color" style="width: 160px; height: 27px; border: 0px;" name="urpro_style_99899" value="'.urpro_stylecolor("style_99899").'"></td> 
            	 	<th scope="row">'.__('More then', 'urpro').' 99.499%</th>
			<td><input type="color" style="width: 160px; height: 27px; border: 0px;" name="urpro_style_99499" value="'.urpro_stylecolor("style_99499").'"></td> 
            	 </tr>
	   	 <tr><th scope="row">'.__('Less then', 'urpro').' 99.500%</th>
			<td><input type="color" style="width: 160px; height: 27px; border: 0px;" name="urpro_style_99500" value="'.urpro_stylecolor("style_99500").'"></td> 
            	 	<th scope="row">'.__('If', 'urpro').' 0%</th>
			<td><input type="color" style="width: 160px; height: 27px; border: 0px;" name="urpro_style_0" value="'.urpro_stylecolor("style_0").'"></td> 
            	 </tr>
	      </table>';

	echo '<input type="submit" value="'.__('save changes', 'urpro').'" class="button button-primary" name="save_style"></form>';
}