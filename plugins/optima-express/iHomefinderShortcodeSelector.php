<?php

class iHomefinderShortcodeSelector {
	
	private $displayRules;
	private $formData;
	private $buttonText;
	
	public function __construct() {
		$this->displayRules = iHomefinderDisplayRules::getInstance();
		$this->formData = iHomefinderFormData::getInstance();
	}
	
	public function getButtonText() {
		return $this->buttonText;	
	}
	
	public function setButtonText($buttonText) {
		$this->buttonText = $buttonText;
	}
	
	public function getNotification() {
		return $this->notification;
	}
	
	public function setNotification($notification) {
		$this->notification = $notification;
	}
	
	public function getHeadContent() {
		?>
		<link type="text/css" rel="stylesheet" href="<?php echo plugins_url("css/bootstrap.css", __FILE__); ?>" />
		<script type="text/javascript" src="<?php echo plugins_url("js/bootstrap.js", __FILE__); ?>"></script>
		<script type="text/javascript" src="<?php echo plugins_url("js/iHomefinderShortcodeSelector.js", __FILE__); ?>"></script>
		<script type="text/javascript">
			jQuery(document).on("submit", "form", function(event) {
				event.preventDefault();
				var $form = jQuery(this);
				var action = $form.find("input[name='action']").val();
				iHomefinderShortcodeSelector[action](this);
			});
		</script>
		<style type="text/css">
			.menu {
				display: none;
			}
		</style>
		<?php
	}
	
