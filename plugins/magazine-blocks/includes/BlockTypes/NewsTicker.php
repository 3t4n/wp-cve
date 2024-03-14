<?php
/**
 * Post List block.
 *
 * @package Magazine Blocks
 */

namespace MagazineBlocks\BlockTypes;

use function MagazineBlocks\custom_pagination_numbers;
use WP_Query;

defined( 'ABSPATH' ) || exit;

/**
 * Button block class.
 */
class NewsTicker extends AbstractBlock {

	/**
	 * Block name.
	 *
	 * @var string Block name.
	 */
	protected $block_name = 'news-ticker';

	public function render( $attributes, $content, $block ) {

		$client_id = magazine_blocks_array_get( $attributes, 'clientId', '' );

		// Query.
		$category   = magazine_blocks_array_get( $attributes, 'category', '' );
		$post_count = magazine_blocks_array_get( $attributes, 'postCount', '' );
		$label      = magazine_blocks_array_get( $attributes, 'label', '' );
		$icon       = magazine_blocks_array_get( $attributes, 'icon', '' );
		$get_icon   = magazine_blocks_get_icon( $icon['icon'], false );

		$args = array(
			'posts_per_page' => $post_count,
			'status'         => 'publish',
			'category__in'   => $category,
		);

		$query = new WP_Query( $args );

		# The Loop.
		$html = '';
		if ( $query->have_posts() ) {
			$html .= '<div class="mzb-news-ticker mzb-news-ticker-' . $client_id . '">';
			$date  = '<span class ="mzb-weather">' . $get_icon . '</span>';
			$html .= $date;
			$html .= '<span class ="mzb-heading">' . $label . '</span>';
			$html .= '<div class="mzb-news-ticker-box">';
			$html .= '<ul class="mzb-news-ticker-list">';
			while ( $query->have_posts() ) {
				$query->the_post();
				$id    = get_post_thumbnail_id();
				$src   = wp_get_attachment_image_src( $id );
				$src   = has_post_thumbnail( get_the_ID() ) ? get_the_post_thumbnail_url( get_the_ID(), 'thumbnail' ) : '';
				$image = $src ? '<div class="mzb-featured-image"><a href="' . esc_url( get_the_permalink() ) . '"alt="' . get_the_title() . '"/><img src="' . esc_url( $src ) . '" alt="' . get_the_title() . '"/> </a></div>' : '';
				$title = '<li><a href="' . esc_url( get_the_permalink() ) . '">' . get_the_title() . '</a></li>';
				$html .= $title;
			}

			$html .= '</ul>';
			$html .= '</div>';
			$html .= '</div>';
			wp_reset_postdata();
		}
		return $html;
	}
}
