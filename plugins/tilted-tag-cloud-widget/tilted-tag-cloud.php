<?php
/*
Plugin Name: Tilted Tag Cloud Widget
Plugin URI: https://www.whiletrue.it/
Description: Takes the website tags and aggregates them into a tilted cloud widget for sidebar.
Author: WhileTrue
Version: 1.3.18
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

function tilted_tag_cloud($instance)
{

	$plugin_name = 'tilted-tag-cloud';

	// RETRIEVE TAGS
	$words_color   = $instance['words_color'];
	$hover_color   = (isset($instance['hover_color']) && $instance['hover_color'] != '') ? $instance['hover_color'] : 'black';
	$number        = (isset($instance['words_number'])  && is_numeric($instance['words_number'])  && $instance['words_number'] > 0) ? $instance['words_number'] : 20;
	$smallest_size = (isset($instance['smallest_size']) && is_numeric($instance['smallest_size']) && $instance['smallest_size'] > 0) ? $instance['smallest_size'] : 7;
	$largest_size  = (isset($instance['largest_size'])  && is_numeric($instance['largest_size'])  && $instance['largest_size'] > 0) ? $instance['largest_size']  : 14;

	$tags = wp_tag_cloud('smallest=14&largest=30&number=' . $number . '&order=RAND&format=array');

	$out = '';
	$out_style = '';
	foreach ($tags as $num => $tag) {
		$i = $num + 1;
		$out .=  '<span id="' . $plugin_name . '-el-' . $i . '">' . $tag . '</span>';

		$deg = rand(-45, 45);

		$the_color = ($words_color == '') ? '#' . str_pad(dechex(rand(0, 4096)), 3, '0', STR_PAD_LEFT) : $words_color;
		$out_style .=  '
		div#' . $plugin_name . ' span#' . $plugin_name . '-el-' . $i . ' {
			margin-top:' . rand(5, round($instance['vertical_spread'] * ($i + 1) / $i)) . 'px; 
			margin-left:' . rand(0, round($instance['horizontal_spread'] * ($i + 1) / $i)) . 'px; 
			     -moz-transform: rotate(' . $deg . 'deg);  
			       -o-transform: rotate(' . $deg . 'deg);   
			  -webkit-transform: rotate(' . $deg . 'deg);  
			      -ms-transform: rotate(' . $deg . 'deg);  
			          transform: rotate(' . $deg . 'deg);  
		}
		div#' . $plugin_name . ' span#' . $plugin_name . '-el-' . $i . ' a, 
		div#' . $plugin_name . ' span#' . $plugin_name . '-el-' . $i . ' a:visited {
			color:' . $the_color . ';
		}
		';
	}

	$words_color_restore = ($words_color == '') ? '"#"+(Math.random()*0xFFFFFF<<0).toString(16)' : '"' . $words_color . '"';

	return '<div id="' . $plugin_name . '">' . $out . '</div>
	<style>
	div#' . $plugin_name . ' {
		position:relative;
		height:' . ($instance['vertical_spread'] * 3) . 'px;
	}
	div#' . $plugin_name . ' span {
		position:absolute; padding-bottom:8px; z-index:1;
	}
	div#' . $plugin_name . ' a, div#' . $plugin_name . ' a:hover, div#' . $plugin_name . ' a:visited {
		text-decoration:none;
	}
	' . $out_style . '
	</style>
	<script type="text/javascript">
	jQuery(document).ready(function(){
		jQuery("#' . $plugin_name . ' span, #' . $plugin_name . ' span a").hover(
			function () {
		    jQuery(this).css("z-index",10);
		    jQuery(this).css("font-weight","bold");
		    jQuery(this).css("color","' . $hover_color . '");
		  },
		  function () {
		    jQuery(this).css("z-index",0);
		    jQuery(this).css("font-weight","normal");
		    jQuery(this).css("color",' . $words_color_restore . ');
		  }
		);
	});
	</script>
	';
}


//////////


// JQUERY INIT REQUIRED
function tilted_tag_cloud_init()
{
	if (!is_admin()) {
		wp_enqueue_script('jquery');
	}
}
add_action('init', 'tilted_tag_cloud_init');


/**
 * TiltedTagCloudWidget Class
 */
