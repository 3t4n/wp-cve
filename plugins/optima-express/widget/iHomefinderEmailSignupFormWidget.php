<?php

class iHomefinderEmailSignupFormWidget extends iHomefinderWidget {
	
	private $enqueueResource;
	private $displayRules;
	
	public function __construct() {
		parent::__construct(
			"iHomefinderEmailSignupFormWidget",
			"IDX: Email Signup",
			array(
				"description" => "Displays a Email Signup form on listing result pages."
			)
		);
		$this->enqueueResource = iHomefinderEnqueueResource::getInstance();
		$this->displayRules = iHomefinderDisplayRules::getInstance();
	}
	
	public function widget($args, $instance) {
		$instance = $this->migrate($instance);
		if($this->isEnabled($instance)) {
			$beforeWidget = $args["before_widget"];
			$afterWidget = $args["after_widget"];
			$beforeTitle = $args["before_title"];
			$afterTitle = $args["after_title"];
			if($this->displayRules->isKestrelAll()) {
				$content = iHomefinderKestrelWidget::getEmailSignupWidget();
			} else {
				$remoteRequest = new iHomefinderRequestor();
				$remoteRequest
					->addParameter("requestType", "email-signup")
				;
				if($this->displayRules->isNoId() === true) {
					$remoteRequest->addParameter("noId", true);
				}
				$remoteResponse = $remoteRequest->remoteGetRequest();
				$content = $remoteResponse->getBody();
				$this->enqueueResource->addToFooter($remoteResponse->getHead());
			}
			$title = apply_filters("widget_title", $instance["title"]);
			echo $beforeWidget;
			if(!empty($title)) {
				echo $beforeTitle . $title . $afterTitle;
			}
			echo $content;
			echo $afterWidget;
		}
	}
	
	protected function isEnabled($instance) {
		$result = false;
		$virtualPageType = get_query_var(iHomefinderConstants::IHF_TYPE_URL_VAR);
		switch($virtualPageType) {
			case iHomefinderVirtualPageFactory::LISTING_SEARCH_RESULTS:
			case iHomefinderVirtualPageFactory::HOT_SHEET_LISTING_REPORT:
			case iHomefinderVirtualPageFactory::HOT_SHEET_OPEN_HOME_REPORT:
			case iHomefinderVirtualPageFactory::HOT_SHEET_MARKET_REPORT:
				$result = true;
		}
		return $result;
	}
	
	public function update($newInstance, $oldInstance) {
		$newInstance = $this->migrate($newInstance);
		$oldInstance = $this->migrate($oldInstance);
		$instance = $oldInstance;
		$instance["title"] = strip_tags(stripslashes($newInstance["title"]));
		return $instance;
	}
	
	public function form($instance) {
		$instance = $this->migrate($instance);
		$title = null;
		if (array_key_exists("title", $instance)) {
			$title = esc_attr($instance["title"]);
		}
		?>
		<p>
			<label>
				Title:
				<input class="widefat" id="<?php echo $this->get_field_id("title"); ?>" name="<?php echo $this->get_field_name("title"); ?>" type="text" value="<?php echo $title; ?>" />
			</label>
		</p>
		<?php
	}
	
}
