<?php
/**
 * Post Video block.
 *
 * @package Magazine Blocks
 */

namespace MagazineBlocks\BlockTypes;

use WP_Query;

defined( 'ABSPATH' ) || exit;

/**
 * Button block class.
 */
class PostVideo extends AbstractBlock {

	/**
	 * Block name.
	 *
	 * @var string Block name.
	 */
	protected $block_name = 'post-video';

	public function render( $attributes, $content, $block ) {

		$client_id = magazine_blocks_array_get( $attributes, 'clientId', '' );

		$category    = magazine_blocks_array_get( $attributes, 'category', '' );
		$no_of_posts = magazine_blocks_array_get( $attributes, 'postCount', '' );
		$column      = magazine_blocks_array_get( $attributes, 'column', '' );

		$args = array(
			'posts_per_page'      => $no_of_posts,
			'status'              => 'publish',
			'cat'                 => $category,
			'ignore_sticky_posts' => 1,
			'tax_query'           => array(
				array(
					'taxonomy' => 'post_format',
					'field'    => 'slug',
					'terms'    => array( 'post-format-video' ),
				),
			),
		);

		$query = new WP_Query( $args );

			# The Loop.
			$html = '';

		if ( $query->have_posts() ) {
			$html .= '<div class="mzb-post-video mzb-post-video-' . $client_id . '">';
			$html .= '<div class="mzb-posts mzb-post-col--' . $column . '">';

			while ( $query->have_posts() ) {
				$query->the_post();
				$id     = get_post_thumbnail_id();
				$src    = wp_get_attachment_image_src( $id );
				$src    = has_post_thumbnail( get_the_ID() ) ? get_the_post_thumbnail_url( get_the_ID() ) : '';
				$image  = $src ? '<img class="mzb-featured-image" src="' . esc_url( $src ) . '" alt="' . get_the_title() . '"/>' : '';
				$author = '<span class="magazine-post-author" >' . get_the_author_posts_link() . '</span>';
				$html  .= '<div class="mzb-post">';
				$html  .= '<a href="' . esc_url( get_the_permalink() ) . '">';
				$html  .= '<div class="mzb-image-overlay">';
				$html  .= $image;
				$html  .= '</div>';
				$html  .= '<div class="mzb-custom-embed-play" role="button">
								<svg viewBox="0 0 18 21" xmlns="http://www.w3.org/2000/svg"><path d="M17.6602 10.9341L0.339646 20.9341L0.339647 0.934081L17.6602 10.9341Z" /></svg>
							</div>';
				$html  .= '</a></div>';
			}

			$html .= '</div>';
			$html .= '</div>';
			wp_reset_postdata();
		}
			return $html;
	}
}
