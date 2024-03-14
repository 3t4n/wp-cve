<?php
/**
 * Created by PhpStorm.
 * User: myn
 * Date: 5/12/19
 * Time: 6:40 PM
 *
 * class to manage options (options are stored in custom post meta)
 */

namespace DataPeen\FaceAuth;

/**
 * Class BC_Options
 * @package BinaryCarpenter
 *
 * This class is used to save options settings in key->value pair in custom post type meta
 */

class Options
{
	/**this is the post type for all plugins and possible themes created by BC
	 * this is unchanged, the number is added to increase uniqueness
	 */
	const OPTION_POST_TYPE_NAME = 'bc_x1379_op_type';

	/**
	 * Since we may have multiple plugin or even multiple options in one plugin use this class,
	 * this OPTION_NAME_META_KEY is used to filter the matched options for a particular settings.
	 * For example in the BC Cart Menu, the value for this could be @bc_cart_menu_design
	 *
	 * Later, when getting the options back to edit, we can easily request the options that have OPTION_NAME_META_KEY
	 * match  @bc_cart_menu_design
	This constant can be shared between plugins (dont' need to change per plugin)
	 */
	const OPTION_NAME_META_KEY = 'bc_option_name';
	private $post_id, $option_name;


	/**
	 * BC_Options constructor.
	 * @param $option_name string : unique name for each plugin, will be used to retrieve settings
	 * a set of options.
	 * @param $post_id int post_id that store all the settings
	 *
	 * One plugin can have multiple $option_name
	 */
	public function __construct($option_name, $post_id)
	{
		//check if custom post type exists and init it if wasn't
		$this->init_post_type();

		//if $post_id == 0, create a new post and set OPTION_NAME_META_KEY = $option_name
		if ($post_id == 0) {
			$post_id = (wp_insert_post(array(
				'post_title' => '',
				'post_type' => self::OPTION_POST_TYPE_NAME,
				'post_status' => 'publish',
				'post_content' => '',
				'meta_input' => array(
					self::OPTION_NAME_META_KEY => $option_name
				)
			)));
		}
		$this->post_id = $post_id;
		$this->option_name = $option_name;
	}

	/**
	 * Get the posts that has the meta key bc_option_name = $option_name
	 * This is used
	 * @param $option_name
	 * @return \WP_Query
	 */
	public static function get_all_options($option_name)
	{
		$args = array(
			'post_status' => 'publish',
			'post_type' => self::OPTION_POST_TYPE_NAME,
			'post_per_page' => -1,
			'meta_key' => self::OPTION_NAME_META_KEY,

			'meta_query' => array(
				array(
					'key' => self::OPTION_NAME_META_KEY,
					'value' => $option_name,
					'compare' => '='
				)
			)


		);

		return new \WP_Query($args);
	}

	public static function delete_all_options($option_name)
	{
		$all_options = self::get_all_options($option_name);

		if ($all_options->have_posts())
		{
			foreach ($all_options->posts as $post)
				wp_delete_post($post->ID, true);
		}
	}

	/**
	 * For settings that have only one option for the whole life of the plugin, this function
	 * pull the only option out.
	 *
	 * @param $option_name string name of the option
	 *
	 * @return int
	 */
	public static function get_the_only_option_id($option_name)
	{
		$all_options = self::get_all_options($option_name);
		if ($all_options->have_posts())
			return $all_options->posts[0]->ID;
		return 0;

	}


	/**
	 * @param $option_name
	 *
	 * @return Options|null
	 */
	public static function get_the_only_option($option_name)
	{
		$id = self::get_the_only_option_id($option_name);

		if ($id == 0)
			return null;

		return new Options($option_name, $id);
	}

	private function init_post_type()
	{
		if (!post_type_exists(self::OPTION_POST_TYPE_NAME)) {
			add_action('init', array(&$this, 'create_option_post_type'));
		}
	}

	/**
	 * Get the post id of the option
	 * @return int
	 */
	public function get_post_id()
	{
		return $this->post_id;
	}

