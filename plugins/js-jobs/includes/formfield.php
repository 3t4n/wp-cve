<?php

if (!defined('ABSPATH'))
    die('Restricted Access');

class JSJOBSformfield {
    /*
     * Create the form text field
     */
    static function resumetext($fieldName, $value,$section, $extraattr = array()) {
        $name = $section.'['.$fieldName.']';        
        $textfield = '<input type="text" name="' . $name . '" id="' . $fieldName . '" 
        value="' . jsjobslib::jsjobs_htmlspecialchars($value, ENT_QUOTES, 'UTF-8', false) . '" ';
        if (!empty($extraattr))
            foreach ($extraattr AS $key => $val)
                $textfield .= ' ' . $key . '="' . $val . '"';
        $textfield .= ' />';
        return $textfield;
    }
    static function text($name, $value, $extraattr = array()) {
        $textfield = '<input type="text" name="' . $name . '" id="' . $name . '" 
        value="' . jsjobslib::jsjobs_htmlspecialchars($value, ENT_QUOTES, 'UTF-8', false) . '" ';
        if (!empty($extraattr))
            foreach ($extraattr AS $key => $val)
                $textfield .= ' ' . $key . '="' . $val . '"';
        $textfield .= ' />';
        return $textfield;
    }
    static function email($name, $value, $extraattr = array()) {
        $textfield = '<input type="email" name="' . $name . '" id="' . $name . '" 
        value="' . jsjobslib::jsjobs_htmlspecialchars($value, ENT_QUOTES, 'UTF-8', false) . '" ';
        if (!empty($extraattr))
            foreach ($extraattr AS $key => $val)
                $textfield .= ' ' . $key . '="' . $val . '"';
        $textfield .= ' />';
        return $textfield;
    }

    /*
     * Create the form password field
     */

    static function password($name, $value, $extraattr = array()) {
        $textfield = '<input type="password" name="' . $name . '" id="' . $name . '" value="' . $value . '" ';
        if (!empty($extraattr))
            foreach ($extraattr AS $key => $val)
                $textfield .= ' ' . $key . '="' . $val . '"';
        $textfield .= ' />';
        return $textfield;
    }

    /*
     * Create the form text area
     */

    static function textarea($name, $value, $extraattr = array()) {
        $textarea = '<textarea name="' . $name . '" id="' . $name . '" ';
        if (!empty($extraattr))
            foreach ($extraattr AS $key => $val)
                $textarea .= ' ' . $key . '="' . $val . '"';
        $textarea .= ' >' . $value . '</textarea>';
        return $textarea;
    }

    /*
     * Create the form hidden field
     */

    static function hidden($name, $value, $extraattr = array(),$id='') {
        $textfield = '';
        if($id == ''){
            $id = $name;
        }
        if(is_array($value)){
            if(jsjobslib::jsjobs_strstr($name, '[]')){
                for ($i=0; $i < count($value) ; $i++) { 
                    $textfield .= '<input type="hidden" name="' . $name . '" id="' . $id . '" value="' . $value[$i] . '" /> ';
                }
                return $textfield;
            }
        }
        $textfield = '<input type="hidden" name="' . $name . '" id="' . $id . '" value="' . sanitize_text_field($value) . '" ';
        
        if (!empty($extraattr))
            foreach ($extraattr AS $key => $val)
                $textfield .= ' ' . $key . '="' . $val . '"';
        $textfield .= ' />';
        return $textfield;
    }

    /*
     * Create the form submitbutton
     */

    static function submitbutton($name, $value, $extraattr = array()) {
        $textfield = '<input type="submit" name="' . $name . '" id="' . $name . '" value="' . $value . '" ';
        if (!empty($extraattr))
            foreach ($extraattr AS $key => $val)
                $textfield .= ' ' . $key . '="' . $val . '"';
        $textfield .= ' />';
        return $textfield;
    }

    /*
     * Create the form button
     */

