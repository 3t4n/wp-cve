<!-- wp:columns -->
<div class="wp-block-columns"><!-- wp:column -->
<div class="wp-block-column"><!-- wp:paragraph -->
<p>[fvd_calljson]<br>[fvd_getdata return=VehicleImageLogoReg]</p>
<!-- /wp:paragraph --></div>
<!-- /wp:column -->

<!-- wp:column -->
<div class="wp-block-column"></div>
<!-- /wp:column --></div>
<!-- /wp:columns -->

<!-- wp:columns -->
<div class="wp-block-columns"><!-- wp:column -->
<div class="wp-block-column"><!-- wp:heading -->
<h2>Vehicle Information</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p class="fvd-section">Make: <strong>[fvd_getdata return=BasicVehicleDetails value=Make]</strong></p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p class="alternative-section">Model: <strong>[fvd_getdata return=BasicVehicleDetails value=Model]</strong></p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p class="fvd-section">Colour: <strong>[fvd_getdata return=BasicVehicleDetails value=Colour]</strong></p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p class="alternative-section">Vehicle Type: <strong>[fvd_getdata return=VehicleType]</strong></p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p class="fvd-section">Body Style: <strong>[fvd_getdata return=BodyStyle]</strong></p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p class="alternative-section">Fuel Type: <strong>[fvd_getdata return=BasicVehicleDetails value=FuelType]</strong></p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p class="fvd-section">BHP: <strong>[fvd_getdata return=BHP]</strong></p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p class="alternative-section">Top Speed: <strong>[fvd_getdata return=TopSpeed]</strong></p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p class="fvd-section">0-60 MPH: <strong>[fvd_getdata return=zerotosixty]</strong></p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p class="alternative-section">Insurance Group: <strong>[fvd_getdata return=InsuranceGroup]</strong></p>
<!-- /wp:paragraph -->
 
<!-- wp:paragraph -->
<p class="fvd-section">V5C Issue Date: <strong>[fvd_getdata return=BasicVehicleDetails value=DateOfLastV5CIssued]</strong></p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p class="alternative-section">Year: <strong>[fvd_getdata return=BasicVehicleDetails value=YearOfManufacture]</strong></p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p class="fvd-section">Vehicle Age: <strong>[fvd_getdata return=BasicVehicleDetails value=Age]</strong></p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p class="alternative-section">Date of Registration: <strong>[fvd_getdata return=BasicVehicleDetails value=DateOfFirstRegistration]</strong></p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p class="fvd-section">Cylinder Capacity: <strong>[fvd_getdata return=BasicVehicleDetails value=CylinderCapacity]</strong></p>
<!-- /wp:paragraph -->

<!-- wp:spacer {"height":32} -->
<div style="height:32px" aria-hidden="true" class="wp-block-spacer"></div>
<!-- /wp:spacer -->

<!-- wp:heading -->
<h2>Mileage Information</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p class="fvd-section">Last Record: <strong>[fvd_getdata return=BasicVehicleDetails value=LastMileageRecord]</strong></p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p class="alternative-section">Average Yearly Mileage: <strong>[fvd_getdata return=BasicVehicleDetails value=AverageMileagePerYear]</strong> <strong>([fvd_getdata return=BasicVehicleDetails value=IsMileageAverageMessage])</strong></p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p class="fvd-section">Estimated Mileage Now: <strong>[fvd_getdata return=BasicVehicleDetails value=EstimatedMileageNow]</strong></p>
<!-- /wp:paragraph -->

<!-- wp:spacer {"height":22} -->
<div style="height:22px" aria-hidden="true" class="wp-block-spacer"></div>
<!-- /wp:spacer -->

