<?php

class ReadMoreFunctions {

	public static function createSelectBox($params, $name, $selectedValue) {
		// Start building the select box
		$selectBox = "<select name='".esc_attr($name)."' class='selectpicker input-md yrm-js-select2'>";

		// Iterate through the options
		foreach ($params as $value => $optionName) {
			// Check if this option is selected
			$selected = ($value == $selectedValue) ? "selected" : "";

			// Add option to the select box
			$selectBox .= "<option value='".esc_attr($value)."' $selected>".esc_html($optionName)."</option>";
		}

		// Close the select box
		$selectBox .= "</select>";

		return $selectBox;
	}

	public static function yrmSelectBox($data, $selectedValue, $attrs) {
		// Initialize variables
		$attrString = '';
		$selected = '';

		// Construct attribute string
		if (is_array($attrs)) {
			foreach ($attrs as $attrName => $attrValue) {
				$attrString .= esc_attr($attrName) . '="' . esc_attr($attrValue) . '" ';
			}
		}

		// Start building the select box
		$selectBox = '<select ' . wp_kses($attrString, ReadMoreAdminHelper::getAllowedTags()) . '>';

		if (!empty($data) && is_array($data)) {
			foreach ($data as $value => $label) {
				// Check if this option is selected
				if ((is_array($selectedValue) && in_array($value, $selectedValue)) || ($selectedValue == $value)) {
					$selected = 'selected';
				}

				// Add option to the select box
				$selectBox .= '<option value="' . esc_attr($value) . '" ' . esc_attr($selected) . '>' . esc_html($label) . '</option>';
				$selected = '';
			}
		}

		// Close the select box
		$selectBox .= '</select>';

		return $selectBox;
	}

	public static function createRadioButtons($data, $savedValue, $attrs) {
		$attrString = '';

		// Construct attribute string
		if (is_array($attrs)) {
			foreach ($attrs as $attrName => $attrValue) {
				$attrString .= esc_attr($attrName) . '="' . esc_attr($attrValue) . '" ';
			}
		}

		$radioButtons = '';

		// Validate $data
		if (is_array($data)) {
			foreach ($data as $value) {
				$checked = ($value === $savedValue) ? 'checked' : '';

				// Ensure $value is properly escaped
				$value = esc_attr($value);

				$radioButtons .= "<input type=\"radio\" value=\"$value\" $attrString $checked>";
			}
		}

		return $radioButtons;
	}

	public static function getFooterReviewBlock() {

		ob_start();
		?>
		<div class="clear"></div>
		<div class="yrmAdminFooterShell">
			Read More by Edmon Version:
			<a target="_blank" href="http://wordpress.org/plugins/contact-form-by-supsystic/changelog/"><?php echo YRM_VERSION_TEXT; ?></a>
		</div>
		<?php if(YRM_PKG == YRM_FREE_PKG):?>
			<div class="yrmAdminFooterShell">|</div>
			<div class="yrmAdminFooterShell">
				Go&nbsp;<a target="_blank" href="<?php echo YRM_PRO_URL;?>">PRO</a>
			</div>
		<?php endif; ?>
		<div class="yrmAdminFooterShell">|</div>
		<div class="yrmAdminFooterShell">
			<a target="_blank" href="https://wordpress.org/support/plugin/expand-maker">Support</a>
		</div>
		<div class="yrmAdminFooterShell">|</div>
		<div class="yrmAdminFooterShell">
			Add your <a target="_blank" href="https://wordpress.org/support/plugin/expand-maker/reviews/?filter=5">★★★★★</a> on wordpress.org.
		</div>
		<?php
		$output = ob_get_contents();
		ob_end_clean();

		return $output;
	}


	public static function getPostTypeData($args = array())
	{
		$query = self::getQueryDataByArgs($args);

		$posts = array();
		if ($query->have_posts()) {
			while ($query->have_posts()) {
				$query->the_post();
				$posts[get_the_ID()] = get_the_title();
			}
			wp_reset_postdata(); // Reset post data after custom query
		}

		return $posts;
	}

	public static function getQueryDataByArgs($args = array())
	{
		$defaultArgs = array(
			'offset'           =>  0,
			'orderby'          => 'date',
			'order'            => 'DESC',
			'post_status'      => 'publish',
			'suppress_filters' => true,
			'post_type'        => 'post',
			'posts_per_page'   => 1000
		);

		$args = wp_parse_args($args, $defaultArgs);

		// Validate and sanitize any user-provided arguments here if necessary

		$query = new WP_Query($args);

		return $query;
	}
}