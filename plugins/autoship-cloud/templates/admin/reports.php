<?php

do_action('autoship_before_autoship_admin_reports');

$tabs = apply_filters('autoship_admin_reports_tabs', array(
  array( 'list_class' => 'active', 'tab_class' => 'active',
         'id' => 'date_summary', 'label' => __( 'Products by date summary', 'autoship') ),
  array( 'list_class' => '', 'tab_class' => '',
          'id' => 'schedule_nxt_order', 'label' => __( 'Scheduled orders by product', 'autoship') ),
  array( 'list_class' => '', 'tab_class' => '',
          'id' => 'schedule_order_metrics', 'label' => __( 'Scheduled Order Metrics', 'autoship') ),
  array( 'list_class' => '', 'tab_class' => '',
          'id' => 'events_logs', 'label' => __( 'Event logs', 'autoship') )
) );

$tab_content = apply_filters('autoship_admin_reports_tabs_content', array(
  array( 'id' => 'date_summary',          'tab_class'=> 'active', 'callback' => 'autoship_admin_reports_tabs_content' ),
  array( 'id' => 'schedule_nxt_order',    'tab_class'=> '', 'callback' => 'autoship_admin_reports_tabs_content' ),
  array( 'id' => 'schedule_order_metrics','tab_class'=> '', 'callback' => 'autoship_admin_reports_tabs_content' ),
  array( 'id' => 'events_logs',           'tab_class'=> '', 'callback' => 'autoship_admin_reports_tabs_content' )
) );

?>

<div class="container autoship-admin-reports">

  <div class="col-md-12 border">
      <ul class="nav nav-tabs" role="tablist">

        <?php foreach ( $tabs as $tab ) { ?>

          <li class="<?php echo $tab['list_class']; ?>"><a class="nav-tab <?php echo $tab['tab_class']; ?>" href="#" role="tab" data-toggle="tab" id="tab-control-<?php echo $tab['id']; ?>" data-target="<?php echo $tab['id']; ?>"><?php echo $tab['label']; ?></a></li>

        <?php } ?>

      </ul>
  </div>

  <div class="tab-content">

    <?php foreach ( $tab_content as $content ) { ?>

    <div class="tab-pane <?php echo $content['tab_class'];?>" id="<?php echo $content['id'];?>">

      <?php
      $content_function = $content['callback'];
      if ( function_exists( $content_function ) )
      $content_function( $content['id'], $site_id, $token_auth );?>

    </div>

    <?php } ?>

  </div>

</div>

<?php do_action('autoship_after_autoship_admin_reports'); ?>
