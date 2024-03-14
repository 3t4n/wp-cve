<div class='ewd-uwcf-thumbnail-links ewd-uwcf-wc-shop-product-sizes'>
	<div class='ewd-uwcf-shop-product-sizes-title'><?php echo esc_html( $this->get_label( 'label-thumbnail-sizes' ) ); ?></div>
	<div class='ewd-uwcf-shop-product-sizes-container'>

		<?php foreach ( $this->get_size_terms() as $term ) { ?>
	
			<div class='ewd-uwcf-size-wrap'>
				<a href='<?php echo esc_attr( $this->get_filtering_url( 'product_size', $term->slug ) ); ?>'><?php echo esc_html( $term->name ); ?></a>
			</div>
		<?php } ?>
		
		<div class='ewd-uwcf-clear'></div>
	</div>
</div>