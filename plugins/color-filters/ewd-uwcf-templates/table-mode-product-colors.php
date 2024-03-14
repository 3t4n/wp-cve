<div class='ewd-uwcf-thumbnail-links ewd-uwcf-wc-shop-product-colors'>
	<div class='ewd-uwcf-shop-product-colors-container'>

		<?php foreach ( $this->filtering->get_color_terms() as $term ) {

			$color = get_term_meta( $term->term_id, 'EWD_UWCF_Color', true );

			?>

			<div class='ewd-uwcf-color-wrap'>
				<a href='<?php echo esc_attr( $this->filtering->get_filtering_url( 'product_color', $term->slug ) ); ?>'>
					<div class='ewd-uwcf-color-preview <?php echo $this->filtering->get_color_shape_class(); ?>' <?php echo ( $color != '' ? 'style="background: ' . esc_attr( $color ) . ';"' : '' ); ?> ></div>
				</a>
			</div>
		<?php } ?>
		
		<div class='ewd-uwcf-clear'></div>
	</div>
</div>