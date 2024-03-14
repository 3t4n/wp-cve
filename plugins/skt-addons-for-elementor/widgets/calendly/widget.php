<?php
/**
 * Calendly widget class
 *
 * @package Skt_Addons_Elementor
 */

namespace Skt_Addons_Elementor\Elementor\Widget;

use Elementor\Controls_Manager;


defined( 'ABSPATH' ) || die();

class Calendly extends Base {

	/**
	 * Get widget title.
	 *
	 * @return string Widget title.
	 * @since 1.0
	 * @access public
	 *
	 */
	public function get_title() {
		return __( 'Calendly', 'skt-addons-elementor' );
	}

    public function get_custom_help_url() {
        return '#';
    }

    /**
	 * Get widget icon.
	 *
	 * @return string Widget icon.
	 * @since 1.0
	 * @access public
	 *
	 */
	public function get_icon() {
		return 'skti skti-calendar';
	}

	public function get_keywords() {
		return [ 'info', 'blurb', 'box', 'text', 'content' ];
	}

	/**
     * Register widget content controls
     */
	protected function register_content_controls() {
		
		$this->start_controls_section(
			'_section_calendly',
			[
				'label' => __( 'Calendly', 'skt-addons-elementor' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'calendly_username',
			[
				'label'       => __( 'Username', 'skt-addons-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => 'sittestaccount',
				'placeholder' => __( 'Type calendly username here', 'skt-addons-elementor' ),
			]
		);

		$this->add_control(
			'calendly_time',
			[
				'label'   => __( 'Time Slot', 'skt-addons-elementor' ),
				'type'    => Controls_Manager::SELECT,
				'options' => [
					'15min' => __( '15 Minutes', 'skt-addons-elementor' ),
					'30min' => __( '30 Minutes', 'skt-addons-elementor' ),
					'60min' => __( '60 Minutes', 'skt-addons-elementor' ),
					'' => __( 'All', 'skt-addons-elementor' ),
				],
				'default' => '30min'
			]
		);

		$this->add_control(
			'event_type_details',
			[
				'label'        => __( 'Hide Event Type Details', 'skt-addons-elementor' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'yes', 'skt-addons-elementor' ),
				'label_off'    => __( 'no', 'skt-addons-elementor' ),
				'return_value' => 'yes',
				'default'      => '',
			]
		);

		$this->add_responsive_control(
			'height',
			[
				'label'      => __( 'Height', 'skt-addons-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range'      => [
					'px' => [
						'min'  => 10,
						'max'  => 1000,
						'step' => 5,
					],
					'%'  => [
						'min' => 5,
						'max' => 100,
					],
				],
				'default'    => [
					'unit' => 'px',
					'size' => '630',
				],
				'selectors'  => [
					'{{WRAPPER}} .calendly-inline-widget' => 'height: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .calendly-wrapper'       => 'height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();
	}

	/**
	 * Register widget style controls
	 */
	protected function register_style_controls() {
		$this->start_controls_section(
			'_section_style_calendly',
			[
				'label' => __( 'Calendly', 'skt-addons-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'_calendly_pro_notice',
			[
				'type' => Controls_Manager::RAW_HTML,
				'raw' => sprintf(
					__( 'The following color customization controls only work with %s. Basic and Premium plan users cannot customize colors as per Calendy pricing plan. For more information please %s.', 'skt-addons-elementor' ),
					'<a href="https://calendly.com/pages/pricing" target="_blank">Calendly Pro plan</a>',
					'<a href="https://calendly.com/pages/pricing" target="_blank">click here</a>'
				),
				'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
			]
		);

		$this->add_control(
			'text_color',
			[
				'label' => __( 'Text Color', 'skt-addons-elementor' ),
				'type'  => Controls_Manager::COLOR,
				'alpha' => false,
			]
		);

		$this->add_control(
			'button_link_color',
			[
				'label' => __( 'Button & Link Color', 'skt-addons-elementor' ),
				'type'  => Controls_Manager::COLOR,
			]
		);

		$this->add_control(
			'background_color',
			[
				'label' => __( 'Background Color', 'skt-addons-elementor' ),
				'type'  => Controls_Manager::COLOR,
			]
		);

		$this->end_controls_section();
	}

	protected function render() {
		$settings = $this->get_settings_for_display();
		$calendly_time = $settings['calendly_time']!=''?"/{$settings['calendly_time']}":'';
		?>
		<?php if ( $settings['calendly_username'] ): ?>
            <div class="calendly-inline-widget"
                 data-url="https://calendly.com/<?php echo esc_attr( $settings['calendly_username'] ); ?><?php echo esc_attr( $calendly_time ); ?>/?<?php if ( 'yes' === $settings['event_type_details'] ): echo esc_attr('hide_event_type_details=1'); endif; ?><?php if ( $settings['text_color'] ): echo esc_attr("&text_color=" . str_replace( '#', '', $settings['text_color'] )); endif; ?><?php if ( $settings['button_link_color'] ): echo esc_attr("&primary_color=" . str_replace( '#', '', $settings['button_link_color'] )); endif; ?><?php if ( $settings['background_color'] ): echo esc_attr("&background_color=" . str_replace( '#', '', $settings['background_color'] )); endif; ?>"
                 style="min-width:320px;"></div>
            <script type="text/javascript" src="https://assets.calendly.com/assets/external/widget.js"></script>
			<?php if ( \Elementor\Plugin::$instance->editor->is_edit_mode() ) : ?>
                <div class="calendly-wrapper" style="width:100%; position:absolute; top:0; left:0; z-index:100;"></div>
			<?php endif; ?>
		<?php endif; ?>
		<?php
	}
}