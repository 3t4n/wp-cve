<?php 
/**
	Admin Page Framework v3.9.0b10 by Michael Uno 
	Generated by PHP Class Files Script Generator <https://github.com/michaeluno/PHP-Class-Files-Script-Generator>
	<http://en.michaeluno.jp/task-scheduler>
	Copyright (c) 2013-2021, Michael Uno; Licensed under MIT <http://opensource.org/licenses/MIT> */
class TaskScheduler_AdminPageFramework_Form_page_meta_box extends TaskScheduler_AdminPageFramework_Form_post_meta_box {
    public $sStructureType = 'page_meta_box';
    public function construct() {
        add_filter('options_' . $this->aArguments['caller_id'], array($this, '_replyToSanitizeSavedFormData'), 5);
        parent::construct();
    }
    public function _replyToSanitizeSavedFormData($aSavedFormData) {
        return $this->castArrayContents($this->getDataStructureFromAddedFieldsets(), $aSavedFormData);
    }
    }
    