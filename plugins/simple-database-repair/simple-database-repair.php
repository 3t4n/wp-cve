<?php
/*
Plugin Name: Simple Database Repair
Description: A simple database repair plugin which can be used to repair wordpress database .
Author: Shantanu Mukherjee
Version: 2.3
Author URI: http://phpsquare.com/
License: GPLv2 or later
Text Domain: simple-database-repair
*/
/*  Copyright 2019  PHPSQUARE (email : info@phpsquare.com)

This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
*/
		add_action('admin_menu', 'SDR_add_option');
		include_once(ABSPATH . 'wp-includes/pluggable.php');
		// Add style Function
		function SDR_stylesheet()
        {
	        wp_enqueue_style( 'SDR_css', plugin_dir_url(__FILE__).'css/style.css' );
        }
        add_action( 'admin_enqueue_scripts','SDR_stylesheet' );

		// current user's info 
		$current_user = wp_get_current_user(); 
		if ( !($current_user instanceof WP_User) ) 
    	return; 
		
		function SDR_add_option(){
        	add_menu_page( 'Simple Database Repair', 'Simple Database Repair', 'manage_options',basename(__FILE__),'SDR_manage_update');
		}
 
		function SDR_manage_update(){	

		global $wpdb;
	    $list_of_table = $wpdb->get_results("SHOW TABLE STATUS");
		
		?>
		<table width="95%" align="center" class="db-rp">
			<tr><td><strong>Table Name</strong></td><td align="center"><strong>Status</strong></td><td align="center"><strong>Repair</strong></td></tr>
			<tr><td colspan="2">&nbsp;</td></tr>
			<form name="SDR_form" action="admin.php?page=<?php echo basename(__FILE__); ?>" method="post">
			<?php wp_nonce_field('SDR_submit','SDR_nonce'); ?>

			<?php
			foreach($list_of_table as $check) {
			if($check->Engine=='InnoDB')
			{
					$status = "The storage engine for the table doesn't support repair";	
			}
			elseif($check->Data_free>0 && $check->Engine!='InnoDB')
			{
				 	$status = "Overhead";
			}
			else
			{
			   		$status = "Ok";
			}
	
			?>
				<tr><td><?php echo $check->Name; ?></td><td align="center"  <?php if($status=='Ok') {?> bgcolor="#00FF00" <?php } elseif($check->Engine=='InnoDB') { ?> bgcolor="#009900" <?php } else { ?>bgcolor="#FF0000" <?php } ?>><?php echo $status; ?></td><td align="center"><input name="tables[]" type="checkbox" value="<?php echo esc_attr($check->Name); ?>" <?php if($status=='Ok' || $check->Engine=='InnoDB') {?> disabled <?php } ?>></td></tr>
			<?php
			}
			?>
			<tr><td colspan="2" align="center"><input type="submit" name="SDR_form_submit" value="Submit"></td><td>For any queries/questions, please email to info@phpsquare.com . </td></tr>
			
			
		
		<?php
		if ( isset( $_POST['SDR_form_submit'] ) && !check_admin_referer('SDR_submit','SDR_nonce')){	
		
				$table_checked = esc_attr($_POST['tables']);
			
			echo '<div id="message" class="error fade"><p><strong>'.__('ERROR','simple-database-repair').' - '.__('Please try again.','simple-database-repair').'</strong></p></div>';

		}
		elseif( isset( $_POST['SDR_form_submit'] ) && isset($_POST['SDR_nonce']) )
		{
			$table_checked = $_POST['tables'];
			//print_r($table_checked);
			if(!empty($table_checked)) {
			foreach($table_checked as $table)
			{
					$repair_db = $wpdb->query("REPAIR TABLE $table");
					if(!$repair_db) {
						echo '<p style="color: red;">'.esc_html($table).' Table could not be repaired!</p>';
					} else {
						echo '<p style="color: green;align:middle;">'.esc_html($table).' Table is repaired!</p>';
					}
				}
			}
			else
			{
				echo '<p style="color: red;"><strong>'.__('ERROR','simple-database-repair').' - '.__('Please select a table to repair!.','simple-database-repair').'</strong></p>';
			}
			?>
			<script type="text/javascript">
				window.location.href = '<?php echo $_SERVER['PHP_SELF'].'?'.$_SERVER['QUERY_STRING']; ?>';
			</script>
			<?php
		}
		?>
		</form>
		</table>
		<?php
	 }  
?>