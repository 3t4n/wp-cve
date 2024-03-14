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
class Absoluteaddons_Style_Button extends Absp_Widget {

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
		return 'absolute-button';
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
		return __( 'Button', 'absolute-addons' );
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
		return 'absp eicon-button';
	}

	/**
	 * Requires css files.
	 *
	 * @return array
	 */
	public function get_style_depends() {
		return array(
			'absolute-addons-btn',
			'absp-button',
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
		 * @param Absoluteaddons_Style_Button $this Current instance of WP_Network_Query (passed by reference).
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
			'two'       => __( 'Style Two (Upcoming)', 'absolute-addons' ),
			'three-pro' => __( 'Style Three (Upcoming)', 'absolute-addons' ),
		] );

		$pro_styles = [
			'three-pro',
		];

		$this->add_control(
			'absolute_button',
			array(
				'label'       => __( 'Button Style', 'absolute-addons' ),
				'label_block' => true,
				'type'        => Absp_Control_Styles::TYPE,
				'options'     => $button,
				'disabled'    => [ 'two', 'three-pro' ],
				'default'     => 'one',
			)
		);

		$this->init_pro_alert( $pro_styles );

		$this->end_controls_section();

		$this->button_section();
		// Content Controllers
		$this->render_controller( 'template-button-style' );

		/**
		 * Fires after controllers are registered.
		 *
		 * @param Absoluteaddons_Style_Button $this Current instance of WP_Network_Query (passed by reference).
		 *
		 * @since 1.0.0
		 *
		 */
		do_action_ref_array( $this->get_prefixed_hook( 'controllers/ends' ), [ &$this ] );

	}

	protected function button_section() {
		$this->start_controls_section(
			'absp_button_section',
			[
				'label' => __( 'Button', 'absolute-addons' ),
			]
		);

		$this->add_control(
			'absp_button_type',
			[
				'label'   => __( 'Button Type', 'absolute-addons' ),
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
			'absp_button_outline',
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
			'absp_button_radius',
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
			'absp_button_disabled',
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
			'absp_button_html_tag',
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
			'absp_button_text',
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
			'absp_button_link',
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
					'absp_button_html_tag!' => 'button',
					'absp_button_disabled!' => 'absp-btn-disabled',
				],
			]
		);

		$this->add_control(
			'absp_button_size',
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
			'absp_button_icons_switch',
			[
				'label'     => __( 'Add icon? ', 'absolute-addons' ),
				'type'      => Controls_Manager::SWITCHER,
				'default'   => 'yes',
				'label_on'  => __( 'Yes', 'absolute-addons' ),
				'label_off' => __( 'No', 'absolute-addons' ),
			]
		);

		$this->add_control(
			'absp_button_icons',
			[
				'label'       => __( 'Icon', 'absolute-addons' ),
				'type'        => Controls_Manager::ICONS,
				'default'     => [
					'value' => '',
				],
				'label_block' => true,
				'condition'   => [
					'absp_button_icons_switch' => 'yes',
				],
			]
		);

		$this->add_control(
			'absp_button_icon_align',
			[
				'label'     => __( 'Icon Position', 'absolute-addons' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => [
					'left'  => __( 'Before', 'absolute-addons' ),
					'right' => __( 'After', 'absolute-addons' ),
				],
				'default'   => 'right',
				'condition' => [
					'absp_button_icons_switch' => 'yes',
				],
			]
		);

		$this->add_responsive_control(
			'absp_button_align',
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
					'{{WRAPPER}} .absp-button' => 'text-align: {{VALUE}};',
				],
				'default'   => 'left',
			]
		);

		$this->end_controls_section();
	}

	protected function render() {
		$settings = $this->get_settings_for_display();
		$style    = $settings['absolute_button'];

		?>
		<div class="absp-wrapper absp-widget">
			<div class="absp-button">
				<div class="absp-button-<?php echo esc_attr( $style ); ?>">
					<?php $this->read_more_button( $settings, 'absp-btn' . $settings['absp_button_outline'] . '-black absp-btn' . $settings['absp_button_outline'] . $settings['absp_button_type'] ); ?>
				</div>
			</div>
		</div><!-- end .absp-wrapper -->
		<?php
	}

	protected function read_more_button( $settings, $class_name ) {
		$this->add_render_attribute( [
			'absp_btn_link_option' => [
				'target' => isset( $settings['absp_button_link']['is_external'] ) ? '_blank' : '',
				'rel'    => isset( $settings['absp_button_link']['nofollow'] ) ? 'nofollow' : '',
			],
		] );

		$this->add_render_attribute( 'absp_btn_attr', 'class', 'absp-btn' );
		$this->add_render_attribute( 'absp_btn_attr', 'class', $class_name );
		$this->add_render_attribute( 'absp_btn_attr', 'class', 'absp-btn-' . esc_attr( $settings['absp_button_size'] ) . '' );
		$this->add_render_attribute( 'absp_btn_attr', 'class', 'absp-btn-' . esc_attr( $settings['absp_button_radius'] ) . '' );
		$this->add_render_attribute( 'absp_btn_attr', 'class', esc_attr( $settings['absp_button_disabled'] ) );

		$href = isset( $settings['absp_button_link']['url'] );

		?>
		<div class="absp-button absp-btn-<?php echo esc_attr( $settings['absp_button_align'] ); ?>">
			<?php if ( 'button' === $settings['absp_button_html_tag'] ) { ?>
				<button <?php $this->print_render_attribute_string( 'absp_btn_attr' ); ?> type="button">
					<?php
					if ( 'left' === $settings['absp_button_icon_align'] ) {
						if ( 'yes' === $settings['absp_button_icons_switch'] ) {
							Icons_Manager::render_icon( $settings['absp_button_icons'], [ 'aria-hidden' => 'true' ] );
						}
						echo esc_html( $settings['absp_button_text'] );
					} else {
						echo esc_html( $settings['absp_button_text'] );
						if ( 'yes' === $settings['absp_button_icons_switch'] ) {
							Icons_Manager::render_icon( $settings['absp_button_icons'], [ 'aria-hidden' => 'true' ] );
						}
					}
					?>
				</button>
			<?php } else { ?>
				<a href="<?php echo esc_url( $href ); ?>" <?php $this->print_render_attribute_string( 'absp_btn_attr' ); ?><?php $this->print_render_attribute_string( 'absp_btn_link_option' ); ?> role="button">
					<?php
					if ( 'left' === $settings['absp_button_icon_align'] ) {
						if ( 'yes' === $settings['absp_button_icons_switch'] ) {
							Icons_Manager::render_icon( $settings['absp_button_icons'], [ 'aria-hidden' => 'true' ] );
						}
						echo esc_html( $settings['absp_button_text'] );
					} else {
						echo esc_html( $settings['absp_button_text'] );
						if ( 'yes' === $settings['absp_button_icons_switch'] ) {
							Icons_Manager::render_icon( $settings['absp_button_icons'], [ 'aria-hidden' => 'true' ] );
						}
					}
					?>
				</a>
			<?php } ?>
		</div>
		<?php
	}
}
