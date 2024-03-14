<?php
/**
 * Class: Soft_Template_OffCanvas
 * Name: Off Canvas
 * Slug: soft-template-offcanvas
 */
namespace Elementor;

// Elementor Classes.
use Elementor\Controls_Manager;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Soft_Template_OffCanvas extends SoftTemplate_Base {
    public function get_name() {
		return 'soft-template-offcanvas';
	}

	public function get_title() {
		return esc_html__( 'Offcanvas', 'soft-template-core' );
	}

    public function get_icon() {
		return 'eicon-menu-bar';
	}

    public function get_script_depends() {
		return array( 'soft-template-offcanvas' );
	}

    public function get_jet_help_url() {
		return '#';
	}

	public function get_categories() {
		return array( 'soft-template-core' );
	}

    /**
	 * Retrieve the menu index.
	 *
	 * Used to get index of menu index.
	 *
	 * @since 1.27.2
	 * @access protected
	 *
	 * @return string menu index.
	 */
	protected function get_menu_index() {
		return $this->menu_index++;
	}

    /**
	 * Menu index.
	 *
	 * @access protected
	 * @var $menu_index
	 */
	protected $menu_index = 1;

    protected function register_controls() {
        $this->register_general_content_controls();
		$this->register_menu_content_controls();
		$this->register_display_offcanvas_controls();
		$this->register_display_content_controls();
		$this->register_close_controls();
		//$this->register_helpful_information();

		$this->register_offcanvas_style_controls();
		$this->register_menu_style_controls();
		$this->register_content_style_controls();
		$this->register_button_style_controls();
		$this->register_icon_style_controls();
		$this->register_close_icon_style_controls();
    }

	/**
	 * Register Off - Canvas Content Controls.
	 *
	 * @since 1.11.0
	 * @access protected
	 */
	protected function register_general_content_controls() {
		$this->start_controls_section(
			'content',
			array(
				'label' => __( 'Content', 'soft-template-core' ),
			)
		);

		$this->add_control(
			'preview_offcanvas',
			array(
				'label'        => __( 'Preview', 'soft-template-core' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'no',
				'return_value' => 'yes',
				'label_off'    => __( 'No', 'soft-template-core' ),
				'label_on'     => __( 'Yes', 'soft-template-core' ),
			)
		);

		$this->add_control(
			'content_type',
			array(
				'label'   => __( 'Content Type', 'soft-template-core' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'content',
				'options' => $this->get_content_type(),
			)
		);

		$menus = $this->get_menus_list();

		if ( ! empty( $menus ) ) {
			$this->add_control(
				'menu',
				array(
					'label'        => __( 'Menu', 'soft-template-core' ),
					'type'         => Controls_Manager::SELECT,
					'options'      => $menus,
					'default'      => array_keys( $menus )[0],
					'save_default' => true,
					/* translators: %s admin link */
					'description'  => sprintf( __( 'Go to the <a href="%s" target="_blank">Menus screen</a> to manage your menus.', 'soft-template-core' ), admin_url( 'nav-menus.php' ) ),
					'condition'    => array(
						'content_type' => 'menu',
					),
				)
			);
		} else {
			$this->add_control(
				'menu',
				array(
					'type'            => Controls_Manager::RAW_HTML,
					/* translators: %s admin link */
					'raw'             => sprintf( __( '<strong>There are no menus in your site.</strong><br>Go to the <a href="%s" target="_blank">Menus screen</a> to create one.', 'soft-template-core' ), admin_url( 'nav-menus.php?action=edit&menu=0' ) ),
					'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
					'condition'       => array(
						'content_type' => 'menu',
					),
				)
			);
		}

		$this->add_control(
			'ct_content',
			array(
				'label'      => __( 'Description', 'soft-template-core' ),
				'type'       => Controls_Manager::WYSIWYG,
				'default'    => __( 'Enter content here. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.​ Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.', 'soft-template-core' ),
				'rows'       => 10,
				'show_label' => false,
				'dynamic'    => array(
					'active' => true,
				),
				'condition'  => array(
					'content_type' => 'content',
				),
			)
		);

		$this->add_control(
			'ct_saved_rows',
			array(
				'label'     => __( 'Select Section', 'soft-template-core' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => \Soft_template_Core_Utils::get_saved_data( 'section' ),
				'default'   => '-1',
				'condition' => array(
					'content_type' => 'saved_rows',
				),
			)
		);

		$this->add_control(
			'ct_page_templates',
			array(
				'label'     => __( 'Select Page', 'soft-template-core' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => \Soft_template_Core_Utils::get_saved_data( 'page' ),
				'default'   => '-1',
				'condition' => array(
					'content_type' => 'saved_page_templates',
				),
			)
		);

		$this->add_control(
			'ct_saved_modules',
			array(
				'label'     => __( 'Select Widget', 'soft-template-core' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => \Soft_template_Core_Utils::get_saved_data( 'widget' ),
				'default'   => '-1',
				'condition' => array(
					'content_type' => 'saved_modules',
				),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Register menu content type controls.
	 *
	 * @since 1.27.2
	 * @access protected
	 */
	protected function register_menu_content_controls() {

		$this->start_controls_section(
			'menu_content_type',
			array(
				'label'     => __( 'Menu', 'soft-template-core' ),
				'condition' => array(
					'content_type' => array( 'menu' ),
				),
			)
		);

		$this->add_control(
			'wrap_the_submenu',
			array(
				'label'        => __( 'Hide Submenu Item', 'soft-template-core' ),
				'description'  => __( 'Enable this option to hide/wrap submenu under respective parent menu item.', 'soft-template-core' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'no',
				'label_off'    => __( 'No', 'soft-template-core' ),
				'label_on'     => __( 'Yes', 'soft-template-core' ),
				'prefix_class' => 'stfe-offcanvas-wrap-submenu-',
				'condition'    => array(
					'content_type' => array( 'menu' ),
				),
				'render_type'  => 'template',
			)
		);

		$this->add_control(
			'submenu_icon',
			array(
				'label'        => __( 'Submenu Trigger Icon', 'soft-template-core' ),
				'type'         => Controls_Manager::SELECT,
				'default'      => 'arrow',
				'options'      => array(
					'arrow'   => __( 'Arrow', 'soft-template-core' ),
					'plus'    => __( 'Plus Sign', 'soft-template-core' ),
					'classic' => __( 'Classic', 'soft-template-core' ),
				),
				'condition'    => array(
					'content_type'     => 'menu',
					'wrap_the_submenu' => 'yes',
				),
				'prefix_class' => 'stfe-offcanvas-submenu-icon-',
			)
		);

		$this->add_control(
			'link_redirect',
			array(
				'label'        => __( 'Action On Menu Click', 'soft-template-core' ),
				'type'         => Controls_Manager::SELECT,
				'default'      => 'child',
				'options'      => array(
					'child'     => __( 'Open Submenu', 'soft-template-core' ),
					'self_link' => __( 'Redirect To Self Link', 'soft-template-core' ),
				),
				'prefix_class' => 'stfe-off-canvas-link-redirect-',
				'condition'    => array(
					'content_type'     => array( 'menu' ),
					'wrap_the_submenu' => 'yes',
				),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Register Off - Canvas Title Style Controls.
	 *
	 * @since 1.11.0
	 * @access protected
	 */
	protected function register_display_content_controls() {
		$this->start_controls_section(
			'offcanvas',
			array(
				'label' => __( 'Display Settings', 'soft-template-core' ),
			)
		);

		$this->add_control(
			'offcanvas_on',
			array(
				'label'   => __( 'Display On', 'soft-template-core' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'button',
				'options' => array(
					'button'    => __( 'Button', 'soft-template-core' ),
					'icon'      => __( 'Icon', 'soft-template-core' ),
					'custom'    => __( 'Custom Class', 'soft-template-core' ),
					'custom_id' => __( 'Custom ID', 'soft-template-core' ),
				),
			)
		);

		$this->add_control(
			'btn_text',
			array(
				'label'       => __( 'Button Text', 'soft-template-core' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => __( 'Click Me', 'soft-template-core' ),
				'placeholder' => __( 'Click Me', 'soft-template-core' ),
				'dynamic'     => array(
					'active' => true,
				),
				'condition'   => array(
					'offcanvas_on' => 'button',
				),
			)
		);
		if ( \Soft_template_Core_Utils::is_elementor_updated() ) {
			$this->add_control(
				'new_icon',
				array(
					'label'            => __( 'Icon', 'soft-template-core' ),
					'type'             => Controls_Manager::ICONS,
					'fa4compatibility' => 'icon',
					'default'          => array(
						'value'   => 'fa fa-home',
						'library' => 'fa-solid',
					),
					'condition'        => array(
						'offcanvas_on' => 'icon',
					),
				)
			);
		} else {
			$this->add_control(
				'icon',
				array(
					'label'            => __( 'Icon', 'soft-template-core' ),
					'type'             => Controls_Manager::ICON,
					'default'          => 'fa fa-home',
					'condition' => array(
						'offcanvas_on' => 'icon',
					),
				)
			);
		}
		
		$this->add_control(
			'offcanvas_button_icon',
			array(
				'label'     => __( 'Select Icon', 'soft-template-core' ),
				'type'      => Controls_Manager::ICON,
				'condition' => array(
					'offcanvas_on' => 'button',
				),
			)
		);


		$this->add_control(
			'icon_size',
			array(
				'label'     => __( 'Size (px)', 'soft-template-core' ),
				'type'      => Controls_Manager::SLIDER,
				'default'   => array(
					'size' => 60,
				),
				'range'     => array(
					'px' => array(
						'max' => 500,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .stfe-offcanvas-action .stfe-offcanvas-icon-bg i,
					{{WRAPPER}} .stfe-offcanvas-action .stfe-offcanvas-icon-bg svg' => 'font-size: {{SIZE}}px; width: {{SIZE}}px; height: {{SIZE}}px; line-height: {{SIZE}}px;',
				),
				'condition' => array(
					'offcanvas_on' => 'icon',
				),
			)
		);

		$this->add_control(
			'uael_display_position',
			array(
				'label'        => __( 'Position', 'soft-template-core' ),
				'type'         => Controls_Manager::SELECT,
				'default'      => 'inline',
				'options'      => array(
					'inline'   => __( 'Inline', 'soft-template-core' ),
					'floating' => __( 'Floating', 'soft-template-core' ),
				),
				'condition'    => array(
					'offcanvas_on' => array( 'button', 'icon' ),
				),
				'prefix_class' => 'stfe-offcanvas-trigger-align-',
				'render_type'  => 'template',
			)
		);

		// If uael_display_position is Inline.
		$this->add_responsive_control(
			'uael_display_inline_button_align',
			array(
				'label'     => __( 'Alignment', 'soft-template-core' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => array(
					'left'    => array(
						'title' => __( 'Left', 'soft-template-core' ),
						'icon'  => 'fa fa-align-left',
					),
					'center'  => array(
						'title' => __( 'Center', 'soft-template-core' ),
						'icon'  => 'fa fa-align-center',
					),
					'right'   => array(
						'title' => __( 'Right', 'soft-template-core' ),
						'icon'  => 'fa fa-align-right',
					),
					'justify' => array(
						'title' => __( 'Justified', 'soft-template-core' ),
						'icon'  => 'fa fa-align-justify',
					),
				),
				'default'   => 'left',
				'condition' => array(
					'uael_display_position' => 'inline',
					'offcanvas_on'          => 'button',
				),
				'toggle'    => false,
			)
		);

		// If uael_display_position is Floating.
		$this->add_control(
			'uael_display_floating_align',
			array(
				'label'       => __( 'Alignment', 'soft-template-core' ),
				'type'        => Controls_Manager::CHOOSE,
				'default'     => 'left',
				'options'     => array(
					'left'  => array(
						'title' => __( 'Left', 'soft-template-core' ),
						'icon'  => 'fa fa-align-left',
					),
					'right' => array(
						'title' => __( 'Right', 'soft-template-core' ),
						'icon'  => 'fa fa-align-right',
					),
				),
				'toggle'      => false,
				'label_block' => false,
				'condition'   => array(
					'offcanvas_on'          => array( 'button', 'icon' ),
					'uael_display_position' => 'floating',
				),
			)
		);

		$this->add_responsive_control(
			'uael_display_floating_on_window_position',
			array(
				'label'          => __( 'Vertical Floating Position', 'soft-template-core' ),
				'type'           => Controls_Manager::SLIDER,
				'size_units'     => '%',
				'default'        => array(
					'size' => '50',
					'unit' => '%',
				),
				'tablet_default' => array(
					'size' => '50',
					'unit' => '%',
				),
				'mobile_default' => array(
					'size' => '50',
					'unit' => '%',
				),
				'range'          => array(
					'%' => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'selectors'      => array(
					'{{WRAPPER}} .stfe-offcanvas-action-wrap .stfe-button-wrapper .stfe-offcanvas-action-alignment-left,
					{{WRAPPER}} .stfe-offcanvas-action-wrap .stfe-offcanvas-icon-wrap .stfe-offcanvas-action-alignment-left,
                    {{WRAPPER}} .stfe-offcanvas-action-wrap .stfe-button-wrapper .stfe-offcanvas-action-alignment-right,
                    {{WRAPPER}} .stfe-offcanvas-action-wrap .stfe-offcanvas-icon-wrap .stfe-offcanvas-action-alignment-right' => 'top: {{SIZE}}{{UNIT}}; transform: translateY( -{{SIZE}}{{UNIT}} );',
				),
				'condition'      => array(
					'uael_display_position' => 'floating',
					'offcanvas_on'          => array( 'button', 'icon' ),
				),
			)
		);

		$this->add_responsive_control(
			'uael_display_floating_on_window_horizontal_position',
			array(
				'label'          => __( 'Horizontal Floating Position', 'soft-template-core' ),
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
					'{{WRAPPER}} .stfe-offcanvas-action-wrap .stfe-button-wrapper .stfe-offcanvas-action-alignment-left,
					{{WRAPPER}} .stfe-offcanvas-action-wrap .stfe-offcanvas-icon-wrap .stfe-offcanvas-action-alignment-left'
					=> 'left: {{SIZE}}{{UNIT}}; transform: translateX( {{SIZE}}{{UNIT}} );',
					'{{WRAPPER}} .stfe-offcanvas-action-wrap .stfe-button-wrapper .stfe-offcanvas-action-alignment-right,
                    {{WRAPPER}} .stfe-offcanvas-action-wrap .stfe-offcanvas-icon-wrap .stfe-offcanvas-action-alignment-right' => 'right: {{SIZE}}{{UNIT}}; transform: translateX( {{SIZE}}{{UNIT}} );',
				),
				'condition'      => array(
					'uael_display_position' => 'floating',
					'offcanvas_on'          => array( 'button', 'icon' ),
				),
			)
		);

		$this->add_control(
			'btn_size',
			array(
				'label'     => __( 'Size', 'soft-template-core' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'sm',
				'options'   => array(
					'xs' => __( 'Extra Small', 'soft-template-core' ),
					'sm' => __( 'Small', 'soft-template-core' ),
					'md' => __( 'Medium', 'soft-template-core' ),
					'lg' => __( 'Large', 'soft-template-core' ),
					'xl' => __( 'Extra Large', 'soft-template-core' ),
				),
				'condition' => array(
					'offcanvas_on' => 'button',
				),
			)
		);

		if ( \Soft_template_Core_Utils::is_elementor_updated() ) {
			$this->add_control(
				'new_offcanvas_button_icon',
				array(
					'label'            => __( 'Select Icon', 'soft-template-core' ),
					'type'             => Controls_Manager::ICONS,
					'fa4compatibility' => 'offcanvas_button_icon',
					'condition'        => array(
						'offcanvas_on' => 'button',
					),
					'render_type'      => 'template',
				)
			);
		} else {
			$this->add_control(
				'offcanvas_button_icon',
				array(
					'label'     => __( 'Select Icon', 'soft-template-core' ),
					'type'      => Controls_Manager::ICON,
					'condition' => array(
						'offcanvas_on' => 'button',
					),
				)
			);
		}

		$this->add_control(
			'offcanvas_button_icon_position',
			array(
				'label'       => __( 'Icon Position', 'soft-template-core' ),
				'type'        => Controls_Manager::SELECT,
				'default'     => 'right',
				'label_block' => false,
				'options'     => array(
					'right' => __( 'After Text', 'soft-template-core' ),
					'left'  => __( 'Before Text', 'soft-template-core' ),
				),
				'conditions'  => array(
					'relation' => 'and',
					'terms'    => array(
						array(
							'name'     => \Soft_template_Core_Utils::get_new_icon_name( 'offcanvas_button_icon' ),
							'operator' => '!=',
							'value'    => '',
						),
						array(
							'name'     => 'offcanvas_on',
							'operator' => '==',
							'value'    => 'button',
						),
					),
				),
			)
		);

		$this->add_control(
			'offcanvas_icon_spacing',
			array(
				'label'      => __( 'Icon Spacing', 'soft-template-core' ),
				'type'       => Controls_Manager::SLIDER,
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 50,
					),
				),
				'default'    => array(
					'size' => '5',
					'unit' => 'px',
				),
				'conditions' => array(
					'relation' => 'and',
					'terms'    => array(
						array(
							'name'     => \Soft_template_Core_Utils::get_new_icon_name( 'offcanvas_button_icon' ),
							'operator' => '!=',
							'value'    => '',
						),
						array(
							'name'     => 'offcanvas_on',
							'operator' => '==',
							'value'    => 'button',
						),
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .elementor-button .elementor-align-icon-right, {{WRAPPER}} .stfe-infobox-link-icon-after' => 'margin-left: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .elementor-button .elementor-align-icon-left, {{WRAPPER}} .stfe-infobox-link-icon-before' => 'margin-right: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'offcanvas_custom',
			array(
				'label'       => __( 'Class', 'soft-template-core' ),
				'type'        => Controls_Manager::TEXT,
				'description' => __( 'Add your custom class without the dot. e.g: my-class', 'soft-template-core' ),
				'condition'   => array(
					'offcanvas_on' => 'custom',
				),
			)
		);

		$this->add_control(
			'offcanvas_custom_id',
			array(
				'label'       => __( 'Custom ID', 'soft-template-core' ),
				'type'        => Controls_Manager::TEXT,
				'description' => __( 'Add your custom id without the # key. e.g: my-id', 'soft-template-core' ),
				'condition'   => array(
					'offcanvas_on' => 'custom_id',
				),
			)
		);

		$this->add_control(
			'offcanvas_trigger_zindex',
			array(
				'label'       => __( 'Z-Index', 'soft-template-core' ),
				'description' => __( 'Adjust the z-index of the floating trigger if it is not visibile on the page. Defaults is set to 999', 'soft-template-core' ),
				'type'        => Controls_Manager::NUMBER,
				'default'     => '999',
				'min'         => 0,
				'step'        => 1,
				'condition'   => array(
					'offcanvas_on'          => array( 'button', 'icon' ),
					'uael_display_position' => 'floating',
				),
				'selectors'   => array(
					'{{WRAPPER}} .stfe-offcanvas-trigger' => 'z-index: {{SIZE}};',
				),
			)
		);

		$this->add_responsive_control(
			'all_align',
			array(
				'label'     => __( 'Alignment', 'soft-template-core' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => array(
					'left'   => array(
						'title' => __( 'Left', 'soft-template-core' ),
						'icon'  => 'fa fa-align-left',
					),
					'center' => array(
						'title' => __( 'Center', 'soft-template-core' ),
						'icon'  => 'fa fa-align-center',
					),
					'right'  => array(
						'title' => __( 'Right', 'soft-template-core' ),
						'icon'  => 'fa fa-align-right',
					),
				),
				'default'   => 'left',
				'condition' => array(
					'offcanvas_on'          => 'icon',
					'uael_display_position' => 'inline',
				),
				'selectors' => array(
					'{{WRAPPER}} .stfe-offcanvas-action-wrap' => 'text-align: {{VALUE}};',
				),
				'toggle'    => false,
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Register Off - Canvas Close button.
	 *
	 * @since 1.11.0
	 * @access protected
	 */
	protected function register_close_controls() {
		$this->start_controls_section(
			'section_close',
			array(
				'label' => __( 'Close Button', 'soft-template-core' ),
			)
		);

		if ( \Soft_template_Core_Utils::is_elementor_updated() ) {
			$this->add_control(
				'new_close_icon',
				array(
					'label'            => __( 'Select Close Icon', 'soft-template-core' ),
					'type'             => Controls_Manager::ICONS,
					'fa4compatibility' => 'close_icon',
					'default'          => array(
						'value'   => 'fas fa-times',
						'library' => 'fa-solid',
					),
					'render_type'      => 'template',
				)
			);
		} else {
			$this->add_control(
				'close_icon',
				array(
					'label'   => __( 'Select Close Icon', 'soft-template-core' ),
					'type'    => Controls_Manager::ICON,
					'default' => 'fa fa-close',
				)
			);
		}

		$this->add_control(
			'close_inside_icon_position',
			array(
				'label'      => __( 'Icon Alignment', 'soft-template-core' ),
				'type'       => Controls_Manager::SELECT,
				'default'    => 'right-top',
				'options'    => array(
					'left-top'  => __( 'Left Top', 'soft-template-core' ),
					'right-top' => __( 'Right Top', 'soft-template-core' ),
				),
				'conditions' => array(
					'relation' => 'or',
					'terms'    => array(
						array(
							'name'     => \Soft_template_Core_Utils::get_new_icon_name( 'close_icon' ),
							'operator' => '!=',
							'value'    => '',
						),
					),
				),
			)
		);

		$this->add_responsive_control(
			'close_icon_size',
			array(
				'label'      => __( 'Size', 'soft-template-core' ),
				'type'       => Controls_Manager::SLIDER,
				'default'    => array(
					'size' => 14,
				),
				'range'      => array(
					'px' => array(
						'max' => 60,
					),
				),
				'selectors'  => array(
					'.uaoffcanvas-{{ID}} .stfe-offcanvas-close .stfe-offcanvas-close-icon, .uaoffcanvas-{{ID}} .stfe-offcanvas-close .stfe-offcanvas-close-icon svg' => 'height: calc( {{SIZE}}px + 5px ); width: calc( {{SIZE}}px + 5px ); font-size: calc( {{SIZE}}px + 5px ); line-height: calc( {{SIZE}}px + 5px );',
				),
				'conditions' => array(
					'relation' => 'or',
					'terms'    => array(
						array(
							'name'     => \Soft_template_Core_Utils::get_new_icon_name( 'close_icon' ),
							'operator' => '!=',
							'value'    => '',
						),
					),
				),
			)
		);

		$this->add_control(
			'esc_keypress',
			array(
				'label'        => __( 'Close on ESC Keypress', 'soft-template-core' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'no',
				'return_value' => 'yes',
				'label_off'    => __( 'No', 'soft-template-core' ),
				'label_on'     => __( 'Yes', 'soft-template-core' ),
			)
		);

		$this->add_control(
			'overlay_click',
			array(
				'label'        => __( 'Close on Overlay Click', 'soft-template-core' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'no',
				'return_value' => 'yes',
				'label_off'    => __( 'No', 'soft-template-core' ),
				'label_on'     => __( 'Yes', 'soft-template-core' ),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Register off-canvas button Style Controls.
	 *
	 * @since 1.11.0
	 * @access protected
	 */
	protected function register_button_style_controls() {
		$this->start_controls_section(
			'section_button_style',
			array(
				'label'     => __( 'Button', 'soft-template-core' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'offcanvas_on' => 'button',
				),
			)
		);

		$this->add_control(
			'btn_html_message',
			array(
				'type'      => Controls_Manager::RAW_HTML,
				'raw'       => sprintf( '<p style="font-size: 11px;font-style: italic;line-height: 1.4;color: #a4afb7;">%s</p>', __( 'To see these changes please turn off the preview setting from Content Tab.', 'soft-template-core' ) ),
				'condition' => array(
					'preview_offcanvas' => 'yes',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'      => 'btn_typography',
				'label'     => __( 'Typography', 'soft-template-core' ),
				'global'    => array(
					'default' => Global_Typography::TYPOGRAPHY_ACCENT,
				),
				'selector'  => '{{WRAPPER}} .stfe-offcanvas-action-wrap a.elementor-button, {{WRAPPER}} .stfe-offcanvas-action-wrap .elementor-button',
				'condition' => array(
					'offcanvas_on' => 'button',
				),
			)
		);

		$this->add_responsive_control(
			'btn_padding',
			array(
				'label'      => __( 'Padding', 'soft-template-core' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .stfe-offcanvas-action-wrap a.elementor-button, {{WRAPPER}} .stfe-offcanvas-action-wrap .elementor-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'condition'  => array(
					'offcanvas_on' => 'button',
				),
			)
		);

		$this->start_controls_tabs( 'tabs_button_style' );

		$this->start_controls_tab(
			'tab_button_normal',
			array(
				'label'     => __( 'Normal', 'soft-template-core' ),
				'condition' => array(
					'offcanvas_on' => 'button',
				),
			)
		);

			$this->add_control(
				'button_text_color',
				array(
					'label'     => __( 'Text Color', 'soft-template-core' ),
					'type'      => Controls_Manager::COLOR,
					'default'   => '',
					'selectors' => array(
						'{{WRAPPER}} .stfe-offcanvas-action-wrap a.elementor-button, {{WRAPPER}} .stfe-offcanvas-action-wrap .elementor-button' => 'color: {{VALUE}};',
					),
					'condition' => array(
						'offcanvas_on' => 'button',
					),
				)
			);

			$this->add_control(
				'btn_background_color',
				array(
					'label'     => __( 'Background Color', 'soft-template-core' ),
					'type'      => Controls_Manager::COLOR,
					'default'   => '',
					'selectors' => array(
						'{{WRAPPER}} .stfe-offcanvas-action-wrap .elementor-button' => 'background-color: {{VALUE}};',
					),
					'condition' => array(
						'offcanvas_on' => 'button',
					),
					'global'    => array(
						'default' => Global_Colors::COLOR_ACCENT,
					),
				)
			);

			$this->add_group_control(
				Group_Control_Border::get_type(),
				array(
					'name'        => 'btn_border',
					'label'       => __( 'Border', 'soft-template-core' ),
					'placeholder' => '1px',
					'default'     => '1px',
					'selector'    => '{{WRAPPER}} .stfe-offcanvas-action-wrap .elementor-button',
					'condition'   => array(
						'offcanvas_on' => 'button',
					),
				)
			);

			$this->add_control(
				'btn_border_radius',
				array(
					'label'      => __( 'Border Radius', 'soft-template-core' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => array( 'px', '%' ),
					'selectors'  => array(
						'{{WRAPPER}} .stfe-offcanvas-action-wrap a.elementor-button, {{WRAPPER}} .stfe-offcanvas-action-wrap .elementor-button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					),
					'condition'  => array(
						'offcanvas_on' => 'button',
					),
				)
			);

			$this->add_group_control(
				Group_Control_Box_Shadow::get_type(),
				array(
					'name'      => 'button_box_shadow',
					'selector'  => '{{WRAPPER}} .stfe-offcanvas-action-wrap .elementor-button',
					'condition' => array(
						'offcanvas_on' => 'button',
					),
				)
			);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_button_hover',
			array(
				'label'     => __( 'Hover', 'soft-template-core' ),
				'condition' => array(
					'offcanvas_on' => 'button',
				),
			)
		);

			$this->add_control(
				'btn_hover_color',
				array(
					'label'     => __( 'Text Color', 'soft-template-core' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'{{WRAPPER}} .stfe-offcanvas-action-wrap a.elementor-button:hover, {{WRAPPER}} .stfe-offcanvas-action-wrap .elementor-button:hover' => 'color: {{VALUE}};',
					),
					'condition' => array(
						'offcanvas_on' => 'button',
					),
				)
			);

			$this->add_control(
				'btn_hover_bg_color',
				array(
					'label'     => __( 'Background Color', 'soft-template-core' ),
					'type'      => Controls_Manager::COLOR,
					'default'   => '',
					'selectors' => array(
						'{{WRAPPER}} .stfe-offcanvas-action-wrap a.elementor-button:hover, {{WRAPPER}} .stfe-offcanvas-action-wrap .elementor-button:hover' => 'background-color: {{VALUE}};',
					),
					'condition' => array(
						'offcanvas_on' => 'button',
					),
					'global'    => array(
						'default' => Global_Colors::COLOR_ACCENT,
					),
				)
			);

			$this->add_control(
				'button_hover_border_color',
				array(
					'label'     => __( 'Border Color', 'soft-template-core' ),
					'type'      => Controls_Manager::COLOR,
					'condition' => array(
						'border_border!' => '',
					),
					'selectors' => array(
						'{{WRAPPER}} .stfe-offcanvas-action-wrap a.elementor-button:hover, {{WRAPPER}} .stfe-offcanvas-action-wrap .elementor-button:hover' => 'border-color: {{VALUE}};',
					),
					'condition' => array(
						'offcanvas_on' => 'button',
					),
				)
			);

			$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();
	}

	/**
	 * Register off-canvas Icon Style Controls.
	 *
	 * @since 1.11.0
	 * @access protected
	 */
	protected function register_icon_style_controls() {
		$this->start_controls_section(
			'section_offcanvas_icon_display_style',
			array(
				'label'     => __( 'Icon', 'soft-template-core' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'offcanvas_on' => 'icon',
				),
			)
		);

		$this->add_responsive_control(
			'offcanvas_icon_padding',
			array(
				'label'      => __( 'Padding', 'soft-template-core' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .stfe-offcanvas-icon-bg' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->start_controls_tabs( 'icon_style' );

			$this->start_controls_tab(
				'icon_normal',
				array(
					'label'     => __( 'Normal', 'soft-template-core' ),
					'condition' => array(
						'offcanvas_on' => 'icon',
					),
				)
			);
				$this->add_control(
					'icon_color_normal',
					array(
						'label'     => __( 'Icon Color', 'soft-template-core' ),
						'type'      => Controls_Manager::COLOR,
						'global'    => array(
							'default' => Global_Colors::COLOR_TEXT,
						),
						'default'   => '',
						'selectors' => array(
							'{{WRAPPER}} .stfe-offcanvas-action i' => 'color: {{VALUE}};',
							'{{WRAPPER}} .stfe-offcanvas-action svg' => 'fill: {{VALUE}};',
						),
						'condition' => array(
							'offcanvas_on' => 'icon',
						),
					)
				);

				$this->add_control(
					'icon_background_color_normal',
					array(
						'label'     => __( 'Background Color', 'soft-template-core' ),
						'type'      => Controls_Manager::COLOR,
						'default'   => '',
						'selectors' => array(
							'{{WRAPPER}} .stfe-offcanvas-icon-bg' => 'background: {{VALUE}};',
						),
						'condition' => array(
							'offcanvas_on' => 'icon',
						),
					)
				);

			$this->end_controls_tab();

			$this->start_controls_tab(
				'icon_hover',
				array(
					'label'     => __( 'Hover', 'soft-template-core' ),
					'condition' => array(
						'offcanvas_on' => 'icon',
					),
				)
			);

				$this->add_control(
					'icon_color_hover',
					array(
						'label'     => __( 'Icon Color', 'soft-template-core' ),
						'type'      => Controls_Manager::COLOR,
						'global'    => array(
							'default' => Global_Colors::COLOR_TEXT,
						),
						'default'   => '',
						'selectors' => array(
							'{{WRAPPER}} .stfe-offcanvas-action i:hover' => 'color: {{VALUE}};',
							'{{WRAPPER}} .stfe-offcanvas-action svg:hover' => 'fill: {{VALUE}};',
						),
						'condition' => array(
							'offcanvas_on' => 'icon',
						),
					)
				);

				$this->add_control(
					'icon_background_color_hover',
					array(
						'label'     => __( 'Background Color', 'soft-template-core' ),
						'type'      => Controls_Manager::COLOR,
						'default'   => '',
						'selectors' => array(
							'{{WRAPPER}} .stfe-offcanvas-icon-bg:hover' => 'background: {{VALUE}};',
						),
						'condition' => array(
							'offcanvas_on' => 'icon',
						),
					)
				);

			$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();
	}

	/**
	 * Register off-canvas CLose Icon Style Controls.
	 *
	 * @since 1.11.0
	 * @access protected
	 */
	protected function register_close_icon_style_controls() {

		$this->start_controls_section(
			'section_close_icon_style',
			array(
				'label'      => __( 'Close Icon', 'soft-template-core' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'conditions' => array(
					'relation' => 'or',
					'terms'    => array(
						array(
							'name'     => \Soft_template_Core_Utils::get_new_icon_name( 'close_icon' ),
							'operator' => '!=',
							'value'    => '',
						),
					),
				),
			)
		);

		$this->add_control(
			'close_icon_color_normal',
			array(
				'label'      => __( 'Icon Color', 'soft-template-core' ),
				'type'       => Controls_Manager::COLOR,
				'default'    => '#000000',
				'selectors'  => array(
					'.uaoffcanvas-{{ID}} .stfe-offcanvas-close .stfe-offcanvas-close-icon i' => 'color: {{VALUE}};',
					'.uaoffcanvas-{{ID}} .stfe-offcanvas-close .stfe-offcanvas-close-icon svg' => 'fill: {{VALUE}};',
				),
				'conditions' => array(
					'relation' => 'or',
					'terms'    => array(
						array(
							'name'     => \Soft_template_Core_Utils::get_new_icon_name( 'close_icon' ),
							'operator' => '!=',
							'value'    => '',
						),
					),
				),
			)
		);

		$this->add_control(
			'close_icon_color_hover',
			array(
				'label'      => __( 'Background Color', 'soft-template-core' ),
				'type'       => Controls_Manager::COLOR,
				'default'    => 'rgba(0,0,0,.3)',
				'selectors'  => array(
					'.uaoffcanvas-{{ID}} .stfe-offcanvas-close' => 'background-color: {{VALUE}};',
				),
				'conditions' => array(
					'relation' => 'or',
					'terms'    => array(
						array(
							'name'     => \Soft_template_Core_Utils::get_new_icon_name( 'close_icon' ),
							'operator' => '!=',
							'value'    => '',
						),
					),
				),
			)
		);

		$this->add_responsive_control(
			'close_icon_padding',
			array(
				'label'      => __( 'Padding', 'soft-template-core' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'.uaoffcanvas-{{ID}} .stfe-offcanvas-close-icon-wrapper .stfe-offcanvas-close' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),

				'conditions' => array(
					'relation' => 'or',
					'terms'    => array(
						array(
							'name'     => \Soft_template_Core_Utils::get_new_icon_name( 'close_icon' ),
							'operator' => '!=',
							'value'    => '',
						),
					),
				),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Register offcanvas Style Controls.
	 *
	 * @since 1.11.0
	 * @access protected
	 */
	protected function register_offcanvas_style_controls() {
		$this->start_controls_section(
			'section_offcanvas_style',
			array(
				'label' => __( 'Off - Canvas', 'soft-template-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'offcanvas_bg_color',
			array(
				'label'     => __( 'Background Color', 'soft-template-core' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'.uaoffcanvas-{{ID}} .stfe-offcanvas' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'offcanvas_spacing',
			array(
				'label'      => __( 'Content Padding', 'soft-template-core' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'.uaoffcanvas-{{ID}} .stfe-offcanvas-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Register Menu Style Controls.
	 *
	 * @since 1.11.0
	 * @access protected
	 */
	protected function register_menu_style_controls() {
		$this->start_controls_section(
			'section_menu_style',
			array(
				'label'     => __( 'Menu', 'soft-template-core' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'content_type' => 'menu',
				),
			)
		);

		$this->add_control(
			'menu_heading',
			array(
				'label'     => __( 'Menu Item', 'soft-template-core' ),
				'type'      => Controls_Manager::HEADING,
				'condition' => array(
					'content_type' => 'menu',
				),
			)
		);
			$this->add_group_control(
				Group_Control_Typography::get_type(),
				array(
					'name'      => 'menu_typography',
					'global'    => array(
						'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
					),
					'selector'  => '{{WRAPPER}} .stfe-offcanvas-menu',
					'condition' => array(
						'content_type' => 'menu',
					),
				)
			);

			$this->add_responsive_control(
				'menu_padding',
				array(
					'label'      => __( 'Padding', 'soft-template-core' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => array( 'px', '%' ),
					'default'    => array(
						'top'      => '5',
						'bottom'   => '5',
						'left'     => '20',
						'right'    => '20',
						'unit'     => 'px',
						'isLinked' => false,
					),
					'selectors'  => array(
						'{{WRAPPER}} .stfe-offcanvas-menu .menu-item a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					),
					'condition'  => array(
						'content_type' => 'menu',
					),
				)
			);

			$this->start_controls_tabs( 'tabs_style_menu_item' );

			$this->start_controls_tab(
				'tab_menu_item_normal',
				array(
					'label'     => __( 'Normal', 'soft-template-core' ),
					'condition' => array(
						'content_type' => 'menu',
					),
				)
			);

				$this->add_control(
					'menu_item_color',
					array(
						'label'     => __( 'Text Color', 'soft-template-core' ),
						'type'      => Controls_Manager::COLOR,
						'global'    => array(
							'default' => Global_Colors::COLOR_TEXT,
						),
						'default'   => '',
						'selectors' => array(
							'{{WRAPPER}} .stfe-offcanvas-menu .menu-item a' => 'color: {{VALUE}}',
						),
						'condition' => array(
							'content_type' => 'menu',
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
					'menu_item_color_hover',
					array(
						'label'     => __( 'Text Color', 'soft-template-core' ),
						'type'      => Controls_Manager::COLOR,
						'global'    => array(
							'default' => Global_Colors::COLOR_ACCENT,
						),
						'selectors' => array(
							'{{WRAPPER}} .stfe-offcanvas-menu .menu-item a:hover' => 'color: {{VALUE}}',
						),
						'condition' => array(
							'content_type' => 'menu',
						),
					)
				);

				$this->add_control(
					'menu_item_bgcolor_hover',
					array(
						'label'     => __( 'Background Color', 'soft-template-core' ),
						'type'      => Controls_Manager::COLOR,
						'selectors' => array(
							'{{WRAPPER}} .stfe-offcanvas-menu .menu-item a:hover' => 'background-color: {{VALUE}}',
						),
						'condition' => array(
							'content_type' => 'menu',
						),
					)
				);

			$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'submenu_heading',
			array(
				'label'     => __( 'Submenu Item', 'soft-template-core' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => array(
					'content_type'     => 'menu',
					'wrap_the_submenu' => 'yes',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'      => 'submenu_typography',
				'global'    => array(
					'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
				),
				'selector'  => '{{WRAPPER}} .stfe-offcanvas-menu .sub-menu',
				'condition' => array(
					'content_type'     => 'menu',
					'wrap_the_submenu' => 'yes',
				),
			)
		);
		$this->add_responsive_control(
			'submenu_padding',
			array(
				'label'      => __( 'Padding', 'soft-template-core' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'default'    => array(
					'top'      => '5',
					'bottom'   => '5',
					'left'     => '20',
					'right'    => '20',
					'unit'     => 'px',
					'isLinked' => false,
				),
				'selectors'  => array(
					'{{WRAPPER}} .stfe-offcanvas-menu .sub-menu a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'condition'  => array(
					'content_type'     => 'menu',
					'wrap_the_submenu' => 'yes',
				),
			)
		);
		$this->start_controls_tabs( 'tabs_style_submenu_item' );

			$this->start_controls_tab(
				'tab_submenu_item_normal',
				array(
					'label'     => __( 'Normal', 'soft-template-core' ),
					'condition' => array(
						'content_type'     => 'menu',
						'wrap_the_submenu' => 'yes',
					),
				)
			);
			$this->add_control(
				'submenu_item_color',
				array(
					'label'     => __( 'Text Color', 'soft-template-core' ),
					'type'      => Controls_Manager::COLOR,
					'global'    => array(
						'default' => Global_Colors::COLOR_TEXT,
					),
					'default'   => '',
					'selectors' => array(
						'{{WRAPPER}} .stfe-offcanvas-menu .sub-menu a' => 'color: {{VALUE}}',
					),
					'condition' => array(
						'content_type'     => 'menu',
						'wrap_the_submenu' => 'yes',
					),
				)
			);
			$this->end_controls_tab();

			$this->start_controls_tab(
				'tab_submenu_item_hover',
				array(
					'label'     => __( 'Hover', 'soft-template-core' ),
					'condition' => array(
						'content_type'     => 'menu',
						'wrap_the_submenu' => 'yes',
					),
				)
			);

				$this->add_control(
					'submenu_item_color_hover',
					array(
						'label'     => __( 'Text Color', 'soft-template-core' ),
						'type'      => Controls_Manager::COLOR,
						'global'    => array(
							'default' => Global_Colors::COLOR_ACCENT,
						),
						'selectors' => array(
							'{{WRAPPER}} .stfe-offcanvas-menu .sub-menu a:hover' => 'color: {{VALUE}}',
						),
						'condition' => array(
							'content_type'     => 'menu',
							'wrap_the_submenu' => 'yes',
						),
					)
				);

				$this->add_control(
					'submenu_item_bgcolor_hover',
					array(
						'label'     => __( 'Background Color', 'soft-template-core' ),
						'type'      => Controls_Manager::COLOR,
						'selectors' => array(
							'{{WRAPPER}} .stfe-offcanvas-menu .sub-menu a:hover' => 'background-color: {{VALUE}}',
						),
						'condition' => array(
							'content_type'     => 'menu',
							'wrap_the_submenu' => 'yes',
						),
					)
				);

			$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();
	}

	/**
	 * Register offcanvas Content Style Controls.
	 *
	 * @since 1.11.0
	 * @access protected
	 */
	protected function register_content_style_controls() {
		$this->start_controls_section(
			'section_content_style',
			array(
				'label'     => __( 'Content', 'soft-template-core' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'content_type' => 'content',
				),
			)
		);

			$this->add_control(
				'content_text_color',
				array(
					'label'     => __( 'Color', 'soft-template-core' ),
					'type'      => Controls_Manager::COLOR,
					'global'    => array(
						'default' => Global_Colors::COLOR_TEXT,
					),
					'selectors' => array(
						'.uaoffcanvas-{{ID}} .stfe-offcanvas-content' => 'color: {{VALUE}};',
						'{{WRAPPER}} .stfe-offcanvas-content' => 'color: {{VALUE}};',
					),
					'condition' => array(
						'content_type' => 'content',
					),
				)
			);

			$this->add_group_control(
				Group_Control_Typography::get_type(),
				array(
					'name'      => 'content_typography',
					'global'    => array(
						'default' => Global_Typography::TYPOGRAPHY_TEXT,
					),
					'selector'  => '.uaoffcanvas-{{ID}} .stfe-offcanvas-content .stfe-text-editor',
					'separator' => 'before',
					'condition' => array(
						'content_type' => 'content',
					),
				)
			);

			$this->add_group_control(
				Group_Control_Border::get_type(),
				array(
					'name'        => 'content_border',
					'label'       => __( 'Content Border', 'soft-template-core' ),
					'placeholder' => '1px',
					'default'     => '1px',
					'selector'    => '{{WRAPPER}} .uaoffcanvas-{{ID}} .stfe-offcanvas .stfe-offcanvas-content',
					'condition'   => array(
						'content_type' => 'content',
					),
				)
			);

			$this->add_control(
				'content_border_radius',
				array(
					'label'      => __( 'Border Radius', 'soft-template-core' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => array( 'px', '%' ),
					'selectors'  => array(
						'{{WRAPPER}} .uaoffcanvas-{{ID}} .stfe-offcanvas .stfe-offcanvas-content' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					),
					'condition'  => array(
						'content_type' => 'content',
					),
				)
			);

		$this->end_controls_section();
	}

	/**
	 * Register Off-Canvas controls.
	 *
	 * @since 1.11.0
	 * @access protected
	 */
	protected function register_display_offcanvas_controls() {
		$this->start_controls_section(
			'section_offcanvas_controls',
			array(
				'label' => __( 'Off - Canvas', 'soft-template-core' ),
			)
		);

		$this->add_responsive_control(
			'offcanvas_width',
			array(
				'label'          => __( 'Width (px)', 'soft-template-core' ),
				'type'           => Controls_Manager::SLIDER,
				'size_units'     => array( 'px' ),
				'default'        => array(
					'size' => '300',
					'unit' => 'px',
				),
				'tablet_default' => array(
					'size' => '250',
					'unit' => 'px',
				),
				'mobile_default' => array(
					'size' => '200',
					'unit' => 'px',
				),
				'range'          => array(
					'px' => array(
						'min' => 0,
						'max' => 2000,
					),
				),
				'selectors'      => array(
					'{{WRAPPER}} .uaoffcanvas-{{ID}} .stfe-offcanvas' => 'width: {{SIZE}}px;',
					'{{WRAPPER}} .uaoffcanvas-{{ID}}.stfe-offcanvas-parent-wrapper .position-at-left' => 'left: -{{SIZE}}px;',
					'{{WRAPPER}} .uaoffcanvas-{{ID}}.stfe-offcanvas-parent-wrapper .position-at-right' => 'right: -{{SIZE}}px;',
				),
			)
		);
		$this->add_control(
			'offcanvas_position',
			array(
				'label'       => __( 'Position', 'soft-template-core' ),
				'type'        => Controls_Manager::CHOOSE,
				'default'     => 'at-left',
				'options'     => array(
					'at-left'  => array(
						'title' => __( 'Left', 'soft-template-core' ),
						'icon'  => 'fa fa-align-left',
					),
					'at-right' => array(
						'title' => __( 'Right', 'soft-template-core' ),
						'icon'  => 'fa fa-align-right',
					),
				),
				'label_block' => false,
				'toggle'      => false,
			)
		);

		$this->add_control(
			'offcanvas_type',
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
			)
		);

		$this->add_control(
			'page_overlay',
			array(
				'label'       => __( 'Overlay Color', 'soft-template-core' ),
				'type'        => Controls_Manager::COLOR,
				'default'     => 'rgba(0,0,0,0.75)',
				'selectors'   => array(
					'.uaoffcanvas-{{ID}} .stfe-offcanvas-overlay' => 'background: {{VALUE}};',
				),
				'render_type' => 'template',
			)
		);

		$this->end_controls_section();
	}
    
	/**
	 * Render content type list.
	 *
	 * @since 1.11.0
	 * @return array Array of content type
	 * @access public
	 */
	public function get_content_type() {
		$content_type = array(
			'content'              => __( 'Content', 'soft-template-core' ),
			'menu'                 => __( 'Menu', 'soft-template-core' ),
			'saved_rows'           => __( 'Saved Section', 'soft-template-core' ),
			'saved_page_templates' => __( 'Saved Page', 'soft-template-core' ),
		);

		if ( defined( 'ELEMENTOR_PRO_VERSION' ) ) {
			$content_type['saved_modules'] = __( 'Saved Widget', 'soft-template-core' );
		}
		return $content_type;
	}

	/**
	 * Get available menus list
	 *
	 * @since 1.11.0
	 * @return array Array of menu
	 * @access public
	 */
	public function get_menus_list() {
		$menus = wp_get_nav_menus();

		$options = array();

		foreach ( $menus as $menu ) {
			$options[ $menu->slug ] = $menu->name;
		}

		return $options;
	}

	/**
	 * Render Menu HTML.
	 *
	 * @since 1.11.0
	 * @param array $settings The settings array.
	 * @param int   $node_id The node id.
	 * @return string menu HTML
	 * @access public
	 */
	public function get_menu_html( $settings, $node_id ) {

		$menus = $this->get_menus_list();

		if ( ! empty( $menus ) ) {
			$args = array(
				'echo'        => false,
				'menu'        => $settings['menu'],
				'menu_class'  => 'stfe-offcanvas-menu',
				'fallback_cb' => '__return_empty_string',
				'container'   => '',
			);

			if ( 'yes' === $settings['wrap_the_submenu'] ) {
				$menu_toggle_array = array(
					'menu_id' => 'menu-' . $this->get_menu_index() . '-' . $this->get_id(),
					'walker'  => new \Menu_Walker(),
				);
				$args              = array_merge( $args, $menu_toggle_array );
			}
			$menu_html = wp_nav_menu( $args );

			return $menu_html;
		}
	}

	/**
	 * Render Off - Canvas widget classes names.
	 *
	 * @since 1.11.0
	 * @param array $settings The settings array.
	 * @param int   $node_id The node id.
	 * @return string Concatenated string of classes
	 * @access public
	 */
	public function get_offcanvas_content( $settings, $node_id ) {
		$content_type     = $settings['content_type'];
		$dynamic_settings = $this->get_settings_for_display();
		$output_html;

		switch ( $content_type ) {
			case 'content':
				global $wp_embed;
				$output_html = '<div class="stfe-text-editor elementor-inline-editing" data-elementor-setting-key="ct_content" data-elementor-inline-editing-toolbar="advanced">' . wpautop( $wp_embed->autoembed( $dynamic_settings['ct_content'] ) ) . '</div>';
				break;

			case 'menu':
				$menu_content = $this->get_menu_html( $settings, $node_id );
				$output_html  = $menu_content;
				break;

			case 'saved_rows':
				$output_html = \Elementor\Plugin::$instance->frontend->get_builder_content_for_display( $settings['ct_saved_rows'] );
				break;

			case 'saved_modules':
				$output_html = \Elementor\Plugin::$instance->frontend->get_builder_content_for_display( $settings['ct_saved_modules'] );
				break;

			case 'saved_page_templates':
				$output_html = \Elementor\Plugin::$instance->frontend->get_builder_content_for_display( $settings['ct_page_templates'] );
				break;

			default:
				break;
		}

		return $output_html;
	}

	/**
	 * Render Button.
	 *
	 * @since 1.11.0
	 * @param int   $node_id The node id.
	 * @param array $settings The settings array.
	 * @access public
	 */
	public function render_button( $node_id, $settings ) {
		$this->add_render_attribute( 'wrapper', 'class', 'stfe-button-wrapper elementor-button-wrapper' );
		$this->add_render_attribute( 'button', 'href', 'javascript:void(0);' );
		$this->add_render_attribute( 'button', 'class', 'stfe-offcanvas-trigger elementor-button-link elementor-button elementor-clickable' );

		if ( ! empty( $settings['btn_size'] ) ) {
			$this->add_render_attribute( 'button', 'class', 'elementor-size-' . $settings['btn_size'] );
		}

		$position = '';

		if ( 'button' === $settings['offcanvas_on'] ) {
			if ( 'floating' === $settings['uael_display_position'] ) {
				$position = ' stfe-offcanvas-action-alignment-' . $settings['uael_display_floating_align'];

				$this->add_render_attribute( 'button', 'class', '' . $position . '' );
			} else {
				if ( ! empty( $settings['uael_display_inline_button_align'] ) ) {
					
					$this->add_render_attribute( 'wrapper', 'class', 'elementor-align-' . $settings['uael_display_inline_button_align'] );

					if( ! empty($settings['uael_display_inline_button_align_tablet']  ) ) {
						$this->add_render_attribute( 'wrapper', 'class', 'elementor-tablet-align-' . $settings['uael_display_inline_button_align_tablet'] );
					}

					if( ! empty($settings['uael_display_inline_button_align_mobile']  ) ) {
						$this->add_render_attribute( 'wrapper', 'class', 'elementor-mobile-align-' . $settings['uael_display_inline_button_align_mobile'] );
					}

				}
			}
		}

		?>
		<div <?php echo wp_kses_post( $this->get_render_attribute_string( 'wrapper' ) ); ?>>
			<a <?php echo wp_kses_post( $this->get_render_attribute_string( 'button' ) ); ?> data-offcanvas="<?php echo esc_attr( $node_id ); ?>">
				<?php $this->render_button_text(); ?>
			</a>
		</div>
		<?php
	}

	/**
	 * Render close icon.
	 *
	 * @since 1.11.0
	 * @param string $node_id The node id.
	 * @param array  $settings The settings array.
	 * @access protected
	 */
	protected function render_close_icon( $node_id, $settings ) {

		$close_position = '';

		$close_position = 'stfe-offcanvas-close-icon-position-' . $settings['close_inside_icon_position'];

		$this->add_render_attribute( 'close-wrapper', 'class', 'stfe-offcanvas-close-icon-wrapper elementor-icon-wrapper elementor-clickable' );

		$this->add_render_attribute( 'close-wrapper', 'class', $close_position );

		$this->add_render_attribute(
			'close-icon',
			'class',
			'stfe-offcanvas-close elementor-icon-link elementor-clickable '
		);

		if ( \Soft_template_Core_Utils::is_elementor_updated() ) {
			if ( ! isset( $settings['close_icon'] ) && ! \Elementor\Icons_Manager::is_migration_allowed() ) {
				// add old default.
				$settings['close_icon'] = 'fa fa-close';
			}
			$has_icon = ! empty( $settings['close_icon'] );

			if ( ! $has_icon && ! empty( $settings['new_close_icon']['value'] ) ) {
				$has_icon = true;
			}

			$close_migrated = isset( $settings['__fa4_migrated']['new_close_icon'] );
			$close_is_new   = ! isset( $settings['close_icon'] ) && \Elementor\Icons_Manager::is_migration_allowed();

			if ( $has_icon ) {
				?>
				<div <?php echo wp_kses_post( $this->get_render_attribute_string( 'close-wrapper' ) ); ?>>
					<span <?php echo wp_kses_post( $this->get_render_attribute_string( 'close-icon' ) ); ?>>
						<span class="stfe-offcanvas-close-icon">
							<?php if ( $close_migrated || $close_is_new ) { ?>
									<?php \Elementor\Icons_Manager::render_icon( $settings['new_close_icon'], array( 'aria-hidden' => 'true' ) ); ?>
							<?php } elseif ( ! empty( $settings['close_icon'] ) ) { ?>
								<i class="<?php echo esc_attr( $settings['close_icon'] ); ?>"></i>
							<?php } ?>
						</span>
					</span>
				</div>
				<?php
			}
		} elseif ( ! empty( $settings['close_icon'] ) ) {
			?>
			<div <?php echo wp_kses_post( $this->get_render_attribute_string( 'close-wrapper' ) ); ?>>
				<span <?php echo wp_kses_post( $this->get_render_attribute_string( 'close-icon' ) ); ?>>
					<span class="stfe-offcanvas-close-icon">
						<i class="<?php echo esc_attr( $settings['close_icon'] ); ?>"></i>
					</span>
				</span>
			</div>
			<?php
		}
	}

	/**
	 * Render button text.
	 *
	 * @since 1.11.0
	 * @access protected
	 */
	protected function render_button_text() {

		$settings = $this->get_settings();

		$this->add_render_attribute( 'content-wrapper', 'class', 'elementor-button-content-wrapper' );

		$this->add_render_attribute(
			'btn-text',
			array(
				'class'                                 => 'elementor-button-text elementor-inline-editing',
				'data-elementor-setting-key'            => 'btn_text',
				'data-elementor-inline-editing-toolbar' => 'none',
			)
		);

		$this->add_render_attribute(
			'icon-align',
			array(
				'class' => 'elementor-button-icon elementor-align-icon-' . $settings['offcanvas_button_icon_position'],
			)
		);

		?>
		<span <?php echo wp_kses_post( $this->get_render_attribute_string( 'content-wrapper' ) ); ?>>

			<?php if ( \Soft_template_Core_Utils::is_elementor_updated() ) { ?>
				<?php if ( ! empty( $settings['offcanvas_button_icon'] ) || ! empty( $settings['new_offcanvas_button_icon'] ) ) { ?>
					<span <?php echo wp_kses_post( $this->get_render_attribute_string( 'icon-align' ) ); ?>>
						<?php
						$button_icon_migrated = isset( $settings['__fa4_migrated']['new_offcanvas_button_icon'] );
						$button_icon_is_new   = ! isset( $settings['offcanvas_button_icon'] );
						if ( $button_icon_is_new || $button_icon_migrated ) {
							\Elementor\Icons_Manager::render_icon( $settings['new_offcanvas_button_icon'], array( 'aria-hidden' => 'true' ) );
						} elseif ( ! empty( $settings['offcanvas_button_icon'] ) ) {
							?>
							<i class="<?php echo esc_attr( $settings['offcanvas_button_icon'] ); ?>" aria-hidden="true"></i>
						<?php } ?>
					</span>
				<?php } ?>
			<?php } elseif ( ! empty( $settings['offcanvas_button_icon'] ) ) { ?>
				<span <?php echo wp_kses_post( $this->get_render_attribute_string( 'icon-align' ) ); ?>>
					<i class="<?php echo esc_attr( $settings['offcanvas_button_icon'] ); ?>" aria-hidden="true"></i>
				</span>
			<?php } ?>
			<span <?php echo wp_kses_post( $this->get_render_attribute_string( 'btn-text' ) ); ?>><?php echo wp_kses_post( $this->get_settings_for_display( 'btn_text' ) ); ?></span>
		</span>
		<?php
	}

	/**
	 * Render action HTML.
	 *
	 * @since 1.11.0
	 * @access protected
	 */
	protected function render_action_html() {
		$settings = $this->get_settings();
		$id       = $this->get_id();

		$is_editor = \Elementor\Plugin::instance()->editor->is_edit_mode();

		if ( 'button' === $settings['offcanvas_on'] ) {
			$this->render_button( $id, $settings );
			if ( ( 'floating' === $settings['uael_display_position'] ) && $is_editor ) {
				?>
				<div class="stfe-builder-msg" style="text-align: center;">
					<h5><?php esc_attr_e( 'Off - Canvas - ID ', 'soft-template-core' ); ?><?php echo esc_attr( $id ); ?></h5>
					<p><?php esc_attr_e( 'Click here to edit the "Off- Canvas" settings. This text will not be visible on frontend.', 'soft-template-core' ); ?></p>
				</div>

				<?php
			}
		} elseif ( (
			'custom' === $settings['offcanvas_on'] ||
			'custom_id' === $settings['offcanvas_on'] ) &&
			$is_editor
		) {
			?>
			<div class="stfe-builder-msg" style="text-align: center;">
				<h5><?php esc_attr_e( 'Off - Canvas - ID ', 'soft-template-core' ); ?><?php echo esc_attr( $id ); ?></h5>
				<p><?php esc_attr_e( 'Click here to edit the "Off- Canvas" settings. This text will not be visible on frontend.', 'soft-template-core' ); ?></p>
			</div>
			<?php
		} else {
			$inner_html = '';
			$position   = '';

			$this->add_render_attribute(
				'action-wrap',
				'class',
				array(
					'stfe-offcanvas-action',
					'elementor-clickable',
					'stfe-offcanvas-trigger',
				)
			);

			$this->add_render_attribute( 'action-wrap', 'data-offcanvas', $id );

			switch ( $settings['offcanvas_on'] ) {
				case 'icon':
					$this->add_render_attribute( 'action-wrap', 'class', 'stfe-offcanvas-icon-wrap' );

					if ( 'floating' === $settings['uael_display_position'] ) {
						$position = ' stfe-offcanvas-action-alignment-' . $settings['uael_display_floating_align'];
					}
					if ( ( 'floating' === $settings['uael_display_position'] ) && $is_editor ) {
						?>
						<div class="stfe-builder-msg" style="text-align: center;">
								<h5><?php esc_attr_e( 'Off - Canvas - ID ', 'soft-template-core' ); ?><?php echo esc_attr( $id ); ?></h5>
								<p><?php esc_attr_e( 'Click here to edit the "Off- Canvas" settings. This text will not be visible on frontend.', 'soft-template-core' ); ?></p>
						</div>
						<?php
					}

					if ( \Soft_template_Core_Utils::is_elementor_updated() ) {
						$icon_migrated = isset( $settings['__fa4_migrated']['new_icon'] );
						$icon_is_new   = ! isset( $settings['icon'] );

						if ( $icon_migrated || $icon_is_new ) {
							ob_start();
							\Elementor\Icons_Manager::render_icon( $settings['new_icon'], array( 'aria-hidden' => 'true' ) );
							$inner_html = ob_get_clean();
						} elseif ( ! empty( $settings['icon'] ) ) {
							$inner_html = '<i class="' . $settings['icon'] . '" aria-hidden="true"></i>';
						}
					} elseif ( ! empty( $settings['icon'] ) ) {
						$inner_html = '<i class="' . $settings['icon'] . '" aria-hidden="true"></i>';
					}

				break;
			}
			?>
			<div <?php echo wp_kses_post( $this->get_render_attribute_string( 'action-wrap' ) ); ?>><span class="stfe-offcanvas-icon-bg stfe-offcanvas-icon <?php echo esc_attr( $position ); ?>"><?php echo $inner_html; //phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span></div>
			<?php
		}
	}

	/**
	 * Close Render action HTML.
	 *
	 * @since 1.11.0
	 * @access protected
	 */
	protected function close_render_action_html() {
		$settings = $this->get_settings_for_display();
		$id       = $this->get_id();

		$is_editor = \Elementor\Plugin::instance()->editor->is_edit_mode();

		if ( ! empty( $settings['close_icon'] ) || ! empty( $settings['new_close_icon'] ) ) {
			$this->render_close_icon( $id, $settings );
		}
	}

	/**
	 * Get Data Attributes.
	 *
	 * @since 1.11.0
	 * @param array $settings The settings array.
	 * @return string Data Attributes
	 * @access public
	 */
	public function get_parent_wrapper_attributes( $settings ) {
		$id = $this->get_id();
		$this->add_render_attribute(
			'parent-wrapper',
			array(
				'id'                    => $id . '-overlay',
				'data-trigger-on'       => $settings['offcanvas_on'],

				'data-close-on-overlay' => $settings['overlay_click'],

				'data-close-on-esc'     => $settings['esc_keypress'],

				'data-content'          => $settings['content_type'],

				'data-device'           => ( false !== ( stripos( $_SERVER['HTTP_USER_AGENT'], 'iPhone' ) ) ? 'true' : 'false' ),

				'data-custom'           => $settings['offcanvas_custom'],

				'data-custom-id'        => $settings['offcanvas_custom_id'],

				'data-canvas-width'     => $settings['offcanvas_width']['size'],
				'data-wrap-menu-item'   => $settings['wrap_the_submenu'],
			)
		);

		$this->add_render_attribute(
			'parent-wrapper',
			'class',
			array(
				'stfe-offcanvas-parent-wrapper',
				'stfe-module-content',
				'uaoffcanvas-' . $id,
			)
		);

		return $this->get_render_attribute_string( 'parent-wrapper' );
	}

	/**
	 * Render offcanvas output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 1.11.0
	 * @access protected
	 */
	protected function render() {

		$settings  = $this->get_settings();
		$node_id   = $this->get_id();
		$is_editor = \Elementor\Plugin::instance()->editor->is_edit_mode();

		$class = ( $is_editor && 'yes' === $settings['preview_offcanvas'] ) ? 'stfe-show-preview' : '';

		$shadowclass = ( '' !== $settings['page_overlay'] ) ? 'stfe-offcanvas-shadow-inset' : 'stfe-offcanvas-shadow-normal';

		// To Disable scroll when overlay is added to the page add the condition of page_overlay option to $scrollclass.
		$scrollclass = 'stfe-offcanvas-scroll-disable';

		$editor_mode_class = ( $is_editor ) ? 'stfe-editor-mode' : '';

		$this->add_inline_editing_attributes( 'btn_text', 'none' );
		$this->add_inline_editing_attributes( 'ct_content', 'advanced' );

		$this->add_render_attribute( 'inner-wrapper', 'id', 'offcanvas-' . $node_id );

		$this->add_render_attribute(
			'inner-wrapper',
			'class',
			array(
				'stfe-offcanvas',
				'stfe-custom-offcanvas',
				$class,
				$editor_mode_class,
				'stfe-offcanvas-type-' . $settings['offcanvas_type'],
				$scrollclass,
				$shadowclass,
			)
		);

		$this->add_render_attribute( 'inner-wrapper', 'class', 'position-' . $settings['offcanvas_position'] );
		?>

		<div <?php echo wp_kses_post( $this->get_parent_wrapper_attributes( $settings ) ); ?> >
			<div <?php echo wp_kses_post( $this->get_render_attribute_string( 'inner-wrapper' ) ); ?>>
				<div class="stfe-offcanvas-content">
					<div class="stfe-offcanvas-action-wrap">
						<?php echo wp_kses_post( $this->close_render_action_html() ); ?>
					</div>
					<div class="stfe-offcanvas-text stfe-offcanvas-content-data">
						<?php echo do_shortcode( $this->get_offcanvas_content( $settings, $node_id ) ); ?>
					</div>
				</div>
			</div>
			<div class="stfe-offcanvas-overlay elementor-clickable"></div>
		</div>
			<div class="stfe-offcanvas-action-wrap">
				<?php echo wp_kses_post( $this->render_action_html() ); ?>
			</div>
		<?php
	}

	/**
	 * Render offcanvas output in the editor.
	 *
	 * Written as a Backbone JavaScript template and used to generate the live preview.
	 *
	 * @since 1.22.1
	 * @access protected
	 */
	protected function content_template() {
	}    
}