<!-- wp:paragraph -->
<p>[fvd_getdata return=mileagechart type=linechart hexcolor=#69d2e7]</p>
<!-- /wp:paragraph --></div>
<!-- /wp:column -->

<!-- wp:column -->
<div class="wp-block-column"><!-- wp:paragraph -->
<p>[fvd_getdata return=TitleMOT]</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p class="fvd-section">MOT Due Date: <strong><strong><strong>[fvd_getdata return=BasicVehicleDetails value=DateMotDue]</strong></strong></strong></p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p class="alternative-section">Days Left: <strong><strong><strong>[fvd_getdata return=BasicVehicleDetails value=DaysLeftUntilMotDue]</strong></strong></strong></p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p class="fvd-section">Previous MOT Records: <strong><strong>[fvd_getdata return=BasicVehicleDetails value=TotalMotRecords]</strong></strong></p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p class="alternative-section">Last MOT Date: <strong><strong>[fvd_getdata return=BasicVehicleDetails value=LastMotDate]</strong></strong></p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p class="fvd-section">MOT Status: <strong><strong>[fvd_getdata return=BasicVehicleDetails value=MotStatusDescription]</strong></strong></p>
<!-- /wp:paragraph -->

<!-- wp:spacer {"height":32} -->
<div style="height:32px" aria-hidden="true" class="wp-block-spacer"></div>
<!-- /wp:spacer -->

<!-- wp:paragraph -->
<p>[fvd_getdata return=TitleTAX]</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p class="fvd-section">Road Tax Status:<strong><strong> [fvd_getdata return=BasicVehicleDetails value=RoadTaxStatusDescription]</strong></strong></p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p class="alternative-section">Road Tax Due: <strong><strong><strong><strong>[fvd_getdata return=BasicVehicleDetails value=DateRoadTaxDue]</strong></strong></strong></strong></p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p class="fvd-section">Road Tax Days left: <strong>[fvd_getdata return=BasicVehicleDetails value=DaysLeftUntilRoadTaxDue]</strong></p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p class="alternative-section">co2 Emissions:<strong><strong> [fvd_getdata return=BasicVehicleDetails value=Co2Emissions]</strong></strong> <strong>(<strong><strong>[fvd_getdata return=BasicVehicleDetails value=Co2Marker]</strong></strong>)</strong></p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p class="fvd-section">Twelve Month Tax Cost: <strong>£[fvd_getdata return=BasicVehicleDetails value=TwelveMonthsTaxRate]</strong></p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p class="alternative-section">Six Month Tax Cost:<strong> £[fvd_getdata return=BasicVehicleDetails value=SixMonthsTaxRate]</strong></p>
<!-- /wp:paragraph -->

<!-- wp:spacer {"height":10} -->
<div style="height:10px" aria-hidden="true" class="wp-block-spacer"></div>
<!-- /wp:spacer -->

<!-- wp:paragraph -->
<p>[fvd_getdata return=taxbandimage]</p>
<!-- /wp:paragraph -->

<!-- wp:spacer {"height":32} -->
<div style="height:32px" aria-hidden="true" class="wp-block-spacer"></div>
<!-- /wp:spacer --></div>
<!-- /wp:column --></div>
<!-- /wp:columns -->

<!-- wp:columns -->
<div class="wp-block-columns"><!-- wp:column -->
<div class="wp-block-column"><!-- wp:separator {"align":"center","className":"is-style-wide"} -->
<hr class="wp-block-separator aligncenter is-style-wide"/>
<!-- /wp:separator -->

<!-- wp:spacer {"height":32} -->
<div style="height:32px" aria-hidden="true" class="wp-block-spacer"></div>
<!-- /wp:spacer -->

<!-- wp:heading {"textAlign":"center"} -->
<h2 class="has-text-align-center">MOT HISTORY</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>[fvd_returnmotrecord record=0]</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p>[fvd_returnmotrecord record=1]</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p>[fvd_returnmotrecord record=2]</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p>[fvd_returnmotrecord record=3]</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p>[fvd_returnmotrecord record=4]</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p>[fvd_returnmotrecord record=5]</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p>[fvd_returnmotrecord record=6]</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p>[fvd_returnmotrecord record=7]</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p>[fvd_returnmotrecord record=8]</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p>[fvd_returnmotrecord record=9]</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p>[fvd_returnmotrecord record=10]</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p>[fvd_returnmotrecord record=11]</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p>[fvd_returnmotrecord record=12]</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p>[fvd_returnmotrecord record=13]</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p>[fvd_returnmotrecord record=14]</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p>[fvd_returnmotrecord record=15]</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p>[fvd_returnmotrecord record=16]</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p>[fvd_returnmotrecord record=17]</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p>[fvd_returnmotrecord record=18]</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p>[fvd_returnmotrecord record=19]</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p>[fvd_returnmotrecord record=20]</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p>[fvd_returnmotrecord record=21]</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p>[fvd_returnmotrecord record=22]</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p>[fvd_returnmotrecord record=23]</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p>[fvd_returnmotrecord record=24]</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p>[fvd_returnmotrecord record=25]</p>
<!-- /wp:paragraph -->

<!-- wp:spacer {"height":32} -->
<div style="height:32px" aria-hidden="true" class="wp-block-spacer"></div>
<!-- /wp:spacer -->

<!-- wp:separator {"align":"center","className":"is-style-wide"} -->
<hr class="wp-block-separator aligncenter is-style-wide"/>
<!-- /wp:separator -->

<!-- wp:paragraph -->
<p class="fvdrepbottom"><a href="https://www.rapidcarcheck.co.uk/" target="_blank">Powered By Rapid Car Check</a></p>
<!-- /wp:paragraph -->

<!-- wp:separator {"align":"center","className":"is-style-wide"} -->
<hr class="wp-block-separator aligncenter is-style-wide"/>
<!-- /wp:separator -->

<!-- wp:spacer {"height":32} -->
<div style="height:32px" aria-hidden="true" class="wp-block-spacer"></div>
<!-- /wp:spacer --></div>
<!-- /wp:column --></div>
<!-- /wp:columns -->