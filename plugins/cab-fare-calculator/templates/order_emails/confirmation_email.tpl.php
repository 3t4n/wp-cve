<html>
<head>
<title><?php esc_attr_e( 'Confirmation Email', 'cab-fare-calculator' ); ?></title>
</head>
<body>
	
<table border="0">
	<?php
	if ( $elsettings->header_logo != '' ) {
		?>
		<tr>
		<td align="right">
			<img src="<?php echo esc_attr( $elsettings->header_logo ); ?>" alt="header_logo" border="0" />
		</td>
	</tr>
		<tr><td>&nbsp;</td></tr>
	<?php } ?>
	
	<?php
	if ( $header_info != '' ) {
		?>
		<tr>
		<td align="left"><?php echo html_entity_decode( esc_html( $header_info ) ); ?></td>
	</tr>
		<tr><td>&nbsp;</td></tr>
		<tr><td><hr></td></tr>
		<?php } ?>
	
	<tr>
				<td>
			<table border="0" width="100%">
								<tr width="100%">
										<td width="50%">
						<?php esc_attr_e( 'Reference No', 'cab-fare-calculator' ); ?>:&nbsp;&nbsp;&nbsp;<?php echo esc_attr( $row_queue->order_number ); ?>
					</td>
										<td width="50%">
												<?php
												// ORDER ID 1234 STATUS: Accepted
												echo sprintf( 'ORDER ID %s STATUS: %s', $row_queue->id, $row_queue->order_status );
												?>
										</td>
								</tr>
								
				<tr><td colspan="2"><hr></td></tr>
								
				<tr width="100%">
										<td width="50%" style="vertical-align:top;">
						<span style="text-decoration:underline;">
							<?php esc_attr_e( 'Traveller information', 'cab-fare-calculator' ); ?>
						</span>
						
						<table border="0" width="100%">
							<tr width="100%">
								<td width="30%"><?php esc_attr_e( 'Passenger', 'cab-fare-calculator' ); ?>:</td>
								<td width="70%"><strong><?php echo esc_attr( $row_queue->names ); ?></strong></td>
							</tr>
							<tr width="100%">
								<td width="30%"><?php esc_attr_e( 'Email', 'cab-fare-calculator' ); ?>:</td>
								<td width="70%"><?php echo esc_attr( $row_queue->email ); ?></td>
							</tr>
							<tr width="100%">
								<td width="30%"><?php esc_attr_e( 'Phone', 'cab-fare-calculator' ); ?>:</td>
								<td width="70%">
								<?php
								// UPDATE July 28.2016 - If phone prefix country code is blank in Order row, this means this order was submitted from Old design
								// In that case, we will not show country prefix, just simply print the Order phone number
								$country_code_display = '';
								if ( $row_queue->country_calling_code != '' ) {
									$country_code_display = '+' . $row_queue->country_calling_code . ' ';
								}
								echo esc_attr( $country_code_display . $row_queue->phone );
								?>
								</td>
							</tr>
						</table>
										</td>
					
										<td width="50%" style="vertical-align:top;">
						<span style="text-decoration:underline;">
							<?php esc_attr_e( 'Carrier Details', 'cab-fare-calculator' ); ?>
						</span>
						
						<table border="0" width="100%">
						
						<?php
						if ( $row_queue->selpassengers > 0 ) {
							?>
							<tr width="100%">
								<td width="45%"><?php esc_attr_e( 'PASSENGER_SEATS', 'cab-fare-calculator' ); ?>:</td>
								<td width="55%"><?php echo esc_attr( $row_queue->selpassengers ); ?></td>
							</tr>
						<?php } ?>
						
						<?php
						if ( $row_queue->selchildseats > 0 ) {
							$price_to_added = $row_queue->selchildseats * (float) $carObj->child_seat_price;
							$price_to_added = ( $price_to_added > 0 ) ? ' (' . BookingHelper::price_display( $price_to_added, $elsettings ) . ')' : '';
							?>
							<tr width="100%">
								<td width="45%"><?php esc_attr_e( 'CHILD_SEATS', 'cab-fare-calculator' ); ?>:</td>
								<td width="55%"><?php echo esc_attr( $row_queue->selchildseats . $price_to_added ); ?></td>
							</tr>
						<?php } ?>
						
						<?php
						if ( $row_queue->selluggage > 0 ) {
							?>
							<tr width="100%">
								<td width="45%"><?php esc_attr_e( 'Check-in Luggage', 'cab-fare-calculator' ); ?>:</td>
								<td width="55%"><?php echo esc_attr( $row_queue->selluggage ); ?></td>
							</tr>
						<?php } ?>
						
						<?php
						$vehicle_style = '';
						if ( isset( $old_row_queue ) && $old_row_queue->vehicletype != $row_queue->vehicletype ) {
							$vehicle_style = ' style="color:red;"';
						}
						?>
							<tr width="100%">
								<td width="45%"><?php esc_attr_e( 'Vehicle type', 'cab-fare-calculator' ); ?>:</td>
								<td width="55%"<?php echo $vehicle_style; ?>><?php echo BookingHelper::get_order_car( $row_queue ); ?></td>
							</tr>
						</table>
										</td>
								</tr>
				
								<tr><td colspan="2"><hr></td></tr>
								
				<tr width="100%">
										<td width="50%" style="vertical-align:top;">
						<span style="text-decoration:underline;">
							<?php esc_attr_e( 'Pick-up Information', 'cab-fare-calculator' ); ?>
						</span>
						
						<table border="0" width="100%">
							
							<?php
							if ( $row_queue->begin != '' ) {
								if ( isset( $receiver ) && ( $receiver == 'driver' || $receiver == 'admins' ) ) {
									$lat    = $row_queue->pickup_lat;
									$lng    = $row_queue->pickup_lng;
									$pickup = '<a href="http://maps.google.com/maps?q=' . urlencode( "$lat,$lng" ) . '">' . esc_attr( $row_queue->begin ) . '</a>';
								} else {
									$pickup = esc_attr( $row_queue->begin );
								}
								?>
							<tr width="100%">
								<td width="30%"><?php esc_attr_e( 'FROM', 'cab-fare-calculator' ); ?>:</td>
								<td width="70%"><?php echo $pickup; ?></td>
							</tr>
							<?php } ?>
							
							<tr width="100%">
								<td width="30%"><?php esc_attr_e( 'Date', 'cab-fare-calculator' ); ?>:</td>
								<td width="70%"><?php echo BookingHelper::get_order_pickup_date( $row_queue, $elsettings ); ?></td>
							</tr>
						</table>
										</td>

										<td width="50%" style="vertical-align:top;">
						<span style="text-decoration:underline;">
							<?php esc_attr_e( 'Drop-off Information', 'cab-fare-calculator' ); ?>
						</span>
						<table border="0" width="100%">
							
							<?php
							if ( $row_queue->end != '' ) {
								if ( isset( $receiver ) && ( $receiver == 'driver' || $receiver == 'admins' ) ) {
									$lat     = $row_queue->dropoff_lat;
									$lng     = $row_queue->dropoff_lng;
									$dropoff = '<a href="http://maps.google.com/maps?q=' . urlencode( "$lat,$lng" ) . '">' . esc_attr( $row_queue->end ) . '</a>';
								} else {
									$dropoff = esc_attr( $row_queue->end );
								}
								?>
							<tr width="100%">
								<td width="30%"><?php esc_attr_e( 'TO', 'cab-fare-calculator' ); ?>:</td>
								<td width="70%"><?php echo $dropoff; ?></td>
							</tr>
							<?php } ?>
						</table>
										</td>

								</tr>
						</table>
		</td>
	</tr>
		
	<tr><td><hr></td></tr>
		
		<tr>
		<td>
			<table width="70%">
				<?php if ( $elsettings->show_price == 1 ) { ?>
				<tr>
					<td width="35%"><?php esc_attr_e( 'Sub Total', 'cab-fare-calculator' ); ?>:</td>
					<td width="35%"><strong><?php echo BookingHelper::price_display( esc_attr( $row_queue->sub_total ), $elsettings ); ?></strong></td>
				</tr>
				
					<?php if ( $row_queue->flat_cost > 0 ) { ?>
				<tr>
					<td width="35%"><?php esc_attr_e( 'Flat Cost', 'cab-fare-calculator' ); ?>:</td>
					<td width="35%"><strong><?php echo BookingHelper::price_display( esc_attr( $row_queue->flat_cost ), $elsettings ); ?></strong></td>
				</tr>
				<?php } ?>
			
					<?php if ( $row_queue->percentage_cost > 0 ) { ?>
				<tr>
					<td width="35%"><?php esc_attr_e( 'Tax', 'cab-fare-calculator' ); ?>:</td>
					<td width="35%"><strong><?php echo BookingHelper::price_display( esc_attr( $row_queue->percentage_cost ), $elsettings ); ?></strong></td>
				</tr>
				<?php } ?>
				
				<tr>
					<td width="35%"><?php esc_attr_e( 'Grand Total', 'cab-fare-calculator' ); ?>:</td>
					<td width="35%"><strong><?php echo BookingHelper::price_display( esc_attr( $row_queue->cprice ), $elsettings ) . $converted_currency_label; ?></strong></td>
				</tr>
				<?php } ?>
				
				<?php if ( $row_queue->custom_payment != '' ) { ?>
				<tr>
					<td width="35%"><?php esc_attr_e( 'Payment Method', 'cab-fare-calculator' ); ?>:</td>
					<td width="35%"><strong><?php echo BookingHelper::get_order_payment( $row_queue ); ?></strong></td>
				</tr>
				<?php } ?>
				
				<?php echo html_entity_decode( esc_html( $payment_data ) ); ?>
			</table>
		</td>
	</tr>
	
	<tr><td><hr></td></tr>

	<tr>
		<td>
			<center><b><?php esc_attr_e( 'Thank you and have a pleasant journey!', 'cab-fare-calculator' ); ?></b></center>
		</td>
	</tr>
	<tr>
		<td>
			<center>
				<span style="color:blue"><?php esc_attr_e( 'Orders are subject to our terms & conditions. We welcome all comments on the services we provide.', 'cab-fare-calculator' ); ?></span>
			</center>
		</td>
	</tr>
	<tr><td><hr></td></tr>
	
	<?php
	if ( $contact_info != '' ) {
		?>
	<tr>
		<td>
			<center><?php esc_attr_e( 'Contact us', 'cab-fare-calculator' ) . ':' . html_entity_decode( esc_html( $contact_info ) ); ?></center>
		</td>
	</tr>
	<?php } ?>

</table>
</body>
</html>
