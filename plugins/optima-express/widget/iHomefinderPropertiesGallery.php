<?php

class iHomefinderPropertiesGallery extends iHomefinderWidget {
	
	private $displayRules;
	private $urlFactory;
	private $enqueueResource;
	
	public function __construct() {
		parent::__construct(
			"iHomefinderPropertiesGallery",
			"IDX: Property Gallery",
			array(
				"description" => "Display a list of properties."
			)
		);
		$this->displayRules = iHomefinderDisplayRules::getInstance();
		$this->urlFactory = iHomefinderUrlFactory::getInstance();
		$this->enqueueResource = iHomefinderEnqueueResource::getInstance();
	}
	
	public function widget($args, $instance) {
		$instance = $this->migrate($instance);
		if($this->isEnabled($instance)) {
			$galleryType = $instance["galleryType"];
			switch ($galleryType) {
				case "hotSheet":
					$this->hotSheet($args, $instance);
					break;
				case "featuredListing":
					$this->featuredListing($args, $instance);
					break;
				case "namedSearch":
					$this->namedSearch($args, $instance);
					break;
				case "linkSearch":
					$this->linkSearch($args, $instance);
					break;
			}
		}
	}
	
	private function hotSheet($args, $instance) {
		if($this->displayRules->isHotSheetEnabled()) {
			$beforeWidget = $args["before_widget"];
			$afterWidget = $args["after_widget"];
			$beforeTitle = $args["before_title"];
			$afterTitle = $args["after_title"];
			$title = apply_filters("widget_title", $instance["name"]);
			$numListingsLimit = empty($instance["propertiesShown"]) ? "5" : $instance["propertiesShown"];
			$hotSheetId = esc_attr($instance["hotSheetId"]);
			$linkText = esc_attr($instance["linkText"]);
			//link to all listings in the hotsheet
			$nameInUrl = preg_replace("[^A-Za-z0-9-]", "-", $title);
			$nameInUrl = str_replace(" ", "-", $nameInUrl);
			$linkUrl = $this->urlFactory->getHotSheetListingReportUrl(true) . "/" . $nameInUrl . "/" . $hotSheetId;
			if($this->displayRules->isKestrelAll()) {
				$content = iHomefinderKestrelWidget::getPropertiesGalleryWidgetHotSheet($hotSheetId, $numListingsLimit);
			} else {
				$remoteRequest = new iHomefinderRequestor();
				$remoteRequest
					->addParameter("requestType", "hotsheet-results")
					->addParameter("startRowNumber", 1)
					->addParameter("numListingsLimit", $numListingsLimit)
					->addParameter("hotSheetId", $hotSheetId)
					->addParameter("smallView", true)
				;
				if($this->displayRules->isNoId() === true) {
					$remoteRequest->addParameter("noId", true);
				}
				$remoteRequest->setCacheExpiration(60*30);
				$remoteResponse = $remoteRequest->remoteGetRequest();
				$content = $remoteResponse->getBody();
				$this->enqueueResource->addToFooter($remoteResponse->getHead());
			}
			echo $beforeWidget;
			if(!empty($title)) {
				echo $beforeTitle . $title . $afterTitle;
			}
			echo $content;
			if(!$this->displayRules->isKestrelAll()) {
				echo "<a href='" . $linkUrl. "'>" . $linkText . "</a>";
			}
			echo $afterWidget;
		}
	 }

