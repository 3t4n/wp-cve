<?php
/**
 * Created by PhpStorm.
 * User: MYN
 * Date: 5/11/2019
 * Time: 10:10 AM
 */

namespace DataPeen\FaceAuth;
use DataPeen\FaceAuth\Config as Config;

/**
 * Class Options_Form
 * @package DataPeen\FaceAuth
 * This class, will be used across multiple BC plugins. They all share one common custom post type to store
 * plugins' settings
 *
 */

class Options_Form
{
	private $option_name, $option_post_id;
	private $options;
	const AJAX_SAVE_FORM = 'datapeen_ff_ajax_save';//Used to store form settings, must be different across plugins
	const REDIRECT_URL = 'redirect_url';


	/**
	 * @param $option_name
	 */
	public function __construct($option_name, $option_post_id)
	{
		$this->option_name = $option_name;
		$this->option_post_id = $option_post_id;
		$this->options = new Options($this->option_name, $option_post_id);

		//update the $option_post_id in case the id passed in is 0, the Options class will create a new post
		$this->option_post_id = $this->options->get_post_id();
	}
	/**
	 * Return the naem of the ajax action. Used in setting_fields() to output ajax action
	 */
	public static function get_action_name()
	{
		return sprintf('%1$s', self::AJAX_SAVE_FORM);
	}

	/**
	 * Return the id of current post
	 */
	public function get_option_post_id()
	{
		return $this->option_post_id;
	}


	/**
	 * Print an input field then multiple other fields that associated to that input field
	 *
	 * @param $setting_field_name string name of the field
	 * @param $child_fields array
	 * @param $echo boolean return or echo HTML
	 */
	public function input_multiple($setting_field_name, $child_fields, $echo = true)
	{

	}

	/**
	 * This function handle form submit (save form data...)
	 * you need to add action and put this as the handler in the
	 * constructor of this class
	 */

	public static function save_form_options()
	{

		//save the option to the post ID
		if (!current_user_can('edit_posts')) {
			echo __('You have no right to perform this action.', Config::TEXT_DOMAIN);
			die();
		}

		//check nonce and update the options
//        dump(check_ajax_referer(self::get_action_name()));
		if (!wp_verify_nonce(sanitize_text_field($_POST['bc_form_security']), sanitize_text_field($_POST['action']))) {
			wp_send_json(array(
				'status' => 'Error',
				'message' => 'You do not have the necessary rights to perform this action'
			));
			die();
		}

		$option_name = sanitize_text_field($_POST['option_name']);
		$option_post_id = intval($_POST['option_post_id']);
		$option_object = new Options($option_name, $option_post_id);
		//save the settings
		//if you want to save as HTML, make sure the field name ($key) ends with _save_html (wtf?)

		foreach ($_POST[$option_name] as $key => $value) {

			if (stripos($key, '_save_html'))
				$option_object->set($key, $value, false, true);
			else
				$option_object->set($key, $value);

			if ($key == 'title') {
				wp_update_post(
					array(
						'ID' => $option_post_id,
						'post_title' => sanitize_text_field($value)
					)
				);
			}
		}



		$option_object->set_option_name($option_name);

		$data = array(
			'status' => 'Success',
			'message' => 'Settings saved successfully');

		if (isset($_POST[self::REDIRECT_URL]))
		{
			$data[self::REDIRECT_URL] = esc_url_raw($_POST[self::REDIRECT_URL]);
		}
		wp_send_json(
			$data
		);
		die();
	}

	/**
	 * output nonce, action ...
	 *
	 * @param $echo boolean return or echo HTML directly to screen
	 *
	 * @return void|string
	 */
	public function setting_fields($echo = true)
	{
		$html = '';

		$html .= sprintf('<input type="hidden" name="action" value="%1$s" />', $this->get_action_name());
		$html .= sprintf('<input type="hidden" name="option_post_id" value="%1$s" />', $this->option_post_id);
		$html .= sprintf('<input type="hidden" name="option_name" value="%1$s" />', $this->option_name);
		$html .= wp_nonce_field($this->get_action_name(), "bc_form_security");

		if ($echo)
			echo $html;
		else
			return $html;

	}


	private function get_option_value($option_form_field, $type = 'string')
	{
		switch ($type) {
			case 'string'://duplicate but included for completeness
				return $this->options->get_string($option_form_field);
				break;
			case 'int':
				return $this->options->get_int($option_form_field);
				break;
			case 'float':
				return $this->options->get_float($option_form_field);
				break;
			case 'bool':
				return $this->options->get_bool($option_form_field);
				break;
			case 'array':
				return $this->options->get_array($option_form_field);
				break;
			default:
				return $this->options->get_string($option_form_field);
				break;
		}
	}

