<?php
/**
 * Pricing table widget class
 *
 * @package Skt_Addons_Elementor
 */
namespace Skt_Addons_Elementor\Elementor\Widget;

use Elementor\Group_Control_Background;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Icons_Manager;
use Elementor\Repeater;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Utils;

defined( 'ABSPATH' ) || die();

class Pricing_Table extends Base {

	/**
	 * Get widget title.
	 *
	 * @since 1.0
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( 'Pricing Table', 'skt-addons-elementor' );
	}

	/**
	 * Get widget icon.
	 *
	 * @since 1.0
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'skti skti-file-cabinet';
	}

	public function get_keywords() {
		return [ 'price',  'pricing', 'table', 'package', 'product', 'plan' ];
	}

	/**
     * Register widget content controls
     */
	protected function register_content_controls() {
		$this->__header_content_controls();
		$this->__price_content_controls();
		$this->__features_desc_content_controls();
		$this->__footer_content_controls();
		$this->__badge_content_controls();
	}

	protected function __header_content_controls() {

		$this->start_controls_section(
			'_section_header',
			[
				'label' => __( 'Header', 'skt-addons-elementor' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'title',
			[
				'label' => __( 'Title', 'skt-addons-elementor' ),
				'type' => Controls_Manager::TEXT,
				'label_block' => false,
				'default' => __( 'Basic', 'skt-addons-elementor' ),
				'dynamic' => [
					'active' => true
				]
			]
		);

		$this->add_control(
			'media_type',
			[
				'label' => __( 'Media Type', 'skt-addons-elementor' ),
				'type' => Controls_Manager::CHOOSE,
				'label_block' => false,
				'separator' => 'before',
				'options' => [
					'icon' => [
						'title' => __( 'Icon', 'skt-addons-elementor' ),
						'icon' => 'eicon-star',
					],
					'image' => [
						'title' => __( 'Image', 'skt-addons-elementor' ),
						'icon' => 'eicon-image',
					],
				],
				'default' => 'icon',
				'toggle' => false,
				'style_transfer' => true,
			]
		);

		$this->add_control(
			'icon',
			[
				'label' => __( 'Icon', 'skt-addons-elementor' ),
				'type' => Controls_Manager::ICONS,
				'default' => [
					'value' => 'far fa-star',
					'library' => 'regular',
				],
				'condition' => [
					'media_type' => 'icon'
				],
			]
		);

		$this->add_control(
			'image',
			[
				'label' => __( 'Image', 'skt-addons-elementor' ),
				'type' => Controls_Manager::MEDIA,
				'default' => [
					'url' => Utils::get_placeholder_image_src(),
				],
				'condition' => [
					'media_type' => 'image'
				],
				'dynamic' => [
					'active' => true,
				]
			]
		);

		$this->add_group_control(
			Group_Control_Image_Size::get_type(),
			[
				'name' => 'media_thumbnail',
				'default' => 'full',
				'separator' => 'none',
				'exclude' => [
					'custom',
				],
				'condition' => [
					'media_type' => 'image'
				]
			]
		);

		$this->add_control(
			'icon_position',
			[
				'label' => __( 'Position', 'skt-addons-elementor' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'before_header',
				'options' => [
					'after_header'  => __( 'After Title', 'skt-addons-elementor' ),
					'before_header'  => __( 'Before Title', 'skt-addons-elementor' ),
				],
				'style_transfer' => true,
			]
		);

		$this->end_controls_section();
	}

	protected function __price_content_controls() {

		$this->start_controls_section(
			'_section_pricing',
			[
				'label' => __( 'Pricing', 'skt-addons-elementor' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'currency',
			[
				'label' => __( 'Currency', 'skt-addons-elementor' ),
				'type' => Controls_Manager::SELECT,
				'label_block' => false,
				'options' => [
					''             => __( 'None', 'skt-addons-elementor' ),
					'baht'         => '&#3647; ' . _x( 'Baht', 'Currency Symbol', 'skt-addons-elementor' ),
					'bdt'          => '&#2547; ' . _x( 'BD Taka', 'Currency Symbol', 'skt-addons-elementor' ),
					'dollar'       => '&#36; ' . _x( 'Dollar', 'Currency Symbol', 'skt-addons-elementor' ),
					'euro'         => '&#128; ' . _x( 'Euro', 'Currency Symbol', 'skt-addons-elementor' ),
					'franc'        => '&#8355; ' . _x( 'Franc', 'Currency Symbol', 'skt-addons-elementor' ),
					'guilder'      => '&fnof; ' . _x( 'Guilder', 'Currency Symbol', 'skt-addons-elementor' ),
					'krona'        => 'kr ' . _x( 'Krona', 'Currency Symbol', 'skt-addons-elementor' ),
					'lira'         => '&#8356; ' . _x( 'Lira', 'Currency Symbol', 'skt-addons-elementor' ),
					'peseta'       => '&#8359 ' . _x( 'Peseta', 'Currency Symbol', 'skt-addons-elementor' ),
					'peso'         => '&#8369; ' . _x( 'Peso', 'Currency Symbol', 'skt-addons-elementor' ),
					'pound'        => '&#163; ' . _x( 'Pound Sterling', 'Currency Symbol', 'skt-addons-elementor' ),
					'real'         => 'R$ ' . _x( 'Real', 'Currency Symbol', 'skt-addons-elementor' ),
					'ruble'        => '&#8381; ' . _x( 'Ruble', 'Currency Symbol', 'skt-addons-elementor' ),
					'rupee'        => '&#8360; ' . _x( 'Rupee', 'Currency Symbol', 'skt-addons-elementor' ),
					'indian_rupee' => '&#8377; ' . _x( 'Rupee (Indian)', 'Currency Symbol', 'skt-addons-elementor' ),
					'shekel'       => '&#8362; ' . _x( 'Shekel', 'Currency Symbol', 'skt-addons-elementor' ),
					'won'          => '&#8361; ' . _x( 'Won', 'Currency Symbol', 'skt-addons-elementor' ),
					'yen'          => '&#165; ' . _x( 'Yen/Yuan', 'Currency Symbol', 'skt-addons-elementor' ),
					'custom'       => __( 'Custom', 'skt-addons-elementor' ),
				],
				'default' => 'dollar',
			]
		);

		$this->add_control(
			'currency_custom',
			[
				'label' => __( 'Custom Symbol', 'skt-addons-elementor' ),
				'type' => Controls_Manager::TEXT,
				'condition' => [
					'currency' => 'custom',
				],
			]
		);

		$this->add_control(
			'price',
			[
				'label' => __( 'Price', 'skt-addons-elementor' ),
				'type' => Controls_Manager::TEXT,
				'default' => '9.99',
				'dynamic' => [
					'active' => true
				]
			]
		);

		$this->add_control(
			'original_price',
			[
				'label' => __( 'Original Price', 'skt-addons-elementor' ),
				'type' => Controls_Manager::TEXT,
				'default' => '8.99',
				'dynamic' => [
					'active' => true
				]
			]
		);

		$this->add_control(
			'period',
			[
				'label' => __( 'Period', 'skt-addons-elementor' ),
				'type' => Controls_Manager::TEXT,
				'default' => __( 'Per Month', 'skt-addons-elementor' ),
				'dynamic' => [
					'active' => true
				]
			]
		);

		$this->add_control(
			'currency_side',
			[
				'label' => __( 'Currency Position', 'skt-addons-elementor' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'left',
				'options' => [
					'left'  => __( 'Left', 'skt-addons-elementor' ),
					'right'  => __( 'Right', 'skt-addons-elementor' ),
				],
				'style_transfer' => true,
			]
		);

		$this->add_control(
			'price_position',
			[
				'label' => __( 'Position', 'skt-addons-elementor' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'after_header',
				'options' => [
					'after_header'  => __( 'After Header', 'skt-addons-elementor' ),
					'before_button'  => __( 'Before Button', 'skt-addons-elementor' ),
				],
				'style_transfer' => true,
			]
		);

		$this->end_controls_section();
	}

	protected function __features_desc_content_controls() {

		$this->start_controls_section(
			'_section_features_and_description',
			[
				'label' => __( 'Features & Description', 'skt-addons-elementor' ),
			]
		);

		$this->add_control(
			'features_title',
			[
				'label' => __( 'Title', 'skt-addons-elementor' ),
				'type' => Controls_Manager::TEXT,
				'default' => __( 'Features', 'skt-addons-elementor' ),
				'separator' => 'after',
				'dynamic' => [
					'active' => true
				]
			]
		);

		$repeater = new Repeater();

		if ( skt_addons_elementor_is_elementor_version( '<', '2.6.0' ) ) {
			$repeater->add_control(
				'icon',
				[
					'label' => __( 'Icon', 'skt-addons-elementor' ),
					'type' => Controls_Manager::ICON,
					'label_block' => false,
					'options' => skt_addons_elementor_get_skt_addons_elementor_icons(),
					'default' => 'fa fa-check',
					'include' => [
						'fa fa-check',
						'fa fa-close',
					]
				]
			);
		} else {
			$repeater->add_control(
				'selected_icon',
				[
					'label' => __( 'Icon', 'skt-addons-elementor' ),
					'type' => Controls_Manager::ICONS,
					'fa4compatibility' => 'icon',
					'default' => [
						'value' => 'fas fa-check',
						'library' => 'fa-solid',
					],
					'recommended' => [
						'fa-regular' => [
							'check-square',
							'window-close',
						],
						'fa-solid' => [
							'check',
						]
					]
				]
			);
		}

		$repeater->add_control(
			'text',
			[
				'label' => __( 'Text', 'skt-addons-elementor' ),
				'type' => Controls_Manager::TEXTAREA,
				'default' => __( 'Exciting Feature', 'skt-addons-elementor' ),
				'dynamic' => [
					'active' => true
				]
			]
		);

		$repeater->add_control(
			'tooltip_text',
			[
				'label' => __( 'Tooltip Text', 'skt-addons-elementor' ),
				'type' => Controls_Manager::TEXTAREA,
				'dynamic' => [
					'active' => true
				]
			]
		);

		$this->add_control(
			'features_list',
			[
				'type' => Controls_Manager::REPEATER,
				'fields' => $repeater->get_controls(),
				'show_label' => false,
				'prevent_empty' => false,
				'default' => [
					[
						'icon' => 'fa fa-check',
						'text' => __( 'Standard Feature', 'skt-addons-elementor' ),
					],
					[
						'icon' => 'fa fa-check',
						'text' => __( 'Another Great Feature', 'skt-addons-elementor' ),
						'tooltip_text' => __( 'It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout.', 'skt-addons-elementor' ),
					],
					[
						'icon' => 'fa fa-close',
						'text' => __( 'Obsolete Feature', 'skt-addons-elementor' ),
					],
					[
						'icon' => 'fa fa-check',
						'text' => __( 'Exciting Feature', 'skt-addons-elementor' ),
					],
				],
				'title_field' => '<# print(haGetFeatureLabel(text)); #>',
			]
		);

		$this->add_control(
			'description',
			[
				'label' => __( 'Description', 'skt-addons-elementor' ),
				'type' => Controls_Manager::TEXTAREA,
				'default' => __( 'It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters, as opposed to using \'Content here, content here\', making it look like readable English.', 'skt-addons-elementor' ),
				'placeholder' => __( 'Type description', 'skt-addons-elementor' ),
				'dynamic' => [
					'active' => true
				]
			]
		);

		$this->add_control(
			'features_alignment',
			[
				'label' => __( 'Features Alignment', 'skt-addons-elementor' ),
				'type' => Controls_Manager::CHOOSE,
				'label_block' => false,
				'options' => [
					'left' => [
						'title' => __( 'Left', 'skt-addons-elementor' ),
						'icon' => 'eicon-text-align-left',
					],
					'center' => [
						'title' => __( 'Center', 'skt-addons-elementor' ),
						'icon' => 'eicon-text-align-center',
					],
					'right' => [
						'title' => __( 'Right', 'skt-addons-elementor' ),
						'icon' => 'eicon-text-align-right',
					],
				],
				'toggle' => false,
				'selectors' => [
					'{{WRAPPER}} .skt-pricing-table-body' => 'text-align: {{VALUE}}',
				],
			]
		);

		$this->end_controls_section();
	}

	protected function __footer_content_controls() {

		$this->start_controls_section(
			'_section_footer',
			[
				'label' => __( 'Footer', 'skt-addons-elementor' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'button_text',
			[
				'label' => __( 'Button Text', 'skt-addons-elementor' ),
				'type' => Controls_Manager::TEXT,
				'default' => __( 'Subscribe', 'skt-addons-elementor' ),
				'placeholder' => __( 'Type button text here', 'skt-addons-elementor' ),
				'label_block' => false,
				'dynamic' => [
					'active' => true
				]
			]
		);

		$this->add_control(
			'button_link',
			[
				'label' => __( 'Link', 'skt-addons-elementor' ),
				'type' => Controls_Manager::URL,
				'label_block' => true,
				'placeholder' => 'https://example.com/',
				'default' => [
					'url' => '#'
				],
				'dynamic' => [
					'active' => true,
				],
			]
		);

		$this->add_control(
			'footer_description',
			[
				'label' => __( 'Footer Description', 'skt-addons-elementor' ),
				'show_label' => true,
				'type' => Controls_Manager::TEXTAREA,
				'dynamic' => [
					'active' => true,
				],
				'separator' => 'before',
			]
		);

		$this->end_controls_section();
	}

	protected function __badge_content_controls() {

		$this->start_controls_section(
			'_section_badge',
			[
				'label' => __( 'Badge', 'skt-addons-elementor' ),
			]
		);

		$this->add_control(
			'show_badge',
			[
				'label' => __( 'Show', 'skt-addons-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'Show', 'skt-addons-elementor' ),
				'label_off' => __( 'Hide', 'skt-addons-elementor' ),
				'return_value' => 'yes',
				'default' => 'yes',
				'style_transfer' => true,
			]
		);

		$this->add_control(
			'badge_text',
			[
				'label' => __( 'Badge Text', 'skt-addons-elementor' ),
				'type' => Controls_Manager::TEXT,
				'default' => __( 'Recommended', 'skt-addons-elementor' ),
				'placeholder' => __( 'Type badge text', 'skt-addons-elementor' ),
				'condition' => [
					'show_badge' => 'yes'
				],
				'dynamic' => [
					'active' => true
				]
			]
		);

		$this->add_control(
			'badge_position',
			[
				'label' => __( 'Position', 'skt-addons-elementor' ),
				'type' => Controls_Manager::CHOOSE,
				'label_block' => false,
				'options' => [
					'left' => [
						'title' => __( 'Left', 'skt-addons-elementor' ),
						'icon' => 'eicon-h-align-left',
					],
					'right' => [
						'title' => __( 'Right', 'skt-addons-elementor' ),
						'icon' => 'eicon-h-align-right',
					],
				],
				'toggle' => false,
				'default' => 'left',
				'style_transfer' => true,
				'condition' => [
					'show_badge' => 'yes'
				]
			]
		);

		$this->end_controls_section();
	}

	/**
     * Register widget style controls
     */
	protected function register_style_controls() {
		$this->__general_style_controls();
		$this->__header_style_controls();
		$this->__pricing_style_controls();
		$this->__features_desc_style_controls();
		$this->__tooltip_style_controls();
		$this->__footer_style_controls();
		$this->__badge_style_controls();
	}

	protected function __general_style_controls() {

		$this->start_controls_section(
			'_section_style_general',
			[
				'label' => __( 'General', 'skt-addons-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'text_color',
			[
				'label' => __( 'Text Color', 'skt-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .skt-pricing-table-icon,'
					. '{{WRAPPER}} .skt-pricing-table-title,'
					. '{{WRAPPER}} .skt-pricing-table-currency,'
					. '{{WRAPPER}} .skt-pricing-table-period,'
					. '{{WRAPPER}} .skt-pricing-table-features-title,'
					. '{{WRAPPER}} .skt-pricing-table-features-list li,'
					. '{{WRAPPER}} .skt-pricing-table-price-text,'
					. '{{WRAPPER}} .skt-pricing-table-description,'
					. '{{WRAPPER}} .skt-pricing-table-footer-description' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'overflow',
			[
				'label' => __( 'Overflow', 'skt-addons-elementor' ),
				'type' => Controls_Manager::SELECT,
				'label_block' => false,
				'options' => [
					'' => __( 'Default', 'skt-addons-elementor' ),
					'hidden' => __( 'Hidden', 'skt-addons-elementor' ),
				],
				'toggle' => false,
				'selectors' => [
					'{{WRAPPER}} > .elementor-widget-container' => 'overflow: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'alignment',
			[
				'label' => __( 'Alignment', 'skt-addons-elementor' ),
				'type' => Controls_Manager::CHOOSE,
				'label_block' => false,
				'options' => [
					'left' => [
						'title' => __( 'Left', 'skt-addons-elementor' ),
						'icon' => 'eicon-text-align-left',
					],
					'center' => [
						'title' => __( 'Center', 'skt-addons-elementor' ),
						'icon' => 'eicon-text-align-center',
					],
					'right' => [
						'title' => __( 'Right', 'skt-addons-elementor' ),
						'icon' => 'eicon-text-align-right',
					],
				],
				'toggle' => false,
				'selectors' => [
					'{{WRAPPER}}' => 'text-align: {{VALUE}}',
				],
			]
		);

		$this->end_controls_section();
	}

	protected function __header_style_controls() {

		$this->start_controls_section(
			'_section_style_header',
			[
				'label' => __( 'Header', 'skt-addons-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'header_area_header',
			[
				'label' => __( 'Container', 'skt-addons-elementor' ),
				'type' => Controls_Manager::HEADING,
			]
		);

		$this->add_control(
			'header_area_padding',
			[
				'label' => __( 'Padding', 'skt-addons-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .skt-pricing-table-header' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'header_area_background',
				'label' => __( 'Background', 'skt-addons-elementor' ),
				'types' => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .skt-pricing-table-header',
			]
		);

		$this->add_control(
			'title_style_header',
			[
				'label' => __( 'Title', 'skt-addons-elementor' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before'
			]
		);

		$this->add_responsive_control(
			'title_spacing',
			[
				'label' => __( 'Bottom Spacing', 'skt-addons-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'selectors' => [
					'{{WRAPPER}} .skt-pricing-table-header' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'title_color',
			[
				'label' => __( 'Title Color', 'skt-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .skt-pricing-table-title' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'title_typography',
				'selector' => '{{WRAPPER}} .skt-pricing-table-title',
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_SECONDARY,
			],
			]
		);

		$this->add_group_control(
			Group_Control_Text_Shadow::get_type(),
			[
				'name' => 'title_text_shadow',
				'selector' => '{{WRAPPER}} .skt-pricing-table-title',
			]
		);

		$this->add_control(
			'icon_style_header',
			[
				'label' => __( 'Media', 'skt-addons-elementor' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'icon_size',
			[
				'label' => __( 'Size', 'skt-addons-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'max' => 500,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .skt-pricing-table-media--icon > i' => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .skt-pricing-table-media--icon > svg' => 'width: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .skt-pricing-table-media--image > img' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'icon_spacing',
			[
				'label' => __( 'Bottom Spacing', 'skt-addons-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'selectors' => [
					'{{WRAPPER}} .skt-pricing-table-media' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'icon_color',
			[
				'label' => __( 'Icon Color', 'skt-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .skt-pricing-table-media--icon > i' => 'color: {{VALUE}};',
					'{{WRAPPER}} .skt-pricing-table-media--icon > svg' => 'fill: {{VALUE}};',
				],
				'condition' => [
					'media_type' => 'icon',
				]
			]
		);

		$this->end_controls_section();
	}

	protected function __pricing_style_controls() {

		$this->start_controls_section(
			'_section_style_pricing',
			[
				'label' => __( 'Pricing', 'skt-addons-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'_header_pricing_area',
			[
				'label' => __( 'Pricing Area', 'skt-addons-elementor' ),
				'type' => Controls_Manager::HEADING,
			]
		);

		$this->add_control(
			'pricing_area_padding',
			[
				'label' => __( 'Padding', 'skt-addons-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .skt-pricing-table-price' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'pricing_area_background',
				'label' => __( 'Background', 'skt-addons-elementor' ),
				'types' => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .skt-pricing-table-price',
			]
		);

		$this->add_control(
			'_heading_price',
			[
				'type' => Controls_Manager::HEADING,
				'label' => __( 'Price', 'skt-addons-elementor' ),
				'separator' => 'before'
			]
		);

		$this->add_responsive_control(
			'price_spacing',
			[
				'label' => __( 'Bottom Spacing', 'skt-addons-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'selectors' => [
					'{{WRAPPER}} .skt-pricing-table-price-tag' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'price_color',
			[
				'label' => __( 'Text Color', 'skt-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .skt-pricing-table-price-text' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'price_typography',
				'selector' => '{{WRAPPER}} .skt-pricing-table-price-text',
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_TEXT,
			],
			]
		);

		$this->add_control(
			'_heading_currency',
			[
				'type' => Controls_Manager::HEADING,
				'label' => __( 'Currency', 'skt-addons-elementor' ),
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'currency_spacing',
			[
				'label' => __( 'Side Spacing', 'skt-addons-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'selectors' => [
					'{{WRAPPER}} .skt-pricing-table-currency' => 'margin-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .skt-pricing-table-currency.right-pos' => 'margin-left: {{SIZE}}{{UNIT}};margin-right:0;',
				],
			]
		);

		$this->add_responsive_control(
			'currency_position',
			[
				'label' => __( 'Position', 'skt-addons-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => -100,
						'max' => 100,
						'step' => 1,
					]
				],
				'selectors' => [
					'{{WRAPPER}} .skt-pricing-table-current-price .skt-pricing-table-currency' => 'top: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'currency_color',
			[
				'label' => __( 'Text Color', 'skt-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .skt-pricing-table-currency' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'currency_typography',
				'selector' => '{{WRAPPER}} .skt-pricing-table-currency',
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_TEXT,
			],
			]
		);

		$this->add_control(
			'_heading_original_price',
			[
				'type' => Controls_Manager::HEADING,
				'label' => __( 'Original Price', 'skt-addons-elementor' ),
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'original_price_spacing',
			[
				'label' => __( 'Side Spacing', 'skt-addons-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => -100,
						'max' => 100,
						'step' => 1,
					]
				],
				'selectors' => [
					'{{WRAPPER}} .skt-pricing-table-original-price' => 'margin-right: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'original_price_position',
			[
				'label' => __( 'Position', 'skt-addons-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => -100,
						'max' => 100,
						'step' => 1,
					]
				],
				'selectors' => [
					'{{WRAPPER}} .skt-pricing-table-original-price .skt-pricing-table-currency,'
				   .'{{WRAPPER}} .skt-pricing-table-original-price .skt-pricing-table-price-text' => 'top: {{SIZE}}{{UNIT}};position:relative;',
				],
			]
		);


		$this->add_control(
			'original_price_color',
			[
				'label' => __( 'Text Color', 'skt-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .skt-pricing-table-original-price .skt-pricing-table-currency' => 'color: {{VALUE}};',
					'{{WRAPPER}} .skt-pricing-table-original-price .skt-pricing-table-price-text' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'original_price_typography',
				'selector' => '{{WRAPPER}} .skt-pricing-table-original-price .skt-pricing-table-currency,{{WRAPPER}} .skt-pricing-table-original-price .skt-pricing-table-price-text',
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_TEXT,
			],
			]
		);

		$this->add_control(
			'_heading_period',
			[
				'type' => Controls_Manager::HEADING,
				'label' => __( 'Period', 'skt-addons-elementor' ),
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'period_spacing',
			[
				'label' => __( 'Bottom Spacing', 'skt-addons-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'selectors' => [
					'{{WRAPPER}} .skt-pricing-table-price' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'period_color',
			[
				'label' => __( 'Text Color', 'skt-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .skt-pricing-table-period' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'period_typography',
				'selector' => '{{WRAPPER}} .skt-pricing-table-period',
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_TEXT,
			],
			]
		);

		$this->end_controls_section();
	}

	protected function __features_desc_style_controls() {

		$this->start_controls_section(
			'_section_style_features_description',
			[
				'label' => __( 'Features & Description', 'skt-addons-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);


		$this->add_control(
			'_heading_features',
			[
				'type' => Controls_Manager::HEADING,
				'label' => __( 'Features', 'skt-addons-elementor' ),
			]
		);

		$this->add_responsive_control(
			'features_container_spacing',
			[
				'label' => __( 'Container Bottom Spacing', 'skt-addons-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'selectors' => [
					'{{WRAPPER}} .skt-pricing-table-body' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'features_container_padding',
			[
				'label' => __( 'Container Padding', 'skt-addons-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .skt-pricing-table-body' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'_heading_features_title',
			[
				'type' => Controls_Manager::HEADING,
				'label' => __( 'Title', 'skt-addons-elementor' ),
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'features_title_spacing',
			[
				'label' => __( 'Bottom Spacing', 'skt-addons-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'selectors' => [
					'{{WRAPPER}} .skt-pricing-table-features-title' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'features_title_color',
			[
				'label' => __( 'Text Color', 'skt-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .skt-pricing-table-features-title' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'features_title_typography',
				'selector' => '{{WRAPPER}} .skt-pricing-table-features-title',
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_SECONDARY,
			],
			]
		);

		$this->add_control(
			'_heading_features_list',
			[
				'type' => Controls_Manager::HEADING,
				'label' => __( 'List', 'skt-addons-elementor' ),
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'features_list_spacing',
			[
				'label' => __( 'Spacing Between', 'skt-addons-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'selectors' => [
					'{{WRAPPER}} .skt-pricing-table-features-list > li' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'features_list_color',
			[
				'label' => __( 'Text Color', 'skt-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .skt-pricing-table-features-list > li' => 'color: {{VALUE}};',
					'{{WRAPPER}} .skt-pricing-table-features-list > li svg' => 'fill: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'features_list_typography',
				'selector' => '{{WRAPPER}} .skt-pricing-table-features-list > li',
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_TEXT,
			],
			]
		);

		$this->add_control(
			'_heading_description_style',
			[
				'type' => Controls_Manager::HEADING,
				'label' => __( 'Description', 'skt-addons-elementor' ),
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'description_spacing',
			[
				'label' => __( 'Bottom Spacing', 'skt-addons-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => -1000,
						'max' => 1000,
					]
				],
				'selectors' => [
					'{{WRAPPER}} .skt-pricing-table-description' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'description__padding',
			[
				'label' => __( 'Padding', 'skt-addons-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .skt-pricing-table-description' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'description_color',
			[
				'label' => __( 'Text Color', 'skt-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .skt-pricing-table-description' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'description_typography',
				'selector' => '{{WRAPPER}} .skt-pricing-table-description',
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_TEXT,
			],
			]
		);

		$this->end_controls_section();
	}

	protected function __tooltip_style_controls() {

		$this->start_controls_section(
			'_section_style_tooltip',
			[
				'label' => __( 'Features Tooltip', 'skt-addons-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'price_tooltip_padding',
			[
				'label' => __( 'Padding', 'skt-addons-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .skt-pricing-table-feature-tooltip-text' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'price_tooltip_border_radius',
			[
				'label' => __( 'Border Radius', 'skt-addons-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .skt-pricing-table-feature-tooltip-text' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'price_tooltip_border',
				'label' => __( 'Border', 'skt-addons-elementor' ),
				'selector' => '{{WRAPPER}} .skt-pricing-table-feature-tooltip-text',
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'price_tooltip_background',
				'label' => __( 'Background', 'skt-addons-elementor' ),
				'types' => [ 'classic', 'gradient'],
				'selector' => '{{WRAPPER}} .skt-pricing-table-feature-tooltip-text',
				'separator' => 'before',
				'exclude' => [
					'image',
				],
			]
		);

		$this->add_control(
			'price_tooltip_color',
			[
				'label' => __( 'Text Color', 'skt-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .skt-pricing-table-feature-tooltip-text' => 'color: {{VALUE}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'price_tooltip_typography',
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_TEXT,
			],
				'selector' => '{{WRAPPER}} .skt-pricing-table-feature-tooltip-text',
			]
		);

		$this->end_controls_section();
	}

	protected function __footer_style_controls() {

		$this->start_controls_section(
			'_section_style_footer',
			[
				'label' => __( 'Footer', 'skt-addons-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'_heading_button',
			[
				'type' => Controls_Manager::HEADING,
				'label' => __( 'Button', 'skt-addons-elementor' ),
			]
		);

		$this->add_responsive_control(
			'button_margin',
			[
				'label' => __( 'Margin', 'skt-addons-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .skt-pricing-table-btn' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'button_padding',
			[
				'label' => __( 'Padding', 'skt-addons-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .skt-pricing-table-btn' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'button_border',
				'selector' => '{{WRAPPER}} .skt-pricing-table-btn',
			]
		);

		$this->add_control(
			'button_border_radius',
			[
				'label' => __( 'Border Radius', 'skt-addons-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .skt-pricing-table-btn' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'button_box_shadow',
				'selector' => '{{WRAPPER}} .skt-pricing-table-btn',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'button_typography',
				'selector' => '{{WRAPPER}} .skt-pricing-table-btn',
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_ACCENT,
			],
			]
		);

		$this->add_responsive_control(
			'button_translate_y',
			[
				'label' => __( 'Offset Y', 'skt-addons-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => -1000,
						'max' => 1000,
					]
				],
				'render_type' => 'ui',
				'selectors' => [
					'{{WRAPPER}} .skt-pricing-table-btn' => '--pricing-table-btn-translate-y: {{SIZE}}{{UNIT}};',
				]
			]
		);

		$this->add_control(
			'hr',
			[
				'type' => Controls_Manager::DIVIDER,
				'style' => 'thick',
			]
		);

		$this->start_controls_tabs( '_tabs_button' );

		$this->start_controls_tab(
			'_tab_button_normal',
			[
				'label' => __( 'Normal', 'skt-addons-elementor' ),
			]
		);

		$this->add_control(
			'button_color',
			[
				'label' => __( 'Text Color', 'skt-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .skt-pricing-table-btn' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'button_bg_color',
			[
				'label' => __( 'Background Color', 'skt-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .skt-pricing-table-btn' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'_tab_button_hover',
			[
				'label' => __( 'Hover', 'skt-addons-elementor' ),
			]
		);

		$this->add_control(
			'button_hover_color',
			[
				'label' => __( 'Text Color', 'skt-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .skt-pricing-table-btn:hover, {{WRAPPER}} .skt-pricing-table-btn:focus' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'button_hover_bg_color',
			[
				'label' => __( 'Background Color', 'skt-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .skt-pricing-table-btn:hover, {{WRAPPER}} .skt-pricing-table-btn:focus' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'button_hover_border_color',
			[
				'label' => __( 'Border Color', 'skt-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'condition' => [
					'button_border_border!' => '',
				],
				'selectors' => [
					'{{WRAPPER}} .skt-pricing-table-btn:hover, {{WRAPPER}} .skt-pricing-table-btn:focus' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();
		$this->end_controls_tabs();

		$this->add_control(
			'footer_description_style_heading',
			[
				'label' => __( 'Footer Description', 'skt-addons-elementor' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'footer_description_margin',
			[
				'label' => __( 'Margin', 'skt-addons-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .skt-pricing-table-footer-description' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'footer_description_padding',
			[
				'label' => __( 'Padding', 'skt-addons-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .skt-pricing-table-footer-description' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'footer_description_color',
			[
				'label' => __( 'Text Color', 'skt-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .skt-pricing-table-footer-description' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'footer_description_typography',
				'selector' => '{{WRAPPER}} .skt-pricing-table-footer-description',
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_TEXT,
			],
			]
		);

		$this->end_controls_section();
	}

	protected function __badge_style_controls() {

		$this->start_controls_section(
			'_section_style_badge',
			[
				'label' => __( 'Badge', 'skt-addons-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'badge_padding',
			[
				'label' => __( 'Padding', 'skt-addons-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .skt-pricing-table-badge' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'badge_color',
			[
				'label' => __( 'Text Color', 'skt-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .skt-pricing-table-badge' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'badge_bg_color',
			[
				'label' => __( 'Background Color', 'skt-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .skt-pricing-table-badge' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'badge_border',
				'selector' => '{{WRAPPER}} .skt-pricing-table-badge',
			]
		);

		$this->add_responsive_control(
			'badge_border_radius',
			[
				'label' => __( 'Border Radius', 'skt-addons-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .skt-pricing-table-badge' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'badge_box_shadow',
				'selector' => '{{WRAPPER}} .skt-pricing-table-badge',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'badge_typography',
				'label' => __( 'Typography', 'skt-addons-elementor' ),
				'selector' => '{{WRAPPER}} .skt-pricing-table-badge',
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_TEXT,
			],
			]
		);

		$this->add_control(
			'badge_translate_toggle',
			[
				'label' => __( 'Offset', 'skt-addons-elementor' ),
				'type' => Controls_Manager::POPOVER_TOGGLE,
				'return_value' => 'yes',
			]
		);

		$this->start_popover();

		$this->add_responsive_control(
			'badge_translate_x',
			[
				'label' => __( 'Offset X', 'skt-addons-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => -1000,
						'max' => 1000,
					]
				],
				'condition' => [
					'badge_translate_toggle' => 'yes'
				],
				'selectors' => [
					'(desktop){{WRAPPER}} .skt-pricing-table-badge' => '--pricing-table-badge-translate-x: {{SIZE}}{{UNIT}};'
				]
			]
		);

		$this->add_responsive_control(
			'badge_translate_y',
			[
				'label' => __( 'Offset Y', 'skt-addons-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => -1000,
						'max' => 1000,
					]
				],
				'condition' => [
					'badge_translate_toggle' => 'yes'
				],
				'selectors' => [
					'{{WRAPPER}} .skt-pricing-table-badge' => '--pricing-table-badge-translate-y: {{SIZE}}{{UNIT}};'
				]
			]
		);

		$this->end_popover();

		$this->add_control(
			'badge_rotate_toggle',
			[
				'label' => __( 'Rotate', 'skt-addons-elementor' ),
				'type' => Controls_Manager::POPOVER_TOGGLE,
				'condition' => [
					'show_badge' => 'yes'
				],
			]
		);

		$this->start_popover();

		$this->add_responsive_control(
			'badge_rotate_z',
			[
				'label' => __( 'Rotate Z', 'skt-addons-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => -180,
						'max' => 180,
					]
				],
				'condition' => [
					'badge_rotate_toggle' => 'yes'
				],
				'selectors' => [
					'{{WRAPPER}} .skt-pricing-table-badge' => '--pricing-table-badge-rotate: {{SIZE}}deg;'
				]
			]
		);

		$this->end_popover();

		$this->end_controls_section();
	}


	private static function get_currency_symbol( $symbol_name ) {
		$symbols = [
			'baht'         => '&#3647;',
			'bdt'          => '&#2547;',
			'dollar'       => '&#36;',
			'euro'         => '&#128;',
			'franc'        => '&#8355;',
			'guilder'      => '&fnof;',
			'indian_rupee' => '&#8377;',
			'pound'        => '&#163;',
			'peso'         => '&#8369;',
			'peseta'       => '&#8359',
			'lira'         => '&#8356;',
			'ruble'        => '&#8381;',
			'shekel'       => '&#8362;',
			'rupee'        => '&#8360;',
			'real'         => 'R$',
			'krona'        => 'kr',
			'won'          => '&#8361;',
			'yen'          => '&#165;',
		];

		return isset( $symbols[ $symbol_name ] ) ? $symbols[ $symbol_name ] : '';
	}

	protected function render() {
		$settings = $this->get_settings_for_display();

		$this->add_render_attribute( 'badge_text', 'class',
			[
				'skt-pricing-table-badge',
				'skt-pricing-table-badge--' . $settings['badge_position']
			]
		);

		$this->add_inline_editing_attributes( 'title', 'basic' );
		$this->add_render_attribute( 'title', 'class', 'skt-pricing-table-title' );

		$this->add_inline_editing_attributes( 'price', 'basic' );
		$this->add_render_attribute( 'price', 'class', 'skt-pricing-table-price-text' );

		$this->add_inline_editing_attributes( 'original_price', 'basic' );
		$this->add_render_attribute( 'original_price', 'class', 'skt-pricing-table-price-text' );

		$this->add_inline_editing_attributes( 'period', 'basic' );
		$this->add_render_attribute( 'period', 'class', 'skt-pricing-table-period' );

		$this->add_inline_editing_attributes( 'features_title', 'basic' );
		$this->add_render_attribute( 'features_title', 'class', 'skt-pricing-table-features-title' );

		$this->add_inline_editing_attributes( 'description', 'intermediate' );
		$this->add_render_attribute( 'description', 'class', 'skt-pricing-table-description' );

		$this->add_inline_editing_attributes( 'button_text', 'none' );
		$this->add_render_attribute( 'button_text', 'class', 'skt-pricing-table-btn' );

		$this->add_inline_editing_attributes( 'footer_description', 'intermediate' );
		$this->add_render_attribute( 'footer_description', 'class', 'skt-pricing-table-footer-description' );

		$this->add_link_attributes( 'button_text', $settings['button_link'] );

		if ( $settings['currency'] === 'custom' ) {
			$currency = $settings['currency_custom'];
		} else {
			$currency = self::get_currency_symbol( $settings['currency'] );
		}
		?>

		<?php if ( $settings['show_badge'] ) : ?>
			<span <?php $this->print_render_attribute_string( 'badge_text' ); ?>><?php echo esc_html( $settings['badge_text'] ); ?></span>
		<?php endif; ?>

		<div class="skt-pricing-table-header">
			<?php if ( 'before_header' == $settings['icon_position'] ) : ?>
				<?php if ( $settings['media_type'] === 'image' && ( $settings['image']['url'] || $settings['image']['id'] ) ) :
					$settings['hover_animation'] = 'disable-animation'; // hack to prevent image hover animation
					?>
					<div class="skt-pricing-table-media skt-pricing-table-media--image">
						<?php echo wp_kses_post(Group_Control_Image_Size::get_attachment_image_html( $settings, 'media_thumbnail', 'image' )); ?>
					</div>
				<?php elseif ( ! empty( $settings['icon'] ) && ! empty( $settings['icon']['value'] ) ) : ?>
					<div class="skt-pricing-table-media skt-pricing-table-media--icon">
						<?php Icons_Manager::render_icon( $settings['icon'], [ 'aria-hidden' => 'true' ] ); ?>
					</div>
				<?php endif; ?>
			<?php endif; ?>
			<?php if ( $settings['title'] ) : ?>
				<h2 <?php $this->print_render_attribute_string( 'title' ); ?>><?php echo wp_kses_post(skt_addons_elementor_kses_basic( $settings['title'] )); ?></h2>
			<?php endif; ?>
			<?php if ( 'after_header' == $settings['icon_position'] ) : ?>
				<?php if ( $settings['media_type'] === 'image' && ( $settings['image']['url'] || $settings['image']['id'] ) ) :
					$settings['hover_animation'] = 'disable-animation'; // hack to prevent image hover animation
					?>
					<div class="skt-pricing-table-media skt-pricing-table-media--image">
						<?php echo wp_kses_post(Group_Control_Image_Size::get_attachment_image_html( $settings, 'media_thumbnail', 'image' )); ?>
					</div>
				<?php elseif ( ! empty( $settings['icon'] ) && ! empty( $settings['icon']['value'] ) ) : ?>
					<div class="skt-pricing-table-media skt-pricing-table-media--icon">
						<?php Icons_Manager::render_icon( $settings['icon'], [ 'aria-hidden' => 'true' ] ); ?>
					</div>
				<?php endif; ?>
			<?php endif; ?>
		</div>

		<?php if ( 'after_header' == $settings['price_position'] ) : ?>
			<div class="skt-pricing-table-price">
				<div class="skt-pricing-table-price-tag">
					<?php if ( $settings['original_price'] ):?>
						<div class="skt-pricing-table-original-price">
							<?php if ( 'left' == $settings['currency_side'] ) : ?>
								<span class="skt-pricing-table-currency"><?php echo esc_html( $currency ); ?></span><span <?php $this->print_render_attribute_string( 'original_price' ); ?>><?php echo wp_kses_post(skt_addons_elementor_kses_basic( $settings['original_price'] )); ?></span>
							<?php else: ?>
								<span <?php $this->print_render_attribute_string( 'original_price' ); ?>><?php echo wp_kses_post(skt_addons_elementor_kses_basic( $settings['original_price'] )); ?></span><span class="skt-pricing-table-currency"><?php echo esc_html( $currency ); ?></span>
							<?php endif; ?>
						</div>
					<?php endif; ?>
					<div class="skt-pricing-table-current-price">
						<?php if ( 'left' == $settings['currency_side'] ) : ?>
							<span class="skt-pricing-table-currency"><?php echo esc_html( $currency ); ?></span><span <?php $this->print_render_attribute_string( 'price' ); ?>><?php echo wp_kses_post(skt_addons_elementor_kses_basic( $settings['price'] )); ?></span>
						<?php else: ?>
							<span <?php $this->print_render_attribute_string( 'price' ); ?>><?php echo wp_kses_post(skt_addons_elementor_kses_basic( $settings['price'] )); ?></span><span class="skt-pricing-table-currency right-pos"><?php echo esc_html( $currency ); ?></span>
						<?php endif; ?>
					</div>
				</div>
				<?php if ( $settings['period'] ) : ?>
					<div <?php $this->print_render_attribute_string( 'period' ); ?>><?php echo wp_kses_post(skt_addons_elementor_kses_basic( $settings['period'] )); ?></div>
				<?php endif; ?>
			</div>
		<?php endif; ?>
		<div class="skt-pricing-table-body">
			<?php if ( $settings['features_title'] ) : ?>
				<h3 <?php $this->print_render_attribute_string( 'features_title' ); ?>><?php echo wp_kses_post(skt_addons_elementor_kses_basic( $settings['features_title'] )); ?></h3>
			<?php endif; ?>

			<?php if ( is_array( $settings['features_list'] )  && 0 != count($settings['features_list']) ) :  ?>
				<ul class="skt-pricing-table-features-list">
					<?php foreach ( $settings['features_list'] as $index => $feature ) :
						$name_key = $this->get_repeater_setting_key( 'text', 'features_list', $index );
						// $this->add_inline_editing_attributes( $name_key, 'intermediate' );
						$this->add_render_attribute( $name_key, 'class', 'skt-pricing-table-feature-text' );
						if ( $feature['tooltip_text'] ) {
							$this->add_render_attribute( $name_key, 'class', 'skt-pricing-table-feature-tooltip' );
						}
						?>
						<li class="<?php echo esc_attr('elementor-repeater-item-' . $feature['_id']); ?>">
							<?php if ( ! empty( $feature['icon'] ) || ! empty( $feature['selected_icon'] ) ) :
								skt_addons_elementor_render_icon( $feature, 'icon', 'selected_icon' );
							endif; ?>
							<div <?php $this->print_render_attribute_string( $name_key ); ?>>
								<?php echo wp_kses_post(skt_addons_elementor_kses_intermediate( $feature['text'] )); ?>
								<?php if ( $feature['tooltip_text'] ) : ?>
									<span class="skt-pricing-table-feature-tooltip-text"><?php echo esc_html( $feature['tooltip_text'] ); ?></span>
								<?php endif; ?>
							</div>
						</li>
					<?php endforeach; ?>
				</ul>
			<?php endif; ?>
		</div>

		<?php if ( $settings['description'] ) : ?>
			<div <?php $this->print_render_attribute_string( 'description' ); ?>><?php echo wp_kses_post(skt_addons_elementor_kses_intermediate( $settings['description'] )); ?></div>
		<?php endif; ?>

		<?php if ( 'before_button' == $settings['price_position'] ) : ?>
			<div class="skt-pricing-table-price">
				<div class="skt-pricing-table-price-tag">
					<?php if($settings['original_price'] ):?>
						<div class="skt-pricing-table-original-price">
							<?php if ( 'left' == $settings['currency_side'] ) : ?>
								<span class="skt-pricing-table-currency"><?php echo esc_html( $currency ); ?></span><span <?php $this->print_render_attribute_string( 'original_price' ); ?>><?php echo wp_kses_post(skt_addons_elementor_kses_basic( $settings['original_price'] )); ?></span>
							<?php else: ?>
								<span <?php $this->print_render_attribute_string( 'original_price' ); ?>><?php echo wp_kses_post(skt_addons_elementor_kses_basic( $settings['original_price'] )); ?></span><span class="skt-pricing-table-currency"><?php echo esc_html( $currency ); ?></span>
							<?php endif; ?>
						</div>
					<?php endif; ?>
					<div class="skt-pricing-table-current-price">
						<?php if ( 'left' == $settings['currency_side'] ) : ?>
							<span class="skt-pricing-table-currency"><?php echo esc_html( $currency ); ?></span><span <?php $this->print_render_attribute_string( 'price' ); ?>><?php echo wp_kses_post(skt_addons_elementor_kses_basic( $settings['price'] )); ?></span>
						<?php else: ?>
							<span <?php $this->print_render_attribute_string( 'price' ); ?>><?php echo wp_kses_post(skt_addons_elementor_kses_basic( $settings['price'] )); ?></span><span class="skt-pricing-table-currency right-pos"><?php echo esc_html( $currency ); ?></span>
						<?php endif; ?>
					</div>
				</div>
				<?php if ( $settings['period'] ) : ?>
					<div <?php $this->print_render_attribute_string( 'period' ); ?>><?php echo wp_kses_post(skt_addons_elementor_kses_basic( $settings['period'] )); ?></div>
				<?php endif; ?>
			</div>
		<?php endif; ?>

		<?php if ( $settings['button_text'] ) : ?>
			<a <?php $this->print_render_attribute_string( 'button_text' ); ?>><?php echo wp_kses_post(skt_addons_elementor_kses_basic( $settings['button_text'] )); ?></a>
		<?php endif; ?>

		<?php if ( $settings['footer_description'] ) : ?>
			<div <?php $this->print_render_attribute_string( 'footer_description' ); ?>><?php echo wp_kses_post(skt_addons_elementor_kses_intermediate( $settings['footer_description'] )); ?></div>
		<?php endif; ?>

		<?php
	}
}