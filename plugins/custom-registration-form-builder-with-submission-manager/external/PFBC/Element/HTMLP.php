<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of HTMLP
 *
 * @author CMSHelplive
 */
class Element_HTMLP extends Element
{

    public function __construct($value, $class=null, array $properties = array())
    {
        $properties = array_merge($properties, array("value" => $value, "class" => $class));
        
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
        {
            //Extract color attribute so that it can be applied to text.
            $style_str = "";
            if(isset($this->_attributes["style"]))
            {
                $al = explode(';',(string)$this->_attributes["style"]);                    
                foreach($al as $a)
                {
                    if(strpos(trim((string)$a),"color:")=== 0)
                    {
                        $style_str ='style="'.$a.'";'; 
                        break;
                    }
                }
            }
            echo '<p '.wp_kses_post((string)$style_str).' class="rm_form_field_type_paragraph',$this->_attributes["class"]? ' '.esc_attr($this->_attributes["class"]):null,'">';
        }
        if($type === "append")
            echo '</p>';
    }

    public function add_condition(){
        if(!empty($this->_attributes) && isset($this->_attributes["data-cond-option"])) {
            echo '<input type="hidden" class="data-conditional" data-cond-option="'.$this->_attributes["data-cond-option"].'" data-cond-value="'.$this->_attributes["data-cond-value"].'" data-cond-operator="'.$this->_attributes["data-cond-operator"].'" data-cond-action="'.$this->_attributes["data-cond-action"].'">';
        }
    }

}