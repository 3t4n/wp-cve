<?php

if (!defined('ABSPATH'))
    die('Restricted Access');

class MJTC_customfields {
    function MJTC_formCustomFields($field) {
        if($field->isuserfield != 1){
            return false;
        }
        if($field->userfieldtype == 'admin_only' && !is_admin()){
            return false;
        }
        $cssclass = "";
        $visibleclass = "";
        if (isset($field->visibleparams) && $field->visibleparams != ''){
            $visibleclass = "visible";
        }
        $html = '';
        $div1 =  ($field->size == 100) ? ' mjtc-support-from-field-wrp-full-width mjtc-support-from-field-wrp '.esc_attr($visibleclass) : 'mjtc-support-from-field-wrp '.esc_attr($visibleclass);
        $div2 = 'mjtc-support-from-field-title';
        $div3 = 'mjtc-support-from-field';


        if(is_admin()){
            $div1 = ($field->size == 100) ? 'mjtc-form-wrapper mjtc-form-custm-flds-wrp fullwidth '.esc_attr($visibleclass) : 'mjtc-form-wrapper mjtc-form-custm-flds-wrp '.esc_attr($visibleclass);
            $div2 = 'mjtc-form-title';
            $div3 = 'mjtc-form-value';
       }


        $required = $field->required;
        if($field->userfieldtype == 'termsandconditions'){
            if (isset(majesticsupport::$_data[0]->id)) {
                return false;
            }
            $required = 1;
            if (isset($field->visibleparams) && $field->visibleparams !='') {
                $required = 0;
            }
        }

        $html = '<div class="' . esc_attr($div1) .  '">
               <div class="' . esc_attr($div2) . '">';
        if ($required == 1 && $visibleclass != 'visible') {
            $html .= esc_html(majesticsupport::MJTC_getVarValue($field->fieldtitle)) . '<span style="color: red;" >*</span>';
                $cssclass = "required";
        }else {
            $html .= esc_html(majesticsupport::MJTC_getVarValue($field->fieldtitle));
                $cssclass = "";
        }
        $html .= ' </div><div class="' . esc_attr($div3) . '">';
        $readonly = "";
        $maxlength = $field->maxlength ? "$field->maxlength" : "";
        $fvalue = "";
        $value = "";
        $userdataid = "";
        $specialClass="";
        if (isset(majesticsupport::$_data[0]->id)) {
            $userfielddataarray = json_decode(majesticsupport::$_data[0]->params);
            $uffield = $field->field;
            if (isset($userfielddataarray->$uffield) && !empty($userfielddataarray->$uffield)) {
                $value = $userfielddataarray->$uffield;
                $specialClass='specialClass';
            } else {
                $value = '';
            }
        }

        switch ($field->userfieldtype) {
            case 'text':
            case 'admin_only':
                $html .= wp_kses(MJTC_formfield::MJTC_text($field->field, $value, array('class' => 'inputbox mjtc-form-input-field mjtc-support-form-field-input one '.esc_attr($specialClass), 'data-validation' => $cssclass, 'maxlength' => $maxlength, $readonly)), MJTC_ALLOWED_TAGS);
                break;
            case 'email':
                $html .= wp_kses(MJTC_formfield::MJTC_email($field->field, $value, array('class' => 'inputbox mjtc-form-input-field mjtc-support-form-field-input one '. esc_attr($specialClass), 'data-validation' => $cssclass, 'maxlength' => $maxlength, $readonly)), MJTC_ALLOWED_TAGS);
                break;
            case 'date':
                if(MJTC_majesticsupportphplib::MJTC_strpos($value , '1970') !== false){
                    $value = "";
                }
                $html .= wp_kses(MJTC_formfield::MJTC_text($field->field, $value, array('class' => 'custom_date mjtc-form-date-field  mjtc-support-input-field  one '. esc_attr($specialClass), 'data-validation' => $cssclass)), MJTC_ALLOWED_TAGS);
                break;
            case 'textarea':
                $html .= wp_kses(MJTC_formfield::MJTC_textarea($field->field, $value, array('class' => 'inputbox mjtc-form-textarea-field mjtc-support-custom-textarea one '.esc_attr($specialClass), 'data-validation' => $cssclass, 'rows' => $field->rows, 'cols' => $field->cols, $readonly)), MJTC_ALLOWED_TAGS);
                break;
            case 'checkbox':
                if (!empty($field->userfieldparams)) {
                    $comboOptions = array();
                    $obj_option = json_decode($field->userfieldparams);
                    $total_options= count($obj_option);
                    if($total_options % 2 == 0) {
                        $field_width = 'style = " width:calc(100% / 2 - 4px); margin:2px;"';
                    } else {
                        $field_width = 'style = " width:calc(100% / 3 - 4px); margin:2px;"';
                    }
                    $i = 0;
                    $valuearray = array();
                    if ($value != '') {
                        $valuearray = MJTC_majesticsupportphplib::MJTC_explode(', ',$value);
                    }
                    foreach ($obj_option AS $option) {
                        $check = '';
                        $option = html_entity_decode($option);
                        if(in_array($option, $valuearray)){
                            $check = 'checked';
                        }
                        $html .= '<div class="ms-formfield-radio-button-wrap mjtc-support-custom-radio-box" '. $field_width .'>';
                        $html .= '<input type="checkbox" ' . esc_attr($check) . ' class="radiobutton mjtc-support-append-radio-btn '.esc_attr($specialClass).'" value="' . esc_attr($option) . '" id="' . esc_attr($field->field) . '_' . esc_attr($i) . '" name="' . esc_attr($field->field) . '[]">';
                        $html .= '<label for="' . esc_attr($field->field) . '_' . esc_attr($i) . '" id="foruf_checkbox1">' . esc_html($option) . '</label>';
                        $html .= '</div>';
                        $i++;
                    }
                } else {
                    $comboOptions = array('1' => majesticsupport::MJTC_getVarValue($field->fieldtitle));
                    $html .= wp_kses(MJTC_formfield::MJTC_checkbox($field->field, $comboOptions, $value, array('class' => 'radiobutton')), MJTC_ALLOWED_TAGS);
                }
                break;
            case 'radio':
                $comboOptions = array();
                if (!empty($field->userfieldparams)) {
                    $obj_option = json_decode($field->userfieldparams);
                    $total_options= count($obj_option);
                    if($total_options % 2 == 0) {
                        $field_width = 'style = " width:calc(100% / 2 - 4px); margin:2px;"';
                    } else {
                        $field_width = 'style = " width:calc(100% / 3 - 4px); margin:2px;"';
                    }
                    $i = 0;
                    $msFunction = '';
                    if ($field->depandant_field != null) {
                        $wpnonce = wp_create_nonce("data-for-depandant-field");
                        $msFunction = "MJTC_getDataForDepandantField('".esc_attr($wpnonce)."','" . esc_attr($field->field) . "','" . esc_attr($field->depandant_field) . "',2);";
                    }
                    $valuearray = array();
                    if ($value != '') {
                        $valuearray = MJTC_majesticsupportphplib::MJTC_explode(', ',$value);
                    }
                    foreach ($obj_option AS $option) {
                        $check = '';
                        $option = html_entity_decode($option);
                        if(in_array($option, $valuearray)){
                            $check = 'checked';
                        }
                        $html .= '<div class="ms-formfield-radio-button-wrap mjtc-support-radio-box" '. $field_width .'>';
                            $html .= '<input type="radio" ' . esc_attr($check) . ' class="radiobutton mjtc-support-radio-btn '.esc_attr($cssclass).' '.esc_attr($specialClass).'" value="' . esc_attr($option) . '" id="' . esc_attr($field->field) . '_' . esc_attr($i) . '" name="' . esc_attr($field->field) . '" data-validation ="'.esc_attr($cssclass).'" onclick = "'.esc_js($msFunction).'"> ';
                            $html .= '<label for="' . esc_attr($field->field) . '_' . esc_attr($i) . '" id="foruf_checkbox1">' . esc_html($option) . '</label>';
                        $html .= '</div>';
                        $i++;
                    }
                }
                break;
            case 'combo':
                $comboOptions = array();
                if (!empty($field->userfieldparams)) {
                    $obj_option = json_decode($field->userfieldparams);
                    foreach ($obj_option as $opt) {
                        $opt = html_entity_decode($opt);
                        $comboOptions[] = (object) array('id' => $opt, 'text' => $opt);
                    }
                }
                //code for handling dependent field
                $msFunction = '';
                if ($field->depandant_field != null) {
                    $wpnonce = wp_create_nonce("data-for-depandant-field");
                    $msFunction = "MJTC_getDataForDepandantField('". esc_js($wpnonce) ."','" . esc_js($field->field) . "','" . esc_js($field->depandant_field) . "',1);";
                }
                //code for handling visible field
                $msVisibleFunction = '';
                if ($field->visible_field != null) {
                    $visibleparams = MJTC_includer::MJTC_getModel('fieldordering')->MJTC_getDataForVisibleField($field->visible_field);
                    foreach ($visibleparams as $visibleparam) {
                        $wpnonce = wp_create_nonce("is-field-required");
                        $msVisibleFunction .= " MJTC_getDataForVisibleField('". esc_js($wpnonce) ."', this.value, '" . esc_js($visibleparam->visibleParent) . "','" . esc_js($visibleparam->visibleParentField) . "','". esc_js($visibleparam->visibleValue) ."','". esc_js($visibleparam->visibleCondition) ."');";
                    }
                    $msFunction.=$msVisibleFunction;
                }
                //end
                $html .= wp_kses(MJTC_formfield::MJTC_select($field->field, $comboOptions, $value, esc_html(__('Select', 'majestic-support')) . ' ' . esc_attr(majesticsupport::MJTC_getVarValue($field->fieldtitle)) , array('data-validation' => $cssclass, 'onchange' => $msFunction, 'class' => 'inputbox mjtc-form-select-field mjtc-support-custom-select one '.esc_attr($specialClass))), MJTC_ALLOWED_TAGS);
                break;
            case 'depandant_field':
                $comboOptions = array();
                if ($value != null) {
                    if (!empty($field->userfieldparams)) {
                        $obj_option = $this->MJTC_getDataForDepandantFieldByParentField($field->field, $userfielddataarray);
                        foreach ($obj_option as $opt) {
                            $opt = html_entity_decode($opt);
                            $comboOptions[] = (object) array('id' => $opt, 'text' => $opt);
                        }
                    }
                }
                //code for handling dependent field
                $msFunction = '';
                if ($field->depandant_field != null) {
                    $wpnonce = wp_create_nonce("data-for-depandant-field");
                    $msFunction = "MJTC_getDataForDepandantField('". esc_js($wpnonce) ."','" . esc_js($field->field) . "','" . esc_js($field->depandant_field) . "');";
                }
                //end
                $html .= wp_kses(MJTC_formfield::MJTC_select($field->field, $comboOptions, $value, esc_html(__('Select', 'majestic-support')) . ' ' . esc_attr(majesticsupport::MJTC_getVarValue($field->fieldtitle)) , array('data-validation' => $cssclass, 'onchange' => $msFunction, 'class' => 'inputbox mjtc-form-select-field mjtc-support-custom-select one '. esc_attr($specialClass))), MJTC_ALLOWED_TAGS);
                break;
            case 'multiple':
                $comboOptions = array();
                if (!empty($field->userfieldparams)) {
                    $obj_option = json_decode($field->userfieldparams);
                    foreach ($obj_option as $opt) {
                        $opt = html_entity_decode($opt);
                        $comboOptions[] = (object) array('id' => $opt, 'text' => $opt);
                    }
                }
                $array = $field->field;
                $array .= '[]';
                $valuearray = array();
                if ($value != '') {
                    $valuearray = MJTC_majesticsupportphplib::MJTC_explode(', ', $value);
                }
                $html .= wp_kses(MJTC_formfield::MJTC_select($array, $comboOptions, $valuearray, esc_html(__('Select', 'majestic-support')) . ' ' . esc_attr(majesticsupport::MJTC_getVarValue($field->fieldtitle)) , array('data-validation' => $cssclass, 'multiple' => 'multiple', 'class' => 'inputbox mjtc-form-input-field mjtc-form-multi-select-field one '. esc_attr($specialClass))), MJTC_ALLOWED_TAGS);
                break;
            case 'file':
                $html .= '<span class="mjtc-attachment-file-box">';
                    $html .= '<input type="file" name="'.esc_attr($field->field).'" id="'.esc_attr($field->field).'"/>';
                $html .= '</span>';
                if($value != null){
                    $html .= wp_kses(MJTC_formfield::MJTC_hidden($field->field.'_1', 0), MJTC_ALLOWED_TAGS);
                    $html .= wp_kses(MJTC_formfield::MJTC_hidden($field->field.'_2',$value), MJTC_ALLOWED_TAGS);
                    $msFunction = "MJTC_deleteCutomUploadedFile('". esc_js($field->field) ."_1')";
                    $html .='<span class='.esc_attr($field->field).'_1>'. esc_html($value) .'( ';
                    $html .= "<a href='#' onClick=\"MJTC_deleteCutomUploadedFile('".esc_js($field->field)."_1')\"  class=".esc_attr($specialClass)." >". esc_html(__('Delete', 'majestic-support'))."</a>";
                    $html .= ' )</span>';
                }
                break;
                case 'termsandconditions':
                    if (isset(majesticsupport::$_data[0]->id)) {
                        break;
                    }
                    if (!empty($field->userfieldparams)) {
                        $obj_option = json_decode($field->userfieldparams,true);

                        $url = $obj_option['termsandconditions_link'];
                        if( isset($obj_option['termsandconditions_linktype']) && $obj_option['termsandconditions_linktype'] == 2){
                             $url  = get_permalink($obj_option['termsandconditions_page']);
                        }

                        $link_start = '<a href="' . esc_url($url) . '" class="termsandconditions_link_anchor" target="_blank" >';
                        $link_end = '</a>';

                        if(MJTC_majesticsupportphplib::MJTC_strstr($obj_option['termsandconditions_text'], '[link]') && MJTC_majesticsupportphplib::MJTC_strstr($obj_option['termsandconditions_text'], '[/link]')){
                            $label_string = MJTC_majesticsupportphplib::MJTC_str_replace('[link]', $link_start, $obj_option['termsandconditions_text']);
                            $label_string = MJTC_majesticsupportphplib::MJTC_str_replace('[/link]', $link_end, $label_string);
                        }else{
                            $label_string = $obj_option['termsandconditions_text'].'&nbsp;'.wp_kses($link_start, MJTC_ALLOWED_TAGS).esc_html($field->fieldtitle).wp_kses($link_end, MJTC_ALLOWED_TAGS);
                        }
                        $c_field_required = '';
                        if($field->required == 1){
                            $c_field_required = 'required';
                        }
                        // ticket terms and conditonions are required.
                        if($field->fieldfor == 1){
                            if (!isset($field->visibleparams)) {
                                $c_field_required = 'required';
                            }
                        }

                        $html .= '<div class="mjtc-support-custom-terms-and-condition-box ms-formfield-radio-button-wrap">';
                        $html .= '<input type="checkbox" class="radiobutton mjtc-support-append-radio-btn '.esc_attr($specialClass).'" value="1" id="' . esc_attr($field->field) . '" name="' . esc_attr($field->field) . '" data-validation="'.esc_attr($c_field_required).'">';
                        $html .= '<label for="' . esc_attr($field->field) . '" id="foruf_checkbox1">' . wp_kses($label_string, MJTC_ALLOWED_TAGS) . '</label>';
                        $html .= '</div>';
                    }
                    break;
        }
        $html .= '</div></div>';
        echo wp_kses($html, MJTC_ALLOWED_TAGS);

    }

