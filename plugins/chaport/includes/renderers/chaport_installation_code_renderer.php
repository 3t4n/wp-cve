<?php
require_once(dirname(dirname(__FILE__)) . '/models/chaport_installation_code.php');
require_once(dirname(__FILE__) . '/chaport_base_renderer.php');

final class ChaportInstallationCodeRenderer extends ChaportBaseRenderer {
    private $installation_code;

    public function __construct($installation_code) {
        $this->installation_code = $installation_code;
    }

    /** Render code snippet (echo) */
    public function render() {
        if (!empty($this->installation_code)){
            require(dirname(dirname(__FILE__)) . '/snippets/chaport_installation_code_snippet.php');
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