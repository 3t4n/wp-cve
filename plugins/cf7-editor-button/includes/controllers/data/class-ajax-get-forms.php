<?php
namespace Ari_Cf7_Button\Controllers\Data;

use Ari\Controllers\Ajax as Ajax_Controller;
use Ari_Cf7_Button\Helpers\Helper as Helper;

class Ajax_Get_Forms extends Ajax_Controller {
    protected function process_request() {
        $forms = Helper::get_cf7_forms();

        return array( 'forms' => $forms );
    }
}