    function MJTC_formCustomFieldsForSearch($field, &$i, $isadmin = 0) {
        if ($field->isuserfield != 1 || $field->userfieldtype == 'termsandconditions' || $field->userfieldtype == 'file')
            return false;
        $cssclass = "";
        $html = '';
        $i++;
        $required = $field->required;
        $div1 = 'mjtc-col-md-3 mjtc-filter-field-wrp';
        $div3 = 'mjtc-filter-value';

        $html = '<div class="' . esc_attr($div1) . '"> ';
        $html .= ' <div class="' . esc_attr($div3) . '">';
        if($isadmin == 1){
            $html = ''; // only field send
        }
        $readonly = ''; 
        $maxlength = '';
        $fvalue = "";
        $value = null;
        $userdataid = "";
        $userfielddataarray = array();
        if (isset(majesticsupport::$_data['filter']['params'])) {
            $userfielddataarray = majesticsupport::$_data['filter']['params'];
            $uffield = $field->field;
            //had to user || oprator bcz of radio buttons

            if (isset($userfielddataarray[$uffield]) || !empty($userfielddataarray[$uffield])) {
                $value = $userfielddataarray[$uffield];
            } else {
                $value = '';
            }
        }
        switch ($field->userfieldtype) {
            case 'text':
            case 'email':
            case 'admin_only':
                $html .= wp_kses(MJTC_formfield::MJTC_text($field->field, $value, array('class' => 'inputbox mjtc-form-input-field one', 'data-validation' => $cssclass,'placeholder' => majesticsupport::MJTC_getVarValue($field->fieldtitle) , $maxlength, $readonly)), MJTC_ALLOWED_TAGS);
                break;
            case 'date':
                $html .= wp_kses(MJTC_formfield::MJTC_text($field->field, $value, array('class' => 'custom_date mjtc-form-date-field one mjtc-form-input-field', 'data-validation' => $cssclass,'placeholder' => majesticsupport::MJTC_getVarValue($field->fieldtitle))), MJTC_ALLOWED_TAGS);
                break;
            case 'editor':
                $html .= wp_kses_post(wp_editor(isset($value) ? $value : '', $field->field, array('media_buttons' => false, 'data-validation' => $cssclass)));
                break;
            case 'textarea':
                $html .= wp_kses(MJTC_formfield::MJTC_textarea($field->field, $value, array('class' => 'inputbox mjtc-form-input-field one', 'data-validation' => $cssclass, 'rows' => $field->rows, 'cols' => $field->cols, $readonly)), MJTC_ALLOWED_TAGS);
                break;
            case 'checkbox':
                if (!empty($field->userfieldparams)) {
                    $comboOptions = array();
                    $obj_option = json_decode($field->userfieldparams);
                    $total_options= count($obj_option);
                    if($isadmin != 1){
                        if($total_options % 2 == 0) {
                            $field_width = 'style = " width:calc(100% / 2 - 4px); margin:2px;height:46px;"';
                        } else {
                            $field_width = 'style = " width:calc(100% / 3 - 4px); margin:2px;height:46px;"';
                        }
                    } else {
                        $field_width = '';
                    }
                    $i = 0;
                    if(empty($value))
                        $value = array();
                    $html .= '<div class="mjtc-form-cust-rad-fld-wrp mjtc-form-cust-ckb-fld-wrp">';
                    foreach ($obj_option AS $option) {
                        $option = html_entity_decode($option);
                        if( in_array($option, $value)){
                            $check = 'checked="true"';
                        }else{
                            $check = '';
                        }
                        $html .= '<div class="mjtc-support-radio-box" '. $field_width .'>';
                        $html .= '<input type="checkbox" ' . esc_attr($check) . ' class="radiobutton" value="' . esc_attr($option) . '" id="' . esc_attr($field->field) . '_' . esc_attr($i) . '" name="' . esc_attr($field->field) . '[]">';
                        $html .= '<label for="' . esc_attr($field->field) . '_' . esc_attr($i) . '" id="foruf_checkbox1">' . esc_html($option) . '</label>';
                        $html .= '</div>';
                        $i++;
                    }
                    $html .= '</div>';
                } else {
                    $comboOptions = array('1' => majesticsupport::MJTC_getVarValue($field->fieldtitle) );
                    $html .= wp_kses(MJTC_formfield::MJTC_checkbox($field->field, $comboOptions, $value, array('class' => 'radiobutton')), MJTC_ALLOWED_TAGS);
                }
                break;
            case 'radio':
                if($isadmin == 1){
                    $comboOptions = array();
                    if (!empty($field->userfieldparams)) {
                        $obj_option = json_decode($field->userfieldparams);
                        for ($i = 0; $i < count($obj_option); $i++) {
                            $obj_option[$i] = html_entity_decode($obj_option[$i]);
                            $comboOptions[$obj_option[$i]] = "$obj_option[$i]";
                        }
                    }
                    $msFunction = '';
                    if ($field->depandant_field != null) {
                        $wpnonce = wp_create_nonce("data-for-depandant-field");
                        $msFunction = "MJTC_getDataForDepandantField('". esc_js($wpnonce) ."','" . esc_js($field->field) . "','" . esc_js($field->depandant_field) . "',2);";
                    }
                    $html .= '<div class="mjtc-form-cust-rad-fld-wrp">';
                    $html .= wp_kses(MJTC_formfield::MJTC_radiobutton($field->field, $comboOptions, $value, array('data-validation' => $cssclass, "autocomplete" => "off", 'onclick' => $msFunction)), MJTC_ALLOWED_TAGS);
                    $html .= '</div>';
                }else{
                    $comboOptions = array();
                    if (!empty($field->userfieldparams)) {
                        $obj_option = json_decode($field->userfieldparams);
                        $total_options= count($obj_option);
                        if($total_options % 2 == 0) {
                            $field_width = 'style = " width:calc(100% / 2 - 4px); margin:2px;"';
                        } else {
                            $field_width = 'style = " width:calc(100% / 3 - 4px); margin:2px;"';
                        }
                        $i = 0;
                        $msFunction = '';
                        if ($field->depandant_field != null) {
                            $wpnonce = wp_create_nonce("data-for-depandant-field");
                            $msFunction = "MJTC_getDataForDepandantField('". esc_js($wpnonce) ."','" . esc_js($field->field) . "','" . esc_js($field->depandant_field) . "',2);";
                        }
                        $valuearray = array();
                        if ($value != '') {
                            $valuearray = MJTC_majesticsupportphplib::MJTC_explode(', ',$value);
                        }
                        $html .= '<div class="mjtc-form-cust-rad-fld-wrp">';
                        foreach ($obj_option AS $option) {
                            $check = '';
                            $option = html_entity_decode($option);
                            if(in_array($option, $valuearray)){
                                $check = 'checked';
                            }
                            $html .= '<div class="mjtc-support-radio-box" '. $field_width .'>';
                                $html .= '<input type="radio" ' . esc_attr($check) . ' class="radiobutton mjtc-support-radio-btn '.esc_attr($cssclass).'" value="' . esc_attr($option) . '" id="' . esc_attr($field->field) . '_' . esc_attr($i) . '" name="' . esc_attr($field->field) . '" data-validation ="'.esc_attr($cssclass).'" onclick = "'.esc_js($msFunction).'"> ';
                                $html .= '<label for="' . esc_attr($field->field) . '_' . esc_attr($i) . '" id="foruf_checkbox1">' . esc_html($option) . '</label>';
                            $html .= '</div>';
                            $i++;
                        }
                        $html .= '</div>';
                    }
                }

                break;
            case 'combo':
                $comboOptions = array();
                if (!empty($field->userfieldparams)) {
                    $obj_option = json_decode($field->userfieldparams);
                    foreach ($obj_option as $opt) {
                        $opt = html_entity_decode($opt);
                        $comboOptions[] = (object) array('id' => $opt, 'text' => $opt);
                    }
                }
                //code for handling dependent field
                $msFunction = '';
                if ($field->depandant_field != null) {
                    $wpnonce = wp_create_nonce("data-for-depandant-field");
                    $msFunction = "MJTC_getDataForDepandantField('". esc_js($wpnonce) ."','" . esc_js($field->field) . "','" . esc_js($field->depandant_field) . "',1);";
                }
                //end
                $html .= wp_kses(MJTC_formfield::MJTC_select($field->field, $comboOptions, $value, esc_html(__('Select', 'majestic-support')) . ' ' . esc_attr(majesticsupport::MJTC_getVarValue($field->fieldtitle)) , array('data-validation' => $cssclass, 'onchange' => $msFunction, 'class' => 'inputbox mjtc-form-select-field one')), MJTC_ALLOWED_TAGS);
                break;
            case 'depandant_field':
                $comboOptions = array();
                if (!empty($field->userfieldparams)) {
                    $obj_option = $this->MJTC_getDataForDepandantFieldByParentField($field->field, $userfielddataarray);
                    if (!empty($obj_option)) {
                        foreach ($obj_option as $opt) {
                            $opt = html_entity_decode($opt);
                            $comboOptions[] = (object) array('id' => $opt, 'text' => $opt);
                        }
                    }
                }
                //code for handling dependent field
                $msFunction = '';
                if ($field->depandant_field != null) {
                    $wpnonce = wp_create_nonce("data-for-depandant-field");
                    $msFunction = "MJTC_getDataForDepandantField('". esc_js($wpnonce) ."','" . esc_js($field->field) . "','" . esc_js($field->depandant_field) . "');";
                }
                //end
                $html .= wp_kses(MJTC_formfield::MJTC_select($field->field, $comboOptions, $value, esc_html(__('Select', 'majestic-support')) . ' ' . esc_attr(majesticsupport::MJTC_getVarValue($field->fieldtitle)) , array('data-validation' => $cssclass, 'onchange' => $msFunction, 'class' => 'inputbox mjtc-form-select-field one')), MJTC_ALLOWED_TAGS);
                break;
            case 'multiple':
                $comboOptions = array();
                if (!empty($field->userfieldparams)) {
                    $obj_option = json_decode($field->userfieldparams);
                    foreach ($obj_option as $opt) {
                        $opt = html_entity_decode($opt);
                        $comboOptions[] = (object) array('id' => $opt, 'text' => $opt);
                    }
                }
                $array = $field->field;
                $array .= '[]';
                $html .= wp_kses(MJTC_formfield::MJTC_select($array, $comboOptions, $value, esc_html(__('Select', 'majestic-support')) . ' ' . esc_attr(majesticsupport::MJTC_getVarValue($field->fieldtitle)) , array('data-validation' => $cssclass, 'multiple' => 'multiple','class' => 'inputbox mjtc-form-multi-select-field')), MJTC_ALLOWED_TAGS);
                break;
        }
        if($isadmin == 1){
            echo wp_kses($html, MJTC_ALLOWED_TAGS);
            return;
        }
        $html .= '</div></div>';
        echo wp_kses($html, MJTC_ALLOWED_TAGS);

    }

