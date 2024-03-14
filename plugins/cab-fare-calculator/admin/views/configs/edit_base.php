<?php require_once TBLIGHT_PLUGIN_PATH . 'fields/select.list.php'; ?>

<?php wp_enqueue_style( 'tblight-admin-config-base-gmap', TBLIGHT_URL . '/admin/css/gmap_style.css', array(), filemtime( TBLIGHT_PATH . '/admin/css/gmap_style.css' ) ); ?>

<?php
$default_operation_area_vertices = '[[51.629949539313905,-0.5104715976563057],[51.36834810929568,-0.3786356601563057],[51.447157400846535,0.2695576992186943],[51.83916879427223,0.3189961757811943]]';
?>

<script type="text/javascript">
var map,areaOfOperation,geocoder;
var selectedAreaVerticesStr = '<?php echo esc_attr( $data->operation_area_vertices ); ?>';
var triangleCoords = new Array();
var selectedAreaVerticesArr = JSON.parse(selectedAreaVerticesStr);

var defaultAreaVerticesStr = '<?php echo esc_attr( $default_operation_area_vertices ); ?>';
var defaultAreaVerticesArr = JSON.parse(defaultAreaVerticesStr);

var base_lat = '<?php echo esc_attr( $data->base_lat ); ?>';
var base_lng = '<?php echo esc_attr( $data->base_long ); ?>';
</script>

<?php wp_enqueue_script( 'tblight-admin-config-base', TBLIGHT_URL . '/admin/js/base_config.js', array(), filemtime( TBLIGHT_PATH . '/admin/js/base_config.js' ), true ); ?>

