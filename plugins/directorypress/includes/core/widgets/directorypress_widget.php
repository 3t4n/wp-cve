<?php

abstract class directorypress_widget extends WP_Widget {
	protected $fields = array();

	public function __construct($id_base, $name, $description = '') {
		$options["description"] = $description;
		
		parent::__construct($id_base, $name, $options);
		
		$this->addField("textfield", "title", __("Title", "DIRECTORYPRESS"));

		// enqueue scripts and styles only for directorytype widgets and when "Show only on directorytype pages" setting is OFF or this setting does not exist for this widget
		add_action('wp_enqueue_scripts', array($this, 'wp_enqueue_scripts'));
		add_action('wp_enqueue_scripts', array($this, 'wp_enqueue_custom_style'), 9999);
		add_action('wp_head', array($this, 'enqueue_dynamic_css'), 9999);
	}
	
	public function wp_enqueue_scripts() {
		global $directorypress_object;
		
		$widget_options_all = get_option($this->option_name);
		if (isset($widget_options_all[$this->number])) {
			$current_widget_options = $widget_options_all[$this->number];
			if (is_active_widget(false, false, $this->id_base, true) && (empty($current_widget_options['visibility']) || !empty($directorypress_object->public_handlers))) {
				//$directorypress_object->enqueue_scripts_styles(true);
			}
		}
	}
	
	public function wp_enqueue_custom_style() {
		global $directorypress_object;
		
		$widget_options_all = get_option($this->option_name);
		if (isset($widget_options_all[$this->number])) {
			$current_widget_options = $widget_options_all[$this->number];
			if (is_active_widget(false, false, $this->id_base, true) && (empty($current_widget_options['visibility']) || !empty($directorypress_object->public_handlers))) {
				//$directorypress_object->enqueue_scripts_styles_custom(true);
			}
		}
	}
	
	public function enqueue_dynamic_css() {
		global $directorypress_object;
		
		$widget_options_all = get_option($this->option_name);
		if (isset($widget_options_all[$this->number])) {
			$current_widget_options = $widget_options_all[$this->number];
			if (is_active_widget(false, false, $this->id_base, true) && (empty($current_widget_options['visibility']) || !empty($directorypress_object->public_handlers))) {
				//$directorypress_object->enqueue_dynamic_css(true);
			}
		}
	}
	
	public function addField($type, $name = false, $label = false, $description = false, $options = array(), $std = false, $dependency = array()) {
		$field = new stdClass();
		
		$field->name = $name;
		$field->type = $type;
		$field->label = $label;
		$field->description = $description;
		$field->options = $options;
		$field->std = $std;
		$field->dependency = $dependency;

		$this->fields[] = $field;
	}

	public function convertParams($params) {
		foreach ($params AS $param) {
			$type = false;
			$name = false;
			$label = false;
			$description = false;
			$options = array();
			$std = false;
			$dependency = false;
			if (isset($param['type'])) {
				$type = $param['type'];
			}
			if (isset($param['param_name'])) {
				$name = $param['param_name'];
			}
			if (isset($param['heading'])) {
				$label = $param['heading'];
			}
			if (isset($param['description'])) {
				$description = $param['description'];
			}
			if (isset($param['value'])) {
				if (is_array($param['value'])) {
					$options = $param['value'];
				} else {
					$std = $param['value'];
				}
			}
			if (isset($param['std'])) {
				$std = $param['std'];
			}
			if (isset($param['dependency'])) {
				$dependency = $param['dependency'];
			}
			$this->addField($type, $name, $label, $description, $options, $std, $dependency);
		}
	}
	
	public function update($new_instance, $old_instance) {
		foreach($this->fields as $field){
			$field = (object) $field;
			
			if (isset($new_instance[$field->name])) {
				$old_instance[$field->name] = $new_instance[$field->name];
			} else {
				$old_instance[$field->name] = '';
			}
		}
		
		return $old_instance;
	}
	
