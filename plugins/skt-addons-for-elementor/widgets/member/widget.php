<?php
/**
 * Member widget class
 *
 * @package Skt_Addons_Elementor
 */
namespace Skt_Addons_Elementor\Elementor\Widget;

use Elementor\Group_Control_Text_Shadow;
use Elementor\Repeater;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Utils;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Css_Filter;
use Skt_Addons_Elementor\Elementor\Traits\Button_Renderer;

defined( 'ABSPATH' ) || die();

class Member extends Base {

	use Button_Renderer;

	/**
	 * Get widget title.
	 *
	 * @since 1.0
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( 'Team Member', 'skt-addons-elementor' );
	}

	public function get_custom_help_url() {
		return '#';
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
		return 'skti skti-team-member';
	}

	public function get_keywords() {
		return [ 'team', 'member', 'crew', 'staff', 'person' ];
	}

	public function get_style_depends() {
		return [
			'elementor-icons-fa-solid',
			'elementor-icons-fa-brands',
		];
	}

	protected static function get_profile_names() {
		return [
			'500px'          => __( '500px', 'skt-addons-elementor' ),
			'apple'          => __( 'Apple', 'skt-addons-elementor' ),
			'behance'        => __( 'Behance', 'skt-addons-elementor' ),
			'bitbucket'      => __( 'BitBucket', 'skt-addons-elementor' ),
			'codepen'        => __( 'CodePen', 'skt-addons-elementor' ),
			'delicious'      => __( 'Delicious', 'skt-addons-elementor' ),
			'deviantart'     => __( 'DeviantArt', 'skt-addons-elementor' ),
			'digg'           => __( 'Digg', 'skt-addons-elementor' ),
			'dribbble'       => __( 'Dribbble', 'skt-addons-elementor' ),
			'email'          => __( 'Email', 'skt-addons-elementor' ),
			'facebook'       => __( 'Facebook', 'skt-addons-elementor' ),
			'flickr'         => __( 'Flicker', 'skt-addons-elementor' ),
			'foursquare'     => __( 'FourSquare', 'skt-addons-elementor' ),
			'github'         => __( 'Github', 'skt-addons-elementor' ),
			'houzz'          => __( 'Houzz', 'skt-addons-elementor' ),
			'instagram'      => __( 'Instagram', 'skt-addons-elementor' ),
			'jsfiddle'       => __( 'JS Fiddle', 'skt-addons-elementor' ),
			'linkedin'       => __( 'LinkedIn', 'skt-addons-elementor' ),
			'medium'         => __( 'Medium', 'skt-addons-elementor' ),
			'pinterest'      => __( 'Pinterest', 'skt-addons-elementor' ),
			'product-hunt'   => __( 'Product Hunt', 'skt-addons-elementor' ),
			'reddit'         => __( 'Reddit', 'skt-addons-elementor' ),
			'slideshare'     => __( 'Slide Share', 'skt-addons-elementor' ),
			'snapchat'       => __( 'Snapchat', 'skt-addons-elementor' ),
			'soundcloud'     => __( 'SoundCloud', 'skt-addons-elementor' ),
			'spotify'        => __( 'Spotify', 'skt-addons-elementor' ),
			'stack-overflow' => __( 'StackOverflow', 'skt-addons-elementor' ),
			'tripadvisor'    => __( 'TripAdvisor', 'skt-addons-elementor' ),
			'tumblr'         => __( 'Tumblr', 'skt-addons-elementor' ),
			'twitch'         => __( 'Twitch', 'skt-addons-elementor' ),
			'twitter'        => __( 'Twitter', 'skt-addons-elementor' ),
			'vimeo'          => __( 'Vimeo', 'skt-addons-elementor' ),
			'vk'             => __( 'VK', 'skt-addons-elementor' ),
			'website'        => __( 'Website', 'skt-addons-elementor' ),
			'whatsapp'       => __( 'WhatsApp', 'skt-addons-elementor' ),
			'wordpress'      => __( 'WordPress', 'skt-addons-elementor' ),
			'xing'           => __( 'Xing', 'skt-addons-elementor' ),
			'yelp'           => __( 'Yelp', 'skt-addons-elementor' ),
			'youtube'        => __( 'YouTube', 'skt-addons-elementor' ),
		];
	}

	/**
     * Register widget content controls
     */
	protected function register_content_controls() {
		$this->__info_content_controls();
		$this->__social_content_controls();
		$this->__details_content_controls();
		$this->__lightbox_content_controls();
	}

