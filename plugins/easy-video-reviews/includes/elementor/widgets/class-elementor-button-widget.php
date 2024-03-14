<?php
/**
 * Easy Video Reviews - Elementor Button Widget Class for Button
 * Elementor Button Widget
 *
 * @package EasyVideoReviews
 */
namespace EasyVideoReviews\Elementor\Widget;

// Exit if accessed directly.
defined('ABSPATH') || exit(1);

// Use Elementor Classes.
use Elementor\Widget_Base; //phpcs:ignore

/**
 * Elementor Widget Class
 */


if ( ! class_exists( __NAMESPACE__ . '/Button' ) ) {

	/**
	 * Class Button
	 *
	 * @package EasyVideoReviews\Elementor\Widget
	 */
	class Button extends Widget_Base {


		// Use Utilities trait.
		use \EasyVideoReviews\Traits\Utilities;

		/**
		 * Get widget name.
		 *
		 * @return string Widget name.
		 */
		public function get_name() {
			return 'evr-buttons';
		}

		/**
		 * Get widget title.
		 *
		 * @return string Widget title.
		 */
		public function get_title() {
			return esc_html__( 'Easy Video Reviews Button', 'easy-video-reviews' );
		}

		/**
		 * Get widget icon.
		 *
		 * @return string Widget icon.
		 */
		public function get_icon() {
			return 'evr-logo-icon';
		}

		/**
		 * Get widget categories.
		 *
		 * @return array Widget categories.
		 */
		public function get_categories() {
			return [ 'general' ];
		}

		/**
		 * Register widget controls.
		 */
		protected function register_controls() {

			$this->start_controls_section(
				'section_content',
				[
					'label' => esc_html__( 'Button', 'easy-video-reviews' ),
				]
			);

			$this->add_control(
				'button_label',
				[
					'label'   => esc_html__( 'Button Label', 'easy-video-reviews' ),
					'type'    => 'text',
					'default' => 'Record Review',
				]
			);

			$this->add_control(
				'button_bg_color',
				[
					'label'   => esc_html__( 'Button Background Color', 'easy-video-reviews' ),
					'type'    => 'color',
					'default' => 'blue',
				]
			);
			$this->add_control(
				'button_text_color',
				[
					'label'   => esc_html__( 'Button Text Color', 'easy-video-reviews' ),
					'type'    => 'color',
					'default' => 'white',
				]
			);

			$this->add_control(
				'button_size',
				[
					'label'      => esc_html__( 'Size', 'easy-video-reviews' ),
					'type'       => 'slider',
					'size_units' => [ 'px' ],
					'range'      => [
						'min' => 1,
						'max' => 72,
					],
					'default'    => [
						'unit' => 'px',
						'size' => 20,
					],
				]
			);

			$this->add_control(
				'button_alignment',
				[
					'label'   => esc_html__( 'Button Alignment', 'easy-video-reviews' ),
					'type'    => 'choose',
					'options' => [
						'justify-start'   => [
							'title' => esc_html__( 'Left', 'easy-video-reviews' ),
							'icon'  => 'dashicons dashicons-editor-alignleft',
						],
						'justify-center' => [
							'title' => esc_html__( 'Center', 'easy-video-reviews' ),
							'icon'  => 'dashicons dashicons-editor-aligncenter',
						],
						'justify-end'  => [
							'title' => esc_html__( 'Right', 'easy-video-reviews' ),
							'icon'  => 'dashicons dashicons-editor-alignright',
						],
					],
					'default' => 'center',
				]
			);

			global $evr_folders;

			if ( ! $evr_folders ) {
				$evr_folders = $this->client()->folders();
			}

			$option = $this->option();

			$default = count( $evr_folders ) > 0 ? $option->array_first_key( $evr_folders ) : 0;

			$all_folders = [];
			foreach ( $evr_folders as $folder ) {
				$all_folders[ (string) $folder->id ] = $folder->name;
			}

			// add Select Folder with all folders.
			$all_folders = [ '0' => esc_html__( 'Select Folder', 'easy-video-reviews' ) ] + $all_folders;

			$this->add_control(
				'folder',
				[
					'label'    => esc_html__( 'Save videos in', 'easy-video-reviews' ),
					'type'     => 'select2',

					'options'  => $all_folders,
					'default'  => $default,
					'multiple' => false,
				]
			);

			$this->end_controls_section();
		}


		/**
		 * Render widget output on the frontend.
		 *
		 * @return void
		 */
		protected function render() {
			$settings = $this->get_settings_for_display();

			$this->add_inline_editing_attributes( 'label', 'basic' );

			$button = '[recorder align="' . esc_attr( $settings['button_alignment'] ) . '" size="' . esc_attr( $settings['button_size']['size'] ) . '" color="' . esc_html( $settings['button_text_color'] ) . '" background="' . esc_html( $settings['button_bg_color'] ) . '" folder="' . esc_html( $settings['folder'] ) . '"]' . esc_html( $settings['button_label'] ) . '[/recorder]';

			echo is_admin() ? wp_kses_post( do_shortcode( $button ) ) : wp_kses_post( $button );
		}

		/**
		 * Render widget output in the editor.
		 *
		 * @return array
		 */
		public function get_keywords() {
			return [ 'evr', 'easy', 'video', 'review', 'reviews', 'testimonial', 'wppool' ];
		}
	}
}
