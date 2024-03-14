<?php
require_once TBLIGHT_PLUGIN_PATH . 'classes/company.helper.php';
require_once TBLIGHT_PLUGIN_PATH . 'fields/select.list.php';
$elsettings = BookingHelper::config();

$this->country_obj     = CompanyHelper::getCountryById( $elsettings->default_country );
$this->default_country = ( $this->country_obj ) ? $this->country_obj->country_2_code : '';

$hr_options_arr = array();
for ( $i = 0; $i <= 23; $i++ ) {
	$value                    = str_pad( $i, 2, '0', STR_PAD_LEFT );
	$hr_options_arr[ $value ] = $value;
}
$min_options_arr = array();
for ( $i = 0; $i <= 59; $i = $i + 5 ) {
	$value                     = str_pad( $i, 2, '0', STR_PAD_LEFT );
	$min_options_arr[ $value ] = $value;
}
// get the maximum passengers
$max           = BookingHelper::getMaxSeatsData();
$max_passenger = isset( $max->max_passenger ) ? $max->max_passenger : 8;
$max_suitcase  = isset( $max->max_suitcase ) ? $max->max_suitcase : 9;
$max_child     = isset( $max->max_child ) ? $max->max_child : 5;

$passenger_options = array( 0 => 0 );
for ( $i = 1; $i <= $max_passenger; $i++ ) {
	$passenger_options[ $i ] = $i;
}

$suitcases_options = array( 0 => 0 );
for ( $i = 1; $i <= $max_suitcase; $i++ ) {
	$suitcases_options[ $i ] = $i;
}

$chseats_options = array( 0 => 0 );
for ( $i = 1; $i <= $max_child; $i++ ) {
	$chseats_options[ $i ] = $i;
}

$has_chseats = ( $max_child > 0 ) ? true : false;
?>

<script type="text/javascript">
var tbxhr;
var itemID = '<?php echo (int) $item->id; ?>';
var page_mode = '<?php echo ( $item->id > 0 ) ? 'edit' : 'add'; ?>';
var car_type = '<?php echo ( $item->custom_car == '' ) ? 'system' : 'custom'; ?>';
var loader = '<img id="loading" src="<?php echo esc_url( TBLIGHT_PLUGIN_DIR_URL .'assets/images/ajax-loader.gif' );?>" alt="Loading.." />';
var booking_type = '<?php echo esc_attr( $item->booking_type ); ?>';
var country_id = '<?php echo esc_attr( $item->country_id ); ?>';
<?php if ( $elsettings->default_country != '' ) { ?>
var options = {
	componentRestrictions: {country: '<?php echo esc_attr( $this->default_country ); ?>'}
};
<?php } else { ?>
var options = {};
<?php } ?>
</script>
<?php wp_enqueue_script( 'orders-custom-edit', TBLIGHT_PLUGIN_DIR_URL . 'admin/js/orders_edit.js', array(), filemtime( TBLIGHT_PATH . '/admin/js/orders_edit.js' ), true ); ?>

