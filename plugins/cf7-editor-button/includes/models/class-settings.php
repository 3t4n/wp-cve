<?php
namespace Ari_Cf7_Button\Models;

use Ari\Models\Model as Model;
use Ari_Cf7_Button\Helpers\Helper as Helper;

class Settings extends Model {
    public function data() {
        $form = Helper::get_settings_form();

        $data = array(
            'form' => $form,
        );

        return $data;
    }
}
