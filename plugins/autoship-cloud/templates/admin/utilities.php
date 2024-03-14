<?php

do_action( 'autoship_before_autoship_admin_utilities' );

$upgrade = isset( $_GET['autoship_activate_sync_upgrade'] );
$processing_version = autoship_get_saved_site_processing_version();

if ( !$upgrade && isset( $_GET['autoship_activate_legacy_processing_upgrade'] ) && $processing_version != 'v3' ){

  ?>

  <div class="autoship-bulk-action upgrade-action" id="autoship-activate-legacy-processing-upgrade">

      <h2><?php echo __( 'Update Required: Upgrade Existing Scheduled Orders to New Processing Date/Time Format', 'autoship' ); ?></h2>
      <p><?php echo __( 'Your new version of <i>Autoship Cloud powered by QPilot</i> requires an upgrade to your existing Scheduled Orders in order to continue order processing successfully.', 'autoship' ); ?></p>
      <p><?php echo __( 'Using the “Upgrade Processing Version” will update the Date and Time format of your existing Scheduled Orders. This upgrade cannot be reversed and will change the processing engine that your connected QPilot Site uses moving forward.', 'autoship' ); ?></p>
      <p><?php echo __( '<strong>IMPORTANT</strong>: If you have made any customizations to how Next Occurrence Dates are generated for Scheduled Orders -  either through custom code, a custom extension plugin, or similar - please test this upgrade in a staging site environment <strong>before upgrading your production site.</strong>', 'autoship' ); ?></p>

      <hr/>

      <p class="form-field inline-field">

        <input type="checkbox"
        id="autoship_upgrade_site_processing_version"
        name="autoship_upgrade_site_processing_version"
        value="confirm"
        required
        autocomplete="false" />
        <label for="autoship_upgrade_site_processing_version" style="display:inline;"><?php echo __( 'I have read the upgrade notice and understand that running the action to “Upgrade Processing Version” will change how Scheduled Orders are processed by QPilot.', 'autoship' ); ?></label>

      </p>

      <p><button type="submit" class="button-primary" name="upgrade_processing" value="upgrade_processing"><span><?php echo __('Upgrade Processing Version', 'autoship'); ?></span></button></p>

      <?php wp_nonce_field( 'autoship-activate-legacy-processing-upgrade', 'autoship-activate-legacy-processing-upgrade-nonce' ); ?>
      <input type="hidden" name="autoship-action" value="autoship_upgrade_site_processing_version">

    </div>

  <?php

  return;

}

$query_ids      = array();
$query_ids['simple']    = autoship_batch_query_product_ids ( 'simple' );
$query_ids['variable']  = autoship_batch_query_product_ids ( 'variable' );
$query_ids['variation'] = autoship_batch_query_product_ids ( 'variation' );

$query_ids['simple_variation']          = array_merge( $query_ids['simple'], $query_ids['variation'] );
$query_ids['simple_variable']           = array_merge( $query_ids['simple'], $query_ids['variable'] );
$query_ids['simple_variable_variation'] = array_merge( $query_ids['simple'], $query_ids['variable'], $query_ids['variation'] );

// Depending on Global get active or all are active
$query_active_ids = array(
  'simple'          => array(),
  'variation'       => array(),
  'simple_variation'=> array()
);

if ( !autoship_global_sync_active_enabled() ) {

  $query_active_ids['simple']    = autoship_batch_query_active_product_ids ( 'simple' );
  $query_active_ids['variable']  = autoship_batch_query_active_product_ids ( 'variable' );
  $query_active_ids['variation'] = autoship_batch_query_active_product_ids ('variation' );
  $query_active_ids['simple_variation']         = array_merge( $query_active_ids['simple'], $query_active_ids['variation'] );
  $query_active_ids['simple_variable']          = array_merge( $query_active_ids['simple'], $query_active_ids['variable'] );
  $query_active_ids['simple_variable_variation']= array_merge( $query_active_ids['simple'], $query_active_ids['variable'], $query_active_ids['variation'] );
}

$notice = array();
$notice['simple_variation'] = count( $query_ids['simple_variation'] ) ? __('A total of a %d Products and Product Variations can be processed.', 'autoship' ) : __('There are no available products to process.', 'autoship' );

