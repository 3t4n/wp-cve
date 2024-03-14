<?php

class DateCounter extends AbstractDatetime
{
	public function display($atts)
	{
		if (!isset($atts["format"]))
			return self::error_message('<b>format</b> attribute is required.');

		try {

			$format = strtolower($atts["format"]);

			if (in_array($format, array('currentyear', 'currentmonth', 'currentday'))) {
				switch ($format) {
					case "currentyear":
						$response = date("Y");
						break;
					case "currentmonth":
						$response = date("m");
						break;
					case "currentday":
						$response = date("d");
						break;
					default:
						break;
				}
			} else {
				if (!isset($atts["startdate"]))
					return self::error_message('<b>startDate</b> attribute is required.');

				if (!isset($atts["enddate"]))
					return self::error_message('<b>endDate</b> attribute is required.');

				$start_date = $this->get_datetime($atts["startdate"]);
				$end_date = $this->get_datetime($atts["enddate"]);

				$difference = $end_date->diff($start_date, true);

				switch ($format) {
					case "year":
					case "years":
						$response = $difference->y;
						break;
					case "month":
					case "months":
						$response = ($difference->y * 12) + $difference->m;
						break;
					case "week":
					case "weeks":
						$response = floor($difference->days / 7);
						break;
					case "day":
					case "days":
						$response = $difference->days;
						break;
					case "hour":
					case "hours":
						$hours = $difference->days * 24;
						$hours += $difference->h;
						$response = $hours;
						break;
					case "minute":
					case "minutes":
						$minutes = $difference->days * 24 * 60;
						$minutes += $difference->h * 60;
						$minutes += $difference->i;
						$response = $minutes;
						break;
					case "second":
					case "seconds":
						$seconds = $difference->days * 24 * 60 * 60;
						$seconds += $difference->h * 60 * 60;
						$seconds += $difference->i * 60;
						$seconds += $difference->s;
						$response = $seconds;
						break;
					default:
						$response = $difference->format($format);
						break;
				}
			}

		} catch (\Exception $e) {
			$response = self::error_message($e->getMessage());
		} finally {
			return $response;
		}
	}
}

add_shortcode('DateCounter', array(new DateCounter, 'display'));