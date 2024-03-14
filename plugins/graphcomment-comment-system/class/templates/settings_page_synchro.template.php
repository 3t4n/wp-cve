<?php

  /*
   *  Settings page : Synchronization
   *  Synchronizes the GraphComment comments back to WordPress
   *  Controller : ../controllers/gc_synchronisation_controller.class.php
   */

  /**
   * @var $activated string
   */

  $gc_sync_comments = get_option('gc_sync_comments');
  $gc_sync_interval = get_option('gc_sync_interval', '30min');
  $gc_sync_date_from = get_option('gc_sync_last_success');

  function getSynchronizationDateFormatted($date_from_str) {

    if ($date_from_str === false) {
      return __('No Sync Yet', 'graphcomment-comment-system');
    }

    try {
      $wp_timezone = GcDateTools::wp_get_timezone_string();
      // get datetime object from site timezone
      $datetime = new DateTime($date_from_str, new DateTimeZone('UTC'));
      $datetime->setTimezone(new DateTimeZone($wp_timezone));

      $date_from_str = $datetime->format(__('Date Format', 'graphcomment-comment-system'));
    } catch ( Exception $e ) {
      // you'll get an exception most commonly when the date/time string passed isn't a valid date/time
    }

    try {
      $wp_timezone = GcDateTools::wp_get_timezone_string();
      // get datetime object from site timezone
      $datetime = new DateTime();
      $datetime->setTimestamp(wp_next_scheduled('graphcomment_cron_task_sync_comments_action'));
      $datetime->setTimezone(new DateTimeZone($wp_timezone));

      $next_date_from_str = $datetime->format(__('Date Format', 'graphcomment-comment-system'));
    } catch ( Exception $e ) {
      // you'll get an exception most commonly when the date/time string passed isn't a valid date/time
    }

    return (
      __('Last Sync', 'graphcomment-comment-system') . ' ' .
      $date_from_str . ' ('. $wp_timezone .')' . '<br>' .
      __('Next Sync', 'graphcomment-comment-system') . ' ' .
      $next_date_from_str . ' ('. $wp_timezone .')'
    );
  }
?>

<?php

// Error during synchronization action
if ($gc_sync_error !== false) {
  $gc_sync_error = json_decode($gc_sync_error);
  echo '<div class="gc-alert gc-alert-danger">
    <a class="gc-close">&times;</a>
    ' . $gc_sync_error->content . '
  </div>';
}
?>


<!-- From GraphComment to WordPress panel -->
<div class="gc-form-fieldset">
  <div class="panel-heading gc-form-legend">
    <?php _e('Synchro Pannel Heading', 'graphcomment-comment-system'); ?>
    (<?php echo $gc_sync_comments === 'true'
      ? __('Activated Label', 'graphcomment-comment-system')
      : __('Not Activated Label', 'graphcomment-comment-system')
    ; ?>)
  </div>

  <p><?php _e('Synchronization Description', 'graphcomment-comment-system'); ?></p>

  <form method="post" action="options.php">

    <input type="hidden" name="gc_action" value="synchronization"/>

    <div class="gc-form-field gc-checkbox gc-form-field-sync">
      <label>
        <input type="hidden" name="gc_sync_comments" value="false"/>
        <input
          id="graphcomment_sync_comments_checkbox" type="checkbox" name="gc_sync_comments"
          value="true" <?php echo (!$activated) ? 'disabled' : ''; ?>
          <?php echo($gc_sync_comments === 'true' ? 'checked' : ''); ?>
        />
        <?php _e('sync_comments', 'graphcomment-comment-system'); ?>
      </label>

      <select class="form-control" name="gc_sync_interval">
        <option value="1min" <?php echo $gc_sync_interval === '1min' ? 'selected' : '' ?>><?php _e('Every 1 minute', 'graphcomment-comment-system'); ?></option>
        <option value="10min" <?php echo $gc_sync_interval === '10min' ? 'selected' : '' ?>><?php _e('Every 10 minutes', 'graphcomment-comment-system'); ?></option>
        <option value="30min" <?php echo $gc_sync_interval === '30min' ? 'selected' : '' ?>><?php _e('Every 30 minutes', 'graphcomment-comment-system'); ?></option>
        <option value="1h" <?php echo $gc_sync_interval === '1h' ? 'selected' : '' ?>><?php _e('Every 1 hour', 'graphcomment-comment-system'); ?></option>
        <option value="12h" <?php echo $gc_sync_interval === '12h' ? 'selected' : '' ?>><?php _e('Every 12 hours', 'graphcomment-comment-system'); ?></option>
      </select>
    </div>

    <p class="gc-text-lite"><?php echo getSynchronizationDateFormatted($gc_sync_date_from); ?></p>

    <?php submit_button(); ?>

  </form>

</div>
