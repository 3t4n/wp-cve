<?php
/**
 * @copyright (C) 2021 - 2024 Holger Brandt IT Solutions
 * @license GPL2
*/

if(!defined('ABSPATH')){
    exit;
}

class WADA_HtmlUtils
{
    const LABEL_CLOSING ='</label>';

    public static function returnAsStringOrRender($returnAsString){
        if($returnAsString){
            $html = ob_get_contents();
            ob_end_clean();
            return $html;
        }
        return '';
    }

    protected static function tableCellRowHeader($cellContent){
        $html = '<th scope="row">';
        $html .= $cellContent;
        $html .= '</th>';
        return $html;
    }

    protected static function tableCell($cellContent, $prefix='', $postfix=''){
        $html = '<td>';
        $html .= $prefix;
        $html .= $cellContent;
        $html .= $postfix;
        $html .= '</td>';
        return $html;
    }

    protected static function getAllowedHtml($inputType){
        $allowed_html = wp_kses_allowed_html('post');
        //WADA_Log::debug('returnAction getAllowedHtml (context post): '.print_r($allowed_html, true));

        $input = array();
        $input['id'] = 1;
        $input['name'] = 1;
        $input['type'] = 1;
        $input['value'] = 1;
        $input['title'] = 1;
        $input['class'] = 1;
        $input['style'] = 1;
        $input['placeholder'] = 1;
        $input['required'] = 1;
        $input['disabled'] = 1;
        $input['data-*'] = 1;

        $select = array();
        $select['id'] = 1;
        $select['name'] = 1;
        $select['title'] = 1;
        $select['class'] = 1;
        $select['style'] = 1;
        $select['required'] = 1;
        $select['disabled'] = 1;
        $select['multiple'] = 1;
        $select['data-*'] = 1;

        $option = array();
        $option['id'] = 1;
        $option['class'] = 1;
        $option['value'] = 1;
        $option['selected'] = 1;
        $option['disabled'] = 1;
        $option['data-*'] = 1;

        $optgroup = array();
        $optgroup['id'] = 1;
        $optgroup['label'] = 1;
        $optgroup['class'] = 1;

        $textarea = array();
        $textarea['id'] = 1;
        $textarea['name'] = 1;
        $textarea['type'] = 1;
        $textarea['value'] = 1;
        $textarea['cols'] = 1;
        $textarea['rows'] = 1;
        $textarea['title'] = 1;
        $textarea['class'] = 1;
        $textarea['style'] = 1;
        $textarea['placeholder'] = 1;
        $textarea['required'] = 1;
        $textarea['disabled'] = 1;
        $textarea['data-*'] = 1;

        $button = array();
        $button['id'] = 1;
        $button['name'] = 1;
        $button['title'] = 1;
        $button['class'] = 1;
        $button['style'] = 1;
        $button['disabled'] = 1;
        $button['onclick'] = 1;
        $button['data-*'] = 1;

        $aTag = array();
        $aTag['id'] = 1;
        $aTag['href'] = 1;
        $aTag['name'] = 1;
        $aTag['title'] = 1;
        $aTag['class'] = 1;
        $aTag['style'] = 1;
        $aTag['data-*'] = 1;

        $iTag = array();
        $iTag['id'] = 1;
        $iTag['name'] = 1;
        $iTag['title'] = 1;
        $iTag['class'] = 1;
        $iTag['style'] = 1;

        if($inputType === 'input' || $inputType === 'password') {
            $allowed_html['input'] = $input;
        } elseif ($inputType === 'checkbox') {
            $input['checked'] = 1;
            $allowed_html['input'] = $input;
        } elseif ($inputType === 'select') {
            $allowed_html['select'] = $select;
            $allowed_html['option'] = $option;
            $allowed_html['optgroup'] = $optgroup;
        } elseif ($inputType === 'textarea') {
            $allowed_html['textarea'] = $textarea;
        } elseif ($inputType === 'button') {
            $allowed_html['button'] = $button;
            $allowed_html['i'] = $iTag;
        } elseif ($inputType === 'link') {
            $allowed_html['a'] = $aTag;
            $allowed_html['i'] = $iTag;
        }

        return $allowed_html;
    }

    protected static function returnAction($inputType, $html, $opt){
        if(array_key_exists('return_as_str', $opt) && $opt['return_as_str']){
            return $html;
        }else{
            echo wp_kses($html, self::getAllowedHtml($inputType));
            return 'FIELD_RENDERED_ALREADY';
        }
    }

