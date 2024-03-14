<?php

do_action( 'autoship_before_autoship_admin_logs' );

$files = autoship_get_log_files();
$file_count = count( $files );
$keep_count = autoship_get_logs_keep_count();
$first_download_link = '';

?>

<h2><?php echo __('Autoship Cloud Logs', 'autoship' ); ?></h2>

<p class="autoship-logs-description"><?php echo sprintf( __('The Autoship Cloud Logging system is useful for troubleshooting issues with your Autoship Cloud and QPilot Integration. Entries are added to daily log files and the latest <strong>%d</strong> log files are retained.', 'autoship' ), $keep_count ); ?></p>

<div class="autoship-bulk-action">

  <div class="options_group autoship-logs">
    <div class="autoship-logs-download">
      <h3><?php echo __('Autoship Log File Download','autoship');?></h3>

      <?php if ( empty( $files ) ){ ?>

        <p><i><?php echo __('There are currently no log files.','autoship');?></i></p>

      <?php } else { ?>

      <p><?php echo __('Select a log file in the drop down below and then click the <strong>Download File</strong> button to download the selected file.','autoship');?></p>
      <div class="options_group options-form-group log-files">

        <div class="option-form-row auto-flex-row">
          <div class="option-form-group auto-flex-col">
            <select id="_autoship_log_file_select" class="option-form-control" name="_autoship_log_file_select">

              <?php foreach ( $files as $name => $time ): ?>

              <?php
              $download_link = autoship_get_log_file_download_link( $name );
              $display_name  = apply_filters( 'autoship_log_file_download_select_display_name', date("l\, F dS Y", $time ), $time, $name );

              if ( empty( $first_download_link ) )
              $first_download_link = $download_link; ?>

              <option value="<?php echo $download_link;?>"><?php echo $display_name; ?></option>

              <?php endforeach; ?>

            </select>
          </div>
          <div class="auto-flex-col-auto">
            <a href="<?php echo $first_download_link;?>" class="btn-primary autoship-action autoship-ajax-button flex-btn no-label" download><span><?php echo __('Download File', 'autoship'); ?></span></a>
          </div>
        </div>

      </div>

      <?php } ?>

    </div>
  </div>

</div>


<?php

do_action( 'autoship_after_autoship_admin_logs' );
