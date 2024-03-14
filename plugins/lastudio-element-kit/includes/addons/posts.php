<?php

/**
 * Class: LaStudioKit_Posts
 * Name: Posts
 * Slug: lakit-posts
 */


namespace Elementor;

if ( ! defined( 'WPINC' ) ) {
	die;
}

use LaStudioKitExtensions\Elementor\Classes\Query_Control as Module_Query;
use LaStudioKitExtensions\Elementor\Controls\Group_Control_Related as Group_Control_Related;


/**
 * Posts Widget
 */

class LaStudioKit_Posts extends LaStudioKit_Base {

		protected function enqueue_addon_resources() {

			$this->add_script_depends( 'jquery-isotope' );

			if ( ! lastudio_kit_settings()->is_combine_js_css() ) {
				$this->add_script_depends( 'lastudio-kit-base' );
				if ( ! lastudio_kit()->is_optimized_css_mode() ) {
					wp_register_style( $this->get_name(), lastudio_kit()->plugin_url( 'assets/css/addons/posts.min.css' ), [ 'lastudio-kit-base' ], lastudio_kit()->get_version() );
					$this->add_style_depends( $this->get_name() );
				}
			}
		}

		public function get_widget_css_config( $widget_name ) {
			$file_url  = lastudio_kit()->plugin_url( 'assets/css/addons/posts.min.css' );
			$file_path = lastudio_kit()->plugin_path( 'assets/css/addons/posts.min.css' );

			return [
				'key'       => $widget_name,
				'version'   => lastudio_kit()->get_version( true ),
				'file_path' => $file_path,
				'data'      => [
					'file_url' => $file_url
				]
			];
		}

		private $_query = null;

		public $item_counter = 0;

		public function get_name() {
			return 'lakit-posts';
		}

		protected function get_html_wrapper_class() {
			return 'lastudio-kit elementor-lakit-gposts elementor-' . $this->get_name();
		}

		protected function get_widget_title() {
			return esc_html__( 'Posts', 'lastudio-kit' );
		}

		public function get_icon() {
			return 'eicon-post-list';
		}

		public function get_keywords() {
			return [ 'posts', 'blog', 'news' ];
		}

		protected function _register_section_layout( $css_scheme ) {

			$preset_type = $this->preset_list();

			$default_preset_type = array_keys( $preset_type );


			/** Layout section */
			$this->_start_controls_section(
				'section_settings',
				array(
					'label' => esc_html__( 'Layout', 'lastudio-kit' ),
				)
			);

			$this->_add_control(
				'layout_type',
				array(
					'label'   => esc_html__( 'Layout type', 'lastudio-kit' ),
					'type'    => Controls_Manager::SELECT,
					'default' => 'grid',
					'options' => array(
						'grid' => esc_html__( 'Default', 'lastudio-kit' )
					),
				)
			);

			$this->_add_control(
				'preset',
				array(
					'label'   => esc_html__( 'Preset', 'lastudio-kit' ),
					'type'    => Controls_Manager::SELECT,
					'default' => $default_preset_type[0],
					'options' => $preset_type
				)
			);

			$this->_add_responsive_control(
				'columns',
				array(
					'label'   => esc_html__( 'Columns', 'lastudio-kit' ),
					'type'    => Controls_Manager::SELECT,
					'default' => 3,
					'options' => lastudio_kit_helper()->get_select_range( 6 )
				)
			);

			$this->_add_control(
				'keep_layout_mb',
				array(
					'type'         => 'switcher',
					'label'        => esc_html__( 'Keep Layout on mobile', 'lastudio-kit' ),
					'description'  => esc_html__( 'This option only works with the List preset', 'lastudio-kit' ),
					'label_on'     => esc_html__( 'Yes', 'lastudio-kit' ),
					'label_off'    => esc_html__( 'No', 'lastudio-kit' ),
					'return_value' => 'yes',
					'default'      => '',
					'prefix_class' => 'lakit-keep-mbl-',
				)
			);

			$this->_add_control(
				'enable_masonry',
				array(
					'type'         => 'switcher',
					'label'        => esc_html__( 'Enable Masonry?', 'lastudio-kit' ),
					'label_on'     => esc_html__( 'Yes', 'lastudio-kit' ),
					'label_off'    => esc_html__( 'No', 'lastudio-kit' ),
					'return_value' => 'yes',
					'default'      => '',
				)
			);


			$this->_add_control(
				'title_html_tag',
				array(
					'label'     => esc_html__( 'Title HTML Tag', 'lastudio-kit' ),
					'type'      => Controls_Manager::SELECT,
					'options'   => array(
						'h1'   => esc_html__( 'H1', 'lastudio-kit' ),
						'h2'   => esc_html__( 'H2', 'lastudio-kit' ),
						'h3'   => esc_html__( 'H3', 'lastudio-kit' ),
						'h4'   => esc_html__( 'H4', 'lastudio-kit' ),
						'h5'   => esc_html__( 'H5', 'lastudio-kit' ),
						'h6'   => esc_html__( 'H6', 'lastudio-kit' ),
						'div'  => esc_html__( 'div', 'lastudio-kit' ),
						'span' => esc_html__( 'span', 'lastudio-kit' ),
						'p'    => esc_html__( 'p', 'lastudio-kit' ),
					),
					'default'   => 'h4',
					'separator' => 'before',
				)
			);

			$this->_add_control(
				'show_title',
				array(
					'type'         => 'switcher',
					'label'        => esc_html__( 'Show Posts Title', 'lastudio-kit' ),
					'label_on'     => esc_html__( 'Yes', 'lastudio-kit' ),
					'label_off'    => esc_html__( 'No', 'lastudio-kit' ),
					'return_value' => 'yes',
					'default'      => 'yes',
					'separator'    => 'before',
				)
			);

			$this->_add_control(
				'title_trimmed',
				array(
					'type'         => 'switcher',
					'label'        => esc_html__( 'Title Word Trim', 'lastudio-kit' ),
					'label_on'     => esc_html__( 'Yes', 'lastudio-kit' ),
					'label_off'    => esc_html__( 'No', 'lastudio-kit' ),
					'return_value' => 'yes',
					'default'      => 'no',
					'condition'    => array(
						'show_title' => 'yes',
					),
				)
			);

			$this->_add_control(
				'title_length',
				array(
					'type'      => 'number',
					'label'     => esc_html__( 'Title Length', 'lastudio-kit' ),
					'default'   => 5,
					'min'       => 1,
					'max'       => 50,
					'step'      => 1,
					'condition' => array(
						'title_trimmed' => 'yes',
					),
				)
			);

			$this->_add_control(
				'title_trimmed_ending_text',
				array(
					'type'      => 'text',
					'label'     => esc_html__( 'Title Trimmed Ending', 'lastudio-kit' ),
					'default'   => '...',
					'condition' => array(
						'title_trimmed' => 'yes',
					)
				)
			);

			$this->_add_control(
				'show_image',
				array(
					'type'         => 'switcher',
					'label'        => esc_html__( 'Show Posts Featured Image', 'lastudio-kit' ),
					'label_on'     => esc_html__( 'Yes', 'lastudio-kit' ),
					'label_off'    => esc_html__( 'No', 'lastudio-kit' ),
					'return_value' => 'yes',
					'default'      => 'yes'
				)
			);

			$this->_add_control(
				'thumb_size',
				array(
					'type'      => Controls_Manager::SELECT,
					'label'     => esc_html__( 'Featured Image Size', 'lastudio-kit' ),
					'default'   => 'full',
					'options'   => lastudio_kit_helper()->get_image_sizes(),
					'condition' => array(
						'show_image' => 'yes'
					)
				)
			);

			$this->_add_control(
				'show_excerpt',
				array(
					'label'        => esc_html__( 'Show Excerpt?', 'lastudio-kit' ),
					'type'         => Controls_Manager::SWITCHER,
					'label_on'     => esc_html__( 'Yes', 'lastudio-kit' ),
					'label_off'    => esc_html__( 'No', 'lastudio-kit' ),
					'return_value' => 'true',
					'default'      => false
				)
			);

			$this->_add_control(
				'excerpt_length',
				array(
					'label'     => esc_html__( 'Custom Excerpt Length', 'lastudio-kit' ),
					'type'      => Controls_Manager::NUMBER,
					'default'   => 20,
					'min'       => 0,
					'max'       => 200,
					'step'      => 1,
					'condition' => array(
						'show_excerpt' => 'true'
					)
				)
			);

			$this->_add_control(
				'show_more',
				array(
					'type'         => 'switcher',
					'label'        => esc_html__( 'Show Read More Button', 'lastudio-kit' ),
					'label_on'     => esc_html__( 'Yes', 'lastudio-kit' ),
					'label_off'    => esc_html__( 'No', 'lastudio-kit' ),
					'return_value' => 'yes',
					'default'      => '',
				)
			);

			$this->_add_control(
				'more_text',
				array(
					'type'      => 'text',
					'label'     => esc_html__( 'Read More Button Text', 'lastudio-kit' ),
					'default'   => esc_html__( 'Read More', 'lastudio-kit' ),
					'condition' => array(
						'show_more' => 'yes',
					),
				)
			);

			$this->_add_icon_control(
				'more_icon',
				[
					'label'       => __( 'Read More Icon', 'lastudio-kit' ),
					'type'        => Controls_Manager::ICON,
					'file'        => '',
					'skin'        => 'inline',
					'label_block' => false,
					'condition'   => array(
						'show_more' => 'yes',
					),
				]
			);

			$this->_end_controls_section();
		}