	public function renderLabel($field, $id){
		if (!empty($field->label)) {
			echo "<label for=\"{$id}\">" . esc_html($field->label) . "</label>";
		}
	}

	public function renderDescription($field){
		if (!empty($field->description)) {
			echo "<span class=\"directorypress-widget-description\">" . esc_html($field->description) . "</span>";
		}
	}

	public function renderDependency($field){
		if ($field->dependency) {
			echo "<span class=\"directorypress-widget-dependency\" data-dependency-element=\"{$field->dependency['element']}\" data-dependency-value=\"{$field->dependency['value']}\"></span>";
		}
	}

	public function checkDependency($original_field, $instance){
		if ($original_field->dependency) {
			foreach ($this->fields AS $field) {
				$value = isset($instance[$field->name]) ? $instance[$field->name] : $field->std;
				if (is_array($value)) {
					if ($field->name == $original_field->dependency['element'] && !in_array($original_field->dependency['value'], $value)) {
						return "style=\"display:none\"";
					}
				} else {
					if ($field->name == $original_field->dependency['element'] && $value != $original_field->dependency['value']) {
						return "style=\"display:none\"";
					}
				}
			}
		}
	}
	
	public function form($instance) {
		echo '<script>
				(function($) {
					"use strict";
			
					$(function() {
							var inputs = $(".widget-content, .so-content").find("input, select").not(":input[type=text], :input[type=submit], :input[type=reset]");
							inputs.each(function() {
								$(this).on("change", function() {
									var input = $(this);
									var dependencies = input.parents(".widget-content, .so-content").find(".directorypress-widget-dependency");
									dependencies.each(function() {
										var name = $(this).data("dependency-element");
										var value = $(this).data("dependency-value");
										if (input.data("original-name") == name) {
											if (input.val() == value) {
												$(this).parent("p").show();
											} else {
												$(this).parent("p").hide();
											}
										}
									});
								});
							});
					});
				})(jQuery);
				</script>';
		
		foreach($this->fields as $key => $field){
			$field = (object) $field;
			
			$method_name = "render_{$field->type}_field";
			
			$this->$method_name($field, $instance);
		}
	}
	
	public function widget($args, $instance) {
		$this->render_widget($instance, $args);
	}
	
	abstract public function render_widget($instance, $args);
	
	public function render_textfield_field($field, $instance) {
		$id = $this->get_field_id($field->name);
		$name = $this->get_field_name($field->name);
		$value = isset($instance[$field->name]) ? esc_attr($instance[$field->name]) : $field->std;
		
		echo "<p " . wp_kses_post($this->checkDependency($field, $instance)) . ">";
		$this->renderDependency($field);
		$this->renderLabel($field, $id);
		echo "<input class=\"widefat\" id=\"{$id}\" name=\"{$name}\" type=\"text\" value=\"{$value}\" />";
		$this->renderDescription($field);
		echo "</p>";
	}
	
	public function render_hidden_field($field, $instance) {
		$id = $this->get_field_id($field->name);
		$name = $this->get_field_name($field->name);
		$value = isset($instance[$field->name]) ? esc_attr($instance[$field->name]) : $field->std;
		
		echo "<input id=\"{$id}\" name=\"{$name}\" type=\"hidden\" value=\"{$value}\" />";
	}

	public function render_dropdown_field($field, $instance) {
		$id = $this->get_field_id($field->name);
		$name = $this->get_field_name($field->name);
		$value = isset($instance[$field->name]) ? esc_attr($instance[$field->name]) : null;
		$std = $field->std;
		
		echo "<p " . wp_kses_post($this->checkDependency($field, $instance)) . ">";
		$this->renderDependency($field);
		$this->renderLabel($field, $id);
		echo "<select class=\"widefat\" id=\"{$id}\" name=\"{$name}\" class=\"widefat directorypress-widget-select-control\" data-original-name=\"{$field->name}\">";
		foreach ($field->options as $label=>$option_val){
			if (is_int($label)) {
				$label = $option_val;
			}

			if ((!is_null($value) && $option_val == $value) || (is_null($value) && $option_val === $std)) {
				$selected = " selected=\"selected\"";
			} else {
				$selected = "";
			}
			
			echo "<option value=\"{$option_val}\"{$selected}>{$label}</option>";
		}
		echo "</select>";
		$this->renderDescription($field, $id);
		echo "</p>";
	}
	
