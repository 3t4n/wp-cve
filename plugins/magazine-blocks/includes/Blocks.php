<?php
/**
 * Magazine Blocks Manager.
 *
 * @since 1.0.0
 * @package Magazine Blocks
 */

namespace MagazineBlocks;

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

use MagazineBlocks\BlockTypes\Advertisement;
use MagazineBlocks\BlockTypes\DateWeather;
use MagazineBlocks\Traits\Singleton;
use MagazineBlocks\BlockTypes\Heading;
use MagazineBlocks\BlockTypes\BannerPosts;
use MagazineBlocks\BlockTypes\GridModule;
use MagazineBlocks\BlockTypes\FeaturedPosts;
use MagazineBlocks\BlockTypes\FeaturedCategories;
use MagazineBlocks\BlockTypes\PostList;
use MagazineBlocks\BlockTypes\CategoryList;
use MagazineBlocks\BlockTypes\TabPost;
use MagazineBlocks\BlockTypes\NewsTicker;
use MagazineBlocks\BlockTypes\Column;
use MagazineBlocks\BlockTypes\PostVideo;
use MagazineBlocks\BlockTypes\Section;
use MagazineBlocks\BlockTypes\Slider;
use MagazineBlocks\BlockTypes\AbstractBlock;
use MagazineBlocks\BlockTypes\SocialIcons;
use MagazineBlocks\BlockTypes\SocialIcon;
use WP_Query;

/**
 * Magazine_Blocks Blocks Manager
 *
 * Registers all the blocks & block categories and manages them.
 *
 * @since 1.0.0
 */
final class Blocks {

	use Singleton;

	/**
	 * Block styles.
	 *
	 * @var BlockStyles|null $block_styles
	 */
	private $block_styles;

	/**
	 * Blocks that need to be prepared for CSS generation.
	 *
	 * @var array $prepared_blocks
	 */
	private $prepared_blocks = array();

	/**
	 * Prepared widget blocks.
	 *
	 * @var array
	 */
	private $prepared_widget_blocks = array();

	/**
	 * Constructor.
	 */
	protected function __construct() {
		$this->init_hooks();
	}

	/**
	 * Magazine_Blocks/BlocksManager Constructor.
	 *
	 * @since 1.0.0
	 */
	private function init_hooks() {
		$block_categories_hook = version_compare( get_bloginfo( 'version' ), '5.8', '>=' ) ?
			'block_categories_all' :
			'block_categories';
		$preload_api_hook_handle = class_exists( 'WP_Block_Editor_Context' ) ? 'block_editor_rest_api_preload_paths' : 'block_editor_preload_paths';

		add_action( 'init', array( $this, 'register_block_types' ) );
		add_filter( $block_categories_hook, array( $this, 'block_categories' ), PHP_INT_MAX, 2 );
		add_filter( $preload_api_hook_handle, array( $this, 'preload_rest_api_path' ), 10, 2 );

		add_filter( 'pre_render_block', array( $this, 'maybe_prepare_blocks' ), 10, 3 );
		add_filter( 'wp_head', array( $this, 'maybe_prepare_blocks' ), 0 );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_blocks_styles' ), 11 );

		add_action( 'customize_save_after', array( $this, 'maybe_clear_widget_block_styles' ) );
		add_action( 'rest_after_save_widget', array( $this, 'maybe_clear_widget_block_styles' ) );
		add_action( 'after_switch_theme', array( $this, 'maybe_clear_block_styles_on_theme_switch' ), 10, 2 );
		add_action( 'save_post', array( $this, 'maybe_clear_block_styles' ), 10, 3 );
		add_action( 'delete_post', array( $this, 'maybe_clear_block_styles' ), 10, 2 );
		add_action( 'magazine_blocks_responsive_breakpoints_changed', array( $this, 'regenerate_block_styles' ) );
	}

	/**
	 * Preload REST api path.
	 *
	 * @param array                                   $paths Rest API paths.
	 * @param \WP_Block_Editor_Context|\WP_Post|mixed $context Current editor context.
	 *
	 * @return array
	 */
	public function preload_rest_api_path( array $paths, $context ): array {
		if (
			$context instanceof \WP_Post ||
			( isset( $context->name ) && in_array( $context->name, [ 'core/edit-site', 'core/edit-post' ], true ) )
		) {
			$paths[] = '/magazine-blocks/v1/library-data';
		}
		return $paths;
	}

