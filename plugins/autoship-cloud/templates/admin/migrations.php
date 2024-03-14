<?php

do_action( 'autoship_before_autoship_admin_migrations' );

?>

<h2><?php echo __('Migrations', 'autoship' ); ?></h2>
<p><strong><?php echo __( 'Upgrading from WC Autoship to Autoship Cloud?', 'autoship' ); ?></strong></p>
<p><?php echo __( 'Please see our <a href="https://support.autoship.cloud/article/374-upgrading-from-wc-autoship-to-autoship-cloud" target="_blank">documentation</a> to help guide your migration.
For technical support with migrations, please visit our <a href="https://support.autoship.cloud" target="_blank">support site</a> to contact us directly.', 'autoship' ); ?></p>

<hr/>

<?php


// Check for Legacy WC Autoship Orders.
$wc_autoship_ids = autoship_has_legacy_origin() ? autoship_legacy_export_query_ids(): array();

if ( !empty( $wc_autoship_ids ) ){

?>

<div class="autoship-bulk-action" id="autoship-bulk-export-csv">

  <h2><?php echo __('Export WC Autoship Schedules to CSV', 'autoship'); ?></h2>
  <p><?php echo __('Export a downloadable CSV of all WC Autoship Schedules.', 'autoship'); ?></p>
  <p><?php echo __('Your site admin will receive email with a link to download the CSV when then export is complete.', 'autoship'); ?></p>

  <hr/>

  <h4 class="autoship-bulk-notice"><?php printf( __('A total of a %d WC Autoship Schedules can be exported.', 'autoship' ) , count( $wc_autoship_ids ) ); ?></h4>
  <h5 class="autoship-bulk-subnotice" style="color:green;display:none;"><strong><?php echo __( 'Export started! Please wait while we prepare your export file for download.', 'autoship' );?></strong></h5>

  <p><button class="button-primary autoship-export-action autoship-ajax-button"><span><?php echo __('Export Scheduled Orders', 'autoship'); ?></span></button></p>

  <?php

  // Get the Download Link and display a download button if it exists.
  $url = autoship_get_export_download_link();
  $display = empty( $url ) ? 'display:none' : '';?>

  <a style="<?php echo $display; ?>" role="button" class="button-primary autoship-bulk-success-action" href="<?php echo $url; ?>"><?php echo __( 'Download Export File', 'autoship' ); ?></a>

  <input type="hidden" name="autoship-action" value="autoship_initiate_schedule_export">

</div>

<?php } else { ?>

<h2><?php echo __('No WC Autoship Schedules Found', 'autoship'); ?></h2>

<?php } ?>

<?php do_action( 'autoship_after_autoship_admin_migrations' ); ?>