	protected function __info_content_controls() {

		$this->start_controls_section(
			'_section_info',
			[
				'label' => __( 'Information', 'skt-addons-elementor' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->start_controls_tabs( '_tabs_photo' );

		$this->start_controls_tab(
			'_tab_photo_normal',
			[
				'label' => __( 'Normal', 'skt-addons-elementor' ),
			]
		);

		$this->add_control(
			'image',
			[
				'label' => __( 'Photo', 'skt-addons-elementor' ),
				'show_label' => false,
				'type' => Controls_Manager::MEDIA,
				'default' => [
					'url' => Utils::get_placeholder_image_src(),
				],
				'dynamic' => [
					'active' => true,
				]
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'_tab_photo_hover',
			[
				'label' => __( 'Hover', 'skt-addons-elementor' ),
			]
		);

		$this->add_control(
			'image2',
			[
				'label' => __( 'Photo 2', 'skt-addons-elementor' ),
				'show_label' => false,
				'type' => Controls_Manager::MEDIA,
				'dynamic' => [
					'active' => true,
				],
			]
		);

		$this->add_control(
			'extra_hover_cls',
			[
				'label' => __( 'Extra class added', 'plugin-domain' ),
				'type' => Controls_Manager::HIDDEN,
				'default' => 'on',
				'prefix_class' => 'skt-member-hover-image-',
				'condition' => [
					'image2[url]!' => '',
				]
			]
		);

		$this->end_controls_tab();
		$this->end_controls_tabs();

		$this->add_group_control(
			Group_Control_Image_Size::get_type(),
			[
				'name' => 'thumbnail',
				'default' => 'large',
				'separator' => 'none',
			]
		);

		$this->add_control(
			'title',
			[
				'label' => __( 'Name', 'skt-addons-elementor' ),
				'label_block' => true,
				'type' => Controls_Manager::TEXT,
				'default' => 'SKT Member Name',
				'placeholder' => __( 'Type Member Name', 'skt-addons-elementor' ),
				'separator' => 'before',
				'dynamic' => [
					'active' => true,
				]
			]
		);

		$this->add_control(
			'job_title',
			[
				'label' => __( 'Job Title', 'skt-addons-elementor' ),
				'label_block' => true,
				'type' => Controls_Manager::TEXT,
				'default' => __( 'SKT Officer', 'skt-addons-elementor' ),
				'placeholder' => __( 'Type Member Job Title', 'skt-addons-elementor' ),
				'dynamic' => [
					'active' => true,
				]
			]
		);

		$this->add_control(
			'bio',
			[
				'label' => __( 'Short Bio', 'skt-addons-elementor' ),
				'description' => skt_addons_elementor_get_allowed_html_desc( 'intermediate' ),
				'type' => Controls_Manager::TEXTAREA,
				'placeholder' => __( 'Write something amazing about the skt member', 'skt-addons-elementor' ),
				'rows' => 5,
				'dynamic' => [
					'active' => true,
				]
			]
		);

		$this->add_control(
			'title_tag',
			[
				'label' => __( 'Title HTML Tag', 'skt-addons-elementor' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'h1'  => [
						'title' => __( 'H1', 'skt-addons-elementor' ),
						'icon' => 'eicon-editor-h1'
					],
					'h2'  => [
						'title' => __( 'H2', 'skt-addons-elementor' ),
						'icon' => 'eicon-editor-h2'
					],
					'h3'  => [
						'title' => __( 'H3', 'skt-addons-elementor' ),
						'icon' => 'eicon-editor-h3'
					],
					'h4'  => [
						'title' => __( 'H4', 'skt-addons-elementor' ),
						'icon' => 'eicon-editor-h4'
					],
					'h5'  => [
						'title' => __( 'H5', 'skt-addons-elementor' ),
						'icon' => 'eicon-editor-h5'
					],
					'h6'  => [
						'title' => __( 'H6', 'skt-addons-elementor' ),
						'icon' => 'eicon-editor-h6'
					]
				],
				'default' => 'h2',
				'toggle' => false,
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'align',
			[
				'label' => __( 'Alignment', 'skt-addons-elementor' ),
				'type' => Controls_Manager::CHOOSE,
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
					'justify' => [
						'title' => __( 'Justify', 'skt-addons-elementor' ),
						'icon' => 'eicon-text-align-justify',
					],
				],
				'toggle' => true,
				'selectors' => [
					'{{WRAPPER}}' => 'text-align: {{VALUE}};'
				]
			]
		);

		$this->end_controls_section();
	}

	protected function __social_content_controls() {

		$this->start_controls_section(
			'_section_social',
			[
				'label' => __( 'Social Profiles', 'skt-addons-elementor' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		$repeater = new Repeater();

		$repeater->add_control(
			'name',
			[
				'label' => __( 'Profile Name', 'skt-addons-elementor' ),
				'type' => Controls_Manager::SELECT2,
				'label_block' => true,
				'select2options' => [
					'allowClear' => false,
				],
				'options' => self::get_profile_names()
			]
		);

		$repeater->add_control(
			'link', [
				'label' => __( 'Profile Link', 'skt-addons-elementor' ),
				'placeholder' => __( 'Add your profile link', 'skt-addons-elementor' ),
				'type' => Controls_Manager::URL,
				'label_block' => true,
				'autocomplete' => false,
				'show_external' => false,
				'condition' => [
					'name!' => 'email'
				],
				'dynamic' => [
					'active' => true,
				]
			]
		);

		$repeater->add_control(
			'email', [
				'label' => __( 'Email Address', 'skt-addons-elementor' ),
				'placeholder' => __( 'Add your email address', 'skt-addons-elementor' ),
				'type' => Controls_Manager::TEXT,
				'label_block' => false,
				'input_type' => 'email',
				'condition' => [
					'name' => 'email'
				],
				'dynamic' => [
					'active' => true,
				]
			]
		);

		$repeater->add_control(
			'customize',
			[
				'label' => __( 'Want To Customize?', 'skt-addons-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'Yes', 'skt-addons-elementor' ),
				'label_off' => __( 'No', 'skt-addons-elementor' ),
				'return_value' => 'yes',
				'style_transfer' => true,
			]
		);

		$repeater->start_controls_tabs(
			'_tab_icon_colors',
			[
				'condition' => ['customize' => 'yes']
			]
		);
		$repeater->start_controls_tab(
			'_tab_icon_normal',
			[
				'label' => __( 'Normal', 'skt-addons-elementor' ),
			]
		);

		$repeater->add_control(
			'color',
			[
				'label' => __( 'Text Color', 'skt-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .skt-member-links > {{CURRENT_ITEM}}' => 'color: {{VALUE}}',
				],
				'condition' => ['customize' => 'yes'],
				'style_transfer' => true,
			]
		);

		$repeater->add_control(
			'bg_color',
			[
				'label' => __( 'Background Color', 'skt-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .skt-member-links > {{CURRENT_ITEM}}' => 'background-color: {{VALUE}}',
				],
				'condition' => ['customize' => 'yes'],
				'style_transfer' => true,
			]
		);

		$repeater->end_controls_tab();
		$repeater->start_controls_tab(
			'_tab_icon_hover',
			[
				'label' => __( 'Hover', 'skt-addons-elementor' ),
			]
		);

		$repeater->add_control(
			'hover_color',
			[
				'label' => __( 'Text Color', 'skt-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .skt-member-links > {{CURRENT_ITEM}}:hover, {{WRAPPER}} .skt-member-links > {{CURRENT_ITEM}}:focus' => 'color: {{VALUE}}',
				],
				'condition' => ['customize' => 'yes'],
				'style_transfer' => true,
			]
		);

		$repeater->add_control(
			'hover_bg_color',
			[
				'label' => __( 'Background Color', 'skt-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .skt-member-links > {{CURRENT_ITEM}}:hover, {{WRAPPER}} .skt-member-links > {{CURRENT_ITEM}}:focus' => 'background-color: {{VALUE}}',
				],
				'condition' => ['customize' => 'yes'],
				'style_transfer' => true,
			]
		);

		$repeater->add_control(
			'hover_border_color',
			[
				'label' => __( 'Border Color', 'skt-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .skt-member-links > {{CURRENT_ITEM}}:hover, {{WRAPPER}} .skt-member-links > {{CURRENT_ITEM}}:focus' => 'border-color: {{VALUE}}',
				],
				'condition' => ['customize' => 'yes'],
				'style_transfer' => true,
			]
		);

		$repeater->end_controls_tab();
		$repeater->end_controls_tabs();

		$this->add_control(
			'profiles',
			[
				'show_label' => false,
				'type' => Controls_Manager::REPEATER,
				'fields' => $repeater->get_controls(),
				'title_field' => '<# print(name.slice(0,1).toUpperCase() + name.slice(1)) #>',
				'default' => [
					[
						'link' => ['url' => 'https://instagram.com/'],
						'name' => 'instagram'
					],
					[
						'link' => ['url' => 'https://twitter.com/'],
						'name' => 'twitter'
					],
					[
						'link' => ['url' => 'https://linkedin.com/'],
						'name' => 'linkedin'
					]
				],
			]
		);

		$this->add_control(
			'show_profiles',
			[
				'label' => __( 'Show Profiles', 'skt-addons-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'Show', 'skt-addons-elementor' ),
				'label_off' => __( 'Hide', 'skt-addons-elementor' ),
				'return_value' => 'yes',
				'default' => 'yes',
				'separator' => 'before',
				'style_transfer' => true,
			]
		);

		$this->end_controls_section();
	}

	protected function __details_content_controls() {

		$this->start_controls_section(
			'_section_button',
			[
				'label' => __( 'Details Button', 'skt-addons-elementor' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'show_details_button',
			[
				'label' => __( 'Show Button', 'skt-addons-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'Show', 'skt-addons-elementor' ),
				'label_off' => __( 'Hide', 'skt-addons-elementor' ),
				'return_value' => 'yes',
				'default' => '',
				'style_transfer' => true,
			]
		);

		$this->add_control(
			'show_lightbox',
			[
				'label' => __( 'Show Lightbox', 'skt-addons-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'Show', 'skt-addons-elementor' ),
				'label_off' => __( 'Hide', 'skt-addons-elementor' ),
				'return_value' => 'yes',
				'default' => '',
				'style_transfer' => true,
				'condition' => [
					'show_details_button' => 'yes',
				]
			]
		);

		$this->add_control(
			'button_position',
			[
				'label' => __( 'Position', 'skt-addons-elementor' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'after',
				'style_transfer' => true,
				'options' => [
					'before' => __( 'Before Social Icons', 'skt-addons-elementor' ),
					'after' => __( 'After Social Icons', 'skt-addons-elementor' ),
				],
				'condition' => [
					'show_details_button' => 'yes',
				]
			]
		);

		$this->add_control(
			'button_text',
			[
				'label' => __( 'Text', 'skt-addons-elementor' ),
				'type' => Controls_Manager::TEXT,
				'default' => __( 'Show Details', 'skt-addons-elementor' ),
				'placeholder' => __( 'Type button text here', 'skt-addons-elementor' ),
				'label_block' => true,
				'dynamic' => [
					'active' => true,
				],
				'condition' => [
					'show_details_button' => 'yes',
				]
			]
		);

		$this->add_control(
			'button_link',
			[
				'label' => __( 'Link', 'skt-addons-elementor' ),
				'type' => Controls_Manager::URL,
				'placeholder' => 'https://example.com',
				'dynamic' => [
					'active' => true,
				],
				'default' => [
					'url' => '#',
				],
				'condition' => [
					'show_details_button' => 'yes',
					'show_lightbox!' => 'yes'
				],
			]
		);

		$this->add_control(
			'button_icon',
			[
				'label' => __( 'Icon', 'skt-addons-elementor' ),
				'type' => Controls_Manager::ICONS,
				'label_block' => false,
				'show_label' => true,
				'skin' => 'inline',
				'condition' => [
					'show_details_button' => 'yes',
				]
			]
		);

		$this->add_control(
			'button_icon_position',
			[
				'label' => __( 'Icon Position', 'skt-addons-elementor' ),
				'type' => Controls_Manager::CHOOSE,
				'label_block' => false,
				'options' => [
					'before' => [
						'title' => __( 'Before', 'skt-addons-elementor' ),
						'icon' => 'eicon-h-align-left',
					],
					'after' => [
						'title' => __( 'After', 'skt-addons-elementor' ),
						'icon' => 'eicon-h-align-right',
					],
				],
				'default' => 'after',
				'toggle' => false,
				'style_transfer' => true,
				'condition' => [
					'show_details_button' => 'yes',
					'button_icon[value]!' => ''
				]
			]
		);

		$this->add_control(
			'button_icon_spacing',
			[
				'label' => __( 'Icon Spacing', 'skt-addons-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 10
				],
				'condition' => [
					'show_details_button' => 'yes',
					'button_icon[value]!' => ''
				],
				'selectors' => [
					'{{WRAPPER}} .skt-btn--icon-before .skt-btn-icon' => 'margin-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .skt-btn--icon-after .skt-btn-icon' => 'margin-left: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();
	}

	protected function __lightbox_content_controls() {

		$this->start_controls_section(
			'_section_lightbox',
			[
				'label' => __( 'Lightbox', 'skt-addons-elementor' ),
				'tab' => Controls_Manager::TAB_CONTENT,
				'condition' => [
					'show_details_button' => 'yes',
					'show_lightbox' => 'yes'
				],
			]
		);

		$this->add_control(
			'saved_template_list',
			[
				'label' => __( 'Content Source', 'skt-addons-elementor' ),
				'description' => __( 'Select a saveed section to show in popup window.', 'skt-addons-elementor' ),
				'type' => Controls_Manager::SELECT,
				'options' => $this->get_saved_content( ['page','section'] ),
				'default' => '0',
			]
		);

		$this->add_control(
			'show_lightbox_preview',
			[
				'label' => __( 'Show Lightbox Preview', 'skt-addons-elementor' ),
				'description' => __( 'This option only works on edit mode.', 'skt-addons-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'Show', 'skt-addons-elementor' ),
				'label_off' => __( 'Hide', 'skt-addons-elementor' ),
				'return_value' => 'yes',
				// 'style_transfer' => true,
				'default' => '',

			]
		);

		$this->add_control(
			'close_position',
			[
				'label' => __( 'Close Icon Position', 'skt-addons-elementor' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'top-left' => __( 'Top Left', 'skt-addons-elementor' ),
					'top-right' => __( 'Top Right', 'skt-addons-elementor' ),
				],
				'default' => 'top-right',
				'selectors_dictionary' => [
                    'top-left' => 'top:0; left:0;',
                    'top-right' => 'top:0; right:0;',
                ],
                'selectors' => [
                    '{{WRAPPER}} .skt-member-lightbox.skt-member-lightbox-show .skt-member-lightbox-close' => '{{VALUE}}',
                ],
			]
		);

		$this->end_controls_section();
	}

	/**
     * Register widget style controls
     */
	protected function register_style_controls() {
		$this->__photo_style_controls();
		$this->__body_content_style_controls();
		$this->__social_style_controls();
		$this->__details_style_controls();
		$this->__lightbox_style_controls();
	}

	protected function __photo_style_controls() {

		$this->start_controls_section(
			'_section_style_image',
			[
				'label' => __( 'Photo', 'skt-addons-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'image_width',
			[
				'label' => __( 'Width', 'skt-addons-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%'],
				'range' => [
					'%' => [
						'min' => 20,
						'max' => 100,
					],
					'px' => [
						'min' => 100,
						'max' => 700,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .skt-member-figure' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'image_height',
			[
				'label' => __( 'Height', 'skt-addons-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 100,
						'max' => 700,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .skt-member-figure' => 'height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'image_spacing',
			[
				'label' => __( 'Bottom Spacing', 'skt-addons-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'selectors' => [
					'{{WRAPPER}} .skt-member-figure' => 'margin-bottom: {{SIZE}}{{UNIT}} !important;',
				],
			]
		);

		$this->add_responsive_control(
			'image_padding',
			[
				'label' => __( 'Padding', 'skt-addons-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .skt-member-figure img' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'image_border',
				'selector' => '{{WRAPPER}} .skt-member-figure img'
			]
		);

		$this->add_responsive_control(
			'image_border_radius',
			[
				'label' => __( 'Border Radius', 'skt-addons-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .skt-member-figure img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'image_box_shadow',
				'exclude' => [
					'box_shadow_position',
				],
				'selector' => '{{WRAPPER}} .skt-member-figure img'
			]
		);

		$this->add_control(
			'image_bg_color',
			[
				'label' => __( 'Background Color', 'skt-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .skt-member-figure img' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->start_controls_tabs(
			'_tabs_img_effects',[
				'condition' => [
					'image2[url]' => '',
				]
			]
		 );

		$this->start_controls_tab(
			'_tab_img_effects_normal',
			[
				'label' => __( 'Normal', 'skt-addons-elementor' ),
			]
		);

		$this->add_control(
			'img_opacity',
			[
				'label' => __( 'Opacity', 'skt-addons-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'max' => 1,
						'min' => 0.10,
						'step' => 0.01,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .skt-member-figure img' => 'opacity: {{SIZE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Css_Filter::get_type(),
			[
				'name' => 'img_css_filters',
				'selector' => '{{WRAPPER}} .skt-member-figure img',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'_tab_img_effects_hover',
			[
				'label' => __( 'Hover', 'skt-addons-elementor' ),
			]
		);

		$this->add_control(
			'img_hover_opacity',
			[
				'label' => __( 'Opacity', 'skt-addons-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'max' => 1,
						'min' => 0.10,
						'step' => 0.01,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .skt-member-figure:hover img' => 'opacity: {{SIZE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Css_Filter::get_type(),
			[
				'name' => 'img_hover_css_filters',
				'selector' => '{{WRAPPER}} .skt-member-figure:hover img',
			]
		);

		$this->add_control(
			'img_hover_transition',
			[
				'label' => __( 'Transition Duration', 'skt-addons-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'max' => 3,
						'step' => 0.1,
					],
				],
				'default' => [
					'size' => .2
				],
				'selectors' => [
					'{{WRAPPER}} .skt-member-figure img' => 'transition-duration: {{SIZE}}s;',
				],
			]
		);

		$this->end_controls_tab();
		$this->end_controls_tabs();

		$this->end_controls_section();
	}

	protected function __body_content_style_controls() {

		$this->start_controls_section(
			'_section_style_content',
			[
				'label' => __( 'Name, Job Title & Bio', 'skt-addons-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'content_padding',
			[
				'label' => __( 'Content Padding', 'skt-addons-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .skt-member-body' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'_heading_title',
			[
				'type' => Controls_Manager::HEADING,
				'label' => __( 'Name', 'skt-addons-elementor' ),
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'title_spacing',
			[
				'label' => __( 'Bottom Spacing', 'skt-addons-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'selectors' => [
					'{{WRAPPER}} .skt-member-name' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'title_color',
			[
				'label' => __( 'Text Color', 'skt-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .skt-member-name' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'title_typography',
				'selector' => '{{WRAPPER}} .skt-member-name',
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_SECONDARY,
			],
			]
		);

		$this->add_group_control(
			Group_Control_Text_Shadow::get_type(),
			[
				'name' => 'title_text_shadow',
				'selector' => '{{WRAPPER}} .skt-member-name',
			]
		);

		$this->add_control(
			'_heading_job_title',
			[
				'type' => Controls_Manager::HEADING,
				'label' => __( 'Job Title', 'skt-addons-elementor' ),
				'separator' => 'before'
			]
		);

		$this->add_responsive_control(
			'job_title_spacing',
			[
				'label' => __( 'Bottom Spacing', 'skt-addons-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'selectors' => [
					'{{WRAPPER}} .skt-member-position' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'job_title_color',
			[
				'label' => __( 'Text Color', 'skt-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .skt-member-position' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'job_title_typography',
				'selector' => '{{WRAPPER}} .skt-member-position',
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_TEXT,
			],
			]
		);

		$this->add_group_control(
			Group_Control_Text_Shadow::get_type(),
			[
				'name' => 'job_title_text_shadow',
				'selector' => '{{WRAPPER}} .skt-member-position',
			]
		);

		$this->add_control(
			'_heading_bio',
			[
				'type' => Controls_Manager::HEADING,
				'label' => __( 'Short Bio', 'skt-addons-elementor' ),
				'separator' => 'before'
			]
		);

		$this->add_responsive_control(
			'bio_spacing',
			[
				'label' => __( 'Bottom Spacing', 'skt-addons-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'selectors' => [
					'{{WRAPPER}} .skt-member-bio' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'bio_color',
			[
				'label' => __( 'Text Color', 'skt-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .skt-member-bio' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'bio_typography',
				'selector' => '{{WRAPPER}} .skt-member-bio',
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_TEXT,
			],
			]
		);

		$this->add_group_control(
			Group_Control_Text_Shadow::get_type(),
			[
				'name' => 'bio_text_shadow',
				'selector' => '{{WRAPPER}} .skt-member-bio',
			]
		);

		$this->end_controls_section();
	}

	protected function __social_style_controls() {

		$this->start_controls_section(
			'_section_style_social',
			[
				'label' => __( 'Social Icons', 'skt-addons-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'links_spacing',
			[
				'label' => __( 'Right Spacing', 'skt-addons-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'selectors' => [
					'{{WRAPPER}} .skt-member-links > a:not(:last-child)' => 'margin-right: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'links_padding',
			[
				'label' => __( 'Padding', 'skt-addons-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'selectors' => [
					'{{WRAPPER}} .skt-member-links > a' => 'padding: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'links_icon_size',
			[
				'label' => __( 'Icon Size', 'skt-addons-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'selectors' => [
					'{{WRAPPER}} .skt-member-links > a' => 'font-size: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'links_border',
				'selector' => '{{WRAPPER}} .skt-member-links > a'
			]
		);

		$this->add_responsive_control(
			'links_border_radius',
			[
				'label' => __( 'Border Radius', 'skt-addons-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .skt-member-links > a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->start_controls_tabs( '_tab_links_colors' );
		$this->start_controls_tab(
			'_tab_links_normal',
			[
				'label' => __( 'Normal', 'skt-addons-elementor' ),
			]
		);

		$this->add_control(
			'links_color',
			[
				'label' => __( 'Text Color', 'skt-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .skt-member-links > a' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'links_bg_color',
			[
				'label' => __( 'Background Color', 'skt-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .skt-member-links > a' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();
		$this->start_controls_tab(
			'_tab_links_hover',
			[
				'label' => __( 'Hover', 'skt-addons-elementor' ),
			]
		);

		$this->add_control(
			'links_hover_color',
			[
				'label' => __( 'Text Color', 'skt-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .skt-member-links > a:hover, {{WRAPPER}} .skt-member-links > a:focus' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'links_hover_bg_color',
			[
				'label' => __( 'Background Color', 'skt-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .skt-member-links > a:hover, {{WRAPPER}} .skt-member-links > a:focus' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'links_hover_border_color',
			[
				'label' => __( 'Border Color', 'skt-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .skt-member-links > a:hover, {{WRAPPER}} .skt-member-links > a:focus' => 'border-color: {{VALUE}};',
				],
				'condition' => [
					'links_border_border!' => '',
				]
			]
		);

		$this->end_controls_tab();
		$this->end_controls_tabs();

		$this->end_controls_section();
	}

	protected function __details_style_controls() {

		$this->start_controls_section(
			'_section_style_button',
			[
				'label' => __( 'Details Button', 'skt-addons-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'button_margin',
			[
				'label' => __( 'Margin', 'skt-addons-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .skt-btn' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
					'{{WRAPPER}} .skt-btn' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'button_typography',
				'selector' => '{{WRAPPER}} .skt-btn',
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_ACCENT,
			],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'button_border',
				'selector' => '{{WRAPPER}} .skt-btn',
			]
		);

		$this->add_control(
			'button_border_radius',
			[
				'label' => __( 'Border Radius', 'skt-addons-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .skt-btn' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'button_box_shadow',
				'selector' => '{{WRAPPER}} .skt-btn',
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
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .skt-btn' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'button_bg_color',
			[
				'label' => __( 'Background Color', 'skt-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .skt-btn' => 'background-color: {{VALUE}};',
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
					'{{WRAPPER}} .skt-btn:hover, {{WRAPPER}} .skt-btn:focus' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'button_hover_bg_color',
			[
				'label' => __( 'Background Color', 'skt-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .skt-btn:hover, {{WRAPPER}} .skt-btn:focus' => 'background-color: {{VALUE}};',
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
					'{{WRAPPER}} .skt-btn:hover, {{WRAPPER}} .skt-btn:focus' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();
		$this->end_controls_tabs();

		$this->end_controls_section();
	}

	protected function __lightbox_style_controls() {

		$this->start_controls_section(
			'_section_style_lightbox',
			[
				'label' => __( 'LightBox', 'skt-addons-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'show_details_button' => 'yes',
					'show_lightbox' => 'yes'
				],
			]
		);

		$this->add_responsive_control(
			'lightbox_padding',
			[
				'label' => __( 'Padding', 'skt-addons-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .skt-member-lightbox.skt-member-lightbox-show' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'lightbox_background',
				'selector' => '{{WRAPPER}} .skt-member-lightbox.skt-member-lightbox-show',
			]
		);

		$this->add_control(
            'close_button_heading',
            [
                'label' => __( 'Close Button', 'skt-addons-elementor' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'close_button_border',
				'selector' => '{{WRAPPER}} .skt-member-lightbox-close',
			]
		);

		$this->add_control(
			'close_button_border_radius',
			[
				'label' => __( 'Border Radius', 'skt-addons-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .skt-member-lightbox-close' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'close_button_box_shadow',
				'selector' => '{{WRAPPER}} .skt-member-lightbox-close',
			]
		);

		$this->add_responsive_control(
            'close_icon_size',
            [
                'label' => __( 'Size', 'skt-addons-elementor' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ],
                'range' => [
                    'px' => [
                        'min' => 2,
                        'max' => 200,
                    ]
                ],
                'selectors' => [
                    '{{WRAPPER}} .skt-member-lightbox-close' => 'font-size: {{SIZE}}{{UNIT}};',
                ],
            ]
		);

		$this->start_controls_tabs( '_tabs_close_button' );
		$this->start_controls_tab(
			'_tab_close_button_normal',
			[
				'label' => __( 'Normal', 'skt-addons-elementor' )
			]
		);

		$this->add_control(
			'close_button_color',
			[
				'label' => __( 'Color', 'skt-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .skt-member-lightbox-close' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'close_button_bg_color',
			[
				'label' => __( 'Background Color', 'skt-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .skt-member-lightbox-close' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'_tab_close_button_hover',
			[
				'label' => __( 'Hover', 'skt-addons-elementor' ),
			]
		);

		$this->add_control(
			'close_button_hover_color',
			[
				'label' => __( 'Color', 'skt-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .skt-member-lightbox-close:hover' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'close_button_hover_bg_color',
			[
				'label' => __( 'Background Color', 'skt-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .skt-member-lightbox-close:hover' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'close_button_hover_border_color',
			[
				'label' => __( 'Border Color', 'skt-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'condition' => [
					'close_button_border_border!' => '',
				],
				'selectors' => [
					'{{WRAPPER}} ..skt-member-lightbox-close:hover' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();
		$this->end_controls_tabs();

		$this->end_controls_section();
	}

	protected function get_post_template( $term = 'page' ) {
		$posts = get_posts(
			[
				'post_type'      => 'elementor_library',
				'orderby'        => 'title',
				'order'          => 'ASC',
				'posts_per_page' => '-1',
				'tax_query'      => [
					[
						'taxonomy' => 'elementor_library_type',
						'field'    => 'slug',
						'terms'    => $term,
					],
				],
			]
		);

		$templates = [];
		foreach ( $posts as $post ) {
			$templates[] = [
				'id'   => $post->ID,
				'name' => $post->post_title,
			];
		}
		return $templates;
	}

	protected function get_saved_content( $term = 'section' ) {
		$saved_contents = $this->get_post_template( $term );

		if ( count( $saved_contents ) > 0 ) {
			$options['0'] = __( 'None', 'skt-addons-elementor' );
			foreach ( $saved_contents as $saved_content ) {
				$content_id             = $saved_content['id'];
				$options[ $content_id ] = $saved_content['name'];
			}
		} else {
			$options['no_template'] = __( 'Nothing Found', 'skt-addons-elementor' );
		}
		return $options;
	}

	protected function render() {
		$settings = $this->get_settings_for_display();

		$button_position = ! empty( $settings['button_position'] ) ? $settings['button_position'] : 'after';

		$show_button = false;
		if ( ! empty( $settings['show_details_button'] ) && $settings['show_details_button'] === 'yes'  ) {
			$show_button = true;
		}

		$this->add_inline_editing_attributes( 'title', 'basic' );
		$this->add_render_attribute( 'title', 'class', 'skt-member-name' );

		$this->add_inline_editing_attributes( 'job_title', 'basic' );
		$this->add_render_attribute( 'job_title', 'class', 'skt-member-position' );

		$this->add_inline_editing_attributes( 'bio', 'intermediate' );
		$this->add_render_attribute( 'bio', 'class', 'skt-member-bio' );
		?>

		<?php if ( $settings['image']['url'] || $settings['image']['id'] ) :
			$settings['hover_animation'] = 'disable-animation'; // hack to prevent image hover animation
			?>
			<figure class="skt-member-figure">
				<?php
					echo wp_kses_post(Group_Control_Image_Size::get_attachment_image_html( $settings, 'thumbnail', 'image' ));
					if($settings['image2']['url'] || $settings['image2']['id'] )
					echo wp_kses_post(Group_Control_Image_Size::get_attachment_image_html( $settings, 'thumbnail', 'image2' ));
				?>
			</figure>
		<?php endif; ?>

		<div class="skt-member-body">
			<?php if ( $settings['title'] ) :
				printf( '<%1$s %2$s>%3$s</%1$s>',
					skt_addons_elementor_escape_tags( $settings['title_tag'], 'h2' ),
					$this->get_render_attribute_string( 'title' ),
					skt_addons_elementor_kses_basic( $settings['title'] )
				);
			endif; ?>

			<?php if ( $settings['job_title' ] ) : ?>
				<div <?php $this->print_render_attribute_string( 'job_title' ); ?>><?php echo wp_kses_post(skt_addons_elementor_kses_basic( $settings['job_title' ] )); ?></div>
			<?php endif; ?>

			<?php if ( $settings['bio'] ) : ?>
				<div <?php $this->print_render_attribute_string( 'bio' ); ?>>
					<p><?php echo wp_kses_post(skt_addons_elementor_kses_intermediate( $settings['bio'] )); ?></p>
				</div>
			<?php endif; ?>

			<?php
			if ( $show_button && $button_position === 'before' ) {
				$this->render_icon_button( [ 'new_icon' => 'button_icon', 'old_icon' => '' ] );
			}
			?>

			<?php if ( $settings['show_profiles' ] && is_array( $settings['profiles' ] ) ) : ?>
				<div class="skt-member-links">
					<?php
					foreach ( $settings['profiles'] as $profile ) :
						$icon = $profile['name'];
						$url = $profile['link']['url'];

						if ( $profile['name'] === 'website' ) {
							$icon = 'globe far';
						} elseif ( $profile['name'] === 'email' ) {
							$icon = 'envelope far';
							$url = 'mailto:' . antispambot( $profile['email'] );
						} else {
							$icon .= ' fab';
						}

						printf( '<a target="_blank" rel="noopener" href="%s" class="elementor-repeater-item-%s"><i class="fa fa-%s" aria-hidden="true"></i></a>',
							$url,
							esc_attr( $profile['_id'] ),
							esc_attr( $icon )
						);
					endforeach; ?>
				</div>
			<?php endif; ?>

			<?php
			if ( $show_button && $button_position === 'after' ) {
				$this->render_icon_button( [ 'new_icon' => 'button_icon', 'old_icon' => '' ] );
			}
			?>
		</div>
		<?php
		// render lightbox
		$this->render_lightbox();
	}

	protected function render_lightbox() {
		$settings = $this->get_settings_for_display();
		$template = false;
		if ( ! empty( $settings['saved_template_list'] ) && '0' != $settings['saved_template_list'] && 'no_template' != $settings['saved_template_list'] ) {
			$template = true;
		}
		if ( $settings['show_lightbox'] && 'yes' === $settings['show_lightbox'] && $template ) :
			$this->add_render_attribute( 'lightbox', 'class', 'skt-member-lightbox' );
			if ( $settings['show_lightbox_preview'] == 'yes' && skt_addons_elementor()->editor->is_edit_mode() ) {
				$this->add_render_attribute( 'lightbox', 'class', 'skt-member-lightbox-show' );
			}
				?>
				<div <?php $this->print_render_attribute_string( 'lightbox' ); ?>>
					<div class="skt-member-lightbox-close"><i aria-hidden="true" class="eicon-editor-close"></i></div>
					<div class="skt-member-lightbox-inner">
						<?php echo \Elementor\Plugin::$instance->frontend->get_builder_content_for_display( $settings['saved_template_list'] ); ?>
					</div>
				</div>
			<?php
		endif;
	}
}