<?php


class DatetimeDifference extends AbstractDatetime
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

			$response = $difference->format(self::normalize_format($atts['format']));

		} catch (Exception $e) {
			$response = self::error_message($e->getMessage());
		} finally {
			return $response;
		}
	}

	protected static function normalize_format($format) {
		$symbols = ['Y', 'y', 'M', 'm', 'D', 'd', 'a', 'H', 'h', 'I', 'i', 'S', 's', 'F', 'f', 'R', 'r'];

		foreach ($symbols as $symbol) {
			$format = preg_replace("/(\b$symbol{1}\b)/", "%$symbol", $format);
		}

		return $format;
	}
}

add_shortcode( 'DatetimeDifference', array( new DatetimeDifference, 'display') );