    static function button($name, $value, $extraattr = array()) {
        $textfield = '<input type="button" name="' . $name . '" id="' . $name . '" value="' . $value . '" ';
        if (!empty($extraattr))
            foreach ($extraattr AS $key => $val)
                $textfield .= ' ' . $key . '="' . $val . '"';
        $textfield .= ' />';
        return $textfield;
    }

    static function resumeSelect($fieldName, $list, $defaultvalue,$section,$title = '', $extraattr = array()) {
        $name = $section.'['.$fieldName.']';

        $selectfield = '<select name="' . $name . '" id="' . $fieldName . '" ';
        if (!empty($extraattr))
            foreach ($extraattr AS $key => $val) {
                $selectfield .= ' ' . $key . '="' . $val . '"';
            }
        $selectfield .= ' >';
        if ($title != '') {
            $selectfield .= '<option value="">' . $title . '</option>';
        }
        if($defaultvalue == ''){
            $defaultvalue = -9999; // B/c '' == 0 in php 
        }
        if (!empty($list))
            foreach ($list AS $record) {
                if ((is_array($defaultvalue) && in_array($record->id, $defaultvalue)) || $defaultvalue == $record->id)
                    $selectfield .= '<option selected="selected" value="' . $record->id . '">' . __($record->text,'js-jobs') . '</option>';
                else
                    $selectfield .= '<option value="' . $record->id . '">' . __($record->text,'js-jobs') . '</option>';
            }

        $selectfield .= '</select>';
        return $selectfield;
    }



    /*
     * Create the form select field
     */

    static function select($name, $list, $defaultvalue, $title = '', $extraattr = array()) {
        $selectfield = '<select name="' . $name . '" id="' . $name . '" ';
        if (!empty($extraattr))
            foreach ($extraattr AS $key => $val) {
                $selectfield .= ' ' . $key . '="' . $val . '"';
            }
        $selectfield .= ' >';
        if ($title != '') {
            $selectfield .= '<option value="">' . $title . '</option>';
        }
        if($defaultvalue == ''){
            $defaultvalue = -9999; // B/c '' == 0 in php 
        }
        if (!empty($list))
            foreach ($list AS $record) {
                $class=isset($record->class)?$record->class:"";
                if ((is_array($defaultvalue) && in_array($record->id, $defaultvalue)) || $defaultvalue == $record->id)
                    $selectfield .= '<option class="' . $class . '"  selected="selected" value="' . $record->id . '">' . __($record->text,'js-jobs') . '</option>';
                else
                    $selectfield .= '<option class="' . $class . '" value="' . $record->id . '">' . __($record->text,'js-jobs') . '</option>';
            }

        $selectfield .= '</select>';
        return $selectfield;
    }

    /*
     * Create the form radio button
     */

    static function radiobutton($name, $list, $defaultvalue, $extraattr = array()) {
        $radiobutton = '';
        $count = 1;
        foreach ($list AS $value => $label) {
            $radiobutton .= '<input type="radio" name="' . $name . '" id="' . $name . $count . '" value="' . $value . '"';
            if ($defaultvalue == $value)
                $radiobutton .= ' checked="checked"';

            if (!empty($extraattr))
                foreach ($extraattr AS $key => $val) {
                    $radiobutton .= ' ' . $key . '="' . $val . '"';
                }
            $radiobutton .= '/><label id="for' . $name . '" for="' . $name . $count . '">' . $label . '</label>';
            $count++;
        }
        return $radiobutton;
    }

    /*
     * Create the form checkbox
     */

    static function checkbox($name, $list, $defaultvalue, $extraattr = array()) {
        $checkbox = '';
        $count = 1;
        foreach ($list AS $value => $label) {
            $checkbox .= '<input type="checkbox" name="' . $name . '" id="' . $name . $count . '" value="' . $value . '"';
            if ($defaultvalue == $value)
                $checkbox .= ' checked="checked"';
            if (!empty($extraattr))
                foreach ($extraattr AS $key => $val) {
                    $checkbox .= ' ' . $key . '="' . $val . '"';
                }
            $checkbox .= '/><label id="for' . $name . '" for="' . $name . $count . '">' . $label . '</label>';
            $count++;
        }
        return $checkbox;
    }

}

?>
