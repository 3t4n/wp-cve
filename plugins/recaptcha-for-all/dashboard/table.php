<?php
/**
 * @ Author: Bill Minozzi
 * @ Copyright: 2024 www.BillMinozzi.com
 * @ Modified time: 2024-01-17
 */
if (!defined('ABSPATH')) {
    die('We\'re sorry, but you can not directly access this file.');
}
global $wpdb;
$table = $wpdb->prefix . "recaptcha_for_all_stats"; 
$challenges = 0;
$ok = 0;
$query = $wpdb->prepare(
    "SELECT COUNT(*) FROM $table WHERE challenge = %d AND date >= %s",
    1,
    date('Y-m-d H:i:s', strtotime('-1 week'))
);
$result = $wpdb->get_var($query);
if ($wpdb->last_error) {
    $error_message = $wpdb->last_error;
    echo "Error: $error_message";
} else {
    $challenges = $result;
}
$query = $wpdb->prepare(
    "SELECT COUNT(*) FROM $table WHERE ok = %d AND date >= %s",
    1,
    date('Y-m-d H:i:s', strtotime('-1 week'))
);
$result = $wpdb->get_var($query);
if ($wpdb->last_error) {
    $error_message = $wpdb->last_error;
    echo "Error: $error_message";
} else {
    $ok = $result;
}
if($challenges > 0 and $ok > 0){
    $fail = ($challenges - $ok);
}
else{
      $recaptcha_for_all_is_empty = true;
      return;


}
echo '<table style="border-collapse: collapse; border: 1px solid black;">';
echo '<tr><th style="padding: 10px;">Event</th><th style="padding: 10px;">Quantity</th><th style="padding: 10px;">Percentage</th></tr>';
echo '<tr><td style="padding: 10px;">Total Challenges</td><td style="padding: 10px;">' . $challenges . '</td><td style="padding: 10px;">100%</td></tr>';
echo '<tr><td style="padding: 10px;">Total Solved (pass)</td><td style="padding: 10px;">' . $ok . '</td><td style="padding: 10px;">' . round(($ok / $challenges) * 100) . '%</td></tr>';
if($challenges > 0 and $ok > 0){
    $fail = ($challenges - $ok);
    echo '<tr><td style="padding: 10px;">Total Fails (blocked)</td><td style="padding: 10px;">' . $fail . '</td><td style="padding: 10px;">' . round(($fail / $challenges) * 100) . '%</td></tr>';
}
echo '</table>';
echo '<br>';