	public function render_textarea_field($field, $instance) {
		$id = $this->get_field_id($field->name);
		$name = $this->get_field_name($field->name);
		$value = isset($instance[$field->name]) ? esc_attr($instance[$field->name]) : $field->std;
		
		echo "<p " . wp_kses_post($this->checkDependency($field, $instance)) . ">";
		$this->renderDependency($field);
		$this->renderLabel($field, $id);
		echo "<textarea id=\"{$id}\" name=\"{$name}\" style=\"display:block;width:100%;\">{$value}</textarea>";
		$this->renderDescription($field, $id);
		echo "</p>";
	}

	public function render_mapstyle_field($field, $instance) {
		$id = $this->get_field_id($field->name);
		$name = $this->get_field_name($field->name);
		$value = isset($instance[$field->name]) ? $instance[$field->name] : 0;
			
		echo "<p " . wp_kses_post($this->checkDependency($field, $instance)) . ">";
		$this->renderDependency($field);
		$this->renderLabel($field, $id);

		echo "<select id=\"{$id}\" name=\"{$name}\" class=\"widefat directorypress-widget-select-control\">";
		echo "<option value=\"0\" " . selected($value, 0) . ">" . __('Default', 'DIRECTORYPRESS') . "</option>";
		foreach (directorypress_map_styles() AS $style_name=>$style) {
			echo "<option value=\"{$style_name}\" " . selected($value, $style_name) . ">{$style_name}</option>";
		}
		echo "</select>";
		$this->renderDescription($field, $id);
		echo "</p>";
	}
	
	public function render_ordering_field($field, $instance) {
		$id = $this->get_field_id($field->name);
		$name = $this->get_field_name($field->name);
		$value = isset($instance[$field->name]) ? $instance[$field->name] : '';
		
		$ordering = directorypress_sorting_options();
		//var_dump($ordering);
		echo "<p " . wp_kses_post($this->checkDependency($field, $instance)) . ">";
		$this->renderDependency($field);
		$this->renderLabel($field, $id);
		//print_r($ordering);
		echo "<select id=\"{$id}\" name=\"{$name}\" class=\"widefat directorypress-widget-select-control\">";
		echo "<option value=\"\" " . selected($value, '', false) . ">" . __('Default', 'DIRECTORYPRESS') . "</option>";
		foreach ($ordering AS $key=>$label) {
			//$value = $ordering_item['value'];
			//$label = $ordering_item['label'];
			
			echo "<option value=\"{$key}\" " . selected($value, $key) . ">{$label}</option>";
		
		}
		echo "</select>";
		$this->renderDescription($field, $id);
		echo "</p>";
	}
	
	public function render_packages_field($field, $instance) {
		$this->render_package_field($field, $instance, true);
	}

	public function render_package_field($field, $instance, $multiple = false) {
		global $directorypress_object;

		$id = $this->get_field_id($field->name);
		$name = $this->get_field_name($field->name);
		$value = isset($instance[$field->name]) ? $instance[$field->name] : 0;
		
		$this->outputJSforMultipleDropBox();
		echo "<p " . wp_kses_post($this->checkDependency($field, $instance)) . ">";
		$this->renderDependency($field);
		$this->renderLabel($field, $id);
	
		echo "<select id=\"{$name}" . ($multiple ? "_select" : "") . "\" name=\"{$name}" . ($multiple ? "_select" : "") . "\" " . ($multiple ? "multiple=\"multiple\" class=\"widefat directorypress-widget-select-multiple-control\"" : "class=\"widefat directorypress-widget-select-control\"") . " data-original-name=\"{$field->name}\">";
		echo "<option value=\"0\" " . selected($value, 0) . ">" . __('- All packages -', 'DIRECTORYPRESS') . "</option>";
		foreach ($directorypress_object->packages->packages_array AS $package) {
			echo "<option value=\"{$package->id}\" " . (in_array($package->id, explode(',', $value)) ? "selected=\"selected\"" : "") . ">{$package->name}</option>";
		}
		echo "</select>";
		if ($multiple) {
			echo "<input type=\"hidden\" id=\"{$name}\" name=\"{$name}\" value=\"{$value}\" />";
		}
		$this->renderDescription($field, $id);
		echo "</p>";
	}
	
