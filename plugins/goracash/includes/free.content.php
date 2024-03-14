<?php

include_once plugin_dir_path(__FILE__) . '/free.content.widget.php';

class Goracash_Free_Content
{
	/**
	 * @var array
	 */
	public static $shortCodeParams = array(
		'type',
		'width',
		'height',
		'tracker',
		'background-color',
		'text-color',
	);

	public function __construct()
	{
		add_action('widgets_init', function() {
			register_widget('Goracash_Free_Content_Widget');
		});
		add_shortcode('goracash_free_content', array($this, 'add_shortcode'));
	}

	/**
	 * @param $type
	 * @return string
	 */
	public static function get_url_from_type($type)
	{
		$urls = array(
			'daily_horoscope' => 'http://www.goracash.com/iframe/iframe_content.php?cat=3',
			'horoscope_hug_of_the_day' => 'http://www.goracash.com/iframe/iframe_content.php?cat=12',
			'love_tip_of_the_day' => 'http://www.goracash.com/iframe/iframe_content.php?cat=1',
			'spotlight' => 'http://www.goracash.com/iframe/iframe_content.php?cat=2',
			'prediction_of_the_day' => ' http://www.goracash.com/iframe/iframe_content.php?cat=4',
			'surname_of_the_day' => 'http://www.goracash.com/iframe/iframe_content.php?cat=5',
			'dream_of_the_day' => 'http://www.goracash.com/iframe/iframe_content.php?cat=6',
			'ritual_of_the_day' => 'http://www.goracash.com/iframe/iframe_content.php?cat=7',
			'sign_of_the_day' => 'http://www.goracash.com/iframe/iframe_content.php?cat=8',
			'testimony_of_the_day' => 'http://www.goracash.com/iframe/iframe_content.php?cat=9',
			'theme_of_the_day' => 'http://www.goracash.com/iframe/iframe_content.php?cat=10',
			'seeing_of_the_day' => 'http://www.goracash.com/iframe/iframe_content.php?cat=11',
 		);
		return $urls[$type];
	}

	/**
	 * @param $type
	 * @return integer
	 */
	public static function get_height_from_type($type)
	{
		$heights = array(
			'daily_horoscope' => 500,
			'horoscope_hug_of_the_day' => 500,
			'love_tip_of_the_day' => 500,
			'spotlight' => 500,
			'prediction_of_the_day' => 500,
			'surname_of_the_day' => 500,
			'dream_of_the_day' => 500,
			'ritual_of_the_day' => 500,
			'sign_of_the_day' => 500,
			'testimony_of_the_day' => 500,
			'theme_of_the_day' => 500,
			'seeing_of_the_day' => 500,
		);
		return $heights[$type];
	}

	/**
	 * @return array
	 */
	public static function get_types()
	{
		return array(
			'daily_horoscope' => __('Daily horoscope', 'goracash'),
			'horoscope_hug_of_the_day' => __('Sex daily horoscope', 'goracash'),
			'love_tip_of_the_day' => __('Love tip of the day', 'goracash'),
			'spotlight' => __('Highlight on...', 'goracash'),
			'prediction_of_the_day' => __('Prediction of the day', 'goracash'),
			'surname_of_the_day' => __('Name of the day', 'goracash'),
			'dream_of_the_day' => __('Dream of the day', 'goracash'),
			'ritual_of_the_day' => __('Ritual of the day', 'goracash'),
			'sign_of_the_day' => __('Sign of the day', 'goracash'),
			'testimony_of_the_day' => __('Testimony of the day', 'goracash'),
			'theme_of_the_day' => __('Theme of the day', 'goracash'),
			'seeing_of_the_day' => __('Reader of the day', 'goracash'),
		);
	}

	/**
	 * @param $attributes
	 * @return string
	 */
	public function add_shortcode($attributes)
	{
		$data = array();
		foreach (Goracash_Free_Content::$shortCodeParams as $key) {
			$shortcode_key = strtolower($key);
			if (isset($attributes[$shortcode_key])) {
				$data[$key] = $attributes[$shortcode_key];
			}
		}

		$args = array(
			'before_widget' => '<div>',
			'after_widget'  => '</div>',
			'before_title'  => '<div>',
			'after_title'   => '</div>',
		);

		ob_start();
		the_widget('Goracash_Free_Content_Widget', $data, $args);
		$output = ob_get_clean();
		return $output;
	}
}