	/**
	 * @param $option_form_field string: the actual field name in the form, will be prepend by $option_level_1[$option_level_2]
	 *
	 * @return string
	 */
	private function generate_form_field($option_form_field)
	{
		return sprintf('%1$s[%2$s]', $this->option_name, $option_form_field);
	}

	/**
	 * Returns nonce field HTML
	 *
	 * @param int $action
	 * @param string $name
	 * @param bool $referer
	 * @internal param bool $echo
	 * @return string
	 */
	public static function nonce_field($action = -1, $name = '_wpnonce', $referer = true, $echo = true)
	{
		$name = esc_attr($name);
		$html = '<input type="hidden" name="' . $name . '" value="' . wp_create_nonce($action) . '" />';

		if ($referer) {
			$html .= wp_referer_field(false);
		}

		if ($echo)
			echo $html;
		else
			return $html;
	}


	/**
	 * Returns an input text element
	 *
	 * @param $setting_field_name
	 *
	 * @return string|void
	 */
	public function hidden($setting_field_name, $echo = true)
	{
		$current_value = $this->get_option_value($setting_field_name, 'string');

		$html = sprintf('<input type="hidden" name="%1$s" value="%2$s" />', $this->generate_form_field($setting_field_name), $current_value);

		if ($echo)
			echo $html;
		else
			return $html;

	}

	/**
	 * @param $key
	 * @param $value
	 * @param bool $echo
	 *
	 * @return string|void
	 */
	public function raw_hidden($key, $value, $echo = true)
	{
		$html =  sprintf('<input type="hidden" name="%1$s" value="%2$s" />', $key, $value);

		if ($echo)
			echo $html;
		else
			return $html;
	}

	/**
	 * Echos an label element
	 *
	 * @param $field_id
	 * @param string $text
	 * @param boolean $echo
	 *
	 * @return string|void
	 */
	public static function label($field_id, $text, $echo = true)
	{
		$output = sprintf('<label for="%1$s" class="bc-doc-label">%2$s</label>', $field_id, $text);
		if ($echo)
			echo $output;
		else
			return $output;
	}

	/**
	 * Echos an input text element
	 *
	 * @param $setting_field_name
	 * @param string $type
	 * @param bool $disabled
	 * @return string|void
	 */
	public function input_field($setting_field_name, $type = 'text', $label = '', $disabled = false, $width = 200, $echo = true)
	{

		$current_value = $this->get_option_value($setting_field_name);
		$disabled = $disabled ? 'disabled' : '';
		$html = '';
		$html .= '<div class="bc-uk-card">';
		if ($label != '')
			$html .= sprintf('<label class="bc-doc-label" for="%1$s">%2$s</label>', $setting_field_name, $label);
		$html .= sprintf('<input class="bc-uk-input" type="%1$s" id="%6$s" name="%2$s" value="%3$s" %4$s style="width: %5$s;"/>', $type, $this->generate_form_field($setting_field_name), $current_value, $disabled, $width . 'px', $setting_field_name);
		$html .= '&nbsp;&nbsp;</div>';

		if ($echo)
			echo $html;
		else
			return $html;

	}


	/**
	 * @param $setting_field_name
	 * @param $button_title
	 * @param string $label
	 * @param bool $disabled
	 * @param bool $echo
	 *
	 * @return string
	 */
	public function image_picker($setting_field_name, $button_title, $label = '', $dimension = array(200, 200), $disabled = false, $echo = true)
	{
		$disabled = $disabled ? 'disabled' : '';
		$current_value = $this->get_option_value($setting_field_name);
		$html = '<div class="bc-image-picker">';

		$label = $label != '' ? sprintf('<label class="bc-doc-label">%1$s</label>', $label) : '';

		$html .= $label;

//		if ($current_value !== '')
//
//		else
		$html .= sprintf('<img class="bc_image_preview" src="%1$s" style="width: %2$s; height: %3$s;" />', $current_value, $dimension[0] .'px', $dimension[1]. 'px');
//		$html .= sprintf('<div class="bc_image_placeholder" style="border: 1px solid #aaa; box-sizing: border-box; width: %1$s; height: %2$s;" ></div>', $dimension[0] .'px', $dimension[1]. 'px');
		$html .= sprintf('<a class="bc-doc__image-picker-button " %1$s>%2$s</a>', $disabled, $button_title);
//		$html .= sprintf('<a class="bc-doc__image-remove-button " %1$s><i class="fas fa-trash-alt"></i></a>', $disabled);
		$html .= sprintf('<input type="hidden" id="%1$s" class="bc_image_picker_hidden_input" name="%1$s" value="%2$s" %3$s/>', $this->generate_form_field($setting_field_name),  $current_value, $disabled);

		$html .= '</div>';

		if ($echo)
			echo $html;
		else
			return $html;

	}


