<?php


class TotalDatetimeDifference extends AbstractDatetime
{
	public function display($atts)
	{
		if (!isset($atts["startdate"]))
			return self::error_message('<b>startDate</b> attribute is required.');

		if (!isset($atts["enddate"]))
			return self::error_message('<b>endDate</b> attribute is required.');

		if (!isset($atts["format"]))
			return self::error_message('<b>format</b> attribute is required.');

		try {
			$start_date = $this->get_datetime($atts["startdate"]);
			$end_date = $this->get_datetime($atts["enddate"]);

			$difference = $end_date->diff($start_date);

			switch($atts['format']) {
				case "y":
					$response = $difference->y;
					break;
				case "m":
					$response = ($difference->y * 12) + $difference->m;
					break;
				case "w":
					$response = floor($difference->days / 7);
					break;
				case "d":
					$response = $difference->days;
					break;
				case "h":
					$hours = $difference->days * 24;
					$hours += $difference->h;
					$response = $hours;
					break;
				case "i":
					$minutes = $difference->days * 24 * 60;
					$minutes += $difference->h * 60;
					$minutes += $difference->i;
					$response = $minutes;
					break;
				case "s":
					$seconds = $difference->i * 60;
					$seconds += $difference->s;
					$response = $seconds;
					break;
				default:
					$response = self::error_message('The entered format does not match any of the following: y, m, w, d, h, i, s. Make sure to enter <b>only one letter</b>.');
					break;
			}

		} catch (Exception $e) {
			$response = self::error_message($e->getMessage());
		} finally {
			return $response;
		}
	}
}

add_shortcode( 'TotalDatetimeDifference', array( new TotalDatetimeDifference, 'display') );