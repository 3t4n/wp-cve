<?php 
/**
	Admin Page Framework v3.9.0b10 by Michael Uno 
	Generated by PHP Class Files Script Generator <https://github.com/michaeluno/PHP-Class-Files-Script-Generator>
	<http://en.michaeluno.jp/task-scheduler>
	Copyright (c) 2013-2021, Michael Uno; Licensed under MIT <http://opensource.org/licenses/MIT> */
abstract class TaskScheduler_AdminPageFramework_Input_Base extends TaskScheduler_AdminPageFramework_FrameworkUtility {
    public $aField = array();
    public $aAttributes = array();
    public $aOptions = array();
    public $aStructureOptions = array('input_container_tag' => 'span', 'input_container_attributes' => array('class' => 'task-scheduler-input-container',), 'label_container_tag' => 'span', 'label_container_attributes' => array('class' => 'task-scheduler-input-label-string',),);
    public function __construct(array $aAttributes, array $aOptions = array()) {
        $this->aAttributes = $this->getElementAsArray($aAttributes, 'attributes', $aAttributes);
        $this->aOptions = $aOptions + $this->aStructureOptions;
        $this->aField = $aAttributes;
        $this->construct();
    }
    protected function construct() {
    }
    public function get() {
    }
    public function getAttribute() {
        $_aParams = func_get_args() + array(0 => null, 1 => null,);
        return isset($_aParams[0]) ? $this->getElement($this->aAttributes, $_aParams[0], $_aParams[1]) : $this->aAttributes;
    }
    public function addClass() {
        foreach (func_get_args() as $_asSelectors) {
            $this->aAttributes['class'] = $this->getClassAttribute($this->aAttributes['class'], $_asSelectors);
        }
        return $this->aAttributes['class'];
    }
    public function setAttribute() {
        $_aParams = func_get_args() + array(0 => null, 1 => null,);
        $this->setMultiDimensionalArray($this->aAttributes, $this->getElementAsArray($_aParams, 0), $_aParams[1]);
    }
    public function setAttributesByKey($sKey) {
        $this->aAttributes = call_user_func_array(array($this, 'getAttributesByKey'), array($sKey));
    }
    public function getAttributesByKey() {
        return array();
    }
    public function getAttributeArray() {
        $_aParams = func_get_args();
        return call_user_func_array(array($this, 'getAttributesByKey'), $_aParams);
    }
    }
    