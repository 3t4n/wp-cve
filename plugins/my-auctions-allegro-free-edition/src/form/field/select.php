<?php
/**
 * My Auctions Allegro
 * @Author Luke Grochal (Grojan Team)
 * @Author URI https://grojanteam.pl
 */

defined('ABSPATH') or die;

class GJMAA_Form_Field_Select extends GJMAA_Form_Field {

	protected $type = 'select';

    public function getInput()
    {
        $id = $this->getInfo('id') ? ' id="'.$this->getInfo('id').'"' : '';
        $name = ' name="' . $this->getInfo('name') . '"';
        $disabled = $this->getInfo('disabled') ? ' disabled="true"' : '';
        $class = $this->getInfo('class') ? ' class="' . $this->getInfo('class') . '"' : '';
        $required = $this->getInfo('required') ? ' required="true"' : '';

        $options = $this->getInfo('options');
        $source = $this->getInfo('source');
        $isMultiSelect = $this->getInfo('is_multiselect');
        $values = $this->getInfo('value');
        $values = $isMultiSelect ? explode(',', $values) : $values;
        $multiple = $isMultiSelect ? ' multiple ' : '';
        $size = $this->getInfo('size') ? ' size="'.$this->getInfo('size').'"' : "";
        $level = is_null($this->getInfo('level')) ? '' : ' level="' . $this->getInfo('level') . '"';
        $style = '';
        if($styles = $this->getInfo('style')){
            $style = 'style="';
            foreach($styles as $attribute => $value) {
                $style .= $attribute .':'.$value.';';
            }
            $style .= '"';
        }

        if(empty($options) && !empty($source)){
            $options = GJMAA::getSource($source)->getAllOptions(!$isMultiSelect);
        }


        $input = "<select{$id}{$name}{$class}{$disabled}{$required}{$size}{$style}{$multiple}{$level}>";
        foreach($options as $value => $label)
        {
            $selected = ((is_array($values) && in_array($value, $values)) || ((is_numeric($value) ? (int)$value : $value) === (is_numeric($values) ? (int)$values : $values)))  ? ' selected="selected"' : '';
            $input .= '<option value="'.$value.'"'.$selected.'>'.__($label,GJMAA_TEXT_DOMAIN).'</option>';
        }
        $input .= '</select>';
        return $input;
    }
}