	/**
	 * Generate a section where one key is associated with an associative array
	 * Keys and Values are in two select fields, side by side
	 *
	 *    _key => array(
	 *       'key' => 'value'
	 *     )
	 *
	 * @param $setting_field_name
	 * @param $array_values_1
	 * @param $array_values_2
	 * @param $value_1_title
	 * @param $value_2_title
	 * @param bool $disabled
	 * @param bool $echo
	 *
	 * @return string|void
	 */
	public function key_select_select(
		$setting_field_name,
		$array_values_1,
		$array_values_2,
		$value_1_title,
		$value_2_title,
		$disabled = false,
		$echo = true
	)
	{
		//get the current value, this should be an associated array
		$current_value = $this->get_option_value($setting_field_name, 'array');

		if (count($current_value) == 0)
		{
			$current_value = array(
				'' => ''
			);
		}

		$html = '';
		foreach ($current_value as $key => $value)
		{
			$html .= self::flex_data_row(array(
				$this->no_key_select($array_values_1, $key),
				$this->no_key_select($array_values_2, $value)
			), true, true);
		}



		$html =  sprintf('<div class="bc-key-select-select bc-data-field" data-name="%1$s">%2$s</div>', $setting_field_name, $html);

		if ($echo)
			echo $html;
		else
			return $html;

	}


	/**
	 * Print single data row. Used in fields that have more than one inputs or selects (a key then value is an array of assocciated array)
	 */
	private static function flex_data_row(array $content, $equal_width = false, $display_add_row = false, $display_minus_row = false)
	{
		$html = '';
		$add_sign = $display_add_row ? '<span class="add-data-row">+</span>' : '';
		$minus_sign = $display_add_row ? '<span class="minus-data-row">-</span>' : '';
		foreach($content as $c)
		{
			$width_class = $equal_width ? 'bc-uk-width-1-1' : '';
			$html .= $single_row = sprintf('<div class="%1$s">%2$s</div>', $width_class, $c);

		}



		//the class .bc-single-data-row will be used to collect data on a single row when the form is saved
		return sprintf ('<div class="bc-single-data-row bc-uk-flex">%1$s %2$s %3$s </div>', $html, $minus_sign, $add_sign);
	}

	/**
	 * Print the select, without field name.
	 * This function print a select as a big field (that has key, which is the field name)
	 * One case is to have a key then two selects
	 * For example, select which category goes with which popup
	 * This field doesn't have an echo option. It's used internally
	 *
	 * @param array $values_array must be an associative array
	 *
	 * @return string|void
	 */
	private function no_key_select($values_array, $selected_value, $multiple = false)
	{
		$html = '';
		foreach($values_array as $value => $name)
		{
			$selected = $value == $selected_value ? 'selected' : '';
			$html .= sprintf('<option value="%1$s" %2$s>%3$s</option>', $value, $selected, $name);
		}

		$multiple = $multiple ? 'multiple' : '';
		//mark this select field as bc-no-key-field so js will exclude it later when saving values later
		$html = sprintf('<select class="bc-uk-select bc-no-key-field" %1$s>%2$s</select>', $multiple, $html);

		return $html;

	}

	/**
	 * Echos an select element
	 *
	 * @param $setting_field_name
	 * @param $values
	 * @param bool $disabled
	 * @return string|void
	 */
	public function select($setting_field_name, $values, $label = '',
		$disabled = false, $multiple = false, $echo = true)
	{

		$current_value = $this->get_option_value($setting_field_name);

		$multiple_text = $multiple ? 'multiple' : '';

//        dump($current_value);
		$multiple_markup = $multiple ? '[]' : '';
		$disabled = $disabled ? 'disabled' : '';
		$html = sprintf('<select class="bc-uk-select" %2$s name="%1$s%4$s" %3$s>', $this->generate_form_field($setting_field_name), $disabled, $multiple_text, $multiple_markup);

		foreach ($values as $v => $text) {
			if (!$multiple)
				$selected = $v == $current_value ? 'selected' : '';
			else {
				if (is_array($current_value))
					$selected = in_array($v, $current_value) ? 'selected' : '';
				else
					$selected = '';
			}
			$html .= sprintf('<option value="%1$s" %3$s>%2$s</option>', $v, $text, $selected);
		}

		if ($label != '')
			$html = sprintf('<label for="%1$s">%2$s</label>', $setting_field_name, $label) . $html;

		$html  .= '</select>';
		if ($echo)
			echo $html;
		else
			return $html;
	}

