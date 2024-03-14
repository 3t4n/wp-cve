<?php
/**
 * Class: Soft_Template_Menu
 * Name: Menu
 * Slug: soft-template-menu
 */
namespace Elementor;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Background;
use Elementor\Utils;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Typography;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Repeater;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Soft_Template_Menu extends SoftTemplate_Base {
    public function get_name() {
		return 'soft-template-nav-menu';
	}

	public function get_title() {
		return esc_html__( 'Nav Menu', 'soft-template-core' );
	}

    public function get_icon() {
		return 'eicon-nav-menu';
	}

	/**
	 * Retrieve the list of scripts the image carousel widget depended on.
	 *
	 * Used to set scripts dependencies required to run the widget.
	 *
	 * @since 1.0.1
	 * @access public
	 *
	 * @return array Widget scripts dependencies.
	 */
	public function get_script_depends() {
		return array( 'soft-template-menu', 'soft-element-resize', 'soft-element-cookie' );
	}

    public function get_jet_help_url() {
		return '#';
	}

	public function get_categories() {
		return array( 'soft-template-core' );
	}

    /**
	 * Menu index.
	 *
	 * @access protected
	 * @var $nav_menu_index
	 */
	protected $nav_menu_index = 1;


    protected function register_controls() {
		$this->register_general_content_controls();
		$this->register_style_content_controls();
		$this->register_dropdown_content_controls();
    }

    /**
	 * Register Nav Menu General Controls.
	 *
	 * @since 1.21.0
	 * @access protected
	 */
	protected function register_general_content_controls() {
        $this->start_controls_section(
			'section_menu',
			array(
				'label' => __( 'Menu', 'soft-template-core' ),
			)
		);

		$this->add_control(
			'menu_type',
			array(
				'label'   => __( 'Type', 'soft-template-core' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'wordpress_menu',
				'options' => array(
					'wordpress_menu' => __( 'WordPress Menu', 'soft-template-core' ),
					'custom'         => __( 'Custom', 'soft-template-core' ),
				),
			)
		);

		$menus = $this->get_available_menus();

		if ( ! empty( $menus ) ) {
			$this->add_control(
				'menu',
				array(
					'label'        => __( 'Menu', 'soft-template-core' ),
					'type'         => Controls_Manager::SELECT,
					'options'      => $menus,
					'default'      => array_keys( $menus )[0],
					'save_default' => true,
					/* translators: %s Nav menu URL */
					'description'  => sprintf( __( 'Go to the <a href="%s" target="_blank">Menus screen</a> to manage your menus.', 'soft-template-core' ), admin_url( 'nav-menus.php' ) ),
					'condition'    => array(
						'menu_type' => 'wordpress_menu',
					),
				)
			);
		} else {
			$this->add_control(
				'menu',
				array(
					'type'            => Controls_Manager::RAW_HTML,
					/* translators: %s Nav menu URL */
					'raw'             => sprintf( __( '<strong>There are no menus in your site.</strong><br>Go to the <a href="%s" target="_blank">Menus screen</a> to create one.', 'soft-template-core' ), admin_url( 'nav-menus.php?action=edit&menu=0' ) ),
					'separator'       => 'after',
					'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
					'condition'       => array(
						'menu_type' => 'wordpress_menu',
					),
				)
			);
		}

		$repeater = new Repeater();

		$repeater->add_control(
			'item_type',
			array(
				'label'   => __( 'Item Type', 'soft-template-core' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'item_menu',
				'options' => array(
					'item_menu'    => __( 'Menu', 'soft-template-core' ),
					'item_submenu' => __( 'Sub Menu', 'soft-template-core' ),
				),
			)
		);

		$repeater->add_control(
			'menu_content_type',
			array(
				'label'     => __( 'Content Type', 'soft-template-core' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => $this->get_content_type(),
				'default'   => 'sub_menu',
				'condition' => array(
					'item_type' => 'item_submenu',
				),
			)
		);

		$repeater->add_control(
			'text',
			array(
				'label'       => __( 'Text', 'soft-template-core' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => __( 'Item', 'soft-template-core' ),
				'placeholder' => __( 'Item', 'soft-template-core' ),
				'dynamic'     => array(
					'active' => true,
				),
				'conditions'  => array(
					'relation' => 'or',
					'terms'    => array(
						array(
							'name'     => 'item_type',
							'operator' => '==',
							'value'    => 'item_menu',
						),
						array(
							'name'     => 'menu_content_type',
							'operator' => '==',
							'value'    => 'sub_menu',
						),
					),
				),
			)
		);

		$repeater->add_control(
			'link',
			array(
				'label'      => __( 'Link', 'soft-template-core' ),
				'type'       => Controls_Manager::URL,
				'default'    => array(
					'url'         => '#',
					'is_external' => '',
				),
				'dynamic'    => array(
					'active' => true,
				),
				'conditions' => array(
					'relation' => 'or',
					'terms'    => array(
						array(
							'name'     => 'item_type',
							'operator' => '==',
							'value'    => 'item_menu',
						),
						array(
							'name'     => 'menu_content_type',
							'operator' => '==',
							'value'    => 'sub_menu',
						),
					),
				),
			)
		);

		$repeater->add_control(
			'content_saved_widgets',
			array(
				'label'     => __( 'Select Widget', 'soft-template-core' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => \Soft_template_Core_Utils::get_saved_data( 'widget' ),
				'default'   => '-1',
				'condition' => array(
					'menu_content_type' => 'saved_modules',
					'item_type'         => 'item_submenu',
				),
			)
		);

		$repeater->add_control(
			'content_saved_rows',
			array(
				'label'     => __( 'Select Section', 'soft-template-core' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => \Soft_template_Core_Utils::get_saved_data( 'section' ),
				'default'   => '-1',
				'condition' => array(
					'menu_content_type' => 'saved_rows',
					'item_type'         => 'item_submenu',
				),
			)
		);

		$repeater->add_control(
			'dropdown_width',
			array(
				'label'     => __( 'Dropdown Width', 'soft-template-core' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'default',
				'options'   => array(
					'default'   => __( 'Default', 'soft-template-core' ),
					'custom'    => __( 'Custom', 'soft-template-core' ),
					'section'   => __( 'Equal to 	Section', 'soft-template-core' ),
					'container' => __( 'Equal to Container', 'soft-template-core' ),
					'column'    => __( 'Equal to 	Column', 'soft-template-core' ),
					'widget'    => __( 'Equal to 	Widget', 'soft-template-core' ),
				),
				'condition' => array(
					'item_type' => 'item_menu',
				),
			)
		);

		$repeater->add_control(
			'section_width',
			array(
				'label'     => __( 'Width (px)', 'soft-template-core' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'min' => 0,
						'max' => 1500,
					),
				),
				'default'   => array(
					'size' => '220',
					'unit' => 'px',
				),
				'selectors' => array(
					'{{WRAPPER}} {{CURRENT_ITEM}} ul.sub-menu' => 'width: {{SIZE}}{{UNIT}}',
				),
				'condition' => array(
					'dropdown_width' => 'custom',
					'item_type'      => 'item_menu',
				),
			)
		);

		$repeater->add_control(
			'dropdown_position',
			array(
				'label'     => __( 'Dropdown Position', 'soft-template-core' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'left',
				'options'   => array(
					'left'   => __( 'Left', 'soft-template-core' ),
					'center' => __( 'Center', 'soft-template-core' ),
					'right'  => __( 'Right', 'soft-template-core' ),
				),
				'condition' => array(
					'item_type'      => 'item_menu',
					'dropdown_width' => array( 'custom', 'default' ),
				),
			)
		);

		$this->add_control(
			'menu_items',
			array(
				'label'       => __( 'Menu Items', 'soft-template-core' ),
				'type'        => Controls_Manager::REPEATER,
				'show_label'  => true,
				'fields'      => $repeater->get_controls(),
				'default'     => array(
					array(
						'item_type' => 'item_menu',
						'text'      => __( 'Menu Item 1', 'soft-template-core' ),
					),
					array(
						'item_type' => 'item_submenu',
						'text'      => __( 'Sub Menu', 'soft-template-core' ),
					),
					array(
						'item_type' => 'item_menu',
						'text'      => __( 'Menu Item 2', 'soft-template-core' ),
					),
					array(
						'item_type' => 'item_submenu',
						'text'      => __( 'Sub Menu', 'soft-template-core' ),
					),
				),
				'title_field' => '{{{ text }}}',
				'separator'   => 'before',
				'condition'   => array(
					'menu_type' => 'custom',
				),
			)
		);

		$current_theme = wp_get_theme();

		if ( 'Twenty Twenty-One' === $current_theme->get( 'Name' ) ) {
			$this->add_control(
				'hide_twenty_twenty_one_theme_icons',
				array(
					'label'        => __( 'Hide + & - Sign', 'soft-template-core' ),
					'type'         => Controls_Manager::SWITCHER,
					'separator'    => 'before',
					'label_on'     => __( 'Yes', 'soft-template-core' ),
					'label_off'    => __( 'No', 'soft-template-core' ),
					'return_value' => 'yes',
					'default'      => 'no',
					'prefix_class' => 'stfe-nav-menu__theme-icon-',
					'condition'    => array(
						'menu_type' => 'wordpress_menu',
					),
				)
			);
		}

		$this->add_control(
			'schema_support',
			array(
				'label'        => __( 'Enable Schema Support', 'soft-template-core' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Yes', 'soft-template-core' ),
				'label_off'    => __( 'No', 'soft-template-core' ),
				'return_value' => 'yes',
				'default'      => 'no',
				'render_type'  => 'template',
			)
		);

		$this->end_controls_section();

			$this->start_controls_section(
				'section_layout',
				array(
					'label' => __( 'Layout', 'soft-template-core' ),
				)
			);

			$this->add_control(
				'layout',
				array(
					'label'   => __( 'Layout', 'soft-template-core' ),
					'type'    => Controls_Manager::SELECT,
					'default' => 'horizontal',
					'options' => array(
						'horizontal' => __( 'Horizontal', 'soft-template-core' ),
						'vertical'   => __( 'Vertical', 'soft-template-core' ),
						'expandible' => __( 'Expanded', 'soft-template-core' ),
						'flyout'     => __( 'Flyout', 'soft-template-core' ),
					),
				)
			);

			$this->add_control(
				'navmenu_align',
				array(
					'label'        => __( 'Alignment', 'soft-template-core' ),
					'type'         => Controls_Manager::CHOOSE,
					'options'      => array(
						'left'    => array(
							'title' => __( 'Left', 'soft-template-core' ),
							'icon'  => 'eicon-h-align-left',
						),
						'center'  => array(
							'title' => __( 'Center', 'soft-template-core' ),
							'icon'  => 'eicon-h-align-center',
						),
						'right'   => array(
							'title' => __( 'Right', 'soft-template-core' ),
							'icon'  => 'eicon-h-align-right',
						),
						'justify' => array(
							'title' => __( 'Justify', 'soft-template-core' ),
							'icon'  => 'eicon-h-align-stretch',
						),
					),
					'default'      => 'left',
					'condition'    => array(
						'layout' => array( 'horizontal', 'vertical' ),
					),
					'prefix_class' => 'stfe-nav-menu__align-',
					'render_type'  => 'template',
				)
			);

			$this->add_control(
				'flyout_layout',
				array(
					'label'     => __( 'Flyout Orientation', 'soft-template-core' ),
					'type'      => Controls_Manager::SELECT,
					'default'   => 'left',
					'options'   => array(
						'left'  => __( 'Left', 'soft-template-core' ),
						'right' => __( 'Right', 'soft-template-core' ),
					),
					'condition' => array(
						'layout' => 'flyout',
					),
				)
			);

			$this->add_control(
				'flyout_type',
				array(
					'label'       => __( 'Appear Effect', 'soft-template-core' ),
					'type'        => Controls_Manager::SELECT,
					'default'     => 'normal',
					'label_block' => false,
					'options'     => array(
						'normal' => __( 'Slide', 'soft-template-core' ),
						'push'   => __( 'Push', 'soft-template-core' ),
					),
					'render_type' => 'template',
					'condition'   => array(
						'layout' => 'flyout',
					),
				)
			);

			$this->add_responsive_control(
				'hamburger_align',
				array(
					'label'                => __( 'Hamburger Align', 'soft-template-core' ),
					'type'                 => Controls_Manager::CHOOSE,
					'default'              => 'center',
					'options'              => array(
						'left'   => array(
							'title' => __( 'Left', 'soft-template-core' ),
							'icon'  => 'eicon-h-align-left',
						),
						'center' => array(
							'title' => __( 'Center', 'soft-template-core' ),
							'icon'  => 'eicon-h-align-center',
						),
						'right'  => array(
							'title' => __( 'Right', 'soft-template-core' ),
							'icon'  => 'eicon-h-align-right',
						),
					),
					'selectors_dictionary' => array(
						'left'   => 'margin-right: auto',
						'center' => 'margin: 0 auto',
						'right'  => 'margin-left: auto',
					),
					'selectors'            => array(
						'{{WRAPPER}} .stfe-nav-menu__toggle' => '{{VALUE}}',
					),
					'default'              => 'center',
					'condition'            => array(
						'layout' => array( 'expandible', 'flyout' ),
					),
					'label_block'          => false,
				)
			);

			$this->add_responsive_control(
				'hamburger_menu_align',
				array(
					'label'        => __( 'Menu Items Align', 'soft-template-core' ),
					'type'         => Controls_Manager::CHOOSE,
					'options'      => array(
						'flex-start'    => array(
							'title' => __( 'Left', 'soft-template-core' ),
							'icon'  => 'eicon-h-align-left',
						),
						'center'        => array(
							'title' => __( 'Center', 'soft-template-core' ),
							'icon'  => 'eicon-h-align-center',
						),
						'flex-end'      => array(
							'title' => __( 'Right', 'soft-template-core' ),
							'icon'  => 'eicon-h-align-right',
						),
						'space-between' => array(
							'title' => __( 'Justify', 'soft-template-core' ),
							'icon'  => 'eicon-h-align-stretch',
						),
					),
					'default'      => 'space-between',
					'condition'    => array(
						'layout' => array( 'expandible', 'flyout' ),
					),
					'selectors'    => array(
						'{{WRAPPER}} li.menu-item a' => 'justify-content: {{VALUE}};',
					),
					'prefix_class' => 'stfe-menu-item-',
				)
			);

			$this->add_control(
				'show_submenu_on',
				array(
					'label'        => __( 'Show Submenu On', 'soft-template-core' ),
					'type'         => Controls_Manager::SELECT,
					'default'      => 'hover',
					'options'      => array(
						'hover' => __( 'Hover', 'soft-template-core' ),
						'click' => __( 'Click', 'soft-template-core' ),
					),
					'condition'    => array(
						'layout' => 'horizontal',
					),
					'prefix_class' => 'stfe-submenu-open-',
					'render_type'  => 'template',
				)
			);

			$this->add_control(
				'submenu_icon',
				array(
					'label'        => __( 'Submenu Icon', 'soft-template-core' ),
					'type'         => Controls_Manager::SELECT,
					'default'      => 'arrow',
					'options'      => array(
						'arrow'   => __( 'Arrows', 'soft-template-core' ),
						'plus'    => __( 'Plus Sign', 'soft-template-core' ),
						'classic' => __( 'Classic', 'soft-template-core' ),
					),
					'condition'    => array(
						'menu_type' => array( 'custom', 'wordpress_menu' ),
					),
					'prefix_class' => 'stfe-submenu-icon-',
				)
			);

			$this->add_control(
				'submenu_animation',
				array(
					'label'        => __( 'Submenu Animation', 'soft-template-core' ),
					'type'         => Controls_Manager::SELECT,
					'default'      => 'none',
					'options'      => array(
						'none'     => __( 'Default', 'soft-template-core' ),
						'slide_up' => __( 'Slide Up', 'soft-template-core' ),
					),
					'condition'    => array(
						'menu_type' => array( 'custom', 'wordpress_menu' ),
					),
					'prefix_class' => 'stfe-submenu-animation-',
					'condition'    => array(
						'layout'          => 'horizontal',
						'show_submenu_on' => 'hover',
					),
				)
			);

			$this->add_control(
				'link_redirect',
				array(
					'label'        => __( 'Action On Menu Click', 'soft-template-core' ),
					'type'         => Controls_Manager::SELECT,
					'default'      => 'child',
					'description'  => __( 'For Horizontal layout, this will affect on the selected breakpoint.', 'soft-template-core' ),
					'options'      => array(
						'child'     => __( 'Open Submenu', 'soft-template-core' ),
						'self_link' => __( 'Redirect To Self Link', 'soft-template-core' ),
					),
					'prefix_class' => 'stfe-link-redirect-',
				)
			);

			$this->add_control(
				'heading_responsive',
				array(
					'type'      => Controls_Manager::HEADING,
					'label'     => __( 'Responsive', 'soft-template-core' ),
					'separator' => 'before',
					'condition' => array(
						'layout' => array( 'horizontal', 'vertical' ),
					),
				)
			);

		$this->add_control(
			'dropdown',
			array(
				'label'        => __( 'Breakpoint', 'soft-template-core' ),
				'type'         => Controls_Manager::SELECT,
				'default'      => 'tablet',
				'options'      => array(
					'mobile' => __( 'Mobile (768px >)', 'soft-template-core' ),
					'tablet' => __( 'Tablet (1025px >)', 'soft-template-core' ),
					'none'   => __( 'None', 'soft-template-core' ),
				),
				'prefix_class' => 'stfe-nav-menu__breakpoint-',
				'condition'    => array(
					'layout' => array( 'horizontal', 'vertical' ),
				),
				'render_type'  => 'template',
			)
		);

		$this->add_control(
			'resp_align',
			array(
				'label'       => __( 'Alignment', 'soft-template-core' ),
				'type'        => Controls_Manager::CHOOSE,
				'options'     => array(
					'left'   => array(
						'title' => __( 'Left', 'soft-template-core' ),
						'icon'  => 'eicon-h-align-left',
					),
					'center' => array(
						'title' => __( 'Center', 'soft-template-core' ),
						'icon'  => 'eicon-h-align-center',
					),
					'right'  => array(
						'title' => __( 'Right', 'soft-template-core' ),
						'icon'  => 'eicon-h-align-right',
					),
				),
				'default'     => 'center',
				'description' => __( 'This is the alignement of menu icon on selected responsive breakpoints.', 'soft-template-core' ),
				'condition'   => array(
					'layout'    => array( 'horizontal', 'vertical' ),
					'dropdown!' => 'none',
				),
				'selectors'   => array(
					'{{WRAPPER}} .stfe-nav-menu__toggle' => 'text-align: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'full_width_dropdown',
			array(
				'label'        => __( 'Full Width', 'soft-template-core' ),
				'description'  => __( 'Enable this option to stretch the Sub Menu to Full Width.', 'soft-template-core' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Yes', 'soft-template-core' ),
				'label_off'    => __( 'No', 'soft-template-core' ),
				'return_value' => 'yes',
				'default'      => 'yes',
				'condition'    => array(
					'layout!'   => 'flyout',
					'dropdown!' => 'none',
				),
				'render_type'  => 'template',
			)
		);

		$this->add_control(
			'toggle_layout_heading',
			array(
				'label'     => __( 'Toggle Button', 'soft-template-core' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => array(
					'dropdown!' => 'none',
				),
			)
		);

		$this->add_control(
			'toggle_label_show',
			array(
				'label'        => __( 'Show Label', 'soft-template-core' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Yes', 'soft-template-core' ),
				'label_off'    => __( 'No', 'soft-template-core' ),
				'return_value' => 'yes',
				'default'      => 'no',
				'render_type'  => 'template',
				'prefix_class' => 'stfe-nav-menu-toggle-label-',
				'condition'    => array(
					'dropdown!' => 'none',
				),
			)
		);

		$this->add_control(
			'toggle_label_text',
			array(
				'label'       => __( 'Label Text', 'soft-template-core' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => __( 'Menu', 'soft-template-core' ),
				'placeholder' => __( 'Type your label text', 'soft-template-core' ),
				'condition'   => array(
					'toggle_label_show' => 'yes',
					'dropdown!'         => 'none',
				),
			)
		);

		$this->add_control(
			'toggle_label_align',
			array(
				'label'                => __( 'Label Position', 'soft-template-core' ),
				'type'                 => Controls_Manager::SELECT,
				'options'              => array(
					'left'  => __( 'Before Icon', 'soft-template-core' ),
					'right' => __( 'After Icon', 'soft-template-core' ),
				),
				'default'              => 'right',
				'prefix_class'         => 'stfe-nav-menu-label-align-',
				'selectors_dictionary' => array(
					'left'  => 'flex-direction: row-reverse',
					'right' => 'flex-direction: row',
				),
				'selectors'            => array(
					'{{WRAPPER}}.stfe-nav-menu-toggle-label-yes .stfe-nav-menu__toggle' => '{{VALUE}}',
				),
				'condition'            => array(
					'toggle_label_show' => 'yes',
					'dropdown!'         => 'none',
				),
			)
		);

        $this->add_control(
            'dropdown_icon',
            array(
                'label'       => __( 'Menu Icon', 'soft-template-core' ),
                'type'        => Controls_Manager::ICONS,
                'label_block' => 'true',
                'default'     => array(
                    'value'   => 'fas fa-align-justify',
                    'library' => 'fa-solid',
                ),
                'condition'   => array(
                    'dropdown!' => 'none',
                ),
            )
        );

        $this->add_control(
            'dropdown_close_icon',
            array(
                'label'       => __( 'Close Icon', 'soft-template-core' ),
                'type'        => Controls_Manager::ICONS,
                'label_block' => 'true',
                'default'     => array(
                    'value'   => 'far fa-window-close',
                    'library' => 'fa-regular',
                ),
                'condition'   => array(
                    'dropdown!' => 'none',
                ),
            )
        );

		$this->end_controls_section();
    }

    /**
	 * Register Nav Menu General Controls.
	 *
	 * @since 1.21.0
	 * @access protected
	 */
	protected function register_style_content_controls() {

		$this->start_controls_section(
			'section_style_main-menu',
			array(
				'label'     => __( 'Main Menu', 'soft-template-core' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'layout!' => 'expandible',
				),
			)
		);

			$this->add_responsive_control(
				'width_flyout_menu_item',
				array(
					'label'       => __( 'Flyout Box Width', 'soft-template-core' ),
					'type'        => Controls_Manager::SLIDER,
					'range'       => array(
						'px' => array(
							'max' => 500,
							'min' => 100,
						),
					),
					'default'     => array(
						'size' => '300',
						'unit' => 'px',
					),
					'selectors'   => array(
						'{{WRAPPER}} .stfe-flyout-wrapper .stfe-side' => 'width: {{SIZE}}{{UNIT}}',
						'{{WRAPPER}} .stfe-flyout-open.left'     => 'left: -{{SIZE}}{{UNIT}}',
						'{{WRAPPER}} .stfe-flyout-open.right'    => 'right: -{{SIZE}}{{UNIT}}',
					),
					'condition'   => array(
						'layout' => 'flyout',
					),
					'render_type' => 'template',
				)
			);

			$this->add_responsive_control(
				'padding_flyout_menu_item',
				array(
					'label'     => __( 'Flyout Box Padding', 'soft-template-core' ),
					'type'      => Controls_Manager::SLIDER,
					'range'     => array(
						'px' => array(
							'max' => 50,
						),
					),
					'default'   => array(
						'size' => 30,
						'unit' => 'px',
					),
					'selectors' => array(
						'{{WRAPPER}} .stfe-flyout-content' => 'padding: {{SIZE}}{{UNIT}}',
					),
					'condition' => array(
						'layout' => 'flyout',
					),
				)
			);

			$this->add_responsive_control(
				'padding_horizontal_menu_item',
				array(
					'label'       => __( 'Horizontal Padding', 'soft-template-core' ),
					'type'        => Controls_Manager::SLIDER,
					'size_units'  => array( 'px' ),
					'range'       => array(
						'px' => array(
							'max' => 50,
						),
					),
					'default'     => array(
						'size' => 15,
						'unit' => 'px',
					),
					'selectors'   => array(
						'{{WRAPPER}} .menu-item a.stfe-menu-item,{{WRAPPER}} .menu-item a.stfe-sub-menu-item' => 'padding-left: {{SIZE}}{{UNIT}}; padding-right: {{SIZE}}{{UNIT}}',
					),
					'render_type' => 'template',
				)
			);

			$this->add_responsive_control(
				'padding_vertical_menu_item',
				array(
					'label'       => __( 'Vertical Padding', 'soft-template-core' ),
					'type'        => Controls_Manager::SLIDER,
					'size_units'  => array( 'px' ),
					'range'       => array(
						'px' => array(
							'max' => 50,
						),
					),
					'default'     => array(
						'size' => 15,
						'unit' => 'px',
					),
					'selectors'   => array(
						'{{WRAPPER}} .menu-item a.stfe-menu-item, {{WRAPPER}} .menu-item a.stfe-sub-menu-item' => 'padding-top: {{SIZE}}{{UNIT}}; padding-bottom: {{SIZE}}{{UNIT}};',
					),
					'render_type' => 'template',
				)
			);

			$this->add_responsive_control(
				'menu_space_between',
				array(
					'label'       => __( 'Space Between', 'soft-template-core' ),
					'type'        => Controls_Manager::SLIDER,
					'size_units'  => array( 'px' ),
					'range'       => array(
						'px' => array(
							'max' => 100,
						),
					),
					'selectors'   => array(
						'body:not(.rtl) {{WRAPPER}} .stfe-nav-menu__layout-horizontal .stfe-nav-menu > li.menu-item:not(:last-child)' => 'margin-right: {{SIZE}}{{UNIT}}',
						'body.rtl {{WRAPPER}} .stfe-nav-menu__layout-horizontal .stfe-nav-menu > li.menu-item:not(:last-child)' => 'margin-left: {{SIZE}}{{UNIT}}',
						'{{WRAPPER}} nav:not(.stfe-nav-menu__layout-horizontal) .stfe-nav-menu > li.menu-item:not(:last-child)' => 'margin-bottom: 0',
						'(tablet)body:not(.rtl) {{WRAPPER}}.stfe-nav-menu__breakpoint-tablet .stfe-nav-menu__layout-horizontal .stfe-nav-menu > li.menu-item:not(:last-child)' => 'margin-right: 0px',
						'(mobile)body:not(.rtl) {{WRAPPER}}.stfe-nav-menu__breakpoint-mobile .stfe-nav-menu__layout-horizontal .stfe-nav-menu > li.menu-item:not(:last-child)' => 'margin-right: 0px',
					),
					'render_type' => 'template',
					'condition'   => array(
						'layout' => 'horizontal',
					),
				)
			);

			$this->add_responsive_control(
				'menu_row_space',
				array(
					'label'       => __( 'Row Spacing', 'soft-template-core' ),
					'type'        => Controls_Manager::SLIDER,
					'size_units'  => array( 'px' ),
					'range'       => array(
						'px' => array(
							'max' => 100,
						),
					),
					'selectors'   => array(
						'body:not(.rtl) {{WRAPPER}} .stfe-nav-menu__layout-horizontal .stfe-nav-menu > li.menu-item' => 'margin-bottom: {{SIZE}}{{UNIT}}',
					),
					'condition'   => array(
						'layout' => 'horizontal',
					),
					'render_type' => 'template',
				)
			);

			$this->add_responsive_control(
				'menu_top_space',
				array(
					'label'       => __( 'Menu Item Top Spacing', 'soft-template-core' ),
					'type'        => Controls_Manager::SLIDER,
					'size_units'  => array( 'px', '%' ),
					'range'       => array(
						'px' => array(
							'max' => 100,
						),
					),
					'selectors'   => array(
						'{{WRAPPER}} .stfe-flyout-wrapper .stfe-nav-menu > li.menu-item:first-child' => 'margin-top: {{SIZE}}{{UNIT}}',
					),
					'condition'   => array(
						'layout' => 'flyout',
					),
					'render_type' => 'template',
				)
			);

			$this->add_control(
				'bg_color_flyout',
				array(
					'label'     => __( 'Background Color', 'soft-template-core' ),
					'type'      => Controls_Manager::COLOR,
					'default'   => '#FFFFFF',
					'selectors' => array(
						'{{WRAPPER}} .stfe-flyout-content' => 'background-color: {{VALUE}}',
					),
					'condition' => array(
						'layout' => 'flyout',
					),
				)
			);

			$this->add_control(
				'pointer',
				array(
					'label'     => __( 'Link Hover Effect', 'soft-template-core' ),
					'type'      => Controls_Manager::SELECT,
					'default'   => 'none',
					'options'   => array(
						'none'        => __( 'None', 'soft-template-core' ),
						'underline'   => __( 'Underline', 'soft-template-core' ),
						'overline'    => __( 'Overline', 'soft-template-core' ),
						'double-line' => __( 'Double Line', 'soft-template-core' ),
						'framed'      => __( 'Framed', 'soft-template-core' ),
						'text'        => __( 'Text', 'soft-template-core' ),
					),
					'condition' => array(
						'layout' => array( 'horizontal' ),
					),
				)
			);

		$this->add_control(
			'animation_line',
			array(
				'label'     => __( 'Animation', 'soft-template-core' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'fade',
				'options'   => array(
					'fade'     => 'Fade',
					'slide'    => 'Slide',
					'grow'     => 'Grow',
					'drop-in'  => 'Drop In',
					'drop-out' => 'Drop Out',
					'none'     => 'None',
				),
				'condition' => array(
					'layout'  => array( 'horizontal' ),
					'pointer' => array( 'underline', 'overline', 'double-line' ),
				),
			)
		);

		$this->add_control(
			'animation_framed',
			array(
				'label'     => __( 'Frame Animation', 'soft-template-core' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'fade',
				'options'   => array(
					'fade'    => 'Fade',
					'grow'    => 'Grow',
					'shrink'  => 'Shrink',
					'draw'    => 'Draw',
					'corners' => 'Corners',
					'none'    => 'None',
				),
				'condition' => array(
					'layout'  => array( 'horizontal' ),
					'pointer' => 'framed',
				),
			)
		);

		$this->add_control(
			'animation_text',
			array(
				'label'     => __( 'Animation', 'soft-template-core' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'grow',
				'options'   => array(
					'grow'   => 'Grow',
					'shrink' => 'Shrink',
					'sink'   => 'Sink',
					'float'  => 'Float',
					'skew'   => 'Skew',
					'rotate' => 'Rotate',
					'none'   => 'None',
				),
				'condition' => array(
					'layout'  => array( 'horizontal' ),
					'pointer' => 'text',
				),
			)
		);

		$this->add_control(
			'style_divider',
			array(
				'type' => Controls_Manager::DIVIDER,
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'      => 'menu_typography',
				'global'    => array(
					'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
				),
				'separator' => 'before',
				'selector'  => '{{WRAPPER}} .menu-item a.stfe-menu-item',
			)
		);

		$this->start_controls_tabs( 'tabs_menu_item_style' );

				$this->start_controls_tab(
					'tab_menu_item_normal',
					array(
						'label' => __( 'Normal', 'soft-template-core' ),
					)
				);

					$this->add_control(
						'color_menu_item',
						array(
							'label'     => __( 'Text Color', 'soft-template-core' ),
							'type'      => Controls_Manager::COLOR,
							'global'    => array(
								'default' => Global_Colors::COLOR_TEXT,
							),
							'default'   => '',
							'selectors' => array(
								'{{WRAPPER}} .menu-item a.stfe-menu-item, {{WRAPPER}} .sub-menu a.stfe-sub-menu-item' => 'color: {{VALUE}}',
							),
						)
					);

					$this->add_control(
						'bg_color_menu_item',
						array(
							'label'     => __( 'Background Color', 'soft-template-core' ),
							'type'      => Controls_Manager::COLOR,
							'default'   => '',
							'selectors' => array(
								'{{WRAPPER}} .menu-item a.stfe-menu-item, {{WRAPPER}} .sub-menu, {{WRAPPER}} nav.stfe-dropdown, {{WRAPPER}} .stfe-dropdown-expandible' => 'background-color: {{VALUE}}',
							),
							'condition' => array(
								'layout!' => 'flyout',
							),
						)
					);

				$this->end_controls_tab();

				$this->start_controls_tab(
					'tab_menu_item_hover',
					array(
						'label' => __( 'Hover', 'soft-template-core' ),
					)
				);

					$this->add_control(
						'color_menu_item_hover',
						array(
							'label'     => __( 'Text Color', 'soft-template-core' ),
							'type'      => Controls_Manager::COLOR,
							'global'    => array(
								'default' => Global_Colors::COLOR_ACCENT,
							),
							'selectors' => array(
								'{{WRAPPER}} .menu-item a.stfe-menu-item:hover,
								{{WRAPPER}} .sub-menu a.stfe-sub-menu-item:hover,
								{{WRAPPER}} .menu-item.current-menu-item a.stfe-menu-item,
								{{WRAPPER}} .menu-item a.stfe-menu-item.highlighted,
								{{WRAPPER}} .menu-item a.stfe-menu-item:focus' => 'color: {{VALUE}}',
							),
						)
					);

					$this->add_control(
						'bg_color_menu_item_hover',
						array(
							'label'     => __( 'Background Color', 'soft-template-core' ),
							'type'      => Controls_Manager::COLOR,
							'selectors' => array(
								'{{WRAPPER}} .menu-item a.stfe-menu-item:hover,
								{{WRAPPER}} .sub-menu a.stfe-sub-menu-item:hover,
								{{WRAPPER}} .menu-item.current-menu-item a.stfe-menu-item,
								{{WRAPPER}} .menu-item a.stfe-menu-item.highlighted,
								{{WRAPPER}} .menu-item a.stfe-menu-item:focus' => 'background-color: {{VALUE}}',
							),
							'condition' => array(
								'layout!' => 'flyout',
							),
						)
					);

					$this->add_control(
						'pointer_color_menu_item_hover',
						array(
							'label'     => __( 'Link Hover Effect Color', 'soft-template-core' ),
							'type'      => Controls_Manager::COLOR,
							'global'    => array(
								'default' => Global_Colors::COLOR_ACCENT,
							),
							'default'   => '',
							'selectors' => array(
								'{{WRAPPER}} .stfe-nav-menu-layout:not(.stfe-pointer__framed) .menu-item.parent a.stfe-menu-item:before,
								{{WRAPPER}} .stfe-nav-menu-layout:not(.stfe-pointer__framed) .menu-item.parent a.stfe-menu-item:after' => 'background-color: {{VALUE}}',
								'{{WRAPPER}} .stfe-nav-menu-layout:not(.stfe-pointer__framed) .menu-item.parent .sub-menu .stfe-has-submenu-container a:after' => 'background-color: unset',
								'{{WRAPPER}} .stfe-pointer__framed .menu-item.parent a.stfe-menu-item:before,
								{{WRAPPER}} .stfe-pointer__framed .menu-item.parent a.stfe-menu-item:after' => 'border-color: {{VALUE}}',
							),
							'condition' => array(
								'pointer!' => array( 'none', 'text' ),
								'layout!'  => 'flyout',
							),
						)
					);

				$this->end_controls_tab();

				$this->start_controls_tab(
					'tab_menu_item_active',
					array(
						'label' => __( 'Active', 'soft-template-core' ),
					)
				);

					$this->add_control(
						'color_menu_item_active',
						array(
							'label'     => __( 'Text Color', 'soft-template-core' ),
							'type'      => Controls_Manager::COLOR,
							'default'   => '',
							'selectors' => array(
								'{{WRAPPER}} .menu-item.current-menu-item a.stfe-menu-item,
								{{WRAPPER}} .menu-item.current-menu-ancestor a.stfe-menu-item,
								{{WRAPPER}} .menu-item.custom-menu-active a.stfe-menu-item' => 'color: {{VALUE}}',
							),
						)
					);

					$this->add_control(
						'bg_color_menu_item_active',
						array(
							'label'     => __( 'Background Color', 'soft-template-core' ),
							'type'      => Controls_Manager::COLOR,
							'default'   => '',
							'selectors' => array(
								'{{WRAPPER}} .menu-item.current-menu-item a.stfe-menu-item,
								{{WRAPPER}} .menu-item.current-menu-ancestor a.stfe-menu-item,
								{{WRAPPER}} .menu-item.custom-menu-active a.stfe-menu-item' => 'background-color: {{VALUE}}',
							),
							'condition' => array(
								'layout!' => 'flyout',
							),
						)
					);

					$this->add_control(
						'pointer_color_menu_item_active',
						array(
							'label'     => __( 'Link Hover Effect Color', 'soft-template-core' ),
							'type'      => Controls_Manager::COLOR,
							'default'   => '',
							'selectors' => array(
								'{{WRAPPER}} .stfe-nav-menu-layout:not(.stfe-pointer__framed) .menu-item.parent.current-menu-item a.stfe-menu-item:before,
								{{WRAPPER}} .stfe-nav-menu-layout:not(.stfe-pointer__framed) .menu-item.parent.current-menu-item a.stfe-menu-item:after,
								{{WRAPPER}} .stfe-nav-menu-layout:not(.stfe-pointer__framed) .menu-item.parent.custom-menu-active a.stfe-menu-item:before,
								{{WRAPPER}} .stfe-nav-menu-layout:not(.stfe-pointer__framed) .menu-item.parent.custom-menu-active a.stfe-menu-item:after' => 'background-color: {{VALUE}}',
								'{{WRAPPER}} .stfe-nav-menu-layout:not(.stfe-pointer__framed) .menu-item.parent .sub-menu .stfe-has-submenu-container a.current-menu-item:after' => 'background-color: unset',
								'{{WRAPPER}} .stfe-pointer__framed .menu-item.parent.current-menu-item a.stfe-menu-item:before,
								{{WRAPPER}} .stfe-pointer__framed .menu-item.parent.current-menu-item a.stfe-menu-item:after, {{WRAPPER}} .stfe-pointer__framed .menu-item.parent.custom-menu-active a.stfe-menu-item:before,
								{{WRAPPER}} .stfe-pointer__framed .menu-item.parent.custom-menu-active a.stfe-menu-item:after' => 'border-color: {{VALUE}}',
							),
							'condition' => array(
								'pointer!' => array( 'none', 'text' ),
								'layout!'  => 'flyout',
							),
						)
					);

				$this->end_controls_tab();

			$this->end_controls_tabs();

		$this->end_controls_section();
    }

    /**
	 * Register Nav Menu General Controls.
	 *
	 * @since 1.21.0
	 * @access protected
	 */
	protected function register_dropdown_content_controls() {

		$this->start_controls_section(
			'section_style_dropdown',
			array(
				'label' => __( 'Dropdown', 'soft-template-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

			$this->add_control(
				'dropdown_description',
				array(
					'raw'             => __( '<b>Note:</b> On desktop, below style options will apply to the submenu. On mobile, this will apply to the entire menu.', 'soft-template-core' ),
					'type'            => Controls_Manager::RAW_HTML,
					'content_classes' => 'elementor-descriptor',
					'condition'       => array(
						'layout!' => array(
							'expandible',
							'flyout',
						),
					),
				)
			);

			$this->start_controls_tabs( 'tabs_dropdown_item_style' );

				$this->start_controls_tab(
					'tab_dropdown_item_normal',
					array(
						'label' => __( 'Normal', 'soft-template-core' ),
					)
				);

					$this->add_control(
						'color_dropdown_item',
						array(
							'label'     => __( 'Text Color', 'soft-template-core' ),
							'type'      => Controls_Manager::COLOR,
							'default'   => '',
							'selectors' => array(
								'{{WRAPPER}} .sub-menu a.stfe-sub-menu-item,
								{{WRAPPER}} .elementor-menu-toggle,
								{{WRAPPER}} nav.stfe-dropdown li a.stfe-menu-item,
								{{WRAPPER}} nav.stfe-dropdown li a.stfe-sub-menu-item,
								{{WRAPPER}} nav.stfe-dropdown-expandible li a.stfe-menu-item,
								{{WRAPPER}} nav.stfe-dropdown-expandible li a.stfe-sub-menu-item' => 'color: {{VALUE}}',
							),
						)
					);

					$this->add_control(
						'background_color_dropdown_item',
						array(
							'label'     => __( 'Background Color', 'soft-template-core' ),
							'type'      => Controls_Manager::COLOR,
							'default'   => '#fff',
							'selectors' => array(
								'{{WRAPPER}} .sub-menu,
								{{WRAPPER}} nav.stfe-dropdown,
								{{WRAPPER}} nav.stfe-dropdown-expandible,
								{{WRAPPER}} nav.stfe-dropdown .menu-item a.stfe-menu-item,
								{{WRAPPER}} nav.stfe-dropdown .menu-item a.stfe-sub-menu-item' => 'background-color: {{VALUE}}',
							),
							'separator' => 'none',
						)
					);

				$this->end_controls_tab();

				$this->start_controls_tab(
					'tab_dropdown_item_hover',
					array(
						'label' => __( 'Hover', 'soft-template-core' ),
					)
				);

					$this->add_control(
						'color_dropdown_item_hover',
						array(
							'label'     => __( 'Text Color', 'soft-template-core' ),
							'type'      => Controls_Manager::COLOR,
							'default'   => '',
							'selectors' => array(
								'{{WRAPPER}} .sub-menu a.stfe-sub-menu-item:hover,
								{{WRAPPER}} .elementor-menu-toggle:hover,
								{{WRAPPER}} nav.stfe-dropdown li a.stfe-menu-item:hover,
								{{WRAPPER}} nav.stfe-dropdown li a.stfe-sub-menu-item:hover,
								{{WRAPPER}} nav.stfe-dropdown-expandible li a.stfe-menu-item:hover,
								{{WRAPPER}} nav.stfe-dropdown-expandible li a.stfe-sub-menu-item:hover' => 'color: {{VALUE}}',
							),
						)
					);

					$this->add_control(
						'background_color_dropdown_item_hover',
						array(
							'label'     => __( 'Background Color', 'soft-template-core' ),
							'type'      => Controls_Manager::COLOR,
							'default'   => '',
							'selectors' => array(
								'{{WRAPPER}} .sub-menu a.stfe-sub-menu-item:hover,
								{{WRAPPER}} nav.stfe-dropdown li a.stfe-menu-item:hover,
								{{WRAPPER}} nav.stfe-dropdown li a.stfe-sub-menu-item:hover,
								{{WRAPPER}} nav.stfe-dropdown-expandible li a.stfe-menu-item:hover,
								{{WRAPPER}} nav.stfe-dropdown-expandible li a.stfe-sub-menu-item:hover' => 'background-color: {{VALUE}}',
							),
							'separator' => 'none',
						)
					);

				$this->end_controls_tab();

				$this->start_controls_tab(
					'tab_dropdown_item_active',
					array(
						'label' => __( 'Active', 'soft-template-core' ),
					)
				);

				$this->add_control(
					'color_dropdown_item_active',
					array(
						'label'     => __( 'Text Color', 'soft-template-core' ),
						'type'      => Controls_Manager::COLOR,
						'default'   => '',
						'selectors' => array(
							'{{WRAPPER}} .sub-menu .menu-item.current-menu-item a.stfe-sub-menu-item.stfe-sub-menu-item-active,
						{{WRAPPER}} nav.stfe-dropdown .menu-item.current-menu-item a.stfe-menu-item,
						{{WRAPPER}} nav.stfe-dropdown .menu-item.current-menu-ancestor a.stfe-menu-item,
						{{WRAPPER}} nav.stfe-dropdown .sub-menu .menu-item.current-menu-item a.stfe-sub-menu-item.stfe-sub-menu-item-active,
						{{WRAPPER}} .sub-menu .menu-item.custom-submenu-active a.stfe-sub-menu-item,
						{{WRAPPER}} nav.stfe-dropdown .menu-item.custom-menu-active a.stfe-menu-item' => 'color: {{VALUE}}',
						),
					)
				);

				$this->add_control(
					'background_color_dropdown_item_active',
					array(
						'label'     => __( 'Background Color', 'soft-template-core' ),
						'type'      => Controls_Manager::COLOR,
						'default'   => '',
						'selectors' => array(
							'{{WRAPPER}} .sub-menu .menu-item.current-menu-item a.stfe-sub-menu-item.stfe-sub-menu-item-active,
							{{WRAPPER}} nav.stfe-dropdown .menu-item.current-menu-item a.stfe-menu-item,
							{{WRAPPER}} nav.stfe-dropdown .menu-item.current-menu-ancestor a.stfe-menu-item,
							{{WRAPPER}} nav.stfe-dropdown .sub-menu .menu-item.current-menu-item a.stfe-sub-menu-item.stfe-sub-menu-item-active,
							{{WRAPPER}} .sub-menu .menu-item.custom-submenu-active a.stfe-sub-menu-item,
							{{WRAPPER}} nav.stfe-dropdown .menu-item.custom-menu-active a.stfe-menu-item' => 'background-color: {{VALUE}}',
						),
						'separator' => 'after',
					)
				);

				$this->end_controls_tabs();

			$this->end_controls_tabs();

			$this->add_group_control(
				Group_Control_Typography::get_type(),
				array(
					'name'      => 'dropdown_typography',
					'global'    => array(
						'default' => Global_Typography::TYPOGRAPHY_ACCENT,
					),
					'separator' => 'before',
					'exclude'   => array( 'line_height' ),
					'selector'  => '{{WRAPPER}} .sub-menu li a.stfe-sub-menu-item,
							{{WRAPPER}} nav.stfe-dropdown li a.stfe-menu-item,
							{{WRAPPER}} nav.stfe-dropdown li a.stfe-sub-menu-item,
							{{WRAPPER}} nav.stfe-dropdown-expandible li a.stfe-menu-item',
					'{{WRAPPER}} nav.stfe-dropdown-expandible li a.stfe-sub-menu-item',

				)
			);

			$this->add_group_control(
				Group_Control_Border::get_type(),
				array(
					'name'     => 'dropdown_border',
					'selector' => '{{WRAPPER}} nav.stfe-nav-menu__layout-horizontal .sub-menu,
							{{WRAPPER}} nav:not(.stfe-nav-menu__layout-horizontal) .sub-menu.sub-menu-open,
							{{WRAPPER}} nav.stfe-dropdown,
						 	{{WRAPPER}} nav.stfe-dropdown-expandible',
				)
			);

			$this->add_responsive_control(
				'dropdown_border_radius',
				array(
					'label'      => __( 'Border Radius', 'soft-template-core' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => array( 'px', '%' ),
					'selectors'  => array(
						'{{WRAPPER}} .sub-menu'         => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						'{{WRAPPER}} .sub-menu li.menu-item:first-child' => 'border-top-left-radius: {{TOP}}{{UNIT}}; border-top-right-radius: {{RIGHT}}{{UNIT}};overflow:hidden;',
						'{{WRAPPER}} .sub-menu li.menu-item:last-child' => 'border-bottom-right-radius: {{BOTTOM}}{{UNIT}}; border-bottom-left-radius: {{LEFT}}{{UNIT}};overflow:hidden',
						'{{WRAPPER}} nav.stfe-dropdown' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						'{{WRAPPER}} nav.stfe-dropdown li.menu-item:first-child' => 'border-top-left-radius: {{TOP}}{{UNIT}}; border-top-right-radius: {{RIGHT}}{{UNIT}};overflow:hidden',
						'{{WRAPPER}} nav.stfe-dropdown li.menu-item:last-child' => 'border-bottom-right-radius: {{BOTTOM}}{{UNIT}}; border-bottom-left-radius: {{LEFT}}{{UNIT}};overflow:hidden',
						'{{WRAPPER}} nav.stfe-dropdown-expandible' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						'{{WRAPPER}} nav.stfe-dropdown-expandible li.menu-item:first-child' => 'border-top-left-radius: {{TOP}}{{UNIT}}; border-top-right-radius: {{RIGHT}}{{UNIT}};overflow:hidden',
						'{{WRAPPER}} nav.stfe-dropdown-expandible li.menu-item:last-child' => 'border-bottom-right-radius: {{BOTTOM}}{{UNIT}}; border-bottom-left-radius: {{LEFT}}{{UNIT}};overflow:hidden',
					),
				)
			);

			$this->add_group_control(
				Group_Control_Box_Shadow::get_type(),
				array(
					'name'      => 'dropdown_box_shadow',
					'exclude'   => array(
						'box_shadow_position',
					),
					'selector'  => '{{WRAPPER}} .stfe-nav-menu .sub-menu,
								{{WRAPPER}} nav.stfe-dropdown,
						 		{{WRAPPER}} nav.stfe-dropdown-expandible',
					'separator' => 'after',
				)
			);

			$this->add_responsive_control(
				'width_dropdown_item',
				array(
					'label'       => __( 'Dropdown Width (px)', 'soft-template-core' ),
					'type'        => Controls_Manager::SLIDER,
					'range'       => array(
						'px' => array(
							'min' => 0,
							'max' => 500,
						),
					),
					'default'     => array(
						'size' => '220',
						'unit' => 'px',
					),
					'selectors'   => array(
						'{{WRAPPER}} ul.sub-menu' => 'width: {{SIZE}}{{UNIT}}',
					),
					'condition'   => array(
						'layout' => 'horizontal',
					),
					'render_type' => 'template',
				)
			);

			$this->add_responsive_control(
				'padding_horizontal_dropdown_item',
				array(
					'label'       => __( 'Horizontal Padding', 'soft-template-core' ),
					'type'        => Controls_Manager::SLIDER,
					'size_units'  => array( 'px' ),
					'default'     => array(
						'size' => 15,
						'unit' => 'px',
					),
					'selectors'   => array(
						'{{WRAPPER}} .sub-menu li a.stfe-sub-menu-item,
						{{WRAPPER}} nav.stfe-dropdown li a.stfe-menu-item,
						{{WRAPPER}} nav.stfe-dropdown li a.stfe-sub-menu-item,
						{{WRAPPER}} nav.stfe-dropdown-expandible li a.stfe-menu-item,
						{{WRAPPER}} nav.stfe-dropdown-expandible li a.stfe-sub-menu-item' => 'padding-left: {{SIZE}}{{UNIT}}; padding-right: {{SIZE}}{{UNIT}}',
					),
					'render_type' => 'template',
				)
			);

			$this->add_responsive_control(
				'padding_vertical_dropdown_item',
				array(
					'label'       => __( 'Vertical Padding', 'soft-template-core' ),
					'type'        => Controls_Manager::SLIDER,
					'size_units'  => array( 'px' ),
					'default'     => array(
						'size' => 15,
						'unit' => 'px',
					),
					'range'       => array(
						'px' => array(
							'max' => 50,
						),
					),
					'selectors'   => array(
						'{{WRAPPER}} .sub-menu a.stfe-sub-menu-item,
						 {{WRAPPER}} nav.stfe-dropdown li a.stfe-menu-item,
						 {{WRAPPER}} nav.stfe-dropdown li a.stfe-sub-menu-item,
						 {{WRAPPER}} nav.stfe-dropdown-expandible li a.stfe-menu-item,
						 {{WRAPPER}} nav.stfe-dropdown-expandible li a.stfe-sub-menu-item' => 'padding-top: {{SIZE}}{{UNIT}}; padding-bottom: {{SIZE}}{{UNIT}}',
					),
					'render_type' => 'template',
				)
			);

			$this->add_responsive_control(
				'distance_from_menu',
				array(
					'label'       => __( 'Top Distance', 'soft-template-core' ),
					'type'        => Controls_Manager::SLIDER,
					'range'       => array(
						'px' => array(
							'min' => -100,
							'max' => 100,
						),
					),
					'selectors'   => array(
						'{{WRAPPER}} nav.stfe-nav-menu__layout-horizontal ul.sub-menu, {{WRAPPER}} nav.stfe-nav-menu__layout-expandible.menu-is-active,
						{{WRAPPER}} .stfe-dropdown.menu-is-active' => 'margin-top: {{SIZE}}px;',
						'(tablet){{WRAPPER}}.stfe-nav-menu__breakpoint-tablet nav.stfe-nav-menu__layout-horizontal ul.sub-menu' => 'margin-top: 0px',
						'(mobile){{WRAPPER}}.stfe-nav-menu__breakpoint-mobile nav.stfe-nav-menu__layout-horizontal ul.sub-menu' => 'margin-top: 0px',
					),
					'condition'   => array(
						'layout' => array( 'horizontal', 'expandible' ),
					),
					'render_type' => 'template',
				)
			);

			$this->add_control(
				'heading_dropdown_divider',
				array(
					'label'     => __( 'Divider', 'soft-template-core' ),
					'type'      => Controls_Manager::HEADING,
					'separator' => 'before',
				)
			);

			$this->add_control(
				'dropdown_divider_border',
				array(
					'label'       => __( 'Border Style', 'soft-template-core' ),
					'type'        => Controls_Manager::SELECT,
					'default'     => 'solid',
					'label_block' => false,
					'options'     => array(
						'none'   => __( 'None', 'soft-template-core' ),
						'solid'  => __( 'Solid', 'soft-template-core' ),
						'double' => __( 'Double', 'soft-template-core' ),
						'dotted' => __( 'Dotted', 'soft-template-core' ),
						'dashed' => __( 'Dashed', 'soft-template-core' ),
					),
					'selectors'   => array(
						'{{WRAPPER}} .sub-menu li.menu-item:not(:last-child),
						{{WRAPPER}} nav.stfe-dropdown li.menu-item:not(:last-child),
						{{WRAPPER}} nav.stfe-dropdown-expandible li.menu-item:not(:last-child)' => 'border-bottom-style: {{VALUE}};',
					),
				)
			);
			$this->add_control(
				'divider_border_color',
				array(
					'label'     => __( 'Border Color', 'soft-template-core' ),
					'type'      => Controls_Manager::COLOR,
					'default'   => '#c4c4c4',
					'selectors' => array(
						'{{WRAPPER}} .sub-menu li.menu-item:not(:last-child),
						{{WRAPPER}} nav.stfe-dropdown li.menu-item:not(:last-child),
						{{WRAPPER}} nav.stfe-dropdown-expandible li.menu-item:not(:last-child)' => 'border-bottom-color: {{VALUE}};',
					),
					'condition' => array(
						'dropdown_divider_border!' => 'none',
					),
				)
			);

			$this->add_control(
				'dropdown_divider_width',
				array(
					'label'     => __( 'Border Width', 'soft-template-core' ),
					'type'      => Controls_Manager::SLIDER,
					'range'     => array(
						'px' => array(
							'max' => 50,
						),
					),
					'default'   => array(
						'size' => '1',
						'unit' => 'px',
					),
					'selectors' => array(
						'{{WRAPPER}} .sub-menu li.menu-item:not(:last-child),
						{{WRAPPER}} nav.stfe-dropdown li.menu-item:not(:last-child),
						{{WRAPPER}} nav.stfe-dropdown-expandible li.menu-item:not(:last-child)' => 'border-bottom-width: {{SIZE}}{{UNIT}}',
					),
					'condition' => array(
						'dropdown_divider_border!' => 'none',
					),
				)
			);

		$this->end_controls_section();

		$this->start_controls_section(
			'style_toggle',
			array(
				'label' => __( 'Menu Trigger & Close Icon', 'soft-template-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->start_controls_tabs( 'tabs_toggle_style' );

		$this->start_controls_tab(
			'toggle_style_normal',
			array(
				'label' => __( 'Normal', 'soft-template-core' ),
			)
		);

		$this->add_control(
			'toggle_color',
			array(
				'label'     => __( 'Color', 'soft-template-core' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} div.stfe-nav-menu-icon' => 'color: {{VALUE}}',
					'{{WRAPPER}} div.stfe-nav-menu-icon svg' => 'fill: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'toggle_background_color',
			array(
				'label'     => __( 'Background Color', 'soft-template-core' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .stfe-nav-menu-icon' => 'background-color: {{VALUE}}; padding: 0.35em;',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'toggle_hover',
			array(
				'label' => __( 'Hover', 'soft-template-core' ),
			)
		);

		$this->add_control(
			'toggle_hover_color',
			array(
				'label'     => __( 'Color', 'soft-template-core' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} div.stfe-nav-menu-icon:hover' => 'color: {{VALUE}}',
					'{{WRAPPER}} div.stfe-nav-menu-icon:hover svg' => 'fill: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'toggle_hover_background_color',
			array(
				'label'     => __( 'Background Color', 'soft-template-core' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .stfe-nav-menu-icon:hover' => 'background-color: {{VALUE}}; padding: 0.35em;',
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_responsive_control(
			'toggle_size',
			array(
				'label'     => __( 'Icon Size', 'soft-template-core' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'min' => 15,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .stfe-nav-menu-icon'     => 'font-size: {{SIZE}}{{UNIT}}',
					'{{WRAPPER}} .stfe-nav-menu-icon svg' => 'font-size: {{SIZE}}px;line-height: {{SIZE}}px;height: {{SIZE}}px;width: {{SIZE}}px;',
				),
				'separator' => 'before',
			)
		);

		$this->add_responsive_control(
			'toggle_border_width',
			array(
				'label'     => __( 'Border Width', 'soft-template-core' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'max' => 10,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .stfe-nav-menu-icon' => 'border-width: {{SIZE}}{{UNIT}}; padding: 0.35em;',
				),
			)
		);

		$this->add_responsive_control(
			'toggle_border_radius',
			array(
				'label'      => __( 'Border Radius', 'soft-template-core' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .stfe-nav-menu-icon' => 'border-radius: {{SIZE}}{{UNIT}}',
				),
			)
		);

		$this->add_control(
			'close_color_flyout',
			array(
				'label'     => __( 'Close Icon Color', 'soft-template-core' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#7A7A7A',
				'selectors' => array(
					'{{WRAPPER}} .stfe-flyout-close'     => 'color: {{VALUE}}',
					'{{WRAPPER}} .stfe-flyout-close svg' => 'fill: {{VALUE}}',
				),
				'condition' => array(
					'layout' => 'flyout',
				),
				'separator' => 'before',
			)
		);

		$this->add_responsive_control(
			'close_flyout_size',
			array(
				'label'     => __( 'Close Icon Size', 'soft-template-core' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'min' => 15,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .stfe-flyout-close svg, {{WRAPPER}} .stfe-flyout-close' => 'height: {{SIZE}}px; width: {{SIZE}}px; font-size: {{SIZE}}px; line-height: {{SIZE}}px;',
				),
				'condition' => array(
					'layout' => 'flyout',
				),
			)
		);

		$this->add_control(
			'toggle_styles_heading',
			array(
				'label'     => __( 'Label', 'soft-template-core' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => array(
					'toggle_label_show' => 'yes',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'      => 'toggle_label_typography',
				'label'     => __( 'Typography', 'soft-template-core' ),
				'selector'  => '{{WRAPPER}} .stfe-nav-menu__toggle .stfe-nav-menu-label',
				'condition' => array(
					'toggle_label_show' => 'yes',
				),
			)
		);

		$this->add_control(
			'toggle_label_color',
			array(
				'label'     => __( 'Text Color', 'soft-template-core' ),
				'type'      => Controls_Manager::COLOR,
				'global'    => array(
					'default' => Global_Colors::COLOR_TEXT,
				),
				'selectors' => array(
					'{{WRAPPER}} .stfe-nav-menu__toggle .stfe-nav-menu-label' => 'color: {{VALUE}}',
				),
				'condition' => array(
					'toggle_label_show' => 'yes',
				),
			)
		);

		$this->add_control(
			'toggle_label_spacing',
			array(
				'label'      => __( 'Spacing', 'soft-template-core' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%' ),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 100,
					),
					'%'  => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'default'    => array(
					'unit' => 'px',
					'size' => 10,
				),
				'selectors'  => array(
					'{{WRAPPER}}.stfe-nav-menu-label-align-left .stfe-nav-menu__toggle .stfe-nav-menu-label' => 'margin-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.stfe-nav-menu-label-align-right .stfe-nav-menu__toggle .stfe-nav-menu-label' => 'margin-left: {{SIZE}}{{UNIT}};',
				),
				'condition'  => array(
					'toggle_label_show' => 'yes',
				),
			)
		);

		$this->end_controls_section();
	}

    /**
	 * Get available menus list
	 *
	 * @return array
	 */
	public function get_available_menus() {

		$raw_menus = wp_get_nav_menus();
		$menus     = wp_list_pluck( $raw_menus, 'name', 'term_id' );

		return $menus;
	}

    /**
	 * Render content type list.
	 *
	 * @since 1.21.0
	 * @return array Array of content type
	 * @access public
	 */
	public function get_content_type() {

		$content_type = array(
			'sub_menu'   => __( 'Text', 'soft-template-core' ),
			'saved_rows' => __( 'Saved Section', 'soft-template-core' ),
		);

		if ( defined( 'ELEMENTOR_PRO_VERSION' ) ) {
			$content_type['saved_modules'] = __( 'Saved Widget', 'soft-template-core' );
		}

		return $content_type;
	}

    /**
	 * Render custom style.
	 *
	 * @since 1.21.0
	 * @access public
	 */
	public function get_custom_style() {
		$settings         = $this->get_settings_for_display();
		$i                = 0;
		$output           = ' ';
		$is_sub_menu_item = false;

		$this->add_render_attribute(
			'stfe-nav-menu-custom',
			'class',
			'stfe-nav-menu stfe-nav-menu-custom stfe-custom-wrapper'
		);
		?>
		<nav <?php echo wp_kses_post( $this->get_render_attribute_string( 'stfe-nav-menu' ) ); ?>>
			<?php
			$output      .= '<ul ' . $this->get_render_attribute_string( 'stfe-nav-menu-custom' ) . '>';
				$i        = 0;
				$is_child = false;
			foreach ( $settings['menu_items'] as $menu => $item ) {
				$repeater_sub_menu_item = $this->get_repeater_setting_key( 'text', 'menu_items', $menu );
				$repeater_link          = $this->get_repeater_setting_key( 'link', 'menu_items', $menu );

				if ( ! empty( $item['link']['url'] ) ) {

					$this->add_render_attribute( $repeater_link, 'href', $item['link']['url'] );
					if ( $item['link']['is_external'] ) {

						$this->add_render_attribute( $repeater_link, 'target', '_blank' );
					}
					if ( $item['link']['nofollow'] ) {

						$this->add_render_attribute( $repeater_link, 'rel', 'nofollow' );
					}
				}

				if ( 'yes' === $settings['schema_support'] ) {

					$this->add_render_attribute( $repeater_link, 'itemprop', 'url' );
					$this->add_render_attribute( 'menu-sub-item' . $item['_id'], 'itemprop', 'name' );
					$this->add_render_attribute( 'menu-item' . $item['_id'], 'itemprop', 'name' );
				}

				if ( 'item_submenu' === $item['item_type'] ) {
					if ( false === $is_child ) {
						$output .= "<ul class='sub-menu parent-do-not-have-template'>";
					}
					if ( 'sub_menu' === $item['menu_content_type'] ) {
							$this->add_render_attribute(
								'menu-sub-item' . $item['_id'],
								'class',
								'menu-item child menu-item-has-children elementor-repeater elementor-repeater-item-' . $item['_id']
							);

							$output .= '<li ' . $this->get_render_attribute_string( 'menu-sub-item' . $item['_id'] ) . '>';
							$output .= '<a ' . $this->get_render_attribute_string( $repeater_link ) . " class='stfe-sub-menu-item'>" . $this->get_render_attribute_string( $repeater_sub_menu_item ) . $item['text'] . '</a>';
							$output .= '</li>';
					} else {
							$this->add_render_attribute(
								'menu-content-item' . $item['_id'],
								'class',
								'menu-item saved-content child elementor-repeater elementor-repeater-item-' . $item['_id']
							);

							$output .= '<div ' . $this->get_render_attribute_string( 'menu-content-item' . $item['_id'] ) . '>';

						if ( 'saved_rows' === $item['menu_content_type'] ) {
							$saved_section_shortcode =  soft_template_core()->elementor_front()->get_builder_content_for_display( apply_filters( 'wpml_object_id', $item['content_saved_rows'], 'page' ) );
							$output  .= do_shortcode( $saved_section_shortcode );
						} elseif ( 'saved_modules' === $item['menu_content_type'] ) {
							$saved_widget_shortcode = soft_template_core()->elementor_front()->get_builder_content_for_display( $item['content_saved_widgets'] );
							$output .= do_shortcode( $saved_widget_shortcode );
						}
							$output .= '</div>';
					}
					$is_child         = true;
					$is_sub_menu_item = true;
				} else {
						$this->add_render_attribute( 'menu-item' . $item['_id'], 'class', 'menu-item menu-item-has-children parent parent-has-no-child elementor-repeater-item-' . $item['_id'] );
						$this->add_render_attribute( 'menu-item' . $item['_id'], 'data-dropdown-width', $item['dropdown_width'] );
						$this->add_render_attribute( 'menu-item' . $item['_id'], 'data-dropdown-pos', $item['dropdown_position'] );

						$is_child = false;
					if ( true === $is_sub_menu_item ) {

						$is_sub_menu_item = false;
						$output  .= '</ul></li>';
					}

						$i++;
						$repeater_main_link = $this->get_repeater_setting_key( 'link', 'menu_items', $menu );

					if ( ! empty( $item['link']['url'] ) && $i === $i++ ) {

						$this->add_render_attribute( $repeater_main_link, 'href', $item['link']['url'] );
						if ( $item['link']['is_external'] ) {

							$this->add_render_attribute( $repeater_main_link, 'target', '_blank' );
						}
						if ( $item['link']['nofollow'] ) {

							$this->add_render_attribute( $repeater_main_link, 'rel', 'nofollow' );
						}
					}

						$output .= '<li ' . $this->get_render_attribute_string( 'menu-item' . $item['_id'] ) . '>';

					if ( array_key_exists( $menu + 1, $settings['menu_items'] ) ) {
						if ( 'item_submenu' === $settings['menu_items'][ $menu + 1 ]['item_type'] ) {
							$output .= "<div class='stfe-has-submenu-container'>";
						}
					}

							$output .= '<a ' . $this->get_render_attribute_string( $repeater_main_link ) . " class='stfe-menu-item'>";
								$output .= $this->get_render_attribute_string( $repeater_sub_menu_item ) . $item['text'];
								$output .= "<span class='stfe-menu-toggle sub-arrow parent-item'><i class='fa'></i></span>";
							$output     .= '</a>';
					if ( array_key_exists( $menu + 1, $settings['menu_items'] ) ) {
						if ( 'item_submenu' === $settings['menu_items'][ $menu + 1 ]['item_type'] ) {
							$output .= '</div>';
						}
					}
				}
			}
			$output .= '</ul>';

			echo $output; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			?>
		</nav>
		<?php
	}

	/**
	 * Add itemprop for Navigation Schema.
	 *
	 * @since 1.33.1
	 * @param string $atts link attributes.
	 * @access protected
	 */
	public function handle_link_attrs( $atts ) {

		$atts .= ' itemprop="url"';
		return $atts;
	}

	/**
	 * Get the menu and close icon HTML.
	 *
	 * @since 1.25.2
	 * @param array $settings Widget settings array.
	 * @access public
	 */
	public function get_menu_close_icon( $settings ) {
		$menu_icon     = '';
		$close_icon    = '';
		$icons         = array();
		$icon_settings = array(
			$settings['dropdown_icon'],
			$settings['dropdown_close_icon'],
		);

		foreach ( $icon_settings as $icon ) {
            ob_start();
            \Elementor\Icons_Manager::render_icon(
                $icon,
                array(
                    'aria-hidden' => 'true',
                    'tabindex'    => '0',
                )
            );
            $menu_icon = ob_get_clean();

			array_push( $icons, $menu_icon );
		}

		return $icons;
	}

	/**
	 * Add itemprop for Navigation Schema.
	 *
	 * @since 1.33.1
	 * @param string $atts link attributes.
	 * @access public
	 */
	public function handle_li_atts( $atts ) {
		$atts .= ' itemprop="name"';
		return $atts;
	}

    /**
	 * Retrieve the menu index.
	 *
	 * Used to get index of nav menu.
	 *
	 * @since 1.21.0
	 * @access protected
	 *
	 * @return string nav index.
	 */
	protected function get_nav_menu_index() {
		return $this->nav_menu_index++;
	}

    protected function render() {
        $this->__context = 'render';

		$this->__open_wrap();


		$settings         = $this->get_settings_for_display();
		$menu_close_icons = array();
		$menu_close_icons = $this->get_menu_close_icon( $settings );

		if ( 'yes' === $settings['schema_support'] ) {

			$this->add_render_attribute( 'stfe-nav-menu', 'itemscope', 'itemscope' );

			$this->add_render_attribute( 'stfe-nav-menu', 'itemtype', 'http://schema.org/SiteNavigationElement' );

		}

		if ( 'wordpress_menu' === $settings['menu_type'] ) {
			$args = array(
				'echo'        => false,
				'menu'        => $settings['menu'],
				'menu_class'  => 'stfe-nav-menu',
				'menu_id'     => 'menu-' . $this->get_nav_menu_index() . '-' . $this->get_id(),
				'fallback_cb' => '__return_empty_string',
				'container'   => '',
				'walker'      => new \Menu_Walker(),
			);

			if ( 'yes' === $settings['schema_support'] ) {

				add_filter( 'uael_nav_menu_attrs', array( $this, 'handle_link_attrs' ) );
				add_filter( 'nav_menu_values', array( $this, 'handle_li_atts' ) );
			}

			$menu_html = wp_nav_menu( $args );
		}

		if ( 'flyout' === $settings['layout'] ) {

			if ( 'flyout' === $settings['layout'] ) {

				$this->add_render_attribute( 'stfe-flyout', 'class', 'stfe-flyout-wrapper' );
			}
			?>
			<div class="stfe-nav-menu__toggle elementor-clickable stfe-flyout-trigger" tabindex="0">
				<div class="stfe-nav-menu-icon">
					<?php echo isset( $menu_close_icons[0] ) ? $menu_close_icons[0] : ''; //phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				</div>
				<?php if ( 'yes' === $settings['toggle_label_show'] ) { ?>
					<span class="stfe-nav-menu-label"><?php echo esc_html( $settings['toggle_label_text'] ); ?></span>
				<?php } ?>
			</div>
			<div <?php echo wp_kses_post( $this->get_render_attribute_string( 'stfe-flyout' ) ); ?> >
				<div class="stfe-flyout-overlay elementor-clickable"></div>
				<div class="stfe-flyout-container">
					<div id="stfe-flyout-content-id-<?php echo esc_attr( $this->get_id() ); ?>" class="stfe-side stfe-flyout-<?php echo esc_attr( $settings['flyout_layout'] ); ?> stfe-flyout-open" data-layout="<?php echo wp_kses_post( $settings['flyout_layout'] ); ?>" data-flyout-type="<?php echo wp_kses_post( $settings['flyout_type'] ); ?>">
						<div class="stfe-flyout-content push">
							<?php if ( 'wordpress_menu' === $settings['menu_type'] ) { ?>
								<nav <?php echo wp_kses_post( $this->get_render_attribute_string( 'stfe-nav-menu' ) ); ?>><?php echo $menu_html; //phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></nav>
								<?php
							} else {
								$this->get_custom_style();
							}
							?>
							<div class="elementor-clickable stfe-flyout-close" tabindex="0">
								<?php echo isset( $menu_close_icons[1] ) ? $menu_close_icons[1] : ''; //phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
							</div>
						</div>
					</div>
				</div>
			</div>
			<?php
		} else {
			$this->add_render_attribute(
				'stfe-main-menu',
				'class',
				array(
					'stfe-nav-menu',
					'stfe-layout-' . $settings['layout'],
				)
			);

			$this->add_render_attribute( 'stfe-main-menu', 'class', 'stfe-nav-menu-layout' );

			$this->add_render_attribute( 'stfe-main-menu', 'data-layout', $settings['layout'] );

			if ( $settings['pointer'] ) {

				if ( 'horizontal' === $settings['layout'] || 'vertical' === $settings['layout'] ) {
					$this->add_render_attribute( 'stfe-main-menu', 'class', 'stfe-pointer__' . $settings['pointer'] );

					if ( in_array( $settings['pointer'], array( 'double-line', 'underline', 'overline' ), true ) ) {

						$key = 'animation_line';
						$this->add_render_attribute( 'stfe-main-menu', 'class', 'stfe-animation__' . $settings[ $key ] );
					} elseif ( 'framed' === $settings['pointer'] || 'text' === $settings['pointer'] ) {

						$key = 'animation_' . $settings['pointer'];
						$this->add_render_attribute( 'stfe-main-menu', 'class', 'stfe-animation__' . $settings[ $key ] );
					}
				}
			}

			if ( 'expandible' === $settings['layout'] ) {

				$this->add_render_attribute( 'stfe-nav-menu', 'class', 'stfe-dropdown-expandible' );
			}

			$this->add_render_attribute(
				'stfe-nav-menu',
				'class',
				array(
					'stfe-nav-menu__layout-' . $settings['layout'],
					'stfe-nav-menu__submenu-' . $settings['submenu_icon'],
				)
			);

			$this->add_render_attribute( 'stfe-nav-menu', 'data-toggle-icon', $menu_close_icons[0] );

			$this->add_render_attribute( 'stfe-nav-menu', 'data-close-icon', $menu_close_icons[1] );

			$this->add_render_attribute( 'stfe-nav-menu', 'data-full-width', $settings['full_width_dropdown'] );

			?>
			<div <?php echo wp_kses_post( $this->get_render_attribute_string( 'stfe-main-menu' ) ); ?>>
					<div class="stfe-nav-menu__toggle elementor-clickable">
						<div class="stfe-nav-menu-icon">
							<?php echo isset( $menu_close_icons[0] ) ? $menu_close_icons[0] : ''; //phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
						</div>
						<?php if ( 'yes' === $settings['toggle_label_show'] ) { ?>
							<span class="stfe-nav-menu-label"><?php echo esc_html( $settings['toggle_label_text'] ); ?></span>
						<?php } ?>
					</div>
				<?php if ( 'wordpress_menu' === $settings['menu_type'] ) { ?>
					<nav <?php echo wp_kses_post( $this->get_render_attribute_string( 'stfe-nav-menu' ) ); ?>><?php echo $menu_html; //phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></nav>
				<?php } else { ?>
						<?php $this->get_custom_style(); ?>
				<?php } ?>
			</div>
			<?php
		}

		$this->__close_wrap();
    }

}