	 private function featuredListing($args, $instance) {
		 if($this->displayRules->isFeaturedPropertiesEnabled()) {
			$beforeWidget = $args["before_widget"];
			$afterWidget = $args["after_widget"];
			$beforeTitle = $args["before_title"];
			$afterTitle = $args["after_title"];
			$title = apply_filters("widget_title", $instance["name"]);
			$numListingsLimit = empty($instance["propertiesShown"]) ? "5" : $instance["propertiesShown"];
			$propertyType = empty($instance["propertyType"]) ? null : $instance["propertyType"];
			$linkText = esc_attr($instance["linkText"]);
			//link to all featured properties
			$linkUrl = $this->urlFactory->getFeaturedSearchResultsUrl(true);
			if(!empty($propertyType)) {
				$linkUrl .= "?propertyType=" . $propertyType;
			}
			if($this->displayRules->isKestrelAll()) {
				$content = iHomefinderKestrelWidget::getPropertiesGalleryWidgetFeatured($propertyType, $numListingsLimit);
			} else {
				$remoteRequest = new iHomefinderRequestor();
				$remoteRequest
					->addParameter("requestType", "featured-search")
					->addParameter("startRowNumber", 1)
					->addParameter("numListingsLimit", $numListingsLimit)
					->addParameter("propertyType", $propertyType)
					->addParameter("smallView", true)
				;
				if($this->displayRules->isNoId() === true) {
					$remoteRequest->addParameter("noId", true);
				}
				$remoteRequest->setCacheExpiration(60*30);
				$remoteResponse = $remoteRequest->remoteGetRequest();
				$content = $remoteResponse->getBody();
				$this->enqueueResource->addToFooter($remoteResponse->getHead());
			}
			echo $beforeWidget;
			if(!empty($title)) {
				echo $beforeTitle . $title . $afterTitle;
			}
			echo $content;
			if(!$this->displayRules->isKestrelAll()) {
				echo "<a href='" . $linkUrl. "'>" . $linkText . "</a>";
			}
			echo $afterWidget;
		 }
	 }

	 private function linkSearch($args, $instance) {
		if($this->displayRules->isLinkSearchEnabled()) {
			$title = apply_filters("widget_title", $instance["name"]);
			$beforeWidget = $args["before_widget"];
			$afterWidget = $args["after_widget"];
			$beforeTitle = $args["before_title"];
			$afterTitle = $args["after_title"];
			$cityId = esc_attr($instance["cityId"]);
			$bed = esc_attr($instance["bed"]);
			$bath = esc_attr($instance["bath"]);
			$minPrice = esc_attr($instance["minPrice"]);
			$maxPrice = esc_attr($instance["maxPrice"]);
			$propertyType = esc_attr($instance["propertyType"]);
			$numListingsLimit = empty($instance["propertiesShown"]) ? "5" : $instance["propertiesShown"];
			$linkText = esc_attr($instance["linkText"]);
			$resultsUrl = $this->urlFactory->getListingsSearchResultsUrl(true);
			$searchParams = array(
				"cityId" => $cityId,
				"propertyType" => $propertyType,
				"bedrooms" => $bed,
				"bathCount" => $bath,
				"minListPrice" => $minPrice,
				"maxListPrice" => $maxPrice
			);
			$linkUrl = iHomefinderUtility::getInstance()->buildUrl($resultsUrl, $searchParams);
			echo $beforeWidget;
			echo $beforeTitle;
			echo "<a href='" . $linkUrl. "'>" . $linkText . "</a>";
			echo $afterTitle;
			echo $afterWidget;
		}
	}


