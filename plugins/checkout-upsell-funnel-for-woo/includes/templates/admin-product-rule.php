<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
$index                       = $index ?? '';
$index                       = $index ?: '{index}';
$prefix                      = $prefix ?? '';
$prefix                      = $prefix ?: '{prefix}';
$params                      = isset($params) && is_array($params) ? $params : array();
$type                        = $type ?? 'product_price';
$woo_currency_symbol         = $woo_currency_symbol ?? get_woocommerce_currency_symbol();
$conditions                  = array(
	'product_price'          => esc_html__( 'Product price', 'checkout-upsell-funnel-for-woo' ),
	'product_show_variation' => esc_html__( 'Show variation', 'checkout-upsell-funnel-for-woo' ),
	'product_visibility'     => esc_html__( 'Product visibility', 'checkout-upsell-funnel-for-woo' ),
	'product_include'        => esc_html__( 'Include products', 'checkout-upsell-funnel-for-woo' ),
	'product_exclude'        => esc_html__( 'Exclude products', 'checkout-upsell-funnel-for-woo' ),
	'cats_include'           => esc_html__( 'Include categories', 'checkout-upsell-funnel-for-woo' ),
	'cats_exclude'           => esc_html__( 'Exclude categories', 'checkout-upsell-funnel-for-woo' ),
);
$product_show_variation      = $product_show_variation ?? 1;
$product_visibility          = isset($product_visibility) && is_array($product_visibility)?$product_visibility : array('visible');
$product_include             = isset($product_include) && is_array($product_include) ? $product_include :  array();
$product_exclude             = isset($product_exclude) && is_array($product_exclude) ? $product_exclude : array();
$cats_include                = isset($cats_include) && is_array($cats_include) ? $cats_include : array();
$cats_exclude                = isset($cats_exclude) && is_array($cats_exclude) ? $cats_exclude : array();
$product_price_min           = $product_price['min'] ?? 0;
$product_price_max           = $product_price['max'] ?? '';
$name_condition_type         = $prefix . 'product_rule_type[' . $index . '][]';
$name_product_show_variation = $prefix . 'product_show_variation[' . $index . ']';
$name_product_visibility     = $prefix . 'product_visibility[' . $index . '][]';
$name_product_include        = $prefix . 'product_include[' . $index . '][]';
$name_product_exclude        = $prefix . 'product_exclude[' . $index . '][]';
$name_cats_include           = $prefix . 'cats_include[' . $index . '][]';
$name_cats_exclude           = $prefix . 'cats_exclude[' . $index . '][]';
$name_product_price_min      = $prefix . 'product_price[' . $index . '][min]';
$name_product_price_max      = $prefix . 'product_price[' . $index . '][max]';
?>
<div class="vi-ui placeholder segment vi-wcuf-condition-wrap-wrap vi-wcuf-pd-condition-wrap-wrap">
    <div class="fields">
        <div class="four wide field">
            <select class="vi-ui fluid dropdown vi-wcuf-condition-type vi-wcuf-pd-condition-product_rule_type"
                    data-wcuf_name="<?php echo esc_attr( $name_condition_type ) ?>"
                    data-wcuf_name_default="{prefix_default}product_rule_type[{index_default}][]"
                    data-wcuf_prefix="<?php echo esc_attr( $prefix ); ?>"
                    name="<?php echo esc_attr( $name_condition_type ) ?>">
				<?php
				foreach ( $conditions as $condition_k => $condition_v ) {
					$check = '';
					if ( $type == $condition_k ) {
						$check = 'selected';
					}
					echo sprintf( '<option value="%s" %s >%s</option>', $condition_k, $check, esc_html( $condition_v ) );
				}
				?>
            </select>
        </div>
        <div class="thirteen wide field vi-wcuf-condition-value-wrap-wrap">
            <div class="field vi-wcuf-condition-wrap vi-wcuf-pd-condition-wrap vi-wcuf-condition-product_price-wrap <?php echo esc_attr( $type === 'product_price' ? '' : 'vi-wcuf-hidden' ); ?>">
                <div class="equal width fields">
                    <div class="field">
                        <div class="vi-ui  left labeled input">
                            <div class="vi-ui label vi-wcuf-basic-label">
								<?php echo sprintf( esc_html__( 'Min(%s)', 'checkout-upsell-funnel-for-woo' ), $woo_currency_symbol ) ?>
                            </div>
                            <input type="number" min="0" step="0.01"
                                   name="<?php echo esc_attr($type === 'product_price' ?  $name_product_price_min  : ''); ?>"
                                   data-wcuf_name="<?php echo esc_attr( $name_product_price_min ) ?>"
                                   data-wcuf_name_default="{prefix_default}product_price[{index_default}][min]"
                                   data-wcuf_prefix="<?php echo esc_attr( $prefix ); ?>"
                                   class="vi-wcuf-pd-condition-product_price_min vi-wcuf-condition-value" value="<?php echo esc_attr( $product_price_min ?: 0 ) ?>">
                        </div>
                    </div>
                    <div class="field">
                        <div class="vi-ui  left labeled input">
                            <div class="vi-ui label vi-wcuf-basic-label">
								<?php echo sprintf( esc_html__( 'Max(%s)', 'checkout-upsell-funnel-for-woo' ), $woo_currency_symbol ) ?>
                            </div>
                            <input type="number" min="0" step="0.01"
                                   name="<?php echo esc_attr($type === 'product_price' ?  $name_product_price_max  : ''); ?>"
                                   data-wcuf_allow_empty="1"
                                   data-wcuf_name="<?php echo esc_attr( $name_product_price_max ) ?>"
                                   data-wcuf_name_default="{prefix_default}product_price[{index_default}][max]"
                                   data-wcuf_prefix="<?php echo esc_attr( $prefix ); ?>"
                                   placeholder="<?php esc_attr_e( 'Leave blank to not limit this', 'checkout-upsell-funnel-for-woo' ); ?>"
                                   class="vi-wcuf-pd-condition-product_price_max vi-wcuf-condition-value" value="<?php echo esc_attr( $product_price_max  ) ?>">
                        </div>
                    </div>
                </div>
            </div>
            <div class="field vi-wcuf-condition-wrap vi-wcuf-pd-condition-wrap vi-wcuf-condition-product_show_variation-wrap <?php echo esc_attr($type === 'product_show_variation' ? '' :  'vi-wcuf-hidden' ); ?>">
                <select class="vi-ui fluid dropdown vi-wcuf-pd-condition-product_show_variation"
                        name="<?php echo esc_attr( $type === 'product_show_variation' ? $name_product_show_variation  : ''); ?>"
                        data-wcuf_name_default="{prefix_default}product_show_variation[{index_default}]"
                        data-wcuf_prefix="<?php echo esc_attr( $prefix ); ?>"
                        data-wcuf_name="<?php echo esc_attr( $name_product_show_variation ) ?>">
                    <option value="1" <?php selected( $product_show_variation, 1 ); ?>>
						<?php esc_html_e( 'Yes', 'checkout-upsell-funnel-for-woo' ); ?>
                    </option>
                    <option value="0" <?php selected( $product_show_variation, 0 ); ?>>
						<?php esc_html_e( 'No', 'checkout-upsell-funnel-for-woo' ); ?>
                    </option>
                </select>
            </div>
            <div class="field vi-wcuf-condition-wrap vi-wcuf-pd-condition-wrap vi-wcuf-condition-product_visibility-wrap <?php echo esc_attr($type === 'product_visibility' ? '' :  'vi-wcuf-hidden' ); ?>">
                <select class="vi-ui fluid dropdown vi-wcuf-pd-condition-product_visibility"
                        name="<?php echo esc_attr($type === 'product_visibility' ?  $name_product_visibility  : ''); ?>"
                        data-wcuf_name_default="{prefix_default}product_visibility[{index_default}][]"
                        data-wcuf_prefix="<?php echo esc_attr( $prefix ); ?>"
                        data-wcuf_name="<?php echo esc_attr( $name_product_visibility ) ?>" multiple>
                    <option value="visible" <?php selected( in_array('visible',$product_visibility), true ) ?>>
						<?php esc_html_e( 'Shop and search results', 'checkout-upsell-funnel-for-woo' ); ?>
                    </option>
                    <option value="catalog" <?php selected( in_array('catalog',$product_visibility), true ) ?>>
						<?php esc_html_e( 'Shop only', 'checkout-upsell-funnel-for-woo' ); ?>
                    </option>
                    <option value="search" <?php selected( in_array('search',$product_visibility), true ) ?>>
						<?php esc_html_e( 'Search results only', 'checkout-upsell-funnel-for-woo' ); ?>
                    </option>
                    <option value="hidden" <?php selected( in_array('hidden',$product_visibility), true ) ?>>
						<?php esc_html_e( 'Hidden', 'checkout-upsell-funnel-for-woo' ); ?>
                    </option>
                </select>
            </div>
            <div class="field vi-wcuf-condition-wrap vi-wcuf-pd-condition-wrap vi-wcuf-condition-product_include-wrap <?php echo esc_attr( $type === 'product_include' ? '' : 'vi-wcuf-hidden' ); ?>">
                <select class="vi-wcuf-search-select2 vi-wcuf-search-product vi-wcuf-pd-condition-product_include vi-wcuf-condition-value"
                        data-type_select2="product"
                        data-pd_include="1"
                        name="<?php echo esc_attr($type === 'product_include' ?  $name_product_include  : ''); ?>"
                        data-wcuf_name_default="{prefix_default}product_include[{index_default}][]"
                        data-wcuf_prefix="<?php echo esc_attr( $prefix ); ?>"
                        data-wcuf_name="<?php echo esc_attr( $name_product_include ) ?>" multiple>
					<?php
					if ( $product_include && is_array( $product_include ) && count( $product_include ) ) {
						foreach ( $product_include as $pd_id ) {
							$product = wc_get_product( $pd_id );
							if ( $product ) {
								echo sprintf( '<option value="%s" selected>%s</option>', $pd_id, $product->get_formatted_name() );
							}
						}
					}
					?>
                </select>
            </div>
            <div class="field vi-wcuf-condition-wrap vi-wcuf-pd-condition-wrap vi-wcuf-condition-product_exclude-wrap <?php echo esc_attr($type === 'product_exclude' ? '' :  'vi-wcuf-hidden' ); ?>">
                <select class="vi-wcuf-search-select2 vi-wcuf-search-product vi-wcuf-pd-condition-product_exclude vi-wcuf-condition-value"
                        data-type_select2="product"
                        name="<?php echo esc_attr( $type === 'product_exclude' ? $name_product_exclude : ''); ?>"
                        data-wcuf_name_default="{prefix_default}product_exclude[{index_default}][]"
                        data-wcuf_prefix="<?php echo esc_attr( $prefix ); ?>"
                        data-wcuf_name="<?php echo esc_attr( $name_product_exclude ) ?>" multiple>
					<?php
					if ( $product_exclude && is_array( $product_exclude ) && count( $product_exclude ) ) {
						foreach ( $product_exclude as $pd_id ) {
							$product = wc_get_product( $pd_id );
							if ( $product ) {
								echo sprintf( '<option value="%s" selected>%s</option>', $pd_id, $product->get_formatted_name() );
							}
						}
					}
					?>
                </select>
            </div>
            <div class="field vi-wcuf-condition-wrap vi-wcuf-pd-condition-wrap vi-wcuf-condition-cats_include-wrap <?php echo esc_attr( $type === 'cats_include' ? '' : 'vi-wcuf-hidden' ); ?>">
                <select class="vi-wcuf-search-select2 vi-wcuf-search-category vi-wcuf-pd-condition-cats_include vi-wcuf-condition-value"
                        data-type_select2="category"
                        name="<?php echo esc_attr( $type === 'cats_include' ? $name_cats_include  : ''); ?>"
                        data-wcuf_name_default="{prefix_default}cats_include[{index_default}][]"
                        data-wcuf_prefix="<?php echo esc_attr( $prefix ); ?>"
                        data-wcuf_name="<?php echo esc_attr( $name_cats_include ) ?>" multiple>
					<?php
					if ( $cats_include && is_array( $cats_include ) && count( $cats_include ) ) {
						foreach ( $cats_include as $cats_id ) {
							$term = get_term( $cats_id );
							if ( $term ) {
								echo sprintf( '<option value="%s" selected>%s</option>', $cats_id, $term->name );
							}
						}
					}
					?>
                </select>
            </div>
            <div class="field vi-wcuf-condition-wrap vi-wcuf-pd-condition-wrap vi-wcuf-condition-cats_exclude-wrap <?php echo esc_attr($type === 'cats_exclude' ? '' :  'vi-wcuf-hidden' ); ?>">
                <select class="vi-wcuf-search-select2 vi-wcuf-search-category vi-wcuf-pd-condition-cats_exclude vi-wcuf-condition-value"
                        data-type_select2="category"
                        name="<?php echo esc_attr( $type === 'cats_exclude' ? $name_cats_exclude  : ''); ?>"
                        data-wcuf_name_default="{prefix_default}cats_exclude[{index_default}][]"
                        data-wcuf_prefix="<?php echo esc_attr( $prefix ); ?>"
                        data-wcuf_name="<?php echo esc_attr( $name_cats_exclude ) ?>" multiple>
					<?php
					if ( $cats_exclude && is_array( $cats_exclude ) && count( $cats_exclude ) ) {
						foreach ( $cats_exclude as $cats_id ) {
							$term = get_term( $cats_id );
							if ( $term ) {
								echo sprintf( '<option value="%s" selected>%s</option>', $cats_id, $term->name );
							}
						}
					}
					?>
                </select>
            </div>
        </div>
        <div class="field vi-wcuf-revmove-condition-btn-wrap">
            <span class="vi-wcuf-revmove-condition-btn vi-wcuf-pd_rule-revmove-condition" data-tooltip="<?php esc_html_e( 'Remove', 'checkout-upsell-funnel-for-woo' ); ?>">
                 <i class="times icon"></i>
            </span>
        </div>
    </div>
</div>
