<?php
/**
 * @author William Sergio Minossi
 */
if (!defined('ABSPATH'))
    exit; // Exit if accessed directly
global $wpdb;

$wptools_table_name = $wpdb->prefix . 'wptools_errors'; 
if ($wpdb->get_var("SHOW TABLES LIKE '$wptools_table_name'") !== $wptools_table_name) {
    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
    $sql = "CREATE TABLE IF NOT EXISTS $wptools_table_name (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `ip` varchar(50) NOT NULL,
        `error_number` int(11) NOT NULL,
        `error_type` varchar(255) NOT NULL,
        `error_string` text NOT NULL,
        `error_file` varchar(255) NOT NULL,
        `error_line` varchar(10) NOT NULL,
        `file_location` varchar(255) NOT NULL,
        `plugin_name` varchar(255) NOT NULL,
        `theme_name` varchar(255) NOT NULL,
        `error_date` timestamp NOT NULL DEFAULT current_timestamp(),
        `ua` text NOT NULL,
        PRIMARY KEY (`id`),
        INDEX (`error_date`)  
    ) ENGINE=InnoDB DEFAULT CHARSET=latin1;";
    // Execute the SQL query
    dbDelta($sql);
}
include("fill_db_errors.php");
 wptools_fill_db__errors();
$table_name = $wpdb->prefix . 'wptools_errors';
$query = "SELECT DATE(error_date) as error_day, COUNT(*) AS qtotal FROM $table_name GROUP BY error_day";
$results9 = $wpdb->get_results($query);





$query = "SELECT DATE(error_date) as error_day, COUNT(*) AS qtotal FROM $table_name GROUP BY error_day";
$results9 = $wpdb->get_results($query);



if ($results9) {
    $total = count($results9);
    if($total < 1 ) {
      $wptools_empty = true;
      return;
    }

} else {
    $wptools_empty = true;
    return;
}



$results8 = json_decode(json_encode($results9), true);
unset($results9);
$x = 0;
$d = 7;
for ($i = $d; $i > 0; $i--) {
    $timestamp = time();
    $tm = 86400 * ($x); // 60 * 60 * 24 = 86400 = 1 day in seconds
    $tm = $timestamp - $tm;
    $array7d[$x] = date("Y-m-d", $tm); // Adjust the format to match the database
    $search_value = trim($array7d[$x]);
    $array7d[$x] = date("Y-m-d", $tm);
    $mykey = array_search($array7d[$x], array_column($results8, 'error_day'));
    $the_day = date("d", $tm);
    $this_month = date('m', $tm);
    $array7d[$x] = $this_month.$the_day ;

    if ($mykey !== false) {
        $awork = $results8[$mykey]['qtotal'];
        //echo "qtotal: $awork\n";
        $array7[$x] = (int)$awork;
    } else {
        $array7[$x] = 0;
    }

    $x++;
}
$array7 = array_reverse($array7);
$array7d = array_reverse($array7d);

/*
// die(var_export($array7));
var_export($array7);
echo '<hr>';
var_export($array7d);
echo '<hr>';
*/
?>