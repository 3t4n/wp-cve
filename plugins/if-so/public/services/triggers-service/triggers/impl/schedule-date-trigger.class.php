<?php

namespace IfSo\PublicFace\Services\TriggersService\Triggers;

require_once( plugin_dir_path ( __DIR__ ) . 'trigger-base.class.php');
require_once('time-date-trigger-base.class.php');

class ScheduleDateTrigger extends TimeDateTriggerBase {
	protected function is_valid($trigger_data) {
		if ( !parent::is_valid($trigger_data) )
			return false;

		$rule = $trigger_data->get_rule();
		
		if ($rule["Time-Date-Schedule-Selection"] != "Schedule-Date")
			return false;
		else if ( !isset($rule['Date-Time-Schedule']) )
			return false;
		else if ( empty($rule['Date-Time-Schedule']) )
			return false;

		return true;
	}

	public function handle($trigger_data) {
		$rule = $trigger_data->get_rule();

		$schedule = json_decode($rule['Date-Time-Schedule']);
		$format = "Y/m/d H:i:s";
		$tz = \IfSo\PublicFace\Helpers\WpDateTimeZone::getWpTimezone();
		$currTime = current_time($format);
        $time_obj = new \DateTime('now',$tz);
        $currDay = $time_obj->format('w');
		$selectedHours = $schedule->$currDay;
		$dayYearMonth = preg_split("/ /", $currTime)[0];
		$currDate = \DateTime::createFromFormat($format, current_time($format),$tz);

		if (!empty($selectedHours)) {
			foreach ($selectedHours as $hoursKey => $hoursPair) {
				$startHour = $dayYearMonth." ".$hoursPair[0].':00';
				$endHour = $dayYearMonth." ".$hoursPair[1].':00';
				
				$startDate = \DateTime::createFromFormat($format, $startHour,$tz);
				$endDate = \DateTime::createFromFormat($format, $endHour,$tz);

				// Check if in between
				// if so we display this version's content

				if ($currDate >= $startDate &&
					$currDate <= $endDate) {
					return $trigger_data->get_content();
				}
			}
		}

		return false;
	}
}