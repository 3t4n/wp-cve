<?php
	global $wpdb;
	$table_name = $wpdb->prefix . "loginlog";

	if ($_POST['loginlog_clear']=='true') {
		$query = "DELETE FROM ".$table_name." WHERE success='0'";
		$wpdb->get_results($query);
	}

	$cur_time = $wpdb->get_results("SELECT UNIX_TIMESTAMP('".current_time('mysql')."') as timestamp FROM ".$table_name);
	$cur_time = $cur_time[0]->timestamp;
	$query = "SELECT username,time,UNIX_TIMESTAMP(time) as timestamp,UNIX_TIMESTAMP(active) as activestamp,IP FROM ".$table_name." WHERE success='1' ORDER BY time DESC";
	$results = $wpdb->get_results($query);
	echo '<div class="wrap">
	<h2>Last logins:</h2>
	<table class="widefat" cellpadding="3" cellspacing="3"><tr><th>Username</th><th>Time</th><th>IP</th><th>How Long Ago</th><th>Time Since Last Active</th></tr>';
	if ($results)
	foreach ($results as $result) {
		echo '<tr><td>'.$result->username.'</td><td>'.$result->time.'</td><td>'.$result->IP.'</td><td>'.format_interval($cur_time-$result->timestamp).'</td><td>'.format_interval($cur_time-$result->activestamp).'</td>';
	}

	$query = "SELECT distinct $wpdb->users.user_login,".$table_name.".username FROM $wpdb->users LEFT OUTER JOIN ".$table_name." ON $wpdb->users.user_login = ".$table_name.".username WHERE ".$table_name.".username IS NULL";
	$results = $wpdb->get_results($query);

	if ($results)
	foreach ($results as $result) {
		echo '<tr><td>'.$result->user_login.'</td><td>N/A</td><td>N/A</td><td>N/A</td><td>N/A</td>';
	}

	$query = "SELECT username,time,UNIX_TIMESTAMP(time) as timestamp,IP FROM ".$table_name." WHERE success='0' ORDER BY time DESC";
	$results = $wpdb->get_results($query);

	echo '</table><h2>Failed logins:</h2>
	<table class="widefat" cellpadding="3" cellspacing="3"><tr><th>Username</th><th>Time</th><th>IP</th><th>How Long Ago</th></tr>';
	if ($results)
	foreach ($results as $result) {
		echo '<tr><td>'.$result->username.'</td><td>'.$result->time.'</td><td>'.$result->IP.'</td><td>'.format_interval($cur_time-$result->timestamp).'</td>';
	}
?>
	</table>
	<form method="post" action="<?php bloginfo('wpurl') ?>/wp-admin/users.php?page=login-logger/manage.php">
	<p class="submit">
		<input type="hidden" name="loginlog_clear" value="true"/>
		<input type="submit" name="loginlog_submit" value="Clear failed logins"/>
	</p></form>
<?php
function format_interval($timestamp, $granularity = 2) {
  $units = array('1 year|@count years' => 31536000, '1 week|@count weeks' => 604800, '1 day|@count days' => 86400, '1 hour|@count hours' => 3600, '1 min|@count min' => 60, '1 sec|@count sec' => 1);
  $output = '';
 foreach ($units as $key => $value) {
    $key = explode('|', $key);
    if ($timestamp >= $value) {
      $output .= ($output ? ' ' : '') . format_plural(floor($timestamp / $value), $key[0], $key[1]);
      $timestamp %= $value;
      $granularity--;
    }

    if ($granularity == 0) {
      break;
    }
  }
  return $output ? $output : '0 sec';
}
function format_plural($count, $singular, $plural) {
  if ($count == 1) return $singular;
  return str_replace("@count",$count,$plural);
}
?>