<?php
/**
 * Class: Soft_Template_Post_Archive
 * Name: Advance Query Posts
 * Slug: soft-template-post-archive
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

use Elementor\Utils;
use Elementor\Plugin;
use Elementor\Core\Files\CSS\Post as Post_CSS;
use WP_Query;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Soft_Template_Post_Archive extends SoftTemplate_Base {
    public function get_name() {
		return 'soft-template-post-archive';
	}

	public function get_title() {
		return esc_html__( 'Advance Query Posts', 'soft-template-core' );
	}

    public function get_icon() {
		return 'eicon-post-list';
	}

    public function get_jet_help_url() {
		return '#';
	}

    public function get_categories() {
		return array( 'soft-template-core' );
	}

	public function get_script_depends() {

		if ( \Elementor\Plugin::$instance->editor->is_edit_mode() || \Elementor\Plugin::$instance->preview->is_preview_mode() ) {

			return [ 'isotope', 'packery-mode', 'soft-template-post-archive' ];

		} else {

			$scripts  = [];
	
			$settings = $this->get_settings_for_display();
	
			if ( 'masonry' === $settings['behavior'] ) {
				$scripts[] = 'isotope';
				$scripts[] = 'packery-mode';
				$scripts[] = 'soft-template-post-archive';
			}
	
			return $scripts;
		}

	}

    protected function register_controls() {
        // Widget main
        $this->widget_query_options();
        $this->widget_layout_general_options();
        $this->widget_layout_options();
        $this->widget_readmore_button();

        // Form Style
        $this->widget_pagination_style();
        $this->widget_content_style();
        $this->widget_spacing_style();
        $this->widget_read_more_style();
        $this->widget_date_box();
    }

    public function widget_query_options() {
        
        $ae_post_types                         = \Soft_template_Core_Utils::get_rule_post_types();
		$ae_post_types_options                 = $ae_post_types;
		$ae_post_types_options['current_loop'] = __( 'Current Archive', 'soft-template-core' );
		$ae_post_types_options['post_by_id']     = __( 'Manual Selection', 'soft-template-core' );
		$ae_post_types_options['related']      = __( 'Related Posts', 'soft-template-core' );
        
        
        $this->start_controls_section(
			'section_query',
			[
				'label' => __( 'Query', 'soft-template-core' ),
			]
		);

        $this->add_control(
			'ae_post_block_adv_mention',
			[
				'type'            => Controls_Manager::RAW_HTML,
				'content_classes' => 'ae-alert ae-alert-warning',
				'raw'             => __( 'Try <b>Post Blocks Advanced</b> - a new and enhanced version of this widget.', 'soft-template-core' ),
				'separator'       => 'after',
			]
		);

        /**
		 *  Add new custom source
		 */

        $ae_post_types_options = apply_filters( 'soft-template-core/post-blocks/custom-source', $ae_post_types_options );

        $this->add_control(
			'ae_post_type',
			[
				'label'   => __( 'Source', 'soft-template-core' ),
				'type'    => Controls_Manager::SELECT,
				'options' => $ae_post_types_options,
				'default' => key( $ae_post_types ),
			]
		);

        $this->add_control(
			'ae_post_ids',
			[
				'label'       => __( 'Posts', 'soft-template-core' ),
				'type'        => Controls_Manager::SELECT2,
				'multiple'    => true,
				'label_block' => true,
				'placeholder' => __( 'Selects Posts', 'soft-template-core' ),
				'default'     => __( '', 'soft-template-core' ),
				'condition'   => [
					'ae_post_type' => 'post_by_id',
				],
			]
		);

        $this->add_control(
			'related_by',
			[
				'label'       => __( 'Related By', 'soft-template-core' ),
				'type'        => Controls_Manager::SELECT2,
				'multiple'    => true,
				'label_block' => true,
				'placeholder' => __( 'Select Taxonomies', 'soft-template-core' ),
				'default'     => '',
				'options'     => \Soft_template_Core_Utils::get_rules_taxonomies(),
				'condition'   => [
					'ae_post_type' => 'related',
				],
			]
		);

        $this->add_control(
			'related_match_with',
			[
				'label'     => __( 'Match With', 'soft-template-core' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'OR',
				'options'   => [
					'OR'  => __( 'Anyone Term', 'soft-template-core' ),
					'AND' => __( 'All Terms', 'soft-template-core' ),
				],
				'condition' => [
					'ae_post_type' => 'related',
				],
			]
		);

        $this->add_control(
			'author_ae_ids',
			[
				'label'       => 'Authors',
				'type'        => Controls_Manager::SELECT2,
				'multiple'    => true,
				'label_block' => true,
				'placeholder' => __( 'Enter Author ID Separated by Comma', 'soft-template-core' ),
				'options'     => \Soft_template_Core_Utils::get_authors(),
				'conditions'  => [
					'relation' => 'and',
					'terms'    => [
						[
							'name'     => 'ae_post_type',
							'operator' => '!==',
							'value'    => 'post_by_id',
						],
						[
							'name'     => 'ae_post_type',
							'operator' => '!==',
							'value'    => 'current_loop',
						],
						[
							'name'     => 'ae_post_type',
							'operator' => '!==',
							'value'    => 'related',
						],
						[
							'name'     => 'ae_post_type',
							'operator' => '!==',
							'value'    => 'relation',
						],
					],
				],
			]
		);

        $this->add_control(
			'taxonomy_heading',
			[
				'label'     => __( 'Taxonomy', 'soft-template-core' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'after',
				'condition' => [
					'ae_post_type' => 'post',
				],
			]
		);

        $ae_taxonomy_filter_args = [
			'show_in_nav_menus' => true,
		];

        $ae_taxonomies = get_taxonomies( $ae_taxonomy_filter_args, 'objects' );

        foreach ( $ae_taxonomies as $ae_taxonomy => $object ) {
			$this->add_control(
				$ae_taxonomy . '_ae_ids',
				[
					'label'       => $object->label,
					'type'        => Controls_Manager::SELECT2,
					'multiple'    => true,
					'label_block' => true,
					'placeholder' => __( 'Enter ' . $object->label . ' ID Separated by Comma', 'soft-template-core' ),
					'object_type' => $ae_taxonomy,
					'options'     => \Soft_template_Core_Utils::get_taxonomy_terms( $ae_taxonomy ),
					'condition'   => [
						'ae_post_type' => $object->object_type,
					],
				]
			);
		}

        $this->add_control(
			'tax_relation',
			[
				'label'     => __( 'Taxonomy Relation', 'soft-template-core' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'AND',
				'options'   => [
					'OR'  => __( 'OR', 'soft-template-core' ),
					'AND' => __( 'AND', 'soft-template-core' ),
				],
				'condition' => [
					'ae_post_type!' => [ 'current_loop', 'post_by_id', 'related', 'relation', 'post_object' ],
				],
			]
		);

        $this->add_control(
			'taxonomy_divider',
			[
				'type' => Controls_Manager::DIVIDER,
			]
		);

        $this->add_control(
			'current_post',
			[
				'label'        => __( 'Exclude Current Post', 'soft-template-core' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'yes',
				'label_on'     => __( 'Show', 'soft-template-core' ),
				'label_off'    => __( 'Hide', 'soft-template-core' ),
				'return_value' => 'yes',
				'condition'    => [
					'ae_post_type!' => 'current_loop',
					'ae_post_type!' => 'related',
				],
			]
		);

        $this->add_control(
			'advanced',
			[
				'label'     => __( 'Advanced', 'soft-template-core' ),
				'type'      => Controls_Manager::HEADING,
				'condition' => [
					'ae_post_type!' => 'current_loop',
				],
			]
		);


		$this->add_control(
			'orderby',
			[
				'label'           => __( 'Order By', 'soft-template-core' ),
				'type'            => Controls_Manager::SELECT,
				'content_classes' => 'ae_conditional_fields',
				'default'         => 'post_date',
				'options'         => [
					'post_date'      => __( 'Date', 'soft-template-core' ),
					'post_title'     => __( 'Title', 'soft-template-core' ),
					'menu_order'     => __( 'Menu Order', 'soft-template-core' ),
					'rand'           => __( 'Random', 'soft-template-core' ),
					'post__in'       => __( 'Manual', 'soft-template-core' ),
					'meta_value'     => __( 'Custom Field', 'soft-template-core' ),
					'meta_value_num' => __( 'Custom Field (Numeric)', 'soft-template-core' ),
				],
				'condition'       => [
					'ae_post_type!' => 'current_loop',
				],
			]
		);

        $this->add_control(
			'orderby_alert',
			[
				'type'            => Controls_Manager::RAW_HTML,
				'content_classes' => 'ae_order_by_alert',
				'raw'             => __( "<div class='elementor-control-field-description'>Note: Order By 'Manual' is only applicable when Source is 'Manual Selection' and 'Relationship' </div>", 'soft-template-core' ),
				'separator'       => 'none',
				'condition'       => [
					'orderby' => 'post__in',
				],
			]
		);

        $this->add_control(
			'orderby_metakey_name',
			[
				'label'       => __( 'Meta Key Name', 'soft-template-core' ),
				'tyoe'        => Controls_Manager::TEXT,
				'description' => __( 'Custom Field Key', 'soft-template-core' ),
				'condition'   => [
					'ae_post_type!' => 'current_loop',
					'orderby'       => [ 'meta_value', 'meta_value_num' ],
				],
			]
		);

        $this->add_control(
			'order',
			[
				'label'     => __( 'Order', 'soft-template-core' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'desc',
				'options'   => [
					'asc'  => __( 'ASC', 'soft-template-core' ),
					'desc' => __( 'DESC', 'soft-template-core' ),
				],
				'condition' => [
					'ae_post_type!' => 'current_loop',
					'orderby!'      => 'post__in',
				],
			]
		);

        $this->add_control(
			'posts_per_page',
			[
				'label'     => __( 'Posts Count', 'soft-template-core' ),
				'type'      => Controls_Manager::NUMBER,
				'default'   => 6,
				'condition' => [
					'ae_post_type!' => 'current_loop',
				],
			]
		);

        $this->add_control(
			'offset',
			[
				'label'       => __( 'Offset', 'soft-template-core' ),
				'type'        => Controls_Manager::NUMBER,
				'default'     => 0,
				'condition'   => [
					'ae_post_type!' => [ 'current_loop', 'post_by_id' ],
				],
				'description' => __( 'Use this setting to skip over posts (e.g. \'2\' to skip over 2 posts).', 'soft-template-core' ),
			]
		);


		$this->add_control(
			'query_filter',
			[
				'label'       => __( 'Query Filter', 'soft-template-core' ),
				'type'        => Controls_Manager::TEXT,
				'description' => __( '<span style="color:red">Danger Ahead!!</span> It is a developer oriented feature. Only use if you know how exaclty WordPress queries and filters works. <a href="https://wpvibes.link/go/feature-post-blocks-query-filter">Read Instructions</a>', 'soft-template-core' ),
			]
		);	
		
		$this->add_control(
			'no_post_message',
			[
				'label'       => __( 'No Post Message', 'soft-template-core' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => __('No post available', 'soft-template-core'),
			]
		);

        $this->end_controls_section();
    }   
    
    public function widget_layout_general_options() {
		$this->start_controls_section(
			'section_layout',
			[
				'label' => __( 'General', 'soft-template-core' ),
			]
		);

		$this->add_control(
			'behavior',
			[
				'label' => __( 'List Appearance', 'soft-template-core' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'columns',
				'options' => [
					'columns'  => __( 'Gallery', 'soft-template-core' ),
					'masonry' => __( 'Masonry', 'soft-template-core' ),
				],
			]
		);	
		
		$this->add_group_control(
			Group_Control_Image_Size::get_type(),
			[
				'label' => __( 'Image Proportions', 'soft-template-core' ),
				'name' => 'masonry_images_proportion', 
				'include' => [],
				'default' => 'large',
			]
		);
				

		$this->add_control(
			'number_of_coloumns',
			[
				'label' => __( 'Number of Columns', 'soft-template-core' ),
				'type' => Controls_Manager::SELECT,
				'default' => '3',
				'options' => [
					'1'  => __( 'One', 'soft-template-core' ),
					'2' => __( 'Two', 'soft-template-core' ),
					'3' => __( 'Three', 'soft-template-core' ),
					'4' => __( 'Four', 'soft-template-core' ),
					'5' => __( 'Five', 'soft-template-core' ),
					'6' => __( 'Six', 'soft-template-core' ),
					'7' => __( 'Seven', 'soft-template-core' ),
					'8' => __( 'Eight', 'soft-template-core' ),
					'' => __( 'Default', 'soft-template-core' ),
				],
			]
		);	

		$this->add_control(
			'coloumns_responsive',
			[
				'label' => __( 'Columns Responsive', 'soft-template-core' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'predefined',
				'options' => [
					'predefined'  => __( 'Predefined', 'soft-template-core' ),
					'custom' => __( 'Custom', 'soft-template-core' ),
				],
			]
		);		
		
		$this->add_control(
			'columns_1440',
			[
				'label' => __( 'Number of Columns 1367px - 1440px', 'soft-template-core' ),
				'type' => Controls_Manager::SELECT,
				'default' => '3',
				'options' => [
					'1'  => __( 'One', 'soft-template-core' ),
					'2' => __( 'Two', 'soft-template-core' ),
					'3' => __( 'Three', 'soft-template-core' ),
					'4' => __( 'Four', 'soft-template-core' ),
					'5' => __( 'Five', 'soft-template-core' ),
					'6' => __( 'Six', 'soft-template-core' ),
					'7' => __( 'Seven', 'soft-template-core' ),
					'8' => __( 'Eight', 'soft-template-core' ),
					'' => __( 'Default', 'soft-template-core' ),
				],
				'condition'   => [
					'coloumns_responsive' => 'custom',
				],
			]
		);		

		$this->add_control(
			'columns_1366',
			[
				'label' => __( 'Number of Columns 1025px - 1366px', 'soft-template-core' ),
				'type' => Controls_Manager::SELECT,
				'default' => '3',
				'options' => [
					'1'  => __( 'One', 'soft-template-core' ),
					'2' => __( 'Two', 'soft-template-core' ),
					'3' => __( 'Three', 'soft-template-core' ),
					'4' => __( 'Four', 'soft-template-core' ),
					'5' => __( 'Five', 'soft-template-core' ),
					'6' => __( 'Six', 'soft-template-core' ),
					'7' => __( 'Seven', 'soft-template-core' ),
					'8' => __( 'Eight', 'soft-template-core' ),
					'' => __( 'Default', 'soft-template-core' ),
				],
				'condition'   => [
					'coloumns_responsive' => 'custom',
				],
			]
		);		
		
		$this->add_control(
			'columns_1024',
			[
				'label' => __( 'Number of Columns 769px - 1024px', 'soft-template-core' ),
				'type' => Controls_Manager::SELECT,
				'default' => '3',
				'options' => [
					'1'  => __( 'One', 'soft-template-core' ),
					'2' => __( 'Two', 'soft-template-core' ),
					'3' => __( 'Three', 'soft-template-core' ),
					'4' => __( 'Four', 'soft-template-core' ),
					'5' => __( 'Five', 'soft-template-core' ),
					'6' => __( 'Six', 'soft-template-core' ),
					'7' => __( 'Seven', 'soft-template-core' ),
					'8' => __( 'Eight', 'soft-template-core' ),
					'' => __( 'Default', 'soft-template-core' ),
				],
				'condition'   => [
					'coloumns_responsive' => 'custom',
				],
			]
		);		
		
		$this->add_control(
			'columns_768',
			[
				'label' => __( 'Number of Columns 681px - 768px', 'soft-template-core' ),
				'type' => Controls_Manager::SELECT,
				'default' => '3',
				'options' => [
					'1'  => __( 'One', 'soft-template-core' ),
					'2' => __( 'Two', 'soft-template-core' ),
					'3' => __( 'Three', 'soft-template-core' ),
					'4' => __( 'Four', 'soft-template-core' ),
					'5' => __( 'Five', 'soft-template-core' ),
					'6' => __( 'Six', 'soft-template-core' ),
					'7' => __( 'Seven', 'soft-template-core' ),
					'8' => __( 'Eight', 'soft-template-core' ),
					'' => __( 'Default', 'soft-template-core' ),
				],
				'condition'   => [
					'coloumns_responsive' => 'custom',
				],
			]
		);		
		
		$this->add_control(
			'columns_680',
			[
				'label' => __( 'Number of Columns 481px - 680px', 'soft-template-core' ),
				'type' => Controls_Manager::SELECT,
				'default' => '3',
				'options' => [
					'1'  => __( 'One', 'soft-template-core' ),
					'2' => __( 'Two', 'soft-template-core' ),
					'3' => __( 'Three', 'soft-template-core' ),
					'4' => __( 'Four', 'soft-template-core' ),
					'5' => __( 'Five', 'soft-template-core' ),
					'6' => __( 'Six', 'soft-template-core' ),
					'7' => __( 'Seven', 'soft-template-core' ),
					'8' => __( 'Eight', 'soft-template-core' ),
					'' => __( 'Default', 'soft-template-core' ),
				],
				'condition'   => [
					'coloumns_responsive' => 'custom',
				],
			]
		);		
		
		$this->add_control(
			'columns_480',
			[
				'label' => __( 'Number of Columns 0 - 480px', 'soft-template-core' ),
				'type' => Controls_Manager::SELECT,
				'default' => '3',
				'options' => [
					'1'  => __( 'One', 'soft-template-core' ),
					'2' => __( 'Two', 'soft-template-core' ),
					'3' => __( 'Three', 'soft-template-core' ),
					'4' => __( 'Four', 'soft-template-core' ),
					'5' => __( 'Five', 'soft-template-core' ),
					'6' => __( 'Six', 'soft-template-core' ),
					'7' => __( 'Seven', 'soft-template-core' ),
					'8' => __( 'Eight', 'soft-template-core' ),
					'' => __( 'Default', 'soft-template-core' ),
				],
				'condition'   => [
					'coloumns_responsive' => 'custom',
				],
			]
		);

		$this->__add_responsive_control(
			'space_between_items',
			[
				'label' => __( 'Space Between Items', 'soft-template-core' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'vw', 'em' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 1000,
						'step' => 1,
					],
					'vw' => [
						'min' => 0,
						'max' => 100,
						'step' => 1,
					],	
					'em' => [
						'min' => 0,
						'max' => 100,
						'step' => 1,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 30,
				],
				'selectors' => [
					'{{WRAPPER}} .qodef-qi-grid > .qodef-grid-inner'  => 'gap: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .qodef-qi-grid.qodef-borders--between > .qodef-grid-inner > .qodef-grid-item:before' => 'bottom: calc( -{{SIZE}}{{UNIT}}/2 );',
					'{{WRAPPER}} .qodef-qi-grid.qodef-borders--between > .qodef-grid-inner > .qodef-grid-item:after'  => 'right: calc( -{{SIZE}}{{UNIT}}/2 );',
					'{{WRAPPER}} .qodef-qi-grid.qodef-borders--all > .qodef-grid-inner > .qodef-grid-item'            => 'padding: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'enable_pagination',
			[
				'label' => __( 'Enable Pagination', 'soft-template-core' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'no',
				'options' => [
					'yes'  => __( 'Yes', 'soft-template-core' ),
					'no' => __( 'No', 'soft-template-core' ),
				],
			]
		);		
		
		$this->add_control(
			'enable_zigzag',
			[
				'label' => __( 'Enable Zigzag', 'soft-template-core' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'no',
				'options' => [
					'yes'  => __( 'Yes', 'soft-template-core' ),
					'no' => __( 'No', 'soft-template-core' ),
				],
			]
		);

		$this->__add_responsive_control(
			'zigzag_amount',
			[
				'label' => __( 'Zigzag Amount', 'soft-template-core' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'vw', 'em' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 1000,
						'step' => 1,
					],
					'vw' => [
						'min' => 0,
						'max' => 100,
						'step' => 1,
					],	
					'em' => [
						'min' => 0,
						'max' => 100,
						'step' => 1,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 0,
				],
				'selectors' => [
					'{{WRAPPER}} .qodef-grid-inner >.qodef-e:nth-of-type(even) > *' => 'margin-top: {{SIZE}}{{UNIT}};',
				],
				'condition'   => [
					'enable_zigzag' => 'yes',
				],
			]
		);


		$this->end_controls_section();
    }    
    
    public function widget_layout_options() {
		$this->start_controls_section(
			'layout_control',
			[
				'label' => __( 'Layout', 'soft-template-core' ),
			]
		);


		$this->add_control(
			'item_layout',
			[
				'label' => __( 'Item Layout', 'soft-template-core' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'boxed',
				'options' => [
					'boxed'  => __( 'Boxed', 'soft-template-core' ),
					'date-boxed' => __( 'Date Boxed', 'soft-template-core' ),
					'info-on-image' => __( 'Info on Image', 'soft-template-core' ),
					'minimal' => __( 'Minimal', 'soft-template-core' ),
					'side-image' => __( 'Side Image', 'soft-template-core' ),
					'standard' => __( 'Standard', 'soft-template-core' ),
				],
			]
		);		

		$this->add_control(
			'title_tag',
			[
				'label'   => __( 'Title Tag', 'soft-template-core' ),
				'type'    => Controls_Manager::SELECT,
				'options' => [
					'h1'   => __( 'H1', 'soft-template-core' ),
					'h2'   => __( 'H2', 'soft-template-core' ),
					'h3'   => __( 'H3', 'soft-template-core' ),
					'h4'   => __( 'H4', 'soft-template-core' ),
					'h5'   => __( 'H5', 'soft-template-core' ),
					'h6'   => __( 'H6', 'soft-template-core' ),
					'div'  => __( 'div', 'soft-template-core' ),
					'span' => __( 'span', 'soft-template-core' ),
				],
				'default' => 'h5',
			]
		);

		$this->add_control(
			'show_excerpt',
			[
				'label' => __( 'Show Excerpt', 'soft-template-core' ),
				'type' => Controls_Manager::SELECT,
				'default' => '',
				'options' => [
					''  => __( 'Default', 'soft-template-core' ),
					'yes' => __( 'Yes', 'soft-template-core' ),
					'no' => __( 'No', 'soft-template-core' ),
				],
			]
		);	

		$this->add_control(
			'excerpt_length',
			[
				'label'     => __( 'Excerpt Length', 'soft-template-core' ),
				'type'      => Controls_Manager::NUMBER,
				'default'   => 6,
				'condition' => [
					'show_excerpt!' => 'no',
				],
			]
		);

		$this->add_control(
			'center_content',
			[
				'label' => __( 'Center Content', 'soft-template-core' ),
				'type' => Controls_Manager::SELECT,
				'default' => '',
				'options' => [
					''  => __( 'Default', 'soft-template-core' ),
					'yes' => __( 'Yes', 'soft-template-core' ),
					'no' => __( 'No', 'soft-template-core' ),
				],
			]
		);			
		
		$this->add_control(
			'show_media',
			[
				'label' => __( 'Show Media', 'soft-template-core' ),
				'type' => Controls_Manager::SELECT,
				'default' => '',
				'options' => [
					''  => __( 'Default', 'soft-template-core' ),
					'yes' => __( 'Yes', 'soft-template-core' ),
					'no' => __( 'No', 'soft-template-core' ),
				],
			]
		);			
		
		$this->add_control(
			'show_info_icons',
			[
				'label' => __( 'Show Info Icons', 'soft-template-core' ),
				'type' => Controls_Manager::SELECT,
				'default' => '',
				'options' => [
					''  => __( 'Default', 'soft-template-core' ),
					'yes' => __( 'Yes', 'soft-template-core' ),
					'no' => __( 'No', 'soft-template-core' ),
				],
			]
		);			
		
		$this->add_control(
			'show_date',
			[
				'label' => __( 'Show Date', 'soft-template-core' ),
				'type' => Controls_Manager::SELECT,
				'default' => '',
				'options' => [
					''  => __( 'Default', 'soft-template-core' ),
					'yes' => __( 'Yes', 'soft-template-core' ),
					'no' => __( 'No', 'soft-template-core' ),
				],
			]
		);			
		
		$this->add_control(
			'show_category',
			[
				'label' => __( 'Show Category', 'soft-template-core' ),
				'type' => Controls_Manager::SELECT,
				'default' => '',
				'options' => [
					''  => __( 'Default', 'soft-template-core' ),
					'yes' => __( 'Yes', 'soft-template-core' ),
					'no' => __( 'No', 'soft-template-core' ),
				],
			]
		);			
		
		$this->add_control(
			'show_author',
			[
				'label' => __( 'Show Author', 'soft-template-core' ),
				'type' => Controls_Manager::SELECT,
				'default' => '',
				'options' => [
					''  => __( 'Default', 'soft-template-core' ),
					'yes' => __( 'Yes', 'soft-template-core' ),
					'no' => __( 'No', 'soft-template-core' ),
				],
			]
		);			
		
		$this->add_control(
			'show_button',
			[
				'label' => __( 'Show Button', 'soft-template-core' ),
				'type' => Controls_Manager::SELECT,
				'default' => '',
				'options' => [
					''  => __( 'Default', 'soft-template-core' ),
					'yes' => __( 'Yes', 'soft-template-core' ),
					'no' => __( 'No', 'soft-template-core' ),
				],
			]
		);	


		$this->end_controls_section();
    }    
	
	public function widget_readmore_button() {
		$this->start_controls_section(
			'read_more_control',
			[
				'label' => __( 'Read More Button', 'soft-template-core' ),
			]
		);

		$this->add_control(
			'read_more_text',
			[
				'label' => __( 'Read More Text', 'soft-template-core' ),
				'description' => __( 'If nothing is entered, \'Read More\' text will be used', 'soft-template-core' ),
				'type' => Controls_Manager::TEXT,
				'default' => '',
			]
		);

		$this->add_control(
			'button_layout',
			[
				'label' => __( 'Layout', 'soft-template-core' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'filled',
				'options' => [
					'filled'  => __( 'Filled', 'soft-template-core' ),
					'outlined' => __( 'Outlined', 'soft-template-core' ),
					'textual' => __( 'Textual', 'soft-template-core' ),
				],
			]
		);			
		
		$this->add_control(
			'button_layout_type',
			[
				'label' => __( 'Type', 'soft-template-core' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'standard',
				'options' => [
					'standard'  => __( 'Standard', 'soft-template-core' ),
					'inner-border' => __( 'With Inner Border', 'soft-template-core' ),
					'icon-boxed' => __( 'Icon Boxed', 'soft-template-core' ),
				],
				'condition'   => [
					'button_layout!' => 'textual',
				],
			]
		);			
		
		$this->add_control(
			'button_text_underline',
			[
				'label' => __( 'Enable Button Text Underline', 'soft-template-core' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'no',
				'options' => [
					'no' => __( 'No', 'soft-template-core' ),
					'yes' => __( 'Yes', 'soft-template-core' ),
				],
			]
		);	

		$this->add_control(
			'button_size',
			[
				'label' => __( 'Size', 'soft-template-core' ),
				'type' => Controls_Manager::SELECT,
				'default' => '',
				'options' => [
					''  => __( 'Normal', 'soft-template-core' ),
					'small'  => __( 'Small', 'soft-template-core' ),
					'large' => __( 'Large', 'soft-template-core' ),
					'full' => __( 'Normal Full Width', 'soft-template-core' ),
				],
				'condition'   => [
					'button_layout!' => 'textual',
				],
			]
		);		

		$this->add_control(
			'readmore_icon',
			[
				'label' => __( 'Icons', 'text-domain' ),
				'type' => Controls_Manager::ICONS,
				'default' => [
					'value' => 'fas fa-arrow-right',
					'library' => 'solid',
				],
			]
		);

		$this->end_controls_section();
    }	
	
	public function widget_pagination_style() {
		$this->__start_controls_section(
			'pagination_style',
			array(
				'label'      => esc_html__( 'Pagination Style', 'soft-template-core' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			)
		);

			$this->start_controls_tabs( 'button_style' );
				$this->start_controls_tab( 'button_normal', [ 'label' => __( 'Normal', 'soft-template-core' ) ] );

					$this->add_control(
						'button_text_color',
						[
							'label'     => __( 'Color', 'soft-template-core' ),
							'type'      => Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .submit' => 'color:{{VALUE}};',
							],
						]
					);

					$this->add_control(
						'button_color',
						[
							'label'     => __( 'Background Color', 'soft-template-core' ),
							'type'      => Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .submit' => 'background:{{VALUE}};',
							],
						]
					);



				$this->end_controls_tab();

				$this->start_controls_tab( 'button_hover', [ 'label' => __( 'Active/Hover', 'soft-template-core' ) ] );

					$this->add_control(
						'button_text_color_hover',
						[
							'label'     => __( 'Color', 'soft-template-core' ),
							'type'      => Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .submit:hover' => 'color:{{VALUE}};',
							],
						]
					);

					$this->add_control(
						'button_color_hover',
						[
							'label'     => __( 'Background Color', 'soft-template-core' ),
							'type'      => Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .submit:hover' => 'background:{{VALUE}};',
							],
						]
					);

		
				$this->end_controls_tab();
			$this->end_controls_tabs();		

		$this->end_controls_section();
    }

	public function widget_content_style() {
		$this->__start_controls_section(
			'content_style',
			array(
				'label'      => esc_html__( 'Content Style', 'soft-template-core' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			)
		);

		$this->add_control(
			'title_color',
			[
				'label'     => __( 'Title Color', 'soft-template-core' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .qodef-e-title' => 'color:{{VALUE}};',
					'{{WRAPPER}} .qodef-e-title a' => 'color:inherit;',
				],
			]
		);		
		
		$this->add_control(
			'title_hover_color',
			[
				'label'     => __( 'Title Hover Color', 'soft-template-core' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .qodef-e-title:hover a' => 'color:{{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'      => 'title_typography',
				'global'    => array(
					'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
				),
				'selector'  => '{{WRAPPER}} .qodef-e-title',
			)
		);


		$this->add_control(
			'title_hover_underline',
			[
				'label' => __( 'Title Hover Underline', 'soft-template-core' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'no',
				'options' => [
					'no'  => __( 'No', 'soft-template-core' ),
					'yes' => __( 'Yes', 'soft-template-core' ),
				],
			]
		);

		$this->add_control(
			'info_color',
			[
				'label'     => __( 'Info Color', 'soft-template-core' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .qodef-e-content .qodef-e-info-item' => 'color:{{VALUE}};',
				],
			]
		);		
		
		$this->add_control(
			'info_hover_color',
			[
				'label'     => __( 'Info Hover Color', 'soft-template-core' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .qodef-e-content .qodef-e-info-item:hover > *' => 'color:{{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'      => 'info_typography',
				'label'     => __( 'Info Typography', 'soft-template-core' ),
				'global'    => array(
					'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
				),
				'selector'  => '{{WRAPPER}} .qodef-e-info',
			)
		);

		$this->add_control(
			'content_info_color',
			[
				'label'     => __( 'Content Color', 'soft-template-core' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .qodef-e-excerpt' => 'color:{{VALUE}};',
				],
			]
		);		
		

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'label'     => __( 'Content Typography', 'soft-template-core' ),
				'name'      => 'content_info_typography',
				'global'    => array(
					'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
				),
				'selector'  => '{{WRAPPER}} .qodef-e-excerpt',
			)
		);

		$this->add_control(
			'image_hover',
			[
				'label' => __( 'Image Hover', 'soft-template-core' ),
				'type' => Controls_Manager::SELECT,
				'default' => '',
				'options' => [
					'zoom'  => __( 'Zoom In', 'soft-template-core' ),
					'zoom-out' => __( 'Zoom Out', 'soft-template-core' ),
					'move' => __( 'Move', 'soft-template-core' ),
					'' => __( 'None', 'soft-template-core' ),
				],
			]
		);		
		
		$this->add_control(
			'image_zoom_origin',
			[
				'label' => __( 'Image Hover Origin', 'soft-template-core' ),
				'type' => Controls_Manager::SELECT,
				'default' => '',
				'options' => [
					'' => __( 'Center', 'soft-template-core' ),
					'top'  => __( 'Top', 'soft-template-core' ),
					'bottom' => __( 'Bottom', 'soft-template-core' ),
					'left' => __( 'Left', 'soft-template-core' ),
					'right' => __( 'Right', 'soft-template-core' ),
				],
			]
		);

		$this->add_control(
			'overlay_color',
			[
				'label'     => __( 'Overlay Color', 'soft-template-core' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .qodef-e-media-image a:after' => 'background-color: {{VALUE}};',
				],
			]
		);			
		
		$this->add_control(
			'overlay_hover_color',
			[
				'label'     => __( 'Overlay Hover Color', 'soft-template-core' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .qodef-e:hover .qodef-e-media-image a:after' => 'background-color: {{VALUE}};',
				],
			]
		);	
		
		$this->add_control(
			'content_background',
			[
				'label'     => __( 'Content Background', 'soft-template-core' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .qodef-item-layout--boxed .qodef-e-content' => 'background-color: {{VALUE}};',
				],
			]
		);	

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'content_box_shadow',
				'label' => __( 'Item Shadow', 'soft-template-core' ),
				'selector' => '{{WRAPPER}} .qodef-item-layout--boxed .qodef-e-inner',
			]
		);

		$this->end_controls_section();
	}

	public function widget_spacing_style() {
		$this->__start_controls_section(
			'content_spacing_style',
			array(
				'label'      => esc_html__( 'Layout Spacing', 'soft-template-core' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			)
		);

		$this->__add_responsive_control(
			'post_info_margin_bottom',
			[
				'label' => __( 'Post Info Margin Bottom', 'soft-template-core' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'vw', 'em' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 1000,
						'step' => 1,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
						'step' => 1,
					],	
					'em' => [
						'min' => 0,
						'max' => 100,
						'step' => 1,
					],
				],
				'default' => [
					'unit' => 'px',
				],
				'selectors' => [
					'{{WRAPPER}} .qodef-e-info.qodef-info--top' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->__add_responsive_control(
			'title_margin_bottom',
			[
				'label' => __( 'Title Margin Bottom', 'soft-template-core' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'vw', 'em' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 1000,
						'step' => 1,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
						'step' => 1,
					],	
					'em' => [
						'min' => 0,
						'max' => 100,
						'step' => 1,
					],
				],
				'default' => [
					'unit' => 'px',
				],
				'selectors' => [
					'{{WRAPPER}} .qodef-e-title' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->__add_responsive_control(
			'text_margin_bottom',
			[
				'label' => __( 'Text Margin Bottom', 'soft-template-core' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'vw', 'em' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 1000,
						'step' => 1,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
						'step' => 1,
					],	
					'em' => [
						'min' => 0,
						'max' => 100,
						'step' => 1,
					],
				],
				'default' => [
					'unit' => 'px',
				],
				'selectors' => [
					'{{WRAPPER}} .qodef-e-text' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
				'condition'   => [
					'item_layout' => 'boxed',
				],
			]
		);

		$this->__add_responsive_control(
			'content_padding',
			[
				'label' => __( 'Content Padding', 'soft-template-core' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .qodef-item-layout--boxed .qodef-e-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition'   => [
					'item_layout' => 'boxed',
				],
			]
		);

		$this->end_controls_section();
	}

	public function widget_read_more_style() {
		$this->__start_controls_section(
			'read_more_style',
			array(
				'label'      => esc_html__( 'Read More Button', 'soft-template-core' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'      => 'readmore_button_typo',
				'global'    => array(
					'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
				),
				'selector'  => '{{WRAPPER}} .qodef-qi-button',
			)
		);

		$this->start_controls_tabs( 'readmore_button_style' );
			$this->start_controls_tab( 'readmore_button_normal', [ 'label' => __( 'Normal', 'soft-template-core' ) ] );

				$this->add_control(
					'readmore_button_text_color',
					[
						'label'     => __( 'Color', 'soft-template-core' ),
						'type'      => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .qodef-qi-button' => 'color:{{VALUE}};',
						],
					]
				);

				$this->add_control(
					'readmore_button_color',
					[
						'label'     => __( 'Background Color', 'soft-template-core' ),
						'type'      => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .qodef-qi-button.qodef-layout--filled' => 'background-color:{{VALUE}};',
						],
						'condition'   => [
							'button_layout!' => 'textual',
						],
					]
				);				
				
				$this->add_control(
					'readmore_button_border_color',
					[
						'label'     => __( 'Border Color', 'soft-template-core' ),
						'type'      => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .qodef-qi-button' => 'border-color:{{VALUE}};',
						],
						'condition'   => [
							'button_layout!' => 'textual',
						],
					]
				);

			$this->end_controls_tab();

			$this->start_controls_tab( 'readmore_button_hover', [ 'label' => __( 'Active/Hover', 'soft-template-core' ) ] );

				$this->add_control(
					'readmore_button_text_color_hover',
					[
						'label'     => __( 'Color', 'soft-template-core' ),
						'type'      => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .qodef-qi-button:hover' => 'color:{{VALUE}};',
						],
					]
				);

				$this->add_control(
					'readmore_button_color_hover',
					[
						'label'     => __( 'Background Color', 'soft-template-core' ),
						'type'      => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .qodef-qi-button.qodef-layout--filled:not(.qodef-hover--reveal):hover'   => 'background-color: {{VALUE}};',
							'{{WRAPPER}} .qodef-qi-button.qodef-layout--outlined:not(.qodef-hover--reveal):hover' => 'background-color: {{VALUE}};',
							'{{WRAPPER}} .qodef-qi-button.qodef-layout--filled.qodef-hover--reveal:after'   => 'background-color: {{VALUE}};',
							'{{WRAPPER}} .qodef-qi-button.qodef-layout--outlined.qodef-hover--reveal:after' => 'background-color: {{VALUE}};',
						],
						'condition'   => [
							'button_layout!' => 'textual',
						],
					]
				);

				$this->add_control(
					'readmore_button_border__hover_color',
					[
						'label'     => __( 'Border Color', 'soft-template-core' ),
						'type'      => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .qodef-qi-button:hover' => 'border-color: {{VALUE}};',
						],
						'condition'   => [
							'button_layout!' => 'textual',
						],
					]
				);


			$this->end_controls_tab();
		$this->end_controls_tabs();	
		
		$this->__add_responsive_control(
			'readmore_border_width',
			[
				'label' => __( 'Border Width', 'soft-template-core' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .qodef-qi-button' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition'   => [
					'button_layout!' => 'textual',
				],
			]
		);

		$this->__add_responsive_control(
			'readmore_border_radius',
			[
				'label' => __( 'Border Radius', 'soft-template-core' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .qodef-qi-button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition'   => [
					'button_layout!' => 'textual',
				],
			]
		);		
		
		$this->__add_responsive_control(
			'readmore_btn_padding',
			[
				'label' => __( 'Padding', 'soft-template-core' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .qodef-qi-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .qodef-qi-button.qodef-type--icon-boxed .qodef-m-text' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .qodef-qi-button.qodef-type--icon-boxed .qodef-m-icon' => 'padding: {{TOP}}{{UNIT}} 0 {{BOTTOM}}{{UNIT}};',
				],
				'condition'   => [
					'button_layout!' => 'textual',
				],
			]
		);

		$this->__add_responsive_control(
			'readmore_btn_icon_size',
			[
				'label' => __( 'Icon Size', 'soft-template-core' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em', 'rem' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 1000,
						'step' => 1,
					],
					'em' => [
						'min' => 0,
						'max' => 100,
						'step' => 1,
					],
					'rem' => [
						'min' => 0,
						'max' => 100,
						'step' => 1,
					],	
				],
				'default' => [
					'unit' => 'px',
				],
				'selectors' => [
					'{{WRAPPER}} .qodef-m-icon' => 'font-size: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'before'
			]
		);

		$this->__add_responsive_control(
			'readmore_btn_icon_margin',
			[
				'label' => __( 'Margin', 'soft-template-core' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .qodef-m-icon' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'readmore_btn_icon_color',
			[
				'label'     => __( 'Icon Color', 'soft-template-core' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .qodef-m-icon' => 'color:{{VALUE}};',
				],
			]
		);		
		
		$this->add_control(
			'readmore_btn_icon_hover_color',
			[
				'label'     => __( 'Icon Hover Color', 'soft-template-core' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .qodef-m-icon:hover' => 'color:{{VALUE}};',
				],
			]
		);

		$this->end_controls_section();
	}

	public function widget_date_box() {
		$this->__start_controls_section(
			'date_boxed_styles',
			array(
				'label'      => esc_html__( 'Date Style', 'soft-template-core' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			)
		);

		$this->add_control(
			'date_colors',
			[
				'label'     => __( 'Date Color', 'soft-template-core' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .qodef-e-info-date a' => 'color:{{VALUE}};',
				],
			]
		);	
		
		$this->add_control(
			'date_hover_colors',
			[
				'label'     => __( 'Date Hover Color', 'soft-template-core' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .qodef-e-info-date a:hover' => 'color:{{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'      => 'date_typography',
				'global'    => array(
					'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
				),
				'selector'  => '{{WRAPPER}} .qodef-e-info-date',
			)
		);

		$this->add_control(
			'date_background_colors',
			[
				'label'     => __( 'Date Background Color', 'soft-template-core' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .qodef-item-layout--boxed .qodef-e-info-date' => 'background-color:{{VALUE}};',
				],
				'conditions'  => array(
					'relation' => 'or',
					'terms'    => array(
						array(
							'name'     => 'item_layout',
							'operator' => '==',
							'value'    => 'date-boxed',
						),
						array(
							'name'     => 'item_layout',
							'operator' => '==',
							'value'    => 'boxed',
						),		
						array(
							'name'     => 'item_layout',
							'operator' => '==',
							'value'    => 'info-on-image',
						),
					),
				),
			]
		);

		$this->__add_responsive_control(
			'boxed_date_padding',
			[
				'label' => __( 'Date Padding', 'soft-template-core' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .qodef-item-layout--boxed .qodef-e-info-date' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .qodef-item-layout--date-boxed .qodef-e-info-date' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .qodef-item-layout--info-on-image .qodef-e-info-date' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'conditions'  => array(
					'relation' => 'or',
					'terms'    => array(
						array(
							'name'     => 'item_layout',
							'operator' => '==',
							'value'    => 'date-boxed',
						),
						array(
							'name'     => 'item_layout',
							'operator' => '==',
							'value'    => 'boxed',
						),		
						array(
							'name'     => 'item_layout',
							'operator' => '==',
							'value'    => 'info-on-image',
						),
					),
				),
			]
		);

		$this->__add_responsive_control(
			'boxed_date_border_radius',
			[
				'label' => __( 'Date Border Radius', 'soft-template-core' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'em' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
						'step' => 1,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
						'step' => 1,
					],	
					'em' => [
						'min' => 0,
						'max' => 100,
						'step' => 1,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 0,
				],
				'selectors' => [
					'{{WRAPPER}} .qodef-item-layout--boxed .qodef-e-info-date' => 'border-radius: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .qodef-item-layout--date-boxed .qodef-e-info-date' => 'border-radius: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .qodef-item-layout--info-on-image .qodef-e-info-date' => 'border-radius: {{SIZE}}{{UNIT}};',
				],
				'conditions'  => array(
					'relation' => 'or',
					'terms'    => array(
						array(
							'name'     => 'item_layout',
							'operator' => '==',
							'value'    => 'date-boxed',
						),
						array(
							'name'     => 'item_layout',
							'operator' => '==',
							'value'    => 'boxed',
						),		
						array(
							'name'     => 'item_layout',
							'operator' => '==',
							'value'    => 'info-on-image',
						),
					),
				),
			]
		);

		$this->__add_responsive_control(
			'boxed_date_vertical_offset',
			[
				'label' => __( 'Date Vertical Offset', 'soft-template-core' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'em' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 200,
						'step' => 1,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
						'step' => 1,
					],	
					'em' => [
						'min' => 0,
						'max' => 100,
						'step' => 1,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 0,
				],
				'selectors' => [
					'{{WRAPPER}} .qodef-item-layout--boxed .qodef-e-info-date' => 'top: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .qodef-item-layout--date-boxed .qodef-e-info-date' => 'top: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .qodef-item-layout--info-on-image .qodef-e-info-date' => 'top: {{SIZE}}{{UNIT}};',
				],
				'conditions'  => array(
					'relation' => 'or',
					'terms'    => array(
						array(
							'name'     => 'item_layout',
							'operator' => '==',
							'value'    => 'date-boxed',
						),
						array(
							'name'     => 'item_layout',
							'operator' => '==',
							'value'    => 'boxed',
						),		
						array(
							'name'     => 'item_layout',
							'operator' => '==',
							'value'    => 'info-on-image',
						),
					),
				),
			]
		);

		$this->__add_responsive_control(
			'boxed_date_horizontal_offset',
			[
				'label' => __( 'Date Vertical Offset', 'soft-template-core' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'em' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 200,
						'step' => 1,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
						'step' => 1,
					],	
					'em' => [
						'min' => 0,
						'max' => 100,
						'step' => 1,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 0,
				],
				'selectors' => [
					'{{WRAPPER}} .qodef-item-layout--boxed .qodef-e-info-date' => 'right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .qodef-item-layout--date-boxed .qodef-e-info-date' => 'right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .qodef-item-layout--info-on-image .qodef-e-info-date' => 'right: {{SIZE}}{{UNIT}};',
				],
				'conditions'  => array(
					'relation' => 'or',
					'terms'    => array(
						array(
							'name'     => 'item_layout',
							'operator' => '==',
							'value'    => 'date-boxed',
						),
						array(
							'name'     => 'item_layout',
							'operator' => '==',
							'value'    => 'boxed',
						),		
						array(
							'name'     => 'item_layout',
							'operator' => '==',
							'value'    => 'info-on-image',
						),
					),
				),
			]
		);

		$this->end_controls_section();
	}

    protected function render() {
		$settings = $this->get_settings_for_display();

		$this->generate_output( $settings );
    }

	public function generate_output( $settings, $with_wrapper = true ) {
		$post_type    = $settings['ae_post_type'];

		// Securing Current post of Parent Query
		$prev_post = get_post();

		list($post_items, $query_args) = $this->get_posts( $settings );

		if ( (isset( $query_args ) && empty( $query_args )) && $post_type !== 'current_loop' ) {
			return;
		}

		$alt_layout = array();

		$holder_classes = array();
		$holder_classes[] = 'qodef-m';
		$holder_classes[] = 'qodef-addons-blog-list';
		$holder_classes[] = 'qodef-info-no-icons';
		$holder_classes[] = 'qodef-qi-grid';

		$holder_classes[] = ! empty( $settings['behavior'] ) ? 'qodef-layout--qi-' . $settings['behavior'] : 'qodef-layout--qi-columns';
		$holder_classes[] = ! empty( $settings['behavior'] ) && 'masonry' === $settings['behavior'] && ! empty( $settings['masonry_images_proportion'] ) && 'fixed' === $settings['masonry_images_proportion'] ? 'qodef-items--fixed' : '';
		$holder_classes[] = ! empty( $settings['number_of_coloumns'] ) ? 'qodef-col-num--' . $settings['number_of_coloumns'] : '';
		$holder_classes[] = ! empty( $settings['borders'] ) ? 'qodef-borders--' . $settings['borders'] : '';

		$holder_classes[] = ! empty( $settings['item_layout'] ) ? 'qodef-item-layout--' . $settings['item_layout'] : '';

		if ( ! empty( $settings['coloumns_responsive'] ) && 'custom' === $settings['coloumns_responsive'] ) {
			$holder_classes[] = 'qodef-responsive--custom';
			$holder_classes[] = ! empty( $settings['columns_1440'] ) ? 'qodef-col-num--1440--' . $settings['columns_1440'] : 'qodef-col-num--1440--' . $settings['columns'];
			$holder_classes[] = ! empty( $settings['columns_1366'] ) ? 'qodef-col-num--1366--' . $settings['columns_1366'] : 'qodef-col-num--1366--' . $settings['columns'];
			$holder_classes[] = ! empty( $settings['columns_1024'] ) ? 'qodef-col-num--1024--' . $settings['columns_1024'] : 'qodef-col-num--1024--' . $settings['columns'];
			$holder_classes[] = ! empty( $settings['columns_768'] ) ? 'qodef-col-num--768--' . $settings['columns_768'] : 'qodef-col-num--768--' . $settings['columns'];
			$holder_classes[] = ! empty( $settings['columns_680'] ) ? 'qodef-col-num--680--' . $settings['columns_680'] : 'qodef-col-num--680--' . $settings['columns'];
			$holder_classes[] = ! empty( $settings['columns_480'] ) ? 'qodef-col-num--480--' . $settings['columns_480'] : 'qodef-col-num--480--' . $settings['columns'];
		} else {
			$holder_classes[] = 'qodef-responsive--predefined';
		}

		if ( ! empty( $settings['layout'] ) && 'standard' === $settings['layout'] ) {
			$holder_classes[] = 'qodef--list';
		}

		if ( ! empty( $settings['center_content'] ) && 'yes' === $settings['center_content'] ) {
			$holder_classes[] = 'qodef-alignment--centered';
		}

		$holder_classes[] = ( 'yes' !== $settings['show_info_icons'] ) ? 'qodef-info-no-icons' : '';
		$holder_classes[] = 'yes' === $settings['title_hover_underline'] ? 'qodef-title--hover-underline' : '';
		$holder_classes[] = ! empty( $settings['image_hover'] ) ? 'qodef-image--hover-' . $settings['image_hover'] : '';
		$holder_classes[] = ! empty( $settings['image_zoom_origin'] ) ? 'qodef-image--hover-from-' . $settings['image_zoom_origin'] : '';
		?>
		
		<div class="<?php echo esc_attr( implode(' ', $holder_classes ) ); ?>">
			<div class="qodef-grid-inner">
			<?php if ( 'masonry' === $settings['behavior'] ) { ?>
				<div class="qodef-qi-grid-masonry-sizer"></div>
			<?php } ?>
			
			<?php

				if( $post_items->have_posts() ) {
					$seq = 0;

					global $post;
					global $wp_query;

					$old_queried_object = $wp_query->queried_object;
					if ( ! isset( $query_args ) ) {
						while ( have_posts() ) {

							$template = $this->get_template( $seq, $settings, $alt_layout );
							$this->item_html( $settings, $template );
						}
					} else {
						while ( $post_items->have_posts() ) {
							$seq++;	

							$template = $this->get_template( $seq, $settings, $alt_layout );

							$post_items->the_post();

							$this->item_html( $settings, $template );
						}
					}

					$wp_query->queried_object = $old_queried_object;

				} else {
					?>
					<div class="ae-no-posts">
						<?php echo do_shortcode( $settings['no_post_message'] ); ?>
					</div>
					<?php
				}
			
			?>
			</div>
		</div>
		<?php

		$post = $prev_post;
		setup_postdata( $post );
	}

    public function get_posts( $settings ) {
        $post_items = null;
		$query_args = [];
		$post_type  = $settings['ae_post_type'];

		$ae_post_ids = $settings['ae_post_ids'];

		if ( isset( $_GET['post'] ) ) {
			$current_post_id = $_GET['post'];
		} else {
			$current_post_id = get_the_ID();
		}

        $paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : '';

		$paged = $this->get_current_page_num();
		
		switch ( $post_type ) {
			case 'current_loop':
				if ( ! Plugin::instance()->editor->is_edit_mode() ) {
					global $wp_query;
					$main_query = clone $wp_query;
					$post_items = $main_query;

					$current_queried_post_type = get_post_type( get_queried_object_id() );
					if( $current_queried_post_type == 'soft-template-core' ) {
						$query_args['post_type'] = 'post';
						$post_items = new WP_Query( $query_args );
					}
				} else {
					$term_data  = \Soft_template_Core_Utils::get_preview_term_data();
					
					$template_meta_data = get_post_meta( $current_post_id, '_elementor_page_settings', true );

					if( !empty($template_meta_data['conditions_sub_archive']) && $template_meta_data['conditions_sub_archive'] == 'archive-all' ) {
						$query_args['post_type'] = 'post';
					} elseif ( ! empty( $template_meta_data['conditions_archive-category_cats'] ) && !empty( $template_meta_data["conditions_sub_archive"]  ) ) {
						$query_args['tax_query'] = [
							[
								'taxonomy' => $term_data['taxonomy'],
								'field'    => 'term_id',
								'terms'    => $term_data['prev_term_id'],
							],
						];
						$query_args['post_type'] = 'any';
					} else {
						$query_args['post_type'] = 'post';
					}
				}
				break;
			case 'post_by_id':
				$query_args['post_type'] = 'any';
				$query_args['post__in']  = $ae_post_ids;
				$query_args['orderby']   = $settings['orderby'];
				$query_args['order']     = $settings['order'];

				if ( empty( $query_args['post__in'] ) ) {
					// If no selection - return an empty query
					$query_args['post__in'] = [ -1 ];
				}
				break;

			case 'related':
				if ( isset( $_POST['fetch_mode'] ) ) {
					$cpost_id = $_POST['cpid'];
					$cpost    = get_post( $cpost_id );
				} else {
					$cpost    = \Soft_template_Core_Utils::get_demo_post_data();
					$cpost_id = $cpost->ID;
				}

				$query_args = [
					'orderby'             => $settings['orderby'],
					'order'               => $settings['order'],
					'ignore_sticky_posts' => 1,
					'post_status'         => 'publish', // Hide drafts/private posts for admins
					'offset'              => $settings['offset'],
					'posts_per_page'      => $settings['posts_per_page'],
					'post__not_in'        => [ $cpost_id ],
					'post_type'           => 'any',
				];

				if ( $settings['orderby'] === 'meta_value' || $settings['orderby'] === 'meta_value_num' ) {
					$query_args['meta_key'] = $settings['orderby_metakey_name'];
				}

				if ( isset( $_POST['page_num'] ) ) {
					$query_args['offset'] = ( $query_args['posts_per_page'] * ( $_POST['page_num'] - 1 ) ) + $query_args['offset'];
				}

				$taxonomies = $settings['related_by'];

				if ( $taxonomies ) {
					foreach ( $taxonomies as $taxonomy ) {

						$terms = get_the_terms( $cpost_id, $taxonomy );
						if ( $terms ) {
							foreach ( $terms as $term ) {
								$term_ids[] = $term->term_id;
							}

							if ( $settings['related_match_with'] === 'OR' ) {
								$operator = 'IN';
							} else {
								$operator = 'AND';
							}

							$query_args['tax_query'][] = [
								'taxonomy' => $taxonomy,
								'field'    => 'term_id',
								'terms'    => $term_ids,
								'operator' => $operator,
							];
						}
					}
				}
				break;

			default:
				$query_args = [
					'orderby'             => $settings['orderby'],
					'order'               => $settings['order'],
					'ignore_sticky_posts' => 1,
					'post_status'         => 'publish', // Hide drafts/private posts for admins
				];
				
				if ( $settings['orderby'] === 'meta_value' || $settings['orderby'] === 'meta_value_num' ) {
					$query_args['meta_key'] = $settings['orderby_metakey_name'];
				}

				$query_args['post_type']      = $post_type;
				$query_args['offset']         = $settings['offset'];
				$query_args['posts_per_page'] = $settings['posts_per_page'];
				$query_args['tax_query']      = [];

				if ( is_singular() && ( $settings['current_post'] === 'yes' ) ) {
					$query_args['post__not_in'] = [ $current_post_id ];
				}

				$taxonomies = get_object_taxonomies( $post_type, 'objects' );

				foreach ( $taxonomies as $object ) {

					if ( isset( $settings['filter_taxonomy'] ) && $object->name === $settings['filter_taxonomy'] && isset( $_POST['term_id'] ) && $_POST['term_id'] > 0 ) {
						$query_args['tax_query'][] = [
							'taxonomy' => $settings['filter_taxonomy'],
							'field'    => 'term_id',
							'terms'    => $_POST['term_id'],
						];
					} else {
						$setting_key = $object->name . '_ae_ids';
						if ( isset( $settings['show_all'] ) ) {
							if ( $settings['show_all'] === 'yes' ) {
								if ( ! empty( $settings[ $setting_key ] ) ) {
									$query_args['tax_query'][] = [
										'taxonomy' => $object->name,
										'field'    => 'term_id',
										'terms'    => $settings[ $setting_key ],
									];
								}
							} else {
								if ( isset( $_POST['term_id'] ) ) {
									if ( ! empty( $settings[ $setting_key ] ) ) {
										$query_args['tax_query'][] = [
											'taxonomy' => $object->name,
											'field'    => 'term_id',
											'terms'    => $settings[ $setting_key ],
										];
									}
								} else {
									if ( isset( $settings[ $settings['filter_taxonomy'] . '_ae_ids' ] ) ) {
										$filter_terms              = get_terms(
											$settings['filter_taxonomy'],
											[
												'hide_empty' => true,
												'term_taxonomy_id' => $settings[ $settings['filter_taxonomy'] . '_ae_ids' ],
											]
										);
										$query_args['tax_query'][] = [
											'taxonomy' => $settings['filter_taxonomy'],
											'field'    => 'term_id',
											'terms'    => $filter_terms[0]->term_id,
										];
									}
								}
							}
						} else {
							if ( ! empty( $settings[ $setting_key ] ) ) {
								$query_args['tax_query'][] = [
									'taxonomy' => $object->name,
									'field'    => 'term_id',
									'terms'    => $settings[ $setting_key ],
								];
							}
						}
					}
				}

				if ( isset( $query_args['tax_query'] ) && count( $query_args['tax_query'] ) > 1 ) {
					$query_args['tax_query']['relation'] = $settings['tax_relation'];
				}

				if ( isset( $_POST['page_num'] ) || $paged > 1 ) {
					$query_args['offset'] = $this->calculate_offset( $settings, $query_args, $paged );
				}

				if ( is_array( $settings['author_ae_ids'] ) && count( $settings['author_ae_ids'] ) ) {
					$query_args['author'] = implode( ',', $settings['author_ae_ids'] );
				}
		}

		/**
		 * Filter - Add Custom Source Query
		 */
		$query_args = apply_filters( 'soft-template-core/post-archive/custom-source-query', $query_args, $settings );

		if ( $post_type === 'current_loop' && ! Plugin::instance()->editor->is_edit_mode() ) {
			$query_args = array();
		} else {
			if ( isset( $query_args ) ) {
				if ( ! empty( $settings['query_filter'] ) ) {
					$query_args = apply_filters( $settings['query_filter'], $query_args );
				}
				$post_items = new WP_Query( $query_args );
			}
		}

		return [ $post_items, $query_args ];
    }

	public function item_html( $settings, $template ) {

		if( $template === 'boxed' ) {
			include $this->__get_global_template( 'boxed' );
		}  		
		
		if( $template === 'date-boxed' ) {
			include $this->__get_global_template( 'date-boxed' );
		}  		
		
		if( $template === 'info-on-image' ) {
			include $this->__get_global_template( 'info-on-image' );
		}  		
		
		if( $template === 'minimal' ) {
			include $this->__get_global_template( 'minimal' );
		}  
		
		if( $template === 'side-image' ) {
			include $this->__get_global_template( 'side-image' );
		} 	
		
		if( $template === 'standard' ) {
			include $this->__get_global_template( 'standard' );
		}  
		?>

		<?php

	}

	public function get_template( $seq, $settings, $alt_layout ) {
		$template = $settings['item_layout'];

		return $template;
	}

	public function calculate_offset( $settings, $query_args, $paged ) {

		if ( $settings['show_pagination'] === 'no' ) {
			return 0;
		}

		if ( $settings['disable_ajax'] === 'yes' && $paged > 1 ) {
			$offset = ( $query_args['posts_per_page'] * ( $paged - 1 ) );

		} else {
			$offset = $query_args['posts_per_page'] * ( $this->get_current_page_num() - 1 );
		}

		if ( is_numeric( $query_args['offset'] ) ) {
			$offset += $query_args['offset'];
		}

		return $offset;
	}


    public function get_current_page_num() {
		$current = 1;

		if ( isset( $_POST['page_num'] ) ) {
			$current = $_POST['page_num'];
			return $current;
		}

		if ( is_front_page() && ! is_home() ) {
			$current = ( get_query_var( 'page' ) ) ? get_query_var( 'page' ) : 1;
		} else {
			$current = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
		}

		return $current;
	}
}