<?php

include_once plugin_dir_path(__FILE__) . '/iframe.widget.php';

class Goracash_Iframe
{
	/**
	 * @var array
	 */
	public static $shortCodeParams = array(
		'type',
		'width',
		'height',
		'tracker',
	);

	public function __construct()
	{
		add_action('widgets_init', function() {
			register_widget('Goracash_Iframe_Widget');
		});
		add_shortcode('goracash_iframe', array($this, 'add_shortcode'));
	}

	/**
	 * @param $type
	 * @return string
	 */
	public static function get_url_from_type($type)
	{
		$urls = array(
			'astro' => 'https://www.news-voyance.com/fr_FR/iframe/',
			'academic' => 'http://www.bonne-note.com/iframe/',
			'academic_subscription' => 'http://www.bonne-note.com/entrainement-en-ligne/iframe/',
			'estimation' => 'http://www.vos-devis.com/iframe/',
			'estimation_pro' => 'http://pro.vos-devis.com/iframe/',
			'juridical' => 'https://partner.juritravail.com/',
			'voslitiges' => 'https://partner.juritravail.com/voslitiges/',
			'rdvmedicaux' => 'https://partner.rdvmedicaux.com/widget',
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
			'astro' => 450,
			'academic' => 800,
			'academic_subscription' => 800,
			'estimation' => 450,
			'estimation_pro' => 450,
			'juridical' => 450,
			'voslitiges' => 450,
			'rdvmedicaux' => 266,
		);
		return $heights[$type];
	}

	/**
	 * @return array
	 */
	public static function get_types()
	{
		return array(
			'astro' => __('Astrology / Fortune Telling', 'goracash'),
			'academic' => __('In-Home Tutoring', 'goracash'),
			'academic_subscription' => __('Academic Subscription', 'goracash'),
			'estimation' => __('Home Renovation Quote', 'goracash'),
			'estimation_pro' => __('Home Renovaton Quote - PRO', 'goracash'),
			'juridical' => __('Law', 'goracash'),
			'voslitiges' => __('Law - Vos Litiges', 'goracash'),
			'rdvmedicaux' => __('Health - RDVMédicaux', 'goracash')
		);
	}

	/**
	 * @param $attributes
	 * @return string
	 */
	public function add_shortcode($attributes)
	{
		$data = array();
		foreach (Goracash_Iframe::$shortCodeParams as $key) {
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
		the_widget('Goracash_Iframe_Widget', $data, $args);
		$output = ob_get_clean();
		return $output;
	}
}