<?php
/**
 * Elementor Spoki Button Widget.
 *
 * Elementor widget that inserts a WhatsApp button content into the page.
 *
 * @since 1.0.0
 */
class Elementor_Spoki_Button_Widget extends \Elementor\Widget_Base {

	/**
	 * Get widget name.
	 *
	 * Retrieve Spoki Button widget name.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'spoki_button';
	}

	/**
	 * Get widget title.
	 *
	 * Retrieve Spoki Button widget title.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( 'Spoki WhatsApp Button', 'spoki' );
	}

	/**
	 * Get widget icon.
	 *
	 * Retrieve Spoki Button widget icon.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'fa fa-whatsapp';
	}

	/**
	 * Get widget categories.
	 *
	 * Retrieve the list of categories the Spoki Button widget belongs to.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return array Widget categories.
	 */
	public function get_categories() {
		return [ 'basic' ];
	}

	/**
	 * Register Spoki Button widget controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function _register_controls() {

		$this->start_controls_section(
			'button_section',
			[
				'label' => __( 'Button', 'spoki' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'cta',
			[
				'label' => __( 'Text', 'spoki' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'input_type' => 'text',
				'placeholder' => __( 'Contact us', 'spoki' ),
			]
		);

		$this->add_control(
			'message',
			[
				'label' => __( 'Message', 'spoki' ),
				'description' => __( 'The text of the message the customer will send to phone via WhatsApp on button click.', 'spoki' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'input_type' => 'text',
				'placeholder' => __( 'Hi, I need more info about your company', 'spoki' ),
			]
		);

		$this->add_control(
			'phone',
			[
				'label' => __( 'Phone', 'spoki' ),
				'description' => __( 'The WhatsApp telephone that will receive the message.', 'spoki' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'input_type' => 'text',
				'placeholder' => __( '+393331234567', 'spoki' ),
			]
		);

		$this->add_control(
			'title',
			[
				'label' => __( 'Title', 'spoki' ),
				'description' => __( 'The html title attr of the link of the button.', 'spoki' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'input_type' => 'text',
				'placeholder' => __( '', 'spoki' ),
			]
		);

		$this->add_control(
			'enable_non_working_message',
			[
				'label' => __( 'Enable non-working message', 'spoki' ),
				'description' => __( 'Enable alternative message on non-working days and times.', 'spoki' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
			]
		);

		$this->add_control(
			'non_working_message',
			[
				'label' => __( 'Non-working Message', 'spoki' ),
				'description' => __( 'The customer will send you this message only the non-working days and times.', 'spoki' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'input_type' => 'text',
				'placeholder' => __( 'Hi, I need more info about your company', 'spoki' ),
			]
		);

		$this->add_control(
			'hide_non_working',
			[
				'label' => __( 'Hide on non-working days and times.', 'spoki' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'style_section',
			[
				'label' => __( 'Button', 'spoki' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'font_size',
			[
				'label' => __( 'Font Size', 'spoki' ),
				'type' => \Elementor\Controls_Manager::NUMBER,
				'input_type' => 'number',
				'default' => 12,
				'placeholder' => __( '12', 'spoki' ),
			]
		);

		$this->add_control(
			'color',
			[
				'label' => __( 'Color', 'spoki' ),
				'description' => __( 'Default #23D366', 'spoki' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'input_type' => 'text',
				'default' => '#23D366',
				'placeholder' => __( '#23D366', 'spoki' ),
			]
		);

		$this->add_control(
			'block',
			[
				'label' => __( 'Full Width', 'spoki' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
			]
		);

		$this->add_control(
			'border_type',
			[
				'label' => __( 'Border Type', 'spoki' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'rounded',
				'options' => [
					'rounded'  => __( 'Rounded', 'spoki' ),
					'squared' => __( 'Squared', 'spoki' ),
				],
			]
		);

		$this->add_control(
			'margin',
			[
				'label' => __( 'Margin', 'spoki' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'default' => [
					'top' => '4',
					'right' => '0',
					'bottom' => '4',
					'left' => '0',
					'isLinked' => false,
				],
			]
		);

		$this->add_control(
			'padding',
			[
				'label' => __( 'Margin', 'spoki' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'default' => [
					'top' => '8',
					'right' => '14',
					'bottom' => '8',
					'left' => '14',
					'isLinked' => false,
				],
			]
		);

		$this->end_controls_section();

	}

	/**
	 * Render Spoki Button widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function render() {
		$attrs = $this->get_settings_for_display();
		$hide_non_working = $attrs['hide_non_working'] ?? '';
		$working_days_times_options = Spoki()->options['working_days_times'];
		$is_working_days_times_enabled = isset($working_days_times_options['enabled']) && $working_days_times_options['enabled'] == 1;
		$is_non_working_day_time = spoki_is_non_working_day_time($working_days_times_options);
		if ($hide_non_working && $is_working_days_times_enabled && $is_non_working_day_time) {
			return;
		}

		$phone = $attrs['phone'] !== '' ? $attrs['phone'] : Spoki()->shop['telephone'];
		$cta = $attrs['cta'] ?? '';
		$title = $attrs['title'] ?? '';

		$final_message = urlencode($attrs['message'] ?? '');
		$is_non_working_days_times_text_enabled = $attrs['enable_non_working_message'] ?? '';
		if ($is_non_working_day_time && $is_non_working_days_times_text_enabled) {
			$final_message = urlencode($attrs['non_working_message'] ?? '');
		}

		$additional_class = $attrs['block'] == 'yes' ? 'size-4' : '';
		$type = 6;
		$color = $attrs['color'] ?? null;
		$border_type = $attrs['border_type'] ?? null;
		$font_size = $attrs['font_size'] ?? null;
		$margin = [
			"top" => isset($attrs['margin']['top']) ? $attrs['margin']['top'] : null,
			"bottom" => isset($attrs['margin']['bottom']) ? $attrs['margin']['bottom'] : null,
			"left" => isset($attrs['margin']['left']) ? $attrs['margin']['left'] : null,
			"right" => isset($attrs['margin']['right']) ? $attrs['margin']['right'] : null,
		];
		$padding = [
			"top" => isset($attrs['padding']['top']) ? $attrs['padding']['top'] : null,
			"bottom" => isset($attrs['padding']['bottom']) ? $attrs['padding']['bottom'] : null,
			"left" => isset($attrs['padding']['left']) ? $attrs['padding']['left'] : null,
			"right" => isset($attrs['padding']['right']) ? $attrs['padding']['right'] : null,
		];

		Spoki()->render_relative_button($phone, $cta, $title, $final_message, $additional_class, $type, $margin, $padding, $color, $border_type, $font_size, '');
	}

}