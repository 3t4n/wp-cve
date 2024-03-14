<?php
/**
 * Schedule Order Item Meta Template
 *
 * This template can be overridden by copying it to yourtheme/autoship-cloud/templates/scheduled-orders/order-item-meta-template.php
*/
defined( 'ABSPATH' ) || exit;

if ( ! apply_filters( 'autoship_include_scheduled_order_item_meta', true, $autoship_order, $customer_id, $autoship_customer_id ) )
return;

/*
* The Skins Filter Allows Devs to Completely customize the forms classes.
*/
$skin = apply_filters( 'autoship_schedule_order_item_meta_skin', array(
'conatiner'             => '',
'meta_item'             => '',
'sku'                   => '',
'meta'                  => '',
));

$meta_data = apply_filters('autoship_schedule_order_item_meta_details', $meta_data, $autoship_order, $customer_id, $autoship_customer_id );

?>


            <div class="autoship-scheduled-order-item-meta <?php echo $skin['conatiner']; ?>">

              <?php foreach ($meta_data as $value): ?>

              <div class="autoship-meta-item <?php echo $skin['meta_item'];?>">
                <?php echo $value; ?>
              </div>

              <?php endforeach; ?>

            </div>