class TiltedTagCloudWidget extends WP_Widget
{
	/** constructor */
	function __construct()
	{
		$this->options = array(
			array('name' => 'title', 'label' => 'Title:', 'type' => 'text'),
			array('name' => 'words_number',      'label' => 'Number of words to show:', 'type' => 'text'),
			array('name' => 'words_color',       'label' => 'Word color (random if not entered):', 'type' => 'text'),
			array('name' => 'hover_color',       'label' => 'Hover color (black if not entered):', 'type' => 'text'),
			array('name' => 'smallest_font',     'label' => 'Smallest font size (default is 7):', 'type' => 'text'),
			array('name' => 'largest_font',      'label' => 'Largest font size (default is 14):', 'type' => 'text'),
			array('name' => 'horizontal_spread', 'label' => 'Horizontal spread in px (default is 60):', 'type' => 'text'),
			array('name' => 'vertical_spread',   'label' => 'Vertical spread in px (default is 60):', 'type' => 'text'),
			array('type' => 'donate'),
		);
		parent::__construct(false, $name = 'Tilted Tag Cloud');
	}

	function widget($args, $instance)
	{
		extract($args);
		$title = apply_filters('widget_title', $instance['title']);
		echo $before_widget;
		if ($title) echo $before_title . $title . $after_title;
		echo tilted_tag_cloud($instance) . $after_widget;
	}

	function update($new_instance, $old_instance)
	{
		$instance = $old_instance;

		foreach ($this->options as $val) {
			if ($val['type'] == 'text') {
				$instance[$val['name']] = strip_tags($new_instance[$val['name']]);
			} else if ($val['type'] == 'checkbox') {
				$instance[$val['name']] = ($new_instance[$val['name']] == 'on') ? true : false;
			}
		}

		return $instance;
	}

	function form($instance)
	{
		if (empty($instance)) {
			$instance['title']             = 'Tilted Tag Cloud';
			$instance['words_number']      = 20;
			$instance['words_color']       = '';
			$instance['smallest_font']     = '7';
			$instance['largest_font']      = '14';
			$instance['horizontal_spread'] = '60';
			$instance['vertical_spread']   = '60';
		}

		foreach ($this->options as $val) {
			echo '<p>
				      <label for="' . $this->get_field_id($val['name']) . '">' . __($val['label'], 'tilted-tag-cloud-widget') . '</label> 
				   ';
			if ($val['type'] == 'text') {
				echo '<input class="widefat" id="' . $this->get_field_id($val['name']) . '" name="' . $this->get_field_name($val['name']) . '" type="text" value="' . esc_attr($instance[$val['name']]) . '" />';
			} else if ($val['type'] == 'checkbox') {
				$checked = ($instance[$val['name']]) ? 'checked="checked"' : '';
				echo '<input id="' . $this->get_field_id($val['name']) . '" name="' . $this->get_field_name($val['name']) . '" type="checkbox" ' . $checked . ' />';
			} else if (isset($val['type']) && $val['type'] == 'donate') {
				echo '<p style="text-align:center; font-weight:bold;">
            ' . __('Do you like it? I\'m supporting it, please support me!', 'tilted-tag-cloud-widget') . '<br />
            <a href="https://www.paypal.com/cgi-bin/webscr?cmd=_donations&business=giu%40formikaio%2eit&item_name=WhileTrue&currency_code=EUR&bn=PP%2dDonationsBF%3abtn_donate_LG%2egif%3aNonHosted" target="_blank">
         			<img alt="PayPal - The safer, easier way to pay online!" src="https://www.paypalobjects.com/en_US/i/btn/btn_donate_LG.gif" > 
            </a>
          </p>';
			}
			echo '</p>';
		}
	}
} // class TiltedTagCloudWidget

// register TiltedTagCloudWidget widget
add_action('widgets_init', create_function('', 'return register_widget("TiltedTagCloudWidget");'));
