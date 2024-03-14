<?php

class iHomefinderAdminShortcodes extends iHomefinderAdminAbstractPage {
	
	private static $instance;
	private $shortcodeSelector;
	
	public static function getInstance() {
		if(!isset(self::$instance)) {
			self::$instance = new self();
		}
		return self::$instance;
	}
	
	public function __construct() {
		parent::__construct();
		$this->shortcodeSelector = new iHomefinderShortcodeSelector();
		$this->shortcodeSelector->setButtonText("Copy to Clipboard");
		$this->shortcodeSelector->setNotification("SearchByAddress");
	}
	
	protected function getHeadContent() {
		echo $this->shortcodeSelector->getHeadContent();
		?>
		<style type="text/css">
			body {
				background-color: inherit;
			}
		</style>
		<script type="text/javascript">			
		iHomefinderShortcodeSelector.onBuildShortcode = function(shortcode, theForm) {
			var $form = jQuery(theForm);
			var $submitButton = $form.find("button");
			var $submitButtonContainer = $submitButton.parent();
			var $notification = jQuery(".ihf-clipboard-notification");
			var $copySelector = jQuery('input.ihf-select-shortcode');
			if($notification.length == 0){
				$notification = jQuery("<span>", {
					"class": "ihf-clipboard-notification",
					"style": "display: none;"
				});
				$submitButtonContainer.append($notification);
			}
			$copySelector.val(shortcode).select();
			if(document.execCommand("copy")){
				$notification.html("Copied!");
				$notification.slideDown("fast").delay(500).fadeOut(3000, function() {
					$notification.remove();
				});
			} else {
				$submitButtonContainer.append('<div class="panel panel-default" style="margin-top:25px"> <div class="panel-heading">Select and copy the following shortcode</div><div class="panel-body">' + shortcode + '</div></div>');
			}	
		}
		</script>	
		<?php
	}

	protected function getContent() {
		?>
		
		<h1>Optima Express Shortcodes</h1>
		<br />
			
		<?php
			echo $this->shortcodeSelector->getShortcodeSelectorContent();
		?>
			<input class="ihf-select-shortcode" type="text" value="" style="cursor: default !important; opacity:0;">
		<?php
	}	
		
}