    protected static function generateFieldAndLabelHtml($inputTag, $label, $labelOpeningTag, $opt){
        $html = '';
        if($opt['render_as_table_row']){
            $html .= '<tr>';
            if($label && !$opt['omit_label']){
                $html .= self::tableCellRowHeader($labelOpeningTag.$label.self::LABEL_CLOSING);
            }else{
                $html .= '<td></td>';
            }
            $html .= self::tableCell($inputTag, $opt['html_prefix'], $opt['html_suffix']);
            $html .= '</tr>';
        }else{
            if($label && !$opt['omit_label']){
                $html .= $labelOpeningTag;
                if($opt['render_label_before_input']){
                    $html .= '<span class="lbl-txt">'.$label."</span>";
                }
            }

            $html .= $opt['html_prefix'];
            $html .= $inputTag;
            $html .= $opt['html_suffix'];

            if($label && !$opt['omit_label']){
                if(!$opt['render_label_before_input']){
                    $html .= '<span class="lbl-txt">'.$label."</span>";
                }
                $html .= self::LABEL_CLOSING;
            }
        }
        return $html;
    }

    public static function hiddenField($name, $value='', $options=array()){
        $opt = self::prepareOptions($options, $name);
        $dataAttributes = self::extractDataAttributes($opt);
        $html = '<input name="'.esc_attr($name).'" type="hidden" id="'.esc_attr($opt['id']).'" value="'.esc_attr($value).'" '.$dataAttributes.' >';
        return self::returnAction('input', $html, $opt);
    }

    protected static function extractDataAttributes($opt){
        $html = '';
        if(array_key_exists('data', $opt) && count($opt['data'])){
            foreach($opt['data'] AS $key => $val){
                 $html .= ' data-'.esc_attr($key).'="'.esc_attr($val).'" ';
            }
            $html = trim($html);
        }
        return $html;
    }

    protected static function generateInputTag($type, $name, $value, $opt, $extraAttributes=array()){
        $defaultClass = 'wada-field-'.$type;
        $infoIcon = $opt['title_as_info_icon'] ? ' <span class="hTip" '.$opt['title'].'><span class="dashicons dashicons-info"></span></span>' : '';
        $dataAttributes = self::extractDataAttributes($opt);
        return '<input name="'.esc_attr($name).'" type="'.esc_attr($type).'" id="'.esc_attr($opt['id']).'" '.$dataAttributes.' value="'.esc_attr($value).'" '.$opt['placeholder'].' '.implode(' ',$extraAttributes).' '.$opt['disabled'].' '.$opt['display_none'].' class="'.esc_attr($defaultClass.' '.$opt['input_class']).'" '.$opt['required'].'>'.$infoIcon;
    }

    protected static function generateLabelOpeningTag($type, $name, $opt){
        $defaultClass = 'wada-field-'.$type.'-lbl';
        return '<label for="'.esc_attr($name).'" class="'.esc_attr($defaultClass.' '.$opt['label_class']).'" '.$opt['title'].'>';
    }

    public static function checkboxField($name, $label, $isChecked=true, $options=array()){
        $opt = self::prepareOptions($options, $name);
        $checked = $isChecked ? 'checked="checked"' : '';
        $labelOpeningTag = self::generateLabelOpeningTag('checkbox', $name, $opt);
        $inputTag = self::generateInputTag('checkbox', $name, '1', $opt, array($checked));
        $html = self::generateFieldAndLabelHtml($inputTag, $label, $labelOpeningTag, $opt);
        return self::returnAction('checkbox', $html, $opt);
    }

    public static function boolToggleField($name, $label, $isChecked=true, $options=array()){
        $options['input_class'] = (array_key_exists('input_class', $options) ?  $options['input_class'].' ' : '').'wada-ui-toggle';
        return self::checkboxField($name, $label, $isChecked, $options);
    }

    public static function inputField($name, $label, $value='', $options=array()){
        $opt = self::prepareOptions($options, $name);
        $labelOpeningTag = self::generateLabelOpeningTag('text', $name, $opt);
        $inputTag = self::generateInputTag('text', $name, $value, $opt);
        $html = self::generateFieldAndLabelHtml($inputTag, $label, $labelOpeningTag, $opt);
        return self::returnAction('input', $html, $opt);
    }

    public static function passwordField($name, $label, $value='', $options=array()){
        $opt = self::prepareOptions($options, $name);
        $labelOpeningTag = self::generateLabelOpeningTag('password', $name, $opt);
        $inputTag = self::generateInputTag('password', $name, $value, $opt);
        $html = self::generateFieldAndLabelHtml($inputTag, $label, $labelOpeningTag, $opt);
        return self::returnAction('password', $html, $opt);
    }

