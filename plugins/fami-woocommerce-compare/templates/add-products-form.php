<?php
/**
 * The Template for add more products to comparision list
 * This template can be overridden by copying it to yourtheme/fami-wccp/add-products-form.php.
 *
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

?>

<div class="fami-wccp-add-products-wrap fami-wccp-form-wrap fami-wccp-popup">
    <div class="fami-wccp-add-products-inner fami-wccp-popup-inner">
        <form name="fami_wccp_search_product_form" class="fami-wccp-search-products-form fami-wccp-form">
            <div class="part-top">
                <h4 class="fami-wccp-title"><?php esc_html_e( 'Type keywords to search', 'fami-woocommerce-compare' ); ?></h4>
                <div class="fami-wccp-input-group">
                    <input type="text" name="fami_wccp_search_product" class="fami-wccp-add-products-input" value=""
                           placeholder="<?php esc_attr_e( 'Search products', 'fami-woocommerce-compare' ); ?>"/>
                    <button type="submit"
                            class="fami-wccp-search-products-btn"><?php esc_html_e( 'Search', 'fami-woocommerce-compare' ); ?></button>
                </div>
            </div>
            <div class="part-bottom">
                <div class="fami-wccp-search-results">

                </div>
            </div>
            <a href="#" title="<?php esc_attr_e( 'Close', 'fami-woocommerce-compare' ); ?>"
               class="fami-wccp-close-popup"><?php esc_html_e( 'Close', 'fami-woocommerce-compare' ); ?></a>
        </form>
    </div>
</div>