	public function getShortcodeSelectorContent() {
		?>
		<div class="panel-body">
			<ul class="nav nav-tabs" id="ihf-dialog-tabs">
				<li>
					<a href="#Leads" data-toggle="tab">Leads</a>
				</li>
				<li class="active">
					<a href="#Listings" data-toggle="tab">Listings</a>
				</li>
				<li>
					<a href="#Search" data-toggle="tab">Search</a>
				</li>
				<li>
					<a href="#IdxPages" data-toggle="tab">IDX Pages</a>
				</li>
				<?php if($this->displayRules->isAgentBioEnabled() || $this->displayRules->isOfficeEnabled()) { ?>
					<li>
						<a href="#Broker" data-toggle="tab">Broker</a>
					</li>
				<?php } ?>
			</ul>
			<div class="tab-content">
				<div class="tab-pane fade" id="Leads">
					<h4></h4>
					<div class="col-xs-5">
						<div class="form-group">
							<?php if($this->displayRules->isEmailSignupShortcodeEnabled()) { ?>
								<div class="radio">
									<label class="control-label">
										<input name="shortcodeType" type="radio" onclick="jQuery('.menu').hide(); jQuery('#hotSheetReportSignup').toggle();">
										Alert Signup
									</label>
								</div>
							<?php } ?>
							<div class="radio">
								<label class="control-label">
									<input name="shortcodeType" type="radio" onclick="jQuery('.menu').hide(); jQuery('#valuationWidget').toggle();">
									Sell My House
								</label>
							</div>
							<?php if($this->displayRules->isOrganizerEnabled()) { ?>
								<div class="radio">
									<label class="control-label">
										<input name="shortcodeType" type="radio" onclick="jQuery('.menu').hide(); jQuery('#organizerLoginWidget').toggle();">
										Property Organizer Widget
									</label>
								</div>
							<?php } ?>
							<?php if($this->displayRules->isOrganizerEnabled()) { ?>
								<div class="radio">
									<label class="control-label">
										<input name="shortcodeType" type="radio" onclick="jQuery('.menu').hide(); jQuery('#registrationFormWidget').toggle();">
										Registration Form
									</label>
								</div>
							<?php } ?>
						</div>
					</div>
					<div class="col-xs-7">
						<div id="hotSheetReportSignup" class="menu">
							<form>
								<input type="hidden" name="action" value="insertHotSheetReportSignup" />
								<input type="hidden" name="slug" value="<?php echo iHomefinderShortcodeDispatcher::HOT_SHEET_REPORT_SIGNUP_SHORTCODE; ?>" />
								<div class="form-group">
									<label class="control-label">Market</label>
									<div>
										<?php $this->createHotSheetsSelect(true); ?>
									</div>
								</div>
								<div class="form-group">
									<label class="control-label">Report Type</label>
									<div>
										<?php $this->createHotSheetReportTypeSelect(true); ?>
									</div>
								</div>
								<button class="btn btn-default"><?php echo $this->buttonText; ?></button>
							</form>
						</div>
						<div id="valuationWidget" class="menu">
							<form>
								<input type="hidden" name="action" value="insertValuationWidget" />
								<input type="hidden" name="slug" value="<?php echo iHomefinderShortcodeDispatcher::VALUATION_WIDGET_SHORTCODE; ?>" />
								<div class="form-group">
									<label class="control-label">Style</label>
									<div>
										<select class="form-control" name="style" required="required">
											<option value="">Select One</option>
											<option value="twoline">Two Line</option>
											<option value="vertical">Vertical</option>
										</select>
									</div>
								</div>
								<button class="btn btn-default"><?php echo $this->buttonText; ?></button>
							</form>
						</div>
						<div id="organizerLoginWidget" class="menu">
							<form>
								<input type="hidden" name="action" value="insertOrganizerLoginWidget" />
								<input type="hidden" name="slug" value="<?php echo iHomefinderShortcodeDispatcher::ORGANIZER_LOGIN_WIGET_SHORTCODE; ?>" />
								<div class="form-group">
									<label class="control-label">Style</label>
									<div>
										<select class="form-control" name="style" required="required">
											<option value="">Select One</option>
											<option value="vertical">Vertical</option>
											<option value="horizontal">Horizontal</option>
										</select>
									</div>
								</div>
								<button class="btn btn-default"><?php echo $this->buttonText; ?></button>
							</form>
						</div>
						<div id="registrationFormWidget" class="menu">
							<form>
								<input type="hidden" name="action" value="insertRegistrationWidget" />
								<input type="hidden" name="slug" value="<?php echo iHomefinderShortcodeDispatcher::REGISTRATION_FORM_SHORTCODE; ?>" />
								<div class="form-group">
									<label class="control-label">Redirect URL</label>
									<div>
										<input class="form-control" name="url" type="url" placeholder="e.g., http://www.mydestination.com/content">
									</div>
								</div>
								<div class="form-group">
									<label class="control-label">Button Text</label>
									<div>
										<input class="form-control" name="buttonText" type="text" placeholder="e.g., View Now!">
									</div>
								</div>
								<button class="btn btn-default"><?php echo $this->buttonText; ?></button>
							</form>
						</div>
					</div>
				</div>
				<div class="tab-pane fade in active" id="Listings">
					<h4></h4>
					<div class="col-xs-4">
						<div class="form-group">
							<div class="radio">
								<label class="control-label">
									<input name="shortcodeType" type="radio" onclick="jQuery('.listingGalleryMenu').show(); jQuery('.menu').hide();">
									Listing Gallery
								</label>
							</div>
							<div class="form-group listingGalleryMenu" style="display: none;">
								<select class="form-control" name="header" onchange="jQuery('.menu').hide(); jQuery('#' + this.value).toggle();">
									<option value="">Select One</option>
									<option value="featuredMenu">Featured Listings</option>
									<?php if($this->displayRules->isAgentBioEnabled()) { ?>
										<option value="agentMenu">Agent Listing</option>
									<?php } ?>
									<?php if($this->displayRules->isOfficeEnabled()) { ?>
										<option value="officeMenu">Office Listing</option>
									<?php } ?>
									<?php if($this->displayRules->isHotSheetEnabled()) { ?>
										<option value="listingReportMenu">Listing Report</option>
									<?php } ?>
									<?php if($this->displayRules->isHotSheetOpenHomeReportEnabled()) { ?>
										<option value="openHomeReportMenu">Open Home Report</option>
									<?php } ?>
									<?php if(iHomefinderDisplayRules::getInstance()->isNamedSearchEnabled()) { ?>
										<option value="searchMenu">Search</option>
									<?php } ?>
								</select>
							</div>
							<?php if($this->displayRules->supportsGallerySlider()) { ?>
								<div class="radio">
									<label class="control-label">
										<input name="shortcodeType" type="radio" onclick="jQuery('.listingGalleryMenu').hide(); jQuery('.menu').hide(); jQuery('#gallerySliderMenu').toggle();">
										Gallery Slider
									</label>
								</div>
							<?php } ?>
							<?php if($this->displayRules->isHotSheetMarketReportEnabled()) { ?>
								<div class="radio">
									<label class="control-label">
										<input name="shortcodeType" type="radio" onclick="jQuery('.listingGalleryMenu').hide(); jQuery('.menu').hide(); jQuery('#marketReportMenu').toggle();">
										Market Report
									</label>
								</div>
							<?php } ?>
						</div>
					</div>
					<div class="col-xs-8">
						<div id="featuredMenu" class="menu">
							<form>
								<input type="hidden" name="action" value="insertFeaturedListings" />
								<input type="hidden" name="slug" value="<?php echo iHomefinderShortcodeDispatcher::FEATURED_SHORTCODE ?>" />
								<div class="form-group">
									<label class="control-label">Property Type</label>
									<div>
										<?php $this->createPropertyTypeSelect(false); ?>
									</div>
								</div>
								<?php if($this->displayRules->isSoldListingsInWidgets()) { ?>
									<div class="form-group">
										<label class="control-label">Status</label>
										<div>
											<?php $this->createStatusSelect(true); ?>
										</div>
									</div>
								<?php } ?>
								<div class="form-group">
									<label class="control-label">Sort</label>
									<div>
										<?php $this->createSortSelect(); ?>
									</div>
								</div>
								<div class="form-group">
									<label class="control-label">Results Per Page</label>
									<div>
										<input class="form-control" type="number" min="1" name="resultsPerPage" />
									</div>
								</div>
								<div class="form-group">
									<label class="control-label">Display Type</label>
									<div>
										<?php $this->createDisplayTypeSelect(); ?>
									</div>
								</div>
								<div class="checkbox">
									<label class="control-label">
										<input type="checkbox" value="true" name="includeMap" checked="checked" />
										Include Map
									</label>
								</div>
								<div class="form-group">
									<label class="control-label">Display Header</label>
									<div>
										<?php $this->createHeaderSelect(true); ?>
									</div>
								</div>
								<button class="btn btn-default"><?php echo $this->buttonText; ?></button>
							</form>
						</div>
						<div id="listingReportMenu" class="menu">
							<form>
								<input type="hidden" name="action" value="insertHotSheetListingReport" />
								<input type="hidden" name="slug" value="<?php echo iHomefinderShortcodeDispatcher::HOT_SHEETS_SHORTCODE ?>" />
								<div class="form-group">
									<label class="control-label">Market</label>
									<div>
										<?php $this->createHotSheetsSelect(true); ?>
									</div>
								</div>
								<?php if($this->displayRules->isSoldListingsInWidgets()) { ?>
									<div class="form-group">
										<label class="control-label">Status</label>
										<div>
											<?php $this->createStatusSelect(true); ?>
										</div>
									</div>
								<?php } ?>
								<div class="form-group">
									<label class="control-label">Sort</label>
									<div>
										<?php $this->createSortSelect(); ?>
									</div>
								</div>
								<div class="form-group">
									<label class="control-label">Results Per Page</label>
									<div>
										<input class="form-control" type="number" min="1" name="resultsPerPage" />
									</div>
								</div>
								<div class="form-group">
									<label class="control-label">Display Type</label>
									<div>
										<?php $this->createDisplayTypeSelect(); ?>
									</div>
								</div>
								<div class="checkbox">
									<label class="control-label">
										<input type="checkbox" value="true" name="includeMap" checked="checked" />
										Include Map
									</label>
								</div>
								<div class="form-group">
									<label class="control-label">Display Header</label>
									<div>
										<?php $this->createHeaderSelect(true); ?>
									</div>
								</div>
								<button class="btn btn-default"><?php echo $this->buttonText; ?></button>
							</form>
						</div>
						<div id="openHomeReportMenu" class="menu">
							<form>
								<input type="hidden" name="action" value="insertHotSheetOpenHomeReport" />
								<input type="hidden" name="slug" value="<?php echo iHomefinderShortcodeDispatcher::HOT_SHEET_OPEN_HOME_REPORT ?>" />
								<div class="form-group">
									<label class="control-label">Market</label>
									<div>
										<?php $this->createHotSheetsSelect(true); ?>
									</div>
								</div>
								<div class="form-group">
									<label class="control-label">Sort</label>
									<div>
										<?php $this->createSortSelect(); ?>
									</div>
								</div>
								<div class="checkbox">
									<label class="control-label">
										<input type="checkbox" value="true" name="includeMap" checked="checked" />
										Include Map
									</label>
								</div>
								<div class="form-group">
									<label class="control-label">Display Header</label>
									<div>
										<?php $this->createHeaderSelect(true); ?>
									</div>
								</div>
								<button class="btn btn-default"><?php echo $this->buttonText; ?></button>
							</form>
						</div>
						<div id="marketReportMenu" class="menu">
							<form>
								<input type="hidden" name="action" value="insertHotSheetMarketReport" />
								<input type="hidden" name="slug" value="<?php echo iHomefinderShortcodeDispatcher::HOT_SHEET_MARKET_REPORT ?>" />
								<div class="form-group">
									<label class="control-label">Market</label>
									<div>
										<?php $this->createHotSheetsSelect(true); ?>
									</div>
								</div>
								<div class="form-group">
									<label class="control-label">Display Header</label>
									<div>
										<?php $this->createHeaderSelect(true); ?>
									</div>
								</div>
								<div class="form-group">
									<label class="control-label"> Slider Columns</label>
									<div>
										<input class="form-control" type="number" min="1" name="columns" required="required" />
									</div>
								</div>
								<button class="btn btn-default"><?php echo $this->buttonText; ?></button>
							</form>
						</div>
						<div id="searchMenu" class="menu">
							<form>
								<input type="hidden" name="action" value="insertSearchResults" />
								<input type="hidden" name="slug" value="<?php echo iHomefinderShortcodeDispatcher::SEARCH_RESULTS_SHORTCODE; ?>" />
								<div class="form-group">
									<label class="control-label">Cities</label>
									<div>
										<?php $this->createCitySelect(true); ?>
									</div>
								</div>
								<div class="form-group">
									<label class="control-label">Property Type</label>
									<div>
										<?php $this->createPropertyTypeSelect(true); ?>
									</div>
								</div>
								<div class="form-group">
									<label class="control-label">Bed</label>
									<div>
										<input class="form-control" type="number" min="0" name="bed" />
									</div>
								</div>
								<div class="form-group">
									<label class="control-label">Bath</label>
									<div>
										<input class="form-control" type="number" min="0" name="bath" />
									</div>
								</div>
								<div class="form-group">
									<label class="control-label">Min Price</label>
									<div>
										<input class="form-control" type="number" min="0" name="minPrice" />
									</div>
								</div>
								<div class="form-group">
									<label class="control-label">Max Price</label>
									<div>
										<input class="form-control" type="number" min="0" name="maxPrice" />
									</div>
								</div>
								<?php if($this->displayRules->isSoldListingsInWidgets()) { ?>
									<div class="form-group">
										<label class="control-label">Status</label>
										<div>
											<?php $this->createStatusSelect(true); ?>
										</div>
									</div>
								<?php } ?>
								<div class="form-group">
									<label class="control-label">Sort</label>
									<div>
										<?php $this->createSortSelect(); ?>
									</div>
								</div>
								<div class="form-group">
									<label class="control-label">Results Per Page</label>
									<div>
										<input class="form-control" type="number" min="1" name="resultsPerPage" />
									</div>
								</div>
								<div class="form-group">
									<label class="control-label">Display Type</label>
									<div>
										<?php $this->createDisplayTypeSelect(); ?>
									</div>
								</div>
								<div class="checkbox">
									<label class="control-label">
										<input type="checkbox" value="true" name="includeMap" checked="checked" />
										Include Map
									</label>
								</div>
								<div class="form-group">
									<label class="control-label">Display Header</label>
									<div>
										<?php $this->createHeaderSelect(true); ?>
									</div>
								</div>
								<button class="btn btn-default"><?php echo $this->buttonText; ?></button>
							</form>
						</div>
						<div id="agentMenu" class="menu">
							<form>
								<input type="hidden" name="action" value="insertAgentListings" />
								<input type="hidden" name="slug" value="<?php echo iHomefinderShortcodeDispatcher::AGENT_LISTINGS_SHORTCODE; ?>" />
								<div class="form-group">
									<label class="control-label">Agent</label>
									<div>
										<?php $this->createAgentSelect(true); ?>
									</div>
								</div>
								<button class="btn btn-default"><?php echo $this->buttonText; ?></button>
							</form>
						</div>
						<div id="officeMenu" class="menu">
							<form>
								<input type="hidden" name="action" value="insertOfficeListings" />
								<input type="hidden" name="slug" value="<?php echo iHomefinderShortcodeDispatcher::OFFICE_LISTINGS_SHORTCODE; ?>" />
								<div class="form-group">
									<label class="control-label">Office</label>
									<div>
										<?php $this->createOfficeSelect(true); ?>
									</div>
								</div>
								<button class="btn btn-default"><?php echo $this->buttonText; ?></button>
							</form>
						</div>
						<div id="gallerySliderMenu" class="menu">
							<form>
								<input type="hidden" name="action" value="insertGallerySlider" />
								<input type="hidden" name="slug" value="<?php echo iHomefinderShortcodeDispatcher::GALLERY_SLIDER_SHORTCODE; ?>" />
								<div class="form-group">
									<?php if($this->displayRules->isHotSheetEnabled()) { ?>
										<label class="radio-inline">
											<input type="radio" name="type" checked onclick="jQuery('#HotSheetsSelect').hide(); jQuery('select#hotSheetId').prop('selectedIndex', 0); jQuery('select#hotSheetId').removeAttr('required');" />
											Featured
										</label>
										<label class="radio-inline">
											<input type="radio" name="type" onclick="jQuery('#HotSheetsSelect').show(); jQuery('select#hotSheetId').attr('required', 'required');" />
											Market
										</label>
									<?php } ?>
								</div>
								<div id="HotSheetsSelect" class="form-group" style="display: none;">
									<?php $this->createHotSheetsSelect(); ?>
								</div>

								<?php if(!$this->displayRules->isKestrelAll()) { ?>
								<div class="form-group">
									<label class="control-label">
										<input style="margin: 0;" type="checkbox" name="fitToWidth" checked onchange="var $input = jQuery('#listingGalleryWidth').toggle().find('input'); $input.prop('required', !$input.prop('required')); ">
										Fit width to column
									</label>
									<div id="listingGalleryWidth" class="input-group" >
										<input class="form-control" type="number" min="1" name="width" required="required" />
										<span class="input-group-addon">px</span>
									</div>
								</div>
								
								<div class="form-group">
									<label class="control-label">Height</label>
									<div class="input-group">
										<input class="form-control" type="number" min="1" name="height" placeholder="Default" />
										<span class="input-group-addon">px</span>
									</div>
								</div>
								<?php } ?>

								<div class="form-group">
									<label class="control-label">Rows</label>
									<div>
										<input class="form-control" type="number" min="1" name="rows" required="required" />
									</div>
								</div>
								<?php if(!$this->displayRules->isKestrelAll()) { ?>
								<div class="form-group">
									<label class="control-label">Columns</label>
									<div>
										<input class="form-control" type="number" min="1" name="columns" required="required" />
									</div>
								</div>
								<?php } ?>


								<div class="form-group">
								<label class="control-label">Navigation Position</label>
									<div>
										<select class="form-control" name="nav" required="required">
											<option value="top">Top</option>
											<option value="bottom">Bottom</option>
											<option value="sides">Sides</option>
										</select>
									</div>
								</div>
								<div class="form-group">
									<label class="control-label">Style</label>
									<div>
										<select class="form-control" name="style" required="required">
											<option value="grid">Color</option>
											<option value="plain">Plain</option>
										</select>
									</div>
								</div>
								<div class="form-group">
									<label class="control-label">Effect</label>
									<div>
										<select class="form-control" name="effect" required="required">
											<option value="slide">Slide</option>
											<option value="fade">Fade</option>
										</select>
									</div>
								</div>
								<div class="form-group">
									<label class="control-label">Auto Advance</label>
									<div>
										<select class="form-control" name="auto" required="required">
											<option value="true">Yes</option>
											<option value="false">No</option>
										</select>
									</div>
								</div>
								<div class="form-group">
									<label class="control-label">Speed</label>
									<div class="input-group">
										<input class="form-control" type="number" min="1" name="interval" />
										<span class="input-group-addon">seconds</span>
									</div>
								</div>
								<?php if($this->displayRules->isSoldListingsInWidgets()) { ?>
									<div class="form-group">
										<label class="control-label">Status</label>
										<div>
											<?php $this->createStatusSelect(true); ?>
										</div>
									</div>
								<?php } ?>
								<div class="form-group">
									<label class="control-label">Sort</label>
									<div>
										<?php $this->createSortSelect(); ?>
									</div>
								</div>
								<div class="form-group">
									<label class="control-label">Max. Results</label>
									<div>
										<input class="form-control" type="number" min="1" name="maxResults" value="25" />
									</div>
								</div>
								<button class="btn btn-default"><?php echo $this->buttonText; ?></button>
							</form>
						</div>
					</div>
				</div>
				<div class="tab-pane fade" id="Search">
					<h4></h4>
					<div class="col-xs-4">
						<div class="form-group">
							<div class="radio">
								<label class="control-label">
									<input name="shortcodeType" type="radio" onclick="jQuery('.menu').hide(); jQuery('#quickSearchMenu').toggle();">
									Quick Search
								</label>
							</div>
							<?php if($this->displayRules->isMapSearchEnabled() && !$this->displayRules->isEurekaSearch()) { ?>
								<div class="radio">
									<label class="control-label">
										<input name="shortcodeType" type="radio" onclick="jQuery('.menu').hide(); jQuery('#mapSearchMenu').toggle();">
										Map Search
									</label>
								</div>
							<?php } ?>
							<?php if($this->displayRules->isEurekaSearch()) { ?>
								<div class="radio">
									<label class="control-label">
										<input name="shortcodeType" type="radio" onclick="jQuery('.menu').hide(); jQuery('#eurekaSearchMenu').toggle();">
										Search
									</label>
								</div>
							<?php } ?>
							<?php if($this->displayRules->isSearchByAddressEnabled() && !$this->displayRules->isEurekaSearch()) { ?>
								<div class="radio">
									<label class="control-label">
										<input name="shortcodeType" type="radio" onclick="jQuery('.menu').hide(); jQuery('#searchByAddressMenu').toggle();">
										Address Search
									</label>
								</div>
							<?php } ?>
							<?php if($this->displayRules->isSearchByListingIdEnabled() && !$this->displayRules->isEurekaSearch()) { ?>
								<div class="radio">
									<label class="control-label">
										<input name="shortcodeType" type="radio" onclick="jQuery('.menu').hide(); jQuery('#searchByListingIdMenu').toggle();">
										Listing ID Search
									</label>
								</div>
							<?php } ?>
						</div>
					</div>
					<div class="col-xs-8">
						<div id="quickSearchMenu" class="menu">
							<form>
								<input type="hidden" name="action" value="insertQuickSearch" />
								<input type="hidden" name="slug" value="<?php echo iHomefinderShortcodeDispatcher::QUICK_SEARCH_SHORTCODE; ?>" />
								<div class="form-group">
									<label class="control-label">Style</label>
									<div>
										<select class="form-control" name="style" required="required" onchange="this.value && this.value !== 'universal' ? jQuery('#quickSearchMenu .propertyType').show() : jQuery('#quickSearchMenu .propertyType').hide()">
											<option value="">Select One</option>
											<?php if($this->displayRules->supportsUniversalQuickSearchLayout()) { ?>
												<option value="universal">Universal</option>
											<?php } ?>
											<option value="horizontal">Horizontal</option>
											<option value="twoline">Two Line</option>
											<option value="vertical">Vertical</option>
										</select>
									</div>
								</div>
								<div class="form-group propertyType" style="display: none;">
									<div class="checkbox">
										<label class="control-label">
											<input type="checkbox" name="showPropertyType" value="true" checked />
											<span>Show Property Type</span>
										</label>
									</div>
								</div>
								<button class="btn btn-default"><?php echo $this->buttonText; ?></button>
							</form>
						</div>
						<div id="mapSearchMenu" class="menu">
							<form>
								<input type="hidden" name="action" value="insertMapSearch" />
								<input type="hidden" name="slug" value="<?php echo iHomefinderShortcodeDispatcher::MAP_SEARCH_SHORTCODE; ?>" />
								<div class="form-group">
									<label class="control-label">Width</label>
									<div class="checkbox">
										<label class="control-label">
											<input type="checkbox" name="fitToWidth" checked onchange="jQuery('#mapSearchWidth').toggle();">
											Fit to column
										</label>
									</div>
									<div class="input-group" style="display: none;" id="mapSearchWidth">
										<input class="form-control" type="text" name="width" />
										<span class="input-group-addon">px</span>
									</div>
								</div>
								<div class="form-group">
									<label class="control-label">Height</label>
									<div class="input-group">
										<input class="form-control" type="number" min="1" name="height" />
										<span class="input-group-addon">px</span>
									</div>
								</div>
								<div class="form-group">
									<label class="control-label">Center Address</label>
									<div>
										<input class="form-control" type="text" name="address" placeholder="e.g., 1900 Addison Street, Berkeley, CA" />
									</div>
								</div>
								<div class="form-group">
									<label class="control-label">Zoom Level</label>
									<div>
										<select class="form-control" name="zoom" required="required">
											<option value="1">1</option>
											<option value="2">2</option>
											<option value="3">3</option>
											<option value="4">4</option>
											<option value="5">5</option>
											<option value="6">6</option>
											<option value="7">7</option>
											<option value="8">8</option>
											<option value="9">9</option>
											<option value="10" selected>10</option>
											<option value="11">11</option>
											<option value="12">12</option>
											<option value="13">13</option>
											<option value="14">14</option>
											<option value="15">15</option>
											<option value="16">16</option>
											<option value="17">17</option>
											<option value="18">18</option>
											<option value="19">19</option>
											<option value="20">20</option>
										</select>
									</div>
								</div>
								<button class="btn btn-default"><?php echo $this->buttonText; ?></button>
							</form>
						</div>
						<div id="eurekaSearchMenu" class="menu">
							<form>
								<input type="hidden" name="action" value="insertMapSearch" />
								<input type="hidden" name="slug" value="<?php echo iHomefinderShortcodeDispatcher::EUREKA_SEARCH_SHORTCODE; ?>" />
									<div class="form-group">
									<label class="control-label">Height (optional)</label>
									<div class="input-group">
										<input class="form-control" type="number" min="1" name="height" />
										<span class="input-group-addon">px</span>
									</div>
								</div>
								<div class="form-group">
									<label class="control-label">Center Address</label>
									<div>
										<input class="form-control" type="text" name="address" placeholder="e.g., 1900 Addison Street, Berkeley, CA" />
									</div>
								</div>
								<div class="form-group">
									<label class="control-label">Zoom Level</label>
									<div>
										<select class="form-control" name="zoom" required="required">
											<option value="1">1</option>
											<option value="2">2</option>
											<option value="3">3</option>
											<option value="4">4</option>
											<option value="5">5</option>
											<option value="6">6</option>
											<option value="7">7</option>
											<option value="8">8</option>
											<option value="9">9</option>
											<option value="10" selected>10</option>
											<option value="11">11</option>
											<option value="12">12</option>
											<option value="13">13</option>
											<option value="14">14</option>
											<option value="15">15</option>
											<option value="16">16</option>
											<option value="17">17</option>
											<option value="18">18</option>
											<option value="19">19</option>
											<option value="20">20</option>
										</select>
									</div>
								</div>
								<button class="btn btn-default"><?php echo $this->buttonText; ?></button>
							</form>
						</div>
						<div id="searchByAddressMenu" class="menu">
							<form>
								<input type="hidden" name="action" value="insertSearchByAddress" />
								<input type="hidden" name="slug" value="<?php echo iHomefinderShortcodeDispatcher::SEARCH_BY_ADDRESS_SHORTCODE; ?>" />
								<div class="form-group">
									<label class="control-label">Style</label>
									<div>
										<select class="form-control" name="style" required="required">
											<option value="">Select One</option>
											<option value="horizontal">Horizontal</option>
											<option value="vertical">Vertical</option>
										</select>
									</div>
								</div>
								<button class="btn btn-default"><?php echo $this->buttonText; ?></button>
							</form>

						</div>
						<div id="searchByListingIdMenu" class="menu">
							<form>
								<input type="hidden" name="action" value="insertSearchByListingId" />
								<input type="hidden" name="slug" value="<?php echo iHomefinderShortcodeDispatcher::SEARCH_BY_LISTING_ID_SHORTCODE; ?>" />
								<button class="btn btn-default"><?php echo $this->buttonText; ?></button>
							</form>
						</div>
					</div>
				</div>
				<div class="tab-pane fade" id="IdxPages">
					<h4></h4>
					<div class="col-xs-4">
						<div class="form-group">
							<?php if($this->displayRules->isBasicSearchEnabled() && !$this->displayRules->isEurekaSearch()) { ?>
								<div class="radio">
									<label class="control-label">
										<input name="shortcodeType" type="radio" onclick="jQuery('.menu').hide(); jQuery('#basicSearchMenu').toggle();">
										Search Form
									</label>
								</div>
							<?php } ?>
							<?php if($this->displayRules->isAdvancedSearchEnabled() && !$this->displayRules->isEurekaSearch()) { ?>
								<div class="radio">
									<label class="control-label">
										<input name="shortcodeType" type="radio" onclick="jQuery('.menu').hide(); jQuery('#advancedSearchMenu').toggle();">
										Advanced Search Form
									</label>
								</div>
							<?php } ?>
							<?php if($this->displayRules->isOrganizerEnabled()) { ?>
								<div class="radio">
									<label class="control-label">
										<input name="shortcodeType" type="radio" onclick="jQuery('.menu').hide(); jQuery('#organizerLoginMenu').toggle();">
										Property Organizer Login
									</label>
								</div>
							<?php } ?>
							<?php if($this->displayRules->isEmailUpdatesEnabled() && !$this->displayRules->isEurekaSearch()) { ?>
								<div class="radio">
									<label class="control-label">
										<input name="shortcodeType" type="radio" onclick="jQuery('.menu').hide(); jQuery('#emailAlertsMenu').toggle();">
										Email Alerts
									</label>
								</div>
							<?php } ?>
							<?php if($this->displayRules->isValuationEnabled()) { ?>
								<div class="radio">
									<label class="control-label">
										<input name="shortcodeType" type="radio" onclick="jQuery('.menu').hide(); jQuery('#valuationFormMenu').toggle();">
										Valuation Form
									</label>
								</div>
							<?php } ?>
							<?php if($this->displayRules->isContactFormEnabled()) { ?>
								<div class="radio">
									<label class="control-label">
										<input name="shortcodeType" type="radio" onclick="jQuery('.menu').hide(); jQuery('#contactFormMenu').toggle();">
										Contact Form
									</label>
								</div>
							<?php } ?>
							<?php if($this->displayRules->isMortgageCalculatorEnabled()) { ?>
								<div class="radio">
									<label class="control-label">
										<input name="shortcodeType" type="radio" onclick="jQuery('.menu').hide(); jQuery('#mortgageCalculatorMenu').toggle();">
										Mortgage Calculator
									</label>
								</div>
							<?php } ?>
						</div>
					</div>
					
					<div class="col-xs-8">
						<div id="basicSearchMenu" class="menu">
							<form>
								<input type="hidden" name="action" value="insertBasicSearch" />
								<input type="hidden" name="slug" value="<?php echo iHomefinderShortcodeDispatcher::BASIC_SEARCH_SHORTCODE; ?>" />
								<button class="btn btn-default"><?php echo $this->buttonText; ?></button>
							</form>
						</div>
						<div id="advancedSearchMenu" class="menu">
							<form>
								<input type="hidden" name="action" value="insertAdvancedSearch" />
								<input type="hidden" name="slug" value="<?php echo iHomefinderShortcodeDispatcher::ADVANCED_SEARCH_SHORTCODE; ?>" />
									<?php if($this->getBoardCount() > 1) { ?>
										<div class="form-group">
											<label class="control-label">Board</label>
											<div>
												<?php $this->createBoardSelect(false); ?>
											</div>
										</div>
									<?php }elseif($this->getBoardCount() === 1){ ?>
										<?php $this->createBoardSelect(false); ?>
									<?php } ?>
								<button class="btn btn-default"><?php echo $this->buttonText; ?></button>
							</form>
						</div>
						<div id="organizerLoginMenu" class="menu">
							<form>
								<input type="hidden" name="action" value="insertOrganizerLogin" />
								<input type="hidden" name="slug" value="<?php echo iHomefinderShortcodeDispatcher::ORGANIZER_LOGIN_SHORTCODE; ?>" />
								<button class="btn btn-default"><?php echo $this->buttonText; ?></button>
							</form>
						</div>
						<div id="emailAlertsMenu" class="menu">
							<form>
								<input type="hidden" name="action" value="insertEmailAlerts" />
								<input type="hidden" name="slug" value="<?php echo iHomefinderShortcodeDispatcher::EMAIL_ALERTS_SHORTCODE; ?>" />
								<button class="btn btn-default"><?php echo $this->buttonText; ?></button>
							</form>
						</div>
						<div id="valuationFormMenu" class="menu">
							<form>
								<input type="hidden" name="action" value="insertValuationForm" />
								<input type="hidden" name="slug" value="<?php echo iHomefinderShortcodeDispatcher::VALUATION_FORM_SHORTCODE; ?>" />
								<button class="btn btn-default"><?php echo $this->buttonText; ?></button>
							</form>
						</div>
						<div id="mortgageCalculatorMenu" class="menu">
							<form>
								<input type="hidden" name="action" value="insertMortgageCalculator" />
								<input type="hidden" name="slug" value="<?php echo iHomefinderShortcodeDispatcher::MORTGAGE_CALCULATOR_SHORTCODE; ?>" />
								<button class="btn btn-default"><?php echo $this->buttonText; ?></button>
							</form>
						</div>
						<div id="contactFormMenu" class="menu">
							<form>
								<input type="hidden" name="action" value="insertContactForm" />
								<input type="hidden" name="slug" value="<?php echo iHomefinderShortcodeDispatcher::CONTACT_FORM_SHORTCODE; ?>" />
								<button class="btn btn-default"><?php echo $this->buttonText; ?></button>
							</form>
						</div>
					</div>
				</div>
				<div class="tab-pane fade" id="Broker">
					<h4></h4>
					<div class="col-xs-5">
						<div class="form-group">
							<?php if($this->displayRules->isAgentBioEnabled()) { ?>
								<div class="radio">
									<label class="control-label">
										<input name="shortcodeType" type="radio" onclick="jQuery('.menu').hide(); jQuery('#agentDetailMenu').toggle();">
										Agent Bio
									</label>
								</div>
							<?php } ?>
							<?php if($this->displayRules->isAgentBioEnabled()) { ?>
								<div class="radio">
									<label class="control-label">
										<input name="shortcodeType" type="radio" onclick="jQuery('.menu').hide(); jQuery('#agentListMenu').toggle();">
										Agent List
									</label>
								</div>
							<?php } ?>
							<?php if($this->displayRules->isOfficeEnabled()) { ?>
								<div class="radio">
									<label class="control-label">
										<input name="shortcodeType" type="radio" onclick="jQuery('.menu').hide(); jQuery('#officeListMenu').toggle();">
										Office List
									</label>
								</div>
							<?php } ?>
						</div>
					</div>
					<div class="col-xs-7">
						<div id="agentDetailMenu" class="menu">
							<form>
								<input type="hidden" name="action" value="insertAgentDetail" />
								<input type="hidden" name="slug" value="<?php echo iHomefinderShortcodeDispatcher::AGENT_DETAIL_SHORTCODE; ?>" />
								<div class="form-group">
									<label class="control-label">Agent</label>
									<div>
										<?php $this->createAgentSelect(true); ?>
									</div>
								</div>
								<button class="btn btn-default"><?php echo $this->buttonText; ?></button>
							</form>
						</div>
						<div id="agentListMenu" class="menu">
							<form>
								<input type="hidden" name="action" value="insertAgentList" />
								<input type="hidden" name="slug" value="<?php echo iHomefinderShortcodeDispatcher::AGENT_LIST_SHORTCODE; ?>" />
								<button class="btn btn-default"><?php echo $this->buttonText; ?></button>
							</form>
						</div>
						<div id="officeListMenu" class="menu">
							<form>
								<input type="hidden" name="action" value="insertOfficeList" />
								<input type="hidden" name="slug" value="<?php echo iHomefinderShortcodeDispatcher::OFFICE_LIST_SHORTCODE; ?>" />
								<button class="btn btn-default"><?php echo $this->buttonText; ?></button>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
		<?php
	}
	