    public static function textareaField($name, $label=null, $value='', $cols=null, $rows=null, $options=array()){
        $opt = self::prepareOptions($options, $name);
        $dataAttributes = self::extractDataAttributes($opt);
        $cols = $cols ? 'cols="'.intval($cols).'"' : '';
        $rows = $rows ? 'rows="'.intval($rows).'"' : '';
        $labelOpeningTag = $label ? self::generateLabelOpeningTag('textarea', $name, $opt) : null;
        $infoIcon = $opt['title_as_info_icon'] ? ' <span class="hTip" '.$opt['title'].'><span class="dashicons dashicons-info"></span></span>' : '';
        $inputTag = '<textarea '.$cols.' '.$rows.' name="'.esc_attr($name).'" id="'.esc_attr($opt['id']).'" '.$dataAttributes.' '.$opt['placeholder'].' '.$opt['disabled'].' '.$opt['display_none'].' class="wada-field-textarea '.esc_attr($opt['input_class']).'" '.$opt['required'].' >'.esc_html($value).'</textarea>'.$infoIcon;
        $html = self::generateFieldAndLabelHtml($inputTag, $label, $labelOpeningTag, $opt);
        // WADA_Log::debug('textareaField html: '.$html);
        return self::returnAction('textarea', $html, $opt);
    }

    public static function button($name, $label=null, $iconClass=null, $onClick=null, $options=array()){
        $opt = self::prepareOptions($options, $name);
        $dataAttributes = self::extractDataAttributes($opt);
        $buttonTag = '<button name="'.esc_attr($name).'" id="'.esc_attr($opt['id']).'" '.$dataAttributes.' '.$opt['title'].' '.$opt['disabled'].' '.$opt['display_none'].' class="wada-ui-button button '.esc_attr($opt['input_class']).'" '.($onClick?'onClick="'.esc_js($onClick).'"':'').' >';
        if($iconClass){
            $buttonTag .= '<i class="'.esc_html($iconClass).'"></i>';
        }
        if($label){
            $buttonTag .= esc_html($label);
        }
        $buttonTag .= '</button>';
        WADA_Log::debug('buttonTag: '.$buttonTag);
        return self::returnAction('button', $buttonTag, $opt);
    }

    public static function buttonLink($name, $href, $label=null, $iconClass=null, $options=array()){
        $opt = self::prepareOptions($options, $name);
        $dataAttributes = self::extractDataAttributes($opt);
        $buttonTag = '<a href="'.esc_url($href).'" name="'.esc_attr($name).'" id="'.esc_attr($opt['id']).'" '.$dataAttributes.' '.$opt['title'].' '.$opt['display_none'].' class="wada-ui-button button '.esc_attr($opt['input_class']).'" >';
        if($iconClass){
            $buttonTag .= '<i class="'.esc_html($iconClass).'"></i>';
        }
        if($label){
            $buttonTag .= esc_html($label);
        }
        $buttonTag .= '</a>';
        return self::returnAction('link', $buttonTag, $opt);
    }

