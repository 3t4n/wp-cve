<?php

if (!defined('ABSPATH'))
    die('Restricted Access');

class MJTC_fieldorderingModel {

    function getFieldOrderingForList($fieldfor) {
        if(!is_numeric($fieldfor)){
            return false;
        }
        $formid = majesticsupport::$_data['formid'];
        if (isset($formid) && $formid != null) {
            $inquery = " AND multiformid = ".esc_sql($formid);
        }
    	else{
            $inquery = " AND multiformid = ".esc_sql(MJTC_includer::MJTC_getModel('ticket')->getDefaultMultiFormId());
    	}

        // Data
        $query = "SELECT * FROM `" . majesticsupport::$_db->prefix . "mjtc_support_fieldsordering` WHERE fieldfor = ".esc_sql($fieldfor);
        $query .= $inquery." ORDER BY ordering ";

        majesticsupport::$_data[0] = majesticsupport::$_db->get_results($query);
        if (majesticsupport::$_db->last_error != null) {
            MJTC_includer::MJTC_getModel('systemerror')->addSystemError();
        }
        return;
    }

    function changePublishStatus($id, $status) {
        if (!is_numeric($id))
            return false;
        if ($status == 'publish') {
            $query = "UPDATE `" . majesticsupport::$_db->prefix . "mjtc_support_fieldsordering` SET published = 1 WHERE id = " . esc_sql($id) . " AND cannotunpublish = 0";
            majesticsupport::$_db->query($query);
            if (majesticsupport::$_db->last_error != null) {
                MJTC_includer::MJTC_getModel('systemerror')->addSystemError();
            }
            MJTC_message::MJTC_setMessage(esc_html(__('Field mark as published', 'majestic-support')),'updated');
        } elseif ($status == 'unpublish') {
            $query = "UPDATE `" . majesticsupport::$_db->prefix . "mjtc_support_fieldsordering` SET published = 0 WHERE id = " . esc_sql($id) . " AND cannotunpublish = 0";
            majesticsupport::$_db->query($query);
            if (majesticsupport::$_db->last_error != null) {
                MJTC_includer::MJTC_getModel('systemerror')->addSystemError();
            }
            MJTC_message::MJTC_setMessage(esc_html(__('Field mark as unpublished', 'majestic-support')),'updated');
        }
        return;
    }

    function changeVisitorPublishStatus($id, $status) {
        if (!is_numeric($id))
            return false;
        if ($status == 'publish') {
            $query = "SELECT userfieldtype FROM " . majesticsupport::$_db->prefix . "mjtc_support_fieldsordering WHERE id = " . esc_sql($id);
            $userfieldtype = majesticsupport::$_db->get_var($query);
            if($userfieldtype == 'admin_only'){
                MJTC_message::MJTC_setMessage(esc_html(__('Field cannot be mark as published', 'majestic-support')),'error');
            }else{
                $query = "UPDATE `" . majesticsupport::$_db->prefix . "mjtc_support_fieldsordering` SET isvisitorpublished = 1 WHERE id = " . esc_sql($id) . " AND cannotunpublish = 0";
                majesticsupport::$_db->query($query);
                if (majesticsupport::$_db->last_error != null) {
                    MJTC_includer::MJTC_getModel('systemerror')->addSystemError();
                }
                MJTC_message::MJTC_setMessage(esc_html(__('Field mark as published', 'majestic-support')),'updated');
            }
        } elseif ($status == 'unpublish') {
            $query = "UPDATE `" . majesticsupport::$_db->prefix . "mjtc_support_fieldsordering` SET isvisitorpublished = 0 WHERE id = " . esc_sql($id) . " AND cannotunpublish = 0";
            majesticsupport::$_db->query($query);
            if (majesticsupport::$_db->last_error != null) {
                MJTC_includer::MJTC_getModel('systemerror')->addSystemError();
            }
            MJTC_message::MJTC_setMessage(esc_html(__('Field mark as unpublished', 'majestic-support')),'updated');
        }
        return;
    }

    function changeRequiredStatus($id, $status) {
        if (!is_numeric($id))
            return false;

        if ($status == 'required') {
            $query = "UPDATE `" . majesticsupport::$_db->prefix . "mjtc_support_fieldsordering` SET required = 1 WHERE id = " . esc_sql($id) . " AND cannotunpublish = 0";
            majesticsupport::$_db->query($query);
            if (majesticsupport::$_db->last_error != null) {
                MJTC_includer::MJTC_getModel('systemerror')->addSystemError();
            }
            MJTC_message::MJTC_setMessage(esc_html(__('Field mark as required', 'majestic-support')),'updated');
        } elseif ($status == 'unrequired') {
            $query = "UPDATE `" . majesticsupport::$_db->prefix . "mjtc_support_fieldsordering` SET required = 0 WHERE id = " . esc_sql($id) . " AND cannotunpublish = 0";
            majesticsupport::$_db->query($query);
            if (majesticsupport::$_db->last_error != null) {
                MJTC_includer::MJTC_getModel('systemerror')->addSystemError();
            }
            MJTC_message::MJTC_setMessage(esc_html(__('Field mark as not required', 'majestic-support')),'updated');
        }
        return;
    }

    function changeOrder($id, $action) {
        if (!is_numeric($id))
            return false;
        if ($action == 'down') {
            $query = "UPDATE `" . majesticsupport::$_db->prefix . "mjtc_support_fieldsordering` AS f1, `" . majesticsupport::$_db->prefix . "mjtc_support_fieldsordering` AS f2
                        SET f1.ordering = f1.ordering - 1 WHERE f1.ordering = f2.ordering + 1 AND f1.fieldfor = f2.fieldfor
                        AND f2.id = " . esc_sql($id);
            majesticsupport::$_db->query($query);
            $query = " UPDATE `" . majesticsupport::$_db->prefix . "mjtc_support_fieldsordering` SET ordering = ordering + 1 WHERE id = " . esc_sql($id);
            majesticsupport::$_db->query($query);
            MJTC_message::MJTC_setMessage(esc_html(__('Field ordering down', 'majestic-support')),'updated');
        } elseif ($action == 'up') {
            $query = "UPDATE `" . majesticsupport::$_db->prefix . "mjtc_support_fieldsordering` AS f1, `" . majesticsupport::$_db->prefix . "mjtc_support_fieldsordering` AS f2 SET f1.ordering = f1.ordering + 1
                        WHERE f1.ordering = f2.ordering - 1 AND f1.fieldfor = f2.fieldfor AND f2.id = " . esc_sql($id);
            majesticsupport::$_db->query($query);
            $query = " UPDATE `" . majesticsupport::$_db->prefix . "mjtc_support_fieldsordering` SET ordering = ordering - 1 WHERE id = " . esc_sql($id);
            majesticsupport::$_db->query($query);
            MJTC_message::MJTC_setMessage(esc_html(__('Field ordering up', 'majestic-support')),'updated');
        }
        return;
    }