	private function createAgentSelect($required = false) {
		$values = $this->formData->getAgents();
		?>
		<select class="form-control" id="agentId" name="agentId"
			<?php if($required === true) { ?>
				required="required"
			<?php } ?>
		>
			<option value="">Select One</option>
			<?php foreach($values as $index => $value) { ?>
				<option value="<?php echo $value->agentId ?>">
					<?php echo $value->agentName ?>
				</option>
			<?php } ?>
		</select>
		<?php
	}
	
	private function createBoardSelect($required = false) {
		$values = $this->formData->getBoards();
		if($this->getBoardCount() === 1){
			$value = $values[0];
			?>
			<input id="boardId" name="boardId" value="<?php echo $value->boardId ?>" type="hidden" />
			<?php
		} elseif ($this->getBoardCount() > 1){
			?>
			<select class="form-control" id="boardId" name="boardId"
				<?php if($required === true) { ?>
					required="required"
				<?php } ?>
			>
				<option value="">Select One</option>
				<?php foreach($values as $index => $value) { ?>
					<option value="<?php echo $value->boardId ?>">
						<?php echo $value->boardName ?>
					</option>
				<?php } ?>
			</select>
			<?php
		}	
	}
	
	private function getBoardCount() { 
		$values = $this->formData->getBoards();
		$result = count($values);
		return $result;		
	}
	
