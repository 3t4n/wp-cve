<?php
defined('ABSPATH') || die('Cheatin\' uh?');

/**
 * Overview
 */
class RKMW_Controllers_Overview extends RKMW_Classes_FrontController {
    /** @var object Checkin process with Cloud */
    public $checkin;

    public function init() {
        //Checkin to API
        $this->checkin = RKMW_Classes_RemoteController::checkin();

        if(RKMW_Classes_Helpers_Tools::getValue('msg', false)){
            RKMW_Classes_Error::setMessage(RKMW_Classes_Helpers_Tools::getValue('msg'));
            RKMW_Classes_Error::hookNotices();
        }

        RKMW_Classes_ObjController::getClass('RKMW_Classes_DisplayController')->loadMedia('bootstrap-reboot');
        if (is_rtl()) {
            RKMW_Classes_ObjController::getClass('RKMW_Classes_DisplayController')->loadMedia('popper');
            RKMW_Classes_ObjController::getClass('RKMW_Classes_DisplayController')->loadMedia('bootstrap.rtl');
            RKMW_Classes_ObjController::getClass('RKMW_Classes_DisplayController')->loadMedia('rtl');
        } else {
            RKMW_Classes_ObjController::getClass('RKMW_Classes_DisplayController')->loadMedia('bootstrap');
        }
        RKMW_Classes_ObjController::getClass('RKMW_Classes_DisplayController')->loadMedia('fontawesome');
        RKMW_Classes_ObjController::getClass('RKMW_Classes_DisplayController')->loadMedia('switchery');
        RKMW_Classes_ObjController::getClass('RKMW_Classes_DisplayController')->loadMedia('global');
        RKMW_Classes_ObjController::getClass('RKMW_Classes_DisplayController')->loadMedia('research');
        RKMW_Classes_ObjController::getClass('RKMW_Classes_DisplayController')->loadMedia('navbar');
        RKMW_Classes_ObjController::getClass('RKMW_Classes_DisplayController')->loadMedia('account');

        add_action('rkmw_form_notices', array($this, 'getNotificationBar'));
        add_action('rkmw_form_notices', array($this, 'getNotificationCompatibility'));

        parent::init();
    }

}
