<?php
/**
 * Action Hook Class.
 *
 * @package RT_FoodMenu
 */

namespace RT\FoodMenu\Controllers\Hooks;

use RT\FoodMenu\Helpers\Fns;
use RT\FoodMenuPro\Helpers\FnsPro;

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'This script cannot be accessed directly.' );
}

/**
 * Action Hook Class.
 */
class ActionHooks {
	use \RT\FoodMenu\Traits\SingletonTrait;

	/**
	 * Class.
	 *
	 * @var string
	 */
	public $classes = '';

	/**
	 * Class Init.
	 *
	 * @return void
	 */
	protected function init() {
		add_action( 'fmp_single_summery', [ $this, 'fmp_single_images' ], 10 );
		add_action( 'fmp_single_summery', [ $this, 'fmp_before_summery' ], 20 );
		add_action( 'fmp_single_summery', [ $this, 'fmp_summery_title' ], 30 );
		add_action( 'fmp_single_summery', [ $this, 'fmp_summery_price' ], 40 );
		add_action( 'fmp_single_summery', [ $this, 'fmp_summery' ], 50 );
		add_action( 'fmp_single_summery', [ $this, 'fmp_summery_meta' ], 60 );
		add_action( 'fmp_single_summery', [ $this, 'fmp_after_summery' ], 70 );
	}

	/**
	 * Single Images.
	 *
	 * @return void
	 */
	public function fmp_single_images() {
		$settings      = get_option( TLPFoodMenu()->options['settings'] );
		$hiddenOptions = ! empty( $settings['hide_options'] ) ? $settings['hide_options'] : [];
		$thumbClass    = has_post_thumbnail() ? 'has-thumbnail' : 'no-thumbnail';

		global $post;

		$html = null;

		if ( ! in_array( 'image', $hiddenOptions ) ) {
			$html .= '<div class="fmp-col-md-5 fmp-col-lg-5 fmp-col-sm-6">';
			$html .= '<div class="fmp-images ' . esc_attr( $thumbClass ) . '" id="fmp-images">';

			if ( TLPFoodMenu()->has_pro() ) {
				$attachments = get_post_meta( $post->ID, '_fmp_image_gallery', true );

				$attachments = is_array( $attachments ) ? $attachments : [];

				if ( has_post_thumbnail() ) {
					array_unshift( $attachments, get_post_thumbnail_id( $post->ID ) );
				}

				if ( ! empty( $attachments ) ) {
					if ( count( $attachments ) > 1 ) {
						$thumbnails = null;
						$slides     = null;

						foreach ( $attachments as $attachment ) {
							$slides     .= "<div class='swiper-slide'>" . Fns::getAttachedImage( $attachment, 'full' ) . '</div>';
							$thumbnails .= "<div class='swiper-slide'>" . Fns::getAttachedImage( $attachment, 'thumbnail' ) . '</div>';
						}

						$slider  = null;
						$slider .= "<div id='fmp-slide-wrapper' class='fmp-single-slider fmp-pre-loader'>";
						$slider .= "<div id='fmp-slider-main' class='rtfm-carousel-main swiper slider-loading'>
										<div class='swiper-wrapper'>{$slides}</div>
										<div class='swiper-nav'>
											<div class='swiper-arrow swiper-button-next'><i class='fa fa-chevron-right'></i></div>
											<div class='swiper-arrow swiper-button-prev'><i class='fa fa-chevron-left'></i></div>
										</div>
									</div>";

						if ( in_array( $post->post_type, [ TLPFoodMenu()->post_type, 'product' ] ) ) {
							$slider .= "<div id='fmp-slider-thumb' class='rtfm-carousel-thumb swiper slider-loading'>
											<div class='swiper-wrapper'>{$thumbnails}</div>
										</div>";
						}

						$slider .= '<div class="fmp-loading-overlay full-op"></div><div class="fmp-loading fmp-ball-clip-rotate"><div></div></div>';
						$slider .= '</div>';

						$html .= $slider;
					} else {
						$html .= "<div class='fmp-single-food-img-wrapper'>";
						$html .= Fns::getAttachedImage( $attachments[0], 'full' );
						$html .= '</div>';
					}
				} else {
					$imgSrc = Fns::placeholder_img_src();
					$html  .= "<div class='fmp-single-food-img-wrapper'>";
					$html  .= '<img class="fmp-single-food-img" alt="Place holder image" src="' . esc_url( $imgSrc ) . '" />';
					$html  .= '</div>';
				}
			} else {
				if ( has_post_thumbnail() ) {
					$html .= get_the_post_thumbnail( $post->ID, [ 500, 500 ] );
				} else {
					$html .= "<img src='" . esc_url( TLPFoodMenu()->assets_url() ) . 'images/demo-100x100.png' . "' alt='" . get_the_title( $post->ID ) . "' />";
				}
			}
			$html .= '</div>'; // #images
			$html .= '</div>';
		}

		Fns::print_html( $html );
	}