	private function createOfficeSelect($required = false) {
		$values = $this->formData->getOffices();
		?>
		<select class="form-control" id="officeId" name="officeId"
			<?php if($required === true) { ?>
				required="required"
			<?php } ?>
		>
			<option value="">Select One</option>
			<?php foreach($values as $index => $value) { ?>
				<option value="<?php echo $value->officeId ?>">
					<?php echo $value->officeName ?>
				</option>
			<?php } ?>
		</select>
		<?php
	}
	
	private function createHotSheetsSelect($required = false) {
		$values = $this->formData->getHotSheets();
		?>
		<select class="form-control" id="hotSheetId" name="hotSheetId"
			<?php if($required === true) { ?>
				required="required"
			<?php } ?>
		>
			<option value="">Select One</option>
			<?php foreach($values as $index => $value) { ?>
				<option value="<?php echo $value->hotsheetId ?>">
					<?php echo $value->displayName ?>
				</option>
			<?php } ?>
		</select>
		<?php
	}
	
	private function createCitySelect($required = false) {
		$values = $this->formData->getCities();
		?>
		<select class="form-control" id="cityId" name="cityId"
			<?php if($required === true) { ?>
				required="required"
			<?php } ?>
		>
			<option value="">Select One</option>
			<?php foreach($values as $index => $value) { ?>
				<option value="<?php echo $value->cityId ?>">
					<?php echo $value->displayName ?>
				</option>
			<?php } ?>
		</select>
		<?php
	}
	