$notice['simple_variable'] = count( $query_ids['simple_variable_variation'] ) ? __('A total of a %d Simple and Variable Products can be processed.', 'autoship' ) : __('There are no available products to process.', 'autoship' );

$notice['simple_variable_variation'] = count( $query_ids['simple_variable_variation'] ) ? __('A total of a %d Simple Products, Variable Products, and Product Variations can be processed.', 'autoship' ) : __('There are no available products to process.', 'autoship' );

$active_notice = $notice;
if ( !autoship_global_sync_active_enabled() ) {
  $active_notice['simple_variation'] = count( $query_active_ids['simple_variation'] ) ? __('A total of a %d Products and Product Variations can be processed.', 'autoship' ) : __('There are no available products to process.', 'autoship' );
}

// If the global option is still set this is an upgrade so we need to show the reset option not individual option
if ( autoship_global_sync_active_enabled() ){

  $maybe_query_ids      = array();
  $maybe_query_ids['simple']    = autoship_batch_query_maybe_active_product_ids ( 'simple' );
  $maybe_query_ids['variable']  = autoship_batch_query_maybe_active_product_ids ( 'variable' );
  $maybe_query_ids['variation'] = autoship_batch_query_maybe_active_product_ids ( 'variation' );
  $maybe_query_ids['simple_variable_variation'] = array_merge( $maybe_query_ids['simple'], $maybe_query_ids['variable'], $maybe_query_ids['variation'] );

?>

  <div class="autoship-bulk-action upgrade-action" id="autoship-bulk-reset-activate-product-sync">

    <h2><?php echo __( 'Update WooCommerce Product Synchronization', 'autoship' ); ?></h2>
    <p><?php echo __( '<i>Autoship Cloud powered by QPilot</i> now enables merchants to select which WooCommerce Products should be synchronized with your connected QPilot Site.<br/>Currently, all WooCommerce Products are synchronized with your QPilot Site: even those that have no settings enabled for Autoship.<br/>Running this update will ensure that only the WooCommerce Products that are setup for Autoship continue to synchronize with your QPilot Site.', 'autoship' ); ?></p>
    <p><?php echo __( '<strong>IMPORTANT: Please review before selecting your update action</strong>', 'autoship' ); ?></p>
    <hr/>
    <div style="padding: 10px;"><p><?php echo __( '<strong>OPTION 1: Activate All Products Currently Enabled for Autoship:</strong><br/>Use the “Activate All Products” option to Activate all WooCommerce Products that currently have Autoship options enabled. These options include any products or variations that are enabled to: Display Schedule Options and/or Available to Add to Scheduled Orders or Process With Scheduled Orders.', 'autoship' ); ?></p></div>
    <div style="padding: 10px;"><p><?php echo __( '<strong>OPTION 2: Deactivate All Products:</strong><br/>Select the “Deactivate all Products” option to deactivate all WooCommerce Products. You should then <a href="https://support.autoship.cloud/article/448-7-enabling-products-for-autoship">select which products are Activated for Product Sync with QPilot</a>.', 'autoship' ); ?></p>
    <p><?php echo sprintf( __( 'Once all products are deactivated, you can quickly select specific products to Activate by visiting the Edit Product screen in <a href="%s">WP-Admin > Products</a> for each product you want to synchronize and enabling the “Activate Product Sync” checkbox under the Autoship tab.', 'autoship' ), autoship_admin_products_page_url() ); ?></p></div>
    <p><?php echo __( 'The Batch Size for this update (default is "10") determines the number of products to update in a single round. Decreasing the Batch Size helps prevent timeout issues for sites with many WooCommerce Products.', 'autoship' ); ?></p>

    <hr/>

    <h4 class="autoship-bulk-notice"><?php printf( $notice['simple_variable_variation'] , count( $maybe_query_ids['simple_variable_variation'] ) ); ?></h4>
    <h5 class="autoship-bulk-subnotice"></h5>

    <p class="form-field inline-field ">
  		<label for="batch_size">Batch Size:</label>
      <input type="number" class="small-text" name="batch_size" value="10" placeholder="10" step="1" min="0"/>
    </p>

    <p class="form-field inline-field">
      <label class="batch-total-toggle" for="no-batch-enable-reset-sync" data-adjust-batch-total="<?php echo count( $maybe_query_ids['simple_variable_variation'] );?>" data-adjust-batch-notice="<?php printf( $notice['simple_variable_variation'] , count( $maybe_query_ids['simple_variable_variation'] ) ); ?>"><input id="no-batch-enable-reset-sync" type="radio" name="enable_reset_active_sync_option" value="no" checked="checked"/>
      <?php echo __( 'Activate All Products', 'autoship' ); ?></label>
    </p>

    <p class="form-field inline-field">
      <label class="batch-total-toggle" for="yes-batch-enable-reset-sync" data-adjust-batch-total="<?php echo count( $query_ids['simple_variable_variation'] );?>" data-adjust-batch-notice="<?php printf( $notice['simple_variable_variation'] , count( $query_ids['simple_variable_variation'] ) ); ?>"><input id="yes-batch-enable-reset-sync" type="radio" name="enable_reset_active_sync_option" value="yes"/>
      <?php echo __( 'Deactivate All Products', 'autoship' ); ?></label>
    </p>

    <p class="form-field">

      <input type="checkbox"
      id="autoship_include_availability_on_sync"
      name="autoship_include_availability_on_sync"
      value="yes"
      autocomplete="false" checked />
      <label for="autoship_include_availability_on_sync"><?php echo __( 'Enable the Add to Scheduled Order and Process on Scheduled Orders options for all products synchronized with your QPilot Site.', 'autoship' ); ?></label>

    </p>


    <div style="display:none;" class="autoship-meter">
      <span style="width:10%"></span>
    </div>

    <p><button class="button-primary autoship-action autoship-ajax-button"><span><?php echo __('Update Products', 'autoship'); ?></span></button></p>

    <p><button class="button-secondary autoship-cancel-action autoship-ajax-cancel-button"><span><?php echo __('Cancel Update', 'autoship'); ?></span></button></p>

    <input type="hidden" class="total-toggle-counters" name="total_count" value="<?php echo count( $maybe_query_ids['simple_variable_variation'] ); ?>">
    <input type="hidden" name="current_count" value="0">
    <input type="hidden" name="current_page" value="1">
    <input type="hidden" name="autoship-action" value="autoship_batch_update_products">
    <input type="hidden" name="batch_action" value="autoship_bulk_update_reset_active_sync">

  </div>

<?php } else { ?>

  <div class="autoship-bulk-action" id="autoship-bulk-activate-product-sync">

    <h2><?php echo __( 'Update Product Synchronization', 'autoship' ); ?></h2>
    <p><?php echo __( 'Autoship Cloud powered by QPilot enables merchants to select which WooCommerce Products should be synchronized with your connected QPilot Site.', 'autoship' ); ?></p>
    <ul>
    <li style="padding-left: 10px;"><p><?php echo __( '- Use the <strong>“Activate All Products”</strong> option to Activate all WooCommerce Products to Sync with QPilot.', 'autoship' ); ?></p></li>
    <li style="padding-left: 10px;"><p><?php echo __( '- Use the <strong>“Deactivate All Products”</strong> option to Deactivate all WooCommerce Products from Syncing with QPilot.', 'autoship' ); ?></p></li>
    </ul>
    <p><?php echo __( 'The Batch Size for this update (default is "10") determines the number of products to update in a single round. Decreasing the Batch Size helps prevent timeout issues for sites with many WooCommerce Products.', 'autoship' ); ?></p>
    <hr/>

    <h4 class="autoship-bulk-notice"><?php printf( $notice['simple_variable_variation'] , count( $query_ids['simple_variable_variation'] ) ); ?></h4>
    <h5 class="autoship-bulk-subnotice"></h5>

    <p class="form-field inline-field ">
  		<label for="batch_size">Batch Size:</label>
      <input type="number" class="small-text" name="batch_size" value="10" placeholder="10" step="1" min="0"/>
    </p>

    <p class="form-field inline-field">
      <label class="batch-total-toggle autoship_trigger" for="yes-batch-enable-sync" data-show-target="#autoship-include-availability-sync" data-adjust-batch-total="<?php echo count( $query_ids['simple_variable_variation'] );?>" data-adjust-batch-notice="<?php printf( $notice['simple_variable_variation'] , count( $query_ids['simple_variable_variation'] ) ); ?>"><input id="yes-batch-enable-sync" type="radio" name="enable_active_sync_option" value="yes" checked />
      <?php echo __( 'Activate All Products', 'autoship' ); ?></label>
    </p>

    <p class="form-field inline-field">
      <label class="batch-total-toggle autoship_trigger" for="no-batch-enable-sync" data-hide-target="#autoship-include-availability-sync" data-adjust-batch-total="<?php echo count( $query_ids['simple_variable_variation'] );?>" data-adjust-batch-notice="<?php printf( $notice['simple_variable_variation'] , count( $query_ids['simple_variable_variation'] ) ); ?>"><input id="no-batch-enable-sync" type="radio" name="enable_active_sync_option" value="no"/>
      <?php echo __( 'Deactivate All Products', 'autoship' ); ?></label>
    </p>

    <p class="form-field" id="autoship-include-availability-sync">

      <label for="autoship_include_availability_on_sync"
      ><input type="checkbox"
      id="autoship_include_availability_on_sync"
      name="autoship_include_availability_on_sync"
      value="yes"
      autocomplete="false" checked />
      <?php echo __( 'Enable the Add to Scheduled Order and Process on Scheduled Orders options for all products synchronized with your QPilot Site.', 'autoship' ); ?></label>

    </p>

    <div style="display:none;" class="autoship-meter">
      <span style="width:10%"></span>
    </div>

    <p><button class="button-primary autoship-action autoship-ajax-button"><span><?php echo __('Update Products', 'autoship'); ?></span></button></p>

    <p><button class="button-secondary autoship-cancel-action autoship-ajax-cancel-button"><span><?php echo __('Cancel Update', 'autoship'); ?></span></button></p>

    <input type="hidden" class="total-toggle-counters" name="total_count" value="<?php echo count( $query_ids['simple_variable_variation'] ); ?>">
    <input type="hidden" name="current_count" value="0">
    <input type="hidden" name="current_page" value="1">
    <input type="hidden" name="autoship-action" value="autoship_batch_update_products">
    <input type="hidden" name="batch_action" value="autoship_bulk_update_active_sync">

  </div>

<?php } ?>