    function getFieldsOrderingforForm($fieldfor,$formid='') {
        if (!is_numeric($fieldfor))
            return false;
        if (MJTC_includer::MJTC_getObjectClass('user')->MJTC_isguest()) {
            $published = ' isvisitorpublished = 1 ';
        } else {
            $published = ' published = 1 ';
        }
	    if(!isset($formid) || $formid==''){
		    $formid = MJTC_includer::MJTC_getModel('ticket')->getDefaultMultiFormId();
	    }
        $query = "SELECT  * FROM `" . majesticsupport::$_db->prefix . "mjtc_support_fieldsordering` WHERE ".$published." AND fieldfor =  " . esc_sql($fieldfor) ." AND multiformid =  " . esc_sql($formid) . " ORDER BY ordering ";
        majesticsupport::$_data['fieldordering'] = majesticsupport::$_db->get_results($query);
        return;
    }

    function checkIsFieldRequired($field,$formid='') {
        if(!isset($formid) || $formid==''){
            $formid = MJTC_includer::MJTC_getModel('ticket')->getDefaultMultiFormId();
        }
        if (MJTC_includer::MJTC_getObjectClass('user')->MJTC_isguest()) {
            $published = ' isvisitorpublished = 1 ';
        } else {
            $published = ' published = 1 ';
        }
        $query = "SELECT required FROM `" . majesticsupport::$_db->prefix . "mjtc_support_fieldsordering` WHERE ".$published." AND fieldfor =  1 AND  field =  '".esc_sql($field)."' AND multiformid =  " . esc_sql($formid);
        $required = majesticsupport::$_db->get_var($query);
        return $required;
    }

