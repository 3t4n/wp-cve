<?php


class fmcWidget extends WP_Widget {

	// holds the path for the view templates
	public $page_view;
	public $admin_page_view;
	public $admin_view_vars;

	public function __construct() {
		global $fmc_plugin_dir;

		// set the view template for the widget
		$class_name = get_class($this);
		$this->page_view = $fmc_plugin_dir . "/views/{$class_name}.php";
		$this->admin_page_view = $fmc_plugin_dir . "/views/admin/{$class_name}.php";

		$this->options = new Fmc_Settings;
	}


	function shortcode_form() {
		global $fmc_widgets;

		$widget_info = $fmc_widgets[ get_class($this) ];

		$settings_content = $this->settings_form( array('_instance_type' => 'shortcode') );

		$response = array(
				'title' => $widget_info['title'] .' widget',
				'body' => flexmlsConnect::shortcode_header() . $settings_content . flexmlsConnect::shortcode_footer()
		);

		echo flexmlsJSON::json_encode($response);

		exit;

	}


	function cache_jelly($args, $instance, $type) {
		global $fmc_widgets;

		$widget_info = $fmc_widgets[ get_class($this) ];

		$cache_item_name = md5(get_class($this) .'_'. serialize($instance) . $type);
		$cache = get_transient('fmc_cache_'. $cache_item_name);

		if (!empty($cache) && flexmlsConnect::cache_turned_on() == true) {
			$return = $cache;
		}
		else {
			$return = $this->jelly($args, $instance, $type);
			$cache_set_result = set_transient('fmc_cache_'. $cache_item_name, $return, $widget_info['max_cache_time']);

			// update transient item which tracks cache items
			$cache_tracker = get_transient('fmc_cache_tracker');
			$cache_tracker[ $cache_item_name ] = true;
			set_transient('fmc_cache_tracker', $cache_tracker, 60*60*24*7);
		}

		return $return;

	}

	// form for generating a widget. used in appearence > widgets. not used for shortcode forms
	function form($instance) {
		echo "<div class='flexmls-widget-settings'>";
		echo $this->settings_form($instance);
		echo "</div>";
	}

	function settings_form($instance) {
    $this->instance = $instance;
    $this->admin_view_vars = $this->admin_view_vars();
    return $this->render_admin_view();
  }

	function shortcode_generate() {

			$shortcode = $this->get_shortcode_string();
			$response = array(
					'body' => $shortcode
			);
		echo  flexmlsJSON::json_encode($response);
		wp_die();
	}

	function get_shortcode_string()
	{
			global $fmc_widgets;

			$widget_info = $fmc_widgets[ get_class($this) ];

			$shortcode = "[{$widget_info['shortcode']}";

			$is_service_lacking_filter_support = false;
			$shortcode_source = flexmlsConnect::wp_input_get_post('source');
			if ($shortcode_source != "location") {
					$is_service_lacking_filter_support = true;
			}
			$is_slideshow_widget = ($widget_info['shortcode'] == "idx_slideshow") ? true : false;

			foreach ($_REQUEST as $k => $v) {
					if ($k == "action") {
							continue;
					}

					if ($is_slideshow_widget && $is_service_lacking_filter_support && ($k == "property_type" || $k == "location")) {
							continue;
					}

					if (!empty($v)) {
							$v = htmlentities(stripslashes($v), ENT_QUOTES);
							$shortcode .= " {$k}=\"{$v}\"";
					}
			}

			$shortcode .= "]";
			return $shortcode;
	}

	function render_view($name = null, $view_vars = null) {
		global $fmc_plugin_dir;
		if ($name == null) {
			$name = $class_name;
		}
		$path = $fmc_plugin_dir . "/views/{$name}.php";
		return $this->render($path, $view_vars);
	}

	function render_admin_view() {
    return $this->render($this->admin_page_view, $this->admin_view_vars);
  }

	protected function render($path_to_view, $view_vars) {
		if (file_exists($path_to_view)) {
			if(is_array($view_vars)){
				extract($view_vars);
			}
			ob_start();
				require($path_to_view);
				$view = ob_get_contents();
			ob_end_clean();
			return $view;
		} else {
			return false;
		}
	}


	function get_field_id($val) {
		$widget = $this->is_called_for_widget();
		if ($widget) {
			return parent::get_field_id($val);
		}
		else {
			return "fmc_shortcode_field_{$val}";
		}
	}


	function get_field_name($val) {
		$widget = $this->is_called_for_widget();
		if ($widget) {
			return parent::get_field_name($val);
		}
		else {
			return $val;
		}
	}


