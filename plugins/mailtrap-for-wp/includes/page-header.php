<h2>Mailtrap for Wordpress</h2>

<h2 class="nav-tab-wrapper">
  <a href="<?php echo admin_url('options-general.php?page=mailtrap-settings'); ?>" class="nav-tab <?php echo $_GET['page'] == 'mailtrap-settings' ? 'nav-tab-active' : ''?>"><?php echo __( 'Account Settings', 'mailtrap-for-wp' ) ?></a>
  <a href="<?php echo admin_url('admin.php?page=mailtrap-test'); ?>" class="nav-tab <?php echo $_GET['page'] == 'mailtrap-test' ? 'nav-tab-active' : ''?>"><?php echo __( 'Run a test', 'mailtrap-for-wp' ) ?></a>
  <?php if (get_option('mailtrap_api_token')): ?>
  <a href="<?php echo admin_url('admin.php?page=mailtrap-inbox'); ?>" class="nav-tab <?php echo $_GET['page'] == 'mailtrap-inbox' ? 'nav-tab-active' : ''?>"><?php echo __( 'Inbox', 'mailtrap-for-wp' ) ?></a>
  <?php endif; ?>
</h2>
