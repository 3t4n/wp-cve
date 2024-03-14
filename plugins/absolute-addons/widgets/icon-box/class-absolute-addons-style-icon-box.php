<?php

namespace AbsoluteAddons\Widgets;

use AbsoluteAddons\Absp_Widget;
use AbsoluteAddons\Controls\Absp_Control_Styles;
use Elementor\Controls_Manager;
use Elementor\Utils;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * @since 1.1.0
 */
class Absoluteaddons_Style_Icon_Box extends Absp_Widget {

	/**
	 * Retrieve the widget name.
	 *
	 * @return string Widget name.
	 * @since 1.1.0
	 *
	 * @access public
	 *
	 */
	public function get_name() {
		return 'absolute-icon-box';
	}

	/**
	 * Retrieve the widget title.
	 *
	 * @return string Widget title.
	 * @since 1.1.0
	 *
	 * @access public
	 *
	 */
	public function get_title() {
		return __( 'Icon Box', 'absolute-addons' );
	}

	/**
	 * Retrieve the widget icon.
	 *
	 * @return string Widget icon.
	 * @since 1.1.0
	 *
	 * @access public
	 *
	 */
	public function get_icon() {
		return 'absp eicon-icon-box';
	}

	/**
	 * Requires css files.
	 *
	 * @return array
	 */
	public function get_style_depends() {
		return [
			'absolute-addons-custom',
			'absp-icon-box',
			'absp-pro-icon-box',
		];
	}

	/**
	 * Requires js files.
	 *
	 * @return array
	 */
	public function get_script_depends() {
		return array(
			'absolute-addons-icon-box',

		);
	}

	/**
	 * Retrieve the list of categories the widget belongs to.
	 *
	 * Used to determine where to display the widget in the editor.
	 *
	 * Note that currently Elementor supports only one category.
	 * When multiple categories passed, Elementor uses the first one.
	 *
	 * @return array Widget categories.
	 * @since 1.1.0
	 *
	 * @access public
	 *
	 */
	public function get_categories() {
		return [ 'absp-widgets' ];
	}