	 private function namedSearch($args, $instance) {
		if($this->displayRules->isNamedSearchEnabled()) {
			$title = apply_filters("widget_title", $instance["name"]);
			$beforeWidget = $args["before_widget"];
			$afterWidget = $args["after_widget"];
			$beforeTitle = $args["before_title"];
			$afterTitle = $args["after_title"];
			$cityId = esc_attr($instance["cityId"]);
			$bed = esc_attr($instance["bed"]);
			$bath = esc_attr($instance["bath"]);
			$minPrice = esc_attr($instance["minPrice"]);
			$maxPrice = esc_attr($instance["maxPrice"]);
			$propertyType = esc_attr($instance["propertyType"]);
			$numListingsLimit = empty($instance["propertiesShown"]) ? "5" : $instance["propertiesShown"];
			$linkText = esc_attr($instance["linkText"]);
			$resultsUrl = $this->urlFactory->getListingsSearchResultsUrl(true);
			$searchParams = array(
				"cityId" => $cityId,
				"propertyType" => $propertyType,
				"bedrooms" => $bed,
				"bathCount" => $bath,
				"minListPrice" => $minPrice,
				"maxListPrice" => $maxPrice
			);
			$linkUrl = iHomefinderUtility::getInstance()->buildUrl($resultsUrl, $searchParams);
			if($this->displayRules->isKestrelAll()) {
				$content = iHomefinderKestrelWidget::getPropertiesGalleryWidget($cityId, $bed, $bath, $minPrice, $maxPrice, $propertyType, $numListingsLimit);
			} else {
				$remoteRequest = new iHomefinderRequestor();
				$remoteRequest
					->addParameter("requestType", "listing-search-results")
					->addParameter("cityId", $cityId)
					->addParameter("bedrooms", $bed)
					->addParameter("bathcount", $bath)
					->addParameter("minListPrice", $minPrice)
					->addParameter("maxListPrice", $maxPrice)
					->addParameter("propertyType", $propertyType)
					->addParameter("numListingsLimit", $numListingsLimit)
					->addParameter("smallView", true)
				;
				if($this->displayRules->isNoId() === true) {
					$remoteRequest->addParameter("noId", true);
				}
				$remoteRequest->setCacheExpiration(60*30);
				$remoteResponse = $remoteRequest->remoteGetRequest();
				$content = $remoteResponse->getBody();
				$this->enqueueResource->addToFooter($remoteResponse->getHead());
			}
			echo $beforeWidget;
			if(!empty($title)) {
				echo $beforeTitle . $title . $afterTitle;
			}
			echo $content;
			if(!$this->displayRules->isKestrelAll()) {
				echo "<a href='" . $linkUrl. "'>" . $linkText . "</a>";
			}
			echo $afterWidget;
		}
	}
	 
	public function update($newInstance, $oldInstance) {
		$newInstance = $this->migrate($newInstance);
		$oldInstance = $this->migrate($oldInstance);
		$instance = $oldInstance;
		$instance["galleryType"] = strip_tags(stripslashes($newInstance["galleryType"]));
		$instance["listingID"] = strip_tags(stripslashes($newInstance["listingID"]));
		$instance["name"] = strip_tags(stripslashes($newInstance["name"]));
		$instance["propertiesShown"] = strip_tags(stripslashes($newInstance["propertiesShown"]));
		$instance["cityId"] = strip_tags(stripslashes($newInstance["cityId"]));
		$instance["propertyType"] = strip_tags(stripslashes($newInstance["propertyType"]));
		$instance["bed"] = strip_tags(stripslashes($newInstance["bed"]));
		$instance["bath"] = strip_tags(stripslashes($newInstance["bath"]));
		$instance["minPrice"] = strip_tags(stripslashes($newInstance["minPrice"]));
		$instance["maxPrice"] = strip_tags(stripslashes($newInstance["maxPrice"]));
		$instance["hotSheetId"] = strip_tags(stripslashes($newInstance["hotSheetId"]));
		$instance["linkText"] = strip_tags(stripslashes($newInstance["linkText"]));
		$instance = $this->updateContext($newInstance, $instance);
		return $instance;
	}
	
