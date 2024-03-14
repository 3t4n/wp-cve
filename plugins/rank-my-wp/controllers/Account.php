<?php
defined('ABSPATH') || die('Cheatin\' uh?');

/**
 * User Account
 */
class RKMW_Controllers_Account extends RKMW_Classes_FrontController {

    /** @var object Checkin process */
    public $checkin;

    public function action() {
        switch (RKMW_Classes_Helpers_Tools::getValue('action')) {
            case 'rkmw_ajax_account_getaccount':
                $json = array();

                $this->checkin = RKMW_Classes_RemoteController::checkin();

                if (!is_wp_error($this->checkin)) {

                    $json['html'] = $this->getView('Blocks/Account');

                    if (RKMW_Classes_Helpers_Tools::isAjax()) {
                        RKMW_Classes_Helpers_Tools::setHeader('json');

                        if (RKMW_Classes_Error::isError()) {
                            $json['error'] = RKMW_Classes_Error::getError();
                        }

                        echo wp_json_encode($json);
                        exit();
                    }

                }
                break;
        }
    }
}
