<?php

class iHomefinderValuationWidget extends iHomefinderWidget {
	
	private $enqueueResource;
	private $displayRules;
	
	public function __construct() {
		parent::__construct(
			"iHomefinderValuationWidget",
			"IDX: Sell My House",
			array(
				"description" => "Displays Sell My House form."
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
			$title = apply_filters("widget_title", $instance["title"]);
			if($this->displayRules->isKestrelAll()) {
				$content = iHomefinderKestrelWidget::getValuationFormWidget($instance["style"]);
			} else {
				$remoteRequest = new iHomefinderRequestor();
				$remoteRequest
					->addParameter("requestType", "valuation-form-widget")
					->addParameter("smallView", true)
					->addParameter("style", $instance["style"])
				;
				if($this->displayRules->isNoId() === true) {
					$remoteRequest->addParameter("noId", true);
				}
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
		if (array_key_exists("title", $instance)) {
			$style = esc_attr($instance["style"]);
		}

		?>
		<p>
			<label>
				Title:
				<input class="widefat" id="<?php echo $this->get_field_id("title"); ?>" name="<?php echo $this->get_field_name("title"); ?>" type="text" value="<?php echo $title; ?>" />
			</label>
		</p>
		<p>
			<label>
				Layout:
				<select class="widefat" id="<?php echo $this->get_field_id("style"); ?>" name="<?php echo $this->get_field_name("style"); ?>">
					<option value="vertical" <?php if($style == "vertical") {echo "selected";} ?>>Vertical</option>
					<option value="twoline" <?php if($style == "twoline") {echo "selected";} ?>>Two Line</option>
				</select>
			</label>
		</p>
		<?php
		$this->getPageSelector($instance);
		?>
		<br />
		<?php
	}
	
}