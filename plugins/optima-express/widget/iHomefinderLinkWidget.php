<?php

class iHomefinderLinkWidget extends iHomefinderWidget {
	
	public function __construct() {
		parent::__construct(
			"iHomefinderLinkWidget",
			"IDX: SEO City Links",
			array(
				"description" => "Configure indexable links to listings in the areas you serve."
			)
		);
	}
	
	public function widget($args, $instance) {
		$instance = $this->migrate($instance);
		if($this->isEnabled($instance)) {
			$beforeWidget = $args["before_widget"];
			$afterWidget = $args["after_widget"];
			$linkWidth = get_option(IHomefinderConstants::SEO_CITY_LINK_WIDTH, null);
			echo $beforeWidget;
			$linkArray = get_option(iHomefinderConstants::SEO_CITY_LINKS_SETTINGS, null);
			if(!empty($linkArray)) {
				?>
				<div>
				<?php
				foreach($linkArray as $link) {
					$linkText = $link[iHomefinderConstants::SEO_CITY_LINKS_TEXT];
					$cityZip = $link[iHomefinderConstants::SEO_CITY_LINKS_CITY_ZIP];
					$propertyType = $link[iHomefinderConstants::SEO_CITY_LINKS_PROPERTY_TYPE];
					$minPrice = $link[iHomefinderConstants::SEO_CITY_LINKS_MIN_PRICE];
					$maxPrice = $link[iHomefinderConstants::SEO_CITY_LINKS_MAX_PRICE];
					if(!empty($linkText)) {
						$searchLinkInfo = new iHomefinderSearchLinkInfo($linkText, $cityZip, $propertyType, $minPrice, $maxPrice);
						$linkUrl = $this->createLinkUrl($searchLinkInfo);
						?>
						<div class="ihf-seo-link" style="
							<?php if(!is_null($linkWidth) && !empty($linkWidth)) { ?> 
								width: <?php echo $linkWidth; ?>px;
							<?php } ?>
						">
							<a href="<?php echo $linkUrl ?>">
								<?php echo $searchLinkInfo->getLinkText(); ?>
							</a>
						</div>
						<?php
					}
				}
				?>
				</div>
				<?php
			}
			echo $afterWidget;
		}
	}
	
	public function update($newInstance, $oldInstance) {
		$newInstance = $this->migrate($newInstance);
		$oldInstance = $this->migrate($oldInstance);
		$instance = $newInstance;
		return $instance;
	}
	
	public function form($instance) {
		$instance = $this->migrate($instance);
		$configurationUrl = admin_url("admin.php?page=" . iHomefinderConstants::PAGE_SEO_CITY_LINKS);
		?>
		<p>
			<a href="<?php echo $configurationUrl ?>">Configure City Links</a>
		</p>
		<?php
	}
	
	private function createLinkUrl($searchLinkInfo) {
		$displayRules = iHomefinderDisplayRules::getInstance();
		$formData = iHomefinderFormData::getInstance();
		$resultsUrl = iHomefinderUrlFactory::getInstance()->getListingsSearchResultsUrl(true);
		$data = array();
		if($searchLinkInfo->hasPostalCode()) {
			$data["zip"] = $searchLinkInfo->getPostalCode();
		} else {
			if($displayRules->isKestrel()) {
				$cityId = $formData->getCityIdFromCityName($searchLinkInfo->getCity());
				$data["cityId"] = $cityId;
			} else {
				$data["city"] = $searchLinkInfo->getCity();
			}
		}	
		if($searchLinkInfo->hasState()) {
			$data["state"] = $searchLinkInfo->getState();
		}
		$data["propertyType"] = $searchLinkInfo->getPropertyType();
		if($searchLinkInfo->getMinPrice() != null) {
			$data["minListPrice"] = $searchLinkInfo->getMinPrice();
		}
		if($searchLinkInfo->getMaxPrice() != null) {
			$data["maxListPrice"] = $searchLinkInfo->getMaxPrice();
		}
		if(iHomefinderDisplayRules::getInstance()->isEurekaSearch()) {
			$data["staticView"] = "true";
		}
		$linkUrl = $resultsUrl . "?" . http_build_query($data);
		return $linkUrl;
	}
}
