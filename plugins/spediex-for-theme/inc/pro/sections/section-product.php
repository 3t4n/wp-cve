<?php
if ( ! function_exists( 'woocommerce_product_section_activate' ) ) {
	function woocommerce_product_section_activate(){
		?>
		<div class="woocommerce_product_sections">
			<div class="widget_product_data">
				<?php dynamic_sidebar('woocommerce_product'); ?>
			</div>		
		</div>
	<?php
	}
}