	function is_called_for_widget() {
		// find out what context this was called from
		$backtrace = debug_backtrace();
		if ($backtrace[3]['function'] == "shortcode_form" || $backtrace[3]['function'] == "flex_mls_gtb_cgb_editor_assets") {
			return false;
		}
		else {
			return true;
		}
	}


	function requestVariableArray($key) {
		if ( isset($_GET[$key]) ) {
			if(is_array($_GET[$key])) {
				return $_GET[$key];
			} elseif (is_string($_GET[$key])) {
				return explode(',', $_GET[$key]);
			}
		} else {
			return array();
		}
	}


	protected function label_tag($for, $display_text) {
		echo '<label for="' . $this->get_field_id($for) . '" class="flexmls-admin-field-label">';
			_e($display_text);
		echo '</label>';
	}

	protected function text_field_tag($for, $args = array()) {
		$size = array_key_exists('size', $args) ? $args['size'] : null;
		$class = array_key_exists('class', $args) ? $args['class'] : 'widefat';

		$data_alpha =   array_key_exists('data-alpha', $args) ? "data-alpha='".$args['data-alpha']."'" : '';
		$default = array_key_exists('default', $args) ? $args['default'] : null;
		$value = $this->get_field_value($for) != false ? $this->get_field_value($for) : $default;

		echo "<input fmc-field=\"$for\" fmc-type='text' size='$size' type='text' class='$class'
			id='{$this->get_field_id($for)}' name='{$this->get_field_name($for)}'
			value='{$value}'$data_alpha>";
	}

	protected function font_field_tag($for, $args = array()) {
		$size = array_key_exists('size', $args) ? $args['size'] : null;
		$class = array_key_exists('class', $args) ? $args['class'] : 'widefat';

		$data_alpha =   array_key_exists('data-alpha', $args) ? "data-alpha='".$args['data-alpha']."'" : '';
		$default = array_key_exists('default', $args) ? $args['default'] : null;
		$value = $this->get_field_value($for) != false ? $this->get_field_value($for) : $default;
		$fonts = array_key_exists('fonts', $args) ? $args['fonts'] : fmcWidget::available_fonts();
		$fonts_json_encoded = json_encode( $fonts );

		echo "<input fmc-field=\"$for\" fmc-type='text' size='$size' type='text' class='$class'
			id='{$this->get_field_id($for)}' name='{$this->get_field_name($for)}'
			value='{$value}'$data_alpha data-fonts='{$fonts_json_encoded}'>";
	}

	protected function hidden_field_tag($for, $args = array()) {
		$default = array_key_exists('default', $args) ? $args['default'] : null;
		$value = $this->get_field_value($for) != false ? $this->get_field_value($for) : $default;

		echo "<input fmc-field=\"$for\" fmc-type='text' type='hidden'
			id='{$this->get_field_id($for)}' name='{$this->get_field_name($for)}'
			value='{$value}'>";
	}

	protected function textarea_tag($for, $args = array()) {
		echo "<textarea fmc-field=\"$for\" fmc-type='text' id='{$this->get_field_id($for)}'
			class='flexmls-admin-textarea' name='{$this->get_field_name($for)}'>";
		echo $this->get_field_value($for);
		echo "</textarea>";
	}

	protected function checkbox_tag($for, $args = array()) {
		$default = array_key_exists('default', $args) ? $args["default"] : null;
		$previous_value = $this->get_field_value($for);
		$checked = $default === true ? "checked" : null;

		if ($previous_value === true) {
			$checked = "checked";
		} elseif ($previous_value === false) {
			$checked = null;
		}
		echo "<input fmc-field=\"$for\" type='checkbox' fmc-type='checkbox' name='{$this->get_field_name($for)}'
			id='{$this->get_field_id($for)}' value='true' $checked >";
	}

