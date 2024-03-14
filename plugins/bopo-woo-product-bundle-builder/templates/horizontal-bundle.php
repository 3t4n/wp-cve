<?php
$settings = VI_WOO_BOPO_BUNDLE_DATA::get_instance();

if ( isset( $args['items'] ) ) {
	$items = $args['items'];
}
if ( isset( $args['bundle_id'] ) ) {
	$bundle_id      = $args['bundle_id'];
	$product_bundle = wc_get_product( $bundle_id );
}
if ( isset( $args['product_array'] ) ) {
	$product_arr = $args['product_array'];
}
if ( isset( $args['bopo_fixed_price'] ) ) {
	$bopo_fixed_price = $args['bopo_fixed_price'];
}
if ( isset( $args['bopo_fixed_sale'] ) ) {
	$bopo_fixed_sale = $args['bopo_fixed_sale'];
}
if ( isset( $args['bopo_tax_array'] ) ) {
	$bopo_tax_array = $args['bopo_tax_array'];
}
if ( isset( $args['bopo_mode'] ) ) {
	$bopo_mode = $args['bopo_mode'];
} else {
	$bopo_mode = '';
}
if ( isset( $args['bopo_template'] ) ) {
	$bopo_template = $args['bopo_template'];
} else {
	$bopo_template = '';
}
$p_description = [];
$p_ratting     = [];
for ( $i = 0; $i < $items['count']; $i ++ ) {
	if ( ! empty( $product_arr[ $i ][0] ) ) {
		if ( $product_arr[ $i ][0] instanceof WC_Product_Variation ) {
			$p_id                = $product_arr[ $i ][0]->get_parent_id();
			$p_prd               = wc_get_product( $p_id );
			$p_description[ $i ] = $p_prd->get_short_description();
			if ( $p_prd->get_rating_count() != '0' && ! empty( $p_prd->get_rating_count() ) ) {
				$p_ratting[ $i ] = wc_get_rating_html( $p_prd->get_average_rating() );
			}
		} else {
			$p_description[ $i ] = $product_arr[ $i ][0]->get_short_description();
			if ( $product_arr[ $i ][0]->get_rating_count() != '0' ) {
				$p_ratting[ $i ] = wc_get_rating_html( $product_arr[ $i ][0]->get_average_rating() );
			}
		}
	}
}
?>
    <div class="bopobb-single-wrap bopobb-single-wrap-<?php echo esc_attr( $bundle_id ); ?>" data-id="<?php echo esc_attr( $bundle_id ); ?>">
        <div class="bopobb-custom-title">
			<?php
			if ( ! empty( $items['title'] ) ) {
				echo esc_html( $items['title'] );
			}
			?>
        </div>
        <div class="bopobb-items-top-wrap">
        </div>
        <div class="bopobb-items-bottom-wrap bopobb-template-2">
            <table class="bopobb-detail-table" data-fixed-price="<?php echo esc_attr( $bopo_fixed_price ) ?>" data-fixed-sale="<?php echo esc_attr( $bopo_fixed_sale ) ?>"
                   data-price-suffix="<?php echo esc_attr( htmlentities( $product_bundle->get_price_suffix() ) ); ?>"
                   data-tax-include="<?php echo esc_attr( $bopo_tax_array['include'] ); ?>" data-tax-exempt="<?php echo esc_attr( $bopo_tax_array['exempt'] ); ?>"
                   data-tax-view="<?php echo esc_attr( $bopo_tax_array['view'] ); ?>" data-tax-rate="<?php echo esc_attr( $bopo_tax_array['rate'] ); ?>">
                <tbody>
				<?php
				for ( $i = 0; $i < $items['count']; $i ++ ) {
					?>
                    <tr class="bopobb-item-product bopobb-item-<?php echo esc_attr( $i ) ?>" data-discount-type="<?php echo esc_attr( $product_arr[ $i ][8] ) ?>"
                        data-discount-number="<?php echo esc_attr( $product_arr[ $i ][9] ) ?>" data-price="<?php echo esc_attr( $product_arr[ $i ][5] ) ?>"
                        data-qty="<?php echo esc_attr( $product_arr[ $i ][4] ) ?>" data-id="<?php echo esc_attr( $product_arr[ $i ][1] ) ?>"
                        data-max="<?php echo esc_attr( $product_arr[ $i ][10] ) ?>" data-change="<?php echo esc_attr( $product_arr[ $i ][12] ); ?>"
                        data-item="<?php echo esc_attr($i); ?>">
                        <th>
                            <div class="bopobb-item-img-wrap bopobb-item-change" data-item="<?php echo esc_attr( $i ) ?>"
                                 data-product="<?php echo esc_attr( $bundle_id ) ?>">
                            <span class="bopobb-item-img" title="<?php echo esc_attr( $product_arr[ $i ][2] ) ?>">
                                <?php if ( $product_arr[ $i ][3] ) {
	                                echo wp_kses_post( $product_arr[ $i ][3] );
                                } else {
	                                ?>
                                    <p class="bopobb-icon-plus2"></p>
	                                <?php
                                } ?>
                            </span>
                            </div>
                            <div class="bopobb-item-detail">
                                <a class="bopobb-item-title <?php if ( $settings->get_params( 'bopobb_link_individual' ) == 2 )
									echo esc_attr( 'bopobb-item-change' ) ?>" data-item="<?php echo esc_attr( $i ) ?>"
                                   data-product="<?php echo esc_attr( $bundle_id ) ?>" <?php
								if ( $settings->get_params( 'bopobb_view_description' ) ) {
									?> title="<?php if ( ! empty( $p_description[ $i ] ) ) {
										echo esc_attr( $p_description[ $i ] );
									} ?>" <?php
								}
								if ( $settings->get_params( 'bopobb_link_individual' ) == 1 && ! empty( $product_arr[ $i ][1] ) ) {
									echo esc_attr( ' href=' . get_permalink( $product_arr[ $i ][1] ) . ' target=_blank' );
								}
								?>>
									<?php $item_title = $product_arr[ $i ][2];
									if ( $settings->get_params( 'bopobb_view_quantity' ) ) {
										$item_title .= ' x' . $product_arr[ $i ][4];
									}
									echo esc_attr( $item_title ) ?>
                                </a>
								<?php
								if ( $settings->get_params( 'bopobb_view_ratting' ) ) {
									if ( ! empty( $p_ratting[ $i ] ) ) {
										echo wp_kses_post( $p_ratting[ $i ] );
									}
								}
								if ( $settings->get_params( 'bopobb_view_stock' ) ) {
									if ( ! empty( $product_arr[ $i ][0] ) ) {
										echo wp_kses_post( wc_get_stock_html( $product_arr[ $i ][0] ) );
									}
								}
								?>
                                <input type="hidden" class="bopobb-item-variations" value="<?php echo esc_attr( $product_arr[ $i ][11] ) ?>" readonly="readonly">
                            </div>
                            <div class="bopobb-item-change-wrap">
                                <div class="bopobb-item-change bopobb-item-<?php echo esc_attr( $i ) ?>" data-item="<?php echo esc_attr( $i ) ?>"
                                     data-product="<?php echo esc_attr( $bundle_id ) ?>" title="<?php echo esc_attr( $settings->get_params( 'bopobb_swap_text' ) ); ?>">
                                    <p class="bopobb-icon-pencil"></p>
                                </div>
                            </div>
                        </th>
                        <td>
							<?php
							if ( ! empty( $product_arr[ $i ][0] ) ) {
								echo wp_kses_post( $product_arr[ $i ][0]->get_price_html() );
							}
							?>
                        </td>
                    </tr>
				<?php } ?>
                </tbody>
            </table>
        </div>
        <div class="bopobb-alert bopobb-text"></div>
    </div>
<?php