		protected function _register_section_meta( $css_scheme ) {
			$this->_start_controls_section(
				'section_meta',
				[
					'label' => __( 'Meta Data', 'lastudio-kit' ),
					'tab'   => Controls_Manager::TAB_CONTENT,
				]
			);

			$this->_add_control(
				'floating_date',
				[
					'label'     => esc_html__( 'Show Floating Date', 'lastudio-kit' ),
					'type'      => Controls_Manager::SWITCHER,
					'label_on'  => esc_html__( 'Yes', 'lastudio-kit' ),
					'label_off' => esc_html__( 'No', 'lastudio-kit' ),
					'default'   => 'no'
				]
			);

			$this->_add_control(
				'floating_date_style',
				[
					'label'     => esc_html__( 'Floating Date Style', 'lastudio-kit' ),
					'type'      => Controls_Manager::SELECT,
					'options'   => [
						'circle' => esc_html__( 'Circle', 'lastudio-kit' ),
						'full-date' => esc_html__( 'Full date', 'lastudio-kit' ),
					],
					'condition' => [
						'floating_date' => 'yes',
					]
				]
			);

			$this->_add_control(
				'floating_category',
				[
					'label'     => esc_html__( 'Show Floating Category', 'lastudio-kit' ),
					'type'      => Controls_Manager::SWITCHER,
					'label_on'  => esc_html__( 'Yes', 'lastudio-kit' ),
					'label_off' => esc_html__( 'No', 'lastudio-kit' ),
					'default'   => 'no'
				]
			);

			$this->_add_control(
				'floating_postformat',
				[
					'label'     => esc_html__( 'Show PostFormat Icon', 'lastudio-kit' ),
					'type'      => Controls_Manager::SWITCHER,
					'label_on'  => esc_html__( 'Yes', 'lastudio-kit' ),
					'label_off' => esc_html__( 'No', 'lastudio-kit' ),
					'default'   => 'no'
				]
			);

            $this->_add_control(
				'postformat_content',
				[
					'label'     => esc_html__( 'Show PostFormat Content', 'lastudio-kit' ),
					'type'      => Controls_Manager::SWITCHER,
					'label_on'  => esc_html__( 'Yes', 'lastudio-kit' ),
					'label_off' => esc_html__( 'No', 'lastudio-kit' ),
					'default'   => 'no'
				]
			);

			$this->_add_control(
				'floating_counter',
				[
					'label'     => esc_html__( 'Show Post Counter', 'lastudio-kit' ),
					'type'      => Controls_Manager::SWITCHER,
					'label_on'  => esc_html__( 'Yes', 'lastudio-kit' ),
					'label_off' => esc_html__( 'No', 'lastudio-kit' ),
					'default'   => 'no'
				]
			);
			$this->_add_control(
				'floating_counter_as',
				[
					'label'     => esc_html__( 'Counter as Icon', 'lastudio-kit' ),
					'type'      => Controls_Manager::SWITCHER,
					'label_on'  => esc_html__( 'Yes', 'lastudio-kit' ),
					'label_off' => esc_html__( 'No', 'lastudio-kit' ),
					'default'   => 'no',
					'condition' => [
						'floating_counter' => 'yes'
					]
				]
			);
			$this->_add_icon_control(
				'counter_icon',
				[
					'label'       => __( 'Custom Icon', 'lastudio-kit' ),
					'type'        => Controls_Manager::ICONS,
					'file'        => '',
					'skin'        => 'inline',
					'label_block' => false,
					'condition'   => [
						'floating_counter'    => 'yes',
						'floating_counter_as' => 'yes',
					],
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
					'options' => apply_filters( 'lastudio-kit/' . $this->get_lakit_name() . '/metadata', [
						'category' => esc_html__( 'Category', 'lastudio-kit' ),
						'tag'      => esc_html__( 'Tags', 'lastudio-kit' ),
						'author'   => esc_html__( 'Author', 'lastudio-kit' ),
						'date'     => esc_html__( 'Posted Date', 'lastudio-kit' ),
						'comment'  => esc_html__( 'Comment', 'lastudio-kit' ),
						'view'      => esc_html__( 'View', 'lastudio-kit' ),
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
						'after_button'  => esc_html__( 'After Button', 'lastudio-kit' ),
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
						'after_button'  => esc_html__( 'After Button', 'lastudio-kit' ),
					],
					'default'   => 'after_title',
					'condition' => [
						'show_meta' => 'yes',
					]
				]
			);

			$this->_add_control(
				'show_author_avatar',
				array(
					'type'         => 'switcher',
					'label'        => esc_html__( 'Show Author Image', 'lastudio-kit' ),
					'label_on'     => esc_html__( 'Yes', 'lastudio-kit' ),
					'label_off'    => esc_html__( 'No', 'lastudio-kit' ),
					'return_value' => 'yes',
					'default'      => '',
					'condition'    => [
						'show_meta' => 'yes',
					]
				)
			);

			$this->_end_controls_section();
		}

		protected function _register_section_query( $css_scheme ) {
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
					'object_type' => '',
					'presets'     => [ 'full' ]
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
				'paginate_infinite',
				[
					'label'     => esc_html__( 'Infinite loading', 'lastudio-kit' ),
					'type'      => Controls_Manager::SWITCHER,
					'default'   => '',
					'condition' => [
						'paginate'             => 'yes',
						'paginate_as_loadmore' => 'yes',
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

			$this->_add_control(
				'nothing_found_message',
				[
					'label'       => esc_html__( 'Nothing Found Message', 'lastudio-kit' ),
					'type'        => Controls_Manager::TEXT,
					'label_block' => true,
					'separator'   => 'before',
				]
			);

			$this->_end_controls_section();
		}

		protected function _register_section_style_content_inner( $css_scheme ) {

		}

		protected function _register_section_style_general( $css_scheme ) {
			/** Style section */
			$this->_start_controls_section(
				'section_column_style',
				array(
					'label'      => esc_html__( 'Column', 'lastudio-kit' ),
					'tab'        => Controls_Manager::TAB_STYLE,
					'show_label' => false,
				)
			);

			$this->_add_responsive_control(
				'column_padding',
				array(
					'label'       => esc_html__( 'Column Padding', 'lastudio-kit' ),
					'type'        => Controls_Manager::DIMENSIONS,
					'size_units'  => array( 'px' ),
					'render_type' => 'template',
					'selectors'   => array(
						'{{WRAPPER}} ' . $css_scheme['column'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						'{{WRAPPER}} '                         => '--lakit-carousel-item-top-space: {{TOP}}{{UNIT}}; --lakit-carousel-item-right-space: {{RIGHT}}{{UNIT}};--lakit-carousel-item-bottom-space: {{BOTTOM}}{{UNIT}};--lakit-carousel-item-left-space: {{LEFT}}{{UNIT}};--lakit-gcol-top-space: {{TOP}}{{UNIT}}; --lakit-gcol-right-space: {{RIGHT}}{{UNIT}};--lakit-gcol-bottom-space: {{BOTTOM}}{{UNIT}};--lakit-gcol-left-space: {{LEFT}}{{UNIT}};',
					),
				)
			);
            $this->_add_group_control(
                Group_Control_Border::get_type(),
                array(
                    'name'        => 'column_border',
                    'label'       => esc_html__( 'Border', 'lastudio-kit' ),
                    'placeholder' => '1px',
                    'default'     => '1px',
                    'selector'    => '{{WRAPPER}} ' . $css_scheme['column'],
                )
            );

			$this->_end_controls_section();

			$this->_start_controls_section(
				'section_box_style',
				array(
					'label'      => esc_html__( 'Item', 'lastudio-kit' ),
					'tab'        => Controls_Manager::TAB_STYLE,
					'show_label' => false,
				)
			);

			$this->_add_responsive_control(
				'boxcontent_alignment',
				array(
					'label'     => esc_html__( 'Horizontal Alignment', 'lastudio-kit' ),
					'type'      => Controls_Manager::CHOOSE,
					'options'   => array(
						'left'   => array(
							'title' => esc_html__( 'Left', 'lastudio-kit' ),
							'icon'  => 'eicon-h-align-left',
						),
						'center' => array(
							'title' => esc_html__( 'Center', 'lastudio-kit' ),
							'icon'  => 'eicon-h-align-center',
						),
						'right'  => array(
							'title' => esc_html__( 'Right', 'lastudio-kit' ),
							'icon'  => 'eicon-h-align-right',
						),
					),
					'selectors' => array(
						'{{WRAPPER}} ' . $css_scheme['inner-box'] => 'text-align: {{VALUE}};',
					),
				)
			);
			$this->_add_responsive_control(
				'boxcontent_v_alignment',
				array(
					'label'     => esc_html__( 'Vertical Alignment', 'lastudio-kit' ),
					'type'      => Controls_Manager::CHOOSE,
					'options'   => array(
						'flex-start' => array(
							'title' => esc_html__( 'Top', 'lastudio-kit' ),
							'icon'  => 'eicon-v-align-top',
						),
						'center'     => array(
							'title' => esc_html__( 'Middle', 'lastudio-kit' ),
							'icon'  => 'eicon-v-align-middle',
						),
						'flex-end'   => array(
							'title' => esc_html__( 'Bottom', 'lastudio-kit' ),
							'icon'  => 'eicon-v-align-bottom',
						),
					),
					'selectors' => array(
						'{{WRAPPER}} ' . $css_scheme['inner-box'] => 'align-items: {{VALUE}};',
					),
				)
			);

            $this->_start_controls_tabs( 'item__tabs' );
            $this->_start_controls_tab( 'item__tab_normal', [
                'label' => __( 'Normal', 'lastudio-kit' ),
            ]);
            $this->_add_control(
                'box_color',
                array(
                    'label'     => esc_html__( 'Text Color', 'lastudio-kit' ),
                    'type'      => Controls_Manager::COLOR,
                    'selectors' => array(
                        '{{WRAPPER}}' => '--lakit-posts-box-tcolor: {{VALUE}}',
                    ),
                )
            );
            $this->_add_control(
                'box_bg',
                array(
                    'label'     => esc_html__( 'Background Color', 'lastudio-kit' ),
                    'type'      => Controls_Manager::COLOR,
                    'selectors' => array(
                        '{{WRAPPER}}' => '--lakit-posts-box-bgcolor: {{VALUE}}',
                    ),
                )
            );
            $this->_add_responsive_control(
                'box_padding',
                array(
                    'label'      => esc_html__( 'Padding', 'lastudio-kit' ),
                    'type'       => Controls_Manager::DIMENSIONS,
                    'size_units' => array( 'px', '%', 'custom' ),
                    'selectors'  => array(
                        '{{WRAPPER}} ' . $css_scheme['inner-box'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ),
                )
            );
            $this->_add_responsive_control(
                'box_margin',
                array(
                    'label'      => esc_html__( 'Margin', 'lastudio-kit' ),
                    'type'       => Controls_Manager::DIMENSIONS,
                    'size_units' => array( 'px', '%', 'custom' ),
                    'selectors'  => array(
                        '{{WRAPPER}} ' . $css_scheme['inner-box'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ),
                )
            );

            $this->_add_responsive_control(
                'box_border_radius',
                array(
                    'label'      => __( 'Border Radius', 'lastudio-kit' ),
                    'type'       => Controls_Manager::DIMENSIONS,
                    'size_units' => array( 'px', '%' ),
                    'selectors'  => array(
                        '{{WRAPPER}} ' . $css_scheme['inner-box'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ),
                )
            );
            $this->_add_group_control(
                Group_Control_Border::get_type(),
                array(
                    'name'        => 'box_border',
                    'label'       => esc_html__( 'Border', 'lastudio-kit' ),
                    'placeholder' => '1px',
                    'default'     => '1px',
                    'selector'    => '{{WRAPPER}} ' . $css_scheme['inner-box'],
                )
            );
            $this->_add_group_control(
                Group_Control_Box_Shadow::get_type(),
                array(
                    'name'     => 'inner_box_shadow',
                    'selector' => '{{WRAPPER}} ' . $css_scheme['inner-box'],
                )
            );
            $this->_end_controls_tab();
            $this->_start_controls_tab( 'item__tab_hover', [
                'label' => __( 'Hover', 'lastudio-kit' ),
            ]);
            $this->_add_control(
                'box_hover_color',
                array(
                    'label'     => esc_html__( 'Text Color', 'lastudio-kit' ),
                    'type'      => Controls_Manager::COLOR,
                    'selectors' => array(
                        '{{WRAPPER}}' => '--lakit-posts-box-tcolor-h: {{VALUE}}',
                    ),
                )
            );
            $this->_add_control(
                'box_hover_bg',
                array(
                    'label'     => esc_html__( 'Background Color', 'lastudio-kit' ),
                    'type'      => Controls_Manager::COLOR,
                    'selectors' => array(
                        '{{WRAPPER}}' => '--lakit-posts-box-bgcolor-h: {{VALUE}}',
                    ),
                )
            );
            $this->_add_responsive_control(
                'box_padding_hover',
                array(
                    'label'      => esc_html__( 'Padding', 'lastudio-kit' ),
                    'type'       => Controls_Manager::DIMENSIONS,
                    'size_units' => array( 'px', '%', 'custom' ),
                    'selectors'  => array(
                        '{{WRAPPER}} ' . $css_scheme['inner-box'] . ':hover' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ),
                )
            );

            $this->_add_responsive_control(
                'box_margin_hover',
                array(
                    'label'      => esc_html__( 'Margin', 'lastudio-kit' ),
                    'type'       => Controls_Manager::DIMENSIONS,
                    'size_units' => array( 'px', '%', 'custom' ),
                    'selectors'  => array(
                        '{{WRAPPER}} ' . $css_scheme['inner-box'] . ':hover' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ),
                )
            );

            $this->_add_responsive_control(
                'box_radius_hover',
                array(
                    'label'      => __( 'Border Radius', 'lastudio-kit' ),
                    'type'       => Controls_Manager::DIMENSIONS,
                    'size_units' => array( 'px', '%' ),
                    'selectors'  => array(
                        '{{WRAPPER}} ' . $css_scheme['inner-box'] . ':hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ),
                )
            );
            $this->_add_group_control(
                Group_Control_Border::get_type(),
                array(
                    'name'        => 'box_border_hover',
                    'label'       => esc_html__( 'Border', 'lastudio-kit' ),
                    'placeholder' => '1px',
                    'default'     => '1px',
                    'selector'    => '{{WRAPPER}} ' . $css_scheme['inner-box'] . ':hover',
                )
            );
            $this->_add_group_control(
                Group_Control_Box_Shadow::get_type(),
                array(
                    'name'     => 'box_shadow_hover',
                    'selector' => '{{WRAPPER}} ' . $css_scheme['inner-box'] . ':hover',
                )
            );
            $this->_end_controls_tab();
            $this->_end_controls_tabs();

			$this->_add_control(
				'box_overlay_heading',
				[
					'label'       => esc_html__( 'Background Overlay', 'lastudio-kit' ),
					'type'        => Controls_Manager::HEADING,
					'label_block' => true,
					'separator'   => 'before',
				]
			);
			$this->_start_controls_tabs( 'box_bg_tabs' );
			$this->_start_controls_tab( 'box_bg_tab_normal', [
				'label' => __( 'Normal', 'lastudio-kit' ),
			] );
			$this->_add_group_control(
				Group_Control_Background::get_type(),
				array(
					'name'     => 'box_bg_overlay',
					'selector' => '{{WRAPPER}} .lakit-posts__inner-box:after',
				),
				25
			);
			$this->_end_controls_tab();
			$this->_start_controls_tab( 'box_bg_tab_hover', [
				'label' => __( 'Hover', 'lastudio-kit' ),
			] );
			$this->_add_group_control(
				Group_Control_Background::get_type(),
				array(
					'name'     => 'box_bg_overlay_hover',
					'selector' => '{{WRAPPER}} .lakit-posts__inner-box:hover:after',
				),
				25
			);
			$this->_end_controls_tab();
			$this->_end_controls_tabs();

			$this->_end_controls_section();

			$this->_start_controls_section(
				'section_thumb_style',
				array(
					'label'      => esc_html__( 'Thumbnail', 'lastudio-kit' ),
					'tab'        => Controls_Manager::TAB_STYLE,
					'show_label' => false,
				)
			);

			$this->_add_responsive_control(
				'custom_thumb_width',
				array(
					'label'       => esc_html__( 'Thumbnail Width', 'lastudio-kit' ),
					'description' => esc_html__( 'This option only works with the List preset', 'lastudio-kit' ),
					'type'        => Controls_Manager::SLIDER,
					'range'       => array(
						'px' => array(
							'min' => 100,
							'max' => 1000,
						),
						'vh' => array(
							'min' => 0,
							'max' => 100,
						)
					),
					'size_units'  => [ 'px', '%', 'vh', 'vw', 'em' ],
					'selectors'   => [
						'{{WRAPPER}} .lakit-posts' => '--lakit-posts-thumbnail-width: {{SIZE}}{{UNIT}};'
					],
				)
			);
			$this->_add_responsive_control(
				'custom_thumb_spacing',
				array(
					'label'       => esc_html__( 'Thumbnail Spacing', 'lastudio-kit' ),
					'description' => esc_html__( 'This option only works with the List preset', 'lastudio-kit' ),
					'type'        => Controls_Manager::SLIDER,
					'range'       => array(
						'px' => array(
							'min' => 100,
							'max' => 1000,
						),
						'vh' => array(
							'min' => 0,
							'max' => 100,
						)
					),
					'size_units'  => [ 'px', '%', 'vh', 'vw', 'em' ],
					'selectors'   => [
						'{{WRAPPER}} .lakit-posts' => '--lakit-posts-thumbnail-spacing: {{SIZE}}{{UNIT}};'
					],
				)
			);

			$this->_add_control(
				'enable_custom_image_height',
				array(
					'label'        => esc_html__( 'Enable Custom Image Height', 'lastudio-kit' ),
					'type'         => Controls_Manager::SWITCHER,
					'label_on'     => esc_html__( 'Yes', 'lastudio-kit' ),
					'label_off'    => esc_html__( 'No', 'lastudio-kit' ),
					'return_value' => 'true',
					'default'      => '',
					'prefix_class' => 'active-object-fit active-object-fit-',
				)
			);

			$this->_add_responsive_control(
				'custom_image_height',
				array(
					'label'      => esc_html__( 'Image Height', 'lastudio-kit' ),
					'type'       => Controls_Manager::SLIDER,
					'size_units' => [ 'px', '%', 'vh', 'vw', 'custom' ],
					'default'    => [
						'size' => 300,
						'unit' => 'px'
					],
					'selectors'  => [
						'{{WRAPPER}} ' . $css_scheme['link'] => 'padding-bottom: {{SIZE}}{{UNIT}};',
						'{{WRAPPER}} .postformat-content .postformat--gallery span' => 'padding-bottom: {{SIZE}}{{UNIT}};'
					],
					'condition'  => [
						'enable_custom_image_height!' => ''
					]
				)
			);

			$this->_add_group_control(
				Group_Control_Border::get_type(),
				array(
					'name'        => 'thumb_border',
					'label'       => esc_html__( 'Border', 'lastudio-kit' ),
					'placeholder' => '1px',
					'default'     => '1px',
					'selector'    => '{{WRAPPER}} ' . $css_scheme['thumb'],
				)
			);

			$this->_add_responsive_control(
				'thumb_border_radius',
				array(
					'label'      => esc_html__( 'Border Radius', 'lastudio-kit' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => array( 'px', '%' ),
					'selectors'  => array(
						'{{WRAPPER}} ' . $css_scheme['thumb'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					),
				)
			);

			$this->_add_group_control(
				Group_Control_Box_Shadow::get_type(),
				array(
					'name'     => 'thumb_box_shadow',
					'selector' => '{{WRAPPER}} ' . $css_scheme['thumb'],
				)
			);

			$this->_add_responsive_control(
				'thumb_margin',
				array(
					'label'      => esc_html__( 'Margin', 'lastudio-kit' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => array( 'px', '%', 'em', 'custom' ),
					'selectors'  => array(
						'{{WRAPPER}} ' . $css_scheme['thumb'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					),
				)
			);

			$this->_add_responsive_control(
				'thumb_padding',
				array(
					'label'      => esc_html__( 'Padding', 'lastudio-kit' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => array( 'px', '%', 'em' ),
					'selectors'  => array(
						'{{WRAPPER}} ' . $css_scheme['thumb'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					),
				)
			);

            $this->start_controls_tabs( 'tabs_thumb_style' );
            $this->start_controls_tab(
                'tabs_thumb_n',
                array(
                    'label' => esc_html__( 'Normal', 'lastudio-kit' ),
                )
            );
            $this->add_control(
                'image_opacity',
                [
                    'label' => esc_html__( 'Opacity', 'lastudio-kit' ),
                    'type' => Controls_Manager::SLIDER,
                    'range' => [
                        'px' => [
                            'max' => 1,
                            'min' => 0.10,
                            'step' => 0.01,
                        ],
                    ],
                    'selectors' => [
                        '{{WRAPPER}}' => '--lakit-post-thumb-opacity: {{SIZE}};',
                    ],
                ]
            );
            $this->add_responsive_control(
                'image_scale',
                [
                    'label' => __( 'Image Scale', 'lastudio-kit' ),
                    'type' => Controls_Manager::SLIDER,
                    'selectors' => [
                        '{{WRAPPER}}' => '--lakit-post-thumb-scale: {{SIZE}};',
                    ],
                ]
            );
            $this->add_group_control(
                Group_Control_Css_Filter::get_type(),
                [
                    'name' => 'image_css_filter',
                    'selector' => '{{WRAPPER}} .lakit-posts__thumbnail img',
                ]
            );
            $this->end_controls_tab();
            $this->start_controls_tab(
                'tabs_thumb_h',
                array(
                    'label' => esc_html__( 'Hover', 'lastudio-kit' ),
                )
            );
            $this->add_control(
                'image_opacity_h',
                [
                    'label' => esc_html__( 'Opacity', 'lastudio-kit' ),
                    'type' => Controls_Manager::SLIDER,
                    'range' => [
                        'px' => [
                            'max' => 1,
                            'min' => 0.10,
                            'step' => 0.01,
                        ],
                    ],
                    'selectors' => [
                        '{{WRAPPER}}' => '--lakit-post-thumb-opacity-hover: {{SIZE}};',
                    ],
                ]
            );
            $this->add_responsive_control(
                'image_scale_h',
                [
                    'label' => __( 'Image Scale', 'lastudio-kit' ),
                    'type' => Controls_Manager::SLIDER,
                    'selectors' => [
                        '{{WRAPPER}}' => '--lakit-post-thumb-scale-hover: {{SIZE}};',
                    ],
                ]
            );
            $this->add_group_control(
                Group_Control_Css_Filter::get_type(),
                [
                    'name' => 'image_css_filter_h',
                    'selector' => '{{WRAPPER}} .lakit-posts__inner-box:hover .lakit-posts__thumbnail img',
                ]
            );
            $this->end_controls_tab();
            $this->end_controls_tabs();

			$this->_end_controls_section();

			$this->start_controls_section(
				'section_thumb_overlay',
				array(
					'label' => esc_html__( 'Thumbnail Overlay', 'lastudio-kit' ),
					'tab'   => Controls_Manager::TAB_STYLE,
				)
			);

			$this->start_controls_tabs( 'tabs_overlay_style' );

			$this->start_controls_tab(
				'tabs_overlay_normal',
				array(
					'label' => esc_html__( 'Normal', 'lastudio-kit' ),
				)
			);

			$this->add_group_control(
				Group_Control_Background::get_type(),
				array(
					'name'     => 'overlay_background',
					'selector' => '{{WRAPPER}} .lakit-posts__thumbnail:after',
				)
			);

            $this->add_control(
                'overlay_blend_mode',
                [
                    'label' => esc_html__( 'Blend Mode', 'elementor' ),
                    'type' => Controls_Manager::SELECT,
                    'options' => lastudio_kit_helper()->get_blend_mode_options(),
                    'selectors' => [
                        '{{WRAPPER}} .lakit-posts__thumbnail:after' => 'mix-blend-mode: {{VALUE}}',
                    ],
                    'conditions' => [
                        'relation' => 'or',
                        'terms' => [
                            [
                                'name' => 'overlay_background_image[url]',
                                'operator' => '!==',
                                'value' => '',
                            ],
                            [
                                'name' => 'overlay_background_color',
                                'operator' => '!==',
                                'value' => '',
                            ],
                        ],
                    ],
                ]
            );

			$this->add_control(
				'overlay_opacity',
				array(
					'label'     => esc_html__( 'Opacity', 'lastudio-kit' ),
					'type'      => Controls_Manager::NUMBER,
					'default'   => 0.6,
					'min'       => 0,
					'max'       => 1,
					'step'      => 0.1,
					'selectors' => array(
						'{{WRAPPER}} .lakit-posts__thumbnail:after' => 'opacity: {{VALUE}};'
					)
				)
			);

			$this->add_control(
				'overlay_zindex',
				array(
					'label'     => esc_html__( 'Z-Index', 'lastudio-kit' ),
					'type'      => Controls_Manager::NUMBER,
					'min'       => - 1,
					'max'       => 10,
					'step'      => 1,
					'selectors' => array(
						'{{WRAPPER}} .lakit-posts__thumbnail:after' => 'z-index: {{VALUE}};'
					)
				)
			);

			$this->add_responsive_control(
				'overlay_position',
				array(
					'label'      => __( 'Position', 'lastudio-kit' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => array( 'px', '%', 'em' ),
					'selectors'  => array(
						'{{WRAPPER}} .lakit-posts__thumbnail:after' => 'top:{{TOP}}{{UNIT}};right:{{RIGHT}}{{UNIT}};bottom:{{BOTTOM}}{{UNIT}};left:{{LEFT}}{{UNIT}};',
					),
				)
			);

			$this->add_responsive_control(
				'overlay_radius',
				array(
					'label'      => esc_html__( 'Border Radius', 'lastudio-kit' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => array( 'px', '%', 'em' ),
					'selectors'  => array(
						'{{WRAPPER}} .lakit-posts__thumbnail:after' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					),
				)
			);

			$this->add_group_control(
				Group_Control_Border::get_type(),
				array(
					'name'        => 'overlay_border',
					'label'       => esc_html__( 'Border', 'lastudio-kit' ),
					'placeholder' => '1px',
					'default'     => '1px',
					'selector'    => '{{WRAPPER}} .lakit-posts__thumbnail:after'
				)
			);

			$this->add_group_control(
				Group_Control_Box_Shadow::get_type(),
				array(
					'name'     => 'overlay_shadow',
					'selector' => '{{WRAPPER}} .lakit-posts__thumbnail:after'
				)
			);

			$this->end_controls_tab();

			$this->start_controls_tab(
				'tabs_overlay_hover',
				array(
					'label' => esc_html__( 'Hover', 'lastudio-kit' ),
				)
			);

			$this->add_group_control(
				Group_Control_Background::get_type(),
				array(
					'name'     => 'overlay_background_hover',
					'selector' => '{{WRAPPER}} .lakit-posts__inner-box:hover .lakit-posts__thumbnail:after'
				)
			);

			$this->add_control(
				'overlay_opacity_hover',
				array(
					'label'     => esc_html__( 'Opacity', 'lastudio-kit' ),
					'type'      => Controls_Manager::NUMBER,
					'default'   => 0.6,
					'min'       => 0,
					'max'       => 1,
					'step'      => 0.1,
					'selectors' => array(
						'{{WRAPPER}} .lakit-posts__inner-box:hover .lakit-posts__thumbnail:after' => 'opacity: {{VALUE}};'
					)
				)
			);
			$this->add_responsive_control(
				'overlay_position_hover',
				array(
					'label'      => __( 'Position', 'lastudio-kit' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => array( 'px', '%', 'em' ),
					'selectors'  => array(
						'{{WRAPPER}} .lakit-posts__inner-box:hover .lakit-posts__thumbnail:after' => 'top: {{TOP}}{{UNIT}}; right: {{RIGHT}}{{UNIT}}; bottom: {{BOTTOM}}{{UNIT}}; left: {{LEFT}}{{UNIT}};',
					),
				)
			);

			$this->add_responsive_control(
				'overlay_radius_hover',
				array(
					'label'      => esc_html__( 'Border Radius', 'lastudio-kit' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => array( 'px', '%', 'em' ),
					'selectors'  => array(
						'{{WRAPPER}} .lakit-posts__inner-box:hover .lakit-posts__thumbnail:after' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					),
				)
			);

			$this->add_group_control(
				Group_Control_Border::get_type(),
				array(
					'name'        => 'overlay_border_hover',
					'label'       => esc_html__( 'Border', 'lastudio-kit' ),
					'placeholder' => '1px',
					'default'     => '1px',
					'selector'    => '{{WRAPPER}} .lakit-posts__inner-box:hover .lakit-posts__thumbnail:after'
				)
			);

			$this->add_group_control(
				Group_Control_Box_Shadow::get_type(),
				array(
					'name'     => 'overlay_shadow_hover',
					'selector' => '{{WRAPPER}} .lakit-posts__inner-box:hover .lakit-posts__thumbnail:after'
				)
			);

			$this->end_controls_tab();

			$this->end_controls_tabs();

			$this->end_controls_section();

			$this->_start_controls_section(
				'section_content_style',
				array(
					'label'      => esc_html__( 'Item Content', 'lastudio-kit' ),
					'tab'        => Controls_Manager::TAB_STYLE,
					'show_label' => false,
				)
			);

			$this->_add_control(
				'show_content_hover',
				[
					'label'        => __( 'Show Content When Hover', 'lastudio-kit' ),
					'type'         => Controls_Manager::SWITCHER,
					'default'      => '',
					'condition'    => [
						'preset' => $this->condition_grid2(),
					],
					'prefix_class' => 'lakit--content-hover-',
				]
			);

			$this->_add_responsive_control(
				'content_padding',
				array(
					'label'      => esc_html__( 'Padding', 'lastudio-kit' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => array( 'px', '%', 'em' ),
					'selectors'  => array(
						'{{WRAPPER}} ' . $css_scheme['content'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						'{{WRAPPER}}'                           => '--lakit-posts-content-padding-top: {{TOP}}{{UNIT}};--lakit-posts-content-padding-right: {{RIGHT}}{{UNIT}};--lakit-posts-content-padding-bottom: {{BOTTOM}}{{UNIT}};--lakit-posts-content-padding-left: {{LEFT}}{{UNIT}};',
					),
				)
			);

			$this->_add_responsive_control(
				'content_margin',
				array(
					'label'      => esc_html__( 'Margin', 'lastudio-kit' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => array( 'px', '%', 'em', 'custom' ),
					'selectors'  => array(
						'{{WRAPPER}} ' . $css_scheme['content'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					)
				)
			);

			$this->_add_responsive_control(
				'content_radius',
				array(
					'label'      => esc_html__( 'Border Radius', 'lastudio-kit' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => array( 'px', '%', 'em' ),
					'selectors'  => array(
						'{{WRAPPER}} ' . $css_scheme['content'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					),
				)
			);

			$this->_start_controls_tabs( 'content_style_tabs' );
			$this->_start_controls_tab( 'content_normal',
				[
					'label' => __( 'Normal', 'lastudio-kit' ),
				]
			);
			$this->_add_group_control(
				Group_Control_Background::get_type(),
				array(
					'name'     => 'content_bg',
					'selector' => '{{WRAPPER}} ' . $css_scheme['content'],
				),
				25
			);

			$this->_add_group_control(
				Group_Control_Box_Shadow::get_type(),
				array(
					'name'     => 'content_shadow',
					'selector' => '{{WRAPPER}} ' . $css_scheme['content'],
				)
			);

			$this->_end_controls_tab();
			$this->_start_controls_tab( 'content_hover',
				[
					'label' => __( 'Hover', 'lastudio-kit' ),
				]
			);
			$this->_add_group_control(
				Group_Control_Background::get_type(),
				array(
					'name'     => 'content_bg_hover',
					'selector' => '{{WRAPPER}} .lakit-posts__outer-box:hover .lakit-posts__inner-content',
				),
				25
			);
			$this->_add_group_control(
				Group_Control_Box_Shadow::get_type(),
				array(
					'name'     => 'content_shadow_hover',
					'selector' => '{{WRAPPER}} .lakit-posts__inner-box:hover .lakit-posts__inner-content',
				)
			);

			$this->_end_controls_tab();
			$this->_end_controls_tabs();

			$this->_end_controls_section();

			$this->_register_section_style_content_inner( $css_scheme );

			$this->_start_controls_section(
				'section_title_style',
				array(
					'label'      => esc_html__( 'Title', 'lastudio-kit' ),
					'tab'        => Controls_Manager::TAB_STYLE,
					'show_label' => false,
				)
			);

			$this->_add_control(
				'title_bg',
				array(
					'label'     => esc_html__( 'Background Color', 'lastudio-kit' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'{{WRAPPER}} ' . $css_scheme['title'] => 'background-color: {{VALUE}}',
					),
				)
			);

            $this->_add_control(
                'title_color',
                array(
                    'label'     => esc_html__( 'Color', 'lastudio-kit' ),
                    'type'      => Controls_Manager::COLOR,
                    'selectors' => array(
                        '{{WRAPPER}} ' . $css_scheme['title'] => 'color: {{VALUE}}',
                    ),
                )
            );

            $this->_add_control(
                'title_color_hover',
                array(
                    'label'     => esc_html__( 'Hover Color', 'lastudio-kit' ),
                    'type'      => Controls_Manager::COLOR,
                    'selectors' => array(
                        '{{WRAPPER}} ' . $css_scheme['title'] . ':hover' => 'color: {{VALUE}}',
                    ),
                )
            );

//			$this->_start_controls_tabs( 'tabs_title_color' );
//
//			$this->_start_controls_tab(
//				'tab_title_color_normal',
//				array(
//					'label' => esc_html__( 'Normal', 'lastudio-kit' ),
//				)
//			);
//
//
//
//			$this->_end_controls_tab();
//
//			$this->_start_controls_tab(
//				'tab_title_color_hover',
//				array(
//					'label' => esc_html__( 'Hover', 'lastudio-kit' ),
//				)
//			);
//
//
//
//			$this->_end_controls_tab();
//
//			$this->_end_controls_tabs();

			$this->_add_group_control(
				Group_Control_Typography::get_type(),
				array(
					'name'     => 'title_typography',
					'selector' => '{{WRAPPER}} ' . $css_scheme['title'],
				)
			);

			$this->_add_responsive_control(
				'title_alignment',
				array(
					'label'     => esc_html__( 'Alignment', 'lastudio-kit' ),
					'type'      => Controls_Manager::CHOOSE,
					'options'   => array(
						'left'   => array(
							'title' => esc_html__( 'Left', 'lastudio-kit' ),
							'icon'  => 'eicon-h-align-left',
						),
						'center' => array(
							'title' => esc_html__( 'Center', 'lastudio-kit' ),
							'icon'  => 'eicon-h-align-center',
						),
						'right'  => array(
							'title' => esc_html__( 'Right', 'lastudio-kit' ),
							'icon'  => 'eicon-h-align-right',
						),
					),
					'selectors' => array(
						'{{WRAPPER}} ' . $css_scheme['title'] => 'text-align: {{VALUE}};',
					),
				)
			);

			$this->_add_responsive_control(
				'title_padding',
				array(
					'label'      => esc_html__( 'Padding', 'lastudio-kit' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => array( 'px', '%', 'em' ),
					'selectors'  => array(
						'{{WRAPPER}} ' . $css_scheme['title'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					),
				)
			);

			$this->_add_responsive_control(
				'title_margin',
				array(
					'label'      => esc_html__( 'Margin', 'lastudio-kit' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => array( 'px', '%', 'em', 'custom' ),
					'selectors'  => array(
						'{{WRAPPER}} ' . $css_scheme['title'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					),
				)
			);

			$this->_add_group_control(
				Group_Control_Border::get_type(),
				array(
					'name'        => 'title_border',
					'label'       => esc_html__( 'Border', 'lastudio-kit' ),
					'placeholder' => '1px',
					'default'     => '1px',
					'selector'    => '{{WRAPPER}} ' . $css_scheme['title']
				)
			);

			$this->_end_controls_section();

			$this->_start_controls_section(
				'section_excerpt_style',
				array(
					'label'      => esc_html__( 'Excerpt', 'lastudio-kit' ),
					'tab'        => Controls_Manager::TAB_STYLE,
					'show_label' => false,
					'condition'  => [
						'show_excerpt' => 'true'
					]
				)
			);
			$this->_add_responsive_control(
				'excerpt_width',
				array(
					'label'      => esc_html__( 'Width', 'lastudio-kit' ),
					'type'       => Controls_Manager::SLIDER,
					'size_units' => [ 'px', '%' ],
					'selectors'  => array(
						'{{WRAPPER}} ' . $css_scheme['excerpt'] => 'width: {{SIZE}}{{UNIT}};',
					),
				)
			);

			$this->_add_control(
				'excerpt_bg',
				array(
					'label'     => esc_html__( 'Background Color', 'lastudio-kit' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'{{WRAPPER}} ' . $css_scheme['excerpt'] => 'background-color: {{VALUE}}',
					),
				)
			);

			$this->_add_control(
				'excerpt_color',
				array(
					'label'     => esc_html__( 'Color', 'lastudio-kit' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'{{WRAPPER}} ' . $css_scheme['excerpt'] => 'color: {{VALUE}}',
					),
				)
			);

			$this->_add_group_control(
				Group_Control_Typography::get_type(),
				array(
					'name'     => 'excerpt_typography',
					'selector' => '{{WRAPPER}} ' . $css_scheme['excerpt'],
				)
			);

			$this->_add_responsive_control(
				'excerpt_alignment',
				array(
					'label'     => esc_html__( 'Alignment', 'lastudio-kit' ),
					'type'      => Controls_Manager::CHOOSE,
					'options'   => array(
						'left'   => array(
							'title' => esc_html__( 'Left', 'lastudio-kit' ),
							'icon'  => 'eicon-h-align-left',
						),
						'center' => array(
							'title' => esc_html__( 'Center', 'lastudio-kit' ),
							'icon'  => 'eicon-h-align-center',
						),
						'right'  => array(
							'title' => esc_html__( 'Right', 'lastudio-kit' ),
							'icon'  => 'eicon-h-align-right',
						),
					),
					'selectors' => array(
						'{{WRAPPER}} ' . $css_scheme['excerpt'] => 'text-align: {{VALUE}};',
					),
				)
			);

			$this->_add_responsive_control(
				'excerpt_padding',
				array(
					'label'      => esc_html__( 'Padding', 'lastudio-kit' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => array( 'px', '%', 'em' ),
					'selectors'  => array(
						'{{WRAPPER}} ' . $css_scheme['excerpt'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					),
				)
			);

			$this->_add_responsive_control(
				'excerpt_margin',
				array(
					'label'      => esc_html__( 'Margin', 'lastudio-kit' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => array( 'px', '%', 'em', 'custom' ),
					'selectors'  => array(
						'{{WRAPPER}} ' . $css_scheme['excerpt'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					),
				)
			);

			$this->_add_group_control(
				Group_Control_Border::get_type(),
				array(
					'name'        => 'excerpt_border',
					'label'       => esc_html__( 'Border', 'lastudio-kit' ),
					'placeholder' => '1px',
					'default'     => '1px',
					'selector'    => '{{WRAPPER}} ' . $css_scheme['excerpt']
				)
			);

			$this->_end_controls_section();

			$this->_start_controls_section(
				'section_button_style',
				array(
					'label'      => esc_html__( 'Button', 'lastudio-kit' ),
					'tab'        => Controls_Manager::TAB_STYLE,
					'show_label' => false,
				)
			);
			$this->_add_control(
				'show_btn_hover',
				[
					'label'        => __( 'Show Button When Hover', 'lastudio-kit' ),
					'type'         => Controls_Manager::SWITCHER,
					'default'      => '',
					'prefix_class' => 'lakit--button-hover-',
				]
			);

			$this->_add_control(
				'button_icon_position',
				array(
					'label'     => esc_html__( 'Icon Position', 'lastudio-kit' ),
					'type'      => Controls_Manager::SELECT,
					'options'   => array(
						'row-reverse' => esc_html__( 'Before Text', 'lastudio-kit' ),
						'row'         => esc_html__( 'After Text', 'lastudio-kit' ),
					),
					'default'   => 'row',
					'selectors' => array(
						'{{WRAPPER}} ' . $css_scheme['button'] => 'flex-direction: {{VALUE}}',
					),
				)
			);

			$this->_start_controls_tabs( 'tabs_button_style' );

			$this->_start_controls_tab(
				'tab_button_normal',
				array(
					'label' => esc_html__( 'Normal', 'lastudio-kit' ),
				)
			);
			$this->_add_group_control(
				Group_Control_Background::get_type(),
				array(
					'name'     => 'button_bg',
					'types'    => [ 'classic', 'gradient' ],
					'selector' => '{{WRAPPER}} ' . $css_scheme['button'],
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
				'button_color',
				array(
					'label'     => esc_html__( 'Text Color', 'lastudio-kit' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'{{WRAPPER}} ' . $css_scheme['button'] => 'color: {{VALUE}}',
					)
				)
			);


			$this->_add_group_control(
				Group_Control_Typography::get_type(),
				array(
					'name'     => 'button_typography',
					'selector' => '{{WRAPPER}}  ' . $css_scheme['button'],
				)
			);

			$this->_add_responsive_control(
				'button_icon_size',
				array(
					'label'     => esc_html__( 'Icon Size', 'lastudio-kit' ),
					'type'      => Controls_Manager::SLIDER,
					'selectors' => array(
						'{{WRAPPER}} ' . $css_scheme['button_icon'] => 'font-size: {{SIZE}}{{UNIT}};',
					),
				)
			);

			$this->_add_control(
				'button_icon_color',
				array(
					'label'     => esc_html__( 'Icon Color', 'lastudio-kit' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'{{WRAPPER}} ' . $css_scheme['button_icon'] => 'color: {{VALUE}}',
					),
				)
			);

			$this->_add_responsive_control(
				'button_icon_margin',
				array(
					'label'      => esc_html__( 'Icon Margin', 'lastudio-kit' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => array( 'px', '%', 'em' ),
					'selectors'  => array(
						'{{WRAPPER}} ' . $css_scheme['button_icon'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					),
				)
			);

			$this->_add_responsive_control(
				'button_padding',
				array(
					'label'      => esc_html__( 'Padding', 'lastudio-kit' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => array( 'px', '%', 'em' ),
					'selectors'  => array(
						'{{WRAPPER}} ' . $css_scheme['button'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					),
				)
			);

			$this->_add_responsive_control(
				'button_margin',
				array(
					'label'      => esc_html__( 'Margin', 'lastudio-kit' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => array( 'px', '%', 'em', 'custom' ),
					'selectors'  => array(
						'{{WRAPPER}} ' . $css_scheme['button'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					),
				)
			);

			$this->_add_responsive_control(
				'button_border_radius',
				array(
					'label'      => esc_html__( 'Border Radius', 'lastudio-kit' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => array( 'px', '%' ),
					'selectors'  => array(
						'{{WRAPPER}} ' . $css_scheme['button'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					),
				)
			);

			$this->_add_group_control(
				Group_Control_Border::get_type(),
				array(
					'name'        => 'button_border',
					'label'       => esc_html__( 'Border', 'lastudio-kit' ),
					'placeholder' => '1px',
					'default'     => '1px',
					'selector'    => '{{WRAPPER}} ' . $css_scheme['button'],
				)
			);

			$this->_add_group_control(
				Group_Control_Box_Shadow::get_type(),
				array(
					'name'     => 'button_box_shadow',
					'selector' => '{{WRAPPER}} ' . $css_scheme['button'],
				)
			);

			$this->_end_controls_tab();

			$this->_start_controls_tab(
				'tab_button_hover',
				array(
					'label' => esc_html__( 'Hover', 'lastudio-kit' ),
				)
			);

			$this->_add_group_control(
				Group_Control_Background::get_type(),
				array(
					'name'     => 'button_hover_bg',
					'types'    => [ 'classic', 'gradient' ],
					'selector' => '{{WRAPPER}} ' . $css_scheme['button'] . ':hover',
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
				'button_hover_color',
				array(
					'label'     => esc_html__( 'Text Color', 'lastudio-kit' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'{{WRAPPER}} ' . $css_scheme['button'] . ':hover' => 'color: {{VALUE}}',
					),
				)
			);

			$this->_add_group_control(
				Group_Control_Typography::get_type(),
				array(
					'name'     => 'button_hover_typography',
					'label'    => esc_html__( 'Typography', 'lastudio-kit' ),
					'selector' => '{{WRAPPER}}  ' . $css_scheme['button'] . ':hover',
				)
			);

			$this->_add_control(
				'button_hover_text_decor',
				array(
					'label'     => esc_html__( 'Text Decoration', 'lastudio-kit' ),
					'type'      => Controls_Manager::SELECT,
					'options'   => array(
						'none'      => esc_html__( 'None', 'lastudio-kit' ),
						'underline' => esc_html__( 'Underline', 'lastudio-kit' ),
					),
					'default'   => 'none',
					'selectors' => array(
						'{{WRAPPER}} ' . $css_scheme['button'] . ':hover' => 'text-decoration: {{VALUE}}',
					),
				)
			);

			$this->_add_responsive_control(
				'button_icon_size_hover',
				array(
					'label'     => esc_html__( 'Icon Size', 'lastudio-kit' ),
					'type'      => Controls_Manager::SLIDER,
					'selectors' => array(
						'{{WRAPPER}} ' . $css_scheme['button'] . ':hover .lakit-btn-more-icon' => 'font-size: {{SIZE}}{{UNIT}};',
					),
				)
			);

			$this->_add_control(
				'button_icon_color_hover',
				array(
					'label'     => esc_html__( 'Icon Color', 'lastudio-kit' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'{{WRAPPER}} ' . $css_scheme['button'] . ':hover .lakit-btn-more-icon' => 'color: {{VALUE}}',
					),
				)
			);

			$this->_add_responsive_control(
				'button_icon_margin_hover',
				array(
					'label'      => esc_html__( 'Icon Margin', 'lastudio-kit' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => array( 'px', '%', 'em' ),
					'selectors'  => array(
						'{{WRAPPER}} ' . $css_scheme['button'] . ':hover .lakit-btn-more-icon' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					),
				)
			);

			$this->_add_responsive_control(
				'button_hover_padding',
				array(
					'label'      => esc_html__( 'Padding', 'lastudio-kit' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => array( 'px', '%', 'em' ),
					'selectors'  => array(
						'{{WRAPPER}} ' . $css_scheme['button'] . ':hover' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					),
				)
			);

			$this->_add_responsive_control(
				'button_hover_margin',
				array(
					'label'      => esc_html__( 'Margin', 'lastudio-kit' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => array( 'px', '%', 'em' ),
					'selectors'  => array(
						'{{WRAPPER}} ' . $css_scheme['button'] . ':hover' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					),
				)
			);

			$this->_add_responsive_control(
				'button_hover_border_radius',
				array(
					'label'      => esc_html__( 'Border Radius', 'lastudio-kit' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => array( 'px', '%' ),
					'selectors'  => array(
						'{{WRAPPER}} ' . $css_scheme['button'] . ':hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					),
				)
			);

			$this->_add_group_control(
				Group_Control_Border::get_type(),
				array(
					'name'        => 'button_hover_border',
					'label'       => esc_html__( 'Border', 'lastudio-kit' ),
					'placeholder' => '1px',
					'default'     => '1px',
					'selector'    => '{{WRAPPER}} ' . $css_scheme['button'] . ':hover',
				)
			);

			$this->_add_group_control(
				Group_Control_Box_Shadow::get_type(),
				array(
					'name'     => 'button_hover_box_shadow',
					'selector' => '{{WRAPPER}} ' . $css_scheme['button'] . ':hover',
				)
			);

			$this->_end_controls_tab();

			$this->_end_controls_tabs();

			$this->_add_responsive_control(
				'button_alignment',
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

		protected function _register_section_style_meta( $css_scheme ) {
			$this->_start_controls_section(
				'section_meta1',
				array(
					'label'     => esc_html__( 'Meta 1', 'lastudio-kit' ),
					'tab'       => Controls_Manager::TAB_STYLE,
					'condition' => [
						'show_meta' => 'yes'
					],
				)
			);

			$this->_add_control(
				'meta1_bg',
				array(
					'label'     => esc_html__( 'Background Color', 'lastudio-kit' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'{{WRAPPER}} ' . $css_scheme['meta1'] => 'background-color: {{VALUE}}',
					),
				)
			);

			$this->_add_control(
				'meta1_color',
				array(
					'label'     => esc_html__( 'Text Color', 'lastudio-kit' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'{{WRAPPER}} ' . $css_scheme['meta1'] => 'color: {{VALUE}}',
					),
				)
			);

			$this->_add_control(
				'meta1_link_color',
				array(
					'label'     => esc_html__( 'Links Color', 'lastudio-kit' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'{{WRAPPER}} ' . $css_scheme['meta1'] . ' a' => 'color: {{VALUE}}',
					),
				)
			);

			$this->_add_control(
				'meta1_link_color_hover',
				array(
					'label'     => esc_html__( 'Links Hover Color', 'lastudio-kit' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'{{WRAPPER}} ' . $css_scheme['meta1'] . ' a:hover' => 'color: {{VALUE}}',
					),
				)
			);

			$this->_add_group_control(
				Group_Control_Typography::get_type(),
				array(
					'name'     => 'meta1_typography',
					'selector' => '{{WRAPPER}} ' . $css_scheme['meta1'],
				)
			);

			$this->_add_responsive_control(
				'meta1_padding',
				array(
					'label'      => esc_html__( 'Padding', 'lastudio-kit' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => array( 'px', '%', 'em' ),
					'selectors'  => array(
						'{{WRAPPER}} ' . $css_scheme['meta1'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					),
				)
			);

			$this->_add_responsive_control(
				'meta1_margin',
				array(
					'label'      => esc_html__( 'Margin', 'lastudio-kit' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => array( 'px', '%', 'em' ),
					'selectors'  => array(
						'{{WRAPPER}} ' . $css_scheme['meta1'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					),
				)
			);

			$this->_add_responsive_control(
				'meta1_alignment',
				array(
					'label'     => esc_html__( 'Alignment', 'lastudio-kit' ),
					'type'      => Controls_Manager::CHOOSE,
					'options'   => array(
						'flex-start'    => array(
							'title' => esc_html__( 'Left', 'lastudio-kit' ),
							'icon'  => 'eicon-h-align-left',
						),
						'center'        => array(
							'title' => esc_html__( 'Center', 'lastudio-kit' ),
							'icon'  => 'eicon-h-align-center',
						),
						'flex-end'      => array(
							'title' => esc_html__( 'Right', 'lastudio-kit' ),
							'icon'  => 'eicon-h-align-right',
						),
						'space-between' => array(
							'title' => esc_html__( 'Stretch', 'lastudio-kit' ),
							'icon'  => 'eicon-h-align-stretch',
						)
					),
					'selectors' => array(
						'{{WRAPPER}} ' . $css_scheme['meta1'] => 'justify-content: {{VALUE}};',
					)
				)
			);

			$this->_add_responsive_control(
				'meta1_text_alignment',
				array(
					'label'     => esc_html__( 'Text Alignment', 'lastudio-kit' ),
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
						),
					),
					'selectors' => array(
						'{{WRAPPER}} ' . $css_scheme['meta1'] => 'text-align: {{VALUE}};',
					),
				)
			);

			$this->_add_control(
				'meta1_divider',
				array(
					'label'     => esc_html__( 'Meta Divider', 'lastudio-kit' ),
					'type'      => Controls_Manager::TEXT,
					'default'   => '',
					'selectors' => array(
						'{{WRAPPER}} ' . $css_scheme['meta1-item'] . ':not(:first-child):before' => 'content: "{{VALUE}}";',
					),
				)
			);

			$this->_add_responsive_control(
				'meta1_divider_gap',
				array(
					'label'      => esc_html__( 'Divider Gap', 'lastudio-kit' ),
					'type'       => Controls_Manager::SLIDER,
					'size_units' => array( 'px' ),
					'range'      => array(
						'px' => array(
							'min' => 0,
							'max' => 90,
						),
					),
					'selectors'  => array(
						'{{WRAPPER}} ' . $css_scheme['meta1-item'] . ':not(:first-child):before' => 'margin-left: {{SIZE}}{{UNIT}};margin-right: {{SIZE}}{{UNIT}};',
					),
				)
			);

			$this->_add_responsive_control(
				'meta1_icon_size',
				array(
					'label'      => esc_html__( 'Icon Size', 'lastudio-kit' ),
					'type'       => Controls_Manager::SLIDER,
					'size_units' => array( 'px', 'em' ),
					'selectors'  => array(
						'{{WRAPPER}} ' . $css_scheme['meta1-item'] . ' .meta--icon' => 'font-size: {{SIZE}}{{UNIT}};',
					),
				)
			);
			$this->_add_responsive_control(
				'meta1_icon_spacing',
				array(
					'label'      => esc_html__( 'Icon Spacing', 'lastudio-kit' ),
					'type'       => Controls_Manager::SLIDER,
					'size_units' => array( 'px', 'em' ),
					'selectors'  => array(
						'{{WRAPPER}} ' . $css_scheme['meta1-item'] . ' .meta--icon' => 'margin-right: {{SIZE}}{{UNIT}};',
					),
				)
			);

            $this->_add_responsive_control(
                'meta1_radius',
                array(
                    'label'      => esc_html__( 'Border Radius', 'lastudio-kit' ),
                    'type'       => Controls_Manager::DIMENSIONS,
                    'size_units' => array( 'px', '%', 'em' ),
                    'selectors'  => array(
                        '{{WRAPPER}} ' . $css_scheme['meta1'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ),
                )
            );

			$this->_add_group_control(
				Group_Control_Border::get_type(),
				array(
					'name'        => 'meta1_border',
					'label'       => esc_html__( 'Border', 'lastudio-kit' ),
					'placeholder' => '1px',
					'default'     => '1px',
					'selector'    => '{{WRAPPER}} ' . $css_scheme['meta1'],
				)
			);

			$this->_end_controls_section();

			$this->_start_controls_section(
				'section_meta2',
				array(
					'label'     => esc_html__( 'Meta 2', 'lastudio-kit' ),
					'tab'       => Controls_Manager::TAB_STYLE,
					'condition' => [
						'show_meta' => 'yes'
					],
				)
			);

			$this->_add_control(
				'meta2_bg',
				array(
					'label'     => esc_html__( 'Background Color', 'lastudio-kit' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'{{WRAPPER}} ' . $css_scheme['meta2'] => 'background-color: {{VALUE}}',
					),
				)
			);

			$this->_add_control(
				'meta2_color',
				array(
					'label'     => esc_html__( 'Text Color', 'lastudio-kit' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'{{WRAPPER}} ' . $css_scheme['meta2'] => 'color: {{VALUE}}',
					),
				)
			);

			$this->_add_control(
				'meta2_link_color',
				array(
					'label'     => esc_html__( 'Links Color', 'lastudio-kit' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'{{WRAPPER}} ' . $css_scheme['meta2'] . ' a' => 'color: {{VALUE}}',
					),
				)
			);

			$this->_add_control(
				'meta2_link_color_hover',
				array(
					'label'     => esc_html__( 'Links Hover Color', 'lastudio-kit' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'{{WRAPPER}} ' . $css_scheme['meta2'] . ' a:hover' => 'color: {{VALUE}}',
					),
				)
			);

			$this->_add_group_control(
				Group_Control_Typography::get_type(),
				array(
					'name'     => 'meta2_typography',
					'selector' => '{{WRAPPER}} ' . $css_scheme['meta2'],
				)
			);

			$this->_add_responsive_control(
				'meta2_padding',
				array(
					'label'      => esc_html__( 'Padding', 'lastudio-kit' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => array( 'px', '%', 'em' ),
					'selectors'  => array(
						'{{WRAPPER}} ' . $css_scheme['meta2'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					),
				)
			);

			$this->_add_responsive_control(
				'meta2_margin',
				array(
					'label'      => esc_html__( 'Margin', 'lastudio-kit' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => array( 'px', '%', 'em' ),
					'selectors'  => array(
						'{{WRAPPER}} ' . $css_scheme['meta2'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					),
				)
			);

			$this->_add_responsive_control(
				'meta2_alignment',
				array(
					'label'     => esc_html__( 'Alignment', 'lastudio-kit' ),
					'type'      => Controls_Manager::CHOOSE,
					'options'   => array(
						'flex-start'    => array(
							'title' => esc_html__( 'Left', 'lastudio-kit' ),
							'icon'  => 'eicon-h-align-left',
						),
						'center'        => array(
							'title' => esc_html__( 'Center', 'lastudio-kit' ),
							'icon'  => 'eicon-h-align-center',
						),
						'flex-end'      => array(
							'title' => esc_html__( 'Right', 'lastudio-kit' ),
							'icon'  => 'eicon-h-align-right',
						),
						'space-between' => array(
							'title' => esc_html__( 'Stretch', 'lastudio-kit' ),
							'icon'  => 'eicon-h-align-stretch',
						)
					),
					'selectors' => array(
						'{{WRAPPER}} ' . $css_scheme['meta2'] => 'justify-content: {{VALUE}};',
					)
				)
			);

			$this->_add_responsive_control(
				'meta2_text_alignment',
				array(
					'label'     => esc_html__( 'Text Alignment', 'lastudio-kit' ),
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
						),
					),
					'selectors' => array(
						'{{WRAPPER}} ' . $css_scheme['meta2'] => 'text-align: {{VALUE}};',
					),
				)
			);

			$this->_add_control(
				'meta2_divider',
				array(
					'label'     => esc_html__( 'Meta Divider', 'lastudio-kit' ),
					'type'      => Controls_Manager::TEXT,
					'default'   => '',
					'selectors' => array(
						'{{WRAPPER}} ' . $css_scheme['meta2-item'] . ':not(:first-child):before' => 'content: "{{VALUE}}";',
					),
				)
			);

			$this->_add_responsive_control(
				'meta2_divider_gap',
				array(
					'label'      => esc_html__( 'Divider Gap', 'lastudio-kit' ),
					'type'       => Controls_Manager::SLIDER,
					'size_units' => array( 'px' ),
					'range'      => array(
						'px' => array(
							'min' => 0,
							'max' => 90,
						),
					),
					'selectors'  => array(
						'{{WRAPPER}} ' . $css_scheme['meta2-item'] . ':not(:first-child):before' => 'margin-left: {{SIZE}}{{UNIT}};margin-right: {{SIZE}}{{UNIT}};',
					),
				)
			);
			$this->_add_responsive_control(
				'meta2_icon_size',
				array(
					'label'      => esc_html__( 'Icon Size', 'lastudio-kit' ),
					'type'       => Controls_Manager::SLIDER,
					'size_units' => array( 'px', 'em' ),
					'selectors'  => array(
						'{{WRAPPER}} ' . $css_scheme['meta2-item'] . ' .meta--icon' => 'font-size: {{SIZE}}{{UNIT}};',
					),
				)
			);
			$this->_add_responsive_control(
				'meta2_icon_spacing',
				array(
					'label'      => esc_html__( 'Icon Spacing', 'lastudio-kit' ),
					'type'       => Controls_Manager::SLIDER,
					'size_units' => array( 'px', 'em' ),
					'selectors'  => array(
						'{{WRAPPER}} ' . $css_scheme['meta2-item'] . ' .meta--icon' => 'margin-right: {{SIZE}}{{UNIT}};',
					),
				)
			);

            $this->_add_responsive_control(
                'meta2_radius',
                array(
                    'label'      => esc_html__( 'Border Radius', 'lastudio-kit' ),
                    'type'       => Controls_Manager::DIMENSIONS,
                    'size_units' => array( 'px', '%', 'em' ),
                    'selectors'  => array(
                        '{{WRAPPER}} ' . $css_scheme['meta2'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ),
                )
            );

			$this->_add_group_control(
				Group_Control_Border::get_type(),
				array(
					'name'        => 'meta2_border',
					'label'       => esc_html__( 'Border', 'lastudio-kit' ),
					'placeholder' => '1px',
					'default'     => '1px',
					'selector'    => '{{WRAPPER}} ' . $css_scheme['meta2'],
				)
			);

			$this->_end_controls_section();

			$this->_start_controls_section(
				'section_notfoundmsg_style',
				array(
					'label'      => esc_html__( 'Not Found Message', 'lastudio-kit' ),
					'tab'        => Controls_Manager::TAB_STYLE,
					'show_label' => false
				)
			);


			$this->_add_control(
				'notfoundmsg_bg',
				array(
					'label'     => esc_html__( 'Background Color', 'lastudio-kit' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'{{WRAPPER}} ' . $css_scheme['notfoundmsg'] => 'background-color: {{VALUE}}',
					),
				)
			);

			$this->_add_control(
				'notfoundmsg_color',
				array(
					'label'     => esc_html__( 'Color', 'lastudio-kit' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'{{WRAPPER}} ' . $css_scheme['notfoundmsg'] => 'color: {{VALUE}}',
					),
				)
			);

			$this->_add_group_control(
				Group_Control_Typography::get_type(),
				array(
					'name'     => 'notfoundmsg_typography',
					'selector' => '{{WRAPPER}} ' . $css_scheme['notfoundmsg'],
				)
			);

			$this->_add_responsive_control(
				'notfoundmsg_alignment',
				array(
					'label'     => esc_html__( 'Alignment', 'lastudio-kit' ),
					'type'      => Controls_Manager::CHOOSE,
					'options'   => array(
						'left'   => array(
							'title' => esc_html__( 'Left', 'lastudio-kit' ),
							'icon'  => 'eicon-h-align-left',
						),
						'center' => array(
							'title' => esc_html__( 'Center', 'lastudio-kit' ),
							'icon'  => 'eicon-h-align-center',
						),
						'right'  => array(
							'title' => esc_html__( 'Right', 'lastudio-kit' ),
							'icon'  => 'eicon-h-align-right',
						),
					),
					'selectors' => array(
						'{{WRAPPER}} ' . $css_scheme['notfoundmsg'] => 'text-align: {{VALUE}};',
					),
				)
			);

			$this->_add_responsive_control(
				'notfoundmsg_padding',
				array(
					'label'      => esc_html__( 'Padding', 'lastudio-kit' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => array( 'px', '%', 'em' ),
					'selectors'  => array(
						'{{WRAPPER}} ' . $css_scheme['notfoundmsg'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					),
				)
			);

			$this->_add_responsive_control(
				'notfoundmsg_margin',
				array(
					'label'      => esc_html__( 'Margin', 'lastudio-kit' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => array( 'px', '%', 'em' ),
					'selectors'  => array(
						'{{WRAPPER}} ' . $css_scheme['notfoundmsg'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					),
				)
			);

			$this->_end_controls_section();
		}

		protected function _register_section_style_pagination( $css_scheme ) {
			/**
			 * Pagination section
			 */
			$this->_start_controls_section(
				'section_pagination_style',
				[
					'label'     => __( 'Pagination', 'lastudio-kit' ),
					'tab'       => Controls_Manager::TAB_STYLE,
					'condition' => [
						'paginate' => 'yes',
					],
				]
			);

			$this->_add_responsive_control(
				'pagination_align',
				[
					'label'     => __( 'Alignment', 'lastudio-kit' ),
					'type'      => Controls_Manager::CHOOSE,
					'options'   => [
						'left'   => [
							'title' => __( 'Left', 'lastudio-kit' ),
							'icon'  => 'eicon-h-align-left',
						],
						'center' => [
							'title' => __( 'Center', 'lastudio-kit' ),
							'icon'  => 'eicon-h-align-center',
						],
						'right'  => [
							'title' => __( 'Right', 'lastudio-kit' ),
							'icon'  => 'eicon-h-align-right',
						],
					],
					'selectors' => [
						'{{WRAPPER}} .lakit-pagination' => 'text-align: {{VALUE}}'
					]
				]
			);

			$this->_add_responsive_control(
				'pagination_spacing',
				[
					'label'      => __( 'Margin', 'lastudio-kit' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => array( 'px', '%', 'em', 'custom' ),
					'selectors'  => [
						'{{WRAPPER}} .lakit-pagination' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);

			$this->_add_control(
				'show_pagination_border',
				[
					'label'        => __( 'Border', 'lastudio-kit' ),
					'type'         => Controls_Manager::SWITCHER,
					'label_off'    => __( 'Hide', 'lastudio-kit' ),
					'label_on'     => __( 'Show', 'lastudio-kit' ),
					'default'      => 'yes',
					'return_value' => 'yes',
                    'prefix_class' => 'lakit-pagination-has-border-',
				]
			);

			$this->_add_control(
				'pagination_border_color',
				[
					'label'     => __( 'Border Color', 'lastudio-kit' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .lakit-pagination' => '--lakit-pagination-border-color: {{VALUE}}',
					],
					'condition' => [
						'show_pagination_border' => 'yes',
					],
				]
			);

			$this->_add_responsive_control(
				'pagination_item_width',
				[
					'label'     => __( 'Item Width', 'lastudio-kit' ),
					'type'      => Controls_Manager::SLIDER,
					'selectors' => [
						'{{WRAPPER}} .lakit-pagination' => '--lakit-pagination-item-width: {{SIZE}}{{UNIT}}',
					],
				]
			);

			$this->_add_responsive_control(
				'pagination_item_spacing',
				[
					'label'     => __( 'Item Spacing', 'lastudio-kit' ),
					'type'      => Controls_Manager::SLIDER,
					'selectors' => [
						'{{WRAPPER}} .lakit-pagination' => '--lakit-pagination-item-spacing: {{SIZE}}{{UNIT}}',
					],
				]
			);

			$this->_add_responsive_control(
				'pagination_item_radius',
				[
					'label'      => __( 'Border Radius', 'lastudio-kit' ),
					'type'       => Controls_Manager::SLIDER,
					'size_units' => array( 'px', '%', 'em' ),
					'selectors'  => [
						'{{WRAPPER}} .lakit-pagination'               => '--lakit-pagination-radius: {{SIZE}}{{UNIT}}',
						'{{WRAPPER}} .lakit-pagination .page-numbers' => 'border-radius: {{SIZE}}{{UNIT}}',
					],
				]
			);

			$this->_add_responsive_control(
				'pagination_padding',
				[
					'label'      => __( 'Padding', 'lastudio-kit' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => array( 'px', '%', 'em' ),
					'selectors'  => [
						'{{WRAPPER}} .lakit-pagination' => '--lakit-pagination-padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);

			$this->_add_group_control(
				Group_Control_Typography::get_type(),
				[
					'name'     => 'pagination_typography',
					'selector' => '{{WRAPPER}} .lakit-pagination',
				]
			);

			$this->_start_controls_tabs( 'pagination_style_tabs' );

			$this->_start_controls_tab( 'pagination_style_normal',
				[
					'label' => __( 'Normal', 'lastudio-kit' ),
				]
			);

			$this->_add_control(
				'pagination_link_color',
				[
					'label'     => __( 'Color', 'lastudio-kit' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .lakit-pagination' => '--lakit-pagination-link-color: {{VALUE}}',
					],
				]
			);
            $this->_add_group_control(
                Group_Control_Background::get_type(),
                array(
                    'name'     => 'pagination_link_bkg',
                    'selector' => '{{WRAPPER}} .lakit-pagination_ajax_loadmore a',
                    'condition' => [
                        'paginate_as_loadmore' => 'yes',
                    ],
                ),
                25
            );

			$this->_add_control(
				'pagination_link_bg_color',
				[
					'label'     => __( 'Background Color', 'lastudio-kit' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .lakit-pagination' => '--lakit-pagination-link-bg-color: {{VALUE}}',
					],
                    'condition' => [
                        'paginate_as_loadmore!' => 'yes',
                    ],
				]
			);

			$this->_end_controls_tab();

			$this->_start_controls_tab( 'pagination_style_hover',
				[
					'label' => __( 'Active', 'lastudio-kit' ),
				]
			);

			$this->_add_control(
				'pagination_link_color_hover',
				[
					'label'     => __( 'Color', 'lastudio-kit' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .lakit-pagination' => '--lakit-pagination-link-hover-color: {{VALUE}}',
					],
				]
			);

            $this->_add_group_control(
                Group_Control_Background::get_type(),
                array(
                    'name'     => 'pagination_link_bkg_hover',
                    'selector' => '{{WRAPPER}} .lakit-pagination_ajax_loadmore a:hover',
                    'condition' => [
                        'paginate_as_loadmore' => 'yes',
                    ],
                ),
                25
            );

			$this->_add_control(
				'pagination_link_bg_color_hover',
				[
					'label'     => __( 'Background Color', 'lastudio-kit' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .lakit-pagination' => '--lakit-pagination-link-hover-bg-color: {{VALUE}}',
					],
                    'condition' => [
                        'paginate_as_loadmore!' => 'yes',
                    ],
				]
			);

			$this->_end_controls_tab();

			$this->_end_controls_tabs();

			$this->_end_controls_section();
		}

		protected function _register_section_style_floating_date( $css_scheme ) {
			/** Floating date **/
			$this->_start_controls_section(
				'section_floating_date',
				array(
					'label'     => esc_html__( 'Floating Date', 'lastudio-kit' ),
					'tab'       => Controls_Manager::TAB_STYLE,
					'condition' => [
						'floating_date' => 'yes'
					]
				)
			);

			$this->_add_control(
				'floating_date_bgcolor',
				array(
					'label'     => esc_html__( 'Background Color', 'lastudio-kit' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'{{WRAPPER}} .lakit-posts__floating_date' => 'background-color: {{VALUE}}',
					),
				)
			);

			$this->_add_control(
				'floating_date_color',
				array(
					'label'     => esc_html__( 'Color', 'lastudio-kit' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'{{WRAPPER}} .lakit-posts__floating_date' => 'color: {{VALUE}}',
					),
				)
			);

			$this->_add_group_control(
				Group_Control_Typography::get_type(),
				array(
					'name'     => 'floating_date_typography',
					'selector' => '{{WRAPPER}} .lakit-posts__floating_date',
				)
			);
			$this->_add_responsive_control(
				'floating_date_width',
				array(
					'label'      => esc_html__( 'Width', 'lastudio-kit' ),
					'type'       => Controls_Manager::SLIDER,
					'size_units' => array( 'px', '%', 'custom' ),
					'selectors'  => array(
						'{{WRAPPER}} .lakit-posts__floating_date' => 'width: {{SIZE}}{{UNIT}};',
					),
				)
			);
			$this->_add_responsive_control(
				'floating_date_height',
				array(
					'label'      => esc_html__( 'Height', 'lastudio-kit' ),
					'type'       => Controls_Manager::SLIDER,
					'size_units' => array( 'px', '%', 'custom' ),
					'selectors'  => array(
						'{{WRAPPER}} .lakit-posts__floating_date' => 'height: {{SIZE}}{{UNIT}};',
					),
				)
			);
			$this->_add_responsive_control(
				'floating_date_border',
				array(
					'label'      => esc_html__( 'Border Radius', 'lastudio-kit' ),
					'type'       => Controls_Manager::SLIDER,
					'size_units' => array( 'px', '%', 'em' ),
					'selectors'  => array(
						'{{WRAPPER}} .lakit-posts__floating_date' => 'border-radius: {{SIZE}}{{UNIT}};',
					),
				)
			);
			$this->_add_control(
				'floating_date__heading',
				[
					'label'       => esc_html__( 'Position', 'lastudio-kit' ),
					'type'        => Controls_Manager::HEADING,
					'label_block' => true,
					'separator'   => 'before',
				]
			);

			$this->_add_control(
				'floating_date__horizontal',
				[
					'label' => esc_html__( 'Horizontal Orientation', 'lastudio-kit' ),
					'type' => Controls_Manager::CHOOSE,
					'default' => is_rtl() ? 'right' : 'left',
					'options' => [
						'left' => [
							'title' => esc_html__( 'Left', 'lastudio-kit' ),
							'icon' => 'eicon-h-align-left',
						],
						'right' => [
							'title' => esc_html__( 'Right', 'lastudio-kit' ),
							'icon' => 'eicon-h-align-right',
						],
					],
					'toggle' => false
				]
			);
			$this->_add_responsive_control(
				'floating_date_left_position',
				array(
					'label'      => esc_html__( 'Offset X', 'lastudio-kit' ),
					'type'       => Controls_Manager::SLIDER,
					'size_units' => array( 'px', '%','custom' ),
					'default'    => [
						'unit' => 'px',
						'size' => '10',
					],
					'selectors'  => array(
						'{{WRAPPER}} .lakit-posts__floating_date' => '{{floating_date__horizontal.VALUE}}: {{SIZE}}{{UNIT}};',
					),
				)
			);

			$this->_add_control(
				'floating_date__vertical',
				[
					'label' => esc_html__( 'Vertical Orientation', 'lastudio-kit' ),
					'type' => Controls_Manager::CHOOSE,
					'options' => [
						'top' => [
							'title' => esc_html__( 'Top', 'lastudio-kit' ),
							'icon' => 'eicon-v-align-top',
						],
						'bottom' => [
							'title' => esc_html__( 'Bottom', 'lastudio-kit' ),
							'icon' => 'eicon-v-align-bottom',
						],
					],
					'default' => 'top',
					'toggle' => false,
				]
			);

			$this->_add_responsive_control(
				'floating_date_top_position',
				array(
					'label'      => esc_html__( 'Offset Y', 'lastudio-kit' ),
					'type'       => Controls_Manager::SLIDER,
					'size_units' => array( 'px', '%', 'custom' ),
					'default'    => [
						'unit' => '%',
						'size' => '60',
					],
					'selectors'  => array(
						'{{WRAPPER}} .lakit-posts__floating_date' => '{{floating_date__vertical.VALUE}}: {{SIZE}}{{UNIT}};',
					),
				)
			);
			$this->_end_controls_section();
		}

		protected function _register_section_style_floating_counter( $css_scheme ) {
			/** Floating Counter **/
			$this->_start_controls_section(
				'section_floating_counter',
				array(
					'label'     => esc_html__( 'Floating Counter', 'lastudio-kit' ),
					'tab'       => Controls_Manager::TAB_STYLE,
					'condition' => [
						'floating_counter' => 'yes'
					]
				)
			);

			$this->_add_group_control(
				Group_Control_Typography::get_type(),
				array(
					'name'     => 'floating_counter_typography',
					'selector' => '{{WRAPPER}} .lakit-floating-counter',
				)
			);

			$this->_add_responsive_control(
				'floating_counter_padding',
				array(
					'label'      => esc_html__( 'Padding', 'lastudio-kit' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => array( 'px', '%', 'em' ),
					'selectors'  => [
						'{{WRAPPER}} .lakit-floating-counter' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				)
			);
			$this->_add_responsive_control(
				'floating_counter_margin',
				array(
					'label'      => esc_html__( 'Margin', 'lastudio-kit' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => array( 'px', '%', 'em' ),
					'selectors'  => [
						'{{WRAPPER}} .lakit-floating-counter' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				)
			);
			$this->_add_group_control(
				Group_Control_Border::get_type(),
				array(
					'name'      => 'floating_counter_border',
					'label'     => esc_html__( 'Border', 'lastudio-kit' ),
					'selector'  => '{{WRAPPER}} .lakit-floating-counter',
					'separator' => 'before',
				)
			);

			$this->_add_responsive_control(
				'floating_counter_radius',
				array(
					'label'      => __( 'Border Radius', 'lastudio-kit' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => array( 'px', '%' ),
					'selectors'  => array(
						'{{WRAPPER}} .lakit-floating-counter' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					),
				)
			);

			$this->_start_controls_tabs( 'floating_counter_style_tabs' );

			$this->_start_controls_tab( 'floating_counter_style_normal',
				[
					'label' => __( 'Normal', 'lastudio-kit' ),
				]
			);

			$this->_add_control(
				'floating_counter_color',
				[
					'label'     => __( 'Color', 'lastudio-kit' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .lakit-floating-counter' => 'color: {{VALUE}}',
					],
				]
			);

			$this->_add_control(
				'floating_counter_bgcolor',
				[
					'label'     => __( 'Background Color', 'lastudio-kit' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .lakit-floating-counter' => 'background-color: {{VALUE}}',
					],
				]
			);

			$this->_end_controls_tab();
			$this->_start_controls_tab( 'floating_counter_style_hover',
				[
					'label' => __( 'Normal', 'lastudio-kit' ),
				]
			);

			$this->_add_control(
				'floating_counter_hover_color',
				[
					'label'     => __( 'Color', 'lastudio-kit' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .lakit-posts__item:hover .lakit-floating-counter' => 'color: {{VALUE}}',
					],
				]
			);

			$this->_add_control(
				'floating_counter_hover_bgcolor',
				[
					'label'     => __( 'Background Color', 'lastudio-kit' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .lakit-posts__item:hover .lakit-floating-counter' => 'background-color: {{VALUE}}',
					],
				]
			);
			$this->_add_control(
				'floating_counter_hover_bordercolor',
				[
					'label'     => __( 'Border Color', 'lastudio-kit' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .lakit-posts__item:hover .lakit-floating-counter' => 'border-color: {{VALUE}}',
					],
				]
			);

			$this->_end_controls_tab();
			$this->_end_controls_tabs();
			$this->_end_controls_section();
		}

		protected function _register_section_style_floating_category( $css_scheme ) {
			/** Floating Category **/
			$this->_start_controls_section(
				'section_floating_cat',
				array(
					'label'     => esc_html__( 'Floating Category', 'lastudio-kit' ),
					'tab'       => Controls_Manager::TAB_STYLE,
					'condition' => [
						'floating_category' => 'yes'
					]
				)
			);

			$this->_add_control(
				'floating_cat_bgcolor',
				array(
					'label'     => esc_html__( 'Background Color', 'lastudio-kit' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'{{WRAPPER}} .lakit-posts__floating_category a' => 'background-color: {{VALUE}}',
					),
				)
			);

			$this->_add_control(
				'floating_cat_color',
				array(
					'label'     => esc_html__( 'Color', 'lastudio-kit' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'{{WRAPPER}} .lakit-posts__floating_category a' => 'color: {{VALUE}}',
					),
				)
			);

			$this->_add_group_control(
				Group_Control_Typography::get_type(),
				array(
					'name'     => 'floating_cat_typography',
					'selector' => '{{WRAPPER}} .lakit-posts__floating_category a',
				)
			);
			$this->_add_responsive_control(
				'floating_cat_padding',
				array(
					'label'      => esc_html__( 'Item Padding', 'lastudio-kit' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => array( 'px', '%', 'em' ),
					'selectors'  => [
						'{{WRAPPER}} .lakit-posts__floating_category a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				)
			);

            $this->_add_responsive_control(
                'floating_cat_gap',
                [
                    'label' => esc_html__( 'Item Gaps', 'lastudio-kit' ),
                    'type' => Controls_Manager::GAPS,
                    'default' => [
                        'row' => '0',
                        'column' => '5',
                        'unit' => 'px',
                    ],
                    'size_units' => [ 'px', '%', 'custom' ],
                    'placeholder' => [
                        'row' => '0',
                        'column' => '5',
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .lakit-posts__floating_category-inner' => 'gap: {{ROW}}{{UNIT}} {{COLUMN}}{{UNIT}}',
                    ],
                ]
            );

			$this->_add_responsive_control(
				'floating_cat_border',
				array(
					'label'      => esc_html__( 'Border Radius', 'lastudio-kit' ),
					'type'       => Controls_Manager::SLIDER,
					'size_units' => array( 'px', '%', 'em' ),
					'selectors'  => array(
						'{{WRAPPER}} .lakit-posts__floating_category a' => 'border-radius: {{SIZE}}{{UNIT}};',
					),
				)
			);

			$this->_add_control(
				'floating_cat__heading',
				[
					'label'       => esc_html__( 'Position', 'lastudio-kit' ),
					'type'        => Controls_Manager::HEADING,
					'label_block' => true,
					'separator'   => 'before',
				]
			);

			$this->_add_control(
				'floating_cat__horizontal',
				[
					'label' => esc_html__( 'Horizontal Orientation', 'lastudio-kit' ),
					'type' => Controls_Manager::CHOOSE,
					'default' => is_rtl() ? 'right' : 'left',
					'options' => [
						'left' => [
							'title' => esc_html__( 'Left', 'lastudio-kit' ),
							'icon' => 'eicon-h-align-left',
						],
						'right' => [
							'title' => esc_html__( 'Right', 'lastudio-kit' ),
							'icon' => 'eicon-h-align-right',
						],
					],
					'toggle' => false
				]
			);
			$this->_add_responsive_control(
				'floating_cat_left_position',
				array(
					'label'      => esc_html__( 'Offset X', 'lastudio-kit' ),
					'type'       => Controls_Manager::SLIDER,
					'size_units' => array( 'px', '%','custom' ),
					'default'    => [
						'unit' => 'px',
						'size' => '10',
					],
					'selectors'  => array(
						'{{WRAPPER}} .lakit-posts__floating_category' => '{{floating_cat__horizontal.VALUE}}: {{SIZE}}{{UNIT}};',
					),
				)
			);

			$this->_add_control(
				'floating_cat__vertical',
				[
					'label' => esc_html__( 'Vertical Orientation', 'lastudio-kit' ),
					'type' => Controls_Manager::CHOOSE,
					'options' => [
						'top' => [
							'title' => esc_html__( 'Top', 'lastudio-kit' ),
							'icon' => 'eicon-v-align-top',
						],
						'bottom' => [
							'title' => esc_html__( 'Bottom', 'lastudio-kit' ),
							'icon' => 'eicon-v-align-bottom',
						],
					],
					'default' => 'top',
					'toggle' => false,
				]
			);

			$this->_add_responsive_control(
				'floating_cat_top_position',
				array(
					'label'      => esc_html__( 'Offset Y', 'lastudio-kit' ),
					'type'       => Controls_Manager::SLIDER,
					'size_units' => array( 'px', '%', 'custom' ),
					'default'    => [
						'unit' => 'px',
						'size' => '10',
					],
					'selectors'  => array(
						'{{WRAPPER}} .lakit-posts__floating_category' => '{{floating_cat__vertical.VALUE}}: {{SIZE}}{{UNIT}};',
					),
				)
			);

			$this->_end_controls_section();
		}

		protected function _register_section_style_floating_postformat( $css_scheme ) {
			/** Floating Post Format **/
			$this->_start_controls_section(
				'section_floating_format',
				array(
					'label'     => esc_html__( 'Floating PostFormat', 'lastudio-kit' ),
					'tab'       => Controls_Manager::TAB_STYLE,
                    'conditions' => [
                        'relation' => 'or',
                        'terms' => [
                            [
                                'name' => 'floating_postformat',
                                'operator' => '==',
                                'value' => 'yes',
                            ],
                            [
                                'name' => 'postformat_content',
                                'operator' => '==',
                                'value' => 'yes',
                            ],
                        ],
                    ],
				)
			);
			$this->_add_responsive_control(
				'floating_pfm_margin',
				[
					'label'      => __( 'Margin', 'lastudio-kit' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => array( 'px', '%', 'em' ),
					'selectors'  => [
						'{{WRAPPER}} .lakit-posts__floating_postformat' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);

			$this->_add_control(
				'floating_pfm_bgcolor',
				array(
					'label'     => esc_html__( 'Background Color', 'lastudio-kit' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'{{WRAPPER}} .lakit-posts__floating_postformat' => 'background-color: {{VALUE}}',
					),
				)
			);

			$this->_add_control(
				'floating_pfm_color',
				array(
					'label'     => esc_html__( 'Color', 'lastudio-kit' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => array(
						'{{WRAPPER}} .lakit-posts__floating_postformat' => 'color: {{VALUE}}',
					),
				)
			);

			$this->_add_group_control(
				Group_Control_Typography::get_type(),
				array(
					'name'     => 'floating_pfm_typography',
					'selector' => '{{WRAPPER}} .lakit-posts__floating_postformat',
				)
			);
			$this->_add_responsive_control(
				'floating_pfm_width',
				array(
					'label'      => esc_html__( 'Width', 'lastudio-kit' ),
					'type'       => Controls_Manager::SLIDER,
					'size_units' => array( 'px', '%', 'custom' ),
					'selectors'  => array(
						'{{WRAPPER}} .lakit-posts__floating_postformat' => 'width: {{SIZE}}{{UNIT}};',
					),
				)
			);
			$this->_add_responsive_control(
				'floating_pfm_height',
				array(
					'label'      => esc_html__( 'Height', 'lastudio-kit' ),
					'type'       => Controls_Manager::SLIDER,
					'size_units' => array( 'px', '%', 'custom' ),
					'selectors'  => array(
						'{{WRAPPER}} .lakit-posts__floating_postformat' => 'height: {{SIZE}}{{UNIT}};',
					),
				)
			);
			$this->_add_responsive_control(
				'floating_pfm_border',
				array(
					'label'      => esc_html__( 'Border Radius', 'lastudio-kit' ),
					'type'       => Controls_Manager::SLIDER,
					'size_units' => array( 'px', '%', 'em' ),
					'selectors'  => array(
						'{{WRAPPER}} .lakit-posts__floating_postformat' => 'border-radius: {{SIZE}}{{UNIT}};',
					),
				)
			);

			$this->_add_group_control(
				Group_Control_Border::get_type(),
				array(
					'name'        => 'floating_pfm_border',
					'label'       => esc_html__( 'Border', 'lastudio-kit' ),
					'placeholder' => '1px',
					'default'     => '1px',
					'selector'    => '{{WRAPPER}} .lakit-posts__floating_postformat',
				)
			);

			$this->_add_control(
				'floating_pfm__heading',
				[
					'label'       => esc_html__( 'Position', 'lastudio-kit' ),
					'type'        => Controls_Manager::HEADING,
					'label_block' => true,
					'separator'   => 'before',
				]
			);

			$this->_add_control(
				'floating_pfm__horizontal',
				[
					'label' => esc_html__( 'Horizontal Orientation', 'lastudio-kit' ),
					'type' => Controls_Manager::CHOOSE,
					'default' => is_rtl() ? 'left' : 'right',
					'options' => [
						'left' => [
							'title' => esc_html__( 'Left', 'lastudio-kit' ),
							'icon' => 'eicon-h-align-left',
						],
						'right' => [
							'title' => esc_html__( 'Right', 'lastudio-kit' ),
							'icon' => 'eicon-h-align-right',
						],
					],
					'toggle' => false
				]
			);
			$this->_add_responsive_control(
				'floating_pfm_left_position',
				array(
					'label'      => esc_html__( 'Offset X', 'lastudio-kit' ),
					'type'       => Controls_Manager::SLIDER,
					'size_units' => array( 'px', '%','custom' ),
					'default'    => [
						'unit' => 'px',
						'size' => '10',
					],
					'selectors'  => array(
						'{{WRAPPER}} .lakit-posts__floating_postformat' => '{{floating_pfm__horizontal.VALUE}}: {{SIZE}}{{UNIT}};',
					),
				)
			);

			$this->_add_control(
				'floating_pfm__vertical',
				[
					'label' => esc_html__( 'Vertical Orientation', 'lastudio-kit' ),
					'type' => Controls_Manager::CHOOSE,
					'options' => [
						'top' => [
							'title' => esc_html__( 'Top', 'lastudio-kit' ),
							'icon' => 'eicon-v-align-top',
						],
						'bottom' => [
							'title' => esc_html__( 'Bottom', 'lastudio-kit' ),
							'icon' => 'eicon-v-align-bottom',
						],
					],
					'default' => 'top',
					'toggle' => false,
				]
			);

			$this->_add_responsive_control(
				'floating_pfm_top_position',
				array(
					'label'      => esc_html__( 'Offset Y', 'lastudio-kit' ),
					'type'       => Controls_Manager::SLIDER,
					'size_units' => array( 'px', '%', 'custom' ),
					'default'    => [
						'unit' => 'px',
						'size' => '10',
					],
					'selectors'  => array(
						'{{WRAPPER}} .lakit-posts__floating_postformat' => '{{floating_pfm__vertical.VALUE}}: {{SIZE}}{{UNIT}};',
					),
				)
			);

			$this->_end_controls_section();

		}

		protected function register_controls() {

			$css_scheme = apply_filters(
				'lastudio-kit/' . $this->get_lakit_name() . '/css-schema',
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
				)
			);

			$this->_register_section_layout( $css_scheme );

			$this->_register_section_meta( $css_scheme );

			$this->_register_section_query( $css_scheme );

			$this->register_masonry_setting_section( [ 'enable_masonry' => 'yes' ] );

			$this->register_carousel_section( [ 'enable_masonry!' => 'yes' ], 'columns' );

			$this->_register_section_style_general( $css_scheme );

			$this->_register_section_style_meta( $css_scheme );

			$this->_register_section_style_floating_date( $css_scheme );

			$this->_register_section_style_floating_counter( $css_scheme );

			$this->_register_section_style_floating_category( $css_scheme );

			$this->_register_section_style_floating_postformat( $css_scheme );

			$this->_register_section_style_pagination( $css_scheme );

			$this->register_carousel_arrows_dots_style_section( [
                'enable_masonry!' => 'yes',
                'enable_carousel' => 'yes',
            ] );

		}

		protected function render() {
			$this->_context = 'render';

            $paged_key = 'post-page' . esc_attr( $this->get_id() );
            $query_post_type = $this->get_settings_for_display( 'query_post_type' );

            $enable_ajax_load = filter_var($this->get_settings_for_display('enable_ajax_load'), FILTER_VALIDATE_BOOLEAN);
            if( $query_post_type !== 'current_query' && $enable_ajax_load && !lastudio_kit()->elementor()->editor->is_edit_mode() && !isset($_REQUEST[$paged_key])){
                echo sprintf(
                    '<div data-lakit_ajax_loadwidget="true" data-widget-id="%1$s" data-pagedkey="%2$s"><span class="lakit-css-loader"></span></div>',
                    $this->get_id(),
                    $paged_key
                );
                return;
            }

			if ( $query_post_type == 'current_query' ) {
				$paged_key = 'paged';
				$this->add_render_attribute( 'main-container', 'data-widget_current_query', 'yes' );
			}

			$page = absint( empty( $_REQUEST[ $paged_key ] ) ? 1 : $_REQUEST[ $paged_key ] );
			if ( $query_post_type == 'current_query' ) {
				$page = get_query_var( 'paged' ) ? (int) get_query_var( 'paged' ) : 1;
				if ( ! empty( $_REQUEST[ $paged_key ] ) ) {
					$page = $_REQUEST[ $paged_key ];
				}
			}

			$this->_paged_key = $paged_key;

			$query_args = [
				'posts_per_page' => $this->get_settings_for_display( 'query_posts_per_page' ),
				'paged'          => 1,
			];

			if ( 1 < $page ) {
				$query_args['paged'] = $page;
			}

			$module_query = Module_Query::get_instance();
			$this->_query = $module_query->get_query( $this, 'query', $query_args, [] );

			$this->_open_wrap();
			include $this->_get_global_template( 'index' );
			$this->_close_wrap();

			wp_reset_postdata();
		}

		protected function the_query() {
			return $this->_query;
		}

		protected function render_post_format_icon( $type ) {
			$output = '';
			switch ( $type ) {
				case 'video':
					$output = '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" aria-hidden="true" focusable="false"><path fill="currentColor" d="M4.6.2A1 1 0 0 0 3 1v18a1 1 0 0 0 1.6.8l12-9a1 1 0 0 0 0-1.6Z"></path></svg>';
					break;
				case 'audio':
					$output = '<svg aria-hidden="true" focusable="false" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path fill="currentColor" d="M481.44 0a29.38 29.38 0 0 0-9.25 1.5l-290.78 96C168.72 101.72 160 114 160 128v244.75C143 360 120.69 352 96 352c-53 0-96 35.81-96 80s43 80 96 80 96-35.81 96-80V256l288-96v148.75C463 296 440.69 288 416 288c-53 0-96 35.81-96 80s43 80 96 80 96-35.81 96-80V32c0-18.25-14.31-32-30.56-32zM96 480c-34.69 0-64-22-64-48s29.31-48 64-48 64 22 64 48-29.31 48-64 48zm320-64c-34.69 0-64-22-64-48s29.31-48 64-48 64 22 64 48-29.31 48-64 48zm64-289.72l-288 96V128h-.56v-.12L480 32.62z"></path></svg>';
					break;
				case 'gallery':
					$output = '<svg aria-hidden="true" focusable="false" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512"><path fill="currentColor" d="M528 32H112c-26.51 0-48 21.49-48 48v16H48c-26.51 0-48 21.49-48 48v288c0 26.51 21.49 48 48 48h416c26.51 0 48-21.49 48-48v-16h16c26.51 0 48-21.49 48-48V80c0-26.51-21.49-48-48-48zm-48 400c0 8.822-7.178 16-16 16H48c-8.822 0-16-7.178-16-16V144c0-8.822 7.178-16 16-16h16v240c0 26.51 21.49 48 48 48h368v16zm64-64c0 8.822-7.178 16-16 16H112c-8.822 0-16-7.178-16-16V80c0-8.822 7.178-16 16-16h416c8.822 0 16 7.178 16 16v288zM176 200c30.928 0 56-25.072 56-56s-25.072-56-56-56-56 25.072-56 56 25.072 56 56 56zm0-80c13.234 0 24 10.766 24 24s-10.766 24-24 24-24-10.766-24-24 10.766-24 24-24zm240.971 23.029c-9.373-9.373-24.568-9.373-33.941 0L288 238.059l-31.029-31.03c-9.373-9.373-24.569-9.373-33.941 0l-88 88A24.002 24.002 0 0 0 128 312v28c0 6.627 5.373 12 12 12h360c6.627 0 12-5.373 12-12v-92c0-6.365-2.529-12.47-7.029-16.971l-88-88zM480 320H160v-4.686l80-80 48 48 112-112 80 80V320z"></path></svg>';
					break;
				case 'image':
					$output = '<svg aria-hidden="true" focusable="false" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path fill="currentColor" d="M464 64H48C21.49 64 0 85.49 0 112v288c0 26.51 21.49 48 48 48h416c26.51 0 48-21.49 48-48V112c0-26.51-21.49-48-48-48zm16 336c0 8.822-7.178 16-16 16H48c-8.822 0-16-7.178-16-16V112c0-8.822 7.178-16 16-16h416c8.822 0 16 7.178 16 16v288zM112 232c30.928 0 56-25.072 56-56s-25.072-56-56-56-56 25.072-56 56 25.072 56 56 56zm0-80c13.234 0 24 10.766 24 24s-10.766 24-24 24-24-10.766-24-24 10.766-24 24-24zm207.029 23.029L224 270.059l-31.029-31.029c-9.373-9.373-24.569-9.373-33.941 0l-88 88A23.998 23.998 0 0 0 64 344v28c0 6.627 5.373 12 12 12h360c6.627 0 12-5.373 12-12v-92c0-6.365-2.529-12.47-7.029-16.971l-88-88c-9.373-9.372-24.569-9.372-33.942 0zM416 352H96v-4.686l80-80 48 48 112-112 80 80V352z"></path></svg>';
					break;
				case 'link':
					$output = '<svg aria-hidden="true" focusable="false"  xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path fill="currentColor" d="M301.148 394.702l-79.2 79.19c-50.778 50.799-133.037 50.824-183.84 0-50.799-50.778-50.824-133.037 0-183.84l79.19-79.2a132.833 132.833 0 0 1 3.532-3.403c7.55-7.005 19.795-2.004 20.208 8.286.193 4.807.598 9.607 1.216 14.384.481 3.717-.746 7.447-3.397 10.096-16.48 16.469-75.142 75.128-75.3 75.286-36.738 36.759-36.731 96.188 0 132.94 36.759 36.738 96.188 36.731 132.94 0l79.2-79.2.36-.36c36.301-36.672 36.14-96.07-.37-132.58-8.214-8.214-17.577-14.58-27.585-19.109-4.566-2.066-7.426-6.667-7.134-11.67a62.197 62.197 0 0 1 2.826-15.259c2.103-6.601 9.531-9.961 15.919-7.28 15.073 6.324 29.187 15.62 41.435 27.868 50.688 50.689 50.679 133.17 0 183.851zm-90.296-93.554c12.248 12.248 26.362 21.544 41.435 27.868 6.388 2.68 13.816-.68 15.919-7.28a62.197 62.197 0 0 0 2.826-15.259c.292-5.003-2.569-9.604-7.134-11.67-10.008-4.528-19.371-10.894-27.585-19.109-36.51-36.51-36.671-95.908-.37-132.58l.36-.36 79.2-79.2c36.752-36.731 96.181-36.738 132.94 0 36.731 36.752 36.738 96.181 0 132.94-.157.157-58.819 58.817-75.3 75.286-2.651 2.65-3.878 6.379-3.397 10.096a163.156 163.156 0 0 1 1.216 14.384c.413 10.291 12.659 15.291 20.208 8.286a131.324 131.324 0 0 0 3.532-3.403l79.19-79.2c50.824-50.803 50.799-133.062 0-183.84-50.802-50.824-133.062-50.799-183.84 0l-79.2 79.19c-50.679 50.682-50.688 133.163 0 183.851z"></path></svg>';
					break;
				case 'quote':
					$output = '<svg aria-hidden="true" focusable="false" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path fill="currentColor" d="M176 32H64C28.7 32 0 60.7 0 96v128c0 35.3 28.7 64 64 64h64v24c0 30.9-25.1 56-56 56H56c-22.1 0-40 17.9-40 40v32c0 22.1 17.9 40 40 40h16c92.6 0 168-75.4 168-168V96c0-35.3-28.7-64-64-64zm32 280c0 75.1-60.9 136-136 136H56c-4.4 0-8-3.6-8-8v-32c0-4.4 3.6-8 8-8h16c48.6 0 88-39.4 88-88v-56H64c-17.7 0-32-14.3-32-32V96c0-17.7 14.3-32 32-32h112c17.7 0 32 14.3 32 32v216zM448 32H336c-35.3 0-64 28.7-64 64v128c0 35.3 28.7 64 64 64h64v24c0 30.9-25.1 56-56 56h-16c-22.1 0-40 17.9-40 40v32c0 22.1 17.9 40 40 40h16c92.6 0 168-75.4 168-168V96c0-35.3-28.7-64-64-64zm32 280c0 75.1-60.9 136-136 136h-16c-4.4 0-8-3.6-8-8v-32c0-4.4 3.6-8 8-8h16c48.6 0 88-39.4 88-88v-56h-96c-17.7 0-32-14.3-32-32V96c0-17.7 14.3-32 32-32h112c17.7 0 32 14.3 32 32v216z"></path></svg>';
					break;
				case 'aside':
					$output = '<svg aria-hidden="true" focusable="false" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path fill="currentColor" d="M504 304H296a8 8 0 0 0-8 8v16a8 8 0 0 0 8 8h208a8 8 0 0 0 8-8v-16a8 8 0 0 0-8-8zm0 128H296a8 8 0 0 0-8 8v16a8 8 0 0 0 8 8h208a8 8 0 0 0 8-8v-16a8 8 0 0 0-8-8zm0-256H296a8 8 0 0 0-8 8v16a8 8 0 0 0 8 8h208a8 8 0 0 0 8-8v-16a8 8 0 0 0-8-8zm0-128H296a8 8 0 0 0-8 8v16a8 8 0 0 0 8 8h208a8 8 0 0 0 8-8V56a8 8 0 0 0-8-8zM216 432H8a8 8 0 0 0-8 8v16a8 8 0 0 0 8 8h208a8 8 0 0 0 8-8v-16a8 8 0 0 0-8-8zm0-128H8a8 8 0 0 0-8 8v16a8 8 0 0 0 8 8h208a8 8 0 0 0 8-8v-16a8 8 0 0 0-8-8zm0-256H8a8 8 0 0 0-8 8v16a8 8 0 0 0 8 8h208a8 8 0 0 0 8-8V56a8 8 0 0 0-8-8zm0 128H8a8 8 0 0 0-8 8v16a8 8 0 0 0 8 8h208a8 8 0 0 0 8-8v-16a8 8 0 0 0-8-8z"></path></svg>';
					break;
				case 'chat':
					$output = '<svg aria-hidden="true" focusable="false" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512"><path fill="currentColor" d="M569.9 441.1c-.5-.4-22.6-24.2-37.9-54.9 27.5-27.1 44-61.1 44-98.2 0-80-76.5-146.1-176.2-157.9C368.4 72.5 294.3 32 208 32 93.1 32 0 103.6 0 192c0 37 16.5 71 44 98.2-15.3 30.7-37.3 54.5-37.7 54.9-6.3 6.7-8.1 16.5-4.4 25 3.6 8.5 12 14 21.2 14 53.5 0 96.7-20.2 125.2-38.8 9.1 2.1 18.4 3.7 28 4.8 31.5 57.5 105.5 98 191.8 98 20.8 0 40.8-2.4 59.8-6.8 28.5 18.5 71.6 38.8 125.2 38.8 9.2 0 17.5-5.5 21.2-14 3.6-8.5 1.9-18.3-4.4-25zM155.4 314l-13.2-3-11.4 7.4c-20.1 13.1-50.5 28.2-87.7 32.5 8.8-11.3 20.2-27.6 29.5-46.4L83 283.7l-16.5-16.3C50.7 251.9 32 226.2 32 192c0-70.6 79-128 176-128s176 57.4 176 128-79 128-176 128c-17.7 0-35.4-2-52.6-6zm289.8 100.4l-11.4-7.4-13.2 3.1c-17.2 4-34.9 6-52.6 6-65.1 0-122-25.9-152.4-64.3C326.9 348.6 416 278.4 416 192c0-9.5-1.3-18.7-3.3-27.7C488.1 178.8 544 228.7 544 288c0 34.2-18.7 59.9-34.5 75.4L493 379.7l10.3 20.7c9.4 18.9 20.8 35.2 29.5 46.4-37.1-4.2-67.5-19.4-87.6-32.4z"></path></svg>';
					break;
			}

			return apply_filters( 'lastudio-kit/' . $this->get_lakit_name() . '/format-icon', $output, $type );
		}

		protected function preset_list() {
			$preset_type = apply_filters(
				'lastudio-kit/' . $this->get_lakit_name() . '/control/preset',
				array(
					'grid-1' => esc_html__( 'Grid 1', 'lastudio-kit' ),
					'grid-2' => esc_html__( 'Grid 2', 'lastudio-kit' ),
					'list-1' => esc_html__( 'List 1', 'lastudio-kit' ),
					'list-2' => esc_html__( 'List 2', 'lastudio-kit' ),
				)
			);

			return $preset_type;
		}

		protected function condition_grid2() {
			return [ 'grid-2', 'grid-2a', 'grid-2b' ];
		}
	}