    function MJTC_showCustomFields($field, $fieldfor, $params) {

        $fvalue = '';

        if(!empty($params)){
            $data = json_decode($params,true);
            if(is_array($data) && $data != ''){
                if(array_key_exists($field->field, $data)){
                    $fvalue = $data[$field->field];
                    $fvalue = MJTC_majesticsupportphplib::MJTC_htmlspecialchars($fvalue);
                }
            }
        }
        if($field->userfieldtype=='file'){

           if($fvalue !=null){
                $path = admin_url("?page=majesticsupport_ticket&action=mstask&task=downloadbyname&id=".esc_attr(majesticsupport::$_data['custom']['ticketid'])."&name=".esc_attr($fvalue));
                $html = '
                    <div class="mjtc_supportattachment">
                        ' .  wp_kses($fvalue, MJTC_ALLOWED_TAGS) . '
                        <a class="button my-download-file-btn" target="_blank" href="' . esc_url($path) . '">' . esc_html(__('Download', 'majestic-support')) . '</a>
                    </div>';
                $fvalue = $html;
            }
        }elseif($field->userfieldtype=='date' && !empty($fvalue)){
            if(MJTC_majesticsupportphplib::MJTC_strpos($fvalue , '1970') !== false){
                $fvalue = "";
            } else {
                $fvalue = date_i18n(majesticsupport::$_config['date_format'],MJTC_majesticsupportphplib::MJTC_strtotime($fvalue));
            }
        }
        $return_array['title'] = $field->fieldtitle;
        $return_array['value'] = $fvalue;
        return $return_array;
    }

