<?php
/**
 * News Ticker widget class
 *
 * @package Skt_Addons_Elementor
 */

namespace Skt_Addons_Elementor\Elementor\Widget;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Group_Control_Background;
use Skt_Addons_Elementor\Elementor\Controls\Select2;

defined( 'ABSPATH' ) || die();

class News_Ticker extends Base {

	/**
	 * Get widget title.
	 *
	 * @return string Widget title.
	 * @since 1.0
	 * @access public
	 *
	 */
	public function get_title () {
		return __( 'News Ticker', 'skt-addons-elementor' );
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
	public function get_icon () {
		return 'skti skti-slider';
	}

	public function get_keywords () {
		return [ 'news', 'news-ticker', 'ticker', 'text-slider', 'slider' ];
	}

	/**
     * Register widget content controls
     */
	protected function register_content_controls () {

		$this->start_controls_section(
			'_section_news_ticker',
			[
				'label' => __( 'News Ticker', 'skt-addons-elementor' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'sticky_title',
			[
				'label' => __( 'Sticky Title', 'skt-addons-elementor' ),
				'type' => Controls_Manager::TEXT,
				'default' => __( 'Breaking News', 'skt-addons-elementor' ),
                'dynamic' => [
                    'active' => true,
                ]
			]
		);

		$this->add_control(
			'sticky_title_position',
			[
				'label' => __( 'Sticky Title Position', 'skt-addons-elementor' ),
				'type' => Controls_Manager::CHOOSE,
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
				'default' => 'left',
				'toggle' => false,
				'style_transfer' => true,
				'selectors' => [
					'{{WRAPPER}}  .skt-news-ticker-wrapper  span.skt-news-ticker-sticky-title' => '{{VALUE}};'
				],
				'selectors_dictionary' => [
					'left' => 'left: 0',
					'right' => 'right: 0'
				],
				'condition' => [
					'sticky_title!' => '',
				]
			]
		);

		$this->add_control(
			'selected_posts',
			[
				'label' => __( 'Select Posts', 'skt-addons-elementor' ),
				'label_block' => true,
				'type' => Select2::TYPE,
				'multiple' => true,
				'placeholder' => 'Search Post',
				'dynamic_params' => [
					'object_type' => 'post',
					'post_type'   => 'post',
				],
				'select2options' => [
					'minimumInputLength' => 0,
				],
			]
		);

		$this->add_control(
			'title_tag',
			[
				'label' => __( 'Post Title Tag', 'skt-addons-elementor' ),
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
			'slide_direction',
			[
				'label' => __( 'Slide direction', 'skt-addons-elementor' ),
				'type' => Controls_Manager::CHOOSE,
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
				'default' => 'left',
				'toggle' => false,
				'style_transfer' => true,
			]
		);

		$this->add_control(
			'item_space',
			[
				'label' => __( 'Space between items', 'skt-addons-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => -100,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .skt-news-ticker-wrapper .skt-news-ticker-item' => 'margin-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .skt-news-ticker-wrapper .skt-news-ticker-item:last-child' => 'margin-right: 0;',
				],
				'style_transfer' => true,
			]
		);

		$this->add_control(
			'speed',
			[
				'label' => __( 'Slide Speed', 'skt-addons-elementor' ),
				'description' => __( 'Autoplay speed in seconds. Default 30', 'skt-addons-elementor' ),
				'type' => Controls_Manager::NUMBER,
				'min' => 1,
				'max' => 10000,
				'default' => 30,
				'frontend_available' => true,
			]
		);

		$this->end_controls_section();
	}

	/**
     * Register widget style controls
     */
	protected function register_style_controls () {
		$this->wrapper_style_controls();
		$this->sticky_title_style_controls();
		$this->title_style_controls();
	}

	protected function wrapper_style_controls () {

		$this->start_controls_section(
			'_style_news_ticker_wrapper',
			[
				'label' => __( 'Wrapper', 'skt-addons-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'wrapper_background',
				'label' => __( 'Background', 'skt-addons-elementor' ),
				'types' => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .skt-news-ticker-wrapper',
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'wrapper_border',
				'label' => __( 'Border', 'skt-addons-elementor' ),
				'selector' => '{{WRAPPER}} .skt-news-ticker-wrapper',
			]
		);

		$this->add_control(
			'wrapper_border_radius',
			[
				'label' => __( 'Border Radius', 'skt-addons-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'selectors' => [
					'{{WRAPPER}} .skt-news-ticker-wrapper' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'wrapper_box_shadow',
				'label' => __( 'Box Shadow', 'skt-addons-elementor' ),
				'selector' => '{{WRAPPER}} .skt-news-ticker-wrapper',
			]
		);

		$this->add_responsive_control(
			'wrapper_padding',
			[
				'label' => __( 'Padding', 'skt-addons-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .skt-news-ticker-wrapper' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'sticky_title_position_left',
			[
				'label' => __( 'Sticky Title Position Left', 'skt-addons-elementor' ),
				'type' => Controls_Manager::HIDDEN,
				'default' => 'left',
				'selectors' => [
					'(desktop){{WRAPPER}}  .skt-news-ticker-wrapper  span.skt-news-ticker-sticky-title' => 'left: {{wrapper_padding.LEFT || 0}}{{wrapper_padding.UNIT}}; right:auto;',
					'(tablet){{WRAPPER}}  .skt-news-ticker-wrapper  span.skt-news-ticker-sticky-title' => 'left: {{wrapper_padding_tablet.LEFT}}{{wrapper_padding_tablet.UNIT}}; right:auto;',
					'(mobile){{WRAPPER}}  .skt-news-ticker-wrapper  span.skt-news-ticker-sticky-title' => 'left: {{wrapper_padding_mobile.LEFT}}{{wrapper_padding_mobile.UNIT}}; right:auto;',
				],
				'condition' => [
					'sticky_title!' => '',
					'sticky_title_position' => 'left',
				]
			]
		);

		$this->add_control(
			'sticky_title_position_right',
			[
				'label' => __( 'Sticky Title Position Right', 'skt-addons-elementor' ),
				'type' => Controls_Manager::HIDDEN,
				'default' => 'right',
				'selectors' => [
					'(desktop){{WRAPPER}}  .skt-news-ticker-wrapper  span.skt-news-ticker-sticky-title' => 'right: {{wrapper_padding.RIGHT || 0}}{{wrapper_padding.UNIT}}; left:auto;',
					'(tablet){{WRAPPER}}  .skt-news-ticker-wrapper  span.skt-news-ticker-sticky-title' => 'right: {{wrapper_padding_tablet.RIGHT}}{{wrapper_padding_tablet.UNIT}}; left:auto;',
					'(mobile){{WRAPPER}}  .skt-news-ticker-wrapper  span.skt-news-ticker-sticky-title' => 'right: {{wrapper_padding_mobile.RIGHT}}{{wrapper_padding_mobile.UNIT}}; left:auto;',
				],
				'condition' => [
					'sticky_title!' => '',
					'sticky_title_position' => 'right',
				]
			]
		);

		$this->end_controls_section();
	}

	protected function sticky_title_style_controls () {

		$this->start_controls_section(
			'_style_news_ticker_sticky_title',
			[
				'label' => __( 'Sticky Title', 'skt-addons-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'sticky_title_color',
			[
				'label' => __( 'Title Color', 'skt-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .skt-news-ticker-wrapper  span.skt-news-ticker-sticky-title' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'sticky_title_typography',
				'label' => __( 'Typography', 'skt-addons-elementor' ),
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_TEXT,
			],
				'selector' => '{{WRAPPER}} .skt-news-ticker-wrapper  span.skt-news-ticker-sticky-title',
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'sticky_title_background',
				'label' => __( 'Background', 'skt-addons-elementor' ),
				'types' => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .skt-news-ticker-wrapper span.skt-news-ticker-sticky-title',
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'sticky_title_border',
				'label' => __( 'Border', 'skt-addons-elementor' ),
				'selector' => '{{WRAPPER}} .skt-news-ticker-wrapper span.skt-news-ticker-sticky-title',
			]
		);

		$this->add_control(
			'sticky_title_border_radius',
			[
				'label' => __( 'Border Radius', 'skt-addons-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'selectors' => [
					'{{WRAPPER}} .skt-news-ticker-wrapper span.skt-news-ticker-sticky-title' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'sticky_title_padding',
			[
				'label' => __( 'Padding', 'skt-addons-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .skt-news-ticker-wrapper span.skt-news-ticker-sticky-title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

	}

	protected function title_style_controls () {

		$this->start_controls_section(
			'_style_news_ticker_title',
			[
				'label' => __( 'Title', 'skt-addons-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->start_controls_tabs( '_tabs_title' );

		$this->start_controls_tab(
			'_tab_title_normal',
			[
				'label' => __( 'Normal', 'skt-addons-elementor' ),
			]
		);

		$this->add_control(
			'title_color',
			[
				'label' => __( 'Title Color', 'skt-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .skt-news-ticker-wrapper  li.skt-news-ticker-item a' => 'color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'_tab_title_hover',
			[
				'label' => __( 'Hover', 'skt-addons-elementor' ),
			]
		);

		$this->add_control(
			'title_hover_color',
			[
				'label' => __( 'Title Color', 'skt-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .skt-news-ticker-wrapper  li.skt-news-ticker-item a:hover, {{WRAPPER}} .skt-news-ticker-wrapper  li.skt-news-ticker-item a:focus' => 'color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();
		$this->end_controls_tabs();

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'title_typography',
				'label' => __( 'Typography', 'skt-addons-elementor' ),
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_TEXT,
			],
				'selector' => '{{WRAPPER}} .skt-news-ticker-wrapper  li.skt-news-ticker-item .skt-news-ticker-title',
			]
		);

		$this->add_group_control(
			Group_Control_Text_Shadow::get_type(),
			[
				'name' => 'title_shadow',
				'label' => __( 'Title Shadow', 'skt-addons-elementor' ),
				'selector' => '{{WRAPPER}} .skt-news-ticker-wrapper  li.skt-news-ticker-item a',
				'style_transfer' => true,
			]
		);

		$this->end_controls_section();
	}

	protected function render () {

		$settings = $this->get_settings_for_display();
		if ( empty( $settings['selected_posts'] ) ) {
			return;
		}

		$query_args = [
			'post_type'           => 'post',
			'post_status'         => 'publish',
			'ignore_sticky_posts' => 1,
			'post__in'            => (array) $settings['selected_posts'],
			'suppress_filters'    => false,
		];

		$news_posts = [];
		$the_query = get_posts( $query_args );

		if ( ! empty( $the_query ) ) {
			$news_posts = wp_list_pluck( $the_query, 'post_title', 'ID' );
		}

		$this->add_render_attribute( 'wrapper', 'class', [ 'skt-news-ticker-wrapper' ] );
		$this->add_render_attribute( 'wrapper', 'data-duration', $settings['speed'] ? ( $settings['speed'] * '1000' ) : '30000' );
		$this->add_render_attribute( 'wrapper', 'data-scroll-direction', $settings['slide_direction'] );
		$this->add_render_attribute( 'container', 'class', [ 'skt-news-ticker-container' ] );
		$this->add_render_attribute( 'item', 'class', [ 'skt-news-ticker-item' ] );

		if ( count( $news_posts ) !== 0 ) :?>
			<div <?php $this->print_render_attribute_string( 'wrapper' ); ?>>
				<?php if ( $settings['sticky_title'] ): ?>
					<span class="skt-news-ticker-sticky-title">
						<?php echo esc_html( $settings['sticky_title'] ); ?>
					</span>
				<?php endif; ?>
				<ul <?php $this->print_render_attribute_string( 'container' ); ?>>
					<?php foreach ( $news_posts as $key => $value ): ?>
						<li <?php $this->print_render_attribute_string( 'item' ); ?>>
							<?php
								printf( '<%1$s class="skt-news-ticker-title"><a href="%2$s">%3$s</a></%1$s>',
									skt_addons_elementor_escape_tags( $settings['title_tag'], 'h2' ),
									esc_url( get_the_permalink($key) ),
									esc_html( $value )
								);
							?>
						</li>
					<?php endforeach; ?>
				</ul>
			</div>
		<?php
		endif;
	}
}