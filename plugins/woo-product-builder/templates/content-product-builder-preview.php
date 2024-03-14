<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}


$message_success = $settings->get_message_success();
$back_url        = get_the_permalink();

?>

<div class="woocommerce-product-builder">
	<form method="POST" action="<?php echo wc_get_cart_url() ?>" class="woopb-form">
		<?php wp_nonce_field( '_woopb_add_to_woocommerce', '_nonce' ) ?>
		<input type="hidden" name="woopb_id" value="<?php echo esc_attr( get_the_ID() ) ?>" />
		<h2><?php esc_html_e( 'Your chosen list', 'woo-product-builder' ); ?></h2>
		<?php
		if ( is_array( $products ) && count( $products ) ) { ?>
			<table class="woocommerce-product-builder-table">
				<thead>
				<tr>
					<th width="15%"></th>
					<th width="35%"><?php esc_html_e( 'Product', 'woo-product-builder' ) ?></th>
					<th width="15%"><?php esc_html_e( 'Price', 'woo-product-builder' ) ?></th>
					<th width="15%"><?php esc_html_e( 'Total', 'woo-product-builder' ) ?></th>
					<th width="5%"></th>

				</tr>
				</thead>
				<tbody>
				<?php
				$index = 1;
				$total = 0;
				foreach ( $products as $step_id => $items ) {
					foreach ( $items as $product_id => $quantity ) {
						$product       = wc_get_product( $product_id );
						$product_title = $product->get_title();
						$prd_des       = $product->get_short_description();
						if ( ! empty( get_the_post_thumbnail( $product_id ) ) ) {
							$prd_thumbnail = get_the_post_thumbnail( $product_id, 'thumbnail' );
						} else {
							$prd_thumbnail = wc_placeholder_img();
						}
						$product_price = $product->get_price();

						?>
						<tr>

							<td><?php echo wp_kses_post($prd_thumbnail); ?></td>
							<td>
								<a target="_blank" href="<?php echo get_permalink( $product_id ); ?>" class="vi-chosen_title"><?php echo esc_html( $product_title ); ?></a> x <?php echo esc_html( $quantity ) ?>
							</td>
							<td><?php echo wp_kses_post($product->get_price_html() )?></td>

							<td class="woopb-total">
								<?php echo wc_price( ( $product_price * $quantity ) ) ?>
							</td>
							<td>
								<?php
								$arg_remove = array(
									'stepp'      => $step_id,
									'product_id' => $product_id,
								);
								?>
								<a class="woopb-step-product-added-remove" href="<?php echo wp_nonce_url( add_query_arg( $arg_remove ), '_woopb_remove_product_step', '_nonce' ) ?>"></a>
							</td>
						</tr>
						<?php
						$total = $total + intval( $product_price );
					}
				} ?>
				</tbody>

			</table>
			<a href="<?php echo esc_url( $back_url ); ?>" class="woopb-button"><?php esc_attr_e( 'Back', 'woo-product-builder' ) ?></a>
			<button class="woopb-button woopb-button-primary"><?php esc_html_e( 'Add to cart', 'woo-product-builder' ) ?></button>
			<?php
			$settings = new VI_WPRODUCTBUILDER_F_Data();
			if ( $settings->enable_email() ) { ?>

				<a id="vi_wpb_sendtofriend" class="woopb-button"><?php esc_attr_e( 'Send email to your friend', 'woo-product-builder' ) ?></a>
			<?php } ?>
			<?php
		}

		?>
	</form>
</div>