	/**
	 * Register the widget controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @since 1.1.0
	 *
	 * @access protected
	 */
	protected function register_controls() {

		/**
		 * Fires after controllers are registered.
		 *
		 * @param Absoluteaddons_Style_Icon_Box $this Current instance of WP_Network_Query (passed by reference).
		 *
		 * @since 1.0.0
		 *
		 */
		do_action_ref_array( $this->get_prefixed_hook( 'controllers/starts' ), [ &$this ] );

		$this->start_controls_section( 'template_layout', [ 'label' => esc_html__( 'Template Style', 'absolute-addons' ) ] );

		$styles = apply_filters( 'absp/widgets/icon-box/styles', [
			'one'           => esc_html__( 'One', 'absolute-addons' ),
			'two'           => esc_html__( 'Two', 'absolute-addons' ),
			'three'         => esc_html__( 'Three', 'absolute-addons' ),
			'four'          => esc_html__( 'Four', 'absolute-addons' ),
			'five-pro'      => esc_html__( 'Five (Pro)', 'absolute-addons' ),
			'six-pro'       => esc_html__( 'Six (Pro)', 'absolute-addons' ),
			'seven-pro'     => esc_html__( 'Seven (Pro)', 'absolute-addons' ),
			'eight-pro'     => esc_html__( 'Eight (Pro)', 'absolute-addons' ),
			'nine-pro'      => esc_html__( 'Nine (Pro)', 'absolute-addons' ),
			'ten'           => esc_html__( 'Ten', 'absolute-addons' ),
			'eleven-pro'    => esc_html__( 'Eleven (Pro)', 'absolute-addons' ),
			'twelve'        => esc_html__( 'Twelve', 'absolute-addons' ),
			'thirteen'      => esc_html__( 'Thirteen', 'absolute-addons' ),
			'fourteen'      => esc_html__( 'Fourteen', 'absolute-addons' ),
			'fifteen'       => esc_html__( 'Fifteen', 'absolute-addons' ),
			'sixteen-pro'   => esc_html__( 'Sixteen (Pro)', 'absolute-addons' ),
			'seventeen-pro' => esc_html__( 'Seventeen (Pro)', 'absolute-addons' ),
			'eighteen'      => esc_html__( 'Eighteen', 'absolute-addons' ),
		] );

		$this->add_control(
			'absolute_icon_box',
			[
				'label'    => esc_html__( 'Icon Box Style', 'absolute-addons' ),
				'type'     => Absp_Control_Styles::TYPE,
				'options'  => $styles,
				'default'  => 'one',
				'disabled' => [ 'fifteen', 'sixteen-pro' ],
			]
		);

		$this->init_pro_alert( [
			'five-pro',
			'six-pro',
			'seven-pro',
			'eight-pro',
			'nine-pro',
			'eleven-pro',
			'sixteen-pro',
			'seventeen-pro',
		] );

		$this->end_controls_section();

		$button_conditions = [
			'relation' => 'or',
			'terms'    => [
				[
					'name'     => 'absolute_icon_box',
					'operator' => '==',
					'value'    => 'one',
				],
				[
					'name'     => 'absolute_icon_box',
					'operator' => '==',
					'value'    => 'two',
				],
				[
					'name'     => 'absolute_icon_box',
					'operator' => '==',
					'value'    => 'four',
				],
				[
					'name'     => 'absolute_icon_box',
					'operator' => '==',
					'value'    => 'seven',
				],
				[
					'name'     => 'absolute_icon_box',
					'operator' => '==',
					'value'    => 'eight',
				],
				[
					'name'     => 'absolute_icon_box',
					'operator' => '==',
					'value'    => 'nine',
				],
				[
					'name'     => 'absolute_icon_box',
					'operator' => '==',
					'value'    => 'ten',
				],
				[
					'name'     => 'absolute_icon_box',
					'operator' => '==',
					'value'    => 'sixteen',
				],
			],
		];

		$this->start_controls_section( 'section_content', [ 'label' => esc_html__( 'Content', 'absolute-addons' ) ] );

		$this->add_control(
			'icon_box_icons',
			[
				'label'   => esc_html__( 'Icon or SVG', 'absolute-addons' ),
				'type'    => Controls_Manager::ICONS,
				'default' => [
					'value'   => 'far fa-star',
					'library' => 'solid',
				],
			]
		);
		$this->add_control(
			'icon_box_title',
			[
				'label'       => esc_html__( 'Title', 'absolute-addons' ),
				'label_block' => true,
				'type'        => Controls_Manager::TEXT,
				'default'     => esc_html__( 'Icon Box Title', 'absolute-addons' ),
			]
		);
		$this->add_control(
			'title_html_tag',
			[
				'label'   => __( 'Title HTML Tag', 'absolute-addons' ),
				'type'    => Controls_Manager::SELECT,
				'options' => [
					'h1'  => 'H1',
					'h2'  => 'H2',
					'h3'  => 'H3',
					'h4'  => 'H4',
					'h5'  => 'H5',
					'h6'  => 'H6',
					'div' => 'div',
				],
				'default' => 'h2',
			]
		);

		$this->add_control(
			'icon_box_label_text_fourteen',
			[
				'label'       => esc_html__( 'Label Prefix', 'absolute-addons' ),
				'label_block' => true,
				'type'        => Controls_Manager::TEXT,
				'default'     => esc_html__( '01', 'absolute-addons' ),
				'condition'   => [
					'absolute_icon_box' => 'fourteen',
				],
				'description' => esc_html__( 'A highlighted number or text to show before box title.', 'absolute-addons' ),
			]
		);

		$this->add_control(
			'icon_box_sub_title',
			[
				'label'       => esc_html__( 'Sub Title', 'absolute-addons' ),
				'label_block' => true,
				'type'        => Controls_Manager::TEXT,
				'default'     => esc_html__( 'Icon Box Sub Title', 'absolute-addons' ),
				'conditions'  => [
					'relation' => 'or',
					'terms'    => [
						[
							'name'     => 'absolute_icon_box',
							'operator' => '==',
							'value'    => 'five',
						],
						[
							'name'     => 'absolute_icon_box',
							'operator' => '==',
							'value'    => 'eleven',
						],
						[
							'name'     => 'absolute_icon_box',
							'operator' => '==',
							'value'    => 'twelve',
						],
						[
							'name'     => 'absolute_icon_box',
							'operator' => '==',
							'value'    => 'fifteen',
						],
						[
							'name'     => 'absolute_icon_box',
							'operator' => '==',
							'value'    => 'eighteen',
						],
					],
				],
			]
		);
		$this->add_control(
			'sub_title_html_tag',
			[
				'label'      => __( 'Sub Title HTML Tag', 'absolute-addons' ),
				'type'       => Controls_Manager::SELECT,
				'options'    => [
					'h1'   => 'H1',
					'h2'   => 'H2',
					'h3'   => 'H3',
					'h4'   => 'H4',
					'h5'   => 'H5',
					'h6'   => 'H6',
					'div'  => 'div',
					'span' => 'span',
				],
				'default'    => 'span',
				'conditions' => [
					'relation' => 'or',
					'terms'    => [
						[
							'name'     => 'absolute_icon_box',
							'operator' => '==',
							'value'    => 'five',
						],
						[
							'name'     => 'absolute_icon_box',
							'operator' => '==',
							'value'    => 'eleven',
						],
						[
							'name'     => 'absolute_icon_box',
							'operator' => '==',
							'value'    => 'twelve',
						],
						[
							'name'     => 'absolute_icon_box',
							'operator' => '==',
							'value'    => 'fifteen',
						],
						[
							'name'     => 'absolute_icon_box',
							'operator' => '==',
							'value'    => 'eighteen',
						],
					],
				],
			]
		);

		$this->add_control(
			'icon_box_content',
			[
				'label'      => esc_html__( 'Content', 'absolute-addons' ),
				'type'       => Controls_Manager::WYSIWYG,
				'default'    => sprintf( '<p>%s</p>', __( 'Icon Box Description Enter Here', 'absolute-addons' ) ),
				'show_label' => false,
				'separator'  => 'before',
				'conditions' => [
					'relation' => 'or',
					'terms'    => [
						[
							'name'     => 'absolute_icon_box',
							'operator' => '==',
							'value'    => 'eight',
						],
						[
							'name'     => 'absolute_icon_box',
							'operator' => '==',
							'value'    => 'fifteen',
						],
						[
							'name'     => 'absolute_icon_box',
							'operator' => '==',
							'value'    => 'sixteen',
						],
					],
				],
			]
		);

		//Show divider for fifteen template
		$this->add_control(
			'icon_box_divider_enable',
			[
				'label'     => esc_html__( 'Show Divider', 'absolute-addons' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => [
					'true'  => esc_html__( 'Yes', 'absolute-addons' ),
					'false' => esc_html__( 'No', 'absolute-addons' ),

				],
				'default'   => 'true',
				'condition' => [
					'absolute_icon_box' => 'fifteen',
				],
			]
		);
		$this->end_controls_section();


		//button start
		$this->start_controls_section(
			'icon_box_button_section',
			array(
				'label'      => esc_html__( 'Button', 'absolute-addons' ),
				'conditions' => $button_conditions,
			)
		);
		$this->add_control(
			'enable_button',
			[
				'label'        => esc_html__( 'Enable Button ?', 'absolute-addons' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __('Yes', 'absolute-addons'),
				'label_off'    => __('No', 'absolute-addons'),
				'return_value' => 'yes',
				'default'      => 'yes',
				'separator'    => 'before',
			]
		);
		$this->add_control(
			'icon_box_button',
			[
				'label'     => esc_html__( 'Button Text', 'absolute-addons' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => esc_html__( 'Button Text', 'absolute-addons' ),
				'condition' => [
					'enable_button' => 'yes',
				],
			]
		);

		$this->add_control(
			'icon_box_button_url',
			[
				'label'         => esc_html__( 'Button Link', 'absolute-addons' ),
				'type'          => Controls_Manager::URL,
				'placeholder'   => esc_html__( 'https://your-link.com', 'absolute-addons' ),
				'show_external' => true,
				'default'       => [
					'url'         => '#',
					'is_external' => false,
					'nofollow'    => false,
				],
				'condition'     => [
					'enable_button' => 'yes',
				],
			]
		);
		$this->add_control(
			'icon_box_button_icon_switch',
			[
				'label'        => esc_html__( 'Button Icon', 'absolute-addons' ),
				'description'  => esc_html__( '(If checked, icon will be show)', 'absolute-addons' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Show', 'absolute-addons' ),
				'label_off'    => esc_html__( 'Hide', 'absolute-addons' ),
				'return_value' => 'button-icon',
				'default'      => 'button-icon',
				'condition'    => [
					'enable_button' => 'yes',
				],
			]
		);
		$this->add_control(
			'icon_box_button_icon',
			[
				'label'      => esc_html__( 'Button Icon', 'absolute-addons' ),
				'type'       => Controls_Manager::ICONS,
				'conditions' => [
					'terms' => [
						[
							'name'     => 'icon_box_button_icon_switch',
							'operator' => '==',
							'value'    => 'button-icon',
						],
					],
				],
				'default'    => [
					'value'   => 'fas fa-angle-right',
					'library' => 'solid',
				],
				'condition'  => [
					'enable_button' => 'yes',
				],
			]
		);
		$this->add_control(
			'icon_box_button_icon_position',
			array(
				'label'      => esc_html__( 'Button Icon Position', 'absolute-addons' ),
				'type'       => Controls_Manager::SELECT,
				'options'    => array(
					'before' => esc_html__( 'Before', 'absolute-addons' ),
					'after'  => esc_html__( 'After', 'absolute-addons' ),

				),
				'conditions' => [
					'terms' => [
						[
							'name'     => 'icon_box_button_icon_switch',
							'operator' => '==',
							'value'    => 'button-icon',
						],
					],
				],
				'default'    => 'after',
				'condition'  => [
					'enable_button' => 'yes',
				],
			)
		);
		$this->add_responsive_control(
			'icon_box_button_icon_spacing',
			[
				'label'      => esc_html__( 'Button Icon Spacing', 'absolute-addons' ),
				'type'       => Controls_Manager::SLIDER,
				'range'      => [
					'px' => [
						'max' => 50,
					],
				],
				'conditions' => [
					'terms' => [
						[
							'name'     => 'icon_box_button_icon_switch',
							'operator' => '==',
							'value'    => 'button-icon',
						],
					],
				],
				'condition'  => [
					'enable_button' => 'yes',
				],
				'selectors'  => [
					'{{WRAPPER}} .absp-wrapper .absp-icon-box-item .icon-box-button-icon-after'  => 'margin-left: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .absp-wrapper .absp-icon-box-item .icon-box-button-icon-before' => 'margin-right: {{SIZE}}{{UNIT}};',
				],
			]
		);
		$this->end_controls_section();
		//button end

		$this->render_controller( 'style-controller-icon-box-settings' );
		$this->render_controller( 'style-controller-icon-box-shape' );
		$this->render_controller( 'style-controller-icon-box-seventeen-shape' );
		$this->render_controller( 'style-controller-icon-box-icon' );
		$this->render_controller( 'style-controller-icon-box-title' );
		$this->render_controller( 'style-controller-icon-box-fourteen-label' );
		$this->render_controller( 'style-controller-icon-box-feature' );
		$this->render_controller( 'style-controller-icon-box-separator' );
		$this->render_controller( 'style-controller-icon-box-sub-title' );
		$this->render_controller( 'style-controller-icon-box-content' );
		$this->render_controller( 'style-controller-icon-box-button' );

		/**
		 * Fires after controllers are registered.
		 *
		 * @param Absoluteaddons_Style_Icon_Box $this Current instance of WP_Network_Query (passed by reference).
		 *
		 * @since 1.0.0
		 *
		 */
		do_action_ref_array( $this->get_prefixed_hook( 'controllers/ends' ), [ &$this ] );

	}

	/**
	 * Render the widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 1.1.0
	 *
	 * @access protected
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();

		$this->add_inline_editing_attributes( 'icon_box_title' );
		$this->add_render_attribute( 'icon_box_title', 'class', 'icon-box-title' );

		//icon box sub title inline attribute start
		$this->add_inline_editing_attributes( 'icon_box_sub_title' );
		$this->add_render_attribute( 'icon_box_sub_title', 'class', 'icon-box-sub-title' );

		$this->add_inline_editing_attributes( 'icon_box_button' );

		if ( ! empty( $settings['icon_box_button_url']['url'] ) ) {
			$this->add_link_attributes( 'icon_box_button', $settings['icon_box_button_url'] );
		}

		$this->add_render_attribute( 'icon_box_button', 'class', 'icon-box-btn ' );
		?>
		<div class="absp-wrapper absp-widget">
			<div class="absp-wrapper-inside">
				<div class="absp-wrapper-content">
					<!-- absp-icon-box-item -->
					<div class="absp-icon-box-item element-<?php echo esc_attr( $settings['absolute_icon_box'] ); ?>">
						<?php $this->render_template( $settings['absolute_icon_box'] ); ?>
					</div>
					<!-- absp-icon-box-item -->
				</div>
			</div>
		</div>
		<?php
	}

	protected function render_separator( $settings ) {
		if ( isset( $settings['icon_box_separator_enable'] ) && absp_string_to_bool( $settings['icon_box_separator_enable'] ) ) {
			?>
			<div class="icon-box-separator-inner">
				<span class="icon-box-separator"></span>
			</div>
			<?php
		}
	}

	protected function render_title( $settings, $before = '', $after = '' ) {
		if ( empty( $settings['icon_box_title'] ) ) {
			return;
		}

		$tag = Utils::validate_html_tag( $settings['title_html_tag'] );

		wp_kses_post_e( $before );
		absp_tag_start( $tag, 'icon_box_title', $this );
		absp_render_title( $settings['icon_box_title'] );
		absp_tag_end( $tag );
		wp_kses_post_e( $after );
	}

	protected function render_sub_title( $settings, $before = '', $after = '' ) {
		if ( empty( $settings['icon_box_sub_title'] ) ) {
			return;
		}

		$tag = Utils::validate_html_tag( $settings['sub_title_html_tag'] );

		wp_kses_post_e( $before );

		absp_tag_start( $tag, 'icon_box_sub_title', $this );
		absp_render_title( $settings['icon_box_sub_title'] );
		absp_tag_end( $tag );
		wp_kses_post_e( $after );
	}

	protected function render_box_icon( $settings, $before = '', $after = '' ) {
		wp_kses_post_e( $before );
		if ( false === strpos( $before, 'icon-box-icon' ) ) {
			echo 'svg' === $settings['icon_box_icons']['library'] ? '' : '<div class="icon-box-icon">';
		}
		if ( 'svg' === $settings['icon_box_icons']['library'] ) {
			if ( ! empty( $settings['icon_box_icons']['value']['id'] ) ) {
				echo '<div class="icon-box-img">';
				echo wp_get_attachment_image( $settings['icon_box_icons']['value']['id'], 'full' );
				echo '</div>';
			} else {
				printf( '<img src="%s">', esc_url( $settings['icon_box_icons']['value']['url'] ) );
			}
		} else {
			$this->render_icon( 'icon_box_icons', 'far fa-star', $settings );
		}
		if ( false === strpos( $before, 'icon-box-icon' ) ) {
			echo 'svg' === $settings['icon_box_icons']['library'] ? '' : '</div>';
		}
		wp_kses_post_e( $after );
	}

	protected function render_button( $settings, $before = '', $after = '' ) {
		if ( isset( $settings['enable_button'] ) && 'yes' === $settings['enable_button'] ) {
			wp_kses_post_e( $before );
			?>
			<a <?php $this->print_render_attribute_string( 'icon_box_button' ); ?>>
				<?php $this->render_button_before( $settings ); ?>
				<?php absp_render_title( $settings['icon_box_button'] ); ?>
				<?php $this->render_button_after( $settings ); ?>
			</a>
			<?php
			wp_kses_post_e( $after );
		}
	}

	protected function render_button_before( $settings ) {
		if ( 'before' === $settings['icon_box_button_icon_position'] ) { ?>
			<div class="icon-box-button-icon-before">
				<?php
				if ( 'svg' === $settings['icon_box_button_icon']['library'] ) {
					if ( ! empty( $settings['icon_box_button_icon']['value']['id'] ) ) {
						echo '<div class="icon-box-button-svg-img">';
						echo wp_get_attachment_image( $settings['icon_box_button_icon']['value']['id'] );
						echo '</div>';

					} else { ?>
						<img src="<?php echo esc_url( $settings['icon_box_button_icon']['value']['url'] ); ?>" alt="Placeholder Image">
						<?php
					}
				} else { ?>
					<div class="icon-box-button-icon"><?php $this->render_icon( 'icon_box_button_icon', '', $settings ); ?></div>
					<?php
				}
				?>
			</div>
		<?php }
	}

	protected function render_button_after( $settings ) {
		if ( 'after' === $settings['icon_box_button_icon_position'] ) {
			?>
			<div class="icon-box-button-icon-after">
				<?php
				if ( 'svg' === $settings['icon_box_button_icon']['library'] ) {
					if ( ! empty( $settings['icon_box_button_icon']['value']['id'] ) ) {
						echo '<div class="icon-box-button-svg-img">';
						echo wp_get_attachment_image( $settings['icon_box_button_icon']['value']['id'] );
						echo '</div>';

					} else { ?>
						<img src="<?php echo esc_url( $settings['icon_box_button_icon']['value']['url'] ); ?>" alt="Placeholder Image">
						<?php
					}
				} else { ?>
					<div class="icon-box-button-icon">
						<i class="<?php echo esc_attr( $settings['icon_box_button_icon']['value'] ); ?>"></i>
					</div>
					<?php
				}
				?>
			</div>
			<?php
		}
	}
}
