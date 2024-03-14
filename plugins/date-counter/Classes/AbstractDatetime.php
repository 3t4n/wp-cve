<?php


abstract class AbstractDatetime
{

	public $datetime;
	public $timezone;

	public function __construct() {
		add_filter( 'get_my_plugin_instance', [ $this, 'get_instance' ] );

		try {
			$this->timezone = wp_timezone();
		} catch (Exception $e) {
			$this->timezone = new DateTimeZone(date_default_timezone_get());
		} finally {
			$this->datetime = new DateTime( "now", $this->timezone );
		}
	}

	abstract public function display($atts);

	public function get_instance()
	{
		return $this;
	}

	protected function get_datetime(string $datetime_string) : DateTime
	{
		if (strpos(strtolower($datetime_string), 'post:') !== false)
		{
			list($post_type, $date_type) = explode(':', strtolower($datetime_string));

			global $post;

			switch ($date_type) {
				case "created":
					return new DateTime($post->post_date, $this->timezone);
					break;
				case "modified":
					return new DateTime($post->post_modified, $this->timezone);
					break;
				default:
					throw new Exception('Invalid date type, possible values are created and modified.');
					break;
			}
		} else {
			return new DateTime($datetime_string, $this->timezone);
		}
	}

	protected static function error_message($message) {
		return sprintf("<span style='color: #D50032;'>%s error:</span> %s", static::class, $message);
	}
}