    function storeUserField($data) {
        if (empty($data)) {
            return false;
        }
        $data = majesticsupport::MJTC_sanitizeData($data);// MJTC_sanitizeData() function uses wordpress santize functions
        if ($data['isuserfield'] == 1) {
            // value to add as field ordering
            if ($data['id'] == '') { // only for new
                $query = "SELECT max(ordering) FROM " . majesticsupport::$_db->prefix . "mjtc_support_fieldsordering WHERE fieldfor=".esc_sql($data['fieldfor']);
                $var = majesticsupport::$_db->get_var($query);
                $data['ordering'] = $var + 1;
                if(isset($data['userfieldtype']) && ($data['userfieldtype'] == 'file' || $data['userfieldtype'] == 'termsandconditions' ) ){
                    $data['cannotsearch'] = 1;
                    $data['cannotshowonlisting'] = 1;
                }else{
                    $data['cannotshowonlisting'] = 0;
                    $data['cannotsearch'] = 0;
                }
                $query = "SELECT max(id) FROM " . majesticsupport::$_db->prefix . "mjtc_support_fieldsordering ";
                $var = majesticsupport::$_db->get_var($query);
                $var = $var + 1;
                $fieldname = 'ufield_'.esc_attr($var);
            }else{
                $fieldname = $data['field'];
            }
            if ($data['userfieldtype'] == 'termsandconditions') { // only for terms and conditions
                $data['required'] = 1;
            }

            $params = array();
            //code for depandetn field
            if (isset($data['userfieldtype']) && $data['userfieldtype'] == 'depandant_field') {
                if ($data['id'] != '') {
                    //to handle edit case of depandat field
                    $data['arraynames'] = $data['arraynames2'];
                }
                $flagvar = $this->updateParentField($data['parentfield'], $fieldname, $data['fieldfor']);
                if ($flagvar == false) {
                    MJTC_message::MJTC_setMessage(esc_html(__('Parent field has not been stored', 'majestic-support')), 'error');
                }
                if (!empty($data['arraynames'])) {
                    $valarrays = MJTC_majesticsupportphplib::MJTC_explode(',', $data['arraynames']);
                    $empty_flag = 0;
                    $key_flag = '';
                    foreach ($valarrays as $key => $value) {
                        if($key != $key_flag){
                            $key_flag = $key;
                            $empty_flag = 0;
                        }
                        $keyvalue = $value;
                        if($value != ''){
                            $value = MJTC_majesticsupportphplib::MJTC_str_replace(' ','__',$value);
                            $value = MJTC_majesticsupportphplib::MJTC_str_replace('.','___',$value);
                        }
                        $keyvalue = MJTC_majesticsupportphplib::MJTC_htmlentities($keyvalue);
                        $params[$keyvalue] = array_filter($data[$value]);
                        $empty_flag = 1;
                    }
                    if($empty_flag == 0){
                        MJTC_message::MJTC_setMessage(esc_html(__('Please Insert At least one value for every option', 'majestic-support')), 'error');
                        return 2 ;
                    }
                }
            }
            if (!empty($data['values'])) {
                foreach ($data['values'] as $key => $value) {
                    if ($value != null) {
                        $params[] = trim($value);
                    }
                }
            }
            
            if (isset($data['visibleParent']) && $data['visibleParent'] != '' && isset($data['visibleValue']) && $data['visibleValue'] != '' && isset($data['visibleCondition']) && $data['visibleCondition'] != ''){
                $visible['visibleParentField'] = $fieldname;
                $visible['visibleParent'] = $data['visibleParent'];
                $visible['visibleCondition'] = $data['visibleCondition'];
                $visible['visibleValue'] = $data['visibleValue'];
                $visible_array = array_map(array($this,'sanitize_custom_field'), $visible);
                $data['visibleparams'] = json_encode($visible_array);

                $query = "SELECT visible_field FROM " . majesticsupport::$_db->prefix . "mjtc_support_fieldsordering WHERE id = " . esc_sql($data['visibleParent']);
                $old_fieldname = majesticsupport::$_db->get_var($query);
                $new_fieldname = $fieldname;
                if ($data['id'] != '') {
                    $query = "SELECT id,visible_field FROM " . majesticsupport::$_db->prefix . "mjtc_support_fieldsordering WHERE visible_field  LIKE '%".esc_sql($fieldname)."%'";
                    $query_run = majesticsupport::$_db->get_row($query);
                    if (isset($query_run)) {
                        $query_fieldname = $query_run->visible_field;
                        if($query_fieldname != ''){
                            $query_fieldname =  MJTC_majesticsupportphplib::MJTC_str_replace(','.$fieldname, '', $query_fieldname);
                            $query_fieldname =  MJTC_majesticsupportphplib::MJTC_str_replace($fieldname, '', $query_fieldname);
                        }
                        $query = "UPDATE `" . majesticsupport::$_db->prefix . "mjtc_support_fieldsordering` SET visible_field = '" . esc_sql($query_fieldname) . "' WHERE id = " . esc_sql($query_run->id);
                        majesticsupport::$_db->query($query);
                    }

                    if($old_fieldname != ''){
                        $old_fieldname =  MJTC_majesticsupportphplib::MJTC_str_replace(','.$fieldname, '', $old_fieldname);
                        $old_fieldname =  MJTC_majesticsupportphplib::MJTC_str_replace($fieldname, '', $old_fieldname);
                    }
                }
                if (isset($old_fieldname) && $old_fieldname != '') {
                    $new_fieldname = $old_fieldname.','.$new_fieldname;
                }
                // update value
                $query = "UPDATE `" . majesticsupport::$_db->prefix . "mjtc_support_fieldsordering` SET visible_field = '" . esc_sql($new_fieldname) . "' WHERE id = " . esc_sql($data['visibleParent']);
                majesticsupport::$_db->query($query);
                if (majesticsupport::$_db->last_error != null) {

                    MJTC_includer::MJTC_getModel('systemerror')->addSystemError();
                }
                
            } else if($data['id'] != ''){
                $data['visibleparams'] = '';
                $query = "SELECT visibleparams FROM " . majesticsupport::$_db->prefix . "mjtc_support_fieldsordering WHERE id = " . esc_sql($data['id']);
                $visibleparams = majesticsupport::$_db->get_var($query);
                if (isset($visibleparams)) {
                    $decodedData = json_decode($visibleparams);
                    $visibleParent = $decodedData->visibleParent;
                }else{
                    $visibleParent = -1;
                }
                $query = "SELECT visible_field FROM " . majesticsupport::$_db->prefix . "mjtc_support_fieldsordering WHERE id = " . esc_sql($visibleParent);
                $old_fieldname = majesticsupport::$_db->get_var($query);
                $new_fieldname = $fieldname;
                $query = "SELECT id,visible_field FROM " . majesticsupport::$_db->prefix . "mjtc_support_fieldsordering WHERE visible_field  LIKE '%".esc_sql($fieldname)."%'";
                $query_run = majesticsupport::$_db->get_row($query);
                if (isset($query_run)) {
                    $query_fieldname = $query_run->visible_field;
                    if($query_fieldname != ''){
                        $query_fieldname =  MJTC_majesticsupportphplib::MJTC_str_replace(','.$fieldname, '', $query_fieldname);
                        $query_fieldname =  MJTC_majesticsupportphplib::MJTC_str_replace($fieldname, '', $query_fieldname);
                    }
                    $query = "UPDATE `" . majesticsupport::$_db->prefix . "mjtc_support_fieldsordering` SET visible_field = '" . esc_sql($query_fieldname) . "' WHERE id = " . esc_sql($query_run->id);
                    majesticsupport::$_db->query($query);
                }
            }

            if (isset($data['userfieldtype']) && $data['userfieldtype'] == 'termsandconditions') { // to manage terms and condition field
                $params['termsandconditions_text'] = $data['termsandconditions_text'];
                $params['termsandconditions_linktype'] = $data['termsandconditions_linktype'];
                $params['termsandconditions_link'] = $data['termsandconditions_link'];
                $params['termsandconditions_page'] = $data['termsandconditions_page'];
            }

            $params_array = array_map(array($this,'sanitize_custom_field'), $params);
            $data['userfieldparams'] = json_encode($params_array, JSON_UNESCAPED_UNICODE);

            // for admin_only
            if(isset($data['userfieldtype']) && ($data['userfieldtype'] == 'admin_only') ){
                $data['isvisitorpublished'] = 0;
            }
        }else{
            $fieldname = $data['field'];
        }

        $data['field'] = $fieldname;
        $data['section'] = 10;

        if (!empty($data['depandant_field']) && $data['depandant_field'] != null ) {

            $query = "SELECT * FROM " . majesticsupport::$_db->prefix . "mjtc_support_fieldsordering where
            field = '". esc_sql($data['depandant_field'])."'";
            $child = majesticsupport::$_db->get_row($query);
            $parent = $data;
            $flagvar = $this->updateChildField($parent, $child);
            if ($flagvar == false) {
                MJTC_message::MJTC_setMessage(esc_html(__('Child fields has not been stored', 'majestic-support')), 'error');
            }
        }

        $row = MJTC_includer::MJTC_getTable('fieldsordering');
        $data = MJTC_includer::MJTC_getModel('majesticsupport')->stripslashesFull($data);// remove slashes with quotes.
        $error = 0;
        if (!$row->bind($data)) {
            $error = 1;
        }
        if (!$row->store()) {
            $error = 1;
        }

        if ($error == 1) {
            MJTC_includer::MJTC_getModel('systemerror')->addSystemError();
            MJTC_message::MJTC_setMessage(esc_html(__('Field has not been stored', 'majestic-support')), 'error');
        } else {
            MJTC_message::MJTC_setMessage(esc_html(__('Field has been stored', 'majestic-support')), 'updated');
        }
        return 1;
    }

