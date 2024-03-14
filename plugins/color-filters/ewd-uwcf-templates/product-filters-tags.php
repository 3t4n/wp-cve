<div class='ewd-uwcf-thumbnail-links ewd-uwcf-wc-shop-product-tags'>
	<div class='ewd-uwcf-shop-product-tags-title'><?php echo esc_html( $this->get_label( 'label-thumbnail-tags' ) ); ?></div>
	<div class='ewd-uwcf-shop-product-tags-container'>

		<?php foreach ( $this->get_tag_terms() as $term ) { ?>
	
			<div class='ewd-uwcf-tag-wrap'>
				<a href='<?php echo esc_attr( $this->get_filtering_url( 'product_tag', $term->slug ) ); ?>'><?php echo esc_html( $term->name ); ?></a>
			</div>
		<?php } ?>
		
		<div class='ewd-uwcf-clear'></div>
	</div>
</div>