	public function fmp_before_summery() {
		$settings      = get_option( TLPFoodMenu()->options['settings'] );
		$hiddenOptions = ! empty( $settings['hide_options'] ) ? $settings['hide_options'] : [];

		if ( in_array( 'image', $hiddenOptions ) ) {
			echo '<div class="fmp-col-md-12 paddingr0 fmp-summery" id="fmp-summery">';
		} else {
			echo '<div class="fmp-col-md-7 fmp-col-lg-7 fmp-col-sm-6 paddingr0 fmp-summery" id="fmp-summery">';
		}
	}

	public function fmp_after_summery() {
		echo '</div>';
	}

	public function fmp_summery_title() {
		?>
		<h2 class><?php the_title(); ?></h2>
		<?php
	}

	public function fmp_summery_price() {
		if ( TLPFoodMenu()->has_pro() ) {
			return;
		}

		$settings      = get_option( TLPFoodMenu()->options['settings'] );
		$hiddenOptions = ! empty( $settings['hide_options'] ) ? $settings['hide_options'] : [];

		if ( ! in_array( 'price', $hiddenOptions ) ) {
			$gTotal = Fns::getPriceWithLabel();
			echo '<div class="offers">' . wp_kses( $gTotal, Fns::allowedHtml() ) . '</div>';
		}
	}

	public function fmp_summery() {
		$settings      = get_option( TLPFoodMenu()->options['settings'] );
		$hiddenOptions = ! empty( $settings['hide_options'] ) ? $settings['hide_options'] : [];

		if ( ! in_array( 'summery', $hiddenOptions ) || ( wp_doing_ajax() && ! in_array( 'description', $hiddenOptions ) ) ) {
			?>
			<div class="fmp-short-description summery entry-summery ">
				<?php
				global $post;

				if ( in_array( $post->post_type, [ TLPFoodMenu()->post_type, 'product' ] ) ) {
					the_content();
				} else {
					if ( ! in_array( 'summery', $hiddenOptions ) ) {
						the_excerpt();
					}

					if ( wp_doing_ajax() && ! in_array( 'description', $hiddenOptions ) ) {
						the_content();
					}
				}
				?>
			</div>
			<?php
		}
	}

	public function fmp_summery_meta() {
		$settings      = get_option( TLPFoodMenu()->options['settings'] );
		$hiddenOptions = ! empty( $settings['hide_options'] ) ? $settings['hide_options'] : [];

		if ( ! in_array( 'taxonomy', $hiddenOptions ) ) {
			global $post;

			$cat       = get_the_terms( $post->ID, TLPFoodMenu()->taxonomies['category'] );
			$cat_count = is_array( $cat ) ? sizeof( $cat ) : 0;
			?>
			<div class="fmp-meta">
				<?php
				do_action( 'fmp_meta_start' );

				Fns::print_html(
					Fns::get_categories(
						$post->ID,
						', ',
						'<span class="posted_in">' . _n(
							'Category:',
							'Categories:',
							$cat_count,
							'tlp-food-menu'
						) . ' ',
						'</span>'
					)
				);

				if ( TLPFoodMenu()->has_pro() ) {
					$tag       = get_the_terms( $post->ID, TLPFoodMenu()->taxonomies['tag'] );
					$tag_count = is_array( $tag ) ? sizeof( $cat ) : 0;

					Fns::print_html(
						FnsPro::get_tags(
							$post->ID,
							', ',
							'<span class="tagged_as">' . _n( 'Tag:', 'Tags:', $tag_count, 'tlp-food-menu' ) . ' ',
							'</span>'
						)
					);
				}

				do_action( 'fmp_meta_end' );
				?>
			</div>
			<?php
		}
	}
}
