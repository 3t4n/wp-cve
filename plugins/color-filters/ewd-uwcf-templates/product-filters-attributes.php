<div class='ewd-uwcf-thumbnail-links ewd-uwcf-wc-shop-product-attribute ewd-uwcf-wc-shop-product-<?php echo esc_attr( $attribute_taxonomy->attribute_name ); ?>'>
	<div class='ewd-uwcf-shop-product-attribute-title'><?php echo esc_html( sprintf( $this->get_label( 'label-thumbnail-attributes' ), $attribute_taxonomy->attribute_label ) ); ?></div>
	<div class='ewd-uwcf-shop-product-attribute-container'>

		<?php foreach ( $this->get_attribute_terms() as $term ) { ?>
	
			<div class='ewd-uwcf-attribute-wrap'>
				<a href='<?php echo esc_attr( $this->get_filtering_url( 'pa_' . $attribute_taxonomy->attribute_name, $term->slug ) ); ?>'><?php echo esc_html( $term->name ); ?></a>
			</div>
		<?php } ?>
		
		<div class='ewd-uwcf-clear'></div>
	</div>
</div>