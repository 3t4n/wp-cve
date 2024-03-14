<?php
/**
 * Widget Portfolio.
 *
 * @package Sight
 */

namespace Sight_Elementor\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Elementor Widget
 *
 * @since 1.0.0
 */
class Sight_Widget_Portfolio extends Widget_Base {

	/**
	 * Retrieve the widget name.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'sight-portfolio-widget';
	}

	/**
	 * Retrieve the widget title.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return esc_html__( 'Portfolio', 'sight' );
	}

	/**
	 * Retrieve the widget icon.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'eicon-call-to-action';
	}

	/**
	 * Retrieve the list of categories the widget belongs to.
	 *
	 * Used to determine where to display the widget in the editor.
	 *
	 * Note that currently Elementor supports only one category.
	 * When multiple categories passed, Elementor uses the first one.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 *
	 * @return array Widget categories.
	 */
	public function get_categories() {
		return array( 'basic' );
	}

	/**
	 * Registers layouts of block.
	 */
	public function widget_layouts() {
		$layouts = array(
			'standard' => array(
				'name'     => esc_html__( 'Standard', 'sight' ),
				'template' => SIGHT_PATH . 'render/handler/post-area-init.php',
			),
		);

		// Return.
		return apply_filters( 'sight_widget_portfolio_layouts', $layouts );
	}

	/**
	 * Get layouts (format: array)
	 *
	 * @access protected
	 */
	protected function get_array_layouts() {
		$widget_layouts = $this->widget_layouts();

		$layouts = array();

		foreach ( $widget_layouts as $key => $attrs ) {
			$layouts[ $key ] = $attrs['name'];
		}

		return $layouts;
	}

