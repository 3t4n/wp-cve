<?php // Cron Stuff

if (!defined('ABSPATH')) exit;

function banhammer_cron_activation() {
	
	if (!wp_next_scheduled('banhammer_cron_reset')) {
		
		wp_schedule_event(time(), 'banhammer_one_day', 'banhammer_cron_reset');
		
	}
	
}

function banhammer_cron_deactivation() {
	
	$timestamp = wp_next_scheduled('banhammer_cron_reset');
	
	wp_unschedule_event($timestamp, 'banhammer_cron_reset');
	
	wp_clear_scheduled_hook('banhammer_cron_reset');
	
}

function banhammer_cron_intervals($schedules) {
	
	$intervals = array(
		
		'banhammer_one_minute'   => 61,
		'banhammer_one_hour'     => 3601,
		'banhammer_six_hours'    => 21601,
		'banhammer_twelve_hours' => 43201,
		'banhammer_one_day'      => 86401,
		'banhammer_one_week'     => 604801,
		'banhammer_one_month'    => 2629747,
		'banhammer_one_year'     => 31556953,
		'banhammer_never'        => 0
		
	);
	
	foreach ($intervals as $key => $value) {
		
		$label = ucwords(str_replace('_', ' ', $key));
		
		$schedule = array('interval' => $value, 'display' => $label);
		
		$schedules[$key] = $schedule;
		
	}
	
	return $schedules;
	
}

function banhammer_cron_update() {
	
	if (isset($_GET['page']) && $_GET['page'] === 'banhammer') {
		
		global $BanhammerWP;
		
		$default = $BanhammerWP->options();
		
		$options = get_option('banhammer_settings', $default);
		
		$interval = isset($options['reset_interval']) ? $options['reset_interval'] : 'banhammer_one_day';
		
		$schedule = wp_get_schedule('banhammer_cron_reset');
		
		if ($schedule !== $interval) {
			
			$timestamp = wp_next_scheduled('banhammer_cron_reset');
			
			wp_unschedule_event($timestamp, 'banhammer_cron_reset');
			
			wp_schedule_event(time(), $interval, 'banhammer_cron_reset');
			
		}
		
	}
	
}

function banhammer_cron_reset() {
	
	if (!defined('DOING_CRON')) return;
	
	global $wpdb;
	
	$table = $wpdb->prefix .'banhammer';
	
	$wpdb->query("TRUNCATE TABLE ". $table);
	
}
