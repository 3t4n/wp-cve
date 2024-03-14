<?php

class View_SideBySide extends View
{

    public $class = "form-horizontal";

    public function render()
    {
        $this->_form->appendAttribute("class", $this->class);
       
        echo '<form', wp_kses_post((string)$this->_form->getAttributes()), '><fieldset>';
        $this->_form->getErrorView()->render();
        echo '<input type="hidden" name="rm_form_sub_id" value='.wp_kses_post((string)$this->_form->getAttribute('id')).'>';
        echo '<input type="hidden" name="rm_form_sub_no" value='.wp_kses_post((string)$this->_form->getAttribute('number')).'>';
        $elements = $this->_form->getElements();
        $elementSize = sizeof($elements);
        $elementCount = 0;
        for ($e = 0; $e < $elementSize; ++$e)
        {
            $element = $elements[$e];
            
            $ele_adv_opts = $element->getAdvanceAttr();
            $row_class = trim("rmrow ".$ele_adv_opts['exclass_row']);
            $input_class = trim("rminput ".$ele_adv_opts['exclass_input']);

            if ($element instanceof Element_Hidden || $element instanceof Element_HTML)
                $element->render();
            elseif ($element instanceof Element_Button || $element instanceof Element_HTMLL)
            {
                if ($e == 0 || (!$elements[($e - 1)] instanceof Element_Button && !$elements[($e - 1)] instanceof Element_HTMLL))
                    echo '<div class="buttonarea">';
                else
                    echo ' ';

                $element->render();

                if (($e + 1) == $elementSize || (!$elements[($e + 1)] instanceof Element_Button && !$elements[($e + 1)] instanceof Element_HTMLL))
                    echo '</div>';
            }elseif ($element instanceof Element_HTMLH || $element instanceof Element_HTMLP)
            {
                echo '<div class="'.esc_attr($row_class).'">', wp_kses_post((string)$element->render()), '', wp_kses_post((string)$this->renderDescriptions($element)), '</div>';
                ++$elementCount;
            } elseif($element instanceof Element_Captcha )
            {
                echo '<div class="'.esc_attr($row_class).' rm_captcha_fieldrow">', wp_kses_post((string)$this->renderLabel($element)), '<div class="'.esc_attr($input_class).'">', wp_kses_post((string)$element->render()), '</div>', wp_kses_post((string)$this->renderDescriptions($element)), '</div>';            
            } else
            {
                echo '<div class="'.esc_attr($row_class).'">', wp_kses_post((string)$this->renderLabel($element)), '<div class="rminput">', wp_kses_post((string)$element->render()), '</div>', wp_kses_post((string)$this->renderDescriptions($element)), '</div>';
                ++$elementCount;
            }
        }

        echo '</fieldset></form>';
    }

    public function renderLabel(Element $element)
   {
        $label = $element->getLabel();
        
        if (!empty($label))
        {
            //echo '<label class="control-label" for="', $element->getAttribute("id"), '">';
            echo '<div class="rmfield" for="', esc_attr($element->getAttribute("id")), '"><label>';
            echo wp_kses_post((string)$label);
            if ($element->isRequired() && $element->show_asterix()=='yes')
            {
                echo '<sup class="required">&nbsp;*</sup>';
            }          
            echo '</label></div>';
        }
    }

    public function _setForm(RM_PFBC_Form $form) {
		$this->_form = $form;
	}
	
	public function renderCSS() {
		echo 'label span.required { color: #B94A48; }';
		echo 'span.help-inline, span.help-block { color: #888; font-size: .9em; font-style: italic; }';
	}
	
	public function renderDescriptions($element) {
		$shortDesc = $element->getShortDesc();
		if(!empty($shortDesc)){
			//echo '<span class="help-inline">', $shortDesc, '</span>';;
                        echo '<div class="rmnote"><div class="rmprenote"></div>';
			echo '<div class="rmnote">', wp_kses_post((string)$shortDesc), '</div></div>';
                }

		$longDesc = $element->getLongDesc();
		if(!empty($longDesc)){
                        echo '<div class="rmnote"><div class="rmprenote"></div>';
			echo '<div class="rmnotecontent">', wp_kses_post((string)$longDesc), '</div></div>';
                }
			//echo '<span class="help-block">', $longDesc, '</span>';;
	}

}
