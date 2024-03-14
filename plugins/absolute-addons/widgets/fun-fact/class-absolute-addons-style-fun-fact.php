<?php

namespace AbsoluteAddons\Widgets;

use AbsoluteAddons\Absp_Widget;
use AbsoluteAddons\Controls\Absp_Control_Styles;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Core\Schemes\Typography;
use Elementor\Core\Schemes\Color;


if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * @since 1.1.0
 */
class Absoluteaddons_Style_Fun_Fact extends Absp_Widget {

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
		return 'absolute-fun-fact';
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
		return __( 'Fun Fact', 'absolute-addons' );
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
		return 'absp eicon-nerd';
	}

	/**
	 * Requires css files.
	 *
	 * @return array
	 */
	public function get_style_depends() {
		return [
			'absolute-addons-custom',
			'absp-fun-fact',
			'absp-pro-fun-fact',
		];
	}

	/**
	 * Requires js files.
	 *
	 * @return array
	 */
	public function get_script_depends() {
		return array(
			'absolute-addons-fun-fact',
			'waypoints',
			'counterup',
			'absp-fun-fact',
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
		 * @param Absoluteaddons_Style_Fun_Fact $this Current instance of WP_Network_Query (passed by reference).
		 *
		 * @since 1.0.0
		 *
		 */
		do_action_ref_array( $this->get_prefixed_hook( 'controllers/starts' ), [ &$this ] );

		$this->start_controls_section( 'template_layout', [ 'label' => esc_html__( 'Template Style', 'absolute-addons' ) ] );

		$styles = apply_filters( 'absp/widgets/fun-fact/styles', [
			'one'       => esc_html__( 'One', 'absolute-addons' ),
			'two'       => esc_html__( 'Two', 'absolute-addons' ),
			'three-pro' => esc_html__( 'Three (Pro)', 'absolute-addons' ),
			'four'      => esc_html__( 'Four', 'absolute-addons' ),
			'five'      => esc_html__( 'Five', 'absolute-addons' ),
			'six-pro'   => esc_html__( 'Six (Pro)', 'absolute-addons' ),
			'seven-pro' => esc_html__( 'Seven (Pro)', 'absolute-addons' ),
			'eight'     => esc_html__( 'Eight', 'absolute-addons' ),
			'nine-pro'  => esc_html__( 'Nine (Pro)', 'absolute-addons' ),
			'ten'       => esc_html__( 'Ten', 'absolute-addons' ),
		] );

		$pro_styles = [
			'three-pro',
			'six-pro',
			'seven-pro',
			'nine-pro',
		];

		$this->add_control(
			'absolute_fun_fact',
			[
				'label'   => esc_html__( 'Fun Fact Style', 'absolute-addons' ),
				'type'    => Absp_Control_Styles::TYPE,
				'options' => $styles,
				'default' => 'one',
			]
		);

		$this->init_pro_alert( $pro_styles );

		$this->end_controls_section();

		$this->start_controls_section( 'section_content', [ 'label' => esc_html__( 'Content', 'absolute-addons' ) ] );
		$this->add_control(
			'fun_fact_icons',
			[
				'label'   => esc_html__( 'Icon or SVG', 'absolute-addons' ),
				'type'    => Controls_Manager::ICONS,
				'default' => [
					'value'   => 'fas fa-heart',
					'library' => 'solid',
				],
			]
		);
		$this->add_control(
			'fun_fact_title',
			[
				'label'       => esc_html__( 'Title', 'absolute-addons' ),
				'label_block' => false,
				'type'        => Controls_Manager::TEXT,
				'default'     => esc_html__( 'Fun Fact Title', 'absolute-addons' ),
				'conditions'  => [
					'relation' => 'and',
					'terms'    => [
						[
							'name'     => 'absolute_fun_fact',
							'operator' => '!==',
							'value'    => 'four',
						],
						[
							'name'     => 'absolute_fun_fact',
							'operator' => '!==',
							'value'    => 'six',
						],
					],
				],
			]
		);

		//Fun fact sub title control
		$this->add_control(
			'fun_fact_sub_title',
			[
				'label'       => esc_html__( 'Sub Title', 'absolute-addons' ),
				'label_block' => false,
				'type'        => Controls_Manager::TEXT,
				'default'     => esc_html__( 'Fun Fact Sub Title', 'absolute-addons' ),
				'conditions'  => [
					'relation' => 'or',
					'terms'    => [
						[
							'name'     => 'absolute_fun_fact',
							'operator' => '==',
							'value'    => 'one',
						],
						[
							'name'     => 'absolute_fun_fact',
							'operator' => '==',
							'value'    => 'ten',
						],
					],
				],
			]
		);
		$this->add_control(
			'fun_fact_number',
			[
				'label'       => esc_html__( 'Counter Number', 'absolute-addons' ),
				'label_block' => false,
				'type'        => Controls_Manager::TEXT,
				'default'     => esc_html__( '38,469', 'absolute-addons' ),
			]
		);
		$this->add_control(
			'fun_fact_counter_suffix',
			[
				'label'       => esc_html__( 'Counter Number Suffix', 'absolute-addons' ),
				'type'        => Controls_Manager::TEXT,
				'placeholder' => esc_html__( 'Plus', 'absolute-addons' ),
				'default'     => esc_html__( '+', 'absolute-addons' ),
				'conditions'  => [
					'relation' => 'and',
					'terms'    => [
						[
							'name'     => 'absolute_fun_fact',
							'operator' => '!==',
							'value'    => 'one',
						],
						[
							'name'     => 'absolute_fun_fact',
							'operator' => '!==',
							'value'    => 'two',
						],
						[
							'name'     => 'absolute_fun_fact',
							'operator' => '!==',
							'value'    => 'ten',
						],
					],
				],
			]
		);

		$this->add_control(
			'fun_fact_counter_number_speed',
			[
				'label'   => esc_html__( 'Counter Number Speed', 'absolute-addons' ),
				'type'    => Controls_Manager::NUMBER,
				'default' => esc_html__( '1500', 'absolute-addons' ),
			]
		);

		$this->add_control(
			'content_align',
			[
				'label'   => esc_html__( 'Text Alignment', 'absolute-addons' ),
				'type'    => Controls_Manager::CHOOSE,
				'options' => [
					'left'   => [
						'title' => esc_html__( 'Left', 'absolute-addons' ),
						'icon'  => 'fa fa-align-left',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'absolute-addons' ),
						'icon'  => 'fa fa-align-center',
					],
					'right'  => [
						'title' => esc_html__( 'Right', 'absolute-addons' ),
						'icon'  => 'fa fa-align-right',
					],
				],
				'toggle'  => true,
			]
		);
		$this->end_controls_section();

		//Fun fact box button start
		$this->start_controls_section(
			'fun_fact_button_control',
			array(
				'label'      => esc_html__( 'Button', 'absolute-addons' ),
				'conditions' => [
					'relation' => 'or',
					'terms'    => [
						[
							'name'     => 'absolute_fun_fact',
							'operator' => '==',
							'value'    => 'one',
						],
						[
							'name'     => 'absolute_fun_fact',
							'operator' => '==',
							'value'    => 'two',
						],
						[
							'name'     => 'absolute_fun_fact',
							'operator' => '==',
							'value'    => 'four',
						],
						[
							'name'     => 'absolute_fun_fact',
							'operator' => '==',
							'value'    => 'six',
						],
						[
							'name'     => 'absolute_fun_fact',
							'operator' => '==',
							'value'    => 'nine',
						],
						[
							'name'     => 'absolute_fun_fact',
							'operator' => '==',
							'value'    => 'ten',
						],
					],
				],
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
			'fun_fact_button',
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
			'fun_fact_button_url',
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
		//Fun fact box button icon start
		$this->add_control(
			'fun_fact_button_icon_switch',
			[
				'label'        => esc_html__( 'Button Icon', 'absolute-addons' ),
				'description'  => esc_html__( '(If checked, icon will be show)', 'absolute-addons' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Show', 'absolute-addons' ),
				'label_off'    => esc_html__( 'Hide', 'absolute-addons' ),
				'return_value' => 'button-icon',
				'default'      => '',
				'separator'    => 'before',
				'condition'    => [
					'enable_button' => 'yes',
				],
			]
		);

		$this->add_control(
			'fun_fact_button_icon',
			[
				'label'      => esc_html__( 'Button Icon', 'absolute-addons' ),
				'type'       => Controls_Manager::ICONS,
				'conditions' => [
					'terms' => [
						[
							'name'     => 'fun_fact_button_icon_switch',
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
			'fun_fact_button_icon_position',
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
							'name'     => 'fun_fact_button_icon_switch',
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
			'fun_fact_button_icon_spacing',
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
							'name'     => 'fun_fact_button_icon_switch',
							'operator' => '==',
							'value'    => 'button-icon',
						],
					],
				],
				'condition'  => [
					'enable_button' => 'yes',
				],
				'selectors'  => [
					'{{WRAPPER}} .absp-wrapper .fun-fact-item .fun-fact-button-icon-after' => 'margin-left: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .absp-wrapper .fun-fact-item .fun-fact-button-icon-before' => 'margin-right: {{SIZE}}{{UNIT}};',
				],
			]
		);
		//Fun fact box button icon end
		$this->end_controls_section();
		//Fun fact box button end

		$this->render_controller( 'style-controller-fun-fact-item-settings' );
		$this->render_controller( 'style-controller-fun-fact-item-icon' );
		$this->render_controller( 'style-controller-fun-fact-item-title' );
		$this->render_controller( 'style-controller-fun-fact-item-sub-title' );
		$this->render_controller( 'style-controller-fun-fact-item-counter-number' );
		$this->render_controller( 'style-controller-fun-fact-item-separator' );
		$this->render_controller( 'style-controller-fun-fact-item-separator-five' );
		$this->render_controller( 'style-controller-fun-fact-item-button' );

		/**
		 * Fires after controllers are registered.
		 *
		 * @param Absoluteaddons_Style_Fun_Fact $this Current instance of WP_Network_Query (passed by reference).
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

		$this->add_inline_editing_attributes( 'fun_fact_title' );
		$this->add_render_attribute( 'fun_fact_title', 'class', 'fun-fact-title' );

		$this->add_inline_editing_attributes( 'fun_fact_sub_title' );
		$this->add_render_attribute( 'fun_fact_sub_title', 'class', 'fun-fact-sub-title' );

		$this->add_inline_editing_attributes( 'fun_fact_counter_suffix' );
		$this->add_render_attribute( 'fun_fact_counter_suffix', 'class', 'fun-fact-number-suffix' );

		$this->add_inline_editing_attributes( 'fun_fact_button' );
		$this->add_render_attribute( 'fun_fact_button', 'class', 'fun-fact-btn' );
		if ( ! empty( $settings['fun_fact_button_url']['url'] ) ) {
			$this->add_link_attributes( 'fun_fact_button', $settings['fun_fact_button_url'] );
		}

		$this->add_render_attribute( [
			'absp-fun-fact' => [
				'class'      => 'fun-fact-count fun-fact-number',
				'data-delay' => '10',
				'data-time'  => $settings['fun_fact_counter_number_speed'],
			],
		] );
		?>

		<div class="absp-wrapper absp-widget">
			<div class="absp-wrapper-inside">
				<div class="absp-wrapper-content">
					<!-- absp-fun-fact-item -->
					<div class="absp-fun-fact-item element-<?php echo esc_attr( $settings['absolute_fun_fact'] ); ?> <?php echo esc_attr( $settings['content_align'] ); ?>">
						<?php $this->render_template(); ?>
					</div>
					<!-- absp-fun-fact-item -->
				</div>
			</div>
		</div>
		<?php
	}

	protected function render_fact_icon( $settings ) {
		if ( 'svg' === $settings['fun_fact_icons']['library'] ) {
			if ( ! empty( $settings['fun_fact_icons']['value']['id'] ) ) {
				echo '<div class="fun-fact-icon-wrapper"><div class="fun-fact-svg-icon">';
				echo wp_get_attachment_image( $settings['fun_fact_icons']['value']['id'] );
				echo '</div></div>';
			} else { ?>
				<div class="fun-fact-icon-wrapper ">
					<div class="fun-fact-icon">
						<i class="<?php echo esc_attr( $settings['fun_fact_icons']['value'] ); ?>" aria-hidden="true"></i>
					</div>
				</div>
				<?php
			}
		} else { ?>
			<div class="fun-fact-icon-wrapper">
				<div class="fun-fact-icon">
					<i class="<?php echo esc_attr( $settings['fun_fact_icons']['value'] ); ?>" aria-hidden="true"></i>
				</div>
			</div>
			<?php
		}
	}
	protected function render_fun_fact_button_icon( $settings ) {
		if ( 'svg' === $settings['fun_fact_button_icon']['library'] ) {
			if ( ! empty( $settings['fun_fact_button_icon']['value']['id'] ) ) {
				echo '<div class="fun-fact-button-svg-img">';
				echo wp_get_attachment_image( $settings['fun_fact_button_icon']['value']['id'] );
				echo '</div>';

			} else { ?>
				<img src="<?php echo esc_url( $settings['fun_fact_button_icon']['value']['url'] ); ?>" alt="Placeholder Image">
				<?php
			}
		} else { ?>
			<div class="fun-fact-button-icon">
				<i class="<?php echo esc_attr( $settings['fun_fact_button_icon']['value'] ); ?>" aria-hidden="true"></i>
			</div>
			<?php
		}
	}

	protected function render_fun_fact_button( $settings, $only_icon = false ) {
		if ( isset( $settings['enable_button'] ) && 'yes' === $settings['enable_button'] ) {
			$class = 'fun-fact-btn';
			if ( $only_icon ) {
				$class .= ' fun-fact-icon-only-btn';
			}
			$this->add_render_attribute( 'fun_fact_button', 'class', $class );
			?>
			<a <?php $this->print_render_attribute_string( 'fun_fact_button' ); ?> >
				<?php
				if ( $only_icon ) {
					if ( 'svg' === $settings['fun_fact_button_icon_only']['library'] ) {
						if ( ! empty( $settings['fun_fact_button_icon_only']['value']['id'] ) ) {
							echo '<div class="fun-fact-button-svg-img">';
							echo wp_get_attachment_image( $settings['fun_fact_button_icon_only']['value']['id'] );
							echo '</div>';
						} else { ?>
							<img src="<?php echo esc_url( $settings['fun_fact_button_icon_only']['value']['url'] ); ?>" alt="Placeholder Image">
							<?php
						}
					} else {
						?>
						<div class="fun-fact-button-icon">
							<i class="<?php echo esc_attr( $settings['fun_fact_button_icon_only']['value'] ); ?>" aria-hidden="true"></i>
						</div>
						<?php
					}
				} else {
					if ( 'before' === $settings['fun_fact_button_icon_position'] ) { ?>
						<div class="fun-fact-button-icon-before">
							<?php $this->render_fun_fact_button_icon( $settings ); ?>
						</div>
					<?php }
					absp_render_title( $settings['fun_fact_button'] );
					if ( 'after' === $settings['fun_fact_button_icon_position'] ) { ?>
						<div class="fun-fact-button-icon-after">
							<?php $this->render_fun_fact_button_icon( $settings ); ?>
						</div>
					<?php }
				}
				?>
			</a>
			<?php

		}
	}
}
