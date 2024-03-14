<?php
/**
 * Post List block.
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
class PostList extends AbstractBlock {

	/**
	 * Block name.
	 *
	 * @var string Block name.
	 */
	protected $block_name = 'post-list';

	public function render( $attributes, $content, $block ) {

		$client_id    = magazine_blocks_array_get( $attributes, 'clientId', '' );
		$css_id       = magazine_blocks_array_get( $attributes, 'cssID', '' );
		$class_name   = magazine_blocks_array_get( $attributes, 'className', '' );
		$image_toggle = magazine_blocks_array_get( $attributes, 'imageToggle', 'true' );
		$position     = magazine_blocks_array_get( $attributes, 'position', 'left' );

		// Query.
		$category          = magazine_blocks_array_get( $attributes, 'category', '' );
		$tag               = magazine_blocks_array_get( $attributes, 'tag', '' );
		$excluded_category = magazine_blocks_array_get( $attributes, 'excludedCategory', '' );
		$order_by          = magazine_blocks_array_get( $attributes, 'orderBy', '' );
		$order_type        = magazine_blocks_array_get( $attributes, 'orderType', '' );
		$author            = magazine_blocks_array_get( $attributes, 'authorName', '' );
		$post_count        = magazine_blocks_array_get( $attributes, 'postCount', '' );

		// General.
		$layout                  = magazine_blocks_array_get( $attributes, 'layout', '' );
		$layout_1_advanced_style = magazine_blocks_array_get( $attributes, 'layout1AdvancedStyle', '' );
		$layout_2_advanced_style = magazine_blocks_array_get( $attributes, 'layout2AdvancedStyle', '' );

		// Heading.
        $enable_heading                  = magazine_blocks_array_get( $attributes, 'enableHeading', '' );
		$heading_layout                  = magazine_blocks_array_get( $attributes, 'headingLayout', '' );
		$heading_layout_1_advanced_style = magazine_blocks_array_get( $attributes, 'headingLayout1AdvancedStyle', '' );
		$heading_layout_2_advanced_style = magazine_blocks_array_get( $attributes, 'headingLayout2AdvancedStyle', '' );

		//  Image.
		$hoverAnimation = magazine_blocks_array_get( $attributes, 'hoverAnimation', '' );

		// Meta.
		$meta_position = magazine_blocks_array_get( $attributes, 'metaPosition', '' );
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

		// Pagination.
		$paged         = isset( $_GET[ 'block_id_' . $client_id ] ) ? max( 1, intval( $_GET[ 'block_id_' . $client_id ] ) ) : 1;
		$args['paged'] = $paged;

		// Add the filter to modify the excerpt length using the anonymous function
		add_filter( 'excerpt_length', $custom_excerpt_length );

		if ( $heading_layout === 'heading-layout-1' ) {
			$heading_style = $heading_layout_1_advanced_style;
		} elseif ( $heading_layout === 'heading-layout-2' ) {
			$heading_style = $heading_layout_2_advanced_style;
		}

		if ( $layout === 'layout-1' ) {
			$advanced_style = $layout_1_advanced_style;
		} elseif ( $layout === 'layout-2' ) {
			$advanced_style = $layout_2_advanced_style;
		}

		$args = array(
			'posts_per_page'      => $post_count,
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

		$cat_name = get_cat_name( $category );

		$cat_name = empty( $cat_name ) ? 'Latest' : $cat_name;

		$query = new WP_Query( $args );

		# The Loop.
		$html = '';

		if ( $query->have_posts() ) {
			$html .= '<div id="' . $css_id . '" class="mzb-post-list mzb-post-list-' . $client_id . ' ' . $class_name . '">';
			$html .= $enable_heading ? '<div class="mzb-post-heading' . ' mzb-' . $heading_layout . ' mzb-' . $heading_style . '"> <h2>' . $cat_name . '</h2></div>' : '';
			$html .= '<div class="mzb-posts' . ' mzb-' . $layout . '' . ' mzb-' . $advanced_style . '">';

			while ( $query->have_posts() ) {
				$query->the_post();
				$id    = get_post_thumbnail_id();
				$src   = wp_get_attachment_image_src( $id );
				$src   = has_post_thumbnail( get_the_ID() ) ? get_the_post_thumbnail_url( get_the_ID() ) : '';
				$image  = $src ? '<div class="mzb-featured-image ' . $hoverAnimation . '"><a href="' . esc_url( get_the_permalink() ) . '"alt="' . get_the_title() . '"/><img src="' . esc_url( $src ) . '" alt="' . get_the_title() . '"/> </a></div>' : '';
				$img   = ( $image_toggle == true ) ? $image : '';
				if ( ! $src ) {
					$position_class = "$position no-thumbnail";
				} else {
					$position_class = $position;
				}
				$title = '<h3 class="mzb-post-title"><a href="' . esc_url( get_the_permalink() ) . '">' . get_the_title() . '</a></h3>';
				$date  = $enable_date ? '<span class ="mzb-post-date"><svg class="mzb-icon mzb-icon--calender" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 14 14">
								<path d="M1.892 12.929h10.214V5.5H1.892v7.429zm2.786-8.822v-2.09a.226.226 0 00-.066-.166.226.226 0 00-.166-.065H3.98a.226.226 0 00-.167.065.226.226 0 00-.065.167v2.09c0 .067.022.122.065.166.044.044.1.065.167.065h.465a.226.226 0 00.166-.065.226.226 0 00.066-.167zm5.571 0v-2.09a.226.226 0 00-.065-.166.226.226 0 00-.167-.065h-.464a.226.226 0 00-.167.065.226.226 0 00-.065.167v2.09c0 .067.021.122.065.166.043.044.099.065.167.065h.464a.226.226 0 00.167-.065.226.226 0 00.065-.167zm2.786-.464v9.286c0 .251-.092.469-.276.652a.892.892 0 01-.653.276H1.892a.892.892 0 01-.653-.275.892.892 0 01-.276-.653V3.643c0-.252.092-.47.276-.653a.892.892 0 01.653-.276h.929v-.696c0-.32.113-.593.34-.82.228-.227.501-.34.82-.34h.465c.319 0 .592.113.82.34.227.227.34.5.34.82v.696h2.786v-.696c0-.32.114-.593.34-.82.228-.227.501-.34.82-.34h.465c.32 0 .592.113.82.34.227.227.34.5.34.82v.696h.93c.25 0 .468.092.652.276a.892.892 0 01.276.653z" />
							</svg>
							<a href="' . esc_url( get_the_permalink() ) . '"> ' . get_the_date() . '</a></span>' : '';
				$html .= '<div class="mzb-post magazine-post--pos-' . $position_class . '">';
				$html .= '';
				$html .= $img;

				if ( $meta_position == 'top' ) {
					$html .= '<div class="mzb-post-content">';
					$html .= '<div class="mzb-post-entry-meta">';
					$html .= $date;
					$html .= '</div>';
					$html .= $title;
				} elseif ( $meta_position == 'bottom' ) {
					$html .= '<div class="mzb-post-content">';
					$html .= '<div class="mzb-post-entry-meta">';
					$html .= $title;
					$html .= '</div>';
					$html .= $date;
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
			wp_reset_postdata();
		}
		return $html;

	}
}
