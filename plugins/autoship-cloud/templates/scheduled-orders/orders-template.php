<?php
/**
 * Schedule Orders Page
 *
 * This template can be overridden by copying it to yourtheme/autoship-cloud/templates/scheduled-orders/orders-template.php
*/
defined( 'ABSPATH' ) || exit;

/*
* The Skins Filter Allows Devs to Completely customize the forms classes.
*/
$skin = apply_filters( 'autoship_orders_form_skin', array(
'container'             => '',
'table'                 => 'woocommerce-orders-table woocommerce-MyAccount-orders shop_table shop_table_responsive my_account_orders account-orders-table',
'table_head'            => '',
'table_head_row'        => '',
'table_head_cell'       => 'woocommerce-orders-table__header woocommerce-orders-table__header-',
'table_body'            => '',
'table_row'             => 'woocommerce-orders-table__row order woocommerce-orders-table__row--status-',
'table_row_notice'      => 'woocommerce-orders-table__row',
'table_cell'            => 'woocommerce-orders-table__cell order-row woocommerce-orders-table__cell-',
'table_cell_action'     => 'autoship-icon-button autoship-button',
'table_cell_notice'     => 'autoship-order-notice',
));

// Get the Site Settings that include Lock Duration etc.
$settings = autoship_get_remote_saved_site_settings();

?>

<div class="<?php echo $skin['container'];?> <?php echo apply_filters('autoship_scheduled_orders_template_classes', 'autoship-scheduled-orders-template-container', $autoship_orders, $customer_id, $autoship_customer_id );?>">

	<?php do_action( 'autoship_before_autoship_scheduled_orders_template', $customer_id, $autoship_customer_id, $autoship_orders ); ?>

  	<table class="autoship-scheduled-orders-table <?php echo $skin['table'];?>">
  		<thead class="<?php echo $skin['table_head'];?>">
  			<tr class="<?php echo $skin['table_head_row'];?>">
  				<?php foreach ( autoship_get_my_account_scheduled_orders_columns() as $column_id => $column_name ) : ?>
  					<th class="<?php echo $skin['table_head_cell'];?> <?php echo esc_attr( $column_id );?>"><span class="nobr"><?php echo esc_html( $column_name ); ?></span></th>
  				<?php endforeach; ?>
  			</tr>
  		</thead>

  		<tbody class="<?php echo $skin['table_body'];?>">

  			<?php foreach ( $autoship_orders as $autoship_order ) :

          $order_number   = $autoship_order->id;
          $order_status   = autoship_get_scheduled_order_status_nicename( $autoship_order->status );
          $order_total    = $autoship_order->total;
          $order_currency = $autoship_order->currencyIso;
          $view_url       = autoship_get_view_edit_scheduled_order_url($order_number, $autoship_order->status );
  				$counts         = autoship_get_item_count( $autoship_order, true );

          // Calculate new totals if needed else use the current totals.
          $totals = autoship_get_calculated_scheduled_order_object_totals( $autoship_order, $autoship_order->scheduledOrderItems, $counts['ids'] );
          $totals = autoship_get_formatted_order_display_totals( $totals , $autoship_order->currencyIso );

          $order_date     = autoship_get_formatted_local_date ( $autoship_order->nextOccurrenceUtc );
          $notice         = apply_filters(
            'autoship_orders_notice_message',
            isset( $autoship_order->scheduledOrderFailureReason ) ? $autoship_order->scheduledOrderFailureReason : '',
            $autoship_order );

          $columns        = autoship_get_my_account_scheduled_orders_columns();

          // Get the Lock Data.
          $lock_data      = autoship_check_lock_status_info ( $autoship_order );

          ?>

  				<tr class="<?php echo $skin['table_row'];?> <?php echo esc_attr( $order_status );?> <?php echo empty( $notice )? '' : 'has-notice'; ?> <?php echo $lock_data['locked'] ? 'order-locked' : ''; ?>" id="row-<?php echo $order_number;?>">

  					<?php foreach ( autoship_get_my_account_scheduled_orders_columns() as $column_id => $column_name ) : ?>

  						<td class="<?php echo $skin['table_cell'];?><?php echo esc_attr( $column_id );?>" data-title="<?php echo esc_attr( $column_name ); ?>">
  							<?php if ( has_action( 'autoship_my_account_my_scheduled_orders_column_' . $column_id ) ) : ?>
  								<?php do_action( 'autoship_my_account_my_scheduled_orders_column_' . $column_id, $order ); ?>

  							<?php elseif ( 'order-number' === $column_id ) : ?>
  								<a href="<?php echo esc_url( $view_url ); ?>">
  									<?php echo _x( '#', 'hash before scheduled order number', 'autoship' ) . $order_number; ?>
  								</a>

  							<?php elseif ( 'order-date' === $column_id ) : ?>
  								<time datetime="<?php echo esc_attr( $order_date ); ?>"><?php echo esc_html( $order_date ); ?></time>

  							<?php elseif ( 'order-status' === $column_id ) : ?>
  								<?php echo '<span class="status-icon">' . $order_status . '</span>'; ?>

  							<?php elseif ( 'order-total' === $column_id ) : ?>

  								<?php
  								/* translators: 1: formatted order total 2: total order items */
  								printf( _n( '%1$s for %2$s item', '%1$s for %2$s items', $counts['count'], 'autoship' ), $totals['total']['value'], $counts['count'] );
  								?>

  							<?php elseif ( 'order-actions' === $column_id ) : ?>

                  <?php

                  // Get the Actions and Apply the final Display Filter
                  $actions = autoship_get_account_scheduled_orders_actions( $order_number, $autoship_order->status );
                  $actions = apply_filters( 'autoship_scheduled_orders_display_actions_filter', $actions, $autoship_order, $customer_id, $settings );
  								if ( ! empty( $actions ) ) {
  									foreach ( $actions as $key => $action ) {
  										echo '<a title="'. esc_html( $action['title'] ) .'" href="' . esc_url( $action['url'] ) . '" class="' . $skin['table_cell_action'] . ' ' . sanitize_html_class( strtolower( $key ) ) . '" data-autoship-order="'. $order_number .'"  data-autoship-action="'. $key .'" data-autoship-view="orders"><span class="screen-reader-text">' . esc_html( $action['name'] ) . '</span></a>';
  									}
  								}
  								?>
  							<?php endif; ?>
  						</td>

            <?php endforeach; ?>

  				</tr>

          <?php
          /**
           * Post Scheduled Orders Order Row Hook.
           *
           * @hooked autoship_scheduled_orders_row_notice_template_display - 10
           */
           do_action( 'autoship_after_autoship_scheduled_orders_template_row', $autoship_order, $customer_id, $autoship_customer_id ); ?>

  			<?php endforeach; ?>

      </tbody>
  	</table>

	<?php do_action( 'autoship_after_autoship_scheduled_orders_template', $customer_id, $autoship_customer_id, $autoship_orders ); ?>
</div>
