<?php
/**
 * @author William Sergio Minossi
 */
if (!defined('ABSPATH'))
    exit; // Exit if accessed directly
/*
        $charset_collate = $wpdb->get_charset_collate();
        $sql = "CREATE TABLE $table_name (
            id INT PRIMARY KEY AUTO_INCREMENT,
            page_url VARCHAR(255) NOT NULL,
            load_time FLOAT NOT NULL,
            timestamp DATETIME NOT NULL
        ) $charset_collate;";
*/

global $wpdb;
$table_name = $wpdb->prefix . 'wptools_page_load_times';

/*
$query = "SELECT DATE(timestamp) AS date, AVG(load_time) AS average_load_time
          FROM $table_name
          WHERE timestamp >= CURDATE() - INTERVAL 6 DAY
            AND page_url NOT LIKE '%wp-admin%'
          GROUP BY DATE(timestamp)
          ORDER BY date";

*/

$query = "SELECT DATE(timestamp) AS date, AVG(load_time) AS average_load_time
          FROM $table_name
          WHERE timestamp >= CURDATE() - INTERVAL 6 DAY
            AND NOT page_url LIKE 'wp-admin'
          GROUP BY DATE(timestamp)
          ORDER BY date";
          



$results9 = $wpdb->get_results($query, ARRAY_A);

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
    $array7ld[$x] = date("Y-m-d", $tm); // Adjust the format to match the database
    $search_value = trim($array7ld[$x]);
    $array7ld[$x] = date("Y-m-d", $tm);
    
    // Use 'date' instead of 'error_day' for comparison
    $mykey = array_search($array7ld[$x], array_column($results8, 'date'));

    $the_day = date("d", $tm);
    $this_month = date('m', $tm);
    $array7ld[$x] = $this_month . $the_day;

    if ($mykey !== false) {
        $awork = $results8[$mykey]['average_load_time'];
        $array7l[$x] = round($awork, 1); // Arredonda para 2 casas decimais

    } else {
        $array7l[$x] = 0;
    }
    $x++;
}

$array7l = array_reverse($array7l);
$array7ld = array_reverse($array7ld);
// die(var_export($array7l));