	/**
	 * Prepare blocks for FSE themes.
	 */
	public function maybe_prepare_blocks() {
		if ( magazine_blocks_is_block_theme() && doing_filter( 'pre_render_block' ) ) {
			$args                    = func_get_args();
			$block                   = $args[1];
			$this->prepared_blocks[] = $block;
			return $args[0];
		}
		if ( ! magazine_blocks_is_block_theme() && doing_action( 'wp_head' ) ) {
			$this->prepared_blocks        = parse_blocks( get_the_content() );
			$this->prepared_widget_blocks = $this->get_widget_blocks();
		}
	}

	/**
	 * Enqueue blocks styles.
	 *
	 * @return void
	 */
	public function enqueue_blocks_styles() {
		if ( empty( $this->prepared_blocks ) ) {
			return;
		}

		$fonts                 = array();
		$this->prepared_blocks = magazine_blocks_process_blocks( $this->prepared_blocks );
		$styles                = magazine_blocks_generate_blocks_styles( $this->prepared_blocks );
		if ( ! magazine_blocks_is_block_theme() ) {
			$this->prepared_widget_blocks = magazine_blocks_process_blocks( $this->prepared_widget_blocks );
			$widget_styles                = magazine_blocks_generate_blocks_styles( $this->prepared_widget_blocks, 'widgets' );
			$fonts                        = $widget_styles->get_fonts();
			$widget_styles->enqueue();
		}

		$styles->enqueue_fonts( $fonts );
		$styles->enqueue();
	}

	/**
	 * Register block types.
	 *
	 * @return void
	 */
	public function register_block_types() {
		$block_types = $this->get_block_types();
		foreach ( $block_types as $block_type ) {
			new $block_type();
		}
	}

	/**
	 * Get block types.
	 *
	 * @return AbstractBlock[]
	 */
	private function get_block_types(): array {
		return apply_filters(
			'magazine_blocks_block_types',
			array(
				Advertisement::class,
				Heading::class,
				BannerPosts::class,
				GridModule::class,
				FeaturedPosts::class,
				FeaturedCategories::class,
				TabPost::class,
				PostList::class,
				CategoryList::class,
				NewsTicker::class,
				Column::class,
				PostVideo::class,
				Section::class,
				DateWeather::class,
				Slider::class,
				SocialIcons::class,
				SocialIcon::class,
			)
		);
	}

	/**
	 * Add "Magazine Blocks" category to the blocks listing in post edit screen.
	 *
	 * @param array $block_categories All registered block categories.
	 * @return array
	 * @since 1.0.0
	 */
	public function block_categories( array $block_categories ): array {
		return array_merge(
			array(
				array(
					'slug'  => 'magazine-blocks',
					'title' => esc_html__( 'Magazine Blocks', 'magazine-blocks' ),
				),
			),
			$block_categories
		);
	}

	/**
	 * Clear cached widget styles when widget is updated.
	 *
	 * @return void
	 */
	public function maybe_clear_widget_block_styles() {
		$cached = get_option( '_magazine_blocks_blocks_css', array() );
		magazine_blocks_array_forget( $cached, 'widgets' );
		update_option( '_magazine_blocks_blocks_css', $cached );
	}

	/**
	 * Clear cached styles when theme is switched.
	 *
	 * If is block theme then clear all cached styles stored in options table.
	 * As block theme fully depends on blocks.
	 *
	 * @param string    $name string Theme name.
	 * @param \WP_Theme $theme Theme object.
	 * @return void
	 */
	public function maybe_clear_block_styles_on_theme_switch( string $name, \WP_Theme $theme ) {
		if ( $theme->is_block_theme() ) {
			delete_option( '_magazine_blocks_blocks_css' );
		}
	}

