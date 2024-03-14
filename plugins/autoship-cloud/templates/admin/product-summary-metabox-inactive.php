<?php
/**
 * The Product Summary Metabox for Simple Products
 *
 * This template can be overridden by copying it to yourtheme/autoship-cloud/templates/product/product-summary-metabox-inactive.php
*/

?>

<div id="autoship_product_summary_container">
  <p><?php echo apply_filters( 'autoship_product_summary_metabox_notice', __('This product is currently <strong>Not Active</strong> and therefore changes made in WooCommerce will not be synchronized with Autoship Cloud. To Activate this product check the <i>Activate Product Sync</i> checkbox on the Autoship tab.', 'autoship'), 'not-active', $autoship_summary, $product );?></p>
  <?php do_action( 'autoship_no_product_summary_metabox_table', $autoship_summary, $product ); ?>
</div>