<legend class="block-heading"><?php echo esc_attr( $heading ); ?></legend>
<div class="tblight-wrap">
	
	<form method="post" name="admin-form" id="admin-form" class="admin-form validate">
	
		<?php wp_nonce_field( 'create-order', 'tblight_create_order' ); ?>
		<input type="hidden" name="action" value="save" />
		<?php // echo "<pre>"; print_r($item); echo "</pre>"; ?>
		<div class="form-group clearfix form-required name_form_field">
			<label class="label"><?php esc_attr_e( 'Name', 'cab-fare-calculator' ); ?> <span class="star">*</span></label>
			<input type="text" name="names" id="names" class="form-control regular-text requried" aria-required="true" value="<?php echo esc_attr( $item->names ); ?>" />
		</div>
		<div class="form-group clearfix form-required email_form_field">
			<label class="label"><?php esc_attr_e( 'Email', 'cab-fare-calculator' ); ?> <span class="star">*</span></label>
			<input type="text" name="email" id="email" class="form-control regular-text requried" aria-required="true" value="<?php echo esc_attr( $item->email ); ?>" />
		</div>
		<div class="form-group clearfix phone_form_field">
			<label class="label"><?php esc_attr_e( 'Phone', 'cab-fare-calculator' ); ?></label>
			<div class="country_list">
			<?php echo html_entity_decode( esc_html( SelectList::getCallingCodeOptions( 'country_id', 'styler_list', $item->country_calling_code, 'backend' ) )); ?>
			</div>
			<input type="text" name="phone" id="phone" class="form-control mid-text" placeholder="Phone Number" aria-required="true" value="<?php echo esc_attr( $item->phone ); ?>" />
			<input type="hidden" name="country_calling_code" id="country_calling_code" value="">
		</div>
		<input type="hidden" name="booking_type" value="address" />
		<div class="form-group clearfix pickup_date_form_field">
			<label class="label"><?php esc_attr_e( 'Pick Up Date', 'cab-fare-calculator' ); ?></label>
			<div class="date_time_block">
				<div class="pickup_datepicker">
					<input type="text" name="pickup_date" id="pickup_date" class="form-control mid-text datepicker_input" aria-required="true" value="<?php echo esc_attr( $item->pickup_date ); ?>" onchange="reloadAvailableCars()" />
				</div>
				<div class="hr_min_wrap">
					<?php echo html_entity_decode( esc_html( SelectList::getSelectListHtml( 'pickup_hr', 'styler_list', $hr_options_arr, $item->pickup_hr ) )); ?>	
					<?php echo html_entity_decode( esc_html( SelectList::getSelectListHtml( 'pickup_min', 'styler_list', $min_options_arr, $item->pickup_min ) )); ?>
				</div>
			</div>
		</div>	
		<div class="form-group clearfix form-required pickup_address_form_field">
			<label class="label"><?php esc_attr_e( 'Pickup Address:', 'cab-fare-calculator' ); ?></label>
			<div class="pick_up_wrap">
				<input type="text" name="pickup_address" id="pickup_address" size="50" value="<?php echo esc_attr( $item->begin ); ?>" class="inputbox form-control regular-text required" />
				<input type="hidden" name="pickup_lat" id="pickup_lat" value="<?php echo esc_attr( $item->pickup_lat ); ?>" />
				<input type="hidden" name="pickup_lng" id="pickup_lng" value="<?php echo esc_attr( $item->pickup_lng ); ?>" />
			</div>
		</div>
		<div class="form-group clearfix form-required dropoff_address_form_field">
			<label class="label"><?php esc_attr_e( 'Drop off Address:', 'cab-fare-calculator' ); ?></label>
			<div class="drop_off_wrap">
				<input type="text" name="dropoff_address" id="dropoff_address" size="50" value="<?php echo esc_attr( $item->end ); ?>" class="inputbox form-control regular-text required" />
				<input type="hidden" name="dropoff_lat" id="dropoff_lat" value="<?php echo esc_attr( $item->dropoff_lat ); ?>" />
				<input type="hidden" name="dropoff_lng" id="dropoff_lng" value="<?php echo esc_attr( $item->dropoff_lng ); ?>" />
			</div>
		</div>		
		<div class="form-group clearfix form-required passengers_form_field">
			<label class="label"><?php esc_attr_e( 'Passengers', 'cab-fare-calculator' ); ?> <span class="star">*</span></label>
			<?php echo html_entity_decode( esc_html( SelectList::getSelectListHtml( 'selpassengers', 'styler_list seat_options required', $passenger_options, $item->selpassengers ))); ?>
		</div>
		<div class="form-group clearfix suitcases_form_field">
			<label class="label"><?php esc_attr_e( 'Suitcases', 'cab-fare-calculator' ); ?></label>
			<?php echo html_entity_decode( esc_html( SelectList::getSelectListHtml( 'selluggage', 'styler_list seat_options', $suitcases_options, $item->selluggage ) )); ?>
		</div>	
		<div class="form-group clearfix childseat_form_field">
			<label class="label"><?php esc_attr_e( 'Child Seats', 'cab-fare-calculator' ); ?></label>
			<?php echo html_entity_decode( esc_html( SelectList::getSelectListHtml( 'selchildseats', 'styler_list seat_options', $chseats_options, $item->selchildseats ) )); ?>
		</div>
		<div class="form-group clearfix car_form_field">
			<label class="label"><?php esc_attr_e( 'Select Car', 'cab-fare-calculator' ); ?></label>
			<div class="car_selection_wrapper">
				<input class="inputbox" type="text" name="custom_car" value="<?php echo esc_attr( $item->custom_car ); ?>" placeholder="<?php esc_attr_e( 'Type in Custom Car', 'cab-fare-calculator' ); ?>" />
				<span class="divider">OR</span>
				<a href="javascript:void(0);" class="get_cars button"><?php esc_attr_e( 'Get Available cars', 'cab-fare-calculator' ); ?></a>
			</div>
		</div>

		<div id="available_cars_loader" style="display:none;margin:20px;">
			<img src="<?php echo TBLIGHT_PLUGIN_DIR_URL; ?>assets/images/ajax-loader-bar.gif" alt="Loading.." />
		</div>
		<!-- shuttles will be listed by ajax -->
		<input type="hidden" name="car_id" id="selected_car_id" value="<?php echo esc_attr( $item->vehicletype ); ?>" />
		<div id="available_cars_wrap" class="no-more-tables_style2" style="margin: 20px 0;display:none;"></div>

		<div class="form-group clearfix price-form-group price_form_field">
			<label class="label"><?php esc_attr_e( 'Price', 'cab-fare-calculator' ); ?></label>
			<input type="text" name="price" id="price" class="form-control mid-text" aria-required="true" value="<?php echo esc_attr( $item->cprice ); ?>" />
			<span class="divider">OR</span>
			<input type="text" name="price_override" id="price_override" placeholder="Type in to override Price" class="form-control mid-text" aria-required="true" value="<?php echo esc_attr( $item->price_override ); ?>" />
		</div>
		<div class="form-group clearfix payment_form_field">
			<label class="label"><?php esc_attr_e( 'Payment Name', 'cab-fare-calculator' ); ?></label>
			<input type="text" name="custom_payment" id="custom_payment" class="form-control mid-text" aria-required="true" value="<?php echo esc_attr( $item->custom_payment ); ?>" />
		</div>
		<div class="form-group clearfix payment_note_form_field">
			<label class="label"><?php esc_attr_e( 'Payment notes', 'cab-fare-calculator' ); ?></label>
			<textarea name="payment_notes"><?php echo esc_textarea( $item->payment_notes ); ?></textarea>
		</div>
		<div class="form-group clearfix status_form_field">
			<label class="label"><?php esc_attr_e( 'Status', 'cab-fare-calculator' ); ?></label>
			<?php echo html_entity_decode( esc_html( SelectList::getDefaultOrderStatusOptions( 'state', 'styler_list state', $item->state ) )); ?>
		</div>					

		<input type="hidden" name="id" value="<?php echo esc_attr( $id ); ?>" />
		<input type="hidden" name="source" value="backend">
		<input type="hidden" name="flat_cost" value="">
		<input type="hidden" name="percentage_cost" value="">
		<input type="hidden" name="changed" id="changed" value="0">
		<input type="submit" name="submit" id="submit" class="button button-primary submit-order" value="<?php esc_attr_e( 'Save', 'cab-fare-calculator' ); ?>" />
		<a href="<?php echo admin_url( 'admin.php?page=orders' ); ?>" class="button" data-action="back"><?php esc_attr_e( 'Cancel', 'cab-fare-calculator' ); ?></a>
	</form>
	
</div>
