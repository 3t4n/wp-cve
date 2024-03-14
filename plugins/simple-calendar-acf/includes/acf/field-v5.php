<?php
/**
 * Simple Calendar ACF v5.x field support
 *
 * @package    SimpleCalendar/Extensions
 * @subpackage ACF/v5
 */

namespace SimpleCalendar\Acf;

use SimpleCalendar\Abstracts\Calendar;
use SimpleCalendar\Assets;

if (!defined("ABSPATH")) {
	exit();
}

/**
 * Advanced Customs Field v5 field.
 *
 * @since 1.0.0
 */
class Field_V5 extends \acf_field
{
	/**
	 * Setup field data.
	 *
	 * @since 1.0.0
	 */
	public function __construct()
	{
		$this->name = "simple_calendar";
		$this->label = "Simple Calendar";
		$this->category = "content";
		$this->defaults = [
			"allow_null" => 1,
		];

		parent::__construct();
	}

	/**
	 * Create extra settings.
	 *
	 * @param $field
	 */
	public function render_field_settings($field)
	{
		// Allow null option (to choose no calendar).
		acf_render_field_setting($field, [
			"label" => __("Allow Null?", "simple-calendar-acf"),
			"instructions" => "",
			"type" => "radio",
			"name" => "allow_null",
			"choices" => [
				1 => __("Yes", "simple-calendar-acf"),
				0 => __("No", "simple-calendar-acf"),
			],
			"layout" => "horizontal",
		]);
	}

	/**
	 * Create the HTML interface for the field.
	 *
	 * @param $field
	 */
	public function render_field($field)
	{
		// Attributes.
		$atts = [
			"id" => $field["id"],
			"class" => $field["class"] . " simcal-field-select-enhanced",
			"name" => $field["name"],
			"data-allow_null" => $field["allow_null"],
		];

		// Special attributes.
		foreach (["readonly", "disabled"] as $k) {
			if (!empty($field[$k])) {
				$atts[$k] = $k;
			}
		}

		echo "<select " . acf_esc_attr($atts) . ">";

		$calendars = simcal_get_calendars();

		if ($field["allow_null"] || empty($calendars)) {
			echo '<option value="null"></option>';
		}

		if (!empty($calendars)) {
			foreach ($calendars as $id => $name) {
				$selected = selected($id, $field["value"], false);
				echo '<option value="' .
					strval($id) .
					'" ' .
					$selected .
					">" .
					$name .
					"</option>" .
					"\n";
			}
		}

		echo "</select>";
	}

	/**
	 * Enqueue field scripts.
	 */
	public function input_admin_enqueue_scripts()
	{
		wp_enqueue_script("simcal-admin-add-calendar");
		wp_localize_script("simcal-admin-add-calendar", "simcal_admin", [
			"locale" => get_locale(),
			"text_dir" => is_rtl() ? "rtl" : "ltr",
		]);
	}

	/**
	 * Load value.
	 *
	 * @param  $value
	 * @param  $post_id
	 * @param  $field
	 *
	 * @return string
	 */
	public function load_value($value, $post_id, $field)
	{
		return is_numeric($value) ? intval($value) : "";
	}

	/**
	 * Format value.
	 *
	 * @param  $value
	 * @param  $post_id
	 * @param  $field
	 *
	 * @return string
	 */
	public function format_value($value, $post_id, $field)
	{
		$html = "";

		if (is_numeric($value) && $value > 0) {
			$calendar = simcal_get_calendar($value);

			if ($calendar instanceof Calendar) {
				$view = $calendar->get_view();
				$assets = new Assets();
				$assets->load_styles($view->styles(".min"));
				$assets->load_scripts($view->scripts(".min"));

				$html = do_shortcode('[calendar id="' . $value . '"]', false);
			}
		}

		return $html;
	}
}

new Field_V5();
