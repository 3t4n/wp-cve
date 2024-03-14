<?php
/**
 * The Main Product Autoship Info Dialog Template
 *
 * This template can be overridden by copying it to yourtheme/autoship-cloud/templates/product/schedule-info-dialog.php
*/

$footer = apply_filters( 'autoship_powered_by_credits', '<p class="autoship-credits"><a href="https://autoship.cloud/?utm_source=AutoshipCloudPlugin&utm_medium=PoweredByLink&utm_campaign=Autoship_Cloud_Plugin" target="_blank" rel="nofollow" >Autoship powered by <img src="' . Autoship_Plugin_Url . '/images/qpilot.svg" class="qpilot"/></a></p>' );

?>

<div class="autoship-schedule-info-dialog">

  <?php do_action('autoship_before_schedule_info_dialog', $options ); ?>

  <?php autoship_generate_modal( 'autoship_schedule_info_dialog_modal', wp_kses_post( $options['autoship_product_info_html'] ) ,
  'customer-modal ' . $options['autoship_product_info_modal_size'], $footer, 'modal' == $options['autoship_product_info_display'] ); ?>

  <?php do_action('autoship_after_schedule_info_dialog', $options ); ?>

</div>