	/**
	 * Register the widget controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @since 1.0.0
	 *
	 * @access protected
	 */
	protected function _register_controls() {

		$this->start_controls_section(
			'layout_section',
			array(
				'label' => esc_html__( 'Layout', 'sight' ),
			)
		);

		do_action( 'sight/widget/fields/before', $this );

		$this->add_control(
			'layout',
			array(
				'label'   => esc_html__( 'Layout', 'sight' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'standard',
				'options' => $this->get_array_layouts(),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'general',
			array(
				'label' => esc_html__( 'General Settings', 'sight' ),
			)
		);

		do_action( 'sight/widget/fields/general/before', $this );

		$this->add_control(
			'source',
			array(
				'label'   => esc_html__( 'Source', 'sight' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'projects',
				'options' => array(
					'projects'   => esc_html__( 'Projects', 'sight' ),
					'custom'     => esc_html__( 'Images', 'sight' ),
					'categories' => esc_html__( 'Categories', 'sight' ),
					'post'       => esc_html__( 'Post Attachments', 'sight' ),
				),
			)
		);

		/* Type Projects */

		$this->add_control(
			'video',
			array(
				'label'      => esc_html__( 'Video Background', 'sight' ),
				'type'       => Controls_Manager::SELECT,
				'default'    => 'always',
				'options'    => array(
					'none'   => esc_html__( 'None', 'sight' ),
					'always' => esc_html__( 'Always', 'sight' ),
					'hover'  => esc_html__( 'On Hover', 'sight' ),
				),
				'conditions' => array(
					'relation' => 'and',
					'terms'    => array(
						array(
							'name'     => 'source',
							'operator' => '==',
							'value'    => 'projects',
						),
					),
				),
			)
		);

		$this->add_control(
			'video_controls',
			array(
				'label'        => esc_html__( 'Enable video controls', 'sight' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'true',
				'default'      => 'true',
				'conditions'   => array(
					'relation' => 'and',
					'terms'    => array(
						array(
							'name'     => 'source',
							'operator' => '==',
							'value'    => 'projects',
						),
						array(
							'name'     => 'video',
							'operator' => '!=',
							'value'    => 'none',
						),
					),
				),
			)
		);

		/* Post */

		$this->add_control(
			'custom_post',
			array(
				'label'      => esc_html__( 'Post', 'sight' ),
				'type'       => 'custom_post',
				'conditions' => array(
					'relation' => 'and',
					'terms'    => array(
						array(
							'name'     => 'source',
							'operator' => '==',
							'value'    => 'post',
						),
					),
				),
			)
		);

		/* Type Custom */

		$this->add_control(
			'custom_images',
			array(
				'label'      => esc_html__( 'Images', 'sight' ),
				'type'       => Controls_Manager::GALLERY,
				'conditions' => array(
					'relation' => 'and',
					'terms'    => array(
						array(
							'name'     => 'source',
							'operator' => '==',
							'value'    => 'custom',
						),
					),
				),
			)
		);

		/* Common end */

		$this->add_control(
			'number_items',
			array(
				'label'   => esc_html__( 'Number of Items', 'sight' ),
				'type'    => Controls_Manager::NUMBER,
				'min'     => 1,
				'max'     => 100,
				'step'    => 1,
				'default' => 10,
			)
		);

		do_action( 'sight/widget/fields/general/after', $this );

		$this->end_controls_section();

		$this->start_controls_section(
			'meta',
			array(
				'label' => esc_html__( 'Meta Settings', 'sight' ),
			)
		);

		do_action( 'sight/widget/fields/meta/after', $this );

		$this->add_control(
			'meta_title',
			array(
				'label'        => esc_html__( 'Display item title', 'sight' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'true',
				'default'      => 'true',
				'conditions'   => array(
					'relation' => 'and',
					'terms'    => array(
						array(
							'name'     => 'source',
							'operator' => '!=',
							'value'    => 'post',
						),
						array(
							'name'     => 'source',
							'operator' => '!=',
							'value'    => 'custom',
						),
					),
				),
			)
		);

		$this->add_control(
			'meta_caption',
			array(
				'label'        => esc_html__( 'Display item caption', 'sight' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'true',
				'default'      => 'true',
			)
		);

		$this->add_control(
			'meta_caption_length',
			array(
				'label'      => esc_html__( 'Caption length', 'sight' ),
				'type'       => Controls_Manager::NUMBER,
				'min'        => 1,
				'max'        => 1000,
				'step'       => 1,
				'default'    => 100,
				'conditions' => array(
					'relation' => 'and',
					'terms'    => array(
						array(
							'name'     => 'meta_caption',
							'operator' => '==',
							'value'    => 'true',
						),
					),
				),
			)
		);

		$this->add_control(
			'meta_category',
			array(
				'label'        => esc_html__( 'Display meta category', 'sight' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'true',
				'conditions'   => array(
					'relation' => 'and',
					'terms'    => array(
						array(
							'name'     => 'source',
							'operator' => '==',
							'value'    => 'projects',
						),
						array(
							'name'     => 'projects_filter_post_type',
							'operator' => '==',
							'value'    => 'sight-projects',
						),
					),
				),
			)
		);

		$this->add_control(
			'meta_date',
			array(
				'label'        => esc_html__( 'Display meta date', 'sight' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'true',
				'conditions'   => array(
					'relation' => 'and',
					'terms'    => array(
						array(
							'name'     => 'source',
							'operator' => '!=',
							'value'    => 'categories',
						),
					),
				),
			)
		);

		do_action( 'sight/widget/fields/meta/after', $this );

		$this->end_controls_section();

		$this->start_controls_section(
			'media',
			array(
				'label' => esc_html__( 'Media Settings', 'sight' ),
			)
		);

		do_action( 'sight/widget/fields/media/before', $this );

		$this->add_control(
			'attachment_lightbox',
			array(
				'label'        => esc_html__( 'Enable lightbox', 'sight' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'true',
			)
		);

		$this->add_control(
			'attachment_lightbox_icon',
			array(
				'label'        => esc_html__( 'Display lightbox zoom icon', 'sight' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'true',
				'default'      => 'true',
				'conditions'   => array(
					'relation' => 'and',
					'terms'    => array(
						array(
							'name'     => 'attachment_lightbox',
							'operator' => '==',
							'value'    => 'true',
						),
					),
				),
			)
		);

		$this->add_control(
			'attachment_link_to',
			array(
				'label'       => esc_html__( 'Link To', 'sight' ),
				'description' => sight_is_archive() ? null : esc_html__( 'If the source is "Categories" then the "Page" value will be ignored.', 'sight' ),
				'type'        => Controls_Manager::SELECT,
				'default'     => 'page',
				'options'     => array(
					'none'  => esc_html__( 'None', 'sight' ),
					'media' => esc_html__( 'Media File', 'sight' ),
					'page'  => esc_html__( 'Page', 'sight' ),
				),
				'conditions'  => array(
					'relation' => 'and',
					'terms'    => array(
						array(
							'name'     => 'attachment_lightbox',
							'operator' => '!=',
							'value'    => 'true',
						),
					),
				),
			)
		);

		$this->add_control(
			'attachment_view_more',
			array(
				'label'        => esc_html__( 'Enable view more', 'sight' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'true',
				'conditions'   => array(
					'relation' => 'and',
					'terms'    => array(
						array(
							'name'     => 'attachment_lightbox',
							'operator' => '!=',
							'value'    => 'true',
						),
						array(
							'name'     => 'attachment_link_to',
							'operator' => '!=',
							'value'    => 'none',
						),
					),
				),
			)
		);

		$this->add_control(
			'attachment_size',
			array(
				'label'   => esc_html__( 'Size', 'sight' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'large',
				'options' => sight_get_list_available_image_sizes(),
			)
		);

		$this->add_control(
			'attachment_orientation',
			array(
				'label'   => esc_html__( 'Orientation', 'sight' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'landscape-16-9',
				'options' => array(
					'original'       => esc_html__( 'Original', 'sight' ),
					'landscape-4-3'  => esc_html__( 'Landscape 4:3', 'sight' ),
					'landscape-3-2'  => esc_html__( 'Landscape 3:2', 'sight' ),
					'landscape-16-9' => esc_html__( 'Landscape 16:9', 'sight' ),
					'portrait-3-4'   => esc_html__( 'Portrait 3:4', 'sight' ),
					'portrait-2-3'   => esc_html__( 'Portrait 2:3', 'sight' ),
					'square'         => esc_html__( 'Square', 'sight' ),
				),
			)
		);

		do_action( 'sight/widget/fields/media/after', $this );

		$this->end_controls_section();

		$this->start_controls_section(
			'typography',
			array(
				'label' => esc_html__( 'Typography Settings', 'sight' ),
			)
		);

		do_action( 'sight/widget/fields/typography/before', $this );

		$this->add_control(
			'typography_heading',
			array(
				'label'      => esc_html__( 'Heading Font Size', 'sight' ),
				'type'       => Controls_Manager::TEXT,
				'default'    => '',
				'conditions' => array(
					'relation' => 'and',
					'terms'    => array(
						array(
							'name'     => 'source',
							'operator' => '!=',
							'value'    => 'post',
						),
						array(
							'name'     => 'source',
							'operator' => '!=',
							'value'    => 'custom',
						),
						array(
							'name'     => 'meta_title',
							'operator' => '==',
							'value'    => 'true',
						),
					),
				),
			)
		);

		$this->add_control(
			'typography_heading_tag',
			array(
				'label'      => esc_html__( 'Heading Tag', 'sight' ),
				'type'       => Controls_Manager::SELECT,
				'default'    => 'h3',
				'options'    => array(
					'h1'  => esc_html__( 'H1', 'sight' ),
					'h2'  => esc_html__( 'H2', 'sight' ),
					'h3'  => esc_html__( 'H3', 'sight' ),
					'h4'  => esc_html__( 'H4', 'sight' ),
					'h5'  => esc_html__( 'H5', 'sight' ),
					'h6'  => esc_html__( 'H6', 'sight' ),
					'p'   => esc_html__( 'P', 'sight' ),
					'div' => esc_html__( 'DIV', 'sight' ),
				),
				'conditions' => array(
					'relation' => 'and',
					'terms'    => array(
						array(
							'name'     => 'source',
							'operator' => '!=',
							'value'    => 'post',
						),
						array(
							'name'     => 'source',
							'operator' => '!=',
							'value'    => 'custom',
						),
						array(
							'name'     => 'meta_title',
							'operator' => '==',
							'value'    => 'true',
						),
					),
				),
			)
		);

		$this->add_control(
			'typography_caption',
			array(
				'label'      => esc_html__( 'Caption Font Size', 'sight' ),
				'type'       => Controls_Manager::TEXT,
				'default'    => '',
				'conditions' => array(
					'relation' => 'and',
					'terms'    => array(
						array(
							'name'     => 'meta_caption',
							'operator' => '==',
							'value'    => 'true',
						),
					),
				),
			)
		);

		do_action( 'sight/widget/fields/typography/after', $this );

		$this->end_controls_section();

		$this->start_controls_section(
			'query',
			array(
				'label'      => esc_html__( 'Query Settings', 'sight' ),
				'conditions' => array(
					'relation' => 'or',
					'terms'    => array(
						array(
							'name'     => 'source',
							'operator' => '==',
							'value'    => 'projects',
						),
						array(
							'name'     => 'source',
							'operator' => '==',
							'value'    => 'categories',
						),
					),
				),
			)
		);

		do_action( 'sight/widget/fields/query/before', $this );

		$this->add_control(
			'projects_filter_post_type',
			array(
				'label'      => esc_html__( 'Filter by Post Type', 'sight' ),
				'type'       => Controls_Manager::SELECT,
				'default'    => 'sight-projects',
				'options'    => sight_get_post_types_stack(),
				'conditions' => array(
					'relation' => 'and',
					'terms'    => array(
						array(
							'name'     => 'source',
							'operator' => '==',
							'value'    => 'projects',
						),
					),
				),
			)
		);

		$this->add_control(
			'projects_filter_categories',
			array(
				'label'      => esc_html__( 'Filter by Categories', 'sight' ),
				'type'       => Controls_Manager::SELECT2,
				'multiple'   => true,
				'options'    => sight_get_categories_stack(),
				'conditions' => array(
					'relation' => 'and',
					'terms'    => array(
						array(
							'name'     => 'source',
							'operator' => '==',
							'value'    => 'projects',
						),
						array(
							'name'     => 'projects_filter_post_type',
							'operator' => '==',
							'value'    => 'sight-projects',
						),
					),
				),
			)
		);

		$this->add_control(
			'projects_filter_offset',
			array(
				'label'      => esc_html__( 'Offset', 'sight' ),
				'type'       => Controls_Manager::TEXT,
				'conditions' => array(
					'relation' => 'and',
					'terms'    => array(
						array(
							'name'     => 'source',
							'operator' => '==',
							'value'    => 'projects',
						),
					),
				),
			)
		);

		$this->add_control(
			'projects_orderby',
			array(
				'label'      => esc_html__( 'Order By', 'sight' ),
				'type'       => Controls_Manager::SELECT,
				'default'    => 'date',
				'options'    => array(
					'date'          => esc_html__( 'Published Date', 'sight' ),
					'modified'      => esc_html__( 'Modified Date', 'sight' ),
					'title'         => esc_html__( 'Title', 'sight' ),
					'rand'          => esc_html__( 'Random', 'sight' ),
					'views'         => esc_html__( 'Views', 'sight' ),
					'comment_count' => esc_html__( 'Comment Count', 'sight' ),
					'ID'            => esc_html__( 'ID', 'sight' ),
				),
				'conditions' => array(
					'relation' => 'and',
					'terms'    => array(
						array(
							'name'     => 'source',
							'operator' => '==',
							'value'    => 'projects',
						),
					),
				),
			)
		);

		$this->add_control(
			'projects_order',
			array(
				'label'      => esc_html__( 'Order', 'sight' ),
				'type'       => Controls_Manager::SELECT,
				'default'    => 'DESC',
				'options'    => array(
					'DESC' => esc_html__( 'Descending', 'sight' ),
					'ASC'  => esc_html__( 'Ascending', 'sight' ),
				),
				'conditions' => array(
					'relation' => 'and',
					'terms'    => array(
						array(
							'name'     => 'source',
							'operator' => '==',
							'value'    => 'projects',
						),
					),
				),
			)
		);

		$this->add_control(
			'categories_filter_ids',
			array(
				'label'      => esc_html__( 'Filter by Categories', 'sight' ),
				'type'       => Controls_Manager::SELECT,
				'options'    => sight_get_categories_stack(),
				'conditions' => array(
					'relation' => 'and',
					'terms'    => array(
						array(
							'name'     => 'source',
							'operator' => '==',
							'value'    => 'categories',
						),
					),
				),
			)
		);

		$this->add_control(
			'categories_orderby',
			array(
				'label'      => esc_html__( 'Order By', 'sight' ),
				'type'       => Controls_Manager::SELECT,
				'default'    => 'name',
				'options'    => array(
					'name'    => esc_html__( 'Name', 'sight' ),
					'count'   => esc_html__( 'Posts count', 'sight' ),
					'include' => esc_html__( 'Filter include', 'sight' ),
					'ID'      => esc_html__( 'ID', 'sight' ),
				),
				'conditions' => array(
					'relation' => 'and',
					'terms'    => array(
						array(
							'name'     => 'source',
							'operator' => '==',
							'value'    => 'categories',
						),
					),
				),
			)
		);

		$this->add_control(
			'categories_order',
			array(
				'label'      => esc_html__( 'Order', 'sight' ),
				'type'       => Controls_Manager::SELECT,
				'default'    => 'ASC',
				'options'    => array(
					'DESC' => esc_html__( 'Descending', 'sight' ),
					'ASC'  => esc_html__( 'Ascending', 'sight' ),
				),
				'conditions' => array(
					'relation' => 'and',
					'terms'    => array(
						array(
							'name'     => 'source',
							'operator' => '==',
							'value'    => 'categories',
						),
					),
				),
			)
		);

		do_action( 'sight/widget/fields/query/after', $this );

		$this->end_controls_section();

		$this->start_controls_section(
			'color',
			array(
				'label' => esc_html__( 'Color Settings', 'sight' ),
			)
		);

		do_action( 'sight/widget/fields/color/before', $this );

		$this->add_control(
			'color_heading',
			array(
				'label'      => esc_html__( 'Heading Color', 'sight' ),
				'type'       => Controls_Manager::COLOR,
				'conditions' => array(
					'relation' => 'and',
					'terms'    => array(
						array(
							'name'     => 'source',
							'operator' => '!=',
							'value'    => 'post',
						),
						array(
							'name'     => 'source',
							'operator' => '!=',
							'value'    => 'custom',
						),
						array(
							'name'     => 'meta_title',
							'operator' => '==',
							'value'    => 'true',
						),
					),
				),
			)
		);

		$this->add_control(
			'color_heading_hover',
			array(
				'label'      => esc_html__( 'Heading Hover Color', 'sight' ),
				'type'       => Controls_Manager::COLOR,
				'conditions' => array(
					'relation' => 'and',
					'terms'    => array(
						array(
							'name'     => 'source',
							'operator' => '!=',
							'value'    => 'post',
						),
						array(
							'name'     => 'source',
							'operator' => '!=',
							'value'    => 'custom',
						),
						array(
							'name'     => 'meta_title',
							'operator' => '==',
							'value'    => 'true',
						),
					),
				),
			)
		);

		$this->add_control(
			'color_caption',
			array(
				'label'      => esc_html__( 'Caption Color', 'sight' ),
				'type'       => Controls_Manager::COLOR,
				'conditions' => array(
					'relation' => 'and',
					'terms'    => array(
						array(
							'name'     => 'meta_caption',
							'operator' => '==',
							'value'    => 'true',
						),
					),
				),
			)
		);

		do_action( 'sight/widget/fields/color/after', $this );

		$this->end_controls_section();

		do_action( 'sight/widget/fields/after', $this );
	}

	/**
	 * Render attributes from widget.
	 *
	 * @param array  $attributes The attributes.
	 * @param string $layout     The layout.
	 */
	public function render_attributes( $attributes, $layout ) {

		// Change type for bool attributes.
		foreach ( $attributes as $key => $value ) {
			if ( 'true' === $attributes[ $key ] ) {
				$attributes[ $key ] = true;
			} elseif ( 'false' === $attributes[ $key ] ) {
				$attributes[ $key ] = false;
			}
		}

		// Set attribute for field 'Images'.
		if ( $attributes['custom_images'] ) {
			$attributes['custom_images'] = wp_list_pluck( $attributes['custom_images'], 'id' );
		}

		return $attributes;
	}

	/**
	 * Render options from widget.
	 *
	 * @param array  $attributes The attributes.
	 * @param string $layout     The layout.
	 */
	public function render_options( $attributes, $layout ) {
		$options = array();

		// Get layouts.
		$layouts = $this->widget_layouts();

		// Render stack.
		foreach ( $attributes as $key => $settings ) {

			$clear_key = str_replace( $layout . '_', '', $key );

			$search = $layout . '_' . $clear_key;

			if ( array_key_exists( $search, $attributes ) ) {
				$options[ $clear_key ] = $attributes[ $search ];
			}
		}

		return $options;
	}

	/**
	 * Render the widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 1.0.0
	 *
	 * @access protected
	 */
	protected function render() {
		$attributes = $this->get_settings_for_display();

		$layouts = $this->widget_layouts();

		// Set id.
		$id = $this->get_id();

		// Set layout.
		$layout = $attributes['layout'];

		// Render attributes.
		$attributes = $this->render_attributes( $attributes, $layout );

		// Render options.
		$options = $this->render_options( $attributes, $layout );

		// Set classes.
		$classes  = " sight-block-portfolio-id-{$id}";
		$classes .= " sight-block-portfolio-layout-{$layout}";

		// Output.
		?>
		<div class="sight-block-portfolio <?php echo esc_attr( $classes ); ?>">
			<?php
			if ( isset( $layouts[ $layout ]['template'] ) && file_exists( $layouts[ $layout ]['template'] ) ) {

				// Require custom template.
				require $layouts[ $layout ]['template'];
			} else {

				// Default template.
				require SIGHT_PATH . 'render/handler/post-area-init.php';
			}
			?>
		</div>

		<?php sight_portfolio_render_style( $attributes, $options, $id ); ?>
		<?php
	}
}

/**
 * Elementor Widget Layouts
 *
 * @since 1.0.0
 */
class Sight_Widget_Portfolio_Layouts {

	/**
	 * Initialize
	 */
	public function __construct() {
		add_action( 'sight/widget/fields/general/after', array( $this, 'widget_portfolio_standard_fields' ), 5, 1 );
	}

	/**
	 * Add controls for standard layout.
	 *
	 * @param object $object The object.
	 */
	public function widget_portfolio_standard_fields( $object ) {
		$object->add_control(
			'standard_filter_items',
			array(
				'label'        => esc_html__( 'Display category filter', 'sight' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'true',
				'default'      => 'true',
				'conditions'   => array(
					'relation' => 'and',
					'terms'    => array(
						array(
							'name'     => 'layout',
							'operator' => '==',
							'value'    => 'standard',
						),
						array(
							'name'     => 'source',
							'operator' => '==',
							'value'    => 'projects',
						),
						array(
							'name'     => 'projects_filter_post_type',
							'operator' => '==',
							'value'    => 'sight-projects',
						),
					),
				),
			)
		);

		$object->add_control(
			'standard_pagination_type',
			array(
				'label'      => esc_html__( 'Pagination type', 'sight' ),
				'type'       => Controls_Manager::SELECT,
				'default'    => 'ajax',
				'options'    => array(
					'none'     => esc_html__( 'None', 'sight' ),
					'ajax'     => esc_html__( 'Load More', 'sight' ),
					'infinite' => esc_html__( 'Infinite Load', 'sight' ),
				),
				'conditions' => array(
					'relation' => 'and',
					'terms'    => array(
						array(
							'name'     => 'layout',
							'operator' => '==',
							'value'    => 'standard',
						),
						array(
							'name'     => 'source',
							'operator' => '==',
							'value'    => 'projects',
						),
					),
				),
			)
		);
	}
}

new Sight_Widget_Portfolio_Layouts();
