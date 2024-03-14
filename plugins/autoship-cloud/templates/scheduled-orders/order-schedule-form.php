<?php
/**
 * Scheduled Order Schedule Edit Form
 *
 * This template can be overridden by copying it to yourtheme/autoship-cloud/templates/scheduled-orders/order-schedule-form.php
*/
defined( 'ABSPATH' ) || exit;

if ( ! apply_filters( 'autoship_include_scheduled_order_schedule_form', true, $autoship_order, $customer_id, $autoship_customer_id ) )
return;

/*
* The Skins Filter Allows Devs to Completely customize the forms classes.
*/
$skin = apply_filters( 'autoship_scheduled_order_schedule_form_skin', array(
'form'                      => '',
'container'                 => '',
'frequency_container'       => '',
'frequency_label'           => '',
'frequency_select'          => '',
'next_occurrence_container' => '',
'next_occurrence_label'     => '',
'next_occurrence_input'     => '',
'action_btn_container'      => '',
'action_btn'                => '',
));

// Date Components
$next_date        =  autoship_get_formatted_local_date ( $autoship_order['nextOccurrenceUtc'] );
$next_input_date  =  autoship_get_formatted_local_date ( $autoship_order['nextOccurrenceUtc'], 'Y-m-d' );
$min_next_available =  apply_filters( 'autoship_scheduled_order_edit_schedule_form_min_date', autoship_get_next_available_nextoccurrence(  $autoship_order, 'Y-m-d' ), $next_date, $autoship_order);

// Get the current frequencies
$current_frequency = $autoship_order['frequencyType'] . ':' . $autoship_order['frequency'];
$frequency_options = autoship_get_all_valid_order_change_frequencies( $autoship_order );

// Build the Frequency drop down.
foreach ( $frequency_options as $frequency_option)
$select_frequency_options[$frequency_option['frequency_type']. ':' . $frequency_option['frequency']] = $frequency_option['display_name'];

?>

  <?php
  /**
  * @hooked autoship_edit_scheduled_order_schedule_form_display_action
  */
  do_action( 'autoship_before_update_scheduled_order_schedule_form', $autoship_order, $customer_id, $autoship_customer_id ); ?>


  <form class="<?php echo $skin['form'];?> <?php echo apply_filters( 'autoship_edit_scheduled_order_schedule_form_classes', 'autoship-scheduled-order-edit-form autoship-edit-scheduled-order-schedule-form', $autoship_order, $customer_id, $autoship_customer_id ); ?>"  method="post" <?php do_action( 'autoship_edit_scheduled_order_schedule_form_tag' ); ?> >

    <div class="autoship-order-details-summary-form <?php echo $skin['container'];?>">

      <?php do_action( 'autoship_before_edit_scheduled_order_schedule_form', $autoship_order, $customer_id, $autoship_customer_id ); ?>

      <div class="frequency <?php echo $skin['frequency_container'];?>">

        <label class="<?php echo $skin['frequency_label'];?>" for="autoship-frequency-select"><?php echo apply_filters( 'autoship_edit_scheduled_order_schedule_form_frequency_label', __( 'Schedule', 'autoship' ) ); ?></label>

        <select name="autoship_order_frequency" class="autoship-frequency-select <?php echo $skin['frequency_select'];?>" id="autoship-frequency-select">

          <?php foreach ( $select_frequency_options as $frequency_option => $frequency_option_name ):?>

            <option value="<?php echo $frequency_option;?>" <?php selected( $frequency_option, $current_frequency ); ?>><?php

            echo esc_html( $frequency_option_name );

            ?></option>

          <?php endforeach; ?>

        </select>

      </div>

      <div class="next-occurrence <?php echo $skin['next_occurrence_container'];?>">

        <label for="autoship-next-occurrence <?php echo $skin['next_occurrence_label'];?>"><?php echo __( 'Next Occurrence', 'autoship' ); ?></label>

        <input type="date" name="autoship_next_occurrence" class="autoship-next-occurrence <?php echo $skin['next_occurrence_input'];?>" value="<?php echo $next_input_date; ?>" min="<?php echo $min_next_available; ?>" id="autoship-next-occurrence">

      </div>

      <?php do_action( 'autoship_after_update_scheduled_order_schedule', $autoship_order, $customer_id, $autoship_customer_id ); ?>

      <div class="autoship-update-action autoship-update-schedule-action <?php echo $skin['action_btn_container'];?>">

        <?php do_action( 'autoship_update_scheduled_order_schedule_actions', $autoship_order, $customer_id, $autoship_customer_id ); ?>

        <button type="submit" class="button autoship-action-btn <?php echo $skin['action_btn'];?>" name="autoship_update_order_schedule" value="<?php echo apply_filters( 'autoship_scheduled_order_edit_schedule_form_action_label', __('Update Schedule', 'autoship' )); ?>"><?php echo apply_filters( 'autoship_scheduled_order_edit_schedule_form_action_label', __('Update Schedule', 'autoship' )); ?></button>

        <input type="hidden" name="autoship_scheduled_order_id" value="<?php echo $autoship_order['id']; ?>" />

        <?php do_action( 'autoship_update_scheduled_order_schedule_form_hidden_fields', $autoship_order, $customer_id, $autoship_customer_id ); ?>

      	<?php

        // Get Nonce & Push Autoship order to Session
        $nonce = wp_create_nonce( 'autoship-update-scheduled-order-schedule' );
        autoship_load_scheduled_order_into_session( $nonce, $autoship_order );?>

        <input type="hidden" id="autoship-update-scheduled-order-schedule-nonce" name="autoship-update-scheduled-order-schedule-nonce" value="<?php echo $nonce; ?>">

        <?php wp_referer_field(); ?>

      </div>

    </div>

  </form>