	/**
	 * Print out headings
	 *
	 * @param string $content HTML content of the heading, usually just text
	 * @param int $level heading level, similar to h1 to h6 but with smaller text. There are only three levels
	 * with text size 38px, 24px and 18px
	 *
	 * @return string|void
	 *
	 */
	public function heading($content, $level = 1, $echo = true)
	{
		$output = sprintf('<div class="bc-doc-heading-%1$s">%2$s</div>', $level, $content);

		if ($echo)
			echo $output;
		else
			return $output;

	}


	/**
	 * Echos a group of radio elements
	 * values: value => label pair or
	 * value => array(label, disabled, postfix)
	 *
	 * @param $setting_field_name
	 * @param $values
	 * @param string $layout
	 * @param $label_type string either: text (normal text), image(image url), icon_font (icon class)
	 * @param string $title
	 * @param array $dimensions width and height of image or icon, default 16 x 16
	 *
	 * @return string|void
	 */
	public function radio($setting_field_name, $values, $layout = 'row', $label_type = 'text', $title = '', $dimensions = array(16, 16), $echo = true)
	{
		$current_value = $this->get_option_value($setting_field_name);

		$html = '';

		$top_row = array();
		$bottom_row = array();


		//$label is actually an array ['label', 'disabled'] e.g. ['content' => 'Option 1', 'disabled' => false]
		foreach ($values as $v => $label_array) {
			$checked = $v == $current_value ? 'checked' : '';
			$disabled = $label_array['disabled'] ? 'disabled' : '';
			$label_content = $label_array['content'];

			$radio = sprintf('<input class="bc-uk-radio" type="radio" name="%1$s" value="%2$s" %3$s %4$s/> ', $this->generate_form_field($setting_field_name), $v, $checked, $disabled);

			switch ($label_type) {
				case 'text':
					$top_row[] = sprintf('<span>%1$s %2$s&nbsp;&nbsp;</span>', $radio, $label_content);
					break;

				case 'image':
					$top_row[] = sprintf('<a href="%1$s" data-rel="lightcase"><img style="width: %2$s; height: %3$s; margin: auto;" src="%1$s" /></a>', $label_content, $dimensions[0] > 0 ? $dimensions[0] . 'px' : '', $dimensions[1] > 0 ? $dimensions[1] . 'px' : '');
					$bottom_row[] = $radio;
					break;

				case 'icon_font':
					$top_row[] = sprintf('<i class="%1$s"></i>', $label_content);
					$bottom_row[] = $radio;
					break;

				default:
					$top_row[] = sprintf('<p>%1$s</p>', $label_content);
					break;

			}


		}


		$top_row_string = '';

		$bottom_row_string = '';

		foreach ($top_row as $content)
			$top_row_string .= '<td>' . $content . '</td>';

		foreach ($bottom_row as $content)
			$bottom_row_string .= '<td>' . $content . '</td>';

		$html = sprintf('<table><tbody><tr style="text-align: center;">%1$s</tr><tr style="text-align: center;">%2$s</tr></tbody></table>', $top_row_string, $bottom_row_string);


		if ($title != '')
			$html = sprintf('<label class="bc-doc-label">%1$s</label>', $title) . $html;

		if ($echo)
			echo $html;
		else
			return $html;

	}

	/**
	 * @param $content
	 * @param string $flex_class
	 * @param bool $echo
	 *
	 * @return string|void
	 */
	public static function flex_section($content, $flex_class = 'bc-uk-flex-left', $echo = true)
	{
		$html = sprintf('<div class="bc-uk-flex %1$s">', $flex_class);

		foreach ($content as $c)
			$html .= sprintf('<div>%1$s</div>', $c);

		$html .= '</div>';

		if ($echo)
			echo $html;
		else
			return $html;
	}


