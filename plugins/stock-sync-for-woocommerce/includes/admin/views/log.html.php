<?php global $title; ?>

<div class="wrap" id="woo-stock-sync-report">
  <h1 class="wp-heading-inline"><?php echo esc_html( $title ); ?></h1>
  <hr class="wp-header-end">

  <?php include 'tabs.html.php'; ?>

  <?php if ( wss_is_secondary() ) { ?>
    <div class="wss-alert">
      <?php printf( __( 'This is a log for Secondary Inventory and only contains failed communication with Primary Inventory. For complete log please view <a href="%s" target="_blank">Primary Inventory Log</a>.', 'woo-stock-sync' ), wss_primary_report_url( 'log' ) ); ?>
    </div>
  <?php } ?>

  <form method="get">
    <div class="tablenav">
      <div class="alignleft actions">
        <input type="hidden" name="page" value="woo-stock-sync-report" />
        <input type="hidden" name="action" value="log" />

        <select class="wc-product-search" name="product_id" data-action="woocommerce_json_search_products_and_variations" data-placeholder="<?php esc_attr_e( 'Filter by product', 'woo-stock-sync' ); ?>" style="min-width:200px;">
          <?php if ( $filter_by_product_id ) { ?>
            <option value="<?php echo esc_attr( $filter_by_product_id ); ?>"><?php echo esc_html( $filter_by_product->get_formatted_name() ); ?></option>
          <?php } ?>
        </select>

        <select name="log_type">
          <?php foreach ( $log_types as $key => $label ) { ?>
            <option value="<?php echo esc_attr( $key ); ?>" <?php selected( $log_type, $key ); ?>><?php echo esc_html( $label ); ?></option>
          <?php } ?>
        </select>

        <input type="submit" name="filter_action" id="post-query-submit" class="button" value="<?php esc_attr_e( 'Filter', 'woo-stock-sync' ); ?>">
      </div>
    
      <?php echo $pagination; ?>
    </div>

    <table class="wp-list-table widefat fixed striped table-view-list wss-log-table">
      <thead>
        <tr>
          <th><?php esc_html_e( 'Message', 'woo-stock-sync' ); ?></th>
          <th><?php esc_html_e( 'Date', 'woo-stock-sync' ); ?></th>
          <th><?php esc_html_e( 'Source', 'woo-stock-sync' ); ?></th>
          <th><?php esc_html_e( 'Synced', 'woo-stock-sync' ); ?></th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ( $logs as $log ) { ?>
          <tr class="type-<?php echo esc_attr( $log->type ); ?>">
            <td>
              <?php echo wp_kses_post( $log->message ); ?>
            </td>
            <td>
              <?php echo esc_html( wss_format_datetime( strtotime( $log->created_at ) ) ); ?>
            </td>
            <td>
              <?php if ( isset( $log->data->source ) ) { ?>
                <?php if ( isset( $log->data->source_url ) ) { ?>
                  <a href="<?php echo esc_attr( $log->data->source_url ); ?>" target="_blank">
                <?php } ?>
                  <?php echo esc_html( wss_format_site_url( $log->data->source ) ); ?>

                  <?php if ( isset( $log->data->source_desc ) ) { ?>
                    <?php echo esc_html( $log->data->source_desc ); ?>
                  <?php } ?>

                <?php if ( isset( $log->data->source_url ) ) { ?>
                  </a>
                <?php } ?>
              <?php } ?>
            </td>
            <td>
              <?php foreach ( $sites as $i => $site ) { ?>
                <?php $entry = wss_transform_results( $log, $site ); ?>
                <span
                  class="wss-sync-result wss-tip sync-result-<?php echo esc_attr( $entry->level ); ?>"
                  data-tip="<?php echo esc_attr( $entry->msg ); ?>"
                >
                  <?php echo esc_attr( $entry->site ); ?>
                </span>
              <?php } ?>
            </td>
          </tr>
        <?php } ?>
        <?php if ( empty( $logs ) ) { ?>
          <?php if ( ! $log_table_exists ) { ?>
            <tr>
              <td colspan="4"><?php esc_html_e( 'Database table for logs doesn\'t exist! Deactivate and reactivate the plugin to create the table.', 'woo-stock-sync' ); ?></td>
            </tr>
          <?php } else { ?>
            <tr>
              <td colspan="4"><?php esc_html_e( 'No log messages.', 'woo-stock-sync' ); ?></td>
            </tr>
          <?php } ?>
        <?php } ?>
      </tbody>
    </table>
    <div class="wss-descs">
      <?php foreach ( $sites as $i => $site ) { ?>
        <span class="desc"><span class="wss-sync-result sync-result-success"><?php echo esc_html( $site['letter'] ); ?></span>&nbsp;<?php echo esc_html( $site['formatted_url'] ); ?></span>
      <?php } ?>
    </div>

    <div class="wss-descs">
      <span class="desc"><span class="wss-sync-result sync-result-success"></span> OK</span>
      <span class="desc"><span class="wss-sync-result sync-result-warning"></span> Warning</span>
      <span class="desc"><span class="wss-sync-result sync-result-error"></span> Error</span>
    </div>
  </form>
</div>