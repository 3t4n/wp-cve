<?php

namespace WP_Rplg_Google_Reviews\Includes;

use WP_Rplg_Google_Reviews\Includes\Core\Core;

class Plugin_Overview_Ajax {

    private $core;

    public function __construct(Core $core) {
        $this->core = $core;

        add_action('wp_ajax_grw_overview_ajax', array($this, 'overview_ajax'));
    }

    public function overview_ajax() {
        $overview = $this->core->get_overview(isset($_POST['place_id']) ? $_POST['place_id'] : 0);
        echo json_encode($overview);

        die();
    }
}
