<div class="flexmls-listings-list-wrapper flexmls-widthchange-wrapper">
	<?php if ( ! empty( $this->search_data ) ) : ?>
		<?php
			$mls_fields_to_suppress = [];
			global $wp;
			$current_url = home_url( add_query_arg( $_GET, $wp->request ) );

			foreach ($this->search_data as $record) :
				// Establish some variables
				$listing_address = flexmlsConnect::format_listing_street_address($record);
				$first_line_address = htmlspecialchars($listing_address[0]);
				$second_line_address = htmlspecialchars($listing_address[1]);
				$one_line_address = htmlspecialchars($listing_address[2]);
				$one_line_without_zip_address = flexmlsSearchUtil::one_line_without_zip_address( $record );
				$link_to_details_criteria = $this->search_criteria;


				$sf =& $record['StandardFields'];

				if ( empty( $mls_fields_to_suppress ) ) {
					$mls_fields_to_suppress = flexmlsSearchUtil::mls_fields_to_suppress( $sf );
				}

				$link_to_details_criteria['m'] = $sf['MlsId'];

				$link_to_details = flexmlsConnect::make_nice_address_url($record, $link_to_details_criteria);

				$link_to_details = add_query_arg( 'search_referral_url', urlencode( $current_url ), $link_to_details );
				$rand = mt_rand();

		?>
			<a href="<?php echo esc_url( $link_to_details ); ?>" class="flexmls-listing">
				<?php $main_photo = fmcSearchResults::main_photo_from_collection( $sf['Photos'] ); ?>
				<div class="flexmls-image-wrapper" style="background-image: url(<?php echo esc_url( $main_photo['Uri640'] ); ?>);">
					<?php if ( strtotime( $sf['OnMarketDate'] ) > strtotime( '-7 days' ) ) : ?>
						<span class="new-listing-tag">New Listing</span>
					<?php endif; ?>
					<?php if ( $sf['OpenHousesCount']  > 0 ) : ?>
						<span class="new-listing-tag open-house">Open House</span>
					<?php endif; ?>
					<?php

							if ( flexmlsConnect::is_not_blank_or_restricted($sf['ListPrice']) && !flexmlsConnect::is_not_blank_or_restricted($sf['ListPriceLow']) && !flexmlsConnect::is_not_blank_or_restricted($sf['ListPriceHigh']) ){
							            $list_price = '$' . flexmlsConnect::gentle_price_rounding($sf['ListPrice']);
						          } 
					        elseif ( flexmlsConnect::is_not_blank_or_restricted( $sf['ClosePrice']) && $sf['MlsStatus'] == 'Closed' ){
					            $list_price = '$'. esc_html( flexmlsConnect::gentle_price_rounding($sf['ClosePrice']) );
					          }
					        elseif ( flexmlsConnect::is_not_blank_or_restricted($sf['ListPriceLow']) && flexmlsConnect::is_not_blank_or_restricted($sf['ListPriceHigh']) ){
					            $list_price = '$'. flexmlsConnect::gentle_price_rounding($sf['ListPriceLow'] );
					            $list_price .= '-';
					            $list_price .= '$'. flexmlsConnect::gentle_price_rounding($sf['ListPriceHigh']);
					          } 
					        else {
					            $list_price = "";
					          }

					?>
					<span class="flexmls-price"><?php echo $list_price; ?></span>
					<div class="flexmls-portal-links">
						<?php fmcAccount::write_carts( $record ); ?>
					</div>
				</div>
				<div class="flexmls-content-wrapper">
					<div class="flexmls-address">
						<?php echo esc_html( $one_line_without_zip_address ); ?>
					</div>
					<div class="flexmls-quick-details">
						<?php if ( ! in_array( "MlsStatus", $mls_fields_to_suppress ) ) : ?>
							<span class="flexmls-status flexmls-status-<?php echo sanitize_title( $sf['MlsStatus'] ); ?>"><?php echo esc_html( $sf['MlsStatus'] ); ?></span>
						<?php endif; ?>
						<?php
							$is_beds_present = flexmlsConnect::is_not_blank_or_restricted( $sf['BedsTotal'] );
							$is_baths_present = flexmlsConnect::is_not_blank_or_restricted( $sf['BathsTotal'] );
							$is_sqft_present = flexmlsConnect::is_not_blank_or_restricted( $sf['BuildingAreaTotal'] ) || flexmlsConnect::is_not_blank_or_restricted( $sf['LivingArea'] );
						?>
						<?php if ( $is_beds_present || $is_baths_present || $is_sqft_present ) : ?>
							<div class="flexmls-details">
								<?php if ( $is_beds_present ) : ?>
									<span class="flexmls-detail"><?php echo esc_html( $sf['BedsTotal'] ); ?>BD</span>
								<?php endif; ?>

								<?php if ( $is_baths_present ) : ?>
									<span class="flexmls-detail"><?php echo esc_html( $sf['BathsTotal'] ); ?>BA</span>
								<?php endif; ?>

								<?php if ( $is_sqft_present ) : ?>
								<?php	$sf_sqft = ( flexmlsConnect::is_not_blank_or_restricted( $sf['BuildingAreaTotal'] ) ) ? $sf['BuildingAreaTotal'] : $sf['LivingArea']; ?>
									<span class="flexmls-detail"><?php echo esc_html( number_format($sf_sqft) ); ?>SF</span>
								<?php endif; ?>
							</div>
						<?php endif; ?>
					</div>
					<div class="flexmls-last-modified-and-idx-wrapper">
						<?php if ( flexmlsConnect::is_not_blank_or_restricted( $sf['ModificationTimestamp'] ) ) : ?>
							<div class="flexmls-last-modified-and-label-wrapper">
								<span class="flexmls-bold-label">Last Modified:</span> <?php echo esc_html( flexmlsConnect::format_date( "g:ia, F j, Y", $sf["ModificationTimestamp"] ) ); ?>
							</div>
						<?php endif; ?>
						<?php fmcSearchResults::compliance_label( $record ); ?>
					</div>

					<?php if ( flexmlsConnect::mls_requires_office_name_in_search_results() ) : ?>
						<?php $listing_office_label = ($sf['StateOrProvince'] == 'NY') ? 'Listing Courtesy of' : 'Listing Office:'; ?>
						<span class="flexmls-office-name">
							<span class="flexmls-bold-label"><?php echo esc_html( $listing_office_label ) ; ?></span>
							<?php echo esc_html( $sf["ListOfficeName"] ); ?>
						</span>
					<?php endif; ?>
				</div>
			</a>

		<?php endforeach; ?>
	<?php endif; ?>
</div>
