<?php
/**
 * Simple Calendar ACF v4.x field support
 *
 * @package    SimpleCalendar/Extensions
 * @subpackage ACF/v4
 */
namespace SimpleCalendar\Acf;

use SimpleCalendar\Abstracts\Calendar;
use SimpleCalendar\Assets;

if (!defined("ABSPATH")) {
	exit();
}

/**
 * Advanced Custom Field v5 field.
 *
 * @since 1.0.0
 */
class Field_V4 extends \acf_field
{
	/**
	 * Field settings
	 *
	 * @access public
	 * @var array
	 */
	public $settings;

	/**
	 * Field options.
	 *
	 * @access public
	 * @var array
	 */
	public $defaults;

	/**
	 * Setup field data.
	 *
	 * @since 1.0.0
	 */
	public function __construct()
	{
		$this->name = "simple_calendar";
		$this->label = "Simple Calendar";
		$this->category = __("Content", "simple-calendar-acf");
		$this->defaults = [
			"allow_null" => 1,
		];

		parent::__construct();

		$this->settings = [
			"path" => apply_filters("acf/helpers/get_path", __FILE__),
			"dir" => apply_filters("acf/helpers/get_dir", __FILE__),
			"version" => "1.0.0",
		];
	}

	/**
	 * Create options.
	 *
	 * @since 1.0.0
	 *
	 * @param $field
	 */
	public function create_options($field)
	{
		$field = array_merge($this->defaults, $field);
		$key = $field["name"];
		?>
		<tr class="field_option field_option_<?php echo $this->name; ?>">
			<td class="label">
				<label><?php _e("Allow Null?", "simple-calendar-acf"); ?></label>
			</td>
			<td>
				<?php do_action("acf/create_field", [
    	"type" => "radio",
    	"name" => "fields[" . $key . "][allow_null]",
    	"value" => $field["allow_null"],
    	"choices" => [
    		1 => __("Yes", "simple-calendar-acf"),
    		0 => __("No", "simple-calendar-acf"),
    	],
    	"layout" => "horizontal",
    ]); ?>
			</td>
		</tr>
		<?php
	}

	/**
	 * Create field.
	 *
	 * @since 1.0.0
	 *
	 * @param $field
	 */
	public function create_field($field)
	{
		echo '<select id="' .
			$field["id"] .
			'" class="' .
			$field["class"] .
			' fa-select2-field" name="' .
			$field["name"] .
			'" >';

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
	 *
	 * @since 1.0.0
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
	 * @since  1.0.0
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
	 * Format value for API.
	 *
	 * @since  1.0.0
	 *
	 * @param  $value
	 * @param  $post_id
	 * @param  $field
	 *
	 * @return string
	 */
	public function format_value_for_api($value, $post_id, $field)
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

new Field_V4();
