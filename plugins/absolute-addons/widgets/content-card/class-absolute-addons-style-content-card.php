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
class Absoluteaddons_Style_Content_Card extends Absp_Widget {

	/**
	 * Retrieve the widget name.
	 *
	 * @since 1.1.0
	 *
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'absolute-content-card';
	}

	/**
	 * Retrieve the widget title.
	 *
	 * @since 1.1.0
	 *
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( 'Content Card', 'absolute-addons' );
	}

	/**
	 * Retrieve the widget icon.
	 *
	 * @since 1.1.0
	 *
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'absp eicon-post-content';
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
			'absp-content-card',
			'absp-pro-content-card',
		];
	}

	/**
	 * Requires js files.
	 *
	 * @return array
	 */
	public function get_script_depends() {
		return [
			'absolute-addons-content-card',
		];
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
		 * @param Absoluteaddons_Style_Content_Card $this Current instance of WP_Network_Query (passed by reference).
		 *
		 * @since 1.0.0
		 *
		 */
		do_action_ref_array( $this->get_prefixed_hook( 'controllers/starts' ), [ &$this ] );

		$this->start_controls_section( 'template_layout', [ 'label' => esc_html__( 'Template Style', 'absolute-addons' ) ] );

		$styles = apply_filters( 'absp/widgets/content-card/styles', [
			'one'           => esc_html__( 'One', 'absolute-addons' ),
			'two'           => esc_html__( 'Two', 'absolute-addons' ),
			'three'         => esc_html__( 'Three', 'absolute-addons' ),
			'four-pro'      => esc_html__( 'Four (Pro)', 'absolute-addons' ),
			'five-pro'      => esc_html__( 'Five (Pro)', 'absolute-addons' ),
			'six-pro'       => esc_html__( 'Six (Pro)', 'absolute-addons' ),
			'seven'         => esc_html__( 'Seven', 'absolute-addons' ),
			'eight-pro'     => esc_html__( 'Eight (Pro)', 'absolute-addons' ),
			'nine'          => esc_html__( 'Nine', 'absolute-addons' ),
			'ten'           => esc_html__( 'Ten', 'absolute-addons' ),
			'eleven-pro'    => esc_html__( 'Eleven (Pro)', 'absolute-addons' ),
			'twelve-pro'    => esc_html__( 'Twelve (Pro)', 'absolute-addons' ),
			'thirteen'      => esc_html__( 'Thirteen', 'absolute-addons' ),
			'fourteen-pro'  => esc_html__( 'Fourteen (Pro)', 'absolute-addons' ),
			'fifteen'       => esc_html__( 'Fifteen', 'absolute-addons' ),
			'sixteen'       => esc_html__( 'Sixteen', 'absolute-addons' ),
			'seventeen-pro' => esc_html__( 'Seventeen (Pro)', 'absolute-addons' ),
			'eighteen-pro'  => esc_html__( 'Eighteen (Pro)', 'absolute-addons' ),
		] );

		$pro_styles = [
			'four-pro',
			'five-pro',
			'six-pro',
			'eight-pro',
			'eleven-pro',
			'twelve-pro',
			'fourteen-pro',
			'seventeen-pro',
			'eighteen-pro',
		];

		$this->add_control(
			'absolute_content_card',
			[
				'label'   => esc_html__( 'Content Card Style', 'absolute-addons' ),
				'type'    => Absp_Control_Styles::TYPE,
				'options' => $styles,
				'default' => 'one',
			]
		);
		$this->init_pro_alert( $pro_styles );
		$this->end_controls_section();

		$this->start_controls_section( 'section_content', [ 'label' => esc_html__( 'Content', 'absolute-addons' ) ] );
		$this->add_control(
			'content_card_box_image',
			[
				'label'   => esc_html__( 'Add Image', 'absolute-addons' ),
				'type'    => Controls_Manager::MEDIA,
				'default' => [
					'url' => Utils::get_placeholder_image_src(),
				],
			]
		);
		$this->add_control(
			'content_card_img_hover_animation',
			[
				'label'        => esc_html__( 'Image Hover Animation?', 'absolute-addons' ),
				'type'         => Controls_Manager::SWITCHER,
				'enable'       => esc_html__( 'Yes', 'absolute-addons' ),
				'disable'      => esc_html__( 'No', 'absolute-addons' ),
				'return_value' => 'enable',
				'default'      => 'enable',
				'conditions'   => [
					'relation' => 'and',
					'terms'    => [
						[
							'name'     => 'absolute_content_card',
							'operator' => '!==',
							'value'    => 'ten',
						],
					],
				],
			]
		);
		$this->add_control(
			'content_card_box_brand_logo_five',
			[
				'label'     => esc_html__( 'Add Brand Logo', 'absolute-addons' ),
				'type'      => Controls_Manager::MEDIA,
				'default'   => [
					'url' => Utils::get_placeholder_image_src(),
				],
				'condition' => [
					'absolute_content_card' => 'five',
				],

			]
		);
		$this->add_control(
			'content_card_box_title',
			[
				'label'       => esc_html__( 'Title', 'absolute-addons' ),
				'label_block' => true,
				'type'        => Controls_Manager::TEXT,
				'default'     => esc_html__( 'Content Card Title', 'absolute-addons' ),
			]
		);
		$this->add_control(
			'content_card_box_sub_title',
			[
				'label'       => esc_html__( 'Sub Title', 'absolute-addons' ),
				'label_block' => true,
				'type'        => Controls_Manager::TEXT,
				'default'     => esc_html__( 'Content Card Sub Title', 'absolute-addons' ),
				'conditions'  => [
					'relation' => 'or',
					'terms'    => [
						[
							'name'     => 'absolute_content_card',
							'operator' => '==',
							'value'    => 'one',
						],
						[
							'name'     => 'absolute_content_card',
							'operator' => '==',
							'value'    => 'three',
						],
						[
							'name'     => 'absolute_content_card',
							'operator' => '==',
							'value'    => 'four',
						],
						[
							'name'     => 'absolute_content_card',
							'operator' => '==',
							'value'    => 'five',
						],
						[
							'name'     => 'absolute_content_card',
							'operator' => '==',
							'value'    => 'six',
						],

					],
				],
			]
		);
		$this->add_control(
			'content_card_box_sub_title_two',
			[
				'label'       => esc_html__( 'Sub Title', 'absolute-addons' ),
				'label_block' => true,
				'type'        => Controls_Manager::TEXT,
				'default'     => sprintf( '%s', __( 'THE <u>INSPIRATION</u>', 'absolute-addons' ) ),
				'condition'   => [
					'absolute_content_card' => 'two',
				],
			]
		);
		$this->add_control(
			'content_card_box_sub_title_fifteen',
			[
				'label'       => esc_html__( 'Sub Title', 'absolute-addons' ),
				'label_block' => true,
				'type'        => Controls_Manager::TEXT,
				'default'     => sprintf( '%s', __( 'THE <b>INSPIRATION</b>', 'absolute-addons' ) ),
				'condition'   => [
					'absolute_content_card' => 'fifteen',
				],
			]
		);
		$this->add_control(
			'content_card_box_brand_name_five',
			[
				'label'       => esc_html__( 'Brand Name', 'absolute-addons' ),
				'label_block' => true,
				'type'        => Controls_Manager::TEXT,
				'default'     => esc_html__( 'swatch', 'absolute-addons' ),
				'condition'   => [
					'absolute_content_card' => 'five',
				],
			]
		);
		$this->add_control(
			'content_card_box_content',
			[
				'label'      => esc_html__( 'Content', 'absolute-addons' ),
				'type'       => Controls_Manager::WYSIWYG,
				'default'    => sprintf( '<p>%s</p>', __( 'Content Card Description Enter Here', 'absolute-addons' ) ),
				'show_label' => false,
				'conditions' => [
					'relation' => 'and',
					'terms'    => [
						[
							'name'     => 'absolute_content_card',
							'operator' => '!==',
							'value'    => 'six',
						],
						[
							'name'     => 'absolute_content_card',
							'operator' => '!==',
							'value'    => 'eight',
						],
						[
							'name'     => 'absolute_content_card',
							'operator' => '!==',
							'value'    => 'ten',
						],
						[
							'name'     => 'absolute_content_card',
							'operator' => '!==',
							'value'    => 'ten',
						],
						[
							'name'     => 'absolute_content_card',
							'operator' => '!==',
							'value'    => 'thirteen',
						],
						[
							'name'     => 'absolute_content_card',
							'operator' => '!==',
							'value'    => 'thirteen',
						],
					],
				],
			]
		);
		$this->add_control(
			'content_card_image_background_style',
			array(
				'label'     => esc_html__( 'Select Image Background Shape', 'absolute-addons' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => array(
					'style1' => esc_html__( 'Shape Style 1', 'absolute-addons' ),
					'style2' => esc_html__( 'Shape Style 2', 'absolute-addons' ),

				),
				'condition' => [
					'absolute_content_card' => 'fifteen',
				],
				'default'   => 'style1',


			)
		);
		//Content card box tag section start
		$this->add_control(
			'content_card_box_tag_title',
			[
				'label'       => esc_html__( 'Tag Title', 'absolute-addons' ),
				'label_block' => false,
				'type'        => Controls_Manager::TEXT,
				'default'     => esc_html__( 'Story', 'absolute-addons' ),
				'condition'   => [
					'absolute_content_card' => 'four',
				],
			]
		);
		$this->add_control(
			'content_card_box_tag_year',
			[
				'label'       => esc_html__( 'Tag Year', 'absolute-addons' ),
				'label_block' => false,
				'type'        => Controls_Manager::TEXT,
				'default'     => esc_html__( '2020', 'absolute-addons' ),
				'condition'   => [
					'absolute_content_card' => 'four',
				],
			]
		);
		//Content card box tag section end

		//Content card box time duration section start
		$this->add_control(
			'content_card_box_time_duration',
			[
				'label'       => esc_html__( 'Time Duration', 'absolute-addons' ),
				'label_block' => false,
				'type'        => Controls_Manager::TEXT,
				'default'     => esc_html__( '/ 5days 4night', 'absolute-addons' ),
				'condition'   => [
					'absolute_content_card' => 'three',
				],
			]
		);
		//Content card box time duration section end

		//Content card box sale price section start
		$this->add_control(
			'content_card_box_price_title',
			[
				'label'       => esc_html__( 'Sale Price Title', 'absolute-addons' ),
				'label_block' => false,
				'type'        => Controls_Manager::TEXT,
				'default'     => esc_html__( 'PRICE:', 'absolute-addons' ),
				'condition'   => [
					'absolute_content_card' => 'five',
				],
			]
		);
		$this->add_control(
			'content_card_price_currency_symbol_thirteen',
			[
				'label'       => esc_html__( 'Currency Symbol', 'absolute-addons' ),
				'label_block' => false,
				'type'        => Controls_Manager::TEXT,
				'default'     => esc_html__( 'USD', 'absolute-addons' ),
				'condition'   => [
					'absolute_content_card' => 'thirteen',
				],
			]
		);
		$this->add_control(
			'content_card_price_currency_symbol',
			[
				'label'       => esc_html__( 'Currency Symbol', 'absolute-addons' ),
				'label_block' => false,
				'type'        => Controls_Manager::TEXT,
				'default'     => esc_html__( '$', 'absolute-addons' ),
				'conditions'  => [
					'relation' => 'and',
					'terms'    => [
						[
							'name'     => 'absolute_content_card',
							'operator' => '!==',
							'value'    => 'four',
						],
						[
							'name'     => 'absolute_content_card',
							'operator' => '!==',
							'value'    => 'thirteen',
						],
					],
				],
			]
		);
		$this->add_control(
			'content_card_box_regular_price',
			[
				'label'      => esc_html__( 'Regular Price (Without Currency Symbol)', 'absolute-addons' ),
				'type'       => Controls_Manager::NUMBER,
				'default'    => esc_html__( '23.70', 'absolute-addons' ),
				'step'       => '0.01',
				'conditions' => [
					'relation' => 'or',
					'terms'    => [
						[
							'name'     => 'absolute_content_card',
							'operator' => '==',
							'value'    => 'two',
						],
						[
							'name'     => 'absolute_content_card',
							'operator' => '==',
							'value'    => 'eight',
						],
						[
							'name'     => 'absolute_content_card',
							'operator' => '==',
							'value'    => 'fifteen',
						],
					],
				],
			]
		);
		$this->add_control(
			'content_card_box_sale_price',
			[
				'label'      => esc_html__( 'Sale Price (Without Currency Symbol)', 'absolute-addons' ),
				'type'       => Controls_Manager::NUMBER,
				'default'    => esc_html__( '39.90', 'absolute-addons' ),
				'step'       => '0.01',
				'conditions' => [
					'relation' => 'and',
					'terms'    => [
						[
							'name'     => 'absolute_content_card',
							'operator' => '!==',
							'value'    => 'four',
						],
						[
							'name'     => 'absolute_content_card',
							'operator' => '!==',
							'value'    => 'seventeen',
						],
						[
							'name'     => 'absolute_content_card',
							'operator' => '!==',
							'value'    => 'eighteen',
						],
					],
				],
			]
		);

		//Content card box sale price section end
		$this->add_control(
			'content_card_box_label',
			[
				'label'       => esc_html__( 'Label', 'absolute-addons' ),
				'label_block' => false,
				'type'        => Controls_Manager::TEXT,
				'default'     => esc_html__( 'NEW', 'absolute-addons' ),
				'conditions'  => [
					'relation' => 'or',
					'terms'    => [
						[
							'name'     => 'absolute_content_card',
							'operator' => '==',
							'value'    => 'two',
						],
						[
							'name'     => 'absolute_content_card',
							'operator' => '==',
							'value'    => 'three',
						],
						[
							'name'     => 'absolute_content_card',
							'operator' => '==',
							'value'    => 'eight',
						],
						[
							'name'     => 'absolute_content_card',
							'operator' => '==',
							'value'    => 'eleven',
						],
						[
							'name'     => 'absolute_content_card',
							'operator' => '==',
							'value'    => 'fifteen',
						],
						[
							'name'     => 'absolute_content_card',
							'operator' => '==',
							'value'    => 'eighteen',
						],
					],
				],
			]
		);
		$this->end_controls_section();
		//Content card box button icon only section end

		//Content card box button start
		$this->start_controls_section(
			'content_card_button_section',
			array(
				'label'      => esc_html__( 'Button', 'absolute-addons' ),
				'conditions' => [
					'relation' => 'and',
					'terms'    => [
						[
							'name'     => 'absolute_content_card',
							'operator' => '!==',
							'value'    => 'three',
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
				'default'      => 'no',
				'separator'    => 'before',
			]
		);
		$this->add_control(
			'content_card_box_button',
			[
				'label'      => esc_html__( 'Button Text', 'absolute-addons' ),
				'type'       => Controls_Manager::TEXT,
				'default'    => esc_html__( 'Button Text', 'absolute-addons' ),
				'conditions' => [
					'relation' => 'and',
					'terms'    => [
						[
							'terms' => [
								[
									'name'     => 'absolute_content_card',
									'operator' => '!==',
									'value'    => 'three',
								],
								[
									'name'     => 'absolute_content_card',
									'operator' => '!==',
									'value'    => 'seven',
								],
								[
									'name'     => 'absolute_content_card',
									'operator' => '!==',
									'value'    => 'ten',
								],
							],
						],
						[
							'terms' => [
								[
									'name'     => 'enable_button',
									'operator' => '==',
									'value'    => 'yes',
								],
							],
						],
					],
				],
			]
		);
		$this->add_control(
			'content_card_button_icon_only',
			[
				'label'      => esc_html__( 'Button Icon', 'absolute-addons' ),
				'type'       => Controls_Manager::ICONS,
				'default'    => [
					'value'   => 'fas fa-cart-arrow-down',
					'library' => 'solid',
				],
				'conditions' => [
					'relation' => 'or',
					'terms'    => [
						[
							'name'     => 'absolute_content_card',
							'operator' => '==',
							'value'    => 'seven',
						],
						[
							'name'     => 'absolute_content_card',
							'operator' => '==',
							'value'    => 'ten',
						],
					],
				],
				'condition'  => [
					'enable_button' => 'yes',
				],
			]
		);
		$this->add_control(
			'content_card_box_button_url',
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
				'conditions'    => [
					'relation' => 'and',
					'terms'    => [
						[
							'name'     => 'absolute_content_card',
							'operator' => '!==',
							'value'    => 'three',
						],
					],
				],
				'condition'     => [
					'enable_button' => 'yes',
				],
			]
		);
		//Content card box button icon start
		$this->add_control(
			'content_card_button_icon_switch',
			[
				'label'        => esc_html__( 'Button Icon', 'absolute-addons' ),
				'description'  => esc_html__( '(If checked, icon will be show)', 'absolute-addons' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Show', 'absolute-addons' ),
				'label_off'    => esc_html__( 'Hide', 'absolute-addons' ),
				'return_value' => 'button-icon',
				'default'      => 'button-icon',
				'separator'    => 'before',
				'conditions'   => [
					'relation' => 'and',
					'terms'    => [
						[
							'terms' => [
								[
									'name'     => 'absolute_content_card',
									'operator' => '!==',
									'value'    => 'three',
								],
								[
									'name'     => 'absolute_content_card',
									'operator' => '!==',
									'value'    => 'seven',
								],
								[
									'name'     => 'absolute_content_card',
									'operator' => '!==',
									'value'    => 'ten',
								],
							],
						],
						[
							'terms' => [
								[
									'name'     => 'enable_button',
									'operator' => '==',
									'value'    => 'yes',
								],
							],
						],
					],
				],
			]
		);

		$this->add_control(
			'content_card_button_icon',
			[
				'label'      => esc_html__( 'Button Icon', 'absolute-addons' ),
				'type'       => Controls_Manager::ICONS,
				'conditions' => [
					'relation' => 'and',
					'terms'    => [
						[
							'terms' => [
								[
									'name'     => 'absolute_content_card',
									'operator' => '!==',
									'value'    => 'three',
								],
								[
									'name'     => 'absolute_content_card',
									'operator' => '!==',
									'value'    => 'seven',
								],
								[
									'name'     => 'absolute_content_card',
									'operator' => '!==',
									'value'    => 'ten',
								],
								[
									'name'     => 'content_card_button_icon_switch',
									'operator' => '==',
									'value'    => 'button-icon',
								],
							],
						],
						[
							'terms' => [
								[
									'name'     => 'enable_button',
									'operator' => '==',
									'value'    => 'yes',
								],
							],
						],
					],
				],
				'default'    => [
					'value'   => 'fas fa-angle-right',
					'library' => 'solid',
				],
			]
		);
		$this->add_control(
			'content_card_button_icon_position',
			array(
				'label'      => esc_html__( 'Button Icon Position', 'absolute-addons' ),
				'type'       => Controls_Manager::SELECT,
				'options'    => array(
					'before' => esc_html__( 'Before', 'absolute-addons' ),
					'after'  => esc_html__( 'After', 'absolute-addons' ),
				),
				'conditions' => [
					'relation' => 'and',
					'terms'    => [
						[
							'terms' => [
								[
									'name'     => 'absolute_content_card',
									'operator' => '!==',
									'value'    => 'three',
								],
								[
									'name'     => 'absolute_content_card',
									'operator' => '!==',
									'value'    => 'seven',
								],
								[
									'name'     => 'absolute_content_card',
									'operator' => '!==',
									'value'    => 'ten',
								],
								[
									'name'     => 'content_card_button_icon_switch',
									'operator' => '==',
									'value'    => 'button-icon',
								],
							],
						],
						[
							'terms' => [
								[
									'name'     => 'enable_button',
									'operator' => '==',
									'value'    => 'yes',
								],
							],
						],
					],
				],
				'default'    => 'after',
			)
		);
		$this->add_responsive_control(
			'content_card_button_icon_spacing',
			[
				'label'      => esc_html__( 'Button Icon Spacing', 'absolute-addons' ),
				'type'       => Controls_Manager::SLIDER,
				'range'      => [
					'px' => [
						'max' => 50,
					],
				],
				'conditions' => [
					'relation' => 'and',
					'terms'    => [
						[
							'terms' => [
								[
									'name'     => 'absolute_content_card',
									'operator' => '!==',
									'value'    => 'three',
								],
								[
									'name'     => 'absolute_content_card',
									'operator' => '!==',
									'value'    => 'seven',
								],
								[
									'name'     => 'absolute_content_card',
									'operator' => '!==',
									'value'    => 'ten',
								],
								[
									'name'     => 'content_card_button_icon_switch',
									'operator' => '==',
									'value'    => 'button-icon',
								],
							],
						],
						[
							'terms' => [
								[
									'name'     => 'enable_button',
									'operator' => '==',
									'value'    => 'yes',
								],
							],
						],
					],
				],

				'selectors'  => [
					'{{WRAPPER}} .absp-wrapper .absp-content-card-item .content-card-box-button-icon-after' => 'margin-left: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .absp-wrapper .absp-content-card-item .content-card-box-button-icon-before' => 'margin-right: {{SIZE}}{{UNIT}};',
				],
			]
		);
		//Content card box button icon end
		$this->end_controls_section();
		//Content card box button end


		//Content card box rating section start
		$this->start_controls_section(
			'section_rating',
			array(
				'label'      => esc_html__( 'Rating', 'absolute-addons' ),
				'conditions' => [
					'relation' => 'or',
					'terms'    => [
						[
							'name'     => 'absolute_content_card',
							'operator' => '==',
							'value'    => 'four',
						],
						[
							'name'     => 'absolute_content_card',
							'operator' => '==',
							'value'    => 'five',
						],
					],
				],
			)
		);
		$this->add_control(
			'content_card_rating',
			[
				'label'        => esc_html__( 'Show Rating?', 'absolute-addons' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __('Yes', 'absolute-addons'),
				'label_off'    => __('No', 'absolute-addons'),
				'return_value' => 'yes',
				'default'      => 'no',
				'separator'    => 'before',
			]
		);
		$this->add_control(
			'rating_scale',
			[
				'label'     => esc_html__( 'Rating Scale', 'absolute-addons' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => [
					'5'  => '0-5',
					'10' => '0-10',
				],
				'default'   => '5',
				'condition' => [
					'content_card_rating' => 'yes',
				],
			]
		);
		$this->add_control(
			'rating',
			[
				'label'     => esc_html__( 'Rating', 'absolute-addons' ),
				'type'      => Controls_Manager::NUMBER,
				'min'       => 0,
				'max'       => 10,
				'step'      => 0.1,
				'default'   => 5,
				'condition' => [
					'content_card_rating' => 'yes',
				],
			]
		);
		$this->add_control(
			'rating_count',
			[
				'label'     => esc_html__( 'Rating Count', 'absolute-addons' ),
				'type'      => Controls_Manager::NUMBER,
				'min'       => 0,
				'max'       => 1000,
				'step'      => 0.1,
				'default'   => 4.9,
				'condition' => [
					'content_card_rating' => 'yes',
				],
			]
		);
		$this->add_control(
			'star_style',
			[
				'label'        => esc_html__( 'Icon', 'absolute-addons' ),
				'type'         => Controls_Manager::SELECT,
				'options'      => [
					'star_fontawesome' => 'Font Awesome',
					'star_unicode'     => 'Unicode',
				],
				'default'      => 'star_fontawesome',
				'render_type'  => 'template',
				'prefix_class' => 'elementor--star-style-',
				'separator'    => 'before',
				'condition'    => [
					'content_card_rating' => 'yes',
				],
			]
		);
		$this->add_control(
			'unmarked_star_style',
			[
				'label'     => esc_html__( 'Unmarked Style', 'absolute-addons' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => [
					'solid'   => [
						'title' => esc_html__( 'Solid', 'absolute-addons' ),
						'icon'  => 'eicon-star',
					],
					'outline' => [
						'title' => esc_html__( 'Outline', 'absolute-addons' ),
						'icon'  => 'eicon-star-o',
					],
				],
				'default'   => 'solid',
				'condition' => [
					'content_card_rating' => 'yes',
				],
			]
		);
		//Content card box rating section end
		$this->end_controls_section();

		//Content card box separator start
		$this->start_controls_section(
			'content_card_separator_section',
			array(
				'label'      => esc_html__( 'Separator', 'absolute-addons' ),
				'conditions' => [
					'relation' => 'or',
					'terms'    => [
						[
							'name'     => 'absolute_content_card',
							'operator' => '==',
							'value'    => 'four',
						],
					],
				],
			)
		);
		$this->add_control(
			'content_card_separator',
			[
				'label'        => esc_html__( 'Display Separator?', 'absolute-addons' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'content-card-show-separator',
				'default'      => 'content-card-show-separator',
			]
		);
		$this->add_control(
			'content_card_separator_color',
			[
				'label'     => esc_html__( 'Separator Color', 'absolute-addons' ),
				'type'      => Controls_Manager::COLOR,
				'condition' => [
					'content_card_separator' => 'content-card-show-separator',
				],
				'selectors' => [
					'{{WRAPPER}} .absp-wrapper .absp-content-card-item .content-card-box .content-card-box-border::before' => 'background-color: {{VALUE}};',
					'{{WRAPPER}} .absp-wrapper .absp-content-card-item .content-card-box .content-card-box-border::after' => 'background-color: {{VALUE}};',
				],
			]
		);
		$this->add_control(
			'countdown_separator_width',
			[
				'label'          => esc_html__( 'Separator Height', 'absolute-addons' ),
				'type'           => Controls_Manager::SLIDER,
				'size_units'     => [ '%', 'px' ],
				'range'          => [
					'px' => [
						'max' => 1000,
					],
				],
				'tablet_default' => [
					'unit' => '%',
				],
				'mobile_default' => [
					'unit' => '%',
				],
				'condition'      => [
					'content_card_separator' => 'content-card-show-separator',
				],
				'selectors'      => [
					'{{WRAPPER}} .absp-wrapper .absp-content-card-item .content-card-box .content-card-box-border::before' => 'height: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .absp-wrapper .absp-content-card-item .content-card-box .content-card-box-border::after' => 'height: {{SIZE}}{{UNIT}};',
				],
			]
		);
		$this->end_controls_section();
		//Content card box separator End

		$this->render_controller( 'style-controller-content-card-settings' );
		$this->render_controller( 'style-controller-content-card-title' );
		$this->render_controller( 'style-controller-content-card-sub-title' );
		$this->render_controller( 'style-controller-content-card-tag' );
		$this->render_controller( 'style-controller-content-card-content' );
		$this->render_controller( 'style-controller-content-card-currency' );
		$this->render_controller( 'style-controller-content-card-images' );
		$this->render_controller( 'style-controller-content-card-label' );
		$this->render_controller( 'style-controller-content-card-one-sale-price' );
		$this->render_controller( 'style-controller-content-card-rating' );
		$this->render_controller( 'style-controller-content-card-regular-price' );
		$this->render_controller( 'style-controller-content-card-sale-price' );
		$this->render_controller( 'style-controller-content-card-three-time-duration' );
		$this->render_controller( 'style-controller-content-card-shape' );
		$this->render_controller( 'style-controller-content-card-button' );
		/**
		 * Fires after controllers are registered.
		 *
		 * @param Absoluteaddons_Style_Content_Card $this Current instance of WP_Network_Query (passed by reference).
		 *
		 * @since 1.0.0
		 *
		 */
		do_action_ref_array( $this->get_prefixed_hook( 'controllers/ends' ), [ &$this ] );

	}

	/**
	 * @since 1.1.0
	 * @access protected
	 */
	protected function get_rating() {
		$settings = $this->get_settings_for_display();
		$rating_scale = (int) $settings['rating_scale'];
		$rating = (float) $settings['rating'] > $rating_scale ? $rating_scale : $settings['rating'];

		return [ $rating, $rating_scale ];
	}

	/**
	 * Print the actual stars and calculate their filling.
	 *
	 * Rating type is float to allow stars-count to be a fraction.
	 * Floored-rating type is int, to represent the rounded-down stars count.
	 * In the `for` loop, the index type is float to allow comparing with the rating value.
	 *
	 * @since 1.1.0
	 * @access protected
	 */
	protected function render_stars( $icon ) {
		$rating_data    = $this->get_rating();
		$rating         = (float) $rating_data[0];
		$floored_rating = floor( $rating );
		$stars_html     = '';

		for ( $stars = 1.0; $stars <= $rating_data[1]; $stars++ ) {
			if ( $stars <= $floored_rating ) {
				$stars_html .= '<i class="elementor-star-full">' . $icon . '</i>';
			} elseif ( $floored_rating + 1 === $stars && $rating !== $floored_rating ) {
				$stars_html .= '<i class="elementor-star-' . ( $rating - $floored_rating ) * 10 . '">' . $icon . '</i>';
			} else {
				$stars_html .= '<i class="elementor-star-empty">' . $icon . '</i>';
			}
		}

		return $stars_html;
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
		$settings       = $this->get_settings_for_display();
		$rating_data    = $this->get_rating();
		$textual_rating = $rating_data[0] . '/' . $rating_data[1];
		$icon           = '&#xE934;';

		if ( 'star_fontawesome' === $settings['star_style'] ) {
			if ( 'outline' === $settings['unmarked_star_style'] ) {
				$icon = '&#xE933;';
			}
		} elseif ( 'star_unicode' === $settings['star_style'] ) {
			$icon = '&#9733;';

			if ( 'outline' === $settings['unmarked_star_style'] ) {
				$icon = '&#9734;';
			}
		}

		$this->add_render_attribute( 'icon_wrapper', [
			'class'     => 'elementor-star-rating',
			'title'     => $textual_rating,
			'itemtype'  => 'https://schema.org/Rating',
			'itemscope' => '',
			'itemprop'  => 'reviewRating',
		] );

		$schema_rating = '<span itemprop="ratingValue" class="elementor-screen-only">' . $textual_rating . '</span>';
		$stars_element = '<div ' . $this->get_render_attribute_string( 'icon_wrapper' ) . '>' . $this->render_stars( $icon ) . ' ' . $schema_rating . '</div>';

		$this->add_inline_editing_attributes( 'content_card_box_title' );
		$this->add_render_attribute( 'content_card_box_title', 'class', 'content-card-box-title' );

		//Content card box sub title inline attribut start
		$this->add_inline_editing_attributes( 'content_card_box_sub_title' );
		$this->add_render_attribute( 'content_card_box_sub_title', 'class', 'content-card-box-sub-title' );

		$this->add_inline_editing_attributes( 'content_card_box_sub_title_two' );
		$this->add_render_attribute( 'content_card_box_sub_title_two', 'class', 'content-card-box-sub-title' );

		$this->add_inline_editing_attributes( 'content_card_box_sub_title_fifteen' );
		$this->add_render_attribute( 'content_card_box_sub_title_fifteen', 'class', 'content-card-box-sub-title' );

		//Content card box time duration start
		$this->add_inline_editing_attributes( 'content_card_box_time_duration' );
		$this->add_render_attribute( 'content_card_box_time_duration', 'class', 'content-card-box-delivery-time' );

		//Content card box regular price inline attribut start
		$this->add_inline_editing_attributes( 'content_card_box_regular_price_two' );
		$this->add_render_attribute( 'content_card_box_regular_price_two', 'class', 'content-card-box-regular-price' );

		//Content card box sale price inline attribut start
		$this->add_inline_editing_attributes( 'content_card_box_sale_price_one' );
		$this->add_render_attribute( 'content_card_box_sale_price_one', 'class', 'content-card-box-sale-price' );

		$this->add_inline_editing_attributes( 'content_card_box_sale_price_two' );
		$this->add_render_attribute( 'content_card_box_sale_price_two', 'class', 'content-card-box-sale-price' );

		//Content card box label inline attribut start
		$this->add_inline_editing_attributes( 'content_card_box_label' );
		$this->add_render_attribute( 'content_card_box_label', 'class', 'content-card-box-label' );


		//Content card box button inline attribut start
		$this->add_inline_editing_attributes( 'content_card_box_button' );

		if ( ! empty( $settings['content_card_box_button_url']['url'] ) ) {
			$this->add_link_attributes( 'content_card_box_button', $settings['content_card_box_button_url'] );
		}

		$regular_price          = ! empty( $settings['content_card_box_regular_price'] ) ? $settings['content_card_box_regular_price'] : 0;
		$regular_price_array    = absp_regular_pricing_price_format( $regular_price );
		$price          = ! empty( $settings['content_card_box_sale_price'] ) ? $settings['content_card_box_sale_price'] : 0;
		$price_array    = absp_pricing_price_format( $price );

		?>
		<div class="absp-wrapper absp-widget">
			<div class="absp-wrapper-inside">
				<div class="absp-wrapper-content">
					<!-- absp-content-card-item -->
					<div class="absp-content-card-item element-<?php echo esc_attr( $settings['absolute_content_card'] ); ?>">
						<?php
						$cart_image_class = 'content-card-box-img';
						$hover_animation = isset( $settings['content_card_img_hover_animation'] ) && 'enable' === $settings['content_card_img_hover_animation'];
						if ( $hover_animation ) {
							$cart_image_class .= ' content-card-img-hover-animation';
						}
						$this->render_template( $settings['absolute_content_card'], [
							'hover_animation'     => $hover_animation,
							'cart_image_class'    => $cart_image_class,
							'price'               => $price,
							'price_array'         => $price_array,
							'regular_price'       => $regular_price,
							'regular_price_array' => $regular_price_array,
							'stars_element'       => $stars_element,
						] );
						?>
					</div>
					<!-- absp-content-card-item -->
				</div>
			</div>
		</div>
		<?php
	}

	protected function render_box_label( $settings ) {
		if ( ! empty( $settings['content_card_box_label'] ) ) { ?>
			<span <?php $this->print_render_attribute_string( 'content_card_box_label' ); ?>><?php echo esc_html( $settings['content_card_box_label'] ); ?></span>
		<?php }
	}

	protected function render_box_image( $settings, $cart_image_class ) {
		if ( ! empty( $settings['content_card_box_image'] ) ) { ?>
			<div class="<?php echo esc_attr( $cart_image_class ); ?>">
				<img src="<?php echo esc_url( $settings['content_card_box_image']['url'] ); ?>">
			</div>
		<?php }
	}

	protected function render_box_border_shape( $settings ) {
		?>
		<div class="content-card-box-border-shape">
			<?php if ( 'style1' === $settings['content_card_image_background_style'] ) { ?>
				<svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 460 390" style="enable-background:new 0 0 460 390;" xml:space="preserve">
					<polygon class="content-card-box-shape1-class1" points="460,190 0,340 0,0 460,0 "/>
					<polygon class="content-card-box-shape1-class1" points="372,382 88,382 88,311.26 80,313.97 80,390 380,390 380,216.1 372,218.7 "/>
					<g>
						<polygon class="content-card-box-shape1-class2" points="80.5,90.5 379.5,90.5 379.5,215.74 372.5,218.02 372.5,97.5 87.5,97.5 87.5,310.9 80.5,313.27 	"/>
						<path class="content-card-box-shape1-class2" d="M379,91v124.38l-6,1.95V98v-1h-1H88h-1v1v212.54l-6,2.03V91H379 M380,90H80v223.97l8-2.71V98h284v120.7l8-2.6 V90L380,90z"/>
					</g>
					</svg>
			<?php } ?>
			<?php if ( 'style2' === $settings['content_card_image_background_style'] ) { ?>
				<svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 460 390" style="enable-background:new 0 0 460 390;" xml:space="preserve">
					<polygon class="content-card-box-shape2-class1" points="0,190 460,340 460,0 0,0 "/>
					<polygon class="content-card-box-shape2-class1" points="88,382 372,382 372,311.26 380,313.97 380,390 80,390 80,216.1 88,218.7 "/>
					<g>
						<polygon class="content-card-box-shape2-class2" points="372.5,310.9 372.5,97.5 87.5,97.5 87.5,218.02 80.5,215.74 80.5,90.5 379.5,90.5 379.5,313.27 	"/>
						<path class="content-card-box-shape2-class2" d="M379,91v221.57l-6-2.03V98v-1h-1H88h-1v1v119.33l-6-1.95V91H379 M380,90H80v126.1l8,2.6V98h284v213.26l8,2.71 V90L380,90z"/>
					</g>
					</svg>
			<?php } ?>
		</div>
		<?php
	}

	protected function render_card_box_title( $settings ) {
		if ( ! empty( $settings['content_card_box_title'] ) ) { ?>
			<h2 <?php $this->print_render_attribute_string( 'content_card_box_title' ); ?> ><?php absp_render_title( $settings['content_card_box_title'] ); ?></h2>
		<?php }
	}

	protected function render_card_button_icon( $settings ) {
		if ( 'svg' === $settings['content_card_button_icon']['library'] ) {
			if ( ! empty( $settings['content_card_button_icon']['value']['id'] ) ) {
				echo '<div class="content-card-box-button-svg-img">';
				echo wp_get_attachment_image( $settings['content_card_button_icon']['value']['id'] );
				echo '</div>';

			} else { ?>
				<img src="<?php echo esc_url( $settings['content_card_button_icon']['value']['url'] ); ?>" alt="Placeholder Image">
				<?php
			}
		} else { ?>
			<div class="content-card-box-button-icon">
				<i class="<?php echo esc_attr( $settings['content_card_button_icon']['value'] ); ?>" aria-hidden="true"></i>
			</div>
			<?php
		}
	}

	protected function render_card_button( $settings, $only_icon = false ) {
		if ( isset( $settings['enable_button'] ) && 'yes' === $settings['enable_button'] ) {
		$class = 'content-card-box-btn';
		if ( $only_icon ) {
			$class .= ' content-card-icon-only-btn';
		}
		$this->add_render_attribute( 'content_card_box_button', 'class', $class );
		?>
		<a <?php $this->print_render_attribute_string( 'content_card_box_button' ); ?> >
			<?php
			if ( $only_icon ) {
				if ( 'svg' === $settings['content_card_button_icon_only']['library'] ) {
					if ( ! empty( $settings['content_card_button_icon_only']['value']['id'] ) ) {
						echo '<div class="content-card-box-button-svg-img">';
						echo wp_get_attachment_image( $settings['content_card_button_icon_only']['value']['id'] );
						echo '</div>';
					} else { ?>
						<img src="<?php echo esc_url( $settings['content_card_button_icon_only']['value']['url'] ); ?>" alt="Placeholder Image">
						<?php
					}
				} else {
					?>
					<div class="content-card-box-button-icon">
						<i class="<?php echo esc_attr( $settings['content_card_button_icon_only']['value'] ); ?>" aria-hidden="true"></i>
					</div>
					<?php
				}
			} else {
				if ( 'before' === $settings['content_card_button_icon_position'] ) { ?>
					<div class="content-card-box-button-icon-before">
						<?php $this->render_card_button_icon( $settings ); ?>
					</div>
				<?php }
				absp_render_title( $settings['content_card_box_button'] );
				if ( 'after' === $settings['content_card_button_icon_position'] ) { ?>
					<div class="content-card-box-button-icon-after">
						<?php $this->render_card_button_icon( $settings ); ?>
					</div>
				<?php }
			}
			?>
		</a>
		<?php

		}
	}
}