	/**
	 * Clear or update cached styles.
	 *
	 * @param int      $id Post ID.
	 * @param \WP_Post $post Post object.
	 * @return void
	 */
	public function maybe_clear_block_styles( $id, \WP_Post $post ) {
		if ( doing_action( 'save_post' ) ) {
			// Don't make style for reusable blocks.
			if ( 'wp_block' === $post->post_type ) {
				return;
			}

			// Clear cached styles when template part or template is updated.
			if ( 'wp_template_part' === $post->post_type || 'wp_template' === $post->post_type ) {
				delete_option( '_magazine_blocks_blocks_css' );
				return;
			}

			delete_post_meta( $id, '_magazine_blocks_blocks_css' );
		}

		if ( doing_action( 'delete_post' ) ) {
			$filesystem = magazine_blocks_get_filesystem();
			if ( $filesystem ) {
				$css_files = $filesystem->dirlist( MAGAZINE_BLOCKS_UPLOAD_DIR );
				if ( ! empty( $css_files ) ) {
					foreach ( $css_files as $css_file ) {
						if ( false !== strpos( $css_file['name'], "ba-style-$id-" ) ) {
							$filesystem->delete( MAGAZINE_BLOCKS_UPLOAD_DIR . $css_file['name'] );
							break;
						}
					}
				}
			}
		}
	}

	/**
	 * Get widget blocks.
	 *
	 * @return array
	 */
	private function get_widget_blocks() {
		return parse_blocks(
			array_reduce(
				get_option( 'widget_block', array() ),
				function( $acc, $curr ) {
					if ( ! empty( $curr['content'] ) ) {
						$acc .= $curr['content'];
					}
					return $acc;
				},
				''
			)
		);
	}

	/**
	 * Regenerate block styles.
	 *
	 * @return void
	 */
	public function regenerate_block_styles() {
		delete_option( '_magazine_blocks_blocks_css' );
		delete_post_meta_by_key( '_magazine_blocks_blocks_css' );

		$filesystem = magazine_blocks_get_filesystem();

		if ( $filesystem ) {
			$filesystem->delete( MAGAZINE_BLOCKS_UPLOAD_DIR, true );
		}
	}
}

/**
 * Custom pagination function to generate numbered pagination links.
 *
 * @param int $max_num_pages The total number of pages.
 * @param int $current_page  The current page number.
 * @return string The HTML for numbered pagination links.
 */
