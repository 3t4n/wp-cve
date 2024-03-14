<?php
namespace Elementor;

use Elementor\Plugin;

class Thim_Ekit_Widget_Course_Instructor extends Widget_Base {

	public function __construct( $data = array(), $args = null ) {
		parent::__construct( $data, $args );
	}

	public function get_name() {
		return 'thim-ekits-course-instructor';
	}

	public function get_title() {
		return esc_html__( ' Course Instructor', 'thim-elementor-kit' );
	}

	public function get_icon() {
		return 'thim-eicon eicon-person';
	}

	public function get_categories() {
		return array( \Thim_EL_Kit\Elementor::CATEGORY_SINGLE_COURSE );
	}

	public function get_help_url() {
		return '';
	}

	protected function register_controls() {
		$this->start_controls_section(
			'section_content',
			array(
				'label' => esc_html__( 'Content', 'thim-elementor-kit' ),
			)
		);
		$this->add_control(
			'title',
			array(
				'label'   => esc_html__( 'Title', 'thim-elementor-kit' ),
				'type'    => Controls_Manager::TEXT,
 			)
		);
		$this->add_control(
			'show_avatar',
			array(
				'label'       => esc_html__( 'Show Avatar', 'thim-elementor-kit' ),
				'type'        => Controls_Manager::SWITCHER,
				'label_on'    => esc_html__( 'Show', 'thim-elementor-kit' ),
				'label_off'   => esc_html__( 'Hide', 'thim-elementor-kit' ),
				'default'     => 'yes',
				'separator'   => 'before',
				'render_type' => 'template',
			)
		);

		$this->add_control(
			'show_name',
			array(
				'label'       => esc_html__( 'Display Name', 'thim-elementor-kit' ),
				'type'        => Controls_Manager::SWITCHER,
				'label_on'    => esc_html__( 'Show', 'thim-elementor-kit' ),
				'label_off'   => esc_html__( 'Hide', 'thim-elementor-kit' ),
				'default'     => 'yes',
				'render_type' => 'template',
			)
		);

		$this->add_control(
			'author_name_tag',
			array(
				'label'   => esc_html__( 'Instructor Name Tag', 'thim-elementor-kit' ),
				'type'    => Controls_Manager::SELECT,
				'options' => array(
					'h1'   => 'H1',
					'h2'   => 'H2',
					'h3'   => 'H3',
					'h4'   => 'H4',
					'h5'   => 'H5',
					'h6'   => 'H6',
					'div'  => 'div',
					'span' => 'span',
				),
				'default' => 'h4',
			)
		);

		$this->add_control(
			'show_description',
			array(
				'label'       => esc_html__( 'Discription', 'thim-elementor-kit' ),
				'type'        => Controls_Manager::SWITCHER,
				'label_on'    => esc_html__( 'Show', 'thim-elementor-kit' ),
				'label_off'   => esc_html__( 'Hide', 'thim-elementor-kit' ),
				'default'     => 'yes',
				'render_type' => 'template',
			)
		);

		$this->add_control(
			'show_social',
			array(
				'label'       => esc_html__( 'Socials', 'thim-elementor-kit' ),
				'type'        => Controls_Manager::SWITCHER,
				'label_on'    => esc_html__( 'Show', 'thim-elementor-kit' ),
				'label_off'   => esc_html__( 'Hide', 'thim-elementor-kit' ),
				'default'     => 'yes',
				'render_type' => 'template',
			)
		);

		$this->add_control(
			'avatar_position',
			array(
				'label'        => esc_html__( 'Avatar Position', 'thim-elementor-kit' ),
				'type'         => Controls_Manager::CHOOSE,
				'options'      => array(
					'left'  => array(
						'title' => esc_html__( 'Left', 'thim-elementor-kit' ),
						'icon'  => 'eicon-h-align-left',
					),
					'top'   => array(
						'title' => esc_html__( 'Top', 'thim-elementor-kit' ),
						'icon'  => 'eicon-v-align-top',
					),
					'right' => array(
						'title' => esc_html__( 'Right', 'thim-elementor-kit' ),
						'icon'  => 'eicon-h-align-right',
					),
				),
				'separator'    => 'before',
				'condition' => array(
					'show_avatar' => 'yes',
				),
				'prefix_class' => 'thim-ekit-single-course__instructor--avatar-position-',
			)
		);

		$this->add_control(
			'alignment',
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
				'selectors' => array(
					'{{WRAPPER}} .thim-ekit-single-course__instructor' => 'text-align: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'divider',
			array(
				'type' => \Elementor\Controls_Manager::DIVIDER,
			)
		);

		$repeater = new Repeater();

		$repeater->add_control(
			'key',
			array(
				'label'   => esc_html__( 'Type', 'thim-elementor-kit' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'facebook',
				'options' => array(
					'facebook' => 'Facebook',
					'twitter'  => 'Twitter',
					'youtube'  => 'Youtube',
					'linkedin' => 'LinkedIn',
				),
			)
		);

		$repeater->add_control(
			'icon',
			array(
				'label'   => esc_html__( 'Icon', 'thim-elementor-kit' ),
				'type'    => \Elementor\Controls_Manager::ICONS,
				'default' => array(
					'value'   => 'fab fa-facebook',
					'library' => 'fa-brands',
				),
			)
		);

		$repeater->add_control(
			'notice',
			array(
				'type'            => Controls_Manager::RAW_HTML,
				'raw'             => esc_html__( 'You can set link for Social in LearnPress Profile.', 'thim-elementor-kit' ),
				'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
			)
		);

		$this->add_control(
			'icon_list',
			array(
				'label'       => esc_html__( 'Social Icons', 'thim-elementor-kit' ),
				'type'        => \Elementor\Controls_Manager::REPEATER,
				'fields'      => $repeater->get_controls(),
				'default'     => array(
					array(
						'key'  => 'facebook',
						'icon' => array(
							'value'   => 'fab fa-facebook',
							'library' => 'fa-brands',
						),
					),
					array(
						'key'  => 'twitter',
						'icon' => array(
							'value'   => 'fab fa-twitter',
							'library' => 'fa-brands',
						),
					),
					array(
						'key'  => 'youtube',
						'icon' => array(
							'value'   => 'fab fa-youtube',
							'library' => 'fa-brands',
						),
					),
					array(
						'key'  => 'linkedin',
						'icon' => array(
							'value'   => 'fab fa-linkedin',
							'library' => 'fa-brands',
						),
					),
				),
				'title_field' => '<span style="text-transform: capitalize;">{{{ key }}}</span>',
				'condition' => array(
					'show_social' => 'yes',
				),
			)
		);

		$this->end_controls_section();

		$this->register_style_control();
	}

	protected function register_style_control() {
		$this->start_controls_section(
			'section_title_style',
			array(
				'label'     => esc_html__( 'Title', 'thim-elementor-kit' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'title!' => '',
				),
			)
		);

		$this->add_control(
			'title_color',
			array(
				'label'     => esc_html__( 'Text Color', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .thim-ekit-single-course__instructor_title' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'typography',
				'selector' => '{{WRAPPER}} .thim-ekit-single-course__instructor_title',
			)
		);

		$this->add_responsive_control(
			'title_margin',
			array(
				'label'      => esc_html__( 'Margin', 'thim-elementor-kit' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .thim-ekit-single-course__instructor_title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);
		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_instructor',
			array(
				'label' => esc_html__( 'Image', 'thim-elementor-kit' ),
				'tab'   => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'show_avatar' => 'yes',
				),
			)
		);

		$this->add_responsive_control(
			'image_size',
			array(
				'label'     => esc_html__( 'Image Size', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'min' => 0,
						'max' => 200,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .thim-ekit-single-course__instructor__avatar img' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}}',
				),
			)
		);

		$this->add_responsive_control(
			'image_gap',
			array(
				'label'     => esc_html__( 'Gap', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .thim-ekit-single-course__instructor' => 'column-gap: {{SIZE}}{{UNIT}}',
					'{{WRAPPER}}.thim-ekit-single-course__instructor--avatar-position-top .thim-ekit-single-course__instructor__avatar' => 'margin-bottom: {{SIZE}}{{UNIT}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'      => 'image_border',
				'selector'  => '{{WRAPPER}} .thim-ekit-single-course__instructor__avatar img',
				'separator' => 'before',
			)
		);

		$this->add_responsive_control(
			'image_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'thim-elementor-kit' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .thim-ekit-single-course__instructor__avatar img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'image_box_shadow',
				'exclude'  => array(
					'box_shadow_position',
				),
				'selector' => '{{WRAPPER}} .thim-ekit-single-course__instructor__avatar img',
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_text',
			array(
				'label' => esc_html__( 'Text', 'thim-elementor-kit' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'heading_name_style',
			array(
				'label'     => esc_html__( 'Name', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'name_color',
			array(
				'label'     => esc_html__( 'Color', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .thim-ekit-single-course__instructor__title span' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'name_typography',
				'selector' => '{{WRAPPER}} .thim-ekit-single-course__instructor__title span',
			)
		);

		$this->add_responsive_control(
			'name_spacing',
			array(
				'label'     => esc_html__( 'Spacing', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .thim-ekit-single-course__instructor__title' => 'margin-bottom: {{SIZE}}{{UNIT}}',
				),
			)
		);

		$this->add_control(
			'heading_description_style',
			array(
				'label'     => esc_html__( 'Description', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'description_color',
			array(
				'label'     => esc_html__( 'Color', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .thim-ekit-single-course__instructor__description' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'description_typography',
				'selector' => '{{WRAPPER}} .thim-ekit-single-course__instructor__description',
			)
		);

		$this->add_responsive_control(
			'description_gap',
			array(
				'label'     => esc_html__( 'Spacing', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .thim-ekit-single-course__instructor__description' => 'margin-bottom: {{SIZE}}{{UNIT}}',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_social',
			array(
				'label' => esc_html__( 'Socials', 'thim-elementor-kit' ),
				'tab'   => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'show_social' => 'yes',
				),
			)
		);

		$this->add_control(
			'icon_color_type',
			array(
				'label'   => esc_html__( 'Color', 'thim-elementor-kit' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'default',
				'options' => array(
					'default' => esc_html__( 'Official Color', 'thim-elementor-kit' ),
					'custom'  => esc_html__( 'Custom', 'thim-elementor-kit' ),
				),
			)
		);

		$this->add_control(
			'icon_bg_color',
			array(
				'label'     => esc_html__( 'Background Color', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::COLOR,
				'condition' => array(
					'icon_color_type' => 'custom',
				),
				'selectors' => array(
					'{{WRAPPER}} .thim-ekit-single-course__instructor__socials > a' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'icon_color',
			array(
				'label'     => esc_html__( 'Color', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::COLOR,
				'condition' => array(
					'icon_color_type' => 'custom',
				),
				'selectors' => array(
					'{{WRAPPER}} .thim-ekit-single-course__instructor__socials i' => 'color: {{VALUE}};',
					'{{WRAPPER}} .thim-ekit-single-course__instructor__socials svg' => 'fill: {{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'icon_size',
			array(
				'label'     => esc_html__( 'Size', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::SLIDER,
				'separator' => 'before',
				'range'     => array(
					'px' => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .thim-ekit-single-course__instructor__socials' => '--thim-ekit-single-course-instructor-size: {{SIZE}}{{UNIT}}',
				),
			)
		);

		$this->add_responsive_control(
			'icon_padding',
			array(
				'label'     => esc_html__( 'Padding', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .thim-ekit-single-course__instructor__socials' => '--thim-ekit-single-course-instructor-padding: {{SIZE}}{{UNIT}}',
				),
			)
		);

		$this->add_responsive_control(
			'icon_spacing',
			array(
				'label'     => esc_html__( 'Spacing', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .thim-ekit-single-course__instructor__socials' => 'column-gap: {{SIZE}}{{UNIT}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'      => 'icon_border',
				'selector'  => '{{WRAPPER}} .thim-ekit-single-course__instructor__socials > a',
				'separator' => 'before',
			)
		);

		$this->add_responsive_control(
			'icon_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'thim-elementor-kit' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .thim-ekit-single-course__instructor__socials > a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();
	}

	public function render() {
		do_action( 'thim-ekit/modules/single-course/before-preview-query' );

		$course = learn_press_get_course();

		if ( ! $course ) {
			return;
		}

		$settings = $this->get_settings_for_display();

		$instructor = $course->get_instructor();
		$socials    = learn_press_get_user_extra_profile_info( $instructor->get_id() );
 		?>
		<?php if ( $settings['title'] ) { ?>
			<div class="thim-ekit-single-course__instructor_title">
				<?php echo esc_html( $settings['title'] ); ?>
			</div>
		<?php } ?>
		<div class="thim-ekit-single-course__instructor">


			<?php if ( $settings['show_avatar'] === 'yes' ) : ?>
				<div class="thim-ekit-single-course__instructor__avatar">
					<?php echo wp_kses_post( $instructor->get_profile_picture() ); ?>
				</div>
			<?php endif; ?>

			<div class="thim-ekit-single-course__instructor__content">
				<?php if ( $settings['show_name'] === 'yes' ) : ?>
					<<?php Utils::print_validated_html_tag( $settings['author_name_tag'] ); ?> class="thim-ekit-single-course__instructor__title">
						<?php echo wp_kses_post( $course->get_instructor_html() ); ?>
					</<?php Utils::print_validated_html_tag( $settings['author_name_tag'] ); ?>>
				<?php endif; ?>

				<?php if ( $settings['show_description'] === 'yes' ) : ?>
					<div class="thim-ekit-single-course__instructor__description">
						<?php do_action( 'learn-press/begin-course-instructor-description', $instructor ); ?>
						<?php echo wp_kses_post( $instructor->get_description() ); ?>
						<?php do_action( 'learn-press/end-course-instructor-description', $instructor ); ?>
					</div>
				<?php endif; ?>

				<?php if ( $socials && $settings['show_social'] === 'yes' ) : ?>
					<div class="thim-ekit-single-course__instructor__socials">
						<?php
						foreach ( $settings['icon_list'] as $item ) {
							foreach ( $socials as $key => $social ) {
								if ( empty( $social ) || $item['key'] !== $key ) {
									continue;
								}
								?>
								<a class="<?php echo esc_attr( $settings['icon_color_type'] === 'default' ? 'elementor-social-icon-' . esc_attr( $item['key'] ) : '' ); ?>" href="<?php echo esc_url( $social ); ?>">
									<span class="elementor-screen-only"><?php echo esc_html( ucwords( $item['key'] ) ); ?></span>
									<?php Icons_Manager::render_icon( $item['icon'] ); ?>
								</a>
								<?php
							}
						}
						?>
					</div>
				<?php endif; ?>
			</div>
		</div>

		<?php
		do_action( 'thim-ekit/modules/single-course/after-preview-query' );
	}
}
