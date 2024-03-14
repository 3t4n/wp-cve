<?php

$elsettings = BookingHelper::config();

if ( isset( $_GET['success'] ) && $_GET['success'] == 1 ) {
	?>
<div class="notice notice-success is-dismissible">
	<p><?php esc_attr_e( 'Successfully saved!', 'cab-fare-calculator' ); ?></p>
</div>
	<?php
}

$icon_waiting   = TBLIGHT_PLUGIN_DIR_URL . 'admin/images/publish_x.png';
$icon_published = TBLIGHT_PLUGIN_DIR_URL . 'admin/images/icon-16-allow.png';
?>

<legend class="block-heading"><?php echo esc_attr( $heading ); ?></legend>

<div class="action-btn-wrapper">	
<a href="<?php echo admin_url( 'admin.php?page=cars' ); ?>" class="button" data-action="back"><?php esc_attr_e( 'Back', 'cab-fare-calculator' ); ?></a> 
<a href="<?php echo admin_url( 'admin.php?page=cars&action=edit&id=' . $id ); ?>" class="button" data-action="edit"><?php esc_attr_e( 'Edit', 'cab-fare-calculator' ); ?></a>
</div>
<div class="tblight-wrap">    
	<div class="inputWrap clearfix">
		<label>Title</label>
		<div class="field-value"><?php echo esc_attr( $item->title ); ?></div>
	</div>
	<div class="inputWrap clearfix">
		<label>Minimum Passengers</label>
		<div class="field-value"><?php echo esc_attr( $item->min_passenger_no ); ?></div>
	</div>
	<div class="inputWrap clearfix">
		<label>Maximum Passengers</label>
		<div class="field-value"><?php echo esc_attr( $item->passenger_no ); ?></div>
	</div>
	<div class="inputWrap clearfix">
		<label>Maximum suitcases</label>
		<div class="field-value"><?php echo esc_attr( $item->suitcase_no ); ?></div>
	</div>
	<div class="inputWrap clearfix">
		<label>Maximum Child Seat</label>
		<div class="field-value"><?php echo esc_attr( $item->child_seat_no ); ?></div>
	</div>
	<div class="inputWrap clearfix">
		<label>Infant child price</label>
		<div class="field-value"><?php echo esc_attr( $item->child_seat_price ); ?></div>
	</div>
	<div class="inputWrap clearfix">
		<label>Image</label>
		<div class="field-value"><img src="<?php echo esc_attr( $item->image ); ?>" width="150" height="150" alt="<?php echo esc_attr( $item->title ); ?>" /></div>
	</div>
	<div class="inputWrap clearfix">
		<label>Additional Car type charge</label>
		<div class="field-value"><?php echo esc_attr( $item->price ); ?></div>
	</div>
	<div class="inputWrap clearfix">
		<label>Minimum Distance</label>
		<div class="field-value"><?php echo esc_attr( $item->minmil ); ?></div>
	</div>
	<div class="inputWrap clearfix">
		<label>Minimum Distance Price</label>
		<div class="field-value"><?php echo esc_attr( $item->minprice ); ?></div>
	</div>
	<div class="inputWrap clearfix">
		<label>Price per <?php echo esc_attr( $elsettings->distance_unit ); ?></label>
		<div class="field-value"><?php echo esc_attr( $item->unit_price ); ?></div>
	</div>
	<div class="inputWrap clearfix">
		<label>Charge per min</label>
		<div class="field-value"><?php echo esc_attr( $item->charge_per_min ); ?></div>
	</div>
	<div class="inputWrap clearfix">
		<label>Blocked Dates</label>
		<?php
		if ( ! empty( $item->blocked_dates ) ) {
			$all_blocked_dates = json_decode( $item->blocked_dates );
			?>
		<div class="field-value">
			<ul>
			<?php foreach ( $all_blocked_dates as $value ) { ?>
			<li><?php echo esc_attr( $value ); ?></li>	
			<?php } ?>
			</ul>
		</div>
		<?php } else { ?>
		<div class="field-value"><img src="<?php echo esc_attr( $icon_waiting ); ?>" alt="Unpublished" /></div>
		<?php } ?>
	</div>
	<div class="inputWrap clearfix">
		<label>Description</label>
		<div class="field-value"><?php echo esc_textarea( $item->text ); ?></div>
	</div>
	<div class="inputWrap clearfix">
		<label>Track Availability</label>
		<div class="field-value">
			<?php if ( $item->track_availability ) { ?>
			<img src="<?php echo esc_attr( $icon_published ); ?>" alt="Published" />
			<?php } else { ?>
			<img src="<?php echo esc_attr( $icon_waiting ); ?>" alt="Unpublished" />
			<?php } ?>
		</div>
	</div>	
	<div class="days-availability-block clearfix" style='<?php echo ( $item->track_availability ) ? 'display:block;' : 'display:none;'; ?>'>
		<?php
		$weekdays          = array(
			0 => ( 'MONDAY' ),
			1 => ( 'TUESDAY' ),
			2 => ( 'WEDNESDAY' ),
			3 => ( 'THURSDAY' ),
			4 => ( 'FRIDAY' ),
			5 => ( 'SATURDAY' ),
			6 => ( 'SUNDAY' ),
		);
		$days_availability = json_decode( $item->days_availability );
		// echo "<pre>"; print_r($days_availability); echo "</pre>";
		?>
		<table class="adminlist" width="100%">
			<thead>
				<tr>
					<th class="one" style="text-align: left;">Weekday</th>
					<th class="two" style="text-align: left;">Availability</th>
				</tr>
			</thead>
			<tbody>
				
				<?php
				for ( $i = 0;$i < count( $weekdays );$i++ ) {
					$checked = ( isset( $days_availability[ $i ]->is_available ) && ( $days_availability[ $i ]->is_available == 1 ) ) ? 'Yes' : 'No';

					$opening_hrs  = $days_availability[ $i ]->opening_hrs;
					$opening_mins = $days_availability[ $i ]->opening_mins;
					$closing_hrs  = $days_availability[ $i ]->closing_hrs;
					$closing_mins = $days_availability[ $i ]->closing_mins;

					if ( isset( $days_availability[ $i ]->is_available ) && ( $days_availability[ $i ]->is_available == 1 ) ) {
						if ( $opening_hrs == -1 && $opening_mins == -1 && $closing_hrs == -1 && $closing_mins == -1 ) {
							$availability = 'Car available all the day';
						} else {
							$availability = $opening_hrs . ':' . $opening_mins . ' - ' . $closing_hrs . ':' . $closing_mins;
						}
					} else {
						$availability = 'No';
					}

					?>
				<tr>
				<td class="center"><?php echo esc_attr( $weekdays[ $i ] ); ?></td>
				<td class="center"><?php echo esc_attr( $availability ); ?></td>
				
				</tr>
				<?php } ?>
			</tbody>
		</table>
		
	</div>
</div>
