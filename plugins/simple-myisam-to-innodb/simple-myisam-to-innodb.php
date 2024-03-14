<?php
/*
Plugin Name: Simple MyISAM to InnoDB
Description: Using this plugin we can convert MyISAM storage engine type to InnoDB . We always recommend backing up your MySQL database before using this plugin.
Author: Shantanu Mukherjee
Version: 1.4
Author URI: http://phpsquare.com/
License: GPLv2 or later
Text Domain: simple-myisam-to-innodb
*/
/*  Copyright 2020  PHPSQUARE (email : info@phpsquare.com)

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

		add_action('admin_menu', 'SMTI_add_option');
		include_once(ABSPATH . 'wp-includes/pluggable.php');
	
		// Add style Function
		function SMTI_stylesheet()
        {
	        wp_enqueue_style( 'SMTI_css', plugin_dir_url(__FILE__).'css/style.css' );
        }
        add_action( 'admin_enqueue_scripts','SMTI_stylesheet' );

		// current user's info 
		$current_user = wp_get_current_user(); 
		if ( !($current_user instanceof WP_User) ) 
    	return; 
		
		function SMTI_add_option(){
        	add_menu_page( 'Simple MyISAM to InnoDB', 'Simple MyISAM to InnoDB', 'manage_options',basename(__FILE__),'SMTI_manage_update');
		}
 
		function SMTI_manage_update(){	

		global $wpdb;
	    $list_of_table = $wpdb->get_results("SHOW TABLE STATUS");
		
		?>
		<p><strong>We always recommend backing up your MySQL database before using this plugin. For any queries please email at info@phpsquare.com.</strong></p>

		<table width="95%" align="center" class="db-rp">
			<tr><td><strong>Table Name</strong></td><td align="center"><strong>Status</strong></td><td align="center"><strong>Upgrade</strong></td></tr>
			<tr><td colspan="2">&nbsp;</td></tr>
			<form name="SMTI_form" action="admin.php?page=<?php echo basename(__FILE__); ?>" method="post">
			<?php wp_nonce_field('SMTI_submit','SMTI_nonce'); ?>

			<?php
			foreach($list_of_table as $check) {
			
			?>
				<tr><td><?php echo $check->Name; ?></td><td align="center"  <?php if($check->Engine!='InnoDB') { ?> bgcolor="#FF0000" <?php } else { ?> bgcolor="#009900" <?php } ?>><?php echo $check->Engine; ?></td><td align="center"><input name="tables[]" type="checkbox" value="<?php echo esc_attr($check->Name); ?>" <?php if($check->Engine=='InnoDB') {?> disabled <?php } ?>></td></tr>
			<?php
			}
			?>
			<tr><td colspan="2" align="center"><input type="submit" name="SMTI_form_submit" value="Submit"></td></tr>
			
			
		
		<?php
		if ( isset( $_POST['SMTI_form_submit'] ) && !check_admin_referer('SMTI_submit','SMTI_nonce')){	
		
				$table_checked = esc_attr($_POST['tables']);
			
			echo '<div id="message" class="error fade"><p><strong>'.__('ERROR','simple-myisam-to-innodb').' - '.__('Please try again.','simple-myisam-to-innodb').'</strong></p></div>';

		}
		elseif( isset( $_POST['SMTI_form_submit'] ) && isset($_POST['SMTI_nonce']) )
		{
			$table_checked = $_POST['tables'];
			//print_r($table_checked);
			if(!empty($table_checked)) {
			foreach($table_checked as $table)
			{
					$repair_db = $wpdb->query("ALTER TABLE $table ENGINE=INNODB");
					if(!$repair_db) {
						echo '<p style="color: red;">'.esc_html($table).' Engine Type could not be upgraded!</p>';
					} else {
						echo '<p style="color: green;align:middle;">'.esc_html($table).' Engine Type is upgraded!</p>';
					}
				}
			}
			else
			{
				echo '<p style="color: red;"><strong>'.__('ERROR','simple-myisam-to-innodb').' - '.__('Please select a table to upgrade!.','simple-myisam-to-innodb').'</strong></p>';
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