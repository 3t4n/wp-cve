<?php
/**
 * Grid Module block.
 *
 * @package Magazine Blocks
 */

namespace MagazineBlocks\BlockTypes;

use function MagazineBlocks\mzb_numbered_pagination;
use WP_Query;

defined( 'ABSPATH' ) || exit;

/**
 * Button block class.
 */
class GridModule extends AbstractBlock {

	/**
	 * Block name.
	 *
	 * @var string Block name.
	 */
	protected $block_name = 'grid-module';

	public function render( $attributes, $content, $block ) {

		$client_id  = magazine_blocks_array_get( $attributes, 'clientId', '' );
		$class_name = magazine_blocks_array_get( $attributes, 'className', '' );

		// General.
        $layout                  = magazine_blocks_array_get( $attributes, 'layout', '' );
		$layout_1_advanced_style = magazine_blocks_array_get( $attributes, 'layout1AdvancedStyle', '' );
		$layout_2_advanced_style = magazine_blocks_array_get( $attributes, 'layout2AdvancedStyle', '' );
		$column                  = magazine_blocks_array_get( $attributes, 'column', '4' );

		// Query.
        $category          = magazine_blocks_array_get( $attributes, 'category', '' );
		$tag               = magazine_blocks_array_get( $attributes, 'tag', '' );
		$excluded_category = magazine_blocks_array_get( $attributes, 'excludedCategory', '' );
		$order_by          = magazine_blocks_array_get( $attributes, 'orderBy', '' );
		$order_type        = magazine_blocks_array_get( $attributes, 'orderType', '' );
		$author            = magazine_blocks_array_get( $attributes, 'authorName', '' );

		$hoverAnimation        = magazine_blocks_array_get( $attributes, 'hoverAnimation', '' );

		// Heading.
		$enable_heading = magazine_blocks_array_get( $attributes, 'enableHeading', '' );
		$label          = magazine_blocks_array_get( $attributes, 'label', 'Explore More' );

		// Post Title.
		$enable_post_title = magazine_blocks_array_get( $attributes, 'enablePostTitle', 'true' );

		// Meta.
		$meta_position = magazine_blocks_array_get( $attributes, 'metaPosition', '' );
		$enable_author = magazine_blocks_array_get( $attributes, 'enableAuthor', '' );
		$enable_date   = magazine_blocks_array_get( $attributes, 'enableDate', '' );

		// Excerpt.
		$enable_excerpt = magazine_blocks_array_get( $attributes, 'enableExcerpt', '' );
		$excerpt_limit  = magazine_blocks_array_get( $attributes, 'excerptLimit', '' );

		// ReadMore.
		$enable_readmore = magazine_blocks_array_get( $attributes, 'enableReadMore', '' );
		$read_more_text  = magazine_blocks_array_get( $attributes, 'readMoreText', '' );

		// Pagination
		$enable_pagination = magazine_blocks_array_get( $attributes, 'enablePagination', '' );

		// Define the custom excerpt length function as an anonymous function
		$custom_excerpt_length = function ( $length ) use ( $excerpt_limit ) {
			return $excerpt_limit; // Change this number to your desired word limit
		};

		// Add the filter to modify the excerpt length using the anonymous function
		add_filter( 'excerpt_length', $custom_excerpt_length );

		// Pagination.
		$paged         = isset( $_GET[ 'block_id_' . $client_id ] ) ? max( 1, intval( $_GET[ 'block_id_' . $client_id ] ) ) : 1;
		$args['paged'] = $paged;

		if ( $layout === 'layout-1' ) {
			$advanced_style = $layout_1_advanced_style;
		} elseif ( $layout === 'layout-2' ) {
			$advanced_style = $layout_2_advanced_style;
		}

		$args = array(
			'posts_per_page'      => 4,
			'status'              => 'publish',
			'cat'                 => $category,
			'tag_id'              => $tag,
			'orderby'             => $order_by,
			'order'               => $order_type,
			'author'              => $author,
			'category__not_in'    => $excluded_category,
			'ignore_sticky_posts' => 1,
			'paged'               => $paged, // Use the paged parameter.
		);

		$query = new WP_Query( $args );

		# The Loop.
		$html = '';

		if ( $query->have_posts() ) {
			$html .= '<div class="mzb-grid-module mzb-grid-module-' . $client_id . ' ' . $class_name . '">';
			$html .= $enable_heading ? '<div class="mzb-post-heading"> <h2>' . $label . '</h2></div>' : '';
			$html .= '<div class="mzb-posts' . ' mzb-' . $layout . '' . ' mzb-' . $advanced_style . ' mzb-post-col--' . $column . '">';

			while ( $query->have_posts() ) {
				$query->the_post();
				$id     = get_post_thumbnail_id();
				$src    = wp_get_attachment_image_src( $id );
				$src    = has_post_thumbnail( get_the_ID() ) ? get_the_post_thumbnail_url( get_the_ID() ) : '';
				$image  = $src ? '<div class="mzb-featured-image ' . $hoverAnimation . '"><a href="' . esc_url( get_the_permalink() ) . '"alt="' . get_the_title() . '"/><img src="' . esc_url( $src ) . '" alt="' . get_the_title() . '"/> </a></div>' : '';
				$title  = $enable_post_title ? '<h3 class="mzb-post-title"><a href="' . esc_url( get_the_permalink() ) . '">' . get_the_title() . '</a></h3>' : '';
				$author = $enable_author ? '<span class="mzb-post-author" ><img class="post-author-image" src="' . get_avatar_url( get_the_author_meta( 'ID' ) ) . ' "/>' . get_the_author_posts_link() . '</span>' : '';
				$date   = $enable_date ? '<span class ="mzb-post-date"><svg class="mzb-icon mzb-icon--calender" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 14 14">
								<path d="M1.892 12.929h10.214V5.5H1.892v7.429zm2.786-8.822v-2.09a.226.226 0 00-.066-.166.226.226 0 00-.166-.065H3.98a.226.226 0 00-.167.065.226.226 0 00-.065.167v2.09c0 .067.022.122.065.166.044.044.1.065.167.065h.465a.226.226 0 00.166-.065.226.226 0 00.066-.167zm5.571 0v-2.09a.226.226 0 00-.065-.166.226.226 0 00-.167-.065h-.464a.226.226 0 00-.167.065.226.226 0 00-.065.167v2.09c0 .067.021.122.065.166.043.044.099.065.167.065h.464a.226.226 0 00.167-.065.226.226 0 00.065-.167zm2.786-.464v9.286c0 .251-.092.469-.276.652a.892.892 0 01-.653.276H1.892a.892.892 0 01-.653-.275.892.892 0 01-.276-.653V3.643c0-.252.092-.47.276-.653a.892.892 0 01.653-.276h.929v-.696c0-.32.113-.593.34-.82.228-.227.501-.34.82-.34h.465c.319 0 .592.113.82.34.227.227.34.5.34.82v.696h2.786v-.696c0-.32.114-.593.34-.82.228-.227.501-.34.82-.34h.465c.32 0 .592.113.82.34.227.227.34.5.34.82v.696h.93c.25 0 .468.092.652.276a.892.892 0 01.276.653z" />
							</svg>
							<a href="' . esc_url( get_the_permalink() ) . '"> ' . get_the_date() . '</a></span>' : '';
				$html  .= '<div class="mzb-post">';
				$html  .= '';
				$html  .= $image;
				if ( $meta_position == 'top' ) {
					$html .= '<div class="mzb-post-content">';
					$html .= '<div class="mzb-post-entry-meta">';
					$html .= $date;
					$html .= $author;
					$html .= '</div>';
					$html .= $title;
				} elseif ( $meta_position == 'bottom' ) {
					$html .= '<div class="mzb-post-content">';
					$html .= $title;
					$html .= '<div class="mzb-post-entry-meta">';
					$html .= $date;
					$html .= $author;
					$html .= '</div>';
				}
				if ( $enable_excerpt || $enable_readmore ) {
					$html .= '<div class="mzb-entry-content">';
					$html .= $enable_excerpt ? '<div class="mzb-entry-summary"><p> ' . get_the_excerpt() . '</p></div>' : '';
					$html .= $enable_readmore ? '<div class="mzb-read-more"><a href="' . esc_url( get_the_permalink() ) . '">' . $read_more_text . ' </a></div>' : '';
					$html .= '</div>';
				}
				$html .= '</div>';
				$html .= '</div>';
			}

			$html .= '</div>';

			// Custom pagination function.
			if ( $enable_pagination ) {
				$html .= mzb_numbered_pagination( $query->max_num_pages, $paged, $client_id );
			}

			$html .= '</div>';
			$query->reset_postdata();
		}
		return $html;
	}
}
