<?php
/**
 * Class: Soft_Template_Social_Share
 * Name: Post Share
 * Slug: soft-template-social-share
 */

namespace Elementor;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Repeater;
use Elementor\Widget_Base;
use Elementor\Utils;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Soft_Template_Social_Share extends SoftTemplate_Base {
    public function get_name() {
		return 'soft-template-social-share';
	}

	public function get_title() {
		return esc_html__( 'Post Share', 'soft-template-core' );
	}

    public function get_icon() {
		return 'eicon-share';
	}

    public function get_jet_help_url() {
		return '#';
	}

    public function get_script_depends() {
		return array( 'soft-template-social-share');
	}

    public function get_categories() {
		return array( 'soft-template-core' );
	}
	/**
	 * Retrive network class directory.
	 *
	 * @var $settings array.
	 */
	private static $uael_networks_class_dictionary = array(
		'pocket' => 'fab fa-get-pocket',
		'email'  => 'fas fa-envelope',
	);

	/**
	 * Retrive network class.
	 *
	 * @param array $network_name returns settings.
	 */
	private static function uael_get_network_class( $network_name ) {

		$prefix = 'fa ';

		if ( Icons_Manager::is_migration_allowed() ) {

			if ( isset( self::$networks_icon_mapping[ $network_name ] ) ) {

				return self::$networks_icon_mapping[ $network_name ];
			}

			$prefix = 'fab ';
		}
		if ( isset( self::$uael_networks_class_dictionary[ $network_name ] ) ) {

			return self::$uael_networks_class_dictionary[ $network_name ];
		}

		return $prefix . 'fa-' . $network_name;
	}

	/**
	 * Load the related styles.
	 *
	 * @since 1.30.0.
	 *
	 * @access public
	 *
	 * @return array Font styles.
	 */
	public function get_style_depends() {
		if ( Icons_Manager::is_migration_allowed() ) {
			return array(
				'elementor-icons-fa-solid',
				'elementor-icons-fa-brands',
			);
		}
		return array();
	}

	/**
	 * Total Share count.
	 *
	 * @var $total_share_count.
	 */
	public $total_share_count = 0;

    protected function register_controls() {
        $this->register_general_content_controls();
        $this->register_count_content_controls();
        $this->widget_style();
    }

	protected function register_general_content_controls() {
		$this->start_controls_section(
			'section_buttons_content',
			array(
				'label' => __( 'Social Share', 'soft-template-core' ),
			)
		);
		$repeater = new Repeater();

		$repeater->add_control(
			'text',
			array(
				'label'       => __( 'Select Network', 'soft-template-core' ),
				'type'        => Controls_Manager::SELECT,
				'label_block' => false,
				'options'     => array(
					'facebook'      => 'Facebook',
					'twitter'       => 'Twitter',
					'linkedin'      => 'LinkedIn',
					'pinterest'     => 'Pinterest',
					'reddit'        => 'Reddit',
					'vk'            => 'VK',
					'odnoklassniki' => 'OK', // odnoklassniki.
					'tumblr'        => 'Tumblr',
					'delicious'     => 'Delicious',
					'digg'          => 'Digg',
					'skype'         => 'Skype',
					'stumbleupon'   => 'StumbleUpon',
					'telegram'      => 'Telegram',
					'pocket'        => 'Pocket',
					'xing'          => 'XING',
					'email'         => 'Email',
					'print'         => 'Print',
					'whatsapp'      => 'WhatsApp',
					'buffer'        => 'Buffer',
				),
				'default'     => 'facebook',
			)
		);

		$repeater->add_control(
			'pin_error_msg',
			array(
				'type'            => Controls_Manager::RAW_HTML,
				/* translators: %s Pinterest share link */
				'raw'             => sprintf( __( 'To share the post on Pinterest you must assign a featured image for the Post/Page.', 'soft-template-core' ) ),
				'content_classes' => 'elementor-panel-alert elementor-panel-alert-warning',
				'condition'       => array(
					'text' => 'pinterest',
				),
			)
		);

		$repeater->add_control(
			'Custom_text',
			array(
				'label'   => __( 'Custom Label', 'soft-template-core' ),
				'type'    => Controls_Manager::TEXT,
				'dynamic' => array(
					'active' => true,
				),
			)
		);

		$this->add_control(
			'social_icon_list',
			array(
				'label'       => __( 'Social Icons', 'soft-template-core' ),
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $repeater->get_controls(),
				'default'     => array(
					array(
						'text' => 'facebook',
					),
					array(
						'text' => 'twitter',
					),
					array(
						'text' => 'linkedin',
					),
				),
				'title_field' => '<# 
				var networksClassDictionary = {
					pocket: "fab fa-get-pocket",
					email: "fas fa-envelope",
					print: "fa fa-print"
				}; 
				var networkClass = networksClassDictionary[text] || "fa fa-" + text;
			    #>
			    <i class="{{{ networkClass }}}" aria-hidden="true"></i>{{{text.charAt(0).toUpperCase() + text.slice(1)}}}',
				'render_type' => 'template',
			)
		);

		$this->add_control(
			'view',
			array(
				'label'        => __( 'Style', 'soft-template-core' ),
				'type'         => Controls_Manager::SELECT,
				'label_block'  => false,
				'options'      => array(
					'icon-text' => __( 'Icon & Text', 'soft-template-core' ),
					'icon'      => __( 'Icon', 'soft-template-core' ),
					'text'      => __( 'Text', 'soft-template-core' ),
				),
				'default'      => 'icon-text',
				'separator'    => 'before',
				'prefix_class' => 'uael-share-buttons--view-',
				'render_type'  => 'template',
			)
		);

		$this->add_control(
			'icon_align',
			array(
				'label'     => __( 'Icon Position', 'soft-template-core' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'left',
				'options'   => array(
					'left'  => __( 'Before', 'soft-template-core' ),
					'right' => __( 'After', 'soft-template-core' ),
				),
				'condition' => array(
					'view' => 'icon-text',
				),
			)
		);

		$this->add_control(
			'skin',
			array(
				'label'   => __( 'Skin', 'soft-template-core' ),
				'type'    => Controls_Manager::SELECT,
				'options' => array(
					'gradient' => __( 'Gradient', 'soft-template-core' ),
					'flat'     => __( 'Flat', 'soft-template-core' ),
					'framed'   => __( 'Framed', 'soft-template-core' ),
					'minimal'  => __( 'Minimal', 'soft-template-core' ),
					'boxed'    => __( 'Boxed', 'soft-template-core' ),
				),
				'default' => 'gradient',
			)
		);

		$this->add_control(
			'shape',
			array(
				'label'   => __( 'Shape', 'soft-template-core' ),
				'type'    => Controls_Manager::SELECT,
				'options' => array(
					'square'  => __( 'Square', 'soft-template-core' ),
					'rounded' => __( 'Rounded', 'soft-template-core' ),
					'circle'  => __( 'Circle', 'soft-template-core' ),
				),
				'default' => 'square',
			)
		);

		$this->add_control(
			'display_position',
			array(
				'label'        => __( 'Position', 'soft-template-core' ),
				'type'         => Controls_Manager::SELECT,
				'options'      => array(
					'inline'   => __( 'Inline', 'soft-template-core' ),
					'floating' => __( 'Floating', 'soft-template-core' ),
				),
				'default'      => 'inline',
				'prefix_class' => 'uael-stylex-',
				'render_type'  => 'template',
				'separator'    => 'before',
			)
		);

		$this->add_responsive_control(
			'columns',
			array(
				'label'        => __( 'Columns', 'soft-template-core' ),
				'type'         => Controls_Manager::SELECT,
				'default'      => '0',
				'options'      => array(
					'0' => 'Auto',
					'1' => '1',
					'2' => '2',
					'3' => '3',
					'4' => '4',
					'5' => '5',
					'6' => '6',
				),
				'prefix_class' => 'elementor-grid%s-',
				'condition'    => array(
					'display_position' => 'inline',
				),
				'render_type'  => 'template',
			)
		);

		$this->add_responsive_control(
			'alignment',
			array(
				'label'        => __( 'Alignment', 'soft-template-core' ),
				'type'         => Controls_Manager::CHOOSE,
				'options'      => array(
					'left'   => array(
						'title' => __( 'Left', 'soft-template-core' ),
						'icon'  => 'eicon-text-align-left',
					),
					'center' => array(
						'title' => __( 'Center', 'soft-template-core' ),
						'icon'  => 'eicon-text-align-center',
					),
					'right'  => array(
						'title' => __( 'Right', 'soft-template-core' ),
						'icon'  => 'eicon-text-align-right',
					),
				),
				'prefix_class' => 'elementor-share-buttons%s--align-',
				'condition'    => array(
					'columns'          => '0',
					'display_position' => 'inline',
				),
			)
		);

		$this->add_control(
			'display_floating_align',
			array(
				'label'        => __( 'Alignment', 'soft-template-core' ),
				'type'         => Controls_Manager::CHOOSE,
				'default'      => 'left',
				'options'      => array(
					'left'  => array(
						'title' => __( 'Left', 'soft-template-core' ),
						'icon'  => 'fa fa-align-left',
					),
					'right' => array(
						'title' => __( 'Right', 'soft-template-core' ),
						'icon'  => 'fa fa-align-right',
					),
				),
				'toggle'       => false,
				'label_block'  => false,
				'render_type'  => 'template',
				'prefix_class' => 'uael-floating-align-',
				'condition'    => array(
					'display_position' => 'floating',
				),
			)
		);

		$this->add_responsive_control(
			'display_floating_position',
			array(
				'label'          => __( 'Vertical Position', 'soft-template-core' ),
				'type'           => Controls_Manager::SLIDER,
				'size_units'     => '%',
				'default'        => array(
					'size' => '25',
					'unit' => '%',
				),
				'tablet_default' => array(
					'size' => '25',
					'unit' => '%',
				),
				'mobile_default' => array(
					'size' => '25',
					'unit' => '%',
				),
				'range'          => array(
					'%' => array(
						'min' => 0,
						'max' => 80,
					),
				),
				'selectors'      => array(
					'{{WRAPPER}} .uael-style-floating .elementor-grid' => 'top: {{SIZE}}{{UNIT}};',
				),
				'condition'      => array(
					'display_position' => 'floating',
				),
			)
		);

		$this->add_responsive_control(
			'display_floating_horizontal_position',
			array(
				'label'          => __( 'Horizontal Position', 'soft-template-core' ),
				'type'           => Controls_Manager::SLIDER,
				'size_units'     => '%',
				'default'        => array(
					'size' => '0',
					'unit' => '%',
				),
				'tablet_default' => array(
					'size' => '0',
					'unit' => '%',
				),
				'mobile_default' => array(
					'size' => '0',
					'unit' => '%',
				),
				'range'          => array(
					'%' => array(
						'min' => 0,
						'max' => 50,
					),
				),
				'selectors'      => array(
					'{{WRAPPER}} .uael-style-floating .elementor-grid.uael-floating-align-right' => 'right: {{SIZE}}{{UNIT}}; left: unset;',
					'{{WRAPPER}} .uael-style-floating .elementor-grid.uael-floating-align-left' => 'left: {{SIZE}}{{UNIT}}; right: unset;',
				),
				'condition'      => array(
					'display_position' => 'floating',
				),
			)
		);

		$this->add_control(
			'share_url_type',
			array(
				'label'     => __( 'Target URL', 'soft-template-core' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => array(
					'current_page' => __( 'Current Page', 'soft-template-core' ),
					'custom'       => __( 'Custom', 'soft-template-core' ),
				),
				'default'   => 'current_page',
				'separator' => 'before',
			)
		);

		$this->add_control(
			'share_url',
			array(
				'label'              => __( 'Link', 'soft-template-core' ),
				'type'               => Controls_Manager::URL,
				'show_external'      => false,
				'placeholder'        => __( 'https://your-link.com', 'soft-template-core' ),
				'condition'          => array(
					'share_url_type' => 'custom',
				),
				'show_label'         => false,
				'frontend_available' => true,
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_buttons_style',
			array(
				'label' => __( 'Social Share', 'soft-template-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_responsive_control(
			'column_gap',
			array(
				'label'          => __( 'Columns Gap', 'soft-template-core' ),
				'type'           => Controls_Manager::SLIDER,
				'range'          => array(
					'em' => array(
						'min'  => 0.5,
						'max'  => 4,
						'step' => 0.1,
					),
					'px' => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'default'        => array(
					'size' => 10,
					'unit' => 'px',
				),
				'tablet_default' => array(
					'size' => 10,
					'unit' => 'px',
				),
				'mobile_default' => array(
					'size' => 10,
					'unit' => 'px',
				),
				'size_units'     => array( 'px', 'em' ),
				'selectors'      => array(
					'{{WRAPPER}}:not(.elementor-grid-0) .elementor-grid' => 'grid-column-gap: {{SIZE}}{{UNIT}}',
					'{{WRAPPER}}.elementor-grid-0 .uael-share-btn' => 'margin-right: calc({{SIZE}}{{UNIT}} / 2); margin-left: calc({{SIZE}}{{UNIT}} / 2)',
					'{{WRAPPER}}.elementor-grid-0 .uael-share-btn:last-child' => 'margin-left: calc({{SIZE}}{{UNIT}} / 2)',
					'(tablet) {{WRAPPER}}.elementor-grid-tablet-0 .uael-share-btn' => 'margin-right: calc({{SIZE}}{{UNIT}} / 2); margin-left: calc({{SIZE}}{{UNIT}} / 2)',
					'(tablet) {{WRAPPER}}.elementor-grid-tablet-0 .uael-share-btn:last-child' => 'margin-left: calc({{SIZE}}{{UNIT}} / 2)',
					'(mobile) {{WRAPPER}}.elementor-grid-mobile-0 .uael-share-btn' => 'margin-right: calc({{SIZE}}{{UNIT}} / 2); margin-left: calc({{SIZE}}{{UNIT}} / 2)',
					'(mobile) {{WRAPPER}}.elementor-grid-mobile-0 .uael-share-btn:last-child' => 'margin-left: calc({{SIZE}}{{UNIT}} / 2)',
					'{{WRAPPER}}.elementor-grid-0 .elementor-grid' => 'margin-right: calc(-{{SIZE}}{{UNIT}} / 2); margin-left: calc(-{{SIZE}}{{UNIT}} / 2)',
					'{{WRAPPER}}.elementor-grid-0 .elementor-grid:last-child' => 'margin-left: calc(-{{SIZE}}{{UNIT}} / 2)',
					'(tablet) {{WRAPPER}}.elementor-grid-tablet-0 .elementor-grid' => 'margin-right: calc(-{{SIZE}}{{UNIT}} / 2); margin-left: calc(-{{SIZE}}{{UNIT}} / 2)',
					'(tablet) {{WRAPPER}}.elementor-grid-tablet-0 .elementor-grid:last-child' => 'margin-left: calc(-{{SIZE}}{{UNIT}} / 2)',
					'(mobile) {{WRAPPER}}.elementor-grid-mobile-0 .elementor-grid' => 'margin-right: calc(-{{SIZE}}{{UNIT}} / 2); margin-left: calc(-{{SIZE}}{{UNIT}} / 2)',
					'(mobile) {{WRAPPER}}.elementor-grid-mobile-0 .elementor-grid:last-child' => 'margin-left: calc(-{{SIZE}}{{UNIT}} / 2)',
				),
				'condition'      => array(
					'display_position' => 'inline',
				),
			)
		);

		$this->add_responsive_control(
			'row_gap',
			array(
				'label'          => __( 'Rows Gap', 'soft-template-core' ),
				'type'           => Controls_Manager::SLIDER,
				'range'          => array(
					'em' => array(
						'min'  => 0.5,
						'max'  => 4,
						'step' => 0.1,
					),
					'px' => array(
						'min' => 0,
						'max' => 50,
					),
				),
				'default'        => array(
					'size' => 0,
					'unit' => 'px',
				),
				'tablet_default' => array(
					'size' => 0,
					'unit' => 'px',
				),
				'mobile_default' => array(
					'size' => 10,
					'unit' => 'px',
				),
				'size_units'     => array( 'px', 'em' ),
				'selectors'      => array(
					'{{WRAPPER}}:not(.elementor-grid-0) .elementor-grid' => 'grid-row-gap: {{SIZE}}{{UNIT}}',
					'{{WRAPPER}}.elementor-grid-0 .uael-share-btn' => 'margin-bottom: {{SIZE}}{{UNIT}}',
					'{{WRAPPER}}.uael-stylex-floating .elementor-grid .uael-share-btn' => 'margin-bottom: {{SIZE}}{{UNIT}}',
					'(tablet) {{WRAPPER}}.elementor-grid-tablet-0 .uael-share-btn' => 'margin-bottom: {{SIZE}}{{UNIT}}',
					'(mobile) {{WRAPPER}}.elementor-grid-mobile-0 .uael-share-btn' => 'margin-bottom: {{SIZE}}{{UNIT}}',
				),
				'condition'      => array(
					'display_position' => array( 'inline', 'floating' ),
				),
			)
		);

		$this->add_responsive_control(
			'button_size',
			array(
				'label'          => __( 'Button Size', 'soft-template-core' ),
				'type'           => Controls_Manager::SLIDER,
				'range'          => array(
					'px' => array(
						'min'  => 0.5,
						'max'  => 2,
						'step' => 0.05,
					),
				),
				'default'        => array(
					'unit' => 'px',
				),
				'tablet_default' => array(
					'unit' => 'px',
				),
				'mobile_default' => array(
					'unit' => 'px',
				),
				'size_units'     => array( 'em', 'px' ),
				'selectors'      => array(
					'{{WRAPPER}} .uael-share-btn'       => 'font-size: calc({{SIZE}}{{UNIT}} * 10);',
					'{{WRAPPER}} .uael-total-share-btn' => 'font-size: calc({{SIZE}}{{UNIT}} * 10);',
				),
				'condition'      => array(
					'display_position' => array( 'inline', 'floating' ),
				),
			)
		);

		$this->add_responsive_control(
			'button_height',
			array(
				'label'          => __( 'Button Height', 'soft-template-core' ),
				'type'           => Controls_Manager::SLIDER,
				'range'          => array(
					'em' => array(
						'min'  => 1,
						'max'  => 7,
						'step' => 0.1,
					),
					'px' => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'default'        => array(
					'unit' => 'em',
				),
				'tablet_default' => array(
					'unit' => 'em',
				),
				'mobile_default' => array(
					'unit' => 'em',
				),
				'size_units'     => array( 'em', 'px' ),
				'selectors'      => array(
					'{{WRAPPER}} .uael-share-btn'       => 'height: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .uael-share-btn__icon' => 'height: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .uael-total-share-btn' => 'height: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'icon_size',
			array(
				'label'          => __( 'Icon Size', 'soft-template-core' ),
				'type'           => Controls_Manager::SLIDER,
				'range'          => array(
					'em' => array(
						'min'  => 0.5,
						'max'  => 4,
						'step' => 0.1,
					),
					'px' => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'default'        => array(
					'unit' => 'em',
				),
				'tablet_default' => array(
					'unit' => 'em',
				),
				'mobile_default' => array(
					'unit' => 'em',
				),
				'size_units'     => array( 'em', 'px' ),
				'selectors'      => array(
					'{{WRAPPER}} .uael-share-btn__icon i' => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .uael-share-btn__icon i' => 'font-size: {{SIZE}}{{UNIT}};',
				),
				'condition'      => array(
					'view!'            => array( 'text' ),
					'display_position' => array( 'floating', 'inline' ),
				),
			)
		);

		$this->add_responsive_control(
			'border_size',
			array(
				'label'      => __( 'Border Size', 'soft-template-core' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'em' ),
				'default'    => array(
					'size' => 2,
				),
				'range'      => array(
					'px' => array(
						'min' => 2,
						'max' => 20,
					),
					'em' => array(
						'max'  => 2,
						'step' => 0.1,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .uael-style-inline .elementor-grid .elementor-grid-item div.uael-share-btn.uaelbtn--skin-framed' => 'border-width: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .uael-style-inline .elementor-grid .elementor-grid-item div.uael-share-btn.uaelbtn--skin-boxed' => 'border-width: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .uael-style-floating .elementor-grid .elementor-grid-item div.uael-share-btn.uaelbtn--skin-framed' => 'border-width: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .uael-style-floating .elementor-grid .elementor-grid-item div.uael-share-btn.uaelbtn--skin-boxed' => 'border-width: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .uael-style-inline .elementor-grid .elementor-grid-item div.uael-share-btn.uaelbtn--skin-framed' => 'border-width: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .uael-style-inline .elementor-grid .elementor-grid-item div.uael-share-btn.uaelbtn--skin-boxed' => 'border-width: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .uael-style-floating .elementor-grid .elementor-grid-item div.uael-share-btn.uaelbtn--skin-framed' => 'border-width: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .uael-style-floating .elementor-grid .elementor-grid-item div.uael-share-btn.uaelbtn--skin-boxed' => 'border-width: {{SIZE}}{{UNIT}};',

				),
				'condition'  => array(
					'skin' => array( 'framed', 'boxed' ),
				),
			)
		);

		$this->add_control(
			'seprator',
			array(
				'type' => Controls_Manager::DIVIDER,
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'      => 'typography',
				'selector'  => '{{WRAPPER}} .uael-share-btn__text span.uael-share-btn__title,{{WRAPPER}} .uael-total-share-btn .uael-total-share-btn__iconx,{{WRAPPER}} .uael-total-share-btn .uael-total-share-btn__titlex',
				'exclude'   => array( 'line_height' ),
				'condition' => array(
					'view' => array( 'icon-text', 'text' ),
				),
			)
		);

		$this->add_control(
			'color',
			array(
				'label'   => __( 'Color', 'soft-template-core' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'default',
				'options' => array(
					'default' => __( 'Official Color', 'soft-template-core' ),
					'custom'  => __( 'Custom color', 'soft-template-core' ),
				),
			)
		);

		$this->start_controls_tabs( 'icon_colors' );

		$this->start_controls_tab(
			'icon_colors_normal',
			array(
				'label'     => __( 'Normal', 'soft-template-core' ),
				'condition' => array(
					'color' => 'custom',
				),
			)
		);

		$this->add_control(
			'primary_color',
			array(
				'label'     => __( 'Primary Color', 'soft-template-core' ),
				'type'      => Controls_Manager::COLOR,
				'condition' => array(
					'color' => 'custom',
				),
				'selectors' => array(

					'{{WRAPPER}}.elementor-widget-uael-social-share .uael-style-inline .elementor-grid .elementor-grid-item .uael-share-btn.uaelbtn--skin-gradient span.uael-share-btn__icon,
                        {{WRAPPER}}.elementor-widget-uael-social-share .uael-style-inline .elementor-grid .elementor-grid-item .uael-share-btn.uaelbtn--skin-gradient div.uael-share-btn__text' => 'background-color:{{VALUE}}',

					'{{WRAPPER}}.elementor-widget-uael-social-share .uael-style-inline .elementor-grid .elementor-grid-item .uael-share-btn.uaelbtn--skin-minimal span.uael-share-btn__icon' => 'background-color:{{VALUE}};',
					'{{WRAPPER}}.elementor-widget-uael-social-share .uael-style-inline .elementor-grid .elementor-grid-item .uael-share-btn.uaelbtn--skin-minimal > div' => 'color:#000000;',

					'{{WRAPPER}}.elementor-widget-uael-social-share .uael-style-inline .elementor-grid .elementor-grid-item .uael-share-btn.uaelbtn--skin-framed span.uael-share-btn__icon,
                    {{WRAPPER}}.elementor-widget-uael-social-share .uael-style-inline .elementor-grid .elementor-grid-item .uael-share-btn.uaelbtn--skin-framed div.uael-share-btn__text .uael-share-btn__title,
                    {{WRAPPER}}.elementor-widget-uael-social-share .uael-style-inline .elementor-grid .elementor-grid-item div.uael-share-btn.uaelbtn--skin-framed:first-child' => 'color: {{VALUE}};border-color: {{VALUE}};',

					'{{WRAPPER}}.elementor-widget-uael-social-share .uael-style-inline .elementor-grid .elementor-grid-item .uael-share-btn.uaelbtn--skin-boxed div.uael-share-btn__text .uael-share-btn__title,
                    {{WRAPPER}}.elementor-widget-uael-social-share .uael-style-inline .elementor-grid .elementor-grid-item a .uael-share-btn.uaelbtn--skin-boxed' => 'color: {{VALUE}};border-color: {{VALUE}};',
					'{{WRAPPER}}.elementor-widget-uael-social-share .uael-style-inline .elementor-grid .elementor-grid-item div.uael-share-btn.uaelbtn--skin-boxed span.uael-share-btn__icon' => 'background-color:{{VALUE}};',

					'{{WRAPPER}}.elementor-widget-uael-social-share .uael-style-inline .elementor-grid .elementor-grid-item div.uael-share-btn.uaelbtn--skin-flat span.uael-share-btn__icon,
                    {{WRAPPER}}.elementor-widget-uael-social-share .uael-style-inline .elementor-grid .elementor-grid-item .uael-share-btn.uaelbtn--skin-flat div.uael-share-btn__text' => 'background-color:{{VALUE}};',

					'{{WRAPPER}}.elementor-widget-uael-social-share .uael-style-floating .elementor-grid .elementor-grid-item .uael-share-btn.uaelbtn--skin-gradient span.uael-share-btn__icon,
                    {{WRAPPER}}.elementor-widget-uael-social-share .uael-style-floating .elementor-grid .elementor-grid-item .uael-share-btn.uaelbtn--skin-gradient div.uael-share-btn__text' => 'background-color:{{VALUE}}',

					'{{WRAPPER}}.elementor-widget-uael-social-share .uael-style-floating .elementor-grid .elementor-grid-item .uael-share-btn.uaelbtn--skin-minimal span.uael-share-btn__icon' => 'background-color:{{VALUE}};',
					'{{WRAPPER}}.elementor-widget-uael-social-share .uael-style-floating .elementor-grid .elementor-grid-item .uael-share-btn.uaelbtn--skin-minimal > div' => 'color:#000000;',

					'{{WRAPPER}}.elementor-widget-uael-social-share .uael-style-floating .elementor-grid .elementor-grid-item .uael-share-btn.uaelbtn--skin-framed span.uael-share-btn__icon,
                    {{WRAPPER}}.elementor-widget-uael-social-share .uael-style-floating .elementor-grid .elementor-grid-item .uael-share-btn.uaelbtn--skin-framed div.uael-share-btn__text .uael-share-btn__title,
                    {{WRAPPER}}.elementor-widget-uael-social-share .uael-style-floating .elementor-grid .elementor-grid-item div.uael-share-btn.uaelbtn--skin-framed:first-child' => 'color: {{VALUE}};border-color: {{VALUE}};',

					'{{WRAPPER}}.elementor-widget-uael-social-share .uael-style-floating .elementor-grid .elementor-grid-item .uael-share-btn.uaelbtn--skin-boxed div.uael-share-btn__text .uael-share-btn__title,
                    {{WRAPPER}}.elementor-widget-uael-social-share .uael-style-floating .elementor-grid .elementor-grid-item a .uael-share-btn.uaelbtn--skin-boxed' => 'color: {{VALUE}};border-color: {{VALUE}};',
					'{{WRAPPER}}.elementor-widget-uael-social-share .uael-style-floating .elementor-grid .elementor-grid-item div.uael-share-btn.uaelbtn--skin-boxed span.uael-share-btn__icon' => 'background-color:{{VALUE}};',

					'{{WRAPPER}}.elementor-widget-uael-social-share .uael-style-floating .elementor-grid .elementor-grid-item div.uael-share-btn.uaelbtn--skin-flat span.uael-share-btn__icon,
                        {{WRAPPER}}.elementor-widget-uael-social-share .uael-style-floating .elementor-grid .elementor-grid-item .uael-share-btn.uaelbtn--skin-flat div.uael-share-btn__text' => 'background-color:{{VALUE}};',

				),
			)
		);

		$this->add_control(
			'secondary_color',
			array(
				'label'     => __( 'Secondary Color', 'soft-template-core' ),
				'type'      => Controls_Manager::COLOR,
				'condition' => array(
					'color' => 'custom',
				),
				'selectors' => array(

					'{{WRAPPER}}.elementor-widget-uael-social-share .uael-style-inline .elementor-grid .uael-share-btn.uaelbtn--skin-flat div.uael-share-btn__text,
					.elementor-widget-uael-social-share .uael-style-inline .elementor-grid .uael-share-btn.uaelbtn--skin-flat span.uael-share-btn__icon.uael-share-btn__icon' => 'background-color: {{VALUE}};',
					'{{WRAPPER}}.elementor-widget-uael-social-share .uael-style-inline .elementor-grid .elementor-grid-item div.uael-share-btn.uaelbtn--skin-boxed span.uael-share-btn__icon i' => 'color: {{VALUE}};',
					'{{WRAPPER}}.elementor-widget-uael-social-share .uael-style-inline .elementor-grid .uael-share-btn.uaelbtn--skin-minimal span.uael-share-btn__icon' => 'background-color: {{VALUE}};',

					'{{WRAPPER}}.elementor-widget-uael-social-share .uael-style-inline .elementor-grid .elementor-grid-item .uael-share-btn.uaelbtn--skin-gradient span.uael-share-btn__icon,
                    {{WRAPPER}}.elementor-widget-uael-social-share .uael-style-inline .elementor-grid .elementor-grid-item .uael-share-btn.uaelbtn--skin-gradient div.uael-share-btn__text,
                    {{WRAPPER}}.elementor-widget-uael-social-share .uael-style-inline .elementor-grid .elementor-grid-item .uael-share-btn.uaelbtn--skin-minimal span.uael-share-btn__icon' => 'color: {{VALUE}};',

					'{{WRAPPER}}.elementor-widget-uael-social-share .uael-style-floating .elementor-grid .elementor-grid-item .uael-share-btn.uaelbtn--skin-gradient span.uael-share-btn__icon,
                        {{WRAPPER}}.elementor-widget-uael-social-share .uael-style-floating .elementor-grid .elementor-grid-item .uael-share-btn.uaelbtn--skin-gradient div.uael-share-btn__text,
                        {{WRAPPER}}.elementor-widget-uael-social-share .uael-style-floating .elementor-grid .elementor-grid-item .uael-share-btn.uaelbtn--skin-minimal span.uael-share-btn__icon' => 'color: {{VALUE}};',

					'{{WRAPPER}}.elementor-widget-uael-social-share .uael-style-inline .elementor-grid .elementor-grid-item .uael-share-btn.uaelbtn--skin-minimal span.uael-share-btn__icon,
                            {{WRAPPER}}.elementor-widget-uael-social-share .uael-style-inline .elementor-grid .elementor-grid-item .uael-share-btn.uaelbtn--skin-minimal div.uael-share-btn__text,
                            {{WRAPPER}}.elementor-widget-uael-social-share .uael-style-inline .elementor-grid .elementor-grid-item .uael-share-btn.uaelbtn--skin-minimal span.uael-share-btn__icon' => 'color: {{VALUE}};',

					'{{WRAPPER}}.elementor-widget-uael-social-share .uael-style-floating .elementor-grid .elementor-grid-item .uael-share-btn.uaelbtn--skin-minimal span.uael-share-btn__icon,
                        {{WRAPPER}}.elementor-widget-uael-social-share .uael-style-floating .elementor-grid .elementor-grid-item .uael-share-btn.uaelbtn--skin-minimal div.uael-share-btn__text,
                        {{WRAPPER}}.elementor-widget-uael-social-share .uael-style-floating .elementor-grid .elementor-grid-item .uael-share-btn.uaelbtn--skin-minimal span.uael-share-btn__icon' => 'color: {{VALUE}};',

					'{{WRAPPER}}.elementor-widget-uael-social-share .uael-style-inline .elementor-grid .elementor-grid-item .uael-share-btn.uaelbtn--skin-boxed span.uael-share-btn__icon,
                        {{WRAPPER}}.elementor-widget-uael-social-share .uael-style-inline .elementor-grid .elementor-grid-item .uael-share-btn.uaelbtn--skin-boxed div.uael-share-btn__text,
                        {{WRAPPER}}.elementor-widget-uael-social-share .uael-style-inline .elementor-grid .elementor-grid-item .uael-share-btn.uaelbtn--skin-boxed span.uael-share-btn__icon' => 'color: {{VALUE}};',

					'{{WRAPPER}}.elementor-widget-uael-social-share .uael-style-floating .elementor-grid .elementor-grid-item .uael-share-btn.uaelbtn--skin-boxed span.uael-share-btn__icon,
                            {{WRAPPER}}.elementor-widget-uael-social-share .uael-style-floating .elementor-grid .elementor-grid-item .uael-share-btn.uaelbtn--skin-boxed div.uael-share-btn__text,
                            {{WRAPPER}}.elementor-widget-uael-social-share .uael-style-floating .elementor-grid .elementor-grid-item .uael-share-btn.uaelbtn--skin-boxed span.uael-share-btn__icon' => 'color: {{VALUE}};',

					'{{WRAPPER}}.elementor-widget-uael-social-share .uael-style-inline .elementor-grid .elementor-grid-item .uael-share-btn.uaelbtn--skin-flat span.uael-share-btn__icon i,
                        {{WRAPPER}}.elementor-widget-uael-social-share .uael-style-inline .elementor-grid .elementor-grid-item .uael-share-btn.uaelbtn--skin-flat div.uael-share-btn__text,
                        {{WRAPPER}}.elementor-widget-uael-social-share .uael-style-inline .elementor-grid .elementor-grid-item .uael-share-btn.uaelbtn--skin-flat span.uael-share-btn__icon' => 'color: {{VALUE}};',

					'{{WRAPPER}}.elementor-widget-uael-social-share .uael-style-floating .elementor-grid .elementor-grid-item .uael-share-btn.uaelbtn--skin-flat span.uael-share-btn__icon i,
                            {{WRAPPER}}.elementor-widget-uael-social-share .uael-style-floating .elementor-grid .elementor-grid-item .uael-share-btn.uaelbtn--skin-flat div.uael-share-btn__text,
                            {{WRAPPER}}.elementor-widget-uael-social-share .uael-style-floating .elementor-grid .elementor-grid-item .uael-share-btn.uaelbtn--skin-flat span.uael-share-btn__icon' => 'color: {{VALUE}};',

				),
			)
		);
		$this->end_controls_tab();

		$this->start_controls_tab(
			'icon_colors_hover',
			array(
				'label'     => __( 'Hover', 'soft-template-core' ),
				'condition' => array(
					'color' => 'custom',
				),
			)
		);

		$this->add_control(
			'hover_primary_color',
			array(
				'label'     => __( 'Primary Color', 'soft-template-core' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'condition' => array(
					'color' => 'custom',
				),
				'selectors' => array(

					'{{WRAPPER}}.elementor-widget-uael-social-share .uael-style-inline .elementor-grid .elementor-grid-item .uael-share-btn.uaelbtn--skin-flat:hover span.uael-share-btn__icon,
  				{{WRAPPER}}.elementor-widget-uael-social-share .uael-style-inline .elementor-grid .elementor-grid-item .uael-share-btn.uaelbtn--skin-flat:hover div' => 'background-color: {{VALUE}}',

					'{{WRAPPER}}.elementor-widget-uael-social-share .uael-style-inline .elementor-grid .elementor-grid-item .uael-share-btn.uaelbtn--skin-minimal:hover span.uael-share-btn__icon' => 'background-color: {{VALUE}}',
					'{{WRAPPER}}.elementor-widget-uael-social-share .uael-style-inline .elementor-grid .elementor-grid-item .uael-share-btn.uaelbtn--skin-minimal:hover div' => 'color:{{VALUE}}',

					'{{WRAPPER}}.elementor-widget-uael-social-share .uael-style-inline .elementor-grid .elementor-grid-item .uael-share-btn.uaelbtn--skin-boxed:hover span.uael-share-btn__icon' => 'background-color: {{VALUE}}',
					'{{WRAPPER}}.elementor-widget-uael-social-share .uael-style-inline .elementor-grid .elementor-grid-item .uael-share-btn.uaelbtn--skin-boxed:hover div.uael-share-btn__text .uael-share-btn__title,
					 {{WRAPPER}}.elementor-widget-uael-social-share .uael-style-inline .elementor-grid .elementor-grid-item .uael-share-btn.uaelbtn--skin-boxed:hover' => 'color:{{VALUE}};border-color: {{VALUE}};',

					'{{WRAPPER}}.elementor-widget-uael-social-share .uael-style-inline .elementor-grid .elementor-grid-item .uael-share-btn.uaelbtn--skin-framed:hover span.uael-share-btn__icon,
                      {{WRAPPER}}.elementor-widget-uael-social-share .uael-style-inline .elementor-grid .elementor-grid-item .uael-share-btn.uaelbtn--skin-framed:hover div.uael-share-btn__text .uael-share-btn__title,
                      {{WRAPPER}}.elementor-widget-uael-social-share .uael-style-inline .elementor-grid .elementor-grid-item .uael-share-btn.uaelbtn--skin-framed:hover' => 'color:{{VALUE}};border-color: {{VALUE}};',
					'{{WRAPPER}}.elementor-widget-uael-social-share .uael-style-inline .elementor-grid .elementor-grid-item .uael-share-btn.uaelbtn--skin-framed:hover' => 'color:{{VALUE}};',

					'{{WRAPPER}}.elementor-widget-uael-social-share .uael-style-inline .elementor-grid .elementor-grid-item .uael-share-btn.uaelbtn--skin-gradient:hover span.uael-share-btn__icon,
                {{WRAPPER}}.elementor-widget-uael-social-share .uael-style-inline .elementor-grid .elementor-grid-item .uael-share-btn.uaelbtn--skin-gradient:hover div' => 'background-color: {{VALUE}}',

					'{{WRAPPER}}.elementor-widget-uael-social-share .uael-style-floating .elementor-grid .elementor-grid-item .uael-share-btn.uaelbtn--skin-flat:hover span.uael-share-btn__icon,
					 {{WRAPPER}}.elementor-widget-uael-social-share .uael-style-floating .elementor-grid .elementor-grid-item .uael-share-btn.uaelbtn--skin-flat:hover div' => 'background-color: {{VALUE}}',

					'{{WRAPPER}}.elementor-widget-uael-social-share .uael-style-floating .elementor-grid .elementor-grid-item .uael-share-btn.uaelbtn--skin-minimal:hover span.uael-share-btn__icon' => 'background-color: {{VALUE}}',
					'{{WRAPPER}}.elementor-widget-uael-social-share .uael-style-floating .elementor-grid .elementor-grid-item .uael-share-btn.uaelbtn--skin-minimal:hover div' => 'color:{{VALUE}}',

					'{{WRAPPER}}.elementor-widget-uael-social-share .uael-style-floating .elementor-grid .elementor-grid-item .uael-share-btn.uaelbtn--skin-boxed:hover span.uael-share-btn__icon' => 'background-color: {{VALUE}}',
					'{{WRAPPER}}.elementor-widget-uael-social-share .uael-style-floating .elementor-grid .elementor-grid-item .uael-share-btn.uaelbtn--skin-boxed:hover div.uael-share-btn__text .uael-share-btn__title,
					 {{WRAPPER}}.elementor-widget-uael-social-share .uael-style-floating .elementor-grid .elementor-grid-item .uael-share-btn.uaelbtn--skin-boxed:hover' => 'color:{{VALUE}};border-color: {{VALUE}};',

					'{{WRAPPER}}.elementor-widget-uael-social-share .uael-style-floating .elementor-grid .elementor-grid-item .uael-share-btn.uaelbtn--skin-framed:hover span.uael-share-btn__icon,
                      {{WRAPPER}}.elementor-widget-uael-social-share .uael-style-floating .elementor-grid .elementor-grid-item .uael-share-btn.uaelbtn--skin-framed:hover div.uael-share-btn__text .uael-share-btn__title,
                      {{WRAPPER}}.elementor-widget-uael-social-share .uael-style-floating .elementor-grid .elementor-grid-item .uael-share-btn.uaelbtn--skin-framed:hover' => 'color:{{VALUE}};border-color: {{VALUE}};',
					'{{WRAPPER}}.elementor-widget-uael-social-share .uael-style-floating .elementor-grid .elementor-grid-item .uael-share-btn.uaelbtn--skin-framed:hover' => 'color:{{VALUE}};',

					'{{WRAPPER}}.elementor-widget-uael-social-share .uael-style-floating .elementor-grid .elementor-grid-item .uael-share-btn.uaelbtn--skin-gradient:hover span.uael-share-btn__icon,
                {{WRAPPER}}.elementor-widget-uael-social-share .uael-style-floating .elementor-grid .elementor-grid-item .uael-share-btn.uaelbtn--skin-gradient:hover div' => 'background-color: {{VALUE}}',

				),
			)
		);

		$this->add_control(
			'hover_secondary_color',
			array(
				'label'     => __( 'Secondary Color', 'soft-template-core' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'condition' => array(
					'color' => 'custom',
				),
				'selectors' => array(

					'{{WRAPPER}}.elementor-widget-uael-social-share .uael-style-inline .elementor-grid .elementor-grid-item .uael-share-btn.uaelbtn--skin-flat:hover div.uael-share-btn__text,
					{{WRAPPER}}.elementor-widget-uael-social-share .uael-style-inline .elementor-grid .elementor-grid-item .uael-share-btn.uaelbtn--skin-flat:hover span.uael-share-btn__icon i' => 'color: {{VALUE}}',

					'{{WRAPPER}}.elementor-widget-uael-social-share .uael-style-inline .elementor-grid .elementor-grid-item .uael-share-btn.uaelbtn--skin-minimal:hover span.uael-share-btn__icon' => 'color: {{VALUE}}',

					'{{WRAPPER}}.elementor-widget-uael-social-share .uael-style-inline .elementor-grid .elementor-grid-item .uael-share-btn.uaelbtn--skin-boxed:hover span.uael-share-btn__icon i' => 'color: {{VALUE}}',

					'{{WRAPPER}}.elementor-widget-uael-social-share .uael-style-inline .elementor-grid .elementor-grid-item .uael-share-btn.uaelbtn--skin-gradient:hover div.uael-share-btn__text,
					{{WRAPPER}}.elementor-widget-uael-social-share .uael-style-inline .elementor-grid .elementor-grid-item .uael-share-btn.uaelbtn--skin-gradient:hover span.uael-share-btn__icon' => 'color: {{VALUE}}',

					'{{WRAPPER}}.elementor-widget-uael-social-share .uael-style-floating .elementor-grid .elementor-grid-item .uael-share-btn.uaelbtn--skin-flat:hover div.uael-share-btn__text,
					{{WRAPPER}}.elementor-widget-uael-social-share .uael-style-floating .elementor-grid .elementor-grid-item .uael-share-btn.uaelbtn--skin-flat:hover span.uael-share-btn__icon i' => 'color: {{VALUE}}',

					'{{WRAPPER}}.elementor-widget-uael-social-share .uael-style-floating .elementor-grid .elementor-grid-item .uael-share-btn.uaelbtn--skin-minimal:hover span.uael-share-btn__icon' => 'color: {{VALUE}}',

					'{{WRAPPER}}.elementor-widget-uael-social-share .uael-style-floating .elementor-grid .elementor-grid-item .uael-share-btn.uaelbtn--skin-boxed:hover span.uael-share-btn__icon i' => 'color: {{VALUE}}',

					'{{WRAPPER}}.elementor-widget-uael-social-share .uael-style-floating .elementor-grid .elementor-grid-item .uael-share-btn.uaelbtn--skin-gradient:hover div.uael-share-btn__text,
					{{WRAPPER}}.elementor-widget-uael-social-share .uael-style-floating .elementor-grid .elementor-grid-item .uael-share-btn.uaelbtn--skin-gradient:hover span.uael-share-btn__icon' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'hover_animation',
			array(
				'label'     => __( 'Hover Animation', 'soft-template-core' ),
				'type'      => Controls_Manager::HOVER_ANIMATION,
				'exclude'   => array( 'shrink' ),
				'condition' => array(
					'color' => 'custom',
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'text_padding',
			array(
				'label'      => __( 'Text Padding', 'soft-template-core' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .uael-share-btn__title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'condition'  => array(
					'view' => 'text',
				),
			)
		);

		$this->end_controls_tab();
		$this->end_controls_section();
	}

    /**
	 * Register Count Controls Controls.
	 *
	 * @since 1.30.0
	 * @access protected
	 */
	protected function register_count_content_controls() {
		$this->start_controls_section(
			'section_share_count',
			array(
				'label' => __( 'Share Count', 'soft-template-core' ),
			)
		);

		$this->add_control(
			'show_count',
			array(
				'label'       => __( 'Share Count', 'soft-template-core' ),
				'description' => __( 'Enable this option to display the total share count of target post/page.', 'soft-template-core' ),
				'type'        => Controls_Manager::SWITCHER,
				'yes'         => __( 'Show', 'soft-template-core' ),
				'no'          => __( 'Hide', 'soft-template-core' ),
				'default'     => 'no',
				'render_type' => 'template',
			)
		);

		$this->add_control(
			'enable_fake_count',
			array(
				'label'       => __( 'Show Fake Count', 'soft-template-core' ),
				'type'        => Controls_Manager::SELECT,
				'default'     => 'no',
				'options'     => array(
					'no'     => __( 'No', 'soft-template-core' ),
					'yes'    => __( 'Static Count', 'soft-template-core' ),
					'random' => __( 'Random Count', 'soft-template-core' ),
				),
				'render_type' => 'template',
				'condition'   => array(
					'show_count' => 'yes',
				),
			)
		);

		$this->add_control(
			'fake_count',
			array(
				'label'       => __( 'Fake Count', 'soft-template-core' ),
				'description' => __( 'Note: Enter the number of fake count you want to display.', 'soft-template-core' ),
				'type'        => Controls_Manager::NUMBER,
				'min'         => 5,
				'max'         => 500,
				'step'        => 1,
				'default'     => 10,
				'condition'   => array(
					'enable_fake_count' => 'yes',
					'show_count'        => 'yes',
				),
			)
		);

		$this->add_control(
			'fake_count_min',
			array(
				'label'     => __( 'Minimum Count', 'soft-template-core' ),
				'type'      => Controls_Manager::NUMBER,
				'min'       => 5,
				'max'       => 1000,
				'step'      => 1,
				'default'   => 25,
				'condition' => array(
					'enable_fake_count' => 'random',
					'show_count'        => 'yes',
				),
			)
		);

		$this->add_control(
			'fake_count_max',
			array(
				'label'     => __( 'Maximum Count', 'soft-template-core' ),
				'type'      => Controls_Manager::NUMBER,
				'min'       => 5,
				'max'       => 1000,
				'step'      => 1,
				'default'   => 35,
				'condition' => array(
					'enable_fake_count' => 'random',
					'show_count'        => 'yes',
				),
			)
		);

		$this->add_control(
			'fake_random_count_note',
			array(
				'type'            => Controls_Manager::RAW_HTML,
				/* translators: %s admin link */
				'raw'             => sprintf( 'Note: Set the minimum & maximum count values to display the random number for your different posts/pages.', 'soft-template-core' ),
				'content_classes' => 'elementor-panel-alert elementor-panel-alert-warning',
				'condition'       => array(
					'enable_fake_count' => 'random',
					'show_count'        => 'yes',
				),
			)
		);

		$this->add_control(
			'fake_count_limit',
			array(
				'label'       => __( 'Fake Count Limit', 'soft-template-core' ),
				'description' => __( 'Once the original count reaches this limit the fake count will be hidden. Only the original count will be shown after this limit reaches.', 'soft-template-core' ),
				'type'        => Controls_Manager::NUMBER,
				'min'         => 5,
				'max'         => 1000,
				'step'        => 1,
				'default'     => 10,
				'condition'   => array(
					'enable_fake_count!' => 'no',
					'show_count'         => 'yes',
				),
			)
		);

		$this->add_control(
			'show_count_for',
			array(
				'label'       => __( 'Fetch Share Count From', 'soft-template-core' ),
				'description' => __( 'Choose Social media from above actions to display share count of particular media.', 'soft-template-core' ),
				'type'        => Controls_Manager::SELECT2,
				'multiple'    => true,
				'label_block' => true,
				'options'     => array(
					'facebook'      => __( 'Facebook', 'soft-template-core' ),
					'twitter'       => __( 'Twitter', 'soft-template-core' ),
					'pinterest'     => __( 'Pinterest', 'soft-template-core' ),
					'tumblr'        => __( 'Tumblr', 'soft-template-core' ),
					'reddit'        => __( 'Reddit', 'soft-template-core' ),
					'buffer'        => __( 'Buffer', 'soft-template-core' ),
					'vk'            => __( 'VK', 'soft-template-core' ),
					'odnoklassniki' => __( 'OK', 'soft-template-core' ), // odnoklassniki.
				),
				'condition'   => array(
					'show_count' => 'yes',
				),
			)
		);

		$this->add_control(
			'show_time_note',
			array(
				'type'            => Controls_Manager::RAW_HTML,
				/* translators: %s admin link */
				'raw'             => sprintf( 'Note: The share count will not instantly reflect on page share. It depends on the API to update the count.', 'soft-template-core' ),
				'content_classes' => 'elementor-panel-alert elementor-panel-alert-warning',
				'condition'       => array(
					'show_count' => 'yes',
				),
			)
		);

		$this->add_control(
			'twitter_options',
			array(
				'label'     => __( 'Twitter', 'soft-template-core' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => array(
					'show_count_for' => 'twitter',
					'show_count'     => 'yes',
				),
			)
		);

		$this->add_control(
			'urls',
			array(
				'type'            => Controls_Manager::RAW_HTML,
				/* translators: %s link */
				'raw'             => sprintf( __( 'For Twitter you will need to register your website %s in order to return the share counts.', 'soft-template-core' ), '<a href="http://opensharecount.com/" target="_blank" rel="noopener">here</a>' ),
				'content_classes' => 'elementor-panel-alert elementor-panel-alert-warning',
				'condition'       => array(
					'show_count_for' => 'twitter',
					'show_count'     => 'yes',
				),
			)
		);

		$this->add_control(
			'pinterest_options',
			array(
				'label'     => __( 'Pinterest', 'soft-template-core' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => array(
					'show_count_for' => 'pinterest',
					'show_count'     => 'yes',
				),
			)
		);

		$this->add_control(
			'pin_error_msg',
			array(
				'type'            => Controls_Manager::RAW_HTML,
				'raw'             => sprintf( __( 'For pinterest you need to set up a featured image and Image URL should contain https://', 'soft-template-core' ) ),
				'content_classes' => 'elementor-panel-alert elementor-panel-alert-warning',
				'condition'       => array(
					'show_count_for' => 'pinterest',
					'show_count'     => 'yes',
				),
			)
		);


		$admin_link          = admin_url('page=soft-template-core');
		$admin_link          = esc_url( $admin_link );

        $this->add_control(
            'facebook_options',
            array(
                'label'     => __( 'Facebook', 'soft-template-core' ),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
                'condition' => array(
                    'show_count_for' => 'facebook',
                    'show_count'     => 'yes',
                ),
            )
        );

        $this->add_control(
            'widget_title',
            [
                'label' => __( 'Title', 'plugin-domain' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => __( 'Default title', 'plugin-domain' ),
                'placeholder' => __( 'Type your title here', 'plugin-domain' ),
            ]
        );

        $this->add_control(
            'error_msg',
            array(
                'type'            => Controls_Manager::RAW_HTML,
                /* translators: %s admin link */
                    'raw'         => sprintf( __( 'Please configure Facebook Access Token from <a href="%s" target="_blank" rel="noopener">here</a>.', 'soft-template-core' ), $admin_link ),
                'content_classes' => 'elementor-panel-alert elementor-panel-alert-danger',
                'condition'       => array(
                    'show_count_for' => 'facebook',
                    'show_count'     => 'yes',
                ),
            )
        );

		$this->add_control(
			'share_text',
			array(
				'label'     => __( 'Style', 'soft-template-core' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => array(
					'icon'   => __( 'Icon', 'soft-template-core' ),
					'custom' => __( 'Text', 'soft-template-core' ),
				),
				'condition' => array(
					'show_count' => 'yes',
				),
				'default'   => 'icon',
				'separator' => 'before',
			)
		);

		$this->add_control(
			'custom_share_text',
			array(
				'label'              => __( 'Text', 'soft-template-core' ),
				'type'               => Controls_Manager::TEXT,
				'default'            => __( 'Share ', 'soft-template-core' ),
				'placeholder'        => __( 'Custom Text', 'soft-template-core' ),
				'condition'          => array(
					'share_text' => 'custom',
					'show_count' => 'yes',
				),
				'show_label'         => true,
				'dynamic'            => array(
					'active' => true,
				),
				'frontend_available' => true,
				'render_type'        => 'template',
			)
		);

		$this->add_responsive_control(
			'share_size',
			array(
				'label'          => __( 'Size', 'soft-template-core' ),
				'type'           => Controls_Manager::SLIDER,
				'range'          => array(
					'px' => array(
						'min'  => 0.5,
						'max'  => 5,
						'step' => 0.05,
					),
				),
				'default'        => array(
					'unit' => 'px',
				),
				'tablet_default' => array(
					'unit' => 'px',
				),
				'mobile_default' => array(
					'unit' => 'px',
				),
				'size_units'     => array( 'px' ),
				'selectors'      => array(
					'{{WRAPPER}} .uael-style-floating .uael-total-share-btn__iconx,{{WRAPPER}} .uael-style-inline .uael-total-share-btn__iconx' => 'font-size: calc({{SIZE}}{{UNIT}} * 10);',
					'{{WRAPPER}} .uael-style-floating .uael-total-share-btn__titlex,{{WRAPPER}} .uael-style-inline .uael-total-share-btn__titlex' => 'font-size: calc({{SIZE}}{{UNIT}} * 10);',
				),
				'condition'      => array(
					'display_position' => array( 'inline', 'floating' ),
					'show_count'       => 'yes',
				),
			)
		);

		$this->add_control(
			'custom_primary_color',
			array(
				'label'     => __( 'Text/Icon Color', 'soft-template-core' ),
				'type'      => Controls_Manager::COLOR,
				'global'    => array(
					'default' => Global_Colors::COLOR_PRIMARY,
				),
				'default'   => '',
				'condition' => array(
					'share_text' => array( 'custom', 'icon' ),
					'show_count' => 'yes',
				),
				'selectors' => array(
					'{{WRAPPER}} span.uael-total-share-btn__icon,{{WRAPPER}} .uael-total-share-btn__title' => 'color: {{VALUE}}',
					'{{WRAPPER}} span.uael-total-share-btn__iconx,{{WRAPPER}} .uael-total-share-btn__titlex' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'      => 'share_typography',
				'global'    => array(
					'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
				),
				'selector'  => '{{WRAPPER}} .uael-style-inline .uael-total-share-btn__iconx,{{WRAPPER}} .uael-style-inline .uael-total-share-btn__titlex,
                {{WRAPPER}} .uael-style-floating .uael-total-share-btn__iconx,{{WRAPPER}} .uael-style-floating .uael-total-share-btn__titlex',
				'exclude'   => array( 'line_height', 'font_size', 'letter_spacing' ),
				'condition' => array(
					'share_text' => 'custom',
					'show_count' => 'yes',
				),
			)
		);

		$this->add_responsive_control(
			'count_alignment',
			array(
				'label'     => __( 'Alignment', 'soft-template-core' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => array(
					'flex-start' => array(
						'title' => __( 'Left', 'soft-template-core' ),
						'icon'  => 'fa fa-align-left',
					),
					'center'     => array(
						'title' => __( 'Center', 'soft-template-core' ),
						'icon'  => 'fa fa-align-center',
					),
					'flex-end'   => array(
						'title' => __( 'Right', 'soft-template-core' ),
						'icon'  => 'fa fa-align-right',
					),
				),
				'default'   => 'flex-start',
				'selectors' => array(
					'{{WRAPPER}} .uael-style-floating .uael-total-share-btn' => 'justify-content: {{VALUE}};',
				),
				'condition' => array(
					'display_position' => 'floating',
					'show_count'       => 'yes',
					'view!'            => 'icon',
				),
			)
		);

		$this->end_controls_section();
	}

    
    public function widget_style() {
        
    }

    	/**
	 * Render the widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 1.30.0
	 *
	 * @access protected
	 */
	protected function render() {

		global $wp;
		$settings  = $this->get_settings();
		$id        = $this->get_id();
		$is_editor = \Elementor\Plugin::instance()->editor->is_edit_mode();

		$fake_count_limit  = ! empty( $settings['fake_count_limit'] ) ? $settings['fake_count_limit'] : 0;
		$fake_count_number = ! empty( $settings['fake_count'] ) ? $settings['fake_count'] : 0;
		$pin_image_url     = '';
		$count             = 0;
		$show_share_count  = $settings['show_count'];
		$total_count       = array();
		$total_result      = 0;

		$networks            = isset( $settings['show_count_for'] ) ? $settings['show_count_for'] : '';
		$args                = array( 'timeout' => 30 );

		if ( 'floating' === $settings['display_position'] && $is_editor ) { ?>

			<div class="uael-builder-msg" style="text-align: center;">
				<h5><?php esc_html_e( 'Floating Social Share - ID', 'soft-template-core' ); ?><?php echo esc_html( $id ); ?></h5>
				<p><?php esc_html_e( 'Click here to edit the "Floating Social Share" settings. This text will not be visible on frontend.', 'soft-template-core' ); ?></p>
			</div>
			<?php
		}

		if ( 'custom' === $settings['share_url_type'] ) {

			if ( '' === $settings['share_url']['url'] ) {

				$page_url = add_query_arg( $wp->query_vars, home_url( $wp->request ) );
			} else {

				$page_url = esc_url( $settings['share_url']['url'] );
			}
		} else {

			$page_url = add_query_arg( $wp->query_vars, home_url( $wp->request ) );
		}

		if ( 'yes' === $settings['show_count'] && ! empty( $settings['show_count_for'] ) ) {

			if ( in_array( 'facebook', $settings['show_count_for'], true ) ) {

                $get_app_id = soft_template_core()->settings->get( 'facebook_app_id' );
                $get_app_scret = soft_template_core()->settings->get( 'facebook_app_secret' );
                $get_app_tocken = soft_template_core()->settings->get( 'facebook_access_tocken' );

				if ( ('' === $get_app_scret && '' === $get_app_id ) && $is_editor ) {
					?>

					<div class="uael-builder-msg" style="text-align: center;">
						<p><?php esc_html_e( 'Please set the Facebook token from Dashboard -> Settings -> UAE -> Social Share - Facebook Access Token.', 'soft-template-core' ); ?></p>
					</div>
					<?php
				} else {



					$access_token = $get_app_tocken;

				}
			}

			if ( in_array( 'pinterest', $settings['show_count_for'], true ) ) {

				if ( empty( get_the_post_thumbnail_url() ) && $is_editor ) {
					?>

					<div class="uael-builder-msg" style="text-align: center;">
						<p><?php esc_html_e( 'To share the post on Pinterest you must assign a featured image for the Post/Page.', 'soft-template-core' ); ?></p>
					</div>
					<?php
				}
			}
		}

		foreach ( $settings['social_icon_list'] as $value ) {

			if ( 'pinterest' === $value['text'] ) {

				$pin_image_url = ( ! empty( get_the_post_thumbnail_url() ) ? get_the_post_thumbnail_url() : Utils::get_placeholder_image_src() );
			}
		}

		$share_url = isset( $settings['share_url']['url'] ) ? $settings['share_url']['url'] : '';
		?>

		<div class="uael-style-<?php echo esc_attr( $settings['display_position'] ); ?> uael-container uael-floating-align-<?php echo esc_attr( $settings['display_floating_align'] ); ?>" data-pin_data_url="<?php echo esc_attr( $pin_image_url ); ?>" data-share_url_type="<?php echo esc_attr( $settings['share_url_type'] ); ?>" data-share_url="<?php echo esc_attr( $share_url ); ?>">
			<div class="elementor-grid uael-floating-align-<?php echo esc_attr( $settings['display_floating_align'] ); ?>">
				<?php

				if ( ! empty( $settings['show_count_for'] ) ) {

					if ( is_array( $settings['show_count_for'] ) ) {

						foreach ( $settings['show_count_for'] as $action ) {

							switch ( $action ) {

								case 'facebook':
									if ( empty( $access_token ) ) {

										$response     = trailingslashit( 'https://graph.facebook.com/v2.12' ) . '?id=' . $page_url;
										$result       = 0;
										$total_result = 0;
										$total_result = $total_result + $result;
									} else {

										$result       = $this->retrive_share_count_fb( $access_token, $page_url );
										$total_result = isset( $total_result ) ? $total_result : 0;
										$total_result = $total_result + $result;
									}
									break;

								case 'twitter':
									$response = 'http://opensharecount.com/count.json?url=' . $page_url;
									$response = \Soft_template_Core_Utils::get_social_share_count( $response, $args );
									$response = isset( $response ) ? json_decode( $response ) : 0;

									if ( isset( $response->count ) ) {

										$result       = $response->count;
										$total_result = isset( $total_result ) ? $total_result : 0;
										$total_result = $total_result + $result;
									}
									break;

								case 'pinterest':
									$result       = $this->retrive_share_count_pin( $page_url );
									$total_result = $total_result + $result;
									break;

								case 'tumblr':
									$result       = $this->retrive_share_count_thumblr( $page_url );
									$total_result = $total_result + $result;
									break;

								case 'reddit':
									$result       = $this->retrive_share_count_reddit( $page_url );
									$total_result = $total_result + $result;
									break;

								case 'buffer':
									$result       = $this->retrive_share_count_buffer( $page_url );
									$total_result = $total_result + $result;
									break;

								case 'vk':
									$result       = $this->retrive_share_count_vk( $page_url );
									$total_result = $total_result + $result;
									break;

								case 'odnoklassniki':
									$result       = $this->retrive_share_count_ok( $page_url );
									$total_result = $total_result + $result;
									break;

								default:
									// code...
									break;
							}
						}
					}
				}

				foreach ( $settings['social_icon_list'] as $button ) {
					?>
					<div class="elementor-grid-item">
						<?php
						$result = isset( $result ) ? $result : 0;

						if ( ! empty( $button['Custom_text'] ) ) {

							$custom_text = $button['Custom_text'];
						} else {

							$custom_text = $button['text'];
						}

						$icon_prop = $button['text'];

						if ( '' !== $icon_prop ) {

							$uael_js_callback_class = $button['text'];
						}
						?>
						<a  class="uael-share-btn-<?php echo esc_attr( $uael_js_callback_class ); ?>">
							<div class="uael-share-btn elementor-animation-<?php echo esc_attr( $settings['hover_animation'] ); ?> uaelbtn-shape-<?php echo esc_attr( $settings['shape'] ); ?> uaelbtn--skin-<?php echo esc_attr( $settings['skin'] ); ?>">

								<?php
								switch ( $settings['view'] ) {
									case 'icon-text':
										if ( 'left' === $settings['icon_align'] ) {
											?>
											<span class="uael-share-btn__icon">
												<i class="<?php echo esc_attr( self::uael_get_network_class( $button['text'] ) ); ?>" aria-hidden="true"></i>
												<span class="elementor-screen-only"><?php /* translators: %s share text */ echo esc_html( sprintf( __( 'Share on %s', 'soft-template-core' ), $custom_text ) ); ?></span>
											</span>
										<?php } ?>
										<div class="uael-share-btn__text">
												<span class="uael-share-btn__title"><?php echo esc_html( ucfirst( $custom_text ) ); ?></span>
										</div>
										<?php if ( 'right' === $settings['icon_align'] ) { ?>
											<span class="uael-share-btn__icon">
												<i class="<?php echo esc_attr( self::uael_get_network_class( $button['text'] ) ); ?>" aria-hidden="true"></i>
												<span class="elementor-screen-only"><?php /* translators: %s share text */ echo esc_html( sprintf( __( 'Share on %s', 'soft-template-core' ), $custom_text ) ); ?></span>
											</span>
										<?php } ?>
										<?php
										break;

									case 'text':
										?>
										<div class="uael-share-btn__text">
											<span class="uael-share-btn__title">
												<?php echo esc_html( ucfirst( $custom_text ) ); ?>
											</span>
										</div>
										<?php
										break;

									case 'icon':
										?>
										<span class="uael-share-btn__icon">
											<i class="<?php echo esc_attr( self::uael_get_network_class( $button['text'] ) ); ?>" aria-hidden="true"></i>
											<span class="elementor-screen-only"><?php /* translators: %s share text */ echo esc_html( sprintf( __( 'Share on %s', 'soft-template-core' ), $custom_text ) ); ?></span>
										</span>
										<?php
										break;

									default:
										// code...
										break;
								}
								?>
							</div>
						</a>
					</div>
					<?php
				}

				if ( 'inline' === $settings['display_position'] && 'yes' === $settings['show_count'] ) {
					?>

					<div class="uael-total-share-btn">
						<?php
						if ( ! empty( $settings['custom_share_text'] ) && 'custom' === $settings['share_text'] ) {
							?>

							<span class="uael-total-share-btn__iconx">
								<?php echo esc_html( $settings['custom_share_text'] ); ?>
							</span>
							<?php
						} elseif ( 'icon' === $settings['share_text'] ) {

							?>
							<span class="uael-total-share-btn__iconx">
								<i class="eicon-share" aria-hidden="true"></i>
							</span>
							<?php
						}
						?>
						<?php $this->get_count_html( $settings, $total_result ); ?>
					</div>
					<?php
				}

				if ( 'floating' === $settings['display_position'] ) {
					$html = $this->inline_floating( $settings, $total_result );
						echo $html; //phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				}
				?>
			</div>
		</div>
		<?php
	}

	/**
	 * Display total share count.
	 *
	 * @since 1.30.1
	 *
	 * @param array $settings returns settings.
	 * @param int   $total_result returns total result.
	 *
	 * @access public
	 */
	public function get_count_html( $settings, $total_result ) {
		global $post;
		$page_id = $post->ID;
		$id      = $this->get_id();
		?>
		<span class="uael-total-share-btn__titlex">

			<?php
			// Show/Hide count.
			$uael_show_share_count = ! empty( $settings['show_count'] ) ? $settings['show_count'] : 'no';

			$total_share_count = isset( $total_result ) ? $total_result : 0;

			$fake_count_limit = ( ! empty( $settings['fake_count_limit'] ) && ! empty( $settings['fake_count'] ) ) ? $settings['fake_count_limit'] : 0;

			$fake_count_number = 0;

			$uael_show_fake_count = ( ! empty( $settings['enable_fake_count'] ) ) ? $settings['enable_fake_count'] : 'no';

			if ( 'yes' === $uael_show_share_count && 'no' !== $uael_show_fake_count ) {

				if ( 'yes' === $uael_show_fake_count ) {
					if ( ( $fake_count_limit >= $total_share_count ) || ( ! $fake_count_limit && $settings['fake_count'] ) ) {
						$fake_count_number = apply_filters( 'uael_fake_share_count', $settings['fake_count'] );
						$fake_count_number = $total_share_count + $fake_count_number;
						echo esc_html( $fake_count_number );
					} else {
						echo esc_html( $total_share_count );
					}
				} elseif ( 'random' === $uael_show_fake_count ) {
					if ( ( ( ! empty( $fake_count_limit ) && ! empty( $total_share_count ) ) && ( $fake_count_limit >= $total_share_count ) ) || ( ( ! $fake_count_limit ) && ( ! empty( $settings['fake_count_min'] ) ) && ( ! empty( $settings['fake_count_max'] ) ) ) ) {

						$post_meta = get_post_meta( $page_id, 'uael-social-share-count', true );

						$is_meta_set = ( ! empty( $post_meta ) && is_array( $post_meta ) );

						if ( '' !== $page_id && ( $is_meta_set && isset( $post_meta[ $id ] ) ) && ( $settings['fake_count_min'] <= $post_meta[ $id ] ) && ( $settings['fake_count_max'] >= $post_meta[ $id ] ) ) {

							$fake_count_number = $post_meta[ $id ];

						} else {
							$rand_number = wp_rand( $settings['fake_count_min'], $settings['fake_count_max'] );

							if ( $is_meta_set && ! isset( $post_meta[ $id ] ) ) {
								$post_meta[ $id ] = $rand_number;
							} else {
								$post_meta = array(
									$id => $rand_number,
								);
							}

							update_post_meta( $page_id, 'uael-social-share-count', $post_meta );

							$fake_count_number = $rand_number;
						}

						$fake_count_number = apply_filters( 'uael_fake_share_count', $fake_count_number );
						$fake_count_number = $total_share_count + $fake_count_number;

						?>
						<span class="uael-share-fake-count"><?php echo esc_html( $fake_count_number ); ?></span>
						<?php
					} else {
						echo esc_html( $total_share_count );
					}
				}
			} elseif ( 'yes' === $uael_show_share_count && 'no' === $uael_show_fake_count ) {

				echo esc_html( $total_share_count );
			}
			?>
			</span>

		<?php
	}

	/**
	 * Access share inline/floating.
	 *
	 * @since 1.30.0
	 *
	 * @param array $settings returns settings.
	 * @param int   $total_result returns total result.
	 *
	 * @access public
	 */
	public function inline_floating( $settings, $total_result ) {

		if ( 'icon-text' === $settings['view'] || 'text' === $settings['view'] || 'icon' === $settings['view'] ) {
			?>

			<div class="elementor-grid-item">
			<?php
			if ( 'floating' === $settings['display_position'] ) {
				?>

				<div class="uael-total-share-btn">
				<?php
				if ( ! empty( $settings['custom_share_text'] ) && 'custom' === $settings['share_text'] ) {
					?>

					<span class="uael-total-share-btn__iconx">
						<?php echo esc_html( $settings['custom_share_text'] ); ?>
					</span>
					<?php
				} elseif ( 'icon' === $settings['share_text'] ) {
					?>

					<span class="uael-total-share-btn__iconx">
						<i class="eicon-share" aria-hidden="true"></i>
					</span>
					<?php
				}
				?>
				<?php $this->get_count_html( $settings, $total_result ); ?>
				</div>
				<?php
			} else {
				?>
				<div class="uael-total-share-btn">
				<?php
				if ( ! empty( $settings['custom_share_text'] ) && 'custom' === $settings['share_text'] ) {
					?>

					<span class="uael-total-share-btn__iconx">
						<?php echo esc_html( $settings['custom_share_text'] ); ?>
					</span>
				<?php } elseif ( 'icon' === $settings['share_text'] ) { ?>

						<span class="uael-total-share-btn__iconx">
							<i class="eicon-share" aria-hidden="true"></i>
						</span>
					<?php
				}
				?>
				<?php $this->get_count_html( $settings, $total_result ); ?>
				</div>
				<?php
			}
		}
	}

	/**
	 * Access facebook share count.
	 *
	 * @since 1.30.0
	 *
	 * @param array $access_token returns access token.
	 * @param url   $page_url returns page_url.
	 *
	 * @access public
	 */
	public function retrive_share_count_fb( $access_token, $page_url ) {

		$args     = array( 'timeout' => 30 );
		$response = trailingslashit( 'https://graph.facebook.com/v2.12' ) . '?id=' . $page_url . '&access_token=' . $access_token . '&fields=engagement';
		$response = \Soft_template_Core_Utils::get_social_share_count( $response, $args );
		$response = isset( $response ) ? json_decode( $response ) : 0;
		$result   = isset( $response->engagement->share_count ) ? $response->engagement->share_count : 0;

		return $result;
	}

	/**
	 * Access share thumblr count.
	 *
	 * @since 1.30.0.
	 * @param url $page_url return page url.
	 *
	 * @access public
	 */
	public function retrive_share_count_thumblr( $page_url ) {

		$args     = array( 'timeout' => 30 );
		$page_url = rawurlencode( $page_url );
		$response = 'http://api.tumblr.com/v2/share/stats?url=' . $page_url;
		$response = \Soft_template_Core_Utils::get_social_share_count( $response, $args );
		$response = isset( $response ) ? json_decode( $response ) : 0;

		$result = 0;

		if ( isset( $response->meta->status ) && 200 === $response->meta->status ) {

			if ( isset( $response->response->note_count ) ) {

				$result = intval( $response->response->note_count );
			} else {

				$result = 0;
			}
		}

		return $result;
	}

	/**
	 * Access share pinterest count.
	 *
	 * @since 1.30.0
	 * @param url $page_url return page url.
	 *
	 * @access public
	 */
	public function retrive_share_count_pin( $page_url ) {

		$args     = array( 'timeout' => 30 );
		$response = 'http://widgets.pinterest.com/v1/urls/count.json?url=' . $page_url;
		$response = \Soft_template_Core_Utils::get_social_share_count( $response, $args );
		$response = preg_replace( '/^receiveCount\((.*)\)$/', "\\1", $response );
		$response = isset( $response ) ? json_decode( $response ) : 0;
		$result   = 0;

		if ( isset( $response ) && isset( $response->count ) ) {

			$result = intval( $response->count );
		}

		return $result;
	}

	/**
	 * Access share reddit count.
	 *
	 * @since 1.30.0
	 * @param url $page_url return page url.
	 *
	 * @access public
	 */
	public function retrive_share_count_reddit( $page_url ) {

		$args     = array( 'timeout' => 30 );
		$response = 'https://www.reddit.com/api/info.json?url=' . $page_url;
		$response = \Soft_template_Core_Utils::get_social_share_count( $response, $args );
		$response = isset( $response ) ? json_decode( $response ) : 0;
		$result   = 0;
		$score    = 0;

		if ( isset( $response->data->children ) ) {

			foreach ( $response->data->children as $child ) {

				$score  = (int) $child->data->score;
				$result = $result + $score;
			}
		}

		return $result;
	}

	/**
	 * Access share vk count.
	 *
	 * @since 1.30.0
	 * @param url $page_url return page url.
	 *
	 * @access public
	 */
	public function retrive_share_count_vk( $page_url ) {

		$args     = array( 'timeout' => 30 );
		$response = 'https://vk.com/share.php?act=count&index=1&format=json&url=' . $page_url;
		$response = \Soft_template_Core_Utils::get_social_share_count( $response, $args );
		$result   = 0;
		preg_match( '/VK.Share.count\(1, ([0-9]+)\);/', $response, $matches );

		if ( is_array( $matches ) && isset( $matches[1] ) ) {

			$result = (int) $matches[1];
		}

		return $result;
	}

	/**
	 * Access share ok count.
	 *
	 * @since 1.30.0
	 * @param url $page_url return page url.
	 *
	 * @access public
	 */
	public function retrive_share_count_ok( $page_url ) {

		$args     = array( 'timeout' => 30 );
		$response = 'https://connect.ok.ru/dk?st.cmd=extLike&tp=json&ref=' . $page_url;
		$response = \Soft_template_Core_Utils::get_social_share_count( $response, $args );
		$response = isset( $response ) ? json_decode( $response ) : 0;
		$result   = 0;

		if ( isset( $response->count ) ) {
			$result = $response->count;
		}

		return $result;
	}

	/**
	 * Access share buffer count.
	 *
	 * @since 1.30.0
	 * @param url $page_url return page url.
	 *
	 * @access public
	 */
	public function retrive_share_count_buffer( $page_url ) {

		$args     = array( 'timeout' => 30 );
		$response = 'https://api.bufferapp.com/1/links/shares.json?url=' . $page_url;
		$response = \Soft_template_Core_Utils::get_social_share_count( $response, $args );
		$response = isset( $response ) ? json_decode( $response ) : 0;
		$result   = 0;

		if ( isset( $response->shares ) ) {

			$result = $response->shares;
		}

		return $result;
	}
}