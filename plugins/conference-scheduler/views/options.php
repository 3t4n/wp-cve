<?php // Tabs for settings
  $current_tab = isset($_GET['tab']) ? sanitize_text_field( $_GET['tab']) : '';
  $tabs = apply_filters('conf_scheduler_settings_tabs', array());
  if ( !in_array($current_tab, array_keys($tabs)) ) $current_tab = '';
?>
<div id="wpbody-content" aria-label="Main content" tabindex="0">
  <div class="wrap<?php if($current_tab) echo ' tab-'.$current_tab;?>" id="conf_scheduler_options">
    <h1><?php _e('Conference Scheduler Settings','conf-scheduler')?>:</h1>
    <?php settings_errors(); /*  to display license messages */ ?>
    <?php if ($tabs) : ?>
      <nav class="nav-tab-wrapper">
        <a href="<?php echo esc_url( admin_url( 'edit.php?post_type=conf_workshop&page=conf_scheduler_options' ) ); ?>" class="nav-tab <?php if($current_tab == '') echo 'nav-tab-active';?>"><?php _e('General', 'conf-scheduler');?></a>
        <?php foreach($tabs as $slug => $title) : ?>
          <a href="<?php echo esc_url( admin_url( 'edit.php?post_type=conf_workshop&page=conf_scheduler_options&tab='.$slug ) ); ?>" class="nav-tab <?php if($current_tab == $slug) echo 'nav-tab-active';?>"><?php echo $title; ?></a>
        <?php endforeach; ?>
      </nav>
    <?php endif; ?>

    <?php do_action('conf_scheduler_before_options'); ?>
    <div class="conf-sidebar">
    		<div class="content">
          <img class="logo" src="<?php echo CONF_SCHEDULER_URL. 'static/images/logo.png';?>" height="34"/>
          <h2><?php _e('Conference Scheduler', 'conf-scheduler');?></h2>
          <p class="subhead"><?php echo sprintf(__('Developed by: %s', 'conf-scheduler'), '<a href="https://myceliumdesign.ca/">Shane Warner</a>');?></p>
          <p><a href="https://conferencescheduler.com/documentation/"><?php _e('Documentation','conf-scheduler');?></a></p>
          <p><a href="https://conferencescheduler.com/support/"><?php _e('Support','conf-scheduler');?></a></p>
          <h3><?php _e('Troubleshooting Information', 'conf-scheduler');?></h3>
          <p><?php _e('If you encounter errors and need to contact support, please include the System Status Report.', 'conf-scheduler');?></p>
          <a id="show-system-status" class="button"><?php _e('Get System Status Report', 'conf-scheduler');?></a>
      		<textarea id="cs-system-status" readonly>
            <?php echo $debug_info; ?>
          </textarea>
        </div>
    		<div class="footer">
    			<p><?php _e('Thanks for using Conference Scheduler!','conf-scheduler' ); ?></p>
    		</div>
    </div>
    <form method="post">
      <?php wp_nonce_field( "conf_scheduler_options", 'conf_scheduler_options_nonce' ); ?>

      <?php
        do_action('conf_scheduler_options_section', array(), $current_tab ); // depreciated the first arg in v2.4
      ?>

      <p class="submit"><input type="submit" name="submit" id="submit" class="button button-primary" value="<?php _e('Save Changes','conf-scheduler')?>"></p>
    </form>
  </div>
  <div class="clear"></div>
</div>