    public static function selectField($name, $label=null, $value=null, $selectOptions=array(), $disabledSelectOptions=array(), $multiSelect=false, $options=array()){
        //WADA_Log::debug('selectField '.$name.', selectOptions: '.print_r($selectOptions, true).', disabled: '.print_r($disabledSelectOptions, true).', multiselect: '.($multiSelect?'Y':'N').', $options: '.print_r($options, true));
        $opt = self::prepareOptions($options, $name);
        $dataAttributes = self::extractDataAttributes($opt);
        $labelOpeningTag = $label ? self::generateLabelOpeningTag('select', $name, $opt) : null;
        $infoIcon = $opt['title_as_info_icon'] ? ' <span class="hTip" '.$opt['title'].'><span class="dashicons dashicons-info"></span></span>' : '';
        $selectTag = '<select name="'.esc_attr($name).($multiSelect?'[]':'').'" id="'.esc_attr($opt['id']).'" '.$dataAttributes.' '.$opt['disabled'].' class="wada-field-select '.esc_attr($opt['input_class']).'" '.$opt['display_none'].' '.($multiSelect?'multiple="multiple"':'').' '.$opt['required'].'>';
        $optionsArray = array();
        if($opt['placeholder_wo_tag']){
            $optionsArray[] = '<option value="0" disabled="disabled" selected="selected">'.$opt['placeholder_wo_tag'].'</option>';
        }
        foreach($selectOptions as $optionValue => $optContent){
            $optGroupCat = ''; // default (only relevant when use_optgroup is set)
            if(is_string($optContent)){
                $optionName = $optContent;
            }else{
                if(property_exists($optContent, 'name')){
                    $optionName = $optContent->name;
                }else if(property_exists($optContent, 'title')){
                    $optionName = $optContent->title;
                }else{
                    $optionName = 'Missing Option Name!';
                }
                if($opt['use_optgroup'] && property_exists($optContent, 'cat')){
                    $optGroupCat = $optContent->cat;
                }
            }
            $disabled = '';
            if( ! empty( $disabledSelectOptions ) ) {
                //WADA_Log::debug('selectField disabledOptions: '.print_r($disabledSelectOptions, true));
                if( in_array($optionValue, $disabledSelectOptions) ) {
                    //WADA_Log::debug('selectField disabledOptions match for '.$optionValue);
                    $disabled = "disabled";
                }
            }
            $valueMatch = false;
            if(is_array($value)){
                if( in_array($optionValue, $value) ) {
                    $valueMatch = true;
                }
            }else{
                $valueMatch = ($value == $optionValue);
            }

            $optHtml = '<option class="wada-field-select-opt '.esc_attr($opt['option_class']).'" value="'.esc_attr($optionValue).'" '.($valueMatch?'selected':'').' '.$disabled.'>'.esc_html($optionName).'</option>';
            if($opt['use_optgroup']){
                if(!array_key_exists($optGroupCat, $optionsArray)){
                    $optionsArray[$optGroupCat] = array();
                }
                $optionsArray[$optGroupCat][] = $optHtml;
            }else{
                $optionsArray[] = $optHtml;
            }
        }
        //WADA_Log::debug('select-l3: '.$selectTag);
        if($opt['use_optgroup']){
            foreach($optionsArray AS $optGroup=>$optGroupOptions){
                $selectTag .= '<optgroup label="'.esc_attr($optGroup).'">'.implode(' ', $optGroupOptions).'</optgroup>';
            }
            //WADA_Log::debug('select-l4: '.$selectTag);
        }else{
            $selectTag .= implode(' ', $optionsArray);
        }
        $selectTag .= '</select>';
        $selectTag .= $infoIcon;
        //WADA_Log::debug('select-l5: '.$selectTag);
        $html = self::generateFieldAndLabelHtml($selectTag, $label, $labelOpeningTag, $opt);
        return self::returnAction('select', $html, $opt);
    }

    protected static function prepareOptions($options, $defaultId){
        $id = array_key_exists('id', $options) ? $options['id'] : $defaultId;
        $returnAsStr = array_key_exists('return_as_str', $options);
        $renderAsTableRow = array_key_exists('render_as_table_row', $options);
        $required = array_key_exists('required', $options) ? 'required' : '';
        $disabled = array_key_exists('disabled', $options) ? 'disabled="disabled"' : '';
        $title = array_key_exists('title', $options) ? 'title="'.esc_attr($options['title']).'"' : '';
        $titleWithoutTag = array_key_exists('title', $options) ? $options['title'] : '';
        $titleAsInfoIcon = array_key_exists('title_as_info_icon', $options);
        $placeholder = array_key_exists('placeholder', $options) ? 'placeholder="'.esc_attr($options['placeholder']).'"' : '';
        $placeholderWithoutTag = array_key_exists('placeholder', $options) ? $options['placeholder'] : '';
        $dataAttributesArray = array_key_exists('data', $options) ? $options['data'] : array();
        $labelClass = array_key_exists('label_class', $options) ? $options['label_class'] : '';
        $inputClass = array_key_exists('input_class', $options) ? $options['input_class'] : '';
        $optionClass = array_key_exists('option_class', $options) ? $options['option_class'] : '';
        $htmlPrefix = array_key_exists('html_prefix', $options) ? $options['html_prefix'] : '';
        $htmlSuffix = array_key_exists('html_suffix', $options) ? $options['html_suffix'] : '';
        $omitLabel = array_key_exists('omit_label', $options);
        $renderLabelBeforeInput = array_key_exists('render_label_before_input', $options);
        $displayNone = array_key_exists('display_none', $options) ? 'style="display:none"' : '';
        $useOptGroup = array_key_exists('use_optgroup', $options);
        return array(
            'id' => $id,
            'return_as_str' => $returnAsStr,
            'render_as_table_row' => $renderAsTableRow,
            'required' => $required,
            'disabled' => $disabled,
            'title' => $title,
            'title_wo_tag' => $titleWithoutTag,
            'title_as_info_icon' => $titleAsInfoIcon,
            'placeholder' => $placeholder,
            'placeholder_wo_tag' => $placeholderWithoutTag,
            'data' => $dataAttributesArray,
            'label_class' => $labelClass,
            'input_class' => $inputClass,
            'option_class' => $optionClass,
            'html_prefix' => $htmlPrefix,
            'html_suffix' => $htmlSuffix,
            'omit_label' => $omitLabel,
            'render_label_before_input' => $renderLabelBeforeInput,
            'display_none' => $displayNone,
            'use_optgroup' => $useOptGroup
        );
    }

}