<?php

class iHomefinderAdminConfiguration extends iHomefinderAdminAbstractPage {
	
	private static $instance;
	private $displayRules;
	
	public static function getInstance() {
		if(!isset(self::$instance)) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	public function __construct() {
		parent::__construct();
		$this->displayRules = iHomefinderDisplayRules::getInstance();
	}
	
	public function registerSettings() {
		register_setting(iHomefinderConstants::OPTION_GROUP_CONFIGURATION, iHomefinderConstants::CSS_OVERRIDE_OPTION);
		register_setting(iHomefinderConstants::OPTION_GROUP_CONFIGURATION, iHomefinderConstants::SHADOW_DOM_HTML_OPTION);
		register_setting(iHomefinderConstants::OPTION_GROUP_CONFIGURATION, iHomefinderConstants::SHADOW_DOM_CSS_OPTION);
		register_setting(iHomefinderConstants::OPTION_GROUP_CONFIGURATION, iHomefinderConstants::NO_ID_OPTION);
	}
	
	protected function getContent() {
		$cssOverride = get_option(iHomefinderConstants::CSS_OVERRIDE_OPTION, null);
		$shadowDomHtml = get_option(iHomefinderConstants::SHADOW_DOM_HTML_OPTION, null);
		$shadowDomCss = get_option(iHomefinderConstants::SHADOW_DOM_CSS_OPTION, null);
		if(empty($cssOverride)) {
			$cssOverride = "<style type=\"text/css\">\n\n</style>";
		}
		?>
		<h2>Configuration</h2>
		<form method="post" action="options.php">
			<?php settings_fields(iHomefinderConstants::OPTION_GROUP_CONFIGURATION); ?>
			<table class="form-table">
				<?php if($this->displayRules->isKestrel()) { ?>
					<tr>
						<th>
							<label for="<?php echo iHomefinderConstants::SHADOW_DOM_CSS_OPTION; ?>">Add CSS to IDX Stylesheet</label>
						</th>
						<td>
							<p>(v10 only) Add CSS overrides to the shadow DOM</p>
							<textarea id="<?php echo iHomefinderConstants::SHADOW_DOM_CSS_OPTION; ?>" name="<?php echo iHomefinderConstants::SHADOW_DOM_CSS_OPTION; ?>" style="width: 100%; height: 200px; "><?php echo $shadowDomCss; ?></textarea>
						</td>
					</tr>
					<tr>
						<th>
							<label for="<?php echo iHomefinderConstants::SHADOW_DOM_HTML_OPTION; ?>">Add Code to IDX Content</label>
						</th>
						<td>
							<p>(v10 only) Add scripts and other HTML to the shadow DOM</p>
							<textarea id="<?php echo iHomefinderConstants::SHADOW_DOM_HTML_OPTION; ?>" name="<?php echo iHomefinderConstants::SHADOW_DOM_HTML_OPTION; ?>" style="width: 100%; height: 200px; "><?php echo $shadowDomHtml; ?></textarea>
						</td>
					</tr>
				<?php } ?>
				<tr>
					<th>
						<label for="<?php echo iHomefinderConstants::CSS_OVERRIDE_OPTION; ?>">Add Code to Page Head</label>
					</th>
					<td>
						<p>Add CSS overrides, scripts, and other HTML to the head of the page</p>
						<textarea id="<?php echo iHomefinderConstants::CSS_OVERRIDE_OPTION; ?>" name="<?php echo iHomefinderConstants::CSS_OVERRIDE_OPTION; ?>" style="width: 100%; height: 200px; "><?php echo $cssOverride; ?></textarea>
					</td>
				</tr>
				<tr>
					<th>Advanced</th>
					<td>
						<label for="<?php echo iHomefinderConstants::NO_ID_OPTION; ?>">Remove duplicate CSS IDs for widgets and shortcodes for improved accessibility</label>
						<input
							type="checkbox" 
							<?php if(get_option(iHomefinderConstants::NO_ID_OPTION, null) === "true") { ?>
								checked="checked"
							<?php } ?>
							value="true"
							name="<?php echo iHomefinderConstants::NO_ID_OPTION; ?>" id="<?php echo iHomefinderConstants::NO_ID_OPTION; ?>
						">
					</td>
				</tr>
			</table>
			<p class="submit">
				<button type="submit" class="button-primary">Save Changes</button>
			</p>
		</form>
		<?php
	}
	
}