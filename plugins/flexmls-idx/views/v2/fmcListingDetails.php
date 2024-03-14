<?php flexmlsPortalPopup::popup_portal('detail_page'); ?>
<div class="flexmls-listing-details flexmls-v2-widget flexmls-widthchange-wrapper flexmls-body-font">
	<?php $has_search_return = ! empty( $_GET['search_referral_url'] ); ?>
	<div class="flexmls-actions-wrapper listing-section <?php echo $has_search_return ? 'has-return-button' : ''; ?>">
		<?php if ( $has_search_return ) : ?>
			<?php $search_referral_url = $_GET['search_referral_url']; ?>
			<?php $back_button_link = wp_validate_redirect( $search_referral_url, flexmlsConnect::get_destination_link() );
				?>
		<?php 
		$back_to_search_link = '';
		if ( strpos($search_referral_url, "'") ) {
			$back_to_search_link = stripslashes($search_referral_url);
		} else {
			$back_to_search_link = wp_validate_redirect( $search_referral_url, flexmlsConnect::get_destination_link() );
		}
		?>
				<a class="back-to-search-link flexmls-primary-color-font" href="<?php echo $back_to_search_link; ?>">&larr; Back to search</a>
		<?php endif; ?>
		<button class="flexmls-btn flexmls-btn-primary flexmls-primary-color-background" onclick="flexmls_connect.contactForm({
			'title': 'Contact agent',
			'subject': '<?php echo $one_line_address_add_slashes; ?> - MLS# <?php echo addslashes($sf['ListingId'])?> ',
			'agentEmail': '<?php echo $this->contact_form_agent_email( $sf ); ?>',
			'officeEmail': '<?php echo $this->contact_form_office_email( $sf ); ?>',
			'id': '<?php echo addslashes( $sf['ListingId'] ); ?>'
		});">
			Contact agent
		</button>
	</div>
	<div class="top-info-wrapper listing-section">
		<div class="title-and-details-wrapper">
			<div class="title-and-status-wrapper">
				<h2 class="property-title flexmls-title-largest flexmls-primary-color-font flexmls-heading-font"><?php echo esc_html( $one_line_address ); ?></h2>

				<?php if ( strtotime( $sf['OnMarketDate'] ) > strtotime( '-7 days' ) ) : ?>
					<span class="new-listing-tag">New Listing</span>
				<?php endif; ?>
			</div>
			
			<div class="price-and-actions-wrapper">
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
					<span class="flexmls-price flexmls-title-large"><?php echo $list_price; ?></span>
				<div class="actions-wrapper">
					<?php fmcAccount::write_carts( $record ); ?>
				</div>
			</div>
		</div>
	</div>
	<?php if ( $count_photos > 0 ) : ?>
		<div class="slideshow-wrapper listing-section">
			<div id="listing-slideshow" class="owl-carousel">
				<?php foreach ( $sf['Photos'] as $index => $p ) : ?>
					<?php if ( $index == 1 ) : ?>
						<?php if ( $count_videos > 0 ) : ?>
							<?php foreach ( $sf['Videos'] as $video ) : ?>
								<?php if ( $video['Privacy'] == "Public" ) : ?>
									<div class="listing-image listing-video">
										<?php echo $this->iframe_from_html_or_url( $video['ObjectHtml'] ); ?>
									</div>
								<?php endif; ?>
							<?php endforeach; ?>
						<?php endif; ?>
						<?php if ( $count_tours > 0 ) : ?>
							<?php foreach ( $sf['VirtualTours'] as $vtour ) : ?>
								<?php if ( $vtour['Privacy'] == "Public" ) : ?>
									<div class="listing-image listing-vtour">
										<?php echo $this->iframe_from_html_or_url( $vtour['Uri'] ); ?>
									</div>
								<?php endif; ?>
							<?php endforeach; ?>
						<?php endif; ?>
					<?php endif; ?>

					<div class="listing-image">
						<img src="<?php echo esc_url( $p['UriLarge'] ); ?>" loading="lazy" />
					</div>
				<?php endforeach; ?>
			</div>
		</div>
		<script type="text/javascript">
			jQuery( function () {
				jQuery('#listing-slideshow').owlCarousel(
					{
						nav: true,
						dots: false,
						center: true,
						loop: true,
						navText: [ "&lsaquo;", "&rsaquo;" ],
						responsive: {
							0: {
								items: 1
							},
							600: {
								items: 1
							}
						}
					}
				);
			} );
		</script>
	<?php endif; ?>

	<div class="main-details-section listing-section">
		<div class="flexmls-details">
			<?php
				$main_details = [
					['field' => 'PropertyTypeLabel', 'label' => 'Property Type'],
					['field' => 'BedsTotal', 'label' => 'Bedrooms'],
					['field' => 'BathsTotal', 'label' => 'Baths'],
				];

				if ( flexmlsConnect::is_not_blank_or_restricted( $sf['BuildingAreaTotal'] ) ) {
					$main_details []= ['field' => 'BuildingAreaTotal', 'label' => 'Square Footage', 'value' => number_format( $sf['BuildingAreaTotal'] )]; 
				}

				elseif ( flexmlsConnect::is_not_blank_or_restricted( $sf['LivingArea'] ) ) {
					$main_details []= ['field' => 'LivingArea', 'label' => 'Square Footage', 'value' => number_format( $sf['LivingArea'] )]; 
				}

				if ( flexmlsConnect::is_not_blank_or_restricted( $sf['LotSizeSquareFeet'] ) ) {
					$main_details []= ['field' => 'LotSizeSquareFeet', 'label' => 'Lot Size (sq. ft.)', 'value' => number_format( $sf['LotSizeSquareFeet'] ) ];
				}

				if ( flexmlsConnect::is_not_blank_or_restricted( $sf['MlsStatus'] ) ) {
					$main_details []= ['field' => 'MlsStatus', 'label' => 'Status' ];
				}

			?>

			<?php foreach ( $main_details as $detail ) : ?>
				<?php if ( flexmlsConnect::is_not_blank_or_restricted( $sf[$detail['field']] ) ) : ?>
					<?php $value = array_key_exists( 'value', $detail ) ? $detail['value'] : $sf[$detail['field']]; ?>
					<span class="flexmls-detail">
						<span class="detail-label flexmls-primary-color-font flexmls-heading-font"><?php echo esc_html( $detail['label'] ); ?>:</span>
						<span class="detail-value"><?php echo esc_html( $value ); ?></span>
					</span>
				<?php endif; ?>
			<?php endforeach; ?>
		</div>
		<div class="price-and-dates">
			<?php if ( flexmlsConnect::is_not_blank_or_restricted( $sf['ClosePrice']) && $sf['MlsStatus'] == 'Closed') : ?>
			 <span class="flexmls-detail flexmls-price">
					<span class="detail-label">Current Price:</span>
					<span class="detail-value">$<?php echo esc_html( flexmlsConnect::gentle_price_rounding($sf['ClosePrice']) ); ?>
					</span>
				</span>
			<?php elseif( flexmlsConnect::is_not_blank_or_restricted( $sf['ListPrice'] ) ) : ?>
				<span class="flexmls-detail flexmls-price">
					<span class="detail-label">Current Price:</span>
					<span class="detail-value">$<?php echo esc_html( flexmlsConnect::gentle_price_rounding( $sf['ListPrice'] ) ); ?></span>
				</span>
			<?php endif; ?>
			<?php if( flexmlsConnect::is_not_blank_or_restricted( $sf['OnMarketDate'] ) ) : ?>
				<span class="flexmls-detail">
					<span class="detail-label">List Date:</span>
					<span class="detail-value"><?php echo esc_html( date( 'n/d/Y', strtotime( $sf['OnMarketDate'] ) ) ); ?></span>
				</span>
			<?php endif; ?>
			<?php if( flexmlsConnect::is_not_blank_or_restricted( $sf['ListingUpdateTimestamp'] ) ) : ?>
				<span class="flexmls-detail">
					<span class="detail-label">Last Modified:</span>
					<span class="detail-value"><?php echo esc_html( date( 'n/d/Y', strtotime( $sf['ListingUpdateTimestamp'] ) ) ); ?></span>
				</span>
			<?php endif; ?>
		</div>
	</div>
	  
	<div class="overview-section listing-section">
		<?php if ( flexmlsConnect::is_not_blank_or_restricted( $sf['PublicRemarks'] ) ) : ?>
			<h2 class="flexmls-title-larger flexmls-primary-color-font flexmls-heading-font">Description</h2>
			<div class="flexmls-description">
				<?php echo $sf['PublicRemarks']; ?> 
				<?php if($sf['Supplement']) : ?>
					<p><strong>Supplements: </strong><?php echo $sf['Supplement']; ?></p>
				<?php endif; ?>
			</div>
		<?php endif; ?>
		<?php if ( $sf['OpenHousesCount']  > 0 ) : ?>
			<div class="open-houses-list-details">
				<h2 class="flexmls-title-larger flexmls-primary-color-font flexmls-heading-font">Open Houses</h2>
				<?php foreach($sf['OpenHouses'] as $OpenHouse) : 
					$todayDate = date("d.m.Y H:i");
					$match_date = date('d.m.Y H:i', strtotime($OpenHouse['Date']));
					if($todayDate == $match_date) { 
						$openingDay = 'Today, ';
					} elseif(date("+1 day", strtotime($todayDate)) == $match_date) {
						$openingDay = 'Tomorrow, ';
					} else {
						$openingDay = date('l, F d, ', strtotime($OpenHouse['Date']));
					}

					?>
					<div class="open-house-list-inner"><?php echo $openingDay.$OpenHouse['StartTime'].' - '.$OpenHouse['EndTime']; ?></div>
				<?php endforeach; ?>
			</div>
		<?php endif; ?>
	</div>

	<div class="listing-section more-information-toggle">
		<h2 class="flexmls-title-larger flexmls-primary-color-font flexmls-heading-font">More Information <span class="mls-id">MLS# <?php echo esc_html( $sf['ListingId'] ); ?></h2>
	</div>
	<script type="text/javascript">
		jQuery( function ( $ ) {
			$( '.more-information-toggle' ).on( 'click', function ( e ) {
				e.preventDefault();
				var $moreInfoWrapper = $( this ).siblings( '.listing-more-information' );

				if ( $( this ).hasClass( 'active' ) ) {
					$moreInfoWrapper.slideUp();
					$( this ).removeClass( 'active' );
				} else {
					$moreInfoWrapper.slideDown();
					$( this ).addClass( 'active' );
				}
			} );
		} );
	</script>

	<div class="features-section listing-section listing-more-information">
		<div class="property-details">
			<?php if ( $this->property_detail_values ) : ?>
				<?php foreach ( $this->property_detail_values as $k => $v ) : ?>
					<div class="details-section">
						<h3 class="detail-section-header flexmls-title-large flexmls-heading-font"><?php echo esc_html( $k ); ?></h3>
						<div class="property-details-wrapper">
							<?php foreach ( $v as $key => $value ) : ?>
								<span class="detail-value"><?php echo $value; ?></span>
							<?php endforeach; ?>
						</div>
					</div>
				<?php endforeach ; ?>
			<?php endif; ?>

			<?php if ( ! empty( $property_features_values ) ) : ?>
				<div class="details-section">
					<h3 class="detail-section-header flexmls-title-large flexmls-heading-font">Property Features</h3>
					<div class="property-details-wrapper">
						<?php foreach ( $property_features_values as $k => $v ) : ?>
							<?php
								$value = "<b>".$k.": </b>";
								foreach($v as $x){
									$value .= $x."; ";
								}
								$value = trim($value,"; ");
							?>
							<span class="detail-value"><?php echo $value; ?></span>
						<?php endforeach; ?>
					</div>
				</div>
			<?php endif; ?>

			<?php $room_count = isset($room_values[0]) ? count($room_values[0]) : false; ?>
			<?php if ( $room_count ) : ?>
				<div class="details-section rooms-section">
					<h3 class="property-details-wrapper flexmls-title-large flexmls-heading-font">Room Information</h3>

					<div class="property-details-wrapper">
						<?php foreach ( $room_values[0] as $i => $room ) : ?>
							<span class="detail-value">
								<span class="room-name"><?php echo esc_html( $room ); ?></span>
								<?php foreach ( $room_names as $j => $room_field ) : ?>
									<?php if ( $j > 0 && ! empty( $room_values[$j][$i] ) ) : ?>
										<span class="room-detail">
											<span class="detail-label"><?php echo esc_html( $room_field ); ?></span>: <?php echo esc_html( $room_values[$j][$i] ); ?>
										</span>
									<?php endif; ?>
								<?php endforeach; ?>

							</span>
						<?php endforeach; ?>
					</div>
				</div>
			<?php endif; ?>
		</div>
	</div>

	<?php if ( $sf['DocumentsCount'] ) : ?>
		<div class="documents-section listing-section">
			<h2 class="flexmls-title-larger flexmls-primary-color-font flexmls-heading-font">Documents</h2>
			<div class="flexmls-documents-wrapper">
				<?php $fmc_colorbox_extensions = [ 'gif', 'png' ]; ?>
				<?php foreach ( $sf['Documents'] as $fmc_document ) : ?>
					<?php if ($fmc_document['Privacy']=='Public') : ?>
						<?php
							$fmc_extension = explode( '.', $fmc_document['Uri'] );
							$fmc_extension = ( $fmc_extension[ count( $fmc_extension ) - 1 ] );
							if ( $fmc_extension == 'pdf' ){
								$fmc_file_image = $fmc_plugin_url . '/assets/images/pdf-tiny.gif';
								$fmc_docs_class = "class='fmc_document fmc_document_pdf'";
							}
							elseif ( in_array( $fmc_extension, $fmc_colorbox_extensions ) ){
								$fmc_file_image = $fmc_plugin_url . '/assets/images/image_16.gif';
								$fmc_docs_class = "class='fmc_document fmc_document_colorbox'";
							}
							else{
								$fmc_file_image = $fmc_plugin_url . '/assets/images/docs_16.gif';
							}

							echo "<div><a $fmc_docs_class value={$fmc_document['Uri']}><img src='{$fmc_file_image}' align='absmiddle' alt='View Document' title='View Document' /> {$fmc_document['Name']} &rsaquo;</a></div>";
						?>
					<?php endif; ?>
				<?php endforeach; ?>
			</div>
		</div>
	<?php endif; ?>

	<div class="map-section listing-section">
		<?php if ( isset ( $options['google_maps_api_key'] ) && $options['google_maps_api_key'] && flexmlsConnect::is_not_blank_or_restricted($sf['Latitude']) && flexmlsConnect::is_not_blank_or_restricted($sf['Longitude']) ) : ?>
			<div id='flexmls_connect__map_canvas' latitude='<?php echo esc_attr( $sf['Latitude'] ); ?>' longitude='<?php echo esc_attr( $sf['Longitude'] ); ?>'></div>
		<?php endif; ?>
	</div>

	<div class="disclosure-section listing-section">
		<?php if ( $sf['StateOrProvince'] != 'NY' ) : ?>
			<?php foreach ( $compList as $reqs ) : ?>
				<?php if ( flexmlsConnect::is_not_blank_or_restricted( $reqs[1] ) ) : ?>
					<?php if ( $reqs[0] == 'LOGO' ) : ?>
						<?php $listing_disclosure_title = $one_line_address . '- MLS# ' . $sf['ListingId']; ?>
						<img style='padding-bottom: 5px' src='<?php echo esc_attr( $reqs[1] ); ?>' alt='<?php echo esc_attr( $listing_disclosure_title ); ?>' title='<?php echo esc_attr( $listing_disclosure_title ); ?>' />
					<?php else:  ?>
						<div class="listing-req"><?php echo esc_html( $reqs[0] ); ?> <?php echo esc_html( $reqs[1] ); ?></div>
					<?php endif; ?>
				<?php endif; ?>
			<?php endforeach; ?>
		<?php endif; ?>

		<?php if ( flexmlsConnect::is_not_blank_or_restricted( $sf['CompensationDisclaimer'] ) ) : ?>
		<hr />
		<div class="compensation-disclaimer">
			<?php echo $sf['CompensationDisclaimer']; ?>
		</div>
		<hr />
		<?php endif; ?>

		<?php if( flexmlsConnect::NAR_broker_attribution( $sf ) ) : ?>
		<div class='listing-req'>Broker Attribution: 
			<?php echo flexmlsConnect::NAR_broker_attribution( $sf ); ?>
		</div>
		<hr />
		<?php endif; ?>

		<div class="disclosure-text">
			<?php echo flexmlsConnect::get_big_idx_disclosure_text(); ?>
		</div>
		<hr />
		
		<div class="fbs-branding" style="text-align: center;">
          <?php echo flexmlsConnect::fbs_products_branding_link(); ?>
      </div>
	</div>
</div>