    function updateField($data) {
        if (empty($data)) {
            return false;
        }
        $inquery = '';
        $clasue = '';
        if(isset($data['fieldtitle']) && $data['fieldtitle'] != null){
            $inquery .= $clasue." fieldtitle = '". esc_sql($data['fieldtitle']) ."'";
            $clasue = ' , ';
        }
        if(isset($data['published']) && $data['published'] != null){
            $inquery .= $clasue." published = ". esc_sql($data['published']);
            $clasue = ' , ';
        }
        if(isset($data['isvisitorpublished']) && $data['isvisitorpublished'] != null){
            $inquery .= $clasue." isvisitorpublished = ". esc_sql($data['isvisitorpublished']);
            $clasue = ' , ';
        }
        if(isset($data['required']) && $data['required'] != null){
            $inquery .= $clasue." required = ". esc_sql($data['required']);
            $clasue = ' , ';
        }
        if(isset($data['search_user']) && $data['search_user'] != null){
            $inquery .= $clasue." search_user = ". esc_sql($data['search_user']);
            $clasue = ' , ';
        }
        if(isset($data['showonlisting']) && $data['showonlisting'] != null){
            $inquery .= $clasue." showonlisting = ". esc_sql($data['showonlisting']);
            $clasue = ' , ';
        }

        $query = "UPDATE `" . majesticsupport::$_db->prefix . "mjtc_support_fieldsordering` SET ".$inquery." WHERE id = " . esc_sql($data['id']) ;
        majesticsupport::$_db->query($query);
        if (majesticsupport::$_db->last_error != null) {
            MJTC_includer::MJTC_getModel('systemerror')->addSystemError();
        }
        MJTC_message::MJTC_setMessage(esc_html(__('Field has been updated', 'majestic-support')),'updated');

        return;
    }

    function updateParentField($parentfield, $field, $fieldfor) {
        if(!is_numeric($parentfield)) return false;

        $query = "UPDATE `" . majesticsupport::$_db->prefix . "mjtc_support_fieldsordering` SET depandant_field = '" . esc_sql($field) . "' WHERE id = " . esc_sql($parentfield) . " AND fieldfor = " . esc_sql($fieldfor);
        majesticsupport::$_db->query($query);
        if (majesticsupport::$_db->last_error != null) {
            MJTC_includer::MJTC_getModel('systemerror')->addSystemError();
        }
        return true;
    }

    function updateChildField($parent, $child){
        $userfieldparams = json_decode( $child->userfieldparams);

        $childNew =  new stdclass();
        foreach ($parent['values'] as $key => $value) {
            if ($userfieldparams->$key) {
               $childNew->$value[0] = $userfieldparams->$key[0];
            } else {
                $childNew->$value[0] = "";
            }
        }
        $childNew = json_encode( $childNew );
        $child->userfieldparams = $childNew;
        $query = "UPDATE `" . majesticsupport::$_db->prefix . "mjtc_support_fieldsordering` SET userfieldparams = '" . esc_sql($childNew) . "' WHERE id = " . esc_sql($child->id);
        majesticsupport::$_db->query($query);
        if (majesticsupport::$_db->last_error != null) {

            MJTC_includer::MJTC_getModel('systemerror')->addSystemError();
        }
        return true;
    }

    function getFieldsForComboByFieldFor() {
        $nonce = MJTC_request::MJTC_getVar('_wpnonce');
        if (! wp_verify_nonce( $nonce, 'get-fields-for-combo-by-fieldfor') ) {
            die( 'Security check Failed' );
        }
        $fieldfor = MJTC_request::MJTC_getVar('fieldfor');
        $parentfield = MJTC_request::MJTC_getVar('parentfield');
        $wherequery = '';
        if(isset($parentfield) && $parentfield !='' ){
            $query = "SELECT id FROM " . majesticsupport::$_db->prefix . "mjtc_support_fieldsordering WHERE fieldfor = ".esc_sql($fieldfor)." AND (userfieldtype = 'radio' OR userfieldtype = 'combo'OR userfieldtype = 'depandant_field') AND depandant_field = '" . esc_sql($parentfield) . "' ";
            $parent = majesticsupport::$_db->get_var($query);
            $wherequery = ' OR id = '.esc_sql($parent);
        }
        $query = "SELECT fieldtitle AS text ,id FROM " . majesticsupport::$_db->prefix . "mjtc_support_fieldsordering WHERE fieldfor = ".esc_sql($fieldfor)." AND (userfieldtype = 'radio' OR userfieldtype = 'combo' OR userfieldtype = 'depandant_field') AND (depandant_field = '' ".$wherequery." ) ";
        $data = majesticsupport::$_db->get_results($query);
        if(isset($parentfield) && $parentfield !='' ){
            $query = "SELECT id FROM " . majesticsupport::$_db->prefix . "mjtc_support_fieldsordering WHERE fieldfor = ".esc_sql($fieldfor)." AND (userfieldtype = 'radio' OR userfieldtype = 'combo'OR userfieldtype = 'depandant_field') AND depandant_field = '" . esc_sql($parentfield) . "' ";
            $parent = majesticsupport::$_db->get_var($query);
        }
        $msFunction = 'getDataOfSelectedField();';
        $html = MJTC_formfield::MJTC_select('parentfield', $data, (isset($parent) && $parent !='') ? $parent : '', esc_html(__('Select', 'majestic-support')) .'&nbsp;'. esc_html(__('Parent Field', 'majestic-support')), array('onchange' => $msFunction, 'class' => 'inputbox one mjtc-form-select-field', 'data-validation' => 'required'));
        $html = MJTC_majesticsupportphplib::MJTC_htmlentities($html);
        $data = json_encode($html);
        return $data;
    }

