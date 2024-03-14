<tr valign="top">
  <th scope="row" class="titledesc">
    <label><?php echo $value['title'] ?></label>
  </th>
  <td class="forminp forminp-<?php echo esc_attr( sanitize_title( $value['type'] ) ); ?>">
    <table class="form-table wp-list-table widefat fixed wss-credentials-table">
      <thead>
        <tr>
          <th class="min"><?php _e( '#', 'woo-stock-sync' ); ?></th>
          <th><?php _e( 'URL', 'woo-stock-sync' ); ?></th>
          <th><?php _e( 'API Key', 'woo-stock-sync' ); ?></th>
          <th><?php _e( 'API Secret', 'woo-stock-sync' ); ?></th>
          <th><?php _e( 'Check', 'woo-stock-sync' ); ?></th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ( $sites as $i => $site ) { ?>
          <tr <?php echo ( $site['hide_row'] && $i > 0 ) ? 'class="hidden"' : ''; ?>>
            <td class="min">
              <?php echo $i + 1; ?>
            </td>
            <td>
              <input
                type="text"
                class="woo-stock-sync-url"
                name="<?php echo esc_attr( $site['url']['name'] ); ?>"
                value="<?php echo esc_attr( $site['url']['value'] ); ?>"
              />
            </td>
            <td>
              <input
                type="text"
                class="woo-stock-sync-api-key"
                name="<?php echo esc_attr( $site['api_key']['name'] ); ?>"
                value="<?php echo esc_attr( $site['api_key']['value'] ); ?>"
              />
            </td>
            <td>
              <input
                type="text"
                class="woo-stock-sync-api-secret"
                name="<?php echo esc_attr( $site['api_secret']['name'] ); ?>"
                value="<?php echo esc_attr( $site['api_secret']['value'] ); ?>"
              />
            </td>
            <td>
              <a href="#" class="woo-stock-sync-check-credentials button"><?php _e( 'Check API', 'woo-stock-sync' ); ?></a>
            </td>
          </tr>
        <?php } ?>
      </tbody>
      <?php if ( wss_is_primary() ) { ?>
        <tfoot>
          <tr>
            <td colspan="5">
              <?php if ( class_exists( 'Woo_Stock_Sync_Pro' ) ) { ?>
                <a href="#" class="wss-add-site button"><?php _e( 'Add site', 'woo-stock-sync' ); ?></a>
              <?php } else { ?>
                <a href="#" class="wss-add-site button disabled"><?php _e( 'Add site', 'woo-stock-sync' ); ?></a>

                <span class="pro-sites"><?php printf( __( 'More sites available in <a href="%s" target="_blank">Pro</a>', 'woo-stock-sync' ), esc_attr( wss_get_pro_url() ) ); ?></a>
              <?php } ?>
            </td>
          </tr>
        </tfoot>
      <?php } ?>
    </table>
  </td>
</tr>
