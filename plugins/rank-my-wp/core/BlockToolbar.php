<?php
defined('ABSPATH') || die('Cheatin\' uh?');

class RKMW_Core_BlockToolbar extends RKMW_Classes_BlockController {

    public function init() {
        echo $this->getView('Blocks/Toolbar');
    }

    public function hookGetContent() { }

}
