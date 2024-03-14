<?php

namespace LaStudioKitExtensions\Albums\Widgets;

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

use Elementor\Controls_Manager;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\LaStudioKit_Posts;
use Elementor\Repeater;
use LaStudioKitExtensions\Elementor\Controls\Group_Control_Related as Group_Control_Related;

class Album_Lists extends LaStudioKit_Posts{

	private $_query = null;

	public $item_counter = 0;

	public $cflag = false;

	public $css_file_name = 'album-lists.min.css';

	public $popup_id = 0;

	public static $popup_instance = [];

	protected function enqueue_addon_resources(){
		$this->add_script_depends( 'jquery-isotope' );
		if(!lastudio_kit_settings()->is_combine_js_css()) {
			$this->add_script_depends( 'lastudio-kit-base' );
			if(!lastudio_kit()->is_optimized_css_mode()) {
				wp_register_style( 'lakit-posts', lastudio_kit()->plugin_url( 'assets/css/addons/posts.min.css' ), [ 'lastudio-kit-base' ], lastudio_kit()->get_version() );
				wp_register_style( $this->get_name(), lastudio_kit()->plugin_url( 'assets/css/addons/' . $this->css_file_name ), [ 'lakit-posts' ], lastudio_kit()->get_version() );
				$this->add_style_depends( $this->get_name() );
			}
		}
		$this->add_script_depends('lastudio-kit-player');
		$this->add_style_depends('lastudio-kit-player');
	}

	public function get_inline_css_depends() {
		return [
			[
				'name' => 'lakit-posts'
			]
		];
	}

	public function get_widget_css_config($widget_name){

        $css_file_name = $widget_name === 'lakit-posts' ? 'posts.min.css' : 'album-lists.min.css';

        $file_url = lastudio_kit()->plugin_url(  'assets/css/addons/' . $css_file_name );
        $file_path = lastudio_kit()->plugin_path( 'assets/css/addons/' . $css_file_name );

		return [
			'key' => $widget_name,
			'version' => lastudio_kit()->get_version(true),
			'file_path' => $file_path,
			'data' => [
				'file_url' => $file_url
			]
		];
	}

	public function get_name() {
		return 'lakit-album-lists';
	}

	public function get_widget_title() {
		return __('Album Lists', 'lastudio-kit');
	}

	public function get_keywords() {
		return [ 'album', 'player' ];
	}

	protected function set_template_output(){
		return lastudio_kit()->plugin_path('includes/extensions/albums/widget-templates');
	}

	protected function preset_list(){
		$preset_type = apply_filters(
			'lastudio-kit/'.$this->get_lakit_name().'/control/preset',
			array(
				'ab-1' => esc_html__( 'Type 1', 'lastudio-kit' ),
				'ab-2' => esc_html__( 'Type 2', 'lastudio-kit' ),
				'ab-3' => esc_html__( 'Type 3', 'lastudio-kit' ),
				'ab-4' => esc_html__( 'Type 4', 'lastudio-kit' ),
				'ab-5' => esc_html__( 'Type 5', 'lastudio-kit' ),
			)
		);
		return $preset_type;
	}

