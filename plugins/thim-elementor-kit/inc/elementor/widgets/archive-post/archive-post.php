<?php
namespace Elementor;

use Elementor\Plugin;
use Elementor\Group_Control_Image_Size;
use Elementor\Core\Kits\Controls\Repeater as Global_Style_Repeater;
use Elementor\Utils;
use Thim_EL_Kit\GroupControlTrait;
class Thim_Ekit_Widget_Archive_Post extends Thim_Ekit_Widget_List_Base {
	use GroupControlTrait;
	protected $current_permalink;

	public function __construct( $data = array(), $args = null ) {
		parent::__construct( $data, $args );
	}

	public function get_name() {
		return 'thim-ekits-archive-post';
	}

	public function get_title() {
		return esc_html__( 'Archive Post', 'thim-elementor-kit' );
	}

	public function get_icon() {
		return 'thim-eicon eicon-archive-posts';
	}

	public function get_categories() {
		return array( \Thim_EL_Kit\Elementor::CATEGORY_ARCHIVE_POST );
	}

	public function get_help_url() {
		return '';
	}

	protected function register_controls() {
		$this->register_options();

		$this->register_style_topbar();
		$this->register_style_layout();

		parent::register_controls();

		$this->register_navigation_archive();
		$this->register_style_pagination_archive('.thim-ekit-archive-post__pagination');
	}

