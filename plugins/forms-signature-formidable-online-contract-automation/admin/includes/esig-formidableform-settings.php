<?php

if (!class_exists('ESIG_FORMIDABLEFORM_SETTING')):

    class ESIG_FORMIDABLEFORM_SETTING {

        private static $entryValues = null;

        public static function setEntryValue($value)
        {
            self::$entryValues = $value;
        }
        public static function getEntryValue()
        {
            return self::$entryValues;
        }

        private static $entryFormID = null;

        public static function setFormID($value)
        {
            self::$entryFormID = $value;
        }
        public static function getFormID()
        {
            return self::$entryFormID;
        }


        const FF_COOKIE = 'esig-formidable-temp-data';
        const FF_FORM_ID_META = 'esig_formidable_form_id';
        const FF_ENTRY_ID_META = 'esig_formidable_entry_id';
        const FF_TEMP_ENTRY_ID = 'esig_formidable_temp_entry_id';

        private static $tempCookie = null;
        private static $tempEntryId = null;

        public static function is_ff_requested_agreement($document_id) {
            $ff_form_id = WP_E_Sig()->meta->get($document_id, self::FF_FORM_ID_META);
            $ff_entry_id = WP_E_Sig()->meta->get($document_id, self::FF_ENTRY_ID_META);
            if ($ff_form_id && $ff_entry_id) {
                return true;
            }
            return false;
        }

        public static function is_ff_esign_required() {
            if (self::get_temp_settings()) {
                return true;
            } else {
                return false;
            }
        }

        public static function get_invite_url($invite_hash) {
            $document_checksum = WP_E_Sig()->document->document_checksum_by_id(WP_E_Sig()->invite->getdocumentid_By_invitehash($invite_hash));
            return WP_E_Sig()->invite->get_invite_url($invite_hash, $document_checksum);
        }

        public static function get_temp_settings() {
            if (!empty(self::$tempCookie)) {
                return json_decode(self::$tempCookie, true);
            }
            if (function_exists("ESIG_COOKIE") && ESIG_COOKIE(self::FF_COOKIE)) {
                return json_decode(stripslashes(ESIG_COOKIE(self::FF_COOKIE)), true);
            }
            return false;
        }

        public static function saveTempEntryId($entryId) {

            esig_setcookie(self::FF_ENTRY_ID_META, $entryId, 3600);
            // for instant cookie load. 
            $_COOKIE[self::FF_ENTRY_ID_META] = $entryId;
            self::$tempEntryId = $entryId;
        }

        public static function deleteTempEntryId() {
            esig_unsetcookie(self::FF_ENTRY_ID_META);
        }

        public static function getTempEntryId() {
            if (self::$tempEntryId) {
                return self::$tempEntryId;
            }
            if (ESIG_COOKIE(self::FF_ENTRY_ID_META)) {
                return ESIG_COOKIE(self::FF_ENTRY_ID_META);
            }
        }

        public static function save_temp_settings($value) {
            $json = json_encode($value);
            esig_setcookie(self::FF_COOKIE, $json, 600);
            // for instant cookie load. 
            $_COOKIE[self::FF_COOKIE] = $json;
            self::$tempCookie = $json;
        }

        public static function delete_temp_settings() {
            esig_unsetcookie(self::FF_COOKIE);
        }

        public static function save_esig_ff_meta($meta_key, $meta_index, $meta_value) {

            $temp_settings = self::get_temp_settings();
            if (!$temp_settings) {
                $temp_settings = array();
                $temp_settings[$meta_key] = array($meta_index => $meta_value);

                self::save_temp_settings($temp_settings);
            } else {

                if (array_key_exists($meta_key, $temp_settings)) {
                    $temp_settings[$meta_key][$meta_index] = $meta_value;
                    self::save_temp_settings($temp_settings);
                } else {
                    $temp_settings[$meta_key] = array($meta_index => $meta_value);
                    self::save_temp_settings($temp_settings);
                }
            }
        }

        public static function save_entry_value($documentId, $entry) {
            WP_E_Sig()->meta->add($documentId, "esig_formidable_submission_value", json_encode($entry));
        }

        public static function get_entry_value($documentId)
        {
            return json_decode(WP_E_Sig()->meta->get($documentId, "esig_formidable_submission_value"));
        }

        public static function get_submission_value($documentId, $field_id) {
            global $formidableValue;
            $entryValue = ESIG_FORMIDABLEFORM_SETTING::getEntryValue();
            if( $entryValue ){
                $formidableValue = $entryValue;
            }
            elseif (is_null($formidableValue)) {

                $formidableValue = json_decode(WP_E_Sig()->meta->get($documentId, "esig_formidable_submission_value"), true);
            }
            if (is_array($formidableValue) && array_key_exists($field_id, $formidableValue)) {
                return $formidableValue[$field_id];
            }
        }

        public static function field_type($fieldId) {
            $type = FrmDb::get_var('frm_fields', array('id' => $fieldId), 'type');
            return $type;
        }

        public static function field_label($fieldId) {
            $label = FrmDb::get_var('frm_fields', array('id' => $fieldId), 'name');
            return $label;
        }

        public static function returnValue($display, $label, $value) {
            if ($display == "label") {
                return $label;
            } elseif ($display == "label_value") {
                return $label . ": " . $value;
            } else {
                return $value;
            }
        }

        public static function prepare_display_value($entry, $field, $atts) {

            $field_value = isset($entry->metas[$field->id]) ? $entry->metas[$field->id] : false;

            if (FrmAppHelper::pro_is_installed()) {
                FrmProEntriesHelper::get_dynamic_list_values($field, $entry, $field_value);
            }

            if ($field->form_id == $entry->form_id || empty($atts['embedded_field_id'])) {
                return FrmEntriesHelper::display_value($field_value, $field, $atts);
            }

            // this is an embeded form
            $val = '';

            if (strpos($atts['embedded_field_id'], 'form') === 0) {
                //this is a repeating section
                $child_entries = FrmEntry::getAll(array('it.parent_item_id' => $entry->id));
            } else {
                // get all values for this field
                $child_values = isset($entry->metas[$atts['embedded_field_id']]) ? $entry->metas[$atts['embedded_field_id']] : false;

                if ($child_values) {
                    $child_entries = FrmEntry::getAll(array('it.id' => (array) $child_values));
                }
            }

            $field_value = array();

            if (!isset($child_entries) || !$child_entries || !FrmAppHelper::pro_is_installed()) {
                return $val;
            }

            foreach ($child_entries as $child_entry) {
                $atts['item_id'] = $child_entry->id;
                $atts['post_id'] = $child_entry->post_id;

                
                // get the value for this field -- check for post values as well
                $entry_val = FrmProEntryMetaHelper::get_post_or_meta_value($child_entry, $field);

                if ($entry_val) {
                    // foreach entry get display_value
                    $field_value[] = FrmEntriesHelper::display_value($entry_val, $field, $atts);
                }

                unset($child_entry);
            }

            return (array) $field_value;
        }

        public static function getDeviderValue($value, $fieldId, $fieldLabel, $option = "none") {

            if (!is_array($value)) {
                return false;
            }
            global $esig_formidable_entry;

            if (is_null($esig_formidable_entry)) {
                return false;
            }

            $retValue = false;
            // foreach ($value as $val) {
            $metaValue = unserialize(FrmDb::get_var('frm_item_metas', array('field_id' => $fieldId, 'item_id' => $esig_formidable_entry->id), 'meta_value'));
            //$childItemId  = json_decode($metaValue);

            $childFormId = FrmDb::get_var('frm_items', array('id' => $metaValue[0]), 'form_id');

            if ($option == "table") {

                $childFields = FrmFieldsHelper::get_form_fields($childFormId);

                $retValue = '<table class="table table-bordered"> <thead><tr>';

                foreach ($childFields as $field) {
                    $retValue .= '<th>' . $field->name . '</th>';
                }

                $retValue .= '</tr></thead><tbody>';
                $result_array = array();

                foreach ($childFields as $field) {


                    $embedded_field_id = ( $esig_formidable_entry->form_id != $field->form_id ) ? 'form' . $field->form_id : 0;
                    $atts = array(
                        'type' => $field->type,
                        'post_id' => $esig_formidable_entry->post_id,
                        'show_filename' => true,
                        'show_icon' => true,
                        'entry_id' => $esig_formidable_entry->id,
                        'embedded_field_id' => $embedded_field_id,
                    );

                    $child_entries = self::prepare_display_value($esig_formidable_entry, $field, $atts);
                    if (empty($result_array)) {

                        foreach ($child_entries as $key => $value) {
                            $result_array[][$key] = $value;
                        }
                    } else {

                        foreach ($child_entries as $key => $value) {
                            array_push($result_array[$key], $value);
                        }
                    }
                }
                foreach ($result_array as $result) {
                    $retValue .= '<tr>';
                    foreach ($result as $key => $value) {
                        $retValue .= '<td>' . $value . "</td>";
                    }
                    $retValue .= '</tr>';
                }


                $retValue .= '</tbody></table>';
            } else {
                $childFields = FrmFieldsHelper::get_form_fields($childFormId);

                foreach ($childFields as $field) {
                    $embedded_field_id = ( $esig_formidable_entry->form_id != $field->form_id ) ? 'form' . $field->form_id : 0;
                    $atts = array(
                        'type' => $field->type,
                        'post_id' => $esig_formidable_entry->post_id,
                        'show_filename' => true,
                        'show_icon' => true,
                        'entry_id' => $esig_formidable_entry->id,
                        'embedded_field_id' => $embedded_field_id,
                    );



                    $retValue .= $field->name . ": " . FrmEntriesHelper::prepare_display_value($esig_formidable_entry, $field, $atts) . "<br>";
                }
            }


            // }


            return $retValue;

            /*
              $retValue = false;
              foreach ($value as $val) {
              $ret = FrmDb::get_var('frm_item_metas', array('item_id' => $val), 'meta_value');
              $retValue .= $ret . " , ";
              }

              return rtrim($retValue, ' ,'); */
        }

        public static function getHtml($fieldId, $display, $fieldLabel, $result) {


            if (!$fieldId) {
                return false;
            }

            $content = FrmDb::get_var('frm_fields', array('id' => $fieldId), 'description');
            return $content;
        }

        public static function generate_value($documentId, $formId, $fieldId, $display = "value", $option = "none") {

            if(!class_exists('frmDb'))
            {
                return false;
            }

            $fieldType = self::field_type($fieldId);
            $fieldLabel = self::field_label($fieldId);
            if ($display == "label") {
                return $fieldLabel;
            }

           
            $value = self::get_submission_value($documentId, $fieldId);
            $underLineData = self::getDisplayType($documentId); 
           
            switch ($fieldType) {
                case 'address':
                    $result = self::getAddress($value);
                    return self::returnValue($display, $fieldLabel, $result);
                    break;
                case 'checkbox':
                    $result = self::get_checkbox($value);
                    return self::returnValue($display, $fieldLabel, $result);
                    break;
                case 'name':
                    $result = FrmEntriesHelper::display_value($value, FrmField::getOne($fieldId), array());
                    if ($display == "label_value") 
                    {
                        return $fieldLabel . ": " . $result;
                    }
                    return $result;
                    break;    
                case 'email':
                    
                    $result = ($underLineData == "underline") ?  '<a href="mailto:' . $value . '" target="_blank"><u>' . $value . '</u></a>' :  '<a href="mailto:' . $value . '" target="_blank">' . $value . '</a>';
                    return self::returnValue($display, $fieldLabel, $result);
                    break;
                case 'html':

                    return self::getHtml($fieldId, $display, $fieldLabel, $result);
                    break;
                case 'url':
                     $result =  ($underLineData == "underline") ? '<a href="' . $value . '" target="_blank"><u>' . $value . '</u></a>' : '<a href="' . $value . '" target="_blank">' . $value . '</a>';
                    return self::returnValue($display, $fieldLabel, $result);
                    break;
                case 'date':
                    if(empty($value))
                    {
                        return false;
                        break;
                    }
                    $newDate = date(get_option('date_format'), strtotime($value));
                    return self::returnValue($display, $fieldLabel, $newDate);
                    break;
                case 'file':
                    if ($display == "image") {
                        $newImage = esig_encoded_image(wp_get_attachment_url($value));

                        $result = '<img src="' . $newImage . '">';
                        return $result;
                    } else {
                        $result = '<a href="' . wp_get_attachment_url($value) . '" target="_blank">' . wp_get_attachment_url($value) . '</a>';
                        return self::returnValue($display, $fieldLabel, $result);
                    }
                    break;
                case 'divider':
                    $result = self::getDeviderValue($value, $fieldId, $fieldLabel, $option);
                    return $result;
                    break;
                case 'image':
                    if ($display == "image") {
                        $newImage = esig_encoded_image($value);

                        $result = '<img src="' . $newImage . '">';
                        return $result;
                    } else {
                        $result = '<a class="dont-break-out" href="' . $value . '" target="_blank">' . $value . '</a>';
                        return self::returnValue($display, $fieldLabel, $result);
                    }

                    break;
                default :

                    /*  if (is_array($value)) {  
                      return self::returnArrayValue($value, $display, $fieldId, $fieldLabel);
                      } */
                    if ($display == "value") {
                        return $value;
                    } elseif ($display == "label_value") {
                        return $fieldLabel . ": " . $value;
                    }
                //return $value;
            }
        }

        private static function returnArrayValue($value, $display, $fieldId, $fieldLabel) {

            $result = false;
            foreach ($value as $val) {
                if (is_numeric($val)) {

                    $res = FrmDb::get_var('frm_item_metas', array('field_id' => $fieldId, 'item_id' => $val), 'meta_value');

                    $result .= $res;
                } else {
                    $result .= $val;
                }
            }
            if ($display == "value") {
                return $result;
            } elseif ($display == "label_value") {
                return $fieldLabel . ": " . $result;
            }
            return $result;
        }

        public static function get_checkbox($value) {

            if (!$value) {
                return false;
            }

            $html = '';

            if (is_array($value)) {
                foreach ($value as $val) {
                    $html .= '<br><label><input type="checkbox" onclick="javascript: return false;" checked="checked" style="margin-right: 8px;" >' . $val . "</label>";
                }
            } else {
                $html .= '<br><label><input type="checkbox" onclick="javascript: return false;" checked="checked" style="margin-right: 8px;" >' . $value . "</label>";
            }

            return $html;
        }

        /**
         * Return address type value  
         * @param type $value
         * @return boolean|string
         */
        public static function getAddress($value) {

            if (!$value) {
                return false;
            }

            if (is_array($value)) {
                $html = "";
                foreach ($value as $val) {
                    $html .= $val . "<br>";
                }
                return $html;
            }
            return $value;
        }

        public static function display_value($formidableValue, $underLineData) {
            $result = '';
            if ($underLineData == "underline") {
                if (is_array($formidableValue)) {
                    foreach ($formidableValue as $val) {
                        $result .= '<u>' . $val . '</u>';
                    }
                } else {
                    $result = '<u>' . $formidableValue . '</u>';
                }
            } else {
                if (is_array($formidableValue)) {
                    foreach ($formidableValue as $val) {
                        $result .= $val;
                    }
                } else {
                    $result = $formidableValue;
                }
            }
            return $result;
        }

        public static function enableReminder($documentId, $formAction) 
        {
            $reminderSet = esig_formidable_get('enable_signing_reminder_email', $formAction);

            if ($reminderSet == '1') {

                $reminderEmail = $formAction['reminder_email'];
                $firstReminderSend = $formAction['first_reminder_send'];
                $expireReminder = $formAction['expire_reminder'];

                if($reminderEmail < 1 || $firstReminderSend < 1  || $expireReminder < 1)
                {
                    return false ; 
                }

                $esigFormidableReminderSettings = array(
                    "esig_reminder_for" => $reminderEmail,
                    "esig_reminder_repeat" => $firstReminderSend,
                    "esig_reminder_expire" => $expireReminder,
                );

                WP_E_Sig()->meta->add($documentId, "esig_reminder_settings_", json_encode($esigFormidableReminderSettings));
                WP_E_Sig()->meta->add($documentId, "esig_reminder_send_", "1");
            }
            
            $underlineData = esig_formidable_get('underline_data', $formAction);
            WP_E_Sig()->meta->add($documentId, 'esig_formidable_underlinedata', $underlineData);
        }

        public static function getInviteUrl($entryId) {
            $documentId = WP_E_Sig()->meta->metadata_by_keyvalue('esig_formidable_entry_id', $entryId);
            if (!$documentId) {
                return false;
            }
            $docHash = WP_E_Sig()->document->document_checksum_by_id($documentId);
            $inviteHash = WP_E_Sig()->invite->getInviteHash_By_documentID($documentId);
            return WP_E_Sig()->invite->get_invite_url($inviteHash, $docHash);
        }

        public static function getFormidableFieldValue($entry,$fieldId,$displayFormat)
        {
            $value = FrmEntryMeta::get_meta_value($entry, $fieldId);
            return FrmEntriesHelper::display_value($value, FrmField::getOne($fieldId),$displayFormat);
        }


        public static function getDisplayType($documentId)
        {
            global $underLineData;
            if(!is_null($underLineData)) return $underLineData;
            return WP_E_Sig()->meta->get($documentId, 'esig_formidable_underlinedata');
        }

    }

    
endif;