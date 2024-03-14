<?php
defined('ABSPATH') || die('Cheatin\' uh?');

/**
 * Uninstall Options
 */
class RKMW_Controllers_Uninstall extends RKMW_Classes_FrontController {

    public function hookHead() {
        RKMW_Classes_ObjController::getClass('RKMW_Classes_DisplayController')->loadMedia('uninstall');
    }

    public function hookFooter() {
        echo $this->getView('Blocks/Uninstall');
    }

    public function action() {
        switch (RKMW_Classes_Helpers_Tools::getValue('action')) {
            case 'rkmw_uninstall_feedback':
                $reason['select'] = RKMW_Classes_Helpers_Tools::getValue('reason_key', false);
                $reason['plugin'] = RKMW_Classes_Helpers_Tools::getValue('reason_found_a_better_plugin', false);
                $reason['other'] = RKMW_Classes_Helpers_Tools::getValue('reason_other', false);

                $args['action'] = 'deactivate';
                $args['value'] = json_encode($reason);
                RKMW_Classes_RemoteController::saveFeedback($args);

                if (RKMW_Classes_Helpers_Tools::getValue('option_remove_records', false)) {
                    RKMW_Classes_Helpers_Tools::saveOptions('api', false);
                }

                RKMW_Classes_Helpers_Tools::setHeader('json');
                echo wp_json_encode(array());
                exit();
        }
    }
}
