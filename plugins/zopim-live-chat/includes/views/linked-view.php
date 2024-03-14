<div id="icon-options-general" class="icon32"><br/></div>
<h2><?php echo $messages[ 'page-header' ] ?></h2>
<br/>
<div class="zopim-linked-view-wrapper">
    <span>
      <form method="post" action="admin.php?page=zopim_account_config">
        <?php wp_nonce_field('zopim_plugin_deactivate') ?>
        <input type="hidden" name="action" value="deactivate"/>
        <button class="zopim-deactivate-button">
          <?php echo $messages[ 'deactivate' ] ?>
        </button>
      </form>
    </span>
  <?php echo $messages[ 'current-account-label' ] ?> &rarr;
  <b><?php echo get_option( Zopim_Options::ZOPIM_OPTION_USERNAME ); ?></b>

  <?php if ( $package_id != '' ) { ?>
    <div class="zopim-package-title"><?php echo ucwords( $package_id ); ?></div>
  <?php }?>

  <br><br><?php echo $messages[ 'dashboard-access-label' ]; ?>
  <br><br>
  <a class="no-underline" href="<?php echo $dashboardLink ?>"
     target="_blank"
     data-popup="true">
    <div class="zopim_btn_orange"><?php echo $messages[ 'launch-dashboard' ]; ?></div>
  </a>
  &nbsp;&nbsp;(<?php echo $messages[ 'open-tab-label' ]; ?>)


  <form method="post" action="admin.php?page=zopim_account_config">
    <?php
    $notices = Zopim_Notices::get_instance();
    $notices->do_notices( 'before_udpate_widget_textarea' );
    ?>
    <p>
      <?php wp_nonce_field('zopim_widget_options') ?>
      <?php echo $messages[ 'textarea-label' ]; ?>
      <br/>
      <textarea name="widget-options"><?php echo esc_textarea( Zopim_Options::get_widget_options() ); ?></textarea>
      <br/>
      <input class="button-primary" type="submit" value="Update widget options"/>
    </p>
  </form>

</div>
