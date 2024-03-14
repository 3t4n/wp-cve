<?php
/**
 * The Product Summary Metabox for Products
 *
 * This template can be overridden by copying it to yourtheme/autoship-cloud/templates/product/product-summary-metabox.php
*/
?>

<div id="autoship_product_summary_container">

  <?php if ( !empty( $error ) ): ?>

  <p class="autoship-sync-<?php echo $error['type']; ?> <?php echo $error['code']; ?>"><?php echo $error['msg']; ?></p>

  <?php endif; ?>

  <p><?php echo apply_filters( 'autoship_product_summary_metabox_notice', __('This product is currently <strong>Active</strong> and any changes made in WooCommerce are synchronized with Autoship Cloud.', 'autoship'), 'active', $autoship_summary, $product );?></p>
  <?php do_action( 'autoship_before_product_summary_metabox_table', $autoship_summary, $product ); ?>

  <?php $table_data = apply_filters( 'autoship_product_summary_metabox_table_data', array(), $autoship_summary, $product ); ?>

  <?php if ( !empty( $table_data ) ):?>

  <table>
    <caption><?php echo __('Autoship Cloud Product Data', 'autoship');?></caption>

    <?php foreach ($table_data as $row): ?>

    <tr>
      <td><?php echo $row['label'];?></td>
      <td><?php echo $row['value'];?></td>
    </tr>

    <?php endforeach; ?>

  </table>

  <?php endif;?>

  <?php do_action( 'autoship_after_product_summary_metabox_table', $autoship_summary, $product ); ?>
</div>
