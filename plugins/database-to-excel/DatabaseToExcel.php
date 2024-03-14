<?php
/**
 * Plugin Name: Database To Excel
 * Plugin URI: http://www.csitworld.com/wordpress/plugins/DatabaseToExcel
 * Description: This plugin provide you the functionality to export MySql database table to excel file. This plugin is very easy to use. It also allow you to show all database table's value with "export to excel" option in admin panel.
 * Version:  1.0
 * Author: Subhash Kumar
 * Author URI: https://profiles.wordpress.org/mistersubhash
 * License: Open Source
 */
ob_start();
//ob_flush();
add_action('admin_menu', 'dbtoExcel_admin_menu');
function dbtoExcel_admin_menu() {
	add_menu_page('DatabasetoExcel', 'Database to Excel', 'administrator',
		'DatabasetoExcel', 'DatabasetoExcel_html_page',plugins_url( 'logo.png', __FILE__ ));
        wp_enqueue_style('dbtoexcel-css', plugins_url('style.css',__FILE__));
}

function DatabasetoExcel_html_page(){
    if(is_admin()){
        global $wpdb;
            if(isset($_POST["tbl_name"])){
                $tablename = sanitize_text_field($_POST["tbl_name"]);
                $sql = "SHOW TABLES";
                $table_list  = $wpdb->get_results($sql,ARRAY_N);
                $IsValidTableName = 0;
                foreach($table_list as $table_name){
                    foreach ($table_name as $singlevalue){
                        if($singlevalue == $tablename){
                            $IsValidTableName = 1;
                        }
                    }
                }
                if($IsValidTableName==1){                                         
                    $con = mysql_connect($wpdb->dbhost,$wpdb->dbuser,$wpdb->dbpassword);
                    mysql_select_db($wpdb->dbname,$con ) or die("Couldn't select database.");              
                    $sql = "SELECT * FROM $tablename";
                    $result = @mysql_query($sql) or die("Couldn't execute query:<br>".mysql_error().'<br>'.mysql_errno());
		    ob_clean();
                    header("Content-Type:   application/vnd.ms-excel; charset=utf-8");
                    header("Content-Disposition: attachment; filename=".$tablename."-".date('Ymd').".xls");  //File name extension was wrong
                    header("Expires: 0");
                    header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
                    header("Cache-Control: private",false);
                    echo "<html>";
                    echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=Windows-1252\">";
                    echo "<body>";
                    echo "<table>";
                    print("<tr>");
                    for ($i = 0; $i < mysql_num_fields($result); $i++) {  // display name of the column as names of the database fields
                        echo "<th  style='border: thin solid; background-color: #83b4d8;'>" . mysql_field_name($result, $i) . "</th>";
                    }
                    print("</tr>");
                    while($row = mysql_fetch_row($result)){
                        $output = '';
                        $output = "<tr>";
                        for($j=0; $j<mysql_num_fields($result); $j++){
                            if(!isset($row[$j]))
                                $output .= "<td>NULL\t</td>";
                            else
                                $output .= "<td style='border: thin solid;'>$row[$j]\t</td>";
                        }
                        $output .= "</tr>";
                        $output = preg_replace("/\r\n|\n\r|\n|\r/", ' ', $output);
                        print(trim($output));
                    }
                    echo "</table>";
                    echo "</body>";
                    echo "</html>";
                }
                else{
                    echo 'Invalid Request.';
                }
            }
	?>
	<div class="e2e_container">
            <div class="">
                <h1>Database to Excel</h1>
                    <p>Please select a database table from below</p>
			<div>
                            <form action="" method="POST">
				<select name="table_name">
				<option value=0>Select Form</option>
							<?php
							$sql = "SHOW TABLES";
							$table_list  = $wpdb->get_results($sql,ARRAY_N);

							foreach($table_list as $table_name){
								foreach ($table_name as $singlevalue){
									?>
									<option value="<?php echo $singlevalue; ?>"><?php echo $singlevalue; ?></option>	
									<?php	
								}
							}
							?>
						</select>
						<input type="submit" class="button button-primary"  value="Show Table Data"/>
					</form>
				</div>
			</div>
			<div style="overflow-x:scroll;">
				<table class="wp-list-table widefat plugins" id="table">
					<?php
					if(isset($_POST["table_name"])){
						$tablename = $_POST["table_name"];
						$i=1;
						echo "<tr>";
						$t = mysql_connect($wpdb->dbhost,$wpdb->dbuser,$wpdb->dbpassword);
						mysql_select_db($wpdb->dbname,$t ) or die("Couldn't select database.");
						$result = mysql_query("SHOW COLUMNS FROM $tablename");

						if (!$result) {
							echo 'Could not run query: ' . mysql_error();
							exit;
						}
						if (mysql_num_rows($result) > 0){
							while ($row = mysql_fetch_assoc($result)) {
								echo "<th style=''>".$row["Field"]."</th>";
								$i++;
							}
						}
						echo "</tr>";
						$query = mysql_query("SELECT * FROM $tablename");
		
						while($row = mysql_fetch_array($query)){
							echo "<tr>";
							for($d = 0;$d<$i-1; $d++)
								echo "<td>".$row[$d]."</td>";
							echo "</tr>";
						} ?>
						<form action="" method="POST">
                                                    <input type="hidden" name="tbl_name" value="<?php echo $tablename; ?>"/>
                                                    <input class="button button-primary exportbtn" name="exportbtn" type="submit" name="table_display" value="Export"/>
						</form>
						<?php } ?>
                                            </table>
				</div>	
                            </div>
<?php } }