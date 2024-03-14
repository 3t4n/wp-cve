<?php
/*
Plugin Name: Categorized Tag Cloud
Plugin URI: https://www.whiletrue.it/
Description: Takes the website tags and aggregates them into a categorized cloud widget for sidebar.
Author: WhileTrue
Version: 1.2.24
Author URI: https://www.whiletrue.it/
*/

/*
    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License version 2, 
    as published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.
*/

function categorized_tag_cloud($instance)
{

	$plugin_name = 'categorized-tag-cloud';

	// RETRIEVE TAGS
	$words_color   = $instance['words_color'];
	$hover_color   = (isset($instance['hover_color'])   && $instance['hover_color'] != '') ? $instance['hover_color'] : 'black';
	$number        = (isset($instance['words_number'])  && is_numeric($instance['words_number'])  && $instance['words_number'] > 0) ? $instance['words_number'] : 20;
	$smallest_font = (isset($instance['smallest_font']) && is_numeric($instance['smallest_font']) && $instance['smallest_font'] > 0) ? $instance['smallest_font'] : 7;
	$largest_font  = (isset($instance['largest_font'])  && is_numeric($instance['largest_font'])  && $instance['largest_font'] > 0) ? $instance['largest_font']  : 14;
	$order         = (isset($instance['order']) && $instance['order'] == 'a') ? 'ASC'  : 'RAND';

	$exclude_items = [];
	$category_filters = (array) json_decode($instance['category_filters']);
	if (!isset($category_filters['cat']) || !is_array($category_filters['cat'])) {
		$category_filters['cat'] = [];
	}
	for ($i = 0; $i < count($category_filters['cat']); $i++) {
		if (is_category($category_filters['cat'][$i]) || (is_single() && in_category($category_filters['cat'][$i]))) {
			$exclude_items[] = $category_filters['tag'][$i];
		}
	}

	$tags = wp_tag_cloud('smallest=' . $smallest_font . '&largest=' . $largest_font  . '&number=' . $number . '&order=' . $order . '&format=array&exclude=' . implode(',', $exclude_items));

	$out = '';
	$out_style = '
  		#' . $plugin_name . ' a, #' . $plugin_name . ' a:visited { text-decoration:none; }
      #' . $plugin_name . ' a:hover { text-decoration:none; color:' . $hover_color . '; }';
	if (is_array($tags)) {
		foreach ($tags as $num => $tag) {
			$i = $num + 1;
			$out .=  '<span id="' . $plugin_name . '-el-' . $i . '">' . $tag . '</span> ';

			$the_color = ($words_color == '') ? '#' . str_pad(dechex(rand(0, 4096)), 3, '0', STR_PAD_LEFT) : $words_color;
			$out_style .=  '
    		#' . $plugin_name . '-el-' . $i . ' a, #' . $plugin_name . '-el-' . $i . ' a:visited { color:' . $the_color . '; }';
		}
	}

	return '
    <div id="' . $plugin_name . '">' . $out . '</div>
  	<style>
  	' . $out_style . '
  	</style>';
}


//////////


/**
 * CategorizedTagCloudWidget Class
 */
class CategorizedTagCloudWidget extends WP_Widget
{
	private
		/** @type {string} */
		$languagePath;

	/** constructor */
	function __construct()
	{
		$this->languagePath = basename(dirname(__FILE__)) . '/lang';
		load_plugin_textdomain('categorized-tag-cloud', 'false', $this->languagePath);

		$this->options = [
			[
				'name' => 'title',             'label' => 'Title:',
				'type' => 'text'
			],
			[
				'name' => 'words_number',      'label' => 'How many tags to show:',
				'type' => 'text'
			],
			[
				'name' => 'words_color',       'label' => 'Word color (random if not entered):',
				'type' => 'text'
			],
			[
				'name' => 'hover_color',       'label' => 'Hover color (black if not entered):',
				'type' => 'text'
			],
			[
				'name' => 'smallest_font',     'label' => 'Smallest font size (default is 7):',
				'type' => 'text'
			],
			[
				'name' => 'largest_font',      'label' => 'Largest font size (default is 14):',
				'type' => 'text'
			],
			[
				'name' => 'order',      'label' => 'Order:',
				'type' => 'radio',      'values' => ['' => 'Random', 'a' => 'Alphanumeric'],
			],
			[
				'label' => 'Category filters',
				'type'	=> 'separator'
			],
			[
				'type'	=> 'category_filters'
			],
			[
				'type'	=> 'donate'
			],
		];

		$control_ops = ['width' => 500];
		parent::__construct(false, 'Categorized Tag Cloud', [], $control_ops);
	}

	function widget($args, $instance)
	{
		extract($args);
		$title = apply_filters('widget_title', $instance['title']);
		echo $before_widget;
		if ($title) echo $before_title . $title . $after_title;
		echo categorized_tag_cloud($instance) . $after_widget;
	}