<?php if ( !$upgrade ) { ?>

  <hr/>

  <div class="autoship-bulk-action" id="autoship-bulk-checkout-discount">

    <h2><?php echo __( 'Bulk Update Autoship Checkout Price', 'autoship' ); ?></h2>
    <p><?php echo __( 'Updates the Autoship Checkout Price for all WooCommerce Simple Products and Product Variations in your store.', 'autoship' ); ?></p>
    <p><?php echo __( 'To update, enter a Percentage as a decimal value (for example: enter "0.10" for 10%) to discount the Regular Price of the WooCommerce Product or Variation to be used as the Autoship Checkout Price.  The Batch Size (default is "10") determines the number of products to update in a single round. Decreasing the Batch Size helps prevent timeout issues for sites with many WooCommerce Products.', 'autoship' ); ?></p>

    <hr/>

    <?php $ids = autoship_global_sync_active_enabled() ? $query_ids['simple_variation'] : $query_active_ids['simple_variation']; ?>

    <h4 class="autoship-bulk-notice"><?php printf( $notice['simple_variation'] , count( $ids ) ); ?></h4>
    <h5 class="autoship-bulk-subnotice"></h5>

    <p class="help"><?php echo __('Select which price to use when calculating the checkout price.');?></p>

    <p class="form-field inline-field">
      <label for="checkout_regular_base_price"><input id="checkout_regular_base_price" type="radio" name="base_price" value="regular" checked="checked"/>
      <?php echo __( 'Regular Price', 'autoship' ); ?></label>
    </p>

    <p class="form-field inline-field">
      <label for="checkout_sale_base_price"><input id="checkout_sale_base_price" type="radio" name="base_price" value="sale"/>
      <?php echo __( 'Sale Price', 'autoship' ); ?></label>
    </p>

    <div style="padding:20px 0px;">

      <p class="form-field inline-field">
    		<label for="batch_size">Batch Size:</label>
        <input type="number" class="small-text" name="batch_size" value="10" placeholder="10" step="1" min="0"/>
      </p>

      <p class="form-field inline-field">
    		<label for="checkout_pct">Percent Discount:</label>
        <input type="number" class="small-text" name="checkout_pct" value=".1" placeholder=".10" step=".01" min="0"/>
      </p>

    </div>

    <div style="display:none;" class="autoship-meter">
      <span style="width:10%"></span>
    </div>

    <p><button class="button-primary autoship-action autoship-ajax-button"><span><?php echo __('Update Products', 'autoship'); ?></span></button></p>

    <p><button class="button-secondary autoship-cancel-action autoship-ajax-cancel-button"><span><?php echo __('Cancel Update', 'autoship'); ?></span></button></p>

    <input type="hidden" class="total-toggle-counters" name="total_count" value="<?php echo count( $ids ); ?>">
    <input type="hidden" name="current_count" value="0">
    <input type="hidden" name="current_page" value="1">
    <input type="hidden" name="autoship-action" value="autoship_batch_update_products">
    <input type="hidden" name="batch_action" value="autoship_bulk_update_checkout_price">

  </div>

  <hr/>

  <div class="autoship-bulk-action" id="autoship-bulk-recurring-discount">

    <h2><?php echo __( 'Bulk Update Autoship Recurring Price', 'autoship' ); ?></h2>
    <p><?php echo __( 'Updates the Autoship Recurring Price for all WooCommerce Simple Products and Product Variations in your store.', 'autoship' ); ?></p>
    <p><?php echo __( 'To update, enter a Percentage as a decimal value (for example: enter "0.10" for 10%) to discount the Regular Price of the WooCommerce Product or Variation to be used as the Autoship Recurring Price.  The Batch Size (default is "10") determines the number of products to update in a single round. Decreasing the Batch Size helps prevent timeout issues for sites with many WooCommerce Products.', 'autoship' ); ?></p>

    <hr/>

    <?php $ids = autoship_global_sync_active_enabled() ? $query_ids['simple_variation'] : $query_active_ids['simple_variation']; ?>

    <h4 class="autoship-bulk-notice"><?php printf( $notice['simple_variation'] , count( $ids ) ); ?></h4>
    <h5 class="autoship-bulk-subnotice"></h5>

    <p class="help"><?php echo __('Select which price to use when calculating the recurring price.');?></p>

    <p class="form-field inline-field">
      <label for="recurring_regular_base_price"><input id="recurring_regular_base_price" type="radio" name="base_recurring_price" value="regular" checked="checked"/>
      <?php echo __( 'Regular Price', 'autoship' ); ?></label>
    </p>

    <p class="form-field inline-field">
      <label for="recurring_sale_base_price"><input id="recurring_sale_base_price" type="radio" name="base_recurring_price" value="sale"/>
      <?php echo __( 'Sale Price', 'autoship' ); ?></label>
    </p>

    <div style="padding:20px 0px;">

      <p class="form-field inline-field">
    		<label for="batch_size">Batch Size:</label>
        <input type="number" class="small-text" name="batch_size" value="10" placeholder="10" step="1" min="0"/>
      </p>

      <p class="form-field inline-field">
    		<label for="recurring_pct">Percent Discount:</label>
        <input type="number" class="small-text" name="recurring_pct" value=".1" placeholder=".10" step=".01" min="0"/>
      </p>

    </div>

    <div style="display:none;" class="autoship-meter">
      <span style="width:10%"></span>
    </div>

    <p><button class="button-primary autoship-action autoship-ajax-button"><span><?php echo __('Update Products', 'autoship'); ?></span></button></p>

    <p><button class="button-secondary autoship-cancel-action autoship-ajax-cancel-button"><span><?php echo __('Cancel Update', 'autoship'); ?></span></button></p>

    <input type="hidden" class="total-toggle-counters" name="total_count" value="<?php echo count( $ids ); ?>">
    <input type="hidden" name="current_count" value="0">
    <input type="hidden" name="current_page" value="1">
    <input type="hidden" name="autoship-action" value="autoship_batch_update_products">
    <input type="hidden" name="batch_action" value="autoship_bulk_update_recurring_price">

  </div>

  <hr/>

  <div class="autoship-bulk-action" id="autoship-bulk-enable-autoship">

    <h2><?php echo __( 'Bulk Enable WooCommerce Products to Display Autoship Options', 'autoship' ); ?></h2>
    <p><?php echo __( 'Enables or disables the display of Autoship options on WooCommerce Product Pages for all Simple Products, Variable Products and Product Variations. The <em>Enable Autoship</em> option updates WooCommerce Simple Products, Variable Products and Product Variations while the <em>Disable Autoship</em> option removes the display from WooCommerce Simple Products and Variable Products.  The Batch Size (default is "10") determines the number of products to update in a single round. Decreasing the Batch Size helps prevent timeout issues for sites with many WooCommerce Products.', 'autoship' ); ?></p>
    <p><?php echo __( '<b>Important:</b> You will still need to enable product availability in Autoship Cloud.', 'autoship' ); ?> <a href="https://support.autoship.cloud/article/441-product-availability-and-stock-status" target="_blank"><?php echo __( 'Learn more', 'autoship' ); ?></a>.</p>

    <hr/>

    <?php

    $ids = autoship_global_sync_active_enabled() ? $query_ids['simple_variable_variation'] : $query_active_ids['simple_variable_variation'];
    $text = autoship_global_sync_active_enabled() ? $notice['simple_variable_variation'] : $active_notice['simple_variable_variation'];

    ?>

    <h4 class="autoship-bulk-notice"><?php printf( $text , count( $ids ) ); ?></h4>
    <h5 class="autoship-bulk-subnotice"></h5>

    <p class="form-field inline-field ">
  		<label for="batch_size">Batch Size:</label>
      <input type="number" class="small-text" name="batch_size" value="10" placeholder="10" step="1" min="0"/>
    </p>

    <p class="form-field inline-field">
      <label class="batch-total-toggle" for="yes-batch-enable" data-adjust-batch-total="<?php echo count( $query_ids['simple_variable_variation'] );?>" data-adjust-batch-notice="<?php printf( $notice['simple_variable_variation'] , count( $query_ids['simple_variable_variation'] ) ); ?>"><input id="yes-batch-enable" type="radio" name="enable_autoship_option" value="yes" checked="checked"/>
      <?php echo __( 'Enable Autoship', 'autoship' ); ?></label>
    </p>

    <p class="form-field inline-field">
      <label class="batch-total-toggle" for="no-batch-enable" data-adjust-batch-total="<?php echo count( $query_ids['simple_variable'] );?>" data-adjust-batch-notice="<?php printf( $notice['simple_variable'] , count( $query_ids['simple_variable'] ) ); ?>"><input id="no-batch-enable" type="radio" name="enable_autoship_option" value="no"/>
      <?php echo __( 'Disable Autoship', 'autoship' ); ?></label>
    </p>

    <div style="display:none;" class="autoship-meter">
      <span style="width:10%"></span>
    </div>

    <p><button class="button-primary autoship-action autoship-ajax-button"><span><?php echo __('Update Products', 'autoship'); ?></span></button></p>

    <p><button class="button-secondary autoship-cancel-action autoship-ajax-cancel-button"><span><?php echo __('Cancel Update', 'autoship'); ?></span></button></p>

    <input type="hidden" class="total-toggle-counters" name="total_count" value="<?php echo count( $query_ids['simple_variable'] ); ?>">
    <input type="hidden" name="current_count" value="0">
    <input type="hidden" name="current_page" value="1">
    <input type="hidden" name="autoship-action" value="autoship_batch_update_products">
    <input type="hidden" name="batch_action" value="autoship_bulk_update_enable_autoship">

  </div>

  <hr/>

  <div class="autoship-bulk-action" id="autoship-bulk-enable-availability">

    <h2><?php echo __( 'Bulk Enable Product Availability', 'autoship' ); ?></h2>
    <p><?php echo __( 'Enables or disables the Add to Scheduled Order and Process on Scheduled Orders for all Simple Products and Product Variations. The Batch Size (default is "10") determines the number of products to update in a single round. Decreasing the Batch Size helps prevent timeout issues for sites with many WooCommerce Products.', 'autoship' ); ?></p>
    <p><?php echo __( '<b>Important:</b> For additional information on the Add to Scheduled Order and Process on Scheduled Orders options', 'autoship' ); ?> <a href="https://support.autoship.cloud/article/441-product-availability-and-stock-status" target="_blank"><?php echo __( 'Click Here', 'autoship' ); ?></a>.</p>

    <hr/>

    <?php
    $ids = autoship_global_sync_active_enabled() ? $query_ids['simple_variation'] : $query_active_ids['simple_variation'];
    $text = autoship_global_sync_active_enabled() ? $notice['simple_variation'] : $active_notice['simple_variation'];
    ?>

    <h4 class="autoship-bulk-notice"><?php printf( $text , count( $ids ) ); ?></h4>
    <h5 class="autoship-bulk-subnotice"></h5>

    <p class="form-field inline-field ">
  		<label for="batch_size">Batch Size:</label>
      <input type="number" class="small-text" name="batch_size" value="10" placeholder="10" step="1" min="0"/>
    </p>

    <p class="form-field inline-field">
      <label class="batch-total-toggle" for="yes-batch-enable-avail" data-adjust-batch-total="<?php echo count( $ids );?>" data-adjust-batch-notice="<?php printf( $text , count( $ids ) ); ?>"><input id="yes-batch-enable-avail" type="radio" name="enable_availability_option" value="yes" checked="checked"/>
      <?php echo __( 'Enable Availability', 'autoship' ); ?></label>
    </p>

    <p class="form-field inline-field">
      <label class="batch-total-toggle" for="no-batch-enable-avail" data-adjust-batch-total="<?php echo count( $ids );?>" data-adjust-batch-notice="<?php printf( $text , count( $ids ) ); ?>"><input id="no-batch-enable-avail" type="radio" name="enable_availability_option" value="no"/>
      <?php echo __( 'Disable Availability', 'autoship' ); ?></label>
    </p>

    <div style="display:none;" class="autoship-meter">
      <span style="width:10%"></span>
    </div>

    <p><button class="button-primary autoship-action autoship-ajax-button"><span><?php echo __('Update Products', 'autoship'); ?></span></button></p>

    <p><button class="button-secondary autoship-cancel-action autoship-ajax-cancel-button"><span><?php echo __('Cancel Update', 'autoship'); ?></span></button></p>

    <input type="hidden" class="total-toggle-counters" name="total_count" value="<?php echo count( $ids ); ?>">
    <input type="hidden" name="current_count" value="0">
    <input type="hidden" name="current_page" value="1">
    <input type="hidden" name="autoship-action" value="autoship_batch_update_products">
    <input type="hidden" name="batch_action" value="autoship_bulk_update_enable_availability">

  </div>

  <hr/>

  <div class="autoship-bulk-action" id="autoship-bulk-update-customer-metrics">

    <h2><?php echo __( 'Bulk Update Customer Metrics', 'autoship' ); ?></h2>
    <p><?php echo __( 'Imports Customer Metrics Data from the QPilot Cloud into the WordPress database for your subscription customers. The Batch Size (default is "10") determines the number of customers to update in a single round. Decreasing the Batch Size helps prevent timeout issues for sites with many WooCommerce Customers.', 'autoship' ); ?></p>

    <hr/>

    <?php

    // Retrieve the total customer in API count
    $total_customers = autoship_search_available_customers( array(
      'pageSize' => 1
    ), 1, false );

    $total= !is_wp_error( $total_customers ) ? $total_customers->totalCount : 0;
    $text = $total ? __('A total of a %d Customers can be processed.', 'autoship' ) : __('There are no available customers to process.', 'autoship' );

    ?>

    <h4 class="autoship-bulk-notice"><?php printf( $text , $total ); ?></h4>
    <h5 class="autoship-bulk-subnotice"></h5>

    <p class="form-field inline-field ">
  		<label for="batch_size">Batch Size:</label>
      <input type="number" class="small-text" name="batch_size" value="10" placeholder="10" step="1" min="0"/>
    </p>

    <div style="display:none;" class="autoship-meter">
      <span style="width:10%"></span>
    </div>

    <p><button class="button-primary autoship-action autoship-ajax-button"><span><?php echo __('Update Customers', 'autoship'); ?></span></button></p>

    <p><button class="button-secondary autoship-cancel-action autoship-ajax-cancel-button"><span><?php echo __('Cancel Update', 'autoship'); ?></span></button></p>

    <input type="hidden" class="total-toggle-counters" name="total_count" value="<?php echo $total; ?>">
    <input type="hidden" name="current_count" value="0">
    <input type="hidden" name="current_page" value="1">
    <input type="hidden" name="autoship-action" value="autoship_batch_update_products">
    <input type="hidden" name="batch_action" value="autoship_bulk_update_customer_metrics">

  </div>

  <?php do_action( 'autoship_after_autoship_admin_utilities', $query_ids, $notice ); ?>

<?php } ?>
