<?php
/**
 * Number pagination.
 *
 * @link       https://shapedplugin.com/
 * @since      2.5.0
 *
 * @package     woo-product-slider
 * @subpackage woo-product-slider/Frontend/views/templates
 */

$total_posts                    = $shortcode_query->found_posts;
$total_posts                    = $number_of_total_products < $total_posts ? $number_of_total_products : $total_posts;
$shortcode_query->max_num_pages = ceil( (int) $total_posts / (int) $products_per_page );
$big                            = 999999999;

if ( ( $shortcode_query->max_num_pages > 1 && $grid_pagination ) ) {
	$grid_pagination_data  = '<div class="wps-pagination ' . $grid_pagination_alignment . '">';
	$args                  = array(
		'format'    => '?paged' . $post_id . '=%#%',
		'total'     => $shortcode_query->max_num_pages,
		'current'   => isset( $_GET[ "$paged_var" ] ) ? wp_unslash( $_GET[ "$paged_var" ] ) : 1,
		'prev_next' => true,
		'type'      => 'array',
		'next_text' => '<i class="fa fa-angle-right"></i>',
		'prev_text' => '<i class="fa fa-angle-left"></i>',
	);
	$items                 = paginate_links( $args );
	$pagination            = "<ul>\n\t<li>";
	$pagination           .= join( "</li>\n\t<li>", $items );
	$pagination           .= "</li>\n</ul>\n";
	$grid_pagination_data .= $pagination;
	$grid_pagination_data .= '</div>';

	echo wp_kses_post( $grid_pagination_data );
}
