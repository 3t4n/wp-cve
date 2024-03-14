<?php

namespace Elementor;

use Thim_EL_Kit\GroupControlTrait;

class Thim_Ekit_Widget_Course_Buttons extends Widget_Base {
	use GroupControlTrait;

	public function __construct( $data = array(), $args = null ) {
		parent::__construct( $data, $args );
	}

	public function get_name() {
		return 'thim-ekits-course-buttons';
	}

	public function get_title() {
		return esc_html__( ' Course Buttons', 'thim-elementor-kit' );
	}

	public function get_icon() {
		return 'thim-eicon eicon-button';
	}

	public function get_categories() {
		return array( \Thim_EL_Kit\Elementor::CATEGORY_SINGLE_COURSE );
	}

	public function get_help_url() {
		return '';
	}

	protected function register_controls() {
		$this->start_controls_section(
			'section_image_style',
			array(
				'label' => esc_html__( 'Buttons', 'thim-elementor-kit' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_responsive_control(
			'align',
			array(
				'label'     => esc_html__( 'Alignment', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => array(
					'left'   => array(
						'title' => esc_html__( 'Left', 'thim-elementor-kit' ),
						'icon'  => 'eicon-text-align-left',
					),
					'center' => array(
						'title' => esc_html__( 'Center', 'thim-elementor-kit' ),
						'icon'  => 'eicon-text-align-center',
					),
					'right'  => array(
						'title' => esc_html__( 'Right', 'thim-elementor-kit' ),
						'icon'  => 'eicon-text-align-right',
					),
				),
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .thim-ekit-single-course__buttons' => 'text-align: {{VALUE}}',
					'{{WRAPPER}}  button'                           => 'text-align: {{VALUE}}',
				),
			)
		);

		$this->add_responsive_control(
			'button_width',
			array(
				'label'     => esc_html__( 'Width %', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::NUMBER,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}}  button' => 'width: {{VALUE}}%;',
					'{{WRAPPER}} form'    => 'display:block;',
				),
			)
		);

		$this->register_button_style( 'global', 'button' );

		$this->end_controls_section();
		// Start now button
		$this->start_controls_section(
			'section_start_style',
			array(
				'label' => esc_html__( 'Start', 'thim-elementor-kit' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);
		$this->register_icon_button( 'start_now' );
		$this->register_button_style( 'start_now', 'form button.button-enroll-course' );
		$this->end_controls_section();

		// Continue button
		$this->start_controls_section(
			'section_continue_style',
			array(
				'label' => esc_html__( 'Continue', 'thim-elementor-kit' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);
		$this->register_icon_button( 'continue' );
		$this->register_button_style( 'continue', 'form[name="continue-course"] button' );
		$this->end_controls_section();

		// Purchase button
		$this->start_controls_section(
			'section_purchase_style',
			array(
				'label' => esc_html__( 'Purchase', 'thim-elementor-kit' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);
		$this->register_icon_button( 'purchase' );
		$this->register_button_style( 'purchase', 'form[name="purchase-course"] button' );
		$this->end_controls_section();
		// Finish button
		$this->start_controls_section(
			'section_finish_style',
			array(
				'label' => esc_html__( 'Finish', 'thim-elementor-kit' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->register_icon_button( 'finish' );
		$this->register_button_style( 'finish', 'form button.lp-btn-finish-course' );
		$this->end_controls_section();

		// Retake button
		$this->start_controls_section(
			'section_retake_style',
			array(
				'label' => esc_html__( 'Retake', 'thim-elementor-kit' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);
		$this->register_icon_button( 'retake' );
		$this->register_button_style( 'retake', 'form button.button-retake-course' );
		$this->end_controls_section();

		// External button
		$this->start_controls_section(
			'section_external_style',
			array(
				'label' => esc_html__( 'External link', 'thim-elementor-kit' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);
		$this->register_icon_button( 'external' );
		$this->register_button_style( 'external', 'form[name="course-external-link"] button' );
		$this->end_controls_section();

	}

	protected function register_icon_button( string $prefix_name ) {
		$this->add_control(
			$prefix_name . '_icons',
			[
				'label'       => esc_html__( 'Icon', 'thim-elementor-kit' ),
				'type'        => Controls_Manager::ICONS,
				'skin'        => 'inline',
				'label_block' => false,
			]
		);
		$this->add_control(
			$prefix_name . '_btn_text',
			array(
				'label'       => esc_html__( 'Button Text', 'thim-elementor-kit' ),
				'label_block' => true,
				'type'        => Controls_Manager::TEXT,
				'default'     => '',
			)
		);
	}

	public function render() {
		do_action( 'thim-ekit/modules/single-course/before-preview-query' );

		$course = learn_press_get_course();

		if ( ! $course ) {
			return;
		}
		$list_buttons = array( 'enroll', 'purchase', 'continue', 'external', 'finish', 'retake' );
		foreach ( $list_buttons as $list_button ) {
			add_filter(
				'learn-press/' . $list_button . '-course-button-text',
				function ( $text ) use ( $list_button ) {
					if ( $list_button == 'enroll' ) {
						$list_button = 'start_now';
					}
					$settings = $this->get_settings_for_display();
					if ( ! empty( $settings[$list_button . '_icons']['value'] ) ) {
						Icons_Manager::render_icon( $settings[$list_button . '_icons'], array( 'aria-hidden' => 'true' ) );
					}
					if ( $settings[$list_button . '_btn_text'] ) {
						$text = $settings[$list_button . '_btn_text'];
					}
					echo $text;
				}
			);
		}
		?>

		<div class="thim-ekit-single-course__buttons">
			<?php
			do_action( 'learn-press/before-course-buttons' );

			do_action( 'learn-press/course-buttons' );

			do_action( 'learn-press/after-course-buttons' );
			?>
		</div>

		<?php
		do_action( 'thim-ekit/modules/single-course/after-preview-query' );
	}
}
