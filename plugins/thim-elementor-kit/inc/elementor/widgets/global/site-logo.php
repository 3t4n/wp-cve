<?php

namespace Elementor;

use Elementor\Plugin;
use Elementor\Group_Control_Image_Size;
use Elementor\Utils;

class Thim_Ekit_Widget_Site_Logo extends Widget_Base {

	public function __construct( $data = array(), $args = null ) {
		parent::__construct( $data, $args );
	}

	public function get_name() {
		return 'thim-ekits-site-logo';
	}

	public function get_title() {
		return esc_html__( 'Site Logo', 'thim-elementor-kit' );
	}

	public function get_icon() {
		return 'thim-eicon eicon-site-logo';
	}

	public function get_categories() {
		return array( \Thim_EL_Kit\Elementor::CATEGORY );
	}

	public function get_keywords() {
		return [
			'thim',
			'site logo',
			'logo',
		];
	}

	protected function register_controls() {
		$this->start_controls_section(
			'section_image',
			array(
				'label' => esc_html__( 'Image', 'thim-elementor-kit' ),
			)
		);

		$this->add_control(
			'logo_image',
			array(
				'label'       => esc_html__( 'Logo Image', 'thim-elementor-kit' ),
				'type'        => Controls_Manager::SELECT,
				'default'     => 'site',
				'options'     => array(
					'site'   => esc_html__( 'Site Logo', 'thim-elementor-kit' ),
					'upload' => esc_html__( 'Upload Logo', 'thim-elementor-kit' ),
				),
				'description' => esc_html__( 'You can upload or change "Site Logo" in Customize.', 'thim-elementor-kit' ),
			)
		);

		$this->add_control(
			'logo_custom_image',
			array(
				'label'     => esc_html__( 'Choose Image', 'thim-elementor-kit' ),
				'type'      => \Elementor\Controls_Manager::MEDIA,
				'default'   => array(
					'url' => \Elementor\Utils::get_placeholder_image_src(),
				),
				'condition' => array(
					'logo_image' => 'upload',
				),
			)
		);

		$this->add_group_control(
			\Elementor\Group_Control_Image_Size::get_type(),
			array(
				'name'         => 'logo_size',
				'default'      => 'full',
				'prefix_class' => 'thim-ekits-logo--size-',
			)
		);

		$this->add_control(
			'logo_align',
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
					'{{WRAPPER}} .thim-ekit-site-logo' => 'text-align: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'logo_link',
			array(
				'label'     => esc_html__( 'Link', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'site',
				'separator' => 'before',
				'options'   => array(
					'none'   => esc_html__( 'None', 'thim-elementor-kit' ),
					'site'   => esc_html__( 'Site URL', 'thim-elementor-kit' ),
					'custom' => esc_html__( 'Custom URL', 'thim-elementor-kit' ),
				),
			)
		);

		$this->add_control(
			'custom_link',
			array(
				'label'         => esc_html__( 'Custom Link', 'thim-elementor-kit' ),
				'type'          => Controls_Manager::URL,
				'placeholder'   => esc_html__( 'https://your-link.com', 'thim-elementor-kit' ),
				'show_external' => true,
				'condition'     => array(
					'logo_link' => 'custom',
				),
			)
		);

		$this->end_controls_section();

		$this->register_style_controls();
	}

	protected function register_style_controls() {
		$this->start_controls_section(
			'section_style_image',
			array(
				'label' => esc_html__( 'Image', 'thim-elementor-kit' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_responsive_control(
			'width',
			array(
				'label'          => esc_html__( 'Width', 'thim-elementor-kit' ),
				'type'           => Controls_Manager::SLIDER,
				'default'        => array(
					'unit' => '%',
				),
				'tablet_default' => array(
					'unit' => '%',
				),
				'mobile_default' => array(
					'unit' => '%',
				),
				'size_units'     => array( '%', 'px', 'vw' ),
				'range'          => array(
					'%'  => array(
						'min' => 1,
						'max' => 100,
					),
					'px' => array(
						'min' => 1,
						'max' => 1000,
					),
					'vw' => array(
						'min' => 1,
						'max' => 100,
					),
				),
				'selectors'      => array(
					'{{WRAPPER}} .thim-ekit-site-logo img' => 'width: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'space',
			array(
				'label'          => esc_html__( 'Max Width', 'thim-elementor-kit' ),
				'type'           => Controls_Manager::SLIDER,
				'default'        => array(
					'unit' => '%',
				),
				'tablet_default' => array(
					'unit' => '%',
				),
				'mobile_default' => array(
					'unit' => '%',
				),
				'size_units'     => array( '%', 'px', 'vw' ),
				'range'          => array(
					'%'  => array(
						'min' => 1,
						'max' => 100,
					),
					'px' => array(
						'min' => 1,
						'max' => 1000,
					),
					'vw' => array(
						'min' => 1,
						'max' => 100,
					),
				),
				'selectors'      => array(
					'{{WRAPPER}} .thim-ekit-site-logo img' => 'max-width: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'height',
			array(
				'label'          => esc_html__( 'Height', 'thim-elementor-kit' ),
				'type'           => Controls_Manager::SLIDER,
				'default'        => array(
					'unit' => 'px',
				),
				'tablet_default' => array(
					'unit' => 'px',
				),
				'mobile_default' => array(
					'unit' => 'px',
				),
				'size_units'     => array( 'px', 'vh' ),
				'range'          => array(
					'px' => array(
						'min' => 1,
						'max' => 500,
					),
					'vh' => array(
						'min' => 1,
						'max' => 100,
					),
				),
				'selectors'      => array(
					'{{WRAPPER}} .thim-ekit-site-logo img' => 'height: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'object-fit',
			array(
				'label'     => esc_html__( 'Object Fit', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::SELECT,
				'condition' => array(
					'height[size]!' => '',
				),
				'options'   => array(
					''        => esc_html__( 'Default', 'thim-elementor-kit' ),
					'fill'    => esc_html__( 'Fill', 'thim-elementor-kit' ),
					'cover'   => esc_html__( 'Cover', 'thim-elementor-kit' ),
					'contain' => esc_html__( 'Contain', 'thim-elementor-kit' ),
				),
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .thim-ekit-site-logo img' => 'object-fit: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'separator_panel_style',
			array(
				'type'  => Controls_Manager::DIVIDER,
				'style' => 'thick',
			)
		);

		$this->start_controls_tabs( 'image_effects' );

		$this->start_controls_tab(
			'normal',
			array(
				'label' => esc_html__( 'Normal', 'thim-elementor-kit' ),
			)
		);

		$this->add_control(
			'opacity',
			array(
				'label'     => esc_html__( 'Opacity', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'max'  => 1,
						'min'  => 0.10,
						'step' => 0.01,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .thim-ekit-site-logo img' => 'opacity: {{SIZE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Css_Filter::get_type(),
			array(
				'name'     => 'css_filters',
				'selector' => '{{WRAPPER}} .thim-ekit-site-logo img',
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'hover',
			array(
				'label' => esc_html__( 'Hover', 'thim-elementor-kit' ),
			)
		);

		$this->add_control(
			'opacity_hover',
			array(
				'label'     => esc_html__( 'Opacity', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'max'  => 1,
						'min'  => 0.10,
						'step' => 0.01,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .thim-ekit-site-logo:hover img' => 'opacity: {{SIZE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Css_Filter::get_type(),
			array(
				'name'     => 'css_filters_hover',
				'selector' => '{{WRAPPER}} .thim-ekit-site-logo:hover img',
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'      => 'image_border',
				'selector'  => '{{WRAPPER}} .thim-ekit-site-logo img',
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
					'{{WRAPPER}} .thim-ekit-site-logo img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
				'selector' => '{{WRAPPER}} .thim-ekit-site-logo img',
			)
		);

		$this->end_controls_section();
	}

	public function render() {
		$settings = $this->get_settings_for_display();

		$logo_type = $settings['logo_image'];

		if ( $logo_type === 'site' ) {
			$logo_id = apply_filters( 'thim-ekits-custom-logo', get_theme_mod( 'custom_logo' ) );
		} else {
			$logo_id = $settings['logo_custom_image']['id'];
		}

		if ( empty( $logo_id ) ) {
			return;
		}

		$settings['logo_size'] = array(
			'id' => absint( $logo_id ),
		);

		$logo_html = Group_Control_Image_Size::get_attachment_image_html( $settings, 'logo_size' );

		if ( empty( $logo_html ) ) {
			return;
		}

		$logo_link_type = $settings['logo_link'];

		if ( $logo_link_type === 'custom' ) {
			$logo_link = $settings['custom_link']['url'];
		} elseif ( $logo_link_type === 'site' ) {
			$logo_link = home_url( '/' );
		} else {
			$logo_link = '';
		}
		?>

		<div class="thim-ekit-site-logo">
			<?php if ( ! empty( $logo_link ) ) : ?>
				<a href="<?php echo esc_url( $logo_link ); ?>" rel="home">
					<?php echo wp_kses_post( $logo_html ); ?>
				</a>
			<?php else : ?>
				<?php echo wp_kses_post( $logo_html ); ?>
			<?php endif; ?>
		</div>

		<?php
	}
}
