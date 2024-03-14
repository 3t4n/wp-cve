<div class='ewd-upcp-product-comparison'>

	<div class='ewd-upcp-product-comparison-back-to-catalog'>

		<a href='<?php echo esc_attr( $this->ajax_url ); ?>'>
			<?php echo esc_html( $this->get_label( 'label-back-to-catalog' ) ); ?>
		</a>

	</div>

	<div class='ewd-upcp-product-comparison-products'>

		<?php $this->print_product_comparison_products(); ?>

	</div>

</div>