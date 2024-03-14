<?php

namespace Elementor;

use Elementor\Plugin;
use Elementor\Group_Control_Image_Size;
use Elementor\Core\Kits\Controls\Repeater as Global_Style_Repeater;
use Elementor\Utils;
use Thim_EL_Kit\GroupControlTrait;

class Thim_Ekit_Widget_List_Blog extends Thim_Ekit_Widget_List_Base {
	use GroupControlTrait;

	protected $current_permalink;

	public function __construct( $data = array(), $args = null ) {
		parent::__construct( $data, $args );
	}

	public function get_name() {
		return 'thim-ekits-list-blog';
	}

	public function get_title() {
		return esc_html__( 'List Blog', 'thim-elementor-kit' );
	}

	public function get_icon() {
		return 'thim-eicon eicon-post-list';
	}

	public function get_categories() {
		return array( \Thim_EL_Kit\Elementor::CATEGORY );
	}

	public function get_keywords() {
		return [
			'thim',
			'blog',
			'list blog',
			'blogs',
		];
	}

	protected function register_controls() {
		$this->start_controls_section(
			'section_content_list',
			array(
				'label' => esc_html__( 'Settings', 'thim-elementor-kit' ),
			)
		);

		$this->add_control(
			'blog_layout',
			array(
				'label'   => esc_html__( 'Select Layout', 'thim-elementor-kit' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'default',
				'options' => array(
					'default' => esc_html__( 'Default', 'thim-elementor-kit' ),
					'slider'  => esc_html__( 'Slider', 'thim-elementor-kit' ),
				),
			)
		);
		$this->add_control(
			'build_loop_item',
			array(
				'label'     => esc_html__( 'Build Loop Item', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::SWITCHER,
				'default'   => 'no',
				'separator' => 'before',
			)
		);

		$this->add_control(
			'template_id',
			array(
				'label'   => esc_html__( 'Choose a template', 'thim-elementor-kit' ),
				'type'    => Controls_Manager::SELECT2,
				'default' => '0',
				'options' => array( '0' => esc_html__( 'None', 'thim-elementor-kit' ) ) + \Thim_EL_Kit\Functions::instance()->get_pages_loop_item( 'post' ),
				'condition'   => array(
					'build_loop_item' => 'yes',
				),
			)
		);

		$this->add_control(
			'cat_id',
			array(
				'label'   => esc_html__( 'Select Category', 'thim-elementor-kit' ),
				'default' => 'all',
				'type'    => Controls_Manager::SELECT,
				'options' => \Thim_EL_Kit\Elementor::get_cat_taxonomy( 'category', array( 'all' => esc_html__( 'All', 'thim-elementor-kit' ) ) ),
			)
		);

		$this->add_control(
			'number_posts',
			array(
				'label'   => esc_html__( 'Number Post', 'thim-elementor-kit' ),
				'default' => '4',
				'type'    => Controls_Manager::NUMBER,
			)
		);

		$this->add_responsive_control(
			'columns',
			array(
				'label'          => esc_html__( 'Columns', 'thim-elementor-kit' ),
				'type'           => Controls_Manager::SELECT,
				'default'        => '3',
				'tablet_default' => '2',
				'mobile_default' => '1',
				'options'        => array(
					'1' => '1',
					'2' => '2',
					'3' => '3',
					'4' => '4',
				),
				'selectors'      => array(
					'{{WRAPPER}}' => '--thim-ekits-post-columns: repeat({{VALUE}}, 1fr)',
				),
				'condition'      => array(
					'blog_layout!' => 'slider',
				),
			)
		);

		$this->add_control(
			'orderby',
			array(
				'label'   => esc_html__( 'Order by', 'thim-elementor-kit' ),
				'type'    => Controls_Manager::SELECT,
				'options' => array(
					'popular' => esc_html__( 'Popular', 'thim-elementor-kit' ),
					'recent'  => esc_html__( 'Date', 'thim-elementor-kit' ),
					'title'   => esc_html__( 'Title', 'thim-elementor-kit' ),
					'random'  => esc_html__( 'Random', 'thim-elementor-kit' ),
				),
				'default' => 'recent',
			)
		);

		$this->add_control(
			'order',
			array(
				'label'   => esc_html__( 'Order by', 'thim-elementor-kit' ),
				'type'    => Controls_Manager::SELECT,
				'options' => array(
					'asc'  => esc_html__( 'ASC', 'thim-elementor-kit' ),
					'desc' => esc_html__( 'DESC', 'thim-elementor-kit' ),
				),
				'default' => 'asc',
			)
		);

		$this->end_controls_section();
		$this->_register_style_layout();

		parent::register_controls();

		$this->_register_settings_slider(
			array(
				'blog_layout' => 'slider',
			)
		);

		$this->_register_setting_slider_dot_style(
			array(
				'blog_layout'             => 'slider',
				'slider_show_pagination!' => 'none',
			)
		);

		$this->_register_setting_slider_nav_style(
			array(
				'blog_layout'       => 'slider',
				'slider_show_arrow' => 'yes',
			)
		);
	}

	protected function _register_style_layout() {
		$this->start_controls_section(
			'section_design_layout',
			array(
				'label'     => esc_html__( 'Layout', 'thim-elementor-kit' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'blog_layout!' => 'slider',
				),
			)
		);

		$this->add_responsive_control(
			'column_gap',
			array(
				'label'     => esc_html__( 'Columns Gap', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::SLIDER,
				'default'   => array(
					'size' => 30,
				),
				'range'     => array(
					'px' => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'selectors' => array(
					'{{WRAPPER}}' => '--thim-ekits-post-column-gap: {{SIZE}}{{UNIT}}',
				),
			)
		);

		$this->add_responsive_control(
			'row_gap',
			array(
				'label'     => esc_html__( 'Rows Gap', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::SLIDER,
				'default'   => array(
					'size' => 35,
				),
				'range'     => array(
					'px' => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'selectors' => array(
					'{{WRAPPER}}' => '--thim-ekits-post-row-gap: {{SIZE}}{{UNIT}}',
				),
			)
		);

		$this->end_controls_section();
	}

	public function render() {
		$settings = $this->get_settings_for_display();

		$query_args = array(
			'post_type'           => 'post',
			'posts_per_page'      => absint( $settings['number_posts'] ),
			'order'               => ( 'asc' == $settings['order'] ) ? 'asc' : 'desc',
			'ignore_sticky_posts' => true,
		);

		if ( $settings['cat_id'] && $settings['cat_id'] != 'all' ) {
			$query_args['cat'] = absint( $settings['cat_id'] );
		}

		switch ( $settings['orderby'] ) {
			case 'recent':
				$query_args['orderby'] = 'post_date';
				break;
			case 'title':
				$query_args['orderby'] = 'post_title';
				break;
			case 'popular':
				$query_args['orderby'] = 'comment_count';
				break;
			default: // random
				$query_args['orderby'] = 'rand';
		}
		$query_vars = new \WP_Query( $query_args );

		$class       = 'thim-ekits-post';
		$class_inner = 'thim-ekits-post__inner';
		$class_item  = 'thim-ekits-post__article';

		if ( $query_vars->have_posts() ) { // It's the global `wp_query` it self. and the loop was started from the theme.
			if ( isset( $settings['blog_layout'] ) && $settings['blog_layout'] == 'slider' ) {
				$swiper_class = \Elementor\Plugin::$instance->experiments->is_feature_active( 'e_swiper_latest' ) ? 'swiper' : 'swiper-container';
				$class       .= ' thim-ekits-sliders ' . $swiper_class;
				$class_inner  = 'swiper-wrapper';
				$class_item  .= ' swiper-slide';

				$this->render_nav_pagination_slider( $settings );
			}
			?>
			<div class="<?php echo esc_attr( $class ); ?>">
				<div class="<?php echo esc_attr( $class_inner ); ?>">
					<?php
					while ( $query_vars->have_posts() ) {
						$query_vars->the_post();
						$this->current_permalink = get_permalink();
						parent::render_post( $settings, $class_item );
					}
					?>
				</div>
			</div>

			<?php
		} else {
			echo '<div class="message-info">' . __( 'No data were found matching your selection, you need to create Post or select Category of Widget.', 'thim-elementor-kit' ) . '</div>';
		}

		wp_reset_postdata();
	}
}
