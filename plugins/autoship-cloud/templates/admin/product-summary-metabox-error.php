<?php
/**
 * The Product Summary Metabox for Errors
 *
 * This template can be overridden by copying it to yourtheme/autoship-cloud/templates/product/product-summary-metabox-error.php
*/
?>

<div id="autoship_product_summary_container">
  <p class="autoship-sync-<?php echo $error['type']; ?> <?php echo $error['code']; ?>"><?php echo apply_filters( 'autoship_product_summary_metabox_notice', $error['msg'], 'error', $autoship_summary, $product, $error );?></p>
  <?php do_action( 'autoship_no_product_summary_metabox_table', $autoship_summary, $product ); ?>
</div>