	public function render_directorytypes_field($field, $instance) {
		$this->render_directorytype_field($field, $instance, true);
	}

	public function render_directorytype_field($field, $instance, $multiple = false) {
		global $directorypress_object;

		if ($directorypress_object->directorytypes->isMultiDirectory()) {
			$id = $this->get_field_id($field->name);
			$name = $this->get_field_name($field->name);
			$value = isset($instance[$field->name]) ? $instance[$field->name] : 0;
			
			$this->outputJSforMultipleDropBox();
			echo "<p " . wp_kses_post($this->checkDependency($field, $instance)) . ">";
			$this->renderDependency($field);
			$this->renderLabel($field, $id);
			echo "<select id=\"{$name}" . ($multiple ? "_select" : "") . "\" name=\"{$name}" . ($multiple ? "_select" : "") . "\" " . ($multiple ? "multiple=\"multiple\" class=\"widefat directorypress-widget-select-multiple-control\"" : "class=\"widefat directorypress-widget-select-control\"") . ">";
			echo "<option value=\"0\" " . selected($value, 0) . ">" . __('- Auto -', 'DIRECTORYPRESS') . "</option>";
			foreach ($directorypress_object->directorytypes->directorypress_array_of_directorytypes AS $directorytype) {
				echo "<option value=\"{$directorytype->id}\" " . (in_array($directorytype->id, explode(',', $value)) ? "selected=\"selected\"" : "") . ">{$directorytype->name}</option>";
			}
			echo "</select>";
			if ($multiple) {
				echo "<input type=\"hidden\" id=\"{$name}\" name=\"{$name}\" value=\"{$value}\" />";
			}
			$this->renderDescription($field, $id);
			echo "</p>";
		}
	}
	
	public function render_categoriesfield_field($field, $instance) {
		$this->render_categoryfield_field($field, $instance, true);
	}

	public function render_categoryfield_field($field, $instance, $multiple = false) {
		$id = $this->get_field_id($field->name);
		$name = $this->get_field_name($field->name);
		$value = isset($instance[$field->name]) ? $instance[$field->name] : '';
		
		$this->outputJSforMultipleDropBox();
		echo "<p " . wp_kses_post($this->checkDependency($field, $instance)) . ">";
		$this->renderDependency($field);
		$this->renderLabel($field, $id);
		echo "<select id=\"{$name}" . ($multiple ? "_select" : "") . "\" name=\"{$name}" . ($multiple ? "_select" : "") . "\" " . ($multiple ? "multiple=\"multiple\" class=\"widefat directorypress-widget-select-multiple-control\"" : "class=\"widefat directorypress-widget-select-control\"") . ">";
		echo "<option value=\"\" " . selected($value, '') . ">" . __('- No category selected -', 'DIRECTORYPRESS') . "</option>";
		ob_start();
		directorypress_renderOptionsTerms(DIRECTORYPRESS_CATEGORIES_TAX, 0, explode(',', $value));
		echo ob_get_clean();
		echo "</select>";
		if ($multiple) {
			echo "<input type=\"hidden\" id=\"{$name}\" name=\"{$name}\" value=\"{$value}\" />";
		}
		$this->renderDescription($field, $id);
		echo "</p>";
	}

