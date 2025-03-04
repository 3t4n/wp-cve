<?php
/**
 * Taxonomy List widget class
 *
 * @package Skt_Addons_Elementor
 */

namespace Skt_Addons_Elementor\Elementor\Widget;

use Elementor\Controls_Manager;
use Elementor\Core\Schemes;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Css_Filter;
use Elementor\Group_Control_Typography;
use Elementor\Icons_Manager;
use Elementor\Repeater;
use Elementor\Utils;
use Skt_Addons_Elementor\Elementor\Controls\Select2;

defined( 'ABSPATH' ) || die();

class Taxonomy_List extends Base {

	/**
	 * Get widget title.
	 *
	 * @return string Widget title.
	 * @since 1.0
	 * @access public
	 *
	 */
	public function get_title() {
		return __( 'Taxonomy List', 'skt-addons-elementor' );
	}

	public function get_custom_help_url() {
		return '#';
	}

	/**
	 * Get widget icon.
	 *
	 * @return string Widget icon.
	 * @since 1.0
	 * @access public
	 *
	 */
	public function get_icon() {
		return 'skti skti-clip-board';
	}

	public function get_keywords() {
		return [ 'taxonomies', 'taxonomy', 'taxonomy-list', 'category', 'category-list', 'list' ];
	}

	/**
	 * Get a list of Taxonomy
	 *
	 * @return array
	 */
	public static function get_taxonomies( $taxonomy_type = '' ) {
		$list = [];
		if ( $taxonomy_type ) {
			$tax = skt_addons_elementor_get_taxonomies( [ 'public' => true, "object_type" => [ $taxonomy_type ] ], 'object', true );
			$list[$taxonomy_type] = count( $tax ) !== 0 ? $tax : '';
		} else {
			$list = skt_addons_elementor_get_taxonomies( [ 'public' => true ], 'object', true );
		}
		return $list;
	}

	/**
     * Register widget content controls
     */
	protected function register_content_controls() {
		$this->__list_content_controls();
		$this->__settings_content_controls();
	}

