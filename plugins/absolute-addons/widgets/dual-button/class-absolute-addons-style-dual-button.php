<?php

namespace AbsoluteAddons\Widgets;

use AbsoluteAddons\Controls\Absp_Control_Styles;
use Elementor\Controls_Manager;
use AbsoluteAddons\Absp_Widget;
use Elementor\Icons_Manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * @since 1.1.0
 */
class Absoluteaddons_Style_Dual_Button extends Absp_Widget {

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
		return 'absolute-dual-button';
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
		return __( 'Dual Button', 'absolute-addons' );
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
		return 'absp eicon-dual-button';
	}

	/**
	 * Requires css files.
	 *
	 * @return array
	 */
	public function get_style_depends() {
		return array(
			'absolute-addons-btn',
			'absp-dual-button',
		);
	}

	/**
	 * Requires js files.
	 *
	 * @return array
	 */
	public function get_script_depends() {
		return array(
			'absolute-addons-button',
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
		return array( 'absp-widgets' );
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
		 * @param Absoluteaddons_Style_Dual_Button $this Current instance of WP_Network_Query (passed by reference).
		 *
		 * @since 1.0.0
		 *
		 */
		do_action_ref_array( $this->get_prefixed_hook( 'controllers/starts' ), [ &$this ] );

		$this->start_controls_section(
			'section_template',
			array(
				'label' => __( 'Template', 'absolute-addons' ),
			)
		);

		$button = apply_filters( $this->get_prefixed_hook( 'styles' ), [
			'one'       => __( 'Style One', 'absolute-addons' ),
			'two'       => __( 'Style Two', 'absolute-addons' ),
			'three-pro' => __( 'Style Three (Upcoming)', 'absolute-addons' ),
		] );

		$pro_styles = [
			'three-pro',
		];

		$this->add_control(
			'absolute_dual_button',
			array(
				'label'       => __( 'Dual Button Style', 'absolute-addons' ),
				'label_block' => true,
				'type'        => Absp_Control_Styles::TYPE,
				'options'     => $button,
				'disabled'    => 'three-pro',
				'default'     => 'one',
			)
		);

		$this->init_pro_alert( $pro_styles );

		$this->end_controls_section();

		$this->button_section();
		// Content Controllers
		$this->render_controller( 'template-dual-button-style' );

		/**
		 * Fires after controllers are registered.
		 *
		 * @param Absoluteaddons_Style_Dual_Button $this Current instance of WP_Network_Query (passed by reference).
		 *
		 * @since 1.0.0
		 *
		 */
		do_action_ref_array( $this->get_prefixed_hook( 'controllers/ends' ), [ &$this ] );

	}

	protected function button_section() {
		$this->start_controls_section(
			'absp_dual_button_section',
			[
				'label' => __( 'Dual Button', 'absolute-addons' ),
			]
		);

		$this->start_controls_tabs( 'absp_dual_button_primary_tabs' );

		$this->start_controls_tab(
			'absp_dual_button_primary',
			[
				'label' => __( 'Primary', 'absolute-addons' ),
			]
		);

		$this->add_control(
			'absp_dual_button_type',
			[
				'label'   => __( 'Dual Button Type', 'absolute-addons' ),
				'type'    => Controls_Manager::SELECT,
				'options' => [
					'-default' => __( 'Default', 'absolute-addons' ),
					'-info'    => __( 'Info', 'absolute-addons' ),
					'-success' => __( 'Success', 'absolute-addons' ),
					'-warning' => __( 'Warning', 'absolute-addons' ),
					'-danger'  => __( 'Danger', 'absolute-addons' ),
				],
				'default' => '-default',
			]
		);

		$this->add_control(
			'absp_dual_button_outline',
			[
				'label'   => __( 'Show Outline Button', 'absolute-addons' ),
				'type'    => Controls_Manager::SELECT,
				'options' => [
					'-outline' => 'Yes',
					''         => 'No',
				],
				'default' => '',
			]
		);

		$this->add_control(
			'absp_dual_button_radius',
			[
				'label'   => __( 'Button Radius', 'absolute-addons' ),
				'type'    => Controls_Manager::SELECT,
				'options' => [
					'none'       => 'None',
					'round'      => 'Round',
					'rounded'    => 'Rounded',
					'round-full' => 'Full',
				],
				'default' => 'none',
			]
		);

		$this->add_control(
			'absp_dual_button_disabled',
			[
				'label'     => __( 'Disabled Button?', 'absolute-addons' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => [
					'absp-btn-disabled' => 'Yes',
					''                  => 'No',
				],
				'default'   => '',
				'separator' => 'after',
			]
		);

		$this->add_control(
			'absp_dual_button_html_tag',
			[
				'label'     => __( 'Button HTML Tag', 'absolute-addons' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => [
					'button' => 'Button',
					'a'      => 'a',
				],
				'default'   => 'a',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'absp_dual_button_text',
			[
				'label'       => __( 'Button Text', 'absolute-addons' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => __( 'Click here', 'absolute-addons' ),
				'placeholder' => __( 'Click here', 'absolute-addons' ),
				'dynamic'     => [
					'active' => true,
				],
			]
		);

		$this->add_control(
			'absp_dual_button_link',
			[
				'label'         => __( 'Link', 'absolute-addons' ),
				'type'          => Controls_Manager::URL,
				'placeholder'   => __( 'https://your-link.com', 'absolute-addons' ),
				'show_external' => true,
				'dynamic'       => [
					'active' => true,
				],
				'default'       => [
					'url'         => '',
					'is_external' => true,
					'nofollow'    => true,
				],
				'condition'     => [
					'absp_dual_button_html_tag!' => 'button',
					'absp_dual_button_disabled!' => 'absp-btn-disabled',
				],
			]
		);

		$this->add_control(
			'absp_dual_button_size',
			[
				'label'   => __( 'Button Size', 'absolute-addons' ),
				'type'    => Controls_Manager::SELECT,
				'options' => [
					'sm' => __( 'Small', 'absolute-addons' ),
					'md' => __( 'Medium', 'absolute-addons' ),
					'lg' => __( 'Large', 'absolute-addons' ),
					'xl' => __( 'Extra Large', 'absolute-addons' ),
				],
				'default' => 'lg',
			]
		);

		$this->add_control(
			'absp_dual_button_icons_switch',
			[
				'label'     => __( 'Add icon? ', 'absolute-addons' ),
				'type'      => Controls_Manager::SWITCHER,
				'default'   => 'yes',
				'label_on'  => __( 'Yes', 'absolute-addons' ),
				'label_off' => __( 'No', 'absolute-addons' ),
			]
		);

		$this->add_control(
			'absp_dual_button_icons',
			[
				'label'       => __( 'Icon', 'absolute-addons' ),
				'type'        => Controls_Manager::ICONS,
				'default'     => [
					'value' => '',
				],
				'label_block' => true,
				'condition'   => [
					'absp_dual_button_icons_switch' => 'yes',
				],
			]
		);

		$this->add_control(
			'absp_dual_button_icon_align',
			[
				'label'     => __( 'Icon Position', 'absolute-addons' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => [
					'left'  => __( 'Before', 'absolute-addons' ),
					'right' => __( 'After', 'absolute-addons' ),
				],
				'default'   => 'right',
				'condition' => [
					'absp_dual_button_icons_switch' => 'yes',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'absp_dual_button_connector',
			[
				'label' => __( 'Connector', 'absolute-addons' ),
			]
		);

		$this->add_control(
			'absp_dual_button_connector_switch',
			[
				'label'          => __( 'Hide Connector?', 'absolute-addons' ),
				'type'           => Controls_Manager::SWITCHER,
				'label_on'       => __( 'Yes', 'absolute-addons' ),
				'label_off'      => __( 'No', 'absolute-addons' ),
				'style_transfer' => true,
			]
		);

		$this->add_control(
			'absp_dual_button_connector_radius',
			[
				'label'   => __( 'Button Radius', 'absolute-addons' ),
				'type'    => Controls_Manager::SELECT,
				'options' => [
					'none'       => 'None',
					'round'      => 'Round',
					'rounded'    => 'Rounded',
					'round-full' => 'Full',
				],
				'default' => 'round-full',
			]
		);

		$this->add_control(
			'absp_dual_button_connector_type',
			[
				'label'     => __( 'Connector Type', 'absolute-addons' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => [
					'text' => __( 'Text', 'absolute-addons' ),
					'icon' => __( 'Icon', 'absolute-addons' ),
				],
				'default'   => 'text',
				'condition' => [
					'absp_dual_button_connector_switch!' => 'yes',
				],
			]
		);

		$this->add_control(
			'absp_dual_button_connector_text',
			[
				'label'       => __( 'Button Text', 'absolute-addons' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => __( 'Or', 'absolute-addons' ),
				'placeholder' => __( 'Text Here', 'absolute-addons' ),
				'dynamic'     => [
					'active' => true,
				],
				'condition'   => [
					'absp_dual_button_connector_type'    => 'text',
					'absp_dual_button_connector_switch!' => 'yes',
				],
			]
		);

		$this->add_control(
			'absp_dual_button_connector_icon',
			[
				'label'       => __( 'Icon', 'absolute-addons' ),
				'type'        => Controls_Manager::ICONS,
				'default'     => [
					'value' => '',
				],
				'label_block' => true,
				'condition'   => [
					'absp_dual_button_connector_type'    => 'icon',
					'absp_dual_button_connector_switch!' => 'yes',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'absp_dual_button_secondary',
			[
				'label' => __( 'Secondary', 'absolute-addons' ),
			]
		);

		$this->add_control(
			'absp_dual_button_type_secondary',
			[
				'label'   => __( 'Dual Button Type', 'absolute-addons' ),
				'type'    => Controls_Manager::SELECT,
				'options' => [
					'-default' => __( 'Default', 'absolute-addons' ),
					'-info'    => __( 'Info', 'absolute-addons' ),
					'-success' => __( 'Success', 'absolute-addons' ),
					'-warning' => __( 'Warning', 'absolute-addons' ),
					'-danger'  => __( 'Danger', 'absolute-addons' ),
				],
				'default' => '-default',
			]
		);

		$this->add_control(
			'absp_dual_button_outline_secondary',
			[
				'label'   => __( 'Show Outline Button', 'absolute-addons' ),
				'type'    => Controls_Manager::SELECT,
				'options' => [
					'-outline' => 'Yes',
					''         => 'No',
				],
				'default' => '',
			]
		);

		$this->add_control(
			'absp_dual_button_radius_secondary',
			[
				'label'   => __( 'Button Radius', 'absolute-addons' ),
				'type'    => Controls_Manager::SELECT,
				'options' => [
					'none'       => 'None',
					'round'      => 'Round',
					'rounded'    => 'Rounded',
					'round-full' => 'Full',
				],
				'default' => 'none',
			]
		);

		$this->add_control(
			'absp_dual_button_disabled_secondary',
			[
				'label'     => __( 'Disabled Button?', 'absolute-addons' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => [
					'absp-btn-disabled' => 'Yes',
					''                  => 'No',
				],
				'default'   => '',
				'separator' => 'after',
			]
		);

		$this->add_control(
			'absp_dual_button_html_tag_secondary',
			[
				'label'     => __( 'Button HTML Tag', 'absolute-addons' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => [
					'button' => 'Button',
					'a'      => 'a',
				],
				'default'   => 'a',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'absp_dual_button_text_secondary',
			[
				'label'       => __( 'Button Text', 'absolute-addons' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => __( 'Click here', 'absolute-addons' ),
				'placeholder' => __( 'Click here', 'absolute-addons' ),
				'dynamic'     => [
					'active' => true,
				],
			]
		);

		$this->add_control(
			'absp_dual_button_link_secondary',
			[
				'label'         => __( 'Link', 'absolute-addons' ),
				'type'          => Controls_Manager::URL,
				'placeholder'   => __( 'https://your-link.com', 'absolute-addons' ),
				'show_external' => true,
				'dynamic'       => [
					'active' => true,
				],
				'default'       => [
					'url'         => '',
					'is_external' => true,
					'nofollow'    => true,
				],
				'condition'     => [
					'absp_dual_button_html_tag!' => 'button',
					'absp_dual_button_disabled!' => 'absp-btn-disabled',
				],
			]
		);

		$this->add_control(
			'absp_dual_button_size_secondary',
			[
				'label'   => __( 'Button Size', 'absolute-addons' ),
				'type'    => Controls_Manager::SELECT,
				'options' => [
					'sm' => __( 'Small', 'absolute-addons' ),
					'md' => __( 'Medium', 'absolute-addons' ),
					'lg' => __( 'Large', 'absolute-addons' ),
					'xl' => __( 'Extra Large', 'absolute-addons' ),
				],
				'default' => 'lg',
			]
		);

		$this->add_control(
			'absp_dual_button_icons_switch_secondary',
			[
				'label'     => __( 'Add icon? ', 'absolute-addons' ),
				'type'      => Controls_Manager::SWITCHER,
				'default'   => 'yes',
				'label_on'  => __( 'Yes', 'absolute-addons' ),
				'label_off' => __( 'No', 'absolute-addons' ),
			]
		);

		$this->add_control(
			'absp_dual_button_icons_secondary',
			[
				'label'       => __( 'Icon', 'absolute-addons' ),
				'type'        => Controls_Manager::ICONS,
				'default'     => [
					'value' => '',
				],
				'label_block' => true,
				'condition'   => [
					'absp_dual_button_icons_switch_secondary' => 'yes',
				],
			]
		);

		$this->add_control(
			'absp_dual_button_icon_align_secondary',
			[
				'label'     => __( 'Icon Position', 'absolute-addons' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => [
					'left'  => __( 'Before', 'absolute-addons' ),
					'right' => __( 'After', 'absolute-addons' ),
				],
				'default'   => 'right',
				'condition' => [
					'absp_dual_button_icons_switch_secondary' => 'yes',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_responsive_control(
			'absp_dual_btn_space',
			[
				'label'      => esc_html__( 'Button Space Between', 'absolute-addons' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'rem' ],
				'range'      => [
					'px'  => [
						'min'  => 0,
						'max'  => 100,
						'step' => 1,
					],
					'rem' => [
						'min' => 0,
						'max' => 10,
					],
				],
				'selectors'  => [
					'{{WRAPPER}} .absp-dual-button .absp-btn.absp-btn-primary'   => 'margin-right: calc({{SIZE}}{{UNIT}} / 2);',
					'{{WRAPPER}} .absp-dual-button .absp-btn.absp-btn-secondary' => 'margin-left: calc({{SIZE}}{{UNIT}} / 2);',
				],
				'separator'  => 'before',
			]
		);

		$this->add_responsive_control(
			'absp_dual_button_align',
			[
				'label'     => __( 'Alignment', 'absolute-addons' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => [
					'left'   => [
						'title' => __( 'Left', 'absolute-addons' ),
						'icon'  => 'eicon-text-align-left',
					],
					'center' => [
						'title' => __( 'Center', 'absolute-addons' ),
						'icon'  => 'eicon-text-align-center',
					],
					'right'  => [
						'title' => __( 'Right', 'absolute-addons' ),
						'icon'  => 'eicon-text-align-right',
					],
				],
				'selectors' => [
					'{{WRAPPER}} .absp-dual-button' => 'text-align: {{VALUE}};',
				],
				'default'   => 'left',
			]
		);

		$this->add_responsive_control(
			'absp_dual_button_layout',
			[
				'label'           => __( 'Button Layout', 'absolute-addons' ),
				'type'            => Controls_Manager::CHOOSE,
				'options'         => [
					'row'    => [
						'title' => __( 'Row', 'absolute-addons' ),
						'icon'  => 'eicon-h-align-left',
					],
					'column' => [
						'title' => __( 'Column', 'absolute-addons' ),
						'icon'  => 'eicon-v-align-top',
					],
				],
				'desktop_default' => 'row',
				'tablet_default'  => 'row',
				'mobile_default'  => 'row',
				'prefix_class'    => 'absp-dual-button%s-layout-',
				'selectors'       => [
					'(desktop+){{WRAPPER}} .absp-dual-button .absp-dual-button-container' => 'flex-direction: {{absp_dual_button_layout.VALUE}};',
					'(tablet){{WRAPPER}} .absp-dual-button .absp-dual-button-container'   => 'flex-direction: {{absp_dual_button_layout_tablet.VALUE}};',
					'(mobile){{WRAPPER}} .absp-dual-button .absp-dual-button-container'   => 'flex-direction: {{absp_dual_button_layout_mobile.VALUE}};',
				],
			]
		);

		$this->end_controls_section();
	}

	protected function render() {
		$settings = $this->get_settings_for_display();
		$style    = $settings['absolute_dual_button'];

		?>
		<div class="absp-wrapper absp-widget">
			<div class="absp-dual-button absp-btn-<?php echo esc_attr( $settings['absp_dual_button_align'] ); ?>">
				<div class="absp-dual-button-<?php echo esc_attr( $style ); ?>">
					<div class="absp-dual-button-container absp-btn-layout-<?php echo esc_attr( $settings['absp_dual_button_layout'] ); ?>">
						<?php $this->button_primary( $settings, 'absp-btn' . $settings['absp_dual_button_outline'] . '-black absp-btn' . $settings['absp_dual_button_outline'] . $settings['absp_dual_button_type'] ); ?>
						<?php $this->button_secondary( $settings, 'absp-btn' . $settings['absp_dual_button_outline_secondary'] . '-black absp-btn' . $settings['absp_dual_button_outline_secondary'] . $settings['absp_dual_button_type_secondary'] ); ?>
					</div>
				</div>
			</div>
		</div><!-- end .absp-wrapper -->
		<?php
	}

	protected function button_primary( $settings, $class_name ) {
		$this->add_render_attribute( [
			'absp_dual_btn_link_option' => [
				'target' => isset( $settings['absp_button_link']['is_external'] ) ? '_blank' : '',
				'rel'    => isset( $settings['absp_button_link']['nofollow'] ) ? 'nofollow' : '',
			],
		] );

		$this->add_render_attribute( 'absp_dual_btn_attr', 'class', 'absp-btn absp-btn-primary' );
		$this->add_render_attribute( 'absp_dual_btn_attr', 'class', $class_name );
		$this->add_render_attribute( 'absp_dual_btn_attr', 'class', 'absp-btn-' . esc_attr( $settings['absp_dual_button_size'] ) );
		$this->add_render_attribute( 'absp_dual_btn_attr', 'class', 'absp-btn-' . esc_attr( $settings['absp_dual_button_radius'] ) );
		$this->add_render_attribute( 'absp_dual_btn_attr', 'class', esc_attr( $settings['absp_dual_button_disabled'] ) );

		$href = isset($settings['absp_dual_button_link']['url']);

		?>
		<div class="absp-dual-button">
			<?php if ( 'button' === $settings['absp_dual_button_html_tag'] ) { ?>
				<button <?php $this->print_render_attribute_string( 'absp_dual_btn_attr' ); ?> type="button">
					<?php
					if ( 'left' === $settings['absp_dual_button_icon_align'] ) {
						if ( 'yes' === $settings['absp_dual_button_icons_switch'] ) {
							Icons_Manager::render_icon( $settings['absp_dual_button_icons'], [ 'aria-hidden' => 'true' ] );
						}
						echo esc_html( $settings['absp_dual_button_text'] );
					} else {
						echo esc_html( $settings['absp_dual_button_text'] );
						if ( 'yes' === $settings['absp_dual_button_icons_switch'] ) {
							Icons_Manager::render_icon( $settings['absp_dual_button_icons'], [ 'aria-hidden' => 'true' ] );
						}
					}
					?>
				</button>
			<?php } else { ?>
				<a href="<?php echo esc_url( $href ); ?>" <?php $this->print_render_attribute_string( 'absp_dual_btn_attr' ); ?> <?php $this->print_render_attribute_string( 'absp_dual_btn_link_option' ); ?> role="button">
					<?php
					if ( 'left' === $settings['absp_dual_button_icon_align'] ) {
						if ( 'yes' === $settings['absp_dual_button_icons_switch'] ) {
							Icons_Manager::render_icon( $settings['absp_dual_button_icons'], [ 'aria-hidden' => 'true' ] );
						}
						echo esc_html( $settings['absp_dual_button_text'] );
					} else {
						echo esc_html( $settings['absp_dual_button_text'] );
						if ( 'yes' === $settings['absp_dual_button_icons_switch'] ) {
							Icons_Manager::render_icon( $settings['absp_dual_button_icons'], [ 'aria-hidden' => 'true' ] );
						}
					}
					?>
				</a>
			<?php }
			$this->button_connector( $settings ); ?>
		</div>
		<?php
	}

	protected function button_secondary( $settings, $class_name ) {
		$this->add_render_attribute( [
			'absp_secondary_dual_btn_link_option' => [
				'target' => isset( [ 'absp_dual_button_link_secondary' ]['is_external'] ) ? '_blank' : '',
				'rel'    => isset( [ 'absp_dual_button_link_secondary' ]['nofollow'] ) ? 'nofollow' : '',
			],
		] );

		$this->add_render_attribute( 'absp_secondary_dual_btn_attr', 'class', 'absp-btn absp-btn-secondary' );
		$this->add_render_attribute( 'absp_secondary_dual_btn_attr', 'class', $class_name );
		$this->add_render_attribute( 'absp_secondary_dual_btn_attr', 'class', 'absp-btn-' . esc_attr( $settings['absp_dual_button_size_secondary'] ) . '' );
		$this->add_render_attribute( 'absp_secondary_dual_btn_attr', 'class', 'absp-btn-' . esc_attr( $settings['absp_dual_button_radius_secondary'] ) . '' );
		$this->add_render_attribute( 'absp_secondary_dual_btn_attr', 'class', esc_attr( $settings['absp_dual_button_disabled_secondary'] ) );

		$href = isset($settings['absp_dual_button_link_secondary']['url']);

		?>
		<div class="absp-dual-button">
			<?php if ( 'button' === $settings['absp_dual_button_html_tag_secondary'] ) { ?>
				<button <?php $this->print_render_attribute_string( 'absp_secondary_dual_btn_attr' ); ?> type="button">
					<?php
					if ( 'left' === $settings['absp_dual_button_icon_align_secondary'] ) {
						if ( 'yes' === $settings['absp_dual_button_icons_switch'] ) {
							Icons_Manager::render_icon( $settings['absp_dual_button_icons_secondary'], [ 'aria-hidden' => 'true' ] );
						}
						echo esc_html( $settings['absp_dual_button_text_secondary'] );
					} else {
						echo esc_html( $settings['absp_dual_button_text_secondary'] );
						if ( 'yes' === $settings['absp_dual_button_icons_switch_secondary'] ) {
							Icons_Manager::render_icon( $settings['absp_dual_button_icons_secondary'], [ 'aria-hidden' => 'true' ] );
						}
					}
					?>
				</button>
			<?php } else { ?>
				<a href="<?php echo esc_url( $href ); ?>" <?php $this->print_render_attribute_string( 'absp_secondary_dual_btn_attr' ); ?><?php $this->print_render_attribute_string( 'absp_dual_btn_link_option_secondary' ); ?> role="button">
					<?php
					if ( 'left' === $settings['absp_dual_button_icon_align_secondary'] ) {
						if ( 'yes' === $settings['absp_dual_button_icons_switch_secondary'] ) {
							Icons_Manager::render_icon( $settings['absp_dual_button_icons_secondary'], [ 'aria-hidden' => 'true' ] );
						}
						echo esc_html( $settings['absp_dual_button_text_secondary'] );
					} else {
						echo esc_html( $settings['absp_dual_button_text_secondary'] );
						if ( 'yes' === $settings['absp_dual_button_icons_switch_secondary'] ) {
							Icons_Manager::render_icon( $settings['absp_dual_button_icons_secondary'], [ 'aria-hidden' => 'true' ] );
						}
					}
					?>
				</a>
			<?php } ?>
		</div>
		<?php
	}

	protected function button_connector( $settings ) {
		if ( 'yes' !== $settings['absp_dual_button_connector_switch'] ) {
			if ( 'icon' === $settings['absp_dual_button_connector_type'] ) { ?>
				<span class="absp-btn-connector absp-connector-<?php echo esc_attr( $settings['absp_dual_button_connector_type'] ); ?> absp-btn-<?php echo esc_attr( $settings['absp_dual_button_connector_radius'] ); ?>">
					<?php Icons_Manager::render_icon( $settings['absp_dual_button_connector_icon'], [ 'aria-hidden' => 'true' ] ); ?>
				</span>
			<?php } else { ?>
				<span class="absp-btn-connector absp-connector-<?php echo esc_attr( $settings['absp_dual_button_connector_type'] ); ?> absp-btn-<?php echo esc_attr( $settings['absp_dual_button_connector_radius'] ); ?>">
					<?php echo esc_html( $settings['absp_dual_button_connector_text'] ); ?>
				</span>
			<?php }
		}
	}
}