    function MJTC_userFieldsData($fieldfor, $listing = null, $multiformid = '') {
        if ($multiformid == '') {
            $multiformid = MJTC_includer::MJTC_getModel('ticket')->getDefaultMultiFormId();
        }
        if (MJTC_includer::MJTC_getObjectClass('user')->MJTC_isguest()) {
            $published = ' isvisitorpublished = 1 ';
        } else {
            $published = ' published = 1 ';
        }
        $inquery = '';
        if ($listing == 1) {
            $inquery = ' AND showonlisting = 1 ';
        }
        if (!is_admin()) {
            $inquery .= ' AND userfieldtype != "admin_only" ';
        }
        $query = "SELECT field,fieldtitle,isuserfield,userfieldtype,userfieldparams,multiformid  FROM " . majesticsupport::$_db->prefix . "mjtc_support_fieldsordering WHERE isuserfield = 1 AND " . $published . " AND fieldfor =" . esc_sql($fieldfor) . $inquery. " AND multiformid =" . esc_sql($multiformid). " ORDER BY ordering";
        $data = majesticsupport::$_db->get_results($query);
        return $data;
    }

    function userFieldsForSearch($fieldfor) {
        if (MJTC_includer::MJTC_getObjectClass('user')->MJTC_isguest()) {
            $inquery = ' isvisitorpublished = 1';
        } else {
            $inquery = ' published = 1 AND search_user =1';
        }
        if(!is_admin()){
            $inquery .= " AND userfieldtype != 'admin_only'";
        }
        if(!in_array('multiform', majesticsupport::$_active_addons)) {
            $multiformid = MJTC_includer::MJTC_getModel('ticket')->getDefaultMultiFormId();
            $inquery .= " AND multiformid = ".esc_sql($multiformid);
        }

        $query = "SELECT `rows`,`cols`,required,field,fieldtitle,isuserfield,userfieldtype,userfieldparams,depandant_field  FROM " . majesticsupport::$_db->prefix . "mjtc_support_fieldsordering WHERE isuserfield = 1 AND " . $inquery . " AND fieldfor =" . esc_sql($fieldfor) ." ORDER BY ordering ";
        $data = majesticsupport::$_db->get_results($query);
        return $data;
    }

