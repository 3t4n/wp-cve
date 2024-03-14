<?php
/*
Filename:      repairDB.php                                                 
Description:   repair tables from the database                            
Author:        Moyo 
Change Log:
	July 21, 2011 	[Moyo] created file                                                                                                                                                                                                           
*/



if ('repairDB.php' == basename($_SERVER['SCRIPT_FILENAME']))
	die ('Please do not access this file directly. Thanks!');






### Form Processing 
if(isset($_POST['do'])) {
	global $wpdb;
	// Lets Prepare The Variables
	$repair = $_POST['repair'];

	// Decide What To Do
	switch($_POST['do']) {
		case __('Repair', 'wp-dbmanager'):
			check_admin_referer('wp-dbmanager_repair');
			if(!empty($repair)) {
				foreach($repair as $key => $value) {
					if($value == 'yes') {
						$tables_string .=  '`, `'.$key;
					}
				}
			} else {
				$text = '<font color="red">'.__('No Tables Selected').'</font>';
			}
			
			
			
			$selected_tables = substr($tables_string, 2);
			//$selected_tables .= '`';
			
			$selected_tables = mysql_escape_string($selected_tables);
			if(!empty($selected_tables)) {
				$repair2 = $wpdb->query("REPAIR TABLE $selected_tables");
				if(!$repair2) {
					$text = '<font color="red">'.sprintf(__('Table(s) \'%s\' NOT Repaired'), str_replace('`', '', $selected_tables)).'</font>';					
				} else {
					$text = '<font color="green">'.sprintf(__('Table(s) \'%s\' Repaired'), str_replace('`', '', $selected_tables)).'</font>';
				}
			}
			break;
	}
}


### Show Tables
global $wpdb;
$tables = $wpdb->get_col("SHOW TABLES");
?>
<?php if(!empty($text)) { echo '<!-- Last Action --><div id="message" class="updated fade"><p>'.$text.'</p></div>'; } ?>
<!-- Repair Database -->
<form method="post" action="#">
	<?php wp_nonce_field('wp-dbmanager_repair'); ?>
	<div class="wrap">
		
		<h2><?php _e('Repair Database'); ?></h2>
		<br style="clear" />
		<table class="widefat">
			<thead>
				<tr>
					<th><?php _e('Tables'); ?></th>
					<th><?php _e('Options'); ?></th>
				</tr>
			</thead>
				<?php
					foreach($tables as $table_name) {
						if($no%2 == 0) {
							$style = '';							
						} else {
							$style = ' class="alternate"';
						}
						$no++;
						echo "<tr $style><th align=\"left\" scope=\"row\">$table_name</th>\n";
						echo "<td><input type=\"radio\" id=\"$table_name-no\" name=\"repair[$table_name]\" value=\"no\" />&nbsp;<label for=\"$table_name-no\">".__('No', 'wp-dbmanager')."</label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type=\"radio\" id=\"$table_name-yes\" name=\"repair[$table_name]\" value=\"yes\" checked=\"checked\" />&nbsp;<label for=\"$table_name-yes\">".__('Yes', 'wp-dbmanager').'</label></td></tr>';
					}
				?>
			<tr>
				<td colspan="2" align="left"><input type="submit" name="do" value="<?php _e('Repair'); ?>" class="button-primary" />&nbsp;&nbsp;</td>
			</tr>
		</table>
	</div>
</form>