function mzb_numbered_pagination( $max_num_pages, $current_page, $client_id, $range = 2 ) {
	$pagination = '';

	if ( $max_num_pages > 1 ) {
		$pagination .= '<ul class="mzb-pagination-numbers">';

		// Previous Arrow
		if ( $current_page > 1 ) {
			$prev_page   = $current_page - 1;
			$pagination .= '<li class="page-item prev"><a class="page-link" href="' . add_query_arg( 'block_id_' . $client_id, $prev_page ) . '"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="17" viewBox="0 0 16 17" fill="none">
			<path d="M14.0002 7.9622H3.60683L8.4735 3.1022C8.53566 3.04004 8.58496 2.96625 8.6186 2.88503C8.65224 2.80382 8.66956 2.71677 8.66956 2.62887C8.66956 2.54096 8.65224 2.45392 8.6186 2.3727C8.58496 2.29149 8.53566 2.21769 8.4735 2.15553C8.41134 2.09337 8.33754 2.04407 8.25633 2.01043C8.17511 1.97679 8.08807 1.95947 8.00016 1.95947C7.82263 1.95947 7.65237 2.03 7.52683 2.15553L1.52683 8.15553C1.46509 8.21811 1.41737 8.2931 1.38683 8.37553C1.35267 8.45568 1.33455 8.54175 1.3335 8.62887C1.33516 8.71807 1.35324 8.80621 1.38683 8.88887C1.42086 8.96552 1.46827 9.0355 1.52683 9.09553L7.52683 15.0955C7.5888 15.158 7.66254 15.2076 7.74378 15.2415C7.82502 15.2753 7.91216 15.2927 8.00016 15.2927C8.08817 15.2927 8.17531 15.2753 8.25655 15.2415C8.33779 15.2076 8.41152 15.158 8.4735 15.0955C8.53598 15.0336 8.58558 14.9598 8.61942 14.8786C8.65327 14.7973 8.6707 14.7102 8.6707 14.6222C8.6707 14.5342 8.65327 14.4471 8.61942 14.3658C8.58558 14.2846 8.53598 14.2108 8.4735 14.1489L3.60683 9.29553H14.0002C14.177 9.29553 14.3465 9.2253 14.4716 9.10027C14.5966 8.97525 14.6668 8.80568 14.6668 8.62887C14.6668 8.45206 14.5966 8.28249 14.4716 8.15746C14.3465 8.03244 14.177 7.9622 14.0002 7.9622Z" fill="#3F3F46"/>
		  </svg></a></li>';
		}

		// Always display the first page
		$pagination .= '<li class="page-item' . ( $current_page === 1 ? ' current' : '' ) . '"><a class="page-link" href="' . add_query_arg( 'block_id_' . $client_id, 1 ) . '">1</a></li>';

		// Start ellipsis
		if ( $current_page > ( $range + 2 ) ) {
			$pagination .= '<li class="page-item ellipsis">...</li>';
		}

		for ( $i = max( 2, $current_page - $range ); $i <= min( $max_num_pages - 1, $current_page + $range ); $i++ ) {
			$class       = ( $i === $current_page ) ? ' current' : '';
			$pagination .= '<li class="page-item' . $class . '"><a class="page-link" href="' . add_query_arg( 'block_id_' . $client_id, $i ) . '">' . $i . '</a></li>';
		}

		// End ellipsis
		if ( $current_page < ( $max_num_pages - $range - 1 ) ) {
			$pagination .= '<li class="page-item ellipsis">...</li>';
		}

		// Always display the last page
		$pagination .= '<li class="page-item' . ( $current_page === $max_num_pages ? ' current' : '' ) . '"><a class="page-link" href="' . add_query_arg( 'block_id_' . $client_id, $max_num_pages ) . '">' . $max_num_pages . '</a></li>';

		// Next Arrow
		if ( $current_page < $max_num_pages ) {
			$next_page   = $current_page + 1;
			$pagination .= '<li class="page-item next"><a class="page-link" href="' . add_query_arg( 'block_id_' . $client_id, $next_page ) . '"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="17" viewBox="0 0 16 17" fill="none">
			<path d="M2.00016 7.9622H12.3935L7.52683 3.1022C7.40129 2.97666 7.33077 2.8064 7.33077 2.62887C7.33077 2.54096 7.34808 2.45392 7.38172 2.3727C7.41536 2.29149 7.46467 2.21769 7.52683 2.15553C7.58899 2.09337 7.66278 2.04407 7.744 2.01043C7.82521 1.97679 7.91226 1.95947 8.00016 1.95947C8.1777 1.95947 8.34796 2.03 8.4735 2.15553L14.4735 8.15553C14.5352 8.21811 14.583 8.2931 14.6135 8.37553C14.6477 8.45568 14.6658 8.54175 14.6668 8.62887C14.6652 8.71807 14.6471 8.80621 14.6135 8.88887C14.5795 8.96552 14.5321 9.0355 14.4735 9.09553L8.4735 15.0955C8.41152 15.158 8.33779 15.2076 8.25655 15.2415C8.17531 15.2753 8.08817 15.2927 8.00016 15.2927C7.91216 15.2927 7.82502 15.2753 7.74378 15.2415C7.66254 15.2076 7.5888 15.158 7.52683 15.0955C7.46434 15.0336 7.41475 14.9598 7.3809 14.8786C7.34706 14.7973 7.32963 14.7102 7.32963 14.6222C7.32963 14.5342 7.34706 14.4471 7.3809 14.3658C7.41475 14.2846 7.46434 14.2108 7.52683 14.1489L12.3935 9.29553H2.00016C1.82335 9.29553 1.65378 9.2253 1.52876 9.10027C1.40373 8.97525 1.3335 8.80568 1.3335 8.62887C1.3335 8.45206 1.40373 8.28249 1.52876 8.15746C1.65378 8.03244 1.82335 7.9622 2.00016 7.9622Z" fill="#3F3F46"/>
		  </svg></a></li>';
		}

		$pagination .= '</ul>';
	}

	return $pagination;
}

