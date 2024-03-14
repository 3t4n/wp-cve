<?php 
/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              Https://ecodeblog.com
 * @since             1.0.0
 * @package           Wpdb_Sql
 *
 * @wordpress-plugin
 * Plugin Name:       WPDB to Sql
 * Description:       Plugin helps admin role user to take backup of MYSQL data dump.
 * Version:           1.2
 * Author:            Niket Joshi - Ecodeblog
 * Author URI:        Https://ecodeblog.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       wpdb-sql
 * Domain Path:       /languages
 */

function my_admin_menu() {
	$user_id = get_current_user_id();
	$user_meta = get_userdata($user_id);
	$user_roles = $user_meta->roles;
	$allowed_roles = array('administrator');
	$role = $user_roles ? $user_roles : array('editor');
	if(array_intersect($allowed_roles, $role ) ){
    	add_management_page( 'Export wpdb to SQL - Ecodeblog', 'Export WPDB2SQL', 'manage_options', 'wpdb-sql','display_plugin_setup_page');
	}
}

add_action( 'admin_menu', 'my_admin_menu' );


function display_plugin_setup_page() {
	require('display-wpdb-sql.php');
}

add_action('admin_init', 'exportSql');
function exportSql() {
	if (!empty($_GET['wpdb_sql'])) {
		global $wpdb;
		$host = $wpdb->dbhost;
		$user = $wpdb->dbuser;
		$pass = $wpdb->dbpassword;
		$name = $wpdb->dbname;
		set_time_limit(3000);

		$mysqli = new mysqli($host,$user,$pass,$name); 
		$mysqli->select_db($name); 
		$mysqli->query("SET NAMES 'utf8'");
		$queryTables = $mysqli->query('SHOW TABLES'); 

		while($row = $queryTables->fetch_row()) { 
			$target_tables[] = $row[0]; 
		}	

		if($tables != false) { 
			$target_tables = array_intersect( $target_tables, $tables); 
		} 
	
		$content = "SET SQL_MODE = \"NO_AUTO_VALUE_ON_ZERO\";\r\nSET time_zone = \"+00:00\";\r\n\r\n\r\n/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;\r\n/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;\r\n/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;\r\n/*!40101 SET NAMES utf8 */;\r\n--\r\n-- Database: `".$name."`\r\n--\r\n\r\n\r\n";

		foreach($target_tables as $table){
			if (empty($table)){ continue; }
			$result	= $mysqli->query('SELECT * FROM `'.$table.'`');
			  	$fields_amount=$result->field_count;  
			  	$rows_num=$mysqli->affected_rows; 	$res = $mysqli->query('SHOW CREATE TABLE '.$table);	$TableMLine=$res->fetch_row(); 
			$content .= "\n\n".$TableMLine[1].";\n\n";   $TableMLine[1]=str_ireplace('CREATE TABLE `','CREATE TABLE IF NOT EXISTS `',$TableMLine[1]);
			for ($i = 0, $st_counter = 0; $i < $fields_amount;   $i++, $st_counter=0) {
				while($row = $result->fetch_row())	{ //when started (and every after 100 command cycle):
					if ($st_counter%100 == 0 || $st_counter == 0 )	{
						$content .= "\nINSERT INTO ".$table." VALUES";
					}
					
					$content .= "\n(";    for($j=0; $j<$fields_amount; $j++){ 
						$row[$j] = str_replace("\n","\\n", addslashes($row[$j]) ); 
						if (isset($row[$j])){
							$content .= '"'.$row[$j].'"' ;
						}else{
							$content .= '""';
						}

						if ($j<($fields_amount-1)){
							$content.= ',';
						}   
					}
					$content .=")";
					
					//every after 100 command cycle [or at last line] ....p.s. but should be inserted 1 cycle eariler
					if ( (($st_counter+1)%100==0 && $st_counter!=0) || $st_counter+1==$rows_num) {
						$content .= ";";
					}else{
						$content .= ",";
					}
					$st_counter=$st_counter+1;
				}
			} 
			$content .="\n\n\n";
		}
		$content .= "\r\n\r\n/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;\r\n/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;\r\n/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;";
		if($_POST['opt_select'] == 'text'){
			echo "<pre>";
			/*echo '<a href="'.admin_url('tools.php?page=wpdb-sql').'">Click here to Go Back</a><br/><br/>';*/
			echo $content;
			exit;
		}elseif($_POST['opt_select'] == 'sql'){
			$backup_name = $backup_name ? $backup_name : $name.'___('.date('H-i-s').'_'.date('d-m-Y').').sql';
			ob_get_clean();
			$backup_name = $backup_name ? $backup_name : $name.".sql";
	        header('Content-Type: application/octet-stream');
	        header("Content-Transfer-Encoding: Binary");
	        header("Content-disposition: attachment; filename=\"".$backup_name."\"");
	        echo $content; exit;
		}else{
			wp_redirect(admin_url('tools.php?page=wpdb-sql&status=noselect'));
		}
	}
}

?>