    function MJTC_getDataForDepandantFieldByParentField($fieldfor, $data) {
        if (MJTC_includer::MJTC_getObjectClass('user')->MJTC_isguest()) {
            $published = ' isvisitorpublished = 1 ';
        } else {
            $published = ' published = 1 ';
        }
        $value = '';
        $returnarray = array();
        $query = "SELECT field from " . majesticsupport::$_db->prefix . "mjtc_support_fieldsordering WHERE isuserfield = 1 AND " . $published . " AND depandant_field ='" . esc_sql($fieldfor) . "'";
        $field = majesticsupport::$_db->get_var($query);
        if ($data != null) {
            foreach ($data as $key => $val) {
                $key = html_entity_decode($key);
                if ($key == $field) {
                    $value = $val;
                }
            }
        }
        $query = "SELECT userfieldparams from " . majesticsupport::$_db->prefix . "mjtc_support_fieldsordering WHERE isuserfield = 1 AND " . $published . " AND field ='" . esc_sql($fieldfor) . "'";
        $field = majesticsupport::$_db->get_var($query);
        $fieldarray = json_decode($field);
        foreach ($fieldarray as $key => $val) {
            $key = html_entity_decode($key);
            if ($value == $key)
                $returnarray = $val;
        }
        return $returnarray;
    }

}

?>
