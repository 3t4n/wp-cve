<?php

  /*
   *  Settings page : Import
   *  Synchronizes comments from WordPress to GraphComment
   *  Controller : ../controllers/gc_import_controller.class.php
   */

  $gcImport = new GcImportService();
  $gc_import_error = $gcImport->getImportErrorMsg();
  $status = $gcImport->getImportStatus();
  $nbr_comment_import = $gcImport->getNbrCommentsImport();
  $total = $gcImport->getTotalComments();
  $dateBegin = $gcImport->getDateBegin();
  $dateEnd = $gcImport->getDateEnd();
  $progress = $total ? $nbr_comment_import / $total * 100 : 100;

  function getPanelStatusClass($gc_status) {
    switch ($gc_status) {
      case 'error':
        return 'danger';
      case 'finished':
        return 'success';
      case 'pending':
        return 'warning';
      default:
        return 'info';
    }
  }
?>


<?php
  // Error during importation action
  if ($gc_import_error !== false && $status !== 'finished') {
    $gc_import_error = $gc_import_error;
    echo '<div class="gc-alert gc-alert-danger">
      <a class="gc-close">&times;</a>
      ' . $gc_import_error . '
    </div>';
  }
?>

<div id="graphcomment-import-pannel" class="gc-form-fieldset status-<?php echo getPanelStatusClass($status); ?>">
  <div class="gc-form-legend"><?php _e('Import Pannel Heading', 'graphcomment-comment-system'); ?></div>

  <p><?php _e('Importation Description', 'graphcomment-comment-system'); ?></p>

  <form id="gc-form-import" class="gc-form" method="post" action="options.php">

    <div class="gc-form-fieldset">
      <input type="hidden" name="gc_action" value="importation"/>
      <input type="hidden" name="gc-import-status" value="<?php echo $status; ?>"/>

      <?php if ($status === 'pending'): ?>
        <?php _e('import_status_label', 'graphcomment-comment-system') ?>:
        <span class="label label-info label-import"><?php _e('Status Pending Label', 'graphcomment-comment-system') ?></span>

        <div class="progress">
          <div class="progress-bar progress-bar-info progress-bar-striped progress-bar-animated" role="progressbar"
               aria-valuenow="<?php echo $nbr_comment_import; ?>"
               aria-valuemin="0" aria-valuemax="<?php echo $total; ?>"
               style="width:<?php echo $progress; ?>%">
            <?php echo floor($progress); ?>%
          </div>
        </div>

        <p class="gc-import-nbr">
          (<span><?php echo $nbr_comment_import; ?></span> comments imported on <?php echo $total; ?>)
        </p>

        <dl class="dl-horizontal">
          <dt><?php _e('Import Started On Label', 'graphcomment-comment-system'); ?>:</dt>
          <dd><?php echo date("l j F Y G:i:s", strtotime($dateBegin)); ?></dd>
          <dt class="hide gc-import-finished-label"><?php _e('Import Finished On Label', 'graphcomment-comment-system'); ?>:</dt>
          <dd class="hide gc-import-finished-date"></dd>
        </dl>

        <div class="gc-import-pending-stop">
          <input type="hidden" name="gc-import-stop" value="true"/>
          <?php submit_button(__('Cancel Import Button', 'graphcomment-comment-system')); ?>
        </div>

      <?php elseif ($status === 'error'): ?>
        <?php _e('import_status_label', 'graphcomment-comment-system') ?>:
        <span class="label label-danger label-import"><?php _e('Status Error Label', 'graphcomment-comment-system'); ?></span>

        <input type="hidden" name="gc-import-restart" value="true"/>

        <div class="progress">
          <div class="progress-bar progress-bar-success progress-bar-danger" role="progressbar"
               aria-valuenow="<?php echo $nbr_comment_import; ?>"
               aria-valuemin="0" aria-valuemax="<?php echo $total; ?>"
               style="width:<?php echo $progress; ?>%">
            <?php echo floor($progress); ?>%
          </div>
        </div>

        <p class="gc-import-nbr">
          (<span><?php echo $nbr_comment_import; ?></span> comments imported on <?php echo $total; ?>)
        </p>

        <dl class="dl-horizontal">
          <dt><?php _e('Import Started On Label', 'graphcomment-comment-system'); ?>:</dt>
          <dd><?php echo date("l j F Y G:i:s", strtotime($dateBegin)); ?></dd>
        </dl>

        <?php submit_button(__('Restart Import Button', 'graphcomment-comment-system')); ?>

      <?php elseif ($status === 'finished'): ?>
        <?php _e('import_status_label', 'graphcomment-comment-system') ?>:
        <span class="label label-success label-import"><?php _e('Status Finished Label', 'graphcomment-comment-system') ?></span>

        <div class="progress">
          <div class="progress-bar progress-bar-success progress-bar-success" role="progressbar"
               aria-valuenow="<?php echo $nbr_comment_import; ?>"
               aria-valuemin="0" aria-valuemax="<?php echo $total; ?>"
               style="width:<?php echo $progress; ?>%">
            <?php echo floor($progress); ?>%
          </div>
        </div>

        <p class="gc-import-nbr">
          (<span><?php echo $nbr_comment_import; ?></span> comments imported on <?php echo $total; ?>)
        </p>

        <dl class="dl-horizontal">
          <dt><?php _e('Import Started On Label', 'graphcomment-comment-system'); ?>:</dt>
          <dd><?php echo date("l j F Y G:i:s", strtotime($dateBegin)); ?></dd>
          <dt><?php _e('Import Finished On Label', 'graphcomment-comment-system'); ?>:</dt>
          <dd><?php echo date("l j F Y G:i:s", strtotime($dateEnd)); ?></dd>
        </dl>

      <?php else: ?>
        <!--
        <?php _e('import_status_label', 'graphcomment-comment-system') ?>:
        <span class="label label-default label-import"><?php _e('Status No Import Label', 'graphcomment-comment-system') ?></span>
        -->

        <?php submit_button(__('Start Import Button', 'graphcomment-comment-system')); ?>

      <?php endif; ?>
    </div>

  </form>
</div>
