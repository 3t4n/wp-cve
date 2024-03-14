<?php

// Retrieve the Autoship site connection params.
$site_parameters = autoship_get_site_parameters ();

// Retrieve the Autoship site subscription status.
$subscription_status = autoship_get_subscription_status();

// Retrieve all the Autoship fields into an array.
$autoship_settings = autoship_get_settings_fields ();

// Get the current settings page tabs and associated callback functions.
// The tab array and be modified by the autoship_admin_settings_tabs filter
$tabs = autoship_settings_tabs();
$active_tab = isset( $_GET[ 'tab' ] ) && isset( $tabs[$_GET[ 'tab' ]] )? $_GET[ 'tab' ] : apply_filters('autoship_admin_settings_default_tab', key($tabs) );

?>

<div id="asc-settings" class="wrap">

  <h1><?php echo __( 'Autoship Cloud powered by', 'autoship' ); ?> <a href="https://support.autoship.cloud/article/392-what-is-qpilot" target="_blank" style="text-decoration: none"></span><?php echo __( 'QPilot&trade;', 'autoship' ); ?></a></h1>
  <p><a href="https://merchants.qpilot.cloud/login/register?utm_source=AutoshipCloudPlugin&utm_medium=Settings&utm_campaign=Autoship_Cloud_Plugin" target="_blank"><?php echo __( "Create a free QPilot Merchant Account", "autoship" ); ?></a><?php echo __( " to get started with Autoship Cloud.", "autoship" );?><br/><?php echo __( "Need help?", 'autoship'); ?><a href="http://support.autoship.cloud/" target="_blank"><?php echo __( " Click here for Autoship Cloud Online Support.", 'autoship'); ?></a>

  <?php

  // Check security rights for the settings page
  // Since this is both a submenu and the main page for security control we add a custom filter,
  if ( autoship_rights_checker( 'autoship_cloud_main_page_options_security', array('administrator') ) ){ ?>

  <?php if ( $subscription_status == 'None' ): ?>
    <div class="subscription-status-none">
      <?php
      $merchant_url = esc_attr( autoship_get_merchants_url() );
      $template = "Your subscription is not active. Log in to the <a href='%s'>QPilot Merchant Center</a> to activate your subscription.";
      echo sprintf( __( $template, 'autoship' ), $merchant_url);
      ?>
    </div>
  <?php endif; ?>

  <?php do_action('autoship_after_admin_settings_header', $autoship_settings, $active_tab, $tabs ); ?>

  <h2 class="nav-tab-wrapper">

    <?php foreach ($tabs as $tab => $values) { ?>

      <a href="<?php echo admin_url( 'admin.php?page=autoship&tab='. $tab ); ?>" class="nav-tab <?php echo $values['link_class']; ?> <?php echo $active_tab == $tab ? 'nav-tab-active' : ''; ?>"><?php echo $values['label'];?></a>

    <?php } ?>

  </h2>

  <form method="post" action="options.php" autocomplete="off">

  <?php
  settings_fields( 'autoship-settings-group' );
  do_settings_sections( 'autoship-settings-group' );
  $current_user=get_current_user_id();
  $user_meta = get_userdata($current_user); ?>

  <input type="hidden" id="uemail" value="<?php echo $user_meta->user_email; ?>">

  <?php
  // Loop through the tabs and add in content
  foreach ($tabs as $tab => $values ) { ?>

  <div id="<?php echo $tab; ?>" class="wrap" style="display:<?php echo $active_tab == $tab ? 'block' : 'none';?>;">

    <?php
    $function = $values['callback'];
    if ( function_exists( $function ) )
    $function($autoship_settings);
    ?>

  </div>

  <?php } ?>

  <?php

  if ( apply_filters('autoship_admin_settings_tab_include_submit', true, $active_tab ) )
  submit_button( 'Update' );

  ?>

  </form>

  <?php } ?>

</div>