	public function render_locationsfield_field($field, $instance) {
		$this->render_locationfield_field($field, $instance, true);
	}

	public function render_locationfield_field($field, $instance, $multiple = false) {
		$id = $this->get_field_id($field->name);
		$name = $this->get_field_name($field->name);
		$value = isset($instance[$field->name]) ? $instance[$field->name] : '';
		
		$this->outputJSforMultipleDropBox();
		echo "<p " . wp_kses_post($this->checkDependency($field, $instance)) . ">";
		$this->renderDependency($field);
		$this->renderLabel($field, $id);
		echo "<select id=\"{$name}" . ($multiple ? "_select" : "") . "\" name=\"{$name}" . ($multiple ? "_select" : "") . "\" " . ($multiple ? "multiple=\"multiple\" class=\"widefat directorypress-widget-select-multiple-control\"" : "class=\"widefat directorypress-widget-select-control\"") . ">";
		echo "<option value=\"\" " . selected($value, '') . ">" . __('- No location selected -', 'DIRECTORYPRESS') . "</option>";
		ob_start();
		directorypress_renderOptionsTerms(DIRECTORYPRESS_LOCATIONS_TAX, 0, explode(',', $value));
		echo ob_get_clean();
		echo "</select>";
		if ($multiple) {
			echo "<input type=\"hidden\" id=\"{$name}\" name=\"{$name}\" value=\"{$value}\" />";
		}
		$this->renderDescription($field, $id);
		echo "</p>";
	}

	public function render_contentfields_field($field, $instance) {
		global $directorypress_object;

		$id = $this->get_field_id($field->name);
		$name = $this->get_field_name($field->name);
		$value = isset($instance[$field->name]) ? $instance[$field->name] : '';
		$fields_ids = explode(',', $value);
		
		$this->outputJSforMultipleDropBox();
		echo "<p " . wp_kses_post($this->checkDependency($field, $instance)) . ">";
		$this->renderDependency($field);
		$this->renderLabel($field, $id);
		echo "<select id=\"{$name}_select\" name=\"{$name}_select\" multiple=\"multiple\" class=\"widefat directorypress-widget-select-multiple-control\">";
		echo "<option value=\"\" " . selected($value, '') . ">" . __('- All content fields -', 'DIRECTORYPRESS') . "</option>";
		echo "<option value=\"-1\" " . selected($value, -1) . ">" . __('- No content fields -', 'DIRECTORYPRESS') . "</option>";
		foreach ($directorypress_object->search_fields->search_fields_array AS $search_field) {
			echo "<option value=\"{$search_field->field->id}\" " . (in_array($search_field->field->id, $fields_ids) ? 'selected' : '') . ">{$search_field->field->name}</option>";
		}
		echo "</select>";
		echo "<input type=\"hidden\" id=\"{$name}\" name=\"{$name}\" value=\"{$value}\" />";
		$this->renderDescription($field, $id);
		echo "</p>";
	}
	
	public function outputJSforMultipleDropBox() {
		$out = '<script>
				jQuery("body").on("change", ".directorypress-widget-select-multiple-control", function() {
					jQuery(this).next("input").val($(this).val());
					
				})
				</script>';
		echo wp_kses_post($out);
	}

	public function render_checkbox_field($field, $instance) {
		$id = $this->get_field_id($field->name);
		$name = $this->get_field_name($field->name);
		if (!$field->options) {
			$value = isset($instance[$field->name]) ? $instance[$field->name] : $field->std;
		} else {
			$value = isset($instance[$field->name]) ? $instance[$field->name] : $field->std;
		}

		echo "<p " . wp_kses_post($this->checkDependency($field, $instance)) . ">";
		$this->renderDependency($field);
		if (!$field->options) {
			$checked = "";
			if ($value == 1) {
				$checked = "checked = \"checked\"";
			}
			echo "<input id=\"{$id}\" name=\"{$name}\" type=\"checkbox\" value=\"1\" {$checked} data-original-name=\"{$field->name}\" />";
			$this->renderLabel($field, $id);
		} else {
			$this->renderLabel($field, $id);
			foreach ($field->options AS $option_name=>$key) {
				$checked = "";
				if (is_array($value) && in_array($key, $value)) {
					$checked = "checked = \"checked\"";
				}
				echo "<br />";
				echo "<label>";
				echo "<input name=\"{$name}[]\" type=\"checkbox\" value=\"{$key}\" {$checked}/>";
				echo wp_kses_post($option_name);
				echo "</label>";
			}
		}
		$this->renderDescription($field, $id);
		echo "</p>";
	}