	protected function register_options() {
		$this->start_controls_section(
			'section_options',
			array(
				'label' => esc_html__( 'Options', 'thim-elementor-kit' ),
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

		$this->add_responsive_control(
			'columns',
			array(
				'label'              => esc_html__( 'Columns', 'thim-elementor-kit' ),
				'type'               => Controls_Manager::SELECT,
				'default'            => '3',
				'tablet_default'     => '2',
				'mobile_default'     => '1',
				'options'            => array(
					'1' => '1',
					'2' => '2',
					'3' => '3',
					'4' => '4',
					'5' => '5',
					'6' => '6',
				),
				'selectors'          => array(
					'{{WRAPPER}}' => '--thim-ekits-archive-post-columns: repeat({{VALUE}}, 1fr)',
				),
				'frontend_available' => true,
			)
		);

		$repeater_header = new \Elementor\Repeater();

		$repeater_header->add_control(
			'header_key',
			array(
				'label'   => esc_html__( 'Type', 'thim-elementor-kit' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'result',
				'options' => array(
					'result'    => 'Result Count',
					//					'page_title' => 'Page Title',
				),
			)
		);

		$this->add_control(
			'thim_header_repeater',
			array(
				'label'       => esc_html__( 'Top Bar', 'thim-elementor-kit' ),
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $repeater_header->get_controls(),
				'prevent_empty'=>false,
				'title_field' => '<span style="text-transform: capitalize;">{{{ header_key.replace("_", " ") }}}</span>',
			)
		);

		$this->end_controls_section();
	}

	protected function register_style_topbar() {
		$this->start_controls_section(
			'section_style_topbar',
			array(
				'label' => esc_html__( 'Top Bar', 'thim-elementor-kit' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_responsive_control(
			'topbar_spacing',
			array(
				'label'     => esc_html__( 'Spacing', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::SLIDER,
				'selectors' => array(
					'{{WRAPPER}} .thim-ekit-archive-post__topbar' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'topbar_gap',
			array(
				'label'     => esc_html__( 'Gap', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'max' => 100,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .thim-ekit-archive-post__topbar' => '--thim-ekit-archive-post-topbar-gap: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();
	}

	protected function register_style_layout() {
		$this->start_controls_section(
			'section_design_layout',
			array(
				'label' => esc_html__( 'Layout', 'thim-elementor-kit' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
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
					'{{WRAPPER}}' => '--thim-ekits-archive-post-column-gap: {{SIZE}}{{UNIT}}',
				),
			)
		);

		$this->add_control(
			'row_gap',
			array(
				'label'              => esc_html__( 'Rows Gap', 'thim-elementor-kit' ),
				'type'               => Controls_Manager::SLIDER,
				'default'            => array(
					'size' => 35,
				),
				'range'              => array(
					'px' => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'frontend_available' => true,
				'selectors'          => array(
					'{{WRAPPER}}' => '--thim-ekits-archive-post-row-gap: {{SIZE}}{{UNIT}}',
				),
			)
		);

		$this->end_controls_section();
	}

	public function render() {
		global $wp_query;

		$query_vars = $wp_query->query_vars;

		$query_vars = apply_filters( 'thim_ekit/elementor/archive_post/query_posts/query_vars', $query_vars );

		if ( $query_vars !== $wp_query->query_vars ) {
			$query = new \WP_Query( $query_vars );
		} else {
			$query = $wp_query;
		}

		if ( ! $query->found_posts ) {
			return;
		}

		$settings = $this->get_settings_for_display();
		$class_item  = 'thim-ekits-post__article';
		?>

		<div class="thim-ekit-archive-post">

			<?php $this->render_topbar( $query, $settings ); ?>

			<div class="thim-ekit-archive-post__inner">
				<?php
				if ( $query->in_the_loop ) { // It's the global `wp_query` it self. and the loop was started from the theme.
					$this->current_permalink = get_permalink();
					parent::render_post( $settings, $class_item );
				} else {
					while ( $query->have_posts() ) {
						$query->the_post();

						$this->current_permalink = get_permalink();
						parent::render_post( $settings, $class_item );
					}
				}

				wp_reset_postdata();
				?>
			</div>

			<?php $this->render_loop_footer( $query, $settings ); ?>
		</div>

		<?php
	}

	public function render_topbar( $query, $settings ) {
		if ( $settings['thim_header_repeater'] ) {
			?>
			<div class="thim-ekit-archive-post__topbar">
				<?php
				foreach ( $settings['thim_header_repeater'] as $item ) {
					switch ( $item['header_key'] ) {
 						case 'result':
							$this->render_result_count( $query, $item );
							break;
					}
				}
				?>
			</div>
			<?php
		}
	}

	public function render_result_count( $query, $settings ) {
		$total = $query->found_posts;

		if ( $total == 0 ) {
			$message = '<p class="message message-error">' . esc_html__( 'No post found!', 'thim-elementor-kit' ) . '</p>';
			$index   = esc_html__( 'There are no available post!', 'thim-elementor-kit' );
		} elseif ( $total == 1 ) {
			$index = esc_html__( 'Showing only one result', 'thim-elementor-kit' );
		} else {
			$post_per_page_get = get_option( 'posts_per_page' );
			$post_per_page     = is_numeric( $post_per_page_get ) ? $post_per_page_get : 9;
			$paged             = get_query_var( 'paged' ) ? intval( get_query_var( 'paged' ) ) : 1;

			$from = 1 + ( $paged - 1 ) * $post_per_page;
			$to   = ( $paged * $post_per_page > $total ) ? $total : $paged * $post_per_page;

			if ( $from == $to ) {
				$index = sprintf(
					esc_html__( 'Showing last post of %s results', 'thim-elementor-kit' ),
					$total
				);
			} else {
				$index = sprintf(
					esc_html__( 'Showing %s - %s of %s results', 'thim-elementor-kit' ),
					$from,
					$to,
					$total
				);
			}
		}
		?>

		<span class="thim-ekits-archive-post__topbar__result">
			<?php echo( $index ); ?>
		</span>
		<?php
	}

	protected function render_loop_footer( $query, $settings ) {
		$ajax_pagination = in_array( $settings['pagination_type'], array( 'load_more_on_click', 'load_more_infinite_scroll' ), true );

		if ( '' === $settings['pagination_type'] ) {
			return;
		}

		$page_limit = $query->max_num_pages;

		if ( '' !== $settings['pagination_page_limit'] && ! $ajax_pagination ) {
			$page_limit = min( $settings['pagination_page_limit'], $page_limit );
		}

		if ( 2 > $page_limit ) {
			return;
		}

		$has_numbers   = in_array( $settings['pagination_type'], array( 'numbers', 'numbers_and_prev_next' ) );

		$only_prev_next = in_array( $settings['pagination_type'], array( 'prev_next') );

		$load_more_type = $settings['pagination_type'];

		if ( $settings['pagination_type'] === '' ) {
			$paged = 1;
		} else {
			$paged = max( 1, get_query_var( 'paged' ), get_query_var( 'page' ) );
		}

		$current_page = $this->get_current_page();
		$next_page = intval( $current_page ) + 1;

		if ( $ajax_pagination ) {
			$this->render_load_more_pagination( $settings, $load_more_type, $paged, $page_limit, $next_page );
			return;
		}

		$links = array();
		$show_prev_next = false;
		if($settings['pagination_type'] == 'numbers_and_prev_next'){
			$show_prev_next = true;
		}
		if ( $has_numbers ) {
			$paginate_args = array(
				'type'               => 'array',
				'current'            => $paged,
				'total'              => $page_limit,
				'prev_next'          => $show_prev_next,
				'prev_text'          => $settings['pagination_prev_label'],
				'next_text'          => $settings['pagination_next_label'],
				'show_all'           => 'yes' !== $settings['pagination_numbers_shorten'],
				'before_page_number' => '<span class="elementor-screen-only">' . esc_html__( 'Page', 'thim-elementor-kit' ) . '</span>',
			);

			if ( is_singular() && ! is_front_page() ) {
				global $wp_rewrite;

				if ( $wp_rewrite->using_permalinks() ) {
					$paginate_args['base']   = trailingslashit( get_permalink() ) . '%_%';
					$paginate_args['format'] = user_trailingslashit( '%#%', 'single_paged' );
				} else {
					$paginate_args['format'] = '?page=%#%';
				}
			}

			$links = paginate_links( $paginate_args );
		}

		if ( $only_prev_next ) {
			$prev_next = $this->get_posts_nav_link( $query, $paged, $page_limit, $settings );
			array_unshift( $links, $prev_next['prev'] );
			$links[] = $prev_next['next'];
		}
		?>
		<nav class="thim-ekit-archive-post__pagination" aria-label="<?php esc_attr_e( 'Pagination', 'thim-elementor-kit' ); ?>">
			<?php echo wp_kses_post( implode( PHP_EOL, $links ) ); ?>
		</nav>
		<?php
	}

	public function get_posts_nav_link( $query, $paged, $page_limit = null, $settings = array() ) {
		if ( ! $page_limit ) {
			$page_limit = $query->max_num_pages;
		}

		$return = array();

		$link_template     = '<a class="page-numbers %s" href="%s">%s</a>';
		$disabled_template = '<span class="page-numbers %s">%s</span>';

		if ( $paged > 1 ) {
			$next_page = intval( $paged ) - 1;

			if ( $next_page < 1 ) {
				$next_page = 1;
			}

			$return['prev'] = sprintf( $link_template, 'prev', $this->get_wp_link_page( $next_page ), $settings['pagination_prev_label'] );
		} else {
			$return['prev'] = sprintf( $disabled_template, 'prev', $settings['pagination_prev_label'] );
		}

		$next_page = intval( $paged ) + 1;

		if ( $next_page <= $page_limit ) {
			$return['next'] = sprintf( $link_template, 'next', $this->get_wp_link_page( $next_page ), $settings['pagination_next_label'] );
		} else {
			$return['next'] = sprintf( $disabled_template, 'next', $settings['pagination_next_label'] );
		}

		return $return;
	}

	public function get_current_page() {
		if ( '' === $this->get_settings_for_display( 'pagination_type' ) ) {
			return 1;
		}

		return max( 1, get_query_var( 'paged' ), get_query_var( 'page' ) );
	}

	private function get_wp_link_page( $i ) {
		if ( ! is_singular() || is_front_page() ) {
			return get_pagenum_link( $i );
		}

		// Based on wp-includes/post-template.php:957 `_wp_link_page`.
		global $wp_rewrite;
		$post       = get_post();
		$query_args = array();
		$url        = get_permalink();

		if ( $i > 1 ) {
			if ( '' === get_option( 'permalink_structure' ) || in_array( $post->post_status, array( 'draft', 'pending' ) ) ) {
				$url = add_query_arg( 'page', $i, $url );
			} elseif ( get_option( 'show_on_front' ) === 'page' && (int) get_option( 'page_on_front' ) === $post->ID ) {
				$url = trailingslashit( $url ) . user_trailingslashit( "$wp_rewrite->pagination_base/" . $i, 'single_paged' );
			} else {
				$url = trailingslashit( $url ) . user_trailingslashit( $i, 'single_paged' );
			}
		}

		if ( is_preview() ) {
			if ( ( 'draft' !== $post->post_status ) && isset( $_GET['preview_id'], $_GET['preview_nonce'] ) ) {
				$query_args['preview_id']    = absint( wp_unslash( $_GET['preview_id'] ) );
				$query_args['preview_nonce'] = sanitize_text_field( wp_unslash( $_GET['preview_nonce'] ) );
			}

			$url = get_preview_post_link( $post, $query_args, $url );
		}

		return $url;

	}
}
