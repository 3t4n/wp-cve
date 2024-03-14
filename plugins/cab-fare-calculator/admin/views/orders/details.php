<?php
require_once TBLIGHT_PLUGIN_PATH . 'fields/select.list.php';
$elsettings = BookingHelper::config();
if ( isset( $_GET['success'] ) && $_GET['success'] == 1 ) {
	?>
<div class="notice notice-success is-dismissible">
	<p><?php esc_attr_e( 'Successfully saved!', 'cab-fare-calculator' ); ?></p>
</div>
	<?php
}
?>
<script type="text/javascript">
var filePath = "<?php echo esc_attr( TBLIGHT_PLUGIN_DIR_URL ); ?>";
var itemID = "<?php echo (int) $item->id; ?>";
</script>
<?php wp_enqueue_script( 'orders-custom-details', TBLIGHT_PLUGIN_DIR_URL . 'admin/js/orders_details.js', array(), filemtime( TBLIGHT_PATH . '/admin/js/orders_details.js' ), true ); ?>
<legend class="block-heading"><?php echo esc_attr( $heading ); ?></legend>

<div class="action-btn-wrapper">	
<a href="<?php echo admin_url( 'admin.php?page=orders' ); ?>" class="button" data-action="back"><?php esc_attr_e( 'Back', 'cab-fare-calculator' ); ?></a> 
<a href="<?php echo admin_url( 'admin.php?page=orders&action=edit&id=' . $id ); ?>" class="button" data-action="edit"><?php esc_attr_e( 'Edit', 'cab-fare-calculator' ); ?></a>
</div>
<?php // echo "<pre>"; print_r($item); echo "</pre>"; ?>
<div class="tblight-wrap">    
	<div class="inputWrap clearfix">
		<label>Order Number</label>
		<div class="field-value"><?php echo esc_attr( $item->order_number ); ?></div>
	</div>
	<div class="inputWrap clearfix">
		<label>State</label>
		<div class="field-value">
			<?php echo esc_attr( BookingHelper::get_order_status_text( $item ) ); ?>
			<a href="javascript:void(0)" class="change_property_trigger">Change</a>
			<div class="change_property_wrap" style="display: none;">
				<?php echo html_entity_decode( esc_html( SelectList::getDefaultOrderStatusOptions( 'state', 'styler_list', $item->state ) )); ?>
				<a href="javascript:void(0);" class="cancel_trigger">Cancel</a>
			</div>
		</div>
	</div>		
	<div class="inputWrap clearfix">
		<label>Name</label>
		<div class="field-value"><?php echo esc_attr( $item->names ); ?></div>
	</div>
	<div class="inputWrap clearfix">
		<label>Email</label>
		<div class="field-value"><?php echo esc_attr( $item->email ); ?></div>
	</div>
	<div class="inputWrap clearfix">
		<label>Phone</label>
		<div class="field-value"><?php echo '+' . esc_attr( $item->country_calling_code ) . ' ' . esc_attr( $item->phone ); ?></div>
	</div>
	<div class="inputWrap clearfix">
		<label>Passengers</label>
		<div class="field-value"><?php echo esc_attr( $item->selpassengers ); ?></div>
	</div>
	<div class="inputWrap clearfix">
		<label>Check in luggage</label>
		<div class="field-value"><?php echo esc_attr( $item->selluggage ); ?></div>
	</div>
	<div class="inputWrap clearfix">
		<label>Child Seats</label>
		<div class="field-value"><?php echo esc_attr( $item->selchildseats ); ?></div>
	</div>
	<div class="inputWrap clearfix">
		<label>Vehicle Type</label>
		<div class="field-value"><?php echo BookingHelper::get_order_car( $item ); ?></div>
	</div>
	<div class="inputWrap clearfix">
		<label>Pick Up Date</label>
		<div class="field-value">
			<?php echo BookingHelper::date_format( esc_attr( $item->datetime1 ), 'Y-m-d H:i:s', $elsettings ) ; ?>
		</div>
	</div>
	<div class="inputWrap clearfix">
		<label>Drop Off Date</label>
		<div class="field-value">
			<?php
			$item->datetime2 = $item->datetime1 + $item->duration;
			echo BookingHelper::date_format( esc_attr( $item->datetime2 ), 'Y-m-d H:i:s', $elsettings ) ;
			?>
		</div>
	</div>
	<div class="inputWrap clearfix">
		<label>From</label>
		<div class="field-value"><?php echo esc_attr( $item->begin ); ?></div>
	</div>
	<div class="inputWrap clearfix">
		<label>To</label>
		<div class="field-value"><?php echo esc_attr( $item->end ); ?></div>
	</div>
	<div class="inputWrap clearfix">
		<label>Distance</label>
		<div class="field-value">
			<?php
				$distance_text = number_format( $item->distance, 2 ) . ' ' . $elsettings->distance_unit . 's';
				echo esc_attr( $distance_text );
			?>
					
		</div>
	</div>
	<div class="inputWrap clearfix">
		<label>Trip Duration</label>
		<div class="field-value"><?php echo esc_attr( $item->duration_text ); ?></div>
	</div>
	<?php if ( $item->sub_total != 0 ) { ?>
	<div class="inputWrap clearfix">
		<label>Sub Total</label>
		<div class="field-value"><?php echo esc_attr( $item->sub_total ); ?></div>
	</div>
	<?php } ?>

	<?php if ( $item->flat_cost != 0 ) { ?>
	<div class="inputWrap clearfix">
		<label>Flat cost</label>
		<div class="field-value"><?php echo esc_attr( $item->flat_cost ); ?></div>
	</div>
	<?php } ?>

	<?php if ( $item->percentage_cost != 0 ) { ?>
	<div class="inputWrap clearfix">
		<label>Percentage cost</label>
		<div class="field-value"><?php echo esc_attr( $item->percentage_cost ); ?></div>
	</div>
	<?php } ?>

	<div class="inputWrap clearfix">
		<label>Grand Total</label>
		<div class="field-value"><?php echo esc_attr( $item->cprice ); ?></div>
	</div>
	

	<?php if ( $item->custom_payment != '' ) { ?>
	<div class="inputWrap clearfix">
		<label>Payment</label>
		<div class="field-value"><?php echo BookingHelper::get_order_payment( $item ); ?></div>
	</div>
	<?php } ?>
	
	<?php if ( $item->payment_notes != '' ) { ?>
	<div class="inputWrap clearfix">
		<label>Payment notes</label>
		<div class="field-value"><?php echo esc_attr( $item->payment_notes ); ?></div>
	</div>
	<?php } ?>

	<?php
	$payment_data = '';
	$payment_obj  = BookingHelper::get_payment_details( $item->payment );
	if ( ! empty( $payment_obj ) ) {
		if ( ! empty( $payment_obj->payment_element ) && file_exists( TBLIGHT_PLUGIN_PATH . 'classes/payment_plugins/' . $payment_obj->payment_element . '.php' ) ) {
			require_once TBLIGHT_PLUGIN_PATH . 'classes/tbpayment.helper.php';
			require_once TBLIGHT_PLUGIN_PATH . 'classes/payment_plugins/' . $payment_obj->payment_element . '.php';

			$plugin_title      = 'plgTblightPayment' . ucfirst( $payment_obj->payment_element );
			$tb_payment_plugin = new $plugin_title();
			$payment_data      = $tb_payment_plugin->plgTbOnShowOrderBEPayment( $item->id, $item->payment );
		}
	}
	echo html_entity_decode( esc_html( $payment_data ) );
	?>
</div>