	/**
	 * Echos an input text element
	 *
	 * @param $setting_field_name
	 * @param string $placeholder
	 * @param bool $disabled
	 * @param int $cols number of rows
	 *
	 * @return void|string
	 */
	public function textarea($setting_field_name, $placeholder = '', $rows = 0, $disabled = false, $echo = true)
	{

		$current_value = $this->get_option_value($setting_field_name);

		$rows_string = $rows > 0 ? sprintf('rows=%1$s', $rows) : "";

		$disabled = $disabled ? 'disabled' : '';
		$html = sprintf('<textarea name="%1$s" %5$s placeholder="%4$s" class="bc-uk-textarea"  %3$s>%2$s</textarea>', $this->generate_form_field($setting_field_name), $current_value, $disabled, $placeholder, $rows_string);



		if ($echo)
			echo $html;
		else
			return $html;
	}

	/**
	 * This is a wrapper function for textarea above but used to accept HTML input
	 * It checks the field name to make sure it contains _save_html
	 *
	 * @param $setting_field_name
	 * @param string $placeholder
	 * @param bool $disabled
	 * @param bool $echo
	 * @param int $rows
	 *
	 * @return string|void
	 *
	 */
	public function html_string($setting_field_name, $placeholder = '', $rows = 0,  $disabled = false, $echo = true)
	{
		if (stripos($setting_field_name, '_save_html') === false)
		{
			echo '<h1 style="color: red;">You want to save as HTML but got the wrong field name. End it with _save_html</h1>';
			return;
		}

		$current_value = esc_html($this->get_option_value($setting_field_name));

		$rows_string = $rows > 0 ? sprintf('rows=%1$s', $rows) : "";

		$disabled = $disabled ? 'disabled' : '';
		$html = sprintf('<textarea name="%1$s" %5$s placeholder="%4$s" class="bc-uk-textarea"  %3$s>%2$s</textarea>', $this->generate_form_field($setting_field_name), $current_value, $disabled, $placeholder, $rows_string);



		if ($echo)
			echo $html;
		else
			return $html;
	}


	/**
	 * Echos an input checkbox element
	 *
	 * @param $setting_field_name
	 * @param bool $disabled
	 * @param string $label
	 * @return string
	 */
	public function checkbox($setting_field_name, $disabled = false, $label = '', $echo = true)
	{

		$current_value = $this->get_option_value($setting_field_name, 'bool');

		$disabled = $disabled ? 'disabled' : '';
		$state = checked(1, $current_value, false);
		$html =  '<div>' .
		         sprintf('<label class="bc-doc-label" for="%1$s"><input type="checkbox" name="%1$s" %2$s %3$s class="bc-uk-checkbox" value="" id="%2$s" /> %4$s &nbsp;&nbsp;</label>', $this->generate_form_field($setting_field_name), $state, $disabled, $label)
		         . '</div>';

		if ($echo)
			echo $html;
		else
			return $html;

	}

	/**
	 * @param $setting_field_name
	 * @param $options array associated array with format key => value where key is the value of a single checkbox and value is the label of that checkbox
	 * @param bool $disabled
	 * @param string $label
	 * @param bool $echo
	 * @param string $dislay display in line or block
	 *
	 * @return void|string
	 */
	public function multiple_checkbox($setting_field_name, $options,  $disabled = false, $label = '', $dislay = 'inline', $echo = true)
	{
		$current_value = $this->get_option_value($setting_field_name, 'array');

		$html = '';


		$display_style = $dislay == 'block' ? 'style="display:block;"' : '';
		foreach ($options as $key => $value)
		{
			$state = in_array($key, $current_value) ? 'checked' : '';
			$html .= sprintf('<label class="bc-doc-label" %6$s for="%1$s"><input type="checkbox" name="%1$s" %2$s %3$s class="bc-uk-checkbox" value="%5$s" id="%2$s" /> %4$s &nbsp;&nbsp;</label>', $this->generate_form_field($setting_field_name), $state, $disabled, $value, $key, $display_style);
		}


		if ($echo)
			echo $html;
		else
			return $html;



	}




	/**
	 * @param bool $echo
	 *
	 * @return string|void
	 */
	public function hr($echo = false)
	{
		if ($echo)
			echo '<hr class="bc-uk-hr" />';
		else
			return '<hr class="bc-uk-hr" />';
	}

	/**
	 * @param $text
	 * @param bool $echo
	 *
	 * @return string|void
	 */
	public function submit_button($text, $echo = true)
	{

		$html =  sprintf('<button name="submit"  type="submit" class="bc-uk-button-primary bc-uk-button bc-form-submit-button" >%1$s</button>', $text);
		if ($echo)
			echo $html;
		else
			return $html;
	}


	/**
	 * TEMPORARY INPUTS
	 *
	 * The input fields that used by JS and don't need to be saved to db
	 *
	 */

}