	public function set_option_name($name)
	{
		$this->set(self::OPTION_NAME_META_KEY, $name, true);
	}

	public function create_option_post_type()
	{
		register_post_type(self::OPTION_POST_TYPE_NAME, array(
			'labels' => array(
				'name' => self::OPTION_POST_TYPE_NAME,
				'singular_name' => self::OPTION_POST_TYPE_NAME
			),
			'public' => true,
			'has_archive' => false,
			'show_ui' => false
		));
	}


	/**
	 * Check if the user is getting the correct option
	 * @return bool
	 */
	public function is_valid()
	{
		return get_post_meta($this->post_id, self::OPTION_NAME_META_KEY, true) == $this->option_name;
	}

	private function get_option_raw($key)
	{
		if (stripos($key, '_save_html'))
			return rawurldecode(get_post_meta($this->post_id, $key, true));
		return unserialize(get_post_meta($this->post_id, $key, true));
	}

	/**
	 * @param $key
	 * @param int $default
	 * @param int $accept_zero accept if the value return zero. If not, return default if the value got from meta is 0
	 * @return int
	 *
	 */
	public function get_int($key, $default = 0, $accept_zero = true)
	{
		if (!$this->is_empty($key))
		{
			$stored_value =  intval($this->get_option_raw($key));


			if ($stored_value == 0)
			{
				if ($accept_zero)
					return $stored_value;
				else
					return $default;
			} else
			{
				return $stored_value;
			}

		}

		return $default;
	}


	public function get_float($key, $default = 0)
	{
		if (!$this->is_empty($key))
			return floatval($this->get_option_raw($key));
		return $default;
	}

	public function get_bool($key, $default = false)
	{
		if (!$this->is_empty($key))
			return $this->get_option_raw($key);
		return $default;
	}


	/**
	 * @param $key
	 * @param string $default
	 * @param bool $accept_blank
	 *
	 * @return mixed|string
	 */
	public function get_html($key, $default = '', $accept_blank = false)
	{
		$output = $this->get_option_raw($key);
		if (!$this->is_empty($key))
		{
			if ($output == '')
			{
				if ($accept_blank)
					return $output;
				else
					return $default;
			}

			return $output;
		}
		return $default;
	}

	/**
	 * @param $key
	 * @param string $default
	 * @param bool $accept_blank
	 *
	 * @return mixed|string
	 */
	public function get_string($key, $default = '', $accept_blank = false)
	{
		$output = $this->get_option_raw($key);
		if (!$this->is_empty($key))
		{
			if ($output == '')
			{
				if ($accept_blank)
					return $output;
				else
					return $default;
			}

			return $output;
		}
		return $default;
	}

	/**
	 * Check if a meta is blank (no value is set)
	 * @param $key
	 */
	public function is_empty($key)
	{
		return !metadata_exists('post', $this->post_id, $key);
	}


	public function get_array($key)
	{
		$result = $this->get_option_raw($key);

		if (!is_array($result))
			return array();

		return $result;
	}

	/**
	 * @param $key
	 * @param $value
	 * @param bool $raw save the value as raw or serialize it
	 */
	public function set($key, $value, $raw = false, $html = false)
	{
		//change the literal boolean (from js) to php boolean
		$value = $value === "true" ? true : $value;
		$value = $value === "false" ? false : $value;

		//sanitize key
		$key = sanitize_text_field($key);
		if ($raw)
			update_post_meta($this->post_id, $key, sanitize_text_field($value));
		else if ($html)
			update_post_meta($this->post_id, $key, wp_filter_post_kses($value));
		else
			update_post_meta($this->post_id, $key, serialize($value));
	}


	public function set_string($key, $value)
	{
		$this->set($key, $value, true);
	}

	public function set_boolean($key, $value)
	{

	}

	public function set_int($key, $value)
	{

	}
	public function unset_key($key)
	{
		delete_post_meta($this->post_id, $key);
	}



}
