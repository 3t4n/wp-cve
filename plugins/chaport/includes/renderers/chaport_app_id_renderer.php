<?php
require_once(dirname(dirname(__FILE__)) . '/models/chaport_app_id.php');
require_once(dirname(__FILE__) . '/chaport_base_renderer.php');

/** Chaport installation code renderer */
final class ChaportAppIdRenderer extends ChaportBaseRenderer {
	/** @var ChaportAppId Chaport App ID */
	private $app_id;

	public function __construct($app_id) {
		if (!($app_id instanceof ChaportAppId)) {
			throw new Error('Expecting appId to be instance of ChaportAppId');
		}
		$this->app_id = $app_id;
	}

	/** Render code snippet (echo) */
	public function render() {
		if (!empty($this->app_id)){
			require(dirname(dirname(__FILE__)) . '/snippets/chaport_app_id_code_snippet.php');
			$this->renderUserDetails();
		}
	}

	/** Render code snippet to a string */
	public function renderToString() {
		ob_start();
		$this->render();
		return ob_get_clean();
	}
}