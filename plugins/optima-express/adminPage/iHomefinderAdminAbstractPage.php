<?php

abstract class iHomefinderAdminAbstractPage implements iHomefinderAdminPageInterface {
	
	protected $admin;
	
	protected function __construct() {
		$this->admin = iHomefinderAdmin::getInstance();
	}
	
	public function getPage() {
		$this->registerSettings();
		if(!current_user_can("manage_options")) {
			wp_die("You do not have sufficient displayRules to access this page.");
		}
		if($this->isUpdated()) {
			$this->admin->activateAuthenticationToken();
		}
		$this->getHeadContent();
		?>
		<div id="ihf-main-container" class="wrap">
			<?php
			$this->getContent();
			?>
		</div>
		<?php
	}
	
	public function registerSettings() {
		
	}
	
	protected function getContent() {
		
	}
	
	protected function getHeadContent() {
		
	} 
	
	//Check if an options form has been updated.
	//When new options are updated, the parameter "updated" is set to true
	public function isUpdated() {
		$settingsUpdated = iHomefinderUtility::getInstance()->getRequestVar("settings-updated");
		$result = $settingsUpdated === "true";
		return $result;
	}
	
	protected function showErrorMessages($errors) {
		if($this->hasErrors($errors)) {
			?>
			<div class="error">
				<?php foreach($errors as $error) { ?>
					<p>
						<?php echo $error; ?>
					</p>
				<?php } ?>
			</div>
			<?php
		}
	}
	
	protected function hasErrors($errors) {
		$result = $errors !== null && count($errors) > 0;
		return $result;
	}
	
}