	private function createPropertyTypeSelect($required = false) {
		$values = $this->formData->getPropertyTypes();
		?>
		<select class="form-control" id="propertyType" name="propertyType"
			<?php if($required === true) { ?>
				required="required"
			<?php } ?>
		>
			<option value="">Select One</option>
			<?php foreach($values as $index => $value) { ?>
				<option value="<?php echo $value->propertyTypeCode ?>">
					<?php echo $value->displayName ?>
				</option>
			<?php } ?>
		</select>
		<?php
	}
	
	private function createSortSelect($required = false) {
		?>
		<select class="form-control" id="sortBy" name="sortBy"
			<?php if($required === true) { ?>
				required="required"
			<?php } ?>
		>	
			<option value="">Select One</option>
			<option value="pd">Price (High to Low)</option>
			<option value="pa">Price (Low to High)</option>
			<option value="st">Status</option>
			<option value="cn">City</option>
			<option value="ds">Listing Date</option>
			<option value="lpd">Type / Price Descending</option>
			<option value="ln">Listing Number</option>
		</select>
		<?php
	}
	
	private function createDisplayTypeSelect($required = false) {
		?>
		<select class="form-control" id="displayType" name="displayType"
			<?php if($required === true) { ?>
				required="required"
			<?php } ?>
		>
			<option value="">Default</option>
			<option value="list">List</option>
			<option value="grid">Grid</option>
		</select>
		<?php
	}
	
	private function createHeaderSelect($required = false) {
		?>
		<select class="form-control" id="header" name="header"
			<?php if($required === true) { ?>
				required="required"
			<?php } ?>
		>
			<option value="true">Yes</option>
			<option value="false">No</option>
		</select>
		<?php
	}
	
	private function createHotSheetReportTypeSelect($required = false) {
		?>
		<select class="form-control" id="hotSheetReportType" name="hotSheetReportType"
			<?php if($required === true) { ?>
				required="required"
			<?php } ?>
		>
			<option value="">Select One</option>
			<option value="listing">Listing Report</option>
			<option value="openHome">Open Home Report</option>
			<option value="market">Market Report</option>
		</select>
		<?php
	}
	
	private function createStatusSelect($required = false) {
		?>
		<select class="form-control" id="status" name="status"
			<?php if($required === true) { ?>
				required="required"
			<?php } ?>
		>
			<option value="active">Active</option>
			<option value="sold">Sold</option>
		</select>
		<?php
	}
	
}