	function update($new_instance, $old_instance)
	{
		$instance = $old_instance;

		foreach ($this->options as $val) {
			if ($val['type'] == 'text' || $val['type'] == 'radio') {
				$instance[$val['name']] = strip_tags($new_instance[$val['name']]);
			} else if ($val['type'] == 'checkbox') {
				$instance[$val['name']] = ($new_instance[$val['name']] == 'on') ? true : false;
			}

			// CATEGORY FILTERS
			$instance['category_filters'] = '';

			if (isset($_POST['categorized-tag-cloud-num-filters']) && is_numeric($_POST['categorized-tag-cloud-num-filters'])) {
				$instance['category_filters'] = [];
				for ($i = 0; $i < $_POST['categorized-tag-cloud-num-filters']; $i++) {
					if ($_POST['categorized-tag-cloud-cat-' . $i] == '' && $_POST['categorized-tag-cloud-tag-' . $i] == '') {
						continue;
					}
					$instance['category_filters']['cat'][] = esc_html($_POST['categorized-tag-cloud-cat-' . $i]);
					$instance['category_filters']['tag'][] = esc_html($_POST['categorized-tag-cloud-tag-' . $i]);
				}
				$instance['category_filters'] = json_encode($instance['category_filters']);
			}
		}

		return $instance;
	}

	function form($instance)
	{
		if (empty($instance)) {
			$instance['title']             = 'Categorized Tag Cloud';
			$instance['words_number']      = 20;
			$instance['words_color']       = '';
			$instance['smallest_font']     = '7';
			$instance['largest_font']      = '14';
			$instance['horizontal_spread'] = '60';
			$instance['vertical_spread']   = '60';
			$instance['order']             = '';
		}

		foreach ($this->options as $val) {
			if ($val['type'] == 'separator') {
				if (isset($val['label']) && $val['label'] != '') {
					echo '<h3>' . __($val['label'], 'categorized-tag-cloud') . '</h3>';
				} else {
					echo '<hr />';
				}
				if (isset($val['notes']) && $val['notes'] != '') {
					echo '<div class="description">' . $val['notes'] . '</div>';
				}
			} else if ($val['type'] == 'category_filters') {
				$category_filters = (array) json_decode($instance['category_filters']);
				if (!is_array($category_filters)) {
					$category_filters = [];
				}
				if (!isset($category_filters['cat']) || !is_array($category_filters['cat'])) {
					$category_filters['cat'] = [];
				}
				echo '<input name="categorized-tag-cloud-num-filters" type="hidden" value="' . (count($category_filters) + 2) . '" />';
				echo '<table>
          <tr>
            <th>' . __('category slug', 'categorized-tag-cloud') . '</th>
            <th style="min-width:300px">' . __('excluded tags id, comma separated', 'categorized-tag-cloud') . '</th>
          </tr>';
				for ($i = 0; $i < count($category_filters['cat']); $i++) {
					echo '
            <tr>
              <td><input type="text" name="categorized-tag-cloud-cat-' . $i . '" value="' . $category_filters['cat'][$i] . '" /></td>
              <td><input type="text" name="categorized-tag-cloud-tag-' . $i . '" value="' . $category_filters['tag'][$i] . '" style="min-width:300px" /></td></tr>';
				}
				for ($j = $i; $j < ($i + 2); $j++) {
					echo '
            <tr>
              <td><input type="text" name="categorized-tag-cloud-cat-' . $j . '" /></td>
              <td><input type="text" name="categorized-tag-cloud-tag-' . $j . '" style="min-width:300px" /></td></tr>';
				}
				echo '</table>';
			} else if ($val['type'] == 'donate') {
				echo '<p style="text-align:center; font-weight:bold;">
            ' . __('Do you like it? I\'m supporting it, please support me!', 'categorized-tag-cloud') . '<br />
            <a href="https://www.paypal.com/cgi-bin/webscr?cmd=_donations&business=giu%40formikaio%2eit&item_name=WhileTrue&currency_code=EUR&bn=PP%2dDonationsBF%3abtn_donate_LG%2egif%3aNonHosted" target="_blank">
         			<img alt="PayPal - The safer, easier way to pay online!" src="https://www.paypalobjects.com/en_US/i/btn/btn_donate_LG.gif" > 
            </a>
          </p>';
			} else if ($val['type'] == 'text') {
				echo '<p>
  				      <label for="' . $this->get_field_id($val['name']) . '">' . __($val['label'], 'categorized-tag-cloud') . '</label> 
  				   ';
				echo '<input class="widefat" id="' . $this->get_field_id($val['name']) . '" name="' . $this->get_field_name($val['name']) . '" type="text" value="' . esc_attr($instance[$val['name']]) . '" />';
				echo '</p>';
			} else if ($val['type'] == 'radio') {
				echo '<p>
  				      <label for="' . $this->get_field_id($val['name']) . '">' . __($val['label'], 'categorized-tag-cloud') . '</label> 
  				   ';
				foreach ($val['values'] as $k => $v) {
					$checked = ($instance[$val['name']] == $k) ? 'checked="checked"' : '';
					echo ' &nbsp; <input id="' . $this->get_field_id($val['name']) . '" name="' . $this->get_field_name($val['name']) . '" value="' . $k . '" type="radio" ' . $checked . ' /> ' . $v . ' &nbsp; ';
				}
				echo '</p>';
			} else if ($val['type'] == 'checkbox') {
				echo '<p>
  				      <label for="' . $this->get_field_id($val['name']) . '">' . __($val['label'], 'categorized-tag-cloud') . '</label> 
  				   ';
				$checked = ($instance[$val['name']]) ? 'checked="checked"' : '';
				echo '<input id="' . $this->get_field_id($val['name']) . '" name="' . $this->get_field_name($val['name']) . '" type="checkbox" ' . $checked . ' />';
				echo '</p>';
			}
		}
	}
} // class CategorizedTagCloudWidget

// register CategorizedTagCloudWidget widget
add_action('widgets_init', function () {
	return register_widget("CategorizedTagCloudWidget");
});
