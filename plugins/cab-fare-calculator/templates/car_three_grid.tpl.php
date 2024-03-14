<div class="vehicles-item">
	<div class="vehicles-item-i">
		<div class="vehicles-item-box">
		<div class="vehicle-room">
			<h3 class="vehicle-title title-top"><?php echo esc_attr( $car->title ); ?></h3>
			<div class="vehicle-img">
				<?php echo html_entity_decode( esc_html( $image ) ); ?>
				<?php
				if ( $car->text != '' ) {
					echo '<br/><a href="javascript:void(0);" class="car_desc" title="' . strip_tags( $car->text ) . '">Vehicle Description</a>';
				}
				?>
			</div>
			
			<div class="vehicles-specs clearfix">
				<div class="col-md-6 col-sm-6 col-xs-6 passenger-number">
					<span class="input-group-addon"><span class="icon-user-full"><i class="tb tb-male"></i></span><span class="sr-only">Passengers</span></span>
					<span class="input-group-addon"><?php echo esc_attr( $car->passenger_no ); ?></span>
				</div>
				<div class="col-md-6 col-sm-6 col-xs-6 suitcase-number">
					<span class="input-group-addon"><span class="icon-briefcase-case"><i class="tb tb-briefcase"></i></span><span class="sr-only">Bags</span></span>
					<span class="input-group-addon"><?php echo esc_attr( $car->suitcase_no ); ?></span>
				</div>
			</div>
		</div>

		<div class="vehicle-price-outer">
			<div class="vehicle-price">
				<div class="booking-price">
					<?php echo esc_attr( BookingHelper::price_display( $car_price, $elsettings ) ); ?>
				</div>
				
				<?php
				if ( $is_admin && $elsettings->debug_price_calculation == 1 ) {
					$price_debug_html  = '';
					$price_debug_html .= '<a href="javascript:void(0);" class="rate_details" data-title="Price calculation details" data-target-selector="price_calculation_details_panel_' . $car->id . '">Price calculation details</a>';

					$price_debug_html .= '<div class="price_calculation_details_panel price_calculation_details_panel_' . $car->id . '" style="display:none;">Below price calculation is visible only to logged in Admins, customers will only see the Total price above.<br/>';
					foreach ( $debug_array as $debug_line_key => $debug_item ) {
						if ( isset( $debug_cars_array[ $car->id ][ $debug_line_key ] ) ) {
							$price_debug_html .= '>> ' . $debug_cars_array[ $car->id ][ $debug_line_key ]['title'] . ': ' . $debug_cars_array[ $car->id ][ $debug_line_key ]['charge_string'] . '<br/>';
							unset( $debug_cars_array[ $car->id ][ $debug_line_key ] );
						} else {
							if ( $debug_item['total_unit'] == 'none' ) {
								$price_debug_html .= '>> ' . $debug_item['title'] . ': ' . BookingHelper::price_display( $debug_item['charge_per_unit'], $elsettings ) . '<br/>';
							} elseif ( $debug_item['total_unit'] == 'string' ) {
								$price_debug_html .= '>> ' . $debug_item['title'] . ': ' . $debug_item['charge_string'] . '<br/>';
							} else {
								$price_debug_html .= '>> ' . $debug_item['title'] . ': ' . $debug_item['total_unit'] . ' X ' . BookingHelper::price_display( $debug_item['charge_per_unit'], $elsettings ) . '<br/>';
							}
						}
					}
					foreach ( $debug_cars_array[ $car->id ] as $debug_item ) {
						$price_debug_html .= '>> ' . $debug_item['title'] . ': ' . $debug_item['charge_string'] . '<br/>';
					}

					$price_debug_html .= '>> Total Price Round Up: ';
					if ( $elsettings->roundup_price == 'nearest5' ) {
						$price_debug_html .= 'Nearest 5 upwards';
					} elseif ( $elsettings->roundup_price == 'whole' ) {
						$price_debug_html .= 'Whole Number';
					} else {
						$price_debug_html .= 'No';
					}
					$price_debug_html .= '</div>';

					echo html_entity_decode( esc_html( $price_debug_html ) );
				}
				?>
				
				<a class="btn btn-lg btn-block btn-primary car_booking button-color" data-carid="<?php echo esc_attr( $car->id ); ?>" href="javascript:void(0);">
					Book now <span class="icon"><i class="tb tb-chevron-right"></i></span>
				</a>
			</div>
		</div>
		</div>
	</div>
</div>