    function getFieldsForVisibleCombobox($fieldfor, $multiformid, $field='', $cid='') {
        $wherequery = '';
        if(isset($field) && $field !='' ){
            $query = "SELECT id FROM " . majesticsupport::$_db->prefix . "mjtc_support_fieldsordering WHERE fieldfor = ".esc_sql($fieldfor)." AND (userfieldtype = 'combo') AND visible_field = '" . esc_sql($field) . "' ";
            $parent = majesticsupport::$_db->get_var($query);
            if ($parent) {
                $wherequery = ' OR id = '.esc_sql($parent);
            }
        }
        $wherequeryforedit = '';
        if(isset($cid) && $cid !='' ){
            $wherequeryforedit = ' AND id != '.esc_sql($cid);
        }
        
        $query = "SELECT fieldtitle AS text ,id FROM " . majesticsupport::$_db->prefix . "mjtc_support_fieldsordering WHERE (fieldfor = ".esc_sql($fieldfor)." AND multiformid = '".esc_sql($multiformid)."' AND field = 'department' ".$wherequeryforedit.$wherequery.") OR (fieldfor = " . esc_sql($fieldfor) . " AND multiformid = '".esc_sql($multiformid)."' AND userfieldtype = 'combo' ".$wherequeryforedit.$wherequery.')';
        $data = majesticsupport::$_db->get_results($query);
        return $data;
    }

    function getChildForVisibleCombobox() {
        $nonce = MJTC_request::MJTC_getVar('_wpnonce');
        if (! wp_verify_nonce( $nonce, 'get-child-for-visible-combobox') ) {
            die( 'Security check Failed' );
        }
        $perentid = MJTC_request::MJTC_getVar('val');
        if (!is_numeric($perentid)){
            return false;
        }

        $query = "SELECT isuserfield, field FROM `" . majesticsupport::$_db->prefix . "mjtc_support_fieldsordering` WHERE id = " . esc_sql($perentid);
        $fieldType = majesticsupport::$_db->get_row($query);
        if (isset($fieldType->isuserfield) && $fieldType->isuserfield == 1) {
            $query = "SELECT userfieldparams AS params FROM `" . majesticsupport::$_db->prefix . "mjtc_support_fieldsordering` WHERE id = " . esc_sql($perentid);
            $options = majesticsupport::$_db->get_var($query);
            $options = json_decode($options);
            foreach ($options as $key => $option) {
                $fieldtypes[$key] = (object) array('id' => $option, 'text' => majesticsupport::MJTC_getVarValue($option));
            }
        } else if ($fieldType->field == 'department') {
            $query = "SELECT departmentname AS text ,id FROM " . majesticsupport::$_db->prefix . "mjtc_support_departments";
            $fieldtypes = majesticsupport::$_db->get_results($query);
        }
        $combobox = false;
        if(!empty($fieldtypes)){
            $combobox = MJTC_formfield::MJTC_select('visibleValue', $fieldtypes, isset(majesticsupport::$_data[0]['userfield']->required) ? majesticsupport::$_data[0]['userfield']->required : 0, '', array('class' => 'inputbox one mjtc-form-select-field mjtc-form-input-field-visible'));
        }
        return $combobox;
    }

    function getSectionToFillValues() {
        $nonce = MJTC_request::MJTC_getVar('_wpnonce');
        if (! wp_verify_nonce( $nonce, 'get-section-to-fill-values') ) {
            die( 'Security check Failed' );
        }
        $field = MJTC_request::MJTC_getVar('pfield');
        if(!is_numeric($field)){
            return false;
        }
        $query = "SELECT userfieldparams FROM " . majesticsupport::$_db->prefix . "mjtc_support_fieldsordering WHERE id=".esc_sql($field);
        $data = majesticsupport::$_db->get_var($query);
        $datas = json_decode($data);
        $html = '';
        $fieldsvar = '';
        $comma = '';
        foreach ($datas as $data) {
            if(is_array($data)){
                for ($i = 0; $i < count($data); $i++) {
                    $fieldsvar .= $comma . "$data[$i]";
                    $textvar = $data[$i];
                    if($textvar != ''){
                        $textvar = MJTC_majesticsupportphplib::MJTC_str_replace(' ','__',$textvar);
                        $textvar = MJTC_majesticsupportphplib::MJTC_str_replace('.','___',$textvar);
                    }
                    $divid = $textvar;
                    $textvar .='[]';
                    $html .= "<div class='ms-user-dd-field-wrap'>";
                    $html .= "<div class='ms-user-dd-field-title'>" . esc_html($data[$i]) . "</div>";
                    $html .= "<div class='ms-user-dd-field-value combo-options-fields' id=" . esc_attr($divid) . ">
                                    <span class='input-field-wrapper'>
                                        " . wp_kses(MJTC_formfield::MJTC_text($textvar, '', array('class' => 'inputbox one user-field')), MJTC_ALLOWED_TAGS) . "
                                        <img class='input-field-remove-img' src='" . esc_url(MJTC_PLUGIN_URL) . "includes/images/delete.png' />
                                    </span>
                                    <input type='button' class='ms-button-link button user-field-val-button' id='depandant-field-button' onClick='getNextField(\"" . esc_js($divid) . "\", this);'  value='Add More' />
                                </div>";
                    $html .= "</div>";
                    $comma = ',';
                }
            }else{
                $fieldsvar .= $comma . "$data";
                $textvar = $data;
                if($textvar != ''){
                    $textvar = MJTC_majesticsupportphplib::MJTC_str_replace(' ','__',$textvar);
                    $textvar = MJTC_majesticsupportphplib::MJTC_str_replace('.','___',$textvar);
                }
                $divid = $textvar;
                $textvar .='[]';
                $html .= "<div class='ms-user-dd-field-wrap'>";
                $html .= "<div class='ms-user-dd-field-title'>" . esc_html($data) . "</div>";
                $html .= "<div class='ms-user-dd-field-value combo-options-fields' id=" . esc_attr($divid) . ">
                                <span class='input-field-wrapper'>
                                    " . MJTC_formfield::MJTC_text($textvar, '', array('class' => 'inputbox one user-field')) . "
                                    <img class='input-field-remove-img' src='" . esc_url(MJTC_PLUGIN_URL) . "includes/images/delete.png' />
                                </span>
                                <input type='button' class='ms-button-link button user-field-val-button' id='depandant-field-button' onClick='getNextField(\"" . esc_js($divid) . "\", this);'  value='Add More' />
                            </div>";
                $html .= "</div>";
                $comma = ',';
            }

        }
        $html .= " <input type='hidden' name='arraynames' value='" . esc_attr($fieldsvar) . "' />";
        $html = MJTC_majesticsupportphplib::MJTC_htmlentities($html);
        $html = json_encode($html);
        return $html;
    }

