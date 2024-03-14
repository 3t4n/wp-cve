<?php

class iHomefinderQuickSearchWidget extends iHomefinderWidget {
	
	private $enqueueResource;
	private $displayRules;
	
	public function __construct() {
		parent::__construct(
			"iHomefinderQuickSearchWidget",
			"IDX: Quick Search",
			array(
				"description" => "Property Search form."
			)
		);
		$this->enqueueResource = iHomefinderEnqueueResource::getInstance();
		$this->displayRules = iHomefinderDisplayRules::getInstance();
	}
	
	public function widget($args, $instance) {
		$instance = $this->migrate($instance);
		if($this->isEnabled($instance)) {
			$style = $instance["style"];
			$showPropertyType = $instance["showPropertyType"];
			$beforeWidget = $args["before_widget"];
			$afterWidget = $args["after_widget"];
			$beforeTitle = $args["before_title"];
			$afterTitle = $args["after_title"];
			$title = apply_filters("widget_title", $instance["title"]);
			if($this->displayRules->isKestrelAll()) {
				$content = iHomefinderKestrelWidget::getQuickSearchWidget($style, $showPropertyType);
			} else {
				$remoteRequest = new iHomefinderRequestor();
				$remoteRequest
					->addParameter("requestType", "listing-search-form")
					->addParameter("smallView", true)
					->addParameter("style", $style)
					->addParameter("showPropertyType", $showPropertyType)
				;
				if($this->displayRules->isNoId() === true) {
					$remoteRequest->addParameter("noId", true);
				}
				$remoteRequest->setCacheExpiration(60*60*24);
				$remoteResponse = $remoteRequest->remoteGetRequest();
				$content = $remoteResponse->getBody();
				$this->enqueueResource->addToFooter($remoteResponse->getHead());
			}
			echo $beforeWidget;
			if(!empty($title)) {
				echo $beforeTitle . $title . $afterTitle;
			}
			echo $content;
			echo $afterWidget;
		}
	}
	
	public function update($newInstance, $oldInstance) {
		$newInstance = $this->migrate($newInstance);
		$oldInstance = $this->migrate($oldInstance);
		$instance = $oldInstance;
		$instance["title"] = strip_tags(stripslashes($newInstance["title"]));
		$instance["style"] = strip_tags(stripslashes($newInstance["style"]));
		$instance["showPropertyType"] = $newInstance["showPropertyType"];
		$instance = $this->updateContext($newInstance, $instance);
		return $instance;
	}
	
	public function form($instance) {
		$instance = $this->migrate($instance);
		$title = null;
		if (array_key_exists("title", $instance)) {
			$title = esc_attr($instance["title"]);
		}
		$style = null;
		if (array_key_exists("style", $instance)) {
			$style = esc_attr($instance["style"]);
		}
		$showPropertyType = null;
		if (array_key_exists("showPropertyType", $instance)) {
			$showPropertyType = $instance["showPropertyType"];
		}
		?>
		<p>
			<label>
				Title:
				<input class="widefat" id="<?php echo $this->get_field_id("title"); ?>" name="<?php echo $this->get_field_name("title"); ?>" type="text" value="<?php 
					if(isset($title)) {
						echo $title;
					}
				?>" />
			</label>
		</p>
		<p>
			<label>
				Style:
				<select
					class="widefat"
					id="<?php echo $this->get_field_id("style"); ?>"
					name="<?php echo $this->get_field_name("style"); ?>"
					onchange="this.value && this.value !== 'universal' ? jQuery(this).closest('form').find('.propertyType').show() : jQuery(this).closest('form').find('.propertyType').hide()"
				>
					<option>Select One</option>
					<?php if($this->displayRules->supportsUniversalQuickSearchLayout()) { ?>
						<option value="universal" <?php if($style == "universal") {echo "selected";} ?>>Universal</option>
					<?php } ?>
					<option value="vertical" <?php if($style == "vertical") {echo "selected";} ?>>Vertical</option>
					<option value="horizontal" <?php if($style == "horizontal") {echo "selected";} ?>>Horizontal</option>
					<option value="twoline" <?php if($style == "twoline") {echo "selected";} ?>>Two Line</option>
				</select>
			</label> 		
		</p>
		<p class="propertyType" <?php if($style == "universal") {?>style="display: none;"<?php } ?>>
			<label>
				<input type="checkbox" name="<?php echo $this->get_field_name("showPropertyType"); ?>" value="true" <?php if($showPropertyType === "true") {echo "checked";} ?> />
				<span>Show Property Type</span>
			</label>
		</p>
		<?php 
		$this->getPageSelector($instance);
		?>
		<br />
		<?php
	}
	
	protected function isEnabled($instance) {
		$result = parent::isEnabled($instance);
		$virtualPageType = get_query_var(iHomefinderConstants::IHF_TYPE_URL_VAR);
		switch($virtualPageType) {
			case iHomefinderVirtualPageFactory::LISTING_SEARCH_FORM:
				$result = false;
				break;
			case iHomefinderVirtualPageFactory::LISTING_ADVANCED_SEARCH_FORM:
				$result = false;
				break;
			case iHomefinderVirtualPageFactory::ORGANIZER_EDIT_SAVED_SEARCH;
				$result = false;
				break;
			case iHomefinderVirtualPageFactory::ORGANIZER_VIEW_SAVED_SEARCH;
				$result = false;
				break;
			case iHomefinderVirtualPageFactory::MAP_SEARCH_FORM:
				$result = false;
				break;
		}
		return $result;	
	}
	
}