	protected function _register_section_meta( $css_schema ){
		$this->_start_controls_section(
			'section_meta',
			[
				'label' => __( 'Meta Data', 'lastudio-kit' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->_add_control(
			'show_play_album',
			array(
				'type'         => Controls_Manager::SWITCHER,
				'label'        => esc_html__( 'Show Play Album', 'lastudio-kit' ),
				'label_on'     => esc_html__( 'Yes', 'lastudio-kit' ),
				'label_off'    => esc_html__( 'No', 'lastudio-kit' ),
				'return_value' => 'yes',
				'default'      => '',
			)
		);

		$this->add_control(
			'album_template_id',
			[
				'label'        => esc_html__( 'Album Popup Template', 'lastudio-kit' ),
				'type'         => 'lastudiokit-query',
				'options'      => [],
				'label_block'  => true,
				'multiple'      => false,
				'autocomplete' => [
					'object' => 'library_template',
					'query' => [
						'posts_per_page' => 20,
						'post_status' => [ 'publish', 'private' ],
						'meta_query' => [
							[
								'key' => '_elementor_template_type',
								'value' => 'popup',
							],
						],
					],
				],
			]
		);

		$this->_add_control(
			'play_album_text',
			array(
				'type'         => Controls_Manager::TEXT,
				'label'        => esc_html__( 'Play Album Text', 'lastudio-kit' ),
				'condition'   => array(
					'show_play_album' => 'yes',
				),
			)
		);

		$this->_add_icon_control(
			'play_album_icon',
			[
				'label'       => __( 'Play Album Icon', 'lastudio-kit' ),
				'type'        => Controls_Manager::ICON,
				'file'        => '',
				'skin'        => 'inline',
				'label_block' => false,
				'condition'   => array(
					'show_play_album' => 'yes',
				),
			]
		);

		$this->_add_control(
			'show_meta',
			array(
				'type'         => 'switcher',
				'label'        => esc_html__( 'Show Meta Data', 'lastudio-kit' ),
				'label_on'     => esc_html__( 'Yes', 'lastudio-kit' ),
				'label_off'    => esc_html__( 'No', 'lastudio-kit' ),
				'return_value' => 'yes',
				'default'      => '',
			)
		);

		$repeater = new Repeater();

		$repeater->add_control(
			'item_label',
			array(
				'label' => esc_html__( 'Label', 'lastudio-kit' ),
				'type'  => Controls_Manager::TEXT,
			)
		);
		$repeater->add_control(
			'item_icon',
			[
				'label'            => __( 'Icon', 'lastudio-kit' ),
				'type'             => Controls_Manager::ICONS,
				'fa4compatibility' => 'icon',
				'skin'             => 'inline',
				'label_block'      => false,
			]
		);

		$repeater->add_control(
			'item_type',
			[
				'label'   => esc_html__( 'Type', 'lastudio-kit' ),
				'type'    => Controls_Manager::SELECT2,
				'options' => apply_filters( 'lastudio-kit/'.$this->get_lakit_name().'/metadata', [
					'genres'            => esc_html__( 'Genres', 'lastudio-kit' ),
					'artists'           => esc_html__( 'Artists', 'lastudio-kit' ),
					'labels'            => esc_html__( 'Labels', 'lastudio-kit' ),
					'release_date'      => esc_html__( 'Release Date', 'lastudio-kit' ),
					'people'            => esc_html__( 'People', 'lastudio-kit' ),
				] )
			]
		);

		$this->_add_control(
			'metadata1',
			array(
				'label'         => esc_html__( 'MetaData 1', 'lastudio-kit' ),
				'type'          => Controls_Manager::REPEATER,
				'fields'        => $repeater->get_controls(),
				'title_field'   => '{{{ item_label }}}',
				'prevent_empty' => false,
				'condition'     => array(
					'show_meta' => 'yes'
				)
			)
		);

		$this->_add_control(
			'meta_position1',
			[
				'label'     => esc_html__( 'MetaData 1 Position', 'lastudio-kit' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => [
					'before_title'  => esc_html__( 'Before Title', 'lastudio-kit' ),
					'after_title'   => esc_html__( 'After Title', 'lastudio-kit' ),
					'after_content' => esc_html__( 'After Content', 'lastudio-kit' ),
				],
				'default'   => 'before_title',
				'condition' => [
					'show_meta' => 'yes',
				]
			]
		);

		$this->_add_control(
			'metadata2',
			array(
				'label'         => esc_html__( 'MetaData 2', 'lastudio-kit' ),
				'type'          => Controls_Manager::REPEATER,
				'fields'        => $repeater->get_controls(),
				'title_field'   => '{{{ item_label }}}',
				'prevent_empty' => false,
				'condition'     => array(
					'show_meta' => 'yes'
				)
			)
		);
		$this->_add_control(
			'meta_position2',
			[
				'label'     => esc_html__( 'MetaData 2 Position', 'lastudio-kit' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => [
					'before_title'  => esc_html__( 'Before Title', 'lastudio-kit' ),
					'after_title'   => esc_html__( 'After Title', 'lastudio-kit' ),
					'after_content' => esc_html__( 'After Content', 'lastudio-kit' ),
				],
				'default'   => 'after_title',
				'condition' => [
					'show_meta' => 'yes',
				]
			]
		);

		$this->_end_controls_section();
	}

	protected function _register_section_query( $css_schema ) {
		/** Query section */
		$this->_start_controls_section(
			'section_query',
			[
				'label' => __( 'Query', 'lastudio-kit' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->_add_group_control(
			Group_Control_Related::get_type(),
			[
				'name'        => 'query',
				'object_type' => 'la_album',
				'post_type'   => 'la_album',
				'presets'     => [ 'full' ],
				'fields_options' => [
					'post_type' => [
						'default' => 'la_album',
						'options' => [
							'current_query' => __( 'Current Query', 'lastudio-kit' ),
							'la_album' => __( 'Latest Album', 'lastudio-kit' ),
							'by_id' => _x( 'Manual Selection', 'Posts Query Control', 'lastudio-kit' ),
							'related' => _x( 'Related', 'Posts Query Control', 'lastudio-kit' ),
						],
					],
					'orderby' => [
						'default' => 'date',
						'options' => [
							'date'          => __( 'Date', 'lastudio-kit' ),
							'title'         => __( 'Title', 'lastudio-kit' ),
							'rand'          => __( 'Random', 'lastudio-kit' ),
							'menu_order'    => __( 'Menu Order', 'lastudio-kit' ),
							'post__in'      => __( 'Manual Selection', 'lastudio-kit' ),
						],
					],
					'exclude' => [
						'options' => [
							'current_post' => __( 'Current Post', 'lastudio-kit' ),
							'manual_selection' => __( 'Manual Selection', 'lastudio-kit' ),
							'terms' => __( 'Album Terms', 'lastudio-kit' ),
						],
					],
					'exclude_ids' => [
						'object_type' => 'la_album',
					],
					'include_ids' => [
						'object_type' => 'la_album',
					],
				],
				'exclude' => [
					'exclude_authors',
					'authors',
					'offset',
					'ignore_sticky_posts',
				],
			]
		);

        $this->_add_control(
            'enable_ajax_load',
            [
                'label'     => __( 'Enable Ajax Load', 'lastudio-kit' ),
                'type'      => Controls_Manager::SWITCHER,
                'default'   => '',
                'condition' => [
                    'query_post_type!' => 'current_query',
                ],
            ]
        );

		$this->_add_control(
			'paginate',
			[
				'label'   => __( 'Pagination', 'lastudio-kit' ),
				'type'    => Controls_Manager::SWITCHER,
				'default' => ''
			]
		);

		$this->_add_control(
			'paginate_as_loadmore',
			[
				'label'     => __( 'Use Load More', 'lastudio-kit' ),
				'type'      => Controls_Manager::SWITCHER,
				'default'   => '',
				'condition' => [
					'paginate' => 'yes',
				],
			]
		);

		$this->_add_control(
			'loadmore_text',
			[
				'label'     => __( 'Load More Text', 'lastudio-kit' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => 'Load More',
				'condition' => [
					'paginate'             => 'yes',
					'paginate_as_loadmore' => 'yes',
				]
			]
		);

		$this->_end_controls_section();
	}

	protected function _register_section_style_content_inner( $css_schema ){

	}

	protected function _register_section_play_album( $css_schema ){
		$this->_start_controls_section(
			'section_buttonplay_style',
			array(
				'label'      => esc_html__( 'Button Play', 'lastudio-kit' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			)
		);
		
		$this->_add_control(
			'button_play_icon_position',
			array(
				'label'     => esc_html__( 'Icon Position', 'lastudio-kit' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => array(
					'row-reverse' => esc_html__( 'Before Text', 'lastudio-kit' ),
					'row'         => esc_html__( 'After Text', 'lastudio-kit' ),
				),
				'default'   => 'row',
				'selectors' => array(
					'{{WRAPPER}} ' . $css_schema['button_play'] => 'flex-direction: {{VALUE}}',
				),
			)
		);

		$this->_start_controls_tabs( 'tabs_button_play_style' );

		$this->_start_controls_tab(
			'tab_button_play_normal',
			array(
				'label' => esc_html__( 'Normal', 'lastudio-kit' ),
			)
		);
		$this->_add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'button_play_bg',
				'types'    => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} ' . $css_schema['button_play'],
				'exclude'  => array(
					'image',
					'position',
					'xpos',
					'ypos',
					'attachment',
					'attachment_alert',
					'repeat',
					'size',
					'bg_width'
				),
			)
		);

		$this->_add_control(
			'button_play_color',
			array(
				'label'     => esc_html__( 'Text Color', 'lastudio-kit' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_schema['button_play'] => 'color: {{VALUE}}',
				)
			)
		);


		$this->_add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'button_play_typography',
				'selector' => '{{WRAPPER}}  ' . $css_schema['button_play'],
			)
		);

		$this->_add_responsive_control(
			'button_play_icon_size',
			array(
				'label'     => esc_html__( 'Icon Size', 'lastudio-kit' ),
				'type'      => Controls_Manager::SLIDER,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_schema['button_play_icon'] => 'font-size: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->_add_control(
			'button_play_icon_color',
			array(
				'label'     => esc_html__( 'Icon Color', 'lastudio-kit' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_schema['button_play_icon'] => 'color: {{VALUE}}',
				),
			)
		);

		$this->_add_responsive_control(
			'button_play_icon_margin',
			array(
				'label'      => esc_html__( 'Icon Margin', 'lastudio-kit' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_schema['button_play_icon'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->_add_responsive_control(
			'button_play_padding',
			array(
				'label'      => esc_html__( 'Padding', 'lastudio-kit' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_schema['button_play'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->_add_responsive_control(
			'button_play_margin',
			array(
				'label'      => esc_html__( 'Margin', 'lastudio-kit' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_schema['button_play'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->_add_responsive_control(
			'button_play_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'lastudio-kit' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_schema['button_play'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->_add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'        => 'button_play_border',
				'label'       => esc_html__( 'Border', 'lastudio-kit' ),
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'    => '{{WRAPPER}} ' . $css_schema['button_play'],
			)
		);

		$this->_add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'button_play_box_shadow',
				'selector' => '{{WRAPPER}} ' . $css_schema['button_play'],
			)
		);

		$this->_end_controls_tab();

		$this->_start_controls_tab(
			'tab_button_play_hover',
			array(
				'label' => esc_html__( 'Hover', 'lastudio-kit' ),
			)
		);

		$this->_add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'button_play_hover_bg',
				'types'    => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} ' . $css_schema['button_play'] . ':hover',
				'exclude'  => array(
					'image',
					'position',
					'xpos',
					'ypos',
					'attachment',
					'attachment_alert',
					'repeat',
					'size',
					'bg_width'
				),
			)
		);

		$this->_add_control(
			'button_play_hover_color',
			array(
				'label'     => esc_html__( 'Text Color', 'lastudio-kit' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_schema['button_play'] . ':hover' => 'color: {{VALUE}}',
				),
			)
		);

		$this->_add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'button_play_hover_typography',
				'label'    => esc_html__( 'Typography', 'lastudio-kit' ),
				'selector' => '{{WRAPPER}}  ' . $css_schema['button_play'] . ':hover',
			)
		);

		$this->_add_control(
			'button_play_hover_text_decor',
			array(
				'label'     => esc_html__( 'Text Decoration', 'lastudio-kit' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => array(
					'none'      => esc_html__( 'None', 'lastudio-kit' ),
					'underline' => esc_html__( 'Underline', 'lastudio-kit' ),
				),
				'default'   => 'none',
				'selectors' => array(
					'{{WRAPPER}} ' . $css_schema['button_play'] . ':hover' => 'text-decoration: {{VALUE}}',
				),
			)
		);

		$this->_add_responsive_control(
			'button_play_icon_size_hover',
			array(
				'label'     => esc_html__( 'Icon Size', 'lastudio-kit' ),
				'type'      => Controls_Manager::SLIDER,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_schema['button_play'] . ':hover .lakit-btn-more-icon' => 'font-size: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->_add_control(
			'button_play_icon_color_hover',
			array(
				'label'     => esc_html__( 'Icon Color', 'lastudio-kit' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_schema['button_play'] . ':hover .lakit-btn-more-icon' => 'color: {{VALUE}}',
				),
			)
		);

		$this->_add_responsive_control(
			'button_play_icon_margin_hover',
			array(
				'label'      => esc_html__( 'Icon Margin', 'lastudio-kit' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_schema['button_play'] . ':hover .lakit-btn-more-icon' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->_add_responsive_control(
			'button_play_hover_padding',
			array(
				'label'      => esc_html__( 'Padding', 'lastudio-kit' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_schema['button_play'] . ':hover' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->_add_responsive_control(
			'button_play_hover_margin',
			array(
				'label'      => esc_html__( 'Margin', 'lastudio-kit' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_schema['button_play'] . ':hover' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->_add_responsive_control(
			'button_play_hover_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'lastudio-kit' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_schema['button_play'] . ':hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->_add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'        => 'button_play_hover_border',
				'label'       => esc_html__( 'Border', 'lastudio-kit' ),
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'    => '{{WRAPPER}} ' . $css_schema['button_play'] . ':hover',
			)
		);

		$this->_add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'button_play_hover_box_shadow',
				'selector' => '{{WRAPPER}} ' . $css_schema['button_play'] . ':hover',
			)
		);

		$this->_end_controls_tab();

		$this->_end_controls_tabs();

		$this->_add_responsive_control(
			'button_play_alignment',
			array(
				'label'     => esc_html__( 'Alignment', 'lastudio-kit' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => array(
					'left'   => array(
						'title' => esc_html__( 'Left', 'lastudio-kit' ),
						'icon'  => 'eicon-text-align-left',
					),
					'center' => array(
						'title' => esc_html__( 'Center', 'lastudio-kit' ),
						'icon'  => 'eicon-text-align-center',
					),
					'right'  => array(
						'title' => esc_html__( 'Right', 'lastudio-kit' ),
						'icon'  => 'eicon-text-align-right',
					)
				),
				'selectors' => array(
					'{{WRAPPER}} .lakit-posts__more-wrap' => 'text-align: {{VALUE}};',
				),
				'separator' => 'before',
			)
		);

		$this->_end_controls_section();
	}

	protected function register_controls() {

		$css_schema = apply_filters(
			'lastudio-kit/'.$this->get_lakit_name().'/css-schema',
			array(
				'wrap_outer'    => '.lakit-posts',
				'wrap'          => '.lakit-posts .lakit-posts__list_wrapper',
				'column'        => '.lakit-posts .lakit-posts__outer-box',
				'inner-box'     => '.lakit-posts .lakit-posts__inner-box',
				'content'       => '.lakit-posts .lakit-posts__inner-content',
				'inner-content' => '.lakit-posts .lakit-posts__inner-content-inner',
				'link'          => '.lakit-posts .lakit-posts__thumbnail-link',
				'thumb'         => '.lakit-posts .lakit-posts__thumbnail',
				'title'         => '.lakit-posts .lakit-posts__title',
				'excerpt'       => '.lakit-posts .lakit-posts__excerpt',
				'button'        => '.lakit-posts .lakit-posts__btn-more',
				'button_icon'   => '.lakit-posts .lakit-btn-more-icon',
				'meta1'         => '.lakit-posts .lakit-posts__meta1',
				'meta1-item'    => '.lakit-posts .lakit-posts__meta1 .lakit-posts__meta__item',
				'meta2'         => '.lakit-posts .lakit-posts__meta2',
				'meta2-item'    => '.lakit-posts .lakit-posts__meta2 .lakit-posts__meta__item',
				'notfoundmsg'   => '.nothing-found-message',
				'button_play'        => '.lakit-posts .lakit-btn_play',
				'button_play_icon'   => '.lakit-posts .lakit-btn_play-icon',
			)
		);

		$this->_register_section_layout( $css_schema );

		$this->_register_section_meta( $css_schema );

		$this->_register_section_query( $css_schema );

		$this->register_masonry_setting_section( [ 'enable_masonry' => 'yes' ] );

		$this->register_carousel_section( [ 'enable_masonry!' => 'yes' ], 'columns' );

		$this->_register_section_style_general( $css_schema );

		$this->_register_section_style_meta( $css_schema );
		
		$this->_register_section_play_album( $css_schema );

		$this->_register_section_style_pagination( $css_schema );

		$this->register_carousel_arrows_dots_style_section( [ 'enable_masonry!' => 'yes' ] );

		$this->update_control('layout_type', [
			'type'    => Controls_Manager::HIDDEN,
		]);

		$this->remove_control('keep_layout_mb');

		$this->_register_player_section();

	}

	protected function _register_player_section(){

		$css_schema = [
			'wrapper'  => '.lakitplayer',
			'controls' => '.lakitplayer__controls',
			'control_item' => '.lakitplayer__controls button',
		];

		/**
		 * PLAYER
		 */
		$this->_start_controls_section(
			'section_player_style',
			array(
				'label'      => esc_html__( 'Player', 'lastudio-kit' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			)
		);

		$this->_add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'controls_box_typography',
				'selector' => '{{WRAPPER}}  ' . $css_schema['controls'],
			),
			50
		);

		$this->_add_responsive_control(
			'controls_item_size',
			array(
				'label'      => esc_html__( 'Control Size', 'lastudio-kit' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'em', 'custom' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_schema['control_item'] => 'font-size: {{SIZE}}{{UNIT}}',
				)
			)
		);
		$this->_add_responsive_control(
			'controls_box_gap',
			array(
				'label'      => esc_html__( 'Box Item Gap', 'lastudio-kit' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'em', 'custom' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_schema['wrapper'] => '--control-box-gap: {{SIZE}}{{UNIT}}',
				)
			)
		);
		$this->_add_responsive_control(
			'controls_item_gap',
			array(
				'label'      => esc_html__( 'Item gap', 'lastudio-kit' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'em', 'custom' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_schema['wrapper'] => '--control-item-gap: {{SIZE}}{{UNIT}}',
				)
			)
		);

		$this->_add_control(
			'controls_box_color',
			array(
				'label'     => esc_html__( 'Color', 'lastudio-kit' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_schema['controls'] => 'color: {{VALUE}}',
				),
			),
			25
		);

		$this->_add_control(
			'controls__progressbar',
			array(
				'label'      => esc_html__( 'Progress Bar', 'lastudio-kit' ),
				'type'       => Controls_Manager::HEADING,
			)
		);
		$this->_add_responsive_control(
			'controls__progressbar_height',
			array(
				'label'      => esc_html__( 'Height', 'lastudio-kit' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'em', 'custom' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_schema['wrapper'] => '--track-height: {{SIZE}}{{UNIT}}',
				)
			)
		);
		$this->_add_control(
			'controls__progressbar_color',
			array(
				'label'     => esc_html__( 'Background Color', 'lastudio-kit' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_schema['wrapper'] => '--track-bg: {{VALUE}}',
				),
			),
			25
		);

		$this->_add_control(
			'controls__progressbar_active_color',
			array(
				'label'     => esc_html__( 'Active Background Color', 'lastudio-kit' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_schema['wrapper'] => '--track-active-bg: {{VALUE}}',
				),
			),
			25
		);
		$this->_add_responsive_control(
			'controls__progressbar_bullet',
			array(
				'label'      => esc_html__( 'Bullet Size', 'lastudio-kit' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'em', 'custom' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_schema['wrapper'] => '--thumb-size: {{SIZE}}{{UNIT}}',
				)
			)
		);
		$this->_add_control(
			'controls__progressbar_bullet_color',
			array(
				'label'     => esc_html__( 'Bullet Color', 'lastudio-kit' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_schema['wrapper'] => '--thumb-bg: {{VALUE}}',
				),
			),
			25
		);


		$this->_add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'controls_box_bg',
				'selector' => '{{WRAPPER}} ' . $css_schema['controls'],
				'separator' => 'before',
			),
			25
		);
		$this->_add_responsive_control(
			'controls_box_padding',
			array(
				'label'       => esc_html__( 'Padding', 'lastudio-kit' ),
				'type'        => Controls_Manager::DIMENSIONS,
				'size_units'  => ['px', 'em', 'custom'],
				'selectors'   => array(
					'{{WRAPPER}} ' . $css_schema['controls'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			),
			50
		);
		$this->_add_responsive_control(
			'controls_box_margin',
			array(
				'label'       => esc_html__( 'Margin', 'lastudio-kit' ),
				'type'        => Controls_Manager::DIMENSIONS,
				'size_units'  => ['px', 'em', 'custom'],
				'selectors'   => array(
					'{{WRAPPER}} ' . $css_schema['controls'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			),
			50
		);

		$this->_add_responsive_control(
			'controls_box_radius',
			array(
				'label'       => esc_html__( 'Border Radius', 'lastudio-kit' ),
				'type'        => Controls_Manager::DIMENSIONS,
				'size_units'  => ['px', 'em', 'custom'],
				'selectors'   => array(
					'{{WRAPPER}} ' . $css_schema['controls'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			),
			50
		);

		$this->_add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'        => 'controls_box_border',
				'label'       => esc_html__( 'Border', 'lastudio-kit' ),
				'placeholder' => '1px',
				'selector'    => '{{WRAPPER}} ' . $css_schema['controls'],
			),
			75
		);

		$this->_add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'controls_box_shadow',
				'selector' => '{{WRAPPER}} ' . $css_schema['controls'],
			),
			75
		);

		$this->end_controls_section();
	}

	private function shouldLoadPopup(){
		$this->popup_id = $this->get_settings_for_display('album_template_id');
		if( !empty($this->popup_id) && !isset(self::$popup_instance[$this->popup_id])){
			$this->add_script_depends('lastudio-kit-popup');
			$this->add_style_depends('lastudio-kit-popup');
			self::$popup_instance[$this->popup_id] = sprintf('<div data-elementor-type="popup" data-elementor-id="%1$d" class="elementor elementor-%1$d elementor-location-popup" data-elementor-settings="{}"></div>', $this->popup_id);
			echo self::$popup_instance[$this->popup_id];
		}
	}

	protected function render() {
		if('yes' === $this->get_settings_for_display('show_play_album')){
			$this->shouldLoadPopup();
		}
		parent::render();
	}
}