<?php

/**
 * Frontend content.
 *
 * @package  Description.
 * @category Template.
 * */

?>

<?php

$args = array(
	'taxonomy'     => 'product_cat',
	'orderby'      => 'name',
	'show_count'   => 0,
	'pad_counts'   => 0,
	'hierarchical' => 1,
	'title_li'     => '',
	'hide_empty'   => 0,
);

$all_categories = get_categories( $args );

$navigation = ' <div class="swiper-button-next next-' . $attributes['blockId'] . '"><i class="' . $attributes['arrowNext'] . '"></i></div>
    <div class="swiper-button-prev prev-' . $attributes['blockId'] . '"> <i class="' . $attributes['arrowPrev'] . '"></i></div> ';

$pagination = ' <div class="swiper-pagination pagination-' . $attributes['blockId'] . '"></div>';

?>
<div 
	class="op-block op-block-<?php
	echo esc_attr(
		$attributes['carousel'] ?
			$attributes['blockId'] . ' swiper' :
			$attributes['blockId']
	)
	?>">

	<?php
		$has_navigation = str_contains( $attributes['options'], 'arrow' );
		$has_pagination = str_contains( $attributes['options'], 'pagination' );

	if ( $attributes['carousel'] ) {
		if ( false !== $has_navigation ) {
			echo $navigation;
		}

		if ( false !== $has_pagination ) {
			echo $pagination;
		}
	}
	?>

	<div class="op-product-column-<?php echo esc_attr( $attributes['carousel'] ? $attributes['columns'] . ' swiper-wrapper' : $attributes['columns'] . ' product-grid-wrap op-woo__category-card-wrapper' );?>">

		<pre style="display:none" id="op-style">
			<?php echo wp_json_encode( $attributes ); ?>
		</pre>

		<?php

		/**
		 * Function to filter only parent categories.
		 */
		if ( ! function_exists( 'filterArr' ) ) {
			function filterArr( $element ) {
				return $element->parent == 0;
			}
		}

		if ( ! empty( $all_categories ) && is_array( $all_categories ) ) {
			if ( ! $attributes['subCategory'] ) {
				$all_categories = array_filter( $all_categories, 'filterArr' );
			}
			$category_rows_count = ( ( $attributes['rows'] ?? 1 ) * ( $attributes['columns'] ?? 3 ) );

			$all_categories = array_slice( $all_categories, 0, $category_rows_count );

			foreach ( $all_categories as $cata ) {
				$term = get_term_by( 'slug', $cata->slug, 'product_cat' );

				if ( $term && ! is_wp_error( $term ) ) {
					$category_link = get_term_link( $term, 'product_cat' );
				}

				$cat_thumb_id = get_term_meta( $cata->term_id, 'thumbnail_id', true );

				$shop_catalog_img = wp_get_attachment_image_src( $cat_thumb_id, 'shop_catalog' );

				$term_link = get_term_link( $cata, 'product_cat' );

					?>
					<div class="op-woo__category-card op-woo__category <?php echo esc_attr( $attributes['carousel'] ? 'swiper-slide' : '' ); ?>">

						<?php if ( 'one' === $attributes['preset'] ) { ?>
							<a href="<?php echo esc_attr( $category_link ); ?>" class="op-woo__category-pre1 op-woo__card">
								<img class="op-woo__category-image" src="<?php echo esc_attr( $shop_catalog_img[0] ?? OMNIPRESS_URL . 'assets/images/placeholder_category.jpeg' ); ?>" alt="<?php echo esc_attr( $cata->slug ); ?>" />
								<div class="op-woo__category-card-title op-woo__category-pre1-title op-woo__card-content">
									<h3 class="op-woo__category-title">
										<?php echo  $cata->name; ?>
									</h3>
									<p class="op-woo__category-pre1-count"><?php echo esc_html( $cata->category_count ); ?> products</p>
									<button class="op-woo__category-pre1-shop op-woo__category-button"> Shop now</button>
								</div>
							</a>
						<?php } ?>


						<?php if ( 'two' === $attributes['preset'] ) { ?>
							<a href="<?php echo esc_attr( $category_link ); ?>" class="op-woo__category-card-content op-woo__category-pre2 op-woo__card">
								<figure>
									<img class="op-woo__category-image" src="<?php echo esc_attr( $shop_catalog_img[0] ?? OMNIPRESS_URL . 'assets/images/placeholder_category.jpeg' ); ?>" alt="<?php echo esc_attr( $cata->slug ); ?>" />
								</figure>
								<div class="op-woo__category-card-title op-woo__card-content">
									<h3 class="op-woo__category-card-title-name"> <?php echo $cata->name; ?> </h3>
								</div>
							</a>
						<?php } ?>

						<?php if ( 'three' === $attributes['preset'] ) { ?>
							<div class="op-woo__category-pre3 op-woo__card" title="<?php echo  $cata->name ; ?>">
								<a href="<?php echo esc_attr( $category_link ); ?>" class="op-woo__category-pre3-label">
									<h3 class="op-woo__category-title"><?php echo $cata->name ?></h3>
								</a>

								<figure>
									<img class="op-woo__category-image" src="<?php echo esc_attr( $shop_catalog_img[0] ?? OMNIPRESS_URL . 'assets/images/placeholder_category.jpeg' ); ?>" class="" decoding="async" />
								</figure>
							</div>
						<?php } ?>
					</div>
						<?php
			}
		}
		?>
	</div>
</div>
