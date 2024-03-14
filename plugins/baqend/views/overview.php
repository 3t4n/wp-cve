<div class="wrap">
  <h1><?php _e( 'Speed Kit', 'baqend' ); ?> &rsaquo; <?php _e( 'Getting Started', 'baqend' ); ?></h1>

  <?php include 'tabs.php'; ?>

  <?php if ($app_name === null): ?>
    <div id="login-vue"></div>
  <?php else: ?>
    <div class="overview">
      <?php if ( $bbq_password ): ?>
        <div class="overview-info">
          <h2><?php _e('Welcome to the Baqend WordPress Plugin!', 'baqend'); ?></h2>
          <p><?php _e('We automatically created a Baqend Speed Kit App for you which is connected to your WordPress.', 'baqend'); ?></p>
          <p>
            <span><?php echo sprintf( __( 'Your password to login to the <a href="%s">Baqend Dashboard</a>', 'baqend' ), 'https://dashboard.baqend.com/apps' ); ?>:</span>
            <code><?php echo $bbq_password; ?></code>
            <span><?php _e('with username', 'baqend'); ?></span>
            <code><?php echo $bbq_username; ?></code>
          </p>
        </div>
      <?php endif; ?>

      <?php include 'overview/performance.php'; ?>
      <?php include 'overview/speedKit.php'; ?>
    </div>
  <?php endif; ?>

  <br class="clear">
</div>
