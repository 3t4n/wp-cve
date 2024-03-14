<?php

if (!class_exists('ESIG_NF_SETTING')) :

    class ESIG_NF_SETTING
    {

        private static $nfentryId = null;

        public static function nfSetEntryID($ID)
        {
            self::$nfentryId = $ID;
        }
        public static function nfGetEntryID()
        {
            return self::$nfentryId;
        }


        const ESIG_NF_COOKIE = 'esig-nf-redirect';

        private static $tempCookie = null;

        public static function is_ninja_three()
        {

            if (defined('Ninja_Forms::VERSION') && version_compare(Ninja_Forms::VERSION, '3.0') >= 0) {
                return true;
            } else {
                return false;
            }
        }

        public static function fieldExists($formdId, $fieldId)
        {
            $result =  Esign_Query::dbconnect()->get_var(Esign_Query::dbconnect()->prepare("SELECT COUNT(*) from " . Esign_Query::dbconnect()->prefix . "nf3_fields where id = %d and parent_id=%d", $fieldId, $formdId));
            return wp_validate_boolean($result);
        }

        public static function get_field_type($field_id)
        {
            global $esig_ninja_form;

            if (!is_null($esig_ninja_form)) {
                $fields = $esig_ninja_form->get_field($field_id);
                if (!$fields) return false;
                return $fields->get_setting('type');
            }
            $fields = Ninja_Forms()->form()->get_field($field_id);
            if (!$fields) return false;
            return $fields->get_setting('type');
        }

        
        public static function get_field_label($field_id)
        {
            global $esig_ninja_form;
            if (!is_null($esig_ninja_form)) {
                $fields = $esig_ninja_form->get_field($field_id);
                return $fields->get_setting('label');
            }
            $fields = Ninja_Forms()->form()->get_field($field_id);
            return $fields->get_setting('label');
        }
        

        public static function get_repeater($post_id, $form_id, $field_id)
        {
            $value = self::genarate_all_value($post_id, $form_id, $field_id);

            if ($value && is_array($value)) {

                $result = false;
                foreach ($value as $val) {
                    $res = $val['value'];
                    if (is_array($res)) {
                        foreach ($res as $entry) {
                            $result .= "&nbsp;" . $entry .  ", ";
                        }
                    } else {
                        $result .= "&nbsp;" . $res .  ", ";
                    }
                }

                return rtrim($result, ', ');
            }

            $fields = Ninja_Forms()->form()->get_field($field_id);
            return $fields->get_setting('default');
        }

        public static function get_html($post_id, $form_id, $field_id)
        {
            $value = self::genarate_all_value($post_id, $form_id, $field_id);
            if ($value) {
                return $value;
            }

            $fields = Ninja_Forms()->form()->get_field($field_id);
            return $fields->get_setting('default');
        }

        public static function get_country($post_id, $form_id, $field_id)
        {
            $value = self::genarate_all_value($post_id, $form_id, $field_id);
            $countrylist = Ninja_Forms()->config('CountryList');
            $country = array_search($value, $countrylist);
            return $country;
        }

        public static function get_file_upload($post_id, $form_id, $field_id)
        {
            $values = self::genarate_all_value($post_id, $form_id, $field_id);
            $result = false;
            if (is_array($values)) {
                foreach ($values as $value) {
                    if (is_array($value)) {
                        continue;
                    }
                    $result .= '<a href="' . $value . '">' . basename($value) . '</a><br>';
                }
            }
            return $result;
        }

        public static function get_table_editor($post_id, $form_id, $field_id)
        {
            $values = self::genarate_all_value($post_id, $form_id, $field_id);

            $result = false;
            if (is_array($values)) {
                $headers = $values['headers'];
                $result .= '<table class="table table-bordered">';

                $result .= '<thead><tr>';
                foreach ($headers as $header) {
                    $result .= '<th>' . $header . '</th>';
                }
                $result .= '</tr></thead>';

                $records = $values['body'];
                foreach ($records as $record) {

                    $result .= '<tbody><tr>';

                    foreach ($record as $value) {
                        $result .= '<th>' . $value . '</th>';
                    }

                    $result .= '</tr></tbody>';
                }

                //  $result .= '<a href="'. $value .'">'. basename($value) . '</a><br>';

                $result .= '</table>';
            }
            return $result;
        }

        public static function get_state($post_id, $form_id, $field_id, $display, $label)
        {

            $value = self::genarate_all_value($post_id, $form_id, $field_id);
            if ($display == "value") {
                return $value;
            }

            $statelist = Ninja_Forms()->config('StateList');
            $state = array_search($value, $statelist);

            if ($display == "label") {
                return $label;
            }

            if ($display == "label_value") {
                return $label . ": " . $state;
            }
        }

        public static function calculate($documentId, $value)
        {
            if (is_array($value)) {
                // $inputs=$value[0];
                return $value;
            }
            /* else {
              $inputs =$value;
              } */

            $inputs = $value;
            preg_match_all("/{([^}]*)}/", $inputs, $matches);

            if (empty($matches[0])) {
                return $inputs;
            }
            global $esigNinjaSubmissions;
            if (!is_null($esigNinjaSubmissions)) {
                $dataArray = $esigNinjaSubmissions;
            } else {
                $dataArray = unserialize(WP_E_Sig()->meta->get($documentId, 'esig_ninja_submission_value'));
            }

            $calculations = esig_nfds_get('calculations', $dataArray);
            $result = false;
            if ($calculations) {
                $dataList = unserialize($calculations[0]);

                foreach ($dataList as $key => $data) {
                    $calc = '{calc:' . $key . '}';

                    $inputs = str_replace($calc, $data['value'], $inputs, $count);
                }
            }

            return $inputs;
        }

        public static function get_listcheckbox($type, $post_id, $form_id, $field_id, $display, $label, $option)
        {
            $value = self::genarate_all_value($post_id, $form_id, $field_id);
            if (!is_array($value)) {
                return false;
            }
            if (is_array($value) && $option == "check") {
                $items = '';
                foreach ($value as $key => $item) {
                    if ($item) {
                        $items .= '<li><span style="font-size:16px;">&#10003;</span>' . $item . '</li>';
                    }
                }
                return "<ul class='esig-checkbox-tick'>$items</ul>";
            } else {
                return self::generate_array_value($type, $label, $field_id, $display, $value);
            }
        }

        public static function get_radio($post_id, $form_id, $field_id, $display, $label, $option)
        {

            $value = self::genarate_all_value($post_id, $form_id, $field_id);
            if (!$value) return false;

            global $esig_ninja_form;

            if (!is_null($esig_ninja_form)) {
                $fields = $esig_ninja_form->get_field($field_id);
            } else {
                $fields = Ninja_Forms()->form()->get_field($field_id);
            }
            // print_r($fields);
            $options = $fields->get_setting('options');

         //   $label = false;

            foreach ($options as $option) {
                $definedValue = esig_nfds_get("value", $option);
                if ($definedValue == $value) $sublabel = esig_nfds_get("label", $option);
            }

            switch ($display) {
                case 'label':
                    return $sublabel;
                    break;
                case 'value':
                    return $value;
                    break;
                case 'label_value':
                    return $sublabel . ":" . $value;
                    break;
            }

            return false;
        }

        public static function get_checkbox($post_id, $form_id, $entryId, $field_id, $display, $label, $option)
        {

            $value = self::genarate_all_value($post_id, $form_id, $field_id);

            global $esig_ninja_form;
            $result = $value;
            if (!is_null($esig_ninja_form)) {

                $nfCheckbox = new NF_Fields_Checkbox();
                $field = $esig_ninja_form->get_field($field_id);
                $result = apply_filters('ninja_forms_custom_columns', $value, $field, $entryId);
            }


            switch ($display) {
                case 'label':
                    return $label;
                    break;
                case 'value':
                    if ($result) return $result;
                    return $value;
                    break;
                case 'label_value':
                    if ($result) return $label . ":" . $result;
                    return $label . ":" . $value;
                    break;
            }

            return false;
        }

        public static function displayEmail($value, $display, $label,$document_id)
        {
            $notification_id = self::ninjaNotificationId($document_id);
            global $esig_ninja_form;
            if (is_null($esig_ninja_form)) {
                $esig_ninja_form = Ninja_Forms()->form($form_id);
            }

            if (self::is_ninja_three()) {

                $underline_data = $esig_ninja_form->get_action($notification_id)->get_settings('underline_data');
            } else {
                $underline_data = Ninja_Forms()->notification($notification_id)->get_setting('underline_data');
            }
            
            $cssClass = ($underline_data == "underline") ? 'class="esig-underline-link"' : false ;
            if ($display == "value") {
                return '<a '. $cssClass .' href="mailto:' . $value . '">' . $value . '</a>';
            } else if ($display == "label_value") {
                return  $label . ": " . '<a '. $cssClass .' href="mailto:' . $value . '">' . $value . '</a>';
            }
        }

        public static function get_value($document_id, $form_id, $field_id, $display = "value", $option = false)
        {

            global $esig_ninja_form;


            $post_id = self::nfGetEntryID();

            if (empty($post_id)) {
                $post_id = self::ninjaEntryId($document_id);
            }
            
            
            //WP_E_Sig()->meta->get($document_id, 'esig_ninja_entry_id');

            // $form_id = WP_E_Sig()->meta->get($document_id, 'esig_ninja_form_id');

            if (is_null($esig_ninja_form)) {
                $esig_ninja_form = Ninja_Forms()->form($form_id);
            }

            if (self::is_ninja_three()) {

                $type = self::get_field_type($field_id);
                
                if (!$type) return false;

                $label = self::get_field_label($field_id);


                if ($display == 'label' && $type != "liststate" && $type != "listradio" && $type != "checkbox" && $type != "listcheckbox" && $type != "listmultiselect") {
                    return $label;
                }

                switch ($type) {
                    case 'html':
                        $value = self::get_html($post_id, $form_id, $field_id);

                        if ($display == "value") {
                            return $value;
                        } elseif ($display == "label_value") {
                            return $label . ": " . $value;
                        }
                        break;
                    case 'repeater':
                        $value = self::get_repeater($post_id, $form_id, $field_id);

                        if ($display == "value") {
                            return $value;
                        } elseif ($display == "label_value") {
                            return $label . ": " . $value;
                        }
                        break;
                    case 'listcountry':
                        $country_value = self::get_country($post_id, $form_id, $field_id);
                        if ($display == "value") {
                            return $country_value;
                        } elseif ($display == "label_value") {
                            return $label . ": " . $country_value;
                        }
                        break;
                    case 'file_upload':
                        $fileUPload = self::get_file_upload($post_id, $form_id, $field_id);
                        if ($display == "value") {
                            return $fileUPload;
                        } elseif ($display == "label_value") {
                            return $label . ": " . $fileUPload;
                        }
                        break;
                    case 'table_editor':
                        return self::get_table_editor($post_id, $form_id, $field_id);
                        break;
                    case 'checkbox':
                        return self::get_checkbox($post_id, $form_id, $post_id, $field_id, $display, $label, $option);
                        break;
                    case 'listcheckbox':
                        return self::get_listcheckbox($type, $post_id, $form_id, $field_id, $display, $label, $option);
                        break;
                    case 'listradio':
                        return self::get_radio($post_id, $form_id, $field_id, $display, $label, $option);
                        break;
                    case 'liststate':
                        return self::get_state($post_id, $form_id, $field_id, $display, $label);
                        break;
                    default:

                        $value = self::genarate_all_value($post_id, $form_id, $field_id);
                       
                        $value = apply_filters("esig_ninja_value_generate", $value, $post_id, $form_id, $field_id, $type);

                        if (is_array($value)) {
                            return self::generate_array_value($type, $label, $field_id, $display, $value);
                        }

                        if ($type == "email") {
                            return self::displayEmail($value, $display, $label, $document_id);
                        }
                       
                        if ($display == "value") {
                            return $value;
                        } elseif ($display == "label_value") {
                            return $label . ": " . $value;
                        }
                }
            } else {

                $nf_value = Ninja_Forms()->sub($post_id)->get_field(absint($field_id));
                return nf_wp_kses_post_deep($nf_value);
            }
        }

        private static function generate_array_value($fieldType, $label, $field_id, $displayType, $value)
        {
            $result = "";

            global $esig_ninja_form;

        //    $label = self::get_field_label($field_id);

            if ($fieldType == "listcheckbox" || $fieldType == "listmultiselect") {

                if (!is_null($esig_ninja_form)) {
                    $fields = $esig_ninja_form->get_field($field_id);
                } else {
                    $fields = Ninja_Forms()->form()->get_field($field_id);
                }
                // print_r($fields);
                $options = $fields->get_setting('options');

              //  $label = self::get_field_label($field_id);
                foreach ($options as $option) {

                    if ($displayType == "value") {
                        $subValue = $option['value'];
                        if (in_array($subValue, $value)) {
                            $result .= $subValue . ', ';
                        }
                    }

                    // Display type label value 
                    if ($displayType == "label_value") {
                        $subValue = $option['value'];
                        $subLabel = $option['label'];

                        if (in_array($subValue, $value)) {
                            $result .= $subLabel . ": " . $subValue . ', ';
                        }
                    }

                    if ($displayType == "label") {
                        $subValue = $option['value'];
                        $subLabel = $option['label'];
                        if (in_array($subValue, $value)) {
                            $result .= $subLabel . ', ';
                        }
                    }

                    /* if ($displayType == "label_value") {

                      $subValue = $option['value'];
                      // $subLabel = $option['label'];
                      if (in_array($subValue, $value)) {
                      $result .=  $subValue . ', ';
                      }

                      } */
                }
                if ($displayType == "label_value") {
                    $result = $result;
                }
                return substr($result, 0, strlen($result) - 2); //rtrim($result, ',');
            } 
            
            elseif($fieldType == "date"){

                $count = 1 ;
                foreach($value as  $subValue)
                {
                  
                    if($count == '1'){
                        if($subValue ){
                        $result .= $subValue . " / ";
                        } 
                    }elseif($count == '2'){
                        if($subValue ){
                        $result .= $subValue . ":";
                        }
                    }elseif($count == '3'){
                        if($subValue ){
                        $result .= $subValue . " ";
                        }
                    }elseif($count == '4'){
                        if($subValue ){
                        $result .= $subValue . ", ";
                        }
                    }
                    
                    $count ++ ;
                }

               

                if($displayType == "value") return rtrim($result, ', ');
                if($displayType == "label_value") return $label . ": " . rtrim($result, ', ');

            }  
            
            else {
                foreach($value as  $subValue)
                {
                    $result .= $subValue . ", ";
                }
                if($displayType == "value") return rtrim($result, ', ');
                if($displayType == "label_value") return $label . ": " . rtrim($result, ', ');
            }
        }

        private static function get_field_id_by_key($field_key, $form_id)
        {
            global $wpdb;

            $field_id = $wpdb->get_var("SELECT id FROM {$wpdb->prefix}nf3_fields WHERE `key` = '{$field_key}' AND `parent_id` = {$form_id}");

            return $field_id;
        }

        private static function is_serialized($value, &$result = null)
        {
            // Bit of a give away this one
            if (!is_string($value) || empty($value)) {
                return false;
            }
            // Serialized false, return true. unserialize() returns false on an
            // invalid string or it could return false if the string is serialized
            // false, eliminate that possibility.
            if ($value === 'b:0;') {
                $result = false;
                return true;
            }
            $length = strlen($value);
            $end = '';
            switch ($value[0]) {
                case 's':
                    if ($value[$length - 2] !== '"') {
                        return false;
                    }
                case 'b':
                case 'i':
                case 'd':
                    // This looks odd but it is quicker than isset()ing
                    $end .= ';';
                case 'a':
                case 'O':
                    $end .= '}';
                    if ($value[1] !== ':') {
                        return false;
                    }
                    switch ($value[2]) {
                        case 0:
                        case 1:
                        case 2:
                        case 3:
                        case 4:
                        case 5:
                        case 6:
                        case 7:
                        case 8:
                        case 9:
                            break;
                        default:
                            return false;
                    }
                case 'N':
                    $end .= ';';
                    if ($value[$length - 1] !== $end[0]) {
                        return false;
                    }
                    break;
                default:
                    return false;
            }
            if (($result = @unserialize($value)) === false) {
                $result = null;
                return false;
            }
            return true;
        }

        public static function ninja_submission($post_id)
        {


            $dataArray = wp_cache_get("esig_ninja_submission_" . $post_id, ESIG_CACHE_GROUP);
            if (false !== $dataArray) {
                return $dataArray;
            }

            $docId = WP_E_Sig()->meta->metadata_by_keyvalue('esig_ninja_entry_id', $post_id);

            $dataArray = $dataArray = WP_E_Sig()->meta->get($docId, 'esig_ninja_submission_value');

            wp_cache_set("esig_ninja_submission_" . $post_id, $dataArray, ESIG_CACHE_GROUP);

            return $dataArray;
        }

        public static function genarate_all_value($post_id, $form_id, $field_id)
        {


            $dataArray = self::ninja_submission($post_id);
            
            $field_id = (is_numeric($field_id)) ? $field_id : self::get_field_id_by_key($field_id, $form_id);

            $field = '_field_' . $field_id;

            
            if ($dataArray) {

                $data = unserialize($dataArray);
                // print_r($data);
                $value = esig_nfds_get($field, $data);
                
                if(!is_array($value)) return false;

                if (self::is_serialized($value[0])) {
                    return unserialize($value[0]);
                }
                return $value[0];
            } else {
                $submission = new NF_Database_Models_Submission($post_id, $form_id);
                $nf_value = $submission->get_field_value($field_id);
                return $nf_value;
            }
        }

        public static function display_value($notification_id, $form_id, $value)
        {

            global $esig_ninja_form, $esigUnderline_data;
            if (is_null($esig_ninja_form)) {
                $esig_ninja_form = Ninja_Forms()->form($form_id);
            }

            if (self::is_ninja_three()) {
                if (is_null($esigUnderline_data)) {
                    $esigUnderline_data = $esig_ninja_form->get_action($notification_id)->get_settings('underline_data');
                }
               
            } else {
                $esigUnderline_data = Ninja_Forms()->notification($notification_id)->get_setting('underline_data');
            }
            $result = '';
            if ($esigUnderline_data == "underline") {
                if (is_array($value)) {
                    foreach ($value as $val) {
                        $result .= '<u>' . $val . '</u><br/>';
                    }
                } else {
                    $result = '<u>' . $value . '</u>';
                }
            } else {
                if (is_array($value)) {
                    foreach ($value as $val) {
                        $result .= $val . '<br/>';
                    }
                } else {
                    $result = $value;
                }
            }
            return $result;
        }

        /**
         *  Used for ninja form 2.0 version 
         * @param type $invite_hash
         * @param type $document_checksum
         */
        public static function save_invite_url($invite_hash, $document_checksum)
        {
            $invite_url = WP_E_Invite::get_invite_url($invite_hash, $document_checksum);
            esig_setcookie(self::ESIG_NF_COOKIE, $invite_url, 600);
            $_COOKIE[self::ESIG_NF_COOKIE] = $invite_url;
            self::$tempCookie = $invite_url;
        }

        public static function remove_invite_url()
        {
            setcookie(self::ESIG_NF_COOKIE, null, time() - YEAR_IN_SECONDS, COOKIEPATH, COOKIE_DOMAIN);
        }

        public static function get_invite_url()
        {
            if (!empty(self::$tempCookie)) {
                return self::$tempCookie;
            }
            return ESIG_COOKIE(self::ESIG_NF_COOKIE);
        }

        public static function nf_get_invite_url($invite_hash)
        {
            $document_checksum = WP_E_Sig()->document->document_checksum_by_id(WP_E_Sig()->invite->getdocumentid_By_invitehash($invite_hash));
            return WP_E_Sig()->invite->get_invite_url($invite_hash, $document_checksum);
        }

        public static function nf_next_agreement_link()
        {
            $temp_data = self::get_temp_settings();
            //$t_data = krsort($temp_data);
            foreach ($temp_data as $invite => $data) {
                if ($data['signed'] == "no") {
                    return self::nf_get_invite_url($invite);
                }
            }
            return false;
        }

        /**
         * Define ninja temp settings for multi ninja agreement redirection 
         * @return boolean
         */
        public static function get_temp_settings()
        {
            if (!empty(self::$tempCookie)) {
                return json_decode(stripslashes(self::$tempCookie), true);
            }
            if (ESIG_COOKIE(self::ESIG_NF_COOKIE)) {
                return json_decode(stripslashes(ESIG_COOKIE(self::ESIG_NF_COOKIE)), true);
            }
            return false;
        }

        public static function save_temp_settings($value)
        {
            $json = json_encode($value);
            esig_setcookie(self::ESIG_NF_COOKIE, $json, 600);
            // for instant cookie load. 
            $_COOKIE[self::ESIG_NF_COOKIE] = $json;
            self::$tempCookie = $json;
        }

        public static function delete_temp_settings()
        {
            esig_unsetcookie(self::ESIG_NF_COOKIE);
        }

        public static function is_nf_esign_required()
        {
            if (self::get_temp_settings()) {
                return true;
            } else {
                return false;
            }
        }

        public static function save_esig_nf_meta($meta_key, $meta_index, $meta_value, $entry_id = false)
        {

            $temp_settings = self::get_temp_settings();
            if (!$temp_settings) {
                $temp_settings = array();
                $temp_settings[$meta_key] = array($meta_index => $meta_value);
                $temp_settings["entry_id"] = $entry_id;
                // finally save slv settings . 
                self::save_temp_settings($temp_settings);
            } else {

                if (array_key_exists($meta_key, $temp_settings)) {
                    $temp_settings[$meta_key][$meta_index] = $meta_value;
                    if ($entry_id) {
                        $temp_settings["entry_id"] = $entry_id;
                    }
                    self::save_temp_settings($temp_settings);
                } else {

                    if (!empty($entry_id) && !array_key_exists($entry_id, $temp_settings)) {

                        $oldEntryId = esig_nfds_sanitize_init(esig_nfds_get("entry_id", $temp_settings));
                        if (empty($oldEntryId)) {
                            unset($temp_settings);
                            self::delete_temp_settings();
                        }
                        if (!empty($oldEntryId) && $oldEntryId != $entry_id) {
                            unset($temp_settings);
                            self::delete_temp_settings();
                        }
                    }
                    $temp_settings[$meta_key] = array($meta_index => $meta_value);
                    if ($entry_id) {
                        $temp_settings["entry_id"] = $entry_id;
                    }
                    self::save_temp_settings($temp_settings);
                }
            }
        }

        public static function isSameSubmission($tempSettings, $signerEmail)
        {
            $ret = true;

            // we can remove this method checking after couple of release once the method is available to all user. 
            if (!method_exists("WP_E_Signer", "getSignerEmail")) {
                return $ret;
            }

            foreach ($tempSettings as $key => $value) {
                $signerId = WP_E_Sig()->invite->getuserid_By_invitehash($key);
                $existingSignerEmail = WP_E_Sig()->signer->getSignerEmail($signerId);
                if ($existingSignerEmail != $signerEmail) {
                    $ret = false;
                    break;
                }
            }
            return $ret;
        }

        public static function get_esig_nf_meta($meta_key, $meta_index)
        {
            $temp_settings = self::get_temp_settings();

            if (is_array($temp_settings)) {
                if (!array_key_exists($meta_key, $temp_settings)) {
                    return false;
                }
                if (array_key_exists($meta_index, $temp_settings[$meta_key])) {
                    return $temp_settings[$meta_key][$meta_index];
                }
            }
            return false;
        }

        public static function is_ninja_requested_agreement($document_id)
        {
            $nf_form_id = WP_E_Sig()->meta->get($document_id, 'esig_ninja_form_id');
            $nf_entry_id = WP_E_Sig()->meta->get($document_id, 'esig_ninja_entry_id');
            if ($nf_form_id && $nf_entry_id) {
                return true;
            }
            return false;
        }

        public static function page_title($page_id)
        {
            global $wpdb;
            return $wpdb->get_var($wpdb->prepare("SELECT post_title FROM $wpdb->posts WHERE ID = %d LIMIT 1", $page_id));
        }

        public static function get_sad_option()
        {

            if (!function_exists("WP_E_Sig")) {
                return array('label' => __('Select an agreement page', 'esig'), 'value' => '');
            }

            global $wpdb;
            $options = array();
            $table_name = $wpdb->prefix . 'esign_documents_stand_alone_docs';


            $sad_pages = $wpdb->get_results("SELECT page_id, document_id FROM {$table_name}", OBJECT);
            $options[] = array('label' => __('Select an agreement page', 'esig'), 'value' => '');

            foreach ($sad_pages as $page) {

                if (self::page_title($page->page_id)) {
                    $options[] = array('label' => self::page_title($page->page_id), 'value' => $page->page_id);
                }
            }


            return $options;
        }

        
        public static function get_sub_id($data)
        {
            if (array_key_exists('actions', $data)) {
                return $data['actions']['save']['sub_id'];
            }
            return false;
        }

        /**
         * Generate ninja form list option version wise . 
         * @return string
         */
        public static function ninja_form_option()
        {

            $options = '';

            if (self::is_ninja_three()) {

                $forms = Ninja_Forms()->form()->get_forms();
                foreach ($forms as $form) {
                    $options .= '<option value="' . $form->get_id() . '">' . $form->get_setting('title') . '</option>';
                }
            } else {

                $nf_forms = new NF_Forms;
                $ninja_forms = $nf_forms->get_all();


                foreach ($ninja_forms as $form_id) {
                    $title = Ninja_Forms()->form($form_id)->get_setting('form_title');

                    $options .= '<option value="' . $form_id . '">' . $title . '</option>';
                }
            }
            return $options;
        }

        /**
         * Generate fields option using form id
         * @param type $form_id
         * @return string
         */
        public static function ninja_form_fields($form_id)
        {
            $html = '';
            if (self::is_ninja_three()) {
                $fields = Ninja_Forms()->form($form_id)->get_fields();

                foreach ($fields as $field) {
                    if ($field->get_setting('type') == 'submit')
                        continue;
                    $html .= '<option data-id='.$field->get_setting('label') .' value="' . $field->get_id() . '">' . $field->get_setting('label') . '</option>';
                }
            } else {
                $all = Ninja_Forms()->form($form_id);
                foreach ($all->fields as $fields) {
                    if ($fields['data']['label'] == 'Submit') {

                        continue;
                    }
                    //$field_name = $fields['data']['label'];
                    $html .= '<option value=" ' . $fields['id'] . ' ">' . $fields['data']['label'] . '</option>';
                }
            }
            return $html;
        }

        public static function generate_reminder_date()
        {

            $options = array();
            for ($i = 1; $i < 32; $i++) {
                $value = ($i == 1) ? "yes" : $i;
                $options[] = array('label' => $i . " Days", "value" => $value);
            }

            return $options;
        }

        /**
         * Return a document Id  
         */

        public static function docId()
        {
            global $esig_ninja_document_id;

            if (!is_null($esig_ninja_document_id)) return $esig_ninja_document_id;

            if (function_exists('WP_E_Sig')) {
                $csum = ESIG_GET('csum');
            } else {
                $csum = (isset($_GET['csum'])) ? esig_nfds_get($_GET['csum']) : false;
            }

            if (empty($csum)) {
                $document_id = get_option('esig_global_document_id');
            } else {
                $document_id = WP_E_Sig()->document->document_id_by_csum($csum);
            }

            return $document_id;
        }

        public static function ninjaNotificationId($docId)
        {
            global $esig_ninja_notification_id;
            if (!is_null($esig_ninja_notification_id)) return $esig_ninja_notification_id;
            return WP_E_Sig()->meta->get($docId, 'esig_ninja_notification_id');
        }

        public static function ninjaEntryId($docId)
        {
           
            global $esig_ninja_entry_id;
            if (!is_null($esig_ninja_entry_id)) return $esig_ninja_entry_id;
            return WP_E_Sig()->meta->get($docId, 'esig_ninja_entry_id');
        }

        public static function ninjaFormId($docId)
        {
           
            global $esig_ninja_form_id;
            if (!is_null($esig_ninja_form_id)) return $esig_ninja_form_id;
            return WP_E_Sig()->meta->get($docId, 'esig_ninja_form_id');
        }

        public static function generate_signer_name( $form_id ) {
            $name_fields = array();
    
            $fields = Ninja_Forms()->form( $form_id )->get_fields();
            
            foreach ( $fields as $field ) {
                if ( $field->get_setting( 'type' ) === 'textbox' || $field->get_setting( 'type' ) === 'firstname' || $field->get_setting( 'type' ) === 'lastname') {
                    $name_fields[] = array(
                        'label' => $field->get_setting( 'label' ),
                        'value' => '{'.$field->get_setting( 'key' ).'}',
                    );

                    if($field->get_setting( 'type' ) === 'firstname'){
                        $first_name_label = $field->get_setting( 'label' );
                        $first_name_value = '{'.$field->get_setting( 'key' ).'}';
                    }elseif($field->get_setting( 'type' ) === 'lastname'){
                        $last_name_label = $field->get_setting( 'label' );
                        $last_name_value = '{'.$field->get_setting( 'key' ).'}';
                    }

                }

                
            }

            if ( $first_name_value && $last_name_value ) {
                $name_fields[] = array(
                    'label' => $first_name_label . ' '. $last_name_label,
                    'value' => $first_name_value . ' '  . $last_name_value,
                );
            }
            
            return $name_fields;
        }

        
    }


endif;
