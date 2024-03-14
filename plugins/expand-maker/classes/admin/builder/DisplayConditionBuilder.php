<?php
namespace yrm;
require_once(YRM_ADMIN_BUILDER_CLASSES.'ConditionBuilder.php');

class DisplayConditionBuilder extends ConditionBuilder {
	public function __construct() {
		global $YRM_DISPLAY_SETTINGS_CONFIG;
		$configData = $YRM_DISPLAY_SETTINGS_CONFIG;
		$this->setConfigData($configData);
		$this->setNameString('yrm-display-settings');
	}
}