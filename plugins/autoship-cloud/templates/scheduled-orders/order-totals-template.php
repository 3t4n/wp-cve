<?php
/**
 * Scheduled Order Totals Template
 *
 * This template can be overridden by copying it to yourtheme/autoship-cloud/templates/scheduled-orders/order-totals-template.php
*/
defined( 'ABSPATH' ) || exit;

if ( ! apply_filters( 'autoship_include_scheduled_order_totals', true, $autoship_order, $customer_id, $autoship_customer_id ) )
return;

/*
* The Skins Filter Allows Devs to Completely customize the forms classes.
*/
$skin = apply_filters( 'autoship_scheduled_order_totals_skin', array(
'container'             => 'cart-collaterals',
'content'               => 'cart_totals',
'table'                 => 'shop_table shop_table_responsive',
'table_body'            => '',
'table_row'             => 'cart-',
'table_head'            => '',
'table_cell'            => '',
));

// Calculate new totals if needed else use the current totals.
$totals = autoship_get_calculated_scheduled_order_totals( $autoship_order, $autoship_order_items );
$totals = autoship_get_formatted_order_display_totals( $totals , $autoship_order['currencyIso'] );

?>

  <div class="<?php echo $skin['container']?> <?php echo apply_filters( 'autoship_view_scheduled_order_totals_template_classes', 'autoship-order-totals-section', $autoship_order ); ?>">
    <div class="<?php echo $skin['content']?>">

      <?php do_action( 'autoship_before_scheduled_order_totals_table', $totals, $autoship_order, $customer_id, $autoship_customer_id ); ?>

    	<table cellspacing="0" class="autoship-order-totals <?php echo $skin['table']?>">
    		<tbody class="<?php echo $skin['table_body']?>">

          <?php do_action( 'autoship_before_scheduled_order_total_rows', $totals, $autoship_order, $customer_id, $autoship_customer_id ); ?>

    			<?php	foreach ( $totals as $key => $total ) {

            $label_html = apply_filters( 'autoship_formatted_order_display_totals_html_label', $total['label'], $key, $autoship_order );

          ?>

            <?php do_action( "autoship_before_scheduled_order_total_{$key}_row", $totals, $autoship_order, $customer_id ); ?>

            <tr class="<?php echo $skin['table_row']?><?php echo $key;?>">
  						<th class="<?php echo $skin['table_head']?>" scope="row"><?php echo $label_html; ?></th>
  						<td class="<?php echo $skin['table_cell']?>" data-title="<?php echo $total['label']; ?>"><?php echo $total['value']; ?></td>
  					</tr>

            <?php do_action( "autoship_after_scheduled_order_total_{$key}_row", $totals, $autoship_order, $customer_id ); ?>

          <?php	} ?>

          <?php do_action( 'autoship_after_scheduled_order_total_rows', $totals, $autoship_order, $customer_id, $autoship_customer_id ); ?>

      	</tbody>
      </table>

      <?php do_action( 'autoship_after_scheduled_order_totals_table', $totals, $autoship_order, $customer_id, $autoship_customer_id ); ?>

    </div>
  </div><!-- .autoship-order-totals-section -->
