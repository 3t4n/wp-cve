<?php
// include the required file
require_once BWFAN_PLUGIN_DIR . '/includes/plugin_helpers/class-bwfan-plugin-silent-upgrader-skin.php';

/**
 * Class BWFAN_Plugin_Install_Skin
 */
class BWFAN_Plugin_Install_Skin extends BWFAN_Plugin_Silent_Upgrader_Skin
{
    public function error($errors)
    {
        if (!empty($errors)) {
            wp_send_json_error($errors);
        }
    }
}