  protected function select_tag($args) {

    $fmc_field = array_key_exists('fmc_field', $args) ? $args['fmc_field'] : null;
    $collection = array_key_exists('collection', $args) ? $args['collection'] : null;
    $option_value_attr = array_key_exists('option_value_attr', $args) ? $args['option_value_attr'] : null;
    $option_display_attr = array_key_exists('option_display_attr', $args) ? $args['option_display_attr'] : null;
    $class = array_key_exists('class', $args) ? $args['class'] : null;
    $default = array_key_exists('default', $args) ? $args['default'] : null;
		$parent_input_value = array_key_exists('parent_input_value', $args) ? $args['parent_input_value'] : null;
		if ( is_array( $parent_input_value ) ) {
			$parent_input_value = json_encode( $parent_input_value );
		}
		$parent_input_attr = $parent_input_value ? "data-parent-value='{$parent_input_value}'" : '';

    $instance_value = $this->get_field_value($fmc_field);
    $selected_value = $instance_value != false ? $instance_value : $default;

    $output = "<select fmc-field=\"{$fmc_field}\" fmc-type='select' class='{$class}'
      id='{$this->get_field_id($fmc_field)}' name='{$this->get_field_name($fmc_field)}' {$parent_input_attr}>";

    foreach ($collection as $item) {

      $value = $option_value_attr == null ? $item : $item[$option_value_attr];
      $display_text = $option_display_attr == null ? $item : $item[$option_display_attr];

      $selected = $selected_value == $value ? 'selected="selected"' : null;
      $output .= "<option value='{$value}' {$selected}>";
      $output .= $display_text;
      $output .= "</option>";
    }

    $output .= "</select>";
    echo $output;
  }

	protected function get_field_value($field) {
		if (is_array($this->instance) && array_key_exists($field, $this->instance)) {
			$value = $this->instance[$field];
			return ($value === true || $value === false) ? $value : esc_attr($value);
		} else {
			return ($this->is_bool_field($field)) ? "off" : null;
		}
	}

	private function is_bool_field($field) {
		$bool_fields = array("allow_sold_searching");
		return in_array($field, $bool_fields);
	}

	function widget($args, $instance){
	 //This is being overridden in the sub classes for each widget
	}

	static function available_fonts() {
		return [
			'default', 'Arial', 'Verdana', 'Tahoma', 'Times', 'Georgia', 'Garamond'
		];
	}

	function idx_links() {
		$api_links = flexmlsConnect::get_all_idx_links();
		$idx_links = [];

		foreach ($api_links as $l_d) {
			$idx_links []= [
				'value' => $l_d['LinkId'],
				'display_text' => $l_d['Name']
			];
		}

		return $idx_links;
	}


	protected function get_view_property_types() {
		if ( ! $this->is_new_version_widget() ) {
			return parent::get_view_property_types();
		}

		global $fmc_api;
		$output = array();
		$types = $fmc_api->GetPropertyTypes();
		if ( is_array( $types ) ) {
			foreach ($types as $id => $name) {
				$output []= [
					'value' => $id,
					'display_text' => flexmlsConnect::nice_property_type_label( $id )
				];
			}
		}

		return $output;
	}

	function has_new_version_widget() {
		return ! empty( $this->widget_version );
	}

	function is_new_version_widget( $instance = false ) {
		if ( empty( $instance ) && ! empty( $this->instance ) ) {
			$instance = $this->instance;
		}

		if ( $this->has_new_version_widget() ) {
			if ( empty( $instance ) ) {
				// This is a new/clean instance
				return true;
			} else {
				// All potential places for a "new version" widget are enumerated and checked here.
				$has_widget_version = ! empty( $instance['widget_version'] );
				$is_new_gutenberg_widget = ! empty( $instance['_is_gutenberg_new'] );
				$is_new_shortcode_instance = ! empty( $instance['_instance_type'] ) && ( $instance['_instance_type'] == 'shortcode' );
				return $has_widget_version || $is_new_gutenberg_widget || $is_new_shortcode_instance;
			}
		}

		return false;
	}

	function update( $new_instance, $old_instance ) {
		if ( $this->is_new_version_widget( $new_instance ) ) {
			return $this->update_v2( $new_instance, $old_instance );
		} else {
			return $this->update_v1( $new_instance, $old_instance );
		}
	}

	function update_v2( $new_instance, $old_instance ) {
		$instance = $old_instance;

		foreach ( static::settings_fields_v2() as $name => $details ) {
			switch ( $details['type'] ) {
				case 'text':
				case 'color':
				case 'select':
				case 'font':
				case 'toggled_inputs':
				case 'hidden':
					$instance[$name] = strip_tags( $new_instance[$name] );
					break;
				case 'list':
					$instance[$name] = strip_tags( $new_instance[$name] );
					break;
				case 'enabler':
					$instance[$name] = ( $new_instance[$name] == "on" ) ? "on" : "off";
					break;
			}
		}

		return $instance;
	}

	function info_icon( $description ) {
		?>
		<div class="flexmls-info-wrapper">
			<span class="flexmls-info-icon">?</span>
			<div class="description">
				<?php echo esc_html( $description ); ?>
			</div>
		</div>
		<?php
	}
}
