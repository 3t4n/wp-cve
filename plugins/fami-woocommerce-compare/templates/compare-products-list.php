<?php
/**
 * This template can be overridden by copying it to yourtheme/fami-wccp/compare-products-list.php.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

?>

<div class="fami-wccp-products-list container">
    <div class="fami-wccp-products-list-content">
        <div class="part-left">
            <h4 class="fami-wccp-title"><?php esc_html_e( 'Compare Products', 'fami-woocommerce-compare' ); ?></h4>
            <a href="#" class="fami-wccp-close"><?php esc_html_e( 'Close', 'fami-woocommerce-compare' ); ?></a>
        </div>
        <div class="part-right">
            {{products_list}}
            <div class="actions-wrap">
                <a href="#" data-product_id="all"
                   class="clear-all-compare-btn"><?php esc_attr_e( 'Clear', 'fami-woocommerce-compare' ); ?></a>
                {{go_to_compare_page}}
            </div>
        </div>
    </div>
</div>