    function getOptionsForFieldEdit() {
        $nonce = MJTC_request::MJTC_getVar('_wpnonce');
        if (! wp_verify_nonce( $nonce, 'get-options-for-field-edit') ) {
            die( 'Security check Failed' );
        }
        $field = MJTC_request::MJTC_getVar('field');
		if(!is_numeric($field)) return false;
        $yesno = array(
            (object) array('id' => 1, 'text' => esc_html(__('Yes', 'majestic-support'))),
            (object) array('id' => 0, 'text' => esc_html(__('No', 'majestic-support'))));

        $query = "SELECT * FROM " . majesticsupport::$_db->prefix . "mjtc_support_fieldsordering WHERE id=".esc_sql($field);
        $data = majesticsupport::$_db->get_row($query);

        $html = '<div class="userpopup-top">
                    <div class="userpopup-heading" >
                    ' . esc_html(__("Edit Field", 'majestic-support')) . '
                    </div>
                    <img id="popup_cross" class="userpopup-close" onClick="close_popup();" src="' . esc_url(MJTC_PLUGIN_URL) . 'includes/images/close-icon-white.png" alt="'.esc_html(__('Close','majestic-support')).'">
                </div>';
        $adminurl = admin_url("?page=majesticsupport_fieldordering&task=savefeild&formid=".esc_attr($data->multiformid));
        $html .= '<form id="adminForm" class="popup-field-from" method="post" action="' . esc_url(wp_nonce_url($adminurl ,"save-feild")).'">';
        $html .= '<div class="popup-field-wrapper">
                    <div class="popup-field-title">' . esc_html(__('Field Title', 'majestic-support')) . '<font class="required-notifier">*</font></div>
                    <div class="popup-field-obj">' . wp_kses(MJTC_formfield::MJTC_text('fieldtitle', isset($data->fieldtitle) ? $data->fieldtitle : 'text', '', array('class' => 'inputbox one', 'data-validation' => 'required')), MJTC_ALLOWED_TAGS) . '</div>
                </div>';
        if ($data->cannotunpublish == 0 || $data->cannotshowonlisting == 0) {
            $html .= '<div class="popup-field-wrapper">
                        <div class="popup-field-title">' . esc_html(__('User Published', 'majestic-support')) . '</div>
                        <div class="popup-field-obj">' . wp_kses(MJTC_formfield::MJTC_select('published', $yesno, isset($data->published) ? $data->published : 0, '', array('class' => 'inputbox one', 'data-validation' => 'required')), MJTC_ALLOWED_TAGS) . '</div>
                    </div>';
            if ($data->userfieldtype != 'admin_only') {
                $html .= '<div class="popup-field-wrapper">
                        <div class="popup-field-title">' . esc_html(__('Visitor Published', 'majestic-support')) . '</div>
                        <div class="popup-field-obj">' . wp_kses(MJTC_formfield::MJTC_select('isvisitorpublished', $yesno, isset($data->isvisitorpublished) ? $data->isvisitorpublished : 0, '', array('class' => 'inputbox one', 'data-validation' => 'required')), MJTC_ALLOWED_TAGS) . '</div>
                    </div>';
            }

            $html .= '<div class="popup-field-wrapper">
                    <div class="popup-field-title">' . esc_html(__('Required', 'majestic-support')) . '</div>
                    <div class="popup-field-obj">' . wp_kses(MJTC_formfield::MJTC_select('required', $yesno, isset($data->required) ? $data->required : 0, '', array('class' => 'inputbox one', 'data-validation' => 'required')), MJTC_ALLOWED_TAGS) . '</div>
                </div>';
        }
        if ($data->cannotsearch == 0) {
            $html .= '<div class="popup-field-wrapper">
                        <div class="popup-field-title">' . esc_html(__('User Search', 'majestic-support')) . '</div>
                        <div class="popup-field-obj">' . wp_kses(MJTC_formfield::MJTC_select('search_user', $yesno, isset($data->search_user) ? $data->search_user : 0, '', array('class' => 'inputbox one', 'data-validation' => 'required')), MJTC_ALLOWED_TAGS) . '</div>
                    </div>';
        }
        if ($data->isuserfield == 1 || $data->cannotshowonlisting == 0) {
            $html .= '<div class="popup-field-wrapper">
                        <div class="popup-field-title">' . esc_html(__('Show On Listing', 'majestic-support')) . '</div>
                        <div class="popup-field-obj">' . wp_kses(MJTC_formfield::MJTC_select('showonlisting', $yesno, isset($data->showonlisting) ? $data->showonlisting : 0, '', array('class' => 'inputbox one', 'data-validation' => 'required')), MJTC_ALLOWED_TAGS) . '</div>
                    </div>';
        }
        $html .= wp_kses(MJTC_formfield::MJTC_hidden('form_request', 'majesticsupport'), MJTC_ALLOWED_TAGS);
        $html .= wp_kses(MJTC_formfield::MJTC_hidden('id', $data->id), MJTC_ALLOWED_TAGS);
        $html .= wp_kses(MJTC_formfield::MJTC_hidden('isuserfield', $data->isuserfield), MJTC_ALLOWED_TAGS);
        $html .= wp_kses(MJTC_formfield::MJTC_hidden('fieldfor', $data->fieldfor), MJTC_ALLOWED_TAGS);
        $html .='<div class="mjtc-submit-container mjtc-col-lg-10 mjtc-col-md-10 mjtc-col-md-offset-1 mjtc-col-md-offset-1">
                    ' . wp_kses(MJTC_formfield::MJTC_submitbutton('save', esc_html(__('Save', 'majestic-support')), array('class' => 'button')), MJTC_ALLOWED_TAGS);
        if ($data->isuserfield == 1) {
            $html .= '<a class="button" style="margin-left:10px;" id="user-field-anchor" href="?page=majesticsupport_fieldordering&mjslay=adduserfeild&majesticsupportid=' . esc_attr($data->id) .'&fieldfor='.esc_attr($data->fieldfor).'&formid='.esc_attr($data->multiformid).'"> ' . esc_html(__('Advanced', 'majestic-support')) . ' </a>';
        }

        $html .='</div>
            </form>';
        $html = MJTC_majesticsupportphplib::MJTC_htmlentities($html);
        return json_encode($html);
    }

