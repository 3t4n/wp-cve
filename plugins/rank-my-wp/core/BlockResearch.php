<?php
defined('ABSPATH') || die('Cheatin\' uh?');

class RKMW_Core_BlockResearch extends RKMW_Classes_BlockController {
    var $stats = array();

    function hookGetContent() {
        RKMW_Classes_ObjController::getClass('RKMW_Classes_DisplayController')->loadMedia('global');
        RKMW_Classes_ObjController::getClass('RKMW_Classes_DisplayController')->loadMedia('research');

        echo $this->getView('Blocks/Research');

    }


}
