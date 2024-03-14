<?php

class iHomefinderShortcodeSelectorTinyMce {
	
	private $displayRules;
	private $formData;
	private static $instance;
	
	private function __construct() {
		$this->displayRules = iHomefinderDisplayRules::getInstance();
		$this->formData = iHomefinderFormData::getInstance();
	}

	public static function getInstance() {
		if(!isset(self::$instance)) {
			self::$instance = new self();
		}
		return self::$instance;
	}
	
	public function addButtons() {
		if(!current_user_can("edit_posts") && !current_user_can("edit_pages")) {
			return;
		}
		add_filter("mce_external_plugins", array($this, "addTinyMcePlugins"));
		add_filter("mce_buttons", array($this, "addTinyMceButtons"));
	}
	
	/**
	 * Used for TinyMCE to register buttons
	 */
	public function addTinyMceButtons($buttons) {
		$buttons[] = "ihomefinderShortcodeSelector";
		return $buttons;
	}
	
	/**
	 * Load the TinyMCE plugin
	 */
	public function addTinyMcePlugins($plugin_array) {
		$plugin_array["ihomefinderShortcodeSelector"] = plugins_url("/tinymce/ihomefinderShortcodeSelector/editor_plugin.js", __FILE__);
		return $plugin_array;
	}
	
	public function getShortcodeSelectorContent() {
		$shortcodeSelector = new iHomefinderShortcodeSelector();
		$shortcodeSelector->setButtonText("Insert");
		?>
		<html>
			<head>
				<script type="text/javascript" src="<?php echo includes_url("/js/jquery/jquery.js"); ?>"></script>
				<script type="text/javascript" src="<?php echo includes_url("js/tinymce/tiny_mce_popup.js", __FILE__); ?>"></script>
				<?php 
				echo $shortcodeSelector->getHeadContent();
				?>
				<script type="text/javascript" src="<?php echo plugins_url("tinymce/ihomefinderShortcodeSelector/dialog.js", __FILE__); ?>"></script>
			</head>
			<body>
				<?php 
				echo $shortcodeSelector->getShortcodeSelectorContent();
				?>
			</body>
		</html>
		<?php
		wp_die(); //don't remove
	}
	
}