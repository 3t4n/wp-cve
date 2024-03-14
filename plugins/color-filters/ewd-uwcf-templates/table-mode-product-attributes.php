<?php foreach ( $this->filtering->get_attribute_terms() as $term ) { ?>
	
	<div class='ewd-uwcf-<?php echo esc_attr( $this->filtering->current_attribute->attribute_name ); ?>-wrap'><?php echo esc_html( $term->name ); ?></div>
<?php } ?>