	protected function __list_content_controls() {

		$this->start_controls_section(
			'_section_taxonomy_list',
			[
				'label' => __( 'List', 'skt-addons-elementor' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'taxonomy_type',
			[
				'label' => __( 'Source', 'skt-addons-elementor' ),
				'type' => Controls_Manager::SELECT,
				'options' => $this->get_taxonomies(),
				'default' => key( $this->get_taxonomies() ),
			]
		);

		$repeater = [];

		foreach ( $this->get_taxonomies() as $key => $value ) {

			$repeater[$key] = new Repeater();

			$repeater[$key]->add_control(
				'title',
				[
					'label' => __( 'Custom Title', 'skt-addons-elementor' ),
					'type' => Controls_Manager::TEXT,
					'label_block' => true,
					'placeholder' => __( 'Customize Title', 'skt-addons-elementor' ),
					'dynamic' => [
						'active' => true,
					],
				]
			);

			$repeater[$key]->add_control(
				'individual_icon',
				[
					'label' => __( 'Icon', 'skt-addons-elementor' ),
					'type' => Controls_Manager::CHOOSE,
					'description' => __( 'If you want to use individual icon disable common icon.', 'skt-addons-elementor' ),
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
					'toggle' => true,
					//'default' => 'icon',
				]
			);

			$repeater[$key]->add_control(
				'icon',
				[
					'label' => __( 'Icon', 'skt-addons-elementor' ),
					'show_label' => false,
					'type' => Controls_Manager::ICONS,
					'default' => [
						'value' => 'far fa-check-circle',
						'library' => 'reguler'
					],
					'condition' => [
						'individual_icon' => 'icon',
					]
				]
			);

			$repeater[$key]->add_control(
				'image',
				[
					'label' => __( 'Image', 'skt-addons-elementor' ),
					'show_label' => false,
					'type' => Controls_Manager::MEDIA,
					'default' => [
						'url' => Utils::get_placeholder_image_src(),
					],
					'dynamic' => [
						'active' => true,
					],
					'condition' => [
						'individual_icon' => 'image',
					]
				]
			);

			$repeater[$key]->add_control(
				'tax_id',
				[
					'label' => __( 'Select ', 'skt-addons-elementor' ) . $value,
					'label_block' => true,
					'type' => Select2::TYPE,
					'multiple' => false,
					'placeholder' => 'Search ' . $value,
					'dynamic_params' => [
						'term_taxonomy' => $key,
						'object_type'   => 'term'
					],
				]
			);

			$this->add_control(
				'selected_list_' . $key,
				[
					'label' => '',
					'type' => Controls_Manager::REPEATER,
					'fields' => $repeater[$key]->get_controls(),
					'title_field' => '{{ title }}',
					'condition' => [
						'taxonomy_type' => $key
					],
				]
			);
		}

		$this->end_controls_section();
	}

	protected function __settings_content_controls() {

		$this->start_controls_section(
			'_section_settings',
			[
				'label' => __( 'Settings', 'skt-addons-elementor' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'view',
			[
				'label' => __( 'Layout', 'skt-addons-elementor' ),
				'label_block' => false,
				'type' => Controls_Manager::CHOOSE,
				'default' => 'list',
				'options' => [
					'list' => [
						'title' => __( 'List', 'skt-addons-elementor' ),
						'icon' => 'eicon-editor-list-ul',
					],
					'inline' => [
						'title' => __( 'Inline', 'skt-addons-elementor' ),
						'icon' => 'eicon-ellipsis-h',
					],
				],
				'style_transfer' => true,
			]
		);

		$this->add_control(
			'title_tag',
			[
				'label' => __( 'Title HTML Tag', 'skt-addons-elementor' ),
				'type' => Controls_Manager::SELECT,
				// 'separator' => 'before',
				'options' => [
					'h1' => 'H1',
					'h2' => 'H2',
					'h3' => 'H3',
					'h4' => 'H4',
					'h5' => 'H5',
					'h6' => 'H6',
					'div' => 'div',
					'span' => 'span',
					'p' => 'p',
				],
				'default' => 'h2',
			]
		);

		$this->add_control(
			'common_icon_enable',
			[
				'label' => __( 'Common icon enable?', 'skt-addons-elementor' ),
				'description' => __( 'Common icon will overwrite all individual icon.', 'skt-addons-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default' => 'yes',
			]
		);

		$this->add_control(
			'common_icon',
			[
				'label' => __( 'Common Icon', 'skt-addons-elementor' ),
				'type' => Controls_Manager::CHOOSE,
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
				'condition' => [
					'common_icon_enable' => 'yes',
				],
				'toggle' => false,
				'default' => 'icon',
			]
		);

		$this->add_control(
			'icon',
			[
				'label' => __( 'Icon', 'skt-addons-elementor' ),
				'description' => __( 'Common icon will overwrite individual icon.', 'skt-addons-elementor' ),
				'show_label' => false,
				'type' => Controls_Manager::ICONS,
				'default' => [
					'value' => 'far fa-check-circle',
					'library' => 'reguler'
				],
				'condition' => [
					'common_icon_enable' => 'yes',
					'common_icon' => 'icon',
				]
			]
		);

		$this->add_control(
			'image',
			[
				'label' => __( 'Image', 'skt-addons-elementor' ),
				'description' => __( 'Common icon will overwrite individual icon.', 'skt-addons-elementor' ),
				'show_label' => false,
				'type' => Controls_Manager::MEDIA,
				'default' => [
					'url' => Utils::get_placeholder_image_src(),
				],
				'dynamic' => [
					'active' => true,
				],
				'condition' => [
					'common_icon_enable' => 'yes',
					'common_icon' => 'image',
				]
			]
		);

		$this->add_control(
			'item_align',
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
				],
				'toggle' => true,
				'selectors_dictionary' => [
					'left' => 'justify-content: flex-start',
					'center' => 'justify-content: center',
					'right' => 'justify-content: flex-end',
				],
				'selectors' => [
					'{{WRAPPER}} .skt-taxonomy-list .skt-taxonomy-list-item a' => '{{VALUE}};'
				],
				'condition' => [
					'view' => 'list',
				]
			]
		);

		$this->end_controls_section();
	}

	/**
     * Register widget style controls
     */
	protected function register_style_controls() {
		$this->__list_style_controls();
		$this->__title_style_controls();
		$this->__icon_image_style_controls();
	}

	protected function __list_style_controls() {

		$this->start_controls_section(
			'_section_taxonomy_list_style',
			[
				'label' => __( 'List', 'skt-addons-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'list_item_common',
			[
				'label' => __( 'Common', 'skt-addons-elementor' ),
				'type' => Controls_Manager::HEADING,
			]
		);

		$this->add_responsive_control(
			'list_item_margin',
			[
				'label' => __( 'Margin', 'skt-addons-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .skt-taxonomy-list .skt-taxonomy-list-item' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				]
			]
		);

		$this->add_responsive_control(
			'list_item_padding',
			[
				'label' => __( 'Padding', 'skt-addons-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .skt-taxonomy-list .skt-taxonomy-list-item a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				]
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'list_item_background',
				'label' => __( 'Background', 'skt-addons-elementor' ),
				'types' => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .skt-taxonomy-list .skt-taxonomy-list-item',
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'list_item_box_shadow',
				'label' => __( 'Box Shadow', 'skt-addons-elementor' ),
				'selector' => '{{WRAPPER}} .skt-taxonomy-list .skt-taxonomy-list-item',
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'list_item_border',
				'label' => __( 'Border', 'skt-addons-elementor' ),
				'selector' => '{{WRAPPER}} .skt-taxonomy-list .skt-taxonomy-list-item',
			]
		);

		$this->add_responsive_control(
			'list_item_border_radius',
			[
				'label' => __( 'Border Radius', 'skt-addons-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .skt-taxonomy-list .skt-taxonomy-list-item' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				]
			]
		);

		$this->add_control(
			'advance_style',
			[
				'label' => __( 'Advance Style', 'skt-addons-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'On', 'skt-addons-elementor' ),
				'label_off' => __( 'Off', 'skt-addons-elementor' ),
				'return_value' => 'yes',
				'default' => '',
			]
		);

		$this->add_responsive_control(
			'list_item_first',
			[
				'label' => __( 'First Item', 'skt-addons-elementor' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'advance_style' => 'yes',
				]
			]
		);

		$this->add_responsive_control(
			'list_item_first_child_margin',
			[
				'label' => __( 'Margin', 'skt-addons-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .skt-taxonomy-list .skt-taxonomy-list-item:first-child' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'advance_style' => 'yes',
				]
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'list_item_first_child_border',
				'label' => __( 'Border', 'skt-addons-elementor' ),
				'selector' => '{{WRAPPER}} .skt-taxonomy-list .skt-taxonomy-list-item:first-child',
				'condition' => [
					'advance_style' => 'yes',
				]
			]
		);

		$this->add_responsive_control(
			'list_item_last',
			[
				'label' => __( 'Last Item', 'skt-addons-elementor' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'advance_style' => 'yes',
				]
			]
		);

		$this->add_responsive_control(
			'list_item_last_child_margin',
			[
				'label' => __( 'Margin', 'skt-addons-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .skt-taxonomy-list .skt-taxonomy-list-item:last-child' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'advance_style' => 'yes',
				]
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'list_item_last_child_border',
				'label' => __( 'Border', 'skt-addons-elementor' ),
				'selector' => '{{WRAPPER}} .skt-taxonomy-list .skt-taxonomy-list-item:last-child',
				'condition' => [
					'advance_style' => 'yes',
				]
			]
		);

		$this->end_controls_section();
	}

	protected function __title_style_controls() {

		$this->start_controls_section(
			'_section_taxonomy_list_title_style',
			[
				'label' => __( 'Title', 'skt-addons-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'title_typography',
				'label' => __( 'Typography', 'skt-addons-elementor' ),
				'scheme' => Schemes\Typography::TYPOGRAPHY_2,
				'selector' => '{{WRAPPER}} .skt-taxonomy-list-title',
			]
		);

		$this->start_controls_tabs( 'title_tabs' );
		$this->start_controls_tab(
			'title_normal_tab',
			[
				'label' => __( 'Normal', 'skt-addons-elementor' ),
			]
		);

		$this->add_control(
			'title_color',
			[
				'label' => __( 'Color', 'skt-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .skt-taxonomy-list-title' => 'color: {{VALUE}}',
				],
			]
		);
		$this->end_controls_tab();

		$this->start_controls_tab(
			'title_hover_tab',
			[
				'label' => __( 'Hover', 'skt-addons-elementor' ),
			]
		);

		$this->add_control(
			'title_hvr_color',
			[
				'label' => __( 'Color', 'skt-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .skt-taxonomy-list .skt-taxonomy-list-item a:hover .skt-taxonomy-list-title' => 'color: {{VALUE}}',
				],
			]
		);
		$this->end_controls_tab();
		$this->end_controls_tabs();

		$this->end_controls_section();
	}

	protected function __icon_image_style_controls() {

		$this->start_controls_section(
			'_section_icon_style',
			[
				'label' => __( 'Icon & Image', 'skt-addons-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'icon_color',
			[
				'label' => __( 'Icon Color', 'skt-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} span.skt-taxonomy-list-icon' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'icon_size',
			[
				'label' => __( 'Icon Size', 'skt-addons-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'selectors' => [
					'{{WRAPPER}} span.skt-taxonomy-list-icon' => 'font-size: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'icon_line_height',
			[
				'label' => __( 'Icon Line Height', 'skt-addons-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'selectors' => [
					'{{WRAPPER}} span.skt-taxonomy-list-icon' => 'line-height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'image_width',
			[
				'label' => __( 'Image Width', 'skt-addons-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 1000,
						'step' => 1,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .skt-taxonomy-list-image img' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Css_Filter::get_type(),
			[
				'name' => 'image_css_filter',
				'selector' => '{{WRAPPER}} .skt-taxonomy-list-image img',
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'image_boder',
				'label' => __( 'Border', 'skt-addons-elementor' ),
				'selector' => '{{WRAPPER}} .skt-taxonomy-list-item a img',
			]
		);

		$this->add_responsive_control(
			'image_boder_radius',
			[
				'label' => __( 'Border Radius', 'skt-addons-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .skt-taxonomy-list-image img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'icon_margin_right',
			[
				'label' => __( 'Margin Right', 'skt-addons-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'selectors' => [
					'{{WRAPPER}} span.skt-taxonomy-list-icon' => 'margin-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} span.skt-taxonomy-list-image' => 'margin-right: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();
	}

	protected function render() {

		$settings = $this->get_settings_for_display();
		if ( !$settings['taxonomy_type'] ) return;
		$customize_title = [];
		$ids = [];
		$lists = $settings['selected_list_' . $settings['taxonomy_type']];
		if ( !empty( $lists ) ) {
			foreach ( $lists as $index => $value ) {
				//trim function to remove extra space before taxonomy ID
				if( is_array($value['tax_id']) ){
					$tax_id = ! empty($value['tax_id'][0]) ? trim($value['tax_id'][0]) : '';
				}else{
					$tax_id = ! empty($value['tax_id']) ? trim($value['tax_id']) : '';
				}
				$ids[] = $tax_id;
				if ( $value['title'] ){
					$customize_title[$tax_id] = $value['title'];
				}
			}
		}
		$terms = [];
		if ( count( $ids ) !== 0 ) {
			$args = [
				'taxonomy' => $settings['taxonomy_type'],
				'hide_empty' => true,
				'include' => $ids,
				'orderby' => 'include',
			];
			$terms = get_terms( $args );
		}
		$loop_count = count($terms) - 1;
		$this->add_render_attribute( 'wrapper', 'class', [ 'skt-taxonomy-list-wrapper' ] );
		$this->add_render_attribute( 'wrapper-inner', 'class', [ 'skt-taxonomy-list' ] );
		if ( 'inline' === $settings['view'] ) {
			$this->add_render_attribute( 'wrapper-inner', 'class', [ 'skt-taxonomy-list-inline' ] );
		}
		$this->add_render_attribute( 'item', 'class', [ 'skt-taxonomy-list-item' ] );

		if ( count( $terms ) !== 0 && count( $lists ) !== 0 ) :?>
			<div <?php $this->print_render_attribute_string( 'wrapper' ); ?>>
				<ul <?php $this->print_render_attribute_string( 'wrapper-inner' ); ?> >
				<?php foreach ( $lists as $index => $value ):
					if ( !$value['tax_id'] ) continue; ?>
					<li <?php $this->print_render_attribute_string( 'item' ); ?>>
						<a href="<?php echo esc_url( get_term_link( $terms[$index]->term_id ) ); ?>">
							<?php
							$icon_settings = 'yes' === $settings['common_icon_enable'] && !empty( $settings['common_icon'] ) ? $settings['common_icon'] : $value['individual_icon'];

							$icon = 'yes' === $settings['common_icon_enable'] && !empty( $settings['icon'] ) ? $settings['icon'] : (isset($value['icon'])? $value['icon']: '');

							$image_url = 'yes' === $settings['common_icon_enable'] && !empty( $settings['image']['url'] ) ? $settings['image']['url'] : (isset($value['image']['url'])? $value['image']['url']: '') ;
							?>
							<?php if ( $icon_settings ) :
								echo wp_kses_post('<span class="skt-taxonomy-list-' . esc_attr( $icon_settings ) . '">');
								if ( 'icon' === $icon_settings && !empty( $icon ) ) :
									Icons_Manager::render_icon( $icon, [ 'aria-hidden' => 'true' ] );
											elseif ( 'image' === $icon_settings && !empty( $image_url ) ) :
									echo wp_kses_post('<img src="' . esc_url( $image_url ) . '">');
								endif;
								echo wp_kses_post('</span>');
							endif; ?>
							<?php
							//Term Title
							$title = $terms[$index]->name;
							if ( array_key_exists( $terms[$index]->term_id, $customize_title ) ) {
								$title = $customize_title[$terms[$index]->term_id];
							}
							if ( $title ) {
								printf( '<%1$s %2$s>%3$s</%1$s>',
									skt_addons_elementor_escape_tags( $settings['title_tag'], 'h2' ),
									'class="skt-taxonomy-list-title"',
									esc_html( $title )
								);
							}
							?>
						</a>
					</li>
					<?php if ( $loop_count === $index ) break; ?>
				<?php endforeach; ?>
				</ul>
			</div>
		<?php
		else:
			printf( '%1$s %2$s %3$s',
				__( 'No ', 'skt-addons-elementor' ),
				esc_html( str_replace( '_', ' ', $settings['taxonomy_type'] ) ),
				__( 'found', 'skt-addons-elementor' )
			);
		endif;
	}
}