<legend class="block-heading"><?php echo esc_attr( $heading ); ?></legend>
<div class="tblight-wrap">
	
	<form method="post" name="admin-form" id="admin-form" class="admin-form validate">
	
		<?php wp_nonce_field( 'create-base-config', 'tblight_create_base_config' ); ?>
		<input type="hidden" name="action" value="save" />
		<?php // echo "<pre>"; print_r($item); echo "</pre>"; ?>
		<input type="hidden" name="title" id="title" value="Base Settings" />
		
		<div class="form-group clearfix defaultcountry_form_field">
			<label class="label">Default Country</label>
			<div>
				<?php echo html_entity_decode( esc_html( SelectList::getCountryOptions( 'configdata[default_country]', 'default_country', $data->default_country ) ) ); ?>
			</div>			
		</div>
		<div class="form-group clearfix defaultcity_form_field">
			<label class="label">Default City</label>
			<input type="text" name="configdata[default_city]" id="default_city" class="form-control mid-text" value="<?php echo esc_attr( $data->default_city ); ?>" />
		</div>
		<input type="hidden" name="configdata[company_location_lat]" id="company_location_lat" value="<?php echo esc_attr( $data->company_location_lat ); ?>" />
		<input type="hidden" name="configdata[company_location_lng]" id="company_location_lng" value="<?php echo esc_attr( $data->company_location_lng ); ?>" />
		<div class="form-group clearfix notificationemail_form_field">
			<label class="label">Booking notification email</label>
			<input type="text" name="configdata[booking_notification_email]" id="booking_notification_email" class="form-control mid-text" value="<?php echo esc_attr( $data->booking_notification_email ); ?>" />
		</div>
		<div class="inputwrap clearfix" style="position:relative;">
			<label>Area of Operation</label>
			<input type="hidden" name="configdata[operation_area_vertices]" id="operation_area_vertices" value="<?php echo esc_attr( $data->operation_area_vertices ); ?>" />
			<div id="panel">
				<input onclick="resetArea();" type=button value="Reset Area" />
			</div>
			
			<div class="map-canvas-wrapper">
				<div id="map-canvas"></div>
			</div>
		</div>

		<div class="form-group clearfix pickarea_form_field">
			<label class="label">Pickup in Area</label>
			<fieldset id="pickup_in_area" class="btn-group btn-group-yesno radio">
				<input type="radio" id="pickup_in_area1" name="configdata[pickup_in_area]" value="1" <?php echo ( $data->pickup_in_area ) ? 'checked="checked"' : ''; ?> />
				<label for="pickup_in_area1" class="btn <?php echo ( $data->pickup_in_area ) ? 'active' : ''; ?>">Yes</label>
				<input type="radio" id="pickup_in_area0" name="configdata[pickup_in_area]" value="0" <?php echo ( $data->pickup_in_area ) ? '' : 'checked="checked"'; ?> />
				<label for="pickup_in_area0" class="btn <?php echo ( $data->pickup_in_area ) ? '' : 'active'; ?>">No</label>
			</fieldset>
		</div>	
		<div class="form-group clearfix dropoff_form_field">
			<label class="label">Drop off in Area</label>
			<fieldset id="dropoff_in_area" class="btn-group btn-group-yesno radio">
				<input type="radio" id="dropoff_in_area1" name="configdata[dropoff_in_area]" value="1" <?php echo ( $data->dropoff_in_area ) ? 'checked="checked"' : ''; ?> />
				<label for="dropoff_in_area1" class="btn <?php echo ( $data->dropoff_in_area ) ? 'active' : ''; ?>">Yes</label>
				<input type="radio" id="dropoff_in_area0" name="configdata[dropoff_in_area]" value="0" <?php echo ( $data->dropoff_in_area ) ? '' : 'checked="checked"'; ?> />
				<label for="dropoff_in_area0" class="btn <?php echo ( $data->dropoff_in_area ) ? '' : 'active'; ?>">No</label>
			</fieldset>
		</div>
		<div class="form-group clearfix bothpickanddropoff_form_field">
			<label class="label">Both pick up and drop off in Area</label>
			<fieldset id="pickup_dropoff_in_area" class="btn-group btn-group-yesno radio">
				<input type="radio" id="pickup_dropoff_in_area1" name="configdata[pickup_dropoff_in_area]" value="1" <?php echo ( $data->pickup_dropoff_in_area ) ? 'checked="checked"' : ''; ?> />
				<label for="pickup_dropoff_in_area1" class="btn <?php echo ( $data->pickup_dropoff_in_area ) ? 'active' : ''; ?>">Yes</label>
				<input type="radio" id="pickup_dropoff_in_area0" name="configdata[pickup_dropoff_in_area]" value="0" <?php echo ( $data->pickup_dropoff_in_area ) ? '' : 'checked="checked"'; ?> />
				<label for="pickup_dropoff_in_area0" class="btn <?php echo ( $data->pickup_dropoff_in_area ) ? '' : 'active'; ?>">No</label>
			</fieldset>
		</div> 
		<div class="base-coords-wrap latitide_form_field">
		<div class="form-group clearfix">
			<label class="label">Base Latitude</label>
			<input type="text" name="configdata[base_lat]" id="base_lat" class="form-control mid-text" value="<?php echo esc_attr( $data->base_lat ); ?>" />
		</div>
		<a href="javascript:void(0);" class="get_base_coords">Get Coords</a>
		</div>
		<div class="form-group clearfix longitude_form_field">
			<label class="label">Base Longitude</label>
			<input type="text" name="configdata[base_long]" id="base_long" class="form-control mid-text" value="<?php echo esc_attr( $data->base_long ); ?>" />
		</div>

		<div class="form-group clearfix pick_up_base_calculation_key">
			<label class="label">Base to Pick up calculation?</label>
			<fieldset id="calculate_base_pickup" class="btn-group btn-group-yesno radio">
				<input type="radio" id="calculate_base_pickup1" name="configdata[calculate_base_pickup]" value="1" <?php echo ( $data->calculate_base_pickup ) ? 'checked="checked"' : ''; ?> />
				<label for="calculate_base_pickup1" class="btn <?php echo ( $data->calculate_base_pickup ) ? 'active' : ''; ?>">Yes</label>
				<input type="radio" id="calculate_base_pickup0" name="configdata[calculate_base_pickup]" value="0" <?php echo ( $data->calculate_base_pickup ) ? '' : 'checked="checked"'; ?> />
				<label for="calculate_base_pickup0" class="btn <?php echo ( $data->calculate_base_pickup ) ? '' : 'active'; ?>">No</label>
			</fieldset>
		</div>
		<div class="form-group clearfix pick_up_base_calculation_option" style="<?php echo ( $data->calculate_base_pickup ) ? 'display:block;' : 'display:none;'; ?>">
			<label class="label">Base to Pick up charge</label>
			<fieldset id="base_pickup_price_type" class="btn-group btn-group-yesno radio">
				<input type="radio" id="base_pickup_price_type1" name="configdata[base_pickup_price_type]" value="flat" <?php echo ( $data->base_pickup_price_type == 'flat' ) ? 'checked="checked"' : ''; ?> />
				<label for="base_pickup_price_type1" class="btn <?php echo ( $data->base_pickup_price_type == 'flat' ) ? 'active' : ''; ?>">Flat rate</label>
				<input type="radio" id="base_pickup_price_type0" name="configdata[base_pickup_price_type]" value="distance" <?php echo ( $data->base_pickup_price_type == 'distance' ) ? ' checked="checked"' : ''; ?> />
				<label for="base_pickup_price_type0" class="btn <?php echo ( $data->base_pickup_price_type == 'distance' ) ? 'active' : ''; ?>">Per unit distance</label>
			</fieldset>
		</div>
		<div class="form-group clearfix pick_up_base_calculation_option" style="<?php echo ( $data->calculate_base_pickup ) ? 'display:block;' : 'display:none;'; ?>">
			<label class="label">Value</label>
			<input type="text" name="configdata[base_pickup_price]" id="base_pickup_price" class="form-control mid-text" value="<?php echo esc_attr( $data->base_pickup_price ); ?>" />
		</div>
		<div class="form-group clearfix pick_up_base_calculation_option" style="<?php echo ( $data->calculate_base_pickup ) ? 'display:block;' : 'display:none;'; ?>">
			<label class="label">Charge if Base to Pick up over miles/kilomerters</label>
			<input type="text" name="configdata[milage_charging_base_pickup]" id="milage_charging_base_pickup" class="form-control mid-text" value="<?php echo esc_attr( $data->milage_charging_base_pickup ); ?>" />
		</div>

		<div class="form-group clearfix drop_off_base_calculation_key">
			<label class="label">Drop off to Base calculation?</label>
			<fieldset id="calculate_dropoff_base" class="btn-group btn-group-yesno radio">
				<input type="radio" id="calculate_dropoff_base1" name="configdata[calculate_dropoff_base]" value="1" <?php echo ( $data->calculate_dropoff_base ) ? 'checked="checked"' : ''; ?> />
				<label for="calculate_dropoff_base1" class="btn <?php echo ( $data->calculate_dropoff_base ) ? 'active' : ''; ?>">Yes</label>
				<input type="radio" id="calculate_dropoff_base0" name="configdata[calculate_dropoff_base]" value="0" <?php echo ( $data->calculate_dropoff_base ) ? '' : 'checked="checked"'; ?> />
				<label for="calculate_dropoff_base0" class="btn <?php echo ( $data->calculate_dropoff_base ) ? '' : 'active'; ?>">No</label>
			</fieldset>
		</div>
		<div class="form-group clearfix drop_off_base_calculation_option" style="<?php echo ( $data->calculate_dropoff_base ) ? 'display:block;' : 'display:none;'; ?>">
			<label class="label">Drop off to Base charge</label>
			<fieldset id="dropoff_base_price_type" class="btn-group btn-group-yesno radio">
				<input type="radio" id="dropoff_base_price_type1" name="configdata[dropoff_base_price_type]" value="flat" <?php echo ( $data->dropoff_base_price_type == 'flat' ) ? 'checked="checked"' : ''; ?> />
				<label for="dropoff_base_price_type1" class="btn <?php echo ( $data->dropoff_base_price_type == 'flat' ) ? 'active' : ''; ?>">Flat rate</label>
				<input type="radio" id="dropoff_base_price_type0" name="configdata[dropoff_base_price_type]" value="distance" <?php echo ( $data->dropoff_base_price_type == 'distance' ) ? 'checked="checked"' : ''; ?> />
				<label for="dropoff_base_price_type0" class="btn <?php echo ( $data->dropoff_base_price_type == 'distance' ) ? 'active' : ''; ?>">Per unit distance</label>
			</fieldset>
		</div>
		<div class="form-group clearfix drop_off_base_calculation_option" style="<?php echo ( $data->calculate_dropoff_base ) ? 'display:block;' : 'display:none;'; ?>">
			<label class="label">Value</label>
			<input type="text" name="configdata[dropoff_base_price]" id="dropoff_base_price" class="form-control mid-text" value="<?php echo esc_attr( $data->dropoff_base_price ); ?>" />
		</div>
		<div class="form-group clearfix drop_off_base_calculation_option" style="<?php echo ( $data->calculate_dropoff_base ) ? 'display:block;' : 'display:none;'; ?>">
			<label class="label">Charge if Drop off to Base over miles/kilomerters</label>
			<input type="text" name="configdata[milage_charging_dropoff_base]" id="milage_charging_dropoff_base" class="form-control mid-text" value="<?php echo esc_attr( $data->milage_charging_dropoff_base ); ?>" />
		</div>
		<div class="form-group clearfix timeaftereachbooking_form_field">
			<label class="label">Add time after each booking (minutes)</label>
			<input type="text" name="configdata[time_after_each_booking]" id="time_after_each_booking" class="form-control mid-text" value="<?php echo esc_attr( $data->time_after_each_booking ); ?>" />
		</div>
		
		<input type="hidden" name="id" value="<?php echo esc_attr( $id ); ?>" />
		<input type="submit" name="submit" id="submit" class="button button-primary submit-base-config" value="<?php esc_attr_e( 'Save', 'cab-fare-calculator' ); ?>" />
		<a href="<?php echo admin_url( 'admin.php?page=configs' ); ?>" class="button" data-action="back"><?php esc_attr_e( 'Cancel', 'cab-fare-calculator' ); ?></a>
	</form>
	
</div>
