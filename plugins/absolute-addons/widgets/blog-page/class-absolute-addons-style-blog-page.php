<?php
/**
 * Blog Style Addon
 *
 * @package AbsoluteAddons
 */

namespace AbsoluteAddons\Widgets;

use AbsoluteAddons\Absp_Widget;
use AbsoluteAddons\Controls\Absp_Control_Styles;
use AbsoluteAddons\Absp_Slider_Controller;
use Elementor\Controls_Manager;
use AbsoluteAddons\Absp_Read_More_Button;
use Elementor\Group_Control_Image_Size;
use Elementor\Icons_Manager;
use WP_Post;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Elementor Blog widget.
 *
 * Elementor widget that displays blog posts in different styles.
 *
 * @since 1.0.0
 */
class Absoluteaddons_Style_Blog_Page extends Absp_Widget {

	use Absp_Read_More_Button;
	use Absp_Slider_Controller;

	protected $current_style;

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
		return 'absolute-blog-page';
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
		return __( 'Blog Page', 'absolute-addons' );
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
		return 'absp eicon-post-list';
	}

	/**
	 * Requires css files.
	 *
	 * @return array
	 */
	public function get_style_depends() {
		return array(
			'absolute-addons-custom',
			'absolute-addons-btn',
			'absp-blog-page',
			'absp-pro-blog-page',
		);
	}

	/**
	 * Requires js files.
	 *
	 * @return array
	 */
	public function get_script_depends() {
		return array(
			'jquery.isotope',
			'absp-blog',
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
		return [ 'absp-widgets' ];
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
		 * @param Absoluteaddons_Style_Blog_page $this Current instance of WP_Network_Query (passed by reference).
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

		$blog_page = apply_filters( $this->get_prefixed_hook( 'styles' ), [
			'one'          => __( 'Style One', 'absolute-addons' ),
			'two'          => __( 'Style Two', 'absolute-addons' ),
			'three-pro'    => __( 'Style Three (Pro)', 'absolute-addons' ),
			'four-pro'     => __( 'Style Four (Pro)', 'absolute-addons' ),
			'five-pro'     => __( 'Style Five (Pro)', 'absolute-addons' ),
			'six-pro'      => __( 'Style Six (Pro)', 'absolute-addons' ),
			'seven-pro'    => __( 'Style Seven (Pro)', 'absolute-addons' ),
			'eight-pro'    => __( 'Style Eight (Pro)', 'absolute-addons' ),
			'nine-pro'     => __( 'Style Nine (Pro)', 'absolute-addons' ),
			'ten'          => __( 'Style Ten', 'absolute-addons' ),
			'eleven'       => __( 'Style Eleven', 'absolute-addons' ),
			'twelve'       => __( 'Style Twelve', 'absolute-addons' ),
			'thirteen'     => __( 'Style Thirteen', 'absolute-addons' ),
			'fourteen-pro' => __( 'Style Fourteen (Pro)', 'absolute-addons' ),
			'fifteen'      => __( 'Style Fifteen (Upcoming)', 'absolute-addons' ),
			'sixteen'      => __( 'Style Sixteen (Upcoming)', 'absolute-addons' ),
			'seventeen'    => __( 'Style Seventeen (Upcoming)', 'absolute-addons' ),
			'eighteen'     => __( 'Style Eighteen (Upcoming)', 'absolute-addons' ),
			'nineteen'     => __( 'Style Nineteen (Upcoming)', 'absolute-addons' ),
			'twenty'       => __( 'Style Twenty (Upcoming)', 'absolute-addons' ),
			'twenty-one'   => __( 'Style Twenty One (Upcoming)', 'absolute-addons' ),
			'twenty-two'   => __( 'Style Twenty Two (Upcoming)', 'absolute-addons' ),
			'twenty-three' => __( 'Style Twenty Three (Upcoming)', 'absolute-addons' ),
			'twenty-four'  => __( 'Style Twenty Four (Upcoming)', 'absolute-addons' ),
			'twenty-five'  => __( 'Style Twenty Five (Upcoming)', 'absolute-addons' ),
			'twenty-six'   => __( 'Style Twenty Six (Upcoming)', 'absolute-addons' ),
			'twenty-seven' => __( 'Style Twenty Seven (Upcoming)', 'absolute-addons' ),
		] );

		$pro_styles = [
			'three-pro',
			'four-pro',
			'five-pro',
			'six-pro',
			'seven-pro',
			'eight-pro',
			'nine-pro',
			'fourteen-pro',
		];

		$this->add_control(
			'absolute_blog_page',
			array(
				'label'       => __( 'Blog Page Style', 'absolute-addons' ),
				'label_block' => true,
				'type'        => Absp_Control_Styles::TYPE,
				'options'     => $blog_page,
				'disabled'    => [
					'eleven',
					'fifteen',
					'eight-pro',
					'sixteen',
					'seventeen',
					'eighteen',
					'nineteen',
					'twenty',
					'twenty-one',
					'twenty-two',
					'twenty-three',
					'twenty-four',
					'twenty-five',
					'twenty-six',
					'twenty-seven',
				],
				'default'     => 'one',
			)
		);

		$this->init_pro_alert( $pro_styles );

		$this->end_controls_section();

		$this->layout_section();
		$this->filter_section();
		$this->post_query_section();
		$this->post_meta_section();
		$this->read_more_button_section();

		// Content Controllers
		$this->render_controller( 'template-blog-page-style' );

		/**
		 * Fires after controllers are registered.
		 *
		 * @param Absoluteaddons_Style_Blog_page $this Current instance of WP_Network_Query (passed by reference).
		 *
		 * @since 1.0.0
		 *
		 */
		do_action_ref_array( $this->get_prefixed_hook( 'controllers/ends' ), [ &$this ] );

	}

	protected function layout_section() {
		$this->start_controls_section(
			'absp_blog_layout_section',
			array(
				'label' => __( 'Layout Section', 'absolute-addons' ),
			)
		);

		$this->add_control(
			'absp_enable_masonry',
			[
				'label'   => __( 'Show Masonry Layout', 'absolute-addons' ),
				'type'    => Controls_Manager::SWITCHER,
				'yes'     => __( 'Yes', 'absolute-addons' ),
				'none'    => __( 'No', 'absolute-addons' ),
				'default' => 'yes',
			]
		);

		$this->add_responsive_control(
			'blog_column',
			[
				'label'           => __( 'Blog Column', 'absolute-addons' ),
				'type'            => Controls_Manager::SELECT,
				'options'         => [
					'100'      => __( '1 Column', 'absolute-addons' ),
					'50'       => __( '2 Column', 'absolute-addons' ),
					'33.3333'  => __( '3 Column', 'absolute-addons' ),
					'25'       => __( '4 Column', 'absolute-addons' ),
					'20'       => __( '5 Column', 'absolute-addons' ),
					'16.66667' => __( '6 Column', 'absolute-addons' ),
				],
				'default'         => '100',
				'devices'         => [ 'desktop', 'tablet', 'mobile' ],
				'desktop_default' => '100',
				'tablet_default'  => '100',
				'mobile_default'  => '100',
				'prefix_class'    => 'absp-col%s-',
				'selectors'       => [
					'(desktop+){{WRAPPER}} .absp-blog-page .absp-grid .absp-col' => 'width: {{blog_column.VALUE}}%;',
					'(tablet){{WRAPPER}} .absp-blog-page .absp-grid .absp-col'   => 'width: {{blog_column_tablet.VALUE}}%;',
					'(mobile){{WRAPPER}} .absp-blog-page .absp-grid .absp-col'   => 'width: {{blog_column_mobile.VALUE}}%;',
				],
				'conditions'      => [
					'relation' => 'or',
					'terms'    => [
						[
							'name'     => 'absp_enable_masonry',
							'operator' => '===',
							'value'    => 'yes',
						],
						[
							'name'     => 'enable_filter_menu',
							'operator' => '===',
							'value'    => 'yes',
						],
					],
				],
			]
		);

		$this->add_responsive_control(
			'blog_grid_column',
			[
				'label'           => __( 'Blog Column', 'absolute-addons' ),
				'type'            => Controls_Manager::SELECT,
				'options'         => [
					'1' => __( '1 Column', 'absolute-addons' ),
					'2' => __( '2 Column', 'absolute-addons' ),
					'3' => __( '3 Column', 'absolute-addons' ),
					'4' => __( '4 Column', 'absolute-addons' ),
					'5' => __( '5 Column', 'absolute-addons' ),
					'6' => __( '6 Column', 'absolute-addons' ),
				],
				'default'         => '1',
				'devices'         => [ 'desktop', 'tablet', 'mobile' ],
				'desktop_default' => 1,
				'tablet_default'  => 1,
				'mobile_default'  => 1,
				'prefix_class'    => 'absp-grid%s-',
				'selectors'       => [
					'(desktop+){{WRAPPER}} .absp-blog-page .absp-grid-col' => 'grid-template-columns: repeat({{blog_grid_column.VALUE}}, 1fr);',
					'(tablet){{WRAPPER}} .absp-blog-page .absp-grid-col'   => 'grid-template-columns: repeat({{blog_grid_column_tablet.VALUE}}, 1fr);',
					'(mobile){{WRAPPER}} .absp-blog-page .absp-grid-col'   => 'grid-template-columns: repeat({{blog_grid_column_mobile.VALUE}}, 1fr);',
				],
				'condition'       => [
					'absp_enable_masonry!' => 'yes',
					'enable_filter_menu!'  => 'yes',
				],
			]
		);

		$this->add_responsive_control(
			'absp_column_gap',
			[
				'label'      => esc_html__( 'Column Gap', 'absolute-addons' ),
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
				'default'    => [
					'unit' => 'rem',
					'size' => 3,
				],
				'selectors'  => [
					'{{WRAPPER}} .absp-blog-page .absp-grid-col' => 'column-gap: {{SIZE}}{{UNIT}};',
				],
				'condition'  => [
					'absp_enable_masonry!' => 'yes',
					'enable_filter_menu!'  => 'yes',
				],
			]
		);

		$this->add_control(
			'absp_blog_posts_feature_img',
			[
				'label'   => __( 'Show Featured Image', 'absolute-addons' ),
				'type'    => Controls_Manager::SWITCHER,
				'yes'     => __( 'Yes', 'absolute-addons' ),
				'none'    => __( 'No', 'absolute-addons' ),
				'default' => 'yes',
			]
		);

		$this->add_group_control(
			Group_Control_Image_Size::get_type(),
			[
				'name'           => 'absp_blog_posts_feature_img',
				'fields_options' => [
					'size' => [
						'label' => __( 'Featured Image Size', 'absolute-addons' ),
					],
				],
				'exclude'        => [ 'custom' ], // phpcs:ignore WordPressVIPMinimum.Performance.WPQueryParams.PostNotIn_exclude
				'default'        => 'large',
				'condition'      => [
					'absp_blog_posts_feature_img' => 'yes',
				],
			]
		);

		$this->add_control(
			'absp_blog_page_title',
			[
				'label'   => __( 'Show Title', 'absolute-addons' ),
				'type'    => Controls_Manager::SWITCHER,
				'yes'     => __( 'Yes', 'absolute-addons' ),
				'none'    => __( 'No', 'absolute-addons' ),
				'default' => 'yes',
			]
		);

		$this->add_control(
			'absp_blog_posts_title_trim',
			[
				'label'     => __( 'Crop title by word', 'absolute-addons' ),
				'type'      => Controls_Manager::NUMBER,
				'default'   => '15',
				'condition' => [
					'absp_blog_page_title' => 'yes',
				],
			]
		);

		$this->add_control(
			'absp_blog_posts_content',
			[
				'label'     => __( 'Show Content', 'absolute-addons' ),
				'type'      => Controls_Manager::SWITCHER,
				'label_on'  => __( 'Yes', 'absolute-addons' ),
				'label_off' => __( 'No', 'absolute-addons' ),
				'default'   => 'yes',
			]
		);

		$this->add_control(
			'absp_blog_posts_content_trim',
			[
				'label'     => __( 'Crop content by word', 'absolute-addons' ),
				'type'      => Controls_Manager::NUMBER,
				'default'   => '50',
				'condition' => [
					'absp_blog_posts_content' => 'yes',
				],
			]
		);

		$this->add_control(
			'absp_blog_posts_read_more',
			[
				'label'     => __( 'Show Read More', 'absolute-addons' ),
				'type'      => Controls_Manager::SWITCHER,
				'label_on'  => __( 'Yes', 'absolute-addons' ),
				'label_off' => __( 'No', 'absolute-addons' ),
				'default'   => 'yes',
				'condition' => [
					'absolute_blog_page!' => [ 'four', 'five', 'six', 'twelve', 'thirteen' ],
				],
			]
		);

		$this->end_controls_section();
	}

	protected function filter_section() {
		$this->start_controls_section(
			'filter_layout_section',
			array(
				'label' => __( 'Filter Section', 'absolute-addons' ),
			)
		);

		$this->add_control(
			'enable_filter_menu',
			[
				'label'        => __( 'Enable Filter Menu', 'absolute-addons' ),
				'type'         => Controls_Manager::SWITCHER,
				'options'      => [
					'yes' => __( 'Yes', 'absolute-addons' ),
					'no'  => __( 'No', 'absolute-addons' ),
				],
				'default'      => 'yes',
				'descriptions' => __( 'Enable to display filter menu.', 'absolute-addons' ),
			]
		);

		$this->add_control(
			'show_all_filter',
			[
				'label'        => __( 'Show "All" Filter', 'absolute-addons' ),
				'type'         => Controls_Manager::SWITCHER,
				'options'      => [
					'yes' => __( 'Yes', 'absolute-addons' ),
					'no'  => __( 'No', 'absolute-addons' ),
				],
				'default'      => 'yes',
				'descriptions' => __( 'Enable to display "All" filter in filter menu.', 'absolute-addons' ),
				'condition'    => [
					'enable_filter_menu' => 'yes',
				],
			]
		);

		$this->add_control(
			'filter_text',
			[
				'label'     => __( 'Filter Text', 'absolute-addons' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => 'All',
				'condition' => [
					'show_all_filter'    => 'yes',
					'enable_filter_menu' => 'yes',
				],
				'separator' => 'after',
			]
		);

		$this->end_controls_section();
	}

	protected function post_query_section() {
		$this->start_controls_section(
			'query_section',
			array(
				'label' => __( 'Query Section', 'absolute-addons' ),
			)
		);

		$this->add_control(
			'number_of_posts',
			[
				'label'        => __( 'Posts Count', 'absolute-addons' ),
				'type'         => Controls_Manager::NUMBER,
				'min'          => 1,
				'max'          => 200,
				'step'         => 1,
				'default'      => 8,
				'descriptions' => __( 'If You need to show all post to input "-1"', 'absolute-addons' ),
			]
		);

		$this->add_control(
			'select_blog_post',
			[
				'label'    => __( 'Select Post', 'absolute-addons' ),
				'type'     => Controls_Manager::SELECT,
				'multiple' => true,
				'options'  => [
					'recent'      => __( 'Recent Post', 'absolute-addons' ),
					'select_post' => __( 'Selected Post', 'absolute-addons' ),
					'category'    => __( 'Category Post', 'absolute-addons' ),
				],
				'default'  => 'select_post',
			]
		);

		$all_terms = get_terms( 'category', [
			'hide_empty' => false,
		] );


		$args = [
			'post_type'      => 'post',
			'post_status'    => 'publish',
			'posts_per_page' => - 1,
		];

		// we get an array of posts objects
		$posts = get_posts( $args );


		$blog_terms = [];
		$blog_post  = [];

		foreach ( (array) $all_terms as $single_terms ) {
			$blog_terms[ $single_terms->slug . '|' . $single_terms->name ] = $single_terms->name;
		}

		foreach ( (array) $posts as $single_post ) {
			$blog_post[ $single_post->ID . '|' . $single_post->post_title ] = $single_post->post_title;
		}

		$this->add_control(
			'blog_category_post',
			[
				'label'     => __( 'Select Category', 'absolute-addons' ),
				'type'      => Controls_Manager::SELECT2,
				'multiple'  => true,
				'options'   => $blog_terms,
				'condition' => [
					'select_blog_post' => [ 'category' ],
				],
			]
		);

		$this->add_control(
			'blog_select_post',
			[
				'label'     => __( 'Select Post', 'absolute-addons' ),
				'type'      => Controls_Manager::SELECT2,
				'multiple'  => true,
				'options'   => $blog_post,
				'condition' => [
					'select_blog_post' => [ 'select_post' ],
				],
			]
		);

		$this->add_control(
			'blog_posts_offset',
			[
				'label'   => __( 'Offset', 'absolute-addons' ),
				'type'    => Controls_Manager::NUMBER,
				'min'     => 0,
				'max'     => 20,
				'default' => 0,
			]
		);

		$this->add_control(
			'blog_posts_order_by',
			[
				'label'   => __( 'Order by', 'absolute-addons' ),
				'type'    => Controls_Manager::SELECT2,
				'options' => [
					'date'          => __( 'Date', 'absolute-addons' ),
					'title'         => __( 'Title', 'absolute-addons' ),
					'author'        => __( 'Author', 'absolute-addons' ),
					'modified'      => __( 'Modified', 'absolute-addons' ),
					'comment_count' => __( 'Comments', 'absolute-addons' ),
				],
				'default' => 'date',
			]
		);

		$this->add_control(
			'blog_posts_sort',
			[
				'label'   => __( 'Order', 'absolute-addons' ),
				'type'    => Controls_Manager::SELECT,
				'options' => [
					'ASC'  => __( 'ASC', 'absolute-addons' ),
					'DESC' => __( 'DESC', 'absolute-addons' ),
				],
				'default' => 'DESC',
			]
		);

		$this->end_controls_section();
	}

	protected function post_meta_section() {
		$this->start_controls_section(
			'absp_blog_page_meta_data',
			[
				'label' => __( 'Meta Data', 'absolute-addons' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'absp_blog_posts_meta',
			[
				'label'     => __( 'Show Meta Data', 'absolute-addons' ),
				'type'      => Controls_Manager::SWITCHER,
				'label_on'  => __( 'Yes', 'absolute-addons' ),
				'label_off' => __( 'No', 'absolute-addons' ),
				'default'   => 'yes',
			]
		);

		$this->add_control(
			'absp_author_select',
			[
				'label'     => __( 'Show Author', 'absolute-addons' ),
				'type'      => Controls_Manager::SWITCHER,
				'label_on'  => __( 'Yes', 'absolute-addons' ),
				'label_off' => __( 'No', 'absolute-addons' ),
				'default'   => 'yes',
				'condition' => [
					'absp_blog_posts_meta' => 'yes',
				],
			]
		);

		$this->add_control(
			'absp_blog_page_author_image',
			[
				'label'     => __( 'Show Author Image', 'absolute-addons' ),
				'type'      => Controls_Manager::SWITCHER,
				'label_on'  => __( 'Yes', 'absolute-addons' ),
				'label_off' => __( 'No', 'absolute-addons' ),
				'default'   => 'no',
				'condition' => [
					'absp_blog_posts_meta' => 'yes',
					'absp_author_select'   => 'yes',
				],
			]
		);

		$this->add_control(
			'absp_author_label',
			[
				'label'     => __( 'Author Label', 'absolute-addons' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => 'By',
				'condition' => [
					'absp_author_select'   => 'yes',
					'absp_blog_posts_meta' => 'yes',
				],
			]
		);

		$this->add_control(
			'absp_category_select',
			[
				'label'     => __( 'Show Category', 'absolute-addons' ),
				'type'      => Controls_Manager::SWITCHER,
				'label_on'  => __( 'Yes', 'absolute-addons' ),
				'label_off' => __( 'No', 'absolute-addons' ),
				'default'   => 'yes',
				'condition' => [
					'absp_blog_posts_meta' => 'yes',
				],
			]
		);

		$this->add_control(
			'absp_date_time_select',
			[
				'label'     => __( 'Show Date & Time', 'absolute-addons' ),
				'type'      => Controls_Manager::SWITCHER,
				'label_on'  => __( 'Yes', 'absolute-addons' ),
				'label_off' => __( 'No', 'absolute-addons' ),
				'default'   => 'yes',
				'condition' => [
					'absp_blog_posts_meta' => 'yes',
				],
			]
		);

		$this->add_control(
			'absp_time_select',
			[
				'label'     => __( 'Show Time', 'absolute-addons' ),
				'type'      => Controls_Manager::SWITCHER,
				'label_on'  => __( 'Yes', 'absolute-addons' ),
				'label_off' => __( 'No', 'absolute-addons' ),
				'default'   => 'yes',
				'condition' => [
					'absp_blog_posts_meta'  => 'yes',
					'absp_date_time_select' => 'yes',
				],
			]
		);

		$this->add_control(
			'absp_comment_select',
			[
				'label'     => __( 'Show Comment?', 'absolute-addons' ),
				'type'      => Controls_Manager::SWITCHER,
				'label_on'  => __( 'Yes', 'absolute-addons' ),
				'label_off' => __( 'No', 'absolute-addons' ),
				'default'   => 'yes',
				'condition' => [
					'absp_blog_posts_meta' => 'yes',
				],
			]
		);

		$this->end_controls_section();
	}

	protected function read_more_button_section() {
		$this->start_controls_section(
			'absp_blog_posts_read_more_section',
			[
				'label'     => __( 'Read More Button', 'absolute-addons' ),
				'condition' => [
					'absolute_blog_page!' => [ 'four', 'five', 'six', 'twelve', 'thirteen' ],
				],
			]
		);

		$this->add_control(
			'absp_blog_posts_btn_text',
			[
				'label'       => __( 'Button Text', 'absolute-addons' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => __( 'Read more ', 'absolute-addons' ),
				'placeholder' => __( 'Read more ', 'absolute-addons' ),
			]
		);

		$this->add_control(
			'absp_blog_posts_btn_icons_switch',
			[
				'label'     => __( 'Add icon? ', 'absolute-addons' ),
				'type'      => Controls_Manager::SWITCHER,
				'default'   => 'yes',
				'label_on'  => __( 'Yes', 'absolute-addons' ),
				'label_off' => __( 'No', 'absolute-addons' ),
			]
		);

		$this->add_control(
			'absp_blog_posts_btn_icons',
			[
				'label'       => __( 'Icon', 'absolute-addons' ),
				'type'        => Controls_Manager::ICONS,
				'default'     => [
					'value' => '',
				],
				'label_block' => true,
				'condition'   => [
					'absp_blog_posts_btn_icons_switch' => 'yes',
				],
			]
		);
		$this->add_control(
			'absp_blog_posts_btn_icon_align',
			[
				'label'     => __( 'Icon Position', 'absolute-addons' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => [
					'left'  => __( 'Before', 'absolute-addons' ),
					'right' => __( 'After', 'absolute-addons' ),
				],
				'default'   => 'right',
				'condition' => [
					'absp_blog_posts_btn_icons_switch' => 'yes',
				],
			]
		);
		$this->add_responsive_control(
			'absp_blog_posts_btn_align',
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
					'{{WRAPPER}} .absp-blog-page-btn' => 'text-align: {{VALUE}};',
				],
				'default'   => 'left',
			]
		);

		$this->end_controls_section();
	}

	/**
	 * Render the widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 1.1.0
	 *
	 * @access protected
	 */
	protected function render() {
		$settings          = $this->get_settings_for_display();
		$controls_selector = wp_unique_id( 'filter-container-' );
		$style             = $settings['absolute_blog_page'];

		$this->current_style = $style;

		if ( 'yes' === $settings['absp_enable_masonry'] || 'yes' === $settings['enable_filter_menu'] ) {
			$absp_grid = 'absp-grid';
		} else {
			$absp_grid = 'absp-grid-col';
		}

		?>
		<div class="absp-blog-page-wrapper absp-widget element-<?php echo esc_attr( $style ); ?>">
			<div class="absp-blog-page absp-blog-page-style-<?php echo esc_attr( $style ); ?>">
				<?php $term_slugs = $this->get_filter_menu( $settings, $controls_selector ); ?>
				<div class="absp-gap <?php echo esc_attr( $absp_grid ) ?>">
					<?php
					$posts = $this->get_posts( $settings, $term_slugs );
					foreach ( $posts as $post ) {
						$GLOBALS['post'] = $post; // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
						setup_postdata( $post );
						$blog_terms = get_the_terms( get_the_ID(), 'category' );
						if ( $blog_terms && ! is_wp_error( $blog_terms ) ) {
							$blog_terms = array_map( function ( $value ) {
								return $value->slug;
							}, $blog_terms );
							$blog_terms = implode( ' ', $blog_terms );
						} else {
							$blog_terms = '';
						}

						$args = [
							'style'      => $style,
							'blog_terms' => $blog_terms,
						];
						?>
						<div class="absp-col <?php echo esc_attr( $blog_terms ); ?>">
							<article class="absp-blog-page-item">
								<?php $this->render_template( $style, $args ); ?>
							</article>
						</div>
						<?php
					}
					wp_reset_postdata();
					?>
				</div>
			</div>
		</div>
		<?php
	}

	protected function get_filter_menu( $settings = [], $controls_selector = '' ) {
		if ( empty( $settings ) ) {
			$settings = $this->get_settings_for_display();
		}

		// prepare layout classnames.
		$layout_class = 'absp-filter-menu';

		$term_slugs = [];

		if ( 'category' === $settings['select_blog_post'] && ! empty( $settings['blog_category_post'] ) ) {
			$terms = ! is_array( $settings['blog_category_post'] ) ? [] : $settings['blog_category_post'];
		} else {
			$args = [
				'taxonomy'   => 'category',
				'hide_empty' => true,
				'object_ids' => null,
			];
			if ( ! empty( $settings['blog_select_post'] ) && is_array( $settings['blog_select_post'] ) ) {
				$args['object_ids'] = array_map( 'absint', $settings['blog_select_post'] );
			}
			$terms = get_terms( $args );
		}

		$enable_filter = ( 'yes' === $settings['enable_filter_menu'] );

		if ( $enable_filter ) { ?>
			<div class="absp-col absp-blog-page-filter">
			<ul class="<?php echo esc_attr( $layout_class ); ?>"><?php
			if ( 'yes' === $settings['show_all_filter'] ) { ?>
				<li class="absp-filter-item">
					<a class="is-active <?php echo esc_attr( $controls_selector ); ?>" data-filter="*"><?php echo
						esc_html( $settings['filter_text'] ); ?></a>
				</li>
				<?php
			}
		}

		if ( $terms && ! is_wp_error( $terms ) ) {
			foreach ( $terms as $term ) {
				if ( is_string( $term ) ) {
					$term_data = explode( '|', $term );
					if ( 2 !== count( $term_data ) ) {
						continue;
					}
					list( $slug, $label ) = $term_data;
				} else {
					$slug  = $term->slug;
					$label = $term->name;
				}
				$term_slugs[] = $slug;
				if ( $enable_filter ) {
					echo '<li class="absp-filter-item"><a class="' . esc_attr( $controls_selector ) . '" data-filter=".' . esc_attr( $slug ) . '">' . esc_html( $label ) . '</a></li>';
				}
			}
		}

		if ( $enable_filter ) { ?>
			</ul></div>
		<?php }

		return $term_slugs;
	}

	/**
	 * @param array $settings
	 * @param array $term_slugs
	 *
	 * @return WP_Post[]
	 */
	protected function get_posts( $settings, $term_slugs = [] ) {
		$args = array(
			'post_type'      => 'post',
			'post_status'    => 'publish',
			'posts_per_page' => $settings['number_of_posts'],
			'offset'         => $settings['blog_posts_offset'],
			'orderby'        => $settings['blog_posts_order_by'],
			'order'          => $settings['blog_posts_sort'],
		);

		if ( 'select_post' == $settings['select_blog_post'] ) {
			if ( is_array( $settings['blog_select_post'] ) ) {
				$args['post__in'] = array_map( 'absint', $settings['blog_select_post'] );
			}
		} else {
			if ( ! empty( $term_slugs ) ) {
				$args['tax_query'] = [ // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_tax_query
					[
						'taxonomy' => 'category',
						'field'    => 'slug',
						'terms'    => $term_slugs,
					],
				];
			}
		}

		return get_posts( $args );
	}

	protected function post_thumbnail( $settings ) {
		if ( 'yes' === $settings['absp_blog_posts_feature_img'] && has_post_thumbnail() ) { ?>
			<div class="absp-blog-page-image">
				<a href="<?php the_permalink(); ?>">
					<img src="<?php the_post_thumbnail_url( esc_attr( $settings['absp_blog_posts_feature_img_size'] ) ); ?>" alt="<?php the_title(); ?>">
				</a>
			</div>
		<?php }
	}

	protected function post_title( $settings ) {
		if ( 'yes' === $settings['absp_blog_page_title'] ) { ?>
			<div class="absp-blog-page-title">
				<h2>
					<a href="<?php the_permalink(); ?>"><?php absp_trim_render_title( get_the_title(), $settings['absp_blog_posts_title_trim'], '' ); ?></a>
				</h2>
			</div>
		<?php }
	}

	protected function post_content( $settings ) {
		if ( 'yes' === $settings['absp_blog_posts_content'] ) { ?>
			<div class="absp-blog-page-content">
				<p><?php absp_render_excerpt_no_pe( get_the_excerpt(), $settings['absp_blog_posts_content_trim'] ); ?></p>
			</div>
		<?php }
	}

	protected function is_meta_active( $key = null, $settings = [] ) {
		if ( empty( $settings ) ) {
			$settings = $this->get_settings_for_display();
		}

		if ( ! $key ) {
			return 'yes' === $settings['absp_blog_posts_meta'];
		}

		return 'yes' === $settings['absp_blog_posts_meta'] && ! empty( $settings['absp_blog_posts_meta_select'] ) && in_array( $key, $settings['absp_blog_posts_meta_select'] );
	}

	protected function post_date_time( $settings ) {
		if ( 'yes' === $settings['absp_date_time_select'] ) {
			if ( 'thee' !== $this->current_style ) { ?>
				<time title="<?php the_date( 'M d Y G:i A' ); ?>">
					<span class="absp-blog-page-date">
						<?php echo esc_html( get_the_date( 'M' ) ); ?>
						<span class="absp-date"><?php echo esc_html( get_the_date( 'd' ) ); ?></span>
						<?php echo esc_html( get_the_date( 'Y' ) ); ?>
					</span>
					<?php if ( 'seven' !== $this->current_style ) {
						if ( 'yes' === $settings['absp_time_select'] ) { ?>
							<span class="absp-blog-page-time"><?php the_time( 'G:i A' ); ?></span>
						<?php }
					} ?>
				</time>
			<?php } else { ?>
				<time title="<?php the_date( 'M d Y G:i A' ); ?>">
					<span class="absp-blog-page-date">
						<span class="absp-date"><?php echo esc_html( get_the_date( 'd M' ) ); ?></span>
						<?php echo esc_html( get_the_date( 'Y' ) ); ?>
					</span>
					<?php if ( 'yes' === $settings['absp_time_select'] ) { ?>
						<span class="absp-blog-page-time"><?php the_time( 'G:i A' ); ?></span>
					<?php } ?>
				</time>
			<?php }
		}
	}

	protected function post_date( $settings ) {
		if ( 'yes' === $settings['absp_date_select'] ) {
			if ( in_array( $this->current_style, [ 'three', 'seven', 'eleven', 'fourteen', 'fifteen' ] ) ) { ?>
				<span class="absp-blog-page-date">
					<span class="absp-date"><?php echo esc_html( get_the_date( 'd M' ) ); ?></span>
					<?php echo esc_html( get_the_date( 'Y' ) ); ?>
				</span>
			<?php } else { ?>
				<span class="absp-blog-page-date">
					<?php echo esc_html( get_the_date( 'M' ) ); ?>
					<span class="absp-date"><?php echo esc_html( get_the_date( 'd' ) ); ?></span>
					<?php echo esc_html( get_the_date( 'Y' ) ); ?>
				</span>
			<?php }
		}
	}

	protected function post_category( $settings ) {
		if ( 'yes' === $settings['absp_category_select'] ) { ?>
			<span class="absp-blog-page-category"><?php wp_kses_post_e( get_the_category_list( ' | ' ) ); ?></span>
		<?php }
	}

	protected function post_author( $settings ) {
		if ( 'yes' === $settings['absp_author_select'] ) { ?>
			<span class="absp-blog-page-author">
				<?php if ( 'yes' === $settings['absp_blog_page_author_image'] ) { ?>
					<span class="absp-author-image"><?php echo get_avatar( get_the_author_meta( "ID" ) ); ?></span>
				<?php } ?>
				<span class="absp-author-label"><?php echo esc_html( $settings['absp_author_label'] ); ?></span>
				<a href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ); ?>"><?php the_author_meta( 'display_name' ); ?></a>
			</span>
		<?php }
	}

	protected function post_comment( $settings ) {
		if ( 'yes' === $settings['absp_comment_select'] ) { ?>
			<span class="absp-blog-page-comment">
				<a href="<?php comments_link(); ?>"><?php echo esc_html( get_comments_number() ); ?>
					<span>Comment</span>
				</a>
			</span>
		<?php }
	}

	protected function read_more_button( $settings, $class_name ) {
		if ( 'yes' === $settings['absp_blog_posts_read_more'] ) { ?>
			<div class="absp-blog-page-btn">
				<a href="<?php the_permalink(); ?>" class="absp-btn <?php echo esc_attr( $class_name ); ?>">
					<?php
					if ( 'left' === $settings['absp_blog_posts_btn_icon_align'] ) {
						if ( 'yes' === $settings['absp_blog_posts_btn_icons_switch'] ) {
							Icons_Manager::render_icon( $settings['absp_blog_posts_btn_icons'], [ 'aria-hidden' => 'true' ] );
						}
						echo esc_html( $settings['absp_blog_posts_btn_text'] );
					} else {
						echo esc_html( $settings['absp_blog_posts_btn_text'] );
						if ( 'yes' === $settings['absp_blog_posts_btn_icons_switch'] ) {
							Icons_Manager::render_icon( $settings['absp_blog_posts_btn_icons'], [ 'aria-hidden' => 'true' ] );
						}
					}
					?>
				</a>
			</div>
		<?php }
	}
}
