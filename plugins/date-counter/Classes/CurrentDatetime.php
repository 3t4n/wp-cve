<?php


class CurrentDatetime extends AbstractDatetime
{
	public function display($atts)
	{
		if (!isset($atts["format"]))
			return self::error_message('<b>format</b> attribute is required.');

		try {
			$response = $this->datetime->format($atts["format"]);
		} catch (Exception $e) {
			$response = self::error_message($e->getMessage());
		} finally {
			return $response;
		}
	}
}

add_shortcode( 'CurrentDatetime', array( new CurrentDatetime, 'display') );