<?php

class iHomefinderAdminControlPanel extends iHomefinderAdminAbstractPage {
	
	private static $instance;
	
	public static function getInstance() {
		if(!isset(self::$instance)) {
			self::$instance = new self();
		}
		return self::$instance;
	}
	
	protected function getContent() {
		if($this->admin->isActivated()) {
			$url = "https://" . iHomefinderConstants::CONTROL_PANEL_EXTERNAL_URL . "/z.cfm?w=" . get_option(iHomefinderConstants::ACTIVATION_TOKEN_OPTION, null);
			?>
			<h2>Your IDX Control Panel will open in a new window.</h2>
			<p>If a new window does not open, please enable pop-ups for this site or <a href="<?php echo $url ?>" target="_blank">click here</a>.</p>
			<script type="text/javascript">
				window.open("<?php echo $url ?>");
			</script>
			<?php
		}
	}
	
}