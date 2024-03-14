<?php

/**
 * Manages options for Easy Video Reviews
 *
 * @since 1.3.8
 * @package EasyVideoReviews
 */

// Namespace.
namespace EasyVideoReviews\Helper;

// Exit if accessed directly.
defined('ABSPATH') || exit(1);

if ( ! class_exists(__NAMESPACE__ . '\Option') ) {

	/**
	 * Manages options for Easy Video Reviews
	 */
	class Option {

		/**
		 * Singleton instance
		 *
		 * @var self
		 */
		private static $instance;

		/**
		 * Returns the singleton instance
		 *
		 * @return self
		 */
		public static function get_instance() {
			if ( ! self::$instance ) {
				self::$instance = new self();
			}

			return self::$instance;
		}
		/**
		 * Prefix for all options
		 *
		 * @var string
		 */
		public $prefix = 'evr_';

		/**
		 * Get default options
		 *
		 * @return array
		 */
		public function get_defaults() {
			// Option Key pairs.
			$options = [
				'recorder' => [
					'max_video_length'    => 120,
					'max_video_size'      => 1000,
					'allow_upload'        => 0,
					'show_publish_policy' => 0,
					'show_options'        => 0,
					'show_form'           => 0,
					'enable_delay'        => true,
					'delay'               => 3,
					'auto_publish'        => false,
					'publishing_polisy_text' => '',
				],
				'default_button'    => [
					'text'       => esc_html__('Leave a video review', 'easy-video-reviews'),
					'color'      => '#f0f0f0',
					'background' => '#000099',
					'size'       => '18',
					'alignment'  => 'center',
					'custom_css' => '',
				],
				'email_template'    => [
					'message' => esc_html__('If you like the product, please leave a video review for us {button}', 'easy-video-reviews'),
					'button'  => [
						'text'       => esc_html__('Leave a video review', 'easy-video-reviews'),
						'color'      => '#f0f0f0',
						'background' => '#000099',
						'size'       => '18',
						'alignment'  => 'center',
						'custom_css' => '',
					],
					'url'     => [
						'label' => esc_html__('Leave a video review', 'easy-video-reviews'),
					],
				],
				'review_option'       => [
					'enable_video_review' => '1',
					'enable_text_review' => '1',
					'text_review_optional' => '1',
					'allow_choose_review' => '0',
				],
				'enable_floating_widget_review' => '0',
				'enable_woocommerce_review' => '0',
				'woocommerce_gallery_settings' => [],
				'woocommerce_button' => [
					'text'       => esc_html__('Leave a video review', 'easy-video-reviews'),
					'color'      => '#8B54FF',
					'background' => '#FFFFFF',
					'size'       => '16',
					'alignment'  => 'center',
					'border_radius' => '500px',
					'border_color' => '#8B54FF',
				],
				'recording_page_id' => 0,
				'review_page_id'    => 0,
				'review_menu_guide_tooltip' => '1',
				'gallaries'         => [],
				'forms'             => [],
				'translations'      => [],
			];

			return apply_filters('evr_options', $options);
		}

		/**
		 * Get all options
		 *
		 * @return array
		 */
		public function get_all() {
			$defaults = $this->get_defaults();
			$options = [];
			foreach ( $defaults as $key => $value ) {
				$options[ $key ] = $this->get($key, $value);
			}

			return $options;
		}

		/**
		 * Get option
		 *
		 * @param string $key Option key.
		 * @param mixed  $default Default value.
		 *
		 * @return mixed
		 */
		public function get( $key, $default = null ) {
			$option = get_option($this->prefix . $key, $default);

			return maybe_unserialize( $option );
		}

		/**
		 * Update option
		 *
		 * @param string $key Option key.
		 * @param mixed  $value Option value. Default is null.
		 *
		 * @return bool
		 */
		public function update( $key, $value = null ) {

			if ( is_null($value) ) {
				$defaults = $this->get_defaults();
				$value    = $defaults[ $key ];
			}

			$option = update_option($this->prefix . $key, $value);

			return $option;
		}

		/**
		 * Delete option
		 *
		 * @param string $key Option key.
		 *
		 * @return bool
		 */
		public function delete( $key ) {
			$option = delete_option($this->prefix . $key);

			return $option;
		}

		/**
		 * Reset options
		 *
		 * @return bool
		 */
		public function reset() {
			$defaults = $this->get_defaults();

			foreach ( $defaults as $key => $value ) {
				$this->update($key, $value);
			}

			return true;
		}

		/**
		 * Get transient
		 *
		 * @param string $key Transient key.
		 * @param mixed  $default Default value.
		 * @return mixed
		 */
		public function get_transient( $key, $default = null ) {
			$transient = get_transient($this->prefix . $key);

			if ( false === $transient ) {
				$transient = $default;
			}

			return $transient;
		}

		/**
		 * Set transient
		 *
		 * @param string $key Transient key.
		 * @param mixed  $value Transient value.
		 * @param int    $expiration Expiration time in seconds. Default is 0.
		 * @return bool
		 */
		public function set_transient( $key, $value, $expiration = 0 ) {
			$transient = set_transient($this->prefix . $key, $value, $expiration);

			return $transient;
		}

		/**
		 * Delete transient
		 *
		 * @param string $key Transient key.
		 * @return bool
		 */
		public function delete_transient( $key ) {
			$transient = delete_transient($this->prefix . $key);

			return $transient;
		}

		/**
		 * Get first array key
		 *
		 * @param array $array
		 * @return string
		 */
		public function array_first_key( array $array ) {
			foreach ( $array as $key => $value ) {
				return $key;
			}
		}
	}
}