	public function render_colorpicker_field($field, $instance) {
		$id = $this->get_field_id($field->name);
		$name = $this->get_field_name($field->name);
		$value = isset($instance[$field->name]) ? esc_attr(strip_tags($instance[$field->name])) : $field->std;

		echo '<script>
				(function($) {
					"use strict";
					$(function() {
						$(".directorypress-select-color").wpColorPicker({
							change: function(event, ui) {
								$(this).parents("form").find("input[type=submit]").trigger("change");
								$(this)
								.val(ui.color.toString())
								.trigger("change");
							}
						});
					});
				})(jQuery);
			</script>';
		echo "<p " . wp_kses_post($this->checkDependency($field, $instance)) . ">";
		$this->renderDependency($field);
		$this->renderLabel($field, $id);
		echo "<input
				type=\"text\"
				id=\"{$id}\"
				name=\"{$name}\"
				value=\"{$value}\"
				class=\"directorypress-select-color widefat\"
			/>";
		$this->renderDescription($field, $id);
		echo "</p>";
	}
	
	public function render_datefieldmin_field($field, $instance) {
		$id = $this->get_field_id($field->name);
		$name = $this->get_field_name($field->name);
		$value = isset($instance[$field->name]) ? esc_attr(strip_tags($instance[$field->name])) : $field->std;
		if (!is_numeric($value)) {
			$value = strtotime($value);
		}

		$settings['field_id'] = $id;
		$settings['param_name'] = $name;
		echo "<p " . wp_kses_post($this->checkDependency($field, $instance)) . ">";
		$this->renderDependency($field);
		$this->renderLabel($field, $id);
		directorypress_display_template('directorypress-search-fields/fields/datetime_input_vc_min.php', array('settings' => $settings, 'value' => $value, 'dateformat' => directorypress_dpf()));
		$this->renderDescription($field, $id);
		echo "</p>";
	}

	public function render_datefieldmax_field($field, $instance) {
		$id = $this->get_field_id($field->name);
		$name = $this->get_field_name($field->name);
		$value = isset($instance[$field->name]) ? esc_attr(strip_tags($instance[$field->name])) : $field->std;
		if (!is_numeric($value)) {
			$value = strtotime($value);
		}

		$settings['field_id'] = rand();
		$settings['param_name'] = $name;
		echo "<p " . wp_kses_post($this->checkDependency($field, $instance)) . ">";
		$this->renderDependency($field);
		$this->renderLabel($field, $id);
		directorypress_display_template('directorypress-search-fields/fields/datetime_input_vc_max.php', array('settings' => $settings, 'value' => $value, 'dateformat' => directorypress_dpf()));
		$this->renderDescription($field, $id);
		echo "</p>";
	}

	public function render_hr_field($field, $instance) {
		echo "<hr/>";
	}
}

add_action('admin_enqueue_scripts', 'directorypress_widgets_enqueue_scripts');
function directorypress_widgets_enqueue_scripts($hook) {
	if ($hook == "widgets.php"){
		wp_enqueue_style( 'wp-color-picker' );
		wp_enqueue_script( 'wp-color-picker' );
	}
}

add_action('widgets_init', 'directorypress_register_widgets');
function directorypress_register_widgets() {
	foreach (get_declared_classes() as $class) {
		if (is_subclass_of($class, "directorypress_widget")) {
			register_widget($class);
		}
	}
}
