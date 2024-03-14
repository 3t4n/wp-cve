<?php

namespace AbsoluteAddons\Widgets;

use AbsoluteAddons\Controls\Absp_Control_Styles;
use Elementor\Controls_Manager;
use AbsoluteAddons\Absp_Widget;
use Elementor\Repeater;
use Elementor\Utils;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * @since 1.1.0
 */
class Absoluteaddons_Style_Info_Box extends Absp_Widget {

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
		return 'absolute-info-box';
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
		return __( 'Info Box', 'absolute-addons' );
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
		return 'absp eicon-info-box';
	}

	/**
	 * Requires css files.
	 *
	 * @return array
	 */
	public function get_style_depends() {
		return [
			'absolute-addons-custom',
			'ico-font',
			'absp-info-box',
			'absp-pro-info-box',
		];
	}

	/**
	 * Requires js files.
	 *
	 * @return array
	 */
	public function get_script_depends() {
		return array(
			'absolute-addons-info-box',
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

		$this->start_controls_section(
			'template_layout',
			array(
				'label' => esc_html__( 'Template Style', 'absolute-addons' ),
			)
		);

		$styles = apply_filters( 'absp/widgets/info-box/styles', [
			'one'            => esc_html__( 'One', 'absolute-addons' ),
			'two'            => esc_html__( 'Two', 'absolute-addons' ),
			'three'          => esc_html__( 'Three', 'absolute-addons' ),
			'four'           => esc_html__( 'Four', 'absolute-addons' ),
			'five-pro'       => esc_html__( 'Five (Pro)', 'absolute-addons' ),
			'six'            => esc_html__( 'Six', 'absolute-addons' ),
			'seven'          => esc_html__( 'Seven', 'absolute-addons' ),
			'eight-pro'      => esc_html__( 'Eight (Pro)', 'absolute-addons' ),
			'nine-pro'       => esc_html__( 'Nine (Pro)', 'absolute-addons' ),
			'ten'            => esc_html__( 'Ten', 'absolute-addons' ),
			'eleven'         => esc_html__( 'Eleven', 'absolute-addons' ),
			'twelve-pro'     => esc_html__( 'Twelve (Pro)', 'absolute-addons' ),
			'thirteen'       => esc_html__( 'Thirteen', 'absolute-addons' ),
			'fourteen'       => esc_html__( 'Fourteen', 'absolute-addons' ),
			'fifteen'        => esc_html__( 'Fifteen', 'absolute-addons' ),
			'sixteen'        => esc_html__( 'Sixteen', 'absolute-addons' ),
			'seventeen-pro'  => esc_html__( 'Seventeen (Pro)', 'absolute-addons' ),
			'eighteen-pro'   => esc_html__( 'Eighteen (Pro)', 'absolute-addons' ),
			'nineteen-pro'   => esc_html__( 'Nineteen (Pro)', 'absolute-addons' ),
			'twenty-pro'     => esc_html__( 'Twenty (Pro)', 'absolute-addons' ),
			'twenty-one'     => esc_html__( 'Twenty One', 'absolute-addons' ),
			'twenty-two-pro' => esc_html__( 'Twenty Two (Pro)', 'absolute-addons' ),
			'twenty-three'   => esc_html__( 'Twenty Three', 'absolute-addons' ),
			'twenty-four'    => esc_html__( 'Twenty Four', 'absolute-addons' ),
		] );

		$pro_styles = [
			'five-pro',
			'eight-pro',
			'nine-pro',
			'twelve-pro',
			'seventeen-pro',
			'eighteen-pro',
			'nineteen-pro',
			'twenty-pro',
			'twenty-two-pro',
		];

		$this->add_control(
			'absolute_info_box',
			array(
				'label'       => esc_html__( 'Info Box Style', 'absolute-addons' ),
				'label_block' => true,
				'type'        => Absp_Control_Styles::TYPE,
				'options'     => $styles,
				'default'     => 'one',
			)
		);
		$this->init_pro_alert( $pro_styles );
		$this->end_controls_section();

		$this->start_controls_section( 'section_content', [ 'label' => esc_html__( 'Content', 'absolute-addons' ) ] );
		$this->add_control(
			'info_box_image',
			[
				'label'      => esc_html__( 'Add Image', 'absolute-addons' ),
				'type'       => Controls_Manager::MEDIA,
				'default'    => [
					'url' => Utils::get_placeholder_image_src(),
				],
				'conditions' => [
					'relation' => 'or',
					'terms'    => [
						[
							'name'     => 'absolute_info_box',
							'operator' => '==',
							'value'    => 'eight',
						],
						[
							'name'     => 'absolute_info_box',
							'operator' => '==',
							'value'    => 'seventeen',
						],
						[
							'name'     => 'absolute_info_box',
							'operator' => '==',
							'value'    => 'eighteen',
						],
						[
							'name'     => 'absolute_info_box',
							'operator' => '==',
							'value'    => 'nineteen',
						],
						[
							'name'     => 'absolute_info_box',
							'operator' => '==',
							'value'    => 'twenty',
						],
						[
							'name'     => 'absolute_info_box',
							'operator' => '==',
							'value'    => 'twenty-one',
						],
					],
				],
			]
		);
		$this->add_control(
			'info_box_icons',
			[
				'label'      => esc_html__( 'Icon or SVG', 'absolute-addons' ),
				'type'       => Controls_Manager::ICONS,
				'default'    => [
					'value'   => 'far fa-star',
					'library' => 'solid',
				],
				'conditions' => [
					'relation' => 'and',
					'terms'    => [
						[
							'name'     => 'absolute_info_box',
							'operator' => '!==',
							'value'    => 'seventeen',
						],
						[
							'name'     => 'absolute_info_box',
							'operator' => '!==',
							'value'    => 'twenty',
						],
						[
							'name'     => 'absolute_info_box',
							'operator' => '!==',
							'value'    => 'nineteen',
						],
						[
							'name'     => 'absolute_info_box',
							'operator' => '!==',
							'value'    => 'twenty-one',
						],
					],
				],
			]
		);
		$this->add_control(
			'info_box_sub_title',
			[
				'label'       => esc_html__( 'Sub Title', 'absolute-addons' ),
				'label_block' => true,
				'type'        => Controls_Manager::TEXT,
				'default'     => esc_html__( 'Info Box Sub Title', 'absolute-addons' ),
				'conditions'  => [
					'relation' => 'or',
					'terms'    => [
						[
							'name'     => 'absolute_info_box',
							'operator' => '==',
							'value'    => 'two',
						],
						[
							'name'     => 'absolute_info_box',
							'operator' => '==',
							'value'    => 'eleven',
						],
						[
							'name'     => 'absolute_info_box',
							'operator' => '==',
							'value'    => 'thirteen',
						],
						[
							'name'     => 'absolute_info_box',
							'operator' => '==',
							'value'    => 'sixteen',
						],
						[
							'name'     => 'absolute_info_box',
							'operator' => '==',
							'value'    => 'twenty-three',
						],
					],
				],
			]
		);
		$this->add_control(
			'info_box_title',
			[
				'label'       => esc_html__( 'Title', 'absolute-addons' ),
				'label_block' => true,
				'type'        => Controls_Manager::TEXT,
				'default'     => esc_html__( 'Info Box Title', 'absolute-addons' ),
			]
		);
		//Info box title section end
		//Show divider for fifteen template
		$this->add_control(
			'info_box_divider_enable',
			array(
				'label'     => esc_html__( 'Enable Divider', 'absolute-addons' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => array(
					'true'  => esc_html__( 'Yes', 'absolute-addons' ),
					'false' => esc_html__( 'No', 'absolute-addons' ),

				),
				'default'   => 'true',
				'condition' => [
					'absolute_info_box' => 'sixteen',
				],
			)
		);
		//Info box content section start
		$this->add_control(
			'info_box_content',
			[
				'label'      => esc_html__( 'Content', 'absolute-addons' ),
				'type'       => Controls_Manager::WYSIWYG,
				'default'    => sprintf( '<p>%s</p>', __( 'Info Box Description Enter Here.', 'absolute-addons' ) ),
				'show_label' => false,
				'conditions' => [
					'relation' => 'and',
					'terms'    => [
						[
							'name'     => 'absolute_info_box',
							'operator' => '!==',
							'value'    => 'twenty',
						],
					],
				],
			]
		);
		$this->add_control(
			'feature_list_icon',
			[
				'label'        => esc_html__( 'List Icon', 'absolute-addons' ),
				'description'  => esc_html__( '(If checked, list icon will be show)', 'absolute-addons' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Show', 'absolute-addons' ),
				'label_off'    => esc_html__( 'Hide', 'absolute-addons' ),
				'return_value' => 'list-icon',
				'default'      => 'list-icon',
				'conditions'   => [
					'relation' => 'or',
					'terms'    => [
						[
							'name'     => 'absolute_info_box',
							'operator' => '==',
							'value'    => 'twenty',
						],
					],
				],
			]
		);

		$info_box_repeater_twenty = new Repeater();

		$info_box_repeater_twenty->add_control(
			'feature_single_line', [
				'label'       => esc_html__( 'Feature Item', 'absolute-addons' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => esc_html__( 'First lorem ipsum', 'absolute-addons' ),
				'label_block' => true,
			]
		);

		$this->add_control(
			'features_line',
			[
				'label'       => esc_html__( 'Features', 'absolute-addons' ),
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $info_box_repeater_twenty->get_controls(),
				'title_field' => '{{{ feature_single_line }}}',
				'default'     => [
					[
						'feature_single_line' => esc_html__( 'First lorem ipsum', 'absolute-addons' ),
					],
					[
						'feature_single_line' => esc_html__( 'Second consectetuer odio', 'absolute-addons' ),
					],
					[
						'feature_single_line' => esc_html__( 'Third non tellus', 'absolute-addons' ),
					],
					[
						'feature_single_line' => esc_html__( 'Fourth consectetr tellus', 'absolute-addons' ),
					],
				],
				'conditions'  => [
					'relation' => 'or',
					'terms'    => [
						[
							'name'     => 'absolute_info_box',
							'operator' => '==',
							'value'    => 'twenty',
						],
					],
				],
			]
		);

		$info_box_repeater_twenty_three = new Repeater();

		$info_box_repeater_twenty_three->add_control(
			'feature_single_line_text', [
				'label'       => esc_html__( 'Feature Item', 'absolute-addons' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => esc_html__( '200GB Sit amet consectetuer', 'absolute-addons' ),
				'label_block' => true,
			]
		);

		$this->add_control(
			'features_line_value',
			[
				'label'       => esc_html__( 'Features', 'absolute-addons' ),
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $info_box_repeater_twenty_three->get_controls(),
				'title_field' => '{{{ feature_single_line_text }}}',
				'default'     => [
					[
						'feature_single_line_text' => esc_html__( '200GB Sit amet consectetuer', 'absolute-addons' ),
					],
					[
						'feature_single_line_text' => esc_html__( '100 Adipiscing seddiam nonumy', 'absolute-addons' ),
					],
					[
						'feature_single_line_text' => esc_html__( '2GB Lorem ipsum dolor sit', 'absolute-addons' ),
					],
					[
						'feature_single_line_text' => esc_html__( 'Premium Consectetuer agam', 'absolute-addons' ),
					],
				],

				'conditions'  => [
					'relation' => 'or',
					'terms'    => [
						[
							'name'     => 'absolute_info_box',
							'operator' => '==',
							'value'    => 'twenty-three',
						],
					],
				],
			]
		);
		$this->end_controls_section();

		//Info box button start
		$this->start_controls_section(
			'info_box_button_section',
			array(
				'label'      => esc_html__( 'Button', 'absolute-addons' ),
				'conditions' => [
					'relation' => 'and',
					'terms'    => [
						[
							'name'     => 'absolute_info_box',
							'operator' => '!=',
							'value'    => 'ten',
						],
						[
							'name'     => 'absolute_info_box',
							'operator' => '!=',
							'value'    => 'thirteen',
						],
						[
							'name'     => 'absolute_info_box',
							'operator' => '!=',
							'value'    => 'twenty-one',
						],
						[
							'name'     => 'absolute_info_box',
							'operator' => '!=',
							'value'    => 'twenty-four',
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
			'info_box_button',
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
			'info_box_button_url',
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
		//Info box button icon start
		$this->add_control(
			'info_box_button_icon_switch',
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
			'info_box_button_icon',
			[
				'label'      => esc_html__( 'Button Icon', 'absolute-addons' ),
				'type'       => Controls_Manager::ICONS,
				'conditions' => [
					'terms' => [
						[
							'name'     => 'info_box_button_icon_switch',
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
			'info_box_button_icon_position',
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
							'name'     => 'info_box_button_icon_switch',
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
			'info_box_button_icon_spacing',
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
							'name'     => 'info_box_button_icon_switch',
							'operator' => '==',
							'value'    => 'button-icon',
						],
					],
				],
				'condition'  => [
					'enable_button' => 'yes',
				],
				'selectors'  => [
					'{{WRAPPER}} .absp-wrapper .absp-info-box-item .info-box-button-icon-after'  => 'margin-left: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .absp-wrapper .absp-info-box-item .info-box-button-icon-before' => 'margin-right: {{SIZE}}{{UNIT}};',
				],
			]
		);
		//Info box button icon end
		$this->end_controls_section();
		//Info box button end


		$this->render_controller( 'style-controller-info-box-settings' );
		$this->render_controller( 'style-controller-info-box-shape' );
		$this->render_controller( 'style-controller-info-box-twenty-two-shape' );
		$this->render_controller( 'style-controller-info-box-images' );
		$this->render_controller( 'style-controller-info-box-icon' );
		$this->render_controller( 'style-controller-info-box-title' );
		$this->render_controller( 'style-controller-info-box-feature' );
		$this->render_controller( 'style-controller-info-box-separator' );
		$this->render_controller( 'style-controller-info-box-sub-title' );
		$this->render_controller( 'style-controller-info-box-content' );
		$this->render_controller( 'style-controller-info-box-button' );

		/**
		 * Fires after controllers are registered.
		 *
		 * @param Absoluteaddons_Style_Info_Box $this Current instance of WP_Network_Query (passed by reference).
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

		$this->add_inline_editing_attributes( 'info_box_title' );
		$this->add_render_attribute( 'info_box_title', 'class', 'info-box-title' );

		//Info box sub title inline attribut start
		$this->add_inline_editing_attributes( 'info_box_sub_title' );
		$this->add_render_attribute( 'info_box_sub_title', 'class', 'info-box-sub-title' );

		$feature_list_icon = ! empty ( $settings['feature_list_icon'] ) ? $settings['feature_list_icon'] : '';

		$this->add_inline_editing_attributes( 'info_box_button' );

		if ( ! empty( $settings['info_box_button_url']['url'] ) ) {
			$this->add_link_attributes( 'info_box_button', $settings['info_box_button_url'] );
		}
		?>
		<div class="absp-wrapper absp-widget">
			<div class="absp-wrapper-inside">
				<div class="absp-wrapper-content">
					<!-- absp-info-box-item -->
					<div class="absp-info-box-item element-<?php echo esc_attr( $settings['absolute_info_box'] ); ?>">
						<?php $this->render_template( $settings['absolute_info_box'], [ 'feature_list_icon' => $feature_list_icon ] ); ?>
					</div>
					<!-- absp-info-box-item -->
				</div>
			</div>
		</div>
		<?php
	}

	protected function render_box_icon( $settings ) {
		if ( 'svg' == $settings['info_box_icons']['library'] ) {
			if ( ! empty( $settings['info_box_icons']['value']['id'] ) ) {
				echo '<div class="info-box-svg-icon">';
				echo wp_get_attachment_image( $settings['info_box_icons']['value']['id'] );
				echo '</div>';
			} else { ?>
				<i class="<?php echo esc_attr( $settings['info_box_icons']['value'] ); ?>"></i>
				<?php
			}
		} else { ?>
			<i class="<?php echo esc_attr( $settings['info_box_icons']['value'] ); ?>"></i>
			<?php
		}
	}

	protected function render_button_icon( $settings ) {
		if ( 'svg' === $settings['info_box_button_icon']['library'] ) {
			if ( ! empty( $settings['info_box_button_icon']['value']['id'] ) ) {
				echo '<div class="info-box-button-svg-img">';
				echo wp_get_attachment_image( $settings['info_box_button_icon']['value']['id'] );
				echo '</div>';
			} else {
				?>
				<img src="<?php echo esc_url( $settings['info_box_button_icon']['value']['url'] ); ?>" alt="Placeholder Image">
				<?php
			}
		} else {
			?>
			<div class="info-box-button-icon">
				<i class="<?php echo esc_attr( $settings['info_box_button_icon']['value'] ); ?>" aria-hidden="true"></i>
			</div>
			<?php
		}
	}

	protected function render_button( $settings ) {
		if ( isset( $settings['enable_button'] ) && 'yes' === $settings['enable_button'] ) {
		$this->add_render_attribute( 'info_box_button', 'class', 'info-box-btn ' );
		?>
		<a <?php $this->print_render_attribute_string( 'info_box_button' ); ?> >

			<?php if ( 'before' === $settings['info_box_button_icon_position'] ) { ?>
				<div class="info-box-button-icon-before">
					<?php $this->render_button_icon( $settings ); ?>
				</div>
			<?php } ?>

			<?php absp_render_title( $settings['info_box_button'] ); ?>

			<?php if ( 'after' === $settings['info_box_button_icon_position'] ) { ?>
				<div class="info-box-button-icon-after">
					<?php $this->render_button_icon( $settings ); ?>
				</div>
			<?php } ?>
		</a>
		<?php
		}
	}
}
