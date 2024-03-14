<?php
/**
 * Featured Posts block.
 *
 * @package Magazine Blocks
 */

namespace MagazineBlocks\BlockTypes;
use WP_Query;

defined( 'ABSPATH' ) || exit;

/**
 * Button block class.
 */
class TabPost extends AbstractBlock {

	/**
	 * Block name.
	 *
	 * @var string Block name.
	 */
	protected $block_name = 'tab-post';

	public function render( $attributes, $content, $block ) {

		$client_id  = magazine_blocks_array_get( $attributes, 'clientId', '' );
		$class_name = magazine_blocks_array_get( $attributes, 'className', '' );
        $css_id     = magazine_blocks_array_get( $attributes, 'cssID', '' );
        $post_count = magazine_blocks_array_get( $attributes, 'postCount', '4' );

		$args = array(
			'posts_per_page'      => $post_count,
			'status'              => 'publish',
			'ignore_sticky_posts' => 1,
		);

		$popular = array(
			'posts_per_page' => $post_count,
			'orderby'        => 'comment_count',
		);

		$query = new WP_Query( $args );

		$popular_query = new WP_Query( $popular );

		# The Loop.
		$html = '';

		$html .= '<div id="' . $css_id . '" class="mzb-tab-post mzb-tab-post-' . $client_id . ' ' . $class_name . '" data-active-tab="latest">';
		$html .= '<div class="mzb-tab-controls">';
		$html .= '<div data-tab="latest" class="mzb-tab-title active">Latest</div>';
		$html .= '<div data-tab="popular" class="mzb-tab-title">Popular</div>';
		$html .= '</div>';

		if ( $query->have_posts() ) {
			$html .= '<div class="mzb-posts" data-posts="latest">';
			while ( $query->have_posts() ) {
				$query->the_post();
				$id    = get_post_thumbnail_id();
				$src   = wp_get_attachment_image_src( $id );
				$src   = has_post_thumbnail( get_the_ID() ) ? get_the_post_thumbnail_url( get_the_ID() ) : '';
				$image = $src ? '<div class="mzb-featured-image"><a href="' . esc_url( get_the_permalink() ) . '"alt="' . get_the_title() . '"/><img src="' . esc_url( $src ) . '" alt="' . get_the_title() . '"/> </a></div>' : '';
				if ( ! $src ) {
					$position_class = 'no-thumbnail';
				} else {
					$position_class = '';
				}
				$title = '<h3 class="mzb-post-title"><a href="' . esc_url( get_the_permalink() ) . '">' . get_the_title() . '</a></h3>';
				$date  = '<span class ="mzb-post-date"><svg class="mzb-icon mzb-icon--calender" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 14 14">
								<path d="M1.892 12.929h10.214V5.5H1.892v7.429zm2.786-8.822v-2.09a.226.226 0 00-.066-.166.226.226 0 00-.166-.065H3.98a.226.226 0 00-.167.065.226.226 0 00-.065.167v2.09c0 .067.022.122.065.166.044.044.1.065.167.065h.465a.226.226 0 00.166-.065.226.226 0 00.066-.167zm5.571 0v-2.09a.226.226 0 00-.065-.166.226.226 0 00-.167-.065h-.464a.226.226 0 00-.167.065.226.226 0 00-.065.167v2.09c0 .067.021.122.065.166.043.044.099.065.167.065h.464a.226.226 0 00.167-.065.226.226 0 00.065-.167zm2.786-.464v9.286c0 .251-.092.469-.276.652a.892.892 0 01-.653.276H1.892a.892.892 0 01-.653-.275.892.892 0 01-.276-.653V3.643c0-.252.092-.47.276-.653a.892.892 0 01.653-.276h.929v-.696c0-.32.113-.593.34-.82.228-.227.501-.34.82-.34h.465c.319 0 .592.113.82.34.227.227.34.5.34.82v.696h2.786v-.696c0-.32.114-.593.34-.82.228-.227.501-.34.82-.34h.465c.32 0 .592.113.82.34.227.227.34.5.34.82v.696h.93c.25 0 .468.092.652.276a.892.892 0 01.276.653z" />
							</svg>
							<a href="' . esc_url( get_the_permalink() ) . '"> ' . get_the_date() . '</a></span>';
				$html .= '<div class="mzb-post ' . $position_class . '">';
				$html .= '';
				$html .= $image;
				$html .= '<div class="mzb-post-content">';
				$html .= $title;
				$html .= '<div class="mzb-post-entry-meta">';
				$html .= $date;
				$html .= '</div>';
				$html .= '</div>';
				$html .= '</div>';

			}
			$html .= '</div>';
			wp_reset_postdata();
		}
		if ( $popular_query->have_posts() ) {
			$html .= '<div class="mzb-posts" data-posts="popular">';
			while ( $popular_query->have_posts() ) {
				$popular_query->the_post();
				$id    = get_post_thumbnail_id();
				$src   = wp_get_attachment_image_src( $id );
				$src   = has_post_thumbnail( get_the_ID() ) ? get_the_post_thumbnail_url( get_the_ID(), 'thumbnail' ) : '';
				$image = $src ? '<div class="mzb-featured-image"><a href="' . esc_url( get_the_permalink() ) . '"alt="' . get_the_title() . '"/><img src="' . esc_url( $src ) . '" alt="' . get_the_title() . '"/> </a></div>' : '';
				if ( ! $src ) {
					$position_class = 'no-thumbnail';
				} else {
					$position_class = '';
				}
				$title = '<h3 class="mzb-post-title"><a href="' . esc_url( get_the_permalink() ) . '">' . get_the_title() . '</a></h3>';
				$date  = '<span class ="mzb-post-date"><svg class="mzb-icon mzb-icon--calender" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 14 14">
								<path d="M1.892 12.929h10.214V5.5H1.892v7.429zm2.786-8.822v-2.09a.226.226 0 00-.066-.166.226.226 0 00-.166-.065H3.98a.226.226 0 00-.167.065.226.226 0 00-.065.167v2.09c0 .067.022.122.065.166.044.044.1.065.167.065h.465a.226.226 0 00.166-.065.226.226 0 00.066-.167zm5.571 0v-2.09a.226.226 0 00-.065-.166.226.226 0 00-.167-.065h-.464a.226.226 0 00-.167.065.226.226 0 00-.065.167v2.09c0 .067.021.122.065.166.043.044.099.065.167.065h.464a.226.226 0 00.167-.065.226.226 0 00.065-.167zm2.786-.464v9.286c0 .251-.092.469-.276.652a.892.892 0 01-.653.276H1.892a.892.892 0 01-.653-.275.892.892 0 01-.276-.653V3.643c0-.252.092-.47.276-.653a.892.892 0 01.653-.276h.929v-.696c0-.32.113-.593.34-.82.228-.227.501-.34.82-.34h.465c.319 0 .592.113.82.34.227.227.34.5.34.82v.696h2.786v-.696c0-.32.114-.593.34-.82.228-.227.501-.34.82-.34h.465c.32 0 .592.113.82.34.227.227.34.5.34.82v.696h.93c.25 0 .468.092.652.276a.892.892 0 01.276.653z" />
							</svg>
							<a href="' . esc_url( get_the_permalink() ) . '"> ' . get_the_date() . '</a></span>';
				$html .= '<div class="mzb-post ' . $position_class . '">';
				$html .= '';
				$html .= $image;
				$html .= '<div class="mzb-post-content">';
				$html .= $title;
				$html .= '<div class="mzb-post-entry-meta">';
				$html .= $date;
				$html .= '</div>';
				$html .= '</div>';
				$html .= '</div>';
			}
			$html .= '</div>';
			wp_reset_postdata();
		}
		$html .= '</div>';
		return $html;

	}
}
