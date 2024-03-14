<?php global $title; ?>

<div class="wrap" id="woo-stock-sync-tools">
  <h1 class="wp-heading-inline"><?php echo $title; ?></h1>
  <hr class="wp-header-end">

  <?php include 'tabs.html.php'; ?>

  <?php if ( wss_is_primary() ) { ?>
    <div class="tools">
      <div class="tool">
        <div class="title"><?php _e( 'Push All', 'woo-stock-sync' ); ?></div>
        <div class="desc"><?php _e( 'Push stock quantities of all products to Secondary Inventories.', 'woo-stock-sync' ); ?></div>
        <div class="action">
          <a href="<?php echo $urls['push_all']; ?>" class="button button-primary"><?php _e( 'Push All', 'woo-stock-sync' ); ?></a>
        </div>
      </div>

      <div class="tool">
        <div class="title"><?php _e( 'Update Report', 'woo-stock-sync' ); ?></div>
        <div class="desc"><?php _e( 'Update report by fetching SKUs and stock quantities from Secondary Inventories.', 'woo-stock-sync' ); ?></div>
        <div class="action">
          <a href="<?php echo $urls['update']; ?>" class="button button-primary"><?php _e( 'Update', 'woo-stock-sync' ); ?></a>
        </div>
      </div>
    </div>
  <?php } else { ?>
    <p><?php printf( __( 'Tools are available in <a href="%s" target="_blank">the Primary Inventory site.</a>', 'woo-stock-sync' ), wss_primary_report_url() ); ?></p>
  <?php } ?>
</div>
