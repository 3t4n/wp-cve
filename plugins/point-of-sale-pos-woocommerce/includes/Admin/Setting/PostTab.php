<?php

namespace ZPOS\Admin\Setting;

abstract class PostTab extends Tab
{
	public function __construct()
	{
		parent::__construct();
		if (method_exists(static::class, 'getDefaultValue')) {
			add_filter(
				self::class . '::getDefaultValueByPost',
				[static::class, 'getDefaultValue'],
				10,
				3
			);
		}
	}

	public function savePost($post)
	{
		if (
			!(
				isset($_REQUEST[esc_attr($this->name)]) &&
				wp_verify_nonce($_REQUEST[esc_attr($this->name)], $this->path)
			)
		) {
			return;
		}

		if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
			return;
		}

		if (!current_user_can('edit_post', $post->ID)) {
			return;
		}

		foreach ($this->boxes as $box) {
			foreach ($box->inputs as $input) {
				if (!metadata_exists('post', $post->ID, $input->name)) {
					$defaultValue = apply_filters(
						self::class . '::getDefaultValueByPost',
						null,
						$post,
						$input->name
					);
					update_post_meta($post->ID, $input->name, $defaultValue);
				}
			}
		}

		foreach ($this->boxes as $box) {
			foreach ($box->inputs as $input) {
				$value = $_POST[$input->name];
				$sanitize = $input->sanitize ? $input->sanitize : 'sanitize_text_field';
				$value = call_user_func($sanitize, $value);
				if (is_callable($input->savePost)) {
					call_user_func($input->savePost, $post, $input->name, $value);
				} else {
					update_post_meta($post->ID, $input->name, $value);
				}
			}
		}
	}

	public function settings_fields()
	{
		return wp_nonce_field($this->path, esc_attr($this->name), true, false);
	}

	public static function getValue($name, $post = null)
	{
		if ($post !== null) {
			return self::getValueByPost($post, $name);
		}

		return function ($post) use ($name) {
			return self::getValueByPost($post, $name);
		};
	}

	protected static function getValueByPost($post, $name)
	{
		if (!$post instanceof \WP_Post) {
			$post = get_post($post);
		}

		if (!metadata_exists('post', $post->ID, $name)) {
			$defaultValue = apply_filters(self::class . '::getDefaultValueByPost', null, $post, $name);
			if ($defaultValue !== null) {
				$value = $defaultValue;
			}
		}

		if (!isset($value)) {
			$value = get_post_meta($post->ID, $name, true);
		}

		return apply_filters(self::class . '::getValueByPost', $value, $post, $name);
	}
}
