<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of HTMLH
 *
 * @author CMSHelplive
 */
class Element_HTMLH extends Element
{

    public function __construct($value, $class=null, $options=null)
    {
        $properties = array("value" => $value, "class" => $class, "options" => $options);
        parent::__construct("", "", $properties);
    }

    public function render()
    {
        $this->renderTag("prepend");
        echo wp_kses_post((string)$this->_attributes["value"]);
        $this->renderTag("append");
    }
    
    public function renderTag($type = "prepend"){
        if($type === "prepend")
            echo '<h1 class="rm_form_field_type_heading',$this->_attributes["class"] ? ' '.esc_attr($this->_attributes["class"]):null,'">';
        if($type === "append")
            echo '</h1>';
    }

    public function add_condition(){
        if(!is_null($this->_attributes["options"]) && isset($this->_attributes["options"]["data-cond-option"])) {
            echo '<input type="hidden" class="'.$this->_attributes["options"]["class"].'" data-cond-option="'.$this->_attributes["options"]["data-cond-option"].'" data-cond-value="'.$this->_attributes["options"]["data-cond-value"].'" data-cond-operator="'.$this->_attributes["options"]["data-cond-operator"].'" data-cond-action="'.$this->_attributes["options"]["data-cond-action"].'">';
        }
    }

}
