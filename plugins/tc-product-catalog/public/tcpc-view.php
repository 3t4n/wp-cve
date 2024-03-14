<?php
/**
 * public view functionality.
 *
 * @link       http://themescode.com
 * @since      1.0.0
 *
 * @package    TC Product Catalog
 * tc  is used as short
 **/

add_shortcode('tcproduct-catalog', 'tcproduct_catalog_view' );


function tcproduct_catalog_view($atts) {

	// Attributes
extract( shortcode_atts(
	array(
		'posts_num' => "-1",
		'order' => 'DESC',
		'orderby' => '',
		'title' => 'yes',
		'catalog'=>'',
		'style'=>'one',
		'border'=>'no',
		'hover'=>'',
		'tooltip'=>'',

	), $atts )
);

	$args = array(
			'orderby' => 'date',
			 'order' => $order,
				'tcpc_catalog' =>$catalog,
				 'showposts' => $posts_num,
				'post_type' => 'tcpc'
	);
	  $tcpc_loop = new WP_Query($args);
 			global $post;
	  $output = '<div class="tcpc-catalog-wrap">';
	  if($tcpc_loop->have_posts()){
	      while($tcpc_loop->have_posts()) {
					$tcpc_loop->the_post();
					// add terms
					$currency_icon =get_post_meta(get_the_ID(), 'tcpc_fields_currency_icon', true);
					$regular_price = get_post_meta(get_the_ID(), 'tcpc_fields_regular_price', true);
					$sale_price= get_post_meta(get_the_ID(), 'tcpc_fields_sale_price', true);
						$terms = get_the_terms( $post->ID, 'tcpc_category' );
										if ( $terms && ! is_wp_error( $terms ) ) :
												$links = array();
												foreach ( $terms as $term ) {
														$links[] = $term->name;
												}
												$tax_links = join( ", ", str_replace(' ', '-', $links));
												$tax = strtolower($tax_links);
											//$tax;
										else :
									$tax = '';
								endif;
					// end add terms

			    $tcpc_thumbnail = get_the_post_thumbnail(get_the_ID(), 'full');
								// = get_terms($post->ID, 'tcpc_category');

           $output .= '<div class="tcpc-single-product">';
			          $output .= '<div class="tcpc-item-image">';
								if(!empty($sale_price)){
									$output .= '<div class="tcpc-item-price">	<span class="tcpc-price">'.$currency_icon. $regular_price.'</span><span class="tcpc-sale-price">'.$currency_icon .$sale_price.'</span></div>';
								}elseif(!empty($regular_price)){
									$output .= '<div class="tcpc-item-price"><span class="">'.$currency_icon. $regular_price.'</span></div>';
								}

			              // $output .='<a href="'.get_the_permalink().'">'. $tcpc_thumbnail.'</a>';
			              $output .=$tcpc_thumbnail;

							 $output .='<div class="tcpc-overlay"> <a class="tcpc-link-hover" href="'.get_the_permalink().'"><i class="fa fa-external-link" aria-hidden="true"></i></a> </div>';

								$output .= '</div>';


								$output .= '<div class="tcpc-item-details">';

									 $output .='<h3 class="tcpc-item-title"> <a class="tcpc-link" href="'.get_the_permalink().'">'.get_the_title() . '</a> </h3>';
									 $output .='<p class="tcpc-item-title"><i class="fa fa-angle-right" aria-hidden="true"></i> '.$tax.'</p>';

								$output .= '</div>'; // tcpc-item-details

				$output .= '</div>'; // tcpc-single-product
	      } //end
	  } else {
	      echo 'No Product Was Found.';
	  }
	  wp_reset_postdata();
	  wp_reset_query();
	  $output .= '</div>';
	  $output .= '</div>';
    $output .='<div style="clear:both"> </div>';
	  return $output;


}
 ?>
