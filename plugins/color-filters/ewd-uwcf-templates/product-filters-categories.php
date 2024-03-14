<div class='ewd-uwcf-thumbnail-links ewd-uwcf-wc-shop-product-categories'>
	<div class='ewd-uwcf-shop-product-categories-title'><?php echo esc_html( $this->get_label( 'label-thumbnail-categories' ) ); ?></div>
	<div class='ewd-uwcf-shop-product-categories-container'>

		<?php foreach ( $this->get_category_terms() as $term ) { ?>
	
			<div class='ewd-uwcf-category-wrap'>
				<a href='<?php echo esc_attr( $this->get_filtering_url( 'product_cat', $term->slug ) ); ?>'><?php echo esc_html( $term->name ); ?></a>
			</div>
		<?php } ?>
		
		<div class='ewd-uwcf-clear'></div>
	</div>
</div>