    function deleteUserField($id){
        if (is_numeric($id) == false)
           return false;
        $query = "SELECT field,field,fieldfor FROM `".majesticsupport::$_db->prefix."mjtc_support_fieldsordering` WHERE id = " . esc_sql($id);
        $result = majesticsupport::$_db->get_row($query);
        if ($this->userFieldCanDelete($result) == true) {
            $row = MJTC_includer::MJTC_getTable('fieldsordering');
            if (!$row->delete($id)) {
                MJTC_includer::MJTC_getModel('systemerror')->addSystemError();
                MJTC_message::MJTC_setMessage(esc_html(__('Field has not been deleted', 'majestic-support')),'error');
            } else {
                $query = "SELECT id,visible_field FROM `" . majesticsupport::$_db->prefix . "mjtc_support_fieldsordering` WHERE visible_field LIKE '%".esc_sql($result->field)."%'";
                $results = majesticsupport::$_db->get_results($query);
                foreach ($results as $value) {
                    if($value->visible_field != ''){
                        $visible_field =  MJTC_majesticsupportphplib::MJTC_str_replace($result->field.',', '', $value->visible_field);
                        $visible_field =  MJTC_majesticsupportphplib::MJTC_str_replace(','.$result->field, '', $visible_field);
                        $visible_field =  MJTC_majesticsupportphplib::MJTC_str_replace($result->field, '', $visible_field);

                        $query = "UPDATE `" . majesticsupport::$_db->prefix . "mjtc_support_fieldsordering` SET visible_field = '".esc_sql($visible_field)."' WHERE id = ".esc_sql($value->id);
                        majesticsupport::$_db->query($query);
                        if (majesticsupport::$_db->last_error != null) {

                            MJTC_includer::MJTC_getModel('systemerror')->addSystemError();
                        }
                    }
                    
                }
                $query = "SELECT id FROM `" . majesticsupport::$_db->prefix . "mjtc_support_fieldsordering` WHERE depandant_field = '".esc_sql($result->field)."'";
                $result = majesticsupport::$_db->get_var($query);
                if (isset($result)) {
                    $query = "UPDATE `" . majesticsupport::$_db->prefix . "mjtc_support_fieldsordering` SET depandant_field = '' WHERE id = ".esc_sql($result);
                    majesticsupport::$_db->query($query);
                }
                MJTC_message::MJTC_setMessage(esc_html(__('Field has been deleted', 'majestic-support')),'updated');
            }
        }else{
            MJTC_message::MJTC_setMessage(esc_html(__('Field has not been deleted', 'majestic-support')),'error');
        }
        return false;
    }

    function enforceDeleteUserField($id){
        if (is_numeric($id) == false)
           return false;
        $query = "SELECT field,fieldfor FROM `".majesticsupport::$_db->prefix."mjtc_support_fieldsordering` WHERE id = ".esc_sql($id);
        $result = majesticsupport::$_db->get_row($query);
        if ($this->userFieldCanDelete($result) == true) {
            $row = MJTC_includer::MJTC_getTable('fieldsordering');
            $row->delete($id);
        }
        return false;
    }

    function userFieldCanDelete($field) {
        $fieldname = $field->field;
        $fieldfor = $field->fieldfor;

        $table = "tickets";
        $query = ' SELECT
                    ( SELECT COUNT(id) FROM `' . majesticsupport::$_db->prefix . 'mjtc_support_'.$table.'` WHERE
                        params LIKE \'%"' . esc_sql($fieldname) . '":%\'
                    )
                    AS total';
        $total = majesticsupport::$_db->get_var($query);
        if ($total > 0)
            return false;
        else
            return true;
    }

    function getUserfieldsfor($fieldfor,$multiformid='') {
        if (!is_numeric($fieldfor))
            return false;
        if (MJTC_includer::MJTC_getObjectClass('user')->MJTC_isguest()) {
            $published = ' isvisitorpublished = 1 ';
        } else {
            $published = ' published = 1 ';
        }
        $inquery = '';
        if (isset($multiformid) && $multiformid != '') {
            $inquery = " AND multiformid = ".esc_sql($multiformid);
        }
        $query = "SELECT field,userfieldparams,userfieldtype,fieldtitle FROM `" . majesticsupport::$_db->prefix . "mjtc_support_fieldsordering` WHERE fieldfor = " . esc_sql($fieldfor) . " AND isuserfield = 1 AND " . $published;
        $query .= $inquery." ORDER BY field ";
        $fields = majesticsupport::$_db->get_results($query);
        return $fields;
    }

    function getUserUnpublishFieldsfor($fieldfor) {
        if (!is_numeric($fieldfor))
            return false;
        if (MJTC_includer::MJTC_getObjectClass('user')->MJTC_isguest()) {
            $published = ' isvisitorpublished = 0 ';
        } else {
            $published = ' published = 0 ';
        }
        $query = "SELECT field FROM `" . majesticsupport::$_db->prefix . "mjtc_support_fieldsordering` WHERE fieldfor = " . esc_sql($fieldfor) . " AND isuserfield = 1 AND " . $published;
        $fields = majesticsupport::$_db->get_results($query);
        return $fields;
    }

    function getFieldTitleByFieldfor($fieldfor) {
        if (!is_numeric($fieldfor))
            return false;
        if (MJTC_includer::MJTC_getObjectClass('user')->MJTC_isguest()) {
            $published = ' isvisitorpublished = 1 ';
        } else {
            $published = ' published = 1 ';
        }
        $query = "SELECT field,fieldtitle FROM `" . majesticsupport::$_db->prefix . "mjtc_support_fieldsordering` WHERE fieldfor = " . esc_sql($fieldfor)." AND " . $published;
        $fields = majesticsupport::$_db->get_results($query);
        $fielddata = array();
        foreach ($fields as $value) {
            $fielddata[$value->field] = $value->fieldtitle;
        }
        return $fielddata;
    }

