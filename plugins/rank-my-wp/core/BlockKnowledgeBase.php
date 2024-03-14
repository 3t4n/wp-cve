<?php
defined('ABSPATH') || die('Cheatin\' uh?');

class RKMW_Core_BlockKnowledgeBase extends RKMW_Classes_BlockController {

    public function hookGetContent(){
        echo $this->getView('Blocks/KnowledgeBase');
    }
}
