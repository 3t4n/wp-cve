<?php

namespace AbsoluteAddons\Widgets;

use AbsoluteAddons\Absp_Widget;
use AbsoluteAddons\Absp_FAQ_Schema;
use AbsoluteAddons\Absp_Read_More_Button;
use AbsoluteAddons\Absp_Accordion_Controller;
use AbsoluteAddons\Controls\Absp_Control_Styles;
use Elementor\Controls_Manager;
use Elementor\Icons_Manager;
use Elementor\Repeater;
use Elementor\Utils;
use WP_Post;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * @since 1.1.0
 */
class Absoluteaddons_Style_Faq extends Absp_Widget {

	use Absp_FAQ_Schema,
		Absp_Read_More_Button,
		Absp_Accordion_Controller;

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
		return 'absolute-faq';
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
		return __( 'Faq', 'absolute-addons' );
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
		return 'absp eicon-help-o';
	}

	/**
	 * Requires css files.
	 *
	 * @return array
	 */
	public function get_style_depends() {
		return [
			'absolute-addons-custom',
			'font-awesome',
			'ico-font',
			'absp-faq',
			'absp-pro-faq',
		];
	}

	/**
	 * Requires js files.
	 *
	 * @return array
	 */
	public function get_script_depends() {
		return [
			'sifter',
			'jquery.beefup',
			'absp-faq',
		];
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
		 * @param Absoluteaddons_Style_Faq $this Current instance of WP_Network_Query (passed by reference).
		 *
		 * @since 1.0.0
		 *
		 */
		do_action_ref_array( $this->get_prefixed_hook( 'controllers/starts' ), [ & $this ] );

		$this->start_controls_section(
			'section_template',
			[
				'label' => __( 'Template', 'absolute-addons' ),
			]
		);

		$faq = apply_filters( $this->get_prefixed_hook( 'styles' ), [
			'one'      => esc_html__( 'Design Style One', 'absolute-addons' ),
			'two'      => esc_html__( 'Design Style Two', 'absolute-addons' ),
			'three'    => esc_html__( 'Design Style Three', 'absolute-addons' ),
			'four-pro' => esc_html__( 'Design Style Four (PRO)', 'absolute-addons' ),
			'five-pro' => esc_html__( 'Design Style Five (PRO)', 'absolute-addons' ),
			'six-pro'  => esc_html__( 'Design Style Six (PRO)', 'absolute-addons' ),
			'seven'    => esc_html__( 'Design Style Seven', 'absolute-addons' ),
			'eight'    => esc_html__( 'Design Style Eight', 'absolute-addons' ),
		] );

		$pro_styles = [
			'four-pro',
			'five-pro',
			'six-pro',
		];

		$this->add_control(
			'absolute_faq',
			[
				'label'       => esc_html__( 'Faq Style', 'absolute-addons' ),
				'label_block' => true,
				'type'        => Absp_Control_Styles::TYPE,
				'options'     => $faq,
				'default'     => 'one',
			]
		);

		$this->init_pro_alert( $pro_styles );

		$this->add_control(
			'faq_highlight_text',
			[
				'label'       => esc_html__( 'FAQs Highlight Text', 'absolute-addons' ),
				'type'        => Controls_Manager::TEXT,
				'label_block' => true,
				'default'     => esc_html__( 'Frequently Asked Questions', 'absolute-addons' ),
				'condition'   => [
					'absolute_faq' => [ 'two', 'four', 'six', 'eight' ],
				],
			]
		);

		$this->add_control(
			'faq_sub_text',
			[
				'label'       => esc_html__( 'FAQs Sub Text', 'absolute-addons' ),
				'type'        => Controls_Manager::TEXT,
				'label_block' => true,
				'default'     => esc_html__( 'Find your answers', 'absolute-addons' ),
				'condition'   => [
					'absolute_faq' => [ 'eight' ],
				],
			]
		);

		$this->add_control(
			'faq_image',
			[
				'label'     => esc_html__( 'Image', 'absolute-addons' ),
				'type'      => Controls_Manager::MEDIA,
				'dynamic'   => [
					'active' => true,
				],
				'default'   => [
					'url' => Utils::get_placeholder_image_src(),
				],
				'condition' => [
					'absolute_faq' => [ 'eight' ],
				],
			]
		);

		$this->add_control(
			'faq_show_excerpt',
			[
				'label'     => esc_html__( 'Excerpt Show', 'absolute-addons' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => [
					'true'  => esc_html__( 'Yes', 'absolute-addons' ),
					'false' => esc_html__( 'No', 'absolute-addons' ),
				],
				'default'   => 'true',
				'separator' => 'before',
				'condition' => [
					'absolute_faq' => [ 'three', 'seven' ],
				],
			]
		);

		$this->add_control(
			'faq_excerpt_length',
			[
				'label'       => esc_html__( 'Excerpt Length', 'absolute-addons' ),
				'type'        => Controls_Manager::NUMBER,
				'description' => esc_html__( 'Number of word you need to show', 'absolute-addons' ),
				'default'     => '15',
				'condition'   => [
					'faq_show_excerpt' => 'true',
					'absolute_faq'     => [ 'three', 'seven' ],
				],
			]
		);

		$repeater = new Repeater();

		$repeater->add_control(
			'faq_title',
			[
				'label'       => esc_html__( 'FAQs Title', 'absolute-addons' ),
				'type'        => Controls_Manager::TEXT,
				'label_block' => true,
				'default'     => esc_html__( 'Lorem Epsum dolor sit amet, consectetur adipiscing elit', 'absolute-addons' ),
			]
		);

		$repeater->add_control(
			'faq_content',
			[
				'label'   => esc_html__( 'FAQs Content', 'absolute-addons' ),
				'type'    => Controls_Manager::WYSIWYG,
				'default' => esc_html__( 'Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat. Ut wisi enim ad minim veniam, quis nostrud exerci tation ullamcorper suscipit lobortis nisl ut aliquip ex ea commodo consequat. Duis autem vel eum iriure dolor in hendrerit in vulputate velit esse molestie consequat.', 'absolute-addons' ),
			]
		);

		$this->add_control(
			'faq',
			[
				'label'       => esc_html__( 'FAQ Item', 'absolute-addons' ),
				'type'        => Controls_Manager::REPEATER,
				'show_label'  => true,
				'fields'      => $repeater->get_controls(),
				'default'     => [
					[
						'faq_title'   => esc_html__( 'FAQ #1', 'absolute-addons' ),
						'faq_content' => esc_html__( 'Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod.', 'absolute-addons' ),
					],
					[
						'faq_title'   => esc_html__( 'FAQ #2', 'absolute-addons' ),
						'faq_content' => esc_html__( 'Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat.', 'absolute-addons' ),
					],
				],
				'title_field' => '{{{ faq_title }}}',
				// phpcs:ignore WordPressVIPMinimum.Security.Mustache.OutputNotation
				'condition'   => [
					'absolute_faq' => [ 'three', 'seven', 'eight' ],
				],
			]
		);

		$this->render_faq_schema_control();

		$this->end_controls_section();

		$this->render_accordion_control();

		$this->render_controller( 'template-faq-style' );
		$this->render_controller( 'faq-post-query-controller' );

		/**
		 * Fires after controllers are registered.
		 *
		 * @param Absoluteaddons_Style_Faq $this Current instance of WP_Network_Query (passed by reference).
		 *
		 * @since 1.0.0
		 *
		 */
		do_action_ref_array( $this->get_prefixed_hook( 'controllers/ends' ), [ & $this ] );
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
		$settings            = $this->get_settings_for_display();
		$style               = $settings['absolute_faq'];
		$uid                 = wp_unique_id( 'absp-faq-' );
		$this->current_style = $style;
		$this->add_render_attribute( [
			'absp-faq-item-wrap' => [
				'class' => 'faq absp-faq-' . $this->get_icon_alignment( $settings ),
			],
		] );
		$this->add_render_attribute( [
			'absp-faq-item' => [
				'class'               => 'content-entry accordion',
				'data-beefup-options' => $this->get_accordion_attributes( $settings ),
			],
		] );
		?>
		<div class="absp-wrapper absp-widget">
			<div class="absp-wrapper-inside">
				<div class="absp-wrapper-content">
					<!-- absp-faq -->
					<div class="absp-faq element-<?php echo esc_attr( $style ); ?>">
						<section class="faq-item-<?php echo esc_attr( $style ); ?>">
							<?php if ( in_array( $style, [ 'one', 'two', 'four', 'five', 'six' ] ) ) {
								$this->render_tabs( $uid, $settings );
							} else {
								$this->render_template();
								$this->render_faq_schema( $settings['faq'], 'faq_title', 'faq_content', $settings );
							} ?>
						</section>
					</div>
					<!-- absp-faq -->
				</div>
			</div>
		</div>
		<?php
	}

	protected function render_tab_navs( $uid, $settings ) {
		if ( in_array( $this->current_style, [ 'two', 'four', 'six' ] ) ) { ?>
			<div class="faq-left">
				<h2 class="faq-highlight-text"><?php echo esc_html( $settings['faq_highlight_text'] ); ?></h2>
				<?php $term_slugs = $this->faq_tab_menu( $uid, $settings ); ?>
			</div>
		<?php } else {
			$term_slugs = $this->faq_tab_menu( $uid, $settings );
		}

		return $term_slugs;
	}

	protected function render_search_form( $uid ) {
		if ( in_array( $this->current_style, [ 'four', 'six' ] ) ) { ?>
			<form class="search-form">
				<label class="sr-only" for="<?php echo esc_attr( $uid . '_search' ); ?>"><?php esc_html_e( 'Search FAQ', 'absolute-addons' ); ?></label>
				<input class="faqsearch" id="<?php echo esc_attr( $uid . '_search' ); ?>" type="text" placeholder="<?php esc_attr_e( 'Search your content..', 'absolute-addons' ); ?>">
				<button type="submit">
					<i class="fas fa-search" aria-hidden="true"></i>
					<span class="sr-only"><?php esc_html_e( 'Search', 'absolute-addons' ); ?></span>
				</button>
			</form>
		<?php }
	}

	protected function render_tabs( $uid, $settings ) {
		$term_slugs = $this->render_tab_navs( $uid, $settings ); ?>
		<div class="tab-content">
			<?php $this->render_search_form( $uid ); ?>
			<?php foreach ( $term_slugs as $index => $term_slug ) { ?>
				<div class="faq-tab-item <?php echo 0 == $index ? 'is-open' : ''; ?>" id="<?php echo esc_attr( $uid . $term_slug ); ?>">
					<h4 class="tab-head" style="display: none !important;"><?php absp_render_title( $term_slug ); ?></h4>
					<div class="tab-body">
						<?php
						$posts = $this->get_posts( $settings, $term_slug );
						foreach ( $posts as $post ) {
							setup_postdata( $post );
							$GLOBALS['post'] = $post; // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited

							$aria_expanded = $this->handle_expend_first( 'absp-faq-item', $settings, $aria_expanded );

							$this->render_template( $this->current_style );
						}
						wp_reset_postdata();
						$this->render_faq_schema( $posts, '', '', $settings );
						?>
					</div>
				</div>
			<?php } ?>
		</div>
		<?php
	}

	/**
	 * @param array $settings
	 *
	 * @return void
	 *
	 */
	protected function faq_icon( $settings = [] ) {

		if ( ! $this->maybe_render_icons( $settings ) ) {
			return;
		}
		?>
		<div class="faq-icon-closed"><?php $this->render_icon_collapsed( $settings ); ?></div>
		<div class="faq-icon-opened"><?php $this->render_icon_active( $settings ); ?></div>
		<?php
	}

	protected function faq_tab_menu( $unique_id, $settings = [] ) {
		$term_slugs = [];
		if ( 'category' === $settings['select_faq_post'] && ! empty( $settings['faq_category_post'] ) ) {
			$terms = ! is_array( $settings['faq_category_post'] ) ? [] : $settings['faq_category_post'];
		} elseif ( 'recent_post' == $settings['select_faq_post'] ) {
			$recent_posts = get_posts( [
				'fields'      => 'ids',
				'post_type'   => 'faq',
				'orderby'     => 'post_date',
				'order'       => 'DESC',
				'numberposts' => $settings['number_of_posts'], // Number of recent posts thumbnails to display
				'post_status' => 'publish', // Show only the published posts
			] );
			$args         = [
				'taxonomy'   => 'faq_category',
				'hide_empty' => true,
				'object_ids' => $recent_posts,
			];
			$terms        = get_terms( $args );
		} else {
			$args = [
				'taxonomy'   => 'faq_category',
				'hide_empty' => true,
				'object_ids' => null,
			];
			if ( ! empty( $settings['faq_select_post'] ) && is_array( $settings['faq_select_post'] ) ) {
				$args['object_ids'] = array_map( 'absint', $settings['faq_select_post'] );
			}

			$terms = get_terms( $args );
		}

		if ( $terms && ! is_wp_error( $terms ) ) {
			echo '<div class="scoll-tab"><ul class="nav-tab">';
			foreach ( $terms as $index => $term ) {
				$is_open = ! $index ? 'is-open' : '';
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
				echo '<li class="' . esc_attr( $is_open ) . '"><a href="#' . esc_attr( $unique_id . $slug ) . '" class="faq-cat-' . esc_attr( $slug ) . '">' . esc_html( $label ) . '</a></li>';
			}
			echo '</ul></div>';
		}

		return $term_slugs;
	}

	/**
	 * @param array $settings
	 * @param array $term_slugs
	 *
	 * @return WP_Post[]
	 */
	protected function get_posts( $settings, $term_slugs = [] ) {
		$args = [
			'post_type'      => 'faq',
			'post_status'    => 'publish',
			'posts_per_page' => $settings['number_of_posts'],
			'offset'         => $settings['faq_posts_offset'],
			'orderby'        => $settings['faq_posts_order_by'],
			'order'          => $settings['faq_posts_sort'],
		];

		if ( 'select_post' == $settings['select_faq_post'] && ! empty( $settings['faq_select_post'] ) ) {
			$args['post__in'] = array_map( 'absint', $settings['faq_select_post'] );
		} else {
			if ( ! empty( $term_slugs ) ) {
				$args['tax_query'] = [ // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_tax_query
					[
						'taxonomy' => 'faq_category',
						'field'    => 'slug',
						'terms'    => $term_slugs,
					],
				];
			}
		}

		return get_posts( $args );
	}
}
