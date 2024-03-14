<div class="wrap">
  <h1><?php _e( 'Advanced', 'baqend' ); ?> &rsaquo; <?php _e( 'Advanced Settings', 'baqend' ); ?></h1>

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

  <p>
    <?php echo sprintf( __( '<strong>Advanced section:</strong> An inaccurate configuration of the following settings can have a negative impact on your website. Therefore this section should be handled with care.' )); ?>
  </p>

  <form action="options.php" method="post">
    <?php settings_fields( $settings_group ); ?>
    <?php echo $fields ?>
  </form>

  <br class="clear">
</div>
<script>
  const showMetricsOption = /[?|&]metrics=true/.test(window.location.href);
  if (!showMetricsOption) {
      const metricsForm = document.querySelector('#submit-baqend-metrics-enabled').parentElement.parentElement.parentElement;
      metricsForm.style.display = 'none';
  }

  const installResourceOption = /[?|&]install=true/.test(window.location.href);
  if (!installResourceOption) {
      const installResourceOptionForm = document.querySelector('#submit-baqend-install-resource-url').parentElement.parentElement.parentElement;
      installResourceOptionForm.style.display = 'none';
  }
</script>
