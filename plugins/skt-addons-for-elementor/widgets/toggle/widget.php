<?php
/**
 * Toggle
 *
 * @package Skt_Addons_Elementor
 */
namespace Skt_Addons_Elementor\Elementor\Widget;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Repeater;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Skt_Addons_Elementor\Elementor\Controls\Group_Control_Foreground;

defined( 'ABSPATH' ) || die();

class Toggle extends Base {

	/**
	 * Get widget title.
	 *
	 * @since 1.0
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( 'Advanced Toggle', 'skt-addons-elementor' );
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
		return 'skti skti-drawer';
	}

	public function get_keywords() {
		return [ 'accordion', 'toggle', 'collapsible', 'tabs', 'switch' ];
	}

	/**
	 * Register widget content controls
	 */
	protected function register_content_controls() {
		$this->__toggle_content_controls();
		$this->__options_content_controls();
	}

	protected function __toggle_content_controls() {

		$this->start_controls_section(
			'_section_toggle',
			[
				'label' => __( 'Toggle', 'skt-addons-elementor' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);

		$repeater = new Repeater();

		$repeater->add_control(
			'title',
			[
				'type'        => Controls_Manager::TEXT,
				'label'       => __( 'Title', 'skt-addons-elementor' ),
				'default'     => __( 'Toggle Title', 'skt-addons-elementor' ),
				'placeholder' => __( 'Type Toggle Title', 'skt-addons-elementor' ),
				'dynamic'     => [
					'active' => true,
				],
			]
		);

		$repeater->add_control(
			'icon',
			[
				'type'       => Controls_Manager::ICONS,
				'label'      => __( 'Icon', 'skt-addons-elementor' ),
				'show_label' => false,
			]
		);

		$repeater->add_control(
			'source',
			[
				'type'      => Controls_Manager::SELECT,
				'label'     => __( 'Content Source', 'skt-addons-elementor' ),
				'default'   => 'editor',
				'separator' => 'before',
				'options'   => [
					'editor'   => __( 'Editor', 'skt-addons-elementor' ),
					'template' => __( 'Template', 'skt-addons-elementor' ),
				],
			]
		);

		$repeater->add_control(
			'editor',
			[
				'label'      => __( 'Content Editor', 'skt-addons-elementor' ),
				'show_label' => false,
				'type'       => Controls_Manager::WYSIWYG,
				'condition'  => [
					'source' => 'editor',
				],
				'dynamic'    => [
					'active' => true,
				],
			]
		);

		$repeater->add_control(
			'template',
			[
				'label'       => __( 'Section Template', 'skt-addons-elementor' ),
				'placeholder' => __( 'Select a section template for as tab content', 'skt-addons-elementor' ),
				'description' => sprintf(
					__( 'Wondering what is section template or need to create one? Please click %1$shere%2$s ', 'skt-addons-elementor' ),
					'<a target="_blank" href="' . esc_url( admin_url( '/edit.php?post_type=elementor_library&tabs_group=library&elementor_library_type=section' ) ) . '">',
					'</a>'
				),
				'type'        => Controls_Manager::SELECT2,
				'label_block' => true,
				'options'     => sktaddonselementorextra_get_section_templates(),
				'condition'   => [
					'source' => 'template',
				],
			]
		);

		$this->add_control(
			'tabs',
			[
				'show_label'  => false,
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $repeater->get_controls(),
				'title_field' => '{{title}}',
				'default'     => [
					[
						'title'  => 'Toggle Item 1',
						'source' => 'editor',
						'editor' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore <br><br>et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.',
					],
					[
						'title'  => 'Toggle Item 2',
						'source' => 'editor',
						'editor' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore <br><br>et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.',
					],
				],
			]
		);

		$this->end_controls_section();
	}

	protected function __options_content_controls() {

		$this->start_controls_section(
			'_section_options',
			[
				'label' => __( 'Options', 'skt-addons-elementor' ),
			]
		);

		$this->add_control(
			'closed_icon',
			[
				'type'    => Controls_Manager::ICONS,
				'label'   => __( 'Closed Icon', 'skt-addons-elementor' ),
				'default' => [
					'library' => 'solid',
					'value'   => 'fas fa-plus',
				],
			]
		);

		$this->add_control(
			'opened_icon',
			[
				'type'    => Controls_Manager::ICONS,
				'label'   => __( 'Opened Icon', 'skt-addons-elementor' ),
				'default' => [
					'library' => 'solid',
					'value'   => 'fas fa-minus',
				],
			]
		);

		$this->add_control(
			'icon_position',
			[
				'type'           => Controls_Manager::CHOOSE,
				'label'          => __( 'Position', 'skt-addons-elementor' ),
				'default'        => 'left',
				'toggle'         => false,
				'options'        => [
					'left'  => [
						'title' => __( 'Left', 'skt-addons-elementor' ),
						'icon'  => 'eicon-h-align-left',
					],
					'right' => [
						'title' => __( 'Right', 'skt-addons-elementor' ),
						'icon'  => 'eicon-h-align-right',
					],
				],
				'prefix_class'   => 'skt-toggle--icon-',
				'style_transfer' => true,
			]
		);

		$this->end_controls_section();
	}

	/**
	 * Register widget style controls
	 */
	protected function register_style_controls() {
		$this->__item_style_controls();
		$this->__title_style_controls();
		$this->__title_icon_style_controls();
		$this->__content_style_controls();
		$this->__open_close_style_controls();
	}

	protected function __item_style_controls() {

		$this->start_controls_section(
			'_section_item',
			[
				'label' => __( 'Item', 'skt-addons-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'item_spacing',
			[
				'label'     => __( 'Vertical Spacing (px)', 'skt-addons-elementor' ),
				'type'      => Controls_Manager::NUMBER,
				'step'      => 'any',
				'default'   => -1,
				'selectors' => [
					'{{WRAPPER}} .skt-toggle__item:not(:first-child)' => 'margin-top: {{VALUE}}px;',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'     => 'item_border',
				'label'    => __( 'Box Border', 'skt-addons-elementor' ),
				'selector' => '{{WRAPPER}} .skt-toggle__item',
			]
		);

		$this->add_control(
			'item_border_radius',
			[
				'label'      => __( 'Border Radius', 'skt-addons-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .skt-toggle__item' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'item_box_shadow',
				'label'    => __( 'Box Shadow', 'skt-addons-elementor' ),
				'selector' => '{{WRAPPER}} .skt-toggle__item',
			]
		);

		$this->end_controls_section();
	}

	protected function __title_style_controls() {

		$this->start_controls_section(
			'_section_title',
			[
				'label' => __( 'Title', 'skt-addons-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'title_padding',
			[
				'label'      => __( 'Padding', 'skt-addons-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .skt-toggle__item-title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'title_typography',
				'selector' => '{{WRAPPER}} .skt-toggle__item-title',
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_SECONDARY,
				],
			]
		);

		$this->add_group_control(
			Group_Control_Text_Shadow::get_type(),
			[
				'name'     => 'title_text_shadow',
				'label'    => __( 'Text Shadow', 'skt-addons-elementor' ),
				'selector' => '{{WRAPPER}} .skt-toggle__item-title',
			]
		);

		$this->start_controls_tabs( '_tab_tab_status' );
		$this->start_controls_tab(
			'_tab_tab_normal',
			[
				'label' => __( 'Normal', 'skt-addons-elementor' ),
			]
		);

		$this->add_control(
			'title_border_radius',
			[
				'label'      => __( 'Border Radius', 'skt-addons-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .skt-toggle__item-title' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Foreground::get_type(),
			[
				'name'     => 'title_text_gradient',
				'selector' => '{{WRAPPER}} .skt-toggle__item-title-text, {{WRAPPER}} .skt-toggle__item-title-icon i:before, {{WRAPPER}} .skt-toggle__item-title-icon svg, {{WRAPPER}} .skt-toggle__icon i:before, {{WRAPPER}} .skt-toggle__icon svg',
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'     => 'title_bg',
				'types'    => [ 'classic', 'gradient' ],
				'exclude'  => [ 'image' ],
				'selector' => '{{WRAPPER}} .skt-toggle__item-title',
			]
		);

		$this->end_controls_tab();
		$this->start_controls_tab(
			'_tab_tab_active',
			[
				'label' => __( 'Active', 'skt-addons-elementor' ),
			]
		);

		$this->add_control(
			'title_active_border_radius',
			[
				'label'      => __( 'Border Radius', 'skt-addons-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .skt-toggle__item-title.skt-toggle__item--active' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Foreground::get_type(),
			[
				'name'     => 'title_active_text_gradient',
				'selector' => '{{WRAPPER}} .skt-toggle__item-title.skt-toggle__item--active .skt-toggle__item-title-text, {{WRAPPER}} .skt-toggle__item-title.skt-toggle__item--active .skt-toggle__item-title-icon i:before, {{WRAPPER}} .skt-toggle__item-title.skt-toggle__item--active .skt-toggle__icon i:before',
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'     => 'title_active_bg',
				'types'    => [ 'classic', 'gradient' ],
				'exclude'  => [ 'image' ],
				'selector' => '{{WRAPPER}} .skt-toggle__item-title.skt-toggle__item--active',
			]
		);

		$this->end_controls_tab();
		$this->end_controls_tabs();

		$this->end_controls_section();
	}

	protected function __title_icon_style_controls() {

		$this->start_controls_section(
			'_section_title_icon',
			[
				'label' => __( 'Title Icon', 'skt-addons-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'title_icon_spacing',
			[
				'label'      => __( 'Spacing', 'skt-addons-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'selectors'  => [
					'{{WRAPPER}} .skt-toggle__item-title-icon' => 'margin-right: {{SIZE}}px;',
				],
			]
		);

		$this->end_controls_section();
	}

	protected function __content_style_controls() {

		$this->start_controls_section(
			'_section_content',
			[
				'label' => __( 'Content', 'skt-addons-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'content_padding',
			[
				'label'      => __( 'Padding', 'skt-addons-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .skt-toggle__item-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'     => 'content_border',
				'label'    => __( 'Border', 'skt-addons-elementor' ),
				'selector' => '{{WRAPPER}} .skt-toggle__item-content',
			]
		);

		$this->add_control(
			'content_border_radius',
			[
				'label'      => __( 'Border Radius', 'skt-addons-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .skt-toggle__item-content' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'content_typography',
				'selector' => '{{WRAPPER}} .skt-toggle__item-content',
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_TEXT,
				],
			]
		);

		$this->add_control(
			'content_color',
			[
				'label'     => __( 'Color', 'skt-addons-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .skt-toggle__item-content' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'     => 'content_bg',
				'types'    => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .skt-toggle__item-content',
			]
		);

		$this->end_controls_section();
	}

	protected function __open_close_style_controls() {

		$this->start_controls_section(
			'_section_icon',
			[
				'label' => __( 'Open / Close Icon', 'skt-addons-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'nav_icon_spacing',
			[
				'label'      => __( 'Spacing', 'skt-addons-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'selectors'  => [
					'{{WRAPPER}}.skt-toggle--icon-left .skt-toggle__icon > span' => 'margin-right: {{SIZE}}px;',
					'{{WRAPPER}}.skt-toggle--icon-right .skt-toggle__icon > span' => 'margin-left: {{SIZE}}px;',
				],
			]
		);

		$this->end_controls_section();
	}

	protected function render() {
		$settings = $this->get_settings_for_display();

		if ( ! is_array( $settings['tabs'] ) || empty( $settings['tabs'] ) ) {
			return;
		}

		$has_closed_icon = ( ! empty( $settings['closed_icon'] ) && ! empty( $settings['closed_icon']['value'] ) );
		$has_opened_icon = ( ! empty( $settings['opened_icon'] ) && ! empty( $settings['opened_icon']['value'] ) );

		$id_int = substr( $this->get_id_int(), 0, 3 );
		?>
		<div class="skt-toggle__wrapper" role="tablist">
			<?php
			foreach ( $settings['tabs'] as $index => $item ) :
				$count = $index + 1;

				$title_setting_key = $this->get_repeater_setting_key( 'title', 'tabs', $index );
				$has_title_icon    = ( ! empty( $item['icon'] ) && ! empty( $item['icon']['value'] ) );

				if ( $item['source'] === 'editor' ) {
					$content_setting_key = $this->get_repeater_setting_key( 'editor', 'tabs', $index );
					$this->add_inline_editing_attributes( $content_setting_key, 'advanced' );
				} else {
					$content_setting_key = $this->get_repeater_setting_key( 'section', 'tabs', $index );
				}

				$this->add_render_attribute(
					$title_setting_key,
					[
						'id'            => 'skt-toggle__item-title-' . $id_int . $count,
						'class'         => [ 'skt-toggle__item-title' ],
						'data-tab'      => $count,
						'role'          => 'tab',
						'aria-controls' => 'skt-toggle__item-content-' . $id_int . $count,
					]
				);

				$this->add_render_attribute(
					$content_setting_key,
					[
						'id'              => 'skt-toggle__item-content-' . $id_int . $count,
						'class'           => [ 'skt-toggle__item-content' ],
						'data-tab'        => $count,
						'role'            => 'tabpanel',
						'aria-labelledby' => 'skt-toggle__item-title-' . $id_int . $count,
					]
				);

				?>
				<div class="skt-toggle__item">
					<div <?php echo wp_kses_post($this->get_render_attribute_string( $title_setting_key )); ?>>
						<?php if ( $has_opened_icon || $has_closed_icon ) : ?>
							<span class="skt-toggle__item-icon skt-toggle__icon" aria-hidden="true">
								<?php if ( $has_opened_icon ) : ?>
									<span class="skt-toggle__icon--closed"><?php skt_addons_elementor_render_icon( $settings, false, 'closed_icon' ); ?></span>
								<?php endif; ?>
								<?php if ( $has_opened_icon ) : ?>
									<span class="skt-toggle__icon--opened"><?php skt_addons_elementor_render_icon( $settings, false, 'opened_icon' ); ?></span>
								<?php endif; ?>
							</span>
						<?php endif; ?>
						<div class="skt-toggle__item-title-inner">
							<?php if ( $has_title_icon ) : ?>
								<span class="skt-toggle__item-title-icon"><?php skt_addons_elementor_render_icon( $item, false, 'icon' ); ?></span>
							<?php endif; ?>
							<span class="skt-toggle__item-title-text"><?php echo wp_kses_post(skt_addons_elementor_kses_basic( $item['title'] )); ?></span>
						</div>
					</div>
					<div <?php echo wp_kses_post($this->get_render_attribute_string( $content_setting_key )); ?>>
						<?php
						if ( $item['source'] === 'editor' ) :
							echo wp_kses_post($this->parse_text_editor( $item['editor'] ));
						elseif ( $item['source'] === 'template' && $item['template'] ) :
							echo skt_addons_elementor()->frontend->get_builder_content_for_display( $item['template'] );
						endif;
						?>
					</div>
				</div>
			<?php endforeach; ?>
		</div>
		<?php
	}
}