    function getUserFieldbyId($id,$fieldfor) {
        if ($id) {
            if (is_numeric($id) == false)
                return false;
            $query = "SELECT * FROM " . majesticsupport::$_db->prefix . "mjtc_support_fieldsordering WHERE id = " . esc_sql($id);
            majesticsupport::$_data[0]['userfield'] = majesticsupport::$_db->get_row($query);
            $params = majesticsupport::$_data[0]['userfield']->userfieldparams;
            $visibleparams = majesticsupport::$_data[0]['userfield']->visibleparams;
            majesticsupport::$_data[0]['userfieldparams'] = !empty($params) ? json_decode($params, True) : '';
            majesticsupport::$_data[0]['visibleparams'] = !empty($visibleparams) ? json_decode($visibleparams, True) : '';
            if (!empty($visibleparams)) {
                $pId = json_decode(majesticsupport::$_data[0]['userfield']->visibleparams);
                $query = "SELECT isuserfield FROM " . majesticsupport::$_db->prefix . "mjtc_support_fieldsordering WHERE id = " . esc_sql($pId->visibleParent);
                $fieldType = majesticsupport::$_db->get_var($query);
                if (isset($fieldType) && $fieldType == 1) { 
                    $visibleparams = json_decode($visibleparams, True);
                    $query = "SELECT userfieldparams AS params FROM `" . majesticsupport::$_db->prefix . "mjtc_support_fieldsordering` WHERE id = " . esc_sql($visibleparams['visibleParent']);
                    $options = majesticsupport::$_db->get_var($query);
                    $options = json_decode($options);
                    foreach ($options as $key => $option) {
                        $fieldtypes[$key] = (object) array('id' => $option, 'text' => majesticsupport::MJTC_getVarValue($option));
                    }
                } else {
                    $query = "SELECT departmentname AS text ,id FROM " . majesticsupport::$_db->prefix . "mjtc_support_departments";
                    $fieldtypes = majesticsupport::$_db->get_results($query);
                }
                majesticsupport::$_data[0]['visibleValue'] = $fieldtypes;
            }else{
                majesticsupport::$_data[0]['visibleValue'] = '';
            }
        }
        majesticsupport::$_data[0]['fieldfor'] = $fieldfor;
        return;
    }
    function getFieldsForListing($fieldfor) {
        if (is_numeric($fieldfor) == false)
            return false;
        $query = "SELECT field, showonlisting FROM " . majesticsupport::$_db->prefix . "mjtc_support_fieldsordering WHERE  fieldfor =  " . esc_sql($fieldfor) ." ORDER BY ordering";
        $fields = majesticsupport::$_db->get_results($query);
        $fielddata = array();
        foreach ($fields AS $field) {
            $fielddata[$field->field] = $field->showonlisting;
        }
        return $fielddata;
    }

    function DataForDepandantField(){
        $nonce = MJTC_request::MJTC_getVar('_wpnonce');
        if (! wp_verify_nonce( $nonce, 'data-for-depandant-field') ) {
            die( 'Security check Failed' );
        }
        $val = MJTC_request::MJTC_getVar('fvalue');
        $childfield = MJTC_request::MJTC_getVar('child');
        $query = "SELECT userfieldparams,fieldtitle,depandant_field,field FROM `".majesticsupport::$_db->prefix."mjtc_support_fieldsordering` WHERE field = '".esc_sql($childfield)."'";
        $data = majesticsupport::$_db->get_row($query);
        $decoded_data = json_decode($data->userfieldparams);
        $comboOptions = array();
        $flag = 0;
        foreach ($decoded_data as $key => $value) {
            $key = html_entity_decode($key);
            if($key==$val){
               for ($i=0; $i <count($value) ; $i++) {
                   $comboOptions[] = (object)array('id' => $value[$i], 'text' => $value[$i]);
                   $flag = 1;
               }
            }
        }
        $msFunction = '';
        if ($data->depandant_field != null) {
            $wpnonce = wp_create_nonce("data-for-depandant-field");
            $msFunction = "MJTC_getDataForDepandantField('".$wpnonce."','" . $data->field . "','" . $data->depandant_field . "',1);";
        }
        $textvar =  ($flag == 1) ?  esc_html(__('Select', 'majestic-support')).' '.$data->fieldtitle : '';
        $html = MJTC_formfield::MJTC_select($childfield, $comboOptions, '',$textvar, array('data-validation' => '','class' => 'inputbox one mjtc-form-select-field mjtc-support-custom-select', 'onchange' => $msFunction));
        $html = MJTC_majesticsupportphplib::MJTC_htmlentities($html);
        $phtml = json_encode($html);
        return $phtml;
    }

    function sanitize_custom_field($arg) {
        if (is_array($arg)) {
            return array_map(array($this,'sanitize_custom_field'), $arg);
        }
        return MJTC_majesticsupportphplib::MJTC_htmlentities($arg, ENT_QUOTES, 'UTF-8');
    }

    function MJTC_getDataForVisibleField($field) {
        $field = esc_sql($field);
        $field_array = MJTC_majesticsupportphplib::MJTC_str_replace(",", "','", $field);
        $query = "SELECT visibleparams FROM ". majesticsupport::$_db->prefix ."mjtc_support_fieldsordering WHERE  field IN ('". $field_array ."')";
        $fields = majesticsupport::$_db->get_results($query);
        $data = array();
        foreach ($fields as $item) {
            $d = json_decode($item->visibleparams);
            $d->visibleParentField = Self::getChildForVisibleField($d->visibleParentField);
            $data[] = $d;
        }
        return $data;
    }

    static function getChildForVisibleField($field) {
        $field = esc_sql($field);
        $oldField = MJTC_majesticsupportphplib::MJTC_explode(',',$field);
        $newField = $oldField[sizeof($oldField) - 1];
        $query = "SELECT visible_field FROM ". majesticsupport::$_db->prefix ."mjtc_support_fieldsordering WHERE  field = '". $newField ."'";
        $queryRun = majesticsupport::$_db->get_var($query);
        if (isset($queryRun) && $queryRun != '') {
            $data = MJTC_majesticsupportphplib::MJTC_explode(',',$queryRun);
            foreach ($data as $value) {
                $field = $field.','.$value;
                $field = Self::getChildForVisibleField($field);
            }
        }        
        return $field;
    }

}

?>
