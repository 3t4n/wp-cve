<?php

namespace IfSo\PublicFace\Services\TriggersService\Triggers;

require_once( plugin_dir_path ( __DIR__ ) . 'trigger-base.class.php');
require_once('time-date-trigger-base.class.php');

class StartEndTimeTrigger extends TimeDateTriggerBase {
	protected function is_valid($trigger_data) {
		if ( !parent::is_valid($trigger_data) )
			return false;

		$rule = $trigger_data->get_rule();
		return $rule["Time-Date-Schedule-Selection"] == "Start-End-Date";
	}

	public function handle($trigger_data) {
		$rule = $trigger_data->get_rule();
		$content = $trigger_data->get_content();

		$format = "Y/m/d H:i";
		$currDate = \DateTime::createFromFormat($format, current_time($format));
		if ( ( isset($rule['Time-Date-Start']) &&
			   isset($rule['Time-Date-End']) && 
			   $rule['Time-Date-Start'] == "None" &&
			   $rule['Time-Date-End'] == "None" ) || 
			 ( empty($rule['time-date-end-date']) && 
			  	empty($rule['time-date-start-date']) ) ) {
			return $content;
		}

		if ( ( isset($rule['Time-Date-Start']) && 
			   $rule['Time-Date-Start'] == "None" ) ||
			  empty($rule['time-date-start-date']) ) {

			// No start date
			$endDate = \DateTime::createFromFormat($format, $rule['time-date-end-date']);

			if ($currDate <= $endDate) {
				// Yes! we are in the right time frame
				return $content;
			}

		} else if ( ( isset($rule['Time-Date-End']) && 
			   		  $rule['Time-Date-End'] == "None" ) ||
			  		  empty($rule['time-date-end-date']) ) {

			// No end date
			$startDate = \DateTime::createFromFormat($format, $rule['time-date-start-date']);

			if ($currDate >= $startDate) {
				// Yes! we are in the right time frame
				return $content;
			}
		} else {
			// Both have dates
			$startDate = \DateTime::createFromFormat($format, $rule['time-date-start-date']);
			$endDate = \DateTime::createFromFormat($format, $rule['time-date-end-date']);

			if ($currDate >= $startDate &&
				$currDate <= $endDate) {

				// Yes! we are in the right time frame

				return $content;
			}
		}

		return false;
	}
}