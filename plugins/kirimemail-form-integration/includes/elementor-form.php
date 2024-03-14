<?php
namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use Elementor\Core\Schemes;

/**
 * Elementor button widget.
 *
 * Elementor widget that displays a button with the ability to control every
 * aspect of the button design.
 *
 * @since 1.0.0
 */
class Widget_KirimEmail extends Widget_Base {

	/**
	 * Get widget name.
	 *
	 * Retrieve button widget name.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'ke_form';
	}

	/**
	 * Get widget title.
	 *
	 * Retrieve button widget title.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( 'KIRIM.EMAIL Form', 'elementor' );
	}

	/**
	 * Get widget icon.
	 *
	 * Retrieve button widget icon.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'eicon-code';
	}

	/**
	 * Get widget categories.
	 *
	 * Retrieve the list of categories the button widget belongs to.
	 *
	 * Used to determine where to display the widget in the editor.
	 *
	 * @since 2.0.0
	 * @access public
	 *
	 * @return array Widget categories.
	 */
	public function get_categories() {
		return [ 'basic' ];
	}

	public static function get_forms() {
		$forms = (new self)->get_api_class()->get_form(['raw_data' => TRUE]);
		$result = [];

		foreach ($forms['data'] as $form) {
			$result[$form['id']] = $form['name'];
		}

		return $result;
	}

	/**
	 * Register button widget controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function _register_controls() {
		$this->start_controls_section(
			'section_button',
			[
				'label' => __( 'KIRIM.EMAIL Form', 'elementor' ),
			]
		);

		$this->add_control(
			'form_id',
			[
				'label' => __( 'Form', 'elementor' ),
				'type' => Controls_Manager::SELECT,
				'dynamic' => [
					'active' => true,
				],
				'default' => '',
				'options' => self::get_forms()
			]
		);

		$this->add_control(
			'styling',
			[
				'label' => __( 'Styling', 'elementor' ),
				'type' => Controls_Manager::SELECT,
				'dynamic' => [
					'active' => true,
				],
				'default' => 0,
				'options' => [
					0 => __('Without Styling', 'elementor'),
					1 => __('With Styling', 'elementor')
				]
			]
		);

		$this->add_control(
			'hide_title_and_desc',
			[
				'label' => __( 'Hide title and description', 'elementor' ),
				'type' => Controls_Manager::SELECT,
				'dynamic' => [
					'active' => true,
				],
				'default' => 0,
				'options' => [
					0 => __('No', 'elementor'),
					1 => __('Yes', 'elementor')
				]
			]
		);

		$this->add_responsive_control(
			'font_size',
			[
				'label' => __( 'Font Size', 'elementor' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 12,
						'max' => 30,
					],
				],
				'dynamic' => [
					'active' => true,
				],
			]
		);

		$this->add_control(
			'background_color',
			[
				'label' => __( 'Background Color', 'elementor' ),
				'type' => Controls_Manager::COLOR,
				'dynamic' => [
					'active' => true,
				],
			]
		);

		$this->add_control(
			'font_color',
			[
				'label' => __( 'Font Color', 'elementor' ),
				'type' => Controls_Manager::COLOR,
				'dynamic' => [
					'active' => true,
				],
			]
		);

		$this->add_control(
			'button_background_color',
			[
				'label' => __( 'Submit Button Background Color', 'elementor' ),
				'type' => Controls_Manager::COLOR,
				'dynamic' => [
					'active' => true,
				],
			]
		);

		$this->add_control(
			'button_font_color',
			[
				'label' => __( 'Submit Button Font Color', 'elementor' ),
				'type' => Controls_Manager::COLOR,
				'dynamic' => [
					'active' => true,
				],
			]
		);

		$this->end_controls_section();
	}

	/**
	 * Render button widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();

		if (!empty($settings['form_id'])) {
			$form = $this->get_api_class()->get_form_detail($settings['form_id']);

			if (isset($form['data'][0])) {
				if ($settings['styling'] == 0 && isset($form['data'][0]['form-html-nostyle'])) {
					echo htmlspecialchars_decode($form['data'][0]['form-html-nostyle']);
				} else if ($settings['styling'] == 1 && isset($form['data'][0]['form-html'])) {
					echo htmlspecialchars_decode($form['data'][0]['form-html']);
				}

				$extra_style = '';

				if ($settings['hide_title_and_desc'] == 1) {
					$extra_style .= '.kirimemail-form-headline,.kirimemail-form-description{display:none!important}';
				}

				if (!empty($settings['background_color'])) {
					$extra_style .= '.kirimemail-content{background-color:' . $settings['background_color'] . '!important}';
				}

				if (!empty($settings['font_color'])) {
					$extra_style .= '#keform input, #keform select, #keform textarea{color:' . $settings['font_color'] . '!important}';
				}

				if (!empty($settings['button_background_color'])) {
					$extra_style .= '.kirimemail-btn-submit{box-shadow:none!important;background-color:' . $settings['button_background_color'] . '!important}';
				}

				if (!empty($settings['button_font_color'])) {
					$extra_style .= '.kirimemail-btn-submit{color:' . $settings['button_font_color'] . '!important}';
				}

				if (!empty($settings['font_size'])) {
					$extra_style .= '#keform input, #keform select, #keform textarea{font-size:' . $settings['font_size']['size'] . 'px!important}';
				}

				if (!empty($extra_style)) {
					echo '<style>' . esc_html($extra_style) . '</style>';
				}
			}
		}
	}

	public function get_api_class() {
		$KEMAIL_WPFORM_API = new \Kemail_Api();
		return $KEMAIL_WPFORM_API;
	}
}
