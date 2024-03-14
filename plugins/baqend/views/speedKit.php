<div class="wrap">
  <h1><?php _e( 'Speed Kit', 'baqend' ); ?> &rsaquo; <?php _e( 'Settings', 'baqend' ); ?></h1>

  <div id="speed-kit-update-error" class="notice notice-error hidden"></div>

  <?php include 'tabs.php'; ?>

  <?php if ( $speed_kit->isOutdated() ): ?>
    <div id="speed-kit-outdated" class="update-nag">
      <form id="form-update-speed-kit">
        <p><?php echo sprintf( __( 'Your Speed Kit version %s is outdated. Update to version %s now!', 'baqend' ), $speed_kit->getLocalVersion(), $speed_kit->getRemoteVersion() ); ?></p>
        <div class="submit-wrap">
          <?php submit_button( __( 'Update Speed Kit', 'baqend' ) ); ?>
          <div class="spinner"></div>
        </div>
        <br class="clear">
      </form>
    </div>
  <?php endif; ?>

  <table style="width:100%;">
    <tr>
      <td>
        <?php echo sprintf( __( 'Not sure what to do? Get more information about <strong>Speed Kit</strong> in the <a href="%s">Speed Kit Help!</a>', 'baqend' ), baqend_admin_url( 'baqend_help#speed-kit' ) ); ?>
      </td>
      <td rowspan="2">
        <form id="form-trigger-speed-kit" style="float:right;">
          <div class="submit-wrap">
            <div class="spinner"></div>
            <?php submit_button( __( 'Revalidate Website', 'baqend' ) ); ?>
          </div>
          <br class="clear">
        </form>
      </td>
    </tr>
  </table>

  <form action="options.php" method="post">
    <?php settings_fields( $settings_group ); ?>
    <?php echo $fields ?>
  </form>

  <br class="clear">
</div>
