<?php

if (!defined('ABSPATH'))
    die('Restricted Access');

class MJTC_formfield {
    /*
     * Create the form text field
     */

    static function MJTC_text($name, $value, $extraattr = array()) {
        $textfield = '<input type="text" name="' . $name . '" id="' . $name . '" value="' . $value . '" ';
        if (!empty($extraattr))
            foreach ($extraattr AS $key => $val)
                $textfield .= ' ' . $key . '="' . $val . '"';
        $textfield .= ' />';
        return $textfield;
    }
    /*
     * Create the form text field
     */

    static function MJTC_email($name, $value, $extraattr = array()) {
        $textfield = '<input type="email" name="' . $name . '" id="' . $name . '" value="' . $value . '" ';
        if (!empty($extraattr))
            foreach ($extraattr AS $key => $val)
                $textfield .= ' ' . $key . '="' . $val . '"';
        $textfield .= ' />';
        return $textfield;
    }

    /*
     * Create the form password field
     */

    static function MJTC_password($name, $value, $extraattr = array()) {
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

    static function MJTC_textarea($name, $value, $extraattr = array()) {
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

    static function MJTC_hidden($name, $value, $extraattr = array()) {
        $textfield = '<input type="hidden" name="' . $name . '" id="' . $name . '" value="' . $value . '" ';
        if (!empty($extraattr))
            foreach ($extraattr AS $key => $val)
                $textfield .= ' ' . $key . '="' . $val . '"';
        $textfield .= ' />';
        return $textfield;
    }

    /*
     * Create the form submitbutton
     */

    static function MJTC_submitbutton($name, $value, $extraattr = array()) {
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

    static function MJTC_button($name, $value, $extraattr = array()) {
        $textfield = '<input type="button" name="' . $name . '" id="' . $name . '" value="' . $value . '" ';
        if (!empty($extraattr))
            foreach ($extraattr AS $key => $val)
                $textfield .= ' ' . $key . '="' . $val . '"';
        $textfield .= ' />';
        return $textfield;
    }

    /*
     * Create the form select field
     */

    static function MJTC_select($name, $list, $defaultvalue, $title = '', $extraattr = array()) {
        $selectfield = '<select name="' . $name . '" id="' . $name . '" ';
        if (!empty($extraattr))
            foreach ($extraattr AS $key => $val) {
                $selectfield .= ' ' . $key . '="' . $val . '"';
            }
        $selectfield .= ' >';
        if ($title != '') {
            $selectfield .= '<option value="">' . esc_html(majesticsupport::MJTC_getVarValue($title)) . '</option>';
        }
        if (!empty($list))
            foreach ($list AS $record) {
                if ((is_array($defaultvalue) && in_array($record->id, $defaultvalue)) || $defaultvalue == $record->id)
                    $selectfield .= '<option selected="selected" value="' . $record->id . '">' . esc_html(majesticsupport::MJTC_getVarValue($record->text)) . '</option>';
                else
                    $selectfield .= '<option value="' . $record->id . '">' . esc_html(majesticsupport::MJTC_getVarValue($record->text)) . '</option>';
            }

        $selectfield .= '</select>';
        return $selectfield;
    }

    /*
     * Create the form radio button
     */

    static function MJTC_radiobutton($name, $list, $defaultvalue, $extraattr = array()) {
        $radiobutton = '';
        $count = 1;
        foreach ($list AS $value => $label) {

            $radiobutton .= '<div class="ms-formfield-radio-button-wrap" >';
            $radiobutton .= '<input type="radio" name="' . $name . '" id="' . $name . $count . '" value="' . $value . '"';
            if ($defaultvalue == $value)
                $radiobutton .= ' checked="checked"';
            if (!empty($extraattr))
                foreach ($extraattr AS $key => $val) {
                    $radiobutton .= ' ' . $key . '="' . $val . '"';
                }
            $radiobutton .= '/><label id="for' . $name. $count . '" for="' . $name . $count . '">' . $label . '</label>';
            $radiobutton .= '</div>';
            $count++;
        }
        return $radiobutton;
    }

    /*
     * Create the form checkbox
     */

    static function MJTC_checkbox($name, $list, $defaultvalue, $extraattr = array()) {
        $checkbox = '';
        $count = 1;
        foreach ($list AS $value => $label) {
            $checkbox .= '<input type="checkbox" name="' . $name . '" id="' . $name . $count . '" value="' . $value . '"';
            if(is_array($defaultvalue)){
                if (in_array($value, $defaultvalue))
                    $checkbox .= ' checked="checked"';
            }else{
                if ($defaultvalue == $value)
                    $checkbox .= ' checked="checked"';
            }

            if (!empty($extraattr))
                foreach ($extraattr AS $key => $val) {
                    $checkbox .= ' ' . $key . '="' . $val . '"';
                }
            $checkbox .= '/><label id="for' . $name . '" for="' . $name . $count . '">' . $label . '</label>';
            $count++;
        }
        return $checkbox;
    }

    static function MJTC_setFormData($data) {
        MJTC_includer::MJTC_getObjectClass('wphdnotification')->MJTC_addSessionNotificationDataToTable($data,'submitform','submitform');
    }

    static function MJTC_getFormData() {
        $data = MJTC_includer::MJTC_getObjectClass('wphdnotification')->MJTC_getNotificationDatabySessionId('submitform',true);
        return $data;
    }
}

?>