	public function form($instance) {
		$instance = $this->migrate($instance);
		$galleryType = ($instance) ? esc_attr($instance["galleryType"]) : null;
		$listingID = ($instance) ? esc_attr($instance["listingID"]) : null;
		$name = ($instance) ? esc_attr($instance["name"]) : null;
		$propertiesShown = ($instance) ? esc_attr($instance["propertiesShown"]) : "3";
		$cityId = ($instance) ? esc_attr($instance["cityId"]) : null;
		$propertyType = ($instance) ? esc_attr($instance["propertyType"]) : null;
		$bed = ($instance) ? esc_attr($instance["bed"]) : null;
		$bath = ($instance) ? esc_attr($instance["bath"]) : null;
		$minPrice = ($instance) ? esc_attr($instance["minPrice"]) : null;
		$maxPrice = ($instance) ? esc_attr($instance["maxPrice"]) : null;
		$hotSheetId = ($instance) ? esc_attr($instance["hotSheetId"]) : null;
		$linkText = ($instance) ? esc_attr($instance["linkText"]) : "View all";
		$formData = iHomefinderFormData::getInstance();
		$hotSheets = $formData->getHotSheets();
		$cities = $formData->getCities();
		$propertyTypes = $formData->getPropertyTypes();
		?>
		<p class="galleryType">
			<label>Gallery type:</label>
			<br />
			<?php if($this->displayRules->isFeaturedPropertiesEnabled()) { ?>
				<label>
					<input type="radio" value="featuredListing" name="<?php echo $this->get_field_name("galleryType"); ?>" />
					Featured Properties Gallery
				</label>
				<br />
			<?php } ?>
			<?php if($this->displayRules->isHotSheetEnabled()) { ?>
				<label>
					<input type="radio" value="hotSheet" name="<?php echo $this->get_field_name("galleryType"); ?>" />
					Market Gallery
				</label>
				<br />
			<?php } ?>
			<?php if($this->displayRules->isNamedSearchEnabled()) { ?>
				<label>
					<input type="radio" value="namedSearch" name="<?php echo $this->get_field_name("galleryType"); ?>" />
					Dynamic Search Gallery
				</label>
				<br />
			<?php } ?>
			<?php if($this->displayRules->isLinkSearchEnabled()) { ?>
				<label>
					<input type="radio" value="linkSearch" name="<?php echo $this->get_field_name("galleryType"); ?>" />
					Dynamic Search Link
				</label>
			<?php } ?>
		</p>
		<p class="name" style="display: none;">
			<label>
				Gallery Title:
				<input class="widefat" type="text" value="<?php echo $name; ?>" name="<?php echo $this->get_field_name("name"); ?>" />
			</label>
		</p>
		<p class="propertiesShown" style="display: none;">
			<label>
				Number of Properties Shown:
				<select class="widefat" name="<?php echo $this->get_field_name("propertiesShown"); ?>">
					<?php for($index = 1; $index < 11; $index += 1) { ?>
						<option value="<?php echo $index; ?>"
							<?php if($propertiesShown == $index) { ?>
								selected="selected"
							<?php } ?>
						>
							<?php echo $index; ?>
						</option>
					<?php } ?>
				</select>
			</label>
		</p>
		<p class="linkText" style="display: none;">
			<label>
				Link Text:
				<input class="widefat" type="text" value="<?php echo $linkText; ?>" name="<?php echo $this->get_field_name("linkText"); ?>" />
			</label>
		</p>
		<p class="hotSheetId" style="display: none;">
			<label>
				Market:
				<select class="widefat" name="<?php echo $this->get_field_name("hotSheetId"); ?>">
					<?php foreach ($hotSheets as $index => $value) { ?>
						<option value="<?php echo (string) $hotSheets[$index]->hotsheetId; ?>"
							<?php if($hotSheets[$index]->hotsheetId == $hotSheetId) { ?>
								selected="selected"
							<?php } ?>
						>
							<?php echo (string) $hotSheets[$index]->displayName; ?>
						</option>
					<?php } ?>
				</select>
			</label>
		</p>
		<p class="cityId" style="display: none;">
			<label>
				City:
				<select class="widefat" style="height: 100px;" name="<?php echo $this->get_field_name("cityId"); ?>" size="5">
					<?php foreach ($cities as $index => $value) { ?>
						<option value="<?php echo $cities[$index]->cityId; ?>"
							<?php if($cities[$index]->cityId == $cityId) { ?>
								selected="selected"
							<?php } ?>
						> 
							<?php echo $cities[$index]->displayName; ?>
						</option>
					<?php } ?>
				</select>
			</label>
		</p>
		<p class="propertyType" style="display: none;">
			<label>
				Property Type:
				<select class="widefat" name="<?php echo $this->get_field_name("propertyType"); ?>" >
					<option value="">Select One</option>
					<?php foreach ($propertyTypes as $index => $value) { ?>
						<option value="<?php echo (string) $propertyTypes[$index]->propertyTypeCode; ?>"
							<?php if($propertyTypes[$index]->propertyTypeCode == $propertyType) { ?>
								selected="selected"
							<?php } ?>
						>
							<?php echo (string) $propertyTypes[$index]->displayName; ?>
						</option>
					<?php } ?>
				</select>
			</label>
		</p>
		<p class="bed" style="display: none;">
			<label>
				Bed:
				<input class="widefat" type="number" value="<?php echo $bed; ?>" name="<?php echo $this->get_field_name("bed"); ?>" />
			</label>
		</p>
		<p class="bath" style="display: none;">
			<label>
				Bath:
				<input class="widefat" type="number" value="<?php echo $bath; ?>" name="<?php echo $this->get_field_name("bath"); ?>" />
			</label>
		</p>
		<p class="minPrice" style="display: none;">
			<label>
				Minimum Price:
				<input class="widefat" type="number" value="<?php echo $minPrice; ?>" name="<?php echo $this->get_field_name("minPrice"); ?>" />
			</label>
		</p>
		<p class="maxPrice" style="display: none;">
			<label>
				Maximum Price:
				<input class="widefat" type="number" value="<?php echo $maxPrice; ?>" name="<?php echo $this->get_field_name("maxPrice"); ?>" />
			</label>
		</p>
		<div class="pageSelector" style="display: none;">
			<?php $this->getPageSelector($instance); ?>
			<br />
		</div>
		<script type="text/javascript">
			function togglePropertyFormFields(galleryType) {
				if(galleryType) {
					jQuery(".galleryType [value='" + galleryType + "']").attr("checked", "checked");
				}
				if(galleryType === "hotSheet") {
					jQuery(".name").show();
					jQuery(".propertiesShown").show();
					jQuery(".linkText").show();
					jQuery(".hotSheetId").show();
					jQuery(".propertyType").hide();
					jQuery(".cityId").hide();
					jQuery(".bed").hide();
					jQuery(".bath").hide();
					jQuery(".minPrice").hide();
					jQuery(".maxPrice").hide();
					jQuery(".pageSelector").show();
				} else if(galleryType === "namedSearch") {
					jQuery(".name").show();
					jQuery(".propertiesShown").show();
					jQuery(".linkText").show();
					jQuery(".hotSheetId").hide();
					jQuery(".cityId").show();
					jQuery(".propertyType").show();
					jQuery(".bed").show();
					jQuery(".bath").show();
					jQuery(".minPrice").show();
					jQuery(".maxPrice").show();
					jQuery(".pageSelector").show();
				} else if(galleryType === "linkSearch") {
					jQuery(".name").hide();
					jQuery(".propertiesShown").hide();
					jQuery(".linkText").show();
					jQuery(".hotSheetId").hide();
					jQuery(".cityId").show();
					jQuery(".propertyType").show();
					jQuery(".bed").show();
					jQuery(".bath").show();
					jQuery(".minPrice").show();
					jQuery(".maxPrice").show();
					jQuery(".pageSelector").show();
				} else if(galleryType === "featuredListing") {
					jQuery(".name").show();
					jQuery(".propertiesShown").show();
					jQuery(".linkText").show();
					jQuery(".hotSheetId").hide();
					jQuery(".cityId").hide();
					jQuery(".propertyType").show();
					jQuery(".bed").hide();
					jQuery(".bath").hide();
					jQuery(".minPrice").hide();
					jQuery(".maxPrice").hide();
					jQuery(".pageSelector").show();
				}
			}
			var galleryType = "<?php echo $galleryType; ?>";
			togglePropertyFormFields(galleryType);
			jQuery(".galleryType input").on("change", function() {
				togglePropertyFormFields(jQuery(this).val());
			});
		</script>
		<?php
	}

}
