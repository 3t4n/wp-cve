<?php

$terms = get_terms(
	array(
		'taxonomy'   => 'product_cat',
		'orderby'    => $settings->orderby,
		'order'      => $settings->order,
		'hide_empty' => ( 'yes' === $settings->hide_empty ),
		'exclude'    => $settings->exclude,
		'number'     => $settings->term_per_page,
	)
);

if ( 'yes' === $settings->cat_only_image ) {
	$terms = get_terms(
		array(
			'meta_query' => array(
				array(
					'key'     => 'thumbnail_id',
					'value'   => 0,
					'compare' => '!=',
				),
			),
		)
	);
}

?>

<div class="xpro-product-grid-wrapper xpro-woo-product-cat-wrapper xpro-woo-product-cat-layout-<?php echo esc_attr( $settings->layout ); ?>">
   <div class="xpro-woo-product-grid-main xpro-woo-product-cat-inner cbp">

		<?php
		if ( $terms ) {
			foreach ( $terms as $term ) :
				$description = $term->description;
				$limit       = $settings->content_length ? $settings->content_length : 15;
				$content     = explode( ' ', $description, $limit );
				if ( count( $content ) >= $limit ) {
					array_pop( $content );
					$content = implode( ' ', $content ) . '...';
				} else {
					$content = implode( ' ', $content );
				}
				$content = preg_replace( '`[[^]]*]`', '', $content );

				$term_id = $term->term_id;
				?>

			<div id="xpro-woo-product-grid-id-<?php echo esc_attr( $term_id ); ?>" class="cbp-item xpro-woo-product-grid-item">
				<?php if ( 'yes' === $settings->clickable_div ) : ?>
					<a class="xpro-woo-product-grid-box-link" href="<?php echo esc_url( get_term_link( $term ) ); ?>"></a>
				<?php endif; ?>

				<div class="xpro-woo-product-grid-img">
					<div class="xpro-woo-product-img-section">
						<?php
						$image_id = get_term_meta( $term_id, 'thumbnail_id', true );
						$img_url  = wp_get_attachment_image_src( $image_id, '', true );
						if ( ( ! empty( $image_id ) ) && 0 !== $image_id && $image_id > 0 ) {
							$img_src = $img_url[0];
						} else {
							$placeholder_url = esc_url( XPRO_ADDONS_FOR_BB_URL . 'assets/images/placeholder.png' );
							$img_src         = $placeholder_url;
						}

						?>
						<img class="xpro-woo-product-grid-img xpro-product-img-url" src="<?php echo esc_url( $img_src ); ?>" alt="<?php echo esc_html( $term->name ); ?>">
					</div>
				</div>

				<div class="xpro-woo-product-grid-content-sec">
					<div class="xpro-woo-product-grid-inner-content-sec">
						<?php if ( 'yes' === $settings->show_title ) { ?>
							<a class="xpro-woo-product-grid-title-wrapper" href="<?php echo esc_url( get_term_link( $term ) ); ?>">
								<h2 class="xpro-woo-product-grid-title">
									<?php
									echo esc_html( $term->name );
									if ( $settings->show_count ) {
										?>
										(<?php echo esc_html( $term->count ); ?>)
									<?php } ?>
								</h2>
							</a>
						<?php } ?>

						<?php if ( 'yes' === $settings->show_content ) { ?>
                            <p class="xpro-woo-product-grid-excerpt"><?php echo $content; ?></p>
						<?php } ?>

						<?php if ( 'yes' === $settings->show_cta ) { ?>
							<div class="xpro-woo-product-grid-btn-section">
								<div class="xpro-woo-product-grid-shop-btn">
									<?php if ( ! empty( $settings->btn_txt ) ) { ?>
										<a class="xpro-woo-cart-btn" href="<?php echo esc_url( get_term_link( $term ) ); ?>">
											<?php echo esc_html( $settings->btn_txt ); ?>
										</a>
									<?php } ?>
								</div>
							</div>
						<?php } ?>

					</div>
				</div>

			</div>


				<?php
			endforeach;
		} else {
			?>
			<p class="xpro-alert xpro-alert-warning">
				<span class="xpro-alert-title"><?php esc_html__( 'No Category Found!', 'xpro-bb-addons' ); ?></span>
				<span class="xpro-alert-description"><?php echo esc_html__( 'Sorry, but nothing matched your selection. Please try again with some different keywords.', 'xpro-bb-addons' ); ?></span>
			</p>

		<?php } ?>
   </div>
</div>
